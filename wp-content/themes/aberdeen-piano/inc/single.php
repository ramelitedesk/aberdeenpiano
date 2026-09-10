<?php
/**
 * Single article helpers — table of contents, share links and related posts.
 *
 * The design's article page has a sidebar "On this page" nav whose links point
 * at ids on the <h2> headings in the body. Editors write plain content, so the
 * ids are added and the contents list is built from the rendered HTML here.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prepare article content for display.
 *
 * Adds an id to every <h2> that lacks one, gives the first paragraph the
 * design's .lead class, and returns the headings for the contents list.
 *
 * @param string $html Rendered post content.
 * @return array {
 *     @type string $html Adjusted HTML.
 *     @type array  $toc  List of array( id, text ).
 * }
 */
function aberdeen_piano_prepare_content( $html ) {
	$result = array(
		'html' => $html,
		'toc'  => array(),
	);

	if ( '' === trim( $html ) || ! class_exists( 'DOMDocument' ) ) {
		return $result;
	}

	$dom      = new DOMDocument();
	$previous = libxml_use_internal_errors( true );

	// The meta charset keeps DOMDocument from mangling UTF-8; LIBXML_HTML_NOIMPLIED
	// and NODEFDTD stop it wrapping the fragment in <html><body>.
	$loaded = $dom->loadHTML(
		'<?xml encoding="utf-8" ?><div id="aberdeen-piano-root">' . $html . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);

	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded ) {
		return $result;
	}

	$root = $dom->getElementById( 'aberdeen-piano-root' );

	if ( ! $root ) {
		return $result;
	}

	$used = array();

	foreach ( $dom->getElementsByTagName( 'h2' ) as $heading ) {
		$text = trim( $heading->textContent );

		if ( '' === $text ) {
			continue;
		}

		$id = $heading->getAttribute( 'id' );

		if ( '' === $id ) {
			$id = sanitize_title( $text );

			// Keep ids unique when two headings share a title.
			$base  = $id;
			$index = 2;

			while ( isset( $used[ $id ] ) ) {
				$id = $base . '-' . $index;
				$index++;
			}

			$heading->setAttribute( 'id', $id );
		}

		$used[ $id ] = true;

		$result['toc'][] = array(
			'id'   => $id,
			'text' => $text,
		);
	}

	// The design opens the article with a larger .lead paragraph.
	foreach ( $root->childNodes as $node ) {
		if ( XML_ELEMENT_NODE === $node->nodeType && 'p' === $node->nodeName ) {
			$classes = trim( $node->getAttribute( 'class' ) . ' lead' );
			$node->setAttribute( 'class', ltrim( $classes ) );
			break;
		}
	}

	$inner = '';

	foreach ( $root->childNodes as $child ) {
		$inner .= $dom->saveHTML( $child );
	}

	$result['html'] = $inner;

	return $result;
}

/**
 * Share links for the current post.
 *
 * @return array List of array( label, title, url, icon, attrs ).
 */
function aberdeen_piano_share_links() {
	$url   = get_permalink();
	$title = get_the_title();

	$links = array(
		array(
			'icon'  => 'f',
			'title' => __( 'Share on Facebook', 'aberdeen-piano' ),
			'url'   => add_query_arg( 'u', rawurlencode( $url ), 'https://www.facebook.com/sharer/sharer.php' ),
			'attrs' => ' target="_blank" rel="noopener noreferrer"',
		),
		array(
			'icon'  => '&#120143;',
			'title' => __( 'Share on X', 'aberdeen-piano' ),
			'url'   => add_query_arg(
				array(
					'url'  => rawurlencode( $url ),
					'text' => rawurlencode( $title ),
				),
				'https://twitter.com/intent/tweet'
			),
			'attrs' => ' target="_blank" rel="noopener noreferrer"',
		),
		array(
			'icon'  => '&#9993;',
			'title' => __( 'Share by email', 'aberdeen-piano' ),
			'url'   => 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url ),
			'attrs' => '',
		),
		array(
			'icon'  => '&#128279;',
			'title' => __( 'Copy link', 'aberdeen-piano' ),
			'url'   => $url,
			'attrs' => ' data-copy-link data-copied-title="' . esc_attr__( 'Link copied', 'aberdeen-piano' ) . '"',
		),
	);

	return apply_filters( 'aberdeen_piano_share_links', $links );
}

/**
 * Posts related to the current one — same category first, topped up with recent
 * posts so the row is never short.
 *
 * @param int $count How many posts to return.
 * @return WP_Post[]
 */
function aberdeen_piano_related_posts( $count = 3 ) {
	$post_id    = get_the_ID();
	$categories = wp_get_post_categories( $post_id );

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$related = array();

	if ( $categories ) {
		$related = get_posts( array_merge( $args, array( 'category__in' => $categories ) ) );
	}

	if ( count( $related ) < $count ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) );

		$filler = get_posts(
			array_merge(
				$args,
				array(
					'posts_per_page' => $count - count( $related ),
					'post__not_in'   => $exclude,
				)
			)
		);

		$related = array_merge( $related, $filler );
	}

	return $related;
}

/**
 * Recent posts for the sidebar's "Keep reading" widget.
 *
 * @param int $count How many posts to return.
 * @return WP_Post[]
 */
function aberdeen_piano_keep_reading( $count = 3 ) {
	return get_posts(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $count,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * The author's avatar for the byline and author box.
 *
 * @param int $size Pixel size.
 * @return string Image URL.
 */
function aberdeen_piano_author_avatar( $size = 200 ) {
	$custom = aberdeen_piano_option( 'ap_author_photo', '' );
	$url    = aberdeen_piano_image_url( $custom, '', 'medium' );

	if ( $url ) {
		return $url;
	}

	return get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => $size ) );
}
