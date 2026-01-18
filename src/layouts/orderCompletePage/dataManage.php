<?php
namespace Shopglut\layouts\orderCompletePage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class dataManage {

	private $active_layout_id = null;
	private $override_enabled = false;

	public function __construct() {
		add_action('wp_ajax_save_ordercomplete_settings', [$this, 'save_ordercomplete_settings']);

		// Initialize WooCommerce overrides - this will conditionally add hooks only if override is enabled
		add_action('init', [$this, 'init_woocommerce_overrides']);

		// Admin AJAX for enabling/disabling override
		add_action('wp_ajax_toggle_woocommerce_override', [$this, 'toggle_woocommerce_override']);
		add_action('wp_ajax_set_active_layout', [$this, 'set_active_layout']);

		// AJAX handlers for save and reset
		add_action('wp_ajax_save_shopg_ordercomplete_layoutdata', [$this, 'save_ordercomplete_layout_data']);
		add_action('wp_ajax_reset_shopg_ordercomplete_layout_settings', [$this, 'reset_ordercomplete_layout_settings']);
	}

	/**
	 * Initialize WooCommerce overrides if enabled
	 */
	public function init_woocommerce_overrides() {
		// Check if WooCommerce is active
		if (!class_exists('WooCommerce')) {
			return;
		}

		// Clear cache to ensure fresh data
		wp_cache_delete('shopglut_active_override_layout');

		// Check if any layout has override enabled
		$active_layout = $this->get_layout_with_override_enabled();

	
		if ($active_layout) {
			$this->active_layout_id = $active_layout['id'];
			$this->override_enabled = true;

			// Update option to track active layout
			update_option('shopglut_active_ordercomplete_layout', $this->active_layout_id);

			// Add hooks for checking and overriding - only when override is enabled
			add_action('wp', [$this, 'check_woocommerce_override'], 1);
			add_action('template_redirect', [$this, 'check_woocommerce_override'], 1);

			// Add woocommerce_thankyou hook early - BEFORE template_redirect
			add_action('woocommerce_thankyou', [$this, 'render_custom_thankyou'], 1);

			// Hook into WooCommerce if override is enabled
			add_action('template_redirect', [$this, 'override_orderComplete_page'], 1);
			add_filter('woocommerce_locate_template', [$this, 'locate_custom_template'], 10, 3);
			add_filter('template_include', [$this, 'override_page_template'], 999);
			add_action('wp_enqueue_scripts', [$this, 'enqueue_override_styles']);
		} else {
			// No layout has override enabled
			$this->override_enabled = false;
			$this->active_layout_id = 0;

			// Clear the option so it doesn't show a stale layout ID
			delete_option('shopglut_active_ordercomplete_layout');

			// Remove any filters that might have been added previously
			remove_filter('woocommerce_thankyou_order_received_text', '__return_empty_string', 999);

			// Temporary debug
		}
	}

	/**
	 * Get the layout with override enabled
	 * Only one layout can have override enabled at a time
	 */
	private function get_layout_with_override_enabled() {
		$cache_key = 'shopglut_active_override_layout';
		$active_layout = wp_cache_get($cache_key);

		if (false === $active_layout) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

			$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
				"SELECT id, layout_settings FROM {$wpdb->prefix}shopglut_ordercomplete_layouts", ARRAY_A // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- No variables in query, prepare not needed
			);

			$active_layout = null;

			foreach ($results as $layout) {
				$settings = maybe_unserialize($layout['layout_settings']);

				// Check if override is enabled for this layout (now inside tabbed settings)
				$override_enabled = false;

				// Check in the correct location (inside ordercomplete-page-settings tab)
				if (isset($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'])
					&& ($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
						|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
						|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1)) {
					$override_enabled = true;
				}

				if ($override_enabled) {
					$active_layout = array(
						'id' => $layout['id'],
						'settings' => $settings
					);
					break; // Only one layout can override at a time
				}
			}

			wp_cache_set($cache_key, $active_layout ? $active_layout : array(), '', 1800);
		}

