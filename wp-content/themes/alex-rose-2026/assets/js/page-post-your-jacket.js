/**
 * Post Your Jacket — request box form interactions.
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

	function fieldValue(form, selector) {
		var el = form.querySelector(selector);
		return el ? el.value.trim() : '';
	}

	function joinParts(parts) {
		return parts.filter(function (p) { return p !== ''; }).join(', ');
	}

	function buildSuccess(data, scheduleUrl) {
		var name = escapeHtml(data.name);
		var email = escapeHtml(data.email);
		var addrShort = escapeHtml(joinParts([data.addr1, data.city]));
		var addrFull = escapeHtml(joinParts([data.addr1, data.city, data.postcode]));

		return '' +
			'<div class="pyj-main__grid pyj-success">' +
				'<div class="pyj-success__main">' +
					'<div class="pyj-success__icon" aria-hidden="true">' +
						'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>' +
					'</div>' +
					'<p class="pyj-main__kicker">Request received</p>' +
					'<h2 class="pyj-main__title">Thank you, ' + name + '.</h2>' +
					'<p class="pyj-success__text">We will send your free prepaid box and returns label to <strong>' + addrShort + '</strong> within two working days.</p>' +
					'<p class="pyj-success__text">While you wait, book a short call with Harold. He will talk you through exactly what to send and answer any questions about your jacket.</p>' +
					'<a class="pyj-success__btn" href="' + escapeHtml(scheduleUrl) + '">Book a Consultation with Harold <span aria-hidden="true">&rarr;</span></a>' +
					'<p class="pyj-success__call">Prefer to call? <a href="tel:+441134688588">0113 468 8588</a> Mon&ndash;Fri, 9am&ndash;5pm.</p>' +
				'</div>' +
				'<div class="pyj-success__side">' +
					'<div class="pyj-success__card">' +
						'<p class="pyj-side__kicker">Your details</p>' +
						'<dl class="pyj-success__dl">' +
							'<div><dt>Name</dt><dd>' + name + '</dd></div>' +
							'<div><dt>Email</dt><dd>' + email + '</dd></div>' +
							'<div><dt>Address</dt><dd>' + addrFull + '</dd></div>' +
						'</dl>' +
						'<div class="pyj-success__note"><p>A confirmation has been sent to <span>' + email + '</span>. We will notify you by email when your box has been dispatched.</p></div>' +
					'</div>' +
				'</div>' +
			'</div>';
	}

	function updateSubmitState(form) {
		var submit = form.querySelector('[data-pyj-submit]');
		if (!submit) {
			return;
		}

		var name = form.querySelector('#pyj-name');
		var email = form.querySelector('#pyj-email');
		var addr1 = form.querySelector('#pyj-addr1');
		var city = form.querySelector('#pyj-city');
		var postcode = form.querySelector('#pyj-postcode');

		var ready =
			name && name.value.trim() !== '' &&
			email && isEmailValid(email.value) &&
			addr1 && addr1.value.trim() !== '' &&
			city && city.value.trim() !== '' &&
			postcode && postcode.value.trim() !== '';

		submit.disabled = !ready;
	}

	function bindForm(form) {
		var fields = form.querySelectorAll('[data-pyj-required]');
		fields.forEach(function (field) {
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

			if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
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

			// Capture the values now — onSuccess fires after the form is hidden.
			var data = {
				name: fieldValue(form, '#pyj-name'),
				email: fieldValue(form, '#pyj-email'),
				addr1: fieldValue(form, '#pyj-addr1'),
				addr2: fieldValue(form, '#pyj-addr2'),
				city: fieldValue(form, '#pyj-city'),
				postcode: fieldValue(form, '#pyj-postcode')
			};
			var scheduleUrl = form.getAttribute('data-pyj-schedule-url') || '/schedule-a-call/';

			window.AlexRoseFormSubmit.send(form, {
				errorNode: form.querySelector('[data-pyj-error]'),
				submitBtn: form.querySelector('[data-pyj-submit]'),
				onSuccess: function () {
					var inner = document.querySelector('.pyj-main__inner');
					if (!inner) {
						return;
					}
					inner.innerHTML = buildSuccess(data, scheduleUrl);
					if (typeof inner.scrollIntoView === 'function') {
						try {
							inner.scrollIntoView({ behavior: 'smooth', block: 'start' });
						} catch (e) {
							inner.scrollIntoView();
						}
					}
				}
			});
		});
	}

	function init() {
		var form = document.querySelector('[data-pyj-form]');
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
