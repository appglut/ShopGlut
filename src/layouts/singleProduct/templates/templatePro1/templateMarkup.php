<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro1;

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

		 <div class="shopglut-single-templatePro1">
        <div class="container">
            <div class="product-page">
            <!-- Left Side - Image Gallery -->
            <div class="product-gallery">
                <!-- Breadcrumb -->
                <nav class="breadcrumb">
                    <a href="#">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9,22 9,12 15,12 15,22"></polyline>
                        </svg>
                        Home
                    </a>
                    <span>•</span>
                    <a href="#">Shop</a>
                    <span>•</span>
                    <a href="#">Electronics</a>
                    <span>•</span>
                    <span>Product</span>
                </nav>

                <!-- Gallery Container -->
                <div class="gallery-container">
                    <!-- Thumbnails -->
                    <div class="thumbnail-list">
                        <div class="thumbnail active">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product thumbnail 1">
                        </div>
                        <div class="thumbnail">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product thumbnail 2">
                        </div>
                        <div class="thumbnail">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product thumbnail 3">
                        </div>
                        <div class="thumbnail">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product thumbnail 4">
                        </div>
                    </div>

                    <!-- Main Image -->
                    <div class="main-image">
                        <img id="main-product-image" src="<?php echo esc_url($placeholder_url); ?>" alt="Product main image">
                    </div>
                </div>
            </div>

            <!-- Right Side - Product Details -->
            <div class="product-details">
                <!-- Product Category -->
                <div class="product-category">
                    <a href="#">Electronics</a> / <a href="#">Smartphones</a>
                </div>

                <!-- Product Title -->
                <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>

                <!-- Stock and Reviews -->
                <div class="stock-reviews">
                    <div class="stock-info">
                        <i class="fas fa-check-circle"></i>
                        <span class="in-stock">15 in stock</span>
                    </div>
                    <div class="reviews">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="reviews-count">(4.5) 24 reviews</span>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="product-description">
                    Experience premium sound quality with our latest wireless headphones featuring industry-leading active noise cancellation technology. Perfect for music lovers, travelers, and professionals who demand the best audio experience.
                </div>

                <!-- Product Price -->
                <div class="product-price">
                    <span class="current-price">$299.99</span>
                    <span class="original-price">$399.99</span>
                </div>

                <!-- Product Variations -->
                <div class="product-variations">
                    <!-- Color Variation -->
                    <div class="variation-group">
                        <label class="variation-label">Color:</label>
                        <div class="swatches">
                            <div class="color-swatch selected" style="background-color: #1a1a1a;" data-color="Black"></div>
                            <div class="color-swatch" style="background-color: #ffffff; border: 2px solid #ddd;" data-color="White"></div>
                            <div class="color-swatch" style="background-color: #0073aa;" data-color="Blue"></div>
                            <div class="color-swatch" style="background-color: #dc3545;" data-color="Red"></div>
                        </div>
                    </div>

                    <!-- Size Variation -->
                    <div class="variation-group">
                        <label class="variation-label">Size:</label>
                        <div class="swatches">
                            <div class="swatch selected" data-size="Standard">Standard</div>
                            <div class="swatch" data-size="Large">Large</div>
                            <div class="swatch" data-size="XL">XL</div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Section -->
                <div class="add-to-cart-section">
                    <div class="quantity-input">
                        <button onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="15">
                        <button onclick="increaseQuantity()">+</button>
                    </div>
                    <button class="add-to-cart" onclick="addToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>

                <!-- Buy Now Button -->
                <button class="buy-now" onclick="buyNow()">
                    <i class="fas fa-bolt"></i>
                    Buy Now
                </button>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="action-button" onclick="addToWishlist()">
                        <i class="far fa-heart"></i>
                        Wishlist
                    </button>
                    <button class="action-button" onclick="addToCompare()">
                        <i class="fas fa-exchange-alt"></i>
                        Compare
                    </button>
                    <button class="action-button" onclick="askQuestion()">
                        <i class="far fa-question-circle"></i>
                        Ask Question
                    </button>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs">
                <div class="tab-navigation">
                    <button class="tab-button active" onclick="switchTab('description')">Description</button>
                    <button class="tab-button" onclick="switchTab('specifications')">Specifications</button>
                    <button class="tab-button" onclick="switchTab('reviews')">Reviews (128)</button>
                    <button class="tab-button" onclick="switchTab('shipping')">Shipping & Returns</button>
                </div>

                <div id="description" class="tab-content active">
                    <div class="description-container">
                        <!-- Left Column - Product Description -->
                        <div class="description-content">
                            <h3>Product Description</h3>
                            <div class="description-text">
                                <p>Our premium wireless headphones redefine your audio experience with cutting-edge technology and superior comfort. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear sound with deep bass and crisp highs.</p>

                                <h4>Key Features:</h4>
                                <div class="features-grid">
                                    <div class="feature-item">
                                        <i class="fas fa-volume-mute"></i>
                                        <span>Active Noise Cancellation</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-battery-full"></i>
                                        <span>40-Hour Battery Life</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-headphones-alt"></i>
                                        <span>Premium Memory Foam</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-bluetooth"></i>
                                        <span>Bluetooth 5.0</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-microphone"></i>
                                        <span>Voice Assistant Support</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-compress-arrows-alt"></i>
                                        <span>Foldable Design</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Product Highlights -->
                        <div class="description-highlights">
                            <div class="highlight-box">
                                <h4>What's in the Box?</h4>
                                <ul class="box-contents">
                                    <li><i class="fas fa-check"></i> Premium Wireless Headphones</li>
                                    <li><i class="fas fa-check"></i> USB-C Charging Cable</li>
                                    <li><i class="fas fa-check"></i> 3.5mm Audio Cable</li>
                                    <li><i class="fas fa-check"></i> Carrying Case</li>
                                    <li><i class="fas fa-check"></i> Airplane Adapter</li>
                                    <li><i class="fas fa-check"></i> Quick Start Guide</li>
                                </ul>
                            </div>

                            <div class="highlight-box">
                                <h4>Warranty & Support</h4>
                                <div class="warranty-info">
                                    <div class="warranty-item">
                                        <strong>2 Year Warranty</strong>
                                        <p>Full manufacturer warranty covering defects</p>
                                    </div>
                                    <div class="warranty-item">
                                        <strong>30-Day Returns</strong>
                                        <p>Hassle-free returns if not satisfied</p>
                                    </div>
                                    <div class="warranty-item">
                                        <strong>24/7 Support</strong>
                                        <p>Dedicated customer service team</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="specifications" class="tab-content">
                    <div class="specifications-container">
                        <!-- Left Column - Audio Specifications -->
                        <div class="spec-section">
                            <h3 class="spec-title">Audio Specifications</h3>
                            <div class="spec-group">
                                <div class="spec-item">
                                    <label>Driver Size</label>
                                    <span>40mm Dynamic</span>
                                </div>
                                <div class="spec-item">
                                    <label>Frequency Response</label>
                                    <span>20Hz - 20kHz</span>
                                </div>
                                <div class="spec-item">
                                    <label>Impedance</label>
                                    <span>32 Ohms</span>
                                </div>
                                <div class="spec-item">
                                    <label>Sensitivity</label>
                                    <span>105 dB</span>
                                </div>
                                <div class="spec-item">
                                    <label>Total Harmonic Distortion</label>
                                    <span>< 0.1%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Column - Battery & Connectivity -->
                        <div class="spec-section">
                            <h3 class="spec-title">Battery & Connectivity</h3>
                            <div class="spec-group">
                                <div class="spec-item">
                                    <label>Battery Life</label>
                                    <span>40 hours</span>
                                </div>
                                <div class="spec-item">
                                    <label>Charging Time</label>
                                    <span>2 hours</span>
                                </div>
                                <div class="spec-item">
                                    <label>Quick Charge</label>
                                    <span>15 min = 3 hours</span>
                                </div>
                                <div class="spec-item">
                                    <label>Bluetooth Version</label>
                                    <span>5.0</span>
                                </div>
                                <div class="spec-item">
                                    <label>Range</label>
                                    <span>10 meters (33 ft)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Physical Specifications -->
                        <div class="spec-section">
                            <h3 class="spec-title">Physical Specifications</h3>
                            <div class="spec-group">
                                <div class="spec-item">
                                    <label>Weight</label>
                                    <span>250g</span>
                                </div>
                                <div class="spec-item">
                                    <label>Material</label>
                                    <span>Premium ABS + Aluminum</span>
                                </div>
                                <div class="spec-item">
                                    <label>Cable Length</label>
                                    <span>1.2m</span>
                                </div>
                                <div class="spec-item">
                                    <label>Plug Type</label>
                                    <span>3.5mm + USB-C</span>
                                </div>
                                <div class="spec-item">
                                    <label>Color Options</label>
                                    <span>Black, White, Blue, Red</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="reviews" class="tab-content">
                    <div class="reviews-container">
                        <!-- Left Column - Review Submission Form -->
                        <div class="review-form-container">
                            <h3>Write a Review</h3>
                            <form class="review-form" onsubmit="submitReview(event)">
                                <div class="form-group">
                                    <label for="review-rating">Rating *</label>
                                    <div class="star-rating-input">
                                        <i class="far fa-star" data-rating="1"></i>
                                        <i class="far fa-star" data-rating="2"></i>
                                        <i class="far fa-star" data-rating="3"></i>
                                        <i class="far fa-star" data-rating="4"></i>
                                        <i class="far fa-star" data-rating="5"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="review-name">Name *</label>
                                    <input type="text" id="review-name" name="review-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="review-email">Email *</label>
                                    <input type="email" id="review-email" name="review-email" required>
                                </div>
                                <div class="form-group">
                                    <label for="review-subject">Subject</label>
                                    <input type="text" id="review-subject" name="review-subject">
                                </div>
                                <div class="form-group">
                                    <label for="review-comment">Review *</label>
                                    <textarea id="review-comment" name="review-comment" rows="6" required></textarea>
                                </div>
                                <button type="submit" class="submit-review-btn">Submit Review</button>
                            </form>
                        </div>

                        <!-- Right Column - Review Statistics -->
                        <div class="review-statistics">
                            <h3>Customer Reviews</h3>

                            <!-- Average Rating and Count -->
                            <div class="average-rating">
                                <div class="rating-number">4.5</div>
                                <div class="rating-stars">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-count">Based on 128 reviews</span>
                                </div>
                            </div>

                            <!-- Star Rating Breakdown -->
                            <div class="rating-breakdown">
                                <div class="rating-bar">
                                    <span class="star-label">5 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 65%"></div>
                                    </div>
                                    <span class="bar-count">65%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-label">4 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 20%"></div>
                                    </div>
                                    <span class="bar-count">20%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-label">3 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 10%"></div>
                                    </div>
                                    <span class="bar-count">10%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-label">2 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 3%"></div>
                                    </div>
                                    <span class="bar-count">3%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-label">1 star</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 2%"></div>
                                    </div>
                                    <span class="bar-count">2%</span>
                                </div>
                            </div>

                            <!-- Customer Reviews List -->
                            <div class="customer-reviews">
                                <h4>Recent Reviews</h4>
                                <div class="review-item">
                                    <div class="review-header">
                                        <strong>John D.</strong>
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <p class="review-comment">Amazing sound quality! The noise cancellation is incredible, perfect for my daily commute.</p>
                                </div>
                                <div class="review-item">
                                    <div class="review-header">
                                        <strong>Sarah M.</strong>
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                    <p class="review-comment">Great headphones overall. Comfortable for long listening sessions. Battery life is as advertised.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="shipping" class="tab-content">
                    <div class="shipping-container">
                        <!-- Left Column - Shipping Information -->
                        <div class="shipping-section">
                            <div class="shipping-header">
                                <i class="fas fa-shipping-fast"></i>
                                <h3>Shipping Information</h3>
                            </div>

                            <div class="shipping-options">
                                <div class="shipping-option">
                                    <div class="option-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="option-details">
                                        <strong>Standard Shipping</strong>
                                        <p>3-5 business days</p>
                                        <span class="price">FREE on orders over $50</span>
                                    </div>
                                </div>

                                <div class="shipping-option">
                                    <div class="option-icon">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div class="option-details">
                                        <strong>Express Shipping</strong>
                                        <p>1-2 business days</p>
                                        <span class="price">$9.99</span>
                                    </div>
                                </div>

                                <div class="shipping-option">
                                    <div class="option-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="option-details">
                                        <strong>Order Processing</strong>
                                        <p>1-2 business days</p>
                                        <span class="note">Before shipping begins</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Return Policy -->
                        <div class="shipping-section">
                            <div class="shipping-header">
                                <i class="fas fa-undo"></i>
                                <h3>Return Policy</h3>
                            </div>

                            <div class="return-features">
                                <div class="return-feature">
                                    <div class="feature-number">30</div>
                                    <div class="feature-text">
                                        <strong>Days</strong>
                                        <p>Hassle-free returns from delivery date</p>
                                    </div>
                                </div>

                                <div class="return-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="feature-text">
                                        <strong>Original Condition</strong>
                                        <p>Product must be unused with all tags</p>
                                    </div>
                                </div>

                                <div class="return-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="feature-text">
                                        <strong>Free Returns</strong>
                                        <p>For defective items, always covered</p>
                                    </div>
                                </div>

                                <div class="return-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="feature-text">
                                        <strong>Quick Refund</strong>
                                        <p>Processed within 5-7 business days</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-support">
                                <p>Need help with returns?</p>
                                <button class="support-btn" onclick="contactSupport()">
                                    <i class="fas fa-headset"></i>
                                    Contact Support
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="related-products">
                <h2>Related Products</h2>
                <div class="related-products-grid">
                    <div class="related-product">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 1">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Wireless Earbuds Pro</h3>
                            <p class="related-product-price">$149.99</p>
                            <button class="quick-add-btn" onclick="quickAddToCart('Wireless Earbuds Pro', 149.99)">Quick Add</button>
                        </div>
                    </div>
                    <div class="related-product">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 2">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Portable Speaker</h3>
                            <p class="related-product-price">$89.99</p>
                            <button class="quick-add-btn" onclick="quickAddToCart('Portable Speaker', 89.99)">Quick Add</button>
                        </div>
                    </div>
                    <div class="related-product">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 3">
                        <div class="related-product-info">
                            <h3 class="related-product-title">USB-C Headphone Adapter</h3>
                            <p class="related-product-price">$19.99</p>
                            <button class="quick-add-btn" onclick="quickAddToCart('USB-C Headphone Adapter', 19.99)">Quick Add</button>
                        </div>
                    </div>
                    <div class="related-product">
                        <img src="<?php echo esc_url($placeholder_url); ?>" alt="Related product 4">
                        <div class="related-product-info">
                            <h3 class="related-product-title">Headphone Stand</h3>
                            <p class="related-product-price">$29.99</p>
                            <button class="quick-add-btn" onclick="quickAddToCart('Headphone Stand', 29.99)">Quick Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Thumbnail image switcher
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.getElementById('main-product-image');

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                // Add active class to clicked thumbnail
                thumbnail.classList.add('active');

                // Change main image
                mainImage.src = '<?php echo esc_url($placeholder_url); ?>';
            });
        });

        // Quantity controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const min = parseInt(input.min);
            const current = parseInt(input.value);
            if (current > min) {
                input.value = current - 1;
            }
        }

        // Color swatch selector
        const colorSwatches = document.querySelectorAll('.color-swatch');
        colorSwatches.forEach(swatch => {
            swatch.addEventListener('click', () => {
                colorSwatches.forEach(s => s.classList.remove('selected'));
                swatch.classList.add('selected');
            });
        });

        // Size swatch selector
        const sizeSwatches = document.querySelectorAll('.swatch:not(.color-swatch)');
        sizeSwatches.forEach(swatch => {
            swatch.addEventListener('click', () => {
                sizeSwatches.forEach(s => s.classList.remove('selected'));
                swatch.classList.add('selected');
            });
        });

        // Tab switcher
        function switchTab(tabName) {
            // Remove active class from all tabs and buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => button.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to selected tab and button
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Action functions
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            const selectedColor = document.querySelector('.color-swatch.selected').dataset.color;
            const selectedSize = document.querySelector('.swatch.selected').dataset.size;

            // Show notification
            showNotification('Product added to cart!');
        }

        function buyNow() {
            showNotification('Redirecting to checkout...');
        }

        function addToWishlist() {
            showNotification('Added to wishlist!');
        }

        function addToCompare() {
            showNotification('Added to compare list!');
        }

        function askQuestion() {
            showNotification('Question form would open here');
        }

        function quickAddToCart(productName, price) {
            showNotification(`${productName} added to cart!`);
        }

        function contactSupport() {
            showNotification('Customer support team will contact you soon!');
        }

        // Review form functionality
        let selectedRating = 0;

        // Initialize star rating input
        const starRatingIcons = document.querySelectorAll('.star-rating-input i');
        starRatingIcons.forEach((star, index) => {
            star.addEventListener('click', () => {
                selectedRating = index + 1;
                updateStarDisplay(selectedRating);
            });

            star.addEventListener('mouseenter', () => {
                updateStarDisplay(index + 1);
            });
        });

        document.querySelector('.star-rating-input').addEventListener('mouseleave', () => {
            updateStarDisplay(selectedRating);
        });

        function updateStarDisplay(rating) {
            starRatingIcons.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        }

        function submitReview(event) {
            event.preventDefault();

            const name = document.getElementById('review-name').value;
            const email = document.getElementById('review-email').value;
            const subject = document.getElementById('review-subject').value;
            const comment = document.getElementById('review-comment').value;

            if (selectedRating === 0) {
                showNotification('Please select a rating');
                return;
            }

            // Create new review element
            const newReview = document.createElement('div');
            newReview.className = 'review-item';
            newReview.innerHTML = `
                <div class="review-header">
                    <strong>${name}</strong>
                    <div class="stars">
                        ${generateStars(selectedRating)}
                    </div>
                </div>
                <p class="review-comment">${comment}</p>
            `;

            // Add review to the list (at the beginning)
            const reviewsList = document.querySelector('.customer-reviews');
            const firstReview = reviewsList.querySelector('.review-item');
            if (firstReview) {
                reviewsList.insertBefore(newReview, firstReview);
            } else {
                reviewsList.appendChild(newReview);
            }

            // Reset form
            document.querySelector('.review-form').reset();
            selectedRating = 0;
            updateStarDisplay(0);

            // Show success message
            showNotification('Review submitted successfully!');
        }

        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star"></i>';
                } else {
                    stars += '<i class="far fa-star"></i>';
                }
            }
            return stars;
        }

        // Notification helper
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #0073aa;
                color: white;
                padding: 15px 25px;
                border-radius: 6px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
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
