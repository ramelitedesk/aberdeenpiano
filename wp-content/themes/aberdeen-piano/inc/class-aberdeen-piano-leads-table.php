<?php
/**
 * Admin list table for stored form leads.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Lists rows from the leads table with search, filtering and bulk delete.
 */
class Aberdeen_Piano_Leads_Table extends WP_List_Table {

	/**
	 * Rows per page.
	 *
	 * @var int
	 */
	protected $per_page = 20;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'lead',
				'plural'   => 'leads',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'         => '<input type="checkbox">',
			'name'       => __( 'Name', 'aberdeen-piano' ),
			'email'      => __( 'Email', 'aberdeen-piano' ),
			'phone'      => __( 'Telephone', 'aberdeen-piano' ),
			'message'    => __( 'Message', 'aberdeen-piano' ),
			'form'       => __( 'Form', 'aberdeen-piano' ),
			'created_at' => __( 'Received', 'aberdeen-piano' ),
		);
	}

	/**
	 * Sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'name'       => array( 'name', false ),
			'email'      => array( 'email', false ),
			'form'       => array( 'form', false ),
			'created_at' => array( 'created_at', true ),
		);
	}

	/**
	 * Bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array( 'delete' => __( 'Delete', 'aberdeen-piano' ) );
	}

	/**
	 * Counts per form, for the view links.
	 *
	 * @return array
	 */
	protected function counts() {
		global $wpdb;

		$table = aberdeen_piano_leads_table();

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( "SELECT form, COUNT(*) AS total FROM {$table} GROUP BY form", ARRAY_A );
		// phpcs:enable

		$counts = array( 'all' => 0 );

		foreach ( (array) $rows as $row ) {
			$counts[ $row['form'] ] = (int) $row['total'];
			$counts['all']         += (int) $row['total'];
		}

		return $counts;
	}

	/**
	 * "All | Contact | Subscribers" links.
	 *
	 * @return array
	 */
	protected function get_views() {
		$counts  = $this->counts();
		$current = isset( $_GET['form'] ) ? sanitize_key( wp_unslash( $_GET['form'] ) ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$base    = admin_url( 'admin.php?page=aberdeen-piano-leads' );

		$labels = array(
			'all'       => __( 'All', 'aberdeen-piano' ),
			'contact'      => __( 'Enquiries', 'aberdeen-piano' ),
			'contact_page' => __( 'Contact page', 'aberdeen-piano' ),
			'subscribe' => __( 'Subscribers', 'aberdeen-piano' ),
		);

		$views = array();

		foreach ( $labels as $key => $label ) {
			$count = isset( $counts[ $key ] ) ? $counts[ $key ] : 0;
			$url   = ( 'all' === $key ) ? $base : add_query_arg( 'form', $key, $base );

			$views[ $key ] = sprintf(
				'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
				esc_url( $url ),
				$current === $key ? ' class="current"' : '',
				esc_html( $label ),
				$count
			);
		}

		return $views;
	}

	/**
	 * Query rows for the current page.
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;

		$this->process_bulk_action();

		$table    = aberdeen_piano_leads_table();
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$form   = isset( $_GET['form'] ) ? sanitize_key( wp_unslash( $_GET['form'] ) ) : '';
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
		$orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'created_at';
		$order   = ( isset( $_GET['order'] ) && 'asc' === strtolower( wp_unslash( $_GET['order'] ) ) ) ? 'ASC' : 'DESC';
		$paged   = max( 1, (int) $this->get_pagenum() );
		// phpcs:enable

		$allowed_orderby = array( 'name', 'email', 'form', 'created_at' );

		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'created_at';
		}

		$where  = 'WHERE 1=1';
		$params = array();

		if ( $form && in_array( $form, array( 'contact', 'contact_page', 'subscribe' ), true ) ) {
			$where   .= ' AND form = %s';
			$params[] = $form;
		}

		if ( '' !== $search ) {
			$like     = '%' . $wpdb->esc_like( $search ) . '%';
			$where   .= ' AND ( name LIKE %s OR email LIKE %s OR phone LIKE %s OR message LIKE %s )';
			$params[] = $like;
			$params[] = $like;
			$params[] = $like;
			$params[] = $like;
		}

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
		$count_sql = "SELECT COUNT(*) FROM {$table} {$where}";
		$total     = (int) ( $params ? $wpdb->get_var( $wpdb->prepare( $count_sql, $params ) ) : $wpdb->get_var( $count_sql ) );

		$offset    = ( $paged - 1 ) * $this->per_page;
		$items_sql = "SELECT * FROM {$table} {$where} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d";
		$args      = array_merge( $params, array( $this->per_page, $offset ) );

		$this->items = $wpdb->get_results( $wpdb->prepare( $items_sql, $args ), ARRAY_A );
		// phpcs:enable

		$this->set_pagination_args(
			array(
				'total_items' => $total,
				'per_page'    => $this->per_page,
				'total_pages' => (int) ceil( $total / $this->per_page ),
			)
		);
	}

	/**
	 * Handle bulk delete.
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'delete' !== $this->current_action() ) {
			return;
		}

		check_admin_referer( 'bulk-' . $this->_args['plural'] );

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$ids = isset( $_REQUEST['lead'] ) ? array_map( 'absint', (array) wp_unslash( $_REQUEST['lead'] ) ) : array();
		$ids = array_filter( $ids );

		if ( ! $ids ) {
			return;
		}

		global $wpdb;

		$table        = aberdeen_piano_leads_table();
		$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE id IN ({$placeholders})", $ids ) );
		// phpcs:enable
	}

	/**
	 * Checkbox column.
	 *
	 * @param array $item Row.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="lead[]" value="%d">', (int) $item['id'] );
	}

	/**
	 * Name column, with row actions.
	 *
	 * @param array $item Row.
	 * @return string
	 */
	public function column_name( $item ) {
		$name = $item['name'] ? $item['name'] : __( '(no name)', 'aberdeen-piano' );

		$delete = wp_nonce_url(
			add_query_arg(
				array(
					'page'   => 'aberdeen-piano-leads',
					'action' => 'delete',
					'lead'   => (int) $item['id'],
				),
				admin_url( 'admin.php' )
			),
			'bulk-' . $this->_args['plural']
		);

		$actions = array(
			'delete' => sprintf(
				'<a href="%s" class="submitdelete" onclick="return confirm(\'%s\')">%s</a>',
				esc_url( $delete ),
				esc_js( __( 'Delete this lead?', 'aberdeen-piano' ) ),
				esc_html__( 'Delete', 'aberdeen-piano' )
			),
		);

		return '<strong>' . esc_html( $name ) . '</strong>' . $this->row_actions( $actions );
	}

	/**
	 * Default column output.
	 *
	 * @param array  $item   Row.
	 * @param string $column Column key.
	 * @return string
	 */
	public function column_default( $item, $column ) {
		switch ( $column ) {
			case 'email':
				return $item['email']
					? sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $item['email'] ) )
					: '&mdash;';

			case 'phone':
				return $item['phone']
					? sprintf( '<a href="tel:%s">%s</a>', esc_attr( preg_replace( '/\s+/', '', $item['phone'] ) ), esc_html( $item['phone'] ) )
					: '&mdash;';

			case 'message':
				if ( ! $item['message'] ) {
					return '&mdash;';
				}

				return esc_html( wp_trim_words( $item['message'], 14 ) );

			case 'form':
				$labels = array(
					'contact'      => __( 'Enquiry', 'aberdeen-piano' ),
					'contact_page' => __( 'Contact page', 'aberdeen-piano' ),
					'subscribe' => __( 'Subscriber', 'aberdeen-piano' ),
				);

				$label = isset( $labels[ $item['form'] ] ) ? $labels[ $item['form'] ] : $item['form'];

				return sprintf(
					'<span class="ap-lead-tag %s">%s</span>',
					'subscribe' === $item['form'] ? 'is-subscribe' : 'is-contact',
					esc_html( $label )
				);

			case 'created_at':
				$time = strtotime( $item['created_at'] );

				return sprintf(
					'%s<br><span class="description">%s</span>',
					esc_html( date_i18n( get_option( 'date_format' ), $time ) ),
					esc_html( date_i18n( get_option( 'time_format' ), $time ) )
				);
		}

		return isset( $item[ $column ] ) ? esc_html( $item[ $column ] ) : '';
	}

	/**
	 * Empty state.
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No form submissions yet.', 'aberdeen-piano' );
	}
}
