<?php
namespace Shopglut\enhancements\Filters;

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Asset registration for ShopGlut Filters
 *
 * Handles loading of all JavaScript and CSS files for the filter system
 */


class FiltersAssets {

    public function __construct() {
        // Always load frontend assets to ensure filters work
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    /**
     * Enqueue frontend assets (styles and scripts)
     */
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue main frontend CSS
        $this->enqueue_frontend_styles($plugin_url);

            wp_enqueue_script(
                'shopglut-filters',
                $plugin_url . 'assets/js/filter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/filter.js'),
                true
            );

            // Enqueue filter actions script for frontend functionality
            if (file_exists(__DIR__ . '/assets/js/filter-actions.js')) {
                wp_enqueue_script(
                    'shopglut-filter-actions',
                    $plugin_url . 'assets/js/filter-actions.js',
                    ['jquery'],
                    filemtime(__DIR__ . '/assets/js/filter-actions.js'),
                    true
                );

                // Localize script for AJAX functionality
                wp_localize_script('shopglut-filter-actions', 'shopglut_filter_actions', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('shopglut_filter_nonce'),
                    'strings' => [
                        'loading' => __('Loading...', 'shopglut'),
                        'error' => __('An error occurred', 'shopglut'),
                        'no_products' => __('No products found', 'shopglut')
                    ]
                ]);
            }
        

    }

    /**
     * Enqueue frontend stylesheets
     */
    private function enqueue_frontend_styles($plugin_url) {

        // Fallback to legacy styles if unified doesn't exist
        if (file_exists(__DIR__ . '/assets/css/shopglut-filters.css')) {
            wp_enqueue_style(
                'shopglut-filters',
                $plugin_url . 'assets/css/shopglut-filters.css',
                [],
                filemtime(__DIR__ . '/assets/css/shopglut-filters.css')
            );
        }

        // Font Awesome for icons - only load if enabled and not already loaded
        $load_font_awesome = apply_filters('shopglut_filters_load_font_awesome', true);

        if ($load_font_awesome && !wp_style_is('font-awesome', 'registered') && !wp_style_is('font-awesome', 'enqueued')) {
            // Try to load a local copy only
            $local_fa_path = plugin_dir_path(dirname(__DIR__)) . 'assets/css/font-awesome.min.css';
            if (file_exists($local_fa_path)) {
                wp_enqueue_style(
                    'font-awesome',
                    plugin_dir_url(dirname(__DIR__)) . 'assets/css/font-awesome.min.css',
                    [],
                    '6.0.0'
                );
            }
            // Note: If no local copy exists, Font Awesome will not be loaded
            // Plugin functionality will remain intact but icons may not display
            // This ensures compliance with WordPress.org guidelines that prohibit external resources
        }
    }

    /**
     * Enqueue admin assets (styles and scripts)
     */
    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on filter-related admin pages
        if ($this->is_filter_admin_page($hook)) {
            // Enqueue admin styles
            $this->enqueue_admin_styles($plugin_url);

            // Enqueue admin scripts
            $this->enqueue_admin_scripts($plugin_url);
        }
    }

    /**
     * Check if current page is a filter admin page
     */
    private function is_filter_admin_page($hook) {
        return (
            strpos($hook, 'shopglut_enhancements') !== false ||
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for asset loading
            (isset($_GET['editor']) && $_GET['editor'] === 'filters') ||
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for asset loading
            (isset($_GET['page']) && $_GET['page'] === 'shopglut_enhancements') ||
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for asset loading
            (isset($_GET['view']) && $_GET['view'] === 'shop_filters')
        );
    }

    /**
     * Enqueue admin stylesheets
     */
    private function enqueue_admin_styles($plugin_url) {
        // Styles are now handled by FilterGlobalStyle and FilterSingleStyle classes
        // No need to load external CSS files
    }
    

    /**
     * Enqueue admin scripts
     */
    private function enqueue_admin_scripts($plugin_url) {
        // Initialize ShopGlut Filters admin object
        wp_add_inline_script('jquery', '
            if (typeof ShopGlutFilters === "undefined") {
                window.ShopGlutFilters = {
                    config: {
                        ajaxUrl: "' . admin_url('admin-ajax.php') . '",
                        nonce: "' . wp_create_nonce('shopFilters_nonce') . '"
                    },
                    modules: {}
                };
            }
        ');

        // Enqueue filter layout data converter
        if (file_exists(__DIR__ . '/assets/js/filter-layout-data-converter.js')) {
            wp_enqueue_script(
                'shopglut-filter-layout-converter',
                $plugin_url . 'assets/js/filter-layout-data-converter.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/filter-layout-data-converter.js'),
                true
            );
        }

        // Enqueue main admin filter script
        if (file_exists(__DIR__ . '/assets/js/filter.js')) {
            wp_enqueue_script(
                'shopglut-filters-admin',
                $plugin_url . 'assets/js/filter.js',
                ['jquery', 'shopglut-filter-layout-converter'],
                filemtime(__DIR__ . '/assets/js/filter.js'),
                true
            );

            // Localize admin script
            wp_localize_script('shopglut-filters-admin', 'ajax_data_filters', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopFilters_nonce'),
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter ID for script localization only, properly sanitized
                'filter_id' => isset($_GET['filter_id']) ? intval($_GET['filter_id']) : 0,
                'strings' => [
                    'saving' => __('Saving...', 'shopglut'),
                    'saved' => __('Settings saved successfully!', 'shopglut'),
                    'error' => __('Failed to save settings', 'shopglut'),
                    'confirmReset' => __('Are you sure you want to reset all settings to default? This action cannot be undone.', 'shopglut')
                ]
            ]);
        }

       
    }

    /**
     * Get plugin version for cache busting
     */
    private function get_plugin_version() {
        // Try to get from plugin header, fallback to timestamp
        if (function_exists('get_plugin_data')) {
            $plugin_data = get_plugin_data(__DIR__ . '/../shopglut.php');
            return $plugin_data['Version'] ?? time();
        }
        return time();
    }

    /**
     * Check if we're on a WooCommerce shop page
     */
    private function is_woocommerce_page() {
        return (
            function_exists('is_shop') && is_shop() ||
            function_exists('is_product_category') && is_product_category() ||
            function_exists('is_product_tag') && is_product_tag() ||
            function_exists('is_product_taxonomy') && is_product_taxonomy()
        );
    }

    /**
     * Conditionally load assets only when needed
     */
    public function maybe_enqueue_assets() {
        // Load assets on all pages where filters might appear
        // Less restrictive condition to ensure filters work
        global $post;

        $should_load = false;

        // Load on WooCommerce pages
        if ($this->is_woocommerce_page()) {
            $should_load = true;
        }

        // Load on pages that might have filters (check for filter shortcodes or blocks)
        if ($post && (has_shortcode($post->post_content, 'shopglut_filter') ||
                     strpos($post->post_content, 'shopglut-filter') !== false ||
                     is_active_widget(false, false, 'shopglut_filter_widget'))) {
            $should_load = true;
        }

        // Load if there are any ShopGlut filters created
        $filter_count = get_option('shopglut_filter_count', 0);
        if ($filter_count > 0) {
            $should_load = true;
        }

        if ($should_load) {
            $this->enqueue_frontend_assets();
        }
    }
}

// Initialize the assets class
new FiltersAssets();
