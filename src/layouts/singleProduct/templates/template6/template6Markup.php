<?php
namespace Shopglut\layouts\singleProduct\templates\template6;

if (!defined('ABSPATH')) {
	exit;
}

// Include template6 AJAX handler
require_once __DIR__ . '/template6-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template6Markup {


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
		<div class="shopglut-single-product template6 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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

	 <div class="single-product-template6">
        <!-- Product Page -->
        <div class="product-page">
            <div class="container-fluid px-4">
                <div class="product-container">
                    <div class="row">
                        <!-- Left Side - Image Gallery -->
                        <div class="col-lg-6">
                            <div class="left-gallery">
                                <div class="image-gallery">
                                    <div class="thumbnail-list">
                                        <div class="thumbnail active" onclick="changeProductImage('headphones1')">
                                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 1">
                                        </div>
                                        <div class="thumbnail" onclick="changeProductImage('headphones2')">
                                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 2">
                                        </div>
                                        <div class="thumbnail" onclick="changeProductImage('headphones3')">
                                            <img src=<?php echo esc_url($placeholder_url); ?> alt="Thumbnail 3">
                                        </div>
                                        <div class="thumbnail" onclick="changeProductImage('headphones4')">
                                            <img src=<?php echo esc_url($placeholder_url); ?> alt="Thumbnail 4">
                                        </div>
                                        <div class="thumbnail" onclick="changeProductImage('headphones5')">
                                            <img src=<?php echo esc_url($placeholder_url); ?> alt="Thumbnail 5">
                                        </div>
                                    </div>

                                    <div class="main-image-container">
                                        <img id="mainProductImage" class="main-image" src=<?php echo esc_url($placeholder_url); ?> alt="Product Image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Product Details -->
                        <div class="col-lg-6">
                            <div class="product-details">
                                <!-- Review and Count -->
                                <div class="rating-section">
                                    <div class="stars">
                                        <span>★★★★½</span>
                                    </div>
                                    <div class="review-count">245 Reviews</div>
                                </div>

                                <!-- Product Title -->
                                <h1 class="product-title">Premium Wireless Noise-Canceling Headphones</h1>

                                <!-- Inline Meta Info -->
                                <div class="product-inline-meta">
                                    <span class="inline-meta-item">
                                        <span class="meta-icon">#</span>
                                        <span class="meta-label">SKU:</span>
                                        <span class="meta-value">WH-NC-2023-01</span>
                                    </span>
                                    <span class="inline-meta-item">
                                        <span class="meta-icon">📁</span>
                                        <span class="meta-label">Categories:</span>
                                        <span class="meta-value">Electronics, Audio, Headphones</span>
                                    </span>
                                </div>

                                <!-- Price and Stock Status -->
                                <div class="price-stock">
                                    <div class="price">$189.99</div>
                                    <div class="stock-status">
                                        <span>✓</span>
                                        In Stock
                                    </div>
                                </div>

                                <!-- Short Description -->
                                <div class="short-description">
                                    Experience premium sound quality with our state-of-the-art wireless headphones. Featuring advanced noise-canceling technology, 30-hour battery life, and comfortable all-day wear. Perfect for music lovers, professionals, and travelers who demand the best in audio performance.
                                </div>

                                <!-- Variable Product Options - Enhanced -->
                                <div class="product-options">
                                    <div class="option-group">
                                        <label class="option-label">Color:</label>
                                        <div class="option-values">
                                            <div class="color-option selected" onclick="selectOption(this)">
                                                <span class="color-swatch" style="background-color: #1a1a1a;"></span>
                                            </div>
                                            <div class="color-option" onclick="selectOption(this)">
                                                <span class="color-swatch" style="background-color: #f8f8f8; border: 1px solid #e0e0e0;"></span>
                                            </div>
                                            <div class="color-option" onclick="selectOption(this)">
                                                <span class="color-swatch" style="background-color: #e8b4b8;"></span>
                                            </div>
                                            <div class="color-option" onclick="selectOption(this)">
                                                <span class="color-swatch" style="background-color: #6c757d;"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="option-group">
                                        <label class="option-label">Size:</label>
                                        <div class="option-values">
                                            <div class="option-value size-option" onclick="selectOption(this)">S</div>
                                            <div class="option-value size-option selected" onclick="selectOption(this)">M</div>
                                            <div class="option-value size-option" onclick="selectOption(this)">L</div>
                                            <div class="option-value size-option" onclick="selectOption(this)">XL</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- All Action Buttons -->
                                <div class="action-row">
                                    <div class="action-row-top">
                                        <div class="quantity-selector">
                                            <button onclick="decreaseQuantity()">-</button>
                                            <input type="text" id="quantity" value="1" readonly>
                                            <button onclick="increaseQuantity()">+</button>
                                        </div>
                                        <button class="btn-add-to-cart" onclick="addToCart()">
                                            <span>🛒</span>
                                            Add to Cart
                                        </button>
                                    </div>
                                    <div class="action-row-bottom">
                                        <button class="btn-wishlist" onclick="addToWishlist()">
                                            <span>♥</span>
                                            Wishlist
                                        </button>
                                        <button class="btn-compare" onclick="addToCompare()">
                                            <span>↔</span>
                                            Compare
                                        </button>
                                    </div>
                                </div>

                                <!-- Border Divider -->
                                <div class="border-divider"></div>

                                <!-- Product Meta Information -->
                                <div class="product-meta">
                                    <div class="meta-item">
                                        <div class="social-share">
                                            <h6>Share</h6>
                                            <div class="social-icons">
                                                <a class="social-icon facebook" href="#">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </a>
                                                <a class="social-icon twitter" href="#">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                </a>
                                                <a class="social-icon instagram" href="#">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.757-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                                    </svg>
                                                </a>
                                                <a class="social-icon pinterest" href="#">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="tabs-wrapper">
                    <ul class="nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-target="#description" type="button" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specifications-tab" data-bs-target="#specifications" type="button" role="tab">Specifications</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-target="#reviews" type="button" role="tab">Reviews (245)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-target="#shipping" type="button" role="tab">Shipping & Returns</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabContent">
                        <!-- Description Tab -->
                        <div class="tab-pane active" id="description" role="tabpanel">
                            <h4>Product Description</h4>
                            <p>Experience premium sound quality with our state-of-the-art wireless headphones. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear audio with deep bass and crisp highs.</p>

                            <p>Featuring advanced noise-canceling technology, these headphones create an immersive listening experience by blocking out unwanted ambient noise. Whether you're commuting, working, or relaxing at home, you'll enjoy your music without distractions.</p>

                            <p>The ergonomic design ensures all-day comfort with soft ear cushions and an adjustable headband. With up to 30 hours of battery life on a single charge, you can enjoy your favorite playlists, podcasts, and calls throughout the day without interruption.</p>

                            <h5>Key Features:</h5>
                            <ul>
                                <li>Active Noise Cancellation (ANC)</li>
                                <li>30-hour battery life</li>
                                <li>Bluetooth 5.0 connectivity</li>
                                <li>Touch gesture controls</li>
                                <li>Built-in microphone with voice assistant support</li>
                                <li>Foldable design with carrying case</li>
                                <li>Quick charge: 5 min = 2 hours of playback</li>
                                <li>Compatible with iOS, Android, and other Bluetooth devices</li>
                            </ul>
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane" id="specifications" role="tabpanel">
                            <h4>Technical Specifications</h4>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><strong>Driver Size</strong></td>
                                        <td>40mm Dynamic Driver</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frequency Response</strong></td>
                                        <td>20Hz - 20kHz</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Impedance</strong></td>
                                        <td>32 Ohms</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sensitivity</strong></td>
                                        <td>105dB</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Battery Life</strong></td>
                                        <td>30 hours (ANC off), 25 hours (ANC on)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Charging Time</strong></td>
                                        <td>2 hours (full charge)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bluetooth Version</strong></td>
                                        <td>5.0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Wireless Range</strong></td>
                                        <td>10 meters (33 feet)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Weight</strong></td>
                                        <td>250g</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane" id="reviews" role="tabpanel">
                            <h4>Customer Reviews</h4>

                            <!-- Review 1 -->
                            <div class="review-item mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="fw-bold">Sarah Johnson</div>
                                    <div class="text-muted">October 15, 2023</div>
                                </div>
                                <div class="stars mb-2">
                                    <span>★★★★★</span>
                                </div>
                                <p>These headphones are absolutely amazing! The sound quality is exceptional, with rich bass and crisp highs. The noise cancellation is so effective that I can barely hear anything when it's turned on maximum. Highly recommend!</p>
                            </div>

                            <!-- Review 2 -->
                            <div class="review-item mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="fw-bold">Michael Chen</div>
                                    <div class="text-muted">October 10, 2023</div>
                                </div>
                                <div class="stars mb-2">
                                    <span>★★★★☆</span>
                                </div>
                                <p>Great headphones overall. The sound quality is excellent and the build feels premium. The touch controls take some getting used to, but work well once you're familiar with them. Comfortable and worth the price.</p>
                            </div>

                            <button class="btn-primary">Load More Reviews</button>
                        </div>

                        <!-- Shipping & Returns Tab -->
                        <div class="tab-pane" id="shipping" role="tabpanel">
                            <h4>Shipping & Returns</h4>
                            <p>We offer several shipping options to meet your needs:</p>
                            <ul>
                                <li><strong>Standard Shipping (3-5 business days):</strong> Free on orders over $50</li>
                                <li><strong>Express Shipping (2-3 business days):</strong> $12.99</li>
                                <li><strong>Overnight Shipping (1 business day):</strong> $24.99</li>
                            </ul>

                            <h5>Returns Policy</h5>
                            <p>30-day return policy on all items. Items must be unused and in original packaging.</p>

                            <h5>Warranty</h5>
                            <p>1-year manufacturer warranty covering defects in materials and workmanship.</p>
                        </div>
                    </div>
                </div>

                <!-- Highly Recommend Related Products -->
                <div class="related-products">
                    <h2 class="section-title">Highly Recommended</h2>
                    <div class="product-grid">
                        <!-- Product 1 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 1">
                                <div class="product-badge">-25%</div>
                            </div>
                            <div class="product-info">
                                <div class="product-name">Premium Wireless Earbuds Pro</div>
                                <div class="product-price">
                                    <span class="current-price-small">$89.99</span>
                                    <span class="original-price-small">$119.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★☆</span>
                                    </div>
                                    <span class="review-count-small">(142)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 2">
                                <div class="product-badge">New</div>
                            </div>
                            <div class="product-info">
                                <div class="product-name">Portable Bluetooth Speaker</div>
                                <div class="product-price">
                                    <span class="current-price-small">$59.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★★</span>
                                    </div>
                                    <span class="review-count-small">(87)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 3">
                                <div class="product-badge">-15%</div>
                            </div>
                            <div class="product-info">
                                <div class="product-name">Professional Studio Headphones</div>
                                <div class="product-price">
                                    <span class="current-price-small">$149.99</span>
                                    <span class="original-price-small">$174.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★½</span>
                                    </div>
                                    <span class="review-count-small">(63)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 4">
                                <div class="product-badge">Hot</div>
                            </div>
                            <div class="product-info">
                                <div class="product-name">Sports Wireless Earbuds</div>
                                <div class="product-price">
                                    <span class="current-price-small">$69.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★☆</span>
                                    </div>
                                    <span class="review-count-small">(115)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 5">
                            </div>
                            <div class="product-info">
                                <div class="product-name">Premium Headphone Storage Case</div>
                                <div class="product-price">
                                    <span class="current-price-small">$24.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★★</span>
                                    </div>
                                    <span class="review-count-small">(42)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src=<?php echo esc_url($placeholder_url); ?> alt="Product 6">
                            </div>
                            <div class="product-info">
                                <div class="product-name">Wireless Charging Station</div>
                                <div class="product-price">
                                    <span class="current-price-small">$39.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <span>★★★★☆</span>
                                    </div>
                                    <span class="review-count-small">(78)</span>
                                </div>
                                <button class="btn-view-product">View Product</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove active from all tabs
                document.querySelectorAll('.nav-tabs .nav-link').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                // Add active to clicked tab
                this.classList.add('active');
                const target = this.getAttribute('data-bs-target');
                document.querySelector(target).classList.add('active');
            });
        });

        // Change product image when thumbnail is clicked
        function changeProductImage(imageName) {
            const mainImage = document.getElementById('mainProductImage');
            mainImage.src = 'demo-image.png';

            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumbnail => {
                thumbnail.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        // Select product option
        function selectOption(element) {
            // Remove selected class from siblings
            const siblings = element.parentElement.querySelectorAll('.option-value');
            siblings.forEach(sibling => {
                sibling.classList.remove('selected');
            });

            // Add selected class to clicked element
            element.classList.add('selected');
        }

        // Quantity selector functions
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        // Add to cart function
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            alert(`Added ${quantity} item(s) to cart!`);
        }

        // Add to wishlist function
        function addToWishlist() {
            alert('Added to wishlist!');
        }

        // Add to compare function
        function addToCompare() {
            alert('Added to compare list!');
        }
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
			'shopglut-template6-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template6/template6-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template6-frontend', 'shopglut_frontend_vars', array(
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
					<div class="main-image-container">
						<!-- Badge Display Area -->
						<div class="shopglut-badges-container">
							<?php
							// Get BadgeDataManage instance to display badges
							if (class_exists('Shopglut\enhancements\ProductBadges\BadgeDataManage')) {
								$badge_manager = \Shopglut\enhancements\ProductBadges\BadgeDataManage::get_instance();
								if (method_exists($badge_manager, 'display_badges_on_product_image')) {
									$badge_manager->display_badges_on_product_image();
								}
							}
							?>
						</div>

						<div class="image-loading-placeholder" style="display: none;">
							<div class="loading-spinner"></div>
						</div>
						<img src="<?php echo esc_url($product_image_url); ?>"
							 alt="<?php echo esc_attr($product_title); ?>"
							 class="main-product-image template-preview-image loaded"
							>
					</div>

					<?php if ($this->shouldShowThumbnails($settings) && (!empty($attachment_ids) || $product_image)): ?>
					<div class="thumbnail-gallery">
						<?php
						// Main image thumbnail
						if ($product_image): ?>
							<div class="thumbnail-item active">
								<img src="<?php echo esc_url($product_image_url); ?>"
									 alt="<?php echo esc_attr($product_title); ?>"
									 class="thumbnail-image template-preview-image loaded">
							</div>
						<?php endif;
						// Gallery thumbnails
						foreach ($attachment_ids as $index => $attachment_id):
							$gallery_image = wp_get_attachment_image_src($attachment_id, 'medium');
							if ($gallery_image): ?>
								<div class="thumbnail-item">
									<img src="<?php echo esc_url($gallery_image[0]); ?>"
										 alt="<?php echo esc_attr($product_title . ' gallery'); ?>"
										 class="thumbnail-image template-preview-image loaded">
								</div>
							<?php endif;
						endforeach; ?>
					</div>
					<?php endif; ?>
				</div>

				<!-- Product Info -->
				<div class="product-info-section">

					<!-- Product Badges -->
					<?php if ($this->shouldShowBadges($settings) && !empty($product_badges)): ?>
					<div class="product-badges-container">
						<?php foreach ($product_badges as $badge): ?>
							<?php if ($this->shouldShowBadgeType($settings, $badge['type'])): ?>
							<span class="product-badge badge-<?php echo esc_attr($badge['type']); ?>"
								 >
								<?php echo esc_html($this->getBadgeText($settings, $badge['type'], $badge['text'])); ?>
							</span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<!-- Module Integration: Badges (before title position) -->
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_product_title', 'badges'); ?>

					<!-- Product Title -->
					<h1 class="product-title">
						<?php echo esc_html($product_title); ?>
					</h1>

					<!-- Module Integration: After title position -->
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_product_title'); ?>

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
					<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_price'); ?>

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
													<?php echo esc_html($product->single_add_to_cart_text()); ?>
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
									<?php echo esc_html($product->single_add_to_cart_text()); ?>
								</button>

								<!-- Module Integration: After Add to Cart -->
								<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>
							</form>
						<?php elseif ($product->is_type('external')): ?>
							<!-- External/Affiliate Product Purchase Section -->

							<!-- Module Integration: Before Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'before_add_to_cart'); ?>

							<div class="external-product-wrapper">
								<a href="<?php echo esc_url($product->get_product_url()); ?>" target="_blank" class="single_add_to_cart_button button alt external-product-btn" rel="nofollow">
									<?php echo esc_html($product->single_add_to_cart_text()); ?>
								</a>
							</div>

							<!-- Module Integration: After Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>
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
									<?php esc_html_e('Add to Cart', 'shopglut'); ?>
								</button>
							</div>

							<!-- Module Integration: After Add to Cart -->
							<?php ModuleIntegration::render_module_wrapper($settings, $product_id, 'after_add_to_cart'); ?>
						<?php endif; ?>

						
					</div>
				</div>
			</div>

			<!-- Features Section -->
			<?php if ($this->shouldShowFeaturesSection($settings)): ?>
			<div class="features-section">
				<?php if ($this->shouldShowFeaturesSectionTitle($settings)): ?>
				<h2 class="features-title">
					<?php echo esc_html($this->getFeaturesSectionTitle($settings)); ?>
				</h2>
				<?php endif; ?>

				<div class="features-grid">
					<?php foreach ($demo_features as $feature): ?>
					<div class="feature-item">
						<?php if ($feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
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

						<?php if ($feature['feature_link_enabled'] && !empty($feature['feature_link_url'])): ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<!-- WooCommerce Product Tabs -->
			<div class="woocommerce-tabs-section">
				<div class="woocommerce-tabs wc-tabs-wrapper">
					<ul class="tabs wc-tabs">
						<li class="description_tab active">
							<a href="#tab-description">Description</a>
						</li>
						<?php if (!empty($attributes) || $product->has_attributes()): ?>
						<li class="additional_information_tab">
							<a href="#tab-additional_information">Additional Information</a>
						</li>
						<?php endif; ?>
						<?php if (comments_open() || $product->get_review_count() > 0): ?>
						<li class="reviews_tab">
							<a href="#tab-reviews">Reviews (<?php echo esc_html($product->get_review_count()); ?>)</a>
						</li>
						<?php endif; ?>
					</ul>

					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab active" id="tab-description">
						<?php if (!empty($product_description)): ?>
							<div class="product-description-content">
								<?php
								// Get the full product description (not just short description)
								$full_description = $product->get_description();
								if (!empty($full_description)) {
									$clean_description = wp_kses_post($full_description);
									$clean_description = force_balance_tags($clean_description);
									echo wp_kses_post($clean_description);
								} elseif (!empty($product_description)) {
									// Fallback to short description if full description is empty
									$clean_description = wp_kses_post($product_description);
									$clean_description = force_balance_tags($clean_description);
									echo wp_kses_post($clean_description);
								} else {
									echo '<p>' . esc_html__('No description available for this product.', 'shopglut') . '</p>';
								}
								?>
							</div>
						<?php else: ?>
							<p><?php esc_html_e('No description available for this product.', 'shopglut'); ?></p>
						<?php endif; ?>
					</div>

					<?php if (!empty($attributes) || $product->has_attributes()): ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information">
						<?php if ($product->has_attributes()): ?>
							<table class="woocommerce-product-attributes shop_attributes">
								<?php foreach ($attributes as $attribute_name => $attribute): ?>
									<?php
									$attribute_label = wc_attribute_label($attribute_name);
									$attribute_values = $product->get_attribute($attribute_name);
									if (!empty($attribute_values)): ?>
										<tr>
											<th><?php echo esc_html($attribute_label); ?></th>
											<td><?php echo wp_kses_post($attribute_values); ?></td>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>

								<?php // Display weight and dimensions if available ?>
								<?php if ($product->has_weight()): ?>
									<tr>
										<th><?php esc_html_e('Weight', 'shopglut'); ?></th>
										<td><?php echo esc_html($product->get_weight() . ' ' . get_option('woocommerce_weight_unit')); ?></td>
									</tr>
								<?php endif; ?>

								<?php if ($product->has_dimensions()): ?>
									<tr>
										<th><?php esc_html_e('Dimensions', 'shopglut'); ?></th>
										<td><?php echo esc_html($product->get_dimensions() . ' ' . get_option('woocommerce_dimension_unit')); ?></td>
									</tr>
								<?php endif; ?>
							</table>
						<?php else: ?>
							<p><?php esc_html_e('No additional information available for this product.', 'shopglut'); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if (comments_open() || $product->get_review_count() > 0): ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews">
						<?php
						// Check if comments are supported and enabled
						if (comments_open()) {
							// Display review form
							comments_template();
						}

						// Display existing reviews
						if ($product->get_review_count() > 0) {
							// Get reviews from comments
							$args = array(
								'post_id' => $product_id,
								'status' => 'approve',
								'type' => 'review'
							);
							$reviews = get_comments($args);

							if (!empty($reviews)) {
								echo '<div class="product-reviews">';
								foreach ($reviews as $review) {
									$rating = get_comment_meta($review->comment_ID, 'rating', true);
									echo '<div class="review-item">';
									if ($rating) {
										echo '<div class="review-rating">' . esc_html(str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)) . '</div>';
									}
									echo '<div class="review-author"><strong>' . esc_html($review->comment_author) . '</strong></div>';
									echo '<div class="review-date">' . esc_html(gmdate('F j, Y', strtotime($review->comment_date))) . '</div>';
									echo '<div class="review-content">' . wp_kses_post($review->comment_content) . '</div>';
									echo '</div>';
								}
								echo '</div>';
							}
						} else {
							echo '<p>' . esc_html__('There are no reviews yet.', 'shopglut') . '</p>';
						}
						?>
					</div>
					<?php endif; ?>
				</div>
			</div>

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

						<button class="quick-add-btn" data-product-id="<?php echo esc_attr($related_product['id']); ?>">
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
	 * (All styling is handled in template6Style.php)
	 */

	/**
	 * Helper method to get setting value with fallback
	 */
	private function getSetting($settings, $key, $default = '') {
		return isset($settings[$key]) ? $settings[$key] : $default;
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
			if (isset($settings['shopg_singleproduct_settings_template6']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template6']['single-product-settings']);
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
			'thumbnail_active_border' => '#667eea',

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
			'related_section_title' => 'You Might Also Like',
			'related_section_title_color' => '#111827',
			'related_products_per_row' => '4',
			'product_card_background' => '#ffffff',
			'product_card_border_color' => '#e5e7eb',
			'product_card_border_radius' => 8,
			'product_card_hover_shadow' => true,
			'quick_add_button_background' => '#667eea',
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
		);
	}
}