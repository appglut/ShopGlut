<?php
namespace Shopglut\enhancements\ProductSwatches;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Attribute Swatches Manager
 * Handles attribute-based swatches assignment and management
 */
class AttributeSwatchesManager {

	/**
	 * Constructor
	 */
	public function __construct() {
		// AJAX handlers
		add_action('wp_ajax_shopglut_get_woocommerce_attributes', array($this, 'ajax_get_attributes'));
		add_action('wp_ajax_shopglut_save_attribute_swatches', array($this, 'ajax_save_attribute_swatches'));
		add_action('wp_ajax_shopglut_get_attribute_layouts', array($this, 'ajax_get_attribute_layouts'));
		add_action('wp_ajax_shopglut_delete_attribute_layout', array($this, 'ajax_delete_attribute_layout'));
		add_action('wp_ajax_shopglut_reset_attribute_layout', array($this, 'ajax_reset_attribute_layout'));
		add_action('wp_ajax_shopglut_save_global_swatches_settings', array($this, 'ajax_save_global_swatches_settings'));
		add_action('wp_ajax_shopglut_reset_global_swatches_settings', array($this, 'ajax_reset_global_swatches_settings'));
	}

	/**
	 * Get all WooCommerce product attributes
	 *
	 * @return array List of attributes
	 */
	public function get_woocommerce_attributes() {
		$attributes = array();

		// Get product attributes (taxonomies)
		$taxonomies = wc_get_attribute_taxonomies();

		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$taxonomy_name = wc_attribute_taxonomy_name($taxonomy->attribute_name);
				$attributes[] = array(
					'id' => $taxonomy_name,
					'label' => $taxonomy->attribute_label,
					'name' => $taxonomy->attribute_name,
					'type' => $taxonomy->attribute_type,
					'order_by' => $taxonomy->attribute_orderby,
					'public' => $taxonomy->attribute_public,
				);
			}
		}

		return $attributes;
	}

	/**
	 * Get layouts assigned to attributes
	 *
	 * @return array Attribute layouts with assigned attributes
	 */
	public function get_attribute_layouts() {
		global $wpdb;

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// Get all attribute-based layouts
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layouts = $wpdb->get_results(
			"SELECT id, layout_name, layout_template, assigned_attributes, layout_settings, updated_at
			FROM `{$table_name}`
			WHERE assignment_type = 'attribute'
			ORDER BY updated_at DESC",
			ARRAY_A
		);

		$result = array();
		foreach ($layouts as $layout) {
			$assigned_attrs = !empty($layout['assigned_attributes']) ? json_decode($layout['assigned_attributes'], true) : array();

			$result[] = array(
				'id' => (int) $layout['id'],
				'name' => $layout['layout_name'],
				'template' => $layout['layout_template'],
				'assigned_attributes' => $assigned_attrs,
				'settings' => !empty($layout['layout_settings']) ? maybe_unserialize($layout['layout_settings']) : array(),
				'updated_at' => $layout['updated_at'],
			);
		}

		return $result;
	}

	/**
	 * Get attributes that are already assigned to layouts
	 *
	 * @return array Assigned attribute names
	 */
	public function get_assigned_attributes() {
		global $wpdb;

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$rows = $wpdb->get_col(
			"SELECT assigned_attributes FROM `{$table_name}`
			WHERE assignment_type = 'attribute'
			AND assigned_attributes IS NOT NULL
			AND assigned_attributes != ''"
		);

		$assigned = array();
		foreach ($rows as $row) {
			$attrs = json_decode($row, true);
			if (is_array($attrs)) {
				$assigned = array_merge($assigned, $attrs);
			}
		}

		return array_unique($assigned);
	}

	/**
	 * Check if attributes are available (not already assigned)
	 *
	 * @param array $attribute_names Attribute names to check
	 * @param int $exclude_layout_id Layout ID to exclude from check
	 * @return array Available attributes
	 */
	public function get_available_attributes($attribute_names, $exclude_layout_id = 0) {
		$assigned = $this->get_assigned_attributes();

		// If editing, remove attributes from the current layout from assigned list
		if ($exclude_layout_id > 0) {
			global $wpdb;
			$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$current_attrs = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT assigned_attributes FROM `{$table_name}` WHERE id = %d",
					$exclude_layout_id
				)
			);

			if ($current_attrs) {
				$current = json_decode($current_attrs, true);
				if (is_array($current)) {
					$assigned = array_diff($assigned, $current);
				}
			}
		}

		return array_diff($attribute_names, $assigned);
	}

	/**
	 * AJAX: Get WooCommerce attributes
	 */
	public function ajax_get_attributes() {
		check_ajax_referer('shopglut_swatches_manager', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		$attributes = $this->get_woocommerce_attributes();
		$assigned = $this->get_assigned_attributes();

		// Mark assigned attributes
		foreach ($attributes as &$attr) {
			$attr['is_assigned'] = in_array($attr['id'], $assigned);
		}

		wp_send_json_success(array(
			'attributes' => $attributes,
			'assigned' => $assigned,
		));
	}

	/**
	 * AJAX: Save attribute swatches layout
	 */
	public function ajax_save_attribute_swatches() {
		check_ajax_referer('shopglut_swatches_manager', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		$layout_id = isset($_POST['layout_id']) ? intval($_POST['layout_id']) : 0;
		$layout_name = isset($_POST['layout_name']) ? sanitize_text_field(wp_unslash($_POST['layout_name'])) : '';
		$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])) : 'template1';
		$attributes = isset($_POST['attributes']) ? array_map('sanitize_text_field', wp_unslash((array) $_POST['attributes'])) : array();
		$settings = isset($_POST['settings']) ? json_decode(stripslashes(sanitize_text_field(wp_unslash($_POST['settings']))), true) : array();

		if (empty($layout_name)) {
			wp_send_json_error(array('message' => __('Layout name is required', 'shopglut')));
		}

		if (empty($attributes)) {
			wp_send_json_error(array('message' => __('Please select at least one attribute', 'shopglut')));
		}

		// Check if attributes are available
		$available = $this->get_available_attributes($attributes, $layout_id);
		if (count($available) < count($attributes)) {
			$conflicts = array_diff($attributes, $available);
			wp_send_json_error(array(
				'message' => __('Some attributes are already assigned to other layouts', 'shopglut'),
				'conflicts' => $conflicts,
			));
		}

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		$data = array(
			'layout_name' => $layout_name,
			'layout_template' => $template,
			'assigned_attributes' => json_encode($attributes),
			'assignment_type' => 'attribute',
			'layout_settings' => serialize($settings),
			'updated_at' => current_time('mysql'),
		);

		if ($layout_id > 0) {
			// Update existing layout
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$result = $wpdb->update(
				$table_name,
				$data,
				array('id' => $layout_id),
				array('%s', '%s', '%s', '%s', '%s', '%s'),
				array('%d')
			);

			if ($result === false) {
				wp_send_json_error(array('message' => __('Failed to update layout', 'shopglut')));
			}
		} else {
			// Insert new layout
			$data['created_at'] = current_time('mysql');

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$result = $wpdb->insert(
				$table_name,
				$data,
				array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
			);

			if ($result === false) {
				wp_send_json_error(array('message' => __('Failed to create layout', 'shopglut')));
			}

			$layout_id = $wpdb->insert_id;
		}

		// Clear cache
		wp_cache_delete("shopglut_layout_data_{$layout_id}", 'shopglut_layouts');

		wp_send_json_success(array(
			'layout_id' => $layout_id,
			'message' => __('Layout saved successfully', 'shopglut'),
		));
	}

	/**
	 * AJAX: Get attribute layouts
	 */
	public function ajax_get_attribute_layouts() {
		check_ajax_referer('shopglut_swatches_manager', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		$layouts = $this->get_attribute_layouts();
		$assigned = $this->get_assigned_attributes();

		wp_send_json_success(array(
			'layouts' => $layouts,
			'assigned' => $assigned,
		));
	}

	/**
	 * AJAX: Delete attribute layout
	 */
	public function ajax_delete_attribute_layout() {
		check_ajax_referer('shopglut_swatches_manager', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		$layout_id = isset($_POST['layout_id']) ? intval($_POST['layout_id']) : 0;

		if ($layout_id <= 0) {
			wp_send_json_error(array('message' => __('Invalid layout ID', 'shopglut')));
		}

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->delete(
			$table_name,
			array('id' => $layout_id),
			array('%d')
		);

		if ($result === false) {
			wp_send_json_error(array('message' => __('Failed to delete layout', 'shopglut')));
		}

		// Clear cache
		wp_cache_delete("shopglut_layout_data_{$layout_id}", 'shopglut_layouts');

		wp_send_json_success(array(
			'message' => __('Layout deleted successfully', 'shopglut'),
		));
	}

	/**
	 * AJAX: Reset attribute layout (clear assignment but keep layout)
	 */
	public function ajax_reset_attribute_layout() {
		// Verify nonce
		$layout_id = isset($_POST['layout_id']) ? intval($_POST['layout_id']) : 0;
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

		if (!wp_verify_nonce($nonce, 'shopglut_reset_attribute_layout_' . $layout_id)) {
			wp_send_json_error(array('message' => __('Security check failed', 'shopglut')));
		}

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		if ($layout_id <= 0) {
			wp_send_json_error(array('message' => __('Invalid layout ID', 'shopglut')));
		}

		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// Clear the assigned_attributes field to remove the assignment
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->update(
			$table_name,
			array(
				'assigned_attributes' => '',
				'updated_at' => current_time('mysql')
			),
			array('id' => $layout_id),
			array('%s', '%s'),
			array('%d')
		);

		if ($result === false) {
			wp_send_json_error(array('message' => __('Failed to reset layout', 'shopglut')));
		}

		// Clear cache
		wp_cache_delete("shopglut_layout_data_{$layout_id}", 'shopglut_layouts');

		wp_send_json_success(array(
			'message' => __('Layout reset successfully. The attribute is now available for reassignment.', 'shopglut'),
		));
	}

	/**
	 * AJAX: Save global swatches settings
	 */
	public function ajax_save_global_swatches_settings() {
		check_ajax_referer('shopglut_global_settings', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		// Clear Button - Basic settings
		$clear_button_enable = isset($_POST['clear_button_enable']) ? rest_sanitize_boolean($_POST['clear_button_enable']) : true;
		$clear_button_text = isset($_POST['clear_button_text']) ? sanitize_text_field(wp_unslash($_POST['clear_button_text'])) : 'Clear';
		$clear_button_color = isset($_POST['clear_button_color']) ? sanitize_hex_color($_POST['clear_button_color']) : '#2271b1';
		$clear_button_font_size = isset($_POST['clear_button_font_size']) ? absint($_POST['clear_button_font_size']) : 14;
		$clear_button_margin_left = isset($_POST['clear_button_margin_left']) ? absint($_POST['clear_button_margin_left']) : 15;

		// Clear Button - Typography
		$clear_button_font_family = isset($_POST['clear_button_font_family']) ? sanitize_text_field(wp_unslash($_POST['clear_button_font_family'])) : 'inherit';
		$clear_button_font_weight = isset($_POST['clear_button_font_weight']) ? sanitize_text_field(wp_unslash($_POST['clear_button_font_weight'])) : '500';
		$clear_button_text_transform = isset($_POST['clear_button_text_transform']) ? sanitize_text_field(wp_unslash($_POST['clear_button_text_transform'])) : 'none';
		$clear_button_text_decoration = isset($_POST['clear_button_text_decoration']) ? sanitize_text_field(wp_unslash($_POST['clear_button_text_decoration'])) : 'underline';
		$clear_button_letter_spacing = isset($_POST['clear_button_letter_spacing']) ? floatval($_POST['clear_button_letter_spacing']) : 0;
		$clear_button_line_height = isset($_POST['clear_button_line_height']) ? absint($_POST['clear_button_line_height']) : 14;
		$clear_button_text_align = isset($_POST['clear_button_text_align']) ? sanitize_text_field(wp_unslash($_POST['clear_button_text_align'])) : 'left';

		// Clear Button - Background & Border
		$clear_button_background_color = isset($_POST['clear_button_background_color']) ? sanitize_text_field(wp_unslash($_POST['clear_button_background_color'])) : 'transparent';
		$clear_button_border_color = isset($_POST['clear_button_border_color']) ? sanitize_text_field(wp_unslash($_POST['clear_button_border_color'])) : 'transparent';
		$clear_button_border_width = isset($_POST['clear_button_border_width']) ? absint($_POST['clear_button_border_width']) : 0;
		$clear_button_border_radius = isset($_POST['clear_button_border_radius']) ? absint($_POST['clear_button_border_radius']) : 4;
		$clear_button_border_style = isset($_POST['clear_button_border_style']) ? sanitize_text_field(wp_unslash($_POST['clear_button_border_style'])) : 'solid';

		// Clear Button - Padding
		$clear_button_padding = array(
			'top' => isset($_POST['clear_button_padding_top']) ? absint($_POST['clear_button_padding_top']) : 6,
			'right' => isset($_POST['clear_button_padding_right']) ? absint($_POST['clear_button_padding_right']) : 12,
			'bottom' => isset($_POST['clear_button_padding_bottom']) ? absint($_POST['clear_button_padding_bottom']) : 6,
			'left' => isset($_POST['clear_button_padding_left']) ? absint($_POST['clear_button_padding_left']) : 12,
		);

		// Clear Button - Margins
		$clear_button_margin = array(
			'top' => isset($_POST['clear_button_margin_top']) ? absint($_POST['clear_button_margin_top']) : 0,
			'right' => isset($_POST['clear_button_margin_right']) ? absint($_POST['clear_button_margin_right']) : 0,
			'bottom' => isset($_POST['clear_button_margin_bottom']) ? absint($_POST['clear_button_margin_bottom']) : 0,
			'left' => isset($_POST['clear_button_margin_left']) ? absint($_POST['clear_button_margin_left']) : 15,
		);

		// Clear Button - Hover & Transition
		$clear_button_hover_color = isset($_POST['clear_button_hover_color']) ? sanitize_hex_color($_POST['clear_button_hover_color']) : '#135e96';
		$clear_button_hover_background = isset($_POST['clear_button_hover_background']) ? sanitize_text_field(wp_unslash($_POST['clear_button_hover_background'])) : 'rgba(34, 113, 177, 0.05)';
		$clear_button_transition_duration = isset($_POST['clear_button_transition_duration']) ? floatval($_POST['clear_button_transition_duration']) : 0.2;

		// Price Display - Basic settings
		$price_enable = isset($_POST['price_enable']) ? rest_sanitize_boolean($_POST['price_enable']) : true;
		$price_position = isset($_POST['price_position']) ? sanitize_text_field(wp_unslash($_POST['price_position'])) : 'after_clear_button';
		$price_color = isset($_POST['price_color']) ? sanitize_hex_color($_POST['price_color']) : '#2271b1';
		$price_font_size = isset($_POST['price_font_size']) ? absint($_POST['price_font_size']) : 16;
		$price_font_weight = isset($_POST['price_font_weight']) ? sanitize_text_field(wp_unslash($_POST['price_font_weight'])) : '600';
		$price_margin_top = isset($_POST['price_margin_top']) ? absint($_POST['price_margin_top']) : 12;

		// Price Display - Typography
		$price_font_family = isset($_POST['price_font_family']) ? sanitize_text_field(wp_unslash($_POST['price_font_family'])) : 'inherit';
		$price_line_height = isset($_POST['price_line_height']) ? absint($_POST['price_line_height']) : 14;
		$price_text_transform = isset($_POST['price_text_transform']) ? sanitize_text_field(wp_unslash($_POST['price_text_transform'])) : 'none';
		$price_letter_spacing = isset($_POST['price_letter_spacing']) ? floatval($_POST['price_letter_spacing']) : 0;
		$price_text_align = isset($_POST['price_text_align']) ? sanitize_text_field(wp_unslash($_POST['price_text_align'])) : 'left';
		$price_font_style = isset($_POST['price_font_style']) ? sanitize_text_field(wp_unslash($_POST['price_font_style'])) : 'normal';

		// Price Display - Background & Border
		$price_background_color = isset($_POST['price_background_color']) ? sanitize_text_field(wp_unslash($_POST['price_background_color'])) : 'transparent';
		$price_border_color = isset($_POST['price_border_color']) ? sanitize_text_field(wp_unslash($_POST['price_border_color'])) : 'transparent';
		$price_border_width = isset($_POST['price_border_width']) ? absint($_POST['price_border_width']) : 0;
		$price_border_radius = isset($_POST['price_border_radius']) ? absint($_POST['price_border_radius']) : 4;
		$price_border_style = isset($_POST['price_border_style']) ? sanitize_text_field(wp_unslash($_POST['price_border_style'])) : 'solid';

		// Price Display - Padding
		$price_padding = array(
			'top' => isset($_POST['price_padding_top']) ? absint($_POST['price_padding_top']) : 4,
			'right' => isset($_POST['price_padding_right']) ? absint($_POST['price_padding_right']) : 8,
			'bottom' => isset($_POST['price_padding_bottom']) ? absint($_POST['price_padding_bottom']) : 4,
			'left' => isset($_POST['price_padding_left']) ? absint($_POST['price_padding_left']) : 8,
		);

		// Price Display - Margins
		$price_margin = array(
			'top' => isset($_POST['price_margin_top']) ? absint($_POST['price_margin_top']) : 12,
			'right' => isset($_POST['price_margin_right']) ? absint($_POST['price_margin_right']) : 15,
			'bottom' => isset($_POST['price_margin_bottom']) ? absint($_POST['price_margin_bottom']) : 0,
			'left' => isset($_POST['price_margin_left']) ? absint($_POST['price_margin_left']) : 0,
		);

		// Actions position setting
		$actions_position = isset($_POST['actions_position']) ? sanitize_text_field(wp_unslash($_POST['actions_position'])) : 'new_line';

		// Variations form styling settings
		$variations_form = array();
		if (isset($_POST['variations_margin_bottom']) && $_POST['variations_margin_bottom'] !== '') {
			$variations_form['margin_bottom'] = absint($_POST['variations_margin_bottom']);
		}
		if (isset($_POST['variations_padding_top']) && $_POST['variations_padding_top'] !== '') {
			$variations_form['padding_top'] = absint($_POST['variations_padding_top']);
		}
		if (isset($_POST['variations_padding_right']) && $_POST['variations_padding_right'] !== '') {
			$variations_form['padding_right'] = absint($_POST['variations_padding_right']);
		}
		if (isset($_POST['variations_padding_bottom']) && $_POST['variations_padding_bottom'] !== '') {
			$variations_form['padding_bottom'] = absint($_POST['variations_padding_bottom']);
		}
		if (isset($_POST['variations_padding_left']) && $_POST['variations_padding_left'] !== '') {
			$variations_form['padding_left'] = absint($_POST['variations_padding_left']);
		}
		if (isset($_POST['variations_row_height']) && $_POST['variations_row_height'] !== '') {
			$variations_form['row_height'] = absint($_POST['variations_row_height']);
		}
		$variations_form['remove_borders'] = isset($_POST['variations_remove_borders']) ? rest_sanitize_boolean($_POST['variations_remove_borders']) : false;
		if (isset($_POST['variations_vertical_align']) && $_POST['variations_vertical_align'] !== '') {
			$variations_form['vertical_align'] = sanitize_text_field(wp_unslash($_POST['variations_vertical_align']));
		}
		// New fields for WooCommerce CSS overrides
		if (isset($_POST['variations_cell_padding_bottom']) && $_POST['variations_cell_padding_bottom'] !== '') {
			$variations_form['cell_padding_bottom'] = absint($_POST['variations_cell_padding_bottom']);
		}
		if (isset($_POST['variations_form_margin_bottom']) && $_POST['variations_form_margin_bottom'] !== '') {
			$variations_form['form_margin_bottom'] = absint($_POST['variations_form_margin_bottom']);
		}

		// Build settings array
		$global_settings = array(
			'clear_button' => array(
				'enable' => $clear_button_enable,
				'text' => $clear_button_text,
				'color' => $clear_button_color,
				'font_size' => $clear_button_font_size,
				// Typography
				'font_family' => $clear_button_font_family,
				'font_weight' => $clear_button_font_weight,
				'text_transform' => $clear_button_text_transform,
				'text_decoration' => $clear_button_text_decoration,
				'letter_spacing' => $clear_button_letter_spacing,
				'line_height' => $clear_button_line_height,
				'text_align' => $clear_button_text_align,
				// Background & Border
				'background_color' => $clear_button_background_color,
				'border_color' => $clear_button_border_color,
				'border_width' => $clear_button_border_width,
				'border_radius' => $clear_button_border_radius,
				'border_style' => $clear_button_border_style,
				// Padding
				'padding' => $clear_button_padding,
				// Margins
				'margin' => $clear_button_margin,
				// Hover & Transition
				'hover_color' => $clear_button_hover_color,
				'hover_background' => $clear_button_hover_background,
				'transition_duration' => $clear_button_transition_duration,
			),
			'price_display' => array(
				'enable' => $price_enable,
				'position' => $price_position,
				'color' => $price_color,
				'font_size' => $price_font_size,
				'font_weight' => $price_font_weight,
				// Typography
				'font_family' => $price_font_family,
				'line_height' => $price_line_height,
				'text_transform' => $price_text_transform,
				'letter_spacing' => $price_letter_spacing,
				'text_align' => $price_text_align,
				'font_style' => $price_font_style,
				// Background & Border
				'background_color' => $price_background_color,
				'border_color' => $price_border_color,
				'border_width' => $price_border_width,
				'border_radius' => $price_border_radius,
				'border_style' => $price_border_style,
				// Padding
				'padding' => $price_padding,
				// Margins
				'margin' => $price_margin,
			),
			'actions_position' => $actions_position,
			'variations_form' => $variations_form,
		);

		// Save to WordPress options
		$updated = update_option('shopglut_global_swatches_settings', $global_settings);

		// update_option returns false if value didn't change, but that's still success
		// Only treat as error if the saved value doesn't match what we tried to save
		$saved_value = get_option('shopglut_global_swatches_settings');

		if ($saved_value === $global_settings) {
			wp_send_json_success(array(
				'message' => __('Global settings saved successfully', 'shopglut'),
			));
		} else {
			wp_send_json_error(array('message' => __('Failed to save settings', 'shopglut')));
		}
	}

	/**
	 * Reset global swatches settings to defaults
	 */
	public function ajax_reset_global_swatches_settings() {
		check_ajax_referer('shopglut_global_settings', 'nonce');

		if (!current_user_can('manage_woocommerce')) {
			wp_send_json_error(array('message' => __('Permission denied', 'shopglut')));
		}

		// Default values for global settings
		$default_settings = array(
			'clear_button' => array(
				'enable' => true,
				'text' => 'Clear',
				'color' => '#000000',
				'bg_color' => '#ffffff',
				'bg_color_hover' => '#ffffff',
				'border_color' => '#ffffff',
				'border_width' => 1,
				'font_size' => 14,
				'font_weight' => '500',
				'border_radius' => 6,
				'padding_top' => 10,
				'padding_right' => 20,
				'padding_bottom' => 10,
				'padding_left' => 20,
				'margin' => 10,
			),
			'price_display' => array(
				'enable' => true,
				'position' => 'after_clear_button',
				'color' => '#000000',
				'bg_color' => '#ffffff',
				'bg_color_hover' => '#ffffff',
				'border_color' => '#ffffff',
				'border_width' => 0,
				'font_size' => 16,
				'font_weight' => '600',
				'border_radius' => 0,
				'padding_top' => 0,
				'padding_right' => 0,
				'padding_bottom' => 0,
				'padding_left' => 0,
				'margin' => 8,
			),
			'actions_position' => 'new_line',
			'variations_form' => array(),
		);

		// Save default settings to WordPress options
		$updated = update_option('shopglut_global_swatches_settings', $default_settings);

		// Verify the settings were saved correctly
		$saved_value = get_option('shopglut_global_swatches_settings');

		if ($saved_value === $default_settings) {
			wp_send_json_success(array(
				'message' => __('Settings reset to defaults successfully', 'shopglut'),
			));
		} else {
			wp_send_json_error(array('message' => __('Failed to reset settings', 'shopglut')));
		}
	}

	/**
	 * Render the attribute swatches manager page
	 */
	public function render_manager_page() {
		$all_attributes = $this->get_woocommerce_attributes();
		$layouts = $this->get_attribute_layouts();
		$assigned = $this->get_assigned_attributes();

		// Get available templates
		$templates = array(
			'template1' => __('Button Swatches', 'shopglut'),
			'template2' => __('Button Swatches v2', 'shopglut'),
			'template3' => __('Color Swatches', 'shopglut'),
			'template4' => __('Image Swatches', 'shopglut'),
		);

		?>
		<div class="wrap shopglut-attribute-swatches-manager">
			<h1 style="font-weight: 600;"><?php esc_html_e('Product Attribute Swatches', 'shopglut'); ?></h1>
			<p class="description"><?php esc_html_e('Assign swatch templates to specific product attributes. Each attribute can only be assigned to one layout.', 'shopglut'); ?></p>

			<div id="shopglut-swatches-manager-app">
				<div class="swatches-manager-container">
					<!-- Left Panel: Attributes List & Current Layouts -->
					<div class="swatches-manager-sidebar">
						<div class="sidebar-section">
							<h2><?php esc_html_e('Current Layouts', 'shopglut'); ?></h2>
							<div id="attribute-layouts-list" class="layouts-list">
								<?php if (empty($layouts)) : ?>
									<p class="no-layouts"><?php esc_html_e('No layouts created yet.', 'shopglut'); ?></p>
								<?php endif; ?>
							</div>
							<button id="create-new-layout-btn" class="button button-primary">
								<i class="fa-solid fa-plus"></i>
								<?php esc_html_e('Create New Layout', 'shopglut'); ?>
							</button>
						</div>

						<div class="sidebar-section">
							<h2><?php esc_html_e('Available Attributes', 'shopglut'); ?></h2>
							<div id="attributes-list" class="attributes-list">
								<?php foreach ($all_attributes as $attr) : ?>
									<div class="attribute-item <?php echo in_array($attr['id'], $assigned) ? 'assigned' : ''; ?>"
										 data-attribute="<?php echo esc_attr($attr['id']); ?>"
										 data-label="<?php echo esc_attr($attr['label']); ?>">
										<span class="attr-name"><?php echo esc_html($attr['label']); ?></span>
										<span class="attr-badge"><?php echo esc_html($attr['type']); ?></span>
										<?php if (in_array($attr['id'], $assigned)) : ?>
											<span class="assigned-badge"><?php esc_html_e('Assigned', 'shopglut'); ?></span>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Right Panel: Layout Editor -->
					<div class="swatches-manager-editor" id="layout-editor-panel" style="display: none;">
						<div class="editor-header">
							<button id="close-editor-btn" class="button button-secondary">
								<i class="fa-solid fa-times"></i>
							</button>
							<h2 id="editor-title"><?php esc_html_e('Create Layout', 'shopglut'); ?></h2>
						</div>

						<div class="editor-content">
							<form id="attribute-layout-form">
								<input type="hidden" id="edit-layout-id" name="layout_id" value="0">

								<!-- Layout Name -->
								<div class="form-group">
									<label for="layout-name-input"><?php esc_html_e('Layout Name', 'shopglut'); ?></label>
									<input type="text" id="layout-name-input" name="layout_name" class="regular-text" required>
								</div>

								<!-- Template Selection -->
								<div class="form-group">
									<label><?php esc_html_e('Select Template', 'shopglut'); ?></label>
									<div class="template-grid" id="template-selection-grid">
										<?php foreach ($templates as $template_id => $template_name) : ?>
											<div class="template-card" data-template="<?php echo esc_attr($template_id); ?>">
												<div class="template-preview">
													<img src="<?php echo esc_url(SHOPGLUT_URL . 'src/enhancements/ProductSwatches/templates/' . $template_id . '/preview.jpg'); ?>"
														 alt="<?php echo esc_attr($template_name); ?>"
														 onerror="this.src='<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/placeholder.png'); ?>'"
													>
												</div>
												<div class="template-info">
													<h4><?php echo esc_html($template_name); ?></h4>
												</div>
												<input type="radio" name="template" value="<?php echo esc_attr($template_id); ?>">
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<!-- Attribute Selection -->
								<div class="form-group">
									<label><?php esc_html_e('Select Attributes', 'shopglut'); ?></label>
									<p class="description"><?php esc_html_e('Choose which attributes this layout applies to:', 'shopglut'); ?></p>
									<div id="attribute-checkboxes" class="attribute-checkboxes">
										<?php foreach ($all_attributes as $attr) : ?>
											<label class="checkbox-label">
												<input type="checkbox"
													   name="attributes[]"
													   value="<?php echo esc_attr($attr['id']); ?>"
													   data-label="<?php echo esc_attr($attr['label']); ?>"
													   <?php echo in_array($attr['id'], $assigned) ? 'disabled' : ''; ?>
												>
												<span><?php echo esc_html($attr['label']); ?></span>
												<span class="attr-type-badge"><?php echo esc_html($attr['type']); ?></span>
												<?php if (in_array($attr['id'], $assigned)) : ?>
													<span class="assigned-note"><?php esc_html_e('(Already assigned)', 'shopglut'); ?></span>
												<?php endif; ?>
											</label>
										<?php endforeach; ?>
									</div>
								</div>

								<!-- Swatches Settings (loaded via AJAX for each template) -->
								<div id="swatches-settings-panel" class="form-group" style="display: none;">
									<label><?php esc_html_e('Customize Appearance', 'shopglut'); ?></label>
											<div id="template-settings-container"></div>
								</div>

								<!-- Form Actions -->
								<div class="form-actions">
									<button type="submit" class="button button-primary">
										<i class="fa-solid fa-save"></i>
										<?php esc_html_e('Save Layout', 'shopglut'); ?>
									</button>
									<button type="button" id="cancel-edit-btn" class="button button-secondary">
										<?php esc_html_e('Cancel', 'shopglut'); ?>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Templates for list items -->
			<template id="layout-item-template">
				<div class="layout-item" data-layout-id="">
					<div class="layout-info">
						<h3 class="layout-name"></h3>
						<div class="layout-meta">
							<span class="layout-template"></span>
							<span class="layout-attributes"></span>
						</div>
					</div>
					<div class="layout-actions">
						<button class="button button-small edit-layout-btn">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="button button-small delete-layout-btn">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
			</template>
		</div>

		<style>
			.shopglut-attribute-swatches-manager {
				max-width: 1400px;
				margin: 20px auto;
			}

			.swatches-manager-container {
				display: grid;
				grid-template-columns: 350px 1fr;
				gap: 20px;
				margin-top: 20px;
			}

			.swatches-manager-sidebar {
				display: flex;
				flex-direction: column;
				gap: 20px;
			}

			.sidebar-section {
				background: #fff;
				border: 1px solid #c3c4c7;
				padding: 15px;
				border-radius: 4px;
			}

			.sidebar-section h2 {
				margin-top: 0;
				font-size: 16px;
				padding-bottom: 10px;
				border-bottom: 1px solid #eee;
			}

			.layouts-list {
				max-height: 250px;
				overflow-y: auto;
			}

			.layout-item {
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 10px;
				border: 1px solid #e0e0e0;
				border-radius: 4px;
				margin-bottom: 8px;
				transition: all 0.2s;
			}

			.layout-item:hover {
				border-color: #2271b1;
				background: #f9f9f9;
			}

			.layout-name {
				margin: 0 0 5px 0;
				font-size: 14px;
				font-weight: 600;
			}

			.layout-meta {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
				font-size: 12px;
				color: #666;
			}

			.layout-attributes {
				color: #2271b1;
			}

			.layout-actions {
				display: flex;
				gap: 5px;
			}

			.attributes-list {
				max-height: 300px;
				overflow-y: auto;
			}

			.attribute-item {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 8px 10px;
				border: 1px solid #e0e0e0;
				border-radius: 4px;
				margin-bottom: 5px;
				font-size: 13px;
			}

			.attribute-item.assigned {
				background: #f0f0f0;
				opacity: 0.7;
			}

			.attr-badge {
				background: #e0e0e0;
				padding: 2px 6px;
				border-radius: 3px;
				font-size: 11px;
				text-transform: uppercase;
			}

			.assigned-badge {
				background: #d63638;
				color: #fff;
				padding: 2px 6px;
				border-radius: 3px;
				font-size: 11px;
			}

			.swatches-manager-editor {
				background: #fff;
				border: 1px solid #c3c4c7;
				border-radius: 4px;
				min-height: 600px;
			}

			.editor-header {
				display: flex;
				align-items: center;
				gap: 15px;
				padding: 15px 20px;
				border-bottom: 1px solid #eee;
			}

			.editor-header h2 {
				margin: 0;
				font-size: 18px;
			}

			.editor-content {
				padding: 20px;
			}

			.form-group {
				margin-bottom: 25px;
			}

			.form-group label {
				display: block;
				font-weight: 600;
				margin-bottom: 8px;
			}

			.form-group .description {
				margin-bottom: 12px;
				font-weight: 400;
				font-style: italic;
				color: #666;
			}

			.template-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
				gap: 15px;
			}

			.template-card {
				border: 2px solid #e0e0e0;
				border-radius: 6px;
				padding: 10px;
				cursor: pointer;
				transition: all 0.2s;
				position: relative;
			}

			.template-card:hover {
				border-color: #2271b1;
			}

			.template-card.selected {
				border-color: #2271b1;
				background: #f0f6fc;
			}

			.template-preview {
				aspect-ratio: 1;
				background: #f9f9f9;
				border-radius: 4px;
				overflow: hidden;
				margin-bottom: 10px;
			}

			.template-preview img {
				width: 100%;
				height: 100%;
				object-fit: cover;
			}

			.template-info h4 {
				margin: 0;
				font-size: 13px;
				text-align: center;
			}

			.template-card input[type="radio"] {
				position: absolute;
				top: 10px;
				right: 10px;
			}

			.attribute-checkboxes {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
				gap: 10px;
			}

			.checkbox-label {
				display: flex;
				align-items: center;
				gap: 8px;
				padding: 8px;
				border: 1px solid #e0e0e0;
				border-radius: 4px;
				cursor: pointer;
				transition: background 0.2s;
			}

			.checkbox-label:hover {
				background: #f9f9f9;
			}

			.checkbox-label input:disabled {
				cursor: not-allowed;
			}

			.checkbox-label input:disabled + span {
				color: #999;
			}

			.attr-type-badge {
				background: #e0e0e0;
				padding: 2px 6px;
				border-radius: 3px;
				font-size: 11px;
				margin-left: auto;
			}

			.assigned-note {
				color: #d63638;
				font-size: 11px;
				font-style: italic;
			}

			.form-actions {
				display: flex;
				gap: 10px;
				padding-top: 15px;
				border-top: 1px solid #eee;
			}

			.no-layouts {
				color: #666;
				font-style: italic;
				padding: 20px;
				text-align: center;
			}

			#template-settings-container {
				background: #f9f9f9;
				padding: 15px;
				border-radius: 4px;
				border: 1px solid #e0e0e0;
			}
		</style>

		<script>
			jQuery(document).ready(function($) {
				var shopglutSwatchesManager = {
					nonces: {
						manager: '<?php echo wp_create_nonce('shopglut_swatches_manager'); ?>'
					},
					currentLayouts: <?php echo json_encode($layouts); ?>,
					currentLayout: null,

					init: function() {
						this.renderLayoutsList();
						this.bindEvents();
					},

					bindEvents: function() {
						$(document).on('click', '#create-new-layout-btn', $.proxy(this.openEditor, this));
						$(document).on('click', '#close-editor-btn, #cancel-edit-btn', $.proxy(this.closeEditor, this));
						$(document).on('click', '.template-card', $.proxy(this.selectTemplate, this));
						$(document).on('submit', '#attribute-layout-form', $.proxy(this.saveLayout, this));
						$(document).on('click', '.edit-layout-btn', $.proxy(this.editLayout, this));
						$(document).on('click', '.delete-layout-btn', $.proxy(this.deleteLayout, this));
					},

					renderLayoutsList: function() {
						var container = $('#attribute-layouts-list');
						container.empty();

						if (this.currentLayouts.length === 0) {
							container.html('<p class="no-layouts"><?php esc_html_e('No layouts created yet.', 'shopglut'); ?></p>');
							return;
						}

						this.currentLayouts.forEach(function(layout) {
							var template = $('#layout-item-template').html();
							var $item = $(template);

							$item.attr('data-layout-id', layout.id);
							$item.find('.layout-name').text(layout.name);
							$item.find('.layout-template').text(layout.template);
							$item.find('.layout-attributes').text(layout.assigned_attributes.join(', '));

							container.append($item);
						});
					},

					openEditor: function(layoutId) {
						$('#layout-editor-panel').show();
						$('#editor-title').text(layoutId ? '<?php esc_html_e('Edit Layout', 'shopglut'); ?>' : '<?php esc_html_e('Create New Layout', 'shopglut'); ?>');
					},

					closeEditor: function() {
						$('#layout-editor-panel').hide();
						$('#attribute-layout-form')[0].reset();
						$('#edit-layout-id').val(0);
						$('.template-card').removeClass('selected');
						$('#swatches-settings-panel').hide();
						this.currentLayout = null;
					},

					selectTemplate: function(e) {
						var card = $(e.currentTarget);
						$('.template-card').removeClass('selected');
						card.addClass('selected');
						card.find('input[type="radio"]').prop('checked', true);

						// Load template settings
						var template = card.data('template');
						this.loadTemplateSettings(template);
					},

					loadTemplateSettings: function(template) {
						$('#swatches-settings-panel').show();
						$('#template-settings-container').html('<div class="spinner is-active"></div>');

						// AJAX to load template-specific settings
						// For now, show placeholder
						setTimeout(function() {
							$('#template-settings-container').html('<p><?php esc_html_e('Template settings will be loaded here.', 'shopglut'); ?></p>');
						}, 500);
					},

					saveLayout: function(e) {
						e.preventDefault();

						var form = $('#attribute-layout-form');
						var data = {
							action: 'shopglut_save_attribute_swatches',
							nonce: this.nonces.manager,
							layout_id: $('#edit-layout-id').val(),
							layout_name: $('#layout-name-input').val(),
							template: form.find('input[name="template"]:checked').val(),
							attributes: form.find('input[name="attributes[]"]:checked').map(function() {
								return $(this).val();
							}).get(),
							settings: {}
						};

						if (!data.template) {
							alert('<?php esc_html_e('Please select a template.', 'shopglut'); ?>');
							return;
						}

						if (data.attributes.length === 0) {
							alert('<?php esc_html_e('Please select at least one attribute.', 'shopglut'); ?>');
							return;
						}

						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: data,
							success: $.proxy(function(response) {
								if (response.success) {
									this.closeEditor();
									this.refreshLayouts();
									alert(response.data.message);
								} else {
									alert(response.data.message);
								}
							}, this),
							error: function() {
								alert('<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
							}
						});
					},

					editLayout: function(e) {
						var layoutId = $(e.currentTarget).closest('.layout-item').data('layout-id');
						var layout = this.currentLayouts.find(function(l) { return l.id === layoutId; });

						if (layout) {
							this.currentLayout = layout;
							$('#edit-layout-id').val(layout.id);
							$('#layout-name-input').val(layout.name);

							// Select template
							$('.template-card').removeClass('selected');
							$('.template-card[data-template="' + layout.template + '"]').addClass('selected');

							// Select attributes
							$('input[name="attributes[]"]').prop('checked', false).prop('disabled', false);
							layout.assigned_attributes.forEach(function(attr) {
								$('input[name="attributes[]"][value="' + attr + '"]').prop('checked', true).prop('disabled', true);
							});

							this.openEditor(true);
						}
					},

					deleteLayout: function(e) {
						if (!confirm('<?php esc_html_e('Are you sure you want to delete this layout?', 'shopglut'); ?>')) {
							return;
						}

						var layoutId = $(e.currentTarget).closest('.layout-item').data('layout-id');

						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'shopglut_delete_attribute_layout',
								nonce: this.nonces.manager,
								layout_id: layoutId
							},
							success: $.proxy(function(response) {
								if (response.success) {
									this.refreshLayouts();
									alert(response.data.message);
								} else {
									alert(response.data.message);
								}
							}, this),
							error: function() {
								alert('<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
							}
						});
					},

					refreshLayouts: function() {
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'shopglut_get_attribute_layouts',
								nonce: this.nonces.manager
							},
							success: $.proxy(function(response) {
								if (response.success) {
									this.currentLayouts = response.data.layouts;
									this.renderLayoutsList();
									// Update assigned attributes
									this.updateAssignedAttributes(response.data.assigned);
								}
							}, this)
						});
					},

					updateAssignedAttributes: function(assigned) {
						$('.attribute-item').removeClass('assigned');
						$('.attribute-item .assigned-badge').remove();
						$('input[name="attributes[]"]').prop('disabled', false);
						$('.assigned-note').remove();

						assigned.forEach(function(attr) {
							$('.attribute-item[data-attribute="' + attr + '"]').addClass('assigned')
								.append('<span class="assigned-badge"><?php esc_html_e('Assigned', 'shopglut'); ?></span>');
							$('input[name="attributes[]"][value="' + attr + '"]')
								.prop('disabled', true)
								.parent()
								.append('<span class="assigned-note"><?php esc_html_e('(Already assigned)', 'shopglut'); ?></span>');
						});
					}
				};

				shopglutSwatchesManager.init();
			});
		</script>
		<?php
	}

	public static function get_instance() {
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}
}
