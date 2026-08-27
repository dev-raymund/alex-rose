<?php
/**
 * Front-end form submission handlers.
 *
 * Wires every public form on the site to `wp_mail()` via WordPress
 * `admin-post.php`. Each registered handler:
 *   - Verifies its nonce
 *   - Rejects honeypot submissions
 *   - Validates required fields
 *   - Builds a labelled plain-text email body
 *   - Sends to the configured recipient (filter: `alex_rose_2026_form_recipient`)
 *   - Returns JSON when posted via the AJAX flag (`_ajax=1`), otherwise
 *     redirects back to the referer with a status flag.
 *
 * To change the destination address, override the filter from a child theme
 * or mu-plugin:
 *
 *     add_filter('alex_rose_2026_form_recipient', static function () {
 *         return 'someone@alexrose.uk';
 *     });
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/* -------------------------------------------------------------------------
 * Configuration helpers
 * ------------------------------------------------------------------------- */

/**
 * Default recipient for every front-end form. Override via filter.
 */
function alex_rose_2026_form_recipient(): string {
	$default = (string) get_option('admin_email', '');
	$value   = apply_filters('alex_rose_2026_form_recipient', $default);
	return is_string($value) ? trim($value) : '';
}

/**
 * The honeypot field name used across the site. A real visitor will leave
 * this empty; spam bots fill every field, so a non-empty value is the
 * signal we silently reject on.
 */
function alex_rose_2026_form_honeypot_name(): string {
	return 'ar_website_url';
}

/**
 * Print a honeypot input. The wrapper is visually hidden but kept in the
 * DOM so bots that read the HTML directly still see + fill it.
 */
function alex_rose_2026_form_honeypot_field(): void {
	$name = alex_rose_2026_form_honeypot_name();
	?>
	<div class="ar-form-hp" aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
		<label for="<?php echo esc_attr($name); ?>">Website (leave blank)</label>
		<input
			type="text"
			id="<?php echo esc_attr($name); ?>"
			name="<?php echo esc_attr($name); ?>"
			tabindex="-1"
			autocomplete="off"
			value=""
		>
	</div>
	<?php
}

/**
 * Addresses and domains blocked from every public form.
 *
 * An entry is either a full address ('spam@example.com') or a bare domain
 * ('mailinator.com'), which blocks that domain and its subdomains. To add one
 * without editing the theme, filter this list:
 *
 *   add_filter('alex_rose_2026_blocked_emails', function ($list) {
 *       $list[] = 'someone@example.com';
 *       return $list;
 *   });
 *
 * @return string[]
 */
function alex_rose_2026_blocked_emails(): array {
	$list = array(
		'melissasaldanamrkt@gmail.com',
		// 'mailinator.com',
	);

	$list = (array) apply_filters('alex_rose_2026_blocked_emails', $list);

	return array_values(array_filter(array_map(
		static function ($entry) {
			return is_string($entry) ? strtolower(trim($entry)) : '';
		},
		$list
	)));
}

/**
 * True when any address in the submission is blocked.
 *
 * Scans the whole payload rather than one named field: each form prefixes its
 * email input differently (ct_email, rcs_email, sac_email...), and spam
 * routinely repeats the address inside the message body too.
 */
function alex_rose_2026_form_has_blocked_email(): bool {
	$blocked = alex_rose_2026_blocked_emails();
	if ($blocked === array()) {
		return false;
	}

	$haystack = array();
	// Nonce is verified by the caller before this runs.
	array_walk_recursive($_POST, static function ($value) use (&$haystack) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (is_string($value)) {
			$haystack[] = strtolower(wp_unslash($value));
		}
	});

	if ($haystack === array()) {
		return false;
	}

	if (! preg_match_all('/[\w.+-]+@[\w-]+\.[\w.-]+/', implode(' ', $haystack), $matches)) {
		return false;
	}

	foreach ($matches[0] as $found) {
		$domain = substr((string) strrchr($found, '@'), 1);

		foreach ($blocked as $rule) {
			// Full address: exact match only.
			if (strpos($rule, '@') !== false) {
				if ($found === $rule) {
					return true;
				}
				continue;
			}
			// Bare domain: the domain itself, or any subdomain of it.
			if ($domain === $rule || substr($domain, -strlen('.' . $rule)) === '.' . $rule) {
				return true;
			}
		}
	}

	return false;
}

/* -------------------------------------------------------------------------
 * Helpers shared by every handler
 * ------------------------------------------------------------------------- */

/**
 * True when the current request expects a JSON response (set by the
 * shared front-end JS helper, which appends _ajax=1 to the body).
 */
function alex_rose_2026_form_is_ajax_request(): bool {
	if (wp_doing_ajax()) {
		return true;
	}
	if (isset($_POST['_ajax']) && (string) $_POST['_ajax'] === '1') { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return true;
	}
	$requested_with = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? (string) $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
	if (strtolower($requested_with) === 'xmlhttprequest') {
		return true;
	}
	$accept = isset($_SERVER['HTTP_ACCEPT']) ? (string) $_SERVER['HTTP_ACCEPT'] : '';
	return $accept !== '' && stripos($accept, 'application/json') !== false;
}

/**
 * Read a posted string field (single value) and trim it. Returns '' when
 * missing so callers can compare with === ''.
 */
function alex_rose_2026_form_field(string $name): string {
	if (! isset($_POST[ $name ])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return '';
	}
	$raw = wp_unslash($_POST[ $name ]); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	if (is_array($raw)) {
		return '';
	}
	return trim((string) $raw);
}

/**
 * Read a posted string array (e.g. checkboxes / repeated hidden inputs).
 *
 * @return string[]
 */
function alex_rose_2026_form_field_list(string $name): array {
	if (! isset($_POST[ $name ])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return array();
	}
	$raw = wp_unslash($_POST[ $name ]); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	if (! is_array($raw)) {
		return array();
	}
	$out = array();
	foreach ($raw as $value) {
		if (is_scalar($value)) {
			$value = trim((string) $value);
			if ($value !== '') {
				$out[] = $value;
			}
		}
	}
	return $out;
}

/**
 * Loose check that a postcode looks like a UK postcode.
 *
 * The prepaid-box / post-a-jacket services are UK delivery only. Those forms
 * carry no country field, so the postcode is the location signal we gate on:
 * an overseas postcode (e.g. a 4-digit code) must not be accepted, otherwise
 * the tailor is emailed a box request that cannot be fulfilled. Accepts the
 * postcode with or without the internal space and in any case.
 */
function alex_rose_2026_is_uk_postcode(string $postcode): bool {
	$postcode = preg_replace('/\s+/', ' ', strtoupper(trim($postcode)));
	if ($postcode === '') {
		return false;
	}
	return (bool) preg_match('/^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/', $postcode);
}

/**
 * Send a success/failure response back to the form, choosing JSON or a
 * redirect based on whether the submission was made over AJAX.
 *
 * @param array<string, mixed> $payload
 */
function alex_rose_2026_form_respond(bool $ok, string $action, string $message, array $payload = array()): void {
	$status   = $ok ? 200 : 422;
	$response = array(
		'ok'      => $ok,
		'action'  => $action,
		'message' => $message,
	);
	if ($payload !== array()) {
		$response = array_merge($response, $payload);
	}

	if (alex_rose_2026_form_is_ajax_request()) {
		wp_send_json($response, $status);
		return;
	}

	$referer = wp_get_referer();
	if (! $referer) {
		$referer = home_url('/');
	}
	$status_flag = $ok ? 'sent' : 'error';
	$redirect    = add_query_arg(
		array(
			'ar_form'   => sanitize_key($action),
			'ar_status' => $status_flag,
		),
		$referer
	);
	wp_safe_redirect($redirect);
	exit;
}

