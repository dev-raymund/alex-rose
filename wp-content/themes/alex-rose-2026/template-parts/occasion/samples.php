<?php
/**
 * "Occasion" — sample-jacket 3-column image strip.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$occasion = get_query_var('alex_rose_occasion', null);
if (! is_array($occasion) || empty($occasion['samples'])) {
	return;
}
?>
<section class="occ-samples">
	<div class="occ-samples__inner ar-container">
		<header class="occ-samples__head">
			<p class="occ-samples__kicker"><?php esc_html_e('Sample Jackets', 'alex-rose-2026'); ?></p>
			<h2 class="occ-samples__title">
				<?php echo esc_html((string) ($occasion['samples_title'] ?? __('Made in this cloth and occasion.', 'alex-rose-2026'))); ?>
			</h2>
		</header>
		<div class="occ-samples__grid">
			<?php foreach ($occasion['samples'] as $image_url) :
				if (empty($image_url)) {
					continue;
				}
				?>
				<figure class="occ-sample">
					<img class="occ-sample__img" src="<?php echo esc_url((string) $image_url); ?>" alt="<?php esc_attr_e('Sample jacket', 'alex-rose-2026'); ?>" loading="lazy">
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
