<?php

namespace Shopglut\layouts\shopLayout;

class dynamicStyle {

	public function dynamicCss( $layout_id ) {

		global $wpdb;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $layout_id ) );

		if ( ! empty( $layout_values ) ) {

			$layout_template = $layout_values[0]->layout_template;

			$layout_data_array = isset( $layout_values[0]->layout_settings ) ? unserialize( $layout_values[0]->layout_settings ) : array();

			$layout_array_values = isset( $layout_data_array['shopg_options_settings']['shopg_settings_options'] ) ? $layout_data_array['shopg_options_settings']['shopg_settings_options'] : '';

			$column_grid_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] )
				: '3';

			if ( $layout_template === 'template6' || $layout_template === 'template7' || $layout_template === 'template8' || $layout_template === 'template9' ) {
				$column_grid_desktop = '2';
			}

			$column_grid_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-tablet'] )
				: '2';

			$column_grid_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-grid']['shopg-column-grid-select-type-mobile'] )
				: '1';

			$column_gap_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_desktop'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_desktop'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-desktop'] )
				: '15px';

			$column_gap_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_tablet'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_tablet'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-tablet'] )
				: '10px';

			$column_gap_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_mobile'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['slider_value_mobile'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-column-gap']['unit-mobile'] )
				: '10px';

			$row_gap_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_desktop'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_desktop'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-desktop'] )
				: '15px';

			$row_gap_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_tablet'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_tablet'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-tablet'] )
				: '10px';

			$row_gap_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_mobile'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['slider_value_mobile'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopg-row-gap']['unit-mobile'] )
				: '10px';

			// Shopbody Margin - Desktop
			$shopbody_margin_top_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				: '0px';
			$shopbody_margin_right_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				: '0px';
			$shopbody_margin_bottom_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				: '0px';
			$shopbody_margin_left_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['desktop-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-desktop'] )
				: '0px';

			// Shopbody Margin - Tablet
			$shopbody_margin_top_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				: '0px';
			$shopbody_margin_right_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				: '0px';
			$shopbody_margin_bottom_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				: '0px';
			$shopbody_margin_left_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['tablet-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-tablet'] )
				: '0px';

			// Shopbody Margin - Mobile
			$shopbody_margin_top_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				: '0px';
			$shopbody_margin_right_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				: '0px';
			$shopbody_margin_bottom_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				: '0px';
			$shopbody_margin_left_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['mobile-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-margin']['unit-mobile'] )
				: '0px';

			// Shopbody Padding - Desktop
			$shopbody_padding_top_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				: '0px';
			$shopbody_padding_right_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				: '0px';
			$shopbody_padding_bottom_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				: '0px';
			$shopbody_padding_left_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['desktop-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-desktop'] )
				: '0px';

			// Shopbody Padding - Tablet
			$shopbody_padding_top_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				: '0px';
			$shopbody_padding_right_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				: '0px';
			$shopbody_padding_bottom_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				: '0px';
			$shopbody_padding_left_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['tablet-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-tablet'] )
				: '0px';

			// Shopbody Padding - Mobile
			$shopbody_padding_top_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-top'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-top'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				: '0px';
			$shopbody_padding_right_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-right'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-right'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				: '0px';
			$shopbody_padding_bottom_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-bottom'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-bottom'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				: '0px';
			$shopbody_padding_left_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-left'], $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['mobile-left'] ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-padding']['unit-mobile'] )
				: '0px';

			// Body Typography
			$body_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-family'] )
				: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';

			$body_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-weight'] )
				: '';

			$body_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['font-styles'] )
				: 'normal';

			$body_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['text-transform'] )
				: '';

			$body_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['text-decoration'] )
				: 'none';

			$body_font_size = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-typography']['unit'] ) . 'px'
				: '';

			// Normal and Hover Background Colors
			$normal_background = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-normal-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-normal-background'] )
				: '';

			$hover_background = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-hover-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-hover-background'] )
				: '';

			$border_style = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['style'] )
				: '';

			$border_color = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['color'] )
				: '';

			$border_width = isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-top'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-right'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-bottom'] ) &&
				isset( $layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-left'] )
				? "{$layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-top']}px {$layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-right']}px {$layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-bottom']}px {$layout_array_values['shopg_style_settings_accordion']['shopbody-border']['border-left']}px"
				: '';

			// Generate the dynamic CSS

			$product_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-top'] )
				: '';

			$product_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-right'] )
				: '';

			$product_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-bottom'] )
				: '';

			$product_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['desktop-left'] )
				: '';

			$product_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-desktop'] )
				: 'px';

			$product_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-top'] )
				: '';

			$product_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-right'] )
				: '';

			$product_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-bottom'] )
				: '';

			$product_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['tablet-left'] )
				: '';

			$product_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-tablet'] )
				: 'px';

			$product_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-top'] )
				: '';

			$product_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-right'] )
				: '';

			$product_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-bottom'] )
				: '';

			$product_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['mobile-left'] )
				: '';

			$product_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-margin']['unit-mobile'] )
				: 'px';

			// Product Padding
			$product_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-top'] )
				: '';

			$product_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-right'] )
				: '';

			$product_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-bottom'] )
				: '';

			$product_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['desktop-left'] )
				: '';

			$product_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-desktop'] )
				: 'px';

			$product_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-top'] )
				: '';

			$product_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-right'] )
				: '';

			$product_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-bottom'] )
				: '';

			$product_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['tablet-left'] )
				: '';

			$product_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-tablet'] )
				: 'px';

			$product_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-top'] )
				: '';

			$product_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-right'] )
				: '';

			$product_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-bottom'] )
				: '';

			$product_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['mobile-left'] )
				: '';

			$product_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-padding']['unit-mobile'] )
				: 'px';

			$product_background_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-normal-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-normal-background'] )
				: '';

			$product_background_hover_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-hover-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-hover-background'] )
				: '';

			// Product Border
			$product_border_style = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['style'] )
				: '';

			$product_border_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-top'] )
				: '';

			$product_border_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-right'] )
				: '';

			$product_border_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-bottom'] )
				: '';

			$product_border_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['border-left'] )
				: '';

			$product_border_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-border']['color'] )
				: '';

			// Product Border Radius
			$product_radius_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-top'] )
				: '';

			$product_radius_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-right'] )
				: '';

			$product_radius_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-bottom'] )
				: '';

			$product_radius_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['desktop-left'] )
				: '';

			$product_radius_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-desktop'] )
				: 'px';

			$product_radius_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-top'] )
				: '';

			$product_radius_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-right'] )
				: '';

			$product_radius_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-bottom'] )
				: '';

			$product_radius_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['tablet-left'] )
				: '';

			$product_radius_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-tablet'] )
				: 'px';

			$product_radius_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-top'] )
				: '';

			$product_radius_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-right'] )
				: '';

			$product_radius_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-bottom'] )
				: '';

			$product_radius_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['mobile-left'] )
				: '';

			$product_radius_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-radius']['unit-mobile'] )
				: 'px';

			// Product Caption Background
			$product_caption_background = isset( $layout_array_values['shopg_style_settings_accordion']['product-caption-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-caption-background'] )
				: '#fff';

			$product_caption_hover_background = isset( $layout_array_values['shopg_style_settings_accordion']['product-caption-hover-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-caption-hover-background'] )
				: '#fff';

			if ( $layout_template === 'template11' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
			} elseif ( $layout_template === 'template12' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
			} elseif ( $layout_template === 'template13' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
			} elseif ( $layout_template === 'template14' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
			} elseif ( $layout_template === 'template15' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
				// Template 15 uses the ::before pseudo-element for its background effect
			} elseif ( $layout_template === 'template16' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
				// Template 16 might use a different background approach
			} elseif ( $layout_template === 'template17' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
				// Template 17 uses multiple pseudo-elements for the blinds effect (top to bottom)
			} elseif ( $layout_template === 'template18' ) {
				$product_caption_background = 'transparent';
				$product_caption_hover_background = 'transparent';
				// Template 18 uses multiple pseudo-elements for the blinds effect (right to left)
			}

			// Product Title
			$product_title_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-family'] )
				: '';

			$product_title_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-weight'] )
				: '';

			$product_title_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-styles'] )
				: '';

			$product_title_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['text-transform'] )
				: '';

			$product_title_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['text-decoration'] )
				: '';

			$product_title_font_size = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-size'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-size'] )
				: '';

			$product_title_font_size_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-size-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['font-size-unit'] )
				: 'px';

			$product_title_line_height = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['line-height'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['line-height'] )
				: '';

			$product_title_line_height_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['line-height-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['line-height-unit'] )
				: 'px';

			$product_title_letter_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['letter-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['letter-spacing'] )
				: '';

			$product_title_letter_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['letter-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['letter-spacing-unit'] )
				: 'px';

			$product_title_word_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['word-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['word-spacing'] )
				: '';

			$product_title_word_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['word-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['word-spacing-unit'] )
				: 'px';

			$product_title_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['color'] )
				: '';

			$product_title_hover_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-title']['hover_color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-title']['hover_color'] )
				: '';

			// Product Category
			$product_category_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-family'] )
				: '';

			$product_category_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-weight'] )
				: '';

			$product_category_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-styles'] )
				: '';

			$product_category_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['text-transform'] )
				: '';

			$product_category_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['text-decoration'] )
				: '';

			$product_category_font_size = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-size'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-size'] )
				: '';

			$product_category_font_size_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-size-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['font-size-unit'] )
				: 'px';

			$product_category_line_height = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['line-height'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['line-height'] )
				: '24';

			$product_category_line_height_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['line-height-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['line-height-unit'] )
				: 'px';

			$product_category_letter_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['letter-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['letter-spacing'] )
				: '';

			$product_category_letter_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['letter-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['letter-spacing-unit'] )
				: 'px';

			$product_category_word_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['word-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['word-spacing'] )
				: '';

			$product_category_word_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['word-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['word-spacing-unit'] )
				: 'px';

			$product_category_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['color'] )
				: '';

			$product_category_hover_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-category']['hover_color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-category']['hover_color'] )
				: '';

			// Product Price
			$product_price_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-family'] )
				: '';

			$product_price_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-weight'] )
				: '';

			$product_price_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-styles'] )
				: '';

			$product_price_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['text-transform'] )
				: '';

			$product_price_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['text-decoration'] )
				: '';

			$product_price_font_size = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-size'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-size'] )
				: '';

			$product_price_font_size_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-size-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['font-size-unit'] )
				: 'px';

			$product_price_line_height = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['line-height'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['line-height'] )
				: '';

			$product_price_line_height_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['line-height-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['line-height-unit'] )
				: 'px';

			$product_price_letter_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['letter-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['letter-spacing'] )
				: '';

			$product_price_letter_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['letter-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['letter-spacing-unit'] )
				: 'px';

			$product_price_word_spacing = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['word-spacing'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['word-spacing'] )
				: '';

			$product_price_word_spacing_unit = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['word-spacing-unit'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['word-spacing-unit'] )
				: 'px';

			$product_price_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['color'] )
				: '';

			$product_price_hover_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-price']['hover_color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-price']['hover_color'] )
				: '';

			$product_price_old_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-old-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-old-color'] )
				: '';
			$product_price_old_hover_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-old-hover-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-old-hover-color'] )
				: '';

			// Product Review
			$product_review_color = isset( $layout_array_values['shopg_style_settings_accordion']['product-review-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['product-review-color'] )
				: '#000';

			// Image Padding
			$image_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-top'] )
				: '';

			$image_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-right'] )
				: '';

			$image_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-bottom'] )
				: '';

			$image_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['desktop-left'] )
				: '';

			$image_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-desktop'] )
				: 'px';

			$image_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-top'] )
				: '';

			$image_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-right'] )
				: '';

			$image_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-bottom'] )
				: '';

			$image_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['tablet-left'] )
				: '';

			$image_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-tablet'] )
				: 'px';

			$image_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-top'] )
				: '';

			$image_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-right'] )
				: '';

			$image_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-bottom'] )
				: '';

			$image_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['mobile-left'] )
				: '';

			$image_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-padding']['unit-mobile'] )
				: 'px';

			// Image Backgrounds
			$image_normal_background = isset( $layout_array_values['shopg_style_settings_accordion']['image-normal-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-normal-background'] )
				: '#fff';

			$image_hover_background = isset( $layout_array_values['shopg_style_settings_accordion']['image-hover-background'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['image-hover-background'] )
				: '#fff';

			// New Badge Margin
			$new_badge_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-top'] )
				: '';

			$new_badge_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-right'] )
				: '';

			$new_badge_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-bottom'] )
				: '';

			$new_badge_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['desktop-left'] )
				: '';

			$new_badge_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-desktop'] )
				: 'px';

			$new_badge_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-top'] )
				: '';

			$new_badge_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-right'] )
				: '';

			$new_badge_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-bottom'] )
				: '';

			$new_badge_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['tablet-left'] )
				: '';

			$new_badge_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-tablet'] )
				: 'px';

			$new_badge_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-top'] )
				: '';

			$new_badge_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-right'] )
				: '';

			$new_badge_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-bottom'] )
				: '';

			$new_badge_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['mobile-left'] )
				: '';

			$new_badge_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-margin']['unit-mobile'] )
				: 'px';

			// New Badge Padding
			$new_badge_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-top'] )
				: '';

			$new_badge_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-right'] )
				: '';

			$new_badge_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-bottom'] )
				: '';

			$new_badge_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['desktop-left'] )
				: '';

			$new_badge_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-desktop'] )
				: 'px';

			$new_badge_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-top'] )
				: '';

			$new_badge_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-right'] )
				: '';

			$new_badge_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-bottom'] )
				: '';

			$new_badge_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['tablet-left'] )
				: '';

			$new_badge_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-tablet'] )
				: 'px';

			$new_badge_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-top'] )
				: '';

			$new_badge_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-right'] )
				: '';

			$new_badge_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-bottom'] )
				: '';

			$new_badge_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['mobile-left'] )
				: '';

			$new_badge_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-padding']['unit-mobile'] )
				: 'px';

			// New Product Width
			$new_product_width_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_desktop'] )
				: '';

			$new_product_width_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-desktop'] )
				: 'px';

			$new_product_width_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_tablet'] )
				: '';

			$new_product_width_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-tablet'] )
				: 'px';

			$new_product_width_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['slider_value_mobile'] )
				: '';

			$new_product_width_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-width']['unit-mobile'] )
				: 'px';

			// New Product Height
			$new_product_height_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_desktop'] )
				: 'auto';

			$new_product_height_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-desktop'] )
				: 'px';

			$new_product_height_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_tablet'] )
				: 'auto';

			$new_product_height_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-tablet'] )
				: 'px';

			$new_product_height_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['slider_value_mobile'] )
				: 'auto';

			$new_product_height_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-height']['unit-mobile'] )
				: 'px';

			// New Product Colors
			$new_product_color = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-color'] )
				: '#fff';

			$new_product_bgcolor = isset( $layout_array_values['shopg_style_settings_accordion']['new-product-bgcolor'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-product-bgcolor'] )
				: '#cd0000';

			// New Badge Border
			$new_badge_border_style = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['style'] )
				: '';

			$new_badge_border_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-top'] )
				: '';

			$new_badge_border_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-right'] )
				: '';

			$new_badge_border_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-bottom'] )
				: '';

			$new_badge_border_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['border-left'] )
				: '';

			$new_badge_border_color = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border']['color'] )
				: '';

			// New Badge Border Radius
			$new_badge_radius_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-top'] )
				: '';

			$new_badge_radius_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-right'] )
				: '';

			$new_badge_radius_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-bottom'] )
				: '';

			$new_badge_radius_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['desktop-left'] )
				: '';

			$new_badge_radius_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-desktop'] )
				: 'px';

			$new_badge_radius_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-top'] )
				: '';

			$new_badge_radius_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-right'] )
				: '';

			$new_badge_radius_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-bottom'] )
				: '';

			$new_badge_radius_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['tablet-left'] )
				: '';

			$new_badge_radius_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-tablet'] )
				: 'px';

			$new_badge_radius_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-top'] )
				: '';

			$new_badge_radius_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-right'] )
				: '';

			$new_badge_radius_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-bottom'] )
				: '';

			$new_badge_radius_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['mobile-left'] )
				: '';

			$new_badge_radius_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-border-radius']['unit-mobile'] )
				: 'px';

			// New Badge Typography
			$new_badge_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-family'] )
				: '';

			$new_badge_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-weight'] )
				: '';

			$new_badge_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['font-styles'] )
				: '';

			$new_badge_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['text-transform'] )
				: 'none';

			$new_badge_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['new-badge-typography']['text-decoration'] )
				: 'none';

			// Out-of-Stock Badge Margin
			$outofstock_badge_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-top'] )
				: '';

			$outofstock_badge_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-right'] )
				: '';

			$outofstock_badge_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-bottom'] )
				: '';

			$outofstock_badge_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['desktop-left'] )
				: '';

			$outofstock_badge_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-desktop'] )
				: 'px';

			$outofstock_badge_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-top'] )
				: '';

			$outofstock_badge_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-right'] )
				: '';

			$outofstock_badge_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-bottom'] )
				: '';

			$outofstock_badge_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['tablet-left'] )
				: '';

			$outofstock_badge_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-tablet'] )
				: 'px';

			$outofstock_badge_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-top'] )
				: '';

			$outofstock_badge_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-right'] )
				: '';

			$outofstock_badge_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-bottom'] )
				: '';

			$outofstock_badge_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['mobile-left'] )
				: '';

			$outofstock_badge_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-margin']['unit-mobile'] )
				: 'px';

			// Out-of-Stock Badge Padding
			$outofstock_badge_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-top'] )
				: '';

			$outofstock_badge_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-right'] )
				: '';

			$outofstock_badge_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-bottom'] )
				: '';

			$outofstock_badge_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['desktop-left'] )
				: '';

			$outofstock_badge_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-desktop'] )
				: 'px';

			$outofstock_badge_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-top'] )
				: '';

			$outofstock_badge_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-right'] )
				: '';

			$outofstock_badge_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-bottom'] )
				: '';

			$outofstock_badge_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['tablet-left'] )
				: '';

			$outofstock_badge_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-tablet'] )
				: 'px';

			$outofstock_badge_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-top'] )
				: '';

			$outofstock_badge_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-right'] )
				: '';

			$outofstock_badge_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-bottom'] )
				: '';

			$outofstock_badge_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['mobile-left'] )
				: '';

			$outofstock_badge_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-padding']['unit-mobile'] )
				: 'px';

			// Out-of-Stock Product Width
			$outofstock_product_width_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_desktop'] )
				: '';

			$outofstock_product_width_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-desktop'] )
				: '%';

			$outofstock_product_width_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_tablet'] )
				: '';

			$outofstock_product_width_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-tablet'] )
				: '%';

			$outofstock_product_width_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['slider_value_mobile'] )
				: '';

			$outofstock_product_width_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-width']['unit-mobile'] )
				: '%';

			// Out-of-Stock Product Height
			$outofstock_product_height_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_desktop'] )
				: 'auto';

			$outofstock_product_height_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-desktop'] )
				: 'px';

			$outofstock_product_height_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_tablet'] )
				: 'auto';

			$outofstock_product_height_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-tablet'] )
				: 'px';

			$outofstock_product_height_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['slider_value_mobile'] )
				: 'auto';

			$outofstock_product_height_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-height']['unit-mobile'] )
				: 'px';

			// Out-of-Stock Product Colors
			$outofstock_product_color = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-color'] )
				: '#fff';

			$outofstock_product_bgcolor = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-bgcolor'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-product-bgcolor'] )
				: '#019714';

			// Out-of-Stock Badge Border
			$outofstock_badge_border_style = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border']['style'] )
				: '';

			$outofstock_badge_border_color = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border']['color'] )
				: '';

			// Out-of-Stock Badge Border Radius
			$outofstock_badge_radius_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-top'] )
				: '';

			$outofstock_badge_radius_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-right'] )
				: '';

			$outofstock_badge_radius_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-bottom'] )
				: '';

			$outofstock_badge_radius_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['desktop-left'] )
				: '';

			$outofstock_badge_radius_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-desktop'] )
				: 'px';

			$outofstock_badge_radius_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-top'] )
				: '';

			$outofstock_badge_radius_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-right'] )
				: '';

			$outofstock_badge_radius_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-bottom'] )
				: '';

			$outofstock_badge_radius_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['tablet-left'] )
				: '';

			$outofstock_badge_radius_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-tablet'] )
				: 'px';

			$outofstock_badge_radius_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-top'] )
				: '';

			$outofstock_badge_radius_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-right'] )
				: '';

			$outofstock_badge_radius_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-bottom'] )
				: '';

			$outofstock_badge_radius_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['mobile-left'] )
				: '';

			$outofstock_badge_radius_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-border-radius']['unit-mobile'] )
				: 'px';

			// Out-of-Stock Badge Typography
			$outofstock_badge_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-family'] )
				: '';

			$outofstock_badge_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-weight'] )
				: '';

			$outofstock_badge_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['font-styles'] )
				: '';

			$outofstock_badge_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['text-transform'] )
				: '';

			$outofstock_badge_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['outof-stock-badge-typography']['text-decoration'] )
				: '';

			// Featured Badge Margin
			$featured_badge_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-top'] )
				: '';

			$featured_badge_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-right'] )
				: '';

			$featured_badge_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-bottom'] )
				: '';

			$featured_badge_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['desktop-left'] )
				: '';

			$featured_badge_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-desktop'] )
				: 'px';

			$featured_badge_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-top'] )
				: '';

			$featured_badge_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-right'] )
				: '';

			$featured_badge_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-bottom'] )
				: '';

			$featured_badge_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['tablet-left'] )
				: '';

			$featured_badge_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-tablet'] )
				: 'px';

			$featured_badge_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-top'] )
				: '';

			$featured_badge_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-right'] )
				: '';

			$featured_badge_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-bottom'] )
				: '';

			$featured_badge_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['mobile-left'] )
				: '';

			$featured_badge_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-margin']['unit-mobile'] )
				: 'px';

			// Featured Badge Padding
			$featured_badge_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-top'] )
				: '';

			$featured_badge_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-right'] )
				: '';

			$featured_badge_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-bottom'] )
				: '';

			$featured_badge_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['desktop-left'] )
				: '';

			$featured_badge_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-desktop'] )
				: 'px';

			$featured_badge_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-top'] )
				: '';

			$featured_badge_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-right'] )
				: '';

			$featured_badge_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-bottom'] )
				: '';

			$featured_badge_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['tablet-left'] )
				: '';

			$featured_badge_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-tablet'] )
				: 'px';

			$featured_badge_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-top'] )
				: '';

			$featured_badge_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-right'] )
				: '';

			$featured_badge_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-bottom'] )
				: '';

			$featured_badge_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['mobile-left'] )
				: '';

			$featured_badge_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-padding']['unit-mobile'] )
				: 'px';

			// Featured Product Width
			$featured_product_width_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_desktop'] )
				: '';

			$featured_product_width_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-desktop'] )
				: '%';

			$featured_product_width_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_tablet'] )
				: '';

			$featured_product_width_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-tablet'] )
				: '%';

			$featured_product_width_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['slider_value_mobile'] )
				: '';

			$featured_product_width_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-width']['unit-mobile'] )
				: '%';

			// Featured Product Height
			$featured_product_height_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_desktop'] )
				: 'auto';

			$featured_product_height_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-desktop'] )
				: 'px';

			$featured_product_height_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_tablet'] )
				: 'auto';

			$featured_product_height_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-tablet'] )
				: 'px';

			$featured_product_height_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['slider_value_mobile'] )
				: 'auto';

			$featured_product_height_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-height']['unit-mobile'] )
				: 'px';

			// Featured Product Colors
			$featured_product_color = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-color'] )
				: '#fff';

			$featured_product_bgcolor = isset( $layout_array_values['shopg_style_settings_accordion']['featured-product-bgcolor'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-product-bgcolor'] )
				: '#019714';

			// Featured Badge Border
			$featured_badge_border_style = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border']['style'] )
				: '';

			$featured_badge_border_color = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border']['color'] )
				: '';

			// Featured Badge Border Radius
			$featured_badge_radius_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-top'] )
				: '';

			$featured_badge_radius_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-right'] )
				: '';

			$featured_badge_radius_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-bottom'] )
				: '';

			$featured_badge_radius_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['desktop-left'] )
				: '';

			$featured_badge_radius_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-desktop'] )
				: 'px';

			$featured_badge_radius_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-top'] )
				: '';

			$featured_badge_radius_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-right'] )
				: '';

			$featured_badge_radius_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-bottom'] )
				: '';

			$featured_badge_radius_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['tablet-left'] )
				: '';

			$featured_badge_radius_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-tablet'] )
				: 'px';

			$featured_badge_radius_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-top'] )
				: '';

			$featured_badge_radius_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-right'] )
				: '';

			$featured_badge_radius_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-bottom'] )
				: '';

			$featured_badge_radius_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['mobile-left'] )
				: '';

			$featured_badge_radius_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-border-radius']['unit-mobile'] )
				: 'px';

			// Featured Badge Typography
			$featured_badge_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-family'] )
				: '';

			$featured_badge_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-weight'] )
				: '';

			$featured_badge_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['font-styles'] )
				: '';

			$featured_badge_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['text-transform'] )
				: '';

			$featured_badge_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['featured-badge-typography']['text-decoration'] )
				: '';

			// Discount Badge Margin
			$discount_badge_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-top'] )
				: '';

			$discount_badge_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-right'] )
				: '';

			$discount_badge_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-bottom'] )
				: '';

			$discount_badge_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['desktop-left'] )
				: '';

			$discount_badge_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-desktop'] )
				: 'px';

			$discount_badge_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-top'] )
				: '';

			$discount_badge_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-right'] )
				: '';

			$discount_badge_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-bottom'] )
				: '';

			$discount_badge_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['tablet-left'] )
				: '';

			$discount_badge_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-tablet'] )
				: 'px';

			$discount_badge_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-top'] )
				: '';

			$discount_badge_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-right'] )
				: '';

			$discount_badge_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-bottom'] )
				: '';

			$discount_badge_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['mobile-left'] )
				: '';

			$discount_badge_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-margin']['unit-mobile'] )
				: 'px';

			// Discount Badge Padding
			$discount_badge_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-top'] )
				: '';

			$discount_badge_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-right'] )
				: '';

			$discount_badge_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-bottom'] )
				: '';

			$discount_badge_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['desktop-left'] )
				: '';

			$discount_badge_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-desktop'] )
				: 'px';

			$discount_badge_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-top'] )
				: '';

			$discount_badge_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-right'] )
				: '';

			$discount_badge_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-bottom'] )
				: '';

			$discount_badge_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['tablet-left'] )
				: '';

			$discount_badge_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-tablet'] )
				: 'px';

			$discount_badge_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-top'] )
				: '';

			$discount_badge_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-right'] )
				: '';

			$discount_badge_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-bottom'] )
				: '';

			$discount_badge_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['mobile-left'] )
				: '';

			$discount_badge_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-padding']['unit-mobile'] )
				: 'px';

			// Discount Badge Width
			$discount_badge_width_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_desktop'] )
				: '';

			$discount_badge_width_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-desktop'] )
				: '%';

			$discount_badge_width_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_tablet'] )
				: '';

			$discount_badge_width_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-tablet'] )
				: '%';

			$discount_badge_width_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['slider_value_mobile'] )
				: '';

			$discount_badge_width_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-width']['unit-mobile'] )
				: '%';

			// Discount Badge Height
			$discount_badge_height_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_desktop'] )
				: 'auto';

			$discount_badge_height_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-desktop'] )
				: 'px';

			$discount_badge_height_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_tablet'] )
				: 'auto';

			$discount_badge_height_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-tablet'] )
				: 'px';

			$discount_badge_height_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['slider_value_mobile'] )
				: 'auto';

			$discount_badge_height_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-height']['unit-mobile'] )
				: 'px';

			// Discount Badge Colors
			$discount_badge_color = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-color'] )
				: '#fff';

			$discount_badge_bgcolor = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-bgcolor'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-bgcolor'] )
				: '#019714';

			// Discount Badge Border
			$discount_badge_border_style = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['style'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['style'] )
				: '';

			$discount_badge_border_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-top'] )
				: '';

			$discount_badge_border_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-right'] )
				: '';

			$discount_badge_border_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-bottom'] )
				: '';

			$discount_badge_border_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['border-left'] )
				: '';

			$discount_badge_border_color = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border']['color'] )
				: '';

			// Discount Badge Border Radius
			$discount_badge_radius_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-top'] )
				: '';

			$discount_badge_radius_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-right'] )
				: '';

			$discount_badge_radius_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-bottom'] )
				: '';

			$discount_badge_radius_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['desktop-left'] )
				: '';

			$discount_badge_radius_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-desktop'] )
				: 'px';

			$discount_badge_radius_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-top'] )
				: '';

			$discount_badge_radius_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-right'] )
				: '';

			$discount_badge_radius_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-bottom'] )
				: '';

			$discount_badge_radius_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['tablet-left'] )
				: '';

			$discount_badge_radius_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-tablet'] )
				: 'px';

			$discount_badge_radius_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-top'] )
				: '';

			$discount_badge_radius_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-right'] )
				: '';

			$discount_badge_radius_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-bottom'] )
				: '';

			$discount_badge_radius_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['mobile-left'] )
				: '';

			$discount_badge_radius_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-border-radius']['unit-mobile'] )
				: 'px';

			// Discount Badge Typography
			$discount_badge_font_family = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-family'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-family'] )
				: '';

			$discount_badge_font_weight = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-weight'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-weight'] )
				: '';

			$discount_badge_font_style = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-styles'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['font-styles'] )
				: '';

			$discount_badge_text_transform = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['text-transform'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['text-transform'] )
				: '';

			$discount_badge_text_decoration = isset( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['text-decoration'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['discount-badge-typography']['text-decoration'] )
				: '';

			// Add to Cart Colors
			$add_to_cart_font_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-font-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-font-color'] )
				: '#000';

			$add_to_cart_icon_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-icon-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-icon-color'] )
				: '#000';
			$add_to_cart_bg_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-bg-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-bg-color'] )
				: '#fff';

			if ( $layout_template === 'template5' && empty( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-bg-color'] ) ) {
				$add_to_cart_bg_color = '#ffffff00';
			}

			// Add to Cart Width
			$add_to_cart_width_desktop = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_desktop'] )
				: '142';

			$add_to_cart_width_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-desktop'] )
				: 'px';

			$add_to_cart_width_tablet = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_tablet'] )
				: '';

			$add_to_cart_width_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-tablet'] )
				: 'px';

			$add_to_cart_width_mobile = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['slider_value_mobile'] )
				: '';

			$add_to_cart_width_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-width']['unit-mobile'] )
				: 'px';

			// Add to Cart Margin
			$add_to_cart_margin_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-top'] )
				: '';

			$add_to_cart_margin_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-right'] )
				: '';

			$add_to_cart_margin_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-bottom'] )
				: '';

			$add_to_cart_margin_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['desktop-left'] )
				: '';

			$add_to_cart_margin_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-desktop'] )
				: 'px';

			$add_to_cart_margin_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-top'] )
				: '';

			$add_to_cart_margin_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-right'] )
				: '';

			$add_to_cart_margin_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-bottom'] )
				: '';

			$add_to_cart_margin_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['tablet-left'] )
				: '';

			$add_to_cart_margin_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-tablet'] )
				: 'px';

			$add_to_cart_margin_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-top'] )
				: '';

			$add_to_cart_margin_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-right'] )
				: '';

			$add_to_cart_margin_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-bottom'] )
				: '';

			$add_to_cart_margin_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['mobile-left'] )
				: '';

			$add_to_cart_margin_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-margin']['unit-mobile'] )
				: 'px';

			// Add to Cart Padding
			$add_to_cart_padding_desktop_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-top'] )
				: '';

			$add_to_cart_padding_desktop_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-right'] )
				: '';

			$add_to_cart_padding_desktop_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-bottom'] )
				: '';

			$add_to_cart_padding_desktop_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['desktop-left'] )
				: '';

			$add_to_cart_padding_desktop_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-desktop'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-desktop'] )
				: 'px';

			$add_to_cart_padding_tablet_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-top'] )
				: '';

			$add_to_cart_padding_tablet_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-right'] )
				: '';

			$add_to_cart_padding_tablet_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-bottom'] )
				: '';

			$add_to_cart_padding_tablet_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['tablet-left'] )
				: '';

			$add_to_cart_padding_tablet_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-tablet'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-tablet'] )
				: 'px';

			$add_to_cart_padding_mobile_top = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-top'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-top'] )
				: '';

			$add_to_cart_padding_mobile_right = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-right'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-right'] )
				: '';

			$add_to_cart_padding_mobile_bottom = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-bottom'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-bottom'] )
				: '';

			$add_to_cart_padding_mobile_left = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-left'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['mobile-left'] )
				: '';

			$add_to_cart_padding_mobile_unit = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-mobile'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-padding']['unit-mobile'] )
				: 'px';

			// Add to Cart Hover Colors
			$add_to_cart_hover_font_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-font-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-font-color'] )
				: '#000';

			$add_to_cart_hover_icon_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-icon-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-icon-color'] )
				: '#000';

			$add_to_cart_hover_bg_color = isset( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-bg-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['add-to-cart-hover-bg-color'] )
				: '#fff';

			// Wishlist Options
			$wishlist_icon_color = isset( $layout_array_values['shopg_style_settings_accordion']['wishlist-icon-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-icon-color'] )
				: '#000';

			$wishlist_bg_color = isset( $layout_array_values['shopg_style_settings_accordion']['wishlist-bg-color'] )
				? esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-bg-color'] )
				: '#fff';

			// Wishlist Width
			// Wishlist Width
			$wishlist_width_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['slider_value_desktop'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['unit-desktop'] ?? 'px' );
			$wishlist_width_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['slider_value_tablet'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['unit-tablet'] ?? 'px' );
			$wishlist_width_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['slider_value_mobile'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-width']['unit-mobile'] ?? 'px' );

			// Wishlist Margin
			$wishlist_margin_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-desktop'] ?? 'px' );
			$wishlist_margin_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-desktop'] ?? 'px' );
			$wishlist_margin_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-desktop'] ?? 'px' );
			$wishlist_margin_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-desktop'] ?? 'px' );

			$wishlist_margin_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-tablet'] ?? 'px' );
			$wishlist_margin_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-tablet'] ?? 'px' );
			$wishlist_margin_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-tablet'] ?? 'px' );
			$wishlist_margin_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-tablet'] ?? 'px' );

			$wishlist_margin_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-mobile'] ?? 'px' );
			$wishlist_margin_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-mobile'] ?? 'px' );
			$wishlist_margin_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-mobile'] ?? 'px' );
			$wishlist_margin_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-margin']['unit-mobile'] ?? 'px' );

			// Wishlist Padding
			$wishlist_padding_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-desktop'] ?? 'px' );
			$wishlist_padding_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-desktop'] ?? 'px' );
			$wishlist_padding_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-desktop'] ?? 'px' );
			$wishlist_padding_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-desktop'] ?? 'px' );

			$wishlist_padding_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-tablet'] ?? 'px' );
			$wishlist_padding_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-tablet'] ?? 'px' );
			$wishlist_padding_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-tablet'] ?? 'px' );
			$wishlist_padding_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-tablet'] ?? 'px' );

			$wishlist_padding_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-mobile'] ?? 'px' );
			$wishlist_padding_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-mobile'] ?? 'px' );
			$wishlist_padding_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-mobile'] ?? 'px' );
			$wishlist_padding_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-padding']['unit-mobile'] ?? 'px' );

			// Wishlist Hover Colors
			$wishlist_hover_icon_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-hover-icon-color'] ?? '#000' );
			$wishlist_hover_bg_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['wishlist-hover-bg-color'] ?? '#fff' );

			if ( $layout_template === 'template11' ) {
				$wishlist_hover_icon_color = '#000';
			}

			// Compare Icon and Background Colors
			$compare_icon_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-icon-color'] ?? '#000' );
			$compare_bg_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-bg-color'] ?? '#fff' );

			// Compare Width
			$compare_width_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['slider_value_desktop'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['unit-desktop'] ?? 'px' );
			$compare_width_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['slider_value_tablet'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['unit-tablet'] ?? 'px' );
			$compare_width_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['slider_value_mobile'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-width']['unit-mobile'] ?? 'px' );

			// Compare Margin
			$compare_margin_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-desktop'] ?? 'px' );
			$compare_margin_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-desktop'] ?? 'px' );
			$compare_margin_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-desktop'] ?? 'px' );
			$compare_margin_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-desktop'] ?? 'px' );

			$compare_margin_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-tablet'] ?? 'px' );
			$compare_margin_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-tablet'] ?? 'px' );
			$compare_margin_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-tablet'] ?? 'px' );
			$compare_margin_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-tablet'] ?? 'px' );

			$compare_margin_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-mobile'] ?? 'px' );
			$compare_margin_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-mobile'] ?? 'px' );
			$compare_margin_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-mobile'] ?? 'px' );
			$compare_margin_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-margin']['unit-mobile'] ?? 'px' );

			// Compare Padding
			$compare_padding_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-desktop'] ?? 'px' );
			$compare_padding_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-desktop'] ?? 'px' );
			$compare_padding_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-desktop'] ?? 'px' );
			$compare_padding_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-desktop'] ?? 'px' );

			$compare_padding_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-tablet'] ?? 'px' );
			$compare_padding_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-tablet'] ?? 'px' );
			$compare_padding_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-tablet'] ?? 'px' );
			$compare_padding_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-tablet'] ?? 'px' );

			$compare_padding_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-mobile'] ?? 'px' );
			$compare_padding_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-mobile'] ?? 'px' );
			$compare_padding_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-mobile'] ?? 'px' );
			$compare_padding_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-padding']['unit-mobile'] ?? 'px' );

			// Compare Hover Colors
			$compare_hover_icon_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-hover-icon-color'] ?? '#000' );
			$compare_hover_bg_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['compare-hover-bg-color'] ?? '#fff' );

			// Quick View Icon and Background Colors
			$quick_views_icon_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-icon-color'] ?? '#000' );
			$quick_views_bg_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-bg-color'] ?? '#fff' );

			// Quick View Width
			$quick_views_width_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['slider_value_desktop'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['unit-desktop'] ?? 'px' );
			$quick_views_width_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['slider_value_tablet'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['unit-tablet'] ?? 'px' );
			$quick_views_width_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['slider_value_mobile'] ?? '100px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-width']['unit-mobile'] ?? 'px' );

			// Quick View Margin
			$quick_views_margin_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-desktop'] ?? 'px' );
			$quick_views_margin_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-desktop'] ?? 'px' );
			$quick_views_margin_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-desktop'] ?? 'px' );
			$quick_views_margin_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-desktop'] ?? 'px' );

			$quick_views_margin_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-tablet'] ?? 'px' );
			$quick_views_margin_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-tablet'] ?? 'px' );
			$quick_views_margin_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-tablet'] ?? 'px' );
			$quick_views_margin_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-tablet'] ?? 'px' );

			$quick_views_margin_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-mobile'] ?? 'px' );
			$quick_views_margin_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-mobile'] ?? 'px' );
			$quick_views_margin_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-mobile'] ?? 'px' );
			$quick_views_margin_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-margin']['unit-mobile'] ?? 'px' );

			// Quick View Padding
			$quick_views_padding_top_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['desktop-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-desktop'] ?? 'px' );
			$quick_views_padding_right_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['desktop-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-desktop'] ?? 'px' );
			$quick_views_padding_bottom_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['desktop-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-desktop'] ?? 'px' );
			$quick_views_padding_left_desktop = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['desktop-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-desktop'] ?? 'px' );

			$quick_views_padding_top_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['tablet-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-tablet'] ?? 'px' );
			$quick_views_padding_right_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['tablet-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-tablet'] ?? 'px' );
			$quick_views_padding_bottom_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['tablet-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-tablet'] ?? 'px' );
			$quick_views_padding_left_tablet = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['tablet-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-tablet'] ?? 'px' );

			$quick_views_padding_top_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['mobile-top'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-mobile'] ?? 'px' );
			$quick_views_padding_right_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['mobile-right'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-mobile'] ?? 'px' );
			$quick_views_padding_bottom_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['mobile-bottom'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-mobile'] ?? 'px' );
			$quick_views_padding_left_mobile = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['mobile-left'] ?? '0px' ) . esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-padding']['unit-mobile'] ?? 'px' );

			// Quick View Hover Colors
			$quick_views_hover_icon_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-hover-icon-color'] ?? '#000' );
			$quick_views_hover_bg_color = esc_html( $layout_array_values['shopg_style_settings_accordion']['quick-view-hover-bg-color'] ?? '#fff' );

			$shop_desk_col = intval( preg_replace( '/[^0-9]/', '', $column_grid_desktop ) );
			$shop_tablet_col = intval( preg_replace( '/[^0-9]/', '', $column_grid_tablet ) );
			$shop_mobile_col = intval( preg_replace( '/[^0-9]/', '', $column_grid_mobile ) );

			$dynamic_css = "
			/* Column Grid */
    #shopg_shop_layout_contents.width-100 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
        grid-template-columns: repeat($shop_desk_col, 1fr);
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts{
            grid-template-columns: repeat($shop_tablet_col, 1fr);
        }
    }

    @media (max-width: 768px) {
        #shopg_shop_layout_contents.width-30 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
            grid-template-columns: repeat($shop_mobile_col, 1fr);
        }
    }

    /* Column Gap */
    #shopg_shop_layout_contents.width-100 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
        grid-column-gap: $column_gap_desktop;
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
            grid-column-gap: $column_gap_tablet;
        }
    }

    @media (max-width: 768px) {
       #shopg_shop_layout_contents.width-30 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
            grid-column-gap: $column_gap_mobile;
        }
    }

    /* Row Gap */
    #shopg_shop_layout_contents.width-100 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
        grid-row-gap: $row_gap_desktop;
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
            grid-row-gap: $row_gap_tablet;
        }
    }

    @media (max-width: 768px) {
        #shopg_shop_layout_contents.width-30 .shopg_shop_layouts, #shopg_shop_layout_contents .shopg_shop_layouts {
            grid-row-gap: $row_gap_mobile;
        }
    }

   /* Margin */
