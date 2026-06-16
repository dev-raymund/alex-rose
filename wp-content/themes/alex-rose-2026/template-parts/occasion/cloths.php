<?php
/**
 * "Occasion" — recommended cloth list.
 *
 * Each row links to /cloths/<slug>/.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$occasion = get_query_var('alex_rose_occasion', null);
if (! is_array($occasion) || empty($occasion['cloths'])) {
	return;
}
?>
<section class="occ-cloths">
	<div class="occ-cloths__inner ar-container">
		<header class="occ-cloths__head">
			<div>
				<p class="occ-cloths__kicker"><?php esc_html_e('Cloth Selection', 'alex-rose-2026'); ?></p>
				<h2 class="occ-cloths__title">
					<?php echo esc_html((string) ($occasion['cloths_title'] ?? __('Cloths we recommend.', 'alex-rose-2026'))); ?>
				</h2>
			</div>
			<a class="occ-cloths__samples-link" href="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>">
				<?php esc_html_e('Free samples', 'alex-rose-2026'); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</header>

		<ul class="occ-cloths__list">
			<?php foreach ($occasion['cloths'] as $cloth) :
				$slug   = isset($cloth['slug']) ? (string) $cloth['slug'] : '';
				$name   = isset($cloth['name']) ? (string) $cloth['name'] : '';
				$kicker = isset($cloth['kicker']) ? (string) $cloth['kicker'] : '';
				$price  = isset($cloth['price']) ? (string) $cloth['price'] : '';
				$image  = isset($cloth['image']) ? (string) $cloth['image'] : '';
				$url    = $slug !== '' ? home_url('/cloths/' . $slug . '/') : home_url('/cloths/');
				?>
				<li class="occ-cloths__item">
					<a class="occ-cloth-card" href="<?php echo esc_url($url); ?>">
						<div class="occ-cloth-card__media">
							<?php if ($image !== '') : ?>
								<img class="occ-cloth-card__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<div class="occ-cloth-card__body">
							<?php if ($kicker !== '') : ?>
								<p class="occ-cloth-card__kicker"><?php echo esc_html($kicker); ?></p>
							<?php endif; ?>
							<p class="occ-cloth-card__name"><?php echo esc_html($name); ?></p>
						</div>
						<div class="occ-cloth-card__tail">
							<?php if ($price !== '') : ?>
								<p class="occ-cloth-card__price"><?php echo esc_html($price); ?></p>
							<?php endif; ?>
							<p class="occ-cloth-card__view">
								<?php esc_html_e('View', 'alex-rose-2026'); ?>
								<span aria-hidden="true">&nbsp;&rarr;</span>
							</p>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="occ-cloths__note">
			<?php
			printf(
				/* translators: %s: link to the free samples page */
				esc_html__('Not sure? %s', 'alex-rose-2026'),
				'<a href="' . esc_url(home_url('/request-cloth-samples/')) . '">' . esc_html__('Order free samples.', 'alex-rose-2026') . '</a>'
			);
			?>
		</p>
	</div>
</section>
