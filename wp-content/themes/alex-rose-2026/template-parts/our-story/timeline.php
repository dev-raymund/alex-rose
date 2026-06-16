<?php
/**
 * "Our Story" — interactive timeline (18 milestones).
 *
 * Each entry has a year, a small tag, a title, a paragraph and a related image.
 * The track and detail panel are wired up by assets/js/page-our-story.js.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$milestones = array(
	array(
		'year'  => 1945,
		'tag'   => __('Foundation', 'alex-rose-2026'),
		'title' => __('Where it all began.', 'alex-rose-2026'),
		'body'  => __('Alexander Rose left the army in 1945 and decided to open a small clothing factory in Cross Harrison Street behind the Grand Theatre in Leeds.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1945.jpg'),
	),
	array(
		'year'  => 1948,
		'tag'   => __('First Factory', 'alex-rose-2026'),
		'title' => __('Trafalgar Works.', 'alex-rose-2026'),
		'body'  => __('In 1948 the business expanded into a former school. This was in Elmwood Street very near the Leeds City centre.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1948.jpg'),
	),
	array(
		'year'  => 1952,
		'tag'   => __('Incorporation', 'alex-rose-2026'),
		'title' => __('Alexander Rose Ltd.', 'alex-rose-2026'),
		'body'  => __('In 1952 we completed a factory extension, the extra space was used for a cutting room and garment pressing and finishing.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1952.jpg'),
	),
	array(
		'year'  => 1956,
		'tag'   => __('Growth', 'alex-rose-2026'),
		'title' => __('Rooted in the community.', 'alex-rose-2026'),
		'body'  => __('In 1956 the company opened number two factory in Allerton Bywater in the former Miners Welfare Club building. We needed the extra capacity to cope with increased volume of orders.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1956.jpg'),
	),
	array(
		'year'  => 1965,
		'tag'   => __('Expansion', 'alex-rose-2026'),
		'title' => __('400 cloths. One standard.', 'alex-rose-2026'),
		'body'  => __('In 1965 the company opened the third factory in Featherstone, production now approaching 40,000 made to measure suits per year. At this point we were supplying around 350 menswear retailers in the UK. There was also a cutting room in Sunderland based in the Boiler Makers Hall.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1965.jpg'),
	),
	array(
		'year'  => 1972,
		'tag'   => __('Craft', 'alex-rose-2026'),
		'title' => __('Harold earns his diploma.', 'alex-rose-2026'),
		'body'  => __("In 1972 Alexander's son Harold Rose becomes managing Director of Alexander Rose Ltd carrying on the family business into the next generation.", 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1972.jpg'),
	),
	array(
		'year'  => 1975,
		'tag'   => __('Peak Production', 'alex-rose-2026'),
		'title' => __('A factory at full stretch.', 'alex-rose-2026'),
		'body'  => __('In 1975 number two factory is extended (and all production is now on one site). The factory was now producing around 1000 jackets & 2000 trousers per week all high fashion ready to wear.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1975.jpg'),
	),
	array(
		'year'  => 1979,
		'tag'   => __('Modernisation', 'alex-rose-2026'),
		'title' => __('Confidence in the future.', 'alex-rose-2026'),
		'body'  => __('Design Council grant enables the factory to be "engineered" using Swedish Consultants. Complete new production methods and garment handling systems. We needed to compete with European manufacturers.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1979.jpg'),
	),
	array(
		'year'  => 1980,
		'tag'   => __('Recognition', 'alex-rose-2026'),
		'title' => __('A model factory.', 'alex-rose-2026'),
		'body'  => __('Acknowledged to be one of the most modern factories in the UK, now supplying high fashion menswear to major store groups and retailers throughout the UK. The British Clothing Manufacturer commended Alexander Rose Ltd for its open doors policy in allowing other companies to visit and see a modern UK clothing factory. We wanted to encourage other companies to follow our lead.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1980.jpg'),
	),
	array(
		'year'  => 1981,
		'tag'   => __('Anniversary', 'alex-rose-2026'),
		'title' => __('Twenty-five years.', 'alex-rose-2026'),
		'body'  => __('25th anniversary of the Allerton Bywater factory, many of the employees had been working in the factory from 1956.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1981.jpg'),
	),
	array(
		'year'  => 1982,
		'tag'   => __('Production', 'alex-rose-2026'),
		'title' => __('The showroom floor.', 'alex-rose-2026'),
		'body'  => __('Cut jackets waiting to go into production.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1982.jpg'),
	),
	array(
		'year'  => 1984,
		'tag'   => __('Scale', 'alex-rose-2026'),
		'title' => __('Craftsmanship at scale.', 'alex-rose-2026'),
		'body'  => __('The jacket production line showing the hanging rail system and various work aids to reduce handling time.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1984.jpg'),
	),
	array(
		'year'  => 1986,
		'tag'   => __('New Venture', 'alex-rose-2026'),
		'title' => __('Executive Image.', 'alex-rose-2026'),
		'body'  => __('Sale of the factory to Harbarry Garments of Manchester, there were precisely 132 machinists whose jobs were fully secured. Established Executive Image supplying high quality corporate clothing to hotels, casinos, house builders etc. including Hilton Hotels, Stakis Casinoes, Bellway Homes, DHL International.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1986.jpg'),
	),
	array(
		'year'  => 1990,
		'tag'   => __('Personal Service', 'alex-rose-2026'),
		'title' => __('The personal touch.', 'alex-rose-2026'),
		'body'  => __('Executive Image supplied all the staff uniforms for the new Crowne Plaza Hotel in Leeds. For the official opening they wanted a young boy to hand over the scissors to the chairman of Whitbread to cut the "ribbon". We made my nephew Daniel a tailcoat in the same style and fabric to match both concierge and front of house staff.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-1990.jpg'),
	),
	array(
		'year'  => 2004,
		'tag'   => __('New Chapter', 'alex-rose-2026'),
		'title' => __('The Master Tailor.', 'alex-rose-2026'),
		'body'  => __('Sale of Executive Image to Corporate CMT. We saw that made to measure tailoring was becoming more of an engineered concept. Master Tailor was established to offer a visiting tailoring service offering garments made to a very high specification.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-2004.png'),
	),
	array(
		'year'  => 2013,
		'tag'   => __('Digital Era', 'alex-rose-2026'),
		'title' => __("Men's Clothing Room.", 'alex-rose-2026'),
		'body'  => __("Opened Mens Clothing Room an online ready to wear menswear store offering classical men's clothing from quintessential British brands.", 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-2013.jpg'),
	),
	array(
		'year'  => 2018,
		'tag'   => __('Legacy', 'alex-rose-2026'),
		'title' => __('Back to the beginning.', 'alex-rose-2026'),
		'body'  => __('We are proud of our family clothing heritage and therefore rebranded back to our original company name of Alexander Rose.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-2018.jpg'),
	),
	array(
		'year'  => 2024,
		'tag'   => __('Modern Legacy', 'alex-rose-2026'),
		'title' => __('Modern Legacy.', 'alex-rose-2026'),
		'body'  => __('Today, Alexander Rose continues to blend traditional craftsmanship with cutting-edge technology, serving discerning clients worldwide.', 'alex-rose-2026'),
		'image' => alex_rose_2026_uploads_url('2026/05/history-2024.png'),
	),
);

$active_index = 0;
$total        = count($milestones);
?>
<section class="os-timeline">
	<div class="os-timeline__inner ar-container ar-container--5xl">
		<header class="os-timeline__head">
			<p class="os-timeline__kicker"><?php esc_html_e('The Story So Far', 'alex-rose-2026'); ?></p>
			<h2 class="os-timeline__title"><?php esc_html_e('Eighty years in the making.', 'alex-rose-2026'); ?></h2>
		</header>

		<div class="os-timeline__track-scroll">
			<div class="os-timeline__track" role="tablist" aria-label="<?php esc_attr_e('Timeline years', 'alex-rose-2026'); ?>">
				<?php foreach ($milestones as $i => $m) : ?>
					<button
						type="button"
						class="os-tl-dot<?php echo $i === $active_index ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $i === $active_index ? 'true' : 'false'; ?>"
						aria-controls="os-tl-panel"
						data-os-year-index="<?php echo esc_attr((string) $i); ?>"
						aria-label="<?php
						/* translators: %d: year */
						echo esc_attr(sprintf(__('Go to %d', 'alex-rose-2026'), (int) $m['year']));
						?>"
					>
						<span class="os-tl-dot__line" aria-hidden="true"></span>
						<span class="os-tl-dot__bullet" aria-hidden="true"></span>
						<span class="os-tl-dot__year"><?php echo esc_html((string) $m['year']); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="os-tl-panel" class="os-timeline__panel" role="tabpanel" aria-live="polite">
			<?php foreach ($milestones as $i => $m) :
				$is_active = $i === $active_index;
				?>
				<div class="os-tl-slide<?php echo $is_active ? ' is-active' : ''; ?>" data-os-year-slide="<?php echo esc_attr((string) $i); ?>" <?php echo $is_active ? '' : 'hidden'; ?>>
					<div class="os-tl-slide__media">
						<img src="<?php echo esc_url($m['image']); ?>" alt="<?php
							/* translators: 1: year, 2: title */
							echo esc_attr(sprintf(__('%1$d, %2$s', 'alex-rose-2026'), (int) $m['year'], $m['title']));
						?>" loading="lazy">
					</div>
					<div class="os-tl-slide__body">
						<span class="os-tl-slide__tag"><?php echo esc_html($m['tag']); ?></span>
						<p class="os-tl-slide__year"><?php echo esc_html((string) $m['year']); ?></p>
						<h3 class="os-tl-slide__title"><?php echo esc_html($m['title']); ?></h3>
						<p class="os-tl-slide__body-text"><?php echo esc_html($m['body']); ?></p>

						<div class="os-tl-slide__nav">
							<button type="button" class="os-tl-nav os-tl-nav--prev" data-os-year-prev aria-label="<?php esc_attr_e('Previous milestone', 'alex-rose-2026'); ?>"<?php echo $i === 0 ? ' disabled' : ''; ?>>
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M7.5 2L3.5 6L7.5 10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
							</button>
							<button type="button" class="os-tl-nav os-tl-nav--next" data-os-year-next aria-label="<?php esc_attr_e('Next milestone', 'alex-rose-2026'); ?>"<?php echo $i === $total - 1 ? ' disabled' : ''; ?>>
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M4.5 2L8.5 6L4.5 10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
							</button>
							<span class="os-tl-slide__counter">
								<span data-os-year-current><?php echo esc_html((string) ($i + 1)); ?></span>
								<span aria-hidden="true">&nbsp;/&nbsp;</span>
								<?php echo esc_html((string) $total); ?>
							</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
