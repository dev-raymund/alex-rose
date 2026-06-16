<?php
/**
 * Front page — uses marketing layout when the page has the “Home” template;
 * otherwise optional ACF + editor content.
 *
 * @package Alex_Rose_2026
 */

get_header();

if (alex_rose_2026_is_home_marketing()) {
	get_template_part('template-parts/home/markup');
	get_footer();
	return;
}
?>
<main id="main" class="site-main" tabindex="-1">

	<?php if (function_exists('get_field')) : ?>
		<?php
		$hero_kicker = get_field('hero_kicker');
		$hero_title  = get_field('hero_title');
		$hero_text   = get_field('hero_text');
		?>
		<?php if ($hero_title || $hero_kicker || $hero_text) : ?>
			<section class="hero-block" data-acf="hero">
				<?php if ($hero_kicker) : ?>
					<p class="hero-kicker"><?php echo esc_html($hero_kicker); ?></p>
				<?php endif; ?>
				<?php if ($hero_title) : ?>
					<h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
				<?php endif; ?>
				<?php if ($hero_text) : ?>
					<div class="hero-text"><?php echo wp_kses_post($hero_text); ?></div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php
		$sections = get_field('page_sections');
		if ($sections && is_array($sections)) {
			foreach ($sections as $row) {
				if (! is_array($row) || empty($row['acf_fc_layout'])) {
					continue;
				}
				$layout = sanitize_key((string) $row['acf_fc_layout']);
				$part   = 'template-parts/acf/section-' . $layout;
				if (locate_template($part . '.php', false, false)) {
					set_query_var('acf_row', $row);
					get_template_part('template-parts/acf/section', $layout);
				}
			}
		}
		?>
	<?php endif; ?>

	<?php
	while (have_posts()) :
		the_post();
		?>
		<article <?php post_class('front-page-content'); ?>>
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
