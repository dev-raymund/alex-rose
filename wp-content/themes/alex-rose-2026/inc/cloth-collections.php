<?php
/**
 * Cloth collection registry + helpers.
 *
 * Each cloth collection is a WordPress page that uses
 * template/cloth-collection.php and is filled in via the matching ACF group
 * (see acf-json/group_cloth_collection.json). The collection's data is
 * keyed by the page slug.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Registry of cloth collections, keyed by page slug.
 *
 * Built from the live WordPress pages that use template/cloth-collection.php,
 * overlaid with their ACF field values.
 *
 * @return array<string, array<string, mixed>>
 */
function alex_rose_2026_cloth_collections(): array {
	$out = array();

	foreach (alex_rose_2026_cloth_collection_pages() as $page) {
		if (! $page instanceof WP_Post) {
			continue;
		}
		$slug = (string) $page->post_name;
		if ($slug === '') {
			continue;
		}

		$base          = alex_rose_2026_blank_cloth_collection((string) $page->post_title);
		$base['title'] = $page->post_title !== '' ? (string) $page->post_title : ($base['title'] ?? '');
		$base          = alex_rose_2026_apply_acf_collection_fields($base, (int) $page->ID);

		$out[ $slug ] = $base;
	}

	return $out;
}

/**
 * Ordered list of collection slugs in the order they appear on /cloths/.
 *
 * @return string[]
 */
function alex_rose_2026_cloth_collection_order(): array {
	return array_keys(alex_rose_2026_cloth_collections());
}

/**
 * Collections + swatches for the Request Cloth Samples form, in display order.
 *
 * @return array<int, array{slug:string, label:string, swatches:array<int, array{id:string, name:string, image:string, alt:string}>}>
 */
function alex_rose_2026_cloth_sample_collections(): array {
	$label_overrides = array(
		'cotswold'        => __('Cotswold Collection', 'alex-rose-2026'),
		'travel-blazer'   => __('International Travel Blazer', 'alex-rose-2026'),
		'harris-tweed'    => __('Harris Tweed', 'alex-rose-2026'),
		'heritage-tweed'  => __('Heritage Tweed', 'alex-rose-2026'),
		'yorkshire-tweed' => __('Yorkshire Tweed', 'alex-rose-2026'),
		'moorland-tweed'  => __('Moorland Tweed', 'alex-rose-2026'),
		'english-blazer'  => __('English Blazer', 'alex-rose-2026'),
		'english-riviera' => __('English Riviera', 'alex-rose-2026'),
	);

	$out = array();

	foreach (alex_rose_2026_cloth_collections() as $slug => $col) {
		$label    = $label_overrides[ $slug ] ?? (string) ( $col['title'] ?? $slug );
		$swatches = is_array($col['swatches'] ?? null) ? $col['swatches'] : array();

		if ($swatches === array() && ! empty($col['cloth_image'])) {
			$swatches = array(
				array(
					'id'    => $slug,
					'name'  => $label,
					'image' => (string) $col['cloth_image'],
					'alt'   => $label,
				),
			);
		} else {
			$mapped = array();
			foreach ($swatches as $swatch) {
				if (! is_array($swatch) || empty($swatch['name'])) {
					continue;
				}
				$name     = (string) $swatch['name'];
				$mapped[] = array(
					'id'    => $slug . '-' . sanitize_title($name),
					'name'  => $name,
					'image' => (string) ( $swatch['image'] ?? '' ),
					'alt'   => (string) ( $swatch['alt'] ?? $name ),
				);
			}
			$swatches = $mapped;
		}

		$out[] = array(
			'slug'     => $slug,
			'label'    => $label,
			'swatches' => $swatches,
		);
	}

	return $out;
}

/**
 * Resolve the current page's collection slug, or null if not on a known
 * collection page.
 */
function alex_rose_2026_current_cloth_collection_slug(): ?string {
	$obj = get_queried_object();
	if (! ($obj instanceof WP_Post)) {
		return null;
	}
	$slug        = $obj->post_name;
	$collections = alex_rose_2026_cloth_collections();
	return isset($collections[$slug]) ? $slug : null;
}

/**
 * Skeleton record returned when a page has no data yet.
 *
 * @return array<string, mixed>
 */
function alex_rose_2026_blank_cloth_collection(string $title = ''): array {
	return array(
		'title'       => $title,
		'kicker'      => '',
		'hero_image'  => '',
		'hero_lead'   => '',
		'cloth_image' => '',
		'intro'       => '',
		'paragraphs'  => array(),
		'specs'       => array(),
		'swatches'    => array(),
	);
}

