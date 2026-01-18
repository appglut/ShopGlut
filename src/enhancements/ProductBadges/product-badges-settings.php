<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$shopglut_badge_settings = "shopglut_product_badge_settings";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_product_badge_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'shopglut_badges',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_product_badge_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Badge Settings
AGSHOPGLUT::createMetabox(
    $shopglut_badge_settings,
    array(
        'title' => esc_html__('Product Badge Settings', 'shopglut'),
        'post_type' => 'shopglut_badges',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $shopglut_badge_settings,
    array(
        'fields' => array(

            // ==================== GLOBAL SETTINGS ====================
            array(
                'id' => 'enable_badge',
                'type' => 'switcher',
                'title' => __('Enable Badge', 'shopglut'),
                'desc' => __('Enable or disable this badge globally', 'shopglut'),
                'default' => true,
            ),

            array(
                'id' => 'display-locations',
                'type' => 'select_badge_display',
                'title' => __('Display Badge On', 'shopglut'),
                'desc' => __('Select pages/products where the badge should appear.', 'shopglut'),
                'options' => 'select_badge_display',
                'multiple' => true,
                'chosen' => true,
                'placeholder' => __('Select pages to display badge', 'shopglut'),
                'dependency' => array('enable_badge', '==', true),
            ),

            array(
                'type' => 'subheading',
                'content' => __('Select Badge Types', 'shopglut'),
                'dependency' => array('enable_badge', '==', true),
            ),

            array(
                'id' => 'badge_type',
                'type' => 'checkbox',
                'title' => __('Badge Type', 'shopglut'),
                'desc' => __('Select one or more badge types. The corresponding tabs below will appear based on your selection.', 'shopglut'),
                'options' => array(
                    'sale' => __('Sale Badge', 'shopglut'),
                    'new' => __('New Product Badge', 'shopglut'),
                    'out_of_stock' => __('Out of Stock Badge', 'shopglut'),
                ),
                'inline' => true,
                'dependency' => array('enable_badge', '==', true),
            ),

            array(
                'id' => 'product_badge-settings',
                'type' => 'tabbed',
                'title' => __('Badge Configuration', 'shopglut'),
                'dependency' => array('enable_badge', '==', true),
                'tabs' => array(

                    // ==================== SALE BADGE TAB ====================
                    array(
                        'title' => __('Sale Badge', 'shopglut'),
                        'icon' => 'fas fa-tags',
                        'dependency' => array('badge_type', 'any', 'sale', true),
                        'fields' => array(

                            array(
                                'type' => 'subheading',
                                'content' => __('Sale Badge Conditions', 'shopglut'),
                            ),

                            array(
                                'id' => 'sale_badge_text',
                                'type' => 'text',
                                'title' => __('Badge Text', 'shopglut'),
                                'desc' => __('Text to display on the sale badge', 'shopglut'),
                                'default' => __('SALE!', 'shopglut'),
                            ),

                            array(
                                'id' => 'sale_condition',
                                'type' => 'select',
                                'title' => __('Sale Condition', 'shopglut'),
                                'desc' => __('When should this sale badge appear?', 'shopglut'),
                                'options' => array(
                                    'any_sale' => __('Any Sale Product', 'shopglut'),
                                    'percentage_sale' => __('Percentage Discount Only', 'shopglut'),
                                    'fixed_sale' => __('Fixed Amount Discount Only', 'shopglut'),
                                    'min_discount' => __('Minimum Discount Amount', 'shopglut'),
                                ),
                                'default' => 'any_sale',
                            ),

                            array(
                                'id' => 'min_discount_percentage',
                                'type' => 'slider',
                                'title' => __('Minimum Discount Percentage', 'shopglut'),
                                'desc' => __('Show badge only when discount is at least this percentage', 'shopglut'),
                                'unit' => '%',
                                'min' => 1,
                                'max' => 100,
                                'step' => 1,
                                'default' => 10,
                                'dependency' => array('sale_condition', 'any', 'min_discount|percentage_sale'),
                            ),

                            array(
                                'id' => 'min_discount_amount',
                                'type' => 'number',
                                'title' => __('Minimum Discount Amount', 'shopglut'),
                                'desc' => __('Show badge only when discount is at least this amount', 'shopglut'),
                                'default' => 5,
                                'attributes' => array(
                                    'min' => 0,
                                    'step' => '0.01',
                                ),
                                'dependency' => array('sale_condition', 'any', 'min_discount|fixed_sale'),
                            ),

                            // Sale Badge - Display & Position
                            array(
                                'type' => 'subheading',
                                'content' => __('Display & Position', 'shopglut'),
                            ),

                            array(
                                'id' => 'sale_display_area',
                                'type' => 'select',
                                'title' => __('Badge Display Area', 'shopglut'),
                                'desc' => __('Select which area of the product to display the badge', 'shopglut'),
                                'options' => array(
                                    'product_image' => __('On Product Image', 'shopglut'),
                                    'before_product_title' => __('Before Product Title', 'shopglut'),
                                ),
                                'default' => 'product_image',
                            ),

                            array(
                                'id' => 'sale_position_image',
                                'type' => 'select',
                                'title' => __('Badge Position on Image', 'shopglut'),
                                'desc' => __('Select the position of the badge on the product image', 'shopglut'),
                                'options' => array(
                                    'top-left' => __('Top Left', 'shopglut'),
                                    'top-center' => __('Top Center', 'shopglut'),
                                    'top-right' => __('Top Right', 'shopglut'),
                                    'bottom-left' => __('Bottom Left', 'shopglut'),
                                    'bottom-center' => __('Bottom Center', 'shopglut'),
                                    'bottom-right' => __('Bottom Right', 'shopglut'),
                                ),
                                'default' => 'top-left',
                                'dependency' => array('sale_display_area', '==', 'product_image'),
                            ),

                            array(
                                'id' => 'sale_position_inline',
                                'type' => 'select',
                                'title' => __('Badge Position', 'shopglut'),
                                'desc' => __('Select the alignment of the badge', 'shopglut'),
                                'options' => array(
                                    'left' => __('Left Aligned', 'shopglut'),
                                    'center' => __('Center Aligned', 'shopglut'),
                                    'right' => __('Right Aligned', 'shopglut'),
                                ),
                                'default' => 'left',
                                'dependency' => array('sale_display_area', '==', 'before_product_title'),
                            ),

                            // Sale Badge - Style
                            array(
                                'type' => 'subheading',
                                'content' => __('Badge Style', 'shopglut'),
                            ),

                            array(
                                'id' => 'sale_badge_text_color',
                                'type' => 'color',
                                'title' => __('Text Color', 'shopglut'),
                                'default' => '#ffffff',
                            ),

                            array(
                                'id' => 'sale_badge_font_size',
                                'type' => 'slider',
                                'title' => __('Font Size', 'shopglut'),
                                'unit' => 'px',
                                'min' => 8,
                                'max' => 40,
                                'step' => 1,
                                'default' => 12,
                            ),

                            array(
                                'id' => 'sale_badge_font_weight',
                                'type' => 'select',
                                'title' => __('Font Weight', 'shopglut'),
                                'options' => array(
                                    '400' => __('Normal', 'shopglut'),
                                    '600' => __('Semi Bold', 'shopglut'),
                                    '700' => __('Bold', 'shopglut'),
                                    '800' => __('Extra Bold', 'shopglut'),
                                ),
                                'default' => '700',
                            ),

                            array(
                                'id' => 'sale_badge_text_transform',
                                'type' => 'select',
                                'title' => __('Text Transform', 'shopglut'),
                                'options' => array(
                                    'none' => __('None', 'shopglut'),
                                    'uppercase' => __('Uppercase', 'shopglut'),
                                    'lowercase' => __('Lowercase', 'shopglut'),
                                    'capitalize' => __('Capitalize', 'shopglut'),
                                ),
                                'default' => 'uppercase',
                            ),

                            array(
                                'id' => 'sale_badge_bg_color',
                                'type' => 'color',
                                'title' => __('Background Color', 'shopglut'),
                                'default' => '#ff0000',
                            ),

                            array(
                                'id' => 'sale_badge_enable_gradient',
                                'type' => 'switcher',
                                'title' => __('Enable Gradient', 'shopglut'),
                                'default' => false,
                            ),

                            array(
                                'id' => 'sale_badge_gradient_color',
                                'type' => 'color',
                                'title' => __('Gradient Color', 'shopglut'),
                                'default' => '#cc0000',
                                'dependency' => array('sale_badge_enable_gradient', '==', true),
                            ),

                            array(
                                'id' => 'sale_badge_padding_v',
                                'type' => 'slider',
                                'title' => __('Padding Vertical', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 30,
                                'step' => 1,
                                'default' => 5,
                            ),

                            array(
                                'id' => 'sale_badge_padding_h',
                                'type' => 'slider',
                                'title' => __('Padding Horizontal', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 10,
                            ),

                            array(
                                'id' => 'sale_badge_border_radius',
                                'type' => 'slider',
                                'title' => __('Border Radius', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 3,
                            ),

                            array(
                                'id' => 'sale_badge_border_width',
                                'type' => 'slider',
                                'title' => __('Border Width', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 10,
                                'step' => 1,
                                'default' => 0,
                            ),

                            array(
                                'id' => 'sale_badge_border_color',
                                'type' => 'color',
                                'title' => __('Border Color', 'shopglut'),
                                'default' => '#000000',
                            ),

                            array(
                                'id' => 'sale_badge_enable_shadow',
                                'type' => 'switcher',
                                'title' => __('Enable Shadow', 'shopglut'),
                                'default' => true,
                            ),

                            array(
                                'id' => 'sale_badge_shadow_color',
                                'type' => 'color',
                                'title' => __('Shadow Color', 'shopglut'),
                                'default' => 'rgba(0, 0, 0, 0.2)',
                                'dependency' => array('sale_badge_enable_shadow', '==', true),
                            ),

                            array(
                                'id' => 'sale_badge_shadow_blur',
                                'type' => 'slider',
                                'title' => __('Shadow Blur', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 20,
                                'step' => 1,
                                'default' => 4,
                                'dependency' => array('sale_badge_enable_shadow', '==', true),
                            ),
                        ),
                    ),

                    // ==================== NEW PRODUCT BADGE TAB ====================
                    array(
                        'title' => __('New Badge', 'shopglut'),
                        'icon' => 'fas fa-star',
                        'dependency' => array('badge_type', 'any', 'new', true),
                        'fields' => array(

                            array(
                                'type' => 'subheading',
                                'content' => __('New Product Badge Conditions', 'shopglut'),
                            ),

                            array(
                                'id' => 'new_badge_text',
                                'type' => 'text',
                                'title' => __('Badge Text', 'shopglut'),
                                'desc' => __('Text to display on the new product badge', 'shopglut'),
                                'default' => __('NEW!', 'shopglut'),
                            ),

                            array(
                                'id' => 'new_product_days',
                                'type' => 'slider',
                                'title' => __('New Product Period', 'shopglut'),
                                'desc' => __('Show badge for products added within this many days', 'shopglut'),
                                'attributes' => array(
                                    'data-unit' => __('days', 'shopglut'),
                                ),
                                'min' => 1,
                                'max' => 365,
                                'step' => 1,
                                'default' => 7,
                            ),

                            // New Badge - Display & Position
                            array(
                                'type' => 'subheading',
                                'content' => __('Display & Position', 'shopglut'),
                            ),

                            array(
                                'id' => 'new_display_area',
                                'type' => 'select',
                                'title' => __('Badge Display Area', 'shopglut'),
                                'desc' => __('Select which area of the product to display the badge', 'shopglut'),
                                'options' => array(
                                    'product_image' => __('On Product Image', 'shopglut'),
                                    'before_product_title' => __('Before Product Title', 'shopglut'),
                                ),
                                'default' => 'product_image',
                            ),

                            array(
                                'id' => 'new_position_image',
                                'type' => 'select',
                                'title' => __('Badge Position on Image', 'shopglut'),
                                'desc' => __('Select the position of the badge on the product image', 'shopglut'),
                                'options' => array(
                                    'top-left' => __('Top Left', 'shopglut'),
                                    'top-center' => __('Top Center', 'shopglut'),
                                    'top-right' => __('Top Right', 'shopglut'),
                                    'bottom-left' => __('Bottom Left', 'shopglut'),
                                    'bottom-center' => __('Bottom Center', 'shopglut'),
                                    'bottom-right' => __('Bottom Right', 'shopglut'),
                                ),
                                'default' => 'top-left',
                                'dependency' => array('new_display_area', '==', 'product_image'),
                            ),

                            array(
                                'id' => 'new_position_inline',
                                'type' => 'select',
                                'title' => __('Badge Position', 'shopglut'),
                                'desc' => __('Select the alignment of the badge', 'shopglut'),
                                'options' => array(
                                    'left' => __('Left Aligned', 'shopglut'),
                                    'center' => __('Center Aligned', 'shopglut'),
                                    'right' => __('Right Aligned', 'shopglut'),
                                ),
                                'default' => 'left',
                                'dependency' => array('new_display_area', '==', 'before_product_title'),
                            ),

                            // New Badge - Style
                            array(
                                'type' => 'subheading',
                                'content' => __('Badge Style', 'shopglut'),
                            ),

                            array(
                                'id' => 'new_badge_text_color',
                                'type' => 'color',
                                'title' => __('Text Color', 'shopglut'),
                                'default' => '#ffffff',
                            ),

                            array(
                                'id' => 'new_badge_font_size',
                                'type' => 'slider',
                                'title' => __('Font Size', 'shopglut'),
                                'unit' => 'px',
                                'min' => 8,
                                'max' => 40,
                                'step' => 1,
                                'default' => 12,
                            ),

                            array(
                                'id' => 'new_badge_font_weight',
                                'type' => 'select',
                                'title' => __('Font Weight', 'shopglut'),
                                'options' => array(
                                    '400' => __('Normal', 'shopglut'),
                                    '600' => __('Semi Bold', 'shopglut'),
                                    '700' => __('Bold', 'shopglut'),
                                    '800' => __('Extra Bold', 'shopglut'),
                                ),
                                'default' => '700',
                            ),

                            array(
                                'id' => 'new_badge_text_transform',
                                'type' => 'select',
                                'title' => __('Text Transform', 'shopglut'),
                                'options' => array(
                                    'none' => __('None', 'shopglut'),
                                    'uppercase' => __('Uppercase', 'shopglut'),
                                    'lowercase' => __('Lowercase', 'shopglut'),
                                    'capitalize' => __('Capitalize', 'shopglut'),
                                ),
                                'default' => 'uppercase',
                            ),

                            array(
                                'id' => 'new_badge_bg_color',
                                'type' => 'color',
                                'title' => __('Background Color', 'shopglut'),
                                'default' => '#008000',
                            ),

                            array(
                                'id' => 'new_badge_enable_gradient',
                                'type' => 'switcher',
                                'title' => __('Enable Gradient', 'shopglut'),
                                'default' => false,
                            ),

                            array(
                                'id' => 'new_badge_gradient_color',
                                'type' => 'color',
                                'title' => __('Gradient Color', 'shopglut'),
                                'default' => '#006400',
                                'dependency' => array('new_badge_enable_gradient', '==', true),
                            ),

                            array(
                                'id' => 'new_badge_padding_v',
                                'type' => 'slider',
                                'title' => __('Padding Vertical', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 30,
                                'step' => 1,
                                'default' => 5,
                            ),

                            array(
                                'id' => 'new_badge_padding_h',
                                'type' => 'slider',
                                'title' => __('Padding Horizontal', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 10,
                            ),

                            array(
                                'id' => 'new_badge_border_radius',
                                'type' => 'slider',
                                'title' => __('Border Radius', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 3,
                            ),

                            array(
                                'id' => 'new_badge_border_width',
                                'type' => 'slider',
                                'title' => __('Border Width', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 10,
                                'step' => 1,
                                'default' => 0,
                            ),

                            array(
                                'id' => 'new_badge_border_color',
                                'type' => 'color',
                                'title' => __('Border Color', 'shopglut'),
                                'default' => '#000000',
                            ),

                            array(
                                'id' => 'new_badge_enable_shadow',
                                'type' => 'switcher',
                                'title' => __('Enable Shadow', 'shopglut'),
                                'default' => true,
                            ),

                            array(
                                'id' => 'new_badge_shadow_color',
                                'type' => 'color',
                                'title' => __('Shadow Color', 'shopglut'),
                                'default' => 'rgba(0, 0, 0, 0.2)',
                                'dependency' => array('new_badge_enable_shadow', '==', true),
                            ),

                            array(
                                'id' => 'new_badge_shadow_blur',
                                'type' => 'slider',
                                'title' => __('Shadow Blur', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 20,
                                'step' => 1,
                                'default' => 4,
                                'dependency' => array('new_badge_enable_shadow', '==', true),
                            ),
                        ),
                    ),

                    // ==================== OUT OF STOCK BADGE TAB ====================
                    array(
                        'title' => __('Out of Stock', 'shopglut'),
                        'icon' => 'fas fa-times-circle',
                        'dependency' => array('badge_type', 'any', 'out_of_stock', true),
                        'fields' => array(

                            array(
                                'type' => 'subheading',
                                'content' => __('Out of Stock Badge Conditions', 'shopglut'),
                            ),

                            array(
                                'id' => 'out_of_stock_badge_text',
                                'type' => 'text',
                                'title' => __('Badge Text', 'shopglut'),
                                'desc' => __('Text to display on the out of stock badge', 'shopglut'),
                                'default' => __('OUT OF STOCK', 'shopglut'),
                            ),

                            array(
                                'id' => 'out_of_stock_types',
                                'type' => 'select',
                                'title' => __('Product Types', 'shopglut'),
                                'desc' => __('Show out of stock badge for which product types?', 'shopglut'),
                                'options' => array(
                                    'all' => __('All Product Types', 'shopglut'),
                                    'simple_only' => __('Simple Products Only', 'shopglut'),
                                    'variable_only' => __('Variable Products Only', 'shopglut'),
                                ),
                                'default' => 'all',
                            ),

                            // Out of Stock Badge - Display & Position
                            array(
                                'type' => 'subheading',
                                'content' => __('Display & Position', 'shopglut'),
                            ),

                            array(
                                'id' => 'out_of_stock_display_area',
                                'type' => 'select',
                                'title' => __('Badge Display Area', 'shopglut'),
                                'desc' => __('Select which area of the product to display the badge', 'shopglut'),
                                'options' => array(
                                    'product_image' => __('On Product Image', 'shopglut'),
                                    'before_product_title' => __('Before Product Title', 'shopglut'),
                                ),
                                'default' => 'product_image',
                            ),

                            array(
                                'id' => 'out_of_stock_position_image',
                                'type' => 'select',
                                'title' => __('Badge Position on Image', 'shopglut'),
                                'desc' => __('Select the position of the badge on the product image', 'shopglut'),
                                'options' => array(
                                    'top-left' => __('Top Left', 'shopglut'),
                                    'top-center' => __('Top Center', 'shopglut'),
                                    'top-right' => __('Top Right', 'shopglut'),
                                    'bottom-left' => __('Bottom Left', 'shopglut'),
                                    'bottom-center' => __('Bottom Center', 'shopglut'),
                                    'bottom-right' => __('Bottom Right', 'shopglut'),
                                ),
                                'default' => 'top-left',
                                'dependency' => array('out_of_stock_display_area', '==', 'product_image'),
                            ),

                            array(
                                'id' => 'out_of_stock_position_inline',
                                'type' => 'select',
                                'title' => __('Badge Position', 'shopglut'),
                                'desc' => __('Select the alignment of the badge', 'shopglut'),
                                'options' => array(
                                    'left' => __('Left Aligned', 'shopglut'),
                                    'center' => __('Center Aligned', 'shopglut'),
                                    'right' => __('Right Aligned', 'shopglut'),
                                ),
                                'default' => 'left',
                                'dependency' => array('out_of_stock_display_area', '==', 'before_product_title'),
                            ),

                            // Out of Stock Badge - Style
                            array(
                                'type' => 'subheading',
                                'content' => __('Badge Style', 'shopglut'),
                            ),

                            array(
                                'id' => 'out_of_stock_badge_text_color',
                                'type' => 'color',
                                'title' => __('Text Color', 'shopglut'),
                                'default' => '#ffffff',
                            ),

                            array(
                                'id' => 'out_of_stock_badge_font_size',
                                'type' => 'slider',
                                'title' => __('Font Size', 'shopglut'),
                                'unit' => 'px',
                                'min' => 8,
                                'max' => 40,
                                'step' => 1,
                                'default' => 12,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_font_weight',
                                'type' => 'select',
                                'title' => __('Font Weight', 'shopglut'),
                                'options' => array(
                                    '400' => __('Normal', 'shopglut'),
                                    '600' => __('Semi Bold', 'shopglut'),
                                    '700' => __('Bold', 'shopglut'),
                                    '800' => __('Extra Bold', 'shopglut'),
                                ),
                                'default' => '700',
                            ),

                            array(
                                'id' => 'out_of_stock_badge_text_transform',
                                'type' => 'select',
                                'title' => __('Text Transform', 'shopglut'),
                                'options' => array(
                                    'none' => __('None', 'shopglut'),
                                    'uppercase' => __('Uppercase', 'shopglut'),
                                    'lowercase' => __('Lowercase', 'shopglut'),
                                    'capitalize' => __('Capitalize', 'shopglut'),
                                ),
                                'default' => 'uppercase',
                            ),

                            array(
                                'id' => 'out_of_stock_badge_bg_color',
                                'type' => 'color',
                                'title' => __('Background Color', 'shopglut'),
                                'default' => '#666666',
                            ),

                            array(
                                'id' => 'out_of_stock_badge_enable_gradient',
                                'type' => 'switcher',
                                'title' => __('Enable Gradient', 'shopglut'),
                                'default' => false,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_gradient_color',
                                'type' => 'color',
                                'title' => __('Gradient Color', 'shopglut'),
                                'default' => '#444444',
                                'dependency' => array('out_of_stock_badge_enable_gradient', '==', true),
                            ),

                            array(
                                'id' => 'out_of_stock_badge_padding_v',
                                'type' => 'slider',
                                'title' => __('Padding Vertical', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 30,
                                'step' => 1,
                                'default' => 5,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_padding_h',
                                'type' => 'slider',
                                'title' => __('Padding Horizontal', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 10,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_border_radius',
                                'type' => 'slider',
                                'title' => __('Border Radius', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 50,
                                'step' => 1,
                                'default' => 3,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_border_width',
                                'type' => 'slider',
                                'title' => __('Border Width', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 10,
                                'step' => 1,
                                'default' => 0,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_border_color',
                                'type' => 'color',
                                'title' => __('Border Color', 'shopglut'),
                                'default' => '#000000',
                            ),

                            array(
                                'id' => 'out_of_stock_badge_enable_shadow',
                                'type' => 'switcher',
                                'title' => __('Enable Shadow', 'shopglut'),
                                'default' => true,
                            ),

                            array(
                                'id' => 'out_of_stock_badge_shadow_color',
                                'type' => 'color',
                                'title' => __('Shadow Color', 'shopglut'),
                                'default' => 'rgba(0, 0, 0, 0.2)',
                                'dependency' => array('out_of_stock_badge_enable_shadow', '==', true),
                            ),

                            array(
                                'id' => 'out_of_stock_badge_shadow_blur',
                                'type' => 'slider',
                                'title' => __('Shadow Blur', 'shopglut'),
                                'unit' => 'px',
                                'min' => 0,
                                'max' => 20,
                                'step' => 1,
                                'default' => 4,
                                'dependency' => array('out_of_stock_badge_enable_shadow', '==', true),
                            ),
                        ),
                    ),

                ),
            ),
        ),
    )
);
