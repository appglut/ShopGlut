<?php
namespace Shopglut\enhancements\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductComparisonDataManage {

	public function __construct() {
		// AJAX handlers for product comparison save and reset
		add_action('wp_ajax_save_shopg_productcomparison_layoutdata', [$this, 'save_productcomparison_layout_data']);
		add_action('wp_ajax_reset_shopg_productcomparison_layout_settings', [$this, 'reset_productcomparison_layout_settings']);
		add_action('wp_ajax_shopglut_get_comparison_display_options', [$this, 'shopglut_get_comparison_display_options']);
		add_action('wp_ajax_nopriv_shopglut_get_comparison_display_options', [$this, 'shopglut_get_comparison_display_options']);

		// AJAX handler for rendering comparison table
		add_action('wp_ajax_shopglut_render_comparison_table', [$this, 'ajax_render_comparison_table']);
		add_action('wp_ajax_nopriv_shopglut_render_comparison_table', [$this, 'ajax_render_comparison_table']);

		// AJAX handler for getting comparison products data
		add_action('wp_ajax_shopglut_get_comparison_products', [$this, 'ajax_get_comparison_products']);
		add_action('wp_ajax_nopriv_shopglut_get_comparison_products', [$this, 'ajax_get_comparison_products']);

		// Initialize comparison button display
		add_action('init', [$this, 'init_comparison_display']);
	}

	/**
	 * Initialize comparison button display on frontend
	 */
	public function init_comparison_display() {
		// Register hooks on wp hook (not init) so page conditionals work
		add_action('wp', [$this, 'register_comparison_hooks']);

		// Enqueue scripts and styles
		add_action('wp_enqueue_scripts', [$this, 'enqueue_comparison_assets']);

		// Add floating comparison bar to footer
		add_action('wp_footer', [$this, 'render_floating_comparison_bar']);
	}

	/**
	 * Register comparison hooks after WP query is set
	 */
	public function register_comparison_hooks() {
		// Get active layout settings to determine button positions
		$layout_settings = $this->get_active_layout_settings();

		if ($layout_settings) {
			// Register hooks based on position settings
			$this->register_position_hooks($layout_settings);
		}
	}

	/**
	 * Register button position hooks based on settings
	 */
	private function register_position_hooks($layout_settings) {
		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Get shop page position
		$shop_position = isset($page_settings['shop_page_position']['shop_button_position'])
			? $page_settings['shop_page_position']['shop_button_position']
			: 'after_add_to_cart';

		// Get archive page position
		$archive_position = isset($page_settings['archive_page_position']['archive_button_position'])
			? $page_settings['archive_page_position']['archive_button_position']
			: 'after_add_to_cart';

		// Get product page position
		$product_position = isset($page_settings['product_page_position']['product_button_position'])
			? $page_settings['product_page_position']['product_button_position']
			: 'after_add_to_cart';

		// Register shop page hooks
		$this->register_shop_hook($shop_position);

		// Register archive page hooks
		$this->register_archive_hook($archive_position);

		// Register product page hooks
		$this->register_product_hook($product_position);
	}

	/**
	 * Register shop page hook based on position
	 */
	private function register_shop_hook($position) {
		switch ($position) {
			case 'before_add_to_cart':
				add_action('woocommerce_before_shop_loop_item', [$this, 'display_comparison_button_shop'], 15);
				break;
			case 'after_add_to_cart':
				add_action('woocommerce_after_shop_loop_item', [$this, 'display_comparison_button_shop'], 15);
				break;
			case 'before_title':
				add_action('woocommerce_before_shop_loop_item_title', [$this, 'display_comparison_button_shop'], 5);
				break;
			case 'after_title':
				add_action('woocommerce_shop_loop_item_title', [$this, 'display_comparison_button_shop'], 15);
				break;
			case 'before_price':
				add_action('woocommerce_after_shop_loop_item_title', [$this, 'display_comparison_button_shop'], 5);
				break;
			case 'after_price':
				add_action('woocommerce_after_shop_loop_item_title', [$this, 'display_comparison_button_shop'], 15);
				break;
		}
	}

