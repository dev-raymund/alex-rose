<?php
/**
 * Cookie consent banner (GDPR / UK PECR).
 *
 * Rendered on every page from footer.php. The banner itself is hidden by
 * default (aria-hidden / [hidden]); assets/js/cookie-banner.js reveals it when
 * no stored consent is found and records the visitor's choice. Any future
 * analytics/marketing script should be gated behind window.ARConsent.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$cookie_policy_url = home_url('/cookie-policy');
$privacy_url       = home_url('/privacy-policy');
?>
<div
	id="ar-cookie-banner"
	class="ar-cookie"
	role="dialog"
	aria-modal="false"
	aria-labelledby="ar-cookie-title"
	aria-describedby="ar-cookie-desc"
	hidden
>
	<div class="ar-cookie__inner">
		<div class="ar-cookie__intro">
			<div class="ar-cookie__rule" aria-hidden="true"></div>
			<p id="ar-cookie-title" class="ar-cookie__title"><?php esc_html_e('Your privacy', 'alex-rose-2026'); ?></p>
			<p id="ar-cookie-desc" class="ar-cookie__text">
				<?php
				printf(
					/* translators: 1: cookie policy link, 2: privacy policy link */
					wp_kses(
						__('We use cookies to run this website and, with your consent, to understand how it is used so we can improve it. Read our %1$s and %2$s.', 'alex-rose-2026'),
						array('a' => array('href' => array()))
					),
					'<a href="' . esc_url($cookie_policy_url) . '">' . esc_html__('Cookie Policy', 'alex-rose-2026') . '</a>',
					'<a href="' . esc_url($privacy_url) . '">' . esc_html__('Privacy Policy', 'alex-rose-2026') . '</a>'
				);
				?>
			</p>
		</div>

		<div class="ar-cookie__prefs" data-ar-cookie-prefs hidden>
			<label class="ar-cookie__option ar-cookie__option--locked">
				<input type="checkbox" checked disabled>
				<span>
					<span class="ar-cookie__option-name"><?php esc_html_e('Strictly necessary', 'alex-rose-2026'); ?></span>
					<span class="ar-cookie__option-desc"><?php esc_html_e('Required for the site to work (cart, checkout, security). Always on.', 'alex-rose-2026'); ?></span>
				</span>
			</label>
			<label class="ar-cookie__option">
				<input type="checkbox" data-ar-cookie-category="analytics">
				<span>
					<span class="ar-cookie__option-name"><?php esc_html_e('Analytics', 'alex-rose-2026'); ?></span>
					<span class="ar-cookie__option-desc"><?php esc_html_e('Helps us measure visits and improve the site. Anonymous.', 'alex-rose-2026'); ?></span>
				</span>
			</label>
			<label class="ar-cookie__option">
				<input type="checkbox" data-ar-cookie-category="marketing">
				<span>
					<span class="ar-cookie__option-name"><?php esc_html_e('Marketing', 'alex-rose-2026'); ?></span>
					<span class="ar-cookie__option-desc"><?php esc_html_e('Used to show you relevant offers on other platforms.', 'alex-rose-2026'); ?></span>
				</span>
			</label>
		</div>

		<div class="ar-cookie__actions">
			<button type="button" class="ar-cookie__btn ar-cookie__btn--ghost" data-ar-cookie-action="reject"><?php esc_html_e('Reject all', 'alex-rose-2026'); ?></button>
			<button type="button" class="ar-cookie__btn ar-cookie__btn--ghost" data-ar-cookie-action="manage"><?php esc_html_e('Manage', 'alex-rose-2026'); ?></button>
			<button type="button" class="ar-cookie__btn ar-cookie__btn--ghost" data-ar-cookie-action="save" hidden><?php esc_html_e('Save choices', 'alex-rose-2026'); ?></button>
			<button type="button" class="ar-cookie__btn ar-cookie__btn--solid" data-ar-cookie-action="accept"><?php esc_html_e('Accept all', 'alex-rose-2026'); ?></button>
		</div>
	</div>
</div>
