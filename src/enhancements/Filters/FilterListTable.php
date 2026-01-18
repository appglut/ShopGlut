<?php
namespace Shopglut\enhancements\Filters;

if ( ! defined( 'ABSPATH' ) ) exit;



class FilterListTable extends \WP_List_Table {

	public function get_filters( $per_page, $current_page = 1 ) {
		return FiltersEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return FiltersEntity::retrieveAllCount();
	}

	// Define table columns
	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'filter_name' => esc_html__( 'Name', 'shopglut' ),
			'filter_showing' => esc_html__( 'Filter Showing', 'shopglut' )
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No Filter found.', 'shopglut' );
	}

	public function column_filter_name( $item ) {

		$filter_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'filters', 'filter_id' => $filter_id ), admin_url( 'admin.php?page=shopglut_enhancements' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'filter_id' => $filter_id ), admin_url( 'admin.php?page=shopglut_enhancements' ) ),
			'shopglut_delete_filter_' . $filter_id
		);

		$actions = array(
			// translators: %d is the filter ID number
			'id' => sprintf( __( 'ID: %d', 'shopglut' ), $filter_id ),
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this layout?', 'shopglut' ),
				esc_html__( 'Delete', 'shopglut' )
			),
		);

		$name = '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $item['filter_name'] ) . '</a>';

		return sprintf( '<strong>%s</strong>%s', $name, $this->row_actions( $actions ) );

	}

	public function column_filter_showing( $item ) {
		// Unserialize the data to access the structure
		$name = ( ! empty( $item['filter_settings'] ) && ( $temp = @unserialize( $item['filter_settings'] ) ) !== false ) ? $temp : array();
		// Access the values within the '_pseudo' array under 'filter-show-on-pages'
		$pseudo_options = isset( $name['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-show-on-pages'] )
			? $name['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-show-on-pages']
			: [];

		// Initialize an array to hold the display values
		$display_values = [];

		// Iterate through the pseudo options and convert numeric IDs to term names or layout names
		if ( is_array( $pseudo_options ) ) {
			foreach ( $pseudo_options as $option ) {
				if ( is_numeric( $option ) ) {
					// Convert numeric ID to term name (check both product_cat and product_tag taxonomies)
					$term = get_term( intval( $option ) );

					if ( $term && ! is_wp_error( $term ) ) {
						// If it's a valid term, add the term name
						$display_values[] = $term->name;
					} else {
						// If term doesn't exist, show an unknown message with the term ID
						// translators: %d is the term ID number
						$display_values[] = sprintf( __( 'Unknown term (ID: %d)', 'shopglut' ), $option );
					}
				} elseif ( strpos( $option, 'sglayout' ) === 0 ) {
					// If the option starts with 'sglayout', it's a layout ID
					global $wpdb;
					$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

					// Check if the layout ID exists in the table
					$layout_id = str_replace( 'sglayout', '', $option ); // Get the layout ID number (remove 'sglayout')
					$cache_key = 'shopglut_layout_' . $layout_id;
					$layout = wp_cache_get($cache_key);
					if ($layout === false) {
						$sql = sprintf( "SELECT layout_name FROM %s WHERE id = %%d", esc_sql( $table_name ) );
						$layout = $wpdb->get_row( $wpdb->prepare( $sql, $layout_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
						wp_cache_set($cache_key, $layout, '', 300); // Cache for 5 minutes
					}

					if ( $layout ) {
						// If layout exists, add the layout name
						$display_values[] = $layout->layout_name;
					} else {
						// If layout not found, show an unknown message with the layout ID
						// translators: %d is the layout ID number
						$display_values[] = sprintf( __( 'Unknown layout (ID: %d)', 'shopglut' ), $layout_id );
					}
				} else {
					// If it's neither numeric nor a layout ID, keep the value as it is
					$display_values[] = $option;
				}
			}
		}

		// Implode the array into a string with commas for displaying all values
		$pseudo_options_str = ! empty( $display_values ) ? implode( ', ', $display_values ) : __( 'No options available', 'shopglut' );

		return '<strong>' . esc_html( $pseudo_options_str ) . '</strong>';
	}




	function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		/* pagination */
		$per_page = $this->get_items_per_page( 'shopglut_enhancements_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items = $this->get_totals();

		$this->set_pagination_args( array(
			'total_items' => $total_items, // total number of items
			'per_page' => $per_page, // items to show on a page
		) );

		$this->items = $this->get_filters( $per_page, $current_page );

	}

	// To show checkbox with each row
	function column_cb( $item ) { // translators: %s is a placeholder
		return sprintf( '<input type="checkbox" name="filter[]" value="%s" />', $item['id'] );


	}

	public function get_bulk_actions() {
		$actions = array(
			'delete' => esc_html__( 'Delete', 'shopglut' ),
		);
		return $actions;
	}

	// Process bulk actions
	public function process_bulk_action() {
		if ( 'delete' === $this->current_action() ) {
			check_admin_referer( 'bulk-filters' );

			$filter_ids = isset( $_POST['filter'] ) ? array_map( 'absint', $_POST['filter'] ) : [];

			if ( ! empty( $filter_ids ) ) {
				foreach ( $filter_ids as $filter_id ) {
					FiltersEntity::delete_layout( $filter_id );
				}
			}
		}
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		parent::display();
		wp_nonce_field( 'bulk-filters' );
	}
}