<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

/**
 *
 * Field: Product Tags
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT_product_tags' ) ) {
  class AGSHOPGLUT_product_tags extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      $args = wp_parse_args( $this->field, array(
        'placeholder' => esc_html__( 'Select tags', 'shopglut' ),
        'chosen'      => true,
        'multiple'    => true,
        'sortable'    => false,
        'ajax'        => false,
      ) );

      $this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

      echo $this->field_before(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method handles escaping

      // Get product tags
      $tags = get_terms( array(
        'taxonomy'   => 'product_tag',
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
        echo '<select name="'. esc_attr( $field_name ) .'" class="agl-hide-select hidden"'. esc_attr( $multiple_attr ) . $field_attr .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $field_attr is escaped in method
        foreach ( $this->value as $option_key ) {
          $term = get_term( $option_key, 'product_tag' );
          if ($term) {
            echo '<option value="'. esc_attr( $option_key ) .'" selected>'. esc_html( $term->name ) .'</option>';
          }
        }
        echo '</select>';
        $field_name = '_pseudo';
        $field_attr = '';
      }

      echo '<select name="'. esc_attr( $field_name ) .'"'. $field_class . esc_attr( $multiple_attr ) . $placeholder_attr . $field_attr .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $field_class and $field_attr escaped in methods

      if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
        if ( ! empty( $args['chosen'] ) ) {
          echo '<option value=""></option>';
        } else {
          echo '<option value="">'. esc_attr( $args['placeholder'] ) .'</option>';
        }
      }

      if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
        foreach ( $tags as $tag ) {
          $selected = ( in_array( $tag->term_id, $this->value ) ) ? ' selected' : '';
          echo '<option value="'. esc_attr( $tag->term_id ) .'" '. esc_attr( $selected ) .'>'. esc_html( $tag->name ) .'</option>';
        }
      } else {
        echo '<option value="">'. esc_html__( 'No tags found', 'shopglut' ) .'</option>';
      }

      echo '</select>';

      echo $this->field_after(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method handles escaping
    }

    public function enqueue() {
      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }
    }
  }
}