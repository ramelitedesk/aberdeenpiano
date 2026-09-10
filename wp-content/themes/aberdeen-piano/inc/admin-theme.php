<?php
/**
 * Brands wp-admin, the login screen and ACF to match the site.
 *
 * Presentation only — no admin behaviour is changed, and nothing is hidden, so
 * the admin keeps working the way an editor expects.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the admin stylesheet on every admin screen.
 *
 * @return void
 */
function aberdeen_piano_admin_styles() {
	wp_enqueue_style(
		'aberdeen-piano-admin',
		get_template_directory_uri() . '/assets/css/admin.css',
		array(),
		aberdeen_piano_asset_version( 'assets/css/admin.css' )
	);
}
add_action( 'admin_enqueue_scripts', 'aberdeen_piano_admin_styles' );

/**
 * Load the login stylesheet, pointing it at the studio logo.
 *
 * @return void
 */
function aberdeen_piano_login_styles() {
	wp_enqueue_style(
		'aberdeen-piano-login',
		get_template_directory_uri() . '/assets/css/login.css',
		array(),
		aberdeen_piano_asset_version( 'assets/css/login.css' )
	);

	wp_add_inline_style(
		'aberdeen-piano-login',
		':root{--ap-login-logo:url(' . esc_url( aberdeen_piano_logo_url() ) . ');}'
	);
}
add_action( 'login_enqueue_scripts', 'aberdeen_piano_login_styles' );

/**
 * Point the login logo at the site rather than wordpress.org.
 *
 * @return string
 */
function aberdeen_piano_login_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'aberdeen_piano_login_url' );

/**
 * Title attribute for the login logo.
 *
 * @return string
 */
function aberdeen_piano_login_title() {
	return aberdeen_piano_brand_name();
}
add_filter( 'login_headertext', 'aberdeen_piano_login_title' );

/**
 * Credit line in the admin footer.
 *
 * @param string $text Existing footer text.
 * @return string
 */
function aberdeen_piano_admin_footer( $text ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return $text;
	}

	return sprintf(
		/* translators: %s: brand name. */
		esc_html__( '%s — site managed with the Aberdeen Piano theme.', 'aberdeen-piano' ),
		esc_html( aberdeen_piano_brand_name() )
	);
}
add_filter( 'admin_footer_text', 'aberdeen_piano_admin_footer' );
