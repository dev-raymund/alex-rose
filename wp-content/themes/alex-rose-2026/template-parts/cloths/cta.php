<?php
/**
 * "Our Cloths" — bottom CTA (free samples).
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="cloths-cta">
	<div class="cloths-cta__inner ar-container ar-container--6xl">
		<p class="cloths-cta__text">
			<?php esc_html_e('Not sure which cloth is right for you?', 'alex-rose-2026'); ?>
			<span class="cloths-cta__muted"><?php esc_html_e('Request free samples and feel them before you decide.', 'alex-rose-2026'); ?></span>
		</p>
		<a class="cloths-cta__btn" href="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>">
			<?php esc_html_e('Request Free Samples', 'alex-rose-2026'); ?>
		</a>
	</div>
</section>
