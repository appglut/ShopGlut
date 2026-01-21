<?php

namespace Shopglut\enhancements\ProductQuickView;

class QuickviewListTable extends \WP_List_Table {

	public function get_enhancements( $per_page, $current_page = 1 ) {
		return QuickViewEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return QuickViewEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
			'status' => esc_html__( 'Status', 'shopglut' ),
			'display_locations' => esc_html__( 'Display Locations', 'shopglut' ),
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
		echo '<h3 style="color: #374151; margin: 0 0 8px 0;">' . esc_html__( 'No QuickView Layouts Found', 'shopglut' ) . '</h3>';
		echo '<p style="color: #6b7280; margin: 0 0 20px 0;">' . esc_html__( 'Create your first QuickView layout to get started.', 'shopglut' ) . '</p>';
		echo '<a href="' . esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews&action=create' ) ) . '" class="button button-primary">' . esc_html__( 'Create New Layout', 'shopglut' ) . '</a>';
		echo '</div>';
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'product_quickview', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_enhancements' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' ) ),
			'shopglut_delete_layout_' . $layout_id
		);
		$duplicate_link = add_query_arg(
			array( 'action' => 'duplicate', 'layout_id' => $layout_id ),
			admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' )
		);

		$actions = array(
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'duplicate' => sprintf( '<a href="%s">%s</a>', esc_url( $duplicate_link ), esc_html__( 'Duplicate', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')" style="color: #dc3545;">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this QuickView layout?', 'shopglut' ),
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
	 * Status column - Shows if QuickView is enabled/disabled
	 */
	public function column_status( $item ) {
		$is_enabled = false;
		$has_locations = false;

		if ( isset( $item['layout_settings'] ) ) {
			$settings = maybe_unserialize( $item['layout_settings'] );

			// Check if QuickView is enabled
			if ( isset( $settings['shopg_product_quickview_settings_template1']['enable_quickview'] ) ) {
				$is_enabled = $settings['shopg_product_quickview_settings_template1']['enable_quickview'];
			}

			// Check if display locations are set
			if ( isset( $settings['shopg_product_quickview_settings_template1']['display-locations'] )
				&& ! empty( $settings['shopg_product_quickview_settings_template1']['display-locations'] ) ) {
				$has_locations = true;
			}
		}

		// Determine status
		if ( $is_enabled && $has_locations ) {
			$status_text = esc_html__( 'Active', 'shopglut' );
			$status_color = '#10b981'; // Green
			$status_icon = '✓';
			$tooltip = esc_attr__( 'QuickView is enabled and has display locations configured', 'shopglut' );
		} elseif ( $is_enabled && ! $has_locations ) {
			$status_text = esc_html__( 'Inactive', 'shopglut' );
			$status_color = '#f59e0b'; // Orange
			$status_icon = '⚠';
			$tooltip = esc_attr__( 'QuickView is enabled but no display locations are set', 'shopglut' );
		} else {
			$status_text = esc_html__( 'Disabled', 'shopglut' );
			$status_color = '#ef4444'; // Red
			$status_icon = '✕';
			$tooltip = esc_attr__( 'QuickView is disabled', 'shopglut' );
		}

		return sprintf(
			'<span style="display: inline-flex; align-items: center; gap: 6px; background: %s; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; white-space: nowrap;" title="%s"><span style="font-size: 14px;">%s</span> %s</span>',
			esc_attr( $status_color ),
			$tooltip,
			$status_icon,
			esc_html( $status_text )
		);
	}

	/**
	 * Display Locations column - Shows where QuickView appears
	 */
	public function column_display_locations( $item ) {
		if ( ! isset( $item['layout_settings'] ) ) {
			return '<span style="color: #9ca3af; font-style: italic;">' . esc_html__( 'None', 'shopglut' ) . '</span>';
		}

		$settings = maybe_unserialize( $item['layout_settings'] );
		$locations = array();

		if ( isset( $settings['shopg_product_quickview_settings_template1']['display-locations'] ) ) {
			$raw_locations = $settings['shopg_product_quickview_settings_template1']['display-locations'];

			if ( ! is_array( $raw_locations ) ) {
				$raw_locations = array( $raw_locations );
			}

			// Map location keys to readable names
			foreach ( $raw_locations as $location ) {
				$location_name = $this->get_location_name( $location );
				if ( $location_name ) {
					$locations[] = $location_name;
				}
			}
		}

		if ( empty( $locations ) ) {
			return '<span style="color: #9ca3af; font-style: italic;">' . esc_html__( 'None', 'shopglut' ) . '</span>';
		}

		// Limit display to first 3 locations, show "+X more" if there are more
		$display_count = 3;
		$total_count = count( $locations );
		$display_locations = array_slice( $locations, 0, $display_count );

		$output = '<div style="display: flex; flex-wrap: wrap; gap: 4px;">';

		foreach ( $display_locations as $location ) {
			$output .= sprintf(
				'<span style="background: #e0e7ff; color: #4338ca; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; white-space: nowrap;">%s</span>',
				esc_html( $location )
			);
		}

		if ( $total_count > $display_count ) {
			$remaining = $total_count - $display_count;
			$all_locations = implode( ', ', $locations );
			$output .= sprintf(
				'<span style="background: #f3f4f6; color: #6b7280; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; cursor: help;" title="%s">+%d %s</span>',
				esc_attr( $all_locations ),
				$remaining,
				esc_html( _n( 'more', 'more', $remaining, 'shopglut' ) )
			);
		}

		$output .= '</div>';

		return $output;
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

	/**
	 * Helper function to get readable location name
	 */
	private function get_location_name( $location ) {
		// Static locations
		$location_map = array(
			'Woo Shop Page' => __( 'Shop Page', 'shopglut' ),
			'All Categories' => __( 'All Categories', 'shopglut' ),
			'All Tags' => __( 'All Tags', 'shopglut' ),
			'All Products' => __( 'All Products', 'shopglut' ),
		);

		if ( isset( $location_map[ $location ] ) ) {
			return $location_map[ $location ];
		}

		// Category locations
		if ( strpos( $location, 'cat_' ) === 0 ) {
			$cat_id = str_replace( 'cat_', '', $location );
			$category = get_term( $cat_id, 'product_cat' );
			if ( $category && ! is_wp_error( $category ) ) {
				/* translators: %s: category name */
				return sprintf( __( 'Category: %s', 'shopglut' ), $category->name );
			}
		}

		// Tag locations
		if ( strpos( $location, 'tag_' ) === 0 ) {
			$tag_id = str_replace( 'tag_', '', $location );
			$tag = get_term( $tag_id, 'product_tag' );
			if ( $tag && ! is_wp_error( $tag ) ) {
				/* translators: %s: tag name */
				return sprintf( __( 'Tag: %s', 'shopglut' ), $tag->name );
			}
		}

		// Product locations
		if ( strpos( $location, 'product_' ) === 0 ) {
			$product_id = str_replace( 'product_', '', $location );
			$product = get_post( $product_id );
			if ( $product ) {
				/* translators: %s: product title */
				return sprintf( __( 'Product: %s', 'shopglut' ), $product->post_title );
			}
		}

		return $location;
	}


	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page = $this->get_items_per_page( 'shopglut_enhancements_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items = $this->get_totals();

		$this->set_pagination_args( array(
			'total_items' => $total_items, // total number of items
			'per_page' => $per_page, // items to show on a page
		) );

		$this->items = $this->get_enhancements( $per_page, $current_page );
	}

	// To show checkbox with each row
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="user[]" value="%s" />', $item['id'] );
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
		if ( 'delete' === $this->current_action() ) {
			check_admin_referer( 'bulk-enhancements' );

			$layout_ids = isset( $_POST['user'] ) ? array_map( 'absint', $_POST['user'] ) : [];

			if ( ! empty( $layout_ids ) ) {
				foreach ( $layout_ids as $layout_id ) {
					QuickViewEntity::delete_enhancement( $layout_id );
				}
			}
		}
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		$this->add_table_styles();
		parent::display();
		wp_nonce_field( 'bulk-enhancements' );
	}

	/**
	 * Add custom CSS for better table styling
	 */
	private function add_table_styles() {
		?>
		<style>
			/* QuickView List Table Custom Styles */
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

			.wp-list-table.widefat .column-status {
				text-align: center;
				width: 120px;
			}

			.wp-list-table.widefat .column-layout_template {
				width: 150px;
			}

			.wp-list-table.widefat .column-display_locations {
				width: 300px;
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
				.wp-list-table.widefat .column-display_locations,
				.wp-list-table.widefat .column-date {
					display: none;
				}
			}
		</style>
		<?php
	}

	/**
	 * Get views for filtering (All, Active, Inactive, Disabled)
	 */
	protected function get_views() {
		$views = array();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter for view filtering
		$current = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';

		// Count layouts by status
		$all_count = $this->get_totals();
		$active_count = 0;
		$inactive_count = 0;
		$disabled_count = 0;

		// Get all items to count statuses
		$all_items = $this->get_enhancements( 999, 1 );
		foreach ( $all_items as $item ) {
			$is_enabled = false;
			$has_locations = false;

			if ( isset( $item['layout_settings'] ) ) {
				$settings = maybe_unserialize( $item['layout_settings'] );

				if ( isset( $settings['shopg_product_quickview_settings_template1']['enable_quickview'] ) ) {
					$is_enabled = $settings['shopg_product_quickview_settings_template1']['enable_quickview'];
				}

				if ( isset( $settings['shopg_product_quickview_settings_template1']['display-locations'] )
					&& ! empty( $settings['shopg_product_quickview_settings_template1']['display-locations'] ) ) {
					$has_locations = true;
				}
			}

			if ( $is_enabled && $has_locations ) {
				$active_count++;
			} elseif ( $is_enabled && ! $has_locations ) {
				$inactive_count++;
			} else {
				$disabled_count++;
			}
		}

		// Build view links
		$base_url = admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' );

		$views['all'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			esc_url( $base_url ),
			$current === 'all' ? 'current' : '',
			esc_html__( 'All', 'shopglut' ),
			$all_count
		);

		if ( $active_count > 0 ) {
			$views['active'] = sprintf(
				'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
				esc_url( add_query_arg( 'status', 'active', $base_url ) ),
				$current === 'active' ? 'current' : '',
				esc_html__( 'Active', 'shopglut' ),
				$active_count
			);
		}

		if ( $inactive_count > 0 ) {
			$views['inactive'] = sprintf(
				'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
				esc_url( add_query_arg( 'status', 'inactive', $base_url ) ),
				$current === 'inactive' ? 'current' : '',
				esc_html__( 'Inactive', 'shopglut' ),
				$inactive_count
			);
		}

		if ( $disabled_count > 0 ) {
			$views['disabled'] = sprintf(
				'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
				esc_url( add_query_arg( 'status', 'disabled', $base_url ) ),
				$current === 'disabled' ? 'current' : '',
				esc_html__( 'Disabled', 'shopglut' ),
				$disabled_count
			);
		}

		return $views;
	}
}