#shopg_shop_layout_contents.width-100 .shopg_shop_layouts,
#shopg_shop_layout_contents .shopg_shop_layouts {
    margin-top: {$shopbody_margin_top_desktop};
    margin-right: {$shopbody_margin_right_desktop};
    margin-bottom: {$shopbody_margin_bottom_desktop};
    margin-left: {$shopbody_margin_left_desktop};
}

@media (max-width: 1024px) {
    #shopg_shop_layout_contents.width-50 .shopg_shop_layouts,
    #shopg_shop_layout_contents .shopg_shop_layouts {
        margin-top: {$shopbody_margin_top_tablet};
        margin-right: {$shopbody_margin_right_tablet};
        margin-bottom: {$shopbody_margin_bottom_tablet};
        margin-left: {$shopbody_margin_left_tablet};
    }
}

@media (max-width: 768px) {
    #shopg_shop_layout_contents.width-30 .shopg_shop_layouts,
    #shopg_shop_layout_contents .shopg_shop_layouts {
        margin-top: {$shopbody_margin_top_mobile};
        margin-right: {$shopbody_margin_right_mobile};
        margin-bottom: {$shopbody_margin_bottom_mobile};
        margin-left: {$shopbody_margin_left_mobile};
    }
}

/* Padding */
#shopg_shop_layout_contents.width-100 .shopg_shop_layouts,
#shopg_shop_layout_contents .shopg_shop_layouts {
    padding-top: {$shopbody_padding_top_desktop};
    padding-right: {$shopbody_padding_right_desktop};
    padding-bottom: {$shopbody_padding_bottom_desktop};
    padding-left: {$shopbody_padding_left_desktop};
}

