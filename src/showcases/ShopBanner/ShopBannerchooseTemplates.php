<?php
namespace Shopglut\showcases\ShopBanner;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ShopBannerchooseTemplates {

	public function __construct() {
		add_action('admin_post_create_shopbanner_layout', array($this, 'handleCreateShopBannerEnhancement'));
		add_action('wp_ajax_get_shopbanner_demo_content', array($this, 'handleGetShopBannerDemoContent'));
	}

	public function loadShopBannerTemplates() {
		$tab_names = [
			'tab1' => [
				'name' => esc_html__("General", 'shopglut'),
				'templates' => ['template1']
			]
		];

		$template_names = ['template1' => esc_html__("Template One", 'shopglut')];

		// Define template images using the constant
		$template_images = [
			'template1' => 'template1.png'
		];

		?>
		<div class="shopg-tab-container shopg-template-gallery">
			<ul class="shopg-tabs">
				<?php foreach ($tab_names as $tab_id => $tab): ?>
					<li class="shopg-tab" data-tab="<?php echo esc_attr($tab_id); ?>">
						<?php echo esc_html($tab['name']); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php foreach ($tab_names as $tab_id => $tab): ?>
				<div class="shopg-tab-content" id="<?php echo esc_attr($tab_id); ?>">
					<?php foreach ($tab['templates'] as $layout_template): ?>
						<div class="shopg-template-preview" data-template="<?php echo esc_attr($layout_template); ?>">
							<div class="template-header">
								<h2><?php echo wp_kses_post($template_names[$layout_template]) ?? ''; ?></h2>
							</div>

							<div class="template-content product-demo">
								<!-- HTML Demo Preview for Quick View -->
								<div class="template-html-container">
									<!-- Background: Scaled HTML Preview -->
									<div class="html-preview-background">
										<?php $this->renderShopBannerPreview($layout_template); ?>
									</div>

									<!-- Foreground: View Demo Button with Overlay -->
									<div class="html-preview-overlay">
										<button type="button" class="demo-view-btn" onclick="openHtmlDemoModal('<?php echo esc_attr($layout_template); ?>', 'product-shopbanner')">
											<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
											<span>View Live Demo</span>
										</button>
									</div>
								</div>
							</div>

							<div class="template-footer">
								<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
									<input type="hidden" name="action" value="create_shopbanner_layout">
									<input type="hidden" name="layout_template" value="<?php echo esc_attr($layout_template); ?>">
									<?php wp_nonce_field('create_shopbanner_layout_nonce', 'create_shopbanner_layout_nonce'); ?>
									<button type="submit" class="choose-template-btn">
										<?php esc_html_e("Choose & Customize", 'shopglut'); ?>
									</button>
								</form>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Image Modal -->
		<div id="imageModal" class="shopglut-template-modal-image-modal" style="display: none;">
			<div class="shopglut-template-modal-modal-content">
				<button class="shopglut-template-modal-close-modal" onclick="closeImageModal()" aria-label="Close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
				<div class="shopglut-template-modal-modal-body">
					<img id="modalMainImage" src="" alt="Template Preview" class="shopglut-template-modal-modal-image"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				</div>
			</div>
		</div>
		<style>
		.shopg-template-gallery .shopg-template-preview {
			border: 1px solid #e0e0e0;
			border-radius: 12px;
			margin: 15px;
			padding: 0;
			background: #fff;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			transition: all 0.3s ease;
			overflow: hidden;
		}

		.shopg-template-gallery .shopg-template-preview:hover {
			box-shadow: 0 8px 25px rgba(0,0,0,0.15);
			transform: translateY(-2px);
		}

		.shopg-template-gallery .template-header {
			padding: 20px 20px 15px 20px;
			border-bottom: 1px solid #f0f0f0;
			background: #2271b1;
			color: white;
		}

		.shopg-template-gallery .template-header h2 {
			margin: 0;
			font-size: 16px;
			font-weight: 600;
			text-align: center;
			color: white;
		}

		.shopg-template-gallery .template-content.product-demo {
			padding: 10px;
			background: #fafafa;
			height: 320px;
			position: relative;
			overflow: hidden !important;
		}

		/* HTML Demo Preview Styles - same as other modules */
		.shopg-template-gallery .template-html-container {
			position: relative;
			width: 100%;
			height: 100%;
		}

		.shopg-template-gallery .html-preview-background {
			width: 75%;
			height: 100%;
			overflow: hidden !important;
			position: relative !important;
		}

		.shopg-template-gallery .html-preview-overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0, 0, 0, 0.7);
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			opacity: 0;
			transition: all 0.3s ease;
			border-radius: 8px;
			text-align: center;
		}

		.shopg-template-gallery .template-content.product-demo:hover .html-preview-overlay {
			opacity: 1;
		}

		.shopg-template-gallery .demo-view-btn {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 10px;
			background: rgba(255, 255, 255, 0.95);
			color: #2271b1;
			border: 2px solid #2271b1;
			padding: 12px 20px;
			border-radius: 8px;
			font-weight: 600;
			font-size: 14px;
			cursor: pointer;
			transition: all 0.3s ease;
			backdrop-filter: blur(10px);
			text-decoration: none;
			margin: 0 auto;
			position: relative;
			transform: translateX(0);
		}

		.shopg-template-gallery .demo-view-btn:hover {
			background: #2271b1;
			color: white;
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(34, 113, 177, 0.3);
		}

		/* Additional centering fixes */
		.shopg-template-gallery .template-html-container {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
		}

		.shopg-template-gallery .html-preview-background {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
		}

		.shopg-template-gallery .demo-view-btn {
			position: absolute !important;
			top: 50% !important;
			left: 50% !important;
			transform: translate(-50%, -50%) !important;
			margin: 0 !important;
			z-index: 100 !important;
		}

		/* Override conflicting modal content styles for proper centering */
		.shopg-template-gallery .html-preview-background .shopbanner-modal-content,
		.shopg-template-gallery .html-preview-background .shopglut-product-shopbanner .shopbanner-modal-content {
			position: static !important;
			transform: none !important;
			max-height: none !important;
			box-shadow: none !important;
			background: transparent !important;
			margin: 0 !important;
			max-width: none !important;
			width: auto !important;
			border-radius: 0 !important;
			overflow: visible !important;
		}

		/* Override shopbanner modal styles in preview */
		.shopg-template-gallery .html-preview-background .shopbanner-modal {
			position: static !important;
			display: block !important;
			opacity: 1 !important;
			visibility: visible !important;
			transform: none !important;
			padding: 0 !important;
			margin: 0 !important;
			background: transparent !important;
			box-shadow: none !important;
			border-radius: 0 !important;
		}

		/* Ensure the shopbanner preview container is centered and fits properly */
		.shopg-template-gallery .html-preview-background .shopglut-shopbanner-preview-container {
			position: absolute !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			width: 200% !important;
			height: 200% !important;
			transform-origin: center center !important;
			overflow: hidden !important;
			top: -50% !important;
			left: -50% !important;
			margin: 0 !important;
			padding: 0 !important;
		}


		/* Fix modal content overflow in preview */
		.shopg-template-gallery .html-preview-background .shopbanner-modal,
		.shopg-template-gallery .html-preview-background .shopbanner-modal-content,
		.shopg-template-gallery .html-preview-background .shopbanner-inner {
			overflow: hidden !important;
			max-height: none !important;
			min-height: auto !important;
		}

		/* Ensure demo content fits perfectly */
		.shopg-template-gallery .html-preview-background .shopglut-product-shopbanner {
			overflow: hidden !important;
			height: 100% !important;
			min-height: 100% !important;
			max-height: 100% !important;
		}

		/* Fix gallery and info sections overflow */
		.shopg-template-gallery .html-preview-background .shopbanner-gallery,
		.shopg-template-gallery .html-preview-background .shopbanner-info {
			overflow: hidden !important;
			max-height: none !important;
		}

		/* Ensure all inner content fits without scrollbars */
		.shopg-template-gallery .html-preview-background .shopbanner-inner {
			overflow: hidden !important;
			max-height: none !important;
			height: auto !important;
		}

		/* Prevent any element from causing scrollbars */
		.shopg-template-gallery .template-html-container,
		.shopg-template-gallery .html-preview-background,
		.shopg-template-gallery .html-preview-overlay {
			overflow: hidden !important;
			scrollbar-width: none !important;
			-ms-overflow-style: none !important;
		}

		.shopg-template-gallery .template-html-container::-webkit-scrollbar,
		.shopg-template-gallery .html-preview-background::-webkit-scrollbar,
		.shopg-template-gallery .html-preview-overlay::-webkit-scrollbar {
			display: none !important;
		}

		.shopg-template-gallery .demo-view-btn svg {
			flex-shrink: 0;
		}

		.shopg-template-gallery .choose-template-btn {
			width: 100%;
			padding: 15px;
			background: #2271b1;
			color: white;
			border: none;
			border-radius: 0 0 12px 12px;
			cursor: pointer;
			font-weight: 600;
			font-size: 14px;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.shopg-template-gallery .choose-template-btn:hover {
			background: #135e96;
			transform: translateY(-1px);
		}

		/* Modal Styles */
		.shopglut-template-modal-image-modal {
			position: fixed !important;
			top: 0 !important;
			left: 0 !important;
			width: 100% !important;
			height: 100% !important;
			background: rgba(0, 0, 0, 0.9) !important;
			z-index: 999999 !important;
			display: none !important;
			align-items: center !important;
			justify-content: center !important;
			text-align: center;
		}

		.shopglut-template-modal-modal-content {
			background: white !important;
			border-radius: 12px !important;
			max-width: 95% !important;
			max-height: 95% !important;
			position: relative !important;
			overflow: visible !important;
			box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important;
			animation: modalSlideIn 0.3s ease-out !important;
			z-index: 1000000 !important;
		}

		@keyframes modalSlideIn {
			from {
				transform: scale(0.8) translateY(20px);
				opacity: 0;
			}
			to {
				transform: scale(1) translateY(0);
				opacity: 1;
			}
		}

		.shopglut-template-modal-close-modal {
			position: fixed !important;
			top: 15% !important;
			right: 13.5% !important;
			transform: translateY(-50%) !important;
			font-size: 0 !important;
			font-weight: bold !important;
			color: #555 !important;
			cursor: pointer !important;
			z-index: 1000001 !important;
			background: rgba(255, 255, 255, 0.95) !important;
			border-radius: 50% !important;
			width: 40px !important;
			height: 40px !important;
			display: none !important;
			align-items: center !important;
			justify-content: center !important;
			box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
			transition: all 0.3s ease !important;
			backdrop-filter: blur(10px) !important;
			border: 2px solid #e5e7eb !important;
			padding: 0 !important;
		}

		.shopglut-template-modal-close-modal svg {
			width: 24px !important;
			height: 24px !important;
			color: #555 !important;
			transition: color 0.3s ease !important;
		}

		.shopglut-template-modal-close-modal.content-loaded {
			display: flex !important;
		}

		.shopglut-template-modal-close-modal:hover {
			background: rgba(255, 255, 255, 1) !important;
			transform: scale(1.1) !important;
		}

		.shopglut-template-modal-close-modal:hover svg {
			color: #333 !important;
		}

		.shopglut-template-modal-modal-body {
			padding: 25px;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			text-align: center;
		}

		.shopglut-template-modal-modal-image {
			max-width: 100%;
			max-height: 85vh;
			border-radius: 8px;
			box-shadow: 0 8px 25px rgba(0,0,0,0.15);
		}

		/* Preview scaling for Quick View template1 */
		.shopg-template-gallery .html-preview-background .shopglut-shopbanner-preview-container {
			
			transform-origin: center center;
			width: 200%;
			height: 200%;
			overflow: visible;
			position: absolute;
			top: -50%;
			left: -50%;
		}

		/* Hide modal overlay and positioning for preview */
		.shopg-template-gallery .html-preview-background .shopbanner-modal {
			position: static !important;
			display: block !important;
			opacity: 1 !important;
			visibility: visible !important;
			transform: none !important;
			padding: 0 !important;
		}

		.shopg-template-gallery .html-preview-background .shopbanner-modal-overlay {
			display: none !important;
		}

		.shopg-template-gallery .html-preview-background .shopbanner-modal-content {
			position: static !important;
			transform: none !important;
			max-height: none !important;
			box-shadow: none !important;
			background: transparent !important;
		}

		.shopg-template-gallery .html-preview-background .shopbanner-close {
			display: none !important;
		}

		/* Fix Live Demo modal visibility issue - override template1 style conflicts */
		#htmlDemoModal .shopglut-product-shopbanner.template1 .shopbanner-modal {
			opacity: 1 !important;
			visibility: visible !important;
			position: fixed !important;
			display: flex !important;
		}

		@media (max-width: 768px) {
			.shopg-template-gallery .template-content.product-demo {
				height: 250px;
			}

			.shopg-template-gallery .shopg-template-preview {
				margin: 10px;
			}

			.shopglut-template-modal-modal-content {
				max-width: 95% !important;
				max-height: 95% !important;
			}
		}

		@media (max-width: 480px) {
			.shopg-template-gallery .template-content.product-demo {
				height: 200px;
			}

			.shopg-template-gallery .template-header {
				padding: 15px;
			}

			.shopg-template-gallery .template-header h2 {
				font-size: 14px;
			}
		}
		</style>

		<script>
		// Test AJAX directly
		function testShopBannerAjax() {
			fetch(ajax_url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'get_shopbanner_demo_content',
					template_id: 'template1',
					nonce: nonce
				})
			})
			.then(response => response.text())
			.then(html => {
				// Show close button when content loads successfully
				if (html && html.trim() !== '') {
					showCloseButtonOnContentLoad();
				}
			})
			.catch(error => {
				// Silently fail
			});
		}

		// Test on page load
		setTimeout(testShopBannerAjax, 2000);

		// Monitor DOM changes for modal content loading
		const observer = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
					mutation.addedNodes.forEach(function(node) {
						if (node.nodeType === 1) { // Element node
							// Check if this is modal content being loaded
							if (node.classList && node.classList.contains('shopglut-product-shopbanner') ||
								(node.id && node.id === 'htmlDemoModal') ||
								(node.querySelector && node.querySelector('.shopglut-product-shopbanner'))) {
								// Content loaded, show close button
								setTimeout(showCloseButtonOnContentLoad, 100);
							}
						}
					});
				}
			});
		});

		// Start observing the document body for changes
		observer.observe(document.body, {
			childList: true,
			subtree: true
		});

		// JavaScript functions are now handled by the main template-html-demo.js file

		// Function to show close button when content is loaded
		function showCloseButtonOnContentLoad() {
			const closeButton = document.querySelector('.shopglut-template-modal-close-modal');
			if (closeButton) {
				closeButton.classList.add('content-loaded');
			}
		}

		// Function to hide close button when modal opens (will be shown when content loads)
		function hideCloseButtonOnModalOpen() {
			const closeButton = document.querySelector('.shopglut-template-modal-close-modal');
			if (closeButton) {
				closeButton.classList.remove('content-loaded');
			}
		}

		// Keep image modal functionality for shopbanner images
		function openImageModal(imageName) {
			const modal = document.getElementById('imageModal');
			const mainImage = document.getElementById('modalMainImage');
			const assetsUrl = '<?php echo esc_js(SHOPGLUT_URL . "global-assets/images/shopbanner-templates/"); ?>';

			if (!modal || !mainImage) {
				return;
			}

			// Set the full-size image
			mainImage.src = assetsUrl + imageName;

			// Show modal with higher specificity
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

		function closeHtmlDemoModal() {
			const modal = document.getElementById('htmlDemoModal');
			if (modal) {
				modal.style.setProperty('display', 'none', 'important');
				document.body.style.overflow = 'auto';
				// Hide close button when modal is closed
				hideCloseButtonOnModalOpen();
			}
		}

		// Close modals when clicking outside or pressing Escape
		document.addEventListener('click', function(e) {
			const imageModal = document.getElementById('imageModal');
			const htmlModal = document.getElementById('htmlDemoModal');
			if (e.target === imageModal) {
				closeImageModal();
			}
			if (e.target === htmlModal) {
				closeHtmlDemoModal();
			}
		});

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeImageModal();
				closeHtmlDemoModal();
			}
		});
		</script>
		<?php
	}

	/**
	 * Render Quick View Preview - Using exact template1Markup.php and template1Style.php
	 */
	private function renderShopBannerPreview($layout_template) {
		// Include the template markup class
		require_once SHOPGLUT_PATH . 'src/showcases/ShopBanner/templates/template1/template1Markup.php';
		require_once SHOPGLUT_PATH . 'src/showcases/ShopBanner/templates/template1/template1Style.php';

		$markup = new \Shopglut\showcases\ShopBanner\templates\template1\template1Markup();
		$style = new \Shopglut\showcases\ShopBanner\templates\template1\template1Style();

		// Render styles inline for preview
		$style->dynamicCss(0);
		?>
		<div class="shopglut-shopbanner-preview-container">
			<?php
			// Render the demo content directly from template1Markup
			// Get default settings for demo
			$settings = $this->getDefaultShopBannerSettings();
			$markup->render_demo_shopbanner($settings, []);
			?>
		</div>
		<?php
	}

	/**
	 * Get default Quick View settings for demo
	 */
	private function getDefaultShopBannerSettings() {
		return array(
			'modal_width' => '1100px',
			'modal_border_radius' => '12px',
			'primary_color' => '#667eea',
			'primary_hover_color' => '#5a67d8',
			'sale_badge_color' => '#ef4444',
			'rating_color' => '#fbbf24',
			'modal_overlay_color' => 'rgba(0, 0, 0, 0.75)',
			'modal_overlay_blur' => 4,
			'modal_background_color' => '#ffffff',
			'close_button_size' => 40,
			'close_button_bg_color' => '#ffffff',
			'close_button_color' => '#374151',
		);
	}

	/**
	 * Render Quick View Full Demo (for modal view) - Using exact template1Markup.php and template1Style.php
	 */
	private function renderShopBannerFullDemo($layout_template) {
		// Include the template markup class
		require_once SHOPGLUT_PATH . 'src/showcases/ShopBanner/templates/template1/template1Markup.php';
		require_once SHOPGLUT_PATH . 'src/showcases/ShopBanner/templates/template1/template1Style.php';

		$markup = new \Shopglut\showcases\ShopBanner\templates\template1\template1Markup();
		$style = new \Shopglut\showcases\ShopBanner\templates\template1\template1Style();

		// Render styles inline
		$style->dynamicCss(0);

		// Get default settings for demo
		$settings = $this->getDefaultShopBannerSettings();

		// Render the full demo shopbanner
		$markup->render_demo_shopbanner($settings, []);
	}

	/**
	 * Handle AJAX request for shopbanner demo content
	 */
	public function handleGetShopBannerDemoContent() {
		// Verify nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_admin_nonce')) {
			wp_die('Security check failed');
		}

		// Get template ID
		if (!isset($_POST['template_id'])) {
			wp_die('Template ID not provided');
		}

		$template_id = sanitize_text_field(wp_unslash($_POST['template_id']));

		// Output full demo content
		$this->renderShopBannerFullDemo($template_id);
		wp_die();
	}

	public function handleCreateShopBannerEnhancement() {
		if (
			!isset($_POST['create_shopbanner_layout_nonce']) ||
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_shopbanner_layout_nonce'])), 'create_shopbanner_layout_nonce') ||
			!current_user_can('manage_options')
		) {
			wp_die('Security check failed', 'Error', ['response' => 403]);
		}

		try {
			// Validate required POST data
			if (!isset($_POST['layout_template'])) {
				wp_die('Missing required fields', 'Error', ['response' => 400]);
			}

			// Database insertion
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_shopbanner_layouts';

			// Prepare data for insertion (without id, let auto_increment handle it)
			$data = array(
				'layout_name' => '',  // Will be updated after insert with the actual ID
				'layout_template' => isset($_POST['layout_template']) ? sanitize_text_field(wp_unslash($_POST['layout_template'])) : '',
				'layout_settings' => '{}', // Default empty JSON object
			);

			// Format specifiers for wpdb
			$format = array(
				'%s',  // layout_name
				'%s',  // layout_template
				'%s',  // layout_settings
			);

	// Use $wpdb->insert() with proper error handling and caching
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Required for custom table operations
			$inserted = $wpdb->insert($table_name, $data, $format);

			if ($inserted === false) {
				wp_die('Database insertion failed: ' . esc_html($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// Get the auto-generated ID
			$layout_id = $wpdb->insert_id;

			// Clear any existing cache for this table
			$cache_key = 'shopglut_shopbanner_layouts';
			wp_cache_delete($cache_key, 'shopglut');

			// Update the layout_name with the actual ID using sprintf
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for custom table operations, cache cleared above
			$update_result = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, cache cleared above
				sprintf( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and sanitized parameters
					"UPDATE `%s` SET layout_name = %s WHERE id = %d",
					esc_sql($table_name),
					"'" . esc_sql(sanitize_text_field('Layout(#' . $layout_id . ')')) . "'",
					absint($layout_id)
				)
			);

			if ($update_result === false) {
				wp_die('Database update failed: ' . esc_html($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// Redirect on success
			$redirect_url = add_query_arg(
				array(
					'page' => 'shopglut_showcases',
					'editor' => 'shopbanner',
					'layout_id' => $layout_id
				),
				admin_url('admin.php')
			);

			wp_safe_redirect($redirect_url);
			exit;

		} catch (Exception $e) {
			wp_die('An error occurred: ' . esc_html($e->getMessage()), 'Error', ['response' => 500]);
		}
	}

	public static function get_instance() {
		static $instance;

		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}