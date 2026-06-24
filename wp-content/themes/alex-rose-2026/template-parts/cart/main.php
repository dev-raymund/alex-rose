<?php
/**
 * "Cart" — Reserved Jackets body, rendered from the live WooCommerce cart.
 *
 * Each cart line is a configured bespoke jacket; its spec + fabric image ride
 * along as cart-item data (see inc/woocommerce.php). Quantity is always 1.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$cart  = function_exists('WC') ? WC()->cart : null;
$items = $cart ? $cart->get_cart() : array();

$trash_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path><path d="M3 6h18"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
?>
<section class="crt-main">
	<div class="crt-main__inner ar-container ar-container--5xl">

		<?php if (function_exists('wc_print_notices')) { wc_print_notices(); } ?>

		<?php if (empty($items)) : ?>

			<div class="crt-empty">
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
				<a class="crt-empty__cta" href="<?php echo esc_url(home_url('/design-your-jacket/')); ?>">
					<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
				</a>
			</div>

		<?php else : ?>

			<div class="crt-grid">
				<div class="crt-grid__items">
					<?php
					foreach ($items as $key => $item) :
						$jacket     = isset($item['ar_jacket']) && is_array($item['ar_jacket']) ? $item['ar_jacket'] : array();
						$spec       = isset($jacket['spec']) && is_array($jacket['spec']) ? $jacket['spec'] : array();
						$image      = ! empty($jacket['image']) ? $jacket['image'] : '';
						$fabric     = isset($spec['Fabric']) ? $spec['Fabric'] : '';
						$collection = isset($spec['Collection']) ? $spec['Collection'] : '';
						$buttoning  = isset($spec['Buttoning']) ? $spec['Buttoning'] : '';

						$product = isset($item['data']) ? $item['data'] : null;
						$name    = $fabric !== ''
							? $fabric . ($collection !== '' ? ' (' . $collection . ')' : '')
							: ($product ? $product->get_name() : __('Bespoke Jacket', 'alex-rose-2026'));

						$line_price = ($cart && $product) ? $cart->get_product_subtotal($product, $item['quantity']) : '';
						$remove_url = wc_get_cart_remove_url($key);

						// The two-column spec grid (only fields that have a value).
						$grid = array(
							__('Buttoning', 'alex-rose-2026') => $buttoning,
							__('Buttons', 'alex-rose-2026')   => isset($spec['Buttons']) ? $spec['Buttons'] : '',
							__('Vent', 'alex-rose-2026')      => isset($spec['Vents']) ? $spec['Vents'] : '',
							__('Pockets', 'alex-rose-2026')   => isset($spec['Pockets']) ? $spec['Pockets'] : '',
							__('Lining', 'alex-rose-2026')    => isset($spec['Lining']) ? $spec['Lining'] : '',
							__('Monogram', 'alex-rose-2026')  => isset($spec['Monogram']) ? $spec['Monogram'] : '',
						);
						$grid = array_filter($grid, static function ($v) { return $v !== ''; });
						?>
						<article class="crt-card">
							<div class="crt-card__head">
								<?php if ($image) : ?>
									<div class="crt-card__thumb"><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name); ?>"></div>
								<?php endif; ?>
								<div class="crt-card__meta">
									<p class="crt-card__eyebrow"><?php esc_html_e('Reserved Design', 'alex-rose-2026'); ?></p>
									<p class="crt-card__name"><?php echo esc_html($name); ?></p>
									<?php if ($buttoning !== '') : ?>
										<p class="crt-card__sub">&middot; <?php echo esc_html($buttoning); ?></p>
									<?php endif; ?>
								</div>
								<a class="crt-card__remove" href="<?php echo esc_url($remove_url); ?>" aria-label="<?php esc_attr_e('Remove this design', 'alex-rose-2026'); ?>">
									<?php echo $trash_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							</div>

							<?php if ($grid) : ?>
								<div class="crt-card__specs">
									<?php foreach ($grid as $label => $value) : ?>
										<div class="crt-card__spec">
											<span class="crt-card__spec-label"><?php echo esc_html($label); ?></span>
											<span class="crt-card__spec-value"><?php echo esc_html($value); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<div class="crt-card__price">
								<span class="crt-card__price-label"><?php esc_html_e('Starting From', 'alex-rose-2026'); ?></span>
								<span class="crt-card__price-value"><?php echo wp_kses_post($line_price); ?></span>
							</div>
						</article>
					<?php endforeach; ?>

					<p class="crt-grid__note"><?php esc_html_e('Your reserved designs are saved in your cart. No payment has been taken.', 'alex-rose-2026'); ?></p>
				</div>

				<aside class="crt-summary">
					<div class="crt-summary__head">
						<p class="crt-summary__eyebrow"><?php esc_html_e('Order Summary', 'alex-rose-2026'); ?></p>
						<?php $count = $cart->get_cart_contents_count(); ?>
						<p class="crt-summary__count">
							<?php
							/* translators: %d: number of reserved jackets */
							printf(esc_html(_n('%d jacket reserved', '%d jackets reserved', $count, 'alex-rose-2026')), (int) $count);
							?>
						</p>
					</div>

					<div class="crt-summary__rows">
						<div class="crt-summary__row">
							<span><?php esc_html_e('Jacket price', 'alex-rose-2026'); ?></span>
							<span><?php echo wp_kses_post($cart->get_cart_subtotal()); ?></span>
						</div>
						<div class="crt-summary__row">
							<span><?php esc_html_e('Alterations', 'alex-rose-2026'); ?></span>
							<span><?php esc_html_e('Included', 'alex-rose-2026'); ?></span>
						</div>
						<div class="crt-summary__row">
							<span><?php esc_html_e('UK delivery', 'alex-rose-2026'); ?></span>
							<span><?php esc_html_e('Free', 'alex-rose-2026'); ?></span>
						</div>
						<div class="crt-summary__row">
							<span><?php esc_html_e('Monogramming', 'alex-rose-2026'); ?></span>
							<span><?php esc_html_e('Included', 'alex-rose-2026'); ?></span>
						</div>
					</div>

					<div class="crt-summary__total">
						<span class="crt-summary__total-label"><?php esc_html_e('Starting From', 'alex-rose-2026'); ?></span>
						<span class="crt-summary__total-value"><?php echo wp_kses_post($cart->get_cart_subtotal()); ?></span>
					</div>

					<div class="crt-summary__actions">
						<a class="crt-summary__checkout" href="<?php echo esc_url(wc_get_checkout_url()); ?>">
							<?php esc_html_e('Checkout Now', 'alex-rose-2026'); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
						</a>
						<a class="crt-summary__add" href="<?php echo esc_url(home_url('/design-your-jacket/')); ?>"><?php esc_html_e('Add Another Jacket', 'alex-rose-2026'); ?></a>
					</div>

					<a class="crt-summary__enquiry" href="<?php echo esc_url(home_url('/contact/')); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path><path d="m21.854 2.147-10.94 10.939"></path></svg>
						<?php esc_html_e('Send Harold an Enquiry', 'alex-rose-2026'); ?>
					</a>
				</aside>
			</div>

		<?php endif; ?>

	</div>
</section>
