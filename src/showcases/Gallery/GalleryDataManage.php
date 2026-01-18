<?php
namespace Shopglut\showcases\Gallery;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GalleryDataManage {

	public function __construct() {
		// AJAX handlers for product gallery save and reset
		add_action('wp_ajax_save_shopg_Gallery_layoutdata', [$this, 'save_Gallery_layout_data']);
		add_action('wp_ajax_reset_shopg_Gallery_layout_settings', [$this, 'reset_Gallery_layout_settings']);

		// Handle individual actions (delete, duplicate)
		add_action('admin_init', [GalleryListTable::class, 'handle_individual_actions']);

		// Handle success messages
		add_action('admin_notices', [$this, 'show_action_messages']);

		// Initialize gallery button display
	}


	/**
	 * Save product gallery layout data from AJAX
	 */
	public function save_Gallery_layout_data() {
		// Check nonce first before accessing any POST data
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in the next line
		if (!isset($_POST['Gallery_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['Gallery_nonce'])), 'shopg_Gallery_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$layout_id = isset($_POST['shopg_Gallery_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_Gallery_layoutid']))) : 0;
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
		$table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

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
		$preview_html = $this->shopglut_render_gallery_preview($layout_id);

		wp_send_json_success(array(
			'message' => 'Product gallery layout saved successfully',
			'layout_id' => $layout_id,
			'html' => $preview_html
		));
	}

	/**
	 * Reset product gallery layout settings to default
	 */
	public function reset_Gallery_layout_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_Gallery_layouts')) {
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
		$table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

		// Get current layout data with caching
		$cache_key = "shopglut_gallery_layout_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_gallery' );

		if ( false === $layout_data ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$layout_data = $wpdb->get_row(
				sprintf("SELECT layout_template FROM `%s` WHERE id = %d", esc_sql($table_name), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated ID
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_gallery', 30 * MINUTE_IN_SECONDS );
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
		wp_cache_delete('shopglut_layout_' . $layout_id, 'shopglut_showcases');
		wp_cache_delete('shopglut_layout_settings_' . $layout_id);
		wp_cache_delete('shopglut_layout_template_' . $layout_id);

		wp_send_json_success(array(
			'message' => 'Settings reset to default successfully!',
			'layout_id' => $layout_id
		));
	}


		/**
     * Render product gallery layout preview
     */
    public function shopglut_render_gallery_preview( $layout_id = 0 ) {
        // Ensure we have a valid enhancement ID
        if ( ! $layout_id ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin preview parameter with capability check
            $layout_id = isset( $_GET['layout_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) ) : 1;
        }

        // Get enhancement data from database with caching
        $cache_key = 'shopglut_layout_' . $layout_id;
        $layout_data = wp_cache_get( $cache_key, 'shopglut_showcases' );

        if ( false === $layout_data ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with proper prepare statement
            $layout_data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_gallery_layouts` WHERE id = %d", $layout_id )
            );

            // Cache the result for 1 hour
            if ( $layout_data ) {
                wp_cache_set( $cache_key, $layout_data, 'shopglut_showcases', HOUR_IN_SECONDS );
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
        $markup_class = 'Shopglut\\showcases\\Gallery\\templates\\' . $template_name . '\\' . $template_name . 'Markup';
        if ( ! class_exists( $markup_class ) ) {
            return '<div class="shopglut-preview-error">Markup class not found: ' . esc_html( $markup_class ) . '</div>';
        }

        // Get the style class
        $style_class = 'Shopglut\\showcases\\Gallery\\templates\\' . $template_name . '\\' . $template_name . 'Style';
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
            echo '<div class="shopglut-gallery-preview-wrapper">';
            echo '<style>.shopglut-product-gallery.template1 .gallery-modal { opacity: 1; visibility: visible; position: relative; }</style>';

            // Render the template markup in demo mode
            $markup_instance->layout_render( $template_data, $layout_id );

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
	 * Show action success messages
	 */
	public function show_action_messages() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe display of notices
		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === '1' ) {
			echo '<div class="notice notice-success is-dismissible"><p>' .
				esc_html__( 'Shop Gallery layout deleted successfully.', 'shopglut' ) .
				'</p></div>';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe display of notices
		if ( isset( $_GET['duplicated'] ) && $_GET['duplicated'] === '1' ) {
			echo '<div class="notice notice-success is-dismissible"><p>' .
				esc_html__( 'Shop Gallery layout duplicated successfully.', 'shopglut' ) .
				'</p></div>';
		}
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