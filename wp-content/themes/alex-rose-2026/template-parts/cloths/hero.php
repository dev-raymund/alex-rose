<?php
/**
 * "Our Cloths" — hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="cloths-hero">
	<img class="cloths-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/process-swatches.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="cloths-hero__shade" aria-hidden="true"></div>
	<div class="cloths-hero__inner">
		<div class="cloths-hero__rule" aria-hidden="true"></div>
		<p class="cloths-hero__kicker"><?php esc_html_e('British & European Mills', 'alex-rose-2026'); ?></p>
		<h1 class="cloths-hero__title"><?php esc_html_e('Our Cloths.', 'alex-rose-2026'); ?></h1>
		<p class="cloths-hero__lead"><?php esc_html_e('Nine collections, each sourced personally. Free samples available before you commit to anything.', 'alex-rose-2026'); ?></p>
	</div>
</section>