/**
 * Overlay ACF field values from the given post on top of a base record.
 *
 * Empty / missing ACF values are left untouched so the base defaults
 * survive when an editor hasn't filled a field in yet.
 *
 * @param array<string, mixed> $base
 * @return array<string, mixed>
 */
function alex_rose_2026_apply_acf_collection_fields(array $base, int $post_id): array {
	if (! function_exists('get_field')) {
		return $base;
	}

	$kicker = get_field('kicker', $post_id);
	if (is_string($kicker) && $kicker !== '') {
		$base['kicker'] = $kicker;
	}

	$hero_image = get_field('hero_image', $post_id);
	if (is_string($hero_image) && $hero_image !== '') {
		$base['hero_image'] = $hero_image;
	}

	$hero_lead = get_field('hero_lead', $post_id);
	if (is_string($hero_lead) && $hero_lead !== '') {
		$base['hero_lead'] = $hero_lead;
	}

	$cloth_image = get_field('cloth_image', $post_id);
	if (is_string($cloth_image) && $cloth_image !== '') {
		$base['cloth_image'] = $cloth_image;
	}

	$intro = get_field('intro', $post_id);
	if (is_string($intro) && $intro !== '') {
		$base['intro'] = $intro;
	}

	$paragraphs = get_field('paragraphs', $post_id);
	if (is_array($paragraphs) && ! empty($paragraphs)) {
		$mapped = array();
		foreach ($paragraphs as $row) {
			if (is_array($row) && ! empty($row['text'])) {
				$mapped[] = (string) $row['text'];
			}
		}
		if (! empty($mapped)) {
			$base['paragraphs'] = $mapped;
		}
	}

	$specs = get_field('specs', $post_id);
	if (is_array($specs) && ! empty($specs)) {
		$mapped = array();
		foreach ($specs as $row) {
			if (! is_array($row)) {
				continue;
			}
			$label = isset($row['label']) ? (string) $row['label'] : '';
			$value = isset($row['value']) ? (string) $row['value'] : '';
			if ($label === '' && $value === '') {
				continue;
			}
			$mapped[] = array('label' => $label, 'value' => $value);
		}
		if (! empty($mapped)) {
			$base['specs'] = $mapped;
		}
	}

	$swatches = get_field('swatches', $post_id);
	if (is_array($swatches) && ! empty($swatches)) {
		$mapped = array();
		foreach ($swatches as $row) {
			if (! is_array($row)) {
				continue;
			}
			$name  = isset($row['name']) ? (string) $row['name'] : '';
			$image = isset($row['image']) ? (string) $row['image'] : '';
			if ($image === '') {
				continue;
			}
			$mapped[] = array(
				'name'  => $name,
				'image' => $image,
				'alt'   => $name !== '' ? $name : '',
			);
		}
		if (! empty($mapped)) {
			$base['swatches'] = $mapped;
		}
	}

	return $base;
}

/**
 * Resolve the current page's collection record.
 *
 * @return array<string, mixed>|null
 */
function alex_rose_2026_current_cloth_collection(): ?array {
	$obj = get_queried_object();
	if (! ($obj instanceof WP_Post)) {
		return null;
	}

	$collections = alex_rose_2026_cloth_collections();
	if (! isset($collections[ $obj->post_name ])) {
		return null;
	}

	return $collections[ $obj->post_name ];
}

/**
 * All pages that use the Cloth Collection template, ordered by menu_order
 * then by title. Returns an empty array when no such pages exist.
 *
 * @return WP_Post[]
 */
function alex_rose_2026_cloth_collection_pages(): array {
	$pages = get_pages(array(
		'meta_key'    => '_wp_page_template',
		'meta_value'  => 'template/cloth-collection.php',
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'asc',
		'post_status' => 'publish',
		'parent'      => -1,
		'hierarchical' => 0,
	));

	return is_array($pages) ? $pages : array();
}

/**
 * Pick the next collection (wrapping around), used by the bottom pager.
 *
 * @return array{url:string, title:string}|null
 */
function alex_rose_2026_next_cloth_collection(): ?array {
	$pages = alex_rose_2026_cloth_collection_pages();
	if (count($pages) < 2) {
		return null;
	}

	$current_id = (int) get_queried_object_id();
	if ($current_id <= 0) {
		return null;
	}

	$ids = array_map(static function (WP_Post $p): int { return (int) $p->ID; }, $pages);
	$idx = array_search($current_id, $ids, true);
	if ($idx === false) {
		return null;
	}

	$next = $pages[ ($idx + 1) % count($pages) ];

	return array(
		'url'   => (string) get_permalink($next->ID),
		'title' => (string) $next->post_title,
	);
}
