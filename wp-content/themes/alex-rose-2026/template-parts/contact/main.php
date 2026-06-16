<?php
/**
 * "Contact" — enquiry form + contact details + map.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$required_mark = '<span class="ct-form__required" aria-hidden="true">*</span>';

$map_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2356.3!2d-1.6435!3d53.8095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48795c0c0c0c0c0c%3A0x0!2s2A%20Rodley%20Lane%2C%20Rodley%2C%20Leeds%20LS13%201HU!5e0!3m2!1sen!2suk!4v1700000000000';
?>
<section class="ct-main">
	<div class="ct-main__inner ar-container ar-container--6xl">
		<div class="ct-main__grid">
			<div class="ct-main__form-col">
				<form
					class="ct-form"
					action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
					method="post"
					data-ct-form
					novalidate
					aria-describedby="ct-form-confirmation"
				>
					<?php wp_nonce_field('ct_send_enquiry', 'ct_nonce'); ?>
					<input type="hidden" name="action" value="ct_send_enquiry">
					<?php alex_rose_2026_form_honeypot_field(); ?>

					<div class="ct-form__grid ct-form__grid--2">
						<div class="ct-form__field">
							<label class="ct-form__label" for="ct-name">
								<?php esc_html_e('Your name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="ct-form__input" type="text" id="ct-name" name="ct_name" placeholder="<?php esc_attr_e('John Smith', 'alex-rose-2026'); ?>" required data-ct-required>
						</div>
						<div class="ct-form__field">
							<label class="ct-form__label" for="ct-email">
								<?php esc_html_e('Email address', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="ct-form__input" type="email" id="ct-email" name="ct_email" placeholder="john@example.com" required data-ct-required>
						</div>
					</div>

					<div class="ct-form__field">
						<label class="ct-form__label" for="ct-phone"><?php esc_html_e('Phone number (optional)', 'alex-rose-2026'); ?></label>
						<input class="ct-form__input" type="tel" id="ct-phone" name="ct_phone" placeholder="+44 7700 000000">
					</div>

					<div class="ct-form__field">
						<label class="ct-form__label" for="ct-message">
							<?php esc_html_e('Your message', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<textarea class="ct-form__input ct-form__textarea" id="ct-message" name="ct_message" rows="5" placeholder="<?php esc_attr_e('Tell us about your jacket, a question you have, or how we can help...', 'alex-rose-2026'); ?>" required data-ct-required></textarea>
					</div>

					<button class="ct-form__btn" type="submit" disabled data-ct-submit>
						<?php esc_html_e('Send Enquiry', 'alex-rose-2026'); ?>
					</button>

					<p class="ct-form__error" data-ct-error role="alert" hidden></p>
				</form>

				<div id="ct-form-confirmation" class="ct-form__confirmation" role="status" aria-live="polite" hidden>
					<p class="ct-form__confirmation-kicker"><?php esc_html_e('Enquiry Received', 'alex-rose-2026'); ?></p>
					<h2 class="ct-form__confirmation-title"><?php esc_html_e('Thank you. Harold will be in touch.', 'alex-rose-2026'); ?></h2>
					<p class="ct-form__confirmation-body">
						<?php esc_html_e('Your master tailor will personally read your enquiry and reply within one working day.', 'alex-rose-2026'); ?>
					</p>
				</div>
			</div>

			<div class="ct-main__details-col">
				<div class="ct-details">
					<div class="ct-details__block">
						<p class="ct-details__label"><?php esc_html_e('Address', 'alex-rose-2026'); ?></p>
						<p class="ct-details__text">
							<?php
							echo wp_kses(
								__('2A Rodley Lane<br>Rodley, Leeds<br>LS13 1HU<br>West Yorkshire, England', 'alex-rose-2026'),
								array('br' => array())
							);
							?>
						</p>
					</div>

					<div class="ct-details__block">
						<p class="ct-details__label"><?php esc_html_e('Telephone', 'alex-rose-2026'); ?></p>
						<a class="ct-details__link" href="tel:+441132570022">0113 257 0022</a>
						<a class="ct-details__link ct-details__link--spaced" href="tel:+441134688588">0113 468 8588</a>
						<p class="ct-details__note"><?php esc_html_e('By prior appointment', 'alex-rose-2026'); ?></p>
					</div>

					<div class="ct-details__block">
						<p class="ct-details__label"><?php esc_html_e('Email', 'alex-rose-2026'); ?></p>
						<a class="ct-details__link" href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>
						<p class="ct-details__note"><?php esc_html_e('We respond within 24 hours', 'alex-rose-2026'); ?></p>
					</div>

					<div class="ct-details__block">
						<p class="ct-details__label"><?php esc_html_e('Opening Hours', 'alex-rose-2026'); ?></p>
						<p class="ct-details__text"><?php esc_html_e('By prior appointment', 'alex-rose-2026'); ?></p>
						<p class="ct-details__text"><?php esc_html_e('Wednesday – Saturday: 10.00 am – 5.30 pm', 'alex-rose-2026'); ?></p>
					</div>

					<div class="ct-details__block">
						<a class="ct-details__showroom" href="<?php echo esc_url(home_url('/showroom/')); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
								<polyline points="9 22 9 12 15 12 15 22"></polyline>
							</svg>
							<?php esc_html_e('Visit the Showroom →', 'alex-rose-2026'); ?>
						</a>
					</div>
				</div>

				<div class="ct-map">
					<iframe
						class="ct-map__iframe"
						title="<?php esc_attr_e('Alex Rose Fine Tailoring, 2A Rodley Lane, Leeds', 'alex-rose-2026'); ?>"
						src="<?php echo esc_url($map_src); ?>"
						width="100%"
						height="100%"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
					></iframe>
				</div>
			</div>
		</div>
	</div>
</section>
