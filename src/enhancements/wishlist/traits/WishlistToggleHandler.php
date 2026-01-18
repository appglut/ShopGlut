<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistToggleHandler {
    
    public function shopglut_toggle_wishlist_callback() {
        global $wpdb;

        // Verify nonce for security
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_wishlist_nonce' ) ) {
            wp_send_json_error( [ 'message' => 'Security check failed.' ] );
            exit;
        }

        // Validate and sanitize input
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( [ 'message' => 'Product ID is required.' ] );
            exit;
        }
        
        $user_id = get_current_user_id();
        $product_id = intval( wp_unslash( $_POST['product_id'] ) );
        $is_added = isset( $_POST['is_added'] ) ? intval( wp_unslash( $_POST['is_added'] ) ) : 0;
        $guest_id = isset( $_POST['shog_wishlist_guest_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shog_wishlist_guest_id'] ) ) : '';
        $post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'product';
        $table_name = $wpdb->prefix . 'shopglut_wishlist';

        $response_data = array();

        // Get button configuration based on post type
        $button_config = $this->get_button_config_by_post_type($post_type);
        $second_click_action = $button_config['second_click_action'];
        
        // Get page URLs
        $wishlist_page_url = get_permalink( $this->enhancements['wishlist-general-page'] );
        $checkout_page_url = wc_get_checkout_url();

        if ( $user_id ) {
            // Logged-in User Handling
            $this->handle_logged_in_user_toggle($user_id, $product_id, $is_added, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        } elseif ( $guest_id ) {
            // Guest User Handling
            $this->handle_guest_user_toggle($guest_id, $product_id, $is_added, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        } else {
            wp_send_json_error( [ 'message' => 'User ID and Guest ID are both missing.' ] );
            exit;
        }

        wp_send_json_error( [ 'message' => 'Unexpected error occurred.' ] );
        exit;
    }

    private function get_button_config_by_post_type($post_type) {
        $config = array();
        
        if ( $post_type === 'product' ) {
            $config['second_click_action'] = $this->enhancements['wishlist-product-second-click'];
            $config['button_text'] = $this->enhancements['wishlist-product-button-text'];
            $config['icon'] = $this->enhancements['wishlist-product-icon'];
            $config['added_button_text'] = $this->enhancements['wishlist-product-button-text-after-added'];
            $config['added_icon'] = $this->enhancements['wishlist-product-added-icon'];
        } else if ( $post_type === 'archive' ) {
            $config['second_click_action'] = $this->enhancements['wishlist-archive-second-click'];
            $config['button_text'] = $this->enhancements['wishlist-archive-button-text'];
            $config['icon'] = $this->enhancements['wishlist-archive-icon'];
            $config['added_button_text'] = $this->enhancements['wishlist-archive-button-text-after-added'];
            $config['added_icon'] = $this->enhancements['wishlist-archive-added-icon'];
        } else if ( $post_type === 'shop' ) {
            $config['second_click_action'] = $this->enhancements['wishlist-shop-second-click'];
            $config['button_text'] = $this->enhancements['wishlist-shop-button-text'];
            $config['icon'] = $this->enhancements['wishlist-shop-icon'];
            $config['added_button_text'] = $this->enhancements['wishlist-shop-button-text-after-added'];
            $config['added_icon'] = $this->enhancements['wishlist-shop-added-icon'];
        }
        
        return $config;
    }

    private function handle_logged_in_user_toggle($user_id, $product_id, $is_added, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        global $wpdb;
        
        $user = get_userdata( $user_id );
        $username = $user->user_login;
        $useremail = $user->user_email;

        if ( $is_added ) {
            // Remove from wishlist for logged-in user
            $this->remove_product_for_logged_user($user_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        } else {
            // Add to wishlist for logged-in user
            $this->add_product_for_logged_user($user_id, $product_id, $table_name, $username, $useremail, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        }
    }

    private function handle_guest_user_toggle($guest_id, $product_id, $is_added, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        if ( $is_added ) {
            // Remove from wishlist for guest user
            $this->remove_product_for_guest($guest_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        } else {
            // Add to wishlist for guest user
            $this->add_product_for_guest($guest_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url);
        }
    }

    private function remove_product_for_logged_user($user_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        global $wpdb;
        
        $query = "SELECT * FROM $table_name WHERE wish_user_id = %d";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $existing_entry = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
        
        if ( $existing_entry ) {
            // Handle product_ids properly (could start with comma)
            $product_ids = array_filter(array_map('trim', explode(',', $existing_entry->product_ids)));
            $product_ids = array_diff( $product_ids, [ (string)$product_id ] );
            $updated_product_ids = implode( ',', $product_ids );

            // Update individual dates - remove this product's date
            $individual_dates = json_decode($existing_entry->product_individual_dates, true) ?: array();
            unset($individual_dates[(string)$product_id]);

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                $table_name,
                [ 
                    'product_ids' => $updated_product_ids, 
                    'product_added_time' => current_time( 'mysql' ),
                    'product_individual_dates' => json_encode($individual_dates)
                ],
                [ 'wish_user_id' => $user_id ]
            );

            $response_data = $this->build_remove_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id);
            wp_send_json_success( $response_data );
            exit;
        } else {
            wp_send_json_error( [ 'message' => 'No products found for removal.' ] );
            exit;
        }
    }

    private function add_product_for_logged_user($user_id, $product_id, $table_name, $username, $useremail, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        global $wpdb;
        
        $query = "SELECT * FROM $table_name WHERE wish_user_id = %d";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $existing_entry = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
        
        if ( $existing_entry ) {
            // Update existing entry
            $product_ids = array_filter(array_map('trim', explode(',', $existing_entry->product_ids)));
            if ( ! in_array( (string)$product_id, $product_ids ) ) {
                $product_ids[] = $product_id;
                $updated_product_ids = implode( ',', $product_ids );

                // Update individual dates - add this product's date
                $individual_dates = json_decode($existing_entry->product_individual_dates, true) ?: array();
                $individual_dates[(string)$product_id] = current_time( 'mysql' );

                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                    $table_name,
                    [ 
                        'product_ids' => $updated_product_ids, 
                        'product_added_time' => current_time( 'mysql' ),
                        'product_individual_dates' => json_encode($individual_dates)
                    ],
                    [ 'wish_user_id' => $user_id ]
                );
            }
        } else {
            // Insert new entry for logged-in user
            $individual_dates = array((string)$product_id => current_time( 'mysql' ));
            
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->insert(
                $table_name,
                [ 
                    'wish_user_id' => $user_id,
                    'username' => $username,
                    'useremail' => $useremail,
                    'product_ids' => $product_id,
                    'wishlist_notifications' => '',
                    'product_added_time' => current_time( 'mysql' ),
                    'product_individual_dates' => json_encode($individual_dates)
                ]
            );
        }
        
        $response_data = $this->build_add_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id);
        wp_send_json_success( $response_data );
        exit;
    }

    private function remove_product_for_guest($guest_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        global $wpdb;
        
        $query = "SELECT * FROM $table_name WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $existing_entry = $wpdb->get_row( $wpdb->prepare( $query, $guest_id ) );
        
        if ( $existing_entry ) {
            $product_ids = array_filter(array_map('trim', explode(',', $existing_entry->product_ids)));
            $product_ids = array_diff( $product_ids, [ (string)$product_id ] );
            $updated_product_ids = implode( ',', $product_ids );

            // Update individual dates - remove this product's date
            $individual_dates = json_decode($existing_entry->product_individual_dates, true) ?: array();
            unset($individual_dates[(string)$product_id]);

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                $table_name,
                [ 
                    'product_ids' => $updated_product_ids, 
                    'product_added_time' => current_time( 'mysql' ),
                    'product_individual_dates' => json_encode($individual_dates)
                ],
                [ 'wish_user_id' => $guest_id ]
            );
            
            $response_data = $this->build_remove_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id);
            wp_send_json_success( $response_data );
            exit;
        } else {
            wp_send_json_error( [ 'message' => __( 'No product found for removal.', 'shopglut' ) ] );
            exit;
        }
    }

    private function add_product_for_guest($guest_id, $product_id, $table_name, $button_config, $second_click_action, $wishlist_page_url, $checkout_page_url) {
        global $wpdb;
        
        $query = "SELECT * FROM $table_name WHERE wish_user_id = %s";
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $existing_entry = $wpdb->get_row( $wpdb->prepare( $query, $guest_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared	

        
        if ( $existing_entry ) {
            // Update existing entry
            $product_ids = array_filter(array_map('trim', explode(',', $existing_entry->product_ids)));
            if ( ! in_array( (string)$product_id, $product_ids ) ) {
                $product_ids[] = $product_id;
                $updated_product_ids = implode( ',', $product_ids );

                // Update individual dates - add this product's date
                $individual_dates = json_decode($existing_entry->product_individual_dates, true) ?: array();
                $individual_dates[(string)$product_id] = current_time( 'mysql' );

                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->update(
                    $table_name,
                    [ 
                        'product_ids' => $updated_product_ids, 
                        'product_added_time' => current_time( 'mysql' ),
                        'product_individual_dates' => json_encode($individual_dates)
                    ],
                    [ 'wish_user_id' => $guest_id ]
                );
            }
        } else {
            // Insert new entry for guest user
            $individual_dates = array((string)$product_id => current_time( 'mysql' ));
            
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->insert(
                $table_name,
                [ 
                    'wish_user_id' => $guest_id,
                    'username' => 'Guest',
                    'useremail' => 'guest@example.com',
                    'product_ids' => $product_id,
                    'product_added_time' => current_time( 'mysql' ),
                    'product_individual_dates' => json_encode($individual_dates),
                    'wishlist_notifications' => ''
                ]
            );
        }
        
        $response_data = $this->build_add_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id);
        wp_send_json_success( $response_data );
        exit;
    }

    private function build_remove_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id) {
        $response_data = array();
        
        switch ( $second_click_action ) {
            case 'goto-wishlist':
                $response_data['button_text'] = $button_config['button_text'];
                $response_data['button_icon'] = $button_config['icon'];
                $response_data['href'] = $wishlist_page_url;
                $notification_text = $this->enhancements['wishlist-product-removed-notification-text'] ?? __('product removed from wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = false;
                break;
            case 'show-already-exist':
                $response_data['button_text'] = $button_config['button_text'];
                $response_data['button_icon'] = $button_config['icon'];
                $response_data['href'] = '';
                $response_data['class'] = 'already-added';
                $response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
                $response_data['perform_toggle'] = 'already-added';
                break;
            case 'redirect-to-checkout':
                $response_data['button_text'] = $button_config['button_text'];
                $response_data['button_icon'] = $button_config['icon'];
                $response_data['href'] = $checkout_page_url;
                $response_data['class'] = 'checkout-link';
                $notification_text = $this->enhancements['wishlist-product-removed-notification-text'] ?? __('product removed from wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = false;
                break;
            case 'remove-wishlist':
                $response_data['button_text'] = $button_config['button_text'];
                $response_data['button_icon'] = $button_config['icon'];
                $notification_text = $this->enhancements['wishlist-product-removed-notification-text'] ?? __('product removed from wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = true;
                break;
        }
        
        // Add counter data to avoid separate AJAX request
        $response_data['counter'] = array(
            'count' => $this->get_wishlist_count(),
            'animation' => 'bounce'
        );
        
        return $response_data;
    }

    private function build_add_response($button_config, $second_click_action, $wishlist_page_url, $checkout_page_url, $product_id) {
        $response_data = array();
        
        switch ( $second_click_action ) {
            case 'goto-wishlist':
                $response_data['button_text'] = $button_config['added_button_text'];
                $response_data['button_icon'] = $button_config['added_icon'];
                $response_data['href'] = $wishlist_page_url;
                $notification_text = $this->enhancements['wishlist-product-added-notification-text'] ?? __('Product added to wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = false;
                break;
            case 'show-already-exist':
                $response_data['button_text'] = $button_config['added_button_text'];
                $response_data['button_icon'] = $button_config['added_icon'];
                $response_data['class'] = 'already-added';
                $response_data['perform_toggle'] = 'already-added';
                $response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
                break;
            case 'redirect-to-checkout':
                $response_data['button_text'] = $button_config['added_button_text'];
                $response_data['button_icon'] = $button_config['added_icon'];
                $response_data['href'] = $checkout_page_url;
                $response_data['class'] = 'checkout-link';
                $notification_text = $this->enhancements['wishlist-product-added-notification-text'] ?? __('Product added to wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = false;
                break;
            case 'remove-wishlist':
                $response_data['button_text'] = $button_config['added_button_text'];
                $response_data['button_icon'] = $button_config['added_icon'];
                $notification_text = $this->enhancements['wishlist-product-added-notification-text'] ?? __('Product added to wishlist', 'shopglut');
                $response_data['notification_text'] = $this->shopglut_parse_notification_text($notification_text, $product_id);
                $response_data['perform_toggle'] = true;
                break;
        }
        
        // Add counter data to avoid separate AJAX request
        $response_data['counter'] = array(
            'count' => $this->get_wishlist_count(),
            'animation' => 'bounce'
        );
        
        return $response_data;
    }

}