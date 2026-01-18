<?php
namespace Shopglut\layouts\shopLayout;

class SettingsPage {

	public $menu_slug = 'shopglut_layouts';

	public function __construct() {

	}

	public function loadShoplayoutEditor() {

		$layout_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'save_shopg_layout_data', $layout_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$layout_data = $wpdb->get_row( $wpdb->prepare( "SELECT layout_name, layout_template FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $layout_id ) );

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
				$shopg_cpage_nonce = wp_create_nonce( 'shopg_shoplayouts_layouts' );
				?>
				<input type="hidden" name="shopg_shoplayouts_layouts_nonce" value="<?php echo esc_attr( $shopg_cpage_nonce ); ?>">
				<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
					value="<?php echo esc_attr( $layout_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="shopglut_editor_header">

						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=shop' ) ); ?>"
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
									[shopg_shop_layout id="<?php echo esc_attr( $layout_id ); ?>"]</span>
							</span>
						</div>

						<div class="shopglut_php_scode">
							<span class="shopglut__sc-title"><?php echo esc_html__( "PHP Code:", 'shopglut' ); ?> </span>
							<span class="shopglut__shortcode-selectable">
								<i class="agl--icon far fa-copy"></i>
								<span class="shopglut_lcopy-text">&lt;?php echo do_shortcode('[shopg_shop_layout
									id="<?php echo esc_attr( $layout_id ); ?>"]');?&gt;</span>
							</span>
						</div>
					</div>


				<div id="shopg-notification-container"></div>

				</div>


				<div id="poststuff" class="shopglut-shoplayouts">
					<div id="post-body" class="metabox-holder columns-2">

				
			   <div id="post-body" class="metabox-holder columns-2">

						<div id="shoplayouts-layout-settings" class="postbox-container shopg-layout-settings-wrapper">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>

						</div>
						<button type="button" id="toggle-settings-button" class="toggle-button"><?php echo esc_html__('Hide', 'shopglut');  ?></button>
						<div class="submitbox" id="submitpost">
								<div id="shoplayoutsLayout-publishing-action" class="shopg-publishing-button">
									<button type="button" id="shoplayouts-reset-settings-button" class="btn btn-fullwidth btn-secondary"
									style=" background: #dc3545; color: white; border: none;">
									<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
									</button>
									<input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
									value="<?php echo esc_attr__( 'Save Layout', 'shopglut' ); ?>">

								</div>
								<div class="clear"></div>
						</div>
						<div id="shoplayouts-layout-container" class="shopg-admin-edit-panel shopg-layout-container">

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