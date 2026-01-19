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

    // ==================== ATTRIBUTE TERMS CUSTOMIZATION ====================
    array(
        'id' => 'per_term_styling',
        'type' => 'term_styling',
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
);


// Create the section with all fields
AGSHOPGLUT::createSection(
    $SHOPG_product_swatches_STYLING,
    array(
        'fields' => $all_fields1
    )
);