	/**
	 * Register archive page hook based on position
	 */
	private function register_archive_hook($position) {
		switch ($position) {
			case 'before_add_to_cart':
				add_action('woocommerce_before_shop_loop_item', [$this, 'display_comparison_button_archive'], 15);
				break;
			case 'after_add_to_cart':
				add_action('woocommerce_after_shop_loop_item', [$this, 'display_comparison_button_archive'], 15);
				break;
			case 'before_title':
				add_action('woocommerce_before_shop_loop_item_title', [$this, 'display_comparison_button_archive'], 5);
				break;
			case 'after_title':
				add_action('woocommerce_shop_loop_item_title', [$this, 'display_comparison_button_archive'], 15);
				break;
			case 'before_price':
				add_action('woocommerce_after_shop_loop_item_title', [$this, 'display_comparison_button_archive'], 5);
				break;
			case 'after_price':
				add_action('woocommerce_after_shop_loop_item_title', [$this, 'display_comparison_button_archive'], 15);
				break;
		}
	}

	/**
	 * Register product page hook based on position
	 */
	private function register_product_hook($position) {
		switch ($position) {
			case 'before_add_to_cart':
				add_action('woocommerce_before_add_to_cart_button', [$this, 'display_comparison_button_single'], 15);
				break;
			case 'after_add_to_cart':
				add_action('woocommerce_after_add_to_cart_button', [$this, 'display_comparison_button_single'], 15);
				break;
			case 'before_product_meta':
				add_action('woocommerce_product_meta_start', [$this, 'display_comparison_button_single'], 5);
				break;
			case 'after_product_meta':
				add_action('woocommerce_product_meta_end', [$this, 'display_comparison_button_single'], 15);
				break;
			case 'before_product_summary':
				add_action('woocommerce_before_single_product_summary', [$this, 'display_comparison_button_single'], 15);
				break;
			case 'after_product_summary':
				add_action('woocommerce_after_single_product_summary', [$this, 'display_comparison_button_single'], 5);
				break;
		}
	}

	/**
	 * Enqueue comparison assets
	 */
	public function enqueue_comparison_assets() {
		if (!$this->should_display_comparison_button()) {
			return;
		}

		// Font Awesome is already enqueued globally by ShopGlutRegisterScripts
		// No need to enqueue it again here

		// Enqueue comparison styles - Register a handle first
		wp_register_style('shopglut-comparison-inline', false, array(), SHOPGLUT_VERSION);
		wp_enqueue_style('shopglut-comparison-inline');
		wp_add_inline_style('shopglut-comparison-inline', $this->get_comparison_styles());

		// Localize settings for JavaScript
		$layout_settings = $this->get_active_layout_settings();
		if ($layout_settings) {
			$this->localize_comparison_settings($layout_settings);
		}
	}

	/**
	 * Localize comparison settings for JavaScript
	 */
	private function localize_comparison_settings($layout_settings) {
		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Get floating bar settings
		$floating_bar_settings = isset($page_settings['floating_bar_settings'])
			? $page_settings['floating_bar_settings']
			: array();

		// Get general settings
		$storage_settings = isset($page_settings['storage_settings']) ? $page_settings['storage_settings'] : array();
		$animation_settings = isset($page_settings['animation_settings']) ? $page_settings['animation_settings'] : array();
		$notification_settings = isset($page_settings['notification_settings']) ? $page_settings['notification_settings'] : array();

		// Prepare settings for JavaScript
		$js_settings = array(
			'minProductsShowBar' => isset($floating_bar_settings['min_products_show_bar']) ? intval($floating_bar_settings['min_products_show_bar']) : 1,
			'maxProductsCompare' => isset($floating_bar_settings['max_products_compare']) ? intval($floating_bar_settings['max_products_compare']) : 4,
			'storageMethod' => isset($storage_settings['storage_method']) ? $storage_settings['storage_method'] : 'localstorage',
			'cookieExpiryDays' => isset($storage_settings['cookie_expiry_days']) ? intval($storage_settings['cookie_expiry_days']) : 30,
			'enableAnimations' => isset($animation_settings['enable_animations']) ? (bool)$animation_settings['enable_animations'] : true,
			'animationSpeed' => isset($animation_settings['animation_speed']) ? intval($animation_settings['animation_speed']) : 300,
			'showNotifications' => isset($notification_settings['show_notifications']) ? (bool)$notification_settings['show_notifications'] : true,
			'notificationPosition' => isset($notification_settings['notification_position']) ? $notification_settings['notification_position'] : 'top-right',
			'notificationDuration' => isset($notification_settings['notification_duration']) ? intval($notification_settings['notification_duration']) : 3000,
		);

		// Add to existing shopglutComparisonData or create new
		wp_localize_script('shopglut-comparison-frontend', 'shopglutComparisonSettings', $js_settings);
	}

