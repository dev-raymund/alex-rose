<?php
/**
 * "Our Story" — three things Harold will not compromise on.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$principles = array(
	array(
		'number' => '01',
		'title'  => __('The cloth must be right.', 'alex-rose-2026'),
		'body'   => __("Harold selects every fabric in the collection personally. If a cloth is not something he would wear himself, it does not appear on the site. Britain's mills produce the finest cloth in the world and Harold knows most of their weavers by name.", 'alex-rose-2026'),
	),
	array(
		'number' => '02',
		'title'  => __('The fit must be exact.', 'alex-rose-2026'),
		'body'   => __('Harold does not make near-enough jackets. Every measurement is confirmed with you before cutting begins. If a measurement is unclear, Harold will ask again. He would rather delay an order than deliver a jacket that does not sit perfectly.', 'alex-rose-2026'),
	),
	array(
		'number' => '03',
		'title'  => __('You must feel it was worth it.', 'alex-rose-2026'),
		'body'   => __("Harold's reputation is built on clients who come back, and clients who send their sons. That only happens when the experience is right from first contact to last button. Harold reads every enquiry himself and replies to every question personally.", 'alex-rose-2026'),
	),
);
?>
<section class="os-principles">
	<div class="os-principles__inner ar-container ar-container--5xl">
		<header class="os-principles__head">
			<p class="os-principles__kicker"><?php esc_html_e('How Harold Works', 'alex-rose-2026'); ?></p>
			<h2 class="os-principles__title"><?php esc_html_e('Three things Harold will not compromise on.', 'alex-rose-2026'); ?></h2>
		</header>

		<div class="os-principles__grid">
			<?php foreach ($principles as $p) : ?>
				<article class="os-principle">
					<p class="os-principle__number"><?php echo esc_html($p['number']); ?></p>
					<h3 class="os-principle__title"><?php echo esc_html($p['title']); ?></h3>
					<p class="os-principle__body"><?php echo esc_html($p['body']); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
