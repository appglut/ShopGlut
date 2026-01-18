<?php
/**
 * Cart Page Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 cart page layout.
 * Template1 features:
 * - Product table with headers (Product, Price, Quantity, Total, Remove)
 * - Product images, titles, meta descriptions, and badges
 * - Quantity controls with +/- buttons
 * - Discount code section with input form
 * - Order summary section with pricing breakdown
 * - Security badges display
 * - Continue shopping link
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_cartpage_STYLING = "shopg_cartpage_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_cart_live_preview',
	array(
		'title' => __( 'Preview -  Demo Mode', 'shopglut' ),
		'post_type' => 'cartpage',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_cart_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Cart Page Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_cartpage_STYLING,
    array(
        'title' => esc_html__('Template1 Cart Page Settings', 'shopglut'),
        'post_type' => 'cartpage',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_cartpage_STYLING,
    array(
        'fields' => array(
            array(
                'id' => 'cart-page-settings',
                'type' => 'tabbed',
                'title' => __('Template1 Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== CART TABLE SETTINGS ====================
                    array(
                        'title' => __('Cart Table', 'shopglut'),
                        'icon' => 'fas fa-table',
                        'fields' => array(

                            // Table Header
                            array(
                                'id' => 'table_header',
                                'type' => 'fieldset',
                                'title' => __('Table Header Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_table_header',
                                        'type' => 'switcher',
                                        'title' => __('Show Table Header', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'header_background_color',
                                        'type' => 'color',
                                        'title' => __('Header Background Color', 'shopglut'),
                                        'default' => '#f3f4f6',
                                        'dependency' => array('show_table_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'header_text_color',
                                        'type' => 'color',
                                        'title' => __('Header Text Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_table_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'header_font_weight',
                                        'type' => 'select',
                                        'title' => __('Header Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                        'dependency' => array('show_table_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'header_padding',
                                        'type' => 'spacing',
                                        'title' => __('Header Cell Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '16',
                                            'right' => '12',
                                            'bottom' => '16',
                                            'left' => '12',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_table_header', '==', true),
                                    ),
                                ),
                            ),

                            // Product Image Settings
                            array(
                                'id' => 'product_images',
                                'type' => 'fieldset',
                                'title' => __('Product Image Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'product_image_size',
                                        'type' => 'dimensions',
                                        'title' => __('Product Image Size', 'shopglut'),
                                        'default' => array(
                                            'width' => 60,
                                            'height' => 60,
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'image_background_color',
                                        'type' => 'color',
                                        'title' => __('Image Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'image_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Image Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 2,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'image_border_color',
                                        'type' => 'color',
                                        'title' => __('Image Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'image_border_width',
                                        'type' => 'slider',
                                        'title' => __('Image Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 5,
                                        'step' => 1,
                                        'default' => 1,
                                    ),
                                ),
                            ),

                            // Product Title Settings
                            array(
                                'id' => 'product_title',
                                'type' => 'fieldset',
                                'title' => __('Product Title Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'product_title_color',
                                        'type' => 'color',
                                        'title' => __('Product Title Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'product_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Product Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'product_title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Product Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                    array(
                                        'id' => 'show_product_link',
                                        'type' => 'switcher',
                                        'title' => __('Make Product Title Clickable', 'shopglut'),
                                        'default' => true,
                                    ),
                                ),
                            ),

                            // Product Meta & Categories
                            array(
                                'id' => 'product_meta',
                                'type' => 'fieldset',
                                'title' => __('Product Meta & Categories', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_product_meta',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Meta Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'product_meta_color',
                                        'type' => 'color',
                                        'title' => __('Product Meta Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'product_meta_font_size',
                                        'type' => 'slider',
                                        'title' => __('Product Meta Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_product_badges',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Badges', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Badge Background Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                ),
                            ),

                            // Quantity Settings
                            array(
                                'id' => 'quantity_settings',
                                'type' => 'fieldset',
                                'title' => __('Quantity Control Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'quantity_button_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Color', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                    array(
                                        'id' => 'quantity_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'quantity_button_hover_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Hover Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'quantity_input_background',
                                        'type' => 'color',
                                        'title' => __('Quantity Input Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'quantity_input_border',
                                        'type' => 'color',
                                        'title' => __('Quantity Input Border', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                    array(
                                        'id' => 'quantity_control_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Quantity Control Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                ),
                            ),

                            // Pricing Settings
                            array(
                                'id' => 'pricing_settings',
                                'type' => 'fieldset',
                                'title' => __('Price Display Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'price_color',
                                        'type' => 'color',
                                        'title' => __('Price Text Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'price_font_size',
                                        'type' => 'slider',
                                        'title' => __('Price Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'price_font_weight',
                                        'type' => 'select',
                                        'title' => __('Price Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                    array(
                                        'id' => 'total_price_highlight',
                                        'type' => 'switcher',
                                        'title' => __('Highlight Total Price', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'total_price_color',
                                        'type' => 'color',
                                        'title' => __('Total Price Color', 'shopglut'),
                                        'default' => '#059669',
                                        'dependency' => array('total_price_highlight', '==', true),
                                    ),
                                ),
                            ),

                            // General Table Styling
                            array(
                                'id' => 'table_styling',
                                'type' => 'fieldset',
                                'title' => __('General Table Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'table_background_color',
                                        'type' => 'color',
                                        'title' => __('Table Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'table_border_color',
                                        'type' => 'color',
                                        'title' => __('Table Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'table_border_width',
                                        'type' => 'slider',
                                        'title' => __('Table Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 3,
                                        'step' => 1,
                                        'default' => 1,
                                    ),
                                    array(
                                        'id' => 'table_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Table Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'row_padding',
                                        'type' => 'spacing',
                                        'title' => __('Table Row Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '16',
                                            'right' => '12',
                                            'bottom' => '16',
                                            'left' => '12',
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'row_hover_effect',
                                        'type' => 'switcher',
                                        'title' => __('Enable Row Hover Effect', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'row_hover_color',
                                        'type' => 'color',
                                        'title' => __('Row Hover Background Color', 'shopglut'),
                                        'default' => '#f8fafc',
                                        'dependency' => array('row_hover_effect', '==', true),
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

                            // Summary Section Layout
                            array(
                                'id' => 'summary_section_layout',
                                'type' => 'fieldset',
                                'title' => __('Summary Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_summary_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Order Summary Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'summary_background_color',
                                        'type' => 'color',
                                        'title' => __('Summary Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                        'dependency' => array('show_summary_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_border_color',
                                        'type' => 'color',
                                        'title' => __('Summary Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_summary_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Summary Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_summary_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_padding',
                                        'type' => 'spacing',
                                        'title' => __('Summary Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '24',
                                            'right' => '20',
                                            'bottom' => '24',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_summary_section', '==', true),
                                    ),
                                ),
                            ),

                            // Summary Header Settings
                            array(
                                'id' => 'summary_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Summary Header Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_summary_header',
                                        'type' => 'switcher',
                                        'title' => __('Show Summary Header', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'summary_title_text',
                                        'type' => 'text',
                                        'title' => __('Summary Title Text', 'shopglut'),
                                        'default' => __('Order Summary', 'shopglut'),
                                        'dependency' => array('show_summary_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_title_color',
                                        'type' => 'color',
                                        'title' => __('Title Text Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_summary_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 28,
                                        'step' => 1,
                                        'default' => 20,
                                        'dependency' => array('show_summary_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_summary_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Summary Icon', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_summary_header', '==', true),
                                    ),
                                    array(
                                        'id' => 'summary_icon_color',
                                        'type' => 'color',
                                        'title' => __('Icon Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_summary_icon', '==', true),
                                    ),
                                ),
                            ),

                            // Summary Row Settings
                            array(
                                'id' => 'summary_row_settings',
                                'type' => 'fieldset',
                                'title' => __('Summary Row Settings', 'shopglut'),
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
                                        'id' => 'show_discount_row',
                                        'type' => 'switcher',
                                        'title' => __('Show Discount Row', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'row_label_color',
                                        'type' => 'color',
                                        'title' => __('Row Label Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'row_value_color',
                                        'type' => 'color',
                                        'title' => __('Row Value Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'row_font_size',
                                        'type' => 'slider',
                                        'title' => __('Row Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'row_spacing',
                                        'type' => 'slider',
                                        'title' => __('Row Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 24,
                                        'step' => 2,
                                        'default' => 12,
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
                                        'id' => 'total_label_color',
                                        'type' => 'color',
                                        'title' => __('Total Label Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'total_value_color',
                                        'type' => 'color',
                                        'title' => __('Total Value Color', 'shopglut'),
                                        'default' => '#059669',
                                    ),
                                    array(
                                        'id' => 'total_font_size',
                                        'type' => 'slider',
                                        'title' => __('Total Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 18,
                                    ),
                                    array(
                                        'id' => 'total_font_weight',
                                        'type' => 'select',
                                        'title' => __('Total Font Weight', 'shopglut'),
                                        'options' => array(
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                            '800' => __('Extra Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                    array(
                                        'id' => 'total_row_separator',
                                        'type' => 'switcher',
                                        'title' => __('Show Total Row Separator', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'total_separator_color',
                                        'type' => 'color',
                                        'title' => __('Separator Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('total_row_separator', '==', true),
                                    ),
                                ),
                            ),

                            // Checkout Button Settings
                            array(
                                'id' => 'checkout_button_settings',
                                'type' => 'fieldset',
                                'title' => __('Checkout Button Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'checkout_button_text',
                                        'type' => 'text',
                                        'title' => __('Checkout Button Text', 'shopglut'),
                                        'default' => __('Secure Checkout', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'checkout_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#059669',
                                    ),
                                    array(
                                        'id' => 'checkout_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'checkout_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#047857',
                                    ),
                                    array(
                                        'id' => 'checkout_button_font_size',
                                        'type' => 'slider',
                                        'title' => __('Button Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'checkout_button_padding',
                                        'type' => 'spacing',
                                        'title' => __('Button Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '16',
                                            'right' => '24',
                                            'bottom' => '16',
                                            'left' => '24',
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'checkout_button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'show_checkout_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Checkout Icon', 'shopglut'),
                                        'default' => true,
                                    ),
                                ),
                            ),

                            // Security Badges Settings
                            array(
                                'id' => 'security_badges_settings',
                                'type' => 'fieldset',
                                'title' => __('Security Badges Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_security_badges',
                                        'type' => 'switcher',
                                        'title' => __('Show Security Badges', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'security_badges_layout',
                                        'type' => 'select',
                                        'title' => __('Security Badges Layout', 'shopglut'),
                                        'options' => array(
                                            'horizontal' => __('Horizontal', 'shopglut'),
                                            'vertical' => __('Vertical', 'shopglut'),
                                            'grid' => __('Grid', 'shopglut'),
                                        ),
                                        'default' => 'horizontal',
                                        'dependency' => array('show_security_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'security_badge_spacing',
                                        'type' => 'slider',
                                        'title' => __('Badge Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 4,
                                        'max' => 20,
                                        'step' => 2,
                                        'default' => 8,
                                        'dependency' => array('show_security_badges', '==', true),
                                    ),
                                ),
                            ),

                            // SSL Security Badge
                            array(
                                'id' => 'ssl_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('SSL Security Badge', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_ssl_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show SSL Security Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_security_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'ssl_badge_text',
                                        'type' => 'text',
                                        'title' => __('SSL Badge Text', 'shopglut'),
                                        'default' => __('SSL Secured', 'shopglut'),
                                        'dependency' => array('show_ssl_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'ssl_badge_icon',
                                        'type' => 'icon',
                                        'title' => __('SSL Badge Icon', 'shopglut'),
                                        'default' => 'fas fa-shield-alt',
                                        'dependency' => array('show_ssl_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'ssl_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('SSL Badge Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_ssl_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'ssl_badge_icon_color',
                                        'type' => 'color',
                                        'title' => __('SSL Badge Icon Color', 'shopglut'),
                                        'default' => '#059669',
                                        'dependency' => array('show_ssl_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'ssl_badge_font_size',
                                        'type' => 'slider',
                                        'title' => __('SSL Badge Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_ssl_badge', '==', true),
                                    ),
                                ),
                            ),

                            // Payment Security Badge
                            array(
                                'id' => 'payment_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Safe Payment Badge', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_payment_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show Safe Payment Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_security_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'payment_badge_text',
                                        'type' => 'text',
                                        'title' => __('Payment Badge Text', 'shopglut'),
                                        'default' => __('Safe Payment', 'shopglut'),
                                        'dependency' => array('show_payment_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'payment_badge_icon',
                                        'type' => 'icon',
                                        'title' => __('Payment Badge Icon', 'shopglut'),
                                        'default' => 'fas fa-credit-card',
                                        'dependency' => array('show_payment_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'payment_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Payment Badge Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_payment_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'payment_badge_icon_color',
                                        'type' => 'color',
                                        'title' => __('Payment Badge Icon Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_payment_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'payment_badge_font_size',
                                        'type' => 'slider',
                                        'title' => __('Payment Badge Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_payment_badge', '==', true),
                                    ),
                                ),
                            ),

                            // Return Policy Badge
                            array(
                                'id' => 'return_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Return Policy Badge', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_return_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show Return Policy Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_security_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'return_badge_text',
                                        'type' => 'text',
                                        'title' => __('Return Badge Text', 'shopglut'),
                                        'default' => __('30-Day Return', 'shopglut'),
                                        'dependency' => array('show_return_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'return_badge_icon',
                                        'type' => 'icon',
                                        'title' => __('Return Badge Icon', 'shopglut'),
                                        'default' => 'fas fa-undo',
                                        'dependency' => array('show_return_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'return_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Return Badge Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_return_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'return_badge_icon_color',
                                        'type' => 'color',
                                        'title' => __('Return Badge Icon Color', 'shopglut'),
                                        'default' => '#f59e0b',
                                        'dependency' => array('show_return_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'return_badge_font_size',
                                        'type' => 'slider',
                                        'title' => __('Return Badge Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_return_badge', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== DISCOUNT CODE SECTION ====================
                    array(
                        'title' => __('Discount Code Section', 'shopglut'),
                        'icon' => 'fas fa-tag',
                        'fields' => array(

                            // Discount Section Layout
                            array(
                                'id' => 'discount_section_layout',
                                'type' => 'fieldset',
                                'title' => __('Discount Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_discount_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Discount Code Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'discount_section_background',
                                        'type' => 'color',
                                        'title' => __('Section Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_discount_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'discount_section_border',
                                        'type' => 'color',
                                        'title' => __('Section Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_discount_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'discount_section_padding',
                                        'type' => 'spacing',
                                        'title' => __('Section Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '20',
                                            'right' => '20',
                                            'bottom' => '20',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                        'dependency' => array('show_discount_section', '==', true),
                                    ),
                                ),
                            ),

                            // Section Title Settings
                            array(
                                'id' => 'discount_title_settings',
                                'type' => 'fieldset',
                                'title' => __('Section Title Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_discount_title',
                                        'type' => 'switcher',
                                        'title' => __('Show Section Title', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'discount_title_text',
                                        'type' => 'text',
                                        'title' => __('Section Title Text', 'shopglut'),
                                        'default' => __('Discount Code', 'shopglut'),
                                        'dependency' => array('show_discount_title', '==', true),
                                    ),
                                    array(
                                        'id' => 'discount_title_color',
                                        'type' => 'color',
                                        'title' => __('Title Text Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_discount_title', '==', true),
                                    ),
                                    array(
                                        'id' => 'discount_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 18,
                                        'dependency' => array('show_discount_title', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_discount_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Title Icon', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_discount_title', '==', true),
                                    ),
                                    array(
                                        'id' => 'discount_icon_color',
                                        'type' => 'color',
                                        'title' => __('Icon Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_discount_icon', '==', true),
                                    ),
                                ),
                            ),

                            // Input Form Settings
                            array(
                                'id' => 'discount_form_settings',
                                'type' => 'fieldset',
                                'title' => __('Discount Form Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'coupon_input_placeholder',
                                        'type' => 'text',
                                        'title' => __('Input Placeholder Text', 'shopglut'),
                                        'default' => __('Enter coupon code', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'coupon_input_background',
                                        'type' => 'color',
                                        'title' => __('Input Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'coupon_input_border',
                                        'type' => 'color',
                                        'title' => __('Input Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                    array(
                                        'id' => 'coupon_input_text_color',
                                        'type' => 'color',
                                        'title' => __('Input Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'coupon_input_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Input Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                    array(
                                        'id' => 'coupon_input_padding',
                                        'type' => 'spacing',
                                        'title' => __('Input Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '12',
                                            'right' => '16',
                                            'bottom' => '12',
                                            'left' => '16',
                                            'unit' => 'px',
                                        ),
                                    ),
                                ),
                            ),

                            // Apply Button Settings
                            array(
                                'id' => 'apply_button_settings',
                                'type' => 'fieldset',
                                'title' => __('Apply Button Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'apply_button_text',
                                        'type' => 'text',
                                        'title' => __('Apply Button Text', 'shopglut'),
                                        'default' => __('Apply', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'apply_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                    ),
                                    array(
                                        'id' => 'apply_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'apply_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#2563eb',
                                    ),
                                    array(
                                        'id' => 'apply_button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                    array(
                                        'id' => 'apply_button_padding',
                                        'type' => 'spacing',
                                        'title' => __('Button Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '12',
                                            'right' => '20',
                                            'bottom' => '12',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                    ),
                                ),
                            ),

                            // Coupon Messages
                            array(
                                'id' => 'coupon_messages',
                                'type' => 'fieldset',
                                'title' => __('Coupon Messages', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_coupon_messages',
                                        'type' => 'switcher',
                                        'title' => __('Show Coupon Messages', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'success_message_color',
                                        'type' => 'color',
                                        'title' => __('Success Message Color', 'shopglut'),
                                        'default' => '#059669',
                                        'dependency' => array('show_coupon_messages', '==', true),
                                    ),
                                    array(
                                        'id' => 'error_message_color',
                                        'type' => 'color',
                                        'title' => __('Error Message Color', 'shopglut'),
                                        'default' => '#dc2626',
                                        'dependency' => array('show_coupon_messages', '==', true),
                                    ),
                                    array(
                                        'id' => 'message_font_size',
                                        'type' => 'slider',
                                        'title' => __('Message Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_coupon_messages', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== CONTINUE SHOPPING LINK ====================
                    array(
                        'title' => __('Continue Shopping Link', 'shopglut'),
                        'icon' => 'fas fa-arrow-left',
                        'fields' => array(

                            // Continue Shopping Settings
                            array(
                                'id' => 'continue_shopping_settings',
                                'type' => 'fieldset',
                                'title' => __('Continue Shopping Link Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_continue_shopping',
                                        'type' => 'switcher',
                                        'title' => __('Show Continue Shopping Link', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'continue_shopping_text',
                                        'type' => 'text',
                                        'title' => __('Continue Shopping Text', 'shopglut'),
                                        'default' => __('Continue Shopping', 'shopglut'),
                                        'dependency' => array('show_continue_shopping', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_shopping_url',
                                        'type' => 'select',
                                        'title' => __('Continue Shopping Link Target', 'shopglut'),
                                        'options' => array(
                                            'shop' => __('Shop Page', 'shopglut'),
                                            'home' => __('Homepage', 'shopglut'),
                                            'previous' => __('Previous Page', 'shopglut'),
                                            'custom' => __('Custom URL', 'shopglut'),
                                        ),
                                        'default' => 'shop',
                                        'dependency' => array('show_continue_shopping', '==', true),
                                    ),
                                    array(
                                        'id' => 'custom_continue_url',
                                        'type' => 'text',
                                        'title' => __('Custom Continue Shopping URL', 'shopglut'),
                                        'dependency' => array('continue_shopping_url', '==', 'custom'),
                                    ),
                                    array(
                                        'id' => 'show_continue_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Continue Shopping Icon', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_continue_shopping', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_link_color',
                                        'type' => 'color',
                                        'title' => __('Link Text Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                        'dependency' => array('show_continue_shopping', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Link Hover Color', 'shopglut'),
                                        'default' => '#2563eb',
                                        'dependency' => array('show_continue_shopping', '==', true),
                                    ),
                                    array(
                                        'id' => 'continue_link_font_size',
                                        'type' => 'slider',
                                        'title' => __('Link Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_continue_shopping', '==', true),
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
?>