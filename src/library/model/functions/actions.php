<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'agl_get_icons' ) ) {
  function agl_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'agl_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'shopglut' ) ) );
    }

    ob_start();

    $icon_library = ( apply_filters( 'agl_fa4', false ) ) ? 'fa4' : 'fa5';

    AGSHOPGLUT::include_plugin_file( 'fields/icon/'. $icon_library .'-icons.php' );

    $icon_lists = apply_filters( 'AGSHOPGLUT_icon_add_icons', agl_get_default_icons() );

    if ( ! empty( $icon_lists ) ) {

      foreach ( $icon_lists as $list ) {

        echo ( count( $icon_lists ) >= 2 ) ? '<div class="agl-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }

      }

    } else {

      echo '<div class="agl-error-text">'. esc_html__( 'No data available.', 'shopglut' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }
  add_action( 'wp_ajax_agl-get-icons', 'agl_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'agl_export' ) ) {
  function agl_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'agl_backup_nonce' ) ) {
      die( esc_html__( 'Error: Invalid nonce verification.', 'shopglut' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Invalid key.', 'shopglut' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragla: no-cache');
    header('Expires: 0');

    echo wp_json_encode( get_option( $unique ) );

    die();

  }
  add_action( 'wp_ajax_agl-export', 'agl_export' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'agl_import_ajax' ) ) {
  function agl_import_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    // First, sanitize and validate the input
    $raw_data = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';
    $nonce = isset( $_POST['action_nonce_check'] ) ? sanitize_text_field( wp_unslash( $_POST['action_nonce_check'] ) ) : '';

    // Verify nonce and process data
    if ( wp_verify_nonce( $nonce, 'action_nonce_check' ) && ! empty( $raw_data ) ) {
        $decoded_data = json_decode( trim( $raw_data ), true );
        $data = is_array( $decoded_data ) ? wp_kses_post_deep( $decoded_data ) : array();
    } else {
        $data = array();
    }
    if ( ! wp_verify_nonce( $nonce, 'agl_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'shopglut' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid key.', 'shopglut' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'shopglut' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_agl-import', 'agl_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'agl_reset_ajax' ) ) {
  function agl_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'agl_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'shopglut' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_agl-reset', 'agl_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'agl_chosen_ajax' ) ) {
  function agl_chosen_ajax() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';

        // First, sanitize and validate the input
    $raw_query_args = isset( $_POST['query_args'] ) ? sanitize_text_field( wp_unslash( $_POST['query_args'] ) ) : '';
    $nonce = isset( $_POST['action1_nonce_check'] ) ? sanitize_text_field( wp_unslash( $_POST['action1_nonce_check'] ) ) : '';

    // Verify nonce and process data
    if ( wp_verify_nonce( $nonce, 'action1_nonce_check' ) && ! empty( $raw_query_args ) ) {
        $query = wp_kses_post_deep( $raw_query_args );
    } else {
        $query = array();
    }

    if ( ! wp_verify_nonce( $nonce, 'agl_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'shopglut' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'shopglut' ) ) );
    }

    $capability = apply_filters( 'agl_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'shopglut' ) ) );
    }

    // Success
    $options = AGSHOPGLUTP::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }
  add_action( 'wp_ajax_agl-chosen', 'agl_chosen_ajax' );
}
