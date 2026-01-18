<?php

namespace Shopglut\layouts\shopLayout;



class shopg_design_shop {

	public function __construct() {


	}


	public function appearance() {

		get_header();

		do_action( 'woocommerce_before_main_content' );

		// Get the passed layout ID

		global $wpdb, $post;

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

		<div class="woocommerce shopglut-shop-container">
			<div class="shopglut-shop-wrapper">

				<main class="shopglut-shop-main">
					 <?php  
			$shop_contents_instance = new ShopContents();
			$shop_contents_instance->custom_display_filters_contents( $layout_array_values, $layout_id, $layout_values, $paged );		
			?>
				</main>
			</div>
		</div>
		<?php

		$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
		$dynamic_css = $dynamic_style->dynamicCss( $layout_id );

		echo '<style type="text/css">' . esc_html( wp_strip_all_tags( $dynamic_css ) ) . '</style>';

		do_action( 'woocommerce_after_main_content' );


		get_footer();


	}

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}

}
