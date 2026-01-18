<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for Product Custom Field
 */

class ProductCustomFieldAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);

        // Load on all pages to catch product pages
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'shopglut-product-custom-field-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }

        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'shopglut-product-custom-field-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );

            // Localize script with necessary variables
            wp_localize_script('shopglut-product-custom-field-script', 'shopglut_custom_field_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_custom_field_nonce'),
                'post_id' => get_the_ID(),
                'required_message' => __('Please select an option.', 'shopglut'),
                'auto_save' => false // Can be enabled in future
            ));
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on product custom field editor page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_product_custom_field_editor = isset($_GET['editor']) && $_GET['editor'] === 'product_custom_field';

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'accountPage-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'accountPage-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }

        // Enqueue layout data converter on editor page
        if ($is_product_custom_field_editor && file_exists(__DIR__ . '/assets/js/product-custom-field-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-product-custom-field-data-converter',
                $plugin_url . 'assets/js/product-custom-field-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/product-custom-field-data-converter.js'),
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-product-custom-field-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }
    }
}

// Initialize the assets class
new ProductCustomFieldAssets();