/**
 * Standard validation: nonce check + honeypot check + blocked-sender check. On
 * failure, responds (JSON or redirect) and exits — handlers can call this and
 * assume the request is sane afterwards.
 */
function alex_rose_2026_form_guard(string $action, string $nonce_action, string $nonce_field): void {
	$nonce = isset($_POST[ $nonce_field ]) ? sanitize_text_field(wp_unslash((string) $_POST[ $nonce_field ])) : '';
	if (! $nonce || ! wp_verify_nonce($nonce, $nonce_action)) {
		alex_rose_2026_form_respond(false, $action, __('Your session has expired. Please reload the page and try again.', 'alex-rose-2026'));
	}

	$hp = alex_rose_2026_form_field(alex_rose_2026_form_honeypot_name());
	if ($hp !== '') {
		alex_rose_2026_form_respond(true, $action, __('Thank you.', 'alex-rose-2026'));
	}

	// Blocked sender. Reports success like the honeypot does: telling someone
	// they are filtered just prompts them to switch address.
	if (alex_rose_2026_form_has_blocked_email()) {
		alex_rose_2026_form_respond(true, $action, __('Thank you.', 'alex-rose-2026'));
	}
}

/**
 * Build a "Label: value" plain-text body from an ordered list of pairs.
 *
 * @param array<int, array{label:string, value:string|string[]}> $fields
 */
function alex_rose_2026_form_build_body(array $fields, string $intro = ''): string {
	$lines = array();
	if ($intro !== '') {
		$lines[] = $intro;
		$lines[] = '';
	}

	foreach ($fields as $row) {
		$label = isset($row['label']) ? (string) $row['label'] : '';
		$value = $row['value'] ?? '';

		if (is_array($value)) {
			$value = array_values(array_filter(array_map('strval', $value), static function ($v) {
				return $v !== '';
			}));
			$value = $value === array() ? '—' : implode("\n  - ", array_merge(array(''), $value));
		} else {
			$value = (string) $value;
			if ($value === '') {
				$value = '—';
			}
		}

		$lines[] = $label . ': ' . $value;
	}

	$lines[] = '';
	$lines[] = '—';
	$lines[] = sprintf(
		/* translators: %s: site URL */
		__('Sent from %s', 'alex-rose-2026'),
		home_url('/')
	);

	return implode("\n", $lines);
}

/**
 * Pick a deliverable From address. Most SMTP servers (including PHPMailer's
 * own validator) reject single-label hosts like "localhost", so when the
 * site URL has no dot we fall back to the admin_email's domain. Filterable
 * via `alex_rose_2026_form_from_address`.
 */
function alex_rose_2026_form_from_address(): string {
	$site_host = (string) wp_parse_url(home_url('/'), PHP_URL_HOST);
	$site_host = preg_replace('/^www\./i', '', $site_host);

	$candidate = '';
	if ($site_host !== '' && strpos($site_host, '.') !== false) {
		$candidate = 'no-reply@' . $site_host;
	} else {
		$admin = (string) get_option('admin_email', '');
		if ($admin !== '' && strpos($admin, '@') !== false) {
			$candidate = 'no-reply@' . substr($admin, strpos($admin, '@') + 1);
		}
	}

	if ($candidate === '' || ! is_email($candidate)) {
		$candidate = 'no-reply@alexrose.uk';
	}

	$value = apply_filters('alex_rose_2026_form_from_address', $candidate);
	return is_string($value) && is_email($value) ? $value : $candidate;
}

/**
 * Compose + send an email via wp_mail. Always returns the wp_mail result.
 */
function alex_rose_2026_form_send_mail(string $subject, string $body, string $reply_to_email = '', string $reply_to_name = ''): bool {
	$to = alex_rose_2026_form_recipient();
	if ($to === '') {
		return false;
	}

	$site_name  = wp_specialchars_decode((string) get_bloginfo('name'), ENT_QUOTES);
	$from_email = alex_rose_2026_form_from_address();

	$headers = array(
		sprintf('From: %s <%s>', $site_name !== '' ? $site_name : 'Alex Rose', $from_email),
		'Content-Type: text/plain; charset=UTF-8',
	);
	if ($reply_to_email !== '' && is_email($reply_to_email)) {
		$headers[] = $reply_to_name !== ''
			? sprintf('Reply-To: %s <%s>', $reply_to_name, $reply_to_email)
			: sprintf('Reply-To: %s', $reply_to_email);
	}

	$subject_prefix = apply_filters('alex_rose_2026_form_subject_prefix', '[Alex Rose] ');
	$subject_full   = trim((string) $subject_prefix) !== '' ? (string) $subject_prefix . $subject : $subject;

	return (bool) wp_mail($to, $subject_full, $body, $headers);
}

/* -------------------------------------------------------------------------
 * Action handlers
 *
 * Each is registered on both `admin_post_<action>` (logged-in users) and
 * `admin_post_nopriv_<action>` (anonymous visitors).
 * ------------------------------------------------------------------------- */

/* --- Contact: Send Enquiry ---------------------------------------------- */

function alex_rose_2026_handle_ct_send_enquiry(): void {
	$action = 'ct_send_enquiry';
	alex_rose_2026_form_guard($action, 'ct_send_enquiry', 'ct_nonce');

	$name    = alex_rose_2026_form_field('ct_name');
	$email   = alex_rose_2026_form_field('ct_email');
	$phone   = alex_rose_2026_form_field('ct_phone');
	$message = alex_rose_2026_form_field('ct_message');

	if ($name === '' || ! is_email($email) || $message === '') {
		alex_rose_2026_form_respond(false, $action, __('Please fill in your name, a valid email address and a message.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),    'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),   'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),   'value' => $phone),
			array('label' => __('Message', 'alex-rose-2026'), 'value' => $message),
		),
		__('A new enquiry has arrived via the Contact page:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('New enquiry from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your message. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your enquiry has been sent.', 'alex-rose-2026'));
}
add_action('admin_post_ct_send_enquiry', 'alex_rose_2026_handle_ct_send_enquiry');
add_action('admin_post_nopriv_ct_send_enquiry', 'alex_rose_2026_handle_ct_send_enquiry');

/* --- Request Cloth Samples ---------------------------------------------- */

function alex_rose_2026_handle_rcs_request_samples(): void {
	$action = 'rcs_request_samples';
	alex_rose_2026_form_guard($action, 'rcs_request_samples', 'rcs_nonce');

	$name     = alex_rose_2026_form_field('rcs_name');
	$email    = alex_rose_2026_form_field('rcs_email');
	$addr1    = alex_rose_2026_form_field('rcs_addr1');
	$addr2    = alex_rose_2026_form_field('rcs_addr2');
	$city     = alex_rose_2026_form_field('rcs_city');
	$postcode = alex_rose_2026_form_field('rcs_postcode');
	$samples  = alex_rose_2026_form_field_list('rcs_samples');

	if ($name === '' || ! is_email($email) || $addr1 === '' || $city === '' || $postcode === '') {
		alex_rose_2026_form_respond(false, $action, __('Please complete the required fields with a valid postal address.', 'alex-rose-2026'));
	}

	$address_lines = array_filter(array($addr1, $addr2, $city, $postcode));

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),    'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),   'value' => $email),
			array('label' => __('Address', 'alex-rose-2026'), 'value' => implode(', ', $address_lines)),
			array('label' => __('Samples requested', 'alex-rose-2026'), 'value' => $samples),
		),
		__('A new cloth-sample request has arrived:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Cloth samples requested by %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your request. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your samples are on their way.', 'alex-rose-2026'));
}
add_action('admin_post_rcs_request_samples', 'alex_rose_2026_handle_rcs_request_samples');
add_action('admin_post_nopriv_rcs_request_samples', 'alex_rose_2026_handle_rcs_request_samples');

