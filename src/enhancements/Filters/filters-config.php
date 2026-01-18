<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Create the live preview metabox
AGSHOPGLUT::createMetabox(
	'shopg_filter_live_preview',
	array(
		'title' => __( 'Live Preview', 'shopglut' ),
		'post_type' => 'shopglut_enhancements',
		'context' => 'normal',
	)
);

// Create the live preview section
AGSHOPGLUT::createSection(
	'shopg_filter_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

$shopglut_shopg_options_settings = "shopg_filter_options_settings";

// Create the layout settings metabox
AGSHOPGLUT::createMetabox(
	$shopglut_shopg_options_settings,
	array(
		'title' => esc_html__( 'Filter Settings', 'shopglut' ),
		'post_type' => 'shopglut_enhancements',
		'context' => 'side',
	)
);

// Fetch all WooCommerce product attributes

// Build options array dynamically for filter types
$shopglut_shopg_filter_options = array(
	'product-categories' => esc_html__( 'Product Categories', 'shopglut' ),
	'product-tags' => esc_html__( 'Product Tags', 'shopglut' ),
	//'product-sortby' => esc_html__( 'Product Sort By', 'shopglut' ),
	// 'product-search' => esc_html__( 'Search By Text', 'shopglut' ),
	// 'product-price' => esc_html__( 'Product Price', 'shopglut' ),
	//'product-rating' => esc_html__( 'Product Rating', 'shopglut' ),
	//'product-author' => esc_html__( 'Author', 'shopglut' ),
	//'product-status' => esc_html__( 'Product Status', 'shopglut' ),
	// 'product-view' => esc_html__( 'Product View', 'shopglut' ),
	//'product-type' => esc_html__( 'Product Type', 'shopglut' ),
	//'product-order-direction' => esc_html__( 'Product Order Direction', 'shopglut' ),
	// 'product-shipping-class' => esc_html__( 'Product Shipping Class', 'shopglut' ),
);


$shopglut_shopg_attribute_taxonomies = wc_get_attribute_taxonomies();

// // Loop through attributes and add them to filter options
// if ( ! empty( $shopglut_shopg_attribute_taxonomies ) ) {
// 	foreach ( $shopglut_shopg_attribute_taxonomies as $shopg_attribute ) {
// 		if ( isset( $shopg_attribute->attribute_name ) ) {
// 			$shopglut_shopg_attribute_name = wc_attribute_taxonomy_name( $shopg_attribute->attribute_name );
// 			/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
// 			$options[ $shopglut_shopg_attribute_name ] = sprintf( esc_html__( 'Product %s', 'shopglut' ), ucfirst( str_replace( '-', ' ', $shopg_attribute->attribute_label ) ) );
// 		}
// 	}
// }



// Main fields array
$shopglut_shopg_fields = array(
	array(
		'id' => 'shopglut-filter-settings-main-tab',
		'type' => 'tabbed',
		'tabs' => array(
			
			array(
				'class' => "shopglut-filter-settings-main-tab",
				'title' => __( 'Filter', 'shopglut' ),
				'icon' => 'fa-solid fa-filter',
				'fields' => array(
					array(
						'id' => 'shopg-filter-add-new',
						'type' => 'repeater',
						'button_title' => __( "Add New", 'shopglut' ),
						'fields' => array(

							array(
								'id' => 'shopg-filter-accordion',
								'type' => 'accordion',
								'accordions' => array(
									array(
										'title' => __( 'Title', 'shopglut' ),
										'fields' => array(
											array(
												'id' => 'shopg-filter-sub-tabbed',
												'type' => 'tabbed',
												'tabs' => array(
													array(
														'class' => 'general-tab',
														'title' => __( 'General', 'shopglut' ),
														'icon' => 'fa-solid fa-gears',
														'fields' => array(
															array(
																'id' => 'accordion-title',
																'type' => 'text',
																'title' => __( 'Filter Title', 'shopglut' ),
															),
															array(
																'id' => 'filter-type',
																'type' => 'select',
																'title' => __( 'Filter Type', 'shopglut' ),
																'options' => $shopglut_shopg_filter_options,
																'default' => 'product-categories',
															),

															array(
																'id' => 'filter-product-price-appearance',
																'type' => 'image_select',
																'title' => __( 'Price Appearance', 'shopglut' ),
																'options' => array(
																	'price-range-slider' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/normal-slider.png',
																	'price-range-radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio-slider.png',
																	'price-range-checkbox' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox-slider.png',
																),
																'default' => 'price-range-slider',
																'dependency' => array( 'filter-type', '==', "product-price" ),
															),

															array(
																'id' => 'filter-product-price-range',
																'type' => 'min_max',
																'title' => __( 'Price Range', 'shopglut' ),
																'min_value' => '0',
																'max_value' => '1000',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															array(
																'id' => 'filter-product-price-range-show',
																'type' => 'switcher',
																'title' => __( 'Show Price Range on Filter', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															array(
																'id' => 'filter-product-price-range-ruler',
																'type' => 'switcher',
																'title' => __( 'Show Price Range Grid on Filter', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Skin selection
															array(
																'id' => 'filter-product-price-skin',
																'type' => 'select',
																'title' => __( 'Slider Skin', 'shopglut' ),
																'options' => array(
																	'flat' => __( 'Flat', 'shopglut' ),
																	'big' => __( 'Big', 'shopglut' ),
																	'modern' => __( 'Modern', 'shopglut' ),
																	'sharp' => __( 'Sharp', 'shopglut' ),
																	'round' => __( 'Round', 'shopglut' ),
																	'square' => __( 'Square', 'shopglut' ),
																),
																'default' => 'flat',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Step size
															array(
																'id' => 'filter-product-price-step',
																'type' => 'number',
																'title' => __( 'Price Step', 'shopglut' ),
																'desc' => __( 'Set step size for the slider (e.g. 5 for $5 increments)', 'shopglut' ),
																'default' => 1,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Number formatting
															array(
																'id' => 'filter-product-price-prettify',
																'type' => 'switcher',
																'title' => __( 'Prettify Numbers', 'shopglut' ),
																'desc' => __( 'Improve readability of long numbers (e.g. 10000 → 10 000)', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => true,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Separator character
															array(
																'id' => 'filter-product-price-separator',
																'type' => 'text',
																'title' => __( 'Thousand Separator', 'shopglut' ),
																'desc' => __( 'Character to use as thousand separator (e.g. " ", ",", ".")', 'shopglut' ),
																'default' => ' ',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																	array( 'filter-product-price-prettify', '==', true ),
																),
															),

															// Prefix/Currency symbol position
															array(
																'id' => 'filter-product-price-prefix',
																'type' => 'text',
																'title' => __( 'Price Prefix', 'shopglut' ),
																'desc' => __( 'Text to display before the price (e.g. "$")', 'shopglut' ),
																'default' => '$',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Postfix
															array(
																'id' => 'filter-product-price-postfix',
																'type' => 'text',
																'title' => __( 'Price Postfix', 'shopglut' ),
																'desc' => __( 'Text to display after the price (e.g. " USD")', 'shopglut' ),
																'default' => '',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Grid number
															array(
																'id' => 'filter-product-price-grid-num',
																'type' => 'number',
																'title' => __( 'Grid Number', 'shopglut' ),
																'desc' => __( 'Number of grid units to display', 'shopglut' ),
																'default' => 4,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																	array( 'filter-product-price-range-ruler', '==', true ),
																),
															),

															// Keyboard control
															array(
																'id' => 'filter-product-price-keyboard',
																'type' => 'switcher',
																'title' => __( 'Keyboard Controls', 'shopglut' ),
																'desc' => __( 'Allow arrow keys to control slider', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => true,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Force edges
															array(
																'id' => 'filter-product-price-force-edges',
																'type' => 'switcher',
																'title' => __( 'Force Edges', 'shopglut' ),
																'desc' => __( 'Keep slider handles inside the container', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => false,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															// Drag interval (for double slider)
															array(
																'id' => 'filter-product-price-drag-interval',
																'type' => 'switcher',
																'title' => __( 'Drag Interval', 'shopglut' ),
																'desc' => __( 'Allow users to drag the entire selected range', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => false,
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-slider,tooltip-range-slider' ),
																),
															),

															array(
																'id' => 'filter-price-range-repeater',
																'class' => 'filter-price-range-repeater',
																'type' => 'repeater',
																'title' => __( 'Choose Ranges', 'shopglut' ),
																'button_title' => __( 'Add Range +', 'shopglut' ),
																'fields' => array(

																	array(
																		'id' => 'filter-group-price-range',
																		'type' => 'min_max',
																		'title' => __( 'Price Range', 'shopglut' ),
																		'min_value' => '0',
																		'max_value' => '20',
																	)

																),
																'dependency' => array(
																	array( 'filter-type', '==', 'product-price' ),
																	array( 'filter-product-price-appearance', 'any', 'price-range-radio,price-range-checkbox' ),
																),
															),

															array(
																'id' => 'filter-product-categories-appearance',
																'type' => 'image_select',
																'title' => __( 'Categories Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	// 'color-button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color-button.png',
																	// 'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	// 'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	// 'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	// 'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	//'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	//'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png',
																	//'tree-view' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/tree-view.png',
																	//'icon' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/icon-view.png',
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-categories" ),
															),

															array(
																'id' => 'filter-product-tags-appearance',
																'type' => 'image_select',
																'title' => __( 'Tags Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	// 'color-button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color-button.png',
																	// 'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	// 'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	// 'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	// 'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	//'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	//'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png',
																	//'tree-view' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/tree-view.png',
																	//'icon' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/icon-view.png',
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-tags" ),
															),

															array(
																'id' => 'filter-product-shipping-appearance',
																'type' => 'image_select',
																'title' => __( 'Product Shipping Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-shipping-class" ),
															),

															array(
																'id' => 'filter-product-sortby-appearance',
																'type' => 'image_select',
																'title' => __( 'SortBy Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-sortby" ),
															),

															array(
																'id' => 'filter-product-sortby-options',
																'type' => 'select',
																'title' => esc_html__( 'Select SortBy Options', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Select an Option', 'shopglut' ),
																'options' => array(
																	'title' => esc_html__( 'Product Title', 'shopglut' ),
																	'name' => esc_html__( 'Product Name (Slug)', 'shopglut' ),
																	'ID' => esc_html__( 'Product ID', 'shopglut' ),
																	'author' => esc_html__( 'Product Author', 'shopglut' ),
																	'sku' => esc_html__( 'Product SKU', 'shopglut' ),
																	'sales' => esc_html__( 'Product Sales', 'shopglut' ),
																	'price_low_to_high' => esc_html__( 'Product Price (Low to High)', 'shopglut' ),
																	'price_high_to_low' => esc_html__( 'Product Price (High to Low)', 'shopglut' ),
																	'date' => esc_html__( 'Product Date', 'shopglut' ),
																	'modified' => esc_html__( 'Product Last Modify', 'shopglut' ),
																	'ratings' => esc_html__( 'Product Ratings', 'shopglut' ),
																	'featured' => esc_html__( 'Featured Product', 'shopglut' ),
																	'stock_quantity' => esc_html__( 'Product Stock Quantity', 'shopglut' ),
																	'reviews_count' => esc_html__( 'Product Reviews Count', 'shopglut' ),

																),
																'dependency' => array( 'filter-type', '==', "product-sortby" ),

															),


															array(
																'id' => 'filter-product-term-images',
																'type' => 'tax_images',
																'title' => __( 'Product Categories Images', 'shopglut' ),
																'product_option' => 'product_cat',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-categories' ),
																	array( 'filter-product-categories-appearance', '==', 'image' ),
																),
															),


															array(
																'id' => 'filter-product-cat-color',
																'type' => 'tax_color',
																'title' => __( 'Product Categories Color', 'shopglut' ),
																'product_option' => 'product_cat',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-categories' ),
																	array( 'filter-product-categories-appearance', 'any', 'color,color-button' ),
																),
															),

															array(
																'id' => 'filter-product-tag-color',
																'type' => 'tax_color',
																'title' => __( 'Product Tags Color', 'shopglut' ),
																'product_option' => 'product_tag',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-tags' ),
																	array( 'filter-product-tags-appearance', 'any', 'color,color-button' ),
																),
															),

															array(
																'id' => 'filter-product-tag-images',
																'type' => 'tax_images',
																'title' => __( 'Product Tags Images', 'shopglut' ),
																'product_option' => 'product_tag',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-tags' ),
																	array( 'filter-product-tags-appearance', '==', 'image' ),
																),
															),

															array(
																'id' => 'filter-product-shipping-images',
																'type' => 'tax_images',
																'title' => __( 'Product Shipping Class Images', 'shopglut' ),
																'product_option' => 'product_shipping_class',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-shipping-class' ),
																	array( 'filter-product-shipping-appearance', '==', 'image' ),
																),
															),

															array(
																'id' => 'filter-product-shipping-color',
																'type' => 'tax_color',
																'title' => __( 'Product Shipping Class Color', 'shopglut' ),
																'product_option' => 'product_shipping_class',
																'dependency' => array(
																	array( 'filter-type', '==', 'product-shipping-class' ),
																	array( 'filter-product-shipping-appearance', '==', 'color' ),
																),
															),

															array(
																'id' => 'filter-product-rating-appearance',
																'type' => 'image_select',
																'title' => __( 'Rating Appearance', 'shopglut' ),
																'options' => array(
																	'star-rating' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/star-rating.png',
																	'checkbox-rating' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox-rating.png',
																	'radio-rating' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio-rating.png',
																	'slider-rating' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/slider-rating.png',
																	'dropdown-rating' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown-rating.png',
																),
																'default' => 'star-rating',
																'dependency' => array( 'filter-type', '==', "product-rating" ),
															),

															array(
																'id' => 'filter-product-rating-show-count',
																'type' => 'switcher',
																'title' => __( 'Show Product Count', 'shopglut' ),
																'desc' => __( 'Display the number of products for each rating level', 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => false,
																'dependency' => array( 'filter-type', '==', "product-rating" ),
															),
															array(
																'id' => 'filter-product-author-appearance',
																'type' => 'image_select',
																'title' => __( 'Author Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-author" ),
															),

															array(
																'id' => 'filter-product-stock-appearance',
																'type' => 'image_select',
																'title' => __( 'Stock Appearance', 'shopglut' ),
																'options' => array(
																	'value-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																),
																'default' => 'value-1',
																'dependency' => array( 'filter-type', '==', "product-stock" ),
															),

															array(
																'id' => 'filter-product-sell-appearance',
																'type' => 'image_select',
																'title' => __( 'Sell Appearance', 'shopglut' ),
																'options' => array(
																	'value-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																),
																'default' => 'value-1',
																'dependency' => array( 'filter-type', '==', "product-sell" ),
															),

															array(
																'id' => 'filter-product-type-appearance',
																'type' => 'image_select',
																'title' => __( 'Product Type Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-type" ),
															),

															array(
																'id' => 'filter-product-keyword-appearance',
																'type' => 'image_select',
																'title' => __( 'Keyword Appearance', 'shopglut' ),
																'options' => array(
																	'value-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																	'value-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
																),
																'default' => 'value-1',
																'dependency' => array( 'filter-type', '==', "product-keyword" ),
															),


															array(
																'id' => 'filter-order-direction-options',
																'type' => 'select',
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Select an Option', 'shopglut' ),
																'title' => __( 'Order Direction Options', 'shopglut' ),
																'options' => array(
																	'asc' => __( 'Ascending ', 'shopglut' ),
																	'desc' => __( 'Descending ', 'shopglut' ),
																),
																'default' => 'asc',
																'dependency' => array( 'filter-type', '==', "product-order-direction" ),
															),

															array(
																'id' => 'filter-product-status-options',
																'type' => 'select',
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Select an Option', 'shopglut' ),
																'title' => __( 'Status Options', 'shopglut' ),
																'options' => array(
																	'in-stock' => __( 'In Stock', 'shopglut' ),
																	'out-of-stock' => __( 'Out of Stock', 'shopglut' ),
																	'on-sale' => __( 'On Sale', 'shopglut' ),
																	'discounted-products' => __( 'Discounted Products', 'shopglut' ),
																	'purchase-history' => __( 'Purchased History', 'shopglut' ),
																),
																'default' => 'in-stock',
																'dependency' => array( 'filter-type', '==', "product-status" ),
															),

															array(
																'id' => 'filter-product-status-appearance',
																'type' => 'image_select',
																'title' => __( 'Status Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-status" ),
															),


															array(
																'id' => 'filter-order-direction-appearance',
																'type' => 'image_select',
																'title' => __( 'Order Direction Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																	'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
																	'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
																),
																'default' => 'check-list',
																'dependency' => array( 'filter-type', '==', "product-order-direction" ),
															),

															array(
																'id' => 'filter-search-placeholder-text',
																'type' => 'text',
																'title' => __( 'Search Input Placeholder', 'shopglut' ),
																'default' => __( 'Search...', 'shopglut' ),
																'dependency' => array( 'filter-type', '==', "product-search" ),
															),
															array(
																'id' => 'filter-search-options',
																'type' => 'select',
																'title' => __( 'Search Option', 'shopglut' ),
																'options' => array(
																	'title' => __( 'Search by Title', 'shopglut' ),
																	'content' => __( 'Search by Content', 'shopglut' ),
																	'excerpt' => __( 'Search by Excerpt', 'shopglut' ),
																	'content_or_excerpt' => __( 'Search by Content OR Excerpt', 'shopglut' ),
																	'title_or_content_or_excerpt' => __( 'Search by title OR content OR excerpt', 'shopglut' ),
																	'title_or_content' => __( 'Search by title OR content', 'shopglut' ),
																),
																'default' => 'title',
																'dependency' => array( 'filter-type', '==', "product-search" ),
															),

															array(
																'id' => 'filter-search-by-word-option',
																'type' => 'select',
																'title' => __( 'Search By Word Option', 'shopglut' ),
																'options' => array(
																	'full-word' => __( 'Search by Full Word', 'shopglut' ),
																	'partial-word' => __( 'Search by Partial Word', 'shopglut' ),
																),
																'default' => 'full-word',
																'dependency' => array( 'filter-type', '==', "product-search" ),
															),


															array(
																'id' => 'filter-category-exclude-include-button',
																'type' => 'button_set',
																'title' => __( 'Choose Option', 'shopglut' ),
																'options' => array(
																	'all-cat' => __( 'All Categories', 'shopglut' ),
																	// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- This is a configuration option, not a query parameter
																	'exclude' => __( 'Exclude', 'shopglut' ),
																	'include' => __( 'Include', 'shopglut' ),
																),
																'default' => 'all-cat',
																'dependency' => array( 'filter-type', '==', "product-categories" ),
															),


															array(
																'id' => 'shopg-filter-include-category',
																'type' => 'select',
																'title' => esc_html__( 'Include Categories', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_cat',
																),
																'dependency' => array( 'filter-category-exclude-include-button|filter-type', '==|==', "include|product-categories" ),
															),


															array(
																'id' => 'shopg-filter-exclude-category',
																'type' => 'select',
																'title' => esc_html__( 'Exclude Categories', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_cat',
																),
																'dependency' => array( 'filter-category-exclude-include-button|filter-type', '==|==', "exclude|product-categories" ),
															),
															array(
																'id' => 'filter-tag-exclude-include-button',
																'type' => 'button_set',
																'title' => __( 'Choose Option', 'shopglut' ),
																'options' => array(
																	'all-tags' => __( 'All Tags', 'shopglut' ),
																	// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- This is a configuration option, not a query parameter
																	'exclude' => __( 'Exclude', 'shopglut' ),
																	'include' => __( 'Include', 'shopglut' ),
																),
																'default' => 'all-tags',
																'dependency' => array( 'filter-type', '==', "product-tags" ),
															),
															array(
																'id' => 'shopg-filter-include-tag',
																'type' => 'select',
																'title' => esc_html__( 'Include Tags', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Choose Tags', 'shopglut' ),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_tag',
																),
																'dependency' => array( 'filter-tag-exclude-include-button|filter-type', '==|==', "include|product-tags" ),
															),
															array(
																'id' => 'shopg-filter-exclude-tag',
																'type' => 'select',
																'title' => esc_html__( 'Exclude Tags', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__( 'Choose Tags', 'shopglut' ),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_tag',
																),
																'dependency' => array( 'filter-tag-exclude-include-button|filter-type', '==|==', "exclude|product-tags" ),
															),

															array(
																'id' => 'filter-product-view-appearance',
																'type' => 'image_select',
																'title' => __( 'Product View Appearance', 'shopglut' ),
																'options' => array(
																	'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
																	'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
																	'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
																	'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
																	'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
																	'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
																),
																'default' => 'radio',
																'dependency' => array( 'filter-type', '==', 'product-view' ),
															),

															array(
																'id' => 'filter-product-view-options',
																'type' => 'select',
																'title' => __( 'Product View Filter Options', 'shopglut' ),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => __( 'Select View Options', 'shopglut' ),
																'options' => array(
																	'asc' => __( 'Ascending', 'shopglut' ),
																	'desc' => __( 'Descending', 'shopglut' ),
																),
																'default' => array( 'asc', 'desc' ),
																'dependency' => array( 'filter-type', '==', 'product-view' ),
															),
														),
													),
													array(
														'class' => 'appearance-tab',
														'title' => __( 'Appearance', 'shopglut' ),
														'icon' => 'fa-solid fa-vest',
														'fields' => array(

															array(
																'id' => 'filter-show-title',
																'type' => 'switcher',
																'title' => __( "Show Filter Title", 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => true,
															),

															array(
																'id' => 'filter-content-bg-color',
																'type' => 'color',
																'title' => __( "Content Background Color", 'shopglut' ),
																'default' => '#fff',
															),

															array(
																'id' => 'filter-content-font-color',
																'type' => 'color',
																'title' => __( "Content Font Color", 'shopglut' ),
																'default' => '#000',
															),

															array(
																'id' => 'show-count',
																'type' => 'switcher',
																'desc' => __( 'Show count of product items', 'shopglut' ),
																'title' => __( "Show Count", 'shopglut' ),
																'text_on' => __( 'Yes', 'shopglut' ),
																'text_off' => __( 'No', 'shopglut' ),
																'default' => false,
															),

															// Tooltip settings removed as requested





														),
													),


												),
											),
										),
									),
								),
							),
						),
					),
				),
			),	
			array(
				'class' => "shopglut-filter-settings-main-tab2",
				'title' => __( 'Settings', 'shopglut' ),
				'icon' => 'fa fa-gear',
				'fields' => array(

					array(
						'id' => 'filter-show-on-pages',
						'type' => 'select_filter_page',
						'title' => __( 'Show On Pages', 'shopglut' ),
						'chosen' => true,
						'multiple' => true,
						'options' => 'select_filter_page'
					),

					array(
						'id' => 'filter-option',
						'type' => 'select',
						'title' => __( 'Filter Option', 'shopglut' ),
						'desc' => __( 'Choose How filter will work', 'shopglut' ),
						'options' => array(
							//'select-submit-filter' => esc_html__( 'Multi Select and Submit Filter', 'shopglut' ),
							'select-apply-filter' => esc_html__( 'Multi Select and Apply Filter', 'shopglut' ),
							//'select-ajax-filter' => esc_html__( 'Select And Ajax Filter', 'shopglut' ),
						),
						'default' => 'select-apply-filter',
					),

					// array(
					// 	'id' => 'filter-multi-select-condition',
					// 	'type' => 'button_set',
					// 	'title' => __( 'Multi Select Condition', 'shopglut' ),
					// 	'options' => array(
					// 		'and' => __( 'AND', 'shopglut' ),
					// 		'or' => __( 'OR', 'shopglut' ),
					// 	),
					// 	'default' => 'and',
					// 	'dependency' => array( 'filter-option', 'any', "select-submit-filter,select-apply-filter,select-ajax-filter" ),

					// ),

					array(
						'id' => 'filter-title-appearance',
						'type' => 'image_select',
						'title' => __( 'Filter Title Appearance', 'shopglut' ),
						'desc' => __( 'First is Accordion Title, Second is Normal Title', 'shopglut' ),
						'options' => array(
							'accordion-design' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/title1.png',
							'normal-design' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/title2.png',
						),
						'default' => 'accordion-design'
					),

					array(
						'id' => 'filter-title-group',
						'type' => 'fieldset',
						'title' => __( 'Choose Filter Title Options', 'shopglut' ),
						'fields' => array(

							array(
								'id' => 'filter-title-color-groups',
								'type' => 'color_group',
								'title' => __( 'Filter Title Color Options ', 'shopglut' ),
								'options' => array(
									'filter-title-bg-color' => __( 'Title Background Color ', 'shopglut' ),
									'filter-title-color' => __( 'Title Color ', 'shopglut' ),
									'filter-title-icon-color' => __( 'Title Icon Color ', 'shopglut' ),
								)
							),

							array(
								'id' => 'filter-title-close-icon',
								'type' => 'icon',
								'title' => __( 'Filter Accordion Close Icon', 'shopglut' ),
								'dependency' => array( 'filter-title-appearance', '==', "accordion-design", 'true' ),
								'default' => 'fa fa-angle-down',
							),

							array(
								'id' => 'filter-title-expand-icon',
								'type' => 'icon',
								'title' => __( 'Filter Accordion Expand Icon', 'shopglut' ),
								'dependency' => array( 'filter-title-appearance', '==', "accordion-design", 'true' ),
								'default' => 'fa fa-angle-right',
							),

							array(
								'id' => 'filter-title-icon',
								'type' => 'icon',
								'title' => __( 'Filter Title Icon', 'shopglut' ),
								'dependency' => array( 'filter-title-appearance', '==', "normal-design", 'true' ),
							),


							array(
								'id' => 'filter-title-border1',
								'type' => 'border',
								'title' => __( 'Filter Title Small Border', 'shopglut' ),
								'right' => false,
								'left' => false,
								'top' => false,
								'default' => array(
									'bottom' => '1',
									'style' => 'solid',
									'color' => '#1e73be',
									'unit' => 'px',
								),
								'dependency' => array( 'filter-title-appearance', '==', "normal-design", 'true' ),
							),

							

							array(
								'id' => 'filter-title-normal-design-hide',
								'type' => 'checkbox',
								'title' => __( 'Hide and Show ', 'shopglut' ),
								'options' => array(
									'hide-icon' => __( 'Hide Icon', 'shopglut' ),
									'hide-small-border' => __( 'Hide Border', 'shopglut' ),
								),
								'dependency' => array( 'filter-title-appearance', '==', "normal-design", 'true' ),
							),


						),
					),


					// array(
					// 	'id' => 'filter-position',
					// 	'type' => 'button_set',
					// 	'title' => __( 'Select Filter Position', 'shopglut' ),
					// 	'options' => array(
					// 		'left-side' => __( 'Left Side', 'shopglut' ),
					// 		'right-side' => __( 'Right Side', 'shopglut' ),
					// 		'top-position' => __( 'Horizontal Above Shop', 'shopglut' ),
					// 	),
					// 	'default' => 'left-side',
					// ),

					// array(
					// 	'id' => 'filter-hide-stock-out',
					// 	'type' => 'switcher',
					// 	'title' => __( "Hide Stock Out Products", 'shopglut' ),
					// 	'text_on' => __( 'Yes', 'shopglut' ),
					// 	'text_off' => __( 'No', 'shopglut' ),
					// 	'default' => false,
					// ),

					// array(
					// 	'id' => 'filter-hide-on-devices',
					// 	'type' => 'button_set',
					// 	'title' => __( 'Hide Filters on Devices', 'shopglut' ),
					// 	'options' => array(
					// 		'no-device' => __( 'No Device', 'shopglut' ),
					// 		'mobile' => __( 'Mobile', 'shopglut' ),
					// 		'tablet' => __( 'Tablet', 'shopglut' ),
					// 		'Desktop' => __( 'Desktop', 'shopglut' ),


					// 	),
					// 	'default' => 'no-device',
					// ),

					array(
						'id' => 'filter-number-products-show',
						'type' => 'number',
						'title' => __( "Number of Products to show", 'shopglut' ),
						'default' => 20,
					),

					// array(
					// 	'id' => 'filter-pagination-style',
					// 	'type' => 'select',
					// 	'title' => __( 'Filter Result Pagination', 'shopglut' ),
					// 	'options' => array(
					// 		'numbers' => __( 'Number Pagination (Default)', 'shopglut' ),
					// 		'loadmore' => __( 'Load More Button', 'shopglut' ),
					// 		'infinite' => __( 'Infinite Scroll', 'shopglut' ),
					// 	),
					// 	'default' => 'numbers',
					// ),

					// array(
					// 	'id' => 'filter-loading-content-show-options',
					// 	'type' => 'button_set',
					// 	'title' => __( 'Show Loading', 'shopglut' ),
					// 	'options' => array(
					// 		'no-loading' => __( 'No Loading Effect', 'shopglut' ),
					// 		'image-loading' => __( 'Loading with Image Effect ', 'shopglut' ),
					// 		'text-loading' => __( 'Loading with Text Effect ', 'shopglut' ),
					// 	),
					// 	'default' => 'no-loading'
					// ),

					// array(
					// 	'id' => 'filter-loading-image',
					// 	'type' => 'media',
					// 	'title' => __( "Loading Image", 'shopglut' ),
					// 	'preview' => true,
					// 	'dependency' => array( 'filter-loading-content-show-options', '==', "image-loading" ),
					// ),


					// array(
					// 	'id' => 'filter-loading-text',
					// 	'type' => 'text',
					// 	'title' => __( "Loading Text", 'shopglut' ),
					// 	'dependency' => array( 'filter-loading-content-show-options', '==', "text-loading" ),
					// ),

					// array(
					// 	'id' => 'filter-loading-image-border',
					// 	'type' => 'border',
					// 	'title' => __( "Loading Image Border", 'shopglut' ),
					// 	'dependency' => array( 'filter-loading-content-show-options', '==', "image-loading" ),
					// ),

					// array(
					// 	'id' => 'filter-loading-text-border',
					// 	'type' => 'border',
					// 	'title' => __( "Loading Text Border", 'shopglut' ),
					// 	'dependency' => array( 'filter-loading-content-show-options', '==', "text-loading" ),
					// ),

					array(
						'id' => 'filter-apply-button-text',
						'type' => 'text',
						'title' => __( "Apply Filter Button Text", 'shopglut' ),
						'dependency' => array( 'filter-option', 'any', "select-submit-filter,select-apply-filter" ),
						'default' => __( "Apply Filter", 'shopglut' ),
					),

					array(
						'id' => 'filter-reset-button-text',
						'type' => 'text',
						'title' => __( "Reset Button Text", 'shopglut' ),
						'dependency' => array( 'filter-option', 'any', "select-submit-filter,select-apply-filter" ),
						'default' => __( " Reset Filter", 'shopglut' ),
					),

				),
			)
			
			
		),
	),

	array(
		'id' => 'save-filter-settings',
		'button_text' => __( 'Save Filter', 'shopglut' ),
		'type' => 'publish',
	),

);


// Loop through attributes and add include/exclude fields for each attribute
if ( ! empty( $shopglut_shopg_attribute_taxonomies ) ) {
	foreach ( $shopglut_shopg_attribute_taxonomies as $shopglut_shopg_attribute ) {
		if ( isset( $shopglut_shopg_attribute->attribute_name ) ) {
			$shopglut_shopg_attribute_name = wc_attribute_taxonomy_name( $shopglut_shopg_attribute->attribute_name );
			$shopglut_shopg_attribute_label = ucfirst( str_replace( '-', ' ', $shopglut_shopg_attribute->attribute_label ) );

			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'filter-' . $shopglut_shopg_attribute_name . '-appearance',
				'type' => 'image_select',
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'title' => sprintf( __( '%s Appearance', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'options' => array(
					'check-list' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/checkbox.png',
					'radio' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/radio.png',
					'dropdown' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/dropdown.png',
					'grid' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/grid.png',
					'button' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/button.png',
					'cloud' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/cloud.png',
					'color' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/color.png',
					'image' => SHOPGLUT_ADMIN_IMAGES . 'filterDesigns/image.png'
				),
				'default' => 'check-list',
				'dependency' => array( 'filter-type', '==', $shopglut_shopg_attribute_name ),
			);
			// Add include/exclude button sets and select fields
			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(

				'id' => 'filter-product-' . $shopglut_shopg_attribute_name . '-images',
				'type' => 'tax_images',
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'title' => sprintf( __( 'Product %s Images', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'product_option' => $shopglut_shopg_attribute_name,
				'dependency' => array(
					array( 'filter-type', '==', $shopglut_shopg_attribute_name ),
					array( 'filter-' . $shopglut_shopg_attribute_name . '-appearance', '==', 'image' ),
				),
			);

			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(

				'id' => 'filter-product-' . $shopglut_shopg_attribute_name . '-color',
				'type' => 'tax_color',
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'title' => sprintf( __( 'Product %s Colors', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'product_option' => $shopglut_shopg_attribute_name,
				'dependency' => array(
					array( 'filter-type', '==', $shopglut_shopg_attribute_name ),
					array( 'filter-' . $shopglut_shopg_attribute_name . '-appearance', '==', 'color' ),
				),
			);

			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'filter-' . $shopglut_shopg_attribute_name . '-exclude-include-button',
				'type' => 'button_set',
				'title' => __( 'Choose Options', 'shopglut' ),
				'options' => array(
					/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
					'all-' . $shopglut_shopg_attribute_name => sprintf( __( 'All %s', 'shopglut' ), $shopglut_shopg_attribute_label ),
					// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- This is a configuration option, not a query parameter
					'exclude' => __( 'Exclude', 'shopglut' ),
					'include' => __( 'Include', 'shopglut' ),
				),
				'default' => 'all-' . $shopglut_shopg_attribute_name,
				'dependency' => array( 'filter-type', '==', $shopglut_shopg_attribute_name ),
			);

			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'shopg-filter-include-' . $shopglut_shopg_attribute_name,
				'type' => 'select',
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'title' => sprintf( esc_html__( 'Include %s', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'chosen' => true,
				'multiple' => true,
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'placeholder' => sprintf( esc_html__( 'Choose %s', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'options' => 'categories',
				'query_args' => array(
					'taxonomy' => $shopglut_shopg_attribute_name,
				),
				'dependency' => array( 'filter-' . $shopglut_shopg_attribute_name . '-exclude-include-button|filter-type', '==|==', "include|$shopglut_shopg_attribute_name" ),
			);

			$shopglut_shopg_fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'shopg-filter-exclude-' . $shopglut_shopg_attribute_name,
				'type' => 'select',
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'title' => sprintf( esc_html__( 'Exclude %s', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'chosen' => true,
				'multiple' => true,
				/* translators: %s: Attribute label (e.g., 'Color', 'Size') */
				'placeholder' => sprintf( esc_html__( 'Choose %s', 'shopglut' ), $shopglut_shopg_attribute_label ),
				'options' => 'categories',
				'query_args' => array(
					'taxonomy' => $shopglut_shopg_attribute_name,
				),
				'dependency' => array( 'filter-' . $shopglut_shopg_attribute_name . '-exclude-include-button|filter-type', '==|==', "exclude|$shopglut_shopg_attribute_name" ),
			);

		}
	}
}



// Final section creation
AGSHOPGLUT::createSection(
	$shopglut_shopg_options_settings,
	array(
		'fields' => $shopglut_shopg_fields, // Use the $shopglut_shopg_fields array here
	)
);