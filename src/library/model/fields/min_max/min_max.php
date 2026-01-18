<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: number with min and max
 *
 * @since 1.0.0
 * @version 1.1.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_min_max' ) ) {
	class AGSHOPGLUT_min_max extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'unit' => '',
				'min_value' => '',
				'max_value' => '',
			) );

			$min_value = isset( $this->value['min'] ) ? $this->value['min'] : $args['min_value'];
			$max_value = isset( $this->value['max'] ) ? $this->value['max'] : $args['max_value'];

			echo esc_attr( $this->field_before() );
			echo '<div class="agl--min_max">';

			// Min Input
			echo '<label for="min_value">' . esc_html__( 'Min:', 'shopglut' ) . '</label>';
			echo '<input type="number" name="' . esc_attr( $this->field_name( '[min]' ) ) . '" value="' . esc_attr( $min_value ) . '"';
			echo wp_kses_post( $this->field_attributes( array( ' class' => 'agl-input-number' ) ) ) . ' step="any" />';

			// Max Input
			echo '<label for="max_value">' . esc_html__( 'Max:', 'shopglut' ) . '</label>';
			echo '<input type="number" name="' . esc_attr( $this->field_name( '[max]' ) ) . '" value="' . esc_attr( $max_value ) . '"';
			echo wp_kses_post( $this->field_attributes( array( ' class' => 'agl-input-number' ) ) ) . ' step="any" />';

			// Unit display
			echo ( ! empty( $args['unit'] ) ) ? '<span class="agl--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';

			echo '</div>';
			echo esc_attr( $this->field_after() );
		}

		public function output() {

			$output = '';
			$elements = ( is_array( $this->field['output'] ) ) ? $this->field['output'] : array_filter( (array) $this->field['output'] );
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$unit = ( ! empty( $this->field['unit'] ) ) ? $this->field['unit'] : 'px';

			$min_value = isset( $this->value['min'] ) ? $this->value['min'] : '';
			$max_value = isset( $this->value['max'] ) ? $this->value['max'] : '';

			// Apply both min and max values if available
			if ( ! empty( $elements ) && ( $min_value !== '' || $max_value !== '' ) ) {
				foreach ( $elements as $key_property => $element ) {
					if ( is_numeric( $key_property ) ) {
						if ( ! empty( $min_value ) ) {
							$output .= implode( ',', $elements ) . '{min-width:' . $min_value . $unit . $important . ';}';
						}
						if ( ! empty( $max_value ) ) {
							$output .= implode( ',', $elements ) . '{max-width:' . $max_value . $unit . $important . ';}';
						}
						break;
					} else {
						if ( ! empty( $min_value ) ) {
							$output .= $element . '{' . $key_property . '-min:' . $min_value . $unit . $important . '}';
						}
						if ( ! empty( $max_value ) ) {
							$output .= $element . '{' . $key_property . '-max:' . $max_value . $unit . $important . '}';
						}
					}
				}
			}

			$this->parent->output_css .= $output;

			return $output;
		}
	}
}