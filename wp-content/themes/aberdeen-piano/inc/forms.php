<?php
/**
 * Form handling — validation, AJAX submission, lead storage and notifications.
 *
 * Two forms share this pipeline: the contact form on the home page and the
 * newsletter form beneath the Journal. Both submit over AJAX to a single
 * endpoint, are validated on the server with the same rules the browser applies
 * live, are stored in a custom table, and trigger a notification to the studio
 * plus an acknowledgement to the visitor.
 *
 * Client-side validation is a convenience; the rules here are authoritative.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX action name, shared by the handler and the script.
 */
const ABERDEEN_PIANO_FORM_ACTION = 'aberdeen_piano_form';

/**
 * Bumped whenever the leads table schema changes.
 */
const ABERDEEN_PIANO_DB_VERSION = '1.0.0';

/**
 * Name of the leads table, including the site prefix.
 *
 * @return string
 */
function aberdeen_piano_leads_table() {
	global $wpdb;

	return $wpdb->prefix . 'aberdeen_leads';
}

/**
 * Create or update the leads table.
 *
 * @return void
 */
function aberdeen_piano_install_leads_table() {
	global $wpdb;

	if ( get_option( 'aberdeen_piano_db_version' ) === ABERDEEN_PIANO_DB_VERSION ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$table   = aberdeen_piano_leads_table();
	$collate = $wpdb->get_charset_collate();

	// dbDelta is whitespace-sensitive: two spaces after PRIMARY KEY, lowercase types.
	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		form varchar(32) NOT NULL DEFAULT 'contact',
		name varchar(100) NOT NULL DEFAULT '',
		email varchar(191) NOT NULL DEFAULT '',
		phone varchar(40) NOT NULL DEFAULT '',
		message text NOT NULL,
		status varchar(20) NOT NULL DEFAULT 'new',
		ip varchar(45) NOT NULL DEFAULT '',
		user_agent varchar(255) NOT NULL DEFAULT '',
		referer varchar(255) NOT NULL DEFAULT '',
		created_at datetime NOT NULL,
		PRIMARY KEY  (id),
		KEY form (form),
		KEY created_at (created_at)
	) {$collate};";

	dbDelta( $sql );

	update_option( 'aberdeen_piano_db_version', ABERDEEN_PIANO_DB_VERSION );
}
add_action( 'after_switch_theme', 'aberdeen_piano_install_leads_table' );
add_action( 'admin_init', 'aberdeen_piano_install_leads_table' );

/*
 * ---------------------------------------------------------------------------
 * Validation
 *
 * The character rules below are deliberately strict, per the brief. They are
 * expressed once here and mirrored in assets/js/forms.js so the live feedback
 * and the server agree.
 * ---------------------------------------------------------------------------
 */

/**
 * Validate a person's name.
 *
 * Letters only, single spaces between words, 3–20 characters.
 *
 * @param string $value Raw value.
 * @return true|string True when valid, otherwise an error message.
 */
function aberdeen_piano_validate_name( $value ) {
	$value = trim( $value );

	if ( '' === $value ) {
		return __( 'Please enter your name.', 'aberdeen-piano' );
	}

	if ( preg_match( '/\d/u', $value ) ) {
		return __( 'Numbers are not allowed in a name.', 'aberdeen-piano' );
	}

	if ( ! preg_match( '/^\p{L}+(?: \p{L}+)*$/u', $value ) ) {
		return __( 'Use letters and single spaces only.', 'aberdeen-piano' );
	}

	$length = function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );

	if ( $length < 3 ) {
		return __( 'Name must be at least 3 characters.', 'aberdeen-piano' );
	}

	if ( $length > 20 ) {
		return __( 'Name must be 20 characters or fewer.', 'aberdeen-piano' );
	}

	return true;
}

/**
 * Validate an e-mail address.
 *
 * Letters, digits, @ and . only, in a valid address shape.
 *
 * @param string $value Raw value.
 * @return true|string
 */
