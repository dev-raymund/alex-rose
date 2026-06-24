<?php
/**
 * Alex Rose 2026 — starter theme (plain CSS + optional ACF)
 *
 * @package Alex_Rose_2026
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
	exit;
}

define('ALEX_ROSE_2026_VERSION', '3.0.0');
define('ALEX_ROSE_2026_DIR', get_template_directory());
define('ALEX_ROSE_2026_URI', get_template_directory_uri());

require_once ALEX_ROSE_2026_DIR . '/inc/cloth-collections.php';
require_once ALEX_ROSE_2026_DIR . '/inc/occasions.php';
require_once ALEX_ROSE_2026_DIR . '/inc/off-the-cuff.php';
require_once ALEX_ROSE_2026_DIR . '/inc/forms.php';

/**
 * Absolute URL for a file under wp-content/uploads (correct when WordPress lives in a subdirectory).
 */
function alex_rose_2026_uploads_url(string $path_from_uploads_root): string {
	$path_from_uploads_root = ltrim($path_from_uploads_root, '/');
	$upload_dir              = wp_upload_dir();
	if (! empty($upload_dir['error'])) {
		return '';
	}

	return esc_url($upload_dir['baseurl'] . '/' . $path_from_uploads_root);
}

/**
 * Absolute URL for a file inside the project's /dist folder (the React build output).
 * Allows the new page templates to reference the dist images without copying them
 * into the WordPress media library.
 */
function alex_rose_2026_dist_url(string $rel): string {
	return esc_url(home_url('/dist/' . ltrim($rel, '/')));
}

/**
 * The page templates that share the dist design system (CSS + JS).
 *
 * @return string[]
 */
function alex_rose_2026_dist_templates(): array {
	return array(
		'template/checkout.php',
	);
}

/**
 * Walker that renders the slide-out sidebar menu in the dist's pattern:
 *  - Top-level items WITHOUT children render as a plain link (with bottom divider).
 *  - Top-level items WITH children render as an accordion (button + chevron, nested list as body).
 *  - Any top-level item with the CSS class "featured" gets the gold/highlight style.
 */
if (! class_exists('Alex_Rose_2026_Sidebar_Walker')) {
	final class Alex_Rose_2026_Sidebar_Walker extends Walker_Nav_Menu {

		public function start_lvl(&$output, $depth = 0, $args = null) {
			if ($depth === 0) {
				$output .= '<div class="site-side-menu__panel" hidden><ul class="site-side-menu__sublist">';
			} else {
				$output .= '<ul class="site-side-menu__sublist">';
			}
		}

		public function end_lvl(&$output, $depth = 0, $args = null) {
			if ($depth === 0) {
				$output .= '</ul></div>';
			} else {
				$output .= '</ul>';
			}
		}

		public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
			$classes  = empty($item->classes) ? array() : (array) $item->classes;
			$featured = in_array('featured', $classes, true);
			$has_kids = in_array('menu-item-has-children', $classes, true);
			$url      = ! empty($item->url) ? $item->url : '#';
			$title    = apply_filters('the_title', $item->title, $item->ID);

			if ($depth === 0) {
				if ($has_kids) {
					$output .= '<li class="site-side-menu__item site-side-menu__item--has-children">';
					$output .= '<button type="button" class="site-side-menu__accordion" aria-expanded="false" data-sidebar-accordion>';
					$output .= '<span class="site-side-menu__label">' . esc_html($title) . '</span>';
					$output .= '<span class="site-side-menu__chevron" aria-hidden="true">+</span>';
					$output .= '</button>';
				} else {
					$link_class = 'site-side-menu__link';
					$arrow      = '';
					if ($featured) {
						$link_class .= ' site-side-menu__link--featured';
						$arrow      = ' <span class="site-side-menu__link-arrow" aria-hidden="true">&rarr;</span>';
					}
					$output .= '<li class="site-side-menu__item">';
					$output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url($url) . '">' . esc_html($title) . $arrow . '</a>';
				}
			} else {
				$output .= '<li class="site-side-menu__subitem">';
				$output .= '<a class="site-side-menu__sublink" href="' . esc_url($url) . '">' . esc_html($title) . '</a>';
			}
		}

		public function end_el(&$output, $item, $depth = 0, $args = null) {
			$output .= '</li>';
		}
	}
}

function alex_rose_2026_is_dist_template(): bool {
	foreach (alex_rose_2026_dist_templates() as $tpl) {
		if (is_page_template($tpl)) {
			return true;
		}
	}
	return false;
}

