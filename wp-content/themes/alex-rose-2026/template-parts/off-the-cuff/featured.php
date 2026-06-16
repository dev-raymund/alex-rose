<?php
/**
 * "Off the Cuff" — featured article band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$post = alex_rose_2026_off_the_cuff_featured_post();
if (! $post instanceof WP_Post) {
	return;
}

$category = alex_rose_2026_off_the_cuff_post_category((int) $post->ID);
$cat_name = $category instanceof WP_Term ? $category->name : '';
$image    = get_the_post_thumbnail_url($post, 'large');
?>
<section class="otc-featured">
	<div class="otc-featured__inner">
		<p class="otc-featured__kicker"><?php esc_html_e('Featured', 'alex-rose-2026'); ?></p>

		<article class="otc-featured__card">
			<a class="otc-featured__link" href="<?php echo esc_url(get_permalink($post)); ?>">
				<?php if ($image) : ?>
					<div class="otc-featured__media">
						<img class="otc-featured__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" loading="lazy">
					</div>
				<?php endif; ?>
				<div class="otc-featured__body">
					<div class="otc-featured__meta">
						<?php if ($cat_name) : ?>
							<span class="otc-featured__category"><?php echo esc_html($cat_name); ?></span>
						<?php endif; ?>
						<span class="otc-featured__date"><?php echo esc_html(alex_rose_2026_off_the_cuff_post_date_label((int) $post->ID)); ?></span>
						<span class="otc-featured__read"><?php echo esc_html(alex_rose_2026_off_the_cuff_reading_time((int) $post->ID)); ?></span>
					</div>
					<h2 class="otc-featured__title"><?php echo esc_html(get_the_title($post)); ?></h2>
					<p class="otc-featured__excerpt"><?php echo esc_html(get_the_excerpt($post)); ?></p>
					<span class="otc-featured__cta"><?php esc_html_e('Read article', 'alex-rose-2026'); ?> &rarr;</span>
				</div>
			</a>
		</article>
	</div>
</section>
