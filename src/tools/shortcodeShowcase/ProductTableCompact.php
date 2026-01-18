<?php
namespace Shopglut\shortcodeShowcase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductTableCompact {
	/**
	 * Constructor to initialize the shortcode
	 */
	public function __construct() {
		// Register the shortcode
		add_shortcode( 'shopglut_product_table_compact', array( $this, 'render_product_table_compact' ) );

		// Register Ajax handlers for quantity updates
		add_action( 'wp_ajax_update_cart_quantity', array( $this, 'update_cart_quantity' ) );
		add_action( 'wp_ajax_nopriv_update_cart_quantity', array( $this, 'update_cart_quantity' ) );
	}

	/**
	 * Handle quantity updates via Ajax
	 */
	public function update_cart_quantity() {
		check_ajax_referer( 'shopglut-product-table-ajax-nonce', 'security' );

		if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['quantity'] ) ) {
			wp_send_json_error( 'Invalid parameters' );
			return;
		}

		$product_id = absint( $_POST['product_id'] );
		$quantity = absint( $_POST['quantity'] );

		// Update cart quantity
		if ( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product_id ) ) ) {
			// If product is in cart, update quantity
			$cart_item_key = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product_id ) );
			WC()->cart->set_quantity( $cart_item_key, $quantity );
		}

		wp_send_json_success();
	}

	/**
	 * Render the compact product table shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output of the compact product table
	 */
	public function render_product_table_compact( $atts ) {
		// Enqueue required scripts and styles

		// Default shortcode attributes
		$default_atts = array(
			'limit' => 10,                // Number of products to display
			'orderby' => 'date',          // Order products by
			'order' => 'DESC',            // Order direction
			'category' => '',             // Filter by category slug
			'tag' => '',                  // Filter by tag
			'ids' => '',                  // Specific product IDs
			'paginate' => 'true',         // Enable pagination
			'show_properties' => 'true',  // Show product properties
			'show_image' => 'true',       // Show product image
			'show_price' => 'true',       // Show product price
			'show_quantity' => 'true',    // Show quantity selector
			'show_actions' => 'true',     // Show action buttons
			'show_description' => 'true'  // Show product description
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_product_table_compact' );

		// Convert string values to boolean
		$atts['paginate'] = filter_var( $atts['paginate'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_properties'] = filter_var( $atts['show_properties'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_image'] = filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_price'] = filter_var( $atts['show_price'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_quantity'] = filter_var( $atts['show_quantity'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_actions'] = filter_var( $atts['show_actions'], FILTER_VALIDATE_BOOLEAN );
		$atts['show_description'] = filter_var( $atts['show_description'], FILTER_VALIDATE_BOOLEAN );

		// Prepare query arguments
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => $atts['orderby'],
			'order' => $atts['order'],
			'posts_per_page' => $atts['limit']
		);

		// Filter by category if specified
		if ( ! empty( $atts['category'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => explode( ',', $atts['category'] ),
				'operator' => 'IN',
			);
		}

		// Filter by tag if specified
		if ( ! empty( $atts['tag'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => explode( ',', $atts['tag'] ),
				'operator' => 'IN',
			);
		}

		// Filter by specific product IDs if specified
		if ( ! empty( $atts['ids'] ) ) {
			$args['post__in'] = explode( ',', $atts['ids'] );
		}

		// Get products
		$products = new \WP_Query( $args );

		// Start output buffering
		ob_start();

		// Start container
		echo '<section class="container">';
		echo '<div class="section-cont">';

		// Check if products exist
		if ( $products->have_posts() ) :
			// Start product items
			echo '<div class="shopg-items section-items shopg-tb">';

			// Loop through products
			while ( $products->have_posts() ) :
				$products->the_post();
				global $product;

				// Skip if not a valid product
				if ( ! is_a( $product, 'WC_Product' ) ) {
					continue;
				}

				// Product item
				echo '<div class="shopgtb-i">';

				// Product top section
				echo '<div class="shopgtb-i-top">';

				// Toggle button with visible expand icon
				echo '<button class="shopgtb-i-toggle" type="button"><i class="fa fa-chevron-down"></i></button>';

				// Product title
				echo '<h3 class="shopgtb-i-ttl"><a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html( $product->get_name() ) . '</a></h3>';


				// Product price and quantity
				if ( $atts['show_price'] || $atts['show_quantity'] ) {
					echo '<div class="shopgtb-i-info">';

					// Show price if enabled
					if ( $atts['show_price'] ) {
						echo '<span class="shopgtb-i-price">';

						// Regular and sale price
						if ( $product->is_on_sale() ) {
							echo '<b>' . wp_kses_post( wc_price( $product->get_sale_price() ) ) . '</b>';
							echo '<del>' . wp_kses_post( wc_price( $product->get_regular_price() ) ) . '</del>';
						} else {
							echo '<b>' . wp_kses_post( wc_price( $product->get_regular_price() ) ) . '</b>';
						}

						echo '</span>';
					}

					// Quantity selector if enabled
					if ( $atts['show_quantity'] ) {
						$cart_item_key = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) );
						$current_qty = $cart_item_key ? WC()->cart->get_cart()[ $cart_item_key ]['quantity'] : 1;

						echo '<p class="shopgtb-i-qnt" data-product-id="' . esc_attr( $product->get_id() ) . '">';
						echo '<input value="' . esc_attr( $current_qty ) . '" type="text" min="1" class="qty-input">';
						echo '<a href="#" class="shopgtb-i-plus"><i class="fa fa-angle-up"></i></a>';
						echo '<a href="#" class="shopgtb-i-minus"><i class="fa fa-angle-down"></i></a>';
						echo '</p>';
					}

					echo '</div>'; // End of shopgtb-i-info
				}

				$wishlist_options = get_option( 'agshopglut_wishlist_options' );
				$product_id = $product->get_id();
				$product_link = get_permalink( $product_id );

				global $wpdb;
				$user_actions = $wpdb->prefix . 'shopglut_user_actions';
				$user_id = get_current_user_id();
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for user wishlist data
				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT product_id FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND action_type = %s",
					$user_id,
					'wishlist'
				) );

				$wishlist_product_ids = array();
				if ( $results ) {
					foreach ( $results as $row ) {
						$wishlist_product_ids[] = $row->product_id;
					}
				}

				$product_type = $product->get_type();

				// Get the WooCommerce cart instance
				$cart = WC()->cart;
				$is_in_cart = false;
				if ( is_object( $cart ) ) {
					$cart_items = $cart->get_cart();
					foreach ( $cart_items as $cart_item ) {
						if ( $cart_item['product_id'] == $product_id ) {
							$is_in_cart = true;
							break;
						}
					}
				}
				// Product actions
				if ( $atts['show_actions'] ) { ?>
					<div class="shopgtb-i-action">
						<a title="Wishlist" <?php if ( $wishlist_options['wishlist-require-login'] == true && ! is_user_logged_in() ) : ?>
								href="<?php echo esc_url( wp_login_url( site_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) ) ); ?>" <?php else : ?> class="wishlist"
								href="#" data-product-id="<?php echo esc_attr( $product_id ); ?>" <?php endif; ?>>
							<?php if ( $wishlist_options['wishlist-require-login'] == true && ! is_user_logged_in() ) : ?>
								<div class="locked">
									<i class="fa-solid fa-lock"></i>
								</div>
							<?php else : ?>
								<?php if ( in_array( $product_id, $wishlist_product_ids ) ) : ?>
									<div class="added shopgtb-i-favorites">
										<span>Wishlist</span>
										<i class="fa-solid fa-heart"></i>
									</div>
								<?php else : ?>
									<div class="not-added shopgtb-i-favorites">
										<span>Wishlist</span>
										<i class="fa-regular fa-heart"></i>
									</div>
								<?php endif; ?>

							<?php endif; ?>
						</a>
						<?php
						echo '<a href="#"  class="shopgtb-i-compare" data-product-id="' . esc_attr( $product->get_id() ) . '" class="shopgtb-i-comapre"><span>Compare</span><i class="fa fa-bar-chart"></i></a>';
						echo '<a href="#" class="qview-btn shopgtb-i-qview" data-product-id="' . esc_attr( $product->get_id() ) . '"><span>Quick View</span><i class="fa fa-search"></i></a>';

						// Add to cart button
						// echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" data-quantity="1" class="shopgtb-i-buy ' . esc_attr( $product->get_type() ) . ' add_to_cart_button" data-product_id="' . esc_attr( $product->get_id() ) . '"><span>Add to cart</span><i class="fa fa-shopping-basket"></i></a>';
	
						?>
						<div class="box-cart shopgtb-i-buy">
							<div class="product-cart-action">
								<?php if ( $product_type === 'simple' ) : ?>
									<?php if ( $is_in_cart ) : ?>
										<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="add-to-cart btn btn-cart shopgtb-i-buy">
											<i class="fa-solid fa-cart-plus"></i>
											<span class="cart-title">
												<?php echo esc_html__( 'Added to cart', 'shopglut' ); ?>
											</span>
										</a>
									<?php else : ?>
										<a href="#" data-product-id="<?php echo esc_attr( $product_id ); ?>"
											class="add-to-cart ajax-spin-cart btn btn-cart shopgtb-i-buy">
											<div class="cart-contents">
												<i class="fa fa-shopping-cart"></i><span class="cart-title">
													<?php echo esc_html__( 'Add to cart', 'shopglut' ); ?>
												</span>
												<span class="cart-loading" style="display: none;">
													<i class="fa fa-spinner animated rotateIn infinite"></i>
													<?php echo esc_html__( 'Wait..', 'shopglut' ); ?>
												</span>

												<span class="cart-added" style="display: none;">
													<?php echo esc_html__( 'Add to cart', 'shopglut' ); ?>
												</span>
												<span class="cart-unavailable" style="display: none;">
													<i class="fa fa-shopping-basket"></i>

												</span>
											</div>
										</a>
									<?php endif; ?>
								<?php elseif ( $product_type === 'external' ) : ?>
									<a href="<?php echo esc_url( $product->get_product_url() ); ?>"
										class="external-product btn btn-cart shopgtb-i-buy" target="_blank">
										<i class="fa fa-external-link"></i>
										<span class="cart-title">
											<?php echo esc_html__( 'External Product', 'shopglut' ); ?>
										</span>
									</a>
								<?php elseif ( $product_type === 'grouped' ) : ?>
									<a href="<?php echo esc_url( $product_link ); ?>" class="grouped-product btn btn-cart shopgtb-i-buy">
										<i class="fa fa-cubes"></i>
										<span class="cart-title">
											<?php echo esc_html__( 'Grouped Product', 'shopglut' ); ?>
										</span>
									</a>
								<?php elseif ( $product_type === 'variable' ) : ?>
									<a href="<?php echo esc_url( $product_link ); ?>" class="variable-product btn btn-cart shopgtb-i-buy">
										<i class="fa-solid fa-sitemap"></i>
										<span class="cart-title">
											<?php echo esc_html__( 'Variable Product', 'shopglut' ); ?>
										</span>
									</a>
								<?php else : ?>
									<div class="add-to-cart not-allowed-purchase btn btn-cart">

										<a href="<?php echo esc_url( $product_link ); ?>" class="variable-product btn btn-cart shopgtb-i-buy">
											<i class="fa fa-ban"></i>
											<span class="cart-title">
												<?php echo esc_html__( 'Not Allowed', 'shopglut' ); ?>
											</span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<?php
						echo '</div>'; // End of shopgtb-i-action
				}

				echo '</div>'; // End of shopgtb-i-top

				// Product details (expanded section)
				echo '<div class="shopglist-i">';

				// Product image carousel (if enabled)
				if ( $atts['show_image'] ) {
					echo '<a class="list-img-carousel shopglist-i-img" href="' . esc_url( get_permalink( $product->get_id() ) ) . '">';

					// Main product image
					if ( $product->get_image_id() ) {
						echo wp_get_attachment_image( $product->get_image_id(), 'medium' );
					} else {
						echo wp_kses_post( wc_placeholder_img( 'medium' ) );
					}

					// Gallery images
					$attachment_ids = $product->get_gallery_image_ids();
					foreach ( $attachment_ids as $attachment_id ) {
						echo wp_get_attachment_image( $attachment_id, 'medium' );
					}

					echo '</a>';
				}

				// Product content
				echo '<div class="shopglist-i-cont">';

				// Short description (if enabled)
				if ( $atts['show_description'] ) {
					echo '<div class="shopglist-i-txt">';
					echo wp_kses_post( $product->get_short_description() );
					echo '</div>';
				}

				// Product variations
				if ( $product->is_type( 'variable' ) ) {
					echo '<div class="shopglist-i-skuwrap">';

					// Get available variations
					$available_variations = $product->get_available_variations();
					$attributes = $product->get_variation_attributes();

					// Loop through attributes
					foreach ( $attributes as $attribute_name => $options ) {
						$attribute_label = wc_attribute_label( $attribute_name );

						echo '<div class="shopglist-i-skuitem">';
						echo '<p class="shopglist-i-skuttl">' . esc_html( $attribute_label ) . '</p>';

						// If this is a color attribute, display color swatches
						if ( strpos( strtolower( $attribute_name ), 'color' ) !== false ) {
							echo '<ul class="shopglist-i-skucolor">';
							foreach ( $options as $option ) {
								echo '<li class="' . ( reset( $options ) === $option ? 'active' : '' ) . '"><img src="' . esc_url( SHOPGLUT_URL . 'assets/img/color/' . strtolower( $option ) . '.jpg' ) . '" alt="' . esc_attr( $option ) . '"></li>';
							}
							echo '</ul>';
						}
						// Otherwise display dropdown select
						else {
							echo '<div class="offer-props-select">';
							echo '<p>' . esc_html( reset( $options ) ) . '</p>';
							echo '<ul>';
							foreach ( $options as $option ) {
								echo '<li class="' . ( reset( $options ) === $option ? 'active' : '' ) . '"><a href="#">' . esc_html( $option ) . '</a></li>';
							}
							echo '</ul>';
							echo '</div>';
						}

						echo '</div>'; // End of shopglist-i-skuitem
					}

					echo '</div>'; // End of shopglist-i-skuwrap
				}

				echo '</div>'; // End of shopglist-i-cont

				// Show product properties if enabled
				if ( $atts['show_properties'] ) {
					echo '<ul class="shopglist-i-props2">';

					// Get product attributes
					$attributes = $product->get_attributes();

					// Loop through attributes
					foreach ( $attributes as $attribute ) {
						if ( $attribute->get_visible() ) {
							$attribute_label = wc_attribute_label( $attribute->get_name() );
							$attribute_values = array();

							if ( $attribute->is_taxonomy() ) {
								$attribute_taxonomy = $attribute->get_taxonomy_object();
								$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
							} else {
								$attribute_values = $attribute->get_options();
							}

							if ( ! empty( $attribute_values ) ) {
								echo '<li>';
								echo '<span class="shopglist-i-propttl"><span>' . esc_html( $attribute_label ) . '</span></span> ';
								echo '<span class="shopglist-i-propval">' . esc_html( implode( ', ', $attribute_values ) ) . '</span>';
								echo '</li>';
							}
						}
					}

					// Add additional product information
					if ( $product->get_sku() ) {
						echo '<li>';
						echo '<span class="shopglist-i-propttl"><span>SKU</span></span> ';
						echo '<span class="shopglist-i-propval">' . esc_html( $product->get_sku() ) . '</span>';
						echo '</li>';
					}

					// Stock status
					echo '<li>';
					echo '<span class="shopglist-i-propttl"><span>Stock</span></span> ';
					echo '<span class="shopglist-i-propval">' . ( $product->is_in_stock() ? 'In Stock' : 'Out of Stock' ) . '</span>';
					echo '</li>';

					echo '</ul>';
				}

				// Display product badges (sale, etc.)
				if ( $product->is_on_sale() ) {
					$discount_percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );

					echo '<div class="shopg-sticker">';
					echo '<p class="shopg-sticker-3">-' . esc_html( $discount_percentage ) . '%</p>';
					echo '</div>';
				}

				echo '</div>'; // End of shopglist-i
				echo '</div>'; // End of shopgtb-i

			endwhile;

			echo '</div>'; // End of shopg-items

			// Pagination if enabled
			if ( $atts['paginate'] && $products->max_num_pages > 1 ) {
				echo '<ul class="pagi">';

				$current_page = max( 1, get_query_var( 'paged' ) );

				// First page
				echo '<li class="' . ( $current_page == 1 ? 'active' : '' ) . '">';
				if ( $current_page == 1 ) {
					echo '<span>1</span>';
				} else {
					echo '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '">1</a>';
				}
				echo '</li>';

				// Page numbers
				$total_pages = min( 4, $products->max_num_pages );
				for ( $i = 2; $i <= $total_pages; $i++ ) {
					echo '<li class="' . ( $current_page == $i ? 'active' : '' ) . '">';
					if ( $current_page == $i ) {
						echo '<span>' . esc_html( $i ) . '</span>';
					} else {
						echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '">' . esc_html( $i ) . '</a>';
					}
					echo '</li>';
				}

				// Next page link
				if ( $current_page < $products->max_num_pages ) {
					echo '<li class="pagi-next"><a href="' . esc_url( get_pagenum_link( $current_page + 1 ) ) . '"><i class="fa fa-angle-double-right"></i></a></li>';
				}

				echo '</ul>';
			} else {
				// No products found
				echo '<p class="woocommerce-info">' . esc_html__( 'No products found', 'shopglut' ) . '</p>';
			}
		endif;

		// Reset post data
		wp_reset_postdata();

		echo '</div>'; // End of section-cont
		echo '</section>'; // End of container

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		echo '<div id="shopg-notification-container"></div>';
		echo ' <div class="loader-overlay">
				<div class="loader-container">
					<img src="' . esc_url( $loading_gif ) . '" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>
        <div id="shopg_shop_contents">
		 <div class="shop-layouts-compare-modal">
		<div class="modal-content">
			<span class="shop-layouts-compare-modal-close">&times;</span>
			<div class="modal-body">
				<div class="comparison-data"></div>
			</div>
		</div>
	</div>
	<div id="shop-layouts-quick-view-modal" style="display: none;">
		<div class="quick-view-content">
			<span class="shop-layouts-quick-view-modal-close">&times;</span>
			<div class="product-overview"></div>
          </div>
		</div>
	</div>';

		// Return the buffered output
		return ob_get_clean();
	}

	/**
	 * Get instance of the class
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}