<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_accordion' ) ) {
	class AGSHOPGLUT_accordion extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unallows = array( 'accordion' );

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for UI rendering only
			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for UI rendering only
			$editor = isset( $_GET['editor'] ) ? sanitize_text_field( wp_unslash( $_GET['editor'] ) ) : '';

			echo esc_attr( $this->field_before() );

			echo '<div class="agl-accordion-items">';

			foreach ( $this->field['accordions'] as $key => $accordion ) {

				echo '<div class="agl-accordion-item">';

				$icon = ( ! empty( $accordion['icon'] ) ) ? 'agl--icon ' . $accordion['icon'] : 'agl-accordion-icon fas fa-angle-right';

				// Check for shop or archive editor
				if ( 'shopglut_layouts' === $page && ( 'shop' === $editor || 'archive' === $editor ) ) {
					echo '<h4 class="agl-accordion-title">';
					echo '<i class="' . esc_attr( $icon ) . '"></i>';
					echo esc_html( $accordion['title'] );
					echo '</h4>';
					echo '<div class="agl-accordion-content">';
				}

				if ( 'shopglut_enhancements' === $page && ( 'filters' === $editor) ) {
					$accordion_title = $accordion['title']; // Default fallback

					// Try to get accordion-title from the nested value structure
					// Check if accordion-title is directly available
					if (isset($this->value['accordion-title']) && !empty($this->value['accordion-title'])) {
						$accordion_title = $this->value['accordion-title'];
					}
					// If not, look for it in the processed field values during the loop below
					else {
						// We'll set this in the field processing loop
						$temp_accordion_title = null;
						foreach ( $accordion['fields'] as $temp_field ) {
							if ( isset( $temp_field['id'] ) && $temp_field['id'] === 'shopg-filter-sub-tabbed' ) {
								if ( isset( $this->value[ $temp_field['id'] ]['accordion-title'] ) && !empty( $this->value[ $temp_field['id'] ]['accordion-title'] ) ) {
									$accordion_title = $this->value[ $temp_field['id'] ]['accordion-title'];
									break;
								}
							}
						}
					}

					echo '<h4 class="agl-accordion-title">';
					echo '<i class="' . esc_attr( $icon ) . '"></i>';
					echo esc_html( $accordion_title );
					echo '</h4>';
					echo '<div class="agl-accordion-content">';
				}

				foreach ( $accordion['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
					}

					$field_id = ( isset( $field['id'] ) ) ? $field['id'] : '';

					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					// Handle different editor types
					$show_accordion_title = false;
					
					if ( 'shopglut_enhancements' === $page && 'filter' === $editor ) {
						$show_accordion_title = true;
					} elseif ( 'shopglut_layouts' === $page && in_array( $editor, array( 'single_product', 'cartpage', 'cartpage' ) ) ) {
						$show_accordion_title = true;
					}

					if ( $show_accordion_title ) {
						echo '<h4 class="agl-accordion-title">';
						echo '<i class="' . esc_attr( $icon ) . '"></i>';
						echo isset( $field_value['accordion-title'] ) ? esc_html( $field_value['accordion-title'] ) : esc_html__( 'Title', 'shopglut' );
						echo '</h4>';
						echo '<div class="agl-accordion-content">';
					}

					AGSHOPGLUT::field( $field, $field_value, $unique_id, 'field/accordion' );

				}

				echo '</div>';

				echo '</div>';

			}

			echo '</div>';

			echo esc_attr( $this->field_after() );

		}

	}
}