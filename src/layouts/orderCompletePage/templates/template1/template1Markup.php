<?php
namespace  Shopglut\layouts\orderCompletePage\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data) {
		// Get settings
		$layout_id = isset($template_data['layout_id']) ? $template_data['layout_id'] : 0;
		$settings = $this->getLayoutSettings($layout_id);

		// Check if we're rendering real order data or demo data
		$order = isset($template_data['order']) ? $template_data['order'] : null;
		$is_demo = !$order;

		if ($is_demo) {
			// Render demo order complete page
			$this->render_demo_ordercomplete($settings);
		} else {
			// Render live order complete page
			$this->live_orderCompletePage($order, $settings);
		}
	}

	/**
	 * Render live order complete page with real WooCommerce order data
	 */
	public function live_orderCompletePage($order, $settings) {
		// Ensure we have a valid WooCommerce order
		if (!$order || !is_a($order, 'WC_Order')) {
			$this->render_demo_ordercomplete($settings);
			return;
		}

		$order_id = $order->get_id();
		$order_number = $order->get_order_number();
		$order_status = wc_get_order_status_name($order->get_status());
		$order_date = $order->get_date_created()->date_i18n(wc_date_format());
		$order_time = $order->get_date_created()->date_i18n(wc_time_format());
		?>
		<div class="shopglut-ordercomplete template1" data-layout-id="<?php echo esc_attr($settings['layout_id'] ?? 0); ?>">
			<div class="container">
				<!-- Header Section -->
				<div class="header">
					<?php if ($settings['show_success_icon']) : ?>
					<div class="success-icon">
						✓
					</div>
					<?php endif; ?>

					<?php if ($settings['show_thank_you_heading']) : ?>
					<h1><?php echo esc_html($settings['thank_you_heading_text']); ?></h1>
					<?php endif; ?>

					<?php if ($settings['show_success_description']) : ?>
					<p><?php echo esc_html($settings['success_description_text']); ?></p>
					<?php endif; ?>
				</div>

				<div class="content">
					<!-- Order Summary Section -->
					<?php if ($settings['show_order_summary']) : ?>
					<div class="order-summary">
						<div class="order-header">
							<?php if ($settings['show_order_number']) : ?>
							<div class="order-number"><?php
							/* translators: %s: Order number */
							echo sprintf(esc_html__('Order #%s', 'shopglut'), esc_html($order_number)); ?></div>
							<?php endif; ?>

							<?php if ($settings['show_order_status']) : ?>
							<div class="order-status"><?php echo esc_html($order_status); ?></div>
							<?php endif; ?>
						</div>

						<?php if ($settings['show_order_details']) : ?>
						<div class="order-details">
							<div class="detail-item">
								<div class="detail-label"><?php echo esc_html__('Order Date', 'shopglut'); ?></div>
								<div class="detail-value"><?php echo esc_html($order_date); ?></div>
							</div>
							<div class="detail-item">
								<div class="detail-label"><?php echo esc_html__('Email', 'shopglut'); ?></div>
								<div class="detail-value"><?php echo esc_html($order->get_billing_email()); ?></div>
							</div>
							<div class="detail-item">
								<div class="detail-label"><?php echo esc_html__('Payment Method', 'shopglut'); ?></div>
								<div class="detail-value"><?php echo esc_html($order->get_payment_method_title()); ?></div>
							</div>
						</div>
						<?php endif; ?>

						<!-- Order Items -->
						<div class="order-items">
							<?php if ($settings['show_items_header']) : ?>
							<div class="items-header"><?php echo esc_html($settings['items_header_text']); ?></div>
							<?php endif; ?>

							<?php foreach ($order->get_items() as $item_id => $item) :
								$product = $item->get_product();
								$quantity = $item->get_quantity();
								$subtotal = $item->get_subtotal();
								$total = $item->get_total();
							?>
							<div class="item">
								<div class="item-info">
									<div class="item-name"><?php echo esc_html($item->get_name()); ?></div>
									<div class="item-meta">
										<?php
										/* translators: %s: Product quantity */
										echo sprintf(esc_html__('Quantity: %s', 'shopglut'), esc_html($quantity)); ?>
										<?php
										// Display item meta
										$item_meta = $item->get_formatted_meta_data();
										if ($item_meta) {
											echo ' | ';
											$meta_strings = array();
											foreach ($item_meta as $meta) {
												$meta_strings[] = esc_html($meta->display_key) . ': ' . wp_kses_post($meta->display_value);
											}
											echo wp_kses_post(implode(', ', $meta_strings));
										}
										?>
									</div>
								</div>
								<div class="item-price"><?php echo wp_kses_post(wc_price($total)); ?></div>
							</div>
							<?php endforeach; ?>
						</div>

						<!-- Total Section -->
						<?php if ($settings['show_total_section']) : ?>
						<div class="total-section">
							<?php if ($settings['show_subtotal']) : ?>
							<div class="total-row">
								<span><?php echo esc_html__('Subtotal:', 'shopglut'); ?></span>
								<span><?php echo wp_kses_post(wc_price($order->get_subtotal())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_shipping'] && $order->get_shipping_total() > 0) : ?>
							<div class="total-row">
								<span><?php echo esc_html__('Shipping:', 'shopglut'); ?></span>
								<span><?php echo wp_kses_post(wc_price($order->get_shipping_total())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_tax'] && $order->get_total_tax() > 0) : ?>
							<div class="total-row">
								<span><?php echo esc_html__('Tax:', 'shopglut'); ?></span>
								<span><?php echo wp_kses_post(wc_price($order->get_total_tax())); ?></span>
							</div>
							<?php endif; ?>

							<?php if ($order->get_total_discount() > 0) : ?>
							<div class="total-row">
								<span><?php echo esc_html__('Discount:', 'shopglut'); ?></span>
								<span>-<?php echo wp_kses_post(wc_price($order->get_total_discount())); ?></span>
							</div>
							<?php endif; ?>

							<div class="total-row grand-total">
								<span><?php echo esc_html__('Total:', 'shopglut'); ?></span>
								<span><?php echo wp_kses_post(wc_price($order->get_total())); ?></span>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<!-- Address Section -->
					<?php if ($settings['show_address_section']) : ?>
					<div class="address-section">
						<div class="address-grid">
							<?php if ($settings['show_billing_address']) : ?>
							<div class="address-card">
								<div class="address-header"><?php echo esc_html__('Billing Address', 'shopglut'); ?></div>
								<div class="address-content">
									<?php
									$billing_address = $order->get_formatted_billing_address();
									echo wp_kses_post(is_array($billing_address) ? '' : $billing_address);
									?>
									<?php if ($order->get_billing_phone()) : ?>
										<p><strong><?php echo esc_html__('Phone:', 'shopglut'); ?></strong> <?php echo esc_html($order->get_billing_phone()); ?></p>
									<?php endif; ?>
									<?php if ($order->get_billing_email()) : ?>
										<p><strong><?php echo esc_html__('Email:', 'shopglut'); ?></strong> <?php echo esc_html($order->get_billing_email()); ?></p>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>

							<?php if ($settings['show_shipping_address'] && $order->has_shipping_address()) : ?>
							<div class="address-card">
								<div class="address-header"><?php echo esc_html__('Shipping Address', 'shopglut'); ?></div>
								<div class="address-content">
									<?php
									$shipping_address = $order->get_formatted_shipping_address();
									echo wp_kses_post(is_array($shipping_address) ? '' : $shipping_address);
									?>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>

					<!-- Action Buttons -->
					<div class="actions">

						<?php if ($settings['show_continue_shopping_button']) : ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-secondary">
							<?php echo esc_html($settings['continue_shopping_button_text']); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- Footer Section -->
				<?php if ($settings['show_footer']) : ?>
				<div class="footer">
					<p><?php echo esc_html($settings['footer_message_1']); ?></p>
					<p><?php echo esc_html($settings['footer_message_2']); ?></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo order complete page for preview
	 */
	public function render_demo_ordercomplete($settings) {
	?>
	 <div class="shopglut-ordercomplete template1">
        <div class="container">
            <!-- Header Section -->
            <div class="header">
                <?php if ($settings['show_success_icon']) : ?>
                <div class="success-icon">
                    ✓
                </div>
                <?php endif; ?>

                <?php if ($settings['show_thank_you_heading']) : ?>
                <h1><?php echo esc_html($settings['thank_you_heading_text']); ?></h1>
                <?php endif; ?>

                <?php if ($settings['show_success_description']) : ?>
                <p><?php echo esc_html($settings['success_description_text']); ?></p>
                <?php endif; ?>
            </div>

            <div class="content">
                <!-- Order Summary Section -->
                <?php if ($settings['show_order_summary']) : ?>
                <div class="order-summary">
                    <div class="order-header">
                        <?php if ($settings['show_order_number']) : ?>
                        <div class="order-number">Order #12345</div>
                        <?php endif; ?>

                        <?php if ($settings['show_order_status']) : ?>
                        <div class="order-status">Processing</div>
                        <?php endif; ?>
                    </div>

                    <?php if ($settings['show_order_details']) : ?>
                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-label">Order Date</div>
                            <div class="detail-value">June 15, 2025</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">customer@example.com</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Delivery</div>
                            <div class="detail-value">Standard Shipping</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Payment</div>
                            <div class="detail-value">Credit Card</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Order Items -->
                    <div class="order-items">
                        <?php if ($settings['show_items_header']) : ?>
                        <div class="items-header"><?php echo esc_html($settings['items_header_text']); ?></div>
                        <?php endif; ?>

                        <div class="item">
                            <div class="item-info">
                                <div class="item-name">Premium Wireless Headphones</div>
                                <div class="item-meta">Quantity: 1 | Color: Black</div>
                            </div>
                            <div class="item-price">$299.99</div>
                        </div>
                        <div class="item">
                            <div class="item-info">
                                <div class="item-name">Bluetooth Speaker</div>
                                <div class="item-meta">Quantity: 2 | Color: Blue</div>
                            </div>
                            <div class="item-price">$159.98</div>
                        </div>
                        <div class="item">
                            <div class="item-info">
                                <div class="item-name">USB-C Cable</div>
                                <div class="item-meta">Quantity: 1 | Length: 2m</div>
                            </div>
                            <div class="item-price">$19.99</div>
                        </div>
                    </div>

                    <!-- Total Section -->
                    <?php if ($settings['show_total_section']) : ?>
                    <div class="total-section">
                        <?php if ($settings['show_subtotal']) : ?>
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>$479.96</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($settings['show_shipping']) : ?>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>$9.99</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($settings['show_tax']) : ?>
                        <div class="total-row">
                            <span>Tax:</span>
                            <span>$39.20</span>
                        </div>
                        <?php endif; ?>

                        <div class="total-row grand-total">
                            <span>Total:</span>
                            <span>$529.15</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Address Section -->
                <?php if ($settings['show_address_section']) : ?>
                <div class="address-section">
                    <div class="address-grid">
                        <?php if ($settings['show_billing_address']) : ?>
                        <div class="address-card">
                            <div class="address-header">Billing Address</div>
                            <div class="address-content">
                                <p><strong>John Doe</strong></p>
                                <p>123 Main Street</p>
                                <p>Apartment 4B</p>
                                <p>New York, NY 10001</p>
                                <p>United States</p>
                                <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                                <p><strong>Email:</strong> john.doe@example.com</p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($settings['show_shipping_address']) : ?>
                        <div class="address-card">
                            <div class="address-header">Shipping Address</div>
                            <div class="address-content">
                                <p><strong>John Doe</strong></p>
                                <p>456 Oak Avenue</p>
                                <p>Suite 12</p>
                                <p>Brooklyn, NY 11201</p>
                                <p>United States</p>
                                <div class="shipping-note">
                                    Expected delivery: 3-5 business days
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="actions">

                    <?php if ($settings['show_continue_shopping_button']) : ?>
                    <a href="#" class="btn btn-secondary"><?php echo esc_html($settings['continue_shopping_button_text']); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer Section -->
            <?php if ($settings['show_footer']) : ?>
            <div class="footer">
                <p><?php echo esc_html($settings['footer_message_1']); ?></p>
                <p><?php echo esc_html($settings['footer_message_2']); ?></p>
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

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for layout settings
		$layout_data = $wpdb->get_row(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safely constructed using $wpdb->prefix
			$wpdb->prepare("SELECT layout_settings FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $layout_id)
		);

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings'])) {
				return $this->flattenSettings($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']);
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
						// Keep array values as-is (for padding, margin, etc.)
						// Text values should be strings already
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			} else {
				// Direct value, not nested
				$flat_settings[$group_key] = $group_values;
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			// Header Section - Success Icon
			'show_success_icon' => true,
			'success_icon_background_color' => '#10b981',
			'success_icon_text_color' => '#ffffff',
			'success_icon_size' => 60,
			'success_icon_border_radius' => 50,

			// Header Section - Thank You Message
			'show_thank_you_heading' => true,
			'thank_you_heading_text' => __('Thank You!', 'shopglut'),
			'thank_you_heading_color' => '#111827',
			'thank_you_heading_font_size' => 32,

			// Header Section - Success Description
			'show_success_description' => true,
			'success_description_text' => __('Your order has been successfully placed and is being processed.', 'shopglut'),
			'success_description_color' => '#6b7280',
			'success_description_font_size' => 16,

			// Header Section - Background
			'header_background_color' => '#f9fafb',
			'header_padding' => array('top' => '40', 'right' => '20', 'bottom' => '40', 'left' => '20', 'unit' => 'px'),

			// Order Summary Section
			'show_order_summary' => true,
			'order_summary_background_color' => '#ffffff',
			'order_summary_border_color' => '#e5e7eb',
			'order_summary_border_radius' => 8,
			'order_summary_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),

			// Order Header
			'show_order_number' => true,
			'order_number_color' => '#111827',
			'order_number_font_size' => 20,
			'show_order_status' => true,
			'order_status_background_color' => '#dbeafe',
			'order_status_text_color' => '#1e40af',

			// Order Details
			'show_order_details' => true,
			'detail_label_color' => '#6b7280',
			'detail_value_color' => '#111827',
			'detail_font_size' => 14,
			'detail_spacing' => 16,

			// Order Items
			'show_items_header' => true,
			'items_header_text' => __('Order Items', 'shopglut'),
			'items_header_color' => '#111827',
			'items_header_font_size' => 18,
			'item_name_color' => '#111827',
			'item_name_font_size' => 16,
			'item_meta_color' => '#6b7280',
			'item_meta_font_size' => 14,
			'item_price_color' => '#111827',
			'item_price_font_size' => 16,
			'item_spacing' => 16,

			// Total Section
			'show_total_section' => true,
			'total_section_background_color' => '#f9fafb',
			'total_section_border_color' => '#e5e7eb',
			'total_section_padding' => array('top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'),
			'show_subtotal' => true,
			'show_shipping' => true,
			'show_tax' => true,
			'total_row_label_color' => '#6b7280',
			'total_row_value_color' => '#111827',
			'total_row_font_size' => 16,
			'total_row_spacing' => 12,
			'grand_total_label_color' => '#111827',
			'grand_total_value_color' => '#059669',
			'grand_total_font_size' => 20,
			'grand_total_font_weight' => '700',

			// Address Section
			'show_address_section' => true,
			'address_grid_gap' => 24,
			'show_billing_address' => true,
			'show_shipping_address' => true,
			'address_card_background_color' => '#ffffff',
			'address_card_border_color' => '#e5e7eb',
			'address_card_border_radius' => 8,
			'address_card_padding' => array('top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'),
			'address_header_color' => '#111827',
			'address_header_font_size' => 18,
			'address_header_font_weight' => '600',
			'address_content_color' => '#6b7280',
			'address_content_font_size' => 14,
			'address_line_height' => 1.6,

			// Action Buttons
			'show_track_order_button' => true,
			'track_order_button_text' => __('Track Your Order', 'shopglut'),
			'track_order_button_background' => '#3b82f6',
			'track_order_button_text_color' => '#ffffff',
			'track_order_button_hover_background' => '#2563eb',
			'show_continue_shopping_button' => true,
			'continue_shopping_button_text' => __('Continue Shopping', 'shopglut'),
			'continue_shopping_button_background' => '#f3f4f6',
			'continue_shopping_button_text_color' => '#111827',
			'continue_shopping_button_hover_background' => '#e5e7eb',
			'button_font_size' => 16,
			'button_padding' => array('top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px'),
			'button_border_radius' => 6,
			'button_spacing' => 16,

			// Footer Section
			'show_footer' => true,
			'footer_background_color' => '#f9fafb',
			'footer_text_color' => '#6b7280',
			'footer_font_size' => 14,
			'footer_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),
			'footer_message_1' => __("We've sent a confirmation email with your order details to your email address.", 'shopglut'),
			'footer_message_2' => __("If you have any questions, please don't hesitate to contact our customer support.", 'shopglut'),

			// General Styling
			'container_max_width' => 1200,
			'container_background_color' => '#ffffff',
			'container_padding' => array('top' => '0', 'right' => '20', 'bottom' => '0', 'left' => '20', 'unit' => 'px'),
			'section_spacing' => 32,
			'font_family' => 'inherit',
		);
	}


}