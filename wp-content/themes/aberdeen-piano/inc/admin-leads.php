<?php
/**
 * Admin screen listing form submissions.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Form Leads menu.
 *
 * @return void
 */
function aberdeen_piano_leads_menu() {
	$hook = add_menu_page(
		__( 'Form Leads', 'aberdeen-piano' ),
		__( 'Form Leads', 'aberdeen-piano' ),
		'manage_options',
		'aberdeen-piano-leads',
		'aberdeen_piano_leads_page',
		'dashicons-email-alt',
		26
	);

	add_action( "load-{$hook}", 'aberdeen_piano_leads_load' );
}
add_action( 'admin_menu', 'aberdeen_piano_leads_menu' );

/**
 * Handle single-row deletion and CSV export before the screen renders.
 *
 * @return void
 */
function aberdeen_piano_leads_load() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : '';
	// phpcs:enable

	if ( 'export' === $action ) {
		check_admin_referer( 'aberdeen_piano_export_leads' );
		aberdeen_piano_export_leads();
	}

	// A single-row delete arrives as ?action=delete&lead=ID.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'delete' === $action && isset( $_REQUEST['lead'] ) && ! is_array( $_REQUEST['lead'] ) ) {
		check_admin_referer( 'bulk-leads' );

		global $wpdb;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$id = absint( wp_unslash( $_REQUEST['lead'] ) );

		if ( $id ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->delete( aberdeen_piano_leads_table(), array( 'id' => $id ), array( '%d' ) );
		}

		wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'admin.php?page=aberdeen-piano-leads' ) ) );
		exit;
	}
}

/**
 * Stream every lead as CSV.
 *
 * @return void
 */
function aberdeen_piano_export_leads() {
	global $wpdb;

	$table = aberdeen_piano_leads_table();

	// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$rows = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY created_at DESC", ARRAY_A );
	// phpcs:enable

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=aberdeen-piano-leads-' . gmdate( 'Y-m-d' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );

	fputcsv( $out, array( 'ID', 'Form', 'Name', 'Email', 'Telephone', 'Message', 'Received', 'IP' ) );

	foreach ( (array) $rows as $row ) {
		fputcsv(
			$out,
			array(
				$row['id'],
				$row['form'],
				$row['name'],
				$row['email'],
				$row['phone'],
				$row['message'],
				$row['created_at'],
				$row['ip'],
			)
		);
	}

	fclose( $out );
	exit;
}

/**
 * Render the screen.
 *
 * @return void
 */
function aberdeen_piano_leads_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'aberdeen-piano' ) );
	}

	$table = new Aberdeen_Piano_Leads_Table();
	$table->prepare_items();

	$export = wp_nonce_url(
		add_query_arg(
			array(
				'page'   => 'aberdeen-piano-leads',
				'action' => 'export',
			),
			admin_url( 'admin.php' )
		),
		'aberdeen_piano_export_leads'
	);
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Form Leads', 'aberdeen-piano' ); ?></h1>
		<a href="<?php echo esc_url( $export ); ?>" class="page-title-action"><?php esc_html_e( 'Export CSV', 'aberdeen-piano' ); ?></a>
		<hr class="wp-header-end">

		<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<?php if ( isset( $_GET['deleted'] ) ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Lead deleted.', 'aberdeen-piano' ); ?></p></div>
		<?php endif; ?>

		<p class="description ap-leads-intro">
			<?php esc_html_e( 'Enquiries from the contact form and sign-ups from the newsletter form. Notifications are sent by e-mail as they arrive; this is the permanent record.', 'aberdeen-piano' ); ?>
		</p>

		<form method="get">
			<input type="hidden" name="page" value="aberdeen-piano-leads">
			<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<?php if ( isset( $_GET['form'] ) ) : ?>
			<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<input type="hidden" name="form" value="<?php echo esc_attr( sanitize_key( wp_unslash( $_GET['form'] ) ) ); ?>">
			<?php endif; ?>
			<?php $table->search_box( __( 'Search leads', 'aberdeen-piano' ), 'aberdeen-piano-leads' ); ?>
		</form>

		<form method="post">
			<?php
			$table->views();
			$table->display();
			?>
		</form>
	</div>
	<?php
}
