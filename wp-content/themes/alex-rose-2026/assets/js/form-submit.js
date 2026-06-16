/**
 * Shared front-end submit helper for every public form on the site.
 *
 * Each page-specific script (page-contact.js, page-gift-vouchers.js, etc.)
 * decides WHEN to submit and which DOM elements represent the confirmation
 * / error region, then defers the actual network call to this module via
 * `window.AlexRoseFormSubmit.send(form, opts)`.
 *
 * The helper POSTs the form via fetch() with the AJAX flag so the matching
 * PHP handler in inc/forms.php returns JSON instead of redirecting.
 */
(function () {
	'use strict';

	function ensureAjaxFlag(formData) {
		if (typeof formData.has === 'function' && formData.has('_ajax')) {
			return;
		}
		formData.append('_ajax', '1');
	}

	function setBusy(form, busy, submitBtn) {
		if (busy) {
			form.classList.add('is-submitting');
			form.setAttribute('aria-busy', 'true');
			if (submitBtn) {
				submitBtn.setAttribute('data-prev-disabled', submitBtn.disabled ? '1' : '0');
				submitBtn.disabled = true;
			}
		} else {
			form.classList.remove('is-submitting');
			form.removeAttribute('aria-busy');
			if (submitBtn) {
				var prev = submitBtn.getAttribute('data-prev-disabled');
				submitBtn.disabled = prev === '1';
				submitBtn.removeAttribute('data-prev-disabled');
			}
		}
	}

	function showError(errorNode, message) {
		if (!errorNode) {
			return;
		}
		errorNode.textContent = message || '';
		if (message) {
			errorNode.removeAttribute('hidden');
		} else {
			errorNode.setAttribute('hidden', '');
		}
	}

	function showConfirmation(form, confirmation, opts) {
		var hideElements = (opts && opts.hideAlso) || [];

		form.setAttribute('hidden', '');
		hideElements.forEach(function (node) {
			if (node) {
				node.setAttribute('hidden', '');
			}
		});

		if (!confirmation) {
			return;
		}
		confirmation.removeAttribute('hidden');
		if (typeof confirmation.scrollIntoView === 'function') {
			try {
				confirmation.scrollIntoView({ behavior: 'smooth', block: 'center' });
			} catch (e) {
				confirmation.scrollIntoView();
			}
		}
		confirmation.setAttribute('tabindex', '-1');
		if (typeof confirmation.focus === 'function') {
			try {
				confirmation.focus({ preventScroll: true });
			} catch (e) {
				confirmation.focus();
			}
		}
	}

	function defaultGenericError() {
		return 'Something went wrong sending your message. Please try again or call us.';
	}

	/**
	 * Submit a form via fetch and orchestrate the standard UI states.
	 *
	 * @param {HTMLFormElement} form
	 * @param {Object} [opts]
	 * @param {HTMLElement} [opts.confirmation] Element to reveal on success.
	 * @param {HTMLElement} [opts.errorNode]    Element to fill on failure.
	 * @param {HTMLElement} [opts.submitBtn]    Submit button (busy state).
	 * @param {HTMLElement[]} [opts.hideAlso]   Extra elements to hide on success.
	 * @param {Function} [opts.onSuccess]
	 * @param {Function} [opts.onError]
	 */
	function send(form, opts) {
		opts = opts || {};

		var url = form.getAttribute('action');
		if (!url) {
			return;
		}

		var formData = new FormData(form);
		ensureAjaxFlag(formData);

		showError(opts.errorNode, '');
		setBusy(form, true, opts.submitBtn);

		fetch(url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				Accept: 'application/json',
			},
		})
			.then(function (response) {
				return response
					.json()
					.catch(function () {
						return { ok: response.ok, message: defaultGenericError() };
					})
					.then(function (data) {
						return { ok: response.ok && data && data.ok !== false, data: data || {} };
					});
			})
			.then(function (result) {
				setBusy(form, false, opts.submitBtn);

				if (!result.ok) {
					var msg = (result.data && result.data.message) || defaultGenericError();
					showError(opts.errorNode, msg);
					if (typeof opts.onError === 'function') {
						opts.onError(result.data);
					}
					return;
				}

				showConfirmation(form, opts.confirmation, opts);
				if (typeof opts.onSuccess === 'function') {
					opts.onSuccess(result.data);
				}
			})
			.catch(function () {
				setBusy(form, false, opts.submitBtn);
				showError(opts.errorNode, defaultGenericError());
				if (typeof opts.onError === 'function') {
					opts.onError({});
				}
			});
	}

	window.AlexRoseFormSubmit = { send: send };
})();
