<?php
/**
 * Single Product template4 Specific Settings
 *
 * This file contains settings specifically designed for template4 single product layout.
 * template4 features:
 * - Product gallery with main image and thumbnails
 * - Product info with badges, title, rating, price, description
 * - Product options (color, size variations)
 * - Purchase section with quantity selector and add to cart
 * - Features section with service highlights
 * - Related products carousel
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_singleproduct_STYLING = "shopg_singleproduct_settings_template4";


// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_single_product_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'singleproduct',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_single_product_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main Single Product Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_singleproduct_STYLING,
    array(
        'title' => esc_html__('template4 Single Product Settings', 'shopglut'),
        'post_type' => 'singleproduct',
        'context' => 'side',
    )
);


// Create fields array with overwrite option
$all_fields1 = array(
    array(
        'id'      => 'overwrite-all-products',
        'type'    => 'switcher',
        'title'   => __("Overwrite All Products", 'shopglut'),
        'default' => false
    ),
    array(
              'id' => 'overwrite-specific-products',
                'type'        => 'select_products',
                'title'       => __("Overwrite Specific Products", 'shopglut'),
                'placeholder' => __("Select products", 'shopglut'),
                'chosen'      => true,
                'multiple'    => true,
                'options' => 'select_products'
            ),
            array(
                'id' => 'single-product-settings',
                'type' => 'tabbed',
                'title' => __('template4 Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== PRODUCT GALLERY SETTINGS ====================
                    array(
                        'title' => __('Product Gallery', 'shopglut'),
                        'icon' => 'fas fa-images',
                        'fields' => array(

                            // Main Image Settings
                            array(
                                'id' => 'main_image_settings',
                                'type' => 'fieldset',
                                'title' => __('Main Image Display', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'main_image_background',
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
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
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
                                    array(
                                        'id' => 'main_image_padding',
                                        'type' => 'slider',
                                        'title' => __('Main Image Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 2,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'main_image_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Main Image Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 2,
                                        'default' => 20,
                                    ),
                                ),
                            ),

                            // Thumbnail Settings
                            array(
                                'id' => 'thumbnail_settings',
                                'type' => 'fieldset',
                                'title' => __('Thumbnail Gallery', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_thumbnails',
                                        'type' => 'switcher',
                                        'title' => __('Show Thumbnail Gallery', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'thumbnail_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 15,
                                        'step' => 1,
                                        'default' => 6,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_spacing',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 4,
                                        'max' => 20,
                                        'step' => 2,
                                        'default' => 8,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_size',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 60,
                                        'max' => 150,
                                        'step' => 5,
                                        'default' => 80,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_border_width',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 5,
                                        'step' => 1,
                                        'default' => 2,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_border_color',
                                        'type' => 'color',
                                        'title' => __('Thumbnail Border Color', 'shopglut'),
                                        'default' => 'transparent',
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_active_border',
                                        'type' => 'color',
                                        'title' => __('Active Thumbnail Border', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_hover_border',
                                        'type' => 'color',
                                        'title' => __('Thumbnail Hover Border', 'shopglut'),
                                        'default' => '#2563eb',
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_gallery_margin_top',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Gallery Top Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 16,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_alignment',
                                        'type' => 'select',
                                        'title' => __('Thumbnail Alignment', 'shopglut'),
                                        'options' => array(
                                            'flex-start' => __('Left', 'shopglut'),
                                            'center' => __('Center', 'shopglut'),
                                            'flex-end' => __('Right', 'shopglut'),
                                        ),
                                        'default' => 'flex-start',
                                        'dependency' => array('show_thumbnails', '==', true),
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

                            // Product Badges General Settings
                            array(
                                'id' => 'product_badges_general',
                                'type' => 'fieldset',
                                'title' => __('Badge General Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_product_badges',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Badges', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'badge_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Badge Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 4,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'badge_font_size',
                                        'type' => 'slider',
                                        'title' => __('Badge Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'badge_font_weight',
                                        'type' => 'select',
                                        'title' => __('Badge Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '500',
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'badge_spacing',
                                        'type' => 'slider',
                                        'title' => __('Badge Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 2,
                                        'max' => 15,
                                        'step' => 1,
                                        'default' => 5,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                ),
                            ),

                            // "New" Badge Settings
                            array(
                                'id' => 'new_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('New Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_new_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "New" Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'new_badge_text',
                                        'type' => 'text',
                                        'title' => __('New Badge Text', 'shopglut'),
                                        'default' => __('New', 'shopglut'),
                                        'dependency' => array('show_new_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'new_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('New Badge Background Color', 'shopglut'),
                                        'default' => '#10b981',
                                        'dependency' => array('show_new_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'new_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('New Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_new_badge', '==', true),
                                    ),
                                ),
                            ),

                            // "Trending" Badge Settings
                            array(
                                'id' => 'trending_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Trending Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_trending_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "Trending" Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'trending_badge_text',
                                        'type' => 'text',
                                        'title' => __('Trending Badge Text', 'shopglut'),
                                        'default' => __('Trending', 'shopglut'),
                                        'dependency' => array('show_trending_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'trending_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Trending Badge Background Color', 'shopglut'),
                                        'default' => '#f59e0b',
                                        'dependency' => array('show_trending_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'trending_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Trending Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_trending_badge', '==', true),
                                    ),
                                ),
                            ),

                            // "Best Seller" Badge Settings
                            array(
                                'id' => 'bestseller_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Best Seller Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_bestseller_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "Best Seller" Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'bestseller_badge_text',
                                        'type' => 'text',
                                        'title' => __('Best Seller Badge Text', 'shopglut'),
                                        'default' => __('Best Seller', 'shopglut'),
                                        'dependency' => array('show_bestseller_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'bestseller_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Best Seller Badge Background Color', 'shopglut'),
                                        'default' => '#ef4444',
                                        'dependency' => array('show_bestseller_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'bestseller_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Best Seller Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_bestseller_badge', '==', true),
                                    ),
                                ),
                            ),

                            // "Hot" Badge Settings
                            array(
                                'id' => 'hot_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Hot Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_hot_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "Hot" Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'hot_badge_text',
                                        'type' => 'text',
                                        'title' => __('Hot Badge Text', 'shopglut'),
                                        'default' => __('Hot', 'shopglut'),
                                        'dependency' => array('show_hot_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'hot_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Hot Badge Background Color', 'shopglut'),
                                        'default' => '#dc2626',
                                        'dependency' => array('show_hot_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'hot_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Hot Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_hot_badge', '==', true),
                                    ),
                                ),
                            ),

                            // "Sale" Badge Settings
                            array(
                                'id' => 'sale_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Sale Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_sale_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "Sale" Badge', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'sale_badge_text',
                                        'type' => 'text',
                                        'title' => __('Sale Badge Text', 'shopglut'),
                                        'default' => __('Sale', 'shopglut'),
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'sale_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Sale Badge Background Color', 'shopglut'),
                                        'default' => '#8b5cf6',
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'sale_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Sale Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_sale_badge', '==', true),
                                    ),
                                ),
                            ),

                            // "Limited" Badge Settings
                            array(
                                'id' => 'limited_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Limited Badge Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_limited_badge',
                                        'type' => 'switcher',
                                        'title' => __('Show "Limited" Badge', 'shopglut'),
                                        'default' => false,
                                        'dependency' => array('show_product_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'limited_badge_text',
                                        'type' => 'text',
                                        'title' => __('Limited Badge Text', 'shopglut'),
                                        'default' => __('Limited', 'shopglut'),
                                        'dependency' => array('show_limited_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'limited_badge_background_color',
                                        'type' => 'color',
                                        'title' => __('Limited Badge Background Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_limited_badge', '==', true),
                                    ),
                                    array(
                                        'id' => 'limited_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Limited Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_limited_badge', '==', true),
                                    ),
                                ),
                            ),

                            // Product Title
                            array(
                                'id' => 'product_title_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Title', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'product_title_color',
                                        'type' => 'color',
                                        'title' => __('Title Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'product_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 48,
                                        'step' => 2,
                                        'default' => 32,
                                    ),
                                    array(
                                        'id' => 'product_title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                            '800' => __('Extra Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                ),
                            ),

                            // Rating Section
                            array(
                                'id' => 'rating_settings',
                                'type' => 'fieldset',
                                'title' => __('Rating Section', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_rating',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Rating', 'shopglut'),
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
                                        'id' => 'rating_text_color',
                                        'type' => 'color',
                                        'title' => __('Rating Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_rating', '==', true),
                                    ),
                                    array(
                                        'id' => 'rating_font_size',
                                        'type' => 'slider',
                                        'title' => __('Rating Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_rating', '==', true),
                                    ),
                                ),
                            ),

                            // Price Section
                            array(
                                'id' => 'price_settings',
                                'type' => 'fieldset',
                                'title' => __('Price Display', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'current_price_color',
                                        'type' => 'color',
                                        'title' => __('Current Price Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'current_price_font_size',
                                        'type' => 'slider',
                                        'title' => __('Current Price Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 28,
                                    ),
                                    array(
                                        'id' => 'original_price_color',
                                        'type' => 'color',
                                        'title' => __('Original Price Color', 'shopglut'),
                                        'default' => '#9ca3af',
                                    ),
                                    array(
                                        'id' => 'discount_badge_color',
                                        'type' => 'color',
                                        'title' => __('Discount Badge Color', 'shopglut'),
                                        'default' => '#ef4444',
                                    ),
                                    array(
                                        'id' => 'discount_badge_text_color',
                                        'type' => 'color',
                                        'title' => __('Discount Badge Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                ),
                            ),

                            // Description Settings
                            array(
                                'id' => 'description_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Description', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_description',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'description_color',
                                        'type' => 'color',
                                        'title' => __('Description Text Color', 'shopglut'),
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
                                        'default' => 16,
                                        'dependency' => array('show_description', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_line_height',
                                        'type' => 'slider',
                                        'title' => __('Description Line Height', 'shopglut'),
                                        'unit' => '',
                                        'min' => 1.2,
                                        'max' => 2.0,
                                        'step' => 0.1,
                                        'default' => 1.6,
                                        'dependency' => array('show_description', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT ATTRIBUTES SETTINGS ====================
                    array(
                        'title' => __('Product Attributes', 'shopglut'),
                        'icon' => 'fas fa-cogs',
                        'fields' => array(

                            // General Attribute Settings
                            array(
                                'id' => 'attribute_general_settings',
                                'type' => 'fieldset',
                                'title' => __('General Attribute Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_product_attributes',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Attributes', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'attribute_layout_style',
                                        'type' => 'select',
                                        'title' => __('Attribute Layout Style', 'shopglut'),
                                        'options' => array(
                                            'horizontal' => __('Horizontal Layout', 'shopglut'),
                                            'vertical' => __('Vertical Layout', 'shopglut'),
                                            'grid' => __('Grid Layout', 'shopglut'),
                                        ),
                                        'default' => 'horizontal',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'attribute_spacing',
                                        'type' => 'slider',
                                        'title' => __('Spacing Between Attributes', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 20,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                ),
                            ),

                            // Attribute Labels
                            array(
                                'id' => 'attribute_labels_settings',
                                'type' => 'fieldset',
                                'title' => __('Attribute Labels', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_attribute_labels',
                                        'type' => 'switcher',
                                        'title' => __('Show Attribute Labels', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'attribute_label_color',
                                        'type' => 'color',
                                        'title' => __('Label Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_attribute_labels', '==', true),
                                    ),
                                    array(
                                        'id' => 'attribute_label_font_size',
                                        'type' => 'slider',
                                        'title' => __('Label Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_attribute_labels', '==', true),
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
                                        'default' => '500',
                                        'dependency' => array('show_attribute_labels', '==', true),
                                    ),
                                    array(
                                        'id' => 'attribute_label_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Label Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_attribute_labels', '==', true),
                                    ),
                                ),
                            ),

                            
                            // Button/Text Attributes (for size, weight, version)
                            array(
                                'id' => 'button_attributes_settings',
                                'type' => 'fieldset',
                                'title' => __('Button/Text Attributes', 'shopglut'),
                                'subtitle' => __('Settings for attributes displayed as buttons or text options', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'button_attribute_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#f3f4f6',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_border_color',
                                        'type' => 'color',
                                        'title' => __('Button Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_active_background',
                                        'type' => 'color',
                                        'title' => __('Active Button Background', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_active_text',
                                        'type' => 'color',
                                        'title' => __('Active Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_active_border',
                                        'type' => 'color',
                                        'title' => __('Active Button Border Color', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_padding_horizontal',
                                        'type' => 'slider',
                                        'title' => __('Button Horizontal Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 16,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_padding_vertical',
                                        'type' => 'slider',
                                        'title' => __('Button Vertical Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 4,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_font_size',
                                        'type' => 'slider',
                                        'title' => __('Button Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_font_weight',
                                        'type' => 'select',
                                        'title' => __('Button Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '500',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_attribute_spacing',
                                        'type' => 'slider',
                                        'title' => __('Spacing Between Buttons', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 4,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                ),
                            ),

                            // Dropdown Attributes
                            array(
                                'id' => 'dropdown_attributes_settings',
                                'type' => 'fieldset',
                                'title' => __('Dropdown Attributes', 'shopglut'),
                                'subtitle' => __('Settings for attributes displayed as dropdown selects', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'dropdown_attribute_background',
                                        'type' => 'color',
                                        'title' => __('Dropdown Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'dropdown_attribute_border_color',
                                        'type' => 'color',
                                        'title' => __('Dropdown Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'dropdown_attribute_text_color',
                                        'type' => 'color',
                                        'title' => __('Dropdown Text Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'dropdown_attribute_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Dropdown Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 15,
                                        'step' => 1,
                                        'default' => 6,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'dropdown_attribute_padding',
                                        'type' => 'slider',
                                        'title' => __('Dropdown Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 8,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'dropdown_attribute_font_size',
                                        'type' => 'slider',
                                        'title' => __('Dropdown Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                ),
                            ),

                            // Attribute Behavior Settings
                            array(
                                'id' => 'attribute_behavior_settings',
                                'type' => 'fieldset',
                                'title' => __('Attribute Behavior', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_attribute_tooltips',
                                        'type' => 'switcher',
                                        'title' => __('Show Attribute Tooltips', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_unavailable_attributes',
                                        'type' => 'switcher',
                                        'title' => __('Show Unavailable Attributes', 'shopglut'),
                                        'subtitle' => __('Whether to show out-of-stock or unavailable attribute options', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'unavailable_attribute_opacity',
                                        'type' => 'slider',
                                        'title' => __('Unavailable Attribute Opacity', 'shopglut'),
                                        'unit' => '',
                                        'min' => 0.1,
                                        'max' => 1.0,
                                        'step' => 0.1,
                                        'default' => 0.5,
                                        'dependency' => array('show_unavailable_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'attribute_required_asterisk',
                                        'type' => 'switcher',
                                        'title' => __('Show Required Asterisk', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_attributes', '==', true),
                                    ),
                                    array(
                                        'id' => 'required_asterisk_color',
                                        'type' => 'color',
                                        'title' => __('Required Asterisk Color', 'shopglut'),
                                        'default' => '#ef4444',
                                        'dependency' => array('attribute_required_asterisk', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PURCHASE SECTION SETTINGS ====================
                    array(
                        'title' => __('Purchase Section', 'shopglut'),
                        'icon' => 'fas fa-shopping-cart',
                        'fields' => array(

                            // Quantity Selector
                            array(
                                'id' => 'quantity_selector_settings',
                                'type' => 'fieldset',
                                'title' => __('Quantity Selector', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'quantity_button_background',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Background', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                    array(
                                        'id' => 'quantity_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Quantity Button Text Color', 'shopglut'),
                                        'default' => '#374151',
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
                                        'id' => 'quantity_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Quantity Control Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 15,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                ),
                            ),

                            // Add to Cart Button
                            array(
                                'id' => 'add_to_cart_settings',
                                'type' => 'fieldset',
                                'title' => __('Add to Cart Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'cart_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'cart_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'cart_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#5a67d8',
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
                                        'id' => 'cart_button_font_weight',
                                        'type' => 'select',
                                        'title' => __('Button Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                ),
                            ),

                            // Secondary Actions
                            array(
                                'id' => 'secondary_actions_settings',
                                'type' => 'fieldset',
                                'title' => __('Secondary Actions', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_wishlist_button',
                                        'type' => 'switcher',
                                        'title' => __('Show Wishlist Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_compare_button',
                                        'type' => 'switcher',
                                        'title' => __('Show Compare Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'secondary_button_color',
                                        'type' => 'color',
                                        'title' => __('Secondary Button Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'secondary_button_hover_color',
                                        'type' => 'color',
                                        'title' => __('Secondary Button Hover Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== FEATURES SECTION SETTINGS ====================
                    array(
                        'title' => __('Features Section', 'shopglut'),
                        'icon' => 'fas fa-star',
                        'fields' => array(

                            // Features Layout
                            array(
                                'id' => 'features_layout_settings',
                                'type' => 'fieldset',
                                'title' => __('Features Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_features_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Features Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'features_section_title',
                                        'type' => 'text',
                                        'title' => __('Features Section Title', 'shopglut'),
                                        'default' => __('Why Choose Us', 'shopglut'),
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_features_section_title',
                                        'type' => 'switcher',
                                        'title' => __('Show Section Title', 'shopglut'),
                                        'default' => false,
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_section_title_color',
                                        'type' => 'color',
                                        'title' => __('Section Title Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_features_section_title', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_background_color',
                                        'type' => 'color',
                                        'title' => __('Features Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Features Section Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_grid_columns',
                                        'type' => 'select',
                                        'title' => __('Features Grid Columns', 'shopglut'),
                                        'options' => array(
                                            '1' => __('1 Column', 'shopglut'),
                                            '2' => __('2 Columns', 'shopglut'),
                                            '3' => __('3 Columns', 'shopglut'),
                                            '4' => __('4 Columns', 'shopglut'),
                                            '5' => __('5 Columns', 'shopglut'),
                                            '6' => __('6 Columns', 'shopglut'),
                                        ),
                                        'default' => '4',
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_padding',
                                        'type' => 'slider',
                                        'title' => __('Features Section Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 60,
                                        'step' => 2,
                                        'default' => 24,
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'features_gap',
                                        'type' => 'slider',
                                        'title' => __('Gap Between Features', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 20,
                                        'dependency' => array('show_features_section', '==', true),
                                    ),
                                ),
                            ),

                            // Dynamic Features List
                            array(
                                'id' => 'features_list',
                                'type' => 'fieldset',
                                'title' => __('Features List', 'shopglut'),
                                'dependency' => array('show_features_section', '==', true),
                                'fields' => array(
                                    array(
                                        'id' => 'product_features',
                                        'type' => 'repeater',
                                        'title' => __('Product Features', 'shopglut'),
                                        'subtitle' => __('Add features to highlight product benefits and services', 'shopglut'),
                                        'button_title' => __('Add New Feature', 'shopglut'),
                                        'max' => 12,
                                        'fields' => array(
                                            array(
                                                'id' => 'feature_icon_type',
                                                'type' => 'select',
                                                'title' => __('Icon Type', 'shopglut'),
                                                'options' => array(
                                                    'fontawesome' => __('Font Awesome Icon', 'shopglut'),
                                                    'image' => __('Custom Image', 'shopglut'),
                                                ),
                                                'default' => 'fontawesome',
                                            ),
                                            array(
                                                'id' => 'feature_fontawesome_icon',
                                                'type' => 'icon',
                                                'title' => __('Font Awesome Icon', 'shopglut'),
                                                'default' => 'fas fa-star',
                                                'dependency' => array('feature_icon_type', '==', 'fontawesome'),
                                            ),
                                            array(
                                                'id' => 'feature_custom_image',
                                                'type' => 'media',
                                                'title' => __('Custom Icon Image', 'shopglut'),
                                                'subtitle' => __('Upload a custom icon image (recommended: 64x64px)', 'shopglut'),
                                                'dependency' => array('feature_icon_type', '==', 'image'),
                                            ),
                                            array(
                                                'id' => 'feature_title',
                                                'type' => 'text',
                                                'title' => __('Feature Title', 'shopglut'),
                                                'default' => __('Feature Title', 'shopglut'),
                                            ),
                                            array(
                                                'id' => 'feature_description',
                                                'type' => 'textarea',
                                                'title' => __('Feature Description', 'shopglut'),
                                                'subtitle' => __('Short description explaining this feature benefit', 'shopglut'),
                                                'default' => __('Feature description goes here...', 'shopglut'),
                                                'rows' => 3,
                                            ),
                                            array(
                                                'id' => 'feature_link_enabled',
                                                'type' => 'switcher',
                                                'title' => __('Enable Feature Link', 'shopglut'),
                                                'default' => false,
                                            ),
                                            array(
                                                'id' => 'feature_link_url',
                                                'type' => 'text',
                                                'title' => __('Feature Link URL', 'shopglut'),
                                                'dependency' => array('feature_link_enabled', '==', true),
                                            ),
                                            array(
                                                'id' => 'feature_link_target',
                                                'type' => 'select',
                                                'title' => __('Link Target', 'shopglut'),
                                                'options' => array(
                                                    '_self' => __('Same Window', 'shopglut'),
                                                    '_blank' => __('New Window', 'shopglut'),
                                                ),
                                                'default' => '_self',
                                                'dependency' => array('feature_link_enabled', '==', true),
                                            ),
                                        ),
                                        'default' => array(
                                            array(
                                                'feature_icon_type' => 'fontawesome',
                                                'feature_fontawesome_icon' => 'fas fa-shipping-fast',
                                                'feature_title' => __('Free Shipping', 'shopglut'),
                                                'feature_description' => __('Free shipping on orders over $50', 'shopglut'),
                                                'feature_link_enabled' => false,
                                            ),
                                            array(
                                                'feature_icon_type' => 'fontawesome',
                                                'feature_fontawesome_icon' => 'fas fa-undo',
                                                'feature_title' => __('Easy Returns', 'shopglut'),
                                                'feature_description' => __('30-day hassle-free returns', 'shopglut'),
                                                'feature_link_enabled' => false,
                                            ),
                                            array(
                                                'feature_icon_type' => 'fontawesome',
                                                'feature_fontawesome_icon' => 'fas fa-shield-alt',
                                                'feature_title' => __('Secure Payment', 'shopglut'),
                                                'feature_description' => __('100% secure payment processing', 'shopglut'),
                                                'feature_link_enabled' => false,
                                            ),
                                            array(
                                                'feature_icon_type' => 'fontawesome',
                                                'feature_fontawesome_icon' => 'fas fa-headset',
                                                'feature_title' => __('24/7 Support', 'shopglut'),
                                                'feature_description' => __('Round-the-clock customer support', 'shopglut'),
                                                'feature_link_enabled' => false,
                                            ),
                                        ),
                                    ),
                                ),
                            ),

                            // Feature Items Styling
                            array(
                                'id' => 'feature_items_styling',
                                'type' => 'fieldset',
                                'title' => __('Feature Items Styling', 'shopglut'),
                                'dependency' => array('show_features_section', '==', true),
                                'fields' => array(
                                    array(
                                        'id' => 'feature_item_alignment',
                                        'type' => 'select',
                                        'title' => __('Feature Item Alignment', 'shopglut'),
                                        'options' => array(
                                            'left' => __('Left Aligned', 'shopglut'),
                                            'center' => __('Center Aligned', 'shopglut'),
                                            'right' => __('Right Aligned', 'shopglut'),
                                        ),
                                        'default' => 'center',
                                    ),
                                    array(
                                        'id' => 'feature_icon_size',
                                        'type' => 'slider',
                                        'title' => __('Feature Icon Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 80,
                                        'step' => 2,
                                        'default' => 32,
                                    ),
                                    array(
                                        'id' => 'feature_icon_color',
                                        'type' => 'color',
                                        'title' => __('Feature Icon Color', 'shopglut'),
                                        'subtitle' => __('Color for Font Awesome icons', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'feature_icon_background',
                                        'type' => 'color',
                                        'title' => __('Feature Icon Background', 'shopglut'),
                                        'default' => 'transparent',
                                    ),
                                    array(
                                        'id' => 'feature_icon_padding',
                                        'type' => 'slider',
                                        'title' => __('Feature Icon Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'feature_icon_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Feature Icon Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'feature_title_color',
                                        'type' => 'color',
                                        'title' => __('Feature Title Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'feature_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Feature Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'feature_title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Feature Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                    array(
                                        'id' => 'feature_title_margin_top',
                                        'type' => 'slider',
                                        'title' => __('Title Top Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'feature_description_color',
                                        'type' => 'color',
                                        'title' => __('Feature Description Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'feature_description_font_size',
                                        'type' => 'slider',
                                        'title' => __('Feature Description Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'feature_description_line_height',
                                        'type' => 'slider',
                                        'title' => __('Description Line Height', 'shopglut'),
                                        'unit' => '',
                                        'min' => 1.2,
                                        'max' => 2.0,
                                        'step' => 0.1,
                                        'default' => 1.5,
                                    ),
                                    array(
                                        'id' => 'feature_description_margin_top',
                                        'type' => 'slider',
                                        'title' => __('Description Top Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                    ),
                                ),
                            ),

                            // Feature Link Styling
                            array(
                                'id' => 'feature_link_styling',
                                'type' => 'fieldset',
                                'title' => __('Feature Link Styling', 'shopglut'),
                                'dependency' => array('show_features_section', '==', true),
                                'fields' => array(
                                    array(
                                        'id' => 'feature_link_color',
                                        'type' => 'color',
                                        'title' => __('Feature Link Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'feature_link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Feature Link Hover Color', 'shopglut'),
                                        'default' => '#5a67d8',
                                    ),
                                    array(
                                        'id' => 'feature_link_decoration',
                                        'type' => 'select',
                                        'title' => __('Feature Link Decoration', 'shopglut'),
                                        'options' => array(
                                            'none' => __('None', 'shopglut'),
                                            'underline' => __('Underline', 'shopglut'),
                                        ),
                                        'default' => 'none',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== MODULE INTEGRATION SETTINGS ====================
                    array(
                        'title' => __('Module Integration', 'shopglut'),
                        'icon' => 'fas fa-plug',
                        'fields' => array(

                            // Wishlist Module
                            array(
                                'id' => 'wishlist_integration',
                                'type' => 'fieldset',
                                'title' => __('Wishlist Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_wishlist',
                                        'type' => 'switcher',
                                        'title' => __('Enable Wishlist Button', 'shopglut'),
                                        'subtitle' => __('Show wishlist button on single product page', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'wishlist_position',
                                        'type' => 'select',
                                        'title' => __('Wishlist Button Position', 'shopglut'),
                                        'options' => array(
                                            'after_add_to_cart' => __('After Add to Cart Button', 'shopglut'),
                                            'before_add_to_cart' => __('Before Add to Cart Button', 'shopglut'),
                                            'after_product_title' => __('After Product Title', 'shopglut'),
                                            'before_price' => __('Before Price', 'shopglut'),
                                        ),
                                        'default' => 'after_add_to_cart',
                                        'dependency' => array('enable_wishlist', '==', true),
                                    ),
                                ),
                            ),

                            
                            // Product Badges Module
                            array(
                                'id' => 'badges_integration',
                                'type' => 'fieldset',
                                'title' => __('Product Badges Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_badges',
                                        'type' => 'switcher',
                                        'title' => __('Enable Product Badges', 'shopglut'),
                                        'subtitle' => __('Show product badges on single product page', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'badge_layout_id',
                                        'type' => 'select_badge_layouts',
                                        'title' => __('Select Badge Layout', 'shopglut'),
                                        'subtitle' => __('Choose which badge layout to display', 'shopglut'),
                                        'placeholder' => __('Select a badge layout', 'shopglut'),
                                        'default' => '',
                                        'dependency' => array('enable_badges', '==', true),
                                    ),
                                    array(
                                        'id' => 'badge_position',
                                        'type' => 'select',
                                        'title' => __('Badge Position', 'shopglut'),
                                        'options' => array(
                                            'on_product_image' => __('On Product Image', 'shopglut'),
                                            'before_product_title' => __('Before Product Title', 'shopglut'),
                                            'after_product_title' => __('After Product Title', 'shopglut'),
                                            'before_price' => __('Before Price', 'shopglut'),
                                        ),
                                        'default' => 'on_product_image',
                                        'dependency' => array('enable_badges', '==', true),
                                    ),
                                ),
                            ),

                            // Product Comparison Module
                            array(
                                'id' => 'comparison_integration',
                                'type' => 'fieldset',
                                'title' => __('Product Comparison Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_comparison',
                                        'type' => 'switcher',
                                        'title' => __('Enable Product Comparison', 'shopglut'),
                                        'subtitle' => __('Show comparison button on single product page', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'comparison_layout_id',
                                        'type' => 'select_comparison_layouts',
                                        'title' => __('Select Comparison Layout', 'shopglut'),
                                        'subtitle' => __('Choose which comparison layout to use', 'shopglut'),
                                        'placeholder' => __('Select a comparison layout', 'shopglut'),
                                        'default' => '',
                                        'dependency' => array('enable_comparison', '==', true),
                                    ),
                                    array(
                                        'id' => 'comparison_position',
                                        'type' => 'select',
                                        'title' => __('Comparison Button Position', 'shopglut'),
                                        'options' => array(
                                            'after_add_to_cart' => __('After Add to Cart Button', 'shopglut'),
                                            'with_wishlist' => __('Next to Wishlist Button', 'shopglut'),
                                            'before_add_to_cart' => __('Before Add to Cart Button', 'shopglut'),
                                        ),
                                        'default' => 'after_add_to_cart',
                                        'dependency' => array('enable_comparison', '==', true),
                                    ),
                                ),
                            ),

                            // Product Custom Fields Module
                            array(
                                'id' => 'custom_fields_integration',
                                'type' => 'fieldset',
                                'title' => __('Product Custom Fields Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_custom_fields',
                                        'type' => 'switcher',
                                        'title' => __('Enable Product Custom Fields', 'shopglut'),
                                        'subtitle' => __('Show custom fields on single product page', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'custom_fields_layout_id',
                                        'type' => 'select_custom_fields',
                                        'title' => __('Select Custom Fields Layout', 'shopglut'),
                                        'subtitle' => __('Choose which custom field layout to use', 'shopglut'),
                                        'placeholder' => __('Select a custom field', 'shopglut'),
                                        'default' => '',
                                        'dependency' => array('enable_custom_fields', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== RELATED PRODUCTS SETTINGS ====================
                    array(
                        'title' => __('Related Products', 'shopglut'),
                        'icon' => 'fas fa-th-large',
                        'fields' => array(

                            // Related Products Layout
                            array(
                                'id' => 'related_products_layout',
                                'type' => 'fieldset',
                                'title' => __('Related Products Section', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_related_products',
                                        'type' => 'switcher',
                                        'title' => __('Show Related Products', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'related_section_title',
                                        'type' => 'text',
                                        'title' => __('Section Title', 'shopglut'),
                                        'default' => __('You Might Also Like', 'shopglut'),
                                        'dependency' => array('show_related_products', '==', true),
                                    ),
                                    array(
                                        'id' => 'related_section_title_color',
                                        'type' => 'color',
                                        'title' => __('Section Title Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_related_products', '==', true),
                                    ),
                                    array(
                                        'id' => 'related_products_per_row',
                                        'type' => 'select',
                                        'title' => __('Products Per Row', 'shopglut'),
                                        'options' => array(
                                            '2' => __('2 Products', 'shopglut'),
                                            '3' => __('3 Products', 'shopglut'),
                                            '4' => __('4 Products', 'shopglut'),
                                        ),
                                        'default' => '4',
                                        'dependency' => array('show_related_products', '==', true),
                                    ),
                                ),
                            ),

                            // Product Cards
                            array(
                                'id' => 'related_product_cards',
                                'type' => 'fieldset',
                                'title' => __('Product Cards', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'product_card_background',
                                        'type' => 'color',
                                        'title' => __('Product Card Background', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'product_card_border_color',
                                        'type' => 'color',
                                        'title' => __('Product Card Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'product_card_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Product Card Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'product_card_hover_shadow',
                                        'type' => 'switcher',
                                        'title' => __('Enable Card Hover Shadow', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'quick_add_button_background',
                                        'type' => 'color',
                                        'title' => __('Quick Add Button Background', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'quick_add_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Quick Add Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
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
    $SHOPG_singleproduct_STYLING,
    array(
        'fields' => $all_fields1
    )
);
?>