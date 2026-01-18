<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template19;

if (!defined('ABSPATH')) {
	exit;
}

// Include Template1 AJAX handler
require_once __DIR__ . '/template19-ajax-handler.php';

// Include Module Integration helper
require_once __DIR__ . '/ModuleIntegration.php';

class template19Markup {


	public function layout_render($template_data) {
		// Get settings for this layout
		$settings = array(); // Default empty settings for demo

		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			echo '<div class="shopglut-error">' . esc_html__('WooCommerce is required for this cart layout.', 'shopglut') . '</div>';
			return;
		}



		// Check if we're in admin area or cart is not available
		$is_admin_preview = is_admin();


		?>
		<div class="shopglut-single-product template1 responsive-layout" data-layout-id="<?php echo esc_attr($template_data['layout_id'] ?? 0); ?>">
			<div class="single-product-container">
				<?php if ($is_admin_preview): ?>
					<!-- Admin Preview Mode -->
					<div class="demo-content responsive-preview shopglut-demo-mode" data-demo-mode="true" style="position: relative;">
						<style>
							.shopglut-demo-mode a[href],
							.shopglut-demo-mode form[action] {
								pointer-events: none !important;
							}
							.shopglut-demo-mode .qty-decrease,
							.shopglut-demo-mode .qty-increase,
							.shopglut-demo-mode .size-option,
							.shopglut-demo-mode .color-swatch,
							.shopglut-demo-mode .attribute-value,
							.shopglut-demo-mode .add-to-cart-btn,
							.shopglut-demo-mode .quick-add-btn,
							.shopglut-demo-mode .wc-tabs a,
							.shopglut-demo-mode .tabs a,
							.shopglut-demo-mode .woocommerce-tabs a,
							.shopglut-demo-mode .thumbnail-item {
								pointer-events: auto !important;
							}
							.shopglut-demo-mode form {
								display: inline;
							}
						</style>
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

		?>
     <div class="container">

        <div class="designs-grid">
            <!-- Design 19: Premium - Card with Details -->
            <div class="design-card shopglut-product-swatches-template19 variation-19">
                <div class="design-title">19. Detailed Cards <span class="premium-badge">PREMIUM</span></div>
                <div class="label">Select Plan:</div>
                <div class="detail-cards">
                    <div class="detail-card">
                        <div class="card-title">Basic Plan</div>
                        <div class="card-description">Perfect for individuals and small projects</div>
                        <div class="card-price">$19.99/month</div>
                    </div>
                    <div class="detail-card selected">
                        <div class="card-title">Pro Plan</div>
                        <div class="card-description">Ideal for professionals and teams</div>
                        <div class="card-price">$49.99/month</div>
                    </div>
                    <div class="detail-card">
                        <div class="card-title">Enterprise Plan</div>
                        <div class="card-description">Custom solutions for large organizations</div>
                        <div class="card-price">Contact Sales</div>
                    </div>
                </div>
            </div>
        </div>
   </div>
		<?php
	}

	/**
	 * Render live single product for frontend
	 */
	private function render_live_single_product($settings) {
		// Remove ALL automatic BadgeDataManage hooks so we can control badge position manually


		?>

		 <div class="container">

        <div class="designs-grid">

            <!-- Design 19: Premium - Card with Details -->
            <div class="design-card shopglut-product-swatches-template19 variation-19">
                <div class="design-title">19. Detailed Cards <span class="premium-badge">PREMIUM</span></div>
                <div class="label">Select Plan:</div>
                <div class="detail-cards">
                    <div class="detail-card">
                        <div class="card-title">Basic Plan</div>
                        <div class="card-description">Perfect for individuals and small projects</div>
                        <div class="card-price">$19.99/month</div>
                    </div>
                    <div class="detail-card selected">
                        <div class="card-title">Pro Plan</div>
                        <div class="card-description">Ideal for professionals and teams</div>
                        <div class="card-price">$49.99/month</div>
                    </div>
                    <div class="detail-card">
                        <div class="card-title">Enterprise Plan</div>
                        <div class="card-description">Custom solutions for large organizations</div>
                        <div class="card-price">Contact Sales</div>
                    </div>
                </div>
            </div>

	    </div>
	</div>
		<?php
	}

	
}