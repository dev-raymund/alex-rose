<?php
/**
 * "Request Tape Measure" — dark hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="rtm-hero">
	<img class="rtm-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/process-tape.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="rtm-hero__shade" aria-hidden="true"></div>
	<div class="rtm-hero__inner">
		<div class="rtm-hero__rule" aria-hidden="true"></div>
		<p class="rtm-hero__kicker"><?php esc_html_e('Your Measurements', 'alex-rose-2026'); ?></p>
		<h1 class="rtm-hero__title"><?php esc_html_e('Request a Tape Measure.', 'alex-rose-2026'); ?></h1>
		<p class="rtm-hero__lead"><?php esc_html_e('Free, posted directly to you with a simple measurement guide.', 'alex-rose-2026'); ?></p>
	</div>
</section>
