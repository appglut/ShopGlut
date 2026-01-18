<?php
/**
 * Product Swatches Template2 Specific Settings
 *
 * This file contains settings specifically designed for Template2 Product Swatches layout.
 * Template2 features:
 * - Button grid display
 * - Per-term styling support
 * - Note: Price and Clear Button settings are now global
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_product_swatches_STYLING = "shopg_product_swatches_settings_template2";


// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_product_swatches_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'product_swatches',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_product_swatches_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Product Swatches Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_product_swatches_STYLING,
    array(
        'title' => esc_html__('Template2 Product Swatches Settings', 'shopglut'),
        'post_type' => 'product_swatches',
        'context' => 'side',
    )
);


// Create fields array - essential settings used by the markup
$all_fields1 = array(

    // ==================== ATTRIBUTE TERMS CUSTOMIZATION ====================
    array(
        'id' => 'per_term_styling',
        'type' => 'term_styling',
    ),

    // ==================== WOOCOMMERCE INTEGRATION ====================
    array(
        'id' => 'product-swatches-settings',
        'type' => 'fieldset',
        'title' => __('WooCommerce Integration', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'enable_variation_overwrite',
                'type' => 'switcher',
                'title' => __('Overwrite WooCommerce Variations', 'shopglut'),
                'default' => true,
            ),
        ),
    ),

    // ==================== LAYOUT SETTINGS ====================
    array(
        'id' => 'layout_settings_section',
        'type' => 'fieldset',
        'title' => __('Layout Settings', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'swatch_columns',
                'type' => 'slider',
                'title' => __('Number of Columns', 'shopglut'),
                'desc' => __('Number of buttons per row', 'shopglut'),
                'unit' => '',
                'min' => 2,
                'max' => 6,
                'step' => 1,
                'default' => 3,
            ),
            array(
                'id' => 'swatch_gap',
                'type' => 'slider',
                'title' => __('Gap Between Buttons', 'shopglut'),
                'unit' => 'px',
                'min' => 4,
                'max' => 20,
                'step' => 1,
                'default' => 10,
            ),
            array(
                'id' => 'swatch_container_margin_bottom',
                'type' => 'slider',
                'title' => __('Buttons Container Bottom Margin', 'shopglut'),
                'unit' => 'px',
                'min' => 0,
                'max' => 40,
                'step' => 2,
                'default' => 20,
            ),
        ),
    ),

    // ==================== ATTRIBUTE LABEL SETTINGS ====================
    array(
        'id' => 'attribute_label_section',
        'type' => 'fieldset',
        'title' => __('Attribute Label', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'attribute_label_position',
                'type' => 'select',
                'title' => __('Label Position', 'shopglut'),
                'options' => array(
                    'inline' => __('Same Line (Inline)', 'shopglut'),
                    'stacked' => __('Above (Stacked)', 'shopglut'),
                ),
                'default' => 'inline',
            ),
            array(
                'id' => 'attribute_label_color',
                'type' => 'color',
                'title' => __('Label Color', 'shopglut'),
                'default' => '#2d3748',
            ),
            array(
                'id' => 'attribute_label_font_size',
                'type' => 'slider',
                'title' => __('Label Font Size', 'shopglut'),
                'unit' => 'px',
                'min' => 12,
                'max' => 20,
                'step' => 1,
                'default' => 16,
            ),
            array(
                'id' => 'attribute_label_font_weight',
                'type' => 'select',
                'title' => __('Label Font Weight', 'shopglut'),
                'options' => array(
                    '400' => __('Normal', 'shopglut'),
                    '500' => __('Medium', 'shopglut'),
                    '600' => __('Semi Bold', 'shopglut'),
                    '700' => __('Bold', 'shopglut'),
                ),
                'default' => '600',
            ),
            array(
                'id' => 'attribute_label_margin_bottom',
                'type' => 'slider',
                'title' => __('Label Bottom Margin', 'shopglut'),
                'unit' => 'px',
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'default' => 16,
            ),
        ),
    ),

    // ==================== BUTTON DEFAULT STATE ====================
    array(
        'id' => 'button_default_section',
        'type' => 'fieldset',
        'title' => __('Button Default State', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'button_default_background',
                'type' => 'color',
                'title' => __('Background Color', 'shopglut'),
                'default' => '#ffffff',
            ),
            array(
                'id' => 'button_default_text_color',
                'type' => 'color',
                'title' => __('Text Color', 'shopglut'),
                'default' => '#2d3748',
            ),
            array(
                'id' => 'button_default_border_color',
                'type' => 'color',
                'title' => __('Border Color', 'shopglut'),
                'default' => '#dddddd',
            ),
            array(
                'id' => 'button_default_border_width',
                'type' => 'slider',
                'title' => __('Border Width', 'shopglut'),
                'unit' => 'px',
                'min' => 0,
                'max' => 5,
                'step' => 1,
                'default' => 2,
            ),
            array(
                'id' => 'button_default_border_radius',
                'type' => 'slider',
                'title' => __('Border Radius', 'shopglut'),
                'unit' => 'px',
                'min' => 0,
                'max' => 30,
                'step' => 1,
                'default' => 8,
            ),
            array(
                'id' => 'button_default_padding_x',
                'type' => 'slider',
                'title' => __('Horizontal Padding', 'shopglut'),
                'unit' => 'px',
                'min' => 4,
                'max' => 30,
                'step' => 1,
                'default' => 12,
            ),
            array(
                'id' => 'button_default_padding_y',
                'type' => 'slider',
                'title' => __('Vertical Padding', 'shopglut'),
                'unit' => 'px',
                'min' => 4,
                'max' => 30,
                'step' => 1,
                'default' => 12,
            ),
            array(
                'id' => 'button_default_font_size',
                'type' => 'slider',
                'title' => __('Font Size', 'shopglut'),
                'unit' => 'px',
                'min' => 10,
                'max' => 24,
                'step' => 1,
                'default' => 14,
            ),
            array(
                'id' => 'button_default_font_weight',
                'type' => 'select',
                'title' => __('Font Weight', 'shopglut'),
                'options' => array(
                    '300' => __('Light', 'shopglut'),
                    '400' => __('Normal', 'shopglut'),
                    '500' => __('Medium', 'shopglut'),
                    '600' => __('Semi Bold', 'shopglut'),
                    '700' => __('Bold', 'shopglut'),
                ),
                'default' => '500',
            ),
            array(
                'id' => 'button_default_min_width',
                'type' => 'text',
                'title' => __('Min Width', 'shopglut'),
                'default' => 'auto',
                'desc' => __('e.g. auto, 50px, 100%', 'shopglut'),
            ),
            array(
                'id' => 'button_default_min_height',
                'type' => 'text',
                'title' => __('Min Height', 'shopglut'),
                'default' => 'auto',
                'desc' => __('e.g. auto, 40px, 50px', 'shopglut'),
            ),
        ),
    ),

    // ==================== BUTTON HOVER STATE ====================
    array(
        'id' => 'button_hover_section',
        'type' => 'fieldset',
        'title' => __('Button Hover State', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'button_hover_background',
                'type' => 'color',
                'title' => __('Background Color', 'shopglut'),
                'default' => '#ffffff',
            ),
            array(
                'id' => 'button_hover_text_color',
                'type' => 'color',
                'title' => __('Text Color', 'shopglut'),
                'default' => '#667eea',
            ),
            array(
                'id' => 'button_hover_border_color',
                'type' => 'color',
                'title' => __('Border Color', 'shopglut'),
                'default' => '#667eea',
            ),
        ),
    ),

    // ==================== BUTTON ACTIVE/SELECTED STATE ====================
    array(
        'id' => 'button_active_section',
        'type' => 'fieldset',
        'title' => __('Button Active/Selected State', 'shopglut'),
        'fields' => array(
            array(
                'id' => 'button_active_background',
                'type' => 'color',
                'title' => __('Background Color', 'shopglut'),
                'default' => '#667eea',
            ),
            array(
                'id' => 'button_active_text_color',
                'type' => 'color',
                'title' => __('Text Color', 'shopglut'),
                'default' => '#ffffff',
            ),
            array(
                'id' => 'button_active_border_color',
                'type' => 'color',
                'title' => __('Border Color', 'shopglut'),
                'default' => '#667eea',
            ),
        ),
    ),

    // ==================== VARIATION SETTINGS ====================
    array(
        'id' => 'variation_settings_section',
        'type' => 'fieldset',
        'title' => __('Variation Settings (Clear Button & Price)', 'shopglut'),
        'fields' => array(
            // Actions Row Settings
            array(
                'id' => 'actions_row_section',
                'type' => 'fieldset',
                'title' => __('Actions Row Layout', 'shopglut'),
                'fields' => array(
                    array(
                        'id' => 'actions_position',
                        'type' => 'select',
                        'title' => __('Clear Button & Price Position', 'shopglut'),
                        'desc' => __('Choose where to display the clear button and price relative to the last attribute', 'shopglut'),
                        'options' => array(
                            'same_line' => __('Same Line - Display inline with the last attribute', 'shopglut'),
                            'new_line' => __('New Line - Display beneath the last attribute', 'shopglut'),
                        ),
                        'default' => 'new_line',
                    ),
                    array(
                        'id' => 'actions_row_margin_top',
                        'type' => 'slider',
                        'title' => __('Top Margin', 'shopglut'),
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 40,
                        'step' => 1,
                        'default' => 20,
                        'dependency' => array('actions_position', '==', 'new_line'),
                    ),
                    array(
                        'id' => 'actions_row_gap',
                        'type' => 'slider',
                        'title' => __('Gap Between Elements', 'shopglut'),
                        'unit' => 'px',
                        'min' => 5,
                        'max' => 30,
                        'step' => 1,
                        'default' => 15,
                    ),
                ),
            ),

            // Clear Button Settings
            array(
                'id' => 'swatch_clear_button_section',
                'type' => 'fieldset',
                'title' => __('Clear Button', 'shopglut'),
                'fields' => array(
                    array(
                        'id' => 'enable_clear_button',
                        'type' => 'switcher',
                        'title' => __('Enable Clear Button', 'shopglut'),
                        'default' => true,
                    ),
                    array(
                        'id' => 'clear_button_text',
                        'type' => 'text',
                        'title' => __('Button Text', 'shopglut'),
                        'default' => 'Clear',
                    ),
                    array(
                        'id' => 'clear_button_color',
                        'type' => 'color',
                        'title' => __('Text Color', 'shopglut'),
                        'default' => '#667eea',
                    ),
                    array(
                        'id' => 'clear_button_font_size',
                        'type' => 'slider',
                        'title' => __('Font Size', 'shopglut'),
                        'unit' => 'px',
                        'min' => 10,
                        'max' => 20,
                        'step' => 1,
                        'default' => 14,
                    ),
                    array(
                        'id' => 'clear_button_font_weight',
                        'type' => 'select',
                        'title' => __('Font Weight', 'shopglut'),
                        'options' => array(
                            '300' => __('Light', 'shopglut'),
                            '400' => __('Normal', 'shopglut'),
                            '500' => __('Medium', 'shopglut'),
                            '600' => __('Semi Bold', 'shopglut'),
                            '700' => __('Bold', 'shopglut'),
                        ),
                        'default' => '500',
                    ),
                    array(
                        'id' => 'clear_button_padding',
                        'type' => 'spacing',
                        'title' => __('Padding', 'shopglut'),
                        'default' => array(
                            'top' => 6,
                            'right' => 12,
                            'bottom' => 6,
                            'left' => 12,
                            'unit' => 'px',
                        ),
                    ),
                    array(
                        'id' => 'clear_button_border_radius',
                        'type' => 'slider',
                        'title' => __('Border Radius', 'shopglut'),
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default' => 6,
                    ),
                    array(
                        'id' => 'clear_button_margin_left',
                        'type' => 'slider',
                        'title' => __('Left Margin', 'shopglut'),
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                        'default' => 10,
                    ),
                ),
            ),

            // Price Display Settings
            array(
                'id' => 'swatch_price_display_section',
                'type' => 'fieldset',
                'title' => __('Price Display', 'shopglut'),
                'fields' => array(
                    array(
                        'id' => 'enable_variation_price',
                        'type' => 'switcher',
                        'title' => __('Show Variation Price', 'shopglut'),
                        'default' => true,
                    ),
                    array(
                        'id' => 'price_display_position',
                        'type' => 'select',
                        'title' => __('Price Position', 'shopglut'),
                        'options' => array(
                            'after_label' => __('After Attribute Label', 'shopglut'),
                            'before_swatches' => __('Before Swatches', 'shopglut'),
                            'after_swatches' => __('After Swatches', 'shopglut'),
                        ),
                        'default' => 'after_swatches',
                    ),
                    array(
                        'id' => 'price_color',
                        'type' => 'color',
                        'title' => __('Price Color', 'shopglut'),
                        'default' => '#667eea',
                    ),
                    array(
                        'id' => 'price_font_size',
                        'type' => 'slider',
                        'title' => __('Font Size', 'shopglut'),
                        'unit' => 'px',
                        'min' => 12,
                        'max' => 32,
                        'step' => 1,
                        'default' => 20,
                    ),
                    array(
                        'id' => 'price_font_weight',
                        'type' => 'select',
                        'title' => __('Font Weight', 'shopglut'),
                        'options' => array(
                            '400' => __('Normal', 'shopglut'),
                            '500' => __('Medium', 'shopglut'),
                            '600' => __('Semi Bold', 'shopglut'),
                            '700' => __('Bold', 'shopglut'),
                            '800' => __('Extra Bold', 'shopglut'),
                        ),
                        'default' => '700',
                    ),
                    array(
                        'id' => 'price_margin_top',
                        'type' => 'slider',
                        'title' => __('Top Margin', 'shopglut'),
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                        'default' => 8,
                    ),
                ),
            ),
        ),
    ),
);


// Create the section with all fields
AGSHOPGLUT::createSection(
    $SHOPG_product_swatches_STYLING,
    array(
        'fields' => $all_fields1
    )
);
