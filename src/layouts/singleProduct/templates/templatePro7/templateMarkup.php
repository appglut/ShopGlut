<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro7;

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
<div class="shopglut-single-templatePro7">
        <!-- Product Page -->
        <div class="product-page">
            <div class="container-fluid px-4">
                <div class="product-container">
                    <div class="row">
                        <!-- Left Section - Image Gallery -->
                        <div class="col-lg-3 col-md-4">
                            <div class="left-section">
                                <div class="main-product-image">
                                    <img id="mainProductImage" src="https://picsum.photos/seed/book1/400/600.jpg" alt="The Hunter Series Book">
                                </div>
                                <div class="image-thumbnails">
                                    <div class="thumbnail active" onclick="changeProductImage('book1')">
                                        <img src="https://picsum.photos/seed/book1/150/150.jpg" alt="Front Cover">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('book2')">
                                        <img src="https://picsum.photos/seed/book2/150/150.jpg" alt="Back Cover">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('book3')">
                                        <img src="https://picsum.photos/seed/book3/150/150.jpg" alt="Side View">
                                    </div>
                                    <div class="thumbnail" onclick="changeProductImage('book4')">
                                        <img src="https://picsum.photos/seed/book4/150/150.jpg" alt="Book Pages">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Section - Product Details -->
                        <div class="col-lg-6 col-md-8">
                            <div class="middle-section">
                                <!-- Product Title -->
                                <h1 class="product-title">The Hunter Series: Complete Collection</h1>

                                <!-- Author Name -->
                                <div class="author-name">by Rick Steves</div>

                                <!-- Review and Count -->
                                <div class="rating-section">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <span class="rating-value">4.6</span>
                                    <span class="review-count">(1,234 Reviews)</span>
                                </div>

                                <!-- Border Divider -->
                                <div class="border-divider"></div>

                                <!-- Price -->
                                <div class="price-section">
                                    <span class="current-price">$24.99</span>
                                    <span class="original-price">$34.99</span>
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

                                <!-- Border Divider -->
                                <div class="border-divider"></div>

                                <!-- Shipping Information, SKU, Availability, Social Share -->
                                <div class="product-info-grid">
                                    <div class="info-item">
                                        <i class="bi bi-truck"></i>
                                        <div class="info-content">
                                            <h6>Shipping</h6>
                                            <p>Free shipping on orders over $50</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-upc-scan"></i>
                                        <div class="info-content">
                                            <h6>SKU</h6>
                                            <p>9781546171461</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-check-circle"></i>
                                        <div class="info-content">
                                            <h6>Availability</h6>
                                            <p>In Stock - Ready to ship</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="social-share">
                                            <h6>Share</h6>
                                            <div class="social-icons">
                                                <div class="social-icon facebook">
                                                    <i class="fab fa-facebook-f"></i>
                                                </div>
                                                <div class="social-icon twitter">
                                                    <i class="fab fa-twitter"></i>
                                                </div>
                                                <div class="social-icon instagram">
                                                    <i class="fab fa-instagram"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section - Book Attributes -->
                        <div class="col-lg-3">
                            <div class="right-section">
                                <div class="book-attributes">
                                    <h3 class="attribute-title">Book Details</h3>

                                    <div class="attribute-item">
                                        <span class="attribute-label">SBN-13:</span>
                                        <span class="attribute-value">9781546171461</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Publisher:</span>
                                        <span class="attribute-value">Rick Steves Publishing</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Publication:</span>
                                        <span class="attribute-value">March 26, 2025</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Series:</span>
                                        <span class="attribute-value">Hunter</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Pages:</span>
                                        <span class="attribute-value">400</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Dimensions:</span>
                                        <span class="attribute-value">12 × 34 × 24 in</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Weight:</span>
                                        <span class="attribute-value">420g</span>
                                    </div>

                                    <div class="attribute-item">
                                        <span class="attribute-label">Age Range:</span>
                                        <span class="attribute-value">12 - 18 Years</span>
                                    </div>
                                </div>

                                <!-- Wishlist and Compare Buttons -->
                                <div class="wishlist-compare">
                                    <button class="btn-wishlist" onclick="addToWishlist()">
                                        <i class="bi bi-heart"></i> Add to Wishlist
                                    </button>
                                    <button class="btn-compare" onclick="addToCompare()">
                                        <i class="bi bi-arrow-left-right"></i> Add to Compare
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Width Banner Image -->
                <div class="banner-section">
                    <img src="https://picsum.photos/seed/book-banner/1400/300.jpg" alt="Special Book Collection Banner">
                </div>

                <!-- Product Tabs -->
                <div class="tabs-section">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false">Product Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (1,234)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="author-tab" data-bs-toggle="tab" data-bs-target="#author" type="button" role="tab" aria-controls="author" aria-selected="false">About the Author</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <h4>About This Book</h4>
                            <p>Join the thrilling adventure in "The Hunter Series: Complete Collection" by acclaimed author Rick Steves. This epic saga follows the journey of a young hunter who discovers ancient secrets and faces unimaginable challenges in a world where magic and reality intertwine.</p>

                            <p>The Hunter Series has captivated readers worldwide with its intricate plot development, compelling character arcs, and breathtaking descriptions of mystical lands. Now, for the first time, all books in this beloved series are available in one comprehensive collection.</p>

                            <p>Perfect for both longtime fans and new readers, this edition includes exclusive bonus content, author's notes, and never-before-seen illustrations that bring the world of Hunter to life like never before.</p>

                            <h5>What Readers Are Saying:</h5>
                            <blockquote class="blockquote">
                                <p class="mb-0">"A masterpiece of modern fantasy literature. Steves has created a world that will stay with you long after you turn the final page."</p>
                                <footer class="blockquote-footer">The Literary Review</footer>
                            </blockquote>
                        </div>

                        <!-- Product Details Tab -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <h4>Complete Product Information</h4>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Title:</strong></td>
                                        <td>The Hunter Series: Complete Collection</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Author:</strong></td>
                                        <td>Rick Steves</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ISBN-13:</strong></td>
                                        <td>9781546171461</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Publisher:</strong></td>
                                        <td>Rick Steves Publishing</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Publication Date:</strong></td>
                                        <td>March 26, 2025</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Series:</strong></td>
                                        <td>Hunter</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pages:</strong></td>
                                        <td>400</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Language:</strong></td>
                                        <td>English</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Format:</strong></td>
                                        <td>Hardcover, Paperback, E-book</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dimensions:</strong></td>
                                        <td>12 × 34 × 24 inches</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Weight:</strong></td>
                                        <td>420 grams</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Target Audience:</strong></td>
                                        <td>Young Adult (12-18 years)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Genre:</strong></td>
                                        <td>Fantasy, Adventure, Coming-of-age</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <h4>Customer Reviews</h4>

                            <!-- Review Summary -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h2 class="display-4">4.6</h2>
                                        <div class="stars mb-2">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                        <p>Based on 1,234 reviews</p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <!-- Rating breakdown would go here -->
                                    <p>Customer reviews highlight the engaging storyline, well-developed characters, and immersive world-building that makes this series a must-read for fantasy enthusiasts.</p>
                                </div>
                            </div>

                            <!-- Individual Reviews -->
                            <div class="review-item mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="fw-bold">Sarah Thompson</div>
                                    <div class="text-muted">October 15, 2023</div>
                                </div>
                                <div class="stars mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p>Absolutely incredible! The Hunter Series has become my all-time favorite. Rick Steves has created such a vivid, immersive world. I couldn't put it down!</p>
                            </div>

                            <div class="review-item mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="fw-bold">Michael Chen</div>
                                    <div class="text-muted">October 8, 2023</div>
                                </div>
                                <div class="stars mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <p>Great series with compelling characters. The pacing is excellent and the plot twists kept me guessing. Highly recommend for any fantasy fan!</p>
                            </div>

                            <button class="btn btn-primary">Load More Reviews</button>
                        </div>

                        <!-- About the Author Tab -->
                        <div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
                            <h4>About Rick Steves</h4>
                            <p>Rick Steves is an award-winning author known for his captivating fantasy series and engaging storytelling style. With over 20 years of experience in creative writing, Steves has established himself as one of the leading voices in modern fantasy literature.</p>

                            <p>Born and raised in the Pacific Northwest, Steves drew inspiration from the lush forests and mysterious landscapes of his childhood home. His unique blend of traditional fantasy elements with contemporary themes has earned him a dedicated global following.</p>

                            <h5>Other Books by Rick Steves:</h5>
                            <ul>
                                <li>The Chronicles of Atheria (Series)</li>
                                <li>Shadows of the Realm</li>
                                <li>The Last Guardian</li>
                                <li>Beyond the Horizon</li>
                                <li>The Stone Council</li>
                            </ul>

                            <p>When he's not writing, Steves enjoys hiking, photography, and visiting schools to encourage young readers to explore their creativity through storytelling.</p>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="related-products">
                    <h2 class="section-title">Related Books</h2>
                    <div class="product-grid">
                        <!-- Book 1 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/book5/220/300.jpg" alt="Fantasy Book">
                                <div class="product-badge">-20%</div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">Fantasy</div>
                                <div class="product-name">The Chronicles of Atheria: Book One</div>
                                <div class="product-author">by Sarah Mitchell</div>
                                <div class="product-price">
                                    <span class="current-price-small">$19.99</span>
                                    <span class="original-price-small">$24.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <span class="review-count-small">(892)</span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart-small">Add to Cart</button>
                                    <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Book 2 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/book6/220/300.jpg" alt="Adventure Book">
                                <div class="product-badge">New</div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">Adventure</div>
                                <div class="product-name">The Last Expedition</div>
                                <div class="product-author">by James Walker</div>
                                <div class="product-price">
                                    <span class="current-price-small">$22.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <span class="review-count-small">(456)</span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart-small">Add to Cart</button>
                                    <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Book 3 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/book7/220/300.jpg" alt="Mystery Book">
                                <div class="product-badge">Bestseller</div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">Mystery</div>
                                <div class="product-name">Shadows in the Library</div>
                                <div class="product-author">by Emma Roberts</div>
                                <div class="product-price">
                                    <span class="current-price-small">$18.99</span>
                                    <span class="original-price-small">$21.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <span class="review-count-small">(623)</span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart-small">Add to Cart</button>
                                    <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Book 4 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/book8/220/300.jpg" alt="Sci-Fi Book">
                                <div class="product-badge">Hot</div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">Science Fiction</div>
                                <div class="product-name">The Quantum Paradox</div>
                                <div class="product-author">by Daniel Park</div>
                                <div class="product-price">
                                    <span class="current-price-small">$24.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <span class="review-count-small">(1,045)</span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart-small">Add to Cart</button>
                                    <button class="btn-wishlist-small"><i class="bi bi-heart"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Book 5 -->
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://picsum.photos/seed/book9/220/300.jpg" alt="Young Adult Book">
                            </div>
                            <div class="product-info">
                                <div class="product-category">Young Adult</div>
                                <div class="product-name">The Academy of Elements</div>
                                <div class="product-author">by Lisa Anderson</div>
                                <div class="product-price">
                                    <span class="current-price-small">$16.99</span>
                                </div>
                                <div class="product-rating-small">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <span class="review-count-small">(2,341)</span>
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
            mainImage.src = `https://picsum.photos/seed/${imageName}/400/600.jpg`;

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
