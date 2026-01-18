<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: border
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_border' ) ) {
	class AGSHOPGLUT_border extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'top_icon' => '<i class="fas fa-long-arrow-alt-up"></i>',
				'left_icon' => '<i class="fas fa-long-arrow-alt-left"></i>',
				'bottom_icon' => '<i class="fas fa-long-arrow-alt-down"></i>',
				'right_icon' => '<i class="fas fa-long-arrow-alt-right"></i>',
				'all_icon' => '<i class="fas fa-arrows-alt"></i>',
				'top_placeholder' => esc_html__( 'top', 'shopglut' ),
				'right_placeholder' => esc_html__( 'right', 'shopglut' ),
				'bottom_placeholder' => esc_html__( 'bottom', 'shopglut' ),
				'left_placeholder' => esc_html__( 'left', 'shopglut' ),
				'all_placeholder' => esc_html__( 'all', 'shopglut' ),
				'top' => true,
				'left' => true,
				'bottom' => true,
				'right' => true,
				'all' => false,
				'color' => true,
				'style' => true,
				'unit' => 'px',
				'show_units' => true,
				'units' => array( 'px', '%', 'em' ),
			) );

			// Check if border properties are set in default array and override args
			if ( isset( $this->field['default'] ) && is_array( $this->field['default'] ) ) {
				if ( isset( $this->field['default']['top'] ) ) {
					$args['top'] = $this->field['default']['top'];
				}
				if ( isset( $this->field['default']['left'] ) ) {
					$args['left'] = $this->field['default']['left'];
				}
				if ( isset( $this->field['default']['bottom'] ) ) {
					$args['bottom'] = $this->field['default']['bottom'];
				}
				if ( isset( $this->field['default']['right'] ) ) {
					$args['right'] = $this->field['default']['right'];
				}
			}

			$default_value = array(
				'top' => '',
				'right' => '',
				'bottom' => '',
				'left' => '',
				'color' => '',
				'style' => 'none',
				'all' => '',
			);

			$border_props = array(
				'none' => esc_html__( 'None', 'shopglut' ),
				'solid' => esc_html__( 'Solid', 'shopglut' ),
				'dashed' => esc_html__( 'Dashed', 'shopglut' ),
				'dotted' => esc_html__( 'Dotted', 'shopglut' ),
				'double' => esc_html__( 'Double', 'shopglut' ),
				'inset' => esc_html__( 'Inset', 'shopglut' ),
				'outset' => esc_html__( 'Outset', 'shopglut' ),
				'groove' => esc_html__( 'Groove', 'shopglut' ),
				'ridge' => esc_html__( 'ridge', 'shopglut' ),

			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			$unit = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' agl--is-unit' : '';

			echo esc_attr( $this->field_before() );

			echo '<div class="agl--inputs">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="agl--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="agl--label agl--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" data-depend-id="' . esc_attr( $this->field['id'] . '-' ) . '" value="' . esc_attr( $value['all'] ) . '"' . esc_attr( $placeholder ) . ' class="agl-input-number agl--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="agl--label agl--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( $properties === array( 'right', 'left' ) ) ? array_reverse( $properties ) : $properties;

				if ( ! empty( $args['style'] ) ) {
					echo '<div class="agl--input">';
					echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '" data-depend-id="' . esc_attr( $this->field['id'] . '-border-type' ) . '">';
					foreach ( $border_props as $border_prop_key => $border_prop_value ) {
						$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				}

				echo '<div class="agl-border-input">';
				foreach ( $properties as $property ) {
					$device_property_key = 'border-' . $property;
					$device_property_value = isset( $value[ $device_property_key ] ) ? $value[ $device_property_key ] : '1';
					echo '<div class="agl--input">';
					echo '<input type="number" data-depend-id="' . esc_attr( $this->field['id'] . '-' . $property ) . '" name="' . esc_attr( $this->field_name( '[' . $device_property_key . ']' ) ) . '" value="' . esc_attr( $device_property_value ) . '" placeholder="0" class="agl-space-input-number' . esc_attr( $is_unit ) . '" step="1" min="-50" max="1000" />';
					echo '<span class="agl--label agl--unit">' . esc_attr( $property ) . '</span>';
					echo '</div>';
				}
				$lock_all_key = 'lock-all';
				$lock_all_checked = isset( $value[ $lock_all_key ] ) ? 'checked="checked"' : '';

				// echo '<label id="lock-border-input" class="lock-all dashicons ' . (isset($value[$lock_all_key]) && $value[$lock_all_key] ? 'dashicons-lock locked' : 'dashicons-unlock unlocked') . '">
				//           <input type="checkbox" name="' . esc_attr($this->field_name('[' . $lock_all_key . ']')) . '" ' . esc_attr($lock_all_checked) . ' style="display:none">
				//           </label>';
				echo '</div>';

			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="agl--color">';
				echo '<div class="agl-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" data-depend-id="' . esc_attr( $this->field['id'] . '-color-value' ) . '" value="' . esc_attr( $value['color'] ) . '" class="agl-color"' . esc_attr( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			echo esc_attr( $this->field_after() );

		}

		public function output() {

			$output = '';
			$unit = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// properties
			$top = ( isset( $this->value['top'] ) && $this->value['top'] !== '' ) ? $this->value['top'] : '';
			$right = ( isset( $this->value['right'] ) && $this->value['right'] !== '' ) ? $this->value['right'] : '';
			$bottom = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ? $this->value['bottom'] : '';
			$left = ( isset( $this->value['left'] ) && $this->value['left'] !== '' ) ? $this->value['left'] : '';
			$style = ( isset( $this->value['style'] ) && $this->value['style'] !== '' ) ? $this->value['style'] : '';
			$color = ( isset( $this->value['color'] ) && $this->value['color'] !== '' ) ? $this->value['color'] : '';
			$all = ( isset( $this->value['all'] ) && $this->value['all'] !== '' ) ? $this->value['all'] : '';

			if ( ! empty( $this->field['all'] ) && ( $all !== '' || $color !== '' ) ) {

				$output = $element . '{';
				$output .= ( $all !== '' ) ? 'border-width:' . $all . $unit . $important . ';' : '';
				$output .= ( $color !== '' ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( $style !== '' ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			} else if ( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' || $color !== '' ) {

				$output = $element . '{';
				$output .= ( $top !== '' ) ? 'border-top-width:' . $top . $unit . $important . ';' : '';
				$output .= ( $right !== '' ) ? 'border-right-width:' . $right . $unit . $important . ';' : '';
				$output .= ( $bottom !== '' ) ? 'border-bottom-width:' . $bottom . $unit . $important . ';' : '';
				$output .= ( $left !== '' ) ? 'border-left-width:' . $left . $unit . $important . ';' : '';
				$output .= ( $color !== '' ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( $style !== '' ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}