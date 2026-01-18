<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: wp_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_wp_editor' ) ) {
	class AGSHOPGLUT_wp_editor extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'tinymce' => true,
				'quicktags' => true,
				'media_buttons' => true,
				'wpautop' => false,
				'height' => '',
			) );

			$attributes = array(
				'rows' => 10,
				'class' => 'wp-editor-area',
				'autocomplete' => 'off',
			);

			$editor_height = ( ! empty( $args['height'] ) ) ? ' style="height:' . esc_attr( $args['height'] ) . ';"' : '';

			$editor_settings = array(
				'tinymce' => $args['tinymce'],
				'quicktags' => $args['quicktags'],
				'media_buttons' => $args['media_buttons'],
				'wpautop' => $args['wpautop'],
			);

			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );

			echo wp_kses_post( $this->field_before() );

			// If 'pro' is set, disable the editor and show pro version link
			if ( $is_pro ) {
				echo '<div class="agl-wp-editor">';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
				echo '</div>';
			} else {
				// Normal editor rendering
				echo ( agl_wp_editor_api() ) ? '<div class="agl-wp-editor" data-editor-settings="' . esc_attr( wp_json_encode( $editor_settings ) ) . '">' : '';
				echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . wp_kses_post( $this->field_attributes( $attributes ) ) . esc_attr( $editor_height ) . '>' . wp_kses_post( $this->value ) . '</textarea>';
				echo ( agl_wp_editor_api() ) ? '</div>' : '';
			}

			echo wp_kses_post( $this->field_after() );

		}

		public function enqueue() {

			$is_pro = ! empty( $this->field['pro'] ) ? true : false;

			if ( ! $is_pro ) {
				if ( agl_wp_editor_api() && function_exists( 'wp_enqueue_editor' ) ) {

					wp_enqueue_editor();

					$this->setup_wp_editor_settings();

					add_action( 'print_default_editor_scripts', array(&$this, 'setup_wp_editor_media_buttons' ) );

				}
			}

		}

		// Setup wp editor media buttons
		public function setup_wp_editor_media_buttons() {

			$is_pro = ! empty( $this->field['pro'] ) ? true : false;

			if ( ! $is_pro ) {
				ob_start();
				echo '<div class="wp-media-buttons">';
				do_action( 'media_buttons' );
				echo '</div>';
				$media_buttons = ob_get_clean();

				echo '<script type="text/javascript">';
				echo 'var agl_media_buttons = ' . wp_json_encode( $media_buttons ) . ';';
				echo '</script>';
			}

		}

		// Setup wp editor settings
		public function setup_wp_editor_settings() {

			$is_pro = ! empty( $this->field['pro'] ) ? true : false;

			if ( ! $is_pro ) {
				if ( agl_wp_editor_api() && class_exists( '_WP_Editors' ) ) {

					$defaults = apply_filters( 'agl_wp_editor', array(
						'tinymce' => array(
							'wp_skip_init' => true
						),
					) );

					$setup = _WP_Editors::parse_settings( 'agl_wp_editor', $defaults );

					_WP_Editors::editor_settings( 'agl_wp_editor', $setup );

				}
			}

		}

	}
}