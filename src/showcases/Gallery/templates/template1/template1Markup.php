<?php
namespace Shopglut\showcases\Gallery\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data, $layout_id) {
		// Get settings
		$layout_id = isset($_GET['layout_id']) ? absint(wp_unslash($_GET['layout_id'])) : $layout_id; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Layout preview in admin context
		$settings = $this->getLayoutSettings($layout_id);

		// Check if we're rendering a specific product or demo
		$product_id = isset($template_data['product_id']) ? $template_data['product_id'] : 0;
		$is_demo = empty($product_id);

		if ($is_demo) {
			// Render demo gallery
			$this->render_demo_gallery($settings, $layout_id);
		} else {
			// Render live gallery
			$this->render_live_gallery($product_id, $settings);
		}
	}

	/**
	 * Render live gallery with real WooCommerce product images
	 */
	public function render_live_gallery($product_id, $settings) {
		$product = wc_get_product($product_id);

		if (!$product) {
			echo '<div class="shopglut-gallery-error"><p>' . esc_html__('Product not found.', 'shopglut') . '</p></div>';
			return;
		}

		// Get product images
		$product_image_id = get_post_thumbnail_id($product_id);
		$product_image_url = $product_image_id ? wp_get_attachment_image_url($product_image_id, 'large') : wc_placeholder_img_src('large');
		$gallery_ids = $product->get_gallery_image_ids();

		// Build gallery images array
		$gallery_images = [];

		// Add main product image
		if ($product_image_url) {
			$gallery_images[] = [
				'url' => $product_image_url,
				'thumb' => $product_image_id ? wp_get_attachment_image_url($product_image_id, 'thumbnail') : wc_placeholder_img_src('thumbnail'),
				'title' => get_the_title($product_image_id) ?: $product->get_name(),
				'alt' => get_post_meta($product_image_id, '_wp_attachment_image_alt', true) ?: $product->get_name()
			];
		}

		// Add gallery images
		if (!empty($gallery_ids)) {
			foreach ($gallery_ids as $gallery_id) {
				$image_url = wp_get_attachment_image_url($gallery_id, 'large');
				$thumb_url = wp_get_attachment_image_url($gallery_id, 'thumbnail');

				if ($image_url && $thumb_url) {
					$gallery_images[] = [
						'url' => $image_url,
						'thumb' => $thumb_url,
						'title' => get_the_title($gallery_id) ?: $product->get_name(),
						'alt' => get_post_meta($gallery_id, '_wp_attachment_image_alt', true) ?: $product->get_name()
					];
				}
			}
		}

		// If no images found, use placeholder
		if (empty($gallery_images)) {
			$placeholder_url = wc_placeholder_img_src('large');
			$gallery_images[] = [
				'url' => $placeholder_url,
				'thumb' => wc_placeholder_img_src('thumbnail'),
				'title' => $product->get_name(),
				'alt' => $product->get_name()
			];
		}

		$columns = isset($settings['grid_columns']) ? intval($settings['grid_columns']) : 3;
		$gap = isset($settings['grid_gap']) ? intval($settings['grid_gap']) : 10;
		$border_radius = isset($settings['border_radius']) ? intval($settings['border_radius']) : 8;
		$enable_lightbox = isset($settings['enable_lightbox']) ? (bool) $settings['enable_lightbox'] : true;
		$enable_captions = isset($settings['enable_captions']) ? (bool) $settings['enable_captions'] : true;
		?>
		<div class="shopglut-gallery-simple-grid template1"
			 data-columns="<?php echo esc_attr($columns); ?>"
			 data-gap="<?php echo esc_attr($gap); ?>"
			 data-border-radius="<?php echo esc_attr($border_radius); ?>"
			 data-enable-lightbox="<?php echo esc_attr($enable_lightbox); ?>"
			 data-product-id="<?php echo esc_attr($product_id); ?>">

			<div class="gallery-grid" style="grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: <?php echo esc_attr($gap); ?>px;">

				<?php foreach ($gallery_images as $index => $image): ?>
					<div class="gallery-item"
						 data-index="<?php echo esc_attr($index); ?>"
						 style="border-radius: <?php echo esc_attr($border_radius); ?>px;">

						<?php if ($enable_lightbox): ?>
							<a href="<?php echo esc_url($image['url']); ?>"
							   class="gallery-lightbox-link"
							   data-title="<?php echo esc_attr($image['title']); ?>"
							   data-alt="<?php echo esc_attr($image['alt']); ?>">
						<?php endif; ?>

							<div class="image-wrapper" style="border-radius: <?php echo esc_attr($border_radius); ?>px;">
								<img src="<?php echo esc_url($image['url']); ?>"
									 alt="<?php echo esc_attr($image['alt']); ?>"
									 title="<?php echo esc_attr($image['title']); ?>"
									 loading="lazy"
									 style="border-radius: <?php echo esc_attr($border_radius); ?>px;">

								<div class="image-overlay" style="border-radius: <?php echo esc_attr($border_radius); ?>px;">
									<div class="overlay-content">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<circle cx="11" cy="11" r="8"></circle>
											<path d="m21 21-4.35-4.35"></path>
											<line x1="8" y1="11" x2="14" y2="11"></line>
										</svg>
										<span><?php esc_html_e('View', 'shopglut'); ?></span>
									</div>
								</div>
							</div>

							<?php if ($enable_captions && $image['title']): ?>
								<div class="image-caption">
									<span><?php echo esc_html($image['title']); ?></span>
								</div>
							<?php endif; ?>

						<?php if ($enable_lightbox): ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>

			</div>

			<?php if ($enable_lightbox): ?>
				<!-- Lightbox Modal -->
				<div class="gallery-lightbox-modal">
					<div class="lightbox-overlay"></div>
					<div class="lightbox-content">
						<button class="lightbox-close" aria-label="<?php esc_attr_e('Close lightbox', 'shopglut'); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="18" y1="6" x2="6" y2="18"></line>
								<line x1="6" y1="6" x2="18" y2="18"></line>
							</svg>
						</button>

						<button class="lightbox-prev" aria-label="<?php esc_attr_e('Previous image', 'shopglut'); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="15 18 9 12 15 6"></polyline>
							</svg>
						</button>

						<button class="lightbox-next" aria-label="<?php esc_attr_e('Next image', 'shopglut'); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</button>

						<div class="lightbox-image-container">
							<img src="" alt="" class="lightbox-image">
						</div>

						<div class="lightbox-caption">
							<h3 class="caption-title"></h3>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Demo version - replica of gallery.html
	 */
	public function render_demo_gallery($settings, $layout_id) {
		// Font Awesome for icons - using local fallback bundled with theme/plugin
		wp_enqueue_style('font-awesome', SHOPGLUT_URL . 'assets/css/font-awesome.min.css', array(), '6.0.0');

		// Demo product data with prices
		$demo_products = [
			[
				'name' => 'Premium Laptop Pro',
				'description' => 'High-performance laptop with stunning 4K display and professional features.',
				'price' => '$1,299',
				'regular_price' => '',
				'category' => 'Electronics',
				'slug' => 'premium-laptop-pro'
			],
			[
				'name' => 'Wireless Audio Pro',
				'description' => 'Crystal-clear sound with active noise cancellation and premium comfort.',
				'price' => '$249',
				'regular_price' => '$349',
				'category' => 'Audio',
				'slug' => 'wireless-audio-pro'
			],
			[
				'name' => 'Smart Watch Ultra',
				'description' => 'Advanced fitness tracking with heart rate monitoring and GPS navigation.',
				'price' => '$399',
				'regular_price' => '$499',
				'category' => 'Wearables',
				'slug' => 'smart-watch-ultra'
			],
			[
				'name' => 'Power Bank Max',
				'description' => 'High-capacity portable charger with fast charging technology.',
				'price' => '$79',
				'regular_price' => '',
				'category' => 'Accessories',
				'slug' => 'power-bank-max'
			],
			[
				'name' => 'Camera Pro HD',
				'description' => 'Professional camera with 4K recording and advanced image stabilization.',
				'price' => '$699',
				'regular_price' => '$899',
				'category' => 'Photography',
				'slug' => 'camera-pro-hd'
			],
			[
				'name' => 'Tablet Studio',
				'description' => 'Creative tablet with stylus support and professional display quality.',
				'price' => '$599',
				'regular_price' => '$799',
				'category' => 'Tablets',
				'slug' => 'tablet-studio'
			]
		];
		// Exact CSS from gallery.html
		echo '<style>
        .template1-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .template1-title {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .template1-gallery-container {
            padding: 30px;
        }

        .template1-gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        
        .template1-gallery-item {
            position: relative;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .template1-gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .template1-gallery-image-wrapper {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .template1-gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .template1-gallery-item:hover .template1-gallery-image {
            transform: scale(1.05);
        }

        .template1-gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.7) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .template1-gallery-item:hover .template1-gallery-overlay {
            opacity: 1;
        }

        .template1-gallery-overlay-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 20px;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .template1-gallery-item:hover .template1-gallery-overlay-icon {
            transform: translateY(0);
        }

        .template1-gallery-info {
            padding: 20px;
        }

        .template1-gallery-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .template1-gallery-description {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .template1-gallery-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .template1-gallery-category {
            display: inline-block;
            padding: 4px 12px;
            background: #f0f4ff;
            color: #667eea;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .template1-gallery-price {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .template1-gallery-price .regular-price {
            font-size: 14px;
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .template1-gallery-price .sale-price {
            color: #e74c3c;
        }

        /* Lightbox Styles */
        .template1-lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .template1-lightbox.active {
            display: flex;
            opacity: 1;
        }

        .template1-lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .template1-lightbox-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        .template1-lightbox-close {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 20px;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .template1-lightbox-close:hover {
            background: white;
            transform: rotate(90deg);
        }

        .template1-lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .template1-lightbox-nav:hover {
            background: white;
        }

        .template1-lightbox-prev {
            left: 15px;
        }

        .template1-lightbox-next {
            right: 15px;
        }

        .template1-lightbox-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 20px;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .template1-gallery-item {
                flex: 0 0 250px;
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .template1-container {
                margin: 10px;
                border-radius: 10px;
            }

            .template1-title {
                padding: 20px;
                font-size: 24px;
            }

            .template1-gallery-container {
                padding: 20px;
            }

            .template1-gallery-item {
                flex: 0 0 220px;
                min-width: 220px;
            }

            .template1-gallery-image-wrapper {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .template1-gallery-container {
                padding: 15px;
            }

            .template1-gallery-item {
                flex: 0 0 200px;
                min-width: 200px;
            }

            .template1-gallery-image-wrapper {
                height: 180px;
            }

            .template1-gallery-title {
                font-size: 16px;
            }

            .template1-gallery-description {
                font-size: 13px;
            }
        }

        /* Loading Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .template1-gallery-item {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .template1-gallery-item:nth-child(1) { animation-delay: 0.1s; }
        .template1-gallery-item:nth-child(2) { animation-delay: 0.2s; }
        .template1-gallery-item:nth-child(3) { animation-delay: 0.3s; }
        .template1-gallery-item:nth-child(4) { animation-delay: 0.4s; }
        .template1-gallery-item:nth-child(5) { animation-delay: 0.5s; }
        .template1-gallery-item:nth-child(6) { animation-delay: 0.6s; }
        </style>';

		// Simple JavaScript for gallery lightbox only
		echo '<script>
        (function() {
            // Gallery data
            var galleryItems = [
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Premium Laptop Pro - High-performance laptop with stunning 4K display"
                },
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Wireless Audio Pro - Crystal-clear sound with active noise cancellation"
                },
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Smart Watch Ultra - Advanced fitness tracking with heart rate monitoring"
                },
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Power Bank Max - High-capacity portable charger with fast charging"
                },
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Camera Pro HD - Professional camera with 4K recording capabilities"
                },
                {
                    image: "' . esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')) . '",
                    caption: "Tablet Studio - Creative tablet with stylus support for professionals"
                }
            ];

            var currentImageIndex = 0;

            window.openLightbox = function(index) {
                currentImageIndex = index;
                var lightbox = document.querySelector(".template1-lightbox");
                var lightboxImage = document.querySelector("#lightbox-image");
                var lightboxCaption = document.querySelector("#lightbox-caption");

                if (!lightbox) return;

                lightboxImage.src = galleryItems[index].image;
                lightboxCaption.textContent = galleryItems[index].caption;

                lightbox.classList.add("active");
                document.body.style.overflow = "hidden";
            };

            window.closeLightbox = function() {
                var lightbox = document.querySelector(".template1-lightbox");
                if (lightbox) {
                    lightbox.classList.remove("active");
                    document.body.style.overflow = "auto";
                }
            };

            window.navigateLightbox = function(direction) {
                currentImageIndex = currentImageIndex + direction;

                if (currentImageIndex < 0) {
                    currentImageIndex = galleryItems.length - 1;
                } else if (currentImageIndex >= galleryItems.length) {
                    currentImageIndex = 0;
                }

                var lightboxImage = document.querySelector("#lightbox-image");
                var lightboxCaption = document.querySelector("#lightbox-caption");

                if (lightboxImage && lightboxCaption) {
                    lightboxImage.style.opacity = "0";

                    setTimeout(function() {
                        lightboxImage.src = galleryItems[currentImageIndex].image;
                        lightboxCaption.textContent = galleryItems[currentImageIndex].caption;
                        lightboxImage.style.opacity = "1";
                    }, 200);
                }
            };

            // Keyboard navigation
            document.addEventListener("keydown", function(e) {
                var lightbox = document.querySelector(".template1-lightbox");

                if (!lightbox || !lightbox.classList.contains("active")) return;

                switch(e.key) {
                    case "Escape":
                        closeLightbox();
                        break;
                    case "ArrowLeft":
                        navigateLightbox(-1);
                        break;
                    case "ArrowRight":
                        navigateLightbox(1);
                        break;
                }
            });

            // Close lightbox on background click
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("template1-lightbox")) {
                    closeLightbox();
                }
            });

            // Force scroll to top immediately and repeatedly
            function forceScrollToTop() {
                var modalBody = document.querySelector(".shopglut-template-modal-modal-body");
                if (modalBody) {
                    modalBody.scrollTop = 0;
                    modalBody.scrollIntoView({ block: "start", behavior: "auto" });
                }
                var modal = document.getElementById("htmlDemoModal");
                if (modal) {
                    modal.scrollTop = 0;
                }
                window.scrollTo(0, 0);
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
            }

            // Force scroll to top immediately
            forceScrollToTop();

            // Force scroll to top multiple times to ensure it works
            setTimeout(forceScrollToTop, 10);
            setTimeout(forceScrollToTop, 50);
            setTimeout(forceScrollToTop, 100);
            setTimeout(forceScrollToTop, 200);
            setTimeout(forceScrollToTop, 500);
            setTimeout(forceScrollToTop, 1000);
        })();
        </script>';

		// Fix the modal body centering conflict
		echo '<style>
		/* CRITICAL: Override modal body centering that hides the header */
		.shopglut-template-modal-modal-body {
			display: block !important;
			align-items: flex-start !important;
			justify-content: flex-start !important;
			text-align: left !important;
			overflow-y: auto !important;
			max-height: 90vh !important;
			scroll-padding-top: 0 !important;
			scroll-margin-top: 0 !important;
			scroll-behavior: auto !important;
		}

		/* Also override any gallery modal centering */
		.shopglut-product-gallery.template1 .gallery-modal {
			align-items: flex-start !important;
			justify-content: flex-start !important;
		}

		/* Hide ONLY the specific conflicting gallery layout elements */
		.shopglut-product-gallery.template1 .gallery-inner {
			display: none !important;
		}

		/* Keep the modal content structure but override centering */
		.shopglut-product-gallery.template1 .gallery-modal-content {
			align-items: flex-start !important;
			justify-content: flex-start !important;
		}

		/* Ensure our container starts at top and is visible */
		.template1-container {
			scroll-margin-top: 0 !important;
			scroll-padding-top: 0 !important;
			margin-top: 0 !important;
			padding-top: 0 !important;
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
		}

		/* Force gallery to start from very top */
		.template1-title {
			margin-top: 0 !important;
			padding-top: 30px !important;
		}
		</style>';
		?>
		<div class="template1-container">
			<h1 class="template1-title">Simple Grid Gallery</h1>

			<div class="template1-gallery-container">
				<div class="template1-gallery-grid">
					<?php foreach ($demo_products as $index => $product): ?>
						<!-- Gallery Item <?php echo esc_html($index + 1); ?> -->
						<div class="template1-gallery-item" onclick="openLightbox(<?php echo esc_js($index); ?>)">
							<div class="template1-gallery-image-wrapper">
								<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="template1-gallery-image">
								<div class="template1-gallery-overlay">
									<div class="template1-gallery-overlay-icon">
										<i class="fas fa-search-plus"></i>
									</div>
								</div>
							</div>
							<div class="template1-gallery-info">
								<h3 class="template1-gallery-title"><?php echo esc_html($product['name']); ?></h3>
								<p class="template1-gallery-description"><?php echo esc_html($product['description']); ?></p>
								<div class="template1-gallery-meta">
									<span class="template1-gallery-category"><?php echo esc_html($product['category']); ?></span>
									<div class="template1-gallery-price">
										<?php if (!empty($product['regular_price'])): ?>
											<span class="regular-price"><?php echo esc_html($product['regular_price']); ?></span>
										<?php endif; ?>
										<span class="sale-price"><?php echo esc_html($product['price']); ?></span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Lightbox -->
			<div class="template1-lightbox" id="lightbox">
				<div class="template1-lightbox-content">
					<button class="template1-lightbox-close" onclick="closeLightbox()">
						<i class="fas fa-times"></i>
					</button>
					<button class="template1-lightbox-nav template1-lightbox-prev" onclick="navigateLightbox(-1)">
						<i class="fas fa-chevron-left"></i>
					</button>
					<button class="template1-lightbox-nav template1-lightbox-next" onclick="navigateLightbox(1)">
						<i class="fas fa-chevron-right"></i>
					</button>
					<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Gallery Image" class="template1-lightbox-image" id="lightbox-image">
					<div class="template1-lightbox-caption" id="lightbox-caption"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row(
			$wpdb->prepare("SELECT layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_gallery_layouts` WHERE id = %d", $layout_id)
		);

		if (!$layout_data || empty($layout_data->layout_settings)) {
			return $this->getDefaultSettings();
		}

		$settings = maybe_unserialize($layout_data->layout_settings);

		if ($settings === false || !is_array($settings)) {
			return $this->getDefaultSettings();
		}

		// Try different possible settings structures
		$gallery_settings = null;

		if (isset($settings['shopg_product_gallery_settings_template1'])) {
			$gallery_settings = $this->flattenSettings($settings['shopg_product_gallery_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$gallery_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['grid_columns']) || isset($settings['gallery_settings'])) {
			$gallery_settings = $this->flattenSettings($settings);
		}

		if ($gallery_settings) {
			return $gallery_settings;
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
			'grid_columns' => 3,
			'grid_gap' => 10,
			'border_radius' => 8,
			'enable_lightbox' => true,
			'enable_captions' => true,
			'layout_id' => 0
		);
	}
}