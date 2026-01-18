<?php

namespace Shopglut\layouts\shopLayout;


class ShopListTable extends \WP_List_Table {

	public function get_layouts( $per_page, $current_page = 1 ) {
		return ShopLayoutEntity::retrieveAll( $per_page, $current_page );
	}

	public function get_totals() {
		return ShopLayoutEntity::retrieveAllCount();
	}

	// Define table columns
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'layout_name' => esc_html__( 'Name', 'shopglut' ),
			'shortcode' => esc_html__( 'Shortcode', 'shopglut' ),
			'layout_template' => esc_html__( 'Template', 'shopglut' ),
			'overwriting_shop' => esc_html__( 'Overwrite Woo Shop', 'shopglut' ),
			'archive_showing' => esc_html__( 'Archive Showing', 'shopglut' ),
		);
		return $columns;
	}

	public function no_items() {
		esc_html_e( 'No layout found.', 'shopglut' );
	}

	public function column_layout_name( $item ) {
		$layout_id = absint( $item['id'] );
		$edit_link = add_query_arg( array( 'editor' => 'shop', 'layout_id' => $layout_id ), admin_url( 'admin.php?page=shopglut_layouts' ) );
		$delete_link = wp_nonce_url(
			add_query_arg( array( 'action' => 'delete', 'layout_id' => $layout_id, 'view' => 'shop' ), admin_url( 'admin.php?page=shopglut_layouts' ) ),
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

		$layout_name = isset( $item['layout_name'] ) ? $item['layout_name'] : '';
		$name = '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $layout_name ) . '</a>';

		return sprintf( '<strong>%s</strong>%s', $name, $this->row_actions( $actions ) );
	}

	public function column_layout_template( $item ) {
		$template_name = isset( $item['layout_template'] ) ? $item['layout_template'] : '';
		return '<strong>' . esc_html( $template_name ) . '</strong>';
	}

	public function column_overwriting_shop( $item ) {

		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_id = absint( $item['id'] );
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$current_layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $layout_id ) );

		$current_enable_switcher = '0';
		if ( ! empty( $current_layout_values ) ) {
			$current_layout_data_array = isset( $current_layout_values[0]->layout_settings ) ? unserialize( $current_layout_values[0]->layout_settings ) : array();
			$current_layout_array_values = isset( $current_layout_data_array['shopg_options_settings']['shopg_settings_options']['shopg_display_settings_accordion'] ) ? $current_layout_data_array['shopg_options_settings']['shopg_settings_options']['shopg_display_settings_accordion'] : array();
			$current_enable_switcher = isset( $current_layout_array_values['overwrite-shop-page'] ) ? $current_layout_array_values['overwrite-shop-page'] : '0';
		}
		if ( $current_enable_switcher === '0' ) {
			$value = 'No';
		} else {
			$value = 'Yes';
		}
		return '<strong>' . esc_html( $value ) . '</strong>';
	}

	public function column_shortcode( $item ) {
		$shortcode_html = '<input class="shortcode_shopg_table" type="text" readonly value="[shopg_shop_layout id=\'' . esc_attr( $item['id'] ) . '\']" />';
		return $shortcode_html;
	}

	public function column_archive_showing( $item ) {
		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_id = absint( $item['id'] );
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$current_layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $layout_id ) );

		$archive_pages = array();
		if ( ! empty( $current_layout_values ) ) {
			$current_layout_data_array = isset( $current_layout_values[0]->layout_settings ) ? unserialize( $current_layout_values[0]->layout_settings ) : array();
			$display_settings_accordion = isset( $current_layout_data_array['shopg_options_settings']['shopg_settings_options']['shopg_display_settings_accordion'] ) ? $current_layout_data_array['shopg_options_settings']['shopg_settings_options']['shopg_display_settings_accordion'] : array();

			// Check for select-archive-pages field
			if ( isset( $display_settings_accordion['select-archive-pages'] ) && is_array( $display_settings_accordion['select-archive-pages'] ) ) {
				$selected_archives = $display_settings_accordion['select-archive-pages'];

				foreach ( $selected_archives as $archive ) {
					if ( $archive === 'Woo Shop Page' ) {
						$archive_pages[] = 'Woo Shop';
					} elseif ( $archive === 'All Categories' ) {
						$archive_pages[] = 'All Categories';
					} elseif ( $archive === 'All Tags' ) {
						$archive_pages[] = 'All Tags';
					} elseif ( strpos( $archive, 'cat_' ) === 0 ) {
						$cat_id = str_replace( 'cat_', '', $archive );
						$term = get_term( $cat_id, 'product_cat' );
						if ( $term && ! is_wp_error( $term ) ) {
							$archive_pages[] = 'Cat: ' . $term->name;
						}
					} elseif ( strpos( $archive, 'tag_' ) === 0 ) {
						$tag_id = str_replace( 'tag_', '', $archive );
						$term = get_term( $tag_id, 'product_tag' );
						if ( $term && ! is_wp_error( $term ) ) {
							$archive_pages[] = 'Tag: ' . $term->name;
						}
					}
				}
			}
		}

		if ( empty( $archive_pages ) ) {
			return '<span style="color: #999;">—</span>';
		}

		return '<strong>' . esc_html( implode( ', ', $archive_pages ) ) . '</strong>';
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
					ShopLayoutEntity::delete_layout( $layout_id );
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