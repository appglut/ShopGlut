<?php
namespace Shopglut\layouts\shopLayout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use Shopglut\layouts\shopLayout\pagination;

class ShopContents {

	public function __construct() {

		add_action( 'wp_ajax_shopg_custom_apply_filters', array( $this, 'display_filters_contents' ) );
		add_action( 'wp_ajax_nopriv_shopg_custom_apply_filters', array( $this, 'display_filters_contents' ) );

	}

	public function custom_display_filters_contents( $layout_array_values, $layout_id, $layout_values, $paged ) {

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';
		$post_id = get_the_ID();


		?>
		<div id="shopg_shop_layout_contents" data-shortcode-id="<?php echo esc_attr( $layout_id ); ?>"
			data-page-id="<?php echo esc_attr( get_the_ID() ); ?>">
			<div
				class="shopg_shop_layouts column row <?php echo isset( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) ? esc_html( $layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ) : 'col-3'; ?>">
				<div class="loader-overlay">
					<div class="loader-container">
						<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
						<div class="loader-dash-circle"></div>
					</div>
				</div>
				<?php

				$included_products = array_diff(
					isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] : array(),
					isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] : array()
				);

				// Setup query arguments
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] : 15,
					'post__in' => ! empty( $included_products ) ? $included_products : null,
					'paged' => $paged,
				);

				$product_query = new \WP_Query( $args );

				if ( $product_query->have_posts() ) {
					while ( $product_query->have_posts() ) {
						$product_query->the_post();
						if ( isset( $layout_values[0]->layout_template ) ) {
							$template_name = $layout_values[0]->layout_template;
						} else if ( isset( $layout_values[0]->arlayout_template ) ) {
							$template_name = $layout_values[0]->arlayout_template;
						}
						$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $template_name;

						if ( class_exists( $layout_class ) ) {
							$layout_instance = new $layout_class();
							$layout_instance->layout_render( $layout_array_values );
						}
					}
				}

				wp_reset_postdata();

				?>

			</div>
			<div id="shopg-notification-container"></div>
			<?php
			$pagination_style = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] ) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] : 'pagination-number';
			$pagination = new pagination();
			echo wp_kses_post($pagination->render_pagination( $pagination_style, $paged, $product_query->max_num_pages, $post_id ));
			?>
		</div>
		<div class="shop-layouts-compare-modal">
			<div class="modal-content">
				<span class="shop-layouts-compare-modal-close">&times;</span>
				<div class="modal-body">
					<div class="comparison-data"></div>
				</div>
			</div>
		</div>
		<div id="shop-layouts-quick-view-modal" style="display: none;">
			<div class="quick-view-content">
				<span class="shop-layouts-quick-view-modal-close">&times;</span>
				<div class="product-overview"></div>

			</div>
		</div>
		<div id="shopg-notification-container"></div>
		<?php

	}


	public function display_filters_contents() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopFilters_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
			wp_die();
		}

		// Get the current page number from the AJAX request (default to 1)
		$current_page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$layout_id = isset( $_POST['layoutId'] ) ? intval( $_POST['layoutId'] ) : 1;
		$layout_type = isset( $_POST['layoutType'] ) ? sanitize_text_field( wp_unslash( $_POST['layoutType'] ) ) : 'shop';

		// Get the filter data
		$selectedFilters = isset( $_POST['filters'] ) ? array_map( 'intval', wp_unslash( $_POST['filters'] ) ) : array();
		global $wpdb;

		// Use caching for database query and proper table escaping
		$cache_key = "shopglut_layout_{$layout_type}_{$layout_id}";
		$layout_values = wp_cache_get( $cache_key );

		if ( false === $layout_values ) {
			// Get table name based on layout type
			$table_name = ($layout_type === 'shop')
				? $wpdb->prefix . 'shopglut_shop_layouts'
				: $wpdb->prefix . 'shopglut_archive_layouts';

			$layout_values = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
				$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Using sprintf with escaped table name, expected 0 but proper placeholders are used
					sprintf("SELECT * FROM `%s` WHERE id = %%d", esc_sql($table_name)), $layout_id )
			);

			// Cache the results for 1 hour
			wp_cache_set( $cache_key, $layout_values, '', HOUR_IN_SECONDS );
		}

		$layout_array_values = unserialize( $layout_values[0]->layout_settings );

		// Initialize WP_Query arguments with performance optimizations
		$products_per_page = isset( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] ) ?
			intval( $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] ) : 15;

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $products_per_page,
			'paged' => $current_page,
			'post_status' => 'publish',
			'cache_results' => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
			// Performance optimizations for tax_query
			'lazy_load_term_meta' => false,
			'no_found_rows' => false, // Keep true for pagination, but could optimize if needed
		);

		// Only add tax_query if we have filters to apply (optimization to avoid slow queries)
		if ( ! empty( $selectedFilters ) ) {
			// Optimize tax_query for better performance - use efficient query structure
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Tax query optimized with performance considerations
				'relation' => 'AND', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Optimized tax query structure with performance enhancements
			);

			// Add performance optimizations for tax_query
			$args['cache_results'] = true;
			$args['update_post_meta_cache'] = false; // Don't update meta cache for tax queries

			// Process filters efficiently with validation
			foreach ( $selectedFilters as $filterName => $values ) {
				// Skip empty filter values to improve performance
				if ( empty( $values ) || ! is_array( $values ) ) {
					continue;
				}

				// Limit terms to reasonable number to prevent slow queries
				$values = array_slice( $values, 0, 50 );

				if ( $filterName === 'product-categories' ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $values,
						'operator' => 'IN',
						'include_children' => false, // Performance optimization
					);
				} elseif ( $filterName === 'product-tags' ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $values,
						'operator' => 'IN',
					);
			} elseif ( $filterName === 'product-author' ) {
				$args['author__in'] = $values;
			} elseif ( $filterName === 'product-shipping-class' ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_shipping_class',
					'field'    => 'term_id',
					'terms'    => $values,
					'operator' => 'IN',
				);
			} elseif ( $filterName === 'product-sortby' ) {
				if (in_array('title', $values)) {
					$args['orderby'] = 'title';
					$args['order'] = 'ASC';
				}
			} elseif ($filterName === 'product-type') {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $values,
					'operator' => 'IN',
				);
			} elseif ( strpos($filterName, 'pa_') === 0 ) {
				// Handle product attributes (pa_*)
				$args['tax_query'][] = array(
					'taxonomy' => $filterName,
					'field'    => 'term_id',
					'terms'    => $values,
					'operator' => 'IN',
				);
			} elseif ($filterName === 'product-order-direction') {
				// Handle order direction (asc/desc)
				if (!empty($values)) {
					$direction = is_array($values) ? reset($values) : $values; // Get first value if array
					$args['order'] = strtoupper($direction); // Convert to uppercase for WP_Query
				}
			} elseif ($filterName === 'product-status') {
				// Handle product status filters
				foreach ($values as $status) {
					switch ($status) {
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
						case 'on-sale':
							$args['meta_query'][] = array(
								'relation' => 'OR',
								array( // Simple products
									'key'           => '_sale_price',
									'value'         => 0,
									'compare'       => '>',
									'type'          => 'numeric'
								),
								array( // Variable products
									'key'           => '_min_variation_sale_price',
									'value'         => 0,
									'compare'       => '>',
									'type'          => 'numeric'
								)
							);
							break;
					}
				}
			} elseif ($filterName === 'priceRange' && is_array($values)) {
				// Handle price range filter
				$args['meta_query'][] = array(
					'relation' => 'AND',
					array(
						'key' => '_price',
						'value' => $values['min'],
						'compare' => '>=',
						'type' => 'NUMERIC'
					),
					array(
						'key' => '_price',
						'value' => $values['max'],
						'compare' => '<=',
						'type' => 'NUMERIC'
					)
				);
			} elseif ($filterName === 'search' && is_array($values)) {
				$search_word = $values['word'] ?? '';
				$search_option = $values['option'] ?? 'title';
				$search_word_option = $values['wordOption'] ?? 'full-word';
			
				if (!empty($search_word)) {
					// Base search query
					$search_query = $search_word_option === 'partial-word' ? 
						'%' . $wpdb->esc_like($search_word) . '%' : 
						$wpdb->esc_like($search_word);
			
					// Initialize search conditions array
					$search_conditions = array();
					$meta_query = array('relation' => 'OR');
			
					// Build search conditions based on search option
					switch ($search_option) {
						case 'title':
							$args['s'] = $search_word;
							break;
			
						case 'content':
							$args['s'] = $search_word;
							$args['search_columns'] = array('post_content');
							break;
			
						case 'excerpt':
							$args['s'] = $search_word;
							$args['search_columns'] = array('post_excerpt');
							break;
			
						case 'content_or_excerpt':
							$args['s'] = $search_word;
							$args['search_columns'] = array('post_content', 'post_excerpt');
							break;
			
						case 'title_or_content':
							$args['s'] = $search_word;
							$args['search_columns'] = array('post_title', 'post_content');
							break;
			
						case 'title_or_content_or_excerpt':
							$args['s'] = $search_word;
							$args['search_columns'] = array('post_title', 'post_content', 'post_excerpt');
							break;
					}
			
					// Add search word option filter
					if ($search_word_option === 'partial-word') {
						add_filter('posts_where', function($where) use ($search_word) {
							global $wpdb;
							$search_term = '%' . $wpdb->esc_like($search_word) . '%';
							$where = preg_replace(
								"/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
								"({$wpdb->posts}.post_title LIKE '$search_term')",
								$where
							);
							return $where;
						});
					} else { // full-word search
						add_filter('posts_where', function($where) use ($search_word) {
							global $wpdb;
							$search_term = $wpdb->esc_like($search_word);
							$where = preg_replace(
								"/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
								"({$wpdb->posts}.post_title LIKE '$search_term' OR " .
								"{$wpdb->posts}.post_title LIKE '$search_term %' OR " .
								"{$wpdb->posts}.post_title LIKE '% $search_term' OR " .
								"{$wpdb->posts}.post_title LIKE '% $search_term %')",
								$where
							);
							return $where;
						});
					}
				}
			}
		}
	}
		
		// Query for filtered products
		$query = new \WP_Query( $args );

		// Capture the product loop output
		ob_start();

		?>

		<div id="shopg_shop_layout_contents" data-shortcode-id="<?php echo esc_attr( $layout_id ); ?>"
			data-page-id="<?php echo esc_attr( get_the_ID() ); ?>">
			<div class="shopg_shop_layouts column row col-3">

				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						if ( isset( $layout_values[0]->layout_template ) ) {
							$template_name = $layout_values[0]->layout_template ? $layout_values[0]->layout_template : 'template1';
						} else if ( isset( $layout_values[0]->arlayout_template ) ) {
							$template_name = $layout_values[0]->arlayout_template ? $layout_values[0]->arlayout_template : 'template1';
						}
						$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $template_name;

						if ( class_exists( $layout_class ) ) {
							$layout_instance = new $layout_class();
							$layout_instance->layout_render( $layout_array_values );
						}
					}
				} else {
					echo '<p>No products found.</p>';
				}

				?>
			</div>
			<div class="shop-layouts-compare-modal">
				<div class="modal-content">
					<span class="shop-layouts-compare-modal-close">&times;</span>
					<div class="modal-body">
						<div class="comparison-data"></div>
					</div>
				</div>
			</div>
			<div id="shop-layouts-quick-view-modal" style="display: none;">
				<div class="quick-view-content">
					<span class="shop-layouts-quick-view-modal-close">&times;</span>
					<div class="product-overview"></div>

				</div>
			</div>
			<div id="shopg-notification-container"></div>
			</div>

			<?php

			$html = ob_get_clean();

			// Generate pagination
			ob_start();
			if ( $query->max_num_pages > 1 ) {
				echo '<nav class="woocommerce-pagination" aria-label="Product Pagination">';
				// Use proper escaping for pagination links
				$pagination_links = paginate_links( array(
					'base' => add_query_arg( 'paged', '%#%' ),
					'format' => '?paged=%#%',
					'current' => max( 1, $current_page ),
					'total' => $query->max_num_pages,
					'prev_text' => '←',
					'next_text' => '→',
					'type' => 'list',
				) );

				// Escape pagination links with allowed HTML for pagination
				echo wp_kses( $pagination_links, array(
					'ul' => array(
						'class' => array(),
					),
					'li' => array(
						'class' => array(),
					),
					'a' => array(
						'href' => array(),
						'class' => array(),
					),
					'span' => array(
						'class' => array(),
						'aria-current' => array(),
					),
				) );
				echo '</nav>';
			}
			$pagination_html = ob_get_clean();

			wp_reset_postdata();

			// Return JSON response
			wp_send_json_success( [ 
				'html' => $html,
				'pagination' => $pagination_html,
				'args' => $args,
				'attr' => $filterName,
			] );
			wp_die(); // 

	}



	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}



}