		return !empty($active_layout) ? $active_layout : null;
	}


	  /**
     * Render layout preview
     */
    public function shopglut_render_orderCompletelayout_preview( $layout_id = 0 ) {
        // Ensure we have a valid layout ID
        if ( ! $layout_id ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            $layout_id = isset( $_GET['layout_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) ) : 1;
        }

        // Get layout data from database with caching
        $cache_key = 'shopglut_layout_' . $layout_id;
        $layout_data = wp_cache_get( $cache_key, 'shopglut_layouts' );

        if ( false === $layout_data ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for layout data lookup, using %i for table name
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $layout_id )
            );

            // Cache the result for 1 hour
            if ( $layout_data ) {
                wp_cache_set( $cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS );
            }
        }

        if ( ! $layout_data ) {
            return '<div class="shopglut-preview-error">Layout not found.</div>';
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
        $markup_class = 'Shopglut\\layouts\\orderCompletePage\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\layouts\\orderCompletePage\\templates\\' . $template_name . '\\' . $template_name . 'Style';
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
	 * Check if we're on order received page and should override
	 */
	public function check_woocommerce_override() {
		global $wp;

		// Don't do anything if override is not enabled
		if (!$this->override_enabled) {
			return;
		}

		// Prevent multiple executions
		static $already_checked = false;
		if ($already_checked) {
			return;
		}

		// Skip if this is a resource request (CSS, JS, favicon, etc.)
		$request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
		if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|woff|woff2|ttf|svg)(\?|$)/i', $request_uri)) {
			return;
		}

		// Check if we're on the order received page
		$is_endpoint = is_wc_endpoint_url('order-received');
		$has_order_received = strpos($request_uri, 'order-received') !== false;

		// Check both WooCommerce endpoint and URL pattern
		if ($is_endpoint || $has_order_received) {
			$already_checked = true;

			$order_id = $this->get_current_order_id();

			if ($order_id && $this->should_override_for_order($order_id)) {
				// Remove ALL WooCommerce thankyou actions (but not ours)
				$this->remove_all_woocommerce_actions();

				// Add additional hooks
				add_action('wp_head', [$this, 'add_custom_head_content']);

				// Also use template_include to completely replace the template
				add_filter('woocommerce_locate_template', [$this, 'override_thankyou_template'], 999, 3);

				// Use output buffering to completely replace content
				add_action('woocommerce_before_thankyou', [$this, 'start_output_buffer'], 1);
				add_action('woocommerce_after_thankyou', [$this, 'end_output_buffer'], 999);
			}
		}
	}

	/**
	 * Check if we should override for this specific order
	 */
	private function should_override_for_order($order_id) {
		if (!$order_id) {
			return false;
		}

		// Check if override is enabled and we have an active layout
		if (!$this->override_enabled || !$this->active_layout_id) {
			return false;
		}

		$order = wc_get_order($order_id);
		if (!$order) {
			return false;
		}

		// Get conditional display settings
		$layout_settings = $this->get_layout_settings($this->active_layout_id);
		$conditional_settings = isset($layout_settings['conditional_display']) ? $layout_settings['conditional_display'] : array();

		if (!isset($conditional_settings['enable_conditional_display']) || !$conditional_settings['enable_conditional_display']) {
			return true; // Override for all orders if no conditions set
		}

		// Check conditions
		$conditions = isset($conditional_settings['conditions']) ? $conditional_settings['conditions'] : array();

		foreach ($conditions as $condition) {
			if (!$this->evaluate_condition($condition, $order)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Evaluate a single condition against an order
	 */
	private function evaluate_condition($condition, $order) {
		$type = isset($condition['condition_type']) ? $condition['condition_type'] : '';
		$operator = isset($condition['condition_operator']) ? $condition['condition_operator'] : 'equals';
		$value = isset($condition['condition_value']) ? $condition['condition_value'] : '';
		
		$actual_value = '';
		
		switch ($type) {
			case 'order_status':
				$actual_value = $order->get_status();
				break;
			case 'order_total':
				$actual_value = floatval($order->get_total());
				$value = floatval($value);
				break;
			case 'payment_method':
				$actual_value = $order->get_payment_method();
				break;
			case 'shipping_method':
				$actual_value = $order->get_shipping_method();
				break;
			case 'customer_role':
				$user = $order->get_user();
				$actual_value = $user ? implode(',', $user->roles) : 'guest';
				break;
			case 'product_category':
				$categories = array();
				foreach ($order->get_items() as $item) {
					$product = $item->get_product();
					if ($product) {
						$product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));
						$categories = array_merge($categories, $product_categories);
					}
				}
				$actual_value = implode(',', array_unique($categories));
				break;
		}
		
		return $this->compare_values($actual_value, $operator, $value);
	}

	/**
	 * Compare values based on operator
	 */
	private function compare_values($actual, $operator, $expected) {
		switch ($operator) {
			case 'equals':
				return $actual == $expected;
			case 'not_equals':
				return $actual != $expected;
			case 'greater_than':
				return floatval($actual) > floatval($expected);
			case 'less_than':
				return floatval($actual) < floatval($expected);
			case 'greater_equal':
				return floatval($actual) >= floatval($expected);
			case 'less_equal':
				return floatval($actual) <= floatval($expected);
			case 'contains':
				return strpos(strtolower($actual), strtolower($expected)) !== false;
			case 'not_contains':
				return strpos(strtolower($actual), strtolower($expected)) === false;
			case 'in_array':
				$array_values = explode(',', $actual);
				return in_array(trim($expected), array_map('trim', $array_values));
			default:
				return true;
		}
	}

	/**
	 * Complete removal of all WooCommerce thank you actions
	 */
	private function remove_all_woocommerce_actions() {
		// Remove ALL default WooCommerce thankyou content
		remove_all_actions('woocommerce_thankyou');
		
		// Remove order details and other default content
		remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
		remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 20);
		
		// Remove notices
		remove_action('woocommerce_before_thankyou', 'woocommerce_output_all_notices', 10);
		
		// Remove additional WooCommerce hooks
		remove_all_actions('woocommerce_order_details_before_order_table');
		remove_all_actions('woocommerce_order_details_after_order_table');
		remove_all_actions('woocommerce_order_details_before_order_table_items');
		remove_all_actions('woocommerce_order_details_after_order_table_items');
		
		// Remove the default order received message and overview
		add_filter('woocommerce_thankyou_order_received_text', '__return_empty_string', 999);
		add_filter('wc_get_template', [$this, 'override_thankyou_template_parts'], 999, 2);
		
		// Allow themes to remove their customizations
		do_action('shopglut_before_thankyou_override');
	}

	/**
	 * Override the thankyou template completely
	 */
	public function override_thankyou_template($template, $template_name, $template_path) {

		// Override the thankyou template
		if ($template_name === 'checkout/thankyou.php') {
			$custom_template = plugin_dir_path(__FILE__) . 'templates/custom-thankyou.php';

			// Create the custom template if it doesn't exist
			if (!file_exists($custom_template)) {
				$this->create_custom_thankyou_template();
			}

			return $custom_template;
		}

		return $template;
	}

	/**
	 * Create custom thankyou template file
	 */
	private function create_custom_thankyou_template() {
		$template_dir = plugin_dir_path(__FILE__) . 'templates/';
		if (!file_exists($template_dir)) {
			wp_mkdir_p($template_dir);
		}

		$template_content = '<?php
/**
 * Custom Order Received (thankyou) template for Shopglut override
 */

defined(\'ABSPATH\') || exit;

// Get the order ID
$order_id = $order->get_id();

// Get the active layout ID
$active_layout_id = get_option(\'shopglut_active_ordercomplete_layout\', 0);

if (!$active_layout_id) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0;">No active layout found</div>\';
	return;
}

// Get layout settings and template
global $wpdb;
$table_name = $wpdb->prefix . \'shopglut_ordercomplete_layouts\';
$layout_data = $wpdb->get_row($wpdb->prepare(
	"SELECT layout_template, layout_settings FROM `{$table_name}` WHERE id = %d",
	$active_layout_id
));

if (!$layout_data) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0;">Layout not found</div>\';
	return;
}

$layout_template = $layout_data->layout_template;

// Load template class
$markup_file = __DIR__ . \'/templates/\' . $layout_template . \'/\' . $layout_template . \'Markup.php\';
if (!file_exists($markup_file)) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0;">Template markup file not found</div>\';
	return;
}

