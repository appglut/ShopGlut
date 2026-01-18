<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template13;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Module Integration Helper for Single Product Template
 * Handles rendering of integrated modules (Wishlist, Swatches, Badges, Comparison, Custom Fields)
 */
class Template13ModuleIntegration {

	private $settings;
	private $product_id;

	public function __construct($settings = array(), $product_id = 0) {
		$this->settings = $settings;
		$this->product_id = $product_id;
	}

	/**
	 * Render wishlist button
	 */
	public function render_wishlist() {
		if (!$this->is_module_enabled('wishlist')) {
			return;
		}

		// Get wishlist instance
		if (!class_exists('Shopglut\enhancements\wishlist\dataManage')) {
			return;
		}

		$wishlist_instance = \Shopglut\enhancements\wishlist\dataManage::get_instance();

		if (!$wishlist_instance) {
			return;
		}

		// Make sure we have a global product - CRITICAL for wishlist to work
		global $product;
		$original_product = $product;

		if (!$product && $this->product_id) {
			$product = wc_get_product($this->product_id);
		}

		if (!$product) {
			return;
		}

		// Call the wishlist button rendering method
		if (method_exists($wishlist_instance, 'shopglut_add_wishlist_button_single')) {
			$wishlist_instance->shopglut_add_wishlist_button_single();
		}

		// Restore original global product if we changed it
		if ($original_product !== $product) {
			$product = $original_product;
		}
	}

	
	/**
	 * Render product badges
	 */
	public function render_badges() {
		if (!$this->is_module_enabled('badges')) {
			return;
		}

		// Get current product ID
		$current_product_id = $this->product_id;
		if (!$current_product_id) {
			$current_product_id = get_the_ID();
		}

		if (!$current_product_id) {
			return;
		}

		// Get the specific badge layout ID from settings
		$badge_layout_id = $this->get_setting('badge_layout_id');

		if (empty($badge_layout_id)) {
			return;
		}

		// Get the specific badge layout from database
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with proper escaping
		$badge_layout = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `{$table_name}` WHERE id = %d",
				$badge_layout_id
			)
		);

		if (!$badge_layout) {
			return;
		}

		// Get badge settings
		$badge_settings = maybe_unserialize($badge_layout->layout_settings);

		if (!$badge_settings || !is_array($badge_settings)) {
			return;
		}

		// Check if badge is enabled
		$enable_badge = $this->get_nested_value($badge_settings, 'enable_badge');
		if (empty($enable_badge)) {
			return;
		}

		// Check if badge should display for this product
		$display_check = $this->should_display_badge_for_product($badge_settings, $current_product_id);

		if (!$display_check) {
			return;
		}

		// Check if badge meets its type-specific conditions (sale, new, etc.)
		$condition_check = $this->should_display_badge_by_conditions($badge_settings, $current_product_id);

		if (!$condition_check) {
			return;
		}

		// Determine display position
		$badge_position = $this->get_setting('badge_position', 'on_product_image');

		// Map position to CSS class
		$position_class_map = array(
			'on_product_image' => 'product_image',
			'before_product_title' => 'before-title',
			'after_product_title' => 'after-title',
			'before_price' => 'before-price',
		);

		$position_class = isset($position_class_map[$badge_position]) ? $position_class_map[$badge_position] : 'product_image';

		echo '<div class="shopglut-product-badges shopglut-badges-' . esc_attr($position_class) . '">';

		// Render the badge using the full badge settings
		$this->render_single_badge($badge_settings);

		echo '</div>';
	}

	/**
	 * Check if badge should display for a specific product
	 */
	private function should_display_badge_for_product($settings, $product_id) {
		if (!isset($settings['display-locations']) || !is_array($settings['display-locations'])) {
			return false;
		}

		$display_locations = $settings['display-locations'];

		// Check if "All Products" is selected - this takes priority
		if (in_array('All Products', $display_locations)) {
			return true;
		}

		// Check if specific product ID is selected
		if (in_array('product_' . $product_id, $display_locations)) {
			return true;
		}

		// Check if product's categories are selected
		$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
		if (!empty($product_categories)) {
			foreach ($product_categories as $cat_id) {
				if (in_array('cat_' . $cat_id, $display_locations)) {
					return true;
				}
			}
		}

		// Check if "All Categories" is selected and product has categories
		if (in_array('All Categories', $display_locations) && !empty($product_categories)) {
			return true;
		}

		return false;
	}

	/**
	 * Check badge conditions
	 */
	private function should_display_badge_by_conditions($settings, $product_id) {
		// Get badge type from nested structure
		$badge_type = $this->get_nested_value($settings, 'badge_type');

		// Get product object
		$product = wc_get_product($product_id);
		if (!$product) {
			return false;
		}

		switch ($badge_type) {
			case 'sale':
				return $product->is_on_sale();

			case 'new':
				// Get product creation date
				$post_date = get_the_date('Y-m-d H:i:s', $product_id);
				$creation_date = new DateTime($post_date);
				$current_date = new DateTime();
				$interval = $creation_date->diff($current_date);
				$days_since_creation = $interval->days;

				$new_product_days = $this->get_nested_value($settings, 'new_product_days', 7);

				// Handle array format for slider fields
				if (is_array($new_product_days) && isset($new_product_days['new_product_days'])) {
					$new_product_days = intval($new_product_days['new_product_days']);
				} else {
					$new_product_days = intval($new_product_days);
				}

				return $days_since_creation <= $new_product_days;

			case 'featured':
				return $product->is_featured();

			default:
				return true; // Show custom badges by default
		}
	}

	/**
	 * Get nested value from array using dot notation
	 */
	private function get_nested_value($array, $key, $default = null) {
		if (!is_array($array)) {
			return $default;
		}

		// Handle direct key access
		if (isset($array[$key])) {
			return $array[$key];
		}

		// Check for badge structure - try shopg_product_badge_settings first
		if (isset($array['shopg_product_badge_settings'][$key])) {
			return $array['shopg_product_badge_settings'][$key];
		}

		// Check nested under product_badge-settings
		if (isset($array['shopg_product_badge_settings']['product_badge-settings'][$key])) {
			$value = $array['shopg_product_badge_settings']['product_badge-settings'][$key];
			// Handle slider fields with array format
			if (is_array($value) && isset($value[$key])) {
				return $value[$key];
			}
			return $value;
		}

		// Handle dot notation for nested access
		if (strpos($key, '.') !== false) {
			$keys = explode('.', $key);
			$value = $array;

			foreach ($keys as $k) {
				if (is_array($value) && isset($value[$k])) {
					$value = $value[$k];
				} else {
					return $default;
				}
			}

			return $value;
		}

		return $default;
	}

	
	/**
	 * Render a single badge
	 */
	private function render_single_badge($badge_settings) {
		// Get badge display settings using nested value extraction
		$badge_text_settings = $this->get_nested_value($badge_settings, 'badge_text_settings', array());
		$badge_background_settings = $this->get_nested_value($badge_settings, 'badge_background_settings', array());
		$badge_dimensions_settings = $this->get_nested_value($badge_settings, 'badge_dimensions_settings', array());
		$badge_border_settings = $this->get_nested_value($badge_settings, 'badge_border_settings', array());
		$badge_shadow_settings = $this->get_nested_value($badge_settings, 'badge_shadow_settings', array());

		// Get badge type and text
		$badge_type = $this->get_nested_value($badge_settings, 'badge_type', 'sale');
		$badge_text = $this->get_badge_text($badge_type, $badge_settings);

		if (empty($badge_text)) {
			return;
		}

		// Build inline styles with default values using nested extraction
		$text_color = $this->get_nested_value($badge_text_settings, 'text_color', '#ffffff');
		$font_size = $this->get_nested_value($badge_text_settings, 'font_size', '12');
		if (is_array($font_size) && isset($font_size['font_size'])) {
			$font_size = $font_size['font_size'];
		}
		$font_weight = $this->get_nested_value($badge_text_settings, 'font_weight', '700');
		$text_transform = $this->get_nested_value($badge_text_settings, 'text_transform', 'uppercase');

		$background_color = $this->get_nested_value($badge_background_settings, 'background_color', '#ff0000');
		$enable_gradient = $this->get_nested_value($badge_background_settings, 'enable_gradient', false);
		$gradient_color = $this->get_nested_value($badge_background_settings, 'gradient_color', '#cc0000');

		$padding_top_bottom = $this->get_nested_value($badge_dimensions_settings, 'padding_top_bottom', '5');
		if (is_array($padding_top_bottom) && isset($padding_top_bottom['padding_top_bottom'])) {
			$padding_top_bottom = $padding_top_bottom['padding_top_bottom'];
		}
		$padding_left_right = $this->get_nested_value($badge_dimensions_settings, 'padding_left_right', '10');
		if (is_array($padding_left_right) && isset($padding_left_right['padding_left_right'])) {
			$padding_left_right = $padding_left_right['padding_left_right'];
		}
		$border_radius = $this->get_nested_value($badge_dimensions_settings, 'border_radius', '3');
		if (is_array($border_radius) && isset($border_radius['border_radius'])) {
			$border_radius = $border_radius['border_radius'];
		}

		$border_width = $this->get_nested_value($badge_border_settings, 'border_width', '0');
		if (is_array($border_width) && isset($border_width['border_width'])) {
			$border_width = $border_width['border_width'];
		}
		$border_color = $this->get_nested_value($badge_border_settings, 'border_color', '#000000');

		$enable_shadow = $this->get_nested_value($badge_shadow_settings, 'enable_shadow', false);
		$shadow_color = $this->get_nested_value($badge_shadow_settings, 'shadow_color', 'rgba(0, 0, 0, 0.2)');
		$shadow_blur = $this->get_nested_value($badge_shadow_settings, 'shadow_blur', '4');
		if (is_array($shadow_blur) && isset($shadow_blur['shadow_blur'])) {
			$shadow_blur = $shadow_blur['shadow_blur'];
		}

		// Build styles
		$styles = array();
		$styles[] = "color: {$text_color}";
		$styles[] = "font-size: {$font_size}px";
		$styles[] = "font-weight: {$font_weight}";
		$styles[] = "text-transform: {$text_transform}";

		if ($enable_gradient) {
			$styles[] = "background: linear-gradient(135deg, {$background_color}, {$gradient_color})";
		} else {
			$styles[] = "background-color: {$background_color}";
		}

		$styles[] = "padding: {$padding_top_bottom}px {$padding_left_right}px";
		$styles[] = "border-radius: {$border_radius}px";

		if ($border_width > 0) {
			$styles[] = "border: {$border_width}px solid {$border_color}";
		}

		if ($enable_shadow) {
			$styles[] = "box-shadow: 0 2px {$shadow_blur}px {$shadow_color}";
		}

		// Render badge
		echo '<div class="shopglut-product-badge shopglut-badge-' . esc_attr($badge_type) . '" style="' . esc_attr(implode('; ', $styles)) . '">';
		echo esc_html($badge_text);
		echo '</div>';
	}

	/**
	 * Get badge text based on type and settings
	 */
	private function get_badge_text($badge_type, $badge_settings) {
		switch ($badge_type) {
			case 'sale':
				$sale_text = $this->get_nested_value($badge_settings, 'sale_badge_text', 'SALE');
				// Parse text that might contain color info like "SALE!fffdddd"
				if (preg_match('/^(.+?)([a-f0-9]{6}|[a-f0-9]{3})$/i', $sale_text, $matches)) {
					return $matches[1]; // Return only the text part
				}
				return $sale_text;

			case 'new':
				return $this->get_nested_value($badge_settings, 'new_badge_text', 'NEW');

			case 'featured':
				return $this->get_nested_value($badge_settings, 'featured_badge_text', 'FEATURED');

			case 'low_stock':
				return $this->get_nested_value($badge_settings, 'low_stock_badge_text', 'LOW STOCK');

			case 'out_of_stock':
				return $this->get_nested_value($badge_settings, 'out_of_stock_badge_text', 'OUT OF STOCK');

			default:
				// Try to get custom text
				$custom_text = $this->get_nested_value($badge_settings, 'custom_badge_text', '');
				return !empty($custom_text) ? $custom_text : '';
		}
	}

	/**
	 * Render product comparison button
	 */
	public function render_comparison() {
		if (!$this->is_module_enabled('comparison')) {
			return;
		}

		// Get the specific comparison layout ID from settings
		$comparison_layout_id = $this->get_setting('comparison_layout_id');

		if (empty($comparison_layout_id)) {
			return;
		}

		// Get comparison layout data from database
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query, no caching needed
		$comparison_layout = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
			$wpdb->prepare(
				sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($table_name), $comparison_layout_id) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf for table name, expected 0 but proper placeholders are used
			)
		);

		if (!$comparison_layout) {
			return;
		}

		// Get layout settings
		$layout_settings = maybe_unserialize($comparison_layout->layout_settings);

		// Extract comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Get button text settings
		$button_text_settings = isset($page_settings['button_text_settings'])
			? $page_settings['button_text_settings']
			: array();

		// Get button icon settings
		$button_icon_settings = isset($page_settings['button_icon_settings'])
			? $page_settings['button_icon_settings']
			: array();

		// Helper function to get setting value
		$get_setting_value = function($settings, $key, $default = null) {
			if (isset($settings[$key])) {
				if (is_array($settings[$key]) && isset($settings[$key][$key])) {
					return $settings[$key][$key];
				}
				return $settings[$key];
			}
			return $default;
		};

		// Extract values
		$button_text = $get_setting_value($button_text_settings, 'button_text', __('Add to Compare', 'shopglut'));
		$button_added_text = $get_setting_value($button_text_settings, 'button_added_text', __('Remove from Compare', 'shopglut'));
		$show_icon = $get_setting_value($button_icon_settings, 'show_button_icon', true);
		$button_icon = $get_setting_value($button_icon_settings, 'button_icon', 'fas fa-exchange-alt');

		// Ensure icon has proper Font Awesome prefix
		if (!empty($button_icon) && strpos($button_icon, 'fa-') === 0) {
			$button_icon = 'fas ' . $button_icon;
		} elseif (!empty($button_icon) && !preg_match('/^(fas|far|fab|fa)\s/', $button_icon)) {
			$button_icon = 'fas ' . $button_icon;
		}

		$icon_position = $get_setting_value($button_icon_settings, 'button_icon_position', 'left');

		// Get button styling
		$button_styling = isset($page_settings['button_styling'])
			? $page_settings['button_styling']
			: array();

		// Build inline styles
		$inline_styles = '';
		if (!empty($button_styling)) {
			$style_parts = array();

			if (isset($button_styling['background_color'])) {
				$style_parts[] = 'background-color: ' . esc_attr($button_styling['background_color']);
			}
			if (isset($button_styling['text_color'])) {
				$style_parts[] = 'color: ' . esc_attr($button_styling['text_color']);
			}
			if (isset($button_styling['border_color'])) {
				$style_parts[] = 'border-color: ' . esc_attr($button_styling['border_color']);
			}
			if (isset($button_styling['border_width'])) {
				$style_parts[] = 'border-width: ' . esc_attr($button_styling['border_width']) . 'px';
			}
			if (isset($button_styling['border_radius'])) {
				$style_parts[] = 'border-radius: ' . esc_attr($button_styling['border_radius']) . 'px';
			}
			if (isset($button_styling['padding'])) {
				$style_parts[] = 'padding: ' . esc_attr($button_styling['padding']);
			}

			$inline_styles = implode('; ', $style_parts);
		}

		// Render the comparison button
		echo '<div class="shopglut-integrated-comparison">';
		echo '<div class="shopglut-comparison-button-wrapper">';
		echo '<button class="shopglut-add-to-comparison-single" data-product-id="' . esc_attr($this->product_id) . '" data-added-text="' . esc_attr($button_added_text) . '" data-default-text="' . esc_attr($button_text) . '" style="' . esc_attr($inline_styles) . '">';

		if ($show_icon && $icon_position === 'left') {
			echo '<i class="' . esc_attr($button_icon) . '"></i> ';
		}

		echo esc_html($button_text);

		if ($show_icon && $icon_position === 'right') {
			echo ' <i class="' . esc_attr($button_icon) . '"></i>';
		}

		echo '</button>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render custom fields
	 */
	public function render_custom_fields() {
		if (!$this->is_module_enabled('custom_fields')) {
			return;
		}

		// Get the specific custom field layout ID from settings
		$custom_field_layout_id = $this->get_setting('custom_fields_layout_id');

		// Get custom field instance
		if (!class_exists('Shopglut\tools\productCustomField\ProductCustomFieldHandler')) {
			return;
		}

		$custom_field_instance = \Shopglut\tools\productCustomField\ProductCustomFieldHandler::get_instance();

		if (!$custom_field_instance) {
			return;
		}

		echo '<div class="shopglut-integrated-custom-fields">';

		// If no specific layout is selected, show all fields (fallback behavior)
		if (empty($custom_field_layout_id)) {
			$fields = $custom_field_instance->get_all_custom_fields();

			if (!empty($fields)) {
				foreach ($fields as $field) {
					$settings = maybe_unserialize($field['field_settings']);

					// Only render fields that should be shown on frontend
					if (!empty($settings['show_in_frontend'])) {
						$custom_field_instance->render_frontend_field($field, $settings);
					}
				}
			}
		} else {
			// Get specific custom field layout from database
			global $wpdb;
			$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query, no caching needed
			$custom_field = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
				$wpdb->prepare(
					sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($table_name), $custom_field_layout_id) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf for table name
				)
			);

			
			if (!$custom_field) {
				// No custom field found with the specified ID
			} else {
				// Convert stdClass to array for compatibility
				$custom_field_array = (array) $custom_field;
				// Get field settings
				$raw_settings = maybe_unserialize($custom_field_array['field_settings']);

				// Extract the custom fields from the nested structure
				$field_settings = [];
				if (isset($raw_settings['shopg_product_custom_field_settings']['custom_fields'])) {
					$field_settings = $raw_settings['shopg_product_custom_field_settings']['custom_fields'];
				}

				if (empty($field_settings) || !is_array($field_settings)) {
					// Invalid or empty field settings
				} else {
					// Check if this custom field should be shown for current product
					$current_product_id = $this->product_id;
					if (!$current_product_id) {
						$current_product_id = get_the_ID();
					}

					if ($current_product_id) {
						// Check if this product is in the selected products list
						$selected_products = $raw_settings['shopg_product_custom_field_settings']['select_products'] ?? [];
						$should_show = false;

						if (in_array('all', $selected_products) || in_array((string)$current_product_id, $selected_products)) {
							$should_show = true;
						}

						
						if ($should_show) {
							// Render each custom field in the layout
							foreach ($field_settings as $index => $field_data) {
																$custom_field_instance->render_frontend_field($custom_field_array, $field_data);
							}
						}
					}
				}
			}
		}

		echo '</div>';
	}

	/**
	 * Render module at specific position
	 *
	 * @param string $position The position to render modules at
	 * @param string $module_type The specific module type to render (optional)
	 */
	public function render_at_position($position, $module_type = null) {
		// Map of positions to modules and their settings keys
		$position_map = array(
			'after_add_to_cart' => array(
				'wishlist' => 'wishlist_position',
				'comparison' => 'comparison_position',
				'custom_fields' => 'custom_fields_position',
			),
			'before_add_to_cart' => array(
				'wishlist' => 'wishlist_position',
				'comparison' => 'comparison_position',
				'custom_fields' => 'custom_fields_position',
			),
			'after_product_title' => array(
				'wishlist' => 'wishlist_position',
				'badges' => 'badge_position',
				'custom_fields' => 'custom_fields_position',
			),
			'before_price' => array(
				'wishlist' => 'wishlist_position',
				'custom_fields' => 'custom_fields_position',
			),
			'on_product_image' => array(
				'badges' => 'badge_position',
			),
			'before_product_title' => array(
				'badges' => 'badge_position',
			),
			'after_description' => array(
				'custom_fields' => 'custom_fields_position',
			),
		);

		if (!isset($position_map[$position])) {
			return;
		}

		// Render modules that should be shown at this position
		foreach ($position_map[$position] as $module => $setting_key) {
			// If specific module type is requested, only render that one
			if ($module_type !== null && $module !== $module_type) {
				continue;
			}

			// Get the module's configured position
			$module_position = $this->get_setting($setting_key);

			// Check if module should be shown at this position
			if ($module_position === $position) {
				$render_method = "render_{$module}";
				if (method_exists($this, $render_method)) {
					$this->$render_method();
				}
			}
		}
	}

	/**
	 * Check if a module is enabled
	 *
	 * @param string $module_type Module type (wishlist, badges, comparison, custom_fields)
	 * @return bool
	 */
	private function is_module_enabled($module_type) {
		$enable_key = "enable_{$module_type}";

		// Check at root level first (handles both boolean true and string '1')
		if (isset($this->settings[$enable_key])) {
			$value = $this->settings[$enable_key];
			if ($value === true || $value === '1' || $value === 1) {
				return true;
			}
		}

		// Check nested under single-product-settings
		if (isset($this->settings['single-product-settings'][$module_type . '_integration'][$enable_key])) {
			$value = $this->settings['single-product-settings'][$module_type . '_integration'][$enable_key];
			return $value === true || $value === '1' || $value === 1;
		}

		return false;
	}

	/**
	 * Get a setting value
	 *
	 * @param string $key Setting key
	 * @param mixed $default Default value
	 * @return mixed
	 */
	private function get_setting($key, $default = null) {
		// Check at root level first
		if (isset($this->settings[$key])) {
			return $this->settings[$key];
		}

		// Check nested under single-product-settings
		if (isset($this->settings['single-product-settings'][$key])) {
			return $this->settings['single-product-settings'][$key];
		}

		// Check nested under single-product-settings -> module_integration
		if (isset($this->settings['single-product-settings']['custom_fields_integration'][$key])) {
			return $this->settings['single-product-settings']['custom_fields_integration'][$key];
		}

		return $default;
	}

	/**
	 * Render all modules wrapper
	 * Creates a container for modules at a specific position
	 */
	public static function render_module_wrapper($settings, $product_id, $position, $module_type = null) {
		$integration = new self($settings, $product_id);

		ob_start();
		$integration->render_at_position($position, $module_type);
		$output = ob_get_clean();

		if (!empty($output)) {
			echo '<div class="shopglut-modules-wrapper position-' . esc_attr($position) . '">';
			echo wp_kses_post($output);
			echo '</div>';
		}
	}
}
