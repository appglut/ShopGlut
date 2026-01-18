<?php
namespace Shopglut\layouts\shopLayout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



use Shopglut\ShopGlutDatabase;

class dataManage {

	public function __construct() {

		add_shortcode( 'shopg_shop_layout', array( $this, 'shopg_render_layout_shortcode' ) );

		add_action( 'wp_ajax_save_shopg_shopdata', array( $this, 'save_shopg_shopdata' ) );
		add_action( 'wp_ajax_save_shopg_archive_data', array( $this, 'save_shopg_archive_data' ) );
		add_action( 'wp_ajax_retrive_shopg_shopdata', array( $this, 'retrive_shopg_shopdata' ) );

		add_action( 'wp_ajax_add_to_cart', [ $this, 'add_to_cart' ] );
		add_action( 'wp_ajax_nopriv_add_to_cart', [ $this, 'add_to_cart' ] );

		add_action( 'wp_ajax_shopglut_add_to_wishlist', [ $this, 'shopglut_add_to_wishlist' ] );
		add_action( 'wp_ajax_nopriv_shopglut_add_to_wishlist', [ $this, 'shopglut_add_to_wishlist' ] );

		// For logged-in users
		add_action( 'wp_ajax_shopglut_shop_remove_from_wishlist', [ $this, 'shopglut_shop_remove_from_wishlist_handler' ] );
		add_action( 'wp_ajax_nopriv_shopglut_shop_remove_from_wishlist', [ $this, 'shopglut_shop_remove_from_wishlist_handler' ] );

		add_action( 'wp_ajax_bulk_action', [ $this, 'bulk_action' ] );
		add_action( 'wp_ajax_nopriv_bulk_action', [ $this, 'bulk_action' ] );

		add_action( 'wp_ajax_add_to_comparison', [ $this, 'add_to_comparison' ] );
		add_action( 'wp_ajax_nopriv_add_to_comparison', [ $this, 'add_to_comparison' ] );
		add_action( 'wp_ajax_remove_from_comparison', [ $this, 'remove_from_comparison' ] );
		add_action( 'wp_ajax_nopriv_remove_from_comparison', [ $this, 'remove_from_comparison' ] );
		add_action( 'wp_ajax_load_comparison_table', [ $this, 'load_comparison_table' ] );
		add_action( 'wp_ajax_nopriv_load_comparison_table', [ $this, 'load_comparison_table' ] );

		add_action( 'wp_ajax_quick_views_product', [ $this, 'quick_views_product' ] );
		add_action( 'wp_ajax_nopriv_quick_views_product', [ $this, 'quick_views_product' ] );

		add_action( 'wp_ajax_reset_shopglut_layouts', [ $this, 'reset_shopglut_layouts' ] );


		add_filter( 'template_include', [ $this, 'custom_design_woo_shop_template' ], 99 );



	}

	public function custom_design_woo_shop_template( $template ) {
		global $wpdb;

		// Query all rows to check if any layout has 'overwrite-shop-page' or archive pages enabled
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Custom table query with safe table name
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with safe table name
		$layout_values = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts" );

		$enabled_layout_id = null; // Variable to store the layout ID

		// Loop through each layout and check conditions
		if ( ! empty( $layout_values ) ) {
			foreach ( $layout_values as $layout ) {
				$layout_data_array = isset( $layout->layout_settings ) ? unserialize( $layout->layout_settings ) : array();
				$layout_array_values = isset( $layout_data_array['shopg_options_settings']['shopg_settings_options'] ) ? $layout_data_array['shopg_options_settings']['shopg_settings_options'] : '';
				$display_settings = isset( $layout_array_values['shopg_display_settings_accordion'] ) ? $layout_array_values['shopg_display_settings_accordion'] : array();

				// Check if 'overwrite-shop-page' is enabled for this layout
				$overwrite_shop = isset( $display_settings['overwrite-shop-page'] ) ? $display_settings['overwrite-shop-page'] : '0';

				// Get selected archive pages
				$selected_archives = isset( $display_settings['select-archive-pages'] ) && is_array( $display_settings['select-archive-pages'] ) ? $display_settings['select-archive-pages'] : array();

				// Check if current page matches any condition
				$should_apply = false;

				// Check shop page overwrite
				if ( is_shop() && $overwrite_shop !== '0' ) {
					$should_apply = true;
				}

				// Check archive page selections
				if ( ! empty( $selected_archives ) ) {
					foreach ( $selected_archives as $archive ) {
						// Check for "All Categories"
						if ( $archive === 'All Categories' && is_product_category() ) {
							$should_apply = true;
							break;
						}

						// Check for "All Tags"
						if ( $archive === 'All Tags' && is_product_tag() ) {
							$should_apply = true;
							break;
						}

						// Check for specific category (format: cat_123)
						if ( strpos( $archive, 'cat_' ) === 0 ) {
							$cat_id = str_replace( 'cat_', '', $archive );
							if ( is_product_category() ) {
								$current_term = get_queried_object();
								if ( $current_term && isset( $current_term->term_id ) && absint( $cat_id ) === $current_term->term_id ) {
									$should_apply = true;
									break;
								}
							}
						}

						// Check for specific tag (format: tag_123)
						if ( strpos( $archive, 'tag_' ) === 0 ) {
							$tag_id = str_replace( 'tag_', '', $archive );
							if ( is_product_tag() ) {
								$current_term = get_queried_object();
								if ( $current_term && isset( $current_term->term_id ) && absint( $tag_id ) === $current_term->term_id ) {
									$should_apply = true;
									break;
								}
							}
						}
					}
				}

				// If any condition matches, use this layout
				if ( $should_apply ) {
					$enabled_layout_id = $layout->id;
					break; // Use the first matching layout
				}
			}
		}

		// If a layout should be applied, pass the layout ID and load custom template
		if ( ! is_null( $enabled_layout_id ) ) {
			set_query_var( 'enabled_shop_layout_id', $enabled_layout_id );

			$shop_design_instance = new shopg_design_shop();
			return $shop_design_instance->appearance();
		}

		// Return the default template if conditions aren't met
		return $template;
	}


