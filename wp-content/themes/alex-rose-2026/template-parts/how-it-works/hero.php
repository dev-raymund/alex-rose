<?php
/**
 * "How It Works" — hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="hiw-hero">
	<img class="hiw-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/process-cutting.jpg')); ?>" alt="" aria-hidden="true" loading="eager" width="1920" height="900">
	<div class="hiw-hero__shade" aria-hidden="true"></div>
	<div class="hiw-hero__inner">
		<div class="hiw-hero__rule" aria-hidden="true"></div>
		<p class="hiw-hero__kicker"><?php esc_html_e('The Process', 'alex-rose-2026'); ?></p>
		<h1 class="hiw-hero__title"><?php esc_html_e('How It Works.', 'alex-rose-2026'); ?></h1>
		<p class="hiw-hero__lead"><?php esc_html_e('Five simple steps from cloth to door.', 'alex-rose-2026'); ?></p>
	</div>
</section>
