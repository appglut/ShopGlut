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
	 * Helper to get setting value with minimum validation
	 */
	private function get_setting_value($settings, $key, $default = '', $min_value = null) {
		$value = $default;
		if (isset($settings[$key])) {
			$value = $settings[$key];
		}
		// Apply minimum value if numeric
		if ($min_value !== null && is_numeric($value)) {
			$value = max(intval($value), $min_value);
		}
		return $value;
	}

	/**
	 * Helper to get nested setting value
	 */
	private function get_nested_setting($settings, $section, $key, $default = '', $min_value = null) {
		$value = $default;
		if (isset($settings[$section][$key])) {
			$value = $settings[$section][$key];
		}
		// Apply minimum value if numeric
		if ($min_value !== null && is_numeric($value)) {
			$value = max(intval($value), $min_value);
		}
		return $value;
	}

	/**
	 * Render demo swatches for admin preview
	 */
	private function render_demo_swatches($layout_id) {
		// Get layout data including assigned attributes (same approach as template2)
		$layout_data = $this->get_layout_data($layout_id);
		$settings = isset($layout_data['settings']) ? $layout_data['settings'] : array();
		$assigned_attribute = isset($layout_data['assigned_attribute']) ? $layout_data['assigned_attribute'] : '';

		// Get settings with proper minimums to prevent invisible text
		$dropdown_bg = $this->get_nested_setting($settings, 'swatch_dropdown_container_section', 'swatch_dropdown_background', '#ffffff');
		$dropdown_border = $this->get_nested_setting($settings, 'swatch_dropdown_container_section', 'swatch_dropdown_border_color', '#d1d5db');
		$dropdown_border_width = $this->get_nested_setting($settings, 'swatch_dropdown_container_section', 'swatch_dropdown_border_width', 1);
		$dropdown_radius = $this->get_nested_setting($settings, 'swatch_dropdown_container_section', 'swatch_dropdown_border_radius', 6);

		$text_color = $this->get_nested_setting($settings, 'swatch_dropdown_typography_section', 'swatch_dropdown_text_color', '#374151');
		$font_size = $this->get_nested_setting($settings, 'swatch_dropdown_typography_section', 'swatch_dropdown_font_size', 14, 12); // Min 12px

		$label_color = $this->get_nested_setting($settings, 'swatch_attribute_label_section', 'swatch_attribute_label_color', '#374151');
		$label_font_size = $this->get_nested_setting($settings, 'swatch_attribute_label_section', 'swatch_attribute_label_font_size', 14, 12); // Min 12px

		// Get actual attribute terms from the assigned attribute (same approach as template2)
		global $product;
		$attribute_data = $this->get_assigned_attribute_terms($product, $assigned_attribute);

		// If no attributes found, fall back to demo data
		if (empty($attribute_data)) {
			$attribute_data = array(
				'label' => 'Choose option:',
				'options' => array(
					array('slug' => 'option-1', 'name' => 'Option 1'),
					array('slug' => 'option-2', 'name' => 'Option 2'),
					array('slug' => 'option-3', 'name' => 'Option 3'),
				)
			);
		}

		// Enforce minimum font sizes for demo display (better visibility)
		$font_size = max(intval($font_size), 14); // Min 14px for demo
		$label_font_size = max(intval($label_font_size), 14); // Min 14px for demo

		?>
		<!-- Simple Demo: Dropdown Swatch -->
		<div class="shopglut-swatches-demo" style="display:flex;align-items:center;justify-content:center;padding:30px;background:#f9fafb;border-radius:8px;">
			<div class="shopglut-swatches-wrapper shopglut-template1" style="width:100%;max-width:400px;text-align:center;">
				<!-- Label -->
				<label class="shopglut-attribute-label" style="color:<?php echo esc_attr($label_color); ?>;font-size:<?php echo intval($label_font_size); ?>px;font-weight:600;display:block;margin-bottom:12px;">
					<?php echo esc_html($attribute_data['label']); ?>
				</label>

				<!-- Dropdown -->
				<select class="shopglut-swatch-dropdown" disabled style="background-color:<?php echo esc_attr($dropdown_bg); ?>;border:<?php echo intval($dropdown_border_width); ?>px solid <?php echo esc_attr($dropdown_border); ?>;border-radius:<?php echo intval($dropdown_radius); ?>px;color:<?php echo esc_attr($text_color); ?>;font-size:<?php echo intval($font_size); ?>px;padding:12px 16px;min-height:46px;width:100%;cursor:not-allowed;opacity:0.8;">
					<option value="">Choose an option</option>
					<?php foreach ($attribute_data['options'] as $option): ?>
						<option value="<?php echo esc_attr($option['slug']); ?>">
							<?php echo esc_html($option['name']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Get product attributes for preview
	 *
	 * @param \WC_Product $product Product object
	 * @param int $layout_id Layout ID for admin preview
	 * @return array Attribute data with label and options
	 */
	private function get_product_attributes_for_preview($product, $layout_id = 0) {
		// First try to get from actual product
		if ($product && method_exists($product, 'is_type') && $product->is_type('variable')) {
			$attributes = $product->get_attributes();

			if (!empty($attributes)) {
				// Find the first variation attribute
				foreach ($attributes as $attr_key => $attribute) {
					$is_variation = method_exists($attribute, 'get_variation') ? $attribute->get_variation() : false;

					if (!$is_variation) {
						continue;
					}

					// Handle taxonomy-based attributes
					if ($attribute->is_taxonomy()) {
						$taxonomy = $attribute->get_taxonomy();
						$label = wc_attribute_label($taxonomy);

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
								$slug = sanitize_title($option);
								$formatted_options[] = array(
									'slug' => $slug,
									'name' => $option,
								);
							}

							if (!empty($formatted_options)) {
								return array(
									'label' => $label . ':',
									'options' => $formatted_options,
								);
							}
						}
					}
				}
			}
		}

		// No product found, try to get from assigned attributes in database
		if ($layout_id) {
			$assigned_attrs = $this->get_assigned_attributes_for_layout($layout_id);
			if (!empty($assigned_attrs)) {
				// Get the first assigned attribute's terms
				foreach ($assigned_attrs as $attr_slug) {
					// Make sure it has pa_ prefix
					if (strpos($attr_slug, 'pa_') !== 0) {
						$attr_slug = 'pa_' . $attr_slug;
					}

					// Check if this taxonomy exists
					if (taxonomy_exists($attr_slug)) {
						$label = wc_attribute_label($attr_slug);

						$terms = get_terms(array(
							'taxonomy' => $attr_slug,
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
				}
			}
		}

		return array();
	}

	/**
	 * Get assigned attributes for a layout from database
	 *
	 * @param int $layout_id Layout ID
	 * @return array Array of attribute slugs
	 */
	private function get_assigned_attributes_for_layout($layout_id) {
		if (!$layout_id) {
			error_log('template1Markup: No layout_id provided');
			return array();
		}

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$layout = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT assigned_attributes FROM `{$table_name}` WHERE id = %d",
				$layout_id
			)
		);

		if (!$layout) {
			error_log('template1Markup: No layout found for id ' . $layout_id);
			return array();
		}

		$assigned = maybe_unserialize($layout->assigned_attributes);

		if (!is_array($assigned)) {
			error_log('template1Markup: assigned_attributes is not an array for layout ' . $layout_id . ': ' . print_r($layout->assigned_attributes, true));
			return array();
		}

		error_log('template1Markup: Found assigned attributes for layout ' . $layout_id . ': ' . implode(', ', $assigned));
		return $assigned;
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
	 * Get terms for the assigned attribute
	 *
	 * @param \WC_Product $product Product object
	 * @param string $assigned_attribute The assigned attribute slug (e.g., 'pa_color')
	 * @return array Attribute data with label and options
	 */
	private function get_assigned_attribute_terms($product, $assigned_attribute) {
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
}
