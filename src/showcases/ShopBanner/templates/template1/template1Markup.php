<?php
namespace Shopglut\showcases\ShopBanner\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data, $layout_id) {
		// Get settings
		$layout_id = isset($_GET['layout_id']) ? sanitize_text_field(wp_unslash($_GET['layout_id'])) : $layout_id; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin area with proper access controls
		$settings = $this->getLayoutSettings($layout_id);

		// Check if we're rendering a specific product or demo
		$product_id = isset($template_data['product_id']) ? $template_data['product_id'] : 0;
		$is_demo = empty($product_id);


		if ($is_demo) {
			// Render demo quick view modal
			$this->render_demo_shopbanner($settings, $layout_id);
		} else {
			// Render live quick view modal
			$this->render_live_shopbanner($product_id, $settings);
		}
	}

	/**
	 * Render live quick view modal with real WooCommerce product
	 */
	public function render_live_shopbanner($product_id, $settings) {
		$product = wc_get_product($product_id);

		if (!$product) {
			echo '<div class="shopglut-shopbanner-error"><p>' . esc_html__('Product not found.', 'shopglut') . '</p></div>';
			return;
		}

		// Get product data
		$product_title = $product->get_name();
		$product_description = $product->get_short_description();
		$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'large');
		$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
		$gallery_ids = $product->get_gallery_image_ids();
		$average_rating = $product->get_average_rating();
		$rating_count = $product->get_rating_count();
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$is_on_sale = $product->is_on_sale();

		// Calculate discount percentage
		$discount_percentage = 0;
		if ($is_on_sale && $regular_price && $sale_price) {
			$discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
		}
		?>
		<div class="shopglut-product-shopbanner template1" data-product-id="<?php echo esc_attr($product_id); ?>">
			<div class="shopbanner-modal">
				<div class="shopbanner-modal-overlay"></div>
				<div class="shopbanner-modal-content">
					<!-- Close Button -->
					<button class="shopbanner-close" aria-label="<?php esc_attr_e('Close', 'shopglut'); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>

					<div class="shopbanner-inner">
						<!-- Product Gallery Section -->
						<div class="shopbanner-gallery">
							<div class="main-image-wrapper">
								<?php if ($is_on_sale && $discount_percentage > 0): ?>
								<span class="sale-badge">-<?php echo esc_html($discount_percentage); ?>%</span>
								<?php endif; ?>
								<img src="<?php echo esc_url($product_image_url); ?>"
									 alt="<?php echo esc_attr($product_title); ?>"
									 class="shopbanner-main-image">
							</div>

							<?php if (!empty($gallery_ids)): ?>
							<div class="gallery-thumbnails">
								<div class="thumbnail-item active">
									<img src="<?php echo esc_url($product_image_url); ?>"
										 alt="<?php echo esc_attr($product_title); ?>">
								</div>
								<?php foreach ($gallery_ids as $gallery_id):
									$gallery_image = wp_get_attachment_image_src($gallery_id, 'thumbnail');
									if ($gallery_image): ?>
									<div class="thumbnail-item">
										<img src="<?php echo esc_url($gallery_image[0]); ?>"
											 data-large="<?php echo esc_url(wp_get_attachment_image_src($gallery_id, 'large')[0]); ?>"
											 alt="<?php echo esc_attr($product_title); ?>">
									</div>
								<?php endif; endforeach; ?>
							</div>
							<?php endif; ?>
						</div>

						<!-- Product Info Section -->
						<div class="shopbanner-info">
							<!-- Product Title -->
							<h2 class="product-title"><?php echo esc_html($product_title); ?></h2>

							<!-- Product Rating -->
							<?php if ($average_rating > 0): ?>
							<div class="product-rating">
								<div class="stars">
									<?php echo wp_kses_post($this->render_stars($average_rating)); ?>
								</div>
								<span class="rating-text"><?php echo esc_html($average_rating); ?> (<?php echo esc_html($rating_count); ?> <?php esc_html_e('reviews', 'shopglut'); ?>)</span>
							</div>
							<?php endif; ?>

							<!-- Product Price -->
							<div class="product-price">
								<?php if ($is_on_sale && $regular_price): ?>
									<span class="sale-price"><?php echo wp_kses_post( wc_price($sale_price) ); ?></span>
									<span class="regular-price"><del><?php echo wp_kses_post( wc_price($regular_price) ); ?></del></span>
								<?php else: ?>
									<span class="current-price"><?php echo wp_kses_post( wc_price($product->get_price()) ); ?></span>
								<?php endif; ?>
							</div>

							<!-- Product Short Description -->
							<?php if ($product_description): ?>
							<div class="product-description">
								<?php echo wp_kses_post($product_description); ?>
							</div>
							<?php endif; ?>

							<!-- Product Meta -->
							<div class="product-meta">
								<?php if (isset($settings['show_sku']) && $settings['show_sku'] && $product->get_sku()): ?>
								<div class="meta-item">
									<span class="meta-label"><?php esc_html_e('SKU:', 'shopglut'); ?></span>
									<span class="meta-value"><?php echo esc_html($product->get_sku()); ?></span>
								</div>
								<?php endif; ?>

								<?php
								if (isset($settings['show_categories']) && $settings['show_categories']) {
									$categories = wc_get_product_category_list($product_id);
									if ($categories): ?>
									<div class="meta-item">
										<span class="meta-label"><?php esc_html_e('Categories:', 'shopglut'); ?></span>
										<span class="meta-value"><?php echo wp_kses_post($categories); ?></span>
									</div>
									<?php endif;
								}
								?>

								<?php if (isset($settings['show_stock_status']) && $settings['show_stock_status']): ?>
								<div class="meta-item">
									<span class="meta-label"><?php esc_html_e('Availability:', 'shopglut'); ?></span>
									<span class="meta-value stock-status <?php echo $product->is_in_stock() ? 'in-stock' : 'out-of-stock'; ?>">
										<?php echo $product->is_in_stock() ? esc_html__('In Stock', 'shopglut') : esc_html__('Out of Stock', 'shopglut'); ?>
									</span>
								</div>
								<?php endif; ?>
							</div>

							<!-- Variable Product Attributes -->
							<?php if ($product->is_type('variable')): ?>
							<div class="product-variations">
								<?php
								$attributes = $product->get_variation_attributes();
								foreach ($attributes as $attribute_name => $options): ?>
									<div class="variation-item">
										<label class="variation-label"><?php echo esc_html(wc_attribute_label($attribute_name)); ?>:</label>
										<select class="variation-select" name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
											<option value=""><?php esc_html_e('Choose an option', 'shopglut'); ?></option>
											<?php foreach ($options as $option): ?>
												<option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>

							<!-- Quantity and Add to Cart -->
							<div class="product-actions">
								<div class="quantity-selector">
									<button class="qty-btn qty-decrease" type="button">-</button>
									<input type="number" class="qty-input" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" step="1">
									<button class="qty-btn qty-increase" type="button">+</button>
								</div>
								<button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
									<?php echo esc_html($product->add_to_cart_text()); ?>
								</button>
							</div>

							<!-- Additional Actions -->
							<div class="additional-actions">
								<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="view-details-btn">
									<?php esc_html_e('View Full Details', 'shopglut'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo quick view modal for preview
	 */
	public function render_demo_shopbanner($settings, $layout_id) {
		$placeholder_url = SHOPGLUT_URL . 'global-assets/images/demo-image.png';

		?>
		<div class="shopglut-banner-container">
						<?php

						global $wpdb;

							// Get banner data from database
							$table_name = $wpdb->prefix . 'shopglut_shopbanner_layouts';
							// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
							$banner_data = $wpdb->get_row(
								$wpdb->prepare( "SELECT * FROM %i WHERE id = %d", $table_name, $layout_id ) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- WP 6.2+ identifier placeholder supported
							);

						if ( $banner_data ) {
							// Data is stored in layout_settings field, not banner_settings
							$banner_settings = unserialize( $banner_data->layout_settings );
							$banner_template = $banner_data->layout_template;

							// Get the actual settings from the new structure
							$actual_settings = isset( $banner_settings['shopg_product_shopbanner_settings_template1'] ) ? $banner_settings['shopg_product_shopbanner_settings_template1'] : array();

							// Get the shopbanner_settings_tabs data
							$tabbed_settings = isset( $actual_settings['shopbanner_settings_tabs'] ) ? $actual_settings['shopbanner_settings_tabs'] : array();

							// Create demo banner data array
							$demo_banner_data = array(
								'id' => $layout_id
							);

							// Prepare settings array for the template using the new tabbed structure
							$settings = array(
								'content' => array(
									'title' => isset( $tabbed_settings['banner_title'] ) ? $tabbed_settings['banner_title'] : __( 'Special Offer!', 'shopglut' ),
									'description' => isset( $tabbed_settings['banner_description'] ) ? $tabbed_settings['banner_description'] : __( 'Check out our amazing deals and discounts!', 'shopglut' ),
									'image_url' => isset( $tabbed_settings['banner_image']['url'] ) ? $tabbed_settings['banner_image']['url'] : '',
									'button_text' => isset( $tabbed_settings['banner_button_text'] ) ? $tabbed_settings['banner_button_text'] : __( 'Shop Now', 'shopglut' ),
									'button_url' => isset( $tabbed_settings['banner_button_url'] ) ? $tabbed_settings['banner_button_url'] : get_home_url(),
								),
								'style' => array(
									'background_color' => isset( $tabbed_settings['banner_appearance']['banner_background_color'] ) ? $tabbed_settings['banner_appearance']['banner_background_color'] : '#ffffff',
									'text_color' => isset( $tabbed_settings['text_styling']['title_color'] ) ? $tabbed_settings['text_styling']['title_color'] : '#2c3e50',
									'button_color' => isset( $tabbed_settings['button_styling']['button_bg_color'] ) ? $tabbed_settings['button_styling']['button_bg_color'] : '#0073aa',
								),
								'display_settings' => array(
									'delay' => isset( $tabbed_settings['display_delay']['display_delay'] ) ? $tabbed_settings['display_delay']['display_delay'] : 3000,
									'position' => isset( $tabbed_settings['banner_position'] ) ? $tabbed_settings['banner_position'] : 'center',
									'overlay_opacity' => isset( $tabbed_settings['modal_styling']['modal_overlay_color'] ) ? $tabbed_settings['modal_styling']['modal_overlay_color'] : 'rgba(0, 0, 0, 0.75)',
								)
							);

							// Load and use the actual template
			
									// For preview, we'll directly render the banner in a preview-friendly format
									?>
									<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; max-width: 600px; margin: 0 auto; text-align: center;">
										<div style="background: <?php echo esc_attr( $settings['style']['background_color'] ); ?>; color: <?php echo esc_attr( $settings['style']['text_color'] ); ?>; border-radius: 12px; padding: 30px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); position: relative; overflow: hidden;">

											
											<div class="shopglut-banner-body">
												<?php if ( ! empty( $settings['content']['image_url'] ) ) : ?>
													<div class="shopglut-banner-image" style="margin-bottom: 20px;">
														<img src="<?php echo esc_url( $settings['content']['image_url'] ); ?>" alt="<?php echo esc_attr( $settings['content']['title'] ); ?>" style="max-width: <?php echo isset( $tabbed_settings['image_styling']['image_max_width']['image_max_width'] ) ? esc_attr( $tabbed_settings['image_styling']['image_max_width']['image_max_width'] ) : '200'; ?>px; height: auto; border-radius: <?php echo isset( $tabbed_settings['image_styling']['image_border_radius']['image_border_radius'] ) ? esc_attr( $tabbed_settings['image_styling']['image_border_radius']['image_border_radius'] ) : '8'; ?>px;">
													</div>
												<?php else: ?>
													<div class="demo-icon" style="font-size: 48px; margin-bottom: 20px;">🎉</div>
												<?php endif; ?>

												<div class="shopglut-banner-text">
													<h3 class="shopglut-banner-title" style="font-size: 28px; font-weight: bold; margin-bottom: 15px;"><?php echo esc_html( $settings['content']['title'] ); ?></h3>
													<p class="shopglut-banner-description" style="font-size: 16px; margin-bottom: 25px; line-height: 1.6; opacity: 0.8;"><?php echo esc_html( $settings['content']['description'] ); ?></p>
												</div>

												<?php if ( isset( $tabbed_settings['show_button'] ) && $tabbed_settings['show_button'] == '1' && ! empty( $settings['content']['button_text'] ) ) : ?>
													<div class="shopglut-banner-actions">
														<a href="<?php echo esc_url( $settings['content']['button_url'] ); ?>"
														   class="shopglut-banner-button"
														   style="background: linear-gradient(45deg, <?php echo esc_attr( $settings['style']['button_color'] ); ?>, #005a8b); color: #fff; padding: 12px 30px; border-radius: 25px; display: inline-block; text-decoration: none; font-size: 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,115,170,0.3);">
															<?php echo esc_html( $settings['content']['button_text'] ); ?>
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>

									</div>
									<?php
								
							
							// Show default demo when no banner data exists
							?>
							
							<?php
						} else {
                          ?>

                          <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; max-width: 600px; margin: 0 auto; text-align: center;">
										<div style="background: #ffffff; color: #2c3e50; border-radius: 12px; padding: 30px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); position: relative; overflow: hidden;">

											
										<div class="shopglut-banner-body">

											<div class="demo-icon" style="font-size: 48px; margin-bottom: 20px;">🎉</div>

												<div class="shopglut-banner-text">
													<h3 class="shopglut-banner-title"><?php echo esc_html__('Special Offer!', 'shopglut');  ?></h3>
													<p class="shopglut-banner-description"><?php echo esc_html__('Check out our amazing deals and discounts on selected products!', 'shopglut');  ?></p>
												</div>
                                              <div class="shopglut-banner-actions">

											  <a href="#" class="shopglut-banner-button">
													<?php echo esc_html__('Shop Now', 'shopglut');  ?></a></div>
												</div>
										</div>

									</div>

                        
						<?php

						}
						?>
					</div>
		<?php
	}

	/**
	 * Render star rating
	 */
	private function render_stars($rating) {
		$output = '';
		$full_stars = floor($rating);
		$half_star = ($rating - $full_stars) >= 0.5;
		$empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

		// Full stars
		for ($i = 0; $i < $full_stars; $i++) {
			$output .= '<span class="star star-full">★</span>';
		}

		// Half star
		if ($half_star) {
			$output .= '<span class="star star-half">★</span>';
		}

		// Empty stars
		for ($i = 0; $i < $empty_stars; $i++) {
			$output .= '<span class="star star-empty">☆</span>';
		}

		return $output;
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_shopbanner_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row(
			$wpdb->prepare("SELECT layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_shopbanner_layouts` WHERE id = %d", $layout_id)
		);

		if (!$layout_data || empty($layout_data->layout_settings)) {
			return $this->getDefaultSettings();
		}

		$settings = maybe_unserialize($layout_data->layout_settings);

		if ($settings === false || !is_array($settings)) {
			return $this->getDefaultSettings();
		}

		// Try different possible settings structures (same logic as ShopBannerDataManage)
		$shopbanner_settings = null;

		// Check for the expected structure with page-settings
		if (isset($settings['shopg_product_shopbanner_settings_template1']['product_shopbanner-page-settings'])) {
			$shopbanner_settings = $this->flattenSettings($settings['shopg_product_shopbanner_settings_template1']['product_shopbanner-page-settings']);
		}
		// Check for the expected structure without page-settings
		elseif (isset($settings['shopg_product_shopbanner_settings_template1'])) {
			$shopbanner_settings = $this->flattenSettings($settings['shopg_product_shopbanner_settings_template1']);
		}
		// Check for direct template settings
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$shopbanner_settings = $this->flattenSettings($settings[$template_key]);
		}
		// Check if settings are directly at root level
		elseif (isset($settings['modal_overlay_color']) || isset($settings['enable_shopbanner'])) {
			$shopbanner_settings = $this->flattenSettings($settings);
		}

		if ($shopbanner_settings) {
			return $shopbanner_settings;
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
					if (is_array($setting_value) && isset($setting_value[$setting_key])) {
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} else {
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			} else {
				// Direct value assignment for non-array values
				$flat_settings[$group_key] = $group_values;
			}
		}

		// Recursively flatten if there are still nested arrays
		foreach ($flat_settings as $key => $value) {
			if (is_array($value)) {
				unset($flat_settings[$key]);
				$flat_settings = array_merge($flat_settings, $this->flattenSettings($value));
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			'layout_id' => 0,
			// Modal overlay settings
			'modal_overlay_color' => 'rgba(0, 0, 0, 0.75)',
			'modal_overlay_blur' => 4,
			// Modal content settings
			'modal_background_color' => '#ffffff',
			'modal_border_radius' => 12,
			'modal_max_width' => 1100,
			// Close button settings
			'close_button_size' => 40,
			'close_button_bg_color' => '#ffffff',
			'close_button_color' => '#374151',
			'close_button_hover_bg' => '#f3f4f6',
			'close_button_hover_color' => '#111827',
		);
	}
}
