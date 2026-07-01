<?php
/**
 * Feedback survey markup — multi-step form ("Help us improve").
 *
 * Step 1 ("About you") is complete. Steps 2–5 are empty scaffolds: paste each
 * step's questions inside its <fieldset data-fb-step="N">. Any field named
 * fb_* is collected and emailed automatically by the handler in inc/forms.php
 * (alex_rose_2026_handle_fb_submit_feedback), so no PHP changes are needed when
 * you add questions.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$fb_steps = array(
	__('About you', 'alex-rose-2026'),
	__('The website', 'alex-rose-2026'),
	__('The configurator', 'alex-rose-2026'),
	__('Your journey', 'alex-rose-2026'),
	__('Overall', 'alex-rose-2026'),
);
$fb_total = count($fb_steps);

$fb_chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>';
$fb_chev_l  = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>';
$fb_check   = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>';
?>
<main id="main" class="page-feedback" tabindex="-1">

	<section class="fb-hero">
		<div class="fb-hero__inner">
			<p class="fb-hero__eyebrow"><?php esc_html_e('Share your experience', 'alex-rose-2026'); ?></p>
			<h1 class="fb-hero__title"><?php esc_html_e('Help us improve.', 'alex-rose-2026'); ?></h1>
			<p class="fb-hero__lead"><?php esc_html_e('Your answers go directly to Harold. Takes around five minutes and earns you 5% off your next order.', 'alex-rose-2026'); ?></p>
		</div>
	</section>

	<div class="fb-body">
		<div class="fb-body__inner">

			<div class="fb-offer">
				<p class="fb-offer__eyebrow"><?php esc_html_e('Exclusive offer', 'alex-rose-2026'); ?></p>
				<p class="fb-offer__title"><?php esc_html_e('Answer this form and get 5% off your next order.', 'alex-rose-2026'); ?></p>
				<p class="fb-offer__copy"><?php esc_html_e('We will send your discount code as soon as you submit. Takes around five minutes.', 'alex-rose-2026'); ?></p>
			</div>

			<div class="fb-steps">
				<div class="fb-steps__row">
					<?php foreach ($fb_steps as $fb_i => $fb_label) : $fb_n = $fb_i + 1; ?>
						<div class="fb-step<?php echo 0 === $fb_i ? ' is-active' : ''; ?>" data-fb-dot="<?php echo esc_attr((string) $fb_n); ?>">
							<span class="fb-step__num"><?php echo esc_html((string) $fb_n); ?></span>
							<span class="fb-step__label"><?php echo esc_html($fb_label); ?></span>
						</div>
						<?php if ($fb_n < $fb_total) : ?>
							<span class="fb-step__line" aria-hidden="true"></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="fb-steps__bar"><div class="fb-steps__bar-fill" data-fb-bar></div></div>
				<p class="fb-steps__caption" data-fb-caption>
					<?php
					printf(
						/* translators: 1: current step number, 2: total steps, 3: step name */
						esc_html__('Step %1$d of %2$d: %3$s', 'alex-rose-2026'),
						1,
						(int) $fb_total,
						esc_html($fb_steps[0])
					);
					?>
				</p>
			</div>

			<form class="fb-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
				<input type="hidden" name="action" value="fb_submit_feedback">
				<?php wp_nonce_field('fb_submit_feedback', 'fb_nonce'); ?>
				<?php alex_rose_2026_form_honeypot_field(); ?>

				<div class="fb-panels">

					<fieldset class="fb-panel is-active" data-fb-step="1" data-fb-label="<?php echo esc_attr($fb_steps[0]); ?>">
						<div class="fb-grid">
							<div class="fb-field">
								<label class="fb-label" for="fb_email"><?php esc_html_e('Email address', 'alex-rose-2026'); ?><span class="fb-req"> *</span></label>
								<input class="fb-input" id="fb_email" name="fb_email" type="email" required placeholder="your@email.com">
							</div>
							<div class="fb-field">
								<label class="fb-label" for="fb_name"><?php esc_html_e('Full name', 'alex-rose-2026'); ?></label>
								<input class="fb-input" id="fb_name" name="fb_name" type="text" placeholder="<?php esc_attr_e('First name, Last name', 'alex-rose-2026'); ?>">
							</div>
						</div>
						<div class="fb-grid">
							<div class="fb-field">
								<label class="fb-label" for="fb_phone"><?php esc_html_e('Phone number', 'alex-rose-2026'); ?></label>
								<input class="fb-input" id="fb_phone" name="fb_phone" type="tel" placeholder="+44 7700 000000">
							</div>
							<div class="fb-field">
								<span class="fb-label"><?php esc_html_e('Age group', 'alex-rose-2026'); ?></span>
								<div class="fb-choices" data-fb-choice="single">
									<input type="hidden" name="fb_age" value="">
									<?php foreach (array('Under 25', '25–34', '35–44', '45–54', '55–64', '65+') as $fb_opt) : ?>
										<button type="button" class="fb-choice" data-value="<?php echo esc_attr($fb_opt); ?>"><?php echo esc_html($fb_opt); ?></button>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Have you purchased made-to-measure garments before?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_mtm" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
					</fieldset>

					<fieldset class="fb-panel" data-fb-step="2" data-fb-label="<?php echo esc_attr($fb_steps[1]); ?>" hidden>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Were you able to access the website easily?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_access_easily" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('How would you rate the loading speed of the website?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices fb-choices--rating" data-fb-choice="single">
								<input type="hidden" name="fb_loading_speed" value="">
								<?php for ($fb_r = 1; $fb_r <= 5; $fb_r++) : ?>
									<button type="button" class="fb-choice fb-choice--rating" data-value="<?php echo esc_attr((string) $fb_r); ?>"><?php echo esc_html((string) $fb_r); ?></button>
								<?php endfor; ?>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Was the homepage clear and welcoming?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_homepage_clear" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_homepage_suggestions"><?php esc_html_e('Any suggestions to improve the homepage?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_homepage_suggestions" name="fb_homepage_suggestions" rows="4" placeholder="<?php esc_attr_e('Your thoughts...', 'alex-rose-2026'); ?>"></textarea>
						</div>
					</fieldset>

					<fieldset class="fb-panel" data-fb-step="3" data-fb-label="<?php echo esc_attr($fb_steps[2]); ?>" hidden>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Was the “Design My Jacket” button easy to find?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_button_easy_find" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('How easy was it to customise your jacket?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices fb-choices--rating" data-fb-choice="single">
								<input type="hidden" name="fb_customise_ease" value="">
								<?php for ($fb_r = 1; $fb_r <= 5; $fb_r++) : ?>
									<button type="button" class="fb-choice fb-choice--rating" data-value="<?php echo esc_attr((string) $fb_r); ?>"><?php echo esc_html((string) $fb_r); ?></button>
								<?php endfor; ?>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_most_intuitive"><?php esc_html_e('Which part of the configurator felt most intuitive?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_most_intuitive" name="fb_most_intuitive" rows="4" placeholder="<?php esc_attr_e('e.g. fabric selection, lining options...', 'alex-rose-2026'); ?>"></textarea>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_confusing"><?php esc_html_e('Which part felt confusing or could be improved?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_confusing" name="fb_confusing" rows="4" placeholder="<?php esc_attr_e('e.g. sizing, style options...', 'alex-rose-2026'); ?>"></textarea>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Did you feel there were enough fabric and style options?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_enough_options" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Did the fitting room feature work smoothly?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_fitting_room_smooth" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('How realistic did the preview feel?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices fb-choices--rating" data-fb-choice="single">
								<input type="hidden" name="fb_preview_realistic" value="">
								<?php for ($fb_r = 1; $fb_r <= 5; $fb_r++) : ?>
									<button type="button" class="fb-choice fb-choice--rating" data-value="<?php echo esc_attr((string) $fb_r); ?>"><?php echo esc_html((string) $fb_r); ?></button>
								<?php endfor; ?>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_fitting_room_improve"><?php esc_html_e('What improvements would make the fitting room more useful?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_fitting_room_improve" name="fb_fitting_room_improve" rows="4" placeholder="<?php esc_attr_e('Your suggestions...', 'alex-rose-2026'); ?>"></textarea>
						</div>
					</fieldset>

					<fieldset class="fb-panel" data-fb-step="4" data-fb-label="<?php echo esc_attr($fb_steps[3]); ?>" hidden>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Did you feel comfortable entering your details?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_comfortable_details" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_confidence_help"><?php esc_html_e('What would help you feel more confident entering personal details?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_confidence_help" name="fb_confidence_help" rows="4" placeholder="<?php esc_attr_e('e.g. security badges, privacy policy...', 'alex-rose-2026'); ?>"></textarea>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Was the form layout clear and easy to follow?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_form_clear" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Did you encounter any technical issues?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_technical_issues" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('How easy was it to enter your measurements?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices fb-choices--rating" data-fb-choice="single">
								<input type="hidden" name="fb_measurements_ease" value="">
								<?php for ($fb_r = 1; $fb_r <= 5; $fb_r++) : ?>
									<button type="button" class="fb-choice fb-choice--rating" data-value="<?php echo esc_attr((string) $fb_r); ?>"><?php echo esc_html((string) $fb_r); ?></button>
								<?php endfor; ?>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Were the measurement instructions clear?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_measurement_instructions" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_measurement_improve"><?php esc_html_e('What improvements would make the measurement process easier?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_measurement_improve" name="fb_measurement_improve" rows="4" placeholder="<?php esc_attr_e('e.g. video guide, diagrams, live chat...', 'alex-rose-2026'); ?>"></textarea>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Did the “Book a Call” link work properly?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_book_call_work" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Was the booking process straightforward?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_booking_straightforward" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_booking_improve"><?php esc_html_e('What improvements would make booking smoother?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_booking_improve" name="fb_booking_improve" rows="4" placeholder="<?php esc_attr_e('Your suggestions...', 'alex-rose-2026'); ?>"></textarea>
						</div>
					</fieldset>

					<fieldset class="fb-panel" data-fb-step="5" data-fb-label="<?php echo esc_attr($fb_steps[4]); ?>" hidden>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('How would you rate your overall experience?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices fb-choices--rating" data-fb-choice="single">
								<input type="hidden" name="fb_overall_rating" value="">
								<?php for ($fb_r = 1; $fb_r <= 5; $fb_r++) : ?>
									<button type="button" class="fb-choice fb-choice--rating" data-value="<?php echo esc_attr((string) $fb_r); ?>"><?php echo esc_html((string) $fb_r); ?></button>
								<?php endfor; ?>
							</div>
						</div>
						<div class="fb-field">
							<span class="fb-label"><?php esc_html_e('Would you recommend the configurator to a friend?', 'alex-rose-2026'); ?></span>
							<div class="fb-choices" data-fb-choice="single">
								<input type="hidden" name="fb_recommend" value="">
								<button type="button" class="fb-choice" data-value="Yes"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></button>
								<button type="button" class="fb-choice" data-value="No"><?php esc_html_e('No', 'alex-rose-2026'); ?></button>
							</div>
						</div>
						<div class="fb-field">
							<label class="fb-label" for="fb_additional_comments"><?php esc_html_e('Any additional comments or suggestions?', 'alex-rose-2026'); ?></label>
							<textarea class="fb-textarea" id="fb_additional_comments" name="fb_additional_comments" rows="4" placeholder="<?php esc_attr_e('Anything else on your mind...', 'alex-rose-2026'); ?>"></textarea>
						</div>
						<label class="fb-consent">
							<input class="fb-consent__input" type="checkbox" name="fb_consent" value="Yes" required>
							<span class="fb-consent__box" aria-hidden="true"></span>
							<span class="fb-consent__text"><?php esc_html_e("I agree to the use of my details to improve Alex Rose Fine Tailoring's website and customer experience. My information will not be shared with third parties.", 'alex-rose-2026'); ?> <span class="fb-req">*</span></span>
						</label>
					</fieldset>

				</div>

				<div class="fb-nav">
					<button type="button" class="fb-nav__back" data-fb-back hidden><?php echo $fb_chev_l; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e('Back', 'alex-rose-2026'); ?></button>
					<button type="button" class="fb-nav__next" data-fb-next><?php esc_html_e('Continue', 'alex-rose-2026'); ?> <?php echo $fb_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					<button type="submit" class="fb-nav__submit" data-fb-submit hidden><?php esc_html_e('Send feedback', 'alex-rose-2026'); ?> <?php echo $fb_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</div>

				<p class="fb-error" role="alert" hidden></p>
			</form>

			<div class="fb-confirm" hidden>
				<div class="fb-confirm__head">
					<span class="fb-confirm__icon" aria-hidden="true"><?php echo $fb_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div>
						<p class="fb-confirm__title"><?php esc_html_e('Thank you. That is genuinely useful.', 'alex-rose-2026'); ?></p>
						<p class="fb-confirm__copy"><?php esc_html_e('Harold will read your feedback personally.', 'alex-rose-2026'); ?></p>
					</div>
				</div>
				<div class="fb-confirm__code">
					<p class="fb-confirm__code-label"><?php esc_html_e('Your 5% discount code', 'alex-rose-2026'); ?></p>
					<div class="fb-confirm__code-row">
						<span class="fb-confirm__code-check" aria-hidden="true"><?php echo $fb_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="fb-confirm__code-value">FEEDBACK5</span>
					</div>
					<p class="fb-confirm__code-note"><?php esc_html_e('Use this code at checkout on your next order. 5% off your jacket. No expiry date.', 'alex-rose-2026'); ?></p>
				</div>
			</div>

		</div>
	</div>
</main>
