<?php
/**
 * Product Swatches Template1 Specific Settings
 *
 * Template1 is specifically for DROPDOWN swatches with:
 * - Dropdown select for variations
 * - Attribute label (swatches label)
 * - Note: Price and Clear Button settings are now global
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_product_swatches_STYLING = "shopg_product_swatches_settings_template1";


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
        'title' => esc_html__('Template1 Appearance Settings', 'shopglut'),
        'post_type' => 'product_swatches',
        'context' => 'side',
    )
);


// Create fields array - only dropdown-related settings
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
                'desc' => __('Replace default WooCommerce variation dropdown with custom styled dropdown', 'shopglut'),
                'default' => true,
            ),
        ),
    ),

    array(
        'id' => 'product-swatches-settings',
        'type' => 'tabbed',
        'title' => __('Appearance Settings', 'shopglut'),
        'tabs' => array(

                    // ==================== VISIBILITY ====================
                    array(
                        'title' => __('Visibility', 'shopglut'),
                        'icon' => 'fas fa-eye',
                        'fields' => array(
                            array(
                                'id' => 'show_dropdown',
                                'type' => 'switcher',
                                'title' => __('Show Dropdown', 'shopglut'),
                                'desc' => __('Enable to display the dropdown select on frontend', 'shopglut'),
                                'default' => true,
                            ),
                            array(
                                'id' => 'show_label',
                                'type' => 'switcher',
                                'title' => __('Show Label', 'shopglut'),
                                'desc' => __('Enable to display the attribute label on frontend', 'shopglut'),
                                'default' => true,
                            ),
                        ),
                    ),

                    // ==================== DROPDOWN STYLE ====================
                    array(
                        'title' => __('Dropdown', 'shopglut'),
                        'icon' => 'fas fa-list',
                        'fields' => array(
                            // Container
                            array(
                                'id' => 'swatch_dropdown_container_section',
                                'type' => 'fieldset',
                                'title' => __('Container', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_dropdown_background',
                                        'type' => 'color',
                                        'title' => __('Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_border_color',
                                        'type' => 'color',
                                        'title' => __('Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_border_width',
                                        'type' => 'slider',
                                        'title' => __('Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 3,
                                        'step' => 1,
                                        'default' => 1,
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 12,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_padding',
                                        'type' => 'dimensions',
                                        'title' => __('Padding', 'shopglut'),
                                        'units' => array('px'),
                                        'default' => array(
                                            'top' => '10',
                                            'right' => '14',
                                            'bottom' => '10',
                                            'left' => '14',
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_width',
                                        'type' => 'slider',
                                        'title' => __('Width', 'shopglut'),
                                        'unit' => '%',
                                        'min' => 50,
                                        'max' => 100,
                                        'step' => 5,
                                        'default' => 100,
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_min_height',
                                        'type' => 'slider',
                                        'title' => __('Min Height', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 30,
                                        'max' => 60,
                                        'step' => 1,
                                        'default' => 40,
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_max_width',
                                        'type' => 'slider',
                                        'title' => __('Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 200,
                                        'max' => 600,
                                        'step' => 10,
                                        'default' => 600,
                                        'desc' => __('Set 0 for no limit', 'shopglut'),
                                    ),
                                ),
                            ),
                            // Typography
                            array(
                                'id' => 'swatch_dropdown_typography_section',
                                'type' => 'fieldset',
                                'title' => __('Typography', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_dropdown_text_color',
                                        'type' => 'color',
                                        'title' => __('Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_font_size',
                                        'type' => 'slider',
                                        'title' => __('Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_font_weight',
                                        'type' => 'select',
                                        'title' => __('Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '400',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_line_height',
                                        'type' => 'slider',
                                        'title' => __('Line Height', 'shopglut'),
                                        'unit' => 'em',
                                        'min' => 10,
                                        'max' => 25,
                                        'step' => 1,
                                        'default' => 14,
                                        'desc' => __('Line height as /10 of em (e.g., 14 = 1.4em)', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_placeholder_color',
                                        'type' => 'color',
                                        'title' => __('Placeholder Color', 'shopglut'),
                                        'default' => '#9ca3af',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_font_family',
                                        'type' => 'select',
                                        'title' => __('Font Family', 'shopglut'),
                                        'options' => array(
                                            'inherit' => __('Inherit', 'shopglut'),
                                            'Arial, sans-serif' => __('Arial', 'shopglut'),
                                            'Georgia, serif' => __('Georgia', 'shopglut'),
                                            '"Times New Roman", serif' => __('Times New Roman', 'shopglut'),
                                            '"Courier New", monospace' => __('Courier New', 'shopglut'),
                                            'Verdana, sans-serif' => __('Verdana', 'shopglut'),
                                        ),
                                        'default' => 'inherit',
                                    ),
                                ),
                            ),
                            // States
                            array(
                                'id' => 'swatch_dropdown_states_section',
                                'type' => 'fieldset',
                                'title' => __('States', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_dropdown_focus_border_color',
                                        'type' => 'color',
                                        'title' => __('Focus Border Color', 'shopglut'),
                                        'default' => '#2271b1',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_focus_background',
                                        'type' => 'color',
                                        'title' => __('Focus Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_focus_shadow',
                                        'type' => 'select',
                                        'title' => __('Focus Shadow', 'shopglut'),
                                        'desc' => __('Box shadow effect when dropdown is focused', 'shopglut'),
                                        'options' => array(
                                            'none' => __('None', 'shopglut'),
                                            'small' => __('Small (2px)', 'shopglut'),
                                            'medium' => __('Medium (3px)', 'shopglut'),
                                            'large' => __('Large (4px)', 'shopglut'),
                                        ),
                                        'default' => 'medium',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_hover_border_color',
                                        'type' => 'color',
                                        'title' => __('Hover Border Color', 'shopglut'),
                                        'default' => '#2271b1',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_option_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Option Hover Background', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_option_hover_color',
                                        'type' => 'color',
                                        'title' => __('Option Hover Text Color', 'shopglut'),
                                        'default' => '#1f2937',
                                    ),
                                ),
                            ),
                            // Arrow Icon
                            array(
                                'id' => 'swatch_dropdown_arrow_section',
                                'type' => 'fieldset',
                                'title' => __('Arrow Icon', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_dropdown_arrow_color',
                                        'type' => 'color',
                                        'title' => __('Arrow Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'swatch_dropdown_arrow_size',
                                        'type' => 'slider',
                                        'title' => __('Arrow Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== SWATCHES LABEL ====================
                    array(
                        'title' => __('Label', 'shopglut'),
                        'icon' => 'fas fa-tag',
                        'fields' => array(
                            array(
                                'id' => 'swatch_attribute_label_section',
                                'type' => 'fieldset',
                                'title' => __('Swatches Label', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_attribute_label_text',
                                        'type' => 'text',
                                        'title' => __('Custom Label Text', 'shopglut'),
                                        'default' => '',
                                        'desc' => __('Leave empty to use the default attribute label. Enter custom text to override.', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'swatch_attribute_label_position',
                                        'type' => 'select',
                                        'title' => __('Position', 'shopglut'),
                                        'options' => array(
                                            'none' => __('None', 'shopglut'),
                                            'inline' => __('Inline', 'shopglut'),
                                            'stacked' => __('Stacked (Above)', 'shopglut'),
                                        ),
                                        'default' => 'inline',
                                    ),
                                    array(
                                        'id' => 'swatch_attribute_label_color',
                                        'type' => 'color',
                                        'title' => __('Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'swatch_attribute_label_font_size',
                                        'type' => 'slider',
                                        'title' => __('Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'swatch_attribute_label_font_weight',
                                        'type' => 'select',
                                        'title' => __('Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                    array(
                                        'id' => 'swatch_attribute_label_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                ),
                            ),
                            // Layout
                            array(
                                'id' => 'swatch_layout_section',
                                'type' => 'fieldset',
                                'title' => __('Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'swatch_alignment',
                                        'type' => 'select',
                                        'title' => __('Alignment', 'shopglut'),
                                        'options' => array(
                                            'left' => __('Left', 'shopglut'),
                                            'center' => __('Center', 'shopglut'),
                                            'right' => __('Right', 'shopglut'),
                                        ),
                                        'default' => 'left',
                                    ),
                                    array(
                                        'id' => 'swatch_wrapper_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 16,
                                    ),
                                ),
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
