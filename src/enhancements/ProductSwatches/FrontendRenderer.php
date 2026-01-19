<?php
namespace Shopglut\enhancements\ProductSwatches;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Frontend Renderer for Product Swatches
 * Simple implementation to override WooCommerce default attribute display
 */
class FrontendRenderer {

	private static $instance = null;
	private $attribute_layouts = null;
	private $has_custom_swatches = false;
	private $swatches_count = 0;
	private $total_attributes = 0;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Hook into WooCommerce to replace default variation dropdowns
		add_filter('woocommerce_dropdown_variation_attribute_options_html', [$this, 'render_custom_swatches'], 9999, 2);

		// Enqueue frontend scripts/styles
		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

		// Add global styles to head instead of inline in form
		add_action('wp_head', [$this, 'output_global_styles']);

		// Override WooCommerce clear button with custom one
		add_filter('woocommerce_reset_variations_link', [$this, 'custom_clear_button']);

		// Output custom price element and hide WooCommerce's default variation price
		add_action('woocommerce_single_variation', [$this, 'output_custom_variation_price'], 5);

		// Apply variations form styling (minimal inline styles only)
		add_action('woocommerce_before_variations_form', [$this, 'apply_form_styling']);
	}

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render custom swatches for variable products
	 *
	 * @param string $html Original dropdown HTML
	 * @param array $args Arguments from WooCommerce
	 * @return string Modified HTML
	 */
	public function render_custom_swatches($html, $args) {
		global $product;

		// Only proceed on frontend for variable products
		if (is_admin() || !$product || !$product->is_type('variable')) {
			return $html;
		}

		// Initialize attribute count on first call
		if ($this->total_attributes === 0) {
			$attributes = $product->get_attributes();
			foreach ($attributes as $attr) {
				if ($attr->is_taxonomy() && $attr->get_variation()) {
					$this->total_attributes++;
				}
			}
		}

		// Get the attribute name from args
		$attribute_name = isset($args['attribute']) ? $args['attribute'] : '';

		if (empty($attribute_name)) {
			
			return $html;
		}

		// Get layout for this attribute
		$layout = $this->get_layout_for_attribute($attribute_name);

		if (!$layout) {
			// No layout assigned, return default WooCommerce HTML (including label)
			return $html;
		}

		// Mark that we have custom swatches
		$this->has_custom_swatches = true;
		$this->swatches_count++;

		// Get options if not provided
		if (!isset($args['options']) || empty($args['options'])) {
			$args['options'] = $this->get_attribute_options($product, $attribute_name);
			if (empty($args['options'])) {
				return $html;
			}
		}

		// Render based on template
		$template = $layout['layout_template'];
		$settings = $layout['settings'];

		ob_start();

		if ($template === 'template1') {
			$this->render_template1($args, $settings);
		} elseif ($template === 'template2') {
			$this->render_template2($args, $settings);
		} else {
			// Fallback to default
			echo $html;
		}

		return ob_get_clean();
	}

	/**
	 * Get layout assigned to an attribute
	 *
	 * @param string $attribute_name Attribute name (e.g., 'pa_color')
	 * @return array|null Layout data or null if not found
	 */
	private function get_layout_for_attribute($attribute_name) {
		// Cache layouts
		if ($this->attribute_layouts === null) {
			$this->attribute_layouts = $this->get_all_attribute_layouts();
		}

		// Normalize attribute name
		$normalized = $attribute_name;
		if (strpos($normalized, 'attribute_') === 0) {
			$normalized = substr($normalized, 10);
		}
		if (strpos($normalized, 'pa_') !== 0) {
			$normalized = 'pa_' . $normalized;
		}

		// Find matching layout
		foreach ($this->attribute_layouts as $layout) {
			if (in_array($normalized, $layout['assigned_attributes'])) {
				return $layout;
			}
		}

		return null;
	}

	/**
	 * Get all attribute-based layouts from database
	 *
	 * @return array Array of layouts
	 */
	private function get_all_attribute_layouts() {
		global $wpdb;

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$results = $wpdb->get_results(
			"SELECT id, layout_name, layout_template, layout_settings, assigned_attributes
			FROM `{$table_name}`
			WHERE assignment_type = 'attribute'
			AND assigned_attributes IS NOT NULL
			AND assigned_attributes != ''",
			ARRAY_A
		);

		$layouts = array();

		foreach ($results as $row) {
			$assigned = json_decode($row['assigned_attributes'], true);

			if (!is_array($assigned)) {
				continue;
			}

			// Normalize assigned attributes
			$normalized_assigned = array();
			foreach ($assigned as $attr) {
				$normalized = $attr;
				if (strpos($normalized, 'attribute_') === 0) {
					$normalized = substr($normalized, 10);
				}
				if (strpos($normalized, 'pa_') !== 0) {
					$normalized = 'pa_' . $normalized;
				}
				$normalized_assigned[] = $normalized;
			}

			$settings = maybe_unserialize($row['layout_settings']);
			$template_settings = $this->extract_template_settings($settings, $row['layout_template']);

			$layouts[] = array(
				'id' => $row['id'],
				'layout_name' => $row['layout_name'],
				'layout_template' => $row['layout_template'],
				'assigned_attributes' => $normalized_assigned,
				'settings' => $template_settings
			);
		}

		return $layouts;
	}

	/**
	 * Extract template settings from layout settings
	 *
	 * @param array $layout_settings Full layout settings
	 * @param string $template Template name
	 * @return array Template settings
	 */
	private function extract_template_settings($layout_settings, $template) {
		if (!is_array($layout_settings)) {
			return array();
		}

		// Try various key formats
		$keys = array(
			'shopg_product_swatches_settings_' . $template,
			'shopg_productswatches_settings_' . $template,
		);

		foreach ($keys as $key) {
			if (isset($layout_settings[$key])) {
				$settings = $layout_settings[$key];
				// Check for nested product-swatches-settings
				if (isset($settings['product-swatches-settings'])) {
					return $settings['product-swatches-settings'];
				}
				return $settings;
			}
		}

		return array();
	}

	/**
	 * Get attribute options for a product
	 *
	 * @param \WC_Product_Variable $product Product object
	 * @param string $attribute_name Attribute name
	 * @return array Array of term slugs
	 */
	private function get_attribute_options($product, $attribute_name) {
		$options = array();

		// Strip 'attribute_' prefix
		$taxonomy = $attribute_name;
		if (strpos($taxonomy, 'attribute_') === 0) {
			$taxonomy = substr($taxonomy, 10);
		}

		$attributes = $product->get_attributes();

		if (empty($attributes)) {
			return $options;
		}

		foreach ($attributes as $attr_key => $attribute) {
			if ($attribute->is_taxonomy()) {
				$attr_taxonomy = $attribute->get_taxonomy();

				if ($attr_key === $attribute_name ||
				    $attr_key === $taxonomy ||
				    $attr_taxonomy === $attribute_name ||
				    $attr_taxonomy === $taxonomy) {

					$terms = get_terms(array(
						'taxonomy' => $attr_taxonomy,
						'hide_empty' => false,
					));

					foreach ($terms as $term) {
						$options[] = $term->slug;
					}
					break;
				}
			}
		}

		return $options;
	}

	/**
	 * Render Template1 - Dropdown Style
	 *
	 * @param array $args WooCommerce args
	 * @param array $settings Template settings
	 */
	private function render_template1($args, $settings) {
		// Extract settings with defaults
		// Always show dropdown and label for now - visibility settings can be controlled via position "none"
		$show_dropdown = true;
		$show_label = true;

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

		$dropdown_padding = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_padding'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_padding']
			: array('top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14', 'unit' => 'px');

		$dropdown_width = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_width'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_width']
			: 100;

		$dropdown_min_height = isset($settings['swatch_dropdown_container_section']['swatch_dropdown_min_height'])
			? $settings['swatch_dropdown_container_section']['swatch_dropdown_min_height']
			: 40;

		$text_color = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_text_color'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_text_color']
			: '#374151';

		$font_size = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_font_size'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_font_size']
			: 14;

		$font_weight = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_font_weight'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_font_weight']
			: '400';

		$line_height = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_line_height'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_line_height']
			: 14;

		$font_family = isset($settings['swatch_dropdown_typography_section']['swatch_dropdown_font_family'])
			? $settings['swatch_dropdown_typography_section']['swatch_dropdown_font_family']
			: 'inherit';

		$label_color = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_color'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_color']
			: '#374151';

		$label_font_size = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_font_size'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_font_size']
			: 14;

		$label_font_weight = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_font_weight'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_font_weight']
			: '600';

		$label_position = isset($settings['swatch_attribute_label_section']['swatch_attribute_label_position'])
			? $settings['swatch_attribute_label_section']['swatch_attribute_label_position']
			: 'inline';

		// Get attribute label
		$attribute_label = wc_attribute_label($args['attribute']);
		$select_name = wc_variation_attribute_name($args['attribute']);

		echo '<div class="shopglut-swatches-wrapper shopglut-template1">';

		// Render label if enabled
		if ($show_label && !empty($attribute_label)) {
			// Enforce minimum font size for label
			$label_font_size = max(intval($label_font_size), 12); // Min 12px

			$label_style = sprintf(
				'color:%s;font-size:%dpx;font-weight:%s;%s',
				esc_attr($label_color),
				intval($label_font_size),
				esc_attr($label_font_weight),
				$label_position === 'stacked' ? 'display:block;margin-bottom:8px;' : 'display:inline-block;margin-right:10px;vertical-align:middle;'
			);
			echo '<label class="shopglut-attribute-label" style="' . esc_attr($label_style) . '">' . esc_html($attribute_label) . '</label>';
			if ($label_position === 'stacked') {
				echo '<br>';
			}
		}

		// Render dropdown if enabled
		if ($show_dropdown) {
			$padding_top = isset($dropdown_padding['top']) ? $dropdown_padding['top'] : '10';
			$padding_right = isset($dropdown_padding['right']) ? $dropdown_padding['right'] : '14';
			$padding_bottom = isset($dropdown_padding['bottom']) ? $dropdown_padding['bottom'] : '10';
			$padding_left = isset($dropdown_padding['left']) ? $dropdown_padding['left'] : '14';

			// Enforce minimum values to prevent invisible/unusable elements
			$font_size = max(intval($font_size), 12); // Min 12px
			$line_height = max(intval($line_height), 14); // Min 1.4em
			$dropdown_min_height = max(intval($dropdown_min_height), 40); // Min 40px

			$select_style = sprintf(
				'background-color:%s;border:%dpx solid %s;border-radius:%dpx;color:%s;font-size:%dpx;font-weight:%s;font-family:%s;line-height:%.1fem;padding:%spx %spx;min-height:%spx;width:%s%%;',
				esc_attr($dropdown_bg),
				intval($dropdown_border_width),
				esc_attr($dropdown_border),
				intval($dropdown_radius),
				esc_attr($text_color),
				intval($font_size),
				esc_attr($font_weight),
				esc_attr($font_family),
				intval($line_height) / 10,
				intval($padding_top),
				intval($padding_right),
				intval($padding_bottom),
				intval($padding_left),
				intval($dropdown_min_height),
				intval($dropdown_width)
			);

			echo '<select class="shopglut-swatch-dropdown" name="' . esc_attr($select_name) . '" data-attribute="' . esc_attr($select_name) . '" style="' . esc_attr($select_style) . '">';
			echo '<option value="">' . esc_html__('Choose an option', 'shopglut') . '</option>';

			foreach ($args['options'] as $option_slug) {
				$term = get_term_by('slug', $option_slug, $args['attribute']);
				$option_name = $term ? $term->name : ucwords(str_replace('-', ' ', $option_slug));
				echo '<option value="' . esc_attr($option_slug) . '">' . esc_html($option_name) . '</option>';
			}

			echo '</select>';
		}

		echo '</div>';
	}

	/**
	 * Render Template2 - Button Style
	 *
	 * @param array $args WooCommerce args
	 * @param array $settings Template settings
	 */
	private function render_template2($args, $settings) {
		// Extract settings with defaults
		$button_bg = isset($settings['button_default_section']['button_default_background'])
			? $settings['button_default_section']['button_default_background']
			: '#ffffff';

		$button_text_color = isset($settings['button_default_section']['button_default_text_color'])
			? $settings['button_default_section']['button_default_text_color']
			: '#2d3748';

		$button_border = isset($settings['button_default_section']['button_default_border_color'])
			? $settings['button_default_section']['button_default_border_color']
			: '#d1d5db';

		$button_border_width = isset($settings['button_default_section']['button_default_border_width'])
			? $settings['button_default_section']['button_default_border_width']
			: 2;

		$button_radius = isset($settings['button_default_section']['button_default_border_radius'])
			? $settings['button_default_section']['button_default_border_radius']
			: 8;

		$button_padding_x = isset($settings['button_default_section']['button_default_padding_x'])
			? $settings['button_default_section']['button_default_padding_x']
			: 16;

		$button_padding_y = isset($settings['button_default_section']['button_default_padding_y'])
			? $settings['button_default_section']['button_default_padding_y']
			: 10;

		$hover_bg = isset($settings['button_hover_section']['button_hover_background'])
			? $settings['button_hover_section']['button_hover_background']
			: '#f3f4f6';

		$hover_text = isset($settings['button_hover_section']['button_hover_text_color'])
			? $settings['button_hover_section']['button_hover_text_color']
			: '#1f2937';

		$hover_border = isset($settings['button_hover_section']['button_hover_border_color'])
			? $settings['button_hover_section']['button_hover_border_color']
			: '#9ca3af';

		$active_bg = isset($settings['button_active_section']['button_active_background'])
			? $settings['button_active_section']['button_active_background']
			: '#2271b1';

		$active_text = isset($settings['button_active_section']['button_active_text_color'])
			? $settings['button_active_section']['button_active_text_color']
			: '#ffffff';

		$active_border = isset($settings['button_active_section']['button_active_border_color'])
			? $settings['button_active_section']['button_active_border_color']
			: '#2271b1';

		$label_color = isset($settings['attribute_label_section']['attribute_label_color'])
			? $settings['attribute_label_section']['attribute_label_color']
			: '#374151';

		$label_font_size = isset($settings['attribute_label_section']['attribute_label_font_size'])
			? $settings['attribute_label_section']['attribute_label_font_size']
			: 14;

		$columns = isset($settings['layout_settings_section']['swatch_columns'])
			? $settings['layout_settings_section']['swatch_columns']
			: 4;

		$gap = isset($settings['layout_settings_section']['swatch_gap'])
			? $settings['layout_settings_section']['swatch_gap']
			: 10;

		$attribute_label = wc_attribute_label($args['attribute']);
		$select_name = wc_variation_attribute_name($args['attribute']);

		echo '<div class="shopglut-swatches-wrapper shopglut-template2">';

		// Render label
		if (!empty($attribute_label)) {
			echo '<label class="shopglut-attribute-label" style="color:' . esc_attr($label_color) . ';font-size:' . intval($label_font_size) . 'px;font-weight:600;display:block;margin-bottom:12px;">' . esc_html($attribute_label) . '</label>';
		}

		// Render buttons container
		$container_style = sprintf(
			'display:grid;grid-template-columns:repeat(%d,1fr);gap:%dpx;',
			intval($columns),
			intval($gap)
		);

		$button_style = sprintf(
			'background-color:%s;color:%s;border:%dpx solid %s;border-radius:%dpx;padding:%dpx %dpx;cursor:pointer;font-size:14px;font-weight:500;transition:all 0.2s ease;',
			esc_attr($button_bg),
			esc_attr($button_text_color),
			intval($button_border_width),
			esc_attr($button_border),
			intval($button_radius),
			intval($button_padding_y),
			intval($button_padding_x)
		);

		echo '<div class="shopglut-buttons-container" style="' . esc_attr($container_style) . '">';

		// Output hidden select for WooCommerce compatibility
		echo '<select class="shopglut-hidden-select" name="' . esc_attr($select_name) . '" data-attribute="' . esc_attr($select_name) . '" style="display:none;">';
		echo '<option value="">' . esc_html__('Choose an Option', 'shopglut') . '</option>';
		foreach ($args['options'] as $option_slug) {
			$term = get_term_by('slug', $option_slug, $args['attribute']);
			$option_name = $term ? $term->name : ucwords(str_replace('-', ' ', $option_slug));
			echo '<option value="' . esc_attr($option_slug) . '">' . esc_html($option_name) . '</option>';
		}
		echo '</select>';

		foreach ($args['options'] as $option_slug) {
			$term = get_term_by('slug', $option_slug, $args['attribute']);
			$option_name = $term ? $term->name : ucwords(str_replace('-', ' ', $option_slug));

			echo '<button type="button" class="shopglut-swatch-button" data-value="' . esc_attr($option_slug) . '" data-attribute="' . esc_attr($select_name) . '" style="' . esc_attr($button_style) . '">';
			echo esc_html($option_name);
			echo '</button>';
		}

		echo '</div>'; // End buttons container
		echo '</div>'; // End wrapper

		// Output inline styles for hover/active states
		?>
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
		<?php
	}

	/**
	 * Output global styles to wp_head instead of inline
	 */
	public function output_global_styles() {
		if (!is_product()) {
			return;
		}

		global $product;
		if (!$product || !$product->is_type('variable')) {
			return;
		}

		$global_settings = get_option('shopglut_global_swatches_settings', array());
		$variations_form = isset($global_settings['variations_form']) ? $global_settings['variations_form'] : array();

		// Pre-check if any attributes have custom layouts
		$has_custom_layouts = false;
		$attributes = $product->get_attributes();
		foreach ($attributes as $attr_key => $attribute) {
			if ($attribute->is_taxonomy() && $attribute->get_variation()) {
				if ($this->get_layout_for_attribute('attribute_' . $attr_key)) {
					$has_custom_layouts = true;
					break;
				}
			}
		}

		// Check if custom price display is enabled
		$price_settings = isset($global_settings['price_display']) ? $global_settings['price_display'] : array();
		$price_enabled = isset($price_settings['enable']) ? filter_var($price_settings['enable'], FILTER_VALIDATE_BOOLEAN) : false;

		// Check if clear button is enabled
		$clear_settings = isset($global_settings['clear_button']) ? $global_settings['clear_button'] : array();
		$clear_enabled = isset($clear_settings['enable']) ? filter_var($clear_settings['enable'], FILTER_VALIDATE_BOOLEAN) : false;

		// Get actions position settings
		$actions_position = isset($global_settings['actions_position']) ? $global_settings['actions_position'] : array();
		$position_type = isset($actions_position['position_type']) ? $actions_position['position_type'] : 'same_line';
		$alignment = isset($actions_position['alignment']) ? $actions_position['alignment'] : 'left';
		$items_spacing = isset($actions_position['items_spacing']) ? intval($actions_position['items_spacing']) : 15;

		// Get hover colors for clear button
		$hover_color = isset($clear_settings['hover_color']) ? $clear_settings['hover_color'] : '#135e96';
		$hover_background = isset($clear_settings['hover_background']) ? $clear_settings['hover_background'] : 'rgba(34, 113, 177, 0.05)';
		$transform_scale = isset($clear_settings['transform_scale']) ? intval($clear_settings['transform_scale']) : 100;

		// Only output if needed
		if (!$has_custom_layouts && empty($variations_form) && !$price_enabled && !$clear_enabled) {
			return;
		}

		?>
		<!-- ShopGlut Product Swatches Global Styles -->
		<style>
			/* Hide clear button by default - JavaScript will remove this class when there are selections */
			.shopglut-reset-hidden {
				display: none !important;
			}

			/* Clear button hover effect */
			.shopglut-reset-variations:hover {
				color: <?php echo esc_attr($hover_color); ?> !important;
				background-color: <?php echo esc_attr($hover_background); ?> !important;
				<?php if ($transform_scale > 100): ?>
				transform: scale(<?php echo floatval($transform_scale) / 100; ?>);
				<?php endif; ?>
			}

			<?php if ($has_custom_layouts): ?>
				/* Hide WooCommerce's default labels when custom swatches are active */
				.variations .label,
				.variations th.label {
					display: none !important;
				}
				/* Remove table row borders for cleaner look */
				.variations tr {
					border: none !important;
				}
			<?php endif; ?>

			<?php if ($price_enabled): ?>
				/* Hide WooCommerce's default variation price when custom price is enabled */
				.woocommerce-variation-price {
					display: none !important;
				}
			<?php endif; ?>

			/* Actions container styling based on position settings */
			.shopglut-actions-container {
				display: flex;
				align-items: center;
				gap: <?php echo intval($items_spacing); ?>px;
				<?php if ($position_type === 'new_line'): ?>
					flex-direction: row;
					width: 100%;
					margin-top: 15px;
					<?php if ($alignment === 'center'): ?>
						justify-content: center;
					<?php elseif ($alignment === 'right'): ?>
						justify-content: flex-end;
					<?php else: ?>
						justify-content: flex-start;
					<?php endif; ?>
				<?php endif; ?>
			}

			/* Price position adjustment */
			<?php if ($price_enabled && isset($price_settings['position']) && $price_settings['position'] === 'after_clear'): ?>
				.shopglut-actions-container {
					flex-direction: row-reverse;
				}
			<?php endif; ?>

			<?php if (!empty($variations_form)): ?>
				/* Variations Form Styling */
				.variations_form.cart {
					<?php if (isset($variations_form['margin_bottom']) && $variations_form['margin_bottom'] !== ''): ?>
						margin-bottom: <?php echo intval($variations_form['margin_bottom']); ?>px !important;
					<?php endif; ?>
				}

				.variations .woocommerce-variation .label {
					display: none !important;
				}

				<?php if (isset($variations_form['remove_borders']) && $variations_form['remove_borders']): ?>
					.variations td,
					.variations th,
					.variations {
						border: none !important;
					}
				<?php endif; ?>

				<?php
				$padding_top = isset($variations_form['padding_top']) ? $variations_form['padding_top'] : '';
				$padding_right = isset($variations_form['padding_right']) ? $variations_form['padding_right'] : '';
				$padding_bottom = isset($variations_form['padding_bottom']) ? $variations_form['padding_bottom'] : '';
				$padding_left = isset($variations_form['padding_left']) ? $variations_form['padding_left'] : '';

				if ($padding_top !== '' || $padding_right !== '' || $padding_bottom !== '' || $padding_left !== ''):
				?>
					.variations td.value {
						<?php if ($padding_top !== ''): ?>
							padding-top: <?php echo intval($padding_top); ?>px !important;
						<?php endif; ?>
						<?php if ($padding_right !== ''): ?>
							padding-right: <?php echo intval($padding_right); ?>px !important;
						<?php endif; ?>
						<?php if ($padding_bottom !== ''): ?>
							padding-bottom: <?php echo intval($padding_bottom); ?>px !important;
						<?php endif; ?>
						<?php if ($padding_left !== ''): ?>
							padding-left: <?php echo intval($padding_left); ?>px !important;
						<?php endif; ?>
					}
				<?php endif; ?>

				<?php if (isset($variations_form['row_height']) && $variations_form['row_height'] !== ''): ?>
					.variations tr {
						line-height: <?php echo floatval($variations_form['row_height']); ?>em !important;
					}
				<?php endif; ?>

				<?php if (isset($variations_form['vertical_align']) && $variations_form['vertical_align'] !== 'default'): ?>
					.variations td {
						vertical-align: <?php echo esc_attr($variations_form['vertical_align']); ?> !important;
					}
				<?php endif; ?>

				<?php if (isset($variations_form['cell_padding_bottom']) && $variations_form['cell_padding_bottom'] !== ''): ?>
					/* Override WooCommerce default table cell padding-bottom */
					.woocommerce table.variations tr td,
					.woocommerce table.variations tr th {
						padding-bottom: <?php echo intval($variations_form['cell_padding_bottom']); ?>px !important;
					}
				<?php endif; ?>

				<?php if (isset($variations_form['form_margin_bottom']) && $variations_form['form_margin_bottom'] !== ''): ?>
					/* Override WooCommerce default variations form margin-bottom */
					.woocommerce div.product form.cart .variations {
						margin-bottom: <?php echo intval($variations_form['form_margin_bottom']); ?>px !important;
					}
				<?php endif; ?>
			<?php endif; ?>
		</style>
		<?php
	}

	/**
	 * Apply variations form styling from global settings
	 */
	public function apply_form_styling() {
		// Minimal inline styles - most styles are now in wp_head
		// This hook is kept for potential future use if needed
	}

	/**
	 * Custom clear button - overrides WooCommerce default reset link
	 *
	 * @param string $html Original HTML
	 * @return string Modified HTML
	 */
	public function custom_clear_button($html) {
		// Get global settings
		$global_settings = get_option('shopglut_global_swatches_settings', array());
		$clear_settings = isset($global_settings['clear_button']) ? $global_settings['clear_button'] : array();

		// Check if enabled
		$clear_enabled = isset($clear_settings['enable']) ? filter_var($clear_settings['enable'], FILTER_VALIDATE_BOOLEAN) : false;

		if (!$clear_enabled) {
			// Return empty to hide default
			return '';
		}

		// Get settings values with defaults
		$clear_text = isset($clear_settings['text']) ? $clear_settings['text'] : 'Clear';

		// Typography
		$clear_color = isset($clear_settings['color']) ? $clear_settings['color'] : '#2271b1';
		$clear_font_size = isset($clear_settings['font_size']) ? intval($clear_settings['font_size']) : 14;
		$clear_font_weight = isset($clear_settings['font_weight']) ? $clear_settings['font_weight'] : '500';
		$clear_font_family = isset($clear_settings['font_family']) ? $clear_settings['font_family'] : 'inherit';
		$text_transform = isset($clear_settings['text_transform']) ? $clear_settings['text_transform'] : 'none';
		$text_decoration = isset($clear_settings['text_decoration']) ? $clear_settings['text_decoration'] : 'underline';
		$letter_spacing = isset($clear_settings['letter_spacing']) ? floatval($clear_settings['letter_spacing']) : 0;
		$line_height = isset($clear_settings['line_height']) ? intval($clear_settings['line_height']) : 14;
		$text_align = isset($clear_settings['text_align']) ? $clear_settings['text_align'] : 'left';

		// Icon
		$show_icon = isset($clear_settings['show_icon']) ? filter_var($clear_settings['show_icon'], FILTER_VALIDATE_BOOLEAN) : false;
		$icon = isset($clear_settings['icon']) ? $clear_settings['icon'] : 'fas fa-undo';
		$icon_position = isset($clear_settings['icon_position']) ? $clear_settings['icon_position'] : 'left';
		$icon_spacing = isset($clear_settings['icon_spacing']) ? intval($clear_settings['icon_spacing']) : 5;

		// Background & Border
		$background_color = isset($clear_settings['background_color']) ? $clear_settings['background_color'] : 'transparent';
		$border_color = isset($clear_settings['border_color']) ? $clear_settings['border_color'] : 'transparent';
		$border_width = isset($clear_settings['border_width']) ? intval($clear_settings['border_width']) : 0;
		$border_radius = isset($clear_settings['border_radius']) ? intval($clear_settings['border_radius']) : 4;
		$border_style = isset($clear_settings['border_style']) ? $clear_settings['border_style'] : 'solid';

		// Shadow
		$enable_shadow = isset($clear_settings['enable_shadow']) ? filter_var($clear_settings['enable_shadow'], FILTER_VALIDATE_BOOLEAN) : false;
		$shadow_horizontal = isset($clear_settings['shadow_horizontal']) ? intval($clear_settings['shadow_horizontal']) : 0;
		$shadow_vertical = isset($clear_settings['shadow_vertical']) ? intval($clear_settings['shadow_vertical']) : 2;
		$shadow_blur = isset($clear_settings['shadow_blur']) ? intval($clear_settings['shadow_blur']) : 4;
		$shadow_spread = isset($clear_settings['shadow_spread']) ? intval($clear_settings['shadow_spread']) : 0;
		$shadow_color = isset($clear_settings['shadow_color']) ? $clear_settings['shadow_color'] : 'rgba(0, 0, 0, 0.1)';
		$shadow_inset = isset($clear_settings['shadow_inset']) ? filter_var($clear_settings['shadow_inset'], FILTER_VALIDATE_BOOLEAN) : false;

		// Size & Display
		$min_width = isset($clear_settings['min_width']) ? intval($clear_settings['min_width']) : 0;
		$max_width = isset($clear_settings['max_width']) ? intval($clear_settings['max_width']) : 0;
		$min_height = isset($clear_settings['min_height']) ? intval($clear_settings['min_height']) : 0;
		$display = isset($clear_settings['display']) ? $clear_settings['display'] : 'inline-block';

		// Transition & Animation
		$transition_duration = isset($clear_settings['transition_duration']) ? floatval($clear_settings['transition_duration']) : 0.2;
		$transition_timing = isset($clear_settings['transition_timing']) ? $clear_settings['transition_timing'] : 'ease';
		$transform_scale = isset($clear_settings['transform_scale']) ? intval($clear_settings['transform_scale']) : 100;

		// Padding
		$padding = isset($clear_settings['padding']) ? $clear_settings['padding'] : array('top' => '6', 'right' => '12', 'bottom' => '6', 'left' => '12', 'unit' => 'px');
		$padding_top = isset($padding['top']) ? $padding['top'] : '6';
		$padding_right = isset($padding['right']) ? $padding['right'] : '12';
		$padding_bottom = isset($padding['bottom']) ? $padding['bottom'] : '6';
		$padding_left = isset($padding['left']) ? $padding['left'] : '12';

		// Margins
		$margin_left = isset($clear_settings['margin_left']) ? intval($clear_settings['margin_left']) : 15;
		$margin_right = isset($clear_settings['margin_right']) ? intval($clear_settings['margin_right']) : 0;
		$margin_top = isset($clear_settings['margin_top']) ? intval($clear_settings['margin_top']) : 0;
		$margin_bottom = isset($clear_settings['margin_bottom']) ? intval($clear_settings['margin_bottom']) : 0;

		// Build shadow CSS
		$shadow_css = '';
		if ($enable_shadow) {
			$inset = $shadow_inset ? 'inset ' : '';
			$shadow_css = sprintf(
				'box-shadow:%s%dpx %dpx %dpx %dpx %s;',
				$inset,
				$shadow_horizontal,
				$shadow_vertical,
				$shadow_blur,
				$shadow_spread,
				$shadow_color
			);
		}

		// Build size CSS
		$size_css = '';
		if ($min_width > 0) {
			$size_css .= sprintf('min-width:%dpx;', $min_width);
		}
		if ($max_width > 0) {
			$size_css .= sprintf('max-width:%dpx;', $max_width);
		}
		if ($min_height > 0) {
			$size_css .= sprintf('min-height:%dpx;', $min_height);
		}

		// Build inline styles
		$clear_style = sprintf(
			'color:%s;font-size:%dpx;font-weight:%s;font-family:%s;text-transform:%s;text-decoration:%s;letter-spacing:%.2fpx;line-height:%.1fem;text-align:%s;background-color:%s;border:%dpx %s %s;border-radius:%dpx;padding:%dpx %dpx %dpx %dpx;margin:%dpx %dpx %dpx %dpx;cursor:pointer;transition:all %.1fs %s;display:%s;%s%s',
			esc_attr($clear_color),
			intval($clear_font_size),
			esc_attr($clear_font_weight),
			esc_attr($clear_font_family),
			esc_attr($text_transform),
			esc_attr($text_decoration),
			$letter_spacing,
			intval($line_height) / 10,
			esc_attr($text_align),
			esc_attr($background_color),
			intval($border_width),
			esc_attr($border_style),
			esc_attr($border_color),
			intval($border_radius),
			intval($padding_top),
			intval($padding_right),
			intval($padding_bottom),
			intval($padding_left),
			intval($margin_top),
			intval($margin_right),
			intval($margin_bottom),
			intval($margin_left),
			$transition_duration,
			esc_attr($transition_timing),
			esc_attr($display),
			$shadow_css,
			$size_css
		);

		// Generate unique ID for this button
		$button_id = 'shopglut-clear-' . uniqid();

		// Get hover colors for CSS
		$hover_color = isset($clear_settings['hover_color']) ? $clear_settings['hover_color'] : '#135e96';
		$hover_background = isset($clear_settings['hover_background']) ? $clear_settings['hover_background'] : 'rgba(34, 113, 177, 0.05)';
		$hover_scale = $transform_scale > 100 ? sprintf('transform:scale(%.2f);', $transform_scale / 100) : '';

		// Build button content with icon
		$button_content = esc_html($clear_text);
		if ($show_icon) {
			$icon_html = sprintf('<i class="%s" style="margin-%s:%dpx;"></i>', esc_attr($icon), $icon_position === 'left' ? 'right' : 'left', $icon_spacing);
			if ($icon_position === 'left') {
				$button_content = $icon_html . esc_html($clear_text);
			} else {
				$button_content = esc_html($clear_text) . $icon_html;
			}
		}

		// Add CSS class to hide by default - JavaScript will remove this class when there are selections
		$output = '<a href="#" id="' . esc_attr($button_id) . '" class="shopglut-reset-variations shopglut-reset-hidden" style="' . esc_attr($clear_style) . '" data-hover-scale="' . esc_attr($hover_scale) . '">' . $button_content . '</a>';

		return $output;
	}

	/**
	 * Output custom variation price element
	 * This outputs the container for custom price display
	 */
	public function output_custom_variation_price() {
		// Get global settings
		$global_settings = get_option('shopglut_global_swatches_settings', array());
		$price_settings = isset($global_settings['price_display']) ? $global_settings['price_display'] : array();

		// Check if enabled
		$price_enabled = isset($price_settings['enable']) ? filter_var($price_settings['enable'], FILTER_VALIDATE_BOOLEAN) : false;

		if (!$price_enabled) {
			return;
		}

		// Get settings values with defaults
		// Typography
		$price_color = isset($price_settings['color']) ? $price_settings['color'] : '#2271b1';
		$price_font_size = isset($price_settings['font_size']) ? intval($price_settings['font_size']) : 22;
		$price_font_weight = isset($price_settings['font_weight']) ? $price_settings['font_weight'] : '500';
		$price_font_family = isset($price_settings['font_family']) ? $price_settings['font_family'] : 'inherit';
		$line_height = isset($price_settings['line_height']) ? intval($price_settings['line_height']) : 14;
		$text_transform = isset($price_settings['text_transform']) ? $price_settings['text_transform'] : 'none';
		$letter_spacing = isset($price_settings['letter_spacing']) ? floatval($price_settings['letter_spacing']) : 0;
		$text_align = isset($price_settings['text_align']) ? $price_settings['text_align'] : 'left';
		$font_style = isset($price_settings['font_style']) ? $price_settings['font_style'] : 'normal';

		// Background & Border
		$background_color = isset($price_settings['background_color']) ? $price_settings['background_color'] : 'transparent';
		$border_color = isset($price_settings['border_color']) ? $price_settings['border_color'] : 'transparent';
		$border_width = isset($price_settings['border_width']) ? intval($price_settings['border_width']) : 0;
		$border_radius = isset($price_settings['border_radius']) ? intval($price_settings['border_radius']) : 4;
		$border_style = isset($price_settings['border_style']) ? $price_settings['border_style'] : 'solid';

		// Shadow
		$enable_shadow = isset($price_settings['enable_shadow']) ? filter_var($price_settings['enable_shadow'], FILTER_VALIDATE_BOOLEAN) : false;
		$shadow_horizontal = isset($price_settings['shadow_horizontal']) ? intval($price_settings['shadow_horizontal']) : 0;
		$shadow_vertical = isset($price_settings['shadow_vertical']) ? intval($price_settings['shadow_vertical']) : 2;
		$shadow_blur = isset($price_settings['shadow_blur']) ? intval($price_settings['shadow_blur']) : 4;
		$shadow_spread = isset($price_settings['shadow_spread']) ? intval($price_settings['shadow_spread']) : 0;
		$shadow_color = isset($price_settings['shadow_color']) ? $price_settings['shadow_color'] : 'rgba(0, 0, 0, 0.1)';
		$shadow_inset = isset($price_settings['shadow_inset']) ? filter_var($price_settings['shadow_inset'], FILTER_VALIDATE_BOOLEAN) : false;

		// Size & Display
		$min_width = isset($price_settings['min_width']) ? intval($price_settings['min_width']) : 0;
		$max_width = isset($price_settings['max_width']) ? intval($price_settings['max_width']) : 0;
		$min_height = isset($price_settings['min_height']) ? intval($price_settings['min_height']) : 0;
		$display = isset($price_settings['display']) ? $price_settings['display'] : 'inline-block';

		// Padding
		$padding = isset($price_settings['padding']) ? $price_settings['padding'] : array('top' => '4', 'right' => '8', 'bottom' => '4', 'left' => '8', 'unit' => 'px');
		$padding_top = isset($padding['top']) ? $padding['top'] : '4';
		$padding_right = isset($padding['right']) ? $padding['right'] : '8';
		$padding_bottom = isset($padding['bottom']) ? $padding['bottom'] : '4';
		$padding_left = isset($padding['left']) ? $padding['left'] : '8';

		// Margins
		$margin_left = isset($price_settings['margin_left']) ? intval($price_settings['margin_left']) : 0;
		$margin_right = isset($price_settings['margin_right']) ? intval($price_settings['margin_right']) : 15;
		$margin_top = isset($price_settings['margin_top']) ? intval($price_settings['margin_top']) : 12;
		$margin_bottom = isset($price_settings['margin_bottom']) ? intval($price_settings['margin_bottom']) : 0;

		// Build shadow CSS
		$shadow_css = '';
		if ($enable_shadow) {
			$inset = $shadow_inset ? 'inset ' : '';
			$shadow_css = sprintf(
				'box-shadow:%s%dpx %dpx %dpx %dpx %s;',
				$inset,
				$shadow_horizontal,
				$shadow_vertical,
				$shadow_blur,
				$shadow_spread,
				$shadow_color
			);
		}

		// Build size CSS
		$size_css = '';
		if ($min_width > 0) {
			$size_css .= sprintf('min-width:%dpx;', $min_width);
		}
		if ($max_width > 0) {
			$size_css .= sprintf('max-width:%dpx;', $max_width);
		}
		if ($min_height > 0) {
			$size_css .= sprintf('min-height:%dpx;', $min_height);
		}

		// Build inline styles
		$price_style = sprintf(
			'color:%s;font-size:%dpx;font-weight:%s;font-family:%s;line-height:%.1fem;text-transform:%s;letter-spacing:%.2fpx;text-align:%s;font-style:%s;background-color:%s;border:%dpx %s %s;border-radius:%dpx;padding:%dpx %dpx %dpx %dpx;margin:%dpx %dpx %dpx %dpx;display:%s;%s%s',
			esc_attr($price_color),
			intval($price_font_size),
			esc_attr($price_font_weight),
			esc_attr($price_font_family),
			intval($line_height) / 10,
			esc_attr($text_transform),
			$letter_spacing,
			esc_attr($text_align),
			esc_attr($font_style),
			esc_attr($background_color),
			intval($border_width),
			esc_attr($border_style),
			esc_attr($border_color),
			intval($border_radius),
			intval($padding_top),
			intval($padding_right),
			intval($padding_bottom),
			intval($padding_left),
			intval($margin_top),
			intval($margin_right),
			intval($margin_bottom),
			intval($margin_left),
			esc_attr($display),
			$shadow_css,
			$size_css
		);

		// Output empty span that will be populated by JavaScript
		echo '<span class="shopglut-variation-price shopglut-global-price" style="' . esc_attr($price_style) . '"></span>';
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_assets() {
		if (!is_product()) {
			return;
		}

		// Enqueue CSS
		wp_enqueue_style(
			'shopglut-swatches-frontend',
			SHOPGLUT_URL . 'src/enhancements/ProductSwatches/assets/swatches-frontend.css',
			array(),
			SHOPGLUT_VERSION
		);

		// Enqueue JS
		wp_enqueue_script(
			'shopglut-swatches-frontend',
			SHOPGLUT_URL . 'src/enhancements/ProductSwatches/assets/swatches-frontend.js',
			array('jquery'),
			SHOPGLUT_VERSION,
			true
		);

		// Localize script
		wp_localize_script('shopglut-swatches-frontend', 'shopglutSwatchesVars', array(
			'ajax_url' => admin_url('admin-ajax.php'),
		));
	}
}
