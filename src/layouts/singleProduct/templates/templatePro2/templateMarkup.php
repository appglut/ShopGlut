<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro2;

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

		 <div class="shopglut-single-templatePro2">
        <div class="container">
            <div class="product-page">
                <!-- ==================== LEFT SIDE ==================== -->
                <div class="left-side">
                    <!-- Main Image -->
                    <div class="main-image-container">
                        <img id="main-product-image" src="demo-image.png" alt="Product main image">
                    </div>

                    <!-- Thumbnail Carousel -->
                    <div class="thumbnail-carousel">
                        <div class="thumb-item active" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Thumbnail 1">
                        </div>
                        <div class="thumb-item" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Thumbnail 2">
                        </div>
                        <div class="thumb-item" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Thumbnail 3">
                        </div>
                        <div class="thumb-item" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Thumbnail 4">
                        </div>
                        <div class="thumb-item" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Thumbnail 5">
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="trust-badge-text">
                                <strong>100% Original</strong>
                                <span>Genuine products only</span>
                            </div>
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="trust-badge-text">
                                <strong>Easy Returns</strong>
                                <span>30 days policy</span>
                            </div>
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="trust-badge-text">
                                <strong>Free Shipping</strong>
                                <span>On orders over $50</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== MIDDLE SIDE ==================== -->
                <div class="middle-side">
                    <!-- Brand & Category -->
                    <div class="brand-section">
                        <span class="brand-label">Brand:</span>
                        <a href="#" class="brand-category">Electronics &gt; Smartphones</a>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>

                    <!-- Price & Reviews -->
                    <div class="price-reviews">
                        <div class="price-section">
                            <span class="current-price">$299.99</span>
                            <span class="original-price">$399.99</span>
                            <span class="discount-badge">-25%</span>
                        </div>
                        <div class="reviews-section">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="reviews-count">(4.5) 128 Reviews</span>
                        </div>
                    </div>

                    <!-- Product Info with Fade Animation -->
                    <div class="product-info-list">
                        <div class="info-item active" id="info-item-0">
                            <i class="fas fa-box-open"></i>
                            <span>Only <strong>15 items</strong> left in stock!</span>
                        </div>
                        <div class="info-item" id="info-item-1">
                            <i class="fas fa-clock"></i>
                            <span>Discount ends in: <strong class="countdown-timer">23:59:45</strong></span>
                        </div>
                        <div class="info-item" id="info-item-2">
                            <i class="fas fa-shopping-bag"></i>
                            <span><strong>847 people</strong> bought this product</span>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="product-description">
                        <h4>Product Highlights:</h4>
                        <ol>
                            <li>Industry-leading active noise cancellation for immersive sound experience</li>
                            <li>40-hour battery life with quick charge - 5 min charge = 3 hours playback</li>
                            <li>Premium memory foam ear cushions for all-day comfort</li>
                            <li>Bluetooth 5.0 with multipoint connection - connect 2 devices simultaneously</li>
                            <li>Built-in microphone with crystal clear voice call quality</li>
                        </ol>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="quantity-cart">
                        <div class="quantity-input">
                            <button onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="15">
                            <button onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>

                    <!-- Buy Now Button (Full Width) -->
                    <button class="buy-now-btn" onclick="buyNow()">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>

                    <!-- Wishlist & Compare -->
                    <div class="wishlist-compare">
                        <button class="action-btn" onclick="addToWishlist()">
                            <i class="far fa-heart"></i>
                            Add to Wishlist
                        </button>
                        <button class="action-btn" onclick="addToCompare()">
                            <i class="fas fa-exchange-alt"></i>
                            Add to Compare
                        </button>
                    </div>

                    <!-- Ask Question & Social Share -->
                    <div class="question-social">
                        <button class="ask-question-btn" onclick="askQuestion()">
                            <i class="far fa-question-circle"></i>
                            Ask a Question
                        </button>
                        <div class="social-share">
                            <span>Share:</span>
                            <div class="social-icons">
                                <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon whatsapp"><i class="fab fa-whatsapp"></i></a>
                                <a href="#" class="social-icon pinterest"><i class="fab fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Active Users -->
                    <div class="active-users">
                        <div class="user-avatars">
                            <div class="user-avatar"><i class="fas fa-user"></i></div>
                            <div class="user-avatar"><i class="fas fa-user"></i></div>
                            <div class="user-avatar"><i class="fas fa-user"></i></div>
                        </div>
                        <span class="users-text"><strong>24 people</strong> are viewing this page right now</span>
                    </div>

                    <!-- Payment Options -->
                    <div class="payment-options-section">
                        <h4>Payment Methods</h4>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <i class="fab fa-cc-visa"></i>
                                <span>Visa</span>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-mastercard"></i>
                                <span>Mastercard</span>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-amex"></i>
                                <span>Amex</span>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-paypal"></i>
                                <span>PayPal</span>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-apple-pay"></i>
                                <span>Apple Pay</span>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-google-pay"></i>
                                <span>Google Pay</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RIGHT SIDE ==================== -->
                <div class="right-side">
                    <!-- Banner -->
                    <div class="side-banner">
                        <img src="demo-image.png" alt="Promotional Banner">
                    </div>

                    <!-- Top Rated Products -->
                    <div class="top-rated-section">
                        <h3><i class="fas fa-star"></i> Top Rated Products</h3>

                        <div class="rated-product-item">
                            <div class="rated-product-img">
                                <img src="demo-image.png" alt="Product 1">
                            </div>
                            <div class="rated-product-info">
                                <div class="rated-product-title">Wireless Earbuds Pro Max</div>
                                <div class="rated-product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span>(156)</span>
                                </div>
                                <div class="rated-product-price">$149.99</div>
                            </div>
                        </div>

                        <div class="rated-product-item">
                            <div class="rated-product-img">
                                <img src="demo-image.png" alt="Product 2">
                            </div>
                            <div class="rated-product-info">
                                <div class="rated-product-title">Portable Bluetooth Speaker</div>
                                <div class="rated-product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span>(89)</span>
                                </div>
                                <div class="rated-product-price">$89.99</div>
                            </div>
                        </div>

                        <div class="rated-product-item">
                            <div class="rated-product-img">
                                <img src="demo-image.png" alt="Product 3">
                            </div>
                            <div class="rated-product-info">
                                <div class="rated-product-title">USB-C Hub Multiport Adapter</div>
                                <div class="rated-product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span>(234)</span>
                                </div>
                                <div class="rated-product-price">$49.99</div>
                            </div>
                        </div>

                        <div class="rated-product-item">
                            <div class="rated-product-img">
                                <img src="demo-image.png" alt="Product 4">
                            </div>
                            <div class="rated-product-info">
                                <div class="rated-product-title">Smart Watch Series 5</div>
                                <div class="rated-product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <span>(67)</span>
                                </div>
                                <div class="rated-product-price">$249.99</div>
                            </div>
                        </div>

                        <div class="rated-product-item">
                            <div class="rated-product-img">
                                <img src="demo-image.png" alt="Product 5">
                            </div>
                            <div class="rated-product-info">
                                <div class="rated-product-title">Laptop Stand Aluminum</div>
                                <div class="rated-product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span>(112)</span>
                                </div>
                                <div class="rated-product-price">$39.99</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== FULL WIDTH SECTIONS ==================== -->

                <!-- Product Tabs (Centered Title) -->
                <div class="full-width-section">
                    <div class="product-tabs">
                        <div class="tab-navigation">
                            <button class="tab-button active" onclick="switchTab('description')">Description</button>
                            <button class="tab-button" onclick="switchTab('specifications')">Specifications</button>
                            <button class="tab-button" onclick="switchTab('reviews')">Reviews (128)</button>
                            <button class="tab-button" onclick="switchTab('shipping')">Shipping Info</button>
                        </div>

                        <div id="description" class="tab-content active">
                            <h3>Product Description</h3>
                            <p>Our premium wireless headphones redefine your audio experience with cutting-edge technology and superior comfort. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear sound with deep bass and crisp highs.</p>
                            <p>The advanced active noise cancellation technology blocks out the world around you, allowing you to focus on your music, podcasts, or calls. With up to 40 hours of battery life, you can enjoy your audio all day long without worrying about recharging.</p>
                            <p>The premium memory foam ear cushions provide unmatched comfort, even during extended listening sessions. The foldable design makes them easy to carry in your bag, perfect for travelers and commuters.</p>
                        </div>

                        <div id="specifications" class="tab-content">
                            <h3>Technical Specifications</h3>
                            <p><strong>Driver Size:</strong> 40mm Dynamic Drivers</p>
                            <p><strong>Frequency Response:</strong> 20Hz - 20kHz</p>
                            <p><strong>Impedance:</strong> 32 Ohms</p>
                            <p><strong>Battery Life:</strong> Up to 40 hours (ANC on), 50 hours (ANC off)</p>
                            <p><strong>Charging Time:</strong> 2 hours (USB-C)</p>
                            <p><strong>Quick Charge:</strong> 5 minutes = 3 hours playback</p>
                            <p><strong>Bluetooth Version:</strong> 5.0 with AAC, aptX HD, LDAC support</p>
                            <p><strong>Weight:</strong> 250g</p>
                        </div>

                        <div id="reviews" class="tab-content">
                            <h3>Customer Reviews</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                <div>
                                    <p style="margin-bottom: 15px;"><strong>Average Rating:</strong> 4.5/5 based on 128 reviews</p>
                                    <div style="display: flex; gap: 5px; color: #ffc107; margin-bottom: 20px;">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px;">
                                        <strong>John D.</strong>
                                        <div style="display: flex; gap: 5px; color: #ffc107; font-size: 12px; margin: 5px 0;">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                        <p style="margin: 0; font-size: 14px;">Amazing sound quality! The noise cancellation is incredible, perfect for my daily commute.</p>
                                    </div>
                                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                        <strong>Sarah M.</strong>
                                        <div style="display: flex; gap: 5px; color: #ffc107; font-size: 12px; margin: 5px 0;">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                        </div>
                                        <p style="margin: 0; font-size: 14px;">Great headphones overall. Comfortable for long listening sessions. Battery life is as advertised.</p>
                                    </div>
                                </div>
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                                    <h4 style="margin-bottom: 15px;">Write a Review</h4>
                                    <form onsubmit="submitReview(event)">
                                        <div style="margin-bottom: 15px;">
                                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Your Name</label>
                                            <input type="text" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Your Email</label>
                                            <input type="email" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Your Review</label>
                                            <textarea rows="4" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;"></textarea>
                                        </div>
                                        <button type="submit" style="background: #0073aa; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-weight: 500;">Submit Review</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="shipping" class="tab-content">
                            <h3>Shipping Information</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                <div>
                                    <h4 style="margin-bottom: 15px; color: #0073aa;">Shipping Options</h4>
                                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px;">
                                        <strong>Standard Shipping</strong>
                                        <p style="margin: 5px 0;">3-5 business days</p>
                                        <span style="color: #28a745; font-weight: 500;">FREE on orders over $50</span>
                                    </div>
                                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px;">
                                        <strong>Express Shipping</strong>
                                        <p style="margin: 5px 0;">1-2 business days</p>
                                        <span style="color: #28a745; font-weight: 500;">$9.99</span>
                                    </div>
                                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                        <strong>Overnight Shipping</strong>
                                        <p style="margin: 5px 0;">Next business day</p>
                                        <span style="color: #28a745; font-weight: 500;">$19.99</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 15px; color: #0073aa;">Return Policy</h4>
                                    <p>We offer a 30-day hassle-free return policy. If you're not completely satisfied with your purchase, you can return it for a full refund within 30 days of delivery.</p>
                                    <ul style="margin-top: 15px; padding-left: 20px;">
                                        <li style="margin-bottom: 8px;">Product must be in original condition</li>
                                        <li style="margin-bottom: 8px;">All tags and packaging included</li>
                                        <li style="margin-bottom: 8px;">Free returns for defective items</li>
                                        <li>Refund processed within 5-7 business days</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="full-width-section">
                    <div class="related-products">
                        <h2 class="section-title">Related Products</h2>
                        <div class="related-products-grid">
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 1">
                                <div class="related-product-info">
                                    <div class="related-product-title">Wireless Earbuds Pro</div>
                                    <div class="related-product-price">$149.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('Wireless Earbuds Pro', 149.99)">Quick Add</button>
                                </div>
                            </div>
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 2">
                                <div class="related-product-info">
                                    <div class="related-product-title">Portable Speaker</div>
                                    <div class="related-product-price">$89.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('Portable Speaker', 89.99)">Quick Add</button>
                                </div>
                            </div>
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 3">
                                <div class="related-product-info">
                                    <div class="related-product-title">Headphone Stand</div>
                                    <div class="related-product-price">$29.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('Headphone Stand', 29.99)">Quick Add</button>
                                </div>
                            </div>
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 4">
                                <div class="related-product-info">
                                    <div class="related-product-title">USB-C Charging Cable</div>
                                    <div class="related-product-price">$19.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('USB-C Charging Cable', 19.99)">Quick Add</button>
                                </div>
                            </div>
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 5">
                                <div class="related-product-info">
                                    <div class="related-product-title">Carrying Case</div>
                                    <div class="related-product-price">$24.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('Carrying Case', 24.99)">Quick Add</button>
                                </div>
                            </div>
                            <div class="related-product">
                                <img src="demo-image.png" alt="Related Product 6">
                                <div class="related-product-info">
                                    <div class="related-product-title">Audio Cable 3.5mm</div>
                                    <div class="related-product-price">$14.99</div>
                                    <button class="quick-add-btn" onclick="quickAddToCart('Audio Cable 3.5mm', 14.99)">Quick Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        // Thumbnail Image Switcher
        function changeMainImage(thumbnail, imageSrc) {
            document.querySelectorAll('.thumb-item').forEach(item => {
                item.classList.remove('active');
            });
            thumbnail.classList.add('active');
            document.getElementById('main-product-image').src = imageSrc;
        }

        // Quantity Controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const max = 15;
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const min = 1;
            if (parseInt(input.value) > min) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Tab Switcher
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Action Functions
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            showToast(`Added ${quantity} item(s) to cart!`);
        }

        function buyNow() {
            showToast('Redirecting to checkout...');
        }

        function addToWishlist() {
            showToast('Added to wishlist!');
        }

        function addToCompare() {
            showToast('Added to compare list!');
        }

        function askQuestion() {
            showToast('Question form will open here');
        }

        function quickAddToCart(productName, price) {
            showToast(`${productName} added to cart!`);
        }

        function submitReview(event) {
            event.preventDefault();
            showToast('Review submitted successfully!');
            event.target.reset();
        }

        // Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Countdown Timer for Discount
        function startCountdown() {
            let hours = 23;
            let minutes = 59;
            let seconds = 45;

            setInterval(() => {
                seconds--;
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                        if (hours < 0) {
                            hours = 23;
                        }
                    }
                }

                const timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                const timerElement = document.querySelector('.countdown-timer');
                if (timerElement) {
                    timerElement.textContent = timeString;
                }
            }, 1000);
        }

        startCountdown();

        // Info Items Rotation Animation
        function startInfoRotation() {
            let currentIndex = 0;
            const totalItems = 3;
            const intervalTime = 3000; // Change every 3 seconds

            setInterval(() => {
                const currentItem = document.getElementById(`info-item-${currentIndex}`);
                currentItem.classList.remove('active');
                currentItem.classList.add('exit');

                setTimeout(() => {
                    currentItem.classList.remove('exit');
                }, 500);

                currentIndex = (currentIndex + 1) % totalItems;

                const nextItem = document.getElementById(`info-item-${currentIndex}`);
                setTimeout(() => {
                    nextItem.classList.add('active');
                }, 100);
            }, intervalTime);
        }

        startInfoRotation();
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
