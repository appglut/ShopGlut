<?php
/**
 * Single Product template3 Specific Settings
 *
 * This file contains settings specifically designed for template3 single product layout.
 * template3 features:
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

$SHOPG_singleproduct_STYLING = "shopg_singleproduct_settings_template3";


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
        'title' => esc_html__('template3 Single Product Settings', 'shopglut'),
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
                'title' => __('template3 Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== PRODUCT GALLERY SETTINGS ====================
                    array(
                        'title' => __('Product Gallery', 'shopglut'),
                        'icon' => 'fas fa-images',
                        'fields' => array(

                            // Gallery Section Settings
                            array(
                                'id' => 'gallery_section_settings',
                                'type' => 'fieldset',
                                'title' => __('Gallery Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'gallery_section_margin',
                                        'type' => 'slider',
                                        'title' => __('Gallery Section Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 5,
                                        'default' => 40,
                                    ),
                                ),
                            ),

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
                                    array(
                                        'id' => 'main_image_object_fit',
                                        'type' => 'select',
                                        'title' => __('Main Image Object Fit', 'shopglut'),
                                        'options' => array(
                                            'cover' => __('Cover', 'shopglut'),
                                            'contain' => __('Contain', 'shopglut'),
                                            'fill' => __('Fill', 'shopglut'),
                                        ),
                                        'default' => 'cover',
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
                                    array(
                                        'id' => 'thumbnail_hover_scale',
                                        'type' => 'slider',
                                        'title' => __('Thumbnail Hover Scale', 'shopglut'),
                                        'unit' => '',
                                        'min' => 1.0,
                                        'max' => 1.2,
                                        'step' => 0.01,
                                        'default' => 1.05,
                                        'dependency' => array('show_thumbnails', '==', true),
                                    ),
                                    array(
                                        'id' => 'thumbnail_object_fit',
                                        'type' => 'select',
                                        'title' => __('Thumbnail Object Fit', 'shopglut'),
                                        'options' => array(
                                            'cover' => __('Cover', 'shopglut'),
                                            'contain' => __('Contain', 'shopglut'),
                                            'fill' => __('Fill', 'shopglut'),
                                        ),
                                        'default' => 'cover',
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
                                        'id' => 'wishlist_layout_id',
                                        'type' => 'select_wishlist_layouts',
                                        'title' => __('Select Wishlist Layout', 'shopglut'),
                                        'placeholder' => __('Select a wishlist layout', 'shopglut'),
                                        'default' => '',
                                        'dependency' => array('enable_wishlist', '==', true),
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

                    // ==================== PRODUCT DESCRIPTION SETTINGS ====================
                    array(
                        'title' => __('Product Description', 'shopglut'),
                        'icon' => 'fas fa-file-alt',
                        'fields' => array(

                            // Description Section Layout
                            array(
                                'id' => 'product_description_section_settings',
                                'type' => 'fieldset',
                                'title' => __('Description Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_description_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Description Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'description_section_padding',
                                        'type' => 'slider',
                                        'title' => __('Section Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 80,
                                        'step' => 5,
                                        'default' => 40,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_section_background',
                                        'type' => 'color',
                                        'title' => __('Section Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_section_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Section Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 0,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                ),
                            ),

                            // Description Title Styling
                            array(
                                'id' => 'description_title_settings',
                                'type' => 'fieldset',
                                'title' => __('Description Title', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'description_title_text',
                                        'type' => 'text',
                                        'title' => __('Title Text', 'shopglut'),
                                        'default' => __('Product Description', 'shopglut'),
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_title_color',
                                        'type' => 'color',
                                        'title' => __('Title Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 18,
                                        'max' => 40,
                                        'step' => 1,
                                        'default' => 28,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_title_font_weight',
                                        'type' => 'select',
                                        'title' => __('Title Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_title_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Title Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 20,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                ),
                            ),

                            // Description Content Styling
                            array(
                                'id' => 'description_content_settings',
                                'type' => 'fieldset',
                                'title' => __('Description Content', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'description_content_color',
                                        'type' => 'color',
                                        'title' => __('Content Text Color', 'shopglut'),
                                        'default' => '#4b5563',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_content_font_size',
                                        'type' => 'slider',
                                        'title' => __('Content Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 16,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_content_line_height',
                                        'type' => 'slider',
                                        'title' => __('Content Line Height', 'shopglut'),
                                        'unit' => '',
                                        'min' => 1.2,
                                        'max' => 2.2,
                                        'step' => 0.1,
                                        'default' => 1.7,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_heading_color',
                                        'type' => 'color',
                                        'title' => __('Heading Color (h3, h4)', 'shopglut'),
                                        'default' => '#1f2937',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_heading_font_size',
                                        'type' => 'slider',
                                        'title' => __('Heading Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 28,
                                        'step' => 1,
                                        'default' => 20,
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'description_list_style',
                                        'type' => 'select',
                                        'title' => __('List Style', 'shopglut'),
                                        'options' => array(
                                            'disc' => __('Bullet Points (disc)', 'shopglut'),
                                            'circle' => __('Circle (circle)', 'shopglut'),
                                            'square' => __('Square (square)', 'shopglut'),
                                            'decimal' => __('Numbers (decimal)', 'shopglut'),
                                            'none' => __('None', 'shopglut'),
                                        ),
                                        'default' => 'disc',
                                        'dependency' => array('show_description_section', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== PRODUCT REVIEWS SETTINGS ====================
                    array(
                        'title' => __('Product Reviews', 'shopglut'),
                        'icon' => 'fas fa-star',
                        'fields' => array(

                            // Reviews Section Layout
                            array(
                                'id' => 'reviews_section_settings',
                                'type' => 'fieldset',
                                'title' => __('Reviews Section Layout', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_reviews_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Reviews Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'reviews_section_padding',
                                        'type' => 'slider',
                                        'title' => __('Section Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 80,
                                        'step' => 5,
                                        'default' => 40,
                                        'dependency' => array('show_reviews_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'reviews_section_background',
                                        'type' => 'color',
                                        'title' => __('Section Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_reviews_section', '==', true),
                                    ),
                                    array(
                                        'id' => 'reviews_section_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Section Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 0,
                                        'dependency' => array('show_reviews_section', '==', true),
                                    ),
                                ),
                            ),

                            // Reviews Header Styling
                            array(
                                'id' => 'reviews_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Reviews Header', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'reviews_header_text',
                                        'type' => 'text',
                                        'title' => __('Header Text', 'shopglut'),
                                        'default' => __('Customer Reviews', 'shopglut'),
                                    ),
                                    array(
                                        'id' => 'reviews_header_color',
                                        'type' => 'color',
                                        'title' => __('Header Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'reviews_header_font_size',
                                        'type' => 'slider',
                                        'title' => __('Header Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 18,
                                        'max' => 40,
                                        'step' => 1,
                                        'default' => 28,
                                    ),
                                    array(
                                        'id' => 'reviews_header_font_weight',
                                        'type' => 'select',
                                        'title' => __('Header Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                    array(
                                        'id' => 'reviews_header_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Header Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 24,
                                    ),
                                ),
                            ),

                            // Review Card Styling
                            array(
                                'id' => 'review_card_settings',
                                'type' => 'fieldset',
                                'title' => __('Review Cards', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'review_card_background',
                                        'type' => 'color',
                                        'title' => __('Card Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'review_card_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Card Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'review_card_padding',
                                        'type' => 'slider',
                                        'title' => __('Card Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 20,
                                    ),
                                    array(
                                        'id' => 'review_card_margin_bottom',
                                        'type' => 'slider',
                                        'title' => __('Card Bottom Margin', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 16,
                                    ),
                                ),
                            ),

                            // Reviewer Info Styling
                            array(
                                'id' => 'reviewer_info_settings',
                                'type' => 'fieldset',
                                'title' => __('Reviewer Information', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'reviewer_avatar_background',
                                        'type' => 'color',
                                        'title' => __('Avatar Background', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'reviewer_avatar_text_color',
                                        'type' => 'color',
                                        'title' => __('Avatar Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'reviewer_name_color',
                                        'type' => 'color',
                                        'title' => __('Reviewer Name Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'reviewer_name_font_weight',
                                        'type' => 'select',
                                        'title' => __('Reviewer Name Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '600',
                                    ),
                                    array(
                                        'id' => 'review_date_color',
                                        'type' => 'color',
                                        'title' => __('Review Date Color', 'shopglut'),
                                        'default' => '#9ca3af',
                                    ),
                                    array(
                                        'id' => 'review_date_font_size',
                                        'type' => 'slider',
                                        'title' => __('Review Date Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                ),
                            ),

                            // Review Rating Stars
                            array(
                                'id' => 'review_rating_settings',
                                'type' => 'fieldset',
                                'title' => __('Review Rating Stars', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'review_star_color',
                                        'type' => 'color',
                                        'title' => __('Star Color', 'shopglut'),
                                        'default' => '#fbbf24',
                                    ),
                                    array(
                                        'id' => 'review_star_size',
                                        'type' => 'slider',
                                        'title' => __('Star Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                ),
                            ),

                            // Review Text Styling
                            array(
                                'id' => 'review_text_settings',
                                'type' => 'fieldset',
                                'title' => __('Review Text', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'review_text_color',
                                        'type' => 'color',
                                        'title' => __('Review Text Color', 'shopglut'),
                                        'default' => '#4b5563',
                                    ),
                                    array(
                                        'id' => 'review_text_font_size',
                                        'type' => 'slider',
                                        'title' => __('Review Text Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'review_text_line_height',
                                        'type' => 'slider',
                                        'title' => __('Review Text Line Height', 'shopglut'),
                                        'unit' => '',
                                        'min' => 1.2,
                                        'max' => 2.0,
                                        'step' => 0.1,
                                        'default' => 1.6,
                                    ),
                                ),
                            ),

                            // Review Form Styling
                            array(
                                'id' => 'review_form_settings',
                                'type' => 'fieldset',
                                'title' => __('Review Form', 'shopglut'),
                                'dependency' => array('show_reviews_section', '==', true, true),
                                'fields' => array(
                                    array(
                                        'id' => 'show_review_form',
                                        'type' => 'switcher',
                                        'title' => __('Show Review Form', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'review_form_title',
                                        'type' => 'text',
                                        'title' => __('Form Title', 'shopglut'),
                                        'default' => __('Write Your Review', 'shopglut'),
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_title_color',
                                        'type' => 'color',
                                        'title' => __('Form Title Color', 'shopglut'),
                                        'default' => '#111827',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Form Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 32,
                                        'step' => 1,
                                        'default' => 22,
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_background',
                                        'type' => 'color',
                                        'title' => __('Form Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Form Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 12,
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_padding',
                                        'type' => 'slider',
                                        'title' => __('Form Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 40,
                                        'step' => 2,
                                        'default' => 24,
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_label_color',
                                        'type' => 'color',
                                        'title' => __('Form Label Color', 'shopglut'),
                                        'default' => '#374151',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_input_background',
                                        'type' => 'color',
                                        'title' => __('Form Input Background', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_input_border_color',
                                        'type' => 'color',
                                        'title' => __('Form Input Border Color', 'shopglut'),
                                        'default' => '#d1d5db',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'review_form_input_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Form Input Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 15,
                                        'step' => 1,
                                        'default' => 6,
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'submit_button_background',
                                        'type' => 'color',
                                        'title' => __('Submit Button Background', 'shopglut'),
                                        'default' => '#667eea',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'submit_button_text_color',
                                        'type' => 'color',
                                        'title' => __('Submit Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                    array(
                                        'id' => 'submit_button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Submit Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 6,
                                        'dependency' => array('show_review_form', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== SOCIAL SHARE SETTINGS ====================
                    array(
                        'title' => __('Social Share', 'shopglut'),
                        'icon' => 'fas fa-share-alt',
                        'fields' => array(

                            // Social Share Settings
                            array(
                                'id' => 'social_share_settings',
                                'type' => 'fieldset',
                                'title' => __('Social Share Settings', 'shopglut'),
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
                                        'default' => 'Share:',
                                        'dependency' => array('enable_social_share', '==', true),
                                    ),
                                    array(
                                        'id' => 'social_share_icons',
                                        'type' => 'repeater',
                                        'title' => __('Social Icons', 'shopglut'),
                                        'button_label' => __('Add Social Icon', 'shopglut'),
                                        'dependency' => array('enable_social_share', '==', true),
                                        'fields' => array(
                                            array(
                                                'id' => 'social_icon',
                                                'type' => 'text',
                                                'title' => __('Icon Class', 'shopglut'),
                                                'default' => 'fab fa-facebook-f',
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
                                                'title' => __('Hover Background Color', 'shopglut'),
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
                                                'max' => 20,
                                                'step' => 1,
                                                'default' => 6,
                                            ),
                                        ),
                                    ),
                                    array(
                                        'id' => 'social_icon_size',
                                        'type' => 'slider',
                                        'title' => __('Icon Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 30,
                                        'max' => 50,
                                        'step' => 2,
                                        'default' => 40,
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
                                        'default' => 10,
                                        'dependency' => array('enable_social_share', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== DEMO SECTION VISIBILITY SETTINGS ====================
                    array(
                        'title' => __('Demo Section Visibility', 'shopglut'),
                        'icon' => 'fas fa-eye',
                        'fields' => array(

                            // Product Elements Visibility
                            array(
                                'id' => 'demo_visibility_settings',
                                'type' => 'fieldset',
                                'title' => __('Product Elements Visibility', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_demo_badges',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Badges', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_title',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Title', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_rating',
                                        'type' => 'switcher',
                                        'title' => __('Show Rating Stars', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_price',
                                        'type' => 'switcher',
                                        'title' => __('Show Price Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_description',
                                        'type' => 'switcher',
                                        'title' => __('Show Short Description', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_variations',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Variations (Color/Size)', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_cart_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Cart Section (Quantity, Add to Cart)', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_action_buttons',
                                        'type' => 'switcher',
                                        'title' => __('Show Action Buttons (Wishlist, Compare)', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_social_share',
                                        'type' => 'switcher',
                                        'title' => __('Show Social Share Icons', 'shopglut'),
                                        'default' => true,
                                    ),
                                ),
                            ),

                            // Section Visibility
                            array(
                                'id' => 'demo_section_visibility',
                                'type' => 'fieldset',
                                'title' => __('Section Visibility', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'show_demo_description_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Product Description Section', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'show_demo_reviews_section',
                                        'type' => 'switcher',
                                        'title' => __('Show Reviews Section', 'shopglut'),
                                        'default' => true,
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