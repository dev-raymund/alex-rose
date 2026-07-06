<?php
/**
 * "Send Measurements" — inline "Post Us a Jacket" panel.
 *
 * A tab sibling of the measure-yourself panel. Collects the visitor's contact
 * and delivery details, emails them to the tailor as an enquiry (handler
 * sm_post_jacket in inc/forms.php), then reveals the Calendly booking embed.
 *
 * The service is UK-only, but the card is never disabled — overseas visitors
 * can still open it; the notice below makes the restriction clear.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="sm-panel sm-panel--post" id="sm-post-panel" data-sm-panel hidden>

	<p class="sm-panel__intro"><?php esc_html_e('Pack a jacket that fits you well and we will measure it by hand, then return it within 48 hours. We send you a free prepaid box. Just confirm your delivery address below.', 'alex-rose-2026'); ?></p>

	<p class="sm-panel__notice">
		<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><circle cx="6" cy="6" r="5.5" stroke="#C8A96A" stroke-width="1"></circle><line x1="6" y1="5" x2="6" y2="9" stroke="#C8A96A" stroke-width="1" stroke-linecap="round"></line><circle cx="6" cy="3.5" r="0.6" fill="#C8A96A"></circle></svg>
		<?php esc_html_e('This service is available within the UK only.', 'alex-rose-2026'); ?>
	</p>

	<form
		class="sm-form sm-post-form"
		action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		method="post"
		data-sm-post-form
		novalidate
	>
		<?php wp_nonce_field('sm_post_jacket', 'sm_post_nonce'); ?>
		<input type="hidden" name="action" value="sm_post_jacket">
		<?php alex_rose_2026_form_honeypot_field(); ?>

		<div class="sm-form__grid sm-form__grid--2">
			<input type="text" id="sm-post-name" name="sm_post_name" class="sm-form__input" data-sm-post-required required placeholder="<?php esc_attr_e('Full name *', 'alex-rose-2026'); ?>">
			<input type="email" id="sm-post-email" name="sm_post_email" class="sm-form__input" data-sm-post-required required placeholder="<?php esc_attr_e('Email address *', 'alex-rose-2026'); ?>">
		</div>

		<input type="tel" id="sm-post-phone" name="sm_post_phone" class="sm-form__input" placeholder="<?php esc_attr_e('Phone (optional)', 'alex-rose-2026'); ?>">

		<p class="sm-panel__kicker"><?php esc_html_e('Delivery address for the box', 'alex-rose-2026'); ?></p>

		<input type="text" id="sm-post-address1" name="sm_post_address1" class="sm-form__input" data-sm-post-required required placeholder="<?php esc_attr_e('Address line 1 *', 'alex-rose-2026'); ?>">
		<input type="text" id="sm-post-address2" name="sm_post_address2" class="sm-form__input" placeholder="<?php esc_attr_e('Address line 2 (optional)', 'alex-rose-2026'); ?>">

		<div class="sm-form__grid sm-form__grid--2">
			<input type="text" id="sm-post-town" name="sm_post_town" class="sm-form__input" data-sm-post-required required placeholder="<?php esc_attr_e('Town or city *', 'alex-rose-2026'); ?>">
			<input type="text" id="sm-post-postcode" name="sm_post_postcode" class="sm-form__input" data-sm-post-required required placeholder="<?php esc_attr_e('Postcode *', 'alex-rose-2026'); ?>">
		</div>

		<textarea id="sm-post-notes" name="sm_post_notes" class="sm-form__input sm-form__textarea" rows="3" placeholder="<?php esc_attr_e('Any notes (optional)', 'alex-rose-2026'); ?>"></textarea>

		<p class="sm-form__error" data-sm-post-error role="alert" hidden></p>

		<button type="submit" class="sm-form__btn" data-sm-post-submit disabled><?php esc_html_e('Send Me the Box & Book a Call →', 'alex-rose-2026'); ?></button>
	</form>

	<div class="sm-success" data-sm-post-success hidden>

		<div class="sm-success__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
		</div>

		<p class="sm-success__kicker"><?php esc_html_e('Request received', 'alex-rose-2026'); ?></p>
		<h2 class="sm-success__title"><?php esc_html_e('Thank you', 'alex-rose-2026'); ?>, <span data-sm-post-name></span>.</h2>
		<p class="sm-success__copy">
			<?php esc_html_e('We will send your free prepaid box and returns label to', 'alex-rose-2026'); ?>
			<span class="sm-success__email" data-sm-post-address></span>
			<?php esc_html_e('within two working days. A confirmation will be sent to', 'alex-rose-2026'); ?>
			<span class="sm-success__email" data-sm-post-email></span>.
		</p>

		<p class="sm-success__kicker"><?php esc_html_e('Next step', 'alex-rose-2026'); ?></p>
		<h3 class="sm-success__subtitle"><?php esc_html_e('Book a Call with Harold.', 'alex-rose-2026'); ?></h3>
		<p class="sm-success__lead"><?php esc_html_e('While you wait for your box, book a short call with Harold. He will talk you through exactly what to send and answer any questions.', 'alex-rose-2026'); ?></p>

		<div class="sm-success__cal">
			<iframe data-sm-post-calendly data-cal-base="https://calendly.com/alex-rose-tailor/virtual-fitting" src="about:blank" width="100%" height="100%" title="<?php esc_attr_e('Book a consultation with Harold', 'alex-rose-2026'); ?>" loading="lazy"></iframe>
		</div>

		<p class="sm-success__phone">
			<?php esc_html_e('Prefer to call?', 'alex-rose-2026'); ?>
			<a href="tel:+441134688588">0113 468 8588</a>
			<?php esc_html_e('Wed–Sat, 10 am–4.30 pm.', 'alex-rose-2026'); ?>
		</p>

		<?php get_template_part('template-parts/send-measurements/offer'); ?>
	</div>

</div>
