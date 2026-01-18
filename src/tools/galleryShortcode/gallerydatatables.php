<?php
/**
 * Gallery Database Tables Management
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GalleryDataTables {

    /**
     * Create gallery shortcode tables
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Main gallery shortcode table
        $table_name = $wpdb->prefix . 'shopglut_gallery_shortcode';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            gallery_name varchar(255) NOT NULL,
            gallery_description text NULL,
            layout varchar(50) NOT NULL DEFAULT 'grid',
            columns int(11) NOT NULL DEFAULT 3,
            columns_tablet int(11) NOT NULL DEFAULT 2,
            columns_mobile int(11) NOT NULL DEFAULT 1,
            spacing varchar(20) NOT NULL DEFAULT 'medium',
            enable_filter varchar(10) NOT NULL DEFAULT 'yes',
            filter_position varchar(20) NOT NULL DEFAULT 'top',
            pagination_type varchar(20) NOT NULL DEFAULT 'yes',
            items_per_page int(11) NOT NULL DEFAULT 12,
            orderby varchar(50) NOT NULL DEFAULT 'date',
            `order` varchar(10) NOT NULL DEFAULT 'DESC',
            category_ids text NULL,
            tag_ids text NULL,
            featured_only varchar(10) NOT NULL DEFAULT 'no',
            sale_only varchar(10) NOT NULL DEFAULT 'no',
            show_price varchar(10) NOT NULL DEFAULT 'yes',
            show_title varchar(10) NOT NULL DEFAULT 'yes',
            show_category varchar(10) NOT NULL DEFAULT 'yes',
            show_rating varchar(10) NOT NULL DEFAULT 'yes',
            show_add_to_cart varchar(10) NOT NULL DEFAULT 'yes',
            hover_effect varchar(50) NOT NULL DEFAULT 'zoom',
            animation varchar(50) NOT NULL DEFAULT 'fadeIn',
            lazy_load varchar(10) NOT NULL DEFAULT 'yes',
            custom_css text NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            status int(11) NOT NULL DEFAULT 1,
            PRIMARY KEY (id),
            KEY layout (layout),
            KEY status (status)
        ) $charset_collate;";

        // Gallery templates table
        $templates_table = $wpdb->prefix . 'shopglut_gallery_templates';
        $template_sql = "CREATE TABLE IF NOT EXISTS $templates_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            template_name varchar(255) NOT NULL,
            template_slug varchar(255) NOT NULL,
            template_description text NULL,
            template_config text NULL,
            template_preview varchar(255) NULL,
            is_default varchar(10) NOT NULL DEFAULT 'no',
            status int(11) NOT NULL DEFAULT 1,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY template_slug (template_slug),
            KEY status (status)
        ) $charset_collate;";

        // Gallery usage tracking table
        $usage_table = $wpdb->prefix . 'shopglut_gallery_usage';
        $usage_sql = "CREATE TABLE IF NOT EXISTS $usage_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            gallery_id int(11) NOT NULL,
            post_id int(11) NULL,
            shortcode_location text NULL,
            usage_count int(11) NOT NULL DEFAULT 1,
            last_used datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY gallery_id (gallery_id),
            KEY post_id (post_id),
            KEY last_used (last_used)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Suppress warnings for dbDelta
        $old_error_reporting = error_reporting(0);
        dbDelta($sql);
        dbDelta($template_sql);
        dbDelta($usage_sql);
        error_reporting($old_error_reporting);

        // Check if templates table has any records before inserting
        global $wpdb;
        $templates_table = self::get_templates_table();
        $template_count = $wpdb->get_var("SELECT COUNT(*) FROM $templates_table");

        // Only insert default templates if table is empty
        if ($template_count == 0) {
            self::insert_default_templates();
        }
    }

    /**
     * Get gallery shortcode table name
     *
     * @return string Table name
     */
    public static function get_gallery_table() {
        global $wpdb;
        return $wpdb->prefix . 'shopglut_gallery_shortcode';
    }

    /**
     * Get templates table name
     *
     * @return string Table name
     */
    public static function get_templates_table() {
        global $wpdb;
        return $wpdb->prefix . 'shopglut_gallery_templates';
    }

    /**
     * Get usage table name
     *
     * @return string Table name
     */
    public static function get_usage_table() {
        global $wpdb;
        return $wpdb->prefix . 'shopglut_gallery_usage';
    }

    /**
     * Insert default gallery templates
     */
    private static function insert_default_templates() {
        global $wpdb;

        $templates_table = self::get_templates_table();

        $default_templates = [
            [
                'template_name' => 'Grid Gallery',
                'template_slug' => 'grid-gallery',
                'template_description' => 'Classic grid layout with responsive columns',
                'template_config' => json_encode([
                    'layout' => 'grid',
                    'columns' => 3,
                    'columns_tablet' => 2,
                    'columns_mobile' => 1,
                    'spacing' => 'medium',
                    'enable_filter' => 'yes',
                    'show_price' => 'yes',
                    'show_title' => 'yes',
                    'show_category' => 'yes',
                    'hover_effect' => 'zoom'
                ]),
                'is_default' => 'yes'
            ],
            [
                'template_name' => 'Isotope Gallery',
                'template_slug' => 'isotope-gallery',
                'template_description' => 'Advanced filtering gallery with smooth animations',
                'template_config' => json_encode([
                    'layout' => 'isotope',
                    'columns' => 4,
                    'columns_tablet' => 3,
                    'columns_mobile' => 2,
                    'spacing' => 'small',
                    'enable_filter' => 'yes',
                    'filter_position' => 'top',
                    'hover_effect' => 'fade',
                    'animation' => 'fadeIn'
                ]),
                'is_default' => 'yes'
            ],
            [
                'template_name' => 'Carousel Gallery',
                'template_slug' => 'carousel-gallery',
                'template_description' => 'Sliding carousel gallery with navigation controls',
                'template_config' => json_encode([
                    'layout' => 'carousel',
                    'columns' => 3,
                    'spacing' => 'medium',
                    'hover_effect' => 'zoom',
                    'animation' => 'none'
                ]),
                'is_default' => 'yes'
            ],
            [
                'template_name' => 'Masonry Gallery',
                'template_slug' => 'masonry-gallery',
                'template_description' => 'Pinterest-style masonry layout',
                'template_config' => json_encode([
                    'layout' => 'masonry',
                    'columns' => 3,
                    'columns_tablet' => 2,
                    'columns_mobile' => 1,
                    'spacing' => 'medium',
                    'hover_effect' => 'slide'
                ]),
                'is_default' => 'yes'
            ]
        ];

        // Cache template existence check
        $cache_key = 'shopglut_default_templates_exist';
        $templates_checked = wp_cache_get($cache_key, 'shopglut_galleries');

        if ($templates_checked === false) {
            foreach ($default_templates as $template) {
                $template_cache_key = "template_exists_{$template['template_slug']}";
                $exists = wp_cache_get($template_cache_key, 'shopglut_galleries');

                if ($exists === false) {
                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for template check, safe table name from internal method
                    $exists = $wpdb->get_var($wpdb->prepare(
                        sprintf("SELECT COUNT(*) FROM %s WHERE template_slug = %%s", esc_sql($templates_table)), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf for table name compatibility
                        $template['template_slug']
                    ));
                    wp_cache_set($template_cache_key, $exists, 'shopglut_galleries', 3600);
                }

                if (!$exists) {
                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for template insertion
                    $wpdb->insert($templates_table, $template);
                    // Clear cache after insert
                    wp_cache_delete($template_cache_key, 'shopglut_galleries');
                }
            }
            wp_cache_set($cache_key, true, 'shopglut_galleries', 3600);
        }
    }

    /**
     * Drop gallery shortcode tables
     */
    public static function drop_tables() {
        global $wpdb;

        $tables = [
            self::get_gallery_table(),
            self::get_templates_table(),
            self::get_usage_table()
        ];

        foreach ($tables as $table) {
            $wpdb->query(sprintf("DROP TABLE IF EXISTS %s", esc_sql($table))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.SchemaChange,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- Required for table cleanup during plugin uninstallation
        }
    }

    /**
     * Get gallery by ID
     *
     * @param int $gallery_id Gallery ID
     * @return array|null Gallery data
     */
    public static function get_gallery($gallery_id) {
        global $wpdb;

        // Check cache first
        $cache_key = "shopglut_gallery_{$gallery_id}";
        $cached_gallery = wp_cache_get($cache_key, 'shopglut_galleries');

        if (false !== $cached_gallery) {
            return $cached_gallery;
        }

        $table_name = self::get_gallery_table();

        $gallery = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE id = %d", esc_sql($table_name), absint($gallery_id)), ARRAY_A); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared -- Direct query required for gallery data, implementing custom caching

        // Cache for 30 minutes
        wp_cache_set($cache_key, $gallery, 'shopglut_galleries', 30 * MINUTE_IN_SECONDS);

        return $gallery;
    }

    /**
     * Get all galleries
     *
     * @param array $args Query arguments
     * @return array List of galleries
     */
    public static function get_galleries($args = []) {
        global $wpdb;

        $defaults = [
            'limit' => 50,
            'offset' => 0,
            'status' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC'
        ];

        $args = wp_parse_args($args, $defaults);
        $table_name = self::get_gallery_table();

        // Build query parameters for secure execution
        $sql_parts = ["SELECT * FROM `%s`"];
        $sql_params = [$table_name];

        if ($args['status'] !== null) {
            $sql_parts[] = "WHERE status = %d";
            $sql_params[] = absint($args['status']);
        }

        // Validate and escape orderby and order parameters
        $allowed_orderby = ['id', 'gallery_name', 'created_at', 'updated_at'];
        $orderby = in_array($args['orderby'], $allowed_orderby) ? $args['orderby'] : 'id';
        $order = in_array(strtolower($args['order']), ['asc', 'desc']) ? strtoupper($args['order']) : 'DESC';

        $sql_parts[] = "ORDER BY `%s` %s LIMIT %d OFFSET %d";
        $sql_params[] = $orderby;
        $sql_params[] = $order;
        $sql_params[] = absint($args['limit']);
        $sql_params[] = absint($args['offset']);

        $sql = implode(' ', $sql_parts);
        return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled by caller
            $wpdb->prepare($sql, ...$sql_params), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using prepare with proper parameterization
            ARRAY_A
        );
    }

    /**
     * Save gallery
     *
     * @param array $data Gallery data
     * @param int|null $gallery_id Gallery ID (null for new gallery)
     * @return int|false Gallery ID or false on failure
     */
    public static function save_gallery($data, $gallery_id = null) {
        global $wpdb;

        $table_name = self::get_gallery_table();

        // Sanitize data
        $sanitized_data = [
            'gallery_name' => sanitize_text_field($data['gallery_name']),
            'gallery_description' => sanitize_textarea_field($data['gallery_description']),
            'layout' => sanitize_text_field($data['layout']),
            'columns' => intval($data['columns']),
            'columns_tablet' => intval($data['columns_tablet']),
            'columns_mobile' => intval($data['columns_mobile']),
            'spacing' => sanitize_text_field($data['spacing']),
            'enable_filter' => sanitize_text_field($data['enable_filter']),
            'filter_position' => sanitize_text_field($data['filter_position']),
            'pagination_type' => sanitize_text_field($data['pagination_type']),
            'items_per_page' => intval($data['items_per_page']),
            'orderby' => sanitize_text_field($data['orderby']),
            'order' => sanitize_text_field($data['order']),
            'category_ids' => sanitize_text_field($data['category_ids']),
            'tag_ids' => sanitize_text_field($data['tag_ids']),
            'featured_only' => sanitize_text_field($data['featured_only']),
            'sale_only' => sanitize_text_field($data['sale_only']),
            'show_price' => sanitize_text_field($data['show_price']),
            'show_title' => sanitize_text_field($data['show_title']),
            'show_category' => sanitize_text_field($data['show_category']),
            'show_rating' => sanitize_text_field($data['show_rating']),
            'show_add_to_cart' => sanitize_text_field($data['show_add_to_cart']),
            'hover_effect' => sanitize_text_field($data['hover_effect']),
            'animation' => sanitize_text_field($data['animation']),
            'lazy_load' => sanitize_text_field($data['lazy_load']),
            'custom_css' => wp_kses_post($data['custom_css']),
            'updated_at' => current_time('mysql')
        ];

        if ($gallery_id) {
            // Update existing gallery
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for gallery update
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching -- Cache management handled at higher level
            $result = $wpdb->update($table_name, $sanitized_data, ['id' => $gallery_id], ['%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'], ['%d']); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for gallery update
            return $result !== false ? $gallery_id : false;
        } else {
            // Insert new gallery
            $sanitized_data['created_at'] = current_time('mysql');
            $sanitized_data['status'] = 1;

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for gallery insertion
            $result = $wpdb->insert($table_name, $sanitized_data);
            return $result !== false ? $wpdb->insert_id : false;
        }
    }

    /**
     * Delete gallery
     *
     * @param int $gallery_id Gallery ID
     * @return bool Success status
     */
    public static function delete_gallery($gallery_id) {
        global $wpdb;

        $table_name = self::get_gallery_table();
        $usage_table = self::get_usage_table();

        // Delete gallery
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for gallery deletion
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching -- Cache invalidation handled separately
        $result = $wpdb->delete($table_name, ['id' => $gallery_id], ['%d']); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for gallery deletion

        if ($result) {
            // Delete usage records
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for usage record cleanup
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching -- Part of deletion operation
            $wpdb->delete($usage_table, ['gallery_id' => $gallery_id], ['%d']); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for usage record cleanup
            return true;
        }

        return false;
    }

    /**
     * Get gallery templates
     *
     * @param array $args Query arguments
     * @return array List of templates
     */
    public static function get_templates($args = []) {
        global $wpdb;

        $defaults = [
            'status' => 1,
            'is_default' => null
        ];

        $args = wp_parse_args($args, $defaults);
        $table_name = self::get_templates_table();

        $where_conditions = [];
        $where_values = [];

        if ($args['status'] !== null) {
            $where_conditions[] = "status = %d";
            $where_values[] = absint($args['status']);
        }

        if ($args['is_default'] !== null) {
            $where_conditions[] = "is_default = %d";
            $where_values[] = absint($args['is_default']);
        }

        // Build the complete SQL query with proper parameters
        $sql_parts = ["SELECT * FROM `%s`"];
        $sql_params = [$table_name];

        if (!empty($where_conditions)) {
            $sql_parts[] = 'WHERE ' . implode(' AND ', $where_conditions);
            $sql_params = array_merge($sql_params, $where_values);
        }

        $sql_parts[] = "ORDER BY template_name ASC";
        $sql = implode(' ', $sql_parts);

        return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled by caller
            $wpdb->prepare($sql, ...$sql_params), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using prepare with proper parameterization
            ARRAY_A
        );
    }

    /**
     * Track gallery usage
     *
     * @param int $gallery_id Gallery ID
     * @param int|null $post_id Post ID where gallery is used
     * @param string|null $location Location where shortcode is used
     * @return bool Success status
     */
    public static function track_usage($gallery_id, $post_id = null, $location = null) {
        global $wpdb;

        $usage_table = self::get_usage_table();

        // Check if usage record already exists
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for usage tracking, implementing custom logic, safe table name from internal method
        $existing = $wpdb->get_row(sprintf("SELECT id, usage_count FROM %s WHERE gallery_id = %d AND post_id = %d", esc_sql($usage_table), absint($gallery_id), absint($post_id ? $post_id : 0))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared -- Direct query required for usage tracking, implementing custom logic

        if ($existing) {
            // Update existing record
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for usage tracking update
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching -- High-frequency operation, caching not appropriate
            return $wpdb->update($usage_table, [ // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for usage tracking update
                'usage_count' => $existing->usage_count + 1,
                'last_used' => current_time('mysql'),
                'shortcode_location' => $location
            ], ['id' => $existing->id], ['%d', '%s', '%s'], ['%d']);
        } else {
            // Insert new record
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for usage tracking insertion
            return $wpdb->insert($usage_table, [
                'gallery_id' => $gallery_id,
                'post_id' => $post_id,
                'shortcode_location' => $location,
                'usage_count' => 1,
                'last_used' => current_time('mysql'),
                'created_at' => current_time('mysql')
            ], ['%d', '%d', '%s', '%d', '%s', '%s']);
        }
    }
}