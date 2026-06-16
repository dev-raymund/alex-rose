<?php
/**
 * "Request Cloth Samples" — sample picker + address form.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$collections   = alex_rose_2026_cloth_sample_collections();
$required_mark = '<span class="rcs-form__required" aria-hidden="true">*</span>';
$chevron_down  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"></path></svg>';
?>
<section class="rcs-form-section">
	<div class="rcs-form-section__inner ar-container ar-container--3xl">
		<form
			class="rcs-form"
			action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
			method="post"
			data-rcs-form
			novalidate
		>
			<?php wp_nonce_field('rcs_request_samples', 'rcs_nonce'); ?>
			<input type="hidden" name="action" value="rcs_request_samples">
			<?php alex_rose_2026_form_honeypot_field(); ?>

			<div class="rcs-form__block">
				<div class="rcs-form__block-head">
					<p class="rcs-form__kicker"><?php esc_html_e('Choose up to 3 cloth samples (optional)', 'alex-rose-2026'); ?></p>
					<p class="rcs-form__counter" data-rcs-counter aria-live="polite">
						<?php esc_html_e('3 remaining', 'alex-rose-2026'); ?>
					</p>
				</div>

				<div class="rcs-accordions" data-rcs-accordions>
					<?php foreach ($collections as $collection) : ?>
						<?php
						$slug       = (string) $collection['slug'];
						$panel_id   = 'rcs-panel-' . $slug;
						$swatches   = is_array($collection['swatches'] ?? null) ? $collection['swatches'] : array();
						?>
						<div class="rcs-accordion">
							<button
								type="button"
								class="rcs-accordion__toggle"
								aria-expanded="false"
								aria-controls="<?php echo esc_attr($panel_id); ?>"
								data-rcs-accordion-toggle
							>
								<span class="rcs-accordion__label"><?php echo esc_html((string) $collection['label']); ?></span>
								<?php echo $chevron_down; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</button>
							<div class="rcs-accordion__panel" id="<?php echo esc_attr($panel_id); ?>" hidden>
								<?php if ($swatches !== array()) : ?>
									<div class="rcs-swatches">
										<?php foreach ($swatches as $swatch) : ?>
											<?php
											$swatch_id    = (string) $swatch['id'];
											$swatch_name  = (string) $swatch['name'];
											$swatch_image = (string) $swatch['image'];
											$swatch_alt   = (string) $swatch['alt'];
											$swatch_value = $slug . '|' . $swatch_name;
											?>
											<button
												type="button"
												class="rcs-swatch"
												data-rcs-swatch
												data-rcs-value="<?php echo esc_attr($swatch_value); ?>"
												data-rcs-label="<?php echo esc_attr($swatch_name); ?>"
												aria-pressed="false"
											>
												<?php if ($swatch_image !== '') : ?>
													<img class="rcs-swatch__img" src="<?php echo esc_url($swatch_image); ?>" alt="<?php echo esc_attr($swatch_alt); ?>" loading="lazy">
												<?php endif; ?>
												<span class="rcs-swatch__name"><?php echo esc_html($swatch_name); ?></span>
											</button>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<p class="rcs-accordion__empty"><?php esc_html_e('Samples for this collection will be available shortly.', 'alex-rose-2026'); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="rcs-form__selected" data-rcs-selected-wrap hidden>
					<p class="rcs-form__selected-label"><?php esc_html_e('Selected samples', 'alex-rose-2026'); ?></p>
					<ul class="rcs-form__selected-list" data-rcs-selected-list></ul>
				</div>
				<div data-rcs-hidden-inputs></div>
			</div>

			<div class="rcs-form__block">
				<p class="rcs-form__kicker"><?php esc_html_e('Your details', 'alex-rose-2026'); ?></p>
				<div class="rcs-form__grid rcs-form__grid--2">
					<div class="rcs-form__field">
						<label class="rcs-form__label" for="rcs-name">
							<?php esc_html_e('Full name', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="rcs-form__input" type="text" id="rcs-name" name="rcs_name" placeholder="<?php esc_attr_e('John Smith', 'alex-rose-2026'); ?>" required data-rcs-required>
					</div>
					<div class="rcs-form__field">
						<label class="rcs-form__label" for="rcs-email">
							<?php esc_html_e('Email address', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="rcs-form__input" type="email" id="rcs-email" name="rcs_email" placeholder="john@example.com" required data-rcs-required>
					</div>
				</div>
			</div>

			<div class="rcs-form__block">
				<p class="rcs-form__kicker"><?php esc_html_e('Postal address', 'alex-rose-2026'); ?></p>
				<div class="rcs-form__fields">
					<div class="rcs-form__field">
						<label class="rcs-form__label" for="rcs-addr1">
							<?php esc_html_e('Address line 1', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</label>
						<input class="rcs-form__input" type="text" id="rcs-addr1" name="rcs_addr1" placeholder="<?php esc_attr_e('12 High Street', 'alex-rose-2026'); ?>" required data-rcs-required>
					</div>
					<div class="rcs-form__field">
						<label class="rcs-form__label" for="rcs-addr2"><?php esc_html_e('Address line 2 (optional)', 'alex-rose-2026'); ?></label>
						<input class="rcs-form__input" type="text" id="rcs-addr2" name="rcs_addr2" placeholder="<?php esc_attr_e('Apartment, suite, etc.', 'alex-rose-2026'); ?>">
					</div>
					<div class="rcs-form__grid rcs-form__grid--2">
						<div class="rcs-form__field">
							<label class="rcs-form__label" for="rcs-city">
								<?php esc_html_e('Town or city', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="rcs-form__input" type="text" id="rcs-city" name="rcs_city" placeholder="<?php esc_attr_e('Leeds', 'alex-rose-2026'); ?>" required data-rcs-required>
						</div>
						<div class="rcs-form__field">
							<label class="rcs-form__label" for="rcs-postcode">
								<?php esc_html_e('Postcode', 'alex-rose-2026'); ?><?php echo $required_mark; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</label>
							<input class="rcs-form__input" type="text" id="rcs-postcode" name="rcs_postcode" placeholder="LS1 1AB" required data-rcs-required>
						</div>
					</div>
				</div>
			</div>

			<button class="rcs-form__btn" type="submit" disabled data-rcs-submit>
				<?php esc_html_e('Request My Samples', 'alex-rose-2026'); ?>
			</button>

			<p class="rcs-form__error" data-rcs-error role="alert" hidden></p>
		</form>

		<div id="rcs-form-confirmation" class="rcs-form__confirmation" role="status" aria-live="polite" hidden>
			<p class="rcs-form__confirmation-kicker"><?php esc_html_e('Request Received', 'alex-rose-2026'); ?></p>
			<h2 class="rcs-form__confirmation-title"><?php esc_html_e('Your samples are on their way.', 'alex-rose-2026'); ?></h2>
			<p class="rcs-form__confirmation-body">
				<?php esc_html_e('Harold will personally review your request and post your cloth samples within one working day.', 'alex-rose-2026'); ?>
			</p>
		</div>
	</div>
</section>
