<?php
namespace Shopglut\showcases\Accordions\templates\template1;

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
			// Render demo accordion
			$this->render_demo_accordion($settings, $layout_id);
		} else {
			// Render live accordion
			$this->render_live_accordion($product_id, $settings);
		}
	}

	/**
	 * Render live accordion with real WooCommerce product data
	 */
	public function render_live_accordion($product_id, $settings) {
		$product = wc_get_product($product_id);

		if (!$product) {
			echo '<div class="shopglut-accordion-error"><p>' . esc_html__('Product not found.', 'shopglut') . '</p></div>';
			return;
		}

		$accordion_style = isset($settings['accordion_style']) ? sanitize_text_field($settings['accordion_style']) : 'default';
		$allow_multiple = isset($settings['allow_multiple']) ? (bool) $settings['allow_multiple'] : false;
		$show_product_info = isset($settings['show_product_info']) ? (bool) $settings['show_product_info'] : true;
		$show_description = isset($settings['show_description']) ? (bool) $settings['show_description'] : true;
		$show_specifications = isset($settings['show_specifications']) ? (bool) $settings['show_specifications'] : true;
		$show_reviews = isset($settings['show_reviews']) ? (bool) $settings['show_reviews'] : true;
		$show_shipping = isset($settings['show_shipping']) ? (bool) $settings['show_shipping'] : true;

		// Build accordion items
		$accordion_items = [];

		// Product Details Item
		if ($show_product_info) {
			$accordion_items[] = [
				'id' => 'product-details',
				'title' => esc_html__('Product Details', 'shopglut'),
				'icon' => 'package',
				'content' => $this->getProductDetailsContent($product),
				'expanded' => true
			];
		}

		// Description Item
		if ($show_description && $product->get_description()) {
			$accordion_items[] = [
				'id' => 'description',
				'title' => esc_html__('Description', 'shopglut'),
				'icon' => 'file-text',
				'content' => '<div class="accordion-description">' . wp_kses_post($product->get_description()) . '</div>',
				'expanded' => false
			];
		}

		// Specifications Item
		if ($show_specifications) {
			$specifications = $this->getSpecificationsContent($product);
			if (!empty($specifications)) {
				$accordion_items[] = [
					'id' => 'specifications',
					'title' => esc_html__('Specifications', 'shopglut'),
					'icon' => 'settings',
					'content' => $specifications,
					'expanded' => false
				];
			}
		}

		// Shipping & Returns Item
		if ($show_shipping) {
			$accordion_items[] = [
				'id' => 'shipping',
				'title' => esc_html__('Shipping & Returns', 'shopglut'),
				'icon' => 'truck',
				'content' => $this->getShippingContent(),
				'expanded' => false
			];
		}

		// Reviews Item
		if ($show_reviews) {
			$accordion_items[] = [
				'id' => 'reviews',
				'title' => esc_html__('Customer Reviews', 'shopglut'),
				'icon' => 'star',
				'content' => $this->getReviewsContent($product),
				'expanded' => false
			];
		}

		if (empty($accordion_items)) {
			// Default item if no content found
			$accordion_items[] = [
				'id' => 'general-info',
				'title' => esc_html__('Product Information', 'shopglut'),
				'icon' => 'info',
				'content' => $this->getDefaultContent($product),
				'expanded' => true
			];
		}

		$animation_speed = isset($settings['animation_speed']) ? intval($settings['animation_speed']) : 300;
		?>
		<div class="shopglut-accordion-container template1"
			 data-accordion-style="<?php echo esc_attr($accordion_style); ?>"
			 data-allow-multiple="<?php echo esc_attr($allow_multiple); ?>"
			 data-animation-speed="<?php echo esc_attr($animation_speed); ?>"
			 data-product-id="<?php echo esc_attr($product_id); ?>">

			<div class="accordion-wrapper <?php echo esc_attr($accordion_style); ?>">

				<?php foreach ($accordion_items as $index => $item): ?>
					<div class="accordion-item <?php echo $item['expanded'] ? 'expanded' : ''; ?>"
						 data-accordion-id="<?php echo esc_attr($item['id']); ?>"
						 data-index="<?php echo esc_attr($index); ?>">

						<button class="accordion-header"
								aria-expanded="<?php echo $item['expanded'] ? 'true' : 'false'; ?>"
								aria-controls="<?php echo esc_attr($item['id']); ?>-content"
								id="<?php echo esc_attr($item['id']); ?>-header">

							<span class="accordion-icon">
								<?php if (!empty($item['icon'])): ?>
									<span class="icon-wrapper">
										<?php echo wp_kses_post($this->getAccordionIcon($item['icon'])); ?>
									</span>
								<?php endif; ?>
								<span class="chevron">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</span>
							</span>

							<span class="accordion-title"><?php echo esc_html($item['title']); ?></span>

							<?php if ($accordion_style === 'minimal'): ?>
								<span class="accordion-indicator"></span>
							<?php endif; ?>
						</button>

						<div class="accordion-content"
							 id="<?php echo esc_attr($item['id']); ?>-content"
							 aria-labelledby="<?php echo esc_attr($item['id']); ?>-header"
							 <?php echo $item['expanded'] ? '' : 'style="display: none;"'; ?>>

							<div class="content-inner">
								<?php echo wp_kses_post($item['content']); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>

			</div>

		</div>
		<?php
	}

	/**
	 * Demo version - replica of accordion.html
	 */
	public function render_demo_accordion($settings, $layout_id) {
		// Font Awesome for icons - using local fallback bundled with theme/plugin
		wp_enqueue_style('font-awesome', SHOPGLUT_URL . 'assets/css/font-awesome.min.css', array(), '6.0.0');

		// Exact CSS from accordion.html
		echo '<style>
        .template1-container {
            max-width: 800px;
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
        }

        .template1-accordion-container {
            padding: 20px;
        }

        .template1-accordion-item {
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .template1-accordion-header {
            width: 100%;
            background: none;
            border: none;
            padding: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            text-align: left;
        }

        .template1-accordion-header:hover {
            background: #f5f5f5;
        }

        .template1-accordion-header.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            border-bottom: 1px solid #e0e0e0;
        }

        .template1-accordion-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .template1-accordion-title i {
            font-size: 18px;
            color: #667eea;
        }

        .template1-accordion-icon {
            transition: transform 0.3s ease;
            color: #667eea;
        }

        .template1-accordion-header.active .template1-accordion-icon {
            transform: rotate(180deg);
        }

        .template1-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .template1-accordion-content.active {
            max-height: 500px;
            padding: 0 20px 20px 52px;
        }

        .template1-accordion-text {
            color: #666;
            line-height: 1.6;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .template1-container {
                margin: 10px;
                border-radius: 10px;
            }

            .template1-title {
                padding: 20px;
                font-size: 24px;
            }

            .template1-accordion-header {
                padding: 15px;
                font-size: 14px;
            }

            .template1-accordion-content.active {
                padding: 0 15px 15px 47px;
            }
        }
        </style>';

		// JavaScript from accordion.html optimized for modal
		echo '<script>
        (function() {
            // Function to initialize accordion
            function initializeAccordion() {
                // Look for accordion within the modal specifically
                const modal = document.getElementById("htmlDemoModal") || document;
                const headers = modal.querySelectorAll(".template1-accordion-header");

                if (headers.length === 0) {
                    return; // No accordion found
                }

                // Add click event listeners to accordion headers
                headers.forEach(header => {
                    header.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleAccordion(this);
                    });

                    // Add keyboard support
                    header.addEventListener("keydown", function(e) {
                        if (e.key === "Enter" || e.key === " ") {
                            e.preventDefault();
                            toggleAccordion(this);
                        }
                    });
                });
            }

            function toggleAccordion(header) {
                const content = header.nextElementSibling;
                const isActive = header.classList.contains("active");

                // Close all accordion items within modal
                const modal = document.getElementById("htmlDemoModal") || document;
                const allHeaders = modal.querySelectorAll(".template1-accordion-header");
                const allContents = modal.querySelectorAll(".template1-accordion-content");

                allHeaders.forEach(h => h.classList.remove("active"));
                allContents.forEach(c => c.classList.remove("active"));

                // Open clicked item if it wasn\'t active
                if (!isActive) {
                    header.classList.add("active");
                    content.classList.add("active");
                }
            }

            // Initialize on DOM content loaded
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", initializeAccordion);
            } else {
                initializeAccordion(); // DOM already loaded
            }

            // Also initialize when modal becomes visible (for dynamic content)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === "childList") {
                        const modal = document.getElementById("htmlDemoModal");
                        if (modal && modal.style.display !== "none") {
                            setTimeout(initializeAccordion, 100); // Small delay for modal to fully render
                        }
                    }
                });
            });

            // Start observing the document body for modal changes
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Also try to initialize immediately in case modal is already open
            setTimeout(initializeAccordion, 500);
        })();
        </script>';

		// HTML from accordion.html
		?>
		<div class="template1-container">
			<h1 class="template1-title">Product Accordion</h1>

			<div class="template1-accordion-container">
				<!-- Accordion Item 1 -->
				<div class="template1-accordion-item">
					<button class="template1-accordion-header active">
						<span class="template1-accordion-title">
							<i class="fas fa-laptop"></i>
							<span>Laptop Pro</span>
						</span>
						<i class="fas fa-chevron-down template1-accordion-icon"></i>
					</button>
					<div class="template1-accordion-content active">
						<div class="template1-accordion-text">
							Experience the ultimate computing power with our Premium Laptop Pro. Featuring the latest Intel processor, 16GB RAM, and a stunning 4K display, this laptop is designed for professionals who demand excellence. Perfect for graphic design, video editing, and intensive multitasking.
						</div>
					</div>
				</div>

				<!-- Accordion Item 2 -->
				<div class="template1-accordion-item">
					<button class="template1-accordion-header">
						<span class="template1-accordion-title">
							<i class="fas fa-headphones"></i>
							<span>Audio Pro</span>
						</span>
						<i class="fas fa-chevron-down template1-accordion-icon"></i>
					</button>
					<div class="template1-accordion-content">
						<div class="template1-accordion-text">
							Immerse yourself in crystal-clear audio with our Wireless Headphones Max. Active noise cancellation, 30-hour battery life, and premium comfort make these the perfect companion for music lovers, travelers, and professionals who need focus in noisy environments.
						</div>
					</div>
				</div>

				<!-- Accordion Item 3 -->
				<div class="template1-accordion-item">
					<button class="template1-accordion-header">
						<span class="template1-accordion-title">
							<i class="fas fa-clock"></i>
							<span>Smart Watch</span>
						</span>
						<i class="fas fa-chevron-down template1-accordion-icon"></i>
					</button>
					<div class="template1-accordion-content">
						<div class="template1-accordion-text">
							Stay connected and track your fitness goals with the Smart Watch Ultra. Features include heart rate monitoring, GPS tracking, water resistance, and a vibrant AMOLED display. Compatible with both iOS and Android devices.
						</div>
					</div>
				</div>

				<!-- Accordion Item 4 -->
				<div class="template1-accordion-item">
					<button class="template1-accordion-header">
						<span class="template1-accordion-title">
							<i class="fas fa-battery-full"></i>
							<span>Power Bank</span>
						</span>
						<i class="fas fa-chevron-down template1-accordion-icon"></i>
					</button>
					<div class="template1-accordion-content">
						<div class="template1-accordion-text">
							Never run out of battery with our high-capacity Portable Power Bank. 20,000mAh capacity can charge your smartphone up to 5 times. Fast charging technology and multiple ports make it essential for travelers and busy professionals.
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
