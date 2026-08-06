/**
 * "Feel the cloth" free-samples modal.
 *
 * Shows once per visitor on first page load. Guards:
 *   - localStorage flag so it never nags a returning visitor
 *   - skipped on cart / checkout (don't interrupt a purchase)
 *   - waits until the cookie banner is dismissed first, so the two never stack
 *
 * Dismiss via the ×, "Maybe later", the backdrop, or Escape.
 */
(function () {
	'use strict';

	var STORE_KEY = 'ar_samples_modal_seen';
	var SHOW_DELAY = 1200;

	var modal = document.getElementById('ar-samples-modal');
	if (!modal) {
		return;
	}

	function stored(key) {
		try {
			return window.localStorage.getItem(key);
		} catch (e) {
			return null;
		}
	}

	function remember(key) {
		try {
			window.localStorage.setItem(key, '1');
		} catch (e) {
			// Private mode / storage disabled — modal simply may reappear.
		}
	}

	// Already seen, or mid-purchase — never show.
	if (stored(STORE_KEY)) {
		return;
	}
	if (
		document.body.classList.contains('woocommerce-cart') ||
		document.body.classList.contains('woocommerce-checkout')
	) {
		return;
	}

	var lastFocused = null;

	function open() {
		if (stored(STORE_KEY)) {
			return;
		}
		remember(STORE_KEY);
		lastFocused = document.activeElement;
		modal.removeAttribute('hidden');
		// Force reflow so the transition runs from the hidden state.
		void modal.offsetWidth;
		modal.classList.add('is-open');
		document.documentElement.style.overflow = 'hidden';

		var focusTarget = modal.querySelector('.ar-samples__btn--solid') || modal.querySelector('[data-ar-samples-dismiss]');
		if (focusTarget) {
			focusTarget.focus();
		}
		document.addEventListener('keydown', onKeydown);
	}

	function close() {
		modal.classList.remove('is-open');
		document.documentElement.style.overflow = '';
		document.removeEventListener('keydown', onKeydown);

		var finish = function () {
			modal.setAttribute('hidden', '');
			modal.removeEventListener('transitionend', finish);
		};
		modal.addEventListener('transitionend', finish);
		// Fallback in case transitionend doesn't fire.
		window.setTimeout(finish, 400);

		if (lastFocused && typeof lastFocused.focus === 'function') {
			lastFocused.focus();
		}
	}

	function onKeydown(event) {
		if (event.key === 'Escape' || event.keyCode === 27) {
			close();
		}
	}

	modal.addEventListener('click', function (event) {
		if (event.target.closest('[data-ar-samples-dismiss]')) {
			event.preventDefault();
			close();
		}
	});

	function cookieBannerVisible() {
		var banner = document.getElementById('ar-cookie-banner');
		return !!banner && !banner.hasAttribute('hidden');
	}

	function schedule() {
		window.setTimeout(open, SHOW_DELAY);
	}

	function start() {
		if (cookieBannerVisible()) {
			// Let the visitor deal with cookie consent first, then show.
			document.addEventListener('ar-consent-change', function onConsent() {
				document.removeEventListener('ar-consent-change', onConsent);
				schedule();
			});
		} else {
			schedule();
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
})();
