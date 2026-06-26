/**
 * Request Tape Measure — form validation + POST via the shared helper.
 */
(function () {
	'use strict';

	function isEmailValid(value) {
		if (!value) {
			return false;
		}
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
	}

	function escapeHtml(value) {
		return String(value == null ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function buildSuccess(data) {
		var name = escapeHtml(data.name);
		var email = escapeHtml(data.email);
		var addrShort = escapeHtml([data.addr1, data.city].filter(Boolean).join(', '));
		var calendly = 'https://calendly.com/alex-rose-tailor/virtual-fitting' +
			'?name=' + encodeURIComponent(data.name) +
			'&email=' + encodeURIComponent(data.email) +
			'&hide_gdpr_banner=1&background_color=f7f5f0&text_color=111111&primary_color=c8a96a';

		return '' +
			'<div class="rtm-success">' +
				'<div class="rtm-success__icon" aria-hidden="true">' +
					'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>' +
				'</div>' +
				'<p class="rtm-success__kicker">On Its Way</p>' +
				'<h2 class="rtm-success__title">Thank you, ' + name + '.</h2>' +
				'<p class="rtm-success__text">Your tape measure and guide will be posted to <strong>' + (addrShort || 'your address') + '</strong> within two working days. A confirmation has been sent to <span class="rtm-success__email">' + email + '</span>.</p>' +
				'<p class="rtm-success__kicker rtm-success__kicker--step">Next step</p>' +
				'<h3 class="rtm-success__subtitle">Book a Call with Harold.</h3>' +
				'<p class="rtm-success__text rtm-success__text--narrow">Once your tape measure arrives, Harold will walk you through every measurement on a short call. Choose a time below.</p>' +
				'<div class="rtm-success__calendar">' +
					'<iframe src="' + escapeHtml(calendly) + '" width="100%" height="100%" title="Book a consultation with Harold" loading="lazy"></iframe>' +
				'</div>' +
				'<p class="rtm-success__call">Prefer to call? <a href="tel:+441134688588">0113 468 8588</a> Wed&ndash;Sat, 10&nbsp;am&ndash;4.30&nbsp;pm.</p>' +
			'</div>';
	}

	function isFormValid(form) {
		var name = form.querySelector('#rtm-name');
		var email = form.querySelector('#rtm-email');
		var addr1 = form.querySelector('#rtm-addr1');
		var city = form.querySelector('#rtm-city');
		var postcode = form.querySelector('#rtm-postcode');

		return (
			name && name.value.trim() !== '' &&
			email && isEmailValid(email.value) &&
			addr1 && addr1.value.trim() !== '' &&
			city && city.value.trim() !== '' &&
			postcode && postcode.value.trim() !== ''
		);
	}

	function updateSubmitState(form) {
		var submit = form.querySelector('[data-rtm-submit]');
		if (submit) {
			submit.disabled = !isFormValid(form);
		}
	}

	function bindForm(form) {
		form.querySelectorAll('[data-rtm-required]').forEach(function (field) {
			field.addEventListener('input', function () {
				updateSubmitState(form);
			});
			field.addEventListener('change', function () {
				updateSubmitState(form);
			});
		});

		updateSubmitState(form);

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!isFormValid(form)) {
				if (typeof form.reportValidity === 'function') {
					form.reportValidity();
				}
				return;
			}

			if (!window.AlexRoseFormSubmit) {
				form.removeAttribute('novalidate');
				form.submit();
				return;
			}

			// Capture now — onSuccess fires after the form is hidden.
			var val = function (selector) {
				var el = form.querySelector(selector);
				return el ? el.value.trim() : '';
			};
			var data = {
				name: val('#rtm-name'),
				email: val('#rtm-email'),
				addr1: val('#rtm-addr1'),
				city: val('#rtm-city')
			};

			window.AlexRoseFormSubmit.send(form, {
				errorNode: form.querySelector('[data-rtm-error]'),
				submitBtn: form.querySelector('[data-rtm-submit]'),
				onSuccess: function () {
					// Replace only the left (form) column; the tips column stays.
					var col = document.querySelector('.rtm-main__form-col');
					if (!col) {
						return;
					}
					col.innerHTML = buildSuccess(data);
					if (typeof col.scrollIntoView === 'function') {
						try {
							col.scrollIntoView({ behavior: 'smooth', block: 'start' });
						} catch (e) {
							col.scrollIntoView();
						}
					}
				}
			});
		});
	}

	function init() {
		var form = document.querySelector('[data-rtm-form]');
		if (!form) {
			return;
		}
		bindForm(form);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
