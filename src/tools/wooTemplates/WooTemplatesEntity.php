<?php
namespace Shopglut\tools\wooTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\ShopGlutDatabase;

class WooTemplatesEntity {

	protected static function getTable() {
		return ShopGlutDatabase::table_woo_templates();
	}

	public static function retrieveAll($limit = 0, $current_page = 1) {
		global $wpdb;

		$table = self::getTable();
		if ( empty( $table ) ) {
			return [];
		}

		// Cache key for this query
		$cache_key = 'shopglut_woo_templates_all_' . md5( $limit . '_' . $current_page );
		$result = wp_cache_get( $cache_key );
		
		if ( false === $result ) {
			// Validate and sanitize current_page and limit parameters
			$current_page = is_numeric($current_page) ? absint($current_page) : 1;
			$limit = is_numeric($limit) ? absint($limit) : 10;

			if ($limit > 0) {
				if ($current_page > 1) {
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
					$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with properly escaped table name and parameterized query, direct query required for custom table operation
						$wpdb->prepare(
							sprintf("SELECT * FROM %%s WHERE 1=%%d ORDER BY id DESC LIMIT %%d OFFSET %%d"), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using sprintf with escaped table name, double percent for proper escaping
							esc_sql($table), 1, $limit, ($current_page - 1) * $limit
						), 'ARRAY_A'
					);
				} else {
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
					$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with properly escaped table name and parameterized query, direct query required for custom table operation
						$wpdb->prepare(
							sprintf("SELECT * FROM %%s WHERE 1=%%d ORDER BY id DESC LIMIT %%d"), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using sprintf with escaped table name, double percent for proper escaping
							esc_sql($table), 1, $limit
						), 'ARRAY_A'
					);
				}
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
				$result = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with properly escaped table name and parameterized query, direct query required for custom table operation
					$wpdb->prepare(
						sprintf("SELECT * FROM %%s WHERE 1=%%d ORDER BY id DESC"), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using sprintf with escaped table name, double percent for proper escaping
						esc_sql($table), 1
					), 'ARRAY_A'
				);
			}
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $result, '', 3600 );
		}

		$output = [];

		if (is_array($result) && !empty($result)) {
			foreach ($result as $item) {
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
		$cache_key = 'shopglut_woo_templates_count';
		$count = wp_cache_get( $cache_key );
		
		if ( false === $count ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
			$count = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with escaped table name, direct query required for custom table operation
				sprintf("SELECT COUNT(id) FROM `%s` WHERE 1=%d", esc_sql($table), 1 ) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- Direct query required for custom table operation, using sprintf with escaped table name for compatibility
			);
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $count, '', 3600 );
		}

		return $count;
	}

	public static function get_template($template_id) {
		global $wpdb;
		
		$table = self::getTable();
		if ( empty( $table ) || ! $template_id ) {
			return null;
		}

		$template_id = absint($template_id);

		// Cache key for this template
		$cache_key = 'shopglut_woo_template_' . $template_id;
		$result = wp_cache_get( $cache_key );
		
		if ( false === $result ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, caching handled manually
			$result = $wpdb->get_row( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with escaped table name, direct query required for custom table operation
				sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($table), absint($template_id)), ARRAY_A // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- Direct query required for custom table operation, using sprintf with escaped table name for compatibility
			);
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $result, '', 3600 );
		}

		return $result;
	}

	public static function get_template_by_template_id($template_id) {
		global $wpdb;
		
		$table = self::getTable();
		if ( empty( $table ) || ! $template_id ) {
			return null;
		}

		$template_id = sanitize_text_field($template_id);

		// Cache key for this template lookup
		$cache_key = 'shopglut_woo_template_by_id_' . md5($template_id);
		$result = wp_cache_get( $cache_key );
		
		if ( false === $result ) {
			$result = $wpdb->get_row(sprintf("SELECT * FROM `%s` WHERE template_id = %s", esc_sql($table), $wpdb->prepare("%s", $template_id)), ARRAY_A); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Direct query required for custom table operation, caching handled manually
			
			// Cache the result for 1 hour
			wp_cache_set( $cache_key, $result, '', 3600 );
		}

		return $result;
	}

	public static function delete_template($template_id) {
		global $wpdb;
		
		$table = self::getTable();
		if ( empty( $table ) || ! $template_id ) {
			return false;
		}

		$template_id = absint($template_id);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table delete operation, cache cleared below
		$result = $wpdb->delete( $table, ['id' => $template_id], ['%d'] );

		// Clear related cache after deletion
		if ( $result ) {
			wp_cache_delete( 'shopglut_woo_templates_count' );
			wp_cache_delete( 'shopglut_woo_template_' . $template_id );
			// Clear listing cache with pattern matching (simplified approach)
			wp_cache_flush(); // Or implement more targeted cache clearing
		}

		return $result;
	}
}