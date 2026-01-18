<?php
/**
 * Framework Typography field file.
 *
 * @since 2.0.0
 *
 * @package shopglut
 * @subpackage shopglut/Framework
 */

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

if (!class_exists('AGSHOPGLUT_typography')) {
	/**
	 *
	 * Field: typography
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class AGSHOPGLUT_typography extends AGSHOPGLUTP {

		/**
		 * Chosen
		 *
		 * @var bool
		 */
		public $chosen = false;

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		/**
		 * Typography field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

			$args = wp_parse_args(
				$this->field,
				array(
					'font_family' => true,
					'font_weight' => true,
					'font_style' => true,
					'font_size' => true,
					'line_height' => true,
					'letter_spacing' => true,
					'text_align' => true,
					'text_transform' => true,
					'color' => true,
					'hover_color' => true,
					'active_color' => true,
					'chosen' => true,
					'preview' => true,
					'subset' => true,
					'multi_subset' => false,
					'extra_styles' => false,
					'backup_font_family' => false,
					'font_variant' => true,
					'word_spacing' => true,
					'text_decoration' => true,
					'custom_style' => false,
					'unit' => true,
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- This is configuration array, not a database query
					'exclude' => '',
					'line_height_unit' => '',
					'preview_text' => 'The quick brown fox jumps over the lazy dog',
					'margin_bottom' => '',
					'max' => 100,
					'min' => 0,
					'step' => 1,
					'slider_value' => true,
					'show_units' => true,
					'units' => array('px', '%', 'em'),
					'active_device' => false,
					'device_type' => array(
						'desktop', 'tablet', 'mobile',
					),
				)
			);

			$default_value = array(
				'font-family' => '',
				'font-weight' => '',
				'font-weights' => '',
				'font-style' => '',
				'font-styles' => '',
				'font-variant' => '',
				'font-size' => '',
				'line-height' => '',
				'letter-spacing' => '',
				'word-spacing' => '',
				'text-align' => '',
				'text-transform' => '',
				'text-decoration' => '',
				'backup-font-family' => '',
				'color' => '',
				'hover_color' => '',
				'active_color' => '',
				'custom-style' => '',
				'type' => '',
				'subset' => '',
				'extra-styles' => array(),
				'margin-bottom' => '',
			);

			$default_value = (!empty($this->field['default'])) ? wp_parse_args($this->field['default'], $default_value) : $default_value;
			$this->value = wp_parse_args($this->value, $default_value);
			$this->chosen = $args['chosen'];
			$chosen_class = ($this->chosen) ? ' agl--chosen' : '';
			$line_height_unit = (!empty($args['line_height_unit'])) ? $args['line_height_unit'] : $args['unit'];

			$unit = (count($args['units']) === 1 && !empty($args['unit'])) ? $args['units'][0] : '';
			$is_unit = (!empty($unit)) ? ' agl--is-unit' : '';

			echo '<div class="agl--typography' . esc_attr($chosen_class) . '" data-unit="' . esc_attr($args['unit']) . '" data-line-height-unit="' . esc_attr($line_height_unit) . '" data-exclude="' . esc_attr($args['exclude']) . '">';

			echo '<div class="agl--blocks agl--blocks-selects">';

			//
			// Font Family.
			if (!empty($args['font_family'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Font Family', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(array($this->value['font-family'] => $this->value['font-family']), 'font-family', esc_html__('Select Font', 'shopglut'));
				echo '</div>';
			}

			//
			// Backup Font Family.
			if (!empty($args['backup_font_family'])) {
				echo '<div class="agl--block agl--block-backup-font-family hidden">';
				echo '<div class="agl--title">' . esc_html__('Backup Font Family', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					apply_filters(
						'agl_field_typography_backup_font_family',
						array(
							'Arial, Helvetica, sans-serif',
							"'Arial Black', Gadget, sans-serif",
							"'Comic Sans MS', cursive, sans-serif",
							'Impact, Charcoal, sans-serif',
							"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
							'Tahoma, Geneva, sans-serif',
							"'Trebuchet MS', Helvetica, sans-serif'",
							'Verdana, Geneva, sans-serif',
							"'Courier New', Courier, monospace",
							"'Lucida Console', Monaco, monospace",
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
					'backup-font-family',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			//
			// Font Style and Extra Style Select.
			if (!empty($args['font_weight']) || !empty($args['font_style'])) {

				//
				// Font Style Select.
				echo '<div class="agl--block agl--block-font-style hidden" style="display:none">';
				echo '<div class="agl--title">' . esc_html__('Font Style', 'shopglut') . '</div>';
				echo '<select data-depend-id="' . esc_attr($this->field['id'] . '-font-style') . '" class="agl--font-style-select" data-placeholder="Default">';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<option value="">' . (!$this->chosen ? esc_html__('Default', 'shopglut') : '') . '</option>';
				if (!empty($this->value['font-weight']) || !empty($this->value['font-style'])) {
					echo '<option value="' . esc_attr(strtolower($this->value['font-weight'] . $this->value['font-style'])) . '" selected></option>';
				}
				echo '</select>';
				echo '<input type="hidden" name="' . esc_attr($this->field_name('[font-weight]')) . '" data-depend-id="' . esc_attr($this->field['id'] . '-font-weight') . '" class="agl--font-weight" value="' . esc_attr($this->value['font-weight']) . '" />';
				echo '<input type="hidden" name="' . esc_attr($this->field_name('[font-style]')) . '" data-depend-id="' . esc_attr($this->field['id'] . '-font-style') . '" class="agl--font-style" value="' . esc_attr($this->value['font-style']) . '" />';

				//
				// Extra Font Style Select.
				if (!empty($args['extra_styles'])) {
					echo '<div class="agl--block-extra-styles hidden">';
					echo (!$this->chosen) ? '<div class="agl--title">' . esc_html__('Load Extra Styles', 'shopglut') . '</div>' : '';
					$placeholder = ($this->chosen) ? esc_html__('Load Extra Styles', 'shopglut') : esc_html__('Default', 'shopglut');
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->create_select($this->value['extra-styles'], 'extra-styles', $placeholder, true);
					echo '</div>';
				}

				echo '</div>';

			}

			//
			// Subset.
			if (!empty($args['subset'])) {
				echo '<div class="agl--block agl--block-subset hidden" style="display:none">';
				echo '<div class="agl--title">' . esc_html__('Subset', 'shopglut') . '</div>';
				$subset = (is_array($this->value['subset'])) ? $this->value['subset'] : array_filter((array) $this->value['subset']);
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select($subset, 'subset', esc_html__('Default', 'shopglut'), $args['multi_subset']);
				echo '</div>';
			}

			//
			// Text Align.
			if (!empty($args['text_align'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Text Align', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'inherit' => esc_html__('Inherit', 'shopglut'),
						'left' => esc_html__('Left', 'shopglut'),
						'center' => esc_html__('Center', 'shopglut'),
						'right' => esc_html__('Right', 'shopglut'),
						'justify' => esc_html__('Justify', 'shopglut'),
						'initial' => esc_html__('Initial', 'shopglut'),
					),
					'text-align',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			//
			// Font weight.
			if (!empty($args['font_weights'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Font Weight', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'normal' => esc_html__('Normal', 'shopglut'),
						'bold' => esc_html__('Bold', 'shopglut'),
						'bolder' => esc_html__('Bolder', 'shopglut'),
						'lighter' => esc_html__('Lighter', 'shopglut'),
						'100' => esc_html__('100', 'shopglut'),
						'200' => esc_html__('200', 'shopglut'),
						'300' => esc_html__('300', 'shopglut'),
						'400' => esc_html__('400', 'shopglut'),
						'500' => esc_html__('500', 'shopglut'),
						'600' => esc_html__('600', 'shopglut'),
						'700' => esc_html__('700', 'shopglut'),
						'800' => esc_html__('800', 'shopglut'),
						'900' => esc_html__('900', 'shopglut'),

					),
					'font-weights',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			// Font Style.
			if (!empty($args['font_styles'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Font Style', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'normal' => esc_html__('Normal', 'shopglut'),
						'italic' => esc_html__('Italic', 'shopglut'),
						'oblique' => esc_html__('Oblique', 'shopglut'),
					),
					'font-styles',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			// Font Variant.
			if (!empty($args['font_variant'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Font Variant', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'normal' => esc_html__('Normal', 'shopglut'),
						'small-caps' => esc_html__('Small Caps', 'shopglut'),
						'all-small-caps' => esc_html__('All Small Caps', 'shopglut'),
					),
					'font-variant',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			//
			// Text Transform.
			if (!empty($args['text_transform'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Text Transform', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none' => esc_html__('None', 'shopglut'),
						'capitalize' => esc_html__('Capitalize', 'shopglut'),
						'uppercase' => esc_html__('Uppercase', 'shopglut'),
						'lowercase' => esc_html__('Lowercase', 'shopglut'),
					),
					'text-transform',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			//
			// Text Decoration.
			if (!empty($args['text_decoration'])) {
				echo '<div class="agl--block">';
				echo '<div class="agl--title">' . esc_html__('Text Decoration', 'shopglut') . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none' => esc_html__('None', 'shopglut'),
						'underline' => esc_html__('Solid', 'shopglut'),
						'underline double' => esc_html__('Double', 'shopglut'),
						'underline dotted' => esc_html__('Dotted', 'shopglut'),
						'underline dashed' => esc_html__('Dashed', 'shopglut'),
						'underline wavy' => esc_html__('Wavy', 'shopglut'),
						'underline overline' => esc_html__('Overline', 'shopglut'),
						'line-through' => esc_html__('Line-through', 'shopglut'),
					),
					'text-decoration',
					esc_html__('Default', 'shopglut')
				);
				echo '</div>';
			}

			echo '</div>';

			echo '<div class="agl--blocks agl--blocks-inputs">';

			//
			// Font Size.
			if (!empty($args['font_size'])) {

				echo '<div class="agl-field agl-field-slider slider-typo">';
				echo '<div class="agl-title"><h4>' . esc_html__('Font Size', 'shopglut') . '</h4></div>';
				echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap field-slider-wrap slider-' . esc_attr($this->field['id']) . '-select-type_">';
				echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_"></div>';
				echo '<div class="agl--inputs slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

				// Radio buttons
				if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
					foreach ($args['units'] as $unit_option) {
						$checked = (isset($this->value['font-size-unit'])) && ($this->value['font-size-unit'] === $unit_option) ? 'checked="checked"' : '';
						$selected_class = $checked ? 'selected' : '';
						echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" data-depend-id="' . esc_attr($this->field['id'] . '-font-size-unit') . '" name="' . esc_attr($this->field_name('[font-size-unit]')) . '" data-depend-id="' . esc_attr($this->field['id'] . '-font-size-unit') . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
					}
				}

				// Number input
				if (!empty($args['slider_value'])) {
					echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . '" type="number" name="' . esc_attr($this->field_name('[font-size]')) . '" data-depend-id="' . esc_attr($this->field['id'] . '-font-size') . '" value="' . esc_attr($this->value['font-size']) . '"' . wp_kses_post($this->field_attributes(array('class' => 'agl-woo-input-number' . esc_attr($is_unit)))) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';

				}

				echo '</div>';
				echo '</div>';

				echo '</div>';

			}

			//
			// Line Height.
			if (!empty($args['line_height'])) {

				echo '<div class="agl-field agl-field-slider slider-typo">';
				echo '<div class="agl-title"><h4>' . esc_html__('Line Height', 'shopglut') . '</h4></div>';
				echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap field-slider-wrap slider-' . esc_attr($this->field['id']) . '-select-type_">';
				echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_"></div>';
				echo '<div class="agl--inputs slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

				// Radio buttons
				if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
					foreach ($args['units'] as $unit_option) {
						$checked = (isset($this->value['line-height-unit'])) && ($this->value['line-height-unit'] === $unit_option) ? 'checked="checked"' : '';
						$selected_class = $checked ? 'selected' : '';
						echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" data-depend-id="' . esc_attr($this->field['id'] . '-line-height-unit') . '" name="' . esc_attr($this->field_name('[line-height-unit]')) . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
					}
				}

				// Number input
				if (!empty($args['slider_value'])) {
					echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . '" type="number" data-depend-id="' . esc_attr($this->field['id'] . '-line-height') . '" name="' . esc_attr($this->field_name('[line-height]')) . '" value="' . esc_attr($this->value['line-height']) . '"' . wp_kses_post($this->field_attributes(array('class' => 'agl-woo-input-number' . esc_attr($is_unit)))) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';

				}

				echo '</div>';
				echo '</div>';

				echo '</div>';
			}

			//
			// Letter Spacing.
			if (!empty($args['letter_spacing'])) {
				echo '<div class="agl-field agl-field-slider slider-typo">';
				echo '<div class="agl-title"><h4>' . esc_html__('Letter Spacing', 'shopglut') . '</h4></div>';
				echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap field-slider-wrap slider-' . esc_attr($this->field['id']) . '-select-type_">';
				echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_"></div>';
				echo '<div class="agl--inputs slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

				// Radio buttons
				if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
					foreach ($args['units'] as $unit_option) {
						$checked = (isset($this->value['letter-spacing-unit'])) && ($this->value['letter-spacing-unit'] === $unit_option) ? 'checked="checked"' : '';
						$selected_class = $checked ? 'selected' : '';
						echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" data-depend-id="' . esc_attr($this->field['id'] . '-letter-spacing-unit') . '" name="' . esc_attr($this->field_name('[letter-spacing-unit]')) . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
					}
				}

				// Number input
				if (!empty($args['slider_value'])) {
					echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . '" type="number" data-depend-id="' . esc_attr($this->field['id'] . '-letter-spacing') . '" name="' . esc_attr($this->field_name('[letter-spacing]')) . '" value="' . esc_attr($this->value['letter-spacing']) . '"' . wp_kses_post($this->field_attributes(array('class' => 'agl-woo-input-number' . esc_attr($is_unit)))) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';

				}

				echo '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Word Spacing.
			if (!empty($args['word_spacing'])) {
				echo '<div class="agl-field agl-field-slider slider-typo">';
				echo '<div class="agl-title"><h4>' . esc_html__('Word Spacing', 'shopglut') . '</h4></div>';
				echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap field-slider-wrap slider-' . esc_attr($this->field['id']) . '-select-type_">';
				echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_"></div>';
				echo '<div class="agl--inputs slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

				// Radio buttons
				if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
					foreach ($args['units'] as $unit_option) {
						$checked = (isset($this->value['word-spacing-unit'])) && ($this->value['word-spacing-unit'] === $unit_option) ? 'checked="checked"' : '';
						$selected_class = $checked ? 'selected' : '';
						echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" data-depend-id="' . esc_attr($this->field['id'] . '-word-spacing-unit') . '" name="' . esc_attr($this->field_name('[word-spacing-unit]')) . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
					}
				}

				// Number input
				if (!empty($args['slider_value'])) {
					echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . '" type="number" data-depend-id="' . esc_attr($this->field['id'] . '-word-spacing') . '" name="' . esc_attr($this->field_name('[word-spacing]')) . '" value="' . esc_attr($this->value['word-spacing']) . '"' . wp_kses_post($this->field_attributes(array('class' => 'agl-woo-input-number' . esc_attr($is_unit)))) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';

				}

				echo '</div>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			//
			// Font Color.
			if (!empty($args['color'])) {
				echo '<div class="agl--blocks agl--blocks-color">';
				$default_color_attr = (!empty($default_value['color'])) ? ' data-default-color="' . esc_attr($default_value['color']) . '"' : '';
				echo '<div class="agl--block agl--block-font-color">';
				echo '<div class="agl--title">' . esc_html__('Font Color', 'shopglut') . '</div>';
				echo '<div class="agl-field-color">';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="text" data-depend-id="' . esc_attr($this->field['id'] . '-font-color') . '" name="' . esc_attr($this->field_name('[color]')) . '" class="agl-color agl--color" value="' . esc_attr($this->value['color']) . '"' . $default_color_attr . ' />';
				echo '</div>';
				echo '</div>';

				//
				// Font Hover Color.
				if (!empty($args['hover_color'])) {
					$default_hover_color_attr = (!empty($default_value['hover_color'])) ? ' data-default-color="' . esc_attr($default_value['hover_color']) . '"' : '';
					echo '<div class="agl--block agl--block-font-color">';
					echo '<div class="agl--title">' . esc_html__('Font Hover Color', 'shopglut') . '</div>';
					echo '<div class="agl-field-color">';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" data-depend-id="' . esc_attr($this->field['id'] . '-font-hover-color') . '" name="' . esc_attr($this->field_name('[hover_color]')) . '" class="agl-color agl--color" value="' . esc_attr($this->value['hover_color']) . '"' . $default_hover_color_attr . ' />';
					echo '</div>';
					echo '</div>';
				}
				//
				// Font active Color.
				if (!empty($args['active_color'])) {
					$default_active_color_attr = (!empty($default_value['active_color'])) ? ' data-default-color="' . esc_attr($default_value['active_color']) . '"' : '';
					echo '<div class="agl--block agl--block-font-color">';
					echo '<div class="agl--title">' . esc_html__('Font Active Color', 'shopglut') . '</div>';
					echo '<div class="agl-field-color">';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" data-depend-id="' . esc_attr($this->field['id'] . '-font-active-color') . '" name="' . esc_attr($this->field_name('[active_color]')) . '" class="agl-color agl--color" value="' . esc_attr($this->value['active_color']) . '"' . $default_active_color_attr . ' />';
					echo '</div>';
					echo '</div>';
				}

				echo '</div>';
			}

			//
			// Custom style.
			if (!empty($args['custom_style'])) {
				echo '<div class="agl--block agl--block-custom-style">';
				echo '<div class="agl--title">' . esc_html__('Custom Style', 'shopglut') . '</div>';
				echo '<textarea name="' . esc_attr($this->field_name('[custom-style]')) . '" class="agl--custom-style">' . esc_attr($this->value['custom-style']) . '</textarea>';
				echo '</div>';
			}

			// Margin Bottom.
			if (!empty($args['margin_bottom'])) {
				echo '<div class="agl--block agl--block-margin">';
				echo '<div class="agl--title">' . esc_html__('Margin Bottom', 'shopglut') . '</div>';
				echo '<div class="agl--blocks">';
				echo '<div class="agl--block agl--unit"><i class="fa fa-long-arrow-down"></i></div>';
				echo '<div class="agl--block"><input type="number" data-depend-id="' . esc_attr($this->field['id'] . '-margin-bottom') . '" name="' . $this->field_name('[margin-bottom]') . '" class="agl--margin-bottom agl--input agl-number" value="' . $this->value['margin-bottom'] . '" /></div>'; // phpcs:ignore
				echo '<div class="agl--block agl--unit">' . wp_kses_post($args['unit']) . '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Preview.
			$always_preview = ('always' !== $args['preview']) ? ' hidden' : '';

			if (!empty($args['preview'])) {
				echo '<div class="agl--block agl--block-preview' . esc_attr($always_preview) . '">';
				echo '<div class="agl--toggle fa fa-toggle-off"></div>';
				echo '<div class="agl--preview">' . esc_attr($args['preview_text']) . '</div>';
				echo '</div>';
			}

			echo '<input type="hidden" name="' . esc_attr($this->field_name('[type]')) . '" class="agl--type" value="' . esc_attr($this->value['type']) . '" />';
			echo '<input type="hidden" name="' . esc_attr($this->field_name('[unit]')) . '" class="agl--unit-save" value="' . esc_attr($args['unit']) . '" />';

			echo '</div>';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}

		/**
		 * Create select
		 *
		 * @param  array  $options options.
		 * @param  string $name name.
		 * @param  mixed  $placeholder placeholder.
		 * @param  bool   $is_multiple is_multiple.
		 * @return statement
		 */
		public function create_select($options, $name, $placeholder = '', $is_multiple = false) {

			$multiple_name = ($is_multiple) ? '[]' : '';
			$multiple_attr = ($is_multiple) ? ' multiple data-multiple="true"' : '';
			$chosen_rtl = ($this->chosen && is_rtl()) ? ' chosen-rtl' : '';

			$output = '<select name="' . esc_attr($this->field_name('[' . $name . ']' . $multiple_name)) . '" data-depend-id="' . esc_attr($this->field['id'] . '-' . $name) . '"  class="agl--' . esc_attr($name) . esc_attr($chosen_rtl) . '" data-placeholder="' . esc_attr($placeholder) . '"' . $multiple_attr . '>';
			$output .= (!empty($placeholder)) ? '<option value="">' . esc_attr((!$this->chosen) ? $placeholder : '') . '</option>' : '';

			if (!empty($options)) {
				foreach ($options as $option_key => $option_value) {
					if ($is_multiple) {
						$selected = (in_array($option_value, $this->value[$name], true)) ? ' selected' : '';
						$output .= '<option value="' . esc_attr($option_value) . '"' . esc_attr($selected) . '>' . esc_attr($option_value) . '</option>';
					} else {
						$option_key = (is_numeric($option_key)) ? $option_value : $option_key;
						$selected = ($option_key === $this->value[$name]) ? ' selected' : '';
						$output .= '<option value="' . esc_attr($option_key) . '"' . esc_attr($selected) . '>' . esc_attr($option_value) . '</option>';
					}
				}
			}

			$output .= '</select>';

			return $output;

		}

		/**
		 * Field enqueue script.
		 *
		 * @return void
		 */
		public function enqueue() {

			if (!wp_script_is('agl-webfontloader')) {

				AGSHOPGLUT::include_plugin_file('fields/typography/google-fonts.php');


				$webfonts = array();

				$customwebfonts = apply_filters('agl_field_typography_customwebfonts', array());

				if (!empty($customwebfonts)) {
					$webfonts['custom'] = array(
						'label' => esc_html__('Custom Web Fonts', 'shopglut'),
						'fonts' => $customwebfonts,
					);
				}

				$webfonts['safe'] = array(
					'label' => esc_html__('Safe Web Fonts', 'shopglut'),
					'fonts' => apply_filters(
						'agl_field_typography_safewebfonts',
						array(
							'Arial',
							'Arial Black',
							'Helvetica',
							'Times New Roman',
							'Courier New',
							'Tahoma',
							'Verdana',
							'Impact',
							'Trebuchet MS',
							'Comic Sans MS',
							'Lucida Console',
							'Lucida Sans Unicode',
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
				);

				$webfonts['google'] = array(
					'label' => esc_html__('Google Web Fonts', 'shopglut'),
					'fonts' => apply_filters(
						'agl_field_typography_googlewebfonts',
						agl_get_google_fonts()
					),
				);

				$defaultstyles = apply_filters('agl_field_typography_defaultstyles', array('normal', 'italic', '700', '700italic'));

				$googlestyles = apply_filters(
					'agl_field_typography_googlestyles',
					array(
						'100' => 'Thin 100',
						'100italic' => 'Thin 100 Italic',
						'200' => 'Extra-Light 200',
						'200italic' => 'Extra-Light 200 Italic',
						'300' => 'Light 300',
						'300italic' => 'Light 300 Italic',
						'normal' => 'Normal 400',
						'italic' => 'Normal 400 Italic',
						'500' => 'Medium 500',
						'500italic' => 'Medium 500 Italic',
						'600' => 'Semi-Bold 600',
						'600italic' => 'Semi-Bold 600 Italic',
						'700' => 'Bold 700',
						'700italic' => 'Bold 700 Italic',
						'800' => 'Extra-Bold 800',
						'800italic' => 'Extra-Bold 800 Italic',
						'900' => 'Black 900',
						'900italic' => 'Black 900 Italic',
					)
				);

				$webfonts = apply_filters('agl_field_typography_webfonts', $webfonts);

				wp_localize_script(
					'shopglut',
					'agl_typography_json',
					array(
						'webfonts' => $webfonts,
						'defaultstyles' => $defaultstyles,
						'googlestyles' => $googlestyles,
					)
				);

			}

		}

		/**
		 * Enqueue google fonts
		 *
		 * @return mixed
		 */
		public function enqueue_google_fonts() {

			$value = $this->value;
			$families = array();
			$is_google = false;

			if (!empty($this->value['type'])) {
				$is_google = ('google' === $this->value['type']) ? true : false;
			} else {
				AGSHOPGLUT::include_plugin_file('fields/typography/google-fonts.php');
				$is_google = (array_key_exists($this->value['font-family'], agl_get_google_fonts())) ? true : false;
			}

			if ($is_google) {

				// set style.
				$font_weight = (!empty($value['font-weight'])) ? $value['font-weight'] : '';
				$font_style = (!empty($value['font-style'])) ? $value['font-style'] : '';

				if ($font_weight || $font_style) {
					$style = $font_weight . $font_style;
					$families['style'][$style] = $style;
				}

				// set extra styles.
				if (!empty($value['extra-styles'])) {
					foreach ($value['extra-styles'] as $extra_style) {
						$families['style'][$extra_style] = $extra_style;
					}
				}

				// set subsets.
				if (!empty($value['subset'])) {
					$value['subset'] = (is_array($value['subset'])) ? $value['subset'] : array_filter((array) $value['subset']);
					foreach ($value['subset'] as $subset) {
						$families['subset'][$subset] = $subset;
					}
				}

				$all_styles = (!empty($families['style'])) ? ':' . implode(',', $families['style']) : '';
				$all_subsets = (!empty($families['subset'])) ? ':' . implode(',', $families['subset']) : '';

				$families = $this->value['font-family'] . str_replace(array('normal', 'italic'), array('n', 'i'), $all_styles) . $all_subsets;

				$this->parent->typographies[] = $families;

				return $families;

			}

			return false;

		}

		/**
		 * Field output
		 *
		 * @return statement
		 */
		public function output() {

			$output = '';
			$bg_image = array();
			$important = (!empty($this->field['output_important'])) ? '!important' : '';
			$element = (is_array($this->field['output'])) ? join(',', $this->field['output']) : $this->field['output'];

			$font_family = (!empty($this->value['font-family'])) ? $this->value['font-family'] : '';
			$backup_family = (!empty($this->value['backup-font-family'])) ? ', ' . $this->value['backup-font-family'] : '';

			if ($font_family) {
				$output .= 'font-family:"' . $font_family . '"' . $backup_family . $important . ';';
			}

			// Common font properties.
			$properties = array(
				'color',
				'hover_color',
				'active_color',
				'font-weight',
				'font-weights',
				'font-style',
				'font-styles',
				'font-variant',
				'text-align',
				'text-transform',
				'text-decoration',
			);

			foreach ($properties as $property) {
				if (isset($this->value[$property]) && '' !== $this->value[$property]) {
					$output .= $property . ':' . $this->value[$property] . $important . ';';
				}
			}

			$properties = array(
				'font-size',
				'line-height',
				'letter-spacing',
				'word-spacing',
			);

			$unit = (!empty($this->value['unit'])) ? $this->value['unit'] : '';
			$line_height_unit = (!empty($this->value['line_height_unit'])) ? $this->value['line_height_unit'] : $unit;

			foreach ($properties as $property) {
				if (isset($this->value[$property]) && '' !== $this->value[$property]) {
					$unit = ('line-height' === $property) ? $line_height_unit : $unit;
					$output .= $property . ':' . $this->value[$property] . $unit . $important . ';';
				}
			}

			$custom_style = (!empty($this->value['custom-style'])) ? $this->value['custom-style'] : '';

			if ($output) {
				$output = $element . '{' . $output . $custom_style . '}';
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}