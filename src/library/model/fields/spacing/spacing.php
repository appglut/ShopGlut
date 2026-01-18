<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 * Field: spacing with pro appearance
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'AGSHOPGLUT_spacing' ) ) {
	class AGSHOPGLUT_spacing extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			// Set default arguments for the spacing field
			$args = wp_parse_args( $this->field, array(
				'top_icon' => '<i class="fas fa-long-arrow-alt-up"></i>',
				'right_icon' => '<i class="fas fa-long-arrow-alt-right"></i>',
				'bottom_icon' => '<i class="fas fa-long-arrow-alt-down"></i>',
				'left_icon' => '<i class="fas fa-long-arrow-alt-left"></i>',
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
				'unit' => true,
				'show_units' => true,
				'all' => false,
				'units' => array( 'px', '%', 'em' )
			) );

			$default_values = array(
				'top' => '',
				'right' => '',
				'bottom' => '',
				'left' => '',
				'all' => '',
				'unit' => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );
			$unit = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' agl--is-unit' : '';

			// Determine if the field is limited to pro
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock in Pro version', 'shopglut' );

			echo esc_attr( $this->field_before() );
			echo '<div class="agl--inputs">';

			if ( $is_pro ) {
				// Pro-locked appearance
				echo '<div class="agl--input agl--pro agl--disabled">';
				echo '<span class="agl--label agl--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>';
				echo '<input type="text" disabled="disabled" class="agl-input-number" placeholder="' . esc_attr( $pro_text ) . '"/>';
				echo '</div>';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
			} else {
				// Standard Spacing Controls
				if ( ! empty( $args['all'] ) ) {
					$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

					echo '<div class="agl--input">';
					echo ( ! empty( $args['all_icon'] ) ) ? '<span class="agl--label agl--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . esc_attr( $placeholder ) . ' class="agl-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( $unit ) ? '<span class="agl--label agl--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
				} else {
					// Individual top, right, bottom, left spacing controls
					foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
						if ( ! empty( $args[ $prop ] ) ) {
							$placeholder = ( ! empty( $args[ $prop . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $prop . '_placeholder' ] ) . '"' : '';

							echo '<div class="agl--input">';
							echo ( ! empty( $args[ $prop . '_icon' ] ) ) ? '<span class="agl--label agl--icon">' . wp_kses_post( $args[ $prop . '_icon' ] ) . '</span>' : '';
							echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $prop . ']' ) ) . '" value="' . esc_attr( $value[ $prop ] ) . '"' . esc_attr( $placeholder ) . ' class="agl-input-number' . esc_attr( $is_unit ) . '" step="any" />';
							echo ( $unit ) ? '<span class="agl--label agl--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
							echo '</div>';
						}
					}
				}

				// Unit Selection
				if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
					echo '<div class="agl--input">';
					echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
					foreach ( $args['units'] as $unit ) {
						$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				}
			}

			echo '</div>';
			echo esc_attr( $this->field_after() );
		}

		public function output() {
			$output = '';
			$element = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$unit = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
			$mode = ( ! empty( $this->field['output_mode'] ) && ! in_array( $this->field['output_mode'], [ 'relative', 'absolute', 'none' ] ) ) ? $this->field['output_mode'] . '-' : '';

			if ( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && $this->value['all'] !== '' ) {
				$output = "{$element} {{$mode}top:{$this->value['all']}{$unit}{$important}; {$mode}right:{$this->value['all']}{$unit}{$important}; {$mode}bottom:{$this->value['all']}{$unit}{$important}; {$mode}left:{$this->value['all']}{$unit}{$important};}";
			} else {
				$styles = [];
				foreach ( [ 'top', 'right', 'bottom', 'left' ] as $side ) {
					if ( isset( $this->value[ $side ] ) && $this->value[ $side ] !== '' ) {
						$styles[] = "{$mode}{$side}:{$this->value[ $side ]}{$unit}{$important}";
					}
				}
				if ( ! empty( $styles ) ) {
					$output = "{$element} { " . implode( '; ', $styles ) . " }";
				}
			}

			$this->parent->output_css .= $output;
			return $output;
		}
	}
}