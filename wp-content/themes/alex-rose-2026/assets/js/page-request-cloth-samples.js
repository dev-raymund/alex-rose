/**
 * Request Cloth Samples — accordions, swatch picker (max 3), form validation.
 */
(function () {
	'use strict';

	var MAX_SAMPLES = 3;

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
		var samples = data.samplesText ? escapeHtml(data.samplesText) : '';
		var calendly = 'https://calendly.com/alex-rose-tailor/virtual-fitting' +
			'?name=' + encodeURIComponent(data.name) +
			'&email=' + encodeURIComponent(data.email) +
			'&hide_gdpr_banner=1&background_color=f7f5f0&text_color=111111&primary_color=c8a96a';

		return '' +
			'<div class="rcs-success">' +
				'<div class="rcs-success__icon" aria-hidden="true">' +
					'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>' +
				'</div>' +
				'<p class="rcs-success__kicker">Request Received</p>' +
				'<h2 class="rcs-success__title">Thank you, ' + name + '.</h2>' +
				(samples ? '<p class="rcs-success__samples">Samples requested: ' + samples + '.</p>' : '') +
				'<p class="rcs-success__text">Your cloth samples will be posted to <strong>' + (addrShort || 'your address') + '</strong> within two working days. A confirmation has been sent to <span class="rcs-success__email">' + email + '</span>.</p>' +
				'<p class="rcs-success__kicker rcs-success__kicker--step">Next step</p>' +
				'<h3 class="rcs-success__subtitle">Book a Call with Harold.</h3>' +
				'<p class="rcs-success__text rcs-success__text--narrow">Once your samples arrive, Harold can talk you through the cloths and help you decide. Choose a time below.</p>' +
				'<div class="rcs-success__calendar">' +
					'<iframe src="' + escapeHtml(calendly) + '" width="100%" height="100%" title="Book a consultation with Harold" loading="lazy"></iframe>' +
				'</div>' +
				'<p class="rcs-success__call">Prefer to call? <a href="tel:+441134688588">0113 468 8588</a> Wed&ndash;Sat, 10&nbsp;am&ndash;4.30&nbsp;pm.</p>' +
			'</div>';
	}

	function isFormValid(form) {
		var name = form.querySelector('#rcs-name');
		var email = form.querySelector('#rcs-email');
		var addr1 = form.querySelector('#rcs-addr1');
		var city = form.querySelector('#rcs-city');
		var postcode = form.querySelector('#rcs-postcode');

		return (
			name && name.value.trim() !== '' &&
			email && isEmailValid(email.value) &&
			addr1 && addr1.value.trim() !== '' &&
			city && city.value.trim() !== '' &&
			postcode && postcode.value.trim() !== ''
		);
	}

	function updateSubmitState(form) {
		var submit = form.querySelector('[data-rcs-submit]');
		if (submit) {
			submit.disabled = !isFormValid(form);
		}
	}

	function updateCounter(form, selectedCount) {
		var counter = form.querySelector('[data-rcs-counter]');
		if (!counter) {
			return;
		}

		var remaining = MAX_SAMPLES - selectedCount;
		if (remaining === 1) {
			counter.textContent = '1 remaining';
		} else if (remaining === 0) {
			counter.textContent = 'Maximum selected';
		} else {
			counter.textContent = remaining + ' remaining';
		}
	}

	function syncHiddenInputs(form, selected) {
		var container = form.querySelector('[data-rcs-hidden-inputs]');
		if (!container) {
			return;
		}

		container.innerHTML = '';
		selected.forEach(function (item) {
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'rcs_samples[]';
			input.value = item.value;
			container.appendChild(input);
		});
	}

	function syncSelectedList(form, selected) {
		var wrap = form.querySelector('[data-rcs-selected-wrap]');
		var list = form.querySelector('[data-rcs-selected-list]');
		if (!wrap || !list) {
			return;
		}

		list.innerHTML = '';
		if (selected.length === 0) {
			wrap.setAttribute('hidden', '');
			return;
		}

		wrap.removeAttribute('hidden');
		selected.forEach(function (item) {
			var li = document.createElement('li');
			li.textContent = item.label;
			list.appendChild(li);
		});
	}

	function updateSwatchStates(form, selected) {
		var atMax = selected.length >= MAX_SAMPLES;

		form.querySelectorAll('[data-rcs-swatch]').forEach(function (btn) {
			var value = btn.getAttribute('data-rcs-value');
			var isSelected = selected.some(function (item) {
				return item.value === value;
			});

			btn.classList.toggle('is-selected', isSelected);
			btn.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
			btn.classList.toggle('is-disabled', atMax && !isSelected);
			btn.disabled = atMax && !isSelected;
		});
	}

	function bindAccordions(form) {
		form.querySelectorAll('[data-rcs-accordion-toggle]').forEach(function (toggle) {
			toggle.addEventListener('click', function () {
				var panelId = toggle.getAttribute('aria-controls');
				var panel = panelId ? document.getElementById(panelId) : null;
				if (!panel) {
					return;
				}

				var expanded = toggle.getAttribute('aria-expanded') === 'true';
				toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
				if (expanded) {
					panel.setAttribute('hidden', '');
				} else {
					panel.removeAttribute('hidden');
				}
			});
		});
	}

	function bindSwatches(form) {
		var selected = [];

		form.querySelectorAll('[data-rcs-swatch]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (btn.disabled) {
					return;
				}

				var value = btn.getAttribute('data-rcs-value');
				var label = btn.getAttribute('data-rcs-label') || value;
				var index = selected.findIndex(function (item) {
					return item.value === value;
				});

				if (index >= 0) {
					selected.splice(index, 1);
				} else if (selected.length < MAX_SAMPLES) {
					selected.push({ value: value, label: label });
				}

				updateSwatchStates(form, selected);
				updateCounter(form, selected.length);
				syncHiddenInputs(form, selected);
				syncSelectedList(form, selected);
			});
		});

		updateCounter(form, 0);
	}

	function bindForm(form) {
		form.querySelectorAll('[data-rcs-required]').forEach(function (field) {
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
			var labels = Array.prototype.slice
				.call(form.querySelectorAll('[data-rcs-swatch].is-selected'))
				.map(function (b) {
					return b.getAttribute('data-rcs-label') || b.getAttribute('data-rcs-value') || '';
				})
				.filter(Boolean);
			var data = {
				name: val('#rcs-name'),
				email: val('#rcs-email'),
				addr1: val('#rcs-addr1'),
				city: val('#rcs-city'),
				samplesText: labels.join(', ')
			};

			window.AlexRoseFormSubmit.send(form, {
				errorNode: form.querySelector('[data-rcs-error]'),
				submitBtn: form.querySelector('[data-rcs-submit]'),
				onSuccess: function () {
					var inner = document.querySelector('.rcs-form-section__inner');
					if (!inner) {
						return;
					}
					inner.innerHTML = buildSuccess(data);
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
		var form = document.querySelector('[data-rcs-form]');
		if (!form) {
			return;
		}

		bindAccordions(form);
		bindSwatches(form);
		bindForm(form);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
