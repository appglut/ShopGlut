<?php
/**
 * Product Custom Field Handler
 *
 * Displays custom fields on WooCommerce product pages
 */

namespace Shopglut\tools\productCustomField;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductCustomFieldHandler {

    private static $instance = null;

    public function __construct() {
        // Only hook into product pages
        add_action('wp', array($this, 'init_hooks'));
    }

    /**
     * Initialize hooks only on product pages
     */
    public function init_hooks() {
        if (!is_product()) {
            return;
        }

        $current_theme = get_template();

        switch ($current_theme) {
            case 'astra':
                $this->register_astra_hooks();
                break;
            default:
                $this->register_woocommerce_hooks();
                break;
        }
    }

    /**
     * Register Astra theme hooks
     */
    private function register_astra_hooks() {
        // Add custom fields at various positions
        add_action('astra_woo_single_title_before', array($this, 'display_custom_fields_before_title'));
        add_action('astra_woo_single_title_after', array($this, 'display_custom_fields_after_title'));
        add_action('astra_woo_single_price_before', array($this, 'display_custom_fields_before_price'));
        add_action('astra_woo_single_price_after', array($this, 'display_custom_fields_after_price'));
        add_action('astra_woo_single_short_description_before', array($this, 'display_custom_fields_before_description'));
        add_action('astra_woo_single_short_description_after', array($this, 'display_custom_fields_after_description'));

        // Astra-specific add to cart hooks
        add_action('astra_woo_single_add_to_cart_before', array($this, 'display_custom_fields_before_add_to_cart'));
        add_action('astra_woo_single_add_to_cart_after', array($this, 'display_custom_fields_after_add_to_cart'));

        // Astra meta hooks
        add_action('astra_woo_single_meta_before', array($this, 'display_custom_fields_before_meta'));
        add_action('astra_woo_single_meta_after', array($this, 'display_custom_fields_after_meta'));
    }

    /**
     * Register WooCommerce hooks
     */
    private function register_woocommerce_hooks() {
        // Add custom fields at key positions with specific priorities
        add_action('woocommerce_single_product_summary', array($this, 'display_custom_fields_after_title'), 6);
        add_action('woocommerce_single_product_summary', array($this, 'display_custom_fields_after_price'), 15);
        add_action('woocommerce_single_product_summary', array($this, 'display_custom_fields_after_description'), 25);
        add_action('woocommerce_single_product_summary', array($this, 'display_custom_fields_after_add_to_cart'), 999); // Very high priority to show at the end

        // Additional hooks
        add_action('woocommerce_before_add_to_cart_form', array($this, 'display_custom_fields_before_add_to_cart'));
        add_action('woocommerce_after_add_to_cart_form', array($this, 'display_custom_fields_after_add_to_cart'));
        add_action('woocommerce_product_meta_start', array($this, 'display_custom_fields_before_meta'));
        add_action('woocommerce_product_meta_end', array($this, 'display_custom_fields_after_meta'));

        // Add a low priority hook to ensure fields show after add to cart
        add_action('woocommerce_after_add_to_cart_button', array($this, 'display_custom_fields_after_add_to_cart'));

        // Fallback: Add fields right after the form closes using WordPress footer hook
        add_action('wp_footer', array($this, 'inject_after_add_to_cart_fields'));
    }

    /**
     * Display custom fields before title
     */
    public function display_custom_fields_before_title() {
        $this->display_custom_fields_by_position('before_title');
    }

    /**
     * Display custom fields after title
     */
    public function display_custom_fields_after_title() {
        $this->display_custom_fields_by_position('after_title');
    }

    /**
     * Display custom fields before price
     */
    public function display_custom_fields_before_price() {
        $this->display_custom_fields_by_position('before_price');
    }

    /**
     * Display custom fields after price
     */
    public function display_custom_fields_after_price() {
        $this->display_custom_fields_by_position('after_price');
    }

    /**
     * Display custom fields before description
     */
    public function display_custom_fields_before_description() {
        $this->display_custom_fields_by_position('before_description');
    }

