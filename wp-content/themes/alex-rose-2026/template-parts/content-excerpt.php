<?php
/**
 * Post excerpt (list)
 *
 * @package Alex_Rose_2026
 */
?>
<article <?php post_class('post-card'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<a class="post-card__thumb-link" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('medium_large', array('class' => 'post-card__image')); ?>
		</a>
	<?php endif; ?>
	<div>
		<header class="post-card__header">
			<h2 class="post-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<p class="post-card__meta">
				<time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
			</p>
		</header>
		<div class="post-card__excerpt">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
