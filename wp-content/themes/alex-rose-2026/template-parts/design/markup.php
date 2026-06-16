<?php
/**
 * Shared "Design Your Jacket" markup.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-design" tabindex="-1">
	<div class="design-layout">
		<?php
		get_template_part('template-parts/design/preview');
		get_template_part('template-parts/design/config');
		?>
	</div>
</main>
