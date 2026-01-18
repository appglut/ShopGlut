<?php
namespace Shopglut\layouts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\layouts\shopLayout\chooseTemplates as ShopLayoutTemplates;
use Shopglut\layouts\shopLayout\SettingsPage as ShopLayoutEditor;
use Shopglut\layouts\shopLayout\ShopListTable;
use Shopglut\layouts\singleProduct\chooseTemplates as SingleProductTemplates;
use Shopglut\layouts\cartPage\chooseTemplates as CartPageTemplates;

use Shopglut\layouts\orderCompletePage\chooseTemplates as OrdercompleteTemplates;
use Shopglut\layouts\orderCompletePage\SettingsPage as orderCompletePagePreBuilderEditor;
use Shopglut\layouts\orderCompletePage\OrdercompleteListTable;


use Shopglut\layouts\accountPage\AccountPageChooseTemplates;
use Shopglut\layouts\singleProduct\SettingsPage as SingleProductEditor;
use Shopglut\layouts\cartPage\SettingsPage as CartPageEditor;
use Shopglut\layouts\cartPage\SettingsPage as CartPageBuilderEditor;


use Shopglut\layouts\accountPage\AccountPageSettingsPage as accountpageEditor;
use Shopglut\layouts\singleProduct\SingleProductListTable;
use Shopglut\layouts\cartPage\CartPageListTable;
use Shopglut\layouts\accountPage\AccountPageListTable;
use Shopglut\BusinessSolutions\AllBusinessSolutions;
use Shopglut\layouts\cartPage\CartPageEntity;
use Shopglut\layouts\orderCompletePage\orderCompletePageEntity as ordercompleteEntity;
use Shopglut\layouts\accountPage\AccountPageEntity;
use Shopglut\layouts\singleProduct\SingleLayoutEntity;
use Shopglut\layouts\LayoutEntity;


class AllLayouts {

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

