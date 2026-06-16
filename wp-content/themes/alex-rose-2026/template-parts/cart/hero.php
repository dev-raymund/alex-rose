<?php
/**
 * "Cart" — dark hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="crt-hero">
	<img class="crt-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-4.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="crt-hero__shade" aria-hidden="true"></div>
	<div class="crt-hero__inner">
		<div class="crt-hero__rule" aria-hidden="true"></div>
		<p class="crt-hero__kicker"><?php esc_html_e('Your Reserved Designs', 'alex-rose-2026'); ?></p>
		<h1 class="crt-hero__title"><?php esc_html_e('Reserved Jackets.', 'alex-rose-2026'); ?></h1>
		<p class="crt-hero__lead"><?php esc_html_e('Your designs are saved here. No payment has been taken at this stage.', 'alex-rose-2026'); ?></p>
	</div>
</section>
