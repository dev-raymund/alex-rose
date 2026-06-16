<?php
/**
 * "Schedule a Call" — 3-step consultation booking form.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$required_mark = '<span class="sac-form__required" aria-hidden="true">*</span>';

$purposes = array(
	__('Measurement guidance', 'alex-rose-2026'),
	__('Styling & cloth advice', 'alex-rose-2026'),
	__('Order review', 'alex-rose-2026'),
	__('Gift voucher query', 'alex-rose-2026'),
	__('General enquiry', 'alex-rose-2026'),
	__('Other', 'alex-rose-2026'),
);

$occasions = array(
	__('Smart casual', 'alex-rose-2026'),
	__('Formal business', 'alex-rose-2026'),
	__('Evening & statement', 'alex-rose-2026'),
	__('Special occasion', 'alex-rose-2026'),
	__('Everyday wear', 'alex-rose-2026'),
	__('Not sure yet', 'alex-rose-2026'),
);

$chevron = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 18 6-6-6-6"></path></svg>';
?>
<section class="sac-form-section">
	<div class="sac-form-section__inner ar-container ar-container--3xl">
		<nav class="sac-progress" aria-label="<?php esc_attr_e('Booking progress', 'alex-rose-2026'); ?>">
			<div class="sac-progress__group">
				<div class="sac-progress__step is-active" data-sac-step-indicator="0">
					<span class="sac-progress__dot">1</span>
					<span class="sac-progress__label"><?php esc_html_e('About you', 'alex-rose-2026'); ?></span>
				</div>
				<div class="sac-progress__line" aria-hidden="true"></div>
			</div>
			<div class="sac-progress__group">
				<div class="sac-progress__step" data-sac-step-indicator="1">
					<span class="sac-progress__dot">2</span>
					<span class="sac-progress__label"><?php esc_html_e('Your requirements', 'alex-rose-2026'); ?></span>
				</div>
				<div class="sac-progress__line" aria-hidden="true"></div>
			</div>
			<div class="sac-progress__group">
				<div class="sac-progress__step" data-sac-step-indicator="2">
					<span class="sac-progress__dot">3</span>
					<span class="sac-progress__label"><?php esc_html_e('Confirmation', 'alex-rose-2026'); ?></span>
				</div>
				<div class="sac-progress__line" aria-hidden="true"></div>
			</div>
			<div class="sac-progress__group sac-progress__group--last">
				<div class="sac-progress__step" data-sac-step-indicator="3">
					<span class="sac-progress__dot">4</span>
					<span class="sac-progress__label"><?php esc_html_e('Book slot', 'alex-rose-2026'); ?></span>
				</div>
			</div>
		</nav>

		<form
			class="sac-form"
			id="sac-form"
			action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
			method="post"
			data-sac-form
			novalidate
		>
			<?php wp_nonce_field('sac_book_call', 'sac_nonce'); ?>
			<input type="hidden" name="action" value="sac_book_call">
			<?php alex_rose_2026_form_honeypot_field(); ?>

			<div class="sac-form__panel" data-sac-panel="0">
				<h2 class="sac-form__heading"><?php esc_html_e('Tell us who you are', 'alex-rose-2026'); ?></h2>

				<div class="sac-form__fields">
					<div class="sac-form__field">
						<label class="sac-form__label" for="sac-name">
							<?php esc_html_e('Full name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="sac-form__input" type="text" id="sac-name" name="sac_name" placeholder="<?php esc_attr_e('John Smith', 'alex-rose-2026'); ?>" required data-sac-step-field="0">
					</div>

					<div class="sac-form__field">
						<label class="sac-form__label" for="sac-email">
							<?php esc_html_e('Email address', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="sac-form__input" type="email" id="sac-email" name="sac_email" placeholder="john@example.com" required data-sac-step-field="0">
					</div>

					<div class="sac-form__field">
						<label class="sac-form__label" for="sac-phone">
							<?php esc_html_e('Phone number', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="sac-form__input" type="tel" id="sac-phone" name="sac_phone" placeholder="+44 7700 000000" required data-sac-step-field="0">
					</div>
				</div>

				<button class="sac-form__btn sac-form__btn--next" type="button" data-sac-next disabled>
					<?php esc_html_e('Continue', 'alex-rose-2026'); ?>
					<?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
			</div>

			<div class="sac-form__panel" data-sac-panel="1" hidden>
				<h2 class="sac-form__heading"><?php esc_html_e('What are you looking for?', 'alex-rose-2026'); ?></h2>

				<div class="sac-form__field">
					<p class="sac-form__label sac-form__legend">
						<?php esc_html_e('What is the main purpose of your call?', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</p>
					<div class="sac-form__chip-row">
						<?php foreach ($purposes as $purpose) : ?>
							<button type="button" class="sac-chip" data-sac-purpose="<?php echo esc_attr($purpose); ?>">
								<?php echo esc_html($purpose); ?>
							</button>
						<?php endforeach; ?>
					</div>
					<input type="hidden" name="sac_purpose" value="" data-sac-step-field="1" data-sac-purpose-value>
				</div>

				<div class="sac-form__field sac-form__field--occasion">
					<p class="sac-form__label sac-form__legend"><?php esc_html_e('Occasion (optional)', 'alex-rose-2026'); ?></p>
					<div class="sac-form__chip-row">
						<?php foreach ($occasions as $occasion) : ?>
							<button type="button" class="sac-chip" data-sac-occasion="<?php echo esc_attr($occasion); ?>">
								<?php echo esc_html($occasion); ?>
							</button>
						<?php endforeach; ?>
					</div>
					<input type="hidden" name="sac_occasion" value="" data-sac-occasion-value>
				</div>

				<div class="sac-form__field">
					<label class="sac-form__label" for="sac-notes"><?php esc_html_e('Anything else you would like to discuss? (optional)', 'alex-rose-2026'); ?></label>
					<textarea class="sac-form__input sac-form__textarea" id="sac-notes" name="sac_notes" rows="4" placeholder="<?php esc_attr_e('Cloth preferences, budget, timeframe, questions about the process…', 'alex-rose-2026'); ?>"></textarea>
				</div>

				<div class="sac-form__actions">
					<button class="sac-form__btn sac-form__btn--back" type="button" data-sac-back>
						<?php esc_html_e('Back', 'alex-rose-2026'); ?>
					</button>
					<button class="sac-form__btn sac-form__btn--next" type="button" data-sac-next>
						<?php esc_html_e('Confirm your details', 'alex-rose-2026'); ?>
						<?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
				<p class="sac-form__error" data-sac-error role="alert" hidden></p>
			</div>

			<div class="sac-form__panel" data-sac-panel="2" hidden>
				<h2 class="sac-form__heading sac-confirm__heading"><?php esc_html_e('Confirmation', 'alex-rose-2026'); ?></h2>
				<div class="sac-confirm">
					<div class="sac-confirm__icon" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
					</div>
					<h2 class="sac-confirm__title"><?php esc_html_e('Your details have been sent.', 'alex-rose-2026'); ?></h2>
					<p class="sac-confirm__body"><?php esc_html_e('Harold has everything he needs. Proceed now to choose a time that suits you and book your call directly with him.', 'alex-rose-2026'); ?></p>
					<div class="sac-form__actions sac-form__actions--confirm">
						<button class="sac-form__btn sac-form__btn--back" type="button" data-sac-back>
							<?php esc_html_e('Back', 'alex-rose-2026'); ?>
						</button>
						<button class="sac-form__btn sac-form__btn--next" type="button" data-sac-next>
							<?php esc_html_e('Book Your Call', 'alex-rose-2026'); ?>
							<?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</button>
					</div>
				</div>
			</div>

			<div class="sac-form__panel" data-sac-panel="3" hidden>
				<div class="sac-calendly__head">
					<h2 class="sac-form__heading"><?php esc_html_e('Pick a date and time that suits you', 'alex-rose-2026'); ?></h2>
					<button class="sac-form__btn sac-form__btn--back sac-form__btn--inline" type="button" data-sac-back>
						<?php esc_html_e('Back', 'alex-rose-2026'); ?>
					</button>
				</div>
				<div class="sac-calendly__frame-wrap">
					<iframe
						class="sac-calendly__frame"
						src=""
						data-sac-calendly
						title="<?php esc_attr_e('Book a virtual fitting call', 'alex-rose-2026'); ?>"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
					></iframe>
				</div>
				<p class="sac-calendly__footnote">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: phone link */
							__('Prefer to call us directly? %s Monday to Friday, 9am to 5pm.', 'alex-rose-2026'),
							'<a href="tel:+441134688588">0113 468 8588</a>'
						)
					);
					?>
				</p>

				<input type="hidden" name="sac_date" value="">
				<input type="hidden" name="sac_time" value="">
				<input type="hidden" name="sac_tz" value="">
			</div>
		</form>

	</div>
</section>