	/**
	 * Get comparison inline styles
	 */
	private function get_comparison_styles() {
		$layout_settings = $this->get_active_layout_settings();
		if (!$layout_settings) {
			return '';
		}

		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Get button styling settings (flat structure)
		$button_styling = isset($page_settings['button_styling'])
			? $page_settings['button_styling']
			: array();

		$bg_color = isset($button_styling['button_background_color']) ? $button_styling['button_background_color'] : '#3b82f6';
		$text_color = isset($button_styling['button_text_color']) ? $button_styling['button_text_color'] : '#ffffff';
		$hover_bg = isset($button_styling['button_hover_background_color']) ? $button_styling['button_hover_background_color'] : '#2563eb';
		$hover_text = isset($button_styling['button_hover_text_color']) ? $button_styling['button_hover_text_color'] : '#ffffff';

		$css = "
			.shopglut-comparison-button-wrapper {
				margin: 10px 0;
			}
			.shopglut-add-to-comparison,
			.shopglut-add-to-comparison-single {
				transition: all 0.3s ease;
			}
			.shopglut-add-to-comparison:hover,
			.shopglut-add-to-comparison-single:hover {
				background-color: {$hover_bg} !important;
				color: {$hover_text} !important;
			}
			.shopglut-add-to-comparison.added,
			.shopglut-add-to-comparison-single.added {
				background-color: #ef4444 !important;
			}
		";

		return $css;
	}

