<?php
/**
 * Template tags — the single bridge between ACF and the templates.
 *
 * Templates never call get_field() directly. They call these helpers, which
 * resolve a value from ACF and fall back to inc/defaults.php whenever the field
 * is empty or ACF is not active.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is ACF available?
 *
 * @return bool
 */
function aberdeen_piano_has_acf() {
	return function_exists( 'get_field' );
}

/**
 * Treat null, '', false and array() as "not filled in".
 *
 * @param mixed $value Value to test.
 * @return bool
 */
function aberdeen_piano_is_empty( $value ) {
	if ( is_array( $value ) ) {
		return empty( $value );
	}

	return null === $value || false === $value || '' === $value;
}

/**
 * A field on the current (or given) post, with a default from the defaults tree.
 *
 * @param string $name         ACF field name.
 * @param string $default_path Dot path into aberdeen_piano_defaults().
 * @param mixed  $post_id      Optional ACF post id.
 * @return mixed
 */
function aberdeen_piano_field( $name, $default_path = '', $post_id = false ) {
	$default = '' === $default_path ? '' : aberdeen_piano_default( $default_path );

	if ( ! aberdeen_piano_has_acf() ) {
		return $default;
	}

	$value = get_field( $name, $post_id );

	return aberdeen_piano_is_empty( $value ) ? $default : $value;
}

/**
 * A field from the theme options page.
 *
 * @param string $name         ACF field name.
 * @param string $default_path Dot path into aberdeen_piano_defaults().
 * @return mixed
 */
function aberdeen_piano_option( $name, $default_path = '' ) {
	return aberdeen_piano_field( $name, $default_path, 'option' );
}

/**
 * A repeater's rows, normalised to an array of arrays.
 *
 * @param string $name         ACF repeater field name.
 * @param string $default_path Dot path to the default rows.
 * @param mixed  $post_id      Optional ACF post id.
 * @return array
 */
function aberdeen_piano_rows( $name, $default_path = '', $post_id = false ) {
	$rows = aberdeen_piano_field( $name, $default_path, $post_id );

	return is_array( $rows ) ? $rows : array();
}

/**
 * Read a key from a repeater row, falling back to a default row.
 *
 * @param array  $row     The ACF row.
 * @param string $key     Sub field name.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function aberdeen_piano_row( $row, $key, $default = '' ) {
	if ( is_array( $row ) && array_key_exists( $key, $row ) && ! aberdeen_piano_is_empty( $row[ $key ] ) ) {
		return $row[ $key ];
	}

	return $default;
}

/**
 * A boolean sub field — false is a meaningful value, so it is not "empty".
 *
 * @param array  $row     The ACF row.
 * @param string $key     Sub field name.
 * @param bool   $default Fallback value.
 * @return bool
 */
function aberdeen_piano_row_bool( $row, $key, $default = false ) {
	if ( is_array( $row ) && array_key_exists( $key, $row ) && null !== $row[ $key ] && '' !== $row[ $key ] ) {
		return (bool) $row[ $key ];
	}

	return $default;
}

/**
 * Escape body copy, turning newlines into <br>.
 *
 * A new repeater row starts a new paragraph; a line break inside one row is a
 * <br> within that paragraph. This mirrors the static design, where some copy
 * runs two sentences into a single <p>.
 *
 * @param string $text Raw text.
 * @return string
 */
function aberdeen_piano_paragraph( $text ) {
	return nl2br( esc_html( trim( (string) $text ) ), false );
}

/**
 * Echo a paragraph row's text.
 *
 * @param array  $row Repeater row.
 * @param string $key Sub field name.
 * @return void
 */
function aberdeen_piano_the_paragraph( $row, $key = 'text' ) {
	echo aberdeen_piano_paragraph( aberdeen_piano_row( $row, $key ) ); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- escaped by aberdeen_piano_paragraph().
}

/**
 * The inline HTML the client may use inside headings.
 *
 * @return array
 */
function aberdeen_piano_heading_tags() {
	return array(
		'em'     => array( 'class' => array() ),
		'i'      => array( 'class' => array() ),
		'b'      => array( 'class' => array() ),
		'strong' => array( 'class' => array() ),
		'span'   => array( 'class' => array() ),
		'br'     => array( 'class' => array() ),
		'sup'    => array( 'class' => array() ),
		'small'  => array(),
	);
}

/**
 * Escape a heading while keeping the small set of inline tags above.
 *
 * @param string $html Raw heading value.
 * @return string
 */
function aberdeen_piano_kses_heading( $html ) {
	$html = wp_kses( (string) $html, aberdeen_piano_heading_tags() );

	/*
	 * Headings carry hard <br> breaks chosen for the desktop column. Stacked on
	 * a tablet the column is far wider, so those breaks leave the heading using
	 * half the width it has. CSS can hide a <br>, but the words either side of
	 * it are written without a space ("learned<br>one"), so hiding it alone
	 * would run them together. Tagging the break and following it with a space
	 * lets the stylesheet drop the break and keep the words apart; on desktop
	 * the space sits at the start of a line, where it collapses away.
	 */
	return preg_replace( '#<br\s*/?>#i', '<br class="hb"> ', $html );
}

