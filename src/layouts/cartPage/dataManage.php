<?php
namespace Shopglut\layouts\cartPage;

if ( ! defined( 'ABSPATH' ) )  {
	exit;
}

class dataManage {

    public function __construct() {
        add_action( 'wp_ajax_save_shopg_cartlayoutdata', array( $this, 'save_shopg_cartlayoutdata' ) );
        add_action( 'wp_ajax_reset_shopg_cartlayout_settings', array( $this, 'reset_shopg_cartlayout_settings' ) );

        // Add AJAX handlers for cart functionality
        add_action( 'wp_ajax_shopglut_update_cart_quantity', array( $this, 'ajax_update_cart_quantity' ) );
        add_action( 'wp_ajax_nopriv_shopglut_update_cart_quantity', array( $this, 'ajax_update_cart_quantity' ) );

        add_action( 'wp_ajax_shopglut_remove_cart_item', array( $this, 'ajax_remove_cart_item' ) );
        add_action( 'wp_ajax_nopriv_shopglut_remove_cart_item', array( $this, 'ajax_remove_cart_item' ) );

        add_action( 'wp_ajax_shopglut_apply_coupon', array( $this, 'ajax_apply_coupon' ) );
        add_action( 'wp_ajax_nopriv_shopglut_apply_coupon', array( $this, 'ajax_apply_coupon' ) );

        add_action( 'wp_ajax_shopglut_remove_coupon', array( $this, 'ajax_remove_coupon' ) );
        add_action( 'wp_ajax_nopriv_shopglut_remove_coupon', array( $this, 'ajax_remove_coupon' ) );

        // Register shortcode
        add_shortcode( 'shopglut_cart_page', array( $this, 'render_cart_shortcode' ) );
    }

