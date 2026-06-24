<?php
/**
 * Checkout Form — Alex Rose "Complete Your Order" layout.
 *
 * Override of woocommerce/checkout/form-checkout.php. Keeps all WooCommerce
 * hooks, field rendering, order review and payment intact; only the structure
 * and surrounding markup change to match the bespoke design.
 *
 * @package Alex_Rose_2026
 */

defined('ABSPATH') || exit;

if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}
?>

<section class="arco-hero">
	<img class="arco-hero__media" src="<?php echo esc_url(ALEX_ROSE_2026_URI . '/assets/img/harold2.jpg'); ?>" alt="" aria-hidden="true" />
	<div class="arco-hero__overlay" aria-hidden="true"></div>
	<div class="arco-hero__inner">
		<div class="arco-hero__rule" aria-hidden="true"></div>
		<p class="arco-hero__eyebrow"><?php esc_html_e('Secure Checkout', 'alex-rose-2026'); ?></p>
		<h1 class="arco-hero__title"><?php esc_html_e('Complete Your Order.', 'alex-rose-2026'); ?></h1>
		<p class="arco-hero__sub"><?php esc_html_e('No payment is taken at this stage. Your tailor will be in touch within 24 hours.', 'alex-rose-2026'); ?></p>
	</div>
</section>

<div class="arco-wrap">
	<?php do_action('woocommerce_before_checkout_form', $checkout); ?>

	<form name="checkout" method="post" class="checkout woocommerce-checkout arco-form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php esc_attr_e('Checkout', 'woocommerce'); ?>">

		<div class="arco-grid">

			<div class="arco-col arco-col--details">
				<?php if ($checkout->get_checkout_fields()) : ?>

					<?php do_action('woocommerce_checkout_before_customer_details'); ?>

					<div id="customer_details">
						<?php do_action('woocommerce_checkout_billing'); ?>
						<?php do_action('woocommerce_checkout_shipping'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_customer_details'); ?>

				<?php endif; ?>

				<aside class="arco-promise">
					<div class="arco-promise__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" width="15" height="15" stroke="#C8A96A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
					</div>
					<div>
						<p class="arco-promise__eyebrow"><?php esc_html_e('Our Promise', 'alex-rose-2026'); ?></p>
						<p class="arco-promise__title"><?php esc_html_e('Your Perfect Fit is Our Top Priority', 'alex-rose-2026'); ?></p>
						<p class="arco-promise__copy"><?php esc_html_e('If your jacket does not fit exactly right when it arrives, simply contact us and we will arrange everything, whether that is an alteration, an adjustment, or a full re-make. We do not consider a jacket finished until you are completely happy wearing it.', 'alex-rose-2026'); ?></p>
						<a class="arco-promise__link" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>"><?php esc_html_e('Speak to Your Tailor', 'alex-rose-2026'); ?></a>
					</div>
				</aside>
			</div>

			<div class="arco-col arco-col--review">
				<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

				<h3 id="order_review_heading" class="arco-review__heading"><?php esc_html_e('Your Order', 'alex-rose-2026'); ?></h3>

				<?php do_action('woocommerce_checkout_before_order_review'); ?>

				<div id="order_review" class="woocommerce-checkout-review-order arco-review">
					<?php do_action('woocommerce_checkout_order_review'); ?>
				</div>

				<?php do_action('woocommerce_checkout_after_order_review'); ?>
			</div>

		</div>
	</form>

	<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>
