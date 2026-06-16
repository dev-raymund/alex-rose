<?php
/**
 * "Occasion" — "When to wear it" 2-column section.
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
?>
<section class="occ-when">
	<div class="occ-when__inner ar-container">
		<header class="occ-when__head">
			<?php if (! empty($occasion['when_kicker'])) : ?>
				<p class="occ-when__kicker"><?php echo esc_html((string) $occasion['when_kicker']); ?></p>
			<?php endif; ?>
			<?php if (! empty($occasion['when_title'])) : ?>
				<h2 class="occ-when__title"><?php echo esc_html((string) $occasion['when_title']); ?></h2>
			<?php endif; ?>
		</header>
		<div class="occ-when__grid">
			<?php if (! empty($occasion['when_paragraph'])) : ?>
				<div class="occ-when__lead">
					<p><?php echo esc_html((string) $occasion['when_paragraph']); ?></p>
				</div>
			<?php endif; ?>
			<?php if (! empty($occasion['when_bullets']) && is_array($occasion['when_bullets'])) : ?>
				<ul class="occ-when__list">
					<?php foreach ($occasion['when_bullets'] as $bullet) : ?>
						<li class="occ-when__item">
							<span class="occ-when__rule" aria-hidden="true"></span>
							<span class="occ-when__text"><?php echo esc_html((string) $bullet); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
