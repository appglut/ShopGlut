<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: select_custom_fields
 * Custom field to select product custom fields
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_select_custom_fields' ) ) {
	class AGSHOPGLUT_select_custom_fields extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'placeholder' => '',
				'chosen' => false,
				'multiple' => false,
			) );

			echo esc_attr( $this->field_before() );

			$multiple_name = ( $args['multiple'] ) ? '[]' : '';
			$multiple_attr = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
			$chosen_rtl = ( is_rtl() ) ? ' chosen-rtl' : '';
			$chosen_class = ( $args['chosen'] ) ? ' class="agl-chosen' . esc_attr( $chosen_rtl ) . '"' : '';
			$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
			$field_name = $this->field_name( $multiple_name );
			$field_attr = esc_attr( $this->field_attributes() );

			// Get all custom fields from database
			global $wpdb;
			$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, safe table name
			$custom_fields = $wpdb->get_results(
				"SELECT id, field_name FROM `" . esc_sql($table_name) . "` ORDER BY id DESC",
				ARRAY_A
			);

			$options = array();
			if ( ! empty( $custom_fields ) && is_array( $custom_fields ) ) {
				foreach ( $custom_fields as $field ) {
					$options[ $field['id'] ] = $field['field_name'];
				}
			}

			echo '<select name="' . esc_attr( $field_name ) . '"' . esc_attr( $chosen_class . $multiple_attr . $placeholder_attr . $field_attr ) . '>';

			// Always show default "Select Option" as first option
			echo '<option value="">' . esc_html__( 'Select Option', 'shopglut' ) . '</option>';

			if ( ! empty( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					$selected = ( $this->value == $option_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $option_value ) . '</option>';
				}
			} else {
				echo '<option value="" disabled>' . esc_html__( 'No custom fields found', 'shopglut' ) . '</option>';
			}

			echo '</select>';

			echo esc_attr( $this->field_after() );

		}

		public function enqueue() {
			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}

	}
}