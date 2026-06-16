<?php
/**
 * "Showroom" — dark hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="sr-hero">
	<img class="sr-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/showroom-exterior.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="sr-hero__shade" aria-hidden="true"></div>
	<div class="sr-hero__inner">
		<div class="sr-hero__rule" aria-hidden="true"></div>
		<p class="sr-hero__kicker"><?php esc_html_e('Visit the Showroom', 'alex-rose-2026'); ?></p>
		<h1 class="sr-hero__title"><?php esc_html_e('Come and See Us in Leeds.', 'alex-rose-2026'); ?></h1>
		<p class="sr-hero__lead"><?php esc_html_e('Our showroom on Rodley Lane is open by appointment. Come in, handle the cloths, and meet the tailor.', 'alex-rose-2026'); ?></p>
	</div>
</section>
