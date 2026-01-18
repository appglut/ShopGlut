<?php
namespace Shopglut\enhancements\ProductQuickView;

use Shopglut\ShopGlutDatabase;

class QuickViewEntity {
    protected static function getTable() {
        global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->prefix . 'shopglut_quickview_layouts';
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Create cache key
        $cache_key = "shopglut_quickview_all_{$limit}_{$current_page}";
        $cached_result = wp_cache_get( $cache_key, 'shopglut_quickview' );

        if ( false !== $cached_result ) {
            return $cached_result;
        }

        // Validate input parameters
        $limit = absint($limit);
        $current_page = absint($current_page);

        // Build query using proper table identifier
        $sql_parts = ["SELECT * FROM %i WHERE 1=%d ORDER BY id DESC"];
        $sql_params = [$table, 1]; // Start with table name and WHERE clause parameter

        if ($limit > 0) {
            $sql_parts[] = "LIMIT %d";
            $sql_params[] = $limit;

            if ($current_page > 1) {
                $offset = ($current_page - 1) * $limit;
                $sql_parts[] = "OFFSET %d";
                $sql_params[] = $offset;
            }
        }

        $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $wpdb->prepare(implode(' ', $sql_parts), ...$sql_params), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using $wpdb->prepare with proper placeholders, %i placeholder for table identifier
            'ARRAY_A'
        );

        // Cache the result for 15 minutes
        wp_cache_set( $cache_key, $result, 'shopglut_quickview', 15 * MINUTE_IN_SECONDS );

        return is_array($result) ? $result : [];
    }

    public static function retrieveAllCount() {
        global $wpdb;
        $table = self::getTable();

        if (empty($table)) {
            return 0;
        }

        // Create cache key
        $cache_key = 'shopglut_quickview_count';
        $cached_count = wp_cache_get( $cache_key, 'shopglut_quickview' );

        if ( false !== $cached_count ) {
            return (int) $cached_count;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        $count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM `" . esc_sql($table) . "` WHERE 1=%d", 1));

        // Cache the count for 15 minutes
        wp_cache_set( $cache_key, $count, 'shopglut_quickview', 15 * MINUTE_IN_SECONDS );

        return $count;
    }

    public static function delete_enhancement($layout_id) {
        global $wpdb;
        $table = self::getTable();

        if (empty($table) || !$layout_id) {
            return false;
        }
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->delete(
            $table,
            ['id' => absint($layout_id)],
            ['%d']
        );

        return $result;
    }

    // Add a method to verify table exists
    public static function verifyTable() {
        global $wpdb;
        $table = self::getTable();

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $exists = $wpdb->get_var(
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $table
            )
        ) === $table;

        return $exists;
    }
}