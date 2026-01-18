<?php
if ( ! class_exists( 'AGSHOPGLUT_tax_color' ) ) {
	class AGSHOPGLUT_tax_color extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$taxonomy = $this->field['product_option'] ?? '';
			$unique_modal_id = esc_attr( $this->unique . $this->field['id'] ); // Unique ID for each field instance

			// Render main field with a button to open modal
			echo '<div class="agshopglut-term-field">';
			echo '<button type="button" class="button button-primary open-modal" data-modal-id="modal-' . esc_attr($unique_modal_id) . '">' . esc_html__( 'Select Color for Terms', 'shopglut' ) . '</button>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[product_option]' ) ) . '" value="' . esc_attr( $taxonomy ) . '" />';

			echo '<div class="agshopglut-modal-overlay"></div>';

			// Modal structure with unique ID
			echo '<div id="modal-' . esc_attr($unique_modal_id) . '" class="agshopglut-modal">';
			echo '<div class="modal-content">';
			echo '<span class="close" data-modal-id="modal-' . esc_attr($unique_modal_id) . '">&times;</span>';
			echo '<h3>' . esc_html__( 'Add Colors for Terms', 'shopglut' ) . '</h3>';
			echo '<div class="save_images_message success_message"></div>';

			// Render terms with media buttons if the selected option is a valid taxonomy
			if ( $this->is_valid_woocommerce_taxonomy( $taxonomy ) ) {
				$this->render_terms_with_media_buttons( $taxonomy );
			} else {
				echo '<p>' . esc_html__( 'Invalid taxonomy selected.', 'shopglut' ) . '</p>';
			}

			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		/**
		 * Check if a taxonomy is a valid WooCommerce taxonomy or custom taxonomy.
		 */
		private function is_valid_woocommerce_taxonomy( $taxonomy ) {
			$woocommerce_taxonomies = array(
				'product_cat',
				'product_tag',
				'product_type',
				'product_shipping_class',
			);

			return taxonomy_exists( $taxonomy ) || in_array( $taxonomy, $woocommerce_taxonomies, true );
		}

		/**
		 * Render terms with media upload buttons for a given taxonomy.
		 */
		private function render_terms_with_media_buttons( $taxonomy ) {
			$repeatable_id_string = preg_replace( '/[\[\]]+/', '_', $this->unique ); // Unique string for each repeatable field

			$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );

			// Start rendering the terms list
			echo '<div class="terms-list">';

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_id = $term->term_id;

					// Fetch the specific color value for this repeatable_id and term_id
					$color_value = $this->value['tax_color'][ $repeatable_id_string ][ $term_id ] ?? ''; // Fetch the color value

					echo '<div class="term-item">';
					echo '<label>' . esc_html( $term->name ) . '</label>';

					// Color picker field for each term
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[tax_color][' . $repeatable_id_string . '][' . $term_id . ']' ) ) . '" value="' . esc_attr( $color_value ) . '" class="agl-color" data-term-id="' . esc_attr( $term_id ) . '" data-repeatable-id="' . esc_attr( $repeatable_id_string ) . '" />';

					echo '</div>';
				}
			} else {
				echo '<p>' . esc_html__( 'No terms found for this taxonomy.', 'shopglut' ) . '</p>';
			}

			echo '<input type="submit" id="save_filter_images" class="button button-primary" value="' . esc_html__( 'Save Colors', 'shopglut' ) . '">';
			echo '</div>';
		}





	}
}