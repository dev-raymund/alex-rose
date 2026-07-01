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

	// Prefill the fitting-room / order forms for a logged-in customer.
	$ar_user_name  = '';
	$ar_user_email = '';
	$ar_user_phone = '';
	if (is_user_logged_in()) {
		$ar_user       = wp_get_current_user();
		$ar_user_name  = trim((string) get_user_meta($ar_user->ID, 'first_name', true) . ' ' . (string) get_user_meta($ar_user->ID, 'last_name', true));
		if ($ar_user_name === '') {
			$ar_user_name = (string) $ar_user->display_name;
		}
		$ar_user_email = (string) $ar_user->user_email;
		$ar_user_phone = (string) get_user_meta($ar_user->ID, 'billing_phone', true);
	}
	?>
	<div id="ar-design-app"
		data-uploads-url="<?php echo esc_url(alex_rose_2026_uploads_url('')); ?>"
		data-schedule-url="<?php echo esc_url(home_url('/schedule-a-call/')); ?>"
		data-samples-url="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>"
		data-ajax-url="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		data-cart-url="<?php echo esc_url($ar_cart_url); ?>"
		data-user-name="<?php echo esc_attr($ar_user_name); ?>"
		data-user-email="<?php echo esc_attr($ar_user_email); ?>"
		data-user-phone="<?php echo esc_attr($ar_user_phone); ?>"
		data-order-nonce="<?php echo esc_attr(wp_create_nonce('ar_create_jacket_order')); ?>">
	</div>
</main>
