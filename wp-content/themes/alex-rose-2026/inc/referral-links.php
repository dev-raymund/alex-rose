<?php
/**
 * Branded influencer referral links + coupon persistence.
 *
 * Maps a short slug (e.g. /brian) to a UTM-tagged landing URL so Google
 * Analytics attributes the visit to the partner instead of "(direct)", and
 * remembers their discount code so it auto-applies once the shopper has items
 * in the cart.
 *
 * Add or edit partners via the `alex_rose_2026_referral_links` filter, e.g.:
 *
 *     add_filter('alex_rose_2026_referral_links', function ($links) {
 *         $links['jane'] = array(
 *             'source'   => 'jane-doe',
 *             'medium'   => 'influencer',
 *             'campaign' => 'founding-referral',
 *             'code'     => 'JANE20',
 *         );
 *         return $links;
 *     });
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

const ALEX_ROSE_2026_REFERRAL_COOKIE = 'ar_ref_code';

/**
 * Registered partner short links, keyed by slug.
 *
 * @return array<string, array<string, string>>
 */
function alex_rose_2026_referral_links(): array {
	$links = array(
		'brian' => array(
			'source'   => 'brian-klimek',
			'medium'   => 'influencer',
			'campaign' => 'founding-referral',
			'code'     => 'STLBMAN20',
		),
	);

	return (array) apply_filters('alex_rose_2026_referral_links', $links);
}

/**
 * Catch /{slug} and redirect to the UTM landing URL, remembering the code.
 */
add_action('template_redirect', 'alex_rose_2026_referral_redirect');

function alex_rose_2026_referral_redirect(): void {
	if (is_admin()) {
		return;
	}

	$request = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
	$path    = trim((string) wp_parse_url($request, PHP_URL_PATH), '/');

	// Support subdirectory installs (strip the WordPress home path prefix).
	$home_path = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
	if ($home_path !== '' && strpos($path, $home_path) === 0) {
		$path = trim(substr($path, strlen($home_path)), '/');
	}

	$links = alex_rose_2026_referral_links();
	if ($path === '' || ! isset($links[ $path ])) {
		return;
	}

	$partner = $links[ $path ];

	$dest = add_query_arg(
		array(
			'utm_source'   => $partner['source'],
			'utm_medium'   => $partner['medium'],
			'utm_campaign' => $partner['campaign'],
		),
		home_url('/')
	);

	if (! empty($partner['code'])) {
		$path_cookie = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
		$domain      = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
		setcookie(ALEX_ROSE_2026_REFERRAL_COOKIE, $partner['code'], time() + 30 * DAY_IN_SECONDS, $path_cookie, $domain);
		$dest = add_query_arg('ar_code', $partner['code'], $dest);
	}

	wp_safe_redirect($dest, 302);
	exit;
}

/**
 * Auto-apply the remembered referral code once the cart has items. Reads the
 * code from the URL first (fresh click) then the cookie (returning visitor).
 */
add_action('wp', 'alex_rose_2026_referral_apply_coupon', 20);

function alex_rose_2026_referral_apply_coupon(): void {
	if (is_admin() || ! function_exists('WC')) {
		return;
	}

	$code = '';
	if (isset($_GET['ar_code'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$code = sanitize_text_field(wp_unslash($_GET['ar_code'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} elseif (isset($_COOKIE[ ALEX_ROSE_2026_REFERRAL_COOKIE ])) {
		$code = sanitize_text_field(wp_unslash($_COOKIE[ ALEX_ROSE_2026_REFERRAL_COOKIE ]));
	}

	if ($code === '') {
		return;
	}

	$cart = WC()->cart;
	if ($cart && ! $cart->is_empty() && ! $cart->has_discount($code)) {
		$cart->apply_coupon($code);
	}
}
