<?php
/**
 * Shop Banner Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 Shop Banner layout.
 * Template1 features:
 * - Banner content (title, description, image, button)
 * - Display settings (pages, positions, timing)
 * - Modal styling and appearance
 * - Banner behavior and triggers
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_SHOPBANNER_STYLING = "shopg_product_shopbanner_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_product_shopbanner_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'product_shopbanner',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_product_shopbanner_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main ShopBanner Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_SHOPBANNER_STYLING,
    array(
        'title' => esc_html__('Template1 Shop Banner Settings', 'shopglut'),
        'post_type' => 'product_shopbanner',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_SHOPBANNER_STYLING,
    array(
        'fields' => array(

            // Global Enable Setting (outside tabs)
            array(
                'id' => 'enable_shopbanner',
                'type' => 'switcher',
                'title' => __('Enable Shop Banner', 'shopglut'),
                'desc' => __('Enable or disable the Shop Banner feature', 'shopglut'),
                'default' => true,
            ),

             // Display Locations
            array(
                'id' => 'display-locations',
                'type' => 'select_banner_pages',
                'title' => __('Display Shop Banner On', 'shopglut'),
                'desc' => __('Select pages where the Shop Banner should appear. Each location can only be used by one layout.', 'shopglut'),
                'options' => 'select_banner_pages',
                'multiple' => true,
                'chosen' => true,
                'placeholder' => __('Select pages to show Shop Banner', 'shopglut'),
                'dependency' => array('enable_shopbanner', '==', true),

            ),

            // Organized Settings in Tabs
            array(
                'id' => 'shopbanner_settings_tabs',
                'type' => 'tabbed',
                'title' => __('Shop Banner Settings', 'shopglut'),
                'dependency' => array('enable_shopbanner', '==', true),
                'tabs' => array(

                    // Tab 1: Content
                    array(
                        'title' => __('Content', 'shopglut'),
                        'icon' => 'fas fa-edit',
                        'fields' => array(

                            // Banner Title
                            array(
                                'id' => 'banner_title',
                                'type' => 'text',
                                'title' => __('Banner Title', 'shopglut'),
                                'desc' => __('Enter the main title for your banner', 'shopglut'),
                                'default' => __('Special Offer!', 'shopglut'),
                            ),

                            // Banner Description
                            array(
                                'id' => 'banner_description',
                                'type' => 'textarea',
                                'title' => __('Banner Description', 'shopglut'),
                                'desc' => __('Enter the description text for your banner', 'shopglut'),
                                'default' => __('Check out our amazing deals and discounts on selected products!', 'shopglut'),
                                'attributes' => array('rows' => 3),
                            ),

                            // Banner Image
                            array(
                                'id' => 'banner_image',
                                'type' => 'media',
                                'title' => __('Banner Image', 'shopglut'),
                                'desc' => __('Upload an image for your banner (optional)', 'shopglut'),
                                'library' => 'image',
                                'url' => false,
                            ),

                            // Banner Button
                            array(
                                'id' => 'show_button',
                                'type' => 'switcher',
                                'title' => __('Show Button', 'shopglut'),
                                'default' => true,
                            ),

                            array(
                                'id' => 'banner_button_text',
                                'type' => 'text',
                                'title' => __('Button Text', 'shopglut'),
                                'default' => __('Shop Now', 'shopglut'),
                                'dependency' => array('show_button', '==', true),
                            ),

                            array(
                                'id' => 'banner_button_url',
                                'type' => 'text',
                                'title' => __('Button URL', 'shopglut'),
                                'desc' => __('Enter the URL where users will be redirected when clicking the button', 'shopglut'),
                                'default' => home_url('/'),
                                'dependency' => array('show_button', '==', true),
                            ),
                        ),
                    ),

                    // Tab 2: Display
                    array(
                        'title' => __('Display', 'shopglut'),
                        'icon' => 'fas fa-desktop',
                        'fields' => array(

                           

                            // Display Delay
                            array(
                                'id' => 'display_delay',
                                'type' => 'slider',
                                'title' => __('Display Delay', 'shopglut'),
                                'desc' => __('Time in milliseconds before the banner appears on page load', 'shopglut'),
                                'unit' => 'ms',
                                'min' => 0,
                                'max' => 10000,
                                'step' => 500,
                                'default' => 3000,
                            ),

                            // Banner Position
                            array(
                                'id' => 'banner_position',
                                'type' => 'select',
                                'title' => __('Banner Position', 'shopglut'),
                                'desc' => __('Where the banner should appear on the screen', 'shopglut'),
                                'options' => array(
                                    'center' => __('Center', 'shopglut'),
                                    'top' => __('Top', 'shopglut'),
                                    'bottom' => __('Bottom', 'shopglut'),
                                ),
                                'default' => 'center',
                            ),

                            // Show Once
                            array(
                                'id' => 'show_once',
                                'type' => 'switcher',
                                'title' => __('Show Once Per Session', 'shopglut'),
                                'desc' => __('Only show the banner once per user session', 'shopglut'),
                                'default' => false,
                            ),
                        ),
                    ),

                    // Tab 3: Styling
                    array(
                        'title' => __('Styling', 'shopglut'),
                        'icon' => 'fas fa-palette',
                        'fields' => array(

                            // Text Styling
                            array(
                                'id' => 'text_styling',
                                'type' => 'fieldset',
                                'title' => __('Text Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'title_color',
                                        'type' => 'color',
                                        'title' => __('Title Color', 'shopglut'),
                                        'default' => '#2c3e50',
                                    ),
                                    array(
                                        'id' => 'title_font_size',
                                        'type' => 'slider',
                                        'title' => __('Title Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 16,
                                        'max' => 48,
                                        'step' => 1,
                                        'default' => 28,
                                    ),
                                    array(
                                        'id' => 'description_color',
                                        'type' => 'color',
                                        'title' => __('Description Color', 'shopglut'),
                                        'default' => '#2c3e50',
                                    ),
                                    array(
                                        'id' => 'description_font_size',
                                        'type' => 'slider',
                                        'title' => __('Description Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 24,
                                        'step' => 1,
                                        'default' => 16,
                                    ),
                                ),
                            ),

                            // Banner Appearance
                            array(
                                'id' => 'banner_appearance',
                                'type' => 'fieldset',
                                'title' => __('Banner Appearance', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'banner_background_color',
                                        'type' => 'color',
                                        'title' => __('Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'border_width',
                                        'type' => 'slider',
                                        'title' => __('Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 10,
                                        'step' => 1,
                                        'default' => 2,
                                    ),
                                    array(
                                        'id' => 'border_color',
                                        'type' => 'color',
                                        'title' => __('Border Color', 'shopglut'),
                                        'default' => '#0073aa',
                                    ),
                                    array(
                                        'id' => 'border_radius',
                                        'type' => 'slider',
                                        'title' => __('Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'banner_padding',
                                        'type' => 'slider',
                                        'title' => __('Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 60,
                                        'step' => 5,
                                        'default' => 30,
                                    ),
                                ),
                            ),

                            // Button Styling
                            array(
                                'id' => 'button_styling',
                                'type' => 'fieldset',
                                'title' => __('Button Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Button Background', 'shopglut'),
                                        'default' => '#0073aa',
                                        'dependency' => array('show_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_text_color',
                                        'type' => 'color',
                                        'title' => __('Button Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                        'dependency' => array('show_button', '==', true),
                                    ),
                                    array(
                                        'id' => 'button_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Button Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                        'default' => 25,
                                        'dependency' => array('show_button', '==', true),
                                    ),
                                ),
                                'dependency' => array('show_button', '==', true),
                            ),

                            // Image Styling
                            array(
                                'id' => 'image_styling',
                                'type' => 'fieldset',
                                'title' => __('Image Styling', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'image_max_width',
                                        'type' => 'slider',
                                        'title' => __('Image Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 100,
                                        'max' => 400,
                                        'step' => 10,
                                        'default' => 200,
                                    ),
                                    array(
                                        'id' => 'image_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Image Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                ),
                            ),

                            // Modal Settings
                            array(
                                'id' => 'modal_styling',
                                'type' => 'fieldset',
                                'title' => __('Modal Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'modal_overlay_color',
                                        'type' => 'color',
                                        'title' => __('Overlay Color', 'shopglut'),
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
                                    array(
                                        'id' => 'modal_max_width',
                                        'type' => 'slider',
                                        'title' => __('Modal Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 300,
                                        'max' => 800,
                                        'step' => 50,
                                        'default' => 600,
                                    ),
                                    array(
                                        'id' => 'close_button_size',
                                        'type' => 'slider',
                                        'title' => __('Close Button Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 50,
                                        'step' => 2,
                                        'default' => 40,
                                    ),
                                    array(
                                        'id' => 'close_button_color',
                                        'type' => 'color',
                                        'title' => __('Close Button Color', 'shopglut'),
                                        'default' => '#2c3e50',
                                    ),
                                    array(
                                        'id' => 'close_button_bg_color',
                                        'type' => 'color',
                                        'title' => __('Close Button Background', 'shopglut'),
                                        'default' => '#ffffff',
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
