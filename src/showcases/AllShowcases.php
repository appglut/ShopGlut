<?php
namespace Shopglut\showcases;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use Shopglut\showcases\Tabs\TabListTable;
use Shopglut\showcases\ShopBanner\ShopBanner;
use Shopglut\showcases\ShopBanner\ShopBannerListTable;
use Shopglut\showcases\ShopBanner\ShopBannerEntity;
use Shopglut\showcases\ShopBanner\ShopBannerChooseTemplates;
use Shopglut\showcases\ShopBanner\ShopBannerFrontend;

use Shopglut\showcases\Tabs\TabSettingsPage;
use Shopglut\showcases\Accordions\AccordionSettingsPage;
use Shopglut\showcases\Gallery\GallerySettingsPage;
use Shopglut\showcases\Gallery\GalleryListTable;
use Shopglut\showcases\Gallery\GallerysChooseTemplates;

use Shopglut\showcases\Sliders\SliderListTable;
use Shopglut\showcases\Sliders\SliderSettingsPage;

use Shopglut\showcases\Accordions\AccordionListTable;
use Shopglut\showcases\Accordions\AccordionsChooseTemplates;

use Shopglut\showcases\Sliders\SlidersChooseTemplates;


use Shopglut\showcases\ShowcaseEntity;


use Shopglut\showcases\Sliders\SliderEntity;
use Shopglut\showcases\Tabs\TabEntity;
use Shopglut\showcases\Accordions\AccordionEntity;



class AllShowcases {

	public $not_implemented;

	public function __construct() {
		add_filter( 'admin_body_class', array( $this, 'shopglutBodyClass' ) );

        $this->not_implemented = true;

	}

	public function shopglutBodyClass( $classes ) {

		$current_screen = get_current_screen();

		if ( empty( $current_screen ) ) {
			return $classes;
		}

		if ( false !== strpos( $current_screen->id, 'shopglut_' ) ) {

			$classes .= ' shopglut-admin';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) ) {
			$classes .= '-shopglut-editor-collapse ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'slider' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-slider-editor ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'banner' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-banner-editor ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'shopbanner' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-shopbanner-editor ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'comparison' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-comparison-editor ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'accordion' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-accordion-editor ';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
         if ( isset( $_GET['page'] ) && 'shopglut_showcases' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'quickview' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			$classes .= '-shopglut-showcase-quickview-editor ';
		}


		return $classes;
	}

