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

	function bindToggle(toggle, panel) {
		var ctaActive = toggle.querySelector('[data-sm-cta-active]');

		function setOpen(open) {
			toggle.classList.toggle('is-active', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			panel.hidden = !open;

			if (ctaActive) {
				ctaActive.hidden = !open;
			}

			if (open) {
				panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		}

		toggle.addEventListener('click', function () {
			setOpen(!toggle.classList.contains('is-active'));
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
		var toggle = document.querySelector('[data-sm-toggle]');
		var panel = document.querySelector('[data-sm-panel]');
		var form = document.querySelector('[data-sm-form]');
		var player = document.querySelector('[data-sm-video]');

		if (toggle && panel) {
			bindToggle(toggle, panel);
		}

		if (player) {
			bindVideo(player);
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
