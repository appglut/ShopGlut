<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro6;

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

	 <div class="shopglut-single-templatePro6">
        <!-- Product Page -->
        <div class="product-page">
            <!-- Full Width Image Carousel -->
            <div class="product-carousel-section">
                <div class="product-slider-container">
                    <button class="slider-nav slider-prev" onclick="slidePrev()">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="slider-nav slider-next" onclick="slideNext()">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <div class="product-slider" id="productSlider">
                        <div class="slider-item" onclick="selectImage(0)">
                            <img src="https://picsum.photos/seed/headphones1/400/300.jpg" alt="Premium Wireless Headphones">
                            <div class="slider-info">
                                <h5>Main Product</h5>
                                <p>Premium Wireless Headphones</p>
                            </div>
                        </div>
                        <div class="slider-item" onclick="selectImage(1)">
                            <img src="https://picsum.photos/seed/headphones2/400/300.jpg" alt="Side View">
                            <div class="slider-info">
                                <h5>Side View</h5>
                                <p>Comfortable ear cushions</p>
                            </div>
                        </div>
                        <div class="slider-item" onclick="selectImage(2)">
                            <img src="https://picsum.photos/seed/headphones3/400/300.jpg" alt="Detail View">
                            <div class="slider-info">
                                <h5>Detail View</h5>
                                <p>Adjustable headband</p>
                            </div>
                        </div>
                        <div class="slider-item" onclick="selectImage(3)">
                            <img src="https://picsum.photos/seed/headphones4/400/300.jpg" alt="Features">
                            <div class="slider-info">
                                <h5>Features</h5>
                                <p>Touch controls</p>
                            </div>
                        </div>
                        <div class="slider-item" onclick="selectImage(4)">
                            <img src="https://picsum.photos/seed/headphones5/400/300.jpg" alt="Accessories">
                            <div class="slider-info">
                                <h5>Accessories</h5>
                                <p>What's in the box</p>
                            </div>
                        </div>
                        <div class="slider-item" onclick="selectImage(5)">
                            <img src="https://picsum.photos/seed/headphones6/400/300.jpg" alt="Color Options">
                            <div class="slider-info">
                                <h5>Colors</h5>
                                <p>Multiple color options</p>
                            </div>
                        </div>
                    </div>

                    <div class="slider-indicators">
                        <div class="indicator active" onclick="goToSlide(0)"></div>
                        <div class="indicator" onclick="goToSlide(1)"></div>
                        <div class="indicator" onclick="goToSlide(2)"></div>
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="product-details-section">
                <div class="container-fluid px-4">
                    <div class="product-container">
                        <div class="row">
                            <!-- Left Section -->
                            <div class="col-lg-5">
                                <div class="left-section">
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

                                    <!-- Product Title -->
                                    <h1 class="product-title">Premium Wireless Noise-Canceling Headphones</h1>

                                    <!-- Price Section -->
                                    <div class="price-section">
                                        <span class="current-price">$189.99</span>
                                        <span class="original-price">$299.99</span>
                                        <span class="discount-badge">-37%</span>
                                    </div>

                                    <!-- Stock Information -->
                                    <div class="stock-info in-stock">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>
                                            <strong>In Stock</strong>
                                            <p class="mb-0">Only 5 items left</p>
                                        </div>
                                    </div>

                                    <!-- Product Meta Information -->
                                    <div class="product-meta">
                                        <div class="meta-item">
                                            <span class="meta-label">SKU:</span>
                                            <span class="meta-value">WH-NC-2023-01</span>
                                        </div>
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
                                    </div>

                                    <!-- Quantity Section -->
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

                                    <!-- Action Buttons -->
                                    <div class="action-buttons">
                                        <button class="btn-buy-now" onclick="buyNow()">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            Buy Now
                                        </button>
                                    </div>

                                    <!-- Wishlist and Compare -->
                                    <div class="wishlist-compare">
                                        <button class="btn-wishlist" onclick="addToWishlist()">
                                            <i class="bi bi-heart"></i> Add to Wishlist
                                        </button>
                                        <button class="btn-compare" onclick="addToCompare()">
                                            <i class="bi bi-arrow-left-right"></i> Compare
                                        </button>
                                    </div>

                                    <!-- Payment Options -->
                                    <div class="payment-options">
                                        <h5><i class="bi bi-credit-card"></i> Payment Options</h5>
                                        <div class="payment-methods">
                                            <div class="payment-method">VISA</div>
                                            <div class="payment-method">MC</div>
                                            <div class="payment-method">AMEX</div>
                                            <div class="payment-method">PP</div>
                                            <div class="payment-method">GPay</div>
                                        </div>
                                    </div>

                                    <!-- Delivery Information -->
                                    <div class="delivery-info">
                                        <h5><i class="bi bi-truck"></i> Delivery Information</h5>
                                        <ul>
                                            <li><i class="bi bi-check-circle"></i> Free shipping on orders over $50</li>
                                            <li><i class="bi bi-check-circle"></i> Standard: 3-5 business days</li>
                                            <li><i class="bi bi-check-circle"></i> Express: 1-2 business days</li>
                                            <li><i class="bi bi-check-circle"></i> 30 days return policy</li>
                                        </ul>
                                    </div>

                                    <!-- Ask a Question -->
                                    <button class="btn-ask-question" onclick="askQuestion()">
                                        <i class="bi bi-question-circle"></i> Ask a Question
                                    </button>
                                </div>
                            </div>

                            <!-- Right Section - Tabs -->
                            <div class="col-lg-7">
                                <div class="right-section">
                                    <div class="tabs-container">
                                        <!-- Horizontal Tabs Navigation -->
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
                                                <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab" aria-controls="additional" aria-selected="false">Additional Info</button>
                                            </li>
                                        </ul>

                                        <!-- Tab Content -->
                                        <div class="tab-content" id="productTabContent">
                                            <!-- Description Tab -->
                                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                                <h4>Product Description</h4>
                                                <p>Experience premium sound quality with our state-of-the-art wireless headphones. Designed for audiophiles and casual listeners alike, these headphones deliver crystal-clear audio with deep bass and crisp highs.</p>

                                                <p>Featuring advanced noise-canceling technology, these headphones create an immersive listening experience by blocking out unwanted ambient noise. Whether you're commuting, working, or relaxing at home, you'll enjoy your music without distractions.</p>

                                                <p>The ergonomic design ensures all-day comfort with soft ear cushions and an adjustable headband. With up to 30 hours of battery life on a single charge, you can enjoy your favorite playlists, podcasts, and calls throughout the day without interruption.</p>

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

                                                <p>Intuitive touch controls make it easy to manage your music, adjust volume, and take calls without reaching for your device. The built-in microphone with noise reduction ensures clear voice quality during phone calls and virtual meetings.</p>
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

                                            <!-- Additional Info Tab -->
                                            <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
                                                <h4>Additional Information</h4>
                                                <p>Warranty information and other important details about your purchase.</p>

                                                <h5>Warranty</h5>
                                                <p>All our products come with a 1-year manufacturer warranty that covers any defects in materials or workmanship. Extended warranty options are available at checkout for an additional fee.</p>

                                                <h5>Care Instructions</h5>
                                                <ul>
                                                    <li>Clean ear cushions with a soft, dry cloth</li>
                                                    <li>Store in the included carrying case when not in use</li>
                                                    <li>Avoid exposure to extreme temperatures and moisture</li>
                                                    <li>Charge the headphones at room temperature</li>
                                                </ul>

                                                <h5>Compatibility</h5>
                                                <p>These headphones are compatible with:</p>
                                                <ul>
                                                    <li>iOS devices (iPhone, iPad, iPod)</li>
                                                    <li>Android devices</li>
                                                    <li>Windows and Mac computers</li>
                                                    <li>Any device with Bluetooth connectivity</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            <div class="related-products">
                <div class="container-fluid px-4">
                    <h2 class="section-title">Related Products</h2>
                    <div class="product-grid">
                        <!-- Product 1 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/earbuds1/300/300.jpg" alt="Wireless Earbuds">
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
                                <img src="https://picsum.photos/seed/speaker1/300/300.jpg" alt="Bluetooth Speaker">
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
                                <img src="https://picsum.photos/seed/headphones2/300/300.jpg" alt="Studio Headphones">
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
                                <img src="https://picsum.photos/seed/earbuds2/300/300.jpg" alt="Sports Earbuds">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Slider functionality
        let currentSlide = 0;
        const slider = document.getElementById('productSlider');
        const totalSlides = 3; // Number of slides (3 items visible at a time)
        const itemsPerSlide = 3; // Items visible per slide
        const totalItems = 6; // Total number of items

        function slidePrev() {
            if (currentSlide > 0) {
                currentSlide--;
            } else {
                currentSlide = totalSlides - 1; // Loop to last slide
            }
            updateSlider();
        }

        function slideNext() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
            } else {
                currentSlide = 0; // Loop to first slide
            }
            updateSlider();
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateSlider();
        }

        function updateSlider() {
            const translateX = -currentSlide * 100; // Each slide moves 100%
            slider.style.transform = `translateX(${translateX}%)`;

            // Update indicators
            const indicators = document.querySelectorAll('.indicator');
            indicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        }

        function selectImage(index) {
            alert(`Selected image ${index + 1}: Image zoom/view functionality would be implemented here`);
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

        // Keyboard navigation for slider
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft') {
                slidePrev();
            } else if (event.key === 'ArrowRight') {
                slideNext();
            }
        });

        // Auto-slide functionality (optional)
        let autoSlideInterval;
        let autoSlideEnabled = false;

        function startAutoSlide() {
            if (autoSlideEnabled) return;
            autoSlideEnabled = true;
            autoSlideInterval = setInterval(slideNext, 3000);
        }

        function stopAutoSlide() {
            autoSlideEnabled = false;
            clearInterval(autoSlideInterval);
        }

        // Pause auto-slide on hover
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        // Uncomment the following line to enable auto-slide by default
        // startAutoSlide();
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
