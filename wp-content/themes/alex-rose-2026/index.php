<?php
/**
 * Main template fallback
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<main id="main" class="site-main" tabindex="-1">
	<?php if (have_posts()) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e('Posts', 'alex-rose-2026'); ?></h1>
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
