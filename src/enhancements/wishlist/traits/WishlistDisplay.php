<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistDisplay {

    
    public function shopglut_load_wishlist_content() {
        check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

        $content = $this->shopglut_wishlist_shortcode();

        if ( $content ) {
            $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            wp_send_json_success( [ 'content' => $content, 'status' => $request_uri ] );
        } else {
            wp_send_json_error( 'Failed to load wishlist content' );
        }
    }

    public function shopglut_wishlist_shortcode() {
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
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );

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
            
             $this->render_multilist_wishlist($wishlist_data);
              
        } else {
            // Render single wishlist
            $this->render_single_wishlist($wishlist_data);
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

    private function render_single_wishlist($wishlist_data) {
        
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



    private function render_multilist_wishlist($wishlist_data) {
        
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


    public function render_wishlist_action_buttons($product_ids_filter, $wishlist_type = 'main', $list_name = '') {
        do_action( 'shopglut_before_render_wishlist_action_buttons', $product_ids_filter, $wishlist_type, $list_name );
        
        $this->internal_render_wishlist_action_buttons($product_ids_filter, $wishlist_type, $list_name);
        
        do_action( 'shopglut_after_render_wishlist_action_buttons', $product_ids_filter, $wishlist_type, $list_name );
    }
    
    public static function trigger_wishlist_action_buttons_render($product_ids_filter) {
        do_action( 'shopglut_render_wishlist_action_buttons', $product_ids_filter );
    }

    private function internal_render_wishlist_action_buttons($product_ids_filter, $wishlist_type = 'main', $list_name = '') {
        ?>
        <div class="wishlist-action-buttons">
            <?php
            if ( is_user_logged_in() && 
                ( isset( $this->enhancements['wishlist-enable-wishlist-subscription'] ) && 
                  $this->enhancements['wishlist-enable-wishlist-subscription'] === '1' ) && 
                ! empty( $product_ids_filter ) ) {
               
                do_action( 'shopglut_wishlist_subscribe_notifications', $product_ids_filter, $wishlist_type, $list_name );
                
            }
            
            // Add QR share button
           if (($this->enhancements['wishlist-enable-share-qr'] ) && 
                  ($this->enhancements['wishlist-enable-share-qr']==='1' ) && 
                ! empty( $product_ids_filter )) {
                $this->render_qr_share_button($list_name);
            } 
            

             if (!defined('SHOPGLUT_WISHLIST_PRO')) {
            
             if (($this->enhancements['wishlist-enable-print-wish'] ) && 
                  ($this->enhancements['wishlist-enable-print-wish']==='1' ) && 
                ! empty( $product_ids_filter )) {
            ?>
            <div class="wishlist-print">
                <button class="btn-print-wishlist" id="print-wishlist">
                    <?php echo esc_html__('Print Wishlist', 'shopglut'); ?>
                </button>
            </div>
            <?php } }
            if (!empty($product_ids_filter)) {
                do_action( 'shopglut_wishlist_all_products_print', $product_ids_filter );
            }
            ?>
        </div>
        <?php
    }
    
    public function init_wishlist_action_buttons_hooks() {
        add_action( 'shopglut_render_wishlist_action_buttons', array( $this, 'handle_render_wishlist_action_buttons' ), 10, 3 );
    }
    
    public function handle_render_wishlist_action_buttons($product_ids_filter, $wishlist_type = 'main', $list_name = '' ) {
        $this->render_wishlist_action_buttons($product_ids_filter, $wishlist_type, $list_name);
    }
}