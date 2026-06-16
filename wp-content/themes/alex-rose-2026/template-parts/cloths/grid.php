<?php
/**
 * "Our Cloths" — collections grid.
 *
 * Pulls every page that uses template/cloth-collection.php and renders a
 * card per collection, sourced from the live WP page + ACF data.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$pages       = alex_rose_2026_cloth_collection_pages();
$collections = alex_rose_2026_cloth_collections();

if (empty($pages)) {
	return;
}
?>
<section class="cloths-grid">
	<div class="cloths-grid__inner ar-container ar-container--6xl">
		<div class="cloths-grid__list">
			<?php foreach ($pages as $page) :
				if (! $page instanceof WP_Post) {
					continue;
				}
				$slug = (string) $page->post_name;
				$col  = $collections[ $slug ] ?? null;
				if (! is_array($col)) {
					continue;
				}

				$title  = (string) $page->post_title;
				$kicker = (string) ( $col['kicker'] ?? '' );
				$desc   = (string) ( $col['hero_lead'] ?? '' );
				$image  = (string) ( $col['hero_image'] ?? '' );
				$url    = (string) get_permalink($page->ID);
				?>
				<article class="cloths-card">
					<a class="cloths-card__link" href="<?php echo esc_url($url); ?>">
						<div class="cloths-card__media">
							<?php if ($image !== '') : ?>
								<img class="cloths-card__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
							<?php endif; ?>
							<div class="cloths-card__shade" aria-hidden="true"></div>
							<div class="cloths-card__caption">
								<?php if ($kicker !== '') : ?>
									<p class="cloths-card__kicker"><?php echo esc_html($kicker); ?></p>
								<?php endif; ?>
								<p class="cloths-card__title"><?php echo esc_html($title); ?></p>
							</div>
						</div>
						<?php if ($desc !== '') : ?>
							<p class="cloths-card__desc"><?php echo esc_html($desc); ?></p>
						<?php endif; ?>
						<span class="cloths-card__cta"><?php esc_html_e('Explore Collection', 'alex-rose-2026'); ?> &rarr;</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
