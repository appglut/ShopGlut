<?php
namespace Shopglut\layouts\singleProduct\templates\template3;

if (!defined('ABSPATH')) {
	exit;
}

// Include template3 AJAX handler
require_once __DIR__ . '/template3-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template3Markup {


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
		<div class="shopglut-single-product template3 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
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

		<div class="single-product-template3">
        <div class="container">
            <!-- Product Main Section -->
            <div class="product-page">
                <!-- Left Side - Product Image/Gallery -->
                <div class="product-image">
                    <div class="main-product-image">
                        <img src="<?php echo esc_url($placeholder_url);  ?>" alt="Product Image">
                    </div>
                    <!-- Thumbnail Gallery -->
                    <div class="thumbnail-gallery">
                        <div class="thumbnail-item active">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image" class="thumbnail-image">
                        </div>
                        <div class="thumbnail-item">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image 2" class="thumbnail-image">
                        </div>
                        <div class="thumbnail-item">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image 3" class="thumbnail-image">
                        </div>
                        <div class="thumbnail-item">
                            <img src="<?php echo esc_url($placeholder_url); ?>" alt="Product Image 4" class="thumbnail-image">
                        </div>
                    </div>
                </div>

                <!-- Right Side - Product Information -->
                <div class="product-info">
                    <!-- Product Badges -->
                    <?php if ($this->getSetting($settings, 'show_demo_badges', true)): ?>
                    <div class="product-badges-container">
                        <span class="product-badge badge-new">New</span>
                        <span class="product-badge badge-sale">-25%</span>
                    </div>
                    <?php endif; ?>

                    <!-- Product Title -->
                    <?php if ($this->getSetting($settings, 'show_demo_title', true)): ?>
                    <h1 class="product-title">Premium Wireless Headphones with Active Noise Cancellation</h1>
                    <?php endif; ?>

                    <!-- Reviews and Rating -->
                    <?php if ($this->getSetting($settings, 'show_demo_rating', true)): ?>
                    <div class="reviews-section">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="reviews-count">4.5 out of 5 (128 reviews)</span>
                    </div>
                    <?php endif; ?>

                    <!-- Price -->
                    <?php if ($this->getSetting($settings, 'show_demo_price', true)): ?>
                    <div class="price-section">
                        <span class="current-price">$299.99</span>
                        <span class="original-price">$399.99</span>
                    </div>
                    <?php endif; ?>

                    <!-- Short Description -->
                    <?php if ($this->getSetting($settings, 'show_demo_description', true)): ?>
                    <div class="short-description">
                        Experience premium sound quality with our latest wireless headphones featuring industry-leading active noise cancellation technology. Perfect for music lovers, travelers, and professionals who demand the best audio experience.
                    </div>
                    <?php endif; ?>

                    <!-- Product Variations -->
                    <?php if ($this->getSetting($settings, 'show_demo_variations', true)): ?>
                    <div class="variations-container">
                        <!-- Color Variation -->
                        <div class="variation-group">
                            <label class="variation-label">Color:</label>
                            <div class="color-options">
                                <div class="color-swatch selected" style="background-color: #1a1a1a;" title="Midnight Black"></div>
                                <div class="color-swatch" style="background-color: #ffffff; border: 2px solid #ddd;" title="Pearl White"></div>
                                <div class="color-swatch" style="background-color: #0073aa;" title="Ocean Blue"></div>
                                <div class="color-swatch" style="background-color: #dc3545;" title="Ruby Red"></div>
                                <div class="color-swatch" style="background-color: #28a745;" title="Forest Green"></div>
                            </div>
                        </div>

                        <!-- Size Variation -->
                        <div class="variation-group">
                            <label class="variation-label">Size:</label>
                            <div class="size-options">
                                <div class="size-option selected">Standard</div>
                                <div class="size-option">Large</div>
                                <div class="size-option">XL</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quantity and Add to Cart -->
                    <?php if ($this->getSetting($settings, 'show_demo_cart_section', true)): ?>
                    <div class="cart-section">
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="15">
                            <button class="quantity-btn" onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if ($this->getSetting($settings, 'show_demo_action_buttons', true)): ?>
                    <div class="action-buttons">
                        <button class="action-btn" onclick="addToWishlist()">
                            <i class="far fa-heart"></i>
                            Wishlist
                        </button>
                        <button class="action-btn" onclick="addToCompare()">
                            <i class="fas fa-exchange-alt"></i>
                            Compare
                        </button>
                      </div>
                    <?php endif; ?>

                    <!-- Social Share -->
                    <?php if ($this->getSetting($settings, 'show_demo_social_share', true)): ?>
                    <div class="social-share">
                        <div class="social-icon" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="social-icon" onclick="shareOnX()">
                            <i class="fab fa-x-twitter"></i>
                        </div>
                        <div class="social-icon" onclick="shareOnPinterest()">
                            <i class="fab fa-pinterest-p"></i>
                        </div>
                        <div class="social-icon" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="social-icon" onclick="shareViaEmail()">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Full Width Product Description -->
                <?php if ($this->getSetting($settings, 'show_demo_description_section', true)): ?>
                <div class="product-description">
                    <h2 class="description-title">Product Description</h2>
                    <div class="description-content">
                        <p>Our premium wireless headphones redefine your audio experience with cutting-edge technology and superior comfort. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear sound with deep bass and crisp highs.</p>

                        <h3>Key Features:</h3>
                        <ul>
                            <li>Advanced Active Noise Cancellation (ANC) technology</li>
                            <li>40mm dynamic drivers for exceptional sound quality</li>
                            <li>40-hour battery life with quick charge support</li>
                            <li>Premium memory foam ear cushions for all-day comfort</li>
                            <li>Bluetooth 5.0 connectivity with aptX support</li>
                            <li>Built-in voice assistant compatibility (Siri, Google Assistant)</li>
                            <li>Foldable design with premium carrying case included</li>
                            <li>Multi-device pairing for seamless switching</li>
                        </ul>

                        <h3>What's in the Box:</h3>
                        <p>Each purchase includes everything you need to start enjoying your music immediately:</p>
                        <ul>
                            <li>Premium Wireless Headphones</li>
                            <li>USB-C Charging Cable</li>
                            <li>3.5mm Audio Cable for wired listening</li>
                            <li>Hard-shell Carrying Case</li>
                            <li>Airplane Adapter</li>
                            <li>Quick Start Guide</li>
                        </ul>

                        <h3>Technical Specifications:</h3>
                        <ul>
                            <li>Driver Size: 40mm Dynamic</li>
                            <li>Frequency Response: 20Hz - 20kHz</li>
                            <li>Impedance: 32 Ohms</li>
                            <li>Sensitivity: 105 dB</li>
                            <li>Battery Life: 40 hours (ANC on), 50 hours (ANC off)</li>
                            <li>Charging Time: 2 hours</li>
                            <li>Bluetooth Version: 5.0</li>
                            <li>Weight: 250g</li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <?php if ($this->getSetting($settings, 'show_demo_reviews_section', true)): ?>
                <div class="reviews-main-section">
                    <div class="reviews-header">
                        <h2>Customer Reviews</h2>
                    </div>

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">JD</div>
                                    <div>
                                        <div class="reviewer-name">John Doe</div>
                                        <div class="review-date">2 days ago</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="review-text">Absolutely incredible headphones! The noise cancellation is top-notch, perfect for my daily commute. The sound quality is crisp and clear with excellent bass response. Battery life is exactly as advertised - I use them for a full week between charges. Highly recommend!</p>
                        </div>

                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">SM</div>
                                    <div>
                                        <div class="reviewer-name">Sarah Mitchell</div>
                                        <div class="review-date">1 week ago</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                            <p class="review-text">Great headphones overall. The comfort level is excellent - I can wear them for hours without any discomfort. The sound quality is impressive, especially for wireless headphones. My only small complaint is that they're a bit bulky for travel, but the included case helps.</p>
                        </div>

                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">MJ</div>
                                    <div>
                                        <div class="reviewer-name">Michael Johnson</div>
                                        <div class="review-date">2 weeks ago</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                            <p class="review-text">Best headphones I've ever owned! The build quality feels premium, and the attention to detail is evident. The quick charge feature is a lifesaver - 15 minutes gives me 3 hours of playback. Worth every penny.</p>
                        </div>

                        <!-- Review Form -->
                        <div class="review-form-container" id="review-form">
                            <h3 class="review-form-title">Write Your Review</h3>
                            <form onsubmit="submitReview(event)">
                                <div class="form-group">
                                    <label class="form-label">Rating *</label>
                                    <div class="star-rating-input" id="star-rating">
                                        <i class="far fa-star" data-rating="1"></i>
                                        <i class="far fa-star" data-rating="2"></i>
                                        <i class="far fa-star" data-rating="3"></i>
                                        <i class="far fa-star" data-rating="4"></i>
                                        <i class="far fa-star" data-rating="5"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" class="form-input" id="reviewer-name" required placeholder="Enter your name">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-input" id="reviewer-email" required placeholder="your.email@example.com">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Review Title</label>
                                    <input type="text" class="form-input" id="review-title" placeholder="Summarize your review">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Your Review *</label>
                                    <textarea class="form-textarea" id="review-content" required placeholder="Share your experience with this product..."></textarea>
                                </div>

                                <button type="submit" class="submit-review-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
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
			'shopglut-template3-frontend',
			SHOPGLUT_URL . 'src/layouts/singleProduct/templates/template3/template3-frontend.js',
			$script_dependencies,
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with necessary data
		wp_localize_script('shopglut-template3-frontend', 'shopglut_frontend_vars', array(
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

			<!-- Product Description Section -->
			<?php if ($this->shouldShowDescriptionSection($settings)): ?>
			<div class="product-description-section" style="<?php echo esc_attr($this->getDescriptionSectionStyles($settings)); ?>">
				<h2 class="description-title" style="<?php echo esc_attr($this->getDescriptionTitleStyles($settings)); ?>">
					<?php echo esc_html($this->getDescriptionTitleText($settings)); ?>
				</h2>
				<div class="description-content" style="<?php echo esc_attr($this->getDescriptionContentStyles($settings)); ?>">
					<?php
					// Get the full product description
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
			</div>
			<?php endif; ?>

			<!-- Product Reviews Section -->
			<?php if ($this->shouldShowReviewsSection($settings) && (comments_open() || $product->get_review_count() > 0)): ?>
			<div class="reviews-section" style="<?php echo esc_attr($this->getReviewsSectionStyles($settings)); ?>">
				<div class="reviews-header">
					<h2 class="reviews-title" style="<?php echo esc_attr($this->getReviewsHeaderStyles($settings)); ?>">
						<?php echo esc_html($this->getReviewsHeaderText($settings)); ?>
					</h2>
				</div>

				<!-- Reviews List -->
				<div class="reviews-list">
					<?php
					// Get reviews from comments
					$args = array(
						'post_id' => $product_id,
						'status' => 'approve',
						'type' => 'review'
					);
					$reviews = get_comments($args);

					if (!empty($reviews)) {
						foreach ($reviews as $review) {
							$rating = get_comment_meta($review->comment_ID, 'rating', true);
							$reviewer_initials = $this->getReviewerInitials($review->comment_author);
							?>
							<div class="review-card" style="<?php echo esc_attr($this->getReviewCardStyles($settings)); ?>">
								<div class="review-header">
									<div class="reviewer-info">
										<div class="reviewer-avatar" style="<?php echo esc_attr($this->getReviewerAvatarStyles($settings)); ?>">
											<?php echo esc_html($reviewer_initials); ?>
										</div>
										<div>
											<div class="reviewer-name" style="<?php echo esc_attr($this->getReviewerNameStyles($settings)); ?>">
												<?php echo esc_html($review->comment_author); ?>
											</div>
											<div class="review-date" style="<?php echo esc_attr($this->getReviewDateStyles($settings)); ?>">
												<?php echo esc_html(gmdate('F j, Y', strtotime($review->comment_date))); ?>
											</div>
										</div>
									</div>
									<div class="review-rating" style="<?php echo esc_attr($this->getReviewRatingStyles($settings)); ?>">
										<?php
										if ($rating) {
											for ($i = 1; $i <= 5; $i++) {
												if ($i <= $rating) {
													echo '<i class="fas fa-star"></i>';
												} else {
													echo '<i class="far fa-star"></i>';
												}
											}
										}
										?>
									</div>
								</div>
								<p class="review-text" style="<?php echo esc_attr($this->getReviewTextStyles($settings)); ?>">
									<?php echo wp_kses_post($review->comment_content); ?>
								</p>
							</div>
							<?php
						}
					} else {
						echo '<p>' . esc_html__('There are no reviews yet.', 'shopglut') . '</p>';
					}
					?>

					<!-- Review Form -->
					<?php if ($this->shouldShowReviewForm($settings) && comments_open()): ?>
					<div class="review-form-container" style="<?php echo esc_attr($this->getReviewFormStyles($settings)); ?>">
						<h3 class="review-form-title" style="<?php echo esc_attr($this->getReviewFormTitleStyles($settings)); ?>">
							<?php echo esc_html($this->getReviewFormTitleText($settings)); ?>
						</h3>
						<?php
						// Get the review form template
						$commenter = wp_get_current_commenter();
						$req = get_option('require_name_email');
						$aria_req = ($req ? " aria-required='true'" : '');

						// Custom review form fields
						?>
						<form action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post" id="commentform" class="comment-form" novalidate>
							<?php do_action('comment_form_before_fields'); ?>
							<div class="form-group">
								<label class="form-label" style="color: <?php echo esc_attr($this->getSetting($settings, 'review_form_label_color', '#374151')); ?>;">
									<?php esc_html_e('Rating *', 'shopglut'); ?>
								</label>
								<div class="star-rating-input" id="star-rating" style="<?php echo esc_attr($this->getReviewRatingStyles($settings)); ?>">
									<?php for ($i = 1; $i <= 5; $i++): ?>
										<i class="far fa-star" data-rating="<?php echo esc_attr($i); ?>"></i>
									<?php endfor; ?>
									<input type="hidden" name="rating" id="rating" value="0" required />
								</div>
							</div>

							<div class="form-group">
								<label class="form-label" style="color: <?php echo esc_attr($this->getSetting($settings, 'review_form_label_color', '#374151')); ?>;">
									<?php esc_html_e('Your Name *', 'shopglut'); ?>
								</label>
								<input type="text" class="form-input" id="author" name="author" required
									   placeholder="<?php esc_attr_e('Enter your name', 'shopglut'); ?>"
									   style="<?php echo esc_attr($this->getReviewFormInputStyles($settings)); ?>"
									   value="<?php echo esc_attr($commenter['comment_author']); ?>" />
							</div>

							<div class="form-group">
								<label class="form-label" style="color: <?php echo esc_attr($this->getSetting($settings, 'review_form_label_color', '#374151')); ?>;">
									<?php esc_html_e('Email Address *', 'shopglut'); ?>
								</label>
								<input type="email" class="form-input" id="email" name="email" required
									   placeholder="<?php esc_attr_e('your.email@example.com', 'shopglut'); ?>"
									   style="<?php echo esc_attr($this->getReviewFormInputStyles($settings)); ?>"
									   value="<?php echo esc_attr($commenter['comment_author_email']); ?>" />
							</div>

							<div class="form-group">
								<label class="form-label" style="color: <?php echo esc_attr($this->getSetting($settings, 'review_form_label_color', '#374151')); ?>;">
									<?php esc_html_e('Your Review *', 'shopglut'); ?>
								</label>
								<textarea class="form-textarea" id="comment" name="comment" required
										  placeholder="<?php esc_attr_e('Share your experience with this product...', 'shopglut'); ?>"
										  style="<?php echo esc_attr($this->getReviewFormInputStyles($settings)); ?>"></textarea>
							</div>

							<input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($product_id); ?>" />
							<input type="hidden" name="comment_parent" value="0" />

							<button type="submit" class="submit-review-btn" style="<?php echo esc_attr($this->getSubmitButtonStyles($settings)); ?>">
								<i class="fas fa-paper-plane"></i>
								<?php esc_html_e('Submit Review', 'shopglut'); ?>
							</button>
							<?php do_action('comment_form_after_fields'); ?>
							<?php do_action('comment_form', $post_id); ?>
						</form>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

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
	 * (All styling is handled in template3Style.php)
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
	 * ==================== PRODUCT DESCRIPTION SETTINGS METHODS ====================
	 */

	/**
	 * Check if description section should be shown
	 */
	private function shouldShowDescriptionSection($settings) {
		return $this->getSetting($settings, 'show_description_section', true);
	}

	/**
	 * Get description section styles
	 */
	private function getDescriptionSectionStyles($settings) {
		$styles = array();
		$styles[] = 'padding: ' . $this->getSetting($settings, 'description_section_padding', 40) . 'px';
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'description_section_background', '#ffffff');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'description_section_border_radius', 0) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get description title styles
	 */
	private function getDescriptionTitleStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'description_title_color', '#111827');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'description_title_font_size', 28) . 'px';
		$styles[] = 'font-weight: ' . $this->getSetting($settings, 'description_title_font_weight', '700');
		$styles[] = 'margin-bottom: ' . $this->getSetting($settings, 'description_title_margin_bottom', 20) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get description title text
	 */
	private function getDescriptionTitleText($settings) {
		return $this->getSetting($settings, 'description_title_text', __('Product Description', 'shopglut'));
	}

	/**
	 * Get description content styles
	 */
	private function getDescriptionContentStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'description_content_color', '#4b5563');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'description_content_font_size', 16) . 'px';
		$styles[] = 'line-height: ' . $this->getSetting($settings, 'description_content_line_height', 1.7);
		return implode('; ', $styles);
	}

	/**
	 * ==================== PRODUCT REVIEWS SETTINGS METHODS ====================
	 */

	/**
	 * Check if reviews section should be shown
	 */
	private function shouldShowReviewsSection($settings) {
		return $this->getSetting($settings, 'show_reviews_section', true);
	}

	/**
	 * Check if review form should be shown
	 */
	private function shouldShowReviewForm($settings) {
		return $this->getSetting($settings, 'show_review_form', true);
	}

	/**
	 * Get reviews section styles
	 */
	private function getReviewsSectionStyles($settings) {
		$styles = array();
		$styles[] = 'padding: ' . $this->getSetting($settings, 'reviews_section_padding', 40) . 'px';
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'reviews_section_background', '#ffffff');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'reviews_section_border_radius', 0) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get reviews header styles
	 */
	private function getReviewsHeaderStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'reviews_header_color', '#111827');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'reviews_header_font_size', 28) . 'px';
		$styles[] = 'font-weight: ' . $this->getSetting($settings, 'reviews_header_font_weight', '700');
		$styles[] = 'margin-bottom: ' . $this->getSetting($settings, 'reviews_header_margin_bottom', 24) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get reviews header text
	 */
	private function getReviewsHeaderText($settings) {
		return $this->getSetting($settings, 'reviews_header_text', __('Customer Reviews', 'shopglut'));
	}

	/**
	 * Get review card styles
	 */
	private function getReviewCardStyles($settings) {
		$styles = array();
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'review_card_background', '#f9fafb');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'review_card_border_radius', 12) . 'px';
		$styles[] = 'padding: ' . $this->getSetting($settings, 'review_card_padding', 20) . 'px';
		$styles[] = 'margin-bottom: ' . $this->getSetting($settings, 'review_card_margin_bottom', 16) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get reviewer avatar styles
	 */
	private function getReviewerAvatarStyles($settings) {
		$styles = array();
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'reviewer_avatar_background', '#667eea');
		$styles[] = 'color: ' . $this->getSetting($settings, 'reviewer_avatar_text_color', '#ffffff');
		return implode('; ', $styles);
	}

	/**
	 * Get reviewer name styles
	 */
	private function getReviewerNameStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'reviewer_name_color', '#111827');
		$styles[] = 'font-weight: ' . $this->getSetting($settings, 'reviewer_name_font_weight', '600');
		return implode('; ', $styles);
	}

	/**
	 * Get review date styles
	 */
	private function getReviewDateStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'review_date_color', '#9ca3af');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'review_date_font_size', 12) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get review rating styles
	 */
	private function getReviewRatingStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'review_star_color', '#fbbf24');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'review_star_size', 16) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get review text styles
	 */
	private function getReviewTextStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'review_text_color', '#4b5563');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'review_text_font_size', 14) . 'px';
		$styles[] = 'line-height: ' . $this->getSetting($settings, 'review_text_line_height', 1.6);
		return implode('; ', $styles);
	}

	/**
	 * Get review form styles
	 */
	private function getReviewFormStyles($settings) {
		$styles = array();
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'review_form_background', '#f9fafb');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'review_form_border_radius', 12) . 'px';
		$styles[] = 'padding: ' . $this->getSetting($settings, 'review_form_padding', 24) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get review form title styles
	 */
	private function getReviewFormTitleStyles($settings) {
		$styles = array();
		$styles[] = 'color: ' . $this->getSetting($settings, 'review_form_title_color', '#111827');
		$styles[] = 'font-size: ' . $this->getSetting($settings, 'review_form_title_font_size', 22) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get review form title text
	 */
	private function getReviewFormTitleText($settings) {
		return $this->getSetting($settings, 'review_form_title', __('Write Your Review', 'shopglut'));
	}

	/**
	 * Get review form input styles
	 */
	private function getReviewFormInputStyles($settings) {
		$styles = array();
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'review_form_input_background', '#ffffff');
		$styles[] = 'border-color: ' . $this->getSetting($settings, 'review_form_input_border_color', '#d1d5db');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'review_form_input_border_radius', 6) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get submit button styles
	 */
	private function getSubmitButtonStyles($settings) {
		$styles = array();
		$styles[] = 'background-color: ' . $this->getSetting($settings, 'submit_button_background', '#667eea');
		$styles[] = 'color: ' . $this->getSetting($settings, 'submit_button_text_color', '#ffffff');
		$styles[] = 'border-radius: ' . $this->getSetting($settings, 'submit_button_border_radius', 6) . 'px';
		return implode('; ', $styles);
	}

	/**
	 * Get reviewer initials from name
	 */
	private function getReviewerInitials($name) {
		$words = explode(' ', trim($name));
		$initials = '';
		foreach ($words as $word) {
			if (!empty($word)) {
				$initials .= strtoupper(substr($word, 0, 1));
				if (strlen($initials) >= 2) {
					break;
				}
			}
		}
		return $initials ?: 'U';
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
			if (isset($settings['shopg_singleproduct_settings_template3']['single-product-settings'])) {
				return $this->flattenSettings($settings['shopg_singleproduct_settings_template3']['single-product-settings']);
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

// Add social sharing functions
add_action('wp_footer', function() {
	?>
	<script type="text/javascript">
	// Social Share Functions for Template3
	function shareOnFacebook() {
		const url = encodeURIComponent(window.location.href);
		window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
	}

	function shareOnX() {
		const url = encodeURIComponent(window.location.href);
		const title = encodeURIComponent(document.title || 'Check out this product!');
		window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
	}

	function shareOnPinterest() {
		const url = encodeURIComponent(window.location.href);
		const title = encodeURIComponent(document.title || 'Check out this product!');
		window.open(`https://pinterest.com/pin/create/button/?url=${url}&description=${title}`, '_blank', 'width=600,height=400');
	}

	function shareOnWhatsApp() {
		const url = encodeURIComponent(window.location.href);
		const title = encodeURIComponent(document.title || 'Check out this product!');
		window.open(`https://wa.me/?text=${title}%20${url}`, '_blank');
	}

	function shareViaEmail() {
		const url = window.location.href;
		const title = document.title || 'Check out this product!';
		const subject = encodeURIComponent(title);
		const body = encodeURIComponent(`I thought you might be interested in this product:\n\n${url}`);
		window.location.href = `mailto:?subject=${subject}&body=${body}`;
	}

	// Add backward compatibility for old function name
	function shareOnTwitter() {
		shareOnX();
	}
	</script>
	<?php
});