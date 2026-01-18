<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: attribute_terms
 *
 * Renders WooCommerce attribute terms for display
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_attribute_terms' ) ) {
	class AGSHOPGLUT_attribute_terms extends AGSHOPGLUTP {

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
				echo '<div class="attribute-terms-no-attribute">';
				echo '<p>' . esc_html__( 'No attribute assigned to this layout. Please assign an attribute from the Product Swatches page.', 'shopglut' ) . '</p>';
				echo '</div>';
				echo wp_kses_post( $this->field_after() );
				return;
			}

			$terms = $this->get_attribute_terms( $assigned_attribute );

			// Ensure value is an array
			$term_values = is_array( $this->value ) ? $this->value : array();

			?>
		<div class="terms-wrapper">
			<h4>
				<?php
				$attribute_label = $this->get_attribute_label( $assigned_attribute );
				printf(
					/* translators: %s: attribute label */
					esc_html__( 'Available Terms for: %s', 'shopglut' ),
					'<span class="attribute-name">' . esc_html( $attribute_label ) . '</span>'
				);
				?>
			</h4>

			<?php if ( empty( $terms ) || is_wp_error( $terms ) ) : ?>
				<div class="attribute-terms-empty">
					<p><?php esc_html_e( 'No terms found for this attribute.', 'shopglut' ); ?></p>
				</div>
			<?php else : ?>
				<div class="terms-grid">
				<?php foreach ( $terms as $term ) :
					$term_slug = $term->slug;
					$term_name = $term->name;
					?>
					<div class="term-item" data-term-slug="<?php echo esc_attr( $term_slug ); ?>">
						<?php echo esc_html( $term_name ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

			<style>
			.terms-wrapper h4 {
				margin: 0 0 12px 0;
				font-size: 14px;
				font-weight: 600;
				color: #1d2327;
			}

			.terms-wrapper .attribute-name {
				color: #6366f1;
				font-weight: 700;
			}

			.terms-grid {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
			}

			.term-item {
				padding: 6px 12px;
				background: #f3f4f6;
				border: 1px solid #d1d5db;
				border-radius: 4px;
				font-size: 13px;
				font-weight: 500;
				color: #374151;
				transition: all 0.2s;
			}

			.term-item:hover {
				background: #e5e7eb;
				border-color: #6366f1;
			}

			.attribute-terms-no-attribute,
			.attribute-terms-empty {
				padding: 20px;
				text-align: center;
				background: #fff;
				border-radius: 6px;
				border: 1px dashed #c3c4c7;
			}

			.attribute-terms-no-attribute p,
			.attribute-terms-empty p {
				margin: 0;
				color: #646970;
				font-size: 13px;
			}
		</style>

			<?php

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Output CSS - not needed for this field
		 */
		public function output() {
			return '';
		}
	}
}
