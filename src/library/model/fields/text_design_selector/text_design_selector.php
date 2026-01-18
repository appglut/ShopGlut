<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: text_design_selector
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_text_design_selector' ) ) {
	class AGSHOPGLUT_text_design_selector extends AGSHOPGLUTP {

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
				'simple' => array(
					'title' => __('Simple Text', 'shopglut'),
					'description' => __('Plain text display with minimal styling', 'shopglut'),
					'demo' => '<div class="simple-demo">Sample text value</div>'
				),
				'badge' => array(
					'title' => __('Styled Badge', 'shopglut'),
					'description' => __('Eye-catching badge with gradient background', 'shopglut'),
					'demo' => '<div class="badge-demo">Premium Quality</div>'
				),
			);

			$designs = wp_parse_args( $args['designs'], $default_designs );
			$current_value = $this->value ?? 'simple';

			?>
			<div class="shopglut-text-design-selector">
				<style>
				.shopglut-text-design-selector {
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
				.simple-demo {
					padding: 8px 12px;
					border: 1px solid #ddd;
					background: white;
					font-family: inherit;
					font-size: 14px;
				}
				.badge-demo {
					display: inline-block;
					padding: 6px 12px;
					background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
					color: white;
					border-radius: 20px;
					font-weight: 500;
					font-size: 14px;
					box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
					$('.shopglut-text-design-selector .shopglut-design-option').on('click', function() {
						var $this = $(this);
						var design = $this.data('design');
						var $container = $this.closest('.shopglut-text-design-selector');

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