<?php
/**
 * "Cloth Collection" — top hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$collection = alex_rose_2026_current_cloth_collection();
if ($collection === null) {
	return;
}
?>
<section class="cc-hero">
	<img class="cc-hero__img" src="<?php echo esc_url($collection['hero_image']); ?>" alt="<?php echo esc_attr($collection['title']); ?>" loading="eager">
	<div class="cc-hero__shade" aria-hidden="true"></div>
	<div class="cc-hero__inner">
		<p class="cc-hero__kicker"><?php echo esc_html($collection['kicker']); ?></p>
		<h1 class="cc-hero__title"><?php echo esc_html($collection['title']); ?></h1>
		<p class="cc-hero__lead"><?php echo esc_html($collection['hero_lead']); ?></p>
	</div>
</section>
