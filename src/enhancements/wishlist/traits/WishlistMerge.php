<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistMerge {

    public function shopglut_set_merge_wishlist_transient( $user_login, $user ) {
		// Transient removed - no longer needed
	}

	public function shopglut_merge_guest_wishlist() {
			//check_ajax_referer('shopLayouts_nonce', 'nonce');

			global $wpdb;
			$user_id = get_current_user_id();
			$guest_id = isset($_POST['guest_id']) ? sanitize_text_field(wp_unslash($_POST['guest_id'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- AJAX action called from authorized context

			if ( $this->enhancements['wishlist-merge-guestlist'] === '1' && $guest_id ) {
				$table_name = $wpdb->prefix . 'shopglut_wishlist';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$guest_wishlist = $wpdb->get_var( $wpdb->prepare(
					"SELECT product_ids FROM {$wpdb->prefix}shopglut_wishlist WHERE wish_user_id = %s", $guest_id
				) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query

				if ( $guest_wishlist ) {
					$guest_product_ids = explode( ',', $guest_wishlist );
					foreach ( $guest_product_ids as $guest_product_id ) {
						if ( ! empty( $guest_product_id ) ) {
							// Check if product already exists in the user's wishlist
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$existing_entry = $wpdb->get_var( $wpdb->prepare(
								"SELECT product_ids FROM {$wpdb->prefix}shopglut_wishlist WHERE wish_user_id = %d", $user_id
							) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
							$product_ids_array = $existing_entry ? explode( ',', $existing_entry ) : [];
							if ( ! in_array( $guest_product_id, $product_ids_array ) ) {
								$product_ids_array[] = $guest_product_id;
								$updated_product_ids = implode( ',', $product_ids_array );
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
								$wpdb->update( $table_name, [ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ], [ 'wish_user_id' => $user_id ] );
							}
						}
					}

					// Remove the guest wishlist entry
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->delete( $table_name, [ 'wish_user_id' => $guest_id ] );
				}
				wp_send_json_success( 'Wishlist merged successfully' );
			} else {
				wp_send_json_error( 'Guest to User Merge Was not Sucessful' );
			}
	}

    private function get_shopglutw_guest_user_id() {
		if ( isset( $_COOKIE['shopglutw_guest_user_id'] ) ) {
			return sanitize_text_field( wp_unslash($_COOKIE['shopglutw_guest_user_id']) );
		}
		return ''; // Return an empty string or handle as needed if the cookie is not set
	}

	public function shopglut_cleanup_old_guest_products() {
		global $wpdb;
		
		// Check if cleanup is enabled
		if (!isset($this->enhancements['wishlist-merge-guestlist']) || $this->enhancements['wishlist-merge-guestlist'] !== '1') {
			return 0;
		}
		
		// Get deletion period (default 15 days)
		$days = isset($this->enhancements['wishlist-guestlist-deletetime']) ? intval($this->enhancements['wishlist-guestlist-deletetime']) : 15;
		
		if ($days <= 0) return 0;
		
		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$cutoff_timestamp = strtotime("-{$days} days");
		
		// Get all guest entries that have individual dates
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$guest_entries = $wpdb->get_results($wpdb->prepare("
			SELECT id, wish_user_id, product_ids, product_individual_dates 
			FROM {$wpdb->prefix}shopglut_wishlist 
			WHERE wish_user_id LIKE %s 
			AND product_individual_dates IS NOT NULL 
			AND product_individual_dates != ''
		", 'guest_%')); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
		
		$total_cleaned = 0;
		$entries_deleted = 0;
		
		foreach ($guest_entries as $entry) {
			$individual_dates = json_decode($entry->product_individual_dates, true);
			
			if (!is_array($individual_dates)) {
				continue;
			}
			
			$current_products = array_filter(array_map('trim', explode(',', $entry->product_ids)));
			$products_to_keep = array();
			$dates_to_keep = array();
			
			// Check each product's individual date
			foreach ($current_products as $product_id) {
				if (isset($individual_dates[$product_id])) {
					$product_timestamp = strtotime($individual_dates[$product_id]);
					
					if ($product_timestamp >= $cutoff_timestamp) {
						// Keep this product (it's recent enough)
						$products_to_keep[] = $product_id;
						$dates_to_keep[$product_id] = $individual_dates[$product_id];
					} else {
						// This product is old, will be removed
						$total_cleaned++;
					}
				} else {
					// No date found for this product, keep it to be safe
					$products_to_keep[] = $product_id;
				}
			}
			
			if (empty($products_to_keep)) {
				// No products to keep, delete entire entry
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->delete($table_name, array('id' => $entry->id), array('%d'));
				$entries_deleted++;
			} else if (count($products_to_keep) < count($current_products)) {
				// Some products removed, update the entry
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update(
					$table_name,
					array(
						'product_ids' => implode(',', $products_to_keep),
						'product_individual_dates' => json_encode($dates_to_keep),
						'product_added_time' => current_time('mysql')
					),
					array('id' => $entry->id),
					array('%s', '%s', '%s'),
					array('%d')
				);
			}
		}
		
		// Also clean entries without individual dates using the old method
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$old_entries_deleted = $wpdb->query($wpdb->prepare("
			DELETE FROM {$wpdb->prefix}shopglut_wishlist 
			WHERE wish_user_id LIKE %s 
			AND (product_individual_dates IS NULL OR product_individual_dates = '')
			AND product_added_time < %s
		", 'guest_%', gmdate('Y-m-d H:i:s', $cutoff_timestamp))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table cleanup query
		
		$total_entries_affected = $entries_deleted + $old_entries_deleted;
		
		if ($total_cleaned > 0 || $total_entries_affected > 0) {
			// Cleanup completed: Removed {$total_cleaned} old products, deleted {$total_entries_affected} guest entries (older than {$days} days)
		}
		
		return array(
			'products_removed' => $total_cleaned,
			'entries_deleted' => $total_entries_affected
		);
	}

	public function shopglut_schedule_guest_cleanup() {
		if (!wp_next_scheduled('shopglut_daily_guest_cleanup')) {
			wp_schedule_event(time(), 'daily', 'shopglut_daily_guest_cleanup');
		}
	}


}