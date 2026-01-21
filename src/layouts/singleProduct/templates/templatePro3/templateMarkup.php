<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro3;

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

	<div class="shopglut-single-templatePro3">
        <div class="container">
            <!-- Product Section with Left and Right Side -->
            <div class="product-section">
                <!-- Left Side - Image Gallery -->
                <div class="product-images">
                    <div class="thumbnail-container">
                        <div class="thumbnail active" onclick="changeImage(1)">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 1">
                        </div>
                        <div class="thumbnail" onclick="changeImage(2)">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 2">
                        </div>
                        <div class="thumbnail" onclick="changeImage(3)">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 3">
                        </div>
                        <div class="thumbnail" onclick="changeImage(4)">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 4">
                        </div>
                        <div class="thumbnail" onclick="changeImage(5)">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 5">
                        </div>
                    </div>
                    <div class="main-image">
                        <img id="mainImage" src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image">
                    </div>
                </div>

                <!-- Right Side - Product Information -->
                <div class="product-info">
                    <div class="product-category">Premium Collection</div>
                    <h1 class="product-title">Professional Wireless Headphones with Noise Cancellation</h1>

                    <div class="product-rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-count">(128 Reviews)</span>
                    </div>

                    <div class="product-price">
                        <span class="current-price">$299.99</span>
                        <span class="original-price">$399.99</span>
                    </div>

                    <div class="product-description">
                        Experience premium sound quality with our professional wireless headphones. Featuring advanced noise cancellation technology, 30-hour battery life, and superior comfort for all-day wear. Perfect for music lovers and professionals who demand the best in audio performance.
                    </div>

                    <!-- Product Options -->
                    <div class="product-options">
                        <div class="option-group">
                            <span class="option-label">Size:</span>
                            <div class="size-options">
                                <button class="size-btn active">S</button>
                                <button class="size-btn">M</button>
                                <button class="size-btn">L</button>
                                <button class="size-btn">XL</button>
                            </div>

                            <span class="option-label">Color:</span>
                            <div class="color-options">
                                <button class="color-btn black active" title="Black"></button>
                                <button class="color-btn blue" title="Blue"></button>
                                <button class="color-btn red" title="Red"></button>
                                <button class="color-btn green" title="Green"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity, Add to Cart, Stock -->
                    <div class="quantity-cart-section">
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                            <input type="number" class="quantity-input" id="quantity" value="1" min="1">
                            <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                        </div>

                        <button class="add-to-cart-btn">Add to Cart</button>

                        <div class="stock-info">Only 5 left in stock!</div>
                    </div>

                    <!-- Wishlist and Compare -->
                    <div class="wishlist-compare">
                        <button class="wishlist-btn">
                            <span>♡</span> Add to Wishlist
                        </button>
                        <button class="compare-btn">
                            <span>⇄</span> Add to Compare
                        </button>
                    </div>

                    <!-- SKU and Categories -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value">WH-NC-001</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Categories:</span>
                            <span class="meta-value">Electronics, Audio, Headphones</span>
                        </div>
                    </div>

                    <!-- Social Share -->
                    <div class="social-share">
                        <a href="#" class="social-btn facebook">f</a>
                        <a href="#" class="social-btn twitter">𝕏</a>
                        <a href="#" class="social-btn instagram">📷</a>
                        <a href="#" class="social-btn whatsapp">✆</a>
                    </div>
                </div>
            </div>

            <!-- Tabs Section with Featured Product -->
            <div class="tabs-section">
                <div class="product-tabs">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="showTab('description')">📝 Description</button>
                        <button class="tab-btn" onclick="showTab('specifications')">⚙️ Specifications</button>
                        <button class="tab-btn" onclick="showTab('reviews')">⭐ Reviews</button>
                        <button class="tab-btn" onclick="showTab('shipping')">🚚 Shipping</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="description">
                            <h3>Product Description</h3>
                            <p>Our professional wireless headphones represent the pinnacle of audio engineering. With industry-leading noise cancellation technology, you can immerse yourself in your music without distractions.</p>
                            <p>Key features include:</p>
                            <ul>
                                <li>Active Noise Cancellation with transparency mode</li>
                                <li>30-hour battery life with quick charging</li>
                                <li>Premium memory foam ear cushions</li>
                                <li>Multi-device connectivity (2 devices simultaneously)</li>
                                <li>Voice assistant integration</li>
                            </ul>
                        </div>

                        <div class="tab-pane" id="specifications">
                            <h3>Technical Specifications</h3>
                            <table>
                                <tr><td>Driver Size:</td><td>40mm dynamic drivers</td></tr>
                                <tr><td>Frequency Response:</td><td>20Hz - 20kHz</td></tr>
                                <tr><td>Impedance:</td><td>32 Ohms</td></tr>
                                <tr><td>Bluetooth Version:</td><td>5.2</td></tr>
                                <tr><td>Charging Time:</td><td>2.5 hours</td></tr>
                                <tr><td>Weight:</td><td>250g</td></tr>
                            </table>
                        </div>

                        <div class="tab-pane" id="reviews">
                            <h3>Customer Reviews</h3>
                            <div class="review">
                                <h4>⭐⭐⭐⭐⭐ Excellent Sound Quality</h4>
                                <p>By John D. - Verified Purchase</p>
                                <p>These headphones exceeded my expectations. The noise cancellation is incredible and the sound quality is crystal clear.</p>
                            </div>
                            <div class="review">
                                <h4>⭐⭐⭐⭐⭐ Worth Every Penny</h4>
                                <p>By Sarah M. - Verified Purchase</p>
                                <p>Comfortable for all-day wear. Battery life is amazing. Highly recommend!</p>
                            </div>
                        </div>

                        <div class="tab-pane" id="shipping">
                            <h3>Shipping & Returns</h3>
                            <p>Free shipping on orders over $50</p>
                            <p>Standard shipping: 5-7 business days</p>
                            <p>Express shipping: 2-3 business days</p>
                            <p>30-day return policy</p>
                            <p>2-year manufacturer warranty</p>
                        </div>
                    </div>
                </div>

                <!-- Featured Product -->
                <div class="featured-product">
                    <h3 class="featured-title">🔥 Featured Deal</h3>
                    <div class="featured-item">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Featured Product" class="featured-image">
                        <div class="featured-overlay">
                            <h4 class="featured-product-title">Smart Watch Pro</h4>
                            <div class="featured-price">$199.99</div>
                            <button class="buy-now-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            <div class="related-section">
                <h2 class="section-title">Discover More Products</h2>

                <div class="related-tabs">
                    <button class="related-tab-btn active" onclick="showRelatedProducts('related')">🔗 Related Products</button>
                    <button class="related-tab-btn" onclick="showRelatedProducts('recently')">👁️ Recently Viewed</button>
                </div>

                <div class="products-grid" id="relatedProducts">
                    <!-- Related Products Grid -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image Gallery
        function changeImage(imageNumber) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = `<?php echo esc_url($placeholder_url); ?>`;

            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach((thumb, index) => {
                if (index === imageNumber - 1) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }

        // Quantity Update
        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + change;

            if (newValue < 1) newValue = 1;
            if (newValue > 99) newValue = 99;

            quantityInput.value = newValue;
        }

        // Tab Switching
        function showTab(tabName) {
            // Hide all tabs
            const tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }

        // Product Data
        const relatedProducts = [
            { id: 1, title: "Wireless Earbuds Pro", price: "$149.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 2, title: "Studio Monitor Speakers", price: "$399.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 3, title: "Audio Interface", price: "$249.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 4, title: "Microphone Stand", price: "$49.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 5, title: "DJ Controller", price: "$599.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 6, title: "Portable Speaker", price: "$89.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 7, title: "Cable Organizer", price: "$19.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 8, title: "Headphone Stand", price: "$29.99", image: "<?php echo esc_url($placeholder_url); ?>" }
        ];

        const recentlyViewedProducts = [
            { id: 9, title: "Gaming Headset", price: "$79.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 10, title: "USB Microphone", price: "$129.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 11, title: "Mixer Console", price: "$449.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 12, title: "Amplifier", price: "$699.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 13, title: "Turntable", price: "$299.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 14, title: "Audio Cables Set", price: "$39.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 15, title: "Carrying Case", price: "$59.99", image: "<?php echo esc_url($placeholder_url); ?>" },
            { id: 16, title: "Power Bank", price: "$49.99", image: "<?php echo esc_url($placeholder_url); ?>" }
        ];

        // Related Products Tabs
        function showRelatedProducts(type) {
            const productsGrid = document.getElementById('relatedProducts');
            const products = type === 'related' ? relatedProducts : recentlyViewedProducts;

            // Update active tab button
            const tabButtons = document.querySelectorAll('.related-tab-btn');
            tabButtons.forEach((btn, index) => {
                btn.classList.remove('active');
                if ((type === 'related' && index === 0) || (type === 'recently' && index === 1)) {
                    btn.classList.add('active');
                }
            });

            // Render products
            productsGrid.innerHTML = products.map(product => `
                <div class="product-card">
                    <img src="${product.image}" alt="${product.title}" class="product-card-image">
                    <div class="product-card-body">
                        <h4 class="product-card-title">${product.title}</h4>
                        <div class="product-card-price">${product.price}</div>
                        <button class="add-card-btn">Add to Cart</button>
                    </div>
                </div>
            `).join('');
        }

        // Initialize with related products
        showRelatedProducts('related');

        // Size and Color Selection
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Prevent quantity input from going below 1
        document.getElementById('quantity').addEventListener('input', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 99) this.value = 99;
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
