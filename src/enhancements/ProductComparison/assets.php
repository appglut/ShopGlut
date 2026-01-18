<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for product_comparison
 */

class product_comparisonAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'product_comparison-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }

        // Enqueue comparison frontend JS
        if (file_exists(__DIR__ . '/assets/js/comparison-frontend.js')) {
            wp_enqueue_script(
                'shopglut-comparison-frontend',
                $plugin_url . 'assets/js/comparison-frontend.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/comparison-frontend.js'),
                true
            );

            // Localize script with AJAX URL
            wp_localize_script('shopglut-comparison-frontend', 'shopglutComparisonData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_comparison_nonce'),
                'loadingGif' => plugin_dir_url(dirname(dirname(dirname(__FILE__)))) . 'global-assets/images/loading-icon.png',
                'cartUrl' => wc_get_cart_url()
            ));
        }

        // Enqueue legacy JS if exists
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'product_comparison-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on order complete enhancement editor page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_product_comparison_editor = isset($_GET['editor']) && $_GET['editor'] === 'product_comparison';

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'product_comparison-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'product_comparison-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }

        // Enqueue enhancement data converter on editor page
        if ($is_product_comparison_editor && file_exists(__DIR__ . '/assets/js/product_comparison-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-product_comparison-enhancement-data-converter',
                $plugin_url . 'assets/js/product_comparison-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/product_comparison-data-converter.js'),
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-product_comparison-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }
    }
}

// Initialize the assets class
new product_comparisonAssets();
