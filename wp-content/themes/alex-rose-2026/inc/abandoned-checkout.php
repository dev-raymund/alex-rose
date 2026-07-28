<?php
/**
 * Automatic abandoned-checkout recovery email.
 *
 * WooCommerce does not track abandoned checkouts on its own (no order is
 * created), so this module:
 *   1. Captures the shopper's email + cart the moment they enter it on the
 *      checkout page (via WooCommerce's built-in order-review AJAX — no extra
 *      front-end JS required).
 *   2. Stores it in a small queue table as `pending`.
 *   3. A WP-Cron task, running every 15 minutes, emails anyone whose checkout
 *      has been idle for the configured delay (default 1 hour) and has not
 *      been converted — exactly once.
 *   4. Completing an order marks that shopper `converted`, so no email is sent.
 *
 * SAFETY: the whole feature is OFF until you opt in. Enable it only after you
 * have confirmed outgoing mail works (WP Mail SMTP) and are comfortable with
 * the UK PECR / GDPR position on cart-recovery email. Enable with either:
 *
 *     define('AR_ABANDONED_CHECKOUT_ENABLED', true);   // in wp-config.php
 *   or
 *     add_filter('alex_rose_2026_abandoned_checkout_enabled', '__return_true');
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/* -------------------------------------------------------------------------
 * Configuration
 * ------------------------------------------------------------------------- */

/**
 * Master switch. Everything (capture, cron, sending) is disabled until this
 * returns true. Default false so the feature cannot act on real customers
 * before it has been reviewed and mail delivery confirmed.
 */
function alex_rose_2026_abandoned_enabled(): bool {
	$enabled = defined('AR_ABANDONED_CHECKOUT_ENABLED') && AR_ABANDONED_CHECKOUT_ENABLED;
	return (bool) apply_filters('alex_rose_2026_abandoned_checkout_enabled', $enabled);
}

/**
 * How long a checkout must sit idle before we email (seconds). Default 1 hour.
 */
function alex_rose_2026_abandoned_delay(): int {
	return (int) apply_filters('alex_rose_2026_abandoned_delay', HOUR_IN_SECONDS);
}

/**
 * Ignore checkouts older than this (seconds) — stale carts are not worth
 * chasing and the discount will have moved on. Default 7 days.
 */
function alex_rose_2026_abandoned_max_age(): int {
	return (int) apply_filters('alex_rose_2026_abandoned_max_age', 7 * DAY_IN_SECONDS);
}

/**
 * Stop sending after the founding-discount campaign ends. After this instant
 * no recovery emails go out (the code is dead). Filterable / can be pushed out.
 * Interpreted in the site timezone.
 */
function alex_rose_2026_abandoned_campaign_end(): int {
	$default = '2026-07-31 23:59:59';
	$raw     = (string) apply_filters('alex_rose_2026_abandoned_campaign_end', $default);
	$ts      = strtotime($raw . ' ' . wp_timezone_string());
	return $ts ? (int) $ts : PHP_INT_MAX;
}

/**
 * The discount code to offer in the recovery email. We do not know the
 * referral source at checkout, so the general founding code is the default.
 */
function alex_rose_2026_abandoned_code(): string {
	return (string) apply_filters('alex_rose_2026_abandoned_code', 'EARLY20');
}

/**
 * Queue table name.
 */
function alex_rose_2026_abandoned_table(): string {
	global $wpdb;
	return $wpdb->prefix . 'ar_abandoned_checkouts';
}

/* -------------------------------------------------------------------------
 * Schema
 * ------------------------------------------------------------------------- */

const ALEX_ROSE_2026_ABANDONED_DB_VERSION = 1;

/**
 * Create / update the queue table. Runs once per schema version.
 */
