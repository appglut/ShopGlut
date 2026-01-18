<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Check if mini_cart module is enabled
$module_manager = \Shopglut\ModuleManager::get_instance();
if ( ! $module_manager->is_module_enabled( 'mini_cart' ) ) {
	return; // Don't create the settings page if module is disabled
}

// Set a unique slug-like ID
$AGSHOPGLUT_MINICART_OPTIONS = 'agshopglut_minicart_options';

// Create Mini Cart options
AGSHOPGLUT::createOptions( $AGSHOPGLUT_MINICART_OPTIONS, array(
	// menu settings
	'menu_title' => esc_html__( '- Mini Cart', 'shopglut' ),
	'show_bar_menu' => false,
	'menu_slug' => 'shopglut_minicart_settings',
	'menu_parent' => 'shopglut_layouts',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'Mini Cart Options', 'shopglut' ),
	'show_reset_section' => true,
	'framework_class' => 'shopglut_minicart_settings',
	'footer_credit' => __( "ShopGlut (Mini Cart)", 'shopglut' ),
	'menu_position' => 8
) );

//
// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'id' => 'primary_tab', // Set a unique slug-like ID
	'title' => __( 'Settings', 'shopglut' ),
	'icon' => 'fa fa-cog',
) );

// Create a sub-tab - General Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'enable-mini-cart',
			'type' => 'switcher',
			'title' => __( 'Enable Custom Mini Cart', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'show-floating-cart',
			'type' => 'switcher',
			'title' => __( 'Show Floating Cart Icon', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'show-in-menu',
			'type' => 'switcher',
			'title' => __( 'Add to Navigation Menu', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'auto-open-on-add',
			'type' => 'switcher',
			'title' => __( 'Auto-open on Product Add', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'show-product-images',
			'type' => 'switcher',
			'title' => __( 'Show Product Images', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'enable-quantity-controls',
			'type' => 'switcher',
			'title' => __( 'Quantity +/- Controls', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'show-shipping-calculator',
			'type' => 'switcher',
			'title' => __( 'Shipping Calculator', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'enable-cart-share',
			'type' => 'switcher',
			'title' => __( 'Enable Cart Sharing', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'show-continue-shopping',
			'type' => 'switcher',
			'title' => __( 'Continue Shopping Button', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'cart-position',
			'type' => 'button_set',
			'title' => __( 'Cart Position', 'shopglut' ),
			'options' => array(
				'right' => __( 'Right Side', 'shopglut' ),
				'left' => __( 'Left Side', 'shopglut' ),
			),
			'default' => 'right',
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'cart-icon-style',
			'type' => 'button_set',
			'title' => __( 'Icon Style', 'shopglut' ),
			'options' => array(
				'cart' => __( 'Shopping Cart', 'shopglut' ),
				'bag' => __( 'Shopping Bag', 'shopglut' ),
				'basket' => __( 'Basket', 'shopglut' ),
			),
			'default' => 'cart',
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'animation-style',
			'type' => 'select',
			'title' => __( 'Animation Style', 'shopglut' ),
			'options' => array(
				'slide' => __( 'Slide', 'shopglut' ),
				'fade' => __( 'Fade', 'shopglut' ),
				'scale' => __( 'Scale', 'shopglut' ),
			),
			'default' => 'slide',
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'cart-width',
			'type' => 'spinner',
			'title' => __( 'Cart Width', 'shopglut' ),
			'unit' => 'px',
			'default' => 400,
			'min' => 300,
			'max' => 600,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

		array(
			'id' => 'auto-close-time',
			'type' => 'spinner',
			'title' => __( 'Auto Close Time', 'shopglut' ),
			'subtitle' => __( 'Auto close after (seconds, 0=disabled)', 'shopglut' ),
			'unit' => 'seconds',
			'default' => 5,
			'min' => 0,
			'max' => 30,
			'dependency' => array( 'enable-mini-cart', '==', 'true' ),
		),

	)
) );

// Create a sub-tab - Cart Share Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Cart Share', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'cart-share-enable',
			'type' => 'switcher',
			'title' => __( 'Enable Cart Share Feature', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'cart-share-email-subject',
			'type' => 'text',
			'title' => __( 'Email Subject', 'shopglut' ),
			'default' => __( 'Cart shared by {name}', 'shopglut' ),
			'desc' => __( 'Use {name} to include sender name', 'shopglut' ),
			'dependency' => array( 'cart-share-enable', '==', 'true' ),
		),

		array(
			'id' => 'cart-share-button-text',
			'type' => 'text',
			'title' => __( 'Share Button Text', 'shopglut' ),
			'default' => __( 'Share Cart', 'shopglut' ),
			'dependency' => array( 'cart-share-enable', '==', 'true' ),
		),

		array(
			'id' => 'cart-share-button-icon',
			'type' => 'icon',
			'title' => __( 'Share Button Icon', 'shopglut' ),
			'default' => 'fa-solid fa-share-nodes',
			'dependency' => array( 'cart-share-enable', '==', 'true' ),
		),

	)
) );

// Create a top-tab for Appearance
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'id' => 'appearance_tab',
	'title' => __( 'Appearance', 'shopglut' ),
	'icon' => 'fa fa-palette',
) );

// Create a sub-tab - Colors
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'appearance_tab',
	'title' => __( 'Colors', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'primary-color',
			'type' => 'color',
			'title' => __( 'Primary Color', 'shopglut' ),
			'default' => '#2271b1',
		),

		array(
			'id' => 'background-color',
			'type' => 'color',
			'title' => __( 'Background Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'text-color',
			'type' => 'color',
			'title' => __( 'Text Color', 'shopglut' ),
			'default' => '#333333',
		),

		array(
			'id' => 'border-color',
			'type' => 'color',
			'title' => __( 'Border Color', 'shopglut' ),
			'default' => '#e0e0e0',
		),

		array(
			'id' => 'button-hover-color',
			'type' => 'color',
			'title' => __( 'Button Hover Color', 'shopglut' ),
			'default' => '#135e96',
		),

	)
) );