@media (max-width: 1024px) {
    #shopg_shop_layout_contents.width-50 .shopg_shop_layouts,
    #shopg_shop_layout_contents .shopg_shop_layouts {
        padding-top: {$shopbody_padding_top_tablet};
        padding-right: {$shopbody_padding_right_tablet};
        padding-bottom: {$shopbody_padding_bottom_tablet};
        padding-left: {$shopbody_padding_left_tablet};
    }
}

@media (max-width: 768px) {
    #shopg_shop_layout_contents.width-30 .shopg_shop_layouts,
    #shopg_shop_layout_contents .shopg_shop_layouts {
        padding-top: {$shopbody_padding_top_mobile};
        padding-right: {$shopbody_padding_right_mobile};
        padding-bottom: {$shopbody_padding_bottom_mobile};
        padding-left: {$shopbody_padding_left_mobile};
    }
}


    /* Body Typography */
    #shopg_shop_layout_contents .shopg_shop_layouts {
        font-family: {$body_font_family};
        font-weight: {$body_font_weight};
        font-style: {$body_font_style};
        text-transform: {$body_text_transform};
        text-decoration: {$body_text_decoration};
    }

    /* Background Colors */
    #shopg_shop_layout_contents .shopg_shop_layouts {
        background-color: {$normal_background};
		transition: background-color 0.3s ease;
    }

    #shopg_shop_layout_contents .shopg_shop_layouts:hover {
        background-color: {$hover_background};
		transition: background-color 0.3s ease;
    }
		 /* Border Settings */
    #shopg_shop_layout_contents .shopg_shop_layouts {
        border-style: {$border_style};
        border-color: {$border_color};
        border-width: {$border_width};
    }
    /* Product Margin */
     #shopg_shop_layout_contents.width-100 .shopg_shop_layouts .product-design,
	 #shopg_shop_layout_contents .shopg_shop_layouts .product-design
	 {
        margin-top: {$product_margin_desktop_top}{$product_margin_desktop_unit};
        margin-right: {$product_margin_desktop_right}{$product_margin_desktop_unit};
        margin-bottom: {$product_margin_desktop_bottom}{$product_margin_desktop_unit};
        margin-left: {$product_margin_desktop_left}{$product_margin_desktop_unit};
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts .product-design,
        #shopg_shop_layout_contents .shopg_shop_layouts .product-design {
            margin-top: {$product_margin_tablet_top}{$product_margin_tablet_unit};
            margin-right: {$product_margin_tablet_right}{$product_margin_tablet_unit};
            margin-bottom: {$product_margin_tablet_bottom}{$product_margin_tablet_unit};
            margin-left: {$product_margin_tablet_left}{$product_margin_tablet_unit};
        }
    }

    @media (max-width: 768px) {
        #shopg_shop_layout_contents.width-30 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
            margin-top: {$product_margin_mobile_top}{$product_margin_mobile_unit};
            margin-right: {$product_margin_mobile_right}{$product_margin_mobile_unit};
            margin-bottom: {$product_margin_mobile_bottom}{$product_margin_mobile_unit};
            margin-left: {$product_margin_mobile_left}{$product_margin_mobile_unit};
        }
    }

    /* Product Padding */
    #shopg_shop_layout_contents.width-100 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
        padding-top: {$product_padding_desktop_top}{$product_padding_desktop_unit};
        padding-right: {$product_padding_desktop_right}{$product_padding_desktop_unit};
        padding-bottom: {$product_padding_desktop_bottom}{$product_padding_desktop_unit};
        padding-left: {$product_padding_desktop_left}{$product_padding_desktop_unit};
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
            padding-top: {$product_padding_tablet_top}{$product_padding_tablet_unit};
            padding-right: {$product_padding_tablet_right}{$product_padding_tablet_unit};
            padding-bottom: {$product_padding_tablet_bottom}{$product_padding_tablet_unit};
            padding-left: {$product_padding_tablet_left}{$product_padding_tablet_unit};
        }
    }

    @media (max-width: 768px) {
        #shopg_shop_layout_contents.width-30 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
            padding-top: {$product_padding_mobile_top}{$product_padding_mobile_unit};
            padding-right: {$product_padding_mobile_right}{$product_padding_mobile_unit};
            padding-bottom: {$product_padding_mobile_bottom}{$product_padding_mobile_unit};
            padding-left: {$product_padding_mobile_left}{$product_padding_mobile_unit};
        }
    }

    /* Product Border */
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
        border-style: {$product_border_style};
        border-width: {$product_border_top}px {$product_border_right}px {$product_border_bottom}px {$product_border_left}px;
        border-color: {$product_border_color};
    }

    /* Product Border Radius */
