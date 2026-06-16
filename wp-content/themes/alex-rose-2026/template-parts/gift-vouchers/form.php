<?php
/**
 * "Gift Vouchers" — order form.
 *
 * Submission is intercepted by assets/js/page-gift-vouchers.js: the form is
 * hidden and the sibling #gv-form-confirmation block is revealed. Wire the
 * <form action> + method to a real handler when ready (REST endpoint,
 * Contact Form 7, etc.) and remove the `data-fake-submit` attribute to opt
 * out of the front-end-only confirmation.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$countries = array(
	'United Kingdom',
	'United States',
	'Australia',
	'Canada',
	'France',
	'Germany',
	'Ireland',
	'Italy',
	'Netherlands',
	'New Zealand',
	'Spain',
	'Switzerland',
	'United Arab Emirates',
	'Other',
);

$required_mark = '<span class="gv-form__required" aria-hidden="true">*</span>';
?>
<section class="gv-form" id="order-form">
	<div class="gv-form__inner ar-container ar-container--3xl">
		<header class="gv-form__head">
			<p class="gv-form__kicker"><?php esc_html_e('Order a Gift Voucher', 'alex-rose-2026'); ?></p>
			<h2 class="gv-form__title"><?php esc_html_e('Order Your Gift Voucher.', 'alex-rose-2026'); ?></h2>
			<p class="gv-form__lead"><?php esc_html_e('Fill in the form below and we will be in touch to confirm your order and take payment.', 'alex-rose-2026'); ?></p>
		</header>

		<form
			class="gv-form__form"
			action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
			method="post"
			data-gv-form
			novalidate
			aria-describedby="gv-form-confirmation"
		>
			<?php wp_nonce_field('gv_order_voucher', 'gv_nonce'); ?>
			<input type="hidden" name="action" value="gv_order_voucher">
			<?php alex_rose_2026_form_honeypot_field(); ?>

			<div class="gv-form__grid gv-form__grid--2">
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-first">
						<?php esc_html_e('First Name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>
					<input class="gv-form__input" type="text" id="gv-first" name="gv_first" placeholder="<?php esc_attr_e('First name', 'alex-rose-2026'); ?>" required>
				</div>
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-last">
						<?php esc_html_e('Last Name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>
					<input class="gv-form__input" type="text" id="gv-last" name="gv_last" placeholder="<?php esc_attr_e('Last name', 'alex-rose-2026'); ?>" required>
				</div>
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-email">
					<?php esc_html_e('Email Address', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</label>
				<input class="gv-form__input" type="email" id="gv-email" name="gv_email" placeholder="your@email.com" required>
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-addr1">
					<?php esc_html_e('Address Line 1', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</label>
				<input class="gv-form__input" type="text" id="gv-addr1" name="gv_addr1" placeholder="<?php esc_attr_e('12 High Street', 'alex-rose-2026'); ?>" required>
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-addr2"><?php esc_html_e('Address Line 2 (optional)', 'alex-rose-2026'); ?></label>
				<input class="gv-form__input" type="text" id="gv-addr2" name="gv_addr2" placeholder="<?php esc_attr_e('Apartment, suite, etc.', 'alex-rose-2026'); ?>">
			</div>

			<div class="gv-form__grid gv-form__grid--2">
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-city">
						<?php esc_html_e('City', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>
					<input class="gv-form__input" type="text" id="gv-city" name="gv_city" placeholder="<?php esc_attr_e('Leeds', 'alex-rose-2026'); ?>" required>
				</div>
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-county"><?php esc_html_e('County (optional)', 'alex-rose-2026'); ?></label>
					<input class="gv-form__input" type="text" id="gv-county" name="gv_county" placeholder="<?php esc_attr_e('West Yorkshire', 'alex-rose-2026'); ?>">
				</div>
			</div>

			<div class="gv-form__grid gv-form__grid--2">
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-postcode">
						<?php esc_html_e('Postcode', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>
					<input class="gv-form__input" type="text" id="gv-postcode" name="gv_postcode" placeholder="LS1 1AB" required>
				</div>
				<div class="gv-form__field">
					<label class="gv-form__label" for="gv-country">
						<?php esc_html_e('Country', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>
					<select class="gv-form__input gv-form__select" id="gv-country" name="gv_country" required>
						<?php foreach ($countries as $country) : ?>
							<option value="<?php echo esc_attr($country); ?>"><?php echo esc_html($country); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-phone"><?php esc_html_e('Mobile Number (optional)', 'alex-rose-2026'); ?></label>
				<input class="gv-form__input" type="tel" id="gv-phone" name="gv_phone" placeholder="+44 7700 000000">
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-recipient">
					<?php esc_html_e("Recipient's Name", 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</label>
				<input class="gv-form__input" type="text" id="gv-recipient" name="gv_recipient" placeholder="<?php esc_attr_e('Who is the gift for?', 'alex-rose-2026'); ?>" required>
			</div>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-amount">
					<?php esc_html_e('Voucher Value (£)', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</label>
				<input class="gv-form__input" type="text" id="gv-amount" name="gv_amount" placeholder="<?php esc_attr_e('e.g. 595', 'alex-rose-2026'); ?>" inputmode="numeric" required>
			</div>

			<fieldset class="gv-form__field gv-form__radio-set">
				<legend class="gv-form__label gv-form__legend"><?php esc_html_e('Voucher Type', 'alex-rose-2026'); ?></legend>
				<div class="gv-form__radios">
					<label class="gv-radio">
						<input class="gv-radio__input" type="radio" name="gv_voucher_type" value="standard" checked>
						<span class="gv-radio__dot" aria-hidden="true"></span>
						<span class="gv-radio__text"><?php esc_html_e('Standard', 'alex-rose-2026'); ?></span>
					</label>
					<label class="gv-radio">
						<input class="gv-radio__input" type="radio" name="gv_voucher_type" value="personalised">
						<span class="gv-radio__dot" aria-hidden="true"></span>
						<span class="gv-radio__text"><?php esc_html_e('Personalised', 'alex-rose-2026'); ?></span>
					</label>
				</div>
			</fieldset>

			<div class="gv-form__field">
				<label class="gv-form__label" for="gv-notes"><?php esc_html_e('Any Special Requests? (optional)', 'alex-rose-2026'); ?></label>
				<textarea class="gv-form__input gv-form__textarea" id="gv-notes" name="gv_notes" rows="4" placeholder="<?php esc_attr_e('Any special requests or personal message to include...', 'alex-rose-2026'); ?>"></textarea>
			</div>

			<div class="gv-form__submit">
				<button class="gv-form__btn" type="submit" data-gv-submit>
					<?php esc_html_e('Order Gift Voucher', 'alex-rose-2026'); ?>
				</button>
				<p class="gv-form__hint">
					<?php
					printf(
						/* translators: %s: phone number link */
						esc_html__('Or call us on %s, Monday to Friday, 9am to 5pm.', 'alex-rose-2026'),
						'<a class="gv-form__hint-link" href="tel:+441134688588">0113 468 8588</a>'
					);
					?>
				</p>
				<p class="gv-form__error" data-gv-error role="alert" hidden></p>
			</div>
		</form>

		<div id="gv-form-confirmation" class="gv-form__confirmation" role="status" aria-live="polite" hidden>
			<p class="gv-form__confirmation-kicker"><?php esc_html_e('Order Received', 'alex-rose-2026'); ?></p>
			<h3 class="gv-form__confirmation-title"><?php esc_html_e('Thank you. Harold will be in touch.', 'alex-rose-2026'); ?></h3>
			<p class="gv-form__confirmation-body">
				<?php esc_html_e('Your master tailor will personally read your enquiry and reply within one working day to confirm the voucher and take payment.', 'alex-rose-2026'); ?>
			</p>
		</div>
	</div>
</section>
