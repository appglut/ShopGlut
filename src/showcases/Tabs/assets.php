<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for product_tab
 */

class product_tabAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'product_tab-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }
        
        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/tab-frontend.js')) {
            wp_enqueue_script(
                'product_tab-script',
                $plugin_url . 'assets/tab-frontend.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/tab-frontend.js'),
                true
            );

            // Localize script with AJAX URL and other data
            wp_localize_script('product_tab-script', 'shopglutTabData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_tab_nonce'),
                'loadingText' => __('Loading...', 'shopglut'),
                'closeText' => __('Close', 'shopglut')
            ));
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on order complete enhancement editor page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_product_tab_editor = isset($_GET['editor']) && $_GET['editor'] === 'tab';

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'product_tab-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'product_tab-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }

        // Enqueue enhancement data converter on editor page
        if ($is_product_tab_editor && file_exists(__DIR__ . '/assets/js/product_tab-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-product_tab-enhancement-data-converter',
                $plugin_url . 'assets/js/product_tab-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/product_tab-data-converter.js'),
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-product_tab-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }
    }
}

// Initialize the assets class
new product_tabAssets();
