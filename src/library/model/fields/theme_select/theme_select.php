<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: select
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'AGSHOPGLUT_theme_select' ) ) {
	class AGSHOPGLUT_theme_select extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'chosen'      => false,
					'multiple'    => false,
					'placeholder' => '',
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo esc_attr($this->field_before());

			if ( ! empty( $this->field['options'] ) ) {

				$options          = ( is_array( $this->field['options'] ) ) ? $this->field['options'] : $this->field_data( $this->field['options'] );
				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$chosen_attr      = ( $args['chosen'] ) ? ' class="spf-chosen' . $chosen_rtl . '"' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . $args['placeholder'] . '"' : '';

				if ( ! empty( $options ) ) {
					echo '<select name="' . esc_attr($this->field_name( $multiple_name )) . '"' . esc_attr( $multiple_attr . $chosen_attr . $placeholder_attr) . wp_kses_post($this->field_attributes()) . '>';

					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
							echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_html($args['placeholder']). '</option>';
						}
					}

					foreach ( $options as $option_key => $option ) {
						$pro_only_value = isset( $option['pro_only'] ) ? $option['pro_only'] : '';
						$pro_only       = true == $pro_only_value ? ' disabled' : '';
						$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
						echo '<option' . esc_attr($pro_only) . ' value="' . esc_attr($option_key) . '" ' .esc_attr( $selected ) . '>' . esc_html($option['text']) . '</option>';
						
					}
					echo '</select>';

				} else {

					echo ( ! empty( $this->field['empty_message'] ) ) ? esc_html($this->field['empty_message']) : esc_html__( 'No data provided for this option type.', 'shopglut' );

				}
			}
			echo '<img src="" class="theme_preview" alt="">';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
			echo esc_attr($this->field_after());
		}
	}
}
