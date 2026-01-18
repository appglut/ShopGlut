<?php
namespace Shopglut\enhancements\ProductSwatches\templates\template22;

class template22Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
       

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        
        .designs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        
        .section-title {
            color: white;
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        .design-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: none;            transition: transform 0.3s ease;
        }
        
        .design-card:hover {
            transform: translateY(-5px);
        }
        
        .design-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .design-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            max-width: 200px;
            height: 2px;
            background: #667eea;
        }

        /* Center label and form elements within design card */
        .design-card .label,
        .design-card > select,
        .design-card > .swatches-container {
            text-align: center;
        }

        .design-card select {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        .premium-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 10px;
            font-weight: 600;
        }
        
        .free-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 10px;
            font-weight: 600;
        }
        
        /* Design 22: Premium - Progress Steps */
        .shopglut-product-swatches-template22.variation-22 .progress-steps {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .shopglut-product-swatches-template22.variation-22 .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 40px;
            right: 40px;
            height: 2px;
            background: #ddd;
            z-index: 0;
        }

        .shopglut-product-swatches-template22.variation-22 .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            z-index: 1;
            position: relative;
        }

        .shopglut-product-swatches-template22.variation-22 .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s;
        }

        .shopglut-product-swatches-template22.variation-22 .step:hover .step-circle {
            border-color: #667eea;
        }

        .shopglut-product-swatches-template22.variation-22 .step.active .step-circle {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .shopglut-product-swatches-template22.variation-22 .step.completed .step-circle {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .shopglut-product-swatches-template22.variation-22 .step-label {
            font-size: 12px;
            font-weight: 500;
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

        // Product Gallery Settings
       

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
                $wpdb->prepare("SELECT layout_settings FROM `{$table_name}` WHERE id = %d", $layout_id)
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
        }

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);

            // Try different possible settings paths
            if (isset($settings['shopg_singleproduct_settings_template1']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template1']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template1']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template1']['cart-page-settings']);
            } elseif (is_array($settings)) {
                // If settings is already flattened, return as is
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
