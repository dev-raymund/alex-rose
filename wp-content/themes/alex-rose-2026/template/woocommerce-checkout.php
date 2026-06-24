<?php
/**
 * Full-width wrapper for the WooCommerce checkout page.
 *
 * Loaded via the `template_include` filter in inc/woocommerce.php (not a
 * selectable page template). Outputs the page content — the [woocommerce_checkout]
 * shortcode, which renders our woocommerce/checkout/form-checkout.php override —
 * without the default page title or .entry-content wrapper, so the custom hero
 * can run edge to edge.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>
<main id="main" class="site-main site-main--checkout" tabindex="-1">
	<?php
	while (have_posts()) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