	/**
	 * Render Shop Layout Shortcode
	 *
	 * Usage: [shopg_shop_layout id="123"]
	 *
	 * @param array $atts Shortcode attributes
	 *                    'id' => Layout ID (required)
	 * @return string HTML output of the shop layout
	 */
	public function shopg_render_layout_shortcode( $atts ) {
		global $wpdb, $post;

		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'shopg_shop_layout'
		);

		$layout_id = absint( $atts['id'] );

		$paged = absint( isset( $atts['paged'] ) ? $atts['paged'] : 1 );

		if ( ! $layout_id ) {
			return esc_html__( 'Invalid layout ID', 'shopglut' );
		}

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with safe table name
		$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_shop_layouts` WHERE id = %d", $layout_id ) );

		if ( empty( $layout_values ) ) {
			return esc_html__( 'Layout not found', 'shopglut' );
		}

		$layout_array_values = unserialize( $layout_values[0]->layout_settings );

		ob_start();

		?>

		<div id="shopg_shop_contents">
            <?php  
			$shop_contents_instance = new ShopContents();
			$shop_contents_instance->custom_display_filters_contents( $layout_array_values, $layout_id, $layout_values, $paged );		
			?>
		</div>
		<?php
		return ob_get_clean();
	}


	public function retrive_shopg_shopdata() {
		// Check nonce for security
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		global $wpdb;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		// Sanitize and retrieve the layout ID from the POST request
		$post_id = isset( $_POST['shopg_shop_layoutid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopg_shop_layoutid'] ) ) : '';
		$current_page_id = isset( $_POST['page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) : ''; // Get the page ID passed from AJAX

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

		// Get layout settings from the database
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with safe table name
		$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}shopglut_shop_layouts` WHERE id = %d", absint( $post_id ) ) );
		if ( ! $layout_values ) {
			wp_send_json_error( array( 'html' => '<h1>Invalid Layout</h1>' ) );
			return;
		}

		$layout_array_values = unserialize( $layout_values[0]->layout_settings );

