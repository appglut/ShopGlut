<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$SHOPGLUT_TOOLS_SETTINGS = 'shopglut_tools_settings';

// Create a top-tab for PosGlut
AGSHOPGLUT::createSection( $SHOPGLUT_TOOLS_SETTINGS, array(
	'id' => 'posglut_tab',
	'title' => __( 'PosGlut', 'shopglut' ),
	'icon' => 'fa fa-shopping-cart',
	'priority' => 25,
) );

// Create a sub-tab for PosGlut General settings
AGSHOPGLUT::createSection( $SHOPGLUT_TOOLS_SETTINGS, array(
	'parent' => 'posglut_tab',
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'posglut-show-menu',
			'type' => 'switcher',
			'title' => __( 'Show PosGlut Menu', 'shopglut' ),
			'desc' => __( 'When enabled, PosGlut will show its own admin menu even when ShopGlut is active.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'posglut-enable-barcode',
			'type' => 'switcher',
			'title' => __( 'Enable Barcode Scanner', 'shopglut' ),
			'desc' => __( 'Enable barcode scanning functionality in POS interface.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'posglut-auto-print-receipt',
			'type' => 'switcher',
			'title' => __( 'Auto Print Receipt', 'shopglut' ),
			'desc' => __( 'Automatically print receipts after each sale.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'posglut-receipt-header',
			'type' => 'textarea',
			'title' => __( 'Receipt Header', 'shopglut' ),
			'desc' => __( 'Custom header text for POS receipts.', 'shopglut' ),
			'default' => get_bloginfo( 'name' ),
		),

		array(
			'id' => 'posglut-receipt-footer',
			'type' => 'textarea',
			'title' => __( 'Receipt Footer', 'shopglut' ),
			'desc' => __( 'Custom footer text for POS receipts.', 'shopglut' ),
			'default' => __( 'Thank you for your purchase!', 'shopglut' ),
		),

	)
) );

// Create a sub-tab for PosGlut Payment Settings
AGSHOPGLUT::createSection( $SHOPGLUT_TOOLS_SETTINGS, array(
	'parent' => 'posglut_tab',
	'title' => __( 'Payments', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'posglut-enabled-payments',
			'type' => 'checkbox',
			'title' => __( 'Enabled Payment Methods', 'shopglut' ),
			'desc' => __( 'Select which payment methods are available in POS.', 'shopglut' ),
			'options' => array(
				'pos_cash' => __( 'Cash', 'shopglut' ),
				'pos_card' => __( 'Card', 'shopglut' ),
			),
			'default' => array(
				'pos_cash' => 'pos_cash',
				'pos_card' => 'pos_card',
			),
		),

		array(
			'id' => 'posglut-cash-rounding',
			'type' => 'switcher',
			'title' => __( 'Enable Cash Rounding', 'shopglut' ),
			'desc' => __( 'Round cash payments to the nearest coin.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'posglut-rounding-precision',
			'type' => 'select',
			'title' => __( 'Rounding Precision', 'shopglut' ),
			'desc' => __( 'Round to the nearest specified amount.', 'shopglut' ),
			'options' => array(
				'0.01' => __( '0.01 (Cent)', 'shopglut' ),
				'0.05' => __( '0.05 (Nickel)', 'shopglut' ),
				'0.10' => __( '0.10 (Dime)', 'shopglut' ),
				'0.25' => __( '0.25 (Quarter)', 'shopglut' ),
				'0.50' => __( '0.50 (Half dollar)', 'shopglut' ),
				'1.00' => __( '1.00 (Dollar)', 'shopglut' ),
			),
			'default' => '0.01',
			'dependency' => array( 'posglut-cash-rounding', '==', '1' ),
		),

		array(
			'id' => 'posglut-allow-partial-payment',
			'type' => 'switcher',
			'title' => __( 'Allow Split Payments', 'shopglut' ),
			'desc' => __( 'Allow customers to pay with multiple payment methods.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'posglut-require-refund-reason',
			'type' => 'switcher',
			'title' => __( 'Require Refund Reason', 'shopglut' ),
			'desc' => __( 'Require a reason when processing refunds.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

	)
) );

// Allow other plugins to add settings
do_action( 'shopglut_tools_settings', $SHOPGLUT_TOOLS_SETTINGS );
