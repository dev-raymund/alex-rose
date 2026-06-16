<?php
/**
 * "Cloth Collection" — page-level wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$collection = alex_rose_2026_current_cloth_collection();
?>
<main id="main" class="page-cloth-collection" tabindex="-1">
	<?php if ($collection === null) : ?>
		<section class="cc-missing">
			<div class="cc-missing__inner">
				<p class="cc-missing__kicker"><?php esc_html_e('Collection not found', 'alex-rose-2026'); ?></p>
				<h1 class="cc-missing__title"><?php esc_html_e('This collection is not yet set up.', 'alex-rose-2026'); ?></h1>
				<p class="cc-missing__body">
					<?php
					printf(
						/* translators: %s: link to /cloths/. */
						esc_html__('Please return to the %s overview.', 'alex-rose-2026'),
						'<a href="' . esc_url(home_url('/cloths/')) . '">' . esc_html__('Our Cloths', 'alex-rose-2026') . '</a>'
					);
					?>
				</p>
			</div>
		</section>
	<?php else : ?>
		<?php
		get_template_part('template-parts/cloth-collection/hero');
		get_template_part('template-parts/cloth-collection/intro');
		get_template_part('template-parts/cloth-collection/swatches');
		get_template_part('template-parts/cloth-collection/next-step');
		get_template_part('template-parts/cloth-collection/pager');
		?>
	<?php endif; ?>
</main>
