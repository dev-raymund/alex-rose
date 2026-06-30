<?php
/**
 * Homepage — story bands, testimonials, journal, final CTA.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="home-story home-story--cream" id="story">
	<div class="home-story__media">
		<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/alexander-rose-factory-hd.png')); ?>" alt="<?php echo esc_attr__('Alexander Rose Ltd factory, archive', 'alex-rose-2026'); ?>" loading="lazy" width="1200" height="900">
		<div class="home-story__caption"><p><?php esc_html_e('c. 1950s', 'alex-rose-2026'); ?></p></div>
	</div>
	<div class="home-story__body">
		<div class="home-story__inner">
			<p class="home-split__kicker"><?php esc_html_e('Our Heritage', 'alex-rose-2026'); ?></p>
			<h2 class="home-story__h2"><?php echo esc_html__('Eighty years of', 'alex-rose-2026'); ?><br><?php echo esc_html__('making it right.', 'alex-rose-2026'); ?></h2>
			<p class="home-story__p"><?php esc_html_e('Alexander Rose started this business with a needle, a table, and a belief that every man deserves a jacket built for him. That belief has been carried forward for eighty years.', 'alex-rose-2026'); ?></p>
			<a class="home-story__link" href="<?php echo esc_url(home_url('/our-story')); ?>"><?php esc_html_e('Our Story', 'alex-rose-2026'); ?></a>
		</div>
	</div>
</section>

<section class="home-story home-story--reverse home-story--cream">
	<div class="home-story__media">
		<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/story-jacket.jpg')); ?>" alt="<?php echo esc_attr__('Made-to-measure jacket by Alex Rose', 'alex-rose-2026'); ?>" loading="lazy" width="1200" height="900">
	</div>
	<div class="home-story__body">
		<div class="home-story__inner">
			<p class="home-split__kicker"><?php esc_html_e('Ethical Made-to-Measure', 'alex-rose-2026'); ?></p>
			<h2 class="home-story__h2"><?php echo esc_html__('Wear it for twenty years,', 'alex-rose-2026'); ?><br><?php echo esc_html__('not twenty weeks.', 'alex-rose-2026'); ?></h2>
			<p class="home-story__p"><?php esc_html_e('A made-to-measure jacket costs more than a high-street suit and lasts five times as long. Natural fibres and a single perfect fit, made exactly for you. That is not just good quality. It is good sense.', 'alex-rose-2026'); ?></p>
		</div>
	</div>
</section>

<section class="home-story home-story--dark home-story--body-first">
	<div class="home-story__body">
		<div class="home-story__inner">
			<p class="home-story__kicker"><?php esc_html_e('Personal, One-to-One Service', 'alex-rose-2026'); ?></p>
			<h2 class="home-story__h2"><?php echo esc_html__('You speak directly with your', 'alex-rose-2026'); ?><br><?php echo esc_html__('master tailor.', 'alex-rose-2026'); ?></h2>
			<p class="home-story__p"><?php esc_html_e('Every enquiry is read personally. Every order is reviewed by our tailor before a single thread is cut. That is how it has always worked at Alex Rose. And that is how it will always work.', 'alex-rose-2026'); ?></p>
			<p class="home-story__sig"><?php esc_html_e('Your Master Tailor · Harold Rose', 'alex-rose-2026'); ?></p>
		</div>
	</div>
	<div class="home-story__media home-story__media--tailor">
		<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/harold2.jpg')); ?>" alt="<?php echo esc_attr__('Your master tailor, Alex Rose Fine Tailoring', 'alex-rose-2026'); ?>" loading="lazy" width="1200" height="900">
	</div>
</section>

<section class="home-testimonials" id="testimonials">
	<div class="home-testimonials__inner">
		<div class="home-testimonials__head">
			<div>
				<p class="home-occasions__kicker"><?php esc_html_e('What Our Clients Say', 'alex-rose-2026'); ?></p>
				<h2 class="home-occasions__title"><?php esc_html_e('Worn with confidence.', 'alex-rose-2026'); ?></h2>
			</div>
			<div class="home-testimonials__links">
				<a class="home-testimonials__out" href="https://www.reviews.io/company-reviews/store/alexander-rose" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Reviews.io →', 'alex-rose-2026'); ?></a>
				<a class="home-testimonials__out" href="https://www.google.com/search?q=alexander+rose+fine+tailoring+reviews#lrd=0x48795c18b655df6d:0x2366c3077c5bcd7,1" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Google →', 'alex-rose-2026'); ?></a>
			</div>
		</div>
		<div class="home-testimonials__grid">
			<div class="home-testimonial">
				<div class="home-testimonial__who">
					<div class="home-testimonial__avatar"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/client-1.png')); ?>" alt="" width="56" height="56"></div>
					<div>
						<p class="home-testimonial__name"><?php esc_html_e('Pankaj Madan', 'alex-rose-2026'); ?></p>
						<p class="home-testimonial__meta"><?php esc_html_e('Client since 2012', 'alex-rose-2026'); ?></p>
					</div>
				</div>
				<div class="home-testimonial__stars" aria-hidden="true"><?php for ($i = 0; $i < 5; $i++) : ?><span class="home-star">★</span><?php endfor; ?></div>
				<div class="home-testimonial__rule" aria-hidden="true"></div>
				<blockquote class="home-testimonial__quote"><?php esc_html_e('“"Harold was a referral from a friend when I needed a tailored suit for my wedding. The convenience of being tailored in your own home is what is needed in this”', 'alex-rose-2026'); ?></blockquote>
			</div>
			<div class="home-testimonial">
				<div class="home-testimonial__who">
					<div class="home-testimonial__avatar"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/client-2.png')); ?>" alt="" width="56" height="56"></div>
					<div>
						<p class="home-testimonial__name"><?php esc_html_e('Raj Singh', 'alex-rose-2026'); ?></p>
						<p class="home-testimonial__meta"><?php esc_html_e('Client since 2004', 'alex-rose-2026'); ?></p>
					</div>
				</div>
				<div class="home-testimonial__stars" aria-hidden="true"><?php for ($i = 0; $i < 5; $i++) : ?><span class="home-star">★</span><?php endfor; ?></div>
				<div class="home-testimonial__rule" aria-hidden="true"></div>
				<blockquote class="home-testimonial__quote"><?php esc_html_e('“Harold Rose has been making jackets for me for over 20 years and they are the best fitting clothes I own. Great fabrics, quality tailoring, and they last and last.”', 'alex-rose-2026'); ?></blockquote>
			</div>
			<div class="home-testimonial">
				<div class="home-testimonial__who">
					<div class="home-testimonial__avatar"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/client-3.png')); ?>" alt="" width="56" height="56"></div>
					<div>
						<p class="home-testimonial__name"><?php esc_html_e('Jovi Overo', 'alex-rose-2026'); ?></p>
						<p class="home-testimonial__meta"><?php esc_html_e('Verified client', 'alex-rose-2026'); ?></p>
					</div>
				</div>
				<div class="home-testimonial__stars" aria-hidden="true"><?php for ($i = 0; $i < 5; $i++) : ?><span class="home-star">★</span><?php endfor; ?></div>
				<div class="home-testimonial__rule" aria-hidden="true"></div>
				<blockquote class="home-testimonial__quote"><?php esc_html_e('“Been a client for most of my adult life and will continue to be a client until I die. Can think of no finer jacket to be buried in!”', 'alex-rose-2026'); ?></blockquote>
			</div>
		</div>
		<div class="home-testimonials__bottom">
			<p class="home-testimonials__bottom-note"><?php esc_html_e('Trusted by clients across the UK and internationally.', 'alex-rose-2026'); ?></p>
			<a class="home-testimonials__bottom-link" href="<?php echo esc_url(home_url('/design')); ?>">
				<span><?php esc_html_e('Start Your Design', 'alex-rose-2026'); ?></span>
				<span aria-hidden="true">→</span>
			</a>
		</div>
	</div>
</section>

<section class="home-journal">
	<div class="home-journal__inner">
		<div class="home-journal__head-row">
			<div>
				<p class="home-occasions__kicker"><?php esc_html_e('The Alex Rose Journal', 'alex-rose-2026'); ?></p>
				<h2 class="home-occasions__title"><?php esc_html_e('Off the Cuff.', 'alex-rose-2026'); ?></h2>
			</div>
			<a class="home-testimonials__out" style="color: rgba(0,0,0,0.4);" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>">
				<span><?php esc_html_e('All Articles', 'alex-rose-2026'); ?></span> <span aria-hidden="true">→</span>
			</a>
		</div>
		<div class="home-journal__grid">
			<a class="home-journal__feature" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>">
				<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-4.jpg')); ?>" alt="" width="900" height="600">
				<div class="home-journal__feature-shade" aria-hidden="true"></div>
				<div class="home-journal__feature-body">
					<div class="flex items-center gap-12">
						<span class="home-journal__side-tag" style="border:1px solid rgba(200,169,106,0.38);padding:4px 8px;background:rgba(0,0,0,0.4);color:var(--ar-gold);"><?php esc_html_e('Featured', 'alex-rose-2026'); ?></span>
						<span style="font-size:9px;text-transform:uppercase;letter-spacing:0.18em;color:rgba(255,255,255,0.5);"><?php esc_html_e('Fit Guide · April 2025 · 4 min read', 'alex-rose-2026'); ?></span>
					</div>
					<div class="max-w-lg" style="max-width:32rem;">
						<h3><?php esc_html_e('How to Measure Yourself for a Made-to-Measure Jacket', 'alex-rose-2026'); ?></h3>
						<p><?php esc_html_e('A step-by-step guide to taking the four key measurements your master tailor needs, chest, shoulders, sleeve, and body length. Get this right and the rest follows.', 'alex-rose-2026'); ?></p>
						<span class="home-journal__read-pill"><?php esc_html_e('Read Article', 'alex-rose-2026'); ?> →</span>
					</div>
				</div>
			</a>
			<div>
				<p style="font-size:9px;text-transform:uppercase;letter-spacing:0.18em;color:rgba(0,0,0,0.35);margin:0 0 8px;"><?php esc_html_e('More From the Journal', 'alex-rose-2026'); ?></p>
				<a class="home-journal__side-item" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>">
					<div class="home-journal__side-thumb"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-2.jpg')); ?>" alt="" width="72" height="72"></div>
					<div class="home-journal__side-body">
						<div class="home-journal__side-meta">
							<span class="home-journal__side-tag"><?php esc_html_e('The Craft', 'alex-rose-2026'); ?></span>
							<span class="home-journal__side-time"><?php esc_html_e('5 min', 'alex-rose-2026'); ?></span>
						</div>
						<h4 class="home-journal__side-title"><?php esc_html_e('Notch, Peak or Shawl: Choosing the Right Lapel', 'alex-rose-2026'); ?></h4>
						<p class="home-journal__side-excerpt home-line-clamp-2"><?php esc_html_e('Lapel shape is the single most visible design choice on a jacket. We break down when to use each style.', 'alex-rose-2026'); ?></p>
					</div>
				</a>
				<a class="home-journal__side-item" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>">
					<div class="home-journal__side-thumb"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-9.jpg')); ?>" alt="" width="72" height="72"></div>
					<div class="home-journal__side-body">
						<div class="home-journal__side-meta">
							<span class="home-journal__side-tag"><?php esc_html_e('Cloth', 'alex-rose-2026'); ?></span>
							<span class="home-journal__side-time"><?php esc_html_e('6 min', 'alex-rose-2026'); ?></span>
						</div>
						<h4 class="home-journal__side-title"><?php esc_html_e('The British Mills Behind Our Cloths', 'alex-rose-2026'); ?></h4>
						<p class="home-journal__side-excerpt home-line-clamp-2"><?php esc_html_e('From the Yorkshire Dales to the Outer Hebrides, the mills supplying Alex Rose have been weaving cloth for generations.', 'alex-rose-2026'); ?></p>
					</div>
				</a>
				<a class="home-journal__side-item" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>">
					<div class="home-journal__side-thumb"><img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-5.jpg')); ?>" alt="" width="72" height="72"></div>
					<div class="home-journal__side-body">
						<div class="home-journal__side-meta">
							<span class="home-journal__side-tag"><?php esc_html_e('Fit Guide', 'alex-rose-2026'); ?></span>
							<span class="home-journal__side-time"><?php esc_html_e('4 min', 'alex-rose-2026'); ?></span>
						</div>
						<h4 class="home-journal__side-title"><?php esc_html_e('What Good Fit Actually Looks Like', 'alex-rose-2026'); ?></h4>
						<p class="home-journal__side-excerpt home-line-clamp-2"><?php esc_html_e('Most men have never worn a truly well-fitting jacket. Here is what to look for, and what to ask your tailor.', 'alex-rose-2026'); ?></p>
					</div>
				</a>
				<a class="home-journal__view-all" href="<?php echo esc_url(home_url('/off-the-cuff')); ?>"><?php esc_html_e('View All Articles →', 'alex-rose-2026'); ?></a>
			</div>
		</div>
	</div>
</section>

<section class="home-cta">
	<div class="home-cta__text">
		<div class="home-cta__inner">
			<p class="home-cta__kicker"><?php esc_html_e('Free · No Obligation', 'alex-rose-2026'); ?></p>
			<h2 class="home-cta__h2"><?php echo esc_html__('Create Your', 'alex-rose-2026'); ?><br><?php echo esc_html__('Made-to-Measure Jacket.', 'alex-rose-2026'); ?></h2>
			<p class="home-cta__p"><?php esc_html_e('Your master tailor reads every enquiry personally and responds within one working day.', 'alex-rose-2026'); ?></p>
			<div class="home-cta__actions">
				<a class="home-btn-gold" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?></a>
				<a class="home-cta__phone" href="tel:+441134688588">
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
					+44 (0)113 468 8588
				</a>
			</div>
			<div class="home-cta__bullets">
				<div class="home-cta__bullet"><?php esc_html_e('Free consultation, no obligation', 'alex-rose-2026'); ?></div>
				<div class="home-cta__bullet"><?php
				printf(
					/* translators: %s: starting price */
					esc_html__('Jackets from %s, fully made to measure', 'alex-rose-2026'),
					alex_rose_2026_price_html(595) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?></div>
				<div class="home-cta__bullet"><?php esc_html_e('Free cloth samples posted to you', 'alex-rose-2026'); ?></div>
			</div>
		</div>
	</div>
	<div class="home-cta__media">
		<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-6.jpg')); ?>" alt="<?php echo esc_attr__('Made-to-measure jacket', 'alex-rose-2026'); ?>" loading="lazy" width="1200" height="900">
	</div>
</section>
