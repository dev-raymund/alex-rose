<?php
/**
 * Single Off the Cuff article — related posts.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$post_id = (int) get_the_ID();
$related = alex_rose_2026_off_the_cuff_related_articles($post_id, 3);

if (! $related) {
	return;
}
?>
<section class="otca-related">
	<div class="otca-related__inner ar-container">
		<p class="otca-related__kicker"><?php esc_html_e('Continue Reading', 'alex-rose-2026'); ?></p>

		<div class="otca-related__list">
			<?php foreach ($related as $post) : ?>
				<?php
				setup_postdata($post);
				$category = alex_rose_2026_off_the_cuff_post_category((int) $post->ID);
				$cat_name = $category instanceof WP_Term ? $category->name : '';
				$image    = get_the_post_thumbnail_url($post, 'large');
				$author   = get_the_author_meta('display_name', (int) $post->post_author);
				?>
				<article class="otca-related__card">
					<a class="otca-related__link" href="<?php echo esc_url(get_permalink($post)); ?>">
						<?php if ($image) : ?>
							<div class="otca-related__media">
								<img class="otca-related__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" loading="lazy">
							</div>
						<?php endif; ?>
						<div class="otca-related__meta">
							<?php if ($cat_name) : ?>
								<span class="otca-related__category"><?php echo esc_html($cat_name); ?></span>
							<?php endif; ?>
							<span class="otca-related__date"><?php echo esc_html(alex_rose_2026_off_the_cuff_post_date_label((int) $post->ID)); ?></span>
							<span class="otca-related__read"><?php echo esc_html(alex_rose_2026_off_the_cuff_reading_time((int) $post->ID)); ?></span>
						</div>
						<h2 class="otca-related__title"><?php echo esc_html(get_the_title($post)); ?></h2>
						<?php if ($author) : ?>
							<p class="otca-related__author">
								<?php
								printf(
									/* translators: %s: article author name */
									esc_html__('By %s', 'alex-rose-2026'),
									esc_html($author)
								);
								?>
							</p>
						<?php endif; ?>
						<span class="otca-related__cta"><?php esc_html_e('Read more', 'alex-rose-2026'); ?> &rarr;</span>
					</a>
				</article>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>
