<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for ProductSwatches
 */

class ProductSwatchesAssets {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // Initialize FrontendRenderer on frontend - hooks into WooCommerce
        add_action('wp', [$this, 'init_frontend_renderer']);
    }

    /**
     * Initialize FrontendRenderer for WooCommerce attribute override
     * This must run early to hook into woocommerce_dropdown_variation_attribute_options_html
     */
    public function init_frontend_renderer() {
        // Only on frontend
        if (is_admin()) {
            return;
        }

        // Only on product pages
        if (!is_product()) {
            return;
        }

        // Load and initialize FrontendRenderer
        require_once __DIR__ . '/FrontendRenderer.php';
        \Shopglut\enhancements\ProductSwatches\FrontendRenderer::get_instance();
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'ProductSwatches-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }
        
        // Main product swatches layout JS
        if (file_exists(__DIR__ . '/assets/productSwatchesLayouts.js')) {
            wp_enqueue_script(
                'shopglut-product-swatches',
                $plugin_url . 'assets/productSwatchesLayouts.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/productSwatchesLayouts.js'),
                true
            );

            // Localize script with secure layout_id and nonce
            $this->localize_single_product_script();
        }
        
        // Generic script
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'ProductSwatches-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue WordPress color picker styles and scripts
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Enqueue admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'ProductSwatches-admin-style',
                $plugin_url . 'assets/admin-style.css',
                ['wp-color-picker'],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }

        // Enqueue admin JS with color picker dependency
        if (file_exists(__DIR__ . '/assets/productSwatchesLayoutss.js')) {
            wp_enqueue_script(
                'shopglut-product-swatches-admin',
                $plugin_url . 'assets/productSwatchesLayouts.js',
                ['jquery', 'wp-color-picker'],
                filemtime(__DIR__ . '/assets/productSwatchesLayouts.js'),
                true
            );

            // Localize script for admin as well
            $this->localize_admin_script();
        }
    }
    
    /**
     * Localize single product script with secure data
     */
    private function localize_single_product_script() {
        // Get secure layout_id (simplified version for module)
        $layout_id = 0;

        if (is_admin() && current_user_can('manage_options')) {
            // Try multiple sources for layout_id
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            if (isset($_GET['layout_id'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_GET['layout_id'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_GET['post'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_GET['post'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_POST['layout_id'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_POST['layout_id'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_POST['post'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_POST['post'])));
            }
        }

        // Create nonce for AJAX calls
        $nonce = wp_create_nonce('shopglut_ajax_nonce');

        wp_localize_script('shopglut-product-swatches', 'shopglut_product_swatches_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'layout_id' => $layout_id,
            'nonce' => $nonce,
        ]);
    }

    /**
     * Localize admin script with secure data
     */
    private function localize_admin_script() {
        // Get secure layout_id (simplified version for module)
        $layout_id = 0;

        if (is_admin() && current_user_can('manage_options')) {
            // Try multiple sources for layout_id
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            if (isset($_GET['layout_id'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_GET['layout_id'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_GET['post'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_GET['post'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_POST['layout_id'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_POST['layout_id'])));
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
            elseif (isset($_POST['post'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin parameter with capability check
                $layout_id = absint(sanitize_text_field(wp_unslash($_POST['post'])));
            }
        }

        // Create nonce for AJAX calls
        $nonce = wp_create_nonce('shopglut_ajax_nonce');

        wp_localize_script('shopglut-product-swatches-admin', 'shopglut_product_swatches_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'layout_id' => $layout_id,
            'nonce' => $nonce,
        ]);
    }

    /**
     * Enqueue shared tabs script for all templates
     * This universal tabs handler works in modals, AJAX content, and regular pages
     */
    public function enqueue_shared_tabs_script() {
        // Path to shared tabs script in ProductSwatches assets
        $tabs_script_path = __DIR__ . '/assets/ProductSwatches-tabs.js';
        $tabs_script_url = plugin_dir_url(__FILE__) . 'assets/ProductSwatches-tabs.js';

        if (file_exists($tabs_script_path)) {
            wp_enqueue_script(
                'shopglut-tabs',
                $tabs_script_url,
                [],
                filemtime($tabs_script_path),
                true // Load in footer
            );
        }
    }
}

// Initialize the assets class
new ProductSwatchesAssets();