/* --- Schedule a Call ---------------------------------------------------- */

function alex_rose_2026_handle_sac_book_call(): void {
	$action = 'sac_book_call';
	alex_rose_2026_form_guard($action, 'sac_book_call', 'sac_nonce');

	$name     = alex_rose_2026_form_field('sac_name');
	$email    = alex_rose_2026_form_field('sac_email');
	$phone    = alex_rose_2026_form_field('sac_phone');
	$purpose  = alex_rose_2026_form_field('sac_purpose');
	$occasion = alex_rose_2026_form_field('sac_occasion');
	$notes    = alex_rose_2026_form_field('sac_notes');
	$date     = alex_rose_2026_form_field('sac_date');
	$time     = alex_rose_2026_form_field('sac_time');
	$timezone = alex_rose_2026_form_field('sac_tz');

	if ($name === '' || ! is_email($email) || $phone === '' || $purpose === '') {
		alex_rose_2026_form_respond(false, $action, __('Please complete every required step before choosing a time.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),                  'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),                 'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),                 'value' => $phone),
			array('label' => __('Purpose', 'alex-rose-2026'),               'value' => $purpose),
			array('label' => __('Occasion', 'alex-rose-2026'),              'value' => $occasion),
			array('label' => __('Preferred date', 'alex-rose-2026'),        'value' => $date),
			array('label' => __('Preferred time (GMT)', 'alex-rose-2026'),  'value' => $time),
			array('label' => __('Visitor timezone', 'alex-rose-2026'),      'value' => $timezone),
			array('label' => __('Notes', 'alex-rose-2026'),                 'value' => $notes),
		),
		__('A new consultation call request has arrived:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Consultation call request from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong booking your call. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your call is booked.', 'alex-rose-2026'));
}
add_action('admin_post_sac_book_call', 'alex_rose_2026_handle_sac_book_call');
add_action('admin_post_nopriv_sac_book_call', 'alex_rose_2026_handle_sac_book_call');

/* --- Post Your Jacket: Request Box -------------------------------------- */

function alex_rose_2026_handle_pyj_request_box(): void {
	$action = 'pyj_request_box';
	alex_rose_2026_form_guard($action, 'pyj_request_box', 'pyj_nonce');

	$name     = alex_rose_2026_form_field('pyj_name');
	$email    = alex_rose_2026_form_field('pyj_email');
	$phone    = alex_rose_2026_form_field('pyj_phone');
	$addr1    = alex_rose_2026_form_field('pyj_addr1');
	$addr2    = alex_rose_2026_form_field('pyj_addr2');
	$city     = alex_rose_2026_form_field('pyj_city');
	$postcode = alex_rose_2026_form_field('pyj_postcode');
	$notes    = alex_rose_2026_form_field('pyj_notes');

	if ($name === '' || ! is_email($email) || $addr1 === '' || $city === '' || $postcode === '') {
		alex_rose_2026_form_respond(false, $action, __('Please complete the required fields with a valid delivery address.', 'alex-rose-2026'));
	}

	if (! alex_rose_2026_is_uk_postcode($postcode)) {
		alex_rose_2026_form_respond(false, $action, __('This free service is available for UK delivery addresses only. Please enter a valid UK postcode, or contact us if you are outside the UK.', 'alex-rose-2026'));
	}

	$address_lines = array_filter(array($addr1, $addr2, $city, $postcode));

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),    'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),   'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),   'value' => $phone),
			array('label' => __('Address', 'alex-rose-2026'), 'value' => implode(', ', $address_lines)),
			array('label' => __('Notes', 'alex-rose-2026'),   'value' => $notes),
		),
		__('A new Post Your Jacket box request has arrived:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Post Your Jacket box request from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your request. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your free box request has been sent.', 'alex-rose-2026'));
}
add_action('admin_post_pyj_request_box', 'alex_rose_2026_handle_pyj_request_box');
add_action('admin_post_nopriv_pyj_request_box', 'alex_rose_2026_handle_pyj_request_box');

/* --- Request Tape Measure ----------------------------------------------- */

function alex_rose_2026_handle_rtm_request_tape(): void {
	$action = 'rtm_request_tape';
	alex_rose_2026_form_guard($action, 'rtm_request_tape', 'rtm_nonce');

	$name     = alex_rose_2026_form_field('rtm_name');
	$email    = alex_rose_2026_form_field('rtm_email');
	$addr1    = alex_rose_2026_form_field('rtm_addr1');
	$addr2    = alex_rose_2026_form_field('rtm_addr2');
	$city     = alex_rose_2026_form_field('rtm_city');
	$postcode = alex_rose_2026_form_field('rtm_postcode');

	if ($name === '' || ! is_email($email) || $addr1 === '' || $city === '' || $postcode === '') {
		alex_rose_2026_form_respond(false, $action, __('Please complete the required fields with a valid postal address.', 'alex-rose-2026'));
	}

	$address_lines = array_filter(array($addr1, $addr2, $city, $postcode));

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),    'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),   'value' => $email),
			array('label' => __('Address', 'alex-rose-2026'), 'value' => implode(', ', $address_lines)),
		),
		__('A new tape-measure request has arrived:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Tape measure requested by %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your request. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your tape measure is on its way.', 'alex-rose-2026'));
}
add_action('admin_post_rtm_request_tape', 'alex_rose_2026_handle_rtm_request_tape');
add_action('admin_post_nopriv_rtm_request_tape', 'alex_rose_2026_handle_rtm_request_tape');

/* --- Send Measurements -------------------------------------------------- */

function alex_rose_2026_handle_sm_send_measurements(): void {
	$action = 'sm_send_measurements';
	alex_rose_2026_form_guard($action, 'sm_send_measurements', 'sm_nonce');

	$first       = alex_rose_2026_form_field('sm_first');
	$last        = alex_rose_2026_form_field('sm_last');
	$email       = alex_rose_2026_form_field('sm_email');
	$unit        = alex_rose_2026_form_field('sm_unit');
	$chest       = alex_rose_2026_form_field('sm_chest');
	$waist       = alex_rose_2026_form_field('sm_waist');
	$hips        = alex_rose_2026_form_field('sm_hips');
	$height      = alex_rose_2026_form_field('sm_height');
	$weight      = alex_rose_2026_form_field('sm_weight');
	$sleeve      = alex_rose_2026_form_field('sm_sleeve');
	$shoulder    = alex_rose_2026_form_field('sm_shoulder');
	$back_length = alex_rose_2026_form_field('sm_back_length');
	$label_size  = alex_rose_2026_form_field('sm_label_size');

	if ($first === '' || $last === '' || ! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter your first name, last name and a valid email address.', 'alex-rose-2026'));
	}

	if ($unit !== 'in') {
		$unit = 'cm';
	}

	$unit_label = $unit === 'in' ? __('Inches', 'alex-rose-2026') : __('Centimetres', 'alex-rose-2026');
	$name       = trim($first . ' ' . $last);

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),           'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),          'value' => $email),
			array('label' => __('Measuring in', 'alex-rose-2026'),   'value' => $unit_label),
			array('label' => __('Chest', 'alex-rose-2026'),          'value' => $chest),
			array('label' => __('Waist', 'alex-rose-2026'),          'value' => $waist),
			array('label' => __('Hips', 'alex-rose-2026'),           'value' => $hips),
			array('label' => __('Height', 'alex-rose-2026'),         'value' => $height),
			array('label' => __('Weight', 'alex-rose-2026'),         'value' => $weight),
			array('label' => __('Jacket sleeve', 'alex-rose-2026'),  'value' => $sleeve),
			array('label' => __('Jacket shoulder', 'alex-rose-2026'), 'value' => $shoulder),
			array('label' => __('Jacket back length', 'alex-rose-2026'), 'value' => $back_length),
			array('label' => __('Label size', 'alex-rose-2026'),     'value' => $label_size),
		),
		__('New measurements have been submitted via the Send Measurements page:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Measurements from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your measurements. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your measurements have been sent.', 'alex-rose-2026'));
}
add_action('admin_post_sm_send_measurements', 'alex_rose_2026_handle_sm_send_measurements');
add_action('admin_post_nopriv_sm_send_measurements', 'alex_rose_2026_handle_sm_send_measurements');

