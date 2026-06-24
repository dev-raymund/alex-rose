<?php
/**
 * WooCommerce checkout bridge for the Design Your Jacket configurator.
 *
 * "Checkout Now" posts the configured jacket here; we add it to the
 * WooCommerce cart (carrying the full spec + the server-derived fabric price)
 * and redirect the customer to WooCommerce's native checkout to pay. The spec
 * rides along as cart-item data and is copied onto the order line item.
 *
 * Shared helpers live in inc/forms.php:
 *   - alex_rose_2026_fabric_price()
 *   - alex_rose_2026_get_bespoke_product()
 *   - alex_rose_2026_jacket_spec_lines()
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Sanitise a decoded [label => value] spec map for safe storage/display.
 *
 * @param array<string, mixed> $spec
 * @return array<string, string>
 */
function alex_rose_2026_sanitize_spec(array $spec): array {
	$clean = array();
	foreach ($spec as $label => $value) {
		if (! is_scalar($value)) {
			continue;
		}
		$label = sanitize_text_field((string) $label);
		$value = sanitize_text_field((string) $value);
		if ($label !== '' && $value !== '') {
			$clean[ $label ] = $value;
		}
	}
	return $clean;
}

/**
 * wc-ajax endpoint: add the configured jacket to the cart, redirect to checkout.
 *
 * Hooked on `wc_ajax_*`, which runs in a front-end context where WC()->cart is
 * available (unlike admin-post / admin-ajax).
 */
function alex_rose_2026_wc_add_jacket_to_cart(): void {
	$fallback = home_url('/design-your-jacket/');

	$nonce = isset($_POST['ar_order_nonce']) ? sanitize_text_field(wp_unslash((string) $_POST['ar_order_nonce'])) : '';
	if (! $nonce || ! wp_verify_nonce($nonce, 'ar_create_jacket_order')) {
		wp_safe_redirect($fallback);
		exit;
	}

	if (! function_exists('WC') || ! WC()->cart || ! function_exists('wc_get_checkout_url')) {
		wp_safe_redirect($fallback);
		exit;
	}

	$options_raw      = isset($_POST['ar_options']) ? wp_unslash((string) $_POST['ar_options']) : '';
	$measurements_raw = isset($_POST['ar_measurements']) ? wp_unslash((string) $_POST['ar_measurements']) : '';

	$options      = json_decode($options_raw, true);
	$options      = is_array($options) ? $options : array();
	$measurements = json_decode($measurements_raw, true);
	$measurements = is_array($measurements) ? $measurements : array();

	// Authoritative price from the fabric reference id (client value is fallback).
	$fabric_ref   = isset($options['fabric']['referenceId']) ? (string) $options['fabric']['referenceId'] : '';
	$client_price = isset($_POST['ar_price']) ? (float) $_POST['ar_price'] : 0.0;
	$price        = alex_rose_2026_fabric_price($fabric_ref, $client_price > 0 ? $client_price : 595.0);
	if ($price < 0) {
		$price = 0.0;
	}

	$product = alex_rose_2026_get_bespoke_product();
	if (! $product) {
		wp_safe_redirect($fallback);
		exit;
	}

	$clean_measurements = array();
	foreach ($measurements as $key => $value) {
		if (is_scalar($value)) {
			$clean_measurements[ sanitize_key((string) $key) ] = sanitize_text_field((string) $value);
		}
	}

	$fabric_image = isset($options['fabric']['image']) ? esc_url_raw((string) $options['fabric']['image']) : '';

	$cart_item_data = array(
		'ar_jacket' => array(
			'price'        => $price,
			'image'        => $fabric_image,
			'spec'         => alex_rose_2026_sanitize_spec(alex_rose_2026_jacket_spec_lines($options)),
			'measurements' => $clean_measurements,
			'tryon'        => ! empty($options['tryOnResult']) ? 'yes' : '',
			// Unique id so each configured jacket is its own cart line (otherwise
			// WooCommerce merges identical product ids into a single quantity).
			'uid'          => wp_generate_uuid4(),
		),
	);

	WC()->cart->add_to_cart($product->get_id(), 1, 0, array(), $cart_item_data);

	// "reserve" lands on the cart page; "checkout" goes straight to pay.
	$redirect = (isset($_POST['ar_redirect']) && $_POST['ar_redirect'] === 'cart')
		? wc_get_cart_url()
		: wc_get_checkout_url();
	wp_safe_redirect($redirect);
	exit;
}
add_action('wc_ajax_ar_add_jacket_to_cart', 'alex_rose_2026_wc_add_jacket_to_cart');

