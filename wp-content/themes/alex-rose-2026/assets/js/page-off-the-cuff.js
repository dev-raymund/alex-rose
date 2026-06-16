/**
 * Off the Cuff — article category filters + newsletter signup.
 */
(function () {
	'use strict';

	function initFilters() {
		var filterBar = document.querySelector('[data-otc-filters]');
		var grid = document.querySelector('[data-otc-grid]');
		if (!filterBar || !grid) {
			return;
		}

		var cards = Array.prototype.slice.call(grid.querySelectorAll('.otc-card[data-category]'));
		var filterEmpty = grid.querySelector('[data-otc-filter-empty]');

		function applyFilter(slug) {
			var visible = 0;

			cards.forEach(function (card) {
				var cat = card.getAttribute('data-category');
				var show = slug === 'all' || cat === slug;
				if (show) {
					card.removeAttribute('hidden');
					visible += 1;
				} else {
					card.setAttribute('hidden', '');
				}
			});

			if (filterEmpty) {
				if (slug !== 'all' && visible === 0) {
					filterEmpty.removeAttribute('hidden');
				} else {
					filterEmpty.setAttribute('hidden', '');
				}
			}
		}

		filterBar.querySelectorAll('[data-otc-filter]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var slug = btn.getAttribute('data-otc-filter') || 'all';

				filterBar.querySelectorAll('[data-otc-filter]').forEach(function (b) {
					var active = b === btn;
					b.classList.toggle('is-active', active);
					b.setAttribute('aria-selected', active ? 'true' : 'false');
				});

				applyFilter(slug);
			});
		});
	}

	function initNewsletter() {
		var form = document.querySelector('[data-otc-newsletter-form]');
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

			var note = form.parentNode ? form.parentNode.querySelector('.otc-newsletter__note') : null;

			window.AlexRoseFormSubmit.send(form, {
				confirmation: document.getElementById('otc-newsletter-confirmation'),
				errorNode: document.querySelector('[data-otc-newsletter-error]'),
				submitBtn: form.querySelector('[data-otc-newsletter-submit]'),
				hideAlso: [note],
			});
		});
	}

	function init() {
		initFilters();
		initNewsletter();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
