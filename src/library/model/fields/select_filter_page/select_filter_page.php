<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_select_filter_page' ) ) {
	class AGSHOPGLUT_select_filter_page extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'placeholder' => '',
				'chosen' => false,
				'multiple' => false,
				'sortable' => false,
				'ajax' => false,
				'settings' => array(),
				'query_args' => array(),
				'active_device' => false,
				'device_type' => array(
					'desktop', 'tablet', 'mobile',
				),
			) );

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				if ( ! empty( $args['ajax'] ) ) {
					$args['settings']['data']['type'] = $args['options'];
					$args['settings']['data']['nonce'] = wp_create_nonce( 'agl_chosen_ajax_nonce' );
					if ( ! empty( $args['query_args'] ) ) {
						$args['settings']['data']['query_args'] = $args['query_args'];
					}
				}

				$chosen_rtl = ( is_rtl() ) ? ' chosen-rtl' : '';
				$multiple_name = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_sortable = ( $args['chosen'] && $args['sortable'] ) ? ' agl-chosen-sortable' : '';
				$chosen_ajax = ( $args['chosen'] && $args['ajax'] ) ? ' agl-chosen-ajax' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_html( $args['placeholder'] ) . '"' : '';
				$field_class = ( $args['chosen'] ) ? ' class="agl-chosen' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
				$field_name = $this->field_name( $multiple_name );
				$field_attr = wp_kses_post( $this->field_attributes() );
				$maybe_options = $this->field['options'];
				$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="' . esc_attr( wp_json_encode( $args['settings'] ) ) . '"' : '';

				if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
					$options = $this->field_wp_query_data_title( $maybe_options, $this->value );
				} else if ( is_string( $maybe_options ) && $maybe_options === 'select_filter_page' ) {

					// Initialize options with static entries
					$options = array(
						'Woo Shop Page' => 'Woo Shop Page',
						//'All Categories' => 'All Categories',
						//'All Tags' => 'All Tags',
					);

					// Fetch all WooCommerce product categories
					// $product_categories = get_terms( array(
					// 	'taxonomy' => 'product_cat',
					// 	'hide_empty' => false,
					// ) );

					// if ( ! is_wp_error( $product_categories ) ) {
					// 	foreach ( $product_categories as $category ) {
					// 		$options[ $category->term_id ] = $category->name;
					// 	}
					// }

					// // Fetch all WooCommerce product tags
					// $product_tags = get_terms( array(
					// 	'taxonomy' => 'product_tag',
					// 	'hide_empty' => false,
					// ) );

					// if ( ! is_wp_error( $product_tags ) ) {
					// 	foreach ( $product_tags as $tag ) {
					// 		// Use tag name directly as both key and value for the option
					// 		$options[ $tag->term_id ] = $tag->name;
					// 	}
					// }

					global $wpdb;

					// Define table name
					//$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
			   // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function
					//$layout_values = $wpdb->get_results( "SELECT * FROM $table_name" );

					//foreach ( $layout_values as $layout ) {
					//	$options[ 'sglayout' . $layout->id ] = $layout->layout_name;
				//	}

				} else if ( is_string( $maybe_options ) ) {
					$options = $this->field_data( $maybe_options, false, $args['query_args'] );
				} else {
					$options = $maybe_options;
				}

				if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {

					if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {

						echo '<select name="' . esc_attr( $field_name ) . '" class="agl-hide-select hidden"' . esc_attr( $multiple_attr ) . esc_attr( $field_attr ) .
							'>';
						foreach ( $this->value as $option_key ) {
							echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_attr( $option_key ) . '</option>';
						}
						echo '</select>';

						$field_name = '_pseudo';
						$field_attr = '';

					}

					$selectAttributes = wp_kses_post( $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr );

					if ( ! empty( $args['active_device'] ) && count( $args['device_type'] ) === 3 ) {

						foreach ( $args['device_type'] as $device_type ) {

							$select_id = $args['id'] . '-select-type-' . $device_type;

							echo '<select class="active-device" id="' . esc_attr( $select_id ) . '" name="' . esc_attr( $this->field_name( '[' . $select_id . ']' ) ) . '" data-depend-id="' . esc_attr($args['id']) . '-' . esc_attr($device_type) . '">';

							if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
								if ( ! empty( $args['chosen'] ) ) {
									echo '<option value=""></option>';
								} else {
									echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
								}
							}

							$this->outputOptions( $options, $this->value, $select_id );

							echo '</select>';
						}

					} else {

						echo '<select name="' . esc_attr( $field_name ) . '"' . wp_kses_post( $selectAttributes ) . '>';
						if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
							if ( ! empty( $args['chosen'] ) ) {
								echo '<option value=""></option>';
							} else {
								echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
							}
						}

						$select_id = $args['id'];

						$this->outputOptions( $options, $this->value, $select_id );

						echo '</select>';

					}

				} else {

					echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'shopglut' );

				}

			}

			echo wp_kses_post( $this->field_after() );

		}

		public function outputOptions( $options, $selectedValues, $select_id ) {
			if ( ! empty( $options ) ) {


				foreach ( $options as $option_key => $option ) {

					if ( is_array( $option ) && ! empty( $option ) ) {

						echo '<optgroup label="' . esc_attr( $option_key ) . '">';

						foreach ( $option as $sub_key => $sub_value ) {
							$selected = ( in_array( $sub_key, $selectedValues ) ) ? ' selected' : '';
							echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $sub_value ) . '</option>';
						}

						echo '</optgroup>';

					} else {

						$selected = ( isset( $selectedValues[ $select_id ] ) && ( $selectedValues[ $select_id ] === $option_key ) && ( array_key_exists( $select_id, $selectedValues ) ) ) || ( ! isset( $selectedValues[ $select_id ] ) && in_array( $option_key, $selectedValues ) ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $option ) . '</option>';
					}

				}
			}
		}

		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}