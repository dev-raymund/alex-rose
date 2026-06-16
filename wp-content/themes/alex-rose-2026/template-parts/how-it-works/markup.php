<?php
/**
 * Shared "How It Works" markup.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-hiw" tabindex="-1">
	<?php
	get_template_part('template-parts/how-it-works/hero');
	get_template_part('template-parts/how-it-works/steps');
	?>
</main>
