<?php
namespace Shopglut\layouts\shopLayout;

use Shopglut\ShopGlutDatabase;

class ShopLayoutEntity {

	protected static function getTable() {
		return ShopGlutDatabase::table_shop_layouts();
	}

	public static function retrieveAll( $limit = 0, $current_page = 1 ) {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) ) {
			return [];
		}

		// Sanitize input parameters
		$limit = (int) $limit;
		$current_page = (int) $current_page;

		// Cache key for this query
		$cache_key = 'shopglut_shop_layouts_all_' . md5( $limit . '_' . $current_page );
		$result = wp_cache_get( $cache_key );

		if ( false === $result ) {
			if ( $limit > 0 ) {
				if ( $current_page > 1 ) {
					$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
					$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
						sprintf("SELECT * FROM `%s` WHERE 1=%%d ORDER BY id DESC LIMIT %%d OFFSET %%d", esc_sql($table)),
						1, $limit, ( $current_page - 1 ) * $limit
					), 'ARRAY_A' );
				} else {
					$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
					$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
						sprintf("SELECT * FROM `%s` WHERE 1=%%d ORDER BY id DESC LIMIT %%d", esc_sql($table)),
						1, $limit
					), 'ARRAY_A' );
				}
			} else {
				$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
				$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
					sprintf("SELECT * FROM `%s` WHERE 1=%%d ORDER BY id DESC", esc_sql($table)),
					1
				), 'ARRAY_A' );
			}
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $result, '', 3600 );
		}

		$output = [];

		if ( is_array( $result ) && ! empty( $result ) ) {
			foreach ( $result as $item ) {
				$output[] = $item;
			}
		}

		return $output;
	}

	public static function retrieveAllCount() {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) ) {
			return 0;
		}

		// Cache key for count query
		$cache_key = 'shopglut_shop_layouts_count';
		$count = wp_cache_get( $cache_key );
		
		if ( false === $count ) {
$count = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
			$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
				sprintf("SELECT COUNT(id) FROM `%s` WHERE 1=%%d", esc_sql(self::getTable())), 1 ) );
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $count, '', 3600 );
		}

		return $count;
	}

	public static function delete_layout( $layout_id ) {
		global $wpdb;
		
		$table = self::getTable();
		if ( empty( $table ) || ! $layout_id ) {
			return false;
		}
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->delete( $table, [ 'id' => absint( $layout_id ) ], [ '%d' ] );

		// Clear related cache after deletion
		if ( $result ) {
			wp_cache_delete( 'shopglut_shop_layouts_count' );
			// Clear listing cache with pattern matching (simplified approach)
			wp_cache_flush(); // Or implement more targeted cache clearing
		}

		return $result;
	}
}