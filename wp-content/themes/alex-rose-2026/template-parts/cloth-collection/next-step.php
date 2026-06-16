<?php
/**
 * "Cloth Collection" — dark "Next step" CTA band.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="cc-next">
	<div class="cc-next__inner ar-container ar-container--6xl">
		<div class="cc-next__lead">
			<p class="cc-next__kicker"><?php esc_html_e('Next Step', 'alex-rose-2026'); ?></p>
			<h3 class="cc-next__title"><?php esc_html_e('Ready to design your jacket?', 'alex-rose-2026'); ?></h3>
		</div>
		<div class="cc-next__actions">
			<a class="cc-next__btn cc-next__btn--primary" href="<?php echo esc_url(home_url('/design/')); ?>">
				<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
			</a>
			<a class="cc-next__btn cc-next__btn--ghost" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>">
				<?php esc_html_e('Book a Free Consultation', 'alex-rose-2026'); ?>
			</a>
		</div>
	</div>
</section>
