<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro9;

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

      <div class="shopglut-single-templatePro9">
        <div class="container">
            <!-- THREE COLUMN LAYOUT -->
            <div class="product-page">
                <!-- ==================== LEFT COLUMN - GALLERY ==================== -->
                <div class="left-gallery">
                    <div class="main-image-container">
                        <span class="image-badge">-25% OFF</span>
                        <img id="main-product-image" src="demo-image.png" alt="Product main image">
                    </div>

                    <div class="thumbnail-slider">
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
                </div>

                <!-- ==================== MIDDLE COLUMN - INFO ==================== -->
                <div class="middle-info">
                    <!-- Reviews -->
                    <div class="reviews-top">
                        <div class="stars-large">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="review-count">
                            <a href="#">128 Reviews</a> | Add Your Review
                        </div>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation & Superior Sound Quality</h1>

                    <!-- Product Price -->
                    <div class="product-price">
                        <span class="current-price">$299.99</span>
                        <span class="original-price">$399.99</span>
                    </div>

                    <!-- Product Info List -->
                    <ul class="product-info-list">
                        <li><i class="fas fa-check-circle"></i> 40-hour battery life with quick charge</li>
                        <li><i class="fas fa-check-circle"></i> Active noise cancellation technology</li>
                        <li><i class="fas fa-check-circle"></i> Premium memory foam ear cushions</li>
                        <li><i class="fas fa-check-circle"></i> Bluetooth 5.0 with multipoint connection</li>
                    </ul>

                    <!-- Shipping Info -->
                    <div class="shipping-info">
                        <i class="fas fa-truck"></i>
                        <div>
                            <strong>Free Shipping</strong>
                            <span>On orders over $50 • Delivery in 3-5 days</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="divider"></div>

                    <!-- Color Options -->
                    <div class="color-section">
                        <h4>Select Color:</h4>
                        <div class="color-options">
                            <div class="color-option selected" onclick="selectColor(this)">
                                <img src="demo-image.png" alt="Black" class="color-option-img">
                                <div class="color-option-info">
                                    <div class="color-option-title">Midnight Black</div>
                                    <div class="color-option-price">$299.99</div>
                                </div>
                            </div>
                            <div class="color-option" onclick="selectColor(this)">
                                <img src="demo-image.png" alt="White" class="color-option-img">
                                <div class="color-option-info">
                                    <div class="color-option-title">Pearl White</div>
                                    <div class="color-option-price">$299.99</div>
                                </div>
                            </div>
                            <div class="color-option" onclick="selectColor(this)">
                                <img src="demo-image.png" alt="Blue" class="color-option-img">
                                <div class="color-option-info">
                                    <div class="color-option-title">Ocean Blue</div>
                                    <div class="color-option-price">$319.99</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Size Options -->
                    <div class="size-section">
                        <h4>Select Size:</h4>
                        <div class="size-options">
                            <div class="size-option selected" onclick="selectSize(this)">Standard</div>
                            <div class="size-option" onclick="selectSize(this)">Large</div>
                            <div class="size-option" onclick="selectSize(this)">XL</div>
                        </div>
                    </div>

                    <!-- Small Banner -->
                    <div class="small-banner">
                        <h4><i class="fas fa-gift"></i> Special Offer!</h4>
                        <p>Get 20% off on accessories when bought together</p>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <span>SKU:</span>
                            <strong>WH-ANC-2024-BLK</strong>
                        </div>
                        <div class="meta-item">
                            <span>Category:</span>
                            <a href="#">Electronics</a>, <a href="#">Audio</a>, <a href="#">Headphones</a>
                        </div>
                        <div class="meta-item">
                            <span>Brand:</span>
                            <a href="#">SoundMax Pro</a>
                        </div>
                    </div>

                    <!-- Social Share -->
                    <div class="social-share-section">
                        <span>Share:</span>
                        <div class="social-icons">
                            <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon whatsapp"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="social-icon pinterest"><i class="fab fa-pinterest-p"></i></a>
                        </div>
                    </div>
                </div>

                <!-- ==================== RIGHT COLUMN - CHECKOUT ==================== -->
                <div class="right-checkout">
                    <!-- Total Price -->
                    <div class="total-price-section">
                        <span>Total Price</span>
                        <strong>$299.99</strong>
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info">
                        <i class="fas fa-check-circle"></i>
                        <span>15 in stock</span>
                    </div>

                    <!-- Quantity -->
                    <div class="quantity-section">
                        <div class="quantity-input">
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="15">
                            <button onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart -->
                    <button class="add-to-cart-btn" onclick="addToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>

                    <!-- Buy Now -->
                    <button class="buy-now-btn" onclick="buyNow()">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>

                    <!-- Wishlist & Compare -->
                    <div class="wishlist-compare">
                        <button class="action-btn" onclick="addToWishlist()">
                            <i class="far fa-heart"></i>
                            Wishlist
                        </button>
                        <button class="action-btn" onclick="addToCompare()">
                            <i class="fas fa-exchange-alt"></i>
                            Compare
                        </button>
                    </div>

                    <!-- Payment Options -->
                    <div class="payment-options">
                        <span>Guaranteed safe checkout</span>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa payment-icon"></i>
                            <i class="fab fa-cc-mastercard payment-icon"></i>
                            <i class="fab fa-cc-amex payment-icon"></i>
                            <i class="fab fa-cc-paypal payment-icon"></i>
                            <i class="fab fa-cc-apple-pay payment-icon"></i>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="contact-section">
                        <h4>Need Help?</h4>
                        <a href="tel:+1234567890" class="call-btn">
                            <i class="fas fa-phone-alt"></i>
                            +1 234 567 890
                        </a>
                        <div class="contact-features">
                            <div class="contact-feature">
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure Payment</span>
                            </div>
                            <div class="contact-feature">
                                <i class="fas fa-undo"></i>
                                <span>Easy Returns</span>
                            </div>
                            <div class="contact-feature">
                                <i class="fas fa-headset"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== TWO COLUMN SECTION ==================== -->
            <div class="two-column-section">
                <!-- Frequently Bought Together -->
                <div class="frequently-bought">
                    <h3><i class="fas fa-plus-circle" style="color: #0073aa;"></i> Frequently Bought Together</h3>
                    <div class="bundle-items">
                        <div class="bundle-item">
                            <input type="checkbox" class="bundle-checkbox" checked data-price="299.99" onchange="updateBundleTotal()">
                            <img src="demo-image.png" alt="Main product">
                            <div class="bundle-item-info">
                                <div class="bundle-item-title">Premium Wireless Headphones</div>
                                <div class="bundle-item-price">$299.99</div>
                            </div>
                        </div>
                        <div class="bundle-item">
                            <input type="checkbox" class="bundle-checkbox" data-price="49.99" onchange="updateBundleTotal()">
                            <img src="demo-image.png" alt="Accessory 1">
                            <div class="bundle-item-info">
                                <div class="bundle-item-title">Headphone Stand Premium</div>
                                <div class="bundle-item-price">$49.99</div>
                            </div>
                        </div>
                        <div class="bundle-item">
                            <input type="checkbox" class="bundle-checkbox" data-price="24.99" onchange="updateBundleTotal()">
                            <img src="demo-image.png" alt="Accessory 2">
                            <div class="bundle-item-info">
                                <div class="bundle-item-title">Hard Carrying Case</div>
                                <div class="bundle-item-price">$24.99</div>
                            </div>
                        </div>
                    </div>
                    <div class="bundle-total-section">
                        <span class="bundle-total-label">Total Price:</span>
                        <span class="bundle-total-price">$<span id="bundle-total">299.99</span></span>
                    </div>
                    <div class="bundle-actions">
                        <button class="bundle-btn bundle-add-cart" onclick="bundleAddCart()">
                            <i class="fas fa-shopping-cart"></i>
                            Add All to Cart
                        </button>
                        <button class="bundle-btn bundle-buy-now" onclick="bundleBuyNow()">
                            <i class="fas fa-bolt"></i>
                            Buy Now
                        </button>
                        <button class="bundle-btn bundle-wishlist" onclick="bundleWishlist()">
                            <i class="far fa-heart"></i>
                            Wishlist
                        </button>
                    </div>
                </div>

                <!-- Right Banners -->
                <div class="right-banners">
                    <div class="banner-vertical">
                        <img src="demo-image.png" alt="Promotional banner 1">
                    </div>
                    <div class="banner-vertical">
                        <img src="demo-image.png" alt="Promotional banner 2">
                    </div>
                </div>
            </div>

            <!-- ==================== PRODUCT TABS ==================== -->
            <div class="full-width-section">
                <div class="product-tabs">
                    <div class="tab-navigation">
                        <button class="tab-button active" onclick="switchTab('description')">Description</button>
                        <button class="tab-button" onclick="switchTab('specifications')">Specifications</button>
                        <button class="tab-button" onclick="switchTab('reviews')">Reviews (128)</button>
                        <button class="tab-button" onclick="switchTab('shipping')">Shipping</button>
                    </div>

                    <div id="description" class="tab-content active">
                        <h3>Product Description</h3>
                        <p>Experience premium sound quality with our latest wireless headphones featuring industry-leading active noise cancellation technology. Perfect for music lovers, travelers, and professionals who demand the best audio experience.</p>
                        <p>Our headphones are engineered with precision drivers that deliver crystal-clear highs, rich mids, and powerful bass. The advanced ANC technology blocks out ambient noise, allowing you to immerse yourself in your music.</p>
                    </div>

                    <div id="specifications" class="tab-content">
                        <h3>Technical Specifications</h3>
                        <p><strong>Driver Size:</strong> 40mm Dynamic Drivers</p>
                        <p><strong>Frequency Response:</strong> 20Hz - 20kHz</p>
                        <p><strong>Battery Life:</strong> Up to 40 hours (ANC on), 50 hours (ANC off)</p>
                        <p><strong>Charging:</strong> USB-C, 2 hours full charge, 5 min = 3 hours playback</p>
                        <p><strong>Bluetooth:</strong> Version 5.0, Range 10m, Multipoint connection</p>
                        <p><strong>Weight:</strong> 250g with premium carrying case included</p>
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
                                    <p style="margin: 0; font-size: 14px;">Great headphones overall. Comfortable for long listening sessions.</p>
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
                                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                    <strong>Express Shipping</strong>
                                    <p style="margin: 5px 0;">1-2 business days</p>
                                    <span style="color: #28a745; font-weight: 500;">$9.99</span>
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

            <!-- ==================== RELATED PRODUCTS ==================== -->
            <div class="full-width-section">
                <div class="related-products">
                    <h2 class="section-title">Related Products</h2>
                    <div class="products-grid">
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 1">
                            <div class="product-card-info">
                                <div class="product-card-title">Wireless Earbuds Pro</div>
                                <div class="product-card-price">$149.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Wireless Earbuds Pro', 149.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 2">
                            <div class="product-card-info">
                                <div class="product-card-title">Portable Speaker</div>
                                <div class="product-card-price">$89.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Portable Speaker', 89.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 3">
                            <div class="product-card-info">
                                <div class="product-card-title">USB-C Hub Adapter</div>
                                <div class="product-card-price">$49.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('USB-C Hub Adapter', 49.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 4">
                            <div class="product-card-info">
                                <div class="product-card-title">Headphone Stand</div>
                                <div class="product-card-price">$29.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Headphone Stand', 29.99)">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== RECENTLY VIEWED ==================== -->
            <div class="full-width-section">
                <div class="recently-viewed">
                    <h2 class="section-title">Recently Viewed Products</h2>
                    <div class="products-grid">
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 1">
                            <div class="product-card-info">
                                <div class="product-card-title">Smart Watch Series 5</div>
                                <div class="product-card-price">$249.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Smart Watch', 249.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 2">
                            <div class="product-card-info">
                                <div class="product-card-title">Laptop Sleeve 15"</div>
                                <div class="product-card-price">$34.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Laptop Sleeve', 34.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 3">
                            <div class="product-card-info">
                                <div class="product-card-title">Wireless Mouse</div>
                                <div class="product-card-price">$44.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Wireless Mouse', 44.99)">Add to Cart</button>
                            </div>
                        </div>
                        <div class="product-card">
                            <img src="demo-image.png" alt="Product 4">
                            <div class="product-card-info">
                                <div class="product-card-title">Mechanical Keyboard</div>
                                <div class="product-card-price">$129.99</div>
                                <button class="product-card-btn" onclick="quickAddToCart('Keyboard', 129.99)">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast"></div>

    <script>
        // Change Main Image
        function changeMainImage(thumbnail, imageSrc) {
            document.querySelectorAll('.thumb-item').forEach(item => item.classList.remove('active'));
            thumbnail.classList.add('active');
            document.getElementById('main-product-image').src = imageSrc;
        }

        // Quantity Controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) < 15) input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
        }

        // Select Color
        function selectColor(element) {
            document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        // Select Size
        function selectSize(element) {
            document.querySelectorAll('.size-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        // Tab Switcher
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Actions
        function addToCart() { showToast('Added to cart!'); }
        function buyNow() { showToast('Redirecting to checkout...'); }
        function addToWishlist() { showToast('Added to wishlist!'); }
        function addToCompare() { showToast('Added to compare!'); }
        function quickAddToCart(name, price) { showToast(`${name} added to cart!`); }
        function submitReview(e) { e.preventDefault(); showToast('Review submitted!'); e.target.reset(); }

        // Bundle
        function updateBundleTotal() {
            const checkboxes = document.querySelectorAll('.bundle-checkbox');
            let total = 0;
            checkboxes.forEach(cb => { if (cb.checked) total += parseFloat(cb.dataset.price); });
            document.getElementById('bundle-total').textContent = total.toFixed(2);
        }

        function bundleAddCart() {
            const count = document.querySelectorAll('.bundle-checkbox:checked').length;
            const total = document.getElementById('bundle-total').textContent;
            showToast(`${count} item(s) added to cart! Total: $${total}`);
        }

        function bundleBuyNow() { showToast('Proceeding to checkout with bundle...'); }
        function bundleWishlist() { showToast('Bundle added to wishlist!'); }

        // Toast
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
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
