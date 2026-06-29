<?php
/**
 * Launch — founding-member landing page markup.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$lp_logo_id = (int) get_theme_mod('custom_logo');
$lp_email   = get_theme_mod('alex_rose_2026_sidebar_email', 'tailor@alexrose.uk');
$lp_chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>';
?>
<div class="lp">
	<div class="lp__stripe" aria-hidden="true"></div>

	<header class="lp__header">
		<a class="lp__logo" href="<?php echo esc_url(home_url('/')); ?>">
			<?php
			if ($lp_logo_id) {
				echo wp_get_attachment_image($lp_logo_id, 'full', false, array('alt' => esc_attr(get_bloginfo('name')), 'class' => 'lp__logo-img'));
			} else {
				?>
				<img class="lp__logo-img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/alex-rose-logo.png')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php
			}
			?>
		</a>
		<a class="lp__header-email" href="mailto:<?php echo esc_attr($lp_email); ?>"><?php echo esc_html($lp_email); ?></a>
	</header>

	<main class="lp__main">
		<div class="lp__inner">
			<p class="lp__eyebrow"><?php esc_html_e('Founding offer', 'alex-rose-2026'); ?></p>
			<h1 class="lp__title"><?php echo wp_kses(__('20% off your first jacket.<br>Offer closes 10 July 2026.', 'alex-rose-2026'), array('br' => array())); ?></h1>
			<p class="lp__lede"><?php esc_html_e('Made-to-measure jackets from a Leeds family tailoring business with eighty years of heritage. Join the founding list and receive your personal 20% discount code by return. No conditions.', 'alex-rose-2026'); ?></p>

			<div class="lp__countdown" data-countdown="2026-07-10T23:59:59">
				<div class="lp__unit"><span class="lp__unit-num" data-cd="days">--</span><span class="lp__unit-label"><?php esc_html_e('days', 'alex-rose-2026'); ?></span></div>
				<div class="lp__unit"><span class="lp__unit-num" data-cd="hours">--</span><span class="lp__unit-label"><?php esc_html_e('hours', 'alex-rose-2026'); ?></span></div>
				<div class="lp__unit"><span class="lp__unit-num" data-cd="mins">--</span><span class="lp__unit-label"><?php esc_html_e('mins', 'alex-rose-2026'); ?></span></div>
				<div class="lp__unit"><span class="lp__unit-num" data-cd="secs">--</span><span class="lp__unit-label"><?php esc_html_e('secs', 'alex-rose-2026'); ?></span></div>
			</div>

			<form class="lp__form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
				<input type="hidden" name="action" value="lp_join_waitlist">
				<?php wp_nonce_field('lp_join_waitlist', 'lp_nonce'); ?>
				<?php alex_rose_2026_form_honeypot_field(); ?>

				<div class="lp__row">
					<input class="lp__email" type="email" name="lp_email" required placeholder="<?php esc_attr_e('Your email address', 'alex-rose-2026'); ?>">
					<button class="lp__submit" type="submit">
						<?php esc_html_e('Get my discount code', 'alex-rose-2026'); ?>
						<?php echo $lp_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</div>

				<div class="lp__field">
					<label class="lp__field-label" for="lp_referral"><?php esc_html_e('Were you referred by someone? Add their name (optional)', 'alex-rose-2026'); ?></label>
					<input class="lp__referral" id="lp_referral" type="text" name="lp_referral" placeholder="<?php esc_attr_e('e.g. suittielinksbracesman (Instagram)', 'alex-rose-2026'); ?>">
				</div>

				<p class="lp__consent"><?php esc_html_e('By entering your email you agree to receive marketing offers and updates from Alex Rose Fine Tailoring. We will never share your details. Unsubscribe at any time.', 'alex-rose-2026'); ?></p>
				<p class="lp__error" role="alert" hidden></p>
			</form>

			<div class="lp__confirm" hidden>
				<p class="lp__confirm-title"><?php esc_html_e('You are on the founding list.', 'alex-rose-2026'); ?></p>
				<p class="lp__confirm-copy"><?php esc_html_e('Check your inbox — your personal 20% discount code is on its way. Ready to begin?', 'alex-rose-2026'); ?></p>
				<div class="lp__confirm-actions">
					<a class="lp__btn lp__btn--ghost" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design your jacket now', 'alex-rose-2026'); ?> <?php echo $lp_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<a class="lp__btn lp__btn--gold" href="<?php echo esc_url(home_url('/schedule-a-call')); ?>"><?php esc_html_e('Book a call with Harold', 'alex-rose-2026'); ?> <?php echo $lp_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</div>

			<p class="lp__fine"><?php esc_html_e('Offer valid until 10 July 2026  ·  One jacket per customer  ·  No conditions', 'alex-rose-2026'); ?></p>
		</div>
	</main>

	<footer class="lp__footer">
		<p class="lp__footer-eyebrow"><?php esc_html_e('Ready to start? Skip the queue.', 'alex-rose-2026'); ?></p>
		<div class="lp__footer-actions">
			<a class="lp__btn lp__btn--ghost" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design your jacket now', 'alex-rose-2026'); ?> <?php echo $lp_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<a class="lp__btn lp__btn--gold" href="<?php echo esc_url(home_url('/schedule-a-call')); ?>"><?php esc_html_e('Book a call with Harold', 'alex-rose-2026'); ?> <?php echo $lp_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
		<p class="lp__footer-meta"><?php esc_html_e('Alex Rose Fine Tailoring, Leeds  ·  alexrose.uk', 'alex-rose-2026'); ?></p>
	</footer>

	<div class="lp__currency" role="group" aria-label="<?php esc_attr_e('Currency', 'alex-rose-2026'); ?>">
		<button type="button" class="lp__currency-btn" data-currency="GBP">GBP</button>
		<button type="button" class="lp__currency-btn" data-currency="EUR">EUR</button>
		<button type="button" class="lp__currency-btn is-active" data-currency="USD">USD</button>
	</div>
</div>
