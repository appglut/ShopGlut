<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

AGSHOPGLUT::createMetabox(
	'shopg_live_preview',
	array(
		'title' => __( 'Live Preview', 'shopglut' ),
		'post_type' => 'shopglut_layouts',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

$SHOPG_OPTIONS_SETTINGS = "shopg_options_settings";

AGSHOPGLUT::createMetabox(
	$SHOPG_OPTIONS_SETTINGS,
	array(
		'title' => esc_html__( 'Layout Settings', 'shopglut' ),
		'post_type' => 'shopglut_layouts',
		'context' => 'side',

	)
);

AGSHOPGLUT::createSection(
	$SHOPG_OPTIONS_SETTINGS,
	array(
		'fields' => array(

			array(
				'id' => 'shopg_settings_options',
				'type' => 'tabbed',
				'tabs' => array(
					array(
						'id' => 'shopg_settings_content',
						'class' => 'shopg_settings_content',
						'title' => esc_html__( 'Content', 'shopglut' ),
						'icon' => 'fa fa-up-down-left-right',
						'fields' => array(
							array(
								'id' => 'shopg_settings_accordion',
								'type' => 'accordion',
								'accordions' => array(
									array(
										'id' => 'shopg_settings_accordion_filter_shop_page',
										'title' => esc_html__( 'Filter Products', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'shopg-layouts-include-products',
												'type' => 'select',
												'title' => esc_html__( 'Include Products', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Product', 'shopglut' ),
												'options' => 'post',
												'query_args' => array(
													'post_type' => 'product',
												),
											),
											array(
												'id' => 'shopg-layouts-exclude-products',
												'type' => 'select',
												'title' => esc_html__( 'Exclude Products', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Product', 'shopglut' ),
												'options' => 'post',
												'query_args' => array(
													'post_type' => 'product',
												),
											),
											array(
												'id' => 'shopg-layouts-include-categories',
												'type' => 'select',
												'title' => esc_html__( 'Include Categories', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
												'options' => 'categories',
												'query_args' => array(
													'taxonomy' => 'product_cat',
												),
											),
											array(
												'id' => 'shopg-layouts-exclude-categories',
												'type' => 'select',
												'title' => esc_html__( 'Exclude Categories', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
												'options' => 'categories',
												'query_args' => array(
													'taxonomy' => 'product_cat',
												),
											),
											array(
												'id' => 'shopg-layouts-include-tags',
												'type' => 'select',
												'title' => esc_html__( 'Include Tags', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Tag', 'shopglut' ),
												'options' => 'tags',
												'query_args' => array(
													'taxonomy' => 'product_tag',
												),
											),
											array(
												'id' => 'shopg-layouts-exclude-tags',
												'type' => 'select',
												'title' => esc_html__( 'Exclude Tags', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Tag', 'shopglut' ),
												'options' => 'tags',
												'query_args' => array(
													'taxonomy' => 'product_tag',
												),
											),
											array(
												'id' => 'shopg-layouts-product-type',
												'type' => 'select',
												'title' => esc_html__( 'Product Type', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'placeholder' => esc_html__( 'Choose Type', 'shopglut' ),
												'options' => 'categories',
												'query_args' => array(
													'taxonomy' => 'product_type',
												),
											),

											array(
												'id' => 'shopg-layouts-product-options',
												'type' => 'select',
												'title' => esc_html__( 'Product Options', 'shopglut' ),
												'options' => array(
													'all-products' => esc_html__( 'All Products', 'shopglut' ),
													'best-selling' => esc_html__( 'Best Selling Products', 'shopglut' ),
													'recent-products' => esc_html__( 'Recent Products', 'shopglut' ),
													'featured-products' => esc_html__( 'Featured Products', 'shopglut' ),
													'rated-products' => esc_html__( 'Top Rated Products', 'shopglut' ),
													'sale-products' => esc_html__( 'Sale Products', 'shopglut' ),
													'in-stock' => esc_html__( 'In Stock Products', 'shopglut' ),
													'out-of-stock' => esc_html__( 'Out Of Products', 'shopglut' ),
												),
												'default' => 'all-products',
											),

											array(
												'id' => 'shopg-layouts-product-sorting',
												'type' => 'select',
												'title' => esc_html__( 'Sort Products', 'shopglut' ),
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
													'random' => esc_html__( 'Random Order', 'shopglut' ),
												),
												'default' => 'title',
											),

											array(
												'id' => 'shopg-layouts-product-order',
												'type' => 'select',
												'title' => esc_html__( 'Sort Order', 'shopglut' ),
												'options' => array(
													'ASC' => esc_html__( 'Ascending', 'shopglut' ),
													'DESC' => esc_html__( 'Descending', 'shopglut' ),
												),
												'default' => 'ASC',
											),
										),
									),

									array(
										'id' => 'shopg_settings_cart_settings_shop_page',
										'title' => esc_html__( 'Cart Settings', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(
											array(
												'id' => 'show-add-to-cart',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Add To Cart', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),

											array(
												'id' => 'show-add-to-cart-text',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Add To Cart Text', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
												'dependency' => array( 'show-add-to-cart', '==', 'true' ),
											),

											array(
												'id' => 'shopg-change-cart-text',
												'type' => 'text',
												'title' => esc_html__( 'Cart Text', 'shopglut' ),
												'default' => esc_html__( 'Add To Cart', 'shopglut' ),
												'dependency' => array( 'show-add-to-cart|show-add-to-cart-text', '==|==', 'true|true' ),
											),

											array(
												'id' => 'shopg-change-cart-icon',
												'type' => 'icon',
												'title' => esc_html__( 'Cart Icon', 'shopglut' ),
												'default' => 'fa fa-shopping-cart',
												'dependency' => array( 'show-add-to-cart', '==', 'true' ),
											),
											array(
												'id' => 'shopg-change-added-cart-icon',
												'type' => 'icon',
												'title' => esc_html__( 'Added Cart Icon', 'shopglut' ),
												'default' => 'fa fa-shopping-cart',
												'dependency' => array( 'show-add-to-cart', '==', 'true' ),
											),
										),
									),
									array(
										'id' => 'shopg_settings_wishlist_settings_shop_page',
										'title' => esc_html__( 'Wishlist Module', 'shopglut' ),
										'icon' => 'fa fa-heart',
										'fields' => array(
											array(
												'id' => 'enable_wishlist',
												'type' => 'switcher',
												'title' => esc_html__( 'Enable Wishlist Button', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
												'subtitle' => esc_html__( 'Add/remove functionality linked to main Wishlist module.', 'shopglut' ),
											),
											array(
												'type' => 'subheading',
												'content' => esc_html__( 'Wishlist Icons', 'shopglut' ),
												'dependency' => array( 'enable_wishlist', '==', 'true' ),
											),
											array(
												'id' => 'wishlist_add_icon',
												'type' => 'icon',
												'title' => esc_html__( 'Add Icon', 'shopglut' ),
												'subtitle' => esc_html__( 'Icon shown when product is NOT in wishlist', 'shopglut' ),
												'default' => 'fa-regular fa-heart',
												'dependency' => array( 'enable_wishlist', '==', 'true' ),
											),
											array(
												'id' => 'wishlist_added_icon',
												'type' => 'icon',
												'title' => esc_html__( 'Remove Icon', 'shopglut' ),
												'subtitle' => esc_html__( 'Icon shown when product IS in wishlist (added state)', 'shopglut' ),
												'default' => 'fa-solid fa-heart',
												'dependency' => array( 'enable_wishlist', '==', 'true' ),
											),
											array(
												'id' => 'wishlist_icon_color',
												'type' => 'color',
												'title' => esc_html__( 'Wishlist Icon Color', 'shopglut' ),
												'dependency' => array( 'enable_wishlist', '==', 'true' ),
											),
											array(
												'id' => 'wishlist_bg_color',
												'type' => 'color',
												'title' => esc_html__( 'Wishlist Background Color', 'shopglut' ),
												'dependency' => array( 'enable_wishlist', '==', 'true' ),
											),
										),
									),
									array(
										'id' => 'shopg_settings_compare_settings_shop_page',
										'title' => esc_html__( 'Product Comparison Module', 'shopglut' ),
										'icon' => 'fa fa-exchange-alt',
										'fields' => array(
											array(
												'id' => 'enable_comparison',
												'type' => 'switcher',
												'title' => esc_html__( 'Enable Comparison Button', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
												'subtitle' => esc_html__( 'Add/remove functionality linked to main Comparison module. Selected layout will be displayed when user clicks comparison.', 'shopglut' ),
											),
											array(
												'id' => 'comparison_layout_id',
												'type' => 'select_comparison_layouts',
												'title' => esc_html__( 'Select Comparison Layout', 'shopglut' ),
												'placeholder' => esc_html__( 'Select a comparison layout', 'shopglut' ),
												'chosen' => true,
												'dependency' => array( 'enable_comparison', '==', 'true' ),
											),
											array(
												'id' => 'comparison_icon',
												'type' => 'icon',
												'title' => esc_html__( 'Comparison Icon', 'shopglut' ),
												'subtitle' => esc_html__( 'Icon shown when product is NOT in comparison', 'shopglut' ),
												'default' => 'fa fa-refresh',
												'dependency' => array( 'enable_comparison', '==', 'true' ),
											),
											array(
												'id' => 'comparison_added_icon',
												'type' => 'icon',
												'title' => esc_html__( 'Added to Comparison Icon', 'shopglut' ),
												'subtitle' => esc_html__( 'Icon shown when product IS in comparison', 'shopglut' ),
												'default' => 'fa fa-check-circle',
												'dependency' => array( 'enable_comparison', '==', 'true' ),
											),
											array(
												'id' => 'comparison_icon_color',
												'type' => 'color',
												'title' => esc_html__( 'Comparison Icon Color', 'shopglut' ),
												'dependency' => array( 'enable_comparison', '==', 'true' ),
											),
											array(
												'id' => 'comparison_bg_color',
												'type' => 'color',
												'title' => esc_html__( 'Comparison Background Color', 'shopglut' ),
												'dependency' => array( 'enable_comparison', '==', 'true' ),
											),
										),
									),
									array(
										'id' => 'shopg_settings_quickview_settings_shop_page',
										'title' => esc_html__( 'Quick View Module', 'shopglut' ),
										'icon' => 'fa fa-eye',
										'fields' => array(
											array(
												'id' => 'enable_quickview',
												'type' => 'switcher',
												'title' => esc_html__( 'Enable Quick View Button', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
												'subtitle' => esc_html__( 'Select quick view layout below. Popup will use chosen layout.', 'shopglut' ),
											),
											array(
												'id' => 'quickview_layout_id',
												'type' => 'select_quickview_layouts',
												'title' => esc_html__( 'Select Quick View Layout', 'shopglut' ),
												'placeholder' => esc_html__( 'Select a quick view layout', 'shopglut' ),
												'chosen' => true,
												'dependency' => array( 'enable_quickview', '==', 'true' ),
											),
											array(
												'id' => 'quickview_icon',
												'type' => 'icon',
												'title' => esc_html__( 'Quick View Icon', 'shopglut' ),
												'default' => 'fa-regular fa-eye',
												'dependency' => array( 'enable_quickview', '==', 'true' ),
											),
											array(
												'id' => 'quickview_icon_color',
												'type' => 'color',
												'title' => esc_html__( 'Quick View Icon Color', 'shopglut' ),
												'dependency' => array( 'enable_quickview', '==', 'true' ),
											),
											array(
												'id' => 'quickview_bg_color',
												'type' => 'color',
												'title' => esc_html__( 'Quick View Background Color', 'shopglut' ),
												'dependency' => array( 'enable_quickview', '==', 'true' ),
											),
										),
									),
									array(
										'id' => 'shopg_settings_badges_shop_page',
										'title' => esc_html__( 'Product Badges Module', 'shopglut' ),
										'icon' => 'fa fa-certificate',
										'fields' => array(
											array(
												'id' => 'enable_badges',
												'type' => 'switcher',
												'title' => esc_html__( 'Enable Product Badges', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
												'subtitle' => esc_html__( 'Select badge layout from Badge modules.', 'shopglut' ),
											),
											array(
												'id' => 'badge_layout_id',
												'type' => 'select_badge_layouts',
												'title' => esc_html__( 'Select Badge Layout', 'shopglut' ),
												'placeholder' => esc_html__( 'Select a badge layout', 'shopglut' ),
												'chosen' => true,
												'dependency' => array( 'enable_badges', '==', 'true' ),
											),
										),
									),
									array(
										'id' => 'shopg_settings_accordion_hide_show',
										'title' => esc_html__( 'Hide & Show', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(
											array(
												'id' => 'shopg-show-title',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Title', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),
											array(
												'id' => 'shopg-show-price',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Price', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),
											array(
												'id' => 'shopg-show-image',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Image', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),

											array(
												'id' => 'shopg-show-category',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Category', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),

											array(
												'id' => 'shopg-show-review',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Review', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),

											array(
												'id' => 'shopg-show-badge',
												'type' => 'switcher',
												'title' => esc_html__( 'Show Badge', 'shopglut' ),
												'text_on' => esc_html__( 'Yes', 'shopglut' ),
												'text_off' => esc_html__( 'No', 'shopglut' ),
												'default' => true,
											),
										),
									),

								),
							),
						),
					),
					array(
						'id' => 'shopg_settings_style',
						'class' => 'shopg_settings_style',
						'title' => esc_html__( 'Style', 'shopglut' ),
						'icon' => 'fa fa-palette',
						'fields' => array(
							array(
								'id' => 'shopg_style_settings_accordion',
								'type' => 'accordion',
								'accordions' => array(
									array(
										'id' => 'shopg_style_shop_body_settings',
										'title' => esc_html__( 'Shop Body', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'shopg-column-grid',
												'type' => 'select',
												'active_device' => true,
												'title' => __( 'Column Grid', 'shopglut' ),
												'placeholder' => __( 'Select an option', 'shopglut' ),
												'options' => array(
													'col-1' => __( 'Column 1', 'shopglut' ),
													'col-2' => __( 'Column 2', 'shopglut' ),
													'col-3' => __( 'Column 3', 'shopglut' ),
													'col-4' => __( 'Column 4', 'shopglut' ),
													'col-5' => __( 'Column 5', 'shopglut' ),
													'col-6' => __( 'Column 6', 'shopglut' ),
													'col-7' => __( 'Column 7', 'shopglut' ),
													'col-8' => __( 'Column 8', 'shopglut' ),
													'col-9' => __( 'Column 9', 'shopglut' ),
													'col-10' => __( 'Column 10', 'shopglut' ),
													'col-11' => __( 'Column 11', 'shopglut' ),
													'col-12' => __( 'Column 12', 'shopglut' ),
												),
												'default' => 'col-3',
											),

											array(
												'id' => 'shopg-column-gap',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Column Distance', 'shopglut' ),
												'min' => 0,
												'max' => 500,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 10,
											),

											array(
												'id' => 'shopg-row-gap',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Row Distance', 'shopglut' ),
												'min' => 0,
												'max' => 500,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
											),

											array(
												'id' => 'shopbody-margin',
												'type' => 'space',
												'title' => __( 'Shop Margin', 'shopglut' ),
												'active_device' => true,
											),

											array(
												'id' => 'shopbody-padding',
												'type' => 'space',
												'title' => __( 'Shop Padding', 'shopglut' ),
												'active_device' => true,
											),

											array(
												'id' => 'shopbody-typography',
												'type' => 'typography',
												'title' => __( 'Shop Typography', 'shopglut' ),
												'text_transform' => true,
												'font_size' => false,
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'line_height' => false,
												'letter_spacing' => false,
												'word_spacing' => false,
												'text_align' => false,
												'color' => false,
												'hover_color' => false,
												'active_color' => false,
												'preview' => false,
											),

											array(
												'id' => 'shopbody-normal-background',
												'type' => 'color',
												'title' => __( 'Normal Background Color', 'shopglut' ),
											),

											array(
												'id' => 'shopbody-hover-background',
												'type' => 'color',
												'title' => __( 'Hover Background Color', 'shopglut' ),
											),

											array(
												'id' => 'shopbody-border',
												'type' => 'border',
												'title' => __( 'Shop Border', 'shopglut' ),
											),

										),
									),
									array(
										'id' => 'shopg_settings_product_spacing',
										'title' => esc_html__( 'Product Body', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'product-margin',
												'type' => 'space',
												'title' => __( 'Product Margin', 'shopglut' ),
												'active_device' => true,
											),

											array(
												'id' => 'product-padding',
												'type' => 'space',
												'title' => __( 'Product Padding', 'shopglut' ),
												'active_device' => true,
											),

											array(
												'id' => 'product-normal-background',
												'type' => 'color',
												'title' => __( 'Product Background Color', 'shopglut' ),
											),

											array(
												'id' => 'product-hover-background',
												'type' => 'color',
												'title' => __( 'Product Hover Background Color', 'shopglut' ),
											),

											array(
												'id' => 'product-border',
												'type' => 'border',
												'title' => __( 'Product Border', 'shopglut' ),
												'default' => array(
													'top' => '1',
													'right' => '1',
													'bottom' => '1',
													'left' => '1',
													'style' => 'solid',
													'color' => '#ddd',
													'unit' => 'px',
												),
											),

											array(
												'id' => 'product-radius',
												'type' => 'space',
												'title' => __( 'Border Radius', 'shopglut' ),
												'active_device' => true,
											),

										),
									),
									array(
										'id' => 'shopg_settings_product_content',
										'title' => esc_html__( 'Product Content', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'product-caption-background',
												'type' => 'color',
												'title' => __( 'Product Caption Background', 'shopglut' ),
											),

											array(
												'id' => 'product-caption-hover-background',
												'type' => 'color',
												'title' => __( 'Product Caption Hover Background', 'shopglut' ),
											),

											array(
												'id' => 'product-content-options',
												'type' => 'button_set',
												'title' => __( 'Choose The Option', 'shopglut' ),
												'options' => array(
													'product-title' => __( 'Product Title', 'shopglut' ),
													'product-category' => __( 'Product Category', 'shopglut' ),
													'product-price' => __( 'Product Price', 'shopglut' ),
													'product-review' => __( 'Product Review', 'shopglut' ),
												),
												'default' => 'product-title',
											),

											array(
												'id' => 'product-title',
												'type' => 'typography',
												'title' => __( 'Product Title', 'shopglut' ),
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'text_align' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-content-options', '==', 'product-title' ),
												'default' => array(
													'color' => '#000',
													'font-size' => '15',
													'line-height' => '3',
													'letter-spacing' => '0',
												),
											),
											array(
												'id' => 'product-category',
												'type' => 'typography',
												'title' => __( 'Product Category', 'shopglut' ),
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'text_align' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-content-options', '==', 'product-category' ),
												'default' => array(
													'color' => '#000',
													'font-size' => '15',
													'line-height' => '20',
													'letter-spacing' => '0',
												),
											),
											array(
												'id' => 'product-price',
												'type' => 'typography',
												'title' => __( 'Product Price', 'shopglut' ),
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'text_align' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-content-options', '==', 'product-price' ),
												'default' => array(
													'color' => '#000',
													'font-size' => '15',
													'line-height' => '3',
													'letter-spacing' => '0',
												),
											),

											array(
												'id' => 'product-old-color',
												'type' => 'color',
												'title' => __( 'Price Old Color', 'shopglut' ),
												'dependency' => array( 'product-content-options', '==', 'product-price' ),
											),

											array(
												'id' => 'product-old-hover-color',
												'type' => 'color',
												'title' => __( 'Price Old Hover Color', 'shopglut' ),
												'dependency' => array( 'product-content-options', '==', 'product-price' ),
											),

											array(
												'id' => 'product-review-color',
												'type' => 'color',
												'title' => __( 'Product Review Color', 'shopglut' ),
												'dependency' => array( 'product-content-options', '==', 'product-review' ),
											),

										),
									),
									array(
										'id' => 'shopg_settings_product_icons',
										'title' => esc_html__( 'Product Options', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'add-to-cart-options',
												'type' => 'button_set',
												'title' => __( 'Choose Cart Option', 'shopglut' ),
												'options' => array(
													'cart-normal' => __( 'Cart Normal', 'shopglut' ),
													'cart-hover' => __( 'Cart on Hover', 'shopglut' ),
												),
												'default' => 'cart-normal',
											),
											array(
												'id' => 'add-to-cart-font-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Font Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),
											array(
												'id' => 'add-to-cart-icon-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Icon Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),
											array(
												'id' => 'add-to-cart-bg-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Background Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),

											array(
												'id' => 'add-to-cart-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Add to Cart Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 142,
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),

											array(
												'id' => 'add-to-cart-margin',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Add to Cart Margin', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),

											array(
												'id' => 'add-to-cart-padding',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Add to Cart Padding', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-normal' ),
											),
											array(
												'id' => 'add-to-cart-hover-font-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Font Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-hover' ),
											),
											array(
												'id' => 'add-to-cart-hover-icon-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Icon Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-hover' ),
											),
											array(
												'id' => 'add-to-cart-hover-bg-color',
												'type' => 'color',
												'title' => __( 'Add to Cart Background Color', 'shopglut' ),
												'dependency' => array( 'add-to-cart-options', '==', 'cart-hover' ),
											),
											// Wishlist Options
											array(
												'id' => 'wishlist-options',
												'type' => 'button_set',
												'title' => __( 'Choose Wishlist Option', 'shopglut' ),
												'options' => array(
													'wishlist-normal' => __( 'Wishlist Normal', 'shopglut' ),
													'wishlist-hover' => __( 'Wishlist on Hover', 'shopglut' ),
												),
												'default' => 'wishlist-normal',
											),
											array(
												'id' => 'wishlist-icon-color',
												'type' => 'color',
												'title' => __( 'Wishlist Icon Color', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-normal' ),
											),
											array(
												'id' => 'wishlist-bg-color',
												'type' => 'color',
												'title' => __( 'Wishlist Background Color', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-normal' ),
											),
											array(
												'id' => 'wishlist-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Wishlist Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 38,
												'dependency' => array( 'wishlist-options', '==', 'wishlist-normal' ),
											),
											array(
												'id' => 'wishlist-margin',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Wishlist Margin', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-normal' ),
											),
											array(
												'id' => 'wishlist-padding',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Wishlist Padding', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-normal' ),
											),
											array(
												'id' => 'wishlist-hover-icon-color',
												'type' => 'color',
												'title' => __( 'Wishlist Icon Color', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-hover' ),
											),
											array(
												'id' => 'wishlist-hover-bg-color',
												'type' => 'color',
												'title' => __( 'Wishlist Background Color', 'shopglut' ),
												'dependency' => array( 'wishlist-options', '==', 'wishlist-hover' ),
											),

											// Compare Options
											array(
												'id' => 'compare-options',
												'type' => 'button_set',
												'title' => __( 'Choose Compare Option', 'shopglut' ),
												'options' => array(
													'compare-normal' => __( 'Compare Normal', 'shopglut' ),
													'compare-hover' => __( 'Compare on Hover', 'shopglut' ),
												),
												'default' => 'compare-normal',
											),
											array(
												'id' => 'compare-icon-color',
												'type' => 'color',
												'title' => __( 'Compare Icon Color', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-normal' ),
											),
											array(
												'id' => 'compare-bg-color',
												'type' => 'color',
												'title' => __( 'Compare Background Color', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-normal' ),
											),
											array(
												'id' => 'compare-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Compare Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 38,
												'dependency' => array( 'compare-options', '==', 'compare-normal' ),
											),
											array(
												'id' => 'compare-margin',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Compare Margin', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-normal' ),
											),
											array(
												'id' => 'compare-padding',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Compare Padding', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-normal' ),
											),
											array(
												'id' => 'compare-hover-icon-color',
												'type' => 'color',
												'title' => __( 'Compare Icon Color', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-hover' ),
											),
											array(
												'id' => 'compare-hover-bg-color',
												'type' => 'color',
												'title' => __( 'Compare Background Color', 'shopglut' ),
												'dependency' => array( 'compare-options', '==', 'compare-hover' ),
											),

											// Quick View Options
											array(
												'id' => 'quick-view-options',
												'type' => 'button_set',
												'title' => __( 'Choose Quick View Option', 'shopglut' ),
												'options' => array(
													'quick-view-normal' => __( 'Quick View Normal', 'shopglut' ),
													'quick-view-hover' => __( 'Quick View on Hover', 'shopglut' ),
												),
												'default' => 'quick-view-normal',
											),
											array(
												'id' => 'quick-view-icon-color',
												'type' => 'color',
												'title' => __( 'Quick View Icon Color', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-normal' ),
											),
											array(
												'id' => 'quick-view-bg-color',
												'type' => 'color',
												'title' => __( 'Quick View Background Color', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-normal' ),
											),
											array(
												'id' => 'quick-view-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Quick View Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 38,
												'dependency' => array( 'quick-view-options', '==', 'quick-view-normal' ),
											),
											array(
												'id' => 'quick-view-margin',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Quick View Margin', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-normal' ),
											),
											array(
												'id' => 'quick-view-padding',
												'type' => 'space',
												'active_device' => true,
												'title' => __( 'Quick View Padding', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-normal' ),
											),
											array(
												'id' => 'quick-view-hover-icon-color',
												'type' => 'color',
												'title' => __( 'Quick View Icon Color', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-hover' ),
											),
											array(
												'id' => 'quick-view-hover-bg-color',
												'type' => 'color',
												'title' => __( 'Quick View Background Color', 'shopglut' ),
												'dependency' => array( 'quick-view-options', '==', 'quick-view-hover' ),
											),

										),
									),
									array(
										'id' => 'shopg_settings_product_image',
										'title' => esc_html__( 'Product Image', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'image-link-option',
												'type' => 'button_set',
												'title' => __( 'Image Product Link', 'shopglut' ),
												'options' => array(
													'enable' => __( 'Enable', 'shopglut' ),
													'disable' => __( 'Disable', 'shopglut' ),
												),
												'default' => 'enable',
											),

											array(
												'id' => 'image-product-open-newtab',
												'type' => 'switcher',
												'title' => __( 'Product Link Open NewTab', 'shopglut' ),
												'text_on' => __( 'Yes', 'shopglut' ),
												'text_off' => __( 'No', 'shopglut' ),
												'dependency' => array( 'image-link-option', '==', 'enable' ),
												'default' => 'text_on',
											),

											array(
												'id' => 'image-padding',
												'type' => 'space',
												'title' => __( 'Image Padding', 'shopglut' ),
												'active_device' => true,
											),

											array(
												'id' => 'image-normal-background',
												'type' => 'color',
												'title' => __( 'Image Background Color', 'shopglut' ),
											),

											array(
												'id' => 'image-hover-background',
												'type' => 'color',
												'title' => __( 'Image Hover Background Color', 'shopglut' ),
											),

										),
									),
									array(
										'id' => 'shopg_settings_product_badge',
										'title' => esc_html__( 'Product Badges', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(

											array(
												'id' => 'product-badges-options',
												'type' => 'button_set',
												'title' => __( 'Choose The Badge', 'shopglut' ),
												'options' => array(
													'new' => __( 'New', 'shopglut' ),
													'outof-stock' => __( 'Out Of Stock', 'shopglut' ),
													'featured' => __( 'Featured', 'shopglut' ),
													'discount' => __( 'Discount', 'shopglut' ),
												),
												'default' => 'new',
											),

											array(
												'id' => 'new-badge-margin',
												'type' => 'space',
												'title' => __( 'New Badge Margin', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-badge-padding',
												'type' => 'space',
												'title' => __( 'New Badge Padding', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-product-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'New Badge Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-product-height',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'New Badge Height', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-product-color',
												'type' => 'color',
												'title' => __( 'New Badge Font Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-product-bgcolor',
												'type' => 'color',
												'title' => __( 'New Badge Background Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-badge-position',
												'type' => 'button_set',
												'title' => __( 'New Badge Position', 'shopglut' ),
												'options' => array(
													'left' => __( 'Left', 'shopglut' ),
													'center' => __( 'Middle', 'shopglut' ),
													'right' => __( 'Right', 'shopglut' ),
												),
												'default' => 'left',
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-badge-border',
												'type' => 'border',
												'title' => __( 'New Badge Border', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-badge-border-radius',
												'type' => 'space',
												'title' => __( 'New Badge Border Radius', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'new-badge-typography',
												'type' => 'typography',
												'title' => __( 'New Badge Typography', 'shopglut' ),
												'text_transform' => true,
												'font_size' => false,
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'line_height' => false,
												'letter_spacing' => false,
												'word_spacing' => false,
												'text_align' => false,
												'color' => false,
												'hover_color' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-badges-options', '==', 'new' ),
											),

											array(
												'id' => 'outof-stock-badge-margin',
												'type' => 'space',
												'title' => __( 'Out-Of Stock Badge Margin', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-badge-padding',
												'type' => 'space',
												'title' => __( 'Out-Of Stock Badge Padding', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-product-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Out-Of Stock Badge Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-product-height',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Out-Of Stock Badge Height', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-product-color',
												'type' => 'color',
												'title' => __( 'Out-Of Stock Badge Font Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-product-bgcolor',
												'type' => 'color',
												'title' => __( 'Out-Of Stock Badge Background Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-badge-position',
												'type' => 'button_set',
												'title' => __( 'Out-Of Stock Badge Position', 'shopglut' ),
												'options' => array(
													'left' => __( 'Left', 'shopglut' ),
													'center' => __( 'Middle', 'shopglut' ),
													'right' => __( 'Right', 'shopglut' ),
												),
												'default' => 'left',
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-badge-border',
												'type' => 'border',
												'title' => __( 'Out-Of Stock Badge Border', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-badge-border-radius',
												'type' => 'space',
												'title' => __( 'Out-Of Stock Badge Border Radius', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'outof-stock-badge-typography',
												'type' => 'typography',
												'title' => __( 'Out-Of Stock Badge Typography', 'shopglut' ),
												'text_transform' => true,
												'font_size' => false,
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'line_height' => false,
												'letter_spacing' => false,
												'word_spacing' => false,
												'text_align' => false,
												'color' => false,
												'hover_color' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-badges-options', '==', 'outof-stock' ),
											),

											array(
												'id' => 'featured-badge-margin',
												'type' => 'space',
												'title' => __( 'Featured Badge Margin', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-badge-padding',
												'type' => 'space',
												'title' => __( 'Featured Badge Padding', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-product-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Featured Badge Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-product-height',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Featured Badge Height', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-product-color',
												'type' => 'color',
												'title' => __( 'Featured Badge Font Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-product-bgcolor',
												'type' => 'color',
												'title' => __( 'Featured Badge Background Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-badge-position',
												'type' => 'button_set',
												'title' => __( 'Featured Badge Position', 'shopglut' ),
												'options' => array(
													'left' => __( 'Left', 'shopglut' ),
													'center' => __( 'Middle', 'shopglut' ),
													'right' => __( 'Right', 'shopglut' ),
												),
												'default' => 'left',
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-badge-border',
												'type' => 'border',
												'title' => __( 'Featured Badge Border', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-badge-border-radius',
												'type' => 'space',
												'title' => __( 'Featured Badge Border Radius', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'featured-badge-typography',
												'type' => 'typography',
												'title' => __( 'Featured Badge Typography', 'shopglut' ),
												'text_transform' => true,
												'font_size' => false,
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'line_height' => false,
												'letter_spacing' => false,
												'word_spacing' => false,
												'text_align' => false,
												'color' => false,
												'hover_color' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-badges-options', '==', 'featured' ),
											),

											array(
												'id' => 'discount-badge-margin',
												'type' => 'space',
												'title' => __( 'Discount Badge Margin', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-padding',
												'type' => 'space',
												'title' => __( 'Discount Badge Padding', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-width',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Discount Badge Width', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-height',
												'type' => 'slider',
												'active_device' => true,
												'title' => __( 'Discount Badge Height', 'shopglut' ),
												'min' => 0,
												'max' => 400,
												'step' => 1,
												'units' => array( 'px', '%', 'em' ),
												'default' => 25,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-color',
												'type' => 'color',
												'title' => __( 'Discount Badge Font Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-bgcolor',
												'type' => 'color',
												'title' => __( 'Discount Badge Background Color', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-position',
												'type' => 'button_set',
												'title' => __( 'Discount Badge Position', 'shopglut' ),
												'options' => array(
													'left' => __( 'Left', 'shopglut' ),
													'center' => __( 'Middle', 'shopglut' ),
													'right' => __( 'Right', 'shopglut' ),
												),
												'default' => 'left',
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-border',
												'type' => 'border',
												'title' => __( 'Discount Badge Border', 'shopglut' ),
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-border-radius',
												'type' => 'space',
												'title' => __( 'Discount Badge Border Radius', 'shopglut' ),
												'active_device' => true,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

											array(
												'id' => 'discount-badge-typography',
												'type' => 'typography',
												'title' => __( 'Discount Badge Typography', 'shopglut' ),
												'text_transform' => true,
												'font_size' => false,
												'font_weights' => true,
												'font_variant' => false,
												'font_styles' => true,
												'line_height' => false,
												'letter_spacing' => false,
												'word_spacing' => false,
												'text_align' => false,
												'color' => false,
												'hover_color' => false,
												'active_color' => false,
												'preview' => false,
												'dependency' => array( 'product-badges-options', '==', 'discount' ),
											),

										),
									),

								),
							),
						),
					),
					array(
						'id' => 'shopg_display_settings',
						'class' => 'shopg_display_settings',
						'title' => esc_html__( 'Display Settings', 'shopglut' ),
						'icon' => 'fa fa-desktop',
						'fields' => array(
							array(
								'id' => 'shopg_display_settings_accordion',
								'type' => 'accordion',
								'accordions' => array(

									array(
										'id' => 'shopg_settings_accordion_showon_page',
										'title' => esc_html__( 'Layout Application', 'shopglut' ),
										'icon' => 'fa fa-map-marker',
										'fields' => array(

											array(
												'id' => 'select-archive-pages',
												'type' => 'select_archive_page',
												'title' => __( 'Select Archive Pages', 'shopglut' ),
												'subtitle' => __( 'Choose WooCommerce archive pages (categories, tags) where this layout should be applied', 'shopglut' ),
												'placeholder' => __( 'Select Archive Pages', 'shopglut' ),
												'chosen' => true,
												'multiple' => true,
												'options' => 'select_archive_page'
											),

											array(
												'id' => 'overwrite-shop-page',
												'type' => 'switcher',
												'title' => __( 'Overwrite Woocommerce Shop', 'shopglut' ),
												'text_on' => __( 'Yes', 'shopglut' ),
												'text_off' => __( 'No', 'shopglut' ),
											),


										),
									),

								),
							),
						),
					),
					array(
						'id' => 'shopg_advanced_settings',
						'class' => 'shopg_advanced_settings',
						'title' => esc_html__( 'Advanced', 'shopglut' ),
						'icon' => 'fa fa-gear',
						'fields' => array(
							array(
								'id' => 'shopg_advanced_settings_accordion',
								'type' => 'accordion',
								'accordions' => array(

									array(
										'id' => 'shopg_settings_accordion_filter_shop_page',
										'title' => esc_html__( 'Pagination', 'shopglut' ),
										'icon' => 'fa fa-table-columns',
										'fields' => array(


											array(
												'id' => 'pagination-product-no',
												'type' => 'number',
												'title' => __( 'Product Per Page', 'shopglut' ),
												'default' => '15',
											),

											array(
												'id' => 'pagination-style',
												'type' => 'radio',
												'title' => __( 'Choose Pagination Style', 'shopglut' ),
												'options' => array(
													'pagination-number' => __( 'Number Paginate', 'shopglut' ),
													// 'pagination-loadmore' => __( 'LoadMore Style', 'shopglut' ),
													// 'pagination-nextprev' => __( 'PrevNext Paginate', 'shopglut' ),
												),
												'default' => 'pagination-number',
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
				'id' => 'shopglayout-publishing-action',
				'button_text' => __( 'Save Layout', 'shopglut' ),
				'type' => 'publish',
			),

		),

	)
);