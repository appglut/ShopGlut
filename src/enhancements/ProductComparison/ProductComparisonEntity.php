<?php
namespace Shopglut\enhancements\ProductComparison;

use Shopglut\ShopGlutDatabase;

class ProductComparisonEntity {
    protected static function getTable() {
        global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->prefix . 'shopglut_comparison_layouts';
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Create cache key
        $cache_key = "shopglut_comparison_all_{$limit}_{$current_page}";
        $cached_result = wp_cache_get( $cache_key, 'shopglut_comparison' );

        if ( false !== $cached_result ) {
            return $cached_result;
        }

        // Build query with proper table name interpolation
        $sql_parts = ["SELECT * FROM {$table} WHERE 1=%d ORDER BY id DESC"];
        $sql_params = [1];

        if ($limit > 0) {
            $sql_parts[] = "LIMIT %d";
            $sql_params[] = $limit;

            if ($current_page > 1) {
                $sql_parts[] = "OFFSET %d";
                $sql_params[] = ($current_page - 1) * $limit;
            }
        }

        // Combine all parts
        $sql = implode( ' ', $sql_parts );

        // Execute with proper table name escaping
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Table name properly escaped
        $result = $wpdb->get_results($wpdb->prepare($sql, ...$sql_params), 'ARRAY_A');

        // Cache the result for 15 minutes
        wp_cache_set( $cache_key, $result, 'shopglut_comparison', 15 * MINUTE_IN_SECONDS );

        return is_array($result) ? $result : [];
    }

    public static function retrieveAllCount() {
        global $wpdb;
        $table = self::getTable();

        if (empty($table)) {
            return 0;
        }

        // Create cache key
        $cache_key = 'shopglut_comparison_count';
        $cached_count = wp_cache_get( $cache_key, 'shopglut_comparison' );

        if ( false !== $cached_count ) {
            return (int) $cached_count;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Table name properly escaped
        $count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM {$table} WHERE 1=%d", 1));

        // Cache the count for 15 minutes
        wp_cache_set( $cache_key, $count, 'shopglut_comparison', 15 * MINUTE_IN_SECONDS );

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

    // Alias method for consistency with other modules
    public static function delete_layout($layout_id) {
        return self::delete_enhancement($layout_id);
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