	/**
	 * Render floating comparison bar
	 */
	public function render_floating_comparison_bar() {
		// Always render floating bar for pages with Shop Layout shortcodes
		// This bypasses the location check to ensure the bar loads on pages with shop layout shortcodes
		if ($this->page_has_shop_layout_shortcode()) {
			$layout_settings = array(
				'shopg_product_comparison_settings_template1' => array(
					'product_comparison-page-settings' => array(
						'floating_bar_settings' => array('enable_floating_bar' => true),
						'floating_bar_styling' => array(
							'floating_bar_background' => '#ffffff',
							'floating_bar_text_color' => '#333333',
							'floating_bar_border_color' => '#e5e7eb',
							'floating_bar_height' => 80,
							'floating_bar_shadow' => true
						),
						'floating_compare_button' => array(
							'floating_compare_button_text' => 'Compare Now',
							'floating_compare_button_bg' => '#10b981',
							'floating_compare_button_text_color' => '#ffffff',
							'floating_clear_button_text' => 'Clear All'
						),
						'floating_bar_settings' => array(
							'floating_bar_position' => 'bottom'
						)
					)
				)
			);
		} else {
			$layout_settings = $this->get_active_layout_settings();
		}

		if (!$layout_settings) {
			return;
		}

		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Check if floating bar is enabled
		$floating_bar_settings = isset($page_settings['floating_bar_settings'])
			? $page_settings['floating_bar_settings']
			: array();

		$enable_floating_bar = isset($floating_bar_settings['enable_floating_bar']) ? $floating_bar_settings['enable_floating_bar'] : true;

		if (!$enable_floating_bar) {
			return;
		}

		// Get floating bar styling
		$floating_bar_styling = isset($page_settings['floating_bar_styling'])
			? $page_settings['floating_bar_styling']
			: array();

		$position = isset($floating_bar_settings['floating_bar_position']) ? $floating_bar_settings['floating_bar_position'] : 'bottom';
		$bg_color = isset($floating_bar_styling['floating_bar_background']) ? $floating_bar_styling['floating_bar_background'] : '#ffffff';
		$text_color = isset($floating_bar_styling['floating_bar_text_color']) ? $floating_bar_styling['floating_bar_text_color'] : '#333333';
		$border_color = isset($floating_bar_styling['floating_bar_border_color']) ? $floating_bar_styling['floating_bar_border_color'] : '#e5e7eb';
		$height = isset($floating_bar_styling['floating_bar_height']) ? $floating_bar_styling['floating_bar_height'] : 80;
		$has_shadow = isset($floating_bar_styling['floating_bar_shadow']) ? $floating_bar_styling['floating_bar_shadow'] : true;

		// Get compare button settings
		$compare_button = isset($page_settings['floating_compare_button'])
			? $page_settings['floating_compare_button']
			: array();

		$compare_text = isset($compare_button['floating_compare_button_text']) ? $compare_button['floating_compare_button_text'] : __('Compare Now', 'shopglut');
		$compare_bg = isset($compare_button['floating_compare_button_bg']) ? $compare_button['floating_compare_button_bg'] : '#10b981';
		$compare_text_color = isset($compare_button['floating_compare_button_text_color']) ? $compare_button['floating_compare_button_text_color'] : '#ffffff';
		$clear_text = isset($compare_button['floating_clear_button_text']) ? $compare_button['floating_clear_button_text'] : __('Clear All', 'shopglut');

		$shadow = $has_shadow ? 'box-shadow: 0 -2px 10px rgba(0,0,0,0.1);' : '';
		$position_style = $position === 'bottom' ? 'bottom: 0;' : 'top: 0;';

		?>
		<div id="shopglut-floating-comparison-bar" style="display: none; position: fixed; <?php echo esc_attr($position_style); ?> left: 0; right: 0; background: <?php echo esc_attr($bg_color); ?>; border-top: 1px solid <?php echo esc_attr($border_color); ?>; height: <?php echo esc_attr($height); ?>px; z-index: 9999; <?php echo esc_attr($shadow); ?> padding: 15px 30px; color: <?php echo esc_attr($text_color); ?>;">
			<div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 100%;">
				<div style="flex: 1;">
					<strong><?php echo esc_html__('Compare Products:', 'shopglut'); ?></strong>
					<span id="shopglut-comparison-count">0</span> <?php echo esc_html__('selected', 'shopglut'); ?>
				</div>
				<div id="shopglut-comparison-products" style="flex: 2; display: flex; gap: 10px; overflow-x: auto;"></div>
				<div style="display: flex; gap: 10px;">
					<button id="shopglut-compare-now-btn" style="background: <?php echo esc_attr($compare_bg); ?>; color: <?php echo esc_attr($compare_text_color); ?>; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
						<?php echo esc_html($compare_text); ?>
					</button>
					<button id="shopglut-clear-comparison-btn" style="background: #ef4444; color: #ffffff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
						<?php echo esc_html($clear_text); ?>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Display comparison button on shop pages
	 */
	public function display_comparison_button_shop() {
		// Only display on shop page
		if (!is_shop()) {
			return;
		}

		if (!$this->should_display_comparison_button()) {
			return;
		}

		$this->render_comparison_button();
	}

	/**
	 * Display comparison button on archive pages (categories, tags)
	 */
	public function display_comparison_button_archive() {
		// Only display on archive pages (categories and tags)
		if (!is_product_category() && !is_product_tag()) {
			return;
		}

		if (!$this->should_display_comparison_button()) {
			return;
		}

		$this->render_comparison_button();
	}

	/**
	 * Render comparison button HTML
	 */
	private function render_comparison_button() {
		$product_id = get_the_ID();
		$layout_settings = $this->get_active_layout_settings();

		if (!$layout_settings) {
			return;
		}

		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Check if comparison button is enabled
		$enable_comparison_button = isset($page_settings['enable_comparison_button'])
			? $page_settings['enable_comparison_button']
			: true;

		if (!$enable_comparison_button) {
			return;
		}

		// Get button text settings
		$button_text_settings = isset($page_settings['button_text_settings'])
			? $page_settings['button_text_settings']
			: array();

		// Get button icon settings
		$button_icon_settings = isset($page_settings['button_icon_settings'])
			? $page_settings['button_icon_settings']
			: array();

		// Extract values using helper method to handle nested fieldsets
		$button_text = $this->get_setting_value($button_text_settings, 'button_text', __('Add to Compare', 'shopglut'));
		$button_added_text = $this->get_setting_value($button_text_settings, 'button_added_text', __('Remove from Compare', 'shopglut'));
		$show_icon = $this->get_setting_value($button_icon_settings, 'show_button_icon', true);
		$button_icon = $this->get_setting_value($button_icon_settings, 'button_icon', 'fas fa-exchange-alt');

		// Ensure icon has proper Font Awesome prefix (fas/far/fab)
		if (!empty($button_icon) && strpos($button_icon, 'fa-') === 0) {
			$button_icon = 'fas ' . $button_icon;
		} elseif (!empty($button_icon) && !preg_match('/^(fas|far|fab|fa)\s/', $button_icon)) {
			$button_icon = 'fas ' . $button_icon;
		}

		$icon_position = $this->get_setting_value($button_icon_settings, 'button_icon_position', 'left');

		// Get button styling
		$button_styling = isset($page_settings['button_styling'])
			? $page_settings['button_styling']
			: array();

		$inline_styles = $this->get_button_inline_styles($button_styling);

		echo '<div class="shopglut-comparison-button-wrapper">';
		echo '<button class="shopglut-add-to-comparison" data-product-id="' . esc_attr($product_id) . '" data-added-text="' . esc_attr($button_added_text) . '" data-default-text="' . esc_attr($button_text) . '" style="' . esc_attr($inline_styles) . '">';

		if ($show_icon && $icon_position === 'left') {
			echo '<i class="' . esc_attr($button_icon) . '"></i> ';
		}

		echo esc_html($button_text);

		if ($show_icon && $icon_position === 'right') {
			echo ' <i class="' . esc_attr($button_icon) . '"></i>';
		}

		echo '</button>';
		echo '</div>';
	}

	/**
	 * Display comparison button on single product page
	 */
	public function display_comparison_button_single() {
		// Check if we should display on single product pages
		if (!is_product() || !$this->should_display_comparison_button()) {
			return;
		}

		$product_id = get_the_ID();
		$layout_settings = $this->get_active_layout_settings();

		if (!$layout_settings) {
			return;
		}

		// Extract the comparison settings
		$comparison_settings = isset($layout_settings['shopg_product_comparison_settings_template1'])
			? $layout_settings['shopg_product_comparison_settings_template1']
			: array();

		$page_settings = isset($comparison_settings['product_comparison-page-settings'])
			? $comparison_settings['product_comparison-page-settings']
			: array();

		// Check if comparison button is enabled
		$enable_comparison_button = isset($page_settings['enable_comparison_button'])
			? $page_settings['enable_comparison_button']
			: true;

		if (!$enable_comparison_button) {
			return;
		}

		// Get button text settings
		$button_text_settings = isset($page_settings['button_text_settings'])
			? $page_settings['button_text_settings']
			: array();

		// Get button icon settings
		$button_icon_settings = isset($page_settings['button_icon_settings'])
			? $page_settings['button_icon_settings']
			: array();

		// Extract values using helper method to handle nested fieldsets
		$button_text = $this->get_setting_value($button_text_settings, 'button_text', __('Add to Compare', 'shopglut'));
		$button_added_text = $this->get_setting_value($button_text_settings, 'button_added_text', __('Remove from Compare', 'shopglut'));
		$show_icon = $this->get_setting_value($button_icon_settings, 'show_button_icon', true);
		$button_icon = $this->get_setting_value($button_icon_settings, 'button_icon', 'fas fa-exchange-alt');

		// Ensure icon has proper Font Awesome prefix (fas/far/fab)
		if (!empty($button_icon) && strpos($button_icon, 'fa-') === 0) {
			$button_icon = 'fas ' . $button_icon;
		} elseif (!empty($button_icon) && !preg_match('/^(fas|far|fab|fa)\s/', $button_icon)) {
			$button_icon = 'fas ' . $button_icon;
		}

		$icon_position = $this->get_setting_value($button_icon_settings, 'button_icon_position', 'left');

		// Get button styling
		$button_styling = isset($page_settings['button_styling'])
			? $page_settings['button_styling']
			: array();

		$inline_styles = $this->get_button_inline_styles($button_styling);

		echo '<div class="shopglut-comparison-button-wrapper">';
		echo '<button class="shopglut-add-to-comparison-single" data-product-id="' . esc_attr($product_id) . '" data-added-text="' . esc_attr($button_added_text) . '" data-default-text="' . esc_attr($button_text) . '" style="' . esc_attr($inline_styles) . '">';

		if ($show_icon && $icon_position === 'left') {
			echo '<i class="' . esc_attr($button_icon) . '"></i> ';
		}

		echo esc_html($button_text);

		if ($show_icon && $icon_position === 'right') {
			echo ' <i class="' . esc_attr($button_icon) . '"></i>';
		}

		echo '</button>';
		echo '</div>';
	}

	/**
	 * Get setting value handling nested fieldset structure
	 * Fieldsets can store values as: array('field_name' => 'value') OR array('field_name' => array('field_name' => 'value'))
	 */
	private function get_setting_value($settings, $key, $default = null) {
		// Direct access
		if (isset($settings[$key])) {
			// Check if it's a nested array with the same key
			if (is_array($settings[$key]) && isset($settings[$key][$key])) {
				return $settings[$key][$key];
			}
			return $settings[$key];
		}
		return $default;
	}

	/**
	 * Get button inline styles from settings
	 */
	private function get_button_inline_styles($button_styling) {
		$styles = array();

		if (isset($button_styling['button_background_color'])) {
			$styles[] = 'background-color: ' . $button_styling['button_background_color'];
		}

		if (isset($button_styling['button_text_color'])) {
			$styles[] = 'color: ' . $button_styling['button_text_color'];
		}

		if (isset($button_styling['button_font_size'])) {
			$font_size = $button_styling['button_font_size'];
			if (is_array($font_size)) {
				$font_size = isset($font_size['value']) ? $font_size['value'] : (isset($font_size[0]) && is_numeric($font_size[0]) ? $font_size[0] : 16);
			}
			$font_size = is_numeric($font_size) ? $font_size : 16;
			$styles[] = 'font-size: ' . $font_size . 'px';
		}

		if (isset($button_styling['button_padding'])) {
			$padding = $button_styling['button_padding'];
			if (is_array($padding)) {
				$styles[] = sprintf(
					'padding: %spx %spx %spx %spx',
					$padding['top'] ?? 10,
					$padding['right'] ?? 20,
					$padding['bottom'] ?? 10,
					$padding['left'] ?? 20
				);
			}
		}

		if (isset($button_styling['button_border_radius'])) {
			$border_radius = $button_styling['button_border_radius'];
			if (is_array($border_radius)) {
				$border_radius = isset($border_radius['value']) ? $border_radius['value'] : (isset($border_radius[0]) && is_numeric($border_radius[0]) ? $border_radius[0] : 6);
			}
			$border_radius = is_numeric($border_radius) ? $border_radius : 6;
			$styles[] = 'border-radius: ' . $border_radius . 'px';
		}

		$styles[] = 'border: none';
		$styles[] = 'cursor: pointer';
		$styles[] = 'display: inline-flex';
		$styles[] = 'align-items: center';
		$styles[] = 'gap: 5px';

		return implode('; ', $styles);
	}

	/**
	 * Check if comparison button should be displayed on current page
	 */
	private function should_display_comparison_button() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_comparison_layouts`");

		foreach ($all_layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			if (!isset($settings['shopg_product_comparison_settings_template1'])) {
				continue;
			}

			$comparison_settings = $settings['shopg_product_comparison_settings_template1'];

			// Check for display location settings (now directly in settings, not in a fieldset)
			if (!isset($comparison_settings['display-locations'])) {
				continue;
			}

			$locations = $comparison_settings['display-locations'];

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

		// Pages with Shop Layout Shortcodes
		if ($location === 'Shop Layout Shortcodes' && $this->page_has_shop_layout_shortcode()) {
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
	 * Check if page context matches the given location (for AJAX calls)
	 *
	 * @param string $location The location to check against
	 * @param array $page_context Page context data from JavaScript
	 * @return bool Whether the location matches
	 */
	private function matches_location_with_context($location, $page_context) {
		// Woo Shop Page
		if ($location === 'Woo Shop Page' && !empty($page_context['is_shop'])) {
			return true;
		}

		// All Categories
		if ($location === 'All Categories' && !empty($page_context['is_category'])) {
			return true;
		}

		// All Tags
		if ($location === 'All Tags' && !empty($page_context['is_tag'])) {
			return true;
		}

		// All Products
		if ($location === 'All Products' && !empty($page_context['is_product'])) {
			return true;
		}

		// Individual Category
		if (strpos($location, 'cat_') === 0) {
			$cat_id = (int) str_replace('cat_', '', $location);
			if (!empty($page_context['is_category']) && $page_context['category_id'] == $cat_id) {
				return true;
			}
		}

		// Individual Tag
		if (strpos($location, 'tag_') === 0) {
			$tag_id = (int) str_replace('tag_', '', $location);
			if (!empty($page_context['is_tag']) && $page_context['tag_id'] == $tag_id) {
				return true;
			}
		}

		// Individual Product
		if (strpos($location, 'product_') === 0) {
			$product_id = (int) str_replace('product_', '', $location);
			if (!empty($page_context['is_product']) && $page_context['product_id'] == $product_id) {
				return true;
			}
		}

		return false;
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
	 * Get active layout settings
	 */
	private function get_active_layout_settings() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_comparison_layouts`");

		foreach ($all_layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			if (!isset($settings['shopg_product_comparison_settings_template1'])) {
				continue;
			}

			$comparison_settings = $settings['shopg_product_comparison_settings_template1'];

			// Check for display location settings (now directly in settings, not in a fieldset)
			if (!isset($comparison_settings['display-locations'])) {
				continue;
			}

			$locations = $comparison_settings['display-locations'];

			if (!is_array($locations)) {
				$locations = array($locations);
			}

			// Check if current page matches any display location
			foreach ($locations as $location) {
				if ($this->matches_location($location)) {
					// Return the full settings array, not just comparison_settings
					return $settings;
				}
			}
		}

		return null;
	}

