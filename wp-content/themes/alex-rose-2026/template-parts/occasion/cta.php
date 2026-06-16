<?php
/**
 * "Occasion" — dark "Start Designing" CTA with a 2x2 feature card grid.
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
$slug = isset($occasion['slug']) ? (string) $occasion['slug'] : '';

$features = array(
	array(
		'title' => __('Made to Order', 'alex-rose-2026'),
		'body'  => __('Every jacket made to your exact specification', 'alex-rose-2026'),
	),
	array(
		'title' => __('No deposit', 'alex-rose-2026'),
		'body'  => __('Reserve your design with no payment upfront', 'alex-rose-2026'),
	),
	array(
		'title' => __('6-8 weeks', 'alex-rose-2026'),
		'body'  => __('From your design to your door, fully tracked', 'alex-rose-2026'),
	),
	array(
		'title' => __('Free samples', 'alex-rose-2026'),
		'body'  => __('Touch the cloth before you commit to anything', 'alex-rose-2026'),
	),
);
?>
<section class="occ-cta">
	<?php if (! empty($occasion['hero_image'])) : ?>
		<img class="occ-cta__bg" src="<?php echo esc_url((string) $occasion['hero_image']); ?>" alt="" aria-hidden="true" loading="lazy">
	<?php endif; ?>
	<div class="occ-cta__shade" aria-hidden="true"></div>

	<div class="occ-cta__inner ar-container">
		<div class="occ-cta__grid">
			<div class="occ-cta__copy">
				<div class="occ-cta__rule" aria-hidden="true"></div>
				<p class="occ-cta__kicker"><?php esc_html_e('Start Designing', 'alex-rose-2026'); ?></p>
				<?php if (! empty($occasion['cta_title'])) : ?>
					<h2 class="occ-cta__title"><?php echo esc_html((string) $occasion['cta_title']); ?></h2>
				<?php endif; ?>
				<?php if (! empty($occasion['cta_lead'])) : ?>
					<p class="occ-cta__lead"><?php echo esc_html((string) $occasion['cta_lead']); ?></p>
				<?php endif; ?>
				<div class="occ-cta__actions">
					<a class="occ-cta__btn occ-cta__btn--primary" href="<?php echo esc_url(add_query_arg('occasion', $slug, home_url('/design/'))); ?>">
						<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
						<span aria-hidden="true">&nbsp;&rarr;</span>
					</a>
					<a class="occ-cta__btn occ-cta__btn--ghost" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>">
						<?php esc_html_e('Book a Free Call', 'alex-rose-2026'); ?>
					</a>
				</div>
			</div>

			<ul class="occ-cta__features">
				<?php foreach ($features as $f) : ?>
					<li class="occ-cta__feature">
						<p class="occ-cta__feature-title"><?php echo esc_html((string) $f['title']); ?></p>
						<p class="occ-cta__feature-body"><?php echo esc_html((string) $f['body']); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
