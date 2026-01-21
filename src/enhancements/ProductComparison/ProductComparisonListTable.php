<?php

namespace Shopglut\enhancements\ProductComparison;

use Shopglut\enhancements\ProductComparison\ProductComparisonEntity;

class ProductComparisonListTable extends \WP_List_Table {

	public function get_enhancements( $per_page, $current_page = 1 ) {
		return ProductComparisonEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return ProductComparisonEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
			'status' => esc_html__( 'Status', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No enhancement found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'product_comparison', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_enhancements' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparisons' ) ),
			'shopglut_delete_layout_' . $layout_id
		);

		$actions = array(
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this enhancement?', 'shopglut' ),
				esc_html__( 'Delete', 'shopglut' )
			),
		);

		$name = '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $item['layout_name'] ) . '</a>';

		return sprintf( '<strong>%s</strong>%s', $name, $this->row_actions( $actions ) );
	}

	public function column_layout_template( $item ) {
		$name = esc_html( $item['layout_template'] );
		return '<strong>' . esc_html( $name ) . '</strong>';
	}

	public function column_status( $item ) {
		$is_active = false;

		if ( isset( $item['layout_settings'] ) ) {
			$settings = maybe_unserialize( $item['layout_settings'] );

			// Get layout template to determine correct settings key
			$layout_template = isset( $item['layout_template'] ) ? $item['layout_template'] : 'template1';
			$settings_key = 'shopg_product_comparison_settings_' . $layout_template;

			// Check if any display locations are set for this layout
			if ( isset( $settings[ $settings_key ]['display-locations'] ) ) {
				$locations = $settings[ $settings_key ]['display-locations'];
				// Layout is active if it has any display locations configured
				if ( !empty( $locations ) && is_array( $locations ) && count( $locations ) > 0 ) {
					$is_active = true;
				}
			}
		}

		if ( $is_active ) {
			return '<span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__( 'This layout is currently active with display locations', 'shopglut' ) . '">' . esc_html__( 'ACTIVE', 'shopglut' ) . '</span>';
		} else {
			return '<span style="background: #6b7280; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__( 'This layout has no display locations configured', 'shopglut' ) . '">' . esc_html__( 'INACTIVE', 'shopglut' ) . '</span>';
		}
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
					ProductComparisonEntity::delete_enhancement( $layout_id );
				}
			}
		}
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		parent::display();
		wp_nonce_field( 'bulk-enhancements' );
	}
}