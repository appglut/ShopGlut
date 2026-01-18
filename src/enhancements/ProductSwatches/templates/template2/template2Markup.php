<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template2;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Template2 Markup - Simple Button Style
 */
class template2Markup {

	public function layout_render($template_data) {
		$layout_id = $template_data['layout_id'] ?? 0;
		$is_admin_preview = is_admin();

		?>
		<div class="shopglut-single-product template2" data-layout-id="<?php echo esc_attr($layout_id); ?>">
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
	 * Helper to get setting value
	 */
	private function get_setting_value($settings, $key, $default = '') {
		if (isset($settings[$key])) {
			$value = $settings[$key];
			if (is_array($value) && isset($value[$key])) {
				return $value[$key];
			}
			return $value;
		}
		return $default;
	}

	/**
	 * Render demo swatches for admin preview
	 */
	private function render_demo_swatches($layout_id) {
		$settings = $this->get_layout_settings($layout_id);

		// Get button default settings
		$button_default = isset($settings['button_default_section']) ? $settings['button_default_section'] : array();
		$button_hover = isset($settings['button_hover_section']) ? $settings['button_hover_section'] : array();
		$button_active = isset($settings['button_active_section']) ? $settings['button_active_section'] : array();
		$layout_settings = isset($settings['layout_settings_section']) ? $settings['layout_settings_section'] : array();
		$label_settings = isset($settings['attribute_label_section']) ? $settings['attribute_label_section'] : array();

		// Extract values
		$button_bg = $this->get_setting_value($button_default, 'button_default_background', '#ffffff');
		$button_text = $this->get_setting_value($button_default, 'button_default_text_color', '#2d3748');
		$button_border = $this->get_setting_value($button_default, 'button_default_border_color', '#d1d5db');
		$button_border_width = $this->get_setting_value($button_default, 'button_default_border_width', 2);
		$button_radius = $this->get_setting_value($button_default, 'button_default_border_radius', 8);
		$padding_x = $this->get_setting_value($button_default, 'button_default_padding_x', 16);
		$padding_y = $this->get_setting_value($button_default, 'button_default_padding_y', 10);

		$hover_bg = $this->get_setting_value($button_hover, 'button_hover_background', '#f3f4f6');
		$hover_text = $this->get_setting_value($button_hover, 'button_hover_text_color', '#1f2937');
		$hover_border = $this->get_setting_value($button_hover, 'button_hover_border_color', '#9ca3af');

		$active_bg = $this->get_setting_value($button_active, 'button_active_background', '#2271b1');
		$active_text = $this->get_setting_value($button_active, 'button_active_text_color', '#ffffff');
		$active_border = $this->get_setting_value($button_active, 'button_active_border_color', '#2271b1');

		$columns = $this->get_setting_value($layout_settings, 'swatch_columns', 4);
		$gap = $this->get_setting_value($layout_settings, 'swatch_gap', 10);

		$label_color = $this->get_setting_value($label_settings, 'attribute_label_color', '#374151');
		$label_font_size = $this->get_setting_value($label_settings, 'attribute_label_font_size', 14);

		$button_style = sprintf(
			'background-color:%s;color:%s;border:%dpx solid %s;border-radius:%dpx;padding:%dpx %dpx;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.2s ease;',
			esc_attr($button_bg),
			esc_attr($button_text),
			intval($button_border_width),
			esc_attr($button_border),
			intval($button_radius),
			intval($padding_y),
			intval($padding_x)
		);

		?>
		<!-- Simple Demo: Button Swatches -->
		<div class="shopglut-swatches-demo">
			<h3 style="margin-bottom: 20px; color: #2271b1;">Template 2: Button Swatches</h3>
			<p style="margin-bottom: 20px; color: #666;">Button style for product variations with hover and active states.</p>

			<div class="shopglut-swatches-wrapper shopglut-template2">
				<!-- Label -->
				<label class="shopglut-attribute-label" style="color:<?php echo esc_attr($label_color); ?>;font-size:<?php echo intval($label_font_size); ?>px;font-weight:600;display:block;margin-bottom:12px;">
					Color:
				</label>

				<!-- Buttons Container -->
				<div class="shopglut-buttons-container" style="display:grid;grid-template-columns:repeat(<?php echo intval($columns); ?>,1fr);gap:<?php echo intval($gap); ?>px;">
					<button type="button" class="shopglut-swatch-button" data-value="red" style="<?php echo esc_attr($button_style); ?>">
						Red
					</button>
					<button type="button" class="shopglut-swatch-button selected" data-value="blue" style="<?php echo esc_attr($button_style); ?>">
						Blue
					</button>
					<button type="button" class="shopglut-swatch-button" data-value="green" style="<?php echo esc_attr($button_style); ?>">
						Green
					</button>
					<button type="button" class="shopglut-swatch-button" data-value="black" style="<?php echo esc_attr($button_style); ?>">
						Black
					</button>
				</div>
			</div>

			<!-- Inline styles for interactions -->
			<style>
				.shopglut-template2 .shopglut-swatch-button:hover {
					background-color: <?php echo esc_attr($hover_bg); ?> !important;
					color: <?php echo esc_attr($hover_text); ?> !important;
					border-color: <?php echo esc_attr($hover_border); ?> !important;
				}
				.shopglut-template2 .shopglut-swatch-button.selected,
				.shopglut-template2 .shopglut-swatch-button.active {
					background-color: <?php echo esc_attr($active_bg); ?> !important;
					color: <?php echo esc_attr($active_text); ?> !important;
					border-color: <?php echo esc_attr($active_border); ?> !important;
				}
			</style>
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
				"SELECT layout_settings, layout_template FROM `{$table_name}` WHERE id = %d",
				$layout_id
			)
		);

		if (!$layout) {
			return array();
		}

		$template = $layout->layout_template ?? 'template2';
		$layout_settings = maybe_unserialize($layout->layout_settings);

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
