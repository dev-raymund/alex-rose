<?php
/**
 * "How It Works" — tabs + active step panel.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$hiw_steps = array(
	array(
		'num'   => '01',
		'short' => __('Choose Your Cloth', 'alex-rose-2026'),
		'title' => __('Choose Your Cloth', 'alex-rose-2026'),
		'desc'  => __('Browse our collection of British cloths and order up to three free samples before you decide.', 'alex-rose-2026'),
		'image' => '2026/05/cloth-jacket-4k.webp',
		'alt'   => __('British cloth options', 'alex-rose-2026'),
		'cta'   => __('Browse Cloths', 'alex-rose-2026'),
		'href'  => '/cloths',
	),
	array(
		'num'   => '02',
		'short' => __('Design Your Jacket', 'alex-rose-2026'),
		'title' => __('Design Your Jacket', 'alex-rose-2026'),
		'desc'  => __('Choose your lapel, lining, pockets, buttons and vents online. We monogram your name into the lining.', 'alex-rose-2026'),
		'image' => '2026/05/design-jacket.webp',
		'alt'   => __('Jacket design configurator', 'alex-rose-2026'),
		'cta'   => __('Start Designing', 'alex-rose-2026'),
		'href'  => '/design',
	),
	array(
		'num'   => '03',
		'short' => __('Share Your Measurements', 'alex-rose-2026'),
		'title' => __('Share Your Measurements', 'alex-rose-2026'),
		'desc'  => __('Measure at home with our guide, post us a jacket you already own, or book a free call and we will do it together.', 'alex-rose-2026'),
		'image' => '2026/05/send-measurements-step.jpg',
		'alt'   => __('Jacket measurements', 'alex-rose-2026'),
		'measure_options' => array(
			array(
				'icon'  => 'ruler',
				'label' => __('Measure yourself', 'alex-rose-2026'),
				'sub'   => __('With our simple home guide', 'alex-rose-2026'),
				'href'  => '/send-measurements',
			),
			array(
				'icon'  => 'package',
				'label' => __('Post us a jacket', 'alex-rose-2026'),
				'sub'   => __('We measure it, return within 48 hrs', 'alex-rose-2026'),
				'href'  => '/post-your-jacket',
			),
			array(
				'icon'  => 'phone',
				'label' => __('Book a free call', 'alex-rose-2026'),
				'sub'   => __('Harold guides you through it', 'alex-rose-2026'),
				'href'  => '/schedule-a-call',
			),
		),
	),
	array(
		'num'   => '04',
		'short' => __('Meet Your Tailor', 'alex-rose-2026'),
		'title' => __('Meet Your Tailor', 'alex-rose-2026'),
		'desc'  => __('Harold reviews your order on a short video call before a single thread is cut. No surprises.', 'alex-rose-2026'),
		'image' => '2026/05/harold.jpg',
		'alt'   => __('Harold Rose, master tailor', 'alex-rose-2026'),
		'cta'   => __('Book a Consultation', 'alex-rose-2026'),
		'href'  => '/schedule-a-call',
	),
	array(
		'num'   => '05',
		'short' => __('Your Jacket Arrives', 'alex-rose-2026'),
		'title' => __('Your Jacket Arrives', 'alex-rose-2026'),
		'desc'  => __('Your jacket is crafted to your exact measurements and delivered to your door, made exactly as you designed it.', 'alex-rose-2026'),
		'image' => '2026/05/jackets.webp',
		'alt'   => __('Finished jacket', 'alex-rose-2026'),
		'cta'   => __('Design Your Jacket', 'alex-rose-2026'),
		'href'  => '/design',
	),
);

$hiw_active = 0;
$hiw_count  = count($hiw_steps);
?>
<section class="hiw-steps">
	<div class="hiw-tabs">
		<div class="hiw-tabs__scroll">
			<div class="hiw-tabs__inner">
				<div class="hiw-tabs__grid" role="tablist" aria-label="<?php esc_attr_e('Process steps', 'alex-rose-2026'); ?>">
					<?php foreach ($hiw_steps as $i => $s) :
						$is_active = ($i === $hiw_active);
						?>
						<button
							type="button"
							class="hiw-tab<?php echo $is_active ? ' is-active' : ''; ?>"
							role="tab"
							id="hiw-tab-<?php echo (int) $i; ?>"
							aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							aria-controls="hiw-panel-<?php echo (int) $i; ?>"
							data-hiw-tab="<?php echo (int) $i; ?>"
							tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
						>
							<span class="hiw-tab__num"><?php echo esc_html($s['num']); ?></span>
							<span class="hiw-tab__label"><?php echo esc_html($s['short']); ?></span>
							<span class="hiw-tab__bar" aria-hidden="true"></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="hiw-panels">
		<div class="hiw-panels__inner">
			<?php foreach ($hiw_steps as $i => $s) :
				$is_active = ($i === $hiw_active);
				$is_first  = ($i === 0);
				$is_last   = ($i === $hiw_count - 1);
				?>
				<div
					class="hiw-panel<?php echo $is_active ? ' is-active' : ''; ?>"
					id="hiw-panel-<?php echo (int) $i; ?>"
					role="tabpanel"
					aria-labelledby="hiw-tab-<?php echo (int) $i; ?>"
					data-hiw-panel="<?php echo (int) $i; ?>"
					<?php echo $is_active ? '' : 'hidden'; ?>
				>
					<div class="hiw-panel__media">
						<img src="<?php echo esc_url(alex_rose_2026_uploads_url($s['image'])); ?>" alt="<?php echo esc_attr($s['alt'] ?? $s['title']); ?>" loading="lazy" width="1000" height="800">
					</div>
					<div class="hiw-panel__body">
						<p class="hiw-panel__kicker"><?php
							/* translators: %s: step number, e.g. 01. */
							printf(esc_html__('Step %s', 'alex-rose-2026'), esc_html($s['num']));
						?></p>
						<h2 class="hiw-panel__title"><?php echo esc_html($s['title']); ?></h2>
						<p class="hiw-panel__desc"><?php echo esc_html($s['desc']); ?></p>

						<?php if (! empty($s['measure_options']) && is_array($s['measure_options'])) : ?>
							<div class="hiw-measure-options">
								<?php foreach ($s['measure_options'] as $opt) :
									$icon = isset($opt['icon']) ? (string) $opt['icon'] : '';
									?>
									<a class="hiw-measure-options__link" href="<?php echo esc_url(home_url($opt['href'])); ?>">
										<div class="hiw-measure-option">
											<div class="hiw-measure-option__icon" aria-hidden="true">
												<?php if ($icon === 'ruler') : ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"></path><path d="m14.5 12.5 2-2"></path><path d="m11.5 9.5 2-2"></path><path d="m8.5 6.5 2-2"></path><path d="m17.5 15.5 2-2"></path></svg>
												<?php elseif ($icon === 'package') : ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><path d="m7.5 4.27 9 5.15"></path></svg>
												<?php elseif ($icon === 'phone') : ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
												<?php endif; ?>
											</div>
											<div class="hiw-measure-option__body">
												<p class="hiw-measure-option__label"><?php echo esc_html($opt['label']); ?></p>
												<p class="hiw-measure-option__sub"><?php echo esc_html($opt['sub']); ?></p>
											</div>
											<svg class="hiw-measure-option__chevron" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
										</div>
									</a>
								<?php endforeach; ?>
							</div>
						<?php elseif (! empty($s['cta']) && ! empty($s['href'])) : ?>
							<a class="hiw-panel__cta" href="<?php echo esc_url(home_url($s['href'])); ?>"><?php echo esc_html($s['cta']); ?></a>
						<?php endif; ?>

						<div class="hiw-panel__foot">
							<button type="button" class="hiw-panel__back" data-hiw-prev <?php echo $is_first ? 'hidden' : ''; ?>>
								<span aria-hidden="true">&larr;</span> <?php esc_html_e('Back', 'alex-rose-2026'); ?>
							</button>
							<button type="button" class="hiw-panel__next" data-hiw-next <?php echo $is_last ? 'hidden' : ''; ?>>
								<?php esc_html_e('Next Step', 'alex-rose-2026'); ?> <span aria-hidden="true">&rarr;</span>
							</button>
							<div class="hiw-panel__dots" role="presentation">
								<?php for ($d = 0; $d < $hiw_count; $d++) :
									$dot_active = ($d === $i);
									?>
									<button
										type="button"
										class="hiw-panel__dot<?php echo $dot_active ? ' is-active' : ''; ?>"
										data-hiw-tab="<?php echo (int) $d; ?>"
										aria-label="<?php
											/* translators: %s: step number. */
											echo esc_attr(sprintf(__('Go to step %s', 'alex-rose-2026'), $hiw_steps[$d]['num']));
										?>"
									></button>
								<?php endfor; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
