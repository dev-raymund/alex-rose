<?php
/**
 * "Cloth Collection" — intro section (cloth image + story + specs + CTAs).
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$collection = alex_rose_2026_current_cloth_collection();
if ($collection === null) {
	return;
}
?>
<section class="cc-intro">
	<div class="cc-intro__inner ar-container ar-container--6xl">
		<div class="cc-intro__media">
			<div class="cc-intro__media-frame">
				<img class="cc-intro__img" src="<?php echo esc_url($collection['cloth_image']); ?>" alt="<?php
					/* translators: %s: collection title */
					echo esc_attr(sprintf(__('%s cloth', 'alex-rose-2026'), $collection['title']));
				?>" loading="lazy">
				<span class="cc-intro__corner cc-intro__corner--tl-h" aria-hidden="true"></span>
				<span class="cc-intro__corner cc-intro__corner--tl-v" aria-hidden="true"></span>
				<span class="cc-intro__corner cc-intro__corner--br-h" aria-hidden="true"></span>
				<span class="cc-intro__corner cc-intro__corner--br-v" aria-hidden="true"></span>
			</div>
		</div>

		<div class="cc-intro__body">
			<p class="cc-intro__kicker"><?php echo esc_html($collection['kicker']); ?></p>
			<p class="cc-intro__lead"><?php echo esc_html($collection['intro']); ?></p>

			<?php if (! empty($collection['paragraphs'])) : ?>
				<div class="cc-intro__paragraphs">
					<?php foreach ((array) $collection['paragraphs'] as $p) : ?>
						<p><?php echo esc_html($p); ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if (! empty($collection['specs'])) : ?>
				<dl class="cc-intro__specs">
					<?php foreach ((array) $collection['specs'] as $spec) : ?>
						<div class="cc-intro__spec">
							<dt class="cc-intro__spec-label"><?php echo esc_html($spec['label']); ?></dt>
							<dd class="cc-intro__spec-value"><?php echo esc_html($spec['value']); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

			<div class="cc-intro__actions">
				<a class="cc-intro__cta cc-intro__cta--primary" href="<?php echo esc_url(home_url('/design/')); ?>">
					<?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?>
				</a>
				<a class="cc-intro__cta cc-intro__cta--ghost" href="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>">
					<?php esc_html_e('Request Free Samples', 'alex-rose-2026'); ?> &rarr;
				</a>
			</div>
		</div>
	</div>
</section>
