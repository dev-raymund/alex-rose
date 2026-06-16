<?php
/**
 * Design page desktop preview column.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<aside class="design-stage-preview" aria-label="<?php esc_attr_e('Jacket preview', 'alex-rose-2026'); ?>">
	<div class="design-stage-preview__media">
		<img class="design-stage-preview__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/collection-hero-riviera.jpg')); ?>" alt="<?php esc_attr_e('Jacket preview', 'alex-rose-2026'); ?>" loading="eager" width="1400" height="1600">
		<div class="design-stage-preview__shade" aria-hidden="true"></div>
		<div class="design-stage-preview__meta">
			<p class="design-stage-preview__kicker"><?php esc_html_e('Alex Rose · Your Design', 'alex-rose-2026'); ?></p>
			<p class="design-stage-preview__name" data-design-selected-name><?php esc_html_e('Exeter', 'alex-rose-2026'); ?></p>
		</div>
		<div class="design-stage-preview__price">
			<p><?php esc_html_e('Starting From', 'alex-rose-2026'); ?></p>
			<strong>£595</strong>
		</div>
		<div class="design-stage-preview__currencies" aria-label="<?php esc_attr_e('Currency', 'alex-rose-2026'); ?>">
			<button type="button" class="is-active">GBP</button>
			<button type="button">EUR</button>
			<button type="button">USD</button>
		</div>
	</div>
	<div class="design-stage-preview__foot">
		<button type="button" class="design-stage-preview__cta"><?php esc_html_e('Preview My Jacket', 'alex-rose-2026'); ?> <span aria-hidden="true">→</span></button>
	</div>
</aside>
