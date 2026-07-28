<?php
/**
 * Cookie Policy — page wrapper.
 *
 * Reuses the privacy-policy visual system (pp-* classes + .page-privacy-policy
 * root) so it inherits assets/css/page-privacy-policy.css with no new stylesheet.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-privacy-policy page-cookie-policy" tabindex="-1">
	<?php
	get_template_part('template-parts/cookie-policy/hero');
	get_template_part('template-parts/cookie-policy/content');
	?>
</main>
