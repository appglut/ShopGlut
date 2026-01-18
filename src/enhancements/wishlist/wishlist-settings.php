<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$AGSHOPGLUT_WISHLIST_OPTIONS = 'agshopglut_wishlist_options';

// Create Woo options
AGSHOPGLUT::createOptions( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	// menu settings
	'menu_title' => esc_html__( 'Wishlist Options', 'shopglut' ),
	'show_bar_menu' => false,
	'hide_menu' => true,
	'menu_slug' => 'shopglut_wishlist_settings',
	'menu_parent' => 'shopglut_layouts',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'Wishlist Options', 'shopglut' ),
	'show_reset_section' => true,
	'shortcode_option' => '[shopglut_wishlist]',
	'framework_class' => 'shopglut_wishlist_settings',
	'footer_credit' => __( "ShopGlut (WIshlist)", 'shopglut' ),
	 'menu_position' => 3
) );

//
// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'id' => 'primary_tab', // Set a unique slug-like ID
	'title' => __( 'Settings', 'shopglut' ),
	'icon' => 'fa fa-cog',
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab', // The slug id of the parent section
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-require-login',
			'type' => 'switcher',
			'title' => __( 'Require Login', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),


		array(
			'id' => 'wishlist-require-login-btn-text',
			'type' => 'text',
			'title' => __( 'Require Login Button Text', 'shopglut' ),
			'default' => 'Wishlist Require Login',
			'dependency' => array( 'wishlist-require-login', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-require-login-btn-icon',
			'type' => 'icon',
			'title' => __( 'Require Login Button Icon', 'shopglut' ),
			'default' => 'fa-solid fa-lock',
			'dependency' => array( 'wishlist-require-login', '==', 'true' ),
		),

	
		array(
			'id' => 'wishlist-merge-guestlist',
			'type' => 'switcher',
			'title' => __( 'Merge Guest Wishlist After Login', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'wishlist-require-login', '==', 'false' ),
		),

		array(
			'id' => 'wishlist-guestlist-deletetime',
			'type'    => 'spinner',
			'title' => __( 'Guest Wishlist Delete After', 'shopglut' ),
			'unit'    => 'days',
			'default' => 15,
			'dependency' => array( 'wishlist-require-login', '==', 'false' ),
		),

		array(
			'id' => 'wishlist-general-page',
			'type' => 'select',
			'title' => esc_html__( 'Wishlist Page', 'shopglut' ),
			'options' => 'pages',
			'query_args' => array(
				'posts_per_page' => -1, 
			),
		),

		array(
			'id' => 'wishlist-enable-share-qr',
			'type' => 'switcher',
			'title' => __( 'Enable Share Via QR', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),
		
		array(
			'id' => 'wishlist-enable-print-wish',
			'type' => 'switcher',
			'title' => __( 'Enable Print WIshlist', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

			array(
			'id' => 'wishlist-enable-other-wishlist',
			'type' => 'switcher',
			'title' => __( 'Display Others Wishlist', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

	   	array(
            'id' => 'wishlist-enable-menu-btn',
            'type' => 'switcher',
            'title' => __( 'Enable Menu Button', 'shopglut' ),
            'desc' => __( 'Wishlist Counter will show on Primary Menu', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
        ),

        	array(
            'id' => 'wishlist-menu-btn-text',
            'type' => 'text',
            'title' => __( 'Menu Button Text', 'shopglut' ),
            'default' => __( 'Wishlist', 'shopglut' ),
            'dependency' => array( 'wishlist-enable-menu-btn', '==', 'true' ),
       ),

        	array(
            'id' => 'wishlist-menu-btn-icon',
            'type' => 'icon',
            'title' => __( 'Menu Button Icon', 'shopglut' ),
            'default' => 'fa-solid fa-heart',
            'dependency' => array( 'wishlist-enable-menu-btn', '==', 'true' ),
       ),
        
        	array(
            'id' => 'wishlist-page-account-page',
            'type' => 'switcher',
            'title' => __( 'Enable Wishlist in Account Page', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
       ),
        
        	array(
            'id' => 'wishlist-page-account-page-name',
            'type' => 'text',
            'title' => __( 'Account Page Name', 'shopglut' ),
            'desc' => __( 'After change Save again Settings > Permalinks', 'shopglut' ),
            'default' => __( 'My Wishlist', 'shopglut' ),
            'dependency' => [ 'wishlist-page-account-page', '==', '1' ],
        ),

		array(
			'id' => 'wishlist-general-notification',
			'type' => 'select',
			'title' => __( 'Wishlist after Added Notification', 'shopglut' ),
			'options' => array(
				'notification-off' => __( 'Notification Off', 'shopglut' ),
				'side-notification' => __( 'Browser Side Notification', 'shopglut' ),
				'popup-notification' => __( 'Popup Notification', 'shopglut' ),
			),
			'default' => 'notification-off',
		),

		array(
			'id' => 'wishlist-side-notification-appear',
			'type' => 'select',
			'title' => __( 'Side Notification Appear', 'shopglut' ),
			'options' => array(
				'top-left' => __( 'From Top Left', 'shopglut' ),
				'top-middle' => __( 'From Top Middle', 'shopglut' ),
				'top-right' => __( 'From Top Right', 'shopglut' ),
				'middle-left' => __( 'From Middle Left', 'shopglut' ),
				'middle-right' => __( 'From Middle Right', 'shopglut' ),
				'bottom-left' => __( 'From Bottom Left', 'shopglut' ),
				'bottom-middle' => __( 'From Bottom Middle', 'shopglut' ),
				'bottom-right' => __( 'From Bottom Right', 'shopglut' ),
			),
			'default' => 'bottom-right',
			'dependency' => array( 'wishlist-general-notification', '==', 'side-notification' ),
		),

		array(
			'id' => 'wishlist-side-notification-effect',
			'type' => 'select',
			'title' => __( 'Side Notification Effect', 'shopglut' ),
			'options' => array(
				'fade-in-out' => __( 'Fade In/Out', 'shopglut' ),
				'slide-down-up' => __( 'Slide Down/Up', 'shopglut' ),
				'slide-from-left' => __( 'Slide from Left', 'shopglut' ),
				'slide-from-right' => __( 'Slide from Right', 'shopglut' ),
				'bounce' => __( 'Bounce', 'shopglut' ),
			),
			'default' => 'fade-in-out',
			'dependency' => array( 'wishlist-general-notification', '==', 'side-notification' ),
		),
		array(
			'id' => 'wishlist-popup-notification-effect',
			'type' => 'select',
			'title' => __( 'PopUp Notification Effect', 'shopglut' ),
			'options' => array(
				'fade-in-out' => __( 'Fade In/Out', 'shopglut' ),
				'zoom-in' => __( 'Zoom In', 'shopglut' ),
				'bounce' => __( 'Bounce', 'shopglut' ),
				'shake' => __( 'Shake', 'shopglut' ),
				'drop-in' => __( 'Drop In from Top', 'shopglut' ),
			),
			'default' => 'fade-in-out',
			'dependency' => array( 'wishlist-general-notification', '==', 'popup-notification' ),
		),

					array(
				'id' => 'wishlist-product-added-notification-text',
				'type' => 'text',
				'title' => __( 'Wishlist Added Text', 'shopglut' ),
				'default' => __( 'Product Added to Wishlist', 'shopglut' ),
				'desc'  => __( ' You can use <strong>{product_name}</strong> to show the product title and <strong>{product_sku}</strong> to show the product SKU. Example: "{product_name} added to your wishlist" or "Added {product_name} {product_sku} to wishlist"', 'shopglut' ),
				'dependency' => array( 'wishlist-general-notification', 'any', 'side-notification,popup-notification' ),
			),
			array(
				'id' => 'wishlist-product-removed-notification-text',
				'type' => 'text',
				'title' => __( 'Wishlist Removed Text', 'shopglut' ),
				'default' => __( 'Product Removed from Wishlist', 'shopglut' ),
				'desc'  => __( 'You can use <strong>{product_name}</strong> to show the product title and <strong>{product_sku}</strong> to show the product SKU. Example: "{product_name} removed from wishlist" or "Removed {product_name} {product_sku} from wishlist"', 'shopglut' ),
				'dependency' => array( 'wishlist-general-notification', 'any', 'side-notification,popup-notification' ),
			),

		array(
			'id' => 'wishlist-general-outofstock',
			'type' => 'switcher',
			'title' => __( 'Hide Wishlist for Out of Stock', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),


	) ) );

$wishlist_page_options = array(
	'product-image',
	'product-name',
	'product-price',
	'product-quantity',
	'product-availability',
	'product-discount-info',
	'product-review',
	'product-short-description',
	'product-sku',
	'product-add-to-cart',
	'product-checkout',
	'product-date-added',
	'product-urgency',
	
);

$attribute_taxonomies = wc_get_attribute_taxonomies();

// Loop through attributes and add them to filter options
if ( ! empty( $attribute_taxonomies ) ) {
	foreach ( $attribute_taxonomies as $attribute ) {
		if ( isset( $attribute->attribute_name ) ) {
			$attribute_name = $attribute->attribute_name;
			$wishlist_page_options[] = $attribute_name;
		}
	}
}


$wishlist_page_fields = array();

// Create sortable field items
$sortable_fields = array();

// Create mapping for translatable titles
$option_titles = array(
	'product-image' => __( 'Show Product Image', 'shopglut' ),
	'product-name' => __( 'Show Product Name', 'shopglut' ),
	'product-price' => __( 'Show Product Price', 'shopglut' ),
	'product-quantity' => __( 'Show Product Quantity', 'shopglut' ),
	'product-availability' => __( 'Show Product Availability', 'shopglut' ),
	'product-discount-info' => __( 'Show Product Discount Info', 'shopglut' ),
	'product-review' => __( 'Show Product Review', 'shopglut' ),
	'product-short-description' => __( 'Show Product Short Description', 'shopglut' ),
	'product-sku' => __( 'Show Product SKU', 'shopglut' ),
	'product-add-to-cart' => __( 'Show Product Add To Cart', 'shopglut' ),
	'product-checkout' => __( 'Show Product Checkout', 'shopglut' ),
	'product-date-added' => __( 'Show Product Date Added', 'shopglut' ),
	'product-urgency' => __( 'Show Product Urgency', 'shopglut' ),
);

foreach ( $wishlist_page_options as $option ) {
	// Use predefined title if available, otherwise create a fallback
	$title = isset( $option_titles[ $option ] ) 
		? $option_titles[ $option ]
		: /* translators: %s: option name */
		sprintf( __( 'Show %s', 'shopglut' ), ucwords( str_replace( array( '-', '_' ), ' ', $option ) ) );
		
	$sortable_fields[] = array(
		'id' => 'wishlist-page-show-' . str_replace( '_', '-', $option ),
		'type' => 'switcher',
		'title' => $title,
		'text_on' => __( 'Yes', 'shopglut' ),
		'text_off' => __( 'No', 'shopglut' ),
		'default' => '1',
	);
}

// Add the sortable field to the main fields array
$wishlist_page_fields[] = array(
	'id'        => 'wishlist-table-sort',
	'type'      => 'sortable',
	'fields'    => $sortable_fields,
);


$wishlist_page_fields[] = array(
	'id' => 'wishlist-remove-if-add-to-cart',
	'type' => 'switcher',
	'title' => __( 'Remove Wishlist if added to Cart', 'shopglut' ),
	'text_on' => __( 'Yes', 'shopglut' ),
	'text_off' => __( 'No', 'shopglut' ),
	'default' => 0,
);


// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Wishlist Page', 'shopglut' ),
	'fields' => $wishlist_page_fields,
) );


// Add the sortable field to the main fields array
$wishlist_account_page_fields[] = array(
	'id'        => 'wishlist-account-table-sort',
	'type'      => 'sortable',
	'fields'    => $sortable_fields,
);


$wishlist_account_page_fields[] = array(
	'id' => 'wishlist-account-remove-if-add-to-cart',
	'type' => 'switcher',
	'title' => __( 'Remove Wishlist if added to Cart', 'shopglut' ),
	'text_on' => __( 'Yes', 'shopglut' ),
	'text_off' => __( 'No', 'shopglut' ),
	'default' => '1',
);


// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Account Page', 'shopglut' ),
	'fields' => $wishlist_account_page_fields,
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Product Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-product-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Product Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-product-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),

		),

		array(
			'id' => 'wishlist-product-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-product-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-product-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-product-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-product-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-product-page', '==', 'true' ),
				array( 'wishlist-product-option', 'any', 'button-with-icon,only-icon' ),
			),
		),
		array(
			'id' => 'wishlist-product-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-icon' ),
		),

		array(
			'id' => 'wishlist-product-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Shop Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-shop-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Shop Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-shop-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),

		),

		array(
			'id' => 'wishlist-shop-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),
		),


		array(
			'id' => 'wishlist-shop-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-shop-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-shop-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-shop-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-shop-page', '==', 'true' ),
				array( 'wishlist-shop-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-shop-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-shop-page', '==', 'true' ),
				array( 'wishlist-shop-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-shop-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Archive Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-archive-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Archive Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-archive-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		// array(
		// 	'id' => 'wishlist-archive-enable-movelist',
		// 	'type' => 'switcher',
		// 	'pro' => 'https://www.appglut.com/plugin/shopglut',
		// 	'title' => __( 'Enable MoveList Button', 'shopglut' ),
		// 	'text_on' => __( 'Yes', 'shopglut' ),
		// 	'text_off' => __( 'No', 'shopglut' ),
		// 	'default' => 1,
		// 	'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		// ),

		array(
			'id' => 'wishlist-archive-select-cat-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist to Show', 'shopglut' ),
			'options' => array(
				'all-categories' => __( 'All Categories & Tags', 'shopglut' ),
				'select-category' => __( 'Select Category', 'shopglut' ),
				'select-tag' => __( 'Select Tag', 'shopglut' ),
			),
			'default' => 'all-categories',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
			),
		),

		array(
			'id' => 'wishlist-archive-select-category',
			'type' => 'select',
			'title' => esc_html__( 'Select Categories', 'shopglut' ),
			'chosen' => true,
			'multiple' => true,
			'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
			'options' => 'categories',
			'query_args' => array(
				'taxonomy' => 'product_cat',
			),
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-select-cat-option', '==', 'select-category' ),
			),
		),

		array(
			'id' => 'wishlist-archive-select-tag',
			'type' => 'select',
			'title' => esc_html__( 'Select Tags', 'shopglut' ),
			'chosen' => true,
			'multiple' => true,
			'placeholder' => esc_html__( 'Choose Tag', 'shopglut' ),
			'options' => 'categories',
			'query_args' => array(
				'taxonomy' => 'product_tag',
			),
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-select-cat-option', '==', 'select-tag' ),
			),
		),

		array(
			'id' => 'wishlist-archive-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-archive-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-archive-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-archive-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-archive-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a sub-tab for Share Buttons
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
    'parent' => 'primary_tab', // The slug id of the parent section
    'title' => __( 'Share Buttons', 'shopglut' ),
    'fields' => array(

        array(
            'id' => 'enable-social-share',
            'type' => 'switcher',
            'title' => __( 'Enable Social Share Buttons', 'shopglut' ),
            'subtitle' => __( 'Allow sharing on social platforms', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
        ),

        array(
            'id' => 'social-share-title',
            'type' => 'text',
            'title' => __( 'Share Section Title', 'shopglut' ),
            'subtitle' => __( 'Heading of share buttons', 'shopglut' ),
            'default' => 'Share Wishlist:',
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'enable-facebook-share',
            'type' => 'switcher',
            'title' => __( 'Enable Facebook Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'enable-twitter-share',
            'type' => 'switcher',
            'title' => __( 'Enable Twitter Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'twitter-share-text',
            'type' => 'text',
            'title' => __( 'Twitter Share Text', 'shopglut' ),
            'subtitle' => __( 'Default text for Twitter shares', 'shopglut' ),
            'default' => 'Check out my wishlist!',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-twitter-share', '==', 'true' )
            ),
        ),

        array(
            'id' => 'enable-whatsapp-share',
            'type' => 'switcher',
            'title' => __( 'Enable WhatsApp Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'whatsapp-share-text',
            'type' => 'textarea',
            'title' => __( 'WhatsApp Share Text', 'shopglut' ),
            'subtitle' => __( 'Default text for WhatsApp shares', 'shopglut' ),
            'default' => 'Check out my wishlist:',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-whatsapp-share', '==', 'true' )
            ),
        ),

        array(
            'id' => 'enable-pinterest-share',
            'type' => 'switcher',
            'title' => __( 'Enable Pinterest Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'enable-linkedin-share',
            'type' => 'switcher',
            'title' => __( 'Enable LinkedIn Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'linkedin-share-title',
            'type' => 'text',
            'title' => __( 'LinkedIn Share Title', 'shopglut' ),
            'subtitle' => __( 'Title for LinkedIn shares', 'shopglut' ),
            'default' => 'My Wishlist',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-linkedin-share', '==', 'true' )
            ),
        ),

        array(
            'id' => 'enable-telegram-share',
            'type' => 'switcher',
            'title' => __( 'Enable Telegram Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'telegram-share-text',
            'type' => 'text',
            'title' => __( 'Telegram Share Text', 'shopglut' ),
            'subtitle' => __( 'Default text for Telegram shares', 'shopglut' ),
            'default' => 'Check out my wishlist!',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-telegram-share', '==', 'true' )
            ),
        ),

        array(
            'id' => 'enable-email-share',
            'type' => 'switcher',
            'title' => __( 'Enable Email Share', 'shopglut' ),
            'text_on' => __( 'Yes', 'shopglut' ),
            'text_off' => __( 'No', 'shopglut' ),
            'default' => 1,
            'dependency' => array( 'enable-social-share', '==', 'true' ),
        ),

        array(
            'id' => 'email-share-subject',
            'type' => 'text',
            'title' => __( 'Email Share Subject', 'shopglut' ),
            'subtitle' => __( 'Subject line for email shares', 'shopglut' ),
            'default' => 'My Wishlist',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-email-share', '==', 'true' )
            ),
        ),

        array(
            'id' => 'email-share-body',
            'type' => 'textarea',
            'title' => __( 'Email Share Body', 'shopglut' ),
            'subtitle' => __( 'Default email body text', 'shopglut' ),
            'default' => 'Check out my wishlist:',
            'dependency' => array( 
                array( 'enable-social-share', '==', 'true' ),
                array( 'enable-email-share', '==', 'true' )
            ),
        ),

    )
) );

// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'id' => 'secondry_tab', // Set a unique slug-like ID
	'icon' => 'fa fa-palette', // Set a unique slug-like ID
	'title' => __( 'Appearance', 'shopglut' ),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-locked-background',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Background', 'shopglut' ),
			'default' => '#dd3333',
		),

		array(
			'id' => 'wishlist-locked-font-color',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-locked-icon-color',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Icon Color', 'shopglut' ),
			'default' => '#fff',
		),


		array(
			'id' => 'wishlist-notification-added-bg-color',
			'type' => 'color',
			'title' => __( 'Notification Button Color(Added)', 'shopglut' ),
			'default' => 'rgba(45,206,24,0.68)',
		),

		array(
			'id' => 'wishlist-notification-removed-bg-color',
			'type' => 'color',
			'title' => __( 'Notification Button Color(Removed)', 'shopglut' ),
			'default' => 'rgba(221,8,8,0.68)',
		),

		array(
			'id' => 'wishlist-notification-font-color',
			'type' => 'color',
			'title' => __( 'Notification Font Color', 'shopglut' ),
			'default' => '#fff',
		),

	),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Wishlist Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-page-table-header-color',
			'type' => 'color',
			'title' => __( 'Table Head Background Color', 'shopglut' ),
			'default' => '#a3a3a3',
		),

		array(
			'id' => 'wishlist-page-table-head-font-color',
			'type' => 'color',
			'title' => __( 'Table Head Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-subscription-btn-color',
			'type' => 'color',
			'title' => __( 'Subscribe Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),

		array(
			'id' => 'wishlist-page-subscription-btn-font-color',
			'type' => 'color',
			'title' => __( 'Subscribe Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-body-color-choice',
			'type' => 'select',
			'title' => __( 'Body Color Option', 'shopglut' ),
			'options' => array(
				'body-same-color' => __( 'Body Same Color', 'shopglut' ),
				'body-oddeven-color' => __( 'Body Odd Even Color', 'shopglut' ),
			),
			'default' => 'body-same-color',
		),

		array(
			'id' => 'wishlist-page-body-color',
			'type' => 'color',
			'title' => __( 'Table Body Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-same-color' ),
		),

		array(
			'id' => 'wishlist-page-body-hover-color',
			'type' => 'color',
			'title' => __( 'Table Body Hover Color', 'shopglut' ),
			'default' => '#f1f1f1',
		),
		array(
			'id' => 'wishlist-page-body-odd-color',
			'type' => 'color',
			'title' => __( 'Body Odd Row Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-oddeven-color' ),
		),

		array(
			'id' => 'wishlist-page-body-even-color',
			'type' => 'color',
			'title' => __( 'Body Even Row Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-oddeven-color' ),
		),

		array(
			'id' => 'wishlist-page-table-head-font-color',
			'type' => 'color',
			'title' => __( 'Table Head Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-table-body-font-color',
			'type' => 'color',
			'title' => __( 'Table Body Font Color', 'shopglut' ),
			'default' => '#000',
		),

		array(
			'id' => 'wishlist-page-addtocart-button-color',
			'type' => 'color',
			'title' => __( 'Add to Cart Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-page-addtocart-button-font-color',
			'type' => 'color',
			'title' => __( 'Add to Cart Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),
		array(
			'id' => 'wishlist-page-checkout-button-color',
			'type' => 'color',
			'title' => __( 'Button Checkout Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-page-checkout-button-font-color',
			'type' => 'color',
			'title' => __( 'Button Checkout Font Color', 'shopglut' ),
			'default' => '#fff',
		),

	),
) );
// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Product Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-product-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-product-button-font-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-product-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '175',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),

		
		// array(
		// 	'id' => 'wishlist-product-movelist-button-width',
		// 	'type' => 'dimensions',
		// 	'pro' => 'https://www.appglut.com/plugin/shopglut',
		// 	'title' => __( 'Movelist Button Width', 'shopglut' ),
		// 	'height' => false,
		// 	'default' => array(
		// 		'width' => '125',
		// 		'unit' => 'px',
		// 	),
		// ),

		// array(
		// 	'id' => 'wishlist-product-move-button-padding',
		// 	'type' => 'spacing',
		// 	'pro' => 'https://www.appglut.com/plugin/shopglut',
		// 	'title' => __( "Move List Button Padding", 'shopglut' ),
		// 	'default' => array(
		// 		'top' => '15',
		// 		'right' => '20',
		// 		'bottom' => '15',
		// 		'left' => '20',
		// 		'unit' => 'px',
		// 	),
		// ),

		// array(
		// 	'id' => 'wishlist-product-move-button-margin',
		// 	'type' => 'spacing',
		// 	'pro' => 'https://www.appglut.com/plugin/shopglut',
		// 	'title' => __( "Move List Button Margin", 'shopglut' ),
		// 	'default' => array(
		// 		'top' => '0',
		// 		'right' => '0',
		// 		'bottom' => '0',
		// 		'left' => '0',
		// 		'unit' => '0',
		// 	),
		// ),

	),
) );
// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Shop Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-shop-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-shop-button-text-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-shop-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '175',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-shop-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),
		
		
	),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Archive Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-archive-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-archive-button-text-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-archive-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '175',
				'unit' => 'px',
			),
		),


		array(
			'id' => 'wishlist-archive-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),
		
	),
) );

