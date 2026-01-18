<?php
namespace Shopglut\enhancements\ProductBadges;

use Shopglut\ShopGlutDatabase;

class BadgeEntity {

	protected static function getTable() {
		return ShopGlutDatabase::table_badges_showcase();
	}

	public static function retrieveAll( $limit = 0, $current_page = 1 ) {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) ) {
			return [];
		}

		// Create cache key
		$cache_key = "shopglut_badges_all_{$limit}_{$current_page}";
		$cached_result = wp_cache_get( $cache_key, 'shopglut_badges' );

		if ( false !== $cached_result ) {
			return $cached_result;
		}

		// Validate input parameters
		$limit = absint( $limit );
		$current_page = absint( $current_page );

		// Build the query with proper placeholders for all parameters
		$sql = "SELECT * FROM %i WHERE 1=1 ORDER BY id DESC";
		$params = [$table];

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
			$wpdb->prepare( $sql, ...$params ), 'ARRAY_A' // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Using $wpdb->prepare with proper placeholders, table name from trusted source
		);

		$output = [];

		if ( is_array( $result ) && ! empty( $result ) ) {
			foreach ( $result as $item ) {
				$output[] = $item;
			}
		}

		// Cache for 15 minutes
		wp_cache_set( $cache_key, $output, 'shopglut_badges', 15 * MINUTE_IN_SECONDS );

		return $output;
	}

	public static function retrieveAllCount() {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) ) {
			return 0;
		}

		// Create cache key
		$cache_key = 'shopglut_badges_count';
		$cached_count = wp_cache_get( $cache_key, 'shopglut_badges' );

		if ( false !== $cached_count ) {
			return (int) $cached_count;
		}

		// Use escaped table name for backward compatibility
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
		$count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(id) FROM `" . esc_sql($table) . "` WHERE 1=%d",
				1
			)
		);

		// Cache for 15 minutes
		wp_cache_set( $cache_key, $count, 'shopglut_badges', 15 * MINUTE_IN_SECONDS );

		return $count;
	}

	public static function delete_badge( $badge_id ) {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) || ! $badge_id ) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table delete operation
		$result = $wpdb->delete( $table, [ 'id' => absint( $badge_id ) ], [ '%d' ] );

		// Clear cache on delete
		if ( $result ) {
			wp_cache_delete( 'shopglut_badges_count', 'shopglut_badges' );
			// Clear all possible cache keys for retrieveAll
			wp_cache_flush_group( 'shopglut_badges' );
		}

		return $result;
	}
}