<?php
/**
 * Occasion registry + helpers.
 *
 * One PHP-defined record per occasion, keyed by the WordPress page slug.
 * A page using template/occasion.php picks up its data based on the current
 * page's slug — so to add a new occasion you create a child page of
 * /occasions/ with a matching slug and add an entry below.
 *
 * Image paths point at wp-content/uploads/2026/05/ via
 * alex_rose_2026_uploads_url() so the user can drop in their own assets
 * without code changes.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * The full registry of occasions, keyed by page slug.
 *
 * @return array<string, array<string, mixed>>
 */
function alex_rose_2026_occasions(): array {
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}

	$cache = array(
		'business' => array(
			'title'           => __('Business & Smart Casual', 'alex-rose-2026'),
			'short_title'     => __('Business & Smart Casual', 'alex-rose-2026'),
			'eyebrow'         => __('Made-to-Measure Jackets · Alex Rose', 'alex-rose-2026'),
			'hero_image'      => alex_rose_2026_uploads_url('2026/05/lifestyle-4.jpg'),
			'hero_lead'       => __('A jacket that carries you from the boardroom to the bar without missing a step.', 'alex-rose-2026'),
			'related_kicker'  => __('Boardroom · Office · Travel', 'alex-rose-2026'),
			'when_kicker'     => __('The Occasion', 'alex-rose-2026'),
			'when_title'      => __('When to wear it.', 'alex-rose-2026'),
			'when_paragraph'  => __('The business jacket is the hardest-working piece in any wardrobe. It needs to sit correctly through a long day of meetings, move with you when you lean across a table, and look considered when you leave the office for something more relaxed in the evening. A made-to-measure jacket solves all of that in one go, because it is cut to your body and your life, not to a mannequin.', 'alex-rose-2026'),
			'when_bullets'    => array(
				__('Client meetings and presentations', 'alex-rose-2026'),
				__('Office wear, five days a week', 'alex-rose-2026'),
				__('Smart casual dinners and receptions', 'alex-rose-2026'),
				__('Travelling for work', 'alex-rose-2026'),
				__('Conferences and industry events', 'alex-rose-2026'),
				__('After-work drinks and informal occasions', 'alex-rose-2026'),
			),
			'samples_title'   => __('Made in this cloth and occasion.', 'alex-rose-2026'),
			'samples'         => array(
				alex_rose_2026_uploads_url('2026/05/business-1.jpg'),
				alex_rose_2026_uploads_url('2026/05/business-2.jpg'),
				alex_rose_2026_uploads_url('2026/05/business-3.jpg'),
			),
			'cloths_title'    => __('Cloths we recommend.', 'alex-rose-2026'),
			'cloths'          => array(
				array(
					'slug'   => 'cotswold',
					'name'   => __('The Cotswold Collection', 'alex-rose-2026'),
					'kicker' => __('Lightweight, wrinkle-resistant', 'alex-rose-2026'),
					'price'  => __('From £595', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-cotswold.webp'),
				),
				array(
					'slug'   => 'travel-blazer',
					'name'   => __('The International Travel Blazer', 'alex-rose-2026'),
					'kicker' => __('Travel-proof, holds its shape', 'alex-rose-2026'),
					'price'  => __('From £625', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-travel.webp'),
				),
				array(
					'slug'   => 'english-blazer',
					'name'   => __('The English Blazer Collection', 'alex-rose-2026'),
					'kicker' => __('Classic structured blazer', 'alex-rose-2026'),
					'price'  => __('From £595', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-english-blazer.jpg'),
				),
			),
			'thinking_title'    => __('How we think about this jacket.', 'alex-rose-2026'),
			'thinking_paragraphs' => array(
				__('For a business jacket, cloth weight matters more than most people realise. Something too heavy becomes uncomfortable on a warm day or in a heated meeting room. Something too light loses its structure by lunchtime. Our Cotswold Collection sits in the right middle ground: light enough to wear all day, substantial enough to press well and hold its shape from morning to evening.', 'alex-rose-2026'),
				__('The details make a business jacket work. A notch lapel at the right width. Flap pockets that sit flat against the body. A single vent that opens cleanly when you sit down. Two buttons, fastened and unfastened correctly without thinking about it. These are small things that, when right, disappear entirely and let you get on with your day.', 'alex-rose-2026'),
				__('Smart casual is simply business without the constraint. Our customers who order for both purposes often choose a cloth with a little more character, a subtle check or a tonal texture, so the jacket reads differently depending on how it is worn. With trousers and a shirt, it is formal. With dark jeans and an open collar, it is relaxed and considered.', 'alex-rose-2026'),
			),
			'pull_quote'     => __('The best jacket you own is the one you reach for without thinking.', 'alex-rose-2026'),
			'cta_title'      => __('Design your business jacket.', 'alex-rose-2026'),
			'cta_lead'       => __('Tell us how you use your jacket and we will help you choose the right cloth and cut from the start.', 'alex-rose-2026'),
		),

		'evening' => array(
			'title'           => __('Evening & Statement', 'alex-rose-2026'),
			'short_title'     => __('Evening & Statement', 'alex-rose-2026'),
			'eyebrow'         => __('Made-to-Measure Jackets · Alex Rose', 'alex-rose-2026'),
			'hero_image'      => alex_rose_2026_uploads_url('2026/05/lifestyle-6.jpg'),
			'hero_lead'       => __('A jacket cut for the occasions you remember, with the formality the evening deserves.', 'alex-rose-2026'),
			'related_kicker'  => __('Events · Occasions · Celebrations', 'alex-rose-2026'),
			'when_kicker'     => __('The Occasion', 'alex-rose-2026'),
			'when_title'      => __('When to wear it.', 'alex-rose-2026'),
			'when_paragraph'  => __('An evening jacket should make the case for you the moment you walk into a room. Cut from a cloth with weight and shadow, finished with quiet detail, it does the formal work without ever raising its voice. We make jackets for the kind of evenings you remember the next morning.', 'alex-rose-2026'),
			'when_bullets'    => array(
				__('Black-tie dinners and award evenings', 'alex-rose-2026'),
				__('Weddings, christenings and family occasions', 'alex-rose-2026'),
				__('Anniversary dinners and milestone birthdays', 'alex-rose-2026'),
				__('Theatre, opera and gala openings', 'alex-rose-2026'),
				__('Private clubs and members\' evenings', 'alex-rose-2026'),
				__('Christmas parties and end-of-year celebrations', 'alex-rose-2026'),
			),
			'samples_title'   => __('Made in this cloth and occasion.', 'alex-rose-2026'),
			'samples'         => array(
				alex_rose_2026_uploads_url('2026/05/evening-1.jpg'),
				alex_rose_2026_uploads_url('2026/05/evening-2.jpg'),
				alex_rose_2026_uploads_url('2026/05/evening-3.jpg'),
			),
			'cloths_title'    => __('Cloths we recommend.', 'alex-rose-2026'),
			'cloths'          => array(
				array(
					'slug'   => 'english-blazer',
					'name'   => __('The English Blazer Collection', 'alex-rose-2026'),
					'kicker' => __('Classic, structured, formal', 'alex-rose-2026'),
					'price'  => __('From £595', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-english-blazer.jpg'),
				),
				array(
					'slug'   => 'heritage-tweed',
					'name'   => __('The Heritage Tweed Collection', 'alex-rose-2026'),
					'kicker' => __('Character cloths for autumn evenings', 'alex-rose-2026'),
					'price'  => __('From £645', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-heritage.jpg'),
				),
				array(
					'slug'   => 'cotswold',
					'name'   => __('The Cotswold Collection', 'alex-rose-2026'),
					'kicker' => __('Lightweight, drapes beautifully', 'alex-rose-2026'),
					'price'  => __('From £595', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-cotswold.webp'),
				),
			),
			'thinking_title'    => __('How we think about this jacket.', 'alex-rose-2026'),
			'thinking_paragraphs' => array(
				__('Evening cloth wants a little weight. A 310 to 380 gram barathea or fine wool holds its line through a long night, presses crisply, and reflects the light of a room without shining. The difference between a borrowed dinner jacket and one cut for you is precisely this — the way the cloth sits, still, on your shoulder.', 'alex-rose-2026'),
				__('The detail is in the restraint. A peak or shawl lapel cut at the right width, a jetted pocket without a flap, a single covered button. There is nothing on the jacket that announces itself, and that is what makes it work.', 'alex-rose-2026'),
				__('A statement jacket need not be loud. A midnight blue rather than a black, a fine herringbone rather than a flat weave, a contrasting facing rather than a matching one — the small choices that say you thought about the evening before it began.', 'alex-rose-2026'),
			),
			'pull_quote'     => __('Quiet jackets make the loudest impression.', 'alex-rose-2026'),
			'cta_title'      => __('Design your evening jacket.', 'alex-rose-2026'),
			'cta_lead'       => __('Tell us which event you have in mind and we will help you choose a cloth and a cut that does the job without trying too hard.', 'alex-rose-2026'),
		),

		'seasonal' => array(
			'title'           => __('Seasonal Jackets', 'alex-rose-2026'),
			'short_title'     => __('Seasonal Jackets', 'alex-rose-2026'),
			'eyebrow'         => __('Made-to-Measure Jackets · Alex Rose', 'alex-rose-2026'),
			'hero_image'      => alex_rose_2026_uploads_url('2026/05/lifestyle-5.jpg'),
			'hero_lead'       => __('Linen for summer, tweed for autumn. Jackets cut for the weather you actually live in.', 'alex-rose-2026'),
			'related_kicker'  => __('Linen · Tweed · Heritage', 'alex-rose-2026'),
			'when_kicker'     => __('The Occasion', 'alex-rose-2026'),
			'when_title'      => __('When to wear it.', 'alex-rose-2026'),
			'when_paragraph'  => __('British weather rewards a wardrobe that listens to the calendar. A linen and wool blend for the long light of summer, a heavier tweed for the wet, cold months. A seasonal jacket is a jacket cut for the time of year you will actually wear it — not a compromise that does both badly.', 'alex-rose-2026'),
			'when_bullets'    => array(
				__('Summer weddings and garden lunches', 'alex-rose-2026'),
				__('Autumn weekends in the country', 'alex-rose-2026'),
				__('Winter dinners and festive evenings', 'alex-rose-2026'),
				__('Spring race meetings and outdoor events', 'alex-rose-2026'),
				__('Travel to warmer or cooler climates', 'alex-rose-2026'),
				__('A second jacket that complements your first', 'alex-rose-2026'),
			),
			'samples_title'   => __('Made in this cloth and occasion.', 'alex-rose-2026'),
			'samples'         => array(
				alex_rose_2026_uploads_url('2026/05/seasonal-1.jpg'),
				alex_rose_2026_uploads_url('2026/05/seasonal-2.jpg'),
				alex_rose_2026_uploads_url('2026/05/seasonal-3.jpg'),
			),
			'cloths_title'    => __('Cloths we recommend.', 'alex-rose-2026'),
			'cloths'          => array(
				array(
					'slug'   => 'english-riviera',
					'name'   => __('The English Riviera Collection', 'alex-rose-2026'),
					'kicker' => __('Coastal · Lightweight', 'alex-rose-2026'),
					'price'  => __('From £575', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-riviera.jpg'),
				),
				array(
					'slug'   => 'cotswold',
					'name'   => __('The Cotswold Collection', 'alex-rose-2026'),
					'kicker' => __('Country · Versatile', 'alex-rose-2026'),
					'price'  => __('From £595', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-cotswold.webp'),
				),
				array(
					'slug'   => 'moorland-tweed',
					'name'   => __('The Moorland Tweed Collection', 'alex-rose-2026'),
					'kicker' => __('Moors · Heather', 'alex-rose-2026'),
					'price'  => __('From £675', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-moorland.webp'),
				),
			),
			'thinking_title'    => __('How we think about this jacket.', 'alex-rose-2026'),
			'thinking_paragraphs' => array(
				__('Weight is the first decision. A summer jacket wants something in the 230 to 270 gram range — open weaves, linen and wool blends, cloths that breathe through warm afternoons. An autumn or winter jacket wants 340 grams or more, with a denser hand and a richer surface.', 'alex-rose-2026'),
				__('Colour follows the season. Soft buffs, dusty blues and faded greens for the bright months. Russet, dark olive, charcoal and warm browns for the dim ones. Make a friend of the weather you live in and your jackets will repay the favour.', 'alex-rose-2026'),
				__('A pair of seasonal jackets covers most of the year cleanly — one summer, one winter — and a third for the in-between months if you are particular about these things. They wear in different ways and last longer because of it.', 'alex-rose-2026'),
			),
			'pull_quote'     => __('Cloth listens to the calendar. So should you.', 'alex-rose-2026'),
			'cta_title'      => __('Design your seasonal jacket.', 'alex-rose-2026'),
			'cta_lead'       => __('Tell us which months you have in mind and we will help you choose a cloth that suits the weather and your wardrobe.', 'alex-rose-2026'),
		),

		'country' => array(
			'title'           => __('Country & Heritage', 'alex-rose-2026'),
			'short_title'     => __('Country & Heritage', 'alex-rose-2026'),
			'eyebrow'         => __('Made-to-Measure Jackets · Alex Rose', 'alex-rose-2026'),
			'hero_image'      => alex_rose_2026_uploads_url('2026/05/lifestyle-9.jpg'),
			'hero_lead'       => __('Tweed for the country weekend, the shooting field, and the long garden table.', 'alex-rose-2026'),
			'related_kicker'  => __('Shoots · Country · Garden Parties', 'alex-rose-2026'),
			'when_kicker'     => __('The Occasion', 'alex-rose-2026'),
			'when_title'      => __('When to wear it.', 'alex-rose-2026'),
			'when_paragraph'  => __('Country tailoring is the most forgiving thing in a wardrobe. A well-cut tweed jacket sits as happily over a shooting shirt as it does over a fine knit and a pair of cord trousers. It wears in instead of out, and it gets better the more you ask of it.', 'alex-rose-2026'),
			'when_bullets'    => array(
				__('Country weekends and house parties', 'alex-rose-2026'),
				__('Shooting days and country sports', 'alex-rose-2026'),
				__('Garden parties and Sunday lunches', 'alex-rose-2026'),
				__('Pub lunches and long walks', 'alex-rose-2026'),
				__('Race meetings and point-to-points', 'alex-rose-2026'),
				__('Family gatherings in the cooler months', 'alex-rose-2026'),
			),
			'samples_title'   => __('Made in this cloth and occasion.', 'alex-rose-2026'),
			'samples'         => array(
				alex_rose_2026_uploads_url('2026/05/country-1.jpg'),
				alex_rose_2026_uploads_url('2026/05/country-2.jpg'),
				alex_rose_2026_uploads_url('2026/05/country-3.jpg'),
			),
			'cloths_title'    => __('Cloths we recommend.', 'alex-rose-2026'),
			'cloths'          => array(
				array(
					'slug'   => 'heritage-tweed',
					'name'   => __('The Heritage Tweed Collection', 'alex-rose-2026'),
					'kicker' => __('Yorkshire · Timeless', 'alex-rose-2026'),
					'price'  => __('From £645', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-heritage.jpg'),
				),
				array(
					'slug'   => 'harris-tweed',
					'name'   => __('The Harris Tweed Collection', 'alex-rose-2026'),
					'kicker' => __('Hebrides · Protected', 'alex-rose-2026'),
					'price'  => __('From £695', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-harris-tweed.webp'),
				),
				array(
					'slug'   => 'moorland-tweed',
					'name'   => __('The Moorland Tweed Collection', 'alex-rose-2026'),
					'kicker' => __('Moors · Heather', 'alex-rose-2026'),
					'price'  => __('From £675', 'alex-rose-2026'),
					'image'  => alex_rose_2026_uploads_url('2026/05/cloth-moorland.webp'),
				),
			),
			'thinking_title'    => __('How we think about this jacket.', 'alex-rose-2026'),
			'thinking_paragraphs' => array(
				__('A country jacket can be a little softer in the shoulder. We use a lighter canvas and a less aggressive chestpiece so the jacket sits easily over a knit rather than perched on top of it. Action pleats at the back are an option if you intend to use the jacket properly.', 'alex-rose-2026'),
				__('Pocket detail is where the work shows. Bellows pockets on a shooting jacket, ticket pockets on a hacking, slanted pockets on a country two-piece. Keep them practical first; let them flatter the jacket only by accident.', 'alex-rose-2026'),
				__('A real country tweed earns its keep slowly. Year one it sits a little stiff and looks new; year three it has taken your shape and the colours have softened; year ten you would not part with it for anything. That is the point of buying one well.', 'alex-rose-2026'),
			),
			'pull_quote'     => __('A good tweed jacket gets better the more you ask of it.', 'alex-rose-2026'),
			'cta_title'      => __('Design your country jacket.', 'alex-rose-2026'),
			'cta_lead'       => __('Tell us how you intend to wear it and we will help you choose a tweed and a cut that earns its place in the cupboard.', 'alex-rose-2026'),
		),
	);

	return $cache;
}

/**
 * Ordered list of occasion slugs.
 *
 * @return string[]
 */
function alex_rose_2026_occasion_order(): array {
	return array_keys(alex_rose_2026_occasions());
}

/**
 * Resolve the current page's occasion slug, or null if not on a known
 * occasion page.
 */
function alex_rose_2026_current_occasion_slug(): ?string {
	$obj = get_queried_object();
	if (! ($obj instanceof WP_Post)) {
		return null;
	}
	$slug   = $obj->post_name;
	$lookup = alex_rose_2026_occasions();
	return isset($lookup[$slug]) ? $slug : null;
}

/**
 * Skeleton record returned when a page exists but has no registry entry yet.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_blank_occasion(string $title = ''): array {
	return array(
		'title'               => $title,
		'short_title'         => $title,
		'eyebrow'             => '',
		'hero_image'          => '',
		'hero_lead'           => '',
		'related_kicker'      => '',
		'when_kicker'         => __('The Occasion', 'alex-rose-2026'),
		'when_title'          => __('When to wear it.', 'alex-rose-2026'),
		'when_paragraph'      => '',
		'when_bullets'        => array(),
		'samples_title'       => __('Made in this cloth and occasion.', 'alex-rose-2026'),
		'samples'             => array(),
		'cloths_title'        => __('Cloths we recommend.', 'alex-rose-2026'),
		'cloths'              => array(),
		'thinking_title'      => __('How we think about this jacket.', 'alex-rose-2026'),
		'thinking_paragraphs' => array(),
		'pull_quote'          => '',
		'cta_title'           => '',
		'cta_lead'            => '',
	);
}

/**
 * Overlay ACF field values from the given post on top of a registry record.
 *
 * Empty / missing ACF values leave the registry defaults in place.
 *
 * @param array<string, mixed> $base
 * @return array<string, mixed>
 */
function alex_rose_2026_apply_acf_occasion_fields(array $base, int $post_id): array {
	if (! function_exists('get_field')) {
		return $base;
	}

	$scalar_fields = array(
		'eyebrow',
		'hero_image',
		'hero_lead',
		'related_kicker',
		'when_kicker',
		'when_title',
		'when_paragraph',
		'samples_title',
		'cloths_title',
		'thinking_title',
		'pull_quote',
		'cta_title',
		'cta_lead',
	);
	foreach ($scalar_fields as $key) {
		$val = get_field($key, $post_id);
		if (is_string($val) && $val !== '') {
			$base[$key] = $val;
		}
	}

	$bullets = get_field('when_bullets', $post_id);
	if (is_array($bullets) && ! empty($bullets)) {
		$mapped = array();
		foreach ($bullets as $row) {
			if (is_array($row) && ! empty($row['text'])) {
				$mapped[] = (string) $row['text'];
			} elseif (is_string($row) && $row !== '') {
				$mapped[] = $row;
			}
		}
		if (! empty($mapped)) {
			$base['when_bullets'] = $mapped;
		}
	}

	$samples = get_field('samples', $post_id);
	if (is_array($samples) && ! empty($samples)) {
		$mapped = array();
		foreach ($samples as $row) {
			if (is_array($row) && ! empty($row['image'])) {
				$mapped[] = (string) $row['image'];
			} elseif (is_string($row) && $row !== '') {
				$mapped[] = $row;
			}
		}
		if (! empty($mapped)) {
			$base['samples'] = $mapped;
		}
	}

	$cloths = get_field('cloths', $post_id);
	if (is_array($cloths) && ! empty($cloths)) {
		$mapped = array();
		foreach ($cloths as $row) {
			if (! is_array($row)) {
				continue;
			}
			$slug   = isset($row['slug']) ? (string) $row['slug'] : '';
			$name   = isset($row['name']) ? (string) $row['name'] : '';
			$kicker = isset($row['kicker']) ? (string) $row['kicker'] : '';
			$price  = isset($row['price']) ? (string) $row['price'] : '';
			$image  = isset($row['image']) ? (string) $row['image'] : '';
			if ($slug === '' && $name === '' && $image === '') {
				continue;
			}
			$mapped[] = array(
				'slug'   => $slug,
				'name'   => $name,
				'kicker' => $kicker,
				'price'  => $price,
				'image'  => $image,
			);
		}
		if (! empty($mapped)) {
			$base['cloths'] = $mapped;
		}
	}

	$paragraphs = get_field('thinking_paragraphs', $post_id);
	if (is_array($paragraphs) && ! empty($paragraphs)) {
		$mapped = array();
		foreach ($paragraphs as $row) {
			if (is_array($row) && ! empty($row['text'])) {
				$mapped[] = (string) $row['text'];
			} elseif (is_string($row) && $row !== '') {
				$mapped[] = $row;
			}
		}
		if (! empty($mapped)) {
			$base['thinking_paragraphs'] = $mapped;
		}
	}

	return $base;
}

/**
 * Resolve the current page's occasion record.
 *
 * The PHP registry provides default values per slug; ACF fields on the actual
 * WordPress page override anything they fill in. The live WP page title
 * always wins for the title.
 *
 * @return array<string, mixed>|null
 */
function alex_rose_2026_current_occasion(): ?array {
	$obj = get_queried_object();
	if (! ($obj instanceof WP_Post)) {
		return null;
	}

	$registry = alex_rose_2026_occasions();
	$base     = isset($registry[$obj->post_name])
		? $registry[$obj->post_name]
		: alex_rose_2026_blank_occasion($obj->post_title);

	$base['title']       = $obj->post_title !== '' ? $obj->post_title : ($base['title'] ?? '');
	$base['short_title'] = $base['title'];
	$base['slug']        = $obj->post_name;

	$base = alex_rose_2026_apply_acf_occasion_fields($base, (int) $obj->ID);

	$has_content = ! empty($base['hero_image'])
		|| ! empty($base['hero_lead'])
		|| ! empty($base['when_paragraph'])
		|| ! empty($base['when_bullets'])
		|| ! empty($base['samples'])
		|| ! empty($base['cloths'])
		|| ! empty($base['thinking_paragraphs'])
		|| isset($registry[$obj->post_name]);

	return $has_content ? $base : null;
}

/**
 * All pages that use the Occasion template, ordered by menu_order then by
 * title. Returns an empty array when no such pages exist.
 *
 * @return WP_Post[]
 */
function alex_rose_2026_occasion_pages(): array {
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}
	$pages = get_pages(array(
		'meta_key'    => '_wp_page_template',
		'meta_value'  => 'template/occasion.php',
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'asc',
	));
	$cache = is_array($pages) ? $pages : array();
	return $cache;
}

/**
 * Build the "You might also like" cards for the occasion bottom strip.
 *
 * Prefers live WP pages that use the template; falls back to registry order
 * when the editor hasn't created the pages yet.
 *
 * @param string|null $exclude_slug Current page slug to exclude.
 * @param int         $limit
 * @return array<int, array{title:string, url:string, kicker:string, image:string}>
 */
function alex_rose_2026_related_occasions(?string $exclude_slug = null, int $limit = 3): array {
	$registry = alex_rose_2026_occasions();
	$out      = array();

	$pages = alex_rose_2026_occasion_pages();
	if (! empty($pages)) {
		foreach ($pages as $p) {
			if ($exclude_slug !== null && $p->post_name === $exclude_slug) {
				continue;
			}
			$slug   = $p->post_name;
			$record = isset($registry[$slug]) ? $registry[$slug] : alex_rose_2026_blank_occasion($p->post_title);
			$out[]  = array(
				'title'  => (string) $p->post_title,
				'url'    => (string) get_permalink($p->ID),
				'kicker' => (string) ($record['related_kicker'] ?? ''),
				'image'  => (string) ($record['hero_image'] ?? ''),
			);
			if (count($out) >= $limit) {
				return $out;
			}
		}
	}

	foreach ($registry as $slug => $record) {
		if ($exclude_slug !== null && $slug === $exclude_slug) {
			continue;
		}
		$already_listed = false;
		foreach ($out as $row) {
			if ($row['title'] === ($record['title'] ?? '')) {
				$already_listed = true;
				break;
			}
		}
		if ($already_listed) {
			continue;
		}
		$out[] = array(
			'title'  => (string) ($record['title'] ?? ''),
			'url'    => home_url('/occasions/' . $slug . '/'),
			'kicker' => (string) ($record['related_kicker'] ?? ''),
			'image'  => (string) ($record['hero_image'] ?? ''),
		);
		if (count($out) >= $limit) {
			break;
		}
	}

	return $out;
}

/**
 * Create /occasions/ and child pages (business, evening, etc.) with the Occasion template.
 *
 * Idempotent: safe to run on every request until the version option matches.
 */
function alex_rose_2026_ensure_occasion_pages(): void {
	$version = 1;
	if ((int) get_option('alex_rose_2026_occasion_pages_version', 0) >= $version) {
		return;
	}

	$parent = get_page_by_path('occasions');
	if (! $parent instanceof WP_Post) {
		$parent_id = wp_insert_post(
			array(
				'post_title'  => __('Occasions', 'alex-rose-2026'),
				'post_name'   => 'occasions',
				'post_status' => 'publish',
				'post_type'   => 'page',
				'post_content'=> '',
			),
			true
		);
		if (is_wp_error($parent_id) || ! $parent_id) {
			return;
		}
	} else {
		$parent_id = (int) $parent->ID;
	}

	$registry = alex_rose_2026_occasions();
	foreach ($registry as $slug => $record) {
		$page = get_page_by_path('occasions/' . $slug);
		if ($page instanceof WP_Post) {
			if (get_page_template_slug($page->ID) !== 'template/occasion.php') {
				update_post_meta($page->ID, '_wp_page_template', 'template/occasion.php');
			}
			continue;
		}

		$title = isset($record['title']) ? (string) $record['title'] : ucfirst($slug);
		$id    = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_parent'  => $parent_id,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			),
			true
		);
		if (is_wp_error($id) || ! $id) {
			continue;
		}
		update_post_meta((int) $id, '_wp_page_template', 'template/occasion.php');
	}

	update_option('alex_rose_2026_occasion_pages_version', $version, false);
}

add_action('after_switch_theme', 'alex_rose_2026_ensure_occasion_pages');
add_action('init', 'alex_rose_2026_ensure_occasion_pages', 20);
