<?php
/**
 * "Cloth Collection" — "Coming soon" body.
 *
 * Shown (in place of the intro / swatches / next-step / pager) for collections
 * flagged via alex_rose_2026_coming_soon_cloth_collections(). The collection
 * still appears in the cloths grid, header mega-menu and sidebar; only this
 * page's body is replaced.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="cc-missing cc-soon">
	<div class="cc-missing__inner">
		<p class="cc-missing__kicker"><?php esc_html_e('Coming soon', 'alex-rose-2026'); ?></p>
		<h2 class="cc-missing__title"><?php esc_html_e('This collection is arriving shortly.', 'alex-rose-2026'); ?></h2>
		<p class="cc-missing__body">
			<?php
			printf(
				/* translators: %s: link to the contact page */
				esc_html__('We are putting the finishing touches to this collection. In the meantime, explore our other cloths, or %s to be the first to know when it launches.', 'alex-rose-2026'),
				'<a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('get in touch', 'alex-rose-2026') . '</a>'
			);
			?>
		</p>
		<p class="cc-missing__body">
			<a href="<?php echo esc_url(home_url('/cloths/')); ?>"><?php esc_html_e('Browse all collections', 'alex-rose-2026'); ?> &rarr;</a>
		</p>
	</div>
</section>
