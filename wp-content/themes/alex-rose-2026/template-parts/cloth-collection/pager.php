<?php
/**
 * "Cloth Collection" — bottom "Next Collection" pager.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$next = alex_rose_2026_next_cloth_collection();
if ($next === null) {
	return;
}
?>
<nav class="cc-pager" aria-label="<?php esc_attr_e('Collection navigation', 'alex-rose-2026'); ?>">
	<div class="cc-pager__inner ar-container ar-container--6xl">
		<div class="cc-pager__spacer" aria-hidden="true"></div>
		<a class="cc-pager__link" href="<?php echo esc_url($next['url']); ?>">
			<span class="cc-pager__kicker"><?php esc_html_e('Next Collection', 'alex-rose-2026'); ?> &rarr;</span>
			<span class="cc-pager__title"><?php echo esc_html($next['title']); ?></span>
		</a>
	</div>
</nav>
