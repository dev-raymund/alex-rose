<?php
/**
 * Design page mix-and-match configurator shell.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$design_sections = array(
	array('key' => 'fabrics', 'label' => __('Fabrics', 'alex-rose-2026'), 'icon' => 'grid', 'subtitle' => __('Choose your cloth collection', 'alex-rose-2026')),
	array('key' => 'lining', 'label' => __('Lining', 'alex-rose-2026'), 'icon' => 'lining', 'subtitle' => __('Select your lining colour', 'alex-rose-2026')),
	array('key' => 'buttons', 'label' => __('Buttons', 'alex-rose-2026'), 'icon' => 'buttons', 'subtitle' => __('Select button finish', 'alex-rose-2026')),
	array('key' => 'buttoning', 'label' => __('Buttoning', 'alex-rose-2026'), 'icon' => 'buttoning', 'subtitle' => __('Choose button stance', 'alex-rose-2026')),
	array('key' => 'pockets', 'label' => __('Pockets', 'alex-rose-2026'), 'icon' => 'pockets', 'subtitle' => __('Select pocket style', 'alex-rose-2026')),
	array('key' => 'vents', 'label' => __('Vents', 'alex-rose-2026'), 'icon' => 'vents', 'subtitle' => __('Choose vent style', 'alex-rose-2026')),
	array('key' => 'monogram', 'label' => __('Monogram', 'alex-rose-2026'), 'icon' => 'monogram', 'subtitle' => __('Add personal monogram', 'alex-rose-2026')),
);

$design_collections = array(
	array('title' => __('English Riviera Collection', 'alex-rose-2026'), 'folder' => 'english-riviera', 'items' => array('Brixham', 'Dartmouth', 'Dawlish', 'Devon', 'Exeter', 'Exmouth', 'Falmouth', 'Kingsbridge', 'Newquay', 'Paignton', 'Salcombe', 'Sidmouth', 'Teignmouth', 'Tiverton', 'Topsham', 'Torbay', 'Torquay', 'Totnes')),
	array('title' => __('Cotswold Collection', 'alex-rose-2026'), 'folder' => 'cotswold', 'items' => array('Blockley', 'Broadway', 'Burford', 'Cheltenham', 'Cirencester', 'Fairford', 'Painswick', 'Stanton', 'Tetbury', 'Winchcombe', 'Witney', 'Woodstock')),
	array('title' => __('International Blazer Collection', 'alex-rose-2026'), 'folder' => 'travel-blazer', 'items' => array('Amsterdam', 'Athens', 'Berlin', 'Geneva', 'Havana', 'Lisbon', 'London', 'Madrid', 'Miami', 'Monaco', 'Oslo', 'Ottawa', 'Paris', 'Prague', 'Rome', 'Stockholm', 'Tokyo', 'Vienna', 'Washington')),
	array('title' => __('Heritage Tweed Collection', 'alex-rose-2026'), 'folder' => 'heritage-tweed', 'items' => array('Allerton', 'Aysgarth', 'Bingley', 'Birstal', 'Masham', 'Roundhay', 'Saddleworth', 'Seacroft', 'Weeton', 'Whitwirk')),
	array('title' => __('Harris Tweed Collection', 'alex-rose-2026'), 'folder' => 'harris-tweed', 'items' => array('Achmore', 'Barra', 'Callanish', 'Cromore', 'Lewis', 'Tarbet')),
	array('title' => __('Moorland Tweed Collection', 'alex-rose-2026'), 'folder' => 'moorland-tweed', 'items' => array('Batley', 'Skipton', 'Whitby', 'Yeadon')),
	array('title' => __('Yorkshire Tweed Collection', 'alex-rose-2026'), 'folder' => 'yorkshire-tweed', 'items' => array('Arncliffe', 'Bowes', 'Easingwold', 'Gisburn', 'Helmsley', 'Kirby', 'Kirkburn', 'Nidderdale', 'Thirsk', 'Wharfe')),
	array('title' => __('English Blazer Collection', 'alex-rose-2026'), 'folder' => 'english-blazer', 'items' => array('Black', 'Navy')),
);

$design_swatch_palette = array(
	array('rgb(38,42,47)', 'rgb(95,102,114)'),
	array('rgb(44,49,58)', 'rgb(119,95,86)'),
	array('rgb(75,48,42)', 'rgb(131,110,83)'),
	array('rgb(49,63,78)', 'rgb(130,145,156)'),
	array('rgb(61,61,61)', 'rgb(122,122,122)'),
	array('rgb(91,48,61)', 'rgb(166,114,119)'),
	array('rgb(74,86,101)', 'rgb(166,177,185)'),
	array('rgb(66,63,55)', 'rgb(140,131,105)'),
);

$lining_plain_colours = array(
	array('name' => 'White', 'color' => '#f2ede6'),
	array('name' => 'Light Gold', 'color' => '#c9a84c'),
	array('name' => 'Yellow', 'color' => '#d4b800'),
	array('name' => 'Orange', 'color' => '#d96212'),
	array('name' => 'Bright Red', 'color' => '#cc1a1a'),
	array('name' => 'Dark Red', 'color' => '#8c1515'),
	array('name' => 'Light Pink', 'color' => '#e07aae'),
	array('name' => 'Violet', 'color' => '#7b38a0'),
	array('name' => 'Dark Violet', 'color' => '#541875'),
	array('name' => 'Light Lilac', 'color' => '#c298ce'),
	array('name' => 'Light Green', 'color' => '#48b860'),
	array('name' => 'Light Beige', 'color' => '#cabb98'),
	array('name' => 'Dark Beige', 'color' => '#a89070'),
	array('name' => 'Light Brown', 'color' => '#8c6040'),
	array('name' => 'Mid Brown', 'color' => '#6a4228'),
	array('name' => 'Dark Brown', 'color' => '#3c200e'),
	array('name' => 'Light Grey', 'color' => '#c2c0bc'),
	array('name' => 'Dark Grey', 'color' => '#5e5e5e'),
	array('name' => 'Blue Grey', 'color' => '#8caac2'),
	array('name' => 'Dk Blue Grey', 'color' => '#4a6880'),
	array('name' => 'Bright Blue', 'color' => '#1a5fbf'),
	array('name' => 'Navy Blue', 'color' => '#1a3880'),
	array('name' => 'Dark Navy', 'color' => '#0d1a4a'),
);

$lining_patterns = array(
	'Midnight Blue',
	'Black',
	'Mafia',
	'Stamps',
	'Blue Map',
	'Night Sky',
	'Black Red Paisley',
	'Blue Red Paisley',
	'Beige Red Paisley',
	'Silver Blue Paisley',
	'Blue Gold Paisley',
	'Beige World Map',
	'Red World Map',
	'Bordeaux World Map',
	'Blue World Map',
	'Grey World Map',
	'Red Skulls',
	'Blue Skulls',
	'Black Skulls',
	'Green Violet Bugs',
	'Green Violet Butterfly',
	'Casino',
	'Animals',
	'Flags',
	'Birds',
	'Rock Skulls',
	'Skulls & Roses',
);

function alex_rose_2026_design_icon_svg($icon) {
	switch ($icon) {
		case 'grid':
			return '<svg viewBox="0 0 48 48" fill="none"><rect x="4" y="4" width="40" height="40" rx="3" fill="rgba(200,169,106,0.15)"></rect><line x1="4" y1="12" x2="44" y2="12" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="20" x2="44" y2="20" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="28" x2="44" y2="28" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="36" x2="44" y2="36" stroke="#C8A96A" stroke-width="1.2"></line><line x1="12" y1="4" x2="12" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="20" y1="4" x2="20" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="28" y1="4" x2="28" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="36" y1="4" x2="36" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line></svg>';
		case 'lining':
			return '<svg viewBox="0 0 48 48" fill="none"><circle cx="16" cy="16" r="9" fill="rgba(200,169,106,0.7)"></circle><circle cx="32" cy="16" r="9" fill="rgba(26,58,90,0.7)"></circle><circle cx="16" cy="32" r="9" fill="rgba(90,26,26,0.7)"></circle><circle cx="32" cy="32" r="9" fill="rgba(42,42,42,0.7)"></circle></svg>';
		case 'buttons':
			return '<svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="20" r="8" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.12)"></circle><circle cx="24" cy="20" r="4.5" stroke="#C8A96A" stroke-width="0.9" fill="none"></circle><circle cx="22" cy="18.5" r="1" fill="#C8A96A"></circle><circle cx="26" cy="18.5" r="1" fill="#C8A96A"></circle><circle cx="22" cy="21.5" r="1" fill="#C8A96A"></circle><circle cx="26" cy="21.5" r="1" fill="#C8A96A"></circle><line x1="24" y1="30" x2="24" y2="40" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line></svg>';
		case 'buttoning':
			return '<svg viewBox="0 0 48 48" fill="none"><path d="M14 6 L14 42 L24 38 L34 42 L34 6 Z" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.08)"></path><line x1="24" y1="14" x2="24" y2="38" stroke="#C8A96A" stroke-width="0.9"></line><circle cx="24" cy="20" r="2.5" fill="rgba(200,169,106,0.5)" stroke="#C8A96A" stroke-width="1"></circle><circle cx="24" cy="29" r="2.5" fill="rgba(200,169,106,0.5)" stroke="#C8A96A" stroke-width="1"></circle></svg>';
		case 'pockets':
			return '<svg viewBox="0 0 48 48" fill="none"><rect x="8" y="24" width="32" height="18" rx="2" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.08)"></rect><rect x="8" y="18" width="32" height="8" rx="1.5" stroke="#C8A96A" stroke-width="1.1" fill="rgba(200,169,106,0.12)"></rect><line x1="22" y1="18" x2="28" y2="10" stroke="#C8A96A" stroke-width="1"></line></svg>';
		case 'vents':
			return '<svg viewBox="0 0 48 48" fill="none"><path d="M10 8 L10 40 L24 34 L38 40 L38 8 Z" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.08)"></path><path d="M17 28 L17 40" stroke="#C8A96A" stroke-width="1.5" stroke-dasharray="2 2"></path><path d="M31 28 L31 40" stroke="#C8A96A" stroke-width="1.5" stroke-dasharray="2 2"></path></svg>';
		case 'monogram':
			return '<svg viewBox="0 0 48 48" fill="none"><path d="M10 36 C14 20, 18 14, 24 14 C30 14, 34 20, 38 36" stroke="#C8A96A" stroke-width="1.3" fill="none" stroke-linecap="round"></path><path d="M17 28 C20 22, 24 20, 28 28" stroke="#C8A96A" stroke-width="1.1" fill="none" stroke-linecap="round"></path><line x1="8" y1="38" x2="40" y2="38" stroke="rgba(200,169,106,0.35)" stroke-width="1"></line></svg>';
		default:
			return '';
	}
}
?>
<section class="design-mix" aria-label="<?php esc_attr_e('Design configurator', 'alex-rose-2026'); ?>">
	<div class="design-mix__mobile-preview">
		<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/collection-hero-riviera.jpg')); ?>" alt="<?php esc_attr_e('Jacket preview', 'alex-rose-2026'); ?>" loading="eager" width="900" height="1280">
		<div class="design-mix__mobile-overlay"></div>
		<div class="design-mix__mobile-meta">
			<p><?php esc_html_e('Alex Rose · Your Design', 'alex-rose-2026'); ?></p>
			<h2 data-design-selected-name><?php esc_html_e('Exeter', 'alex-rose-2026'); ?></h2>
		</div>
		<div class="design-mix__mobile-price">
			<span><?php esc_html_e('From', 'alex-rose-2026'); ?></span>
			<strong>£595</strong>
		</div>
		<div class="design-mix__mobile-controls">
			<div class="design-mix__mobile-sections">
				<?php foreach ($design_sections as $index => $section) : ?>
					<button type="button" class="design-mix__mobile-quick-btn<?php echo 0 === $index ? ' is-active' : ''; ?>" data-design-section-btn="<?php echo esc_attr($section['key']); ?>" data-design-subtitle="<?php echo esc_attr($section['subtitle']); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<span class="design-mix__mobile-icon" aria-hidden="true"><?php echo alex_rose_2026_design_icon_svg($section['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span><?php echo esc_html($section['label']); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="design-mix__mobile-preview-btn"><?php esc_html_e('Preview My Jacket', 'alex-rose-2026'); ?> <span aria-hidden="true">›</span></button>
		</div>
	</div>

	<div class="design-mix__workspace">
		<aside class="design-mix__sections" aria-label="<?php esc_attr_e('Mix and match sections', 'alex-rose-2026'); ?>">
			<p class="design-mix__step"><?php esc_html_e('Step 1', 'alex-rose-2026'); ?><br><?php esc_html_e('Design', 'alex-rose-2026'); ?></p>
			<?php foreach ($design_sections as $index => $section) : ?>
				<button type="button" class="design-mix__section-btn<?php echo 0 === $index ? ' is-active' : ''; ?>" data-design-section-btn="<?php echo esc_attr($section['key']); ?>" data-design-subtitle="<?php echo esc_attr($section['subtitle']); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
					<span class="design-mix__section-icon" aria-hidden="true"><?php echo alex_rose_2026_design_icon_svg($section['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="design-mix__section-label"><?php echo esc_html($section['label']); ?></span>
				</button>
			<?php endforeach; ?>
		</aside>

		<div class="design-mix__options">
			<header class="design-mix__head">
				<h1 data-design-section-title><?php esc_html_e('Fabrics', 'alex-rose-2026'); ?></h1>
				<p data-design-section-subtitle><?php esc_html_e('Choose your cloth collection', 'alex-rose-2026'); ?></p>
			</header>

			<nav class="design-mix__mobile-tabs" aria-label="<?php esc_attr_e('Sections', 'alex-rose-2026'); ?>">
				<?php foreach ($design_sections as $index => $section) : ?>
					<button type="button" class="design-mix__mobile-tab<?php echo 0 === $index ? ' is-active' : ''; ?>" data-design-section-btn="<?php echo esc_attr($section['key']); ?>" data-design-subtitle="<?php echo esc_attr($section['subtitle']); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<?php echo esc_html($section['label']); ?>
					</button>
				<?php endforeach; ?>
			</nav>

			<div class="design-mix__scroll">
				<div data-design-section-panel="fabrics">
					<?php foreach ($design_collections as $group) : ?>
						<section class="design-mix__group">
							<h3><?php echo esc_html($group['title']); ?></h3>
							<div class="design-mix__swatches">
								<?php foreach ($group['items'] as $item) : ?>
									<?php
									$is_selected = ('English Riviera Collection' === $group['title'] && 'Exeter' === $item);
									$seed        = absint(crc32($group['title'] . '-' . $item));
									$colors      = $design_swatch_palette[$seed % count($design_swatch_palette)];
									?>
									<button type="button" class="design-mix__swatch<?php echo $is_selected ? ' is-selected' : ''; ?>" data-design-swatch data-design-label="<?php echo esc_attr($item); ?>" aria-pressed="<?php echo $is_selected ? 'true' : 'false'; ?>">
										<span class="design-mix__swatch-photo" style="<?php echo esc_attr('--swatch-a:' . $colors[0] . ';--swatch-b:' . $colors[1] . ';'); ?>" aria-hidden="true"></span>
										<span class="design-mix__swatch-name"><?php echo esc_html($item); ?></span>
									</button>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endforeach; ?>
				</div>

				<div data-design-section-panel="lining" hidden>
					<section class="design-mix__group">
						<h3><?php esc_html_e('Plain Colours', 'alex-rose-2026'); ?></h3>
						<div class="design-mix__linings">
							<?php foreach ($lining_plain_colours as $index => $lining) : ?>
								<button type="button" class="design-mix__lining-swatch<?php echo 0 === $index ? ' is-selected' : ''; ?>" data-design-swatch data-design-label="<?php echo esc_attr($lining['name']); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
									<span class="design-mix__lining-chip" style="<?php echo esc_attr('background:' . $lining['color'] . ';'); ?>"></span>
									<span class="design-mix__lining-label"><?php echo esc_html($lining['name']); ?></span>
								</button>
							<?php endforeach; ?>
						</div>
					</section>

					<section class="design-mix__group">
						<h3><?php esc_html_e('Patterns', 'alex-rose-2026'); ?></h3>
						<div class="design-mix__linings">
							<?php foreach ($lining_patterns as $pattern) : ?>
								<?php
								$seed   = absint(crc32('lining-' . $pattern));
								$colors = $design_swatch_palette[$seed % count($design_swatch_palette)];
								?>
								<button type="button" class="design-mix__lining-swatch" data-design-swatch data-design-label="<?php echo esc_attr($pattern); ?>" aria-pressed="false">
									<span class="design-mix__lining-chip design-mix__lining-chip--pattern" style="<?php echo esc_attr('--swatch-a:' . $colors[0] . ';--swatch-b:' . $colors[1] . ';'); ?>"></span>
									<span class="design-mix__lining-label"><?php echo esc_html($pattern); ?></span>
								</button>
							<?php endforeach; ?>
						</div>
					</section>

					<p class="design-mix__lining-note"><?php esc_html_e('Your chosen lining is monogrammed with your name inside the jacket.', 'alex-rose-2026'); ?></p>
				</div>

				<?php foreach ($design_sections as $section) : ?>
					<?php if ('fabrics' === $section['key']) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<section class="design-mix__empty" data-design-section-panel="<?php echo esc_attr($section['key']); ?>" hidden>
						<h3><?php echo esc_html($section['label']); ?></h3>
						<p><?php esc_html_e('Option cards for this section will be added next.', 'alex-rose-2026'); ?></p>
					</section>
				<?php endforeach; ?>
			</div>

			<p class="design-mix__samples-link">
				<a href="<?php echo esc_url(home_url('/request-cloth-samples')); ?>"><?php esc_html_e('Request free cloth samples →', 'alex-rose-2026'); ?></a>
			</p>
		</div>
	</div>
</section>
