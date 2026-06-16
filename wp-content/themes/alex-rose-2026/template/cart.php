<?php
/**
 * Template Name: Cart
 *
 * Assign under Page → Template. Renders the "Reserved Jackets" cart page.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
get_template_part('template-parts/cart/markup');
get_footer();
