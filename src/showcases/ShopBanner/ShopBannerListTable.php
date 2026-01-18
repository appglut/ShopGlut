<?php
namespace Shopglut\showcases\ShopBanner;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class ShopBannerListTable extends \WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'shopbanner',
            'plural' => 'shopbanners',
            'ajax' => false
        ));
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'layout_name' => __('Name', 'shopglut'),
            'layout_template' => __('Template', 'shopglut'),
            'display_locations' => __('Display Locations', 'shopglut'),
            'status' => __('Status', 'shopglut'),
            'date' => __('Created', 'shopglut')
        );
    }

    public function get_sortable_columns() {
        return array(
            'layout_name' => array('layout_name', false),
            'layout_template' => array('layout_template', false),
            'date' => array('created_at', true)
        );
    }

    public function get_bulk_actions() {
        return array(
            'delete' => __('Delete', 'shopglut')
        );
    }

    public function no_items() {
        echo '<div style="padding: 40px 20px; text-align: center;">';
        echo '<div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">📦</div>';
        echo '<h3 style="color: #374151; margin: 0 0 8px 0;">' . esc_html__('No ShopBanner Layouts Found', 'shopglut') . '</h3>';
        echo '<p style="color: #6b7280; margin: 0 0 20px 0;">' . esc_html__('Create your first ShopBanner layout to get started.', 'shopglut') . '</p>';
        echo '<a href="' . esc_url(admin_url('admin.php?page=shopglut_showcases&view=shop_banner_templates')) . '" class="button button-primary">' . esc_html__('Create New Layout', 'shopglut') . '</a>';
        echo '</div>';
    }

    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Handle search
        $search = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Handle sorting
        $allowed_orderby = ['id', 'layout_name', 'created_at', 'updated_at'];
        $orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'layout_name'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $orderby = in_array($orderby, $allowed_orderby, true) ? $orderby : 'layout_name';

        $allowed_order = ['ASC', 'DESC'];
        $order = isset($_GET['order']) ? sanitize_text_field(wp_unslash($_GET['order'])) : 'ASC'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $order = in_array(strtoupper($order), $allowed_order, true) ? strtoupper($order) : 'ASC';

        // Get shopbanners using existing methods
        $total_items = ShopBannerEntity::retrieveAllCount();
        $shopbanners = ShopBannerEntity::retrieveAll($per_page, $current_page);

        $this->items = $shopbanners;

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
            '<input type="checkbox" name="layout_ids[]" value="%s" />',
            esc_attr($item['id'])
        );
    }

    public function column_layout_name($item) {
        $layout_id = absint($item['id']);
        $edit_link = add_query_arg(array('editor' => 'shopbanner', 'layout_id' => $layout_id), admin_url('admin.php?page=shopglut_showcases'));
        $delete_link = wp_nonce_url(
            add_query_arg(array('action' => 'delete', 'layout_id' => $layout_id), admin_url('admin.php?page=shopglut_showcases&view=shop_banner')),
            'shopglut_delete_layout_' . $layout_id
        );
        $duplicate_link = add_query_arg(
            array('action' => 'duplicate', 'layout_id' => $layout_id),
            admin_url('admin.php?page=shopglut_showcases&view=shop_banner')
        );

        $actions = array(
            'edit' => sprintf('<a href="%s">%s</a>', esc_url($edit_link), esc_html__('Edit', 'shopglut')),
            'duplicate' => sprintf('<a href="%s">%s</a>', esc_url($duplicate_link), esc_html__('Duplicate', 'shopglut')),
            'delete' => sprintf(
                '<a href="%s" onclick="return confirm(\'%s\')" style="color: #dc3545;">%s</a>',
                esc_url($delete_link),
                esc_html__('Are you sure you want to delete this ShopBanner layout?', 'shopglut'),
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

    public function column_display_locations($item) {
        if (empty($item['layout_settings'])) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        $banner_settings = maybe_unserialize($item['layout_settings']);
        $display_locations = isset($banner_settings['shopg_product_shopbanner_settings_template1']['display-locations'])
            ? $banner_settings['shopg_product_shopbanner_settings_template1']['display-locations']
            : array();

        if (empty($display_locations) || !is_array($display_locations)) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        // Add some CSS for better styling
        static $css_added = false;
        if (!$css_added) {
            echo '<style>
                .banner-display-all {
                    background: #e8f5e8;
                    color: #2d6a2d;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 600;
                    display: inline-block;
                    margin: 1px;
                }
                .banner-display-product {
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
                .banner-display-location {
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

        foreach ($display_locations as $location) {
            if ($location === 'All Products') {
                $display_items[] = '<span class="banner-display-all" title="' . esc_attr__('This banner will appear on all products', 'shopglut') . '">' . esc_html__('All Products', 'shopglut') . '</span>';
            } elseif ($location === 'Woo Shop Page') {
                $display_items[] = '<span class="banner-display-all" title="' . esc_attr__('This banner will appear on shop page', 'shopglut') . '">' . esc_html__('Shop Page', 'shopglut') . '</span>';
            } elseif (strpos($location, 'cat_') === 0) {
                $cat_id = str_replace('cat_', '', $location);
                $category = get_term($cat_id, 'product_cat');
                if ($category && !is_wp_error($category)) {
                    /* translators: %s: category name */
                    $display_items[] = '<span class="banner-display-location" title="' . esc_attr(sprintf(__('Category: %s', 'shopglut'), $category->name)) . '">' . esc_html($category->name) . '</span>';
                }
            } elseif (strpos($location, 'product_') === 0) {
                $product_id = str_replace('product_', '', $location);
                $product_title = $this->get_product_title($product_id);
                if ($product_title) {
                    /* translators: %s: product title */
$display_items[] = '<span class="banner-display-product" title="' . esc_attr(sprintf(__('Product: %s', 'shopglut'), $product_title)) . '">' . esc_html($product_title) . '</span>';
                }
            } else {
                // Handle other display locations
                /* translators: %s: Display location name */
                $display_items[] = '<span class="banner-display-location" title="' . esc_attr(sprintf(__('Display location: %s', 'shopglut'), $location)) . '">' . esc_html(ucfirst(str_replace('_', ' ', $location))) . '</span>';
            }
        }

        if (empty($display_items)) {
            return '<span class="description">' . esc_html__('Not configured', 'shopglut') . '</span>';
        }

        $output = '<div style="line-height: 1.8; max-width: 300px;">';
        $output .= implode(' ', array_slice($display_items, 0, 3));

        if (count($display_items) > 3) {
            $remaining = count($display_items) - 3;
            /* translators: %d: Number of additional locations */
            $output .= ' <span class="banner-display-location" title="' . esc_attr(sprintf(__('%d more locations', 'shopglut'), $remaining)) . '">+' . $remaining . ' ' . esc_html__('more', 'shopglut') . '</span>';
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
        $has_locations = false;

        if (isset($item['layout_settings'])) {
            $settings = maybe_unserialize($item['layout_settings']);

            // Check if banner is enabled
            if (isset($settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner'])) {
                $is_enabled = $settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner'];
            }

            // Check if display locations are set
            if (isset($settings['shopg_product_shopbanner_settings_template1']['display-locations'])) {
                $locations = $settings['shopg_product_shopbanner_settings_template1']['display-locations'];
                if (!empty($locations) && is_array($locations) && count($locations) > 0) {
                    $has_locations = true;
                }
            }
        }

        // Determine status
        if ($is_enabled && $has_locations) {
            return '<span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__('This banner is currently active with display locations', 'shopglut') . '">' . esc_html__('ACTIVE', 'shopglut') . '</span>';
        } elseif (isset($is_enabled) && $is_enabled && !$has_locations) {
            return '<span style="background: #f59e0b; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__('This banner is enabled but no display locations are set', 'shopglut') . '">' . esc_html__('INACTIVE', 'shopglut') . '</span>';
        } else {
            return '<span style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;" title="' . esc_attr__('This banner is disabled', 'shopglut') . '">' . esc_html__('DISABLED', 'shopglut') . '</span>';
        }
    }

    public function column_date($item) {
        if (empty($item['created_at'])) {
            return '<span style="color: #9ca3af;">—</span>';
        }

        $date = strtotime($item['created_at']);
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');

        $formatted_date = date_i18n($date_format, $date);
        $formatted_time = date_i18n($time_format, $date);
        $relative_time = human_time_diff($date, current_time('timestamp'));

        return sprintf(
            '<span title="%s %s" style="cursor: help;">%s<br><small style="color: #6b7280;">%s %s</small></span>',
            esc_attr($formatted_date),
            esc_attr($formatted_time),
            esc_html($formatted_date),
            esc_html($relative_time),
            esc_html__('ago', 'shopglut')
        );
    }

    public function process_bulk_action() {
        if (!isset($_POST['action']) && !isset($_POST['action2'])) {
            return;
        }

        $action = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : sanitize_text_field(wp_unslash($_POST['action2']));
        $layout_ids = isset($_POST['layout_ids']) ? array_map('absint', (array)$_POST['layout_ids']) : array();

        if (empty($layout_ids)) {
            return;
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'bulk-showcases')) {
            wp_die(esc_html__('Security check failed.', 'shopglut'));
        }

        switch ($action) {
            case 'delete':
                $deleted_count = 0;

                foreach ($layout_ids as $layout_id) {
                    $result = ShopBannerEntity::delete_enhancement($layout_id);
                    if ($result !== false) {
                        $deleted_count++;
                    }
                }

                // Clear cache after bulk deletion
                if ($deleted_count > 0) {
                    wp_cache_flush_group('shopglut_shopbanner');
                }
                /* translators: %d: Number of deleted banners */
                $message = sprintf(__('%d shop banners have been deleted.', 'shopglut'), $deleted_count);
                break;
        }

        if (isset($message)) {
            add_action('admin_notices', function() use ($message) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            });
        }
    }

    // Handle individual actions (delete and duplicate)
    public static function handle_individual_actions() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below
        if (isset($_GET['page']) && $_GET['page'] === 'shopglut_showcases' && isset($_GET['view']) && $_GET['view'] === 'shop_banner') {

            // Handle delete action
            if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['layout_id'])) {
                $layout_id = absint($_GET['layout_id']);
                $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

                if (wp_verify_nonce($nonce, 'shopglut_delete_layout_' . $layout_id)) {
                    ShopBannerEntity::delete_enhancement($layout_id);

                    // Redirect back to the list
                    wp_safe_redirect(add_query_arg(
                        array(
                            'page' => 'shopglut_showcases',
                            'view' => 'shop_banner',
                            'deleted' => '1'
                        ),
                        admin_url('admin.php')
                    ));
                    exit;
                }
            }

            // Handle duplicate action
            if (isset($_GET['action']) && $_GET['action'] === 'duplicate' && isset($_GET['layout_id'])) {
                $layout_id = absint($_GET['layout_id']);

                // Get the original layout
                $original_layout = ShopBannerEntity::retrieve_by_id($layout_id);
                if ($original_layout) {
                    // Create duplicate with modified name
                    /* translators: %s: original layout name */
                    $duplicate_name = sprintf(esc_html__('%s (Copy)', 'shopglut'), $original_layout['layout_name']);

                    $new_layout_id = ShopBannerEntity::duplicate_enhancement($layout_id, $duplicate_name);

                    if ($new_layout_id) {
                        // Redirect to edit the new layout
                        wp_safe_redirect(add_query_arg(
                            array(
                                'page' => 'shopglut_showcases',
                                'editor' => 'shopbanner',
                                'layout_id' => $new_layout_id,
                                'duplicated' => '1'
                            ),
                            admin_url('admin.php')
                        ));
                        exit;
                    }
                }
            }
        }
    }

    // Display the table and handle the nonce field for bulk actions
    public function display() {
        parent::display();
        wp_nonce_field('bulk-showcases');
    }

    /**
     * Get views for filtering (All, Active, Inactive, Disabled)
     */
    protected function get_views() {
        $views = array();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter for view filtering
        $current = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : 'all';

        // Count layouts by status
        $all_count = $this->get_totals();
        $active_count = 0;
        $inactive_count = 0;
        $disabled_count = 0;

        // Get all items to count statuses
        $all_items = ShopBannerEntity::retrieveAll(999, 1);
        foreach ($all_items as $item) {
            $is_enabled = false;
            $has_locations = false;

            if (isset($item['layout_settings'])) {
                $settings = maybe_unserialize($item['layout_settings']);

                if (isset($settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner'])) {
                    $is_enabled = $settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner'];
                }

                if (isset($settings['shopg_product_shopbanner_settings_template1']['display-locations'])
                    && !empty($settings['shopg_product_shopbanner_settings_template1']['display-locations'])) {
                    $has_locations = true;
                }
            }

            if ($is_enabled && $has_locations) {
                $active_count++;
            } elseif ($is_enabled && !$has_locations) {
                $inactive_count++;
            } else {
                $disabled_count++;
            }
        }

        // Build view links
        $base_url = admin_url('admin.php?page=shopglut_showcases&view=shop_banner');

        $views['all'] = sprintf(
            '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
            esc_url($base_url),
            $current === 'all' ? 'current' : '',
            esc_html__('All', 'shopglut'),
            $all_count
        );

        if ($active_count > 0) {
            $views['active'] = sprintf(
                '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
                esc_url(add_query_arg('status', 'active', $base_url)),
                $current === 'active' ? 'current' : '',
                esc_html__('Active', 'shopglut'),
                $active_count
            );
        }

        if ($inactive_count > 0) {
            $views['inactive'] = sprintf(
                '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
                esc_url(add_query_arg('status', 'inactive', $base_url)),
                $current === 'inactive' ? 'current' : '',
                esc_html__('Inactive', 'shopglut'),
                $inactive_count
            );
        }

        if ($disabled_count > 0) {
            $views['disabled'] = sprintf(
                '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
                esc_url(add_query_arg('status', 'disabled', $base_url)),
                $current === 'disabled' ? 'current' : '',
                esc_html__('Disabled', 'shopglut'),
                $disabled_count
            );
        }

        return $views;
    }
}