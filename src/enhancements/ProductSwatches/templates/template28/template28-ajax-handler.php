<?php
/**
 * Template1 Variable Product AJAX Handler
 *
 * Handles AJAX requests for variable product add to cart functionality
 * specific to Template1 layout
 */

if (!defined('ABSPATH')) {
    exit;
}

class Template28_Variable_Product_Handler {

    public static function init() {
        // Register AJAX handlers only when Template1 is active
        add_action('wp_ajax_shopglut_template1_add_variable_to_cart', array(__CLASS__, 'handle_variable_add_to_cart'));
        add_action('wp_ajax_nopriv_shopglut_template1_add_variable_to_cart', array(__CLASS__, 'handle_variable_add_to_cart'));

        // Enable WooCommerce AJAX for single product pages when using Template1
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
    }

    public static function enqueue_scripts() {
        if (is_product()) {
            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_script('woocommerce');

            // Add body class to enable AJAX
            add_filter('body_class', function($classes) {
                $classes[] = 'woocommerce-js';
                return $classes;
            });
        }
    }

    /**
     * AJAX handler for variable product add to cart
     */
    public static function handle_variable_add_to_cart() {
        // Check if this is a duplicate call
        static $call_count = 0;
        $call_count++;

        // If this is a duplicate call within the same request, exit
        if ($call_count > 1) {
            wp_send_json_error(array('error' => 'Duplicate call prevented'));
        }

        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_frontend_nonce')) {
            wp_send_json_error(array('error' => 'Security check failed'));
        }

        // Check if required data is present
        if (!isset($_POST['add-to-cart']) || !isset($_POST['variation_id'])) {
            wp_send_json_error(array('error' => 'Missing required data'));
        }

        // Get data from request
        $product_id = absint($_POST['add-to-cart']);
        $variation_id = absint($_POST['variation_id']);
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

        // Validate data
        if (!$product_id || !$variation_id) {
            wp_send_json_error(array('error' => 'Invalid product or variation ID'));
        }

        // Get variation attributes
        $variation = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                $variation[$key] = sanitize_text_field($value);
            }
        }

        try {
            // Ensure WooCommerce is loaded and initialized
            if (!class_exists('WooCommerce')) {
                wp_send_json_error(array('error' => 'WooCommerce class not found'));
            }

            if (!function_exists('WC')) {
                wp_send_json_error(array('error' => 'WC function not available'));
            }

            // Check if WooCommerce is properly initialized
            if (!WC()->cart) {
                // Initialize WooCommerce cart if not available
                WC()->frontend_includes();
                WC()->session = new WC_Session_Handler();
                WC()->session->init();
                WC()->cart = new WC_Cart();
                WC()->customer = new WC_Customer();
            }

            // Check if this exact variation is already in cart
            $cart_id = WC()->cart->generate_cart_id($product_id, $variation_id, $variation);
            $existing_cart_item_key = WC()->cart->find_product_in_cart($cart_id);
            $existing_quantity = 0;

            if ($existing_cart_item_key) {
                $existing_quantity = WC()->cart->cart_contents[$existing_cart_item_key]['quantity'];
            }

            // Simple standard WooCommerce add to cart - NO custom logic
            $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

            if ($cart_item_key) {
                // Get the final quantity in cart for this specific item
                $final_quantity = WC()->cart->cart_contents[$cart_item_key]['quantity'];
                $was_merged = ($existing_quantity > 0);

                // Get cart fragments
                if (class_exists('WC_AJAX')) {
                    WC_AJAX::get_refreshed_fragments();
                } else {
                    // Fallback with enhanced success message
                    $message = $was_merged ?
                        sprintf('Product quantity updated to %d in cart', $final_quantity) :
                        sprintf('Product added to cart (quantity: %d)', $final_quantity);

                    wp_send_json_success(array(
                        'message' => $message,
                        'cart_count' => WC()->cart->get_cart_contents_count(),
                        'item_quantity' => $final_quantity,
                        'was_merged' => $was_merged
                    ));
                }
            } else {
                wp_send_json_error(array('error' => 'Failed to add product to cart'));
            }
        } catch (Exception $e) {
            wp_send_json_error(array('error' => $e->getMessage()));
        }
    }
}

// Initialize the handler
Template28_Variable_Product_Handler::init();