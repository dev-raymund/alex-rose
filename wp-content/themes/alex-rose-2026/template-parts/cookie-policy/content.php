<?php
/**
 * Cookie Policy — numbered prose sections.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$privacy_link = '<a href="' . esc_url(home_url('/privacy-policy')) . '">' . esc_html__('Privacy Policy', 'alex-rose-2026') . '</a>';
$email_link   = '<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>';
$manage_link  = '<a href="#" data-ar-cookie-settings>' . esc_html__('cookie settings', 'alex-rose-2026') . '</a>';

$sections = array(
	array(
		'title' => __('1. What Are Cookies', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Cookies are small text files that are placed on your computer or device when you visit a website. They are widely used to make websites work, to work more efficiently, and to provide information to the site owners.', 'alex-rose-2026'),
			__('Some cookies are set for the duration of your visit and are deleted when you close your browser (session cookies). Others remain on your device for a set period or until you delete them (persistent cookies).', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('2. How We Use Cookies', 'alex-rose-2026'),
		'paragraphs' => array(
			__('We use cookies to keep the website functioning correctly — for example, to remember the items in your cart, to keep you signed in during checkout, and to keep the site secure. These are essential and cannot be switched off.', 'alex-rose-2026'),
			__('With your consent, we also use cookies to understand how visitors use the site so that we can improve it, and to help us show you relevant information on other platforms. You are free to accept or decline these.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('3. Categories of Cookies We Use', 'alex-rose-2026'),
		'paragraphs' => array(
			__('Strictly necessary — required for the website to function, including cart, checkout, and security features. These are always active and do not require consent.', 'alex-rose-2026'),
			__('Analytics — help us measure how many people visit the site and how they use it, so we can improve the experience. This information is aggregated and does not identify you personally.', 'alex-rose-2026'),
			__('Marketing — allow us and our partners to show you products and offers that may be relevant to you on other websites and platforms.', 'alex-rose-2026'),
		),
	),
	array(
		'title' => __('4. Consent', 'alex-rose-2026'),
		'paragraphs' => array(
			__('When you first visit our website you are asked whether you accept non-essential cookies. Strictly necessary cookies are set regardless, as the site cannot function without them.', 'alex-rose-2026'),
			sprintf(
				/* translators: %s: cookie settings link */
				wp_kses(__('You can change or withdraw your consent at any time by opening your %s. Your choice is stored for six months, after which we will ask again.', 'alex-rose-2026'), array('a' => array('href' => array(), 'data-ar-cookie-settings' => array()))),
				$manage_link
			),
		),
		'html_paragraphs' => array(1),
	),
	array(
		'title' => __('5. Managing Cookies in Your Browser', 'alex-rose-2026'),
		'paragraphs' => array(
			__('In addition to our cookie settings, you can control and delete cookies through your browser settings. Most browsers allow you to refuse or delete cookies; the method varies from browser to browser. Please note that blocking all cookies may prevent parts of our website from working.', 'alex-rose-2026'),
			sprintf(
				/* translators: 1: aboutcookies.org link, 2: allaboutcookies.org link */
				wp_kses(
					__('For further information about cookies visit %1$s or %2$s.', 'alex-rose-2026'),
					array('a' => array('href' => array(), 'rel' => array(), 'target' => array()))
				),
				'<a href="https://www.aboutcookies.org" target="_blank" rel="noopener noreferrer">www.aboutcookies.org</a>',
				'<a href="https://www.allaboutcookies.org" target="_blank" rel="noopener noreferrer">www.allaboutcookies.org</a>'
			),
		),
		'html_paragraphs' => array(1),
	),
	array(
		'title' => __('6. More Information', 'alex-rose-2026'),
		'paragraphs' => array(
			sprintf(
				/* translators: %s: privacy policy link */
				wp_kses(__('This Cookie Policy should be read alongside our %s, which explains how we handle your personal information.', 'alex-rose-2026'), array('a' => array('href' => array()))),
				$privacy_link
			),
			sprintf(
				/* translators: %s: email link */
				wp_kses(__('If you have any questions about our use of cookies, please contact us at %s.', 'alex-rose-2026'), array('a' => array('href' => array()))),
				$email_link
			),
			__('This Cookie Policy was last updated in July 2026.', 'alex-rose-2026'),
		),
		'html_paragraphs' => array(0, 1),
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