#shopg_shop_layout_contents.width-100 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
        border-top-left-radius: {$product_radius_desktop_top}{$product_radius_desktop_unit};
        border-top-right-radius: {$product_radius_desktop_right}{$product_radius_desktop_unit};
        border-bottom-right-radius: {$product_radius_desktop_bottom}{$product_radius_desktop_unit};
        border-bottom-left-radius: {$product_radius_desktop_left}{$product_radius_desktop_unit};
    }

    @media (max-width: 1024px) {
        #shopg_shop_layout_contents.width-50 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design{
            border-top-left-radius: {$product_radius_tablet_top}{$product_radius_tablet_unit};
            border-top-right-radius: {$product_radius_tablet_right}{$product_radius_tablet_unit};
            border-bottom-right-radius: {$product_radius_tablet_bottom}{$product_radius_tablet_unit};
            border-bottom-left-radius: {$product_radius_tablet_left}{$product_radius_tablet_unit};
        }
    }

    @media (max-width: 768px) {
        #shopg_shop_layout_contents.width-30 .shopg_shop_layouts .product-design,
#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
            border-top-left-radius: {$product_radius_mobile_top}{$product_radius_mobile_unit};
            border-top-right-radius: {$product_radius_mobile_right}{$product_radius_mobile_unit};
            border-bottom-right-radius: {$product_radius_mobile_bottom}{$product_radius_mobile_unit};
            border-bottom-left-radius: {$product_radius_mobile_left}{$product_radius_mobile_unit};
        }
    }

	#shopg_shop_layout_contents .shopg_shop_layouts .product-design {
        background-color: {$product_background_color};
		transition: background-color 0.3s ease;
    }

	#shopg_shop_layout_contents .shopg_shop_layouts .product-design:hover {
        background-color: {$product_background_hover_color};
		transition: background-color 0.3s ease;
    }

    /* Product Caption Background */
