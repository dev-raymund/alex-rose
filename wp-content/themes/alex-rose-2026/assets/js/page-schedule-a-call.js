/**
 * Schedule a Call — multistep form with per-step validation.
 */
(function () {
	'use strict';

	var CALENDLY_BASE =
		'https://calendly.com/alex-rose-tailor/virtual-fitting?hide_gdpr_banner=1&background_color=f7f5f0&text_color=111111&primary_color=c8a96a';

	function isEmailValid(value) {
		if (!value) {
			return false;
		}
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
	}

	function getPanelFields(form, step) {
		return form.querySelectorAll('[data-sac-step-field="' + step + '"]');
	}

	function isStepValid(form, step) {
		if (step === 0) {
			var name = form.querySelector('#sac-name');
			var email = form.querySelector('#sac-email');
			var phone = form.querySelector('#sac-phone');
			return (
				name && name.value.trim() !== '' &&
				email && isEmailValid(email.value) &&
				phone && phone.value.trim() !== ''
			);
		}

		if (step === 1) {
			var purpose = form.querySelector('[data-sac-purpose-value]');
			return purpose && purpose.value.trim() !== '';
		}

		return true;
	}

	function updateStepButtons(form, step) {
		var panel = form.querySelector('[data-sac-panel="' + step + '"]');
		if (!panel) {
			return;
		}

		var nextBtn = panel.querySelector('[data-sac-next]');
		var submitBtn = panel.querySelector('[data-sac-submit]');
		var valid = isStepValid(form, step);

		if (nextBtn) {
			nextBtn.disabled = !valid;
		}
		if (submitBtn) {
			submitBtn.disabled = !valid;
		}
	}

	function updateProgress(root, step) {
		root.querySelectorAll('[data-sac-step-indicator]').forEach(function (indicator) {
			var index = parseInt(indicator.getAttribute('data-sac-step-indicator'), 10);
			indicator.classList.toggle('is-active', index === step);
			indicator.classList.toggle('is-complete', index < step);
		});
	}

	function showStep(form, root, step) {
		form.querySelectorAll('[data-sac-panel]').forEach(function (panel) {
			var index = parseInt(panel.getAttribute('data-sac-panel'), 10);
			if (index === step) {
				panel.removeAttribute('hidden');
			} else {
				panel.setAttribute('hidden', '');
			}
		});

		updateProgress(root, step);
		updateStepButtons(form, step);
	}

	function bindForm(form) {
		var root = form.closest('.sac-form-section__inner');
		if (!root) {
			return;
		}

		var currentStep = 0;
		var detailsSent = false;

		var purposeInput = form.querySelector('[data-sac-purpose-value]');
		var occasionInput = form.querySelector('[data-sac-occasion-value]');
		var calendlyFrame = form.querySelector('[data-sac-calendly]');
		var stepTwoError = form.querySelector('[data-sac-error]');

		function submitStepTwoDetails(btn, done) {
			var formData = new FormData(form);
			if (typeof formData.has === 'function' && !formData.has('_ajax')) {
				formData.append('_ajax', '1');
			}

			if (stepTwoError) {
				stepTwoError.textContent = '';
				stepTwoError.setAttribute('hidden', '');
			}

			var prevDisabled = btn.disabled;
			btn.disabled = true;

			fetch(form.getAttribute('action'), {
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
							return { ok: response.ok };
						})
						.then(function (data) {
							return { ok: response.ok && data && data.ok !== false, data: data || {} };
						});
				})
				.then(function (result) {
					btn.disabled = prevDisabled;
					if (!result.ok && stepTwoError) {
						stepTwoError.textContent =
							(result.data && result.data.message) ||
							'We could not send your details right now, but you can still book your call below.';
						stepTwoError.removeAttribute('hidden');
					}
					done(!!result.ok);
				})
				.catch(function () {
					btn.disabled = prevDisabled;
					if (stepTwoError) {
						stepTwoError.textContent = 'We could not send your details right now, but you can still book your call below.';
						stepTwoError.removeAttribute('hidden');
					}
					done(false);
				});
		}

		function updateCalendlySrc() {
			if (!calendlyFrame) {
				return;
			}
			var name = form.querySelector('#sac-name');
			var email = form.querySelector('#sac-email');
			var params = [];
			if (name && name.value.trim() !== '') {
				params.push('name=' + encodeURIComponent(name.value.trim()));
			}
			if (email && email.value.trim() !== '') {
				params.push('email=' + encodeURIComponent(email.value.trim()));
			}
			calendlyFrame.src = CALENDLY_BASE + (params.length ? '&' + params.join('&') : '');
		}

		form.querySelectorAll('[data-sac-purpose]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				detailsSent = false;
				var value = btn.getAttribute('data-sac-purpose') || '';
				if (purposeInput) {
					purposeInput.value = value;
					purposeInput.dispatchEvent(new Event('change', { bubbles: true }));
				}
				form.querySelectorAll('[data-sac-purpose]').forEach(function (other) {
					other.classList.toggle('is-active', other === btn);
				});
			});
		});

		form.querySelectorAll('[data-sac-occasion]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				detailsSent = false;
				var value = btn.getAttribute('data-sac-occasion') || '';
				var isActive = btn.classList.contains('is-active');
				form.querySelectorAll('[data-sac-occasion]').forEach(function (other) {
					other.classList.remove('is-active');
				});
				if (occasionInput) {
					if (isActive) {
						occasionInput.value = '';
					} else {
						btn.classList.add('is-active');
						occasionInput.value = value;
					}
				}
			});
		});

		form.querySelectorAll('[data-sac-step-field]').forEach(function (field) {
			var step = parseInt(field.getAttribute('data-sac-step-field'), 10);
			var handler = function () {
				detailsSent = false;
				updateStepButtons(form, step);
			};
			field.addEventListener('input', handler);
			field.addEventListener('change', handler);
		});

		var notes = form.querySelector('#sac-notes');
		if (notes) {
			notes.addEventListener('input', function () {
				detailsSent = false;
			});
			notes.addEventListener('change', function () {
				detailsSent = false;
			});
		}

		form.querySelectorAll('[data-sac-next]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (!isStepValid(form, currentStep)) {
					if (typeof form.reportValidity === 'function') {
						form.reportValidity();
					}
					return;
				}
				if (currentStep === 1) {
					if (detailsSent) {
						currentStep = 2;
						showStep(form, root, currentStep);
						return;
					}

					submitStepTwoDetails(btn, function (ok) {
						detailsSent = ok;
						// Do not block booking if mail transport fails.
						currentStep = 2;
						showStep(form, root, currentStep);
					});
					return;
				}

				if (currentStep === 2) {
					currentStep = 3;
					updateCalendlySrc();
					showStep(form, root, currentStep);
					return;
				}

				if (currentStep < 3) {
					currentStep += 1;
					showStep(form, root, currentStep);
				}
			});
		});

		form.querySelectorAll('[data-sac-back]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (currentStep > 0) {
					currentStep -= 1;
					showStep(form, root, currentStep);
				}
			});
		});

		showStep(form, root, currentStep);
	}

	function init() {
		var form = document.querySelector('[data-sac-form]');
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