	/**
     * Render product comparison layout preview
     */
    public function shopglut_render_comparison_preview( $layout_id = 0 ) {
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
            $table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with proper prepare statement
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_comparison_layouts` WHERE id = %d", $layout_id )
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
        $markup_class = 'Shopglut\\enhancements\\ProductComparison\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\enhancements\\ProductComparison\\templates\\' . $template_name . '\\' . $template_name . 'Style';
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

        // Prepare template data
        // NOTE: We intentionally don't pass 'products' key here so the template
        // will render in demo mode with sample data that respects the settings
        $template_data = array(
            'layout_id' => $layout_id,
            'layout_name' => $layout_data->layout_name,
            'settings' => maybe_unserialize( $layout_data->layout_settings )
        );

        // Start output buffering
        ob_start();

        try {
            // Generate dynamic CSS
            $dynamic_css = $style_instance->dynamicCss( $layout_id );

            // Output CSS
            if ( ! empty( $dynamic_css ) ) {
                echo '<style type="text/css">' . wp_kses( $dynamic_css, array() ) . '</style>';
            }

            // Render the template markup
            $markup_instance->layout_render( $template_data );

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
	 * Save product comparison layout data from AJAX
	 */
	public function save_productcomparison_layout_data() {
		// Check nonce first before accessing any POST data
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in the next line
		if (!isset($_POST['productcomparison_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['productcomparison_nonce'])), 'shopg_productcomparison_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_productcomparison_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_productcomparison_layoutid']))) : 0;
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
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

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
		$preview_html = $this->shopglut_render_comparison_preview($layout_id);

		wp_send_json_success(array(
			'message' => 'Product comparison layout saved successfully',
			'layout_id' => $layout_id,
			'html' => $preview_html
		));
	}

	/**
	 * Reset product comparison layout settings to default
	 */
	public function reset_productcomparison_layout_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_productcomparison_layouts')) {
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
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// Get current layout data with caching
		$cache_key = "shopglut_comparison_layout_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_comparison' );

		if ( false === $layout_data ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Table name properly escaped
			$layout_data = $wpdb->get_row(
				$wpdb->prepare( "SELECT layout_template FROM {$table_name} WHERE id = %d", $layout_id )
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_comparison', 30 * MINUTE_IN_SECONDS );
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
	 * Get comparison display options via AJAX
	 */
	public function shopglut_get_comparison_display_options() {
		// Verify nonce
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
		if (!wp_verify_nonce($nonce, 'shopg_productcomparison_layouts')) {
			wp_send_json_error(['error' => __('Invalid nonce verification.', 'shopglut')]);
		}

		// Check capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['error' => __('You do not have permission to do that.', 'shopglut')]);
		}

		// Get current layout ID to exclude its selections from disabled options
		$layout_id = isset($_POST['layout_id']) ? absint(sanitize_text_field(wp_unslash($_POST['layout_id']))) : 0;

		// Initialize options with static entries
		$options = [
			'Woo Shop Page' => 'Woo Shop Page',
			'All Categories' => 'All Categories',
			'All Tags' => 'All Tags',
			'All Products' => 'All Products',
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

		// Fetch all products
		$product_query = new \WP_Query([
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		if ($product_query->have_posts()) {
			while ($product_query->have_posts()) {
				$product_query->the_post();
				$options['product_' . get_the_ID()] = 'Product: ' . get_the_title();
			}
			wp_reset_postdata();
		}

		// Get already selected options from other comparison layouts
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_comparison_layouts`");

		$used_options = [];
		foreach ($all_layouts as $layout) {
			// Skip current layout being edited
			if ($layout->id == $layout_id) {
				continue;
			}

			$settings = maybe_unserialize($layout->layout_settings);
			if (isset($settings['shopg_product_comparison_settings_template1']['display-locations'])) {
				$locations = $settings['shopg_product_comparison_settings_template1']['display-locations'];
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
	/**
	 * AJAX handler to render comparison table
	 */
	public function ajax_render_comparison_table() {
		// Get product IDs from request
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public AJAX endpoint via nopriv action, data is sanitized
		$product_ids = isset($_POST['product_ids']) ? array_map('intval', $_POST['product_ids']) : array();

		if (empty($product_ids)) {
			wp_send_json_error(array('message' => __('No products selected for comparison.', 'shopglut')));
			return;
		}

		// Get page context from request (sent by JavaScript)
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Public AJAX endpoint via nopriv action, sanitized below
		$page_context = isset($_POST['page_context']) ? wp_unslash($_POST['page_context']) : array();

		// Sanitize page context
		$page_context = array(
			'is_shop' => isset($page_context['is_shop']) ? (bool) $page_context['is_shop'] : false,
			'is_product' => isset($page_context['is_product']) ? (bool) $page_context['is_product'] : false,
			'is_category' => isset($page_context['is_category']) ? (bool) $page_context['is_category'] : false,
			'is_tag' => isset($page_context['is_tag']) ? (bool) $page_context['is_tag'] : false,
			'category_id' => isset($page_context['category_id']) ? absint($page_context['category_id']) : 0,
			'tag_id' => isset($page_context['tag_id']) ? absint($page_context['tag_id']) : 0,
			'product_id' => isset($page_context['product_id']) ? absint($page_context['product_id']) : 0,
		);

		// Get active layout ID from database
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings, layout_name FROM `{$wpdb->prefix}shopglut_comparison_layouts`");

		$layout_id = 0;
		$layout_data = null;

		// Find the active layout that matches current page using page context
		foreach ($all_layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			if (!isset($settings['shopg_product_comparison_settings_template1'])) {
				continue;
			}

			$comparison_settings = $settings['shopg_product_comparison_settings_template1'];

			if (!isset($comparison_settings['display-locations'])) {
				continue;
			}

			$locations = $comparison_settings['display-locations'];
			if (!is_array($locations)) {
				$locations = array($locations);
			}

			// Check if current page matches any display location using page context
			foreach ($locations as $location) {
				if ($this->matches_location_with_context($location, $page_context)) {
					$layout_id = $layout->id;
					$layout_data = $layout;
					break 2;
				}
			}
		}

		if (!$layout_id) {
			// For AJAX calls, we can't match page location, so use the first layout with actual settings
			foreach ($all_layouts as $layout) {
				$settings = maybe_unserialize($layout->layout_settings);
				if (isset($settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings'])) {
					// Check if it has actual comparison fields configured
					$page_settings = $settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings'];
					if (isset($page_settings['comparison_fields']) && !empty($page_settings['comparison_fields'])) {
						$layout_id = $layout->id;
						$layout_data = $layout;
						break;
					}
				}
			}

			// If still no match, fallback to first layout
			if (!$layout_id && !empty($all_layouts)) {
				$layout_id = $all_layouts[0]->id;
				$layout_data = $all_layouts[0];
			}
		}

		// Prepare products data
		$products = array();
		foreach ($product_ids as $product_id) {
			$products[] = array('id' => $product_id);
		}

		// Load the template markup and style classes
		$template_class = '\\Shopglut\\enhancements\\ProductComparison\\templates\\template1\\template1Markup';
		$style_class = '\\Shopglut\\enhancements\\ProductComparison\\templates\\template1\\template1Style';

		if (class_exists($template_class) && class_exists($style_class)) {
			$template = new $template_class();
			$style = new $style_class();

			// Start output buffering
			ob_start();

			// Generate and output dynamic CSS
			$dynamic_css = $style->dynamicCss($layout_id);
			if (!empty($dynamic_css)) {
				echo '<style type="text/css">' . wp_kses($dynamic_css, array()) . '</style>';
			}

			// Render the comparison table
			$template->layout_render(array(
				'layout_id' => $layout_id,
				'layout_name' => $layout_data ? $layout_data->layout_name : 'Comparison',
				'settings' => $layout_data ? maybe_unserialize($layout_data->layout_settings) : array(),
				'products' => $products
			));

			// Get the rendered HTML
			$html = ob_get_clean();

			wp_send_json_success(array('html' => $html));
		} else {
			wp_send_json_error(array('message' => __('Comparison template not found.', 'shopglut')));
		}
	}

	/**
	 * AJAX handler to get comparison products data
	 */
	public function ajax_get_comparison_products() {
		// Get product IDs from request
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public AJAX endpoint via nopriv action, data is sanitized
		$product_ids = isset($_POST['product_ids']) ? array_map('intval', $_POST['product_ids']) : array();

		if (empty($product_ids)) {
			wp_send_json_error(array('message' => __('No products provided.', 'shopglut')));
			return;
		}

		$products = array();

		foreach ($product_ids as $product_id) {
			$product = wc_get_product($product_id);

			if (!$product) {
				continue;
			}

			$image_id = $product->get_image_id();
			$image_url = '';

			if ($image_id) {
				$image_array = wp_get_attachment_image_src($image_id, 'thumbnail');
				$image_url = $image_array ? $image_array[0] : '';
			}

			$products[] = array(
				'id' => $product_id,
				'name' => $product->get_name(),
				'image' => $image_url,
				'url' => $product->get_permalink(),
			);
		}

		wp_send_json_success(array('products' => $products));
	}

	public static function get_instance() {
		static $instance;
		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}