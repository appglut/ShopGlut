<?php

namespace Shopglut\layouts\singleProduct;

use Shopglut\layouts\singleProduct\SingleLayoutEntity;

class SingleProductListTable extends \WP_List_Table {

	public function get_layouts( $per_page, $current_page = 1 ) {
		return SingleLayoutEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return SingleLayoutEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'overwrite_all_products' => esc_html__( 'Overwrite All Products', 'shopglut' ),
			'overwrite_specific' => esc_html__( 'Overwrite Specific Products', 'shopglut' ),
            'layout_template' => esc_html__( 'Template', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No layout found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'single_product', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts' ) );
		$delete_link = wp_nonce_url(
			add_query_arg(
				array(
					'page' => 'shopglut_layouts',
					'view' => 'single_product',
					'action' => 'delete',
					'layout_id' => $layout_id
				),
				admin_url( 'admin.php' )
			),
			'shopglut_delete_layout_' . $layout_id
		);

		$actions = array(
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this layout?', 'shopglut' ),
				esc_html__( 'Delete', 'shopglut' )
			),
		);

		$name = '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $item['layout_name'] ) . '</a>';

		return sprintf( '<strong>%s</strong>%s', $name, $this->row_actions( $actions ) );
	}

	/**
	 * Get the ID of layout that has "Overwrite All Products" enabled
	 *
	 * @return int|null Layout ID or null if none found
	 */
	private function get_overwrite_all_layout_id() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
		$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, no caching needed
			$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.MissingReplacements -- Using sprintf with escaped table name, no additional parameters needed
				sprintf("SELECT id, layout_settings, layout_template FROM `%s`", esc_sql($table_name)) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf for table name
			),
			ARRAY_A
		);

		foreach ( $results as $layout ) {
			$layout_settings = maybe_unserialize( $layout['layout_settings'] );

			if ( ! empty( $layout_settings ) ) {
				$template_settings_key = 'shopg_singleproduct_settings_' . $layout['layout_template'];

				if ( isset( $layout_settings[$template_settings_key] ) ) {
					$template_settings = $layout_settings[$template_settings_key];
					$overwrite_all = $template_settings['overwrite-all-products'] ?? false;

					if ( $overwrite_all ) {
						return $layout['id'];
					}
				}
			}
		}

		return null;
	}

	/**
	 * Get layout name by ID
	 *
	 * @param int $layout_id Layout ID
	 * @return string Layout name
	 */
	private function get_layout_name_by_id( $layout_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
		return $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, no caching needed
			$wpdb->prepare(
				sprintf("SELECT layout_name FROM `%s` WHERE id = %d", esc_sql($table_name)), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf for table name, expected 0 but proper placeholders are used
				$layout_id
			)
		);
	}

	/**
	 * Display layout template column
	 *
	 * @param array $item Layout item data
	 * @return string Template database value
	 */
	public function column_layout_template( $item ) {
		$template = isset( $item['layout_template'] ) ? $item['layout_template'] : '';
		
		if ( ! empty( $template ) ) {
			return '<span class="template-badge">' . esc_html( $template ) . '</span>';
		} else {
			return '<span class="template-badge template-empty">' . esc_html__( 'No Template', 'shopglut' ) . '</span>';
		}
	}
    
	/**
	 * Display Overwrite All Products column
	 *
	 * @param array $item Layout item data
	 * @return string Overwrite All Products status
	 */
	public function column_overwrite_all_products( $item ) {
		$layout_id = absint( $item['id'] );

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
		$layout_data = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, no caching needed
			sprintf("SELECT layout_settings FROM `%s` WHERE id = %d", esc_sql($table_name), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameter
		);

		$overwrite_all_status = __( 'Disabled', 'shopglut' );

		if ( $layout_data ) {
			$unserialized_data = maybe_unserialize( $layout_data );

			// Check if main settings exist and overwrite all products is enabled
			if ( isset( $unserialized_data['shopg_singleproduct_settings']['overwrite-all-products'] ) &&
				$unserialized_data['shopg_singleproduct_settings']['overwrite-all-products'] === '1' ) {
				$overwrite_all_status = '<span class="status-enabled">' . __( 'Enabled', 'shopglut' ) . '</span>';
			}
			// If not in main settings, check for template-specific settings
			else {
				// Loop through possible templates (template1, template2, template3, etc.)
				foreach ( $unserialized_data as $key => $value ) {
					if ( strpos( $key, 'shopg_singleproduct_settings_template' ) === 0 && is_array( $value ) ) {
						// Check if this template has overwrite all products enabled
						if ( isset( $value['overwrite-all-products'] ) &&
							$value['overwrite-all-products'] === '1' ) {
							$overwrite_all_status = '<span class="status-enabled">' . __( 'Enabled', 'shopglut' ) . '</span>';
							break; // Exit loop once we find overwrite all products
						}
						// Check nested structure: template -> single-product-settings
						elseif ( isset( $value['single-product-settings']['overwrite-all-products'] ) &&
							$value['single-product-settings']['overwrite-all-products'] === '1' ) {
							$overwrite_all_status = '<span class="status-enabled">' . __( 'Enabled', 'shopglut' ) . '</span>';
							break; // Exit loop once we find overwrite all products
						}
					}
				}
			}
		}

		return $overwrite_all_status;
	}

	/**
	 * Display Overwrite Specific Products column
	 *
	 * @param array $item Layout item data
	 * @return string Formatted product names list or empty
	 */
	public function column_overwrite_specific( $item ) {
		$layout_id = absint( $item['id'] );

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
		$layout_data = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, no caching needed
			sprintf("SELECT layout_settings FROM `%s` WHERE id = %d", esc_sql($table_name), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameter
		);

		$found_products = array();

		if ( $layout_data ) {
			$unserialized_data = maybe_unserialize( $layout_data );

			// Check main settings for specific products
			if ( isset( $unserialized_data['shopg_singleproduct_settings']['overwrite-specific-products'] ) &&
				is_array( $unserialized_data['shopg_singleproduct_settings']['overwrite-specific-products'] ) &&
				!empty( $unserialized_data['shopg_singleproduct_settings']['overwrite-specific-products'] ) ) {
				$found_products = array_merge( $found_products, $unserialized_data['shopg_singleproduct_settings']['overwrite-specific-products'] );
			}

			// Loop through possible templates (template1, template2, template3, etc.)
			foreach ( $unserialized_data as $key => $value ) {
				if ( strpos( $key, 'shopg_singleproduct_settings_template' ) === 0 && is_array( $value ) ) {
					// Check if this template has specific products set
					if ( isset( $value['overwrite-specific-products'] ) &&
							is_array( $value['overwrite-specific-products'] ) &&
							!empty( $value['overwrite-specific-products'] ) ) {
						// Store the products found in this template
						$found_products = array_merge( $found_products, $value['overwrite-specific-products'] );
					}
					// Check nested structure: template -> single-product-settings -> overwrite-specific-products
					elseif ( isset( $value['single-product-settings']['overwrite-specific-products'] ) &&
							is_array( $value['single-product-settings']['overwrite-specific-products'] ) &&
							!empty( $value['single-product-settings']['overwrite-specific-products'] ) ) {
						// Store the products found in this template
						$found_products = array_merge( $found_products, $value['single-product-settings']['overwrite-specific-products'] );
					}
				}
			}
		}

		// Display the found products
		if ( !empty( $found_products ) ) {
			// Remove duplicates and re-index
			$found_products = array_unique( $found_products );
			$found_products = array_values( $found_products );
			$product_names = $this->get_product_names( $found_products );
			return $product_names;
		} else {
			return '<span class="status-disabled">' . __( 'No Specific Products', 'shopglut' ) . '</span>';
		}
	}

	/**
	 * Get product names from product IDs
	 *
	 * @param array $product_ids Array of product IDs
	 * @return string Formatted HTML product names list
	 */
	private function get_product_names( $product_ids ) {
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return '';
		}

		$product_names = array();

		foreach ( $product_ids as $product_id ) {
			$product_id = absint( $product_id );
			if ( $product_id > 0 ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					$product_names[] = '<span class="product-name-item">' . esc_html( $product->get_name() ) . '</span>';
				} else {
					// If product not found, show ID with indication
					// translators: %d: Number
					// translators: %d is the product ID that was not found
					$product_names[] = '<span class="product-name-item product-not-found">' . sprintf( __( 'Product ID: %d (not found)', 'shopglut' ), $product_id ) . '</span>';
				}
			}
		}

		// Display all products without limit
		return '<div class="product-names-list">' . implode( '', $product_names ) . '</div>';
	}

	/**
	 * Make columns sortable
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'layout_name' => array( 'layout_name', false ),
			'layout_template' => array( 'layout_template', false ),
		);
		return $sortable_columns;
	}

	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page = $this->get_items_per_page( 'shopglut_layouts_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items = $this->get_totals();

		$this->set_pagination_args( array(
			'total_items' => $total_items, // total number of items
			'per_page' => $per_page, // items to show on a page
		) );

		$this->items = $this->get_layouts( $per_page, $current_page );
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

	// Process bulk actions - now handled in AllLayouts.php to avoid redirect issues
	public function process_bulk_action() {
		// This method is intentionally empty - bulk actions are now handled in AllLayouts.php
		// before the table is prepared to avoid redirect conflicts
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		// Add custom CSS for template badges, product names, and status indicators
		echo '<style>
			.template-badge {
				display: inline-block;
				padding: 4px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 500;
				background: #f0f4f8;
				color: #455a64;
				border: 1px solid #e0e0e0;
			}
			.template-empty {
				background: #ffebee;
				color: #d32f2f;
				border-color: #ffcdd2;
			}
			.product-names-list {
				font-size: 12px;
				line-height: 1.4;
				max-width: 300px;
			}
			.product-name-item {
				display: block;
				margin-bottom: 2px;
				color: #333;
			}
			.product-not-found {
				color: #d32f2f;
				font-style: italic;
			}
			.more-products {
				color: #666;
				font-size: 11px;
			}
			.status-enabled {
				display: inline-block;
				padding: 4px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 500;
				background: #e8f5e8;
				color: #2e7d32;
				border: 1px solid #c8e6c9;
			}
			.status-disabled {
				display: inline-block;
				padding: 4px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 500;
				background: #f5f5f5;
				color: #757575;
				border: 1px solid #e0e0e0;
			}
		</style>';

		parent::display();
		wp_nonce_field( 'bulk-layouts' );
	}
}