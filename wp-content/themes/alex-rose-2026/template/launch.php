<?php
/**
 * RETIRED — the 20% founding-member promotion has ended.
 *
 * The `Template Name` header is deliberately removed so WordPress no longer
 * offers this template in Page Attributes and any page still assigned to it
 * falls back to the default page template. The file is kept intact so the
 * campaign can be revived: restore the two header lines quoted below, verbatim
 * and unquoted, to re-register it. They are quoted here on purpose — WordPress
 * matches its file headers with `^[ \t\/*#@]*Template Name:`, which a plain
 * commented-out line would still satisfy, re-registering the template.
 *
 * >   "Template Name: Launch"
 * >   "Template Post Type: page"
 *
 * Reviving it also needs: the enqueue block in functions.php, 'template/launch.php'
 * back in alex_rose_2026_form_templates(), and the lp_join_waitlist handler in
 * inc/forms.php.
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
