/**
 * Global display-currency switcher.
 *
 * Display-only: converts prices marked with [data-ar-price] (a base GBP
 * amount) into the visitor's chosen currency. The choice is persisted in
 * localStorage under the SAME key the "Design Your Jacket" configurator uses
 * ("alexrose_currency"), so the two stay in sync. WooCommerce still charges in
 * GBP — this only changes displayed marketing figures.
 */
(function () {
	'use strict';

	var KEY = 'alexrose_currency';

	// Rates mirror the configurator (assets/js/page-design.js).
	var RATES = {
		GBP: { symbol: '£', rate: 1 },
		EUR: { symbol: '€', rate: 1.18 },
		USD: { symbol: '$', rate: 1.27 }
	};

	function getCurrency() {
		try {
			var stored = window.localStorage.getItem(KEY);
			if (stored && RATES[stored]) {
				return stored;
			}
		} catch (e) {}
		return 'GBP';
	}

	function saveCurrency(currency) {
		try {
			window.localStorage.setItem(KEY, currency);
		} catch (e) {}
	}

	function formatPrice(gbp, currency, decimals) {
		var meta = RATES[currency] || RATES.GBP;
		var value = gbp * meta.rate;
		var d = (typeof decimals === 'number' && decimals >= 0) ? decimals : 0;
		return meta.symbol + value.toLocaleString('en-GB', {
			minimumFractionDigits: d,
			maximumFractionDigits: d
		});
	}

	function convertPrices(currency) {
		var nodes = document.querySelectorAll('[data-ar-price]');
		Array.prototype.forEach.call(nodes, function (node) {
			var gbp = parseFloat(node.getAttribute('data-ar-price'));
			if (isNaN(gbp)) {
				return;
			}
			var dAttr = node.getAttribute('data-ar-decimals');
			var decimals = (dAttr === null) ? 0 : parseInt(dAttr, 10);
			if (isNaN(decimals)) {
				decimals = 0;
			}
			node.textContent = formatPrice(gbp, currency, decimals);
		});
	}

	function syncButtons(currency) {
		var btns = document.querySelectorAll('[data-ar-currency]');
		Array.prototype.forEach.call(btns, function (btn) {
			var on = btn.getAttribute('data-ar-currency') === currency;
			btn.classList.toggle('is-active', on);
			btn.setAttribute('aria-pressed', on ? 'true' : 'false');
		});
	}

	function markConverting(on) {
		var nodes = document.querySelectorAll('[data-ar-price]');
		Array.prototype.forEach.call(nodes, function (node) {
			node.classList.toggle('is-converting', !!on);
		});
	}

	var loadingEl = null;
	var loadingTimer = null;

	function getOverlay() {
		if (loadingEl) {
			return loadingEl;
		}
		if (!document.body) {
			return null;
		}
		loadingEl = document.createElement('div');
		loadingEl.className = 'ar-currency-loading';
		loadingEl.setAttribute('aria-hidden', 'true');
		var spinner = document.createElement('div');
		spinner.className = 'ar-currency-loading__spinner';
		loadingEl.appendChild(spinner);
		document.body.appendChild(loadingEl);
		return loadingEl;
	}

	function showLoading() {
		var el = getOverlay();
		if (!el) {
			return;
		}
		// Force reflow so the opacity transition runs.
		void el.offsetWidth;
		el.classList.add('is-active');
	}

	function hideLoading() {
		if (loadingEl) {
			loadingEl.classList.remove('is-active');
		}
	}

	function notify(currency) {
		try {
			document.dispatchEvent(new CustomEvent('ar:currencychange', { detail: { currency: currency } }));
		} catch (e) {}
	}

	function apply(currency, persist, withLoading) {
		if (!RATES[currency]) {
			currency = 'GBP';
		}
		if (persist) {
			saveCurrency(currency);
		}

		// Instant feedback on the switcher itself.
		syncButtons(currency);

		if (!withLoading) {
			convertPrices(currency);
			notify(currency);
			return;
		}

		markConverting(true);
		showLoading();
		if (loadingTimer) {
			window.clearTimeout(loadingTimer);
		}
		loadingTimer = window.setTimeout(function () {
			convertPrices(currency);
			markConverting(false);
			hideLoading();
			notify(currency);
		}, 480);
	}

	function closestButton(el) {
		while (el && el.nodeType === 1) {
			if (el.getAttribute && el.getAttribute('data-ar-currency')) {
				return el;
			}
			el = el.parentNode;
		}
		return null;
	}

	// One-time migration: the site defaults to GBP (primary market). Clear any
	// currency a visitor (or we, while testing) previously persisted so the
	// first load after this lands on GBP. Switching afterwards still persists.
	function resetDefaultOnce() {
		try {
			if (!window.localStorage.getItem('alexrose_currency_default')) {
				window.localStorage.removeItem(KEY);
				window.localStorage.setItem('alexrose_currency_default', 'GBP');
			}
		} catch (e) {}
	}

	function init() {
		resetDefaultOnce();
		apply(getCurrency(), false, false);

		document.addEventListener('click', function (e) {
			var btn = closestButton(e.target);
			if (!btn) {
				return;
			}
			e.preventDefault();
			apply(btn.getAttribute('data-ar-currency'), true, true);
		});

		// Keep other tabs (and the configurator's own rail) in sync.
		window.addEventListener('storage', function (e) {
			if (e.key === KEY && e.newValue && RATES[e.newValue]) {
				apply(e.newValue, false, false);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
