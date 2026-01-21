<?php
namespace Shopglut\showcases\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\ShopGlutDatabase;

class MegaMenuEntity {
    protected static function getTable() {
        return ShopGlutDatabase::table_mega_menu_showcase();
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Cache key for this query - disabled for now to ensure fresh data
        // $cache_key = 'shopglut_megamenu_all_' . md5( $limit . '_' . $current_page );
        // $result = wp_cache_get( $cache_key, 'shopglut_megamenu' );

        // Force fresh data retrieval
        $result = false;

        if ( false === $result ) {
            if ($limit > 0) {
                if ($current_page > 1) {
                    $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
                        sprintf("SELECT id, menu_name, menu_template, menu_settings
                        FROM `%s` WHERE 1=%d ORDER BY id DESC LIMIT %d OFFSET %d", esc_sql($table), 1, absint($limit), absint(($current_page - 1) * $limit))
                    , 'ARRAY_A'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Parameters properly escaped with absint()
                } else {
                    $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
                        sprintf("SELECT id, menu_name, menu_template, menu_settings
                        FROM `%s` WHERE 1=%d ORDER BY id DESC LIMIT %d", esc_sql($table), 1, absint($limit))
                    , 'ARRAY_A'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Parameter properly escaped with absint()
                }
            } else {
                $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
                    sprintf("SELECT id, menu_name, menu_template, menu_settings
                    FROM `%s` WHERE 1=%d ORDER BY id DESC", esc_sql($table), 1)
                , 'ARRAY_A'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, no prepare needed
            }

            // Cache the result for future requests (disabled for now)
            // wp_cache_set($cache_key, $result, 'shopglut_megamenu', 300);
        }

        return $result ?: [];
    }

    public static function retrieve($id) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return null;
        }

        // Cache key for this specific layout
        // $cache_key = 'shopglut_megamenu_' . $id;
        // $result = wp_cache_get($cache_key, 'shopglut_megamenu');

        // Force fresh data retrieval
        $result = false;

        if (false === $result) {
            $result = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
                sprintf("SELECT id, menu_name, menu_template, menu_settings
                 FROM `%s` WHERE id = %d", esc_sql($table), absint($id))
            , 'ARRAY_A'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameter

            // Cache the result for future requests (disabled for now)
            // if ($result) {
            //     wp_cache_set($cache_key, $result, 'shopglut_megamenu', 300);
            // }
        }

        return $result;
    }

    public static function getTotalCount() {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return 0;
        }

        // Cache key for count
        // $cache_key = 'shopglut_megamenu_count';
        // $count = wp_cache_get($cache_key, 'shopglut_megamenu');

        // Force fresh count
        $count = false;

        if (false === $count) {
            $count = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
                sprintf("SELECT COUNT(*) FROM `%s`", esc_sql($table)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.MissingReplacements -- Using sprintf with escaped table name, no additional parameters needed
            );

            // Cache the count for a shorter time (disabled for now)
            // wp_cache_set($cache_key, $count, 'shopglut_megamenu', 300);
        }

        return (int) $count;
    }

    public static function delete_layout($id) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return false;
        }

        // Verify the layout exists
        $layout = self::retrieve($id);
        if (!$layout) {
            return false;
        }

        // Clear caches for this layout
        // wp_cache_delete('shopglut_megamenu_' . $id, 'shopglut_megamenu');
        // wp_cache_delete('shopglut_megamenu_count', 'shopglut_megamenu');

        // Clear the "all layouts" cache
        // wp_cache_delete('shopglut_megamenu_all', 'shopglut_megamenu');

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->delete(
            $table,
            ['id' => $id],
            ['%d']
        );
    }

    public static function createLayout($menu_name, $menu_template, $menu_settings = '{}') {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return false;
        }

        // Sanitize inputs
        $menu_name = sanitize_text_field($menu_name);
        $menu_template = sanitize_text_field($menu_template);

        // Validate JSON
        if (!is_string($menu_settings)) {
            $menu_settings = '{}';
        }

        // Ensure valid JSON
        json_decode($menu_settings);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $menu_settings = '{}';
        }

        // Prepare data for insertion
        $data = [
            'menu_name' => $menu_name,
            'menu_template' => $menu_template,
            'menu_settings' => $menu_settings
        ];

        $format = ['%s', '%s', '%s'];

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->insert($table, $data, $format);

        if ($result === false) {
            return false;
        }

        // Clear caches after insertion
        // wp_cache_delete('shopglut_megamenu_count', 'shopglut_megamenu');
        // wp_cache_delete('shopglut_megamenu_all', 'shopglut_megamenu');

        return $wpdb->insert_id;
    }

    public static function updateLayout($id, $menu_name = null, $menu_template = null, $menu_settings = null) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return false;
        }

        // Verify the layout exists
        $existing_layout = self::retrieve($id);
        if (!$existing_layout) {
            return false;
        }

        // Prepare update data
        $update_data = [];
        $format = [];

        if ($menu_name !== null) {
            $update_data['menu_name'] = sanitize_text_field($menu_name);
            $format[] = '%s';
        }

        if ($menu_template !== null) {
            $update_data['menu_template'] = sanitize_text_field($menu_template);
            $format[] = '%s';
        }

        if ($menu_settings !== null) {
            // Validate JSON
            if (!is_string($menu_settings)) {
                $menu_settings = '{}';
            }

            json_decode($menu_settings);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $menu_settings = '{}';
            }

            $update_data['menu_settings'] = $menu_settings;
            $format[] = '%s';
        }

        if (empty($update_data)) {
            return false; // Nothing to update
        }

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->update(
            $table,
            $update_data,
            ['id' => $id],
            $format,
            ['%d']
        );

        if ($result === false) {
            return false;
        }

        // Clear caches for this layout
        // wp_cache_delete('shopglut_megamenu_' . $id, 'shopglut_megamenu');
        // wp_cache_delete('shopglut_megamenu_all', 'shopglut_megamenu');

        return true;
    }
}