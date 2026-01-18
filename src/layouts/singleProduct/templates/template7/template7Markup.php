<?php
namespace Shopglut\layouts\singleProduct\templates\template7;

if (!defined('ABSPATH')) {
	exit;
}

// Include template7 AJAX handler
require_once __DIR__ . '/template7-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template7Markup {


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
		<div class="shopglut-single-product template7 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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

<div class="single-product-template7">
    <!-- Product Page -->
    <div class="product-page">
        <div class="container-fluid px-4">
            <div class="product-container">
                <div class="row">
                    <!-- Left Side - Image Gallery -->
                    <div class="col-lg-6">
                        <div class="left-gallery">
                            <!-- Main Image -->
                            <div class="main-image-container">
                                <img id="mainProductImage" class="main-image" src="<?php echo esc_url($placeholder_url); ?>" alt="Premium Wireless Headphones">
                            </div>

                            <!-- Image Carousel -->
                            <div class="image-carousel">
                                <div class="carousel-container" id="imageCarousel">
                                    <div class="carousel-item active" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Main View">
                                    </div>
                                    <div class="carousel-item" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Side View">
                                    </div>
                                    <div class="carousel-item" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Detail View">
                                    </div>
                                    <div class="carousel-item" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Features">
                                    </div>
                                    <div class="carousel-item" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Accessories">
                                    </div>
                                    <div class="carousel-item" onclick="changeProductImage('<?php echo esc_url($placeholder_url); ?>')">
                                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Packaging">
                                    </div>
                                </div>
                                <button class="carousel-nav carousel-prev" onclick="moveCarousel(-1)">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="carousel-nav carousel-next" onclick="moveCarousel(1)">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Product Details -->
                    <div class="col-lg-6">
                        <div class="product-details">
                            <!-- Review and Count -->
                            <div class="rating-section">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <div class="review-count">245 Reviews</div>
                            </div>

                            <!-- Product Title -->
                            <h1 class="product-title">Premium Wireless Noise-Canceling Headphones</h1>

                            <!-- Product Price and Stock on Same Line -->
                            <div class="price-stock-row">
                                <div class="price-section">
                                    <span class="current-price">$189.99</span>
                                    <span class="original-price">$299.99</span>
                                </div>
                                <div class="stock-info">
                                    <div class="stock-status">
                                        <i class="bi bi-check-circle-fill"></i>
                                        In Stock
                                    </div>
                                    <div class="left-in-stock">Only 5 items left</div>
                                </div>
                            </div>

                            <!-- Short Description -->
                            <div class="short-description">
                                Experience premium sound quality with our state-of-the-art wireless headphones. Featuring advanced noise-canceling technology, 30-hour battery life, and ergonomic design for all-day comfort.
                            </div>

                            <!-- Color and Size Variations -->
                            <div class="product-variations">
                                <!-- Color Variation -->
                                <div class="variation-group">
                                    <label class="variation-label">Color:</label>
                                    <div class="variation-options">
                                        <div class="color-swatch selected" style="background-color: #1a1a1a;" onclick="selectColor(this)" title="Midnight Black"></div>
                                        <div class="color-swatch" style="background-color: #f8f8f8;" onclick="selectColor(this)" title="Pearl White"></div>
                                        <div class="color-swatch" style="background-color: #e0a96d;" onclick="selectColor(this)" title="Rose Gold"></div>
                                        <div class="color-swatch" style="background-color: #708090;" onclick="selectColor(this)" title="Space Gray"></div>
                                        <div class="color-swatch" style="background-color: #4169e1;" onclick="selectColor(this)" title="Royal Blue"></div>
                                    </div>
                                </div>

                                <!-- Size Variation -->
                                <div class="variation-group">
                                    <label class="variation-label">Size:</label>
                                    <div class="variation-options">
                                        <div class="variation-option" onclick="selectSize(this)">Standard</div>
                                        <div class="variation-option selected" onclick="selectSize(this)">Large</div>
                                        <div class="variation-option" onclick="selectSize(this)">Extra Large</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity and Add to Cart Row -->
                            <div class="quantity-action-row">
                                <div class="quantity-section-inline">
                                    <div class="quantity-selector">
                                        <button onclick="decreaseQuantity()">-</button>
                                        <input type="text" id="quantity" value="1" readonly>
                                        <button onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>
                                <button class="btn-add-to-cart" onclick="addToCart()">
                                    <i class="bi bi-cart-plus"></i>
                                    Add to Cart
                                </button>
                            </div>

                            <!-- Buy Now Button Full Width -->
                            <button class="btn-buy-now" onclick="buyNow()">
                                <i class="bi bi-lightning-charge-fill"></i>
                                Buy Now
                            </button>

                            <!-- Wishlist and Compare Buttons -->
                            <div class="wishlist-compare">
                                <button class="btn-wishlist" onclick="addToWishlist()">
                                    <i class="bi bi-heart"></i>
                                    Add to Wishlist
                                </button>
                                <button class="btn-compare" onclick="addToCompare()">
                                    <i class="bi bi-arrow-left-right"></i>
                                    Add to Compare
                                </button>
                            </div>

                            <!-- Product Information -->
                            <div class="product-info">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-title-row">
                                            <i class="bi bi-truck"></i>
                                            <div class="info-content">
                                                <h6>Delivery</h6>
                                            </div>
                                        </div>
                                        <p>Free shipping on orders over $50</p>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-title-row">
                                            <i class="bi bi-upc-scan"></i>
                                            <div class="info-content">
                                                <h6>SKU</h6>
                                            </div>
                                        </div>
                                        <p>WH-NC-2023-01</p>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-title-row">
                                            <i class="bi bi-shield-check"></i>
                                            <div class="info-content">
                                                <h6>Warranty</h6>
                                            </div>
                                        </div>
                                        <p>1 Year Manufacturer Warranty</p>
                                    </div>
                                </div>

                                <!-- Payment Options -->
                                <div class="payment-options">
                                    <h6>Payment Options:</h6>
                                    <div class="payment-methods">
                                        <div class="payment-method">VISA</div>
                                        <div class="payment-method">MC</div>
                                        <div class="payment-method">AMEX</div>
                                        <div class="payment-method">PayPal</div>
                                        <div class="payment-method">GPay</div>
                                        <div class="payment-method">Apple Pay</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Share Outside Box -->
                            <div class="social-share-section">
                                <h6>Share:</h6>
                                <div class="social-icons">
                                    <div class="social-icon facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </div>
                                    <div class="social-icon twitter">
                                        <i class="fab fa-twitter"></i>
                                    </div>
                                    <div class="social-icon instagram">
                                        <i class="fab fa-instagram"></i>
                                    </div>
                                    <div class="social-icon pinterest">
                                        <i class="fab fa-pinterest-p"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width Tabs Section -->
            <div class="tabs-section">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Specifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (245)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <h4>Product Description</h4>
                        <p>Experience premium sound quality with our state-of-the-art wireless headphones. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear audio with deep bass and crisp highs that bring your music to life.</p>

                        <p>Featuring advanced noise-canceling technology, these headphones create an immersive listening experience by blocking out unwanted ambient noise. Whether you're commuting, working in a busy office, or relaxing at home, you'll enjoy your music without distractions.</p>

                        <p>The ergonomic design ensures all-day comfort with soft ear cushions and an adjustable headband. With up to 30 hours of battery life on a single charge, you can enjoy your favorite playlists, podcasts, and calls throughout the day without interruption.</p>

                        <h5>Key Features:</h5>
                        <ul>
                            <li>Active Noise Cancellation (ANC) with adjustable levels</li>
                            <li>30-hour battery life with quick charge support</li>
                            <li>Bluetooth 5.0 connectivity for stable connection</li>
                            <li>Intuitive touch gesture controls</li>
                            <li>Built-in microphone with voice assistant support</li>
                            <li>Foldable design with premium carrying case</li>
                            <li>Quick charge: 5 minutes = 2 hours of playback</li>
                            <li>Compatible with iOS, Android, and all Bluetooth devices</li>
                        </ul>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        <h4>Technical Specifications</h4>
                        <table class="table table-striped">
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
                                    <td>2 hours (full charge), 15 minutes (80% charge)</td>
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
                                <tr>
                                    <td><strong>Material</strong></td>
                                    <td>Premium ABS with aluminum accents</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <h4>Customer Reviews</h4>

                        <!-- Review Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h2 class="display-4">4.6</h2>
                                    <div class="stars mb-2">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <p>Based on 245 reviews</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <p>Customers love these headphones for their exceptional sound quality, comfort, and effective noise cancellation. Perfect for travel, work, and everyday use.</p>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="review-item mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="fw-bold">Sarah Johnson</div>
                                <div class="text-muted">October 15, 2023</div>
                            </div>
                            <div class="stars mb-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p>These headphones are absolutely amazing! The sound quality is exceptional, with rich bass and crisp highs. The noise cancellation is so effective that I can barely hear anything when it's turned on maximum. The battery life is incredible - I've been using them for over a week with regular use and still haven't needed to recharge. Highly recommend!</p>
                        </div>

                        <div class="review-item mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="fw-bold">Michael Chen</div>
                                <div class="text-muted">October 10, 2023</div>
                            </div>
                            <div class="stars mb-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <p>Great headphones overall. The sound quality is excellent and the build feels premium. The touch controls take some getting used to, but work well once you're familiar with them. My only complaint is that they can get a bit warm during extended use, but it's not a dealbreaker. Comfortable and worth the price.</p>
                        </div>

                        <button class="btn btn-primary">Load More Reviews</button>
                    </div>

                    <!-- Shipping & Returns Tab -->
                    <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        <h4>Shipping & Returns</h4>
                        <p>We offer several shipping options to meet your needs:</p>
                        <ul>
                            <li><strong>Standard Shipping (3-5 business days):</strong> Free on orders over $50, otherwise $5.99</li>
                            <li><strong>Express Shipping (2-3 business days):</strong> $12.99</li>
                            <li><strong>Overnight Shipping (1 business day):</strong> $24.99</li>
                            <li><strong>International Shipping:</strong> Rates vary by destination</li>
                        </ul>

                        <h5>Returns Policy</h5>
                        <p>We offer a 30-day return policy on all items. To be eligible for a return:</p>
                        <ul>
                            <li>The item must be unused and in the same condition that you received it</li>
                            <li>The item must be in the original packaging</li>
                            <li>You must provide the receipt or proof of purchase</li>
                        </ul>

                        <h5>Warranty</h5>
                        <p>All our products come with a 1-year manufacturer warranty that covers any defects in materials or workmanship. Extended warranty options are available at checkout.</p>
                    </div>
                </div>
            </div>

            <!-- Full Width Related Products -->
            <div class="related-products">
                <h2 class="section-title">Related Products</h2>
                <div class="product-grid">
                    <!-- Product 1 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Wireless Earbuds">
                            <div class="product-badge">-25%</div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">Audio</div>
                            <div class="product-name">Premium Wireless Earbuds Pro</div>
                            <div class="product-price">
                                <span class="current-price-small">$89.99</span>
                                <span class="original-price-small">$119.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="review-count-small">(142)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Bluetooth Speaker">
                            <div class="product-badge">New</div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">Audio</div>
                            <div class="product-name">Portable Bluetooth Speaker</div>
                            <div class="product-price">
                                <span class="current-price-small">$59.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-count-small">(87)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Studio Headphones">
                            <div class="product-badge">-15%</div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">Audio</div>
                            <div class="product-name">Professional Studio Headphones</div>
                            <div class="product-price">
                                <span class="current-price-small">$149.99</span>
                                <span class="original-price-small">$174.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <span class="review-count-small">(63)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Sports Earbuds">
                            <div class="product-badge">Hot</div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">Audio</div>
                            <div class="product-name">Sports Wireless Earbuds</div>
                            <div class="product-price">
                                <span class="current-price-small">$69.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="review-count-small">(115)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 5 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphone Case">
                        </div>
                        <div class="product-info">
                            <div class="product-category">Accessories</div>
                            <div class="product-name">Premium Headphone Storage Case</div>
                            <div class="product-price">
                                <span class="current-price-small">$24.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-count-small">(42)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 6 -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Charging Station">
                        </div>
                        <div class="product-info">
                            <div class="product-category">Accessories</div>
                            <div class="product-name">Wireless Charging Station</div>
                            <div class="product-price">
                                <span class="current-price-small">$39.99</span>
                            </div>
                            <div class="product-rating-small">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="review-count-small">(78)</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn-add-to-cart-small">Add to Cart</button>
                                <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCarouselPosition = 0;
        const totalCarouselItems = 6;
        const itemsVisible = 3;

        // Tab functionality (replacing Bootstrap tabs)
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all tabs and panes
                    tabLinks.forEach(function(l) {
                        l.classList.remove('active');
                        l.setAttribute('aria-selected', 'false');
                    });
                    tabPanes.forEach(function(p) {
                        p.classList.remove('active', 'show');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    // Show corresponding pane
                    const targetId = this.getAttribute('data-bs-target');
                    const targetPane = document.querySelector(targetId);
                    if (targetPane) {
                        targetPane.classList.add('active', 'show');
                    }
                });
            });
        });

        // Image carousel functionality
        function moveCarousel(direction) {
            const carousel = document.getElementById('imageCarousel');
            const maxPosition = Math.max(0, totalCarouselItems - itemsVisible);

            currentCarouselPosition += direction;

            if (currentCarouselPosition < 0) {
                currentCarouselPosition = maxPosition;
            } else if (currentCarouselPosition > maxPosition) {
                currentCarouselPosition = 0;
            }

            const translateX = -currentCarouselPosition * (100 / itemsVisible);
            carousel.style.transform = `translateX(${translateX}%)`;

            // Update active states
            updateCarouselActiveStates();
        }

        function updateCarouselActiveStates() {
            const carouselItems = document.querySelectorAll('.carousel-item');
            carouselItems.forEach((item, index) => {
                item.classList.toggle('active', index === currentCarouselPosition);
            });
        }

        // Change product image when carousel item is clicked
        function changeProductImage(imageName) {
            const mainImage = document.getElementById('mainProductImage');
            mainImage.src = imageName;
        }

        // Color selection
        function selectColor(element) {
            document.querySelectorAll('.color-swatch').forEach(swatch => {
                swatch.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        // Size selection
        function selectSize(element) {
            document.querySelectorAll('.variation-option').forEach(option => {
                option.classList.remove('selected');
            });
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

        // Button functions
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            alert(`Added ${quantity} item(s) to cart!`);
        }

        function buyNow() {
            const quantity = document.getElementById('quantity').value;
            alert(`Proceeding to checkout with ${quantity} item(s)!`);
        }

        function addToWishlist() {
            alert('Added to wishlist!');
        }

        function addToCompare() {
            alert('Added to compare list!');
        }

        function zoomImage() {
            alert('Image zoom functionality would be implemented here');
        }
    </script>

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
			'shopglut-template7-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template7/template7-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template7-frontend', 'shopglut_frontend_vars', array(
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
	 * (All styling is handled in template7Style.php)
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
			if (isset($settings['shopg_singleproduct_settings_template7']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template7']['single-product-settings']);
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