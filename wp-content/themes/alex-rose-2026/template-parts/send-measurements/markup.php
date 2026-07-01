<?php
/**
 * "Send Measurements" — order confirmation + measurement options.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$nav_options = array(
	array(
		'icon'  => 'package',
		'label' => __('Post Us a Jacket', 'alex-rose-2026'),
		'desc'  => __('Send a jacket that fits you well. We measure it and return it within 48 hours, free of charge.', 'alex-rose-2026'),
		'cta'   => __('Arrange a collection →', 'alex-rose-2026'),
		'href'  => '/post-your-jacket',
	),
	array(
		'icon'  => 'phone',
		'label' => __('Book a Call with Harold', 'alex-rose-2026'),
		'desc'  => __('Harold guides you through every measurement on a short video call, at a time that suits you.', 'alex-rose-2026'),
		'cta'   => __('Schedule a free call →', 'alex-rose-2026'),
		'href'  => '/schedule-a-call',
	),
);
?>
<main id="main" class="page-send-measurements" tabindex="-1">

	<div class="sm-confirm">
		<div class="sm-confirm__inner ar-container ar-container--4xl">
			<div class="sm-confirm__row">
				<div class="sm-confirm__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
				</div>
				<p class="sm-confirm__title"><?php esc_html_e('Order received. Your tailor will be in touch within one working day.', 'alex-rose-2026'); ?></p>
			</div>
			<p class="sm-confirm__sub"><?php esc_html_e('No payment taken yet. We confirm every detail with you first.', 'alex-rose-2026'); ?></p>
		</div>
	</div>

	<section class="sm-options">
		<div class="sm-options__inner ar-container ar-container--4xl">
			<p class="sm-options__kicker"><?php esc_html_e('Next step', 'alex-rose-2026'); ?></p>
			<h1 class="sm-options__title"><?php esc_html_e('Send Us Your Measurements.', 'alex-rose-2026'); ?></h1>
			<p class="sm-options__lead"><?php esc_html_e('Choose whichever option works best for you.', 'alex-rose-2026'); ?></p>

			<div class="sm-options__grid">
				<button
					type="button"
					class="sm-option"
					data-sm-toggle
					aria-expanded="false"
					aria-controls="sm-measure-panel"
				>
					<div class="sm-option__icon" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"></path><path d="m14.5 12.5 2-2"></path><path d="m11.5 9.5 2-2"></path><path d="m8.5 6.5 2-2"></path><path d="m17.5 15.5 2-2"></path></svg>
					</div>
					<p class="sm-option__label"><?php esc_html_e('Measure Yourself', 'alex-rose-2026'); ?></p>
					<p class="sm-option__desc"><?php esc_html_e('Follow our step-by-step guide and video, then fill in the form below.', 'alex-rose-2026'); ?></p>
					<span class="sm-option__cta sm-option__cta--active" data-sm-cta-active hidden><?php esc_html_e('Guide & form below ↓', 'alex-rose-2026'); ?></span>
				</button>

				<?php foreach ($nav_options as $opt) :
					$icon = isset($opt['icon']) ? (string) $opt['icon'] : '';
					?>
					<a class="sm-option" href="<?php echo esc_url(home_url($opt['href'])); ?>">
						<div class="sm-option__icon" aria-hidden="true">
							<?php if ($icon === 'package') : ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><path d="m7.5 4.27 9 5.15"></path></svg>
							<?php elseif ($icon === 'phone') : ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
							<?php endif; ?>
						</div>
						<p class="sm-option__label"><?php echo esc_html($opt['label']); ?></p>
						<p class="sm-option__desc"><?php echo esc_html($opt['desc']); ?></p>
						<span class="sm-option__cta"><?php echo esc_html($opt['cta']); ?></span>
					</a>
				<?php endforeach; ?>
			</div>

			<?php get_template_part('template-parts/send-measurements/measure-yourself'); ?>
			<?php get_template_part('template-parts/send-measurements/success'); ?>
		</div>
	</section>

</main>
