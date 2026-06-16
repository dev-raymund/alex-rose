<?php
/**
 * "Our Story" — hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="os-hero">
	<img class="os-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/history-1945.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="os-hero__shade" aria-hidden="true"></div>
	<div class="os-hero__inner">
		<div class="os-hero__rule" aria-hidden="true"></div>
		<p class="os-hero__kicker"><?php esc_html_e('Our Heritage', 'alex-rose-2026'); ?></p>
		<h1 class="os-hero__title"><?php esc_html_e('Our Heritage.', 'alex-rose-2026'); ?></h1>
		<p class="os-hero__lead"><?php esc_html_e('Eighty years of making it right.', 'alex-rose-2026'); ?></p>
	</div>
</section>
