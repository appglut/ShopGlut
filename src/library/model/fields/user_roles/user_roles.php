<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

/**
 *
 * Field: User Roles
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT_user_roles' ) ) {
  class AGSHOPGLUT_user_roles extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      $args = wp_parse_args( $this->field, array(
        'placeholder' => esc_html__( 'Select user roles (optional)', 'shopglut' ),
        'chosen'      => true,
        'multiple'    => true,
        'sortable'    => false,
        'ajax'        => false,
      ) );

      $this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

      echo $this->field_before(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method handles escaping

      // Get all WordPress user roles
      global $wp_roles;
      $all_roles = $wp_roles->get_names();

      // Remove some internal roles if they exist
      $excluded_roles = array('subscriber', 'customer');
      $filtered_roles = array();

      foreach ( $all_roles as $role_key => $role_name ) {
        if ( ! in_array( $role_key, $excluded_roles ) ) {
          $filtered_roles[$role_key] = $role_name;
        }
      }

      $chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
      $multiple_name    = ( $args['multiple'] ) ? '[]' : '';
      $multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
      $chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' agl-chosen-sortable' : '';
      $placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="'. esc_attr( $args['placeholder'] ) .'"' : '';
      $field_class      = ( $args['chosen'] ) ? ' class="agl-chosen'. esc_attr( $chosen_rtl . $chosen_sortable ) .'"' : '';
      $field_name       = $this->field_name( $multiple_name );
      $field_attr       = $this->field_attributes();

      if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {
        echo '<select name="'. esc_attr( $field_name ) .'" class="agl-hide-select hidden"'. esc_attr( $multiple_attr ) . esc_attr( $field_attr ) .'>';
        foreach ( $this->value as $option_key ) {
          echo '<option value="'. esc_attr( $option_key ) .'" selected>'. esc_attr( $filtered_roles[$option_key] ) .'</option>';
        }
        echo '</select>';
        $field_name = '_pseudo';
        $field_attr = '';
      }

      echo '<select name="'. esc_attr( $field_name ) .'"'. esc_attr( $field_class . $multiple_attr . $placeholder_attr . $field_attr ) .'>';

      if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
        if ( ! empty( $args['chosen'] ) ) {
          echo '<option value=""></option>';
        } else {
          echo '<option value="">'. esc_attr( $args['placeholder'] ) .'</option>';
        }
      }

      if ( ! empty( $filtered_roles ) ) {
        foreach ( $filtered_roles as $role_key => $role_name ) {
          $selected = ( in_array( $role_key, $this->value ) ) ? ' selected' : '';
          echo '<option value="'. esc_attr( $role_key ) .'" '. esc_attr( $selected ) .'>'. esc_html( $role_name ) .'</option>';
        }
      } else {
        echo '<option value="">'. esc_html__( 'No user roles found', 'shopglut' ) .'</option>';
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