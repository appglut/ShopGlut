<?php
namespace Shopglut\enhancements\Filters;

use Shopglut\ShopGlutDatabase;

class FiltersEntity {

	protected static function getTable() {
		return ShopGlutDatabase::table_showcase_filters();
	}

	public static function retrieveAll( $limit = 0, $current_page = 1 ) {
		// Create cache key based on parameters
		$cache_key = 'shopglut_filters_' . md5( serialize( [ $limit, $current_page ] ) );

		// Try to get cached results
		$cached_result = wp_cache_get( $cache_key, 'shopglut_filters' );
		if ( false !== $cached_result ) {
			return $cached_result;
		}

		global $wpdb;

		$table_name = self::getTable();

		// Check cache first
		$cache_key = "shopglut_filters_{$limit}_{$current_page}";
		$cached_result = wp_cache_get( $cache_key, 'shopglut_filters' );

		if ( false !== $cached_result ) {
			return $cached_result;
		}

		// Validate input parameters
		$limit = absint( $limit );
		$current_page = absint( $current_page );

		// Build the query with proper placeholders for all parameters
		$sql = "SELECT * FROM %i WHERE 1=1 ORDER BY id DESC";
		$params = [$table_name];

		if ( $limit > 0 ) {
			$sql .= " LIMIT %d";
			$params[] = $limit;

			if ( $current_page > 1 ) {
				$offset = ( $current_page - 1 ) * $limit;
				$sql .= " OFFSET %d";
				$params[] = $offset;
			}
		}
		$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Direct query required for custom table operation, $sql and $params are safely constructed
			$wpdb->prepare( $sql, ...$params ), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Using $wpdb->prepare with proper placeholders, table name from trusted source
			'ARRAY_A'
		);

		// Cache the result for 5 minutes
		wp_cache_set( $cache_key, $result, 'shopglut_filters', 300 );

		$output = [];

		if ( is_array( $result ) && ! empty( $result ) ) {
			foreach ( $result as $item ) {
				$output[] = $item;
			}
		}

		// Cache the results for 5 minutes
		wp_cache_set( $cache_key, $output, 'shopglut_filters', 300 );

		return $output;
	}

	public static function retrieveAllCount() {
		// Try to get cached count
		$cache_key = 'shopglut_filters_count';
		$cached_count = wp_cache_get( $cache_key, 'shopglut_filters' );
		if ( false !== $cached_count ) {
			return $cached_count;
		}

		global $wpdb;

		$table_name = self::getTable();
		// Use escaped table name for backward compatibility
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
		$count = $wpdb->get_var( "SELECT COUNT(id) FROM `" . esc_sql($table_name) . "`" );

		// Cache the count for 5 minutes
		wp_cache_set( $cache_key, $count, 'shopglut_filters', 300 );

		return $count;
	}

	public static function delete_layout( $layout_id ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Using wpdb->delete is the correct method, cache is cleared after deletion
		$result = $wpdb->delete( self::getTable(), [ 'id' => $layout_id ], [ '%d' ] );

		// Clear related caches after deletion
		if ( $result ) {
			wp_cache_delete( 'shopglut_filters_count', 'shopglut_filters' );
			// Clear all cached filter lists (they may contain the deleted item)
			wp_cache_flush_group( 'shopglut_filters' );
		}

		return $result;
	}
}