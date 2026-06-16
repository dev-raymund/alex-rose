<?php
/**
 * "Gift Vouchers" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-gift-vouchers" tabindex="-1">
	<?php
	get_template_part('template-parts/gift-vouchers/hero');
	get_template_part('template-parts/gift-vouchers/included');
	get_template_part('template-parts/gift-vouchers/pricing');
	get_template_part('template-parts/gift-vouchers/form');
	?>
</main>
