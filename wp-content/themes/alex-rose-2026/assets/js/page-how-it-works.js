/**
 * "How It Works" page — step tabs and panel navigation.
 */
(function () {
	'use strict';

	function init() {
		var tabs = document.querySelectorAll('[data-hiw-tab]');
		var panels = document.querySelectorAll('[data-hiw-panel]');
		if (!tabs.length || !panels.length) {
			return;
		}

		var total = panels.length;

		function activate(index) {
			if (index < 0 || index >= total) {
				return;
			}

			panels.forEach(function (panel) {
				var pi = parseInt(panel.getAttribute('data-hiw-panel'), 10);
				var active = pi === index;
				panel.classList.toggle('is-active', active);
				if (active) {
					panel.removeAttribute('hidden');
				} else {
					panel.setAttribute('hidden', '');
				}
			});

			document.querySelectorAll('.hiw-tab').forEach(function (tab) {
				var ti = parseInt(tab.getAttribute('data-hiw-tab'), 10);
				var active = ti === index;
				tab.classList.toggle('is-active', active);
				tab.setAttribute('aria-selected', active ? 'true' : 'false');
				tab.setAttribute('tabindex', active ? '0' : '-1');
			});

			document.querySelectorAll('.hiw-panel__dot').forEach(function (dot) {
				var di = parseInt(dot.getAttribute('data-hiw-tab'), 10);
				dot.classList.toggle('is-active', di === index);
			});

			document.querySelectorAll('[data-hiw-prev]').forEach(function (btn) {
				btn.hidden = index === 0;
			});
			document.querySelectorAll('[data-hiw-next]').forEach(function (btn) {
				btn.hidden = index === total - 1;
			});

			var activeTab = document.querySelector('.hiw-tab.is-active');
			if (activeTab && typeof activeTab.scrollIntoView === 'function') {
				try {
					activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
				} catch (e) {
					activeTab.scrollIntoView(false);
				}
			}
		}

		function currentIndex() {
			for (var i = 0; i < panels.length; i += 1) {
				if (panels[i].classList.contains('is-active')) {
					return i;
				}
			}
			return 0;
		}

		tabs.forEach(function (el) {
			el.addEventListener('click', function () {
				activate(parseInt(el.getAttribute('data-hiw-tab'), 10) || 0);
			});
		});

		document.querySelectorAll('[data-hiw-prev]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				activate(currentIndex() - 1);
			});
		});

		document.querySelectorAll('[data-hiw-next]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				activate(currentIndex() + 1);
			});
		});

		// Deep-linking: /how-it-works/#step-3 opens the 3rd step (1-based) so
		// footer / cross-page links can land the visitor on a specific step.
		function indexFromHash() {
			var m = /#step-(\d+)/.exec(window.location.hash || '');
			if (!m) {
				return -1;
			}
			var i = parseInt(m[1], 10) - 1;
			return i >= 0 && i < total ? i : -1;
		}

		function activateFromHash(scrollSection) {
			var i = indexFromHash();
			if (i < 0) {
				return false;
			}
			activate(i);
			if (scrollSection) {
				var section = document.querySelector('.hiw-steps');
				if (section && typeof section.scrollIntoView === 'function') {
					try {
						section.scrollIntoView({ behavior: 'smooth', block: 'start' });
					} catch (e) {
						section.scrollIntoView();
					}
				}
			}
			return true;
		}

		window.addEventListener('hashchange', function () {
			activateFromHash(true);
		});

		var tablist = document.querySelector('.hiw-tabs__grid');
		if (tablist) {
			tablist.addEventListener('keydown', function (e) {
				var current = document.activeElement;
				if (!current || !current.classList.contains('hiw-tab')) {
					return;
				}
				var idx = parseInt(current.getAttribute('data-hiw-tab'), 10) || 0;
				var next = idx;
				if (e.key === 'ArrowRight') {
					next = (idx + 1) % total;
				} else if (e.key === 'ArrowLeft') {
					next = (idx - 1 + total) % total;
				} else if (e.key === 'Home') {
					next = 0;
				} else if (e.key === 'End') {
					next = total - 1;
				} else {
					return;
				}
				e.preventDefault();
				activate(next);
				var target = document.querySelector('.hiw-tab[data-hiw-tab="' + next + '"]');
				if (target) {
					target.focus();
				}
			});
		}

		if (!activateFromHash(true)) {
			activate(currentIndex());
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
