<?php
namespace Shopglut\enhancements\ProductQuickView;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class QuickViewDataManage {

	public function __construct() {
		// AJAX handlers for product quickview save and reset
		add_action('wp_ajax_save_shopg_productquickview_layoutdata', [$this, 'save_productquickview_layout_data']);
		add_action('wp_ajax_reset_shopg_productquickview_layout_settings', [$this, 'reset_productquickview_layout_settings']);
		add_action('wp_ajax_shopglut_get_quickview_display_options', [$this, 'shopglut_get_quickview_display_options']);
		add_action('wp_ajax_nopriv_shopglut_get_quickview_display_options', [$this, 'shopglut_get_quickview_display_options']);

		// AJAX handler for getting product data
		add_action('wp_ajax_shopglut_get_quickview_product', [$this, 'get_quickview_product']);
		add_action('wp_ajax_nopriv_shopglut_get_quickview_product', [$this, 'get_quickview_product']);

		// Initialize quickview button display
		add_action('init', [$this, 'init_quickview_display']);
	}

	/**
	 * Initialize quickview button display on frontend
	 */
	public function init_quickview_display() {
		// Enqueue scripts and styles
		add_action('wp_enqueue_scripts', [$this, 'enqueue_quickview_assets']);

		// Add quickview button based on position settings
		add_action('wp', [$this, 'setup_quickview_button_hooks']);

		// Render quickview modal in footer
		add_action('wp_footer', [$this, 'render_quickview_modal_container']);
	}

	/**
	 * Setup hooks for quickview button based on settings
	 */
	public function setup_quickview_button_hooks() {
		if (!$this->should_display_quickview_button()) {
			return;
		}

		$layout_settings = $this->get_active_layout_settings();
		if (!$layout_settings) {
			return;
		}

		// Check if quickview is enabled
		$is_enabled = isset($layout_settings['enable_quickview']) ? $layout_settings['enable_quickview'] : false;
		if (!$is_enabled) {
			return;
		}

		// Get button position
		$button_position = isset($layout_settings['button_position']) ? $layout_settings['button_position'] : 'after_add_to_cart';

		// Hook button display based on position
		switch ($button_position) {
			case 'before_add_to_cart':
				add_action('woocommerce_before_add_to_cart_button', [$this, 'display_quickview_button'], 10);
				add_action('woocommerce_after_shop_loop_item', [$this, 'display_quickview_button_loop'], 5);
				break;
			case 'after_add_to_cart':
				add_action('woocommerce_after_add_to_cart_button', [$this, 'display_quickview_button'], 10);
				add_action('woocommerce_after_shop_loop_item', [$this, 'display_quickview_button_loop'], 15);
				break;
			case 'on_image':
				add_action('woocommerce_before_shop_loop_item_title', [$this, 'display_quickview_button_on_image'], 15);
				break;
			case 'after_product_title':
				// Use filter to inject button before add to cart - works better with most themes
				add_filter('woocommerce_loop_add_to_cart_link', [$this, 'add_quickview_before_add_to_cart'], 10, 2);
				break;
		}
	}

