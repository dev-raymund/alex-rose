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
 * Standard validation: nonce check + honeypot check. On failure, responds
 * (JSON or redirect) and exits — handlers can call this and assume the
 * request is sane afterwards.
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
