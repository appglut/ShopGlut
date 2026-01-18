<?php
namespace Shopglut\enhancements\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ComparisonchooseTemplates {

	public function __construct() {

		add_action( 'admin_post_create_comparison_layout', array( $this, 'handleCreateComparisonEnhancement' ) );
		add_action( 'wp_ajax_get_comparison_demo_content', array( $this, 'handleGetComparisonDemoContent' ) );

	}

	public function loadProductComparisonTemplates() {
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
								<!-- HTML Demo Preview for Comparison -->
								<div class="template-html-container">
									<!-- Background: Scaled HTML Preview -->
									<div class="html-preview-background">
										<?php $this->renderComparisonPreview($layout_template); ?>
									</div>

									<!-- Foreground: View Demo Button with Overlay -->
									<div class="html-preview-overlay">
										<button type="button" class="demo-view-btn" onclick="openHtmlDemoModal('<?php echo esc_attr($layout_template); ?>', 'product-comparison')">
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
									<input type="hidden" name="action" value="create_comparison_layout">
									<input type="hidden" name="layout_template" value="<?php echo esc_attr($layout_template); ?>">
									<?php wp_nonce_field('create_comparison_layout_nonce', 'create_comparison_layout_nonce'); ?>
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
		}

		.shopg-template-gallery .template-image-container:hover {
			box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
			border-color: rgba(102, 126, 234, 0.3);
		}

		.shopg-template-gallery .template-preview-image {
			width: 100%;
			height: 100%;
			object-fit: contain;
			object-position: center;
			transition: all 0.3s ease;
			opacity: 0;
			padding: 8px;
			box-sizing: border-box;
		}

		.shopg-template-gallery .template-preview-image.loaded {
			opacity: 1;
		}

		.shopg-template-gallery .template-preview-image.error {
			opacity: 1;
			object-fit: contain;
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
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 1;
			transition: opacity 0.3s ease;
		}

