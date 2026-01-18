<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistPopular {
    
    private function render_wishlist_popular($product_ids) {

        ob_start(); ?>


         <!-- Bulk Actions -->
        <div class="wishlist-social-proof">
      <h4> <?php echo esc_html__('Others also wishlisted:', 'shopglut')  ?></h4>
       <?php echo wp_kses_post( $this->get_popular_wishlist_items($product_ids) ); ?>
       </div>

        <?php 

        return ob_get_clean();

    }

    private function get_popular_wishlist_items($current_product_ids) {
        global $wpdb;
        $table = $wpdb->prefix . 'shopglut_wishlist';
        
        if (empty($current_product_ids)) {
            return '';
        }
        
        // Convert current product IDs to array if it's a string
        if (is_string($current_product_ids)) {
            $current_product_ids = array_filter(array_map('trim', explode(',', $current_product_ids)));
        } else {
            // Ensure all IDs are strings for consistent comparison
            $current_product_ids = array_map('strval', $current_product_ids);
        }
        
        // Get all wishlist entries
        $query = "SELECT product_ids 
            FROM $table 
            WHERE product_ids != '' 
            AND product_ids IS NOT NULL";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $all_wishlists = $wpdb->get_results( $query );
        
        if (empty($all_wishlists)) {
            return '<p class="no-popular-items">' . esc_html__('No popular items found.', 'shopglut') . '</p>';
        }
        
        // Count product occurrences across all wishlists
        $product_counts = array();
        
        foreach ($all_wishlists as $wishlist) {
            $product_ids = array_filter(array_map('trim', explode(',', $wishlist->product_ids)));
            
            foreach ($product_ids as $product_id) {
                if (!empty($product_id) && is_numeric($product_id)) {
                    // Only count products that are NOT in current user's wishlist
                    if (!in_array($product_id, array_map('strval', $current_product_ids))) {
                        if (!isset($product_counts[$product_id])) {
                            $product_counts[$product_id] = 0;
                        }
                        $product_counts[$product_id]++;
                    }
                }
            }
        }
        
        if (empty($product_counts)) {
            return '<p class="no-popular-items">' . esc_html__('No recommendations available.', 'shopglut') . '</p>';
        }
        
        // Sort by popularity (highest count first) and limit to top 5
        arsort($product_counts);
        $popular_product_ids = array_slice(array_keys($product_counts), 0, 5);
        
        return $this->render_popular_items_grid($popular_product_ids, $product_counts);
    }

    private function render_popular_items_grid($popular_product_ids, $product_counts) {
        $output = '<div class="popular-wishlist-items">';
        $output .= '<div class="popular-items-grid">';
        
        $items_found = 0;
        
        foreach ($popular_product_ids as $product_id) {
            $product = wc_get_product($product_id);
            
            // Skip if product doesn't exist or is not published
            if (!$product || $product->get_status() !== 'publish') {
                continue;
            }
            
            $output .= $this->render_single_popular_item($product, $product_id, $product_counts[$product_id]);
            $items_found++;
            
            // Break if we have enough items
            if ($items_found >= 5) {
                break;
            }
        }
        
        $output .= '</div>'; // End grid
        
        // Add view all link if there are more popular items
        if (count($product_counts) > 5) {
            $shop_url = get_permalink(wc_get_page_id('shop'));
            $output .= '<div class="popular-items-footer">';
            $output .= '<a href="' . esc_url($shop_url) . '" class="view-all-popular">';
            $output .= __('View All Popular Products', 'shopglut') . ' →';
            $output .= '</a>';
            $output .= '</div>';
        }
        
        $output .= '</div>'; // End popular-wishlist-items
        
        // Only return content if we found items
        return $items_found > 0 ? $output : '<p class="no-popular-items">' . esc_html__('No popular items available at the moment.', 'shopglut') . '</p>';
    }

    private function render_single_popular_item($product, $product_id, $wishlist_count) {
        $product_url = get_permalink($product_id);
        $product_image = $product->get_image('thumbnail');
        $product_name = $product->get_name();
        $product_price = $product->get_price_html();
        
        // Check if current user already has this in wishlist
        $is_in_wishlist = $this->is_product_in_user_wishlist($product_id);
        $wishlist_button_data = $this->get_wishlist_button_data($is_in_wishlist, $product_id);
        
        $output = '<div class="popular-item" data-product-id="' . esc_attr($product_id) . '">';
        $output .= '<div class="popular-item-image">';
        $output .= '<a href="' . esc_url($product_url) . '">' . $product_image . '</a>';
        
        // Add popularity badge
        $output .= '<div class="popularity-badge">';
        $output .= '<span class="wishlist-count">👥 ' . 
            /* translators: %d: number of users who wishlisted this product */
            // translators: %d is the number of times this product was wishlisted
            sprintf(__('%d wishlisted', 'shopglut'), $wishlist_count) . '</span>';
        $output .= '</div>';
        
        $output .= '</div>';
        
        $output .= '<div class="popular-item-details">';
        $output .= '<h4 class="popular-item-title"><a href="' . esc_url($product_url) . '">' . esc_html($product_name) . '</a></h4>';
        $output .= '<div class="popular-item-price">' . $product_price . '</div>';
        
        // Add stock status
        $output .= $this->get_stock_status_html($product);
        
        // Add quick action buttons
        $output .= '<div class="popular-item-actions">';
        $output .= $this->render_popular_item_wishlist_button($product_id, $wishlist_button_data);
        $output .= '</div>'; // End actions
        $output .= '</div>'; // End details
        $output .= '</div>'; // End popular-item
        
        return $output;
    }

    private function get_wishlist_button_data($is_in_wishlist, $product_id) {
        $wishlist_button_class = $is_in_wishlist ? 'shopgw-added' : 'not-shopgw-added';
        $href = '#';
        
        // Use the EXACT same enhancements as your working button
        if ($is_in_wishlist) {
            $wishlist_button_text = $this->enhancements['wishlist-product-button-text-after-added'] ?? __('Added to Wishlist', 'shopglut');
            $wishlist_icon_class = $this->enhancements['wishlist-product-added-icon'] ?? 'fa fa-heart';
            
            // Handle second click action like your existing button
            $second_click_action = $this->enhancements['wishlist-product-second-click'] ?? 'remove-wishlist';
            switch ( $second_click_action ) {
                case 'goto-wishlist':
                    $href = esc_url( get_permalink( $this->enhancements['wishlist-general-page'] ) );
                    break;
                case 'redirect-to-checkout':
                    $wishlist_button_class = "checkout-link";
                    $href = esc_url( wc_get_checkout_url() );
                    break;
                case 'show-already-exist':
                    $wishlist_button_class = "already-added";
                    break;
                default:
                    $wishlist_button_class = "shopgw-added";
                    $href = '#';
                    break;
            }
        } else {
            $wishlist_button_text = $this->enhancements['wishlist-product-button-text'] ?? __('Add to Wishlist', 'shopglut');
            $wishlist_icon_class = $this->enhancements['wishlist-product-icon'] ?? 'fa-regular fa-heart';
            $wishlist_button_class = 'not-shopgw-added';
            $href = '#';
        }
        
        // Handle login requirement like your existing button
        if ( $this->enhancements['wishlist-require-login'] == true && ! is_user_logged_in() ) {
            $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $href = wp_login_url( site_url( $request_uri ) );
            $wishlist_button_class = "login-required";
            $wishlist_button_text = __( 'Login Required', 'shopglut' );
            $wishlist_icon_class = $this->enhancements['wishlist-require-login-btn-icon'];
        }
        
        return array(
            'text' => $wishlist_button_text,
            'icon' => $wishlist_icon_class,
            'class' => $wishlist_button_class,
            'href' => $href
        );
    }

    private function get_stock_status_html($product) {
        if ($product->is_in_stock()) {
            $stock_quantity = $product->get_stock_quantity();
            if ($stock_quantity && $stock_quantity <= 10) {
                return '<div class="stock-status low-stock">⚠️ ' . 
                    /* translators: %d: stock quantity remaining */
                    // translators: %d is the stock quantity remaining
                    sprintf(__('Only %d left!', 'shopglut'), $stock_quantity) . '</div>';
            } else {
                return '<div class="stock-status in-stock">✅ ' . esc_html__('In Stock', 'shopglut') . '</div>';
            }
        } else {
            return '<div class="stock-status out-of-stock">❌ ' . esc_html__('Out of Stock', 'shopglut') . '</div>';
        }
    }

    private function render_popular_item_wishlist_button($product_id, $button_data) {
        $output = '<div class="shopglut_wishlist single-product popular-wishlist-container">';
        $output .= '<a href="' . esc_url($button_data['href']) . '" class="button ' . esc_attr($button_data['class']) . '" ';
        $output .= 'data-product-id="' . esc_attr($product_id) . '">';
        $output .= '<i class="' . esc_attr($button_data['icon']) . '"></i> ';
        $output .= '<span class="button-text">' . esc_html($button_data['text']) . '</span>';
        $output .= '</a>';
        $output .= '</div>';
        
        return $output;
    }

    private function is_product_in_user_wishlist($product_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shopglut_wishlist';
        
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        
        if (!$user_id) {
            return false;
        }
        
        $query = "SELECT product_ids FROM $table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
        
        if (!$wishlist_data || empty($wishlist_data->product_ids)) {
            return false;
        }
        
        $current_product_ids = array_filter(array_map('trim', explode(',', $wishlist_data->product_ids)));
        
        return in_array((string)$product_id, $current_product_ids);
    }

}