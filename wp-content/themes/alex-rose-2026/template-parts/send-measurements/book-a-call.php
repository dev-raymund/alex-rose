<?php
/**
 * "Send Measurements" — inline "Book a Call with Harold" panel.
 *
 * A tab sibling of the measure-yourself panel. Collects the visitor's contact
 * details + call topic, emails them to the tailor as an enquiry (handler
 * sm_book_call in inc/forms.php), then reveals the Calendly booking embed
 * prefilled with their name and email.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$sm_call_topics = array(
	__('Measurement guidance', 'alex-rose-2026'),
	__('Styling & cloth advice', 'alex-rose-2026'),
	__('Order review', 'alex-rose-2026'),
	__('General enquiry', 'alex-rose-2026'),
);
?>
<div class="sm-panel sm-panel--call" id="sm-call-panel" data-sm-panel hidden>

	<p class="sm-panel__intro"><?php esc_html_e('Harold guides you through every measurement on a short call, at a time that suits you. Confirm your details and choose a slot.', 'alex-rose-2026'); ?></p>

	<form
		class="sm-form sm-call-form"
		action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		method="post"
		data-sm-call-form
		novalidate
	>
		<?php wp_nonce_field('sm_book_call', 'sm_call_nonce'); ?>
		<input type="hidden" name="action" value="sm_book_call">
		<?php alex_rose_2026_form_honeypot_field(); ?>

		<div class="sm-form__grid sm-form__grid--2">
			<input type="text" id="sm-call-name" name="sm_call_name" class="sm-form__input" data-sm-call-required required placeholder="<?php esc_attr_e('Full name *', 'alex-rose-2026'); ?>">
			<input type="email" id="sm-call-email" name="sm_call_email" class="sm-form__input" data-sm-call-required required placeholder="<?php esc_attr_e('Email address *', 'alex-rose-2026'); ?>">
		</div>

		<input type="tel" id="sm-call-phone" name="sm_call_phone" class="sm-form__input" placeholder="<?php esc_attr_e('Phone (optional)', 'alex-rose-2026'); ?>">

		<div class="sm-form__field">
			<p class="sm-panel__kicker"><?php esc_html_e('What is the call about? (optional)', 'alex-rose-2026'); ?></p>
			<div class="sm-chips">
				<?php foreach ($sm_call_topics as $sm_topic) : ?>
					<button type="button" class="sm-chip" data-sm-call-topic="<?php echo esc_attr($sm_topic); ?>"><?php echo esc_html($sm_topic); ?></button>
				<?php endforeach; ?>
			</div>
			<input type="hidden" name="sm_call_topics" value="" data-sm-call-topics-value>
		</div>

		<textarea id="sm-call-notes" name="sm_call_notes" class="sm-form__input sm-form__textarea" rows="3" placeholder="<?php esc_attr_e('Any notes or questions for Harold? (optional)', 'alex-rose-2026'); ?>"></textarea>

		<p class="sm-form__error" data-sm-call-error role="alert" hidden></p>

		<button type="submit" class="sm-form__btn" data-sm-call-submit disabled><?php esc_html_e('Choose a Time →', 'alex-rose-2026'); ?></button>
	</form>

	<div class="sm-call-success" data-sm-call-success hidden>
		<p class="sm-panel__kicker sm-call-success__kicker"><?php esc_html_e('Choose a time', 'alex-rose-2026'); ?></p>
		<h2 class="sm-call-success__title"><?php esc_html_e('Book Your Consultation.', 'alex-rose-2026'); ?></h2>
		<div class="sm-call-cal">
			<iframe data-sm-call-calendly data-cal-base="https://calendly.com/alex-rose-tailor/virtual-fitting" src="about:blank" width="100%" height="100%" title="<?php esc_attr_e('Book a consultation with Harold', 'alex-rose-2026'); ?>" loading="lazy"></iframe>
		</div>
		<p class="sm-call-success__phone">
			<?php esc_html_e('Prefer to call?', 'alex-rose-2026'); ?>
			<a href="tel:+441134688588">0113 468 8588</a>
			<?php esc_html_e('Wed–Sat, 10 am–4.30 pm.', 'alex-rose-2026'); ?>
		</p>
	</div>

</div>