/**
 * A price period ("/ month") ready to sit inline after an amount.
 *
 * These cards were designed with the period stacked under the amount as an
 * uppercase label, so the saved values carry no separator — "month", "hour".
 * Read inline they need one. A value that already starts with a slash or a dash
 * is returned untouched, so content written either way renders correctly.
 *
 * @param string $value Raw period value.
 * @return string
 */
function aberdeen_piano_period( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value || preg_match( '#^[/\p{Pd}]#u', $value ) ) {
		return $value;
	}

	return '/ ' . $value;
}

/**
 * Echo a heading field.
 *
 * @param string $name         ACF field name.
 * @param string $default_path Dot path to the default.
 * @return void
 */
function aberdeen_piano_the_heading( $name, $default_path ) {
	echo aberdeen_piano_kses_heading( aberdeen_piano_field( $name, $default_path ) ); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- escaped by aberdeen_piano_kses_heading().
}

/**
 * Normalise an ACF link/image value plus the matching default.
 *
 * ACF link fields return array( url, title, target ); this accepts that, a bare
 * URL string, or a default array of the same shape.
 *
 * @param mixed $value   Field value.
 * @param array $default Default link array.
 * @return array {
 *     @type string $url    Link URL.
 *     @type string $title  Link label.
 *     @type string $target Link target.
 * }
 */
function aberdeen_piano_link( $value, $default = array() ) {
	$link = wp_parse_args(
		is_array( $value ) ? $value : array(),
		wp_parse_args(
			$default,
			array(
				'url'    => '',
				'title'  => '',
				'target' => '',
			)
		)
	);

	if ( is_string( $value ) && '' !== $value ) {
		$link['url'] = $value;
	}

	return $link;
}

/**
 * Resolve an ACF image field to a URL.
 *
 * Handles the array, id and url return formats, plus a plain URL default.
 *
 * @param mixed  $value   Field value.
 * @param string $default Default URL.
 * @param string $size    Image size for id/array values.
 * @return string
 */
function aberdeen_piano_image_url( $value, $default = '', $size = 'large' ) {
	if ( is_array( $value ) && ! empty( $value['ID'] ) ) {
		$src = wp_get_attachment_image_src( $value['ID'], $size );

		return $src ? $src[0] : $default;
	}

	if ( is_numeric( $value ) && $value ) {
		$src = wp_get_attachment_image_src( (int) $value, $size );

		return $src ? $src[0] : $default;
	}

	if ( is_string( $value ) && '' !== $value ) {
		return $value;
	}

	return $default;
}

/**
 * Alt text for an ACF image field.
 *
 * @param mixed  $value   Field value.
 * @param string $default Default alt text.
 * @return string
 */
function aberdeen_piano_image_alt( $value, $default = '' ) {
	if ( is_array( $value ) && ! empty( $value['alt'] ) ) {
		return $value['alt'];
	}

	if ( is_numeric( $value ) && $value ) {
		$alt = get_post_meta( (int) $value, '_wp_attachment_image_alt', true );

		if ( $alt ) {
			return $alt;
		}
	}

	$default = trim( (string) $default );

	/*
	 * Never return an empty string. Every caller writes the result straight into
	 * an alt attribute, and a picture whose ACF alt field and attachment alt are
	 * both blank would otherwise publish as alt="". The brand name is the last
	 * resort, not the intent — a real description belongs in the media library
	 * or the field beside the image.
	 */
	return '' !== $default ? $default : aberdeen_piano_brand_name();
}

/**
 * Alt text for a post's featured image.
 *
 * Prefers the alt text set on the attachment in the media library, because that
 * describes the picture. Falls back to the post title, so a thumbnail is never
 * published with an empty alt just because nobody filled the field in.
 *
 * @param int|null $post_id Post the thumbnail belongs to.
 * @return string
 */
function aberdeen_piano_thumbnail_alt( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	$thumb_id = get_post_thumbnail_id( $post_id );

	if ( $thumb_id ) {
		$alt = trim( (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) );

		if ( '' !== $alt ) {
			return $alt;
		}
	}

	$title = trim( wp_strip_all_tags( get_the_title( $post_id ) ) );

	return '' !== $title ? $title : aberdeen_piano_brand_name();
}

/**
 * The studio e-mail address.
 *
 * @return string
 */
function aberdeen_piano_email() {
	return apply_filters( 'aberdeen_piano_email', aberdeen_piano_option( 'ap_email', 'global.email' ) );
}

/**
 * The studio address as an array of lines.
 *
 * @return array
 */
function aberdeen_piano_address() {
	$address = aberdeen_piano_option( 'ap_address', 'global.address' );
	$lines   = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $address ) ) );

	return apply_filters( 'aberdeen_piano_address', array_values( $lines ) );
}

/**
 * The brand name shown in the header, falling back to the site title.
 *
 * @return string
 */
function aberdeen_piano_brand_name() {
	$name = aberdeen_piano_option( 'ap_brand_name', 'global.brand_name' );

	return $name ? $name : get_bloginfo( 'name' );
}

