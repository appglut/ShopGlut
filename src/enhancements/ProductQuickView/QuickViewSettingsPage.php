<?php
namespace Shopglut\enhancements\ProductQuickView;

class QuickViewSettingsPage {

	public $menu_slug = 'product_quickview';

	public function __construct() {

	}

	public function loadProductQuickviewEditor() {


		$layout_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'shopglut_save_layout_data', $layout_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

		// Check cache first
		$cache_key = "shopglut_quickview_layout_data_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_quickview' );

		if ( false === $layout_data ) {
			$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';
			$layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
				$wpdb->prepare( "SELECT layout_name, layout_template FROM `" . esc_sql($table_name) . "` WHERE id = %d", $layout_id ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using $wpdb->prepare with proper placeholders
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_quickview', 30 * MINUTE_IN_SECONDS );
		}

		if ( $layout_data ) {
			$layout_name = $layout_data->layout_name;
			$layout_template = $layout_data->layout_template;
		} else {
			?>
			<div class="wrap">
				<p><?php esc_html_e( 'No layout data found.', 'shopglut' ); ?></p>
			</div>
			<?php
			return;
		}

		?>
		<div id="shopg-layout-admin-settings" class="wrap layout_settings">

			<div class="loader-overlay" style="display: flex; opacity: 1;">
				<div class="loader-container">
					<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>

	    <form id="shopglut_shop_layouts" method="post" action="">

				<?php 
				$shopg_cpage_nonce = wp_create_nonce( 'shopg_productquickview_layouts' ); 
				?>
				<input type="hidden" name="shopg_productquickview_layouts_nonce" value="<?php echo esc_attr( $shopg_cpage_nonce ); ?>">
				<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
					value="<?php echo esc_attr( $layout_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="shopglut_editor_header">

						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' ) ); ?>"
								class="button button-secondary button-large">
								<i class="fa-solid fa-angles-left"></i>
								<?php echo esc_html__( 'Back To Menu', 'shopglut' ); ?>
							</a>

							<div class="clear"></div>
						</div>

						<div class="shopglut_layout_name">
							<label for="layout_name"><?php esc_html_e( 'Layout Name:', 'shopglut' ); ?></label>
							<input type="text" id="layout_name" name="layout_name"
								value="<?php echo esc_html( $layout_name ); ?>" />
							<input type="hidden" id="layout_template" name="layout_template"
								value="<?php echo esc_html( $layout_template ); ?>" />
						</div>

					</div>

					<div class="shopglut_layout_caption">
						<i class="fa-solid fa-circle-info"></i>
						<p class="info"><?php echo esc_html__( 'Info:', 'shopglut' ); ?></p>
						<p><?php echo esc_html__( 'Save Layout and see the update Preview', 'shopglut' ); ?></p>
					</div>


				<div id="shopg-notification-container"></div>

				</div>


				<div id="poststuff" class="shopglut-shoplayouts">
					<div id="post-body" class="metabox-holder columns-2">

				
			   <div id="post-body" class="metabox-holder columns-2">

						<div id="shopg-productquickview-layout-settings" class="postbox-container shopg-layout-settings-wrapper">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>

						</div>
						<button type="button" id="toggle-settings-button" class="toggle-button"><?php echo esc_html__('Hide', 'shopglut');  ?></button>
						<div class="submitbox" id="submitpost">
								<div id="productquickviewLayout-publishing-action" class="shopg-publishing-button">
									<button type="button" id="productquickview-reset-settings-button" class="btn btn-fullwidth btn-secondary"
									style=" background: #dc3545; color: white; border: none;">
									<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
									</button>
									<button type="button" name="publish" id="productquickview-save-layout-button" class="btn btn-fullwidth">
										<?php echo esc_attr__( 'Save Layout', 'shopglut' ); ?>
									</button>

								</div>
								<div class="clear"></div>
						</div>
						<div id="shopg-productquickview-layout-container" class="shopg-admin-edit-panel shopg-layout-container">
							
							<?php do_meta_boxes( $this->menu_slug, 'normal', '' ); ?>
						</div>
				</div>


			</div>

				</div>
			</div>

        </form>

		</div>

		<style>
			html.wp-toolbar {
				padding-top: 0px !important;
			}

			.loader-overlay {
				z-index: 9999999 !important;
			}

			/* When loader is visible, prevent interaction with quickview demo */
			.loader-overlay:not([style*="display: none"]) ~ * .shopglut-product-quickview {
				pointer-events: none !important;
				opacity: 0.3 !important;
			}
		</style>

		<?php
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}