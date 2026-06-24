<?php
/**
 * Header
 *
 * @package Alex_Rose_2026
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="site-html">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class('site'); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header" role="banner">
	<div class="site-header__bar">
		<a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
			<?php
			$logo_id = (int) get_theme_mod('custom_logo');
			if ($logo_id) {
				echo wp_get_attachment_image($logo_id, 'full', false, array('alt' => esc_attr(get_bloginfo('name'))));
			} else {
				?>
				<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/alex-rose-logo.png')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php
			}
			?>
		</a>
		<nav class="site-nav" aria-label="<?php esc_attr_e('Primary', 'alex-rose-2026'); ?>">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'primary',
				'menu_class'     => 'menu',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 1,
			));
			?>
		</nav>
		<a class="site-header__cart" href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart')); ?>" aria-label="<?php esc_attr_e('Cart', 'alex-rose-2026'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M16 10a4 4 0 0 1-8 0"></path>
				<path d="M3.103 6.034h17.794"></path>
				<path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path>
			</svg>
			<?php
			if (function_exists('WC') && WC()->cart) {
				$ar_cart_count = (int) WC()->cart->get_cart_contents_count();
				if ($ar_cart_count > 0) {
					echo '<span class="site-header__cart-count">' . esc_html($ar_cart_count) . '</span>';
				}
			}
			?>
		</a>
		<button type="button" class="site-header__toggle" aria-expanded="false" aria-controls="site-side-menu" aria-label="<?php esc_attr_e('Open menu', 'alex-rose-2026'); ?>" data-side-menu-toggle>
			<span class="site-header__toggle-bars" aria-hidden="true">
				<span></span><span></span><span></span>
			</span>
		</button>
	</div>
</header>

<aside id="site-side-menu" class="site-side-menu" aria-hidden="true" aria-label="<?php esc_attr_e('Site menu', 'alex-rose-2026'); ?>">
	<div class="site-side-menu__rule" aria-hidden="true"></div>
	<nav class="site-side-menu__nav" aria-label="<?php esc_attr_e('Sidebar', 'alex-rose-2026'); ?>">
		<?php
		wp_nav_menu(array(
			'theme_location' => 'sidebar',
			'menu_class'     => 'site-side-menu__list',
			'container'      => false,
			'fallback_cb'    => false,
			'depth'          => 2,
			'walker'         => new Alex_Rose_2026_Sidebar_Walker(),
		));
		?>
		<div class="site-side-menu__contact">
			<?php
			$sidebar_phone = get_theme_mod('alex_rose_2026_sidebar_phone', '+44 (0)113 468 8588');
			$sidebar_email = get_theme_mod('alex_rose_2026_sidebar_email', 'tailor@alexrose.uk');
			?>
			<?php if ($sidebar_phone) : ?>
				<a class="site-side-menu__contact-link" href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $sidebar_phone)); ?>"><?php echo esc_html($sidebar_phone); ?></a>
			<?php endif; ?>
			<?php if ($sidebar_email) : ?>
				<a class="site-side-menu__contact-link" href="mailto:<?php echo esc_attr($sidebar_email); ?>"><?php echo esc_html($sidebar_email); ?></a>
			<?php endif; ?>
		</div>
	</nav>
</aside>
