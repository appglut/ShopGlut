<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: select_badge_display
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_select_badge_display' ) ) {
	class AGSHOPGLUT_select_badge_display extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'id' => false,
				'placeholder' => '',
				'chosen' => false,
				'multiple' => false,
				'sortable' => false,
				'ajax' => false,
				'settings' => array(),
				'query_args' => array(),
				'active_device' => false,
				'device_type' => array(
					'desktop', 'tablet', 'mobile',
				),
			) );

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				if ( ! empty( $args['ajax'] ) ) {
					$args['settings']['data']['type'] = $args['options'];
					$args['settings']['data']['nonce'] = wp_create_nonce( 'agl_chosen_ajax_nonce' );
					if ( ! empty( $args['query_args'] ) ) {
						$args['settings']['data']['query_args'] = $args['query_args'];
					}
				}

				$chosen_rtl = ( is_rtl() ) ? ' chosen-rtl' : '';
				$multiple_name = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_sortable = ( $args['chosen'] && $args['sortable'] ) ? ' agl-chosen-sortable' : '';
				$chosen_ajax = ( $args['chosen'] && $args['ajax'] ) ? ' agl-chosen-ajax' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_html( $args['placeholder'] ) . '"' : '';
				$field_class = ( $args['chosen'] ) ? ' class="agl-chosen' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
				$field_name = $this->field_name( $multiple_name );
				$field_attr = wp_kses_post( $this->field_attributes() );
				$maybe_options = $this->field['options'];
				$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="' . esc_attr( wp_json_encode( $args['settings'] ) ) . '"' : '';

				if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
					// For AJAX mode, only load titles for selected values
					$options = array();
					if ( ! empty( $this->value ) && is_array( $this->value ) ) {
						foreach ( $this->value as $value ) {
							// Determine the type and get the title
							if ( $value === 'Woo Shop Page' || $value === 'All Categories' || $value === 'All Tags' || $value === 'All Products' ) {
								$options[ $value ] = $value;
							} elseif ( strpos( $value, 'cat_' ) === 0 ) {
								$cat_id = str_replace( 'cat_', '', $value );
								$term = get_term( $cat_id, 'product_cat' );
								if ( $term && ! is_wp_error( $term ) ) {
									$options[ $value ] = 'Category: ' . $term->name;
								}
							} elseif ( strpos( $value, 'tag_' ) === 0 ) {
								$tag_id = str_replace( 'tag_', '', $value );
								$term = get_term( $tag_id, 'product_tag' );
								if ( $term && ! is_wp_error( $term ) ) {
									$options[ $value ] = 'Tag: ' . $term->name;
								}
							} elseif ( strpos( $value, 'product_' ) === 0 ) {
								$product_id = str_replace( 'product_', '', $value );
								$product_title = get_the_title( $product_id );
								if ( $product_title ) {
									$options[ $value ] = 'Product: ' . $product_title;
								}
							}
						}
					}
				} else if ( is_string( $maybe_options ) && $maybe_options === 'select_badge_display' ) {

					// Initialize options with static entries
					$options = array(
						'Woo Shop Page' => 'Woo Shop Page',
						'Single Product Template1' => 'Single Product Template1',
						'All Categories' => 'All Categories',
						'All Tags' => 'All Tags',
						'All Products' => 'All Products',
					);

					// Fetch all WooCommerce product categories
					$product_categories = get_terms( array(
						'taxonomy' => 'product_cat',
						'hide_empty' => false,
					) );

					if ( ! is_wp_error( $product_categories ) ) {
						foreach ( $product_categories as $category ) {
							$options[ 'cat_' . $category->term_id ] = 'Category: ' . $category->name;
						}
					}

					// Fetch all WooCommerce product tags
					$product_tags = get_terms( array(
						'taxonomy' => 'product_tag',
						'hide_empty' => false,
					) );

					if ( ! is_wp_error( $product_tags ) ) {
						foreach ( $product_tags as $tag ) {
							$options[ 'tag_' . $tag->term_id ] = 'Tag: ' . $tag->name;
						}
					}

					// Fetch all products
					$product_query = new WP_Query( array(
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => -1,
					) );

					if ( $product_query->have_posts() ) {
						while ( $product_query->have_posts() ) {
							$product_query->the_post();
							$options[ 'product_' . get_the_ID() ] = 'Product: ' . get_the_title();
						}
						wp_reset_postdata();
					}

					// Get already selected options from other badge posts
					global $wpdb;

					// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check
					$current_post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0;

					// Get all published badge posts except the current one
					$badge_query = new WP_Query( array(
						'post_type' => 'shopglut_badges',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'post__not_in' => array( $current_post_id ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- Necessary to exclude current post
						'fields' => 'ids',
					) );

					$used_options = array();
					if ( $badge_query->have_posts() ) {
						foreach ( $badge_query->posts as $badge_id ) {
							$badge_settings = get_post_meta( $badge_id, 'shopg_product_badge_settings', true );

							// Check for display location settings
							if ( isset( $badge_settings['display-locations'] ) ) {
								$locations = $badge_settings['display-locations'];
								if ( is_array( $locations ) ) {
									$used_options = array_merge( $used_options, $locations );
								}
							}
						}
					}
					wp_reset_postdata();

					// Mark used options as disabled
					$this->used_options = array_unique( $used_options );

				} else if ( is_string( $maybe_options ) ) {
					$options = $this->field_data( $maybe_options, false, $args['query_args'] );
				} else {
					$options = $maybe_options;
				}

				if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {

					if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {

						echo '<select name="' . esc_attr( $field_name ) . '" class="agl-hide-select hidden"' . wp_kses_post( $multiple_attr ) . wp_kses_post( $field_attr ) . ' data-depend-id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '">';
						foreach ( $this->value as $option_key ) {
							echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_attr( $option_key ) . '</option>';
						}
						echo '</select>';

						$field_name = '_pseudo';
						$field_attr = '';

					}

					$selectAttributes = wp_kses_post( $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr );

					if ( ! empty( $args['active_device'] ) && count( $args['device_type'] ) === 3 ) {

						foreach ( $args['device_type'] as $device_type ) {

							$select_id = $args['id'] . '-select-type-' . $device_type;

							echo '<select class="active-device" id="' . esc_attr( $select_id ) . '" name="' . esc_attr( $this->field_name( '[' . $select_id . ']' ) ) . '" data-depend-id="' . esc_attr($args['id']) . '-' . esc_attr($device_type) . '">';

							if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
								if ( ! empty( $args['chosen'] ) ) {
									echo '<option value=""></option>';
								} else {
									echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
								}
							}

							$this->outputOptions( $options, $this->value, $select_id );

							echo '</select>';
						}

					} else {

						echo '<select name="' . esc_attr( $field_name ) . '"' . wp_kses_post( $selectAttributes ) . '>';
						if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
							if ( ! empty( $args['chosen'] ) ) {
								echo '<option value=""></option>';
							} else {
								echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
							}
						}

						$select_id = $args['id'];

						$this->outputOptions( $options, $this->value, $select_id );

						echo '</select>';

					}

				} else {

					echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'shopglut' );

				}

			}

			echo wp_kses_post( $this->field_after() );

		}

		public function outputOptions( $options, $selectedValues, $select_id ) {
			if ( ! empty( $options ) ) {

				foreach ( $options as $option_key => $option ) {

					if ( is_array( $option ) && ! empty( $option ) ) {

						echo '<optgroup label="' . esc_attr( $option_key ) . '">';

						foreach ( $option as $sub_key => $sub_value ) {
							$selected = ( in_array( $sub_key, $selectedValues ) ) ? ' selected' : '';
							// Check if this option is used by another badge
							$disabled = ( isset( $this->used_options ) && in_array( $sub_key, $this->used_options ) ) ? ' disabled' : '';
							$disabled_text = $disabled ? ' (Used by another badge)' : '';
							echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . esc_attr( $disabled ) . '>' . esc_attr( $sub_value . $disabled_text ) . '</option>';
						}

						echo '</optgroup>';

					} else {

						$selected = ( isset( $selectedValues[ $select_id ] ) && ( $selectedValues[ $select_id ] === $option_key ) && ( array_key_exists( $select_id, $selectedValues ) ) ) || ( ! isset( $selectedValues[ $select_id ] ) && in_array( $option_key, $selectedValues ) ) ? ' selected' : '';
						// Check if this option is used by another badge
						$disabled = ( isset( $this->used_options ) && in_array( $option_key, $this->used_options ) ) ? ' disabled' : '';
						$disabled_text = $disabled ? ' (Used by another badge)' : '';
						echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . esc_attr( $disabled ) . '>' . esc_attr( $option . $disabled_text ) . '</option>';
					}

				}
			}
		}

		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}