// Create a sub-tab for Share Buttons Style
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Share Buttons Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'social-share-container-margin',
			'type' => 'spacing',
			'title' => __( 'Share Container Margin', 'shopglut' ),
			'subtitle' => __( 'Margin around the entire share buttons container', 'shopglut' ),
			'default' => array(
				'top' => '20',
				'right' => '0',
				'bottom' => '20',
				'left' => '0',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-container-padding',
			'type' => 'spacing',
			'title' => __( 'Share Container Padding', 'shopglut' ),
			'subtitle' => __( 'Padding inside the share buttons container', 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '15',
				'bottom' => '15',
				'left' => '15',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-title-color',
			'type' => 'color',
			'title' => __( 'Share Title Color', 'shopglut' ),
			'subtitle' => __( 'Color of the "Share Wishlist:" text', 'shopglut' ),
			'default' => '#333333',
		),

		array(
			'id' => 'social-share-title-font-size',
			'type' => 'dimensions',
			'title' => __( 'Share Title Font Size', 'shopglut' ),
			'height' => false,
			'width_icon' => 'T',
			'default' => array(
				'width' => '16',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-button-size',
			'type' => 'dimensions',
			'title' => __( 'Share Button Size', 'shopglut' ),
			'subtitle' => __( 'Width and height of each share button', 'shopglut' ),
			'default' => array(
				'width' => '40',
				'height' => '40',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-button-spacing',
			'type' => 'dimensions',
			'title' => __( 'Button Spacing', 'shopglut' ),
			'subtitle' => __( 'Space between share buttons', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '8',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-button-border-radius',
			'type' => 'dimensions',
			'title' => __( 'Button Border Radius', 'shopglut' ),
			'subtitle' => __( 'Rounded corners for share buttons', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '5',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'social-share-facebook-color',
			'type' => 'color',
			'title' => __( 'Facebook Button Color', 'shopglut' ),
			'default' => '#1877f2',
		),

		array(
			'id' => 'social-share-twitter-color',
			'type' => 'color',
			'title' => __( 'Twitter Button Color', 'shopglut' ),
			'default' => '#1da1f2',
		),

		array(
			'id' => 'social-share-whatsapp-color',
			'type' => 'color',
			'title' => __( 'WhatsApp Button Color', 'shopglut' ),
			'default' => '#25d366',
		),

		array(
			'id' => 'social-share-pinterest-color',
			'type' => 'color',
			'title' => __( 'Pinterest Button Color', 'shopglut' ),
			'default' => '#bd081c',
		),

		array(
			'id' => 'social-share-linkedin-color',
			'type' => 'color',
			'title' => __( 'LinkedIn Button Color', 'shopglut' ),
			'default' => '#0077b5',
		),

		array(
			'id' => 'social-share-telegram-color',
			'type' => 'color',
			'title' => __( 'Telegram Button Color', 'shopglut' ),
			'default' => '#0088cc',
		),

		array(
			'id' => 'social-share-email-color',
			'type' => 'color',
			'title' => __( 'Email Button Color', 'shopglut' ),
			'default' => '#666666',
		),

		array(
			'id' => 'social-share-icon-color',
			'type' => 'color',
			'title' => __( 'Icon Color', 'shopglut' ),
			'subtitle' => __( 'Color of the icons inside share buttons', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'social-share-button-hover-opacity',
			'type' => 'slider',
			'title' => __( 'Button Hover Opacity', 'shopglut' ),
			'subtitle' => __( 'Opacity when hovering over buttons (0-100)', 'shopglut' ),
			'min' => 0,
			'max' => 100,
			'step' => 5,
			'default' => 80,
		),

	),
) );

AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Menu Counter Styling', 'shopglut' ),
	'fields' => array(

		// Basic Colors
		array(
			'id' => 'wishlist-page-menu-button-text-color',
			'type' => 'color',
			'title' => __( 'Menu Button Text Color', 'shopglut' ),
			'default' => '#000000',
		),

		array(
			'id' => 'wishlist-page-menu-button-icon-color',
			'type' => 'color',
			'title' => __( 'Menu Button Icon Color', 'shopglut' ),
			'default' => '#000000',
		),

		array(
			'id' => 'wishlist-page-menu-button-background-color',
			'type' => 'color',
			'title' => __( 'Menu Button Background Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		// Counter Bubble Colors
		array(
			'id' => 'wishlist-page-menu-counter-bubble-bg-color',
			'type' => 'color',
			'title' => __( 'Counter Bubble Background Color', 'shopglut' ),
			'default' => '#ff4444',
		),

		array(
			'id' => 'wishlist-page-menu-counter-bubble-text-color',
			'type' => 'color',
			'title' => __( 'Counter Bubble Text Color', 'shopglut' ),
			'default' => '#ffffff',
		),

		// Spacing & Margins
		array(
			'id' => 'wishlist-page-menu-button-text-margin',
			'type' => 'spacing',
			'title' => __( 'Menu Button Margin', 'shopglut' ),
			'default' => array(
				'top' => '5',
				'right' => '5',
				'bottom' => '5',
				'left' => '5',
			),
			'units' => array( 'px', 'em', 'rem', '%' ),
		),

		array(
			'id' => 'wishlist-page-menu-button-padding',
			'type' => 'spacing',
			'title' => __( 'Menu Button Padding', 'shopglut' ),
			'default' => array(
				'top' => '8',
				'right' => '12',
				'bottom' => '8',
				'left' => '12',
			),
			'units' => array( 'px', 'em', 'rem' ),
		),

		// Typography
		array(
			'id' => 'wishlist-page-menu-button-font-size',
			'type' => 'slider',
			'title' => __( 'Menu Button Font Size', 'shopglut' ),
			'desc' => __( 'Font size for the wishlist menu button text', 'shopglut' ),
			'default' => 14,
			'min' => 10,
			'max' => 24,
			'step' => 1,
			'unit' => 'px',
		),

		array(
			'id' => 'wishlist-page-menu-button-font-weight',
			'type' => 'select',
			'title' => __( 'Menu Button Font Weight', 'shopglut' ),
			'desc' => __( 'Font weight for the wishlist menu button text', 'shopglut' ),
			'options' => array(
				'300' => __( 'Light (300)', 'shopglut' ),
				'400' => __( 'Normal (400)', 'shopglut' ),
				'500' => __( 'Medium (500)', 'shopglut' ),
				'600' => __( 'Semi Bold (600)', 'shopglut' ),
				'700' => __( 'Bold (700)', 'shopglut' ),
			),
			'default' => '500',
		),

		array(
			'id' => 'wishlist-page-menu-icon-size',
			'type' => 'slider',
			'title' => __( 'Menu Icon Size', 'shopglut' ),
			'desc' => __( 'Size for the wishlist menu icon', 'shopglut' ),
			'default' => 16,
			'min' => 12,
			'max' => 32,
			'step' => 1,
			'unit' => 'px',
		),

		// Design Settings
		array(
			'id' => 'wishlist-page-menu-button-border-radius',
			'type' => 'slider',
			'title' => __( 'Menu Button Border Radius', 'shopglut' ),
			'desc' => __( 'Border radius for the wishlist menu button', 'shopglut' ),
			'default' => 4,
			'min' => 0,
			'max' => 50,
			'step' => 1,
			'unit' => 'px',
		),

		array(
			'id' => 'wishlist-page-menu-button-border-width',
			'type' => 'slider',
			'title' => __( 'Menu Button Border Width', 'shopglut' ),
			'desc' => __( 'Border width for the wishlist menu button', 'shopglut' ),
			'default' => 0,
			'min' => 0,
			'max' => 5,
			'step' => 1,
			'unit' => 'px',
		),

		array(
			'id' => 'wishlist-page-menu-button-border-color',
			'type' => 'color',
			'title' => __( 'Menu Button Border Color', 'shopglut' ),
			'desc' => __( 'Border color for the wishlist menu button', 'shopglut' ),
			'default' => '#cccccc',
			'dependency' => array( 'wishlist-page-menu-button-border-width', '!=', '0' ),
		),

		// Gap between elements
		array(
			'id' => 'wishlist-page-menu-elements-gap',
			'type' => 'slider',
			'title' => __( 'Elements Gap', 'shopglut' ),
			'desc' => __( 'Gap between icon, text and counter bubble', 'shopglut' ),
			'default' => 8,
			'min' => 0,
			'max' => 20,
			'step' => 1,
			'unit' => 'px',
		),

		// Hover Effects
		array(
			'id' => 'wishlist-page-menu-button-hover-bg-color',
			'type' => 'color',
			'title' => __( 'Menu Button Hover Background', 'shopglut' ),
			'default' => '#f5f5f5',
		),

		array(
			'id' => 'wishlist-page-menu-button-hover-text-color',
			'type' => 'color',
			'title' => __( 'Menu Button Hover Text Color', 'shopglut' ),
			'default' => '#000000',
		),

		array(
			'id' => 'wishlist-page-menu-button-hover-icon-color',
			'type' => 'color',
			'title' => __( 'Menu Button Hover Icon Color', 'shopglut' ),
			'default' => '#000000',
		),

		// Animation Settings
		array(
			'id' => 'wishlist-page-menu-button-transition-duration',
			'type' => 'slider',
			'title' => __( 'Hover Transition Duration', 'shopglut' ),
			'desc' => __( 'Duration of hover transition effect in milliseconds', 'shopglut' ),
			'default' => 300,
			'min' => 100,
			'max' => 1000,
			'step' => 50,
			'unit' => 'ms',
		),

		array(
			'id' => 'wishlist-page-menu-button-hover-transform',
			'type' => 'switcher',
			'title' => __( 'Enable Hover Transform', 'shopglut' ),
			'desc' => __( 'Enable slight upward movement on hover', 'shopglut' ),
			'default' => true,
		),

		// Counter Bubble Advanced Settings
		array(
			'id' => 'wishlist-page-menu-counter-bubble-font-size',
			'type' => 'slider',
			'title' => __( 'Counter Bubble Font Size', 'shopglut' ),
			'desc' => __( 'Font size for the counter bubble text', 'shopglut' ),
			'default' => 12,
			'min' => 8,
			'max' => 18,
			'step' => 1,
			'unit' => 'px',
		),

		array(
			'id' => 'wishlist-page-menu-counter-bubble-min-width',
			'type' => 'slider',
			'title' => __( 'Counter Bubble Min Width', 'shopglut' ),
			'desc' => __( 'Minimum width for the counter bubble', 'shopglut' ),
			'default' => 18,
			'min' => 16,
			'max' => 30,
			'step' => 1,
			'unit' => 'px',
		),

		// Visibility Settings
		array(
			'id' => 'wishlist-page-menu-button-show-text',
			'type' => 'switcher',
			'title' => __( 'Show Menu Button Text', 'shopglut' ),
			'desc' => __( 'Display text alongside the wishlist icon', 'shopglut' ),
			'default' => true,
		),

		array(
			'id' => 'wishlist-page-menu-button-text',
			'type' => 'text',
			'title' => __( 'Menu Button Text', 'shopglut' ),
			'desc' => __( 'Custom text for the wishlist menu button', 'shopglut' ),
			'default' => __( 'Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-page-menu-button-show-text', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-page-menu-button-show-counter',
			'type' => 'switcher',
			'title' => __( 'Show Counter Bubble', 'shopglut' ),
			'desc' => __( 'Display counter bubble with wishlist item count', 'shopglut' ),
			'default' => true,
		),

		array(
			'id' => 'wishlist-page-menu-button-hide-empty-counter',
			'type' => 'switcher',
			'title' => __( 'Hide Counter When Empty', 'shopglut' ),
			'desc' => __( 'Hide counter bubble when wishlist is empty', 'shopglut' ),
			'default' => true,
			'dependency' => array( 'wishlist-page-menu-button-show-counter', '==', 'true' ),
		),

	),
) );





// Allow pro plugin to add settings

do_action( 'shopglut_wishlist_pro_settings', $AGSHOPGLUT_WISHLIST_OPTIONS, $this );
