<?php
namespace Shopglut\layouts\singleProduct\templates\template4;

if (!defined('ABSPATH')) {
	exit;
}

// Include template4 AJAX handler
require_once __DIR__ . '/template4-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template4Markup {


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
		<div class="shopglut-single-product template4 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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

		<div class="single-product-template4">
        <div class="container">
            <div class="product-main">
                <div class="product-showcase">
                <div class="main-image">
                    <img src="<?php echo esc_url($placeholder_url);   ?>" alt="Product" style="width:100%;height:100%;object-fit:cover;border-radius:16px;">
                </div>
                <div class="image-nav prev">‹</div>
                <div class="image-nav next">›</div>
            </div>
            <div class="product-details">
                <div class="badge">Product Badge</div>
                <h1>Elite Professional Series</h1>
                <div class="rating">
                    <div class="stars">★★★★★</div>
                    <span>(4.9/5 • 2,847 reviews)</span>
                </div>
                <div class="price-section">
                    <span class="current-price">$599</span>
                    <span class="original-price">$799</span>
                </div>
                <div class="description">
                    Engineered for professionals who demand excellence. This premium edition features cutting-edge technology, superior materials, and uncompromising performance.
                </div>
                <div class="premium-features">
                    <ul class="feature-list">
                        <li>Professional-grade construction</li>
                        <li>Advanced noise cancellation</li>
                        <li>Wireless charging capability</li>
                        <li>Premium leather finish</li>
                        <li>2-year warranty included</li>
                    </ul>
                </div>
                <div class="cta-section">
                    <button class="primary-btn">Buy Now</button>
                    <button class="secondary-btn">Add to Cart</button>
                    <button class="wishlist-btn">♡</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Product Tabs Section -->
        <div class="container">
            <div class="product-tabs">
                <div class="tab-navigation">
                    <button class="tab-btn active" data-tab="description">Description</button>
                    <button class="tab-btn" data-tab="specifications">Specifications</button>
                    <button class="tab-btn" data-tab="reviews">Reviews</button>
                    <button class="tab-btn" data-tab="shipping">Shipping</button>
                </div>

                <div class="tab-content active" id="description">
                    <h3>Product Description</h3>
                    <p>Experience the ultimate in professional-grade audio technology with our Elite Professional Series. Engineered for discerning professionals and audiophiles who demand nothing but excellence in their audio equipment.</p>
                    <p>This premium edition features cutting-edge technology that delivers unparalleled sound quality, superior materials that ensure durability, and uncompromising performance that meets the highest industry standards. Every component has been meticulously crafted to provide an exceptional audio experience.</p>
                    <p>The advanced design incorporates the latest innovations in acoustic engineering, resulting in crystal-clear sound reproduction, exceptional comfort for extended use, and seamless integration with professional audio systems.</p>
                </div>

                <div class="tab-content" id="specifications">
                    <h3>Technical Specifications</h3>
                    <div class="specifications-grid">
                        <div>
                            <div class="spec-row">
                                <span class="spec-label">Driver Type</span>
                                <span class="spec-value">50mm Planar Magnetic</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Frequency Response</span>
                                <span class="spec-value">15Hz - 25kHz</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Impedance</span>
                                <span class="spec-value">50 Ohms</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Sensitivity</span>
                                <span class="spec-value">110dB SPL</span>
                            </div>
                        </div>
                        <div>
                            <div class="spec-row">
                                <span class="spec-label">Battery Life</span>
                                <span class="spec-value">40 hours</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Charging Time</span>
                                <span class="spec-value">3 hours</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Connectivity</span>
                                <span class="spec-value">Bluetooth 5.3, USB-C</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">Weight</span>
                                <span class="spec-value">320g</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="reviews">
                    <h3>Customer Reviews</h3>

                    <div class="reviews-summary">
                        <div class="rating-overview">
                            <div class="overall-rating">
                                <div class="rating-number">4.9</div>
                                <div class="rating-stars">★★★★★</div>
                                <div class="rating-count">Based on 2,847 reviews</div>
                            </div>
                            <div class="rating-breakdown">
                                <div class="rating-bar">
                                    <span class="star-count">5 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 89%"></div>
                                    </div>
                                    <span class="bar-percentage">89%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-count">4 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 8%"></div>
                                    </div>
                                    <span class="bar-percentage">8%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-count">3 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 2%"></div>
                                    </div>
                                    <span class="bar-percentage">2%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-count">2 stars</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 0.5%"></div>
                                    </div>
                                    <span class="bar-percentage">0.5%</span>
                                </div>
                                <div class="rating-bar">
                                    <span class="star-count">1 star</span>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 0.5%"></div>
                                    </div>
                                    <span class="bar-percentage">0.5%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Alex Chen</span>
                            <span class="review-rating">★★★★★</span>
                        </div>
                        <div class="review-text">
                            Outstanding professional quality! These headphones have completely transformed my mixing sessions. The clarity and detail are simply incredible. Worth every penny for serious audio work.
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Jennifer Rodriguez</span>
                            <span class="review-rating">★★★★★</span>
                        </div>
                        <div class="review-text">
                            As a mastering engineer, I'm extremely picky about headphones. These exceeded my expectations in every way. The frequency response is incredibly flat and the build quality is top-notch.
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Michael Thompson</span>
                            <span class="review-rating">★★★★★</span>
                        </div>
                        <div class="review-text">
                            Perfect for professional use. The comfort level is amazing even during 8+ hour sessions. The noise cancellation is phenomenal and the wireless connectivity is rock solid.
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="shipping">
                    <h3>Shipping & Returns</h3>
                    <p><strong>Free Express Shipping:</strong> 2-3 business days for orders over $100</p>
                    <p><strong>Standard Shipping:</strong> 5-7 business days - $15.99</p>
                    <p><strong>Next Day Delivery:</strong> Available in major cities - $29.99</p>
                    <p><strong>International Shipping:</strong> Available worldwide, 7-21 business days</p>
                    <p>All orders are processed within 1 business day. Professional audio equipment is carefully packaged to ensure safe delivery.</p>
                    <p><strong>Return Policy:</strong> 45-day professional evaluation period. If these headphones don't meet your professional standards, return them for a full refund.</p>
                    <p><strong>Warranty:</strong> 2-year professional warranty with dedicated technical support for audio professionals.</p>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="related-products">
            <div class="container">
                <h2 class="section-title">Professional Audio Collection</h2>
                <div class="products-grid">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url);   ?>" alt="Studio Monitors" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        </div>
                        <div class="product-name">Reference Studio Monitors</div>
                        <div class="product-price">$899.99</div>
                        <button class="quick-add-btn">Quick Add</button>
                    </div>

                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url);   ?>" alt="Audio Interface" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        </div>
                        <div class="product-name">Pro Audio Interface</div>
                        <div class="product-price">$449.99</div>
                        <button class="quick-add-btn">Quick Add</button>
                    </div>

                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url);   ?>" alt="Microphone" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        </div>
                        <div class="product-name">Condenser Microphone</div>
                        <div class="product-price">$329.99</div>
                        <button class="quick-add-btn">Quick Add</button>
                    </div>

                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo esc_url($placeholder_url);   ?>" alt="Cable Kit" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        </div>
                        <div class="product-name">Professional Cable Kit</div>
                        <div class="product-price">$159.99</div>
                        <button class="quick-add-btn">Quick Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Interactive elements for Design 2
        document.addEventListener('DOMContentLoaded', function() {
            // Image navigation
            document.querySelector('.prev').addEventListener('click', function() {
                // Previous image logic would go here
                console.log('Previous image');
            });

            document.querySelector('.next').addEventListener('click', function() {
                // Next image logic would go here
                console.log('Next image');
            });

            // Button interactions
            document.querySelector('.primary-btn').addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Processing...';
                this.style.background = 'linear-gradient(135deg, #059669, #047857)';

                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = '';
                }, 2000);
            });

            document.querySelector('.secondary-btn').addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Added!';
                this.style.background = 'rgba(255,255,255,0.1)';
                this.style.borderColor = '#10b981';

                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = '';
                    this.style.borderColor = '';
                }, 2000);
            });

            // Wishlist functionality
            document.querySelector('.wishlist-btn').addEventListener('click', function() {
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    this.textContent = '♥';
                } else {
                    this.textContent = '♡';
                }
            });

            // Tab functionality
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active class from all tabs and content
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Quick add buttons for related products
            document.querySelectorAll('.quick-add-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const originalText = this.textContent;
                    this.textContent = 'Added!';
                    this.style.background = '#10b981';
                    this.style.borderColor = '#10b981';
                    this.style.color = 'white';

                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.background = '';
                        this.style.borderColor = '';
                        this.style.color = '';
                    }, 1500);
                });
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
			'shopglut-template4-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template4/template4-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template4-frontend', 'shopglut_frontend_vars', array(
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
	 * (All styling is handled in template4Style.php)
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
			if (isset($settings['shopg_singleproduct_settings_template4']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template4']['single-product-settings']);
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