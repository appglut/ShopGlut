<?php
namespace Shopglut\layouts\shopLayout;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Module Integration Helper for Shop Layout
 * Handles rendering of integrated modules (Wishlist, Swatches, Badges, Comparison, Quick View, Custom Fields)
 */
class ModuleIntegration {

	private $settings;
	private $product_id;
	private $template_name;

	public function __construct($settings = array(), $product_id = 0, $template_name = '') {
		$this->settings = $settings;
		$this->product_id = $product_id;
		$this->template_name = $template_name;
	}

	/**
	 * Render wishlist button
	 */
	public function render_wishlist() {
		// DEBUG: Check if wishlist is enabled
		echo '<div style="background: #ffebee; padding: 5px; margin: 5px 0; font-size: 11px;">';
		echo 'DEBUG ModuleIntegration::render_wishlist - Wishlist enabled: ' . ($this->is_module_enabled('wishlist') ? '<strong>YES</strong>' : '<strong style="color:red;">NO</strong>');
		echo '</div>';

		if (!$this->is_module_enabled('wishlist')) {
			echo '<div style="background: #ffcdd2; padding: 10px; color: #c62828; font-weight: bold;">⚠️ WISHLIST NOT ENABLED - enable_wishlist is not set or false</div>';
			return;
		}

		// Get wishlist instance
		if (!class_exists('Shopglut\\enhancements\\wishlist\\dataManage')) {
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

		// Add template-specific wrapper class for custom styling
		$template_class = !empty($this->template_name) ? ' template-' . esc_attr($this->template_name) : '';
		echo '<div class="shopglut-template-wishlist' . esc_attr($template_class) . '">';

		// Determine which wishlist button method to call based on the current page context
		// For shop layout templates, we use shopglut_add_wishlist_button_shop()
		// For archive pages, we use shopglut_add_wishlist_button_category()
		// IMPORTANT: Shop layout templates can be used in shortcodes or custom pages (not just is_shop())
		// So we need to call the shop method by default for shop layout templates

		// Set a global flag to indicate we're rendering from a shop layout template
		// This allows wishlist buttons to render even outside is_shop() context
		global $shopglut_rendering_shop_layout;
		$shopglut_rendering_shop_layout = true;

		if (is_product_category() || is_product_tag() || is_product_taxonomy()) {
			// Archive/category page context
			if (method_exists($wishlist_instance, 'shopglut_add_wishlist_button_category')) {
				$wishlist_instance->shopglut_add_wishlist_button_category();
			}
		} else {
			// Shop page context OR shop layout template in shortcode/custom page
			// Use shop method as default for shop layout templates
			if (method_exists($wishlist_instance, 'shopglut_add_wishlist_button_shop')) {
				$wishlist_instance->shopglut_add_wishlist_button_shop();
			}
		}

		// Reset the flag
		$shopglut_rendering_shop_layout = false;

		echo '</div>';

		// Restore original global product if we changed it
		if ($original_product !== $product) {
			$product = $original_product;
		}
	}

	/**
	 * Render product swatches
	 */
	public function render_swatches() {
		if (!$this->is_module_enabled('swatches')) {
			return;
		}

		// Check if swatches module exists and is enabled
		if (!class_exists('Shopglut\\enhancements\\Swatches\\Swatches')) {
			return;
		}

		// Swatches are handled automatically by WooCommerce hooks
		// This is a placeholder for any custom positioning logic
		do_action('shopglut_swatches_shop_product', $this->product_id);
	}

	/**
	 * Render product badges
	 */
	public function render_badges() {
		if (!$this->is_module_enabled('badges')) {
			return;
		}

		// Get the specific badge layout ID from settings
		$badge_layout_id = $this->get_setting('badge_layout_id');

		if (empty($badge_layout_id)) {
			return;
		}

		// Get badge data from database
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';

		// Try to get from cache first
		$cache_key = 'shopglut_badge_layout_' . $badge_layout_id;
		$badge = wp_cache_get($cache_key, 'shopglut_badges');

		if (false === $badge) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table name is safe, using $wpdb->prefix
			$badge = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM `" . esc_sql($table_name) . "` WHERE id = %d", $badge_layout_id)
			);

			// Cache the result for 1 hour
			wp_cache_set($cache_key, $badge, 'shopglut_badges', HOUR_IN_SECONDS);
		}

		if (!$badge) {
			return;
		}

		// Get badge instance for rendering
		if (!class_exists('Shopglut\\enhancements\\ProductBadges\\BadgeDataManage')) {
			return;
		}

		$badge_instance = \Shopglut\enhancements\ProductBadges\BadgeDataManage::get_instance();

		if (!$badge_instance) {
			return;
		}

		// Determine display position
		$badge_position = $this->get_setting('badge_position', 'on_product_image');

		// Map position to CSS class
		$position_class_map = array(
			'on_product_image' => 'product_image',
			'before_product_title' => 'before-title',
			'after_product_title' => 'after-title',
		);

		$position_class = isset($position_class_map[$badge_position]) ? $position_class_map[$badge_position] : 'product_image';

		echo '<div class="shopglut-product-badges shopglut-badges-' . esc_attr($position_class) . '">';

		// Render the specific badge
		if (method_exists($badge_instance, 'render_badge')) {
			$badge_instance->render_badge($badge);
		}

