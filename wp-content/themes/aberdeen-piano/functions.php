<?php
/**
 * Aberdeen Piano theme functions.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ABERDEEN_PIANO_VERSION' ) ) {
	define( 'ABERDEEN_PIANO_VERSION', '1.0.0' );
}

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/defaults-pages.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/class-aberdeen-piano-nav-walker.php';
require_once get_template_directory() . '/inc/journal.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/single.php';
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/journal-ajax.php';
require_once get_template_directory() . '/inc/acf-fields-journal.php';
require_once get_template_directory() . '/inc/acf-fields-pages.php';
require_once get_template_directory() . '/inc/admin-theme.php';

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/class-aberdeen-piano-leads-table.php';
	require_once get_template_directory() . '/inc/admin-leads.php';
}

/**
 * Theme supports and navigation menus.
 */
function aberdeen_piano_setup() {
	load_theme_textdomain( 'aberdeen-piano', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 96,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'aberdeen-piano' ),
			'footer'  => __( 'Footer Menu', 'aberdeen-piano' ),
		)
	);
}
add_action( 'after_setup_theme', 'aberdeen_piano_setup' );

/**
 * Content width.
 */
function aberdeen_piano_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'aberdeen_piano_content_width', 1160 );
}
add_action( 'after_setup_theme', 'aberdeen_piano_content_width', 0 );

/**
 * Front-end styles and scripts.
 */
function aberdeen_piano_assets() {
	wp_enqueue_style(
		'aberdeen-piano-style',
		get_stylesheet_uri(),
		array(),
		aberdeen_piano_asset_version( 'style.css' )
	);

	wp_enqueue_script(
		'aberdeen-piano-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		aberdeen_piano_asset_version( 'assets/js/theme.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aberdeen_piano_assets' );

/**
 * Keep the browser from caching front-end HTML while developing.
 *
 * style.css is already versioned by filemtime, so editing it always changes
 * its URL — but the browser only learns the new URL if it re-fetches the page.
 * WordPress sends no cache headers to logged-out visitors on the front end, so
 * the browser applies heuristic caching to the HTML and keeps replaying the old
 * markup with the old ?ver=. That is why appending a random query string to the
 * page URL looked like the only way to see a change: it was busting the cached
 * page, not the cached stylesheet.
 *
 * Gated on WP_DEBUG so a production install is unaffected.
 *
 * @param array $headers Headers WordPress is about to send.
 * @return array
 */
function aberdeen_piano_dev_nocache_headers( $headers ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || is_admin() ) {
		return $headers;
	}

	$headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0';
	$headers['Pragma']        = 'no-cache';
	$headers['Expires']       = 'Wed, 11 Jan 1984 05:00:00 GMT';

	return $headers;
}
add_filter( 'wp_headers', 'aberdeen_piano_dev_nocache_headers' );

/**
 * Sidebar for the Journal (blog) templates.
 */
function aberdeen_piano_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Journal Sidebar', 'aberdeen-piano' ),
			'id'            => 'journal-sidebar',
			'description'   => __( 'Widgets shown beside the Journal listing and single posts.', 'aberdeen-piano' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'aberdeen_piano_widgets_init' );

/**
 * Render a registered menu through the theme's walker.
 *
 * Falls back to the anchors from the static design when no menu is assigned, so
 * the header and footer are never empty on a fresh install.
 *
 * @param string $location Registered menu location.
 * @return void
 */
function aberdeen_piano_nav_menu( $location ) {
	$is_primary = 'primary' === $location;

	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location'       => $location,
				'container'            => 'nav',
				'container_class'      => $is_primary ? 'nav-links' : 'footer-nav',
				'container_aria_label' => $is_primary ? __( 'Main navigation', 'aberdeen-piano' ) : __( 'Footer navigation', 'aberdeen-piano' ),
				'items_wrap'           => '%3$s',
				'depth'                => 1,
				'walker'               => new Aberdeen_Piano_Nav_Walker(),
				'fallback_cb'          => false,
			)
		);

		return;
	}

	aberdeen_piano_nav_menu_fallback( $location );
}

/**
 * The default menus from the static design.
 *
 * @param string $location Registered menu location.
 * @return void
 */
