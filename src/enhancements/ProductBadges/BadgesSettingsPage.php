<?php
namespace Shopglut\enhancements\ProductBadges;

class BadgesSettingsPage {

	public $menu_slug = 'shopglut_badges';

	public function __construct() {

	}

	public function BadgeEditor() {

		$badge_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['badge_id'] ) ? absint( $_GET['badge_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'shopglut_save_badge_data', $badge_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

		// Check if this is a template request (badge_id parameter from template selector)
		$is_template_request = isset( $_GET['badge_id'] );
		$template_id = $is_template_request ? sanitize_text_field( wp_unslash( $_GET['badge_id'] ) ) : $badge_id;

		$badge_data = null;
		$badge_name = '';

		if ( $is_template_request ) {

			// Use caching and proper table name escaping
			$table_name = \Shopglut\ShopGlutDatabase::table_product_badges();
			$cache_key = "shopglut_badge_name_{$badge_id}";
			$badge_data = wp_cache_get( $cache_key, 'shopglut_badges' );

			if ( false === $badge_data ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
				$badge_data = $wpdb->get_row(
					$wpdb->prepare( "SELECT layout_name FROM `" . esc_sql($table_name) . "` WHERE id = %d", $badge_id )
				);

				// Cache the result for 30 minutes
				wp_cache_set( $cache_key, $badge_data, 'shopglut_badges', 30 * MINUTE_IN_SECONDS );
			}

			if ( $badge_data ) {
				$badge_name = $badge_data->layout_name;
			}
		}

		if ( $badge_data ) {
			// Set up form for editing
			if ( !$is_template_request ) {
				?>
				<input type="hidden" id="shopg_badge_id" name="shopg_badge_id" value="<?php echo esc_attr( $badge_data->id ); ?>">
				<?php
			}
		} else {
			?>
			<div class="wrap">
				<p><?php esc_html_e( 'No badge data found.', 'shopglut' ); ?></p>
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

	    <form id="shopglut_product_badge_layouts" method="post" action="">

				<?php
				$shopg_badge_nonce = wp_create_nonce( 'shopg_productbadge_nonce' );
				?>
				<input type="hidden" name="shopg_productbadge_nonce" value="<?php echo esc_attr( $shopg_badge_nonce ); ?>">
				<input type="hidden" name="shopg_badge_id" id="shopg_badge_id"
					value="<?php echo esc_attr( $badge_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="shopglut_editor_header">

						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_badges' ) ); ?>"
								class="button button-secondary button-large">
								<i class="fa-solid fa-angles-left"></i>
								<?php echo esc_html__( 'Back To Menu', 'shopglut' ); ?>
							</a>

							<div class="clear"></div>
						</div>

						<div class="shopglut_layout_name">
							<label for="badge_name"><?php esc_html_e( 'Badge Name:', 'shopglut' ); ?></label>
							<input type="text" id="badge_name" name="badge_name"
								value="<?php echo esc_html( $badge_name ); ?>" />
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

						<div id="shopg-productbadge-settings" class="postbox-container shopg-layout-settings-wrapper">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>

						</div>
						<button type="button" id="toggle-settings-button" class="toggle-button"><?php echo esc_html__('Hide', 'shopglut');  ?></button>
						<div class="submitbox" id="submitpost">
								<div id="productbadge-publishing-action" class="shopg-publishing-button">
									<button type="button" id="productbadge-reset-settings-button" class="btn btn-fullwidth btn-secondary"
									style=" background: #dc3545; color: white; border: none;">
									<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
									</button>
									<button type="button" name="publish" id="productbadge-save-badge-button" class="btn btn-fullwidth">
										<?php echo esc_attr__( 'Save Badge', 'shopglut' ); ?>
									</button>

								</div>
								<div class="clear"></div>
						</div>
						<div id="shopg-productbadge-container" class="shopg-admin-edit-panel shopg-layout-container">

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

			/* When loader is visible, prevent interaction with badge preview */
			.loader-overlay:not([style*="display: none"]) ~ * .shopglut-product-badge-preview {
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
