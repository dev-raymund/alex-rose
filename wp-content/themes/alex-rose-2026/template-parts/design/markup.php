<?php
/**
 * Design Your Jacket — SPA mount point.
 *
 * The page-design.js ES module renders the full configurator into #ar-design-app.
 * config.php and preview.php are kept on disk but are no longer loaded here.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<main id="main" class="page-design" tabindex="-1">
	<div id="ar-design-app"
		data-uploads-url="<?php echo esc_url(alex_rose_2026_uploads_url('')); ?>"
		data-schedule-url="<?php echo esc_url(home_url('/schedule-a-call/')); ?>"
		data-samples-url="<?php echo esc_url(home_url('/request-cloth-samples/')); ?>">
	</div>
</main>
