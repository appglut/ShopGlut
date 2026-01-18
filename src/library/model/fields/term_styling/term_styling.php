<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: term_styling
 *
 * Renders per-term styling controls for attribute terms
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_term_styling' ) ) {
	class AGSHOPGLUT_term_styling extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Get assigned attribute for current layout
		 */
		private function get_assigned_attribute() {
			global $wpdb;

			// Get current layout ID from URL
			$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

			if ( ! $layout_id ) {
				return null;
			}

			$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT assigned_attributes, assignment_type FROM `{$table_name}` WHERE id = %d",
					$layout_id
				)
			);

			if ( $layout && $layout->assignment_type === 'attribute' && ! empty( $layout->assigned_attributes ) ) {
				$attributes = json_decode( $layout->assigned_attributes, true );
				if ( is_array( $attributes ) && ! empty( $attributes ) ) {
					return $attributes[0]; // Return first assigned attribute
				}
			}

			return null;
		}

		/**
		 * Get terms for the attribute
		 */
		private function get_attribute_terms( $attribute_name ) {
			// The assigned_attribute may already be the taxonomy name (pa_size) or just the attribute name (size)
			// Normalize to get the taxonomy name
			$taxonomy = $attribute_name;

			// If it doesn't start with 'pa_', add it
			if ( strpos( $taxonomy, 'pa_' ) !== 0 ) {
				$taxonomy = 'pa_' . $taxonomy;
			}

			// Get all terms for this taxonomy
			$terms = get_terms( array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			) );

			return $terms;
		}

		/**
		 * Get attribute label
		 */
		private function get_attribute_label( $attribute_name ) {
			// Strip 'pa_' prefix if present for comparison
			$attr_name = ( strpos( $attribute_name, 'pa_' ) === 0 )
				? substr( $attribute_name, 3 )
				: $attribute_name;

			$attribute_taxonomies = wc_get_attribute_taxonomies();

			foreach ( $attribute_taxonomies as $attr ) {
				if ( $attr->attribute_name === $attr_name ) {
					return $attr->attribute_label;
				}
			}

			return ucwords( str_replace( '_', ' ', $attr_name ) );
		}

		/**
		 * Render the field
		 */
		public function render() {
			$assigned_attribute = $this->get_assigned_attribute();

			echo wp_kses_post( $this->field_before() );

			if ( ! $assigned_attribute ) {
				echo '<div class="term-styling-no-attribute">';
				echo '<p>' . esc_html__( 'No attribute assigned to this layout. Please assign an attribute from the Product Swatches page.', 'shopglut' ) . '</p>';
				echo '</div>';
				echo wp_kses_post( $this->field_after() );
				return;
			}

			$terms = $this->get_attribute_terms( $assigned_attribute );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				echo '<div class="term-styling-empty">';
				echo '<p>' . esc_html__( 'No terms found for this attribute.', 'shopglut' ) . '</p>';
				echo '</div>';
				echo wp_kses_post( $this->field_after() );
				return;
			}

			$attribute_label = $this->get_attribute_label( $assigned_attribute );

			// Ensure value is an array
			$term_styles = is_array( $this->value ) ? $this->value : array();

			?>
			<div class="term-styling-wrapper">
				<div class="term-styling-header">
					<h4><?php printf( esc_html__( 'Per-Term Styling for: %s', 'shopglut' ), esc_html( $attribute_label ) ); ?></h4>
					<p class="description"><?php esc_html_e( 'Customize the appearance of each term button individually.', 'shopglut' ); ?></p>
				</div>

				<div class="term-styling-accordion">
					<?php
					foreach ( $terms as $index => $term ) :
						$term_slug = $term->slug;
						$term_name = $term->name;
						$field_name = $this->field_name() . '[' . esc_attr( $term_slug ) . ']';

						// Get saved values for this term
						$term_data = isset( $term_styles[ $term_slug ] ) ? $term_styles[ $term_slug ] : array();

						$bg_color = isset( $term_data['bg_color'] ) ? $term_data['bg_color'] : '#f3f4f6';
						$text_color = isset( $term_data['text_color'] ) ? $term_data['text_color'] : '#374151';
						$border_color = isset( $term_data['border_color'] ) ? $term_data['border_color'] : '#d1d5db';
						$border_width = isset( $term_data['border_width'] ) ? $term_data['border_width'] : 1;
						$border_radius = isset( $term_data['border_radius'] ) ? $term_data['border_radius'] : 6;
						$font_size = isset( $term_data['font_size'] ) ? $term_data['font_size'] : 13;
						$font_weight = isset( $term_data['font_weight'] ) ? $term_data['font_weight'] : '500';
						$padding_x = isset( $term_data['padding_x'] ) ? $term_data['padding_x'] : 16;
						$padding_y = isset( $term_data['padding_y'] ) ? $term_data['padding_y'] : 8;
						$min_width = isset( $term_data['min_width'] ) ? $term_data['min_width'] : 45;
						$min_height = isset( $term_data['min_height'] ) ? $term_data['min_height'] : 40;

						$is_first = $index === 0;
					?>
						<div class="term-styling-item" data-term-slug="<?php echo esc_attr( $term_slug ); ?>">
							<div class="term-styling-header-bar">
								<span class="term-styling-title"><?php echo esc_html( $term_name ); ?></span>
								<span class="term-styling-toggle dashicons dashicons-arrow-down-alt2 <?php echo $is_first ? 'open' : ''; ?>"></span>
							</div>

							<div class="term-styling-content" style="display: <?php echo $is_first ? 'block' : 'none'; ?>;">
								<div class="term-styling-preview">
									<div class="term-preview-label"><?php esc_html_e( 'Preview:', 'shopglut' ); ?></div>
									<button class="term-preview-btn" style="
										background-color: <?php echo esc_attr( $bg_color ); ?>;
										color: <?php echo esc_attr( $text_color ); ?>;
										border: <?php echo esc_attr( $border_width ); ?>px solid <?php echo esc_attr( $border_color ); ?>;
										border-radius: <?php echo esc_attr( $border_radius ); ?>px;
										font-size: <?php echo esc_attr( $font_size ); ?>px;
										font-weight: <?php echo esc_attr( $font_weight ); ?>;
										padding: <?php echo esc_attr( $padding_y ); ?>px <?php echo esc_attr( $padding_x ); ?>px;
										min-width: <?php echo esc_attr( $min_width ); ?>px;
										min-height: <?php echo esc_attr( $min_height ); ?>px;
										cursor: pointer;
									"><?php echo esc_html( $term_name ); ?></button>
								</div>

								<div class="term-styling-fields">
									<!-- Colors Section -->
									<div class="term-styling-section">
										<h5><?php esc_html_e( 'Colors', 'shopglut' ); ?></h5>
										<div class="term-styling-row">
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Background', 'shopglut' ); ?></label>
												<input type="text" name="<?php echo esc_attr( $field_name . '[bg_color]' ); ?>"
													class="shopglut-color-picker" value="<?php echo esc_attr( $bg_color ); ?>"
													data-default-color="<?php echo esc_attr( $bg_color ); ?>">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Text', 'shopglut' ); ?></label>
												<input type="text" name="<?php echo esc_attr( $field_name . '[text_color]' ); ?>"
													class="shopglut-color-picker" value="<?php echo esc_attr( $text_color ); ?>"
													data-default-color="<?php echo esc_attr( $text_color ); ?>">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Border', 'shopglut' ); ?></label>
												<input type="text" name="<?php echo esc_attr( $field_name . '[border_color]' ); ?>"
													class="shopglut-color-picker" value="<?php echo esc_attr( $border_color ); ?>"
													data-default-color="<?php echo esc_attr( $border_color ); ?>">
											</div>
										</div>
									</div>

									<!-- Border Section -->
									<div class="term-styling-section">
										<h5><?php esc_html_e( 'Border', 'shopglut' ); ?></h5>
										<div class="term-styling-row">
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Width (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[border_width]' ); ?>"
													value="<?php echo esc_attr( $border_width ); ?>" min="0" max="10" step="1">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Radius (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[border_radius]' ); ?>"
													value="<?php echo esc_attr( $border_radius ); ?>" min="0" max="50" step="1">
											</div>
										</div>
									</div>

									<!-- Typography Section -->
									<div class="term-styling-section">
										<h5><?php esc_html_e( 'Typography', 'shopglut' ); ?></h5>
										<div class="term-styling-row">
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Font Size (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[font_size]' ); ?>"
													value="<?php echo esc_attr( $font_size ); ?>" min="10" max="30" step="1">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Font Weight', 'shopglut' ); ?></label>
												<select name="<?php echo esc_attr( $field_name . '[font_weight]' ); ?>">
													<?php
													$weights = array( '300', '400', '500', '600', '700', '800', '900' );
													foreach ( $weights as $w ) {
														$selected = ( $font_weight === $w ) ? 'selected' : '';
														echo '<option value="' . esc_attr( $w ) . '" ' . $selected . '>' . esc_html( $w ) . '</option>';
													}
													?>
												</select>
											</div>
										</div>
									</div>

									<!-- Spacing Section -->
									<div class="term-styling-section">
										<h5><?php esc_html_e( 'Spacing', 'shopglut' ); ?></h5>
										<div class="term-styling-row">
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Padding X (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[padding_x]' ); ?>"
													value="<?php echo esc_attr( $padding_x ); ?>" min="0" max="50" step="1">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Padding Y (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[padding_y]' ); ?>"
													value="<?php echo esc_attr( $padding_y ); ?>" min="0" max="50" step="1">
											</div>
										</div>
										<div class="term-styling-row">
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Min Width (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[min_width]' ); ?>"
													value="<?php echo esc_attr( $min_width ); ?>" min="20" max="200" step="1">
											</div>
											<div class="term-styling-field">
												<label><?php esc_html_e( 'Min Height (px)', 'shopglut' ); ?></label>
												<input type="number" name="<?php echo esc_attr( $field_name . '[min_height]' ); ?>"
													value="<?php echo esc_attr( $min_height ); ?>" min="20" max="200" step="1">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<style>
				.term-styling-wrapper {
					background: #fff;
					border-radius: 6px;
				}

				.term-styling-header {
					padding: 15px 0;
					border-bottom: 1px solid #ddd;
				}

				.term-styling-header h4 {
					margin: 0 0 5px 0;
					font-size: 14px;
					font-weight: 600;
					color: #1d2327;
				}

				.term-styling-header .description {
					margin: 0;
					font-size: 12px;
					color: #646970;
				}

				.term-styling-accordion {
					margin-top: 15px;
				}

				.term-styling-item {
					border: 1px solid #ddd;
					border-radius: 4px;
					margin-bottom: 10px;
					overflow: hidden;
				}

				.term-styling-header-bar {
					display: flex;
					justify-content: space-between;
					align-items: center;
					padding: 12px 15px;
					background: #f9f9f9;
					cursor: pointer;
					user-select: none;
				}

				.term-styling-header-bar:hover {
					background: #f0f0f0;
				}

				.term-styling-title {
					font-weight: 600;
					font-size: 13px;
					color: #1d2327;
				}

				.term-styling-toggle {
					transition: transform 0.3s;
				}

				.term-styling-toggle.open {
					transform: rotate(180deg);
				}

				.term-styling-content {
					padding: 15px;
					background: #fff;
				}

				.term-styling-preview {
					display: flex;
					align-items: center;
					gap: 15px;
					margin-bottom: 15px;
					padding-bottom: 15px;
					border-bottom: 1px dashed #ddd;
				}

				.term-preview-label {
					font-size: 12px;
					font-weight: 600;
					color: #646970;
					min-width: 60px;
				}

				.term-styling-fields {
					display: flex;
					flex-direction: column;
					gap: 15px;
				}

				.term-styling-section h5 {
					margin: 0 0 10px 0;
					font-size: 12px;
					font-weight: 600;
					color: #1d2327;
					text-transform: uppercase;
				}

				.term-styling-row {
					display: flex;
					gap: 15px;
					flex-wrap: wrap;
				}

				.term-styling-field {
					flex: 1;
					min-width: 120px;
				}

				.term-styling-field label {
					display: block;
					font-size: 11px;
					font-weight: 500;
					color: #646970;
					margin-bottom: 4px;
				}

				.term-styling-field input[type="text"],
				.term-styling-field input[type="number"],
				.term-styling-field select {
					width: 100%;
					padding: 6px 8px;
					border: 1px solid #d1d5db;
					border-radius: 4px;
					font-size: 12px;
				}

				.term-styling-field input[type="number"] {
					min-width: 70px;
				}

				.shopglut-color-picker {
					width: 50px !important;
					height: 30px;
				}

				.term-styling-no-attribute,
				.term-styling-empty {
					padding: 20px;
					text-align: center;
					background: #fff;
					border-radius: 6px;
					border: 1px dashed #c3c4c7;
				}

				.term-styling-no-attribute p,
				.term-styling-empty p {
					margin: 0;
					color: #646970;
					font-size: 13px;
				}
			</style>

			<script>
				jQuery(document).ready(function($) {
					// Color picker initialization
					$('.shopglut-color-picker').wpColorPicker();

					// Accordion toggle
					$('.term-styling-header-bar').on('click', function() {
						var content = $(this).next('.term-styling-content');
						var toggle = $(this).find('.term-styling-toggle');

						content.slideToggle(200);
						toggle.toggleClass('open');
					});

					// Live preview on input change
					$('.term-styling-fields input, .term-styling-fields select').on('change input', function() {
						var item = $(this).closest('.term-styling-item');
						var previewBtn = item.find('.term-preview-btn');
						var bg = item.find('input[name*="[bg_color]"]').val();
						var text = item.find('input[name*="[text_color]"]').val();
						var border = item.find('input[name*="[border_color]"]').val();
						var borderWidth = item.find('input[name*="[border_width]"]').val();
						var borderRadius = item.find('input[name*="[border_radius]"]').val();
						var fontSize = item.find('input[name*="[font_size]"]').val();
						var fontWeight = item.find('select[name*="[font_weight]"]').val();
						var paddingX = item.find('input[name*="[padding_x]"]').val();
						var paddingY = item.find('input[name*="[padding_y]"]').val();
						var minWidth = item.find('input[name*="[min_width]"]').val();
						var minHeight = item.find('input[name*="[min_height]"]').val();

						previewBtn.css({
							'background-color': bg,
							'color': text,
							'border': borderWidth + 'px solid ' + border,
							'border-radius': borderRadius + 'px',
							'font-size': fontSize + 'px',
							'font-weight': fontWeight,
							'padding': paddingY + 'px ' + paddingX + 'px',
							'min-width': minWidth + 'px',
							'min-height': minHeight + 'px'
						});
					});
				});
			</script>

			<?php

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Output CSS - not needed for this field
		 */
		public function output() {
			// Output custom CSS for per-term styling
			$this->render_per_term_css();
		}

		/**
		 * Render per-term custom CSS
		 */
		private function render_per_term_css() {
			if ( empty( $this->value ) || ! is_array( $this->value ) ) {
				return;
			}

			$css = '';

			foreach ( $this->value as $term_slug => $term_data ) {
				if ( empty( $term_data ) || ! is_array( $term_data ) ) {
					continue;
				}

				$selector = '.shopglut-swatch-button[data-value="' . esc_attr( $term_slug ) . '"]';
				$styles = array();

				if ( isset( $term_data['bg_color'] ) ) {
					$styles[] = 'background-color: ' . esc_attr( $term_data['bg_color'] ) . ' !important';
				}
				if ( isset( $term_data['text_color'] ) ) {
					$styles[] = 'color: ' . esc_attr( $term_data['text_color'] ) . ' !important';
				}
				if ( isset( $term_data['border_color'] ) ) {
					$border_width = isset( $term_data['border_width'] ) ? $term_data['border_width'] : 1;
					$styles[] = 'border: ' . esc_attr( $border_width ) . 'px solid ' . esc_attr( $term_data['border_color'] ) . ' !important';
				}
				if ( isset( $term_data['border_radius'] ) ) {
					$styles[] = 'border-radius: ' . esc_attr( $term_data['border_radius'] ) . 'px !important';
				}
				if ( isset( $term_data['font_size'] ) ) {
					$styles[] = 'font-size: ' . esc_attr( $term_data['font_size'] ) . 'px !important';
				}
				if ( isset( $term_data['font_weight'] ) ) {
					$styles[] = 'font-weight: ' . esc_attr( $term_data['font_weight'] ) . ' !important';
				}
				if ( isset( $term_data['padding_x'] ) && isset( $term_data['padding_y'] ) ) {
					$styles[] = 'padding: ' . esc_attr( $term_data['padding_y'] ) . 'px ' . esc_attr( $term_data['padding_x'] ) . 'px !important';
				}
				if ( isset( $term_data['min_width'] ) ) {
					$styles[] = 'min-width: ' . esc_attr( $term_data['min_width'] ) . 'px !important';
				}
				if ( isset( $term_data['min_height'] ) ) {
					$styles[] = 'min-height: ' . esc_attr( $term_data['min_height'] ) . 'px !important';
				}

				if ( ! empty( $styles ) ) {
					$css .= $selector . ' { ' . implode( '; ', $styles ) . '; }' . "\n";
				}
			}

			if ( ! empty( $css ) ) {
				echo '<style id="shopglut-term-styling-css">' . $css . '</style>';
			}
		}
	}
}
