<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: position_selector
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_position_selector' ) ) {
	class AGSHOPGLUT_position_selector extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'positions' => array(),
			) );

			echo wp_kses_post( $this->field_before() );

			$default_positions = array(
				'before_title' => array(
					'title' => __('Before Product Title', 'shopglut'),
					'description' => __('Display content above the product title', 'shopglut'),
					'icon' => '↑'
				),
				'after_title' => array(
					'title' => __('After Product Title', 'shopglut'),
					'description' => __('Display content below the product title', 'shopglut'),
					'icon' => '↓'
				),
				'before_price' => array(
					'title' => __('Before Price', 'shopglut'),
					'description' => __('Display content above the price', 'shopglut'),
					'icon' => '↑'
				),
				'after_price' => array(
					'title' => __('After Price', 'shopglut'),
					'description' => __('Display content below the price', 'shopglut'),
					'icon' => '↓'
				),
				'before_add_to_cart' => array(
					'title' => __('Before Add to Cart', 'shopglut'),
					'description' => __('Display content above the add to cart button', 'shopglut'),
					'icon' => '↑'
				),
				'after_add_to_cart' => array(
					'title' => __('After Add to Cart', 'shopglut'),
					'description' => __('Display content below the add to cart button', 'shopglut'),
					'icon' => '↓'
				),
				'before_meta' => array(
					'title' => __('Before Product Meta', 'shopglut'),
					'description' => __('Display content above categories/tags', 'shopglut'),
					'icon' => '↑'
				),
				'after_meta' => array(
					'title' => __('After Product Meta', 'shopglut'),
					'description' => __('Display content below categories/tags', 'shopglut'),
					'icon' => '↓'
				),
			);

			$positions = wp_parse_args( $args['positions'], $default_positions );
			$current_value = $this->value ?? 'after_title';

			?>
			<div class="shopglut-position-selector">
				<style>
				.shopglut-position-selector {
					width: 100%;
				}
				.shopglut-position-options {
					display: grid;
					grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
					gap: 12px;
					margin-top: 10px;
				}
				.shopglut-position-option {
					border: 2px solid #e0e0e0;
					border-radius: 8px;
					padding: 12px;
					cursor: pointer;
					transition: all 0.3s ease;
					background: white;
					position: relative;
					text-align: center;
				}
				.shopglut-position-option:hover {
					border-color: #0073aa;
					box-shadow: 0 2px 8px rgba(0,115,170,0.1);
				}
				.shopglut-position-option.selected {
					border-color: #0073aa;
					background-color: #f7fcff;
				}
				.shopglut-position-option.selected::after {
					content: '✓';
					position: absolute;
					top: 6px;
					right: 6px;
					width: 20px;
					height: 20px;
					background: #0073aa;
					color: white;
					border-radius: 50%;
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 12px;
					font-weight: bold;
					box-shadow: 0 2px 4px rgba(0,115,170,0.3);
					z-index: 1;
				}
				.position-icon {
					font-size: 18px;
					margin-bottom: 5px;
					color: #0073aa;
				}
				.position-title {
					font-weight: 600;
					margin-bottom: 5px;
					color: #333;
					font-size: 13px;
				}
				.position-description {
					font-size: 11px;
					color: #666;
					line-height: 1.3;
				}
				</style>

				<div class="shopglut-position-options">
					<?php foreach ( $positions as $position_key => $position ): ?>
						<div class="shopglut-position-option <?php echo ($current_value === $position_key) ? 'selected' : ''; ?>"
							 data-position="<?php echo esc_attr( $position_key ); ?>">
							<div class="position-icon"><?php echo esc_html( $position['icon'] ); ?></div>
							<div class="position-title"><?php echo esc_html( $position['title'] ); ?></div>
							<div class="position-description"><?php echo esc_html( $position['description'] ); ?></div>
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
					// Handle click on position options
					$('.shopglut-position-selector .shopglut-position-option').on('click', function() {
						var $this = $(this);
						var position = $this.data('position');
						var $container = $this.closest('.shopglut-position-selector');

						// Remove selected class from all options in this container
						$container.find('.shopglut-position-option').removeClass('selected');

						// Add selected class to clicked option
						$this.addClass('selected');

						// Update hidden input value
						var $input = $container.find('input[type="hidden"]');
						if ($input.length) {
							$input.val(position);
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