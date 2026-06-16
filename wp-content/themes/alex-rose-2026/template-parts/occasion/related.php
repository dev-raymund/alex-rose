<?php
/**
 * "Occasion" — "Other Occasions" / You might also like.
 *
 * Auto-builds 3 cards from the occasion registry / live WP pages, excluding
 * the current page.
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
$slug = isset($occasion['slug']) ? (string) $occasion['slug'] : null;

$related = alex_rose_2026_related_occasions($slug, 3);
if (empty($related)) {
	return;
}
?>
<section class="occ-related">
	<div class="occ-related__inner ar-container">
		<header class="occ-related__head">
			<p class="occ-related__kicker"><?php esc_html_e('Other Occasions', 'alex-rose-2026'); ?></p>
			<h2 class="occ-related__title"><?php esc_html_e('You might also like.', 'alex-rose-2026'); ?></h2>
		</header>
		<ul class="occ-related__grid">
			<?php foreach ($related as $card) : ?>
				<li>
					<a class="occ-related-card" href="<?php echo esc_url((string) $card['url']); ?>">
						<div class="occ-related-card__media">
							<?php if (! empty($card['image'])) : ?>
								<img class="occ-related-card__img" src="<?php echo esc_url((string) $card['image']); ?>" alt="<?php echo esc_attr((string) $card['title']); ?>" loading="lazy">
							<?php endif; ?>
							<div class="occ-related-card__shade" aria-hidden="true"></div>
						</div>
						<div class="occ-related-card__copy">
							<?php if (! empty($card['kicker'])) : ?>
								<p class="occ-related-card__kicker"><?php echo esc_html((string) $card['kicker']); ?></p>
							<?php endif; ?>
							<p class="occ-related-card__title"><?php echo esc_html((string) $card['title']); ?></p>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
