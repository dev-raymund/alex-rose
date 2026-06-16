<?php
/**
 * "Our Story" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-our-story" tabindex="-1">
	<?php
	get_template_part('template-parts/our-story/hero');
	get_template_part('template-parts/our-story/harold');
	get_template_part('template-parts/our-story/principles');
	get_template_part('template-parts/our-story/quote');
	get_template_part('template-parts/our-story/timeline');
	get_template_part('template-parts/our-story/stats');
	get_template_part('template-parts/our-story/cta');
	?>
</main>
