<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template2;

class template2Style {

    public function dynamicCss($layout_id = 0)
    {
        // DEBUG: Log when dynamicCss is called
        error_log('=== dynamicCss called for layout_id: ' . $layout_id . ' ===');

        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        // Start output buffering to capture the CSS
        ob_start();

        ?>
       <style>
        /* ============================================
           TEMPLATE 2 - Design 2: Button Grid
           From woo_variations.html
           ============================================ */

        /* DEBUG MARKER - If you see this, CSS is being loaded */
        .shopglut-css-loaded-marker {
            display: none !important;
        }

        /* Main Container */
        .shopglut-single-product.template2 {
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        .shopglut-single-product.template2 .single-product-container {
            width: 100%;
        }

        /* Demo Mode Container */
        .shopglut-single-product.template2 .shopglut-demo-mode {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 40px;
            min-height: 200px;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.06);
        }

        /* Swatches Demo Wrapper */
        .shopglut-swatches-demo {
            max-width: 600px;
            margin: 0 auto;
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
           TEMPLATE 2 - Design 2: Button Grid
           ============================================ */

        /* Button container - targets multiple possible classes */
        .shopglut-buttons-container,
        .shopglut-swatches-wrapper .options,
        .shopglut-product-swatches-template2 .options,
        .shopglut-product-swatches-template2.variation-2 .options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* Button styling - using inline styles for per-term customization, only minimal defaults here */
        .shopglut-swatch-button,
        .shopglut-swatches-wrapper .option-btn,
        .shopglut-product-swatches-template2 .option-btn,
        .shopglut-product-swatches-template2.variation-2 .option-btn,
        .shopglut-product-swatches-template2 button {
            cursor: pointer;
            transition: all 0.3s;
        }

        /* Note: Hover and selected states are now handled by inline styles in render_demo_swatches() */

        /* Disabled state */
        .shopglut-swatch-button.disabled,
        .shopglut-product-swatches-template2 .option-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ============================================
           Clear Button & Price Display
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
            .shopglut-single-product.template2 .shopglut-demo-mode {
                padding: 24px;
            }

            .shopglut-swatches-demo {
                max-width: 100%;
            }

            .shopglut-demo-attribute-label {
                font-size: 14px;
            }

            .shopglut-buttons-container,
            .shopglut-swatches-wrapper .options,
            .shopglut-product-swatches-template2 .options {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .shopglut-single-product.template2 .shopglut-demo-mode {
                padding: 16px;
            }

            .shopglut-demo-attribute-label {
                font-size: 13px;
            }

            .shopglut-buttons-container,
            .shopglut-swatches-wrapper .options,
            .shopglut-product-swatches-template2 .options {
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }
        }

        /* ============================================
           Accessibility & Focus States
           ============================================ */
        .shopglut-swatch-button:focus-visible,
        .shopglut-product-swatches-template2 .option-btn:focus-visible,
        .shopglut-product-swatches-template2 button:focus-visible,
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
        <?php
        $generated_css = $this->generateSettingsBasedCSS($settings);

        // DEBUG: Output the generated CSS
        error_log('=== GENERATED CSS DEBUG ===');
        error_log('Layout ID: ' . $layout_id);
        error_log('Settings count: ' . count($settings));
        error_log('Generated CSS length: ' . strlen($generated_css));
        error_log('Generated CSS: ' . $generated_css);
        error_log('=== END GENERATED CSS DEBUG ===');

        echo $generated_css;
        ?>

        /* DEBUG TEST - If you see RED background on body, CSS IS loading! */
        body.shopglut-css-test {
            background-color: red !important;
        }

        </style>
        <?php

        // Get the buffered content and return it
        $css_output = ob_get_clean();

        // Debug log the final output
        error_log('=== FINAL CSS OUTPUT ===');
        error_log('CSS Output length: ' . strlen($css_output));
        error_log('=== END FINAL CSS OUTPUT ===');

        return $css_output;
    }

    /**
     * Generate CSS based on settings
     */
    private function generateSettingsBasedCSS($settings) {
        $css = '';

        // Helper function to extract nested setting value (handles slider fields with nested arrays)
        $extract_value = function($section_array, $key, $default = '') {
            if (!isset($section_array[$key])) {
                return $default;
            }
            $value = $section_array[$key];
            // Handle slider fields that have nested array format: array('key' => value)
            if (is_array($value) && isset($value[$key])) {
                return $value[$key];
            }
            return $value;
        };

        // Layout Settings
        $layout_section = isset($settings['layout_settings_section']) ? $settings['layout_settings_section'] : array();
        $swatch_columns = $extract_value($layout_section, 'swatch_columns', 3);
        $swatch_gap = $extract_value($layout_section, 'swatch_gap', 10);
        $swatch_container_margin_bottom = $extract_value($layout_section, 'swatch_container_margin_bottom', 20);

        // Attribute Label Settings
        $label_section = isset($settings['attribute_label_section']) ? $settings['attribute_label_section'] : array();
        $label_color = $extract_value($label_section, 'attribute_label_color', '#2d3748');
        $label_font_size = $extract_value($label_section, 'attribute_label_font_size', 16);
        $label_font_weight = $extract_value($label_section, 'attribute_label_font_weight', 600);
        $label_margin_bottom = $extract_value($label_section, 'attribute_label_margin_bottom', 16);

        // Button Default Settings
        $button_section = isset($settings['button_default_section']) ? $settings['button_default_section'] : array();
        $button_bg = $extract_value($button_section, 'button_default_background', '#ffffff');
        $button_text_color = $extract_value($button_section, 'button_default_text_color', '#2d3748');
        $button_border_color = $extract_value($button_section, 'button_default_border_color', '#ddd');
        $button_border_width = $extract_value($button_section, 'button_default_border_width', 2);
        $button_border_radius = $extract_value($button_section, 'button_default_border_radius', 8);
        $button_padding_x = $extract_value($button_section, 'button_default_padding_x', 12);
        $button_padding_y = $extract_value($button_section, 'button_default_padding_y', 12);
        $button_font_size = $extract_value($button_section, 'button_default_font_size', 14);
        $button_font_weight = $extract_value($button_section, 'button_default_font_weight', 500);
        $button_min_width = $extract_value($button_section, 'button_default_min_width', 'auto');
        $button_min_height = $extract_value($button_section, 'button_default_min_height', 'auto');

        // Button Hover Settings
        $hover_section = isset($settings['button_hover_section']) ? $settings['button_hover_section'] : array();
        $hover_bg = $extract_value($hover_section, 'button_hover_background', '#ffffff');
        $hover_text_color = $extract_value($hover_section, 'button_hover_text_color', '#667eea');
        $hover_border_color = $extract_value($hover_section, 'button_hover_border_color', '#667eea');

        // Button Active Settings
        $active_section = isset($settings['button_active_section']) ? $settings['button_active_section'] : array();
        $active_bg = $extract_value($active_section, 'button_active_background', '#667eea');
        $active_text_color = $extract_value($active_section, 'button_active_text_color', '#ffffff');
        $active_border_color = $extract_value($active_section, 'button_active_border_color', '#667eea');

        // Generate CSS
        $css .= "/* Settings-based Dynamic CSS for Template2 */\n";

        // Layout settings
        $css .= ".shopglut-buttons-container, .shopglut-swatches-wrapper .options, .shopglut-product-swatches-template2 .options {\n";
        $css .= "    grid-template-columns: repeat(" . intval($swatch_columns) . ", 1fr) !important;\n";
        $css .= "    gap: " . intval($swatch_gap) . "px !important;\n";
        $css .= "    margin-bottom: " . intval($swatch_container_margin_bottom) . "px !important;\n";
        $css .= "}\n";

        // Attribute label
        $css .= ".shopglut-attribute-label, .shopglut-swatches-wrapper .attribute-label {\n";
        $css .= "    color: " . esc_attr($label_color) . " !important;\n";
        $css .= "    font-size: " . intval($label_font_size) . "px !important;\n";
        $css .= "    font-weight: " . esc_attr($label_font_weight) . " !important;\n";
        $css .= "    margin-bottom: " . intval($label_margin_bottom) . "px !important;\n";
        $css .= "}\n";

        // Note: Button styles (including hover and selected states) are now applied as inline styles
        // in render_demo_swatches() to support per-term customization properly.

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

        // Clear cache to ensure fresh data
        $cache_key = 'shopglut_product_swatches_layout_' . $layout_id;
        wp_cache_delete($cache_key, 'shopglut_layouts');

        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
        $layout_data = $wpdb->get_row(
            $wpdb->prepare("SELECT layout_settings, layout_template FROM `{$table_name}` WHERE id = %d", $layout_id)
        );

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);

            // Get template name
            $template = isset($layout_data->layout_template) ? $layout_data->layout_template : 'template2';

            // Try the correct swatches settings key
            $settings_key = 'shopg_product_swatches_settings_' . $template;

            if (isset($settings[$settings_key]) && is_array($settings[$settings_key])) {
                // Found nested settings, use them
                return $this->flattenSettings($settings[$settings_key]);
            }

            // Try old format (without underscore between product and swatches)
            $old_key = 'shopg_productswatches_settings_' . $template;

            if (isset($settings[$old_key]) && is_array($settings[$old_key])) {
                // Found old format settings, use them
                return $this->flattenSettings($settings[$old_key]);
            }

            // If settings is already flattened (no wrapper key), use as-is
            if (is_array($settings) && !empty($settings)) {
                // Merge with defaults to ensure all sections exist
                return array_merge($this->getDefaultSettings(), $settings);
            }
        }

