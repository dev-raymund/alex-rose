<?php
/**
 * Delivery Information — prose sections and costs table.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$sections = array(
	array(
		'title' => __('What to Expect', 'alex-rose-2026'),
		'paragraphs' => array(
			__('When your new personalised tailored jacket is ready, it will be dispatched to you together with a complimentary garment cover and an individual welcome card detailing the story of your jacket.', 'alex-rose-2026'),
			__('Also included will be care tips from our master tailor to enable you to keep your jacket in perfect condition.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Delivery Timeline', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Each of our garments are individually tailored to you, meaning there will be no two jackets alike.', 'alex-rose-2026'),
			__('Because of our personalised service, the estimated time for delivery is between 6 to 8 weeks, subject to the fabric of your choice being in stock.', 'alex-rose-2026'),
			__('We will endeavour to keep you updated at each step of production and will be sure to inform you of any unexpected delays in the process.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('International Orders', 'alex-rose-2026'),
		'paragraphs' => array(
			__('For all of our clients outside the UK, the delivery date will depend on the courier and any customs clearance.', 'alex-rose-2026'),
			__('We unfortunately will not be responsible for any customs or clearance charges, so please keep this in mind when placing your order.', 'alex-rose-2026'),
			__('UK VAT at 20% is automatically deducted from orders dispatched to countries outside of the UK and EU.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Returns Policy', 'alex-rose-2026'),
		'paragraphs' => array(
			__('All garments are individually made to your preferences. Therefore, we cannot accept any returns except for those featuring a production error.', 'alex-rose-2026'),
			__('Incorrect measurements do not count as a production error.', 'alex-rose-2026'),
			__('To ensure a perfect fit, we can offer you a free consultation with our master tailor and also a complimentary tailor\'s tape measure.', 'alex-rose-2026'),
		),
	),
);

$delivery_costs = array(
	array('region' => __('UK', 'alex-rose-2026'), 'price' => __('Free of charge', 'alex-rose-2026')),
	array('region' => __('Europe', 'alex-rose-2026'), 'price' => 25),
	array('region' => __('United States & Canada', 'alex-rose-2026'), 'price' => 35),
	array('region' => __('Rest of World', 'alex-rose-2026'), 'price' => 50),
);

$vat_note = __('UK VAT at 20% is automatically deducted from orders dispatched to countries outside of the UK and EU.', 'alex-rose-2026');

$contact_line = sprintf(
	/* translators: 1: email link, 2: phone link */
	wp_kses(__('Email: %1$s · Phone: %2$s', 'alex-rose-2026'), array('a' => array('href' => array()))),
	'<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>',
	'<a href="tel:+441134688588">+44 (0)113 468 8588</a>'
);
?>
<section class="di-body">
	<div class="di-body__inner ar-container ar-container--6xl">
		<div class="di-body__col">
			<?php foreach ($sections as $section) : ?>
				<article class="di-section">
					<div class="di-section__head">
						<div class="di-section__rule" aria-hidden="true"></div>
						<h2 class="di-section__title"><?php echo esc_html((string) $section['title']); ?></h2>
					</div>
					<div class="di-section__body">
						<?php foreach ($section['paragraphs'] as $paragraph) : ?>
							<p><?php echo esc_html((string) $paragraph); ?></p>
						<?php endforeach; ?>
					</div>
				</article>
			<?php endforeach; ?>

			<article class="di-section">
				<div class="di-section__head">
					<div class="di-section__rule" aria-hidden="true"></div>
					<h2 class="di-section__title"><?php esc_html_e('Delivery Costs', 'alex-rose-2026'); ?></h2>
				</div>
				<div class="di-section__body">
					<div class="di-costs-table" role="table" aria-label="<?php esc_attr_e('Delivery costs by region', 'alex-rose-2026'); ?>">
						<?php foreach ($delivery_costs as $row) : ?>
							<div class="di-costs-table__row" role="row">
								<span class="di-costs-table__region" role="rowheader"><?php echo esc_html((string) $row['region']); ?></span>
								<span class="di-costs-table__price" role="cell"><?php
							if (is_numeric($row['price'])) {
								echo alex_rose_2026_price_html($row['price']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							} else {
								echo esc_html((string) $row['price']);
							}
							?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<p class="di-section__note"><?php echo esc_html($vat_note); ?></p>
				</div>
			</article>

			<article class="di-section">
				<div class="di-section__head">
					<div class="di-section__rule" aria-hidden="true"></div>
					<h2 class="di-section__title"><?php esc_html_e('Questions?', 'alex-rose-2026'); ?></h2>
				</div>
				<div class="di-section__body">
					<p><?php esc_html_e('If you have any questions about delivery, please get in touch and we will be happy to help.', 'alex-rose-2026'); ?></p>
					<p><?php echo wp_kses_post($contact_line); ?></p>
				</div>
			</article>
		</div>
	</div>
</section>
