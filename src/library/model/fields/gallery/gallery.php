<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_gallery' ) ) {
	class AGSHOPGLUT_gallery extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unallows = array( 'gallery' );

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for UI rendering only
			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for UI rendering only
			$editor = isset( $_GET['editor'] ) ? sanitize_text_field( wp_unslash( $_GET['editor'] ) ) : '';

			echo esc_attr( $this->field_before() );

			echo '<div class="agl-gallery-items">';

			foreach ( $this->field['gallerys'] as $key => $gallery ) {

				echo '<div class="agl-gallery-item">';

				$icon = ( ! empty( $gallery['icon'] ) ) ? 'agl--icon ' . $gallery['icon'] : 'agl-gallery-icon fas fa-images';

				// Check for shop or archive editor
				if ( 'shopglut_layouts' === $page && ( 'shop' === $editor || 'archive' === $editor ) ) {
					echo '<h4 class="agl-gallery-title">';
					echo '<i class="' . esc_attr( $icon ) . '"></i>';
					echo esc_html( $gallery['title'] );
					echo '</h4>';
					echo '<div class="agl-gallery-content">';
				}

				if ( 'shopglut_enhancements' === $page && ( 'filters' === $editor) ) {
					$gallery_title = $gallery['title']; // Default fallback

					// Try to get gallery-title from the nested value structure
					// Check if gallery-title is directly available
					if (isset($this->value['gallery-title']) && !empty($this->value['gallery-title'])) {
						$gallery_title = $this->value['gallery-title'];
					}
					// If not, look for it in the processed field values during the loop below
					else {
						// We'll set this in the field processing loop
						$temp_gallery_title = null;
						foreach ( $gallery['fields'] as $temp_field ) {
							if ( isset( $temp_field['id'] ) && $temp_field['id'] === 'shopg-filter-sub-tabbed' ) {
								if ( isset( $this->value[ $temp_field['id'] ]['gallery-title'] ) && !empty( $this->value[ $temp_field['id'] ]['gallery-title'] ) ) {
									$gallery_title = $this->value[ $temp_field['id'] ]['gallery-title'];
									break;
								}
							}
						}
					}

					echo '<h4 class="agl-gallery-title">';
					echo '<i class="' . esc_attr( $icon ) . '"></i>';
					echo esc_html( $gallery_title );
					echo '</h4>';
					echo '<div class="agl-gallery-content">';
				}

				foreach ( $gallery['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
					}

					$field_id = ( isset( $field['id'] ) ) ? $field['id'] : '';

					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					// Handle different editor types
					$show_gallery_title = false;

					if ( 'shopglut_enhancements' === $page && 'filter' === $editor ) {
						$show_gallery_title = true;
					} elseif ( 'shopglut_layouts' === $page && in_array( $editor, array( 'single_product', 'cartpage', 'cartpage' ) ) ) {
						$show_gallery_title = true;
					}

					if ( $show_gallery_title ) {
						echo '<h4 class="agl-gallery-title">';
						echo '<i class="' . esc_attr( $icon ) . '"></i>';
						echo isset( $field_value['gallery-title'] ) ? esc_html( $field_value['gallery-title'] ) : esc_html__( 'Title', 'shopglut' );
						echo '</h4>';
						echo '<div class="agl-gallery-content">';
					}

					AGSHOPGLUT::field( $field, $field_value, $unique_id, 'field/gallery' );

				}

				echo '</div>';

				echo '</div>';

			}

			echo '</div>';

			echo esc_attr( $this->field_after() );

		}

	}
}