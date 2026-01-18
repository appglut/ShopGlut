<?php
namespace Shopglut\layouts\singleProduct;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class chooseTemplates {

	public function __construct() {

		add_action( 'admin_post_create_single_product_layout', array( $this, 'handleCreateSingleProductLayout' ) );
		add_action( 'wp_ajax_shopglut_get_template_demo_content', array( $this, 'handleGetTemplateDemoContent' ) );
		add_action( 'wp_ajax_nopriv_shopglut_get_template_demo_content', array( $this, 'handleGetTemplateDemoContent' ) );

	}

	/**
	 * Check if the single product pro plugin is active
	 */
	private function is_single_product_pro_active() {
		// Check for the new single product pro plugin
		if (class_exists('Shopglut\layouts\singleProductPro\dataManage')) {
			return true;
		}

		if (is_plugin_active('shopglut-singleproduct-pro/shopglut-singleproduct-pro.php')) {
			return true;
		}

		if (defined('SHOPGLUT_SINGLEPRODUCT_PRO_VERSION')) {
			return true;
		}

		return false;
	}

	/**
	 * Check if templatePro1 pro features are available
	 */
	private function is_template1_pro_available() {
		return $this->is_single_product_pro_active();
	}

	public function loadSingleProductTemplates() {
		$tab_names = [
			'tab1' => [
				'name' => esc_html__("Free Templates", 'shopglut'),
				'templates' => ['template1', 'template2', 'template3']
				// 'template4', 'template5', 'template6', 'template7']
			],
			'tab2' => [
				'name' => esc_html__("Pro Templates", 'shopglut'),
				'templates' => ['templatePro1']
				//'templatePro2', 'templatePro3', 'templatePro4', 'templatePro5', 'templatePro6', 'templatePro7', 'templatePro8', 'templatePro9', 'templatePro10']
			]

		];

		$template_names = [
			'template1' => esc_html__("Template One", 'shopglut'),
			'template2' => esc_html__("Template Two ", 'shopglut'),
			'template3' => esc_html__("Template Three ", 'shopglut'),
			'template4' => esc_html__("Template Four ", 'shopglut'),
			'template5' => esc_html__("Template Five ", 'shopglut'),
			'template6' => esc_html__("Template Six ", 'shopglut'),
			'template7' => esc_html__("Template Seven ", 'shopglut'),
			'template8' => esc_html__("Template Eight ", 'shopglut'),
			'template9' => esc_html__("Template Nine ", 'shopglut'),
			'templatePro1' => esc_html__("Pro Template One", 'shopglut'),
			'templatePro2' => esc_html__("Pro Template Two", 'shopglut'),
			'templatePro3' => esc_html__("Pro Template Three", 'shopglut'),
			'templatePro4' => esc_html__("Pro Template Four", 'shopglut'),
			'templatePro5' => esc_html__("Pro Template Five", 'shopglut'),
			'templatePro6' => esc_html__("Pro Template Six", 'shopglut'),
			'templatePro7' => esc_html__("Pro Template Seven", 'shopglut'),
			'templatePro8' => esc_html__("Pro Template Eight", 'shopglut'),
			'templatePro9' => esc_html__("Pro Template Nine", 'shopglut'),
			'templatePro10' => esc_html__("Pro Template Ten", 'shopglut'),
		];

		// Define template images using the constant
		$template_images = [
			'template1' => 'template1.jpg',
			'template2' => 'template1.png',
			'template3' => 'template1.png',
			'template4' => 'template1.png',
			'template5' => 'template1.png',
			'template6' => 'template1.png',
			'template7' => 'template1.png',
			'template8' => 'template1.png',
			'templatePro1' => 'template1.png',
			'templatePro2' => 'template1.png',
			'templatePro3' => 'template1.png',
			'templatePro4' => 'template1.png',
			'templatePro5' => 'template1.png',
			'templatePro6' => 'template1.png',
			'templatePro7' => 'template1.png',
			'templatePro8' => 'template1.png',
			'templatePro9' => 'template1.png',
			'templatePro10' => 'template1.png',
		];

		// Define which templates use HTML preview vs image preview
		$html_preview_templates = ['template1', 'template2', 'template3', 'template4', 'template5', 'template6', 'template7', 'templatePro1', 'templatePro2', 'templatePro3', 'templatePro4', 'templatePro5', 'templatePro6', 'templatePro7', 'templatePro8', 'templatePro9', 'templatePro10'];

		?>
		<div class="shopg-tab-container shopg-template-gallery" id="shopg-template-gallery">
			<!-- Template Gallery Loader -->
			<div id="template-gallery-loader" class="template-gallery-loader">
				<div class="loader-spinner"></div>
			</div>

			<div class="gallery-content-wrapper" >
				<ul class="shopg-tabs">
					<?php foreach ($tab_names as $tab_id => $tab): ?>
						<li class="shopg-tab" data-tab="<?php echo esc_attr($tab_id); ?>">
							<?php echo esc_html($tab['name']); ?>
						</li>
					<?php endforeach; ?>
				</ul>

			<?php foreach ($tab_names as $tab_id => $tab): ?>
				<div class="shopg-tab-content" id="<?php echo esc_attr($tab_id); ?>" style="background: transparent !important; border: none !important; padding: 20px 0 !important; flex-direction: column !important; gap: 0 !important;">
					<?php
					$template_count = 0;
					foreach ($tab['templates'] as $layout_template):
					$template_count++;
					$is_html_preview = in_array($layout_template, $html_preview_templates);
					?>
						<div class="shopg-template-preview" data-template="<?php echo esc_attr($layout_template); ?>" style="border: 3px solid #e1e5e9 !important; border-radius: 16px !important; margin: 0 auto !important; padding: 0 !important; background: #fff !important; box-shadow: 0 6px 20px rgba(0,0,0,0.15), 0 2px 8px rgba(0,0,0,0.1) !important; transition: all 0.3s ease !important; overflow: hidden !important; position: relative !important; display: block !important; width: 90% !important; box-sizing: border-box !important;">
							<!-- Enhanced Gradient Accent Bar -->
							<div class="template-header" style="padding: 22px 20px 18px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); background: linear-gradient(135deg, #2271b1 0%, #2a80b8 100%); color: white; position: relative;">
								<h2 style="margin: 0 auto; font-size: 16px; font-weight: 600; text-align: center; color:#fff; text-shadow: 0 1px 2px rgba(0,0,0,0.1);"><?php echo wp_kses_post($template_names[$layout_template]) ?? ''; ?></h2>
							</div>

							<div class="template-content product-demo">
								<?php if ($is_html_preview): ?>
									<!-- HTML Demo Preview for <?php echo esc_html($layout_template); ?> -->
									<div class="template-html-container">
										<!-- Background: Scaled HTML Preview -->
										<div class="html-preview-background">
											<?php $this->renderTemplatePreview($layout_template); ?>
										</div>

										<!-- Foreground: View Demo Button with Overlay -->
										<div class="html-preview-overlay">
											<button type="button" class="demo-view-btn" onclick="console.log('Template button clicked: <?php echo esc_attr($layout_template); ?>'); (typeof openHtmlDemoModal !== 'undefined' ? openHtmlDemoModal : window.originalOpenHtmlDemoModal)('<?php echo esc_attr($layout_template); ?>', 'single-product')">
												<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
													<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
												<span>View Live Demo</span>
											</button>
										</div>
									</div>
								<?php else: ?>
									<!-- Image Preview for <?php echo esc_html($layout_template); ?> -->
									<div class="template-image-container" onclick="openImageModal('<?php echo esc_attr($template_images[$layout_template]); ?>')">
										<!-- Loading Placeholder -->
										<div class="image-loading-placeholder">
											<div class="loading-skeleton">
												<div class="skeleton-shimmer"></div>
											</div>
											<div class="loading-icon">
												<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M21 19V5C21 3.9 20.1 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19ZM8.5 13.5L11 16.51L14.5 12L19 18H5L8.5 13.5Z" fill="#e0e0e0"/>
												</svg>
											</div>
										</div>

										<!-- Template Image (same for preview and modal) -->
										<img src="<?php echo esc_url(SHOPGLUT_URL .'global-assets/images/singleproduct-templates/'. $template_images[$layout_template]);// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>"
											 alt="<?php echo esc_attr($template_names[$layout_template]); ?>"
											 class="template-preview-image"
											 loading="lazy">

										<!-- Hover Overlay -->
										<div class="image-overlay">
											<div class="expand-icon-container">
												<svg class="expand-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M15 3H21V9M9 21H3V15M21 3L14 10M3 21L10 14" stroke="#333333" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<div class="template-footer">
								<?php
								// Check if this template requires pro and if pro is active
								// All templates starting with 'templatePro' require the pro plugin
								$is_pro_template_required = (strpos($layout_template, 'templatePro') === 0);
								$is_pro_available = $this->is_template1_pro_available();
								$should_disable = $is_pro_template_required && !$is_pro_available;
								?>

								<?php if ($should_disable): ?>
									<!-- Pro upgrade link -->
									<div class="template-pro-locked">
										<a href="<?php echo esc_url(SHOPGLUT_PRICING_URL); ?>" target="_blank" rel="noopener noreferrer" class="upgrade-to-pro-link">
											<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
											</svg>
											<span><?php esc_html_e("Upgrade to Pro", 'shopglut'); ?></span>
											<span class="pro-link-message"><?php esc_html_e("to use this template", 'shopglut'); ?></span>
										</a>
									</div>
								<?php else: ?>
									<!-- Normal button for templates without pro requirement or when pro is active -->
									<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
										<input type="hidden" name="action" value="create_single_product_layout">
										<input type="hidden" name="layout_template" value="<?php echo esc_attr($layout_template); ?>">
										<?php
										global $wpdb;
										$table_name = $wpdb->prefix . 'shopglut_single_product_layout';

										// Get next layout ID with caching
										$cache_key = 'shopglut_single_product_max_layout_id';
										$max_id = wp_cache_get( $cache_key );

										if ( false === $max_id ) {
											// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for MAX ID lookup, using %i for table name
											$max_id = $wpdb->get_var( $wpdb->prepare(
												"SELECT MAX(id) FROM {$wpdb->prefix}shopglut_single_product_layout"
											) ); // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnnecessaryPrepare -- No variables to replace, but prepare() used for consistency
											wp_cache_set( $cache_key, $max_id, '', 300 ); // Cache for 5 minutes
										}

										$layout_id = intval($max_id) + 1 ?: 1;
										?>
										<input type="hidden" name="layout_id" value="<?php echo esc_attr($layout_id); ?>">
										<?php wp_nonce_field('create_single_product_layout_nonce', 'create_single_product_layout_nonce'); ?>
										<button type="submit" class="choose-template-btn">
											<?php esc_html_e("Choose & Customize", 'shopglut'); ?>
										</button>
									</form>
								<?php endif; ?>
							</div>
						</div>

						<!-- Add professional divider between templates -->
						<?php if ($template_count < count($tab['templates'])): ?>
						<div style="margin: 40px 0; padding: 20px 0;">
							<div style="width:90%; margin:0 auto; height: 3px; background: linear-gradient(90deg,
								#d1d5db 0%,
								#3b82f6 25%,
								#1d4ed8 50%,
								#3b82f6 75%,
								#d1d5db 100%);
								border-radius: 2px;
								box-shadow: 0 2px 4px rgba(29, 78, 216, 0.1);">
							</div>
						</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			</div><!-- End of gallery-content-wrapper -->
		</div>

		<!-- Image Modal -->
		<div id="imageModal" class="shopglut-template-modal-image-modal" style="display: none;">
			<div class="shopglut-template-modal-modal-content">
				<span class="shopglut-template-modal-close-modal" onclick="closeImageModal()">&times;</span>
				<div class="shopglut-template-modal-modal-body">
					<img id="modalMainImage" src="" alt="Template Preview" class="shopglut-template-modal-modal-image"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				</div>
			</div>
		</div>

		<!-- HTML Demo Modal -->
		<div id="shopglut-html-demo-modal" class="shopglut-template-modal-image-modal" style="display: none;">
			<div class="shopglut-template-modal-modal-content shopglut-html-demo-modal-content">
				<span class="shopglut-template-modal-close-modal" onclick="closeHtmlDemoModal('single-product')">&times;</span>
				<div class="shopglut-template-modal-modal-body">
					<div class="html-demo-header" id="htmlDemoHeader">
						<h3 class="html-demo-header-title" id="htmlDemoTitle">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M15 3V7H9V3H15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							Template Preview
						</h3>
						<p class="html-demo-header-subtitle">Explore the full interactive product page layout below</p>
					</div>
					<div id="htmlDemoContent" class="html-demo-full-view">
						<?php $this->renderTemplatePreview('template1'); ?>
					</div>
				</div>
			</div>
		</div>

		<script>
		// Image modal functions
		function openImageModal(imageName) {
			const modal = document.getElementById('imageModal');
			const mainImage = document.getElementById('modalMainImage');
			const assetsUrl = '<?php echo esc_js(SHOPGLUT_URL . "global-assets/images/singleproduct-templates/"); ?>';

			if (!modal || !mainImage) {
				return;
			}

			mainImage.src = assetsUrl + imageName;
			modal.style.setProperty('display', 'flex', 'important');
			modal.style.setProperty('z-index', '999999', 'important');
			document.body.style.overflow = 'hidden';
		}

		function closeImageModal() {
			const modal = document.getElementById('imageModal');
			if (modal) {
				modal.style.setProperty('display', 'none', 'important');
				document.body.style.overflow = 'auto';
			}
		}

		// Store the original openHtmlDemoModal function if it exists
		if (typeof openHtmlDemoModal !== 'undefined') {
			window.originalOpenHtmlDemoModal = openHtmlDemoModal;
		}

		// Tab switching functionality
		document.addEventListener('DOMContentLoaded', function() {
			// Add CSS for tab styling
			const style = document.createElement('style');
			style.textContent = `
				.shopg-tab {
					cursor: pointer;
					transition: all 0.3s ease;
					padding: 10px 20px;
					background: #7340b0;
					color: white;
					border-radius: 4px 4px 0 0;
				}
				.shopg-tab:hover {
					background: #5a2a8b;
				}
				.shopg-tab.active {
					background: #ffffff;
					color: #7340b0;
				}
			`;
			document.head.appendChild(style);

			// Tab click handler
			const tabButtons = document.querySelectorAll('.shopg-tab');
			const tabContents = document.querySelectorAll('.shopg-tab-content');

			// Function to show a specific tab
			function showTab(tabId) {
				// Remove active class from all tabs
				tabButtons.forEach(function(btn) {
					btn.classList.remove('active');
				});

				// Hide all tab contents
				tabContents.forEach(function(content) {
					content.style.display = 'none';
				});

				// Add active class to clicked button
				const clickedBtn = document.querySelector('.shopg-tab[data-tab="' + tabId + '"]');
				if (clickedBtn) {
					clickedBtn.classList.add('active');
				}

				// Show the selected tab content
				const targetContent = document.getElementById(tabId);
				if (targetContent) {
					targetContent.style.display = 'flex';
				}
			}

			// Attach click handlers
			tabButtons.forEach(function(tab) {
				tab.addEventListener('click', function(e) {
					e.preventDefault();
					const tabId = this.getAttribute('data-tab');
					showTab(tabId);
				});
			});

			// Activate first tab by default
			if (tabButtons.length > 0 && tabContents.length > 0) {
				showTab(tabButtons[0].getAttribute('data-tab'));
			}
		});

		// Image loading handler
		document.addEventListener('DOMContentLoaded', function() {
			const images = document.querySelectorAll('.template-preview-image');
			images.forEach(function(img) {
				if (img.complete && img.naturalHeight !== 0) {
					const placeholder = img.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.style.display = 'none';
					}
					img.classList.add('loaded');
				}

				img.addEventListener('load', function() {
					const placeholder = this.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.style.display = 'none';
					}
					this.classList.add('loaded');
				});

				img.addEventListener('error', function() {
					const placeholder = img.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Image not found</div>';
					}
					this.style.display = 'none';
				});
			});
		});

		// Close modals when clicking outside or pressing Escape
		document.addEventListener('click', function(e) {
			const imageModal = document.getElementById('imageModal');
			if (e.target === imageModal) {
				closeImageModal();
			}
		});

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeImageModal();
			}
		});

		// Global showTab function for tabs
		window.showTab = function(tabName) {
			// Target only tab contents within the template-demo-container to avoid duplicates
			var container = document.querySelector('.template-demo-container');
			var tabContents = container ? container.querySelectorAll(".tab-content") : document.querySelectorAll(".tab-content");
			tabContents.forEach(function(content) {
				content.classList.remove("active");
			});

			// Remove active class from all tab buttons
			var tabButtons = document.querySelectorAll(".tab-button");
			tabButtons.forEach(function(button) {
				button.classList.remove("active");
			});

			// Show selected tab content - prefer the one within template-demo-container
			var selectedTab = container ? container.querySelector("#" + tabName) : document.getElementById(tabName);
			if (selectedTab) {
				selectedTab.classList.add("active");

				// Force display with inline styles as backup
				selectedTab.style.display = 'block';
				selectedTab.style.visibility = 'visible';
				selectedTab.style.opacity = '1';
				selectedTab.style.position = 'static';
			}

			// Add active class to clicked button
			var buttons = document.querySelectorAll(".tab-button");
			buttons.forEach(function(button) {
				if (button.getAttribute("onclick") && button.getAttribute("onclick").includes(tabName)) {
					button.classList.add("active");
				}
			});
		};

		// Also define it without window. prefix for compatibility
		function showTab(tabName) {
			return window.showTab(tabName);
		}

		</script>

		<!-- Include global template CSS and JS -->
		<link rel="stylesheet" href="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/css/template-html-demo.css'); ?>">

		<!-- Define global variables for the template HTML demo -->
		<script>
		window.shopglut_admin_vars = {
			ajax_url: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
			nonce: '<?php echo esc_js(wp_create_nonce('shopglut_admin_nonce')); ?>'
		};
		</script>
		<script src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/js/template-html-demo.js'); ?>"></script>

		<style>
		/* Ensure proper overflow for all templates */
		.shopg-template-preview .html-preview-background {
			height: 400px !important;
			overflow: hidden !important;
			position: relative !important;
		}

		

		.shopg-template-preview .shopglut-single-template2 .container {
			max-width: none !important;
			padding: 0 !important;
		}

		.shopg-template-preview .shopglut-single-template2 .product-page {
			gap: 30px !important;
		}

		.shopg-template-preview .shopglut-single-template2 .product-tabs,
		.shopg-template-preview .shopglut-single-template2 .related-products {
			display: none !important;
		}

		

		.shopg-template-preview .shopglut-single-product.template1 .single-product-container {
			max-width: none !important;
		}

		/* Ensure the template footer is visible for all templates */
		.template-footer {
			display: block !important;
		}

		/* Fix Template1 display in modal */
		.html-demo-full-view .shopglut-single-product.template1 {
			background: #ffffff !important;
			width: 100% !important;
			max-width: none !important;
			margin: 0 !important;
			padding: 0 !important;
			box-sizing: border-box !important;
		}

		.html-demo-full-view .shopglut-single-product.template1 .single-product-container {
			background: #ffffff !important;
			max-width: 1200px !important;
			margin: 0 auto !important;
			padding: 0 !important;
			width: 100% !important;
		}

		.html-demo-full-view .shopglut-single-product.template1 .product-main-wrapper {
			display: grid !important;
			grid-template-columns: 1fr 1fr !important;
			gap: 40px !important;
			width: 100% !important;
		}

		/* Fix Template2 display in modal */
		.html-demo-full-view .shopglut-single-template2 {
			background: #ffffff !important;
			width: 100% !important;
			max-width: none !important;
			margin: 0 !important;
			padding: 0 !important;
			box-sizing: border-box !important;
		}

		.html-demo-full-view .shopglut-single-template2 .container {
			max-width: 1200px !important;
			margin: 0 auto !important;
			padding: 0 20px !important;
			width: 100% !important;
		}

		.html-demo-full-view .shopglut-single-template2 .product-page {
			display: grid !important;
			grid-template-columns: 1fr 1fr !important;
			gap: 40px !important;
			width: 100% !important;
		}

		.html-demo-full-view .shopglut-single-template2 .product-gallery {
			width: 100% !important;
		}

		.html-demo-full-view .shopglut-single-template2 .product-details {
			width: 100% !important;
		}

		/* Ensure Template1 and Template2 styles don't break in modal */
		.html-demo-full-view .shopglut-single-product *,
		.html-demo-full-view .shopglut-single-product .single-product-container *,
		.html-demo-full-view .shopglut-single-product .product-main-wrapper *,
		.html-demo-full-view .shopglut-single-template2 *,
		.html-demo-full-view .shopglut-single-template2 .container *,
		.html-demo-full-view .shopglut-single-template2 .product-page * {
			box-sizing: border-box !important;
		}

		/* Fix responsive layout */
		@media (max-width: 768px) {
			.html-demo-full-view .shopglut-single-product.template1 .product-main-wrapper,
			.html-demo-full-view .shopglut-single-template2 .product-page {
				grid-template-columns: 1fr !important;
				gap: 20px !important;
			}
		}

		/* Reset any conflicting CSS */
		.shopg-template-preview .html-preview-background * {
			box-sizing: border-box !important;
		}

		.shopg-template-preview .html-preview-background > div {
			float: none !important;
			clear: both !important;
		}

		/* Pro Locked Template Styles - Classic Design */
		.template-pro-locked {
			padding: 20px !important;
		}

		.upgrade-to-pro-link {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 8px !important;
			width: 100% !important;
			padding: 14px 20px !important;
			background: #7c3aed !important;
			color: #ffffff !important;
			font-size: 15px !important;
			font-weight: 600 !important;
			text-decoration: none !important;
			border: none !important;
			border-radius: 6px !important;
			transition: all 0.3s ease !important;
			box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25) !important;
		}

		.upgrade-to-pro-link:hover {
			background: #6d28d9 !important;
			transform: translateY(-2px) !important;
			box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4) !important;
		}

		.upgrade-to-pro-link svg {
			flex-shrink: 0 !important;
		}

		.upgrade-to-pro-link .pro-link-message {
			font-weight: 400 !important;
			opacity: 0.9 !important;
		}
		</style>

		<?php
	}

	/**
	 * Render Template Preview (dynamic for any template)
	 */
	private function renderTemplatePreview($template_id) {
		// Check if this is a pro template (starts with 'templatePro')
		$is_pro_template = (strpos($template_id, 'templatePro') === 0);

		if ($is_pro_template) {
			// Pro templates use generic file names: templateMarkup.php, templateStyle.php
			$markup_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\templateMarkup";
			$style_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\templateStyle";

			// Include the template files
			$markup_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/templateMarkup.php";
			$style_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/templateStyle.php";
		} else {
			// Regular templates use template-specific file names: template1Markup.php, template1Style.php
			$markup_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\{$template_id}Markup";
			$style_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\{$template_id}Style";

			// Include the template files
			$markup_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/{$template_id}Markup.php";
			$style_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/{$template_id}Style.php";
		}

		if (file_exists($markup_file) && file_exists($style_file)) {
			require_once $markup_file;
			require_once $style_file;

			if (class_exists($markup_class) && class_exists($style_class)) {
				$markup = new $markup_class();
				$style = new $style_class();

				// Render styles inline
				$style->dynamicCss(0);

				// Render the markup
				$markup->layout_render(array('layout_id' => 0));

				// Load JavaScript for interactive functionality
				$is_pro_template = (strpos($template_id, 'templatePro') === 0);
				if ($is_pro_template) {
					// Pro templates use templateMarkup-frontend.js
					$js_file = SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/' . $template_id . '/templateMarkup-frontend.js';
				} else {
					// Regular templates use template1-frontend.js
					$js_file = SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/' . $template_id . '/' . $template_id . '-frontend.js';
				}

				if (file_exists($js_file)) {
					echo '<script>';
					readfile($js_file);
					echo '</script>';
				}
			}
		}
	}

	/**
	 * Render Template Full Demo (dynamic for any template)
	 */
	private function renderTemplateFullDemo($template_id) {
		// Check if this is a pro template (starts with 'templatePro')
		$is_pro_template = (strpos($template_id, 'templatePro') === 0);

		if ($is_pro_template) {
			// Pro templates use generic file names: templateMarkup.php, templateStyle.php
			$markup_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\templateMarkup";
			$style_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\templateStyle";

			// Include the template files
			$markup_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/templateMarkup.php";
			$style_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/templateStyle.php";
		} else {
			// Regular templates use template-specific file names: template1Markup.php, template1Style.php
			$markup_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\{$template_id}Markup";
			$style_class = "\\Shopglut\\layouts\\singleProduct\\templates\\{$template_id}\\{$template_id}Style";

			// Include the template files
			$markup_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/{$template_id}Markup.php";
			$style_file = SHOPGLUT_PATH . "src/layouts/singleProduct/templates/{$template_id}/{$template_id}Style.php";
		}

		if (file_exists($markup_file) && file_exists($style_file)) {
			require_once $markup_file;
			require_once $style_file;

			if (class_exists($markup_class) && class_exists($style_class)) {
				$markup = new $markup_class();
				$style = new $style_class();

				// Render styles inline
				$style->dynamicCss(0);

				// Add additional modal-specific styles to ensure proper display
				echo '<style>
				.template-demo-container {
					padding: 20px !important;
					background: #fff !important;
					border-radius: 8px !important;
					margin: 0 !important;
				}
				.template-demo-container .shopglut-single-product,
				.template-demo-container .shopglut-single-template2 {
					background: #fff !important;
				}
				</style>';

				// Add container with proper padding
				echo '<div class="template-demo-container">';

				// Render the markup with default settings
				$template_data = array(
					'layout_id' => 0,
					'settings' => array()
				);

				$markup->layout_render($template_data);

				echo '</div>';

				// Load JavaScript for interactive functionality
				$is_pro_template = (strpos($template_id, 'templatePro') === 0);
				if ($is_pro_template) {
					// Pro templates use templateMarkup-frontend.js
					$js_file = SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/' . $template_id . '/templateMarkup-frontend.js';
				} else {
					// Regular templates use template1-frontend.js
					$js_file = SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/' . $template_id . '/' . $template_id . '-frontend.js';
				}

				if (file_exists($js_file)) {
					echo '<script>';
					readfile($js_file);
					echo '</script>';
				}
			}
		}
	}

	/**
	 * Handle AJAX request for template demo content
	 */
	public function handleGetTemplateDemoContent() {
		// Verify nonce
		if (!wp_verify_nonce($_POST['nonce'], 'shopglut_admin_nonce')) {
			wp_die('Security check failed', 'Error', ['response' => 403]);
		}

		$template_id = isset($_POST['template_id']) ? sanitize_text_field($_POST['template_id']) : '';
		$module_type = isset($_POST['module_type']) ? sanitize_text_field($_POST['module_type']) : '';

		if (empty($template_id) || $module_type !== 'single-product') {
			wp_die('Invalid parameters', 'Error', ['response' => 400]);
		}

		ob_start();

		// Use dynamic rendering for any template
		$this->renderTemplateFullDemo($template_id);

		$html = ob_get_clean();

		// Allow style and script tags in admin AJAX responses for template demos
		$allowed_html = wp_kses_allowed_html('post');

		// Allow style and script tags
		$allowed_html['style'] = array('media' => true);
		$allowed_html['script'] = array(
			'src' => true,
			'type' => true,
			'id' => true,
			'async' => true,
			'defer' => true
		);
		$allowed_html['link'] = array(
			'href' => true,
			'rel' => true,
			'type' => true,
			'id' => true,
			'media' => true
		);

		// Allow common attributes for all elements
		$common_attrs = array(
			'class' => true,
			'id' => true,
			'style' => true,
			'data-*' => true,
			'aria-*' => true,
			'role' => true,
			'hidden' => true,
		);

		// Apply common attributes to block elements
		$block_elements = array('div', 'section', 'article', 'aside', 'header', 'footer', 'nav', 'main');
		foreach ($block_elements as $tag) {
			if (!isset($allowed_html[$tag])) {
				$allowed_html[$tag] = array();
			}
			$allowed_html[$tag] = array_merge($allowed_html[$tag], $common_attrs);
		}

		// Specific element attributes
		$allowed_html['div'] = array_merge($allowed_html['div'] ?? array(), array(
			'data-layout-id' => true,
			'data-demo-mode' => true,
			'data-index' => true,
			'data-image-full' => true,
			'data-lightbox-enabled' => true,
			'data-cursor-style' => true,
			'data-hover-zoom-enabled' => true,
			'data-hover-zoom-level' => true,
			'data-alignment' => true,
			'data-hover-scale' => true,
			'data-tab' => true,
			'data-value' => true,
			'data-badge' => true,
		));

		$allowed_html['img'] = array_merge($allowed_html['img'] ?? array(), array(
			'src' => true,
			'alt' => true,
			'class' => true,
			'id' => true,
			'style' => true,
			'width' => true,
			'height' => true,
			'loading' => true,
			'data-index' => true,
			'data-image-full' => true,
			'srcset' => true,
			'sizes' => true,
		));

		$allowed_html['button'] = array_merge($allowed_html['button'] ?? array(), array(
			'type' => true,
			'class' => true,
			'id' => true,
			'style' => true,
			'name' => true,
			'value' => true,
			'disabled' => true,
			'onclick' => true,
			'data-value' => true,
			'aria-label' => true,
			'aria-disabled' => true,
		));

		$allowed_html['span'] = array_merge($allowed_html['span'] ?? array(), $common_attrs);
		$allowed_html['i'] = array_merge($allowed_html['i'] ?? array(), array(
			'class' => true,
			'style' => true,
			'aria-hidden' => true,
		));

		$allowed_html['svg'] = array_merge($allowed_html['svg'] ?? array(), array(
			'width' => true,
			'height' => true,
			'viewBox' => true,
			'fill' => true,
			'xmlns' => true,
			'class' => true,
			'style' => true,
		));

		$allowed_html['path'] = array_merge($allowed_html['path'] ?? array(), array(
			'd' => true,
			'stroke' => true,
			'stroke-width' => true,
			'stroke-linecap' => true,
			'stroke-linejoin' => true,
			'fill' => true,
			'fill-rule' => true,
		));

		$allowed_html['polyline'] = array_merge($allowed_html['polyline'] ?? array(), array(
			'points' => true,
			'stroke' => true,
			'stroke-width' => true,
			'stroke-linecap' => true,
			'stroke-linejoin' => true,
			'fill' => true,
		));

		$allowed_html['form'] = array_merge($allowed_html['form'] ?? array(), array(
			'action' => true,
			'method' => true,
			'class' => true,
			'id' => true,
			'data-product_id' => true,
		));

		$allowed_html['input'] = array_merge($allowed_html['input'] ?? array(), array(
			'type' => true,
			'name' => true,
			'value' => true,
			'class' => true,
			'id' => true,
			'placeholder' => true,
			'min' => true,
			'max' => true,
			'step' => true,
			'required' => true,
			'checked' => true,
			'disabled' => true,
			'style' => true,
		));

		$allowed_html['a'] = array_merge($allowed_html['a'] ?? array(), array(
			'href' => true,
			'class' => true,
			'id' => true,
			'target' => true,
			'rel' => true,
			'onclick' => true,
			'style' => true,
			'aria-label' => true,
		));

		$allowed_html['ul'] = array_merge($allowed_html['ul'] ?? array(), $common_attrs);
		$allowed_html['li'] = array_merge($allowed_html['li'] ?? array(), array(
			'class' => true,
			'id' => true,
			'style' => true,
			'data-tab' => true,
		));

		$allowed_html['h1'] = array_merge($allowed_html['h1'] ?? array(), $common_attrs);
		$allowed_html['h2'] = array_merge($allowed_html['h2'] ?? array(), $common_attrs);
		$allowed_html['h3'] = array_merge($allowed_html['h3'] ?? array(), $common_attrs);
		$allowed_html['h4'] = array_merge($allowed_html['h4'] ?? array(), $common_attrs);
		$allowed_html['h5'] = array_merge($allowed_html['h5'] ?? array(), $common_attrs);
		$allowed_html['h6'] = array_merge($allowed_html['h6'] ?? array(), $common_attrs);

		$allowed_html['p'] = array_merge($allowed_html['p'] ?? array(), $common_attrs);
		$allowed_html['label'] = array_merge($allowed_html['label'] ?? array(), array(
			'class' => true,
			'id' => true,
			'for' => true,
			'style' => true,
		));

		$allowed_html['select'] = array_merge($allowed_html['select'] ?? array(), array(
			'name' => true,
			'class' => true,
			'id' => true,
			'style' => true,
		));

		$allowed_html['textarea'] = array_merge($allowed_html['textarea'] ?? array(), array(
			'name' => true,
			'class' => true,
			'id' => true,
			'rows' => true,
			'cols' => true,
			'style' => true,
		));

		echo wp_kses($html, $allowed_html);
		wp_die();
	}

	public function handleCreateSingleProductLayout() {

		// 2. Verify nonce and capabilities
		if (
    !isset($_POST['create_single_product_layout_nonce']) ||
    !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_single_product_layout_nonce'])), 'create_single_product_layout_nonce') ||
    !current_user_can('manage_options')
		) {
			//error_log('Security check failed in HandleCreateLayout');
			wp_die('Security check failed', 'Error', ['response' => 403]);
		}
		try {

			// 4. Validate required POST data
			if (!isset($_POST['layout_id']) || !isset($_POST['layout_template'])) {
				//error_log('Missing required fields in HandleCreateLayout');
				wp_die('Missing required fields', 'Error', ['response' => 400]);
			}

			// 5. Prepare data for insertion
			$current_time = current_time('mysql');
			$data = array(
				'id' => absint($_POST['layout_id']),
				'layout_name' => sanitize_text_field('Layout(#' . absint($_POST['layout_id']) . ')'),
				'layout_template' => sanitize_text_field(wp_unslash($_POST['layout_template'])),
				'layout_settings' => '{}', // Default empty JSON object
				'created_at' => $current_time,
				'updated_at' => $current_time
			);

			// 6. Format specifiers for wpdb
			$format = array(
				'%d',  // id
				'%s',  // layout_name
				'%s',  // layout_template
				'%s',  // layout_settings
				'%s',  // created_at
				'%s'   // updated_at
			);

			// 7. Database insertion
			global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_single_product_layout';

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$inserted = $wpdb->insert($table_name, $data, $format);

			if ($inserted === false) {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				wp_die('Database insertion failed: ' . wp_kses_post($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// 8. Redirect on success
			$redirect_url = add_query_arg(
				array(
					'page' => 'shopglut_layouts',
					'editor' => 'single_product',
					'layout_id' => absint($_POST['layout_id'])
				),
				admin_url('admin.php')
			);

			//error_log('Redirecting to: ' . $redirect_url);
			wp_safe_redirect($redirect_url);
			exit;

		} catch (Exception $e) {
			//error_log('Exception in HandleCreateLayout: ' . $e->getMessage());
			wp_die('An error occurred: ' . wp_kses_post($e->getMessage()), 'Error', ['response' => 500]);
		}
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}