// Create a sub-tab - Floating Icon
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'appearance_tab',
	'title' => __( 'Floating Icon', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'floating-icon-size',
			'type' => 'slider',
			'title' => __( 'Icon Size', 'shopglut' ),
			'default' => 24,
			'min' => 16,
			'max' => 48,
			'step' => 1,
			'unit' => 'px',
		),

		array(
			'id' => 'floating-icon-bg-color',
			'type' => 'color',
			'title' => __( 'Icon Background Color', 'shopglut' ),
			'default' => '#2271b1',
		),

		array(
			'id' => 'floating-icon-color',
			'type' => 'color',
			'title' => __( 'Icon Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'floating-icon-badge-color',
			'type' => 'color',
			'title' => __( 'Badge Background Color', 'shopglut' ),
			'default' => '#ff4444',
		),

		array(
			'id' => 'floating-icon-badge-text-color',
			'type' => 'color',
			'title' => __( 'Badge Text Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'floating-icon-position-bottom',
			'type' => 'slider',
			'title' => __( 'Position from Bottom', 'shopglut' ),
			'default' => 30,
			'min' => 0,
			'max' => 200,
			'step' => 5,
			'unit' => 'px',
		),

		array(
			'id' => 'floating-icon-position-side',
			'type' => 'slider',
			'title' => __( 'Position from Side', 'shopglut' ),
			'default' => 30,
			'min' => 0,
			'max' => 200,
			'step' => 5,
			'unit' => 'px',
		),

	)
) );

// Create a sub-tab - Cart Sidebar
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'appearance_tab',
	'title' => __( 'Cart Sidebar', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'sidebar-header-bg',
			'type' => 'color',
			'title' => __( 'Header Background', 'shopglut' ),
			'default' => '#2271b1',
		),

		array(
			'id' => 'sidebar-header-text',
			'type' => 'color',
			'title' => __( 'Header Text Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'cart-item-border',
			'type' => 'color',
			'title' => __( 'Cart Item Border', 'shopglut' ),
			'default' => '#e0e0e0',
		),

		array(
			'id' => 'cart-item-hover-bg',
			'type' => 'color',
			'title' => __( 'Cart Item Hover Background', 'shopglut' ),
			'default' => '#f9f9f9',
		),

		array(
			'id' => 'remove-button-color',
			'type' => 'color',
			'title' => __( 'Remove Button Color', 'shopglut' ),
			'default' => '#ff4444',
		),

		array(
			'id' => 'checkout-button-bg',
			'type' => 'color',
			'title' => __( 'Checkout Button Background', 'shopglut' ),
			'default' => '#2271b1',
		),

		array(
			'id' => 'checkout-button-text',
			'type' => 'color',
			'title' => __( 'Checkout Button Text', 'shopglut' ),
			'default' => '#ffffff',
		),

	)
) );

// Create a sub-tab - Custom CSS
AGSHOPGLUT::createSection( $AGSHOPGLUT_MINICART_OPTIONS, array(
	'parent' => 'appearance_tab',
	'title' => __( 'Custom CSS', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'custom-css',
			'type' => 'textarea',
			'title' => __( 'Custom CSS', 'shopglut' ),
			'subtitle' => __( 'Add your custom CSS here', 'shopglut' ),
			'default' => '/* Add your custom CSS here */',
			'attributes' => array(
				'rows' => 10,
				'style' => 'font-family: monospace;'
			),
		),

	)
) );

// Allow pro plugin to add settings
do_action( 'shopglut_minicart_pro_settings', $AGSHOPGLUT_MINICART_OPTIONS, $this );
