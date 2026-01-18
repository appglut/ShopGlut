<?php
namespace Shopglut\layouts\singleProduct\templates\template2;

if (!defined('ABSPATH')) {
	exit;
}

// Include template2 AJAX handler
require_once __DIR__ . '/template2-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template2Markup {


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
		<div class="shopglut-single-product template2 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
			<div class="single-product-container">
				<?php if ($is_admin_preview): ?>
					<!-- Admin Preview Mode -->
					<div class="demo-content responsive-preview">
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
	 * Render demo single product for admin preview
	 */
	private function render_demo_single_product($settings) {
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

		 <div class="single-product-template2">
        <div class="container">
            <!-- Main Product Section -->
            <div class="product-page">
                <div class="product-container">
                    <!-- Left Side - Product Image -->
                    <div class="product-image">
                        <!-- Module Integration: Badges on Product Image -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'on_product_image', 'badges'); ?>

                        <?php
                        $main_image_classes = $this->getMainImageClasses($settings);
                        $main_image_data_attrs = $this->getMainImageDataAttributes($settings);
                        $data_attrs_string = '';
                        foreach ($main_image_data_attrs as $key => $value) {
                            $data_attrs_string .= ' data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
                        }
                        ?>
                        <div class="<?php echo esc_attr($main_image_classes); ?>"<?php echo $data_attrs_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in loop above ?>>
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image">
                        </div>
                        <!-- Thumbnail Gallery -->
                        <div class="thumbnail-gallery">
                            <div class="thumbnail-item active" onclick="return false;">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 1">
                            </div>
                            <div class="thumbnail-item" onclick="return false;">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 2">
                            </div>
                            <div class="thumbnail-item" onclick="return false;">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 3">
                            </div>
                            <div class="thumbnail-item" onclick="return false;">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 4">
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Product Information -->
                    <div class="product-info">
                        <!-- Breadcrumb -->
                        <?php if ($this->shouldShowBreadcrumb($settings)): ?>
                        <nav class="breadcrumb">
                            <a href="#"><?php esc_html_e('Home', 'shopglut'); ?></a>
                            <span class="separator"><?php echo esc_html($this->getSetting($settings, 'breadcrumb_separator', '>')); ?></span>
                            <a href="#"><?php esc_html_e('Computers', 'shopglut'); ?></a>
                            <span class="separator"><?php echo esc_html($this->getSetting($settings, 'breadcrumb_separator', '>')); ?></span>
                            <span><?php esc_html_e('Product', 'shopglut'); ?></span>
                        </nav>
                        <?php endif; ?>

                        <!-- Reviews Section -->
                        <div class="reviews-section">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="reviews-count">(4.5) 128 reviews</span>
                        </div>

                        <!-- Module Integration: Badges (before title position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_product_title', 'badges'); ?>

                        <!-- Product Title -->
                        <h1 class="product-title">Gaming Laptop Intel Core i9 32GB RAM</h1>

                        <!-- Module Integration: Badges (after title position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_product_title', 'badges'); ?>

                        <!-- Module Integration: Custom Fields (after title position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_product_title', 'custom_fields'); ?>

                        <!-- Module Integration: Badges (before price position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_price', 'badges'); ?>

                        <!-- Module Integration: Custom Fields (before price position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'before_price', 'custom_fields'); ?>

                        <!-- Price -->
                        <div class="price-section">
                            <span class="current-price">$299.99</span>
                            <span class="original-price">$399.99</span>
                        </div>

                        <!-- Short Description -->
                        <div class="short-description">
                            Experience ultimate gaming performance with our powerful gaming laptop featuring Intel Core i9 processor, 32GB RAM, and dedicated graphics. Perfect for gamers, content creators, and professionals who demand speed and reliability.
                        </div>

                        <!-- Module Integration: Custom Fields (after description position) -->
                        <?php ModuleIntegration::render_module_wrapper($settings, 0, 'after_description', 'custom_fields'); ?>

                        <!-- Product Attributes -->
                        <?php if ($this->shouldShowAttributes($settings)): ?>
                        <div class="product-attributes">
                            <?php if ($this->shouldShowAttributeLabels($settings)): ?>
                            <div class="attribute-label">Color:</div>
                            <?php endif; ?>
                            <div class="attribute-options">
                                <div class="attribute-option selected" style="background-color: #1a1a1a;" title="Midnight Black"></div>
                                <div class="attribute-option" style="background-color: #ffffff; border: 2px solid #ddd;" title="Pearl White"></div>
                                <div class="attribute-option" style="background-color: #0073aa;" title="Ocean Blue"></div>
                                <div class="attribute-option" style="background-color: #dc3545;" title="Ruby Red"></div>
                            </div>
                        </div>
                        <?php if ($this->shouldShowAttributeLabels($settings)): ?>
                        <div class="product-attributes">
                            <div class="attribute-label">Size:</div>
                            <div class="attribute-options">
                                <div class="attribute-option selected">Standard</div>
                                <div class="attribute-option">Large</div>
                                <div class="attribute-option">XL</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <!-- Cart Actions -->
                        <div class="cart-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn" onclick="return false;">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="15">
                                <button class="quantity-btn" onclick="return false;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button class="add-to-cart" onclick="return false;">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <!-- Wishlist Button (Demo) -->
                            <button class="wishlist-btn" onclick="return false;">
                                <i class="far fa-heart"></i>
                                Wishlist
                            </button>
                            <!-- Comparison Button (Demo) -->
                            <?php if ($this->getSetting($settings, 'enable_comparison', false)): ?>
                            <button class="comparison-btn" onclick="return false;">
                                <i class="fas fa-exchange-alt"></i>
                                Compare
                            </button>
                            <?php endif; ?>
                        </div>

                        <!-- Buy Now Button -->
                        <button class="buy-now-btn" onclick="return false;">
                            <i class="fas fa-bolt"></i>
                            Buy Now
                        </button>

                        <!-- Bottom Border After Buy Now -->
                        <div class="buy-now-border"></div>

                        <!-- Product Metadata -->
                        <?php if ($this->shouldShowProductMeta($settings)): ?>
                        <div class="product-meta">
                            <?php if ($this->getSetting($settings, 'show_categories', true)): ?>
                            <div class="meta-item">
                                <span class="meta-label"><?php esc_html_e('Categories:', 'shopglut'); ?></span>
                                <span class="meta-value">
                                    <a href="#"><?php esc_html_e('Computers', 'shopglut'); ?></a>
                                    <a href="#"><?php esc_html_e('Laptops', 'shopglut'); ?></a>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->getSetting($settings, 'show_tags', true)): ?>
                            <div class="meta-item">
                                <span class="meta-label"><?php esc_html_e('Tags:', 'shopglut'); ?></span>
                                <span class="meta-value">
                                    <a href="#"><?php esc_html_e('Intel', 'shopglut'); ?></a>
                                    <a href="#"><?php esc_html_e('Gaming', 'shopglut'); ?></a>
                                    <a href="#"><?php esc_html_e('32GB', 'shopglut'); ?></a>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->shouldShowSocialShare($settings)): ?>
                            <div class="meta-item">
                                <span class="meta-label"><?php echo esc_html($this->getSetting($settings, 'social_share_label', __('Share:', 'shopglut'))); ?></span>
                                <span class="meta-value">
                                    <?php echo wp_kses_post($this->renderSocialIcons($settings)); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Custom Product Tabs -->
            <?php
            $product_tabs = isset($settings['product_tabs_list']) ? $settings['product_tabs_list'] : array();

            // Use default tabs if none configured
            if (empty($product_tabs)) {
                $product_tabs = array(
                    array(
                        'tab_icon' => 'fas fa-shipping-fast',
                        'tab_title' => __('Shipping Info', 'shopglut'),
                        'tab_content' => __('Free shipping on all orders over $50. Delivery within 3-5 business days.', 'shopglut'),
                    ),
                    array(
                        'tab_icon' => 'fas fa-undo',
                        'tab_title' => __('Returns', 'shopglut'),
                        'tab_content' => __('30-day hassle-free returns on all products.', 'shopglut'),
                    ),
                );
            }
            ?>
            <div class="woocommerce-tabs-section">
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
                            <?php echo wp_kses_post($tab['tab_content']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Related Products Slider -->
            <?php if ($this->shouldShowRelatedProducts($settings)): ?>
            <div class="related-products">
                <h2><?php echo esc_html($this->getRelatedProductsTitle($settings)); ?></h2>
                <div class="products-slider">
                    <div class="related-product" onclick="return false;">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 1">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Wireless Mouse</h3>
                            <p class="related-product-price">$149.99</p>
                            <button class="add-related-btn" onclick="return false;">Add to Cart</button>
                        </div>
                    </div>

                    <div class="related-product" onclick="return false;">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 2">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Gaming Keyboard</h3>
                            <p class="related-product-price">$89.99</p>
                            <button class="add-related-btn" onclick="return false;">Add to Cart</button>
                        </div>
                    </div>

                    <div class="related-product" onclick="return false;">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 3">
                        <div class="related-product-info">
                            <h3 class="related-product-title">USB-C Hub</h3>
                            <p class="related-product-price">$29.99</p>
                            <button class="add-related-btn" onclick="return false;">Add to Cart</button>
                        </div>
                    </div>

                    <div class="related-product" onclick="return false;">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 4">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Laptop Stand</h3>
                            <p class="related-product-price">$39.99</p>
                            <button class="add-related-btn" onclick="return false;">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

		<?php
	}

	/**
	 * Render live single product for frontend
	 */
	private function render_live_single_product($settings) {
		global $product;
		if (!$product) {
			global $post;
			$product = wc_get_product($post->ID);
		}

		if (!$product) {
			echo '<div class="shopglut-error">Product not found.</div>';
			return;
		}

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
			'shopglut-template2-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template2/template2-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template2-frontend', 'shopglut_frontend_vars', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'product_id' => $product_id,
			'nonce' => wp_create_nonce('shopglut_frontend_nonce')
			));

		?>

		<div class="single-product-template2">
			<div class="container">
				<!-- Main Product Section -->
				<div class="product-page">
					<div class="product-container">
					<!-- Left Side - Product Image -->
					<div class="product-image">
						<!-- Module Integration: Badges on Product Image -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'on_product_image', 'badges'); ?>

						<?php
						$main_image_classes = $this->getMainImageClasses($settings);
						$main_image_data_attrs = $this->getMainImageDataAttributes($settings);
						$data_attrs_string = '';
						foreach ($main_image_data_attrs as $key => $value) {
							$data_attrs_string .= ' data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
						}
						?>
						<div class="<?php echo esc_attr($main_image_classes); ?>"<?php echo $data_attrs_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in loop above ?>>
							<img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
						</div>
						<!-- Thumbnail Gallery -->
						<?php if ($this->shouldShowThumbnails($settings) && (!empty($attachment_ids) || $product_image)): ?>
						<div class="thumbnail-gallery">
							<?php
							// Main image thumbnail
							if ($product_image): ?>
								<div class="thumbnail-item active">
									<img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
								</div>
							<?php endif;
							// Gallery thumbnails
							foreach ($attachment_ids as $index => $attachment_id):
								$gallery_image = wp_get_attachment_image_src($attachment_id, 'medium');
								if ($gallery_image): ?>
									<div class="thumbnail-item">
										<img src="<?php echo esc_url($gallery_image[0]); ?>" alt="<?php echo esc_attr($product_title . ' gallery'); ?>">
									</div>
								<?php endif;
							endforeach; ?>
						</div>
						<?php endif; ?>
					</div>

					<!-- Right Side - Product Information -->
					<div class="product-info">
						<!-- Breadcrumb -->
						<?php if ($this->shouldShowBreadcrumb($settings)): ?>
						<nav class="breadcrumb">
							<a href="<?php echo esc_url(get_home_url()); ?>"><?php esc_html_e('Home', 'shopglut'); ?></a>
							<span class="separator"><?php echo esc_html($this->getSetting($settings, 'breadcrumb_separator', '>')); ?></span>
							<?php
							$product_categories = get_the_terms($product_id, 'product_cat');
							if ($product_categories && !is_wp_error($product_categories)): ?>
								<a href="<?php echo esc_url(get_term_link($product_categories[0])); ?>"><?php echo esc_html($product_categories[0]->name); ?></a>
								<span class="separator"><?php echo esc_html($this->getSetting($settings, 'breadcrumb_separator', '>')); ?></span>
							<?php endif; ?>
							<span><?php echo esc_html($product_title); ?></span>
						</nav>
						<?php endif; ?>

						<!-- Reviews Section -->
						<?php if ($this->shouldShowRating($settings) && ($average_rating > 0 || $rating_count > 0)): ?>
						<div class="reviews-section">
							<div class="rating-stars">
								<?php echo wp_kses_post($this->renderStars($average_rating, $settings)); ?>
							</div>
							<span class="reviews-count">(<?php echo esc_html($average_rating); ?>) <?php echo esc_html($rating_count); ?> <?php esc_html_e('reviews', 'shopglut'); ?></span>
						</div>
						<?php endif; ?>

						<!-- Module Integration: Badges (before title position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_product_title', 'badges'); ?>

						<!-- Product Title -->
						<h1 class="product-title"><?php echo esc_html($product_title); ?></h1>

						<!-- Module Integration: Badges (after title position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_product_title', 'badges'); ?>

						<!-- Module Integration: Custom Fields (after title position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_product_title', 'custom_fields'); ?>

						<!-- Module Integration: Badges (before price position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_price', 'badges'); ?>

						<!-- Module Integration: Custom Fields (before price position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_price', 'custom_fields'); ?>

						<!-- Price -->
						<div class="price-section">
							<span class="current-price"><?php echo esc_html($currency_symbol . number_format((float)$current_price, 2)); ?></span>
							<?php if ($is_on_sale && $regular_price): ?>
								<span class="original-price"><?php echo esc_html($currency_symbol . number_format((float)$regular_price, 2)); ?></span>
							<?php endif; ?>
						</div>

						<!-- Short Description -->
						<?php if ($this->shouldShowDescription($settings) && !empty($product_description)): ?>
						<div class="short-description">
							<?php
							$clean_description = wp_kses_post($product_description);
							$clean_description = force_balance_tags($clean_description);
							echo wp_kses_post($clean_description);
							?>
						</div>
						<?php endif; ?>

						<!-- Module Integration: Custom Fields (after description position) -->
						<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_description', 'custom_fields'); ?>

						<!-- Product Attributes -->
						<?php if ($this->shouldShowAttributes($settings) && !empty($attributes)): ?>
						<div class="product-attributes-wrapper">
							<?php
							foreach ($attributes as $attribute_name => $attribute):
								if ($attribute->get_visible()):
									$attribute_data = $attribute->get_data();
									$attribute_label = wc_attribute_label($attribute_name);
									$attribute_values = $attribute->get_terms();
									$attribute_options = $attribute->get_options();
									?>
									<div class="product-attributes">
										<?php if ($this->shouldShowAttributeLabels($settings)): ?>
										<div class="attribute-label"><?php echo esc_html($attribute_label); ?>:</div>
										<?php endif; ?>
										<div class="attribute-options">
											<?php if ($attribute->is_taxonomy() && !empty($attribute_values)): ?>
												<?php foreach ($attribute_values as $attribute_term): ?>
													<div class="attribute-option" data-attribute-name="<?php echo esc_attr($attribute_name); ?>" data-attribute-value="<?php echo esc_attr($attribute_term->slug); ?>">
														<?php echo esc_html($attribute_term->name); ?>
													</div>
												<?php endforeach; ?>
											<?php elseif (!empty($attribute_options)): ?>
												<?php foreach ($attribute_options as $attribute_option): ?>
													<div class="attribute-option" data-attribute-name="<?php echo esc_attr($attribute_name); ?>" data-attribute-value="<?php echo esc_attr($attribute_option); ?>">
														<?php echo esc_html($attribute_option); ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
							<!-- For variable products, also display default variation attributes -->
							<?php if ($product->is_type('variable')): ?>
								<?php
								$default_attributes = $product->get_default_attributes();
								$variations = $product->get_available_variations();
								if (!empty($variations)):
									$variation_attributes = $variations[0]['attributes'];
									foreach ($variation_attributes as $attr_name => $attr_value):
										if (empty($attr_value)) continue;
										// Check if this attribute is not already displayed
										$attr_label = wc_attribute_label(str_replace('attribute_', '', $attr_name));
										if (!isset($attributes[$attr_name]) || !$attributes[$attr_name]->get_visible()):
											$attribute_object = $product->get_attribute(str_replace('attribute_', '', $attr_name));
											if ($attribute_object && $attribute_object->get_visible()):
												$attribute_terms = $attribute_object->get_terms();
												?>
												<div class="product-attributes">
													<?php if ($this->shouldShowAttributeLabels($settings)): ?>
													<div class="attribute-label"><?php echo esc_html($attr_label); ?>:</div>
													<?php endif; ?>
													<div class="attribute-options">
														<?php foreach ($attribute_terms as $term): ?>
															<div class="attribute-option" data-attribute-name="<?php echo esc_attr($attr_name); ?>" data-attribute-value="<?php echo esc_attr($term->slug); ?>">
																<?php echo esc_html($term->name); ?>
															</div>
														<?php endforeach; ?>
													</div>
												</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
						</div>
						<?php endif; ?>

					<!-- Cart Actions -->
					<?php if ($product->is_in_stock()): ?>
					<form class="cart" method="post" enctype='multipart/form-data'>
						<div class="cart-actions">
							<div class="quantity-selector">
								<?php
								$min_qty = $product->get_min_purchase_quantity();
								$max_qty = $product->get_max_purchase_quantity();
								$input_value = isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity();
								?>
								<button type="button" class="quantity-btn" onclick="return false;">
									<i class="fas fa-minus"></i>
								</button>
								<input type="number" class="quantity-input" name="quantity" value="<?php echo esc_attr($input_value); ?>" min="<?php echo esc_attr($min_qty); ?>" max="<?php echo esc_attr($max_qty); ?>">
								<button type="button" class="quantity-btn" onclick="return false;">
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<button type="submit" class="add-to-cart">
								<i class="fas fa-shopping-cart"></i>
								<?php echo esc_html($product->single_add_to_cart_text()); ?>
							</button>
							<!-- Module Integration: After Add to Cart (Wishlist, Compare, etc.) -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>
						</div>
					</form>
					<?php else: ?>
						<p class="stock out-of-stock"><?php esc_html_e('Out of stock', 'shopglut'); ?></p>
					<?php endif; ?>

					<!-- Buy Now Button -->
					<?php if ($product->is_in_stock()): ?>
					<button class="buy-now-btn" onclick="return false;">
						<i class="fas fa-bolt"></i>
						<?php esc_html_e('Buy Now', 'shopglut'); ?>
					</button>
					<?php endif; ?>

					<!-- Bottom Border After Buy Now -->
					<div class="buy-now-border"></div>

					<!-- Product Metadata -->
					<?php if ($this->shouldShowProductMeta($settings)): ?>
					<div class="product-meta">
						<?php if ($this->getSetting($settings, 'show_categories', true)): ?>
						<?php
						$product_categories = get_the_terms($product_id, 'product_cat');
						if ($product_categories && !is_wp_error($product_categories)): ?>
							<div class="meta-item">
								<span class="meta-label"><?php esc_html_e('Categories:', 'shopglut'); ?></span>
								<span class="meta-value">
									<?php foreach ($product_categories as $category): ?>
										<a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a><?php echo $category !== end($product_categories) ? ', ' : ''; ?>
									<?php endforeach; ?>
								</span>
							</div>
						<?php endif; ?>
						<?php endif; ?>

						<?php if ($this->getSetting($settings, 'show_tags', true)): ?>
						<?php
						$product_tags = get_the_terms($product_id, 'product_tag');
						if ($product_tags && !is_wp_error($product_tags)): ?>
							<div class="meta-item">
								<span class="meta-label"><?php esc_html_e('Tags:', 'shopglut'); ?></span>
								<span class="meta-value">
									<?php foreach ($product_tags as $tag): ?>
										<a href="<?php echo esc_url(get_term_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a><?php echo $tag !== end($product_tags) ? ', ' : ''; ?>
									<?php endforeach; ?>
								</span>
							</div>
						<?php endif; ?>
						<?php endif; ?>

						<?php if ($this->shouldShowSocialShare($settings)): ?>
						<div class="meta-item">
							<span class="meta-label"><?php echo esc_html($this->getSetting($settings, 'social_share_label', __('Share:', 'shopglut'))); ?></span>
							<span class="meta-value">
								<?php echo wp_kses_post($this->renderSocialIcons($settings)); ?>
							</span>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Custom Product Tabs -->
		<?php
		$product_tabs = isset($settings['product_tabs_list']) ? $settings['product_tabs_list'] : array();

		// Use default tabs if none configured
		if (empty($product_tabs)) {
			$product_tabs = array(
				array(
					'tab_icon' => 'fas fa-shipping-fast',
					'tab_title' => __('Shipping Info', 'shopglut'),
					'tab_content' => __('Free shipping on all orders over $50. Delivery within 3-5 business days.', 'shopglut'),
				),
				array(
					'tab_icon' => 'fas fa-undo',
					'tab_title' => __('Returns', 'shopglut'),
					'tab_content' => __('30-day hassle-free returns on all products.', 'shopglut'),
				),
			);
		}
		?>
		<div class="woocommerce-tabs-section">
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
						<?php echo wp_kses_post($tab['tab_content']); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Related Products Slider -->
		<?php if ($this->shouldShowRelatedProducts($settings) && !empty($related_products)): ?>
		<div class="related-products">
			<h2><?php echo esc_html($this->getRelatedProductsTitle($settings)); ?></h2>
			<div class="products-slider">
				<?php foreach ($related_products as $related_product): ?>
				<div class="related-product">
					<a href="<?php echo esc_url($related_product['link']); ?>">
						<img src="<?php echo esc_url($related_product['image']); ?>" alt="<?php echo esc_attr($related_product['name']); ?>">
					</a>
					<div class="related-product-info">
						<h3 class="related-product-title"><?php echo esc_html($related_product['name']); ?></h3>
						<p class="related-product-price"><?php echo wp_kses_post($related_product['price']); ?></p>
						<button class="add-related-btn" onclick="return false;"><?php esc_html_e('Add to Cart', 'shopglut'); ?></button>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
     </div>
		<?php
	}

	/**
	 * Helper method to get setting value with fallback
	 */
	private function getSetting($settings, $key, $default = '') {
		return isset($settings[$key]) ? $settings[$key] : $default;
	}

	/**
	 * Get CSS classes for main product image based on gallery settings
	 */
	private function getMainImageClasses($settings) {
		$classes = array('main-product-image');

		// Add lightbox class if enabled
		if ($this->getSetting($settings, 'enable_image_lightbox', true)) {
			$classes[] = 'lightbox-enabled';
		}

		// Add hover zoom class if enabled
		if ($this->getSetting($settings, 'enable_image_hover_zoom', false)) {
			$classes[] = 'hover-zoom-enabled';
		}

		return implode(' ', $classes);
	}

	/**
	 * Get data attributes for main product image
	 */
	private function getMainImageDataAttributes($settings) {
		$attributes = array();

		// Add zoom level data attribute if hover zoom is enabled
		if ($this->getSetting($settings, 'enable_image_hover_zoom', false)) {
			$attributes['data-zoom-level'] = $this->getSetting($settings, 'hover_zoom_level', 2);
		}

		// Add object-fit data attribute
		$attributes['data-object-fit'] = $this->getSetting($settings, 'main_image_object_fit', 'cover');

		return $attributes;
	}

	/**
	 * Check if badges should be shown
	 */
	private function shouldShowBadges($settings) {
		return $this->getSetting($settings, 'show_product_badges', true);
	}

	/**
	 * Check if specific badge type should be shown
	 */
	private function shouldShowBadgeType($settings, $type) {
		return $this->getSetting($settings, 'show_' . $type . '_badge', true);
	}

	/**
	 * Get badge text from settings
	 */
	private function getBadgeText($settings, $type, $default) {
		return $this->getSetting($settings, $type . '_badge_text', $default);
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
		return $this->getSetting($settings, 'related_section_title', 'Related Products');
	}

	/**
	 * Product Info Helper Methods
	 */

	/**
	 * Check if breadcrumb should be shown
	 */
	private function shouldShowBreadcrumb($settings) {
		return $this->getSetting($settings, 'show_breadcrumb', true);
	}

	/**
	 * Check if product metadata section should be shown
	 */
	private function shouldShowProductMeta($settings) {
		return $this->getSetting($settings, 'show_product_meta', true);
	}

	/**
	 * Check if social share should be shown
	 */
	private function shouldShowSocialShare($settings) {
		return $this->getSetting($settings, 'enable_social_share', true);
	}

	/**
	 * Render social share icons
	 */
	private function renderSocialIcons($settings) {
		$social_icons = $this->getSetting($settings, 'social_share_icons', array());

		// Default icons if none configured
		if (empty($social_icons)) {
			$social_icons = array(
				array(
					'social_icon' => 'fab fa-facebook-f',
					'social_background' => '#1877f2',
					'social_color' => '#ffffff',
					'social_hover_background' => '#0e5f9e',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fa-brands fa-x-twitter',
					'social_background' => '#000000',
					'social_color' => '#ffffff',
					'social_hover_background' => '#333333',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fab fa-pinterest-p',
					'social_background' => '#e60023',
					'social_color' => '#ffffff',
					'social_hover_background' => '#ad081b',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fab fa-whatsapp',
					'social_background' => '#25d366',
					'social_color' => '#ffffff',
					'social_hover_background' => '#128c7e',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
			);
		}

		$output = '<div class="share-icons">';

		foreach ($social_icons as $icon) {
			$icon_class = !empty($icon['social_icon']) ? $icon['social_icon'] : 'fas fa-share-alt';
			$background = !empty($icon['social_background']) ? $icon['social_background'] : '#1877f2';
			$color = !empty($icon['social_color']) ? $icon['social_color'] : '#ffffff';
			$border_radius = !empty($icon['social_border_radius']) ? $icon['social_border_radius'] : 6;

			// Fix: Remove icon class from div, only use i tag for icon to prevent doubling
			$output .= sprintf(
				'<div class="share-icon" style="background-color: %s; color: %s; border-radius: %spx;" onclick="return false;">',
				esc_attr($background),
				esc_attr($color),
				esc_attr($border_radius)
			);
			$output .= sprintf('<i class="%s"></i>', esc_attr($icon_class));
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
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
	 * Render features section
	 */
	private function renderFeaturesSection($settings) {
		$features = $this->getSetting($settings, 'product_features', array());

		if (empty($features)) {
			return;
		}

		if ($this->shouldShowFeaturesSectionTitle($settings)) {
			echo '<h2 class="features-title" style="' . esc_attr($this->getFeaturesTitleStyles($settings)) . '">';
			echo esc_html($this->getFeaturesSectionTitle($settings));
			echo '</h2>';
		}

		echo '<div class="features-grid" style="' . esc_attr($this->getFeaturesGridStyles($settings)) . '">';
		foreach ($features as $feature) {
			echo '<div class="feature-item" style="' . esc_attr($this->getFeatureItemStyles($settings)) . '">';

			if ($feature['feature_link_enabled'] && !empty($feature['feature_link_url'])) {
				echo '<a href="' . esc_url($feature['feature_link_url']) . '" target="' . esc_attr($feature['feature_link_target'] ?? '_self') . '" style="' . esc_attr($this->getFeatureLinkStyles($settings)) . '">';
			}

			echo '<div class="feature-icon" style="' . esc_attr($this->getFeatureIconContainerStyles($settings)) . '">';
			echo wp_kses_post($this->renderFeatureIcon($feature, $settings));
			echo '</div>';

			echo '<div class="feature-content">';
			echo '<div class="feature-title" style="' . esc_attr($this->getFeatureTitleStyles($settings)) . '">';
			echo esc_html($feature['feature_title']);
			echo '</div>';
			echo '<div class="feature-description" style="' . esc_attr($this->getFeatureDescriptionStyles($settings)) . '">';
			echo esc_html($feature['feature_description']);
			echo '</div>';
			echo '</div>';

			if ($feature['feature_link_enabled'] && !empty($feature['feature_link_url'])) {
				echo '</a>';
			}

			echo '</div>';
		}
		echo '</div>';
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
		$layout_data = wp_cache_get($cache_key, 'shopglut_layouts');

		if (false === $layout_data) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_single_product_layout';

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with safe table name, proper prepare statement, and caching implemented
			$layout_data = $wpdb->get_row(
				$wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_single_product_layout` WHERE id = %d", $layout_id)
			);

			// Cache the result for 1 hour
			wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
		}

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_singleproduct_settings_template2']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template2']['single-product-settings']);
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
						// Preserve nested arrays (like repeater fields)
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			}
		}

		// Merge with defaults, but preserve nested arrays from settings
		$defaults = $this->getDefaultSettings();
		foreach ($defaults as $key => $value) {
			// Only use default if not in flat_settings
			if (!isset($flat_settings[$key])) {
				$flat_settings[$key] = $value;
			}
		}

		return $flat_settings;
	}


	/**
	 * Get default settings values for single product template
	 */
	private function getDefaultSettings() {
		return array(
			// Product Gallery
			'gallery_section_margin' => 40,
			'main_image_background' => '#f9fafb',
			'main_image_border_radius' => 8,
			'main_image_border_color' => '#e5e7eb',
			'main_image_border_width' => 1,
			'main_image_padding' => 14,
			'main_image_margin_bottom' => 20,
			'main_image_object_fit' => 'cover',
			'main_image_cursor' => 'zoom-in',
			'main_image_shadow' => true,
			'main_image_shadow_color' => 'rgba(0,0,0,0.1)',
			'enable_shimmer_effect' => false,
			'shimmer_speed' => 3,
			'shimmer_opacity' => 20,
			'main_image_hover_scale' => false,
			'main_image_hover_scale_value' => 1.05,
			'main_image_hover_brightness' => false,
			'main_image_hover_brightness_value' => 110,
			'show_thumbnails' => true,
			'thumbnail_size' => 80,
			'thumbnail_spacing' => 8,
			'thumbnail_border_radius' => 6,
			'thumbnail_border_width' => 2,
			'thumbnail_border_color' => 'transparent',
			'thumbnail_active_border' => '#667eea',
			'thumbnail_hover_border' => '#2563eb',
			'thumbnail_opacity' => 70,
			'thumbnail_hover_scale' => true,
			'thumbnail_hover_scale_value' => 1.05,
			'thumbnail_gallery_margin_top' => 16,
			'thumbnail_alignment' => 'flex-start',
			'thumbnail_object_fit' => 'cover',
			'enable_image_lightbox' => true,
			'enable_image_hover_zoom' => false,
			'hover_zoom_level' => 2,

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
			'original_price_color' => '#9ca3af',
			'discount_badge_color' => '#ef4444',
			'discount_badge_text_color' => '#ffffff',

			// Description
			'show_description' => true,
			'description_color' => '#6b7280',
			'description_font_size' => 16,
			'description_line_height' => 1.6,

			// ==================== PRODUCT INFO SETTINGS ====================
			// Breadcrumb Settings
			'show_breadcrumb' => true,
			'breadcrumb_font_size' => 14,
			'breadcrumb_text_color' => '#6b7280',
			'breadcrumb_link_color' => '#667eea',
			'breadcrumb_link_hover_color' => '#5a67d8',
			'breadcrumb_separator' => '>',
			'breadcrumb_separator_color' => '#9ca3af',
			'breadcrumb_margin_bottom' => 16,

			// Product Metadata Settings
			'show_product_meta' => true,
			'show_categories' => true,
			'show_tags' => true,
			'meta_label_color' => '#374151',
			'meta_label_font_size' => 14,
			'meta_label_font_weight' => '500',
			'meta_link_color' => '#667eea',
			'meta_link_hover_color' => '#5a67d8',

			// Social Share Settings
			'enable_social_share' => true,
			'social_share_label' => 'Share:',
			'social_share_icons' => array(
				array(
					'social_icon' => 'fab fa-facebook-f',
					'social_background' => '#1877f2',
					'social_color' => '#ffffff',
					'social_hover_background' => '#0e5f9e',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fa-brands fa-x-twitter',
					'social_background' => '#000000',
					'social_color' => '#ffffff',
					'social_hover_background' => '#333333',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fab fa-pinterest-p',
					'social_background' => '#e60023',
					'social_color' => '#ffffff',
					'social_hover_background' => '#ad081b',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
				array(
					'social_icon' => 'fab fa-whatsapp',
					'social_background' => '#25d366',
					'social_color' => '#ffffff',
					'social_hover_background' => '#128c7e',
					'social_hover_color' => '#ffffff',
					'social_border_radius' => 6,
				),
			),
			'social_icon_size' => 36,
			'social_icon_spacing' => 8,

			// Attributes
			'show_product_attributes' => true,
			'show_attribute_labels' => true,
			'attribute_label_color' => '#374151',
			'attribute_label_font_size' => 14,
			'attribute_label_font_weight' => '500',

			// Purchase Section
			'cart_button_background' => '#667eea',
			'cart_button_text_color' => '#ffffff',
			'cart_button_hover_background' => '#5a67d8',
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
			'feature_icon_color' => '#667eea',
			'feature_title_color' => '#111827',
			'feature_title_font_size' => 16,
			'feature_title_font_weight' => '600',
			'feature_description_color' => '#6b7280',
			'feature_description_font_size' => 14,

			// Related Products
			'show_related_products' => true,
			'related_section_title' => 'Related Products',
			'related_section_title_color' => '#111827',
			'related_products_per_row' => '4',
			'product_card_background' => '#ffffff',
			'product_card_border_color' => '#e5e7eb',
			'product_card_border_radius' => 8,
			'product_card_hover_shadow' => true,
			'quick_add_button_background' => '#667eea',
			'quick_add_button_text_color' => '#ffffff',

			// Product Tabs
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
			'tab_icon_size' => 16,
			'tab_icon_color' => '#6b7280',
			'tab_icon_hover_color' => '#667eea',
			'tab_icon_active_color' => '#667eea',
			'tab_title_color' => '#374151',
			'tab_title_hover_color' => '#667eea',
			'tab_title_active_color' => '#667eea',
			'tab_title_font_size' => 15,
			'tab_title_font_weight' => '500',

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
		);
	}
}