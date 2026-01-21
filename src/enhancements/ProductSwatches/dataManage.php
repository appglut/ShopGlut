<?php
namespace Shopglut\enhancements\ProductSwatches;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class dataManage {

    public function __construct() {

		add_action('wp_ajax_shopglut_template2_add_to_cart', array($this, 'handle_add_to_cart'));
        add_action('wp_ajax_nopriv_shopglut_template2_add_to_cart', array($this, 'handle_add_to_cart'));

        add_action('wp_ajax_shopglut_save_product_swatches_layout', [$this, 'shopglut_save_product_swatches_layout']);
        add_action('wp_ajax_shopglut_reset_product_swatches_layout', [$this, 'shopglut_reset_product_swatches_layout']);

        // Disabled: Use FrontendRenderer instead to only replace swatches, not entire product page
        // The FrontendRenderer hooks into woocommerce_dropdown_variation_attribute_options_html
        // to replace ONLY the variation dropdowns with custom swatches
        // if (!$this->is_pro_plugin_active_and_enabled()) {
        //     add_filter('template_include', [$this, 'override_template'], 99);
        // }

       add_action('wp_ajax_shopglut_ProductSwatches_get_product_options', array($this, 'shopglut_ProductSwatches_get_product_options'));
       add_action('wp_ajax_nopriv_shopglut_ProductSwatches_get_product_options', array($this, 'shopglut_ProductSwatches_get_product_options'));


    }

    public function get_template_files() {
        $template_path = plugin_dir_path(__FILE__) . 'templates/single-product/';
        $files = array();
        
        $found_files = glob($template_path . '{,**/}*.php', GLOB_BRACE);
        
        foreach ($found_files as $file) {
            $files[] = str_replace($template_path, '', $file);
        }
        
        return $files;
    }

    private function is_pro_plugin_active_and_enabled() {
        if (class_exists('Shopglut\layouts\ProductSwatchesPro\dataManage')) {
            return true;
        }

        if (is_plugin_active('shopglut-productPage-pro\productPage-pro.php')) {
            return true;
        }

        if (defined('SHOPGLUT_PRODUCTPAGE_PRO_VERSION')) {
            return true;
        }

        return false;
    }

