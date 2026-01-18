<?php
namespace Shopglut\BusinessSolutions;

class AllBusinessSolutions {

	public function __construct() {
		// Initialize any business solution components if needed
	}

	public function renderBusinessSolutionsPage() {
		$this->settingsPageHeader();
		$this->renderBusinessModules();
	}

	private function settingsPageHeader() {
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

	private function renderBusinessModules() {
		$module_manager = \Shopglut\ModuleManager::get_instance();
		?>
		<div class="wrap shopglut-admin-contents shopg-woo-builder">
			<h1><?php echo esc_html__( 'WooCommerce Business Solution', 'shopglut' ); ?></h1>
			<p class="subheading">
				<?php echo esc_html__( 'Improve your Business with Glut service. Toggle switches to enable/disable modules.', 'shopglut' ); ?>
			</p>

			<div class="shopg-woo-builder grid-container">
				
				<?php 
				// Business Solution Modules
				$module_manager->render_module_card('pdf_invoices', admin_url( 'admin.php?page=shopglut_pdf_invoices_slips' ));
				$module_manager->render_module_card('email_customizer', admin_url( 'admin.php?page=shopglut_email_customizer' ));
				?>

			</div>
		</div>
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