<?php
/**
 * Product Comparison Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 product comparison.
 * Template1 features:
 * - Comparison button on product pages
 * - Floating comparison bar
 * - Product comparison table
 * - Sticky comparison widget
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_product_comparison_STYLING = "shopg_product_comparison_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_product_comparison_live_preview',
	array(
		'title' => __( 'Preview -  Demo Mode', 'shopglut' ),
		'post_type' => 'product_comparison',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_product_comparison_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Product Comparison Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_product_comparison_STYLING,
    array(
        'title' => esc_html__('Template1 Product Comparison Settings', 'shopglut'),
        'post_type' => 'product_comparison',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_product_comparison_STYLING,
    array(
        'fields' => array(

            // ==================== DISPLAY LOCATION (OUTSIDE TABS) ====================
            array(
                'id' => 'display-locations',
                'type' => 'select_comparison_display',
                'title' => __('Show Comparison Button On', 'shopglut'),
                'desc' => __('Select pages where the comparison button should appear. Each location can only be used by one layout.', 'shopglut'),
                'options' => 'select_comparison_display',
                'multiple' => true,
                'chosen' => true,
                'placeholder' => __('Select pages to show comparison button', 'shopglut'),
            ),

            array(
                'id' => 'product_comparison-page-settings',
                'type' => 'tabbed',
                'title' => __('Template1 Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== BUTTON SETTINGS ====================
                    array(
                        'title' => __('Comparison Button', 'shopglut'),
                        'icon' => 'fas fa-exchange-alt',
                        'fields' => array(

                            // Enable Comparison Button
                            array(
                                'id' => 'enable_comparison_button',
                                'type' => 'switcher',
                                'title' => __('Enable Comparison Button', 'shopglut'),
                                'desc' => __('Show "Add to Compare" button globally', 'shopglut'),
                                'default' => true,
                            ),

                            // Shop Page Position
                            array(
                                'id' => 'shop_page_position',
                                'type' => 'fieldset',
                                'title' => __('Shop Page Button Position', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'shop_button_position',
                                        'type' => 'select',
                                        'title' => __('Button Position on Shop Page', 'shopglut'),
                                        'desc' => __('Select where to display the comparison button on shop/loop pages', 'shopglut'),
                                        'options' => array(
                                            'before_add_to_cart' => __('Before Add to Cart', 'shopglut'),
                                            'after_add_to_cart' => __('After Add to Cart', 'shopglut'),
                                            'before_title' => __('Before Product Title', 'shopglut'),
                                            'after_title' => __('After Product Title', 'shopglut'),
                                            'before_price' => __('Before Price', 'shopglut'),
                                            'after_price' => __('After Price', 'shopglut'),
                                        ),
                                        'default' => 'after_add_to_cart',
                                    ),
                                ),
                            ),

                            // Archive Page Position
                            array(
                                'id' => 'archive_page_position',
                                'type' => 'fieldset',
                                'title' => __('Archive Page Button Position', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'archive_button_position',
                                        'type' => 'select',
                                        'title' => __('Button Position on Archive Pages', 'shopglut'),
                                        'desc' => __('Select where to display the comparison button on category/tag pages', 'shopglut'),
                                        'options' => array(
                                            'before_add_to_cart' => __('Before Add to Cart', 'shopglut'),
                                            'after_add_to_cart' => __('After Add to Cart', 'shopglut'),
                                            'before_title' => __('Before Product Title', 'shopglut'),
                                            'after_title' => __('After Product Title', 'shopglut'),
                                            'before_price' => __('Before Price', 'shopglut'),
                                            'after_price' => __('After Price', 'shopglut'),
                                        ),
                                        'default' => 'after_add_to_cart',
                                    ),
                                ),
                            ),

                            // Product Page Position
                            array(
                                'id' => 'product_page_position',
                                'type' => 'fieldset',
                                'title' => __('Product Page Button Position', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'product_button_position',
                                        'type' => 'select',
                                        'title' => __('Button Position on Single Product Page', 'shopglut'),
                                        'desc' => __('Select where to display the comparison button on single product pages', 'shopglut'),
                                        'options' => array(
                                            'before_add_to_cart' => __('Before Add to Cart Button', 'shopglut'),
                                            'after_add_to_cart' => __('After Add to Cart Button', 'shopglut'),
                                            'before_product_meta' => __('Before Product Meta', 'shopglut'),
                                            'after_product_meta' => __('After Product Meta', 'shopglut'),
                                            'before_product_summary' => __('Before Product Summary', 'shopglut'),
                                            'after_product_summary' => __('After Product Summary', 'shopglut'),
                                        ),
                                        'default' => 'after_add_to_cart',
                                    ),
                                ),
                            ),

                            // Button Text Settings
                            array(
                                'id' => 'button_text_settings',
                                'type' => 'fieldset',
                                'title' => __('Button Text', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'button_text',
                                        'type' => 'text',
                                        'title' => __('Button Text', 'shopglut'),
                                        'desc' => __('Text to display on the compare button', 'shopglut'),
                                        'default' => __('Add to Compare', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'button_added_text',
                                        'type' => 'text',
                                        'title' => __('Button Text (Added State)', 'shopglut'),
                                        'desc' => __('Text when product is already added to comparison', 'shopglut'),
                                        'default' => __('Remove from Compare', 'shopglut'),
                                    ),
                                ),
                            ),

                            // Button Icon Settings
                            array(
                                'id' => 'button_icon_settings',
                                'type' => 'fieldset',
                                'title' => __('Button Icon', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_button_icon',
                                        'type' => 'switcher',
                                        'title' => __('Show Button Icon', 'shopglut'),
                                        'desc' => __('Display icon along with button text', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'button_icon',
                                        'type' => 'icon',
                                        'title' => __('Button Icon', 'shopglut'),
                                        'desc' => __('Choose an icon for the comparison button', 'shopglut'),
                                        'default' => 'fas fa-exchange-alt',
                                        'dependency' => array('show_button_icon', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_icon_position',
                                        'type' => 'select',
                                        'title' => __('Icon Position', 'shopglut'),
                                        'options' => array(
                                            'left' => __('Left', 'shopglut'),
                                            'right' => __('Right', 'shopglut'),
                                        ),
                                        'default' => 'left',
                                        'dependency' => array('show_button_icon', '==', true),
                                    ),
                                ),
                            ),

                            // Button Styling
                            array(
                                'id' => 'button_styling',
                                'type' => 'fieldset',
                                'title' => __('Button Styles', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'button_background_color',
                                        'type' => 'color',
                                        'title' => __('Background Color', 'shopglut'),
                                        'default' => '#3b82f6',
                                    ),
                                    array(
                                        'id' => 'button_text_color',
                                        'type' => 'color',
                                        'title' => __('Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'button_hover_background_color',
                                        'type' => 'color',
                                        'title' => __('Hover Background Color', 'shopglut'),
                                        'default' => '#2563eb',
                                    ),
                                    array(
                                        'id' => 'button_hover_text_color',
                                        'type' => 'color',
                                        'title' => __('Hover Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'button_font_size',
                                        'type' => 'slider',
                                        'title' => __('Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'button_padding',
                                        'type' => 'spacing',
                                        'title' => __('Button Padding', 'shopglut'),
                                        'default' => array(
                                            'top' => '10',
                                            'right' => '20',
                                            'bottom' => '10',
                                            'left' => '20',
                                            'unit' => 'px',
                                        ),
                                    ),
                                    array(
                                        'id' => 'button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                        'default' => 4,
                                    ),
                                ),
                            ),

                        ),
                    ),

                    // ==================== FLOATING COMPARISON BAR ====================
                    array(
                        'title' => __('Floating Bar', 'shopglut'),
                        'icon' => 'fas fa-window-maximize',
                        'fields' => array(

                            // Floating Bar Settings
                            array(
                                'id' => 'floating_bar_settings',
                                'type' => 'fieldset',
                                'title' => __('Floating Bar Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_floating_bar',
                                        'type' => 'switcher',
                                        'title' => __('Enable Floating Comparison Bar', 'shopglut'),
                                        'desc' => __('Show a floating bar at the bottom showing selected products', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'floating_bar_position',
                                        'type' => 'select',
                                        'title' => __('Floating Bar Position', 'shopglut'),
                                        'options' => array(
                                            'bottom' => __('Bottom', 'shopglut'),
                                            'top' => __('Top', 'shopglut'),
                                        ),
                                        'default' => 'bottom',
                                        'dependency' => array('enable_floating_bar', '==', true),
                                    ),
                                    array(
                                        'id' => 'min_products_show_bar',
                                        'type' => 'number',
                                        'title' => __('Minimum Products to Show Bar', 'shopglut'),
                                        'desc' => __('Show floating bar when at least this many products are added', 'shopglut'),
                                        'default' => 1,
                                        'min' => 1,
                                        'max' => 10,
                                        'dependency' => array('enable_floating_bar', '==', true),
                                    ),
                                    array(
                                        'id' => 'max_products_compare',
                                        'type' => 'number',
                                        'title' => __('Maximum Products to Compare', 'shopglut'),
                                        'desc' => __('Maximum number of products that can be compared at once', 'shopglut'),
                                        'default' => 4,
                                        'min' => 2,
                                        'max' => 10,
                                        'dependency' => array('enable_floating_bar', '==', true),
                                    ),
                                ),
                            ),

                            // Floating Bar Styling
                            array(
                                'id' => 'floating_bar_styling',
                                'type' => 'fieldset',
                                'title' => __('Floating Bar Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'floating_bar_background',
                                        'type' => 'color',
                                        'title' => __('Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'floating_bar_text_color',
                                        'type' => 'color',
                                        'title' => __('Text Color', 'shopglut'),
                                        'default' => '#333333',
                                    ),
                                    array(
                                        'id' => 'floating_bar_border_color',
                                        'type' => 'color',
                                        'title' => __('Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'floating_bar_height',
                                        'type' => 'slider',
                                        'title' => __('Bar Height', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 60,
                                        'max' => 150,
                                        'step' => 5,
                                        'default' => 80,
                                    ),
                                    array(
                                        'id' => 'floating_bar_shadow',
                                        'type' => 'switcher',
                                        'title' => __('Enable Shadow', 'shopglut'),
                                        'default' => true,
                                    ),
                                ),
                            ),

                            // Compare Button in Floating Bar
                            array(
                                'id' => 'floating_compare_button',
                                'type' => 'fieldset',
                                'title' => __('Compare Button in Floating Bar', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'floating_compare_button_text',
                                        'type' => 'text',
                                        'title' => __('Compare Button Text', 'shopglut'),
                                        'default' => __('Compare Now', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'floating_compare_button_bg',
                                        'type' => 'color',
                                        'title' => __('Button Background', 'shopglut'),
                                        'default' => '#10b981',
                                    ),
                                    array(
                                        'id' => 'floating_compare_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'floating_clear_button_text',
                                        'type' => 'text',
                                        'title' => __('Clear All Button Text', 'shopglut'),
                                        'default' => __('Clear All', 'shopglut'),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== COMPARISON TABLE ====================
                    array(
                        'title' => __('Comparison Table', 'shopglut'),
                        'icon' => 'fas fa-table',
                        'fields' => array(

                            // Table Settings
                            array(
                                'id' => 'table_settings',
                                'type' => 'fieldset',
                                'title' => __('Table Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'comparison_page_url',
                                        'type' => 'text',
                                        'title' => __('Comparison Page URL', 'shopglut'),
                                        'desc' => __('Enter the URL where the comparison table will be displayed', 'shopglut'),
                                        'default' => '',
                                    ),
                                    array(
                                        'id' => 'table_layout',
                                        'type' => 'select',
                                        'title' => __('Table Layout', 'shopglut'),
                                        'options' => array(
                                            'vertical' => __('Vertical (Products in Columns)', 'shopglut'),
                                            'horizontal' => __('Horizontal (Products in Rows)', 'shopglut'),
                                        ),
                                        'default' => 'vertical',
                                    ),
                                    array(
                                        'id' => 'enable_sticky_header',
                                        'type' => 'switcher',
                                        'title' => __('Enable Sticky Table Header', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_product_image',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Images', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'product_image_size',
                                        'type' => 'slider',
                                        'title' => __('Product Image Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 80,
                                        'max' => 300,
                                        'step' => 10,
                                        'default' => 150,
                                        'dependency' => array('show_product_image', '==', true),
                                    ),
                                ),
                            ),

                            // Fields to Compare
                            array(
                                'id' => 'comparison_fields',
                                'type' => 'fieldset',
                                'title' => __('Fields to Compare', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_price',
                                        'type' => 'switcher',
                                        'title' => __('Show Price', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_rating',
                                        'type' => 'switcher',
                                        'title' => __('Show Rating', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_stock_status',
                                        'type' => 'switcher',
                                        'title' => __('Show Stock Status', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_description',
                                        'type' => 'switcher',
                                        'title' => __('Show Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_sku',
                                        'type' => 'switcher',
                                        'title' => __('Show SKU', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'show_categories',
                                        'type' => 'switcher',
                                        'title' => __('Show Categories', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_tags',
                                        'type' => 'switcher',
                                        'title' => __('Show Tags', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'show_attributes',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Attributes', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_add_to_cart',
                                        'type' => 'switcher',
                                        'title' => __('Show Add to Cart Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                ),
                            ),

                            // Table Styling
                            array(
                                'id' => 'table_styling',
                                'type' => 'fieldset',
                                'title' => __('Table Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'table_header_bg',
                                        'type' => 'color',
                                        'title' => __('Table Header Background', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                    array(
                                        'id' => 'table_header_text_color',
                                        'type' => 'color',
                                        'title' => __('Table Header Text Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'table_row_bg',
                                        'type' => 'color',
                                        'title' => __('Table Row Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'table_row_alt_bg',
                                        'type' => 'color',
                                        'title' => __('Table Row Alternate Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'table_border_color',
                                        'type' => 'color',
                                        'title' => __('Table Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'table_text_color',
                                        'type' => 'color',
                                        'title' => __('Table Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== GENERAL SETTINGS ====================
                    array(
                        'title' => __('General Settings', 'shopglut'),
                        'icon' => 'fas fa-cog',
                        'fields' => array(

                            // Storage Settings
                            array(
                                'id' => 'storage_settings',
                                'type' => 'fieldset',
                                'title' => __('Storage Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'storage_method',
                                        'type' => 'select',
                                        'title' => __('Storage Method', 'shopglut'),
                                        'desc' => __('How to store comparison data', 'shopglut'),
                                        'options' => array(
                                            'cookie' => __('Browser Cookie', 'shopglut'),
                                            'localstorage' => __('Local Storage', 'shopglut'),
                                            'session' => __('PHP Session (Requires Login)', 'shopglut'),
                                        ),
                                        'default' => 'localstorage',
                                    ),
                                    array(
                                        'id' => 'cookie_expiry_days',
                                        'type' => 'number',
                                        'title' => __('Cookie Expiry (Days)', 'shopglut'),
                                        'default' => 30,
                                        'min' => 1,
                                        'max' => 365,
                                        'dependency' => array('storage_method', '==', 'cookie'),
                                    ),
                                ),
                            ),

                            // Animation Settings
                            array(
                                'id' => 'animation_settings',
                                'type' => 'fieldset',
                                'title' => __('Animation Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_animations',
                                        'type' => 'switcher',
                                        'title' => __('Enable Animations', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'animation_speed',
                                        'type' => 'slider',
                                        'title' => __('Animation Speed', 'shopglut'),
                                        'unit' => 'ms',
                                        'min' => 100,
                                        'max' => 1000,
                                        'step' => 50,
                                        'default' => 300,
                                        'dependency' => array('enable_animations', '==', true),
                                    ),
                                ),
                            ),

                            // Notification Settings
                            array(
                                'id' => 'notification_settings',
                                'type' => 'fieldset',
                                'title' => __('Notification Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_notifications',
                                        'type' => 'switcher',
                                        'title' => __('Show Notifications', 'shopglut'),
                                        'desc' => __('Show notification when product is added/removed', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'notification_position',
                                        'type' => 'select',
                                        'title' => __('Notification Position', 'shopglut'),
                                        'options' => array(
                                            'top-right' => __('Top Right', 'shopglut'),
                                            'top-left' => __('Top Left', 'shopglut'),
                                            'bottom-right' => __('Bottom Right', 'shopglut'),
                                            'bottom-left' => __('Bottom Left', 'shopglut'),
                                        ),
                                        'default' => 'top-right',
                                        'dependency' => array('show_notifications', '==', true),
                                    ),
                                    array(
                                        'id' => 'notification_duration',
                                        'type' => 'slider',
                                        'title' => __('Notification Duration', 'shopglut'),
                                        'unit' => 'ms',
                                        'min' => 1000,
                                        'max' => 10000,
                                        'step' => 500,
                                        'default' => 3000,
                                        'dependency' => array('show_notifications', '==', true),
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
