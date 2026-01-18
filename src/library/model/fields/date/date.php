<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

/**
 *
 * Field: Date
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT_date' ) ) {
  class AGSHOPGLUT_date extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'placeholder' => '',
        'format'      => 'Y-m-d',
      ));

      $this->value = ( ! empty( $this->value ) ) ? $this->value : '';

      echo wp_kses_post($this->field_before()); // HTML output escaped in the method

      echo '<input type="date" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. wp_kses_post($this->field_attributes()) .'>'; // Attributes escaped in method

      echo wp_kses_post($this->field_after()); // HTML output escaped in the method

    }

  }
}