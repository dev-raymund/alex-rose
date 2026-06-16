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

			window.AlexRoseFormSubmit.send(form, {
				confirmation: document.getElementById('pyj-form-confirmation'),
				errorNode: form.querySelector('[data-pyj-error]'),
				submitBtn: form.querySelector('[data-pyj-submit]'),
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
