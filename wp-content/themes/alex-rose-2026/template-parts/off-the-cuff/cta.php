<?php
/**
 * "Off the Cuff" — bottom dark CTA band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="otc-cta">
	<div class="otc-cta__inner ar-container">
		<div class="otc-cta__lead">
			<p class="otc-cta__kicker"><?php esc_html_e('Ready to start?', 'alex-rose-2026'); ?></p>
			<h2 class="otc-cta__title">
				<?php
				echo wp_kses(
					__('Your jacket is waiting<br>to be made.', 'alex-rose-2026'),
					array('br' => array())
				);
				?>
			</h2>
		</div>
		<div class="otc-cta__actions">
			<a class="otc-cta__btn otc-cta__btn--primary" href="<?php echo esc_url(home_url('/design/')); ?>">
				<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
			</a>
			<a class="otc-cta__btn otc-cta__btn--ghost" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>">
				<?php esc_html_e('Book a Consultation', 'alex-rose-2026'); ?>
			</a>
		</div>
	</div>
</section>
