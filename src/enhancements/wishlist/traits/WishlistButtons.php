<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistButtons {


	public function shopglut_add_wishlist_button_single() {
		// Ensure WooCommerce is active
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		global $wpdb, $product; // Access the global $wpdb and product object

		if ( $this->enhancements['wishlist-general-outofstock'] == '1' && ! $product->is_in_stock() ) {
			return; // Exit the function without displaying the wishlist button
		}

		// Retrieve guest or current user ID
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id(); // Get the current product ID

		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$query = "SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)";
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$existing_entry = $wpdb->get_var( $wpdb->prepare(
			$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$user_id,
			$product_id
		) );

		$button_class = '';
		$href = '#';

		$move_href = '#';
		$move_button_class = "move_to_list";
		$move_button_text = __( "Move to List", 'shopglut' );

		if ( $existing_entry > 0 ) {
			$icon = $this->enhancements['wishlist-product-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->enhancements['wishlist-product-button-text-after-added'] ?? __( 'Added to Wishlist', 'shopglut' );
			$second_click_action = $this->enhancements['wishlist-product-second-click'] ?? 'remove-wishlist';

			// Handle the second click action based on the existing entry
			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->enhancements['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class = "already-added";
					break;
				default:
					$button_class = "shopgw-added";
					$href = '#';
					break;
			}
		} else {
			$icon = $this->enhancements['wishlist-product-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->enhancements['wishlist-product-button-text'] ?? __( 'Add to Wishlist', 'shopglut' );
			$button_class = 'not-shopgw-added';
			$href = '#';
		}

		if ( $this->enhancements['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$href = wp_login_url( site_url( $request_uri ) );
			$button_class = "login-required";
			$button_text = __( 'Login Required', 'shopglut' );
			$icon = $this->enhancements['wishlist-require-login-btn-icon'];
			$move_href = wp_login_url( site_url( $request_uri ) );
			$move_button_class = "login-required";
			$move_button_text = __( "Move to List", 'shopglut' );
		}

		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist single-product'>";
		if ( $this->enhancements['wishlist-product-option'] === 'only-icon' ) {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->enhancements['wishlist-product-option'] === 'button-with-icon' ) {
			$icon_position = $this->enhancements['wishlist-product-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";

	
		do_action('shopglut_wishlist_pro_features_loaded', $product_id, 'single-product');

		
		echo "</div>";

	}

	public function shopglut_add_wishlist_button_shop() {
		global $wpdb, $product, $shopglut_rendering_shop_layout;

		// Detect if the context is an AJAX request
		$is_ajax_request = defined( 'DOING_AJAX' ) && DOING_AJAX;

		// Check if we're rendering from a shop layout template (shortcode/custom page)
		$is_shop_layout_template = isset($shopglut_rendering_shop_layout) && $shopglut_rendering_shop_layout;

		if ( $this->enhancements['wishlist-general-outofstock'] == '1' && ! $product->is_in_stock() ) {
			return; // Exit the function without displaying the wishlist button
		}


		// Check WooCommerce context and product availability
		// Allow rendering if: is_shop() OR AJAX request OR shop layout template
		if ( ! function_exists( 'is_woocommerce' ) || ! $product || ( ! is_shop() && ! $is_ajax_request && ! $is_shop_layout_template ) ) {
			return;
		}
		// Retrieve guest or current user ID
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id();

		// Check if product is already in the wishlist
		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$query = "SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)";
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$existing_entry = $wpdb->get_var( $wpdb->prepare(
			$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$user_id,
			$product_id
		) );

		// Define button settings based on whether the product is in the wishlist
		if ( $existing_entry > 0 ) {
			$icon = $this->enhancements['wishlist-shop-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->enhancements['wishlist-shop-button-text-after-added'] ?? __( 'Added To Wishlist', 'shopglut' );
			$second_click_action = $this->enhancements['wishlist-shop-second-click'] ?? 'remove-wishlist';
			$button_class = "shopgw-added";

			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->enhancements['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class .= " already-added";
					$href = '#';
					break;
				default:
					$href = '#';
					break;
			}
		} else {
			$icon = $this->enhancements['wishlist-shop-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->enhancements['wishlist-shop-button-text'] ?? __( 'Add To Wishlist', 'shopglut' );
			$button_class = "not-shopgw-added";
			$href = '#';
		}

		// Add login requirement if necessary
		if ( $this->enhancements['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$href = wp_login_url( site_url( $request_uri ) );
			$button_class = "login-required";
			$button_text = __( 'Login Required', 'shopglut' );
			$icon = $this->enhancements['wishlist-require-login-btn-icon'];
		}

		// Render the wishlist button
		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist shop-page'>";
		if ( $this->enhancements['wishlist-shop-option'] === 'only-icon' ) {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->enhancements['wishlist-shop-option'] === 'button-with-icon' ) {
			$icon_position = $this->enhancements['wishlist-shop-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";

		// Optionally render MoveList button
		if ( $this->enhancements['wishlist-shop-enable-movelist'] === '1' && is_user_logged_in() ) {
			$move_button_text = __( "Move to List", 'shopglut' );
			echo "<div class='shopglut_wishlist_movelist shop-page'>";
			echo '<a href="#" class="button move_to_list" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $move_button_text ) . '</span></a>';
			echo "</div>";
		}

		echo "</div>";
	}

	public function shopglut_add_wishlist_button_category() {
		global $wpdb, $product, $shopglut_rendering_shop_layout;

		// Check if we're rendering from a shop layout template
		$is_shop_layout_template = isset($shopglut_rendering_shop_layout) && $shopglut_rendering_shop_layout;

		// Ensure WooCommerce is active and the current page is a product category or archive page
		// Allow rendering if shop layout template flag is set
		if ( ! function_exists( 'is_woocommerce' ) || ! $product || ( ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() && ! $is_shop_layout_template ) ) {
			return;
		}

			if ( $this->enhancements['wishlist-general-outofstock'] == '1' && ! $product->is_in_stock() ) {
			return; // Exit the function without displaying the wishlist button
		}


		// Check if the user wants to filter by category or tag
		$filter_option = $this->enhancements['wishlist-archive-select-cat-option'] ?? 'all-categories';

		// Handle Category-specific display setting
		if ( $filter_option === 'select-category' ) {
			// Ensure $categories is an array
			$categories = (array) ( $this->enhancements['wishlist-archive-select-category'] ?? [] );
			$product_categories = wp_get_post_terms( $product->get_id(), 'product_cat', [ 'fields' => 'ids' ] );

			if ( ! array_intersect( $product_categories, $categories ) ) {
				return; // Exit if the product category is not in the selected categories
			}
		}

		// Handle Tag-specific display setting
		if ( $filter_option === 'select-tag' ) {
			// Ensure $tags is an array
			$tags = (array) ( $this->enhancements['wishlist-archive-select-category'] ?? [] );
			$product_tags = wp_get_post_terms( $product->get_id(), 'product_tag', [ 'fields' => 'ids' ] );

			if ( ! array_intersect( $product_tags, $tags ) ) {
				return; // Exit if the product tag is not in the selected tags
			}
		}

		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id();

		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$query = "SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)";
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$existing_entry = $wpdb->get_var( $wpdb->prepare(
			$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$user_id,
			$product_id
		) );

		if ( $existing_entry > 0 ) {
			$icon = $this->enhancements['wishlist-archive-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->enhancements['wishlist-archive-button-text-after-added'] ?? __( 'Added To Wishlist', 'shopglut' );
			$second_click_action = $this->enhancements['wishlist-archive-second-click'] ?? 'remove-wishlist';
			$button_class = "shopgw-added";

			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->enhancements['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class .= " already-added";
					$href = '#';
					break;
				default:
					$href = '#';
					break;
			}
		} else {
			$icon = $this->enhancements['wishlist-archive-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->enhancements['wishlist-archive-button-text'] ?? __( 'Add To Wishlist', 'shopglut' );
			$button_class = "not-shopgw-added";
			$href = '#';
		}

		if ( $this->enhancements['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$href = wp_login_url( site_url( $request_uri ) );
			$button_class = "login-required";
			$button_text = __( 'Login Required', 'shopglut' );
			$icon = $this->enhancements['wishlist-require-login-btn-icon'];
		}

		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist archive-page'>";
		if ( $this->enhancements['wishlist-archive-option'] === 'only-icon' ) {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->enhancements['wishlist-archive-option'] === 'button-with-icon' ) {
			$icon_position = $this->enhancements['wishlist-archive-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";

		if ( $this->enhancements['wishlist-archive-enable-movelist'] === '1' && is_user_logged_in() ) {
			$move_button_text = __( "Move to List", 'shopglut' );
			echo "<div class='shopglut_wishlist_movelist archive-page'>";
			echo '<a href="#" class="button move_to_list" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $move_button_text ) . '</span></a>';
			echo "</div>";
		}

		echo "</div>";
	}

}