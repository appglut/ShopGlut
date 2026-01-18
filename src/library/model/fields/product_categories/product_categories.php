<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

/**
 *
 * Field: Product Categories
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT_product_categories' ) ) {
  class AGSHOPGLUT_product_categories extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      $args = wp_parse_args( $this->field, array(
        'placeholder' => esc_html__( 'Select categories', 'shopglut' ),
        'chosen'      => true,
        'multiple'    => true,
        'sortable'    => false,
        'ajax'        => false,
      ) );

      $this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

      echo wp_kses_post($this->field_before()); // HTML output escaped in the method

      // Get product categories
      $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC'
      ) );

      $chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
      $multiple_name    = ( $args['multiple'] ) ? '[]' : '';
      $multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
      $chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' agl-chosen-sortable' : '';
      $placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="'. esc_attr( $args['placeholder'] ) .'"' : '';
      $field_class      = ( $args['chosen'] ) ? ' class="agl-chosen'. esc_attr( $chosen_rtl . $chosen_sortable ) .'"' : '';
      $field_name       = $this->field_name( $multiple_name );
      $field_attr       = $this->field_attributes();

      if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {
        echo '<select name="'. esc_attr( $field_name ) .'" class="agl-hide-select hidden"'. esc_attr( $multiple_attr ) . wp_kses_post($field_attr) .'>';
        foreach ( $this->value as $option_key ) {
          $term = get_term( $option_key, 'product_cat' );
          if ($term) {
            echo '<option value="'. esc_attr( $option_key ) .'" selected>'. esc_html( $term->name ) .'</option>';
          }
        }
        echo '</select>';
        $field_name = '_pseudo';
        $field_attr = '';
      }

      echo '<select name="'. esc_attr( $field_name ) .'"'. wp_kses_post($field_class) . esc_attr( $multiple_attr ) . wp_kses_post($placeholder_attr) . wp_kses_post($field_attr) .'>';

      if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
        if ( ! empty( $args['chosen'] ) ) {
          echo '<option value=""></option>';
        } else {
          echo '<option value="">'. esc_attr( $args['placeholder'] ) .'</option>';
        }
      }

      if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        foreach ( $categories as $category ) {
          $selected = ( in_array( $category->term_id, $this->value ) ) ? ' selected' : '';
          echo '<option value="'. esc_attr( $category->term_id ) .'" '. esc_attr( $selected ) .'>'. esc_html( $category->name ) .'</option>';
        }
      } else {
        echo '<option value="">'. esc_html__( 'No categories found', 'shopglut' ) .'</option>';
      }

      echo '</select>';

      echo wp_kses_post($this->field_after()); // HTML output escaped in the method
    }

    public function enqueue() {
      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }
    }
  }
}