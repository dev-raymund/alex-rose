<?php
/**
 * Search results
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<main id="main" class="site-main" tabindex="-1">
	<?php if (have_posts()) : ?>
		<header class="page-header">
			<h1 class="page-title page-title--search">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__('Search results for "%s"', 'alex-rose-2026'),
					esc_html(get_search_query())
				);
				?>
			</h1>
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
