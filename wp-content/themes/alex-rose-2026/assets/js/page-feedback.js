/**
 * Feedback survey — multi-step engine.
 *
 * Generic: it drives however many .fb-panel fieldsets exist. Each panel may
 * contain normal inputs and/or .fb-choices button groups (each writes to a
 * hidden input). A step advances only when its [required] fields are valid.
 * Final submit goes through the shared AlexRoseFormSubmit helper.
 */
(function () {
	'use strict';

	var form = document.querySelector('.fb-form');
	if (!form) {
		return;
	}

	var panels = Array.prototype.slice.call(form.querySelectorAll('.fb-panel'));
	var dots = Array.prototype.slice.call(document.querySelectorAll('[data-fb-dot]'));
	var bar = document.querySelector('[data-fb-bar]');
	var caption = document.querySelector('[data-fb-caption]');
	var backBtn = form.querySelector('[data-fb-back]');
	var nextBtn = form.querySelector('[data-fb-next]');
	var submitBtn = form.querySelector('[data-fb-submit]');
	var errorNode = form.querySelector('.fb-error');
	var confirmation = document.querySelector('.fb-confirm');
	var total = panels.length;
	var current = 0;

	if (!total) {
		return;
	}

	function labelOf(i) {
		return panels[i] ? (panels[i].getAttribute('data-fb-label') || '') : '';
	}

	function panelValid(panel) {
		var reqs = panel.querySelectorAll('[required]');
		for (var i = 0; i < reqs.length; i++) {
			if (typeof reqs[i].checkValidity === 'function') {
				if (!reqs[i].checkValidity()) {
					return false;
				}
			} else if (!String(reqs[i].value || '').trim()) {
				return false;
			}
		}
		// Choice groups flagged required must have a value.
		var groups = panel.querySelectorAll('.fb-choices[data-fb-required] input[type="hidden"]');
		for (var j = 0; j < groups.length; j++) {
			if (!String(groups[j].value || '').trim()) {
				return false;
			}
		}
		return true;
	}

	function updateValidity() {
		var ok = panelValid(panels[current]);
		if (nextBtn) {
			nextBtn.disabled = !ok;
		}
		if (submitBtn) {
			submitBtn.disabled = !ok;
		}
	}

	function render() {
		for (var i = 0; i < panels.length; i++) {
			var on = i === current;
			panels[i].hidden = !on;
			panels[i].classList.toggle('is-active', on);
		}
		for (var d = 0; d < dots.length; d++) {
			dots[d].classList.toggle('is-active', d === current);
			dots[d].classList.toggle('is-done', d < current);
		}
		if (bar) {
			bar.style.width = (total > 1 ? (current / (total - 1)) * 100 : 100) + '%';
		}
		if (caption) {
			caption.textContent = 'Step ' + (current + 1) + ' of ' + total + ': ' + labelOf(current);
		}
		var last = current === total - 1;
		if (backBtn) {
			backBtn.hidden = current === 0;
		}
		if (nextBtn) {
			nextBtn.hidden = last;
		}
		if (submitBtn) {
			submitBtn.hidden = !last;
		}
		updateValidity();
	}

	function scrollToTop() {
		var anchor = document.querySelector('.fb-steps');
		if (anchor && typeof anchor.scrollIntoView === 'function') {
			try {
				anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
			} catch (e) {
				anchor.scrollIntoView();
			}
		}
	}

	function go(delta) {
		var target = current + delta;
		if (target < 0 || target >= total) {
			return;
		}
		if (delta > 0 && !panelValid(panels[current])) {
			return;
		}
		current = target;
		render();
		scrollToTop();
	}

	function closestChoice(el) {
		while (el && el.nodeType === 1) {
			if (el.classList && el.classList.contains('fb-choice')) {
				return el;
			}
			el = el.parentNode;
		}
		return null;
	}

	form.addEventListener('click', function (e) {
		var btn = closestChoice(e.target);
		if (!btn) {
			return;
		}
		e.preventDefault();
		var group = btn.parentNode;
		var hidden = group.querySelector('input[type="hidden"]');
		var btns = group.querySelectorAll('.fb-choice');
		for (var i = 0; i < btns.length; i++) {
			btns[i].classList.remove('is-selected');
		}
		btn.classList.add('is-selected');
		if (hidden) {
			hidden.value = btn.getAttribute('data-value') || '';
		}
		updateValidity();
	});

	form.addEventListener('input', updateValidity);
	form.addEventListener('change', updateValidity);

	if (nextBtn) {
		nextBtn.addEventListener('click', function (e) {
			e.preventDefault();
			go(1);
		});
	}
	if (backBtn) {
		backBtn.addEventListener('click', function (e) {
			e.preventDefault();
			go(-1);
		});
	}

	form.addEventListener('submit', function (e) {
		e.preventDefault();
		if (!window.AlexRoseFormSubmit || !panelValid(panels[current])) {
			return;
		}
		window.AlexRoseFormSubmit.send(form, {
			confirmation: confirmation,
			errorNode: errorNode,
			submitBtn: submitBtn,
			hideAlso: [document.querySelector('.fb-steps'), document.querySelector('.fb-offer')]
		});
	});

	render();
})();
