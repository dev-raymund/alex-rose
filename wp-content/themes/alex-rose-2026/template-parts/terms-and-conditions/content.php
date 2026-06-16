<?php
/**
 * Terms and Conditions — prose sections.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$email_link   = '<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>';
$phone_link   = '<a href="tel:+441134688588">+44 (0)113 468 8588</a>';
$website_link = '<a href="https://www.alexrose.uk">www.alexrose.uk</a>';

$sections = array(
	array(
		'title' => __('Introduction', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Please read these terms and conditions carefully. Together with our privacy policy, they govern our relationship with you in relation to your access and use of this website and also govern the basis on which we sell our products to you.', 'alex-rose-2026'),
			sprintf(
				/* translators: 1: email link, 2: phone link */
				wp_kses(
					__('If you have any questions about them or do not wish to accept them, please contact our customer services department at %1$s or %2$s before continuing to use this website.', 'alex-rose-2026'),
					array('a' => array('href' => array()))
				),
				$email_link,
				$phone_link
			),
			__('We may change these terms and conditions at any time by updating this page. Using or accessing this website indicates your acceptance of these terms and conditions.', 'alex-rose-2026'),
		),
		'html_paragraphs' => array(1),
	),
	array(
		'title' => __('Information About Us', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We are Master Tailor Ltd, trading as Alex Rose since 1945, a company registered in England and Wales at Companies House. Our registered office is 2A Rodley Lane, Rodley, Leeds LS13 1HU and our registered number is 02587407.', 'alex-rose-2026'),
			__('Our VAT number is GB 829 8677 60.', 'alex-rose-2026'),
			sprintf(
				/* translators: 1: email link, 2: phone link */
				wp_kses(
					__('You can contact us by email at %1$s or by telephone on %2$s.', 'alex-rose-2026'),
					array('a' => array('href' => array()))
				),
				$email_link,
				$phone_link
			),
			sprintf(
				/* translators: %s: website link */
				wp_kses(
					__('The website to which these terms and conditions apply is %s.', 'alex-rose-2026'),
					array(
						'a' => array(
							'href'   => array(),
							'rel'    => array(),
							'target' => array(),
						),
					)
				),
				$website_link
			),
		),
		'html_paragraphs' => array(2, 3),
	),
	array(
		'title' => __('Intellectual Property', 'alex-rose-2026'),
		'paragraphs' => array(
			__('This website and all the materials contained in it are protected by intellectual property rights, including copyright, and either belong to us or are licensed to us to use. Materials include, but are not limited to, the design, layout, look, appearance, graphics, documents, product descriptions and product prices.', 'alex-rose-2026'),
			__('You may not copy, redistribute, republish or otherwise make the materials on this website available to anyone else without our consent in writing, except for personal use.', 'alex-rose-2026'),
			__('You may print or download materials from this website for your own personal use provided no materials are modified in any way, no graphics are used separately from accompanying text, and our copyright and trademark notices appear in all copies.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Purchasing From Us', 'alex-rose-2026'),
		'paragraphs' => array(
			__('By submitting your order, you are offering to buy the goods at the price set out in the order.', 'alex-rose-2026'),
			__('The prices of all goods published on this website are displayed inclusive of Value Added Tax (VAT) at the applicable rate and including delivery. However, if we are dispatching outside the UK then the VAT will be deducted in addition to the delivery charge.', 'alex-rose-2026'),
			__('We are not obliged to supply the goods to you until we have confirmed that we have accepted your order. You do not own the goods until we receive payment in full.', 'alex-rose-2026'),
			__('Prices are checked regularly. However, if we find the price has changed when we receive your order, we will contact you and ask if you wish to proceed.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Availability and Delivery', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We aim to deliver goods to you within six to eight weeks (excluding public holidays) following the date we confirm your order, subject always to the availability of the fabric that you choose.', 'alex-rose-2026'),
			__('You may cancel your order within 48 hours of placing the order. As all our products are made to order and bespoke, you may not cancel outside of that 48-hour period.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Damaged or Defective Goods', 'alex-rose-2026'),
		'paragraphs' => array(
			__('You should inspect the goods when you receive them for defects or damage.', 'alex-rose-2026'),
			__('If you find a defect or damage you must notify us by email within 7 days of receiving the goods and return the goods within 30 days of first receiving them.', 'alex-rose-2026'),
			__('Once we have received and inspected the goods, you have the right to a refund, provided we verify that the goods are faulty. Alternatively, we may repair or replace the goods at your option.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Returns', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We want you to be happy with your new clothing. If any garment is defective in either the material or workmanship it will be either replaced, refunded or adjusted.', 'alex-rose-2026'),
			__('Any garments that are made to order are not returnable unless they are defective.', 'alex-rose-2026'),
			__('All returns must be returned by you at your cost using a signed for service.', 'alex-rose-2026'),
			__('When sending goods back you need to obtain a certificate of posting as we are not responsible if any goods are lost in transit to us.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Our Liability', 'alex-rose-2026'),
		'paragraphs' => array(
			__('These terms and conditions do not exclude our liability (if any) to you for personal injury or death resulting from our negligence; fraud; or any matter which it would be illegal for us to exclude.', 'alex-rose-2026'),
			__('We are only liable to you for losses which you suffer as a result of a breach of these terms and conditions by us.', 'alex-rose-2026'),
			__('We are not responsible to you for any losses which you may incur which were not a foreseeable consequence of us breaching these terms and conditions.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Security', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.', 'alex-rose-2026'),
			__('The credit and debit card details entered by you are made directly into our secure payment partner\'s server. Alex Rose does not receive information relating to your credit card details.', 'alex-rose-2026'),
			__('We will email you with confirmation of your order once funds have been authorised.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Governing Law', 'alex-rose-2026'),
		'paragraphs' => array(
			__('The formation, existence, construction, performance, validity and all aspects of these terms and conditions will be governed by the law of England and Wales.', 'alex-rose-2026'),
			__('The English and Welsh courts will have non-exclusive jurisdiction to settle any disputes which may arise out of or in connection with these terms and conditions or use of the website.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('Contact', 'alex-rose-2026'),
		'paragraphs' => array(
			sprintf(
				/* translators: 1: email link, 2: phone link */
				wp_kses(
					__('If you have any questions about these terms, please contact us at %1$s or call %2$s.', 'alex-rose-2026'),
					array('a' => array('href' => array()))
				),
				$email_link,
				$phone_link
			),
			__('Master Tailor Ltd, 2A Rodley Lane, Rodley, Leeds LS13 1HU, West Yorkshire, England.', 'alex-rose-2026'),
		),
		'html_paragraphs' => array(0),
	),
);
?>
<section class="tc-body">
	<div class="tc-body__inner ar-container ar-container--6xl">
		<div class="tc-body__col">
			<?php foreach ($sections as $section) : ?>
				<?php
				$html_indexes = isset($section['html_paragraphs']) && is_array($section['html_paragraphs'])
					? $section['html_paragraphs']
					: array();
				?>
				<article class="tc-section">
					<div class="tc-section__head">
						<div class="tc-section__rule" aria-hidden="true"></div>
						<h2 class="tc-section__title"><?php echo esc_html((string) $section['title']); ?></h2>
					</div>
					<div class="tc-section__body">
						<?php foreach ($section['paragraphs'] as $index => $paragraph) : ?>
							<p>
								<?php
								if (in_array($index, $html_indexes, true)) {
									echo wp_kses_post((string) $paragraph);
								} else {
									echo esc_html((string) $paragraph);
								}
								?>
							</p>
						<?php endforeach; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
