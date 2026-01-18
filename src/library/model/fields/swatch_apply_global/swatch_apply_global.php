<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: swatch_apply_global
 *
 * Global application field for product swatches
 * When enabled, applies swatches to all products, shop page, and archive pages
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_swatch_apply_global' ) ) {
	class AGSHOPGLUT_swatch_apply_global extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'text_on'        => __( 'Yes', 'shopglut' ),
				'text_off'       => __( 'No', 'shopglut' ),
				'text_width'     => 80,
				'default_option' => false,
			) );

			echo wp_kses_post( $this->field_before() );

			$active = ( ! empty( $this->value ) ) ? ' swatch-active' : '';
			$checked = ( ! empty( $this->value ) ) ? ' checked' : '';

			echo '<div class="swatch-apply-global-wrapper">';
			echo '<div class="swatch-apply-global-info">';

			// Main description
			echo '<div class="swatch-apply-global-title">' . esc_html__( 'Apply Globally to All Products & Pages', 'shopglut' ) . '</div>';
			echo '<div class="swatch-apply-global-desc">' . esc_html__( 'Enable to apply swatches to all products, shop page, and archive pages globally.', 'shopglut' ) . '</div>';

			echo '</div>'; // .swatch-apply-global-info

			// Switcher
			echo '<div class="swatch-apply-global-switcher">';
			echo '<label class="swatch-apply-global-label' . esc_attr( $active ) . '">';
			echo '<input type="checkbox" name="' . esc_attr( $this->field_name() ) . '" value="1"' . esc_attr( $checked ) . wp_kses_post( $this->field_attributes() ) . '/>';
			echo '<span class="swatch-apply-global-text-on">' . esc_html( $args['text_on'] ) . '</span>';
			echo '<span class="swatch-apply-global-text-off">' . esc_html( $args['text_off'] ) . '</span>';
			echo '</label>';
			echo '</div>'; // .swatch-apply-global-switcher

			// Additional info
			echo '<div class="swatch-apply-global-note">';
			echo '<span class="dashicons dashicons-info"></span>';
			echo '<span>' . esc_html__( 'When enabled, swatches will be applied across your entire store.', 'shopglut' ) . '</span>';
			echo '</div>';

			echo '</div>'; // .swatch-apply-global-wrapper

			echo wp_kses_post( $this->field_after() );

			// Inline styles
			echo '<style>
				.swatch-apply-global-wrapper {
					padding: 15px;
					background: #f9fafb;
					border-radius: 8px;
					border: 1px solid #e5e7eb;
				}
				.swatch-apply-global-info {
					display: flex;
					flex-direction: column;
					margin-bottom: 12px;
				}
				.swatch-apply-global-title {
					font-weight: 600;
					font-size: 14px;
					color: #374151;
					margin-bottom: 4px;
				}
				.swatch-apply-global-desc {
					font-size: 13px;
					color: #6b7280;
					line-height: 1.4;
				}
				.swatch-apply-global-switcher {
					margin-bottom: 10px;
				}
				.swatch-apply-global-label {
					position: relative;
					display: inline-block;
					width: ' . esc_attr( $args['text_width'] ) . 'px;
					height: 32px;
					cursor: pointer;
				}
				.swatch-apply-global-label input {
					position: absolute;
					opacity: 0;
					width: 0;
					height: 0;
				}
				.swatch-apply-global-text-on,
				.swatch-apply-global-text-off {
					position: absolute;
					top: 50%;
					transform: translateY(-50%);
					font-size: 12px;
					font-weight: 500;
					transition: all 0.3s ease;
				}
				.swatch-apply-global-text-on {
					left: 8px;
					color: #10b981;
				}
				.swatch-apply-global-text-off {
					right: 8px;
					color: #6b7280;
				}
				.swatch-apply-global-label.swatch-active .swatch-apply-global-text-on {
					color: #ffffff;
				}
				.swatch-apply-global-label.swatch-active .swatch-apply-global-text-off {
					color: #6b7280;
				}
				.swatch-apply-global-label::before {
					content: "";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-color: #d1d5db;
					border-radius: 16px;
					transition: all 0.3s ease;
				}
				.swatch-apply-global-label::after {
					content: "";
					position: absolute;
					top: 3px;
					left: 3px;
					width: 26px;
					height: 26px;
					background-color: #ffffff;
					border-radius: 50%;
					transition: all 0.3s ease;
					box-shadow: 0 2px 4px rgba(0,0,0,0.1);
				}
				.swatch-apply-global-label.swatch-active::before {
					background-color: #10b981;
				}
				.swatch-apply-global-label.swatch-active::after {
					transform: translateX(' . ( $args['text_width'] - 32 ) . 'px);
				}
				.swatch-apply-global-note {
					display: flex;
					align-items: center;
					gap: 6px;
					font-size: 12px;
					color: #6b7280;
					padding-top: 8px;
					border-top: 1px solid #e5e7eb;
				}
				.swatch-apply-global-note .dashicons {
					font-size: 16px;
					color: #3b82f6;
				}
			</style>';

			// Inline script
			echo '<script>
				jQuery(document).ready(function($) {
					$(".swatch-apply-global-label").on("change", "input", function() {
						$(this).closest(".swatch-apply-global-label").toggleClass("swatch-active", $(this).is(":checked"));
					});
				});
			</script>';
		}

		public function enqueue() {
			// No additional scripts needed
		}
	}
}