	public function handle_add_to_cart()
    {
        // Load WooCommerce if not loaded
        if (!function_exists('WC')) {
            // Try to load WooCommerce
            if (defined('WC_PLUGIN_FILE')) {
                include_once WC_PLUGIN_FILE;
            }
        }

        // Check if WooCommerce is available after loading attempt
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => __('WooCommerce not available', 'shopglut')));
        }

        // Initialize WooCommerce cart if needed
        if (WC()->cart === null) {
            wc_load_cart();
        }

        // Verify nonce
        if (!isset($_POST['nonce'])) {
            wp_send_json_error(array('message' => __('Security nonce not provided', 'shopglut')));
        }

        if (!wp_verify_nonce($_POST['nonce'], 'shopglut_template2_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'shopglut')));
        }

        // Validate required parameters
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if (!$product_id || $quantity < 1) {
            wp_send_json_error(array('message' => __('Invalid product or quantity', 'shopglut')));
        }

        // Validate product exists
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error(array('message' => __('Product not found', 'shopglut')));
        }

        // Check if product is purchasable
        if (!$product->is_purchasable()) {
            wp_send_json_error(array('message' => __('Product is not purchasable', 'shopglut')));
        }

        // Check stock
        if (!$product->is_in_stock()) {
            wp_send_json_error(array('message' => __('Product is out of stock', 'shopglut')));
        }

        // Get selected variations
        $variation_id = 0;
        $variation_data = array();

        if ($product->is_type('variable')) {
            $variation_data = $this->get_variation_data_from_post();
            $variation_id = $this->find_matching_variation($product, $variation_data);

            if (!$variation_id) {
                wp_send_json_error(array('message' => __('Please select product options', 'shopglut')));
            }

            $variation_product = wc_get_product($variation_id);
            if (!$variation_product || !$variation_product->is_in_stock()) {
                wp_send_json_error(array('message' => __('Selected variation is out of stock', 'shopglut')));
            }
        }

        // Add to cart
        $cart_item_data = array();
        if (!empty($variation_data)) {
            $cart_item_data['variation'] = $variation_data;
        }

        $cart_key = WC()->cart->add_to_cart(
            $product_id,
            $quantity,
            $variation_id,
            $variation_data,
            $cart_item_data
        );

        if ($cart_key === false) {
            wp_send_json_error(array('message' => __('Failed to add product to cart', 'shopglut')));
        }

        // Prepare response data
        $response_data = array(
            'success' => true,
            'message' => __('Product added to cart', 'shopglut'),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
            'cart_hash' => WC()->cart->get_cart_hash(),
            'product_data' => array(
                'id' => $product_id,
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'quantity' => $quantity
            )
        );

        // Add variation data if applicable
        if ($variation_id) {
            $variation_product = wc_get_product($variation_id);
            $response_data['product_data']['variation_id'] = $variation_id;
            $response_data['product_data']['variation_price'] = $variation_product->get_price();
        }

        // Apply filters for additional data
        $response_data = apply_filters('shopglut_template2_add_to_cart_response', $response_data, $product, $quantity, $variation_id);

        wp_send_json_success($response_data);
    }

	  /**
     * Get variation data from POST request
     */
    private function get_variation_data_from_post()
    {
        $variation_data = array();

        // Get color selection
        if (isset($_POST['color']) && !empty($_POST['color'])) {
            $variation_data['attribute_color'] = sanitize_text_field($_POST['color']);
        }

        // Get size selection
        if (isset($_POST['size']) && !empty($_POST['size'])) {
            $variation_data['attribute_size'] = sanitize_text_field($_POST['size']);
        }

        // Get any other variation attributes
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attribute_') === 0 && !empty($value)) {
                $variation_data[$key] = sanitize_text_field($value);
            }
        }

        return $variation_data;
    }



	 /**
     * Render cart layout preview
     */
    public function shopglut_render_singleplayout_preview( $layout_id = 0 ) {
        // Ensure we have a valid layout ID
        if ( ! $layout_id ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            $layout_id = isset( $_GET['layout_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) ) : 1;
        }

        // Set up product context for preview to show actual product attributes
        $this->setup_preview_product_context();

        // Get layout data from database
        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

        // Create cache key
        $cache_key = "shopglut_productswatches_layout_{$layout_id}";
        $cached_layout = wp_cache_get( $cache_key, 'shopglut_layouts' );

        if ( false === $cached_layout ) {
            $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$table_name}` WHERE id = %d", $layout_id )
            );
            // Cache for 30 minutes
            wp_cache_set( $cache_key, $layout_data, 'shopglut_layouts', 30 * MINUTE_IN_SECONDS );
        } else {
            $layout_data = $cached_layout;
        }

        if ( ! $layout_data ) {
            return '<div class="shopglut-preview-error">Layout not found.</div>';
        }

        $template_name = $layout_data->layout_template; // e.g., 'template1', 'template2', 'templatePro1', etc.

        // Check if this is a pro template (starts with 'templatePro')
        $is_pro_template = (strpos($template_name, 'templatePro') === 0);

        if ($is_pro_template) {
            // Pro templates use generic file names: templateMarkup.php, templateStyle.php
            $markup_file = __DIR__ . '/templates/' . $template_name . '/templateMarkup.php';
            $style_file = __DIR__ . '/templates/' . $template_name . '/templateStyle.php';
            $markup_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $template_name . '\\templateMarkup';
            $style_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $template_name . '\\templateStyle';
        } else {
            // Regular templates use template-specific file names: template1Markup.php, template1Style.php
            $markup_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Markup.php';
            $style_file = __DIR__ . '/templates/' . $template_name . '/' . $template_name . 'Style.php';
            $markup_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
            $style_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $template_name . '\\' . $template_name . 'Style';
        }

        if ( ! file_exists( $markup_file ) ) {
            return '<div class="shopglut-preview-error">Template markup file not found: ' . esc_html( basename($markup_file) ) . '</div>';
        }

        if ( ! file_exists( $style_file ) ) {
            return '<div class="shopglut-preview-error">Template style file not found: ' . esc_html( basename($style_file) ) . '</div>';
        }

        // Include the markup file
        require_once $markup_file;

        // Include the style file
        require_once $style_file;

        // Check if classes exist
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        if ( ! class_exists( $style_class ) ) {
            return '<div class="shopglut-preview-error">Style class not found: ' . esc_html( $style_class ) . '</div>';
        }

        // Initialize classes
        // Only pass admin_preview=true for the AJAX preview context
        $layout_settings = maybe_unserialize($layout_data->layout_settings);

        // Check if we're in AJAX preview context (not live frontend)
        $is_ajax_preview = defined('SHOPGLUT_PREVIEW_MODE') && SHOPGLUT_PREVIEW_MODE;
        $markup_instance = new $markup_class($layout_settings, $is_ajax_preview);
        $style_instance = new $style_class();

        // Check if required methods exist
        if ( ! method_exists( $markup_instance, 'layout_render' ) ) {
            return '<div class="shopglut-preview-error">layout_render method not found in markup class.</div>';
        }

        if ( ! method_exists( $style_instance, 'dynamicCss' ) ) {
            return '<div class="shopglut-preview-error">dynamicCss method not found in style class.</div>';
        }

        // Prepare template data
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
                echo '<style type="text/css">' . $dynamic_css . '</style>';
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


    public function shopglut_ProductSwatches_get_product_options() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $results = $wpdb->get_results("SELECT id, layout_settings, layout_template FROM `{$table_name}`", ARRAY_A);

        $all_used_options = [];

        // Get current layout ID to exclude it from the check
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check
        $current_layout_id = isset($_POST['layout_id']) ? intval($_POST['layout_id']) : 0;

        // Fallback: try to get from URL if not in POST
        if ($current_layout_id === 0) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check
            $current_layout_id = isset($_GET['layout_id']) ? intval($_GET['layout_id']) : 0;
        }

        // Fallback: try WordPress standard 'post' parameter
        if ($current_layout_id === 0) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check
            $current_layout_id = isset($_POST['post']) ? intval($_POST['post']) : 0;
        }

        // Fallback: try WordPress standard 'post' parameter in URL
        if ($current_layout_id === 0) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check
            $current_layout_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
        }

        foreach ($results as $result) {
            // Skip the current layout being edited
            if ($current_layout_id > 0 && $result['id'] == $current_layout_id) {
                continue;
            }

            $layout_settings = unserialize($result['layout_settings']);
            $template_settings_key = 'shopg_product_swatches_settings_' . $result['layout_template'];

            // Check if template settings exist
            if (isset($layout_settings[$template_settings_key])) {
                $template_settings = $layout_settings[$template_settings_key];

                // ONLY collect specific page selections (ignore "Apply Globally" completely)
                $used_options = $template_settings['apply-selective-swatches'] ?? array();
                if (is_array($used_options) && !empty($used_options)) {
                    $all_used_options = array_merge($all_used_options, $used_options);
                }
            }
        }

        // Remove duplicates and re-index
        $all_used_options = array_values(array_unique($all_used_options));

        wp_send_json_success(array_map(function($id) {
            return [
                'id' => $id,
                'name' => get_the_title($id)
            ];
        }, $all_used_options));
    }


	public function override_template($template) {
    if (!is_product()) {
        return $template;
    }

    global $wpdb;
    $product_id = get_the_ID();

    // Get layouts from database
    $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $layouts = $wpdb->get_results("SELECT * FROM `{$table_name}`", ARRAY_A);

    $should_override = false;
    $selected_layout = null;

    foreach ($layouts as $layout) {
        $settings = maybe_unserialize($layout['layout_settings']);

        if (empty($settings)) continue;

        $layout_template = $layout['layout_template'] ?? 'template1';
        $template_settings_key = 'shopg_product_swatches_settings_' . $layout_template;

        if (isset($settings[$template_settings_key])) {
            $template_settings = $settings[$template_settings_key];
            $apply_global = $template_settings['apply-global-swatches'] ?? false;
            $selective_pages = $template_settings['apply-selective-swatches'] ?? [];
            $is_specific_page = in_array('product_' . $product_id, $selective_pages);

            if ($apply_global || $is_specific_page) {
                $should_override = true;
                $selected_layout = $layout;
                break;
            }
        }
    }

    if ($should_override && $selected_layout) {
        $layout_template = $selected_layout['layout_template'];

        // Check if this is a pro template (starts with 'templatePro')
        $is_pro_template = (strpos($layout_template, 'templatePro') === 0);

        if ($is_pro_template) {
            // Pro templates use generic file names: templateMarkup.php, templateStyle.php
            $template_markup_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/templateMarkup.php';
            $template_style_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/templateStyle.php';
            $markup_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $layout_template . '\\templateMarkup';
            $style_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $layout_template . '\\templateStyle';
        } else {
            // Regular templates use template-specific file names: template1Markup.php, template1Style.php
            $template_markup_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/' . $layout_template . 'Markup.php';
            $template_style_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/' . $layout_template . 'Style.php';
            $markup_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $layout_template . '\\' . $layout_template . 'Markup';
            $style_class = 'Shopglut\\enhancements\\ProductSwatches\\templates\\' . $layout_template . '\\' . $layout_template . 'Style';
        }

        if (file_exists($template_markup_path) && file_exists($template_style_path)) {
            // Include template files
            require_once $template_markup_path;
            require_once $template_style_path;

            if (class_exists($markup_class) && class_exists($style_class)) {
                get_header();

                // Make layout settings globally available
                global $layout_settings;
                $layout_settings = maybe_unserialize($selected_layout['layout_settings']);

                // Create instances
                // Frontend should never use admin preview mode - always use live product data
                $markup_instance = new $markup_class(array(), false);
                $style_instance = new $style_class();

                // Add CSS to override Astra theme container restrictions
                echo '<style type="text/css">
                    /* Override Astra theme container restrictions for ShopGlut templates */
                    .shopglut-single-product-container {
                        width: 100% !important;
                        max-width: 100% !important;
                        margin: 0 !important;
                        padding: 0 20px !important;
                    }

                    /* Ensure full width display */
                    .shopglut-single-product {
                        width: 100% !important;
                        max-width: 1240px !important;
                        margin: 0 auto !important;
                        padding: 20px !important;
                    }

                    /* Override any container flex restrictions */
                    .site-content .ast-container {
                        display: block !important;
                    }

                    /* Ensure our template gets proper spacing */
                    .shopglut-single-product-container .product-main-wrapper {
                        display: flex !important;
                        gap: 40px !important;
                        margin-bottom: 40px !important;
                    }

                    @media (min-width: 922px) {
                        .shopglut-single-product-container .product-gallery-section {
                            flex: 0 0 50% !important;
                        }

                        .shopglut-single-product-container .product-info-section {
                            flex: 0 0 50% !important;
                        }
                    }

                    @media (max-width: 921px) {
                        .shopglut-single-product-container .product-main-wrapper {
                            flex-direction: column !important;
                        }
                    }
                </style>';

                // Generate and output template-specific CSS
                if (method_exists($style_instance, 'dynamicCss')) {
                    $layout_id = $selected_layout['id'];
                    $dynamic_css = $style_instance->dynamicCss($layout_id);
                    if (!empty($dynamic_css)) {
                        echo '<style type="text/css">' . wp_kses($dynamic_css, array()) . '</style>';
                    }
                }

                // Use the layout_render method
                if (method_exists($markup_instance, 'layout_render')) {
                    $template_data = array(
                        'layout_id' => $selected_layout['id'],
                        'layout_name' => $selected_layout['layout_name'] ?? '',
                        'settings' => $layout_settings
                    );
                    $markup_instance->layout_render($template_data);
                } else {
                    echo '<div style="padding: 20px; background: #ffe6e6; color: #d8000c;">Error: Template class method not found</div>';
                }

                get_footer();
                exit;
            } else {
                echo '<div style="padding: 20px; background: #ffe6e6; color: #d8000c;">Error: Template classes not found for ' . esc_html($layout_template) . '</div>';
                get_footer();
                exit;
            }
        } else {
            echo '<div style="padding: 20px; background: #ffe6e6; color: #d8000c;">Error: Template files not found for ' . esc_html($layout_template) . '</div>';
            get_footer();
            exit;
        }
    }

    // No override - return default template
    return $template;
}

	/**
	 * Get template settings keys that have apply functionality enabled
	 */
	private function get_template_settings_keys($settings) {
		$keys = [];

		// Find template-specific settings that have apply enabled
		foreach ($settings as $key => $value) {
			if (strpos($key, 'shopg_product_swatches_settings_') === 0 && is_array($value)) {
				$has_global_apply = $value['apply-global-swatches'] ?? false;
				$has_specific_apply = !empty($value['apply-selective-swatches']);

				if ($has_global_apply || $has_specific_apply) {
					$keys[] = $key;
				}
			}
		}

		return $keys;
	}

	/**
	 * Get priority level for template application
	 * Higher number = higher priority
	 */
	private function get_template_priority($settings_key, $product_id, $selective_pages, $apply_global) {
		$is_specific_match = in_array('product_' . $product_id, $selective_pages);
		$is_global_match = $apply_global;
		
		if (!$is_specific_match && !$is_global_match) {
			return 0; // No match
		}
		
		// Determine base priority
		$base_priority = $is_specific_match ? 100 : 50; // Specific > Global
		
		// Add template-specific bonus
		if ($settings_key === 'shopg_product_swatches_settings_template1') {
			// Default template gets lowest bonus
			$template_bonus = 1;
		} else {
			// Custom templates get higher bonus
			// Extract template number/name for additional priority if needed
			preg_match('/template(\d+|[a-z]+)/', $settings_key, $matches);
			$template_bonus = 10; // Base bonus for custom templates
			
			// You can add specific logic here for different template priorities
			// For example: template1 gets higher priority than template2
			if (isset($matches[1])) {
				$template_identifier = $matches[1];
				// Add specific template priority logic here if needed
				// $template_bonus += (is_numeric($template_identifier) ? (10 - intval($template_identifier)) : 5);
			}
		}
		
		return $base_priority + $template_bonus;
	}

public function shopglut_save_product_swatches_layout() {
    
		// Clean nonce check
		if (!isset($_POST['shopg_productswatches_layouts_nonce'])) {
			wp_send_json_error('No nonce provided');
			return;
		}
		
		if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shopg_productswatches_layouts_nonce'])), 'shopg_productswatches_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		$layout_id = isset($_POST['shopg_shop_layoutid']) ? intval($_POST['shopg_shop_layoutid']) : 0;
		$layout_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';
		$layout_template = isset($_POST['layout_template']) ? sanitize_text_field(wp_unslash($_POST['layout_template'])) : '';
		
		// Handle JSON stringified data
		$layout_settings = array();
		if (isset($_POST['shopg_options_settings'])) {
			if (empty($_POST['shopg_options_settings'])) {
				$layout_settings = array(); // Keep as empty array if empty string
			} else {
				// Decode JSON string back to array
				$decoded_settings = json_decode(stripslashes(sanitize_text_field(wp_unslash($_POST['shopg_options_settings']))), true);
				
				// Check if JSON decoding was successful
				if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_settings)) {
					// Clean empty values from the array
					$layout_settings = $this->remove_empty_values($decoded_settings);
				} else {
					wp_send_json_error('Invalid JSON format in settings: ' . json_last_error_msg());
					return;
				}
			}
		}
	
		if (empty($layout_name) || empty($layout_template)) {
			wp_send_json_error('Required fields are missing');
			return;
		}

		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_productswatches_layout';

		// Validation for layout exclusivity - always query DB directly for accuracy
		$template_settings_key = 'shopg_product_swatches_settings_' . $layout_template;
		if (isset($layout_settings[$template_settings_key])) {
			$template_settings = $layout_settings[$template_settings_key];

			// Check if "Apply Globally" is being enabled (value is '1', 1, or true)
			$apply_global = isset($template_settings['apply-global-swatches']) ? $template_settings['apply-global-swatches'] : false;

			if ($apply_global == '1' || $apply_global === 1 || $apply_global === true) {
				// Directly query DB to check if any other layout already has "Apply Globally" enabled
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for validation
				$existing_layouts = $wpdb->get_results(
					$wpdb->prepare("SELECT id, layout_name, layout_template, layout_settings FROM `" . esc_sql($table_name) . "` WHERE id != %d", $layout_id),
					ARRAY_A
				);

				$conflicting_layout = null;
				foreach ($existing_layouts as $existing_layout) {
					$existing_settings = maybe_unserialize($existing_layout['layout_settings']);
					$existing_template_key = 'shopg_product_swatches_settings_' . $existing_layout['layout_template'];

					if (isset($existing_settings[$existing_template_key])) {
						$existing_template_settings = $existing_settings[$existing_template_key];

						// Check direct key - look for '1', 1, or true
						$existing_apply_global = isset($existing_template_settings['apply-global-swatches'])
							? $existing_template_settings['apply-global-swatches']
							: false;

						// Check if existing layout has it enabled
						if ($existing_apply_global == '1' || $existing_apply_global === 1 || $existing_apply_global === true) {
							$conflicting_layout = $existing_layout;
							break;
						}
					}
				}

				if ($conflicting_layout) {
					// Another layout already has "Apply Globally" enabled, force disable it for this layout
					$layout_settings[$template_settings_key]['apply-global-swatches'] = '0';
				}
			}

			// Check if "Apply Selective" has selections that conflict with a layout with "Apply Globally"
			$selective_pages = $template_settings['apply-selective-swatches'] ?? [];
			if (!empty($selective_pages)) {
				// Directly query DB - no cache
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for validation
				$existing_layouts = $wpdb->get_results(
					$wpdb->prepare("SELECT id, layout_name, layout_settings FROM `" . esc_sql($table_name) . "` WHERE id != %d", $layout_id),
					ARRAY_A
				);

				foreach ($existing_layouts as $existing_layout) {
					$existing_settings = maybe_unserialize($existing_layout['layout_settings']);
					$existing_template_key = 'shopg_product_swatches_settings_' . $existing_layout['layout_template'];

					if (isset($existing_settings[$existing_template_key])) {
						$existing_template_settings = $existing_settings[$existing_template_key];
						$existing_apply_global = $existing_template_settings['apply-global-swatches'] ?? false;

						if ($existing_apply_global) {
							wp_send_json_error('Cannot apply to specific pages because layout "' . esc_html($existing_layout['layout_name']) . '" (ID: ' . $existing_layout['id'] . ') already applies swatches globally.');
							return;
						}
					}
				}
			}
		}

		$data = array(
			'layout_name' => $layout_name,
			'layout_template' => $layout_template,
			'layout_settings' => serialize($layout_settings)
		);

		if ($layout_id > 0) {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->update(
				$table_name,
				$data,
				array('id' => $layout_id),
				array('%s', '%s', '%s'),
				array('%d')
			);
		} else {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->insert($table_name, $data, array('%s', '%s', '%s'));
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_id = $wpdb->insert_id;
		}

		if ($result === false) {
			wp_send_json_error(array(
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				'message' => 'Database error: ' . $wpdb->last_error,
				'debug' => array(
					'data' => $data,
					'layout_id' => $layout_id
				)
			));
			return;
		}

		// Clear apply global status cache when settings are saved
		wp_cache_delete('shopglut_apply_global_swatches_status', 'shopglut_swatches');
		wp_cache_delete('shopglut_productswatches_layouts_all', ''); // Clear cache used in switcher.php
		wp_cache_delete("shopglut_layout_data_{$layout_id}", 'shopglut_swatches');
		wp_cache_delete("shopglut_layout_template_{$layout_id}", 'shopglut_swatches');

		// Add a flag to indicate we're in preview mode
	define('SHOPGLUT_PREVIEW_MODE', true);
		$this->setup_preview_product_context();

		// Generate updated preview HTML
		$preview_html = $this->shopglut_render_singleplayout_preview($layout_id);

		wp_send_json_success(array(
			'message' => 'Layout saved successfully',
			'layout_id' => $layout_id,
			'reload' => false,
			'html' => $preview_html
		));
}

/**
 * Reset single product layout settings to empty values
 */
public function shopglut_reset_product_swatches_layout() {
	// Clean nonce check
	if (!isset($_POST['shopg_productswatches_layouts_nonce'])) {
		wp_send_json_error('No nonce provided');
		return;
	}

	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shopg_productswatches_layouts_nonce'])), 'shopg_productswatches_layouts')) {
		wp_send_json_error('Invalid nonce');
		return;
	}

	$layout_id = isset($_POST['shopg_shop_layoutid']) ? intval($_POST['shopg_shop_layoutid']) : 0;

	// Debug logging
	// error_log('Reset request - Layout ID received: ' . $layout_id);
	// error_log('Reset request - POST data: ' . print_r($_POST, true));

	if (!$layout_id) {
		wp_send_json_error('Layout ID is required');
		return;
	}

	global $wpdb;
	$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

	// Debug: Check what layouts exist
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$all_layouts = $wpdb->get_results("SELECT id, layout_name FROM `{$table_name}`");
	// error_log('All layouts in database: ' . print_r($all_layouts, true));

	// Check if layout exists
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$layout_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `{$table_name}` WHERE id = %d", $layout_id));

	// error_log('Layout exists check result: ' . $layout_exists);

	if (!$layout_exists) {
		wp_send_json_error('Layout not found - ID: ' . $layout_id);
		return;
	}

	// Reset layout_settings to empty array (serialized empty array)
	$empty_settings = serialize(array());

	// Update the layout with empty settings
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$result = $wpdb->update(
		$table_name,
		array(
			'layout_settings' => $empty_settings,
			'updated_at' => current_time('mysql')
		),
		array('id' => $layout_id),
		array('%s', '%s'),
		array('%d')
	);

	if ($result === false) {
		wp_send_json_error(array(
			'message' => 'Database error: ' . $wpdb->last_error,
			'layout_id' => $layout_id
		));
		return;
	}

	// Clear apply global status cache when settings are reset
	wp_cache_delete('shopglut_apply_global_swatches_status', 'shopglut_swatches');
	wp_cache_delete("shopglut_layout_data_{$layout_id}", 'shopglut_swatches');
	wp_cache_delete("shopglut_layout_template_{$layout_id}", 'shopglut_swatches');

	wp_send_json_success(array(
		'message' => 'Layout settings reset successfully',
		'layout_id' => $layout_id,
		'reload' => true
	));
}

/**
 * Recursively remove empty values and handle specific cases
 */
private function remove_empty_values($array) {
    if (!is_array($array)) {
        return $array;
    }
    
    $cleaned = array();
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            // Special handling for apply-selective-swatches
            if ($key === 'apply-selective-swatches') {
                $flattened = $this->flatten_empty_key_arrays($value);
                if (!empty($flattened)) {
                    $cleaned[$key] = $flattened;
                }
            } else {
                // Recursively clean nested arrays
                $cleaned_value = $this->remove_empty_values($value);
                
                // Only add if the cleaned array has meaningful content
                if (!empty($cleaned_value) && $this->has_meaningful_content($cleaned_value)) {
                    $cleaned[$key] = $cleaned_value;
                }
            }
        } else {
            // Only add non-empty values (but allow 0 and false)
            if ($value !== '' && $value !== null) {
                $cleaned[$key] = $value;
            }
        }
    }
    
    return $cleaned;
}


private function has_meaningful_content($array) {
    if (!is_array($array)) {
        return $array !== '' && $array !== null;
    }
    
    foreach ($array as $key => $value) {
        // Skip empty keys
        if ($key === '' || $key === null) {
            continue;
        }
        
        if (is_array($value)) {
            if ($this->has_meaningful_content($value)) {
                return true;
            }
        } else {
            if ($value !== '' && $value !== null) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Flatten arrays with empty keys
 */
private function flatten_empty_key_arrays($array) {
    $flattened = array();
    
    foreach ($array as $key => $value) {
        if ($key === '' || $key === null) {
            // If empty key contains an array, merge its contents
            if (is_array($value)) {
                $flattened = array_merge($flattened, $value);
            }
        } else {
            $flattened[$key] = $value;
        }
    }
    
    return $flattened;
}

		/**
	 * Set up a product context for preview rendering
	 */
	private function setup_preview_product_context() {
		global $product, $post;
		
		// If we already have a valid product, use it
		if ($product && is_a($product, 'WC_Product')) {
			return;
		}
		
		// Try to get a sample product for preview
		$sample_product_id = $this->get_sample_product_id();
		
		if ($sample_product_id) {
			$product = wc_get_product($sample_product_id);
			$post = get_post($sample_product_id);
			
			// Set up the global post data
			if ($post) {
				setup_postdata($post);
			}
			
			// Also set in GLOBALS for template access
			$GLOBALS['product'] = $product;
			$GLOBALS['post'] = $post;
		}
	}

	/**
	 * Get a sample product ID for preview
	 */
	private function get_sample_product_id() {
		// First try to get the most recent published product
		$recent_products = get_posts(array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'numberposts' => 1,
			'orderby' => 'date',
			'order' => 'ASC',
			'fields' => 'ids'
		));
		
		if (!empty($recent_products)) {
			return $recent_products[0];
		}
		
		// Fallback: try to get any published product
		$any_products = get_posts(array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields' => 'ids'
		));
		
		if (!empty($any_products)) {
			return $any_products[0];
		}
		
		return false;
	}


	/**
	 * Render tabs section for frontend - UPDATED to respect settings
	 */
	private function render_frontend_tabs($tab_settings, $product) {
		if (empty($tab_settings['tab-repeater'])) {
			return;
		}

		// Get global tab settings
		$global_tab_settings = $tab_settings['tab_global_settings'] ?? array();
		$tab_style = $global_tab_settings['tab_style'] ?? 'default';
		$tab_position = $global_tab_settings['tab_position'] ?? 'top';
		$tab_animation = $global_tab_settings['tab_animation'] ?? 'none';

		// Process each tab from settings
		$tabs_to_render = array();
		
		foreach ($tab_settings['tab-repeater'] as $tab_config) {


           $tab_data = $tab_config['shopg-tab-accordion']['opt-fieldset-tab'] ?? array();

			if (empty($tab_data)) continue;

			$tab_type = $tab_data['tab_type'] ?? '';
			$tab_title = $tab_data['accordion-title'] ?? '';

			// Generate tab content based on type
			$tab_content = $this->generate_tab_content($tab_type, $tab_data, $product);
			
			if (!empty($tab_content)) {
				$tabs_to_render[] = array(
					'id' => sanitize_title($tab_title),
					'title' => $tab_title,
					'content' => $tab_content,
					'type' => $tab_type,
				);
			}
		}

		if (empty($tabs_to_render)) {
			return;
		}

		// Generate unique ID for this tab container
		$container_id = 'shopglut-tabs-' . uniqid();

		// Render the tabs HTML
		$this->render_tabs_html($tabs_to_render, $container_id, $tab_style, $tab_position, $tab_animation);
	}

	/*
	 * Generate content for individual tab based on type
	 */
	private function generate_tab_content($tab_type, $tab_data, $product) {
		global $product;
		$original_product = $product;
		$GLOBALS['product'] = $product; // Ensure product context

		
		ob_start();

		switch ($tab_type) {
			case 'description':
				$this->render_description_tab($tab_data, $product);
				break;

			case 'additional_information':
				$this->render_additional_information_tab($tab_data, $product);
				break;

			case 'reviews':
				$this->render_reviews_tab($tab_data, $product);
				break;

			case 'custom':
				$this->render_custom_tab($tab_data, $product);
				break;

			case 'tabs':
				// This renders all default WooCommerce tabs
				$this->render_all_tabs($tab_data, $product);
				break;

			default:
				// Allow custom tab types via hook
				do_action('shopglut_render_custom_tab_type', $tab_type, $tab_data, $product);
				break;
		}

		$content = ob_get_clean();
		$GLOBALS['product'] = $original_product; // Restore original product

		return $content;
	}

	/**
	 * Render description tab content
	 */
	private function render_description_tab($settings, $product) {
		$show_title = $settings['show_title'] ?? true;
		$layout = $settings['description_layout'] ?? 'default';

		if ($show_title) {
			echo '<h3>' . esc_html__('Description', 'shopglut') . '</h3>';
		}

		$description = $product->get_description();
		
		if (empty($description)) {
			echo '<p>' . esc_html__('No description available.', 'shopglut') . '</p>';
			return;
		}

		switch ($layout) {
			case 'columns':
				echo '<div class="shopglut-description-columns">';
				echo '<div class="description-column-1">' . wp_kses_post(substr($description, 0, strlen($description)/2)) . '</div>';
				echo '<div class="description-column-2">' . wp_kses_post(substr($description, strlen($description)/2)) . '</div>';
				echo '</div>';
				break;

			case 'accordion':
				echo '<div class="shopglut-description-accordion">';
				echo '<details><summary>' . esc_html__('Full Description', 'shopglut') . '</summary>';
				echo wp_kses_post($description);
				echo '</details></div>';
				break;

			default:
				echo '<div class="shopglut-description-default">' . wp_kses_post($description) . '</div>';
				break;
		}
	}

	/**
	 * Render additional information tab content
	 */
	private function render_additional_information_tab($settings, $product) {
		$show_attributes = $settings['show_attributes'] ?? true;
		$show_dimensions = $settings['show_dimensions'] ?? true;
		$show_weight = $settings['show_weight'] ?? true;
		$layout = $settings['additional_info_layout'] ?? 'table';

		$attributes = $product->get_attributes();
		$has_dimensions = $product->has_dimensions();
		$has_weight = $product->has_weight();

		if (empty($attributes) && !$has_dimensions && !$has_weight) {
			echo '<p>' . esc_html__('No additional information available.', 'shopglut') . '</p>';
			return;
		}

		echo '<div class="shopglut-additional-info shopglut-layout-' . esc_attr($layout) . '">';

		switch ($layout) {
			case 'table':
				echo '<table class="shop_attributes">';
				$this->render_additional_info_rows($product, $show_attributes, $show_dimensions, $show_weight);
				echo '</table>';
				break;

			case 'list':
				echo '<dl class="shop_attributes_list">';
				$this->render_additional_info_list($product, $show_attributes, $show_dimensions, $show_weight);
				echo '</dl>';
				break;

			case 'grid':
				echo '<div class="shop_attributes_grid">';
				$this->render_additional_info_grid($product, $show_attributes, $show_dimensions, $show_weight);
				echo '</div>';
				break;
		}

		echo '</div>';
	}

	/**
	 * Render reviews tab content
	 */
	private function render_reviews_tab($settings, $product) {
		$show_rating = $settings['show_rating'] ?? true;
		$show_verified = $settings['show_verified'] ?? true;
		$layout = $settings['reviews_layout'] ?? 'list';
		$per_page = $settings['reviews_per_page'] ?? 10;

		// Get reviews
		$reviews = get_comments(array(
			'post_id' => $product->get_id(),
			'status' => 'approve',
			'type' => 'review',
			'number' => $per_page,
		));

		if (empty($reviews)) {
			echo '<p>' . esc_html__('No reviews yet.', 'shopglut') . '</p>';
			return;
		}

		echo '<div class="shopglut-reviews shopglut-reviews-' . esc_attr($layout) . '">';

		foreach ($reviews as $review) {
			echo '<div class="review-item">';
			
			if ($show_rating) {
				$rating = get_comment_meta($review->comment_ID, 'rating', true);
				if ($rating) {
					echo '<div class="review-rating">';
					for ($i = 1; $i <= 5; $i++) {
						echo $i <= $rating ? '★' : '☆';
					}
					echo '</div>';
				}
			}

			echo '<div class="review-author">' . esc_html($review->comment_author) . '</div>';
			echo '<div class="review-date">' . esc_html(get_comment_date('', $review)) . '</div>';
			
			if ($show_verified && wc_review_is_from_verified_owner($review->comment_ID)) {
				echo '<span class="verified-badge">' . esc_html__('Verified Purchase', 'shopglut') . '</span>';
			}

			echo '<div class="review-content">' . wp_kses_post($review->comment_content) . '</div>';
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Render custom tab content
	 */
	private function render_custom_tab($settings, $product) {
		$content = $settings['custom_content'] ?? '';
		$width = $settings['custom_content_width'] ?? 'full';

		if (empty($content)) {
			echo '<p>' . esc_html__('No custom content defined.', 'shopglut') . '</p>';
			return;
		}

		echo '<div class="shopglut-custom-content shopglut-width-' . esc_attr($width) . '">';
		echo wp_kses_post($content);
		echo '</div>';
	}

	/**
	 * Render all default WooCommerce tabs
	 */
	private function render_all_tabs($settings, $product) {
		$enable_description = $settings['enable_description_tab'] ?? true;
		$enable_additional_info = $settings['enable_additional_info_tab'] ?? true;
		$enable_reviews = $settings['enable_reviews_tab'] ?? true;
		$tabs_style = $settings['tabs_style'] ?? 'default';
		$tabs_position = $settings['tabs_position'] ?? 'top';

		// Get WooCommerce tabs
		$tabs = apply_filters('woocommerce_product_tabs', array());
		
		// Filter tabs based on settings
		if (!$enable_description) {
			unset($tabs['description']);
		}
		if (!$enable_additional_info) {
			unset($tabs['additional_information']);
		}
		if (!$enable_reviews) {
			unset($tabs['reviews']);
		}

		if (empty($tabs)) {
			return;
		}

		$container_id = 'woocommerce-tabs-' . uniqid();
		
		echo '<div class="woocommerce-tabs wc-tabs-wrapper shopglut-tabs-' . esc_attr($tabs_style) . ' shopglut-tabs-' . esc_attr($tabs_position) . '" id="' . esc_attr($container_id) . '">';
		
		// Render tab navigation
		echo '<ul class="tabs wc-tabs" role="tablist">';
		$active_set = false;
		foreach ($tabs as $key => $tab) {
			$active_class = !$active_set ? ' active' : '';
			$active_set = true;
			echo '<li class="' . esc_attr($key) . '_tab' . esc_attr($active_class) . '" id="tab-title-' . esc_attr($key) . '" role="tab" aria-controls="tab-' . esc_attr($key) . '">';
			echo '<a href="#tab-' . esc_attr($key) . '">' . wp_kses_post(apply_filters('woocommerce_product_' . esc_attr($key) . '_tab_title', $tab['title'], $key)) . '</a>';
			echo '</li>';
		}
		echo '</ul>';

		// Render tab content
		$active_set = false;
		foreach ($tabs as $key => $tab) {
			$active_class = !$active_set ? ' active' : '';
			$active_set = true;
			echo '<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--' . esc_attr($key) . ' panel entry-content wc-tab' . wp_kses_post($active_class) . '" id="tab-' . esc_attr($key) . '" role="tabpanel" aria-labelledby="tab-title-' . esc_attr($key) . '">';
			
			if (isset($tab['callback'])) {
				call_user_func($tab['callback'], $key, $tab);
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}

	/**
	 * Render the complete tabs HTML structure
	 */
	private function render_tabs_html($tabs, $container_id, $style, $position, $animation) {
		?>
		<div class="shopglut-tabs-container shopglut-tabs-<?php echo esc_attr($style); ?> shopglut-tabs-<?php echo esc_attr($position); ?> shopglut-animation-<?php echo esc_attr($animation); ?>" id="<?php echo esc_attr($container_id); ?>">
			
			<!-- Tab Navigation -->
			<ul class="shopglut-tab-nav">
				<?php foreach ($tabs as $index => $tab): ?>
					<li class="shopglut-tab-item<?php echo $index === 0 ? ' active' : ''; ?>">
						<a href="#<?php echo esc_attr($tab['id']); ?>" data-tab="<?php echo esc_attr($tab['id']); ?>">
							<?php echo esc_html($tab['title']); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<!-- Tab Content -->
			<div class="shopglut-tab-content">
				<?php foreach ($tabs as $index => $tab): ?>
					<div id="<?php echo esc_attr($tab['id']); ?>" class="shopglut-tab-pane<?php echo $index === 0 ? ' active' : ''; ?>" data-type="<?php echo esc_attr($tab['type']); ?>">
						<?php echo wp_kses_post($tab['content']); ?>
					</div>
				<?php endforeach; ?>
			</div>

		</div>

		<style>
		/* Basic Tab Styles */
		.shopglut-tabs-container {
			margin: 20px 0;
		}

		.shopglut-tab-nav {
			display: flex;
			list-style: none;
			margin: 0;
			padding: 0;
			border-bottom: 1px solid #ddd;
		}

		.shopglut-tab-item {
			margin: 0;
		}

		.shopglut-tab-item a {
			display: block;
			padding: 12px 20px;
			text-decoration: none;
			color: #666;
			border-bottom: 2px solid transparent;
			transition: all 0.3s ease;
		}

		.shopglut-tab-item.active a,
		.shopglut-tab-item a:hover {
			color: #333;
			border-bottom-color: #0073aa;
		}

		.shopglut-tab-content {
			padding: 20px 0;
		}

		.shopglut-tab-pane {
			display: none;
		}

		.shopglut-tab-pane.active {
			display: block;
		}

		/* Tab Styles */
		.shopglut-tabs-minimal .shopglut-tab-nav {
			border-bottom: none;
		}

		.shopglut-tabs-minimal .shopglut-tab-item a {
			background: #f5f5f5;
			margin-right: 5px;
			border-radius: 4px 4px 0 0;
		}

		.shopglut-tabs-modern .shopglut-tab-item a {
			;
			color: white;
			margin-right: 10px;
			border-radius: 20px;
			border-bottom: none;
		}

		.shopglut-tabs-pills .shopglut-tab-item a {
			background: #e9ecef;
			border-radius: 20px;
			margin-right: 10px;
			border-bottom: none;
		}

		.shopglut-tabs-pills .shopglut-tab-item.active a {
			background: #007cba;
			color: white;
		}

		/* Position Styles */
		.shopglut-tabs-left,
		.shopglut-tabs-right {
			display: flex;
		}

		.shopglut-tabs-left .shopglut-tab-nav,
		.shopglut-tabs-right .shopglut-tab-nav {
			flex-direction: column;
			width: 200px;
			border-bottom: none;
			border-right: 1px solid #ddd;
		}

		.shopglut-tabs-right .shopglut-tab-nav {
			border-right: none;
			border-left: 1px solid #ddd;
			order: 2;
		}

		.shopglut-tabs-left .shopglut-tab-content,
		.shopglut-tabs-right .shopglut-tab-content {
			flex: 1;
			padding: 0 20px;
		}

		/* Animations */
		.shopglut-animation-fade .shopglut-tab-pane {
			opacity: 0;
			transition: opacity 0.3s ease;
		}

		.shopglut-animation-fade .shopglut-tab-pane.active {
			opacity: 1;
		}

		.shopglut-animation-slide .shopglut-tab-pane {
			transform: translateX(-20px);
			transition: transform 0.3s ease;
		}

		.shopglut-animation-slide .shopglut-tab-pane.active {
			transform: translateX(0);
		}

		/* Responsive */
		@media (max-width: 768px) {
			.shopglut-tabs-left,
			.shopglut-tabs-right {
				flex-direction: column;
			}

			.shopglut-tabs-left .shopglut-tab-nav,
			.shopglut-tabs-right .shopglut-tab-nav {
				flex-direction: row;
				width: auto;
				border: none;
				border-bottom: 1px solid #ddd;
			}

			.shopglut-tab-nav {
				flex-wrap: wrap;
			}

			.shopglut-tab-item a {
				padding: 8px 12px;
				font-size: 14px;
			}
		}
		</style>

		<script>
		jQuery(document).ready(function($) {
			$('#<?php echo esc_js($container_id); ?> .shopglut-tab-nav a').on('click', function(e) {
				e.preventDefault();
				
				var $this = $(this);
				var target = $this.attr('href');
				var $container = $this.closest('.shopglut-tabs-container');
				
				// Remove active class from all tabs and content
				$container.find('.shopglut-tab-item').removeClass('active');
				$container.find('.shopglut-tab-pane').removeClass('active');
				
				// Add active class to clicked tab and corresponding content
				$this.parent().addClass('active');
				$container.find(target).addClass('active');
			});
		});
		</script>
		<?php
	}

	/**
	 * Helper methods for additional information rendering
	 */
	private function render_additional_info_rows($product, $show_attributes, $show_dimensions, $show_weight) {
		if ($show_weight && $product->has_weight()) {
			echo '<tr><th>' . esc_html__('Weight', 'shopglut') . '</th><td>' . esc_html($product->get_weight()) . ' ' . esc_attr(get_option('woocommerce_weight_unit')) . '</td></tr>';
		}

		if ($show_dimensions && $product->has_dimensions()) {
			echo '<tr><th>' . esc_html__('Dimensions', 'shopglut') . '</th><td>' . esc_html($product->get_dimensions(false)) . '</td></tr>';
		}

		if ($show_attributes) {
			foreach ($product->get_attributes() as $attribute) {
				if ($attribute->get_visible()) {
					echo '<tr><th>' . esc_html(wc_attribute_label($attribute->get_name())) . '</th><td>' . wp_kses_post($product->get_attribute($attribute->get_name())) . '</td></tr>';
				}
			}
		}
	}

	private function render_additional_info_list($product, $show_attributes, $show_dimensions, $show_weight) {
		if ($show_weight && $product->has_weight()) {
			echo '<dt>' . esc_html__('Weight', 'shopglut') . '</dt><dd>' . esc_html($product->get_weight()) . ' ' . esc_attr(get_option('woocommerce_weight_unit')) . '</dd>';
		}

		if ($show_dimensions && $product->has_dimensions()) {
			echo '<dt>' . esc_html__('Dimensions', 'shopglut') . '</dt><dd>' . esc_html($product->get_dimensions(false)) . '</dd>';
		}

		if ($show_attributes) {
			foreach ($product->get_attributes() as $attribute) {
				if ($attribute->get_visible()) {
					echo '<dt>' . esc_html(wc_attribute_label($attribute->get_name())) . '</dt><dd>' . wp_kses_post($product->get_attribute($attribute->get_name())) . '</dd>';
				}
			}
		}
	}

	private function render_additional_info_grid($product, $show_attributes, $show_dimensions, $show_weight) {
		if ($show_weight && $product->has_weight()) {
			echo '<div class="attribute-item"><span class="label">' . esc_html__('Weight', 'shopglut') . '</span><span class="value">' . esc_html($product->get_weight()) . ' ' . esc_attr(get_option('woocommerce_weight_unit')) . '</span></div>';
		}

		if ($show_dimensions && $product->has_dimensions()) {
			echo '<div class="attribute-item"><span class="label">' . esc_html__('Dimensions', 'shopglut') . '</span><span class="value">' . esc_html($product->get_dimensions(false)) . '</span></div>';
		}

		if ($show_attributes) {
			foreach ($product->get_attributes() as $attribute) {
				if ($attribute->get_visible()) {
					echo '<div class="attribute-item"><span class="label">' . esc_html(wc_attribute_label($attribute->get_name())) . '</span><span class="value">' . wp_kses_post($product->get_attribute($attribute->get_name())) . '</span></div>';
				}
			}
		}
	}

	/**
	 * Render related products for frontend
	 */
	private function render_frontend_related_products($settings, $product) {
    // Get related products
    $related_products = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $settings['products_count'] ?? 4)), 'wc_products_array_filter_visible');
    
    if (empty($related_products)) {
        return; // No related products found
    }
    
    $GLOBALS['shopglut_related_settings'] = array(
        'title' => $settings['related_title'] ?? 'Related Products',
        'count' => $settings['products_count'] ?? 4,
        'columns' => $settings['columns'] ?? 4,
        'show_price' => $settings['related_show_price'] ?? true,
        'show_rating' => $settings['related_show_rating'] ?? true,
        'show_add_to_cart' => $settings['related_show_add_to_cart'] ?? true,
        'layout_style' => $settings['related_layout_style'] ?? 'grid',
    );
    
    // Make related products available to the template
    $GLOBALS['related_products'] = $related_products;
    
    $template_path = plugin_dir_path(__FILE__) . 'templates/single-product/related.php';
    if (file_exists($template_path)) {
        include $template_path;
    }
    
    unset($GLOBALS['shopglut_related_settings']);
    unset($GLOBALS['related_products']);
}

	/**
	 * Render upsells for frontend
	 */
	private function render_frontend_upsells($settings, $product) {
    // Get upsell products
    $upsell_ids = $product->get_upsell_ids();
    $upsells = array_filter(array_map('wc_get_product', $upsell_ids), 'wc_products_array_filter_visible');
    
    // Limit the number of upsells if count is specified
    $count = $settings['upsells_count'] ?? 4;
    if ($count > 0) {
        $upsells = array_slice($upsells, 0, $count);
    }

    
    if (empty($upsells)) {
        return; // No upsells found
    }
    
    $GLOBALS['shopglut_upsells_settings'] = array(
        'title' => $settings['upsells_title'] ?? 'You may also like…',
        'count' => $settings['upsells_count'] ?? 4,
        'columns' => $settings['upsells_columns'] ?? 4,
    );
    
    // Make upsells available to the template
    $GLOBALS['upsells'] = $upsells;

    
    $template_path = plugin_dir_path(__FILE__) . 'templates/single-product/up-sells.php';
    if (file_exists($template_path)) {
        include $template_path;
    }
    
    unset($GLOBALS['shopglut_upsells_settings']);
    unset($GLOBALS['upsells']);
}
	/**
	 * Generate CSS for frontend sections
	 */
	private function generate_frontend_section_css($settings, $unique_id) {
		$css = array();
		
		// Section spacing
		if (!empty($settings['section_padding'])) {
			$padding = $settings['section_padding'];
			$css[] = sprintf(
				'padding: %spx %spx %spx %spx',
				$padding['top'] ?? 0,
				$padding['right'] ?? 0,
				$padding['bottom'] ?? 0,
				$padding['left'] ?? 0
			);
		}

		if (!empty($settings['section_margin'])) {
			$margin = $settings['section_margin'];
			$css[] = sprintf(
				'margin: %spx %spx %spx %spx',
				$margin['top'] ?? 0,
				$margin['right'] ?? 0,
				$margin['bottom'] ?? 0,
				$margin['left'] ?? 0
			);
		}

		// Section-specific CSS
		switch ($settings['section_type'] ?? '') {
			case 'title':
				if (!empty($settings['font_size'])) {
					$css[] = 'font-size: ' . $settings['font_size'] . 'px !important';
				}
				if (!empty($settings['font_weight'])) {
					$css[] = 'font-weight: ' . $settings['font_weight'] . ' !important';
				}
				if (!empty($settings['text_color'])) {
					$css[] = 'color: ' . $settings['text_color'] . ' !important';
				}
				if (!empty($settings['margin_top'])) {
					$css[] = 'margin-top: ' . $settings['margin_top'] . 'px !important';
				}
				if (!empty($settings['margin_bottom'])) {
					$css[] = 'margin-bottom: ' . $settings['margin_bottom'] . 'px !important';
				}
				break;
				
			case 'breadcrumb':
				if (!empty($settings['breadcrumb_font_size'])) {
					$css[] = 'font-size: ' . $settings['breadcrumb_font_size'] . 'px';
				}
				if (!empty($settings['breadcrumb_text_color'])) {
					$css[] = 'color: ' . $settings['breadcrumb_text_color'].' !important';
				}
				
				break;
		}

		if (!empty($css)) {
			return '#' . $unique_id . ' { ' . implode('; ', $css) . ' }';
		}

		return '';
	}

	/**
	 * Custom category implementation
	 */
     private function render_custom_category($settings, $product) {
	
		// Get category-specific settings with defaults
		$show_title = $settings['show_category_title'] ?? true;
		$category_font_size = isset($settings['category_font_size']) ? intval($settings['category_font_size']) : 14;
		$category_text_color = isset($settings['category_text_color']) ? $settings['category_text_color'] : '#333333';
		$category_link_color = isset($settings['category_link_color']) ? $settings['category_link_color'] : '#1e73be';
		
		// Get padding and margin settings
		$section_padding = isset($settings['section_padding']) ? $settings['section_padding'] : array();
		$section_margin = isset($settings['section_margin']) ? $settings['section_margin'] : array();
		
		// Parse padding values
		$padding_top = isset($section_padding['top']) ? intval($section_padding['top']) : 0;
		$padding_right = isset($section_padding['right']) ? intval($section_padding['right']) : 0;
		$padding_bottom = isset($section_padding['bottom']) ? intval($section_padding['bottom']) : 0;
		$padding_left = isset($section_padding['left']) ? intval($section_padding['left']) : 0;
		$padding_unit = isset($section_padding['unit']) ? $section_padding['unit'] : 'px';
		
		// Parse margin values
		$margin_top = isset($section_margin['top']) ? intval($section_margin['top']) : 0;
		$margin_right = isset($section_margin['right']) ? intval($section_margin['right']) : 0;
		$margin_bottom = isset($section_margin['bottom']) ? intval($section_margin['bottom']) : 0;
		$margin_left = isset($section_margin['left']) ? intval($section_margin['left']) : 0;
		$margin_unit = isset($section_margin['unit']) ? $section_margin['unit'] : 'px';
		
		$categories = get_the_terms($product->get_id(), 'product_cat');
		if ($categories && !is_wp_error($categories)) {
			?>
			<style>
			.shopglut-product-categories {
				margin: <?php echo esc_attr($margin_top . $margin_unit . ' ' . $margin_right . $margin_unit . ' ' . $margin_bottom . $margin_unit . ' ' . $margin_left . $margin_unit); ?>;
				padding: <?php echo esc_attr($padding_top . $padding_unit . ' ' . $padding_right . $padding_unit . ' ' . $padding_bottom . $padding_unit . ' ' . $padding_left . $padding_unit); ?>;
				font-size: <?php echo esc_attr($category_font_size); ?>px;
				line-height: 1.4;
			}
			
			.shopglut-product-categories .category-label {
				color: <?php echo esc_attr($category_text_color); ?> !important;
				font-weight: 500;
				margin-right: 5px;
			}
			
			.shopglut-product-categories a {
				color: <?php echo esc_attr($category_link_color); ?> !important;
				text-decoration: none;
				transition: color 0.3s ease, opacity 0.3s ease;
			}
			
			.shopglut-product-categories a:hover {
				opacity: 0.8;
				text-decoration: underline;
			}
			
			.shopglut-product-categories a:focus {
				outline: 2px solid <?php echo esc_attr($category_link_color); ?>;
				outline-offset: 2px;
			}
			</style>
			<?php
			
			echo '<div class="shopglut-product-categories">';
			
			if ($show_title) {
				echo '<span class="category-label">' . esc_html__('Category:', 'shopglut') . ' </span>';
			}
			
			$cat_links = array();
			foreach ($categories as $category) {
				$cat_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
			}
			
			echo implode(', ', wp_kses_post($cat_links));
			echo '</div>';
		}
}

	/**
	 * Ensure product context is properly set before loading templates
	 */
	private function ensure_product_context() {
		global $product, $post;
		
		// If we already have a valid product, return
		if ($product && is_a($product, 'WC_Product')) {
			return true;
		}
		
		// Try to get the product from current context
		$product = wc_get_product();
		
		// If still no product, try from global post
		if (!$product && $post && $post->post_type === 'product') {
			$product = wc_get_product($post->ID);
		}
		
		// Final check - if we still don't have a product, this is a problem
		if (!$product || !is_a($product, 'WC_Product')) {
			//error_log('ShopGlut: Unable to load product context for template rendering');
			return false;
		}
		
		// Set the global product
		$GLOBALS['product'] = $product;
		return true;
	}

	/**
	 * Safe template loader that ensures product context
	 */
	private function load_template_safely($template_file) {
		
		// Check if file exists
		if (!file_exists($template_file)) {
			//error_log('ShopGlut: Template file not found: ' . $template_file);
			return false;
		}
		
		// Include the template
		include $template_file;
		return true;
	}

	/**
	 * Render add-to-cart with safety checks
	 */
	private function render_add_to_cart_by_type_safely($settings, $template_path) {
		global $product;
		
		// if (!$this->ensure_product_context()) {
		// 	return;
		// }
		
		$product_type = $product->get_type();
		
		switch ($product_type) {
			case 'simple':
				$this->load_template_safely($template_path . 'add-to-cart/simple.php');
				break;
			case 'variable':
				$this->load_template_safely($template_path . 'add-to-cart/variable.php');
				break;
			case 'grouped':
				$this->load_template_safely($template_path . 'add-to-cart/grouped.php');
				break;
			case 'external':
				$this->load_template_safely($template_path . 'add-to-cart/external.php');
				break;
			default:
				$this->load_template_safely($template_path . 'add-to-cart/simple.php');
				break;
		}
	}


	/**
	 * Helper method to get column width
	 */
	private function get_column_width($global_settings, $key, $default = 50) {
		if (isset($global_settings[$key]) && is_array($global_settings[$key])) {
			return (int) ($global_settings[$key][$key] ?? $default);
		} elseif (isset($global_settings[$key]) && is_numeric($global_settings[$key])) {
			return (int) $global_settings[$key];
		}
		return $default;
	}



	/**
	 * Render three column layout
	 */
	private function render_three_column_layout($layout_settings, $product) {
		$column_settings = $layout_settings['product-column-section-3col'] ?? array();
		
		echo '<div class="shopglut-product-columns">';
		
		// Left Column
		echo '<div class="shopglut-left-column">';
		$this->render_column_content_3col($column_settings, 'left', $product);
		echo '</div>';
		
		// Middle Column
		echo '<div class="shopglut-middle-column">';
		$middle_sections = $column_settings['middle-column-repeater'] ?? array();
		foreach ($middle_sections as $section) {
			$section_settings = $section['shopg-middle-accordion']['middle-column-fieldset'] ?? array();
			if (!empty($section_settings)) {
				$this->render_frontend_section($section_settings, $product);
			}
		}
		echo '</div>';
		
		// Right Column
		echo '<div class="shopglut-right-column">';
		$this->render_column_content_3col($column_settings, 'right', $product);
		echo '</div>';
		
		echo '</div>';
	}

	/**
	 * Render column content for two-column layout
	 */
	private function render_column_content($column_settings, $side, $product) {
		$column_type = $column_settings["{$side}_column_settings"]["{$side}_column_type"] ?? 'content';
		
		if ($column_type === 'sidebar') {
			// Render sidebar widgets
			$sections = $column_settings["{$side}-sidebar-repeater"] ?? array();
			foreach ($sections as $section) {
				// Try different possible accordion keys
				$section_settings = null;
				if (isset($section['shopg-sidebar-accordion']["{$side}-sidebar-fieldset"])) {
					$section_settings = $section['shopg-sidebar-accordion']["{$side}-sidebar-fieldset"];
				} elseif (isset($section['shopg-sidebar-accordion-right']["{$side}-sidebar-fieldset"])) {
					$section_settings = $section['shopg-sidebar-accordion-right']["{$side}-sidebar-fieldset"];
				}
				
				if (!empty($section_settings)) {
					$this->render_sidebar_widget($section_settings, $product);
				}
			}
		} else {
			// Render content sections
			$sections = $column_settings["{$side}-column-repeater"] ?? array();
			foreach ($sections as $section) {
				// Try different possible accordion keys for content
				$section_settings = null;
				if (isset($section['shopg-content-accordion']["{$side}-content-fieldset"])) {
					$section_settings = $section['shopg-content-accordion']["{$side}-content-fieldset"];
				} elseif (isset($section['shopg-content-accordion-right']["{$side}-content-fieldset"])) {
					$section_settings = $section['shopg-content-accordion-right']["{$side}-content-fieldset"];
				} elseif (isset($section['shopg-filter-accordion']["{$side}-column-fieldset"])) {
					// Fallback to original structure
					$section_settings = $section['shopg-filter-accordion']["{$side}-column-fieldset"];
				}
				
				if (!empty($section_settings)) {
					$this->render_frontend_section($section_settings, $product);
				}
			}
		}
	}

	/**
	 * Render column content for three-column layout
	 */
	private function render_column_content_3col($column_settings, $side, $product) {
		$column_type = $column_settings["{$side}_column_settings_3col"]["{$side}_column_type_3col"] ?? 'content';
		
		if ($column_type === 'sidebar') {
			// Render sidebar widgets
			$sections = $column_settings["{$side}-sidebar-repeater-3col"] ?? array();
			foreach ($sections as $section) {
				if ($side === 'left') {
					$section_settings = $section['shopg-sidebar-accordion-3col']["{$side}-sidebar-fieldset-3col"] ?? array();
				} else {
					$section_settings = $section['shopg-sidebar-accordion-3col-right']["{$side}-sidebar-fieldset-3col"] ?? array();
				}
				if (!empty($section_settings)) {
					$this->render_sidebar_widget($section_settings, $product);
				}
			}
		} else {
			// Render content sections
			$sections = $column_settings["{$side}-column-repeater-3col"] ?? array();
			foreach ($sections as $section) {
				if ($side === 'left') {
					$section_settings = $section['shopg-content-accordion-3col']["{$side}-content-fieldset-3col"] ?? array();
				} else {
					$section_settings = $section['shopg-content-accordion-3col-right']["{$side}-content-fieldset-3col"] ?? array();
				}
				if (!empty($section_settings)) {
					$this->render_frontend_section($section_settings, $product);
				}
			}
		}
	}

	/**
	 * Render sidebar widget
	 */
	private function render_sidebar_widget($settings, $product) {
		$widget_type = $settings['section_type'] ?? '';
		$widget_title = $settings['accordion-title'] ?? '';
		$unique_id = 'shopglut-widget-' . uniqid();
		
		echo '<div id="' . esc_attr($unique_id) . '" class="shopglut-sidebar-widget shopglut-widget-' . esc_attr($widget_type) . '">';
		
		if ($widget_title) {
			echo '<h3>' . esc_html($widget_title) . '</h3>';
		}
		
		// Set global settings for template files
		$GLOBALS['shopglut_current_section_settings'] = $settings;
		
		// Get template path
		$template_path = plugin_dir_path(__FILE__) . 'templates/sidebar-widgets/';
		
		switch ($widget_type) {
			case 'category_widget':
				$this->load_template_safely($template_path . 'category-widget.php');
				break;
				
			case 'popular_products':
				$this->load_template_safely($template_path . 'popular-products.php');
				break;
				
			case 'recent_products':
				$this->load_template_safely($template_path . 'recent-products.php');
				break;
				
			case 'featured_products':
				$this->load_template_safely($template_path . 'featured-products.php');
				break;
				
			case 'sale_products':
				$this->load_template_safely($template_path . 'sale-products.php');
				break;
				
			case 'product_tags':
				$this->load_template_safely($template_path . 'product-tags.php');
				break;
				
			case 'product_search':
				$this->load_template_safely($template_path . 'product-search.php');
				break;
				
			// case 'newsletter_signup':
			// 	$this->load_template_safely($template_path . 'newsletter-signup.php');
			// 	break;
				
			case 'custom_widget':
				echo do_shortcode(wp_kses_post($settings['custom_widget_content'] ?? ''));
				break;
				
			case 'social_links':
				$this->load_template_safely($template_path . 'social-links.php');
				break;
				
			case 'testimonials':
				$this->load_template_safely($template_path . 'testimonials.php');
				break;
				
			case 'promotional_banner':
				$this->load_template_safely($template_path . 'promotional-banner.php');
				break;
				
			default:
				echo '<p>' . esc_html__('Widget type not found: ', 'shopglut') . esc_html($widget_type) . '</p>';
				break;
		}
		
		echo '</div>';
		
		// Clear settings
		unset($GLOBALS['shopglut_current_section_settings']);
	}




/**
 * ENHANCED: Better error handling for frontend section rendering
 */
private function render_frontend_section($settings, $product) {
    $section_type = $settings['section_type'] ?? '';
    
    if (empty($section_type)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo '<div class="shopglut-debug-error">Section type not defined</div>';
        }
        return;
    }
    
    $unique_id = 'shopglut-section-' . uniqid();
    
    // Check if this is a sidebar widget type
    $sidebar_widgets = array(
        'category_widget', 'popular_products', 'recent_products', 'featured_products',
        'sale_products', 'product_tags', 'product_search', 'newsletter_signup',
        'custom_widget', 'social_links', 'testimonials', 'promotional_banner'
    );
    
    if (in_array($section_type, $sidebar_widgets)) {
        $this->render_sidebar_widget($settings, $product);
        return;
    }
    
    // Generate section CSS
    $section_css = $this->generate_frontend_section_css($settings, $unique_id);
    if ($section_css) {
        echo '<style>' . wp_kses_post($section_css) . '</style>';
    }
    
    // Section wrapper
    $wrapper_classes = array('shopglut-section', 'shopglut-section-' . $section_type);
    if (!empty($settings['section_width'])) {
        $wrapper_classes[] = 'shopglut-section-' . $settings['section_width'];
    }
    
    echo '<div id="' . esc_attr($unique_id) . '" class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';
    
    // Set global settings for template files
    $GLOBALS['shopglut_current_section_settings'] = $settings;
    
    // Get template path
    $template_path = plugin_dir_path(__FILE__) . 'templates/single-product/';
    
    switch ($section_type) {
        case 'product_images':
            $this->load_template_safely($template_path . 'product-image.php');
            break;
            
        case 'title':
            $this->load_template_safely($template_path . 'title.php');
            break;
            
        case 'price':
            $this->load_template_safely($template_path . 'price.php');
            break;
            
        case 'rating':
            $this->load_template_safely($template_path . 'rating.php');
            break;
            
        case 'short_description':
            $this->load_template_safely($template_path . 'short-description.php');
            break;
            
        case 'stock':
            $this->load_template_safely($template_path . 'stock.php');
            break;
            
        case 'sale_flash':
            $this->load_template_safely($template_path . 'sale-flash.php');
            break;
            
        case 'meta':
            $this->load_template_safely($template_path . 'meta.php');
            break;
            
        case 'product_attributes':
            $this->load_template_safely($template_path . 'product-attributes.php');
            break;
            
        case 'share':
            $this->load_template_safely($template_path . 'share.php');
            break;
            
        case 'review_meta':
            $this->load_template_safely($template_path . 'review-meta.php');
            break;
            
        case 'review':
            $this->load_template_safely($template_path . 'review.php');
            break;
            
        case 'add_to_cart':
            $this->render_add_to_cart_by_type_safely($settings, $template_path);
            break;
            
        case 'breadcrumb':
            $this->load_template_safely($template_path . 'breadcrumb.php');
            break;
            
        case 'category':
            $this->render_custom_category($settings, $product);
            break;
            
        case 'custom_html':
            echo do_shortcode(wp_kses_post($settings['custom_html_content'] ?? ''));
            break;
            
        case 'related':
            $this->load_template_safely($template_path . 'related.php');
            break;
            
        case 'up_sells':
            $this->load_template_safely($template_path . 'up-sells.php');
            break;
            
        default:
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo '<div class="shopglut-debug-error">Unknown section type: ' . esc_html($section_type) . '</div>';
            }
            // Allow custom section types via hook
            do_action('shopglut_render_custom_section_type', $section_type, $settings, $product);
            break;
    }
    
    echo '</div>';
    
    // Clear settings
    unset($GLOBALS['shopglut_current_section_settings']);
}

/**
 * ENHANCED: Better debugging function
 */
private function debug_layout_structure($layout_settings, $layout_type) {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    
        
    switch ($layout_type) {
        case 'single_column':
            $single_section = $layout_settings['single-column-section'] ?? 'NOT_FOUND';
                        if (is_array($single_section)) {
                //error_log('Single Column Keys: ' . implode(', ', array_keys($single_section)));
            }
            break;
            
        case 'two_column':
            $column_section = $layout_settings['product-column-section'] ?? 'NOT_FOUND';
                        break;
    }
    }



/**
 * CORRECTED: Generate CSS based on actual settings values
 */
private function generate_layout_css($layout_settings, $layout_type) {
    $global_settings = $layout_settings['global_settings'] ?? array();
    $css_rules = array();
    
    // Container Width Settings
    $container_width = $global_settings['container_width'] ?? 'boxed';
    
    switch ($container_width) {
        case 'full':
            $css_rules[] = '.shopglut-container-full { width: 100%; padding: 0 20px; }';
            break;
        case 'wide':
            $css_rules[] = '.shopglut-container-wide { max-width: 1400px; margin: 0 auto; padding: 0 20px; }';
            break;
        case 'narrow':
            $css_rules[] = '.shopglut-container-narrow { max-width: 1000px; margin: 0 auto; padding: 0 20px; }';
            break;
        case 'boxed':
        default:
            $css_rules[] = '.shopglut-container-boxed { max-width: 1200px; margin: 0 auto; padding: 0 20px; }';
            break;
    }
    
    // Layout-specific CSS based on settings
    switch ($layout_type) {
        case 'single_column':
            $single_width = $this->get_setting_value($global_settings, 'single_column_width', 100);
            $css_rules[] = ".shopglut-single-column { max-width: {$single_width}%; margin: 0 auto; }";
            break;
            
       case 'two_column':
			$column_gap = (int) $this->get_setting_value($global_settings, 'column_gap', 30);
			$left_width = (int) $this->get_setting_value($global_settings, 'left_column_width', 50);
			$right_width = (int) $this->get_setting_value($global_settings, 'right_column_width', 50);
			
			// Ensure widths add up correctly
			if ($left_width + $right_width > 100) {
				$right_width = 100 - $left_width;
			}
			
    // Rest of your code...
            $css_rules[] = ".shopglut-product-columns { display: flex; gap: {$column_gap}px; align-items: flex-start; }";
            $css_rules[] = ".shopglut-left-column { width: {$left_width}%; }";
            $css_rules[] = ".shopglut-right-column { width: {$right_width}%; }";
            break;
            
        case 'three_column':
            // Add three column support if needed
            $column_gap = $this->get_setting_value($global_settings, 'column_gap_3col', 20);
            $left_width = $this->get_setting_value($global_settings, 'left_column_width_3col', 25);
            $middle_width = $this->get_setting_value($global_settings, 'middle_column_width_3col', 50);
            $right_width = $this->get_setting_value($global_settings, 'right_column_width_3col', 25);
            
            $css_rules[] = ".shopglut-product-columns { display: flex; gap: {$column_gap}px; align-items: flex-start; }";
            $css_rules[] = ".shopglut-left-column { width: {$left_width}%; }";
            $css_rules[] = ".shopglut-middle-column { width: {$middle_width}%; }";
            $css_rules[] = ".shopglut-right-column { width: {$right_width}%; }";
            break;
    }
    
    return implode("\n", $css_rules);
}

/**
 * Helper function to extract setting values from complex structures
 */
private function get_setting_value($settings, $key, $default = null) {
    if (!isset($settings[$key])) {
        return $default;
    }
    
    $value = $settings[$key];
    
    // Handle slider/complex field structures like:
    // Array ( [left_column_width-unit] => % [left_column_width] => 60 )
    if (is_array($value)) {
        // Try to get the actual value (without unit suffix)
        if (isset($value[$key])) {
            return $value[$key];
        }
        
        // Fallback: look for any numeric value in the array
        foreach ($value as $v) {
            if (is_numeric($v)) {
                return $v;
            }
        }
        
        return $default;
    }
    
    return $value;
}

/**
 * CORRECTED: Generate column-specific CSS based on settings
 */
private function generate_column_css($column_settings, $column_type) {
    $css_rules = array();
    
    // Background color
    $bg_key = $column_type . '_column_bg_color';
    if (!empty($column_settings[$bg_key])) {
        $css_rules[] = "background-color: {$column_settings[$bg_key]};";
    }
    
    // Padding
    $padding_key = $column_type . '_column_padding';
    if (!empty($column_settings[$padding_key])) {
        $padding = $column_settings[$padding_key];
        if (is_array($padding)) {
            $top = $padding['top'] ?? 0;
            $right = $padding['right'] ?? 0;
            $bottom = $padding['bottom'] ?? 0;
            $left = $padding['left'] ?? 0;
            $unit = $padding['unit'] ?? 'px';
            
            if ($top || $right || $bottom || $left) {
                $css_rules[] = "padding: {$top}{$unit} {$right}{$unit} {$bottom}{$unit} {$left}{$unit};";
            }
        }
    }
    
    return implode(' ', $css_rules);
}

public function get_latest_woo_product_id() {
    global $post;
    
    // Try to get the latest published product
    $latest_products = wc_get_products(array(
        'limit' => 1,
        'orderby' => 'date',
        'order' => 'ASC',
        'status' => 'publish'
    ));
    
    if (!empty($latest_products)) {
        return $latest_products[0]->get_id();
    }
    
    // Fallback to current post/page ID if no products found
    return $post ? $post->ID : get_the_ID();
}

public function render_frontend_layout($layout_id) {
    global $wpdb, $product, $post;
    
    // Get layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $layout_options = maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT layout_settings FROM `{$table_name}` WHERE id = %d", $layout_id)));
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $layout_template = $wpdb->get_var($wpdb->prepare("SELECT layout_template FROM `{$table_name}` WHERE id = %d", $layout_id));

    $layout_settings = $layout_options['shopg_product_swatches_settings_template1'] ?? array();
    $layout_settings_template = $layout_options['shopg_product_swatches_settings_'.$layout_template] ?? array();
    
    // Get current product ID for validation
    $current_product_id = $this->get_latest_woo_product_id();
    
    // Extract layout type
    $layout_type = $layout_settings['layout_type'] ?? 'two_column';
    $global_settings = $layout_settings['global_settings'] ?? array();
    $tab_settings = $layout_settings['product-tab-section'] ?? array();
    $container_width = $global_settings['container_width'] ?? 'boxed';


    // Check if custom template is specified
    if ($layout_template && $layout_template !== 'template_default') {
        $this->load_template_file($layout_template, $layout_settings_template, $product);
            return;
        } else {
            // Fall back to default template if product not in scope
            $this->render_default_layout($layout_settings, $product);
            return;
        }
    

    // Original default template rendering code continues...
    ?>
    <style>
    /* Base Styles */
    .shopglut-product-layout {
        margin-bottom: 40px;
    }
   
    <?php echo wp_kses_post($this->generate_layout_css($layout_settings, $layout_type)); ?>
    </style>

    <div class="woocommerce woocommerce-single-product">
        <?php
        do_action('woocommerce_before_single_product');
        if (post_password_required()) {
            echo wp_kses_post(get_the_password_form());
            return;
        }
        ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
            <div class="shopglut-container-<?php echo esc_attr($container_width); ?>">
                <div class="shopglut-product-layout shopglut-layout-<?php echo esc_attr($layout_type); ?>">
                    <?php
                    switch ($layout_type) {
                        case 'single_column':
                            $this->render_single_column_layout($layout_settings, $product);
                            break;
                        case 'two_column':
                            $this->render_two_column_layout($layout_settings, $product);
                            break;
                        default:
                            $this->render_two_column_layout($layout_settings, $product);
                            break;
                    }
                    ?>
                </div>
            </div>

            <!-- Bottom Sections -->
            <div class="shopglut-container-<?php echo esc_attr($container_width); ?>">
                <?php
                if (!empty($tab_settings['tab-repeater'])) {
                    $this->render_frontend_tabs($tab_settings, $product);
                }
                if (!empty($tab_settings['show_related'])) {
                    $this->render_frontend_related_products($tab_settings, $product);
                }
                if (!empty($tab_settings['show_upsells'])) {
                    $this->render_frontend_upsells($tab_settings, $product);
                }
                ?>
            </div>
        </div>

        <?php do_action('woocommerce_after_single_product'); ?>
    </div>
    <?php
}


/**
 * Load template file directly (simplified version)
 */
/**
 * Modified load_template_file function with latest product support
 */
private function load_template_file($template_name, $layout_settings, $product = null) {
    // Sanitize template name
    $template_name = sanitize_file_name($template_name);
    
    // Build the template file path
    $template_path = plugin_dir_path(__FILE__) . "templates/designs/{$template_name}.php";
    
    // Check if template file exists
    if (file_exists($template_path)) {
        // If no product provided, get the latest product
        if (!$product) {
            $latest_product_id = $this->get_latest_woo_product_id();
            $product = $latest_product_id ? wc_get_product($latest_product_id) : null;
        }
        
        // Make variables available to the template
        global $shopglut_template_settings, $shopglut_product;
        $shopglut_template_settings = $layout_settings;
        $shopglut_product = $product;
        
        // Store original global $product and set new one for WooCommerce compatibility
        $original_global_product = isset($GLOBALS['product']) ? $GLOBALS['product'] : null;
        $GLOBALS['product'] = $product;
		$GLOBALS['shopglut_template_settings'] = $layout_settings;
  
        // Include the template file
        include $template_path;
        
        // Restore original global $product
        if ($original_global_product !== null) {
            $GLOBALS['product'] = $original_global_product;
        } else {
            unset($GLOBALS['product']);
        }
        
        return;
    }
    
    // Fallback to default if template file not found
    $this->render_default_layout([], $product);
}

/**
 * Render default layout when template fails
 */
private function render_default_layout($layout_settings, $product) {
    $layout_type = $layout_settings['layout_type'] ?? 'two_column';
    
    switch ($layout_type) {
        case 'single_column':
            $this->render_single_column_layout($layout_settings, $product);
            break;
        case 'two_column':
            $this->render_two_column_layout($layout_settings, $product);
            break;
        default:
            $this->render_two_column_layout($layout_settings, $product);
            break;
    }
}

/**
 * UPDATED: Single column layout with settings-based styling
 */
private function render_single_column_layout($layout_settings, $product) {
    $product_column_section = $layout_settings['product-column-section'] ?? array();
    $single_column_settings = $product_column_section['single_column_settings'] ?? array();
    $sections = $product_column_section['single_column-repeater'] ?? array();
    $global_settings = $layout_settings['global_settings'] ?? array();
    
    // Generate column-specific styling
    $column_styles = array();
    
    // Background color from single column settings
    if (!empty($single_column_settings['one_column_bg_color'])) {
        $column_styles[] = 'background-color: ' . $single_column_settings['one_column_bg_color'];
    }
    
    // Padding from single column settings
    if (!empty($single_column_settings['one_column_padding'])) {
        $padding = $single_column_settings['one_column_padding'];
        if (is_array($padding)) {
            $top = $padding['top'] ?? 0;
            $right = $padding['right'] ?? 0;
            $bottom = $padding['bottom'] ?? 0;
            $left = $padding['left'] ?? 0;
            $unit = $padding['unit'] ?? 'px';
            
            if ($top || $right || $bottom || $left) {
                $column_styles[] = "padding: {$top}{$unit} {$right}{$unit} {$bottom}{$unit} {$left}{$unit}";
            }
        }
    }
    
    // Default padding if none specified
    if (empty($single_column_settings['one_column_padding'])) {
        $column_styles[] = 'padding: 20px';
    }
    
    
    $style_attr = !empty($column_styles) ? ' style="' . implode('; ', $column_styles) . '"' : '';
    
    echo '<div class="shopglut-single-column"' . wp_kses_post($style_attr) . '>';
    
    if (empty($sections)) {
        echo '<div class="shopglut-no-sections">';
        echo '<p>' . esc_html__('No sections configured for this layout. Please add sections using the admin panel.', 'shopglut') . '</p>';
        echo '</div>';
    } else {
        foreach ($sections as $section_index => $section) {
            $section_settings = $section['shopg-content-accordion']['single-content-fieldset'] ?? array();
            
            if (!empty($section_settings)) {
                $this->render_frontend_section($section_settings, $product);
            }
        }
    }
    
    echo '</div>';
}

/**
 * UPDATED: Two column layout with settings-based styling
 */
private function render_two_column_layout($layout_settings, $product) {
    $column_settings = $layout_settings['product-column-section'] ?? array();
    
    echo '<div class="shopglut-product-columns">';
    
    // Left Column with settings-based styling
    $left_column_css = $this->generate_column_css($column_settings['left_column_settings'] ?? array(), 'left');
    $left_style = $left_column_css ? ' style="' . $left_column_css . '"' : '';
    
    echo '<div class="shopglut-left-column"' . wp_kses_post($left_style) . '>';
    $this->render_column_content($column_settings, 'left', $product);
    echo '</div>';
    
    // Right Column with settings-based styling
    $right_column_css = $this->generate_column_css($column_settings['right_column_settings'] ?? array(), 'right');
    $right_style = $right_column_css ? ' style="' . $right_column_css . '"' : '';
    
    echo '<div class="shopglut-right-column"' . wp_kses_post($right_style) . '>';
    $this->render_column_content($column_settings, 'right', $product);
    echo '</div>';
    
    echo '</div>';
}

/**
 * EXAMPLE: How to test your settings
 * Add this temporary function to debug what values are actually being extracted
 */
private function debug_settings_extraction($global_settings) {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    
    //error_log('=== SETTINGS EXTRACTION DEBUG ===');
    
    $single_width = $this->get_setting_value($global_settings, 'single_column_width', 100);
    //error_log('Single Column Width: ' . $single_width);
    
    $left_width = $this->get_setting_value($global_settings, 'left_column_width', 50);
    //error_log('Left Column Width: ' . $left_width);
    
    $column_gap = $this->get_setting_value($global_settings, 'column_gap', 30);
    //error_log('Column Gap: ' . $column_gap);
    
    $container_width = $global_settings['container_width'] ?? 'boxed';
    //error_log('Container Width: ' . $container_width);
    
    //error_log('=== END SETTINGS DEBUG ===');
}


   

    public static function get_instance() {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}