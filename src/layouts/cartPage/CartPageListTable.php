<?php

namespace Shopglut\layouts\cartPage;

use Shopglut\layouts\cartPage\CartPageEntity;

class CartPageListTable extends \WP_List_Table {

	public function get_layouts( $per_page, $current_page = 1 ) {
		return CartPageEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return CartPageEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'shortcode' => esc_html__( 'Shortcode', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No layout found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'cartpage', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ) ),
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

	public function column_layout_template( $item ) {
		$name = esc_html( $item['layout_template'] );
		return '<strong>' . esc_html( $name ) . '</strong>';
	}

	

	public function column_shortcode( $item ) {
		$shortcode_html = '<input class="shortcode_shopg_table" type="text" readonly value="[shopg_cart_page id=\'' . esc_attr( $item['id'] ) . '\']" />';
		return $shortcode_html;
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
					CartPageEntity::delete_layout( $layout_id );
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