function alex_rose_2026_abandoned_install(): void {
	$installed = (int) get_option('ar_abandoned_db_version', 0);
	if ($installed === ALEX_ROSE_2026_ABANDONED_DB_VERSION) {
		return;
	}

	global $wpdb;
	$table   = alex_rose_2026_abandoned_table();
	$collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		email varchar(191) NOT NULL,
		first_name varchar(100) NOT NULL DEFAULT '',
		cart_hash varchar(64) NOT NULL DEFAULT '',
		cart_contents longtext NULL,
		cart_total decimal(12,2) NOT NULL DEFAULT 0,
		currency varchar(10) NOT NULL DEFAULT '',
		status varchar(20) NOT NULL DEFAULT 'pending',
		token varchar(64) NOT NULL DEFAULT '',
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		sent_at datetime NULL,
		recovered_at datetime NULL,
		PRIMARY KEY  (id),
		KEY email (email),
		KEY status (status),
		KEY updated_at (updated_at)
	) {$collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);

	update_option('ar_abandoned_db_version', ALEX_ROSE_2026_ABANDONED_DB_VERSION);
}
add_action('after_switch_theme', 'alex_rose_2026_abandoned_install');
add_action('init', 'alex_rose_2026_abandoned_install', 1);

/* -------------------------------------------------------------------------
 * Capture — fired by WooCommerce's checkout order-review AJAX. The serialized
 * checkout form (post_data) carries billing_email once the shopper types it.
 * ------------------------------------------------------------------------- */

add_action('woocommerce_checkout_update_order_review', 'alex_rose_2026_abandoned_capture');

function alex_rose_2026_abandoned_capture($post_data): void {
	if (! alex_rose_2026_abandoned_enabled() || ! function_exists('WC')) {
		return;
	}

	$fields = array();
	parse_str((string) $post_data, $fields);

	$email = isset($fields['billing_email']) ? sanitize_email(wp_unslash($fields['billing_email'])) : '';
	if ($email === '' || ! is_email($email)) {
		return;
	}
	$first = isset($fields['billing_first_name']) ? sanitize_text_field(wp_unslash($fields['billing_first_name'])) : '';

	$cart = WC()->cart;
	if (! $cart || $cart->is_empty()) {
		return;
	}

	// Snapshot the cart for the email + analytics.
	$items = array();
	foreach ($cart->get_cart() as $line) {
		$product = isset($line['data']) ? $line['data'] : null;
		$items[] = array(
			'name'  => $product ? $product->get_name() : '',
			'qty'   => isset($line['quantity']) ? (int) $line['quantity'] : 1,
			'total' => isset($line['line_total']) ? (float) $line['line_total'] : 0,
		);
	}

	$snapshot = wp_json_encode($items);
	$total    = (float) $cart->get_total('edit');
	$currency = function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : '';
	$hash     = method_exists($cart, 'get_cart_hash') ? (string) $cart->get_cart_hash() : md5($snapshot);
	$now      = current_time('mysql');

	global $wpdb;
	$table = alex_rose_2026_abandoned_table();

	// Most recent row for this email.
	$existing = $wpdb->get_row(
		$wpdb->prepare("SELECT id, status, cart_hash FROM {$table} WHERE email = %s ORDER BY id DESC LIMIT 1", $email)
	);

	if ($existing) {
		// Already dealt with (converted, or emailed for this same cart) — do nothing.
		if ($existing->status === 'converted') {
			return;
		}
		if ($existing->status === 'sent' && $existing->cart_hash === $hash) {
			return;
		}
		if ($existing->status === 'pending') {
			$wpdb->update(
				$table,
				array(
					'first_name'    => $first,
					'cart_hash'     => $hash,
					'cart_contents' => $snapshot,
					'cart_total'    => $total,
					'currency'      => $currency,
					'updated_at'    => $now,
				),
				array('id' => (int) $existing->id),
				array('%s', '%s', '%s', '%f', '%s', '%s'),
				array('%d')
			);
			return;
		}
	}

	$wpdb->insert(
		$table,
		array(
			'email'         => $email,
			'first_name'    => $first,
			'cart_hash'     => $hash,
			'cart_contents' => $snapshot,
			'cart_total'    => $total,
			'currency'      => $currency,
			'status'        => 'pending',
			'token'         => wp_hash($email . '|' . $now),
			'created_at'    => $now,
			'updated_at'    => $now,
		),
		array('%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s')
	);
}

/* -------------------------------------------------------------------------
 * Conversion — a completed order cancels any outstanding recovery email.
 * ------------------------------------------------------------------------- */

add_action('woocommerce_checkout_order_processed', 'alex_rose_2026_abandoned_mark_converted');
add_action('woocommerce_thankyou', 'alex_rose_2026_abandoned_mark_converted');