    /**
     * Display custom fields after description
     */
    public function display_custom_fields_after_description() {
        $this->display_custom_fields_by_position('after_description');
    }

    /**
     * Display custom fields after add to cart
     */
    public function display_custom_fields_after_add_to_cart() {
        $this->display_custom_fields_by_position('after_add_to_cart');
    }

    /**
     * Display custom fields before add to cart
     */
    public function display_custom_fields_before_add_to_cart() {
        $this->display_custom_fields_by_position('before_add_to_cart');
    }

    /**
     * Display custom fields before meta
     */
    public function display_custom_fields_before_meta() {
        $this->display_custom_fields_by_position('before_meta');
    }

    /**
     * Display custom fields after meta
     */
    public function display_custom_fields_after_meta() {
        $this->display_custom_fields_by_position('after_meta');
    }

    /**
     * Display custom fields for specific position
     */
    public function display_custom_fields_by_position($position) {
        // Get custom fields for this position
        $fields = $this->get_custom_fields_for_position($position);

        if (empty($fields)) {
            echo "<!-- DEBUG: No fields found for position: " . esc_html($position) . " -->";
            return;
        }

        echo "<!-- DEBUG: Found " . esc_html(count($fields)) . " fields for position: " . esc_html($position) . " -->";

        // Display each field
        foreach ($fields as $index => $field) {
            echo "<!-- DEBUG: Rendering field " . esc_html($index) . " with label: " . (isset($field['field_label']) ? esc_html($field['field_label']) : 'no label') . " -->";
            $this->render_field($field);
        }
    }

    /**
     * Get custom fields for specific position
     */
    private function get_custom_fields_for_position($position) {
        global $wpdb;

        $table_name = esc_sql($wpdb->prefix . 'shopglut_product_custom_field_settings');
        $current_product_id = get_the_ID();

        // Try to get from cache first
        $cache_key = 'shopglut_custom_field_settings';
        $settings = wp_cache_get($cache_key, 'shopglut_custom_fields');

        if (false === $settings) {
            // Get all custom field settings from database
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with interpolated variable
            $settings = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
                sprintf("SELECT field_settings FROM %s LIMIT 1", $table_name) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, safe for custom table operations
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $settings, 'shopglut_custom_fields', HOUR_IN_SECONDS);
        }

        if (empty($settings)) {
            return array();
        }

        $unserialized = maybe_unserialize($settings);

        if (!isset($unserialized['shopg_product_custom_field_settings'])) {
            return array();
        }

        $settings_data = $unserialized['shopg_product_custom_field_settings'];

        if (!isset($settings_data['custom_fields'])) {
            return array();
        }

        $custom_fields = $settings_data['custom_fields'];
        $selected_products = isset($settings_data['select_products']) ? $settings_data['select_products'] : array();
        $matching_fields = array();

        echo "<!-- DEBUG: Total custom fields: " . count($custom_fields) . " -->";

