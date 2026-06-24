<?php
/**
 * "Showroom" — book an appointment CTA.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="sr-cta">
	<div class="sr-cta__inner ar-container ar-container--6xl">
		<div class="sr-cta__grid">
			<div class="sr-cta__copy">
				<p class="sr-cta__kicker"><?php esc_html_e('Ready to Visit?', 'alex-rose-2026'); ?></p>
				<h2 class="sr-cta__title"><?php esc_html_e('Book an appointment with Harold', 'alex-rose-2026'); ?></h2>
				<p class="sr-cta__lead"><?php esc_html_e('Appointments are by prior arrangement. Call us, send an email, or use the form below and we will confirm a time that suits you.', 'alex-rose-2026'); ?></p>
			</div>

			<div class="sr-cta__actions">
				<a class="sr-cta__btn sr-cta__btn--outline" href="tel:+01132571145">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.78a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.47 16z"></path>
					</svg>
					<?php esc_html_e('Call 0113 257 1145', 'alex-rose-2026'); ?>
				</a>
				<a class="sr-cta__btn sr-cta__btn--gold" href="<?php echo esc_url(home_url('/contact/')); ?>">
					<?php esc_html_e('Send an Enquiry →', 'alex-rose-2026'); ?>
				</a>
			</div>
		</div>
	</div>
</section>
