<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Asset registration for ProductBadges
 */

class Shopglut_ProductBadgesAssets {
    
    public function __construct() {
      add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
     public function enqueue_frontend_assets() {
        // Only load on WooCommerce pages
        if (!is_woocommerce() && !is_shop() && !is_product_category() && !is_product()) {
            return;
        }

        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'product_badge-style',
                $plugin_url . 'assets/style.css',
                [],
                '1.0.0'
            );
        }

        // Enqueue comparison frontend JS
        if (file_exists(__DIR__ . '/assets/js/comparison-frontend.js')) {
            wp_enqueue_script(
                'shopglut-comparison-frontend',
                $plugin_url . 'assets/js/comparison-frontend.js',
                ['jquery'],
                '1.0.0',
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
                'product_badge-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                '1.0.0',
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on product badge related pages
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_product_badge_editor = isset($_GET['editor']) && $_GET['editor'] === 'product_badges';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_product_badge_page = isset($_GET['view']) && $_GET['view'] === 'product_badges';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_enhancement_page = isset($_GET['page']) && $_GET['page'] === 'shopglut_enhancements';

        // Enqueue admin CSS on product badge related pages
        if (($is_product_badge_editor || $is_product_badge_page || $is_enhancement_page) && file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'product_badge-admin-style',
                $plugin_url . 'assets/style.css',
                [],
                '1.0.0'
            );
        }

        // Enqueue admin JS on product badge related pages
        if (($is_product_badge_editor || $is_product_badge_page || $is_enhancement_page) && file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'product_badge-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                '1.0.0',
                true
            );
        }

        // Enqueue badges admin JS
        if (($is_product_badge_editor || $is_product_badge_page || $is_enhancement_page) && file_exists(__DIR__ . '/assets/badges-admin.js')) {
            wp_enqueue_script(
                'shopglut-badges-admin',
                $plugin_url . 'assets/badges-admin.js',
                ['jquery'],
                '1.0.0',
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-badges-admin', 'shopglut_badges', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_badges_nonce')
            ));
        }

        // Enqueue enhancement data converter on editor page
        if ($is_product_badge_editor && file_exists(__DIR__ . '/assets/js/product_badge-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-product_badge-enhancement-data-converter',
                $plugin_url . 'assets/js/product_badge-data-converter.js',
                ['jquery'],
                '1.0.0',
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-product_badge-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }
    }
}

// Initialize the assets class
new Shopglut_ProductBadgesAssets();
