<?php
/**
 * ACF field groups for the four interior pages.
 *
 * Same architecture as inc/acf-fields.php: registered in code so they travel
 * with the theme, keys namespaced field_ap_<page>_<name>, names ap_<page>_<name>,
 * and every field optional so an empty one falls back to inc/defaults-pages.php.
 *
 * Location: each group is attached both to its page template and to the page
 * whose slug matches, so the fields appear whether the client assigns the
 * template explicitly or WordPress picks it up from the slug.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Location rules for an interior page.
 *
 * Produces an OR set: the page template, plus any page found at one of the
 * candidate slugs. Filterable so a site with different slugs can point the
 * groups elsewhere without touching the theme.
 *
 * @param string $template Template file name, e.g. "page-about-us.php".
 * @param array  $slugs    Candidate page slugs.
 * @return array
 */
function aberdeen_piano_acf_page_location( $template, $slugs ) {
	$rules = array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => $template,
			),
		),
	);

	/**
	 * Filter the slugs a page group attaches to.
	 *
	 * @param array  $slugs    Candidate slugs.
	 * @param string $template Template file name.
	 */
	$slugs = (array) apply_filters( 'aberdeen_piano_acf_page_slugs', $slugs, $template );

	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );

		if ( $page ) {
			$rules[] = array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page->ID,
				),
			);
		}
	}

	return $rules;
}

/**
 * A group bound to one interior page.
 *
 * @param string $key      Group key suffix.
 * @param string $title    Group title.
 * @param int    $order    Metabox order.
 * @param string $template Template file name.
 * @param array  $slugs    Candidate page slugs.
 * @param array  $fields   Fields.
 * @return array
 */
function aberdeen_piano_acf_page_group( $key, $title, $order, $template, $slugs, $fields ) {
	return aberdeen_piano_acf_group(
		$key,
		$title,
		$order,
		$fields,
		array( 'location' => aberdeen_piano_acf_page_location( $template, $slugs ) )
	);
}

/**
 * A text field with a default drawn from the defaults tree.
 *
 * @param string $key          Key/name suffix.
 * @param string $label        Admin label.
 * @param string $default_path Dot path into the defaults.
 * @param array  $args         Extra arguments.
 * @return array
 */
function aberdeen_piano_acf_text( $key, $label, $default_path = '', $args = array() ) {
	if ( '' !== $default_path ) {
		$args['default_value'] = aberdeen_piano_default( $default_path );
	}

	return aberdeen_piano_acf_field( $key, $label, 'text', $args );
}

/**
 * A textarea field with a default drawn from the defaults tree.
 *
 * @param string $key          Key/name suffix.
 * @param string $label        Admin label.
 * @param string $default_path Dot path into the defaults.
 * @param int    $rows         Textarea rows.
 * @return array
 */
function aberdeen_piano_acf_textarea( $key, $label, $default_path = '', $rows = 3 ) {
	$args = array(
		'rows'      => $rows,
		'new_lines' => '',
	);

	if ( '' !== $default_path ) {
		$args['default_value'] = aberdeen_piano_default( $default_path );
	}

	return aberdeen_piano_acf_field( $key, $label, 'textarea', $args );
}

/**
 * A heading field — the small inline tag set is allowed inside.
 *
 * @param string $key          Key/name suffix.
 * @param string $label        Admin label.
 * @param string $default_path Dot path into the defaults.
 * @return array
 */
function aberdeen_piano_acf_heading( $key, $label, $default_path ) {
	return aberdeen_piano_acf_text(
		$key,
		$label,
		$default_path,
		array( 'instructions' => __( '&lt;em&gt;, &lt;br&gt; and &lt;sup&gt; are allowed.', 'aberdeen-piano' ) )
	);
}

/**
 * An image field plus its alt text.
 *
 * @param string $key       Key/name suffix.
 * @param string $label     Admin label.
 * @param string $alt_path  Dot path to the default alt text.
 * @return array Two fields.
 */
function aberdeen_piano_acf_image_pair( $key, $label, $alt_path = '' ) {
	return array(
		aberdeen_piano_acf_field(
			$key . '_image',
			$label,
			'image',
			array(
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'wrapper'       => array( 'width' => '50' ),
			)
		),
		aberdeen_piano_acf_text(
			$key . '_image_alt',
			__( 'Image alt text', 'aberdeen-piano' ),
			$alt_path,
			array(
				'wrapper'      => array( 'width' => '50' ),
				'instructions' => __( 'Describes the picture for screen readers.', 'aberdeen-piano' ),
			)
		),
	);
}

/**
 * A repeater with a fixed set of sub fields.
 *
 * @param string $key          Key/name suffix.
 * @param string $label        Admin label.
 * @param string $button       Add-row button label.
 * @param array  $sub_fields   Sub fields.
 * @param string $layout       ACF repeater layout.
 * @param array  $args         Extra arguments.
 * @return array
 */
function aberdeen_piano_acf_repeater( $key, $label, $button, $sub_fields, $layout = 'block', $args = array() ) {
	return array_merge(
		array(
			'key'          => 'field_ap_' . $key,
			'label'        => $label,
			'name'         => 'ap_' . $key,
			'type'         => 'repeater',
			'layout'       => $layout,
			'button_label' => $button,
			'sub_fields'   => $sub_fields,
		),
		$args
	);
}

/**
 * A repeater of one-line items, used for tick lists and pill labels.
 *
 * @param string $key    Key/name suffix.
 * @param string $label  Admin label.
 * @param string $button Add-row button label.
 * @return array
 */
function aberdeen_piano_acf_text_rows( $key, $label, $button ) {
	return aberdeen_piano_acf_repeater(
		$key,
		$label,
		$button,
		array( aberdeen_piano_acf_sub_field( $key . '_text', 'text', __( 'Text', 'aberdeen-piano' ), 'text' ) ),
		'table'
	);
}

/**
 * The shared hero fields every interior page uses.
 *
 * @param string $page Page key, e.g. "about".
 * @param string $path Defaults path prefix, e.g. "page_about.hero".
 * @return array
 */
