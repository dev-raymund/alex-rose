<?php
/**
 * Archives
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<main id="main" class="site-main" tabindex="-1">
	<?php if (have_posts()) : ?>
		<header class="page-header">
			<?php the_archive_title('<h1 class="page-title page-title--lg">', '</h1>'); ?>
			<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
		</header>
		<div class="post-list">
			<?php
			while (have_posts()) :
				the_post();
				get_template_part('template-parts/content', 'excerpt');
			endwhile;
			the_posts_pagination();
			?>
		</div>
	<?php else : ?>
		<?php get_template_part('template-parts/content', 'none'); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
