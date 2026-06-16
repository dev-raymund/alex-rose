<?php
/**
 * Template Name: Checkout
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="main" class="page-dist page-checkout">

	<div class="page-banner">
		<div class="container">
			<p class="t-label"><?php esc_html_e('Secure checkout', 'alex-rose-2026'); ?></p>
			<h1 class="t-h1" style="color:var(--white)"><?php esc_html_e('Checkout', 'alex-rose-2026'); ?></h1>
		</div>
	</div>

	<section class="section" style="background:var(--bg)">
		<div class="container">

			<div class="step-progress mb-48" style="max-width:480px;margin:0 auto 48px" id="checkout-progress">
				<div class="step-progress-item">
					<div class="step-dot active" id="cdot-0">1</div>
					<div class="step-progress-label"><?php esc_html_e('Details', 'alex-rose-2026'); ?></div>
				</div>
				<div class="step-progress-item">
					<div class="step-dot" id="cdot-1">2</div>
					<div class="step-progress-label"><?php esc_html_e('Delivery', 'alex-rose-2026'); ?></div>
				</div>
				<div class="step-progress-item">
					<div class="step-dot" id="cdot-2">3</div>
					<div class="step-progress-label"><?php esc_html_e('Payment', 'alex-rose-2026'); ?></div>
				</div>
			</div>

			<div class="checkout-layout" id="checkout-layout">
				<div>
					<form class="multistep-form" id="checkout-form" novalidate>

						<div class="step-panel active" id="cpanel-0">
							<div class="checkout-card">
								<h3><?php esc_html_e('Contact information', 'alex-rose-2026'); ?></h3>
								<div class="form-grid-2">
									<div class="form-group">
										<label class="form-label" for="ch-first"><?php esc_html_e('First name', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-first" class="form-control" required placeholder="James">
									</div>
									<div class="form-group">
										<label class="form-label" for="ch-last"><?php esc_html_e('Last name', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-last" class="form-control" required placeholder="Wilson">
									</div>
								</div>
								<div class="form-group">
									<label class="form-label" for="ch-email"><?php esc_html_e('Email address', 'alex-rose-2026'); ?></label>
									<input type="email" id="ch-email" class="form-control" required placeholder="james@example.com">
								</div>
								<div class="form-group">
									<label class="form-label" for="ch-phone"><?php esc_html_e('Phone number', 'alex-rose-2026'); ?></label>
									<input type="tel" id="ch-phone" class="form-control" placeholder="+44 7700 900000">
								</div>
							</div>
							<button type="button" class="btn btn-primary btn-lg" data-next="1" style="width:100%;justify-content:center"><?php esc_html_e('Continue to delivery →', 'alex-rose-2026'); ?></button>
							<p class="t-small t-center mt-12"><a href="<?php echo esc_url(home_url('/cart/')); ?>" style="color:var(--muted)"><?php esc_html_e('← Return to cart', 'alex-rose-2026'); ?></a></p>
						</div>

						<div class="step-panel" id="cpanel-1">
							<div class="checkout-card">
								<h3><?php esc_html_e('Delivery address', 'alex-rose-2026'); ?></h3>
								<div class="form-group">
									<label class="form-label" for="ch-addr1"><?php esc_html_e('Address line 1', 'alex-rose-2026'); ?></label>
									<input type="text" id="ch-addr1" class="form-control" required placeholder="12 Savile Row">
								</div>
								<div class="form-group">
									<label class="form-label" for="ch-addr2"><?php esc_html_e('Address line 2', 'alex-rose-2026'); ?></label>
									<input type="text" id="ch-addr2" class="form-control" placeholder="Apartment, floor…">
								</div>
								<div class="form-grid-2">
									<div class="form-group">
										<label class="form-label" for="ch-city"><?php esc_html_e('City', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-city" class="form-control" required placeholder="London">
									</div>
									<div class="form-group">
										<label class="form-label" for="ch-postcode"><?php esc_html_e('Postcode / ZIP', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-postcode" class="form-control" required placeholder="W1S 3PL">
									</div>
								</div>
								<div class="form-group">
									<label class="form-label" for="ch-country"><?php esc_html_e('Country', 'alex-rose-2026'); ?></label>
									<select id="ch-country" class="form-control">
										<option>United Kingdom</option>
										<option>United States</option>
										<option>Australia</option>
										<option>Canada</option>
										<option>France</option>
										<option>Germany</option>
										<option>Ireland</option>
										<option>Italy</option>
										<option>Netherlands</option>
										<option>New Zealand</option>
										<option>Singapore</option>
										<option>Switzerland</option>
										<option>UAE</option>
										<option>Other</option>
									</select>
								</div>
							</div>
							<div style="background:var(--white);border:1px solid var(--border);padding:20px;margin-bottom:20px;display:flex;align-items:center;gap:12px">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;color:var(--gold);flex-shrink:0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
								<div>
									<p style="font-size:0.8125rem;font-weight:600"><?php esc_html_e('Free worldwide delivery', 'alex-rose-2026'); ?></p>
									<p class="t-small"><?php esc_html_e('Tracked courier · 5–10 days international', 'alex-rose-2026'); ?></p>
								</div>
							</div>
							<div class="flex gap-12">
								<button type="button" class="btn btn-outline" data-back="0"><?php esc_html_e('← Back', 'alex-rose-2026'); ?></button>
								<button type="button" class="btn btn-primary" data-next="2" style="flex:1;justify-content:center"><?php esc_html_e('Continue to payment →', 'alex-rose-2026'); ?></button>
							</div>
						</div>

						<div class="step-panel" id="cpanel-2">
							<div class="checkout-card">
								<h3><?php esc_html_e('Payment method', 'alex-rose-2026'); ?></h3>
								<div class="payment-tabs" style="margin-bottom:24px">
									<button type="button" class="payment-tab active" data-tab="card">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
										<?php esc_html_e('Card', 'alex-rose-2026'); ?>
									</button>
									<button type="button" class="payment-tab" data-tab="paypal">
										<span class="paypal-badge">Pay</span><span class="paypal-badge" style="color:#009cde">Pal</span>
									</button>
									<button type="button" class="payment-tab" data-tab="applepay">
										<span class="applepay-badge"> Pay</span>
									</button>
									<button type="button" class="payment-tab" data-tab="klarna">
										<span class="klarna-badge">klarna</span>
									</button>
								</div>

								<div class="payment-panel active" id="payment-card">
									<div class="form-group">
										<label class="form-label" for="ch-cardnum"><?php esc_html_e('Card number', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-cardnum" class="form-control" required placeholder="1234 5678 9012 3456" maxlength="19">
									</div>
									<div class="form-grid-2">
										<div class="form-group">
											<label class="form-label" for="ch-exp"><?php esc_html_e('Expiry', 'alex-rose-2026'); ?></label>
											<input type="text" id="ch-exp" class="form-control" required placeholder="MM / YY" maxlength="7">
										</div>
										<div class="form-group">
											<label class="form-label" for="ch-cvc"><?php esc_html_e('CVC', 'alex-rose-2026'); ?></label>
											<input type="text" id="ch-cvc" class="form-control" required placeholder="123" maxlength="4">
										</div>
									</div>
									<div class="form-group">
										<label class="form-label" for="ch-name"><?php esc_html_e('Name on card', 'alex-rose-2026'); ?></label>
										<input type="text" id="ch-name" class="form-control" required placeholder="James Wilson">
									</div>
								</div>

								<div class="payment-panel" id="payment-paypal">
									<div class="payment-alt-msg">
										<p class="paypal-badge" style="font-size:1.5rem;margin-bottom:12px">PayPal</p>
										<p style="font-size:0.875rem;color:var(--muted)"><?php esc_html_e("You'll be redirected to PayPal to complete your payment securely.", 'alex-rose-2026'); ?></p>
									</div>
								</div>

								<div class="payment-panel" id="payment-applepay">
									<div class="payment-alt-msg">
										<div class="applepay-badge" style="font-size:1.1rem;padding:10px 20px;display:inline-block;margin-bottom:12px"> Pay</div>
										<p style="font-size:0.875rem;color:var(--muted)"><?php esc_html_e('Use Touch ID or Face ID to complete your payment with Apple Pay.', 'alex-rose-2026'); ?></p>
									</div>
								</div>

								<div class="payment-panel" id="payment-klarna">
									<div class="payment-alt-msg">
										<span class="klarna-badge" style="font-size:1.1rem;padding:8px 16px;display:inline-block;margin-bottom:12px">klarna</span>
										<p style="font-size:0.875rem;color:var(--muted);margin-bottom:12px"><?php esc_html_e('Pay in 3 interest-free instalments. No fees, no surprises.', 'alex-rose-2026'); ?></p>
										<div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;font-size:0.8rem;color:var(--muted)">
											<span><?php esc_html_e('✓ Today: £165.00', 'alex-rose-2026'); ?></span>
											<span><?php esc_html_e('✓ In 30 days: £165.00', 'alex-rose-2026'); ?></span>
											<span><?php esc_html_e('✓ In 60 days: £165.00', 'alex-rose-2026'); ?></span>
										</div>
									</div>
								</div>
							</div>

							<div class="flex gap-12 mt-8">
								<button type="button" class="btn btn-outline" data-back="1"><?php esc_html_e('← Back', 'alex-rose-2026'); ?></button>
								<button type="button" class="btn btn-primary btn-lg" data-submit style="flex:1;justify-content:center"><?php esc_html_e('Place order →', 'alex-rose-2026'); ?></button>
							</div>
							<p class="t-small t-center mt-16" style="color:var(--muted)">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:4px" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
								<?php esc_html_e('Your payment is encrypted and secure', 'alex-rose-2026'); ?>
							</p>
						</div>
					</form>

					<div id="form-confirmation" style="display:none">
						<div class="confirmation-box">
							<div class="confirmation-icon">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
							</div>
							<p class="t-label mb-16"><?php esc_html_e('Order confirmed', 'alex-rose-2026'); ?></p>
							<h2 class="t-h2 mb-16"><?php esc_html_e('Thank you for your order', 'alex-rose-2026'); ?></h2>
							<p class="t-body mb-8" style="color:var(--muted)"><?php esc_html_e("You'll receive a confirmation email shortly. Your order reference is", 'alex-rose-2026'); ?> <strong>AR-<span id="order-ref"></span></strong>.</p>
							<p class="t-body mb-32" style="color:var(--muted)"><?php esc_html_e("Harold will review your order and be in touch within one working day. If you haven't already sent your measurements, you can do so below.", 'alex-rose-2026'); ?></p>
							<div class="flex gap-16 flex-wrap" style="justify-content:center">
								<a href="<?php echo esc_url(home_url('/send-measurements/')); ?>" class="btn btn-primary"><?php esc_html_e('Send measurements', 'alex-rose-2026'); ?></a>
								<a href="<?php echo esc_url(home_url('/post-your-jacket/')); ?>" class="btn btn-outline"><?php esc_html_e('Post a jacket instead', 'alex-rose-2026'); ?></a>
							</div>
						</div>
					</div>
				</div>

				<div>
					<div class="cart-summary">
						<h3 class="t-h3 mb-20"><?php esc_html_e('Your order', 'alex-rose-2026'); ?></h3>
						<div id="checkout-items" style="margin-bottom:16px;font-size:0.875rem;color:var(--muted)"><?php esc_html_e('Loading…', 'alex-rose-2026'); ?></div>
						<div class="summary-line"><span><?php esc_html_e('Subtotal', 'alex-rose-2026'); ?></span><span id="co-subtotal">—</span></div>
						<div class="summary-line"><span><?php esc_html_e('Delivery', 'alex-rose-2026'); ?></span><span style="color:var(--gold);font-weight:600"><?php esc_html_e('Free', 'alex-rose-2026'); ?></span></div>
						<div class="summary-line total"><span><?php esc_html_e('Total', 'alex-rose-2026'); ?></span><span id="co-total">—</span></div>
						<div style="padding-top:16px;border-top:1px solid var(--border)">
							<p class="t-small t-center" style="color:var(--muted)"><?php esc_html_e("Free alterations if your jacket doesn't fit perfectly — guaranteed.", 'alex-rose-2026'); ?></p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

</main>

<?php get_footer();
