<?php
/**
 * AJAX filtering and pagination for the Journal.
 *
 * Progressive enhancement: the filter pills and page links are ordinary links
 * to category archives and /page/N/ URLs, so they work with JavaScript
 * disabled, are crawlable, and can be shared or bookmarked. This endpoint lets
 * the browser swap the grid in place instead of reloading, and the script
 * rewrites the address bar to the same URL the link points at.
 *
 * The response is rendered through template-parts/journal/posts.php — the same
 * file used on first load — so filtered markup can never drift from initial
 * markup.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The AJAX action name, shared by the handler and the script.
 */
const ABERDEEN_PIANO_JOURNAL_ACTION = 'aberdeen_piano_journal';

/**
 * Is the current request one of the Journal listing views?
 *
 * @return bool
 */
function aberdeen_piano_is_journal() {
	return ( is_home() && ! is_front_page() ) || is_category() || is_tag() || is_author() || is_date() || is_search();
}

/**
 * Enqueue the Journal script only where the listing is shown.
 *
 * @return void
 */
function aberdeen_piano_journal_assets() {
	if ( ! aberdeen_piano_is_journal() ) {
		return;
	}

	wp_enqueue_script(
		'aberdeen-piano-journal',
		get_template_directory_uri() . '/assets/js/journal.js',
		array( 'aberdeen-piano-theme' ),
		aberdeen_piano_asset_version( 'assets/js/journal.js' ),
		true
	);

	wp_localize_script(
		'aberdeen-piano-journal',
		'aberdeenPianoJournal',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'action'  => ABERDEEN_PIANO_JOURNAL_ACTION,
			'nonce'   => wp_create_nonce( ABERDEEN_PIANO_JOURNAL_ACTION ),
			'home'    => aberdeen_piano_journal_url(),
			'i18n'    => array(
				'loading' => __( 'Loading posts…', 'aberdeen-piano' ),
				'error'   => __( 'Something went wrong. Please try again.', 'aberdeen-piano' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aberdeen_piano_journal_assets' );

/**
 * Return a rendered post grid for the requested category, page and search term.
 *
 * @return void
 */
function aberdeen_piano_journal_ajax() {
	check_ajax_referer( ABERDEEN_PIANO_JOURNAL_ACTION, 'nonce' );

	$category = isset( $_POST['category'] ) ? sanitize_title( wp_unslash( $_POST['category'] ) ) : 'all';
	$paged    = isset( $_POST['paged'] ) ? absint( wp_unslash( $_POST['paged'] ) ) : 1;
	$search   = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
	$paged    = max( 1, $paged );

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'paged'               => $paged,
		'posts_per_page'      => (int) get_option( 'posts_per_page' ),
		'ignore_sticky_posts' => true,
	);

	// Only accept a category that actually exists; anything else means "all".
	$term = ( $category && 'all' !== $category ) ? get_term_by( 'slug', $category, 'category' ) : null;

	if ( $term instanceof WP_Term ) {
		$args['cat'] = $term->term_id;
		$base        = get_category_link( $term->term_id );
	} else {
		$category = 'all';
		$base     = aberdeen_piano_journal_url();
	}

	if ( '' !== $search ) {
		$args['s'] = $search;
		$base      = add_query_arg( 's', rawurlencode( $search ), aberdeen_piano_journal_url() );
	}

	$query = new WP_Query( $args );

	// Swap the main query so the shared template part behaves exactly as it does
	// on a normal request, then restore it.
	$previous_query = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;
	$previous_post  = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

	$GLOBALS['wp_query'] = $query;

	aberdeen_piano_pagination_base( $search ? '' : $base );

	ob_start();
	get_template_part( 'template-parts/journal/posts' );
	$html = ob_get_clean();

	aberdeen_piano_pagination_base( '' );
	wp_reset_postdata();

	$GLOBALS['wp_query'] = $previous_query;
	$GLOBALS['post']     = $previous_post;

	// The URL this view is reachable at, for history.pushState().
	$url = $base;

	if ( $paged > 1 ) {
		$url = ( '' !== $search )
			? add_query_arg( 'paged', $paged, $base )
			: trailingslashit( $base ) . user_trailingslashit( 'page/' . $paged, 'paged' );
	}

	wp_send_json_success(
		array(
			'html'     => $html,
			'url'      => $url,
			'category' => $category,
			'paged'    => $paged,
			'maxPages' => (int) $query->max_num_pages,
			'found'    => (int) $query->found_posts,
		)
	);
}
add_action( 'wp_ajax_' . ABERDEEN_PIANO_JOURNAL_ACTION, 'aberdeen_piano_journal_ajax' );
add_action( 'wp_ajax_nopriv_' . ABERDEEN_PIANO_JOURNAL_ACTION, 'aberdeen_piano_journal_ajax' );
