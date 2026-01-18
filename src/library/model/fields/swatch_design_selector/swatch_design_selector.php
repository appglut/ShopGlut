<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: swatch_design_selector
 *
 * Custom field for selecting swatch design templates with HTML previews
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_swatch_design_selector' ) ) {
	class AGSHOPGLUT_swatch_design_selector extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'designs' => array(),
			) );

			echo wp_kses_post( $this->field_before() );

			$current_value = $this->value ?? 'dropdown';

			// All 30 designs with unique HTML previews
			$all_designs = $this->get_all_designs();

			?>
			<div class="shopglut-swatch-design-selector">
				<style>
				.shopglut-swatch-design-selector {
					width: 100%;
				}
				.shopglut-swatch-design-options {
					display: grid;
					grid-template-columns: repeat(2, 1fr);
					gap: 15px;
					margin-top: 15px;
				}
				@media (max-width: 768px) {
					.shopglut-swatch-design-options {
						grid-template-columns: 1fr;
					}
				}
				.shopglut-swatch-design-option {
					border: 2px solid #e0e0e0;
					border-radius: 8px;
					padding: 12px;
					cursor: pointer;
					transition: all 0.2s ease;
					background: white;
					position: relative;
					min-height: 130px;
				}
				.shopglut-swatch-design-option:hover {
					border-color: #0073aa;
					box-shadow: 0 2px 8px rgba(0,115,170,0.15);
					transform: translateY(-1px);
				}
				.shopglut-swatch-design-option.selected {
					border-color: #0073aa;
					background-color: #f0f7ff;
				}
				.shopglut-swatch-design-option.selected::after {
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
				}
				.design-title {
					font-weight: 600;
					font-size: 13px;
					margin-bottom: 8px;
					color: #333;
					display: flex;
					align-items: center;
					gap: 6px;
				}
				.design-type-badge {
					font-size: 9px;
					padding: 2px 6px;
					border-radius: 10px;
					text-transform: uppercase;
					font-weight: 600;
					letter-spacing: 0.5px;
				}
				.design-type-badge.free {
					background: #10b981;
					color: white;
				}
				.design-type-badge.pro {
					background: #f59e0b;
					color: #333;
				}
				.design-preview {
					background: #fafafa;
					padding: 10px;
					border-radius: 6px;
					margin-bottom: 8px;
					border: 1px solid #eee;
					display: flex;
					gap: 5px;
					justify-content: center;
					flex-wrap: wrap;
				}
				.design-description {
					font-size: 10px;
					color: #666;
					line-height: 1.3;
				}

				/* Preview swatch base styles */
				.preview-swatch {
					display: inline-flex;
					align-items: center;
					justify-content: center;
					text-align: center;
					white-space: nowrap;
					cursor: pointer;
					font-size: 10px;
					font-weight: 500;
					transition: all 0.2s ease;
				}

				/* Dual-text preview styles */
				.preview-swatch-dual {
					flex-direction: column;
					gap: 4px;
				}
				.preview-swatch-dual .preview-label {
					font-size: 12px;
					font-weight: 700;
					line-height: 1;
				}
				.preview-swatch-dual .preview-detail {
					font-size: 8px;
					opacity: 0.7;
					line-height: 1;
				}

				/* 1. Classic - Sharp rectangular button */
				.preview-classic .preview-swatch {
					border: 2px solid #000;
					border-radius: 0;
					padding: 6px 10px;
					background: #fff;
					color: #000;
					text-transform: uppercase;
					letter-spacing: 0.5px;
				}
				.preview-classic .preview-swatch.selected {
					background: #000;
					color: #fff;
				}

				/* 2. Modern - Circle design */
				.preview-modern .preview-swatch {
					border: 0;
					border-radius: 50%;
					width: 32px;
					height: 32px;
					padding: 0;
					background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
					color: #fff;
					font-weight: 600;
				}
				.preview-modern .preview-swatch.selected {
					background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
					transform: scale(1.1);
				}

				/* 3. Minimal - Inline text with underline */
				.preview-minimal .preview-swatch {
					border: 0;
					border-radius: 0;
					padding: 6px 8px;
					background: transparent;
					color: #666;
				}
				.preview-minimal .preview-swatch.selected {
					color: #000;
					font-weight: 600;
				}

				/* 4. Outlined - Dashed border */
				.preview-outlined .preview-swatch {
					border: 2px dashed #999;
					border-radius: 4px;
					padding: 5px 8px;
					background: transparent;
					color: #666;
				}
				.preview-outlined .preview-swatch.selected {
					border-style: solid;
					background: #000;
					color: #fff;
				}

				/* 5. Pill - Extra wide pill */
				.preview-pill .preview-swatch {
					border: 0;
					border-radius: 100px;
					padding: 6px 16px;
					background: #f0f0f0;
					color: #333;
				}
				.preview-pill .preview-swatch.selected {
					background: #333;
					color: #fff;
				}

				/* 6. Underlined - Thick underline */
				.preview-underlined .preview-swatch {
					border: 0;
					border-bottom: 3px solid #ddd;
					border-radius: 0;
					padding: 6px 6px;
					background: transparent;
					color: #666;
				}
				.preview-underlined .preview-swatch.selected {
					border-bottom-color: #000;
					color: #000;
				}

				/* 7. Dual Tone - Split color */
				.preview-dual_tone .preview-swatch {
					border: 0;
					border-radius: 6px;
					padding: 6px 10px;
					background: linear-gradient(to bottom, #ff6b6b 50%, #4ecdc4 50%);
					color: #fff;
					font-weight: 600;
				}
				.preview-dual_tone .preview-swatch.selected {
					background: linear-gradient(to bottom, #000 50%, #333 50%);
					transform: scale(1.05);
				}

				/* 8. Stacked - Dual-text card */
				.preview-stacked .preview-swatch {
					border: 0;
					border-radius: 8px;
					padding: 8px 12px;
					background: #fff;
					color: #333;
					box-shadow: 0 2px 6px rgba(0,0,0,0.1);
				}
				.preview-stacked .preview-swatch.selected {
					background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
					color: #fff;
				}

				/* 9. Gradient Card - Animated gradient */
				.preview-gradient_card .preview-swatch {
					border: 0;
					border-radius: 10px;
					padding: 10px 14px;
					background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
					color: #fff;
					font-weight: 700;
				}
				.preview-gradient_card .preview-swatch.selected {
					background: #000;
				}

				/* 10. Neon Glow - Dark cyberpunk */
				.preview-neon_glow .preview-swatch {
					border: 1px solid #0ff;
					border-radius: 6px;
					padding: 8px 12px;
					background: #000;
					color: #0ff;
					box-shadow: 0 0 5px rgba(0,255,255,0.4);
				}
				.preview-neon_glow .preview-swatch.selected {
					background: #0ff;
					color: #000;
				}

				/* 11. 3D Press - Deep button */
				.preview-3d_press .preview-swatch {
					border: 0;
					border-radius: 8px;
					padding: 8px 12px;
					background: #4a90d9;
					color: #fff;
					box-shadow: 0 4px 0 #2c5c96;
				}
				.preview-3d_press .preview-swatch.selected {
					background: #2c5c96;
					transform: translateY(2px);
					box-shadow: 0 2px 0 #1a3c61;
				}

				/* 12. Glass Card - Frosted glass */
				.preview-glass_card .preview-swatch {
					border: 1px solid rgba(255,255,255,0.3);
					border-radius: 12px;
					padding: 10px 14px;
					background: rgba(255,255,255,0.15);
					color: #333;
				}
				.preview-glass_card .preview-swatch.selected {
					background: rgba(255,255,255,0.9);
					color: #000;
				}

				/* 13. Brutalist Box - Bold and raw */
				.preview-brutalist_box .preview-swatch {
					border: 3px solid #000;
					border-radius: 0;
					padding: 8px 12px;
					background: #fff;
					color: #000;
					box-shadow: 3px 3px 0 #000;
					font-weight: 700;
				}
				.preview-brutalist_box .preview-swatch.selected {
					background: #000;
					color: #ff0;
				}

				/* 14. Elegant Lift - Layered shadows */
				.preview-elegant_lift .preview-swatch {
					border: 0;
					border-radius: 10px;
					padding: 10px 14px;
					background: #fff;
					color: #333;
					box-shadow: 0 4px 12px rgba(0,0,0,0.1);
				}
				.preview-elegant_lift .preview-swatch.selected {
					background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
					color: #fff;
				}

				/* 15. Soft Bubble - Organic bubble */
				.preview-soft_bubble .preview-swatch {
					border: 0;
					border-radius: 20px;
					padding: 10px 16px;
					background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
					color: #5d4e37;
				}
				.preview-soft_bubble .preview-swatch.selected {
					background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
				}

				/* 16. Neumorphic - Soft shadows */
				.preview-neumorphic .preview-swatch {
					border: 0;
					border-radius: 16px;
					padding: 10px 16px;
					background: #e0e5ec;
					color: #5a6a7a;
					box-shadow: 4px 4px 8px rgba(163,177,198,0.4), -4px -4px 8px rgba(255,255,255,0.8);
				}
				.preview-neumorphic .preview-swatch.selected {
					background: #667eea;
					color: #fff;
				}

				/* 17. Cyber Split - Cyberpunk */
				.preview-cyber_split .preview-swatch {
					border: 0;
					border-radius: 8px;
					padding: 8px 12px;
					background: linear-gradient(135deg, #0a0a0a 50%, #1a1a1a 50%);
					color: #0ff;
					font-weight: 700;
				}
				.preview-cyber_split .preview-swatch.selected {
					background: linear-gradient(135deg, #f0f 50%, #ff0 50%);
					color: #000;
				}

				/* 18. Floating Pill - Floating effect */
				.preview-floating_pill .preview-swatch {
					border: 0;
					border-radius: 100px;
					padding: 10px 18px;
					background: #fff;
					color: #ec4899;
					box-shadow: 0 4px 12px rgba(236,72,153,0.15);
				}
				.preview-floating_pill .preview-swatch.selected {
					background: #ec4899;
					color: #fff;
				}
				</style>

				<div class="shopglut-swatch-design-options">
					<?php foreach ( $all_designs as $design_key => $design ): ?>
						<div class="shopglut-swatch-design-option <?php echo ( $current_value === $design_key ) ? 'selected' : ''; ?>"
							 data-design="<?php echo esc_attr( $design_key ); ?>">
							<div class="design-title">
								<?php echo esc_html( $design['title'] ); ?>
								<span class="design-type-badge <?php echo esc_attr( $design['type'] ); ?>">
									<?php echo esc_html( $design['type'] ); ?>
								</span>
							</div>
							<div class="design-preview preview-<?php echo esc_attr( $design_key ); ?>">
								<?php echo wp_kses_post( $design['preview'] ); ?>
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
				jQuery(document).ready(function($) {
					$('.shopglut-swatch-design-selector .shopglut-swatch-design-option').on('click', function() {
						var $this = $(this);
						var design = $this.data('design');
						var $container = $this.closest('.shopglut-swatch-design-selector');

						$container.find('.shopglut-swatch-design-option').removeClass('selected');
						$this.addClass('selected');

						var $input = $container.find('input[type="hidden"]');
						if ($input.length) {
							$input.val(design).trigger('change');
						}
					});
				});
				</script>
			</div>

			<?php

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Get all 30 designs with unique HTML previews
		 */
		private function get_all_designs() {
			return [
				// FREE DESIGNS (1-8)
				'dropdown' => [
					'title' => __( 'Dropdown', 'shopglut' ),
					'description' => __( 'Classic dropdown select', 'shopglut' ),
					'type' => 'free',
					'preview' => '<select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"><option>Select Size</option><option selected>Small</option><option>Medium</option><option>Large</option></select>',
				],
				'button_grid' => [
					'title' => __( 'Button Grid', 'shopglut' ),
					'description' => __( 'Grid of clickable buttons', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;"><span style="padding:8px;border:1px solid #ddd;text-align:center;border-radius:4px;">XS</span><span style="padding:8px;background:#667eea;color:white;text-align:center;border-radius:4px;">S</span><span style="padding:8px;border:1px solid #ddd;text-align:center;border-radius:4px;">M</span></div>',
				],
				'color_swatches' => [
					'title' => __( 'Color Swatches', 'shopglut' ),
					'description' => __( 'Circular color pickers', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:flex;gap:8px;"><span style="width:30px;height:30px;border-radius:50%;background:#ff6b6b;border:2px solid #333;box-shadow:0 0 0 2px white;"></span><span style="width:30px;height:30px;border-radius:50%;background:#4ecdc4;"></span><span style="width:30px;height:30px;border-radius:50%;background:#45b7d1;"></span></div>',
				],
				'radio_cards' => [
					'title' => __( 'Radio Cards', 'shopglut' ),
					'description' => __( 'Card-style radio buttons', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:flex;flex-direction:column;gap:5px;"><span style="padding:8px;border:1px solid #ddd;border-radius:4px;background:#667eea;color:white;">● Cotton</span><span style="padding:8px;border:1px solid #ddd;border-radius:4px;">○ Polyester</span></div>',
				],
				'image_thumbnails' => [
					'title' => __( 'Image Thumbnails', 'shopglut' ),
					'description' => __( 'Grid of image thumbnails', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:5px;"><span style="aspect-ratio:1;border:2px solid #333;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;">A</span><span style="aspect-ratio:1;border:1px solid #ddd;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;">B</span><span style="aspect-ratio:1;border:1px solid #ddd;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;">C</span></div>',
				],
				'pill_buttons' => [
					'title' => __( 'Pill Buttons', 'shopglut' ),
					'description' => __( 'Rounded pill-shaped buttons', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:flex;gap:5px;"><span style="padding:6px 12px;border:1px solid #ddd;border-radius:15px;font-size:12px;">Small</span><span style="padding:6px 12px;background:#667eea;color:white;border-radius:15px;font-size:12px;">Medium</span><span style="padding:6px 12px;border:1px solid #ddd;border-radius:15px;font-size:12px;">Large</span></div>',
				],
				'box_selection' => [
					'title' => __( 'Box Selection', 'shopglut' ),
					'description' => __( 'Boxed items with icons', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:5px;"><span style="border:1px solid #ddd;border-radius:6px;padding:6px;text-align:center;background:#f8f9ff;border-color:#667eea;"><span style="font-size:18px;">📦</span><br><span style="font-size:10px;font-weight:600;">Standard</span></span><span style="border:1px solid #ddd;border-radius:6px;padding:6px;text-align:center;"><span style="font-size:18px;">🚀</span><br><span style="font-size:10px;font-weight:600;">Express</span></span></div>',
				],
				'toggle_switches' => [
					'title' => __( 'Toggle Switches', 'shopglut' ),
					'description' => __( 'On/off toggle switches', 'shopglut' ),
					'type' => 'free',
					'preview' => '<div style="display:flex;flex-direction:column;gap:8px;"><div style="display:flex;justify-content:space-between;align-items:center;"><span style="font-size:11px;">Gift Wrap</span><span style="width:30px;height:15px;background:#667eea;border-radius:10px;position:relative;"><span style="width:11px;height:11px;background:white;border-radius:50%;position:absolute;top:2px;right:2px;"></span></span></div><div style="display:flex;justify-content:space-between;align-items:center;"><span style="font-size:11px;">Express</span><span style="width:30px;height:15px;background:#ddd;border-radius:10px;position:relative;"><span style="width:11px;height:11px;background:white;border-radius:50%;position:absolute;top:2px;left:2px;"></span></span></div></div>',
				],

				// PRO DESIGNS (9-30)
				'checkbox_grid' => [
					'title' => __( 'Checkbox Grid', 'shopglut' ),
					'description' => __( 'Grid with checkboxes', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:5px;"><span style="display:flex;align-items:center;gap:5px;padding:6px;border:1px solid #ddd;border-radius:6px;background:#667eea;color:white;"><span style="width:14px;height:14px;background:white;display:flex;align-items:center;justify-content:center;font-size:10px;">✓</span><span style="font-size:11px;">Shipping</span></span><span style="display:flex;align-items:center;gap:5px;padding:6px;border:1px solid #ddd;border-radius:6px;"><span style="width:14px;height:14px;border:1px solid #ddd;"></span><span style="font-size:11px;">Gift Wrap</span></span></div>',
				],
				'star_rating' => [
					'title' => __( 'Star Rating', 'shopglut' ),
					'description' => __( 'Star-based selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;flex-direction:column;gap:5px;"><span style="padding:6px;border:1px solid #ddd;border-radius:6px;display:flex;align-items:center;gap:5px;"><span style="color:#ffc107;font-size:12px;">★★★★★</span><span style="font-size:11px;">Premium</span></span><span style="padding:6px;border:1px solid #667eea;border-radius:6px;background:#f8f9ff;display:flex;align-items:center;gap:5px;"><span style="color:#ffc107;font-size:12px;">★★★★☆</span><span style="font-size:11px;">Standard</span></span></div>',
				],
				'icon_buttons' => [
					'title' => __( 'Icon Buttons', 'shopglut' ),
					'description' => __( 'Buttons with icons', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;"><span style="padding:8px;border:1px solid #ddd;border-radius:6px;text-align:center;background:#667eea;color:white;"><span style="font-size:16px;">🚚</span><br><span style="font-size:10px;">Standard</span></span><span style="padding:8px;border:1px solid #ddd;border-radius:6px;text-align:center;"><span style="font-size:16px;">✈️</span><br><span style="font-size:10px;">Express</span></span></div>',
				],
				'bordered_list' => [
					'title' => __( 'Bordered List', 'shopglut' ),
					'description' => __( 'List with borders', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="border:1px solid #ddd;border-radius:6px;overflow:hidden;"><div style="padding:8px;border-bottom:1px solid #ddd;display:flex;justify-content:space-between;font-size:11px;"><span>Monthly</span><span style="font-weight:600;">$9.99</span></div><div style="padding:8px;background:#667eea;color:white;display:flex;justify-content:space-between;font-size:11px;"><span>Yearly</span><span style="font-weight:600;">$99.99</span></div></div>',
				],
				'badge_selector' => [
					'title' => __( 'Badge Selector', 'shopglut' ),
					'description' => __( 'Badge-style selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;gap:5px;"><span style="padding:5px 10px;border:1px solid #ddd;border-radius:12px;font-size:11px;position:relative;background:#667eea;color:white;"><span style="position:absolute;top:-6px;right:-6px;background:#28a745;color:white;width:14px;height:14px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;">✓</span>New</span><span style="padding:5px 10px;border:1px solid #ddd;border-radius:12px;font-size:11px;">Sale</span></div>',
				],
				'tag_style' => [
					'title' => __( 'Tag Style', 'shopglut' ),
					'description' => __( 'Tag-like selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;gap:5px;"><span style="padding:4px 10px;background:#667eea;color:white;border-radius:4px;font-size:11px;position:relative;padding-right:20px;">Electronics<span style="position:absolute;right:5px;top:50%;transform:translateY(-50%);">×</span></span><span style="padding:4px 10px;background:#f0f0f0;border-radius:4px;font-size:11px;">Books</span></div>',
				],
				'table_selection' => [
					'title' => __( 'Table Selection', 'shopglut' ),
					'description' => __( 'Table-style selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="border:1px solid #ddd;border-radius:6px;overflow:hidden;"><div style="display:grid;grid-template-columns:2fr 1fr 1fr;padding:6px;background:#f8f9ff;font-size:10px;font-weight:600;border-bottom:1px solid #ddd;"><span>Plan</span><span>Users</span><span>Price</span></div><div style="display:grid;grid-template-columns:2fr 1fr 1fr;padding:6px;font-size:10px;border-bottom:1px solid #ddd;"><span>Basic</span><span>1-5</span><span>$19</span></div><div style="display:grid;grid-template-columns:2fr 1fr 1fr;padding:6px;font-size:10px;background:#667eea;color:white;"><span>Pro</span><span>5-20</span><span>$49</span></div></div>',
				],
				'horizontal_tabs' => [
					'title' => __( 'Horizontal Tabs', 'shopglut' ),
					'description' => __( 'Tab-style selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div><div style="display:flex;border-bottom:1px solid #ddd;"><span style="flex:1;padding:6px;text-align:center;font-size:10px;border-bottom:2px solid transparent;margin-bottom:-1px;">Electronics</span><span style="flex:1;padding:6px;text-align:center;font-size:10px;border-bottom:2px solid #667eea;color:#667eea;">Fashion</span><span style="flex:1;padding:6px;text-align:center;font-size:10px;border-bottom:2px solid transparent;margin-bottom:-1px;">Home</span></div><div style="padding:6px;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;font-size:10px;">Fashion content</div></div>',
				],
				'timeline_style' => [
					'title' => __( 'Timeline Style', 'shopglut' ),
					'description' => __( 'Timeline-based selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="position:relative;padding-left:20px;"><div style="position:absolute;left:5px;top:5px;bottom:5px;width:1px;background:#ddd;"></div><div style="position:relative;padding:6px;border:1px solid #ddd;border-radius:4px;margin-bottom:5px;font-size:10px;"><span style="position:absolute;left:-12px;top:50%;transform:translateY(-50%);width:8px;height:8px;background:white;border:2px solid #ddd;border-radius:50%;"></span>1 Year</div><div style="position:relative;padding:6px;border:1px solid #667eea;border-radius:4px;background:#f8f9ff;font-size:10px;"><span style="position:absolute;left:-12px;top:50%;transform:translateY(-50%);width:8px;height:8px;background:#667eea;border:2px solid #667eea;border-radius:50%;"></span>2 Year</div></div>',
				],
				'split_button_group' => [
					'title' => __( 'Split Button Group', 'shopglut' ),
					'description' => __( 'Connected button group', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="border:1px solid #ddd;border-radius:6px;overflow:hidden;display:flex;"><span style="flex:1;padding:8px;text-align:center;border-right:1px solid #ddd;font-size:11px;background:white;">PDF</span><span style="flex:1;padding:8px;text-align:center;font-size:11px;background:#667eea;color:white;">EPUB</span><span style="flex:1;padding:8px;text-align:center;border-left:1px solid #ddd;font-size:11px;background:white;">MOBI</span></div>',
				],
				'detailed_cards' => [
					'title' => __( 'Detailed Cards', 'shopglut' ),
					'description' => __( 'Cards with details', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;flex-direction:column;gap:5px;"><span style="padding:8px;border:1px solid #ddd;border-radius:6px;"><span style="font-weight:600;font-size:11px;">Basic Plan</span><span style="font-size:9px;color:#666;">For individuals</span><span style="margin-top:4px;font-weight:600;color:#667eea;font-size:10px;">$19.99</span></span><span style="padding:8px;border:1px solid #667eea;border-radius:6px;background:#f8f9ff;"><span style="font-weight:600;font-size:11px;">Pro Plan</span><span style="font-size:9px;color:#666;">For teams</span><span style="margin-top:4px;font-weight:600;color:#667eea;font-size:10px;">$49.99</span></span></div>',
				],
				'chip_selection' => [
					'title' => __( 'Chip Selection', 'shopglut' ),
					'description' => __( 'Chip-style buttons', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;gap:5px;"><span style="padding:5px 10px;border:1px solid #ddd;border-radius:12px;font-size:11px;display:flex;align-items:center;gap:5px;background:#667eea;color:white;border-color:#667eea;"><span style="font-size:10px;">✓</span>Wi-Fi</span><span style="padding:5px 10px;border:1px solid #ddd;border-radius:12px;font-size:11px;display:flex;align-items:center;gap:5px;"><span style="font-size:10px;">✓</span>Bluetooth</span></div>',
				],
				'slider_labels' => [
					'title' => __( 'Slider with Labels', 'shopglut' ),
					'description' => __( 'Range slider selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div><div style="height:4px;background:#ddd;border-radius:3px;position:relative;margin:10px 0;"><span style="position:absolute;left:0;top:0;width:12px;height:12px;background:#667eea;border-radius:50%;transform:translate(-50%,50%);"></span></div><div style="display:flex;justify-content:space-between;font-size:9px;color:#666;margin-bottom:8px;"><span>64GB</span><span>128GB</span><span>256GB</span><span>512GB</span></div><div style="text-align:center;font-size:12px;font-weight:600;color:#667eea;">256GB Selected</div></div>',
				],
				'progress_steps' => [
					'title' => __( 'Progress Steps', 'shopglut' ),
					'description' => __( 'Step-based selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;justify-content:space-between;position:relative;padding-top:15px;"><div style="position:absolute;top:18px;left:20px;right:20px;height:1px;background:#ddd;"></div><div style="display:flex;flex-direction:column;align-items:center;position:relative;"><span style="width:20px;height:20px;border:1px solid #ddd;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#28a745;border-color:#28a745;">✓</span><span style="font-size:9px;margin-top:3px;">Bronze</span></div><div style="display:flex;flex-direction:column;align-items:center;position:relative;"><span style="width:20px;height:20px;border:1px solid #667eea;background:#667eea;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;">2</span><span style="font-size:9px;margin-top:3px;">Silver</span></div><div style="display:flex;flex-direction:column;align-items:center;position:relative;"><span style="width:20px;height:20px;border:1px solid #ddd;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;">3</span><span style="font-size:9px;margin-top:3px;">Gold</span></div></div>',
				],
				'dropdown_icons' => [
					'title' => __( 'Dropdown with Icons', 'shopglut' ),
					'description' => __( 'Custom dropdown with icons', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="border:1px solid #ddd;border-radius:6px;padding:8px;display:flex;justify-content:space-between;align-items:center;background:white;"><span style="display:flex;align-items:center;gap:5px;"><span style="font-size:14px;">💳</span><span style="font-size:11px;">Credit Card</span></span><span style="font-size:10px;">▼</span></div>',
				],
				'vertical_button_group' => [
					'title' => __( 'Vertical Button Group', 'shopglut' ),
					'description' => __( 'Vertical list style', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="border:1px solid #ddd;border-radius:6px;overflow:hidden;"><span style="padding:8px;border-bottom:1px solid #ddd;display:flex;justify-content:space-between;font-size:10px;background:white;"><span>1 Year - $0</span><span>→</span></span><span style="padding:8px;border-bottom:1px solid #ddd;display:flex;justify-content:space-between;font-size:10px;background:#667eea;color:white;"><span>2 Year - $29</span><span>→</span></span><span style="padding:8px;display:flex;justify-content:space-between;font-size:10px;background:white;"><span>3 Year - $59</span><span>→</span></span></div>',
				],
				'card_grid_hover' => [
					'title' => __( 'Card Grid with Hover', 'shopglut' ),
					'description' => __( 'Hoverable card grid', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:5px;"><span style="border:1px solid #ddd;border-radius:6px;padding:10px;text-align:center;"><span style="font-size:20px;">📱</span><br><span style="font-weight:600;font-size:11px;">Basic</span><br><span style="font-size:10px;color:#666;">$29.99</span></span><span style="border:1px solid #667eea;border-radius:6px;padding:10px;text-align:center;background:#667eea;color:white;"><span style="font-size:20px;">💼</span><br><span style="font-weight:600;font-size:11px;">Pro</span><br><span style="font-size:10px;">$49.99</span></span></div>',
				],
				'comparison_cards' => [
					'title' => __( 'Comparison Cards', 'shopglut' ),
					'description' => __( 'Comparing card options', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;"><span style="border:1px solid #ddd;border-radius:6px;padding:8px;text-align:center;position:relative;"><span style="font-weight:600;font-size:11px;">Basic</span><div style="font-size:14px;font-weight:600;margin:5px 0;">$9.99</div><span style="font-size:9px;">10GB Storage</span></span><span style="border:1px solid #667eea;border-radius:6px;padding:8px;text-align:center;background:#667eea;color:white;position:relative;"><span style="position:absolute;top:-5px;right:5px;background:white;color:#667eea;padding:2px 6px;border-radius:10px;font-size:8px;font-weight:600;">POPULAR</span><span style="font-weight:600;font-size:11px;">Pro</span><div style="font-size:14px;font-weight:600;margin:5px 0;">$29.99</div><span style="font-size:9px;">50GB Storage</span></span></div>',
				],
				'range_price' => [
					'title' => __( 'Range Price Selector', 'shopglut' ),
					'description' => __( 'Price range selection', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div><div style="display:flex;gap:10px;margin-bottom:8px;"><span style="flex:1;"><span style="font-size:9px;color:#666;">Min</span><div style="padding:6px;border:1px solid #ddd;border-radius:4px;font-size:11px;font-weight:600;">$100</div></span><span style="flex:1;"><span style="font-size:9px;color:#666;">Max</span><div style="padding:6px;border:1px solid #ddd;border-radius:4px;font-size:11px;font-weight:600;">$500</div></span></div><div style="height:4px;background:#ddd;border-radius:3px;position:relative;"><span style="position:absolute;left:25%;right:25%;height:100%;background:#667eea;border-radius:3px;"></span></div></div>',
				],
				'multi_select_boxes' => [
					'title' => __( 'Multi-Select Boxes', 'shopglut' ),
					'description' => __( 'Multi-select with boxes', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:5px;"><span style="display:flex;gap:8px;padding:8px;border:1px solid #667eea;border-radius:6px;background:#f8f9ff;"><span style="width:14px;height:14px;background:#667eea;border-radius:3px;display:flex;align-items:center;justify-content:center;"><span style="color:white;font-size:9px;">✓</span></span><span><span style="font-weight:600;font-size:10px;">Warranty</span><br><span style="font-size:9px;color:#666;">3 years</span></span></span><span style="display:flex;gap:8px;padding:8px;border:1px solid #ddd;border-radius:6px;"><span style="width:14px;height:14px;border:1px solid #ddd;border-radius:3px;"></span><span><span style="font-weight:600;font-size:10px;">Support</span><br><span style="font-size:9px;color:#666;">Priority</span></span></span></div>',
				],
				'quantity_matrix' => [
					'title' => __( 'Quantity Matrix', 'shopglut' ),
					'description' => __( 'Quantity button grid', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div><div style="font-size:10px;color:#666;margin-bottom:5px;">Choose quantity</div><div style="display:grid;grid-template-columns:repeat(5,1fr);gap:3px;"><span style="aspect-ratio:1;border:1px solid #ddd;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:white;">1</span><span style="aspect-ratio:1;border:1px solid #ddd;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:white;">5</span><span style="aspect-ratio:1;border:1px solid #667eea;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:#667eea;color:white;">10</span><span style="aspect-ratio:1;border:1px solid #ddd;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:white;">25</span><span style="aspect-ratio:1;border:1px solid #ddd;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:white;">50</span></div></div>',
				],
				'pyramid_selection' => [
					'title' => __( 'Pyramid Selection', 'shopglut' ),
					'description' => __( 'Pyramid-style layout', 'shopglut' ),
					'type' => 'pro',
					'preview' => '<div style="display:flex;flex-direction:column;gap:5px;"><div style="display:flex;justify-content:center;gap:5px;"><span style="padding:8px 15px;border:1px solid #667eea;border-radius:6px;background:#667eea;color:white;font-size:11px;font-weight:600;position:relative;"><span style="position:absolute;top:-10px;right:-5px;font-size:14px;">⭐</span><span>Best Value</span></span></div><div style="display:flex;justify-content:center;gap:5px;"><span style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;background:white;font-size:11px;font-weight:600;">Standard</span><span style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;background:white;font-size:11px;font-weight:600;">Plus</span></div></div>',
				],
			];
		}

	}
}