		// Only apply editor-specific classes in admin context with proper permissions
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return $classes;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for body class only
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) ) {
			$classes .= '-shopglut-editor-collapse ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'shop' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-shopglut-shop-editor ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'single_product' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-sg-single-product ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'cartpage' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-shopglut-cartpage-editor ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'cartpage' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-shopglut-cartpage-editor ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'ordercomplete' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-shopglut-ordercomplete-editor ';
		}

		// PHPCS: Input var ok. Body class filter context doesn't require nonce verification.
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) && 'accountpage_prebuilt' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= '-shopglut-accountpage_prebuilt-editor ';
		}

		return $classes;
	}

	public function renderLayoutsPages() {

		$singleProduct_editor = new SingleProductEditor();
		$shopLayout_editor = new ShopLayoutEditor();
		$cartpage_editor = new CartPageEditor();
		$cartpage_editor = new CartPageBuilderEditor();
		$ordercomplete_editor = new orderCompletePagePreBuilderEditor();
		$accountpage_editor = new accountpageEditor();

		// Sanitize and validate input - only in admin context
		$page = '';
		$editor = '';
		$layout_id = 0;
		$view = '';

		if ( is_admin() && current_user_can( 'manage_options' ) ) {
			// Verify nonce if present (for editor actions), skip for basic navigation
			$nonce_verified = true;
			if ( isset( $_GET['_wpnonce'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
				// For delete actions, verify against the specific delete nonce
				$delete_layout_id = absint( $_GET['layout_id'] );
				$nonce_verified = wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $delete_layout_id );
			} elseif ( isset( $_GET['_wpnonce'] ) ) {
				// For other actions, verify against the general admin action nonce
				$nonce_verified = wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_admin_action' );
			}

			if ( $nonce_verified ) {
				$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
				$editor = isset( $_GET['editor'] ) ? sanitize_text_field( wp_unslash( $_GET['editor'] ) ) : '';
				$layout_id = isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 0;
				$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : '';
			}
		}


		if ( 'shopglut_layouts' === $page && 'single_product' === $editor && $layout_id > 0 ) {
			$singleProduct_editor->loadSingleProductEditor();
		} elseif ( 'shopglut_layouts' === $page && 'shop' === $editor && $layout_id > 0 ) {
			$shopLayout_editor->loadShopLayoutEditor();
		} elseif ( 'shopglut_layouts' === $page && 'cartpage' === $editor && $layout_id > 0 ) {
			$cartpage_editor->loadCartPageEditor();
		}  elseif ( 'shopglut_layouts' === $page && 'ordercomplete' === $editor && $layout_id > 0 ) {
			$ordercomplete_editor->loadOrdercompleteEditor();
		} elseif ( 'shopglut_layouts' === $page && 'accountpage' === $editor && $layout_id > 0 ) {
			$accountpage_editor->loadAccountPageEditor();
		} elseif ( 'shopglut_layouts' === $page && 'shop_templates' === $view ) {
			$this->ShoplayoutTemplatesPage();
		} elseif ( 'shopglut_layouts' === $page && 'single_product_templates' === $view ) {
			$this->SingleProductlayoutTemplatesPage();
		} elseif ( 'shopglut_layouts' === $page && 'cartpage_templates' === $view ) {
			$this->CartlayoutTemplatesPage();
		} elseif ( 'shopglut_layouts' === $page && 'accountpage_templates' === $view ) {
			$this->AccountTemplatesPage();
		} elseif ( 'shopglut_layouts' === $page && 'ordercomplete_templates' === $view ) {
			$this->ThanklayoutTemplatesPage();
		} elseif ( 'shopglut_layouts' === $page && ! empty( $view ) ) {
			switch ( $view ) {
				case 'shop':
					$this->renderLayoutsTable();
					break;
				case 'single_product':
					$this->renderSingleProduct();
					break;
				case 'cartpage':
					$this->renderCart();
					break;
				case 'checkout':
					$this->renderCheckout();
					break;
				case 'ordercomplete':
					$this->renderOrderThankyou();
					break;
				case 'accountpage':
					$this->renderMyAccount();
					break;
				// case 'quick_views':
				//     $this->renderQuickView();
				//     break;
				default:
					//$this->renderLayoutsTable();
					break;
			}
		} elseif ( 'shopglut_layouts' === $page ) {
			$this->renderWooCommerceLayouts();
		} elseif ( 'shopg_woocommerce_builder' === $page ) {
			$this->renderWooBuilderPage();
		} else {
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
					<?php $id = $menu['id'];
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
		return 'all_layouts';
	}

	public function headerMenuTabs() {
		$tabs = [
			1 => [ 'id' => 'all_layouts', 'url' => admin_url( 'admin.php?page=shopglut_layouts' ), 'label' => '📋 ' . esc_html__( 'All Layouts', 'shopglut' ) ],
			30 => [ 'id' => 'shop', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=shop' ), 'label' => '🏪 ' . esc_html__( 'Shop & Archive Layouts', 'shopglut' ) ],
			5 => [ 'id' => 'single_product', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=single_product' ), 'label' => '📦 ' . esc_html__( 'Single Product', 'shopglut' ) ],
			10 => ['id' => 'cartpage', 'url' => admin_url('admin.php?page=shopglut_layouts&view=cartpage'), 'label' => '🛒 ' . esc_html__('Cart Page', 'shopglut')],
			20 => ['id' => 'ordercomplete', 'url' => admin_url('admin.php?page=shopglut_layouts&view=ordercomplete'), 'label' => '✅ ' . esc_html__('Order Complete', 'shopglut')],
			15 => ['id' => 'checkout', 'url' => admin_url('admin.php?page=shopglut_layouts&view=checkout'), 'label' => '💳 ' . esc_html__('Shop Checkout', 'shopglut')],
            25 => ['id' => 'accountpage', 'url' => admin_url('admin.php?page=shopglut_layouts&view=accountpage'), 'label' => '⭕ ' . esc_html__('My Account', 'shopglut')],
		];

		ksort( $tabs );

		return $tabs;
}

	public function activeMenuTab() {
		// PHPCS: Input var ok. Navigation context doesn't require nonce verification.
		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// PHPCS: Input var ok. Navigation context doesn't require nonce verification.
		$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		
		// Check if we're on a shopglut page
		if ( false !== strpos( $page, 'shopglut' ) ) {
			// If no view parameter, we're on the main landing page (all_layouts)
			if ( empty( $view ) && 'shopglut_layouts' === $page ) {
				return 'all_layouts';
			}
			return ! empty( $view ) ? $view : $this->defaultHeaderMenu();
		}

		return false;
	}

	public function renderLayoutsTable() {
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

		// Check if shop_layouts module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'shop_layouts' ) ) {
			$module_manager->render_disabled_module_message( 'shop_layouts' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout using ShopLayoutEntity
				\Shopglut\layouts\shopLayout\ShopLayoutEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission - maintain the view parameter
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_layouts&view=shop&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new ShopListTable();
		$layouts_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=shop_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function ShoplayoutTemplatesPage() {

		$active_menu = 'layouts';
		$this->settingsPageHeader( $active_menu );
		$shopLayout_templates = new ShopLayoutTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt ShopPage Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $shopLayout_templates->loadShoplayoutTemplates();

	}

	public function SingleProductlayoutTemplatesPage() {
		$active_menu = 'single_product';
		$this->settingsPageHeader( $active_menu );
		$shopLayout_templates = new SingleProductTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'Prebuilt SingleProduct Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
			<?php $shopLayout_templates->loadSingleProductTemplates(); ?>
		</div>
		<?php
	}

	public function CartlayoutTemplatesPage() {
		$active_menu = 'cartpage';
		$this->settingsPageHeader( $active_menu );
		$cartLayout_templates = new CartPageTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Cart Layout Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $cartLayout_templates->loadCartPageTemplates();
	}
	
	public function ThanklayoutTemplatesPage() {
		$active_menu = 'ordercomplete';
		$this->settingsPageHeader( $active_menu );
		$thankLayout_templates = new OrdercompleteTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Ordercomplete Layout Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $thankLayout_templates->loadOrdercompleteTemplates();
	}
	
	public function AccountTemplatesPage() {
		$active_menu = 'accountpage';
		$this->settingsPageHeader( $active_menu );
		$thankLayout_templates = new AccountPageChooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt AccountPage Layout Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $thankLayout_templates->loadProductAccountPageTemplates();
	}

	public function renderCart() {
			$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		 if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if cart_page module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'cart_page' ) ) {
			$active_menu = $this->activeMenuTab();
			$this->settingsPageHeader( $active_menu );
			$module_manager->render_disabled_module_message( 'cart_page' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				CartPageEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new CartPageListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Cart Page Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
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

	public function renderCheckout() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );

		 if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>

		// Check if cart_page module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'checkout_page' ) ) {
			$module_manager->render_disabled_module_message( 'checkout_page' );
			return;
		}
		?>
		<div class="wrap shopglut-admin-contents">
			<div style="max-width: 800px; margin: 12px auto; text-align: center;">
				<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
					<div style="margin-bottom: 30px;">
						<div style="font-size: 64px; margin-bottom: 20px; opacity: 0.7;">
							📝
						</div>
						<h1 style="color: #2c3e50; font-size: 32px; font-weight: 600; margin: 0 0 15px 0; line-height: 1.3;">
							<?php echo esc_html__( 'Checkout Field Management', 'shopglut' ); ?>
						</h1>
						<p style="color: #7f8c8d; font-size: 18px; margin: 0 0 40px 0; line-height: 1.6;">
							<?php echo esc_html__( 'Configure and manage checkout fields for your WooCommerce store', 'shopglut' ); ?>
						</p>
					</div>

					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_checkout_fields' ) ); ?>" class="shopglut-wishlist-button">
						<span><i class="fa fa-cog"></i></span>
						<?php echo esc_html__( 'Go to Checkout Field Admin', 'shopglut' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php endif;
    }
	
	public function renderOrderThankyou() {
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

		// Check if orderComplete_page module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'orderComplete_page' ) ) {
			$module_manager->render_disabled_module_message( 'orderComplete_page' );
			return;
		}

		// Handle individual delete action
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification happens below
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Sanitized and used for nonce verification
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This IS the nonce verification
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				$result = ordercompleteEntity::delete_layout( $layout_id );

				// Cache clearing removed

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_layouts&view=ordercomplete&deleted=true' ) );
				exit;
			} else {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Debugging output
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) . '<br>Layout ID: ' . esc_html( $layout_id ) . '<br>Nonce: ' . esc_html( isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : 'not set' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new OrdercompleteListTable();
		$layouts_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'OrderComplete Page Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=ordercomplete_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderMyAccount() {
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

		// Check if account_page module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'account_page' ) ) {
			$module_manager->render_disabled_module_message( 'account_page' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				AccountPageEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_layouts&view=accountpage&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new AccountPageListTable();
		$layouts_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Account Page Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=accountpage_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php
	}

	public function renderSingleProduct() {
		 //if($this->not_implemented):
		 //$this->renderNotImplementedMessage();
		 //else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if single_product module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'single_product' ) ) {
			$active_menu = $this->activeMenuTab();
			$this->settingsPageHeader( $active_menu );
			$module_manager->render_disabled_module_message( 'single_product' );
			return;
		}

		// Handle individual delete action FIRST - before any other actions
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				\Shopglut\layouts\singleProduct\SingleLayoutEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_layouts&view=single_product&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// Handle direct creation from "Create From New" button
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'create_new' && isset( $_GET['_wpnonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'create_new_layout_nonce' ) ) {
				try {
					// Get next layout ID
					global $wpdb;
					$table_name = $wpdb->prefix . 'shopglut_single_product_layout';
					$layout_id = intval($wpdb->get_var("SELECT MAX(id) FROM {$wpdb->prefix}shopglut_single_product_layout")) + 1 ?: 1;			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function
				
					// Create layout directly with default settings
					$current_time = current_time('mysql');
					$data = array(
						'id' => $layout_id,
						'layout_name' => sanitize_text_field('Layout(#' . $layout_id . ')'),
						'layout_template' => 'template_default', // Default template
						'layout_settings' => '{}', // Default empty JSON object
						'created_at' => $current_time,
						'updated_at' => $current_time
					);
					
					$format = array('%d', '%s', '%s', '%s', '%s', '%s');
					
					
					$inserted = $wpdb->insert($table_name, $data, $format);			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function

					
					if ($inserted !== false) {
						// Redirect to editor on success
						$redirect_url = add_query_arg(
							array(
								'page' => 'shopglut_layouts',
								'editor' => 'single_product',
								'layout_id' => $layout_id
							),
							admin_url('admin.php')
						);
						wp_safe_redirect($redirect_url);
						exit;
					} else {
						// Handle database insertion failure
						add_action('admin_notices', function() {
							echo '<div class="error notice"><p>' . esc_html__('Failed to create new layout. Please try again.', 'shopglut') . '</p></div>';
						});
					}
				} catch (Exception $e) {
					// Handle any exceptions
					add_action('admin_notices', function() use ($e) {
						echo '<div class="error notice"><p>' . esc_html__('An error occurred: ', 'shopglut') . esc_html($e->getMessage()) . '</p></div>';
					});
				}
			} else {
				// Handle nonce verification failure
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		
		// Show success/error messages
		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		// Show bulk delete success message
		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'bulk' && isset( $_GET['count'] ) ) {
			$count = absint( $_GET['count'] );
			if ( $count > 0 ) {
				printf(
					'<div class="updated notice"><p>%s</p></div>',
					esc_html( sprintf(
						_n(
							'%d layout deleted successfully.',
							'%d layouts deleted successfully.',
							$count,
							'shopglut'
						),
						$count
					) )
				);
			}
		}

		// Handle bulk delete action (check POST before any output)
		if ( isset( $_POST['action'] ) && $_POST['action'] === 'delete' && isset( $_POST['user'] ) ) {
			check_admin_referer( 'bulk-layouts' );

			$layout_ids = array_map( 'absint', wp_unslash( $_POST['user'] ) );

			if ( ! empty( $layout_ids ) ) {
				$deleted_count = 0;
				foreach ( $layout_ids as $layout_id ) {
					$result = \Shopglut\layouts\singleProduct\SingleLayoutEntity::delete_layout( $layout_id );
					if ( $result !== false ) {
						$deleted_count++;
					}
				}

				// Redirect to avoid resubmission and show success message
				$redirect_url = add_query_arg(
					array(
						'page' => 'shopglut_layouts',
						'view' => 'single_product',
						'deleted' => 'bulk',
						'count' => $deleted_count
					),
					admin_url( 'admin.php' )
				);
				wp_safe_redirect( $redirect_url );
				exit;
			}
		}

		// Handle bulk delete from bottom dropdown
		if ( isset( $_POST['action2'] ) && $_POST['action2'] === 'delete' && isset( $_POST['user'] ) ) {
			check_admin_referer( 'bulk-layouts' );

			$layout_ids = array_map( 'absint', wp_unslash( $_POST['user'] ) );

			if ( ! empty( $layout_ids ) ) {
				$deleted_count = 0;
				foreach ( $layout_ids as $layout_id ) {
					$result = \Shopglut\layouts\singleProduct\SingleLayoutEntity::delete_layout( $layout_id );
					if ( $result !== false ) {
						$deleted_count++;
					}
				}

				// Redirect to avoid resubmission and show success message
				$redirect_url = add_query_arg(
					array(
						'page' => 'shopglut_layouts',
						'view' => 'single_product',
						'deleted' => 'bulk',
						'count' => $deleted_count
					),
					admin_url( 'admin.php' )
				);
				wp_safe_redirect( $redirect_url );
				exit;
			}
		}

		// Prepare layouts table
		$layouts_table = new SingleProductListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		
		// Generate URLs for the action buttons
		$templates_url = admin_url( 'admin.php?page=shopglut_layouts&view=single_product_templates' );
		
		$create_new_url = add_query_arg(
			array(
				'page' => 'shopglut_layouts',
				'view' => 'single_product',
				'action' => 'create_new',
				'_wpnonce' => wp_create_nonce('create_new_layout_nonce')
			),
			admin_url('admin.php')
		);
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Single Product Layouts', 'shopglut' ); ?>
				<a href="<?php echo esc_url( $templates_url ); ?>">
					<span class="add-new-h2"><?php echo esc_html__( 'Create From Templates', 'shopglut' ); ?></span>
				</a>
			
				<!-- <a href="<?php echo esc_url( $create_new_url ); ?>">
					<span class="add-new-h2"><?php echo esc_html__( 'Create From New', 'shopglut' ); ?></span>
				</a> -->
			</h2>

			<form method="post" id="shopglut-layouts-form">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php //endif; ?>
		<?php
  }

  public function renderWooBuilderPage() {
		$module_manager = \Shopglut\ModuleManager::get_instance();
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
		<div class="wrap shopglut-admin-contents shopg-woo-builder">
			<h1><?php echo esc_html__( 'WooCommerce Builder Modules', 'shopglut' ); ?></h1>
			<p class="subheading">
				<?php echo esc_html__( 'Choose your module to build and customize. Toggle switches to enable/disable modules.', 'shopglut' ); ?>
			</p>

			<!-- Grid Block Container -->
			<div class="shopg-woo-builder grid-container">

				<?php
				// ===== WOOCOMMERCE LAYOUT & BUILDER MODULES =====

				// Core Page Builders
				$module_manager->render_module_card('single_product', admin_url( 'admin.php?page=shopglut_layouts&view=single_product' ));
				//$module_manager->render_module_card('shop_layouts', admin_url( 'admin.php?page=shopglut_layouts&view=shop' ));
				//$module_manager->render_module_card('archive_layouts', admin_url( 'admin.php?page=shopglut_layouts&view=archive' ));
				//$module_manager->render_module_card('cart_page', admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ));
				//$module_manager->render_module_card('checkout_page', admin_url( 'admin.php?page=shopglut_checkout_fields' ));
				//$module_manager->render_module_card('orderComplete_page', admin_url( 'admin.php?page=shopglut_layouts&view=ordercomplete' ));
				//$module_manager->render_module_card('account_page', admin_url( 'admin.php?page=shopglut_layouts&view=accountpage' ));

				// ===== WOOCOMMERCE ENHANCEMENT MODULES =====

				// Product Enhancement
				//$module_manager->render_module_card('quick_views', admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickview' ));
				$module_manager->render_module_card('product_comparison', admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparison' ));
				$module_manager->render_module_card('wishlist', admin_url( 'admin.php?page=shopglut_wishlist' ));
				$module_manager->render_module_card('product_swatches', admin_url( 'admin.php?page=shopglut_swatches_settings' ));
				$module_manager->render_module_card('product_badges', admin_url( 'admin.php?page=shopglut_badges' ));
				//$module_manager->render_module_card('shop_filters', admin_url( 'admin.php?page=shopglut_enhancements&editor=filters' ));

				// ===== WOOCOMMERCE TOOLS MODULES =====

				// Product Tools
				$module_manager->render_module_card('acf_fields', admin_url( 'admin.php?page=shopglut_tools&view=acf_fields' )); // Product Custom Fields
				//$module_manager->render_module_card('shortcode_showcase', admin_url( 'admin.php?page=shopglut_shortcode_showcase' ));
				//$module_manager->render_module_card('mini_cart', admin_url( 'admin.php?page=shopglut_tools&view=mini_cart' ));
				//$module_manager->render_module_card('shop_banner', admin_url( 'admin.php?page=shopglut_tools&view=shop_banner' ));
				//$module_manager->render_module_card('woo_templates', admin_url( 'admin.php?page=shopglut_tools&view=woo_templates' ));
				//$module_manager->render_module_card('login_register', admin_url( 'admin.php?page=shopglut_tools&view=login_register' ));
				//$module_manager->render_module_card('woo_themes', admin_url( 'admin.php?page=shopglut_tools&view=woo_themes' ));

				?>
			</div>
		</div>
		<?php
	}

	public function renderWooCommerceLayouts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2 style="text-align: center; font-weight: bold;"><?php echo esc_html__( 'WooCommerce Layouts', 'shopglut' ); ?></h2>
			<p class="subheading" style="text-align: center;">
				<?php echo esc_html__( 'Design and customize your WooCommerce store pages with our powerful layout builder', 'shopglut' ); ?>
			</p>
			<div class="shopglut-enhancements-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
				
				<!-- Single Product -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-cube" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Single Product', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create stunning product detail pages with custom layouts and elements.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=single_product' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>

				<!-- Cart Page -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-shopping-cart" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Cart Page', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Customize your shopping cart page layout and enhance user experience.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=cartpage' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>

				<!-- Checkout Page -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-credit-card" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Checkout Page', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Optimize your checkout process with custom fields and layouts.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=checkout' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>

				<!-- Order Complete Page -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-check-circle" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Order Complete Page', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create engaging order confirmation pages to delight your customers.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=ordercomplete' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>

				<!-- My Account Page -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-user-circle" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'My Account Page', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Design personalized customer account pages with custom dashboards.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=accountpage' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>
				<!-- Shop Page -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-store" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Shop Page', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Design beautiful shop pages with advanced product grids and filtering.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=shop' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Layouts', 'shopglut' ); ?></a>
				</div>

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