function alex_rose_2026_abandoned_mark_converted($order_id): void {
	if (! function_exists('wc_get_order')) {
		return;
	}
	$order = wc_get_order($order_id);
	if (! $order) {
		return;
	}
	$email = sanitize_email($order->get_billing_email());
	if ($email === '' || ! is_email($email)) {
		return;
	}

	global $wpdb;
	$table = alex_rose_2026_abandoned_table();
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$table} SET status = 'converted', recovered_at = %s WHERE email = %s AND status IN ('pending','sent')",
			current_time('mysql'),
			$email
		)
	);
}

/* -------------------------------------------------------------------------
 * Cron — send recovery emails.
 * ------------------------------------------------------------------------- */

add_filter('cron_schedules', static function (array $schedules): array {
	if (! isset($schedules['ar_fifteen_minutes'])) {
		$schedules['ar_fifteen_minutes'] = array(
			'interval' => 15 * MINUTE_IN_SECONDS,
			'display'  => __('Every 15 minutes (Alex Rose)', 'alex-rose-2026'),
		);
	}
	return $schedules;
});

add_action('init', static function (): void {
	$hook = 'alex_rose_2026_abandoned_cron';
	if (alex_rose_2026_abandoned_enabled()) {
		if (! wp_next_scheduled($hook)) {
			wp_schedule_event(time() + MINUTE_IN_SECONDS, 'ar_fifteen_minutes', $hook);
		}
	} elseif (wp_next_scheduled($hook)) {
		wp_clear_scheduled_hook($hook);
	}
});

add_action('alex_rose_2026_abandoned_cron', 'alex_rose_2026_abandoned_process');

function alex_rose_2026_abandoned_process(): void {
	if (! alex_rose_2026_abandoned_enabled()) {
		return;
	}
	if (time() > alex_rose_2026_abandoned_campaign_end()) {
		return; // Discount has expired — nothing worth sending.
	}

	global $wpdb;
	$table = alex_rose_2026_abandoned_table();

	$idle_cutoff = gmdate('Y-m-d H:i:s', current_time('timestamp') - alex_rose_2026_abandoned_delay());
	$age_cutoff  = gmdate('Y-m-d H:i:s', current_time('timestamp') - alex_rose_2026_abandoned_max_age());
	$batch       = (int) apply_filters('alex_rose_2026_abandoned_batch', 25);

	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$table}
			 WHERE status = 'pending'
			   AND sent_at IS NULL
			   AND updated_at <= %s
			   AND created_at >= %s
			 ORDER BY id ASC
			 LIMIT %d",
			$idle_cutoff,
			$age_cutoff,
			$batch
		)
	);

	if (! $rows) {
		return;
	}

	$now = current_time('mysql');
	foreach ($rows as $row) {
		// Honour unsubscribe / suppression.
		if (alex_rose_2026_abandoned_is_suppressed($row->email)) {
			$wpdb->update($table, array('status' => 'converted', 'updated_at' => $now), array('id' => (int) $row->id), array('%s', '%s'), array('%d'));
			continue;
		}

		$sent = alex_rose_2026_abandoned_send_email($row);

		// Mark sent regardless, so a hard-failing address is not retried forever.
		$wpdb->update(
			$table,
			array('status' => 'sent', 'sent_at' => $now, 'updated_at' => $now),
			array('id' => (int) $row->id),
			array('%s', '%s', '%s'),
			array('%d')
		);
		unset($sent);
	}
}

/* -------------------------------------------------------------------------
 * Email
 * ------------------------------------------------------------------------- */

