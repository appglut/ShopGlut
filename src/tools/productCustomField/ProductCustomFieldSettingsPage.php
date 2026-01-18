<?php
namespace Shopglut\tools\productCustomField;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductCustomFieldSettingsPage {

	public $menu_slug = 'shopglut_product_custom_field';

	public function __construct() {

	}

	public function loadProductCustomFieldEditor() {


		$field_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['field_id'] ) ? absint( $_GET['field_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'save_shopg_product_custom_field_data', $field_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

		// Get layout data with caching
		$cache_key = "shopglut_product_custom_field_layout_{$field_id}";
		$layout_data = wp_cache_get($cache_key, 'shopglut_product_custom_field');

		if (false === $layout_data) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation, caching implemented below
			$layout_data = $wpdb->get_row(
				sprintf("SELECT field_name FROM %s WHERE id = %d",
					esc_sql($wpdb->prefix . 'shopglut_product_custom_field_settings'),
					absint($field_id)
				)
			);

			// Cache for 10 minutes since layout data doesn't change frequently
			wp_cache_set($cache_key, $layout_data, 'shopglut_product_custom_field', 10 * MINUTE_IN_SECONDS);
		}

		if ( $layout_data ) {
			$layout_name = $layout_data->field_name;
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
							$shopg_cpage_nonce = wp_create_nonce( 'shopg_product_custom_field_layouts' ); 
							?>
							<input type="hidden" name="shopg_product_custom_field_layouts_nonce" value="<?php echo esc_attr( $shopg_cpage_nonce ); ?>">
							<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
								value="<?php echo esc_attr( $field_id ); ?>">

							<div class="shopglut_layout_contents">

								<div class="shopglut_editor_header">

									<div class="back-to-menu">

										<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=product_custom_field' ) ); ?>"
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
								<div id="post-body" class="metabox-holder columns-1">

									<div id="shopg-product_custom_field-layout-container" class="shopg-admin-edit-panel shopg-layout-container" style="width: 100%; max-width: 100%;">

										<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>

									</div>

									<div class="submitbox" id="submitpost" style="margin-top: 20px;">
											<div id="product_custom_fieldLayout-publishing-action" class="shopg-publishing-button">
												<button type="button" id="product_custom_field-reset-settings-button" class="btn btn-fullwidth btn-secondary"
												style=" background: #dc3545; color: white; border: none;">
												<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
												</button>
												<input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
												value="<?php echo esc_attr__( 'Save Layout', 'shopglut' ); ?>">

											</div>
											<div class="clear"></div>
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