function aberdeen_piano_acf_page_hero_fields( $page, $path ) {
	return array(
		aberdeen_piano_acf_field(
			$page . '_hero_image',
			__( 'Background image', 'aberdeen-piano' ),
			'image',
			array(
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => __( 'A wide, dark-friendly photograph. The overlay keeps the headline legible.', 'aberdeen-piano' ),
			)
		),
		aberdeen_piano_acf_text( $page . '_hero_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $path . '.eyebrow' ),
		aberdeen_piano_acf_heading( $page . '_hero_title', __( 'Title', 'aberdeen-piano' ), $path . '.title' ),
		aberdeen_piano_acf_textarea( $page . '_hero_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $path . '.lead', 3 ),
	);
}

/**
 * The shared anchor-bar fields (Services and Student Resources).
 *
 * @param string $page Page key.
 * @return array
 */
function aberdeen_piano_acf_page_index_field( $page ) {
	return aberdeen_piano_acf_repeater(
		$page . '_index_items',
		__( 'Anchor links', 'aberdeen-piano' ),
		__( 'Add link', 'aberdeen-piano' ),
		array(
			aberdeen_piano_acf_sub_field( $page . '_index_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text' ),
			aberdeen_piano_acf_sub_field( $page . '_index_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
			aberdeen_piano_acf_sub_field( $page . '_index_meta', 'meta', __( 'Meta', 'aberdeen-piano' ), 'text' ),
			aberdeen_piano_acf_sub_field(
				$page . '_index_anchor',
				'anchor',
				__( 'Anchor', 'aberdeen-piano' ),
				'text',
				array( 'instructions' => __( 'For example #student-lessons', 'aberdeen-piano' ) )
			),
		),
		'table'
	);
}

/**
 * The shared closing-panel fields.
 *
 * @param string $page Page key.
 * @param string $path Defaults path prefix, e.g. "page_about.cta".
 * @return array
 */
function aberdeen_piano_acf_page_cta_fields( $page, $path ) {
	return array(
		aberdeen_piano_acf_text( $page . '_cta_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $path . '.eyebrow' ),
		aberdeen_piano_acf_heading( $page . '_cta_heading', __( 'Heading', 'aberdeen-piano' ), $path . '.heading' ),
		aberdeen_piano_acf_textarea( $page . '_cta_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $path . '.lead', 3 ),
		aberdeen_piano_acf_repeater(
			$page . '_cta_buttons',
			__( 'Buttons', 'aberdeen-piano' ),
			__( 'Add button', 'aberdeen-piano' ),
			array(
				aberdeen_piano_acf_sub_field( $page . '_cta_button_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
				aberdeen_piano_acf_sub_field( $page . '_cta_button_url', 'url', __( 'URL', 'aberdeen-piano' ), 'text' ),
				aberdeen_piano_acf_sub_field(
					$page . '_cta_button_style',
					'style',
					__( 'Style', 'aberdeen-piano' ),
					'select',
					array(
						'choices'    => array(
							''          => __( 'Solid', 'aberdeen-piano' ),
							'cta-ghost' => __( 'Outline', 'aberdeen-piano' ),
						),
						'allow_null' => 0,
					)
				),
			),
			'table'
		),
		aberdeen_piano_acf_repeater(
			$page . '_cta_meta',
			__( 'Contact row', 'aberdeen-piano' ),
			__( 'Add item', 'aberdeen-piano' ),
			array(
				aberdeen_piano_acf_sub_field( $page . '_cta_meta_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
				aberdeen_piano_acf_sub_field( $page . '_cta_meta_value', 'value', __( 'Value', 'aberdeen-piano' ), 'text' ),
				aberdeen_piano_acf_sub_field(
					$page . '_cta_meta_url',
					'url',
					__( 'Link', 'aberdeen-piano' ),
					'text',
					array( 'instructions' => __( 'Optional. tel: and mailto: links are fine.', 'aberdeen-piano' ) )
				),
			),
			'table'
		),
	);
}

/**
 * Register the interior page groups.
 *
 * @param array $groups Existing groups.
 * @return array
 */
function aberdeen_piano_acf_page_groups( $groups ) {
	return array_merge(
		$groups,
		array(
			aberdeen_piano_acf_group_page_about(),
			aberdeen_piano_acf_group_page_services(),
			aberdeen_piano_acf_group_page_student(),
			aberdeen_piano_acf_group_page_contact(),
			aberdeen_piano_acf_group_page_abrsm(),
			aberdeen_piano_acf_group_page_activities(),
			aberdeen_piano_acf_group_page_calendar(),
			aberdeen_piano_acf_group_page_testimonials(),
			aberdeen_piano_acf_group_page_gallery(),
		)
	);
}
add_filter( 'aberdeen_piano_acf_groups', 'aberdeen_piano_acf_page_groups' );

/**
 * About Us.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_about() {
	$p          = 'page_about.';
	$hero       = aberdeen_piano_acf_page_hero_fields( 'about', $p . 'hero' );
	$cta        = aberdeen_piano_acf_page_cta_fields( 'about', $p . 'cta' );
	$intro_img  = aberdeen_piano_acf_image_pair( 'about_intro', __( 'Photograph', 'aberdeen-piano' ), $p . 'intro.image_alt' );
	$portrait   = aberdeen_piano_acf_image_pair( 'about_instructor', __( 'Portrait', 'aberdeen-piano' ), $p . 'instructor.image_alt' );

	return aberdeen_piano_acf_page_group(
		'page_about',
		__( 'About Us Page', 'aberdeen-piano' ),
		20,
		'page-about-us.php',
		array( 'about-us', 'about' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'about_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
			aberdeen_piano_acf_repeater(
				'about_hero_stats',
				__( 'Statistics', 'aberdeen-piano' ),
				__( 'Add statistic', 'aberdeen-piano' ),
				array(
					aberdeen_piano_acf_sub_field( 'about_hero_stat_value', 'value', __( 'Figure', 'aberdeen-piano' ), 'text', array( 'instructions' => __( '&lt;sup&gt; is allowed.', 'aberdeen-piano' ) ) ),
					aberdeen_piano_acf_sub_field( 'about_hero_stat_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
				),
				'table'
			),

			aberdeen_piano_acf_field( 'about_tab_intro', __( 'Studio introduction', 'aberdeen-piano' ), 'tab' ),
			$intro_img[0],
			$intro_img[1],
			aberdeen_piano_acf_text( 'about_intro_badge_label', __( 'Badge label', 'aberdeen-piano' ), $p . 'intro.badge_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
			aberdeen_piano_acf_text( 'about_intro_badge_value', __( 'Badge value', 'aberdeen-piano' ), $p . 'intro.badge_value', array( 'wrapper' => array( 'width' => '50' ) ) ),
			aberdeen_piano_acf_text( 'about_intro_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'intro.eyebrow' ),
			aberdeen_piano_acf_heading( 'about_intro_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'intro.heading' ),
			aberdeen_piano_acf_textarea( 'about_intro_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'intro.lead', 3 ),
			aberdeen_piano_acf_paragraphs( 'about_intro_paragraphs', 'ap_about_intro_paragraphs', __( 'Paragraphs', 'aberdeen-piano' ), __( 'Add paragraph', 'aberdeen-piano' ) ),
			aberdeen_piano_acf_text_rows( 'about_intro_marks', __( 'Pill labels', 'aberdeen-piano' ), __( 'Add label', 'aberdeen-piano' ) ),

			aberdeen_piano_acf_field( 'about_tab_mission', __( 'Mission', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_text( 'about_mission_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'mission.eyebrow' ),
			aberdeen_piano_acf_textarea( 'about_mission_quote', __( 'Mission statement', 'aberdeen-piano' ), $p . 'mission.quote', 4 ),
			aberdeen_piano_acf_text( 'about_mission_cite', __( 'Attribution', 'aberdeen-piano' ), $p . 'mission.cite' ),

			aberdeen_piano_acf_field( 'about_tab_approach', __( 'Teaching approach', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_text( 'about_approach_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'approach.eyebrow' ),
			aberdeen_piano_acf_heading( 'about_approach_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'approach.heading' ),
			aberdeen_piano_acf_textarea( 'about_approach_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'approach.intro', 3 ),
			aberdeen_piano_acf_repeater(
				'about_approach_cards',
				__( 'Cards', 'aberdeen-piano' ),
				__( 'Add card', 'aberdeen-piano' ),
				array(
					aberdeen_piano_acf_sub_field( 'about_approach_card_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
					aberdeen_piano_acf_sub_field( 'about_approach_card_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
					aberdeen_piano_acf_sub_field( 'about_approach_card_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				)
			),

			aberdeen_piano_acf_field( 'about_tab_philosophy', __( 'Philosophy', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_text( 'about_philosophy_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'philosophy.eyebrow' ),
			aberdeen_piano_acf_heading( 'about_philosophy_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'philosophy.heading' ),
			aberdeen_piano_acf_textarea( 'about_philosophy_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'philosophy.lead', 3 ),
			aberdeen_piano_acf_textarea( 'about_philosophy_paragraph', __( 'Second paragraph', 'aberdeen-piano' ), $p . 'philosophy.paragraph', 3 ),
			aberdeen_piano_acf_repeater(
				'about_philosophy_steps',
				__( 'Stages', 'aberdeen-piano' ),
				__( 'Add stage', 'aberdeen-piano' ),
				array(
					aberdeen_piano_acf_sub_field( 'about_philosophy_step_number', 'number', __( 'Numeral', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
					aberdeen_piano_acf_sub_field( 'about_philosophy_step_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
					aberdeen_piano_acf_sub_field( 'about_philosophy_step_description', 'description', __( 'Description', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				)
			),

			aberdeen_piano_acf_field( 'about_tab_instructor', __( 'Instructor', 'aberdeen-piano' ), 'tab' ),
			$portrait[0],
			$portrait[1],
			aberdeen_piano_acf_text( 'about_instructor_plate_name', __( 'Name plate — name', 'aberdeen-piano' ), $p . 'instructor.plate_name', array( 'wrapper' => array( 'width' => '50' ) ) ),
			aberdeen_piano_acf_text( 'about_instructor_plate_role', __( 'Name plate — role', 'aberdeen-piano' ), $p . 'instructor.plate_role', array( 'wrapper' => array( 'width' => '50' ) ) ),
			aberdeen_piano_acf_text( 'about_instructor_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'instructor.eyebrow' ),
			aberdeen_piano_acf_heading( 'about_instructor_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'instructor.heading' ),
			aberdeen_piano_acf_textarea( 'about_instructor_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'instructor.lead', 3 ),
			aberdeen_piano_acf_repeater(
				'about_instructor_facts',
				__( 'Profile panels', 'aberdeen-piano' ),
				__( 'Add panel', 'aberdeen-piano' ),
				array(
					aberdeen_piano_acf_sub_field( 'about_instructor_fact_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'about_instructor_fact_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				)
			),
			aberdeen_piano_acf_text( 'about_instructor_signature', __( 'Signature', 'aberdeen-piano' ), $p . 'instructor.signature', array( 'wrapper' => array( 'width' => '50' ) ) ),
			aberdeen_piano_acf_text( 'about_instructor_credentials', __( 'Credentials', 'aberdeen-piano' ), $p . 'instructor.credentials', array( 'wrapper' => array( 'width' => '50' ) ) ),

			aberdeen_piano_acf_field( 'about_tab_policies', __( 'Policies', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_text( 'about_policies_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'policies.eyebrow' ),
			aberdeen_piano_acf_heading( 'about_policies_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'policies.heading' ),
			aberdeen_piano_acf_textarea( 'about_policies_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'policies.intro', 3 ),
			aberdeen_piano_acf_textarea( 'about_policies_note', __( 'Side note', 'aberdeen-piano' ), $p . 'policies.note', 2 ),
			aberdeen_piano_acf_repeater(
				'about_policies_items',
				__( 'Policies', 'aberdeen-piano' ),
				__( 'Add policy', 'aberdeen-piano' ),
				array(
					aberdeen_piano_acf_sub_field( 'about_policy_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'about_policy_open', 'open', __( 'Open by default', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1 ) ),
					aberdeen_piano_acf_sub_field(
						'about_policy_paragraphs',
						'paragraphs',
						__( 'Paragraphs', 'aberdeen-piano' ),
						'repeater',
						array(
							'layout'       => 'block',
							'button_label' => __( 'Add paragraph', 'aberdeen-piano' ),
							'sub_fields'   => array(
								aberdeen_piano_acf_sub_field( 'about_policy_paragraph_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
							),
						)
					),
				)
			),

			aberdeen_piano_acf_field( 'about_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Services.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_services() {
	$p    = 'page_services.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'services', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'services', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_services',
		__( 'Services Page', 'aberdeen-piano' ),
		21,
		'page-services.php',
		array( 'services', 'our-services' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'services_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'services_tab_index', __( 'Anchor bar', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_page_index_field( 'services' ),

				aberdeen_piano_acf_field( 'services_tab_student', __( 'Student lessons', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'services_student_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'student.eyebrow' ),
				aberdeen_piano_acf_heading( 'services_student_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'student.heading' ),
				aberdeen_piano_acf_textarea( 'services_student_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'student.intro', 3 ),
				aberdeen_piano_acf_text( 'services_student_subtitle', __( 'Inclusions heading', 'aberdeen-piano' ), $p . 'student.subtitle' ),
				aberdeen_piano_acf_repeater(
					'services_student_includes',
					__( 'What tuition includes', 'aberdeen-piano' ),
					__( 'Add inclusion', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'services_include_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'services_include_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
					)
				),
				aberdeen_piano_acf_text( 'services_tuition_label', __( 'Price card — label', 'aberdeen-piano' ), $p . 'student.card.label', array( 'wrapper' => array( 'width' => '40' ) ) ),
				aberdeen_piano_acf_text( 'services_tuition_currency', __( 'Currency', 'aberdeen-piano' ), $p . 'student.card.currency', array( 'wrapper' => array( 'width' => '15' ) ) ),
				aberdeen_piano_acf_text( 'services_tuition_amount', __( 'Amount', 'aberdeen-piano' ), $p . 'student.card.amount', array( 'wrapper' => array( 'width' => '20' ) ) ),
				aberdeen_piano_acf_text( 'services_tuition_period', __( 'Period', 'aberdeen-piano' ), $p . 'student.card.period', array( 'wrapper' => array( 'width' => '25' ) ) ),
				aberdeen_piano_acf_textarea( 'services_tuition_note', __( 'Price card — note', 'aberdeen-piano' ), $p . 'student.card.note', 2 ),
				aberdeen_piano_acf_repeater(
					'services_tuition_facts',
					__( 'Price card — facts', 'aberdeen-piano' ),
					__( 'Add fact', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'services_tuition_fact_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'services_tuition_fact_value', 'value', __( 'Value', 'aberdeen-piano' ), 'text' ),
					),
					'table'
				),
				aberdeen_piano_acf_text( 'services_tuition_button_label', __( 'Button label', 'aberdeen-piano' ), $p . 'student.card.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'services_tuition_button_url', __( 'Button URL', 'aberdeen-piano' ), $p . 'student.card.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),

				aberdeen_piano_acf_field( 'services_tab_policy', __( 'Cancellation &amp; payment', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'services_cancel_title', __( 'Cancellation — title', 'aberdeen-piano' ), $p . 'cancellation.title' ),
				aberdeen_piano_acf_textarea( 'services_cancel_text', __( 'Cancellation — text', 'aberdeen-piano' ), $p . 'cancellation.text', 3 ),
				aberdeen_piano_acf_text( 'services_cancel_phone_label', __( 'Phone label', 'aberdeen-piano' ), $p . 'cancellation.phone_label', array( 'wrapper' => array( 'width' => '25' ) ) ),
				aberdeen_piano_acf_text( 'services_cancel_phone', __( 'Phone', 'aberdeen-piano' ), $p . 'cancellation.phone', array( 'wrapper' => array( 'width' => '25' ) ) ),
				aberdeen_piano_acf_text( 'services_cancel_email_label', __( 'Email label', 'aberdeen-piano' ), $p . 'cancellation.email_label', array( 'wrapper' => array( 'width' => '25' ) ) ),
				aberdeen_piano_acf_text( 'services_cancel_email', __( 'Email', 'aberdeen-piano' ), $p . 'cancellation.email', array( 'wrapper' => array( 'width' => '25' ) ) ),
				aberdeen_piano_acf_text( 'services_payment_title', __( 'Payment — title', 'aberdeen-piano' ), $p . 'payment.title' ),
				aberdeen_piano_acf_text_rows( 'services_payment_items', __( 'Payment — points', 'aberdeen-piano' ), __( 'Add point', 'aberdeen-piano' ) ),
				aberdeen_piano_acf_textarea( 'services_payment_note', __( 'Payment — note', 'aberdeen-piano' ), $p . 'payment.note', 2 ),

				aberdeen_piano_acf_field( 'services_tab_adult', __( 'Adult lessons', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'services_adult_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'adult.eyebrow' ),
				aberdeen_piano_acf_heading( 'services_adult_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'adult.heading' ),
				aberdeen_piano_acf_textarea( 'services_adult_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'adult.intro', 3 ),
				aberdeen_piano_acf_repeater(
					'services_adult_tiers',
					__( 'Tiers', 'aberdeen-piano' ),
					__( 'Add tier', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'services_tier_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '40' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_currency', 'currency', __( 'Currency', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '15' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_amount', 'amount', __( 'Amount', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_period', 'period', __( 'Period', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_note', 'note', __( 'Note', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'services_tier_divider', 'divider', __( 'List heading', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_featured', 'featured', __( 'Featured (dark)', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1, 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field(
							'services_tier_items',
							'items',
							__( 'List items', 'aberdeen-piano' ),
							'repeater',
							array(
								'layout'       => 'table',
								'button_label' => __( 'Add item', 'aberdeen-piano' ),
								'sub_fields'   => array(
									aberdeen_piano_acf_sub_field( 'services_tier_item_text', 'text', __( 'Text', 'aberdeen-piano' ), 'text' ),
								),
							)
						),
						aberdeen_piano_acf_sub_field( 'services_tier_link_label', 'link_label', __( 'Link label', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_tier_link_url', 'link_url', __( 'Link URL', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
					)
				),

				aberdeen_piano_acf_field( 'services_tab_theory', __( 'Music theory', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'services_theory_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'theory.eyebrow' ),
				aberdeen_piano_acf_heading( 'services_theory_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'theory.heading' ),
				aberdeen_piano_acf_textarea( 'services_theory_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'theory.lead', 3 ),
				aberdeen_piano_acf_textarea( 'services_theory_paragraph', __( 'Second paragraph', 'aberdeen-piano' ), $p . 'theory.paragraph', 3 ),
				aberdeen_piano_acf_repeater(
					'services_theory_steps',
					__( 'Points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'services_theory_step_number', 'number', __( 'Numeral', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_theory_step_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_theory_step_description', 'description', __( 'Description', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'services_tab_performance', __( 'Performance prep', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'services_performance_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'performance.eyebrow' ),
				aberdeen_piano_acf_heading( 'services_performance_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'performance.heading' ),
				aberdeen_piano_acf_textarea( 'services_performance_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'performance.intro', 3 ),
				aberdeen_piano_acf_repeater(
					'services_performance_cards',
					__( 'Cards', 'aberdeen-piano' ),
					__( 'Add card', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'services_performance_card_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_performance_card_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'services_performance_card_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'services_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Student Resources.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_student() {
	$p    = 'page_student.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'student', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'student', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_student',
		__( 'Student Resources Page', 'aberdeen-piano' ),
		22,
		'page-student.php',
		array( 'student', 'student-resources', 'the-studio', 'studio' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'student_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'student_tab_index', __( 'Anchor bar', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_page_index_field( 'student' ),

				aberdeen_piano_acf_field( 'student_tab_lab', __( 'Audio-Visual Lab', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_image_pair( 'student_lab', __( 'Photograph', 'aberdeen-piano' ), $p . 'lab.image_alt' )[0],
				aberdeen_piano_acf_image_pair( 'student_lab', __( 'Photograph', 'aberdeen-piano' ), $p . 'lab.image_alt' )[1],
				aberdeen_piano_acf_text( 'student_lab_tag', __( 'Photo tag', 'aberdeen-piano' ), $p . 'lab.tag' ),
				aberdeen_piano_acf_text( 'student_lab_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'lab.eyebrow' ),
				aberdeen_piano_acf_heading( 'student_lab_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'lab.heading' ),
				aberdeen_piano_acf_textarea( 'student_lab_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'lab.lead', 3 ),
				aberdeen_piano_acf_repeater(
					'student_lab_features',
					__( 'Points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'student_lab_feature_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_lab_feature_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_lab_feature_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'student_tab_access', __( 'Additional access', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'student_access_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'access.eyebrow' ),
				aberdeen_piano_acf_heading( 'student_access_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'access.heading' ),
				aberdeen_piano_acf_textarea( 'student_access_text', __( 'Text', 'aberdeen-piano' ), $p . 'access.text', 3 ),
				aberdeen_piano_acf_text( 'student_access_button_label', __( 'Button label', 'aberdeen-piano' ), $p . 'access.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'student_access_button_url', __( 'Button URL', 'aberdeen-piano' ), $p . 'access.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_repeater(
					'student_access_items',
					__( 'Session types', 'aberdeen-piano' ),
					__( 'Add type', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'student_access_item_letter', 'letter', __( 'Letter', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_access_item_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_access_item_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'student_tab_library', __( 'Music Library', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_image_pair( 'student_library', __( 'Photograph', 'aberdeen-piano' ), $p . 'library.image_alt' )[0],
				aberdeen_piano_acf_image_pair( 'student_library', __( 'Photograph', 'aberdeen-piano' ), $p . 'library.image_alt' )[1],
				aberdeen_piano_acf_text( 'student_library_tag', __( 'Photo tag', 'aberdeen-piano' ), $p . 'library.tag' ),
				aberdeen_piano_acf_text( 'student_library_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'library.eyebrow' ),
				aberdeen_piano_acf_heading( 'student_library_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'library.heading' ),
				aberdeen_piano_acf_textarea( 'student_library_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'library.lead', 3 ),
				aberdeen_piano_acf_repeater(
					'student_library_features',
					__( 'Points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'student_library_feature_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_library_feature_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'student_library_feature_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'student_tab_performance', __( 'Performance', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'student_performance_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'performance.eyebrow' ),
				aberdeen_piano_acf_heading( 'student_performance_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'performance.heading' ),
				aberdeen_piano_acf_textarea( 'student_performance_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'performance.intro', 3 ),
				aberdeen_piano_acf_text( 'student_recital_label', __( 'Recital card — label', 'aberdeen-piano' ), $p . 'performance.recital.label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'student_recital_title', __( 'Recital card — title', 'aberdeen-piano' ), $p . 'performance.recital.title', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_repeater(
					'student_recital_points',
					__( 'Recital card — points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'student_recital_point_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'student_recital_point_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),
				aberdeen_piano_acf_text( 'student_prep_label', __( 'Preparation card — label', 'aberdeen-piano' ), $p . 'performance.prep.label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'student_prep_title', __( 'Preparation card — title', 'aberdeen-piano' ), $p . 'performance.prep.title', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_textarea( 'student_prep_lead', __( 'Preparation card — lead', 'aberdeen-piano' ), $p . 'performance.prep.lead', 2 ),
				aberdeen_piano_acf_text( 'student_prep_divider', __( 'Preparation card — list heading', 'aberdeen-piano' ), $p . 'performance.prep.divider' ),
				aberdeen_piano_acf_text_rows( 'student_prep_items', __( 'Preparation card — expectations', 'aberdeen-piano' ), __( 'Add expectation', 'aberdeen-piano' ) ),
				aberdeen_piano_acf_textarea( 'student_prep_note', __( 'Preparation card — note', 'aberdeen-piano' ), $p . 'performance.prep.note', 2 ),

				aberdeen_piano_acf_field( 'student_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Contact Us.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_contact() {
	$p    = 'page_contact.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'contact_page', $p . 'hero' );

	return aberdeen_piano_acf_page_group(
		'page_contact',
		__( 'Contact Page', 'aberdeen-piano' ),
		23,
		'page-contact-us.php',
		array( 'contact-us', 'contact' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'contact_page_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'contact_page_tab_methods', __( 'Contact methods', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_repeater(
					'contact_page_methods',
					__( 'Methods', 'aberdeen-piano' ),
					__( 'Add method', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'contact_page_method_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'contact_page_method_value', 'value', __( 'Value', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'contact_page_method_hint', 'hint', __( 'Hint', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field(
							'contact_page_method_url',
							'url',
							__( 'Link', 'aberdeen-piano' ),
							'text',
							array( 'instructions' => __( 'Optional. Leave empty for a plain panel.', 'aberdeen-piano' ) )
						),
					)
				),

				aberdeen_piano_acf_field( 'contact_page_tab_details', __( 'Contact information', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'contact_page_details_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'details.eyebrow' ),
				aberdeen_piano_acf_heading( 'contact_page_details_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'details.heading' ),
				aberdeen_piano_acf_textarea( 'contact_page_details_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'details.lead', 3 ),
				aberdeen_piano_acf_repeater(
					'contact_page_details_items',
					__( 'Details', 'aberdeen-piano' ),
					__( 'Add detail', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'contact_page_detail_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field(
							'contact_page_detail_value',
							'value',
							__( 'Value', 'aberdeen-piano' ),
							'textarea',
							array(
								'rows'         => 2,
								'new_lines'    => '',
								'instructions' => __( 'Line breaks are kept, so an address can span two lines.', 'aberdeen-piano' ),
							)
						),
						aberdeen_piano_acf_sub_field( 'contact_page_detail_url', 'url', __( 'Link', 'aberdeen-piano' ), 'text' ),
					)
				),
				aberdeen_piano_acf_textarea( 'contact_page_details_note', __( 'Side note', 'aberdeen-piano' ), $p . 'details.note', 2 ),

				aberdeen_piano_acf_field( 'contact_page_tab_form', __( 'Form', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'contact_page_form_label', __( 'Card label', 'aberdeen-piano' ), $p . 'form.label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'contact_page_form_heading', __( 'Card heading', 'aberdeen-piano' ), $p . 'form.heading', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_repeater(
					'contact_page_form_fields',
					__( 'Form fields', 'aberdeen-piano' ),
					__( 'Add field', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field(
							'contact_page_field_name',
							'name',
							__( 'Name', 'aberdeen-piano' ),
							'select',
							array(
								'choices'      => array(
									'name'      => __( 'Name', 'aberdeen-piano' ),
									'email'     => __( 'Email', 'aberdeen-piano' ),
									'telephone' => __( 'Telephone', 'aberdeen-piano' ),
									'subject'   => __( 'Subject', 'aberdeen-piano' ),
									'message'   => __( 'Message', 'aberdeen-piano' ),
								),
								'allow_null'   => 0,
								'instructions' => __( 'Determines validation and where the value is stored.', 'aberdeen-piano' ),
							)
						),
						aberdeen_piano_acf_sub_field( 'contact_page_field_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field(
							'contact_page_field_type',
							'type',
							__( 'Input type', 'aberdeen-piano' ),
							'select',
							array(
								'choices'    => array(
									'text'     => __( 'Text', 'aberdeen-piano' ),
									'email'    => __( 'E-mail', 'aberdeen-piano' ),
									'tel'      => __( 'Telephone', 'aberdeen-piano' ),
									'textarea' => __( 'Message', 'aberdeen-piano' ),
								),
								'allow_null' => 0,
							)
						),
						aberdeen_piano_acf_sub_field( 'contact_page_field_placeholder', 'placeholder', __( 'Placeholder', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'contact_page_field_required', 'required', __( 'Required', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1 ) ),
						aberdeen_piano_acf_sub_field( 'contact_page_field_half', 'half', __( 'Half width', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1 ) ),
					)
				),
				aberdeen_piano_acf_text( 'contact_page_form_submit_label', __( 'Submit button', 'aberdeen-piano' ), $p . 'form.submit_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'contact_page_form_note', __( 'Footer note', 'aberdeen-piano' ), $p . 'form.note', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_textarea( 'contact_page_form_success_text', __( 'Success message', 'aberdeen-piano' ), $p . 'form.success_text', 2 ),

				aberdeen_piano_acf_field( 'contact_page_tab_map', __( 'Map', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'contact_page_map_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'map.eyebrow' ),
				aberdeen_piano_acf_heading( 'contact_page_map_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'map.heading' ),
				aberdeen_piano_acf_textarea( 'contact_page_map_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'map.intro', 3 ),
				aberdeen_piano_acf_text( 'contact_page_map_address_label', __( 'Address label', 'aberdeen-piano' ), $p . 'map.address_label' ),
				aberdeen_piano_acf_textarea( 'contact_page_map_address', __( 'Address', 'aberdeen-piano' ), $p . 'map.address', 2 ),
				aberdeen_piano_acf_repeater(
					'contact_page_map_facts',
					__( 'Facts', 'aberdeen-piano' ),
					__( 'Add fact', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'contact_page_map_fact_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'contact_page_map_fact_value', 'value', __( 'Value', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'contact_page_map_fact_url', 'url', __( 'Link', 'aberdeen-piano' ), 'text' ),
					),
					'table'
				),
				aberdeen_piano_acf_text(
					'contact_page_map_embed_url',
					__( 'Map embed URL', 'aberdeen-piano' ),
					$p . 'map.embed_url',
					array( 'instructions' => __( 'A Google Maps embed URL. The keyless form is https://www.google.com/maps?q=YOUR+ADDRESS&amp;output=embed', 'aberdeen-piano' ) )
				),
				aberdeen_piano_acf_text( 'contact_page_map_button_label', __( 'Directions button', 'aberdeen-piano' ), $p . 'map.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'contact_page_map_button_url', __( 'Directions URL', 'aberdeen-piano' ), $p . 'map.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_textarea( 'contact_page_map_note', __( 'Note under the button', 'aberdeen-piano' ), $p . 'map.note', 2 ),
			)
		)
	);
}

/**
 * ABRSM Program.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_abrsm() {
	$p    = 'page_abrsm.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'abrsm', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'abrsm', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_abrsm',
		__( 'ABRSM Program Page', 'aberdeen-piano' ),
		24,
		'page-abrsm-program.php',
		array( 'abrsm-program', 'abrsm' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'abrsm_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'abrsm_tab_index', __( 'Anchor bar', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_page_index_field( 'abrsm' ),

				aberdeen_piano_acf_field( 'abrsm_tab_overview', __( 'Overview', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'abrsm_overview_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'overview.eyebrow' ),
				aberdeen_piano_acf_heading( 'abrsm_overview_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'overview.heading' ),
				aberdeen_piano_acf_textarea( 'abrsm_overview_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'overview.lead', 3 ),
				aberdeen_piano_acf_paragraphs( 'abrsm_overview_paragraphs', 'ap_abrsm_overview_paragraphs', __( 'Paragraphs', 'aberdeen-piano' ), __( 'Add paragraph', 'aberdeen-piano' ) ),
				aberdeen_piano_acf_text( 'abrsm_overview_facts_label', __( 'Fact card — label', 'aberdeen-piano' ), $p . 'overview.facts_label' ),
				aberdeen_piano_acf_repeater(
					'abrsm_overview_facts',
					__( 'Fact card — rows', 'aberdeen-piano' ),
					__( 'Add fact', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'abrsm_overview_fact_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'abrsm_overview_fact_value', 'value', __( 'Value', 'aberdeen-piano' ), 'text' ),
					),
					'table'
				),
				aberdeen_piano_acf_textarea( 'abrsm_overview_facts_note', __( 'Fact card — note', 'aberdeen-piano' ), $p . 'overview.facts_note', 2 ),

				aberdeen_piano_acf_field( 'abrsm_tab_ladder', __( 'Grade ladder', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'abrsm_ladder_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'ladder.eyebrow' ),
				aberdeen_piano_acf_heading( 'abrsm_ladder_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'ladder.heading' ),
				aberdeen_piano_acf_repeater(
					'abrsm_ladder_items',
					__( 'Rungs', 'aberdeen-piano' ),
					__( 'Add rung', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'abrsm_ladder_rung', 'rung', __( 'Rung', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '40' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_ladder_crest', 'crest', __( 'Dark (final rung)', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1, 'wrapper' => array( 'width' => '60' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_ladder_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'abrsm_tab_content', __( 'The program', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'abrsm_content_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'content.eyebrow' ),
				aberdeen_piano_acf_heading( 'abrsm_content_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'content.heading' ),
				aberdeen_piano_acf_textarea( 'abrsm_content_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'content.intro', 3 ),
				aberdeen_piano_acf_repeater(
					'abrsm_content_cards',
					__( 'Panels', 'aberdeen-piano' ),
					__( 'Add panel', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'abrsm_content_card_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_content_card_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_content_card_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
						aberdeen_piano_acf_sub_field(
							'abrsm_content_card_items',
							'items',
							__( 'Ticked list', 'aberdeen-piano' ),
							'repeater',
							array(
								'layout'       => 'table',
								'button_label' => __( 'Add item', 'aberdeen-piano' ),
								'sub_fields'   => array(
									aberdeen_piano_acf_sub_field( 'abrsm_content_card_item_text', 'text', __( 'Text', 'aberdeen-piano' ), 'text' ),
								),
							)
						),
					)
				),

				aberdeen_piano_acf_field( 'abrsm_tab_commitment', __( 'Commitment', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'abrsm_commitment_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'commitment.eyebrow' ),
				aberdeen_piano_acf_heading( 'abrsm_commitment_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'commitment.heading' ),
				aberdeen_piano_acf_textarea( 'abrsm_commitment_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'commitment.lead', 3 ),
				aberdeen_piano_acf_textarea( 'abrsm_commitment_paragraph', __( 'Second paragraph', 'aberdeen-piano' ), $p . 'commitment.paragraph', 2 ),
				aberdeen_piano_acf_repeater(
					'abrsm_commitment_steps',
					__( 'Points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'abrsm_commitment_step_number', 'number', __( 'Numeral', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_commitment_step_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'abrsm_commitment_step_description', 'description', __( 'Description', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'abrsm_tab_resource', __( 'External resource', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'abrsm_resource_label', __( 'Label', 'aberdeen-piano' ), $p . 'resource.label' ),
				aberdeen_piano_acf_heading( 'abrsm_resource_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'resource.heading' ),
				aberdeen_piano_acf_textarea( 'abrsm_resource_text', __( 'Text', 'aberdeen-piano' ), $p . 'resource.text', 2 ),
				aberdeen_piano_acf_text( 'abrsm_resource_button_label', __( 'Button label', 'aberdeen-piano' ), $p . 'resource.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'abrsm_resource_button_url', __( 'Button URL', 'aberdeen-piano' ), $p . 'resource.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),

				aberdeen_piano_acf_field( 'abrsm_tab_cta', __( 'Consultation panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Activities &amp; Events.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_activities() {
	$p    = 'page_activities.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'activities', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'activities', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_activities',
		__( 'Activities &amp; Events Page', 'aberdeen-piano' ),
		25,
		'page-activities-events.php',
		array( 'activities-events', 'activities' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'activities_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'activities_tab_intro', __( 'Introduction', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'activities_intro_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'intro.eyebrow' ),
				aberdeen_piano_acf_heading( 'activities_intro_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'intro.heading' ),
				aberdeen_piano_acf_textarea( 'activities_intro_lead', __( 'Lead paragraph', 'aberdeen-piano' ), $p . 'intro.lead', 3 ),
				aberdeen_piano_acf_textarea( 'activities_intro_paragraph', __( 'Second paragraph', 'aberdeen-piano' ), $p . 'intro.paragraph', 3 ),

				aberdeen_piano_acf_field( 'activities_tab_list', __( 'Participation opportunities', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'activities_list_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'activities.eyebrow' ),
				aberdeen_piano_acf_heading( 'activities_list_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'activities.heading' ),
				aberdeen_piano_acf_textarea( 'activities_list_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'activities.intro', 2 ),
				aberdeen_piano_acf_repeater(
					'activities_list_cards',
					__( 'Activities', 'aberdeen-piano' ),
					__( 'Add activity', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'activities_card_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'activities_card_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'activities_card_tag', 'tag', __( 'Tag', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '30' ) ) ),
						aberdeen_piano_acf_sub_field( 'activities_card_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'activities_tab_recognition', __( 'Recognition', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'activities_recognition_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'recognition.eyebrow' ),
				aberdeen_piano_acf_heading( 'activities_recognition_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'recognition.heading' ),
				aberdeen_piano_acf_textarea( 'activities_recognition_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'recognition.intro', 2 ),
				aberdeen_piano_acf_repeater(
					'activities_recognition_cards',
					__( 'Recognition cards', 'aberdeen-piano' ),
					__( 'Add card', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field(
							'activities_recognition_mark',
							'mark',
							__( 'Symbol', 'aberdeen-piano' ),
							'text',
							array(
								'wrapper'      => array( 'width' => '20' ),
								'instructions' => __( 'A single character, e.g. ★ ♪ ✦', 'aberdeen-piano' ),
							)
						),
						aberdeen_piano_acf_sub_field( 'activities_recognition_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'activities_recognition_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'activities_tab_band', __( 'Calendar band', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'activities_band_label', __( 'Label', 'aberdeen-piano' ), $p . 'band.label' ),
				aberdeen_piano_acf_heading( 'activities_band_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'band.heading' ),
				aberdeen_piano_acf_textarea( 'activities_band_text', __( 'Text', 'aberdeen-piano' ), $p . 'band.text', 2 ),
				aberdeen_piano_acf_text( 'activities_band_button_label', __( 'Button label', 'aberdeen-piano' ), $p . 'band.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'activities_band_button_url', __( 'Button URL', 'aberdeen-piano' ), $p . 'band.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),

				aberdeen_piano_acf_field( 'activities_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Calendar of Events.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_calendar() {
	$p    = 'page_calendar.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'calendar_page', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'calendar_page', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_calendar',
		__( 'Calendar Page', 'aberdeen-piano' ),
		26,
		'page-calendar.php',
		array( 'calendar', 'calendar-of-events' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'calendar_page_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'calendar_page_tab_year', __( 'The year', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'calendar_page_year_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'year.eyebrow' ),
				aberdeen_piano_acf_heading( 'calendar_page_year_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'year.heading' ),
				aberdeen_piano_acf_textarea( 'calendar_page_year_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'year.intro', 3 ),
				aberdeen_piano_acf_repeater(
					'calendar_page_filters',
					__( 'Filter buttons', 'aberdeen-piano' ),
					__( 'Add filter', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field(
							'calendar_page_filter_key',
							'key',
							__( 'Key', 'aberdeen-piano' ),
							'text',
							array( 'instructions' => __( 'Must match an event kind. Keep one filter with the key "all".', 'aberdeen-piano' ) )
						),
						aberdeen_piano_acf_sub_field( 'calendar_page_filter_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
					),
					'table'
				),
				aberdeen_piano_acf_repeater(
					'calendar_page_months',
					__( 'Months', 'aberdeen-piano' ),
					__( 'Add month', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'calendar_page_month_label', 'label', __( 'Month', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'calendar_page_month_year', 'year', __( 'Year', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field(
							'calendar_page_month_events',
							'events',
							__( 'Events', 'aberdeen-piano' ),
							'repeater',
							array(
								'layout'       => 'block',
								'button_label' => __( 'Add event', 'aberdeen-piano' ),
								'sub_fields'   => array(
									aberdeen_piano_acf_sub_field(
										'calendar_page_event_kind',
										'kind',
										__( 'Kind', 'aberdeen-piano' ),
										'select',
										array(
											'choices'    => array(
												'lesson'      => __( 'Lesson', 'aberdeen-piano' ),
												'makeup'      => __( 'Make-up', 'aberdeen-piano' ),
												'performance' => __( 'Performance', 'aberdeen-piano' ),
												'holiday'     => __( 'Holiday / break', 'aberdeen-piano' ),
											),
											'allow_null' => 0,
											'wrapper'    => array( 'width' => '25' ),
										)
									),
									aberdeen_piano_acf_sub_field( 'calendar_page_event_date', 'date', __( 'Date', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
									aberdeen_piano_acf_sub_field( 'calendar_page_event_day', 'day', __( 'Day', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
									aberdeen_piano_acf_sub_field( 'calendar_page_event_time', 'time', __( 'Time', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
									aberdeen_piano_acf_sub_field( 'calendar_page_event_name', 'name', __( 'Event', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '65' ) ) ),
									aberdeen_piano_acf_sub_field( 'calendar_page_event_tag', 'tag', __( 'Tag', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '35' ) ) ),
								),
							)
						),
					)
				),
				aberdeen_piano_acf_textarea( 'calendar_page_year_footnote', __( 'Footnote', 'aberdeen-piano' ), $p . 'year.footnote', 2 ),

				aberdeen_piano_acf_field( 'calendar_page_tab_summer', __( 'Summer program', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'calendar_page_summer_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'summer.eyebrow' ),
				aberdeen_piano_acf_heading( 'calendar_page_summer_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'summer.heading' ),
				aberdeen_piano_acf_textarea( 'calendar_page_summer_text', __( 'Text', 'aberdeen-piano' ), $p . 'summer.text', 3 ),
				aberdeen_piano_acf_text( 'calendar_page_summer_button_label', __( 'Button label', 'aberdeen-piano' ), $p . 'summer.button_label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'calendar_page_summer_button_url', __( 'Button URL', 'aberdeen-piano' ), $p . 'summer.button_url', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_repeater(
					'calendar_page_summer_items',
					__( 'Summer points', 'aberdeen-piano' ),
					__( 'Add point', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'calendar_page_summer_item_letter', 'letter', __( 'Letter', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
						aberdeen_piano_acf_sub_field( 'calendar_page_summer_item_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
						aberdeen_piano_acf_sub_field( 'calendar_page_summer_item_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'calendar_page_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Testimonials.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_testimonials() {
	$p    = 'page_testimonials.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'testimonials', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'testimonials', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_testimonials',
		__( 'Testimonials Page', 'aberdeen-piano' ),
		27,
		'page-testimonials.php',
		array( 'testimonials', 'testimonial' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'testimonials_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'testimonials_tab_featured', __( 'Featured quote', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_textarea( 'testimonials_featured_quote', __( 'Quote', 'aberdeen-piano' ), $p . 'featured.quote', 4 ),
				aberdeen_piano_acf_text( 'testimonials_featured_name', __( 'Attribution', 'aberdeen-piano' ), $p . 'featured.name', array( 'wrapper' => array( 'width' => '50' ) ) ),
				aberdeen_piano_acf_text( 'testimonials_featured_meta', __( 'Detail', 'aberdeen-piano' ), $p . 'featured.meta', array( 'wrapper' => array( 'width' => '50' ) ) ),

				aberdeen_piano_acf_field( 'testimonials_tab_wall', __( 'Testimonials', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'testimonials_wall_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'wall.eyebrow' ),
				aberdeen_piano_acf_heading( 'testimonials_wall_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'wall.heading' ),
				aberdeen_piano_acf_textarea( 'testimonials_wall_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'wall.intro', 2 ),
				aberdeen_piano_acf_repeater(
					'testimonials_wall_items',
					__( 'Testimonials', 'aberdeen-piano' ),
					__( 'Add testimonial', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'testimonials_item_kind', 'kind', __( 'Type', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '30' ), 'instructions' => __( 'Parent, Student, Adult learner…', 'aberdeen-piano' ) ) ),
						aberdeen_piano_acf_sub_field( 'testimonials_item_mark', 'mark', __( 'Monogram', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ), 'instructions' => __( 'One letter.', 'aberdeen-piano' ) ) ),
						aberdeen_piano_acf_sub_field( 'testimonials_item_dark', 'dark', __( 'Dark card', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1, 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'testimonials_item_quote', 'quote', __( 'Quote', 'aberdeen-piano' ), 'textarea', array( 'rows' => 4, 'new_lines' => '' ) ),
						aberdeen_piano_acf_sub_field( 'testimonials_item_name', 'name', __( 'Attribution', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
						aberdeen_piano_acf_sub_field( 'testimonials_item_meta', 'meta', __( 'Detail', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
					)
				),

				aberdeen_piano_acf_field( 'testimonials_tab_achievements', __( 'Achievements', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'testimonials_achievements_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'achievements.eyebrow' ),
				aberdeen_piano_acf_heading( 'testimonials_achievements_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'achievements.heading' ),
				aberdeen_piano_acf_textarea( 'testimonials_achievements_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'achievements.intro', 2 ),
				aberdeen_piano_acf_repeater(
					'testimonials_achievements_items',
					__( 'Achievements', 'aberdeen-piano' ),
					__( 'Add achievement', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field( 'testimonials_achievement_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text' ),
						aberdeen_piano_acf_sub_field( 'testimonials_achievement_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
					)
				),

				aberdeen_piano_acf_field( 'testimonials_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}

/**
 * Gallery.
 *
 * @return array
 */
function aberdeen_piano_acf_group_page_gallery() {
	$p    = 'page_gallery.';
	$hero = aberdeen_piano_acf_page_hero_fields( 'gallery', $p . 'hero' );
	$cta  = aberdeen_piano_acf_page_cta_fields( 'gallery', $p . 'cta' );

	return aberdeen_piano_acf_page_group(
		'page_gallery',
		__( 'Gallery Page', 'aberdeen-piano' ),
		28,
		'page-gallery.php',
		array( 'gallery', 'photo-gallery' ),
		array_merge(
			array( aberdeen_piano_acf_field( 'gallery_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ) ),
			$hero,
			array(
				aberdeen_piano_acf_field( 'gallery_tab_grid', __( 'Photographs', 'aberdeen-piano' ), 'tab' ),
				aberdeen_piano_acf_text( 'gallery_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), $p . 'gallery.eyebrow' ),
				aberdeen_piano_acf_heading( 'gallery_heading', __( 'Heading', 'aberdeen-piano' ), $p . 'gallery.heading' ),
				aberdeen_piano_acf_textarea( 'gallery_intro', __( 'Intro', 'aberdeen-piano' ), $p . 'gallery.intro', 2 ),
				aberdeen_piano_acf_text( 'gallery_all_label', __( '"All" filter label', 'aberdeen-piano' ), $p . 'gallery.all_label' ),
				aberdeen_piano_acf_repeater(
					'gallery_categories',
					__( 'Categories', 'aberdeen-piano' ),
					__( 'Add category', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field(
							'gallery_category_key',
							'key',
							__( 'Key', 'aberdeen-piano' ),
							'text',
							array( 'instructions' => __( 'Lowercase, no spaces. Each photograph refers to this key.', 'aberdeen-piano' ) )
						),
						aberdeen_piano_acf_sub_field( 'gallery_category_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
					),
					'table'
				),
				aberdeen_piano_acf_repeater(
					'gallery_photos',
					__( 'Photographs', 'aberdeen-piano' ),
					__( 'Add photograph', 'aberdeen-piano' ),
					array(
						aberdeen_piano_acf_sub_field(
							'gallery_photo_image',
							'image',
							__( 'Photograph', 'aberdeen-piano' ),
							'image',
							array(
								'return_format' => 'array',
								'preview_size'  => 'medium',
								'wrapper'       => array( 'width' => '30' ),
							)
						),
						aberdeen_piano_acf_sub_field( 'gallery_photo_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '35' ) ) ),
						aberdeen_piano_acf_sub_field( 'gallery_photo_meta', 'meta', __( 'Caption detail', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '35' ) ) ),
						aberdeen_piano_acf_sub_field(
							'gallery_photo_kind',
							'kind',
							__( 'Category key', 'aberdeen-piano' ),
							'text',
							array(
								'wrapper'      => array( 'width' => '30' ),
								'instructions' => __( 'Must match one of the category keys above.', 'aberdeen-piano' ),
							)
						),
						aberdeen_piano_acf_sub_field(
							'gallery_photo_alt',
							'alt',
							__( 'Alt text', 'aberdeen-piano' ),
							'text',
							array(
								'wrapper'      => array( 'width' => '70' ),
								'instructions' => __( 'Falls back to the alt text on the attachment.', 'aberdeen-piano' ),
							)
						),
					)
				),
				aberdeen_piano_acf_textarea( 'gallery_note', __( 'Note under the grid', 'aberdeen-piano' ), $p . 'gallery.note', 2 ),

				aberdeen_piano_acf_field( 'gallery_tab_cta', __( 'Closing panel', 'aberdeen-piano' ), 'tab' ),
			),
			$cta
		)
	);
}
