<?php
namespace Shopglut\layouts\singleProduct\templates\template5;

if (!defined('ABSPATH')) {
	exit;
}

// Include template5 AJAX handler
require_once __DIR__ . '/template5-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template5Markup {


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
		<div class="shopglut-single-product template5 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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

	 <div class="single-product-template5">
    <div class="container">
        <!-- Product Main Section -->
        <div class="product-container">
            <!-- Left Side - Image Gallery (60%) -->
            <div class="product-gallery">
                <div class="main-image-container">
                    <img src="<?php echo esc_url($placeholder_url); ?>" alt="Premium Wireless Headphones" class="main-image" id="mainImage">
                </div>

                <div class="thumbnail-container">
                    <button class="slider-btn slider-prev" onclick="slideThumbnails('prev')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-btn slider-next" onclick="slideThumbnails('next')">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div class="thumbnails-wrapper">
                        <div class="thumbnails" id="thumbnails">
                            <div class="thumbnail active" onclick="changeImage(0)">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphones 1">
                            </div>
                            <div class="thumbnail" onclick="changeImage(1)">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphones 2">
                            </div>
                            <div class="thumbnail" onclick="changeImage(2)">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphones 3">
                            </div>
                            <div class="thumbnail" onclick="changeImage(3)">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphones 4">
                            </div>
                            <div class="thumbnail" onclick="changeImage(4)">
                                <img src="<?php echo esc_url($placeholder_url); ?>" alt="Headphones 5">
                            </div>
                          </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Product Details (40%) -->
            <div class="product-details">
                <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>

                <div class="reviews">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="review-count">(245 Reviews)</span>
                </div>

                <div class="price">
                    $299.99
                    <span class="original-price">$399.99</span>
                </div>

                <div class="short-description">
                    Experience premium sound quality with our latest wireless headphones. Featuring advanced noise cancellation, 30-hour battery life, and superior comfort for all-day wear.
                </div>

                <div class="product-meta">
                    <span><strong>SKU:</strong> WH-1000XM4</span>
                    <span><strong>Categories:</strong> Electronics, Audio, Headphones</span>
                </div>

                <!-- Product Options -->
                <div class="product-options">
                    <!-- Color Options -->
                    <div class="option-group">
                        <label class="option-label">Color</label>
                        <div class="color-options">
                            <div class="color-option selected" style="background: #000000;" onclick="selectColor(this)"></div>
                            <div class="color-option" style="background: #ffffff; border: 2px solid #dee2e6;" onclick="selectColor(this)"></div>
                            <div class="color-option" style="background: #007bff;" onclick="selectColor(this)"></div>
                            <div class="color-option" style="background: #dc3545;" onclick="selectColor(this)"></div>
                        </div>
                    </div>

                    <!-- Size Options -->
                    <div class="option-group">
                        <label class="option-label">Size</label>
                        <div class="size-options">
                            <div class="size-option" onclick="selectSize(this)">Standard</div>
                            <div class="size-option selected" onclick="selectSize(this)">Large</div>
                            <div class="size-option" onclick="selectSize(this)">Extra Large</div>
                        </div>
                    </div>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="quantity-cart">
                    <div class="quantity-selector">
                        <button class="quantity-btn" onclick="decreaseQuantity()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="10" readonly>
                        <button class="quantity-btn" onclick="increaseQuantity()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <button class="add-to-cart" onclick="addToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>

                <!-- Buy Now Button -->
                <button class="buy-now" onclick="buyNow()">
                    Buy Now
                </button>

                
                <!-- Payment Options -->
                <div class="payment-options">
                    <div class="payment-title">
                        <i class="fas fa-lock"></i>
                        Guaranteed Secure Checkout
                    </div>
                    <div class="payment-icons">
                        <div class="payment-icon">VISA</div>
                        <div class="payment-icon">MC</div>
                        <div class="payment-icon">AMEX</div>
                        <div class="payment-icon">PP</div>
                        <div class="payment-icon">GPAY</div>
                        <div class="payment-icon">APAY</div>
                    </div>
                </div>

                <!-- Share Options -->
                <div class="share-options">
                    <div class="share-label">Share Product</div>
                    <div class="social-icons">
                        <div class="social-icon facebook" onclick="shareOn('facebook')">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="social-icon twitter" onclick="shareOn('x')">
                            <i class="fab fa-x-twitter"></i>
                        </div>
                        <div class="social-icon instagram" onclick="shareOn('instagram')">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <div class="social-icon pinterest" onclick="shareOn('pinterest')">
                            <i class="fab fa-pinterest-p"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="showTab('description')">Description</button>
                <button class="tab-btn" onclick="showTab('specifications')">Specifications</button>
                <button class="tab-btn" onclick="showTab('reviews')">Reviews</button>
                <button class="tab-btn" onclick="showTab('shipping')">Shipping</button>
            </div>

            <div class="tab-content active" id="description">
                <h3>Product Description</h3>
                <p>Our Premium Wireless Headphones deliver an exceptional audio experience with cutting-edge technology. Immerse yourself in crystal-clear sound with active noise cancellation that blocks out unwanted ambient noise, allowing you to focus on what matters - your music.</p>
                <br>
                <p>Key features include:</p>
                <ul>
                    <li>Active Noise Cancellation (ANC) technology</li>
                    <li>30-hour battery life with quick charge</li>
                    <li>Premium memory foam ear cushions</li>
                    <li>Bluetooth 5.0 connectivity</li>
                    <li>Built-in microphone for hands-free calls</li>
                    <li>Folding design for easy portability</li>
                </ul>
            </div>

            <div class="tab-content" id="specifications">
                <h3>Technical Specifications</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Driver Size:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">40mm Dynamic Drivers</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Frequency Response:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">20Hz - 20kHz</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Impedance:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">32 Ohms</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Battery Life:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">30 hours (ANC on), 40 hours (ANC off)</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Charging Time:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">2 hours (15 min charge = 3 hours playback)</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;"><strong>Weight:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e9ecef;">250g</td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="reviews">
                <h3>Customer Reviews</h3>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="font-weight: 600;">John D.</span>
                        <div class="stars" style="color: #ffc107;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p style="color: #6c757d;">"Amazing sound quality! The noise cancellation is incredible. Worth every penny."</p>
                </div>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="font-weight: 600;">Sarah M.</span>
                        <div class="stars" style="color: #ffc107;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <p style="color: #6c757d;">"Very comfortable for long flights. Battery life is exactly as advertised."</p>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="font-weight: 600;">Mike R.</span>
                        <div class="stars" style="color: #ffc107;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p style="color: #6c757d;">"Great build quality and features. The only downside is they're a bit pricey."</p>
                </div>
            </div>

            <div class="tab-content" id="shipping">
                <h3>Shipping & Returns</h3>
                <p><strong>Free Shipping:</strong> On all orders over $50</p>
                <p><strong>Standard Delivery:</strong> 5-7 business days</p>
                <p><strong>Express Delivery:</strong> 2-3 business days</p>
                <p><strong>Next Day Delivery:</strong> Order before 12 PM for next day delivery</p>
                <br>
                <p><strong>Returns Policy:</strong></p>
                <ul>
                    <li>30-day return policy from date of delivery</li>
                    <li>Items must be unused and in original packaging</li>
                    <li>Free returns on all orders</li>
                    <li>Refunds processed within 5-7 business days</li>
                </ul>
            </div>

          </div>

        <!-- Related Products Section -->
        <div class="related-products">
            <h2 class="section-title">Related products</h2>
            <div class="products-grid">
                <div class="product-card">
                    <img src="<?php echo esc_url($placeholder_url); ?>" alt="Wireless Earbuds" class="product-card-image">
                    <div class="product-card-content">
                        <h3 class="product-card-title">Wireless Earbuds Pro</h3>
                        <div class="product-card-price">$149.99</div>
                    </div>
                </div>
                <div class="product-card">
                    <img src="<?php echo esc_url($placeholder_url); ?>" alt="Gaming Headset" class="product-card-image">
                    <div class="product-card-content">
                        <h3 class="product-card-title">Gaming Headset RGB</h3>
                        <div class="product-card-price">$89.99</div>
                    </div>
                </div>
                <div class="product-card">
                    <img src="<?php echo esc_url($placeholder_url); ?>" alt="Studio Headphones" class="product-card-image">
                    <div class="product-card-content">
                        <h3 class="product-card-title">Studio Monitor Headphones</h3>
                        <div class="product-card-price">$199.99</div>
                    </div>
                </div>
                <div class="product-card">
                    <img src="<?php echo esc_url($placeholder_url); ?>" alt="Sports Earphones" class="product-card-image">
                    <div class="product-card-content">
                        <h3 class="product-card-title">Sports Bluetooth Earphones</h3>
                        <div class="product-card-price">$59.99</div>
                    </div>
                </div>
            </div>
        </div>

    <script>
        // Image Gallery
        const mainImages = [
            '<?php echo esc_js($placeholder_url); ?>',
            '<?php echo esc_js($placeholder_url); ?>',
            '<?php echo esc_js($placeholder_url); ?>',
            '<?php echo esc_js($placeholder_url); ?>',
            '<?php echo esc_js($placeholder_url); ?>'
        ];

        function changeImage(index) {
            document.getElementById('mainImage').src = mainImages[index];

            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }

        let currentSlide = 0;
        const thumbnails = document.getElementById('thumbnails');
        const thumbnailCount = thumbnails.children.length;
        const visibleThumbnails = 5;

        function slideThumbnails(direction) {
            const maxSlide = thumbnailCount - visibleThumbnails;

            if (direction === 'next') {
                currentSlide = Math.min(currentSlide + 1, maxSlide);
            } else {
                currentSlide = Math.max(currentSlide - 1, 0);
            }

            const slideWidth = 100 / visibleThumbnails;
            thumbnails.style.transform = `translateX(-${currentSlide * slideWidth}%)`;
        }

        // Color Selection
        function selectColor(element) {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        // Size Selection
        function selectSize(element) {
            document.querySelectorAll('.size-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        // Quantity Controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue < 10) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        // Cart Functions
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            alert(`Added ${quantity} item(s) to cart!`);
        }

        function buyNow() {
            alert('Redirecting to checkout...');
        }

        // Share Functions
        function shareOn(platform) {
            const url = window.location.href;
            const title = 'Check out this amazing product!';

            switch(platform) {
                case 'facebook':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
                    break;
                case 'x':
                case 'twitter':
                    // X.com (formerly Twitter) - use twitter.com/intent for sharing
                    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
                    break;
                case 'pinterest':
                    window.open(`https://pinterest.com/pin/create/button/?url=${url}`, '_blank');
                    break;
                default:
                    alert(`Share on ${platform}`);
            }
        }

        // Tab Functions
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>

    </div>
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
			'shopglut-template5-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template5/template5-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template5-frontend', 'shopglut_frontend_vars', array(
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
	 * (All styling is handled in template5Style.php)
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
			if (isset($settings['shopg_singleproduct_settings_template5']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template5']['single-product-settings']);
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