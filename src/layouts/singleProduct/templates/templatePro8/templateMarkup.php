<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro8;

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

		 <div class="shopglut-single-templatePro8">
        <div class="container">
            <div class="product-page">
                <!-- ==================== LEFT SIDE ==================== -->
                <div class="left-side">
                    <!-- Main Image -->
                    <div class="main-image-wrapper">
                        <img id="main-product-image" src="demo-image.png" alt="Product main image">
                    </div>

                    <!-- Image Grid 3x2 -->
                    <div class="image-grid">
                        <div class="grid-image active" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 1">
                        </div>
                        <div class="grid-image" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 2">
                        </div>
                        <div class="grid-image" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 3">
                        </div>
                        <div class="grid-image" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 4">
                        </div>
                        <div class="grid-image" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 5">
                        </div>
                        <div class="grid-image" onclick="changeMainImage(this, 'demo-image.png')">
                            <img src="demo-image.png" alt="Product image 6">
                        </div>
                    </div>
                </div>

                <!-- ==================== RIGHT SIDE ==================== -->
                <div class="right-side">
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        <a href="#">Home</a>
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                        <a href="#">Shop</a>
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                        <a href="#">Electronics</a>
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                        <span>Product</span>
                    </nav>

                    <!-- Product Category -->
                    <div class="product-category">
                        <a href="#">Electronics</a> / <a href="#">Audio</a>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>

                    <!-- Stock & Reviews -->
                    <div class="stock-reviews">
                        <div class="stock-info">
                            <i class="fas fa-check-circle"></i>
                            <span>15 in stock</span>
                        </div>
                        <div class="reviews">
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

                    <!-- Product Description -->
                    <div class="product-description">
                        Experience premium sound quality with our latest wireless headphones featuring industry-leading active noise cancellation technology. Perfect for music lovers, travelers, and professionals.
                    </div>

                    <!-- Product Price -->
                    <div class="product-price">
                        <span class="current-price">$299.99</span>
                        <span class="original-price">$399.99</span>
                    </div>

                    <!-- Product Variations -->
                    <div class="product-variations">
                        <div class="variation-group">
                            <label class="variation-label">Color:</label>
                            <div class="swatches">
                                <div class="color-swatch selected" style="background-color: #1a1a1a;"></div>
                                <div class="color-swatch" style="background-color: #ffffff; border: 2px solid #ddd;"></div>
                                <div class="color-swatch" style="background-color: #0073aa;"></div>
                                <div class="color-swatch" style="background-color: #dc3545;"></div>
                            </div>
                        </div>
                        <div class="variation-group">
                            <label class="variation-label">Size:</label>
                            <div class="swatches">
                                <div class="swatch selected">Standard</div>
                                <div class="swatch">Large</div>
                                <div class="swatch">XL</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="quantity-cart">
                        <div class="quantity-input">
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="15">
                            <button onclick="increaseQuantity()">+</button>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>

                    <!-- Buy Now -->
                    <button class="buy-now-btn" onclick="buyNow()">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="action-btn" onclick="addToWishlist()">
                            <i class="far fa-heart"></i>
                            Wishlist
                        </button>
                        <button class="action-btn" onclick="addToCompare()">
                            <i class="fas fa-exchange-alt"></i>
                            Compare
                        </button>
                        <button class="action-btn" onclick="askQuestion()">
                            <i class="far fa-question-circle"></i>
                            Ask Question
                        </button>
                    </div>

                    <!-- Frequently Bought Together -->
                    <div class="frequently-bought">
                        <h3><i class="fas fa-plus-circle" style="color: #0073aa; margin-right: 8px;"></i>Frequently Bought Together</h3>
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
                                    <div class="bundle-item-title">Headphone Stand</div>
                                    <div class="bundle-item-price">$49.99</div>
                                </div>
                            </div>
                            <div class="bundle-item">
                                <input type="checkbox" class="bundle-checkbox" data-price="24.99" onchange="updateBundleTotal()">
                                <img src="demo-image.png" alt="Accessory 2">
                                <div class="bundle-item-info">
                                    <div class="bundle-item-title">Carrying Case</div>
                                    <div class="bundle-item-price">$24.99</div>
                                </div>
                            </div>
                        </div>
                        <div class="total-section">
                            <div class="total-price">Total: $<span id="bundle-total">299.99</span></div>
                            <button class="checkout-btn" onclick="checkoutBundle()">
                                <i class="fas fa-shopping-cart"></i>
                                Add All to Cart
                            </button>
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
                            <button class="tab-button" onclick="switchTab('shipping')">Shipping Info</button>
                        </div>

                        <div id="description" class="tab-content active">
                            <h3>Product Description</h3>
                            <p>Our premium wireless headphones redefine your audio experience with cutting-edge technology and superior comfort. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear sound with deep bass and crisp highs.</p>
                            <p>The advanced active noise cancellation technology blocks out the world around you, allowing you to focus on your music, podcasts, or calls. With up to 40 hours of battery life, you can enjoy your audio all day long.</p>
                        </div>

                        <div id="specifications" class="tab-content">
                            <h3>Technical Specifications</h3>
                            <p><strong>Driver Size:</strong> 40mm Dynamic Drivers</p>
                            <p><strong>Frequency Response:</strong> 20Hz - 20kHz</p>
                            <p><strong>Battery Life:</strong> Up to 40 hours</p>
                            <p><strong>Charging Time:</strong> 2 hours (USB-C)</p>
                            <p><strong>Bluetooth Version:</strong> 5.0 with multipoint connection</p>
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
                                        <p style="margin: 0; font-size: 14px;">Amazing sound quality! The noise cancellation is incredible.</p>
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
                                    <p>We offer a 30-day hassle-free return policy. If you're not completely satisfied with your purchase, you can return it for a full refund.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== INFO BANNER ==================== -->
                <div class="full-width-section">
                    <div class="info-banner">
                        <div class="banner-content">
                            <h2>Why Choose Our Premium Headphones?</h2>
                            <p>Experience the difference with our award-winning wireless headphones, designed for audiophiles and professionals alike.</p>
                            <div class="banner-features">
                                <div class="banner-feature">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>2 Year Warranty</span>
                                </div>
                                <div class="banner-feature">
                                    <i class="fas fa-truck"></i>
                                    <span>Free Shipping</span>
                                </div>
                                <div class="banner-feature">
                                    <i class="fas fa-undo"></i>
                                    <span>30-Day Returns</span>
                                </div>
                                <div class="banner-feature">
                                    <i class="fas fa-headset"></i>
                                    <span>24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RELATED PRODUCTS SLIDER ==================== -->
                <div class="full-width-section">
                    <div class="related-products">
                        <h2 class="section-title">Related Products</h2>
                        <div class="slider-container">
                            <div class="slider-wrapper" id="slider-wrapper">
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 1">
                                    <div class="related-product-info">
                                        <div class="related-product-title">Wireless Earbuds Pro</div>
                                        <div class="related-product-price">$149.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('Wireless Earbuds Pro', 149.99)">Quick Add</button>
                                    </div>
                                </div>
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 2">
                                    <div class="related-product-info">
                                        <div class="related-product-title">Portable Speaker</div>
                                        <div class="related-product-price">$89.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('Portable Speaker', 89.99)">Quick Add</button>
                                    </div>
                                </div>
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 3">
                                    <div class="related-product-info">
                                        <div class="related-product-title">USB-C Hub Adapter</div>
                                        <div class="related-product-price">$49.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('USB-C Hub Adapter', 49.99)">Quick Add</button>
                                    </div>
                                </div>
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 4">
                                    <div class="related-product-info">
                                        <div class="related-product-title">Headphone Stand</div>
                                        <div class="related-product-price">$29.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('Headphone Stand', 29.99)">Quick Add</button>
                                    </div>
                                </div>
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 5">
                                    <div class="related-product-info">
                                        <div class="related-product-title">Carrying Case</div>
                                        <div class="related-product-price">$24.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('Carrying Case', 24.99)">Quick Add</button>
                                    </div>
                                </div>
                                <div class="related-product">
                                    <img src="demo-image.png" alt="Product 6">
                                    <div class="related-product-info">
                                        <div class="related-product-title">Audio Cable 3.5mm</div>
                                        <div class="related-product-price">$14.99</div>
                                        <button class="quick-add-btn" onclick="quickAddToCart('Audio Cable 3.5mm', 14.99)">Quick Add</button>
                                    </div>
                                </div>
                            </div>
                            <div class="slider-nav">
                                <button class="nav-btn" onclick="slideLeft()"><i class="fas fa-chevron-left"></i></button>
                                <button class="nav-btn" onclick="slideRight()"><i class="fas fa-chevron-right"></i></button>
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
            document.querySelectorAll('.grid-image').forEach(item => {
                item.classList.remove('active');
            });
            thumbnail.classList.add('active');
            document.getElementById('main-product-image').src = imageSrc;
        }

        // Quantity Controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) < 15) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Tab Switcher
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Action Functions
        function addToCart() {
            showToast('Added to cart!');
        }

        function buyNow() {
            showToast('Redirecting to checkout...');
        }

        function addToWishlist() {
            showToast('Added to wishlist!');
        }

        function addToCompare() {
            showToast('Added to compare!');
        }

        function askQuestion() {
            showToast('Question form will open');
        }

        function quickAddToCart(productName, price) {
            showToast(`${productName} added to cart!`);
        }

        function submitReview(event) {
            event.preventDefault();
            showToast('Review submitted!');
            event.target.reset();
        }

        // Bundle Total Calculation
        function updateBundleTotal() {
            const checkboxes = document.querySelectorAll('.bundle-checkbox');
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.price);
                }
            });
            document.getElementById('bundle-total').textContent = total.toFixed(2);
        }

        function checkoutBundle() {
            const checkboxes = document.querySelectorAll('.bundle-checkbox:checked');
            const count = checkboxes.length;
            const total = document.getElementById('bundle-total').textContent;
            showToast(`${count} item(s) added to cart! Total: $${total}`);
        }

        // Slider Navigation
        function slideLeft() {
            const slider = document.getElementById('slider-wrapper');
            slider.scrollBy({ left: -300, behavior: 'smooth' });
        }

        function slideRight() {
            const slider = document.getElementById('slider-wrapper');
            slider.scrollBy({ left: 300, behavior: 'smooth' });
        }

        // Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Color Swatch Selection
        document.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.addEventListener('click', () => {
                document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
                swatch.classList.add('selected');
            });
        });

        // Size Swatch Selection
        document.querySelectorAll('.swatch:not(.color-swatch)').forEach(swatch => {
            swatch.addEventListener('click', () => {
                document.querySelectorAll('.swatch:not(.color-swatch)').forEach(s => s.classList.remove('selected'));
                swatch.classList.add('selected');
            });
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
