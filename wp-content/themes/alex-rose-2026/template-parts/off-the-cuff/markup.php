<?php
/**
 * "Off the Cuff" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-off-the-cuff" tabindex="-1">
	<?php
	get_template_part('template-parts/off-the-cuff/hero');
	get_template_part('template-parts/off-the-cuff/featured');
	get_template_part('template-parts/off-the-cuff/grid');
	get_template_part('template-parts/off-the-cuff/newsletter');
	get_template_part('template-parts/off-the-cuff/cta');
	?>
</main>
