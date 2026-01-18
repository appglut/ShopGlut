<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: section
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_Field_section' ) ) {
	class AGSHOPGLUT_section extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unallows = array( 'section' );

			echo esc_attr( $this->field_before() );

			foreach ( $this->field['sections'] as $key => $tab ) {

				// Start section content
				echo '<div class="agl-section-content ' . esc_attr( $tab['id'] ) . '">';

				foreach ( $tab['fields'] as $field ) {

					// Check if the field type is 'accordion'
					if ( 'accordion' === $field['type'] ) {
						// Render accordion
						echo '<div class="agl-field agl-field-accordion">';

						echo '<div class="agl-accordion-items">';
						// Loop through each accordion and render the individual accordion items
						foreach ( $field['accordions'] as $accordion ) {
							echo '<div class="agl-accordion-item">';

							// Accordion title (with icon if exists)
							echo '<div class="agl-accordion-title">';
							if ( isset( $accordion['icon'] ) ) {
								echo '<i class="' . esc_attr( $accordion['icon'] ) . '"></i>';
							}
							echo esc_html( $accordion['title'] ) . '</div>';

							// Fields within the accordion
							echo '<div class="agl-accordion-content">';
							foreach ( $accordion['fields'] as $accordion_field ) {

								$field_id = isset( $accordion_field['id'] ) ? $accordion_field['id'] : '';
								$field_default = isset( $accordion_field['default'] ) ? $accordion_field['default'] : '';
								$field_value = isset( $this->value[ $field_id ] ) ? $this->value[ $field_id ] : $field_default;
								$unique_id = ! empty( $this->unique ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

								// Render the individual field within the accordion
								AGSHOPGLUT::field( $accordion_field, $field_value, $unique_id, 'field/section' );

							}
							echo '</div>'; // Close accordion content

							echo '</div>'; // Close accordion item
						}

						echo '</div>'; // Close accordion container
						echo '</div>'; // Close accordion container
					} else {
						// Render regular field (non-accordion)
						if ( in_array( $field['type'], $unallows ) ) {
							$field['_notice'] = true;
						}

						$field_id = isset( $field['id'] ) ? $field['id'] : '';
						$field_default = isset( $field['default'] ) ? $field['default'] : '';
						$field_value = isset( $this->value[ $field_id ] ) ? $this->value[ $field_id ] : $field_default;
						$unique_id = ! empty( $this->unique ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

						// Render the field as normal
						AGSHOPGLUT::field( $field, $field_value, $unique_id, 'field/section' );
					}
				}

				echo '</div>'; // Close section content
			}

			echo esc_attr( $this->field_after() );

		}


	}
}
