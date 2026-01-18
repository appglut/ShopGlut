<?php
namespace Shopglut\tools\productCustomField;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\tools\productCustomField\ProductCustomFieldEntity;

class ProductCustomFieldListTable extends \WP_List_Table {

	public function get_layouts( $per_page, $current_page = 1 ) {
		return ProductCustomFieldEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return ProductCustomFieldEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No layout found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$field_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'product_custom_field', 'field_id' => $field_id ), admin_url( 'admin.php?page=shopglut_tools' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'field_id' => $field_id ), admin_url( 'admin.php?page=shopglut_tools&view=product_custom_field' ) ),
			'shopglut_delete_product_custom_field_' . $field_id
		);

		$actions = array(
			'edit' => sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html__( 'Edit', 'shopglut' ) ),
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Are you sure you want to delete this custom field?', 'shopglut' ),
				esc_html__( 'Delete', 'shopglut' )
			),
		);

		$field_name = isset( $item['field_name'] ) ? $item['field_name'] : '';
		$name = '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $field_name ) . '</a>';

		return sprintf( '<strong>%s</strong>%s', $name, $this->row_actions( $actions ) );
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

	// Process bulk actions
	public function process_bulk_action() {
		if ( 'delete' === $this->current_action() ) {
			check_admin_referer( 'bulk-layouts' );

			$layout_ids = isset( $_POST['user'] ) ? array_map( 'absint', $_POST['user'] ) : [];

			if ( ! empty( $layout_ids ) ) {
				foreach ( $layout_ids as $layout_id ) {
					ProductCustomFieldEntity::delete_layout( $layout_id );
				}
			}
		}
	}

	// Display the table and handle the nonce field for bulk actions
	public function display() {
		parent::display();
		wp_nonce_field( 'bulk-layouts' );
	}
}