function aberdeen_piano_validate_email( $value ) {
	$value = trim( $value );

	if ( '' === $value ) {
		return __( 'Please enter your email address.', 'aberdeen-piano' );
	}

	if ( strlen( $value ) > 254 ) {
		return __( 'That email address is too long.', 'aberdeen-piano' );
	}

	/**
	 * Characters permitted in an address.
	 *
	 * The brief allows only @ and . besides letters and digits. Many real
	 * mailboxes also use _ - and +; add them to this pattern to accept those.
	 */
	$allowed = apply_filters( 'aberdeen_piano_email_allowed_pattern', '/^[A-Za-z0-9@.]+$/' );

	if ( ! preg_match( $allowed, $value ) ) {
		return __( 'Only letters, numbers, @ and . are allowed.', 'aberdeen-piano' );
	}

	// Shape: local@domain.tld, no leading/trailing/double dots in either part.
	if ( ! preg_match( '/^[A-Za-z0-9]+(?:\.[A-Za-z0-9]+)*@[A-Za-z0-9]+(?:\.[A-Za-z0-9]+)*\.[A-Za-z]{2,}$/', $value ) ) {
		return __( 'Enter a valid email address, for example name@example.com.', 'aberdeen-piano' );
	}

	if ( ! is_email( $value ) ) {
		return __( 'Enter a valid email address.', 'aberdeen-piano' );
	}

	return true;
}

/**
 * Validate a telephone number.
 *
 * Digits with single spaces, optionally prefixed with + for an international
 * dialling code. 7–15 digits, matching the E.164 range used worldwide.
 *
 * @param string $value    Raw value.
 * @param bool   $required Whether an empty value is an error.
 * @return true|string
 */
function aberdeen_piano_validate_phone( $value, $required = true ) {
	$value = trim( $value );

	if ( '' === $value ) {
		return $required ? __( 'Please enter your telephone number.', 'aberdeen-piano' ) : true;
	}

	if ( preg_match( '/\p{L}/u', $value ) ) {
		return __( 'Letters are not allowed in a phone number.', 'aberdeen-piano' );
	}

	// A leading + is required for international numbers, so it is the one
	// permitted symbol; everything else must be digits or a single space.
	if ( ! preg_match( '/^\+?\d+(?: \d+)*$/', $value ) ) {
		return __( 'Use digits, single spaces, and an optional leading +.', 'aberdeen-piano' );
	}

	$digits = strlen( preg_replace( '/\D/', '', $value ) );

	if ( $digits < 7 ) {
		return __( 'That phone number is too short.', 'aberdeen-piano' );
	}

	if ( $digits > 15 ) {
		return __( 'That phone number is too long.', 'aberdeen-piano' );
	}

	return true;
}

/**
 * Validate one field by type.
 *
 * @param string $type     Field type: name, email, tel or message.
 * @param string $value    Raw value.
 * @param bool   $required Whether the field is required.
 * @return true|string
 */
function aberdeen_piano_validate_field( $type, $value, $required = true ) {
	switch ( $type ) {
		case 'name':
			return aberdeen_piano_validate_name( $value );

		case 'email':
			return aberdeen_piano_validate_email( $value );

		case 'tel':
			return aberdeen_piano_validate_phone( $value, $required );

		case 'subject':
			$value = trim( $value );

			if ( $required && '' === $value ) {
				return __( 'Please enter a subject.', 'aberdeen-piano' );
			}

			if ( strlen( $value ) > 150 ) {
				return __( 'That subject is too long.', 'aberdeen-piano' );
			}

			return true;

		case 'textarea':
		case 'message':
			$value = trim( $value );

			if ( $required && '' === $value ) {
				return __( 'Please enter a message.', 'aberdeen-piano' );
			}

			if ( strlen( $value ) > 2000 ) {
				return __( 'That message is too long.', 'aberdeen-piano' );
			}

			return true;
	}

	return true;
}

/**
 * The fields each form expects: name => array( type, required ).
 *
 * @param string $form Form key.
 * @return array
 */
