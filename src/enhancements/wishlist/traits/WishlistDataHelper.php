<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistDataHelper {
    
    private function prepare_products_data($product_ids) {
        $products_data = array();
        
        foreach ( $product_ids as $product_id ) {
            if ( is_numeric( $product_id ) ) {
                $product = wc_get_product( $product_id );
                if ( $product ) {
                    $products_data[] = array(
                        'id' => $product_id,
                        'product' => $product,
                        'date_added' => $this->get_product_date_added($product_id)
                    );
                }
            }
        }
        
        return $products_data;
    }

    private function get_product_date_added($product_id) {
        global $wpdb;
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
        
        // Get user ID internally
        $current_user_id = get_current_user_id();
        $guest_id = isset($_COOKIE['shog_wishlist_guest_id']) ? sanitize_text_field( wp_unslash( $_COOKIE['shog_wishlist_guest_id'] ) ) : '';
        $user_id = $current_user_id ? $current_user_id : $guest_id;
        
        // Get the row for this user - now including product_individual_dates
        $query = "SELECT product_ids, product_added_time, product_individual_dates FROM $wishlist_table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $row = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );

        if (!$row) {
            return __('Unknown', 'shopglut');
        }

        // Handle product_ids - could be JSON array or comma-separated string
        $product_ids = array();
        
        // Try to decode as JSON first
        $decoded = json_decode($row->product_ids, true);
        if (is_array($decoded)) {
            $product_ids = $decoded;
        } else {
            // If not JSON, treat as comma-separated string
            // Split by comma and remove empty values and trim whitespace
            $product_ids = array_filter(array_map('trim', explode(',', $row->product_ids)));
        }
        
        // Convert product_id to string for comparison (in case it's passed as integer)
        $product_id = (string) $product_id;
        
        // Check if this product exists for this user
        if (in_array($product_id, $product_ids)) {
            // First, try to get individual date for this specific product
            if ($row->product_individual_dates) {
                $individual_dates = json_decode($row->product_individual_dates, true);
                
                if (is_array($individual_dates) && isset($individual_dates[$product_id])) {
                    // Format with date and time (including minutes)
                    $date_format = get_option('date_format');
                    $time_format = get_option('time_format');
                    return date_i18n($date_format . ' ' . $time_format, strtotime($individual_dates[$product_id]));
                }
            }
            
            // Fallback to general product_added_time if individual date not found
            if ($row->product_added_time && $row->product_added_time !== '0000-00-00 00:00:00') {
                // Format with date and time (including minutes)
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                return date_i18n($date_format . ' ' . $time_format, strtotime($row->product_added_time));
            }
        }

        return __('Unknown', 'shopglut');
    }

    private function shopglut_parse_notification_text($text, $product_id) {
        if (empty($text) || empty($product_id)) {
            return $text;
        }
        
        // Get product object
        $product = wc_get_product($product_id);
        if (!$product) {
            return $text;
        }
        
        // Simple placeholders - just the essentials
        $placeholders = array(
            '{product_name}' => $product->get_name(),
            '{product_sku}' => $product->get_sku() ?: '',
        );
        
        // Replace placeholders in text
        $parsed_text = str_replace(array_keys($placeholders), array_values($placeholders), $text);
        
        return $parsed_text;
    }
}