/**
 * True when the current view should load marketing homepage assets (including front page).
 */
function alex_rose_2026_is_home_marketing(): bool {
	if (is_page_template('template/home.php')) {
		return true;
	}
	if (! is_front_page()) {
		return false;
	}
	$page = get_queried_object();
	if (! ($page instanceof WP_Post) || $page->post_type !== 'page') {
		return false;
	}
	$slug = get_page_template_slug($page);
	return $slug === 'template/home.php';
}

add_action(
	'after_setup_theme',
	static function (): void {
		load_theme_textdomain('alex-rose-2026', ALEX_ROSE_2026_DIR . '/languages');

		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
		add_theme_support('custom-logo', array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		));

		register_nav_menus(array(
			'primary' => __('Primary menu', 'alex-rose-2026'),
			'sidebar' => __('Sidebar menu (slide-out)', 'alex-rose-2026'),
			'footer'  => __('Footer menu', 'alex-rose-2026'),
		));
	}
);

/**
 * Page templates that include a public form whose JS depends on the
 * shared form-submit helper.
 *
 * @return string[]
 */
function alex_rose_2026_form_templates(): array {
	return array(
		'template/contact.php',
		'template/gift-vouchers.php',
		'template/request-cloth-samples.php',
		'template/request-tape-measure.php',
		'template/schedule-a-call.php',
		'template/post-your-jacket.php',
		'template/send-measurements.php',
		'template/off-the-cuff.php',
	);
}

/**
 * True when the current view renders one of the public forms (so we
 * should load the shared form-submit helper).
 */
