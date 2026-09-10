<?php
/**
 * ACF field registration for the Journal (blog) templates.
 *
 * Registered against the page chosen as "Posts page" in Settings → Reading, so
 * the listing's copy is editable in one place. The filter buttons themselves are
 * not fields — they are generated from the post categories.
 *
 * Groups are appended to the theme's list via the aberdeen_piano_acf_groups
 * filter, keeping front page and Journal field definitions in separate files.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the Journal groups to the registered set.
 *
 * @param array $groups Existing field groups.
 * @return array
 */
function aberdeen_piano_acf_journal_groups( $groups ) {
	$groups[] = aberdeen_piano_acf_group_journal();
	$groups[] = aberdeen_piano_acf_group_newsletter();

	return $groups;
}
add_filter( 'aberdeen_piano_acf_groups', 'aberdeen_piano_acf_journal_groups' );

/**
 * Location rules that put a group on the posts page (the Journal).
 *
 * @return array
 */
function aberdeen_piano_acf_posts_page_location() {
	return array(
		array(
			array(
				'param'    => 'page_type',
				'operator' => '==',
				'value'    => 'posts_page',
			),
		),
	);
}

/**
 * Journal listing — hero, marquee, grid heading, filters and pagination labels.
 *
 * @return array
 */
function aberdeen_piano_acf_group_journal() {
	return aberdeen_piano_acf_group(
		'journal',
		__( 'Journal — Listing', 'aberdeen-piano' ),
		1,
		array(
			aberdeen_piano_acf_field( 'journal_tab_hero', __( 'Hero', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ),
			aberdeen_piano_acf_field(
				'journal_eyebrow',
				__( 'Eyebrow', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.eyebrow' ) )
			),
			aberdeen_piano_acf_field(
				'journal_title_white',
				__( 'Headline — white key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'instructions'  => __( 'Shown on the Journal itself. Category and search views show their own title.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.title_white' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_title_black',
				__( 'Headline — black key word', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'default_value' => aberdeen_piano_default( 'journal.title_black' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_title_em',
				__( 'Headline — emphasis line', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '34' ),
					'default_value' => aberdeen_piano_default( 'journal.title_em' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_description',
				__( 'Introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 4,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'journal.description' ),
				)
			),
			array(
				'key'          => 'field_ap_journal_marquee',
				'label'        => __( 'Marquee items', 'aberdeen-piano' ),
				'name'         => 'ap_journal_marquee',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add item', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'journal_marquee_text', 'text', __( 'Text', 'aberdeen-piano' ) ),
				),
			),

			aberdeen_piano_acf_field( 'journal_tab_loader', __( 'Loader', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'journal_loader_mark',
				__( 'Loader marquee text', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'instructions'  => __( 'Shown on the Journal, categories and articles.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.loader_mark' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_loader_message',
				__( 'Loader message', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'journal.loader_message' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_loader_label',
				__( 'Loader accessible label', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.loader_label' ) )
			),

			aberdeen_piano_acf_field( 'journal_tab_grid', __( 'Post grid', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'journal_head_eyebrow',
				__( 'Section eyebrow', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.head_eyebrow' ) )
			),
			aberdeen_piano_acf_field(
				'journal_head_heading',
				__( 'Section heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.head_heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_head_intro',
				__( 'Section introduction', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'journal.head_intro' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_filters_all',
				__( '"All posts" button label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'instructions'  => __( 'The remaining filter buttons are your post categories, generated automatically.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.filters_all' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_filters_label',
				__( 'Filter bar label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'instructions'  => __( 'Read by screen readers.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.filters_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_read_more',
				__( '"Read" link label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'default_value' => aberdeen_piano_default( 'journal.read_more' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_prev_label',
				__( 'Pagination — newer', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '33' ),
					'default_value' => aberdeen_piano_default( 'journal.prev_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_next_label',
				__( 'Pagination — older', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '34' ),
					'default_value' => aberdeen_piano_default( 'journal.next_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_empty_text',
				__( 'Empty state', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( 'Shown when a filter or search returns nothing.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.empty_text' ),
				)
			),

			aberdeen_piano_acf_field( 'journal_tab_quote', __( 'Quote', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'journal_quote_text',
				__( 'Quote', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'journal.quote.text' ),
				)
			),
			aberdeen_piano_acf_field(
				'journal_quote_cite',
				__( 'Attribution', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.quote.cite' ) )
			),
		),
		array( 'location' => aberdeen_piano_acf_posts_page_location() )
	);
}

/**
 * Newsletter block shown beneath the Journal.
 *
 * @return array
 */
function aberdeen_piano_acf_group_newsletter() {
	return aberdeen_piano_acf_group(
		'newsletter',
		__( 'Journal — Newsletter', 'aberdeen-piano' ),
		2,
		array(
			aberdeen_piano_acf_field(
				'newsletter_eyebrow',
				__( 'Eyebrow', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.newsletter.eyebrow' ) )
			),
			aberdeen_piano_acf_field(
				'newsletter_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'journal.newsletter.heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_description',
				__( 'Description', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'journal.newsletter.description' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_name_label',
				__( 'Name field label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'journal.newsletter.name_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_email_label',
				__( 'E-mail field label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'journal.newsletter.email_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_submit_label',
				__( 'Submit button', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'journal.newsletter.submit_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_success_text',
				__( 'Success message', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'journal.newsletter.success_text' ),
				)
			),
			aberdeen_piano_acf_field(
				'newsletter_note',
				__( 'Small print', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'journal.newsletter.note' ) )
			),
		),
		array( 'location' => aberdeen_piano_acf_posts_page_location() )
	);
}

/**
 * Add the article-page groups to the registered set.
 *
 * @param array $groups Existing field groups.
 * @return array
 */
function aberdeen_piano_acf_article_groups( $groups ) {
	$groups[] = aberdeen_piano_acf_group_article();
	$groups[] = aberdeen_piano_acf_group_post();

	return $groups;
}
add_filter( 'aberdeen_piano_acf_groups', 'aberdeen_piano_acf_article_groups' );

/**
 * Article page furniture — labels and the sidebar call to action.
 *
 * Registered on the posts page, alongside the Journal listing settings, because
 * these apply to every article rather than to one post.
 *
 * @return array
 */
function aberdeen_piano_acf_group_article() {
	return aberdeen_piano_acf_group(
		'article',
		__( 'Journal — Article page', 'aberdeen-piano' ),
		3,
		array(
			aberdeen_piano_acf_field( 'article_tab_sidebar', __( 'Sidebar', 'aberdeen-piano' ), 'tab', array( 'placement' => 'top' ) ),
			aberdeen_piano_acf_field(
				'article_toc_title',
				__( 'Contents heading', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'instructions'  => __( 'The contents list is built automatically from the article headings.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'article.toc_title' ),
				)
			),
			aberdeen_piano_acf_field(
				'article_keep_title',
				__( '"Keep reading" heading', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'article.keep_title' ),
				)
			),
			aberdeen_piano_acf_field(
				'article_cta_title',
				__( 'Call to action — heading', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'article.cta_title' ) )
			),
			aberdeen_piano_acf_field(
				'article_cta_text',
				__( 'Call to action — text', 'aberdeen-piano' ),
				'textarea',
				array(
					'rows'          => 3,
					'new_lines'     => '',
					'default_value' => aberdeen_piano_default( 'article.cta_text' ),
				)
			),
			aberdeen_piano_acf_field(
				'article_cta_link',
				__( 'Call to action — button', 'aberdeen-piano' ),
				'link',
				array( 'return_format' => 'array' )
			),

			aberdeen_piano_acf_field( 'article_tab_footer', __( 'Author & sharing', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'article_share_label',
				__( 'Share label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'article.share_label' ),
				)
			),
			aberdeen_piano_acf_field(
				'article_author_role',
				__( 'Author role label', 'aberdeen-piano' ),
				'text',
				array(
					'wrapper'       => array( 'width' => '50' ),
					'default_value' => aberdeen_piano_default( 'article.author_role' ),
				)
			),

			aberdeen_piano_acf_field( 'article_tab_related', __( 'Related', 'aberdeen-piano' ), 'tab' ),
			aberdeen_piano_acf_field(
				'article_related_eyebrow',
				__( 'Eyebrow', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'article.related_eyebrow' ) )
			),
			aberdeen_piano_acf_field(
				'article_related_heading',
				__( 'Heading', 'aberdeen-piano' ),
				'text',
				array(
					'instructions'  => __( '&lt;em&gt; and &lt;br&gt; are allowed.', 'aberdeen-piano' ),
					'default_value' => aberdeen_piano_default( 'article.related_heading' ),
				)
			),
			aberdeen_piano_acf_field(
				'article_related_link',
				__( '"All articles" link label', 'aberdeen-piano' ),
				'text',
				array( 'default_value' => aberdeen_piano_default( 'article.related_link' ) )
			),
		),
		array( 'location' => aberdeen_piano_acf_posts_page_location() )
	);
}

/**
 * Per-article options, shown on the post edit screen.
 *
 * @return array
 */
function aberdeen_piano_acf_group_post() {
	return aberdeen_piano_acf_group(
		'post',
		__( 'Article options', 'aberdeen-piano' ),
		1,
		array(
			array(
				'key'          => 'field_ap_article_marquee',
				'label'        => __( 'Marquee items', 'aberdeen-piano' ),
				'name'         => 'ap_article_marquee',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __( 'Add item', 'aberdeen-piano' ),
				'instructions' => __( 'Optional. Leave empty to use the Journal marquee.', 'aberdeen-piano' ),
				'sub_fields'   => array(
					aberdeen_piano_acf_sub_field( 'article_marquee_text', 'text', __( 'Text', 'aberdeen-piano' ) ),
				),
			),
		),
		array(
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
			),
			'position' => 'side',
		)
	);
}
