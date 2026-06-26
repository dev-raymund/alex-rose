<?php
/**
 * Homepage markup — hero, stats, occasions.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="home-hero">
	<img class="home-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-4.jpg')); ?>" alt="<?php echo esc_attr__('Made-to-measure jacket by Alex Rose Fine Tailoring', 'alex-rose-2026'); ?>" loading="eager" width="1920" height="1080">
	<div class="home-hero__shade" aria-hidden="true"></div>
	<div class="home-hero__shade-bottom" aria-hidden="true"></div>
	<div class="home-hero__inner home-gutters">
		<p class="home-hero__kicker"><?php esc_html_e('Specialists in Made-to-Measure Jackets', 'alex-rose-2026'); ?></p>
		<div class="home-hero__spacer" aria-hidden="true"></div>
		<div class="home-hero__copy">
			<h1 class="home-hero__title">
				<?php echo esc_html__('Custom Jackets', 'alex-rose-2026'); ?><br>
				<?php echo esc_html__('Designed to Fit', 'alex-rose-2026'); ?><br>
				<?php echo esc_html__('You Properly.', 'alex-rose-2026'); ?>
			</h1>
			<p class="home-hero__lead">
				<?php echo esc_html__('Design your jacket online. Refine the fit in person.', 'alex-rose-2026'); ?><br>
				<?php echo esc_html__('Made for how you live and dress.', 'alex-rose-2026'); ?>
			</p>
			<div class="home-hero__actions">
				<a class="home-btn-gold" href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?></a>
				<a class="home-link-quiet" href="<?php echo esc_url(home_url('/schedule-a-call')); ?>">
					<span><?php esc_html_e('Book a Consultation', 'alex-rose-2026'); ?></span>
					<span class="home-link-quiet__arrow" aria-hidden="true">→</span>
				</a>
			</div>
		</div>
	</div>
	<div class="home-scroll-indicator" aria-hidden="true">
		<div class="home-scroll-indicator__track"><div class="home-scroll-indicator__bar"></div></div>
	</div>
</section>

<div class="home-stats">
	<div class="home-stats__track">
		<div class="home-stats__cell">
			<span class="home-stats__num">1945</span>
			<span class="home-stats__label"><?php esc_html_e('Founded', 'alex-rose-2026'); ?></span>
		</div>
		<div class="home-stats__cell">
			<span class="home-stats__num">100%</span>
			<span class="home-stats__label"><?php esc_html_e('Made to Measure', 'alex-rose-2026'); ?></span>
		</div>
		<div class="home-stats__cell">
			<span class="home-stats__num">£595</span>
			<span class="home-stats__label"><?php esc_html_e('Jackets From', 'alex-rose-2026'); ?></span>
		</div>
		<div class="home-stats__cell">
			<span class="home-stats__num"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></span>
			<span class="home-stats__label"><?php esc_html_e('Free Cloth Samples', 'alex-rose-2026'); ?></span>
		</div>
		<div class="home-stats__cell">
			<span class="home-stats__num"><?php esc_html_e('Yes', 'alex-rose-2026'); ?></span>
			<span class="home-stats__label"><?php esc_html_e('Free Discovery Call', 'alex-rose-2026'); ?></span>
		</div>
		<div class="home-stats__cell">
			<span class="home-stats__num"><?php esc_html_e('Worldwide', 'alex-rose-2026'); ?></span>
			<span class="home-stats__label"><?php esc_html_e('Delivered', 'alex-rose-2026'); ?></span>
		</div>
	</div>
</div>

<?php
$reviews = array(
	array(
		'quote' => __('Harold was a referral from a friend when I needed a tailored suit for my wedding. The convenience of being tailored in your own home is what is needed in this day and age.', 'alex-rose-2026'),
		'tag'   => __('Wedding customer', 'alex-rose-2026'),
	),
	array(
		'quote' => __('Excellent attention to detail, everything correct, first time. Harold entertains whilst providing any information you could want about tailoring and the manufacturing process.', 'alex-rose-2026'),
		'tag'   => __('Savile Row client', 'alex-rose-2026'),
	),
	array(
		'quote' => __('Very impressed with the attention to detail and the choices I was provided with. Over the moon with how it fits, feels and looks.', 'alex-rose-2026'),
		'tag'   => __('New job suit', 'alex-rose-2026'),
	),
	array(
		'quote' => __('Highly professional, knowledgeable, reactive and a super quick service. First time experience for me and very impressed. I would highly recommend.', 'alex-rose-2026'),
		'tag'   => __('First-time customer', 'alex-rose-2026'),
	),
	array(
		'quote' => __('Outstanding. Extensive experience, really knows the business of tailoring. Excellent customer service and a pleasure to deal with.', 'alex-rose-2026'),
		'tag'   => __('Verified customer', 'alex-rose-2026'),
	),
	array(
		'quote' => __('Harold is delightful and professional. A great eye for cut. His latest incarnation of a navy blue three piece is simply superb.', 'alex-rose-2026'),
		'tag'   => __('Three-piece suit customer', 'alex-rose-2026'),
	),
);
$total_reviews = count($reviews);

$star_svg = '<svg width="9" height="9" viewBox="0 0 24 24" fill="#C8A96A" aria-hidden="true" focusable="false"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

$stars_row = function () use ($star_svg): string {
	return '<span class="home-reviews__stars" aria-hidden="true">' . str_repeat($star_svg, 5) . '</span>';
};
?>
<aside class="home-reviews" aria-label="<?php esc_attr_e('Customer reviews', 'alex-rose-2026'); ?>">
	<div class="home-reviews__row">
		<div class="home-reviews__summary">
			<?php echo $stars_row(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<p class="home-reviews__rating">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: average rating out of 5, e.g. 4.85 */
						__('%s / 5', 'alex-rose-2026'),
						'4.85'
					)
				);
				?>
			</p>
			<p class="home-reviews__count">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: number of verified reviews */
						_n('%d Verified Review', '%d Verified Reviews', $total_reviews >= 38 ? $total_reviews : 38, 'alex-rose-2026'),
						38
					)
				);
				?>
			</p>
		</div>

		<div class="home-reviews__carousel" data-home-reviews>
			<div class="home-reviews__track" data-home-reviews-track>
				<?php foreach ($reviews as $i => $review) : ?>
					<article class="home-reviews__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-home-reviews-slide="<?php echo esc_attr((string) $i); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
						<?php echo $stars_row(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<blockquote class="home-reviews__quote"><?php echo esc_html('"' . $review['quote'] . '"'); ?></blockquote>
						<p class="home-reviews__tag"><?php echo esc_html((string) $review['tag']); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="home-reviews__dots" role="tablist" aria-label="<?php esc_attr_e('Choose a review', 'alex-rose-2026'); ?>">
				<?php foreach ($reviews as $i => $review) : ?>
					<button
						type="button"
						class="home-reviews__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-controls="home-reviews-slide-<?php echo esc_attr((string) $i); ?>"
						data-home-reviews-dot="<?php echo esc_attr((string) $i); ?>"
						aria-label="<?php
						/* translators: 1: review index, 2: total reviews */
						echo esc_attr(sprintf(__('Show review %1$d of %2$d', 'alex-rose-2026'), $i + 1, $total_reviews));
						?>"
					></button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="home-reviews__brands">
			<a class="home-reviews__brand" href="https://www.reviews.io/company-reviews/store/alexander-rose" target="_blank" rel="noopener noreferrer">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
					<circle cx="12" cy="12" r="10" fill="#00B67A"></circle>
					<path d="M12 7l1.39 2.82 3.11.45-2.25 2.19.53 3.09L12 14.1l-2.78 1.46.53-3.09L7.5 10.27l3.11-.45L12 7z" fill="#fff"></path>
				</svg>
				<span class="home-reviews__brand-label"><?php esc_html_e('Reviews.io', 'alex-rose-2026'); ?></span>
			</a>
			<a class="home-reviews__brand" href="https://www.google.com/search?q=alexander+rose+fine+tailoring+reviews#lrd=0x48795c18b655df6d:0x2366c3077c5bcd7,1" target="_blank" rel="noopener noreferrer">
				<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
					<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
					<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
					<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
				</svg>
				<span class="home-reviews__brand-label"><?php esc_html_e('Google', 'alex-rose-2026'); ?></span>
			</a>
		</div>
	</div>
</aside>

<section class="home-occasions" id="occasions">
	<div class="home-occasions__intro">
		<p class="home-occasions__kicker"><?php esc_html_e('Occasions', 'alex-rose-2026'); ?></p>
		<h2 class="home-occasions__title"><?php esc_html_e('A jacket for every occasion.', 'alex-rose-2026'); ?></h2>
	</div>
	<div class="home-occasions__grid-wrap">
		<div class="home-occasions__grid">
			<a class="home-occ-card" href="<?php echo esc_url(home_url('/occasions/business')); ?>">
				<span class="home-occ-card__media" aria-hidden="true">
					<img class="home-occ-card__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/occasion-business.jpg')); ?>" alt="" loading="lazy" width="600" height="800">
				</span>
				<span class="home-occ-card__shade" aria-hidden="true"></span>
				<div class="home-occ-card__body">
					<p class="home-occ-card__tags"><?php esc_html_e('Office · Meetings · Travel', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__title-wrap">
						<h3 class="home-occ-card__title"><?php esc_html_e('Business & Smart Casual', 'alex-rose-2026'); ?></h3>
					</div>
					<p class="home-occ-card__desc"><?php esc_html_e('Precision fit for the modern professional. A jacket that carries you from boardroom to bar without missing a step.', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__row">
						<span class="home-occ-card__explore"><?php esc_html_e('Explore', 'alex-rose-2026'); ?></span>
					</div>
				</div>
			</a>
			<a class="home-occ-card" href="<?php echo esc_url(home_url('/occasions/evening')); ?>">
				<span class="home-occ-card__media" aria-hidden="true">
					<img class="home-occ-card__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-6.jpg')); ?>" alt="" loading="lazy" width="600" height="800">
				</span>
				<span class="home-occ-card__shade" aria-hidden="true"></span>
				<div class="home-occ-card__body">
					<p class="home-occ-card__tags"><?php esc_html_e('Galas · Dinners · Events', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__title-wrap">
						<h3 class="home-occ-card__title"><?php esc_html_e('Evening & Statement', 'alex-rose-2026'); ?></h3>
					</div>
					<p class="home-occ-card__desc"><?php esc_html_e('Bold cloth, sharp lines, and presence for the moments that matter. When the dress code calls for more than ordinary.', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__row">
						<span class="home-occ-card__explore"><?php esc_html_e('Explore', 'alex-rose-2026'); ?></span>
					</div>
				</div>
			</a>
			<a class="home-occ-card" href="<?php echo esc_url(home_url('/occasions/seasonal')); ?>">
				<span class="home-occ-card__media" aria-hidden="true">
					<img class="home-occ-card__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-5.jpg')); ?>" alt="" loading="lazy" width="600" height="800">
				</span>
				<span class="home-occ-card__shade" aria-hidden="true"></span>
				<div class="home-occ-card__body">
					<p class="home-occ-card__tags"><?php esc_html_e('Spring · Autumn · Layering', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__title-wrap">
						<h3 class="home-occ-card__title"><?php esc_html_e('Seasonal Jackets', 'alex-rose-2026'); ?></h3>
					</div>
					<p class="home-occ-card__desc"><?php esc_html_e('Weight, texture, and colour tuned to the season—so your jacket feels right the day it arrives and years later.', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__row">
						<span class="home-occ-card__explore"><?php esc_html_e('Explore', 'alex-rose-2026'); ?></span>
					</div>
				</div>
			</a>
			<a class="home-occ-card" href="<?php echo esc_url(home_url('/occasions/country')); ?>">
				<span class="home-occ-card__media" aria-hidden="true">
					<img class="home-occ-card__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/lifestyle-9.jpg')); ?>" alt="" loading="lazy" width="600" height="800">
				</span>
				<span class="home-occ-card__shade" aria-hidden="true"></span>
				<div class="home-occ-card__body">
					<p class="home-occ-card__tags"><?php esc_html_e('Weekends · Field · Country', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__title-wrap">
						<h3 class="home-occ-card__title"><?php esc_html_e('Country & Heritage', 'alex-rose-2026'); ?></h3>
					</div>
					<p class="home-occ-card__desc"><?php esc_html_e('Relaxed tailoring with character—tweeds, checks, and cloth that looks as good in town as it does outdoors.', 'alex-rose-2026'); ?></p>
					<div class="home-occ-card__row">
						<span class="home-occ-card__explore"><?php esc_html_e('Explore', 'alex-rose-2026'); ?></span>
					</div>
				</div>
			</a>
		</div>
	</div>
</section>
