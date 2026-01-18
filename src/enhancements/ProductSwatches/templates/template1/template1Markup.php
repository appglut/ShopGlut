<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Template1 Markup - Simple Dropdown Style
 */
class template1Markup {

	public function layout_render($template_data) {
		$layout_id = $template_data['layout_id'] ?? 0;
		$is_admin_preview = is_admin();

		?>
		<div class="shopglut-single-product template1" data-layout-id="<?php echo esc_attr($layout_id); ?>">
			<div class="single-product-container">
				<?php if ($is_admin_preview): ?>
					<!-- Admin Preview Mode -->
					<div class="demo-content shopglut-demo-mode">
						<?php $this->render_demo_swatches($layout_id); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo swatches for admin preview
	 */
	private function render_demo_swatches($layout_id) {
		$settings = $this->get_layout_settings($layout_id);

		// Get settings with defaults
		$dropdown_bg = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_background'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_background']
			: '#ffffff';

		$dropdown_border = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_border_color'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_border_color']
			: '#d1d5db';

		$dropdown_border_width = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_border_width'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_border_width']
			: 1;

		$dropdown_radius = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_border_radius'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_border_radius']
			: 6;

		$text_color = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_text_color'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_text_color']
			: '#374151';

		$font_size = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_font_size'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_font_size']
			: 14;

		$label_color = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_color'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_color']
			: '#374151';

		$label_font_size = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_font_size'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_font_size']
			: 14;

		?>
		<!-- Simple Demo: Dropdown Swatch -->
		<div class="shopglut-swatches-demo">
			<h3 style="margin-bottom: 20px; color: #2271b1;">Template 1: Dropdown Swatches</h3>
			<p style="margin-bottom: 20px; color: #666;">Classic dropdown style for product variations with clean styling.</p>

			<div class="shopglut-swatches-wrapper shopglut-template1">
				<!-- Label -->
				<label class="shopglut-attribute-label" style="color:<?php echo esc_attr($label_color); ?>;font-size:<?php echo intval($label_font_size); ?>px;font-weight:600;display:block;margin-bottom:8px;">
					Size:
				</label>

				<!-- Dropdown -->
				<select class="shopglut-swatch-dropdown" disabled style="background-color:<?php echo esc_attr($dropdown_bg); ?>;border:<?php echo intval($dropdown_border_width); ?>px solid <?php echo esc_attr($dropdown_border); ?>;border-radius:<?php echo intval($dropdown_radius); ?>px;color:<?php echo esc_attr($text_color); ?>;font-size:<?php echo intval($font_size); ?>px;padding:10px 14px;min-height:40px;min-width:200px;">
					<option value="">Choose an option</option>
					<option value="small">Small</option>
					<option value="medium">Medium</option>
					<option value="large">Large</option>
					<option value="xlarge">Extra Large</option>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Get layout settings from database
	 */
	private function get_layout_settings($layout_id) {
		global $wpdb;

		if (!$layout_id) {
			return array();
		}

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$layout = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT layout_settings FROM `{$table_name}` WHERE id = %d",
				$layout_id
			)
		);

		if (!$layout) {
			return array();
		}

		$layout_settings = maybe_unserialize($layout->layout_settings);
		$template = isset($layout_settings['layout_template']) ? $layout_settings['layout_template'] : 'template1';

		// Try to extract template settings
		$keys = array(
			'shopg_product_swatches_settings_' . $template,
			'shopg_productswatches_settings_' . $template,
		);

		foreach ($keys as $key) {
			if (isset($layout_settings[$key])) {
				$settings = $layout_settings[$key];
				if (isset($settings['product-swatches-settings'])) {
					return $settings['product-swatches-settings'];
				}
				return $settings;
			}
		}

		return array();
	}
}
