<?php

namespace Shopglut\enhancements\Filters\implementation;


if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Simple Shop Page Template - Just Sidebar + Default WooCommerce
 */

class ShopPageTemplate {

    private static $sidebar_opened = false;

    public function __construct() {
        // Don't override the entire shop page, just add sidebar
        add_action('woocommerce_before_main_content', [$this, 'add_shop_sidebar'], 5);
        add_action('woocommerce_after_main_content', [$this, 'end_sidebar'], 5);
    }

    /**
     * Check if we have filters for shop page
     */
    private function get_shop_filters() {
        global $wpdb;

        // Check cache first
        $cache_key = 'shopglut_enhancement_filters';
        $filters = wp_cache_get($cache_key);

        if (false === $filters) {
            // Use escaped table name for backward compatibility - table name is safe from internal method
            $table_name = $wpdb->prefix . 'shopglut_enhancement_filters';
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom plugin table
            $filters = $wpdb->get_results( "SELECT * FROM `" . esc_sql($table_name) . "`" );

            // Cache for 30 minutes
            wp_cache_set($cache_key, $filters, '', 30 * MINUTE_IN_SECONDS);
        }

        $shop_filters = [];
        if ($filters) {
            foreach ($filters as $filter) {
                $settings = maybe_unserialize($filter->filter_settings);
                $show_on_pages = $settings['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-show-on-pages'] ?? [];

                if (in_array('Woo Shop Page', (array)$show_on_pages)) {
                    $shop_filters[] = [
                        'filter' => $filter,
                        'settings' => $settings
                    ];
                }
            }
        }

        return $shop_filters;
    }

    /**
     * Add shop sidebar with filters
     */
    public function add_shop_sidebar() {
        if (!is_shop() || self::$sidebar_opened) return;

        $shop_filters = $this->get_shop_filters();

        if (!empty($shop_filters)) {
            self::$sidebar_opened = true;
            echo '<div style="display: flex; gap: 30px; max-width: 1200px; margin: 20px auto;">';
            echo '<aside class="shop-sidebar">';

            // Show only the first shop filter
            $filter_data = $shop_filters[0];
            $shop_filter = new ShopPageFilter($filter_data['filter']->id, $filter_data['settings']);
            echo wp_kses_post($shop_filter->render());

            echo '</aside>';
            echo '<div class="shop-main">';
        }
    }

    /**
     * Close sidebar
     */
    public function end_sidebar() {
        if (!is_shop() || !self::$sidebar_opened) return;

        echo '</div>';
        echo '</div>';

        // Reset the static variable for future page loads
        self::$sidebar_opened = false;

        // Responsive CSS
        echo '<style>
        .shop-sidebar {
            flex: 0 0 250px;
            min-width: 250px;
        }
        .shop-main {
            flex: 1;
        }

        /* Hide breadcrumb and shop page title when filter sidebar is active */
        .woocommerce-breadcrumb,
        .woocommerce-products-header__title.page-title {
            display: none !important;
        }

        /* Remove primary container margin when filter sidebar is active */
        #primary {
            margin: 0 !important;
        }

        @media (max-width: 768px) {
            .shop-sidebar {
                margin-bottom: 20px;
                flex: 0 0 100%;
            }
        }
        </style>';
    }
}

// Initialize
new ShopPageTemplate();