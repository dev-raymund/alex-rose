/**
 * Scroll-triggered entrance animations for marketing pages.
 * Mirrors reference React (opacity + blur + translateY) via IntersectionObserver.
 */
(function () {
	'use strict';

	var main = document.getElementById('main');
	if (!main) {
		return;
	}

	document.documentElement.classList.add('ar-reveal-js');

	var reducedMotion =
		window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var EXCLUDE =
		'.home-occ-card__tags, .home-occ-card__desc, .home-occ-card__shade, ' +
		'.home-client-card__zoom, .home-client-card__tag, .home-client-card__overlay, ' +
		'.home-client-card__shade, .home-reviews__slide, [hidden], [data-ar-no-reveal]';

	var AUTO_SELECTOR = [
		'[class$="-hero__inner"] > *:not([aria-hidden="true"])',
		'section [class$="__intro"]',
		'section [class$="__head"]',
		'section [class$="__head-row"]',
		'.home-stats__cell',
		'.home-occ-card',
		'.home-split__media',
		'.home-split__text',
		'.home-how__cell',
		'.home-client-card',
		'.home-story__inner',
		'.home-story__media',
		'.home-testimonial',
		'.home-testimonials__bottom',
		'.home-journal__feature',
		'.home-journal__side-item',
		'.home-cta__inner',
		'.cloths-card',
		'.otc-card',
		'.otc-featured__card',
		'.otca-related__card',
		'.faq-item',
		'.di-section',
		'.pp-section',
		'.tc-section',
		'.os-stat',
		'.os-principle',
		'.os-harold__body',
		'.os-quote__inner',
		'.os-timeline__head',
		'.os-timeline__panel',
		'.cc-intro__media',
		'.cc-intro__body',
		'.hiw-panel__media',
		'.hiw-panel__body',
		'.hiw-measure-option',
		'.sm-option',
		'.sm-panel__list-item',
		'.sr-expect__card',
		'.occ-thinking__col',
		'.occ-when__grid > *',
		'.occ-samples__head',
		'.occ-sample',
		'.occ-cloths__head',
		'.occ-cloths__item',
		'.occ-related__head',
		'.occ-related-card',
		'.occ-cta__copy',
		'.occ-cta__feature',
		'.gv-pricing__row',
		'.ct-main__form-col',
		'.ct-main__details-col',
		'.crt-summary',
		'[class$="-cta"] [class*="__inner"]',
		'section > .ar-container > *',
		'section > .ar-container--6xl > *',
		'section > .ar-container--5xl > *',
		'section > .ar-container--3xl > *',
	].join(', ');

	function matchesExclude(el) {
		return el.matches(EXCLUDE) || !!el.closest(EXCLUDE);
	}

	function hasRevealAncestor(el) {
		var node = el.parentElement;
		while (node && node !== main) {
			if (node.hasAttribute('data-ar-reveal')) {
				return true;
			}
			node = node.parentElement;
		}
		return false;
	}

	function isHeroChild(el) {
		return !!el.closest('[class$="-hero__inner"]');
	}

	function collectTargets() {
		var seen = new Set();
		var list = [];

		main.querySelectorAll(AUTO_SELECTOR).forEach(function (el) {
			if (seen.has(el) || el.hasAttribute('data-ar-reveal')) {
				return;
			}
			if (matchesExclude(el) || hasRevealAncestor(el)) {
				return;
			}
			if (el.matches('[class$="__img"], [class$="__shade"], [class$="__rule"], .home-hero__spacer')) {
				return;
			}

			seen.add(el);
			list.push(el);
		});

		return list;
	}

	function staggerDelay(el, index) {
		var parent =
			el.closest(
				'[class*="__grid"], [class*="__row"], [class*="__strip"], section, .ar-container'
			) || main;
		var siblings = Array.prototype.filter.call(
			parent.querySelectorAll('[data-ar-reveal]'),
			function (node) {
				return node.closest('[class*="__grid"], [class*="__row"], [class*="__strip"], section, .ar-container') === parent;
			}
		);
		var idx = siblings.indexOf(el);
		if (idx < 0) {
			idx = index;
		}
		return Math.min(idx * 70, 420);
	}

	function markVisible(el) {
		el.classList.add('is-visible');
	}

	function revealAll(targets) {
		targets.forEach(markVisible);
	}

	function initObserver(targets) {
		if (!('IntersectionObserver' in window)) {
			revealAll(targets);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}
					markVisible(entry.target);
					observer.unobserve(entry.target);
				});
			},
			{
				root: null,
				rootMargin: '0px 0px -6% 0px',
				threshold: 0.08,
			}
		);

		targets.forEach(function (el, index) {
			if (isHeroChild(el)) {
				el.style.setProperty('--ar-reveal-delay', String(index * 90) + 'ms');
				requestAnimationFrame(function () {
					markVisible(el);
				});
				return;
			}

			el.style.setProperty('--ar-reveal-delay', staggerDelay(el, index) + 'ms');
			observer.observe(el);
		});
	}

	function initParallax() {
		if (reducedMotion) {
			return;
		}

		var images = main.querySelectorAll('.home-story__media > img');
		if (!images.length) {
			return;
		}

		var ticking = false;

		function update() {
			ticking = false;
			var vh = window.innerHeight || document.documentElement.clientHeight;

			images.forEach(function (img) {
				var section = img.closest('.home-story__media');
				if (!section) {
					return;
				}
				var rect = section.getBoundingClientRect();
				if (rect.bottom < 0 || rect.top > vh) {
					return;
				}
				var progress = (vh - rect.top) / (vh + rect.height);
				var offset = (progress - 0.5) * 36;
				img.style.transform = 'translate3d(0, ' + offset.toFixed(2) + 'px, 0)';
			});
		}

		function onScroll() {
			if (!ticking) {
				ticking = true;
				requestAnimationFrame(update);
			}
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll, { passive: true });
		update();
	}

	function init() {
		var targets = collectTargets();

		targets.forEach(function (el) {
			if (!el.hasAttribute('data-ar-reveal')) {
				el.setAttribute('data-ar-reveal', '');
			}
		});

		if (reducedMotion) {
			revealAll(targets);
			return;
		}

		initObserver(targets);
		initParallax();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
