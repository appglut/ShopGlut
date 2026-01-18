<?php
namespace Shopglut\enhancements\ProductSwatches;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Template-based Frontend Renderer
 * Handles rendering of custom swatches by delegating to individual templates
 */
class TemplateFrontend {

	private static $instance = null;

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Hook into WooCommerce to replace default variation dropdowns
		add_filter('woocommerce_dropdown_variation_attribute_options_html', [$this, 'render_template_swatches'], 999, 2);

		// Enqueue frontend scripts for swatches functionality
		add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
	}

	/**
	 * Render swatches using template-specific methods
	 *
	 * @param string $html Original dropdown HTML
	 * @param array $args Arguments passed by WooCommerce
	 * @return string Modified HTML with custom swatches
	 */
	public function render_template_swatches($html, $args) {
		global $product;

		// Only proceed on product pages
		if (!is_product()) {
			return $html;
		}

		// Check if product is variable
		if (!$product || !$product->is_type('variable')) {
			return $html;
		}

		// Get the current attribute name from args
		$attribute_name = isset($args['attribute']) ? $args['attribute'] : '';

		// Get the active layout for this specific attribute
		$layout_data = $this->get_active_layout_for_product($product->get_id(), $attribute_name);

		if (!$layout_data) {
			return $html; // No layout applied, return default
		}

		$layout_id = $layout_data['id'];
		$layout_template = $layout_data['template'];
		$layout_settings = $layout_data['settings'];

		// Get template-specific settings
		$template_settings = $this->get_template_settings($layout_settings, $layout_template);

		// Check if overwrite is enabled
		$enable_overwrite = isset($template_settings['enable_variation_overwrite'])
			? filter_var($template_settings['enable_variation_overwrite'], FILTER_VALIDATE_BOOLEAN)
			: true;

		if (!$enable_overwrite) {
			return $html; // Overwrite disabled, return default WooCommerce HTML
		}

		// Ensure options are included in args
		if (!isset($args['options']) || empty($args['options'])) {
			$args['options'] = $this->get_attribute_options($product, $args['attribute']);
		}

		// Render swatches using template-specific method
		return $this->render_with_template($args, $product, $layout_id, $layout_template, $template_settings);
	}

	/**
	 * Get active layout for a product attribute
	 */
	private function get_active_layout_for_product($product_id, $attribute_name) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_product_swatches_layouts';

		// Get all published layouts
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$layouts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT id, layout_name, layout_template, layout_settings FROM `{$table_name}` WHERE layout_status = %d",
				1
			),
			ARRAY_A
		);

		if (empty($layouts)) {
			return null;
		}

		// Strip 'attribute_' prefix to get the taxonomy name
		$taxonomy = $attribute_name;
		if (strpos($taxonomy, 'attribute_') === 0) {
			$taxonomy = substr($taxonomy, 10);
		}

		// Find layout that has this attribute assigned
		foreach ($layouts as $layout) {
			$settings = maybe_unserialize($layout['layout_settings']);

			if (!isset($settings['attribute_assignments'])) {
				continue;
			}

			$assignments = $settings['attribute_assignments'];

			// Check if this attribute is assigned to this layout
			foreach ($assignments as $assignment) {
				if (isset($assignment['attribute']) && $assignment['attribute'] === $taxonomy) {
					return array(
						'id' => $layout['id'],
						'template' => $layout['layout_template'],
						'settings' => $settings
					);
				}
			}
		}

		return null;
	}

	/**
	 * Get template-specific settings
	 */
	private function get_template_settings($layout_settings, $template) {
		// Try new format first
		$new_key = 'shopg_productswatches_settings_' . $template;
		if (isset($layout_settings[$new_key])) {
			return $layout_settings[$new_key];
		}

		// Try old format
		$old_key = 'shopg_product_swatches_settings_' . $template;
		if (isset($layout_settings[$old_key])) {
			return $layout_settings[$old_key];
		}

		// Fallback - find any shopg_product swatches settings
		foreach ($layout_settings as $key => $value) {
			if (strpos($key, 'shopg_product') !== false && strpos($key, 'swatches') !== false && strpos($key, 'settings') !== false) {
				return $value;
			}
		}

		return array();
	}

	/**
	 * Get attribute options for a product
	 */
	private function get_attribute_options($product, $attribute_name) {
		$options = array();

		// Strip 'attribute_' prefix to get the taxonomy name
		$taxonomy = $attribute_name;
		if (strpos($taxonomy, 'attribute_') === 0) {
			$taxonomy = substr($taxonomy, 10);
		}

		// Get the attribute object
		$attributes = $product->get_attributes();

		if (empty($attributes)) {
			return $options;
		}

		foreach ($attributes as $attr_key => $attribute) {
			if ($attribute->is_taxonomy()) {
				$attr_taxonomy = $attribute->get_taxonomy();

				// Check for match with or without attribute_ prefix
				$matches = (
					$attr_key === $attribute_name ||
					$attr_key === $taxonomy ||
					$attr_taxonomy === $taxonomy ||
					wc_variation_attribute_name($attr_taxonomy) === wc_variation_attribute_name($attribute_name)
				);

				if ($matches && isset($attribute->get_terms()[0])) {
					foreach ($attribute->get_terms() as $term) {
						$options[] = $term->slug;
					}
					break;
				}
			}
		}

		return $options;
	}

	/**
	 * Render swatches using the specific template class
	 */
	private function render_with_template($args, $product, $layout_id, $template, $settings) {
		// Get the template markup class
		$template_class = $this->get_template_markup_class($template);

		if (!$template_class || !class_exists($template_class)) {
			return $this->render_default_dropdown($args);
		}

		// Create template instance and call its rendering method
		try {
			$template_instance = new $template_class();

			// Check if template has a render_frontend_swatches method
			if (method_exists($template_instance, 'render_frontend_swatches')) {
				return $template_instance->render_frontend_swatches($args, $product, $layout_id, $settings);
			}

			// Fallback to render_live_swatches
			if (method_exists($template_instance, 'render_live_swatches')) {
				ob_start();
				$template_instance->render_live_swatches($layout_id);
				$output = ob_get_clean();

				// If it's just a placeholder, render default dropdown
				if (strpos($output, '<!-- Swatches rendered via WooCommerce hooks -->') !== false) {
					return $this->render_default_dropdown($args);
				}

				return $output;
			}
		} catch (\Exception $e) {
			// On any error, return default dropdown
		}

		return $this->render_default_dropdown($args);
	}

	/**
	 * Get template markup class name
	 */
	private function get_template_markup_class($template) {
		// Try pro templates first (generic naming)
		$pro_class = "\\Shopglut\\enhancements\\ProductSwatches\\templates\\{$template}\\templateMarkup";
		if (class_exists($pro_class)) {
			return $pro_class;
		}

		// Try regular templates (template-specific naming)
		$regular_class = "\\Shopglut\\enhancements\\ProductSwatches\\templates\\{$template}\\{$template}Markup";
		if (class_exists($regular_class)) {
			return $regular_class;
		}

		return null;
	}

	/**
	 * Render default dropdown as fallback
	 */
	private function render_default_dropdown($args) {
		$html = '<select class="shopglut-swatch-dropdown" name="' . esc_attr($args['attribute']) . '" data-attribute="' . esc_attr($args['attribute']) . '">';
		$html .= '<option value="">' . esc_html__('Choose an option', 'shopglut') . '</option>';

		foreach (($args['options'] ?? array()) as $option_slug) {
			$term = get_term_by('slug', $option_slug, $args['attribute']);
			$term_name = $term ? $term->name : $option_slug;
			$html .= '<option value="' . esc_attr($option_slug) . '">' . esc_html($term_name) . '</option>';
		}

		$html .= '</select>';
		return $html;
	}

	/**
	 * Enqueue frontend scripts for swatches functionality
	 */
	public function enqueue_frontend_scripts() {
		// Only load on product pages
		if (!is_product()) {
			return;
		}

		global $product;

		if (!$product || !$product->is_type('variable')) {
			return;
		}

		$plugin_url = plugin_dir_url(dirname(__FILE__));

		// Enqueue swatches frontend CSS
		if (file_exists(dirname(__FILE__) . '/assets/swatches-frontend.css')) {
			wp_enqueue_style(
				'shopglut-swatches-frontend',
				$plugin_url . 'assets/swatches-frontend.css',
				[],
				filemtime(dirname(__FILE__) . '/assets/swatches-frontend.css')
			);
		}

		// Enqueue swatches frontend JS
		if (file_exists(dirname(__FILE__) . '/assets/swatches-frontend.js')) {
			wp_enqueue_script(
				'shopglut-swatches-frontend',
				$plugin_url . 'assets/swatches-frontend.js',
				['jquery'],
				filemtime(dirname(__FILE__) . '/assets/swatches-frontend.js'),
				true
			);
		}
	}
}
