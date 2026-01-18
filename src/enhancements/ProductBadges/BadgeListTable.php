<?php
namespace Shopglut\enhancements\ProductBadges;

if ( ! defined( 'ABSPATH' ) ) exit;

use Shopglut\ShopGlutDatabase;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BadgeListTable extends \WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'badge',
            'plural' => 'badges',
            'ajax' => false
        ));
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'layout_name' => __('Name', 'shopglut'),
            'layout_template' => __('Template', 'shopglut'),
            'display_badge_on' => __('Display Badge On', 'shopglut'),
            'status' => __('Status', 'shopglut')
        );
    }

    public function get_sortable_columns() {
        return array(
            'layout_name' => array('layout_name', false),
            'status' => array('status', false)
        );
    }

    public function get_bulk_actions() {
        return array(
            'bulk_delete' => __('Delete', 'shopglut')
        );
    }

    public function no_items() {
        esc_html_e('No badge layouts found.', 'shopglut');
    }

    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Handle search
        $search = isset($_POST['s']) ? sanitize_text_field( wp_unslash( $_POST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Handle sorting
        $allowed_orderby = ['id', 'layout_name', 'created_at', 'updated_at'];
        $orderby = isset($_GET['orderby']) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'layout_name'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $orderby = in_array($orderby, $allowed_orderby, true) ? $orderby : 'layout_name';

        $allowed_order = ['ASC', 'DESC'];
        $order = isset($_GET['order']) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'ASC'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $order = in_array(strtoupper($order), $allowed_order, true) ? strtoupper($order) : 'ASC';

        // Get badges from product badges table
        global $wpdb;
        $table_name = ShopGlutDatabase::table_product_badges();

        if (!empty($search)) {
            // Search badges with pagination
            $search_term = '%' . $wpdb->esc_like($search) . '%';

            // Create cache keys
            $badges_cache_key = "shopglut_badges_search_{$search}_{$orderby}_{$order}_{$per_page}_{$offset}";
            $count_cache_key = "shopglut_badges_search_count_{$search}";

            $cached_badges = wp_cache_get($badges_cache_key, 'shopglut_badges');
            $cached_count = wp_cache_get($count_cache_key, 'shopglut_badges');

            if (false === $cached_badges) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query, safe table name from Database class method
                $badges = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM `" . esc_sql($table_name) . "` WHERE layout_name LIKE %s ORDER BY %s %s LIMIT %d OFFSET %d",
                    $search_term, $orderby, $order, $per_page, $offset
                ), ARRAY_A);
                wp_cache_set($badges_cache_key, $badges, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
            } else {
                $badges = $cached_badges;
            }

            if (false === $cached_count) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query, safe table name from Database class method
                $total_items = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM `" . esc_sql($table_name) . "` WHERE layout_name LIKE %s",
                    $search_term
                ));
                wp_cache_set($count_cache_key, $total_items, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
            } else {
                $total_items = $cached_count;
            }
        } else {
            // Create cache keys
            $badges_cache_key = "shopglut_badges_all_{$orderby}_{$order}_{$per_page}_{$offset}";
            $count_cache_key = 'shopglut_badges_all_count';

            $cached_badges = wp_cache_get($badges_cache_key, 'shopglut_badges');
            $cached_count = wp_cache_get($count_cache_key, 'shopglut_badges');

            if (false === $cached_badges) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query, safe table name from Database class method
                $badges = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM `" . esc_sql($table_name) . "` ORDER BY %s %s LIMIT %d OFFSET %d",
                    $orderby, $order, $per_page, $offset
                ), ARRAY_A);
                wp_cache_set($badges_cache_key, $badges, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
            } else {
                $badges = $cached_badges;
            }

            if (false === $cached_count) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query, safe table name from Database class method
                $total_items = $wpdb->get_var("SELECT COUNT(*) FROM `" . esc_sql($table_name) . "`");
                wp_cache_set($count_cache_key, $total_items, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
            } else {
                $total_items = $cached_count;
            }
        }

        $this->items = $badges;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $this->_column_headers = array(
            $this->get_columns(),
            array(),
            $this->get_sortable_columns()
        );
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="badges[]" value="%s" />',
            esc_attr($item['id'])
        );
    }

    public function column_layout_name($item) {
        $badge_id = absint($item['id']);
        $edit_link = add_query_arg(array('editor' => 'product_badges', 'badge_id' => $badge_id), admin_url('admin.php?page=shopglut_enhancements'));
        $delete_link = wp_nonce_url(
            add_query_arg(array('action' => 'delete', 'badge_id' => $badge_id), admin_url('admin.php?page=shopglut_enhancements&view=product_badges')),
            'shopglut_delete_badge_' . $badge_id
        );

        $actions = array(
            'edit' => sprintf('<a href="%s">%s</a>', esc_url($edit_link), esc_html__('Edit', 'shopglut')),
            'delete' => sprintf(
                '<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
                esc_url($delete_link),
                esc_html__('Are you sure you want to delete this badge?', 'shopglut'),
                esc_html__('Delete', 'shopglut')
            ),
        );

        $name = '<a href="' . esc_url($edit_link) . '">' . esc_html($item['layout_name']) . '</a>';

        return sprintf('<strong>%s</strong>%s', $name, $this->row_actions($actions));
    }

    public function column_layout_template($item) {
        $template_name = esc_html($item['layout_template']);
        return '<strong>' . esc_html($template_name) . '</strong>';
    }

    public function column_display_badge_on($item) {
        if (empty($item['layout_settings'])) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        $badge_settings = maybe_unserialize($item['layout_settings']);
        $display_locations = isset($badge_settings['shopg_product_badge_settings']['display-locations'])
            ? $badge_settings['shopg_product_badge_settings']['display-locations']
            : array();

        if (empty($display_locations) || !is_array($display_locations)) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        // Add some CSS for better styling
        static $css_added = false;
        if (!$css_added) {
            echo '<style>
                .badge-display-all {
                    background: #e8f5e8;
                    color: #2d6a2d;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 600;
                    display: inline-block;
                    margin: 1px;
                }
                .badge-display-product {
                    background: #e3f2fd;
                    color: #1565c0;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 500;
                    display: inline-block;
                    margin: 1px;
                    max-width: 150px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    vertical-align: middle;
                }
                .badge-display-location {
                    background: #fff3e0;
                    color: #e65100;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 500;
                    display: inline-block;
                    margin: 1px;
                }
            </style>';
            $css_added = true;
        }

        $display_items = array();
        $product_count = 0;

        foreach ($display_locations as $location) {
            if ($location === 'All Products') {
                $display_items[] = '<span class="badge-display-all" title="' . esc_attr__('This badge will appear on all products', 'shopglut') . '">' . esc_html__('All Products', 'shopglut') . '</span>';
            } elseif (preg_match('/product_(\d+)/i', $location, $matches)) {
                $product_id = intval($matches[1]);
                $product_title = $this->get_product_title($product_id);
                if ($product_title) {
                    $product_count++;
                    if ($product_count <= 3) {
                        /* translators: %d: Product ID */
                        $display_items[] = '<span class="badge-display-product" title="' . esc_attr(sprintf(__('Product ID: %d', 'shopglut'), $product_id)) . '">' . esc_html($product_title) . '</span>';
                    }
                }
            } else {
                // Handle other display locations
                /* translators: %s: Display location name */
                $display_items[] = '<span class="badge-display-location" title="' . esc_attr(sprintf(__('Display location: %s', 'shopglut'), $location)) . '">' . esc_html(ucfirst(str_replace('_', ' ', $location))) . '</span>';
            }
        }

        if (empty($display_items)) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        $output = '<div style="line-height: 1.8; max-width: 300px;">';
        if ($product_count > 3) {
            $remaining = $product_count - 3;
            $shown_items = array_slice($display_items, 0, 3);
            $output .= implode(' ', $shown_items);
            /* translators: %d: Number of additional products */
            $output .= ' <span class="badge-display-location" title="' . esc_attr(sprintf(__('%d more products', 'shopglut'), $remaining)) . '">+' . $remaining . ' ' . esc_html__('more', 'shopglut') . '</span>';
        } else {
            $output .= implode(' ', $display_items);
        }
        $output .= '</div>';

        return $output;
    }

    private function get_product_title($product_id) {
        // Try WooCommerce first
        if (function_exists('wc_get_product')) {
            $product = wc_get_product($product_id);
            if ($product) {
                return $product->get_name();
            }
        }

        // Fallback to WordPress post
        $post = get_post($product_id);
        if ($post && $post->post_type === 'product') {
            return $post->post_title;
        }

        // Final fallback
        /* translators: %d: Product ID */
        return sprintf(__('Product #%d', 'shopglut'), $product_id);
    }

    public function column_status($item) {
        $is_active = false;

        if (isset($item['layout_settings'])) {
            $settings = maybe_unserialize($item['layout_settings']);

            // Check if badge is enabled and has display locations
            if (isset($settings['shopg_product_badge_settings']['enable_badge']) &&
                $settings['shopg_product_badge_settings']['enable_badge'] === '1') {

                // Check if any display locations are set
                if (isset($settings['shopg_product_badge_settings']['display-locations'])) {
                    $locations = $settings['shopg_product_badge_settings']['display-locations'];
                    if (!empty($locations) && is_array($locations) && count($locations) > 0) {
                        $is_active = true;
                    }
                }
            }
        }

        if ($is_active) {
            return '<span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__('This badge is currently active with display locations', 'shopglut') . '">' . esc_html__('ACTIVE', 'shopglut') . '</span>';
        } else {
            return '<span style="background: #6b7280; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__('This badge has no display locations configured', 'shopglut') . '">' . esc_html__('INACTIVE', 'shopglut') . '</span>';
        }
    }

    public function process_bulk_action() {
        if (!isset($_POST['action']) && !isset($_POST['action2'])) {
            return;
        }

        $action = isset($_POST['action']) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : sanitize_text_field( wp_unslash( $_POST['action2'] ) );
        $badge_ids = isset($_POST['badges']) ? array_map('absint', (array) $_POST['badges']) : array();

        if (empty($badge_ids)) {
            return;
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'bulk-badges')) {
            wp_die(esc_html__('Security check failed.', 'shopglut'));
        }

        switch ($action) {
            case 'bulk_delete':
                global $wpdb;
                $table_name = ShopGlutDatabase::table_product_badges();
                $deleted_count = 0;

                foreach ($badge_ids as $badge_id) {
                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, cache flushed after bulk operation
                    $result = $wpdb->delete($table_name, ['id' => absint($badge_id)], ['%d']);
                    if ($result !== false) {
                        $deleted_count++;
                    }
                }

                // Clear cache after bulk deletion
                if ($deleted_count > 0) {
                    wp_cache_flush_group('shopglut_badges');
                }
                /* translators: %d: Number of deleted badges */
                $message = sprintf(__('%d badges have been deleted.', 'shopglut'), $deleted_count);
                break;
        }

        if (isset($message)) {
            add_action('admin_notices', function() use ($message) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            });
        }
    }
}