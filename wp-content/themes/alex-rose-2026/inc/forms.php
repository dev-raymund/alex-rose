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

/* --- Launch: Founding-member discount list ------------------------------ */

function alex_rose_2026_handle_lp_join_waitlist(): void {
	$action = 'lp_join_waitlist';
	alex_rose_2026_form_guard($action, 'lp_join_waitlist', 'lp_nonce');

	$email    = alex_rose_2026_form_field('lp_email');
	$referral = alex_rose_2026_form_field('lp_referral');

	if (! is_email($email)) {
		alex_rose_2026_form_respond(false, $action, __('Please enter a valid email address.', 'alex-rose-2026'));
	}

	$body = alex_rose_2026_form_build_body(
		array(
			array('label' => __('Email', 'alex-rose-2026'),       'value' => $email),
			array('label' => __('Referred by', 'alex-rose-2026'), 'value' => $referral),
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