        foreach ($custom_fields as $index => $field) {
            $field_pos = isset($field['content_position']) ? $field['content_position'] : '';
            $field_type = isset($field['field_type']) ? $field['field_type'] : 'unknown';
            $field_label = isset($field['field_label']) ? $field['field_label'] : 'no label';

            echo "<!-- DEBUG: Field " . esc_html($index) . " - Label: " . esc_html($field_label) . ", Type: " . esc_html($field_type) . ", Position: " . esc_html($field_pos) . " -->";

            // Check if field should display at this position (either content_position or radio_content_position)
            $field_matches = false;
            if ($field_pos === $position) {
                $field_matches = true;
                echo "<!-- DEBUG: Field " . esc_html($index) . " matches position " . esc_html($position) . " -->";
            } elseif (isset($field['radio_content_position']) && $field['radio_content_position'] === $position) {
                $field_matches = true;
                echo "<!-- DEBUG: Field " . esc_html($index) . " matches radio position " . esc_html($position) . " -->";
            }

            if ($field_matches) {
                // Check product selection
                if ($this->should_display_for_product_new($selected_products, $current_product_id)) {
                    $matching_fields[] = $field;
                    echo "<!-- DEBUG: Field " . esc_html($index) . " ADDED to matching fields -->";
                } else {
                    echo "<!-- DEBUG: Field " . esc_html($index) . " FAILED product selection check -->";
                }
            }
        }
        return $matching_fields;
    }

    /**
     * Check if field should display for current product
     */
    private function should_display_for_product($field, $product_id) {
        if (!isset($field['product_selection'])) {
            return false;
        }

        $product_selection = $field['product_selection'];

        // Ensure product_selection is an array
        if (!is_array($product_selection)) {
            $product_selection = array($product_selection);
        }

        // Clean the product selection array - convert to strings and trim
        $cleaned_selection = array_map('trim', array_map('strval', $product_selection));
        $current_product_id_str = (string) $product_id;

        // Check if "all" or "all-products" is in the selection
        if (in_array('all', $cleaned_selection) || in_array('all-products', $cleaned_selection)) {
            return true;
        }

        // Check if current product ID is in the selection
        if (in_array($current_product_id_str, $cleaned_selection, true)) {
            return true;
        }

        return false;
    }

    /**
     * Check if field should display for current product (new method)
     */
    private function should_display_for_product_new($selected_products, $product_id) {
        // Clean the product selection array
        $cleaned_selection = array_map('trim', array_map('strval', $selected_products));
        $current_product_id_str = (string) $product_id;

        // Check if "all" is in the selection
        if (in_array('all', $cleaned_selection)) {
            return true;
        }

        // Check if current product ID is in the selection
        if (in_array($current_product_id_str, $cleaned_selection, true)) {
            return true;
        }

        return false;
    }

    /**
     * Render individual field
     */
    private function render_field($field) {
        if (!isset($field['field_type'])) {
            return;
        }

        // Only handle textarea/design fields
        switch ($field['field_type']) {
            case 'textarea':
                $this->render_textarea_field($field);
                break;
            case 'design':
                $this->render_textarea_field($field);
                break;
            default:
                // Treat any field type as design/textarea
                $this->render_textarea_field($field);
                break;
        }
    }

    /**
     * Render textarea field
     */
    private function render_textarea_field($field) {
        // Use the correct key from database: field_content instead of textarea_content
        if (!isset($field['field_content']) || empty($field['field_content'])) {
            return;
        }

        $content = $field['field_content'];
        // Replace any single line breaks with proper newlines if they were stripped
        $content = str_replace(['\\n', '\\r\\n'], ["\n", "\r\n"], $content);
        // Use the correct key from database: textarea_field_design instead of textarea_design
        $design = isset($field['textarea_field_design']) ? $field['textarea_field_design'] : 'simple_text';

        // Add field label if exists
        $label = isset($field['field_label']) && !empty($field['field_label']) ? esc_html($field['field_label']) : '';

        echo '<div class="shopglut-custom-field shopglut-textarea-field shopglut-design-' . esc_attr($design) . '">';

        // Show field label if exists
        if (!empty($label)) {
            echo '<h4 class="shopglut-field-label">' . $label . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $label is already escaped with esc_html() above
        }

        switch ($design) {
            case 'simple_list':
                $this->render_simple_list($content);
                break;
            case 'bullet_points':
                $this->render_bullet_points($content);
                break;
            case 'numbered_list':
                $this->render_numbered_list($content);
                break;
            case 'paragraphs':
                $this->render_paragraphs($content);
                break;
            case 'cards':
                $this->render_cards($content);
                break;
            case 'features_grid':
                $this->render_features_grid($content);
                break;
            case 'info_boxes':
                $this->render_info_boxes($content);
                break;
            case 'tags':
                $this->render_tags($content);
                break;
            case 'timeline':
                $this->render_timeline($content);
                break;
            default:
                echo '<div class="shopglut-simple-text">' . wpautop(wp_kses_post($content)) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is properly escaped with wp_kses_post
                break;
        }

        echo '</div>';
    }

    /**
     * Render radio field
     */
    private function render_radio_field($field) {
        // Use the correct key from database: field_options instead of radio_options
        if (!isset($field['field_options'])) {
            return;
        }

        $options_text = $field['field_options'];
        // Replace escaped newlines with real newlines
        $options_text = str_replace(['\\n', '\\r\\n'], ["\n", "\r\n"], $options_text);
        // Split options by newline (handle both \n and \r\n)
        $options = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $options_text)));

        if (empty($options)) {
            return;
        }

        // Use the correct key from database: radio_field_design instead of radio_design
        $design = isset($field['radio_field_design']) ? $field['radio_field_design'] : 'basic_radio';

        echo '<div class="shopglut-custom-field shopglut-radio-field shopglut-design-' . esc_attr($design) . '">';

        switch ($design) {
            case 'basic_radio':
                $this->render_basic_radio($options);
                break;
            case 'button_group':
                $this->render_button_group($options);
                break;
            case 'card_selection':
                $this->render_card_selection($options);
                break;
            case 'toggle_switch':
                $this->render_toggle_switch($options);
                break;
            default:
                $this->render_basic_radio($options);
                break;
        }

        echo '</div>';
    }

    /**
     * Design rendering methods for textarea
     */
    private function render_simple_list($content) {
        // Handle both \n and \r\n line breaks
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<ul class="shopglut-simple-list" style="list-style: none; padding: 0; margin: 10px 0;">';
            foreach ($lines as $line) {
                echo '<li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0; position: relative;">' . esc_html($line) . '</li>';
            }
            echo '</ul>';
        }
    }

    private function render_bullet_points($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<ul class="shopglut-bullet-points" style="list-style: none; padding: 0; margin: 15px 0;">';
            foreach ($lines as $line) {
                echo '<li style="padding: 10px 0 10px 25px; position: relative; border-bottom: 1px solid #f5f5f5;">';
                echo '<span style="position: absolute; left: 0; top: 12px; color: #4CAF50; font-size: 16px; font-weight: bold;">•</span>';
                echo esc_html($line);
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    private function render_numbered_list($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<ol class="shopglut-numbered-list" style="list-style: none; padding: 0; margin: 15px 0; counter-reset: step-counter;">';
            foreach ($lines as $index => $line) {
                echo '<li style="padding: 12px 0 12px 35px; position: relative; border-bottom: 1px solid #f5f5f5; counter-increment: step-counter;">';
                echo '<span style="position: absolute; left: 0; top: 12px; background: #2196F3; color: white; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: bold;">' . esc_html($index + 1) . '</span>';
                echo esc_html($line);
                echo '</li>';
            }
            echo '</ol>';
        }
    }

    private function render_paragraphs($content) {
        echo '<div class="shopglut-paragraphs" style="margin: 15px 0; line-height: 1.6;">';
        echo wpautop(wp_kses_post($content)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is properly escaped with wp_kses_post
        echo '</div>';
    }

    private function render_cards($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<div class="shopglut-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">';
            foreach ($lines as $line) {
                echo '<div class="shopglut-card" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s; cursor: default;">';
                echo '<div style="font-weight: 500; color: #333;">' . esc_html($line) . '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }

    private function render_features_grid($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<div class="shopglut-features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin: 15px 0;">';
            foreach ($lines as $line) {
                echo '<div class="shopglut-feature-item" style="display: flex; align-items: center; padding: 12px; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #4CAF50;">';
                echo '<div class="feature-icon" style="color: #4CAF50; font-weight: bold; margin-right: 12px; font-size: 18px;">✓</div>';
                echo '<div class="feature-text" style="color: #333; font-weight: 500;">' . esc_html($line) . '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }

    private function render_info_boxes($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<div class="shopglut-info-boxes" style="margin: 15px 0;">';
            foreach ($lines as $line) {
                echo '<div class="shopglut-info-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);">';
                echo '<div style="display: flex; align-items: center;">';
                echo '<div style="background: rgba(255,255,255,0.2); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-weight: bold;">ℹ</div>';
                echo '<div>' . esc_html($line) . '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }

    private function render_tags($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<div class="shopglut-tags" style="margin: 15px 0;">';
            echo '<div style="display: flex; flex-wrap: wrap; gap: 8px;">';
            foreach ($lines as $line) {
                echo '<span class="shopglut-tag" style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #bbdefb;">';
                echo esc_html($line);
                echo '</span>';
            }
            echo '</div>';
            echo '</div>';
        }
    }

    private function render_timeline($content) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content)));
        if (!empty($lines)) {
            echo '<div class="shopglut-timeline" style="margin: 20px 0; position: relative;">';
            echo '<div style="position: absolute; left: 20px; top: 0; bottom: 0; width: 2px; background: #e0e0e0;"></div>';
            foreach ($lines as $index => $line) {
                echo '<div class="shopglut-timeline-item" style="position: relative; padding-left: 50px; margin-bottom: 20px;">';
                echo '<div style="position: absolute; left: 14px; top: 5px; width: 14px; height: 14px; border-radius: 50%; background: #4CAF50; border: 3px solid white; box-shadow: 0 0 0 2px #e0e0e0;"></div>';
                echo '<div style="background: #f5f5f5; padding: 12px; border-radius: 6px; border-left: 3px solid #4CAF50;">';
                echo esc_html($line);
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }

    /**
     * Design rendering methods for radio
     */
    private function render_basic_radio($options) {
        echo '<div class="shopglut-basic-radio">';
        foreach ($options as $index => $option) {
            $option_text = is_array($option) ? $option['label'] : $option;
            $option_value = is_array($option) ? $option['value'] : sanitize_title($option);

            echo '<label class="radio-label">';
            echo '<input type="radio" name="shopglut_custom_radio_' . esc_attr(get_the_ID()) . '" value="' . esc_attr($option_value) . '"> ';
            echo esc_html($option_text);
            echo '</label><br>';
        }
        echo '</div>';
    }

    private function render_button_group($options) {
        echo '<div class="shopglut-button-group">';
        foreach ($options as $index => $option) {
            $option_text = is_array($option) ? $option['label'] : $option;
            $option_value = is_array($option) ? $option['value'] : sanitize_title($option);

            echo '<label class="button-label">';
            echo '<input type="radio" name="shopglut_custom_radio_' . esc_attr(get_the_ID()) . '" value="' . esc_attr($option_value) . '"> ';
            echo '<span class="button-text">' . esc_html($option_text) . '</span>';
            echo '</label>';
        }
        echo '</div>';
    }

    private function render_card_selection($options) {
        echo '<div class="shopglut-card-selection">';
        foreach ($options as $index => $option) {
            $option_text = is_array($option) ? $option['label'] : $option;
            $option_value = is_array($option) ? $option['value'] : sanitize_title($option);

            echo '<label class="card-label">';
            echo '<input type="radio" name="shopglut_custom_radio_' . esc_attr(get_the_ID()) . '" value="' . esc_attr($option_value) . '"> ';
            echo '<div class="card">' . esc_html($option_text) . '</div>';
            echo '</label>';
        }
        echo '</div>';
    }

    private function render_toggle_switch($options) {
        echo '<div class="shopglut-toggle-switch">';
        foreach ($options as $index => $option) {
            $option_text = is_array($option) ? $option['label'] : $option;
            $option_value = is_array($option) ? $option['value'] : sanitize_title($option);

            echo '<label class="switch-label">';
            echo '<input type="radio" name="shopglut_custom_radio_' . esc_attr(get_the_ID()) . '" value="' . esc_attr($option_value) . '"> ';
            echo '<span class="switch-slider"></span>';
            echo '<span class="switch-text">' . esc_html($option_text) . '</span>';
            echo '</label>';
        }
        echo '</div>';
    }

    /**
     * Inject after add to cart fields using JavaScript fallback
     */
    public function inject_after_add_to_cart_fields() {
        if (!is_product()) {
            return;
        }

        // Get fields for after_add_to_cart position
        $fields = $this->get_custom_fields_for_position('after_add_to_cart');

        if (empty($fields)) {
            return;
        }

        // Capture the output
        ob_start();
        echo '<!-- DEBUG: JavaScript injection for after_add_to_cart -->';
        foreach ($fields as $field) {
            $this->render_field($field);
        }
        $fields_html = ob_get_clean();

        // Add JavaScript to inject the fields
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Try multiple insertion points with delays
            var $html = <?php echo json_encode($fields_html); ?>;

            function injectFields() {
                console.log('Attempting to inject after_add_to_cart fields...');

                // Try to insert after the add to cart form
                if ($('.cart').length) {
                    console.log('Found .cart, inserting after it');
                    $('.cart').after($html);
                    return true;
                }
                // Try to insert after the wishlist/comparison buttons (more specific)
                else if ($('.shopglut-comparison-button-wrapper').length) {
                    console.log('Found comparison buttons, inserting after them');
                    $('.shopglut-comparison-button-wrapper').parent().after($html);
                    return true;
                }
                // Try to insert after the add to cart button
                else if ($('.single_add_to_cart_button').length) {
                    console.log('Found add to cart button, inserting after form');
                    $('.single_add_to_cart_button').closest('form').after($html);
                    return true;
                }
                // Try to insert before product meta
                else if ($('.product_meta').length) {
                    console.log('Found product meta, inserting before it');
                    $('.product_meta').before($html);
                    return true;
                }
                // Fallback: insert at end of summary
                else if ($('.summary.entry-summary').length) {
                    console.log('Found summary, appending to end');
                    $('.summary.entry-summary').append($html);
                    return true;
                }
                return false;
            }

            // Try immediately
            if (!injectFields()) {
                // Try again after a short delay
                setTimeout(function() {
                    injectFields();
                }, 100);
            }

            // Also try after DOM is fully loaded
            $(window).on('load', function() {
                setTimeout(function() {
                    console.log('Window loaded, trying final injection...');
                    injectFields();
                }, 500);
            });
        });
        </script>
        <?php
    }

    /**
     * Get all custom fields from database
     */
    public function get_all_custom_fields() {
        global $wpdb;

        $table_name = esc_sql($wpdb->prefix . 'shopglut_product_custom_field_settings');

        // Try to get from cache first
        $cache_key = 'shopglut_custom_field_settings';
        $settings = wp_cache_get($cache_key, 'shopglut_custom_fields');

        if (false === $settings) {
            // Get all custom field settings from database
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query with interpolated variable
            $settings = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
                sprintf("SELECT field_settings FROM %s LIMIT 1", $table_name) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, safe for custom table operations
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $settings, 'shopglut_custom_fields', HOUR_IN_SECONDS);
        }

        if (empty($settings)) {
            return array();
        }

        $unserialized = maybe_unserialize($settings);

        if (!isset($unserialized['shopg_product_custom_field_settings'])) {
            return array();
        }

        $settings_data = $unserialized['shopg_product_custom_field_settings'];

        if (!isset($settings_data['custom_fields'])) {
            return array();
        }

        $custom_fields = $settings_data['custom_fields'];
        $fields_with_settings = array();

        foreach ($custom_fields as $field) {
            // Create a field entry with field_settings as expected by ModuleIntegration
            $fields_with_settings[] = array(
                'field_settings' => serialize($field),
                'field_data' => $field
            );
        }

        return $fields_with_settings;
    }

    /**
     * Render field on frontend (for integration with ModuleIntegration)
     */
    public function render_frontend_field($field, $settings) {
        if (isset($field['field_data'])) {
            $this->render_field($field['field_data']);
        } elseif (is_array($settings)) {
            $this->render_field($settings);
        }
    }

    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}