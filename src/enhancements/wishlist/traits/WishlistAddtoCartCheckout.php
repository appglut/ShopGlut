<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistAddtoCartCheckout{

    public function shopglut_wishlist_add_to_cart() {
		// Verify nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_wishlist_nonce' ) ) {
			wp_send_json_error( 'Security check failed.' );
			return;
		}

		// Validate and sanitize input
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( 'Product ID is required.' );
			return;
		}

		$product_id = intval( sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) );
		$quantity = isset( $_POST['quantity'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) ) : 1;
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();

		if ( $user_id && $product_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity ); // Add product with specified quantity
			wp_send_json_success();
		} else {
			wp_send_json_error( 'Invalid product ID or user not logged in.' );
		}
	}

	public function shopglut_add_to_cart_and_checkout() {
		// Verify nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_wishlist_nonce' ) ) {
			wp_send_json_error( 'Security check failed.' );
			return;
		}

		// Validate and sanitize input
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( 'Product ID is required.' );
			return;
		}

		if ( ! isset( $_POST['quantity'] ) ) {
			wp_send_json_error( 'Quantity is required.' );
			return;
		}

		$product_id = intval( sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) );
		$quantity = intval( sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) );

		if ( $product_id && $quantity > 0 ) {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}

		// Return the checkout URL as a JSON response
		$checkout_url = wc_get_checkout_url();
		wp_send_json_success( [ 'redirect_url' => $checkout_url ] );
	}

}