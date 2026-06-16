<?php
/**
 * Single Off the Cuff article — body content.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$excerpt = get_the_excerpt();
?>
<section class="otca-content">
	<div class="otca-content__inner ar-container ar-container--3xl">
		<?php if ($excerpt) : ?>
			<p class="otca-content__standfirst"><?php echo esc_html($excerpt); ?></p>
		<?php endif; ?>

		<div class="otca-content__prose entry-content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
