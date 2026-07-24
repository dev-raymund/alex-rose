<?php
/**
 * "Design Your Jacket" — left sticky preview pane.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<aside class="design-preview" aria-label="<?php esc_attr_e('Jacket preview', 'alex-rose-2026'); ?>">
	<img class="design-preview__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/collection-hero-blazer.jpg')); ?>" alt="<?php echo esc_attr__('Jacket preview', 'alex-rose-2026'); ?>" loading="eager" width="1200" height="1600">
	<div class="design-preview__shade" aria-hidden="true"></div>
	<p class="design-preview__brand"><?php esc_html_e('Alex Rose · Fine Tailoring', 'alex-rose-2026'); ?></p>
	<div class="design-preview__caption">
		<p class="design-preview__caption-kicker"><?php esc_html_e('Selected Cloth', 'alex-rose-2026'); ?></p>
		<p class="design-preview__caption-title"><?php esc_html_e('English Blazer Collection', 'alex-rose-2026'); ?></p>
	</div>
</aside>
