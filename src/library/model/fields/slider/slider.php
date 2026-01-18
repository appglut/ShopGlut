<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: slider
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('AGSHOPGLUT_slider')) {
	class AGSHOPGLUT_slider extends AGSHOPGLUTP {

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render() {
			$args = wp_parse_args($this->field, array(
				'max' => 100,
				'min' => 0,
				'step' => 1,
				'slider_value' => true,
				'unit' => true,
				'show_units' => true,
				'units' => array('px', '%', 'em'),
				'active_device' => false,
				'device_type' => array(
					'desktop', 'tablet', 'mobile',
				),
			));

			$unit = (count($args['units']) === 1 && !empty($args['unit'])) ? $args['units'][0] : '';
			$is_unit = (!empty($unit)) ? ' agl--is-unit' : '';

			$default_values = array(
				'slider_value' => '',
				'unit' => 'px',
			);

			$value = wp_parse_args($this->value, $default_values);

			if (($args['active_device'] === true)) {
				echo '<div class="agl-field agl-field-slider">';
				foreach ($args['device_type'] as $device_type) {
					echo esc_attr($this->field_before());

					echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap agl--inputs field-slider-wrap ' . esc_attr($this->field['id']) . '-select-type-' . esc_attr($device_type) . '">';
					echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_' . esc_attr($device_type) . '"></div>';
					echo '<div class="slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

					// Radio buttons
					if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
						foreach ($args['units'] as $unit_option) {
							$checked = (isset($value['unit-' . $device_type]) && ($value['unit-' . $device_type] === $unit_option)) ? 'checked="checked"' : '';
							$selected_class = $checked ? 'selected' : '';
							echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" name="' . esc_attr($this->field_name('[unit-' . $device_type . ']')) . '" data-depend-id="' . esc_attr($this->field['id'] . '-' . $device_type . '-unit') . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
						}
					}

					$this->field['attributes'] = array('data-depend-id' => $this->field['id'] . '-' . $device_type);

					if (!empty($args['slider_value'])) {
						$input_value = isset($value['slider_value_' . $device_type]) ? esc_attr($value['slider_value_' . $device_type]) : esc_attr($this->value);
						$input_attrs = $this->field_attributes(array('class' => 'agl-shopg-slide-input-number' . esc_attr($is_unit)));
						echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . esc_attr($device_type) . '" type="number" name="' . esc_attr($this->field_name('[slider_value_' . esc_attr($device_type) . ']')) . '" value="' . esc_attr($input_value) . '"' . wp_kses_post($input_attrs) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';
					}

					echo '</div>';
					echo '</div>';

					echo esc_attr($this->field_after());
				}
				echo '</div>'; // Close agl-field agl-field-slider wrapper
			} else {
				echo '<div class="agl-field agl-field-slider slider-single">';
				echo '<div id="' . esc_attr($this->field['id']) . '" class="agl--wrap field-slider-wrap slider-' . esc_attr($this->field['id']) . '-select-type_">';
				echo '<div class="agl-slider-ui_' . esc_attr($this->field['id']) . '_"></div>';
				echo '<div class="agl--inputs slider-units" data-depend-id="' . esc_attr($this->field['id']) . '">';

				// Radio buttons
				if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
					foreach ($args['units'] as $unit_option) {
						$saved_unit = isset($this->value[$this->field['id'] . '-unit']) ? $this->value[$this->field['id'] . '-unit'] : (isset($this->field['unit']) ? $this->field['unit'] : 'px');
						$checked = ($saved_unit === $unit_option) ? 'checked="checked"' : '';
						$selected_class = $checked ? 'selected' : '';
						echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" name="' . esc_attr($this->field_name('[' . $this->field['id'] . '-unit]')) . '" value="' . esc_attr($unit_option) . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
					}
				}

				// Number input
				if (!empty($args['slider_value'])) {
					$value = isset($this->value[$this->field['id']]) ? esc_attr($this->value[$this->field['id']]) : (isset($this->field['default']) ? esc_attr($this->field['default']) : '');
					$input_attrs = $this->field_attributes(array('class' => 'agl-woo-input-number' . esc_attr($is_unit)));
					echo '<input id="slider_value_' . esc_attr($this->field['id']) . '_' . '" type="number" name="' . esc_attr($this->field_name('[' . $this->field['id'] . ']')) . '" value="' . esc_attr($value) . '"' . wp_kses_post($input_attrs) . ' data-min="' . esc_attr($args['min']) . '" data-max="' . esc_attr($args['max']) . '" data-step="' . esc_attr($args['step']) . '" step="any" />';

				}

				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}

		public function enqueue() {

			if (!wp_script_is('jquery-ui-slider')) {
				wp_enqueue_script('jquery-ui-slider');
			}

		}

		public function output() {

			$output = '';
			$elements = (is_array($this->field['output'])) ? $this->field['output'] : array_filter((array) $this->field['output']);
			$important = (!empty($this->field['output_important'])) ? '!important' : '';
			$mode = (!empty($this->field['output_mode'])) ? $this->field['output_mode'] : 'width';
			$unit = (!empty($this->field['unit'])) ? $this->field['unit'] : 'px';

			if (!empty($elements) && isset($this->value) && $this->value !== '') {
				foreach ($elements as $key_property => $element) {
					if (is_numeric($key_property)) {
						if ($mode) {
							$output = implode(',', $elements) . '{' . $mode . ':' . $this->value . $unit . $important . ';}';
						}
						break;
					} else {
						$output .= $element . '{' . $key_property . ':' . $this->value . $unit . $important . '}';
					}
				}
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}