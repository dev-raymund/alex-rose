<?php
/**
 * Off the Cuff — journal category + article helpers.
 *
 * Posts are standard WordPress posts assigned to child categories of the
 * parent "Off The Cuff" category. Filter buttons are built from those children.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Parent category slug for the Off the Cuff journal.
 */
function alex_rose_2026_off_the_cuff_parent_slug(): string {
	return 'off-the-cuff';
}

/**
 * The parent "Off The Cuff" category term, or null if missing.
 *
 * @return WP_Term|null
 */
function alex_rose_2026_off_the_cuff_parent_term(): ?WP_Term {
	static $term = null;
	static $resolved = false;

	if ($resolved) {
		return $term;
	}

	$resolved = true;
	$term     = get_term_by('slug', alex_rose_2026_off_the_cuff_parent_slug(), 'category');

	if (! $term instanceof WP_Term) {
		$term = get_term_by('name', 'Off The Cuff', 'category');
	}

	if (! $term instanceof WP_Term) {
		$term = null;
	}

	return $term;
}

/**
 * Child categories used as grid filter labels.
 *
 * @return WP_Term[]
 */
function alex_rose_2026_off_the_cuff_filter_terms(): array {
	$parent = alex_rose_2026_off_the_cuff_parent_term();
	if (! $parent instanceof WP_Term) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'parent'     => (int) $parent->term_id,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if (is_wp_error($terms) || ! is_array($terms)) {
		return array();
	}

	return $terms;
}

/**
 * Filter buttons for the grid toolbar (All + child categories).
 *
 * @return array<int, array{slug: string, label: string}>
 */
function alex_rose_2026_off_the_cuff_filters(): array {
	$filters = array(
		array(
			'slug'  => 'all',
			'label' => __('All', 'alex-rose-2026'),
		),
	);

	foreach (alex_rose_2026_off_the_cuff_filter_terms() as $term) {
		$filters[] = array(
			'slug'  => $term->slug,
			'label' => $term->name,
		);
	}

	return $filters;
}

/**
 * Child category assigned to a post under Off the Cuff.
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function alex_rose_2026_off_the_cuff_post_category(int $post_id): ?WP_Term {
	$parent = alex_rose_2026_off_the_cuff_parent_term();
	if (! $parent instanceof WP_Term) {
		return null;
	}

	$parent_id = (int) $parent->term_id;
	$terms     = get_the_terms($post_id, 'category');

	if (! is_array($terms)) {
		return null;
	}

	foreach ($terms as $term) {
		if ((int) $term->parent === $parent_id) {
			return $term;
		}
	}

	return null;
}

/**
 * Estimated reading time label for a post.
 *
 * @param int $post_id Post ID.
 */
function alex_rose_2026_off_the_cuff_reading_time(int $post_id): string {
	$content    = (string) get_post_field('post_content', $post_id);
	$word_count = str_word_count(wp_strip_all_tags($content));
	$minutes    = max(1, (int) ceil($word_count / 200));

	return sprintf(
		/* translators: %d: estimated minutes to read */
		_n('%d min read', '%d min read', $minutes, 'alex-rose-2026'),
		$minutes
	);
}

/**
 * Month + year label for article cards (e.g. March 2025).
 *
 * @param int $post_id Post ID.
 */
function alex_rose_2026_off_the_cuff_post_date_label(int $post_id): string {
	$timestamp = get_post_timestamp($post_id);
	if (! $timestamp) {
		return '';
	}

	return mb_strtoupper(date_i18n('F Y', $timestamp));
}

/**
 * Featured article post ID for the Off the Cuff landing page.
 *
 * Override in a child theme or plugin:
 * add_filter( 'alex_rose_2026_off_the_cuff_featured_post_id', fn () => 123 );
 */
function alex_rose_2026_off_the_cuff_featured_post_id(): int {
	return (int) apply_filters('alex_rose_2026_off_the_cuff_featured_post_id', 131);
}

/**
 * Featured article shown above the grid.
 *
 * @return WP_Post|null
 */
function alex_rose_2026_off_the_cuff_featured_post(): ?WP_Post {
	$post_id = alex_rose_2026_off_the_cuff_featured_post_id();
	if ($post_id <= 0) {
		return null;
	}

	$post = get_post($post_id);
	if (! $post instanceof WP_Post || $post->post_type !== 'post' || $post->post_status !== 'publish') {
		return null;
	}

	return $post;
}

/**
 * Published Off the Cuff articles for the landing grid.
 *
 * @param array<string, mixed> $args Optional WP_Query overrides.
 * @return WP_Post[]
 */
function alex_rose_2026_off_the_cuff_articles(array $args = array()): array {
	$parent = alex_rose_2026_off_the_cuff_parent_term();
	if (! $parent instanceof WP_Term) {
		return array();
	}

	$exclude = array();
	$featured = alex_rose_2026_off_the_cuff_featured_post();
	if ($featured instanceof WP_Post) {
		$exclude[] = (int) $featured->ID;
	}

	$query_args = wp_parse_args(
		$args,
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => -1,
			'post__not_in'        => $exclude,
			'ignore_sticky_posts' => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'tax_query'           => array(
				array(
					'taxonomy'         => 'category',
					'field'            => 'term_id',
					'terms'            => (int) $parent->term_id,
					'include_children' => true,
				),
			),
		)
	);

	$query = new WP_Query($query_args);

	if (! $query->have_posts()) {
		return array();
	}

	return $query->posts;
}

/**
 * Whether a post belongs to the Off the Cuff journal.
 *
 * @param int|null $post_id Post ID, or null for the current post in the loop.
 */
function alex_rose_2026_is_off_the_cuff_post(?int $post_id = null): bool {
	$post_id = $post_id ?? (int) get_the_ID();
	if ($post_id <= 0) {
		return false;
	}

	$parent = alex_rose_2026_off_the_cuff_parent_term();
	if (! $parent instanceof WP_Term) {
		return false;
	}

	$terms = get_the_terms($post_id, 'category');
	if (! is_array($terms)) {
		return false;
	}

	$parent_id = (int) $parent->term_id;
	foreach ($terms as $term) {
		if ((int) $term->term_id === $parent_id || (int) $term->parent === $parent_id) {
			return true;
		}
	}

	return false;
}

/**
 * Related Off the Cuff articles for a single post.
 *
 * @param int $post_id Current post ID.
 * @param int $limit   Maximum number of posts to return.
 * @return WP_Post[]
 */
function alex_rose_2026_off_the_cuff_related_articles(int $post_id, int $limit = 3): array {
	$parent = alex_rose_2026_off_the_cuff_parent_term();
	if (! $parent instanceof WP_Term || $limit <= 0) {
		return array();
	}

	$related = array();
	$exclude = array($post_id);
	$category = alex_rose_2026_off_the_cuff_post_category($post_id);

	if ($category instanceof WP_Term) {
		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $limit,
				'post__not_in'        => $exclude,
				'ignore_sticky_posts' => true,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'tax_query'           => array(
					array(
						'taxonomy'         => 'category',
						'field'            => 'term_id',
						'terms'            => (int) $category->term_id,
						'include_children' => false,
					),
				),
			)
		);

		if ($query->have_posts()) {
			foreach ($query->posts as $post) {
				$related[] = $post;
				$exclude[] = (int) $post->ID;
			}
		}
	}

	if (count($related) < $limit) {
		$more = alex_rose_2026_off_the_cuff_articles(
			array(
				'posts_per_page' => $limit - count($related),
				'post__not_in'   => $exclude,
			)
		);
		$related = array_merge($related, $more);
	}

	return array_slice($related, 0, $limit);
}
