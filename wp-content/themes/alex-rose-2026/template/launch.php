<?php
/**
 * Template Name: Launch
 * Template Post Type: page
 *
 * Standalone founding-member landing page. Unlike the rest of the site it has
 * its own minimal chrome (logo + email header, CTA footer) instead of the
 * shared header/footer, so it renders a self-contained HTML document.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="site-html">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class('page-launch'); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/launch/markup'); ?>
<?php wp_footer(); ?>
</body>
</html>
