<?php
/**
 * "Off the Cuff" — filter bar + three-column article grid.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$filters  = alex_rose_2026_off_the_cuff_filters();
$articles = alex_rose_2026_off_the_cuff_articles();
?>
<section class="otc-grid">
	<div class="otc-grid__inner ar-container">
		<div class="otc-grid__toolbar">
			<p class="otc-grid__kicker"><?php esc_html_e('All Articles', 'alex-rose-2026'); ?></p>
			<?php if ($filters) : ?>
				<div class="otc-grid__filters" role="tablist" aria-label="<?php esc_attr_e('Filter articles by category', 'alex-rose-2026'); ?>" data-otc-filters>
					<?php foreach ($filters as $filter) : ?>
						<button
							type="button"
							class="otc-filter<?php echo $filter['slug'] === 'all' ? ' is-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo $filter['slug'] === 'all' ? 'true' : 'false'; ?>"
							data-otc-filter="<?php echo esc_attr((string) $filter['slug']); ?>"
						>
							<?php echo esc_html((string) $filter['label']); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="otc-grid__list" data-otc-grid>
			<?php if ($articles) : ?>
				<?php foreach ($articles as $post) : ?>
					<?php
					setup_postdata($post);
					$category = alex_rose_2026_off_the_cuff_post_category((int) $post->ID);
					$cat_slug = $category instanceof WP_Term ? $category->slug : '';
					$cat_name = $category instanceof WP_Term ? $category->name : '';
					$image    = get_the_post_thumbnail_url($post, 'large');
					$author   = get_the_author_meta('display_name', (int) $post->post_author);
					?>
					<article class="otc-card" data-category="<?php echo esc_attr($cat_slug); ?>">
						<a class="otc-card__link" href="<?php echo esc_url(get_permalink($post)); ?>">
							<?php if ($image) : ?>
								<div class="otc-card__media">
									<img class="otc-card__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" loading="lazy">
								</div>
							<?php endif; ?>
							<div class="otc-card__meta">
								<?php if ($cat_name) : ?>
									<span class="otc-card__category"><?php echo esc_html($cat_name); ?></span>
								<?php endif; ?>
								<span class="otc-card__date"><?php echo esc_html(alex_rose_2026_off_the_cuff_post_date_label((int) $post->ID)); ?></span>
								<span class="otc-card__read"><?php echo esc_html(alex_rose_2026_off_the_cuff_reading_time((int) $post->ID)); ?></span>
							</div>
							<h2 class="otc-card__title"><?php echo esc_html(get_the_title($post)); ?></h2>
							<p class="otc-card__excerpt"><?php echo esc_html(get_the_excerpt($post)); ?></p>
							<?php if ($author) : ?>
								<p class="otc-card__author">
									<?php
									printf(
										/* translators: %s: article author name */
										esc_html__('By %s', 'alex-rose-2026'),
										esc_html($author)
									);
									?>
								</p>
							<?php endif; ?>
							<span class="otc-card__cta"><?php esc_html_e('Read more', 'alex-rose-2026'); ?> &rarr;</span>
						</a>
					</article>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
				<p class="otc-grid__empty otc-grid__filter-empty" data-otc-filter-empty hidden><?php esc_html_e('No articles in this category yet.', 'alex-rose-2026'); ?></p>
			<?php else : ?>
				<p class="otc-grid__empty"><?php esc_html_e('No articles published yet.', 'alex-rose-2026'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