function aberdeen_piano_form_fields( $form ) {
	// The contact page's fields are editable in ACF, so its spec is derived
	// from them rather than hard-coded.
	if ( 'contact_page' === $form ) {
		return aberdeen_piano_contact_page_form_spec();
	}

	$forms = array(
		'contact'   => array(
			'name'      => array( 'name', true ),
			'email'     => array( 'email', true ),
			'telephone' => array( 'tel', true ),
			'message'   => array( 'message', false ),
		),
		'subscribe' => array(
			'name'  => array( 'name', true ),
			'email' => array( 'email', true ),
		),
	);

	return isset( $forms[ $form ] ) ? $forms[ $form ] : array();
}

/**
 * The Contact page id.
 *
 * Found by assigned template first, then by slug, so the page is located
 * whether the client picked the template explicitly or WordPress matched it
 * from the slug.
 *
 * @return int
 */
function aberdeen_piano_contact_page_id() {
	static $id = null;

	if ( null !== $id ) {
		return $id;
	}

	$id = 0;

	$assigned = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => 'page-contact-us.php', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);

	if ( $assigned ) {
		$id = (int) $assigned[0];

		return $id;
	}

	foreach ( array( 'contact-us', 'contact' ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( $page ) {
			$id = (int) $page->ID;
			break;
		}
	}

	return $id;
}

/**
 * The Contact page form's spec, built from its ACF rows.
 *
 * @return array name => array( rule, required )
 */
function aberdeen_piano_contact_page_form_spec() {
	$rows = aberdeen_piano_rows( 'ap_contact_page_form_fields', 'page_contact.form.fields', aberdeen_piano_contact_page_id() );
	$spec = array();

	foreach ( $rows as $row ) {
		$name = sanitize_key( aberdeen_piano_row( $row, 'name' ) );

		if ( ! $name ) {
			continue;
		}

		$spec[ $name ] = array(
			aberdeen_piano_validation_rule( $name, aberdeen_piano_row( $row, 'type', 'text' ) ),
			aberdeen_piano_row_bool( $row, 'required' ),
		);
	}

	return $spec;
}

/**
 * Handle an AJAX form submission.
 *
 * @return void
 */
function aberdeen_piano_handle_form() {
	check_ajax_referer( ABERDEEN_PIANO_FORM_ACTION, 'nonce' );

	$form = isset( $_POST['form'] ) ? sanitize_key( wp_unslash( $_POST['form'] ) ) : '';
	$spec = aberdeen_piano_form_fields( $form );

	if ( ! $spec ) {
		wp_send_json_error( array( 'message' => __( 'Unknown form.', 'aberdeen-piano' ) ), 400 );
	}

	// Honeypot: a hidden field only a bot would fill in.
	if ( ! empty( $_POST['ap_website'] ) ) {
		wp_send_json_success( array( 'message' => aberdeen_piano_form_success_text( $form ) ) );
	}

	// Light rate limit per IP, so the endpoint cannot be hammered.
	$ip  = aberdeen_piano_client_ip();
	$key = 'ap_form_' . md5( $ip . '|' . $form );

	if ( $ip && get_transient( $key ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Please wait a moment before sending again.', 'aberdeen-piano' ) ),
			429
		);
	}

	$values = array();
	$errors = array();

	foreach ( $spec as $field => $rules ) {
		list( $type, $required ) = $rules;

		$raw = isset( $_POST[ $field ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : '';
		$raw = trim( preg_replace( '/[ \t]+/', ' ', $raw ) );

		$result = aberdeen_piano_validate_field( $type, $raw, $required );

		if ( true !== $result ) {
			$errors[ $field ] = $result;
			continue;
		}

		$values[ $field ] = $raw;
	}

	if ( $errors ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please check the highlighted fields.', 'aberdeen-piano' ),
				'errors'  => $errors,
			),
			422
		);
	}

	$lead_id = aberdeen_piano_store_lead( $form, $values, $ip );

	if ( ! $lead_id ) {
		wp_send_json_error(
			array( 'message' => __( 'Sorry — we could not save your details. Please try again.', 'aberdeen-piano' ) ),
			500
		);
	}

	set_transient( $key, 1, 20 );

	aberdeen_piano_notify_admin( $form, $values, $lead_id );
	aberdeen_piano_notify_visitor( $form, $values );

	/**
	 * Fires after a lead has been stored and notifications sent.
	 *
	 * @param int    $lead_id Row id.
	 * @param string $form    Form key.
	 * @param array  $values  Validated values.
	 */
	do_action( 'aberdeen_piano_lead_created', $lead_id, $form, $values );

	wp_send_json_success(
		array(
			'message' => aberdeen_piano_form_success_text( $form ),
			'leadId'  => $lead_id,
		)
	);
}
add_action( 'wp_ajax_' . ABERDEEN_PIANO_FORM_ACTION, 'aberdeen_piano_handle_form' );
add_action( 'wp_ajax_nopriv_' . ABERDEEN_PIANO_FORM_ACTION, 'aberdeen_piano_handle_form' );

