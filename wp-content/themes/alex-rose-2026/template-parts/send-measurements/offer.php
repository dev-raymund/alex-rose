<?php
/**
 * "Send Measurements" — "5% off for your feedback" offer block.
 *
 * Shared by the success views of the Measure Yourself and Post Us a Jacket
 * flows (success.php, post-us-a-jacket.php).
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="sm-offer">
	<div class="sm-offer__stripe" aria-hidden="true"></div>
	<div class="sm-offer__inner">
		<div class="sm-offer__main">
			<p class="sm-offer__eyebrow"><?php esc_html_e('Exclusive offer for our customers', 'alex-rose-2026'); ?></p>
			<h3 class="sm-offer__headline"><?php esc_html_e('5% off your next order.', 'alex-rose-2026'); ?><br><span class="sm-offer__accent"><?php esc_html_e('Just share your thoughts.', 'alex-rose-2026'); ?></span></h3>
			<p class="sm-offer__copy"><?php esc_html_e('Harold reads every response personally. Tell us what worked, what did not, and what you would change. We will send your 5% discount code straight back.', 'alex-rose-2026'); ?></p>
		</div>
		<div class="sm-offer__aside">
			<ul class="sm-offer__list">
				<?php foreach (array(
					__('Around five minutes', 'alex-rose-2026'),
					__('No obligation to purchase', 'alex-rose-2026'),
					__('5% code sent on submission', 'alex-rose-2026'),
				) as $sm_point) : ?>
					<li class="sm-offer__item">
						<svg class="sm-offer__star" xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="#C8A96A" stroke="#C8A96A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
						<span><?php echo esc_html($sm_point); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="sm-offer__btn" href="<?php echo esc_url(home_url('/feedback/')); ?>">
				<?php esc_html_e('Start feedback form', 'alex-rose-2026'); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
			</a>
			<p class="sm-offer__fine"><?php esc_html_e('Code valid on any jacket order. No expiry.', 'alex-rose-2026'); ?></p>
		</div>
	</div>
</div>
