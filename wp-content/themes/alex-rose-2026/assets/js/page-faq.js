/**
 * FAQ page — accordion with slide-down panels.
 */
(function () {
	'use strict';

	function setOpen(trigger, isOpen) {
		if (!trigger) {
			return;
		}
		var panelId = trigger.getAttribute('aria-controls');
		var panel = panelId ? document.getElementById(panelId) : null;

		trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		if (panel) {
			panel.classList.toggle('is-open', isOpen);
			panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
		}
	}

	function toggle(trigger) {
		if (!trigger) {
			return;
		}
		var open = trigger.getAttribute('aria-expanded') === 'true';
		setOpen(trigger, !open);
	}

	function init() {
		var page = document.querySelector('.page-faq');
		if (!page) {
			return;
		}

		var triggers = Array.prototype.slice.call(page.querySelectorAll('[data-faq-trigger]'));
		if (triggers.length === 0) {
			return;
		}

		triggers.forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				toggle(trigger);
			});
		});

		if (window.location && window.location.hash) {
			var hash = window.location.hash.slice(1);
			if (hash) {
				var direct = document.getElementById(hash);
				if (direct && direct.matches('[data-faq-trigger]')) {
					setOpen(direct, true);
					try {
						direct.scrollIntoView({ behavior: 'smooth', block: 'start' });
					} catch (e) {
						direct.scrollIntoView();
					}
				}
			}
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
