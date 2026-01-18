<?php
namespace Shopglut\layouts\accountPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AccountPageDataManage {

	private $enabled_layout_id = null;

	public function __construct() {
		// AJAX handlers for product accountpage save and reset
		add_action('wp_ajax_save_shopg_productaccountpage_layoutdata', [$this, 'save_productaccountpage_layout_data']);
		add_action('wp_ajax_reset_shopg_productaccountpage_layout_settings', [$this, 'reset_productaccountpage_layout_settings']);
		add_action('wp_ajax_shopglut_get_accountpage_display_options', [$this, 'shopglut_get_accountpage_display_options']);
		add_action('wp_ajax_nopriv_shopglut_get_accountpage_display_options', [$this, 'shopglut_get_accountpage_display_options']);

		// Initialize accountpage button display
		add_action('init', [$this, 'init_accountpage_display']);
	}

	/**
	 * Initialize custom account page display on frontend
	 */
	public function init_accountpage_display() {
		// Always add the filter - we'll check if enabled inside the filter
		add_filter('woocommerce_locate_template', [$this, 'override_account_template'], 10, 3);

		// Enqueue styles for account page
		add_action('wp_enqueue_scripts', [$this, 'enqueue_accountpage_assets']);
	}

	/**
	 * Check if custom account page is enabled
	 */
	private function is_custom_accountpage_enabled() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

		// Get all layouts
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$layouts = $wpdb->get_results(
			"SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_accountpage_layouts` ORDER BY id ASC"
		);

		if (!$layouts) {
			return false;
		}

		// Check each layout to find one that's enabled
		foreach ($layouts as $layout) {
			$settings = maybe_unserialize($layout->layout_settings);

			if (isset($settings['shopg_accountpage_settings_template1']['enable_accountpage'])) {
				$enabled = $settings['shopg_accountpage_settings_template1']['enable_accountpage'];
				// Handle various formats: true, 'true', '1', 1
				if (filter_var($enabled, FILTER_VALIDATE_BOOLEAN)) {
					// Store the enabled layout ID for later use
					$this->enabled_layout_id = $layout->id;
					return true;
				}
			}
		}

