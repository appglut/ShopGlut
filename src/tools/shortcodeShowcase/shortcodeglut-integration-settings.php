<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$SHOPGLUT_TOOLS_SETTINGS = 'shopglut_tools_settings';

// Create options
AGSHOPGLUT::createOptions( $SHOPGLUT_TOOLS_SETTINGS, array(
	// menu settings
	'menu_title' => esc_html__( 'Settings', 'shopglut' ),
	'show_bar_menu' => false,
	'hide_menu' => false,
	'menu_slug' => 'shopglut_tools_settings',
	'menu_parent' => 'shopglut_layouts',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'Tools Settings', 'shopglut' ),
	'show_reset_section' => true,
	'framework_class' => 'shopglut_tools_settings',
	'footer_credit' => __( "ShopGlut", 'shopglut' ),
	'menu_position' => 6,
) );

// Create a top-tab
AGSHOPGLUT::createSection( $SHOPGLUT_TOOLS_SETTINGS, array(
	'id' => 'shortcodes_tab',
	'title' => __( 'ShortcodeGlut', 'shopglut' ),
	'icon' => 'fa fa-plug',
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $SHOPGLUT_TOOLS_SETTINGS, array(
	'parent' => 'shortcodes_tab',
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'shortcodeglut-show-menu',
			'type' => 'switcher',
			'title' => __( 'Show ShortcodeGlut Menu', 'shopglut' ),
			'desc' => __( 'When enabled, ShortcodeGlut will show its own admin menu even when ShopGlut is active.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );

// Allow other plugins to add settings
do_action( 'shopglut_tools_settings', $SHOPGLUT_TOOLS_SETTINGS );
