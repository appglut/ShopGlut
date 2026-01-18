<?php
/**
 * Order Complete Page Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 order complete page layout.
 * Template1 features:
 * - Success header with icon and thank you message
 * - Order summary with order number and status
 * - Order details (date, email, delivery, payment)
 * - Order items list with product names and prices
 * - Total section with subtotal, shipping, tax, and total
 * - Billing and shipping address cards
 * - Action buttons (Track Order, Continue Shopping)
 * - Footer with confirmation message
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_accountpage_STYLING = "shopg_accountpage_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_accountpage_live_preview',
	array(
		'title' => __( 'Preview -  Demo Mode', 'shopglut' ),
		'post_type' => 'accountpage',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_accountpage_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Order Complete Page Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_accountpage_STYLING,
    array(
        'title' => esc_html__('Template1 Order Complete Page Settings', 'shopglut'),
        'post_type' => 'accountpage',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_accountpage_STYLING,
    array(
        'fields' => array(
            array(
                'id' => 'accountpage-page-settings',
                'type' => 'tabbed',
                'title' => __('Template1 Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== OVERRIDE SETTINGS ====================
                    array(
                        'title' => __('Override Settings', 'shopglut'),
                        'icon' => 'fas fa-cog',
                        'fields' => array(
                            array(
                                'id' => 'override_woocommerce_accountpage',
                                'type' => 'switcher',
                                'title' => __('Override WooCommerce Order Complete Page', 'shopglut'),
                                'desc' => __('Enable this to replace the default WooCommerce order complete/thank you page with this custom layout. Only one layout can override the order complete page at a time.', 'shopglut'),
                                'default' => false,
                            ),
                            array(
                                'type' => 'content',
                                'content' => '<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 12px; margin-top: 15px;">
                                    <p style="margin: 0; font-size: 13px;"><strong>' . __('Note:', 'shopglut') . '</strong> ' . __('When you enable this option, this layout will automatically replace the WooCommerce order complete page. Any other layout that has override enabled will be automatically disabled.', 'shopglut') . '</p>
                                    </div>',
                            ),
                        ),
                    ),

                    // ==================== HEADER SECTION ====================
                    array(
                        'title' => __('Header Section', 'shopglut'),
                        'icon' => 'fas fa-check-circle',
                        'fields' => array(

                            // Success Icon Settings
                            array(
                                'id' => 'success_icon_settings',
                                'type' => 'fieldset',
                                'title' => __('Success Icon Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_success_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Success Icon', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'success_icon_background_color',
                                        'type' => 'color',
                                        'title' => __('Icon Background Color', 'shopglut'),
                                        'default' => '#10b981',
                                        'dependency' => array('show_success_icon', '==', true),
                                    ),
                                    array(
                                        'id' => 'success_icon_text_color',
                                        'type' => 'color',
                                        'title' => __('Icon Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_success_icon', '==', true),
                                    ),
                                    array(
                                        'id' => 'success_icon_size',
                                        'type' => 'slider',
                                        'title' => __('Icon Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 40,
                                        'max' => 100,
                                        'step' => 5,
                                        'default' => 60,
                                        'dependency' => array('show_success_icon', '==', true),
                                    ),
                                    array(
                                        'id' => 'success_icon_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Icon Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 5,
                                        'default' => 50,
                                        'dependency' => array('show_success_icon', '==', true),
                                    ),
                                ),
                            ),

                            // Thank You Message Settings
                            array(
                                'id' => 'thank_you_message',
                                'type' => 'fieldset',
                                'title' => __('Thank You Message Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_thank_you_heading',
                                        'type' => 'switcher',
                                        'title' => __('Show Thank You Heading', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'thank_you_heading_text',
                                        'type' => 'text',
                                        'title' => __('Thank You Heading Text', 'shopglut'),
                                        'default' => __('Thank You!', 'shopglut'),
                                        'dependency' => array('show_thank_you_heading', '==', true),
                                    ),
                                    array(
                                        'id' => 'thank_you_heading_color',
                                        'type' => 'color',
                                        'title' => __('Heading Text Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_thank_you_heading', '==', true),
                                    ),
                                    array(
                                        'id' => 'thank_you_heading_font_size',
                                        'type' => 'slider',
                                        'title' => __('Heading Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 24,
                                        'max' => 48,
                                        'step' => 2,
                                        'default' => 32,
                                        'dependency' => array('show_thank_you_heading', '==', true),
                                    ),
                                ),
                            ),

                            // Success Description
                            array(
                                'id' => 'success_description',
                                'type' => 'fieldset',
                                'title' => __('Success Description Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_success_description',
                                        'type' => 'switcher',
                                        'title' => __('Show Success Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'success_description_text',
                                        'type' => 'textarea',
                                        'title' => __('Success Description Text', 'shopglut'),
                                        'default' => __('Your order has been successfully placed and is being processed.', 'shopglut'),
                                        'dependency' => array('show_success_description', '==', true),
                                    ),
                                    array(
                                        'id' => 'success_description_color',
                                        'type' => 'color',
                                        'title' => __('Description Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_success_description', '==', true),
                                    ),
                                    array(
                                        'id' => 'success_description_font_size',
                                        'type' => 'slider',
                                        'title' => __('Description Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                        'dependency' => array('show_success_description', '==', true),
                                    ),
                                ),
                            ),

                            // Header Background Settings
                            array(
                                'id' => 'header_background',
                                'type' => 'fieldset',
                                'title' => __('Header Background Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'header_background_color',
                                        'type' => 'color',
                                        'title' => __('Header Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'header_padding',
                                        'type' => 'spacing',
                                        'title' => __('Header Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '40',
                                            'right' => '20',
                                            'bottom' => '40',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== ORDER SUMMARY SECTION ====================
                    array(
                        'title' => __('Order Summary', 'shopglut'),
                        'icon' => 'fas fa-receipt',
                        'fields' => array(

                            // Order Summary Layout
                            array(
                                'id' => 'order_summary_layout',
                                'type' => 'fieldset',
                                'title' => __('Order Summary Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_order_summary',
                                        'type' => 'switcher',
                                        'title' => __('Show Order Summary', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'order_summary_background_color',
                                        'type' => 'color',
                                        'title' => __('Summary Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_order_summary', '==', true),
                                    ),
                                    array(
                                        'id' => 'order_summary_border_color',
                                        'type' => 'color',
                                        'title' => __('Summary Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_order_summary', '==', true),
                                    ),
                                    array(
                                        'id' => 'order_summary_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Summary Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_order_summary', '==', true),
                                    ),
                                    array(
                                        'id' => 'order_summary_padding',
                                        'type' => 'spacing',
                                        'title' => __('Summary Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '24',
                                            'right' => '20',
                                            'bottom' => '24',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_order_summary', '==', true),
                                    ),
                                ),
                            ),

                            // Order Header (Number & Status)
                            array(
                                'id' => 'order_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Order Header Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_order_number',
                                        'type' => 'switcher',
                                        'title' => __('Show Order Number', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'order_number_color',
                                        'type' => 'color',
                                        'title' => __('Order Number Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_order_number', '==', true),
                                    ),
                                    array(
                                        'id' => 'order_number_font_size',
                                        'type' => 'slider',
                                        'title' => __('Order Number Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 28,
                                        'step' => 1,
                                        'default' => 20,
                                        'dependency' => array('show_order_number', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_order_status',
                                        'type' => 'switcher',
                                        'title' => __('Show Order Status', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'order_status_background_color',
                                        'type' => 'color',
                                        'title' => __('Status Background Color', 'shopglut'),
                                        'default' => '#dbeafe',
                                        'dependency' => array('show_order_status', '==', true),
                                    ),
                                    array(
                                        'id' => 'order_status_text_color',
                                        'type' => 'color',
                                        'title' => __('Status Text Color', 'shopglut'),
                                        'default' => '#1e40af',
                                        'dependency' => array('show_order_status', '==', true),
                                    ),
                                ),
                            ),

                            // Order Details (Date, Email, etc.)
                            array(
                                'id' => 'order_details_settings',
                                'type' => 'fieldset',
                                'title' => __('Order Details Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_order_details',
                                        'type' => 'switcher',
                                        'title' => __('Show Order Details', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'detail_label_color',
                                        'type' => 'color',
                                        'title' => __('Detail Label Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_order_details', '==', true),
                                    ),
                                    array(
                                        'id' => 'detail_value_color',
                                        'type' => 'color',
                                        'title' => __('Detail Value Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_order_details', '==', true),
                                    ),
                                    array(
                                        'id' => 'detail_font_size',
                                        'type' => 'slider',
                                        'title' => __('Detail Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_order_details', '==', true),
                                    ),
                                    array(
                                        'id' => 'detail_spacing',
                                        'type' => 'slider',
                                        'title' => __('Detail Item Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 32,
                                        'step' => 2,
                                        'default' => 16,
                                        'dependency' => array('show_order_details', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== ORDER ITEMS SECTION ====================
                    array(
                        'title' => __('Order Items', 'shopglut'),
                        'icon' => 'fas fa-shopping-bag',
                        'fields' => array(

                            // Items Header
                            array(
                                'id' => 'items_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Items Header Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_items_header',
                                        'type' => 'switcher',
                                        'title' => __('Show Items Header', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'items_header_text',
                                        'type' => 'text',
                                        'title' => __('Items Header Text', 'shopglut'),
                                        'default' => __('Order Items', 'shopglut'),
                                        'dependency' => array('show_items_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'items_header_color',
                                        'type' => 'color',
                                        'title' => __('Header Text Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_items_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'items_header_font_size',
                                        'type' => 'slider',
                                        'title' => __('Header Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 18,
                                        'dependency' => array('show_items_header', '==', true),
                                    ),
                                ),
                            ),

                            // Item Display Settings
                            array(
                                'id' => 'item_display_settings',
                                'type' => 'fieldset',
                                'title' => __('Item Display Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'item_name_color',
                                        'type' => 'color',
                                        'title' => __('Item Name Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'item_name_font_size',
                                        'type' => 'slider',
                                        'title' => __('Item Name Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'item_meta_color',
                                        'type' => 'color',
                                        'title' => __('Item Meta Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'item_meta_font_size',
                                        'type' => 'slider',
                                        'title' => __('Item Meta Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'item_price_color',
                                        'type' => 'color',
                                        'title' => __('Item Price Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'item_price_font_size',
                                        'type' => 'slider',
                                        'title' => __('Item Price Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'item_spacing',
                                        'type' => 'slider',
                                        'title' => __('Item Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 32,
                                        'step' => 2,
                                        'default' => 16,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== TOTAL SECTION ====================
                    array(
                        'title' => __('Total Section', 'shopglut'),
                        'icon' => 'fas fa-calculator',
                        'fields' => array(

                            // Total Section Layout
                            array(
                                'id' => 'total_section_layout',
                                'type' => 'fieldset',
                                'title' => __('Total Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_total_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Total Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'total_section_background_color',
                                        'type' => 'color',
                                        'title' => __('Section Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                        'dependency' => array('show_total_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'total_section_border_color',
                                        'type' => 'color',
                                        'title' => __('Section Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_total_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'total_section_padding',
                                        'type' => 'spacing',
                                        'title' => __('Section Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '16',
                                            'right' => '16',
                                            'bottom' => '16',
                                            'left' => '16',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_total_section', '==', true),
                                    ),
                                ),
                            ),

                            // Total Row Settings
                            array(
                                'id' => 'total_row_settings',
                                'type' => 'fieldset',
                                'title' => __('Total Row Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_subtotal',
                                        'type' => 'switcher',
                                        'title' => __('Show Subtotal Row', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_shipping',
                                        'type' => 'switcher',
                                        'title' => __('Show Shipping Row', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_tax',
                                        'type' => 'switcher',
                                        'title' => __('Show Tax Row', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'total_row_label_color',
                                        'type' => 'color',
                                        'title' => __('Row Label Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'total_row_value_color',
                                        'type' => 'color',
                                        'title' => __('Row Value Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'total_row_font_size',
                                        'type' => 'slider',
                                        'title' => __('Row Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'total_row_spacing',
                                        'type' => 'slider',
                                        'title' => __('Row Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 20,
                                        'step' => 2,
                                        'default' => 12,
                                    ),
                                ),
                            ),

                            // Grand Total Settings
                            array(
                                'id' => 'grand_total_settings',
                                'type' => 'fieldset',
                                'title' => __('Grand Total Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'grand_total_label_color',
                                        'type' => 'color',
                                        'title' => __('Total Label Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'grand_total_value_color',
                                        'type' => 'color',
                                        'title' => __('Total Value Color', 'shopglut'),
                                        'default' => '#059669',
                                    ),
                                    array(
                                        'id' => 'grand_total_font_size',
                                        'type' => 'slider',
                                        'title' => __('Total Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 18,
                                        'max' => 28,
                                        'step' => 1,
                                        'default' => 20,
                                    ),
                                    array(
                                        'id' => 'grand_total_font_weight',
                                        'type' => 'select',
                                        'title' => __('Total Font Weight', 'shopglut'),
                                        'options' => array(
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                            '800' => __('Extra Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== ADDRESS SECTION ====================
                    array(
                        'title' => __('Address Section', 'shopglut'),
                        'icon' => 'fas fa-map-marker-alt',
                        'fields' => array(

                            // Address Section Layout
                            array(
                                'id' => 'address_section_layout',
                                'type' => 'fieldset',
                                'title' => __('Address Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_address_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Address Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'address_grid_gap',
                                        'type' => 'slider',
                                        'title' => __('Grid Gap Between Cards', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 48,
                                        'step' => 4,
                                        'default' => 24,
                                        'dependency' => array('show_address_section', '==', true),
                                    ),
                                ),
                            ),

                            // Address Card Settings
                            array(
                                'id' => 'address_card_settings',
                                'type' => 'fieldset',
                                'title' => __('Address Card Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_billing_address',
                                        'type' => 'switcher',
                                        'title' => __('Show Billing Address', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_shipping_address',
                                        'type' => 'switcher',
                                        'title' => __('Show Shipping Address', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'address_card_background_color',
                                        'type' => 'color',
                                        'title' => __('Card Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'address_card_border_color',
                                        'type' => 'color',
                                        'title' => __('Card Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'address_card_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Card Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'address_card_padding',
                                        'type' => 'spacing',
                                        'title' => __('Card Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '20',
                                            'right' => '20',
                                            'bottom' => '20',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                    ),
                                ),
                            ),

                            // Address Header Settings
                            array(
                                'id' => 'address_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Address Header Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'address_header_color',
                                        'type' => 'color',
                                        'title' => __('Header Text Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'address_header_font_size',
                                        'type' => 'slider',
                                        'title' => __('Header Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 22,
                                        'step' => 1,
                                        'default' => 18,
                                    ),
                                    array(
                                        'id' => 'address_header_font_weight',
                                        'type' => 'select',
                                        'title' => __('Header Font Weight', 'shopglut'),
                                        'options' => array(
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                ),
                            ),

                            // Address Content Settings
                            array(
                                'id' => 'address_content_settings',
                                'type' => 'fieldset',
                                'title' => __('Address Content Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'address_content_color',
                                        'type' => 'color',
                                        'title' => __('Content Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'address_content_font_size',
                                        'type' => 'slider',
                                        'title' => __('Content Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 13,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'address_line_height',
                                        'type' => 'slider',
                                        'title' => __('Line Height', 'shopglut'),
                                        'unit' => 'em',
                                        'min' => 1.4,
                                        'max' => 2.0,
                                        'step' => 0.1,
                                        'default' => 1.6,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== ACTION BUTTONS ====================
                    array(
                        'title' => __('Action Buttons', 'shopglut'),
                        'icon' => 'fas fa-hand-pointer',
                        'fields' => array(

                            // Track Order Button
                            array(
                                'id' => 'track_order_button',
                                'type' => 'fieldset',
                                'title' => __('Track Order Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_track_order_button',
                                        'type' => 'switcher',
                                        'title' => __('Show Track Order Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'track_order_button_text',
                                        'type' => 'text',
                                        'title' => __('Button Text', 'shopglut'),
                                        'default' => __('Track Your Order', 'shopglut'),
                                        'dependency' => array('show_track_order_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'track_order_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_track_order_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'track_order_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_track_order_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'track_order_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#2563eb',
                                        'dependency' => array('show_track_order_button', '==', true),
                                    ),
                                ),
                            ),

                            // Continue Shopping Button
                            array(
                                'id' => 'continue_shopping_button',
                                'type' => 'fieldset',
                                'title' => __('Continue Shopping Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_continue_shopping_button',
                                        'type' => 'switcher',
                                        'title' => __('Show Continue Shopping Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'continue_shopping_button_text',
                                        'type' => 'text',
                                        'title' => __('Button Text', 'shopglut'),
                                        'default' => __('Continue Shopping', 'shopglut'),
                                        'dependency' => array('show_continue_shopping_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_shopping_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#f3f4f6',
                                        'dependency' => array('show_continue_shopping_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_shopping_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_continue_shopping_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_shopping_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_continue_shopping_button', '==', true),
                                    ),
                                ),
                            ),

                            // Button General Settings
                            array(
                                'id' => 'button_general_settings',
                                'type' => 'fieldset',
                                'title' => __('Button General Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'button_font_size',
                                        'type' => 'slider',
                                        'title' => __('Button Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'button_padding',
                                        'type' => 'spacing',
                                        'title' => __('Button Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '12',
                                            'right' => '24',
                                            'bottom' => '12',
                                            'left' => '24',
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                    array(
                                        'id' => 'button_spacing',
                                        'type' => 'slider',
                                        'title' => __('Space Between Buttons', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 32,
                                        'step' => 2,
                                        'default' => 16,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== FOOTER SECTION ====================
                    array(
                        'title' => __('Footer Section', 'shopglut'),
                        'icon' => 'fas fa-comment-alt',
                        'fields' => array(

                            // Footer Settings
                            array(
                                'id' => 'footer_settings',
                                'type' => 'fieldset',
                                'title' => __('Footer Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_footer',
                                        'type' => 'switcher',
                                        'title' => __('Show Footer', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'footer_background_color',
                                        'type' => 'color',
                                        'title' => __('Footer Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                        'dependency' => array('show_footer', '==', true),
                                    ),
                                    array(
                                        'id' => 'footer_text_color',
                                        'type' => 'color',
                                        'title' => __('Footer Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_footer', '==', true),
                                    ),
                                    array(
                                        'id' => 'footer_font_size',
                                        'type' => 'slider',
                                        'title' => __('Footer Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_footer', '==', true),
                                    ),
                                    array(
                                        'id' => 'footer_padding',
                                        'type' => 'spacing',
                                        'title' => __('Footer Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '24',
                                            'right' => '20',
                                            'bottom' => '24',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_footer', '==', true),
                                    ),
                                ),
                            ),

                            // Footer Message Content
                            array(
                                'id' => 'footer_message_content',
                                'type' => 'fieldset',
                                'title' => __('Footer Message Content', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'footer_message_1',
                                        'type' => 'textarea',
                                        'title' => __('Footer Message Line 1', 'shopglut'),
                                        'default' => __("We've sent a confirmation email with your order details to your email address.", 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'footer_message_2',
                                        'type' => 'textarea',
                                        'title' => __('Footer Message Line 2', 'shopglut'),
                                        'default' => __("If you have any questions, please don't hesitate to contact our customer support.", 'shopglut'),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== GENERAL STYLING ====================
                    array(
                        'title' => __('General Styling', 'shopglut'),
                        'icon' => 'fas fa-paint-brush',
                        'fields' => array(

                            // Container Settings
                            array(
                                'id' => 'container_settings',
                                'type' => 'fieldset',
                                'title' => __('Container Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'container_max_width',
                                        'type' => 'slider',
                                        'title' => __('Container Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 800,
                                        'max' => 1400,
                                        'step' => 20,
                                        'default' => 1200,
                                    ),
                                    array(
                                        'id' => 'container_background_color',
                                        'type' => 'color',
                                        'title' => __('Container Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'container_padding',
                                        'type' => 'spacing',
                                        'title' => __('Container Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '0',
                                            'right' => '20',
                                            'bottom' => '0',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                    ),
                                ),
                            ),

                            // Spacing Settings
                            array(
                                'id' => 'spacing_settings',
                                'type' => 'fieldset',
                                'title' => __('Section Spacing', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'section_spacing',
                                        'type' => 'slider',
                                        'title' => __('Space Between Sections', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 60,
                                        'step' => 4,
                                        'default' => 32,
                                    ),
                                ),
                            ),

                            // Typography Settings
                            array(
                                'id' => 'typography_settings',
                                'type' => 'fieldset',
                                'title' => __('Typography Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'font_family',
                                        'type' => 'text',
                                        'title' => __('Font Family', 'shopglut'),
                                        'default' => 'inherit',
                                        'desc' => __('Enter font family name or leave as "inherit" to use theme font', 'shopglut'),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    )
);