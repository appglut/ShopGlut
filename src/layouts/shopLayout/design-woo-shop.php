<?php
// design-woocommerce-taxonomy.php

defined( 'ABSPATH' ) || exit;

// Load the active theme's header
get_header();
// Get the passed layout ID

global $wpdb, $post;

$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

$layout_id = get_query_var( 'enabled_shop_layout_id' );


if ( ! $layout_id ) {
	return esc_html__( 'Invalid layout ID', 'shopglut' );
}

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
$layout_values = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $layout_id ) );

if ( empty( $layout_values ) ) {
	return esc_html__( 'Layout not found', 'shopglut' );
}

$layout_array_values = unserialize( $layout_values[0]->layout_settings );


?>

<div id="shopg_shop_contents">
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
					$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;

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
		//$pagination = new pagination();
		//echo $pagination->render_pagination( $pagination_style, $paged, $product_query->max_num_pages, $post_id );
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

$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
$dynamic_css = $dynamic_style->dynamicCss( $layout_id );

echo '<style type="text/css">' . esc_html( wp_strip_all_tags( $dynamic_css ) ) . '</style>';

get_footer();

