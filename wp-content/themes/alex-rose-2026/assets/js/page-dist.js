/**
 * Shared interactivity for the dist-style page templates.
 * Cart is persisted in localStorage so the cart and checkout pages share state.
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'ar_cart_v1';

	function read() {
		try {
			var raw = window.localStorage.getItem(STORAGE_KEY);
			return raw ? JSON.parse(raw) : [];
		} catch (e) {
			return [];
		}
	}

	function write(items) {
		try {
			window.localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
		} catch (e) { /* ignore */ }
	}

	var Cart = {
		get: function () { return read(); },
		add: function (item) {
			var items = read();
			items.push(Object.assign({ qty: 1 }, item));
			write(items);
			Cart.refresh();
		},
		setQty: function (index, qty) {
			var items = read();
			if (!items[index]) return;
			items[index].qty = Math.max(1, qty | 0);
			write(items);
			Cart.refresh();
		},
		remove: function (index) {
			var items = read();
			items.splice(index, 1);
			write(items);
			Cart.refresh();
		},
		clear: function () { write([]); Cart.refresh(); },
		count: function () {
			return read().reduce(function (n, i) { return n + (i.qty || 1); }, 0);
		},
		total: function () {
			return read().reduce(function (sum, i) {
				return sum + (Number(i.price) || 0) * (i.qty || 1);
			}, 0);
		},
		refresh: function () {
			var c = Cart.count();
			document.querySelectorAll('[data-cart-count]').forEach(function (el) {
				el.textContent = String(c);
				el.setAttribute('data-count', String(c));
			});
			renderCartPage();
			renderCheckoutSummary();
		}
	};

	window.ArCart = Cart;

	function fmt(n) { return '£' + (Number(n) || 0).toFixed(2); }

	function renderCartPage() {
		var emptyEl = document.getElementById('cart-empty');
		var contentEl = document.getElementById('cart-content');
		var itemsEl = document.getElementById('cart-items');
		if (!emptyEl || !contentEl || !itemsEl) return;
		var items = Cart.get();
		if (items.length === 0) {
			emptyEl.hidden = false;
			contentEl.hidden = true;
			return;
		}
		emptyEl.hidden = true;
		contentEl.hidden = false;
		itemsEl.innerHTML = items.map(function (i, index) {
			var line = (Number(i.price) || 0) * (i.qty || 1);
			return '<tr>' +
				'<td>' +
				'<div class="cart-item-name">' + escapeHtml(i.name || 'Bespoke jacket') + '</div>' +
				'<div class="cart-item-detail">' + escapeHtml(i.detail || '') + '</div>' +
				'</td>' +
				'<td>' +
				'<div class="qty-control">' +
				'<button class="qty-btn" type="button" data-qty-dec="' + index + '" aria-label="Decrease">−</button>' +
				'<span>' + (i.qty || 1) + '</span>' +
				'<button class="qty-btn" type="button" data-qty-inc="' + index + '" aria-label="Increase">+</button>' +
				'</div>' +
				'</td>' +
				'<td>' + fmt(line) + '</td>' +
				'<td><button class="qty-btn" type="button" data-qty-remove="' + index + '" aria-label="Remove">×</button></td>' +
				'</tr>';
		}).join('');

		var total = Cart.total();
		var sub = document.getElementById('cart-subtotal');
		var tot = document.getElementById('cart-total');
		if (sub) sub.textContent = fmt(total);
		if (tot) tot.textContent = fmt(total);
	}

	function renderCheckoutSummary() {
		var listEl = document.getElementById('checkout-items');
		var subEl = document.getElementById('co-subtotal');
		var totEl = document.getElementById('co-total');
		if (!listEl && !subEl && !totEl) return;
		var items = Cart.get();
		var total = Cart.total();
		if (listEl) {
			if (items.length === 0) {
				listEl.innerHTML = '<p style="color:var(--muted)">Your cart is empty.</p>';
			} else {
				listEl.innerHTML = items.map(function (i) {
					var line = (Number(i.price) || 0) * (i.qty || 1);
					return '<div style="display:flex;justify-content:space-between;margin-bottom:8px">' +
						'<span>' + escapeHtml(i.name || 'Bespoke jacket') + ' ×' + (i.qty || 1) + '</span>' +
						'<span style="color:var(--ink)">' + fmt(line) + '</span>' +
						'</div>' +
						'<p style="font-size:0.75rem;color:var(--muted);margin-bottom:12px">' + escapeHtml(i.detail || '') + '</p>';
				}).join('');
			}
		}
		if (subEl) subEl.textContent = fmt(total);
		if (totEl) totEl.textContent = fmt(total);
	}

	function escapeHtml(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function bindCartPage() {
		document.addEventListener('click', function (e) {
			var t = e.target;
			if (!t || !t.matches) return;
			if (t.matches('[data-qty-inc]')) {
				var i = +t.getAttribute('data-qty-inc');
				var items = Cart.get();
				Cart.setQty(i, (items[i].qty || 1) + 1);
			} else if (t.matches('[data-qty-dec]')) {
				var j = +t.getAttribute('data-qty-dec');
				var ii = Cart.get();
				Cart.setQty(j, (ii[j].qty || 1) - 1);
			} else if (t.matches('[data-qty-remove]')) {
				Cart.remove(+t.getAttribute('data-qty-remove'));
			} else if (t.matches('[data-cart-clear]')) {
				Cart.clear();
			}
		});
	}

	function bindAccordion(scope) {
		(scope || document).querySelectorAll('.accordion-trigger').forEach(function (trig) {
			trig.addEventListener('click', function () {
				var item = trig.closest('.accordion-item');
				if (!item) return;
				var body = item.querySelector('.accordion-body');
				var isOpen = item.classList.toggle('open');
				if (body) {
					body.style.maxHeight = isOpen ? body.scrollHeight + 'px' : '0px';
				}
			});
		});
	}

	function bindMultistepForms() {
		document.querySelectorAll('form.multistep-form, [data-multistep]').forEach(function (form) {
			var panels = form.querySelectorAll('.step-panel');
			var dots = form.parentElement
				? form.parentElement.querySelectorAll('.step-dot')
				: form.querySelectorAll('.step-dot');

			function show(index) {
				panels.forEach(function (p, idx) { p.classList.toggle('active', idx === index); });
				dots.forEach(function (d, idx) {
					d.classList.toggle('active', idx === index);
					d.classList.toggle('done', idx < index);
				});
			}

			form.querySelectorAll('[data-next]').forEach(function (btn) {
				btn.addEventListener('click', function () { show(+btn.getAttribute('data-next')); });
			});
			form.querySelectorAll('[data-back]').forEach(function (btn) {
				btn.addEventListener('click', function () { show(+btn.getAttribute('data-back')); });
			});
			form.querySelectorAll('[data-submit]').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var confirmation = form.parentElement
						? form.parentElement.querySelector('#form-confirmation')
						: document.getElementById('form-confirmation');
					form.style.display = 'none';
					var prog = form.parentElement
						? form.parentElement.querySelector('.step-progress')
						: null;
					if (prog) prog.style.display = 'none';
					if (confirmation) confirmation.style.display = 'block';
					var refEl = document.getElementById('order-ref');
					if (refEl) refEl.textContent = String(Math.floor(10000 + Math.random() * 90000));
					Cart.clear();
				});
			});
		});
	}

	function bindSimpleForms() {
		document.querySelectorAll('form.simple-form').forEach(function (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var conf = form.parentElement
					? form.parentElement.querySelector('#form-confirmation')
					: document.getElementById('form-confirmation');
				form.style.display = 'none';
				if (conf) conf.style.display = 'block';
			});
		});
	}

	function bindPaymentTabs() {
		document.querySelectorAll('.payment-tab').forEach(function (tab) {
			tab.addEventListener('click', function () {
				var tabs = tab.parentElement.querySelectorAll('.payment-tab');
				tabs.forEach(function (t) { t.classList.remove('active'); });
				tab.classList.add('active');
				var name = tab.getAttribute('data-tab');
				document.querySelectorAll('.payment-panel').forEach(function (p) {
					p.classList.toggle('active', p.id === 'payment-' + name);
				});
			});
		});
	}

	function bindFilters() {
		document.querySelectorAll('.filter-bar').forEach(function (bar) {
			var grid = bar.parentElement.querySelector('.grid-4, .grid-3, .grid-2');
			if (!grid) return;
			bar.querySelectorAll('.filter-btn').forEach(function (btn) {
				btn.addEventListener('click', function () {
					bar.querySelectorAll('.filter-btn').forEach(function (b) { b.classList.remove('active'); });
					btn.classList.add('active');
					var f = btn.getAttribute('data-filter');
					grid.querySelectorAll('[data-category]').forEach(function (card) {
						var cat = card.getAttribute('data-category');
						card.style.display = (f === 'all' || cat === f) ? '' : 'none';
					});
				});
			});
		});
	}

	function init() {
		bindCartPage();
		bindAccordion();
		bindMultistepForms();
		bindSimpleForms();
		bindPaymentTabs();
		bindFilters();
		renderCartPage();
		renderCheckoutSummary();
		Cart.refresh();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
