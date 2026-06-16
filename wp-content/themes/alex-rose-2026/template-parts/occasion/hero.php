<?php
/**
 * "Occasion" — full-bleed dark hero.
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
?>
<section class="occ-hero">
	<?php if (! empty($occasion['hero_image'])) : ?>
		<img class="occ-hero__img" src="<?php echo esc_url((string) $occasion['hero_image']); ?>" alt="<?php echo esc_attr((string) ($occasion['title'] ?? '')); ?>" loading="eager">
	<?php endif; ?>
	<div class="occ-hero__shade" aria-hidden="true"></div>
	<div class="occ-hero__shade-bottom" aria-hidden="true"></div>
	<div class="occ-hero__inner">
		<?php if (! empty($occasion['eyebrow'])) : ?>
			<p class="occ-hero__eyebrow"><?php echo esc_html((string) $occasion['eyebrow']); ?></p>
		<?php endif; ?>
		<div class="occ-hero__spacer" aria-hidden="true"></div>
		<div class="occ-hero__body">
			<h1 class="occ-hero__title"><?php echo esc_html((string) ($occasion['title'] ?? '') . '.'); ?></h1>
			<?php if (! empty($occasion['hero_lead'])) : ?>
				<p class="occ-hero__lead"><?php echo esc_html((string) $occasion['hero_lead']); ?></p>
			<?php endif; ?>
			<div class="occ-hero__actions">
				<a class="occ-hero__btn occ-hero__btn--primary" href="<?php echo esc_url(add_query_arg('occasion', $slug, home_url('/design/'))); ?>">
					<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
				</a>
				<a class="occ-hero__btn occ-hero__btn--ghost" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>">
					<span><?php esc_html_e('Book a Consultation', 'alex-rose-2026'); ?></span>
					<span class="occ-hero__btn-arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</div>
	</div>
</section>
