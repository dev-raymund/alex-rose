<?php
/**
 * "Showroom" — page wrapper.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-showroom" tabindex="-1">
	<?php
	get_template_part('template-parts/showroom/hero');
	get_template_part('template-parts/showroom/main');
	get_template_part('template-parts/showroom/expect');
	get_template_part('template-parts/showroom/cta');
	?>
</main>
