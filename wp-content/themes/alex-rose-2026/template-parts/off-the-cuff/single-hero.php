<?php
/**
 * Single Off the Cuff article — hero band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$post_id  = (int) get_the_ID();
$category = alex_rose_2026_off_the_cuff_post_category($post_id);
$cat_name = $category instanceof WP_Term ? $category->name : '';
$author   = get_the_author_meta('display_name', (int) get_post_field('post_author', $post_id));
$image    = get_the_post_thumbnail_url($post_id, 'full');

if (! $image) {
	$image = alex_rose_2026_uploads_url('2026/05/jackets.jpg');
}
?>
<section class="otca-hero">
	<img class="otca-hero__img" src="<?php echo esc_url($image); ?>" alt="" aria-hidden="true" loading="eager">
	<div class="otca-hero__shade" aria-hidden="true"></div>
	<div class="otca-hero__inner ar-container">
		<a class="otca-hero__back" href="<?php echo esc_url(home_url('/off-the-cuff/')); ?>">
			<?php esc_html_e('All Articles', 'alex-rose-2026'); ?> &larr;
		</a>

		<div class="otca-hero__body">
			<div class="otca-hero__meta">
				<?php if ($cat_name) : ?>
					<span class="otca-hero__category"><?php echo esc_html($cat_name); ?></span>
				<?php endif; ?>
				<span class="otca-hero__date"><?php echo esc_html(alex_rose_2026_off_the_cuff_post_date_label($post_id)); ?></span>
				<span class="otca-hero__read"><?php echo esc_html(alex_rose_2026_off_the_cuff_reading_time($post_id)); ?></span>
			</div>
			<h1 class="otca-hero__title"><?php the_title(); ?></h1>
			<?php if ($author) : ?>
				<p class="otca-hero__author">
					<?php
					printf(
						/* translators: %s: article author name */
						esc_html__('By %s', 'alex-rose-2026'),
						esc_html($author)
					);
					?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
