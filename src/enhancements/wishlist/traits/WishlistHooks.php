<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistHooks {

    public function shopglut_register_wishlist_buttons() {
		// Only proceed if WooCommerce is active and we're on frontend
		if ( ! function_exists( 'wc_get_product' ) || is_admin() ) {
			return;
		}
		
		// Add notification container to footer for all pages with wishlist buttons
		add_action( 'wp_footer', [ $this, 'shopglut_add_notification_container' ] );

		// Add wishlist button to product page
		if ( ! empty( $this->enhancements['wishlist-enable-product-page'] ) ) {
			$position = $this->enhancements['wishlist-product-position'] ?? 'after-cart';
			
			// Check for out of stock condition only on single product pages
			if ( is_product() ) {
				global $product;
				
				// If global product is not available, try to get it
				if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
					$product_id = get_the_ID();
					if ( $product_id ) {
						$product = wc_get_product( $product_id );
					}
				}
				
				// Double-check that we have a valid WC_Product object
				if ( $product && is_a( $product, 'WC_Product' ) ) {
					if ( isset( $this->enhancements['wishlist-general-outofstock'] ) && 
						$this->enhancements['wishlist-general-outofstock'] == '0' && 
						! $product->is_in_stock() ) {
						$position = 'after-product-meta'; 
					}
				}
			}
			
			$hook = $this->determine_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_single' ], 15 );
		}

		// Add wishlist button to shop page
		if ( ! empty( $this->enhancements['wishlist-enable-shop-page'] ) ) {
			$position = $this->enhancements['wishlist-shop-position'] ?? 'after-cart';
			
			// Check for out of stock condition on shop pages
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				global $product;
				
				// If global product is not available, try to get it
				if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
					$product_id = get_the_ID();
					if ( $product_id ) {
						$product = wc_get_product( $product_id );
					}
				}
				
				// Double-check that we have a valid WC_Product object
				if ( $product && is_a( $product, 'WC_Product' ) ) {
					if ( isset( $this->enhancements['wishlist-general-outofstock'] ) && 
						$this->enhancements['wishlist-general-outofstock'] == '0' && 
						! $product->is_in_stock() ) {
						$position = 'after-product-meta'; 
					}
				}
			}
			
			$hook = $this->determine_shop_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_shop' ], 15 );
		}

		// Add wishlist button to category/archive pages
		if ( ! empty( $this->enhancements['wishlist-enable-archive-page'] ) ) {
			$position = $this->enhancements['wishlist-archive-position'] ?? 'after-cart';
			
			// Check for out of stock condition on archive pages
			if ( is_product_category() || is_product_tag() || is_product_taxonomy() ) {
				global $product;
				
				// If global product is not available, try to get it
				if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
					$product_id = get_the_ID();
					if ( $product_id ) {
						$product = wc_get_product( $product_id );
					}
				}
				
				// Double-check that we have a valid WC_Product object
				if ( $product && is_a( $product, 'WC_Product' ) ) {
					if ( isset( $this->enhancements['wishlist-general-outofstock'] ) && 
						$this->enhancements['wishlist-general-outofstock'] == '0' && 
						! $product->is_in_stock() ) {
						$position = 'after-product-meta'; 
					}
				}
			}
			
			$hook = $this->determine_archive_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_category' ], 20 );
		}
	}

	private function determine_hook_position( $position ) {

			switch ( $position ) {
				case 'before-cart':
					return 'woocommerce_before_add_to_cart_button';
				case 'after-product-meta':
					return 'woocommerce_product_meta_end';
				default:
					return 'woocommerce_after_add_to_cart_button';
			}
	}

	private function determine_shop_hook_position( $position ) {
			switch ( $position ) {
				case 'before-cart':
					// Hook directly before the Add to Cart button in the shop loop
					return 'woocommerce_after_shop_loop_item_title';
				case 'after-product-meta':
					// Hook after product meta or title, closest to cart button in the loop
					return 'woocommerce_after_shop_loop_item';
				default:
					// Hook after the Add to Cart button by default
					return 'woocommerce_after_shop_loop_item';
			}
	}

	private function determine_archive_hook_position( $position ) {
		switch ( $position ) {
			case 'before-cart':
				// Hook directly before the Add to Cart button in the shop loop
				return 'woocommerce_after_shop_loop_item_title';
			case 'after-product-meta':
				// Hook after product meta or title, closest to cart button in the loop
				return 'woocommerce_after_shop_loop_item';
			default:
				// Hook after the Add to Cart button by default
				return 'woocommerce_after_shop_loop_item';
		}
    }
    
    /**
     * Add notification container to footer
     */
    public function shopglut_add_notification_container() {
        // Only add on pages with wishlist functionality
        if ( is_product() || is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
            echo '<div id="shopglut-wishlist-notification" style="display: none;"></div>';
        }
    }

}
