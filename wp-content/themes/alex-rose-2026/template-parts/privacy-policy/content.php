<?php
/**
 * Privacy Policy — numbered prose sections.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$email_link = '<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>';

$sections = array(
	array(
		'title' => __('1. Who We Are', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We are Master Tailor Ltd, a company registered in England and Wales, registered company number 02587407.', 'alex-rose-2026'),
			__('Our registered address is 53 The Fairway, Leeds LS17 7PE, United Kingdom. Our correspondence address is Master Tailor Ltd, Prospect House, 32 Sovereign Street, Leeds LS1 4BJ, United Kingdom.', 'alex-rose-2026'),
			sprintf(
				/* translators: %s: email link */
				wp_kses(__('You may also contact us via email with any questions related to this privacy notice: %s', 'alex-rose-2026'), array('a' => array('href' => array()))),
				$email_link
			),
			__('The person responsible for overseeing our processing of personally identifiable information is Harold Rose.', 'alex-rose-2026'),
		),
		'html_paragraphs' => array(2),
	),
	array(
		'title' => __('2. What Information We Collect', 'alex-rose-2026'),
		'paragraphs' => array(
			__('When you register or place an order with us, we will hold details of your name, billing address and any separate delivery address.', 'alex-rose-2026'),
			__('We may record details of your IP address and will retain details of any orders you place with us or emails you send to us. We will also pass details of any payment information to our payment processing partners, but we do not retain this information in full.', 'alex-rose-2026'),
			__('You may choose to provide your email address, contact telephone number(s), and body measurements.', 'alex-rose-2026'),
			__('From time to time we may ask if you wish to provide additional information regarding specific areas of interest. We may also ask you to complete voluntary surveys.', 'alex-rose-2026'),
			__('We collect information regarding your use of our website using cookies.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('3. Why We Collect This Information', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Details of your name and addresses are necessary in order for us to fulfil our contract with you for the delivery of any goods you have ordered.', 'alex-rose-2026'),
			__('Providing us with an email address and contact telephone number is optional, but if provided, will assist us to notify you of updates to your order and to contact you regarding any queries we may have.', 'alex-rose-2026'),
			__('Your information may also be used for marketing purposes in order for us to send you information regarding our products and services by email, post, or text message.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('4. Cookies', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Cookies are text files placed on your computer to collect standard internet log information and visitor behaviour information. This information is used to track visitor use of our website and to compile statistical reports on website activity.', 'alex-rose-2026'),
			sprintf(
				/* translators: 1: aboutcookies.org link, 2: allaboutcookies.org link */
				wp_kses(
					__('For further information about cookies visit %1$s or %2$s.', 'alex-rose-2026'),
					array(
						'a' => array(
							'href'   => array(),
							'rel'    => array(),
							'target' => array(),
						),
					)
				),
				'<a href="https://www.aboutcookies.org" target="_blank" rel="noopener noreferrer">www.aboutcookies.org</a>',
				'<a href="https://www.allaboutcookies.org" target="_blank" rel="noopener noreferrer">www.allaboutcookies.org</a>'
			),
			__('You can set your browser not to accept cookies. However, some of our website features may not function as a result.', 'alex-rose-2026'),
			__('Some cookies are third-party cookies used by third parties in order to assist us in providing certain facilities, such as analysis purposes and support when you leave our site.', 'alex-rose-2026'),
		),
		'html_paragraphs' => array(1),
	),
	array(
		'title' => __('5. Who We Share Your Information With', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We may share your information with our payment processing partners, fraud detection partners, and courier companies.', 'alex-rose-2026'),
			__('We share information via cookies with our partners who provide certain website services.', 'alex-rose-2026'),
			__('We may also share details of your orders with our review partner in order to obtain your feedback regarding our products and service.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('6. What We Do With Your Information', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We will process your information in order to fulfil our contract with you and may pass it to third parties, such as courier companies, in order for them to deliver your order to you.', 'alex-rose-2026'),
			__('Details of your IP address may be passed to our payment processing partner in order to assist us with our fraud detection processes.', 'alex-rose-2026'),
			__('We will also process your information in order to send you marketing information. You may opt-in and out of receiving marketing communications from us by updating your marketing preferences.', 'alex-rose-2026'),
			__('You may opt-out of receiving marketing communications from us at any time. Please note that in the case of opting out of direct (postal) marketing, it may take up to 28 days for your request to be fully implemented.', 'alex-rose-2026'),
			__('All personal information we hold is stored securely with access limited to that only necessary for the specific purpose for which we are processing your data.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('7. How Long We Keep Your Information', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We retain your information for as long as necessary in order for us to complete our contract with you, provide information to Government organisations regarding VAT accounting, and to enable us to fulfil our legal duties.', 'alex-rose-2026'),
			__('In normal circumstances, we will retain information regarding you and any orders you placed with us for seven years after the date of your last order.', 'alex-rose-2026'),
			__('We will cease to process your information for marketing purposes when you ask us to do so.', 'alex-rose-2026'),
			__('We will delete any of your information which we are not legally required to retain on receipt of a formal request to do so.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('8. Accessing the Information We Hold', 'alex-rose-2026'),
		'paragraphs' => array(
			__('You may make a formal request to view the information we hold about you. This is called a Subject Access Request (SAR). You may make such a request either verbally or in writing.', 'alex-rose-2026'),
			__('When we receive such a request, we will process it as quickly as possible, usually within one month.', 'alex-rose-2026'),
			__('We will not usually make a charge for providing this information.', 'alex-rose-2026'),
			sprintf(
				/* translators: %s: email link */
				wp_kses(__('To make a request, please contact us at %s.', 'alex-rose-2026'), array('a' => array('href' => array()))),
				$email_link
			),
		),
		'html_paragraphs' => array(3),
	),
	array(
		'title' => __('9. Other Websites', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Our website may contain links to other websites. This privacy notice only applies to this website, so when you link to other websites you should read their own privacy policies.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('10. Changes to This Privacy Notice', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We keep our privacy policy under regular review and we will place any updates on this web page.', 'alex-rose-2026'),
			__('This privacy notice was last updated on 15th April 2020.', 'alex-rose-2026'),
		),
	),
);
?>
<section class="pp-body">
	<div class="pp-body__inner ar-container ar-container--6xl">
		<div class="pp-body__col">
			<?php foreach ($sections as $section) : ?>
				<?php
				$html_indexes = isset($section['html_paragraphs']) && is_array($section['html_paragraphs'])
					? $section['html_paragraphs']
					: array();
				?>
				<article class="pp-section">
					<div class="pp-section__head">
						<div class="pp-section__rule" aria-hidden="true"></div>
						<h2 class="pp-section__title"><?php echo esc_html((string) $section['title']); ?></h2>
					</div>
					<div class="pp-section__body">
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