		// No enabled layout found
		return false;
	}

	/**
	 * Override WooCommerce account page template
	 */
	public function override_account_template($template, $template_name, $template_path) {
		// Only override my-account.php template
		if ($template_name !== 'myaccount/my-account.php') {
			return $template;
		}

		// Check if custom account page is enabled
		if (!$this->is_custom_accountpage_enabled()) {
			return $template;
		}

		// Get custom template path
		$custom_template = $this->get_custom_account_template();

		if ($custom_template && file_exists($custom_template)) {
			return $custom_template;
		}

		return $template;
	}

	/**
	 * Get custom account template path
	 */
	private function get_custom_account_template() {
		// Use the enabled layout ID if we have it
		$layout_id = $this->enabled_layout_id;

		if (!$layout_id) {
			// Fallback: check if any layout is enabled
			if (!$this->is_custom_accountpage_enabled()) {
				return false;
			}
			$layout_id = $this->enabled_layout_id;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$layout = $wpdb->get_row(
			$wpdb->prepare("SELECT id, layout_template FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = %d LIMIT 1", $layout_id)
		);

		if (!$layout) {
			return false;
		}

		$template_name = $layout->layout_template; // e.g., 'template1'
		$template_file = __DIR__ . '/templates/' . $template_name . '/my-account.php';

		return $template_file;
	}

	/**
	 * Enqueue accountpage assets
	 */
	public function enqueue_accountpage_assets() {
		// Only enqueue on account page
		if (!is_account_page()) {
			return;
		}

		// Get dynamic CSS
		$dynamic_css = $this->get_accountpage_dynamic_css();

		if ($dynamic_css) {
			// Add inline style
			wp_register_style('shopglut-accountpage-custom', false, array(), SHOPGLUT_VERSION);
			wp_enqueue_style('shopglut-accountpage-custom');
			wp_add_inline_style('shopglut-accountpage-custom', $dynamic_css);
		}
	}

	/**
	 * Get dynamic CSS for account page from settings
	 */
	private function get_accountpage_dynamic_css() {
		// Use the enabled layout ID if we have it
		$layout_id = $this->enabled_layout_id;

		if (!$layout_id) {
			// Fallback: check if any layout is enabled
			if (!$this->is_custom_accountpage_enabled()) {
				return '';
			}
			$layout_id = $this->enabled_layout_id;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$layout = $wpdb->get_row(
			$wpdb->prepare("SELECT id, layout_template FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = %d LIMIT 1", $layout_id)
		);

		if (!$layout) {
			return '';
		}

		$template_name = $layout->layout_template;

		// Check if template style file exists
		$style_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Style.php';
		if (!file_exists($style_file)) {
			return '';
		}

		// Include the style file
		require_once $style_file;

		// Get the style class
		$style_class = 'Shopglut\\layouts\\accountPage\\templates\\' . $template_name . '\\' . $template_name . 'Style';
		if (!class_exists($style_class)) {
			return '';
		}

		// Initialize style instance
		$style_instance = new $style_class();

		// Check if dynamicCss method exists
		if (!method_exists($style_instance, 'dynamicCss')) {
			return '';
		}

		// Start output buffering
		ob_start();

		// Generate dynamic CSS
		$style_instance->dynamicCss($layout_id);

		// Get the CSS
		$css = ob_get_clean();

		// Remove <style> tags if present
		$css = preg_replace('/<\/?style[^>]*>/', '', $css);

		return $css;
	}

	/**
     * Render product accountpage layout preview
     */
    public function shopglut_render_accountpage_preview( $layout_id = 0 ) {
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
            $table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with proper prepare statement
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = %d", $layout_id )
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
        $markup_class = 'Shopglut\\layouts\\accountPage\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\layouts\\accountPage\\templates\\' . $template_name . '\\' . $template_name . 'Style';
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

        // Prepare template data for demo mode
        $template_data = array(
            'layout_id' => $layout_id,
            'layout_name' => $layout_data->layout_name,
            'settings' => maybe_unserialize( $layout_data->layout_settings ),
            'is_demo' => true, // Enable demo mode for preview
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

            // Add preview wrapper
            echo '<div class="shopglut-accountpage-preview-wrapper">';

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
	 * Save product accountpage layout data from AJAX
	 */
	public function save_productaccountpage_layout_data() {
		// Check nonce first before accessing any POST data
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in the next line
		if (!isset($_POST['productaccountpage_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['productaccountpage_nonce'])), 'shopg_accountpage_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_productaccountpage_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_productaccountpage_layoutid']))) : 0;
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
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

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
		$preview_html = $this->shopglut_render_accountpage_preview($layout_id);

		wp_send_json_success(array(
			'message' => 'Product accountpage layout saved successfully',
			'layout_id' => $layout_id,
			'html' => $preview_html
		));
	}

	/**
	 * Reset product accountpage layout settings to default
	 */
	public function reset_productaccountpage_layout_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_accountpage_layouts')) {
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
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';
		$escaped_table = esc_sql($table_name);

		// Get current layout data
		$layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
			$wpdb->prepare(sprintf("SELECT layout_template FROM `%s` WHERE id = %d", $escaped_table), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name
		);

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
	 * Get accountpage display options via AJAX
	 */
	public function shopglut_get_accountpage_display_options() {
		// Verify nonce
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
		if (!wp_verify_nonce($nonce, 'shopg_accountpage_layouts')) {
			wp_send_json_error(['error' => __('Invalid nonce verification.', 'shopglut')]);
		}

		// Check capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['error' => __('You do not have permission to do that.', 'shopglut')]);
		}

		// Get current layout ID to exclude its selections from disabled options
		$layout_id = isset($_POST['layout_id']) ? absint(sanitize_text_field(wp_unslash($_POST['layout_id']))) : 0;

		// Initialize options with static entries
		// Note: AccountPage doesn't need individual products since it's meant for product listings
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

		// Get already selected options from other accountpage layouts
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
		$all_layouts = $wpdb->get_results("SELECT id, layout_settings FROM `{$wpdb->prefix}shopglut_accountpage_layouts`");

		$used_options = [];
		foreach ($all_layouts as $layout) {
			// Skip current layout being edited
			if ($layout->id == $layout_id) {
				continue;
			}

			$settings = maybe_unserialize($layout->layout_settings);
			if (isset($settings['shopg_product_accountpage_settings_template1']['display-locations'])) {
				$locations = $settings['shopg_product_accountpage_settings_template1']['display-locations'];
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