<?php
/**
 * Single Off the Cuff article — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-off-the-cuff page-otc-article" tabindex="-1">
	<?php
	get_template_part('template-parts/off-the-cuff/single-hero');
	get_template_part('template-parts/off-the-cuff/single-content');
	get_template_part('template-parts/off-the-cuff/single-related');
	get_template_part('template-parts/off-the-cuff/newsletter');
	get_template_part('template-parts/off-the-cuff/cta');
	?>
</main>
