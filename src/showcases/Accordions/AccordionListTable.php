<?php
namespace Shopglut\showcases\Accordions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AccordionListTable extends \WP_List_Table {

	public function get_showcases( $per_page, $current_page = 1 ) {
		return AccordionEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return AccordionEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
			'date' => esc_html__( 'Created', 'shopglut' ),
		);
		return $columns;
	}

	// Make columns sortable
	public function get_sortable_columns() {
		$sortable_columns = array(
			'layout_name' => array( 'layout_name', false ),
			'layout_template' => array( 'layout_template', false ),
			'date' => array( 'created_at', true ), // true means already sorted by this column
		);
		return $sortable_columns;
	}

	public function no_items() {
		echo '<div style="padding: 40px 20px; text-align: center;">';
		echo '<div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">📦</div>';
		echo '<h3 style="color: #374151; margin: 0 0 8px 0;">' . esc_html__( 'No Accordion Layouts Found', 'shopglut' ) . '</h3>';
		echo '<p style="color: #6b7280; margin: 0 0 20px 0;">' . esc_html__( 'Create your first Accordion layout to get started.', 'shopglut' ) . '</p>';
		echo '<a href="' . esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=accordion_templates' ) ) . '" class="button button-primary">' . esc_html__( 'Create New Layout', 'shopglut' ) . '</a>';
		echo '</div>';
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'accordion', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_showcases' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_showcases&view=accordions' ) ),
			'shopglut_delete_layout_' . $layout_id
		);
		$duplicate_link = add_query_arg(
			array( 'action' => 'duplicate', 'layout_id' => $layout_id ),
			admin_url( 'admin.php?page=shopglut_showcases&view=accordions' )
		);

		$actions = array(
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'duplicate' => sprintf( '<a href="%s">%s</a>', esc_url( $duplicate_link ), esc_html__( 'Duplicate', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')" style="color: #dc3545;">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this Accordion layout?', 'shopglut' ),
				esc_html__( 'Delete', 'shopglut' )
			),
		);

		$name = '<a href="' . esc_url( $edit_link ) . '" style="font-weight: 600; font-size: 14px;">' . esc_html( $item['layout_name'] ) . '</a>';

		return sprintf( '%s%s', $name, $this->row_actions( $actions ) );
	}

	public function column_layout_template( $item ) {
		$name = esc_html( $item['layout_template'] );
		return '<strong>' . esc_html( $name ) . '</strong>';
	}

	
	/**
	 * Date column - Shows when the layout was created
	 */
	public function column_date( $item ) {
		if ( empty( $item['created_at'] ) ) {
			return '<span style="color: #9ca3af;">—</span>';
		}

		$date = strtotime( $item['created_at'] );
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$formatted_date = date_i18n( $date_format, $date );
		$formatted_time = date_i18n( $time_format, $date );
		$relative_time = human_time_diff( $date, current_time( 'timestamp' ) );

		return sprintf(
			'<span title="%s %s" style="cursor: help;">%s<br><small style="color: #6b7280;">%s %s</small></span>',
			esc_attr( $formatted_date ),
			esc_attr( $formatted_time ),
			esc_html( $formatted_date ),
			esc_html( $relative_time ),
			esc_html__( 'ago', 'shopglut' )
		);
	}

	

	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page = $this->get_items_per_page( 'shopglut_showcases_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items = $this->get_totals();

		$this->set_pagination_args( array(
			'total_items' => $total_items, // total number of items
			'per_page' => $per_page, // items to show on a page
		) );

		$this->items = $this->get_showcases( $per_page, $current_page );
	}

	// To show checkbox with each row
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="layout_ids[]" value="%s" />', $item['id'] );
	}

	// Bulk actions
	public function get_bulk_actions() {
		$actions = array(
			'delete' => esc_html__( 'Delete', 'shopglut' ),
		);
		return $actions;
	}

	// Process bulk actions
	public function process_bulk_action() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below
		if ( 'delete' === $this->current_action() ) {
			check_admin_referer( 'bulk-showcases' );

			$layout_ids = isset( $_POST['layout_ids'] ) ? array_map( 'absint', $_POST['layout_ids'] ) : array();

			if ( ! empty( $layout_ids ) ) {
				foreach ( $layout_ids as $layout_id ) {
					AccordionEntity::delete_enhancement( $layout_id );
				}

				// Add success message
				add_action( 'admin_notices', function() use ( $layout_ids ) {
					$count = count( $layout_ids );
					echo '<div class="notice notice-success is-dismissible"><p>' .
						sprintf(
							/* translators: %d: number of deleted layouts */
							esc_html( _n( '%d Shop Accordion layout deleted successfully.', '%d Shop Accordion layouts deleted successfully.', $count, 'shopglut' ) ),
							(int) $count
						) . '</p></div>';
				});
			}
		}
	}

	// Handle individual actions (delete and duplicate)
	public static function handle_individual_actions() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'shopglut_showcases' && isset( $_GET['view'] ) && $_GET['view'] === 'accordion' ) {

			// Handle delete action
			if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
				$layout_id = absint( $_GET['layout_id'] );
				$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

				if ( wp_verify_nonce( $nonce, 'shopglut_delete_layout_' . $layout_id ) ) {
					AccordionEntity::delete_enhancement( $layout_id );

					// Redirect back to the list
					wp_safe_redirect( add_query_arg(
						array(
							'page' => 'shopglut_showcases',
							'view' => 'accordion',
							'deleted' => '1'
						),
						admin_url( 'admin.php' )
					) );
					exit;
				}
			}

			// Handle duplicate action
			if ( isset( $_GET['action'] ) && $_GET['action'] === 'duplicate' && isset( $_GET['layout_id'] ) ) {
				$layout_id = absint( $_GET['layout_id'] );

				// Get the original layout
				$original_layout = AccordionEntity::retrieve_by_id( $layout_id );
				if ( $original_layout ) {
					// Create duplicate with modified name
					/* translators: %s: original layout name */
					$duplicate_name = sprintf( esc_html__( '%s (Copy)', 'shopglut' ), $original_layout['layout_name'] );

					$new_layout_id = AccordionEntity::duplicate_enhancement( $layout_id, $duplicate_name );

					if ( $new_layout_id ) {
						// Redirect to edit the new layout
						wp_safe_redirect( add_query_arg(
							array(
								'page' => 'shopglut_showcases',
								'editor' => 'accordion',
								'layout_id' => $new_layout_id,
								'duplicated' => '1'
							),
							admin_url( 'admin.php' )
						) );
						exit;
					}
				}
			}
		}
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		$this->add_table_styles();
		parent::display();
		wp_nonce_field( 'bulk-showcases' );
	}

	/**
	 * Add custom CSS for better table styling
	 */
	private function add_table_styles() {
		?>
		<style>
			/* Accordion List Table Custom Styles */
			.wp-list-table.widefat tbody tr:hover {
				background-color: #f9fafb;
			}

			.wp-list-table.widefat th {
				font-weight: 600;
				text-transform: uppercase;
				font-size: 12px;
				letter-spacing: 0.5px;
				color: #374151;
			}

			.wp-list-table.widefat td {
				vertical-align: middle;
				padding: 12px 10px;
			}

			.wp-list-table.widefat .column-layout_template {
				width: 150px;
			}

			.wp-list-table.widefat .column-date {
				width: 180px;
			}

			.wp-list-table.widefat .row-actions {
				color: #6b7280;
			}

			.wp-list-table.widefat .row-actions a {
				color: #4f46e5;
				text-decoration: none;
			}

			.wp-list-table.widefat .row-actions a:hover {
				color: #4338ca;
				text-decoration: underline;
			}

			/* Empty state styling */
			.wp-list-table.widefat tbody td.colspanchange {
				background: #fff;
				border: none;
			}

			/* Checkbox styling */
			.wp-list-table.widefat .check-column {
				width: 2.5em;
			}

			/* Responsive improvements */
			@media screen and (max-width: 782px) {
				.wp-list-table.widefat .column-date {
					display: none;
				}
			}
		</style>
		<?php
	}

}