/* --- Book a Call (Send Measurements page) ------------------------------- */

function alex_rose_2026_handle_sm_book_call(): void {
	$action = 'sm_book_call';
	alex_rose_2026_form_guard($action, 'sm_book_call', 'sm_call_nonce');

	$name   = alex_rose_2026_form_field('sm_call_name');
	$email  = alex_rose_2026_form_field('sm_call_email');
	$phone  = alex_rose_2026_form_field('sm_call_phone');
	$topics = alex_rose_2026_form_field('sm_call_topics');
	$notes  = alex_rose_2026_form_field('sm_call_notes');

	if ($name === '' || ! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter your name and a valid email address.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),      'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),     'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),     'value' => $phone),
			array('label' => __('Call about', 'alex-rose-2026'), 'value' => $topics),
			array('label' => __('Notes', 'alex-rose-2026'),     'value' => $notes),
		),
		__('A call has been requested via the Send Measurements page:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Call request from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your request. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your details have been sent.', 'alex-rose-2026'));
}
add_action('admin_post_sm_book_call', 'alex_rose_2026_handle_sm_book_call');
add_action('admin_post_nopriv_sm_book_call', 'alex_rose_2026_handle_sm_book_call');

/* --- Post Us a Jacket (Send Measurements page) -------------------------- */

function alex_rose_2026_handle_sm_post_jacket(): void {
	$action = 'sm_post_jacket';
	alex_rose_2026_form_guard($action, 'sm_post_jacket', 'sm_post_nonce');

	$name     = alex_rose_2026_form_field('sm_post_name');
	$email    = alex_rose_2026_form_field('sm_post_email');
	$phone    = alex_rose_2026_form_field('sm_post_phone');
	$address1 = alex_rose_2026_form_field('sm_post_address1');
	$address2 = alex_rose_2026_form_field('sm_post_address2');
	$town     = alex_rose_2026_form_field('sm_post_town');
	$postcode = alex_rose_2026_form_field('sm_post_postcode');
	$notes    = alex_rose_2026_form_field('sm_post_notes');

	if ($name === '' || ! is_email($email) || $address1 === '' || $town === '' || $postcode === '') {
		alex_rose_2026_form_respond(false, $action, __('Please enter your name, a valid email address, and your full delivery address.', 'alex-rose-2026'));
	}

	if (! alex_rose_2026_is_uk_postcode($postcode)) {
		alex_rose_2026_form_respond(false, $action, __('This service is available for UK delivery addresses only. Please enter a valid UK postcode, or contact us if you are outside the UK.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),           'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),          'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),          'value' => $phone),
			array('label' => __('Address line 1', 'alex-rose-2026'), 'value' => $address1),
			array('label' => __('Address line 2', 'alex-rose-2026'), 'value' => $address2),
			array('label' => __('Town / city', 'alex-rose-2026'),    'value' => $town),
			array('label' => __('Postcode', 'alex-rose-2026'),       'value' => $postcode),
			array('label' => __('Notes', 'alex-rose-2026'),          'value' => $notes),
		),
		__('A prepaid measuring box has been requested via the Send Measurements page:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: visitor name */
			__('Post-a-jacket box request from %s', 'alex-rose-2026'),
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your request. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your details have been sent.', 'alex-rose-2026'));
}
add_action('admin_post_sm_post_jacket', 'alex_rose_2026_handle_sm_post_jacket');
add_action('admin_post_nopriv_sm_post_jacket', 'alex_rose_2026_handle_sm_post_jacket');

/* --- Gift Vouchers ------------------------------------------------------ */

function alex_rose_2026_handle_gv_order_voucher(): void {
	$action = 'gv_order_voucher';
	alex_rose_2026_form_guard($action, 'gv_order_voucher', 'gv_nonce');

	$first        = alex_rose_2026_form_field('gv_first');
	$last         = alex_rose_2026_form_field('gv_last');
	$email        = alex_rose_2026_form_field('gv_email');
	$addr1        = alex_rose_2026_form_field('gv_addr1');
	$addr2        = alex_rose_2026_form_field('gv_addr2');
	$city         = alex_rose_2026_form_field('gv_city');
	$county       = alex_rose_2026_form_field('gv_county');
	$postcode     = alex_rose_2026_form_field('gv_postcode');
	$country      = alex_rose_2026_form_field('gv_country');
	$phone        = alex_rose_2026_form_field('gv_phone');
	$recipient    = alex_rose_2026_form_field('gv_recipient');
	$amount       = alex_rose_2026_form_field('gv_amount');
	$voucher_type = alex_rose_2026_form_field('gv_voucher_type');
	$notes        = alex_rose_2026_form_field('gv_notes');

	if (
		$first === '' || $last === '' || ! is_email($email) ||
		$addr1 === '' || $city === '' || $postcode === '' ||
		$country === '' || $recipient === '' || $amount === ''
	) {
		alex_rose_2026_form_respond(false, $action, __('Please complete all required fields before placing your order.', 'alex-rose-2026'));
	}

	$name          = trim($first . ' ' . $last);
	$address_lines = array_filter(array($addr1, $addr2, $city, $county, $postcode, $country));

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Name', 'alex-rose-2026'),             'value' => $name),
			array('label' => __('Email', 'alex-rose-2026'),            'value' => $email),
			array('label' => __('Phone', 'alex-rose-2026'),            'value' => $phone),
			array('label' => __('Address', 'alex-rose-2026'),          'value' => implode(', ', $address_lines)),
			array('label' => __('Recipient', 'alex-rose-2026'),        'value' => $recipient),
			array('label' => __('Voucher value (£)', 'alex-rose-2026'), 'value' => $amount),
			array('label' => __('Voucher type', 'alex-rose-2026'),     'value' => $voucher_type),
			array('label' => __('Notes', 'alex-rose-2026'),            'value' => $notes),
		),
		__('A new gift-voucher order has been placed:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: 1: voucher amount, 2: visitor name */
			__('Gift voucher order: £%1$s for %2$s', 'alex-rose-2026'),
			$amount,
			$name
		),
		$body,
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong placing your order. Please try again or call us.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Harold will be in touch to confirm your order.', 'alex-rose-2026'));
}
add_action('admin_post_gv_order_voucher', 'alex_rose_2026_handle_gv_order_voucher');
add_action('admin_post_nopriv_gv_order_voucher', 'alex_rose_2026_handle_gv_order_voucher');

/* --- Off the Cuff Newsletter ------------------------------------------- */

function alex_rose_2026_handle_otc_newsletter_signup(): void {
	$action = 'otc_newsletter_signup';
	alex_rose_2026_form_guard($action, 'otc_newsletter_signup', 'otc_newsletter_nonce');

	$email = alex_rose_2026_form_field('otc_newsletter_email');

	if (! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter a valid email address.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Email', 'alex-rose-2026'), 'value' => $email),
		),
		__('A new Off the Cuff subscriber has signed up:', 'alex-rose-2026')
	);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: subscriber email */
			__('Off the Cuff signup: %s', 'alex-rose-2026'),
			$email
		),
		$body,
		$email
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong adding you to the list. Please try again later.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. You are on the list.', 'alex-rose-2026'));
}
add_action('admin_post_otc_newsletter_signup', 'alex_rose_2026_handle_otc_newsletter_signup');
add_action('admin_post_nopriv_otc_newsletter_signup', 'alex_rose_2026_handle_otc_newsletter_signup');

