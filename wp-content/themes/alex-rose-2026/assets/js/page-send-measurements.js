/**
 * Send Measurements — option toggle, unit switcher, form validation.
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
		var first = form.querySelector('#sm-first');
		var last = form.querySelector('#sm-last');
		var email = form.querySelector('#sm-email');

		return (
			first && first.value.trim() !== '' &&
			last && last.value.trim() !== '' &&
			email && isEmailValid(email.value)
		);
	}

	function updateSubmitState(form) {
		var submit = form.querySelector('[data-sm-submit]');
		if (submit) {
			submit.disabled = !isFormValid(form);
		}
	}

	/**
	 * The option cards behave as mutually-exclusive tabs: opening one closes
	 * the others. Each toggle points at its panel via aria-controls. Clicking
	 * an already-open toggle collapses it (accordion-style).
	 */
	function initTabs() {
		var toggles = Array.prototype.slice.call(document.querySelectorAll('[data-sm-toggle]'));
		if (!toggles.length) {
			return;
		}

		function panelFor(toggle) {
			var id = toggle.getAttribute('aria-controls');
			return id ? document.getElementById(id) : null;
		}

		function setState(toggle, open) {
			toggle.classList.toggle('is-active', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

			var cta = toggle.querySelector('[data-sm-cta-active]');
			if (cta) {
				cta.hidden = !open;
			}

			var panel = panelFor(toggle);
			if (panel) {
				panel.hidden = !open;
			}
		}

		// Clear any revealed success view so it never lingers over another tab.
		// The Measure Yourself success sits outside the panels; the booking
		// successes live inside theirs, so restore their form too.
		function resetSuccess() {
			var measureSuccess = document.querySelector('[data-sm-success]');
			if (measureSuccess) {
				measureSuccess.hidden = true;
			}

			[
				['[data-sm-call-form]', '[data-sm-call-success]'],
				['[data-sm-post-form]', '[data-sm-post-success]']
			].forEach(function (pair) {
				var panelForm = document.querySelector(pair[0]);
				var panelSuccess = document.querySelector(pair[1]);
				if (panelForm) {
					panelForm.hidden = false;
				}
				if (panelSuccess) {
					panelSuccess.hidden = true;
				}
			});
		}

		toggles.forEach(function (toggle) {
			toggle.addEventListener('click', function () {
				resetSuccess();

				if (toggle.classList.contains('is-active')) {
					setState(toggle, false);
					return;
				}

				toggles.forEach(function (other) {
					setState(other, other === toggle);
				});

				var panel = panelFor(toggle);
				if (panel && typeof panel.scrollIntoView === 'function') {
					panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			});
		});
	}

	function bindVideo(player) {
		player.addEventListener('click', function () {
			if (player.classList.contains('is-playing')) {
				return;
			}

			var videoSrc = player.getAttribute('data-video-src');
			if (!videoSrc) {
				return;
			}

			var video = document.createElement('video');
			video.src = videoSrc;
			video.controls = true;
			video.autoplay = true;
			video.playsInline = true;
			video.setAttribute('title', player.getAttribute('aria-label') || 'Measurement video guide');

			player.innerHTML = '';
			player.appendChild(video);
			player.classList.add('is-playing');
		});
	}

	function updateUnitLabels(form, unit) {
		var attr = unit === 'in' ? 'smLabelIn' : 'smLabelCm';

		form.querySelectorAll('[data-sm-label-cm]').forEach(function (label) {
			var text = label.dataset[attr];
			if (text) {
				label.textContent = text;
			}
		});
	}

	function bindUnits(form) {
		var radios = form.querySelectorAll('[data-sm-unit]');

		radios.forEach(function (radio) {
			radio.addEventListener('change', function () {
				if (radio.checked) {
					updateUnitLabels(form, radio.value);
				}
			});
		});
	}

	function fieldValue(form, selector) {
		var el = form.querySelector(selector);
		return el ? String(el.value || '').trim() : '';
	}

	/**
	 * Reveal the inline success view (confirmation + Calendly + feedback offer)
	 * instead of redirecting. Falls back to the schedule URL if the success
	 * markup is missing.
	 */
	function showSuccess(form) {
		var success = document.querySelector('[data-sm-success]');
		if (!success) {
			window.location.href = form.getAttribute('data-schedule-url') || '/schedule-a-call/';
			return;
		}

		var first = fieldValue(form, '#sm-first');
		var last = fieldValue(form, '#sm-last');
		var email = fieldValue(form, '#sm-email');
		var fullName = (first + ' ' + last).trim();

		var nameEl = success.querySelector('[data-sm-name]');
		if (nameEl) {
			nameEl.textContent = first || 'there';
		}
		var emailEl = success.querySelector('[data-sm-email]');
		if (emailEl) {
			emailEl.textContent = email;
		}

		var iframe = success.querySelector('[data-sm-calendly]');
		if (iframe) {
			var base = iframe.getAttribute('data-cal-base') || 'https://calendly.com/alex-rose-tailor/virtual-fitting';
			iframe.src = base +
				'?name=' + encodeURIComponent(fullName) +
				'&email=' + encodeURIComponent(email) +
				'&hide_gdpr_banner=1&background_color=f7f5f0&text_color=111111&primary_color=c8a96a';
		}

		var panel = document.querySelector('[data-sm-panel]');
		if (panel) {
			panel.hidden = true;
		}

		success.hidden = false;
		if (typeof success.scrollIntoView === 'function') {
			try {
				success.scrollIntoView({ behavior: 'smooth', block: 'start' });
			} catch (e) {
				success.scrollIntoView();
			}
		}
	}

	/* ----- Book a Call panel ----- */

	function bindCallChips(form) {
		var chips = Array.prototype.slice.call(form.querySelectorAll('[data-sm-call-topic]'));
		var hidden = form.querySelector('[data-sm-call-topics-value]');

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				chip.classList.toggle('is-active');
				if (!hidden) {
					return;
				}
				var selected = [];
				chips.forEach(function (c) {
					if (c.classList.contains('is-active')) {
						selected.push(c.getAttribute('data-sm-call-topic'));
					}
				});
				hidden.value = selected.join(', ');
			});
		});
	}

	function isBookingValid(form, cfg) {
		var valid = true;
		form.querySelectorAll(cfg.required).forEach(function (field) {
			if (String(field.value || '').trim() === '') {
				valid = false;
			}
		});
		var email = form.querySelector(cfg.email);
		if (!email || !isEmailValid(email.value)) {
			valid = false;
		}
		return valid;
	}

	function updateBookingSubmit(form, cfg) {
		var submit = form.querySelector(cfg.submit);
		if (submit) {
			submit.disabled = !isBookingValid(form, cfg);
		}
	}

	/**
	 * Point the (now revealed) Calendly iframe at the booking URL, prefilled
	 * with the visitor's name and email so they can pick a slot directly.
	 */
	function fillBookingCalendly(form, cfg) {
		var success = document.querySelector(cfg.success);
		if (!success) {
			return;
		}
		var iframe = success.querySelector(cfg.calendly);
		if (!iframe) {
			return;
		}

		var name = fieldValue(form, cfg.name);
		var email = fieldValue(form, cfg.email);
		var base = iframe.getAttribute('data-cal-base') || 'https://calendly.com/alex-rose-tailor/virtual-fitting';

		iframe.src = base +
			'?name=' + encodeURIComponent(name) +
			'&email=' + encodeURIComponent(email) +
			'&hide_gdpr_banner=1&background_color=f7f5f0&text_color=111111&primary_color=c8a96a';
	}

	function bindBookingForm(form, cfg) {
		var success = document.querySelector(cfg.success);

		form.querySelectorAll(cfg.required).forEach(function (field) {
			field.addEventListener('input', function () {
				updateBookingSubmit(form, cfg);
			});
			field.addEventListener('change', function () {
				updateBookingSubmit(form, cfg);
			});
		});

		updateBookingSubmit(form, cfg);

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!isBookingValid(form, cfg)) {
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
				confirmation: success,
				errorNode: form.querySelector(cfg.error),
				submitBtn: form.querySelector(cfg.submit),
				onSuccess: function () {
					if (typeof cfg.onReveal === 'function') {
						cfg.onReveal(form);
					}
					fillBookingCalendly(form, cfg);
				},
			});
		});
	}

	var CALL_CFG = {
		required: '[data-sm-call-required]',
		name: '#sm-call-name',
		email: '#sm-call-email',
		submit: '[data-sm-call-submit]',
		error: '[data-sm-call-error]',
		success: '[data-sm-call-success]',
		calendly: '[data-sm-call-calendly]'
	};

	var POST_CFG = {
		required: '[data-sm-post-required]',
		name: '#sm-post-name',
		email: '#sm-post-email',
		submit: '[data-sm-post-submit]',
		error: '[data-sm-post-error]',
		success: '[data-sm-post-success]',
		calendly: '[data-sm-post-calendly]',
		onReveal: function (form) {
			var success = document.querySelector('[data-sm-post-success]');
			if (!success) {
				return;
			}
			var nameEl = success.querySelector('[data-sm-post-name]');
			if (nameEl) {
				nameEl.textContent = fieldValue(form, '#sm-post-name').split(/\s+/)[0] || 'there';
			}
			var addressEl = success.querySelector('[data-sm-post-address]');
			if (addressEl) {
				addressEl.textContent = [
					fieldValue(form, '#sm-post-address1'),
					fieldValue(form, '#sm-post-town')
				].filter(Boolean).join(', ');
			}
			var emailEl = success.querySelector('[data-sm-post-email]');
			if (emailEl) {
				emailEl.textContent = fieldValue(form, '#sm-post-email');
			}
		}
	};

	function bindCallForm(form) {
		bindCallChips(form);
		bindBookingForm(form, CALL_CFG);
	}

	function bindPostForm(form) {
		bindBookingForm(form, POST_CFG);
	}

	function bindForm(form) {
		form.querySelectorAll('[data-sm-required]').forEach(function (field) {
			field.addEventListener('input', function () {
				updateSubmitState(form);
			});
			field.addEventListener('change', function () {
				updateSubmitState(form);
			});
		});

		updateSubmitState(form);
		bindUnits(form);

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
				errorNode: form.querySelector('[data-sm-error]'),
				submitBtn: form.querySelector('[data-sm-submit]'),
				onSuccess: function () {
					showSuccess(form);
				},
			});
		});
	}

	function init() {
		var form = document.querySelector('[data-sm-form]');
		var callForm = document.querySelector('[data-sm-call-form]');
		var postForm = document.querySelector('[data-sm-post-form]');
		var player = document.querySelector('[data-sm-video]');

		initTabs();

		if (player) {
			bindVideo(player);
		}

		if (callForm) {
			bindCallForm(callForm);
		}

		if (postForm) {
			bindPostForm(postForm);
		}

		if (form) {
			var scheduleUrl = form.getAttribute('data-schedule-url');
			if (!scheduleUrl) {
				var root = document.querySelector('.page-send-measurements');
				if (root && root.dataset.scheduleUrl) {
					form.setAttribute('data-schedule-url', root.dataset.scheduleUrl);
				}
			}
			bindForm(form);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