        // No settings found, use defaults
        return $this->getDefaultSettings();
    }

    /**
     * Flatten nested settings structure to simple key-value pairs
     * Preserves section-level nesting for CSS generation
     */
    private function flattenSettings($nested_settings) {
        $flat_settings = array();

        foreach ($nested_settings as $group_key => $group_values) {
            if (is_array($group_values)) {
                // Keep section-level nesting for sections that need it
                $sections_needing_nesting = array(
                    'layout_settings_section',
                    'attribute_label_section',
                    'button_default_section',
                    'button_hover_section',
                    'button_active_section',
                    'per_term_styling',
                );

                if (in_array($group_key, $sections_needing_nesting)) {
                    // Keep this section nested
                    foreach ($group_values as $setting_key => $setting_value) {
                        // Handle slider fields that have separate value and unit
                        if (is_array($setting_value) && isset($setting_value[$setting_key])) {
                            $flat_settings[$group_key][$setting_key] = $setting_value[$setting_key];
                        } else {
                            $flat_settings[$group_key][$setting_key] = $setting_value;
                        }
                    }
                } else {
                    // Flatten other sections
                    foreach ($group_values as $setting_key => $setting_value) {
                        if (is_array($setting_value) && isset($setting_value[$setting_key])) {
                            $flat_settings[$setting_key] = $setting_value[$setting_key];
                        } else {
                            $flat_settings[$setting_key] = $setting_value;
                        }
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
            // Layout Settings
            'layout_settings_section' => array(
                'swatch_columns' => 3,
                'swatch_gap' => 10,
                'swatch_container_margin_bottom' => 20,
            ),
            // Attribute Label Settings
            'attribute_label_section' => array(
                'attribute_label_color' => '#2d3748',
                'attribute_label_font_size' => 16,
                'attribute_label_font_weight' => '600',
                'attribute_label_margin_bottom' => 16,
            ),
            // Button Default Settings
            'button_default_section' => array(
                'button_default_background' => '#ffffff',
                'button_default_text_color' => '#2d3748',
                'button_default_border_color' => '#dddddd',
                'button_default_border_width' => 2,
                'button_default_border_radius' => 8,
                'button_default_padding_x' => 12,
                'button_default_padding_y' => 12,
                'button_default_font_size' => 14,
                'button_default_font_weight' => '500',
                'button_default_min_width' => 'auto',
                'button_default_min_height' => 'auto',
            ),
            // Button Hover Settings
            'button_hover_section' => array(
                'button_hover_background' => '#ffffff',
                'button_hover_text_color' => '#667eea',
                'button_hover_border_color' => '#667eea',
            ),
            // Button Active Settings
            'button_active_section' => array(
                'button_active_background' => '#667eea',
                'button_active_text_color' => '#ffffff',
                'button_active_border_color' => '#667eea',
            ),
        );
    }

}
