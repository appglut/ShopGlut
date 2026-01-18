<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: select_comparison_layouts
 * Custom field to select product comparison layouts
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_select_comparison_layouts' ) ) {
	class AGSHOPGLUT_select_comparison_layouts extends AGSHOPGLUTP {

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

			// Get all comparison layouts from database
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation, safe table name
			$comparison_layouts = $wpdb->get_results(
				"SELECT id, layout_name FROM `" . esc_sql($table_name) . "` ORDER BY id DESC",
				ARRAY_A
			);

			$options = array();
			if ( ! empty( $comparison_layouts ) && is_array( $comparison_layouts ) ) {
				foreach ( $comparison_layouts as $layout ) {
					$options[ $layout['id'] ] = $layout['layout_name'];
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
				echo '<option value="" disabled>' . esc_html__( 'No comparison layouts found', 'shopglut' ) . '</option>';
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
