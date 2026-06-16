/**
 * Gift Vouchers page — POST the order form via the shared helper, then
 * hide the form and reveal the sibling #gv-form-confirmation block.
 *
 * The visible radio dots are styled via CSS sibling selectors on
 * `:checked`, so no JS toggling is required for them.
 */
(function () {
	'use strict';

	function init() {
		var form = document.querySelector('[data-gv-form]');
		if (!form) {
			return;
		}

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
				confirmation: document.getElementById('gv-form-confirmation'),
				errorNode: form.querySelector('[data-gv-error]'),
				submitBtn: form.querySelector('[data-gv-submit]'),
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
