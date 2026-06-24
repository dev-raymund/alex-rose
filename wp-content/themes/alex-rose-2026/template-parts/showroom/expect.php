<?php
/**
 * "Showroom" — "What to expect" grid.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$items = array(
	array(
		'num'   => '01',
		'title' => __('A warm welcome', 'alex-rose-2026'),
		'body'  => __('Harold or a member of the team will greet you personally. There is no hard sell, just a conversation about what you are looking for.', 'alex-rose-2026'),
	),
	array(
		'num'   => '02',
		'title' => __('Browse the cloth library', 'alex-rose-2026'),
		'body'  => __('Over 150 fine British cloths are displayed in the showroom. You can handle, hold up and compare swatches in natural light before making any decision.', 'alex-rose-2026'),
	),
	array(
		'num'   => '03',
		'title' => __('Your measurements taken', 'alex-rose-2026'),
		'body'  => __('We take a full set of measurements, which are held on file. Any future orders can be accurately replicated or tweaked if required.', 'alex-rose-2026'),
	),
	array(
		'num'   => '04',
		'title' => __('Design your jacket together', 'alex-rose-2026'),
		'body'  => __('Style details, lapels, buttons, vents, lining, monogram, are agreed during the appointment. Every step is taken at your pace.', 'alex-rose-2026'),
	),
);
?>
<section class="sr-expect">
	<div class="sr-expect__inner ar-container ar-container--6xl">
		<header class="sr-expect__head">
			<p class="sr-expect__kicker"><?php esc_html_e('During Your Visit', 'alex-rose-2026'); ?></p>
			<h2 class="sr-expect__title"><?php esc_html_e('What to expect', 'alex-rose-2026'); ?></h2>
		</header>

		<div class="sr-expect__grid">
			<?php foreach ($items as $item) : ?>
				<article class="sr-expect__card">
					<p class="sr-expect__num"><?php echo esc_html((string) $item['num']); ?></p>
					<h3 class="sr-expect__card-title"><?php echo esc_html((string) $item['title']); ?></h3>
					<p class="sr-expect__card-body"><?php echo esc_html((string) $item['body']); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
