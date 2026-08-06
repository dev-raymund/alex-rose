<?php
/**
 * "Feel the cloth" free-samples modal.
 *
 * Rendered site-wide from footer.php but shown ONCE per visitor on first page
 * load (assets/js/cloth-samples-modal.js guards on localStorage and waits for
 * the cookie banner to be dismissed first). Swatch images are pulled from the
 * live cloth-collection registry so there are no broken images.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$links       = function_exists('alex_rose_2026_cloth_collection_links') ? alex_rose_2026_cloth_collection_links() : array();
$swatches    = array_slice(array_values($links), 0, 4);
$cloths_url  = function_exists('alex_rose_2026_cloths_page_url') ? alex_rose_2026_cloths_page_url() : home_url('/cloths');
$samples_url = home_url('/request-cloth-samples');
?>
<div
	id="ar-samples-modal"
	class="ar-samples"
	role="dialog"
	aria-modal="true"
	aria-labelledby="ar-samples-title"
	aria-describedby="ar-samples-desc"
	hidden
>
	<div class="ar-samples__backdrop" data-ar-samples-dismiss></div>
	<div class="ar-samples__dialog" role="document">
		<div class="ar-samples__bar" aria-hidden="true"></div>
		<button type="button" class="ar-samples__close" aria-label="<?php esc_attr_e('Close', 'alex-rose-2026'); ?>" data-ar-samples-dismiss>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
		</button>
		<div class="ar-samples__body">
			<p class="ar-samples__kicker"><?php esc_html_e('Complimentary Service', 'alex-rose-2026'); ?></p>
			<h2 id="ar-samples-title" class="ar-samples__title"><?php esc_html_e('Feel the cloth before you commit.', 'alex-rose-2026'); ?></h2>
			<p id="ar-samples-desc" class="ar-samples__text"><?php esc_html_e('We will send up to three cloth samples to your door, free of charge. Hold them in your hand, compare the weights and weaves, then decide. No account needed.', 'alex-rose-2026'); ?></p>

			<div class="ar-samples__swatches">
				<?php foreach ($swatches as $swatch) : ?>
					<span class="ar-samples__swatch">
						<?php if (! empty($swatch['image'])) : ?>
							<img src="<?php echo esc_url($swatch['image']); ?>" alt="<?php echo esc_attr(isset($swatch['title']) ? (string) $swatch['title'] : ''); ?>" loading="lazy">
						<?php endif; ?>
					</span>
				<?php endforeach; ?>
				<a class="ar-samples__swatch ar-samples__swatch--more" href="<?php echo esc_url($cloths_url); ?>">
					<span><?php esc_html_e('100+ Cloths', 'alex-rose-2026'); ?></span>
				</a>
			</div>

			<div class="ar-samples__actions">
				<a class="ar-samples__btn ar-samples__btn--solid" href="<?php echo esc_url($samples_url); ?>"><?php esc_html_e('Request Free Samples', 'alex-rose-2026'); ?></a>
				<button type="button" class="ar-samples__btn ar-samples__btn--ghost" data-ar-samples-dismiss><?php esc_html_e('Maybe later', 'alex-rose-2026'); ?></button>
			</div>

			<p class="ar-samples__note"><?php esc_html_e('UK addresses only. Posted within two working days.', 'alex-rose-2026'); ?></p>
		</div>
	</div>
</div>