/**
 * The confirmation shown after a successful submission.
 *
 * @param string $form Form key.
 * @return string
 */
function aberdeen_piano_form_success_text( $form ) {
	if ( 'subscribe' === $form ) {
		return aberdeen_piano_journal_field( 'ap_newsletter_success_text', 'journal.newsletter.success_text' );
	}

	if ( 'contact_page' === $form ) {
		return aberdeen_piano_field( 'ap_contact_page_form_success_text', 'page_contact.form.success_text', aberdeen_piano_contact_page_id() );
	}

	return aberdeen_piano_field( 'ap_contact_success_text', 'contact.success_text', aberdeen_piano_front_page_id() );
}

/**
 * The front page id, so contact fields resolve from the right post.
 *
 * @return int
 */
function aberdeen_piano_front_page_id() {
	return (int) get_option( 'page_on_front' );
}

/**
 * The visitor's IP address, as far as it can be trusted.
 *
 * @return string
 */
function aberdeen_piano_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
}

/**
 * Insert a lead.
 *
 * @param string $form   Form key.
 * @param array  $values Validated values.
 * @param string $ip     Client IP.
 * @return int Row id, or 0 on failure.
 */
function aberdeen_piano_store_lead( $form, $values, $ip = '' ) {
	global $wpdb;

	// The leads table has no subject column; a subject is kept at the head of
	// the message so nothing is lost and the schema stays as it is.
	$message = isset( $values['message'] ) ? $values['message'] : '';

	if ( ! empty( $values['subject'] ) ) {
		$message = sprintf(
			/* translators: %s: subject line. */
			__( 'Subject: %s', 'aberdeen-piano' ),
			$values['subject']
		) . "\n\n" . $message;
	}

	$ok = $wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		aberdeen_piano_leads_table(),
		array(
			'form'       => $form,
			'name'       => isset( $values['name'] ) ? $values['name'] : '',
			'email'      => isset( $values['email'] ) ? $values['email'] : '',
			'phone'      => isset( $values['telephone'] ) ? $values['telephone'] : '',
			'message'    => $message,
			'status'     => 'new',
			'ip'         => $ip,
			'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 255 ) : '',
			'referer'    => isset( $_SERVER['HTTP_REFERER'] ) ? substr( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ), 0, 255 ) : '',
			'created_at' => current_time( 'mysql' ),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	return $ok ? (int) $wpdb->insert_id : 0;
}

/**
 * Address notifications are sent to.
 *
 * @return string
 */
function aberdeen_piano_notify_address() {
	$email = aberdeen_piano_option( 'ap_email', 'global.email' );

	return apply_filters( 'aberdeen_piano_notify_address', $email ? $email : get_option( 'admin_email' ) );
}

/**
 * Notify the studio about a new lead.
 *
 * @param string $form    Form key.
 * @param array  $values  Validated values.
 * @param int    $lead_id Row id.
 * @return bool
 */