		echo '</div>';
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

		// Try to get from cache first
		$cache_key = 'shopglut_comparison_layout_' . $comparison_layout_id;
		$comparison_layout = wp_cache_get($cache_key, 'shopglut_comparisons');

		if (false === $comparison_layout) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table name is safe, using $wpdb->prefix
			$comparison_layout = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM `" . esc_sql($table_name) . "` WHERE id = %d", $comparison_layout_id)
			);

			// Cache the result for 1 hour
			wp_cache_set($cache_key, $comparison_layout, 'shopglut_comparisons', HOUR_IN_SECONDS);
		}

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
		} elseif (!empty($button_icon) && !preg_match('/^(fas|far|fab|fa)\\s/', $button_icon)) {
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
		echo '<button class="shopglut-add-to-comparison" data-product-id="' . esc_attr($this->product_id) . '" data-added-text="' . esc_attr($button_added_text) . '" data-default-text="' . esc_attr($button_text) . '" style="' . esc_attr($inline_styles) . '">';

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
	 * Render quick view button
	 */
	public function render_quickview() {
		if (!$this->is_module_enabled('quickview')) {
			return;
		}

		// Get quick view instance
		if (!class_exists('Shopglut\\enhancements\\QuickView\\QuickView')) {
			return;
		}

		$quickview_instance = \Shopglut\enhancements\QuickView\QuickView::get_instance();

		if (!$quickview_instance) {
			return;
		}

		// Render quick view button
		if (method_exists($quickview_instance, 'render_quick_view_button')) {
			$quickview_instance->render_quick_view_button($this->product_id);
		}
	}

	/**
	 * Render custom fields
	 */
	public function render_custom_fields() {
		if (!$this->is_module_enabled('custom_fields')) {
			return;
		}

		// Get custom fields instance
		if (!class_exists('Shopglut\\tools\\productCustomField\\ProductCustomFieldHandler')) {
			return;
		}

		$custom_field_instance = \Shopglut\tools\productCustomField\ProductCustomFieldHandler::get_instance();

		if (!$custom_field_instance) {
			return;
		}

		// Get all custom fields
		$fields = $custom_field_instance->get_all_custom_fields();

		if (empty($fields)) {
			return;
		}

		echo '<div class="shopglut-integrated-custom-fields">';

		// Render each custom field
		foreach ($fields as $field) {
			$settings = maybe_unserialize($field['field_settings']);

			// Only render fields that should be shown on frontend
			if (!empty($settings['show_in_frontend'])) {
				$custom_field_instance->render_frontend_field($field, $settings);
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
			'default' => array(
				'wishlist' => 'wishlist_position',
				'comparison' => 'comparison_position',
				'quickview' => 'quickview_position',
			),
			'after_add_to_cart' => array(
				'wishlist' => 'wishlist_position',
				'comparison' => 'comparison_position',
				'quickview' => 'quickview_position',
				'custom_fields' => 'custom_fields_position',
			),
			'before_add_to_cart' => array(
				'comparison' => 'comparison_position',
			),
			'after_product_title' => array(
				'wishlist' => 'wishlist_position',
				'swatches' => 'swatches_position',
				'custom_fields' => 'custom_fields_position',
			),
			'before_price' => array(
				'wishlist' => 'wishlist_position',
				'swatches' => 'swatches_position',
			),
			'after_price' => array(
				'swatches' => 'swatches_position',
				'custom_fields' => 'custom_fields_position',
			),
			'on_product_image' => array(
				'badges' => 'badge_position',
				'wishlist' => 'wishlist_position',
				'quickview' => 'quickview_position',
			),
			'before_product_title' => array(
				'badges' => 'badge_position',
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

			$module_position = $this->get_setting($setting_key);
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
	 * @param string $module_type Module type (wishlist, swatches, badges, comparison, quickview, custom_fields)
	 * @return bool
	 */
	private function is_module_enabled($module_type) {
		$enable_key = "enable_{$module_type}";
		return isset($this->settings[$enable_key]) && $this->settings[$enable_key];
	}

	/**
	 * Get a setting value
	 *
	 * @param string $key Setting key
	 * @param mixed $default Default value
	 * @return mixed
	 */
	private function get_setting($key, $default = null) {
		return isset($this->settings[$key]) ? $this->settings[$key] : $default;
	}

	/**
	 * Render all modules wrapper
	 * Creates a container for modules at a specific position
	 *
	 * @param array $settings Module integration settings
	 * @param int $product_id Product ID
	 * @param string $position Position identifier
	 * @param string|null $module_type Specific module type to render
	 * @param string $template_name Template name for custom styling (e.g., 'template1')
	 */
	public static function render_module_wrapper($settings, $product_id, $position, $module_type = null, $template_name = '') {
		$integration = new self($settings, $product_id, $template_name);

		ob_start();
		$integration->render_at_position($position, $module_type);
		$output = ob_get_clean();

		if (!empty($output)) {
			$template_class = !empty($template_name) ? ' template-' . esc_attr($template_name) : '';
			echo '<div class="shopglut-modules-wrapper position-' . esc_attr($position) . esc_attr($template_class) . '">';
			echo wp_kses_post($output);
			echo '</div>';
		}
	}
}