function aberdeen_piano_nav_menu_fallback( $location ) {
	$is_primary = 'primary' === $location;

	$links = $is_primary
		? array(
			'#about'      => __( 'About', 'aberdeen-piano' ),
			'#the-studio' => __( 'The Studio', 'aberdeen-piano' ),
			'#calendar'   => __( 'Calendar', 'aberdeen-piano' ),
			'#pricing'    => __( 'Tuition', 'aberdeen-piano' ),
			'#journal'    => __( 'Journal', 'aberdeen-piano' ),
			'#contact'    => __( 'Contact', 'aberdeen-piano' ),
		)
		: array(
			'#about'    => __( 'About', 'aberdeen-piano' ),
			'#lessons'  => __( 'Lessons', 'aberdeen-piano' ),
			'#calendar' => __( 'Calendar', 'aberdeen-piano' ),
			'#pricing'  => __( 'Tuition', 'aberdeen-piano' ),
			'#journal'  => __( 'Journal', 'aberdeen-piano' ),
			'#contact'  => __( 'Contact', 'aberdeen-piano' ),
		);

	$base = aberdeen_piano_anchor_base();

	printf(
		'<nav%s aria-label="%s">',
		$is_primary ? ' class="nav-links"' : ' class="footer-nav"',
		esc_attr( $is_primary ? __( 'Main navigation', 'aberdeen-piano' ) : __( 'Footer navigation', 'aberdeen-piano' ) )
	);

	// Highlight Journal across every journal view, matching the design.
	$on_journal = ! is_front_page() && ( is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() || is_search() );

	foreach ( $links as $anchor => $label ) {
		$is_journal = ( '#journal' === $anchor );
		$url        = $is_journal ? aberdeen_piano_journal_url() : $base . $anchor;
		$current    = ( $is_journal && $on_journal );

		printf(
			'<a href="%s"%s>%s</a>',
			esc_url( $url ),
			$current ? ' class="current" aria-current="page"' : '',
			esc_html( $label )
		);
	}

	echo '</nav>';
}

/**
 * Use the brand name as the site half of the document title.
 *
 * The tab currently reads "… – Ireneaberdeen", which is the WordPress site
 * title from Settings → General. The theme already carries a brand name for
 * the header, the footer credit and image alt text, so the title bar uses the
 * same one rather than a second, unrelated name.
 *
 * @param array $parts Document title parts.
 * @return array
 */
function aberdeen_piano_document_site_name( $parts ) {
	if ( empty( $parts['site'] ) ) {
		return $parts;
	}

	$brand = trim( (string) aberdeen_piano_brand_name() );

	if ( '' !== $brand ) {
		$parts['site'] = $brand;
	}

	return $parts;
}
add_filter( 'document_title_parts', 'aberdeen_piano_document_site_name', 20 );

/**
 * Favicon fallback when no site icon is set in the Customizer.
 */
function aberdeen_piano_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	$img = get_template_directory_uri() . '/assets/img/';

	printf( '<link rel="icon" href="%s" sizes="any">' . "\n", esc_url( $img . 'favicon.ico' ) );
	printf( '<link rel="icon" type="image/png" href="%s">' . "\n", esc_url( $img . 'favicon.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $img . 'favicon.png' ) );
}
add_action( 'wp_head', 'aberdeen_piano_favicon' );

/**
 * Body classes — the loader locks scrolling until the page has loaded.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aberdeen_piano_body_classes( $classes ) {
	// The loader covers every page, as in the design, and locks scrolling until
	// the script releases it on load.
	$classes[] = 'locked';

	return $classes;
}
add_filter( 'body_class', 'aberdeen_piano_body_classes' );

/**
 * Admin notice when ACF is missing — the site still renders the design defaults.
 */
function aberdeen_piano_acf_notice() {
	if ( aberdeen_piano_has_acf() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html__( 'Aberdeen Piano: install and activate Advanced Custom Fields to edit the home page content. Until then the site shows the built-in design defaults.', 'aberdeen-piano' )
	);
}
add_action( 'admin_notices', 'aberdeen_piano_acf_notice' );


// Disable Gutenberg block editor for posts
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg block editor for widgets
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Style the classic editor so writing a page resembles reading it.
 *
 * @return void
 */
function aberdeen_piano_editor_styles() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'aberdeen_piano_editor_styles' );

/**
 * Let wide tables in page or post content scroll instead of breaking the layout.
 *
 * The editor emits a bare <table>, which overflows on a phone. Wrapping it in a
 * scrolling container keeps the page itself from scrolling sideways.
 *
 * @param string $content Post content.
 * @return string
 */
function aberdeen_piano_wrap_tables( $content ) {
	if ( is_admin() || false === strpos( $content, '<table' ) ) {
		return $content;
	}

	// Skip tables already inside a wrapper (a figure, or a previous run).
	return preg_replace(
		'#(?<!<div class="table-scroll">)(<table[\s>].*?</table>)#is',
		'<div class="table-scroll">$1</div>',
		$content
	);
}
add_filter( 'the_content', 'aberdeen_piano_wrap_tables', 20 );
