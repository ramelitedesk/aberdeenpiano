<?php
/**
 * Navigation walker.
 *
 * The design's header and footer are flat lists of anchors inside a container —
 * no <ul>/<li> — because .nav-links styles its direct <a> children. This walker
 * renders wp_nav_menu() into exactly that markup while keeping the classes and
 * ARIA state WordPress expects.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders a menu as a flat run of <a> elements.
 */
class Aberdeen_Piano_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Database fields to use.
	 *
	 * @var array
	 */
	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id'     => 'db_id',
	);

	/**
	 * No wrapper element is opened — the container supplies it.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '';
	}

	/**
	 * No wrapper element is closed.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '';
	}

	/**
	 * Render one menu item as an anchor.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		/*
		 * Drop WordPress' structural and state classes — the design styles the
		 * active link with .nav-links a.current, added below. Anything the
		 * client typed into the menu item's own CSS Classes field is kept.
		 */
		$noise   = array( 'menu-item', 'current-', 'current_', 'page-item', 'page_item' );
		$classes = array_filter(
			$classes,
			static function ( $class ) use ( $noise ) {
				foreach ( $noise as $prefix ) {
					if ( 0 === strpos( $class, $prefix ) ) {
						return false;
					}
				}

				return (bool) $class;
			}
		);

		if ( in_array( 'current-menu-item', (array) $item->classes, true ) || in_array( 'current_page_item', (array) $item->classes, true ) ) {
			$classes[] = 'current';
		}

		/*
		 * The posts page item stays highlighted across every Journal view —
		 * single posts, categories, tags, author, date and search — matching the
		 * fallback menu in functions.php.
		 */
		if ( ! in_array( 'current', $classes, true ) && $this->is_journal_item( $item ) ) {
			$classes[] = 'current';
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

		$atts = array(
			'href'   => ! empty( $item->url ) ? $item->url : '',
			'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => ! empty( $item->target ) ? $item->target : '',
			'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
			'class'  => $classes ? implode( ' ', array_map( 'sanitize_html_class', $classes ) ) : '',
		);

		if ( '_blank' === $atts['target'] && empty( $atts['rel'] ) ) {
			$atts['rel'] = 'noopener noreferrer';
		}

		if ( in_array( 'current', $classes, true ) ) {
			$atts['aria-current'] = 'page';
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' === $value || false === $value ) {
				continue;
			}

			$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$html = '<a' . $attributes . '>' . esc_html( $title ) . '</a>';

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$output .= apply_filters( 'walker_nav_menu_start_el', $html, $item, $depth, $args );
	}

	/**
	 * Whether this item points at the posts page while a Journal view is shown.
	 *
	 * @param WP_Post $item Menu item data object.
	 * @return bool
	 */
	protected function is_journal_item( $item ) {
		$posts_page = (int) get_option( 'page_for_posts' );

		if ( ! $posts_page || 'post_type' !== $item->type || (int) $item->object_id !== $posts_page ) {
			return false;
		}

		return ! is_front_page() && ( is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() || is_search() );
	}

	/**
	 * Anchors are self-contained, so nothing to close.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '';
	}
}
