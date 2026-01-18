<?php


if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_media' ) ) {
	/**
	 *
	 * Field: media
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class AGSHOPGLUT_media extends AGSHOPGLUTP {

		/**
		 * Media field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'url'            => true,
					'preview'        => true,
					'remove_preview' => false,
					'remove_button'  => true,
					'library'        => array(),
					'button_title'   => esc_html__( 'Upload', 'shopglut' ),
					'remove_title'   => esc_html__( 'Remove', 'shopglut' ),
					'preview_size'   => 'thumbnail',
				)
			);

			$default_values = array(
				'url'         => '',
				'id'          => '',
				'width'       => '',
				'height'      => '',
				'thumbnail'   => '',
				'alt'         => '',
				'title'       => '',
				'description' => '',
			);

			// fallback.
			if ( is_numeric( $this->value ) ) {

				$this->value = array(
					'id'        => $this->value,
					'url'       => wp_get_attachment_url( $this->value ),
					'thumbnail' => wp_get_attachment_image_src( $this->value, 'thumbnail', true )[0],
				);

			}

			$this->value = wp_parse_args( $this->value, $default_values );

			$library     = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
			$library     = ( ! empty( $library ) ) ? implode( ',', $library ) : '';
			$preview_src = ( 'thumbnail' !== $args['preview_size'] ) ? $this->value['url'] : $this->value['thumbnail'];
			$hidden_url  = ( empty( $args['url'] ) ) ? ' hidden' : '';
			$hidden_auto = ( empty( $this->value['url'] ) ) ? ' hidden' : '';
			$placeholder = ( empty( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_html__( 'No media selected', 'shopglut' ) . '"' : '';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

			if ( ! empty( $args['preview'] ) ) {
				echo '<div class="agl--preview' . esc_attr( $hidden_auto ) . '">';
				echo '<div class="agl-image-preview">';
				echo ( $args['remove_preview'] ) ? '<a href="#" class="agl--remove fa fa-times"></a>' : '';
				echo '<img src="' . esc_url( $preview_src ) . '" class="agl--src" /></div>';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
				echo '</div>';
			}

			echo '<div class="agl--placeholder">';
			echo '<input type="text" name="' . esc_attr( $this->field_name( '[url]' ) ) . '" value="' . esc_attr( $this->value['url'] ) . '" class="agl--url' . esc_attr( $hidden_url ) . '" readonly="readonly"' . $this->field_attributes() . $placeholder . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<a href="#" class="button button-primary agl--button" data-library="' . esc_attr( $library ) . '" data-preview-size="' . esc_attr( $args['preview_size'] ) . '">' . wp_kses_post( $args['button_title'] ) . '</a>';
			echo ( $args['remove_button'] ) ? '<a href="#" class="button button-secondary agl-warning-primary agl--remove' . esc_attr( $hidden_auto ) . '">' . wp_kses_post( $args['remove_title'] ) . '</a>' : '';
			echo '</div>';

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[id]' ) ) . '" value="' . esc_attr( $this->value['id'] ) . '" class="agl--id"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[width]' ) ) . '" value="' . esc_attr( $this->value['width'] ) . '" class="agl--width"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[height]' ) ) . '" value="' . esc_attr( $this->value['height'] ) . '" class="agl--height"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[thumbnail]' ) ) . '" value="' . esc_attr( $this->value['thumbnail'] ) . '" class="agl--thumbnail"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[alt]' ) ) . '" value="' . esc_attr( $this->value['alt'] ) . '" class="agl--alt"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[title]' ) ) . '" value="' . esc_attr( $this->value['title'] ) . '" class="agl--title"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[description]' ) ) . '" value="' . esc_attr( $this->value['description'] ) . '" class="agl--description"/>';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}

	}
}