#shopg_shop_layout_contents .shopg_shop_layouts .product-caption {
        background-color: {$product_caption_background};
		transition: background-color 0.3s ease;
    }

#shopg_shop_layout_contents .shopg_shop_layouts .product-caption:hover {
        background-color: {$product_caption_hover_background};
		transition: background-color 0.3s ease;
    }

    /* Product Title */
   #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-title {
        font-family: {$product_title_font_family};
        font-weight: {$product_title_font_weight};
        font-style: {$product_title_font_style};
        text-transform: {$product_title_text_transform};
        text-decoration: {$product_title_text_decoration};
        font-size: {$product_title_font_size}{$product_title_font_size_unit};
        line-height: {$product_title_line_height}{$product_title_line_height_unit};
        letter-spacing: {$product_title_letter_spacing}{$product_title_letter_spacing_unit};
        word-spacing: {$product_title_word_spacing}{$product_title_word_spacing_unit};
        color: {$product_title_color};
    }

    #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-title:hover {
        color: {$product_title_hover_color};
    }
    /* Product Category */
    #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-category {
        font-family: {$product_category_font_family};
        font-weight: {$product_category_font_weight};
        font-style: {$product_category_font_style};
        text-transform: {$product_category_text_transform};
        text-decoration: {$product_category_text_decoration};
        font-size: {$product_category_font_size}{$product_category_font_size_unit};
        line-height: {$product_category_line_height}{$product_category_line_height_unit};
        letter-spacing: {$product_category_letter_spacing}{$product_category_letter_spacing_unit};
        word-spacing: {$product_category_word_spacing}{$product_category_word_spacing_unit};
        color: {$product_category_color};
    }

    #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-category:hover {
        color: {$product_category_hover_color};
    }

    /* Product Price */
    #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-price {
        font-family: {$product_price_font_family};
        font-weight: {$product_price_font_weight};
        font-style: {$product_price_font_style};
        text-transform: {$product_price_text_transform};
        text-decoration: {$product_price_text_decoration};
        font-size: {$product_price_font_size}{$product_price_font_size_unit};
        line-height: {$product_price_line_height}{$product_price_line_height_unit};
        letter-spacing: {$product_price_letter_spacing}{$product_price_letter_spacing_unit};
        word-spacing: {$product_price_word_spacing}{$product_price_word_spacing_unit};
        color: {$product_price_color};
    }

  #shopg_shop_layout_contents .shopg_shop_layouts .product-design .product-price:hover {
        color: {$product_price_hover_color};
    }
  #shopg_shop_layout_contents .shopg_shop_layouts .product-design .price-old{
        color: {$product_price_old_color};
    }
  #shopg_shop_layout_contents .shopg_shop_layouts .product-design .price-old:hover {
        color: {$product_price_old_hover_color};
    }

    /* Product Review */
    #shopg_shop_layout_contents .shopg_shop_layouts .product-design .ratings {
        color: {$product_review_color};
    }

	#shopg_shop_layout_contents .product-design .grouped-product{
	width: 178px;
    display: block;
    margin: 0 auto;
	color: {$add_to_cart_font_color};
		}

	#shopg_shop_layout_contents .product-design.product-template1 .variable-product,
	#shopg_shop_layout_contents .product-design.product-template2 .variable-product,
	#shopg_shop_layout_contents .product-design.product-template3 .variable-product,
	#shopg_shop_layout_contents .product-design.product-template4 .variable-product,
	#shopg_shop_layout_contents .product-design.product-template5 .variable-product
	{
	width: 175px;
    display: block;
    margin: 0 auto;
	color: {$add_to_cart_font_color};
		}
	
	#shopg_shop_layout_contents .product-design .external-product{
	       width: 175px;
    display: block;
    margin: 0 auto;
	color: {$add_to_cart_font_color};
		}

	#shopg_shop_layout_contents .product-design .add-to-cart.not-allowed-purchase{
	width: 245px;
	color: {$add_to_cart_font_color};
		}

	/* Add to Cart Button - Desktop */
    #shopg_shop_layout_contents .product-design .add-to-cart {
        color: {$add_to_cart_font_color};
        background-color: {$add_to_cart_bg_color};
        width: {$add_to_cart_width_desktop}{$add_to_cart_width_desktop_unit};
        margin-top: {$add_to_cart_margin_desktop_top}{$add_to_cart_margin_desktop_unit};
        margin-right: {$add_to_cart_margin_desktop_right}{$add_to_cart_margin_desktop_unit};
        margin-bottom: {$add_to_cart_margin_desktop_bottom}{$add_to_cart_margin_desktop_unit};
        margin-left: {$add_to_cart_margin_desktop_left}{$add_to_cart_margin_desktop_unit};
        padding-top: {$add_to_cart_padding_desktop_top}{$add_to_cart_padding_desktop_unit};
        padding-right: {$add_to_cart_padding_desktop_right}{$add_to_cart_padding_desktop_unit};
        padding-bottom: {$add_to_cart_padding_desktop_bottom}{$add_to_cart_padding_desktop_unit};
        padding-left: {$add_to_cart_padding_desktop_left}{$add_to_cart_padding_desktop_unit};
    }

   #shopg_shop_layout_contents .product-design .add-to-cart i {
        color: {$add_to_cart_icon_color};
    }

   #shopg_shop_layout_contents .product-design .add-to-cart i:hover {
        color: {$add_to_cart_hover_icon_color};
    }

	 /* Add to Cart Button Hover */
    #shopg_shop_layout_contents .product-design .add-to-cart:hover {
        color: {$add_to_cart_hover_font_color};
        background-color: {$add_to_cart_hover_bg_color};
    }

    /* Add to Cart Button - Tablet */
    @media (max-width: 1024px) {
       #shopg_shop_layout_contents .product-design .add-to-cart {
            width: {$add_to_cart_width_tablet}{$add_to_cart_width_tablet_unit};
            margin-top: {$add_to_cart_margin_tablet_top}{$add_to_cart_margin_tablet_unit};
            margin-right: {$add_to_cart_margin_tablet_right}{$add_to_cart_margin_tablet_unit};
            margin-bottom: {$add_to_cart_margin_tablet_bottom}{$add_to_cart_margin_tablet_unit};
            margin-left: {$add_to_cart_margin_tablet_left}{$add_to_cart_margin_tablet_unit};
            padding-top: {$add_to_cart_padding_tablet_top}{$add_to_cart_padding_tablet_unit};
            padding-right: {$add_to_cart_padding_tablet_right}{$add_to_cart_padding_tablet_unit};
            padding-bottom: {$add_to_cart_padding_tablet_bottom}{$add_to_cart_padding_tablet_unit};
            padding-left: {$add_to_cart_padding_tablet_left}{$add_to_cart_padding_tablet_unit};
        }
    }

    /* Add to Cart Button - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .add-to-cart {
            width: {$add_to_cart_width_mobile}{$add_to_cart_width_mobile_unit};
            margin-top: {$add_to_cart_margin_mobile_top}{$add_to_cart_margin_mobile_unit};
            margin-right: {$add_to_cart_margin_mobile_right}{$add_to_cart_margin_mobile_unit};
            margin-bottom: {$add_to_cart_margin_mobile_bottom}{$add_to_cart_margin_mobile_unit};
            margin-left: {$add_to_cart_margin_mobile_left}{$add_to_cart_margin_mobile_unit};
            padding-top: {$add_to_cart_padding_mobile_top}{$add_to_cart_padding_mobile_unit};
            padding-right: {$add_to_cart_padding_mobile_right}{$add_to_cart_padding_mobile_unit};
            padding-bottom: {$add_to_cart_padding_mobile_bottom}{$add_to_cart_padding_mobile_unit};
            padding-left: {$add_to_cart_padding_mobile_left}{$add_to_cart_padding_mobile_unit};
        }
    }


    /* Wishlist Button - Desktop */
    #shopg_shop_layout_contents .product-design .wishlist {
        background-color: {$wishlist_bg_color};
        width: {$wishlist_width_desktop};
        margin-top: {$wishlist_margin_top_desktop};
        margin-right: {$wishlist_margin_right_desktop};
        margin-bottom: {$wishlist_margin_bottom_desktop};
        margin-left: {$wishlist_margin_left_desktop};
        padding-top: {$wishlist_padding_top_desktop};
        padding-right: {$wishlist_padding_right_desktop};
        padding-bottom: {$wishlist_padding_bottom_desktop};
        padding-left: {$wishlist_padding_left_desktop};
    }

	

	 /* Wishlist Button Hover */
  #shopg_shop_layout_contents .product-design .wishlist:hover {
        background-color: {$wishlist_hover_bg_color};
    }

    /* Wishlist Button - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .wishlist {
            width: {$wishlist_width_tablet};
            margin-top: {$wishlist_margin_top_tablet};
            margin-right: {$wishlist_margin_right_tablet};
            margin-bottom: {$wishlist_margin_bottom_tablet};
            margin-left: {$wishlist_margin_left_tablet};
            padding-top: {$wishlist_padding_top_tablet};
            padding-right: {$wishlist_padding_right_tablet};
            padding-bottom: {$wishlist_padding_bottom_tablet};
            padding-left: {$wishlist_padding_left_tablet};
        }
    }

    /* Wishlist Button - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .wishlist {
            width: {$wishlist_width_mobile};
            margin-top: {$wishlist_margin_top_mobile};
            margin-right: {$wishlist_margin_right_mobile};
            margin-bottom: {$wishlist_margin_bottom_mobile};
            margin-left: {$wishlist_margin_left_mobile};
            padding-top: {$wishlist_padding_top_mobile};
            padding-right: {$wishlist_padding_right_mobile};
            padding-bottom: {$wishlist_padding_bottom_mobile};
            padding-left: {$wishlist_padding_left_mobile};
        }
    }
#shopg_shop_layout_contents .product-design.product-template1 .wishlist i,
	#shopg_shop_layout_contents .product-design.product-template2 .wishlist i,
	#shopg_shop_layout_contents .product-design.product-template3 .wishlist i, 
	#shopg_shop_layout_contents .product-design.product-template4 .wishlist i,
	#shopg_shop_layout_contents .product-design.product-template5 .wishlist i {
        color: {$wishlist_icon_color};
    }

   #shopg_shop_layout_contents .product-design.product-template1 .wishlist i:hover, 
   #shopg_shop_layout_contents .product-design.product-template2 .wishlist i:hover, 
   #shopg_shop_layout_contents .product-design.product-template3 .wishlist i:hover, 
   #shopg_shop_layout_contents .product-design.product-template4 .wishlist i:hover, 
   #shopg_shop_layout_contents .product-design.product-template5 .wishlist i:hover,
   #shopg_shop_layout_contents .product-design.product-template11 .wishlist i:hover  
   {
        color: {$wishlist_hover_icon_color};
    }

    /* Compare Button - Desktop */
    #shopg_shop_layout_contents .product-design .compare {
        background-color: {$compare_bg_color};
        width: {$compare_width_desktop};
        margin-top: {$compare_margin_top_desktop};
        margin-right: {$compare_margin_right_desktop};
        margin-bottom: {$compare_margin_bottom_desktop};
        margin-left: {$compare_margin_left_desktop};
        padding-top: {$compare_padding_top_desktop};
        padding-right: {$compare_padding_right_desktop};
        padding-bottom: {$compare_padding_bottom_desktop};
        padding-left: {$compare_padding_left_desktop};
    }

    /* Compare Button - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .compare {
            width: {$compare_width_tablet};
            margin-top: {$compare_margin_top_tablet};
            margin-right: {$compare_margin_right_tablet};
            margin-bottom: {$compare_margin_bottom_tablet};
            margin-left: {$compare_margin_left_tablet};
            padding-top: {$compare_padding_top_tablet};
            padding-right: {$compare_padding_right_tablet};
            padding-bottom: {$compare_padding_bottom_tablet};
            padding-left: {$compare_padding_left_tablet};
        }
    }

    /* Compare Button - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .compare {
            width: {$compare_width_mobile};
            margin-top: {$compare_margin_top_mobile};
            margin-right: {$compare_margin_right_mobile};
            margin-bottom: {$compare_margin_bottom_mobile};
            margin-left: {$compare_margin_left_mobile};
            padding-top: {$compare_padding_top_mobile};
            padding-right: {$compare_padding_right_mobile};
            padding-bottom: {$compare_padding_bottom_mobile};
            padding-left: {$compare_padding_left_mobile};
        }
    }

    /* Compare Button Hover */
    #shopg_shop_layout_contents .product-design .compare:hover {

        background-color: {$compare_hover_bg_color};
    }

   #shopg_shop_layout_contents .product-design .compare i {
          color: {$compare_icon_color};
    }

   #shopg_shop_layout_contents .product-design .compare i:hover {
         color: {$compare_hover_icon_color};
    }


    /* Quick View Button - Desktop */
    #shopg_shop_layout_contents .product-design .quick-view {
        background-color: {$quick_views_bg_color};
        width: {$quick_views_width_desktop};
        margin-top: {$quick_views_margin_top_desktop};
        margin-right: {$quick_views_margin_right_desktop};
        margin-bottom: {$quick_views_margin_bottom_desktop};
        margin-left: {$quick_views_margin_left_desktop};
        padding-top: {$quick_views_padding_top_desktop};
        padding-right: {$quick_views_padding_right_desktop};
        padding-bottom: {$quick_views_padding_bottom_desktop};
        padding-left: {$quick_views_padding_left_desktop};
    }

    /* Quick View Button - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .quick-view {
            width: {$quick_views_width_tablet};
            margin-top: {$quick_views_margin_top_tablet};
            margin-right: {$quick_views_margin_right_tablet};
            margin-bottom: {$quick_views_margin_bottom_tablet};
            margin-left: {$quick_views_margin_left_tablet};
            padding-top: {$quick_views_padding_top_tablet};
            padding-right: {$quick_views_padding_right_tablet};
            padding-bottom: {$quick_views_padding_bottom_tablet};
            padding-left: {$quick_views_padding_left_tablet};
        }
    }

    /* Quick View Button - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .quick-view {
            width: {$quick_views_width_mobile};
            margin-top: {$quick_views_margin_top_mobile};
            margin-right: {$quick_views_margin_right_mobile};
            margin-bottom: {$quick_views_margin_bottom_mobile};
            margin-left: {$quick_views_margin_left_mobile};
            padding-top: {$quick_views_padding_top_mobile};
            padding-right: {$quick_views_padding_right_mobile};
            padding-bottom: {$quick_views_padding_bottom_mobile};
            padding-left: {$quick_views_padding_left_mobile};
        }
    }

    /* Quick View Button Hover */
   #shopg_shop_layout_contents .product-design .quick-view:hover {
        background-color: {$quick_views_hover_bg_color};
    }


	#shopg_shop_layout_contents .product-design .quick-view i {
         color: {$quick_views_icon_color};
    }

   #shopg_shop_layout_contents .product-design .quick-view i:hover {
         color: {$quick_views_hover_icon_color};
    }

    /* Image Padding - Desktop */
    #shopg_shop_layout_contents .product-design .product-thumb img{
        padding-top: {$image_padding_desktop_top}{$image_padding_desktop_unit};
        padding-right: {$image_padding_desktop_right}{$image_padding_desktop_unit};
        padding-bottom: {$image_padding_desktop_bottom}{$image_padding_desktop_unit};
        padding-left: {$image_padding_desktop_left}{$image_padding_desktop_unit};
        background-color: {$image_normal_background};
    }

    /* Image Padding - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .product-thumb img {
            padding-top: {$image_padding_tablet_top}{$image_padding_tablet_unit};
            padding-right: {$image_padding_tablet_right}{$image_padding_tablet_unit};
            padding-bottom: {$image_padding_tablet_bottom}{$image_padding_tablet_unit};
            padding-left: {$image_padding_tablet_left}{$image_padding_tablet_unit};
        }
    }

    /* Image Padding - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .product-thumb img {
            padding-top: {$image_padding_mobile_top}{$image_padding_mobile_unit};
            padding-right: {$image_padding_mobile_right}{$image_padding_mobile_unit};
            padding-bottom: {$image_padding_mobile_bottom}{$image_padding_mobile_unit};
            padding-left: {$image_padding_mobile_left}{$image_padding_mobile_unit};
        }
    }

    /* Image Background Hover */
    #shopg_shop_layout_contents .product-design .product-thumb img:hover {
        background-color: {$image_hover_background};
    }
     /* New Badge Margin - Desktop */
   #shopg_shop_layout_contents .product-design .new-badge {
        margin-top: {$new_badge_margin_desktop_top}{$new_badge_margin_desktop_unit};
        margin-right: {$new_badge_margin_desktop_right}{$new_badge_margin_desktop_unit};
        margin-bottom: {$new_badge_margin_desktop_bottom}{$new_badge_margin_desktop_unit};
        margin-left: {$new_badge_margin_desktop_left}{$new_badge_margin_desktop_unit};
        padding-top: {$new_badge_padding_desktop_top}{$new_badge_padding_desktop_unit};
        padding-right: {$new_badge_padding_desktop_right}{$new_badge_padding_desktop_unit};
        padding-bottom: {$new_badge_padding_desktop_bottom}{$new_badge_padding_desktop_unit};
        padding-left: {$new_badge_padding_desktop_left}{$new_badge_padding_desktop_unit};
        width: {$new_product_width_desktop}{$new_product_width_desktop_unit};
        height: {$new_product_height_desktop}{$new_product_height_desktop_unit};
        color: {$new_product_color};
        background-color: {$new_product_bgcolor};
        border-style: {$new_badge_border_style};
        border-top-width: {$new_badge_border_top}px;
        border-right-width: {$new_badge_border_right}px;
        border-bottom-width: {$new_badge_border_bottom}px;
        border-left-width: {$new_badge_border_left}px;
        border-color: {$new_badge_border_color};
        border-top-left-radius: {$new_badge_radius_desktop_top}{$new_badge_radius_desktop_unit};
        border-top-right-radius: {$new_badge_radius_desktop_right}{$new_badge_radius_desktop_unit};
        border-bottom-right-radius: {$new_badge_radius_desktop_bottom}{$new_badge_radius_desktop_unit};
        border-bottom-left-radius: {$new_badge_radius_desktop_left}{$new_badge_radius_desktop_unit};
        font-family: {$new_badge_font_family};
        font-weight: {$new_badge_font_weight};
        font-style: {$new_badge_font_style};
        text-transform: {$new_badge_text_transform};
        text-decoration: {$new_badge_text_decoration};
    }

    /* New Badge Margin - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .new-badge {
            margin-top: {$new_badge_margin_tablet_top}{$new_badge_margin_tablet_unit};
            margin-right: {$new_badge_margin_tablet_right}{$new_badge_margin_tablet_unit};
            margin-bottom: {$new_badge_margin_tablet_bottom}{$new_badge_margin_tablet_unit};
            margin-left: {$new_badge_margin_tablet_left}{$new_badge_margin_tablet_unit};
            padding-top: {$new_badge_padding_tablet_top}{$new_badge_padding_tablet_unit};
            padding-right: {$new_badge_padding_tablet_right}{$new_badge_padding_tablet_unit};
            padding-bottom: {$new_badge_padding_tablet_bottom}{$new_badge_padding_tablet_unit};
            padding-left: {$new_badge_padding_tablet_left}{$new_badge_padding_tablet_unit};
            width: {$new_product_width_tablet}{$new_product_width_tablet_unit};
            height: {$new_product_height_tablet}{$new_product_height_tablet_unit};
            border-top-left-radius: {$new_badge_radius_tablet_top}{$new_badge_radius_tablet_unit};
            border-top-right-radius: {$new_badge_radius_tablet_right}{$new_badge_radius_tablet_unit};
            border-bottom-right-radius: {$new_badge_radius_tablet_bottom}{$new_badge_radius_tablet_unit};
            border-bottom-left-radius: {$new_badge_radius_tablet_left}{$new_badge_radius_tablet_unit};
        }
    }

    /* New Badge Margin - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .new-badge {
            margin-top: {$new_badge_margin_mobile_top}{$new_badge_margin_mobile_unit};
            margin-right: {$new_badge_margin_mobile_right}{$new_badge_margin_mobile_unit};
            margin-bottom: {$new_badge_margin_mobile_bottom}{$new_badge_margin_mobile_unit};
            margin-left: {$new_badge_margin_mobile_left}{$new_badge_margin_mobile_unit};
            padding-top: {$new_badge_padding_mobile_top}{$new_badge_padding_mobile_unit};
            padding-right: {$new_badge_padding_mobile_right}{$new_badge_padding_mobile_unit};
            padding-bottom: {$new_badge_padding_mobile_bottom}{$new_badge_padding_mobile_unit};
            padding-left: {$new_badge_padding_mobile_left}{$new_badge_padding_mobile_unit};
            width: {$new_product_width_mobile}{$new_product_width_mobile_unit};
            height: {$new_product_height_mobile}{$new_product_height_mobile_unit};
            border-top-left-radius: {$new_badge_radius_mobile_top}{$new_badge_radius_mobile_unit};
            border-top-right-radius: {$new_badge_radius_mobile_right}{$new_badge_radius_mobile_unit};
            border-bottom-right-radius: {$new_badge_radius_mobile_bottom}{$new_badge_radius_mobile_unit};
            border-bottom-left-radius: {$new_badge_radius_mobile_left}{$new_badge_radius_mobile_unit};
        }
    }

    /* Out-of-Stock Badge Margin - Desktop */
    #shopg_shop_layout_contents .product-design .outofstock-badge {
        margin-top: {$outofstock_badge_margin_desktop_top}{$outofstock_badge_margin_desktop_unit};
        margin-right: {$outofstock_badge_margin_desktop_right}{$outofstock_badge_margin_desktop_unit};
        margin-bottom: {$outofstock_badge_margin_desktop_bottom}{$outofstock_badge_margin_desktop_unit};
        margin-left: {$outofstock_badge_margin_desktop_left}{$outofstock_badge_margin_desktop_unit};
        padding-top: {$outofstock_badge_padding_desktop_top}{$outofstock_badge_padding_desktop_unit};
        padding-right: {$outofstock_badge_padding_desktop_right}{$outofstock_badge_padding_desktop_unit};
        padding-bottom: {$outofstock_badge_padding_desktop_bottom}{$outofstock_badge_padding_desktop_unit};
        padding-left: {$outofstock_badge_padding_desktop_left}{$outofstock_badge_padding_desktop_unit};
        width: {$outofstock_product_width_desktop}{$outofstock_product_width_desktop_unit};
        height: {$outofstock_product_height_desktop}{$outofstock_product_height_desktop_unit};
        color: {$outofstock_product_color};
        background-color: {$outofstock_product_bgcolor};
        border-style: {$outofstock_badge_border_style};
        border-color: {$outofstock_badge_border_color};
        border-top-left-radius: {$outofstock_badge_radius_desktop_top}{$outofstock_badge_radius_desktop_unit};
        border-top-right-radius: {$outofstock_badge_radius_desktop_right}{$outofstock_badge_radius_desktop_unit};
        border-bottom-right-radius: {$outofstock_badge_radius_desktop_bottom}{$outofstock_badge_radius_desktop_unit};
        border-bottom-left-radius: {$outofstock_badge_radius_desktop_left}{$outofstock_badge_radius_desktop_unit};
        font-family: {$outofstock_badge_font_family};
        font-weight: {$outofstock_badge_font_weight};
        font-style: {$outofstock_badge_font_style};
        text-transform: {$outofstock_badge_text_transform};
        text-decoration: {$outofstock_badge_text_decoration};
    }

    /* Out-of-Stock Badge Margin - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .outofstock-badge {
            margin-top: {$outofstock_badge_margin_tablet_top}{$outofstock_badge_margin_tablet_unit};
            margin-right: {$outofstock_badge_margin_tablet_right}{$outofstock_badge_margin_tablet_unit};
            margin-bottom: {$outofstock_badge_margin_tablet_bottom}{$outofstock_badge_margin_tablet_unit};
            margin-left: {$outofstock_badge_margin_tablet_left}{$outofstock_badge_margin_tablet_unit};
            padding-top: {$outofstock_badge_padding_tablet_top}{$outofstock_badge_padding_tablet_unit};
            padding-right: {$outofstock_badge_padding_tablet_right}{$outofstock_badge_padding_tablet_unit};
            padding-bottom: {$outofstock_badge_padding_tablet_bottom}{$outofstock_badge_padding_tablet_unit};
            padding-left: {$outofstock_badge_padding_tablet_left}{$outofstock_badge_padding_tablet_unit};
            width: {$outofstock_product_width_tablet}{$outofstock_product_width_tablet_unit};
            height: {$outofstock_product_height_tablet}{$outofstock_product_height_tablet_unit};
            border-top-left-radius: {$outofstock_badge_radius_tablet_top}{$outofstock_badge_radius_tablet_unit};
            border-top-right-radius: {$outofstock_badge_radius_tablet_right}{$outofstock_badge_radius_tablet_unit};
            border-bottom-right-radius: {$outofstock_badge_radius_tablet_bottom}{$outofstock_badge_radius_tablet_unit};
            border-bottom-left-radius: {$outofstock_badge_radius_tablet_left}{$outofstock_badge_radius_tablet_unit};
        }
    }

    /* Out-of-Stock Badge Margin - Mobile */
    @media (max-width: 768px) {
       #shopg_shop_layout_contents .product-design .outofstock-badge {
            margin-top: {$outofstock_badge_margin_mobile_top}{$outofstock_badge_margin_mobile_unit};
            margin-right: {$outofstock_badge_margin_mobile_right}{$outofstock_badge_margin_mobile_unit};
            margin-bottom: {$outofstock_badge_margin_mobile_bottom}{$outofstock_badge_margin_mobile_unit};
            margin-left: {$outofstock_badge_margin_mobile_left}{$outofstock_badge_margin_mobile_unit};
            padding-top: {$outofstock_badge_padding_mobile_top}{$outofstock_badge_padding_mobile_unit};
            padding-right: {$outofstock_badge_padding_mobile_right}{$outofstock_badge_padding_mobile_unit};
            padding-bottom: {$outofstock_badge_padding_mobile_bottom}{$outofstock_badge_padding_mobile_unit};
            padding-left: {$outofstock_badge_padding_mobile_left}{$outofstock_badge_padding_mobile_unit};
            width: {$outofstock_product_width_mobile}{$outofstock_product_width_mobile_unit};
            height: {$outofstock_product_height_mobile}{$outofstock_product_height_mobile_unit};
            border-top-left-radius: {$outofstock_badge_radius_mobile_top}{$outofstock_badge_radius_mobile_unit};
            border-top-right-radius: {$outofstock_badge_radius_mobile_right}{$outofstock_badge_radius_mobile_unit};
            border-bottom-right-radius: {$outofstock_badge_radius_mobile_bottom}{$outofstock_badge_radius_mobile_unit};
            border-bottom-left-radius: {$outofstock_badge_radius_mobile_left}{$outofstock_badge_radius_mobile_unit};
        }
    }
    /* Featured Badge Margin - Desktop */
    #shopg_shop_layout_contents .product-design .featured-badge {
        margin-top: {$featured_badge_margin_desktop_top}{$featured_badge_margin_desktop_unit};
        margin-right: {$featured_badge_margin_desktop_right}{$featured_badge_margin_desktop_unit};
        margin-bottom: {$featured_badge_margin_desktop_bottom}{$featured_badge_margin_desktop_unit};
        margin-left: {$featured_badge_margin_desktop_left}{$featured_badge_margin_desktop_unit};
        padding-top: {$featured_badge_padding_desktop_top}{$featured_badge_padding_desktop_unit};
        padding-right: {$featured_badge_padding_desktop_right}{$featured_badge_padding_desktop_unit};
        padding-bottom: {$featured_badge_padding_desktop_bottom}{$featured_badge_padding_desktop_unit};
        padding-left: {$featured_badge_padding_desktop_left}{$featured_badge_padding_desktop_unit};
        width: {$featured_product_width_desktop}{$featured_product_width_desktop_unit};
        height: {$featured_product_height_desktop}{$featured_product_height_desktop_unit};
        color: {$featured_product_color};
        background-color: {$featured_product_bgcolor};
        border-style: {$featured_badge_border_style};
        border-color: {$featured_badge_border_color};
        border-top-left-radius: {$featured_badge_radius_desktop_top}{$featured_badge_radius_desktop_unit};
        border-top-right-radius: {$featured_badge_radius_desktop_right}{$featured_badge_radius_desktop_unit};
        border-bottom-right-radius: {$featured_badge_radius_desktop_bottom}{$featured_badge_radius_desktop_unit};
        border-bottom-left-radius: {$featured_badge_radius_desktop_left}{$featured_badge_radius_desktop_unit};
        font-family: {$featured_badge_font_family};
        font-weight: {$featured_badge_font_weight};
        font-style: {$featured_badge_font_style};
        text-transform: {$featured_badge_text_transform};
        text-decoration: {$featured_badge_text_decoration};
    }

    /* Featured Badge Margin - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .featured-badge {
            margin-top: {$featured_badge_margin_tablet_top}{$featured_badge_margin_tablet_unit};
            margin-right: {$featured_badge_margin_tablet_right}{$featured_badge_margin_tablet_unit};
            margin-bottom: {$featured_badge_margin_tablet_bottom}{$featured_badge_margin_tablet_unit};
            margin-left: {$featured_badge_margin_tablet_left}{$featured_badge_margin_tablet_unit};
            padding-top: {$featured_badge_padding_tablet_top}{$featured_badge_padding_tablet_unit};
            padding-right: {$featured_badge_padding_tablet_right}{$featured_badge_padding_tablet_unit};
            padding-bottom: {$featured_badge_padding_tablet_bottom}{$featured_badge_padding_tablet_unit};
            padding-left: {$featured_badge_padding_tablet_left}{$featured_badge_padding_tablet_unit};
            width: {$featured_product_width_tablet}{$featured_product_width_tablet_unit};
            height: {$featured_product_height_tablet}{$featured_product_height_tablet_unit};
            border-top-left-radius: {$featured_badge_radius_tablet_top}{$featured_badge_radius_tablet_unit};
            border-top-right-radius: {$featured_badge_radius_tablet_right}{$featured_badge_radius_tablet_unit};
            border-bottom-right-radius: {$featured_badge_radius_tablet_bottom}{$featured_badge_radius_tablet_unit};
            border-bottom-left-radius: {$featured_badge_radius_tablet_left}{$featured_badge_radius_tablet_unit};
        }
    }

    /* Featured Badge Margin - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .featured-badge {
            margin-top: {$featured_badge_margin_mobile_top}{$featured_badge_margin_mobile_unit};
            margin-right: {$featured_badge_margin_mobile_right}{$featured_badge_margin_mobile_unit};
            margin-bottom: {$featured_badge_margin_mobile_bottom}{$featured_badge_margin_mobile_unit};
            margin-left: {$featured_badge_margin_mobile_left}{$featured_badge_margin_mobile_unit};
            padding-top: {$featured_badge_padding_mobile_top}{$featured_badge_padding_mobile_unit};
            padding-right: {$featured_badge_padding_mobile_right}{$featured_badge_padding_mobile_unit};
            padding-bottom: {$featured_badge_padding_mobile_bottom}{$featured_badge_padding_mobile_unit};
            padding-left: {$featured_badge_padding_mobile_left}{$featured_badge_padding_mobile_unit};
            width: {$featured_product_width_mobile}{$featured_product_width_mobile_unit};
            height: {$featured_product_height_mobile}{$featured_product_height_mobile_unit};
            border-top-left-radius: {$featured_badge_radius_mobile_top}{$featured_badge_radius_mobile_unit};
            border-top-right-radius: {$featured_badge_radius_mobile_right}{$featured_badge_radius_mobile_unit};
            border-bottom-right-radius: {$featured_badge_radius_mobile_bottom}{$featured_badge_radius_mobile_unit};
            border-bottom-left-radius: {$featured_badge_radius_mobile_left}{$featured_badge_radius_mobile_unit};
        }
    }
    /* Discount Badge Margin - Desktop */
    #shopg_shop_layout_contents .product-design .discount-badge {
        margin-top: {$discount_badge_margin_desktop_top}{$discount_badge_margin_desktop_unit};
        margin-right: {$discount_badge_margin_desktop_right}{$discount_badge_margin_desktop_unit};
        margin-bottom: {$discount_badge_margin_desktop_bottom}{$discount_badge_margin_desktop_unit};
        margin-left: {$discount_badge_margin_desktop_left}{$discount_badge_margin_desktop_unit};
        padding-top: {$discount_badge_padding_desktop_top}{$discount_badge_padding_desktop_unit};
        padding-right: {$discount_badge_padding_desktop_right}{$discount_badge_padding_desktop_unit};
        padding-bottom: {$discount_badge_padding_desktop_bottom}{$discount_badge_padding_desktop_unit};
        padding-left: {$discount_badge_padding_desktop_left}{$discount_badge_padding_desktop_unit};
        width: {$discount_badge_width_desktop}{$discount_badge_width_desktop_unit};
        height: {$discount_badge_height_desktop}{$discount_badge_height_desktop_unit};
        color: {$discount_badge_color};
        background-color: {$discount_badge_bgcolor};
        border-style: {$discount_badge_border_style};
        border-color: {$discount_badge_border_color};
        border-width: {$discount_badge_border_top}px {$discount_badge_border_right}px {$discount_badge_border_bottom}px {$discount_badge_border_left}px;
        border-top-left-radius: {$discount_badge_radius_desktop_top}{$discount_badge_radius_desktop_unit};
        border-top-right-radius: {$discount_badge_radius_desktop_right}{$discount_badge_radius_desktop_unit};
        border-bottom-right-radius: {$discount_badge_radius_desktop_bottom}{$discount_badge_radius_desktop_unit};
        border-bottom-left-radius: {$discount_badge_radius_desktop_left}{$discount_badge_radius_desktop_unit};
        font-family: {$discount_badge_font_family};
        font-weight: {$discount_badge_font_weight};
        font-style: {$discount_badge_font_style};
        text-transform: {$discount_badge_text_transform};
        text-decoration: {$discount_badge_text_decoration};
    }

    /* Discount Badge Margin - Tablet */
    @media (max-width: 1024px) {
        #shopg_shop_layout_contents .product-design .discount-badge {
            margin-top: {$discount_badge_margin_tablet_top}{$discount_badge_margin_tablet_unit};
            margin-right: {$discount_badge_margin_tablet_right}{$discount_badge_margin_tablet_unit};
            margin-bottom: {$discount_badge_margin_tablet_bottom}{$discount_badge_margin_tablet_unit};
            margin-left: {$discount_badge_margin_tablet_left}{$discount_badge_margin_tablet_unit};
            padding-top: {$discount_badge_padding_tablet_top}{$discount_badge_padding_tablet_unit};
            padding-right: {$discount_badge_padding_tablet_right}{$discount_badge_padding_tablet_unit};
            padding-bottom: {$discount_badge_padding_tablet_bottom}{$discount_badge_padding_tablet_unit};
            padding-left: {$discount_badge_padding_tablet_left}{$discount_badge_padding_tablet_unit};
            width: {$discount_badge_width_tablet}{$discount_badge_width_tablet_unit};
            height: {$discount_badge_height_tablet}{$discount_badge_height_tablet_unit};
            border-top-left-radius: {$discount_badge_radius_tablet_top}{$discount_badge_radius_tablet_unit};
            border-top-right-radius: {$discount_badge_radius_tablet_right}{$discount_badge_radius_tablet_unit};
            border-bottom-right-radius: {$discount_badge_radius_tablet_bottom}{$discount_badge_radius_tablet_unit};
            border-bottom-left-radius: {$discount_badge_radius_tablet_left}{$discount_badge_radius_tablet_unit};
        }
    }

    /* Discount Badge Margin - Mobile */
    @media (max-width: 768px) {
        #shopg_shop_layout_contents .product-design .discount-badge {
            margin-top: {$discount_badge_margin_mobile_top}{$discount_badge_margin_mobile_unit};
            margin-right: {$discount_badge_margin_mobile_right}{$discount_badge_margin_mobile_unit};
            margin-bottom: {$discount_badge_margin_mobile_bottom}{$discount_badge_margin_mobile_unit};
            margin-left: {$discount_badge_margin_mobile_left}{$discount_badge_margin_mobile_unit};
            padding-top: {$discount_badge_padding_mobile_top}{$discount_badge_padding_mobile_unit};
            padding-right: {$discount_badge_padding_mobile_right}{$discount_badge_padding_mobile_unit};
            padding-bottom: {$discount_badge_padding_mobile_bottom}{$discount_badge_padding_mobile_unit};
            padding-left: {$discount_badge_padding_mobile_left}{$discount_badge_padding_mobile_unit};
            width: {$discount_badge_width_mobile}{$discount_badge_width_mobile_unit};
            height: {$discount_badge_height_mobile}{$discount_badge_height_mobile_unit};
            border-top-left-radius: {$discount_badge_radius_mobile_top}{$discount_badge_radius_mobile_unit};
            border-top-right-radius: {$discount_badge_radius_mobile_right}{$discount_badge_radius_mobile_unit};
            border-bottom-right-radius: {$discount_badge_radius_mobile_bottom}{$discount_badge_radius_mobile_unit};
            border-bottom-left-radius: {$discount_badge_radius_mobile_left}{$discount_badge_radius_mobile_unit};
        }
    } ";

			return $dynamic_css;
		}
	}

}