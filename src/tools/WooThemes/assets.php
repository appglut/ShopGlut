<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for WooThemes
 */

class WooThemesAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'WooThemes-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }
        
        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'WooThemes-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Load theme admin CSS for woo themes pages
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS loading
        if (is_admin() && isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'shopglut_tools' &&
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin view parameter check for CSS loading
            isset($_GET['view']) && sanitize_text_field(wp_unslash($_GET['view'])) === 'woo_themes') {
            if (file_exists(__DIR__ . '/assets/woo-themes-admin.css')) {
                wp_enqueue_style(
                    'shopglut-woo-themes-admin',
                    $plugin_url . 'assets/woo-themes-admin.css',
                    [],
                    filemtime(__DIR__ . '/assets/woo-themes-admin.css')
                );
            }
        }
        
        // Enqueue admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'WooThemes-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }
    }
}

// Initialize the assets class
new WooThemesAssets();
