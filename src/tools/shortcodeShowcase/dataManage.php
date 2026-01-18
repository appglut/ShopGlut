<?php
namespace Shopglut\shortcodeShowcase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class dataManage {

	public function __construct() {
		add_action( 'admin_post_create_sshowcase', array( $this, 'handleCreateSShowcase' ) );
		add_shortcode( 'shopglut_archive', array( $this, 'render_archive' ) );

		add_action( 'wp_ajax_shopglut_load_products', array( $this, 'ajax_load_products' ) );
		add_action( 'wp_ajax_nopriv_shopglut_load_products', array( $this, 'ajax_load_products' ) );
	}

	public function render_archive( $atts ) {
		$default_atts = array(
			'button_style' => 'primary',
			'template' => 'default',
			'order_by' => 'post_title',
			'order' => 'ASC',
			'items_per_page' => -1,
			'cols' => 1
		);

		$atts = shortcode_atts( $default_atts, $atts );

		ob_start();
		?>
				<div class="shopglut-archive">
					<div class="shopglut-row">
						<!-- Category Tree Column -->
						<div class="shopglut-col-4">
							<ul class="shopglut-category-tree">
								<?php echo wp_kses_post( $this->render_category_tree() ); ?>
							</ul>
						</div>

						<!-- Products Column -->
						<div class="shopglut-col-8">
							<div class="shopglut-card shopglut-header-card">
								<div class="shopglut-card-body">
									<form id="shopglut-search" class="shopglut-search-form">
										<div class="shopglut-row">
											<input type="hidden" name="category" id="shopglut-category" value="0">
											<div class="shopglut-col-12">
												<div class="shopglut-search-group">
													<input type="text" class="shopglut-input" name="search"
														placeholder="Search products..." id="shopglut-search-input">
													<button class="shopglut-btn shopglut-btn-search" type="submit">
														<i class="fas fa-search"></i>
													</button>
												</div>
											</div>

											<div class="shopglut-col-6">
												<label for="shopglut-orderby">Order By:</label>
												<select name="orderby" id="shopglut-orderby" class="shopglut-select">
													<option value="date">Publish Date</option>
													<option value="title">Title</option>
													<option value="price">Price</option>
													<option value="popularity">Popularity</option>
													<option value="rating">Rating</option>
												</select>
											</div>
											<div class="shopglut-col-6">
												<label for="shopglut-order">Order:</label>
												<select name="order" id="shopglut-order" class="shopglut-select">
													<option value="DESC">Descending Order</option>
													<option value="ASC" selected="selected">Ascending Order</option>
												</select>
											</div>
										</div>
									</form>
								</div>
								<div class="shopglut-card-footer">
									<div class="shopglut-breadcrumb">
										<a href="#" id="shopglut-home">Home</a>
										<span class="shopglut-breadcrumb-separator"></span>
										<span id="shopglut-current-category"></span>
									</div>
								</div>
							</div>

							<div id="shopglut-products" class="shopglut-products-grid">
								<?php echo wp_kses_post( $this->render_products( $atts ) ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
				return ob_get_clean();
	}

	private function render_category_tree() {
		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
			'parent' => 0
		);

		$categories = get_terms( $args );
		$output = '';

		foreach ( $categories as $category ) {
			$has_children = get_term_children( $category->term_id, 'product_cat' );

			$output .= '<li class="shopglut-category-item">';
			$output .= '<div class="shopglut-category-wrapper">';

			$link_class = $has_children ? 'shopglut-category-link with-children' : 'shopglut-category-link';
			$output .= sprintf(
				'<a class="%s" href="#" data-category="%d">%s</a>',
				$link_class,
				$category->term_id,
				$category->name
			);

			if ( $has_children ) {
				$output .= sprintf(
					'<button class="shopglut-expand-btn" data-toggle="collapse" data-target="#category-%d">
                        <i class="fas fa-chevron-down"></i>
                    </button>',
					$category->term_id
				);

				$output .= sprintf(
					'<ul class="shopglut-subcategories" id="category-%d">%s</ul>',
					$category->term_id,
					$this->get_subcategories( $category->term_id )
				);
			}

			$output .= '</div></li>';
		}

		return $output;
	}

	private function get_subcategories( $parent_id ) {
		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
			'parent' => $parent_id
		);

		$categories = get_terms( $args );
		$output = '';

