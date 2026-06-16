(function () {
	'use strict';

	var toggle = document.querySelector('[data-side-menu-toggle]');
	var menu = document.getElementById('site-side-menu');
	var body = document.body;

	if (!toggle || !menu) {
		return;
	}

	function setOpen(open) {
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		menu.setAttribute('aria-hidden', open ? 'false' : 'true');
		menu.classList.toggle('is-open', open);
		body.classList.toggle('site-menu-open', open);
	}

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		setOpen(!isOpen);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
			setOpen(false);
			toggle.focus();
		}
	});

	// Close the menu when a real link is clicked, but not when an accordion button is.
	menu.addEventListener('click', function (e) {
		var target = e.target;
		if (!target) {
			return;
		}
		if (target.closest && target.closest('[data-sidebar-accordion]')) {
			var btn = target.closest('[data-sidebar-accordion]');
			var expanded = btn.getAttribute('aria-expanded') === 'true';
			btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
			var panel = btn.nextElementSibling;
			if (panel && panel.classList.contains('site-side-menu__panel')) {
				if (expanded) {
					panel.style.height = panel.scrollHeight + 'px';
					// Force reflow before collapsing so the transition runs.
					void panel.offsetHeight;
					panel.style.height = '0px';
					panel.addEventListener('transitionend', function handler() {
						panel.hidden = true;
						panel.style.height = '';
						panel.removeEventListener('transitionend', handler);
					});
				} else {
					panel.hidden = false;
					panel.style.height = '0px';
					void panel.offsetHeight;
					panel.style.height = panel.scrollHeight + 'px';
					panel.addEventListener('transitionend', function handler() {
						panel.style.height = 'auto';
						panel.removeEventListener('transitionend', handler);
					});
				}
			}
			return;
		}
		if (target.closest && target.closest('a')) {
			setOpen(false);
		}
	});
})();