function alex_rose_2026_should_load_form_helper(): bool {
	foreach (alex_rose_2026_form_templates() as $tpl) {
		if (is_page_template($tpl)) {
			return true;
		}
	}
	if (is_singular('post') && function_exists('alex_rose_2026_is_off_the_cuff_post')
		&& alex_rose_2026_is_off_the_cuff_post((int) get_queried_object_id())) {
		return true;
	}
	return false;
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$global_css = ALEX_ROSE_2026_DIR . '/assets/css/global.css';
		$global_ver = is_readable($global_css) ? (string) filemtime($global_css) : ALEX_ROSE_2026_VERSION;

		wp_enqueue_style(
			'alex-rose-2026-global',
			ALEX_ROSE_2026_URI . '/assets/css/global.css',
			array(),
			$global_ver
		);

		$theme_css = ALEX_ROSE_2026_DIR . '/assets/css/theme.css';
		$theme_ver = is_readable($theme_css) ? (string) filemtime($theme_css) : ALEX_ROSE_2026_VERSION;

		wp_enqueue_style(
			'alex-rose-2026',
			ALEX_ROSE_2026_URI . '/assets/css/theme.css',
			array('alex-rose-2026-global'),
			$theme_ver
		);

		$reveal_css = ALEX_ROSE_2026_DIR . '/assets/css/ar-reveal.css';
		if (is_readable($reveal_css)) {
			wp_enqueue_style(
				'alex-rose-2026-reveal',
				ALEX_ROSE_2026_URI . '/assets/css/ar-reveal.css',
				array('alex-rose-2026'),
				(string) filemtime($reveal_css)
			);
		}

		$reveal_js = ALEX_ROSE_2026_DIR . '/assets/js/ar-reveal.js';
		if (is_readable($reveal_js)) {
			wp_enqueue_script(
				'alex-rose-2026-reveal',
				ALEX_ROSE_2026_URI . '/assets/js/ar-reveal.js',
				array(),
				(string) filemtime($reveal_js),
				true
			);
		}

		$form_js_path = ALEX_ROSE_2026_DIR . '/assets/js/form-submit.js';
		if (is_readable($form_js_path)) {
			wp_register_script(
				'alex-rose-2026-form-submit',
				ALEX_ROSE_2026_URI . '/assets/js/form-submit.js',
				array(),
				(string) filemtime($form_js_path),
				true
			);
			if (alex_rose_2026_should_load_form_helper()) {
				wp_enqueue_script('alex-rose-2026-form-submit');
			}
		}

		if (alex_rose_2026_is_home_marketing()) {
			$home_css = ALEX_ROSE_2026_DIR . '/assets/css/page-home.css';
			$home_ver = is_readable($home_css) ? (string) filemtime($home_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-home',
				ALEX_ROSE_2026_URI . '/assets/css/page-home.css',
				array('alex-rose-2026'),
				$home_ver
			);
		}

		if (is_page_template('template/how-it-works.php')) {
			$hiw_css = ALEX_ROSE_2026_DIR . '/assets/css/page-how-it-works.css';
			$hiw_ver = is_readable($hiw_css) ? (string) filemtime($hiw_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-hiw',
				ALEX_ROSE_2026_URI . '/assets/css/page-how-it-works.css',
				array('alex-rose-2026'),
				$hiw_ver
			);

			$hiw_js = ALEX_ROSE_2026_DIR . '/assets/js/page-how-it-works.js';
			if (is_readable($hiw_js)) {
				wp_enqueue_script(
					'alex-rose-2026-hiw',
					ALEX_ROSE_2026_URI . '/assets/js/page-how-it-works.js',
					array(),
					(string) filemtime($hiw_js),
					true
				);
			}
		}

		if (is_page_template('template/design.php')) {
			$design_css = ALEX_ROSE_2026_DIR . '/assets/css/page-design.css';
			$design_ver = is_readable($design_css) ? (string) filemtime($design_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-design',
				ALEX_ROSE_2026_URI . '/assets/css/page-design.css',
				array('alex-rose-2026'),
				$design_ver
			);

			// Loaded as type="module" via a direct wp_footer tag.
			// wp_enqueue_script strips the type attribute; a global script_loader_tag
			// filter caused 120 s timeouts (see commit history). This hook is scoped
			// to the design template only so it cannot affect other pages.
			add_action('wp_footer', function () {
				$js = ALEX_ROSE_2026_DIR . '/assets/js/page-design.js';
				if (! is_readable($js)) {
					return;
				}
				$url = add_query_arg('v', filemtime($js), ALEX_ROSE_2026_URI . '/assets/js/page-design.js');
				echo '<script type="module" src="' . esc_url($url) . '"></script>' . "\n";
			}, 20);
		}

		if (is_page_template('template/cloths.php')) {
			$cloths_css = ALEX_ROSE_2026_DIR . '/assets/css/page-cloths.css';
			$cloths_ver = is_readable($cloths_css) ? (string) filemtime($cloths_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-cloths',
				ALEX_ROSE_2026_URI . '/assets/css/page-cloths.css',
				array('alex-rose-2026'),
				$cloths_ver
			);
		}

		if (is_page_template('template/cloth-collection.php')) {
			$cc_css = ALEX_ROSE_2026_DIR . '/assets/css/page-cloth-collection.css';
			$cc_ver = is_readable($cc_css) ? (string) filemtime($cc_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-cloth-collection',
				ALEX_ROSE_2026_URI . '/assets/css/page-cloth-collection.css',
				array('alex-rose-2026'),
				$cc_ver
			);
		}

		if (is_page_template('template/cart.php')) {
			$cart_css = ALEX_ROSE_2026_DIR . '/assets/css/page-cart.css';
			$cart_ver = is_readable($cart_css) ? (string) filemtime($cart_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-cart',
				ALEX_ROSE_2026_URI . '/assets/css/page-cart.css',
				array('alex-rose-2026'),
				$cart_ver
			);

			$cart_js = ALEX_ROSE_2026_DIR . '/assets/js/page-cart.js';
			if (is_readable($cart_js)) {
				wp_enqueue_script(
					'alex-rose-2026-cart',
					ALEX_ROSE_2026_URI . '/assets/js/page-cart.js',
					array(),
					(string) filemtime($cart_js),
					true
				);
			}
		}

		if (is_page_template('template/off-the-cuff.php')) {
			$otc_css = ALEX_ROSE_2026_DIR . '/assets/css/page-off-the-cuff.css';
			$otc_ver = is_readable($otc_css) ? (string) filemtime($otc_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-off-the-cuff',
				ALEX_ROSE_2026_URI . '/assets/css/page-off-the-cuff.css',
				array('alex-rose-2026'),
				$otc_ver
			);

			$otc_js = ALEX_ROSE_2026_DIR . '/assets/js/page-off-the-cuff.js';
			if (is_readable($otc_js)) {
				wp_enqueue_script(
					'alex-rose-2026-off-the-cuff',
					ALEX_ROSE_2026_URI . '/assets/js/page-off-the-cuff.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($otc_js),
					true
				);
			}
		}

		if (is_singular('post') && alex_rose_2026_is_off_the_cuff_post((int) get_queried_object_id())) {
			$otc_css = ALEX_ROSE_2026_DIR . '/assets/css/page-off-the-cuff.css';
			$otc_ver = is_readable($otc_css) ? (string) filemtime($otc_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-off-the-cuff',
				ALEX_ROSE_2026_URI . '/assets/css/page-off-the-cuff.css',
				array('alex-rose-2026'),
				$otc_ver
			);

			$otca_css = ALEX_ROSE_2026_DIR . '/assets/css/page-otc-article.css';
			$otca_ver = is_readable($otca_css) ? (string) filemtime($otca_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-otc-article',
				ALEX_ROSE_2026_URI . '/assets/css/page-otc-article.css',
				array('alex-rose-2026-off-the-cuff'),
				$otca_ver
			);

			$otc_js = ALEX_ROSE_2026_DIR . '/assets/js/page-off-the-cuff.js';
			if (is_readable($otc_js)) {
				wp_enqueue_script(
					'alex-rose-2026-off-the-cuff',
					ALEX_ROSE_2026_URI . '/assets/js/page-off-the-cuff.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($otc_js),
					true
				);
			}
		}

		if (is_page_template('template/request-tape-measure.php')) {
			$rtm_css = ALEX_ROSE_2026_DIR . '/assets/css/page-request-tape-measure.css';
			$rtm_ver = is_readable($rtm_css) ? (string) filemtime($rtm_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-request-tape-measure',
				ALEX_ROSE_2026_URI . '/assets/css/page-request-tape-measure.css',
				array('alex-rose-2026'),
				$rtm_ver
			);

			$rtm_js = ALEX_ROSE_2026_DIR . '/assets/js/page-request-tape-measure.js';
			if (is_readable($rtm_js)) {
				wp_enqueue_script(
					'alex-rose-2026-request-tape-measure',
					ALEX_ROSE_2026_URI . '/assets/js/page-request-tape-measure.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($rtm_js),
					true
				);
			}
		}

		if (is_page_template('template/request-cloth-samples.php')) {
			$rcs_css = ALEX_ROSE_2026_DIR . '/assets/css/page-request-cloth-samples.css';
			$rcs_ver = is_readable($rcs_css) ? (string) filemtime($rcs_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-request-cloth-samples',
				ALEX_ROSE_2026_URI . '/assets/css/page-request-cloth-samples.css',
				array('alex-rose-2026'),
				$rcs_ver
			);

			$rcs_js = ALEX_ROSE_2026_DIR . '/assets/js/page-request-cloth-samples.js';
			if (is_readable($rcs_js)) {
				wp_enqueue_script(
					'alex-rose-2026-request-cloth-samples',
					ALEX_ROSE_2026_URI . '/assets/js/page-request-cloth-samples.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($rcs_js),
					true
				);
			}
		}

		if (is_page_template('template/schedule-a-call.php')) {
			$sac_css = ALEX_ROSE_2026_DIR . '/assets/css/page-schedule-a-call.css';
			$sac_ver = is_readable($sac_css) ? (string) filemtime($sac_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-schedule-a-call',
				ALEX_ROSE_2026_URI . '/assets/css/page-schedule-a-call.css',
				array('alex-rose-2026'),
				$sac_ver
			);

			$sac_js = ALEX_ROSE_2026_DIR . '/assets/js/page-schedule-a-call.js';
			if (is_readable($sac_js)) {
				wp_enqueue_script(
					'alex-rose-2026-schedule-a-call',
					ALEX_ROSE_2026_URI . '/assets/js/page-schedule-a-call.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($sac_js),
					true
				);
			}
		}

		if (is_page_template('template/send-measurements.php')) {
			$sm_css = ALEX_ROSE_2026_DIR . '/assets/css/page-send-measurements.css';
			$sm_ver = is_readable($sm_css) ? (string) filemtime($sm_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-send-measurements',
				ALEX_ROSE_2026_URI . '/assets/css/page-send-measurements.css',
				array('alex-rose-2026'),
				$sm_ver
			);

			$sm_js = ALEX_ROSE_2026_DIR . '/assets/js/page-send-measurements.js';
			if (is_readable($sm_js)) {
				wp_enqueue_script(
					'alex-rose-2026-send-measurements',
					ALEX_ROSE_2026_URI . '/assets/js/page-send-measurements.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($sm_js),
					true
				);
			}
		}

		if (is_page_template('template/post-your-jacket.php')) {
			$pyj_css = ALEX_ROSE_2026_DIR . '/assets/css/page-post-your-jacket.css';
			$pyj_ver = is_readable($pyj_css) ? (string) filemtime($pyj_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-post-your-jacket',
				ALEX_ROSE_2026_URI . '/assets/css/page-post-your-jacket.css',
				array('alex-rose-2026'),
				$pyj_ver
			);

			$pyj_js = ALEX_ROSE_2026_DIR . '/assets/js/page-post-your-jacket.js';
			if (is_readable($pyj_js)) {
				wp_enqueue_script(
					'alex-rose-2026-post-your-jacket',
					ALEX_ROSE_2026_URI . '/assets/js/page-post-your-jacket.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($pyj_js),
					true
				);
			}
		}

		if (is_page_template('template/showroom.php')) {
			$sr_css = ALEX_ROSE_2026_DIR . '/assets/css/page-showroom.css';
			$sr_ver = is_readable($sr_css) ? (string) filemtime($sr_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-showroom',
				ALEX_ROSE_2026_URI . '/assets/css/page-showroom.css',
				array('alex-rose-2026'),
				$sr_ver
			);
		}

		if (is_page_template('template/contact.php')) {
			$ct_css = ALEX_ROSE_2026_DIR . '/assets/css/page-contact.css';
			$ct_ver = is_readable($ct_css) ? (string) filemtime($ct_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-contact',
				ALEX_ROSE_2026_URI . '/assets/css/page-contact.css',
				array('alex-rose-2026'),
				$ct_ver
			);

			$ct_js = ALEX_ROSE_2026_DIR . '/assets/js/page-contact.js';
			if (is_readable($ct_js)) {
				wp_enqueue_script(
					'alex-rose-2026-contact',
					ALEX_ROSE_2026_URI . '/assets/js/page-contact.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($ct_js),
					true
				);
			}
		}

		if (is_page_template('template/gift-vouchers.php')) {
			$gv_css = ALEX_ROSE_2026_DIR . '/assets/css/page-gift-vouchers.css';
			$gv_ver = is_readable($gv_css) ? (string) filemtime($gv_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-gift-vouchers',
				ALEX_ROSE_2026_URI . '/assets/css/page-gift-vouchers.css',
				array('alex-rose-2026'),
				$gv_ver
			);

			$gv_js = ALEX_ROSE_2026_DIR . '/assets/js/page-gift-vouchers.js';
			if (is_readable($gv_js)) {
				wp_enqueue_script(
					'alex-rose-2026-gift-vouchers',
					ALEX_ROSE_2026_URI . '/assets/js/page-gift-vouchers.js',
					array('alex-rose-2026-form-submit'),
					(string) filemtime($gv_js),
					true
				);
			}
		}

		if (is_page_template('template/occasion.php')) {
			$occ_css = ALEX_ROSE_2026_DIR . '/assets/css/page-occasion.css';
			$occ_ver = is_readable($occ_css) ? (string) filemtime($occ_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-occasion',
				ALEX_ROSE_2026_URI . '/assets/css/page-occasion.css',
				array('alex-rose-2026'),
				$occ_ver
			);
		}

		if (is_page_template('template/faq.php')) {
			$faq_css = ALEX_ROSE_2026_DIR . '/assets/css/page-faq.css';
			$faq_ver = is_readable($faq_css) ? (string) filemtime($faq_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-faq',
				ALEX_ROSE_2026_URI . '/assets/css/page-faq.css',
				array('alex-rose-2026'),
				$faq_ver
			);

			$faq_js = ALEX_ROSE_2026_DIR . '/assets/js/page-faq.js';
			if (is_readable($faq_js)) {
				wp_enqueue_script(
					'alex-rose-2026-faq',
					ALEX_ROSE_2026_URI . '/assets/js/page-faq.js',
					array(),
					(string) filemtime($faq_js),
					true
				);
			}
		}

		if (is_page_template('template/delivery-information.php')) {
			$di_css = ALEX_ROSE_2026_DIR . '/assets/css/page-delivery-information.css';
			$di_ver = is_readable($di_css) ? (string) filemtime($di_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-delivery-information',
				ALEX_ROSE_2026_URI . '/assets/css/page-delivery-information.css',
				array('alex-rose-2026'),
				$di_ver
			);
		}

		if (is_page_template('template/privacy-policy.php')) {
			$pp_css = ALEX_ROSE_2026_DIR . '/assets/css/page-privacy-policy.css';
			$pp_ver = is_readable($pp_css) ? (string) filemtime($pp_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-privacy-policy',
				ALEX_ROSE_2026_URI . '/assets/css/page-privacy-policy.css',
				array('alex-rose-2026'),
				$pp_ver
			);
		}

		if (is_page_template('template/terms-and-conditions.php')) {
			$tc_css = ALEX_ROSE_2026_DIR . '/assets/css/page-terms-and-conditions.css';
			$tc_ver = is_readable($tc_css) ? (string) filemtime($tc_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-terms-and-conditions',
				ALEX_ROSE_2026_URI . '/assets/css/page-terms-and-conditions.css',
				array('alex-rose-2026'),
				$tc_ver
			);
		}

		if (is_page_template('template/our-story.php')) {
			$os_css = ALEX_ROSE_2026_DIR . '/assets/css/page-our-story.css';
			$os_ver = is_readable($os_css) ? (string) filemtime($os_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-our-story',
				ALEX_ROSE_2026_URI . '/assets/css/page-our-story.css',
				array('alex-rose-2026'),
				$os_ver
			);

			$os_js = ALEX_ROSE_2026_DIR . '/assets/js/page-our-story.js';
			if (is_readable($os_js)) {
				wp_enqueue_script(
					'alex-rose-2026-our-story',
					ALEX_ROSE_2026_URI . '/assets/js/page-our-story.js',
					array(),
					(string) filemtime($os_js),
					true
				);
			}
		}

		if (alex_rose_2026_is_dist_template()) {
			$dist_css = ALEX_ROSE_2026_DIR . '/assets/css/page-dist.css';
			$dist_ver = is_readable($dist_css) ? (string) filemtime($dist_css) : ALEX_ROSE_2026_VERSION;
			wp_enqueue_style(
				'alex-rose-2026-dist',
				ALEX_ROSE_2026_URI . '/assets/css/page-dist.css',
				array('alex-rose-2026'),
				$dist_ver
			);

			$dist_js = ALEX_ROSE_2026_DIR . '/assets/js/page-dist.js';
			if (is_readable($dist_js)) {
				wp_enqueue_script(
					'alex-rose-2026-dist',
					ALEX_ROSE_2026_URI . '/assets/js/page-dist.js',
					array(),
					(string) filemtime($dist_js),
					true
				);
			}
		}

		$header_js = ALEX_ROSE_2026_DIR . '/assets/js/header.js';
		if (is_readable($header_js)) {
			wp_enqueue_script(
				'alex-rose-2026-header',
				ALEX_ROSE_2026_URI . '/assets/js/header.js',
				array(),
				(string) filemtime($header_js),
				true
			);
		}
	}
);

