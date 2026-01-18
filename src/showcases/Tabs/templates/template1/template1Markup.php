<?php
namespace Shopglut\showcases\Tabs\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data, $layout_id) {
		$settings = $this->getLayoutSettings($layout_id);
		$this->render_tab_demo($settings, $layout_id);
	}

	public function render_tab($settings, $layout_id) {
		$products_to_show = isset($settings['products_to_show']) ? intval($settings['products_to_show']) : 6;

		// Query real WooCommerce products
		$tab_products = $this->get_woocommerce_products($products_to_show);

		// Font Awesome for icons - using local fallback bundled with theme/plugin
		wp_enqueue_style('font-awesome', SHOPGLUT_URL . 'assets/css/font-awesome.min.css', array(), '6.0.0');

		// Enhanced CSS styles from index.html
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

		.template1-tabs-container {
			padding: 20px;
		}

		.template1-tab-headers {
			display: flex;
			border-bottom: 2px solid #e0e0e0;
			margin-bottom: 20px;
		}

		.template1-tab-header {
			background: none;
			border: none;
			padding: 15px 20px;
			cursor: pointer;
			display: flex;
			align-items: center;
			gap: 8px;
			font-size: 16px;
			color: #666;
			transition: all 0.3s ease;
			border-bottom: 3px solid transparent;
			flex: 1;
			justify-content: center;
		}

		.template1-tab-header:hover {
			background: #f5f5f5;
			color: #333;
		}

		.template1-tab-header.active {
			color: #667eea;
			border-bottom-color: #667eea;
			background: rgba(102, 126, 234, 0.1);
		}

		.template1-tab-header i {
			font-size: 18px;
		}

		.template1-tab-contents {
			padding: 20px 0;
		}

		.template1-tab-content {
			display: none;
			padding: 20px;
			animation: fadeIn 0.3s ease-in;
		}

		.template1-tab-content.active {
			display: block;
		}

		.template1-tab-title {
			color: #333;
			margin-bottom: 15px;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.template1-tab-text {
			color: #666;
			line-height: 1.6;
			font-size: 16px;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Responsive Design */
		@media (max-width: 600px) {
			.template1-tab-headers {
				flex-wrap: wrap;
			}

			.template1-tab-header {
				flex: 1 1 50%;
				min-width: 120px;
			}

			.template1-container {
				margin: 10px;
				border-radius: 10px;
			}

			.template1-title {
				padding: 20px;
				font-size: 24px;
			}
		}

		@media (max-width: 400px) {
			.template1-tab-header {
				flex: 1 1 100%;
			}

			.template1-tab-header span {
				font-size: 14px;
			}
		}
		</style>';

		// Enhanced JavaScript from index.html with modal-specific scope
		echo '<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Use a more specific selector to avoid conflicts with other modals
			const container = document.querySelector(".template1-container");
			if (!container) return;

			const tabHeaders = container.querySelectorAll(".template1-tab-header");
			const tabContents = container.querySelectorAll(".template1-tab-content");

			// Function to switch tabs
			function switchTab(tabId) {
				// Remove active class from all headers and contents
				tabHeaders.forEach(header => header.classList.remove("active"));
				tabContents.forEach(content => content.classList.remove("active"));

				// Add active class to selected header and content
				const selectedHeader = container.querySelector(`[data-tab="${tabId}"]`);
				const selectedContent = container.querySelector(`#${tabId}`);

				if (selectedHeader && selectedContent) {
					selectedHeader.classList.add("active");
					selectedContent.classList.add("active");
				}
			}

			// Add click event listeners to tab headers
			tabHeaders.forEach(header => {
				header.addEventListener("click", function(e) {
					e.preventDefault();
					e.stopPropagation();
					const tabId = this.getAttribute("data-tab");
					switchTab(tabId);
				});
			});

			// Optional: Add keyboard navigation
			container.addEventListener("keydown", function(e) {
				if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
					e.preventDefault();
					const activeHeader = container.querySelector(".template1-tab-header.active");
					const headers = Array.from(tabHeaders);
					const currentIndex = headers.indexOf(activeHeader);

					let nextIndex;
					if (e.key === "ArrowLeft") {
						nextIndex = currentIndex > 0 ? currentIndex - 1 : headers.length - 1;
					} else {
						nextIndex = currentIndex < headers.length - 1 ? currentIndex + 1 : 0;
					}

					const nextHeader = headers[nextIndex];
					const nextTabId = nextHeader.getAttribute("data-tab");
					switchTab(nextTabId);
					nextHeader.focus();
				}
			});

			// Make tab headers focusable for keyboard navigation
			tabHeaders.forEach(header => {
				header.setAttribute("tabindex", "0");
			});

			// Show first tab by default
			if (tabHeaders.length > 0) {
				const firstTabId = tabHeaders[0].getAttribute("data-tab");
				switchTab(firstTabId);
			}
		});
		</script>';

		echo '<div class="template1-container">';
		echo '<h1 class="template1-title">Product Tabs</h1>';

		echo '<div class="template1-tabs-container">';
		// Tab Headers
		echo '<div class="template1-tab-headers">';

		if (!empty($tab_products)) {
			foreach ($tab_products as $index => $product) {
				$tab_id = 'product-' . $index;
				echo '<button class="template1-tab-header" data-tab="' . esc_attr($tab_id) . '">';
				echo '<i class="fas fa-box"></i>';
				echo '<span>' . esc_html($product['name']) . '</span>';
				echo '</button>';
			}
		} else {
			echo '<button class="template1-tab-header" data-tab="no-products">';
			echo '<i class="fas fa-info-circle"></i>';
			echo '<span>No Products</span>';
			echo '</button>';
		}

		echo '</div>';

		// Tab Contents
		echo '<div class="template1-tab-contents">';
		if (!empty($tab_products)) {
			foreach ($tab_products as $index => $product) {
				$tab_id = 'product-' . $index;
				echo '<div class="template1-tab-content" id="' . esc_attr($tab_id) . '">';
				echo '<h2 class="template1-tab-title"><i class="fas fa-box"></i> ' . esc_html($product['name']) . '</h2>';
				echo '<div class="template1-tab-text">';
				echo wp_kses_post($this->render_product_description($product));
				echo '</div>';
				echo '</div>';
			}
		} else {
			echo '<div class="template1-tab-content" id="no-products">';
			echo '<h2 class="template1-tab-title"><i class="fas fa-info-circle"></i> No Products Found</h2>';
			echo '<p class="template1-tab-text">No WooCommerce products found. Please add some products to your store.</p>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Demo version with custom products
	 */
	public function render_tab_demo($settings, $layout_id) {
		// Custom product data
		$tab_products = array(
			array(
				'name' => 'Laptop Pro',
				'description' => 'Experience the ultimate computing power with our Premium Laptop Pro. Featuring the latest Intel processor, 16GB RAM, and a stunning 4K display, this laptop is designed for professionals who demand excellence. Perfect for graphic design, video editing, and intensive multitasking.',
				'icon' => 'fa-laptop'
			),
			array(
				'name' => 'Audio Pro',
				'description' => 'Immerse yourself in crystal-clear audio with our Wireless Headphones Max. Active noise cancellation, 30-hour battery life, and premium comfort make these the perfect companion for music lovers, travelers, and professionals who need focus in noisy environments.',
				'icon' => 'fa-headphones'
			),
			array(
				'name' => 'Smart Watch',
				'description' => 'Stay connected and track your fitness goals with the Smart Watch Ultra. Features include heart rate monitoring, GPS tracking, water resistance, and a vibrant AMOLED display. Compatible with both iOS and Android devices.',
				'icon' => 'fa-clock'
			),
			array(
				'name' => 'Power Bank',
				'description' => 'Never run out of battery with our high-capacity Portable Power Bank. 20,000mAh capacity can charge your smartphone up to 5 times. Fast charging technology and multiple ports make it essential for travelers and busy professionals.',
				'icon' => 'fa-battery-full'
			)
		);

		// Font Awesome for icons - using local fallback bundled with theme/plugin
		wp_enqueue_style('font-awesome', SHOPGLUT_URL . 'assets/css/font-awesome.min.css', array(), '6.0.0');

		// Exact CSS from index.html
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

        .template1-tabs-container {
            padding: 20px;
        }

        .template1-tab-headers {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .template1-tab-header {
            background: none;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #666;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            flex: 1;
            justify-content: center;
        }

        .template1-tab-header:hover {
            background: #f5f5f5;
            color: #333;
        }

        .template1-tab-header.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .template1-tab-header i {
            font-size: 18px;
        }

        .template1-tab-contents {
            padding: 20px 0;
        }

        .template1-tab-content {
            display: none;
            padding: 20px;
            animation: fadeIn 0.3s ease-in;
        }

        .template1-tab-content.active {
            display: block;
        }

        .template1-tab-title {
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .template1-tab-text {
            color: #666;
            line-height: 1.6;
            font-size: 16px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .template1-tab-headers {
                flex-wrap: wrap;
            }

            .template1-tab-header {
                flex: 1 1 50%;
                min-width: 120px;
            }

            .template1-container {
                margin: 10px;
                border-radius: 10px;
            }

            .template1-title {
                padding: 20px;
                font-size: 24px;
            }
        }

        @media (max-width: 400px) {
            .template1-tab-header {
                flex: 1 1 100%;
            }

            .template1-tab-header span {
                font-size: 14px;
            }
        }
        </style>';

		// JavaScript for modal environments
		echo '<script>
        (function() {
            // Function to initialize tabs
            function initializeTabs() {
                // Look for tabs within the modal specifically
                const modal = document.getElementById("htmlDemoModal") || document;
                const tabHeaders = modal.querySelectorAll(".template1-tab-header");
                const tabContents = modal.querySelectorAll(".template1-tab-content");

                if (tabHeaders.length === 0 || tabContents.length === 0) {
                    return; // No tabs found
                }

                // Function to switch tabs
                function switchTab(tabId) {
                    // Remove active class from all headers and contents within modal
                    tabHeaders.forEach(header => header.classList.remove("active"));
                    tabContents.forEach(content => content.classList.remove("active"));

                    // Add active class to selected header and content
                    const selectedHeader = modal.querySelector(`[data-tab="${tabId}"]`);
                    const selectedContent = modal.querySelector(`#${tabId}`);

                    if (selectedHeader && selectedContent) {
                        selectedHeader.classList.add("active");
                        selectedContent.classList.add("active");
                    }
                }

                // Add click event listeners to tab headers
                tabHeaders.forEach(header => {
                    header.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const tabId = this.getAttribute("data-tab");
                        switchTab(tabId);
                    });
                });

                // Make tab headers focusable for keyboard navigation
                tabHeaders.forEach(header => {
                    header.setAttribute("tabindex", "0");
                });

                // Show first tab by default if none is active
                const hasActiveTab = modal.querySelector(".template1-tab-header.active");
                if (!hasActiveTab && tabHeaders.length > 0) {
                    const firstTabId = tabHeaders[0].getAttribute("data-tab");
                    switchTab(firstTabId);
                }
            }

            // Initialize on DOM content loaded
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", initializeTabs);
            } else {
                initializeTabs(); // DOM already loaded
            }

            // Also initialize when modal becomes visible (for dynamic content)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === "childList") {
                        const modal = document.getElementById("htmlDemoModal");
                        if (modal && modal.style.display !== "none") {
                            setTimeout(initializeTabs, 100); // Small delay for modal to fully render
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
            setTimeout(initializeTabs, 500);
        })();
        </script>';

		// HTML with WooCommerce products
		?>
		<div class="template1-container">
			<h1 class="template1-title">Product Tabs</h1>

			<div class="template1-tabs-container">
				<!-- Tab Headers -->
				<div class="template1-tab-headers">
					<?php if (!empty($tab_products)): ?>
						<?php foreach ($tab_products as $index => $product): ?>
							<?php
							$tab_id = 'product-' . $index;
							$active_class = $index === 0 ? 'active' : '';
							$icon_class = isset($product['icon']) ? $product['icon'] : 'fa-box';
							?>
							<button class="template1-tab-header <?php echo esc_attr($active_class); ?>" data-tab="<?php echo esc_attr($tab_id); ?>">
								<i class="fas <?php echo esc_attr($icon_class); ?>"></i>
								<span><?php echo esc_html($product['name']); ?></span>
							</button>
						<?php endforeach; ?>
					<?php else: ?>
						<button class="template1-tab-header active" data-tab="no-products">
							<i class="fas fa-info-circle"></i>
							<span>No Products</span>
						</button>
					<?php endif; ?>
				</div>

				<!-- Tab Contents -->
				<div class="template1-tab-contents">
					<?php if (!empty($tab_products)): ?>
						<?php foreach ($tab_products as $index => $product): ?>
							<?php
							$tab_id = 'product-' . $index;
							$active_class = $index === 0 ? 'active' : '';
							$icon_class = isset($product['icon']) ? $product['icon'] : 'fa-box';
							$description = isset($product['description']) ? $product['description'] : 'No description available for this product.';
							?>
							<div class="template1-tab-content <?php echo esc_attr($active_class); ?>" id="<?php echo esc_attr($tab_id); ?>">
								<div class="template1-tab-text">
									<?php echo wp_kses_post($description); ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="template1-tab-content active" id="no-products">
							<h2 class="template1-tab-title"><i class="fas fa-info-circle"></i> No Products Found</h2>
							<p class="template1-tab-text">No products found. Please add some products to display.</p>
						</div>
					<?php endif; ?>
				</div>
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
		$tab_products = array();

		if ($products_query->have_posts()) {
			while ($products_query->have_posts()) {
				$products_query->the_post();
				$product = wc_get_product(get_the_ID());

				if ($product) {
					$tab_products[] = $this->get_product_data($product);
				}
			}
		}
		wp_reset_postdata();

		return $tab_products;
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
	 * Render product description panel
	 */
	private function render_product_description($product) {
		$product_obj = wc_get_product($product['id']);
		$description = $product_obj ? $product_obj->get_description() : '';
		$short_description = $product_obj ? $product_obj->get_short_description() : '';

		// Use short description if available, otherwise use full description
		$display_description = !empty($short_description) ? $short_description : $description;
		if (empty($display_description)) {
			$display_description = 'No description available for this product.';
		}

		ob_start();
		?>
		<div class="product-description">
			<?php echo wp_kses_post($display_description); ?>
		</div>
		<?php
		return ob_get_clean();
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
		$table_name = $wpdb->prefix . 'shopglut_tab_layouts';

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
		$tab_settings = null;

		if (isset($settings['shopg_product_tab_settings_template1'])) {
			$tab_settings = $this->flattenSettings($settings['shopg_product_tab_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$tab_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['autoplay']) || isset($settings['tab_settings'])) {
			$tab_settings = $this->flattenSettings($settings);
		}

		if ($tab_settings) {
			return $tab_settings;
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