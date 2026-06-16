<?php
/**
 * "Occasion" — "How we think about this jacket" + pull-quote.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$occasion = get_query_var('alex_rose_occasion', null);
if (! is_array($occasion)) {
	return;
}

$paragraphs = isset($occasion['thinking_paragraphs']) && is_array($occasion['thinking_paragraphs'])
	? $occasion['thinking_paragraphs']
	: array();

if (empty($paragraphs) && empty($occasion['pull_quote'])) {
	return;
}

$first_pair  = array_slice($paragraphs, 0, 2);
$third_para  = isset($paragraphs[2]) ? (string) $paragraphs[2] : '';
?>
<section class="occ-thinking">
	<div class="occ-thinking__inner ar-container">
		<header class="occ-thinking__head">
			<p class="occ-thinking__kicker"><?php esc_html_e('How We Think', 'alex-rose-2026'); ?></p>
			<?php if (! empty($occasion['thinking_title'])) : ?>
				<h2 class="occ-thinking__title"><?php echo esc_html((string) $occasion['thinking_title']); ?></h2>
			<?php endif; ?>
		</header>

		<?php if (! empty($paragraphs)) : ?>
			<div class="occ-thinking__grid">
				<div class="occ-thinking__col">
					<?php foreach ($first_pair as $para) : ?>
						<p class="occ-thinking__para"><?php echo esc_html((string) $para); ?></p>
					<?php endforeach; ?>
				</div>
				<?php if ($third_para !== '') : ?>
					<div class="occ-thinking__col">
						<p class="occ-thinking__para"><?php echo esc_html($third_para); ?></p>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if (! empty($occasion['pull_quote'])) : ?>
			<figure class="occ-thinking__quote">
				<blockquote>
					<?php echo esc_html('“' . (string) $occasion['pull_quote'] . '”'); ?>
				</blockquote>
			</figure>
		<?php endif; ?>
	</div>
</section>
