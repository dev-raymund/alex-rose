<?php
/**
 * "Request Tape Measure" — form + measuring tips.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$required_mark = '<span class="rtm-form__required" aria-hidden="true">*</span>';

$tips = array(
	__('Measure over your shirt, not a thick jumper or jacket.', 'alex-rose-2026'),
	__('Stand naturally with your arms relaxed at your sides.', 'alex-rose-2026'),
	__('Ask someone to help for a more accurate reading.', 'alex-rose-2026'),
	__('Keep the tape level and snug but not tight.', 'alex-rose-2026'),
);
?>
<section class="rtm-main">
	<div class="rtm-main__inner ar-container ar-container--5xl">
		<div class="rtm-main__grid">
			<div class="rtm-main__form-col">
				<form
					class="rtm-form"
					action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
					method="post"
					data-rtm-form
					novalidate
				>
					<?php wp_nonce_field('rtm_request_tape', 'rtm_nonce'); ?>
					<input type="hidden" name="action" value="rtm_request_tape">
					<?php alex_rose_2026_form_honeypot_field(); ?>

					<div class="rtm-form__grid rtm-form__grid--2">
						<div class="rtm-form__field">
							<label class="rtm-form__label" for="rtm-name">
								<?php esc_html_e('Full name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="rtm-form__input" type="text" id="rtm-name" name="rtm_name" placeholder="<?php esc_attr_e('John Smith', 'alex-rose-2026'); ?>" required data-rtm-required>
						</div>
						<div class="rtm-form__field">
							<label class="rtm-form__label" for="rtm-email">
								<?php esc_html_e('Email address', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="rtm-form__input" type="email" id="rtm-email" name="rtm_email" placeholder="john@example.com" required data-rtm-required>
						</div>
					</div>

					<div class="rtm-form__block">
						<p class="rtm-form__kicker"><?php esc_html_e('Postal address', 'alex-rose-2026'); ?></p>
						<div class="rtm-form__fields">
							<div class="rtm-form__field">
								<label class="rtm-form__label" for="rtm-addr1">
									<?php esc_html_e('Address line 1', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</label>
								<input class="rtm-form__input" type="text" id="rtm-addr1" name="rtm_addr1" placeholder="<?php esc_attr_e('12 High Street', 'alex-rose-2026'); ?>" required data-rtm-required>
							</div>
							<div class="rtm-form__field">
								<label class="rtm-form__label" for="rtm-addr2"><?php esc_html_e('Address line 2 (optional)', 'alex-rose-2026'); ?></label>
								<input class="rtm-form__input" type="text" id="rtm-addr2" name="rtm_addr2" placeholder="<?php esc_attr_e('Apartment, suite, etc.', 'alex-rose-2026'); ?>">
							</div>
							<div class="rtm-form__grid rtm-form__grid--2">
								<div class="rtm-form__field">
									<label class="rtm-form__label" for="rtm-city">
										<?php esc_html_e('Town or city', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</label>
									<input class="rtm-form__input" type="text" id="rtm-city" name="rtm_city" placeholder="<?php esc_attr_e('Leeds', 'alex-rose-2026'); ?>" required data-rtm-required>
								</div>
								<div class="rtm-form__field">
									<label class="rtm-form__label" for="rtm-postcode">
										<?php esc_html_e('Postcode', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</label>
									<input class="rtm-form__input" type="text" id="rtm-postcode" name="rtm_postcode" placeholder="LS1 1AB" required data-rtm-required>
								</div>
							</div>
						</div>
					</div>

					<button class="rtm-form__btn" type="submit" disabled data-rtm-submit>
						<?php esc_html_e('Send Me a Tape Measure', 'alex-rose-2026'); ?>
					</button>

					<p class="rtm-form__error" data-rtm-error role="alert" hidden></p>
				</form>

				<div id="rtm-form-confirmation" class="rtm-form__confirmation" role="status" aria-live="polite" hidden>
					<p class="rtm-form__confirmation-kicker"><?php esc_html_e('Request Received', 'alex-rose-2026'); ?></p>
					<h2 class="rtm-form__confirmation-title"><?php esc_html_e('Your tape measure is on its way.', 'alex-rose-2026'); ?></h2>
					<p class="rtm-form__confirmation-body">
						<?php esc_html_e('We will post your free tape measure and measurement guide within one working day.', 'alex-rose-2026'); ?>
					</p>
				</div>
			</div>

			<aside class="rtm-main__tips-col">
				<p class="rtm-tips__kicker"><?php esc_html_e('Tips for measuring at home', 'alex-rose-2026'); ?></p>
				<ol class="rtm-tips__list">
					<?php foreach ($tips as $index => $tip) : ?>
						<li class="rtm-tips__item">
							<span class="rtm-tips__num"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
							<p class="rtm-tips__text"><?php echo esc_html($tip); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
				<div class="rtm-tips__callout">
					<p class="rtm-tips__callout-text">
						<?php
						printf(
							/* translators: %s: link to post-your-jacket page */
							esc_html__('Not confident measuring yourself? You can always %s and we will do it for you.', 'alex-rose-2026'),
							'<a class="rtm-tips__callout-link" href="' . esc_url(home_url('/post-your-jacket/')) . '">' . esc_html__('post us a jacket that fits you', 'alex-rose-2026') . '</a>'
						);
						?>
					</p>
				</div>
			</aside>
		</div>
	</div>
</section>
