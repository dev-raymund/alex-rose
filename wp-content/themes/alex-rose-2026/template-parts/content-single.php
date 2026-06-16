<?php
/**
 * Single post content
 *
 * @package Alex_Rose_2026
 */
?>
<article <?php post_class(); ?>>
	<header class="single-header">
		<h1 class="single-title"><?php the_title(); ?></h1>
		<p class="single-meta">
			<time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
		</p>
	</header>
	<?php if (has_post_thumbnail()) : ?>
		<div class="single-featured"><?php the_post_thumbnail('large', array('class' => 'alignnone')); ?></div>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
