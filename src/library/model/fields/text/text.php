<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_text' ) ) {
	class AGSHOPGLUT_text extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );

			echo esc_attr( $this->field_before() );

			// If 'pro' is set, disable the text input and show pro version text
			if ( $is_pro ) {
				echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" style="margin-right:5px" disabled />';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
			} else {
				// Normal text input
				echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
			}

			echo wp_kses_post($this->field_after());
		}
	}
}