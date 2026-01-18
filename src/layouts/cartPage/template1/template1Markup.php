<?php
namespace Shopglut\layouts\cartPage\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data) {
		// Get settings for this layout
		$settings = $this->getLayoutSettings($template_data['layout_id'] ?? 0);

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			echo '<div class="shopglut-error">' . esc_html__('WooCommerce is required for this cart layout.', 'shopglut') . '</div>';
			return;
		}

		// Initialize WooCommerce session and cart if not in admin
		if (!is_admin()) {
			// Initialize WooCommerce if needed
			if (!did_action('woocommerce_init')) {
				WC()->init();
			}

			// Initialize session and cart safely
			if (WC()->session === null && method_exists(WC(), 'initialize_session')) {
				WC()->initialize_session();
			}
			if (WC()->cart === null && method_exists(WC(), 'initialize_cart')) {
				WC()->initialize_cart();
			}
		}

		// Check if we're in admin area or cart is not available
		$is_admin_preview = is_admin() || WC()->cart === null;

		if ($is_admin_preview) {
			// In admin preview mode, show demo content
			$cart_items = array();
			$cart_totals = array();
			$is_cart_empty = true;
		} else {
			// Get WooCommerce cart for frontend
			$cart = WC()->cart;
			$cart_items = $cart->get_cart();
			$cart_totals = $cart->get_totals();
			$is_cart_empty = $cart->is_empty();
		}


		?>
		<div class="shopglut-cart template1" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
			<div class="cart-container">
				<?php if ($is_admin_preview): ?>
					<!-- Admin Preview Mode -->
					<div class="demo-content">
						<?php $this->render_demo_cart($settings); ?>
					</div>
				<?php elseif ($is_cart_empty): ?>
					<!-- Empty Cart State -->
					<div class="empty-cart-state">
						<div class="empty-cart-icon">
							<i class="fas fa-shopping-cart"></i>
						</div>
						<h2><?php echo esc_html__('Your cart is empty', 'shopglut'); ?></h2>
						<p><?php echo esc_html__('Looks like you haven\'t added anything to your cart yet.', 'shopglut'); ?></p>
						<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="continue-shopping-btn">
							<i class="fas fa-arrow-left"></i>
							<?php echo esc_html__('Continue Shopping', 'shopglut'); ?>
						</a>
					</div>

					<!-- Demo Content for Preview -->
					<div class="demo-content" style="opacity: 0.5; pointer-events: none;">
						<div class="demo-notice">
							<p><?php echo esc_html__('Preview Mode - Add products to cart to see real data', 'shopglut'); ?></p>
						</div>
						<?php $this->render_demo_cart($settings); ?>
					</div>
				<?php else: ?>
					<!-- Real Cart Content -->
					<div class="cart-content">
						<?php $this->render_cart_table($cart_items, $settings); ?>
						<?php $this->render_cart_footer($cart, $cart_totals, $settings); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render cart table with real cart items
	 */
	private function render_cart_table($cart_items, $settings) {
		?>
		<div class="cart-table-container">
			<table class="cart-table">
				<?php if ($settings['show_table_header']): ?>
				<thead>
					<tr>
						<th><?php echo esc_html__('Product', 'shopglut'); ?></th>
						<th><?php echo esc_html__('Price', 'shopglut'); ?></th>
						<th><?php echo esc_html__('Quantity', 'shopglut'); ?></th>
						<th><?php echo esc_html__('Total', 'shopglut'); ?></th>
						<th></th>
					</tr>
				</thead>
				<?php endif; ?>
				<tbody>
					<?php foreach ($cart_items as $cart_item_key => $cart_item):
						$product = $cart_item['data'];
						$product_id = $cart_item['product_id'];
						$variation_id = $cart_item['variation_id'];
						$quantity = $cart_item['quantity'];
						$line_total = $cart_item['line_total'];
						$line_subtotal = $cart_item['line_subtotal'];
						?>
						<tr class="cart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
							<td>
								<div class="product-cell">
									<div class="product-image">
										<?php
										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key);
										echo wp_kses_post($thumbnail);
										?>
									</div>
									<div class="product-details">
										<?php if ($settings['show_product_link']): ?>
											<a href="<?php echo esc_url($product->get_permalink($cart_item)); ?>" class="product-name">
												<?php echo wp_kses_post($product->get_name()); ?>
											</a>
										<?php else: ?>
											<div class="product-name"><?php echo wp_kses_post($product->get_name()); ?></div>
										<?php endif; ?>

										<?php if ($settings['show_product_meta']): ?>
											<div class="product-meta">
												<?php
												// Display variation attributes
												if ($variation_id && $variation_data = wc_get_formatted_variation($cart_item['variation'], true)) {
													echo '<div class="variation-data">' . wp_kses_post($variation_data) . '</div>';
												}

												// Display product SKU
												if ($product->get_sku()) {
													echo '<div class="product-sku">' . esc_html__('SKU:', 'shopglut') . ' ' . esc_html($product->get_sku()) . '</div>';
												}

												// Custom product badges
												if ($settings['show_product_badges']):
													if ($product->is_on_sale()) {
														echo '<span class="product-badge sale-badge">' . esc_html__('Sale', 'shopglut') . '</span>';
													}
													if ($product->is_featured()) {
														echo '<span class="product-badge featured-badge">' . esc_html__('Featured', 'shopglut') . '</span>';
													}
												endif;
												?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td class="price-cell">
								<?php echo wp_kses_post(wc_price($product->get_price())); ?>
							</td>
							<td class="quantity-cell">
								<div class="qty-control">
									<button class="qty-btn qty-decrease" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">−</button>
									<input type="number"
										   value="<?php echo esc_attr($quantity); ?>"
										   min="1"
										   max="<?php echo esc_attr($product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : 999); ?>"
										   class="qty-input"
										   data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
										   step="1">
									<button class="qty-btn qty-increase" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
								</div>
							</td>
							<td class="price-cell item-total">
								<?php echo wp_kses_post(wc_price($line_total)); ?>
							</td>
							<td>
								<button class="remove-btn" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" title="<?php echo esc_attr__('Remove item', 'shopglut'); ?>">
									<i class="fas fa-times"></i>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render cart footer with totals and checkout
	 */
	private function render_cart_footer($cart, $cart_totals, $settings) {
		// Safety check for cart availability
		if (!$cart) {
			return;
		}
		?>
		<div class="cart-footer">
			<div class="footer-grid">
				<!-- Coupon Section -->
				<?php if ($settings['show_discount_section'] ?? true): ?>
				<div class="footer-section">
					<?php if ($settings['show_discount_title'] ?? true): ?>
					<h3 class="section-title">
						<?php if ($settings['show_discount_icon'] ?? true): ?>
						<i class="fas fa-tag"></i>
						<?php endif; ?>
						<?php echo esc_html($settings['discount_title_text'] ?? __('Discount Code', 'shopglut')); ?>
					</h3>
					<?php endif; ?>
					<form class="coupon-form" id="shopglut-coupon-form">
						<div class="input-group">
							<input type="text"
								   placeholder="<?php echo esc_attr($settings['coupon_input_placeholder'] ?? __('Enter coupon code', 'shopglut')); ?>"
								   class="coupon-input"
								   id="couponCode"
								   name="coupon_code">
							<button type="submit" class="apply-btn">
								<?php echo esc_html($settings['apply_button_text'] ?? __('Apply', 'shopglut')); ?>
							</button>
						</div>
						<?php if ($settings['show_coupon_messages'] ?? true): ?>
						<div class="coupon-message" id="couponMessage"></div>
						<?php endif; ?>
					</form>

					<!-- Applied Coupons -->
					<?php if ($applied_coupons = $cart->get_applied_coupons()): ?>
					<div class="applied-coupons">
						<h4><?php echo esc_html__('Applied Coupons:', 'shopglut'); ?></h4>
						<?php foreach ($applied_coupons as $coupon_code): ?>
							<div class="applied-coupon">
								<span class="coupon-code"><?php echo esc_html($coupon_code); ?></span>
								<button class="remove-coupon" data-coupon="<?php echo esc_attr($coupon_code); ?>">
									<i class="fas fa-times"></i>
								</button>
							</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Cart Summary -->
				<?php if ($settings['show_summary_section'] ?? true): ?>
				<div class="footer-section">
					<div class="cart-summary">
						<?php if ($settings['show_summary_header'] ?? true): ?>
						<div class="summary-header">
							<h3 class="summary-title">
								<?php if ($settings['show_summary_icon'] ?? true): ?>
								<i class="fas fa-receipt"></i>
								<?php endif; ?>
								<?php echo esc_html($settings['summary_title_text'] ?? __('Order Summary', 'shopglut')); ?>
							</h3>
						</div>
						<?php endif; ?>
						<div class="summary-content">
							<?php if ($settings['show_subtotal'] ?? true): ?>
							<div class="summary-row">
								<span class="label"><?php echo esc_html__('Subtotal:', 'shopglut'); ?></span>
								<span class="value"><?php echo wp_kses_post(wc_price($cart->get_subtotal())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_discount_row'] ?? true && $cart->get_discount_total() > 0): ?>
							<div class="summary-row">
								<span class="label"><?php echo esc_html__('Discount:', 'shopglut'); ?></span>
								<span class="value discount">-<?php echo wp_kses_post(wc_price($cart->get_discount_total())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_shipping'] ?? true): ?>
							<div class="summary-row">
								<span class="label"><?php echo esc_html__('Shipping:', 'shopglut'); ?></span>
								<span class="value"><?php echo wp_kses_post(wc_price($cart->get_shipping_total())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_tax'] ?? true && wc_tax_enabled()): ?>
							<div class="summary-row">
								<span class="label"><?php echo esc_html__('Tax:', 'shopglut'); ?></span>
								<span class="value"><?php echo wp_kses_post(wc_price($cart->get_total_tax())); ?></span>
							</div>
							<?php endif; ?>

							<div class="summary-row total-row">
								<span class="label"><?php echo esc_html__('Total:', 'shopglut'); ?></span>
								<span class="value"><?php echo wp_kses_post(wc_price($cart->get_total(''))); ?></span>
							</div>

							<button class="checkout-btn" id="proceed-to-checkout">
								<?php if ($settings['show_checkout_icon'] ?? true): ?>
								<i class="fas fa-lock"></i>
								<?php endif; ?>
								<?php echo esc_html($settings['checkout_button_text'] ?? __('Secure Checkout', 'shopglut')); ?>
							</button>

							<?php if ($settings['show_security_badges'] ?? true): ?>
							<div class="security-info">
								<?php if ($settings['show_ssl_badge'] ?? true): ?>
								<div class="security-badge">
									<i class="<?php echo esc_attr($settings['ssl_badge_icon'] ?? 'fas fa-shield-alt'); ?>"></i>
									<?php echo esc_html($settings['ssl_badge_text'] ?? __('SSL Secured', 'shopglut')); ?>
								</div>
								<?php endif; ?>
								<?php if ($settings['show_payment_badge'] ?? true): ?>
								<div class="security-badge">
									<i class="<?php echo esc_attr($settings['payment_badge_icon'] ?? 'fas fa-credit-card'); ?>"></i>
									<?php echo esc_html($settings['payment_badge_text'] ?? __('Safe Payment', 'shopglut')); ?>
								</div>
								<?php endif; ?>
								<?php if ($settings['show_return_badge'] ?? true): ?>
								<div class="security-badge">
									<i class="<?php echo esc_attr($settings['return_badge_icon'] ?? 'fas fa-undo'); ?>"></i>
									<?php echo esc_html($settings['return_badge_text'] ?? __('30-Day Return', 'shopglut')); ?>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<!-- Continue Shopping Link -->
			<?php if ($settings['show_continue_shopping'] ?? true): ?>
			<div class="continue-shopping">
				<a href="<?php echo esc_url($settings['continue_shopping_url'] ?? wc_get_page_permalink('shop')); ?>" class="continue-link">
					<?php if ($settings['show_continue_icon'] ?? true): ?>
					<i class="fas fa-arrow-left"></i>
					<?php endif; ?>
					<?php echo esc_html($settings['continue_shopping_text'] ?? __('Continue Shopping', 'shopglut')); ?>
				</a>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render demo cart for preview when cart is empty
	 */
	private function render_demo_cart($settings) {
		// Get the placeholder image URL
		$placeholder_url = SHOPGLUT_URL . 'global-assets/images/wc-placeholder.png';

		?>
		<div class="cart-content">
			<div class="cart-table-container">
				<table class="cart-table">
					<?php if ($settings['show_table_header']): ?>
					<thead>
						<tr>
							<th><?php echo esc_html__('Product', 'shopglut'); ?></th>
							<th><?php echo esc_html__('Price', 'shopglut'); ?></th>
							<th><?php echo esc_html__('Quantity', 'shopglut'); ?></th>
							<th><?php echo esc_html__('Total', 'shopglut'); ?></th>
							<th></th>
						</tr>
					</thead>
					<?php endif; ?>
					<tbody>
						<!-- Demo Product 1 -->
						<tr class="demo-item">
							<td>
								<div class="product-cell">
									<div class="product-image">
										<img src="<?php echo esc_url($placeholder_url); ?>" alt="<?php echo esc_attr__('Sample Product', 'shopglut'); ?>" style="width: <?php echo esc_attr($settings['product_image_size']['width'] ?? 60); ?>px; height: <?php echo esc_attr($settings['product_image_size']['height'] ?? 60); ?>px; object-fit: cover; border-radius: <?php echo esc_attr($settings['image_border_radius'] ?? 8); ?>px;" />
									</div>
									<div class="product-details">
										<?php if ($settings['show_product_link']): ?>
										<a href="#" class="product-name"><?php echo esc_html__('Premium Wireless Headphones', 'shopglut'); ?></a>
										<?php else: ?>
										<div class="product-name"><?php echo esc_html__('Premium Wireless Headphones', 'shopglut'); ?></div>
										<?php endif; ?>
										<?php if ($settings['show_product_meta']): ?>
										<div class="product-meta">
											<?php echo esc_html__('High-quality audio with noise cancellation', 'shopglut'); ?>
											<?php if ($settings['show_product_badges']): ?>
											<span class="product-badge sale-badge"><?php echo esc_html__('Sale', 'shopglut'); ?></span>
											<?php endif; ?>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td class="price-cell">$129.99</td>
							<td class="quantity-cell">
								<div class="qty-control">
									<button class="qty-btn qty-decrease" disabled>−</button>
									<input type="number" value="1" min="1" class="qty-input" disabled>
									<button class="qty-btn qty-increase" disabled>+</button>
								</div>
							</td>
							<td class="price-cell item-total">$129.99</td>
							<td>
								<button class="remove-btn" disabled title="<?php echo esc_attr__('Remove item', 'shopglut'); ?>">
									<i class="fas fa-times"></i>
								</button>
							</td>
						</tr>

						<!-- Demo Product 2 -->
						<tr class="demo-item">
							<td>
								<div class="product-cell">
									<div class="product-image">
										<img src="<?php echo esc_url($placeholder_url); ?>" alt="<?php echo esc_attr__('Sample Product', 'shopglut'); ?>" style="width: <?php echo esc_attr($settings['product_image_size']['width'] ?? 60); ?>px; height: <?php echo esc_attr($settings['product_image_size']['height'] ?? 60); ?>px; object-fit: cover; border-radius: <?php echo esc_attr($settings['image_border_radius'] ?? 8); ?>px;" />
									</div>
									<div class="product-details">
										<?php if ($settings['show_product_link']): ?>
										<a href="#" class="product-name"><?php echo esc_html__('Smartphone Case', 'shopglut'); ?></a>
										<?php else: ?>
										<div class="product-name"><?php echo esc_html__('Smartphone Case', 'shopglut'); ?></div>
										<?php endif; ?>
										<?php if ($settings['show_product_meta']): ?>
										<div class="product-meta">
											<?php echo esc_html__('Protective case with premium materials', 'shopglut'); ?>
											<?php if ($settings['show_product_badges']): ?>
											<span class="product-badge featured-badge"><?php echo esc_html__('Featured', 'shopglut'); ?></span>
											<?php endif; ?>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td class="price-cell">$24.99</td>
							<td class="quantity-cell">
								<div class="qty-control">
									<button class="qty-btn qty-decrease" disabled>−</button>
									<input type="number" value="2" min="1" class="qty-input" disabled>
									<button class="qty-btn qty-increase" disabled>+</button>
								</div>
							</td>
							<td class="price-cell item-total">$49.98</td>
							<td>
								<button class="remove-btn" disabled title="<?php echo esc_attr__('Remove item', 'shopglut'); ?>">
									<i class="fas fa-times"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="cart-footer">
				<div class="footer-grid">
					<?php if ($settings['show_discount_section'] ?? true): ?>
					<div class="footer-section">
						<?php if ($settings['show_discount_title'] ?? true): ?>
						<h3 class="section-title">
							<?php if ($settings['show_discount_icon'] ?? true): ?>
							<i class="fas fa-tag"></i>
							<?php endif; ?>
							<?php echo esc_html($settings['discount_title_text'] ?? __('Discount Code', 'shopglut')); ?>
						</h3>
						<?php endif; ?>
						<form class="coupon-form">
							<div class="input-group">
								<input type="text" placeholder="<?php echo esc_attr($settings['coupon_input_placeholder'] ?? __('Enter coupon code', 'shopglut')); ?>" class="coupon-input" disabled>
								<button type="button" class="apply-btn" disabled><?php echo esc_html($settings['apply_button_text'] ?? __('Apply', 'shopglut')); ?></button>
							</div>
						</form>
					</div>
					<?php endif; ?>

					<?php if ($settings['show_summary_section'] ?? true): ?>
					<div class="footer-section">
						<div class="cart-summary">
							<?php if ($settings['show_summary_header'] ?? true): ?>
							<div class="summary-header">
								<h3 class="summary-title">
									<?php if ($settings['show_summary_icon'] ?? true): ?>
									<i class="fas fa-receipt"></i>
									<?php endif; ?>
									<?php echo esc_html($settings['summary_title_text'] ?? __('Order Summary', 'shopglut')); ?>
								</h3>
							</div>
							<?php endif; ?>
							<div class="summary-content">
								<?php if ($settings['show_subtotal'] ?? true): ?>
								<div class="summary-row">
									<span class="label"><?php echo esc_html__('Subtotal:', 'shopglut'); ?></span>
									<span class="value">$179.97</span>
								</div>
								<?php endif; ?>
								<?php if ($settings['show_shipping'] ?? true): ?>
								<div class="summary-row">
									<span class="label"><?php echo esc_html__('Shipping:', 'shopglut'); ?></span>
									<span class="value">$9.99</span>
								</div>
								<?php endif; ?>
								<?php if ($settings['show_tax'] ?? true): ?>
								<div class="summary-row">
									<span class="label"><?php echo esc_html__('Tax:', 'shopglut'); ?></span>
									<span class="value">$14.40</span>
								</div>
								<?php endif; ?>
								<div class="summary-row total-row">
									<span class="label"><?php echo esc_html__('Total:', 'shopglut'); ?></span>
									<span class="value">$204.36</span>
								</div>

								<button class="checkout-btn" disabled>
									<?php if ($settings['show_checkout_icon'] ?? true): ?>
									<i class="fas fa-lock"></i>
									<?php endif; ?>
									<?php echo esc_html($settings['checkout_button_text'] ?? __('Secure Checkout', 'shopglut')); ?>
								</button>

								<?php if ($settings['show_security_badges'] ?? true): ?>
								<div class="security-info">
									<?php if ($settings['show_ssl_badge'] ?? true): ?>
									<div class="security-badge">
										<i class="<?php echo esc_attr($settings['ssl_badge_icon'] ?? 'fas fa-shield-alt'); ?>"></i>
										<?php echo esc_html($settings['ssl_badge_text'] ?? __('SSL Secured', 'shopglut')); ?>
									</div>
									<?php endif; ?>
									<?php if ($settings['show_payment_badge'] ?? true): ?>
									<div class="security-badge">
										<i class="<?php echo esc_attr($settings['payment_badge_icon'] ?? 'fas fa-credit-card'); ?>"></i>
										<?php echo esc_html($settings['payment_badge_text'] ?? __('Safe Payment', 'shopglut')); ?>
									</div>
									<?php endif; ?>
									<?php if ($settings['show_return_badge'] ?? true): ?>
									<div class="security-badge">
										<i class="<?php echo esc_attr($settings['return_badge_icon'] ?? 'fas fa-undo'); ?>"></i>
										<?php echo esc_html($settings['return_badge_text'] ?? __('30-Day Return', 'shopglut')); ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>

				<!-- Continue Shopping Link -->
				<?php if ($settings['show_continue_shopping'] ?? true): ?>
				<div class="continue-shopping">
					<a href="<?php echo esc_url($settings['continue_shopping_url'] ?? '#'); ?>" class="continue-link">
						<?php if ($settings['show_continue_icon'] ?? true): ?>
						<i class="fas fa-arrow-left"></i>
						<?php endif; ?>
						<?php echo esc_html($settings['continue_shopping_text'] ?? __('Continue Shopping', 'shopglut')); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

		// Use caching for better performance
		$cache_key = "shopglut_cartpage_settings_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_cartpage' );

		if ( false === $layout_data ) {
			// Use proper table name escaping with %i placeholder
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$layout_data = $wpdb->get_row(
				$wpdb->prepare("SELECT layout_settings FROM {$wpdb->prefix}shopglut_cart_layouts WHERE id = %d", $layout_id)
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_cartpage', 30 * MINUTE_IN_SECONDS );
		}

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_cartpage_settings_template1']['cart-page-settings'])) {
				return $this->flattenSettings($settings['shopg_cartpage_settings_template1']['cart-page-settings']);
			}
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Flatten nested settings structure to simple key-value pairs
	 */
	private function flattenSettings($nested_settings) {
		$flat_settings = array();

		foreach ($nested_settings as $group_key => $group_values) {
			if (is_array($group_values)) {
				foreach ($group_values as $setting_key => $setting_value) {
					// Handle slider fields that have separate value and unit
					if (is_array($setting_value) && isset($setting_value[$setting_key])) {
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} else {
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			// Table Header Settings
			'show_table_header' => true,
			'header_background_color' => '#f3f4f6',
			'header_text_color' => '#374151',
			'header_font_weight' => '600',

			// Product Settings
			'show_product_link' => true,
			'show_product_meta' => true,
			'show_product_badges' => true,

			// Summary Section Settings
			'show_summary_section' => true,
			'show_summary_header' => true,
			'summary_title_text' => 'Order Summary',
			'show_summary_icon' => true,

			// Summary Rows
			'show_subtotal' => true,
			'show_shipping' => true,
			'show_tax' => true,
			'show_discount_row' => true,

			// Checkout Button
			'checkout_button_text' => 'Secure Checkout',
			'show_checkout_icon' => true,

			// Security Badges
			'show_security_badges' => true,
			'show_ssl_badge' => true,
			'ssl_badge_text' => 'SSL Secured',
			'ssl_badge_icon' => 'fas fa-shield-alt',
			'show_payment_badge' => true,
			'payment_badge_text' => 'Safe Payment',
			'payment_badge_icon' => 'fas fa-credit-card',
			'show_return_badge' => true,
			'return_badge_text' => '30-Day Return',
			'return_badge_icon' => 'fas fa-undo',

			// Discount Section
			'show_discount_section' => true,
			'show_discount_title' => true,
			'discount_title_text' => 'Discount Code',
			'show_discount_icon' => true,
			'coupon_input_placeholder' => 'Enter coupon code',
			'apply_button_text' => 'Apply',
			'show_coupon_messages' => true,

			// Continue Shopping
			'show_continue_shopping' => true,
			'continue_shopping_text' => 'Continue Shopping',
			'continue_shopping_url' => '#',
			'show_continue_icon' => true,
		);
	}
}