<?php
/**
 * Single Product template2 Specific Settings
 *
 * This file contains settings specifically designed for template2 single product layout.
 * template2 features:
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

$SHOPG_singleproduct_STYLING = "shopg_singleproduct_settings_template2";


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
        'title' => esc_html__('Template2 Single Product Settings', 'shopglut'),
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
                'title' => __('Template2 Configuration', 'shopglut'),
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
                                        'id' => 'main_image_object_fit',
                                        'type' => 'select',
                                        'title' => __('Image Fit Mode', 'shopglut'),
                                        'options' => array(
                                            'cover' => __('Cover (Fill Container)', 'shopglut'),
                                            'contain' => __('Contain (Show Full Image)', 'shopglut'),
                                            'fill' => __('Fill (Stretch Image)', 'shopglut'),
                                        ),
                                        'default' => 'cover',
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
                                        'id' => 'enable_shimmer_effect',
                                        'type' => 'switcher',
                                        'title' => __('Enable Shimmer Effect', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'shimmer_speed',
                                        'type' => 'slider',
                                        'title' => __('Shimmer Animation Speed', 'shopglut'),
                                        'unit' => 's',
                                        'min' => 1,
                                        'max' => 10,
                                        'step' => 0.5,
                                        'default' => 3,
                                        'dependency' => array('enable_shimmer_effect', '==', true),
                                    ),
                                    array(
                                        'id' => 'shimmer_opacity',
                                        'type' => 'slider',
                                        'title' => __('Shimmer Opacity', 'shopglut'),
                                        'unit' => '%',
                                        'min' => 5,
                                        'max' => 50,
                                        'step' => 5,
                                        'default' => 20,
                                        'dependency' => array('enable_shimmer_effect', '==', true),
                                    ),
                                    array(
                                        'id' => 'main_image_shadow',
                                        'type' => 'switcher',
                                        'title' => __('Enable Image Shadow', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'main_image_shadow_color',
                                        'type' => 'color',
                                        'title' => __('Shadow Color', 'shopglut'),
                                        'default' => 'rgba(0,0,0,0.1)',
                                        'dependency' => array('main_image_shadow', '==', true),
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
                                    array(
                                        'id' => 'main_image_hover_scale',
                                        'type' => 'switcher',
                                        'title' => __('Enable Hover Scale Effect', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'main_image_hover_scale_value',
                                        'type' => 'slider',
                                        'title' => __('Hover Scale Value', 'shopglut'),
                                        'unit' => 'x',
                                        'min' => 1.02,
                                        'max' => 1.15,
                                        'step' => 0.01,
                                        'default' => 1.05,
                                        'dependency' => array('main_image_hover_scale', '==', true),
                                    ),
                                    array(
                                        'id' => 'main_image_hover_brightness',
                                        'type' => 'switcher',
                                        'title' => __('Enable Hover Brightness', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'main_image_hover_brightness_value',
                                        'type' => 'slider',
                                        'title' => __('Hover Brightness Level', 'shopglut'),
                                        'unit' => '%',
                                        'min' => 100,
                                        'max' => 130,
                                        'step' => 5,
                                        'default' => 110,
                                        'dependency' => array('main_image_hover_brightness', '==', true),
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
                                        'id' => 'thumbnail_opacity',
                                        'type' => 'slider',
                                        'title' => __('Inactive Thumbnail Opacity', 'shopglut'),
                                        'unit' => '%',
                                        'min' => 20,
                                        'max' => 100,
                                        'step' => 5,
                                        'default' => 70,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_hover_scale',
                                        'type' => 'switcher',
                                        'title' => __('Enable Hover Scale Effect', 'shopglut'),
                                        'default' => true,
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
                                ),
                            ),

                            // Gallery Interaction Settings
                            array(
                                'id' => 'gallery_interaction_settings',
                                'type' => 'fieldset',
                                'title' => __('Gallery Interaction', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_image_lightbox',
                                        'type' => 'switcher',
                                        'title' => __('Enable Lightbox on Click', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'main_image_cursor',
                                        'type' => 'select',
                                        'title' => __('Image Cursor Style', 'shopglut'),
                                        'options' => array(
                                            'pointer' => __('Pointer (Click to Zoom)', 'shopglut'),
                                            'zoom-in' => __('Zoom In', 'shopglut'),
                                            'default' => __('Default', 'shopglut'),
                                        ),
                                        'default' => 'zoom-in',
                                    ),
                                    array(
                                        'id' => 'enable_image_hover_zoom',
                                        'type' => 'switcher',
                                        'title' => __('Enable Hover Zoom Effect', 'shopglut'),
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'hover_zoom_level',
                                        'type' => 'slider',
                                        'title' => __('Hover Zoom Level', 'shopglut'),
                                        'unit' => 'x',
                                        'min' => 1.5,
                                        'max' => 3,
                                        'step' => 0.1,
                                        'default' => 2,
                                        'dependency' => array('enable_image_hover_zoom', '==', true),
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

                            // Breadcrumb Settings
                            array(
                                'id' => 'breadcrumb_settings',
                                'type' => 'fieldset',
                                'title' => __('Breadcrumb Navigation', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_breadcrumb',
                                        'type' => 'switcher',
                                        'title' => __('Show Breadcrumb', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'breadcrumb_font_size',
                                        'type' => 'slider',
                                        'title' => __('Breadcrumb Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_text_color',
                                        'type' => 'color',
                                        'title' => __('Breadcrumb Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_link_color',
                                        'type' => 'color',
                                        'title' => __('Breadcrumb Link Color', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Breadcrumb Link Hover Color', 'shopglut'),
                                        'default' => '#5a67d8',
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_separator',
                                        'type' => 'text',
                                        'title' => __('Breadcrumb Separator', 'shopglut'),
                                        'default' => '>',
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_separator_color',
                                        'type' => 'color',
                                        'title' => __('Separator Color', 'shopglut'),
                                        'default' => '#9ca3af',
                                        'dependency' => array('show_breadcrumb', '==', true),
                                    ),
                                    array(
                                        'id' => 'breadcrumb_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Breadcrumb Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 16,
                                        'dependency' => array('show_breadcrumb', '==', true),
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
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'badge_layout_id',
                                        'type' => 'select_badge_layouts',
                                        'title' => __('Select Badge Layout', 'shopglut'),
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

                            // Product Custom Fields Module
                            array(
                                'id' => 'custom_fields_integration',
                                'type' => 'fieldset',
                                'title' => __('Product Custom Fields Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_custom_fields',
                                        'type' => 'switcher',
                                        'title' => __('Enable Custom Fields', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'custom_fields_layout_id',
                                        'type' => 'select_custom_fields',
                                        'title' => __('Select Custom Fields Layout', 'shopglut'),
                                        'placeholder' => __('Select a custom field', 'shopglut'),
                                        'default' => '',
                                        'dependency' => array('enable_custom_fields', '==', true),
                                    ),
                                    array(
                                        'id' => 'custom_fields_position',
                                        'type' => 'select',
                                        'title' => __('Custom Fields Position', 'shopglut'),
                                        'options' => array(
                                            'after_product_title' => __('After Product Title', 'shopglut'),
                                            'before_price' => __('Before Price', 'shopglut'),
                                            'after_description' => __('After Description', 'shopglut'),
                                            'after_add_to_cart' => __('After Add to Cart', 'shopglut'),
                                        ),
                                        'default' => 'after_description',
                                        'dependency' => array('enable_custom_fields', '==', true),
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
                                        'id' => 'price_background_color',
                                        'type' => 'color',
                                        'title' => __('Price Background Color', 'shopglut'),
                                        'default' => 'transparent',
                                    ),
                                    array(
                                        'id' => 'original_price_color',
                                        'type' => 'color',
                                        'title' => __('Original Price Color', 'shopglut'),
                                        'default' => '#9ca3af',
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

                            // Product Metadata Settings
                            array(
                                'id' => 'product_metadata_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Metadata', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_product_meta',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Metadata', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_categories',
                                        'type' => 'switcher',
                                        'title' => __('Show Categories', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'show_tags',
                                        'type' => 'switcher',
                                        'title' => __('Show Tags', 'shopglut'),
                                        'default' => true,
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'meta_label_color',
                                        'type' => 'color',
                                        'title' => __('Meta Label Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'meta_label_font_size',
                                        'type' => 'slider',
                                        'title' => __('Meta Label Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'meta_label_font_weight',
                                        'type' => 'select',
                                        'title' => __('Meta Label Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '500',
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'meta_link_color',
                                        'type' => 'color',
                                        'title' => __('Meta Link Color', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                    array(
                                        'id' => 'meta_link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Meta Link Hover Color', 'shopglut'),
                                        'default' => '#5a67d8',
                                        'dependency' => array('show_product_meta', '==', true),
                                    ),
                                ),
                            ),

                            // Social Share Settings
                            array(
                                'id' => 'social_share_settings',
                                'type' => 'fieldset',
                                'title' => __('Social Share', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_social_share',
                                        'type' => 'switcher',
                                        'title' => __('Enable Social Share', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'social_share_label',
                                        'type' => 'text',
                                        'title' => __('Social Share Label', 'shopglut'),
                                        'default' => __('Share:', 'shopglut'),
                                        'dependency' => array('enable_social_share', '==', true),
                                    ),
                                    array(
                                        'id' => 'social_share_icons',
                                        'type' => 'repeater',
                                        'title' => __('Social Share Icons', 'shopglut'),
                                        'button_title' => __('Add Icon', 'shopglut'),
                                        'dependency' => array('enable_social_share', '==', true),
                                        'fields' => array(
                                            array(
                                                'id' => 'social_icon',
                                                'type' => 'icon',
                                                'title' => __('Icon', 'shopglut'),
                                                'default' => 'fab fa-facebook-f',
                                            ),
                                            array(
                                                'id' => 'social_label',
                                                'type' => 'text',
                                                'title' => __('Label', 'shopglut'),
                                                'default' => __('Facebook', 'shopglut'),
                                            ),
                                            array(
                                                'id' => 'social_background',
                                                'type' => 'color',
                                                'title' => __('Background Color', 'shopglut'),
                                                'default' => '#1877f2',
                                            ),
                                            array(
                                                'id' => 'social_color',
                                                'type' => 'color',
                                                'title' => __('Icon Color', 'shopglut'),
                                                'default' => '#ffffff',
                                            ),
                                            array(
                                                'id' => 'social_hover_background',
                                                'type' => 'color',
                                                'title' => __('Hover Background', 'shopglut'),
                                                'default' => '#0e5f9e',
                                            ),
                                            array(
                                                'id' => 'social_hover_color',
                                                'type' => 'color',
                                                'title' => __('Hover Icon Color', 'shopglut'),
                                                'default' => '#ffffff',
                                            ),
                                            array(
                                                'id' => 'social_border_radius',
                                                'type' => 'slider',
                                                'title' => __('Border Radius', 'shopglut'),
                                                'unit' => 'px',
                                                'min' => 0,
                                                'max' => 25,
                                                'step' => 1,
                                                'default' => 6,
                                            ),
                                        ),
                                        'default' => array(
                                            array(
                                                'social_icon' => 'fab fa-facebook-f',
                                                'social_label' => 'Facebook',
                                                'social_background' => '#1877f2',
                                                'social_color' => '#ffffff',
                                                'social_hover_background' => '#0e5f9e',
                                                'social_hover_color' => '#ffffff',
                                                'social_border_radius' => 6,
                                            ),
                                            array(
                                                'social_icon' => 'fab fa-twitter',
                                                'social_label' => 'Twitter',
                                                'social_background' => '#1da1f2',
                                                'social_color' => '#ffffff',
                                                'social_hover_background' => '#0c85d0',
                                                'social_hover_color' => '#ffffff',
                                                'social_border_radius' => 6,
                                            ),
                                            array(
                                                'social_icon' => 'fab fa-pinterest-p',
                                                'social_label' => 'Pinterest',
                                                'social_background' => '#bd081c',
                                                'social_color' => '#ffffff',
                                                'social_hover_background' => '#8c0615',
                                                'social_hover_color' => '#ffffff',
                                                'social_border_radius' => 6,
                                            ),
                                            array(
                                                'social_icon' => 'fab fa-whatsapp',
                                                'social_label' => 'WhatsApp',
                                                'social_background' => '#25d366',
                                                'social_color' => '#ffffff',
                                                'social_hover_background' => '#128c7e',
                                                'social_hover_color' => '#ffffff',
                                                'social_border_radius' => 6,
                                            ),
                                        ),
                                    ),
                                    array(
                                        'id' => 'social_icon_size',
                                        'type' => 'slider',
                                        'title' => __('Icon Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 24,
                                        'max' => 48,
                                        'step' => 2,
                                        'default' => 36,
                                        'dependency' => array('enable_social_share', '==', true),
                                    ),
                                    array(
                                        'id' => 'social_icon_spacing',
                                        'type' => 'slider',
                                        'title' => __('Icon Spacing', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 4,
                                        'max' => 20,
                                        'step' => 2,
                                        'default' => 8,
                                        'dependency' => array('enable_social_share', '==', true),
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

                            // Product Swatches Module
                            array(
                                'id' => 'swatches_integration',
                                'type' => 'fieldset',
                                'title' => __('Product Swatches Module', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_swatches',
                                        'type' => 'switcher',
                                        'title' => __('Enable Product Swatches', 'shopglut'),
                                        'desc' => __('Replace default variation dropdowns with visual swatches', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'swatches_position',
                                        'type' => 'select',
                                        'title' => __('Swatches Position', 'shopglut'),
                                        'options' => array(
                                            'replace_attributes' => __('Replace Default Attributes', 'shopglut'),
                                            'after_attributes' => __('After Default Attributes', 'shopglut'),
                                            'before_add_to_cart' => __('Before Add to Cart', 'shopglut'),
                                        ),
                                        'default' => 'replace_attributes',
                                        'dependency' => array('enable_swatches', '==', true),
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
                                        'default' => false,
                                    ),
                                    array(
                                        'id' => 'comparison_layout_id',
                                        'type' => 'select_comparison_layouts',
                                        'title' => __('Select Comparison Layout', 'shopglut'),
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

                            // Buy Now Button
                            array(
                                'id' => 'buy_now_settings',
                                'type' => 'fieldset',
                                'title' => __('Buy Now Button', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_buy_now_button',
                                        'type' => 'switcher',
                                        'title' => __('Show Buy Now Button', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'buy_now_button_text',
                                        'type' => 'text',
                                        'title' => __('Button Text', 'shopglut'),
                                        'default' => __('Buy Now', 'shopglut'),
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_background',
                                        'type' => 'color',
                                        'title' => __('Button Background Color', 'shopglut'),
                                        'default' => '#f59e0b',
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Button Hover Background', 'shopglut'),
                                        'default' => '#d97706',
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_font_size',
                                        'type' => 'slider',
                                        'title' => __('Button Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 14,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_font_weight',
                                        'type' => 'select',
                                        'title' => __('Button Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_padding_top',
                                        'type' => 'slider',
                                        'title' => __('Button Top Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 25,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_button_padding_bottom',
                                        'type' => 'slider',
                                        'title' => __('Button Bottom Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 25,
                                        'step' => 1,
                                        'default' => 14,
                                        'dependency' => array('show_buy_now_button', '==', true),
                                    ),
                                ),
                            ),

                            // Buy Now Border
                            array(
                                'id' => 'buy_now_border_settings',
                                'type' => 'fieldset',
                                'title' => __('Buy Now Section Border', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_buy_now_border',
                                        'type' => 'switcher',
                                        'title' => __('Show Border After Buy Now', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'buy_now_border_color',
                                        'type' => 'color',
                                        'title' => __('Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                        'dependency' => array('show_buy_now_border', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_border_height',
                                        'type' => 'slider',
                                        'title' => __('Border Height', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 1,
                                        'max' => 5,
                                        'step' => 1,
                                        'default' => 1,
                                        'dependency' => array('show_buy_now_border', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_border_margin_top',
                                        'type' => 'slider',
                                        'title' => __('Border Top Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 24,
                                        'dependency' => array('show_buy_now_border', '==', true),
                                    ),
                                    array(
                                        'id' => 'buy_now_border_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Border Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 24,
                                        'dependency' => array('show_buy_now_border', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT TABS SETTINGS ====================
                    array(
                        'title' => __('Product Tabs', 'shopglut'),
                        'icon' => 'fas fa-clone',
                        'fields' => array(
                            array(
                                'id' => 'product_tabs_list',
                                'type' => 'repeater',
                                'title' => __('Product Tabs', 'shopglut'),
                                'button_title' => __('Add New Tab', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'tab_icon',
                                        'type' => 'icon',
                                        'title' => __('Tab Icon', 'shopglut'),
                                        'default' => 'fas fa-info-circle',
                                    ),
                                    array(
                                        'id' => 'tab_title',
                                        'type' => 'text',
                                        'title' => __('Tab Title', 'shopglut'),
                                        'default' => __('Custom Tab', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'tab_content',
                                        'type' => 'wp_editor',
                                        'title' => __('Tab Content', 'shopglut'),
                                        'default' => __('Your tab content goes here...', 'shopglut'),
                                    ),
                                ),
                                'default' => array(
                                    array(
                                        'tab_icon' => 'fas fa-shipping-fast',
                                        'tab_title' => __('Shipping Info', 'shopglut'),
                                        'tab_content' => __('Free shipping on all orders over $50. Delivery within 3-5 business days.', 'shopglut'),
                                    ),
                                    array(
                                        'tab_icon' => 'fas fa-undo',
                                        'tab_title' => __('Returns', 'shopglut'),
                                        'tab_content' => __('30-day hassle-free returns on all products.', 'shopglut'),
                                    ),
                                ),
                            ),

                            // Tab Icon Styling
                            array(
                                'id' => 'tab_icon_styling',
                                'type' => 'fieldset',
                                'title' => __('Tab Icon Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'tab_icon_size',
                                        'type' => 'slider',
                                        'title' => __('Tab Icon Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                    array(
                                        'id' => 'tab_icon_color',
                                        'type' => 'color',
                                        'title' => __('Tab Icon Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'tab_icon_hover_color',
                                        'type' => 'color',
                                        'title' => __('Tab Icon Hover Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'tab_icon_active_color',
                                        'type' => 'color',
                                        'title' => __('Tab Icon Active Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                ),
                            ),

                            // Tab Title Styling
                            array(
                                'id' => 'tab_title_styling',
                                'type' => 'fieldset',
                                'title' => __('Tab Title Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'tab_title_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'tab_title_hover_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Hover Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'tab_title_active_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Active Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'tab_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Tab Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 15,
                                    ),
                                    array(
                                        'id' => 'tab_title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Tab Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '500',
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
                                        'default' => __('Related Products', 'shopglut'),
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