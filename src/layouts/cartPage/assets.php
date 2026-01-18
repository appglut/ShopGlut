<?php
if ( ! defined( 'ABSPATH' ) )  {
	exit;
}
/**
 * Asset registration for cartPage
 */

class CartPageAssets {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'cartPage-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }

        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'cartPage-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }

        // Enqueue Template1 Cart Functionality Assets
        $this->enqueue_template1_assets();

        // Add cart dynamic styles
        $this->add_dynamic_styles();
    }

    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'cartPage-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'cartPage-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }

        // Enqueue Template1 styles for admin preview
        $this->enqueue_template1_admin_assets();

        // Enqueue cart layout data converter
        if (file_exists(__DIR__ . '/assets/js/cart-layout-data-converter.js')) {
            wp_enqueue_script(
                'cart-layout-data-converter',
                $plugin_url . 'assets/js/cart-layout-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/cart-layout-data-converter.js'),
                true
            );

            // Localize script for cart layout data converter
            wp_localize_script(
                'cart-layout-data-converter',
                'shopglut_admin_vars',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('shopglut_admin_nonce'),
                )
            );
        }
    }

    /**
     * Enqueue Template1 cart functionality assets for frontend
     */
    private function enqueue_template1_assets() {
        $script_url = plugin_dir_url(__FILE__) . 'template1/assets/cart-functionality.js';
        $script_path = plugin_dir_path(__FILE__) . 'template1/assets/cart-functionality.js';
        $style_url = plugin_dir_url(__FILE__) . 'template1/assets/cart-styles.css';
        $style_path = plugin_dir_path(__FILE__) . 'template1/assets/cart-styles.css';

        // Enqueue CSS (both admin and frontend)
        if (file_exists($style_path)) {
            wp_enqueue_style(
                'shopglut-cart-template1-styles',
                $style_url,
                array(),
                filemtime($style_path)
            );
        }

        // Only enqueue JavaScript on frontend
        if (!is_admin() && file_exists($script_path)) {
            wp_enqueue_script(
                'shopglut-cart-template1',
                $script_url,
                array('jquery'),
                filemtime($script_path),
                true
            );

            // Localize script with AJAX data
            wp_localize_script('shopglut-cart-template1', 'shopglut_cart_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_cart_action'),
                'wc_ajax_url' => class_exists('WC_AJAX') ? \WC_AJAX::get_endpoint('%%endpoint%%') : '',
                'checkout_url' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '',
                'i18n' => array(
                    'updating' => __('Updating...', 'shopglut'),
                    'removing' => __('Removing...', 'shopglut'),
                    'error' => __('Something went wrong. Please try again.', 'shopglut'),
                    'coupon_applied' => __('Coupon applied successfully!', 'shopglut'),
                    'coupon_removed' => __('Coupon removed successfully!', 'shopglut'),
                    'invalid_coupon' => __('Invalid coupon code.', 'shopglut'),
                    'confirm_remove' => __('Are you sure you want to remove this item?', 'shopglut'),
                )
            ));
        }
    }

    /**
     * Enqueue Template1 styles for admin preview
     */
    private function enqueue_template1_admin_assets() {
        $style_url = plugin_dir_url(__FILE__) . 'template1/assets/cart-styles.css';
        $style_path = plugin_dir_path(__FILE__) . 'template1/assets/cart-styles.css';

        // Enqueue CSS for admin preview
        if (file_exists($style_path)) {
            wp_enqueue_style(
                'shopglut-cart-template1-admin-styles',
                $style_url,
                array(),
                filemtime($style_path)
            );
        }
    }

    /**
     * Add dynamic cart styles
     */
    private function add_dynamic_styles() {
        if (class_exists('\Shopglut\layouts\cartPage\dynamicStyle')) {
            // Get secure layout_id (simplified version for module)
            $layout_id = 0;
            if (is_admin() && current_user_can('manage_options') && isset($_GET['layout_id'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview with capability check
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_GET['layout_id'])));
            }

            $cart_dynamic_style = new \Shopglut\layouts\cartPage\dynamicStyle();
            $cart_dynamic_css = $cart_dynamic_style->dynamicCss($layout_id);
            if (!empty($cart_dynamic_css)) {
                wp_add_inline_style('shopglut-main', $cart_dynamic_css);
            }
        }
    }
}

// Initialize the assets class
new CartPageAssets();