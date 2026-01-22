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
		// Get layout data including assigned attributes
		$layout_data = $this->get_layout_data($layout_id);
		$settings = isset($layout_data['settings']) ? $layout_data['settings'] : array();
		$assigned_attribute = isset($layout_data['assigned_attribute']) ? $layout_data['assigned_attribute'] : '';

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

		// Get actual attribute terms from the assigned attribute
		global $product;
		$attribute_data = $this->get_assigned_attribute_terms($product, $assigned_attribute);

		// If no attributes found, fall back to demo data
		if (empty($attribute_data)) {
			$attribute_data = array(
				'label' => 'Color:',
				'options' => array(
					array('slug' => 'red', 'name' => 'Red'),
					array('slug' => 'blue', 'name' => 'Blue'),
					array('slug' => 'green', 'name' => 'Green'),
				)
			);
		}

		?>
		<!-- Button Swatches Preview -->
		<div class="shopglut-swatches-demo shopglut-demo-center">
			<div class="shopglut-swatches-wrapper shopglut-template2">
				<!-- Label -->
				<label class="shopglut-attribute-label" style="color:<?php echo esc_attr($label_color); ?>;font-size:<?php echo intval($label_font_size); ?>px;font-weight:600;display:block;margin-bottom:12px;">
					<?php echo esc_html($attribute_data['label']); ?>
				</label>

				<!-- Buttons Container -->
				<div class="shopglut-buttons-container" style="display:grid;grid-template-columns:repeat(<?php echo intval($columns); ?>,1fr);gap:<?php echo intval($gap); ?>px;">
					<?php foreach ($attribute_data['options'] as $index => $option): ?>
						<button type="button" class="shopglut-swatch-button<?php echo $index === 1 ? ' selected' : ''; ?>" data-value="<?php echo esc_attr($option['slug']); ?>" style="<?php echo esc_attr($button_style); ?>">
							<?php echo esc_html($option['name']); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Inline styles for interactions -->
			<style>
				/* Parent container styles for proper centering */
				.shopg-template-preview .html-preview-background {
					display: flex !important;
					align-items: center !important;
					justify-content: center !important;
				}

				.shopg-template-preview .shopglut-single-product {
					width: 100% !important;
				}

				.shopg-template-preview .single-product-container {
					display: flex !important;
					align-items: center !important;
					justify-content: center !important;
					width: 100% !important;
					padding: 20px !important;
				}

				.shopg-template-preview .demo-content {
					display: flex !important;
					align-items: center !important;
					justify-content: center !important;
					width: 100% !important;
				}

				/* Centered demo styling */
				.shopglut-demo-center {
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content: center;
					padding: 24px;
					width: 100%;
				}

				/* Wrapper container with max-width */
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

				/* Button hover and active states */
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
	 * Get terms for the assigned attribute
	 *
	 * @param \WC_Product $product Product object
	 * @param string $assigned_attribute The assigned attribute slug (e.g., 'pa_size')
	 * @return array Attribute data with label and options
	 */
	private function get_assigned_attribute_terms($product, $assigned_attribute) {
		if (empty($assigned_attribute)) {
			return array();
		}

		if (!$product) {
			return array();
		}

		// Normalize attribute name
		$attribute = $assigned_attribute;
		if (strpos($attribute, 'pa_') !== 0) {
			$attribute = 'pa_' . $attribute;
		}

		// Get the label for this attribute
		$label = wc_attribute_label($attribute);

		// Get all terms for this taxonomy
		$terms = get_terms(array(
			'taxonomy' => $attribute,
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

		return array(
			'label' => $label . ':',
			'options' => $options,
		);
	}

	/**
	 * Get product attributes for preview
	 *
	 * @param \WC_Product $product Product object
	 * @return array Attribute data with label and options
	 */
	private function get_product_attributes_for_preview($product) {
		if (!$product) {
			return array();
		}

		if (!method_exists($product, 'is_type') || !$product->is_type('variable')) {
			return array();
		}

		$attributes = $product->get_attributes();

		if (empty($attributes)) {
			return array();
		}

		// Find the first variation attribute (try taxonomy first, then custom)
		foreach ($attributes as $attr_key => $attribute) {
			// Check if this is a variation attribute
			$is_variation = method_exists($attribute, 'get_variation') ? $attribute->get_variation() : false;

			if (!$is_variation) {
				continue;
			}

			// Handle taxonomy-based attributes
			if ($attribute->is_taxonomy()) {
				$taxonomy = $attribute->get_taxonomy();
				$label = wc_attribute_label($taxonomy);

				// Get terms for this taxonomy
				$terms = get_terms(array(
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
				));

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
			}
			// Handle custom product attributes (non-taxonomy)
			else {
				$label = $attribute->get_name();
				$options = $attribute->get_options();

				if (!empty($options)) {
					$formatted_options = array();
					foreach ($options as $option) {
						// Create slug from option name (for custom attributes, value is the name)
						$slug = sanitize_title($option);
						$formatted_options[] = array(
							'slug' => $slug,
							'name' => $option,
						);
					}

					return array(
						'label' => $label . ':',
						'options' => $formatted_options,
					);
				}
			}
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

		$template = $layout->layout_template ?? 'template2';
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
