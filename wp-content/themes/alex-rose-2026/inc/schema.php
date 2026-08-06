<?php
/**
 * Structured data (JSON-LD) for search + generative engines (GEO/SEO).
 *
 * Emits a single connected @graph in <head> on the front end:
 *   - ClothingStore / LocalBusiness  — the Leeds tailoring business (site-wide)
 *   - Person                          — Harold Rose, master tailor (site-wide)
 *   - Organization                    — the brand entity (homepage only)
 *   - Product                         — the bespoke made-to-measure jacket
 *                                       (only on the Design Your Jacket page)
 *   - BreadcrumbList                  — trail for inner pages (off by default
 *                                       when Yoast is active; see filter below)
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
 * Social profile URLs, shared across schema nodes.
 *
 * @return string[]
 */
function alex_rose_2026_schema_social_profiles(): array {
	return array(
		'https://www.tiktok.com/@alexrosetailoring',
		'https://www.facebook.com/alexrosesince1945/',
		'https://www.instagram.com/alexrosetailoring/',
		'https://www.linkedin.com/in/harold-rose/',
	);
}

/**
 * Postal address, shared by the business + organization nodes.
 *
 * @return array<string, string>
 */
function alex_rose_2026_schema_address(): array {
	return array(
		'@type'           => 'PostalAddress',
		'streetAddress'   => '2A Rodley Lane, Rodley',
		'addressLocality' => 'Leeds',
		'addressRegion'   => 'West Yorkshire',
		'postalCode'      => 'LS13 1HU',
		'addressCountry'  => 'GB',
	);
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
		// Consolidate the historical name so search engines treat it as the
		// same entity rather than a competing brand.
		'alternateName' => array('Alexander Rose', 'Alexander Rose Ltd'),
		'url'         => home_url('/'),
		'logo'        => alex_rose_2026_schema_logo_url(),
		'image'       => alex_rose_2026_schema_logo_url(),
		'email'       => 'tailor@alexrose.uk',
		'telephone'   => '+441134688588',
		'foundingDate' => '1945',
		'priceRange'  => '££–£££',
		'description' => 'Made-to-measure jackets from a Leeds family tailoring business with eighty years of heritage. Design online, refine the fit in person, delivered worldwide.',
		'address'     => alex_rose_2026_schema_address(),
		'areaServed'  => array(
			array('@type' => 'Country', 'name' => 'United Kingdom'),
			'Worldwide',
		),
		'founder'     => array('@type' => 'Person', 'name' => 'Alexander Rose'),
		'employee'    => array('@id' => alex_rose_2026_schema_id('harold')),
		'sameAs'      => alex_rose_2026_schema_social_profiles(),
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
 * Organization node for the brand entity (homepage only). Helps Google associate
 * the name, logo and social profiles with a single company in the knowledge graph.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_schema_organization(): array {
	$organization = array(
		'@type'         => 'Organization',
		'@id'           => alex_rose_2026_schema_id('organization'),
		'name'          => 'Alex Rose Fine Tailoring',
		'legalName'     => 'Alex Rose Fine Tailoring Ltd',
		// Fold the historical name into this one entity, not a competing brand.
		'alternateName' => array('Alexander Rose', 'Alexander Rose Ltd'),
		'url'           => home_url('/'),
		'logo'          => array(
			'@type' => 'ImageObject',
			'url'   => alex_rose_2026_schema_logo_url(),
		),
		'image'         => alex_rose_2026_schema_logo_url(),
		'email'         => 'tailor@alexrose.uk',
		'telephone'     => '+441134688588',
		'foundingDate'  => '1945',
		'founder'       => array('@type' => 'Person', 'name' => 'Alexander Rose'),
		'address'       => alex_rose_2026_schema_address(),
		'contactPoint'  => array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer service',
			'telephone'         => '+441134688588',
			'email'             => 'tailor@alexrose.uk',
			'areaServed'        => 'GB',
			'availableLanguage' => 'English',
		),
		'sameAs'        => alex_rose_2026_schema_social_profiles(),
	);

	/**
	 * Filter the Organization node.
	 *
	 * @param array<string, mixed> $organization
	 */
	return (array) apply_filters('alex_rose_2026_schema_organization', $organization);
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
 * Whether to emit the theme's BreadcrumbList.
 *
 * Off by default when Yoast is active (it emits its own BreadcrumbList, and two
 * competing trails can conflict). Force it on with:
 *   add_filter('alex_rose_2026_breadcrumb_schema_enabled', '__return_true');
 */
function alex_rose_2026_breadcrumb_schema_enabled(): bool {
	return (bool) apply_filters('alex_rose_2026_breadcrumb_schema_enabled', ! defined('WPSEO_VERSION'));
}

/**
 * BreadcrumbList for the current view. Null on the homepage / when a trail of
 * fewer than two crumbs would result.
 *
 * @return array<string, mixed>|null
 */
function alex_rose_2026_schema_breadcrumb(): ?array {
	if (is_front_page()) {
		return null;
	}

	$crumbs = array(array('name' => __('Home', 'alex-rose-2026'), 'url' => home_url('/')));

	if (is_singular()) {
		$post_id = (int) get_queried_object_id();

		if (is_singular('post')) {
			// Journal articles sit under the Off the Cuff landing page.
			$crumbs[] = array('name' => __('Off the Cuff', 'alex-rose-2026'), 'url' => home_url('/off-the-cuff/'));
		} else {
			foreach (array_reverse(get_post_ancestors($post_id)) as $ancestor_id) {
				$crumbs[] = array('name' => get_the_title($ancestor_id), 'url' => (string) get_permalink($ancestor_id));
			}
		}

		$crumbs[] = array('name' => get_the_title($post_id), 'url' => (string) get_permalink($post_id));
	} elseif (is_category() || is_tag() || is_tax()) {
		$term = get_queried_object();
		$link = $term instanceof WP_Term ? get_term_link($term) : '';
		if ($term instanceof WP_Term && is_string($link)) {
			$crumbs[] = array('name' => $term->name, 'url' => $link);
		}
	} elseif (is_post_type_archive()) {
		$crumbs[] = array(
			'name' => post_type_archive_title('', false),
			'url'  => (string) get_post_type_archive_link((string) get_post_type()),
		);
	} else {
		return null;
	}

	if (count($crumbs) < 2) {
		return null;
	}

	$elements = array();
	foreach ($crumbs as $index => $crumb) {
		$elements[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $crumb['name'],
			'item'     => $crumb['url'],
		);
	}

	$breadcrumb = array(
		'@type'           => 'BreadcrumbList',
		'@id'             => alex_rose_2026_schema_id('breadcrumb'),
		'itemListElement' => $elements,
	);

	/**
	 * Filter the BreadcrumbList node.
	 *
	 * @param array<string, mixed> $breadcrumb
	 */
	return (array) apply_filters('alex_rose_2026_schema_breadcrumb', $breadcrumb);
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

	if (is_front_page()) {
		$graph[] = alex_rose_2026_schema_organization();
	}

	if (function_exists('is_page_template') && is_page_template('template/design.php')) {
		$graph[] = alex_rose_2026_schema_product();
	}

	if (alex_rose_2026_breadcrumb_schema_enabled()) {
		$breadcrumb = alex_rose_2026_schema_breadcrumb();
		if ($breadcrumb) {
			$graph[] = $breadcrumb;
		}
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
