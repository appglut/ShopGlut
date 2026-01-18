<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for wishlist
 */

class WishlistAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Main wishlist CSS
        if (file_exists(__DIR__ . '/assets/wishlist-style.css')) {
            wp_enqueue_style(
                'shopglut-wishlist-style',
                $plugin_url . 'assets/wishlist-style.css',
                [],
                filemtime(__DIR__ . '/assets/wishlist-style.css')
            );
        }
        
        // RTL support
        if (file_exists(__DIR__ . '/assets/wishlist-rtl.css')) {
            wp_enqueue_style(
                'shopglut-wishlist-rtl',
                $plugin_url . 'assets/wishlist-rtl.css',
                ['shopglut-wishlist-style'],
                filemtime(__DIR__ . '/assets/wishlist-rtl.css')
            );
        }
        
        // Main wishlist JS
        if (file_exists(__DIR__ . '/assets/wishlist.js')) {
            wp_enqueue_script(
                'shopglut-wishlist-js',
                $plugin_url . 'assets/wishlist.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/wishlist.js'),
                true
            );
            
            // Localize script with AJAX data
            $this->localize_wishlist_script();
        }
        
        // Wishlist control JS
        if (file_exists(__DIR__ . '/assets/wishlist-control.js')) {
            wp_enqueue_script(
                'shopglut-wishlist-control-js',
                $plugin_url . 'assets/wishlist-control.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/wishlist-control.js'),
                true
            );
        }
        
        // Wishlist counter JS
        if (file_exists(__DIR__ . '/assets/wishlist-counter.js')) {
            wp_enqueue_script(
                'shopglut-wishlist-counter-js',
                $plugin_url . 'assets/wishlist-counter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/wishlist-counter.js'),
                true
            );
            
            // Localize counter script as well (in case it gets uncommented)
            wp_localize_script('shopglut-wishlist-counter-js', 'shopglut_wishlist_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_wishlist_nonce'),
            ));
        }
        
        // Wishlist QR JS
        if (file_exists(__DIR__ . '/assets/wishlist-qr.js')) {
            wp_enqueue_script(
                'shopglut-wishlist-qr-js',
                $plugin_url . 'assets/wishlist-qr.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/wishlist-qr.js'),
                true
            );
        }
        
        // Add wishlist dynamic styles
        $this->add_dynamic_styles();
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Admin CSS
        if (file_exists(__DIR__ . '/assets/wishlist-admin.css')) {
            wp_enqueue_style(
                'shopglut-wishlist-admin',
                $plugin_url . 'assets/wishlist-admin.css',
                [],
                filemtime(__DIR__ . '/assets/wishlist-admin.css')
            );
        }
        
        // Admin JS
        if (file_exists(__DIR__ . '/assets/wishlist-admin.js')) {
            wp_enqueue_script(
                'shopglut-wishlist-admin-js',
                $plugin_url . 'assets/wishlist-admin.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/wishlist-admin.js'),
                true
            );
        }
    }
    
    /**
     * Add dynamic wishlist styles
     */
    private function add_dynamic_styles() {
        if (class_exists('\Shopglut\enhancements\wishlist\dynamicStyle')) {
            $wishlist_dynamic_style = new \Shopglut\enhancements\wishlist\dynamicStyle();
            $wishlist_dynamic_css = $wishlist_dynamic_style->dynamicCss();
            if (!empty($wishlist_dynamic_css)) {
                wp_add_inline_style('shopglut-wishlist-style', $wishlist_dynamic_css);
            }
        }
    }
    
    /**
     * Localize wishlist script with AJAX data
     */
    private function localize_wishlist_script() {
        $wishlist_options = get_option('agshopglut_wishlist_options', array());
        
        wp_localize_script('shopglut-wishlist-js', 'ajax_data', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_wishlist_nonce'),
            'post_type' => get_post_type(),
            'should_merge_wishlist' => is_user_logged_in(),
            'notification_type' => $wishlist_options['wishlist-general-notification'] ?? 'notification-off',
            'notification_position' => $wishlist_options['wishlist-side-notification-appear'] ?? 'bottom-right',
            'side_notification_effect' => $wishlist_options['wishlist-side-notification-effect'] ?? 'fade-in-out',
            'popup_notification_effect' => $wishlist_options['wishlist-popup-notification-effect'] ?? 'fade-in-out',
        ));
    }
}

// Initialize the assets class
new WishlistAssets();
