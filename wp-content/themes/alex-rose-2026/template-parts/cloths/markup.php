<?php
/**
 * Shared "Our Cloths" markup.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-cloths" tabindex="-1">
	<?php
	get_template_part('template-parts/cloths/hero');
	get_template_part('template-parts/cloths/grid');
	get_template_part('template-parts/cloths/cta');
	?>
</main>
