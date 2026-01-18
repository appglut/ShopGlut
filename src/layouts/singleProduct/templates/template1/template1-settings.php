<?php
/**
 * Single Product Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 single product layout.
 * Template1 features:
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

$SHOPG_singleproduct_STYLING = "shopg_singleproduct_settings_template1";


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
        'title' => esc_html__('Template1 Single Product Settings', 'shopglut'),
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
                'title' => __('Template1 Configuration', 'shopglut'),
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
                                    )
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
                                        'default' => '#ffffff',
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
                                        'default' => true,
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
                                        'id' => 'feature_icon_display_mode',
                                        'type' => 'select',
                                        'title' => __('Icon Display Mode', 'shopglut'),
                                        'options' => array(
                                            'icon_only' => __('Icon Only', 'shopglut'),
                                            'icon_with_background' => __('Icon with Background', 'shopglut'),
                                            'icon_circle' => __('Icon in Circle', 'shopglut'),
                                        ),
                                        'default' => 'icon_with_background',
                                    ),
                                    array(
                                        'id' => 'feature_icon_width',
                                        'type' => 'slider',
                                        'title' => __('Icon Container Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 32,
                                        'max' => 80,
                                        'step' => 2,
                                        'default' => 48,
                                    ),
                                    array(
                                        'id' => 'feature_icon_height',
                                        'type' => 'slider',
                                        'title' => __('Icon Container Height', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 32,
                                        'max' => 80,
                                        'step' => 2,
                                        'default' => 48,
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
                                        'id' => 'tab_title_background_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Background Color', 'shopglut'),
                                        'default' => '#f3f4f6',
                                    ),
                                    array(
                                        'id' => 'tab_title_hover_background_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Hover Background Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'tab_title_active_background_color',
                                        'type' => 'color',
                                        'title' => __('Tab Title Active Background Color', 'shopglut'),
                                        'default' => '#ffffff',
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
                                    array(
                                        'id' => 'quick_add_button_hover_background',
                                        'type' => 'color',
                                        'title' => __('Quick Add Button Hover Background', 'shopglut'),
                                        'default' => '#5a67d8',
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