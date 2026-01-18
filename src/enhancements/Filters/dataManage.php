<?php
namespace Shopglut\enhancements\Filters;

if ( ! defined( 'ABSPATH' ) ) exit;


class dataManage {

	public function __construct() {

		add_action( 'admin_post_create_filter', array( $this, 'handleCreateFilter' ) );
		add_action( 'wp_ajax_save_shopg_filterdata', array( $this, 'save_shopg_filterdata' ) );
		add_action( 'wp_ajax_shopglut_filter_products', array( $this, 'filter_products' ) );

		// Initialize shop page template
		if (class_exists('Shopglut\enhancements\Filters\implementation\ShopPageTemplate')) {
			new \Shopglut\enhancements\Filters\implementation\ShopPageTemplate();
		}

		
	}


	public function handleCreateFilter() {
		if (
			isset( $_POST['create_filter_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['create_filter_nonce'] ) ), 'create_filter_nonce' ) &&
			current_user_can( 'manage_options' )
		) {
			$filter_name = sanitize_text_field( 'Filter' );

			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';
			$inserted = $wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$table_name,
				array(
					'filter_name' => $filter_name,
				)
			);

			if ( $inserted ) {
				$inserted_id = $wpdb->insert_id;
				// Update the filter name to include the generated ID
				$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$table_name,
					array( 'filter_name' => 'Filter(#' . $inserted_id . ')' ),
					array( 'id' => $inserted_id )
				);

				$redirect_url = admin_url( 'admin.php?page=shopglut_enhancements&editor=filters&filter_id=' . $inserted_id );
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				wp_die( 'Database insertion error' );
			}
		} else {
			wp_die( 'Permission error' );
		}
	}

	public function save_shopg_filterdata() {

		// Check if clean JSON data is sent
		$clean_data = null;
		if ( isset( $_POST['filter_data'] ) ) {
			$json_string = sanitize_text_field( wp_unslash( $_POST['filter_data'] ) );
			$clean_data = json_decode( $json_string, true );
		}

		// Fallback to old format if clean data not available
		if ( empty( $clean_data ) ) {
			$raw_data = isset( $_POST['shopg_filter_settings'] ) ? map_deep( wp_unslash( $_POST['shopg_filter_settings'] ), 'sanitize_text_field' ) : array();
			$data_to_save = $raw_data;
		} else {
			// Convert clean JSON data to expected serialized format
			$data_to_save = array( 'shopg_filter_options_settings' => $this->convert_clean_json_to_expected_format( $clean_data ) );
		}

		if ( ! empty( $data_to_save ) && isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopFilters_nonce' ) && ! empty( $_POST['filter_name'] ) ) {

			global $wpdb;

			$post_id = isset( $_POST['shopg_shop_filter_id'] ) ? absint( wp_unslash( $_POST['shopg_shop_filter_id'] ) ) : 0;
			$layout_name = sanitize_text_field( wp_unslash( $_POST['filter_name'] ) );

			$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$existing_record = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_enhancement_filters WHERE id = %d", $post_id )
			);

			$data_to_insert = array(
				'filter_name' => $layout_name,
				'filter_settings' => serialize( $data_to_save ),
			);

			if ( $existing_record ) {
				$wpdb->update( $table_name, $data_to_insert, array( 'id' => $existing_record->id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$filter_id = $existing_record->id;
			} else {
				$wpdb->insert( $table_name, $data_to_insert ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$filter_id = $wpdb->insert_id;
			}

			// Generate preview HTML
			$preview_html = '';
			if (class_exists('Shopglut\enhancements\Filters\implementation\ShopPageFilter')) {
				$filter_renderer = new \Shopglut\enhancements\Filters\implementation\ShopPageFilter($filter_id, $data_to_save);
				$preview_html = $filter_renderer->render_preview();
			}

			wp_send_json_success( array(
				'filter_id' => $filter_id,
				'preview_html' => $preview_html,
				'message' => 'Filter saved successfully!'
			) );
		}
		wp_send_json_error( 'Invalid request' );
	}

	private function convert_clean_json_to_expected_format( $clean_data ) {
		// The JavaScript already sends data with shopg_filter_options_settings as the root key,
		// so we need to extract the inner structure to avoid double-nesting
		if ( isset( $clean_data['shopg_filter_options_settings'] ) ) {
			return $clean_data['shopg_filter_options_settings'];
		}

		// Fallback to returning the data as is if the expected structure isn't found
		return $clean_data;
	}



	/**
	 * Handle AJAX request for filtering products
	 */
	public function filter_products() {
		// Verify nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_filter_nonce' ) ) {
			wp_send_json_error( 'Security check failed' );
			return;
		}

		// Get filter parameters
		$filter_params = isset( $_POST['filter_params'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['filter_params'] ) ), true ) : array();

		// If it's a JSON string, decode it (handle both cases)
		if ( is_string( $filter_params ) ) {
			$filter_params = json_decode( $filter_params, true ) ?: array();
		} else {
			// Ensure it's an array
			$filter_params = is_array( $filter_params ) ? $filter_params : array();
		}

		// Sanitize the filter parameters
		$sanitized_params = array();
		if ( isset( $filter_params['categories'] ) && is_array( $filter_params['categories'] ) ) {
			$sanitized_params['categories'] = array_map( 'intval', $filter_params['categories'] );
		}
		if ( isset( $filter_params['tags'] ) && is_array( $filter_params['tags'] ) ) {
			$sanitized_params['tags'] = array_map( 'intval', $filter_params['tags'] );
		}

		$filter_params = $sanitized_params;

		// Get pagination settings
		$filter_id = isset( $_POST['filter_id'] ) ? intval( $_POST['filter_id'] ) : 0;
		$products_per_page = $this->get_products_per_page( $filter_id );

		// Debug: Log filter information for troubleshooting
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			do_action(
				'shopglut_debug_log',
				'Filter pagination information',
				array(
					'filter_id' => $filter_id,
					'products_per_page' => $products_per_page,
				)
			);
		}

