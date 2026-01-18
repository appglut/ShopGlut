<?php
namespace Shopglut\wooTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\tools\wooTemplates\WooTemplatesEntity;

class WooTemplates {
	private static $instance = null;
	private $menu_slug = 'shopglut_woo_templates';

	public function __construct() {
		// Add body class for the template editor page
		add_filter( 'admin_body_class', array( $this, 'wooTemplatesBodyClass' ) );
	}

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add body class for the template editor
	 */
	public function wooTemplatesBodyClass( $classes ) {
		$current_screen = get_current_screen();

		if ( empty( $current_screen ) ) {
			return $classes;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class assignment, no form processing
		if ( isset( $_GET['page'] ) && $this->menu_slug === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'woo_template' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) && isset( $_GET['template_id'] ) ) {
			$classes .= ' shopglut-woo-template-editor';
		}

		return $classes;
	}

	/**
	 * Render the main templates page or editor based on URL parameters
	 */
	public function renderTemplatesPage() {
		$woo_templates_settings = new SettingsPage();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for page routing, no form processing
		if ( isset( $_GET['page'] ) && $this->menu_slug === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'woo_template' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) && isset( $_GET['template_id'] ) ) {
			// Render template editor
			$woo_templates_settings->templateEditorPage();
		} else {
			// Render templates list
			//$this->templatesListPage();
		}
	}

	/**
	 * Display the page header
	 */
	public function pageHeader( $active_menu ) {
		$logo_url = SHOPGLUT_URL . 'global-assets/images/header-logo.svg';
		?>
		<div class="shopglut-page-header">
			<div class="shopglut-page-header-wrap">
				<div class="shopglut-page-header-banner shopglut-pro shopglut-no-submenu">
					<div class="shopglut-page-header-banner__logo">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
					</div>
					<div class="shopglut-page-header-banner__helplinks">
						<span><a rel="noopener"
								href="https://shopglut.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=adminmenu"
								target="_blank">
								<span class="dashicons dashicons-admin-page"></span>
								<?php echo esc_html__( 'Documentation', 'shopglut' ); ?>
							</a></span>
						<span><a class="shopglut-active" rel="noopener"
								href="https://www.appglut.com/plugin/shopglut/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=upgrade"
								target="_blank">
								<span class="dashicons dashicons-unlock"></span>
								<?php echo esc_html__( 'Unlock Pro Edition', 'shopglut' ); ?>
							</a></span>
						<span><a rel="noopener"
								href="https://www.appglut.com/support/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=support"
								target="_blank">
								<span class="dashicons dashicons-share-alt"></span>
								<?php echo esc_html__( 'Support', 'shopglut' ); ?>
							</a></span>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<?php
	}
}