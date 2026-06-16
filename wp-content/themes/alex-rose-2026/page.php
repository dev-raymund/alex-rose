<?php
/**
 * Page template
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<main id="main" class="site-main" tabindex="-1">
	<?php
	while (have_posts()) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<header class="page-header">
				<h1 class="page-title page-title--lg"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
