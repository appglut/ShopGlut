<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for orderCompletePage
 */

class orderCompletePageAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'orderCompletePage-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }
        
        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'orderCompletePage-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on order complete layout editor page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_ordercomplete_editor = isset($_GET['editor']) && $_GET['editor'] === 'ordercomplete';

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'orderCompletePage-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'orderCompletePage-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }

        // Enqueue layout data converter on editor page
        if ($is_ordercomplete_editor && file_exists(__DIR__ . '/assets/js/ordercomplete-layout-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-ordercomplete-layout-data-converter',
                $plugin_url . 'assets/js/ordercomplete-layout-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/ordercomplete-layout-data-converter.js'),
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-ordercomplete-layout-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }
    }
}

// Initialize the assets class
new orderCompletePageAssets();
