<?php

use Shopglut\layouts\singleProduct\dataManage as SingleProductDataManage;
use Shopglut\layouts\cartPage\dataManage as CartDataManage;
use Shopglut\layouts\orderCompletePage\dataManage as OrderCompleteDataManage;
use Shopglut\layouts\accountPage\AccountPageDataManage;
use Shopglut\enhancements\ProductComparison\ProductComparisonDataManage;
use Shopglut\enhancements\ProductQuickView\QuickViewDataManage;
use Shopglut\showcases\Sliders\SliderDataManage;
use Shopglut\enhancements\ProductBadges\BadgeDataManage;
use Shopglut\enhancements\ProductSwatches\dataManage as ProductSwatchesDataManage;

use Shopglut\showcases\ShopBanner\templates\template1\template1Markup;
use Shopglut\showcases\Sliders\templates\template1\template1Markup as Slider1Markup;
use Shopglut\showcases\Gallery\templates\template1\template1Markup as GalleryTemplate1Markup;
use Shopglut\showcases\Tabs\templates\template1\template1Markup as TabsTemplate1Markup;
use Shopglut\showcases\Accordions\templates\template1\template1Markup as AccordionTemplate1Markup; 


if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 *
 * Field: Preview
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT_preview' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 2.0.15
	 * @version 2.0.15
	 */
	class AGSHOPGLUT_preview extends AGSHOPGLUTP {

		//use Shopglut\enhancements\Filters\FilterTrait;


		/**
		 * Shortcode field constructor.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'shop' === $_GET['editor'] ) {
				?>
				<div class="shopg_shop_layout_contents">
					<div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
						<?php

						global $wpdb;

						$layout_id = ! wp_verify_nonce( isset( $_POST['preview_nonce_check'] ), 'preview_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

						$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for layout preview
						$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", ! wp_verify_nonce( isset( $_POST['preview_nonce_check'] ), 'preview_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1 ) );

						$layout_array_values = isset( $layout_values[0]->layout_settings ) ? unserialize( $layout_values[0]->layout_settings ) : array();

						$included_products =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products']
							: array();
						$excluded_products =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products']
							: array();
						$included_categories =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories']
							: array();
						$product_filter_option =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options']
							: 'all-products';
						$product_sorting =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting']
							: 'date';
						$product_order =
							isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] )
							?
							$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order']
							: 'ASC';

						$included_products = array_diff( $included_products, $excluded_products );

						$pagination_product_number = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] : 15;

						$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] : 'pagination-number';

						$args = array(
							'post_type' => 'product',
							'posts_per_page' => $pagination_product_number,
							'orderby' => $product_sorting,
							'order' => $product_order,
							'paged' => 1,
						);

						$meta_key = '';
						switch ( $product_sorting ) {
							case 'price_low_to_high':
							case 'price_high_to_low':
								$args['orderby'] = 'meta_value_num';
								$meta_key = '_price';
								break;
							case 'sales':
								$args['orderby'] = 'meta_value_num';
								$meta_key = 'total_sales';
								break;
							case 'ratings':
								$args['orderby'] = 'meta_value_num';
								$meta_key = '_wc_average_rating';
								break;
							case 'featured':
								$args['orderby'] = 'meta_value_num';
								$meta_key = '_featured';
								break;
							case 'popularity':
								$args['orderby'] = 'meta_value_num';
								$meta_key = 'product_views';
								break;
							case 'stock_quantity':
								$args['orderby'] = 'meta_value_num';
								$meta_key = '_stock';
								break;
							case 'reviews_count':
								$args['orderby'] = 'meta_value_num';
								$meta_key = '_wc_review_count';
								break;
							case 'random':
								$args['orderby'] = 'rand';
								break;
						}

						if ( ! empty( $meta_key ) ) {
							// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required for product sorting by meta fields with type optimization
							$args['meta_key'] = $meta_key;
							// Add meta_type for better performance
							if ( in_array( $meta_key, array( '_price', '_stock', 'total_sales', '_wc_average_rating', 'product_views', '_wc_review_count' ) ) ) {
								$args['meta_type'] = in_array( $meta_key, array( '_price', '_wc_average_rating' ) ) ? 'DECIMAL' : 'NUMERIC';
							}
						}

						switch ( $product_filter_option ) {
							case 'best-selling':
								// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required for best-selling product sorting with numeric optimization
								$args['meta_key'] = 'total_sales';
								$args['orderby'] = 'meta_value_num';
								$args['meta_type'] = 'NUMERIC';
								break;
							case 'recent-products':
								$args['orderby'] = 'date';
								$args['order'] = 'DESC';
								break;
							case 'featured-products':
								$args['tax_query'][] = array(
									'taxonomy' => 'product_visibility',
									'field' => 'name',
									'terms' => 'featured',
									'operator' => 'IN',
								);
								break;
							case 'rated-products':
								// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required for rating-based product sorting with decimal optimization
								$args['meta_key'] = '_wc_average_rating';
								$args['orderby'] = 'meta_value_num';
								$args['meta_type'] = 'DECIMAL';
								break;
							case 'sale-products':
								$args['meta_query'][] = array(
									'key' => '_sale_price',
									'value' => 0,
									'compare' => '>',
									'type' => 'NUMERIC',
								);
								break;
							case 'in-stock':
								$args['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => 'instock',
									'compare' => '=',
								);
								break;
							case 'out-of-stock':
								$args['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => 'outofstock',
									'compare' => '=',
								);
								break;
							case 'all-products':
							default:
								break;
						}

						if ( ! empty( $included_products ) ) {
							$args['post__in'] = $included_products;
						}

						// Use post__not_in only for small exclusion lists to avoid performance issues
						if ( ! empty( $excluded_products ) && count( $excluded_products ) < 100 ) {
							// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- Limited to small lists for performance
							$args['post__not_in'] = $excluded_products;
						}

						if ( ! empty( $included_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $included_categories,
							);
						}

						$query = new \WP_Query( $args );

						$file_included = false;

						?>

						<div
							class="shopg_shop_layouts column row <?php echo isset( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) ? esc_html( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) : 'col-3' ?>">

							<?php

							if ( $query->have_posts() ) {
								while ( $query->have_posts() ) {
									$query->the_post();

									$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;

									if ( class_exists( $layout_class ) ) {
										$layout_instance = new $layout_class();
										$layout_instance->layout_render( $layout_array_values );
										$file_included = true;
									}
								}
							}

							?>

						</div>
						<?php

						?>
						<div id="no-product-found" style="display:none;"><?php echo esc_html__( 'No Product Found', 'shopglut' ); ?>

							<?php

							if ( ! $file_included ) {
								echo esc_html__( 'Layout file not found', 'shopglut' );
							}
							?>
						</div>
					</div>
					<?php
					wp_reset_postdata();
			}
			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'archive' === $_GET['editor'] ) {
				?>
					<div class="shopg_shop_layout_contents">
						<div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
							<?php

							global $wpdb;

							$layout_id = ! wp_verify_nonce( isset( $_POST['preview_nonce_check'] ), 'preview_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

							$table_name = $wpdb->prefix . 'shopglut_archive_layouts';

							// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for archive layout preview
							$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_archive_layouts WHERE id = %d", ! wp_verify_nonce( isset( $_POST['preview_nonce_check'] ), 'preview_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1 ) );

							$layout_array_values = unserialize( $layout_values[0]->arlayout_settings );

							$included_products =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products']
								: array();
							$excluded_products =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products']
								: array();
							$included_categories =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories']
								: array();
							$product_filter_option =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options']
								: 'all-products';
							$product_sorting =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting']
								: 'date';
							$product_order =
								isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] )
								?
								$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order']
								: 'ASC';

							$included_products = array_diff( $included_products, $excluded_products );

							$pagination_product_number = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] : 15;

							$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] : 'pagination-number';

							$args = array(
								'post_type' => 'product',
								'posts_per_page' => $pagination_product_number,
								'orderby' => $product_sorting,
								'order' => $product_order,
								'paged' => 1,
							);

							$meta_key = '';
							switch ( $product_sorting ) {
								case 'price_low_to_high':
								case 'price_high_to_low':
									$args['orderby'] = 'meta_value_num';
									$meta_key = '_price';
									break;
								case 'sales':
									$args['orderby'] = 'meta_value_num';
									$meta_key = 'total_sales';
									break;
								case 'ratings':
									$args['orderby'] = 'meta_value_num';
									$meta_key = '_wc_average_rating';
									break;
								case 'featured':
									$args['orderby'] = 'meta_value_num';
									$meta_key = '_featured';
									break;
								case 'popularity':
									$args['orderby'] = 'meta_value_num';
									$meta_key = 'product_views';
									break;
								case 'stock_quantity':
									$args['orderby'] = 'meta_value_num';
									$meta_key = '_stock';
									break;
								case 'reviews_count':
									$args['orderby'] = 'meta_value_num';
									$meta_key = '_wc_review_count';
									break;
								case 'random':
									$args['orderby'] = 'rand';
									break;
							}

							if ( ! empty( $meta_key ) ) {
								// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Necessary for product filtering
								$args['meta_key'] = $meta_key;
							}

							switch ( $product_filter_option ) {
								case 'best-selling':
									// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required for best-selling products query
									$args['meta_key'] = 'total_sales';
									$args['orderby'] = 'meta_value_num';
									break;
								case 'recent-products':
									$args['orderby'] = 'date';
									$args['order'] = 'DESC';
									break;
								case 'featured-products':
									$args['tax_query'][] = array(
										'taxonomy' => 'product_visibility',
										'field' => 'name',
										'terms' => 'featured',
										'operator' => 'IN',
									);
									break;
								case 'rated-products':
									// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required for rating-based product query
									$args['meta_key'] = '_wc_average_rating';
									$args['orderby'] = 'meta_value_num';
									break;
								case 'sale-products':
									$args['meta_query'][] = array(
										'key' => '_sale_price',
										'value' => 0,
										'compare' => '>',
										'type' => 'NUMERIC',
									);
									break;
								case 'in-stock':
									$args['meta_query'][] = array(
										'key' => '_stock_status',
										'value' => 'instock',
										'compare' => '=',
									);
									break;
								case 'out-of-stock':
									$args['meta_query'][] = array(
										'key' => '_stock_status',
										'value' => 'outofstock',
										'compare' => '=',
									);
									break;
								case 'all-products':
								default:
									break;
							}

							if ( ! empty( $included_products ) ) {
								$args['post__in'] = $included_products;
							}

							if ( ! empty( $excluded_products ) ) {
								// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- Necessary for product exclusion filtering
								$args['post__not_in'] = $excluded_products;
							}

							if ( ! empty( $included_categories ) ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'product_cat',
									'field' => 'term_id',
									'terms' => $included_categories,
								);
							}

							$query = new \WP_Query( $args );

							$file_included = false;

							?>

							<div
								class="shopg_shop_layouts column row <?php echo isset( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) ? esc_html( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) : 'col-3' ?>">

								<?php

								if ( $query->have_posts() ) {
									while ( $query->have_posts() ) {
										$query->the_post();

										$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->arlayout_template;

										if ( class_exists( $layout_class ) ) {
											$layout_instance = new $layout_class();
											$layout_instance->layout_render( $layout_array_values );
											$file_included = true;
										}
									}
								}

								?>

							</div>
							<?php

							?>
							<div id="no-product-found" style="display:none;"><?php echo esc_html__( 'No Product Found', 'shopglut' ); ?>

								<?php

								if ( ! $file_included ) {
									echo esc_html__( 'Layout file not found', 'shopglut' );
								}
								?>
							</div>
						</div>
						<?php
						wp_reset_postdata();
			}

			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && isset( $_GET['editor'] ) && 'filters' === $_GET['editor'] ) {

			// Check if filter_id is set and fetch the corresponding data
			$filter_id = isset( $_GET['filter_id'] ) ? intval( $_GET['filter_id'] ) : 0;

			// Retrieve the saved filter data from the database based on filter_id
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for filter preview
			$get_data = $wpdb->get_var( $wpdb->prepare( "SELECT filter_settings FROM {$wpdb->prefix}shopglut_enhancement_filters WHERE id = %d", $filter_id ) );

			//$preview_data = isset( $get_data ) ? unserialize( $get_data ) : array();
			$preview_data = ( ! empty( $get_data ) && ( $temp = @unserialize( $get_data ) ) !== false ) ? $temp : array();

			// Get title appearance setting from database (moved to higher scope)
			$title_appearance = $preview_data['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-title-appearance'] ?? 'accordion-design';
			$title_group = $preview_data['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-title-group'] ?? [];

			// Get icon settings
			$expand_icon = $title_group['filter-title-expand-icon'] ?? 'fa fa-plus';
			$close_icon = $title_group['filter-title-close-icon'] ?? 'fa fa-minus';

			// Generate styles directly since we're not using ShopPageFilter
			if (class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
				$filter_style_handler = new \Shopglut\enhancements\Filters\implementation\FilterStyle($filter_id, $preview_data);
				$filter_style_handler->output_styles();
			}

		}

			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'single_product' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				// Define preview mode constant for demo editor
				if (!defined('SHOPGLUT_PREVIEW_MODE')) {
					define('SHOPGLUT_PREVIEW_MODE', true);
				}

				// Filter to add form element to allowed HTML for preview
				add_filter('wp_kses_allowed_html', function($allowed_html, $context) {
					if ($context === 'post') {
						// Add form element
						$allowed_html['form'] = array(
							'action' => true,
							'method' => true,
							'class' => true,
							'id' => true,
							'novalidate' => true,
							'enctype' => true,
							'style' => true,
							'name' => true,
							'target' => true,
							'accept' => true,
							'accept-charset' => true,
							'autocomplete' => true,
						);
						// Add label with for attribute
						$allowed_html['label'] = array(
							'class' => true,
							'id' => true,
							'style' => true,
							'for' => true,
						);
					}
					return $allowed_html;
				}, 10, 2);

				$single_product_data_manage = new SingleProductDataManage();

				// Get base allowed HTML
				$allowed_html = wp_kses_allowed_html( 'post' );

				// Allow style tags and link tags
				$allowed_html['style'] = array();
				$allowed_html['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
					'media' => true,
				);

				// Add div, span, h1-h6, p, label, strong, em with all common attributes
				$block_elements = array('div', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'strong', 'em', 'small', 'ul', 'ol', 'li', 'section', 'article', 'dl', 'dt', 'dd');
				foreach ($block_elements as $tag) {
					$allowed_html[$tag] = array(
						'class' => true,
						'id' => true,
						'style' => true,
						'data-*' => true,
					);
				}

				// Label element needs 'for' attribute
				$allowed_html['label'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
					'for' => true,
				);

				// Span element needs comprehensive attributes
				$allowed_html['span'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
					'data-*' => true,
					'aria-hidden' => true,
				);

				// Form element with all necessary attributes
				$allowed_html['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
					'novalidate' => true,
					'enctype' => true,
					'style' => true,
					'name' => true,
					'target' => true,
					'accept' => true,
					'accept-charset' => true,
					'autocomplete' => true,
				);

				// Input element with comprehensive attributes
				$allowed_html['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
					'required' => true,
					'checked' => true,
					'disabled' => true,
					'readonly' => true,
					'size' => true,
					'style' => true,
					'width' => true,
					'height' => true,
					'alt' => true,
					'src' => true,
				);
				$allowed_html['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
					'name' => true,
					'value' => true,
					'style' => true,
					'form' => true,
				);
				$allowed_html['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
					'required' => true,
					'multiple' => true,
					'size' => true,
					'style' => true,
					'position' => true,
					'opacity' => true,
					'width' => true,
					'height' => true,
					'form' => true,
				);
				$allowed_html['option'] = array(
					'value' => true,
					'selected' => true,
					'disabled' => true,
					'label' => true,
				);
				$allowed_html['textarea'] = array(
					'name' => true,
					'rows' => true,
					'cols' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'required' => true,
					'style' => true,
					'minlength' => true,
					'maxlength' => true,
					'wrap' => true,
					'form' => true,
				);

				// Add SVG support for icons
				$allowed_html['svg'] = array(
					'width' => true,
					'height' => true,
					'viewbox' => true,
					'fill' => true,
					'stroke' => true,
					'stroke-width' => true,
					'stroke-linecap' => true,
					'stroke-linejoin' => true,
					'class' => true,
					'xmlns' => true,
					'style' => true,
				);
				$allowed_html['path'] = array(
					'd' => true,
					'fill' => true,
					'stroke' => true,
					'stroke-width' => true,
					'stroke-linecap' => true,
				);
				$allowed_html['polyline'] = array(
					'points' => true,
					'fill' => true,
					'stroke' => true,
				);
				$allowed_html['line'] = array(
					'x1' => true,
					'y1' => true,
					'x2' => true,
					'y2' => true,
					'stroke' => true,
					'stroke-width' => true,
				);
				$allowed_html['circle'] = array(
					'cx' => true,
					'cy' => true,
					'r' => true,
					'fill' => true,
					'stroke' => true,
				);
				$allowed_html['rect'] = array(
					'x' => true,
					'y' => true,
					'width' => true,
					'height' => true,
					'rx' => true,
					'ry' => true,
					'fill' => true,
				);

				// Add i tag for FontAwesome icons with comprehensive attributes
				$allowed_html['i'] = array(
					'class' => true,
					'data-rating' => true,
					'style' => true,
					'aria-hidden' => true,
				);

				// Add table elements for product attributes
				$table_elements = array('table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption');
				foreach ($table_elements as $tag) {
					$allowed_html[$tag] = array(
						'class' => true,
						'id' => true,
						'style' => true,
						'colspan' => true,
						'rowspan' => true,
						'scope' => true,
					);
				}

				// Add img with full attributes
				$allowed_html['img'] = array(
					'src' => true,
					'alt' => true,
					'class' => true,
					'id' => true,
					'style' => true,
					'width' => true,
					'height' => true,
					'srcset' => true,
					'sizes' => true,
					'loading' => true,
					'data-src' => true,
				);

				// Add a tag
				$allowed_html['a'] = array(
					'href' => true,
					'class' => true,
					'id' => true,
					'style' => true,
					'title' => true,
					'target' => true,
					'rel' => true,
					'data-*' => true,
				);

				// Add br and hr
				$allowed_html['br'] = array('class' => true, 'style' => true);
				$allowed_html['hr'] = array('class' => true, 'style' => true);

				// Allow style and data-* attributes on all elements
				foreach ( $allowed_html as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_html[ $tag ]['style'] = true;
						$allowed_html[ $tag ]['class'] = true;
						$allowed_html[ $tag ]['id'] = true;
						// Allow data-* attributes
						$allowed_html[ $tag ]['data-*'] = true;
						if (in_array($tag, ['i', 'div', 'span', 'button', 'input', 'select', 'a'])) {
							$allowed_html[ $tag ]['data-rating'] = true;
						}
					}
				}

				// Expand safe_style_css filter to allow all common CSS properties
				add_filter('safe_style_css', function($styles) {
					$additional_styles = array(
						'display', 'visibility', 'opacity', 'position', 'top', 'right', 'bottom', 'left',
						'width', 'height', 'min-width', 'max-width', 'min-height', 'max-height',
						'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
						'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
						'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
						'border-color', 'border-style', 'border-width', 'border-radius',
						'background', 'background-color', 'background-image', 'background-position', 'background-size', 'background-repeat',
						'color', 'font', 'font-family', 'font-size', 'font-weight', 'font-style',
						'text-align', 'text-decoration', 'text-transform', 'line-height', 'letter-spacing', 'word-spacing',
						'overflow', 'overflow-x', 'overflow-y',
						'float', 'clear',
						'flex', 'flex-direction', 'flex-wrap', 'justify-content', 'align-items', 'align-content', 'flex-grow', 'flex-shrink', 'flex-basis',
						'grid', 'grid-template-columns', 'grid-template-rows', 'grid-gap', 'grid-column', 'grid-row',
						'transform', 'transition', 'animation',
						'box-shadow', 'text-shadow',
						'cursor', 'pointer-events',
						'z-index', 'vertical-align',
						'white-space', 'word-wrap', 'word-break',
						'list-style', 'list-style-type', 'list-style-position',
						'table-layout', 'border-collapse', 'border-spacing',
						'caption-side', 'empty-cells',
					);
					return array_merge($styles, $additional_styles);
				});

				// Get the preview content
				$preview_content = $single_product_data_manage->shopglut_render_singleplayout_preview( $layout_id );

				// Output with wp_kses filtering
				echo wp_kses( $preview_content, $allowed_html );

			}

			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'cartpage' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				$cartPage_dataManage = new CartDataManage();

				$allowed_html = wp_kses_allowed_html( 'post' );
				$allowed_html['style'] = array();
				$allowed_html['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
				);

				// Add form elements for cart functionality
				$allowed_html['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
				);
				$allowed_html['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
				);
				$allowed_html['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
				);
				$allowed_html['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
				);
				$allowed_html['option'] = array(
					'value' => true,
					'selected' => true,
				);
				$allowed_html['textarea'] = array(
					'name' => true,
					'rows' => true,
					'cols' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_html as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_html[ $tag ]['style'] = true;
						$allowed_html[ $tag ]['class'] = true;
						$allowed_html[ $tag ]['id'] = true;
					}
				}

				echo wp_kses( $cartPage_dataManage->shopglut_render_cartlayout_preview( $layout_id ), $allowed_html );
			}

			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'ordercomplete' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				$orderCompletePage_dataManage = new OrderCompleteDataManage();

				$allowed_html = wp_kses_allowed_html( 'post' );
				$allowed_html['style'] = array();
				$allowed_html['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
				);

				// Add form elements for cart functionality
				$allowed_html['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
				);
				$allowed_html['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
				);
				$allowed_html['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
				);
				$allowed_html['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
				);
				$allowed_html['option'] = array(
					'value' => true,
					'selected' => true,
				);
				$allowed_html['textarea'] = array(
					'name' => true,
					'rows' => true,
					'cols' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_html as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_html[ $tag ]['style'] = true;
						$allowed_html[ $tag ]['class'] = true;
						$allowed_html[ $tag ]['id'] = true;
					}
				}

				echo wp_kses( $orderCompletePage_dataManage->shopglut_render_orderCompletelayout_preview( $layout_id ), $allowed_html );
			}

			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && isset( $_GET['editor'] ) && 'product_comparison' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				$productComparison_dataManage = new ProductComparisonDataManage();

				$allowed_html = wp_kses_allowed_html( 'post' );
				$allowed_html['style'] = array();
				$allowed_html['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
				);

				// Add form elements for comparison functionality
				$allowed_html['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
				);
				$allowed_html['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
					'data-product-id' => true,
				);
				$allowed_html['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
					'data-product-id' => true,
				);
				$allowed_html['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
				);
				$allowed_html['option'] = array(
					'value' => true,
					'selected' => true,
				);
				$allowed_html['table'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
				);
				$allowed_html['thead'] = array(
					'class' => true,
				);
				$allowed_html['tbody'] = array(
					'class' => true,
				);
				$allowed_html['tr'] = array(
					'class' => true,
				);
				$allowed_html['th'] = array(
					'class' => true,
					'colspan' => true,
					'rowspan' => true,
				);
				$allowed_html['td'] = array(
					'class' => true,
					'colspan' => true,
					'rowspan' => true,
				);

				// Allow style attribute and data attributes on all elements
				foreach ( $allowed_html as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_html[ $tag ]['style'] = true;
						$allowed_html[ $tag ]['class'] = true;
						$allowed_html[ $tag ]['id'] = true;
						$allowed_html[ $tag ]['data-layout-id'] = true;
					}
				}

				echo wp_kses( $productComparison_dataManage->shopglut_render_comparison_preview( $layout_id ), $allowed_html );
			}

			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && isset( $_GET['editor'] ) && 'product_quickview' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				$quickView_dataManage = new QuickViewDataManage();

				$allowed_html = wp_kses_allowed_html( 'post' );
				$allowed_html['style'] = array();
				$allowed_html['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
				);

				// Add SVG support for close button
				$allowed_html['svg'] = array(
					'width' => true,
					'height' => true,
					'viewbox' => true,
					'fill' => true,
					'stroke' => true,
					'stroke-width' => true,
					'class' => true,
					'xmlns' => true,
				);
				$allowed_html['line'] = array(
					'x1' => true,
					'y1' => true,
					'x2' => true,
					'y2' => true,
					'stroke' => true,
					'stroke-width' => true,
				);
				$allowed_html['path'] = array(
					'd' => true,
					'fill' => true,
					'stroke' => true,
				);

				// Add form elements for quick view functionality
				$allowed_html['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
				);
				$allowed_html['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
					'data-product-id' => true,
					'data-large' => true,
				);
				$allowed_html['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
					'data-product-id' => true,
					'aria-label' => true,
				);
				$allowed_html['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
				);
				$allowed_html['option'] = array(
					'value' => true,
					'selected' => true,
				);

				// Allow style attribute and data attributes on all elements
				foreach ( $allowed_html as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_html[ $tag ]['style'] = true;
						$allowed_html[ $tag ]['class'] = true;
						$allowed_html[ $tag ]['id'] = true;
						$allowed_html[ $tag ]['data-layout-id'] = true;
						$allowed_html[ $tag ]['data-product-id'] = true;
					}
				}

				echo wp_kses( $quickView_dataManage->shopglut_render_quickview_preview( $layout_id ), $allowed_html );
			}


			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'accountpage' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				$accountpage_dataManage = AccountPageDataManage::get_instance();
				// Allow style tags for admin preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Internal preview content with controlled kses rules
				echo wp_kses( $accountpage_dataManage->shopglut_render_accountpage_preview( $layout_id ), $allowed_tags );
			}

			if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'accountpage_prebuilt' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				global $wpdb;
				$table_name = $wpdb->prefix . 'shopglut_accountpage_builder';
				$escaped_table = esc_sql($table_name);
				$layout_template = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for accountpage layout
					$wpdb->prepare(sprintf("SELECT layout_template FROM `%s` WHERE id = %d", $escaped_table), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name
				);

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for accountpage settings
				$layout_options = unserialize($wpdb->get_var($wpdb->prepare("SELECT layout_settings FROM {$wpdb->prefix}shopglut_accountpage_builder WHERE id = %d", $layout_id)));

				$layout_settings = isset($layout_options['shopg_accountpage_prebuilt_settings']) ? $layout_options['shopg_accountpage_prebuilt_settings'] : array();

				//echo $cartPage_dataManage->shopglut_render_cartlayout_preview( $layout_id );
               ?>
				<div class="shopglut-product-preview shopglut-layout-preview">
				<!-- Columns Layout -->
				<div class="preview-layout">
				<?php

				$default_namespace = 'Shopglut\\layouts\\accountPage\\templates\\';
				$template_class = $default_namespace . $layout_template;


				if ( class_exists( $template_class ) ) {
					$alayout_instance = new $template_class();
					$alayout_instance->layout_render( $layout_settings );
				}

				?>
				</div>


		     </div>
			 <?php
			}


			// ShopBanner Preview
			if ( isset( $_GET['page'] ) && 'shopglut_showcases' === $_GET['page'] && isset( $_GET['editor'] ) && 'shopbanner' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				// Allow style tags and necessary HTML for banner preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();
				$allowed_tags['script'] = array(
					'type' => true,
				);
				$allowed_tags['link'] = array(
					'href' => true,
					'target' => true,
					'rel' => true,
					'class' => true,
					'style' => true,
				);
				$allowed_tags['img'] = array(
					'src' => true,
					'alt' => true,
					'class' => true,
					'style' => true,
				);
				$allowed_tags['button'] = array(
					'type' => true,
					'class' => true,
					'style' => true,
					'onclick' => true,
				);
				$allowed_tags['div'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
				);
				$allowed_tags['h1'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['h2'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['h3'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['p'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['span'] = array(
					'class' => true,
					'style' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_tags as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_tags[ $tag ]['style'] = true;
						$allowed_tags[ $tag ]['class'] = true;
						$allowed_tags[ $tag ]['id'] = true;
					}
				}


				// Render ShopBanner preview
				?>
				<div class="shopglut-banner-preview">
					<?php $render_demo = new template1Markup();
					   $render_demo -> layout_render([], $layout_id);
					?>
				</div>
				<style>
					.shopglut-banner-preview {
						max-width: 800px;
						margin: 20px auto;
						border-radius: 8px;
						overflow: hidden;
					}
					.shopglut-banner-container {
						min-height: 300px;
					}
					.shopglut-banner-button {
						transition: all 0.3s ease;
					}
					.shopglut-banner-button:hover {
						opacity: 0.9;
						transform: translateY(-2px);
						box-shadow: 0 6px 20px rgba(0,115,170,0.4) !important;
					}
					.demo-icon {
						font-size: 48px;
						margin-bottom: 20px;
						line-height: 1;
					}
					.error {
						padding: 20px;
						background-color: #f8d7da;
						border: 1px solid #f5c6cb;
						color: #721c24;
						border-radius: 4px;
						text-align: center;
					}
				</style>
				<?php
			}

			if ( isset( $_GET['page'] ) && 'shopglut_showcases' === $_GET['page'] && isset( $_GET['editor'] ) && 'slider' === $_GET['editor'] ) {
				$layout_id = isset( $_GET['layout_id'] ) ? intval( $_GET['layout_id'] ) : 0;

				// Allow style tags and necessary HTML for banner preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();
				$allowed_tags['script'] = array(
					'type' => true,
				);
				$allowed_tags['link'] = array(
					'href' => true,
					'target' => true,
					'rel' => true,
					'class' => true,
					'style' => true,
				);
				$allowed_tags['img'] = array(
					'src' => true,
					'alt' => true,
					'class' => true,
					'style' => true,
				);
				$allowed_tags['button'] = array(
					'type' => true,
					'class' => true,
					'style' => true,
					'onclick' => true,
				);
				$allowed_tags['div'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
				);
				$allowed_tags['h1'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['h2'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['h3'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['p'] = array(
					'class' => true,
					'style' => true,
				);
				$allowed_tags['span'] = array(
					'class' => true,
					'style' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_tags as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_tags[ $tag ]['style'] = true;
						$allowed_tags[ $tag ]['class'] = true;
						$allowed_tags[ $tag ]['id'] = true;
					}
				}


				// Render ShopBanner preview
				?>
				<div class="shopglut-banner-preview">
					<?php $render_demo = new Slider1Markup();
					   $render_demo -> layout_render([], $layout_id);
					?>
				</div>
				<style>
					.shopglut-banner-preview {
						max-width: 800px;
						margin: 20px auto;
						border-radius: 8px;
						overflow: hidden;
					}
					.shopglut-banner-container {
						min-height: 300px;
					}
					.shopglut-banner-button {
						transition: all 0.3s ease;
					}
					.shopglut-banner-button:hover {
						opacity: 0.9;
						transform: translateY(-2px);
						box-shadow: 0 6px 20px rgba(0,115,170,0.4) !important;
					}
					.demo-icon {
						font-size: 48px;
						margin-bottom: 20px;
						line-height: 1;
					}
					.error {
						padding: 20px;
						background-color: #f8d7da;
						border: 1px solid #f5c6cb;
						color: #721c24;
						border-radius: 4px;
						text-align: center;
					}
				</style>
				<?php
			}

			// Product Badges Preview
			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && isset( $_GET['editor'] ) && 'product_badges' === $_GET['editor'] ) {
				$badge_id = isset( $_GET['badge_id'] ) ? intval( $_GET['badge_id'] ) : 0;

				$badge_dataManage = BadgeDataManage::get_instance();

				// Allow style tags and necessary HTML for badge preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();
				$allowed_tags['button'] = array(
					'type' => true,
					'class' => true,
					'style' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_tags as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_tags[ $tag ]['style'] = true;
						$allowed_tags[ $tag ]['class'] = true;
						$allowed_tags[ $tag ]['id'] = true;
					}
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Internal preview content with controlled kses rules
				echo wp_kses( $badge_dataManage->shopglut_render_badge_preview( $badge_id ), $allowed_tags );
			}

			// Product Swatches Preview
			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && isset( $_GET['editor'] ) && 'product_swatches' === $_GET['editor'] ) {
				$swatches_id = isset( $_GET['swatches_id'] ) ? intval( $_GET['swatches_id'] ) : 0;

				$swatches_dataManage = new ProductSwatchesDataManage();

				// Allow style tags and necessary HTML for swatches preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();
				$allowed_tags['link'] = array(
					'rel' => true,
					'type' => true,
					'href' => true,
					'media' => true,
				);

				// Add form elements for swatches functionality
				$allowed_tags['form'] = array(
					'action' => true,
					'method' => true,
					'class' => true,
					'id' => true,
					'novalidate' => true,
					'enctype' => true,
					'style' => true,
				);
				$allowed_tags['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'placeholder' => true,
					'class' => true,
					'id' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'onchange' => true,
					'onclick' => true,
					'required' => true,
					'checked' => true,
					'disabled' => true,
					'readonly' => true,
					'size' => true,
					'style' => true,
					'data-*' => true,
				);
				$allowed_tags['button'] = array(
					'type' => true,
					'class' => true,
					'id' => true,
					'onclick' => true,
					'disabled' => true,
					'name' => true,
					'value' => true,
					'style' => true,
					'form' => true,
					'data-*' => true,
				);
				$allowed_tags['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'onchange' => true,
					'required' => true,
					'multiple' => true,
					'size' => true,
					'style' => true,
					'position' => true,
				);
				$allowed_tags['option'] = array(
					'value' => true,
					'selected' => true,
					'disabled' => true,
					'label' => true,
				);

				// Add label with for attribute
				$allowed_tags['label'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
					'for' => true,
				);

				// Add SVG support for icons
				$allowed_tags['svg'] = array(
					'width' => true,
					'height' => true,
					'viewbox' => true,
					'fill' => true,
					'stroke' => true,
					'stroke-width' => true,
					'stroke-linecap' => true,
					'stroke-linejoin' => true,
					'class' => true,
					'xmlns' => true,
					'style' => true,
				);
				$allowed_tags['path'] = array(
					'd' => true,
					'fill' => true,
					'stroke' => true,
					'stroke-width' => true,
					'stroke-linecap' => true,
				);

				// Add i tag for icons
				$allowed_tags['i'] = array(
					'class' => true,
					'style' => true,
					'aria-hidden' => true,
				);

				// Add table elements
				$table_elements = array('table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption');
				foreach ($table_elements as $tag) {
					$allowed_tags[$tag] = array(
						'class' => true,
						'id' => true,
						'style' => true,
						'colspan' => true,
						'rowspan' => true,
						'scope' => true,
					);
				}

				// Allow style attribute and data attributes on all elements
				foreach ( $allowed_tags as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_tags[ $tag ]['style'] = true;
						$allowed_tags[ $tag ]['class'] = true;
						$allowed_tags[ $tag ]['id'] = true;
						$allowed_tags[ $tag ]['data-*'] = true;
					}
				}

				// Expand safe_style_css filter to allow all common CSS properties for swatches preview
				add_filter('safe_style_css', function($styles) {
					$additional_styles = array(
						'display', 'visibility', 'opacity', 'position', 'top', 'right', 'bottom', 'left',
						'width', 'height', 'min-width', 'max-width', 'min-height', 'max-height',
						'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
						'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
						'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
						'border-color', 'border-style', 'border-width', 'border-radius',
						'background', 'background-color', 'background-image', 'background-position', 'background-size', 'background-repeat',
						'color', 'font', 'font-family', 'font-size', 'font-weight', 'font-style',
						'text-align', 'text-decoration', 'text-transform', 'line-height', 'letter-spacing', 'word-spacing',
						'overflow', 'overflow-x', 'overflow-y',
						'float', 'clear',
						'flex', 'flex-direction', 'flex-wrap', 'justify-content', 'align-items', 'align-content', 'flex-grow', 'flex-shrink', 'flex-basis',
						'grid', 'grid-template-columns', 'grid-template-rows', 'grid-gap', 'grid-column', 'grid-row', 'gap',
						'transform', 'transition', 'animation',
						'box-shadow', 'text-shadow',
						'cursor', 'pointer-events',
						'z-index', 'vertical-align',
						'white-space', 'word-wrap', 'word-break',
						'list-style', 'list-style-type', 'list-style-position',
						'table-layout', 'border-collapse', 'border-spacing',
						'caption-side', 'empty-cells',
						'box-sizing',
					);
					return array_merge($styles, $additional_styles);
				});

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Internal preview content with controlled kses rules
				echo wp_kses( $swatches_dataManage->shopglut_render_singleplayout_preview( $swatches_id ), $allowed_tags );
			}

			// Filters Preview - Allow input elements for checkboxes and radios
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === $_GET['page'] && ( (isset( $_GET['editor'] ) && 'filters' === $_GET['editor']) || (strpos( $request_uri, 'shopglut_enhancements' ) !== false && strpos( $request_uri, 'filter' ) !== false) ) ) {
				
				// Define custom allowed HTML for filter preview
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['style'] = array();

				// Allow input elements for checkboxes and radios with comprehensive attributes
				$allowed_tags['input'] = array(
					'type' => true,
					'name' => true,
					'value' => true,
					'checked' => true,
					'class' => true,
					'id' => true,
					'style' => true,
					'disabled' => true,
					'readonly' => true,
					'required' => true,
				);

				// Allow span for checkmarks and radio marks
				$allowed_tags['span'] = array(
					'class' => true,
					'style' => true,
				);

				// Allow div for filter containers
				$allowed_tags['div'] = array(
					'class' => true,
					'id' => true,
					'style' => true,
				);

				// Allow label for proper accessibility
				$allowed_tags['label'] = array(
					'class' => true,
					'for' => true,
					'style' => true,
				);

				// Allow strong for parent categories
				$allowed_tags['strong'] = array(
					'style' => true,
				);

				// Allow select and option elements for dropdown filters
				$allowed_tags['select'] = array(
					'name' => true,
					'class' => true,
					'id' => true,
					'style' => true,
					'disabled' => true,
					'required' => true,
				);

				$allowed_tags['option'] = array(
					'value' => true,
					'selected' => true,
					'disabled' => true,
				);

				// Allow button elements for apply/reset buttons
				$allowed_tags['button'] = array(
					'type' => true,
					'class' => true,
					'style' => true,
					'disabled' => true,
				);

				// Allow style attribute on all elements
				foreach ( $allowed_tags as $tag => $attributes ) {
					if ( is_array( $attributes ) ) {
						$allowed_tags[ $tag ]['style'] = true;
					}
				}

				// Add custom CSS properties to safe styles
				add_filter('safe_style_css', function($styles) {
					$styles[] = 'display';
					$styles[] = 'margin-left';
					$styles[] = 'appearance';
					$styles[] = '-webkit-appearance';
					$styles[] = '-moz-appearance';
					$styles[] = 'width';
					$styles[] = 'height';
					$styles[] = 'opacity';
					$styles[] = 'position';
					$styles[] = 'vertical-align';
					$styles[] = 'flex-shrink';
					$styles[] = 'cursor';
					$styles[] = 'font-weight';
					$styles[] = 'line-height';
					$styles[] = 'text-align';
					$styles[] = 'text-transform';
					$styles[] = 'text-decoration';
					$styles[] = 'background-color';
					$styles[] = 'color';
					$styles[] = 'border';
					$styles[] = 'border-color';
					$styles[] = 'border-radius';
					$styles[] = 'box-shadow';
					$styles[] = 'transform';
					$styles[] = 'transition';
					return $styles;
				});

				// Store the allowed tags for use in the filter rendering
				if (!isset($GLOBALS['shopglut_filter_allowed_tags'])) {
					$GLOBALS['shopglut_filter_allowed_tags'] = $allowed_tags;
				}

				// Output centralized filter styles
				if ($filter_style_handler) {
					$filter_style_handler->output_styles();
				}

				// Use FilterContent for HTML generation - wrap with container for backend
				$filter_content = new \Shopglut\enhancements\Filters\implementation\FilterContent($filter_style_handler, $filter_id, $preview_data);
				echo '<div class="shopglut-filter-container" data-filter-id="' . esc_attr($filter_id) . '">';
				echo wp_kses_post($filter_content->generate_filter_html(true));
				echo '</div>';
			}


		}

		/**
		 * Get custom allowed HTML tags for filter preview
		 */
		private function get_filter_allowed_tags() {
			$allowed_tags = wp_kses_allowed_html( 'post' );
			$allowed_tags['style'] = array();

			// Allow input elements for checkboxes and radios with comprehensive attributes
			$allowed_tags['input'] = array(
				'type' => true,
				'name' => true,
				'value' => true,
				'checked' => true,
				'class' => true,
				'id' => true,
				'style' => true,
				'disabled' => true,
				'readonly' => true,
				'required' => true,
			);

			// Allow span for checkmarks and radio marks
			$allowed_tags['span'] = array(
				'class' => true,
				'style' => true,
			);

			// Allow div for filter containers
			$allowed_tags['div'] = array(
				'class' => true,
				'id' => true,
				'style' => true,
			);

			// Allow label for proper accessibility
			$allowed_tags['label'] = array(
				'class' => true,
				'for' => true,
				'style' => true,
			);

			// Allow strong for parent categories
			$allowed_tags['strong'] = array(
				'style' => true,
			);

			// Allow select and option elements for dropdown filters
			$allowed_tags['select'] = array(
				'name' => true,
				'class' => true,
				'id' => true,
				'style' => true,
				'disabled' => true,
				'required' => true,
			);

			$allowed_tags['option'] = array(
				'value' => true,
				'selected' => true,
				'disabled' => true,
			);

			// Allow button elements for apply/reset buttons
			$allowed_tags['button'] = array(
				'type' => true,
				'class' => true,
				'style' => true,
				'disabled' => true,
			);

			// Allow style attribute on all elements
			foreach ( $allowed_tags as $tag => $attributes ) {
				if ( is_array( $attributes ) ) {
					$allowed_tags[ $tag ]['style'] = true;
				}
			}

			return $allowed_tags;
		}

		/**
		 * Recursive method to render category hierarchy for filter preview
		 */
		private function render_category_hierarchy_preview( $term, $type, $show_count, $is_radio = false ) {
			$term_id = is_object( $term ) ? $term->term_id : $term['term_id'];
			$term_name = is_object( $term ) ? $term->name : $term['name'];
			$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );
			$has_children = is_object( $term ) && ! empty( $term->children ) && ! is_wp_error( $term->children );
			$is_parent = !$this->has_parent( $term );

			$html = '<label class="' . esc_attr( $is_radio ? 'shopglut-filter-radio-label' : 'shopglut-filter-checkbox' ) . '">';
			$html .= '<input type="' . esc_attr( $is_radio ? 'radio' : 'checkbox' ) . '" name="' . esc_attr( $type ) . ( $is_radio ? '' : '[]' ) . '" value="' . esc_attr( $term_id ) . '">';
			$html .= '<span class="' . esc_attr( $is_radio ? 'radio-mark' : 'checkmark' ) . '"></span>';
			$html .= $is_parent ? '<strong>' . esc_html( $term_name ) . '</strong>' : esc_html( $term_name );
			if ( $show_count ) {
				$html .= ' <span class="count">(' . esc_html( $term_count ) . ')</span>';
			}
			$html .= '</label>';

			// Render children recursively if they exist
			if ( $has_children ) {
				foreach ( $term->children as $child ) {
					$html .= '<div style="margin-left: 20px;">' . $this->render_category_hierarchy_preview( $child, $type, $show_count, $is_radio ) . '</div>';
				}
			}

			return $html;
		}

		/**
		 * Check if a term has a parent
		 */
		private function has_parent( $term ) {
			if ( is_object( $term ) ) {
				return isset( $term->parent ) && $term->parent != 0;
			}
			return false;
		}

		private function display_terms_with_appearance( $type, $terms, $appearance_type, $options = array() ) {
			if ( empty( $terms ) ) {
				return '';
			}

			$html = '';
			$images = isset( $options['images'] ) ? $options['images'] : array();
			$colors = isset( $options['colors'] ) ? $options['colors'] : array();
			$show_count = isset( $options['show_count'] ) ? $options['show_count'] : false;

			switch ( $appearance_type ) {
				case 'check-list':
					$html .= '<div class="shopglut-filter-checklist">';
					foreach ( $terms as $term ) {
						// Use recursive rendering to handle hierarchy
						$html .= $this->render_category_hierarchy_preview( $term, $type, $show_count, false );
					}
					$html .= '</div>';
					break;

				case 'radio':
					$html .= '<div class="shopglut-filter-radio">';
					foreach ( $terms as $term ) {
						// Use recursive rendering to handle hierarchy
						$html .= $this->render_category_hierarchy_preview( $term, $type, $show_count, true );
					}
					$html .= '</div>';
					break;

				case 'dropdown':
					$html .= '<select name="' . esc_attr( $type ) . '" class="shopglut-filter-dropdown">';
					$html .= '<option value="">Select ' . ucwords( str_replace( '-', ' ', $type ) ) . '</option>';
					foreach ( $terms as $term ) {
						$term_id = is_object( $term ) ? $term->term_id : $term['term_id'];
						$term_name = is_object( $term ) ? $term->name : $term['name'];
						$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );

						$count_text = $show_count ? ' (' . $term_count . ')' : '';
						$html .= '<option value="' . esc_attr( $term_id ) . '">' . esc_html( $term_name . $count_text ) . '</option>';
					}
					$html .= '</select>';
					break;

				case 'button':
					$html .= '<div class="shopglut-filter-buttons">';
					foreach ( $terms as $term ) {
						$term_id = is_object( $term ) ? $term->term_id : $term['term_id'];
						$term_name = is_object( $term ) ? $term->name : $term['name'];
						$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );

						$html .= '<button type="button" class="shopglut-filter-button" data-value="' . esc_attr( $term_id ) . '">';
						$html .= esc_html( $term_name );
						if ( $show_count ) {
							$html .= ' <span class="count">(' . esc_html( $term_count ) . ')</span>';
						}
						$html .= '</button>';
					}
					$html .= '</div>';
					break;

				case 'color':
					$html .= '<div class="shopglut-filter-colors">';
					foreach ( $terms as $term ) {
						$term_id = is_object( $term ) ? $term->term_id : $term['term_id'];
						$term_name = is_object( $term ) ? $term->name : $term['name'];
						$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );

						$color = isset( $colors[$term_id] ) ? $colors[$term_id] : '#cccccc';
						$html .= '<span class="shopglut-filter-color" data-value="' . esc_attr( $term_id ) . '" title="' . esc_attr( $term_name ) . '">';
						$html .= '<span class="color-swatch" style="background-color: ' . esc_attr( $color ) . '"></span>';
						if ( $show_count ) {
							$html .= ' <span class="count">(' . esc_html( $term_count ) . ')</span>';
						}
						$html .= '</span>';
					}
					$html .= '</div>';
					break;

				case 'image':
					$html .= '<div class="shopglut-filter-images">';
					foreach ( $terms as $term ) {
						$term_id = is_object( $term ) ? $term->term_id : $term['term_id'];
						$term_name = is_object( $term ) ? $term->name : $term['name'];
						$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );

						$image_url = isset( $images[$term_id] ) ? $images[$term_id] : '';
						$html .= '<span class="shopglut-filter-image" data-value="' . esc_attr( $term_id ) . '" title="' . esc_attr( $term_name ) . '">';
						if ( $image_url ) {
							$html .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $term_name ) . '">';
						} else {
							$html .= '<span class="no-image">' . esc_html( substr( $term_name, 0, 2 ) ) . '</span>';
						}
						if ( $show_count ) {
							$html .= ' <span class="count">(' . esc_html( $term_count ) . ')</span>';
						}
						$html .= '</span>';
					}
					$html .= '</div>';
					break;

				default:
					$html .= '<div class="shopglut-filter-list">';
					foreach ( $terms as $term ) {
						$term_name = is_object( $term ) ? $term->name : $term['name'];
						$term_count = is_object( $term ) ? $term->count : ( isset( $term['count'] ) ? $term['count'] : 0 );

						$html .= '<span class="shopglut-filter-item">';
						$html .= esc_html( $term_name );
						if ( $show_count ) {
							$html .= ' <span class="count">(' . esc_html( $term_count ) . ')</span>';
						}
						$html .= '</span>';
					}
					$html .= '</div>';
					break;
			}

			return $html;
		}



	}


}