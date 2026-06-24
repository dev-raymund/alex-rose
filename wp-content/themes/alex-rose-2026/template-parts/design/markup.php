<?php
/**
 * Design Your Jacket — SPA mount point.
 *
 * The page-design.js ES module renders the full configurator into #ar-design-app.
 * config.php and preview.php are kept on disk but are no longer loaded here.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-design" tabindex="-1">
	<?php
	// "Checkout Now" posts here: a wc-ajax endpoint that adds the jacket to the
	// cart and redirects to checkout (front-end context where WC()->cart works).
	$ar_cart_url = class_exists('WC_AJAX')
		? WC_AJAX::get_endpoint('ar_add_jacket_to_cart')
		: home_url('/?wc-ajax=ar_add_jacket_to_cart');
	?>
	<div id="ar-design-app"
		data-uploads-url="<?php echo esc_url(alex_rose_2026_uploads_url('')); ?>"
		data-schedule-url="<?php echo esc_url(home_url('/schedule-a-call/')); ?>"
		data-samples-url="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>"
		data-ajax-url="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		data-cart-url="<?php echo esc_url($ar_cart_url); ?>"
		data-order-nonce="<?php echo esc_attr(wp_create_nonce('ar_create_jacket_order')); ?>">
	</div>
</main>
