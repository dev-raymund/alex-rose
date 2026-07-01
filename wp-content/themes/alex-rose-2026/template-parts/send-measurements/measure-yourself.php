<?php
/**
 * "Send Measurements" — toggled self-measure guide + form.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$measurements = array(
	array(__('Chest', 'alex-rose-2026'),              __('Measure around the fullest part of your chest, keeping the tape horizontal under your arms.', 'alex-rose-2026')),
	array(__('Waist', 'alex-rose-2026'),              __('Measure around the narrowest part of your torso, usually just above the navel.', 'alex-rose-2026')),
	array(__('Hips', 'alex-rose-2026'),                __('Measure around the fullest part of your hips, about 8 inches below your natural waist.', 'alex-rose-2026')),
	array(__('Height', 'alex-rose-2026'),              __('Stand straight against a wall without shoes. Measure from floor to the top of your head.', 'alex-rose-2026')),
	array(__('Jacket sleeve', 'alex-rose-2026'),       __('From the top of the shoulder seam, along the outside of the arm to the wrist bone.', 'alex-rose-2026')),
	array(__('Jacket shoulder', 'alex-rose-2026'),     __('Measure across the back from shoulder seam to shoulder seam on an existing jacket.', 'alex-rose-2026')),
	array(__('Jacket back length', 'alex-rose-2026'),   __('From the centre back neck to the hem of an existing jacket you wear well.', 'alex-rose-2026')),
);
?>
<div class="sm-panel" id="sm-measure-panel" data-sm-panel hidden>
	<div class="sm-panel__video">
		<p class="sm-panel__kicker"><?php esc_html_e('Measurement video guide', 'alex-rose-2026'); ?></p>
		<button
			type="button"
			class="sm-panel__player"
			data-sm-video
			data-video-src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/06/measurements.mp4')); ?>"
			aria-label="<?php esc_attr_e('Play measurement video guide', 'alex-rose-2026'); ?>"
		>
			<span class="sm-panel__player-grid" aria-hidden="true"></span>
			<span class="sm-panel__player-play" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="0"><path d="M5 5a2 2 0 0 1 3.008-1.728l11.997 6.998a2 2 0 0 1 .003 3.458l-12 7A2 2 0 0 1 5 19z"></path></svg>
			</span>
			<span class="sm-panel__player-copy">
				<span class="sm-panel__player-title"><?php esc_html_e('How to Take Your Measurements', 'alex-rose-2026'); ?></span>
				<span class="sm-panel__player-meta"><?php esc_html_e('Video guide · 3 min', 'alex-rose-2026'); ?></span>
			</span>
			<span class="sm-panel__player-corner sm-panel__player-corner--tl" aria-hidden="true"></span>
			<span class="sm-panel__player-corner sm-panel__player-corner--tr" aria-hidden="true"></span>
			<span class="sm-panel__player-corner sm-panel__player-corner--bl" aria-hidden="true"></span>
			<span class="sm-panel__player-corner sm-panel__player-corner--br" aria-hidden="true"></span>
		</button>
	</div>

	<div class="sm-panel__guide">
		<p class="sm-panel__kicker"><?php esc_html_e('What to measure', 'alex-rose-2026'); ?></p>
		<div class="sm-panel__list">
			<?php foreach ($measurements as $i => $m) : ?>
				<div class="sm-panel__list-item">
					<span class="sm-panel__list-num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
					<div class="sm-panel__list-body">
						<p class="sm-panel__list-title"><?php echo esc_html($m[0]); ?></p>
						<p class="sm-panel__list-desc"><?php echo esc_html($m[1]); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="sm-panel__tape-note"><?php
			printf(
				wp_kses(
					/* translators: %s: link to request tape measure page */
					__('Don\'t have a tape measure? %s', 'alex-rose-2026'),
					array('a' => array('href' => array(), 'class' => array()))
				),
				'<a href="' . esc_url(home_url('/request-tape-measure/')) . '">' . esc_html__('Request one free of charge →', 'alex-rose-2026') . '</a>'
			);
		?></p>
	</div>

	<form
		class="sm-form"
		action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		method="post"
		data-sm-form
		data-schedule-url="<?php echo esc_url(home_url('/schedule-a-call/')); ?>"
		novalidate
	>
		<?php wp_nonce_field('sm_send_measurements', 'sm_nonce'); ?>
		<input type="hidden" name="action" value="sm_send_measurements">
		<?php alex_rose_2026_form_honeypot_field(); ?>

		<p class="sm-panel__kicker"><?php esc_html_e('Enter your measurements', 'alex-rose-2026'); ?></p>

		<div class="sm-form__grid sm-form__grid--3">
			<input type="text" id="sm-first" name="sm_first" class="sm-form__input" data-sm-required required placeholder="<?php esc_attr_e('First name', 'alex-rose-2026'); ?>">
			<input type="text" id="sm-last" name="sm_last" class="sm-form__input" data-sm-required required placeholder="<?php esc_attr_e('Last name', 'alex-rose-2026'); ?>">
			<input type="email" id="sm-email" name="sm_email" class="sm-form__input" data-sm-required required placeholder="<?php esc_attr_e('Email', 'alex-rose-2026'); ?>">
		</div>

		<div class="sm-form__units">
			<p class="sm-form__units-label"><?php esc_html_e('Measuring in', 'alex-rose-2026'); ?></p>
			<div class="sm-form__units-options" role="radiogroup" aria-label="<?php esc_attr_e('Measuring unit', 'alex-rose-2026'); ?>">
				<label class="sm-form__unit">
					<input type="radio" name="sm_unit" value="cm" class="sm-form__unit-input" checked data-sm-unit>
					<span class="sm-form__unit-dot" aria-hidden="true"></span>
					<span class="sm-form__unit-text"><?php esc_html_e('Centimetres', 'alex-rose-2026'); ?></span>
				</label>
				<label class="sm-form__unit">
					<input type="radio" name="sm_unit" value="in" class="sm-form__unit-input" data-sm-unit>
					<span class="sm-form__unit-dot" aria-hidden="true"></span>
					<span class="sm-form__unit-text"><?php esc_html_e('Inches', 'alex-rose-2026'); ?></span>
				</label>
			</div>
		</div>

		<div class="sm-form__group">
			<p class="sm-panel__kicker sm-panel__kicker--spaced"><?php esc_html_e('Body measurements', 'alex-rose-2026'); ?></p>
			<div class="sm-form__grid sm-form__grid--5">
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-chest" data-sm-label-cm="<?php esc_attr_e('Chest (cm)', 'alex-rose-2026'); ?>" data-sm-label-in="<?php esc_attr_e('Chest (in)', 'alex-rose-2026'); ?>"><?php esc_html_e('Chest (cm)', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-chest" name="sm_chest" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-waist" data-sm-label-cm="<?php esc_attr_e('Waist (cm)', 'alex-rose-2026'); ?>" data-sm-label-in="<?php esc_attr_e('Waist (in)', 'alex-rose-2026'); ?>"><?php esc_html_e('Waist (cm)', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-waist" name="sm_waist" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-hips" data-sm-label-cm="<?php esc_attr_e('Hips (cm)', 'alex-rose-2026'); ?>" data-sm-label-in="<?php esc_attr_e('Hips (in)', 'alex-rose-2026'); ?>"><?php esc_html_e('Hips (cm)', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-hips" name="sm_hips" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-height" data-sm-label-cm="<?php esc_attr_e('Height (cm)', 'alex-rose-2026'); ?>" data-sm-label-in="<?php esc_attr_e('Height (in)', 'alex-rose-2026'); ?>"><?php esc_html_e('Height (cm)', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-height" name="sm_height" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-weight" data-sm-label-cm="<?php esc_attr_e('Weight (kg)', 'alex-rose-2026'); ?>" data-sm-label-in="<?php esc_attr_e('Weight (lbs)', 'alex-rose-2026'); ?>"><?php esc_html_e('Weight (kg)', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-weight" name="sm_weight" class="sm-form__input" inputmode="decimal">
				</div>
			</div>
		</div>

		<div class="sm-form__group">
			<p class="sm-panel__kicker sm-panel__kicker--spaced"><?php esc_html_e('Jacket measurements', 'alex-rose-2026'); ?></p>
			<p class="sm-form__group-note"><?php esc_html_e('From an existing jacket that fits you well.', 'alex-rose-2026'); ?></p>
			<div class="sm-form__grid sm-form__grid--4">
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-sleeve"><?php esc_html_e('Sleeve', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-sleeve" name="sm_sleeve" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-shoulder"><?php esc_html_e('Shoulder', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-shoulder" name="sm_shoulder" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-back-length"><?php esc_html_e('Back length', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-back-length" name="sm_back_length" class="sm-form__input" inputmode="decimal">
				</div>
				<div class="sm-form__field">
					<label class="sm-form__field-label" for="sm-label-size"><?php esc_html_e('Label size', 'alex-rose-2026'); ?></label>
					<input type="text" id="sm-label-size" name="sm_label_size" class="sm-form__input">
				</div>
			</div>
		</div>

		<p class="sm-form__error" data-sm-error role="alert" hidden></p>

		<button type="submit" class="sm-form__btn" data-sm-submit disabled><?php esc_html_e('Send Measurements & Book a Call →', 'alex-rose-2026'); ?></button>
		<p class="sm-form__note"><?php esc_html_e('After submitting, you can book a short call with Harold below to confirm every detail.', 'alex-rose-2026'); ?></p>
	</form>

	<div class="sm-offer">
		<div class="sm-offer__stripe" aria-hidden="true"></div>
		<div class="sm-offer__inner">
			<div class="sm-offer__main">
				<p class="sm-offer__eyebrow"><?php esc_html_e('Exclusive offer for our customers', 'alex-rose-2026'); ?></p>
				<h3 class="sm-offer__headline"><?php esc_html_e('5% off your next order.', 'alex-rose-2026'); ?><br><span class="sm-offer__accent"><?php esc_html_e('Just share your thoughts.', 'alex-rose-2026'); ?></span></h3>
				<p class="sm-offer__copy"><?php esc_html_e('Harold reads every response personally. Tell us what worked, what did not, and what you would change. We will send your 5% discount code straight back.', 'alex-rose-2026'); ?></p>
			</div>
			<div class="sm-offer__aside">
				<ul class="sm-offer__list">
					<?php foreach (array(
						__('Around five minutes', 'alex-rose-2026'),
						__('No obligation to purchase', 'alex-rose-2026'),
						__('5% code sent on submission', 'alex-rose-2026'),
					) as $sm_point) : ?>
						<li class="sm-offer__item">
							<svg class="sm-offer__star" xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="#C8A96A" stroke="#C8A96A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
							<span><?php echo esc_html($sm_point); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<a class="sm-offer__btn" href="<?php echo esc_url(home_url('/feedback/')); ?>">
					<?php esc_html_e('Start feedback form', 'alex-rose-2026'); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
				</a>
				<p class="sm-offer__fine"><?php esc_html_e('Code valid on any jacket order. No expiry.', 'alex-rose-2026'); ?></p>
			</div>
		</div>
	</div>
</div>
