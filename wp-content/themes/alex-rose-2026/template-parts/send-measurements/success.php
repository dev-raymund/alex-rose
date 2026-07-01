<?php
/**
 * "Send Measurements" — success view.
 *
 * Hidden by default; revealed by page-send-measurements.js once the
 * measurements form is submitted (which also fills the name/email and the
 * Calendly prefill). Shows a confirmation, the booking embed, and the
 * "Exclusive offer for our customers" feedback box.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="sm-success" data-sm-success hidden>

	<div class="sm-success__icon" aria-hidden="true">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
	</div>

	<p class="sm-success__kicker"><?php esc_html_e('Measurements received', 'alex-rose-2026'); ?></p>
	<h2 class="sm-success__title"><?php esc_html_e('Thank you', 'alex-rose-2026'); ?>, <span data-sm-name></span>.</h2>
	<p class="sm-success__copy">
		<?php esc_html_e('We have your measurements and will review them before your jacket is cut. A confirmation has been sent to', 'alex-rose-2026'); ?>
		<span class="sm-success__email" data-sm-email></span>.
	</p>

	<p class="sm-success__kicker"><?php esc_html_e('Next step', 'alex-rose-2026'); ?></p>
	<h3 class="sm-success__subtitle"><?php esc_html_e('Book a Call with Harold.', 'alex-rose-2026'); ?></h3>
	<p class="sm-success__lead"><?php esc_html_e('Harold will confirm every detail with you before a single cut is made. Choose a time that suits you below.', 'alex-rose-2026'); ?></p>

	<div class="sm-success__cal">
		<iframe data-sm-calendly data-cal-base="https://calendly.com/alex-rose-tailor/virtual-fitting" src="about:blank" width="100%" height="100%" title="<?php esc_attr_e('Book a consultation with Harold', 'alex-rose-2026'); ?>" loading="lazy"></iframe>
	</div>

	<p class="sm-success__phone">
		<?php esc_html_e('Prefer to call?', 'alex-rose-2026'); ?>
		<a href="tel:+441134688588">0113 468 8588</a>
		<?php esc_html_e('Wed–Sat, 10 am–4.30 pm.', 'alex-rose-2026'); ?>
	</p>

</div>
