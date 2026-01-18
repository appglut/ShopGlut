<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: swatch_apply_selective
 *
 * Selective application field for product swatches
 * Allows selecting specific shop page, archive pages, and individual products
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_swatch_apply_selective' ) ) {
	class AGSHOPGLUT_swatch_apply_selective extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'placeholder' => __( 'Select pages and products...', 'shopglut' ),
				'chosen' => true,
				'multiple' => true,
				'sortable' => false,
				'ajax' => false,
				'settings' => array(),
				'query_args' => array(),
			) );

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			// Generate unique ID and class for this instance
			$unique_instance_id = 'swatch-apply-selective-' . uniqid();
			$chosen_rtl = ( is_rtl() ) ? ' swatch-chosen-rtl' : '';
			$multiple_name = ( $args['multiple'] ) ? '[]' : '';
			$multiple_attr = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
			$chosen_sortable = ( $args['chosen'] && $args['sortable'] ) ? ' swatch-chosen-sortable' : '';
			$chosen_ajax = ( $args['chosen'] && $args['ajax'] ) ? ' swatch-chosen-ajax' : '';
			$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_html( $args['placeholder'] ) . '"' : '';
			$field_class = ( $args['chosen'] ) ? ' class="swatch-chosen-select swatch-apply-selective-field' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
			$field_name = $this->field_name( $multiple_name );
			$field_attr = wp_kses_post( $this->field_attributes() );
			$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-swatch-chosen-settings="' . esc_attr( wp_json_encode( $args['settings'] ) ) . '"' : '';

			// Build options array with grouped sections
			$options = array();

			// Section 1: Shop Page
			$options['shop_page'] = __( 'Shop Page', 'shopglut' );

			// Section 2: Archive Pages
			// Product Categories
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

			// Product Tags
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

			// Product Attributes
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

			// Section 3: Individual Products
			$product_query = new WP_Query( array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
			) );

			if ( $product_query->have_posts() ) {
				while ( $product_query->have_posts() ) {
					$product_query->the_post();
					$product_id = get_the_ID();
					$product_title = get_the_title();
					$options[ 'product_' . $product_id ] = sprintf(
						__( 'Product: %s', 'shopglut' ),
						$product_title . ' (#' . $product_id . ')'
					);
				}
				wp_reset_postdata();
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

				// Field description
				echo '<div class="swatch-apply-selective-wrapper">';
				echo '<div class="swatch-apply-selective-header">';
				echo '<div class="swatch-apply-selective-title">' . esc_html__( 'Apply to Specific Pages & Products', 'shopglut' ) . '</div>';
				echo '<div class="swatch-apply-selective-desc">' . esc_html__( 'Choose specific pages, archives, or products where swatches should be applied.', 'shopglut' ) . '</div>';
				echo '</div>';

				echo '<select id="' . esc_attr( $unique_instance_id ) . '" name="' . esc_attr( $field_name ) . '"' . wp_kses_post( $selectAttributes ) . '>';

				if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
					if ( ! empty( $args['chosen'] ) ) {
						echo '<option value=""></option>';
					} else {
						echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
					}
				}

				// Group options by type for better UX
				$this->output_option_group( $options, $this->value, 'shop', __( 'Shop Page', 'shopglut' ) );
				$this->output_option_group( $options, $this->value, 'cat_', __( 'Product Categories', 'shopglut' ) );
				$this->output_option_group( $options, $this->value, 'tag_', __( 'Product Tags', 'shopglut' ) );
				$this->output_option_group( $options, $this->value, 'attr_', __( 'Product Attributes', 'shopglut' ) );
				$this->output_option_group( $options, $this->value, 'product_', __( 'Individual Products', 'shopglut' ) );

				echo '</select>';

				// Help text
				echo '<div class="swatch-apply-selective-help">';
				echo '<span class="dashicons dashicons-editor-help"></span>';
				echo '<span>' . esc_html__( 'Select multiple items to apply swatches to specific locations only.', 'shopglut' ) . '</span>';
				echo '</div>';

				echo '</div>'; // .swatch-apply-selective-wrapper

			} else {
				echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No pages or products available.', 'shopglut' );
			}

			echo wp_kses_post( $this->field_after() );

			// Add inline script to initialize chosen
			if ( $args['chosen'] ) {
				echo '<script>
				jQuery(document).ready(function($) {
					$("#' . esc_js( $unique_instance_id ) . '").on("chosen:ready", function(){
						$(this).next(".chosen-container").removeAttr("id").addClass("swatch-chosen-container");
					}).chosen({
						max_selected_options: 100
					});
				});
				</script>';
			}

			// Inline styles
			echo '<style>
				.swatch-apply-selective-wrapper {
					padding: 15px;
					background: #f9fafb;
					border-radius: 8px;
					border: 1px solid #e5e7eb;
				}
				.swatch-apply-selective-header {
					margin-bottom: 12px;
				}
				.swatch-apply-selective-title {
					font-weight: 600;
					font-size: 14px;
					color: #374151;
					margin-bottom: 4px;
				}
				.swatch-apply-selective-desc {
					font-size: 13px;
					color: #6b7280;
					line-height: 1.4;
				}
				.swatch-apply-selective-help {
					display: flex;
					align-items: center;
					gap: 6px;
					font-size: 12px;
					color: #6b7280;
					margin-top: 10px;
					padding-top: 10px;
					border-top: 1px solid #e5e7eb;
				}
				.swatch-apply-selective-help .dashicons {
					font-size: 16px;
					color: #3b82f6;
				}
				.swatch-apply-selective-field {
					width: 100%;
				}
			</style>';
		}

		private function output_option_group( $options, $selected_values, $prefix, $group_label ) {
			$group_options = array();

			foreach ( $options as $key => $label ) {
				if ( strpos( $key, $prefix ) === 0 || ( $prefix === 'shop' && $key === 'shop_page' ) ) {
					$group_options[ $key ] = $label;
				}
			}

			if ( ! empty( $group_options ) ) {
				echo '<optgroup label="' . esc_attr( $group_label ) . '">';
				foreach ( $group_options as $option_key => $option ) {
					$is_selected = ( in_array( $option_key, $selected_values ) ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $is_selected ) . '>' . esc_html( $option ) . '</option>';
				}
				echo '</optgroup>';
			}
		}

		public function enqueue() {
			// Chosen is already loaded by the framework
		}
	}
}