/* --- Brevo (email marketing) integration -------------------------------- */

/**
 * Add (or update) a contact in Brevo via its REST API. Used to push launch-page
 * waitlist signups into a Brevo list so they can receive the discount email or
 * enter a Brevo automation. Best-effort: failures never block the form.
 *
 * Configure the API key + list id (do NOT hard-code the key in the theme).
 * In wp-config.php:
 *
 *     define('ALEX_ROSE_BREVO_API_KEY', 'xkeysib-xxxxxxxx');
 *     define('ALEX_ROSE_BREVO_LAUNCH_LIST_ID', 3);
 *
 * Both are filterable: `alex_rose_2026_brevo_api_key`,
 * `alex_rose_2026_brevo_list_id`, `alex_rose_2026_brevo_attributes`.
 *
 * @param array<string, mixed> $attributes Brevo contact attributes (must already
 *                                          exist in the account, e.g. FIRSTNAME).
 * @param int[]                $list_ids   Brevo list ids to add the contact to.
 */
function alex_rose_2026_brevo_add_contact(string $email, array $attributes = array(), array $list_ids = array()): bool {
	$key = defined('ALEX_ROSE_BREVO_API_KEY') ? (string) ALEX_ROSE_BREVO_API_KEY : '';
	$key = (string) apply_filters('alex_rose_2026_brevo_api_key', $key);
	if ($key === '' || ! is_email($email)) {
		return false;
	}

	$payload = array(
		'email'         => $email,
		'updateEnabled' => true, // Upsert — re-subscribing an existing email won't error.
	);
	if ($attributes !== array()) {
		$payload['attributes'] = $attributes;
	}
	$list_ids = array_values(array_filter(array_map('intval', $list_ids)));
	if ($list_ids !== array()) {
		$payload['listIds'] = $list_ids;
	}

	$response = wp_remote_post(
		'https://api.brevo.com/v3/contacts',
		array(
			'timeout' => 12,
			'headers' => array(
				'api-key'      => $key,
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			),
			'body'    => wp_json_encode($payload),
		)
	);

	if (is_wp_error($response)) {
		error_log('[Alex Rose] Brevo request error: ' . $response->get_error_message());
		return false;
	}
	// 201 created, 204 updated (updateEnabled), 200 ok.
	$code = (int) wp_remote_retrieve_response_code($response);
	if (! in_array($code, array(200, 201, 204), true)) {
		error_log('[Alex Rose] Brevo add_contact HTTP ' . $code . ': ' . wp_remote_retrieve_body($response));
		return false;
	}
	return true;
}

/**
 * Push a form signup into Brevo using the theme's configured list + filters.
 *
 * @param array<string, mixed> $context Extra data passed to the attributes filter.
 */
function alex_rose_2026_brevo_capture(string $email, string $action, array $context = array()): void {
	$list_id = defined('ALEX_ROSE_BREVO_LAUNCH_LIST_ID') ? (int) ALEX_ROSE_BREVO_LAUNCH_LIST_ID : 0;
	$list_id = (int) apply_filters('alex_rose_2026_brevo_list_id', $list_id, $action);

	// Empty by default: Brevo rejects the whole request for attributes that
	// don't exist in the account, so only send ones added via the filter (once
	// you've created e.g. a REFERRED_BY attribute in Brevo).
	$attributes = apply_filters('alex_rose_2026_brevo_attributes', array(), $action, $context);
	$attributes = is_array($attributes) ? $attributes : array();

	alex_rose_2026_brevo_add_contact($email, $attributes, $list_id ? array($list_id) : array());
}

/**
 * Choose the founding discount code for a launch signup.
 *
 * Everyone gets the general EARLY20 code, except visitors who say they were
 * referred by Brian Klimek (Instagram: Suttielinksbracesman) — they get his
 * referral code STLBMAN20. Matching is loose so common spellings of the name
 * or handle all resolve. Filterable via `alex_rose_2026_launch_discount_code`.
 */
function alex_rose_2026_launch_discount_code(string $referral): string {
	$needle   = strtolower(trim($referral));
	$is_brian = $needle !== '' && (
		strpos($needle, 'brian') !== false ||
		strpos($needle, 'klimek') !== false ||
		strpos($needle, 'ttielinksbracesman') !== false || // suttie/suittie...
		strpos($needle, 'stlbman') !== false
	);

	$code = $is_brian ? 'STLBMAN20' : 'EARLY20';

	return (string) apply_filters('alex_rose_2026_launch_discount_code', $code, $referral);
}

/**
 * Email a launch subscriber their 20%-off founding discount code directly from
 * WordPress, so delivery does not depend on the Brevo automation. Best-effort:
 * returns the wp_mail result but the caller does not block the signup on it.
 */