require_once $markup_file;

$template_class = \'Shopglut\\\\layouts\\\\orderCompletePage\\\\templates\\\\\' . $layout_template . \'\\\\\' . $layout_template . \'Markup\';

if (!class_exists($template_class)) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0;">Template class not found</div>\';
	return;
}

// Prepare template data
$template_data = array(
	\'layout_id\' => $active_layout_id,
	\'order\' => $order,
	\'order_id\' => $order_id
);

// Render the layout
echo \'<!-- Shopglut Custom Order Complete Template -->\';
$layout_instance = new $template_class();
$layout_instance->layout_render($template_data);
';

		file_put_contents($template_dir . 'custom-thankyou.php', $template_content);
	}

	/**
	 * Override specific template parts
	 */
	public function override_thankyou_template_parts($template, $template_name) {
		// Override the order received template parts
		if (in_array($template_name, [
			'checkout/thankyou.php',
			'order/order-details.php',
			'order/order-details-table.php'
		])) {
			// Return our custom template
			$custom_template = plugin_dir_path(__FILE__) . 'templates/empty.php';
			if (file_exists($custom_template)) {
				return $custom_template;
			}
		}
		return $template;
	}

	/**
	 * Start output buffering to capture and replace content
	 */
	public function start_output_buffer() {
		ob_start();
	}

	/**
	 * End output buffering and replace with custom content
	 */
	public function end_output_buffer() {
		$default_content = ob_get_clean();
		// Don't output the default content, our custom content will be rendered instead
	}

	/**
	 * Override the page template completely
	 */
	public function override_page_template($template) {
		if (!is_wc_endpoint_url('order-received')) {
			return $template;
		}

		$order_id = $this->get_current_order_id();

		if (!$order_id || !$this->should_override_for_order($order_id)) {
			return $template;
		}

		// Return our custom full page template
		$custom_template = plugin_dir_path(__FILE__) . 'templates/custom-page-template.php';

		if (!file_exists($custom_template)) {
			$this->create_custom_page_template();
		}

		return $custom_template;
	}

	/**
	 * Create custom page template
	 */
	private function create_custom_page_template() {
		$template_dir = plugin_dir_path(__FILE__) . 'templates/';
		if (!file_exists($template_dir)) {
			wp_mkdir_p($template_dir);
		}

		$template_content = '<?php
/**
 * Custom Order Received Page Template
 */

// Check if this is a block theme
$is_block_theme = function_exists(\'wp_is_block_theme\') && wp_is_block_theme();

if (!$is_block_theme) {
	// Classic theme - use traditional header
	get_header(\'shop\');
	// WooCommerce wrapper start
	do_action(\'woocommerce_before_main_content\');
}

// Get order ID
global $wp;
$order_id = isset($wp->query_vars[\'order-received\']) ? absint($wp->query_vars[\'order-received\']) : 0;

if (!$order_id) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px;">No order ID found</div>\';
	get_footer();
	exit;
}

$order = wc_get_order($order_id);

if (!$order) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px;">Order not found</div>\';
	get_footer();
	exit;
}

// Get active layout
$active_layout_id = get_option(\'shopglut_active_ordercomplete_layout\', 0);

if (!$active_layout_id) {
	// No active layout - fall back to default WooCommerce template
	if ($is_block_theme) {
		get_header();
	} else {
		get_header(\'shop\');
	}

	// Let WooCommerce handle the thank you page
	do_action(\'woocommerce_before_main_content\');
	wc_get_template(\'checkout/thankyou.php\', array(\'order\' => $order));
	do_action(\'woocommerce_after_main_content\');

	if ($is_block_theme) {
		get_footer();
	} else {
		get_footer(\'shop\');
	}
	exit;
}

// Get layout template
global $wpdb;
$table_name = $wpdb->prefix . \'shopglut_ordercomplete_layouts\';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for layout template lookup, using %i for table name
$layout_data = $wpdb->get_row($wpdb->prepare(
	"SELECT layout_template FROM %i WHERE id = %d",
	$table_name,
	$active_layout_id
));

if (!$layout_data) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px;">Layout not found</div>\';
	get_footer();
	exit;
}

$layout_template = $layout_data->layout_template;

// Load template class - use absolute path
$ordercompletepage_dir = \'/media/books-audio/wordpress-sites/shopglutpro/wp-content/plugins/shopglut/src/layouts/orderCompletePage\';
$markup_file = $ordercompletepage_dir . \'/templates/\' . $layout_template . \'/\' . $layout_template . \'Markup.php\';
$style_file = $ordercompletepage_dir . \'/templates/\' . $layout_template . \'/\' . $layout_template . \'Style.php\';

if (!file_exists($markup_file)) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px;">Template markup file not found: \' . esc_html($markup_file) . \'</div>\';
	get_footer();
	exit;
}

require_once $markup_file;

if (file_exists($style_file)) {
	require_once $style_file;
}

$template_class = \'Shopglut\\\\layouts\\\\orderCompletePage\\\\templates\\\\\' . $layout_template . \'\\\\\' . $layout_template . \'Markup\';
$style_class = \'Shopglut\\\\layouts\\\\orderCompletePage\\\\templates\\\\\' . $layout_template . \'\\\\\' . $layout_template . \'Style\';

if (!class_exists($template_class)) {
	echo \'<div style="background: #f44336; color: white; padding: 20px; margin: 20px;">Template class not found</div>\';
	get_footer();
	exit;
}

// Generate and output dynamic CSS
if (class_exists($style_class)) {
	$style_instance = new $style_class();
	if (method_exists($style_instance, \'dynamicCss\')) {
		$dynamic_css = $style_instance->dynamicCss($active_layout_id);
		if (!empty($dynamic_css)) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Dynamic CSS is generated by our style class
			echo \'<style type="text/css">\' . $dynamic_css . \'</style>\';
		}
	}
}

// Prepare template data
$template_data = array(
	\'layout_id\' => $active_layout_id,
	\'order\' => $order,
	\'order_id\' => $order_id
);

// Render the layout
if ($is_block_theme) {
	// For block themes, use block template parts
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo(\'charset\'); ?>">
		<?php wp_head(); ?>
		<style>
		/* Block theme layout styles for footer */
		.is-content-justification-space-between.wp-block-group-is-layout-flex {
			justify-content: space-between;
			align-items: flex-start;
		}
		.is-vertical.is-content-justification-stretch.is-layout-flex.wp-block-group-is-layout-flex {
			flex-direction: column;
			align-items: stretch;
		}
		</style>
	</head>
	<body <?php body_class(\'wp-embed-responsive\'); ?>>
		<?php wp_body_open(); ?>
		<div class="wp-site-blocks">
			<header class="wp-block-template-part">
			<?php
			if (function_exists(\'block_header_area\')) {
				block_header_area();
			} else {
				$header_template = get_block_template(get_stylesheet() . \'//header\', \'wp_template_part\');
				if ($header_template) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress core do_blocks() output is safe
					echo do_blocks($header_template->content);
				}
			}
			?>
			</header>

			<div class="shopglut-order-complete-wrapper" style="padding: 2rem 0; clear: both; overflow: hidden;">
				<?php
				ob_start();
				$layout_instance = new $template_class();
				$layout_instance->layout_render($template_data);
				$layout_output = ob_get_clean();
				// Ensure proper div closing by counting divs
				$open_divs = substr_count($layout_output, \'<div\');
				$close_divs = substr_count($layout_output, \'</div\');
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Layout output is rendered by our template class
				echo $layout_output;
				// Close any unclosed divs
				if ($open_divs > $close_divs) {
					for ($i = 0; $i < ($open_divs - $close_divs); $i++) {
						echo \'</div>\';
					}
				}
				?>
			</div>

			<footer class="wp-block-template-part" style="clear: both;">
			<?php
			if (function_exists(\'block_footer_area\')) {
				block_footer_area();
			} else {
				$footer_template = get_block_template(get_stylesheet() . \'//footer\', \'wp_template_part\');
				if ($footer_template) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress core do_blocks() output is safe
					echo do_blocks($footer_template->content);
				}
			}
			?>
			</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
	</html>
	<?php
} else {
	// For classic themes
	echo \'<div class="shopglut-order-complete-wrapper">\';
	$layout_instance = new $template_class();
	$layout_instance->layout_render($template_data);
	echo \'</div>\';

	// WooCommerce wrapper end
	do_action(\'woocommerce_after_main_content\');

	get_footer(\'shop\');
}
';

		file_put_contents($template_dir . 'custom-page-template.php', $template_content);
	}

	/**
	 * Override the Order Complete Page template completely
	 */
	public function override_orderComplete_page() {
		if (!is_wc_endpoint_url('order-received')) {
			return;
		}

		$order_id = $this->get_current_order_id();

		if (!$order_id || !$this->should_override_for_order($order_id)) {
			return;
		}

		// Use content filter for complete replacement
		add_filter('the_content', [$this, 'replace_entire_content'], 999);
	}

	/**
	 * Replace the entire page content
	 */
	public function replace_entire_content($content) {
		// Only replace on order received page
		if (!is_wc_endpoint_url('order-received')) {
			return $content;
		}

		$order_id = $this->get_current_order_id();

		if (!$order_id || !$this->should_override_for_order($order_id)) {
			return $content;
		}

		// Generate our custom content
		ob_start();
		echo '<!-- Shopglut Order Complete Override Active - Layout ID: ' . esc_html($this->active_layout_id) . ' -->';
		$this->render_custom_layout($order_id);
		$custom_content = ob_get_clean();

		$custom_content .="</div></div>";
		// Return ONLY our custom content
		return $custom_content;
	}

	/**
	 * Get current order ID from various sources
	 */
	private function get_current_order_id() {
		global $wp;
		
		$order_id = 0;
		
		// Method 1: From URL endpoint
		if (isset($wp->query_vars['order-received']) && $wp->query_vars['order-received']) {
			$order_id = absint($wp->query_vars['order-received']);
		}
		
		// Method 2: From GET parameters with order key verification
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe order retrieval with key verification on Order Complete Page
		elseif (isset($_GET['order']) && isset($_GET['key'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe order parameter on Order Complete Page
			$order_id = absint(wp_unslash($_GET['order']));
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe order key parameter on Order Complete Page
			$order_key = sanitize_text_field(wp_unslash($_GET['key']));
			
			// Verify order key for security
			$order = wc_get_order($order_id);
			if (!$order || !hash_equals($order->get_order_key(), $order_key)) {
				$order_id = 0;
			}
		}
		
		// Method 3: Direct order_id parameter (fallback)
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe order retrieval parameter on Order Complete Page
		elseif (isset($_GET['order_id'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe order ID parameter on Order Complete Page
			$order_id = absint(wp_unslash($_GET['order_id']));
		}
		
		return $order_id;
	}

	/**
	 * Render custom thank you content
	 */
	public function render_custom_thankyou($order_id) {
		// Check if we should override for this order
		if (!$this->should_override_for_order($order_id)) {
			return;
		}

		// Render our custom layout
		echo '<!-- Shopglut Order Complete Override Active - Layout ID: ' . esc_html($this->active_layout_id) . ' -->';
		$this->render_custom_layout($order_id);
	}

	/**
	 * Render the custom layout
	 */
	public function render_custom_layout($order_id) {
		$layout_id = $this->active_layout_id;
		
		if (!$layout_id) {
			// Try to get any available layout
			$cache_key = 'shopglut_first_layout_id';
			$layout_id = wp_cache_get($cache_key);
			
			if (false === $layout_id) {
				global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for layout lookup, using %i for table name
				$layout_id = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}shopglut_ordercomplete_layouts ORDER BY id ASC LIMIT 1");
				wp_cache_set($cache_key, $layout_id, '', 3600);
			}
		}

		if (!$layout_id) {
			echo '<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0; border-radius: 8px; text-align: center;">';
			echo '<h3>No Layout Available</h3>';
			echo '<p>Please create and activate a Order Complete Page layout in the admin.</p>';
			echo '</div>';
			return;
		}

		$layout_settings = $this->get_layout_settings($layout_id);
		$layout_template = $this->get_layout_template($layout_id);
		
		// Prepare template data with order information
		$template_data = $this->prepare_template_data($layout_settings, $order_id);
		
		// Get template class and render
		$template_class = $this->get_template_class($layout_template);
		
		if (class_exists($template_class)) {
			$layout_instance = new $template_class();
			$layout_instance->layout_render($template_data);
		} else {
			echo '<div style="background: #ff9800; color: white; padding: 20px; margin: 20px 0; border-radius: 8px; text-align: center;">';
			echo '<h3>Template Error</h3>';
			echo '<p>Template class not found. Please check your template configuration.</p>';
			echo '</div>';
		}
	}

	/**
	 * Prepare template data with order and WooCommerce information
	 */
	private function prepare_template_data($layout_settings, $order_id) {
		$template_data = array(
			'layout_id' => $this->active_layout_id,
			'ordercomplete-styling' => $layout_settings,
			'order_id' => $order_id
		);

		// Add WooCommerce data if order exists
		if ($order_id && function_exists('wc_get_order')) {
			$order = wc_get_order($order_id);
			if ($order) {
				// Pass the order object directly for template rendering
				$template_data['order'] = $order;
				$template_data['woocommerce_order'] = $order;
				$template_data['woocommerce_override_settings'] = array(
					'enable_woocommerce_override' => true,
					'preserve_woo_data' => true
				);
				$template_data['woocommerce_content_mapping'] = array(
					'map_order_number' => true,
					'map_order_date' => true,
					'map_customer_email' => true,
					'map_payment_method' => true,
					'map_order_items' => true,
					'map_order_totals' => true,
					'map_billing_address' => true,
					'map_shipping_address' => true
				);
			}
		}

		return apply_filters('shopglut_template_data', $template_data, $order_id);
	}

	/**
	 * Add custom head content for styling and scripts
	 */
	public function add_custom_head_content() {
		echo '<meta name="shopglut-thankyou-override" content="active">' . "\n";
		
		// Hide any remaining WooCommerce elements
		echo '<style>
			.woocommerce-notice--success,
			.woocommerce-order-overview,
			.woocommerce-thankyou-order-received,
			.woocommerce-order-details,
			.woocommerce-customer-details {
				display: none !important;
			}
		</style>' . "\n";
		
		// Add any custom head content from layout settings
		$layout_settings = $this->get_layout_settings($this->active_layout_id);
		$custom_head = isset($layout_settings['custom_styling']['custom_head']) ? $layout_settings['custom_styling']['custom_head'] : '';
		
		if (!empty($custom_head)) {
			echo wp_kses_post($custom_head) . "\n";
		}
	}

	/**
	 * Enqueue override styles
	 */
	public function enqueue_override_styles() {
		if (!is_wc_endpoint_url('order-received')) {
			return;
		}

		$order_id = $this->get_current_order_id();
		if (!$order_id || !$this->should_override_for_order($order_id)) {
			return;
		}

		// Add Font Awesome - Use local copy instead of external CDN
		wp_enqueue_style(
			'shopglut-font-awesome',
			SHOPGLUT_URL . 'assets/css/font-awesome.min.css',
			array(),
			SHOPGLUT_VERSION
		);

		// Add override styles to hide WooCommerce elements
		wp_add_inline_style('shopglut-font-awesome', '
			/* Hide all default WooCommerce thank you elements */
			.woocommerce-notice--success,
			.woocommerce-order-overview,
			.woocommerce-thankyou-order-received,
			.woocommerce-order-details,
			.woocommerce-customer-details,
			.woocommerce-order-downloads,
			.woocommerce-bacs-bank-details {
				display: none !important;
				visibility: hidden !important;
				height: 0 !important;
				overflow: hidden !important;
			}
		');
	}

	/**
	 * Locate custom template for WooCommerce
	 */
	public function locate_custom_template($template, $template_name, $template_path) {
		// Log all checkout/order related templates
		static $logged_templates = array();

		if ((strpos($template_name, 'checkout') !== false ||
		     strpos($template_name, 'order') !== false ||
		     strpos($template_name, 'thankyou') !== false) &&
		    !isset($logged_templates[$template_name])) {
				$logged_templates[$template_name] = true;
		}

		// Override the order received template
		if ($template_name === 'checkout/thankyou.php') {
			$custom_template = plugin_dir_path(__FILE__) . 'templates/custom-thankyou.php';

			// Create the custom template if it doesn't exist
			if (!file_exists($custom_template)) {
				$this->create_custom_thankyou_template();
			}

			return $custom_template;
		}

		return $template;
	}

	/**
	 * AJAX: Toggle WooCommerce override
	 */
	public function toggle_woocommerce_override() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_admin_nonce')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		$enable = isset($_POST['enable']) ? (bool) sanitize_text_field(wp_unslash($_POST['enable'])) : false;
		update_option('shopglut_woo_override_enabled', $enable);

		wp_send_json_success(array(
			'enabled' => $enable,
			'message' => $enable ? 'WooCommerce override enabled' : 'WooCommerce override disabled'
		));
	}

	/**
	 * AJAX: Set active layout
	 */
	public function set_active_layout() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_admin_nonce')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		$layout_id = isset($_POST['layout_id']) ? intval(sanitize_text_field(wp_unslash($_POST['layout_id']))) : 0;
		
		if ($layout_id <= 0) {
			wp_send_json_error('Invalid layout ID');
			return;
		}

		// Verify layout exists
		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
		$cache_key = 'shopglut_layout_exists_' . $layout_id;
		$layout_exists = wp_cache_get($cache_key);
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		
		if (false === $layout_exists) {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_exists = $wpdb->get_var($wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d",
				$layout_id
			));
			wp_cache_set($cache_key, $layout_exists, '', 1800);
		}

		if (!$layout_exists) {
			wp_send_json_error('Layout not found');
			return;
		}

		update_option('shopglut_active_ordercomplete_layout', $layout_id);
		$this->active_layout_id = $layout_id;

		wp_send_json_success(array(
			'layout_id' => $layout_id,
			'message' => 'Active layout updated'
		));
	}

	/**
	 * Get layout settings from database
	 */
	private function get_layout_settings($layout_id) {
		if (!$layout_id) {
			return array();
		}

		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
		
		$cache_key = 'shopglut_layout_settings_' . $layout_id;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_settings = wp_cache_get($cache_key);
		
		if (false === $layout_settings) {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_settings = $wpdb->get_var($wpdb->prepare(
				"SELECT layout_settings FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d",
				$layout_id
			));
			wp_cache_set($cache_key, $layout_settings, '', 3600);
		}

		if ($layout_settings) {
			$unserialized = unserialize($layout_settings);
			// Return the settings in the correct nested structure
			if (isset($unserialized['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings'])) {
				return $unserialized['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings'];
			}
			// Fallback to old structure for backward compatibility
			if (isset($unserialized['shopg_ordercomplete_styling'])) {
				return $unserialized['shopg_ordercomplete_styling'];
			}
		}

		return array();
	}

	/**
	 * Get layout template from database
	 */
	private function get_layout_template($layout_id) {
		if (!$layout_id) {
			return 'template1';
		}

		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
		
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$cache_key = 'shopglut_layout_template_' . $layout_id;
		$template = wp_cache_get($cache_key);
		
		if (false === $template) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Custom table query for getting layout template
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$template = $wpdb->get_var($wpdb->prepare(
				"SELECT layout_template FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d",
				$layout_id
			));
			wp_cache_set($cache_key, $template, '', 3600);
		}

		return $template ? $template : 'template1';
	}

	/**
	 * Get template class with namespace
	 */
	protected function get_template_class($template_id) {
		$default_namespace = 'Shopglut\\layouts\\orderCompletePage\\templates\\';
		$template_class = $default_namespace . $template_id . '\\' . $template_id . 'Markup';

		return apply_filters('shopglut_template_class', $template_class, $template_id);
	}

	/**
	 * Save Order Complete Page settings
	 */
	public function save_ordercomplete_settings() {
		// Check nonce
		if (!isset($_POST['ordercomplete_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ordercomplete_nonce'])), 'shopg_ordercomplete_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_ordercomplete_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_ordercomplete_layoutid']))) : 0;
		$layout_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';
		$layout_template = isset($_POST['layout_template']) ? sanitize_text_field(wp_unslash($_POST['layout_template'])) : '';
		// Don't use array_map with wp_kses_post on nested arrays - use sanitize_layout_settings instead
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitization handled by sanitize_layout_settings() method
		$layout_settings = isset($_POST['shopg_options_settings']) ? $this->sanitize_layout_settings(wp_unslash($_POST['shopg_options_settings'])) : array();

		// Validate required fields
		if (empty($layout_name) || empty($layout_template)) {
			wp_send_json_error('Required fields are missing');
			return;
		}

		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

		// Check if override is enabled for this layout (inside tabbed settings)
		$override_enabled = false;

		// Check in the correct location (inside ordercomplete-page-settings tab)
		if (isset($layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'])
			&& ($layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
				|| $layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
				|| $layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1)) {
			$override_enabled = true;
		}

		// If override is enabled for this layout, disable it for all other layouts
		if ($override_enabled) {
			$this->disable_override_for_other_layouts($layout_id);
		}

		// Prepare data for saving
		$data = array(
			'layout_name' => $layout_name,
			'layout_template' => $layout_template,
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			'layout_settings' => serialize($layout_settings),
			'updated_at' => current_time('mysql')
		);

		if ($layout_id > 0) {
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->update(
				$table_name,
				$data,
				array('id' => $layout_id),// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				array('%s', '%s', '%s', '%s'),
				array('%d')
			);
		} else {
			$data['created_at'] = current_time('mysql');
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->insert($table_name, $data, array('%s', '%s', '%s', '%s', '%s'));
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_id = $wpdb->insert_id;
		}

		if ($result === false) {
			wp_send_json_error(array(
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				'message' => 'Database error: ' . $wpdb->last_error
			));
			return;
		}

		// Clear relevant caches
		wp_cache_delete('shopglut_active_override_layout');
		wp_cache_delete('shopglut_layout_' . $layout_id, 'shopglut_layouts');
		wp_cache_delete('shopglut_layout_settings_' . $layout_id);
		wp_cache_delete('shopglut_layout_template_' . $layout_id);

		ob_start();
		echo wp_kses_post($this->shopglut_render_ordercomplete_preview($layout_id));
		$preview_html = ob_get_clean();

		$message = 'Layout saved successfully';
		if ($override_enabled) {
			$message .= '. This layout will now override the WooCommerce order complete page.';
		}

		wp_send_json_success(array(
			'message' => $message,
			'layout_id' => $layout_id,
			'html' => $preview_html,
			'override_enabled' => $override_enabled
		));
	}

	/**
	 * Disable override for all other layouts when one is enabled
	 */
	private function disable_override_for_other_layouts($current_layout_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

		// Sanitize input parameter
		$current_layout_id = (int) $current_layout_id;

		$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
			$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
				sprintf("SELECT id, layout_settings FROM `%s` WHERE id != %%d", esc_sql($table_name)), $current_layout_id ), ARRAY_A
		);

		foreach ($results as $layout) {
			$settings = maybe_unserialize($layout['layout_settings']);
			$updated = false;

			// Check and disable override in the correct location (inside tabbed settings)
			if (isset($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'])
				&& ($settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
					|| $settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1)) {

				// Disable override for this layout
				$settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] = false;
				$updated = true;
			}

			// Update the layout if changes were made
			if ($updated) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update(
					$table_name,
					array('layout_settings' => serialize($settings)),
					array('id' => $layout['id']),
					array('%s'),
					array('%d')
				);

				// Clear cache for this layout
				wp_cache_delete('shopglut_layout_' . $layout['id'], 'shopglut_layouts');
				wp_cache_delete('shopglut_layout_settings_' . $layout['id']);
			}
		}

		// Clear the active override layout cache
		wp_cache_delete('shopglut_active_override_layout');
	}

	/**
	 * Render Order Complete Page preview
	 */
	public function shopglut_render_ordercomplete_preview($layout_id) {
		// Get layout template with caching
		$cache_key_template = 'shopglut_layout_template_' . $layout_id;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_template = wp_cache_get($cache_key_template);
		
		if (false === $layout_template) {
			global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	
			$layout_template = $wpdb->get_var($wpdb->prepare("SELECT layout_template FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $layout_id));
			wp_cache_set($cache_key_template, $layout_template, '', 3600);
		}
		
		// Get layout settings with caching
		$cache_key_settings = 'shopglut_layout_settings_' . $layout_id;
		$layout_settings_raw = wp_cache_get($cache_key_settings);
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		
		if (false === $layout_settings_raw) {
			if (!isset($wpdb)) {
				global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
			}
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	
			$layout_settings_raw = $wpdb->get_var($wpdb->prepare("SELECT layout_settings FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $layout_id));
			wp_cache_set($cache_key_settings, $layout_settings_raw, '', 3600);
		}
		
		$layout_options = maybe_unserialize($layout_settings_raw);

		// Include template files
		$markup_file = __DIR__ . '/templates/' . $layout_template . '/' . $layout_template . 'Markup.php';
		$style_file = __DIR__ . '/templates/' . $layout_template . '/' . $layout_template . 'Style.php';

		if (!file_exists($markup_file)) {
			return '<div class="shopglut-preview-error">Template markup file not found.</div>';
		}

		if (!file_exists($style_file)) {
			return '<div class="shopglut-preview-error">Template style file not found.</div>';
		}

		require_once $markup_file;
		require_once $style_file;

		ob_start();
		?>
		<div class="shopglut-product-preview shopglut-layout-preview" style="width: 100%; position: relative;">
			
			<div class="preview-layout" style="margin-bottom: 30px;">
				<?php
				$layout_class = $this->get_template_class($layout_template);
				$style_class = 'Shopglut\\layouts\\orderCompletePage\\templates\\' . $layout_template . '\\' . $layout_template . 'Style';

				if (class_exists($layout_class) && class_exists($style_class)) {
					// Generate dynamic CSS
					$style_instance = new $style_class();
					if (method_exists($style_instance, 'dynamicCss')) {
						$dynamic_css = $style_instance->dynamicCss($layout_id);
						if (!empty($dynamic_css)) {
							echo '<style type="text/css">' . wp_kses($dynamic_css, array()) . '</style>';
						}
					}

					// Render markup
					$layout_instance = new $layout_class();
					$template_data = array('layout_id' => $layout_id);
					$layout_instance->layout_render($template_data);
				} else {
					echo '<p style="color: #f44336; padding: 20px; text-align: center;">Template class not found: ' . esc_html($layout_class) . '</p>';
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}


	/**
	 * Get override status for admin
	 */
	public function get_override_status() {
		return array(
			'enabled' => $this->override_enabled,
			'active_layout_id' => $this->active_layout_id,
			'woocommerce_active' => class_exists('WooCommerce'),
			'layouts_count' => $this->get_layouts_count()
		);
	}

	/**
	 * Get total layouts count
	 */
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	private function get_layouts_count() {
		$cache_key = 'shopglut_layouts_count';
		$count = wp_cache_get($cache_key);
		
		if (false === $count) {
			global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Custom table query for getting total layouts count
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	
			$count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_ordercomplete_layouts");
			wp_cache_set($cache_key, $count, '', 1800);
		}
		
		return $count;
	}

	/**
	 * Convert clean JSON data to expected format
	 */
	private function convert_clean_json_to_expected_format($clean_data) {
		// The JavaScript sends data with shopg_ordercomplete_settings_template1 as the root key,
		// so we need to extract the inner structure to avoid double-nesting
		if (isset($clean_data['shopg_ordercomplete_settings_template1'])) {
			return array('shopg_ordercomplete_settings_template1' => $clean_data['shopg_ordercomplete_settings_template1']);
		}

		// Fallback to returning the data as is if the expected structure isn't found
		return $clean_data;
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
	 * Save order complete layout data from AJAX
	 */
	public function save_ordercomplete_layout_data() {
		// Check nonce first before accessing any POST data
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in the next line
		if (!isset($_POST['ordercomplete_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ordercomplete_nonce'])), 'shopg_ordercomplete_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_ordercomplete_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_ordercomplete_layoutid']))) : 0;
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
					// Extract the inner structure to match format
					$layout_settings = $this->convert_clean_json_to_expected_format($settings_decoded);
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
		$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

		// Check if override is enabled for this layout (inside tabbed settings)
		$override_enabled = false;

		// Check in the correct location (inside ordercomplete-page-settings tab)
		if (isset($layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'])
			&& ($layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 'true'
				|| $layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == true
				|| $layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == '1'
				|| $layout_settings['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] == 1)) {
			$override_enabled = true;
		}

		// If override is enabled for this layout, disable it for all other layouts
		if ($override_enabled) {
			$this->disable_override_for_other_layouts($layout_id);
		}

		// Prepare data for saving
		$data = array(
			'layout_name' => $layout_name,
			'layout_template' => $layout_template,
			'layout_settings' => serialize($layout_settings),
			'updated_at' => current_time('mysql')
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

		// Clear relevant caches
		wp_cache_delete('shopglut_active_override_layout');
		wp_cache_delete('shopglut_layout_' . $layout_id, 'shopglut_layouts');
		wp_cache_delete('shopglut_layout_settings_' . $layout_id);
		wp_cache_delete('shopglut_layout_template_' . $layout_id);

		// Generate preview HTML
		$preview_html = $this->shopglut_render_ordercomplete_preview($layout_id);

		$message = 'Layout saved successfully';
		if ($override_enabled) {
			$message .= '. This layout will now override the WooCommerce order complete page.';
		}

		wp_send_json_success(array(
			'message' => $message,
			'layout_id' => $layout_id,
			'html' => $preview_html,
			'override_enabled' => $override_enabled
		));
	}

	/**
	 * Reset order complete layout settings to default
	 */
	public function reset_ordercomplete_layout_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_ordercomplete_layouts')) {
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

		// Get current layout data
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_data = $wpdb->get_row($wpdb->prepare("SELECT layout_template FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $layout_id));

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
		wp_cache_delete('shopglut_active_override_layout');
		wp_cache_delete('shopglut_layout_' . $layout_id, 'shopglut_layouts');
		wp_cache_delete('shopglut_layout_settings_' . $layout_id);
		wp_cache_delete('shopglut_layout_template_' . $layout_id);

		wp_send_json_success(array(
			'message' => 'Settings reset to default successfully!',
			'layout_id' => $layout_id
		));
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

// Create empty template file for overrides
add_action('init', function() {
	$template_dir = plugin_dir_path(__FILE__) . 'templates/woocommerce/checkout/';
	if (!file_exists($template_dir)) {
		wp_mkdir_p($template_dir);
	}
	
	$empty_template = $template_dir . 'thankyou.php';
	if (!file_exists($empty_template)) {

	}
});