function aberdeen_piano_notify_admin( $form, $values, $lead_id ) {
	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

	$subject = ( 'subscribe' === $form )
		/* translators: %s: site name. */
		? sprintf( __( '[%s] New newsletter subscriber', 'aberdeen-piano' ), $site )
		/* translators: %s: site name. */
		: sprintf( __( '[%s] New enquiry from the website', 'aberdeen-piano' ), $site );

	$lines = array();

	if ( ! empty( $values['name'] ) ) {
		$lines[] = __( 'Name:', 'aberdeen-piano' ) . ' ' . $values['name'];
	}
	if ( ! empty( $values['email'] ) ) {
		$lines[] = __( 'Email:', 'aberdeen-piano' ) . ' ' . $values['email'];
	}
	if ( ! empty( $values['telephone'] ) ) {
		$lines[] = __( 'Telephone:', 'aberdeen-piano' ) . ' ' . $values['telephone'];
	}
	if ( ! empty( $values['subject'] ) ) {
		$lines[] = __( 'Subject:', 'aberdeen-piano' ) . ' ' . $values['subject'];
	}
	if ( ! empty( $values['message'] ) ) {
		$lines[] = '';
		$lines[] = __( 'Message:', 'aberdeen-piano' );
		$lines[] = $values['message'];
	}

	$lines[] = '';
	$lines[] = sprintf(
		/* translators: %s: admin URL. */
		__( 'View all leads: %s', 'aberdeen-piano' ),
		admin_url( 'admin.php?page=aberdeen-piano-leads' )
	);
	$lines[] = sprintf(
		/* translators: %d: lead reference number. */
		__( 'Reference: #%d', 'aberdeen-piano' ),
		$lead_id
	);

	$headers = array();

	if ( ! empty( $values['email'] ) ) {
		$name      = ! empty( $values['name'] ) ? $values['name'] : $values['email'];
		$headers[] = 'Reply-To: ' . $name . ' <' . $values['email'] . '>';
	}

	return wp_mail( aberdeen_piano_notify_address(), $subject, implode( "\n", $lines ), $headers );
}

/**
 * Acknowledge the submission to the visitor.
 *
 * @param string $form   Form key.
 * @param array  $values Validated values.
 * @return bool
 */
function aberdeen_piano_notify_visitor( $form, $values ) {
	if ( empty( $values['email'] ) ) {
		return false;
	}

	$site  = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$name  = ! empty( $values['name'] ) ? $values['name'] : '';
	$brand = aberdeen_piano_brand_name();

	if ( 'subscribe' === $form ) {
		/* translators: %s: site name. */
		$subject = sprintf( __( 'You are subscribed to %s', 'aberdeen-piano' ), $site );
		$body    = array(
			$name ? sprintf( /* translators: %s: subscriber name. */ __( 'Hello %s,', 'aberdeen-piano' ), $name ) : __( 'Hello,', 'aberdeen-piano' ),
			'',
			__( 'Thank you for subscribing to the studio journal. A short letter arrives a few times a season — practice tips, recital dates, and the occasional listening pick.', 'aberdeen-piano' ),
		);
	} else {
		/* translators: %s: site name. */
		$subject = sprintf( __( 'Thank you for contacting %s', 'aberdeen-piano' ), $site );
		$body    = array(
			$name ? sprintf( /* translators: %s: sender name. */ __( 'Hello %s,', 'aberdeen-piano' ), $name ) : __( 'Hello,', 'aberdeen-piano' ),
			'',
			__( 'Thank you for getting in touch. Your message has reached the studio and Irene will reply personally, usually within a couple of days.', 'aberdeen-piano' ),
		);

		if ( ! empty( $values['message'] ) ) {
			$body[] = '';
			$body[] = __( 'For your records, this is what you sent:', 'aberdeen-piano' );
			$body[] = $values['message'];
		}
	}

	$body[] = '';
	$body[] = '— ' . $brand;
	$body[] = home_url( '/' );

	return wp_mail( $values['email'], $subject, implode( "\n", $body ) );
}

/**
 * Render a form's hidden fields: nonce, form key and honeypot.
 *
 * @param string $form Form key.
 * @return void
 */
function aberdeen_piano_form_hidden_fields( $form ) {
	printf( '<input type="hidden" name="form" value="%s">', esc_attr( $form ) );
	printf(
		'<p class="ap-hp" aria-hidden="true"><label>%1$s<input type="text" name="ap_website" tabindex="-1" autocomplete="off"></label></p>',
		esc_html__( 'Leave this field empty', 'aberdeen-piano' )
	);
}

