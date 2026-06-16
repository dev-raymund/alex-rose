<?php
/**
 * FAQ — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-faq" tabindex="-1">
	<?php
	get_template_part('template-parts/faq/hero');
	get_template_part('template-parts/faq/list');
	?>
</main>
