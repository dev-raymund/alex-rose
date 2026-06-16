<?php
/**
 * "Gift Vouchers" — "What's included" 2-column section.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$included = array(
	__("Each jacket is carefully crafted to the wearer's exact measurements", 'alex-rose-2026'),
	__('They can choose their own style, cloth, lining and buttons', 'alex-rose-2026'),
	__('We will even monogram their name in the jacket lining', 'alex-rose-2026'),
	__('Choice of over 150 fine British cloths', 'alex-rose-2026'),
	__('We even send free cloth samples on request', 'alex-rose-2026'),
	__('Our unique online measuring system ensures a perfect fit', 'alex-rose-2026'),
	__('We despatch worldwide', 'alex-rose-2026'),
	__('The prices are from £595 to £800', 'alex-rose-2026'),
	__('Buying a gift voucher is very easy, just fill in the contact form', 'alex-rose-2026'),
	__('We will call or email you', 'alex-rose-2026'),
);

$check_svg = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 6 9 17l-5-5"></path></svg>';
?>
<section class="gv-included">
	<div class="gv-included__inner ar-container ar-container--6xl">
		<div class="gv-included__grid">
			<div class="gv-included__media">
				<img class="gv-included__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/gift-voucher.jpg')); ?>" alt="<?php esc_attr_e('Alex Rose Gift Voucher in envelope', 'alex-rose-2026'); ?>" loading="lazy">
			</div>

			<div class="gv-included__body">
				<p class="gv-included__kicker"><?php esc_html_e("What's included", 'alex-rose-2026'); ?></p>
				<h2 class="gv-included__title">
					<?php
					echo wp_kses(
						__('You Make It Yours,<br>We Tailor It.', 'alex-rose-2026'),
						array('br' => array())
					);
					?>
				</h2>
				<ul class="gv-included__list">
					<?php foreach ($included as $item) : ?>
						<li class="gv-included__item">
							<span class="gv-included__icon" aria-hidden="true"><?php echo $check_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<p class="gv-included__text"><?php echo esc_html($item); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