		// Get current page
		$current_page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$current_page = max( 1, $current_page );

		// Build WP_Query args
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => $products_per_page,
			'paged' => $current_page,
		);

		// Build tax_query
		$tax_query = array();

		// Add category filter
		if ( ! empty( $filter_params['categories'] ) && is_array( $filter_params['categories'] ) ) {
			$categories = array_map( 'intval', $filter_params['categories'] );
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $categories,
				'operator' => 'IN',
			);
		}

		// Add tag filter
		if ( ! empty( $filter_params['tags'] ) && is_array( $filter_params['tags'] ) ) {
			$tags = array_map( 'intval', $filter_params['tags'] );
			$tax_query[] = array(
				'taxonomy' => 'product_tag',
				'field' => 'term_id',
				'terms' => $tags,
				'operator' => 'IN',
			);
		}

		// Set tax_query if we have filters
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Tax query required for product filtering functionality
		// Performance mitigated by implementing caching below
		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Performance mitigated by caching
			$args['tax_query']['relation'] = 'AND'; // All filters must match
		}

		// Create cache key based on filter parameters
		$cache_key = 'shopglut_filter_products_' . md5( serialize( $args ) );
		$cached_query = wp_cache_get( $cache_key, 'shopglut_filters' );

		if ( false !== $cached_query ) {
			$products_query = $cached_query;
		} else {
			// Perform the query
			$products_query = new \WP_Query( $args );
			// Cache the query object for 15 minutes
			wp_cache_set( $cache_key, $products_query, 'shopglut_filters', 15 * MINUTE_IN_SECONDS );
		}

		// Generate HTML for filtered products
		$products_html = '';
		if ( $products_query->have_posts() ) {
			ob_start();
			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				wc_get_template_part( 'content', 'product' );
			}
			$products_html = ob_get_clean();
		} else {
			$products_html = '<div class="woocommerce-info">' . esc_html__( 'No products found matching your selection.', 'shopglut' ) . '</div>';
		}

		// Restore original post data
		wp_reset_postdata();

		// Generate pagination HTML
		$pagination_html = $this->generate_pagination_html( $products_query, $current_page, $products_per_page );

		// Send response
		wp_send_json_success( array(
			'products' => $products_html,
			'pagination' => $pagination_html,
			'found_posts' => $products_query->found_posts,
			'max_num_pages' => $products_query->max_num_pages,
			'current_page' => $current_page,
			'products_per_page' => $products_per_page,
			'query_args' => $args
		) );
	}

	/**
	 * Get products per page setting from filter configuration
	 */
	private function get_products_per_page( $filter_id ) {
		if ( ! $filter_id ) {
			return 20; // Default value if no filter ID
		}

		global $wpdb;

		// Check cache first
		$cache_key = "shopglut_filter_settings_{$filter_id}";
		$cached_data = wp_cache_get($cache_key, 'shopglut_filters');

		if (false !== $cached_data) {
			return $cached_data;
		}

		// Query the filter_settings column from the database
		$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';
		$filter_data = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared -- Direct query required for custom plugin table - no WordPress core function available, using escaped table name for backward compatibility
			$wpdb->prepare( "SELECT filter_settings FROM `" . esc_sql($table_name) . "` WHERE id = %d", $filter_id ) // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Using $wpdb->prepare with proper placeholders
		);

		if ( ! empty( $filter_data ) ) {
			$settings = maybe_unserialize( $filter_data );

			if ( is_array( $settings ) ) {
				// Based on filters-config.php structure:
				// $fields[0]['tabs'][1]['fields'] contains filter-number-products-show
				// So the path is: shopglut-filter-settings-main-tab -> filter-number-products-show

				if ( isset( $settings['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-number-products-show'] ) ) {
					$products_per_page = $settings['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-number-products-show'];
					$result = intval( $products_per_page );
					wp_cache_set( $cache_key, $result, 'shopglut_filters', 300 ); // Cache for 5 minutes
					return $result;
				}
			}
		}

		// Return default value and cache it
		$default_result = 20;
		wp_cache_set( $cache_key, $default_result, 'shopglut_filters', 300 ); // Cache for 5 minutes
		return $default_result;
	}

	
	/**
	 * Generate pagination HTML
	 */
	private function generate_pagination_html( $query, $current_page, $per_page ) {
		if ( $query->max_num_pages <= 1 ) {
			return '';
		}

		$big = 999999999; // need an unlikely integer
		$base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
		$format = '?paged=%#%';

		$pagination_args = array(
			'base' => $base,
			'format' => $format,
			'total' => $query->max_num_pages,
			'current' => $current_page,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __( '&laquo; Previous', 'shopglut' ),
			'next_text' => __( 'Next &raquo;', 'shopglut' ),
			'type' => 'array',
			'add_args' => false,
			'add_fragment' => '',
		);

		$links = paginate_links( $pagination_args );

		if ( ! empty( $links ) ) {
			$pagination_html = '<nav class="shopglut-pagination">';
			$pagination_html .= '<ul class="page-numbers">';

			foreach ( $links as $link ) {
				if ( strpos( $link, 'current' ) !== false ) {
					$pagination_html .= '<li><span class="page-numbers current">' . $current_page . '</span></li>';
				} else {
					$pagination_html .= '<li>' . $link . '</li>';
				}
			}

			$pagination_html .= '</ul>';
			$pagination_html .= '</nav>';

			return $pagination_html;
		}

		return '';
	}

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}