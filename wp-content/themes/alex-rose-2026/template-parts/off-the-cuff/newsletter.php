<?php
/**
 * "Off the Cuff" — newsletter signup band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="otc-newsletter">
	<div class="otc-newsletter__inner ar-container">
		<div class="otc-newsletter__lead">
			<p class="otc-newsletter__kicker"><?php esc_html_e('The Off the Cuff Newsletter', 'alex-rose-2026'); ?></p>
			<h2 class="otc-newsletter__title"><?php esc_html_e('Notes from the workroom, by post.', 'alex-rose-2026'); ?></h2>
			<p class="otc-newsletter__text">
				<?php esc_html_e('Occasional letters on cloth, fit and the craft of tailoring, written by Harold from the workroom. The kind of thing worth reading slowly.', 'alex-rose-2026'); ?>
			</p>
		</div>

		<div class="otc-newsletter__form-col">
			<form
				class="otc-newsletter__form"
				action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
				method="post"
				data-otc-newsletter-form
				novalidate
				aria-describedby="otc-newsletter-confirmation"
			>
				<?php wp_nonce_field('otc_newsletter_signup', 'otc_newsletter_nonce'); ?>
				<input type="hidden" name="action" value="otc_newsletter_signup">
				<?php alex_rose_2026_form_honeypot_field(); ?>
				<label class="otc-newsletter__label-sr" for="otc-newsletter-email"><?php esc_html_e('Email address', 'alex-rose-2026'); ?></label>
				<input
					class="otc-newsletter__input"
					type="email"
					id="otc-newsletter-email"
					name="otc_newsletter_email"
					placeholder="<?php esc_attr_e('Your email address', 'alex-rose-2026'); ?>"
					required
					autocomplete="email"
				>
				<button class="otc-newsletter__submit" type="submit" data-otc-newsletter-submit>
					<?php esc_html_e('Subscribe', 'alex-rose-2026'); ?>
				</button>
			</form>

			<p class="otc-newsletter__error" data-otc-newsletter-error role="alert" hidden></p>

			<p class="otc-newsletter__note">
				<?php esc_html_e('Unsubscribe at any time. By signing up you agree to our', 'alex-rose-2026'); ?>
				<a class="otc-newsletter__link" href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">
					<?php esc_html_e('privacy policy', 'alex-rose-2026'); ?>
				</a>.
			</p>

			<p class="otc-newsletter__confirmation" id="otc-newsletter-confirmation" hidden>
				<?php esc_html_e('Thank you. You are on the list.', 'alex-rose-2026'); ?>
			</p>
		</div>
	</div>
</section>
