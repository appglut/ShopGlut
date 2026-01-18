<?php
namespace Shopglut\layouts\cartPage;

class SettingsPage {

	public $menu_slug = 'cartpage';


	public function loadCartPageEditor() {

		$layout_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'save_shopg_layout_data', $layout_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

        // Use caching and proper table name escaping
		$table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';
		$cache_key = "shopglut_cartpage_layout_data_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_cartpage' );

		if ( false === $layout_data ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$layout_data = $wpdb->get_row(
				sprintf( "SELECT layout_name, layout_template FROM `%s` WHERE id = %d", esc_sql( $table_name ), $layout_id ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated ID
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $layout_data, 'shopglut_cartpage', 30 * MINUTE_IN_SECONDS );
		}

		if ( $layout_data ) {
			$layout_name = $layout_data->layout_name;
			$layout_template = $layout_data->layout_template;
		} else {
			// Check if the table exists
			$table_exists = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for table existence check
				sprintf( "SHOW TABLES LIKE '%s'", esc_sql( $table_name ) ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name
			) === $table_name;

			if ( ! $table_exists ) {
				?>
				<div class="wrap">
					<h3><?php esc_html_e( 'Database Table Missing', 'shopglut' ); ?></h3>
					<p><?php esc_html_e( 'The cartpage layouts table does not exist. Please deactivate and reactivate the plugin to create the required database tables.', 'shopglut' ); ?></p>
					<p><strong><?php esc_html_e( 'Table name:', 'shopglut' ); ?></strong> <?php echo esc_html( $table_name ); ?></p>
				</div>
				<?php
				return;
			}

			// Check if any layouts exist
			$layout_count = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for layout count check
				sprintf( "SELECT COUNT(*) FROM `%s`", esc_sql( $table_name ) ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name
			);

			if ( $layout_count === 0 ) {
				?>
				<div class="wrap">
					<h3><?php esc_html_e( 'No Cart Page Layouts Found', 'shopglut' ); ?></h3>
					<p><?php esc_html_e( 'No cart page layouts have been created yet.', 'shopglut' ); ?></p>
					<p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Create Your First Cart Page Layout', 'shopglut' ); ?>
						</a>
					</p>
				</div>
				<?php
				return;
			} else {
				// Layout exists but this specific ID wasn't found
				$first_layout_id = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for fallback layout ID
					sprintf( "SELECT id FROM `%s` ORDER BY id ASC LIMIT 1", esc_sql( $table_name ) ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name
				);
				?>
				<div class="wrap">
					<h3><?php esc_html_e( 'Layout Not Found', 'shopglut' ); ?></h3>
					<p>
					<?php
					printf(
						/* translators: 1: layout ID, 2: layout count */
						esc_html__( 'Cart page layout with ID %1$d was not found, but %2$d layout(s) exist in the database.', 'shopglut' ),
						absint( $layout_id ),
						absint( $layout_count )
					);
					?>
					</p>
					<p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&editor=cartpage&layout_id=' . $first_layout_id ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'View First Available Layout', 'shopglut' ); ?>
						</a>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ) ); ?>" class="button">
							<?php esc_html_e( 'View All Layouts', 'shopglut' ); ?>
						</a>
					</p>
				</div>
				<?php
				return;
			}
		}

		?>
		<div id="shopg-layout-admin-settings" class="wrap layout_settings shopglut-cart_settings">

			<div class="loader-overlay" style="display: flex; opacity: 1;">
				<div class="loader-container">
					<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>

	<form id="shopglut_shop_layouts" method="post" action="">

				<?php 
				$shopg_cpage_nonce = wp_create_nonce( 'shopg_cartpage_layouts' ); 
				?>
				<input type="hidden" name="shopg_cartpage_layouts_nonce" value="<?php echo esc_attr( $shopg_cpage_nonce ); ?>">
				<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
					value="<?php echo esc_attr( $layout_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="shopglut_editor_header">

						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ) ); ?>"
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

					<div class="shopglut_layout_shortcode">
						<div class="shopglut_php_scode">
							<span class="shopglut__sc-title"><?php echo esc_html__( "Shortcode:", "shopglut" ); ?></span>
							<span class="shopglut__shortcode-selectable">
								<i class="agl--icon far fa-copy"></i>
								<span class="shopglut_lcopy-text">
									[shopglut_cart_page id="<?php echo esc_attr( $layout_id ); ?>"]</span>
							</span>
						</div>

						<div class="shopglut_php_scode">
							<span class="shopglut__sc-title"><?php echo esc_html__( "PHP Code:", 'shopglut' ); ?> </span>
							<span class="shopglut__shortcode-selectable">
								<i class="agl--icon far fa-copy"></i>
								<span class="shopglut_lcopy-text">&lt;?php echo do_shortcode('[shopglut_cart_page
									id="<?php echo esc_attr( $layout_id ); ?>"]');?&gt;</span>
							</span>
						</div>
					</div>


				<div id="shopg-notification-container"></div>

				</div>

                <div id="poststuff" class="shopglut-shoplayouts">
				
				<div id="post-body" class="metabox-holder columns-2">

						<div id="shopg-cart-layout-settings" class="postbox-container shopg-layout-settings-wrapper">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>
							
						</div>
						<button type="button" id="toggle-settings-button" class="toggle-button"><?php echo esc_html__('Hide', 'shopglut');  ?></button>
						<div class="submitbox" id="submitpost">
								<div id="cartLayout-publishing-action">
									<button type="button" id="reset-settings-button" class="btn btn-fullwidth btn-secondary"
									style=" background: #dc3545; color: white; border: none;">
									<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
									</button>
									<input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
									value="<?php echo esc_attr__( 'Save Layout', 'shopglut' ); ?>">
									
								</div>
								<div class="clear"></div>
						</div>
						<div id="shopg-cart-layout-container" class="shopg-admin-edit-panel shopg-layout-container">
							
							<?php do_meta_boxes( $this->menu_slug, 'normal', '' ); ?>
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