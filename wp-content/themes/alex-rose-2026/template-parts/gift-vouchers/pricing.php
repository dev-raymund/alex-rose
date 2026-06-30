<?php
/**
 * "Gift Vouchers" — pricing list (max-w-3xl, muted bg).
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$rows = array(
	array('label' => __('Cotswold Jacket', 'alex-rose-2026'),             'price' => 695),
	array('label' => __('International Travel Jacket', 'alex-rose-2026'), 'price' => 695),
	array('label' => __('Yorkshire Tweed Jacket', 'alex-rose-2026'),      'price' => 595),
	array('label' => __('Harris Tweed Jacket', 'alex-rose-2026'),         'price' => 650),
	array('label' => __('Heritage Jacket', 'alex-rose-2026'),             'price' => 595),
	array('label' => __('Moorland Tweed Jacket', 'alex-rose-2026'),       'price' => 695),
	array('label' => __('English Blazer', 'alex-rose-2026'),              'price' => 595),
);
?>
<section class="gv-pricing">
	<div class="gv-pricing__inner ar-container ar-container--3xl">
		<p class="gv-pricing__kicker"><?php esc_html_e('Pricing', 'alex-rose-2026'); ?></p>
		<h2 class="gv-pricing__title"><?php esc_html_e('A Guide to Our Jacket Prices.', 'alex-rose-2026'); ?></h2>
		<dl class="gv-pricing__list">
			<?php foreach ($rows as $row) : ?>
				<div class="gv-pricing__row">
					<dt class="gv-pricing__label">
						<span class="gv-pricing__dot" aria-hidden="true"></span>
						<span><?php echo esc_html((string) $row['label']); ?></span>
					</dt>
					<dd class="gv-pricing__price"><?php echo alex_rose_2026_price_html($row['price']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
		<p class="gv-pricing__note"><?php esc_html_e('Prices are a guide only. Final price confirmed after consultation.', 'alex-rose-2026'); ?></p>
	</div>
</section>
