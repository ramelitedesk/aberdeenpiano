<?php
/**
 * ACF field registration.
 *
 * Fields are registered in code (never in the database) so they travel with the
 * theme and stay under version control. Architecture:
 *
 *   - One field group per front page section, ordered by menu_order.
 *   - A "Site Settings" options page for content shared by every template.
 *   - Field keys are namespaced field_ap_<section>_<name>; field names are
 *     ap_<section>_<name>, matching the aberdeen_piano_field() calls in the
 *     templates.
 *   - Every field is optional. When one is left empty the template falls back to
 *     inc/defaults.php, so the page always matches the approved design.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a field definition.
 *
 * @param string $key   Key/name suffix, e.g. "hero_eyebrow".
 * @param string $label Admin label.
 * @param string $type  ACF field type.
 * @param array  $args  Extra field arguments.
 * @return array
 */
function aberdeen_piano_acf_field( $key, $label, $type = 'text', $args = array() ) {
	return array_merge(
		array(
			'key'   => 'field_ap_' . $key,
			'label' => $label,
			'name'  => 'ap_' . $key,
			'type'  => $type,
		),
		$args
	);
}

/**
 * Build a repeater sub field. Sub field names are plain row keys.
 *
 * @param string $key   Globally unique key suffix.
 * @param string $name  Row key used in the templates.
 * @param string $label Admin label.
 * @param string $type  ACF field type.
 * @param array  $args  Extra field arguments.
 * @return array
 */
function aberdeen_piano_acf_sub_field( $key, $name, $label, $type = 'text', $args = array() ) {
	return array_merge(
		array(
			'key'   => 'field_ap_' . $key,
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		),
		$args
	);
}

/**
 * A repeater of single-line paragraphs, used for body copy throughout.
 *
 * @param string $key          Key suffix for the repeater.
 * @param string $name         Field name.
 * @param string $label        Admin label.
 * @param string $button_label Add-row button label.
 * @return array
 */
function aberdeen_piano_acf_paragraphs( $key, $name, $label, $button_label ) {
	return array(
		'key'          => 'field_ap_' . $key,
		'label'        => $label,
		'name'         => $name,
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => $button_label,
		'sub_fields'   => array(
			aberdeen_piano_acf_sub_field( $key . '_text', 'text', __( 'Text', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
		),
	);
}

/**
 * Location rules that put a group on the front page only.
 *
 * @return array
 */
function aberdeen_piano_acf_front_page_location() {
	return array(
		array(
			array(
				'param'    => 'page_type',
				'operator' => '==',
				'value'    => 'front_page',
			),
		),
	);
}

/**
 * Shared group defaults.
 *
 * @param string $key        Group key suffix.
 * @param string $title      Group title.
 * @param int    $order      Metabox order.
 * @param array  $fields     Fields.
 * @param array  $args       Extra group arguments.
 * @return array
 */
function aberdeen_piano_acf_group( $key, $title, $order, $fields, $args = array() ) {
	return array_merge(
		array(
			'key'                   => 'group_ap_' . $key,
			'title'                 => $title,
			'fields'                => $fields,
			'location'              => aberdeen_piano_acf_front_page_location(),
			'menu_order'            => $order,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'show_in_rest'          => false,
			'description'           => '',
		),
		$args
	);
}

/**
 * Register the options page and every field group.
 *
 * @return void
 */
function aberdeen_piano_acf_init() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'Site Settings', 'aberdeen-piano' ),
				'menu_title' => __( 'Site Settings', 'aberdeen-piano' ),
				'menu_slug'  => 'aberdeen-piano-settings',
				'capability' => 'edit_theme_options',
				'position'   => '59.9',
				'icon_url'   => 'dashicons-admin-customizer',
				'redirect'   => false,
				'autoload'   => true,
			)
		);
	}

	foreach ( aberdeen_piano_acf_groups() as $group ) {
		acf_add_local_field_group( $group );
	}
}
add_action( 'acf/init', 'aberdeen_piano_acf_init' );

