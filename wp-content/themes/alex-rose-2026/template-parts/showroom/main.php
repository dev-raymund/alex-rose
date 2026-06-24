<?php
/**
 * "Showroom" — contact details, map, and exterior photo.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$map_embed = 'https://maps.google.com/maps?q=2A+Rodley+Lane%2C+Rodley%2C+Leeds%2C+LS13+1HU&t=&z=16&ie=UTF8&iwloc=&output=embed';
$map_link  = 'https://maps.app.goo.gl/HYRidwuVoByMUkjU7';
?>
<section class="sr-main">
	<div class="sr-main__inner ar-container ar-container--6xl">
		<div class="sr-main__grid">
			<div class="sr-main__info-col">
				<div class="sr-details">
					<div class="sr-details__block">
						<p class="sr-details__label"><?php esc_html_e('Address', 'alex-rose-2026'); ?></p>
						<address class="sr-details__address">
							<?php
							echo wp_kses(
								__('2A Rodley Lane<br>Rodley, Leeds<br>LS13 1HU<br>West Yorkshire, England', 'alex-rose-2026'),
								array('br' => array())
							);
							?>
						</address>
					</div>

					<div class="sr-details__block">
						<p class="sr-details__label"><?php esc_html_e('Telephone', 'alex-rose-2026'); ?></p>
						<a class="sr-details__link" href="tel:+01134688588">0113 468 8588</a>
						<a class="sr-details__link" href="tel:+01132571145">0113 257 1145</a>
						<p class="sr-details__note"><?php esc_html_e('By prior appointment', 'alex-rose-2026'); ?></p>
					</div>

					<div class="sr-details__block">
						<p class="sr-details__label"><?php esc_html_e('Email', 'alex-rose-2026'); ?></p>
						<a class="sr-details__link" href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>
						<p class="sr-details__note"><?php esc_html_e('We respond within 24 hours', 'alex-rose-2026'); ?></p>
					</div>

					<div class="sr-details__block">
						<p class="sr-details__label"><?php esc_html_e('Opening Hours', 'alex-rose-2026'); ?></p>
						<p class="sr-details__text"><?php esc_html_e('By prior appointment', 'alex-rose-2026'); ?></p>
						<p class="sr-details__text"><?php esc_html_e('Wednesday – Saturday', 'alex-rose-2026'); ?></p>
						<p class="sr-details__text"><?php esc_html_e('10.00 am – 5.30 pm', 'alex-rose-2026'); ?></p>
					</div>
				</div>

				<div class="sr-map-wrap">
					<div class="sr-map">
						<iframe
							class="sr-map__iframe"
							title="<?php esc_attr_e('Alex Rose Fine Tailoring, 2A Rodley Lane, Leeds LS13 1HU', 'alex-rose-2026'); ?>"
							src="<?php echo esc_url($map_embed); ?>"
							width="100%"
							height="100%"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
						></iframe>
					</div>
					<a class="sr-map__link" href="<?php echo esc_url($map_link); ?>" target="_blank" rel="noopener noreferrer">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<circle cx="12" cy="10" r="3"></circle>
							<path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 14 8 14s8-8.75 8-14a8 8 0 0 0-8-8z"></path>
						</svg>
						<?php esc_html_e('Open in Google Maps →', 'alex-rose-2026'); ?>
					</a>
				</div>
			</div>

			<div class="sr-main__photo-col">
				<div class="sr-photo">
					<img
						class="sr-photo__img"
						src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/showroom-exterior.png')); ?>"
						alt="<?php esc_attr_e('Alex Rose Fine Tailoring showroom on Rodley Lane, Leeds', 'alex-rose-2026'); ?>"
						loading="lazy"
					>
				</div>
			</div>
		</div>
	</div>
</section>
