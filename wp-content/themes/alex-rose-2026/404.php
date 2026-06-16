<?php
/**
 * 404
 *
 * @package Alex_Rose_2026
 */

get_header();
?>
<main id="main" class="site-main" tabindex="-1">
	<header class="page-header page-header--compact">
		<h1 class="page-title"><?php esc_html_e('Page not found', 'alex-rose-2026'); ?></h1>
	</header>
	<p class="lead"><?php esc_html_e('Nothing matches that address. Try a search:', 'alex-rose-2026'); ?></p>
	<div class="search-box">
		<?php get_search_form(); ?>
	</div>
</main>
<?php
get_footer();
