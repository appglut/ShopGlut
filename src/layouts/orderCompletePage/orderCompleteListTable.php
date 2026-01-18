<?php

namespace Shopglut\layouts\orderCompletePage;

use Shopglut\layouts\orderCompletePage\orderCompletePageEntity;

class OrdercompleteListTable extends \WP_List_Table {

	public function get_layouts( $per_page, $current_page = 1 ) {
		return orderCompletePageEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return orderCompletePageEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
			'override_status' => esc_html__( 'Override Status', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No layout found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'ordercomplete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts&view=ordercomplete' ) ),
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

		// Check if this layout has override enabled
		$override_badge = '';
		if ( isset( $item['layout_settings'] ) ) {
			$settings = maybe_unserialize( $item['layout_settings'] );
			$override_enabled = false;

			// Check in the new location (inside ordercomplete-page-settings tab)
			if ( isset( $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] )
				&& ( $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1 ) ) {
				$override_enabled = true;
			}
			// Also check old location for backward compatibility
			elseif ( isset( $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] )
				&& ( $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == true
					|| $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == '1'
					|| $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == 1 ) ) {
				$override_enabled = true;
			}

			if ( $override_enabled ) {
				$override_badge = ' <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; margin-left: 8px;" title="' . esc_attr__( 'This layout is overriding the WooCommerce order complete page', 'shopglut' ) . '">' . esc_html__( 'ACTIVE OVERRIDE', 'shopglut' ) . '</span>';
			}
		}

		return sprintf( '<strong>%s</strong>%s%s', $name, $override_badge, $this->row_actions( $actions ) );
	}

	public function column_layout_template( $item ) {
		$name = esc_html( $item['layout_template'] );
		return '<strong>' . esc_html( $name ) . '</strong>';
	}

	public function column_override_status( $item ) {
		if ( isset( $item['layout_settings'] ) ) {
			$settings = maybe_unserialize( $item['layout_settings'] );
			$override_enabled = false;

			// Check in the new location (inside ordercomplete-page-settings tab)
			if ( isset( $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] )
				&& ( $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1 ) ) {
				$override_enabled = true;
			}
			// Also check old location for backward compatibility
			elseif ( isset( $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] )
				&& ( $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == true
					|| $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == '1'
					|| $settings['shopg_ordercomplete_override']['override_woocommerce_ordercomplete'] == 1 ) ) {
				$override_enabled = true;
			}

			if ( $override_enabled ) {
				return '<span style="display: inline-flex; align-items: center; background: #10b981; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">
					<span style="width: 8px; height: 8px; background: white; border-radius: 50%; margin-right: 6px;"></span>
					' . esc_html__( 'Active', 'shopglut' ) . '
				</span>';
			} else {
				return '<span style="display: inline-flex; align-items: center; color: #6b7280; font-size: 12px;">
					<span style="width: 8px; height: 8px; background: #d1d5db; border-radius: 50%; margin-right: 6px;"></span>
					' . esc_html__( 'Inactive', 'shopglut' ) . '
				</span>';
			}
		}

		return '<span style="color: #9ca3af; font-size: 12px;">' . esc_html__( '-', 'shopglut' ) . '</span>';
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
					orderCompletePageEntity::delete_layout( $layout_id );
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