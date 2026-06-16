<?php
/**
 * "Our Story" — Meet Harold section.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="os-harold">
	<div class="os-harold__inner ar-container ar-container--6xl">
		<div class="os-harold__media">
			<div class="os-harold__media-frame">
				<img class="os-harold__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/harold2.jpg')); ?>" alt="<?php esc_attr_e('Harold Rose, Master Tailor', 'alex-rose-2026'); ?>" loading="lazy">
				<span class="os-harold__corner os-harold__corner--tl-h" aria-hidden="true"></span>
				<span class="os-harold__corner os-harold__corner--tl-v" aria-hidden="true"></span>
				<span class="os-harold__corner os-harold__corner--br-h" aria-hidden="true"></span>
				<span class="os-harold__corner os-harold__corner--br-v" aria-hidden="true"></span>
			</div>
			<p class="os-harold__caption"><?php esc_html_e('Harold Rose · Master Tailor', 'alex-rose-2026'); ?></p>
		</div>

		<div class="os-harold__body">
			<p class="os-harold__kicker"><?php esc_html_e('Meet Harold', 'alex-rose-2026'); ?></p>
			<h1 class="os-harold__title">
				<?php
				echo wp_kses(
					__('The man behind<br>every jacket.', 'alex-rose-2026'),
					array('br' => array())
				);
				?>
			</h1>
			<div class="os-harold__paragraphs">
				<p><?php esc_html_e('Harold Rose grew up inside the family tailoring business his father Alexander founded in 1945, learning the trade from the inside out before becoming managing director in his own right.', 'alex-rose-2026'); ?></p>
				<p><?php esc_html_e('Today, Harold reviews every order personally, confirms every detail with you directly, and remains available throughout. There is no intermediary. You are working with the tailor.', 'alex-rose-2026'); ?></p>
			</div>
		</div>
	</div>
</section>