/**
 * Every field group the theme registers.
 *
 * @return array
 */
function aberdeen_piano_acf_groups() {
	$groups = array(
		aberdeen_piano_acf_group_settings(),
		aberdeen_piano_acf_group_hero(),
		aberdeen_piano_acf_group_marquee(),
		aberdeen_piano_acf_group_about(),
		aberdeen_piano_acf_group_quote(),
		aberdeen_piano_acf_group_programs(),
		aberdeen_piano_acf_group_studio(),
		aberdeen_piano_acf_group_policies(),
		aberdeen_piano_acf_group_pricing(),
		aberdeen_piano_acf_group_calendar(),
		aberdeen_piano_acf_group_contact(),
	);

	return apply_filters( 'aberdeen_piano_acf_groups', $groups );
}

/**
 * Site Settings — header, footer, loader and contact details.
 *
 * @return array
 */
function aberdeen_piano_acf_group_settings() {
	return aberdeen_piano_acf_group(
		'settings',
		__( 'Site Settings', 'aberdeen-piano' ),
		0,
		array(
			aberdeen_piano_acf_field( 'settings_tab_brand', __( 'Brand', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ),
			aberdeen_piano_acf_field(
				'brand_name',
				__( 'Brand name', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_brand_name',
					'instructions'  => __( 'Shown beside the logo and in the footer credit.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'global.brand_name' ),
				)
			),
			aberdeen_piano_acf_field(
				'logo',
				__( 'Logo', 'aberdeen-piano' ),
				'image',
				array(
					'name'          => 'ap_logo',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'instructions'  => __( 'Overrides the bundled mark. A Customizer logo takes precedence over this.', 'aberdeen-piano' ),
				)
			),

			aberdeen_piano_acf_field( 'settings_tab_topline', __( 'Top line', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'topline_text',
				__( 'Top line text', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_topline_text',
					'default_value' => aberdeen_piano_default( 'global.topline_text' ),
				)
			),
			aberdeen_piano_acf_field(
				'topline_label',
				__( 'Top line link label', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_topline_label',
					'instructions'  => __( 'Links to the studio e-mail address below.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'global.topline_label' ),
				)
			),

			aberdeen_piano_acf_field( 'settings_tab_contact', __( 'Contact', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'email',
				__( 'Studio e-mail', 'aberdeen-piano' ),
				'email',
				array(
					'name'          => 'ap_email',
					'default_value' => aberdeen_piano_default( 'global.email' ),
				)
			),
			aberdeen_piano_acf_field(
				'address',
				__( 'Studio address', 'aberdeen-piano' ),
				'textarea',
				array(
					'name'          => 'ap_address',
					'rows'          => 3,
					'new_lines'     => '',
					'instructions'  => __( 'One line per row.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'global.address' ),
				)
			),

			aberdeen_piano_acf_field( 'settings_tab_author', __( 'Author', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'author_photo',
				__( 'Author photo', 'aberdeen-piano' ),
				'image',
				array(
					'name'          => 'ap_author_photo',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'instructions'  => __( 'Shown in the article byline and author box. Falls back to the Gravatar for the WordPress user.', 'aberdeen-piano' ),
				)
			),
			aberdeen_piano_acf_field(
				'author_bio',
				__( 'Author biography', 'aberdeen-piano' ),
				'textarea',
				array(
					'name'          => 'ap_author_bio',
					'rows'          => 3,
					'new_lines'     => '',
					'instructions'  => __( 'Used when the WordPress user has no biography set.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'article.author_bio' ),
				)
			),

			aberdeen_piano_acf_field( 'settings_tab_loader', __( 'Loader & footer', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'loader_mark',
				__( 'Loader marquee text', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_loader_mark',
					'default_value' => aberdeen_piano_default( 'global.loader_mark' ),
				)
			),
			aberdeen_piano_acf_field(
				'loader_message',
				__( 'Loader message', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_loader_message',
					'default_value' => aberdeen_piano_default( 'global.loader_message' ),
				)
			),
			aberdeen_piano_acf_field(
				'footer_credit',
				__( 'Footer credit', 'aberdeen-piano' ),
				'text',
				array(
					'name'          => 'ap_footer_credit',
					'instructions'  => __( 'Use %1$s for the year and %2$s for the site name.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'global.footer_credit' ),
				)
			),
		),
		array(
			'location'   => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'aberdeen-piano-settings',
					),
				),
			),
			'menu_order' => 0,
		)
	);
}

/**
 * Hero section.
 *
 * @return array
 */
function aberdeen_piano_acf_group_hero() {
	return aberdeen_piano_acf_group(
		'hero',
		__( 'Home — Hero', 'aberdeen-piano' ),
		1,
		array(
			aberdeen_piano_acf_field( 'hero_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'hero.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'hero_title_white',
				__( 'Headline — white key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'default_value' => aberdeen_piano_default( 'hero.title_white' ),
				)
			),
			aberdeen_piano_acf_field(
				'hero_title_black',
				__( 'Headline — black key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'default_value' => aberdeen_piano_default( 'hero.title_black' ),
				)
			),
			aberdeen_piano_acf_field(
				'hero_title_em',
				__( 'Headline — emphasis line', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '34' ),
					'default_value' => aberdeen_piano_default( 'hero.title_em' ),
				)
			),
			aberdeen_piano_acf_field(
				'hero_description',
				__( 'Introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 4,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'hero.description' ),
				)
			),
			array(
				'key'          => 'field_ap_hero_buttons',
				'label'        => __( 'Buttons', 'aberdeen-piano' ),
				'name'         => 'ap_hero_buttons',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add button', 'aberdeen-piano' ),
				'instructions' => __( 'Optional. The approved design ships with no hero buttons.', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'hero_button_link', 'link', __( 'Link', 'aberdeen-piano' ), 'link', array( 'return_format' => 'array' ) ),
					aberdeen_piano_acf_sub_field(
						'hero_button_style',
						'style',
						__( 'Style', 'aberdeen-piano' ),
						'select',
						array(
							'choices'       => array(
								''      => __( 'Solid', 'aberdeen-piano' ),
								'light' => __( 'Light', 'aberdeen-piano' ),
							),
							'allow_null'    => 0,
							'return_format' => 'value',
						)
					),
				),
			),
		)
	);
}

/**
 * Scrolling marquee.
 *
 * @return array
 */
function aberdeen_piano_acf_group_marquee() {
	return aberdeen_piano_acf_group(
		'marquee',
		__( 'Home — Marquee', 'aberdeen-piano' ),
		2,
		array(
			array(
				'key'          => 'field_ap_marquee_items',
				'label'        => __( 'Marquee items', 'aberdeen-piano' ),
				'name'         => 'ap_marquee_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add item', 'aberdeen-piano' ),
				'instructions' => __( 'Separated automatically by a musical note and looped twice.', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'marquee_item_text', 'text', __( 'Text', 'aberdeen-piano' ) ),
				),
			),
		)
	);
}

/**
 * About / meet your teacher.
 *
 * @return array
 */
function aberdeen_piano_acf_group_about() {
	return aberdeen_piano_acf_group(
		'about',
		__( 'Home — About', 'aberdeen-piano' ),
		3,
		array(
			aberdeen_piano_acf_field(
				'about_image',
				__( 'Portrait', 'aberdeen-piano' ),
				'image',
				array(
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'wrapper'       => array( 'width' => '50' ),
				)
			),
			aberdeen_piano_acf_field(
				'about_image_alt',
				__( 'Portrait alt text', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( 'Only used when the image has no alt text of its own.', 'aberdeen-piano' ),
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'about.image_alt' ),
				)
			),
			aberdeen_piano_acf_field( 'about_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'about.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'about_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'about.heading' ),
				)
			),
			aberdeen_piano_acf_paragraphs( 'about_paragraphs', 'ap_about_paragraphs', __( 'Paragraphs', 'aberdeen-piano' ), __( 'Add paragraph', 'aberdeen-piano' ) ),
			aberdeen_piano_acf_field(
				'about_button',
				__( 'Button', 'aberdeen-piano' ),
				'link',
				array( 'return_format' => 'array' )
			),
			aberdeen_piano_acf_field(
				'about_signature',
				__( 'Signature', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'about.signature' ),
				)
			),
			aberdeen_piano_acf_field(
				'about_credentials',
				__( 'Credentials', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'about.credentials' ),
				)
			),
		)
	);
}