/**
 * Re-hydrate our custom data when the cart is loaded from the session.
 */
add_filter(
	'woocommerce_get_cart_item_from_session',
	static function ($item, $values) {
		if (! empty($values['ar_jacket'])) {
			$item['ar_jacket'] = $values['ar_jacket'];
		}
		return $item;
	},
	10,
	2
);

/**
 * Apply the per-jacket fabric price to its cart line.
 */
add_action(
	'woocommerce_before_calculate_totals',
	static function ($cart) {
		if (is_admin() && ! defined('DOING_AJAX')) {
			return;
		}
		if (! is_a($cart, 'WC_Cart')) {
			return;
		}
		foreach ($cart->get_cart() as $cart_item) {
			if (isset($cart_item['ar_jacket']['price']) && isset($cart_item['data'])) {
				$cart_item['data']->set_price((float) $cart_item['ar_jacket']['price']);
			}
		}
	},
	20
);

/**
 * Show the jacket spec under the line item in the cart and checkout.
 */
add_filter(
	'woocommerce_get_item_data',
	static function ($item_data, $cart_item) {
		if (empty($cart_item['ar_jacket']['spec'])) {
			return $item_data;
		}
		foreach ($cart_item['ar_jacket']['spec'] as $label => $value) {
			$item_data[] = array(
				'key'   => $label,
				'value' => $value,
			);
		}
		return $item_data;
	},
	10,
	2
);

/**
 * Show the selected fabric image as the line-item thumbnail in the checkout
 * order summary (the review table has no thumbnail column, so we prepend it to
 * the product name and position it with CSS). Checkout only — the cart page
 * already renders its own thumbnail column.
 */
add_filter(
	'woocommerce_cart_item_name',
	static function ($name, $cart_item, $cart_item_key) {
		// Skip the cart page (it has its own thumbnail column). Everywhere else —
		// including the checkout review, which re-renders via AJAX where
		// is_checkout() is false — prepend the fabric swatch.
		$on_cart_page = function_exists('is_cart') && is_cart();
		if (! $on_cart_page && ! empty($cart_item['ar_jacket']['image'])) {
			$img = '<img class="arco-thumb" src="' . esc_url($cart_item['ar_jacket']['image']) . '" alt="" />';
			return $img . $name;
		}
		return $name;
	},
	10,
	3
);

/**
 * Persist the spec + measurements onto the order line item at checkout.
 */
add_action(
	'woocommerce_checkout_create_order_line_item',
	static function ($item, $cart_item_key, $values, $order) {
		if (empty($values['ar_jacket'])) {
			return;
		}
		$jacket = $values['ar_jacket'];

		if (! empty($jacket['spec'])) {
			foreach ($jacket['spec'] as $label => $value) {
				$item->add_meta_data($label, $value, true);
			}
		}
		if (! empty($jacket['measurements'])) {
			$item->add_meta_data('_ar_measurements', wp_json_encode($jacket['measurements']), true);
		}
		if (! empty($jacket['image'])) {
			$item->add_meta_data('_ar_fabric_image', $jacket['image'], true);
		}
		if (! empty($jacket['tryon'])) {
			$item->add_meta_data('_ar_tryon_generated', 'yes', true);
		}
	},
	10,
	4
);

/* -------------------------------------------------------------------------
 * Branded checkout — reproduces the "Complete Your Order" design while
 * keeping WooCommerce's real fields, payment and order processing.
 * ------------------------------------------------------------------------- */

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support('woocommerce');
	}
);

/**
 * Render the checkout page through a full-width template (no page title /
 * .entry-content wrapper) so the custom hero can run edge to edge. Endpoints
 * such as order-received keep the normal page template.
 */
add_filter(
	'template_include',
	static function ($template) {
		if (function_exists('is_checkout') && is_checkout() && ! is_wc_endpoint_url()) {
			$custom = ALEX_ROSE_2026_DIR . '/template/woocommerce-checkout.php';
			if (is_readable($custom)) {
				return $custom;
			}
		}
		return $template;
	},
	99
);

/**
 * Force the classic checkout. New WooCommerce installs build the Checkout page
 * with the Checkout *block*, which ignores our template override + CSS. Rather
 * than hand-editing the page on every environment, render the classic
 * [woocommerce_checkout] shortcode for the main checkout content — so
 * form-checkout.php applies everywhere (block or shortcode page).
 */
