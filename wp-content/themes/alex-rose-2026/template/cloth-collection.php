<?php
/**
 * Template Name: Cloth Collection
 * Template Post Type: page
 *
 * Assign this template to a child page of /cloths/ (e.g. /cloths/english-riviera/)
 * whose slug matches a key in inc/cloth-collections.php. The page slug drives
 * which collection is rendered.
 *
 * @package Alex_Rose_2026
 */

get_header();
get_template_part('template-parts/cloth-collection/markup');
get_footer();
