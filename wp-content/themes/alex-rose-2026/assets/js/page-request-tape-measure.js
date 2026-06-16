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

			window.AlexRoseFormSubmit.send(form, {
				confirmation: document.getElementById('rtm-form-confirmation'),
				errorNode: form.querySelector('[data-rtm-error]'),
				submitBtn: form.querySelector('[data-rtm-submit]'),
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
