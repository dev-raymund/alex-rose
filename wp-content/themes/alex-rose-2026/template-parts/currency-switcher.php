<?php
/**
 * Global display-currency switcher (fixed right-edge pill).
 *
 * Rendered into wp_footer on every front-end view (except the configurator,
 * which has its own in-canvas rail). Behaviour lives in assets/js/currency.js.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$ar_currencies = array('GBP', 'EUR', 'USD');
?>
<div class="ar-currency" role="group" aria-label="<?php esc_attr_e('Display currency', 'alex-rose-2026'); ?>">
	<?php foreach ($ar_currencies as $ar_cur) : ?>
		<?php $ar_default = ('GBP' === $ar_cur); ?>
		<button type="button" class="ar-currency__btn<?php echo $ar_default ? ' is-active' : ''; ?>" data-ar-currency="<?php echo esc_attr($ar_cur); ?>" aria-pressed="<?php echo $ar_default ? 'true' : 'false'; ?>"><?php echo esc_html($ar_cur); ?></button>
	<?php endforeach; ?>
</div>