function alex_rose_2026_send_launch_discount_email(string $email, string $code): bool {
	if (! is_email($email)) {
		return false;
	}

	$code        = strtoupper($code);
	$gold        = '#c8a96a';
	$ink         = '#111111';
	$site        = 'www.alexrose.uk';
	$site_url     = 'https://www.alexrose.uk';
	$support      = 'harold@alexrose.uk';
	$address      = '2A Rodley Ln, Rodley, Leeds LS13 1HU';
	$valid_until  = __('31 August 2026', 'alex-rose-2026');

	$subject = __('Your code is inside', 'alex-rose-2026');

	$preheader = esc_html__('As promised, here is your 20% off founding discount code.', 'alex-rose-2026');
	$greeting  = esc_html__('Hi there,', 'alex-rose-2026');
	$intro     = esc_html__('Thank you for joining the Alex Rose founding list. We’re glad to have you here.', 'alex-rose-2026');
	$lead      = esc_html__('As promised, here is your personal discount code for 20% off your first jacket:', 'alex-rose-2026');
	$general   = esc_html__('General code:', 'alex-rose-2026');
	$referral_note = esc_html__('If you were referred by Brian Klimek (Suttielinksbracesman on Instagram), please use the referral code:', 'alex-rose-2026');
	$closing   = esc_html__('We look forward to making your jacket.', 'alex-rose-2026');
	$regards   = esc_html__('Warm regards,', 'alex-rose-2026');
	$signoff   = esc_html__('Harold and the Alex Rose team', 'alex-rose-2026');
	$brand     = esc_html__('Alex Rose Fine Tailoring', 'alex-rose-2026');

	$validity = sprintf(
		/* translators: 1: valid-until date, 2: website address */
		esc_html__('This code is personal to you and valid until %1$s. Simply enter it when you design your jacket and check out at %2$s. You may enter only one code in the basket.', 'alex-rose-2026'),
		esc_html($valid_until),
		'<a href="' . esc_url($site_url) . '" style="color:' . $gold . ';text-decoration:none;">' . esc_html($site) . '</a>'
	);
	$support_line = sprintf(
		/* translators: %s: support email address */
		esc_html__('If you have any questions at all, simply reply to this email or reach Harold directly at %s. Every message is read personally.', 'alex-rose-2026'),
		'<a href="mailto:' . esc_attr($support) . '" style="color:' . $gold . ';text-decoration:none;">' . esc_html($support) . '</a>'
	);

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
		. '<h1 style="margin:0 0 22px;font-family:Georgia,\'Times New Roman\',serif;font-size:24px;font-weight:normal;color:' . $ink . ';">' . esc_html__('Your code is inside.', 'alex-rose-2026') . '</h1>'
		. '<p style="' . $p . '">' . $greeting . '</p>'
		. '<p style="' . $p . '">' . $intro . '</p>'
		. '<p style="' . $p . '">' . $lead . '</p>'
		. '<p style="' . $p . '"><strong>' . $general . '</strong> EARLY20<br>' . $referral_note . ' <strong>STLBMAN20</strong></p>'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:8px 0 28px;"><tr><td align="center" style="border:1px dashed ' . $gold . ';background:#faf7f0;padding:22px;">'
		. '<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;color:#8a8577;margin-bottom:8px;">' . esc_html__('Your code', 'alex-rose-2026') . '</div>'
		. '<div style="font-family:Georgia,\'Times New Roman\',serif;font-size:32px;letter-spacing:0.12em;color:' . $ink . ';font-weight:bold;">' . esc_html($code) . '</div>'
		. '</td></tr></table>'
		. '<p style="' . $p . '">' . $validity . '</p>'
		. '<p style="' . $p . '">' . $support_line . '</p>'
		. '<p style="' . $p . '">' . $closing . '</p>'
		. '<p style="' . $p . 'margin-bottom:4px;">' . $regards . '</p>'
		. '<p style="margin:0;font-family:Georgia,\'Times New Roman\',serif;font-size:15px;color:' . $ink . ';">' . $signoff . '</p>'
		. '</td></tr>'
		. '<tr><td style="background:' . $ink . ';padding:28px 40px;text-align:center;">'
		. '<div style="font-family:Georgia,\'Times New Roman\',serif;font-size:13px;letter-spacing:0.2em;text-transform:uppercase;color:#ffffff;margin-bottom:10px;">' . $brand . '</div>'
		. '<a href="' . esc_url($site_url) . '" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:' . $gold . ';text-decoration:none;">' . esc_html($site) . '</a>'
		. '<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:rgba(255,255,255,0.5);margin-top:12px;">' . esc_html($address) . '</div>'
		. '</td></tr>'
		. '</table></td></tr></table></body></html>';

	$headers = array(
		sprintf('From: %s <%s>', $brand, alex_rose_2026_form_from_address()),
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: Harold <' . $support . '>',
	);

	return (bool) wp_mail($email, $subject, $html, $headers);
}

/* --- Launch: Founding-member discount list ------------------------------ */

function alex_rose_2026_handle_lp_join_waitlist(): void {
	$action = 'lp_join_waitlist';
	alex_rose_2026_form_guard($action, 'lp_join_waitlist', 'lp_nonce');

	$email    = alex_rose_2026_form_field('lp_email');
	$referral = alex_rose_2026_form_field('lp_referral');

	if (! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter a valid email address.', 'alex-rose-2026'));
	}

	// A Brian Klimek referral earns his STLBMAN20 code; everyone else EARLY20.
	$code = alex_rose_2026_launch_discount_code($referral);

	// Add the subscriber to Brevo (no-op until an API key is configured).
	alex_rose_2026_brevo_capture($email, $action, array('referral' => $referral));

	// Email the subscriber their code straight from WordPress. Best-effort: a
	// delivery failure is logged but does not block the signup (Brevo remains a
	// backup path).
	if (! alex_rose_2026_send_launch_discount_email($email, $code)) {
		error_log('[Alex Rose] Launch discount email failed for ' . $email);
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Email', 'alex-rose-2026'),       'value' => $email),
			array('label' => __('Referred by', 'alex-rose-2026'), 'value' => $referral),
			array('label' => __('Code sent', 'alex-rose-2026'),   'value' => $code),
		),
		__('A new founding-member discount request has arrived via the Launch page:', 'alex-rose-2026')
	);

	// Founding-list signups also notify the build team, on top of the
	// site-wide recipient. Scoped to this handler only.
	$lp_extra_recipient = static function ($recipient) {
		$extra     = 'harold@alexrose.uk, tailor@alexrose.uk';
		$recipient = is_string($recipient) ? trim($recipient) : '';
		return $recipient !== '' ? $recipient . ', ' . $extra : $extra;
	};
	add_filter('alex_rose_2026_form_recipient', $lp_extra_recipient, 20);

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: subscriber email */
			__('Founding list signup: %s', 'alex-rose-2026'),
			$email
		),
		$body,
		$email
	);

	remove_filter('alex_rose_2026_form_recipient', $lp_extra_recipient, 20);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong. Please try again in a moment.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your discount code is on its way.', 'alex-rose-2026'));
}
add_action('admin_post_lp_join_waitlist', 'alex_rose_2026_handle_lp_join_waitlist');
add_action('admin_post_nopriv_lp_join_waitlist', 'alex_rose_2026_handle_lp_join_waitlist');

/* --- Feedback survey ---------------------------------------------------- */

/**
 * Multi-step "Help us improve" feedback survey. Every posted field named fb_*
 * is collected and emailed in form order, so adding questions to steps 2–5
 * (template-parts/feedback/markup.php) needs no change here — just name the
 * inputs fb_something.
 */
