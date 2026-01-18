<?php
namespace Shopglut\enhancements\ProductComparison\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data) {
		// Get settings
		$layout_id = isset($template_data['layout_id']) ? $template_data['layout_id'] : 0;
		$settings = $this->getLayoutSettings($layout_id);

		// Check if we're rendering real comparison data or demo data
		$products = isset($template_data['products']) ? $template_data['products'] : array();
		$is_demo = empty($products);

		if ($is_demo) {
			// Render demo comparison table
			$this->render_demo_comparison($settings);
		} else {
			// Render live comparison table
			$this->render_live_comparison($products, $settings);
		}
	}

	/**
	 * Render live comparison table with real WooCommerce products
	 */
	public function render_live_comparison($products, $settings) {
		if (empty($products)) {
			echo '<div class="shopglut-comparison-empty"><p>' . esc_html__('No products to compare. Please add products to compare.', 'shopglut') . '</p></div>';
			return;
		}

		// Get comparison table settings
		$table_settings = isset($settings['table_settings']) ? $settings['table_settings'] : array();
		$comparison_fields = isset($settings['comparison_fields']) ? $settings['comparison_fields'] : array();

		// Get individual settings with defaults
		$show_product_image = isset($table_settings['show_product_image']) ? $table_settings['show_product_image'] : true;
		$product_image_size = isset($table_settings['product_image_size']) ? $table_settings['product_image_size'] : 150;
		$table_layout = isset($table_settings['table_layout']) ? $table_settings['table_layout'] : 'vertical';
		$enable_sticky_header = isset($table_settings['enable_sticky_header']) ? $table_settings['enable_sticky_header'] : true;

		// Get field visibility settings with defaults
		$show_price = isset($comparison_fields['show_price']) ? $comparison_fields['show_price'] : true;
		$show_rating = isset($comparison_fields['show_rating']) ? $comparison_fields['show_rating'] : true;
		$show_stock_status = isset($comparison_fields['show_stock_status']) ? $comparison_fields['show_stock_status'] : true;
		$show_description = isset($comparison_fields['show_description']) ? $comparison_fields['show_description'] : true;
		$show_sku = isset($comparison_fields['show_sku']) ? $comparison_fields['show_sku'] : false;
		$show_categories = isset($comparison_fields['show_categories']) ? $comparison_fields['show_categories'] : true;
		$show_tags = isset($comparison_fields['show_tags']) ? $comparison_fields['show_tags'] : false;
		$show_attributes = isset($comparison_fields['show_attributes']) ? $comparison_fields['show_attributes'] : true;
		$show_add_to_cart = isset($comparison_fields['show_add_to_cart']) ? $comparison_fields['show_add_to_cart'] : true;

		$sticky_class = $enable_sticky_header ? 'sticky-header' : '';
		?>
		<div class="shopglut-product-comparison template1 layout-<?php echo esc_attr($table_layout); ?>" data-layout-id="<?php echo esc_attr($settings['layout_id'] ?? 0); ?>">
			<div class="comparison-container">
				<!-- Comparison Header -->
				<div class="comparison-header">
					<h2><?php echo esc_html__('Product Comparison', 'shopglut'); ?></h2>
					<button class="clear-all-btn">
						<?php echo esc_html__('Clear All', 'shopglut'); ?>
					</button>
				</div>

				<!-- Comparison Table -->
				<div class="comparison-table-wrapper">
					<table class="comparison-table <?php echo esc_attr($sticky_class); ?>">
						<thead>
							<tr>
								<th class="feature-column"><?php echo esc_html__('Feature', 'shopglut'); ?></th>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product" data-product-id="<?php echo esc_attr($product['id']); ?>">&times;</button>
										<?php if ($show_product_image && $wc_product->get_image_id()) : ?>
											<div class="product-image" style="max-width: <?php echo esc_attr($product_image_size); ?>px;">
												<?php echo wp_kses_post($wc_product->get_image('medium')); ?>
											</div>
										<?php endif; ?>
										<h3 class="product-title">
											<a href="<?php echo esc_url($wc_product->get_permalink()); ?>">
												<?php echo esc_html($wc_product->get_name()); ?>
											</a>
										</h3>
									</div>
								</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<!-- Price Row -->
							<?php if ($show_price) : ?>
							<tr class="price-row">
								<td class="feature-label"><?php echo esc_html__('Price', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<td class="product-value">
									<span class="price"><?php echo wp_kses_post($wc_product->get_price_html()); ?></span>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Rating Row -->
							<?php if ($show_rating) : ?>
							<tr class="rating-row">
								<td class="feature-label"><?php echo esc_html__('Rating', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
									$rating = $wc_product->get_average_rating();
								?>
								<td class="product-value">
									<?php if ($rating > 0) : ?>
										<div class="star-rating">
											<?php echo wp_kses_post(wc_get_rating_html($rating)); ?>
											<span class="rating-count">(<?php echo esc_html($wc_product->get_rating_count()); ?>)</span>
										</div>
									<?php else : ?>
										<span><?php echo esc_html__('No ratings', 'shopglut'); ?></span>
									<?php endif; ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Stock Status Row -->
							<?php if ($show_stock_status) : ?>
							<tr class="stock-row">
								<td class="feature-label"><?php echo esc_html__('Availability', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<td class="product-value">
									<?php if ($wc_product->is_in_stock()) : ?>
										<span class="in-stock"><?php echo esc_html__('In Stock', 'shopglut'); ?></span>
									<?php else : ?>
										<span class="out-of-stock"><?php echo esc_html__('Out of Stock', 'shopglut'); ?></span>
									<?php endif; ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Description Row -->
							<?php if ($show_description) : ?>
							<tr class="description-row">
								<td class="feature-label"><?php echo esc_html__('Description', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<td class="product-value">
									<div class="product-description">
										<?php echo wp_kses_post(wp_trim_words($wc_product->get_short_description(), 20)); ?>
									</div>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- SKU Row -->
							<?php if ($show_sku) : ?>
							<tr class="sku-row">
								<td class="feature-label"><?php echo esc_html__('SKU', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<td class="product-value">
									<?php echo esc_html($wc_product->get_sku() ? $wc_product->get_sku() : __('N/A', 'shopglut')); ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Categories Row -->
							<?php if ($show_categories) : ?>
							<tr class="categories-row">
								<td class="feature-label"><?php echo esc_html__('Categories', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
									$categories = wc_get_product_category_list($wc_product->get_id());
								?>
								<td class="product-value">
									<?php echo wp_kses_post($categories ? $categories : __('N/A', 'shopglut')); ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Tags Row -->
							<?php if ($show_tags) : ?>
							<tr class="tags-row">
								<td class="feature-label"><?php echo esc_html__('Tags', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
									$tags = wc_get_product_tag_list($wc_product->get_id());
								?>
								<td class="product-value">
									<?php echo wp_kses_post($tags ? $tags : __('N/A', 'shopglut')); ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>

							<!-- Product Attributes -->
							<?php if ($show_attributes) :
							?><?php
							// Get all unique attributes from all products
							$all_attributes = array();
							foreach ($products as $product) {
								$wc_product = wc_get_product($product['id']);
								if (!$wc_product) continue;
								$attributes = $wc_product->get_attributes();
								foreach ($attributes as $attribute) {
									if ($attribute->get_variation()) continue; // Skip variation attributes
									$name = $attribute->get_name();
									if (!isset($all_attributes[$name])) {
										$all_attributes[$name] = wc_attribute_label($name);
									}
								}
							}

							// Display attribute rows
							foreach ($all_attributes as $attr_name => $attr_label) :
							?>
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html($attr_label); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
									$attributes = $wc_product->get_attributes();
									$value = '';
									if (isset($attributes[$attr_name])) {
										$attribute = $attributes[$attr_name];
										if ($attribute->is_taxonomy()) {
											$terms = wc_get_product_terms($wc_product->get_id(), $attr_name, array('fields' => 'names'));
											$value = implode(', ', $terms);
										} else {
											$value = implode(', ', $attribute->get_options());
										}
									}
								?>
								<td class="product-value">
									<?php echo esc_html($value ? $value : __('N/A', 'shopglut')); ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endforeach; ?>
							<?php endif; ?>

							<!-- Add to Cart Row -->
							<?php if ($show_add_to_cart) : ?>
							<tr class="add-to-cart-row">
								<td class="feature-label"><?php echo esc_html__('Action', 'shopglut'); ?></td>
								<?php foreach ($products as $product) :
									$wc_product = wc_get_product($product['id']);
									if (!$wc_product) continue;
								?>
								<td class="product-value">
									<?php if ($wc_product->is_purchasable() && $wc_product->is_in_stock()) : ?>
										<a href="<?php echo esc_url($wc_product->add_to_cart_url()); ?>"
										   class="add-to-cart-button add-to-cart-btn"
										   data-product-id="<?php echo esc_attr($wc_product->get_id()); ?>"
										   data-url="<?php echo esc_url($wc_product->add_to_cart_url()); ?>">
											<?php echo esc_html($wc_product->add_to_cart_text()); ?>
										</a>
									<?php else : ?>
										<a href="<?php echo esc_url($wc_product->get_permalink()); ?>" class="view-product-button">
											<?php echo esc_html__('View Product', 'shopglut'); ?>
										</a>
									<?php endif; ?>
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo comparison table for preview
	 */
	public function render_demo_comparison($settings) {
		// Get comparison table settings
		$table_settings = isset($settings['table_settings']) ? $settings['table_settings'] : array();
		$comparison_fields = isset($settings['comparison_fields']) ? $settings['comparison_fields'] : array();

		// Get individual settings with defaults
		$show_product_image = isset($table_settings['show_product_image']) ? $table_settings['show_product_image'] : true;
		$product_image_size = isset($table_settings['product_image_size']) ? $table_settings['product_image_size'] : 150;
		$table_layout = isset($table_settings['table_layout']) ? $table_settings['table_layout'] : 'vertical';
		$enable_sticky_header = isset($table_settings['enable_sticky_header']) ? $table_settings['enable_sticky_header'] : true;

		// Get field visibility settings with defaults
		$show_price = isset($comparison_fields['show_price']) ? $comparison_fields['show_price'] : true;
		$show_rating = isset($comparison_fields['show_rating']) ? $comparison_fields['show_rating'] : true;
		$show_stock_status = isset($comparison_fields['show_stock_status']) ? $comparison_fields['show_stock_status'] : true;
		$show_description = isset($comparison_fields['show_description']) ? $comparison_fields['show_description'] : true;
		$show_sku = isset($comparison_fields['show_sku']) ? $comparison_fields['show_sku'] : false;
		$show_categories = isset($comparison_fields['show_categories']) ? $comparison_fields['show_categories'] : true;
		$show_tags = isset($comparison_fields['show_tags']) ? $comparison_fields['show_tags'] : false;
		$show_attributes = isset($comparison_fields['show_attributes']) ? $comparison_fields['show_attributes'] : true;
		$show_add_to_cart = isset($comparison_fields['show_add_to_cart']) ? $comparison_fields['show_add_to_cart'] : true;

		$sticky_class = $enable_sticky_header ? 'sticky-header' : '';
		?>
		<div class="shopglut-product-comparison template1 layout-<?php echo esc_attr($table_layout); ?>">
			<div class="comparison-container">
				<!-- Comparison Header -->
				<div class="comparison-header">
					<h2><?php echo esc_html__('Product Comparison', 'shopglut'); ?></h2>
					<button class="clear-all-btn">
						<?php echo esc_html__('Clear All', 'shopglut'); ?>
					</button>
				</div>

				<!-- Comparison Table -->
				<div class="comparison-table-wrapper">
					<table class="comparison-table <?php echo esc_attr($sticky_class); ?>">
						<thead>
							<tr>
								<th class="feature-column"><?php echo esc_html__('Feature', 'shopglut'); ?></th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<?php if ($show_product_image) : ?>
										<div class="product-image" style="max-width: <?php echo esc_attr($product_image_size); ?>px;">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 1">
										</div>
										<?php endif; ?>
										<h3 class="product-title">
											<a href="#">Premium Wireless Headphones</a>
										</h3>
									</div>
								</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<?php if ($show_product_image) : ?>
										<div class="product-image" style="max-width: <?php echo esc_attr($product_image_size); ?>px;">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 2">
										</div>
										<?php endif; ?>
										<h3 class="product-title">
											<a href="#">Professional Studio Monitor</a>
										</h3>
									</div>
								</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<?php if ($show_product_image) : ?>
										<div class="product-image" style="max-width: <?php echo esc_attr($product_image_size); ?>px;">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 3">
										</div>
										<?php endif; ?>
										<h3 class="product-title">
											<a href="#">Bluetooth Speaker Pro</a>
										</h3>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<!-- Price Row -->
							<?php if ($show_price) : ?>
							<tr class="price-row">
								<td class="feature-label"><?php echo esc_html__('Price', 'shopglut'); ?></td>
								<td class="product-value"><span class="price">$299.99</span></td>
								<td class="product-value"><span class="price">$449.99</span></td>
								<td class="product-value"><span class="price">$199.99</span></td>
							</tr>
							<?php endif; ?>

							<!-- Rating Row -->
							<?php if ($show_rating) : ?>
							<tr class="rating-row">
								<td class="feature-label"><?php echo esc_html__('Rating', 'shopglut'); ?></td>
								<td class="product-value">
									<div class="star-rating">★★★★★ <span class="rating-count">(245)</span></div>
								</td>
								<td class="product-value">
									<div class="star-rating">★★★★☆ <span class="rating-count">(128)</span></div>
								</td>
								<td class="product-value">
									<div class="star-rating">★★★★★ <span class="rating-count">(412)</span></div>
								</td>
							</tr>
							<?php endif; ?>

							<!-- Stock Status Row -->
							<?php if ($show_stock_status) : ?>
							<tr class="stock-row">
								<td class="feature-label"><?php echo esc_html__('Availability', 'shopglut'); ?></td>
								<td class="product-value"><span class="in-stock"><?php echo esc_html__('In Stock', 'shopglut'); ?></span></td>
								<td class="product-value"><span class="in-stock"><?php echo esc_html__('In Stock', 'shopglut'); ?></span></td>
								<td class="product-value"><span class="out-of-stock"><?php echo esc_html__('Out of Stock', 'shopglut'); ?></span></td>
							</tr>
							<?php endif; ?>

							<!-- Description Row -->
							<?php if ($show_description) : ?>
							<tr class="description-row">
								<td class="feature-label"><?php echo esc_html__('Description', 'shopglut'); ?></td>
								<td class="product-value">
									<div class="product-description">Premium noise-cancelling headphones with superior sound quality</div>
								</td>
								<td class="product-value">
									<div class="product-description">Professional-grade studio monitors for audio production</div>
								</td>
								<td class="product-value">
									<div class="product-description">Portable Bluetooth speaker with 360° sound</div>
								</td>
							</tr>
							<?php endif; ?>

							<!-- SKU Row -->
							<?php if ($show_sku) : ?>
							<tr class="sku-row">
								<td class="feature-label"><?php echo esc_html__('SKU', 'shopglut'); ?></td>
								<td class="product-value">WH-1000XM4</td>
								<td class="product-value">SM-PRO-500</td>
								<td class="product-value">BT-SPK-360</td>
							</tr>
							<?php endif; ?>

							<!-- Categories Row -->
							<?php if ($show_categories) : ?>
							<tr class="categories-row">
								<td class="feature-label"><?php echo esc_html__('Categories', 'shopglut'); ?></td>
								<td class="product-value">Audio, Headphones</td>
								<td class="product-value">Audio, Studio Equipment</td>
								<td class="product-value">Audio, Speakers</td>
							</tr>
							<?php endif; ?>

							<!-- Tags Row -->
							<?php if ($show_tags) : ?>
							<tr class="tags-row">
								<td class="feature-label"><?php echo esc_html__('Tags', 'shopglut'); ?></td>
								<td class="product-value">Wireless, Premium, Audio</td>
								<td class="product-value">Professional, Studio</td>
								<td class="product-value">Portable, Bluetooth</td>
							</tr>
							<?php endif; ?>

							<!-- Product Attributes -->
							<?php if ($show_attributes) : ?>
							<!-- Attribute: Brand -->
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html__('Brand', 'shopglut'); ?></td>
								<td class="product-value">Sony</td>
								<td class="product-value">Yamaha</td>
								<td class="product-value">JBL</td>
							</tr>

							<!-- Attribute: Color -->
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html__('Color', 'shopglut'); ?></td>
								<td class="product-value">Black, Silver</td>
								<td class="product-value">Black</td>
								<td class="product-value">Blue, Red, Black</td>
							</tr>

							<!-- Attribute: Wireless -->
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html__('Wireless', 'shopglut'); ?></td>
								<td class="product-value">Yes (Bluetooth 5.0)</td>
								<td class="product-value">No</td>
								<td class="product-value">Yes (Bluetooth 5.1)</td>
							</tr>

							<!-- Attribute: Battery Life -->
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html__('Battery Life', 'shopglut'); ?></td>
								<td class="product-value">30 hours</td>
								<td class="product-value">N/A</td>
								<td class="product-value">12 hours</td>
							</tr>

							<!-- Attribute: Weight -->
							<tr class="attribute-row">
								<td class="feature-label"><?php echo esc_html__('Weight', 'shopglut'); ?></td>
								<td class="product-value">250g</td>
								<td class="product-value">8.5kg</td>
								<td class="product-value">540g</td>
							</tr>
							<?php endif; ?>

							<!-- Add to Cart Row -->
							<?php if ($show_add_to_cart) : ?>
							<tr class="add-to-cart-row">
								<td class="feature-label"><?php echo esc_html__('Action', 'shopglut'); ?></td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button"><?php echo esc_html__('Add to Cart', 'shopglut'); ?></a>
								</td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button"><?php echo esc_html__('Add to Cart', 'shopglut'); ?></a>
								</td>
								<td class="product-value">
									<a href="#" class="view-product-button"><?php echo esc_html__('View Product', 'shopglut'); ?></a>
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
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
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$layout_data = $wpdb->get_row(
			$wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_comparison_layouts` WHERE id = %d", $layout_id)
		);

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings'])) {
				return $settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings'];
			}
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Get specific setting value with default fallback
	 */
	private function getSetting($settings, $tab, $fieldset, $field, $default = null) {
		if (isset($settings[$tab][$fieldset][$field])) {
			return $settings[$tab][$fieldset][$field];
		}
		return $default;
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
		);
	}
}
