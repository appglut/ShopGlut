<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistAccountPage{


    public function add_my_account_menu_item( $menu_items ) {
        // Get custom page name from enhancements
        $page_name = ! empty( $this->enhancements['wishlist-page-account-page-name'] ) ? 
                     $this->enhancements['wishlist-page-account-page-name'] : 
                     __( 'My Wishlist', 'shopglut' );
        
        // Insert wishlist menu item as per custom name
        $menu_items = array_slice( $menu_items, 0, 1, true ) +
                      [ sanitize_title( $page_name ) => esc_html( $page_name ) ] +
                      array_slice( $menu_items, 1, null, true );
        
        return $menu_items;
    }
    
    public function add_my_account_endpoint() {
        // Get the sanitized endpoint from the custom page name
        $endpoint = ! empty( $this->enhancements['wishlist-page-account-page-name'] ) ? 
                    sanitize_title( $this->enhancements['wishlist-page-account-page-name'] ) : 
                    'my-wishlist';
        add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );
    }
    
    public function my_account_wishlist_content() {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->shopglut_account_wishlist();
    }
    
    public function load_account_wishlist_content() {
        check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
        
        $content = $this->shopglut_account_wishlist();
        
        if ( $content ) {
            wp_send_json_success( $content );
        } else {
            wp_send_json_error( $content );
        }
    }
    
    public function shopglut_account_wishlist() {
        global $wpdb;

        // Check if this is a shared wishlist first
        if ($this->handle_shared_wishlist_display()) {
            return ob_get_clean();
        } 
        // Check if this is a shared wishlist first
        if ($this->handle_shared_social_wishlist_display()) {
            return ob_get_clean();
        }

        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        $enhancements = get_option( 'agshopglut_wishlist_options' );
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

        // Fetch main wishlist items
        $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	

        // Get sublists from social table (pro feature)
        $wishlist_sublist = apply_filters('shopglut_get_user_sublists', [], $user_id);
        
        ob_start();

        echo "<div class='shopglut-wishlist-container'>";

        // Check for multilist functionality
        if (function_exists('is_plugin_active') && 
            is_plugin_active('shopglut-wishlist-pro/shopglut-wishlist-pro.php') && 
            isset( $this->enhancements['wishlist-enable-multilist-tabs'] ) && 
            $this->enhancements['wishlist-enable-multilist-tabs'] === '1' && 
            ! empty( $wishlist_sublist )) {
            
             $this->render_multilist_account_wishlist($wishlist_data);
              
        } else {
            // Render single wishlist
            $this->render_single_account_wishlist($wishlist_data);
        }

        // Add social share buttons
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->render_social_share_buttons();

        // Add QR code modal
        $this->render_qr_code_modal();

        echo "<div id='shopglut-wishlist-notification'></div>";

        echo "</div>";


        return ob_get_clean();
    }

    private function render_single_account_wishlist($wishlist_data) {
        
        echo '<div class="shoglut-wishlist-tabs" id="shopglut-normal-wishlist">';
        
        $product_ids_single = ! empty( $wishlist_data->product_ids ) ? 
            array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
        $product_ids_filter = array_filter( $product_ids_single, function ($value) {
            return ! empty( $value );
        } );

        // Hook: Before wishlist content
        do_action( 'shopglut_before_wishlist_content', $wishlist_data, $product_ids_filter );

        // Render action buttons
        $this->render_wishlist_action_buttons($product_ids_filter, 'main', '');
        
        // Hook: After action buttons
        do_action( 'shopglut_after_action_buttons', $wishlist_data, $product_ids_filter );
        
        // Render controls
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->render_wishlist_controls();

        // Hook: Before wishlist table
        do_action( 'shopglut_before_wishlist_table', $wishlist_data, $product_ids_filter );

        // Render table
        $this->render_wishlist_table( $product_ids_single, 'main' );

        // Hook: After wishlist table
        do_action( 'shopglut_after_wishlist_table', $wishlist_data, $product_ids_filter );

        
        // Hook: Before popular products
        do_action( 'shopglut_before_popular_products', $wishlist_data, $product_ids_filter );

        // Hook: After popular products
        do_action( 'shopglut_after_popular_products', $wishlist_data, $product_ids_filter );

        // Hook: After wishlist content
        do_action( 'shopglut_after_wishlist_content', $wishlist_data, $product_ids_filter );

        if(isset( $this->enhancements['wishlist-enable-other-wishlist'] ) && 
                $this->enhancements['wishlist-enable-other-wishlist'] === '1')
                {
        
                echo wp_kses_post( $this->render_wishlist_popular($product_ids_single) );

                }
        
        echo '</div>';
    }

    private function render_multilist_account_wishlist($wishlist_data) {
        
        echo '<div class="shoglut-wishlist-tabs" id="shopglut-normal-wishlist">';
        
        $product_ids_single = ! empty( $wishlist_data->product_ids ) ? 
            array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
        $product_ids_filter = array_filter( $product_ids_single, function ($value) {
            return ! empty( $value );
        } );

        // Hook: Before wishlist content
        do_action( 'shopglut_before_wishlist_content', $wishlist_data, $product_ids_filter );

        
        // Hook: After action buttons
        do_action( 'shopglut_after_action_buttons', $wishlist_data, $product_ids_filter );
        
        // Hook: Before wishlist table
        do_action( 'shopglut_before_wishlist_table', $wishlist_data, $product_ids_filter );

        // Render table
        do_action('shopglut_wishlist_pro_multilist_tabs', 10, 'multilist-tabs');

        // Hook: After wishlist table
        do_action( 'shopglut_after_wishlist_table', $wishlist_data, $product_ids_filter );

        
        // Hook: Before popular products
        do_action( 'shopglut_before_popular_products', $wishlist_data, $product_ids_filter );

        // Hook: After popular products
        do_action( 'shopglut_after_popular_products', $wishlist_data, $product_ids_filter );

        // Hook: After wishlist content
        do_action( 'shopglut_after_wishlist_content', $wishlist_data, $product_ids_filter );

        if(isset( $this->enhancements['wishlist-enable-other-wishlist'] ) && 
                $this->enhancements['wishlist-enable-other-wishlist'] === '1')
                {
        
                echo wp_kses_post( $this->render_wishlist_popular($product_ids_single) );

                }
        
        echo '</div>';
    }
    

}