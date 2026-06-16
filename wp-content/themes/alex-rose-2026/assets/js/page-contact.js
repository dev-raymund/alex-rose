/**
 * Contact page — enable submit when required fields are valid; POST via
 * the shared form helper.
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
		var submit = form.querySelector('[data-ct-submit]');
		if (!submit) {
			return;
		}

		var name = form.querySelector('#ct-name');
		var email = form.querySelector('#ct-email');
		var message = form.querySelector('#ct-message');

		var ready =
			name && name.value.trim() !== '' &&
			email && isEmailValid(email.value) &&
			message && message.value.trim() !== '';

		submit.disabled = !ready;
	}

	function bindForm(form) {
		var fields = form.querySelectorAll('[data-ct-required]');
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
				confirmation: document.getElementById('ct-form-confirmation'),
				errorNode: form.querySelector('[data-ct-error]'),
				submitBtn: form.querySelector('[data-ct-submit]'),
			});
		});
	}

	function init() {
		var form = document.querySelector('[data-ct-form]');
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
