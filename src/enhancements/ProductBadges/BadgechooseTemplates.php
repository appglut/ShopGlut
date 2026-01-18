<?php
namespace Shopglut\enhancements\ProductBadges;

if ( ! defined( 'ABSPATH' ) ) exit;

class BadgechooseTemplates {

	public function __construct() {
		add_action( 'admin_post_create_badge_from_template', array( $this, 'handleCreateBadgeFromTemplate' ) );
	}

	public function loadProductBadgeTemplates() {
		$tab_names = [
			'tab1' => [
				'name' => esc_html__("Available Templates", 'shopglut'),
				'templates' => ['sale_red', 'new_green']
			]
		];

		$templates = BadgeTemplates::get_prebuilt_templates();

		// Define template images
		$template_images = [
			'sale_red' => 'sale-red.png',
			'new_green' => 'new-green.png'
			/*
			// Commented out - keeping only Sale and New badges active
			'featured_gold' => 'featured-gold.png',
			'hot_orange' => 'hot-orange.png',
			'limited_purple' => 'limited-purple.png',
			'discount_blue' => 'discount-blue.png',
			'bestseller_red' => 'bestseller-red.png',
			'premium_black' => 'premium-black.png',
			'trending_pink' => 'trending-pink.png',
			'eco_green' => 'eco-green.png'
			*/
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
					<?php foreach ($tab['templates'] as $template_id): ?>
						<?php $template = $templates[$template_id]; ?>
						<div class="shopg-template-preview" data-template="<?php echo esc_attr($template_id); ?>">
							<div class="template-header">
								<h2><?php echo esc_html($template['name']); ?></h2>
							</div>

							<div class="template-content product-demo">
								<!-- Simple Badge Preview -->
								<div class="badge-preview-container">
									<?php
									$preview_style = $template['preview_style'];
									$css_styles = $this->build_preview_styles($preview_style);
									$badge_text = $template['default_settings'][$template['default_settings']['badge_type'] . '_badge_text'] ?? 'BADGE';
									?>
									<span class="template-badge-preview" style="<?php echo esc_attr($css_styles); ?>">
										<?php echo esc_html($badge_text); ?>
									</span>
								</div>
							</div>

							<div class="template-footer">
								<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
									<input type="hidden" name="action" value="create_badge_from_template">
									<input type="hidden" name="template_id" value="<?php echo esc_attr($template_id); ?>">
									<?php wp_nonce_field('create_badge_from_template_nonce', 'create_badge_from_template_nonce'); ?>
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
				<span class="shopglut-template-modal-close-modal" onclick="closeImageModal()">&times;</span>
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
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.badge-preview-container {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			height: 100%;
			padding: 20px;
			border-radius: 8px;
		}

		.shopg-template-gallery .template-badge-preview {
			display: inline-block;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
			font-size: 28px !important;
			padding: 16px 32px !important;
			border-radius: 8px !important;
			transition: all 0.3s ease;
		}

		.shopg-template-gallery .template-badge-preview:hover {
			transform: scale(1.05);
			box-shadow: 0 6px 20px rgba(0,0,0,0.2);
		}

		.shopg-template-gallery .template-image-container {
			position: relative;
			overflow: hidden;
			border-radius: 6px;
			cursor: pointer;
			transition: all 0.3s ease;
			width: 100%;
			height: 100%;
			background: #fff;
			border: 1px solid #e8e8e8;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.shopg-template-gallery .template-image-container:hover {
			box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
			border-color: rgba(102, 126, 234, 0.3);
		}

		/* Loading Placeholder Styles */
		.shopg-template-gallery .image-loading-placeholder {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			border-radius: 8px;
			display: none;
			align-items: center;
			justify-content: center;
			z-index: 1;
			transition: opacity 0.3s ease;
		}

		.shopg-template-gallery .loading-skeleton {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			border-radius: 8px;
			overflow: hidden;
			background: linear-gradient(90deg,
				rgba(255,255,255,0) 0%,
				rgba(255,255,255,0.4) 50%,
				rgba(255,255,255,0) 100%);
		}

		.shopg-template-gallery .skeleton-shimmer {
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg,
				transparent 0%,
				rgba(255,255,255,0.6) 50%,
				transparent 100%);
			animation: shimmer 2s infinite;
		}

		@keyframes shimmer {
			0% { left: -100%; }
			100% { left: 100%; }
		}

		.shopg-template-gallery .loading-icon {
			z-index: 2;
			opacity: 0.7;
			animation: pulse 2s infinite;
		}

		@keyframes pulse {
			0%, 100% { opacity: 0.7; transform: scale(1); }
			50% { opacity: 0.4; transform: scale(1.05); }
		}

		.shopg-template-gallery .image-overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0, 0, 0, 0.6);
			display: flex;
			align-items: center;
			justify-content: center;
			opacity: 0;
			transition: all 0.3s ease;
			border-radius: 8px;
		}

		.shopg-template-gallery .template-image-container:hover .image-overlay {
			opacity: 1;
		}

		.shopg-template-gallery .expand-icon-container {
			background: rgba(255, 255, 255, 0.95);
			border-radius: 50%;
			padding: 10px;
			backdrop-filter: blur(10px);
			border: 2px solid rgba(255, 255, 255, 0.8);
			transition: all 0.3s ease;
			box-shadow: 0 4px 15px rgba(0,0,0,0.2);
		}

		.shopg-template-gallery .expand-icon-container:hover {
			background: rgba(255, 255, 255, 1);
			transform: scale(1.1);
		}

		.shopg-template-gallery .expand-icon {
			filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
			color: #667eea;
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
		}

	    .shopglut-template-modal-modal-content {
			background: white !important;
			border-radius: 12px !important;
			max-width: 600px !important;
			max-height: 600px !important;
			position: relative !important;
			overflow: hidden !important;
			box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important;
			animation: modalSlideIn 0.3s ease-out !important;
			z-index: 1000000 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			padding: 40px !important;
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


		.shopglut-template-modal-close-modal:hover {
			background: rgba(255, 255, 255, 1);
			transform: scale(1.1);
			color: #333;
		}

	   .shopglut-template-modal-modal-body {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.shopglut-template-modal-modal-image {
			max-width: 100%;
			max-height: 100%;
		}

		@media (max-width: 768px) {
		.shopglut-template-modal-modal-content {
				max-width: 95%;
				max-height: 95%;
			}

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

			.shopg-template-gallery .template-badge-preview {
				font-size: 24px !important;
				padding: 10px 20px !important;
			}
		}
		</style>

		<script>
		// JavaScript functions are now handled by the main template-html-demo.js file
	</script>
		<?php
	 }

	
	
	private function build_preview_styles($preview_style) {
		$css = array();

		// Handle background
		if (!empty($preview_style['background'])) {
			if (strpos($preview_style['background'], 'gradient') !== false) {
				$css[] = 'background: ' . esc_attr($preview_style['background']);
			} else {
				$css[] = 'background-color: ' . esc_attr($preview_style['background']);
			}
		}

		// Handle text color
		if (!empty($preview_style['color'])) {
			$css[] = 'color: ' . esc_attr($preview_style['color']);
		}

		// Handle font size
		if (!empty($preview_style['font_size'])) {
			$css[] = 'font-size: ' . esc_attr($preview_style['font_size']);
		}

		// Handle font weight
		if (!empty($preview_style['font_weight'])) {
			$css[] = 'font-weight: ' . esc_attr($preview_style['font_weight']);
		}

		// Handle padding
		if (!empty($preview_style['padding'])) {
			$css[] = 'padding: ' . esc_attr($preview_style['padding']);
		}

		// Handle border radius
		if (!empty($preview_style['border_radius'])) {
			$css[] = 'border-radius: ' . esc_attr($preview_style['border_radius']) . 'px';
		}

		// Handle text transform
		if (!empty($preview_style['text_transform'])) {
			$css[] = 'text-transform: ' . esc_attr($preview_style['text_transform']);
		}

		return implode('; ', $css);
	}

	
	public function handleCreateBadgeFromTemplate() {
		if (
			!isset($_POST['create_badge_from_template_nonce']) ||
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_badge_from_template_nonce'])), 'create_badge_from_template_nonce') ||
			!current_user_can('manage_options')
		) {
			wp_die('Security check failed', 'Error', ['response' => 403]);
		}

		try {
			// Validate required POST data
			if (!isset($_POST['template_id'])) {
				wp_die('Missing required fields', 'Error', ['response' => 400]);
			}

			$template_id = sanitize_text_field(wp_unslash($_POST['template_id']));
			$template = BadgeTemplates::get_template($template_id);

			if (!$template) {
				wp_die('Invalid template', 'Error', ['response' => 400]);
			}

			// Database insertion
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';

			// Prepare data for insertion - wrap in shopg_product_badge_settings key like other modules
			$badge_settings = array(
				'shopg_product_badge_settings' => $template['data']
			);

			$data = array(
				'layout_name' => '',  // Will be updated after insert
				'layout_template' => 'template1',
				'layout_settings' => serialize($badge_settings)
			);

			$format = array('%s', '%s', '%s');

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$inserted = $wpdb->insert($table_name, $data, $format);

			if ($inserted === false) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				wp_die('Database insertion failed: ' . esc_html($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// Get the auto-generated ID
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$badge_id = $wpdb->insert_id;

			// Update the layout_name with the actual ID
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update(
				$table_name,
				array('layout_name' => $template['name'] . ' (#' . $badge_id . ')'),
				array('id' => $badge_id),
				array('%s'),
				array('%d')
			);

			// Redirect on success
			$redirect_url = add_query_arg(
				array(
					'page' => 'shopglut_enhancements',
					'editor' => 'product_badges',
					'badge_id' => $badge_id
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

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}
