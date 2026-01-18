<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro4;

if (!defined('ABSPATH')) {
	exit;
}

class templateMarkup {


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
		<div class="shopglut-single-product templatePro1 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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
			array('text' => 'Hot', 'type' => 'hot'),
		);

		?>

 <div class="shopglut-single-templatePro4">
        <div class="container">
            <!-- Product Main Section -->
            <div class="product-container">
                <!-- Left Side - Image Gallery -->
                <div class="image-gallery">
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <img src="demo-image.png" alt="Product Image">
                        </div>
                        <div class="gallery-item">
                            <img src="demo-image.png" alt="Product View 2">
                        </div>
                        <div class="gallery-item">
                            <img src="demo-image.png" alt="Product View 3">
                        </div>
                        <div class="gallery-item">
                            <img src="demo-image.png" alt="Product View 4">
                        </div>
                    </div>
                </div>

                <!-- Right Side - Product Details -->
                <div class="product-details">
                    <!-- Discount Badge -->
                    <div class="discount-badge">
                        <i class="fas fa-fire"></i> 40% OFF - Limited Time
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">Premium Wireless Noise-Canceling Headphones Pro Max</h1>

                    <!-- Reviews Section -->
                    <div class="reviews-section">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="review-count">4.5 (328 Reviews)</span>
                    </div>

                    <!-- Short Description -->
                    <p class="product-description">
                        Experience premium sound quality with our latest flagship headphones. Features advanced noise cancellation, 40-hour battery life, and premium comfort for all-day wear. Perfect for audiophiles and professionals who demand the best.
                    </p>

                    <!-- Price and Quantity -->
                    <div class="price-quantity-row">
                        <div class="price-section">
                            <span class="current-price">$299.99</span>
                            <span class="original-price">$499.99</span>
                        </div>
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" id="quantity">
                            <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn btn-secondary">
                            <i class="far fa-heart"></i> Wishlist
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-exchange-alt"></i> Compare
                        </button>
                    </div>

                    <!-- Product Meta Information -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-content">
                                <span class="meta-label">SKU</span>
                                <div class="meta-value">PH-PRO-MAX-2024</div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-content">
                                <span class="meta-label">Category</span>
                                <div class="meta-value">Electronics > Audio</div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-content">
                                <span class="meta-label">Tags</span>
                                <div class="tags">
                                    <span class="tag">wireless</span>
                                    <span class="tag">noise-canceling</span>
                                    <span class="tag">premium</span>
                                </div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-content">
                                <span class="meta-label">Share</span>
                                <div class="share-buttons">
                                    <div class="share-btn"><i class="fab fa-facebook-f"></i></div>
                                    <div class="share-btn"><i class="fab fa-x-twitter"></i></div>
                                    <div class="share-btn"><i class="fab fa-instagram"></i></div>
                                    <div class="share-btn"><i class="fab fa-pinterest"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="features-section">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Free Shipping</h4>
                                <p>On orders over $99</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="feature-text">
                                <h4>30 Days Return</h4>
                                <p>Hassle-free returns</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="feature-text">
                                <h4>2 Year Warranty</h4>
                                <p>Full coverage included</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="showTab('description')">Description</button>
                    <button class="tab-btn" onclick="showTab('specifications')">Specifications</button>
                    <button class="tab-btn" onclick="showTab('reviews')">Reviews (328)</button>
                    <button class="tab-btn" onclick="showTab('shipping')">Shipping</button>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="description">
                        <h3>Product Description</h3>
                        <p>Our Premium Wireless Noise-Canceling Headphones Pro Max represent the pinnacle of audio engineering. With cutting-edge technology and premium materials, these headphones deliver an unparalleled listening experience.</p>
                        <br>
                        <h4>Key Features:</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>Active Noise Cancellation with transparency mode</li>
                            <li>40-hour battery life with quick charge</li>
                            <li>Premium memory foam ear cushions</li>
                            <li>Bluetooth 5.0 with multi-device connectivity</li>
                            <li>Built-in voice assistant support</li>
                            <li>Foldable design with carrying case</li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="specifications">
                        <h3>Technical Specifications</h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Driver Size</strong></td>
                                <td style="padding: 10px;">40mm Dynamic</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Frequency Response</strong></td>
                                <td style="padding: 10px;">20Hz - 20kHz</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Impedance</strong></td>
                                <td style="padding: 10px;">32 Ohms</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Battery Life</strong></td>
                                <td style="padding: 10px;">40 hours</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Charging Time</strong></td>
                                <td style="padding: 10px;">2 hours (15 min for 3 hours)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><strong>Weight</strong></td>
                                <td style="padding: 10px;">250g</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane" id="reviews">
                        <h3>Customer Reviews</h3>
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <strong>John D.</strong>
                            </div>
                            <p style="color: #666;">"Absolutely amazing sound quality! The noise cancellation is incredible, and the battery life is exactly as advertised. Worth every penny!"</p>
                        </div>
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <strong>Sarah M.</strong>
                            </div>
                            <p style="color: #666;">"Great headphones overall. Comfortable for long periods and the sound quality is excellent. Only minor issue is they're a bit heavy."</p>
                        </div>
                        <button class="btn btn-primary">Load More Reviews</button>
                    </div>
                    <div class="tab-pane" id="shipping">
                        <h3>Shipping & Delivery</h3>
                        <p><strong>Free Standard Shipping</strong> on orders over $99</p>
                        <p><strong>Express Shipping</strong> available at checkout</p>
                        <br>
                        <h4>Delivery Times:</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>Standard: 5-7 business days</li>
                            <li>Express: 2-3 business days</li>
                            <li>Overnight: Next business day (order before 2 PM)</li>
                        </ul>
                        <br>
                        <p>We ship worldwide. International shipping rates calculated at checkout.</p>
                    </div>
                </div>
            </div>

            <!-- Related Products / Recently Viewed Section -->
            <div>
                <div class="section-tabs">
                    <div class="section-tab active" onclick="showSection('related')">Related Products</div>
                    <div class="section-tab" onclick="showSection('recent')">Recently Viewed</div>
                </div>

                <div class="products-grid" id="related-products">
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Wireless Earbuds Pro</h4>
                            <p class="product-card-price">$149.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Bluetooth Speaker Max</h4>
                            <p class="product-card-price">$199.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Studio Headphones</h4>
                            <p class="product-card-price">$249.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Gaming Headset RGB</h4>
                            <p class="product-card-price">$179.99</p>
                        </div>
                    </div>
                </div>

                <div class="products-grid" id="recently-viewed" style="display: none;">
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Portable DAC</h4>
                            <p class="product-card-price">$299.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Audio Cable Premium</h4>
                            <p class="product-card-price">$49.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Headphone Stand</h4>
                            <p class="product-card-price">$39.99</p>
                        </div>
                    </div>
                    <div class="product-card">
                        <img src="demo-image.png" alt="Product">
                        <div class="product-card-content">
                            <h4 class="product-card-title">Carrying Case Pro</h4>
                            <p class="product-card-price">$29.99</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quantity Selector
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Tab Switching
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }

        // Section Switching (Related/Recent)
        function showSection(section) {
            // Remove active class from all tabs
            document.querySelectorAll('.section-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Hide both grids
            document.getElementById('related-products').style.display = 'none';
            document.getElementById('recently-viewed').style.display = 'none';

            // Show selected section
            if (section === 'related') {
                document.getElementById('related-products').style.display = 'grid';
                document.querySelectorAll('.section-tab')[0].classList.add('active');
            } else {
                document.getElementById('recently-viewed').style.display = 'grid';
                document.querySelectorAll('.section-tab')[1].classList.add('active');
            }
        }

        // Add to cart animation
        document.querySelector('.btn-primary').addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Added!';
            this.style.background = 'linear-gradient(135deg, #56ab2f 0%, #a8e063 100%)';

            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.background = '';
            }, 2000);
        });
    </script>

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

		// Get real product data
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
		$current_price = $product->get_price();
		$currency_symbol = get_woocommerce_currency_symbol();
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$is_on_sale = $product->is_on_sale();

		$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
		$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();

		$attachment_ids = $product->get_gallery_image_ids();
		$average_rating = $product->get_average_rating();
		$rating_count = $product->get_rating_count();

		?>

		<div class="shopglut-pro-product-wrapper" data-product-id="<?php echo esc_attr($product_id); ?>">
			<div class="pro-product-container">
				<!-- Left Side - Product Gallery -->
				<div class="pro-product-gallery">
					<div class="pro-main-image">
						<img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
					</div>
					<?php if (!empty($attachment_ids)): ?>
					<div class="pro-thumbnails">
						<div class="pro-thumb active"><img src="<?php echo esc_url($product_image_url); ?>" alt="Thumbnail 1"></div>
						<?php foreach ($attachment_ids as $index => $attachment_id): ?>
							<?php
							$thumb_img = wp_get_attachment_image_src($attachment_id, 'medium');
							if ($thumb_img): ?>
								<div class="pro-thumb">
									<img src="<?php echo esc_url($thumb_img[0]); ?>" alt="Thumbnail <?php echo esc_attr($index + 2); ?>">
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>

				<!-- Right Side - Product Details -->
				<div class="pro-product-details">
					<?php if ($is_on_sale): ?>
					<div class="pro-badges">
						<span class="pro-badge-sale">SALE</span>
					</div>
					<?php endif; ?>

					<!-- Product Title -->
					<h1 class="pro-title"><?php echo esc_html($product_title); ?></h1>

					<!-- Rating -->
					<?php if ($average_rating > 0): ?>
					<div class="pro-rating">
						<div class="pro-stars">
							<?php for ($i = 1; $i <= 5; $i++): ?>
								<i class="fas fa-star<?php echo $i <= $average_rating ? '' : '-o'; ?>"></i>
							<?php endfor; ?>
						</div>
						<span class="pro-rating-text"><?php echo esc_html($average_rating . ' (' . $rating_count . ' reviews)'); ?></span>
					</div>
					<?php endif; ?>

					<!-- Price -->
					<div class="pro-price-wrapper">
						<span class="pro-current-price"><?php echo esc_html($currency_symbol . number_format((float)$current_price, 2)); ?></span>
						<?php if ($is_on_sale && $regular_price): ?>
							<span class="pro-original-price"><?php echo esc_html($currency_symbol . number_format((float)$regular_price, 2)); ?></span>
						<?php endif; ?>
					</div>

					<!-- Short Description -->
					<?php if (!empty($product_description)): ?>
					<div class="pro-short-description">
						<?php echo wp_kses_post($product_description); ?>
					</div>
					<?php endif; ?>

					<!-- Quantity & Cart Actions -->
					<div class="pro-cart-actions">
						<div class="pro-quantity">
							<button class="pro-qty-btn pro-qty-minus">-</button>
							<input type="number" class="pro-qty-input" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity() == -1 ? 9999 : $product->get_max_purchase_quantity()); ?>">
							<button class="pro-qty-btn pro-qty-plus">+</button>
						</div>
						<button class="pro-add-cart single_add_to_cart_button" data-product-id="<?php echo esc_attr($product_id); ?>">
							<i class="fas fa-shopping-bag"></i>
							Add to Cart
						</button>
					</div>
				</div>
			</div>
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
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		$cache_key = 'shopglut_single_product_layout_' . $layout_id;
		$layout_data = wp_cache_get($cache_key, 'shopglut_layouts');

		if (false === $layout_data) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
			$layout_data = $wpdb->get_row(
				$wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_single_product_layout` WHERE id = %d", $layout_id)
			);
			wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
		}

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_singleproduct_settings_templatePro1']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_templatePro1']['single-product-settings']);
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
	 * Get default settings values for single product template
	 */
	private function getDefaultSettings() {
		return array(
			'show_product_badges' => true,
			'show_rating' => true,
			'show_description' => true,
			'show_thumbnails' => true,
		);
	}
}