		foreach ( $categories as $category ) {
			$has_children = get_term_children( $category->term_id, 'product_cat' );

			$output .= '<li class="shopglut-category-item">';
			$output .= '<div class="shopglut-category-wrapper">';

			$link_class = $has_children ? 'shopglut-category-link with-children' : 'shopglut-category-link';
			$output .= sprintf(
				'<a class="%s" href="#" data-category="%d">%s</a>',
				$link_class,
				$category->term_id,
				$category->name
			);

			if ( $has_children ) {
				$output .= sprintf(
					'<button class="shopglut-expand-btn" data-toggle="collapse" data-target="#category-%d">
                        <i class="fas fa-chevron-down"></i>
                    </button>',
					$category->term_id
				);

				$output .= sprintf(
					'<ul class="shopglut-subcategories" id="category-%d">%s</ul>',
					$category->term_id,
					$this->get_subcategories( $category->term_id )
				);
			}

			$output .= '</div></li>';
		}

		return $output;
	}

	private function render_products( $atts ) {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $atts['items_per_page'],
			'orderby' => $atts['order_by'],
			'order' => $atts['order']
		);

		$products = new \WP_Query( $args );

		if ( ! $products->have_posts() ) {
			return '<div class="shopglut-alert shopglut-alert-info">No products found!</div>';
		}

		$output = '<div class="shopglut-row">';

		while ( $products->have_posts() ) {
			$products->the_post();
			$product = wc_get_product( get_the_ID() );

			// Get product type and ID
			$product_type = $product->get_type();
			$product_id = $product->get_id();

			// Determine button text and classes based on product type
			$button_text = $product_type === 'variable' ? 'Select options' : 'Add to cart';
			$button_class = $product_type === 'variable' ? 'product_type_variable' : 'product_type_simple ajax_add_to_cart';

			// Generate button HTML directly instead of using shortcode
			$button_html = sprintf(
				'<a href="%s" 
            data-quantity="1" 
            class="button %s add_to_cart_button shopglut-btn-%s" 
            data-product_id="%d" 
            aria-label="%s" 
            rel="nofollow">%s</a>',
				$product_type === 'variable' ? get_permalink() : '?add-to-cart=' . $product_id,
				$button_class,
				esc_attr( $atts['button_style'] ),
				$product_id,
				esc_attr( sprintf( 'Add "%s" to your cart', get_the_title() ) ),
				esc_html( $button_text )
			);

			$output .= sprintf(
				'<div class="shopglut-col-%d">
            <div class="shopglut-product-card">
                <div class="shopglut-product-media">
                    <div class="shopglut-product-thumb">%s</div>
                    <div class="shopglut-product-content">
                        <h3 class="shopglut-product-title">
                            <a href="%s">%s</a>
                        </h3>
                        <div class="shopglut-product-meta">
                            <span class="shopglut-price">%s</span>
                            <div class="shopglut-stock-status">%s</div>
                        </div>
                        <div class="shopglut-product-actions">%s</div>
                    </div>
                </div>
            </div>
        </div>',
				12 / $atts['cols'],
				$product->get_image( 'medium' ),
				get_permalink(),
				get_the_title(),
				$product->get_price_html(),
				$product->get_stock_status(),
				$button_html
			);
		}

		wp_reset_postdata();

		$output .= '</div>';
		return $output;
	}

	public function ajax_load_products() {
		check_ajax_referer( 'shopglut_ajax_nonce', 'nonce' );

		$orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'title';
		$order = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'ASC';

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => isset( $_POST['items_per_page'] ) ? intval( $_POST['items_per_page'] ) : -1,
		);

		switch ( $orderby ) {
			case 'price':
				// Use WooCommerce optimized ordering
				$args['orderby'] = 'menu_order title';
				$args['order'] = $order;
				// Cache and optimize price ordering through WC hooks
				add_filter( 'posts_clauses', array( $this, 'order_by_price_asc_post_clauses' ) );
				break;

			case 'popularity':
				// Use WooCommerce optimized popularity ordering
				$args['orderby'] = 'menu_order title';
				$args['order'] = $order;
				// Cache and optimize popularity through WC hooks
				add_filter( 'posts_clauses', array( $this, 'order_by_popularity_post_clauses' ) );
				break;

			case 'rating':
				// Use WooCommerce optimized rating ordering
				$args['orderby'] = 'menu_order title';
				$args['order'] = $order;
				// Cache and optimize rating through WC hooks
				add_filter( 'posts_clauses', array( $this, 'order_by_rating_post_clauses' ) );
				break;

			case 'date':
				$args['orderby'] = 'date';
				$args['order'] = $order;
				break;

			case 'title':
			default:
				$args['orderby'] = 'title';
				$args['order'] = $order;
				break;
		}

		if ( ! empty( $_POST['category'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => intval( $_POST['category'] )
			);
		}

		if ( ! empty( $_POST['search'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_POST['search'] ) );
		}

		if ( ! empty( $_POST['min_price'] ) && ! empty( $_POST['max_price'] ) ) {
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => array( floatval( $_POST['min_price'] ), floatval( $_POST['max_price'] ) ),
				'type' => 'NUMERIC',
				'compare' => 'BETWEEN'
			);
		}

		if ( $orderby === 'price' ) {
			$args['meta_query'][] = array(
				'relation' => 'OR',
				array(
					'key' => '_price',
					'value' => '',
					'type' => 'NUMERIC',
					'compare' => '!='
				),
				array(
					'key' => '_min_variation_price',
					'value' => '',
					'type' => 'NUMERIC',
					'compare' => '!='
				)
			);
		}

		$products = new \WP_Query( $args );

		ob_start();
		if ( $products->have_posts() ) {
			echo '<div class="shopglut-row">';
			while ( $products->have_posts() ) {
				$products->the_post();
				$product = wc_get_product( get_the_ID() );

				$product_type = $product->get_type();
				$product_id = $product->get_id();

				$button_text = $product_type === 'variable' ? 'Select options' : 'Add to cart';
				$button_class = $product_type === 'variable' ? 'product_type_variable' : 'product_type_simple ajax_add_to_cart';

				$button_html = sprintf(
					'<a href="%s" 
						data-quantity="1" 
						class="button %s add_to_cart_button shopglut-btn-%s" 
						data-product_id="%d" 
						aria-label="%s" 
						rel="nofollow">%s</a>',
					$product_type === 'variable' ? get_permalink() : '?add-to-cart=' . $product_id,
					$button_class,
					sanitize_text_field( wp_unslash( $_POST['button_style'] ?? 'primary' ) ),
					$product_id,
					esc_attr( sprintf( 'Add "%s" to your cart', get_the_title() ) ),
					esc_html( $button_text )
				);

				?>
								<div class="shopglut-col-<?php echo 12 / intval( $_POST['cols'] ?? 1 ); ?>">
									<div class="shopglut-product-card">
										<div class="shopglut-product-media">
											<div class="shopglut-product-thumb">
												<?php echo wp_kses_post( $product->get_image( 'medium' ) ); ?>
											</div>
											<div class="shopglut-product-content">
												<h3 class="shopglut-product-title">
													<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
												</h3>
												<div class="shopglut-product-meta">
													<span class="shopglut-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
													<div class="shopglut-stock-status"><?php echo esc_html( $product->get_stock_status() ); ?></div>
												</div>
												<div class="shopglut-product-actions">
													<?php echo wp_kses_post( $button_html ); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
			}
			echo '</div>';
		} else {
			echo '<div class="shopglut-alert shopglut-alert-info">No products found!</div>';
		}
		wp_reset_postdata();

		$output = ob_get_clean();
		wp_send_json_success( $output );
	}

	public function handleCreateSShowcase() {
		if (
			isset( $_POST['create_sshowcase_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['create_sshowcase_nonce'] ) ), 'create_sshowcase_nonce' ) &&
			current_user_can( 'manage_options' )
		) {
			$template_id = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
			$template_name = sanitize_text_field( 'Template(#' . $template_id . ')' );

			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_shortcodes_showcase';
			$inserted = $wpdb->insert(// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$table_name,
				array(
					'template_id' => $template_id,
					'template_name' => $template_name,
				)
			);

			if ( $inserted ) {
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_shortcode_showcase&editor=shortcode_showcase&template_id=' . $template_id ) );
				exit;
			} else {
				wp_die( 'Database insertion error' );
			}
		} else {
			wp_die( 'Permission error' );
		}
	}

	// Optimized ordering methods to replace direct meta_key queries
	public function order_by_price_asc_post_clauses( $args ) {
		global $wpdb;
		$args['join'] .= " LEFT JOIN {$wpdb->postmeta} wc_price ON {$wpdb->posts}.ID = wc_price.post_id AND wc_price.meta_key = '_price' ";
		$args['orderby'] = "CAST(wc_price.meta_value AS DECIMAL(10,2)) ASC, {$wpdb->posts}.post_title ASC";
		return $args;
	}

	public function order_by_popularity_post_clauses( $args ) {
		global $wpdb;
		$args['join'] .= " LEFT JOIN {$wpdb->postmeta} wc_sales ON {$wpdb->posts}.ID = wc_sales.post_id AND wc_sales.meta_key = 'total_sales' ";
		$args['orderby'] = "CAST(wc_sales.meta_value AS UNSIGNED) DESC, {$wpdb->posts}.post_title ASC";
		return $args;
	}

	public function order_by_rating_post_clauses( $args ) {
		global $wpdb;
		$args['join'] .= " LEFT JOIN {$wpdb->postmeta} wc_rating ON {$wpdb->posts}.ID = wc_rating.post_id AND wc_rating.meta_key = '_wc_average_rating' ";
		$args['orderby'] = "CAST(wc_rating.meta_value AS DECIMAL(3,2)) DESC, {$wpdb->posts}.post_title ASC";
		return $args;
	}

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}