/**
 * The brand logo URL — custom logo, then ACF, then the bundled mark.
 *
 * @return string
 */
function aberdeen_piano_logo_url() {
	$custom = get_theme_mod( 'custom_logo' );

	if ( $custom ) {
		$src = wp_get_attachment_image_src( $custom, 'full' );

		if ( $src ) {
			return $src[0];
		}
	}

	return aberdeen_piano_image_url( aberdeen_piano_option( 'ap_logo', '' ), aberdeen_piano_img( 'aberdeen.png' ), 'full' );
}

/**
 * Path to a bundled theme image.
 *
 * @param string $file File name inside assets/img.
 * @return string
 */
function aberdeen_piano_img( $file ) {
	return get_template_directory_uri() . '/assets/img/' . ltrim( $file, '/' );
}

/**
 * URL of the Journal (posts) archive, falling back to the home page.
 *
 * @return string
 */
function aberdeen_piano_journal_url() {
	$blog_page = get_option( 'page_for_posts' );

	if ( $blog_page ) {
		return get_permalink( $blog_page );
	}

	return home_url( '/' );
}

/**
 * Prefix for in-page anchors — empty on the front page, the home URL elsewhere.
 *
 * @return string
 */
function aberdeen_piano_anchor_base() {
	return is_front_page() ? '' : home_url( '/' );
}

/**
 * Estimated reading time for the current post, e.g. "4 min read".
 *
 * @return string
 */
function aberdeen_piano_reading_time() {
	$words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );

	/* translators: %d: estimated reading time in minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'aberdeen-piano' ), $minutes );
}

/**
 * Cache-busting version for a theme asset.
 *
 * Uses the file's modification time so an edited stylesheet or script is picked
 * up immediately instead of being served from the browser cache until the theme
 * version is bumped by hand. Falls back to the theme version.
 *
 * @param string $relative_path Path relative to the theme root, e.g. 'assets/js/theme.js'.
 * @return string
 */
function aberdeen_piano_asset_version( $relative_path ) {
	$file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $file ) ) {
		return (string) filemtime( $file );
	}

	return ABERDEEN_PIANO_VERSION;
}

/**
 * ID of the page assigned as the posts page (the Journal).
 *
 * @return int
 */
function aberdeen_piano_journal_page_id() {
	return (int) get_option( 'page_for_posts' );
}

/**
 * Read a Journal or article field.
 *
 * The Journal field groups are attached to the posts page, so on a single
 * article — or inside an AJAX request, where there is no queried object —
 * get_field() would look at the wrong post and silently return nothing. This
 * always reads them from the posts page.
 *
 * @param string $name         ACF field name.
 * @param string $default_path Dot path into aberdeen_piano_defaults().
 * @return mixed
 */
function aberdeen_piano_journal_field( $name, $default_path = '' ) {
	$page_id = aberdeen_piano_journal_page_id();

	if ( ! $page_id ) {
		return aberdeen_piano_field( $name, $default_path );
	}

	return aberdeen_piano_field( $name, $default_path, $page_id );
}

/**
 * Loader copy and timing for the current view.
 *
 * The design shows the loader on every page, with different wording and a
 * shorter run on the Journal than on the home page.
 *
 * @return array
 */
function aberdeen_piano_loader() {
	$is_journal = ! is_front_page() && (
		is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() || is_search()
	);

	if ( $is_journal ) {
		return array(
			'label'    => aberdeen_piano_journal_field( 'ap_journal_loader_label', 'journal.loader_label' ),
			'mark'     => aberdeen_piano_journal_field( 'ap_journal_loader_mark', 'journal.loader_mark' ),
			'message'  => aberdeen_piano_journal_field( 'ap_journal_loader_message', 'journal.loader_message' ),
			'duration' => (int) aberdeen_piano_default( 'journal.loader_duration' ),
		);
	}

	return array(
		'label'    => aberdeen_piano_default( 'global.loader_label' ),
		'mark'     => aberdeen_piano_option( 'ap_loader_mark', 'global.loader_mark' ),
		'message'  => aberdeen_piano_option( 'ap_loader_message', 'global.loader_message' ),
		'duration' => (int) aberdeen_piano_default( 'global.loader_duration' ),
	);
}

/**
 * Resolve a link entered in ACF to a usable URL.
 *
 * Editors type "/#contact" or "#pricing" for on-site targets; those are made
 * absolute against the site root. Absolute URLs, mailto: and tel: are returned
 * untouched.
 *
 * @param string $url Raw value.
 * @return string
 */
function aberdeen_piano_page_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '';
	}

	if ( preg_match( '#^(https?:|mailto:|tel:|//|\#)#i', $url ) ) {
		return $url;
	}

	if ( 0 === strpos( $url, '/' ) ) {
		return home_url( $url );
	}

	return $url;
}

/**
 * Echo a value that may contain line breaks, e.g. a postal address.
 *
 * @param string $text Raw text.
 * @return void
 */
function aberdeen_piano_the_lines( $text ) {
	echo aberdeen_piano_paragraph( $text ); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- escaped by aberdeen_piano_paragraph().
}
