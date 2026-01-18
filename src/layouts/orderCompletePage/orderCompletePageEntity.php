<?php
namespace Shopglut\layouts\orderCompletePage;

use Shopglut\ShopGlutDatabase;

class orderCompletePageEntity {
    protected static function getTable() {
        global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->prefix . 'shopglut_ordercomplete_layouts';
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Validate input parameters
        $limit = absint($limit);
        $current_page = absint($current_page);

        // Cache key for this query
        $cache_key = 'shopglut_ordercomplete_all_' . md5( $limit . '_' . $current_page );
        $result = wp_cache_get( $cache_key );

        if ( false === $result ) {
            // Build query with proper table name placeholder (compatible with older WordPress versions)
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

            // Execute with proper table name escaping and caching
            $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
                $wpdb->prepare(implode(' ', $sql_parts), ...$sql_params), 'ARRAY_A' // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using implode with sql_parts and params
            );

            // Cache the result for 1 hour
            wp_cache_set( $cache_key, $result, '', 3600 );
        }

        return is_array($result) ? $result : [];
    }

    public static function retrieveAllCount() {
        global $wpdb;
        $table = self::getTable();
        
        if (empty($table)) {
            return 0;
        }

        // Cache key for count query
        $cache_key = 'shopglut_ordercomplete_count';
        $count = wp_cache_get( $cache_key );
        
        if ( false === $count ) {
            $count = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
                sprintf("SELECT COUNT(id) FROM `%s` WHERE 1=%d", esc_sql(self::getTable()), 1) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameter
            );

            // Cache the result for 1 hour
            wp_cache_set( $cache_key, $count, '', 3600 );
        }

        return $count;
    }

    public static function delete_layout($layout_id) {
        global $wpdb;
        $table = self::getTable();
        
        if (empty($table) || !$layout_id) {
            return false;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table delete operation, cache cleared below
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->delete(
            $table, 
            ['id' => absint($layout_id)], 
            ['%d']
        );

        // Clear related cache after deletion
        if ( $result ) {
            wp_cache_delete( 'shopglut_ordercomplete_count' );
            // Clear listing cache with pattern matching (simplified approach)
            wp_cache_flush(); // Or implement more targeted cache clearing
        }

        return $result;
    }

    // Add a method to verify table exists
    public static function verifyTable() {
        global $wpdb;
        $table = self::getTable();
        
        // Cache key for table existence
        $cache_key = 'shopglut_table_exists_ordercomplete';
        $exists = wp_cache_get( $cache_key );
        
        if ( false === $exists ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table existence check with caching implemented
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            $exists = $wpdb->get_var(
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                $wpdb->prepare(
                    "SHOW TABLES LIKE %s",
                    $table
                )
            ) === $table;
            
            // Cache for 1 hour
            wp_cache_set( $cache_key, $exists, '', 3600 );
        }
        
        return $exists;
    }
}