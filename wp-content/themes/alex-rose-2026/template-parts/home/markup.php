<?php
/**
 * Shared homepage markup (marketing sections + client strip script).
 * Used by template/home.php and front-page.php when the Home template is selected.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-home" tabindex="-1">
	<?php
	get_template_part('template-parts/home/content', '1');
	get_template_part('template-parts/home/content', '2');
	get_template_part('template-parts/home/content', '3');
	?>
</main>
<script>
(function () {
	var strip = document.querySelector('.home-clients__strip');
	var stripWrap = document.querySelector('.home-clients__strip-wrap');
	if (strip) {
		document.querySelectorAll('.home-clients__arrow').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var dir = btn.getAttribute('data-dir') === 'next' ? 1 : -1;
				strip.scrollBy({ left: dir * Math.max(240, strip.clientWidth * 0.35), behavior: 'smooth' });
			});
		});

		if (stripWrap && !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
			var scrollDir = 0;
			var scrollRaf = null;
			var scrollSpeed = 5;

			function scrollStep() {
				if (scrollDir !== 0) {
					strip.scrollLeft += scrollDir * scrollSpeed;
					scrollRaf = window.requestAnimationFrame(scrollStep);
				} else {
					scrollRaf = null;
				}
			}

			function setScrollDir(dir) {
				scrollDir = dir;
				if (scrollDir !== 0 && !scrollRaf) {
					scrollRaf = window.requestAnimationFrame(scrollStep);
				}
			}

			stripWrap.querySelectorAll('[data-scroll-edge]').forEach(function (edge) {
				edge.addEventListener('mouseenter', function () {
					setScrollDir(edge.getAttribute('data-scroll-edge') === 'next' ? 1 : -1);
				});
				edge.addEventListener('mouseleave', function () {
					setScrollDir(0);
				});
			});
		}
	}

	// Client jacket spotlight — zoom button opens full-screen gallery.
	var spotlight = document.getElementById('home-client-spotlight');
	var cards = Array.prototype.slice.call(document.querySelectorAll('.home-client-card[data-client-index]'));
	if (spotlight && cards.length) {
		var imgEl = spotlight.querySelector('.home-client-spotlight__img');
		var captionEl = spotlight.querySelector('.home-client-spotlight__caption');
		var prevBtn = spotlight.querySelector('[data-client-spotlight-prev]');
		var nextBtn = spotlight.querySelector('[data-client-spotlight-next]');
		var current = 0;
		var lastFocus = null;

		function cardData(card) {
			return {
				src: card.getAttribute('data-client-src') || '',
				pos: card.getAttribute('data-client-pos') || '50% 50%',
				tag: card.getAttribute('data-client-tag') || '',
				index: parseInt(card.getAttribute('data-client-index'), 10) || 0
			};
		}

		function render(index) {
			if (index < 0) index = cards.length - 1;
			if (index >= cards.length) index = 0;
			current = index;

			var data = cardData(cards[current]);
			if (imgEl) {
				imgEl.src = data.src;
				imgEl.style.objectPosition = data.pos;
			}
			if (captionEl) {
				captionEl.textContent = data.tag.toUpperCase() + ' ' + (current + 1) + '/' + cards.length;
			}
		}

		function open(index) {
			lastFocus = document.activeElement;
			render(index);
			spotlight.removeAttribute('hidden');
			spotlight.classList.add('is-open');
			document.body.style.overflow = 'hidden';
			var closeBtn = spotlight.querySelector('.home-client-spotlight__close');
			if (closeBtn && typeof closeBtn.focus === 'function') {
				closeBtn.focus();
			}
		}

		function close() {
			spotlight.setAttribute('hidden', '');
			spotlight.classList.remove('is-open');
			document.body.style.overflow = '';
			if (lastFocus && typeof lastFocus.focus === 'function') {
				lastFocus.focus();
			}
		}

		cards.forEach(function (card) {
			var zoom = card.querySelector('[data-client-zoom]');
			if (!zoom) return;
			zoom.addEventListener('click', function (event) {
				event.stopPropagation();
				var idx = parseInt(card.getAttribute('data-client-index'), 10);
				open(isNaN(idx) ? 0 : idx);
			});
		});

		spotlight.querySelectorAll('[data-client-spotlight-close]').forEach(function (el) {
			el.addEventListener('click', close);
		});

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				render(current - 1);
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				render(current + 1);
			});
		}

		document.addEventListener('keydown', function (event) {
			if (spotlight.hasAttribute('hidden')) return;
			if (event.key === 'Escape') {
				event.preventDefault();
				close();
			} else if (event.key === 'ArrowLeft') {
				event.preventDefault();
				render(current - 1);
			} else if (event.key === 'ArrowRight') {
				event.preventDefault();
				render(current + 1);
			}
		});
	}

	// Review row carousel — rotates every 6s, pauses on hover, dots jump.
	var review = document.querySelector('[data-home-reviews]');
	if (!review) return;
	var slides = Array.prototype.slice.call(review.querySelectorAll('[data-home-reviews-slide]'));
	var dots   = Array.prototype.slice.call(review.querySelectorAll('[data-home-reviews-dot]'));
	if (slides.length === 0) return;

	var current = 0;
	var paused  = false;
	var interval = null;
	var DELAY = 6000;

	function show(index) {
		if (index < 0) index = slides.length - 1;
		if (index >= slides.length) index = 0;
		current = index;

		slides.forEach(function (slide, i) {
			var active = i === index;
			slide.classList.toggle('is-active', active);
			if (active) {
				slide.removeAttribute('hidden');
			} else {
				slide.setAttribute('hidden', '');
			}
		});
		dots.forEach(function (dot, i) {
			var active = i === index;
			dot.classList.toggle('is-active', active);
			dot.setAttribute('aria-selected', active ? 'true' : 'false');
		});
	}

	function start() {
		stop();
		interval = window.setInterval(function () {
			if (!paused) show(current + 1);
		}, DELAY);
	}
	function stop() {
		if (interval) {
			window.clearInterval(interval);
			interval = null;
		}
	}

	dots.forEach(function (dot) {
		dot.addEventListener('click', function () {
			var idx = parseInt(dot.getAttribute('data-home-reviews-dot'), 10);
			if (!isNaN(idx)) {
				show(idx);
				start();
			}
		});
	});

	review.addEventListener('mouseenter', function () { paused = true; });
	review.addEventListener('mouseleave', function () { paused = false; });
	review.addEventListener('focusin',   function () { paused = true; });
	review.addEventListener('focusout',  function () { paused = false; });

	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (!reduceMotion) {
		start();
	}
})();
</script>
