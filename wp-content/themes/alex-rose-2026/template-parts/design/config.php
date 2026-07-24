<?php
/**
 * "Design Your Jacket" — right column tabs + accordion sections + footer.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$design_tabs = array(
	array('key' => 'design',        'label' => __('Design', 'alex-rose-2026')),
	array('key' => 'preview',       'label' => __('Preview', 'alex-rose-2026')),
	array('key' => 'reserve',       'label' => __('Reserve', 'alex-rose-2026')),
	array('key' => 'measurements',  'label' => __('Measurements', 'alex-rose-2026')),
	array('key' => 'consultation',  'label' => __('Consultation', 'alex-rose-2026')),
);

$design_occasions = array(
	array(
		'key'   => 'business',
		'title' => __('Business & Smart Casual', 'alex-rose-2026'),
		'sub'   => __('Office · Meetings · Travel', 'alex-rose-2026'),
		'image' => '2026/05/lifestyle-4.jpg',
	),
	array(
		'key'   => 'evening',
		'title' => __('Evening & Statement', 'alex-rose-2026'),
		'sub'   => __('Events · Occasions · Celebrations', 'alex-rose-2026'),
		'image' => '2026/05/lifestyle-6.jpg',
	),
	array(
		'key'   => 'country',
		'title' => __('Country & Heritage', 'alex-rose-2026'),
		'sub'   => __('Tweed · Heritage · Outdoors', 'alex-rose-2026'),
		'image' => '2026/05/lifestyle-9.jpg',
	),
	array(
		'key'   => 'seasonal',
		'title' => __('Seasonal', 'alex-rose-2026'),
		'sub'   => __('Linen · Summer · Garden Parties', 'alex-rose-2026'),
		'image' => '2026/05/lifestyle-5.jpg',
	),
);

$design_selected_occasion = 'evening';
?>
<section class="design-config" aria-label="<?php esc_attr_e('Configurator', 'alex-rose-2026'); ?>">
	<div class="design-config__tabs" role="tablist" aria-label="<?php esc_attr_e('Configurator stages', 'alex-rose-2026'); ?>">
		<?php foreach ($design_tabs as $i => $t) :
			$is_active = ($t['key'] === 'design');
			?>
			<button
				type="button"
				class="design-config__tab<?php echo $is_active ? ' is-active' : ''; ?>"
				role="tab"
				id="design-tab-<?php echo esc_attr($t['key']); ?>"
				aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				aria-disabled="<?php echo $is_active ? 'false' : 'true'; ?>"
				data-design-tab="<?php echo esc_attr($t['key']); ?>"
				tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
			>
				<?php echo esc_html($t['label']); ?>
				<span class="design-config__tab-bar" aria-hidden="true"></span>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="design-config__body">
		<header class="design-config__intro">
			<p class="design-config__kicker"><?php esc_html_e('Made-to-Measure Jacket', 'alex-rose-2026'); ?></p>
			<h1 class="design-config__title"><?php esc_html_e('Design Your Jacket.', 'alex-rose-2026'); ?></h1>
			<p class="design-config__note"><?php esc_html_e('Your progress is saved automatically.', 'alex-rose-2026'); ?></p>
		</header>

		<div class="design-acc" data-design-accordion>
			<div class="design-acc__item is-open" data-design-acc-item>
				<button type="button" class="design-acc__head" data-design-acc-toggle aria-expanded="true" aria-controls="design-acc-occasion">
					<span class="design-acc__check" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
					</span>
					<span class="design-acc__head-body">
						<span class="design-acc__head-label"><?php esc_html_e('Occasion', 'alex-rose-2026'); ?></span>
					</span>
					<svg class="design-acc__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
				</button>
				<div class="design-acc__panel" id="design-acc-occasion" data-design-acc-panel>
					<div class="design-acc__panel-inner">
						<div class="design-occasions" role="radiogroup" aria-label="<?php esc_attr_e('Choose an occasion', 'alex-rose-2026'); ?>">
							<?php foreach ($design_occasions as $occ) :
								$is_selected = ($occ['key'] === $design_selected_occasion);
								?>
								<button
									type="button"
									role="radio"
									aria-checked="<?php echo $is_selected ? 'true' : 'false'; ?>"
									class="design-occasion<?php echo $is_selected ? ' is-selected' : ''; ?>"
									data-design-occasion="<?php echo esc_attr($occ['key']); ?>"
								>
									<img class="design-occasion__img" src="<?php echo esc_url(alex_rose_2026_uploads_url($occ['image'])); ?>" alt="<?php echo esc_attr($occ['title']); ?>" loading="lazy" width="200" height="120">
									<span class="design-occasion__shade" aria-hidden="true"></span>
									<span class="design-occasion__mark" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
									</span>
									<span class="design-occasion__body">
										<span class="design-occasion__title"><?php echo esc_html($occ['title']); ?></span>
										<span class="design-occasion__sub"><?php echo esc_html($occ['sub']); ?></span>
									</span>
								</button>
							<?php endforeach; ?>
						</div>
						<button type="button" class="design-acc__continue" data-design-acc-next>
							<?php esc_html_e('Continue', 'alex-rose-2026'); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
						</button>
					</div>
				</div>
			</div>

			<div class="design-acc__item" data-design-acc-item>
				<button type="button" class="design-acc__head" data-design-acc-toggle aria-expanded="false" aria-controls="design-acc-cloth">
					<span class="design-acc__check" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
					</span>
					<span class="design-acc__head-body">
						<span class="design-acc__head-label"><?php esc_html_e('Cloth Collection', 'alex-rose-2026'); ?></span>
						<span class="design-acc__head-value"><?php esc_html_e('English Blazer Collection', 'alex-rose-2026'); ?></span>
					</span>
					<svg class="design-acc__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
				</button>
				<div class="design-acc__panel" id="design-acc-cloth" data-design-acc-panel hidden></div>
			</div>

			<div class="design-acc__item" data-design-acc-item>
				<button type="button" class="design-acc__head" data-design-acc-toggle aria-expanded="false" aria-controls="design-acc-style">
					<span class="design-acc__check" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
					</span>
					<span class="design-acc__head-body">
						<span class="design-acc__head-label"><?php esc_html_e('Style Details', 'alex-rose-2026'); ?></span>
						<span class="design-acc__head-value"><?php esc_html_e('Peak Lapel · Single Breasted', 'alex-rose-2026'); ?></span>
					</span>
					<svg class="design-acc__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
				</button>
				<div class="design-acc__panel" id="design-acc-style" data-design-acc-panel hidden></div>
			</div>

			<div class="design-acc__item" data-design-acc-item>
				<button type="button" class="design-acc__head" data-design-acc-toggle aria-expanded="false" aria-controls="design-acc-fit">
					<span class="design-acc__check" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
					</span>
					<span class="design-acc__head-body">
						<span class="design-acc__head-label"><?php esc_html_e('Fit', 'alex-rose-2026'); ?></span>
						<span class="design-acc__head-value"><?php esc_html_e('Classic', 'alex-rose-2026'); ?></span>
					</span>
					<svg class="design-acc__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
				</button>
				<div class="design-acc__panel" id="design-acc-fit" data-design-acc-panel hidden></div>
			</div>
		</div>

		<div class="design-mobile-bar">
			<div>
				<p class="design-mobile-bar__kicker"><?php esc_html_e('Starting From', 'alex-rose-2026'); ?></p>
				<p class="design-mobile-bar__price">£595</p>
			</div>
			<a class="design-mobile-bar__cta" href="<?php echo esc_url(home_url('/design#preview')); ?>">
				<?php esc_html_e('Preview', 'alex-rose-2026'); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
			</a>
		</div>
	</div>

	<div class="design-config__foot">
		<div class="design-config__foot-meta">
			<img class="design-config__foot-thumb" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/cloth-english-blazer.jpg')); ?>" alt="" loading="lazy" width="40" height="40">
			<div>
				<p class="design-config__foot-kicker"><?php esc_html_e('Starting From', 'alex-rose-2026'); ?></p>
				<p class="design-config__foot-price">£595</p>
			</div>
		</div>
		<a class="design-config__foot-cta" href="<?php echo esc_url(home_url('/design#preview')); ?>">
			<?php esc_html_e('Preview Your Jacket', 'alex-rose-2026'); ?>
			<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
		</a>
	</div>
</section>
<script>
(function () {
	var items = document.querySelectorAll('[data-design-acc-item]');
	if (!items.length) return;

	function setOpen(item, open) {
		var head = item.querySelector('[data-design-acc-toggle]');
		var panel = item.querySelector('[data-design-acc-panel]');
		if (!head || !panel) return;
		item.classList.toggle('is-open', open);
		head.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (open) {
			panel.removeAttribute('hidden');
		} else {
			panel.setAttribute('hidden', '');
		}
	}

	items.forEach(function (item) {
		var head = item.querySelector('[data-design-acc-toggle]');
		var next = item.querySelector('[data-design-acc-next]');
		if (head) {
			head.addEventListener('click', function () {
				var open = head.getAttribute('aria-expanded') === 'true';
				setOpen(item, !open);
			});
		}
		if (next) {
			next.addEventListener('click', function () {
				setOpen(item, false);
				var sibling = item.nextElementSibling;
				if (sibling && sibling.matches('[data-design-acc-item]')) {
					setOpen(sibling, true);
					var head2 = sibling.querySelector('[data-design-acc-toggle]');
					if (head2) head2.focus();
				}
			});
		}
	});

	var occasions = document.querySelectorAll('[data-design-occasion]');
	occasions.forEach(function (btn) {
		btn.addEventListener('click', function () {
			occasions.forEach(function (o) {
				o.classList.remove('is-selected');
				o.setAttribute('aria-checked', 'false');
			});
			btn.classList.add('is-selected');
			btn.setAttribute('aria-checked', 'true');
		});
	});
})();
</script>
