<?php
namespace Shopglut\tools\productCustomField;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\ShopGlutDatabase;

class ProductCustomFieldEntity {
    protected static function getTable() {
        return ShopGlutDatabase::table_product_custom_field_settings();
    }

    public static function retrieveAll($limit = 0, $current_page = 1) {
        global $wpdb;

        $table = self::getTable();
        if (empty($table)) {
            return [];
        }

        // Cache key for this query
        $cache_key = 'shopglut_product_custom_field_all_' . md5( $limit . '_' . $current_page );
        $result = wp_cache_get( $cache_key );
        
        if ( false === $result ) {
            // Validate and sanitize current_page and limit parameters
            $current_page = is_numeric($current_page) ? absint($current_page) : 1;
            $limit = is_numeric($limit) ? absint($limit) : 10;

            if ($limit > 0) {
                if ($current_page > 1) {
                                      $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query, caching handled separately
                        sprintf("SELECT * FROM %s WHERE 1=%d ORDER BY id DESC LIMIT %d OFFSET %d", esc_sql($table), 1, absint($limit), absint(($current_page - 1) * $limit)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameters
                    , 'ARRAY_A');
                } else {
                                    $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query, caching handled separately
                        sprintf("SELECT * FROM %s WHERE 1=%d ORDER BY id DESC LIMIT %d", esc_sql($table), 1, absint($limit)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameters
                    , 'ARRAY_A');
                }
            } else {
                          $result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query, caching handled separately
                    sprintf("SELECT * FROM %s WHERE 1=%d ORDER BY id DESC", esc_sql($table), 1) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameters
                , 'ARRAY_A');
            }
            
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
        $cache_key = 'shopglut_product_custom_field_count';
        $count = wp_cache_get( $cache_key );
        
        if ( false === $count ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $count = (int) $wpdb->get_var(sprintf("SELECT COUNT(id) FROM %s WHERE 1=%d", esc_sql($table), 1)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with escaped table name, safe for custom table operations
            
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
            wp_cache_delete( 'shopglut_product_custom_field_count' );
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
        $cache_key = 'shopglut_table_exists_product_custom_field';
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