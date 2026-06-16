<?php
/**
 * "Cart" — main body: empty state + filled table + summary.
 *
 * Cart data lives in localStorage and is rendered by assets/js/page-cart.js.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="crt-main">
	<div class="crt-main__inner ar-container ar-container--5xl">

		<div class="crt-empty" data-crt-empty hidden>
			<div class="crt-empty__icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
					<path d="M16 10a4 4 0 0 1-8 0"></path>
					<path d="M3.103 6.034h17.794"></path>
					<path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path>
				</svg>
			</div>
			<div class="crt-empty__text">
				<p class="crt-empty__lead"><?php esc_html_e('No jackets reserved yet.', 'alex-rose-2026'); ?></p>
				<p class="crt-empty__sub"><?php esc_html_e('Design your jacket and reserve it here while you decide.', 'alex-rose-2026'); ?></p>
			</div>
			<a class="crt-empty__cta" href="<?php echo esc_url(home_url('/design/')); ?>">
				<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="m9 18 6-6-6-6"></path>
				</svg>
			</a>
		</div>

		<div class="crt-filled" data-crt-filled hidden>
			<div class="crt-filled__grid">
				<div class="crt-filled__items">
					<table class="crt-table">
						<thead>
							<tr>
								<th><?php esc_html_e('Reserved jacket', 'alex-rose-2026'); ?></th>
								<th><?php esc_html_e('Qty', 'alex-rose-2026'); ?></th>
								<th><?php esc_html_e('Price', 'alex-rose-2026'); ?></th>
								<th><span class="crt-sr"><?php esc_html_e('Remove', 'alex-rose-2026'); ?></span></th>
							</tr>
						</thead>
						<tbody data-crt-items></tbody>
					</table>

					<div class="crt-filled__actions">
						<a class="crt-link" href="<?php echo esc_url(home_url('/design/')); ?>">
							<?php esc_html_e('+ Reserve another jacket', 'alex-rose-2026'); ?>
						</a>
						<button class="crt-link crt-link--muted" type="button" data-crt-clear>
							<?php esc_html_e('Clear reservations', 'alex-rose-2026'); ?>
						</button>
					</div>
				</div>

				<aside class="crt-summary">
					<h2 class="crt-summary__title"><?php esc_html_e('Reservation summary', 'alex-rose-2026'); ?></h2>
					<div class="crt-summary__row">
						<span><?php esc_html_e('Subtotal', 'alex-rose-2026'); ?></span>
						<span data-crt-subtotal>&pound;0.00</span>
					</div>
					<div class="crt-summary__row">
						<span><?php esc_html_e('Delivery', 'alex-rose-2026'); ?></span>
						<span class="crt-summary__free"><?php esc_html_e('Free', 'alex-rose-2026'); ?></span>
					</div>
					<div class="crt-summary__row crt-summary__row--total">
						<span><?php esc_html_e('Total', 'alex-rose-2026'); ?></span>
						<span data-crt-total>&pound;0.00</span>
					</div>

					<a class="crt-summary__cta" href="<?php echo esc_url(home_url('/checkout/')); ?>">
						<?php esc_html_e('Proceed to checkout', 'alex-rose-2026'); ?>
					</a>
					<p class="crt-summary__note">
						<?php esc_html_e('No payment has been taken until you place the order.', 'alex-rose-2026'); ?>
					</p>
				</aside>
			</div>
		</div>

	</div>
</section>
