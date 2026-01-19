<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template1;

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* ============================================
           TEMPLATE 1 - Design 1: Classic Dropdown
           From woo_variations.html
           ============================================ */

        /* Main Container */
        .shopglut-single-product.template1 {
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        .shopglut-single-product.template1 .single-product-container {
            width: 100%;
        }

        /* Demo Mode Container */
        .shopglut-single-product.template1 .shopglut-demo-mode {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 40px;
            min-height: 300px;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Swatches Demo Wrapper - Centered */
        .shopglut-swatches-demo {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        /* Demo center override for proper alignment */
        .shopglut-demo-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* Attribute Label */
        .shopglut-demo-attribute-label {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-demo-attribute-label::before {
            content: '';
            width: 4px;
            height: 18px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        /* Demo Notice */
        .shopglut-demo-notice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 24px;
            border-radius: 12px;
            text-align: center;
            font-size: 15px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        /* Demo Swatches Wrapper */
        .shopglut-demo-swatches-wrapper {
            position: relative;
        }

        /* ============================================
           TEMPLATE 1 - Design 1: Classic Dropdown
           ============================================ */

        /* Target the actual dropdown class from FrontendRenderer */
        .shopglut-swatch-dropdown,
        .shopglut-swatches-wrapper select,
        .shopglut-product-swatches-template1 select,
        .shopglut-product-swatches-template1.variation-1 select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 8px;
            cursor: pointer;
            transition: border-color 0.3s;
            background: #ffffff;
            color: #2d3748;
        }

        .shopglut-swatch-dropdown:hover,
        .shopglut-swatches-wrapper select:hover,
        .shopglut-product-swatches-template1 select:hover,
        .shopglut-product-swatches-template1.variation-1 select:hover {
            border-color: #667eea;
        }

        .shopglut-swatch-dropdown:focus,
        .shopglut-swatches-wrapper select:focus,
        .shopglut-product-swatches-template1 select:focus,
        .shopglut-product-swatches-template1.variation-1 select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Dropdown wrapper */
        .shopglut-dropdown-wrapper {
            width: 100%;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* ============================================
           Clear Button & Price Display
           ============================================ */

        /* Clear button styling */
        .shopglut-reset-variations {
            color: #667eea;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
            margin-left: 10px;
            transition: color 0.3s;
            border: none;
            background: none;
        }

        .shopglut-reset-variations:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* ============================================
           Clear Button & Price Display (Enhanced)
           ============================================ */

        /* Actions Row - contains price and clear button */
        .shopglut-actions-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Clear button styling */
        .shopglut-reset-variations {
            color: #667eea;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            border: none;
            background: none;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .shopglut-reset-variations:hover {
            color: #764ba2;
            background: rgba(102, 126, 234, 0.05);
            text-decoration: none;
        }

        .shopglut-reset-variations:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        /* Price wrapper */
        .shopglut-variation-price-wrapper {
            display: inline-flex;
            align-items: center;
        }

        .shopglut-variation-price {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            line-height: 1.2;
            display: inline-flex;
            align-items: baseline;
            gap: 4px;
        }

        /* Price prefix/suffix text */
        .shopglut-variation-price .price-prefix,
        .shopglut-variation-price .price-suffix {
            font-size: 0.7em;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Price currency symbol styling */
        .shopglut-variation-price .woocommerce-Price-currencySymbol {
            font-size: 0.75em;
            vertical-align: baseline;
            margin-right: 1px;
        }

        /* Price amount styling */
        .shopglut-variation-price .woocommerce-Price-amount {
            font-weight: 700;
        }

        /* Sale price styling */
        .shopglut-variation-price ins {
            text-decoration: none;
            color: #667eea;
        }

        .shopglut-variation-price del {
            color: #9ca3af;
            font-size: 0.85em;
            margin-right: 8px;
            text-decoration: line-through;
        }

        /* ============================================
           Attribute Label Styling (Enhanced)
           ============================================ */
        .shopglut-demo-attribute-label,
        .shopglut-attribute-label,
        .shopglut-swatches-wrapper .attribute-label {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
            text-transform: none;
            letter-spacing: normal;
            display: block;
        }

        /* Optional label with icon */
        .shopglut-attribute-label.with-icon {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-attribute-label.with-icon::before {
            content: '▼';
            font-size: 10px;
            color: #667eea;
        }

        /* Label with required indicator */
        .shopglut-attribute-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ============================================
           Swatches Wrapper Container
           ============================================ */
        .shopglut-swatches-wrapper {
            margin-bottom: 16px;
        }

        .shopglut-attribute-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            display: inline-block;
        }

        /* ============================================
           Responsive Design
           ============================================ */
        @media (max-width: 768px) {
            .shopglut-single-product.template1 .shopglut-demo-mode {
                padding: 24px;
            }

            .shopglut-swatches-demo {
                max-width: 100%;
            }

            .shopglut-demo-attribute-label {
                font-size: 14px;
            }

            .shopglut-swatch-dropdown,
            .shopglut-swatches-wrapper select,
            .shopglut-product-swatches-template1 select {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .shopglut-single-product.template1 .shopglut-demo-mode {
                padding: 16px;
            }

            .shopglut-demo-attribute-label {
                font-size: 13px;
            }

            .shopglut-swatch-dropdown,
            .shopglut-swatches-wrapper select,
            .shopglut-product-swatches-template1 select {
                padding: 8px;
                font-size: 13px;
            }
        }

        /* ============================================
           Accessibility & Focus States
           ============================================ */
        .shopglut-swatch-dropdown:focus-visible,
        .shopglut-swatches-wrapper select:focus-visible,
        .shopglut-product-swatches-template1 select:focus-visible,
        .shopglut-reset-variations:focus-visible {
            outline: 3px solid #667eea;
            outline-offset: 2px;
        }

        /* ============================================
           Animations & Transitions
           ============================================ */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .shopglut-swatches-demo {
            animation: fadeIn 0.4s ease-out;
        }

        /* ========== END COMPREHENSIVE RESPONSIVE CSS ========== */

        /* Settings-based Dynamic CSS - Loaded last to override base styles */
        <?php echo $this->generateSettingsBasedCSS($settings); ?>

        </style>
        <?php
    }

    /**
     * Generate CSS based on settings
     */
    private function generateSettingsBasedCSS($settings) {
        $css = '';

        // Helper function to get nested setting value
        $get_setting = function($section, $key, $default = '') use ($settings) {
            if (!isset($settings[$section])) {
                return $default;
            }
            $value = $settings[$section];
            if (is_array($value) && isset($value[$key])) {
                return $value[$key];
            }
            if (is_array($value)) {
                return reset($value) !== false ? reset($value) : $default;
            }
            return $value;
        };

        // Get value from array with key as fallback
        $get_value = function($arr, $key, $default = '') {
            if (is_array($arr) && isset($arr[$key])) {
                $val = $arr[$key];
                if (is_array($val) && isset($val[$key])) {
                    return $val[$key];
                }
                return is_array($val) ? reset($val) : $val;
            }
            return $default;
        };

        // Dropdown Container Settings
        $background = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_background', '#ffffff');
        $border_color = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_border_color', '#d1d5db');
        $border_width = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_border_width', 1);
        $border_radius = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_border_radius', 6);
        $width = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_width', 100);

        $padding = $get_setting('swatch_dropdown_container_section', 'swatch_dropdown_padding', array('top' => 10, 'right' => 14, 'bottom' => 10, 'left' => 14, 'unit' => 'px'));
        if (is_string($padding)) {
            $padding = array('top' => 10, 'right' => 14, 'bottom' => 10, 'left' => 14, 'unit' => 'px');
        }
        $padding_top = isset($padding['top']) ? $padding['top'] : 10;
        $padding_right = isset($padding['right']) ? $padding['right'] : 14;
        $padding_bottom = isset($padding['bottom']) ? $padding['bottom'] : 10;
        $padding_left = isset($padding['left']) ? $padding['left'] : 14;
        $padding_unit = isset($padding['unit']) ? $padding['unit'] : 'px';

        // Typography Settings
        $text_color = $get_setting('swatch_dropdown_typography_section', 'swatch_dropdown_text_color', '#374151');
        $font_size = $get_setting('swatch_dropdown_typography_section', 'swatch_dropdown_font_size', 14);
        $font_weight = $get_setting('swatch_dropdown_typography_section', 'swatch_dropdown_font_weight', '400');
        $placeholder_color = $get_setting('swatch_dropdown_typography_section', 'swatch_dropdown_placeholder_color', '#9ca3af');

        // States Settings
        $focus_border_color = $get_setting('swatch_dropdown_states_section', 'swatch_dropdown_focus_border_color', '#2271b1');
        $focus_shadow_preset = $get_setting('swatch_dropdown_states_section', 'swatch_dropdown_focus_shadow', 'medium');
        $hover_border_color = $get_setting('swatch_dropdown_states_section', 'swatch_dropdown_hover_border_color', '#2271b1');

        // Attribute Label Settings
        $label_color = $get_setting('swatch_attribute_label_section', 'swatch_attribute_label_color', '#374151');
        $label_font_size = $get_setting('swatch_attribute_label_section', 'swatch_attribute_label_font_size', 14);
        $label_font_weight = $get_setting('swatch_attribute_label_section', 'swatch_attribute_label_font_weight', '600');

        // Generate CSS
        $css .= "/* Settings-based Dynamic CSS for Template1 */\n";

        // Enforce minimum values to prevent bad appearance
        $font_size = max(intval($font_size), 12); // Min 12px
        $label_font_size = max(intval($label_font_size), 12); // Min 12px
        $width = max(intval($width), 50); // Min 50% width to prevent narrow dropdowns

        // Dropdown styles
        $css .= ".shopglut-swatch-dropdown, .shopglut-swatches-wrapper select, .shopglut-product-swatches-template1 select {\n";
        $css .= "    background-color: " . esc_attr($background) . " !important;\n";
        $css .= "    border: " . intval($border_width) . "px solid " . esc_attr($border_color) . " !important;\n";
        $css .= "    border-radius: " . intval($border_radius) . "px !important;\n";
        $css .= "    width: " . intval($width) . "% !important;\n";
        $css .= "    color: " . esc_attr($text_color) . " !important;\n";
        $css .= "    font-size: " . intval($font_size) . "px !important;\n";
        $css .= "    font-weight: " . esc_attr($font_weight) . " !important;\n";
        $css .= "    padding: " . intval($padding_top) . esc_attr($padding_unit) . " " . intval($padding_right) . esc_attr($padding_unit) . " " . intval($padding_bottom) . esc_attr($padding_unit) . " " . intval($padding_left) . esc_attr($padding_unit) . " !important;\n";
        $css .= "}\n";

        // Hover state
        $css .= ".shopglut-swatch-dropdown:hover, .shopglut-swatches-wrapper select:hover, .shopglut-product-swatches-template1 select:hover {\n";
        $css .= "    border-color: " . esc_attr($hover_border_color) . " !important;\n";
        $css .= "}\n";

        // Focus state
        $css .= ".shopglut-swatch-dropdown:focus, .shopglut-swatches-wrapper select:focus, .shopglut-product-swatches-template1 select:focus {\n";
        $css .= "    border-color: " . esc_attr($focus_border_color) . " !important;\n";

        // Focus shadow
        $shadow_map = array(
            'none' => 'none',
            'small' => '0 0 0 2px',
            'medium' => '0 0 0 3px',
            'large' => '0 0 0 4px',
        );

        $focus_shadow = isset($shadow_map[$focus_shadow_preset]) ? $shadow_map[$focus_shadow_preset] : $shadow_map['medium'];
        if ($focus_shadow !== 'none') {
            $hex = str_replace('#', '', $focus_border_color);
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $rgb = "$r, $g, $b";

            $alpha_map = array(
                'none' => 0,
                'small' => 0.1,
                'medium' => 0.1,
                'large' => 0.15,
            );
            $alpha = isset($alpha_map[$focus_shadow_preset]) ? $alpha_map[$focus_shadow_preset] : 0.1;
            $css .= "    box-shadow: " . esc_attr($focus_shadow) . " rgba(" . esc_attr($rgb) . ", " . esc_attr($alpha) . ") !important;\n";
        } else {
            $css .= "    box-shadow: none !important;\n";
        }
        $css .= "    outline: none;\n";
        $css .= "}\n";

        // Placeholder
        $css .= ".shopglut-swatch-dropdown::placeholder, .shopglut-swatches-wrapper select::placeholder {\n";
        $css .= "    color: " . esc_attr($placeholder_color) . " !important;\n";
        $css .= "}\n";

        // Attribute label
        $css .= ".shopglut-attribute-label, .shopglut-swatches-wrapper .attribute-label {\n";
        $css .= "    color: " . esc_attr($label_color) . " !important;\n";
        $css .= "    font-size: " . intval($label_font_size) . "px !important;\n";
        $css .= "    font-weight: " . esc_attr($label_font_weight) . " !important;\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Helper method to get setting value with fallback
     */
    private function getSetting($settings, $key, $default = '') {
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * Convert hex color to rgba format
     */
    private function hexToRgba($hex, $alpha = 1) {
        // Remove hash if present
        $hex = ltrim($hex, '#');

        // Parse the hex values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }

    /**
     * Get layout settings from database
     */
    private function getLayoutSettings($layout_id) {
        if (!$layout_id) {
            return $this->getDefaultSettings();
        }

        // Check cache first
        $cache_key = 'shopglut_product_swatches_layout_' . $layout_id;
        $layout_data = wp_cache_get($cache_key, 'shopglut_layouts');

        if (false === $layout_data) {
            global $wpdb;
            $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching implemented
            $layout_data = $wpdb->get_row(
                $wpdb->prepare("SELECT layout_settings, layout_template FROM `{$table_name}` WHERE id = %d", $layout_id)
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
        }

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);

            // Get template name
            $template = isset($layout_data->layout_template) ? $layout_data->layout_template : 'template1';

            // Try the correct swatches settings key
            $settings_key = 'shopg_product_swatches_settings_' . $template;
            if (isset($settings[$settings_key])) {
                return $this->flattenSettings($settings[$settings_key]);
            }

            // Try old format (without underscore between product and swatches)
            $old_key = 'shopg_productswatches_settings_' . $template;
            if (isset($settings[$old_key])) {
                return $this->flattenSettings($settings[$old_key]);
            }

            // If settings is already flattened, return as is
            if (is_array($settings)) {
                return array_merge($this->getDefaultSettings(), $settings);
            }
        }

        return $this->getDefaultSettings();
    }

    /**
     * Flatten nested settings structure to simple key-value pairs
     */
    private function flattenSettings($nested_settings) {
        $flat_settings = array();

        foreach ($nested_settings as $group_key => $group_values) {
            if (is_array($group_values)) {
                foreach ($group_values as $setting_key => $setting_value) {
                    // Handle slider fields that have separate value and unit
                    if (is_array($setting_value) && isset($setting_value[$setting_key])) {
                        $flat_settings[$setting_key] = $setting_value[$setting_key];
                    } else {
                        $flat_settings[$setting_key] = $setting_value;
                    }
                }
            }
        }

        return array_merge($this->getDefaultSettings(), $flat_settings);
    }

    /**
     * Get default settings values for single product template
     */
    private function getDefaultSettings() {
        return array(

        );
    }

}
