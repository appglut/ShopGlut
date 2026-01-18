<?php
namespace Shopglut\showcases\Tabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\ShopGlutDatabase;

class TabEntity {
    protected static function getTable() {
        global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->prefix . 'shopglut_tab_layouts';
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Create cache key
        $cache_key = "shopglut_tab_all_{$limit}_{$current_page}";
        $cached_result = wp_cache_get( $cache_key, 'shopglut_tab' );

        if ( false !== $cached_result ) {
            return $cached_result;
        }

        // Build query parameters using proper prepare for security
        $sql_parts = ["SELECT * FROM `%s` WHERE 1=%d ORDER BY id DESC"];
        $sql_params = [$table, 1];

        if ($limit > 0) {
            $sql_parts[] = "LIMIT %d";
            $sql_params[] = $limit;

            if ($current_page > 1) {
                $sql_parts[] = "OFFSET %d";
                $sql_params[] = ($current_page - 1) * $limit;
            }
        }

        // Execute with proper table name escaping
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $wpdb->prepare(implode(' ', $sql_parts), ...$sql_params), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- SQL parts properly escaped and parameterized, placeholders exist in $sql_parts
            'ARRAY_A'
        );

        // Cache the result for 15 minutes
        wp_cache_set( $cache_key, $result, 'shopglut_tab', 15 * MINUTE_IN_SECONDS );

        return is_array($result) ? $result : [];
    }

    public static function retrieveAllCount() {
        global $wpdb;
        $table = self::getTable();

        if (empty($table)) {
            return 0;
        }

        // Create cache key
        $cache_key = 'shopglut_tab_count';
        $cached_count = wp_cache_get( $cache_key, 'shopglut_tab' );

        if ( false !== $cached_count ) {
            return (int) $cached_count;
        }

        $count = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            sprintf("SELECT COUNT(id) FROM `%s` WHERE 1=%d", esc_sql($table), 1) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, no prepare needed

        // Cache the count for 15 minutes
        wp_cache_set( $cache_key, $count, 'shopglut_tab', 15 * MINUTE_IN_SECONDS );

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

    /**
     * Retrieve a specific layout by ID
     */
    public static function retrieve_by_id($layout_id) {
        global $wpdb;
        $table = self::getTable();

        if (empty($table) || !$layout_id) {
            return false;
        }

$result = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
            sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($table), absint($layout_id)), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, no prepare needed
            ARRAY_A
        );

        return $result;
    }

    /**
     * Duplicate an existing layout
     */
    public static function duplicate_enhancement($layout_id, $new_name = null) {
        global $wpdb;
        $table = self::getTable();

        if (empty($table) || !$layout_id) {
            return false;
        }

        // Get the original layout
        $original_layout = self::retrieve_by_id($layout_id);
        if (!$original_layout) {
            return false;
        }

        // Prepare new layout data
        $new_layout_data = $original_layout;
        unset($new_layout_data['id']); // Remove the ID for auto-increment
        unset($new_layout_data['created_at']); // Let MySQL set new timestamp

        // Set new name if provided
        if ($new_name) {
            $new_layout_data['layout_name'] = $new_name;
        } else {
            /* translators: %s: original layout name */
            $new_layout_data['layout_name'] = sprintf( __('%s (Copy)', 'shopglut'), $original_layout['layout_name']);
        }

        // Insert the new layout
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->insert(
            $table,
            $new_layout_data,
            array_fill(0, count($new_layout_data), '%s')
        );

        if ($result) {
            return $wpdb->insert_id;
        }

        return false;
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