	public function rendershowcasesPages() {

		$slider_editor = new SliderSettingsPage();

		// Check user permissions first
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}


		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		if ( ! isset( $_GET['page'] ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'shopglut' ) );
		}
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$editor = isset( $_GET['editor'] ) ? sanitize_text_field( wp_unslash( $_GET['editor'] ) ) : '';
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : '';
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$layout_id = isset( $_GET['layout_id'] ) ? sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) : '';

		// Handle shopglut_showcases page
		if ( 'shopglut_showcases' === $page ) {
			
			// Editor routes with layout_id
			if ( ! empty( $editor ) ) {
				switch ( $editor ) {
					case 'sliders':
					case 'slider':
						$slider_editor->loadSliderEditor();
						break;
					case 'tabs':
					case 'tab':
						$this->loadTabsEditor();
						break;
					case 'accordion':
					case 'product_accordion':
						$this->loadAccordionEditor();
						break;
					case 'gallerys':
					case 'gallery':
						$this->loadGalleryEditor();
						break;
					case 'mega_menu':
						$this->loadMegaMenuEditor();
						break;
					case 'misc':
						$this->loadMiscEditor();
						break;
					case 'shopbanner':
					case 'product_shopbanner':
						$this->loadShopBannerEditor();
						break;
					default:
						wp_die( esc_html__( 'Invalid editor type.', 'shopglut' ) );
				}
			}
			// Template view routes
			elseif ( ! empty( $view ) ) {
				switch ( $view ) {
					case 'sliders':
						$this->renderSlidersTable();
						break;
					case 'woo_slider_templates':
						$this->renderSliderTemplates();
						break;
					case 'tabs':
						$this->renderTabsTable();
						break;
					case 'tabs_templates':
						$this->renderTabsTemplates();
						break;
					case 'accordions':
						$this->renderAccordionsTable();
						break;
					case 'accordion_templates':
						$this->renderAccordionTemplates();
						break;
					case 'gallerys':
						$this->renderGallerysTable();
						break;
					case 'gallery_templates':
						$this->renderGalleryTemplates();
						break;
					case 'shop_banner':
						$this->renderShopBannersTable();
						break;
					case 'shop_banner_templates':
						$this->renderShopBannerTemplates();
						break;
					case 'mega_menu':
						$this->renderMegaMenu();
						break;
					case 'misc':
						$this->renderMisc();
						break;
					default:
						// Default case - you can uncomment if needed
						// $this->rendershowcasesTable();
						break;
				}
			}
			// Default shopglut_showcases page
			else {
				$this->renderWooCommerceShowcases();
			}
		}
		// Handle other pages
		elseif ( 'shopg_woocommerce_builder' === $page ) {
			$this->renderWooBuilderPage();
		}
		elseif ( 'shopg_business_solution' === $page ) {
			$this->renderWooBusiness();
		}
		else {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'shopglut' ) );
		}
	}

	public function settingsPageHeader( $active_menu ) {
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
					<?php $this->settingsPageHeaderMenus( $active_menu ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function settingsPageHeaderMenus( $active_menu ) {
		$menus = $this->headerMenuTabs();

		if ( count( $menus ) < 2 ) {
			return;
		}

		?>
		<div class="shopglut-header-menus">
			<nav class="shopglut-nav-tab-wrapper nav-tab-wrapper">
				<?php foreach ( $menus as $menu ) : ?>
					<?php
					$id = $menu['id'];
					$url = esc_url_raw( ! empty( $menu['url'] ) ? $menu['url'] : '' );
					?>
					<a href="<?php echo esc_url( remove_query_arg( wp_removable_query_args(), $url ) ); ?>"
						class="shopglut-nav-tab nav-tab<?php echo esc_attr( $id ) == esc_attr( $active_menu ) ? ' shopglut-nav-active' : ''; ?>">
						<?php echo esc_html( $menu['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
		<?php
	}

	public function defaultHeaderMenu() {
		return 'all_showcases';
	}

	public function headerMenuTabs() {
		$tabs = [
			1 => [ 'id' => 'all_showcases', 'url' => admin_url( 'admin.php?page=shopglut_showcases' ), 'label' => '🎨 ' . esc_html__( 'All Showcases', 'shopglut' ) ],
			5 => [ 'id' => 'shop_banner', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=shop_banner' ), 'label' => '🏪 ' . esc_html__( 'Shop Banner', 'shopglut' ) ],
			10 => [ 'id' => 'sliders', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=sliders' ), 'label' => '🎢 ' . esc_html__( 'Sliders', 'shopglut' ) ],
			15 => [ 'id' => 'tabs', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=tabs' ), 'label' => '📋 ' . esc_html__( 'Tabs', 'shopglut' ) ],
			20 => [ 'id' => 'accordions', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=accordions' ), 'label' => '📝 ' . esc_html__( 'Accordion', 'shopglut' ) ],
		    25 => [ 'id' => 'gallerys', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=gallerys' ), 'label' => '🖼️ ' . esc_html__( 'Gallery', 'shopglut' ) ],
		    30 => [ 'id' => 'mega_menu', 'url' => admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ), 'label' => '🗂️ ' . esc_html__( 'Mega Menu', 'shopglut' ) ],
		];

		ksort( $tabs );

		return $tabs;
	}

	public function activeMenuTab() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for navigation state only
		if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'shopglut' ) !== false ) {
			// If no view parameter, we're on the main landing page (all_showcases)
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for navigation state only
			if ( !isset( $_GET['view'] ) && isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'shopglut_showcases' ) {
				return 'all_showcases';
			}
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for navigation state only
			return isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : $this->defaultHeaderMenu();
		}

		return false;
	}

	public function renderNotImplementedMessage() {
		?>
		<div style="padding: 50px 30px; background: #fff; margin: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); border-radius: 6px;">
			<div style="text-align: center;">
				<div style="font-size: 48px; margin-bottom: 20px; opacity: 0.6;">
					🚧
				</div>
				<h1 style="color: #dc3232; font-size: 24px; font-weight: 500; margin: 0 0 20px 0; line-height: 1.4;">
					<?php echo esc_html__( 'Feature Not Available', 'shopglut' ); ?>
				</h1>
				<p style="color: #666; font-size: 16px; margin: 0 0 25px 0; line-height: 1.6; max-width: 500px; margin-left: auto; margin-right: auto;">
					<?php echo esc_html__( 'This feature is currently under development. We are working to bring you enhanced functionality and customization options.', 'shopglut' ); ?>
				</p>
				<div style="padding: 15px; background: #f0f6fc; border: 1px solid #c3d9ed; border-radius: 4px; margin: 20px auto; max-width: 400px;">
					<p style="color: #0073aa; font-size: 14px; margin: 0; font-weight: 500;">
						<?php echo esc_html__( 'Please check back for updates in future releases.', 'shopglut' ); ?>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function renderSlidersTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_slider_' . $layout_id ) ) {
				// Delete the slider
				SliderEntity::delete_slider( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_showcases&view=sliders&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Slider deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		// Display slider templates if we're creating a new slider
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'new' ) {
			$slider_templates = new \Shopglut\showcases\Sliders\SliderchooseTemplates();
			$slider_templates->loadSliderTemplates();
			return;
		}

		// Display the sliders list table
		$sliders_table = new SliderListTable();
		$sliders_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<div class="wrap">
				<h2><?php echo esc_html__( 'Product Sliders', 'shopglut' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=woo_slider_templates' ) ); ?>"
						class="add-new-h2">
						<?php echo esc_html__( 'Add New Slider', 'shopglut' ); ?>
					</a>
				</h2>
			</div>

			<form method="post">
				<?php $sliders_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderTabsTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if tabs module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'tabs' ) ) {
			$module_manager->render_disabled_module_message( 'tabs' );
			return;
		}

		
		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['tab_id'] ) ) {
			$tab_id = absint( $_GET['tab_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_tab_' . $tab_id ) ) {
				// Delete the tab
				TabEntity::delete_tab( $tab_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_showcases&view=tabs&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Tab deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		
		// Display tab templates if we're creating a new tab
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'new' ) {
			$tab_templates = new ChooseTabTemplates();
			$tab_templates->loadTabTemplates();
			return;
		}

		// Display the tabs list table
		$tabs_table = new TabListTable();
		$tabs_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<div class="wrap">
				<h2><?php echo esc_html__( 'Product Tabs', 'shopglut' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=tabs_templates' ) ); ?>"
						class="add-new-h2">
						<?php echo esc_html__( 'Add New Tab', 'shopglut' ); ?>
					</a>
				</h2>
			</div>

			<form method="post">
				<?php $tabs_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderAccordionsTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if accordions module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'accordions' ) ) {
			$module_manager->render_disabled_module_message( 'accordions' );
			return;
		}

		
		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['accordion_id'] ) ) {
			$accordion_id = absint( $_GET['accordion_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_accordion_' . $accordion_id ) ) {
				// Delete the accordion
				AccordionEntity::delete_accordion( $accordion_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_showcases&view=accordions&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// Handle bulk actions
		if ( isset( $_POST['action'] ) && isset( $_POST['accordion_ids'] ) && is_array( $_POST['accordion_ids'] ) ) {
			$bulk_action = sanitize_text_field( wp_unslash( $_POST['action'] ) );
			$accordion_ids = array_map( 'absint', $_POST['accordion_ids'] );

			if ( $bulk_action === 'delete' && ! empty( $accordion_ids ) ) {
				// Verify nonce
				if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'bulk-accordions' ) ) {
					// Delete selected accordions
					foreach ( $accordion_ids as $accordion_id ) {
						AccordionEntity::delete_accordion( $accordion_id );
					}

					// Redirect to avoid resubmission
					wp_safe_redirect( admin_url( 'admin.php?page=shopglut_showcases&view=accordions&bulk_deleted=true' ) );
					exit;
				} else {
					wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
				}
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Accordion deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		if ( isset( $_GET['bulk_deleted'] ) && $_GET['bulk_deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Selected accordions deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		// Check if we're in the accordion editor
		if ( isset( $_GET['editor'] ) && $_GET['editor'] === 'accordions' && isset( $_GET['accordion_id'] ) ) {
			$accordion_editor = new AccordionSettingsPage();
			$accordion_editor->loadAccordionEditor();
			return;
		}

		
		// Display the accordions list table
		$accordions_table = new AccordionListTable();
		$accordions_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<div class="wrap">
				<h2><?php echo esc_html__( 'Product Accordions', 'shopglut' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=accordion_templates' ) ); ?>"
						class="add-new-h2">
						<?php echo esc_html__( 'Add New Accordion', 'shopglut' ); ?>
					</a>
				</h2>
			</div>

			<form method="post">
				<?php wp_nonce_field('bulk-accordions'); ?>
				<?php $accordions_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderShopBannersTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if shop_banner module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'shop_banner' ) ) {
			$module_manager->render_disabled_module_message( 'shop_banner' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_shopbanner_' . $layout_id ) ) {
				// Delete the banner
				ShopBannerEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_showcases&view=shop_banner&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// Display the banners list table
		$shop_banner_table = new ShopBannerListTable();
		$shop_banner_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<div class="wrap">
				<h2><?php echo esc_html__( 'Shop Banners', 'shopglut' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=shop_banner_templates' ) ); ?>"
						class="add-new-h2">
						<?php echo esc_html__( 'Add New Banner', 'shopglut' ); ?>
					</a>
				</h2>
			</div>

			<form method="post">
				<?php $shop_banner_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function loadTabsEditor() {
		$tab_editor = \Shopglut\showcases\Tabs\TabSettingsPage::get_instance();
		$tab_editor->loadTabEditor();
	}

	public function loadAccordionEditor() {
		$accordion_editor = new AccordionSettingsPage();
		$accordion_editor->loadAccordionEditor();
	}

	
	public function loadShopBannerEditor() {
		$shop_banner_editor = new \Shopglut\showcases\ShopBanner\ShopBannerSettingsPage();
		$shop_banner_editor->loadShopBannerEditor();
	}

	public function loadGalleryEditor() {
		$gallery_editor = new \Shopglut\showcases\Gallery\GallerySettingsPage();
		$gallery_editor->loadGalleryEditor();
	}

	public function loadMegaMenuEditor() {
		require_once SHOPGLUT_PATH . 'src/showcases/MegaMenu/MegaMenuSettingsPage.php';
		$megaMenuEditor = new \Shopglut\showcases\MegaMenu\MegaMenuSettingsPage();
		$megaMenuEditor->loadMegaMenuEditor();
	}

	public function loadMiscEditor() {
		echo '<div class="wrap"><h1>' . esc_html__( 'Misc Editor', 'shopglut' ) . '</h1><p>' . esc_html__( 'Misc editor functionality will be implemented here.', 'shopglut' ) . '</p></div>';
	}


	public function renderGallerysTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
			<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Display the gallery list table
		$gallery_table = new GalleryListTable();
		$gallery_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<div class="wrap">
				<h2><?php echo esc_html__( 'Product Gallery', 'shopglut' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=gallery_templates' ) ); ?>"
						class="add-new-h2">
						<?php echo esc_html__( 'Add New Gallery', 'shopglut' ); ?>
					</a>
				</h2>
			</div>

			<form method="post">
				<?php $gallery_table->display(); ?>
			</form>
		</div>
		<?php endif;  ?>
		<?php
	}


	public function renderGalleryTemplates() {
		$active_menu = 'gallerys';
		$this->settingsPageHeader( $active_menu );
		$gallery_templates = new \Shopglut\showcases\Gallery\GallerychooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Gallery Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $gallery_templates->loadGalleryTemplates();
	}

	public function renderMegaMenu() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		require_once SHOPGLUT_PATH . 'src/showcases/MegaMenu/MegaMenu.php';
		$megaMenu = new \ShopGlut\Showcases\MegaMenu\MegaMenu();
		?>
		<div class="wrap shopglut-admin-contents">
			<?php $megaMenu->render(); ?>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderMisc() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Misc', 'shopglut' ); ?></h2>
			<p class="subheading"><?php echo esc_html__( 'Manage your miscellaneous showcase elements', 'shopglut' ); ?></p>
			<p><?php echo esc_html__( 'Misc functionality will be implemented here.', 'shopglut' ); ?></p>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderWooCommerceShowcases() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2 style="text-align: center; font-weight: bold;"><?php echo esc_html__( 'Woo Showcases', 'shopglut' ); ?></h2>
			<p class="subheading" style="text-align: center;">
				<?php echo esc_html__( 'Configure and manage your WooCommerce showcase elements', 'shopglut' ); ?>
			</p>

			<div class="shopglut-showcases-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
				
				<!-- Sliders -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-sliders-h" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Sliders', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create and manage dynamic product sliders for your store.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=sliders' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Sliders', 'shopglut' ); ?></a>
				</div>

				<!-- Tabs -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-folder-open" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Tabs', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Configure tabbed product displays and content sections.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=tabs' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Setup Tabs', 'shopglut' ); ?></a>
				</div>

				<!-- Accordion -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-list-ul" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Accordion', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create collapsible accordion sections for product information.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=accordions' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Configure Accordion', 'shopglut' ); ?></a>
				</div>

				<!-- Gallery -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-images" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Gallery', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Manage product image galleries and visual showcases.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=gallerys' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Gallery', 'shopglut' ); ?></a>
				</div>

				<!-- Shop Banner -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-bullhorn" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Shop Banner', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create and manage promotional banners for your shop.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=shop_banner' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Configure Banner', 'shopglut' ); ?></a>
				</div>

				<!-- Mega Menu -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-bars" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Mega Menu', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create advanced navigation mega menus for your site.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Build Menu', 'shopglut' ); ?></a>
				</div>

				<!-- Misc -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-wrench" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Misc', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Manage miscellaneous showcase elements and utilities.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=misc' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Misc', 'shopglut' ); ?></a>
				</div>

			</div>
		</div>
		<?php
	}

	public function renderSliderTemplates() {
		$active_menu = 'sliders';
		$this->settingsPageHeader( $active_menu );
		$slider_templates = new \Shopglut\showcases\Sliders\SliderchooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Slider Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $slider_templates->loadSliderTemplates();
	}

	public function renderShopBannerTemplates() {
		$active_menu = 'shop_banner';
		$this->settingsPageHeader( $active_menu );
		$shop_banner_templates = new \Shopglut\showcases\ShopBanner\ShopBannerchooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Shop Banner Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $shop_banner_templates->loadShopBannerTemplates();
	}

	public function renderAccordionTemplates() {
		$active_menu = 'accordions';
		$this->settingsPageHeader( $active_menu );
		$accordion_templates = new \Shopglut\showcases\Accordions\AccordionchooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Accordion Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $accordion_templates->loadAccordionTemplates();
	}

	public function renderTabsTemplates() {
		$active_menu = 'tabs';
		$this->settingsPageHeader( $active_menu );
		$tab_templates = \Shopglut\showcases\Tabs\TabchooseTemplates::get_instance();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Tab Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $tab_templates->loadTabTemplates();
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}