/**
 * Pull quote.
 *
 * @return array
 */
function aberdeen_piano_acf_group_quote() {
	return aberdeen_piano_acf_group(
		'quote',
		__( 'Home — Quote', 'aberdeen-piano' ),
		4,
		array(
			aberdeen_piano_acf_field(
				'quote_text',
				__( 'Quote', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'quote.text' ),
				)
			),
			aberdeen_piano_acf_field( 'quote_cite', __( 'Attribution', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'quote.cite' ) ) ),
		)
	);
}

/**
 * Programme cards.
 *
 * @return array
 */
function aberdeen_piano_acf_group_programs() {
	return aberdeen_piano_acf_group(
		'programs',
		__( 'Home — Programs', 'aberdeen-piano' ),
		5,
		array(
			aberdeen_piano_acf_field(
				'programs_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'programs.heading' ),
				)
			),
			aberdeen_piano_acf_field( 'programs_lead', __( 'Lead line', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'programs.lead' ) ) ),
			aberdeen_piano_acf_field(
				'programs_intro',
				__( 'Introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'programs.intro' ),
				)
			),
			array(
				'key'          => 'field_ap_programs_cards',
				'label'        => __( 'Cards', 'aberdeen-piano' ),
				'name'         => 'ap_programs_cards',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => __( 'Add card', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'programs_card_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
					aberdeen_piano_acf_sub_field( 'programs_card_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
					aberdeen_piano_acf_paragraphs( 'programs_card_paragraphs', 'paragraphs', __( 'Paragraphs', 'aberdeen-piano' ), __( 'Add paragraph', 'aberdeen-piano' ) ),
				),
			),
		)
	);
}

