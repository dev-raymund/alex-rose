<?php
/**
 * "Cart" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-cart" tabindex="-1">
	<?php
	get_template_part('template-parts/cart/hero');
	get_template_part('template-parts/cart/main');
	?>
</main>
