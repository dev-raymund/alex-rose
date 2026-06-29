/**
 * Launch — founding-member landing page.
 *
 * Live countdown, AJAX waitlist signup (via the shared form-submit helper),
 * and the visual currency toggle.
 */
(function () {
	'use strict';

	function initCountdown() {
		var el = document.querySelector('.lp__countdown');
		if (!el) {
			return;
		}
		var target = new Date(el.getAttribute('data-countdown')).getTime();
		if (isNaN(target)) {
			return;
		}
		function pad(n) {
			return (n < 10 ? '0' : '') + n;
		}
		function set(key, value) {
			var node = el.querySelector('[data-cd="' + key + '"]');
			if (node) {
				node.textContent = value;
			}
		}
		function tick() {
			var diff = Math.max(0, target - Date.now());
			set('days', Math.floor(diff / 86400000));
			set('hours', pad(Math.floor((diff % 86400000) / 3600000)));
			set('mins', pad(Math.floor((diff % 3600000) / 60000)));
			set('secs', pad(Math.floor((diff % 60000) / 1000)));
		}
		tick();
		window.setInterval(tick, 1000);
	}

	function initForm() {
		var form = document.querySelector('.lp__form');
		if (!form || !window.AlexRoseFormSubmit) {
			return;
		}
		var confirmation = document.querySelector('.lp__confirm');
		var errorNode = form.querySelector('.lp__error');
		var submitBtn = form.querySelector('.lp__submit');

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			window.AlexRoseFormSubmit.send(form, {
				confirmation: confirmation,
				errorNode: errorNode,
				submitBtn: submitBtn
			});
		});
	}

	function initCurrency() {
		var btns = document.querySelectorAll('.lp__currency-btn');
		if (!btns.length) {
			return;
		}
		Array.prototype.forEach.call(btns, function (btn) {
			btn.addEventListener('click', function () {
				Array.prototype.forEach.call(btns, function (other) {
					other.classList.remove('is-active');
				});
				btn.classList.add('is-active');
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initCountdown();
		initForm();
		initCurrency();
	});
})();