		// Determine pagination style and product number per page
		$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] )
			? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']
			: 'pagination-number';

		$paged = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$pagination_product_number = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] )
			? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no']
			: 15;

		// Prepare product query arguments
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $pagination_product_number,
			'paged' => $paged,
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			// Generate dynamic CSS for the preview
			$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
			$dynamic_css = $dynamic_style->dynamicCss( $post_id );

			ob_start();
			?>
			<style type="text/css">
				<?php echo $dynamic_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output from internal function ?>
			</style>
			<div class="shopg_shop_layout_contents">
				<div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
					<div
						class="shopg_shop_layouts column row <?php echo esc_html( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ?? 'col-3' ); ?>">
						<div class="loader-overlay">
							<div class="loader-container">
								<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
								<div class="loader-dash-circle"></div>
							</div>
						</div>
						<?php
						while ( $query->have_posts() ) {
							$query->the_post();
							$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;
							if ( class_exists( $layout_class ) ) {
								$layout_instance = new $layout_class();
								$layout_instance->layout_render( $layout_array_values );
							}
						}
						?>
					</div>
				</div>
			</div>
			<?php

			// Prepare pagination HTML using your pagination class
			$pagination = new pagination();
			$pagination_links = $pagination->render_pagination( $pagination_style, $paged, $query->max_num_pages, $current_page_id );

			$output = ob_get_clean();
			// Send both the product HTML and pagination links back in the AJAX response
			wp_send_json_success( array( 'html' => $output, 'max_pages' => $query->max_num_pages ) );
		} else {
			wp_send_json_error( array( 'html' => '<h1>No Products Found</h1>' ) );
		}
	}

	/**
	 * Recursively sanitize shop settings array
	 */
	private function sanitize_shop_settings($data) {
		if (!is_array($data)) {
			return sanitize_text_field($data);
		}

		$sanitized = array();
		foreach ($data as $key => $value) {
			$sanitized_key = sanitize_key($key);
			if (is_array($value)) {
				$sanitized[$sanitized_key] = $this->sanitize_shop_settings($value);
			} else {
				$sanitized[$sanitized_key] = sanitize_text_field($value);
			}
		}

		return $sanitized;
	}

	public function save_shopg_shopdata() {
		// Check nonce for security
		if ( ! isset( $_POST['shoplayouts_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shoplayouts_nonce'] ) ), 'shopg_shoplayouts_layouts' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
			return;
		}

		// Decode JSON data
		$raw_data = isset( $_POST['shopg_options_settings'] ) ? sanitize_text_field( wp_unslash( $_POST['shopg_options_settings'] ) ) : '';
		$data = json_decode( $raw_data, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( array( 'message' => 'Invalid JSON data' ) );
			return;
		}

		$data = $this->sanitize_shop_settings( $data );

		if ( ! empty( $data ) && ! wp_verify_nonce( isset( $_GET['post_nonce_check'] ), 'post_nonce_value' ) && ! empty( $_POST['layout_template'] ) ) {

			global $wpdb;

			$post_id = isset( $_POST['shopg_shop_layoutid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopg_shop_layoutid'] ) ) : '';
			$layout_name = isset( $_POST['layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_name'] ) ) : '';
			$layout_template = isset( $_POST['layout_template'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_template'] ) ) : '';
			$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1; // Handle pagination

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for checking existing layout record
			$existing_record = $wpdb->get_row(// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $post_id )
			);

			$data_to_insert = array(
				'layout_name' => $layout_name,
				'layout_template' => $layout_template,
				'layout_settings' => serialize( $data ),
			);

			if ( $existing_record ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table update for layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update( $table_name, $data_to_insert, array( 'id' => $existing_record->id ) );
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table insert for new layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->insert( $table_name, $data_to_insert );
			}

			// Clear cache immediately after database save to ensure fresh data in preview
			$cache_key = 'shopglut_shop_layout_' . absint($post_id);
			wp_cache_delete($cache_key);
			wp_cache_delete('shopglut_shop_layouts_count');
			wp_cache_delete('shopglut_shop_layouts_all_' . md5('0_1'));

			// Generate dynamic CSS for the updated preview
			$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
			$dynamic_css = $dynamic_style->dynamicCss( $post_id );

			ob_start();
			?>
			<style type="text/css">
				<?php echo $dynamic_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output from internal function ?>
			</style>
			<div class="shopg_shop_layout_contents">
				<div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
					<?php

					// Use the data that was just saved instead of querying database
					// This ensures preview shows exactly what was saved
					$layout_values = array(
						(object) array(
							'id' => $post_id,
							'layout_name' => $layout_name,
							'layout_template' => $layout_template,
							'layout_settings' => serialize($data)
						)
					);

					$layout_array_values = $data;

					$included_products = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] : array();
					$excluded_products = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] : array();
					$included_categories = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] : array();
					$excluded_categories = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories'] : array();
					$include_tags = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags'] : array();
					$exclude_tags = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags'] : array();
					$product_type = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type'] : array();
					$product_option = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] : 'all-products';
					$product_sorting = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] : 'date';
					$product_order = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] : 'ASC';
					$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) ? isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) : 'pagination-number';

					$included_products = array_diff( $included_products, $excluded_products );

					$args = array(
						'post_type' => 'product',
						'posts_per_page' => -1,
						'post__in' => ! empty( $included_products ) ? $included_products : null,
						// Removed post__not_in for better performance - handled conditionally below
						// Tax query initialized conditionally below for better performance
						'order' => $product_order,
						'paged' => $paged, // Handle pagination
					);

					// Handle post exclusions more efficiently
					if ( ! empty( $excluded_products ) && count( $excluded_products ) < 100 ) {
						// Only use post__not_in for small exclusion lists to avoid performance issues
						// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
						$args['post__not_in'] = $excluded_products;
					}

					// Initialize tax_query array conditionally
					$tax_queries = array();
					$tax_queries['relation'] = 'AND';

					if ( ! empty( $included_categories ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $included_categories,
							'operator' => 'IN',
						);
					}

					if ( ! empty( $excluded_categories ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $excluded_categories,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $include_tags ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_tag',
							'field' => 'term_id',
							'terms' => $include_tags,
							'operator' => 'IN',
						);
					}

					if ( ! empty( $exclude_tags ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_tag',
							'field' => 'term_id',
							'terms' => $exclude_tags,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $product_type ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_type',
							'field' => 'term_id',
							'terms' => $product_type,
							'operator' => 'IN',
						);
					}

					switch ( $product_option ) {
						case 'best-selling':
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = 'total_sales';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key

							$args['meta_type'] = 'NUMERIC';
							break;
						case 'recent-products':
							$args['orderby'] = 'date';
							break;
						case 'featured-products':
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'featured',
								'operator' => 'IN',
							);
							break;
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						case 'rated-products':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_wc_average_rating';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key

							$args['meta_type'] = 'DECIMAL';
							break;
						case 'sale-products':
							// Use optimized taxonomy query instead of meta query for better performance
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'on-sale',
								'operator' => 'IN',
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
							);
							break;
						case 'in-stock':
							// Use optimized meta query with proper indexing
							$args['meta_query'] = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query

								array(
									'key' => '_stock_status',
									'value' => 'instock',
									'compare' => '=',
									'type' => 'CHAR',
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query

								),
							);
							break;
						case 'out-of-stock':
							// Use optimized meta query with proper indexing
							$args['meta_query'] = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
								array(
									'key' => '_stock_status',
									'value' => 'outofstock',
									'compare' => '=',
									'type' => 'CHAR',
								),
							);
							break;
					}

					switch ( $product_sorting ) {
						case 'title':
						case 'name':
						case 'ID':
						case 'author':
						case 'date':
						case 'modified':
						case 'rand':
							// These are handled by default 'orderby'
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['orderby'] = $product_sorting;
							break;

						case 'sales':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_key'] = 'total_sales';
							$args['meta_type'] = 'NUMERIC';
							break;

						case 'price_low_to_high':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_key'] = '_price';
							$args['meta_type'] = 'DECIMAL';
							$args['order'] = 'ASC';
							break;

						case 'price_high_to_low':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_price';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							$args['order'] = 'DESC';
							break;

						case 'ratings':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_wc_average_rating';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							break;

						case 'featured':
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'featured',
								'operator' => 'IN',
							);
							break;

						case 'random':
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
							$args['orderby'] = 'rand';
							break;

						default:
							$args['orderby'] = 'date'; // Default to date if no match
							break;
					}

					// Add tax queries to args only if they exist for better performance
					if ( count( $tax_queries ) > 1 ) { // More than just the relation element
						// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						$args['tax_query'] = $tax_queries;
					}

					$query = new \WP_Query( $args );

					$file_included = false;

					?>
					<div
						class="shopg_shop_layouts column row <?php echo isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) ? esc_html( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) : 'col-3' ?>">

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

							wp_reset_postdata();
						}

						?>
					</div>
					<?php

					if ( ! $file_included ) {
						echo esc_html__( 'Layout file not found', 'shopglut' );
					}
					?>
				</div>
			</div>
			<?php
			$output = ob_get_clean();
			wp_send_json_success( array(
				'message' => 'Shop layout saved successfully!',
				'layout_id' => $post_id,
				'html' => $output
			) );
		}
		wp_send_json_error( array( 'message' => 'Failed to save shop layout data' ) );

	}
	public function save_shopg_archive_data() {
		// Check nonce for security
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$data = isset( $_POST['shopg_options_settings'] ) ? $this->sanitize_shop_settings( array_map( 'wp_kses_post', wp_unslash( $_POST['shopg_options_settings'] ) ) ) : array();

		if ( ! empty( $data ) && ! wp_verify_nonce( isset( $_GET['post_nonce_check'] ), 'post_nonce_value' ) && ! empty( $_POST['layout_template'] ) ) {

			global $wpdb;

			$post_id = isset( $_POST['shopg_shop_layoutid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopg_shop_layoutid'] ) ) : '';
			$layout_name = isset( $_POST['layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_name'] ) ) : '';
			$layout_template = isset( $_POST['layout_template'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_template'] ) ) : '';
			$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1; // Handle pagination

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom archive table query for checking existing layout record
			$existing_record = $wpdb->get_row(// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_archive_layouts WHERE id = %d", $post_id )
			);

			$data_to_insert = array(
				'arlayout_name' => $layout_name,
				'arlayout_template' => $layout_template,
				'arlayout_settings' => serialize( $data ),
			);

			if ( $existing_record ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom archive table update for layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update( $table_name, $data_to_insert, array( 'id' => $existing_record->id ) );
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom archive table insert for new layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->insert( $table_name, $data_to_insert );
			}

			// Clear cache immediately after database save to ensure fresh data in preview
			$cache_key = 'shopglut_archive_layout_' . absint($post_id);
			wp_cache_delete($cache_key);

			ob_start();
			?>
			<div class="shopg_shop_layout_contents">
				<div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
					<?php

					// Use the data that was just saved instead of querying database
					// This ensures preview shows exactly what was saved
					$layout_values = array(
						(object) array(
							'id' => $post_id,
							'arlayout_name' => $layout_name,
							'arlayout_template' => $layout_template,
							'arlayout_settings' => serialize($data)
						)
					);

					$layout_array_values = $data;

					$included_products = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] : array();
					$excluded_products = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] : array();
					$included_categories = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] : array();
					$excluded_categories = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories'] : array();
					$include_tags = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags'] : array();
					$exclude_tags = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags'] : array();
					$product_type = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type'] : array();
					$product_option = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] : 'all-products';
					$product_sorting = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] : 'date';
					$product_order = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] : 'ASC';
					$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) ? isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) : 'pagination-number';

					$included_products = array_diff( $included_products, $excluded_products );

					$args = array(
						'post_type' => 'product',
						'posts_per_page' => -1,
// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
						'post__in' => ! empty( $included_products ) ? $included_products : null,
						// Removed post__not_in for better performance - handled conditionally below
						// Tax query initialized conditionally below for better performance
						'order' => $product_order,
						'paged' => $paged, // Handle pagination
					);

					// Handle post exclusions more efficiently
					if ( ! empty( $excluded_products ) && count( $excluded_products ) < 100 ) {
						// Only use post__not_in for small exclusion lists to avoid performance issues
						// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
						$args['post__not_in'] = $excluded_products;
					}

					// Initialize tax_query array conditionally
					$tax_queries = array();
					$tax_queries['relation'] = 'AND';

					if ( ! empty( $included_categories ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $included_categories,
							'operator' => 'IN',
						);
					}

					if ( ! empty( $excluded_categories ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $excluded_categories,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $include_tags ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_tag',
							'field' => 'term_id',
							'terms' => $include_tags,
							'operator' => 'IN',
						);
					}

					if ( ! empty( $exclude_tags ) ) {
						$tax_queries[] = array(
							'taxonomy' => 'product_tag',
							'field' => 'term_id',
							'terms' => $exclude_tags,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $product_type ) ) {
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						$tax_queries[] = array(
							'taxonomy' => 'product_type',
							'field' => 'term_id',
							'terms' => $product_type,
							'operator' => 'IN',
						);
					}

					switch ( $product_option ) {
						case 'best-selling':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = 'total_sales';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'NUMERIC';
							break;
						case 'recent-products':
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['orderby'] = 'date';
							break;
						case 'featured-products':
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'featured',
								'operator' => 'IN',
							);
							break;
						case 'rated-products':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_wc_average_rating';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							break;
						case 'sale-products':
							// Use optimized taxonomy query instead of meta query for better performance
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'on-sale',
								'operator' => 'IN',
							);
							break;
						case 'in-stock':
							// Use optimized meta query with proper indexing
							$args['meta_query'] = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query

								array(
									'key' => '_stock_status',
									'value' => 'instock',
									'compare' => '=',
									'type' => 'CHAR',
								),
							);
							break;
						case 'out-of-stock':
							// Use optimized meta query with proper indexing
							// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
							$args['meta_query'] = array(
								array(
									'key' => '_stock_status',
									'value' => 'outofstock',
									'compare' => '=',
									'type' => 'CHAR',
								),
							);
							break;
					}
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key

					switch ( $product_sorting ) {
						case 'title':
						case 'name':
						case 'ID':
						case 'author':
// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						case 'date':
						case 'modified':
						case 'rand':
							// These are handled by default 'orderby'
							$args['orderby'] = $product_sorting;
							break;

						case 'sales':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_key'] = 'total_sales';
							$args['meta_type'] = 'NUMERIC';
							break;

						case 'price_low_to_high':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_price';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							$args['order'] = 'ASC';
							break;

						case 'price_high_to_low':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_price';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							$args['order'] = 'DESC';
							break;

						case 'ratings':
							// Use WooCommerce optimized ordering for better performance
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = '_wc_average_rating';// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							$args['meta_type'] = 'DECIMAL';
							break;

// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						case 'featured':
							$tax_queries[] = array(
								'taxonomy' => 'product_visibility',
								'field' => 'name',
								'terms' => 'featured',
								'operator' => 'IN',
							);
							break;

						case 'random':
							$args['orderby'] = 'rand';
							break;

						default:
							$args['orderby'] = 'date'; // Default to date if no match
							break;
					}

					// Add tax queries to args only if they exist for better performance
					if ( count( $tax_queries ) > 1 ) { // More than just the relation element
						$args['tax_query'] = $tax_queries;// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

					}

					$query = new \WP_Query( $args );

					$file_included = false;

					?>
					<div
						class="shopg_shop_layouts column row <?php echo isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) ? esc_html( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) : 'col-3' ?>">

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

							wp_reset_postdata();
						}

						?>
					</div>
					<?php

					if ( ! $file_included ) {
						echo esc_html__( 'Layout file not found', 'shopglut' );
					}
					?>
				</div>
			</div>
			<?php
			$output = ob_get_clean();
			wp_send_json_success( array( 'html' => $output ) );
		}
		wp_send_json_error( $_POST );

	}

	public function add_to_cart() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

		if ( $product_id ) {
			// Check if product exists and is purchasable
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				wp_send_json_error( 'Product not found.' );
				return;
			}

			if ( ! $product->is_purchasable() ) {
				wp_send_json_error( 'Product is not purchasable.' );
				return;
			}

			// Add to cart (works for both logged in and guest users)
			$cart_item_key = WC()->cart->add_to_cart( $product_id );

			if ( $cart_item_key ) {
				// Get basic cart data for update
				$cart_count = WC()->cart->get_cart_contents_count();
				$cart_total = WC()->cart->get_cart_total();
				$cart_url = wc_get_cart_url();

				wp_send_json_success( array(
					'message' => 'Product added to cart!',
					'cart_count' => $cart_count,
					'cart_total' => $cart_total,
					'cart_url' => $cart_url,
					'cart_hash' => WC()->cart->get_cart_hash()
				));
			} else {
				wp_send_json_error( 'Failed to add product to cart.' );
			}
		} else {
			wp_send_json_error( 'Invalid product ID.' );
		}
	}

	public function shopglut_add_to_wishlist() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		$user_id = get_current_user_id();

		if ( $user_id && $product_id ) {
			$wishlist = $this->get_user_wishlist( $user_id );

			if ( ! $wishlist ) {
				$wishlist = [];
			}

			if ( ! in_array( $product_id, $wishlist ) ) {
				$this->store_user_action( $user_id, $product_id, 'wishlist' );
				wp_send_json_success();
			} else {
				wp_send_json_error( [ 'product_id' => $product_id, 'wishlist' => $wishlist ] );
			}
		} else {
			wp_send_json_error( 'Invalid product ID or user not logged in.' );
		}
	}

	public function shopglut_shop_remove_from_wishlist_handler() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		$user_id = get_current_user_id();

		if ( $user_id && $product_id ) {
			$wishlist = $this->get_user_wishlist( $user_id );

			if ( in_array( $product_id, $wishlist ) ) {
				// Remove the product from the wishlist
				$this->remove_user_action( $user_id, $product_id, 'wishlist' );
				wp_send_json_success( [ 'product_id' => $product_id ] );
			} else {
				wp_send_json_error( 'Product not found in wishlist.' );
			}
		} else {
			wp_send_json_error( 'Invalid product ID or user not logged in.' );
		}
	}

	private function remove_user_action( $user_id, $product_id, $action_type ) {
		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table delete for removing user action
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->delete(
			$table_name,
			[ 
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => $action_type,
			],
			[ 
				'%d',
				'%d',
				'%s',
			]
		);
	}


	public function bulk_action() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$action_type = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : '';
		$product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', $_POST['product_ids'] ) : array();
		$user_id = get_current_user_id();

		if ( $user_id && ! empty( $product_ids ) && in_array( $action_type, [ 'add_to_cart', 'remove' ] ) ) {
			foreach ( $product_ids as $product_id ) {
				if ( $action_type === 'add_to_cart' ) {
					WC()->cart->add_to_cart( $product_id );
				} elseif ( $action_type === 'remove' ) {
					$this->remove_user_action( $user_id, $product_id, 'wishlist' );
				}
			}
			wp_send_json_success();
		} else {
			wp_send_json_error( 'Invalid product IDs, action type, or user not logged in.' );
		}
	}

	private function store_user_action( $user_id, $product_id, $action_type ) {
		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table insert for storing user action
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->insert(
			$table_name,
			[ 
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => $action_type,
				'timestamp' => current_time( 'mysql' ),
			],
			[ 
				'%d',
				'%d',
				'%s',
				'%s',
			]
		);
	}


	private function get_user_wishlist( $user_id ) {
		global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	 -- Table name is safely constructed with prefix + hardcoded string
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT product_id FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND action_type = %s",
			$user_id,
			'wishlist'
		) );
		return wp_list_pluck( $results, 'product_id' );
	}

	public function add_to_comparison() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
		global $wpdb;
		$user_id = get_current_user_id();
		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( 'Invalid product ID.' );
			return;
		}

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';

		// Check if already in comparison
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$exists = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND product_id = %d AND action_type = %s",
			$user_id,
			$product_id,
			'compare'
		) );

		if ( $exists ) {
			wp_send_json_error( 'Product already in comparison.' );
			return;
		}

		// Add to comparison
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table insert for adding comparison item
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->insert( $table_name, [
			'user_id' => $user_id,
			'product_id' => $product_id,
			'action_type' => 'compare',
			'timestamp' => current_time( 'mysql' ),
		], [
			'%d',
			'%d',
			'%s',
			'%s',
		] );

		if ( $result ) {
			wp_send_json_success( [ 'message' => 'Product added to comparison.', 'product_id' => $product_id ] );
		} else {
			wp_send_json_error( 'Failed to add product to comparison.' );
		}
	}

	public function remove_from_comparison() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );
		global $wpdb;
		$user_id = get_current_user_id();
		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table delete for removing comparison item
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->delete( $table_name, [
			'user_id' => $user_id,
			'product_id' => $product_id,
			'action_type' => 'compare',
		] );

		wp_send_json_success( true );
	}

	public function get_comparison_product_data( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return [];
		}

		return [ 
			'id' => $product->get_ID(),
			'name' => $product->get_name(),
			'price' => $product->get_price_html(),
			'weight' => $product->get_weight(),
			'dimensions' => $product->get_dimensions(),
			'sku' => $product->get_sku(),
			'stock_status' => $product->get_stock_status(),
			'rating' => $product->get_average_rating(),
			'reviews' => $product->get_review_count(),
			'image' => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ),
			'description' => $product->get_short_description(),
			'attributes' => $product->get_attributes(),
			'categories' => wc_get_product_category_list( $product_id ),
			'tags' => wc_get_product_tag_list( $product_id ),
			'regular_price' => wc_price( $product->get_regular_price() ),
			'sale_price' => $product->is_on_sale() ? wc_price( $product->get_sale_price() ) : '',
		];
	}

	public function load_comparison_table() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		// Delegate to the Product Comparison module if it exists
		if ( class_exists( 'Shopglut\\enhancements\\ProductComparison\\ProductComparisonDataManage' ) ) {
			$comparison_instance = \Shopglut\enhancements\ProductComparison\ProductComparisonDataManage::get_instance();

			if ( $comparison_instance && method_exists( $comparison_instance, 'ajax_render_comparison_table' ) ) {
				// Get user's comparison products from user_actions table
				global $wpdb;
				$user_id = get_current_user_id();
				$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$table_name = $wpdb->prefix . 'shopglut_user_actions';

				// Add product to comparison if provided
				if ( $product_id ) {
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
					$exists = $wpdb->get_var( $wpdb->prepare(
						"SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND product_id = %d AND action_type = %s",
						$user_id,
						$product_id,
						'compare'
					) );

					if ( ! $exists ) {
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->insert( $table_name, [
							'user_id' => $user_id,
							'product_id' => $product_id,
							'action_type' => 'compare',
							'timestamp' => current_time( 'mysql' ),
						], [
							'%d',
							'%d',
							'%s',
							'%s',
						] );
					}
				}

				// Get all comparison products for this user
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$comparison_items = $wpdb->get_results( $wpdb->prepare(
					"SELECT product_id FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND action_type = %s",
					$user_id,
					'compare'
				) );

				$product_ids = wp_list_pluck( $comparison_items, 'product_id' );

				// Set product IDs for the module to use
				$_POST['product_ids'] = $product_ids;

				// Call the module's comparison table rendering
				$comparison_instance->ajax_render_comparison_table();
				return;
			}
		}

		// Fallback to default template1 if module not available
		global $wpdb;

		$user_id = get_current_user_id();
		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_user_actions';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	 -- Table name is safely constructed with prefix + hardcoded string
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$exists = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND product_id = %d AND action_type = %s",
			$user_id,
			$product_id,
			'compare'
		) );

		if ( ! $exists && $product_id ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table insert for adding comparison item
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->insert( $table_name, [ 
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => 'compare',
			] );
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	 -- Table name is safely constructed with prefix + hardcoded string
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$comparison_items = $wpdb->get_results( $wpdb->prepare(
			"SELECT product_id FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND action_type = %s",
			$user_id,
			'compare'
		) );

		$product_ids = wp_list_pluck( $comparison_items, 'product_id' );
		$comparison_data = array_map( [ $this, 'get_comparison_product_data' ], $product_ids );

		$available_fields = [];
		if ( $comparison_data ) {
			foreach ( $comparison_data as $product ) {
				foreach ( $product as $key => $value ) {
					if ( ! empty( $value ) && ! in_array( $key, $available_fields ) ) {
						$available_fields[] = $key;
					}
				}
			}
		}

		ob_start();
		if ( $comparison_data ) {
			?>
			<table class="comparison-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Product', 'shopglut' ); ?>
						</th>
						<?php foreach ( $comparison_data as $product ) : ?>

							<th>
								<img src="<?php echo esc_url( $product['image'] ); ?>"
									alt="<?php echo esc_attr( $product['name'] ); ?>">
								<h2>
									<?php echo esc_html( $product['name'] ); ?>
								</h2>
								<button class="shopg-shop-remove-compare" data-product-id="<?php echo esc_attr( $product['id'] ); ?>">
									<?php esc_html_e( 'Remove', 'shopglut' ); ?>
								</button>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php if ( in_array( 'price', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Price', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo wp_kses_post( $product['price'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'weight', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Weight', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo esc_html( $product['weight'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'dimensions', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Dimensions', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo esc_html( $product['dimensions'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'sku', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'SKU', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo esc_html( $product['sku'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'stock_status', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Stock Status', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo esc_html( $product['stock_status'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'rating', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Rating', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo esc_html( $product['rating'] ) . ' (' . esc_html( $product['reviews'] ) . ')'; ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'categories', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Categories', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo wp_kses_post( $product['categories'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'tags', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Tags', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo wp_kses_post( $product['tags'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'regular_price', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Regular Price', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo wp_kses_post( $product['regular_price'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<?php if ( in_array( 'sale_price', $available_fields ) ) : ?>
						<tr>
							<th><?php esc_html_e( 'Sale Price', 'shopglut' ); ?></th>
							<?php foreach ( $comparison_data as $product ) : ?>
								<td><?php echo wp_kses_post( $product['sale_price'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<?php
		} else {
			echo esc_html__( 'No items in your comparison list.', 'shopglut' );
		}
		$content = ob_get_clean();

		wp_send_json_success( $content );

	}

	public function quick_views_product() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		// Delegate to the Product Quick View module if it exists
		if ( class_exists( 'Shopglut\\enhancements\\ProductQuickView\\QuickViewDataManage' ) ) {
			$quickview_instance = \Shopglut\enhancements\ProductQuickView\QuickViewDataManage::get_instance();

			if ( $quickview_instance && method_exists( $quickview_instance, 'get_quickview_product' ) ) {
				// Change nonce check to match QuickView module's expected nonce
				$_POST['nonce'] = wp_create_nonce( 'shopglut_quickview_nonce' );
				$quickview_instance->get_quickview_product();
				return;
			}
		}

		// Fallback to default template1 if module not available
		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			wp_send_json_error( 'Invalid product.' );
		}

		ob_start();

		// Function to format the price
		function format_price_range( $product ) {
			if ( $product->is_type( 'variable' ) ) {
				$min_price = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price( 'min', true ) ) );
				$max_price = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price( 'max', true ) ) );

				// Return formatted price range
				return sprintf( '%s - %s', wc_price( $min_price ), wc_price( $max_price ) );
			}
			// Return the price HTML for simple products
			return $product->get_price_html();
		}

		?>
		<div class="product-quick-view">
			<div class="product-images">
				<?php echo wp_kses_post( $product->get_image() ); ?>
			</div>
			<div class="product-summary">
				<h1 class="product-title">
					<?php echo esc_html( $product->get_name() ); ?>
				</h1>
				<div class="price">
					<?php echo wp_kses_post( format_price_range( $product ) ); ?>
				</div>
				<div class="description"><?php echo wp_kses_post( $product->get_short_description() ); ?></div>
				<div class="product-meta">
					<?php echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'shopglut' ) . ' ', '</span>' ) ); ?>
				</div>
			</div>
		</div>
		<?php

		$output = ob_get_clean();

		wp_send_json_success( $output );
	}

	/**
	 * Reset specific Shopglut layout settings
	 */
	public function reset_shopglut_layouts() {
		// Check nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopg_shoplayouts_layouts' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
			return;
		}

		// Get the layout ID from the AJAX request
		$layout_id = isset( $_POST['layout_id'] ) ? intval( $_POST['layout_id'] ) : 0;

		if ( $layout_id <= 0 ) {
			wp_send_json_error( array( 'message' => 'Invalid layout ID' ) );
			return;
		}

		global $wpdb;
		$table_name = ShopGlutDatabase::table_shop_layouts();

		// Update only the specific layout with the given ID
		// Set layout_settings to an empty string
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table update for resetting layout settings
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->update(
			$table_name,
			array( 'layout_settings' => '' ),
			array( 'id' => $layout_id ),
			array( '%s' ),
			array( '%d' )
		);

		if ( $result !== false ) {
			// Clear cache after reset to ensure fresh data on reload
			$cache_key = 'shopglut_shop_layout_' . absint($layout_id);
			wp_cache_delete($cache_key);
			wp_cache_delete('shopglut_shop_layouts_count');
			wp_cache_delete('shopglut_shop_layouts_all_' . md5('0_1'));

			wp_send_json_success( array( 'message' => 'Settings have been reset successfully.' ) );
		} else {
			wp_send_json_error( array( 'message' => 'Failed to reset the settings.' ) );
		}
	}

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}