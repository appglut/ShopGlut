<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro10;

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
   <div class="shopglut-single-templatePro10">
        <div class="container">
            <div class="product-page">
                <!-- ==================== LEFT COLUMN - GALLERY ==================== -->
                <div class="left-gallery">
                    <div class="main-image-wrapper">
                        <img id="main-product-image" src="<?php echo esc_url($placeholder_url); ?>" alt="Product main image">
                        <div class="image-zoom-badge" onclick="zoomImage()">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>

                    <!-- Thumbnails Beneath Main Image -->
                    <div class="thumbnails-container">
                        <div class="thumbnail-item active" onclick="changeMainImage(this, '<?php echo esc_js($placeholder_url); ?>')">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 1">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, '<?php echo esc_js($placeholder_url); ?>')">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 2">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, '<?php echo esc_js($placeholder_url); ?>')">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 3">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, '<?php echo esc_js($placeholder_url); ?>')">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 4">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, '<?php echo esc_js($placeholder_url); ?>')">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Thumbnail 5">
                        </div>
                    </div>

                    <!-- Gallery Actions -->
                    <div class="gallery-actions">
                        <div class="gallery-action-btn" onclick="playVideo()">
                            <i class="fas fa-play-circle"></i>
                            Watch Video
                        </div>
                        <div class="gallery-action-btn" onclick="view360()">
                            <i class="fas fa-sync-alt"></i>
                            360° View
                        </div>
                    </div>

                    <!-- Quick Features -->
                    <div class="quick-features">
                        <div class="quick-feature">
                            <i class="fas fa-battery-full"></i>
                            <span>40H Battery</span>
                        </div>
                        <div class="quick-feature">
                            <i class="fas fa-volume-mute"></i>
                            <span>ANC</span>
                        </div>
                        <div class="quick-feature">
                            <i class="fas fa-microphone"></i>
                            <span>Mic</span>
                        </div>
                    </div>
                </div>

                <!-- ==================== MIDDLE COLUMN - INFO ==================== -->
                <div class="middle-info">
                    <!-- Product Badge -->
                    <div class="product-badge">
                        <i class="fas fa-fire"></i>
                        Best Seller
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>

                    <!-- Reviews Section -->
                    <div class="reviews-section">
                        <div class="reviews-stars">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="average">4.5</span>
                        </div>
                        <div class="reviews-details">
                            <span>Based on 128 verified reviews</span>
                            <a href="#">Read all reviews →</a>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="product-description">
                        Experience exceptional sound quality with our premium wireless headphones. Featuring advanced active noise cancellation technology, 40-hour battery life, and ultra-comfortable memory foam ear cushions. Perfect for music lovers, professionals, and travelers who demand the best.
                    </div>

                    <!-- Price Section -->
                    <div class="price-section">
                        <span class="current-price">$299.99</span>
                        <span class="original-price">$399.99</span>
                        <span class="save-badge">Save $100</span>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-status">
                        <div class="stock-indicator"></div>
                        <span>Only 15 items left in stock - Order soon!</span>
                    </div>

                    <!-- Product Variations -->
                    <div class="variations-section">
                        <div class="variation-group">
                            <label class="variation-label">Color</label>
                            <div class="color-swatches">
                                <div class="color-swatch selected" style="background: #1a1a1a;" onclick="selectColor(this)"></div>
                                <div class="color-swatch" style="background: #ffffff; border: 2px solid #ddd;" onclick="selectColor(this)"></div>
                                <div class="color-swatch" style="background: #0073aa;" onclick="selectColor(this)"></div>
                                <div class="color-swatch" style="background: #dc3545;" onclick="selectColor(this)"></div>
                                <div class="color-swatch" style="background: #6c5ce7;" onclick="selectColor(this)"></div>
                            </div>
                        </div>
                        <div class="variation-group">
                            <label class="variation-label">Size</label>
                            <div class="size-swatches">
                                <div class="size-swatch selected" onclick="selectSize(this)">Standard</div>
                                <div class="size-swatch" onclick="selectSize(this)">Large</div>
                                <div class="size-swatch" onclick="selectSize(this)">XL</div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Meta Information -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="fas fa-barcode"></i>
                            <div>
                                <label>SKU</label>
                                <span>WH-ANC-2024-BLK</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tags"></i>
                            <div>
                                <label>Category</label>
                                <a href="#">Electronics</a>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-folder-open"></i>
                            <div>
                                <label>Sub-Category</label>
                                <a href="#">Audio</a>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-trademark"></i>
                            <div>
                                <label>Brand</label>
                                <a href="#">SoundMax Pro</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RIGHT COLUMN - SIDEBAR ==================== -->
                <div class="right-sidebar">
                    <!-- Highlight Box -->
                    <div class="highlight-box">
                        <h3><i class="fas fa-star"></i> Why Choose Us?</h3>
                        <div class="highlight-list">
                            <div class="highlight-item">
                                <i class="fas fa-shipping-fast"></i>
                                <div>
                                    <strong>Free Shipping</strong>
                                    <p>On all orders over $50</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-undo-alt"></i>
                                <div>
                                    <strong>Easy Returns</strong>
                                    <p>30-day hassle-free returns</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <strong>Secure Payment</strong>
                                    <p>100% protected transactions</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-headset"></i>
                                <div>
                                    <strong>24/7 Support</strong>
                                    <p>Dedicated customer service</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Price Box -->
                    <div class="total-box">
                        <div class="total-box-label">Total Price</div>
                        <div class="total-box-price"><span>$</span>299.99</div>
                        <div class="total-box-savings">
                            <i class="fas fa-tag"></i> You save $100 (25% off)
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-wishlist" onclick="addToWishlist()">
                            <i class="far fa-heart"></i>
                            Add to Wishlist
                        </button>
                        <button class="btn btn-addcart" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>

                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <i class="fab fa-cc-visa"></i>
                            <span>Visa</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fab fa-cc-mastercard"></i>
                            <span>Mastercard</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fab fa-cc-paypal"></i>
                            <span>PayPal</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-lock"></i>
                            <span>Secure</span>
                        </div>
                    </div>
                </div>

                <!-- ==================== FULL WIDTH SECTIONS ==================== -->

                <!-- Product Tabs -->
                <div class="full-width">
                    <div class="product-tabs">
                        <div class="tab-navigation">
                            <button class="tab-button active" onclick="switchTab('description')">Description</button>
                            <button class="tab-button" onclick="switchTab('specifications')">Specifications</button>
                            <button class="tab-button" onclick="switchTab('reviews')">Reviews</button>
                            <button class="tab-button" onclick="switchTab('shipping')">Shipping</button>
                        </div>

                        <div id="description" class="tab-content active">
                            <h3>Product Description</h3>
                            <p>Introducing our premium wireless headphones – the perfect blend of cutting-edge technology and exceptional comfort. Engineered for audiophiles and professionals alike, these headphones deliver an immersive listening experience that sets new standards in the industry.</p>
                            <p>The advanced active noise cancellation system uses intelligent algorithms to analyze and block ambient sounds, allowing you to focus on what matters most – your music. With up to 40 hours of battery life, you can enjoy uninterrupted listening throughout your day.</p>
                            <p>Designed with premium materials including soft memory foam ear cushions and an adjustable headband, these headphones provide all-day comfort. The multipoint Bluetooth 5.0 connection lets you seamlessly switch between devices.</p>
                        </div>

                        <div id="specifications" class="tab-content">
                            <h3>Technical Specifications</h3>
                            <p><strong>Driver Size:</strong> 40mm Neodymium Drivers</p>
                            <p><strong>Frequency Response:</strong> 4Hz - 40kHz</p>
                            <p><strong>Impedance:</strong> 32 Ohms</p>
                            <p><strong>Battery Life:</strong> Up to 40 hours (ANC on), 50 hours (ANC off)</p>
                            <p><strong>Charging:</strong> USB-C Fast Charging (5 min = 3 hours playback)</p>
                            <p><strong>Bluetooth:</strong> Version 5.0, Range 10m, Multipoint Connection</p>
                            <p><strong>Weight:</strong> 250g</p>
                            <p><strong>Color Options:</strong> Black, White, Blue, Red, Purple</p>
                        </div>

                        <div id="reviews" class="tab-content">
                            <h3>Customer Reviews</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                                        <div style="font-size: 48px; font-weight: 700; color: #1a1a2e;">4.5</div>
                                        <div>
                                            <div style="display: flex; gap: 5px; color: #ffc107; margin-bottom: 8px;">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </div>
                                            <div style="color: #666; font-size: 14px;">Based on 128 reviews</div>
                                        </div>
                                    </div>
                                    <div style="padding: 18px; background: #f8f9fa; border-radius: 12px; margin-bottom: 15px;">
                                        <strong>John D.</strong>
                                        <div style="display: flex; gap: 5px; color: #ffc107; font-size: 14px; margin: 8px 0;">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                        <p style="margin: 0; font-size: 14px; color: #555;">"Absolutely incredible sound quality! The noise cancellation is a game-changer for my daily commute."</p>
                                    </div>
                                    <div style="padding: 18px; background: #f8f9fa; border-radius: 12px;">
                                        <strong>Sarah M.</strong>
                                        <div style="display: flex; gap: 5px; color: #ffc107; font-size: 14px; margin: 8px 0;">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                        </div>
                                        <p style="margin: 0; font-size: 14px; color: #555;">"Very comfortable for long sessions. Battery life is exactly as advertised. Highly recommend!"</p>
                                    </div>
                                </div>
                                <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 30px; border-radius: 16px;">
                                    <h4 style="margin-bottom: 20px; font-size: 18px;">Write a Review</h4>
                                    <form onsubmit="submitReview(event)">
                                        <div style="margin-bottom: 18px;">
                                            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Your Name</label>
                                            <input type="text" required style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px;">
                                        </div>
                                        <div style="margin-bottom: 18px;">
                                            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Your Email</label>
                                            <input type="email" required style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px;">
                                        </div>
                                        <div style="margin-bottom: 18px;">
                                            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Your Review</label>
                                            <textarea rows="5" required style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px;"></textarea>
                                        </div>
                                        <button type="submit" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #0073aa, #005a87); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">Submit Review</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="shipping" class="tab-content">
                            <h3>Shipping Information</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                                <div>
                                    <h4 style="margin-bottom: 20px; color: #0073aa; font-size: 18px;">Shipping Options</h4>
                                    <div style="padding: 20px; background: #f8f9fa; border-radius: 12px; margin-bottom: 18px;">
                                        <strong style="font-size: 16px;">Standard Shipping</strong>
                                        <p style="margin: 8px 0; color: #666;">3-5 business days</p>
                                        <span style="color: #2e7d32; font-weight: 600;">FREE on orders over $50</span>
                                    </div>
                                    <div style="padding: 20px; background: #f8f9fa; border-radius: 12px; margin-bottom: 18px;">
                                        <strong style="font-size: 16px;">Express Shipping</strong>
                                        <p style="margin: 8px 0; color: #666;">1-2 business days</p>
                                        <span style="color: #2e7d32; font-weight: 600;">$9.99</span>
                                    </div>
                                    <div style="padding: 20px; background: #f8f9fa; border-radius: 12px;">
                                        <strong style="font-size: 16px;">Overnight Shipping</strong>
                                        <p style="margin: 8px 0; color: #666;">Next business day</p>
                                        <span style="color: #2e7d32; font-weight: 600;">$19.99</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 20px; color: #0073aa; font-size: 18px;">Return Policy</h4>
                                    <p style="line-height: 1.8; color: #555;">We offer a comprehensive 30-day return policy. If you're not completely satisfied with your purchase, you can return it for a full refund. Products must be in original condition with all tags and packaging.</p>
                                    <ul style="margin-top: 20px; padding-left: 20px; color: #555;">
                                        <li style="margin-bottom: 12px;">30-day return window from delivery date</li>
                                        <li style="margin-bottom: 12px;">Free returns on defective items</li>
                                        <li style="margin-bottom: 12px;">Refund processed within 5-7 business days</li>
                                        <li style="margin-bottom: 12px;">Easy return label generation</li>
                                        <li>Customer satisfaction guaranteed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="full-width">
                    <div class="related-products">
                        <h2 class="section-title">Related Products</h2>
                        <div class="products-grid">
                            <div class="product-card">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product 1">
                                <div class="product-card-info">
                                    <div class="product-card-title">Wireless Earbuds Pro</div>
                                    <div class="product-card-price">$149.99</div>
                                    <button class="product-card-btn" onclick="quickAddToCart('Wireless Earbuds Pro', 149.99)">Add to Cart</button>
                                </div>
                            </div>
                            <div class="product-card">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product 2">
                                <div class="product-card-info">
                                    <div class="product-card-title">Portable Bluetooth Speaker</div>
                                    <div class="product-card-price">$89.99</div>
                                    <button class="product-card-btn" onclick="quickAddToCart('Portable Speaker', 89.99)">Add to Cart</button>
                                </div>
                            </div>
                            <div class="product-card">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product 3">
                                <div class="product-card-info">
                                    <div class="product-card-title">Premium Headphone Stand</div>
                                    <div class="product-card-price">$49.99</div>
                                    <button class="product-card-btn" onclick="quickAddToCart('Headphone Stand', 49.99)">Add to Cart</button>
                                </div>
                            </div>
                            <div class="product-card">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product 4">
                                <div class="product-card-info">
                                    <div class="product-card-title">Hard Carrying Case</div>
                                    <div class="product-card-price">$29.99</div>
                                    <button class="product-card-btn" onclick="quickAddToCart('Carrying Case', 29.99)">Add to Cart</button>
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
        // Change Main Image
        function changeMainImage(thumbnail, imageSrc) {
            document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
            thumbnail.classList.add('active');
            document.getElementById('main-product-image').src = imageSrc;
        }

        // Zoom Image
        function zoomImage() {
            showToast('Image zoom feature - would open modal');
        }

        // Play Video
        function playVideo() {
            showToast('Product video would play in modal <i class="fas fa-play-circle"></i>');
        }

        // 360 View
        function view360() {
            showToast('360° product viewer would open <i class="fas fa-sync-alt"></i>');
        }

        // Select Color
        function selectColor(element) {
            document.querySelectorAll('.color-swatch').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        // Select Size
        function selectSize(element) {
            document.querySelectorAll('.size-swatch').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        // Tab Switcher
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Action Functions
        function addToWishlist() {
            showToast('Added to wishlist! <i class="fas fa-heart" style="color: #e91e63;"></i>');
        }

        function addToCart() {
            showToast('Added to cart! <i class="fas fa-check-circle" style="color: #28a745;"></i>');
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
            toast.innerHTML = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3500);
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
