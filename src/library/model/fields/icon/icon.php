<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_icon' ) ) {
  class AGSHOPGLUT_icon extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'shopglut' ),
        'remove_title' => esc_html__( 'Remove Icon', 'shopglut' ),
      ) );

      echo wp_kses_post($this->field_before());

      $nonce  = wp_create_nonce( 'agl_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="agl-icon-selects">';
      echo '<div id="agl-icon-preview" class="agl-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></div>';
      echo '<a href="#" class="button button-primary agl-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html($args['button_title']) .'</a>';
      echo '<a href="#" class="button agl-warning-primary agl-icon-remove'. esc_attr( $hidden ) .'">'. esc_html($args['remove_title']) .'</a>';
      echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="agl-icon-value"'. wp_kses_post($this->field_attributes()) .' />';
      echo '</div>';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {
      add_action( 'admin_footer', array( &$this, 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( &$this, 'add_footer_modal_icon' ) );
    }

    public function add_footer_modal_icon() {
    ?>
      <div id="agl-modal-icon" class="agl-modal agl-modal-icon hidden">
        <div class="agl-modal-table">
          <div class="agl-modal-table-cell">
            <div class="agl-modal-overlay"></div>
            <div class="agl-modal-inner">
              <div class="agl-modal-title">
                <?php esc_html_e( 'Add Icon', 'shopglut' ); ?>
                <div class="agl-modal-close agl-icon-close"></div>
              </div>
              <div class="agl-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'shopglut' ); ?>" class="agl-icon-search" />
              </div>
              <div class="agl-modal-content">
                <div class="agl-modal-loading"><div class="agl-loading"></div></div>
                <div class="agl-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}
