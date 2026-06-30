<?php
/**
 * Full-width wrapper for the WooCommerce My Account page (and its endpoints).
 *
 * Loaded via the `template_include` filter in inc/woocommerce.php (not a
 * selectable page template). Renders a branded hero, then the page content —
 * the [woocommerce_my_account] shortcode, styled by assets/css/page-account.css
 * — edge to edge, without the default page title.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();

$ar_logged_in = is_user_logged_in();
$ar_user      = $ar_logged_in ? wp_get_current_user() : null;
$ar_name      = $ar_user ? ($ar_user->first_name !== '' ? $ar_user->first_name : $ar_user->display_name) : '';

if ($ar_logged_in) {
	$ar_eyebrow = __('My Account', 'alex-rose-2026');
	$ar_title   = $ar_name !== ''
		/* translators: %s: customer first name */
		? sprintf(__('Welcome back, %s', 'alex-rose-2026'), $ar_name)
		: __('Welcome back', 'alex-rose-2026');
	$ar_sub = __('Manage your reserved jackets, orders, addresses and account details.', 'alex-rose-2026');
} else {
	$ar_eyebrow = __('Account', 'alex-rose-2026');
	$ar_title   = __('Sign in to your account', 'alex-rose-2026');
	$ar_sub     = __('Access your reserved jackets and orders — or create an account to begin.', 'alex-rose-2026');
}
?>
<main id="main" class="site-main site-main--account" tabindex="-1">
	<section class="arac-hero">
		<div class="arac-hero__inner">
			<span class="arac-hero__rule" aria-hidden="true"></span>
			<p class="arac-hero__eyebrow"><?php echo esc_html($ar_eyebrow); ?></p>
			<h1 class="arac-hero__title"><?php echo esc_html($ar_title); ?></h1>
			<p class="arac-hero__sub"><?php echo esc_html($ar_sub); ?></p>
		</div>
	</section>

	<div class="arac-body">
		<div class="arac-body__inner">
			<?php
			while (have_posts()) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>
</main>
<?php
get_footer();
