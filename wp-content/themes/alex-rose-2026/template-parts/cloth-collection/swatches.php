<?php
/**
 * "Cloth Collection" — swatch grid ("Choose your cloth.").
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$collection = alex_rose_2026_current_cloth_collection();
if ($collection === null || empty($collection['swatches'])) {
	return;
}
?>
<section class="cc-swatches">
	<div class="cc-swatches__inner ar-container ar-container--6xl">
		<header class="cc-swatches__head">
			<p class="cc-swatches__kicker"><?php esc_html_e('Available Cloths', 'alex-rose-2026'); ?></p>
			<h2 class="cc-swatches__title"><?php esc_html_e('Choose your cloth.', 'alex-rose-2026'); ?></h2>
		</header>

		<ul class="cc-swatches__grid">
			<?php foreach ((array) $collection['swatches'] as $swatch) : ?>
				<li class="cc-swatch">
					<figure class="cc-swatch__figure">
						<div class="cc-swatch__media">
							<img class="cc-swatch__img" src="<?php echo esc_url($swatch['image']); ?>" alt="<?php echo esc_attr($swatch['alt']); ?>" loading="lazy">
							<span class="cc-swatch__frame" aria-hidden="true"></span>
						</div>
						<figcaption class="cc-swatch__name"><?php echo esc_html($swatch['name']); ?></figcaption>
					</figure>
				</li>
			<?php endforeach; ?>
		</ul>

		<a class="cc-swatches__cta" href="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>">
			<span><?php esc_html_e('Request free cloth samples', 'alex-rose-2026'); ?></span>
			<span class="cc-swatches__cta-arrow" aria-hidden="true">&rarr;</span>
		</a>
	</div>
</section>
