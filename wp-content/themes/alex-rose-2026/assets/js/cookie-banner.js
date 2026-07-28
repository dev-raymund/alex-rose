/**
 * Cookie consent banner + consent store (GDPR / UK PECR).
 *
 * Shows #ar-cookie-banner until the visitor makes a choice, then records it in
 * a first-party cookie for 6 months. Exposes a small global API so future
 * analytics/marketing scripts can gate themselves on consent:
 *
 *   window.ARConsent.get()              -> { necessary, analytics, marketing }
 *   window.ARConsent.has('analytics')   -> boolean
 *   window.ARConsent.open()             -> re-open the banner (withdraw/change)
 *   document.addEventListener('ar-consent-change', fn)  // fires on every save
 *
 * Any element with [data-ar-cookie-settings] re-opens the banner on click
 * (e.g. the "Cookie Settings" footer link).
 */
(function () {
	'use strict';

	var COOKIE_NAME = 'ar_cookie_consent';
	var VERSION = 1;
	var MAX_AGE_DAYS = 180;
	var CATEGORIES = ['analytics', 'marketing'];

	var banner = document.getElementById('ar-cookie-banner');
	if (!banner) {
		return;
	}

	var prefs = banner.querySelector('[data-ar-cookie-prefs]');
	var saveBtn = banner.querySelector('[data-ar-cookie-action="save"]');
	var manageBtn = banner.querySelector('[data-ar-cookie-action="manage"]');

	function readCookie() {
		var match = document.cookie.match(
			new RegExp('(?:^|; )' + COOKIE_NAME + '=([^;]*)')
		);
		if (!match) {
			return null;
		}
		try {
			var parsed = JSON.parse(decodeURIComponent(match[1]));
			if (!parsed || parsed.v !== VERSION) {
				return null;
			}
			return parsed;
		} catch (e) {
			return null;
		}
	}

	function writeCookie(consent) {
		var payload = {
			v: VERSION,
			necessary: true,
			analytics: !!consent.analytics,
			marketing: !!consent.marketing,
			t: Date.now()
		};
		var expires = new Date(
			Date.now() + MAX_AGE_DAYS * 24 * 60 * 60 * 1000
		).toUTCString();
		var secure = location.protocol === 'https:' ? '; Secure' : '';
		document.cookie =
			COOKIE_NAME +
			'=' +
			encodeURIComponent(JSON.stringify(payload)) +
			'; path=/; expires=' +
			expires +
			'; SameSite=Lax' +
			secure;
		return payload;
	}

	function currentConsent() {
		var stored = readCookie();
		if (stored) {
			return {
				necessary: true,
				analytics: !!stored.analytics,
				marketing: !!stored.marketing
			};
		}
		return { necessary: true, analytics: false, marketing: false };
	}

	function emitChange(consent) {
		var evt;
		try {
			evt = new CustomEvent('ar-consent-change', { detail: consent });
		} catch (e) {
			evt = document.createEvent('CustomEvent');
			evt.initCustomEvent('ar-consent-change', false, false, consent);
		}
		document.dispatchEvent(evt);
	}

	function hideBanner() {
		banner.setAttribute('hidden', '');
		document.documentElement.classList.remove('ar-cookie-open');
	}

	function showBanner() {
		syncCheckboxes();
		banner.removeAttribute('hidden');
		document.documentElement.classList.add('ar-cookie-open');
	}

	function setPrefsVisible(visible) {
		if (!prefs) {
			return;
		}
		if (visible) {
			prefs.removeAttribute('hidden');
			if (saveBtn) {
				saveBtn.removeAttribute('hidden');
			}
			if (manageBtn) {
				manageBtn.setAttribute('hidden', '');
			}
		} else {
			prefs.setAttribute('hidden', '');
			if (saveBtn) {
				saveBtn.setAttribute('hidden', '');
			}
			if (manageBtn) {
				manageBtn.removeAttribute('hidden');
			}
		}
	}

	function syncCheckboxes() {
		var consent = currentConsent();
		CATEGORIES.forEach(function (cat) {
			var box = banner.querySelector(
				'[data-ar-cookie-category="' + cat + '"]'
			);
			if (box) {
				box.checked = !!consent[cat];
			}
		});
	}

	function collectCheckboxes() {
		var consent = { analytics: false, marketing: false };
		CATEGORIES.forEach(function (cat) {
			var box = banner.querySelector(
				'[data-ar-cookie-category="' + cat + '"]'
			);
			consent[cat] = !!(box && box.checked);
		});
		return consent;
	}

	function save(consent) {
		writeCookie(consent);
		hideBanner();
		setPrefsVisible(false);
		emitChange(currentConsent());
	}

	banner.addEventListener('click', function (event) {
		var trigger = event.target.closest('[data-ar-cookie-action]');
		if (!trigger || !banner.contains(trigger)) {
			return;
		}
		var action = trigger.getAttribute('data-ar-cookie-action');

		if (action === 'accept') {
			save({ analytics: true, marketing: true });
		} else if (action === 'reject') {
			save({ analytics: false, marketing: false });
		} else if (action === 'manage') {
			setPrefsVisible(true);
		} else if (action === 'save') {
			save(collectCheckboxes());
		}
	});

	document.addEventListener('click', function (event) {
		var toggle = event.target.closest('[data-ar-cookie-settings]');
		if (!toggle) {
			return;
		}
		event.preventDefault();
		setPrefsVisible(true);
		showBanner();
	});

	// Public API for gating third-party scripts on consent.
	window.ARConsent = {
		get: currentConsent,
		has: function (category) {
			return !!currentConsent()[category];
		},
		open: function () {
			setPrefsVisible(true);
			showBanner();
		}
	};

	// First visit (no stored choice) — reveal the banner.
	if (!readCookie()) {
		showBanner();
	}
})();
