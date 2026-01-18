<?php
/**
 * Product QuickView Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 quick view layout.
 * Template1 features:
 * - Product gallery with main image and thumbnails
 * - Product info with title, rating, price, description
 * - Product meta (SKU, categories, availability)
 * - Product variations for variable products
 * - Quantity selector and add to cart
 */

if (!defined('ABSPATH')) {
    exit;
}

$shopglut_quickview_styling = "shopglut_product_quickview_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_product_quickview_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'product_quickview',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_product_quickview_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main QuickView Styling Settings
AGSHOPGLUT::createMetabox(
    $shopglut_quickview_styling,
    array(
        'title' => esc_html__('Template1 QuickView Settings', 'shopglut'),
        'post_type' => 'product_quickview',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $shopglut_quickview_styling,
    array(
        'fields' => array(

            // ==================== DISPLAY SETTINGS (OUTSIDE TABS) ====================
            array(
                'id' => 'enable_quickview',
                'type' => 'switcher',
                'title' => __('Enable QuickView', 'shopglut'),
                'desc' => __('Enable or disable the QuickView feature globally', 'shopglut'),
                'default' => true,
            ),

            array(
                'id' => 'display-locations',
                'type' => 'select_quickview_display',
                'title' => __('Display QuickView Button On', 'shopglut'),
                'desc' => __('Select pages where the QuickView button should appear. Each location can only be used by one layout.', 'shopglut'),
                'options' => 'select_quickview_display',
                'multiple' => true,
                'chosen' => true,
                'placeholder' => __('Select pages to show QuickView button', 'shopglut'),
                'dependency' => array('enable_quickview', '==', true),
            ),

            array(
                'id' => 'button_position',
                'type' => 'select',
                'title' => __('QuickView Button Position', 'shopglut'),
                'desc' => __('Select where to display the QuickView button on product items', 'shopglut'),
                'options' => array(
                    'before_add_to_cart' => __('Before Add to Cart Button', 'shopglut'),
                    'after_add_to_cart' => __('After Add to Cart Button', 'shopglut'),
                    'on_image' => __('On Product Image (Overlay)', 'shopglut'),
                    'after_product_title' => __('After Product Title', 'shopglut'),
                ),
                'default' => 'after_add_to_cart',
                'dependency' => array('enable_quickview', '==', true),
            ),

            array(
                'id' => 'button_text',
                'type' => 'text',
                'title' => __('QuickView Button Text', 'shopglut'),
                'desc' => __('Text to display on the QuickView button', 'shopglut'),
                'default' => __('Quick View', 'shopglut'),
                'dependency' => array('enable_quickview', '==', true),
            ),

            array(
                'id' => 'show_button_icon',
                'type' => 'switcher',
                'title' => __('Show Button Icon', 'shopglut'),
                'desc' => __('Display an icon on the QuickView button', 'shopglut'),
                'default' => true,
                'dependency' => array('enable_quickview', '==', true),
            ),

            array(
                'id' => 'button_icon',
                'type' => 'icon',
                'title' => __('Button Icon', 'shopglut'),
                'desc' => __('Select an icon for the QuickView button', 'shopglut'),
                'default' => 'fas fa-eye',
                'dependency' => array(
                    array('enable_quickview', '==', true),
                    array('show_button_icon', '==', true),
                ),
            ),

            array(
                'type' => 'subheading',
                'content' => __('QuickView Modal Settings', 'shopglut'),
            ),

            array(
                'id' => 'product_quickview-page-settings',
                'type' => 'tabbed',
                'title' => __('QuickView Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== MODAL SETTINGS ====================
                    array(
                        'title' => __('Modal', 'shopglut'),
                        'icon' => 'fas fa-window-maximize',
                        'fields' => array(

                            // Modal Overlay Settings
                            array(
                                'id' => 'modal_overlay_settings',
                                'type' => 'fieldset',
                                'title' => __('Modal Overlay', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'modal_overlay_color',
                                        'type' => 'color',
                                        'title' => __('Overlay Background Color', 'shopglut'),
                                        'default' => 'rgba(0, 0, 0, 0.75)',
                                    ),
                                    array(
                                        'id' => 'modal_overlay_blur',
                                        'type' => 'slider',
                                        'title' => __('Overlay Blur', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 10,
                                        'step' => 1,
                                        'default' => 4,
                                    ),
                                ),
                            ),

                            // Modal Content Settings
                            array(
                                'id' => 'modal_content_settings',
                                'type' => 'fieldset',
                                'title' => __('Modal Content', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'modal_max_width',
                                        'type' => 'slider',
                                        'title' => __('Modal Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 800,
                                        'max' => 1400,
                                        'step' => 50,
                                        'default' => 1100,
                                    ),
                                    array(
                                        'id' => 'modal_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Modal Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'modal_background_color',
                                        'type' => 'color',
                                        'title' => __('Modal Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'modal_padding',
                                        'type' => 'slider',
                                        'title' => __('Modal Inner Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 80,
                                        'step' => 5,
                                        'default' => 40,
                                    ),
                                ),
                            ),

                            // Close Button Settings
                            array(
                                'id' => 'close_button_settings',
                                'type' => 'fieldset',
                                'title' => __('Close Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'close_button_size',
                                        'type' => 'slider',
                                        'title' => __('Close Button Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 30,
                                        'max' => 60,
                                        'step' => 2,
                                        'default' => 40,
                                    ),
                                    array(
                                        'id' => 'close_button_color',
                                        'type' => 'color',
                                        'title' => __('Close Button Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'close_button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Close Button Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'close_button_hover_color',
                                        'type' => 'color',
                                        'title' => __('Close Button Hover Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'close_button_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Close Button Hover Background', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== GALLERY SETTINGS ====================
                    array(
                        'title' => __('Gallery', 'shopglut'),
                        'icon' => 'fas fa-images',
                        'fields' => array(

                            // Main Image Settings
                            array(
                                'id' => 'gallery_main_image',
                                'type' => 'fieldset',
                                'title' => __('Main Image', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'main_image_bg_color',
                                        'type' => 'color',
                                        'title' => __('Main Image Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'main_image_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Main Image Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'main_image_border_color',
                                        'type' => 'color',
                                        'title' => __('Main Image Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'main_image_border_width',
                                        'type' => 'slider',
                                        'title' => __('Main Image Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 5,
                                        'step' => 1,
                                        'default' => 1,
                                    ),
                                ),
                            ),

                            // Thumbnail Settings
                            array(
                                'id' => 'gallery_thumbnails',
                                'type' => 'fieldset',
                                'title' => __('Thumbnails', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_thumbnails',
                                        'type' => 'switcher',
                                        'title' => __('Show Thumbnails', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'thumbnail_size',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 60,
                                        'max' => 120,
                                        'step' => 5,
                                        'default' => 80,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_gap',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Gap', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 5,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 10,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_border_color',
                                        'type' => 'color',
                                        'title' => __('Thumbnail Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_active_border_color',
                                        'type' => 'color',
                                        'title' => __('Active Thumbnail Border', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                ),
                            ),

                            // Sale Badge Settings
                            array(
                                'id' => 'sale_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Sale Badge', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_sale_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show Sale Badge', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'sale_badge_bg_color',
                                        'type' => 'color',
                                        'title' => __('Sale Badge Background', 'shopglut'),
                                        'default' => '#ef4444',
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'sale_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Sale Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'sale_badge_font_size',
                                        'type' => 'slider',
                                        'title' => __('Sale Badge Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT INFO SETTINGS ====================
                    array(
                        'title' => __('Product Info', 'shopglut'),
                        'icon' => 'fas fa-info-circle',
                        'fields' => array(

                            // Product Title Settings
                            array(
                                'id' => 'product_title_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Title', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'title_color',
                                        'type' => 'color',
                                        'title' => __('Title Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 18,
                                        'max' => 40,
                                        'step' => 1,
                                        'default' => 28,
                                    ),
                                    array(
                                        'id' => 'title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                ),
                            ),

                            // Product Rating Settings
                            array(
                                'id' => 'product_rating_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Rating', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_rating',
                                        'type' => 'switcher',
                                        'title' => __('Show Rating', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'star_color',
                                        'type' => 'color',
                                        'title' => __('Star Color', 'shopglut'),
                                        'default' => '#fbbf24',
                                        'dependency' => array('show_rating', '==', true),
                                    ),
                                    array(
                                        'id' => 'star_size',
                                        'type' => 'slider',
                                        'title' => __('Star Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 18,
                                        'dependency' => array('show_rating', '==', true),
                                    ),
                                    array(
                                        'id' => 'rating_text_color',
                                        'type' => 'color',
                                        'title' => __('Rating Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_rating', '==', true),
                                    ),
                                ),
                            ),

                            // Product Price Settings
                            array(
                                'id' => 'product_price_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Price', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'price_color',
                                        'type' => 'color',
                                        'title' => __('Price Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'price_font_size',
                                        'type' => 'slider',
                                        'title' => __('Price Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 48,
                                        'step' => 1,
                                        'default' => 32,
                                    ),
                                    array(
                                        'id' => 'regular_price_color',
                                        'type' => 'color',
                                        'title' => __('Regular Price Color', 'shopglut'),
                                        'default' => '#9ca3af',
                                    ),
                                ),
                            ),

                            // Product Description Settings
                            array(
                                'id' => 'product_description_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Description', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_description',
                                        'type' => 'switcher',
                                        'title' => __('Show Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'description_color',
                                        'type' => 'color',
                                        'title' => __('Description Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_description', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_font_size',
                                        'type' => 'slider',
                                        'title' => __('Description Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 15,
                                        'dependency' => array('show_description', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT META SETTINGS ====================
                    array(
                        'title' => __('Product Meta', 'shopglut'),
                        'icon' => 'fas fa-tags',
                        'fields' => array(

                            // Product Meta Settings
                            array(
                                'id' => 'product_meta_settings',
                                'type' => 'fieldset',
                                'title' => __('Meta Information', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_sku',
                                        'type' => 'switcher',
                                        'title' => __('Show SKU', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_categories',
                                        'type' => 'switcher',
                                        'title' => __('Show Categories', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_stock_status',
                                        'type' => 'switcher',
                                        'title' => __('Show Stock Status', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'meta_bg_color',
                                        'type' => 'color',
                                        'title' => __('Meta Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'meta_label_color',
                                        'type' => 'color',
                                        'title' => __('Meta Label Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'meta_value_color',
                                        'type' => 'color',
                                        'title' => __('Meta Value Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'in_stock_color',
                                        'type' => 'color',
                                        'title' => __('In Stock Color', 'shopglut'),
                                        'default' => '#10b981',
                                    ),
                                    array(
                                        'id' => 'out_of_stock_color',
                                        'type' => 'color',
                                        'title' => __('Out of Stock Color', 'shopglut'),
                                        'default' => '#ef4444',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT ACTIONS SETTINGS ====================
                    array(
                        'title' => __('Actions', 'shopglut'),
                        'icon' => 'fas fa-shopping-cart',
                        'fields' => array(

                            // Quantity Selector Settings
                            array(
                                'id' => 'quantity_selector_settings',
                                'type' => 'fieldset',
                                'title' => __('Quantity Selector', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'qty_button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'qty_button_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Hover Background', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'qty_button_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'qty_border_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                ),
                            ),

                            // Add to Cart Button Settings
                            array(
                                'id' => 'add_to_cart_button_settings',
                                'type' => 'fieldset',
                                'title' => __('Add to Cart Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'cart_button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'cart_button_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#5a67d8',
                                    ),
                                    array(
                                        'id' => 'cart_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'cart_button_font_size',
                                        'type' => 'slider',
                                        'title' => __('Button Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'cart_button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                ),
                            ),

                            // View Details Button Settings
                            array(
                                'id' => 'view_details_button_settings',
                                'type' => 'fieldset',
                                'title' => __('View Details Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'details_button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'details_button_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'details_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'details_button_border_color',
                                        'type' => 'color',
                                        'title' => __('Button Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== VARIATIONS SETTINGS ====================
                    array(
                        'title' => __('Variations', 'shopglut'),
                        'icon' => 'fas fa-sliders-h',
                        'fields' => array(

                            // Variation Label Settings
                            array(
                                'id' => 'variation_label_settings',
                                'type' => 'fieldset',
                                'title' => __('Variation Labels', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'variation_label_color',
                                        'type' => 'color',
                                        'title' => __('Label Color', 'shopglut'),
                                        'desc' => __('Color for variation attribute labels (e.g., "Size:", "Color:")', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'variation_label_font_size',
                                        'type' => 'slider',
                                        'title' => __('Label Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                ),
                            ),

                            // Variation Select Settings
                            array(
                                'id' => 'variation_select_settings',
                                'type' => 'fieldset',
                                'title' => __('Variation Dropdowns', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'variation_select_bg',
                                        'type' => 'color',
                                        'title' => __('Select Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'variation_select_text_color',
                                        'type' => 'color',
                                        'title' => __('Select Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'variation_select_border',
                                        'type' => 'color',
                                        'title' => __('Select Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                    ),
                                    array(
                                        'id' => 'variation_select_border_width',
                                        'type' => 'slider',
                                        'title' => __('Select Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 1,
                                        'max' => 3,
                                        'step' => 1,
                                        'default' => 2,
                                    ),
                                    array(
                                        'id' => 'variation_select_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Select Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'variation_select_focus_border',
                                        'type' => 'color',
                                        'title' => __('Select Focus Border', 'shopglut'),
                                        'desc' => __('Border color when dropdown is focused/active', 'shopglut'),
                                        'default' => '#667eea',
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
