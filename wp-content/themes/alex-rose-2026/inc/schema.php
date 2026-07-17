<?php
/**
 * Structured data (JSON-LD) for search + generative engines (GEO/SEO).
 *
 * Emits a single connected @graph in <head> on the front end:
 *   - ClothingStore / LocalBusiness  — the Leeds tailoring business (site-wide)
 *   - Person                          — Harold Rose, master tailor (site-wide)
 *   - Product                         — the bespoke made-to-measure jacket
 *                                       (only on the Design Your Jacket page)
 *
 * This is deliberately self-contained (no Yoast dependency) so it deploys with
 * the theme. It coexists with Yoast's Organization/WebSite graph; the pieces
 * here use their own @id anchors. Every literal is filterable so values can be
 * corrected without editing this file — see the filters below.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Stable @id anchors for cross-referencing pieces within the graph.
 */
function alex_rose_2026_schema_id(string $anchor): string {
	return home_url('/#' . ltrim($anchor, '#'));
}

/**
 * The business's public logo URL (falls back to the packaged logo).
 */
function alex_rose_2026_schema_logo_url(): string {
	$logo_id = (int) get_theme_mod('custom_logo');
	if ($logo_id) {
		$url = wp_get_attachment_image_url($logo_id, 'full');
		if ($url) {
			return $url;
		}
	}
	return alex_rose_2026_uploads_url('2026/05/alex-rose-logo.png');
}

/**
 * LocalBusiness (typed as ClothingStore) describing the tailoring business.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_schema_local_business(): array {
	$business = array(
		'@type'       => array('ClothingStore', 'LocalBusiness'),
		'@id'         => alex_rose_2026_schema_id('business'),
		'name'        => 'Alex Rose Fine Tailoring',
		'legalName'   => 'Alex Rose Fine Tailoring Ltd',
		'url'         => home_url('/'),
		'logo'        => alex_rose_2026_schema_logo_url(),
		'image'       => alex_rose_2026_schema_logo_url(),
		'email'       => 'tailor@alexrose.uk',
		'telephone'   => '+441134688588',
		'foundingDate' => '1945',
		'priceRange'  => '££–£££',
		'description' => 'Made-to-measure jackets from a Leeds family tailoring business with eighty years of heritage. Design online, refine the fit in person, delivered worldwide.',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '2A Rodley Lane, Rodley',
			'addressLocality' => 'Leeds',
			'addressRegion'   => 'West Yorkshire',
			'postalCode'      => 'LS13 1HU',
			'addressCountry'  => 'GB',
		),
		'areaServed'  => array(
			array('@type' => 'Country', 'name' => 'United Kingdom'),
			'Worldwide',
		),
		'founder'     => array('@id' => alex_rose_2026_schema_id('harold')),
		'employee'    => array('@id' => alex_rose_2026_schema_id('harold')),
		'sameAs'      => array(
			'https://www.tiktok.com/@alexrosetailoring',
			'https://www.facebook.com/alexrosesince1945/',
			'https://www.instagram.com/alexrosetailoring/',
			'https://www.linkedin.com/in/harold-rose/',
		),
		// Showroom hours — CONFIRM these are correct before relying on them.
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array('Wednesday', 'Thursday', 'Friday', 'Saturday'),
				'opens'     => '10:00',
				'closes'    => '16:30',
			),
		),
	);

	/**
	 * Filter the LocalBusiness node (e.g. add geo coordinates or fix hours).
	 *
	 * @param array<string, mixed> $business
	 */
	return (array) apply_filters('alex_rose_2026_schema_local_business', $business);
}

/**
 * Person node for Harold Rose, the master tailor.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_schema_person(): array {
	$person = array(
		'@type'       => 'Person',
		'@id'         => alex_rose_2026_schema_id('harold'),
		'name'        => 'Harold Rose',
		'jobTitle'    => 'Master Tailor',
		'description' => 'Master tailor at Alex Rose Fine Tailoring. Harold became Managing Director in 1972, carrying forward the family tailoring business founded by his father, Alexander Rose, in 1945.',
		'url'         => home_url('/our-story/'),
		'worksFor'    => array('@id' => alex_rose_2026_schema_id('business')),
		'sameAs'      => array(
			'https://www.linkedin.com/in/harold-rose/',
		),
	);

	/**
	 * Filter the Person (Harold) node.
	 *
	 * @param array<string, mixed> $person
	 */
	return (array) apply_filters('alex_rose_2026_schema_person', $person);
}

/**
 * Product node for the bespoke made-to-measure jacket. Only added on the
 * Design Your Jacket configurator page.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_schema_product(): array {
	$price     = (string) apply_filters('alex_rose_2026_schema_product_price', '595');
	$valid_until = gmdate('Y-m-d', strtotime('+1 year'));

	$product = array(
		'@type'       => 'Product',
		'@id'         => alex_rose_2026_schema_id('bespoke-jacket'),
		'name'        => 'Bespoke Made-to-Measure Jacket',
		'description' => 'A made-to-measure jacket designed online and cut to your exact measurements. Choose your cloth, lapel, lining, buttons, pockets and vents, with your name monogrammed into the lining. Delivered worldwide.',
		'category'    => 'Made-to-measure jackets',
		'image'       => array(
			alex_rose_2026_uploads_url('2026/05/jackets.webp'),
		),
		'brand'       => array(
			'@type' => 'Brand',
			'name'  => 'Alex Rose Fine Tailoring',
		),
		'offers'      => array(
			'@type'           => 'Offer',
			'priceCurrency'   => 'GBP',
			'price'           => $price,
			'priceValidUntil' => $valid_until,
			'availability'    => 'https://schema.org/InStock',
			'url'             => get_permalink() ?: home_url('/'),
			'seller'          => array('@id' => alex_rose_2026_schema_id('business')),
		),
	);

	/**
	 * Filter the bespoke jacket Product node.
	 *
	 * @param array<string, mixed> $product
	 */
	return (array) apply_filters('alex_rose_2026_schema_product', $product);
}

/**
 * Print the JSON-LD @graph in <head>.
 */
function alex_rose_2026_print_schema(): void {
	if (is_admin() || is_feed() || is_404() || is_search()) {
		return;
	}

	$graph = array(
		alex_rose_2026_schema_local_business(),
		alex_rose_2026_schema_person(),
	);

	if (function_exists('is_page_template') && is_page_template('template/design.php')) {
		$graph[] = alex_rose_2026_schema_product();
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values(array_filter($graph)),
	);

	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
		. '</script>' . "\n";
}
add_action('wp_head', 'alex_rose_2026_print_schema', 20);
