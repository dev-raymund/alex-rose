/**
 * Cross-page customer prefill.
 *
 * The fitting-room and order forms in the "Design Your Jacket" configurator
 * save the visitor's name, email and phone to localStorage under
 * "alexrose_customer". This fills the matching fields on the later order forms
 * (WooCommerce checkout billing, Send Measurements, Feedback) so the details
 * carry through the whole order process without re-typing.
 *
 * Only fills EMPTY fields (never overwrites what the visitor or WooCommerce has
 * already put there), and fires input/change so dependent validation updates.
 */
(function () {
	'use strict';

	var KEY = 'alexrose_customer';

	function load() {
		try {
			var data = JSON.parse(window.localStorage.getItem(KEY));
			if (data && typeof data === 'object') {
				return data;
			}
		} catch (e) {}
		return null;
	}

	function splitName(name) {
		var parts = String(name || '').trim().split(/\s+/).filter(Boolean);
		if (!parts.length) {
			return { first: '', last: '' };
		}
		return { first: parts[0], last: parts.slice(1).join(' ') };
	}

	function setField(selector, value) {
		if (!value) {
			return;
		}
		var el = document.querySelector(selector);
		if (!el || String(el.value || '').trim() !== '') {
			return;
		}
		el.value = value;
		el.dispatchEvent(new Event('input', { bubbles: true }));
		el.dispatchEvent(new Event('change', { bubbles: true }));
	}

	function prefill() {
		var c = load();
		if (!c) {
			return;
		}
		var name = splitName(c.name);

		var pairs = [
			// WooCommerce checkout — billing
			['#billing_first_name', name.first],
			['#billing_last_name', name.last],
			['#billing_email', c.email],
			['#billing_phone', c.phone],
			// Send Measurements
			['#sm-first', name.first],
			['#sm-last', name.last],
			['#sm-email', c.email],
			// Feedback survey
			['input[name="fb_name"]', c.name],
			['input[name="fb_email"]', c.email],
			['input[name="fb_phone"]', c.phone]
		];

		for (var i = 0; i < pairs.length; i++) {
			setField(pairs[i][0], pairs[i][1]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', prefill);
	} else {
		prefill();
	}

	// WooCommerce re-renders checkout fields on some AJAX updates.
	if (window.jQuery) {
		window.jQuery(document.body).on('updated_checkout', prefill);
	}
})();
