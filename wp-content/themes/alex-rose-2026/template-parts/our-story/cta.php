<?php
/**
 * "Our Story" — bottom dark CTA band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="os-cta">
	<div class="os-cta__inner ar-container ar-container--3xl">
		<p class="os-cta__kicker"><?php esc_html_e('Ready to begin?', 'alex-rose-2026'); ?></p>
		<h2 class="os-cta__title">
			<?php
			echo wp_kses(
				__('Eighty years of making it right.<br>Your jacket is next.', 'alex-rose-2026'),
				array('br' => array())
			);
			?>
		</h2>
		<div class="os-cta__actions">
			<a class="os-cta__btn os-cta__btn--primary" href="<?php echo esc_url(home_url('/design/')); ?>">
				<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
			</a>
			<a class="os-cta__btn os-cta__btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">
				<?php esc_html_e('Send an Enquiry', 'alex-rose-2026'); ?>
			</a>
		</div>
	</div>
</section>