/**
 * Which validation rule a contact field uses.
 *
 * The contact form's fields are editable in ACF, so the rule is inferred from
 * the field name first, then its input type.
 *
 * @param string $name Field name.
 * @param string $type Input type.
 * @return string One of: name, email, tel, textarea.
 */
function aberdeen_piano_validation_rule( $name, $type ) {
	if ( 'name' === $name ) {
		return 'name';
	}

	if ( 'subject' === $name ) {
		return 'subject';
	}

	if ( 'message' === $name || 'textarea' === $type ) {
		return 'textarea';
	}

	if ( 'email' === $type || false !== strpos( $name, 'email' ) ) {
		return 'email';
	}

	if ( 'tel' === $type || false !== strpos( $name, 'phone' ) || false !== strpos( $name, 'telephone' ) ) {
		return 'tel';
	}

	return 'name';
}

/**
 * A sensible autocomplete token for a field.
 *
 * @param string $name Field name.
 * @param string $type Input type.
 * @return string
 */
function aberdeen_piano_autocomplete( $name, $type ) {
	switch ( aberdeen_piano_validation_rule( $name, $type ) ) {
		case 'email':
			return 'email';
		case 'tel':
			return 'tel';
		case 'name':
			return 'name';
		case 'subject':
			return 'off';
	}

	return 'on';
}

/**
 * Enqueue the form script wherever a form appears.
 *
 * @return void
 */
function aberdeen_piano_form_assets() {
	$contact_page = aberdeen_piano_contact_page_id() && get_queried_object_id() === aberdeen_piano_contact_page_id();

	if ( ! is_front_page() && ! aberdeen_piano_is_journal() && ! is_singular( 'post' ) && ! $contact_page ) {
		return;
	}

	wp_enqueue_script(
		'aberdeen-piano-forms',
		get_template_directory_uri() . '/assets/js/forms.js',
		array(),
		aberdeen_piano_asset_version( 'assets/js/forms.js' ),
		true
	);

	wp_localize_script(
		'aberdeen-piano-forms',
		'aberdeenPianoForms',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'action'  => ABERDEEN_PIANO_FORM_ACTION,
			'nonce'   => wp_create_nonce( ABERDEEN_PIANO_FORM_ACTION ),
			'i18n'    => array(
				'nameRequired'   => __( 'Please enter your name.', 'aberdeen-piano' ),
				'nameNoNumbers'  => __( 'Numbers are not allowed in a name.', 'aberdeen-piano' ),
				'nameChars'      => __( 'Use letters and single spaces only.', 'aberdeen-piano' ),
				'nameShort'      => __( 'Name must be at least 3 characters.', 'aberdeen-piano' ),
				'nameLong'       => __( 'Name must be 20 characters or fewer.', 'aberdeen-piano' ),
				'emailRequired'  => __( 'Please enter your email address.', 'aberdeen-piano' ),
				'emailChars'     => __( 'Only letters, numbers, @ and . are allowed.', 'aberdeen-piano' ),
				'emailFormat'    => __( 'Enter a valid email address, for example name@example.com.', 'aberdeen-piano' ),
				'phoneRequired'  => __( 'Please enter your telephone number.', 'aberdeen-piano' ),
				'phoneNoLetters' => __( 'Letters are not allowed in a phone number.', 'aberdeen-piano' ),
				'phoneChars'     => __( 'Use digits, single spaces, and an optional leading +.', 'aberdeen-piano' ),
				'phoneShort'     => __( 'That phone number is too short.', 'aberdeen-piano' ),
				'phoneLong'      => __( 'That phone number is too long.', 'aberdeen-piano' ),
				'fieldRequired'  => __( 'This field is required.', 'aberdeen-piano' ),
				'checkFields'    => __( 'Please check the highlighted fields.', 'aberdeen-piano' ),
				'sending'        => __( 'Sending…', 'aberdeen-piano' ),
				'thanks'         => __( 'Thank you — we’ll be in touch.', 'aberdeen-piano' ),
				'error'          => __( 'Sorry — something went wrong. Please try again.', 'aberdeen-piano' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aberdeen_piano_form_assets' );