function alex_rose_2026_abandoned_send_email(object $row): bool {
	$email = sanitize_email((string) $row->email);
	if ($email === '' || ! is_email($email)) {
		return false;
	}

	$first = trim((string) $row->first_name);

	$gold     = '#c8a96a';
	$ink      = '#111111';
	$site      = 'alexrose.uk';
	$site_url  = 'https://alexrose.uk';
	$support   = 'harold@alexrose.uk';
	$address   = '2A Rodley Lane, Rodley, Leeds LS13 1HU';
	$brand     = esc_html__('Alex Rose Fine Tailoring', 'alex-rose-2026');

	$unsub_url    = alex_rose_2026_abandoned_unsub_url($email, (string) $row->token);
	$site_link    = '<a href="' . esc_url($site_url) . '" style="color:' . $gold . ';text-decoration:none;">alexrose.uk</a>';
	$support_link = '<a href="mailto:' . esc_attr($support) . '" style="color:' . $gold . ';text-decoration:none;">' . esc_html($support) . '</a>';

	$subject   = __('Your Alex Rose founding discount is still waiting', 'alex-rose-2026');
	$preheader = esc_html__('Your 20% off code expires on 31 July 2026.', 'alex-rose-2026');

	$greeting = $first !== ''
		? sprintf(esc_html__('Hi %s,', 'alex-rose-2026'), esc_html($first))
		: esc_html__('Hi there,', 'alex-rose-2026');

	// Exact copy as supplied by the client — do not paraphrase.
	$line_waiting   = esc_html__('Your exclusive Alex Rose founding discount is still waiting for you.', 'alex-rose-2026');
	$line_expires   = esc_html__('Your 20% off code expires on 31 July 2026, and we would hate for you to miss it.', 'alex-rose-2026');
	$label_general  = esc_html__('General code:', 'alex-rose-2026');
	$label_referral = esc_html__('Referral code (Brian Klimek):', 'alex-rose-2026');
	$line_howto     = sprintf(
		/* translators: %s: alexrose.uk link */
		esc_html__('Simply head to %s, design your jacket, and enter your code at checkout. You may only use one code per order.', 'alex-rose-2026'),
		$site_link
	);
	$line_help = sprintf(
		/* translators: %s: support email link */
		esc_html__('If you have any questions, reply to this email or reach Harold directly at %s. Every message is read personally.', 'alex-rose-2026'),
		$support_link
	);
	$line_hope = esc_html__('We hope to be making your jacket very soon.', 'alex-rose-2026');
	$regards   = esc_html__('Warm regards,', 'alex-rose-2026');
	$signoff   = esc_html__('Harold and the Alex Rose team', 'alex-rose-2026');
	$unsub     = esc_html__('If you would prefer not to receive these reminders, unsubscribe here.', 'alex-rose-2026');

	$p = 'margin:0 0 18px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.7;color:#333333;';

	$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
		. '<body style="margin:0;padding:0;background:#f4f2ee;">'
		. '<span style="display:none;max-height:0;overflow:hidden;opacity:0;">' . $preheader . '</span>'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f2ee;padding:32px 0;"><tr><td align="center">'
		. '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background:#ffffff;border:1px solid #e7e3da;">'
		. '<tr><td style="background:' . $ink . ';padding:26px 40px;text-align:center;">'
		. '<span style="font-family:Georgia,\'Times New Roman\',serif;font-size:22px;letter-spacing:0.28em;color:#ffffff;text-transform:uppercase;">Alex Rose</span>'
		. '</td></tr>'
		. '<tr><td style="height:3px;background:' . $gold . ';"></td></tr>'
		. '<tr><td style="padding:40px;">'
		. '<p style="' . $p . '">' . $greeting . '</p>'
		. '<p style="' . $p . '">' . $line_waiting . '</p>'
		. '<p style="' . $p . '">' . $line_expires . '</p>'
		. '<p style="' . $p . '"><strong>' . $label_general . '</strong> EARLY20<br><strong>' . $label_referral . '</strong> STLBMAN20</p>'
		. '<p style="' . $p . '">' . $line_howto . '</p>'
		. '<p style="' . $p . '">' . $line_help . '</p>'
		. '<p style="' . $p . '">' . $line_hope . '</p>'
		. '<p style="' . $p . 'margin-bottom:4px;">' . $regards . '</p>'
		. '<p style="margin:0;font-family:Georgia,\'Times New Roman\',serif;font-size:15px;color:' . $ink . ';">' . $signoff . '</p>'
		. '</td></tr>'
		. '<tr><td style="background:' . $ink . ';padding:28px 40px;text-align:center;">'
		. '<div style="font-family:Georgia,\'Times New Roman\',serif;font-size:13px;letter-spacing:0.2em;text-transform:uppercase;color:#ffffff;margin-bottom:10px;">' . $brand . '</div>'
		. '<a href="' . esc_url($site_url) . '" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:' . $gold . ';text-decoration:none;">' . esc_html($site) . '</a>'
		. '<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:rgba(255,255,255,0.5);margin-top:12px;">' . esc_html($address) . '</div>'
		. '<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;margin-top:14px;"><a href="' . esc_url($unsub_url) . '" style="color:rgba(255,255,255,0.5);text-decoration:underline;">' . $unsub . '</a></div>'
		. '</td></tr>'
		. '</table></td></tr></table></body></html>';

	$from = function_exists('alex_rose_2026_form_from_address') ? alex_rose_2026_form_from_address() : 'no-reply@alexrose.uk';

	$headers = array(
		sprintf('From: %s <%s>', 'Alex Rose Fine Tailoring', $from),
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: Harold <' . $support . '>',
	);

	return (bool) wp_mail($email, $subject, $html, $headers);
}

