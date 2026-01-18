<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: select_badge_layouts
 * Custom field to select product badge layouts
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_select_badge_layouts' ) ) {
	class AGSHOPGLUT_select_badge_layouts extends AGSHOPGLUTP {

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

			// Get all badge layouts from database
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';
			$escaped_table = esc_sql($table_name);

			$badge_layouts = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
				sprintf("SELECT id, layout_name FROM `%s` ORDER BY id DESC", $escaped_table), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.MissingReplacements -- Using sprintf with escaped table name, no additional parameters needed
				ARRAY_A
			);

			$options = array();
			if ( ! empty( $badge_layouts ) && is_array( $badge_layouts ) ) {
				foreach ( $badge_layouts as $layout ) {
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
				echo '<option value="" disabled>' . esc_html__( 'No badge layouts found', 'shopglut' ) . '</option>';
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
