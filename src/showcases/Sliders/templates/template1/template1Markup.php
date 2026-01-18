<?php
namespace Shopglut\showcases\Sliders\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data, $layout_id) {
		$settings = $this->getLayoutSettings($layout_id);
		$this->render_slider($settings, $layout_id);
	}

	public function render_slider($settings, $layout_id) {
		$autoplay = isset($settings['autoplay']) ? (bool) $settings['autoplay'] : true;
		$autoplay_speed = isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 3000;
		$show_dots = isset($settings['show_dots']) ? (bool) $settings['show_dots'] : true;
		$show_arrows = isset($settings['show_arrows']) ? (bool) $settings['show_arrows'] : true;
		$animation_speed = isset($settings['animation_speed']) ? intval($settings['animation_speed']) : 500;
		$slides_per_view = isset($settings['slides_per_view']) ? intval($settings['slides_per_view']) : 4;
		$products_to_show = isset($settings['products_to_show']) ? intval($settings['products_to_show']) : 8;

		// Query real WooCommerce products
		$slider_products = $this->get_woocommerce_products($products_to_show);

		// Group products into slides
		$slide_groups = array_chunk($slider_products, $slides_per_view);
		$total_slides = count($slide_groups);

		// Include clean inline CSS styles
		echo '<style>
		/* Clean Modern Product Slider */
		.shopglut-product-slider.template1 {
			margin: 30px 0;
			background: #ffffff;
		}

		.shopglut-product-slider.template1 .slider-header {
			text-align: center;
			margin-bottom: 25px;
			position: relative;
		}

		.shopglut-product-slider.template1 .slider-title {
			margin: 0;
			font-size: 28px;
			font-weight: 700;
			color: #2c3e50;
			font-family: "Segoe UI", Roboto, sans-serif;
		}

		.shopglut-product-slider.template1 .slider-wrapper {
			position: relative;
			display: flex;
			align-items: center;
		}

		.shopglut-product-slider.template1 .slider-nav {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			display: flex;
			align-items: center;
			justify-content: center;
			width: 45px;
			height: 45px;
			background: #ffffff;
			border: 2px solid #e9ecef;
			border-radius: 50%;
			color: #2c3e50;
			cursor: pointer;
			transition: all 0.3s ease;
			z-index: 10;
			font-size: 16px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		/* Fallback CSS arrows if Font Awesome doesnt load */
		.shopglut-product-slider.template1 .slider-nav::before {
			content: "";
			position: absolute;
			width: 0;
			height: 0;
			border-style: solid;
			opacity: 0;
			transition: opacity 0.3s ease;
		}

		.shopglut-product-slider.template1 .slider-prev::before {
			border-width: 6px 10px 6px 0;
			border-color: transparent #2c3e50 transparent transparent;
			left: 50%;
			margin-left: -2px;
		}

		.shopglut-product-slider.template1 .slider-next::before {
			border-width: 6px 0 6px 10px;
			border-color: transparent transparent transparent #2c3e50;
			left: 50%;
			margin-left: -4px;
		}

		/* Hide fallback when Font Awesome icons are loaded */
		.shopglut-product-slider.template1 .slider-nav i {
			position: relative;
			z-index: 2;
		}

		/* Show fallback when Font Awesome is not available */
		.shopglut-product-slider.template1 .slider-nav:not(:has(i))::before {
			opacity: 1;
		}

		.shopglut-product-slider.template1 .slider-nav:hover {
			background: #f8f9fa;
			border-color: #007bff;
			transform: translateY(-50%) scale(1.05);
			box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
		}

		.shopglut-product-slider.template1 .slider-nav:active {
			transform: translateY(-50%) scale(0.95);
		}

		.shopglut-product-slider.template1 .slider-nav:disabled {
			background: #f8f9fa;
			color: #adb5bd;
			border-color: #e9ecef;
			cursor: not-allowed;
			transform: translateY(-50%);
			box-shadow: none;
		}

		.shopglut-product-slider.template1 .slider-nav:disabled:hover {
			transform: translateY(-50%);
			box-shadow: none;
		}

		.shopglut-product-slider.template1 .slider-prev {
			left: 15px;
		}

		.shopglut-product-slider.template1 .slider-next {
			right: 15px;
		}

		.shopglut-product-slider.template1 .product-slider-track {
			position: relative;
			overflow: hidden;
			margin: 0 70px;
		}

		.shopglut-product-slider.template1 .product-slider-wrapper-inner {
			display: flex;
			transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.shopglut-product-slider.template1 .product-slide {
			flex: 0 0 100%;
		}

		.shopglut-product-slider.template1 .products-container {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 24px;
		}

		.shopglut-product-slider.template1 .product-card {
			background: #ffffff;
			border: 1px solid #f0f0f0;
			border-radius: 12px;
			overflow: hidden;
			transition: all 0.3s ease;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		}

		.shopglut-product-slider.template1 .product-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
			border-color: #007bff;
		}

		.shopglut-product-slider.template1 .product-image {
			width: 100%;
			height: 220px;
			background: #f8f9fa;
			position: relative;
			overflow: hidden;
		}

		.shopglut-product-slider.template1 .product-image a {
			display: block;
			width: 100%;
			height: 100%;
			text-decoration: none;
		}

		.shopglut-product-slider.template1 .product-img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.shopglut-product-slider.template1 .sale-badge {
			position: absolute;
			top: 12px;
			right: 12px;
			background: #ff4757;
			color: #ffffff;
			padding: 5px 10px;
			border-radius: 4px;
			font-size: 11px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		/* Simple Two-Row Product Info Design */
		.shopglut-product-slider.template1 .product-info {
			padding: 15px;
			background: #ffffff;
			display: block !important;
			align-items: unset !important;
			gap: unset !important;
		}

		/* Row 1: Product Title */
		.shopglut-product-slider.template1 .product-name {
			margin: 0 0 10px 0;
			font-size: 15px;
			font-weight: 600;
			line-height: 1.3;
			color: #2c3e50;
			height: 39px;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
		}

		.shopglut-product-slider.template1 .product-name a {
			color: #2c3e50;
			text-decoration: none;
		}

		.shopglut-product-slider.template1 .product-name a:hover {
			color: #3498db;
		}

		/* Row 2: Category (Left) and Price (Right) */
		.shopglut-product-slider.template1 .product-categories {
			font-size: 12px;
			color: #7f8c8d;
			font-weight: 500;
			float: left;
			margin-bottom: 10px;
		}

		.shopglut-product-slider.template1 .product-categories a {
			color: #7f8c8d;
			text-decoration: none;
		}

		.shopglut-product-slider.template1 .product-categories a:hover {
			color: #3498db;
		}

		/* Price - Right Side */
		.shopglut-product-slider.template1 .product-price {
			font-size: 16px;
			font-weight: 700;
			color: #2c3e50;
			display: flex;
			align-items: baseline;
			gap: 5px;
			float: right;
			margin-bottom: 10px;
		}

		/* Clear floats */
		.shopglut-product-slider.template1 .product-info::after {
			content: "";
			display: table;
			clear: both;
		}

		.shopglut-product-slider.template1 .regular-price {
			color: #95a5a6;
			font-size: 14px;
			font-weight: 400;
		}

		.shopglut-product-slider.template1 .regular-price.has-sale {
			text-decoration: line-through;
			color: #bdc3c7;
			font-size: 13px;
		}

		.shopglut-product-slider.template1 .sale-price {
			color: #e74c3c;
			font-weight: 700;
		}

		.shopglut-product-slider.template1 .woocommerce-Price-currencySymbol {
			font-size: 0.8em;
		}

		.shopglut-product-slider.template1 .product-slider-dots {
			display: flex;
			justify-content: center;
			gap: 10px;
			margin-top: 25px;
		}

		.shopglut-product-slider.template1 .dot-item {
			width: 10px;
			height: 10px;
			background: #dfe6e9;
			border: none;
			border-radius: 50%;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.shopglut-product-slider.template1 .dot-item:hover {
			background: #b2bec3;
			transform: scale(1.2);
		}

		.shopglut-product-slider.template1 .dot-item.active {
			background: #007bff;
			transform: scale(1.3);
		}

		/* Responsive Design */
		@media (max-width: 992px) {
			.shopglut-product-slider.template1 .products-container {
				grid-template-columns: repeat(3, 1fr);
				gap: 20px;
			}
		}

		@media (max-width: 768px) {
			.shopglut-product-slider.template1 .products-container {
				grid-template-columns: repeat(2, 1fr);
				gap: 16px;
			}

			.shopglut-product-slider.template1 .product-slider-track {
				margin: 0 50px;
			}

			.shopglut-product-slider.template1 .slider-nav {
				width: 40px;
				height: 40px;
				font-size: 16px;
			}

			.shopglut-product-slider.template1 .slider-prev {
				left: 5px;
			}

			.shopglut-product-slider.template1 .slider-next {
				right: 5px;
			}
		}

		@media (max-width: 480px) {
			.shopglut-product-slider.template1 .products-container {
				grid-template-columns: 1fr;
				gap: 20px;
			}

			.shopglut-product-slider.template1 .product-slider-track {
				margin: 0 40px;
			}

			.shopglut-product-slider.template1 .product-image {
				height: 200px;
			}

			.shopglut-product-slider.template1 .product-info {
				padding: 15px;
			}
		}
		</style>';

		// Include simple inline JavaScript
		echo '<script>
		document.addEventListener("DOMContentLoaded", function() {
			function initProductSliders() {
				const sliders = document.querySelectorAll(".shopglut-product-slider.template1");

				Array.from(sliders).forEach(function(slider) {
					const wrapper = slider.querySelector(".product-slider-wrapper-inner");
					const slides = slider.querySelectorAll(".product-slide");
					const prevBtn = slider.querySelector(".slider-prev");
					const nextBtn = slider.querySelector(".slider-next");
					const dots = slider.querySelectorAll(".dot-item");

					let currentSlide = 0;
					const totalSlides = slides.length;

					// Return if essential elements are missing or only one slide
					if (!wrapper || totalSlides <= 1) return;

					// Initialize slider structure
					function initializeSlider() {
						// Set up the wrapper for sliding
						wrapper.style.display = "flex";
						wrapper.style.width = (totalSlides * 100) + "%";
						wrapper.style.transition = "transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)";

						// Configure each slide
						Array.from(slides).forEach(function(slide) {
							slide.style.flex = "0 0 100%";
							slide.style.width = "100%";
						});
					}

					// Slide navigation
					function goToSlide(slideIndex) {
						// Ensure slide index is within bounds
						if (slideIndex < 0) {
							slideIndex = 0;
						} else if (slideIndex >= totalSlides) {
							slideIndex = totalSlides - 1;
						}

						currentSlide = slideIndex;
						const translateX = -(slideIndex * 100);
						wrapper.style.transform = "translateX(" + translateX + "%)";

						// Update UI elements
						updateNavigation();
					}

					function nextSlide() {
						const nextIndex = (currentSlide + 1) % totalSlides;
						goToSlide(nextIndex);
					}

					function prevSlide() {
						const prevIndex = currentSlide - 1;
						if (prevIndex < 0) {
							goToSlide(totalSlides - 1);
						} else {
							goToSlide(prevIndex);
						}
					}

					// Update navigation states
					function updateNavigation() {
						// Update dots
						Array.from(dots).forEach(function(dot, index) {
							dot.classList.toggle("active", index === currentSlide);
						});

						// Update arrow states for circular navigation
						if (prevBtn) {
							prevBtn.disabled = false;
							prevBtn.style.opacity = "1";
						}
						if (nextBtn) {
							nextBtn.disabled = false;
							nextBtn.style.opacity = "1";
						}
					}

					// Event listeners
					function bindEvents() {
						// Previous button
						if (prevBtn) {
							prevBtn.addEventListener("click", function(e) {
								e.preventDefault();
								if (!this.disabled) {
									prevSlide();
								}
							});
						}

						// Next button
						if (nextBtn) {
							nextBtn.addEventListener("click", function(e) {
								e.preventDefault();
								if (!this.disabled) {
									nextSlide();
								}
							});
						}

						// Dot navigation
						Array.from(dots).forEach(function(dot, index) {
							dot.addEventListener("click", function() {
								goToSlide(index);
							});
						});

						// Keyboard navigation
						slider.addEventListener("keydown", function(e) {
							switch (e.key) {
								case "ArrowLeft":
									e.preventDefault();
									prevSlide();
									break;
								case "ArrowRight":
									e.preventDefault();
									nextSlide();
									break;
								case "Home":
									e.preventDefault();
									goToSlide(0);
									break;
								case "End":
									e.preventDefault();
									goToSlide(totalSlides - 1);
									break;
							}
						});
					}

					// Initialize the slider
					initializeSlider();
					bindEvents();
					goToSlide(0);
				});
			}

			// Initialize sliders
			initProductSliders();

			// Re-initialize if dynamic content is loaded
			if (window.MutationObserver) {
				const observer = new MutationObserver(function(mutations) {
					let shouldReinit = false;
					Array.from(mutations).forEach(function(mutation) {
						Array.from(mutation.addedNodes).forEach(function(node) {
							if (node.nodeType === 1) {
								if (node.classList && node.classList.contains("shopglut-product-slider") ||
									node.querySelector && node.querySelector(".shopglut-product-slider")) {
									shouldReinit = true;
								}
							}
						});
					});

					if (shouldReinit) {
						setTimeout(initProductSliders, 100);
					}
				});

				observer.observe(document.body, {
					childList: true,
					subtree: true
				});
			}
		});
		</script>';

		?>

		<div class="shopglut-product-slider-container">
			<div class="shopglut-product-slider template1">
				<div class="slider-header">
					<h2 class="slider-title">Featured Products</h2>
				</div>

				<div class="product-slider-wrapper">
					<?php if ($show_arrows && $total_slides > 1): ?>
						<button type="button" class="slider-nav slider-prev" aria-label="Previous slide">
							<i class="fas fa-chevron-left"></i>
						</button>
						<button type="button" class="slider-nav slider-next" aria-label="Next slide">
							<i class="fas fa-chevron-right"></i>
						</button>
					<?php endif; ?>

					<div class="product-slider-track">
						<div class="product-slider-wrapper-inner">
							<?php if (!empty($slide_groups)): ?>
								<?php foreach ($slide_groups as $slide_index => $slide_products): ?>
									<div class="product-slide" data-slide="<?php echo esc_attr($slide_index); ?>">
										<div class="products-container">
											<?php foreach ($slide_products as $product): ?>
												<?php echo wp_kses_post($this->render_product_card($product)); ?>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if ($show_dots && $total_slides > 1): ?>
					<div class="product-slider-dots">
						<?php for ($i = 0; $i < $total_slides; $i++): ?>
							<?php
							/* translators: %d: slide number */
							$slide_number = $i + 1;
							$active_class = $i === 0 ? 'active' : '';
							?>
							<button type="button" class="dot-item <?php echo esc_attr($active_class); ?>"
									data-slide="<?php echo esc_attr($i); ?>" aria-label="<?php echo esc_attr(sprintf(
										/* translators: %d: slide number */
										__('Go to slide %d', 'shopglut'),
										$slide_number
									)); ?>"></button>
						<?php endfor; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get real WooCommerce products
	 */
	private function get_woocommerce_products($limit = 8) {
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'orderby' => 'date',
			'order' => 'DESC',
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Meta query required for stock status filtering
				array(
					'key' => '_stock_status',
					'value' => 'instock',
					'compare' => '='
				)
			)
		);

		$products_query = new \WP_Query($args);
		$slider_products = array();

		if ($products_query->have_posts()) {
			while ($products_query->have_posts()) {
				$products_query->the_post();
				$product = wc_get_product(get_the_ID());

				if ($product) {
					$slider_products[] = $this->get_product_data($product);
				}
			}
		}
		wp_reset_postdata();

		return $slider_products;
	}

	/**
	 * Get product data for display
	 */
	private function get_product_data($product) {
		$product_id = $product->get_id();
		$image_id = $product->get_image_id();
		$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : wc_placeholder_img_src('medium');

		return array(
			'id' => $product_id,
			'name' => get_the_title($product_id),
			'price' => $product->get_price_html(),
			'regular_price' => $product->get_regular_price(),
			'sale_price' => $product->get_sale_price(),
			'is_on_sale' => $product->is_on_sale(),
			'is_in_stock' => $product->is_in_stock(),
			'rating' => wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()),
			'image' => $image_url,
			'link' => get_permalink($product_id),
			'add_to_cart_url' => add_query_arg('add-to-cart', $product_id, wc_get_cart_url()),
			'sku' => $product->get_sku(),
			'categories' => wc_get_product_category_list($product_id, ', '),
			'type' => $product->get_type()
		);
	}

	/**
	 * Render product card HTML with title, category, review, price
	 */
	private function render_product_card($product) {
		ob_start();
		?>
		<div class="product-card">
			<div class="product-image">
				<a href="<?php echo esc_url($product['link']); ?>">
					<img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="product-img">
				</a>
				<?php if ($product['is_on_sale']): ?>
					<span class="sale-badge">Sale</span>
				<?php endif; ?>
			</div>
			<div class="product-info">
				<!-- Row 1: Product Title -->
				<h3 class="product-name">
					<a href="<?php echo esc_url($product['link']); ?>"><?php echo esc_html($product['name']); ?></a>
				</h3>

				<!-- Row 2: Category (Left) and Price (Right) -->
				<?php if ($product['categories']): ?>
					<div class="product-categories"><?php echo wp_kses_post($product['categories']); ?></div>
				<?php else: ?>
					<div class="product-categories">General</div>
				<?php endif; ?>

				<div class="product-price">
					<?php if ($product['is_on_sale']): ?>
						<span class="regular-price has-sale"><?php echo wp_kses_post(wc_price($product['regular_price'])); ?></span>
						<span class="sale-price"><?php echo wp_kses_post(wc_price($product['sale_price'])); ?></span>
					<?php else: ?>
						<span class="regular-price"><?php echo wp_kses_post($product['price']); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_slider_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Using sprintf with esc_sql() for compatibility, direct query required for custom table operation
			$wpdb->prepare(sprintf("SELECT layout_settings, layout_template FROM `%s` WHERE id = %d", esc_sql($table_name)), $layout_id)
		);

		if (!$layout_data || empty($layout_data->layout_settings)) {
			return $this->getDefaultSettings();
		}

		$settings = maybe_unserialize($layout_data->layout_settings);

		if ($settings === false || !is_array($settings)) {
			return $this->getDefaultSettings();
		}

		// Try different possible settings structures
		$slider_settings = null;

		if (isset($settings['shopg_product_slider_settings_template1'])) {
			$slider_settings = $this->flattenSettings($settings['shopg_product_slider_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$slider_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['autoplay']) || isset($settings['slider_settings'])) {
			$slider_settings = $this->flattenSettings($settings);
		}

		if ($slider_settings) {
			return $slider_settings;
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Flatten nested settings structure
	 */
	private function flattenSettings($nested_settings) {
		$flat_settings = [];

		foreach ($nested_settings as $group_key => $group_values) {
			if (is_array($group_values)) {
				foreach ($group_values as $setting_key => $setting_value) {
					if (is_array($setting_value) && isset($setting_value[$setting_key])) {
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} else {
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			} else {
				$flat_settings[$group_key] = $group_values;
			}
		}

		// Recursively flatten if there are still nested arrays
		foreach ($flat_settings as $key => $value) {
			if (is_array($value)) {
				unset($flat_settings[$key]);
				$flat_settings = array_merge($flat_settings, $this->flattenSettings($value));
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			'autoplay' => true,
			'autoplay_speed' => 3000,
			'show_dots' => true,
			'show_arrows' => true,
			'show_thumbnails' => true,
			'animation_speed' => 500,
			'products_to_show' => 8,
			'slides_per_view' => 4,
			'layout_id' => 0
		);
	}
}