/* -------------------------------------------------------------------------
 * Coupon auto-apply from the recovery link
 * ------------------------------------------------------------------------- */

add_action('wp', static function (): void {
	if (is_admin() || ! function_exists('WC') || ! isset($_GET['ar_code'])) {
		return;
	}
	$code = sanitize_text_field(wp_unslash($_GET['ar_code']));
	if ($code === '') {
		return;
	}
	$cart = WC()->cart;
	if ($cart && ! $cart->is_empty() && ! $cart->has_discount($code)) {
		$cart->apply_coupon($code);
	}
});

/* -------------------------------------------------------------------------
 * Unsubscribe
 * ------------------------------------------------------------------------- */

function alex_rose_2026_abandoned_unsub_url(string $email, string $token): string {
	return add_query_arg(
		array(
			'action' => 'ar_abandoned_unsub',
			'e'      => rawurlencode($email),
			't'      => rawurlencode($token),
		),
		admin_url('admin-post.php')
	);
}

function alex_rose_2026_abandoned_is_suppressed(string $email): bool {
	$list = (array) get_option('ar_abandoned_suppressed', array());
	return in_array(md5(strtolower($email)), $list, true);
}

add_action('admin_post_nopriv_ar_abandoned_unsub', 'alex_rose_2026_abandoned_handle_unsub');
add_action('admin_post_ar_abandoned_unsub', 'alex_rose_2026_abandoned_handle_unsub');

function alex_rose_2026_abandoned_handle_unsub(): void {
	$email = isset($_GET['e']) ? sanitize_email(wp_unslash($_GET['e'])) : '';
	$token = isset($_GET['t']) ? sanitize_text_field(wp_unslash($_GET['t'])) : '';

	$ok = false;
	if ($email !== '' && is_email($email) && $token !== '') {
		global $wpdb;
		$table  = alex_rose_2026_abandoned_table();
		$stored = $wpdb->get_var(
			$wpdb->prepare("SELECT token FROM {$table} WHERE email = %s ORDER BY id DESC LIMIT 1", $email)
		);
		if ($stored && hash_equals((string) $stored, $token)) {
			$list = (array) get_option('ar_abandoned_suppressed', array());
			$key  = md5(strtolower($email));
			if (! in_array($key, $list, true)) {
				$list[] = $key;
				update_option('ar_abandoned_suppressed', $list, false);
			}
			$wpdb->query(
				$wpdb->prepare("UPDATE {$table} SET status = 'converted' WHERE email = %s AND status IN ('pending','sent')", $email)
			);
			$ok = true;
		}
	}

	$title   = $ok ? __('You have been unsubscribed', 'alex-rose-2026') : __('Link expired', 'alex-rose-2026');
	$message = $ok
		? __('You will no longer receive checkout reminders from Alex Rose. You can still return to your order at any time.', 'alex-rose-2026')
		: __('We could not verify this unsubscribe link. Please email harold@alexrose.uk and we will remove you right away.', 'alex-rose-2026');

	wp_die(
		'<h1 style="font-family:Georgia,serif;">' . esc_html($title) . '</h1><p style="font-family:Arial,sans-serif;">' . esc_html($message) . '</p>',
		esc_html($title),
		array('response' => 200)
	);
}
