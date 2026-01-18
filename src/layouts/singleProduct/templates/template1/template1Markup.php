<?php
namespace Shopglut\layouts\singleProduct\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

// Include Template1 AJAX handler
require_once __DIR__ . '/template1-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template1Markup {


	public function layout_render($template_data) {
		// Get settings for this layout
		$settings = $this->getLayoutSettings($template_data['layout_id'] ?? 0);

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			echo '<div class="shopglut-error">' . esc_html__('WooCommerce is required for this cart layout.', 'shopglut') . '</div>';
			return;
		}

		

		// Check if we're in admin area or cart is not available
		$is_admin_preview = is_admin();


		?>
		<div class="shopglut-single-product template1 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
			<div class="single-product-container">
				<?php if ($is_admin_preview): ?>
					<!-- Admin Preview Mode -->
					<div class="demo-content responsive-preview shopglut-demo-mode" data-demo-mode="true" style="position: relative;">
						<style>
							.shopglut-demo-mode a[href],
							.shopglut-demo-mode form[action] {
								pointer-events: none !important;
							}
							.shopglut-demo-mode .qty-decrease,
							.shopglut-demo-mode .qty-increase,
							.shopglut-demo-mode .size-option,
							.shopglut-demo-mode .color-swatch,
							.shopglut-demo-mode .attribute-value,
							.shopglut-demo-mode .add-to-cart-btn,
							.shopglut-demo-mode .quick-add-btn,
							.shopglut-demo-mode .wc-tabs a,
							.shopglut-demo-mode .tabs a,
							.shopglut-demo-mode .woocommerce-tabs a,
							.shopglut-demo-mode .thumbnail-item {
								pointer-events: auto !important;
							}
							.shopglut-demo-mode form {
								display: inline;
							}
						</style>
						<?php $this->render_demo_single_product($settings); ?>
					</div>
				<?php else: ?>
					<!-- Live Product Mode -->
					<div class="live-content responsive-live">
						<?php $this->render_live_single_product($settings); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}


	/**
	 * Render badge directly from badge layout ID (bypasses product condition checks)
	 * This is used for both demo mode and live mode in template1
	 */
	private function render_badge_directly($badge_layout_id) {
		if (!$badge_layout_id || !class_exists('Shopglut\enhancements\ProductBadges\BadgeDataManage')) {
			return '';
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query
		$badge = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$table_name}` WHERE id = %d", $badge_layout_id));

		if (!$badge) {
			return '';
		}

		$badge_settings = maybe_unserialize($badge->layout_settings);

		// Get badge types from nested structure
		$badge_types = isset($badge_settings['shopg_product_badge_settings']['badge_type'])
			? $badge_settings['shopg_product_badge_settings']['badge_type']
			: array();

		// Get inner badge settings from nested structure
		$badge_settings_inner = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
			? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
			: array();

		$html = '';
		// Render all available badge types
		foreach ($badge_types as $badge_type) {
			$text_key = $badge_type . '_badge_text';
			$badge_text = isset($badge_settings_inner[$text_key]) ? $badge_settings_inner[$text_key] : strtoupper(str_replace('_', ' ', $badge_type));

			// Get badge styles - handle array format
			$prefix = $badge_type . '_badge_';

			// Helper to extract value from potentially nested array
			$get_value = function($key, $default = '') use ($badge_settings_inner) {
				if (isset($badge_settings_inner[$key])) {
					$val = $badge_settings_inner[$key];
					if (is_array($val)) {
						return isset($val[$key]) ? $val[$key] : $default;
					}
					return $val;
				}
				return $default;
			};

			$text_color = $get_value($prefix . 'text_color', '#ffffff');
			$bg_color = $get_value($prefix . 'bg_color', '#ff0000');
			$font_size = intval($get_value($prefix . 'font_size', 12));
			$font_weight = $get_value($prefix . 'font_weight', '700');
			$padding_v = intval($get_value($prefix . 'padding_v', 5));
			$padding_h = intval($get_value($prefix . 'padding_h', 10));
			$border_radius = intval($get_value($prefix . 'border_radius', 3));

			$style = "color: {$text_color}; background-color: {$bg_color}; font-size: {$font_size}px; font-weight: {$font_weight}; padding: {$padding_v}px {$padding_h}px; border-radius: {$border_radius}px; display: inline-block;";
			$html .= '<span class="shopglut-badge shopglut-badge-' . esc_attr($badge_layout_id) . ' shopglut-badge-type-' . esc_attr($badge_type) . '" style="' . esc_attr($style) . '">' . esc_html($badge_text) . '</span> ';
		}

		return $html;
	}

	/**
	 * Render demo single product for admin preview
	 */
	private function render_demo_single_product($settings) {
		// Get badge layout ID for demo mode
		$badge_layout_id = $this->getSetting($settings, 'badge_layout_id', 0);
		$demo_badge_html = $this->render_badge_directly($badge_layout_id);

		// Get badge CSS for demo
		$badge_custom_css = '';
		if ($badge_layout_id && class_exists('Shopglut\enhancements\ProductBadges\BadgeDataManage')) {
			$badge_manager = \Shopglut\enhancements\ProductBadges\BadgeDataManage::get_instance();
			if (method_exists($badge_manager, 'get_badge_css')) {
				$badge_custom_css = $badge_manager->get_badge_css($badge_layout_id);
			}
		}

		// Add custom CSS for template1 badge positioning
		?>
		<style>
			/* Demo Badge Styles - loaded dynamically from selected badge layout */
			<?php echo wp_kses_post($badge_custom_css); ?>

		</style>
		<?php

		// Remove the placeholder since we're using real product data now

		$placeholder_url = SHOPGLUT_URL . 'global-assets/images/demo-image.png';

		// Demo content data
		$demo_badges = array(
			array('text' => 'New', 'type' => 'new'),
			array('text' => 'Trending', 'type' => 'trending'),
			array('text' => 'Best Seller', 'type' => 'bestseller')
		);

		$demo_features = isset($settings['product_features']) ? $settings['product_features'] : array(
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-shipping-fast',
				'feature_title' => 'Free Shipping',
				'feature_description' => 'Free shipping on orders over $50',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-undo',
				'feature_title' => 'Easy Returns',
				'feature_description' => '30-day hassle-free returns',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-shield-alt',
				'feature_title' => 'Secure Payment',
				'feature_description' => '100% secure payment processing',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-headset',
				'feature_title' => '24/7 Support',
				'feature_description' => 'Round-the-clock customer support',
				'feature_link_enabled' => false,
			),
		);

		$demo_related_products = array(
			array('name' => 'Premium Bluetooth Speaker', 'price' => '$149.99', 'original' => '$189.99', 'badge' => '-20%', 'rating' => 4.0, 'reviews' => 89),
			array('name' => 'Pro Gaming Headset RGB', 'price' => '$199.99', 'original' => '$249.99', 'badge' => 'New', 'rating' => 5.0, 'reviews' => 156),
			array('name' => 'Wireless Earbuds Pro', 'price' => '$129.99', 'original' => '', 'badge' => '', 'rating' => 5.0, 'reviews' => 234),
			array('name' => 'Studio Reference Monitor', 'price' => '$349.99', 'original' => '$399.99', 'badge' => 'Hot', 'rating' => 4.0, 'reviews' => 67),
		);

		?>

		<div class="shopglut-single-product-container">
			<!-- Product Main Section -->
			<div class="product-main-wrapper">

				<!-- Product Gallery -->
				<div class="product-gallery-section">
					<div class="main-image-container"
						data-lightbox-enabled="<?php echo esc_attr($this->getSetting($settings, 'enable_image_lightbox', true) ? 'true' : 'false'); ?>"
						data-cursor-style="<?php echo esc_attr($this->getSetting($settings, 'main_image_cursor', 'zoom-in')); ?>"
						data-hover-zoom-enabled="<?php echo esc_attr($this->getSetting($settings, 'enable_image_hover_zoom', false) ? 'true' : 'false'); ?>"
						data-hover-zoom-level="<?php echo esc_attr($this->getSetting($settings, 'hover_zoom_level', 2)); ?>">
						<!-- Badge Display Area - Only show if badges are enabled AND badge_position is on_product_image -->
						<?php
						$enable_badges = $this->getSetting($settings, 'enable_badges', true);
						$badge_pos = $this->getSetting($settings, 'badge_position', 'on_product_image');
						if ($enable_badges && $badge_pos === 'on_product_image'):
						?>
							<div class="shopglut-badge-wrapper shopglut-badge-on-image">
								<?php
								// For demo mode, use pre-rendered badge HTML
								echo wp_kses_post($demo_badge_html);
								?>
							</div>
						<?php endif; ?>

						<div class="image-loading-placeholder" style="display: none;">
							<div class="loading-spinner"></div>
						</div>
						<img src="<?php echo esc_url($placeholder_url); ?>"
							 alt="<?php esc_attr_e('Ultra Premium Wireless Headphones', 'shopglut'); ?>"
							 class="main-product-image template-preview-image loaded"
							 data-image-full="<?php echo esc_url($placeholder_url); ?>"
							 data-index="0"
							>
					</div>

					<?php if ($this->shouldShowThumbnails($settings)): ?>
					<div class="thumbnail-gallery"
						data-alignment="<?php echo esc_attr($this->getSetting($settings, 'thumbnail_alignment', 'flex-start')); ?>"
						data-hover-scale="<?php echo esc_attr($this->getSetting($settings, 'thumbnail_hover_scale', true) ? 'true' : 'false'); ?>">
						<?php for ($i = 0; $i < 4; $i++): ?>
							<div class="thumbnail-item <?php echo $i === 0 ? 'active' : ''; ?>"
								data-index="<?php echo esc_attr($i); ?>"
								data-image-full="<?php echo esc_url($placeholder_url); ?>"
								>
								<img src="<?php echo esc_url($placeholder_url); ?>"
									 alt="<?php esc_attr_e('Product thumbnail', 'shopglut'); ?>"
									 class="thumbnail-image template-preview-image loaded">
							</div>
						<?php endfor; ?>
					</div>
					<?php endif; ?>
				</div>

				<!-- Product Info -->
				<div class="product-info-section">

					<!-- Badges before title -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					if ($enable_badges && $this->getSetting($settings, 'badge_position', 'on_product_image') === 'before_product_title'):
					?>
						<div class="shopglut-badge-wrapper shopglut-badge-before-title">
							<?php
							// For demo mode, use pre-rendered badge HTML
							echo wp_kses_post($demo_badge_html);
							?>
						</div>
					<?php endif; ?>

					<!-- Product Title -->
					<h1 class="product-title">
						<?php esc_html_e('Ultra Premium Wireless Headphones', 'shopglut'); ?>
					</h1>

					<!-- Badges after title -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					$badge_pos_after = $this->getSetting($settings, 'badge_position', 'on_product_image');
					if ($enable_badges && $badge_pos_after === 'after_product_title'):
					?>
					<div class="shopglut-badge-wrapper shopglut-badge-after-title">
						<?php
						// For demo mode, use pre-rendered badge HTML
						echo wp_kses_post($demo_badge_html);
						?>
					</div>
					<?php endif; ?>

					<!-- Module Integration: After product title position for custom fields -->
					<?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_product_title', 'custom_fields'); ?>

					<!-- Product Rating -->
					<?php if ($this->shouldShowRating($settings)): ?>
					<div class="rating-section">
						<div class="stars-container">
							<?php echo wp_kses_post($this->renderStars(4.8, $settings)); ?>
						</div>
						<span class="rating-text">
							<?php esc_html_e('4.8 (1,247 reviews)', 'shopglut'); ?>
						</span>
					</div>
					<?php endif; ?>

					<!-- Module Integration: Before price position -->
					<?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_price', 'badges'); ?>
					<?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_price', 'custom_fields'); ?>

					<!-- Badges before price -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					$badge_pos_price = $this->getSetting($settings, 'badge_position', 'on_product_image');
					if ($enable_badges && $badge_pos_price === 'before_price'):
					?>
					<div class="shopglut-badge-wrapper shopglut-badge-before-price">
						<?php
						// For demo mode, use pre-rendered badge HTML
						echo wp_kses_post($demo_badge_html);
						?>
					</div>
					<?php endif; ?>

					<!-- Product Price -->
					<div class="price-section">
						<span class="current-price">$299.99</span>
						<span class="original-price">$399.99</span>
						<span class="discount-badge">25% OFF</span>
					</div>

					<!-- Product Description -->
					<?php if ($this->shouldShowDescription($settings)): ?>
					<div class="product-description">
						<?php esc_html_e('Experience unparalleled audio quality with our flagship wireless headphones. Featuring advanced noise cancellation technology, premium materials, and 40-hour battery life for the ultimate listening experience.', 'shopglut'); ?>
					</div>
					<?php endif; ?>

					<!-- Module Integration: After description (Custom Fields) -->
					<?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_description', 'custom_fields'); ?>

					<!-- Product Attributes -->

					<!-- Purchase Section -->
					<div class="purchase-section">
						<!-- Demo Variable Product Example -->
						<div class="demo-variation-wrapper">

							<div class="variation-selector">
								<div class="variation-group">
									<label class="variation-label">
										<span class="label-text">Size</span>
										<span class="label-required">*</span>
									</label>
									<div class="variation-options">
										<button type="button" class="size-option" data-value="small" onclick="return false;">
											<span class="size-label">S</span>
											<span class="size-detail">Small</span>
										</button>
										<button type="button" class="size-option active" data-value="medium" onclick="return false;">
											<span class="size-label">M</span>
											<span class="size-detail">Medium</span>
										</button>
										<button type="button" class="size-option" data-value="large" onclick="return false;">
											<span class="size-label">L</span>
											<span class="size-detail">Large</span>
										</button>
										<button type="button" class="size-option" data-value="xlarge" onclick="return false;">
											<span class="size-label">XL</span>
											<span class="size-detail">Extra Large</span>
										</button>
									</div>
								</div>
							</div>

						</div>

						<!-- Module Integration: Before Add to Cart -->
						<?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_add_to_cart'); ?>

						<div class="quantity-cart-wrapper">
							<div class="quantity-selector">
								<button type="button" class="qty-decrease" onclick="return false;">-</button>
								<input type="number" class="qty-input" value="1" min="1"
									  >
								<button type="button" class="qty-increase" onclick="return false;">+</button>
							</div>
							<button type="button" class="add-to-cart-btn" onclick="return false;">
								<i class="fas fa-shopping-cart"></i> <span><?php esc_html_e('Add to Cart', 'shopglut'); ?></span>
							</button>
						</div>

						<!-- Module Integration: After Add to Cart -->
						<?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_add_to_cart', 'custom_fields'); ?>
					</div>
				</div>
			</div>

			<!-- Features Section -->
			<?php
			$product_features = $this->getSetting($settings, 'product_features', $demo_features);

			if ($this->shouldShowFeaturesSection($settings) && !empty($product_features)):
			?>
			<div class="features-section">
				<?php if ($this->shouldShowFeaturesSectionTitle($settings)): ?>
				<h2 class="features-title">
					<?php echo esc_html($this->getFeaturesSectionTitle($settings)); ?>
				</h2>
				<?php endif; ?>

				<div class="features-grid">
					<?php foreach ($product_features as $feature): ?>
					<div class="feature-item">
						<?php if (isset($feature['feature_link_enabled']) && $feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
						<a href="<?php echo esc_url($feature['feature_link_url']); ?>"
						   target="<?php echo esc_attr($feature['feature_link_target'] ?? '_self'); ?>"
						  >
						<?php endif; ?>

						<div class="feature-icon">
							<?php echo wp_kses_post($this->renderFeatureIcon($feature, $settings)); ?>
						</div>

						<div class="feature-content">
							<div class="feature-title">
								<?php echo esc_html($feature['feature_title']); ?>
							</div>
							<div class="feature-description">
								<?php echo esc_html($feature['feature_description']); ?>
							</div>
						</div>

						<?php if (isset($feature['feature_link_enabled']) && $feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<!-- Custom Product Tabs -->
			<?php
			$product_tabs = $this->getSetting($settings, 'product_tabs_list', array());
			if (!empty($product_tabs)):
			?>
			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs">
					<?php $tab_index = 0; foreach ($product_tabs as $tab): $tab_index++; ?>
					<li class="<?php echo $tab_index === 1 ? 'active' : ''; ?>">
						<a href="#tab-<?php echo esc_attr($tab_index); ?>">
							<?php if (!empty($tab['tab_icon'])): ?>
							<i class="<?php echo esc_attr($tab['tab_icon']); ?>"></i>
							<?php endif; ?>
							<?php echo esc_html($tab['tab_title']); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php $tab_index = 0; foreach ($product_tabs as $tab): $tab_index++; ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--custom panel entry-content wc-tab <?php echo $tab_index === 1 ? 'active' : ''; ?>" id="tab-<?php echo esc_attr($tab_index); ?>">
					<div class="custom-tab-content">
						<?php
						$tab_content = isset($tab['tab_content']) ? $tab['tab_content'] : '';
						echo wp_kses_post($tab_content);
						?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<!-- Related Products -->
			<?php if ($this->shouldShowRelatedProducts($settings)): ?>
			<div class="related-products-section">
				<h2 class="related-products-title">
					<?php echo esc_html($this->getRelatedProductsTitle($settings)); ?>
				</h2>

				<div class="related-products-grid">
					<?php foreach ($demo_related_products as $product): ?>
					<div class="related-product-card">
						<div class="related-product-image">
							<?php if (!empty($product['badge'])): ?>
							<div class="related-product-badge">
								<?php echo esc_html($product['badge']); ?>
							</div>
							<?php endif; ?>
							<img src="<?php echo esc_url($placeholder_url); ?>"
								 alt="<?php echo esc_attr($product['name']); ?>"
								 class="related-product-img template-preview-image loaded">
						</div>

						<div class="related-product-info">
							<div class="related-product-name">
								<?php echo esc_html($product['name']); ?>
							</div>

							<div class="related-product-rating">
								<?php echo wp_kses_post($this->renderStars($product['rating'], $settings)); ?>
								<span class="related-product-reviews">(<?php echo esc_html($product['reviews']); ?>)</span>
							</div>

							<div class="related-product-price">
								<span class="related-current-price"><?php echo esc_html($product['price']); ?></span>
								<?php if (!empty($product['original'])): ?>
								<span class="related-original-price"><?php echo esc_html($product['original']); ?></span>
								<?php endif; ?>
							</div>
						</div>

						<button type="button" class="quick-add-btn" onclick="return false;">
							<?php esc_html_e('Quick Add', 'shopglut'); ?>
						</button>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render live single product for frontend
	 */
	private function render_live_single_product($settings) {
		// Remove ALL automatic BadgeDataManage hooks so we can control badge position manually
		if (class_exists('Shopglut\enhancements\ProductBadges\BadgeDataManage')) {
			$badge_instance = \Shopglut\enhancements\ProductBadges\BadgeDataManage::get_instance();
			remove_action('woocommerce_before_shop_loop_item_title', array($badge_instance, 'display_badges_on_product_image'), 5);
			remove_action('woocommerce_before_single_product_summary', array($badge_instance, 'display_badges_on_product_image'), 15);
			remove_action('woocommerce_before_shop_loop_item', array($badge_instance, 'display_badges_before_title'), 20);
			remove_action('woocommerce_single_product_summary', array($badge_instance, 'display_badges_before_title'), 4);
			remove_action('wp_footer', array($badge_instance, 'add_badge_positioning_script'));
		}

		// Add custom CSS for template1 badge positioning
		?>
		<style>
			/* Template1 Badge Positioning - Specific to template1 class */

			/* Template1: Badge wrapper general styles */
			.template1 .shopglut-badge-wrapper {
				z-index: 10;
				display: inline-block;
			}

			/* Template1: Badge wrapper on product image - absolute positioning */
			.template1 .shopglut-badge-wrapper.shopglut-badge-on-image {
				position: absolute;
				top: 10px;
				left: 10px;
				z-index: 20;
				display: flex;
				flex-direction: column;
				gap: 5px;
				align-items: flex-start;
			}

			/* Template1: Badge wrappers in non-image positions - static/inline */
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price {
				position: static;
				display: inline-block;
				margin: 0 10px 10px 0;
			}

			/* Template1: Badge inside wrappers - override absolute positioning */
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badges-container,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badges-container,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badges-container {
				position: static !important;
				display: inline-block;
			}

			/* Template1: For badges without position wrappers (from display_badges_raw) */
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge {
				display: inline-block;
				margin: 0 5px 5px 0;
			}

			/* Template1: Badge inside on-image wrapper - preserve badge styles */
			.template1 .shopglut-badge-wrapper.shopglut-badge-on-image .shopglut-badge {
				display: inline-block;
				margin: 0;
			}

			/* Template1: Any position classes inside non-image wrappers - override */
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge-position-top-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge-position-top-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge-position-bottom-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge-position-bottom-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-title .shopglut-badge-position-center,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge-position-top-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge-position-top-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge-position-bottom-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge-position-bottom-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-after-title .shopglut-badge-position-center,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge-position-top-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge-position-top-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge-position-bottom-left,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge-position-bottom-right,
			.template1 .shopglut-badge-wrapper.shopglut-badge-before-price .shopglut-badge-position-center {
				position: static !important;
				top: auto !important;
				left: auto !important;
				right: auto !important;
				bottom: auto !important;
				transform: none !important;
				display: inline-block;
				margin: 0 5px 5px 0;
			}

			/* Template1: Badge span styling */
			.template1 .shopglut-badge {
				display: inline-block;
			}
		</style>
		<?php

		global $product;
		if (!$product) {
			global $post;
			$product = wc_get_product($post->ID);
		}

		if (!$product) {
			echo '<div class="shopglut-error">Product not found.</div>';
			return;
		}

		// Get real product data
		// Ensure $product is a valid WC_Product object
		if (!is_object($product) || !method_exists($product, 'get_id')) {
			global $post;
			$product = wc_get_product($post->ID ?? get_the_ID());
		}

		if (!$product || !is_object($product)) {
			echo '<div class="shopglut-error">Unable to load product data.</div>';
			return;
		}

		$product_id = $product->get_id();
		$product_title = $product->get_name();
		$product_description = $product->get_short_description();
		$product_price = $product->get_price_html();
		$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
		$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();

		// Get detailed price information
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$current_price = $product->get_price();
		$currency_symbol = get_woocommerce_currency_symbol();
		$is_on_sale = $product->is_on_sale();

		// Calculate discount percentage
		$discount_percentage = 0;
		if ($is_on_sale && $regular_price && $sale_price) {
			$discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
		}

		// Get product gallery images
		$attachment_ids = $product->get_gallery_image_ids();

		// Get product rating
		$average_rating = $product->get_average_rating();
		$rating_count = $product->get_rating_count();

		// Get product attributes
		$attributes = $product->get_attributes();

		// Get related products (real ones)
		$related_ids = wc_get_related_products($product_id, 4);
		$related_products = array();
		foreach ($related_ids as $related_id) {
			$related_product = wc_get_product($related_id);
			if ($related_product) {
				$related_image = wp_get_attachment_image_src(get_post_thumbnail_id($related_id), 'medium');
				$related_products[] = array(
					'id' => $related_id,
					'name' => $related_product->get_name(),
					'price' => $related_product->get_price_html(),
					'image' => $related_image ? $related_image[0] : wc_placeholder_img_src(),
					'rating' => $related_product->get_average_rating(),
					'reviews' => $related_product->get_rating_count(),
					'link' => get_permalink($related_id)
				);
			}
		}

		// Product badges (based on product data)
		$product_badges = array();
		if ($product->is_on_sale()) {
			$product_badges[] = array('text' => 'Sale', 'type' => 'sale');
		}
		if ($product->is_featured()) {
			$product_badges[] = array('text' => 'Featured', 'type' => 'featured');
		}
		// Check if product is new (created within last 30 days)
		$created_date = get_the_date('U', $product_id);
		if ($created_date > strtotime('-30 days')) {
			$product_badges[] = array('text' => 'New', 'type' => 'new');
		}

		// Features from settings (keep these as configured)
		$demo_features = isset($settings['product_features']) ? $settings['product_features'] : array(
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-shipping-fast',
				'feature_title' => 'Free Shipping',
				'feature_description' => 'Free shipping on orders over $50',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-undo',
				'feature_title' => 'Easy Returns',
				'feature_description' => '30-day hassle-free returns',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-shield-alt',
				'feature_title' => 'Secure Payment',
				'feature_description' => '100% secure payment processing',
				'feature_link_enabled' => false,
			),
			array(
				'feature_icon_type' => 'fontawesome',
				'feature_fontawesome_icon' => 'fas fa-headset',
				'feature_title' => '24/7 Support',
				'feature_description' => 'Round-the-clock customer support',
				'feature_link_enabled' => false,
			),
		);

		// Enqueue frontend JavaScript for live version
		$script_dependencies = array('jquery', 'wc-add-to-cart');

		// Add variation scripts for variable products
		if ($product && $product->is_type('variable')) {
			$script_dependencies[] = 'wc-add-to-cart-variation';
			wp_enqueue_script('wc-add-to-cart-variation');
		}

		wp_enqueue_script(
			'shopglut-template1-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template1/template1-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template1-frontend', 'shopglut_frontend_vars', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'product_id' => $product_id,
			'nonce' => wp_create_nonce('shopglut_frontend_nonce')
		));

		?>

		<div class="shopglut-single-product-container" data-product-id="<?php echo esc_attr($product_id); ?>">
			<!-- Product Main Section -->
			<div class="product-main-wrapper">

				<!-- Product Gallery -->
				<div class="product-gallery-section">
					<div class="main-image-container"
						data-lightbox-enabled="<?php echo esc_attr($this->getSetting($settings, 'enable_image_lightbox', true) ? 'true' : 'false'); ?>"
						data-cursor-style="<?php echo esc_attr($this->getSetting($settings, 'main_image_cursor', 'zoom-in')); ?>"
						data-hover-zoom-enabled="<?php echo esc_attr($this->getSetting($settings, 'enable_image_hover_zoom', false) ? 'true' : 'false'); ?>"
						data-hover-zoom-level="<?php echo esc_attr($this->getSetting($settings, 'hover_zoom_level', 2)); ?>">
						<!-- Badge Display Area - Only show if badges are enabled AND badge_position is on_product_image -->
						<?php
						$enable_badges = $this->getSetting($settings, 'enable_badges', true);
						$badge_pos_live = $this->getSetting($settings, 'badge_position', 'on_product_image');
						if ($enable_badges && $badge_pos_live === 'on_product_image'):
						?>
							<div class="shopglut-badge-wrapper shopglut-badge-on-image">
								<?php
								$badge_layout_id = $this->getSetting($settings, 'badge_layout_id', 0);
								echo wp_kses_post($this->render_badge_directly($badge_layout_id));
								?>
							</div>
						<?php endif; ?>

						<div class="image-loading-placeholder" style="display: none;">
							<div class="loading-spinner"></div>
						</div>
						<img src="<?php echo esc_url($product_image_url); ?>"
							 alt="<?php echo esc_attr($product_title); ?>"
							 class="main-product-image template-preview-image loaded"
							 data-image-full="<?php echo esc_url($product_image_url); ?>"
							 data-index="0"
							>
					</div>

					<?php if ($this->shouldShowThumbnails($settings) && (!empty($attachment_ids) || $product_image)): ?>
					<div class="thumbnail-gallery"
						data-alignment="<?php echo esc_attr($this->getSetting($settings, 'thumbnail_alignment', 'flex-start')); ?>"
						data-hover-scale="<?php echo esc_attr($this->getSetting($settings, 'thumbnail_hover_scale', true) ? 'true' : 'false'); ?>">
						<?php
						$thumbnail_index = 0;
						// Main image thumbnail
						if ($product_image): ?>
							<div class="thumbnail-item <?php echo $thumbnail_index === 0 ? 'active' : ''; ?>"
								data-index="<?php echo esc_attr($thumbnail_index); ?>"
								data-image-full="<?php echo esc_url($product_image_url); ?>">
								<img src="<?php echo esc_url($product_image[0]); ?>"
									 alt="<?php echo esc_attr($product_title); ?>"
									 class="thumbnail-image template-preview-image loaded">
							</div>
						<?php
							$thumbnail_index++;
						endif;
						// Gallery thumbnails
						foreach ($attachment_ids as $index => $attachment_id):
							$gallery_image_full = wp_get_attachment_image_src($attachment_id, 'full');
							$gallery_image = wp_get_attachment_image_src($attachment_id, 'medium');
							if ($gallery_image): ?>
								<div class="thumbnail-item <?php echo $thumbnail_index === 0 ? 'active' : ''; ?>"
									data-index="<?php echo esc_attr($thumbnail_index); ?>"
									data-image-full="<?php echo esc_url($gallery_image_full[0]); ?>">
									<img src="<?php echo esc_url($gallery_image[0]); ?>"
										 alt="<?php echo esc_attr($product_title . ' gallery ' . ($index + 1)); ?>"
										 class="thumbnail-image template-preview-image loaded">
								</div>
							<?php
								$thumbnail_index++;
							endif;
						endforeach; ?>
					</div>
					<?php endif; ?>
				</div>

				<!-- Product Info -->
				<div class="product-info-section">

					<!-- Badges before title -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					if ($enable_badges && $this->getSetting($settings, 'badge_position', 'on_product_image') === 'before_product_title'):
					?>
					<div class="shopglut-badge-wrapper shopglut-badge-before-title">
						<?php
						$badge_layout_id = $this->getSetting($settings, 'badge_layout_id', 0);
						echo wp_kses_post($this->render_badge_directly($badge_layout_id));
						?>
					</div>
					<?php endif; ?>

					<!-- Product Title -->
					<h1 class="product-title">
						<?php echo esc_html($product_title); ?>
					</h1>

					<!-- Badges after title -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					$badge_pos_after = $this->getSetting($settings, 'badge_position', 'on_product_image');
					if ($enable_badges && $badge_pos_after === 'after_product_title'):
					?>
					<div class="shopglut-badge-wrapper shopglut-badge-after-title">
						<?php
						$badge_layout_id = $this->getSetting($settings, 'badge_layout_id', 0);
						echo wp_kses_post($this->render_badge_directly($badge_layout_id));
						?>
					</div>
					<?php endif; ?>

					<!-- Module Integration: After product title position for custom fields -->
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_product_title', 'custom_fields'); ?>

					<!-- Product Rating -->
					<?php if ($this->shouldShowRating($settings) && ($average_rating > 0 || $rating_count > 0)): ?>
					<div class="rating-section">
						<div class="stars-container">
							<?php echo wp_kses_post($this->renderStars($average_rating, $settings)); ?>
						</div>
						<span class="rating-text">
							<?php echo esc_html($average_rating . ' (' . $rating_count . ' reviews)'); ?>
						</span>
					</div>
					<?php endif; ?>

					<!-- Module Integration: Before price position -->
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_price', 'badges'); ?>
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_price', 'custom_fields'); ?>

					<!-- Badges before price -->
					<?php
					$enable_badges = $this->getSetting($settings, 'enable_badges', true);
					$badge_pos_price_live = $this->getSetting($settings, 'badge_position', 'on_product_image');
					if ($enable_badges && $badge_pos_price_live === 'before_price'):
					?>
						<div class="shopglut-badge-wrapper shopglut-badge-before-price">
							<?php
							$badge_layout_id = $this->getSetting($settings, 'badge_layout_id', 0);
							echo wp_kses_post($this->render_badge_directly($badge_layout_id));
							?>
						</div>
					<?php endif; ?>

					<!-- Product Price -->
					<div class="price-section">
						<span class="current-price"><?php echo esc_html($currency_symbol . number_format((float)$current_price, 2)); ?></span>
						<?php if ($is_on_sale && $regular_price): ?>
							<span class="original-price"><?php echo esc_html($currency_symbol . number_format((float)$regular_price, 2)); ?></span>
							<?php if ($discount_percentage > 0): ?>
								<span class="discount-badge"><?php echo esc_html($discount_percentage . '% OFF'); ?></span>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<!-- Product Description -->
					<?php if ($this->shouldShowDescription($settings) && !empty($product_description)): ?>
					<div class="product-description">
						<?php
						// Sanitize and fix HTML to prevent layout breaking
						$clean_description = wp_kses_post($product_description);
						// Ensure any unclosed tags are properly closed
						$clean_description = force_balance_tags($clean_description);
						echo wp_kses_post($clean_description);
						?>
					</div>
					<?php endif; ?>

					<!-- Module Integration: After description (Custom Fields) -->
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_description', 'custom_fields'); ?>

					<!-- Product Attributes (only for simple products) -->
					<?php if ($this->shouldShowAttributes($settings) && !empty($attributes) && !$product->is_type('variable')): ?>
					<div class="product-attributes responsive-attributes">
						<?php foreach ($attributes as $attribute_name => $attribute): ?>
							<?php
							$attribute_label = wc_attribute_label($attribute_name);
							$attribute_values = $product->get_attribute($attribute_name);
							if (!empty($attribute_values)): ?>
								<div class="attribute-group">
									<?php if ($this->shouldShowAttributeLabels($settings)): ?>
									<label class="attribute-label">
										<?php echo esc_html($attribute_label); ?>
									</label>
									<?php endif; ?>
									<div class="attribute-values">
										<?php if (is_string($attribute_values)): ?>
											<span class="attribute-value"><?php echo esc_html($attribute_values); ?></span>
										<?php else: ?>
											<?php $values = explode(', ', $attribute_values); ?>
											<?php foreach ($values as $value): ?>
												<span class="attribute-value"><?php echo esc_html(trim($value)); ?></span>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<!-- Purchase Section -->
					<div class="purchase-section">
						<?php if ($product->is_type('variable')): ?>
							<!-- Variable Product Purchase Section -->
							<form class="variations_form cart shopglut-variations-form woocommerce-variation-add-to-cart-enabled" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo esc_attr(wp_json_encode($product->get_available_variations())); ?>">
								<?php do_action('woocommerce_before_variations_form'); ?>

								<?php if (empty($product->get_available_variations()) && false !== $product->get_available_variations()): ?>
									<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'shopglut'))); ?></p>
								<?php else: ?>
									<table class="variations" cellspacing="0">
										<tbody>
											<?php foreach ($product->get_variation_attributes() as $attribute_name => $options): ?>
												<tr>
													<td class="label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo esc_html(wc_attribute_label($attribute_name)); ?></label></td>
													<td class="value">
														<?php
														wc_dropdown_variation_attribute_options(array(
															'options'   => $options,
															'attribute' => $attribute_name,
															'product'   => $product,
														));
														?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

									<div class="single_variation_wrap">
										<?php do_action('woocommerce_before_single_variation'); ?>
										<div class="single_variation"></div>
										<div class="woocommerce-variation-add-to-cart variations_button">
											<?php do_action('woocommerce_before_add_to_cart_quantity'); ?>
											<!-- Module Integration: Before Add to Cart -->
											<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_add_to_cart'); ?>

											<div class="quantity-cart-wrapper">
												<div class="quantity-selector">
													<button type="button" class="qty-decrease">-</button>
													<?php
													woocommerce_quantity_input(array(
														'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
														'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
														'input_value' => isset($_POST['quantity']) ? wc_stock_amount(sanitize_text_field(wp_unslash($_POST['quantity']))) : $product->get_min_purchase_quantity(), // phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce standard quantity input, nonce verified by WC form handler
														'classes'     => array('qty-input'),
													), $product);
													?>
													<button type="button" class="qty-increase">+</button>
												</div>
												<?php do_action('woocommerce_after_add_to_cart_quantity'); ?>
												<button type="submit" class="single_add_to_cart_button button alt shopglut-variable-add-to-cart" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
													<i class="fas fa-shopping-cart"></i> <span><?php echo esc_html($product->single_add_to_cart_text()); ?></span>
												</button>
											</div>

											<!-- Module Integration: After Add to Cart -->
											<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>
										</div>
										<?php do_action('woocommerce_after_single_variation'); ?>
									</div>

									
								<?php endif; ?>

								<?php do_action('woocommerce_after_variations_form'); ?>
							</form>
						<?php elseif ($product->is_type('grouped')): ?>
							<!-- Grouped Product Purchase Section -->
							<form class="cart grouped_form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
								<table cellspacing="0" class="woocommerce-grouped-product-list group_table">
									<tbody>
										<?php
										$grouped_products = $product->get_children();
										foreach ($grouped_products as $grouped_product_child) {
											$grouped_product_child_obj = wc_get_product($grouped_product_child);
											if (!$grouped_product_child_obj || !$grouped_product_child_obj->is_purchasable()) {
												continue;
											}
										?>
											<tr id="product-<?php echo esc_attr($grouped_product_child); ?>" class="woocommerce-grouped-product-list-item">
												<td class="woocommerce-grouped-product-list-item__label">
													<label for="quantity_<?php echo esc_attr($grouped_product_child); ?>">
														<?php echo wp_kses_post($grouped_product_child_obj->get_name()); ?>
													</label>
												</td>
												<td class="woocommerce-grouped-product-list-item__price">
													<?php echo wp_kses_post($grouped_product_child_obj->get_price_html()); ?>
												</td>
												<td class="woocommerce-grouped-product-list-item__quantity">
													<div class="quantity-cart-wrapper">
														<div class="quantity-selector">
															<button type="button" class="qty-decrease">-</button>
															<?php
															woocommerce_quantity_input(array(
																'input_name'  => "quantity[{$grouped_product_child}]",
																'input_value' => 0,
																'min_value'   => 0,
																'max_value'   => $grouped_product_child_obj->get_max_purchase_quantity(),
																'classes'     => array('qty-input'),
															), $grouped_product_child_obj);
															?>
															<button type="button" class="qty-increase">+</button>
														</div>
													</div>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>

								<!-- Module Integration: Before Add to Cart -->
								<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_add_to_cart'); ?>

								<button type="submit" class="single_add_to_cart_button button alt add-to-cart-btn">
									<i class="fas fa-shopping-cart"></i> <span><?php echo esc_html($product->single_add_to_cart_text()); ?></span>
								</button>

								<!-- Module Integration: After Add to Cart -->
								<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>

								<!-- Secondary Actions: Wishlist & Compare -->
								<?php if ($this->shouldShowSecondaryActions($settings)): ?>
								<div class="secondary-actions">
									<?php if ($this->shouldShowWishlistButton($settings)): ?>
									<button type="button" class="wishlist-btn">
										<i class="fas fa-heart"></i> <?php esc_html_e('Add to Wishlist', 'shopglut'); ?>
									</button>
									<?php endif; ?>

									<?php if ($this->shouldShowCompareButton($settings)): ?>
									<button type="button" class="compare-btn">
										<i class="fas fa-balance-scale"></i> <?php esc_html_e('Compare', 'shopglut'); ?>
									</button>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</form>
						<?php elseif ($product->is_type('external')): ?>
							<!-- External/Affiliate Product Purchase Section -->

							<!-- Module Integration: Before Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_add_to_cart'); ?>

							<div class="external-product-wrapper">
								<a href="<?php echo esc_url($product->get_product_url()); ?>" target="_blank" class="single_add_to_cart_button button alt external-product-btn" rel="nofollow">
									<i class="fas fa-external-link-alt"></i> <span><?php echo esc_html($product->single_add_to_cart_text()); ?></span>
								</a>
							</div>

							<!-- Module Integration: After Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>

							<!-- Secondary Actions: Wishlist & Compare -->
							<?php if ($this->shouldShowSecondaryActions($settings)): ?>
							<div class="secondary-actions">
								<?php if ($this->shouldShowWishlistButton($settings)): ?>
								<button type="button" class="wishlist-btn">
									<i class="fas fa-heart"></i> <?php esc_html_e('Add to Wishlist', 'shopglut'); ?>
								</button>
								<?php endif; ?>

								<?php if ($this->shouldShowCompareButton($settings)): ?>
								<button type="button" class="compare-btn">
									<i class="fas fa-balance-scale"></i> <?php esc_html_e('Compare', 'shopglut'); ?>
								</button>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						<?php else: ?>
							<!-- Simple Product Purchase Section -->

							<!-- Module Integration: Before Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_add_to_cart'); ?>

							<div class="quantity-cart-wrapper">
								<div class="quantity-selector">
									<button class="qty-decrease">-</button>
									<input type="number" class="qty-input" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity() == -1 ? 9999 : $product->get_max_purchase_quantity()); ?>"
										  >
									<button class="qty-increase">+</button>
								</div>
								<button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
									<i class="fas fa-shopping-cart"></i> <span><?php esc_html_e('Add to Cart', 'shopglut'); ?></span>
								</button>
							</div>

							<!-- Module Integration: After Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>

						<?php endif; ?>

						
					</div>
				</div>
			</div>

			<!-- Features Section -->
			<?php
			$product_features = $this->getSetting($settings, 'product_features', $demo_features);

			if ($this->shouldShowFeaturesSection($settings) && !empty($product_features)):
			?>
			<div class="features-section">
				<?php if ($this->shouldShowFeaturesSectionTitle($settings)): ?>
				<h2 class="features-title">
					<?php echo esc_html($this->getFeaturesSectionTitle($settings)); ?>
				</h2>
				<?php endif; ?>

				<div class="features-grid">
					<?php foreach ($product_features as $feature): ?>
					<div class="feature-item">
						<?php if (isset($feature['feature_link_enabled']) && $feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
						<a href="<?php echo esc_url($feature['feature_link_url']); ?>"
						   target="<?php echo esc_attr($feature['feature_link_target'] ?? '_self'); ?>"
						  >
						<?php endif; ?>

						<div class="feature-icon">
							<?php echo wp_kses_post($this->renderFeatureIcon($feature, $settings)); ?>
						</div>

						<div class="feature-content">
							<div class="feature-title">
								<?php echo esc_html($feature['feature_title']); ?>
							</div>
							<div class="feature-description">
								<?php echo esc_html($feature['feature_description']); ?>
							</div>
						</div>

						<?php if (isset($feature['feature_link_enabled']) && $feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<!-- Custom Product Tabs -->
			<?php
			$product_tabs = $this->getSetting($settings, 'product_tabs_list', array());
			if (!empty($product_tabs)):
			?>
			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs">
					<?php $tab_index = 0; foreach ($product_tabs as $tab): $tab_index++; ?>
					<li class="<?php echo $tab_index === 1 ? 'active' : ''; ?>">
						<a href="#tab-<?php echo esc_attr($tab_index); ?>">
							<?php if (!empty($tab['tab_icon'])): ?>
							<i class="<?php echo esc_attr($tab['tab_icon']); ?>"></i>
							<?php endif; ?>
							<?php echo esc_html($tab['tab_title']); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php $tab_index = 0; foreach ($product_tabs as $tab): $tab_index++; ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--custom panel entry-content wc-tab <?php echo $tab_index === 1 ? 'active' : ''; ?>" id="tab-<?php echo esc_attr($tab_index); ?>">
					<div class="custom-tab-content">
						<?php
						$tab_content = isset($tab['tab_content']) ? $tab['tab_content'] : '';
						echo wp_kses_post($tab_content);
						?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<!-- Related Products -->
			<?php if ($this->shouldShowRelatedProducts($settings) && !empty($related_products)): ?>
			<div class="related-products-section">
				<h2 class="related-products-title">
					<?php echo esc_html($this->getRelatedProductsTitle($settings)); ?>
				</h2>

				<div class="related-products-grid">
					<?php foreach ($related_products as $related_product): ?>
					<div class="related-product-card">
						<div class="related-product-image">
							<a href="<?php echo esc_url($related_product['link']); ?>">
								<img src="<?php echo esc_url($related_product['image']); ?>"
									 alt="<?php echo esc_attr($related_product['name']); ?>"
									 class="related-product-img template-preview-image loaded">
							</a>
						</div>

						<div class="related-product-info">
							<div class="related-product-name">
								<a href="<?php echo esc_url($related_product['link']); ?>">
									<?php echo esc_html($related_product['name']); ?>
								</a>
							</div>

							<?php if ($related_product['rating'] > 0 || $related_product['reviews'] > 0): ?>
							<div class="related-product-rating">
								<?php echo wp_kses_post($this->renderStars($related_product['rating'], $settings)); ?>
								<span class="related-product-reviews">(<?php echo esc_html($related_product['reviews']); ?>)</span>
							</div>
							<?php endif; ?>

							<div class="related-product-price">
								<?php echo wp_kses_post($related_product['price']); ?>
							</div>
						</div>

						<button type="button" class="quick-add-btn" data-product-id="<?php echo esc_attr($related_product['id']); ?>">
							<?php esc_html_e('Quick Add', 'shopglut'); ?>
						</button>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Content and visibility logic methods
	 * (All styling is handled in template1Style.php)
	 */

	/**
	 * Helper method to get setting value with fallback
	 */
	private function getSetting($settings, $key, $default = '') {
		return isset($settings[$key]) ? $settings[$key] : $default;
	}

	/**
	 * Check if rating should be shown
	 */
	private function shouldShowRating($settings) {
		return $this->getSetting($settings, 'show_rating', true);
	}

	/**
	 * Check if description should be shown
	 */
	private function shouldShowDescription($settings) {
		return $this->getSetting($settings, 'show_description', true);
	}

	/**
	 * Check if attributes should be shown
	 */
	private function shouldShowAttributes($settings) {
		return $this->getSetting($settings, 'show_product_attributes', true);
	}

	/**
	 * Check if features section should be shown
	 */
	private function shouldShowFeaturesSection($settings) {
		return $this->getSetting($settings, 'show_features_section', true);
	}

	/**
	 * Check if related products should be shown
	 */
	private function shouldShowRelatedProducts($settings) {
		return $this->getSetting($settings, 'show_related_products', true);
	}

	/**
	 * Content visibility and data methods
	 */
	private function shouldShowThumbnails($settings) {
		return $this->getSetting($settings, 'show_thumbnails', true);
	}

	private function shouldShowAttributeLabels($settings) {
		return $this->getSetting($settings, 'show_attribute_labels', true);
	}

	private function shouldShowSecondaryActions($settings) {
		return true;
	}

	private function shouldShowWishlistButton($settings) {
		return $this->getSetting($settings, 'show_wishlist_button', true);
	}

	private function shouldShowCompareButton($settings) {
		return $this->getSetting($settings, 'show_compare_button', true);
	}

	private function shouldShowFeaturesSectionTitle($settings) {
		return $this->getSetting($settings, 'show_features_section_title', false);
	}

	private function getFeaturesSectionTitle($settings) {
		return $this->getSetting($settings, 'features_section_title', 'Why Choose Us');
	}

	private function getRelatedProductsTitle($settings) {
		return $this->getSetting($settings, 'related_section_title', 'You Might Also Like');
	}

	/**
	 * Render stars rating
	 */
	private function renderStars($rating, $settings) {
		$stars = '';
		for ($i = 1; $i <= 5; $i++) {
			if ($i <= $rating) {
				$stars .= '<span class="star filled">★</span>';
			} else {
				$stars .= '<span class="star">☆</span>';
			}
		}
		return $stars;
	}

	/**
	 * Render feature icon
	 */
	private function renderFeatureIcon($feature, $settings) {
		if ($feature['feature_icon_type'] === 'image' && !empty($feature['feature_custom_image'])) {
			return '<img src="' . esc_url($feature['feature_custom_image']) . '" alt="' . esc_attr($feature['feature_title']) . '" class="feature-icon-image">';
		} else {
			$icon_class = !empty($feature['feature_fontawesome_icon']) ? $feature['feature_fontawesome_icon'] : 'fas fa-star';
			return '<i class="' . esc_attr($icon_class) . '"></i>';
		}
	}

	/**
	 * Get layout settings from database
	*/
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		// Check cache first
		$cache_key = 'shopglut_single_product_layout_' . $layout_id;
		// Skip cache in debug mode to always get fresh data
		$use_cache = !defined('WP_DEBUG') || !WP_DEBUG;
		$layout_data = $use_cache ? wp_cache_get($cache_key, 'shopglut_layouts') : false;

		if (false === $layout_data) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_single_product_layout';

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with safe table name, proper prepare statement, and caching implemented
			$layout_data = $wpdb->get_row(
				$wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_single_product_layout` WHERE id = %d", $layout_id)
			);

			// Cache the result for 1 hour
			if ($use_cache) {
				wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
			}
		}

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_singleproduct_settings_template1']['single-product-settings'])) {
				$nested_settings = $settings['shopg_singleproduct_settings_template1']['single-product-settings'];

				// Preserve both flattened and nested settings for ModuleIntegration
				$flat_settings = $this->flattenSettings($nested_settings);
				// Store the original nested settings for modules that need them
				$flat_settings['_nested_settings'] = $nested_settings;

				return $flat_settings;
			}
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Flatten nested settings structure to simple key-value pairs
	 * Preserves repeater field arrays (product_tabs_list, product_features, etc.)
	 */
	private function flattenSettings($nested_settings) {
		$flat_settings = array();

		// IMPORTANT: Preserve repeater fields directly before any processing
		// These are sequential arrays that must not be processed by the loop
		$repeater_fields = array('product_tabs_list', 'product_features');

		// Check at top level first
		foreach ($repeater_fields as $field) {
			if (isset($nested_settings[$field]) && is_array($nested_settings[$field])) {
				$flat_settings[$field] = $nested_settings[$field];
			}
		}

		foreach ($nested_settings as $group_key => $group_values) {
			if (is_array($group_values)) {
				foreach ($group_values as $setting_key => $setting_value) {
					// Skip if already processed as top-level repeater
					if (isset($flat_settings[$setting_key]) && in_array($setting_key, $repeater_fields)) {
						continue;
					}

					// Handle nested fieldsets - recurse into them
					if (is_array($setting_value) && !$this->isSequentialArray($setting_value) && !isset($setting_value[$setting_key])) {
						// This looks like a fieldset (associative array), recurse into it
						foreach ($setting_value as $sub_key => $sub_value) {
							// Check if this is a nested repeater field that needs to be preserved
							if (in_array($sub_key, $repeater_fields) && is_array($sub_value) && $this->isSequentialArray($sub_value)) {
								$flat_settings[$sub_key] = $sub_value;
							} elseif (is_array($sub_value) && isset($sub_value[$sub_key])) {
								// Slider format
								$flat_settings[$sub_key] = $sub_value[$sub_key];
							} elseif (is_array($sub_value) && $this->isSequentialArray($sub_value)) {
								// Repeater format - preserve it
								$flat_settings[$sub_key] = $sub_value;
							} else {
								$flat_settings[$sub_key] = $sub_value;
							}
						}
					} elseif (is_array($setting_value) && isset($setting_value[$setting_key])) {
						// Slider field format: ['key' => value]
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} elseif (is_array($setting_value) && $this->isSequentialArray($setting_value)) {
						// Repeater field format - sequential array
						$flat_settings[$setting_key] = $setting_value;
					} else {
						// Simple value or array that doesn't match other patterns - just store it
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			}
		}

		// Get defaults and only fill in missing keys (don't override existing values)
		$defaults = $this->getDefaultSettings();
		foreach ($defaults as $key => $value) {
			if (!isset($flat_settings[$key])) {
				$flat_settings[$key] = $value;
			}
		}

		return $flat_settings;
	}

	/**
	 * Helper to check if an array is sequential (repeater field)
	 * vs associative (slider field like ['padding' => 10])
	 */
	private function isSequentialArray($array) {
		if (empty($array)) {
			return false;
		}
		// Check if array has sequential numeric keys starting from 0
		return array_keys($array) === range(0, count($array) - 1);
	}


	/**
	 * Get default settings values for single product template
	 */
	private function getDefaultSettings() {
		return array(
			// Product Gallery
			'main_image_background' => '#f9fafb',
			'main_image_border_radius' => 8,
			'main_image_border_color' => '#e5e7eb',
			'main_image_border_width' => 1,
			'show_thumbnails' => true,
			'thumbnail_border_radius' => 6,
			'thumbnail_spacing' => 8,
			'thumbnail_active_border' => '#8b5cf6',

			// Product Badges
			'enable_badges' => true,
			'show_product_badges' => true,
			'badge_position' => 'on_product_image',
			'badge_border_radius' => 4,
			'badge_font_size' => 12,
			'badge_font_weight' => '500',
			'badge_spacing' => 5,
			'show_new_badge' => true,
			'new_badge_text' => 'New',
			'new_badge_background_color' => '#10b981',
			'new_badge_text_color' => '#ffffff',
			'show_trending_badge' => true,
			'trending_badge_text' => 'Trending',
			'trending_badge_background_color' => '#f59e0b',
			'trending_badge_text_color' => '#ffffff',
			'show_bestseller_badge' => true,
			'bestseller_badge_text' => 'Best Seller',
			'bestseller_badge_background_color' => '#ef4444',
			'bestseller_badge_text_color' => '#ffffff',

			// Product Title
			'product_title_color' => '#111827',
			'product_title_font_size' => 32,
			'product_title_font_weight' => '700',

			// Rating
			'show_rating' => true,
			'star_color' => '#fbbf24',
			'rating_text_color' => '#6b7280',
			'rating_font_size' => 14,

			// Price
			'current_price_color' => '#111827',
			'current_price_font_size' => 28,
			'price_background_color' => 'transparent',
			'original_price_color' => '#9ca3af',
			'discount_badge_color' => '#ef4444',
			'discount_badge_text_color' => '#ffffff',

			// Description
			'show_description' => true,
			'description_color' => '#6b7280',
			'description_font_size' => 16,
			'description_line_height' => 1.6,

			// Attributes
			'show_product_attributes' => true,
			'show_attribute_labels' => true,
			'attribute_label_color' => '#374151',
			'attribute_label_font_size' => 14,
			'attribute_label_font_weight' => '500',

			// Purchase Section
			'cart_button_background' => '#8b5cf6',
			'cart_button_text_color' => '#ffffff',
			'cart_button_hover_background' => '#7c3aed',
			'cart_button_border_radius' => 8,
			'cart_button_font_size' => 16,
			'cart_button_font_weight' => '600',
			'show_wishlist_button' => true,
			'show_compare_button' => true,

			// Features Section
			'show_features_section' => true,
			'features_section_title' => 'Why Choose Us',
			'show_features_section_title' => false,
			'features_background_color' => '#f9fafb',
			'features_border_radius' => 12,
			'features_grid_columns' => '4',
			'features_padding' => 24,
			'features_gap' => 20,
			'feature_icon_size' => 32,
			'feature_icon_color' => '#8b5cf6',
			'feature_title_color' => '#111827',
			'feature_title_font_size' => 16,
			'feature_title_font_weight' => '600',
			'feature_description_color' => '#6b7280',
			'feature_description_font_size' => 14,

			// Related Products
			'show_related_products' => true,
			'related_section_title' => 'You Might Also Like',
			'related_section_title_color' => '#111827',
			'related_products_per_row' => '4',
			'product_card_background' => '#ffffff',
			'product_card_border_color' => '#e5e7eb',
			'product_card_border_radius' => 8,
			'product_card_hover_shadow' => true,
			'quick_add_button_background' => '#8b5cf6',
			'quick_add_button_text_color' => '#ffffff',

			// Default features
			'product_features' => array(
				array(
					'feature_icon_type' => 'fontawesome',
					'feature_fontawesome_icon' => 'fas fa-shipping-fast',
					'feature_title' => 'Free Shipping',
					'feature_description' => 'Free shipping on orders over $50',
					'feature_link_enabled' => false,
				),
				array(
					'feature_icon_type' => 'fontawesome',
					'feature_fontawesome_icon' => 'fas fa-undo',
					'feature_title' => 'Easy Returns',
					'feature_description' => '30-day hassle-free returns',
					'feature_link_enabled' => false,
				),
				array(
					'feature_icon_type' => 'fontawesome',
					'feature_fontawesome_icon' => 'fas fa-shield-alt',
					'feature_title' => 'Secure Payment',
					'feature_description' => '100% secure payment processing',
					'feature_link_enabled' => false,
				),
				array(
					'feature_icon_type' => 'fontawesome',
					'feature_fontawesome_icon' => 'fas fa-headset',
					'feature_title' => '24/7 Support',
					'feature_description' => 'Round-the-clock customer support',
					'feature_link_enabled' => false,
				),
			),

			// Default product tabs
			'product_tabs_list' => array(
				array(
					'tab_icon' => 'fas fa-shipping-fast',
					'tab_title' => 'Shipping Info',
					'tab_content' => 'Free shipping on all orders over $50. Delivery within 3-5 business days.',
				),
				array(
					'tab_icon' => 'fas fa-undo',
					'tab_title' => 'Returns',
					'tab_content' => '30-day hassle-free returns on all products.',
				),
			),
		);
	}
}