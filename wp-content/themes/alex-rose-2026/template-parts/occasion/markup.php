<?php
/**
 * "Occasion" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$occasion = alex_rose_2026_current_occasion();
?>
<main id="main" class="page-occasion" tabindex="-1">
	<?php if ($occasion === null) : ?>
		<section class="occ-missing">
			<div class="occ-missing__inner">
				<p class="occ-missing__kicker"><?php esc_html_e('Occasion not found', 'alex-rose-2026'); ?></p>
				<h1 class="occ-missing__title"><?php esc_html_e('This occasion is not yet set up.', 'alex-rose-2026'); ?></h1>
				<p class="occ-missing__body">
					<?php
					printf(
						/* translators: %s: link to /design/. */
						esc_html__('Please continue to %s to start designing a jacket.', 'alex-rose-2026'),
						'<a href="' . esc_url(home_url('/design/')) . '">' . esc_html__('Design Your Jacket', 'alex-rose-2026') . '</a>'
					);
					?>
				</p>
			</div>
		</section>
	<?php else :
		set_query_var('alex_rose_occasion', $occasion);
		get_template_part('template-parts/occasion/hero');
		get_template_part('template-parts/occasion/when');
		get_template_part('template-parts/occasion/samples');
		get_template_part('template-parts/occasion/cloths');
		get_template_part('template-parts/occasion/thinking');
		get_template_part('template-parts/occasion/cta');
		get_template_part('template-parts/occasion/related');
	endif; ?>
</main>