	/**
	 * Enqueue quickview assets
	 */
	public function enqueue_quickview_assets() {
		if (!$this->should_display_quickview_button()) {
			return;
		}

		$layout_settings = $this->get_active_layout_settings();
		if (!$layout_settings) {
			return;
		}

		// Check if quickview is enabled
		$is_enabled = isset($layout_settings['enable_quickview']) ? $layout_settings['enable_quickview'] : false;
		if (!$is_enabled) {
			return;
		}

		// Enqueue quickview styles - Register a handle first
		wp_register_style('shopglut-quickview-inline', false, array(), SHOPGLUT_VERSION);
		wp_enqueue_style('shopglut-quickview-inline');
		wp_add_inline_style('shopglut-quickview-inline', $this->get_quickview_styles());

		// Enqueue quickview script
		wp_enqueue_script(
			'shopglut-quickview-frontend',
			SHOPGLUT_URL . 'src/enhancements/ProductQuickView/assets/quickview-frontend.js',
			array('jquery'),
			SHOPGLUT_VERSION,
			true
		);

		// Localize script with AJAX data
		wp_localize_script('shopglut-quickview-frontend', 'shopglutQuickView', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('shopglut_quickview_nonce'),
		));
	}

	/**
	 * Get quickview inline styles
	 */
	private function get_quickview_styles() {
		$layout_settings = $this->get_active_layout_settings();
		if (!$layout_settings) {
			return '';
		}

		$button_position = isset($layout_settings['button_position']) ? $layout_settings['button_position'] : 'after_add_to_cart';

		$css = "
			.shopglut-quickview-button-wrapper {
				margin: 10px 0;
				display: inline-block;
			}
			.shopglut-quickview-button {
				display: inline-flex;
				align-items: center;
				gap: 6px;
				padding: 10px 20px;
				background: #667eea;
				color: #fff;
				border: none;
				border-radius: 4px;
				cursor: pointer;
				font-size: 14px;
				font-weight: 500;
				transition: all 0.3s ease;
				text-decoration: none;
			}
			.shopglut-quickview-button:hover {
				background: #5a67d8;
				transform: translateY(-1px);
				box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
			}
			.shopglut-quickview-button i {
				font-size: 14px;
			}
		";

		// Add position-specific styles
		if ($button_position === 'on_image') {
			$css .= "
				.shopglut-quickview-button-on-image {
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%);
					opacity: 0;
					visibility: hidden;
					transition: all 0.3s ease;
					z-index: 10;
				}
				.woocommerce ul.products li.product:hover .shopglut-quickview-button-on-image {
					opacity: 1;
					visibility: visible;
				}
				.woocommerce ul.products li.product {
					position: relative;
				}
				.woocommerce ul.products li.product .woocommerce-loop-product__link {
					position: relative;
					display: block;
				}
			";
		}

		// Add loader styles
		$css .= "
			/* QuickView Modal Loader Styles */
			.quickview-loader-container {
				display: flex;
				justify-content: center;
				align-items: center;
				min-height: 200px;
				padding: 40px 20px;
			}

			.quickview-loader {
				text-align: center;
				color: #667eea;
			}

			.quickview-loader-spinner {
				font-size: 48px;
				margin-bottom: 20px;
			}

			.quickview-loader-spinner i {
				animation: fa-spin 1s infinite linear;
			}

			.quickview-loader-text {
				font-size: 16px;
				font-weight: 500;
				color: #666;
				margin-top: 10px;
			}

			/* Ensure modal content is properly sized for loader */
			.quickview-modal-content {
				max-width: 900px;
				width: 90%;
				max-height: 90vh;
				background: #fff;
				border-radius: 8px;
				box-shadow: 0 20px 60px rgba(0,0,0,0.3);
				position: relative;
				z-index: 999999;
				overflow: hidden;
			}

			@media (max-width: 768px) {
				.quickview-loader-container {
					min-height: 150px;
					padding: 30px 15px;
				}

				.quickview-loader-spinner {
					font-size: 36px;
					margin-bottom: 15px;
				}

				.quickview-loader-text {
					font-size: 14px;
				}
			}
		";

		return $css;
	}


	/**
	 * Display quickview button on product loops (shop, category, tag pages)
	 */
	public function display_quickview_button_loop() {
		$this->render_quickview_button(get_the_ID());
	}

	/**
	 * Add quickview button before add to cart button via filter
	 */
	public function add_quickview_before_add_to_cart($add_to_cart_html, $product) {
		ob_start();
		$this->render_quickview_button($product->get_id());
		$quickview_button = ob_get_clean();

		return $quickview_button . $add_to_cart_html;
	}

	/**
	 * Display quickview button on single product page or cart button area
	 */
	public function display_quickview_button() {
		$this->render_quickview_button(get_the_ID());
	}

	/**
	 * Display quickview button on product image (overlay)
	 */
	public function display_quickview_button_on_image() {
		$product_id = get_the_ID();
		$layout_settings = $this->get_active_layout_settings();

		if (!$layout_settings) {
			return;
		}

		$button_text = isset($layout_settings['button_text']) ? $layout_settings['button_text'] : __('Quick View', 'shopglut');
		$show_icon = isset($layout_settings['show_button_icon']) ? $layout_settings['show_button_icon'] : true;
		$button_icon = isset($layout_settings['button_icon']) ? $layout_settings['button_icon'] : 'fas fa-eye';

		echo '<div class="shopglut-quickview-button-on-image">';
		echo '<button class="shopglut-quickview-button" data-product-id="' . esc_attr($product_id) . '">';

		if ($show_icon) {
			echo '<i class="' . esc_attr($button_icon) . '"></i> ';
		}

		echo esc_html($button_text);

		echo '</button>';
		echo '</div>';
	}

	/**
	 * Render quickview button HTML
	 */
	private function render_quickview_button($product_id) {
		$layout_settings = $this->get_active_layout_settings();

		if (!$layout_settings) {
			// Debug: Button not showing because no active layout settings
			if (current_user_can('manage_options')) {
				echo '<!-- QuickView Debug: No active layout settings found -->';
			}
			return;
		}

		$button_text = isset($layout_settings['button_text']) ? $layout_settings['button_text'] : __('Quick View', 'shopglut');
		$show_icon = isset($layout_settings['show_button_icon']) ? $layout_settings['show_button_icon'] : true;
		$button_icon = isset($layout_settings['button_icon']) ? $layout_settings['button_icon'] : 'fas fa-eye';

		// Debug output for administrators
		if (current_user_can('manage_options')) {
			echo '<!-- QuickView Button Rendering: Product ID=' . esc_html($product_id) . ', Position=' . esc_html(isset($layout_settings['button_position']) ? $layout_settings['button_position'] : 'not set') . ' -->';
		}

		echo '<div class="shopglut-quickview-button-wrapper">';
		echo '<button class="shopglut-quickview-button" data-product-id="' . esc_attr($product_id) . '">';

		if ($show_icon) {
			echo '<i class="' . esc_attr($button_icon) . '"></i> ';
		}

		echo esc_html($button_text);

		echo '</button>';
		echo '</div>';
	}

	/**
	 * Render quickview modal container in footer
	 */
	public function render_quickview_modal_container() {
		// Always render modal container for pages with Shop Layout shortcodes
		// This bypasses the location check to ensure the modal loads on pages with shop layout shortcodes
		if ($this->page_has_shop_layout_shortcode()) {
			echo '<div id="shopglut-quickview-modal-container"></div>';
			return;
		}

		if (!$this->should_display_quickview_button()) {
			return;
		}

		$layout_settings = $this->get_active_layout_settings();

		if (!$layout_settings) {
			return;
		}

		// Check if quickview is enabled
		$is_enabled = isset($layout_settings['enable_quickview']) ? $layout_settings['enable_quickview'] : false;

		if (!$is_enabled) {
			return;
		}

		// Render empty container for modal - content will be loaded via AJAX
		echo '<div id="shopglut-quickview-modal-container"></div>';
	}

	/**
	 * Check if current page contains Shop Layout shortcodes
	 */
	private function page_has_shop_layout_shortcode() {
		// Only check on singular posts/pages
		if (!is_singular()) {
			return false;
		}

		global $post;
		if (!$post || !isset($post->post_content)) {
			return false;
		}

		// Check for shop layout shortcode
		return has_shortcode($post->post_content, 'shopg_shop_layout');
	}

	/**
	 * AJAX handler to get product data for quickview
	 */
	public function get_quickview_product() {
		// Verify nonce
		check_ajax_referer('shopglut_quickview_nonce', 'nonce');

		$product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

		if (!$product_id) {
			wp_send_json_error(array('message' => __('Invalid product ID', 'shopglut')));
			return;
		}

		// Get the selected Quick View layout ID from shop layout settings
		$selected_quickview_layout_id = $this->get_selected_quickview_layout_id();

		if ($selected_quickview_layout_id) {
			// error_log('QuickView Debug: Using selected layout ID from shop settings: ' . $selected_quickview_layout_id);
		} else {
			// error_log('QuickView Debug: No Quick View layout selected in shop settings, using fallback logic');
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		if ($selected_quickview_layout_id) {
			// Get the specific selected layout
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$all_layouts = $wpdb->get_results($wpdb->prepare(
				"SELECT id, layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_quickview_layouts` WHERE id = %d",
				$selected_quickview_layout_id
			));
		} else {
			// Fallback: get all layouts and find the first enabled one
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$all_layouts = $wpdb->get_results("SELECT id, layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_quickview_layouts`");
		}

		$layout_id = 0;
		$template_name = 'template1';

		// Debug: Log all layouts found
		// error_log('QuickView Debug: Found ' . count($all_layouts) . ' layouts in database');

		if ($selected_quickview_layout_id && !empty($all_layouts)) {
			// Use the specifically selected layout
			$layout = $all_layouts[0];
			$settings = maybe_unserialize($layout->layout_settings);

			if ($settings !== false && is_array($settings)) {
				$layout_id = $layout->id;
				$template_name = $layout->layout_template;
				// error_log('QuickView Debug: Using selected layout ID ' . $layout_id . ' with template ' . $template_name);
			} else {
				// error_log('QuickView Debug: Selected layout settings failed to unserialize, falling back to auto-detection');
			}
		}

		// If no specific layout selected or selected layout failed, fall back to auto-detection
		if (!$layout_id) {
			foreach ($all_layouts as $layout) {
				$settings = maybe_unserialize($layout->layout_settings);

				// Check if unserialization failed
				if ($settings === false) {
					// error_log('QuickView Debug: Layout ID ' . $layout->id . ' failed to unserialize settings');
					continue;
				}

				// Ensure settings is an array
				if (!is_array($settings)) {
					// error_log('QuickView Debug: Layout ID ' . $layout->id . ' settings is not an array, type: ' . gettype($settings));
					continue;
				}

				// Debug: Log layout structure
				// error_log('QuickView Debug: Layout ID ' . $layout->id . ' settings keys: ' . implode(', ', array_keys($settings)));

				// Try different possible settings structure
				$quickview_settings = null;

				// Check for the expected structure
				if (isset($settings['shopg_product_quickview_settings_template1'])) {
					$quickview_settings = $settings['shopg_product_quickview_settings_template1'];
				}
				// Check for direct template settings
				elseif (isset($settings['template1']) || isset($settings[$layout->layout_template])) {
					$template_key = isset($settings[$layout->layout_template]) ? $layout->layout_template : 'template1';
					$quickview_settings = $settings[$template_key];
				}
				// Check if settings are directly at root level
				elseif (isset($settings['enable_quickview'])) {
					$quickview_settings = $settings;
				}

				if (!$quickview_settings) {
					// error_log('QuickView Debug: No quickview settings found for layout ' . $layout->id);
					continue;
				}

				// Check if QuickView is enabled for this layout
				$is_enabled = isset($quickview_settings['enable_quickview']) ? $quickview_settings['enable_quickview'] : false;

				// error_log('QuickView Debug: Layout ' . $layout->id . ' QuickView enabled: ' . ($is_enabled ? 'true' : 'false'));

				// Check for display location settings
				if (!isset($quickview_settings['display-locations']) || empty($quickview_settings['display-locations'])) {
					// error_log('QuickView Debug: Layout ' . $layout->id . ' no display locations found');
					continue;
				}

				// If QuickView is enabled and has locations configured, use this layout
				if ($is_enabled) {
					$layout_id = $layout->id;
					$template_name = $layout->layout_template;
					// error_log('QuickView Debug: Using auto-detected layout ID ' . $layout_id . ' with template ' . $template_name);
					break;
				}
			}
		}

		// If still no layout found, try to use the first available layout as final fallback
		if (!$layout_id && !empty($all_layouts)) {
			$first_layout = $all_layouts[0];
			$layout_id = $first_layout->id;
			$template_name = $first_layout->layout_template;
			// error_log('QuickView Debug: Using final fallback layout ID ' . $layout_id . ' with template ' . $template_name);
		}

		if (!$layout_id) {
			wp_send_json_error(array('message' => __('No enabled QuickView layout found', 'shopglut')));
			return;
		}

		// Load template files
		$markup_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Markup.php';
		$style_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Style.php';

		if (!file_exists($markup_file) || !file_exists($style_file)) {
			wp_send_json_error(array('message' => __('Template files not found', 'shopglut')));
			return;
		}

		require_once $markup_file;
		require_once $style_file;

		// Get class names
		$markup_class = 'Shopglut\\enhancements\\ProductQuickView\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
		$style_class = 'Shopglut\\enhancements\\ProductQuickView\\templates\\' . $template_name . '\\' . $template_name . 'Style';

		if (!class_exists($markup_class) || !class_exists($style_class)) {
			wp_send_json_error(array('message' => __('Template classes not found', 'shopglut')));
			return;
		}

		// Initialize classes
		$markup_instance = new $markup_class();
		$style_instance = new $style_class();

		// Prepare template data
		$template_data = array(
			'layout_id' => $layout_id,
			'product_id' => $product_id,
		);

		// Start output buffering
		ob_start();

		try {
			// Generate dynamic CSS
			$style_instance->dynamicCss($layout_id);

			// Render the template markup
			$markup_instance->layout_render($template_data);

		} catch (Exception $e) {
			ob_clean();
			wp_send_json_error(array('message' => __('Error rendering template: ', 'shopglut') . $e->getMessage()));
			return;
		}

		// Get the rendered content
		$output = ob_get_clean();

		wp_send_json_success(array(
			'html' => $output,
		));
	}


	/**
	 * Check if quickview button should be displayed on current page
	 */
	private function should_display_quickview_button() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_quickview_layouts`");

		foreach ($all_layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			// Check if unserialization failed
			if ($settings === false) {
				continue;
			}

			// Ensure settings is an array
			if (!is_array($settings)) {
				continue;
			}

			// Try different possible settings structure (same logic as other methods)
			$quickview_settings = null;

			// Check for the expected structure
			if (isset($settings['shopg_product_quickview_settings_template1'])) {
				$quickview_settings = $settings['shopg_product_quickview_settings_template1'];
			}
			// Check for direct template settings
			elseif (isset($settings['template1']) || isset($settings[$layout->layout_template])) {
				$template_key = isset($settings[$layout->layout_template]) ? $layout->layout_template : 'template1';
				$quickview_settings = $settings[$template_key];
			}
			// Check if settings are directly at root level
			elseif (isset($settings['enable_quickview'])) {
				$quickview_settings = $settings;
			}

			if (!$quickview_settings) {
				continue;
			}

			// Check for display location settings
			if (!isset($quickview_settings['display-locations'])) {
				continue;
			}

			$locations = $quickview_settings['display-locations'];

			if (!is_array($locations)) {
				$locations = array($locations);
			}

			// Check if current page matches any display location
			foreach ($locations as $location) {
				if ($this->matches_location($location)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if current page matches the given location
	 */
	private function matches_location($location) {
		// Woo Shop Page
		if ($location === 'Woo Shop Page' && is_shop()) {
			return true;
		}

		// All Categories
		if ($location === 'All Categories' && is_product_category()) {
			return true;
		}

		// All Tags
		if ($location === 'All Tags' && is_product_tag()) {
			return true;
		}

		// All Products
		if ($location === 'All Products' && is_product()) {
			return true;
		}

		// Individual Category
		if (strpos($location, 'cat_') === 0) {
			$cat_id = str_replace('cat_', '', $location);
			if (is_product_category() && is_tax('product_cat', (int)$cat_id)) {
				return true;
			}
		}

		// Individual Tag
		if (strpos($location, 'tag_') === 0) {
			$tag_id = str_replace('tag_', '', $location);
			if (is_product_tag() && is_tax('product_tag', (int)$tag_id)) {
				return true;
			}
		}

		// Individual Product
		if (strpos($location, 'product_') === 0) {
			$product_id = str_replace('product_', '', $location);
			if (is_product() && get_the_ID() == $product_id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the selected Quick View layout ID from shop layout settings
	 */
	private function get_selected_quickview_layout_id() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

		// In AJAX context, we can't use conditional tags reliably
		// So we'll get the most recent shop layout that has QuickView enabled
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$shop_layouts = $wpdb->get_results("
			SELECT id, layout_settings
			FROM `{$wpdb->prefix}shopglut_shop_layouts`
			WHERE layout_settings LIKE '%quickview_layout_id%'
			ORDER BY updated_at DESC, id DESC
			LIMIT 1
		");

		if (empty($shop_layouts)) {
			// error_log('QuickView Debug: No shop layouts found with QuickView settings');
			return 0;
		}

		$shop_layout = $shop_layouts[0];
		$settings = maybe_unserialize($shop_layout->layout_settings);

		if ($settings === false || !is_array($settings)) {
			// error_log('QuickView Debug: Failed to unserialize shop layout settings');
			return 0;
		}

		// Look for the quickview_layout_id setting
		$quickview_layout_id = 0;

		// Check direct setting
		if (isset($settings['quickview_layout_id'])) {
			$quickview_layout_id = intval($settings['quickview_layout_id']);
		}
		// Check in content settings
		elseif (isset($settings['content']['quickview_layout_id'])) {
			$quickview_layout_id = intval($settings['content']['quickview_layout_id']);
		}
		// Check in template1 settings
		elseif (isset($settings['template1']['quickview_layout_id'])) {
			$quickview_layout_id = intval($settings['template1']['quickview_layout_id']);
		}
		// Check in any nested structure
		else {
			// Recursive search for quickview_layout_id
			$quickview_layout_id = $this->find_quickview_layout_id_recursive($settings);
		}

		// error_log('QuickView Debug: Found selected QuickView layout ID: ' . $quickview_layout_id);
		return $quickview_layout_id;
	}

	/**
	 * Recursively search for quickview_layout_id in nested array
	 */
	private function find_quickview_layout_id_recursive($array) {
		if (!is_array($array)) {
			return 0;
		}

		foreach ($array as $key => $value) {
			if ($key === 'quickview_layout_id') {
				return intval($value);
			}
			if (is_array($value)) {
				$result = $this->find_quickview_layout_id_recursive($value);
				if ($result > 0) {
					return $result;
				}
			}
		}

		return 0;
	}

	/**
	 * Get active layout settings
	 */
	private function get_active_layout_settings() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings, layout_template FROM `{$wpdb->prefix}shopglut_quickview_layouts`");

		foreach ($all_layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			// Check if unserialization failed
			if ($settings === false) {
				continue;
			}

			// Ensure settings is an array
			if (!is_array($settings)) {
				continue;
			}

			// Try different possible settings structure (same logic as in get_quickview_product)
			$quickview_settings = null;

			// Check for the expected structure
			if (isset($settings['shopg_product_quickview_settings_template1'])) {
				$quickview_settings = $settings['shopg_product_quickview_settings_template1'];
			}
			// Check for direct template settings
			elseif (isset($settings['template1']) || isset($settings[$layout->layout_template])) {
				$template_key = isset($settings[$layout->layout_template]) ? $layout->layout_template : 'template1';
				$quickview_settings = $settings[$template_key];
			}
			// Check if settings are directly at root level
			elseif (isset($settings['enable_quickview'])) {
				$quickview_settings = $settings;
			}

			if (!$quickview_settings) {
				continue;
			}

			// Check for display location settings
			if (!isset($quickview_settings['display-locations'])) {
				continue;
			}

			$locations = $quickview_settings['display-locations'];

			if (!is_array($locations)) {
				$locations = array($locations);
			}

			// Check if current page matches any display location
			foreach ($locations as $location) {
				if ($this->matches_location($location)) {
					return $quickview_settings;
				}
			}
		}

		return null;
	}

	/**
     * Render product quickview layout preview
     */
    public function shopglut_render_quickview_preview( $layout_id = 0 ) {
        // Ensure we have a valid enhancement ID
        if ( ! $layout_id ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            $layout_id = isset( $_GET['layout_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) ) : 1;
        }

        // Get enhancement data from database with caching
        $cache_key = 'shopglut_layout_' . $layout_id;
        $layout_data = wp_cache_get( $cache_key, 'shopglut_enhancements' );

        if ( false === $layout_data ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with proper prepare statement
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_quickview_layouts` WHERE id = %d", $layout_id )
            );

            // Cache the result for 1 hour
            if ( $layout_data ) {
                wp_cache_set( $cache_key, $layout_data, 'shopglut_enhancements', HOUR_IN_SECONDS );
            }
        }

        if ( ! $layout_data ) {
            return '<div class="shopglut-preview-error">Enhancement not found.</div>';
        }

        $template_name = $layout_data->layout_template; // e.g., 'template1', 'template2', etc.

        // Check if template markup file exists
        $markup_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Markup.php';
        if ( ! file_exists( $markup_file ) ) {
            return '<div class="shopglut-preview-error">Template markup file not found: ' . esc_html( $template_name . '/' . $template_name . 'Markup.php' ) . '</div>';
        }

        // Check if template style file exists
        $style_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Style.php';
        if ( ! file_exists( $style_file ) ) {
            return '<div class="shopglut-preview-error">Template style file not found: ' . esc_html( $template_name . '/' . $template_name . 'Style.php' ) . '</div>';
        }

        // Include the markup file
        require_once $markup_file;

        // Include the style file
        require_once $style_file;

        // Get the markup class
        $markup_class = 'Shopglut\\enhancements\\ProductQuickview\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\enhancements\\ProductQuickview\\templates\\' . $template_name . '\\' . $template_name . 'Style';
        if ( ! class_exists( $style_class ) ) {
            return '<div class="shopglut-preview-error">Style class not found: ' . esc_html( $style_class ) . '</div>';
        }

        // Initialize classes
        $markup_instance = new $markup_class();
        $style_instance = new $style_class();

        // Check if required methods exist
        if ( ! method_exists( $markup_instance, 'layout_render' ) ) {
            return '<div class="shopglut-preview-error">layout_render method not found in markup class.</div>';
        }

        if ( ! method_exists( $style_instance, 'dynamicCss' ) ) {
            return '<div class="shopglut-preview-error">dynamicCss method not found in style class.</div>';
        }

        // Prepare template data - No product_id means demo mode
        $template_data = array(
            'layout_id' => $layout_id,
            'layout_name' => $layout_data->layout_name,
            'settings' => maybe_unserialize( $layout_data->layout_settings ),
            // Don't include product_id to trigger demo mode
        );

        // Start output buffering
        ob_start();

        try {
            // Generate dynamic CSS
            $dynamic_css = $style_instance->dynamicCss( $layout_id );

            // Output CSS
            if ( ! empty( $dynamic_css ) ) {
                echo wp_kses( $dynamic_css, array() );
            }

            // Add preview wrapper with modal active state
            echo '<div class="shopglut-quickview-preview-wrapper">';
            echo '<style>.shopglut-product-quickview.template1 .quickview-modal { opacity: 1; visibility: visible; position: relative; }</style>';

            // Render the template markup in demo mode
            $markup_instance->layout_render( $template_data );

            echo '</div>';

        } catch ( Exception $e ) {
            ob_clean();
            return '<div class="shopglut-preview-error">Error rendering template: ' . esc_html( $e->getMessage() ) . '</div>';
        }

        // Get the rendered content
        $output = ob_get_clean();

        return $output;
    }

	/**
	 * Sanitize layout settings recursively
	 */
	private function sanitize_layout_settings($settings) {
		if (!is_array($settings)) {
			return sanitize_text_field($settings);
		}

		$sanitized = array();
		foreach ($settings as $key => $value) {
			$sanitized_key = sanitize_key($key);
			if (is_array($value)) {
				$sanitized[$sanitized_key] = $this->sanitize_layout_settings($value);
			} else {
				$sanitized[$sanitized_key] = sanitize_text_field($value);
			}
		}
		return $sanitized;
	}


	/**
	 * Save product quickview layout data from AJAX
	 */
	public function save_productquickview_layout_data() {
		// Check nonce first before accessing any POST data
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in the next line
		if (!isset($_POST['productquickview_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['productquickview_nonce'])), 'shopg_productquickview_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_productquickview_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_productquickview_layoutid']))) : 0;
		$layout_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';
		$layout_template = isset($_POST['layout_template']) ? sanitize_text_field(wp_unslash($_POST['layout_template'])) : 'template1';

		// Get settings data - handle both direct array and JSON string
		$layout_settings = array();
		if (isset($_POST['shopg_options_settings'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Data is sanitized below based on type
			$settings_raw = wp_unslash($_POST['shopg_options_settings']);

			// Check if it's a JSON string
			if (is_string($settings_raw)) {
				$settings_decoded = json_decode($settings_raw, true);
				if ($settings_decoded) {
					$layout_settings = $settings_decoded;
				}
			} else {
				$layout_settings = $this->sanitize_layout_settings($settings_raw);
			}
		}

		// Validate required fields
		if (empty($layout_name)) {
			wp_send_json_error('Layout name is required');
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		// Prepare data for saving
		$data = array(
			'layout_name' => $layout_name,
			'layout_template' => $layout_template,
			'layout_settings' => serialize($layout_settings)
		);

		if ($layout_id > 0) {
			// Update existing layout
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->update(
				$table_name,
				$data,
				array('id' => $layout_id),
				array('%s', '%s', '%s', '%s'),
				array('%d')
			);
		} else {
			// Insert new layout
			$data['created_at'] = current_time('mysql');
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->insert($table_name, $data, array('%s', '%s', '%s', '%s', '%s'));
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_id = $wpdb->insert_id;
		}

		if ($result === false) {
			wp_send_json_error(array(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				'message' => 'Database error: ' . $wpdb->last_error
			));
			return;
		}


		// Generate preview HTML
		$preview_html = $this->shopglut_render_quickview_preview($layout_id);

		wp_send_json_success(array(
			'message' => 'Product quickview layout saved successfully',
			'layout_id' => $layout_id,
			'html' => $preview_html
		));
	}

	/**
	 * Reset product quickview layout settings to default
	 */
	public function reset_productquickview_layout_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_productquickview_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		$layout_id = isset($_POST['layout_id']) ? intval(sanitize_text_field(wp_unslash($_POST['layout_id']))) : 0;

		if ($layout_id <= 0) {
			wp_send_json_error('Invalid layout ID');
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		// Get current layout data with caching
		$cache_key = "shopglut_quickview_layout_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_quickview' );

		if ( false === $layout_data ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$layout_data = $wpdb->get_row(
				$wpdb->prepare( "SELECT layout_template FROM `" . esc_sql($table_name) . "` WHERE id = %d", $layout_id )
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_quickview', 30 * MINUTE_IN_SECONDS );
		}

		if (!$layout_data) {
			wp_send_json_error('Layout not found');
			return;
		}

		// Clear settings (set to empty array so defaults will be used)
		$empty_settings = array();

		// Update the layout with empty settings
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->update(
			$table_name,
			array(
				'layout_settings' => serialize($empty_settings),
				'updated_at' => current_time('mysql')
			),
			array('id' => $layout_id),
			array('%s', '%s'),
			array('%d')
		);

		if ($result === false) {
			wp_send_json_error(array(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				'message' => 'Database error: ' . $wpdb->last_error
			));
			return;
		}

		// Clear relevant caches
		wp_cache_delete('shopglut_layout_' . $layout_id, 'shopglut_enhancements');
		wp_cache_delete('shopglut_layout_settings_' . $layout_id);
		wp_cache_delete('shopglut_layout_template_' . $layout_id);

		wp_send_json_success(array(
			'message' => 'Settings reset to default successfully!',
			'layout_id' => $layout_id
		));
	}

	/**
	 * Get quickview display options via AJAX
	 */
	public function shopglut_get_quickview_display_options() {
		// Verify nonce
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
		if (!wp_verify_nonce($nonce, 'shopg_productquickview_layouts')) {
			wp_send_json_error(['error' => __('Invalid nonce verification.', 'shopglut')]);
		}

		// Check capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['error' => __('You do not have permission to do that.', 'shopglut')]);
		}

		// Get current layout ID to exclude its selections from disabled options
		$layout_id = isset($_POST['layout_id']) ? absint(sanitize_text_field(wp_unslash($_POST['layout_id']))) : 0;

		// Initialize options with static entries
		// Note: QuickView doesn't need individual products since it's meant for product listings
		$options = [
			'Woo Shop Page' => 'Woo Shop Page',
			'All Categories' => 'All Categories',
			'All Tags' => 'All Tags',
		];

		// Fetch all WooCommerce product categories
		$product_categories = get_terms([
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		]);

		if (!is_wp_error($product_categories)) {
			foreach ($product_categories as $category) {
				$options['cat_' . $category->term_id] = 'Category: ' . $category->name;
			}
		}

		// Fetch all WooCommerce product tags
		$product_tags = get_terms([
			'taxonomy' => 'product_tag',
			'hide_empty' => false,
		]);

		if (!is_wp_error($product_tags)) {
			foreach ($product_tags as $tag) {
				$options['tag_' . $tag->term_id] = 'Tag: ' . $tag->name;
			}
		}

		// Get already selected options from other quickview layouts
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_quickview_layouts`");

		$used_options = [];
		foreach ($all_layouts as $layout) {
			// Skip current layout being edited
			if ($layout->id == $layout_id) {
				continue;
			}

			$settings = maybe_unserialize($layout->layout_settings);
			if (isset($settings['shopg_product_quickview_settings_template1']['display-locations'])) {
				$locations = $settings['shopg_product_quickview_settings_template1']['display-locations'];
				if (is_array($locations)) {
					$used_options = array_merge($used_options, $locations);
				}
			}
		}

		$used_options = array_unique($used_options);

		// Mark used options as disabled
		$formatted_options = [];
		foreach ($options as $key => $value) {
			$formatted_options[] = [
				'value' => $key,
				'text' => $value,
				'disabled' => in_array($key, $used_options) ? ' (Used by another layout)' : false
			];
		}

		wp_send_json_success($formatted_options);
	}

	/**
	 * Get plugin instance
	 */
	public static function get_instance() {
		static $instance;
		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}