function alex_rose_2026_handle_fb_submit_feedback(): void {
	$action = 'fb_submit_feedback';
	alex_rose_2026_form_guard($action, 'fb_submit_feedback', 'fb_nonce');

	$email = alex_rose_2026_form_field('fb_email');
	if (! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter a valid email address.', 'alex-rose-2026'));
	}
	if (alex_rose_2026_form_field('fb_consent') === '') {
		alex_rose_2026_form_respond(false, $action, __('Please agree to the use of your details before sending.', 'alex-rose-2026'));
	}
	$name = alex_rose_2026_form_field('fb_name');

	// Friendly labels for the known step-1 fields; anything else is humanised
	// from its field name (fb_age_band -> "Age band").
	$labels = array(
		'fb_email' => __('Email', 'alex-rose-2026'),
		'fb_name'  => __('Full name', 'alex-rose-2026'),
		'fb_phone' => __('Phone', 'alex-rose-2026'),
		'fb_age'   => __('Age group', 'alex-rose-2026'),
		'fb_mtm'   => __('Bought made-to-measure before', 'alex-rose-2026'),
		// Step 2 — The website
		'fb_access_easily'        => __('Accessed the website easily', 'alex-rose-2026'),
		'fb_loading_speed'        => __('Loading speed rating (1–5)', 'alex-rose-2026'),
		'fb_homepage_clear'       => __('Homepage clear & welcoming', 'alex-rose-2026'),
		'fb_homepage_suggestions' => __('Homepage suggestions', 'alex-rose-2026'),
		// Step 3 — The configurator
		'fb_button_easy_find'     => __('“Design My Jacket” button easy to find', 'alex-rose-2026'),
		'fb_customise_ease'       => __('Ease of customising (1–5)', 'alex-rose-2026'),
		'fb_most_intuitive'       => __('Most intuitive part', 'alex-rose-2026'),
		'fb_confusing'            => __('Confusing / to improve', 'alex-rose-2026'),
		'fb_enough_options'       => __('Enough fabric & style options', 'alex-rose-2026'),
		'fb_fitting_room_smooth'  => __('Fitting room worked smoothly', 'alex-rose-2026'),
		'fb_preview_realistic'    => __('Preview realism (1–5)', 'alex-rose-2026'),
		'fb_fitting_room_improve' => __('Fitting room improvements', 'alex-rose-2026'),
		// Step 4 — Your journey
		'fb_comfortable_details'      => __('Comfortable entering details', 'alex-rose-2026'),
		'fb_confidence_help'          => __('What would build confidence', 'alex-rose-2026'),
		'fb_form_clear'               => __('Form layout clear', 'alex-rose-2026'),
		'fb_technical_issues'         => __('Encountered technical issues', 'alex-rose-2026'),
		'fb_measurements_ease'        => __('Ease of entering measurements (1–5)', 'alex-rose-2026'),
		'fb_measurement_instructions' => __('Measurement instructions clear', 'alex-rose-2026'),
		'fb_measurement_improve'      => __('Measurement process improvements', 'alex-rose-2026'),
		'fb_book_call_work'           => __('“Book a Call” link worked', 'alex-rose-2026'),
		'fb_booking_straightforward'  => __('Booking process straightforward', 'alex-rose-2026'),
		'fb_booking_improve'          => __('Booking improvements', 'alex-rose-2026'),
		// Step 5 — Overall
		'fb_overall_rating'       => __('Overall experience (1–5)', 'alex-rose-2026'),
		'fb_recommend'            => __('Would recommend to a friend', 'alex-rose-2026'),
		'fb_additional_comments'  => __('Additional comments', 'alex-rose-2026'),
		'fb_consent'              => __('Consent to use details', 'alex-rose-2026'),
	);

	$rows = array();
	foreach ($_POST as $key => $raw) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$key = (string) $key;
		if (strpos($key, 'fb_') !== 0 || $key === 'fb_nonce') {
			continue;
		}
		$value = alex_rose_2026_form_field_list($key);
		if ($value === array()) {
			$single = alex_rose_2026_form_field($key);
			if ($single === '') {
				continue;
			}
			$value = $single;
		}
		$label = isset($labels[ $key ])
			? $labels[ $key ]
			: ucfirst(trim(str_replace('_', ' ', substr($key, 3))));
		$rows[] = array('label' => $label, 'value' => $value);
	}

	$sent = alex_rose_2026_form_send_mail(
		sprintf(
			/* translators: %s: respondent email */
			__('Feedback survey response from %s', 'alex-rose-2026'),
			$email
		),
		alex_rose_2026_form_build_body($rows, __('A new feedback survey response has arrived:', 'alex-rose-2026')),
		$email,
		$name
	);

	if (! $sent) {
		alex_rose_2026_form_respond(false, $action, __('Something went wrong sending your feedback. Please try again.', 'alex-rose-2026'));
	}

	alex_rose_2026_form_respond(true, $action, __('Thank you. Your feedback has been sent.', 'alex-rose-2026'));
}
add_action('admin_post_fb_submit_feedback', 'alex_rose_2026_handle_fb_submit_feedback');
add_action('admin_post_nopriv_fb_submit_feedback', 'alex_rose_2026_handle_fb_submit_feedback');

/* --- Design Your Jacket: create made-to-order WooCommerce order ---------- */

/**
 * Build (and cache) a [referenceId => price] map from the Tailormate design
 * catalogue, server-side. The API authorises by an Origin allow-list; the
 * `http://localhost` origin is whitelisted, so we send it from PHP regardless
 * of the live domain (swap via the `alex_rose_2026_tailormate_origin` filter
 * once the production domain is whitelisted).
 *
 * @return array<string, float>
 */
function alex_rose_2026_tailormate_fabric_prices(): array {
	$cached = get_transient('ar_tailormate_fabric_prices');
	if (is_array($cached)) {
		return $cached;
	}

	$key    = (string) apply_filters('alex_rose_2026_tailormate_key', '2817c949-40f8-412a-bce0-1a62ea20ffab');
	$base   = (string) apply_filters('alex_rose_2026_tailormate_base', 'https://tailormate.xiontechnologies.in/api');
	$origin = (string) apply_filters('alex_rose_2026_tailormate_origin', 'http://localhost');

	$prices      = array();
	$page        = 1;
	$total_pages = 1;

	do {
		$url = add_query_arg(
			array('limit' => 100, 'page' => $page, 'depth' => 2, 'tag' => 'website'),
			$base . '/designs'
		);
		$response = wp_remote_get($url, array(
			'timeout' => 12,
			'headers' => array(
				'Authorization' => 'Bearer ' . $key,
				'Origin'        => $origin,
			),
		));

		if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
			break;
		}

		$payload = json_decode(wp_remote_retrieve_body($response), true);
		$data    = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : array();
		foreach ($data as $design) {
			$ref = isset($design['ReferenceId']) ? strtolower((string) $design['ReferenceId']) : '';
			if ($ref === '' && isset($design['FileName'])) {
				$ref = strtolower((string) preg_replace('/\.[^.]+$/', '', (string) $design['FileName']));
			}
			if ($ref !== '' && isset($design['Price'])) {
				$prices[ $ref ] = (float) $design['Price'];
			}
		}

		$total_pages = isset($payload['totalPages']) ? (int) $payload['totalPages'] : 1;
		$page++;
	} while ($page <= $total_pages);

	if ($prices !== array()) {
		set_transient('ar_tailormate_fabric_prices', $prices, 12 * HOUR_IN_SECONDS);
	}

	return $prices;
}

/**
 * Authoritative price for a fabric, looked up server-side by reference id.
 * Falls back to the supplied value when the catalogue can't be reached.
 */
function alex_rose_2026_fabric_price(string $reference_id, float $fallback = 0.0): float {
	$reference_id = strtolower(trim($reference_id));
	if ($reference_id === '') {
		return $fallback;
	}
	$prices = alex_rose_2026_tailormate_fabric_prices();
	return isset($prices[ $reference_id ]) ? (float) $prices[ $reference_id ] : $fallback;
}

/**
 * Find (or create) the single hidden "Bespoke Jacket" product that every
 * configurator order is booked against. Per-order pricing and the full spec
 * live on the order line item, so one product covers every combination.
 *
 * Only ever called from inside a `function_exists('wc_create_order')` guard.
 *
 * @return WC_Product
 */
function alex_rose_2026_get_bespoke_product() {
	$sku        = 'ar-bespoke-jacket';
	$product_id = function_exists('wc_get_product_id_by_sku') ? wc_get_product_id_by_sku($sku) : 0;

	if ($product_id) {
		$product = wc_get_product($product_id);
		// A product must be 'publish' to be purchasable (add to cart / checkout).
		// An older build created it 'private'; heal that here.
		if ($product && $product->get_status() !== 'publish') {
			$product->set_status('publish');
			$product->set_catalog_visibility('hidden');
			$product->set_sold_individually(false);
			$product->save();
		}
		return $product;
	}

	$product = new WC_Product_Simple();
	$product->set_name(__('Bespoke Jacket', 'alex-rose-2026'));
	$product->set_sku($sku);
	$product->set_status('publish');          // purchasable…
	$product->set_catalog_visibility('hidden'); // …but hidden from shop/search
	$product->set_regular_price('0');
	$product->set_price('0');
	$product->set_virtual(true);              // made-to-order; no stock/shipping weight
	$product->save();

	return $product;
}

