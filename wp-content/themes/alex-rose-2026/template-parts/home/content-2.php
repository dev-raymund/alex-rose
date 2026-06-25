<?php
/**
 * Homepage — configurator, how it works, client gallery.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="home-split">
	<div class="home-split__inner">
		<div class="home-split__row">
			<div class="home-split__media">
				<div class="home-split__video-box">
					<video src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/how-it-works.mp4')); ?>" controls playsinline preload="metadata" poster="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-4.jpg')); ?>"></video>
					<div class="home-split__corner home-split__corner--tl-h" aria-hidden="true"></div>
					<div class="home-split__corner home-split__corner--tl-v" aria-hidden="true"></div>
					<div class="home-split__corner home-split__corner--br-h" aria-hidden="true"></div>
					<div class="home-split__corner home-split__corner--br-v" aria-hidden="true"></div>
					<div class="home-split__video-cap">
						<p><?php esc_html_e('The configurator in action · Alex Rose Fine Tailoring', 'alex-rose-2026'); ?></p>
					</div>
				</div>
			</div>
			<div class="home-split__text">
				<p class="home-split__kicker"><?php esc_html_e('The Configurator', 'alex-rose-2026'); ?></p>
				<h2 class="home-split__h2"><?php echo esc_html__('Design every', 'alex-rose-2026'); ?><br><?php echo esc_html__('detail.', 'alex-rose-2026'); ?></h2>
				<p class="home-split__p"><?php esc_html_e('Every choice is yours: lapels, cloth, buttons, lining. Design your jacket online, then your master tailor takes care of the rest.', 'alex-rose-2026'); ?></p>
				<div class="home-split__btn-wrap">
					<a class="home-btn-gold" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Start Your Design', 'alex-rose-2026'); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="home-how" id="how-it-works">
	<div class="home-how__inner">
		<div class="home-how__head">
			<div>
				<p class="home-how__kicker"><?php esc_html_e('A Personal Approach', 'alex-rose-2026'); ?></p>
				<h2 class="home-how__title"><?php esc_html_e('Four steps to your perfect jacket.', 'alex-rose-2026'); ?></h2>
			</div>
			<a class="home-how__guide" href="<?php echo esc_url(home_url('/how-it-works')); ?>"><?php esc_html_e('Full guide', 'alex-rose-2026'); ?></a>
		</div>
		<div class="home-how__grid">
			<div class="home-how__cell">
				<div class="home-how__cell-inner">
					<div class="home-how__icon" aria-hidden="true">
						<svg viewBox="0 0 72 72" fill="none"><rect x="14" y="10" width="32" height="40" rx="2" stroke="#C8A96A" stroke-width="2.2"></rect><rect x="14" y="10" width="32" height="9" rx="2" fill="#C8A96A" fill-opacity="0.18" stroke="#C8A96A" stroke-width="2.2"></rect><line x1="21" y1="27" x2="39" y2="27" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="21" y1="33" x2="36" y2="33" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="21" y1="39" x2="32" y2="39" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><circle cx="50" cy="54" r="9" fill="#C8A96A" fill-opacity="0.15" stroke="#C8A96A" stroke-width="2.2"></circle><path d="M46 58 L48 52 L54 52 L54 58Z" fill="#C8A96A" fill-opacity="0.35"></path><line x1="48" y1="52" x2="54" y2="52" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="46" y1="58" x2="54" y2="58" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line></svg>
					</div>
					<p class="home-how__step">01</p>
					<div class="home-how__rule" aria-hidden="true"></div>
					<h3 class="home-how__h3"><?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?></h3>
					<p class="home-how__desc"><?php esc_html_e('Select cloth, lapels, cut, buttons, and lining online at your own pace.', 'alex-rose-2026'); ?></p>
				</div>
			</div>
			<div class="home-how__cell">
				<div class="home-how__cell-inner">
					<div class="home-how__icon" aria-hidden="true">
						<svg viewBox="0 0 72 72" fill="none"><path d="M12 36 Q12 20 36 20 Q60 20 60 36 Q60 52 36 52 Q12 52 12 36Z" stroke="#C8A96A" stroke-width="2.2" fill="#C8A96A" fill-opacity="0.1"></path><line x1="12" y1="36" x2="60" y2="36" stroke="#C8A96A" stroke-width="1.2" stroke-opacity="0.5"></line><line x1="20" y1="36" x2="20" y2="31" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="28" y1="36" x2="28" y2="32" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="36" y1="36" x2="36" y2="29" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="44" y1="36" x2="44" y2="32" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><line x1="52" y1="36" x2="52" y2="31" stroke="#C8A96A" stroke-width="2" stroke-linecap="round"></line><circle cx="36" cy="36" r="3.5" fill="#C8A96A"></circle><circle cx="36" cy="12" r="2.5" fill="#C8A96A"></circle><circle cx="36" cy="60" r="2.5" fill="#C8A96A"></circle></svg>
					</div>
					<p class="home-how__step">02</p>
					<div class="home-how__rule" aria-hidden="true"></div>
					<h3 class="home-how__h3"><?php esc_html_e('Get Measured', 'alex-rose-2026'); ?></h3>
					<p class="home-how__desc"><?php esc_html_e('Measure yourself at home with our easy-to-follow guide, or book a call on Teams or Google Meet and we will talk you through it. You can also post your own jacket so we can measure it for you.', 'alex-rose-2026'); ?></p>
				</div>
			</div>
			<div class="home-how__cell">
				<div class="home-how__cell-inner">
					<div class="home-how__icon" aria-hidden="true">
						<svg viewBox="0 0 72 72" fill="none"><path d="M20 56 Q30 38 38 28" stroke="#C8A96A" stroke-width="2.2" stroke-linecap="round"></path><path d="M38 28 L47 14" stroke="#C8A96A" stroke-width="2.4" stroke-linecap="round"></path><ellipse cx="47.5" cy="13" rx="4.5" ry="3" transform="rotate(-22 47.5 13)" stroke="#C8A96A" stroke-width="2" fill="#C8A96A" fill-opacity="0.2"></ellipse><circle cx="20.5" cy="56.5" r="4" fill="#C8A96A" fill-opacity="0.25" stroke="#C8A96A" stroke-width="2.2"></circle><path d="M44 38 Q49 31 55 38 Q61 45 55 51 Q49 57 44 50 Q38 43 44 38Z" stroke="#C8A96A" stroke-width="2.2" fill="#C8A96A" fill-opacity="0.12"></path><line x1="50" y1="38" x2="50" y2="51" stroke="#C8A96A" stroke-width="1.2" stroke-opacity="0.55" stroke-linecap="round"></line><line x1="42" y1="44.5" x2="58" y2="44.5" stroke="#C8A96A" stroke-width="1.2" stroke-opacity="0.55" stroke-linecap="round"></line></svg>
					</div>
					<p class="home-how__step">03</p>
					<div class="home-how__rule" aria-hidden="true"></div>
					<h3 class="home-how__h3"><?php esc_html_e('Made to Order', 'alex-rose-2026'); ?></h3>
					<p class="home-how__desc"><?php esc_html_e('Your jacket is made to your exact specification by skilled tailors trusted by many Savile Row tailors in London.', 'alex-rose-2026'); ?></p>
				</div>
			</div>
			<div class="home-how__cell">
				<div class="home-how__cell-inner">
					<div class="home-how__icon" aria-hidden="true">
						<svg viewBox="0 0 72 72" fill="none"><path d="M36 8 L36 15" stroke="#C8A96A" stroke-width="2.2" stroke-linecap="round"></path><path d="M27 15 Q36 11 45 15" stroke="#C8A96A" stroke-width="2.2" stroke-linecap="round"></path><path d="M27 15 L16 28 L16 62 L56 62 L56 28 L45 15" stroke="#C8A96A" stroke-width="2.2" stroke-linejoin="round"></path><path d="M27 15 Q31 24 36 26 Q41 24 45 15" stroke="#C8A96A" stroke-width="2" fill="#C8A96A" fill-opacity="0.15"></path><line x1="16" y1="42" x2="56" y2="42" stroke="#C8A96A" stroke-width="1.4" stroke-opacity="0.4" stroke-linecap="round"></line><circle cx="54" cy="22" r="6" fill="#C8A96A" fill-opacity="0.25" stroke="#C8A96A" stroke-width="2.2"></circle><path d="M51 22 L53.2 24.5 L57.5 19.5" stroke="#C8A96A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
					</div>
					<p class="home-how__step">04</p>
					<div class="home-how__rule" aria-hidden="true"></div>
					<h3 class="home-how__h3"><?php esc_html_e('Your Jacket Arrives', 'alex-rose-2026'); ?></h3>
					<p class="home-how__desc"><?php esc_html_e('Your jacket is delivered to your door. If it does not fit you perfectly, reach out to us. We prioritise your perfect fit and comfort above all else.

', 'alex-rose-2026'); ?></p>
				</div>
			</div>
		</div>
		<div class="home-how__cta">
			<a class="home-btn-gold" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Start Designing', 'alex-rose-2026'); ?></a>
		</div>
	</div>
</section>

<section class="home-clients">
	<div class="home-clients__head home-gutters">
		<div>
			<p class="home-clients__kicker"><?php esc_html_e('Past Clients', 'alex-rose-2026'); ?></p>
			<h2 class="home-clients__title"><?php esc_html_e('Jackets we have made.', 'alex-rose-2026'); ?></h2>
		</div>
		<div class="home-clients__arrows">
			<button type="button" class="home-clients__arrow" data-dir="prev" aria-label="<?php esc_attr_e('Scroll left', 'alex-rose-2026'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
			</button>
			<button type="button" class="home-clients__arrow" data-dir="next" aria-label="<?php esc_attr_e('Scroll right', 'alex-rose-2026'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
			</button>
		</div>
	</div>
	<div class="home-clients__strip-wrap">
		<div class="home-clients__strip">
			<?php
			$clients = array(
				array('file' => 'client-2.png', 'pos' => '50% 15%', 'tag' => __('Business', 'alex-rose-2026')),
				array('file' => 'client-3.png', 'pos' => '50% 12%', 'tag' => __('Evening', 'alex-rose-2026')),
				array('file' => 'client-9.jpg', 'pos' => '50% 12%', 'tag' => __('Statement', 'alex-rose-2026')),
				array('file' => 'client-10.jpg', 'pos' => '50% 15%', 'tag' => __('Business', 'alex-rose-2026')),
				array('file' => 'client-11.jpg', 'pos' => '50% 12%', 'tag' => __('Evening', 'alex-rose-2026')),
			);
			foreach ($clients as $i => $c ) :
				$img_url = alex_rose_2026_uploads_url('2026/05/' . $c['file']);
				?>
				<article
					class="home-client-card"
					data-client-index="<?php echo esc_attr((string) $i); ?>"
					data-client-tag="<?php echo esc_attr($c['tag']); ?>"
					data-client-src="<?php echo esc_url($img_url); ?>"
					data-client-pos="<?php echo esc_attr($c['pos']); ?>"
				>
					<img class="home-client-card__img" style="object-position: <?php echo esc_attr($c['pos']); ?>;" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr__('Client in a made-to-measure Alex Rose jacket', 'alex-rose-2026'); ?>" loading="lazy" width="400" height="533">
					<button type="button" class="home-client-card__zoom" aria-label="<?php esc_attr_e('Zoom in', 'alex-rose-2026'); ?>" data-client-zoom>
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<circle cx="11" cy="11" r="7"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							<line x1="11" y1="8" x2="11" y2="14"></line>
							<line x1="8" y1="11" x2="14" y2="11"></line>
						</svg>
					</button>
					<span class="home-client-card__tag"><?php echo esc_html($c['tag']); ?></span>
				</article>
				<?php
			endforeach;
			?>
		</div>
		<div class="home-clients__strip-edge home-clients__strip-edge--left" data-scroll-edge="prev" aria-hidden="true"></div>
		<div class="home-clients__strip-edge home-clients__strip-edge--right" data-scroll-edge="next" aria-hidden="true"></div>
	</div>
	<div class="home-clients__foot">
		<p class="home-clients__foot-note"><?php esc_html_e('Each jacket made to your exact measurements', 'alex-rose-2026'); ?></p>
		<a class="home-clients__foot-link" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design Yours →', 'alex-rose-2026'); ?></a>
	</div>

	<div class="home-client-spotlight" id="home-client-spotlight" hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Client jacket gallery', 'alex-rose-2026'); ?>">
		<div class="home-client-spotlight__backdrop" data-client-spotlight-close tabindex="-1"></div>
		<button type="button" class="home-client-spotlight__close" data-client-spotlight-close aria-label="<?php esc_attr_e('Close', 'alex-rose-2026'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
		<button type="button" class="home-client-spotlight__nav home-client-spotlight__nav--prev" data-client-spotlight-prev aria-label="<?php esc_attr_e('Previous image', 'alex-rose-2026'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m15 18-6-6 6-6"></path></svg>
		</button>
		<button type="button" class="home-client-spotlight__nav home-client-spotlight__nav--next" data-client-spotlight-next aria-label="<?php esc_attr_e('Next image', 'alex-rose-2026'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 18 6-6-6-6"></path></svg>
		</button>
		<figure class="home-client-spotlight__figure">
			<img class="home-client-spotlight__img" src="" alt="<?php echo esc_attr__('Client in a made-to-measure Alex Rose jacket', 'alex-rose-2026'); ?>">
			<figcaption class="home-client-spotlight__caption"></figcaption>
		</figure>
	</div>
</section>