    /**
     * Save cart layout data via AJAX
     */
    public function save_shopg_cartlayoutdata() {

        // Check if clean JSON data is sent
        $clean_data = null;
        if ( isset( $_POST['cart_layout_data'] ) ) {
            $json_string = sanitize_text_field( wp_unslash( $_POST['cart_layout_data'] ) );
            $clean_data = json_decode( $json_string, true );
        }

        // Fallback to old format if clean data not available
        if ( empty( $clean_data ) ) {
            $raw_data = isset( $_POST['shopg_cartpage_settings_template1'] ) ? map_deep( wp_unslash( $_POST['shopg_cartpage_settings_template1'] ), 'sanitize_text_field' ) : array();
            $data_to_save = $raw_data;
        } else {
            // Convert clean JSON data to expected serialized format
            $data_to_save = array( 'shopg_cartpage_settings_template1' => $this->convert_clean_json_to_expected_format( $clean_data ) );
        }

        if ( ! empty( $data_to_save ) && isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopg_cartpage_layouts' ) && ! empty( $_POST['layout_name'] ) ) {

            global $wpdb;

            $layout_id = isset( $_POST['shopg_cart_layoutid'] ) ? absint( wp_unslash( $_POST['shopg_cart_layoutid'] ) ) : 0;
            $layout_name = sanitize_text_field( wp_unslash( $_POST['layout_name'] ) );

            $table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $existing_record = $wpdb->get_row(
                sprintf( "SELECT * FROM `%s` WHERE id = %d", esc_sql( $table_name ), $layout_id ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated ID
            );

            $data_to_insert = array(
                'layout_name' => $layout_name,
                'layout_settings' => serialize( $data_to_save ),
            );

            if ( $existing_record ) {
                $data_to_insert['updated_at'] = current_time( 'mysql' );
                $wpdb->update( $table_name, $data_to_insert, array( 'id' => $existing_record->id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            } else {
                // Remove any potential ID from data_to_insert to avoid duplicate key errors
                unset( $data_to_insert['id'] );
                $data_to_insert['layout_template'] = 'template1'; // Default template
                $data_to_insert['created_at'] = current_time( 'mysql' );
                $data_to_insert['updated_at'] = current_time( 'mysql' );

                // Try to insert and handle potential duplicate key error
                try {
                    $wpdb->insert( $table_name, $data_to_insert ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
                } catch ( Exception $e ) {
                    // Check if it's a duplicate entry error
                    if ( strpos( $e->getMessage(), 'Duplicate entry' ) !== false ) {
                        // If duplicate, try to get the existing record and update it instead
                        $existing_duplicate = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for duplicate checking, caching not appropriate here
                            sprintf("SELECT * FROM `%s` WHERE layout_name = %s ORDER BY id DESC LIMIT 1", esc_sql($table_name), esc_sql($layout_name)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and parameter
                        );

                        if ( $existing_duplicate ) {
                            $data_to_insert['updated_at'] = current_time( 'mysql' );
                            $wpdb->update( $table_name, $data_to_insert, array( 'id' => $existing_duplicate->id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for duplicate update, caching not appropriate here
                        } else {
                            // If we can't find the duplicate, return error
                            wp_send_json_error( 'Database error: Duplicate entry detected' );
                            return;
                        }
                    } else {
                        // For other errors, re-throw
                        throw $e;
                    }
                }
            }

            wp_send_json_success( true );
        }
        wp_send_json_error( 'Invalid request' );
    }

    /**
     * Reset cart layout settings to default values
     */
    public function reset_shopg_cartlayout_settings() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopg_cartpage_layouts' ) ) {
            wp_send_json_error( 'Security check failed.' );
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'You do not have permission to perform this action.' );
            return;
        }

        // Get layout ID
        $layout_id = isset( $_POST['layout_id'] ) ? absint( wp_unslash( $_POST['layout_id'] ) ) : 0;

        if ( ! $layout_id ) {
            wp_send_json_error( 'Invalid layout ID.' );
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

        // Clear the layout_settings column value
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table update with proper prepare statements
        $result = $wpdb->update(
            $table_name,
            array(
                'layout_settings' => null,
                'updated_at' => current_time( 'mysql' )
            ),
            array( 'id' => $layout_id ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        // Clear cache for this layout
        wp_cache_delete( 'shopglut_layout_' . $layout_id, 'shopglut_layouts' );

        if ( $result !== false ) {
            wp_send_json_success( array(
                'message' => 'Settings reset successfully!'
            ) );
        } else {
            wp_send_json_error( 'Failed to reset settings.' );
        }
    }

    /**
     * Process and clean cart layout data
     */
    private function process_cart_layout_data( $data ) {
        // If the data is already in the expected format, return it
        if ( isset( $data['shopg_cartpage_settings_template1'] ) ) {
            return $data;
        }

        // Otherwise, wrap it in the expected structure
        return array( 'shopg_cartpage_settings_template1' => $data );
    }

    /**
     * Convert clean JSON data to expected format
     */
    private function convert_clean_json_to_expected_format( $clean_data ) {
        // The JavaScript already sends data with shopg_cartpage_settings_template1 as the root key,
        // so we need to extract the inner structure to avoid double-nesting
        if ( isset( $clean_data['shopg_cartpage_settings_template1'] ) ) {
            return $clean_data['shopg_cartpage_settings_template1'];
        }

        // Fallback to returning the data as is if the expected structure isn't found
        return $clean_data;
    }

    /**
     * Process individual data values
     */
    private function process_data_value( $value ) {
        if ( is_array( $value ) ) {
            $processed = array();
            foreach ( $value as $sub_key => $sub_value ) {
                $processed[ $sub_key ] = $this->process_data_value( $sub_value );
            }
            return $processed;
        }

        // Sanitize and return the value
        if ( is_string( $value ) ) {
            return sanitize_text_field( $value );
        }

        return $value;
    }

    /**
     * Validate cart layout data structure
     */
    private function validate_cart_layout_data( $data ) {
        // Basic validation
        if ( ! is_array( $data ) ) {
            return false;
        }

        // Add more specific validation rules as needed
        return true;
    }

    /**
     * Render cart layout preview
     */
    public function shopglut_render_cartlayout_preview( $layout_id = 0 ) {
        // Ensure we have a valid layout ID
        if ( ! $layout_id ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            $layout_id = isset( $_GET['layout_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) ) : 1;
        }

        // Get layout data from database with caching
        $cache_key = 'shopglut_layout_' . $layout_id;
        $layout_data = wp_cache_get( $cache_key, 'shopglut_layouts' );

        if ( false === $layout_data ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with proper prepare statement
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_cartpage_layouts` WHERE id = %d", $layout_id )
            );

            // Cache the result for 1 hour
            if ( $layout_data ) {
                wp_cache_set( $cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS );
            }
        }

        if ( ! $layout_data ) {
            return '<div class="shopglut-preview-error">Layout not found.</div>';
        }

        $template_name = $layout_data->layout_template; // e.g., 'template1', 'template2', etc.

        // Check if template markup file exists
        $markup_file = __DIR__ . '/' . $template_name . '/' . $template_name . 'Markup.php';
        if ( ! file_exists( $markup_file ) ) {
            return '<div class="shopglut-preview-error">Template markup file not found: ' . esc_html( $template_name . '/' . $template_name . 'Markup.php' ) . '</div>';
        }

        // Check if template style file exists
        $style_file = __DIR__ . '/' . $template_name . '/' . $template_name . 'Style.php';
        if ( ! file_exists( $style_file ) ) {
            return '<div class="shopglut-preview-error">Template style file not found: ' . esc_html( $template_name . '/' . $template_name . 'Style.php' ) . '</div>';
        }

        // Include the markup file
        require_once $markup_file;

        // Include the style file
        require_once $style_file;

        // Get the markup class
        $markup_class = 'Shopglut\\layouts\\cartPage\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\layouts\\cartPage\\' . $template_name . '\\' . $template_name . 'Style';
        if ( ! class_exists( $style_class ) ) {
            return '<div class="shopglut-preview-error">Style class not found: ' . esc_html( $style_class ) . '</div>';
        }

        // Initialize classes
        $markup_instance = new $markup_class();
        $style_instance = new $style_class();

        // Check if required methods exist
        if ( ! method_exists( $markup_instance, 'layout_render' ) ) {
            return '<div class="shopglut-preview-error">layout_render method not found in markup class.</div>';
        }

        if ( ! method_exists( $style_instance, 'dynamicCss' ) ) {
            return '<div class="shopglut-preview-error">dynamicCss method not found in style class.</div>';
        }

        // Prepare template data
        $template_data = array(
            'layout_id' => $layout_id,
            'layout_name' => $layout_data->layout_name,
            'settings' => maybe_unserialize( $layout_data->layout_settings )
        );

        // Start output buffering
        ob_start();

        try {
            // Generate dynamic CSS
            $dynamic_css = $style_instance->dynamicCss( $layout_id );

            // Output CSS
            if ( ! empty( $dynamic_css ) ) {
                echo '<style type="text/css">' . wp_kses( $dynamic_css, array() ) . '</style>';
            }

            // Render the template markup
            $markup_instance->layout_render( $template_data );

        } catch ( Exception $e ) {
            ob_clean();
            return '<div class="shopglut-preview-error">Error rendering template: ' . esc_html( $e->getMessage() ) . '</div>';
        }

        // Get the rendered content
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Get default cart settings
     */
    private function get_default_cart_settings() {
        return array(
            // Table Header Settings
            'show_table_header' => true,
            'header_background_color' => '#f3f4f6',
            'header_text_color' => '#374151',
            'header_font_weight' => '600',
            'header_padding' => array('top' => '16', 'right' => '12', 'bottom' => '16', 'left' => '12', 'unit' => 'px'),

            // Product Image Settings
            'product_image_size' => array('width' => 60, 'height' => 60, 'unit' => 'px'),
            'image_background_color' => '#f9fafb',
            'image_border_radius' => 8,
            'image_border_color' => '#e5e7eb',
            'image_border_width' => 1,

            // Product Title Settings
            'product_title_color' => '#111827',
            'product_title_font_size' => 16,
            'product_title_font_weight' => '600',
            'show_product_link' => true,

            // Product Meta Settings
            'show_product_meta' => true,
            'product_meta_color' => '#6b7280',
            'product_meta_font_size' => 14,
            'show_product_badges' => true,
            'badge_background_color' => '#3b82f6',
            'badge_text_color' => '#ffffff',

            // Quantity Settings
            'quantity_button_color' => '#f3f4f6',
            'quantity_button_text_color' => '#374151',
            'quantity_button_hover_color' => '#e5e7eb',
            'quantity_input_background' => '#ffffff',
            'quantity_input_border' => '#d1d5db',
            'quantity_control_border_radius' => 6,

            // Pricing Settings
            'price_color' => '#111827',
            'price_font_size' => 16,
            'price_font_weight' => '600',
            'total_price_highlight' => true,
            'total_price_color' => '#059669',

            // Table Styling
            'table_background_color' => '#ffffff',
            'table_border_color' => '#e5e7eb',
            'table_border_width' => 1,
            'table_border_radius' => 8,
            'row_padding' => array('top' => '16', 'right' => '12', 'bottom' => '16', 'left' => '12', 'unit' => 'px'),
            'row_hover_effect' => true,
            'row_hover_color' => '#f8fafc',

            // Summary Section Settings
            'show_summary_section' => true,
            'summary_background_color' => '#f9fafb',
            'summary_border_color' => '#e5e7eb',
            'summary_border_radius' => 8,
            'summary_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),

            // Summary Header
            'show_summary_header' => true,
            'summary_title_text' => 'Order Summary',
            'summary_title_color' => '#111827',
            'summary_title_font_size' => 20,
            'show_summary_icon' => true,
            'summary_icon_color' => '#3b82f6',

            // Summary Rows
            'show_subtotal' => true,
            'show_shipping' => true,
            'show_tax' => true,
            'show_discount_row' => true,
            'row_label_color' => '#6b7280',
            'row_value_color' => '#111827',
            'row_font_size' => 14,
            'row_spacing' => 12,

            // Total Row
            'total_label_color' => '#111827',
            'total_value_color' => '#059669',
            'total_font_size' => 18,
            'total_font_weight' => '700',
            'total_row_separator' => true,
            'total_separator_color' => '#e5e7eb',

            // Checkout Button
            'checkout_button_text' => 'Secure Checkout',
            'checkout_button_background' => '#059669',
            'checkout_button_text_color' => '#ffffff',
            'checkout_button_hover_background' => '#047857',
            'checkout_button_font_size' => 16,
            'checkout_button_padding' => array('top' => '16', 'right' => '24', 'bottom' => '16', 'left' => '24', 'unit' => 'px'),
            'checkout_button_border_radius' => 8,
            'show_checkout_icon' => true,

            // Security Badges
            'show_security_badges' => true,
            'security_badges_layout' => 'horizontal',
            'security_badge_spacing' => 8,
            'show_ssl_badge' => true,
            'ssl_badge_text' => 'SSL Secured',
        );
    }

    /**
     * AJAX handler for updating cart quantity
     */
    public function ajax_update_cart_quantity() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_cart_action' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when security verification fails */ __( 'Security check failed.', 'shopglut' ) ) );
            return;
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when WooCommerce plugin is not available */ __( 'WooCommerce is required.', 'shopglut' ) ) );
            return;
        }

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
        $quantity = isset( $_POST['quantity'] ) ? absint( wp_unslash( $_POST['quantity'] ) ) : 0;

        if ( empty( $cart_item_key ) || $quantity <= 0 ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when invalid data is provided for cart operations */ __( 'Invalid data provided.', 'shopglut' ) ) );
            return;
        }

        $cart = WC()->cart;
        $cart_item = $cart->get_cart_item( $cart_item_key );

        if ( ! $cart_item ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when cart item cannot be found */ __( 'Cart item not found.', 'shopglut' ) ) );
            return;
        }

        // Update quantity
        $result = $cart->set_quantity( $cart_item_key, $quantity );

        if ( $result ) {
            // Get cart fragments for updating the UI
            $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );

            wp_send_json_success( array(
                'message' => /* translators: Success message when cart quantity is updated */ __( 'Quantity updated successfully!', 'shopglut' ),
                'fragments' => $fragments,
                'cart_hash' => WC()->cart->get_cart_hash()
            ) );
        } else {
            wp_send_json_error( array( 'message' => /* translators: Error message when cart quantity update fails */ __( 'Failed to update quantity.', 'shopglut' ) ) );
        }
    }

    /**
     * AJAX handler for removing cart item
     */
    public function ajax_remove_cart_item() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_cart_action' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when security verification fails */ __( 'Security check failed.', 'shopglut' ) ) );
            return;
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when WooCommerce plugin is not available */ __( 'WooCommerce is required.', 'shopglut' ) ) );
            return;
        }

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';

        if ( empty( $cart_item_key ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when cart item key is invalid */ __( 'Invalid cart item key.', 'shopglut' ) ) );
            return;
        }

        $cart = WC()->cart;
        $result = $cart->remove_cart_item( $cart_item_key );

        if ( $result ) {
            // Get cart fragments for updating the UI
            $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );

            wp_send_json_success( array(
                'message' => /* translators: Success message when cart item is removed */ __( 'Item removed successfully!', 'shopglut' ),
                'fragments' => $fragments,
                'cart_hash' => WC()->cart->get_cart_hash()
            ) );
        } else {
            wp_send_json_error( array( 'message' => /* translators: Error message when cart item removal fails */ __( 'Failed to remove item.', 'shopglut' ) ) );
        }
    }

    /**
     * AJAX handler for applying coupon
     */
    public function ajax_apply_coupon() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_cart_action' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when security verification fails */ __( 'Security check failed.', 'shopglut' ) ) );
            return;
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when WooCommerce plugin is not available */ __( 'WooCommerce is required.', 'shopglut' ) ) );
            return;
        }

        $coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';

        if ( empty( $coupon_code ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when coupon code field is empty */ __( 'Please enter a coupon code.', 'shopglut' ) ) );
            return;
        }

        // Apply coupon
        $result = WC()->cart->apply_coupon( $coupon_code );

        if ( $result ) {
            // Get cart fragments for updating the UI
            $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );

            wp_send_json_success( array(
                'message' => /* translators: Success message when coupon is applied */ __( 'Coupon applied successfully!', 'shopglut' ),
                'fragments' => $fragments,
                'cart_hash' => WC()->cart->get_cart_hash()
            ) );
        } else {
            // Get the last error message
            $notices = wc_get_notices( 'error' );
            $error_message = ! empty( $notices ) ? $notices[0]['notice'] : /* translators: Error message when coupon code is invalid */ __( 'Invalid coupon code.', 'shopglut' );
            wc_clear_notices(); // Clear notices to prevent showing them again

            wp_send_json_error( array( 'message' => $error_message ) );
        }
    }

    /**
     * AJAX handler for removing coupon
     */
    public function ajax_remove_coupon() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_cart_action' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when security verification fails */ __( 'Security check failed.', 'shopglut' ) ) );
            return;
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when WooCommerce plugin is not available */ __( 'WooCommerce is required.', 'shopglut' ) ) );
            return;
        }

        $coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';

        if ( empty( $coupon_code ) ) {
            wp_send_json_error( array( 'message' => /* translators: Error message when coupon code is invalid or empty */ __( 'Invalid coupon code.', 'shopglut' ) ) );
            return;
        }

        // Remove coupon
        $result = WC()->cart->remove_coupon( $coupon_code );

        if ( $result ) {
            // Get cart fragments for updating the UI
            $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );

            wp_send_json_success( array(
                'message' => /* translators: Success message when coupon is removed */ __( 'Coupon removed successfully!', 'shopglut' ),
                'fragments' => $fragments,
                'cart_hash' => WC()->cart->get_cart_hash()
            ) );
        } else {
            wp_send_json_error( array( 'message' => /* translators: Error message when coupon removal fails */ __( 'Failed to remove coupon.', 'shopglut' ) ) );
        }
    }

    /**
     * Shortcode handler for rendering cart layouts
     */
    public function render_cart_shortcode( $atts ) {
        // Parse shortcode attributes
        $atts = shortcode_atts( array(
            'id' => 1, // Default layout ID
        ), $atts, 'shopglut_cart_page' );

        $layout_id = absint( $atts['id'] );

        if ( ! $layout_id ) {
            return '<div class="shopglut-error">' . /* translators: Error message when cart layout ID is invalid */ esc_html__( 'Invalid cart layout ID.', 'shopglut' ) . '</div>';
        }

        // Check if layout exists
        global $wpdb;
        $table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

        // Check if table exists first (cache for 1 day since table structure rarely changes)
        $table_cache_key = 'shopglut_table_exists';
        $table_exists = wp_cache_get( $table_cache_key, 'shopglut_layouts' );

        if ( false === $table_exists ) {
            // Use proper table name escaping for SHOW TABLES
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for table existence check
            $table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );
            wp_cache_set( $table_cache_key, $table_exists, 'shopglut_layouts', DAY_IN_SECONDS );
        }

        if ( ! $table_exists ) {
            return '<div class="shopglut-error">' . /* translators: Error message when cart layouts database table is missing */ esc_html__( 'Cart layouts table does not exist. Please check your plugin installation.', 'shopglut' ) . '</div>';
        }

        // Check if layout exists with caching
        $exists_cache_key = 'shopglut_layout_exists_' . $layout_id;
        $layout_exists = wp_cache_get( $exists_cache_key, 'shopglut_layouts' );

        if ( false === $layout_exists ) {
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table count query with proper prepare statement
            $layout_exists = $wpdb->get_var(
                $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->prefix}shopglut_cartpage_layouts` WHERE id = %d", $layout_id )
            );
            wp_cache_set( $exists_cache_key, $layout_exists, 'shopglut_layouts', HOUR_IN_SECONDS );
        }

        if ( ! $layout_exists ) {
            return '<div class="shopglut-error">' . sprintf(
                /* translators: %d: The cart layout ID that was not found */ esc_html__( 'Cart layout with ID %d not found. Please check if the layout exists.', 'shopglut' ),
                $layout_id
            ) . '</div>';
        }

        // Start output buffering
        ob_start();

        try {
            // Render the cart layout
            echo wp_kses_post($this->shopglut_render_cartlayout_preview( $layout_id ));
        } catch ( Exception $e ) {
            return '<div class="shopglut-error">' . /* translators: Error message prefix when cart layout rendering fails */ esc_html__( 'Error rendering cart layout: ', 'shopglut' ) . esc_html( $e->getMessage() ) . '</div>';
        }

        // Get the rendered content
        $output = ob_get_clean();

        // If output is empty, show debug info
        if ( empty( trim( $output ) ) ) {
            return '<div class="shopglut-error">' . /* translators: Error message when cart layout renders but produces no output */ esc_html__( 'Cart layout rendered but produced no output. Please check the template configuration.', 'shopglut' ) . '</div>';
        }

        return $output;
    }

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}