add_filter(
	'the_content',
	static function ($content) {
		if (
			function_exists('is_checkout') && is_checkout() && ! is_wc_endpoint_url()
			&& in_the_loop() && is_main_query()
		) {
			return do_shortcode('[woocommerce_checkout]');
		}
		return $content;
	},
	9
);

/**
 * Checkout CSS — only on the checkout page.
 */
add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if (! function_exists('is_checkout') || ! is_checkout() || is_wc_endpoint_url()) {
			return;
		}
		$css = ALEX_ROSE_2026_DIR . '/assets/css/page-checkout.css';
		$ver = is_readable($css) ? (string) filemtime($css) : ALEX_ROSE_2026_VERSION;
		wp_enqueue_style(
			'alex-rose-2026-checkout',
			ALEX_ROSE_2026_URI . '/assets/css/page-checkout.css',
			array('alex-rose-2026'),
			$ver
		);

		// Drop the select2 search widget so Country renders as a plain <select>
		// we can style to match the design. WooCommerce falls back gracefully.
		wp_dequeue_style('select2');
		wp_dequeue_script('select2');
		wp_dequeue_script('selectWoo');
	},
	20
);

/**
 * Relabel the checkout fields to match the design, and drop the company field.
 */
add_filter(
	'woocommerce_checkout_fields',
	static function ($fields) {
		if (isset($fields['billing']['billing_company'])) {
			unset($fields['billing']['billing_company']);
		}

		$labels = array(
			'billing_email'     => __('Email Address', 'alex-rose-2026'),
			'billing_phone'     => __('Telephone', 'alex-rose-2026'),
			'billing_address_1' => __('Address', 'alex-rose-2026'),
			'billing_address_2' => __('Address Line 2 (optional)', 'alex-rose-2026'),
			'billing_city'      => __('Town / City', 'alex-rose-2026'),
			'billing_postcode'  => __('Postcode', 'alex-rose-2026'),
		);
		foreach ($labels as $key => $label) {
			if (isset($fields['billing'][ $key ])) {
				$fields['billing'][ $key ]['label'] = $label;
			}
		}

		if (isset($fields['order']['order_comments'])) {
			$fields['order']['order_comments']['label']       = __('Notes for your tailor (optional)', 'alex-rose-2026');
			$fields['order']['order_comments']['placeholder']  = __('Any fit preferences, style notes, or questions...', 'alex-rose-2026');
		}

		return $fields;
	}
);

/**
 * Add the "Included" rows to the order summary, just above the total.
 */
add_action(
	'woocommerce_review_order_before_order_total',
	static function (): void {
		$rows = array(
			__('Alterations', 'alex-rose-2026')   => __('Included', 'alex-rose-2026'),
			__('Monogramming', 'alex-rose-2026')  => __('Included', 'alex-rose-2026'),
			__('Delivery', 'alex-rose-2026')      => __('Free', 'alex-rose-2026'),
		);
		foreach ($rows as $label => $value) {
			echo '<tr class="arco-included"><th>' . esc_html($label) . '</th><td>' . esc_html($value) . '</td></tr>';
		}
	}
);

/**
 * Move the coupon ("Promo or gift voucher") form into the order summary,
 * above the payment block, to match the design.
 */
add_action(
	'init',
	static function (): void {
		remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
		add_action('woocommerce_review_order_before_payment', 'woocommerce_checkout_coupon_form', 5);
	}
);

/**
 * True when an order contains the bespoke jacket product.
 */
function alex_rose_2026_order_has_bespoke_jacket($order): bool {
	if (! ($order instanceof WC_Order)) {
		return false;
	}
	foreach ($order->get_items() as $item) {
		$product = $item->get_product();
		if ($product && $product->get_sku() === 'ar-bespoke-jacket') {
			return true;
		}
	}
	return false;
}

/**
 * After a jacket order is placed/paid, send the customer to the Send
 * Measurements page instead of the default order-received page. Scoped to
 * jacket orders so any other products keep the normal thank-you page.
 */
add_filter(
	'woocommerce_get_return_url',
	static function ($return_url, $order) {
		if (alex_rose_2026_order_has_bespoke_jacket($order)) {
			return add_query_arg('order_id', $order->get_id(), home_url('/send-measurements/'));
		}
		return $return_url;
	},
	10,
	2
);
