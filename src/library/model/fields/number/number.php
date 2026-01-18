<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_number' ) ) {
	class AGSHOPGLUT_number extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'unit' => '',
			) );

			echo wp_kses_post( $this->field_before() );
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( ' Unlock the Pro version', 'shopglut' );

			if ( $is_pro ) {

				echo '<div class="agl--wrap agl--pro agl--disabled">';
				echo '<input type="number" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes( array( 'class' => 'agl-input-number' ) ) ) . ' step="any" disabled />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="agl--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" style="margin-left:5px;" target="_blank" class="agl--pro-link"> ' . esc_html( $pro_text ) . '</a>';
			} else {
				echo '<div class="agl--wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes( array( 'class' => 'agl-input-number' ) ) ) . ' step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="agl--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
			}
			echo wp_kses_post( $this->field_after() );

		}

		public function output() {

			$output = '';
			$elements = ( is_array( $this->field['output'] ) ) ? $this->field['output'] : array_filter( (array) $this->field['output'] );
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'width';
			$unit = ( ! empty( $this->field['unit'] ) ) ? $this->field['unit'] : 'px';

			if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
				foreach ( $elements as $key_property => $element ) {
					if ( is_numeric( $key_property ) ) {
						if ( $mode ) {
							$output = implode( ',', $elements ) . '{' . $mode . ':' . $this->value . $unit . $important . ';}';
						}
						break;
					} else {
						$output .= $element . '{' . $key_property . ':' . $this->value . $unit . $important . '}';
					}
				}
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