/**
 * Flatten the posted jacket configuration into ordered "Label => value"
 * pairs used for both the order line-item meta and the notification email.
 *
 * @param array<string, mixed> $options
 * @return array<string, string>
 */
function alex_rose_2026_jacket_spec_lines(array $options): array {
	$label_of = static function ($node): string {
		if (is_array($node)) {
			if (isset($node['label'])) {
				return (string) $node['label'];
			}
			return isset($node['name']) ? (string) $node['name'] : '';
		}
		return (string) $node;
	};

	$fabric = isset($options['fabric']) && is_array($options['fabric']) ? $options['fabric'] : array();

	return array(
		__('Fabric', 'alex-rose-2026')     => isset($fabric['name']) ? (string) $fabric['name'] : '',
		__('Collection', 'alex-rose-2026') => isset($fabric['collection']) ? (string) $fabric['collection'] : '',
		__('Lining', 'alex-rose-2026')     => $label_of($options['lining'] ?? ''),
		__('Buttons', 'alex-rose-2026')    => $label_of($options['buttons'] ?? ''),
		__('Buttoning', 'alex-rose-2026')  => $label_of($options['buttoning'] ?? ''),
		__('Pockets', 'alex-rose-2026')    => $label_of($options['pockets'] ?? ''),
		__('Vents', 'alex-rose-2026')      => $label_of($options['vents'] ?? ''),
		__('Monogram', 'alex-rose-2026')   => isset($options['monogram']) ? (string) $options['monogram'] : '',
	);
}

function alex_rose_2026_handle_ar_create_jacket_order(): void {
	$action = 'ar_create_jacket_order';
	alex_rose_2026_form_guard($action, 'ar_create_jacket_order', 'ar_order_nonce');

	$name     = alex_rose_2026_form_field('ar_name');
	$email    = alex_rose_2026_form_field('ar_email');
	$phone    = alex_rose_2026_form_field('ar_phone');
	$date     = alex_rose_2026_form_field('ar_date');
	$message  = alex_rose_2026_form_field('ar_message');
	$currency = alex_rose_2026_form_field('ar_currency');

	if ($name === '' || ! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter your name and a valid email address.', 'alex-rose-2026'));
	}

	$options      = json_decode(alex_rose_2026_form_field('ar_options'), true);
	$options      = is_array($options) ? $options : array();
	$measurements = json_decode(alex_rose_2026_form_field('ar_measurements'), true);
	$measurements = is_array($measurements) ? $measurements : array();

	$spec = alex_rose_2026_jacket_spec_lines($options);

	// Price is derived server-side from the fabric's reference id (never trusted
	// from the client). The posted price is only a last-ditch fallback.
	$fabric_ref   = isset($options['fabric']['referenceId']) ? (string) $options['fabric']['referenceId'] : '';
	$client_price = (float) alex_rose_2026_form_field('ar_price');
	$price        = alex_rose_2026_fabric_price($fabric_ref, $client_price > 0 ? $client_price : 595.0);
	if ($price < 0) {
		$price = 0.0;
	}

	/* --- Create the WooCommerce order (only when WooCommerce is active) --- */
	$order_id = 0;
	if (function_exists('wc_create_order') && class_exists('WC_Product_Simple')) {
		try {
			$product = alex_rose_2026_get_bespoke_product();
			$order   = wc_create_order();

			// Build the line item directly. We can't use add_product() + get_item()
			// here: the order isn't saved yet, so the item id is 0 and get_item(0)
			// returns false — which would leave the price unset and the order at £0.
			$item = new WC_Order_Item_Product();
			$item->set_name($product->get_name());
			$item->set_product_id($product->get_id());
			$item->set_quantity(1);
			$item->set_subtotal($price);
			$item->set_total($price);
			foreach ($spec as $label => $value) {
				if ($value !== '') {
					$item->add_meta_data($label, $value, true);
				}
			}
			$order->add_item($item);

			$order->set_address(
				array(
					'first_name' => $name,
					'email'      => $email,
					'phone'      => $phone,
				),
				'billing'
			);

			if ($measurements !== array()) {
				$order->update_meta_data('_ar_measurements', wp_json_encode($measurements));
			}
			if ($date !== '') {
				$order->update_meta_data('_ar_preferred_contact', $date);
			}
			if ($currency !== '') {
				$order->update_meta_data('_ar_display_currency', $currency);
			}
			if (! empty($options['tryOnResult'])) {
				$order->update_meta_data('_ar_tryon_generated', 'yes');
			}
			if ($message !== '') {
				$order->add_order_note(
					sprintf(/* translators: %s: customer message */ __('Customer note: %s', 'alex-rose-2026'), $message)
				);
			}

			if (method_exists($order, 'set_created_via')) {
				$order->set_created_via('alex-rose-configurator');
			}

			// Made-to-order: the jacket is reserved and settled once it's made.
			$order->set_status('on-hold', __('Bespoke jacket reserved via the configurator.', 'alex-rose-2026'));
			$order->calculate_totals();
			$order->save();
			$order_id = (int) $order->get_id();
		} catch (\Throwable $e) {
			// Never block the customer if WooCommerce errors — fall through to email.
			$order_id = 0;
		}
	}

	/* --- Always notify the tailor by email (as every other form does) ---- */
	$rows = array(
		array('label' => __('Name', 'alex-rose-2026'),              'value' => $name),
		array('label' => __('Email', 'alex-rose-2026'),             'value' => $email),
		array('label' => __('Phone', 'alex-rose-2026'),             'value' => $phone),
		array('label' => __('Preferred contact', 'alex-rose-2026'), 'value' => $date),
		array('label' => __('Price', 'alex-rose-2026'),             'value' => $price > 0 ? '£' . number_format($price, 2) : ''),
	);
	foreach ($spec as $label => $value) {
		$rows[] = array('label' => $label, 'value' => $value);
	}
	foreach ($measurements as $key => $value) {
		if (is_scalar($value) && (string) $value !== '') {
			$rows[] = array('label' => 'Measurement: ' . ucfirst(str_replace('_', ' ', (string) $key)), 'value' => (string) $value);
		}
	}
	if ($message !== '') {
		$rows[] = array('label' => __('Message', 'alex-rose-2026'), 'value' => $message);
	}
	if ($order_id > 0) {
		$rows[] = array('label' => __('WooCommerce order', 'alex-rose-2026'), 'value' => '#' . $order_id);
	}

	$intro = $order_id > 0
		? __('A new bespoke jacket has been reserved (WooCommerce order created):', 'alex-rose-2026')
		: __('A new bespoke jacket has been reserved via the configurator:', 'alex-rose-2026');

	alex_rose_2026_form_send_mail(
		sprintf(/* translators: %s: customer name */ __('Bespoke jacket reserved by %s', 'alex-rose-2026'), $name),
		alex_rose_2026_form_build_body($rows, $intro),
		$email,
		$name
	);

	alex_rose_2026_form_respond(
		true,
		$action,
		__('Thank you. Your order has been received.', 'alex-rose-2026'),
		array('order_id' => $order_id)
	);
}
add_action('admin_post_ar_create_jacket_order', 'alex_rose_2026_handle_ar_create_jacket_order');
add_action('admin_post_nopriv_ar_create_jacket_order', 'alex_rose_2026_handle_ar_create_jacket_order');
