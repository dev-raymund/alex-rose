/**
 * "Our Story" page — interactive timeline.
 *
 * Wires the year track and the slide panel together:
 *  - Clicking a year dot activates the matching slide.
 *  - The Prev / Next buttons in the active slide step through the milestones.
 *  - The dot's "is-passed" state highlights the gold rail up to (but not
 *    including) the current year.
 */
(function () {
	'use strict';

	function init() {
		var section = document.querySelector('.os-timeline');
		if (!section) {
			return;
		}

		var dots = Array.prototype.slice.call(section.querySelectorAll('[data-os-year-index]'));
		var slides = Array.prototype.slice.call(section.querySelectorAll('[data-os-year-slide]'));
		var total = dots.length;
		if (total === 0 || slides.length === 0) {
			return;
		}

		function setActive(index) {
			if (index < 0 || index >= total) {
				return;
			}

			dots.forEach(function (dot, i) {
				var isActive = i === index;
				var isPassed = i < index;

				dot.classList.toggle('is-active', isActive);
				dot.classList.toggle('is-passed', isPassed);
				dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
			});

			slides.forEach(function (slide, i) {
				var isActive = i === index;
				slide.classList.toggle('is-active', isActive);
				if (isActive) {
					slide.removeAttribute('hidden');
				} else {
					slide.setAttribute('hidden', '');
				}
			});

			var activeSlide = slides[index];
			if (activeSlide) {
				var prev = activeSlide.querySelector('[data-os-year-prev]');
				var next = activeSlide.querySelector('[data-os-year-next]');
				var counter = activeSlide.querySelector('[data-os-year-current]');
				if (prev) { prev.disabled = index === 0; }
				if (next) { next.disabled = index === total - 1; }
				if (counter) { counter.textContent = String(index + 1); }
			}

			var activeDot = dots[index];
			if (activeDot && typeof activeDot.scrollIntoView === 'function') {
				try {
					activeDot.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
				} catch (e) {
					activeDot.scrollIntoView(false);
				}
			}
		}

		function currentIndex() {
			for (var i = 0; i < dots.length; i += 1) {
				if (dots[i].classList.contains('is-active')) {
					return i;
				}
			}
			return 0;
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				var raw = dot.getAttribute('data-os-year-index');
				var idx = parseInt(raw, 10);
				if (!isNaN(idx)) {
					setActive(idx);
				}
			});
		});

		section.addEventListener('click', function (event) {
			var target = event.target;
			if (!target || !target.closest) {
				return;
			}
			if (target.closest('[data-os-year-prev]')) {
				setActive(currentIndex() - 1);
			} else if (target.closest('[data-os-year-next]')) {
				setActive(currentIndex() + 1);
			}
		});

		section.addEventListener('keydown', function (event) {
			if (!event.target || !event.target.closest) {
				return;
			}
			if (!event.target.closest('[data-os-year-index]')) {
				return;
			}
			var idx = currentIndex();
			if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
				event.preventDefault();
				setActive(idx - 1);
			} else if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
				event.preventDefault();
				setActive(idx + 1);
			} else if (event.key === 'Home') {
				event.preventDefault();
				setActive(0);
			} else if (event.key === 'End') {
				event.preventDefault();
				setActive(total - 1);
			}
		});

		setActive(currentIndex());
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
