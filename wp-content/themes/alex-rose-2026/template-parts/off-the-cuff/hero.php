<?php
/**
 * "Off the Cuff" — hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="otc-hero">
	<img class="otc-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/jackets.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="otc-hero__shade" aria-hidden="true"></div>
	<div class="otc-hero__inner">
		<div class="otc-hero__rule" aria-hidden="true"></div>
		<p class="otc-hero__kicker"><?php esc_html_e('The Alex Rose Journal', 'alex-rose-2026'); ?></p>
		<h1 class="otc-hero__title"><?php esc_html_e('Off the Cuff.', 'alex-rose-2026'); ?></h1>
		<p class="otc-hero__lead"><?php esc_html_e('Notes on cloth, fit, craft, and dressing well, from the workroom floor.', 'alex-rose-2026'); ?></p>
	</div>
</section>
