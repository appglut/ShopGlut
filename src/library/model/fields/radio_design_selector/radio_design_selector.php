<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: radio_design_selector
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_radio_design_selector' ) ) {
	class AGSHOPGLUT_radio_design_selector extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'designs' => array(),
			) );

			echo wp_kses_post( $this->field_before() );

			$default_designs = array(
				'vertical' => array(
					'title' => __('Vertical Layout', 'shopglut'),
					'description' => __('Stacked radio buttons vertically', 'shopglut'),
					'demo' => '<div class="radio-vertical">
						<label><input type="radio" name="demo-vertical" checked> Option A</label>
						<label><input type="radio" name="demo-vertical"> Option B</label>
						<label><input type="radio" name="demo-vertical"> Option C</label>
					</div>'
				),
				'horizontal' => array(
					'title' => __('Horizontal Layout', 'shopglut'),
					'description' => __('Display radio buttons inline', 'shopglut'),
					'demo' => '<div class="radio-horizontal">
						<label><input type="radio" name="demo-horizontal" checked> Option A</label>
						<label><input type="radio" name="demo-horizontal"> Option B</label>
						<label><input type="radio" name="demo-horizontal"> Option C</label>
					</div>'
				),
			);

			$designs = wp_parse_args( $args['designs'], $default_designs );
			$current_value = $this->value ?? 'vertical';

			?>
			<div class="shopglut-radio-design-selector">
				<style>
				.shopglut-radio-design-selector {
					width: 100%;
				}
				.shopglut-design-options {
					display: flex;
					gap: 15px;
					flex-wrap: wrap;
					margin-top: 10px;
				}
				.shopglut-design-option {
					border: 2px solid #e0e0e0;
					border-radius: 8px;
					padding: 15px;
					cursor: pointer;
					transition: all 0.3s ease;
					flex: 1;
					min-width: 200px;
					background: white;
					position: relative;
				}
				.shopglut-design-option:hover {
					border-color: #0073aa;
					box-shadow: 0 4px 12px rgba(0,115,170,0.15);
					transform: translateY(-2px);
				}
				.shopglut-design-option.selected {
					border-color: #0073aa;
					background-color: #f7fcff;
				}
				.shopglut-design-option.selected::after {
					content: '✓';
					position: absolute;
					top: 8px;
					right: 8px;
					width: 24px;
					height: 24px;
					background: #0073aa;
					color: white;
					border-radius: 50%;
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 14px;
					font-weight: bold;
					box-shadow: 0 2px 4px rgba(0,115,170,0.3);
					z-index: 1;
				}
				.design-title {
					font-weight: 600;
					margin-bottom: 10px;
					color: #333;
				}
				.design-demo {
					background: #f9f9f9;
					padding: 10px;
					border-radius: 4px;
					margin-bottom: 10px;
					border: 1px solid #e0e0e0;
				}
				.design-description {
					font-size: 12px;
					color: #666;
					line-height: 1.4;
				}
				.radio-vertical {
					display: flex;
					flex-direction: column;
					gap: 8px;
				}
				.radio-vertical label {
					display: flex;
					align-items: center;
					gap: 8px;
					font-size: 14px;
					cursor: pointer;
				}
				.radio-horizontal {
					display: flex;
					gap: 15px;
				}
				.radio-horizontal label {
					display: flex;
					align-items: center;
					gap: 6px;
					font-size: 14px;
					cursor: pointer;
				}
				input[type="radio"] {
					margin: 0;
					cursor: pointer;
				}
				</style>

				<div class="shopglut-design-options">
					<?php foreach ( $designs as $design_key => $design ): ?>
						<div class="shopglut-design-option <?php echo ($current_value === $design_key) ? 'selected' : ''; ?>"
							 data-design="<?php echo esc_attr( $design_key ); ?>">
							<div class="design-title"><?php echo esc_html( $design['title'] ); ?></div>
							<div class="design-demo">
								<?php echo wp_kses_post( $design['demo'] ); ?>
							</div>
							<div class="design-description"><?php echo esc_html( $design['description'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<input type="hidden"
					   name="<?php echo esc_attr( $this->field_name() ); ?>"
					   value="<?php echo esc_attr( $current_value ); ?>"
					   <?php echo wp_kses_post( $this->field_attributes() ); ?>>

				<script>
				// Use jQuery for better compatibility with WordPress admin
				jQuery(document).ready(function($) {
					// Handle click on design options
					$('.shopglut-radio-design-selector .shopglut-design-option').on('click', function() {
						var $this = $(this);
						var design = $this.data('design');
						var $container = $this.closest('.shopglut-radio-design-selector');

						// Remove selected class from all options in this container
						$container.find('.shopglut-design-option').removeClass('selected');

						// Add selected class to clicked option
						$this.addClass('selected');

						// Update hidden input value
						var $input = $container.find('input[type="hidden"]');
						if ($input.length) {
							$input.val(design);
						}
					});
				});
				</script>
			</div>

			<?php

			echo wp_kses_post( $this->field_after() );

		}

	}
}