		.shopg-template-gallery .image-loading-placeholder.hidden {
			opacity: 0;
			pointer-events: none;
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

		/* Error State */
		.shopg-template-gallery .image-error-placeholder {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
			border-radius: 8px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			z-index: 1;
		}

		.shopg-template-gallery .error-icon {
			opacity: 0.8;
			margin-bottom: 8px;
		}

		.shopg-template-gallery .error-text {
			color: #636e72;
			font-size: 12px;
			font-weight: 500;
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

		.shopg-template-gallery .template-image-container:hover .template-preview-image {
			transform: scale(1.02);
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
			max-width: 95% !important;
			max-height: 95% !important;
			position: relative !important;
			overflow: hidden !important;
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


		.shopglut-template-modal-close-modal:hover {
			background: rgba(255, 255, 255, 1);
			transform: scale(1.1);
			color: #333;
		}

	   .shopglut-template-modal-modal-body {
			padding: 25px;
		}

		.shopglut-template-modal-modal-image {
			max-width: 100%;
			max-height: 85vh;
			border-radius: 8px;
			box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
		}
		</style>

		<?php
	 }

	/**
	 * Render Comparison Preview
	 */
	private function renderComparisonPreview($layout_template) {
		?>
		<div class="shopglut-product-comparison template1 comparison-preview">
			<div class="comparison-container">
				<!-- Comparison Header -->
				<div class="comparison-header">
					<h2>Product Comparison</h2>
					<button class="clear-all-btn">Clear All</button>
				</div>

				<!-- Comparison Table -->
				<div class="comparison-table-wrapper">
					<table class="comparison-table">
						<thead>
							<tr>
								<th class="feature-column">Feature</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<div class="product-image">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 1">
										</div>
										<h3 class="product-title">
											<a href="#">Premium Wireless Headphones</a>
										</h3>
									</div>
								</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<div class="product-image">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 2">
										</div>
										<h3 class="product-title">
											<a href="#">Professional Studio Monitor</a>
										</h3>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<!-- Price Row -->
							<tr class="price-row">
								<td class="feature-label">Price</td>
								<td class="product-value"><span class="price">$299.99</span></td>
								<td class="product-value"><span class="price">$449.99</span></td>
							</tr>

							<!-- Rating Row -->
							<tr class="rating-row">
								<td class="feature-label">Rating</td>
								<td class="product-value">
									<div class="star-rating">★★★★★ <span class="rating-count">(245)</span></div>
								</td>
								<td class="product-value">
									<div class="star-rating">★★★★☆ <span class="rating-count">(128)</span></div>
								</td>
							</tr>

							<!-- Stock Status Row -->
							<tr class="stock-row">
								<td class="feature-label">Availability</td>
								<td class="product-value"><span class="in-stock">In Stock</span></td>
								<td class="product-value"><span class="in-stock">In Stock</span></td>
							</tr>

							<!-- Description Row -->
							<tr class="description-row">
								<td class="feature-label">Description</td>
								<td class="product-value">
									<div class="product-description">Premium noise-cancelling headphones</div>
								</td>
								<td class="product-value">
									<div class="product-description">Professional-grade studio monitors</div>
								</td>
							</tr>

							<!-- Add to Cart Row -->
							<tr class="add-to-cart-row">
								<td class="feature-label">Action</td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button">Add to Cart</a>
								</td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button">Add to Cart</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Include the actual template styles -->
		<style>
		/* ===== RESET & BASE STYLES ===== */
		.shopglut-product-comparison.template1 * {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		.shopglut-product-comparison.template1 {
			font-family: Arial, sans-serif;
			color: #374151;
			line-height: 1.5;
			padding: 15px 0;
		}

		/* ===== COMPARISON CONTAINER ===== */
		.shopglut-product-comparison.template1 .comparison-container {
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 15px;
		}

		/* ===== COMPARISON HEADER ===== */
		.shopglut-product-comparison.template1 .comparison-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 1px solid #e5e7eb;
		}

		.shopglut-product-comparison.template1 .comparison-header h2 {
			font-size: 22px;
			font-weight: bold;
			color: #111827;
			margin: 0;
		}

		.shopglut-product-comparison.template1 .clear-all-btn {
			background: #c62828;
			color: #fff;
			border: 1px solid #c62828;
			padding: 8px 15px;
			font-size: 13px;
			cursor: pointer;
		}

		.shopglut-product-comparison.template1 .clear-all-btn:hover {
			background: #b71c1c;
		}

		/* ===== COMPARISON TABLE WRAPPER ===== */
		.shopglut-product-comparison.template1 .comparison-table-wrapper {
			overflow-x: auto;
			background: #ffffff;
			border: 1px solid #e5e7eb;
			margin-bottom: 20px;
		}

		/* ===== COMPARISON TABLE ===== */
		.shopglut-product-comparison.template1 .comparison-table {
			width: 100%;
			border-collapse: collapse;
			min-width: 800px;
		}

		/* ===== TABLE HEADER ===== */
		.shopglut-product-comparison.template1 .comparison-table thead {
			background: #f3f4f6;
			color: #111827;
		}

		.shopglut-product-comparison.template1 .comparison-table thead th {
			padding: 12px 10px;
			text-align: center;
			font-weight: bold;
			font-size: 13px;
			border: 1px solid #e5e7eb;
		}

		.shopglut-product-comparison.template1 .comparison-table .feature-column {
			min-width: 150px;
			text-align: left !important;
			background: #f3f4f6;
		}

		.shopglut-product-comparison.template1 .comparison-table .product-column {
			min-width: 200px;
			max-width: 250px;
		}

		/* ===== PRODUCT HEADER IN TABLE ===== */
		.shopglut-product-comparison.template1 .product-header {
			position: relative;
			padding: 10px;
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 8px;
		}

		.shopglut-product-comparison.template1 .product-header .remove-product {
			position: absolute;
			top: 5px;
			right: 5px;
			background: #fff;
			color: #d32f2f;
			border: 1px solid #ddd;
			width: 22px;
			height: 22px;
			font-size: 16px;
			line-height: 20px;
			text-align: center;
			cursor: pointer;
		}

		.shopglut-product-comparison.template1 .product-header .remove-product:hover {
			background: #d32f2f;
			color: #fff;
			border-color: #d32f2f;
		}

		.shopglut-product-comparison.template1 .product-header .product-image {
			width: 100px;
			height: 100px;
			overflow: hidden;
			background: #fff;
			padding: 5px;
			border: 1px solid #ddd;
		}

		.shopglut-product-comparison.template1 .product-header .product-image img {
			width: 100%;
			height: 100%;
			object-fit: contain;
		}

		.shopglut-product-comparison.template1 .product-header .product-title {
			font-size: 14px;
			font-weight: bold;
			text-align: center;
			line-height: 1.3;
			margin: 0;
		}

		.shopglut-product-comparison.template1 .product-header .product-title a {
			color: #333;
			text-decoration: none;
		}

		.shopglut-product-comparison.template1 .product-header .product-title a:hover {
			color: #0066cc;
			text-decoration: underline;
		}

		/* ===== TABLE BODY ===== */
		.shopglut-product-comparison.template1 .comparison-table tbody tr {
			border-bottom: 1px solid #e5e7eb;
			background-color: #ffffff;
		}

		.shopglut-product-comparison.template1 .comparison-table tbody tr:nth-child(even) {
			background-color: #f9fafb;
		}

		.shopglut-product-comparison.template1 .comparison-table tbody td {
			padding: 12px 10px;
			vertical-align: middle;
			border: 1px solid #e5e7eb;
		}

		/* ===== FEATURE LABEL ===== */
		.shopglut-product-comparison.template1 .feature-label {
			font-weight: bold;
			color: #111827;
			background: #f3f4f6;
			font-size: 13px;
		}

		/* ===== PRODUCT VALUE ===== */
		.shopglut-product-comparison.template1 .product-value {
			text-align: center;
			color: #374151;
			font-size: 13px;
		}

		/* ===== PRICE STYLING ===== */
		.shopglut-product-comparison.template1 .price-row .price {
			font-size: 18px;
			font-weight: bold;
			color: #2e7d32;
		}

		/* ===== RATING STYLING ===== */
		.shopglut-product-comparison.template1 .rating-row .star-rating {
			display: inline-block;
			color: #f9a825;
			font-size: 14px;
		}

		.shopglut-product-comparison.template1 .rating-row .rating-count {
			font-size: 12px;
			color: #777;
			margin-left: 5px;
		}

		/* ===== STOCK STATUS ===== */
		.shopglut-product-comparison.template1 .in-stock {
			color: #2e7d32;
			font-weight: bold;
			font-size: 12px;
		}

		.shopglut-product-comparison.template1 .out-of-stock {
			color: #d32f2f;
			font-weight: bold;
			font-size: 12px;
		}

		/* ===== DESCRIPTION ===== */
		.shopglut-product-comparison.template1 .product-description {
			line-height: 1.4;
			color: #555;
			font-size: 13px;
		}

		/* ===== ACTION BUTTONS ===== */
		.shopglut-product-comparison.template1 .add-to-cart-button,
		.shopglut-product-comparison.template1 .view-product-button {
			display: inline-block;
			padding: 8px 16px;
			font-size: 13px;
			text-decoration: none;
			cursor: pointer;
			border: 1px solid;
		}

		.shopglut-product-comparison.template1 .add-to-cart-button {
			background: #2e7d32;
			color: #fff;
			border-color: #2e7d32;
		}

		.shopglut-product-comparison.template1 .add-to-cart-button:hover {
			background: #1b5e20;
			border-color: #1b5e20;
		}

		.shopglut-product-comparison.template1 .view-product-button {
			background: #757575;
			color: #fff;
			border-color: #757575;
		}

		.shopglut-product-comparison.template1 .view-product-button:hover {
			background: #616161;
			border-color: #616161;
		}

		/* Preview-specific scaling */
		.comparison-preview {
			width: 100%;
			height: 320px;
			overflow: hidden;
			position: relative;
		}

		.comparison-preview .shopglut-product-comparison {
			transform: scale(0.4);
			transform-origin: top left;
			width: 250%;
			height: 250%;
			overflow: visible;
		}

		.comparison-preview .comparison-container {
			max-width: none;
			margin: 0;
			padding: 0;
		}
		</style>
		<?php
	}

	/**
	 * Render Comparison Full Demo (for modal view)
	 */
	private function renderComparisonFullDemo($layout_template) {
		?>
		<div class="shopglut-product-comparison template1 comparison-full-demo">
			<div class="comparison-container">
				<!-- Comparison Header -->
				<div class="comparison-header">
					<h2>Product Comparison</h2>
					<button class="clear-all-btn">Clear All</button>
				</div>

				<!-- Comparison Table -->
				<div class="comparison-table-wrapper">
					<table class="comparison-table">
						<thead>
							<tr>
								<th class="feature-column">Feature</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<div class="product-image">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 1">
										</div>
										<h3 class="product-title">
											<a href="#">Premium Wireless Headphones</a>
										</h3>
									</div>
								</th>
								<th class="product-column">
									<div class="product-header">
										<button class="remove-product">&times;</button>
										<div class="product-image">
											<img src="<?php echo esc_url(plugins_url('shopglut/global-assets/images/demo-image.png')); ?>" alt="Product 2">
										</div>
										<h3 class="product-title">
											<a href="#">Professional Studio Monitor</a>
										</h3>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<!-- Price Row -->
							<tr class="price-row">
								<td class="feature-label">Price</td>
								<td class="product-value"><span class="price">$299.99</span></td>
								<td class="product-value"><span class="price">$449.99</span></td>
							</tr>

							<!-- Rating Row -->
							<tr class="rating-row">
								<td class="feature-label">Rating</td>
								<td class="product-value">
									<div class="star-rating">★★★★★ <span class="rating-count">(245)</span></div>
								</td>
								<td class="product-value">
									<div class="star-rating">★★★★☆ <span class="rating-count">(128)</span></div>
								</td>
							</tr>

							<!-- Stock Status Row -->
							<tr class="stock-row">
								<td class="feature-label">Availability</td>
								<td class="product-value"><span class="in-stock">In Stock</span></td>
								<td class="product-value"><span class="in-stock">In Stock</span></td>
							</tr>

							<!-- Description Row -->
							<tr class="description-row">
								<td class="feature-label">Description</td>
								<td class="product-value">
									<div class="product-description">Premium noise-cancelling headphones</div>
								</td>
								<td class="product-value">
									<div class="product-description">Professional-grade studio monitors</div>
								</td>
							</tr>

							<!-- Add to Cart Row -->
							<tr class="add-to-cart-row">
								<td class="feature-label">Action</td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button">Add to Cart</a>
								</td>
								<td class="product-value">
									<a href="#" class="add-to-cart-button">Add to Cart</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Include the actual template styles for modal -->
		<style>
		/* ===== RESET & BASE STYLES ===== */
		.shopglut-product-comparison.template1 * {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		.shopglut-product-comparison.template1 {
			font-family: Arial, sans-serif;
			color: #374151;
			line-height: 1.5;
			padding: 15px 0;
		}

		/* ===== COMPARISON CONTAINER ===== */
		.shopglut-product-comparison.template1 .comparison-container {
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 15px;
		}

		/* ===== COMPARISON HEADER ===== */
		.shopglut-product-comparison.template1 .comparison-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 1px solid #e5e7eb;
		}

		.shopglut-product-comparison.template1 .comparison-header h2 {
			font-size: 22px;
			font-weight: bold;
			color: #111827;
			margin: 0;
		}

		.shopglut-product-comparison.template1 .clear-all-btn {
			background: #c62828;
			color: #fff;
			border: 1px solid #c62828;
			padding: 8px 15px;
			font-size: 13px;
			cursor: pointer;
		}

		.shopglut-product-comparison.template1 .clear-all-btn:hover {
			background: #b71c1c;
		}

		/* ===== COMPARISON TABLE WRAPPER ===== */
		.shopglut-product-comparison.template1 .comparison-table-wrapper {
			overflow-x: auto;
			background: #ffffff;
			border: 1px solid #e5e7eb;
			margin-bottom: 20px;
		}

		/* ===== COMPARISON TABLE ===== */
		.shopglut-product-comparison.template1 .comparison-table {
			width: 100%;
			border-collapse: collapse;
			min-width: 800px;
		}

		/* ===== TABLE HEADER ===== */
		.shopglut-product-comparison.template1 .comparison-table thead {
			background: #f3f4f6;
			color: #111827;
		}

		.shopglut-product-comparison.template1 .comparison-table thead th {
			padding: 12px 10px;
			text-align: center;
			font-weight: bold;
			font-size: 13px;
			border: 1px solid #e5e7eb;
		}

		.shopglut-product-comparison.template1 .comparison-table .feature-column {
			min-width: 150px;
			text-align: left !important;
			background: #f3f4f6;
		}

		.shopglut-product-comparison.template1 .comparison-table .product-column {
			min-width: 200px;
			max-width: 250px;
		}

		/* ===== PRODUCT HEADER IN TABLE ===== */
		.shopglut-product-comparison.template1 .product-header {
			position: relative;
			padding: 10px;
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 8px;
		}

		.shopglut-product-comparison.template1 .product-header .remove-product {
			position: absolute;
			top: 5px;
			right: 5px;
			background: #fff;
			color: #d32f2f;
			border: 1px solid #ddd;
			width: 22px;
			height: 22px;
			font-size: 16px;
			line-height: 20px;
			text-align: center;
			cursor: pointer;
		}

		.shopglut-product-comparison.template1 .product-header .remove-product:hover {
			background: #d32f2f;
			color: #fff;
			border-color: #d32f2f;
		}

		.shopglut-product-comparison.template1 .product-header .product-image {
			width: 100px;
			height: 100px;
			overflow: hidden;
			background: #fff;
			padding: 5px;
			border: 1px solid #ddd;
		}

		.shopglut-product-comparison.template1 .product-header .product-image img {
			width: 100%;
			height: 100%;
			object-fit: contain;
		}

		.shopglut-product-comparison.template1 .product-header .product-title {
			font-size: 14px;
			font-weight: bold;
			text-align: center;
			line-height: 1.3;
			margin: 0;
		}

		.shopglut-product-comparison.template1 .product-header .product-title a {
			color: #333;
			text-decoration: none;
		}

		.shopglut-product-comparison.template1 .product-header .product-title a:hover {
			color: #0066cc;
			text-decoration: underline;
		}

		/* ===== TABLE BODY ===== */
		.shopglut-product-comparison.template1 .comparison-table tbody tr {
			border-bottom: 1px solid #e5e7eb;
			background-color: #ffffff;
		}

		.shopglut-product-comparison.template1 .comparison-table tbody tr:nth-child(even) {
			background-color: #f9fafb;
		}

		.shopglut-product-comparison.template1 .comparison-table tbody td {
			padding: 12px 10px;
			vertical-align: middle;
			border: 1px solid #e5e7eb;
		}

		/* ===== FEATURE LABEL ===== */
		.shopglut-product-comparison.template1 .feature-label {
			font-weight: bold;
			color: #111827;
			background: #f3f4f6;
			font-size: 13px;
		}

		/* ===== PRODUCT VALUE ===== */
		.shopglut-product-comparison.template1 .product-value {
			text-align: center;
			color: #374151;
			font-size: 13px;
		}

		/* ===== PRICE STYLING ===== */
		.shopglut-product-comparison.template1 .price-row .price {
			font-size: 18px;
			font-weight: bold;
			color: #2e7d32;
		}

		/* ===== RATING STYLING ===== */
		.shopglut-product-comparison.template1 .rating-row .star-rating {
			display: inline-block;
			color: #f9a825;
			font-size: 14px;
		}

		.shopglut-product-comparison.template1 .rating-row .rating-count {
			font-size: 12px;
			color: #777;
			margin-left: 5px;
		}

		/* ===== STOCK STATUS ===== */
		.shopglut-product-comparison.template1 .in-stock {
			color: #2e7d32;
			font-weight: bold;
			font-size: 12px;
		}

		.shopglut-product-comparison.template1 .out-of-stock {
			color: #d32f2f;
			font-weight: bold;
			font-size: 12px;
		}

		/* ===== DESCRIPTION ===== */
		.shopglut-product-comparison.template1 .product-description {
			line-height: 1.4;
			color: #555;
			font-size: 13px;
		}

		/* ===== ACTION BUTTONS ===== */
		.shopglut-product-comparison.template1 .add-to-cart-button,
		.shopglut-product-comparison.template1 .view-product-button {
			display: inline-block;
			padding: 8px 16px;
			font-size: 13px;
			text-decoration: none;
			cursor: pointer;
			border: 1px solid;
		}

		.shopglut-product-comparison.template1 .add-to-cart-button {
			background: #2e7d32;
			color: #fff;
			border-color: #2e7d32;
		}

		.shopglut-product-comparison.template1 .add-to-cart-button:hover {
			background: #1b5e20;
			border-color: #1b5e20;
		}

		.shopglut-product-comparison.template1 .view-product-button {
			background: #757575;
			color: #fff;
			border-color: #757575;
		}

		.shopglut-product-comparison.template1 .view-product-button:hover {
			background: #616161;
			border-color: #616161;
		}

		/* Modal-specific styles */
		.comparison-full-demo {
			padding: 20px;
			background: #f9fafb;
		}
		</style>
		<?php
	}

	/**
	 * Handle AJAX request for comparison demo content
	 */
	public function handleGetComparisonDemoContent() {
		// Verify nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_admin_nonce')) {
			wp_die('Security check failed');
		}

		// Get template ID
		if (!isset($_POST['template_id'])) {
			wp_die('Template ID not provided');
		}

		$template_id = sanitize_text_field(wp_unslash($_POST['template_id']));

		// Output full demo
		$this->renderComparisonFullDemo($template_id);
		wp_die();
	}

	public function handleCreateComparisonEnhancement() {


		if (
			!isset($_POST['create_comparison_layout_nonce']) ||
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_comparison_layout_nonce'])), 'create_comparison_layout_nonce') ||
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
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

			// Prepare data for insertion (without id, let auto_increment handle it)
			$data = array(
				'layout_name' => '',  // Will be updated after insert with the actual ID
				'layout_template' => sanitize_text_field(wp_unslash($_POST['layout_template'])),
				'layout_settings' => '{}', // Default empty JSON object
			);

			// Format specifiers for wpdb
			$format = array(
				'%s',  // layout_name
				'%s',  // layout_template
				'%s',  // layout_settings
			);

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$inserted = $wpdb->insert($table_name, $data, $format);

			if ($inserted === false) {

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				wp_die('Database insertion failed: ' . esc_html($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// Get the auto-generated ID
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_id = $wpdb->insert_id;

			// Update the layout_name with the actual ID
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update(
				$table_name,
				array('layout_name' => 'Layout(#' . $layout_id . ')'),
				array('id' => $layout_id),
				array('%s'),
				array('%d')
			);

			// Redirect on success
			$redirect_url = add_query_arg(
				array(
					'page' => 'shopglut_enhancements',
					'editor' => 'product_comparison',
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

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}