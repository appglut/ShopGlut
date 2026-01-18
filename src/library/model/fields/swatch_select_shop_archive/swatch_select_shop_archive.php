<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: swatch_select_shop_archive
 *
 * Product swatches shop/archive selector field
 * Allows selecting which shop/archive pages to apply swatches to
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_swatch_select_shop_archive' ) ) {
	class AGSHOPGLUT_swatch_select_shop_archive extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'placeholder' => __( 'Select Pages', 'shopglut' ),
				'chosen' => true,
				'multiple' => true,
				'sortable' => false,
				'ajax' => false,
				'settings' => array(),
				'query_args' => array(),
			) );

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			// Generate unique ID and class for this instance to avoid conflicts
			$unique_instance_id = 'swatch-shop-archive-select-' . uniqid();
			$chosen_rtl = ( is_rtl() ) ? ' swatch-chosen-rtl' : '';
			$multiple_name = ( $args['multiple'] ) ? '[]' : '';
			$multiple_attr = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
			$chosen_sortable = ( $args['chosen'] && $args['sortable'] ) ? ' swatch-chosen-sortable' : '';
			$chosen_ajax = ( $args['chosen'] && $args['ajax'] ) ? ' swatch-chosen-ajax' : '';
			$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_html( $args['placeholder'] ) . '"' : '';
			$field_class = ( $args['chosen'] ) ? ' class="swatch-chosen-select swatch-shop-archive-selector' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
			$field_name = $this->field_name( $multiple_name );
			$field_attr = wp_kses_post( $this->field_attributes() );
			$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-swatch-chosen-settings="' . esc_attr( wp_json_encode( $args['settings'] ) ) . '"' : '';

			// Get all product categories, taxonomies, and shop pages
			$options = array();

			// Shop page options
			$options['shop_page'] = __( 'Shop Page', 'shopglut' );

			// Archive pages options
			$product_categories = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => false,
			) );

			if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
				foreach ( $product_categories as $category ) {
					$options[ 'cat_' . $category->term_id ] = sprintf(
						__( 'Category: %s', 'shopglut' ),
						$category->name
					);
				}
			}

			// Product tags
			$product_tags = get_terms( array(
				'taxonomy' => 'product_tag',
				'hide_empty' => false,
			) );

			if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) {
				foreach ( $product_tags as $tag ) {
					$options[ 'tag_' . $tag->term_id ] = sprintf(
						__( 'Tag: %s', 'shopglut' ),
						$tag->name
					);
				}
			}

			// Product attributes
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attr ) {
					$taxonomy_name = wc_attribute_taxonomy_name( $attr->attribute_name );
					$options[ 'attr_' . $taxonomy_name ] = sprintf(
						__( 'Attribute: %s', 'shopglut' ),
						$attr->attribute_label
					);
				}
			}

			if ( is_array( $options ) && ! empty( $options ) ) {

				if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {

					echo '<select name="' . esc_attr( $field_name ) . '" class="swatch-hide-select hidden"' . wp_kses_post( $multiple_attr ) . wp_kses_post( $field_attr ) . '>';
					foreach ( $this->value as $option_key ) {
						echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_attr( $option_key ) . '</option>';
					}
					echo '</select>';

					$field_name = '_pseudo';
					$field_attr = '';
				}

				$selectAttributes = wp_kses_post( $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr );

				echo '<select id="' . esc_attr( $unique_instance_id ) . '" name="' . esc_attr( $field_name ) . '"' . wp_kses_post( $selectAttributes ) . '>';

				if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
					if ( ! empty( $args['chosen'] ) ) {
						echo '<option value=""></option>';
					} else {
						echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
					}
				}

				foreach ( $options as $option_key => $option ) {
					$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $option ) . '</option>';
				}

				echo '</select>';

			} else {
				echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No shop/archive pages available.', 'shopglut' );
			}

			echo wp_kses_post( $this->field_after() );

			// Add inline script to initialize chosen with unique selector
			if ( $args['chosen'] ) {
				echo '<script>
				jQuery(document).ready(function($) {
					$("#' . esc_js( $unique_instance_id ) . '").on("chosen:ready", function(){
						// Remove the chosen-container from parent to avoid conflicts
						$(this).next(".chosen-container").removeAttr("id").addClass("swatch-chosen-container");
					}).chosen();
				});
				</script>';
			}
		}

		public function enqueue() {
			// Chosen is already loaded by the framework
		}
	}
}
