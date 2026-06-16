<?php
/**
 * "Our Story" — stats band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$stats = array(
	array(
		'value'    => '1945',
		'label'    => __('Founded in Leeds', 'alex-rose-2026'),
		'is_gold'  => true,
	),
	array(
		'value'    => '80+',
		'label'    => __('Years in business', 'alex-rose-2026'),
		'is_gold'  => false,
	),
);
?>
<section class="os-stats">
	<div class="os-stats__inner ar-container ar-container--5xl">
		<dl class="os-stats__grid">
			<?php foreach ($stats as $s) : ?>
				<div class="os-stat">
					<dt class="os-stat__value<?php echo $s['is_gold'] ? ' os-stat__value--gold' : ''; ?>"><?php echo esc_html($s['value']); ?></dt>
					<dd class="os-stat__label"><?php echo esc_html($s['label']); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
