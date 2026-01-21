<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro5;

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
 <div class="shopglut-single-templatePro5">
        <!-- Breadcrumb Section -->
        <div class="breadcrumb-section">
            <div class="container-fluid px-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Electronics</a></li>
                        <li class="breadcrumb-item"><a href="#">Audio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Headphones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Page -->
        <div class="product-page">
            <div class="container-fluid px-4">
                <div class="product-container">
                    <div class="row g-4">
                        <!-- Left Section -->
                        <div class="col-lg-3 col-md-4">
                            <div class="left-section">
                                <!-- Product Title -->
                                <h1>Premium Wireless Noise-Canceling Headphones</h1>

                                <!-- Rating Section -->
                                <div class="rating-section">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <span class="rating-value">4.5</span>
                                    <span class="review-count">(245 Reviews)</span>
                                </div>

                                <!-- Price Section -->
                                <div class="price-section">
                                    <span class="current-price">$189.99</span>
                                    <span class="original-price">$299.99</span>
                                    <span class="discount-badge">-37%</span>
                                </div>

                                <!-- Availability and Stock Information -->
                                <div class="stock-info in-stock">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>In Stock</strong>
                                        <p class="mb-0">Only 5 items left</p>
                                    </div>
                                </div>

                                <div class="availability-info">
                                    <i class="bi bi-truck"></i>
                                    <span>Free shipping on orders over $50</span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Section -->
                        <div class="col-lg-5 col-md-8">
                            <div class="middle-section">
                                <!-- Product Image Slider -->
                                <div class="product-image-slider">
                                    <img id="mainProductImage" src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Premium Wireless Headphones">
                                </div>

                                <!-- Image Thumbnails -->
                                <div class="image-thumbnails">
                                    <div class="thumbnail active" onclick="changeProductImage('headphones1')">
                                        <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="View 1">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('headphones2')">
                                        <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="View 2">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('headphones3')">
                                        <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="View 3">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('headphones4')">
                                        <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="View 4">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('headphones5')">
                                        <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="View 5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="col-lg-4">
                            <div class="right-section">
                                <!-- Category and Tag -->
                                <div class="product-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Category:</span>
                                        <span class="meta-value"><a href="#">Electronics > Audio > Headphones</a></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Tags:</span>
                                        <span class="meta-value">
                                            <a href="#">Wireless</a>,
                                            <a href="#">Noise-Canceling</a>,
                                            <a href="#">Premium</a>
                                        </span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">SKU:</span>
                                        <span class="meta-value">WH-NC-2023-01</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Brand:</span>
                                        <span class="meta-value"><a href="#">AudioTech Pro</a></span>
                                    </div>
                                </div>

                                <!-- Quantity and Add to Cart -->
                                <div class="quantity-section">
                                    <div class="quantity-selector">
                                        <button onclick="decreaseQuantity()">-</button>
                                        <input type="text" id="quantity" value="1" readonly>
                                        <button onclick="increaseQuantity()">+</button>
                                    </div>
                                    <button class="btn-add-to-cart" onclick="addToCart()">
                                        <i class="bi bi-cart-plus"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <!-- Buy Now Button -->
                                <button class="btn-buy-now" onclick="buyNow()">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    Buy Now
                                </button>

                                <!-- Delivery and Return -->
                                <div class="delivery-return">
                                    <div class="delivery-info">
                                        <h5><i class="bi bi-truck"></i> Delivery</h5>
                                        <p>Free Delivery on orders over $50</p>
                                        <p>Standard: 3-5 business days</p>
                                    </div>
                                    <div class="return-info">
                                        <h5><i class="bi bi-arrow-return-left"></i> Return</h5>
                                        <p>30 days return policy</p>
                                        <p>Money back guarantee</p>
                                    </div>
                                </div>

                                <!-- Ask a Question Button -->
                                <button class="btn-ask-question" onclick="askQuestion()">
                                    <i class="bi bi-question-circle"></i> Ask a Question
                                </button>

                                <!-- Wishlist and Compare -->
                                <div class="wishlist-compare">
                                    <button class="btn-wishlist" onclick="addToWishlist()">
                                        <i class="bi bi-heart"></i> Wishlist
                                    </button>
                                    <button class="btn-compare" onclick="addToCompare()">
                                        <i class="bi bi-arrow-left-right"></i> Compare
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment and Share Section -->
                <div class="payment-share-section">
                    <div class="container-fluid px-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="payment-options">
                                    <h5>Payment Options:</h5>
                                    <div class="payment-methods">
                                        <div class="payment-method">VISA</div>
                                        <div class="payment-method">MC</div>
                                        <div class="payment-method">AMEX</div>
                                        <div class="payment-method">PP</div>
                                        <div class="payment-method">GPay</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="share-options">
                                    <h5>Share:</h5>
                                    <div class="social-share">
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

                <!-- Tabs Section -->
                <div class="tabs-section container-fluid px-4">
                    <div class="tabs-container">
                        <!-- Vertical Tabs Navigation -->
                        <div class="tab-nav">
                            <ul class="nav nav-pills flex-column" id="productTabs" role="tablist">
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
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="productTabContent">
                            <!-- Description Tab -->
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                <h4>Product Description</h4>
                                <p>Experience premium sound quality with our state-of-the-art wireless headphones. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear audio with deep bass and crisp highs.</p>

                                <p>Featuring advanced noise-canceling technology, these headphones create an immersive listening experience by blocking out unwanted ambient noise. Whether you're commuting, working, or relaxing at home, you'll enjoy your music without distractions.</p>

                                <p>The ergonomic design ensures all-day comfort with soft ear cushions and an adjustable headband. With up to 30 hours of battery life on a single charge, you can enjoy your favorite playlists, podcasts, and calls throughout the day without interruption.</p>

                                <p>Intuitive touch controls make it easy to manage your music, adjust volume, and take calls without reaching for your device. The built-in microphone with noise reduction ensures clear voice quality during phone calls and virtual meetings.</p>

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
                            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                                <h4>Technical Specifications</h4>
                                <table class="specifications-table">
                                    <tr>
                                        <td>Driver Size</td>
                                        <td>40mm Dynamic Driver</td>
                                    </tr>
                                    <tr>
                                        <td>Frequency Response</td>
                                        <td>20Hz - 20kHz</td>
                                    </tr>
                                    <tr>
                                        <td>Impedance</td>
                                        <td>32 Ohms</td>
                                    </tr>
                                    <tr>
                                        <td>Sensitivity</td>
                                        <td>105dB</td>
                                    </tr>
                                    <tr>
                                        <td>Battery Life</td>
                                        <td>30 hours (ANC off), 25 hours (ANC on)</td>
                                    </tr>
                                    <tr>
                                        <td>Charging Time</td>
                                        <td>2 hours (full charge)</td>
                                    </tr>
                                    <tr>
                                        <td>Bluetooth Version</td>
                                        <td>5.0</td>
                                    </tr>
                                    <tr>
                                        <td>Wireless Range</td>
                                        <td>10 meters (33 feet)</td>
                                    </tr>
                                    <tr>
                                        <td>Weight</td>
                                        <td>250g</td>
                                    </tr>
                                    <tr>
                                        <td>Material</td>
                                        <td>Premium ABS with aluminum accents</td>
                                    </tr>
                                    <tr>
                                        <td>Color Options</td>
                                        <td>Midnight Black, Pearl White, Rose Gold</td>
                                    </tr>
                                    <tr>
                                        <td>What's in the Box</td>
                                        <td>Headphones, Carrying Case, USB-C Cable, 3.5mm Audio Cable, Airplane Adapter</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Reviews Tab -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <h4>Customer Reviews</h4>

                                <!-- Review 1 -->
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-author">Sarah Johnson</div>
                                        <div class="review-date">October 15, 2023</div>
                                    </div>
                                    <div class="review-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="review-text">
                                        <p>These headphones are absolutely amazing! The sound quality is exceptional, with rich bass and crisp highs. The noise cancellation is so effective that I can barely hear anything when it's turned on maximum. The battery life is incredible - I've been using them for over a week with regular use and still haven't needed to recharge. Highly recommend!</p>
                                    </div>
                                </div>

                                <!-- Review 2 -->
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-author">Michael Chen</div>
                                        <div class="review-date">October 10, 2023</div>
                                    </div>
                                    <div class="review-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <div class="review-text">
                                        <p>Great headphones overall. The sound quality is excellent and the build feels premium. The touch controls take some getting used to, but work well once you're familiar with them. My only complaint is that they can get a bit warm during extended use, but it's not a dealbreaker. Comfortable and worth the price.</p>
                                    </div>
                                </div>

                                <!-- Review 3 -->
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-author">Emily Rodriguez</div>
                                        <div class="review-date">September 28, 2023</div>
                                    </div>
                                    <div class="review-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="review-text">
                                        <p>I've been using these headphones for my daily commute and they've been a game-changer! The noise cancellation blocks out all the subway noise, and the battery easily lasts my entire week. The quick charge feature has saved me multiple times when I forgot to charge them overnight. Comfortable enough to wear all day at my desk job too.</p>
                                    </div>
                                </div>

                                <button class="btn btn-primary mt-3">Load More Reviews</button>
                            </div>

                            <!-- Shipping & Returns Tab -->
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                <h4>Shipping & Delivery</h4>
                                <p>We offer several shipping options to meet your needs:</p>
                                <ul>
                                    <li><strong>Standard Shipping (3-5 business days):</strong> Free on orders over $50, otherwise $5.99</li>
                                    <li><strong>Express Shipping (2-3 business days):</strong> $12.99</li>
                                    <li><strong>Overnight Shipping (1 business day):</strong> $24.99</li>
                                    <li><strong>International Shipping:</strong> Rates vary by destination</li>
                                </ul>

                                <h4 class="mt-4">Returns & Refunds</h4>
                                <p>We offer a 30-day return policy on all items. To be eligible for a return:</p>
                                <ul>
                                    <li>The item must be unused and in the same condition that you received it</li>
                                    <li>The item must be in the original packaging</li>
                                    <li>You must provide the receipt or proof of purchase</li>
                                </ul>

                                <h4 class="mt-4">Warranty</h4>
                                <p>All our products come with a 1-year manufacturer warranty that covers any defects in materials or workmanship. Extended warranty options are available at checkout.</p>

                                <h4 class="mt-4">Customer Support</h4>
                                <p>Have questions about your order or need assistance with returns? Our customer support team is available:</p>
                                <ul>
                                    <li>Monday - Friday: 9 AM - 6 PM EST</li>
                                    <li>Saturday: 10 AM - 4 PM EST</li>
                                    <li>Sunday: Closed</li>
                                </ul>
                                <p>Email: support@example.com | Phone: 1-800-123-4567</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products Section -->
                <div class="related-products container-fluid px-4">
                    <h2 class="section-title">Related Products</h2>
                    <div class="product-grid">
                        <!-- Product 1 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Wireless Earbuds">
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
                                <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Bluetooth Speaker">
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
                                <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Studio Headphones">
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
                                <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Sports Earbuds">
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
                                <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>" alt="Headphone Case">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Change product image when thumbnail is clicked
        function changeProductImage(imageName) {
            const mainImage = document.getElementById('mainProductImage');
            mainImage.src = "<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/templatePro5-demo-image.png'); ?>";

            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumbnail => {
                thumbnail.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
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

        // Buy now function
        function buyNow() {
            const quantity = document.getElementById('quantity').value;
            alert(`Proceeding to checkout with ${quantity} item(s)!`);
        }

        // Ask question function
        function askQuestion() {
            alert('Question form would open here');
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