/**
 * Allow modern image formats (WebP, AVIF) in the Media Library and force
 * WordPress to trust the extension when the real-MIME sniff returns false
 * on hosts where the GD/Imagick libraries do not advertise the format.
 */
add_filter(
	'upload_mimes',
	static function (array $mimes): array {
		$mimes['webp'] = 'image/webp';
		$mimes['avif'] = 'image/avif';
		return $mimes;
	}
);

add_filter(
	'wp_check_filetype_and_ext',
	static function (array $data, string $file, string $filename, $mimes) {
		if (! empty($data['ext']) && ! empty($data['type'])) {
			return $data;
		}

		$ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
		if ($ext === 'webp') {
			$data['ext']             = 'webp';
			$data['type']            = 'image/webp';
			$data['proper_filename'] = $filename;
		} elseif ($ext === 'avif') {
			$data['ext']             = 'avif';
			$data['type']            = 'image/avif';
			$data['proper_filename'] = $filename;
		}

		return $data;
	},
	10,
	4
);

/**
 * Front-end form recipients. Every enquiry, sample request, gift voucher
 * order, etc. is delivered to all of the addresses in this list.
 */
add_filter(
	'alex_rose_2026_form_recipient',
	static function (): string {
		$recipients = array(
			'mastertailorteam@gmail.com',
		);
		return implode(', ', $recipients);
	}
);

if (function_exists('acf_get_setting')) {
	add_filter(
		'acf/settings/save_json',
		static function (string $path): string {
			return ALEX_ROSE_2026_DIR . '/acf-json';
		}
	);

	add_filter(
		'acf/settings/load_json',
		static function (array $paths): array {
			$paths[] = ALEX_ROSE_2026_DIR . '/acf-json';
			return $paths;
		}
	);
}
