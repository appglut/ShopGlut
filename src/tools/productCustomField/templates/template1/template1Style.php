<?php
namespace  Shopglut\tools\acf\templates\template1;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        // Extract padding values
        $container_padding = $this->extractPadding($settings['container_padding']);
        $header_padding = $this->extractPadding($settings['header_padding']);
        $order_summary_padding = $this->extractPadding($settings['order_summary_padding']);
        $total_section_padding = $this->extractPadding($settings['total_section_padding']);
        $address_card_padding = $this->extractPadding($settings['address_card_padding']);
        $button_padding = $this->extractPadding($settings['button_padding']);
        $footer_padding = $this->extractPadding($settings['footer_padding']);

        ?>
       <style>
        .shopglut-accountpage.template1 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .shopglut-accountpage.template1 body {
            font-family: <?php echo esc_attr($settings['font_family']); ?>;
            background-color: #f5f5f5;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .shopglut-accountpage.template1 .container {
            max-width: <?php echo esc_attr($settings['container_max_width']); ?>px;
            margin: 0 auto;
            background: <?php echo esc_attr($settings['container_background_color']); ?>;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: <?php echo esc_attr($container_padding); ?>;
        }

        .shopglut-accountpage.template1 .header {
            background-color: <?php echo esc_attr($settings['header_background_color']); ?>;
            padding: <?php echo esc_attr($header_padding); ?>;
            text-align: center;
            margin-bottom: <?php echo esc_attr($settings['section_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .success-icon {
            width: <?php echo esc_attr($settings['success_icon_size']); ?>px;
            height: <?php echo esc_attr($settings['success_icon_size']); ?>px;
            background-color: <?php echo esc_attr($settings['success_icon_background_color']); ?>;
            color: <?php echo esc_attr($settings['success_icon_text_color']); ?>;
            border-radius: <?php echo esc_attr($settings['success_icon_border_radius']); ?>%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }

        .shopglut-accountpage.template1 .header h1 {
            font-size: <?php echo esc_attr($settings['thank_you_heading_font_size']); ?>px;
            color: <?php echo esc_attr($settings['thank_you_heading_color']); ?>;
            margin-bottom: 10px;
        }

        .shopglut-accountpage.template1 .header p {
            font-size: <?php echo esc_attr($settings['success_description_font_size']); ?>px;
            color: <?php echo esc_attr($settings['success_description_color']); ?>;
        }

        .shopglut-accountpage.template1 .content {
            padding: 0;
        }

        .shopglut-accountpage.template1 .order-summary {
            background-color: <?php echo esc_attr($settings['order_summary_background_color']); ?>;
            border: 1px solid <?php echo esc_attr($settings['order_summary_border_color']); ?>;
            border-radius: <?php echo esc_attr($settings['order_summary_border_radius']); ?>px;
            padding: <?php echo esc_attr($order_summary_padding); ?>;
            margin-bottom: <?php echo esc_attr($settings['section_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .shopglut-accountpage.template1 .order-number {
            font-size: <?php echo esc_attr($settings['order_number_font_size']); ?>px;
            font-weight: bold;
            color: <?php echo esc_attr($settings['order_number_color']); ?>;
        }

        .shopglut-accountpage.template1 .order-status {
            background-color: <?php echo esc_attr($settings['order_status_background_color']); ?>;
            color: <?php echo esc_attr($settings['order_status_text_color']); ?>;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .shopglut-accountpage.template1 .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: <?php echo esc_attr($settings['detail_spacing']); ?>px;
            margin-bottom: 20px;
        }

        .shopglut-accountpage.template1 .detail-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid <?php echo esc_attr($settings['order_summary_border_color']); ?>;
            text-align: center;
        }

        .shopglut-accountpage.template1 .detail-label {
            font-size: <?php echo esc_attr($settings['detail_font_size']); ?>px;
            color: <?php echo esc_attr($settings['detail_label_color']); ?>;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .shopglut-accountpage.template1 .detail-value {
            font-weight: bold;
            color: <?php echo esc_attr($settings['detail_value_color']); ?>;
        }

        .shopglut-accountpage.template1 .order-items {
            background: white;
            border: 1px solid <?php echo esc_attr($settings['order_summary_border_color']); ?>;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .shopglut-accountpage.template1 .items-header {
            background-color: <?php echo esc_attr($settings['order_summary_background_color']); ?>;
            padding: 12px 15px;
            font-weight: bold;
            border-bottom: 1px solid <?php echo esc_attr($settings['order_summary_border_color']); ?>;
            color: <?php echo esc_attr($settings['items_header_color']); ?>;
            font-size: <?php echo esc_attr($settings['items_header_font_size']); ?>px;
        }

        .shopglut-accountpage.template1 .item {
            padding: 15px;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: <?php echo esc_attr($settings['item_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .item:last-child {
            border-bottom: none;
        }

        .shopglut-accountpage.template1 .item-info {
            flex: 1;
        }

        .shopglut-accountpage.template1 .item-name {
            font-weight: bold;
            margin-bottom: 3px;
            color: <?php echo esc_attr($settings['item_name_color']); ?>;
            font-size: <?php echo esc_attr($settings['item_name_font_size']); ?>px;
        }

        .shopglut-accountpage.template1 .item-meta {
            font-size: <?php echo esc_attr($settings['item_meta_font_size']); ?>px;
            color: <?php echo esc_attr($settings['item_meta_color']); ?>;
        }

        .shopglut-accountpage.template1 .item-price {
            font-weight: bold;
            color: <?php echo esc_attr($settings['item_price_color']); ?>;
            font-size: <?php echo esc_attr($settings['item_price_font_size']); ?>px;
        }

        .shopglut-accountpage.template1 .total-section {
            background-color: <?php echo esc_attr($settings['total_section_background_color']); ?>;
            border-top: 1px solid <?php echo esc_attr($settings['total_section_border_color']); ?>;
            padding: <?php echo esc_attr($total_section_padding); ?>;
            border-radius: 5px;
        }

        .shopglut-accountpage.template1 .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: <?php echo esc_attr($settings['total_row_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .total-row span:first-child {
            color: <?php echo esc_attr($settings['total_row_label_color']); ?>;
            font-size: <?php echo esc_attr($settings['total_row_font_size']); ?>px;
        }

        .shopglut-accountpage.template1 .total-row span:last-child {
            color: <?php echo esc_attr($settings['total_row_value_color']); ?>;
            font-size: <?php echo esc_attr($settings['total_row_font_size']); ?>px;
        }

        .shopglut-accountpage.template1 .total-row.grand-total span:first-child {
            color: <?php echo esc_attr($settings['grand_total_label_color']); ?>;
            font-size: <?php echo esc_attr($settings['grand_total_font_size']); ?>px;
            font-weight: <?php echo esc_attr($settings['grand_total_font_weight']); ?>;
        }

        .shopglut-accountpage.template1 .total-row.grand-total span:last-child {
            color: <?php echo esc_attr($settings['grand_total_value_color']); ?>;
            font-size: <?php echo esc_attr($settings['grand_total_font_size']); ?>px;
            font-weight: <?php echo esc_attr($settings['grand_total_font_weight']); ?>;
        }

        .shopglut-accountpage.template1 .total-row.grand-total {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid <?php echo esc_attr($settings['total_section_border_color']); ?>;
        }

        .shopglut-accountpage.template1 .address-section {
            margin-top: <?php echo esc_attr($settings['section_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .address-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: <?php echo esc_attr($settings['address_grid_gap']); ?>px;
        }

        .shopglut-accountpage.template1 .address-card {
            background: <?php echo esc_attr($settings['address_card_background_color']); ?>;
            border: 1px solid <?php echo esc_attr($settings['address_card_border_color']); ?>;
            border-radius: <?php echo esc_attr($settings['address_card_border_radius']); ?>px;
            padding: <?php echo esc_attr($address_card_padding); ?>;
        }

        .shopglut-accountpage.template1 .address-header {
            font-weight: <?php echo esc_attr($settings['address_header_font_weight']); ?>;
            font-size: <?php echo esc_attr($settings['address_header_font_size']); ?>px;
            color: <?php echo esc_attr($settings['address_header_color']); ?>;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid <?php echo esc_attr($settings['address_card_border_color']); ?>;
        }

        .shopglut-accountpage.template1 .address-content {
            color: <?php echo esc_attr($settings['address_content_color']); ?>;
            font-size: <?php echo esc_attr($settings['address_content_font_size']); ?>px;
            line-height: <?php echo esc_attr($settings['address_line_height']); ?>;
        }

        .shopglut-accountpage.template1 .address-content p {
            margin-bottom: 5px;
        }

        .shopglut-accountpage.template1 .address-content strong {
            color: <?php echo esc_attr($settings['address_header_color']); ?>;
        }

        .shopglut-accountpage.template1 .shipping-note {
            margin-top: 10px;
            padding: 8px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 3px;
            font-size: 0.9rem;
            color: #155724;
        }

        .shopglut-accountpage.template1 .actions {
            display: flex;
            gap: <?php echo esc_attr($settings['button_spacing']); ?>px;
            margin-top: <?php echo esc_attr($settings['section_spacing']); ?>px;
            flex-wrap: wrap;
        }

        .shopglut-accountpage.template1 .btn {
            flex: 1;
            min-width: 180px;
            padding: <?php echo esc_attr($button_padding); ?>;
            border: none;
            border-radius: <?php echo esc_attr($settings['button_border_radius']); ?>px;
            font-size: <?php echo esc_attr($settings['button_font_size']); ?>px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .shopglut-accountpage.template1 .btn-primary {
            background-color: <?php echo esc_attr($settings['track_order_button_background']); ?>;
            color: <?php echo esc_attr($settings['track_order_button_text_color']); ?>;
        }

        .shopglut-accountpage.template1 .btn-primary:hover {
            background-color: <?php echo esc_attr($settings['track_order_button_hover_background']); ?>;
        }

        .shopglut-accountpage.template1 .btn-secondary {
            background-color: <?php echo esc_attr($settings['continue_shopping_button_background']); ?>;
            color: <?php echo esc_attr($settings['continue_shopping_button_text_color']); ?>;
            border: 1px solid <?php echo esc_attr($settings['continue_shopping_button_text_color']); ?>;
        }

        .shopglut-accountpage.template1 .btn-secondary:hover {
            background-color: <?php echo esc_attr($settings['continue_shopping_button_hover_background']); ?>;
        }

        .shopglut-accountpage.template1 .footer {
            text-align: center;
            padding: <?php echo esc_attr($footer_padding); ?>;
            color: <?php echo esc_attr($settings['footer_text_color']); ?>;
            font-size: <?php echo esc_attr($settings['footer_font_size']); ?>px;
            background-color: <?php echo esc_attr($settings['footer_background_color']); ?>;
            border-top: 1px solid <?php echo esc_attr($settings['order_summary_border_color']); ?>;
            margin-top: <?php echo esc_attr($settings['section_spacing']); ?>px;
        }

        .shopglut-accountpage.template1 .footer p {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .shopglut-accountpage.template1 .container {
                margin: 10px;
            }

            .shopglut-accountpage.template1 .header {
                padding: 20px 15px;
            }

            .shopglut-accountpage.template1 .header h1 {
                font-size: 1.5rem;
            }

            .shopglut-accountpage.template1 .content {
                padding: 20px 15px;
            }

            .shopglut-accountpage.template1 .order-summary {
                padding: 15px;
            }

            .shopglut-accountpage.template1 .order-details {
                grid-template-columns: 1fr;
            }

            .shopglut-accountpage.template1 .address-grid {
                grid-template-columns: 1fr;
            }

            .shopglut-accountpage.template1 .actions {
                flex-direction: column;
            }

            .shopglut-accountpage.template1 .btn {
                min-width: auto;
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
        $table_name = esc_sql($wpdb->prefix . 'shopglut_accountpage_layouts');

        // Validate layout_id
        $layout_id = is_numeric($layout_id) ? absint($layout_id) : 0;

        // Try to get from cache first
        $cache_key = 'shopglut_layout_settings_style_' . $layout_id;
        $layout_data = wp_cache_get($cache_key, 'shopglut_layouts');

        if (false === $layout_data) {
            // Get layout data from database
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using sprintf with escaped table name, direct query required for custom table operation
                $wpdb->prepare(
                    sprintf("SELECT layout_settings FROM `%s` WHERE id = %d", esc_sql($table_name)), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf for table name, expected 0 but proper placeholders are used
                    $layout_id
                )
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
        }

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);
            if (isset($settings['shopg_accountpage_settings_template1']['accountpage-page-settings'])) {
                return $this->flattenSettings($settings['shopg_accountpage_settings_template1']['accountpage-page-settings']);
            }
        }

        return $this->getDefaultSettings();
    }

    /**
     * Extract padding values from array format to CSS string
     */
    private function extractPadding($padding_array) {
        if (is_array($padding_array)) {
            $unit = isset($padding_array['unit']) ? $padding_array['unit'] : 'px';
            $top = isset($padding_array['top']) ? $padding_array['top'] : '0';
            $right = isset($padding_array['right']) ? $padding_array['right'] : '0';
            $bottom = isset($padding_array['bottom']) ? $padding_array['bottom'] : '0';
            $left = isset($padding_array['left']) ? $padding_array['left'] : '0';

            return $top . $unit . ' ' . $right . $unit . ' ' . $bottom . $unit . ' ' . $left . $unit;
        }
        return $padding_array;
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
     * Get default settings values
     */
    private function getDefaultSettings() {
        return array(
            // Header Section - Success Icon
            'show_success_icon' => true,
            'success_icon_background_color' => '#10b981',
            'success_icon_text_color' => '#ffffff',
            'success_icon_size' => 60,
            'success_icon_border_radius' => 50,

            // Header Section - Thank You Message
            'show_thank_you_heading' => true,
            'thank_you_heading_text' => __('Thank You!', 'shopglut'),
            'thank_you_heading_color' => '#111827',
            'thank_you_heading_font_size' => 32,

            // Header Section - Success Description
            'show_success_description' => true,
            'success_description_text' => __('Your order has been successfully placed and is being processed.', 'shopglut'),
            'success_description_color' => '#6b7280',
            'success_description_font_size' => 16,

            // Header Section - Background
            'header_background_color' => '#f9fafb',
            'header_padding' => array('top' => '40', 'right' => '20', 'bottom' => '40', 'left' => '20', 'unit' => 'px'),

            // Order Summary Section
            'show_order_summary' => true,
            'order_summary_background_color' => '#ffffff',
            'order_summary_border_color' => '#e5e7eb',
            'order_summary_border_radius' => 8,
            'order_summary_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),

            // Order Header
            'show_order_number' => true,
            'order_number_color' => '#111827',
            'order_number_font_size' => 20,
            'show_order_status' => true,
            'order_status_background_color' => '#dbeafe',
            'order_status_text_color' => '#1e40af',

            // Order Details
            'show_order_details' => true,
            'detail_label_color' => '#6b7280',
            'detail_value_color' => '#111827',
            'detail_font_size' => 14,
            'detail_spacing' => 16,

            // Order Items
            'show_items_header' => true,
            'items_header_text' => __('Order Items', 'shopglut'),
            'items_header_color' => '#111827',
            'items_header_font_size' => 18,
            'item_name_color' => '#111827',
            'item_name_font_size' => 16,
            'item_meta_color' => '#6b7280',
            'item_meta_font_size' => 14,
            'item_price_color' => '#111827',
            'item_price_font_size' => 16,
            'item_spacing' => 16,

            // Total Section
            'show_total_section' => true,
            'total_section_background_color' => '#f9fafb',
            'total_section_border_color' => '#e5e7eb',
            'total_section_padding' => array('top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'),
            'show_subtotal' => true,
            'show_shipping' => true,
            'show_tax' => true,
            'total_row_label_color' => '#6b7280',
            'total_row_value_color' => '#111827',
            'total_row_font_size' => 16,
            'total_row_spacing' => 12,
            'grand_total_label_color' => '#111827',
            'grand_total_value_color' => '#059669',
            'grand_total_font_size' => 20,
            'grand_total_font_weight' => '700',

            // Address Section
            'show_address_section' => true,
            'address_grid_gap' => 24,
            'show_billing_address' => true,
            'show_shipping_address' => true,
            'address_card_background_color' => '#ffffff',
            'address_card_border_color' => '#e5e7eb',
            'address_card_border_radius' => 8,
            'address_card_padding' => array('top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'),
            'address_header_color' => '#111827',
            'address_header_font_size' => 18,
            'address_header_font_weight' => '600',
            'address_content_color' => '#6b7280',
            'address_content_font_size' => 14,
            'address_line_height' => 1.6,

            // Action Buttons
            'show_track_order_button' => true,
            'track_order_button_text' => __('Track Your Order', 'shopglut'),
            'track_order_button_background' => '#3b82f6',
            'track_order_button_text_color' => '#ffffff',
            'track_order_button_hover_background' => '#2563eb',
            'show_continue_shopping_button' => true,
            'continue_shopping_button_text' => __('Continue Shopping', 'shopglut'),
            'continue_shopping_button_background' => '#f3f4f6',
            'continue_shopping_button_text_color' => '#111827',
            'continue_shopping_button_hover_background' => '#e5e7eb',
            'button_font_size' => 16,
            'button_padding' => array('top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px'),
            'button_border_radius' => 6,
            'button_spacing' => 16,

            // Footer Section
            'show_footer' => true,
            'footer_background_color' => '#f9fafb',
            'footer_text_color' => '#6b7280',
            'footer_font_size' => 14,
            'footer_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),
            'footer_message_1' => __("We've sent a confirmation email with your order details to your email address.", 'shopglut'),
            'footer_message_2' => __("If you have any questions, please don't hesitate to contact our customer support.", 'shopglut'),

            // General Styling
            'container_max_width' => 1200,
            'container_background_color' => '#ffffff',
            'container_padding' => array('top' => '0', 'right' => '20', 'bottom' => '0', 'left' => '20', 'unit' => 'px'),
            'section_spacing' => 32,
            'font_family' => 'inherit',
        );
    }

}
