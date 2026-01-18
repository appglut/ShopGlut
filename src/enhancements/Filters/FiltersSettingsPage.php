<?php
namespace Shopglut\enhancements\Filters;

class FiltersSettingsPage {
	public $menu_slug = 'shopglut_enhancements';

	public function __construct() {

	}

	public function FilterSettings() {

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		$filter_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['filter_id'] ) ? absint( $_GET['filter_id'] ) : 1;
		global $wpdb;

		$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';

		$cache_key = 'shopglut_filter_name_' . $filter_id;
		$result = wp_cache_get($cache_key);
		if ($result === false) {
			$sql = sprintf( "SELECT filter_name FROM %s WHERE id = %%d", esc_sql( $table_name ) );
			$result = $wpdb->get_row( $wpdb->prepare( $sql, $filter_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
			wp_cache_set($cache_key, $result, '', 300); // Cache for 5 minutes
		}

		if ( $result ) {
			$filter_name = $result->filter_name;
		}

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		?>
		<div id="shopg-layout-admin-settings" class="wrap shopg-filter-admin-page layout_settings">

			<div class="loader-overlay">
				<div class="loader-container">
					<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>

	<form id="shopglut_shop_filter" method="post" action="">

				<?php $shopg_filter_nonce = wp_create_nonce( 'shopFilters_nonce' ); ?>
				<input type="hidden" name="shopg_shop_filters_nonce" value="<?php echo esc_attr( $shopg_filter_nonce ); ?>">
				<input type="hidden" name="shopg_shop_filter_id" id="shopg_shop_filter_id"
					value="<?php echo esc_attr( $filter_id ); ?>">

				<div class="shopglut_filter_contents">

					<div class="filter_editor_menucontent">
						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=shop_filters' ) ); ?>"
								class="button button-secondary button-large">
								<i class="fa-solid fa-angles-left"></i>
								<?php echo esc_html__( 'Back To Menu', 'shopglut' ); ?>
							</a>

							<div class="clear"></div>
						</div>

						<div class="shopglut_filter_name">
							<label for="filter_name"><?php esc_html_e( 'Filter Name:', 'shopglut' ); ?></label>
							<input type="text" id="filter_name" name="filter_name"
								value="<?php echo esc_html( $filter_name ); ?>" />
						</div>
					</div>

					<div class="shopglut_filter_caption">
						<i class="fa-solid fa-circle-info"></i>
						<p class="info"><?php echo esc_html__( 'Info:', 'shopglut' ); ?></p>
						<p><?php echo esc_html__( 'Save Filter and see the update Preview', 'shopglut' ); ?></p>

					</div>

				</div>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder filter-editor">

						<div id="shopg-filter-container" class="shopg-admin-edit-panel postbox-container">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>
						</div>

						<div class="submitbox" id="submitpost">
								<div id="filterLayout-publishing-action" class="shopg-publishing-button">
									<input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
									value="<?php echo esc_attr__( 'Save Filter', 'shopglut' ); ?>">
									
								</div>
								<div class="clear"></div>
						</div>

						<div id="shopg-filter-preview">
							<div id="postbox-container-1" class="postbox-container">
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