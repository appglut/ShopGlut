<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Template1 Markup - Dropdown Style
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

		// Get dropdown settings with defaults
		$dropdown_bg = $this->get_setting_value($settings, 'swatch_dropdown_background', '#ffffff');
		$dropdown_border = $this->get_setting_value($settings, 'swatch_dropdown_border_color', '#d1d5db');
		$dropdown_border_width = $this->get_setting_value($settings, 'swatch_dropdown_border_width', 1);
		$dropdown_radius = $this->get_setting_value($settings, 'swatch_dropdown_border_radius', 6);

		// Get padding settings
		$padding = $this->get_setting_value($settings, 'swatch_dropdown_padding', array('top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14', 'unit' => 'px'));
		if (is_string($padding)) {
			$padding = array('top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14', 'unit' => 'px');
		}
		$padding_top = isset($padding['top']) ? $padding['top'] : '10';
		$padding_right = isset($padding['right']) ? $padding['right'] : '14';
		$padding_bottom = isset($padding['bottom']) ? $padding['bottom'] : '10';
		$padding_left = isset($padding['left']) ? $padding['left'] : '14';

		$text_color = $this->get_setting_value($settings, 'swatch_dropdown_text_color', '#374151');
		$font_size = max(intval($this->get_setting_value($settings, 'swatch_dropdown_font_size', 14)), 15);
		$font_weight = $this->get_setting_value($settings, 'swatch_dropdown_font_weight', '400');
		$font_family = $this->get_setting_value($settings, 'swatch_dropdown_font_family', 'inherit');

		$label_color = $this->get_setting_value($settings, 'swatch_attribute_label_color', '#374151');
		$label_font_size = max(intval($this->get_setting_value($settings, 'swatch_attribute_label_font_size', 14)), 15);
		$label_font_weight = $this->get_setting_value($settings, 'swatch_attribute_label_font_weight', '600');

		// Get actual attribute terms from the assigned attribute
		global $product;
		$attribute_data = $this->get_assigned_attribute_terms($product, $layout_id);

		// If no attributes found, fall back to demo data
		if (empty($attribute_data)) {
			$attribute_data = array(
				'label' => 'Color',
				'options' => array(
					array('slug' => 'beige', 'name' => 'Beige'),
					array('slug' => 'green', 'name' => 'Green'),
				)
			);
		}

		?>
		<!-- Template1 Demo: Dropdown Swatch -->
		<div class="shopglut-swatches-demo shopglut-demo-center">
			<div class="shopglut-swatches-wrapper shopglut-template1">
				<!-- Label -->
				<label class="shopglut-attribute-label" style="color:<?php echo esc_attr($label_color); ?>;font-size:<?php echo esc_attr($label_font_size); ?>px;font-weight:<?php echo esc_attr($label_font_weight); ?>;display:block;margin-bottom:16px;letter-spacing:0.5px;">
					<?php echo esc_html($attribute_data['label']); ?>
					<span class="shopglut-label-required">*</span>
				</label>

				<!-- Dropdown -->
				<select class="shopglut-swatch-dropdown shopglut-demo-dropdown" disabled style="background-color:<?php echo esc_attr($dropdown_bg); ?>;border:<?php echo intval($dropdown_border_width); ?>px solid <?php echo esc_attr($dropdown_border); ?>;border-radius:<?php echo intval($dropdown_radius); ?>px;color:<?php echo esc_attr($text_color); ?>;font-size:<?php echo esc_attr($font_size); ?>px;font-weight:<?php echo esc_attr($font_weight); ?>;font-family:<?php echo esc_attr($font_family); ?>;padding:<?php echo intval($padding_top); ?>px <?php echo intval($padding_right); ?>px <?php echo intval($padding_bottom); ?>px <?php echo intval($padding_left); ?>px;width:100%;min-height:52px;cursor:not-allowed;opacity:0.7;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
					<option value="" disabled selected style="color:#9ca3af;">Choose an option</option>
					<?php foreach ($attribute_data['options'] as $option): ?>
						<option value="<?php echo esc_attr($option['slug']); ?>">
							<?php echo esc_html($option['name']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<style>
			/* Enhanced demo styling */
			.shopglut-demo-center {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				padding: 24px;
			}

			/* Enhanced dropdown container */
			.shopglut-demo-center .shopglut-swatches-wrapper {
				width: 100%;
				max-width: 380px;
				text-align: left;
			}

			/* Enhanced label styling */
			.shopglut-demo-center .shopglut-attribute-label {
				position: relative;
				padding-left: 12px;
				display: flex;
				align-items: center;
				gap: 4px;
				font-size: 16px !important;
			}

			.shopglut-demo-center .shopglut-attribute-label::before {
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				width: 3px;
				height: 16px;
				background: linear-gradient(180deg, #2271b1 0%, #135e96 100%);
				border-radius: 2px;
			}

			.shopglut-demo-center .shopglut-label-required {
				color: #ef4444;
				font-size: 16px;
				font-weight: bold;
				margin-left: 2px;
			}

			/* Enhanced dropdown styling */
			.shopglut-demo-center .shopglut-swatch-dropdown {
				transition: all 0.2s ease;
				position: relative;
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14' fill='none'%3E%3Cpath d='M3.5 5.5L7 9L10.5 5.5' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
				background-repeat: no-repeat;
				background-position: right 14px center;
				padding-right: 40px;
				appearance: none;
				-webkit-appearance: none;
				-moz-appearance: none;
			}

			.shopglut-demo-center .shopglut-swatch-dropdown:hover {
				border-color: #2271b1;
				box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
			}

			/* Focus ring for accessibility */
			.shopglut-demo-center .shopglut-swatch-dropdown:focus {
				outline: none;
				border-color: #2271b1;
				box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
			}
		</style>
		<?php
	}

	/**
	 * Get terms for the assigned attribute
	 *
	 * @param \WC_Product $product Product object
	 * @param int $layout_id Layout ID
	 * @return array Attribute data with label and options
	 */
	private function get_assigned_attribute_terms($product, $layout_id) {
		// Get layout data to find assigned attribute
		$layout_data = $this->get_layout_data($layout_id);
		$assigned_attribute = isset($layout_data['assigned_attribute']) ? $layout_data['assigned_attribute'] : '';

		if (empty($assigned_attribute)) {
			return array();
		}

		// Make sure it has pa_ prefix
		if (strpos($assigned_attribute, 'pa_') !== 0) {
			$assigned_attribute = 'pa_' . $assigned_attribute;
		}

		// Check if this taxonomy exists
		if (!taxonomy_exists($assigned_attribute)) {
			return array();
		}

		$label = wc_attribute_label($assigned_attribute);

		// Get terms for this taxonomy
		$terms = get_terms(array(
			'taxonomy' => $assigned_attribute,
			'hide_empty' => false,
		));

		if (empty($terms) || is_wp_error($terms)) {
			return array();
		}

		$options = array();
		foreach ($terms as $term) {
			$options[] = array(
				'slug' => $term->slug,
				'name' => $term->name,
			);
		}

		if (!empty($options)) {
			return array(
				'label' => $label . ':',
				'options' => $options,
			);
		}

		return array();
	}

	/**
	 * Get layout data from database including settings and assigned attribute
	 *
	 * @param int $layout_id Layout ID
	 * @return array Layout data with settings and assigned_attribute
	 */
	private function get_layout_data($layout_id) {
		global $wpdb;

		if (!$layout_id) {
			return array('settings' => array(), 'assigned_attribute' => '');
		}

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$layout = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT layout_settings, layout_template, assigned_attributes FROM `{$table_name}` WHERE id = %d",
				$layout_id
			)
		);

		if (!$layout) {
			return array('settings' => array(), 'assigned_attribute' => '');
		}

		$template = $layout->layout_template ?? 'template1';
		$layout_settings = maybe_unserialize($layout->layout_settings);

		// Extract assigned attribute
		$assigned_attribute = '';
		if (!empty($layout->assigned_attributes)) {
			$assigned = json_decode($layout->assigned_attributes, true);
			if (is_array($assigned) && !empty($assigned)) {
				$assigned_attribute = $assigned[0]; // Get first assigned attribute
			}
		}

		// Try to extract template settings
		$keys = array(
			'shopg_product_swatches_settings_' . $template,
			'shopg_productswatches_settings_' . $template,
		);

		$settings = array();
		foreach ($keys as $key) {
			if (isset($layout_settings[$key])) {
				$settings = $layout_settings[$key];
				if (isset($settings['product-swatches-settings'])) {
					$settings = $settings['product-swatches-settings'];
				}
				break;
			}
		}

		return array(
			'settings' => $settings,
			'assigned_attribute' => $assigned_attribute,
		);
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

		$template = $layout->layout_template ?? 'template1';
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
