<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistAjaxHandlers {
    
    public function shopglut_bulk_add_to_cart() {
        check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
        
        $product_ids = isset($_POST['product_ids']) ? array_map('intval', $_POST['product_ids']) : [];
        $quantities = isset($_POST['quantities']) ? array_map('intval', $_POST['quantities']) : [];
        
        if (empty($product_ids)) {
            wp_send_json_error('No products selected');
        }
        
        $added_products = [];
        $failed_products = [];
        
        foreach ($product_ids as $index => $product_id) {
            $quantity = isset($quantities[$index]) ? $quantities[$index] : 1;
            
            $result = WC()->cart->add_to_cart($product_id, $quantity);
            
            if ($result) {
                $added_products[] = $product_id;
                
                // Remove from wishlist if option is enabled
                if ($this->enhancements['wishlist-remove-if-add-to-cart'] == '1') {
                    $this->remove_single_product_from_wishlist($product_id);
                }
            } else {
                $failed_products[] = $product_id;
            }
        }
        
        wp_send_json_success([
            'added' => count($added_products),
            'failed' => count($failed_products),
            // translators: %d is the number of products added to cart
            'message' => sprintf(__('%d products added to cart', 'shopglut'), count($added_products))
        ]);
    }

    public function shopglut_add_all_to_cart() {
        check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
        
        global $wpdb;
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

        // Fetch all wishlist items
        $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	

        if (!$wishlist_data) {
            wp_send_json_error('No wishlist found');
        }

        $product_ids = array_filter(array_map('trim', explode(',', $wishlist_data->product_ids)));
        
        if (empty($product_ids)) {
            wp_send_json_error('No products in wishlist');
        }
        
        $added_products = [];
        $failed_products = [];
        
        foreach ($product_ids as $product_id) {
            $result = WC()->cart->add_to_cart($product_id, 1);
            
            if ($result) {
                $added_products[] = $product_id;
                
                // Remove from wishlist if option is enabled
                if ($this->enhancements['wishlist-remove-if-add-to-cart'] == '1') {
                    $this->remove_single_product_from_wishlist($product_id);
                }
            } else {
                $failed_products[] = $product_id;
            }
        }
        
        wp_send_json_success([
            'added' => count($added_products),
            'failed' => count($failed_products),
            // translators: %d is the number of products added to cart
            'message' => sprintf(__('%d products added to cart', 'shopglut'), count($added_products))
        ]);
    }

    public function shopglut_remove_from_wishlist() {
        // Verify nonce for security
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_wishlist_nonce' ) ) {
            wp_send_json_error( 'Security check failed.' );
            return;
        }

        global $wpdb;

        // Validate and sanitize input
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( 'Product ID is required.' );
            return;
        }

        if ( ! isset( $_POST['wishlist_type'] ) ) {
            wp_send_json_error( 'Wishlist type is required.' );
            return;
        }

        $product_id = intval( sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) );
        $wishlist_type = sanitize_text_field( wp_unslash( $_POST['wishlist_type'] ) );
        $list_name = isset( $_POST['list_name'] ) ? sanitize_text_field( wp_unslash( $_POST['list_name'] ) ) : null;
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();

        if ( $user_id && $product_id ) {
            $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

            // Fetch current wishlist data for the user
            $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	

            if ( $wishlist_data ) {
                if ( $wishlist_type === 'main' ) {
                    $this->remove_from_main_wishlist($wishlist_data, $product_id, $user_id, $wishlist_table);
                } elseif ( $wishlist_type === 'list' && $list_name ) {
                    // Sublist operations are handled by the pro plugin
                    do_action( 'shopglut_remove_from_sublist', $product_id, $list_name, $user_id );
                }

                wp_send_json_success();
            } else {
                wp_send_json_error( 'Could not find wishlist data for the user.' );
            }
        } else {
            wp_send_json_error( 'Invalid product ID or user not logged in.' );
        }
    }

    private function remove_from_main_wishlist($wishlist_data, $product_id, $user_id, $wishlist_table) {
        global $wpdb;
        
        // Remove the product from the main product_ids list
        $product_ids = explode( ',', $wishlist_data->product_ids );
        $updated_product_ids = array_diff( $product_ids, [ $product_id ] );
        $updated_product_ids_string = implode( ',', $updated_product_ids );

        // Update only the product_ids in the main wishlist
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $wishlist_table,
            [ 'product_ids' => $updated_product_ids_string ],
            [ 'wish_user_id' => $user_id ],
            [ '%s' ],
            [ '%s' ]
        );
    }


    private function remove_single_product_from_wishlist($product_id) {
        global $wpdb;
        
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
        
        $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $product_ids = $wpdb->get_var( $wpdb->prepare( $query, $user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery	, WordPress.DB.DirectDatabaseQuery.NoCaching	
        
        if ($product_ids) {
            $product_ids_array = explode(',', $product_ids);
            $product_ids_array = array_diff($product_ids_array, [$product_id]);
            $updated_product_ids = implode(',', $product_ids_array);
            
            $wpdb->update(// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                $wishlist_table,
                ['product_ids' => $updated_product_ids],
                ['wish_user_id' => $user_id]
            );
        }
    }

    public function shopglut_bulk_remove_from_wishlist() {
        check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
        
        $product_ids = isset($_POST['product_ids']) ? array_map('intval', $_POST['product_ids']) : [];
        
        if (empty($product_ids)) {
            wp_send_json_error('No products selected');
        }
        
        $removed_products = [];
        $failed_products = [];
        
        foreach ($product_ids as $product_id) {
            try {
                $this->remove_single_product_from_wishlist($product_id);
                $removed_products[] = $product_id;
            } catch (Exception $e) {
                $failed_products[] = $product_id;
            }
        }
        
        wp_send_json_success([
            'removed' => count($removed_products),
            'failed' => count($failed_products),
            // translators: %d is the number of products removed from wishlist
            'message' => sprintf(__('%d products removed from wishlist', 'shopglut'), count($removed_products))
        ]);
    }
}