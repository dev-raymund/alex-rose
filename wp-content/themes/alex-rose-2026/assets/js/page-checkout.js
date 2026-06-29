/**
 * Checkout — apply the custom promo box via WooCommerce's AJAX coupon endpoint.
 *
 * The promo box lives inside the order summary (inside the checkout <form>), so
 * it can't be a real coupon <form> (nested forms are invalid). Instead the
 * Apply button posts to wc-ajax=apply_coupon, then refreshes the order review.
 */
(function ($) {
	'use strict';

	function applyPromo($promo) {
		if (typeof wc_checkout_params === 'undefined') {
			return;
		}
		var $input = $promo.find('.arco-promo__input');
		var code = ($input.val() || '').trim();
		if (!code) {
			return;
		}

		var $btn = $promo.find('.arco-promo__apply');
		$btn.prop('disabled', true);

		$.ajax({
			type: 'POST',
			url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
			data: {
				security: $promo.data('nonce'),
				coupon_code: code
			},
			dataType: 'html',
			complete: function () {
				// Refresh totals + show any WooCommerce notice (applied / invalid).
				$(document.body).trigger('update_checkout');
			}
		});
	}

	$(document).on('click', '.arco-promo__apply', function (e) {
		e.preventDefault();
		applyPromo($(this).closest('.arco-promo'));
	});

	// Enter in the promo field applies the code instead of submitting the order.
	$(document).on('keydown', '.arco-promo__input', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			e.preventDefault();
			applyPromo($(this).closest('.arco-promo'));
		}
	});
}(jQuery));
