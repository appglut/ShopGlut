<?php
namespace Shopglut\enhancements\ProductComparison\templates\template1;

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        // Get table styling settings with defaults
        $table_styling = isset($settings['table_styling']) ? $settings['table_styling'] : array();
        $table_header_bg = isset($table_styling['table_header_bg']) ? $table_styling['table_header_bg'] : '#f3f4f6';
        $table_header_text_color = isset($table_styling['table_header_text_color']) ? $table_styling['table_header_text_color'] : '#111827';
        $table_row_bg = isset($table_styling['table_row_bg']) ? $table_styling['table_row_bg'] : '#ffffff';
        $table_row_alt_bg = isset($table_styling['table_row_alt_bg']) ? $table_styling['table_row_alt_bg'] : '#f9fafb';
        $table_border_color = isset($table_styling['table_border_color']) ? $table_styling['table_border_color'] : '#e5e7eb';
        $table_text_color = isset($table_styling['table_text_color']) ? $table_styling['table_text_color'] : '#374151';

        ?>
       <style>
        /* ===== RESET & BASE STYLES ===== */
        .shopglut-product-comparison.template1 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .shopglut-product-comparison.template1 {
            font-family: Arial, sans-serif;
            color: <?php echo esc_attr($table_text_color); ?>;
            line-height: 1.5;
            padding: 15px 0;
        }

        /* ===== COMPARISON CONTAINER ===== */
        .shopglut-product-comparison.template1 .comparison-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* ===== COMPARISON HEADER ===== */
        .shopglut-product-comparison.template1 .comparison-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid <?php echo esc_attr($table_border_color); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-header h2 {
            font-size: 22px;
            font-weight: bold;
            color: <?php echo esc_attr($table_header_text_color); ?>;
            margin: 0;
        }

        .shopglut-product-comparison.template1 .clear-all-btn {
            background: #c62828;
            color: #fff;
            border: 1px solid #c62828;
            padding: 8px 15px;
            font-size: 13px;
            cursor: pointer;
        }

        .shopglut-product-comparison.template1 .clear-all-btn:hover {
            background: #b71c1c;
        }

        /* ===== COMPARISON TABLE WRAPPER ===== */
        .shopglut-product-comparison.template1 .comparison-table-wrapper {
            overflow-x: auto;
            background: <?php echo esc_attr($table_row_bg); ?>;
            border: 1px solid <?php echo esc_attr($table_border_color); ?>;
            margin-bottom: 20px;
        }

        /* ===== COMPARISON TABLE ===== */
        .shopglut-product-comparison.template1 .comparison-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        /* ===== TABLE HEADER ===== */
        .shopglut-product-comparison.template1 .comparison-table thead {
            background: <?php echo esc_attr($table_header_bg); ?>;
            color: <?php echo esc_attr($table_header_text_color); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-table thead th {
            padding: 12px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid <?php echo esc_attr($table_border_color); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-table .feature-column {
            min-width: 150px;
            text-align: left !important;
            background: <?php echo esc_attr($table_header_bg); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-table .product-column {
            min-width: 200px;
            max-width: 250px;
        }

        /* ===== PRODUCT HEADER IN TABLE ===== */
        .shopglut-product-comparison.template1 .product-header {
            position: relative;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .shopglut-product-comparison.template1 .product-header .remove-product {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #fff;
            color: #d32f2f;
            border: 1px solid #ddd;
            width: 22px;
            height: 22px;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            cursor: pointer;
        }

        .shopglut-product-comparison.template1 .product-header .remove-product:hover {
            background: #d32f2f;
            color: #fff;
            border-color: #d32f2f;
        }

        .shopglut-product-comparison.template1 .product-header .product-image {
            width: 100px;
            height: 100px;
            overflow: hidden;
            background: #fff;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .shopglut-product-comparison.template1 .product-header .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .shopglut-product-comparison.template1 .product-header .product-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 1.3;
            margin: 0;
        }

        .shopglut-product-comparison.template1 .product-header .product-title a {
            color: #333;
            text-decoration: none;
        }

        .shopglut-product-comparison.template1 .product-header .product-title a:hover {
            color: #0066cc;
            text-decoration: underline;
        }

        /* ===== TABLE BODY ===== */
        .shopglut-product-comparison.template1 .comparison-table tbody tr {
            border-bottom: 1px solid <?php echo esc_attr($table_border_color); ?>;
            background-color: <?php echo esc_attr($table_row_bg); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-table tbody tr:nth-child(even) {
            background-color: <?php echo esc_attr($table_row_alt_bg); ?>;
        }

        .shopglut-product-comparison.template1 .comparison-table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border: 1px solid <?php echo esc_attr($table_border_color); ?>;
        }

        /* ===== FEATURE LABEL ===== */
        .shopglut-product-comparison.template1 .feature-label {
            font-weight: bold;
            color: <?php echo esc_attr($table_header_text_color); ?>;
            background: <?php echo esc_attr($table_header_bg); ?>;
            font-size: 13px;
        }

        /* ===== PRODUCT VALUE ===== */
        .shopglut-product-comparison.template1 .product-value {
            text-align: center;
            color: <?php echo esc_attr($table_text_color); ?>;
            font-size: 13px;
        }

        /* ===== PRICE STYLING ===== */
        .shopglut-product-comparison.template1 .price-row .price {
            font-size: 18px;
            font-weight: bold;
            color: #2e7d32;
        }

        /* ===== RATING STYLING ===== */
        .shopglut-product-comparison.template1 .rating-row .star-rating {
            display: inline-block;
            color: #f9a825;
            font-size: 14px;
        }

        .shopglut-product-comparison.template1 .rating-row .rating-count {
            font-size: 12px;
            color: #777;
            margin-left: 5px;
        }

        /* ===== STOCK STATUS ===== */
        .shopglut-product-comparison.template1 .in-stock {
            color: #2e7d32;
            font-weight: bold;
            font-size: 12px;
        }

        .shopglut-product-comparison.template1 .out-of-stock {
            color: #d32f2f;
            font-weight: bold;
            font-size: 12px;
        }

        /* ===== DESCRIPTION ===== */
        .shopglut-product-comparison.template1 .product-description {
            line-height: 1.4;
            color: #555;
            font-size: 13px;
        }

        /* ===== ACTION BUTTONS ===== */
        .shopglut-product-comparison.template1 .add-to-cart-button,
        .shopglut-product-comparison.template1 .view-product-button {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid;
        }

        .shopglut-product-comparison.template1 .add-to-cart-button {
            background: #2e7d32;
            color: #fff;
            border-color: #2e7d32;
        }

        .shopglut-product-comparison.template1 .add-to-cart-button:hover {
            background: #1b5e20;
            border-color: #1b5e20;
        }

        .shopglut-product-comparison.template1 .view-product-button {
            background: #757575;
            color: #fff;
            border-color: #757575;
        }

        .shopglut-product-comparison.template1 .view-product-button:hover {
            background: #616161;
            border-color: #616161;
        }

        /* ===== FLOATING COMPARISON BAR ===== */
        .shopglut-product-comparison.template1 .floating-comparison-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 2px solid #ddd;
            z-index: 1000;
            padding: 10px 15px;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .bar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .products-preview {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            flex: 1;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .product-mini {
            position: relative;
            flex-shrink: 0;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .product-mini img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .remove-mini {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #d32f2f;
            color: #fff;
            border: none;
            width: 18px;
            height: 18px;
            font-size: 11px;
            line-height: 18px;
            text-align: center;
            cursor: pointer;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .bar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .product-count {
            font-weight: bold;
            color: #333;
            font-size: 13px;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .compare-now-btn {
            background: #2e7d32;
            color: #fff;
            border: 1px solid #2e7d32;
            padding: 8px 16px;
            font-size: 13px;
            cursor: pointer;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .compare-now-btn:hover {
            background: #1b5e20;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .clear-all-mini {
            background: #fff;
            color: #d32f2f;
            border: 1px solid #d32f2f;
            padding: 8px 16px;
            font-size: 13px;
            cursor: pointer;
        }

        .shopglut-product-comparison.template1 .floating-comparison-bar .clear-all-mini:hover {
            background: #d32f2f;
            color: #fff;
        }

        /* ===== EMPTY STATE ===== */
        .shopglut-comparison-empty {
            text-align: center;
            padding: 40px 20px;
            background: #f9f9f9;
            border: 1px dashed #ccc;
        }

        .shopglut-comparison-empty p {
            font-size: 16px;
            color: #777;
            margin: 0;
        }

        /* ===== RESPONSIVE STYLES ===== */
        @media (max-width: 768px) {
            .shopglut-product-comparison.template1 .comparison-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .shopglut-product-comparison.template1 .comparison-header h2 {
                font-size: 18px;
            }

            .shopglut-product-comparison.template1 .clear-all-btn {
                width: 100%;
            }

            .shopglut-product-comparison.template1 .comparison-table thead th {
                padding: 10px 8px;
                font-size: 12px;
            }

            .shopglut-product-comparison.template1 .comparison-table tbody td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .shopglut-product-comparison.template1 .product-header .product-image {
                width: 80px;
                height: 80px;
            }

            .shopglut-product-comparison.template1 .product-header .product-title {
                font-size: 13px;
            }

            .shopglut-product-comparison.template1 .floating-comparison-bar .bar-content {
                flex-direction: column;
                gap: 10px;
            }

            .shopglut-product-comparison.template1 .floating-comparison-bar .bar-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .shopglut-product-comparison.template1 .floating-comparison-bar,
            .shopglut-product-comparison.template1 .clear-all-btn,
            .shopglut-product-comparison.template1 .remove-product {
                display: none !important;
            }
        }
    </style>
        <?php
    }

    /**
     * Get layout settings from database
     */
    private function getLayoutSettings($layout_id) {
        if (!$layout_id) {
            return $this->getDefaultSettings();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table name variable
        $layout_data = $wpdb->get_row(
            $wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_comparison_layouts` WHERE id = %d", $layout_id)
        );

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);
            if (isset($settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings'])) {
                return $this->flattenSettings($settings['shopg_product_comparison_settings_template1']['product_comparison-page-settings']);
            }
        }

        return $this->getDefaultSettings();
    }

    /**
     * Flatten nested settings structure to simple key-value pairs
     * Preserves fieldset structures like 'table_styling', 'button_styling', etc.
     */
    private function flattenSettings($nested_settings) {
        $flat_settings = array();

        // Known fieldsets that should preserve their structure
        $fieldset_keys = array('table_styling', 'button_styling', 'table_settings', 'comparison_fields',
                                'floating_bar_styling', 'floating_bar_settings', 'button_text_settings',
                                'button_icon_settings', 'shop_page_position', 'archive_page_position',
                                'product_page_position', 'floating_compare_button', 'storage_settings',
                                'animation_settings', 'notification_settings');

        foreach ($nested_settings as $group_key => $group_values) {
            if (is_array($group_values)) {
                // Check if this is a known fieldset that should preserve structure
                if (in_array($group_key, $fieldset_keys)) {
                    // Preserve the entire fieldset structure
                    $flat_settings[$group_key] = $group_values;
                } else {
                    // For non-fieldset arrays, check if it contains simple key-value pairs
                    $has_nested_arrays = false;
                    foreach ($group_values as $k => $v) {
                        if (is_array($v)) {
                            $has_nested_arrays = true;
                            break;
                        }
                    }

                    if ($has_nested_arrays) {
                        // Recursively flatten if it has nested arrays
                        $flattened_group = $this->flattenSettings($group_values);
                        $flat_settings = array_merge($flat_settings, $flattened_group);
                    } else {
                        // Direct assignment for simple key-value pairs
                        foreach ($group_values as $setting_key => $setting_value) {
                            $flat_settings[$setting_key] = $setting_value;
                        }
                    }
                }
            } else {
                // Direct scalar values
                $flat_settings[$group_key] = $group_values;
            }
        }

        return array_merge($this->getDefaultSettings(), $flat_settings);
    }

    /**
     * Get default settings values
     */
    private function getDefaultSettings() {
        return array();
    }
}
