/**
 * Cart page — localStorage-backed cart with empty/filled rendering.
 *
 * Storage shape: array of { name, detail, price, qty } in `ar_cart_v1`.
 * The same key is shared with the checkout page so both stay in sync.
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'ar_cart_v1';

	function read() {
		try {
			var raw = window.localStorage.getItem(STORAGE_KEY);
			var parsed = raw ? JSON.parse(raw) : [];
			return Array.isArray(parsed) ? parsed : [];
		} catch (e) {
			return [];
		}
	}

	function write(items) {
		try {
			window.localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
		} catch (e) {
			/* ignore quota / privacy errors */
		}
	}

	function formatPrice(value) {
		var n = Number(value) || 0;
		return '\u00A3' + n.toFixed(2);
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
			return {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#39;'
			}[ch];
		});
	}

	function totalFor(items) {
		return items.reduce(function (sum, item) {
			var price = Number(item.price) || 0;
			var qty = Math.max(1, item.qty | 0);
			return sum + price * qty;
		}, 0);
	}

	function init() {
		var root = document.querySelector('.page-cart');
		if (!root) {
			return;
		}

		var emptyEl = root.querySelector('[data-crt-empty]');
		var filledEl = root.querySelector('[data-crt-filled]');
		var itemsEl = root.querySelector('[data-crt-items]');
		var subtotalEl = root.querySelector('[data-crt-subtotal]');
		var totalEl = root.querySelector('[data-crt-total]');
		var clearBtn = root.querySelector('[data-crt-clear]');

		if (!emptyEl || !filledEl || !itemsEl) {
			return;
		}

		function render() {
			var items = read();

			if (items.length === 0) {
				emptyEl.removeAttribute('hidden');
				filledEl.setAttribute('hidden', '');
				if (subtotalEl) { subtotalEl.textContent = formatPrice(0); }
				if (totalEl) { totalEl.textContent = formatPrice(0); }
				return;
			}

			emptyEl.setAttribute('hidden', '');
			filledEl.removeAttribute('hidden');

			itemsEl.innerHTML = items.map(function (item, index) {
				var qty = Math.max(1, item.qty | 0);
				var line = (Number(item.price) || 0) * qty;
				return (
					'<tr>' +
						'<td>' +
							'<p class="crt-item__name">' + escapeHtml(item.name || 'Bespoke jacket') + '</p>' +
							(item.detail ? '<p class="crt-item__detail">' + escapeHtml(item.detail) + '</p>' : '') +
						'</td>' +
						'<td>' +
							'<div class="crt-qty">' +
								'<button type="button" class="crt-qty__btn" data-crt-dec="' + index + '" aria-label="Decrease quantity">&minus;</button>' +
								'<span class="crt-qty__value">' + qty + '</span>' +
								'<button type="button" class="crt-qty__btn" data-crt-inc="' + index + '" aria-label="Increase quantity">+</button>' +
							'</div>' +
						'</td>' +
						'<td>' + formatPrice(line) + '</td>' +
						'<td>' +
							'<button type="button" class="crt-remove" data-crt-remove="' + index + '" aria-label="Remove">&times;</button>' +
						'</td>' +
					'</tr>'
				);
			}).join('');

			var total = totalFor(items);
			if (subtotalEl) { subtotalEl.textContent = formatPrice(total); }
			if (totalEl) { totalEl.textContent = formatPrice(total); }
		}

		function setQty(index, qty) {
			var items = read();
			if (!items[index]) { return; }
			items[index].qty = Math.max(1, qty | 0);
			write(items);
			render();
		}

		root.addEventListener('click', function (event) {
			var target = event.target;
			if (!target || !target.getAttribute) { return; }

			var inc = target.getAttribute('data-crt-inc');
			var dec = target.getAttribute('data-crt-dec');
			var remove = target.getAttribute('data-crt-remove');

			if (inc !== null) {
				var items = read();
				var i = parseInt(inc, 10);
				if (items[i]) { setQty(i, (items[i].qty | 0) + 1); }
			} else if (dec !== null) {
				var items2 = read();
				var j = parseInt(dec, 10);
				if (items2[j]) { setQty(j, (items2[j].qty | 0) - 1); }
			} else if (remove !== null) {
				var items3 = read();
				var k = parseInt(remove, 10);
				items3.splice(k, 1);
				write(items3);
				render();
			}
		});

		if (clearBtn) {
			clearBtn.addEventListener('click', function () {
				write([]);
				render();
			});
		}

		render();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
