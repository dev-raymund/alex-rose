<?php
/**
 * Single post
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<?php
while (have_posts()) :
	the_post();
	if (alex_rose_2026_is_off_the_cuff_post()) {
		get_template_part('template-parts/off-the-cuff/single', 'markup');
	} else {
		?>
		<main id="main" class="site-main" tabindex="-1">
			<?php get_template_part('template-parts/content', 'single'); ?>
		</main>
		<?php
	}
endwhile;
?>
<?php
get_footer();
