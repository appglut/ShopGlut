<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: space
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('AGSHOPGLUT_space')) {
	class AGSHOPGLUT_space extends AGSHOPGLUTP {

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render() {

			$args = wp_parse_args($this->field, array(
				'top' => true,
				'left' => true,
				'bottom' => true,
				'right' => true,
				'unit' => true,
				'show_units' => true,
				'units' => array('px', '%', 'em'),
				'device_type' => array(
					'desktop', 'tablet', 'mobile',
				),
			));

			$default_values = array(
				'unit' => 'px',
			);

			$value = wp_parse_args($this->value, $default_values);
			$unit = (count($args['units']) === 1 && !empty($args['unit'])) ? $args['units'][0] : '';
			$is_unit = (!empty($unit)) ? ' agl--is-unit' : '';

			//print_r($value);

			if (count($args['device_type']) === 3) {

				foreach ($args['device_type'] as $device_type) {

					echo esc_attr($this->field_before());

					echo '<div class="agl--inputs ' . esc_attr($this->field['id']) . ' ' . esc_attr($this->field['id']) . '-select-type-' . esc_attr($device_type) . '">';

					$properties = array();

					foreach (array('top', 'right', 'bottom', 'left') as $prop) {
						if (!empty($args[$prop])) {
							$properties[] = $prop;
						}
					}

					$properties = ($properties === array('right', 'left')) ? array_reverse($properties) : $properties;

					// Radio buttons
					if (!empty($args['unit']) && !empty($args['show_units']) && count($args['units']) > 1) {
						echo '<div class="agl-space-units">';
						foreach ($args['units'] as $unit_option) {
							$checked = (isset($value['unit-' . $device_type])) && ($value['unit-' . $device_type] === $unit_option) ? 'checked="checked"' : '';
							$selected_class = $checked ? 'selected' : '';
							echo '<label class="' . esc_attr($selected_class) . '"> <input type="radio" name="' . esc_attr($this->field_name('[unit-' . $device_type . ']')) . '" value="' . esc_attr($unit_option) . '" data-depend-id="' . esc_attr($this->field['id'] . '-' . $device_type . '-unit') . '" ' . esc_attr($checked) . ' class="agl-hidden-radio" style="display: none;" />' . esc_html($unit_option) . '</label>';
						}
						echo '</div>';
					}

					echo '<div class="agl-space-input">';
					foreach ($properties as $property) {
						$device_property_key = $device_type . '-' . $property;
						$device_property_value = isset($value[$device_property_key]) ? $value[$device_property_key] : '';
						echo '<div class="agl--input">';
						echo '<input type="number" name="' . esc_attr($this->field_name('[' . $device_property_key . ']')) . '" value="' . esc_attr($device_property_value) . '" placeholder="0" class="agl-space-input-number' . esc_attr($is_unit) . '" step="1" min="-50" max="1000" data-depend-id="' . esc_attr($this->field['id']) . '-' . esc_attr($device_property_key) . '" />';
						echo '<span class="agl--label agl--unit">' . esc_attr($property) . '</span>';
						echo '</div>';
					}

					$lock_all_key = $device_type . '-lock-all';
					$lock_all_checked = isset($value[$lock_all_key]) ? 'checked="checked"' : '';

					echo '<label id="lock-space-input" class="lock-all dashicons ' . (isset($value[$lock_all_key]) && $value[$lock_all_key] ? 'dashicons-lock locked' : 'dashicons-unlock unlocked') . '">
                          <input type="checkbox" name="' . esc_attr($this->field_name('[' . $lock_all_key . ']')) . '" ' . esc_attr($lock_all_checked) . ' style="display:none"' . wp_kses_post($this->field_attributes()) . '>
                          </label>';

					echo '</div>';

					echo '</div>';

					echo esc_attr($this->field_after());
				}
			}

		}

	}
}