/**
 * Inside the studio / steps.
 *
 * @return array
 */
function aberdeen_piano_acf_group_studio() {
	return aberdeen_piano_acf_group(
		'studio',
		__( 'Home — The Studio', 'aberdeen-piano' ),
		6,
		array(
			aberdeen_piano_acf_field( 'studio_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'studio.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'studio_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'studio.heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'studio_lead',
				__( 'Lead paragraph', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'studio.lead' ),
				)
			),
			array(
				'key'          => 'field_ap_studio_steps',
				'label'        => __( 'Steps', 'aberdeen-piano' ),
				'name'         => 'ap_studio_steps',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => __( 'Add step', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'studio_step_number', 'number', __( 'Number', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
					aberdeen_piano_acf_sub_field( 'studio_step_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '80' ) ) ),
					aberdeen_piano_acf_sub_field( 'studio_step_description', 'description', __( 'Description', 'aberdeen-piano' ), 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				),
			),
		)
	);
}

/**
 * Policies accordion.
 *
 * @return array
 */
function aberdeen_piano_acf_group_policies() {
	return aberdeen_piano_acf_group(
		'policies',
		__( 'Home — Policies', 'aberdeen-piano' ),
		7,
		array(
			aberdeen_piano_acf_field( 'policies_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'policies.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'policies_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'policies.heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'policies_intro',
				__( 'Introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 2,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'policies.intro' ),
				)
			),
			array(
				'key'          => 'field_ap_policies_items',
				'label'        => __( 'Policies', 'aberdeen-piano' ),
				'name'         => 'ap_policies_items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => __( 'Add policy', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'policies_item_title', 'title', __( 'Title', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '70' ) ) ),
					aberdeen_piano_acf_sub_field(
						'policies_item_open',
						'open',
						__( 'Open by default', 'aberdeen-piano' ),
						'true_false',
						array(
							'ui'      => 1,
							'wrapper' => array( 'width' => '30' ),
						)
					),
					aberdeen_piano_acf_paragraphs( 'policies_item_paragraphs', 'paragraphs', __( 'Paragraphs', 'aberdeen-piano' ), __( 'Add paragraph', 'aberdeen-piano' ) ),
				),
			),
		)
	);
}

/**
 * Tuition cards.
 *
 * @return array
 */
function aberdeen_piano_acf_group_pricing() {
	return aberdeen_piano_acf_group(
		'pricing',
		__( 'Home — Tuition', 'aberdeen-piano' ),
		8,
		array(
			aberdeen_piano_acf_field( 'pricing_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'pricing.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'pricing_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'pricing.heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'pricing_intro',
				__( 'Introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 4,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'pricing.intro' ),
				)
			),
			array(
				'key'          => 'field_ap_pricing_cards',
				'label'        => __( 'Tuition cards', 'aberdeen-piano' ),
				'name'         => 'ap_pricing_cards',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => __( 'Add tuition card', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'pricing_card_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field(
						'pricing_card_currency',
						'currency',
						__( 'Currency', 'aberdeen-piano' ),
						'text',
						array(
							'default_value' => '$',
							'wrapper'       => array( 'width' => '15' ),
						)
					),
					aberdeen_piano_acf_sub_field( 'pricing_card_amount', 'amount', __( 'Amount', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '20' ) ) ),
					aberdeen_piano_acf_sub_field( 'pricing_card_period', 'period', __( 'Period', 'aberdeen-piano' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
					aberdeen_piano_acf_sub_field(
						'pricing_card_starred',
						'starred',
						__( 'Show footnote star', 'aberdeen-piano' ),
						'true_false',
						array(
							'ui'      => 1,
							'wrapper' => array( 'width' => '20' ),
						)
					),
					aberdeen_piano_acf_sub_field(
						'pricing_card_featured',
						'featured',
						__( 'Featured card', 'aberdeen-piano' ),
						'true_false',
						array(
							'ui'      => 1,
							'wrapper' => array( 'width' => '20' ),
						)
					),
					aberdeen_piano_acf_paragraphs( 'pricing_card_features', 'features', __( 'Features', 'aberdeen-piano' ), __( 'Add feature', 'aberdeen-piano' ) ),
					aberdeen_piano_acf_sub_field( 'pricing_card_footnote', 'footnote', __( 'Footnote', 'aberdeen-piano' ), 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
					aberdeen_piano_acf_sub_field( 'pricing_card_link', 'link', __( 'Link', 'aberdeen-piano' ), 'link', array( 'return_format' => 'array' ) ),
					aberdeen_piano_acf_sub_field(
						'pricing_card_css_class',
						'css_class',
						__( 'Extra CSS class', 'aberdeen-piano' ),
						'text',
						array( 'instructions' => __( 'Optional modifier, e.g. "one".', 'aberdeen-piano' ) )
					),
				),
			),
			aberdeen_piano_acf_field(
				'pricing_note',
				__( 'Payment note', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 2,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'pricing.note' ),
				)
			),
		)
	);
}

/**
 * Studio calendar.
 *
 * @return array
 */
function aberdeen_piano_acf_group_calendar() {
	$kinds = array();
	foreach ( aberdeen_piano_default( 'calendar.filters', array() ) as $filter ) {
		if ( 'all' !== $filter['key'] ) {
			$kinds[ $filter['key'] ] = $filter['label'];
		}
	}

	return aberdeen_piano_acf_group(
		'calendar',
		__( 'Home — Calendar', 'aberdeen-piano' ),
		9,
		array(
			aberdeen_piano_acf_field( 'calendar_eyebrow', __( 'Eyebrow', 'aberdeen-piano' ), 'text', array( 'default_value' => aberdeen_piano_default( 'calendar.eyebrow' ) ) ),
			aberdeen_piano_acf_field(
				'calendar_title_white',
				__( 'Heading — white key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'calendar.title_white' ),
				)
			),
			aberdeen_piano_acf_field(
				'calendar_title_black',
				__( 'Heading — black key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'calendar.title_black' ),
				)
			),
			array(
				'key'          => 'field_ap_calendar_filters',
				'label'        => __( 'Filter buttons', 'aberdeen-piano' ),
				'name'         => 'ap_calendar_filters',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add filter', 'aberdeen-piano' ),
				'instructions' => __( 'The first row is the "show everything" button. Each key must match the event kinds below.', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'calendar_filter_key', 'key', __( 'Key', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'calendar_filter_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
				),
			),
			array(
				'key'          => 'field_ap_calendar_events',
				'label'        => __( 'Calendar dates', 'aberdeen-piano' ),
				'name'         => 'ap_calendar_events',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add date', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field(
						'calendar_event_kind',
						'kind',
						__( 'Kind', 'aberdeen-piano' ),
						'select',
						array(
							'choices'       => $kinds,
							'allow_null'    => 0,
							'return_format' => 'value',
						)
					),
					aberdeen_piano_acf_sub_field( 'calendar_event_date', 'date', __( 'Date', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'calendar_event_day', 'day', __( 'Day', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'calendar_event_name', 'name', __( 'Name', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'calendar_event_tag', 'tag', __( 'Tag', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field( 'calendar_event_time', 'time', __( 'Time', 'aberdeen-piano' ), 'text' ),
				),
			),
			aberdeen_piano_acf_field(
				'calendar_note',
				__( 'Calendar note', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'calendar.note' ),
				)
			),
		)
	);
}

/**
 * Contact section and enquiry form.
 *
 * @return array
 */
function aberdeen_piano_acf_group_contact() {
	return aberdeen_piano_acf_group(
		'contact',
		__( 'Home — Contact', 'aberdeen-piano' ),
		10,
		array(
			aberdeen_piano_acf_field(
				'contact_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'contact.heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'contact_description',
				__( 'Description', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 4,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'contact.description' ),
				)
			),
			array(
				'key'          => 'field_ap_contact_form_fields',
				'label'        => __( 'Form fields', 'aberdeen-piano' ),
				'name'         => 'ap_contact_form_fields',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add field', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field(
						'contact_field_name',
						'name',
						__( 'Name', 'aberdeen-piano' ),
						'text',
						array( 'instructions' => __( 'Lowercase, no spaces.', 'aberdeen-piano' ) )
					),
					aberdeen_piano_acf_sub_field( 'contact_field_label', 'label', __( 'Label', 'aberdeen-piano' ), 'text' ),
					aberdeen_piano_acf_sub_field(
						'contact_field_type',
						'type',
						__( 'Type', 'aberdeen-piano' ),
						'select',
						array(
							'choices'       => array(
								'text'     => __( 'Text', 'aberdeen-piano' ),
								'email'    => __( 'E-mail', 'aberdeen-piano' ),
								'tel'      => __( 'Telephone', 'aberdeen-piano' ),
								'textarea' => __( 'Message', 'aberdeen-piano' ),
							),
							'allow_null'    => 0,
							'return_format' => 'value',
						)
					),
					aberdeen_piano_acf_sub_field( 'contact_field_required', 'required', __( 'Required', 'aberdeen-piano' ), 'true_false', array( 'ui' => 1 ) ),
					aberdeen_piano_acf_sub_field(
						'contact_field_half',
						'half',
						__( 'Half width', 'aberdeen-piano' ),
						'true_false',
						array(
							'ui'           => 1,
							'instructions' => __( 'Half-width fields pair up on one row.', 'aberdeen-piano' ),
						)
					),
				),
			),
			aberdeen_piano_acf_field(
				'contact_submit_label',
				__( 'Submit button', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'contact.submit_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'contact_success_text',
				__( 'Success message', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'contact.success_text' ),
				)
			),
		)
	);
}

