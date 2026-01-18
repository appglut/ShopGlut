<?php
namespace Shopglut\shortcodeShowcase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductDisplay {
	/**
	 * Constructor to initialize the shortcodes
	 */
	public function __construct() {
		// Register the shortcodes
		add_shortcode( 'shopglut_products', array( $this, 'render_products' ) );
		add_shortcode( 'shopglut_products_grid', array( $this, 'render_products_grid' ) );
		add_shortcode( 'shopglut_products_compact', array( $this, 'render_products_compact' ) );
		add_shortcode( 'shopglut_products_simple', array( $this, 'render_products_simple' ) );
		add_shortcode( 'shopglut_products_flat', array( $this, 'render_products_flat' ) );
		add_shortcode( 'shopglut_products_filter', array( $this, 'render_products_filter' ) );
		add_shortcode( 'shopglut_categories', array( $this, 'render_categories' ) );
		add_shortcode( 'shopglut_tags', array( $this, 'render_tags' ) );
		add_shortcode( 'shopglut_category_blocks', array( $this, 'render_category_blocks' ) );

		// Register scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Register required scripts and styles for the product displays
	 */
	public function register_scripts() {
		// Register CSS for product displays
		wp_register_style(
			'shopglut-product-display-css',
			SHOPGLUT_URL . 'assets/css/product-display.css',
			array(),
			SHOPGLUT_VERSION
		);

		// Register JS for product displays
		wp_register_script(
			'shopglut-product-display-js',
			SHOPGLUT_URL . 'assets/js/product-display.js',
			array( 'jquery' ),
			SHOPGLUT_VERSION,
			true
		);
	}

	/**
	 * Main render_products shortcode - the base for all display variations
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output of the products display
	 */
	public function render_products( $atts ) {
		// Enqueue required scripts and styles
		wp_enqueue_style( 'shopglut-product-display-css' );
		wp_enqueue_script( 'shopglut-product-display-js' );

		// Default shortcode attributes
		$default_atts = array(
			'template' => 'default',       // Template to use
			'cols' => 3,                   // Number of columns
			'colspad' => 2,                // Number of columns on tablet
			'colsphone' => 1,              // Number of columns on mobile
			'items_per_page' => 10,        // Number of products per page
			'orderby' => 'title',          // Order products by
			'order' => 'ASC',              // Order direction
			'category' => '',              // Filter by category slug
			'include_children' => 0,       // Include child categories
			'tag' => '',                   // Filter by tag
			'button_style' => 'default',   // Button style
			'login' => 0,                  // Require login to view
			'last_state' => 0              // Remember last filter state
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_products' );

		// Convert string values to boolean/integer
		$atts['cols'] = intval( $atts['cols'] );
		$atts['colspad'] = intval( $atts['colspad'] );
		$atts['colsphone'] = intval( $atts['colsphone'] );
		$atts['items_per_page'] = intval( $atts['items_per_page'] );
		$atts['include_children'] = filter_var( $atts['include_children'], FILTER_VALIDATE_BOOLEAN );
		$atts['login'] = filter_var( $atts['login'], FILTER_VALIDATE_BOOLEAN );
		$atts['last_state'] = filter_var( $atts['last_state'], FILTER_VALIDATE_BOOLEAN );

		// Check if login is required and user is not logged in
		if ( $atts['login'] && ! is_user_logged_in() ) {
			return $this->get_login_message();
		}

		// Prepare query arguments
		$args = $this->prepare_product_query( $atts );

		// Get products
		$products_query = new \WP_Query( $args );

		// Start output buffering
		ob_start();

		// Generate a unique ID for the container
		$container_id = 'shopglut-products-' . uniqid();

		// Start container
		echo '<div id="' . esc_attr( $container_id ) . '" class="shopglut-products-container template-' . esc_attr( $atts['template'] ) . '">';

		// Check if products exist
		if ( $products_query->have_posts() ) :
			// Add filter/sort controls if needed
			$this->maybe_add_controls( $atts );

			// Start products grid
			echo '<div class="shopglut-products-grid cols-' . esc_attr( $atts['cols'] ) . ' colspad-' . esc_attr( $atts['colspad'] ) . ' colsphone-' . esc_attr( $atts['colsphone'] ) . '">';

			// Loop through products
			while ( $products_query->have_posts() ) :
				$products_query->the_post();
				global $product;

				// Skip if not a valid product
				if ( ! is_a( $product, 'WC_Product' ) ) {
					continue;
				}

				// Get template part based on the template attribute
				$this->get_template_part( 'product', $atts['template'], array(
					'product' => $product,
					'atts' => $atts
				) );

			endwhile;

			// End products grid
			echo '</div>';

			// Add pagination
			$this->add_pagination( $products_query, $atts );

		else :
			// No products found
			echo '<p class="woocommerce-info">' . esc_html__( 'No products found', 'shopglut' ) . '</p>';
		endif;

		// Reset post data
		wp_reset_postdata();

		// End container
		echo '</div>';

		// Return the buffered output
		return ob_get_clean();
	}

	/**
	 * Grid layout shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_products_grid( $atts ) {
		// Set template to grid
		$atts['template'] = 'grid';

		// Use the main render function
		return $this->render_products( $atts );
	}

	/**
	 * Compact layout shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_products_compact( $atts ) {
		// Set template to compact
		$atts['template'] = 'compact';

		// Use the main render function
		return $this->render_products( $atts );
	}

	/**
	 * Simple layout shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_products_simple( $atts ) {
		// Set template to simple
		$atts['template'] = 'simple';

		// Use the main render function
		return $this->render_products( $atts );
	}

	/**
	 * Flat layout shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_products_flat( $atts ) {
		// Set template to flat
		$atts['template'] = 'flat';

		// Use the main render function
		return $this->render_products( $atts );
	}

	/**
	 * Filter layout shortcode - advanced search and filter
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_products_filter( $atts ) {
		// Enqueue required scripts and styles
		wp_enqueue_style( 'shopglut-product-display-css' );
		wp_enqueue_script( 'shopglut-product-display-js' );

		// Default shortcode attributes
		$default_atts = array(
			'template' => 'default',       // Template to use
			'cols' => 3,                   // Number of columns
			'colspad' => 2,                // Number of columns on tablet
			'colsphone' => 1,              // Number of columns on mobile
			'items_per_page' => 12,        // Number of products per page
			'orderby' => 'title',          // Order products by
			'order' => 'ASC',              // Order direction
			'category' => '',              // Filter by category slug
			'include_children' => 0,       // Include child categories
			'order_fields' => '',          // Custom order fields
			'sidebar' => 'left',           // Sidebar position
			'login' => 0                   // Require login to view
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_products_filter' );

		// Convert string values to boolean/integer
		$atts['cols'] = intval( $atts['cols'] );
		$atts['colspad'] = intval( $atts['colspad'] );
		$atts['colsphone'] = intval( $atts['colsphone'] );
		$atts['items_per_page'] = intval( $atts['items_per_page'] );
		$atts['include_children'] = filter_var( $atts['include_children'], FILTER_VALIDATE_BOOLEAN );
		$atts['login'] = filter_var( $atts['login'], FILTER_VALIDATE_BOOLEAN );

		// Check if login is required and user is not logged in
		if ( $atts['login'] && ! is_user_logged_in() ) {
			return $this->get_login_message();
		}

		// Start output buffering
		ob_start();

		// Generate a unique ID for the container
		$container_id = 'shopglut-products-filter-' . uniqid();

		// Start container
		echo '<div id="' . esc_attr( $container_id ) . '" class="shopglut-products-filter-container sidebar-' . esc_attr( $atts['sidebar'] ) . '">';

		// Add filter sidebar
		echo '<div class="shopglut-filter-sidebar">';
		$this->render_filter_sidebar( $atts );
		echo '</div>';

		// Add products container
		echo '<div class="shopglut-filter-products">';

		// Add filter controls
		$this->render_filter_controls( $atts );

		// Add products wrapper (will be populated by AJAX)
		echo '<div class="shopglut-filter-products-wrapper">';
		echo '<div class="shopglut-loading">' . esc_html__( 'Loading products...', 'shopglut' ) . '</div>';
		echo '</div>';

		echo '</div>'; // End products container

		// End container
		echo '</div>';

		// Add inline script to initialize the filter
		$this->add_filter_script( $container_id, $atts );

		// Return the buffered output
		return ob_get_clean();
	}

	/**
	 * Categories shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_categories( $atts ) {
		// Enqueue required scripts and styles
		wp_enqueue_style( 'shopglut-product-display-css' );

		// Default shortcode attributes
		$default_atts = array(
			'subcat' => 1,                 // Show subcategories
			'showcount' => 1,              // Show product count
			'cols' => 3                    // Number of columns
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_categories' );

		// Convert string values to boolean/integer
		$atts['subcat'] = filter_var( $atts['subcat'], FILTER_VALIDATE_BOOLEAN );
		$atts['showcount'] = filter_var( $atts['showcount'], FILTER_VALIDATE_BOOLEAN );
		$atts['cols'] = intval( $atts['cols'] );

		// Get product categories
		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
			'parent' => $atts['subcat'] ? 0 : ''
		);

		$product_categories = get_terms( $args );

		// Start output buffering
		ob_start();

		// Check if categories exist
		if ( ! empty( $product_categories ) ) :
			// Start categories container
			echo '<div class="shopglut-categories-container cols-' . esc_attr( $atts['cols'] ) . '">';

			// Loop through categories
			foreach ( $product_categories as $category ) :
				// Get category image
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail' ) : '';

				// Start category item
				echo '<div class="shopglut-category-item">';

				// Category image
				echo '<div class="shopglut-category-image">';
				echo '<a href="' . esc_url( get_term_link( $category ) ) . '">';
				if ( $image ) {
					echo '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $category->name ) . '" />';
				} else {
					echo wp_kses_post( wc_placeholder_img( 'woocommerce_thumbnail' ) );
				}
				echo '</a>';
				echo '</div>';

				// Category title and count
				echo '<div class="shopglut-category-info">';
				echo '<h3><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></h3>';
				if ( $atts['showcount'] ) {
					echo '<span class="count">(' . esc_html( $category->count ) . ')</span>';
				}
				echo '</div>';

				echo '</div>'; // End category item
			endforeach;

			echo '</div>'; // End categories container
		else :
			echo '<p class="woocommerce-info">' . esc_html__( 'No categories found', 'shopglut' ) . '</p>';
		endif;

		return ob_get_clean();
	}

	/**
	 * Tags shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_tags( $atts ) {
		// Enqueue required scripts and styles
		wp_enqueue_style( 'shopglut-product-display-css' );

		// Default shortcode attributes
		$default_atts = array(
			'showcount' => 1,              // Show product count
			'cols' => 3,                   // Number of columns
			'icon' => 'tag',               // Font awesome icon
			'btnstyle' => 'link'           // Button style
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_tags' );

		// Convert string values to boolean/integer
		$atts['showcount'] = filter_var( $atts['showcount'], FILTER_VALIDATE_BOOLEAN );
		$atts['cols'] = intval( $atts['cols'] );

		// Get product tags
		$args = array(
			'taxonomy' => 'product_tag',
			'hide_empty' => true
		);

		$product_tags = get_terms( $args );

		// Start output buffering
		ob_start();

		// Check if tags exist
		if ( ! empty( $product_tags ) ) :
			// Start tags container
			echo '<div class="shopglut-tags-container cols-' . esc_attr( $atts['cols'] ) . '">';

			// Loop through tags
			foreach ( $product_tags as $tag ) :
				// Start tag item
				echo '<div class="shopglut-tag-item">';

				// Tag link
				echo '<a href="' . esc_url( get_term_link( $tag ) ) . '" class="shopglut-tag-link btn btn-' . esc_attr( $atts['btnstyle'] ) . '">';
				echo '<i class="fa fa-' . esc_attr( $atts['icon'] ) . '"></i> ';
				echo esc_html( $tag->name );
				if ( $atts['showcount'] ) {
					echo ' <span class="count">(' . esc_html( $tag->count ) . ')</span>';
				}
				echo '</a>';

				echo '</div>'; // End tag item
			endforeach;

			echo '</div>'; // End tags container
		else :
			echo '<p class="woocommerce-info">' . esc_html__( 'No tags found', 'shopglut' ) . '</p>';
		endif;

		return ob_get_clean();
	}

	/**
	 * Category blocks shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_category_blocks( $atts ) {
		// Enqueue required scripts and styles
		wp_enqueue_style( 'shopglut-product-display-css' );

		// Default shortcode attributes
		$default_atts = array(
			'categories' => '',            // Category slugs
			'cols' => 4,                   // Number of columns
			'button_color' => 'primary',   // Button color
			'hover_color' => 'primary-hover' // Hover color
		);

		// Parse shortcode attributes
		$atts = shortcode_atts( $default_atts, $atts, 'shopglut_category_blocks' );

		// Convert string values to integer
		$atts['cols'] = intval( $atts['cols'] );

		// Get categories
		$category_slugs = ! empty( $atts['categories'] ) ? explode( ',', $atts['categories'] ) : array();
		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true
		);

		if ( ! empty( $category_slugs ) ) {
			$args['slug'] = $category_slugs;
		}

		$product_categories = get_terms( $args );

		// Start output buffering
		ob_start();

		// Check if categories exist
		if ( ! empty( $product_categories ) ) :
			// Start category blocks container
			echo '<div class="shopglut-category-blocks-container cols-' . esc_attr( $atts['cols'] ) . '">';

			// Loop through categories
			foreach ( $product_categories as $category ) :
				// Get category image
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail' ) : '';

				// Start category block
				echo '<div class="shopglut-category-block ' . esc_attr( $atts['button_color'] ) . ' ' . esc_attr( $atts['hover_color'] ) . '">';

				// Category image
				echo '<div class="shopglut-category-block-image">';
				if ( $image ) {
					echo '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $category->name ) . '" />';
				} else {
					echo wp_kses_post( wc_placeholder_img( 'woocommerce_thumbnail' ) );
				}
				echo '</div>';

				// Category info
				echo '<div class="shopglut-category-block-info">';
				echo '<h3>' . esc_html( $category->name ) . '</h3>';
				echo '<p>' . esc_html( $category->description ) . '</p>';
				echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="shopglut-category-block-button">' . esc_html__( 'View Products', 'shopglut' ) . '</a>';
				echo '</div>';

				echo '</div>'; // End category block
			endforeach;

			echo '</div>'; // End category blocks container
		else :
			echo '<p class="woocommerce-info">' . esc_html__( 'No categories found', 'shopglut' ) . '</p>';
		endif;

		return ob_get_clean();
	}

	/**
	 * Prepare product query arguments based on shortcode attributes
	 * 
	 * @param array $atts Shortcode attributes
	 * @return array Query arguments
	 */
	private function prepare_product_query( $atts ) {
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $atts['items_per_page'],
			'orderby' => $atts['orderby'],
			'order' => $atts['order'],
			'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1
		);

		// Filter by category if specified
		if ( ! empty( $atts['category'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => explode( ',', $atts['category'] ),
				'operator' => 'IN',
				'include_children' => $atts['include_children']
			);
		}

		// Filter by tag if specified
		if ( ! empty( $atts['tag'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => explode( ',', $atts['tag'] ),
				'operator' => 'IN'
			);
		}

		return $args;
	}

	/**
	 * Add filter and sort controls to the products display
	 * 
	 * @param array $atts Shortcode attributes
	 */
	private function maybe_add_controls( $atts ) {
		// Only add controls if last_state is enabled
		if ( $atts['last_state'] ) {
			echo '<div class="shopglut-products-controls">';

			// Add sorting dropdown
			echo '<div class="shopglut-products-sorting">';
			echo '<label>' . esc_html__( 'Sort by:', 'shopglut' ) . '</label>';
			echo '<select class="shopglut-sort-select">';
			echo '<option value="title-asc">' . esc_html__( 'Title (A-Z)', 'shopglut' ) . '</option>';
			echo '<option value="title-desc">' . esc_html__( 'Title (Z-A)', 'shopglut' ) . '</option>';
			echo '<option value="price-asc">' . esc_html__( 'Price (Low to High)', 'shopglut' ) . '</option>';
			echo '<option value="price-desc">' . esc_html__( 'Price (High to Low)', 'shopglut' ) . '</option>';
			echo '<option value="date-desc">' . esc_html__( 'Newest First', 'shopglut' ) . '</option>';
			echo '<option value="date-asc">' . esc_html__( 'Oldest First', 'shopglut' ) . '</option>';
			echo '</select>';
			echo '</div>';

			echo '</div>';
		}
	}

	/**
	 * Add pagination to the products display
	 * 
	 * @param WP_Query $query The products query
	 * @param array $atts Shortcode attributes
	 */
	private function add_pagination( $query, $atts ) {
		$total_pages = $query->max_num_pages;

		if ( $total_pages > 1 ) {
			echo '<div class="shopglut-products-pagination">';

			$current_page = max( 1, get_query_var( 'paged' ) );

			echo wp_kses_post( paginate_links( array(
				'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format' => '?paged=%#%',
				'current' => $current_page,
				'total' => $total_pages,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type' => 'list',
				'end_size' => 3,
				'mid_size' => 3
			) ) );

			echo '</div>';
		}
	}

	/**
	 * Get template part for product display
	 * 
	 * @param string $slug Template slug
	 * @param string $name Template name
	 * @param array $args Template arguments
	 */
	private function get_template_part( $slug, $name, $args = array() ) {
		// Extract args to make them available in the template
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		// Look for template in theme first
		$template = locate_template( array(
			'shopglut/' . $slug . '-' . $name . '.php',
			'shopglut/' . $slug . '.php'
		) );

		// If not found in theme, use plugin template
		if ( ! $template ) {
			$template = SHOPGLUT_PATH . 'templates/' . $slug . '-' . $name . '.php';

			if ( ! file_exists( $template ) ) {
				$template = SHOPGLUT_PATH . 'templates/' . $slug . '.php';
			}
		}

		// Include the template if it exists
		if ( file_exists( $template ) ) {
			include $template;
		}
	}

	/**
	 * Render filter sidebar for the filter layout
	 * 
	 * @param array $atts Shortcode attributes
	 */
	private function render_filter_sidebar( $atts ) {
		// Categories filter
		echo '<div class="shopglut-filter-widget">';
		echo '<h4>' . esc_html__( 'Categories', 'shopglut' ) . '</h4>';

		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
			'parent' => 0
		);

		$product_categories = get_terms( $args );

		if ( ! empty( $product_categories ) ) {
			echo '<ul class="shopglut-filter-categories">';

			foreach ( $product_categories as $category ) {
				echo '<li>';
				echo '<label>';
				echo '<input type="checkbox" name="category[]" value="' . esc_attr( $category->slug ) . '" class="shopglut-filter-checkbox"> ';
				echo esc_html( $category->name ) . ' <span class="count">(' . esc_html( $category->count ) . ')</span>';
				echo '</label>';

				// Show subcategories if any
				$subcategories = get_terms( array(
					'taxonomy' => 'product_cat',
					'hide_empty' => true,
					'parent' => $category->term_id
				) );

				if ( ! empty( $subcategories ) ) {
					echo '<ul class="shopglut-filter-subcategories">';

					foreach ( $subcategories as $subcategory ) {
						echo '<li>';
						echo '<label>';
						echo '<input type="checkbox" name="category[]" value="' . esc_attr( $subcategory->slug ) . '" class="shopglut-filter-checkbox"> ';
						echo esc_html( $subcategory->name ) . ' <span class="count">(' . esc_html( $subcategory->count ) . ')</span>';
						echo '</label>';
						echo '</li>';
					}

					echo '</ul>';
				}

				echo '</li>';
			}

			echo '</ul>';
		} else {
			echo '<p>' . esc_html__( 'No categories found', 'shopglut' ) . '</p>';
		}

		echo '</div>';

		// Price filter
		echo '<div class="shopglut-filter-widget">';
		echo '<h4>' . esc_html__( 'Price Range', 'shopglut' ) . '</h4>';

		// Get min and max prices from products
		$min_price = floor( wc_get_price_to_display( wc_get_product( wc_get_min_price_product_id() ) ) );
		$max_price = ceil( wc_get_price_to_display( wc_get_product( wc_get_max_price_product_id() ) ) );

		echo '<div class="shopglut-price-slider-container">';
		echo '<div class="shopglut-price-slider" data-min="' . esc_attr( $min_price ) . '" data-max="' . esc_attr( $max_price ) . '"></div>';
		echo '<div class="shopglut-price-slider-values">';
		echo '<span class="shopglut-price-slider-min">' . esc_html( get_woocommerce_currency_symbol() ) . esc_html( $min_price ) . '</span>';
		echo '<span class="shopglut-price-slider-max">' . esc_html( get_woocommerce_currency_symbol() ) . esc_html( $max_price ) . '</span>';
		echo '</div>';
		echo '</div>';

		echo '</div>';

		// Tags filter
		echo '<div class="shopglut-filter-widget">';
		echo '<h4>' . esc_html__( 'Tags', 'shopglut' ) . '</h4>';

		$product_tags = get_terms( array(
			'taxonomy' => 'product_tag',
			'hide_empty' => true
		) );

		if ( ! empty( $product_tags ) ) {
			echo '<div class="shopglut-filter-tags">';

			foreach ( $product_tags as $tag ) {
				echo '<label class="shopglut-filter-tag">';
				echo '<input type="checkbox" name="tag[]" value="' . esc_attr( $tag->slug ) . '" class="shopglut-filter-checkbox"> ';
				echo esc_html( $tag->name );
				echo '</label>';
			}

			echo '</div>';
		} else {
			echo '<p>' . esc_html__( 'No tags found', 'shopglut' ) . '</p>';
		}

		echo '</div>';

		// Filter button
		echo '<div class="shopglut-filter-widget">';
		echo '<button class="shopglut-apply-filters button">' . esc_html__( 'Apply Filters', 'shopglut' ) . '</button>';
		echo '<button class="shopglut-reset-filters button">' . esc_html__( 'Reset Filters', 'shopglut' ) . '</button>';
		echo '</div>';
	}

	/**
	 * Render filter controls for the filter layout
	 * 
	 * @param array $atts Shortcode attributes
	 */
	private function render_filter_controls( $atts ) {
		echo '<div class="shopglut-filter-controls">';

		// Sort dropdown
		echo '<div class="shopglut-filter-sort">';
		echo '<label>' . esc_html__( 'Sort by:', 'shopglut' ) . '</label>';
		echo '<select class="shopglut-sort-select">';

		// Parse custom order fields if provided
		if ( ! empty( $atts['order_fields'] ) ) {
			$order_fields = explode( '|', $atts['order_fields'] );

			foreach ( $order_fields as $field ) {
				$field_parts = explode( ':', $field );

				if ( count( $field_parts ) === 2 ) {
					$field_key = trim( $field_parts[0] );
					$field_label = trim( $field_parts[1] );

					echo '<option value="' . esc_attr( $field_key ) . '">' . esc_html( $field_label ) . '</option>';
				}
			}
		} else {
			// Default sort options
			echo '<option value="title-asc">' . esc_html__( 'Title (A-Z)', 'shopglut' ) . '</option>';
			echo '<option value="title-desc">' . esc_html__( 'Title (Z-A)', 'shopglut' ) . '</option>';
			echo '<option value="price-asc">' . esc_html__( 'Price (Low to High)', 'shopglut' ) . '</option>';
			echo '<option value="price-desc">' . esc_html__( 'Price (High to Low)', 'shopglut' ) . '</option>';
			echo '<option value="date-desc">' . esc_html__( 'Newest First', 'shopglut' ) . '</option>';
			echo '<option value="date-asc">' . esc_html__( 'Oldest First', 'shopglut' ) . '</option>';
		}

		echo '</select>';
		echo '</div>';

		// Mobile filter toggle
		echo '<div class="shopglut-filter-toggle">';
		echo '<button class="shopglut-filter-toggle-button button">' . esc_html__( 'Filters', 'shopglut' ) . '</button>';
		echo '</div>';

		echo '</div>';
	}

	/**
	 * Add inline script to initialize the filter
	 * 
	 * @param string $container_id Container ID
	 * @param array $atts Shortcode attributes
	 */
	private function add_filter_script( $container_id, $atts ) {
		// Prepare filter settings
		$settings = array(
			'containerId' => $container_id,
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'shopglut_filter_nonce' ),
			'cols' => $atts['cols'],
			'colspad' => $atts['colspad'],
			'colsphone' => $atts['colsphone'],
			'template' => $atts['template'],
			'itemsPerPage' => $atts['items_per_page'],
			'category' => $atts['category'],
			'includeChildren' => $atts['include_children'],
			'orderby' => $atts['orderby'],
			'order' => $atts['order']
		);

		// Add inline script
		wp_add_inline_script( 'shopglut-product-display-js', 'jQuery(document).ready(function($) { ShopglutProductFilter.init(' . json_encode( $settings ) . '); });' );
	}

	/**
	 * Get login message for login-protected shortcodes
	 * 
	 * @return string Login message HTML
	 */
	private function get_login_message() {
		ob_start();

		echo '<div class="shopglut-login-required">';
		echo '<p>' . esc_html__( 'You must be logged in to view this content.', 'shopglut' ) . '</p>';

		// Show login form if not logged in
		if ( ! is_user_logged_in() ) {
			echo '<div class="shopglut-login-form">';
			wc_get_template( 'myaccount/form-login.php' );
			echo '</div>';
		}

		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Get instance of the class
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}