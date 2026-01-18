<?php
namespace Shopglut\tools\productCustomField;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductCustomFieldDataManage {

	public function __construct() {
		// AJAX handlers for product custom field save and reset
		add_action('wp_ajax_save_shopg_product_custom_field_layoutdata', array($this, 'save_product_custom_field_data'));
		add_action('wp_ajax_reset_shopg_product_custom_field_settings', array($this, 'reset_product_custom_field_settings'));

		// Hook to save product custom field data
		add_action('save_shopg_product_custom_field_data', array($this, 'process_product_custom_field_save'));
	}

	/**
	 * Process product custom field save when form is submitted
	 */
	public function process_product_custom_field_save($field_id) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in form submission
		if (!isset($_POST['shopg_product_custom_field_layouts_nonce']) || !isset($_POST['publish'])) {
			return;
		}

		// Verify nonce
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce check happens below
		if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shopg_product_custom_field_layouts_nonce'])), 'shopg_product_custom_field_layouts')) {
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

		// Get field name
		$field_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';

		if (empty($field_name)) {
			return;
		}

		// Get field ID
		$field_id = isset($_POST['shopg_shop_layoutid']) ? absint($_POST['shopg_shop_layoutid']) : 0;

		if ($field_id <= 0) {
			return;
		}

		// Collect all custom field settings
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Data is sanitized below
		$field_settings = isset($_POST['shopg_options_settings']) ? wp_unslash($_POST['shopg_options_settings']) : array();
		$field_settings = $this->sanitize_field_settings($field_settings);

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();

		// Prepare data for saving
		$data = array(
			'field_name' => $field_name,
			'field_settings' => serialize($field_settings),
			'updated_at' => current_time('mysql')
		);

		// Update existing field
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->update(
			$table_name,
			$data,
			array('id' => $field_id),
			array('%s', '%s', '%s'),
			array('%d')
		);

		if ($result === false) {
			// Handle error
			add_action('admin_notices', function() use ($wpdb) {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php echo esc_html('Error saving custom field: ' . $wpdb->last_error); ?></p>
				</div>
				<?php
			});
		} else {
			// Show success message
			add_action('admin_notices', function() {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e('Custom field saved successfully!', 'shopglut'); ?></p>
				</div>
				<?php
			});
		}
	}

	/**
	 * Sanitize field settings recursively
	 */
	private function sanitize_field_settings($settings) {
		if (!is_array($settings)) {
			return sanitize_textarea_field($settings);
		}

		$sanitized = array();
		foreach ($settings as $key => $value) {
			$sanitized_key = sanitize_key($key);
			if (is_array($value)) {
				$sanitized[$sanitized_key] = $this->sanitize_field_settings($value);
			} else {
				// Use sanitize_textarea_field to preserve newlines for content fields
				if (in_array($key, ['field_content', 'field_options', 'textarea_content', 'radio_options'])) {
					$sanitized[$sanitized_key] = sanitize_textarea_field($value);
				} else {
					$sanitized[$sanitized_key] = sanitize_text_field($value);
				}
			}
		}
		return $sanitized;
	}

	/**
	 * Save product custom field data from AJAX
	 */
	public function save_product_custom_field_data() {
		// Get settings data first to extract nonce
		$settings_raw = isset($_POST['shopg_options_settings']) ? sanitize_text_field(wp_unslash($_POST['shopg_options_settings'])) : '';

		// Decode if it's JSON
		if (is_string($settings_raw)) {
			$settings_decoded = json_decode($settings_raw, true);
			if ($settings_decoded && is_array($settings_decoded)) {
				$settings_raw = $settings_decoded;
			}
		}

		// Extract nonce from settings
		$nonce = isset($settings_raw['shopg_product_custom_field_layouts_nonce']) ? sanitize_text_field($settings_raw['shopg_product_custom_field_layouts_nonce']) : '';

		// Check nonce
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens below
		if (empty($nonce) || !wp_verify_nonce($nonce, 'shopg_product_custom_field_layouts')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		// Get and sanitize data
		$field_id = isset($_POST['shopg_shop_layoutid']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_shop_layoutid']))) : 0;
		$field_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';

		// Remove nonce from settings before saving
		if (isset($settings_raw['shopg_product_custom_field_layouts_nonce'])) {
			unset($settings_raw['shopg_product_custom_field_layouts_nonce']);
		}
		if (isset($settings_raw['shopg_shop_layoutid'])) {
			unset($settings_raw['shopg_shop_layoutid']);
		}
		if (isset($settings_raw['layout_name'])) {
			unset($settings_raw['layout_name']);
		}
		if (isset($settings_raw['layout_template'])) {
			unset($settings_raw['layout_template']);
		}

		// Sanitize the settings
		$field_settings = $this->sanitize_field_settings($settings_raw);

		// Validate required fields
		if (empty($field_name)) {
			wp_send_json_error('Field name is required');
			return;
		}

		if ($field_id <= 0) {
			wp_send_json_error('Invalid field ID');
			return;
		}

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();

		// Prepare data for saving
		$data = array(
			'field_name' => $field_name,
			'field_settings' => serialize($field_settings),
			'updated_at' => current_time('mysql')
		);

		// Update existing field
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->update(
			$table_name,
			$data,
			array('id' => $field_id),
			array('%s', '%s', '%s'),
			array('%d')
		);

		if ($result === false) {
			wp_send_json_error(array(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				'message' => 'Database error: ' . $wpdb->last_error
			));
			return;
		}

		wp_send_json_success(array(
			'message' => 'Custom field saved successfully',
			'field_id' => $field_id
		));
	}

	/**
	 * Reset product custom field settings to default
	 */
	public function reset_product_custom_field_settings() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_product_custom_field')) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Insufficient permissions');
			return;
		}

		$field_id = isset($_POST['field_id']) ? intval(sanitize_text_field(wp_unslash($_POST['field_id']))) : 0;

		if ($field_id <= 0) {
			wp_send_json_error('Invalid field ID');
			return;
		}

		global $wpdb;
		$table_name = esc_sql(\Shopglut\ShopGlutDatabase::table_product_custom_field_settings());

		// Get current field data with caching
		$cache_key = "shopglut_product_custom_field_exists_{$field_id}";
		$field_row = wp_cache_get($cache_key, 'shopglut_product_custom_field');

		if (false === $field_row) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation, caching implemented below
			$field_row = $wpdb->get_row(
				sprintf("SELECT id FROM %s WHERE id = %d", $table_name, absint($field_id)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated parameter
			);

			// Cache for 5 minutes since this is validation data
			wp_cache_set($cache_key, $field_row, 'shopglut_product_custom_field', 5 * MINUTE_IN_SECONDS);
		}

		if (!$field_row) {
			wp_send_json_error('Custom field not found');
			return;
		}

		// Clear settings (set to empty array so defaults will be used)
		$empty_settings = array();

		// Update the field with empty settings
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->update(
			$table_name,
			array(
				'field_settings' => serialize($empty_settings),
				'updated_at' => current_time('mysql')
			),
			array('id' => $field_id),
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

		wp_send_json_success(array(
			'message' => 'Custom field settings reset to default successfully!',
			'field_id' => $field_id
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
