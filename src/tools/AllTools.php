<?php
namespace Shopglut\tools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\layouts\singleProduct\chooseTemplates as SingleProductTemplates;
use Shopglut\layouts\singleProduct\SingleProductListTable;
use Shopglut\BusinessSolutions\PdfInvoices\PdfInvoicesHandler;
use Shopglut\layouts\singleProduct\SingleLayoutEntity;
use Shopglut\tools\productCustomField\ProductCustomFieldListTable;
use Shopglut\tools\productCustomField\ProductCustomFieldSettingsPage;

use Shopglut\layouts\cartPage\chooseTemplates as CartPageTemplates;
use Shopglut\layouts\cartPage\dataManage as CartPageDataManage;
use Shopglut\layouts\cartPage\SettingsPage as CartPageEditor;
use Shopglut\layouts\cartPage\CartPageEntity;
use Shopglut\layouts\cartPage\CartPageListTable;
use Shopglut\tools\WooThemes\WooThemes;


class AllTools {

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
			if ( isset( $_GET['page'] ) && 'shopglut_tools' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) ) {
				$classes .= '-shopglut-editor-collapse ';
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
			if ( isset( $_GET['page'] ) && 'shopglut_tools' === sanitize_text_field( wp_unslash($_GET['page'] )) ) {
				
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
				if ( isset( $_GET['editor'] ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
					$editor = sanitize_text_field( wp_unslash($_GET['editor']) );
					
					switch ( $editor ) {
						case 'shop':
							$classes .= '-shopglut-shop-editor ';
							break;
						case 'slider':
							$classes .= '-sg-slider-editor shopglut-admin';
							break;
						case 'archive':
							$classes .= '-shopglut-archive-editor ';
							break;
						case 'cartpage':
							$classes .= '-shopglut-cartpage-editor ';
							break;
						case 'cartpage':
							$classes .= '-shopglut-cartpage-editor shopglut-admin ';
							break;
						case 'ordercomplete':
							$classes .= '-shopglut-ordercomplete-editor ';
							break;
						case 'accountpage_prebuilt':
							$classes .= '-shopglut-accountpage_prebuilt-editor ';
							break;
						case 'woo_template':
							$classes .= '-shopglut-woo-template-editor ';
							break;
					}
				}
			}

			return $classes;
	}

	public function rendertoolsPages() {

		//$singleProduct_editor = new SingleProductEditor();

		//$cartpage_editor = new CartPageBuilderEditor();

			$product_custom_field_settings_page = new ProductCustomFieldSettingsPage();

			
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
			
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
			$product_custom_field_action = isset( $_GET['product_custom_field_action'] ) ? sanitize_text_field( wp_unslash( $_GET['product_custom_field_action'] ) ) : '';

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
			$product_custom_field_id = isset( $_GET['product_custom_field_id'] ) ? absint( wp_unslash( $_GET['product_custom_field_id'] ) ) : 0;

			// Handle shopglut_tools page
			if ( 'shopglut_tools' === $page ) {
				
				// Editor routes with layout_id
				if ( ! empty( $editor )) {
					switch ( $editor ) {
						case 'product_custom_field':
							// Get field_id from URL parameter
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter for field display
					$field_id = isset( $_GET['field_id'] ) ? absint( $_GET['field_id'] ) : 0;

					// For new field creation
					if ( $field_id === 0 ) {
						global $wpdb;
						$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();

						// Create a new field entry in the database
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->insert(
							$table_name,
							array(
								'field_name' => 'Custom Field #',
								'field_settings' => '',
							),
							array( '%s', '%s' )
						);

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
						$field_id = $wpdb->insert_id;

						// Update the field name to include the auto-generated ID
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->update(
							$table_name,
							array(
								'field_name' => 'Custom Field #' . $field_id,
							),
							array( 'id' => $field_id ),
							array( '%s' ),
							array( '%d' )
						);

						// Redirect to the same page with the new field_id
						wp_safe_redirect( admin_url( 'admin.php?page=shopglut_tools&editor=product_custom_field&field_id=' . $field_id ) );
						exit;
					}

					$product_custom_field_settings_page->loadProductCustomFieldEditor();
							break;
						case 'woo_template':
							// Check if ShortcodeGlut is active and use its editor
							if ( $this->is_shortcodeglut_active() ) {
								$shortcodeglut_path = $this->get_shortcodeglut_path();
								if ( $shortcodeglut_path && file_exists( $shortcodeglut_path . '/src/wooTemplates/SettingsPage.php' ) ) {
									require_once $shortcodeglut_path . '/src/wooTemplates/SettingsPage.php';
									$woo_template_settings = \Shortcodeglut\wooTemplates\SettingsPage::get_instance();
									$woo_template_settings->templateEditorPage();
									break;
								}
							}
							// Fallback to ShopGlut's built-in editor
							require_once SHOPGLUT_PATH . 'src/tools/wooTemplates/SettingsPage.php';
							$woo_template_settings = new \Shopglut\tools\wooTemplates\SettingsPage();
							$woo_template_settings->templateEditorPage();
							break;
						case 'login_register':
							require_once SHOPGLUT_PATH . 'src/tools/loginRegister/LoginRegister.php';
							$login_register = new \Shopglut\tools\loginRegister\LoginRegister();
							$login_register->renderTemplateCustomizer();
							break;
						case 'shop':
							$shopLayout_editor->loadShopLayoutEditor();
							break;
						case 'ordercomplete':
							$ordercomplete_editor->loadOrdercompletePreBuiltEditor();
							break;
						case 'accountpage_prebuilt':
							$accountpage_Prebuilt_editor->loadAccountPagePreBuiltEditor();
							break;
						default:
							wp_die( esc_html__( 'Invalid editor type.', 'shopglut' ) );
					}
				}
				// Handle Product Custom Field actions
				elseif ( ! empty( $product_custom_field_action ) ) {
					switch ( $product_custom_field_action ) {
						case 'add':
						case 'edit':
							$product_custom_field_settings_page->loadProductCustomFieldEditor( $product_custom_field_id );
							break;
						case 'delete':
							if ( $product_custom_field_id > 0 && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_product_custom_field_' . $product_custom_field_id ) ) {
								global $wpdb;
								$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();
								// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Safe table name from internal function
								$wpdb->delete( $table_name, array( 'id' => $product_custom_field_id ), array( '%d' ) );
								wp_safe_redirect( admin_url( 'admin.php?page=shopglut_tools&product_custom_field_deleted=1' ) );
								exit;
							} else {
								wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
							}
							break;
						default:
							wp_die( esc_html__( 'Invalid Product Custom Field action.', 'shopglut' ) );
					}
				}
				// Template view routes
				elseif ( ! empty( $view ) ) {
					switch ( $view ) {
						case 'shortcode_showcase':
							$this->renderShortcodeShowcase();
							break;
						case 'woo_templates':
							$this->renderProductTemplates();
							break;
						case 'login_register':
							$this->renderLoginRegister();
							break;
						case 'mini_cart':
							$this->renderMiniCart();
							break;
						case 'product_custom_field':
							$this->renderAcfSettingsTable();
							break;
						case 'woo_themes':
							$this->renderWooThemes();
							break;
						case 'posglut':
							$this->renderPosglut();
							break;

						default:
							$this->renderWooCommerceTools();
							break;
					}
				}
				// Default shopglut_tools page - show main tools landing page
				else {
					$this->renderWooCommerceTools();
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
						<img src="<?php echo esc_url( $logo_url );// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="">
					</div>
					<div class="shopglut-page-header-banner__helplinks">
						<span><a rel="noopener"
								href="https://documentation.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=adminmenu"
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
		return 'all_tools';
	}

	public function headerMenuTabs() {
		$tabs = [
			1 => [ 'id' => 'all_tools', 'url' => admin_url( 'admin.php?page=shopglut_tools' ), 'label' => '🔧 ' . esc_html__( 'All Tools', 'shopglut' ) ],
			5 => [ 'id' => 'product_custom_field', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=product_custom_field' ), 'label' => '📦 ' . esc_html__( 'Product Custom Fields', 'shopglut' ) ],
			10 => [ 'id' => 'shortcode_showcase', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=shortcode_showcase' ), 'label' => '💻 ' . esc_html__( 'Shortcode Showcase', 'shopglut' ) ],
			15 => [ 'id' => 'woo_templates', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=woo_templates' ), 'label' => '📋 ' . esc_html__( 'Woo Templates', 'shopglut' ) ],
			17 => [ 'id' => 'woo_themes', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=woo_themes' ), 'label' => '🎨 ' . esc_html__( 'Woo Themes', 'shopglut' ) ],
			19 => [ 'id' => 'posglut', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=posglut' ), 'label' => '🛒 ' . esc_html__( 'POS (Point of Sale)', 'shopglut' ) ],
			20 => [ 'id' => 'login_register', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=login_register' ), 'label' => '👤 ' . esc_html__( 'Login/Register', 'shopglut' ) ],
			25 => [ 'id' => 'mini_cart', 'url' => admin_url( 'admin.php?page=shopglut_tools&view=mini_cart' ), 'label' => '🛒 ' . esc_html__( 'Mini Cart', 'shopglut' ) ],
		];

		ksort( $tabs );

		return $tabs;
 }

	public function activeMenuTab() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for menu display only
		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for menu display only
		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for menu display only
		$nonce_check = isset( $_GET['url_nonce_check'] ) ? sanitize_text_field( wp_unslash( $_GET['url_nonce_check'] ) ) : '';
		
		if ( ( ! wp_verify_nonce( $nonce_check, 'url_nonce_value' ) ) && ( strpos( $page, 'shopglut' ) !== false ) ) {
			// If no view parameter, we're on the main landing page (all_tools)
			if ( !isset( $_GET['view'] ) && isset( $_GET['page'] ) && $_GET['page'] === 'shopglut_tools' ) {
				return 'all_tools';
			}
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for menu display only
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

	public function renderAcfSettingsTable() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php //if($this->not_implemented): ?>
			<?php //$this->renderNotImplementedMessage(); ?>
		<?php //else: ?>
		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if acf_fields module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'acf_fields' ) ) {
			$module_manager->render_disabled_module_message( 'acf_fields' );
			return;
		}

		// Handle delete action
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification performed below
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['field_id'] ) ) {
			$field_id = absint( $_GET['field_id'] );

			// Verify nonce
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is performed here
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_product_custom_field_' . $field_id ) ) {
				global $wpdb;
				$table_name = \Shopglut\ShopGlutDatabase::table_product_custom_field_settings();
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Safe table name from internal function
				$wpdb->delete( $table_name, array( 'id' => $field_id ), array( '%d' ) );
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_tools&view=product_custom_field&product_custom_field_deleted=1' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// Display success message if Product Custom Field was deleted
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for success message display only
		if ( isset( $_GET['product_custom_field_deleted'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['product_custom_field_deleted'] ) ) ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Custom field deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		$product_custom_field_table = new ProductCustomFieldListTable();
		$product_custom_field_table->prepare_items();
		?>
		<div class="wrap shopglut-admin-contents">
			<h2>
				<?php echo esc_html__( 'Product Custom Fields', 'shopglut' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&editor=product_custom_field&field_id=0' ) ); ?>" class="add-new-h2">
					<?php echo esc_html__( 'Add New', 'shopglut' ); ?>
				</a>
			</h2>
			<form method="post">
				<?php $product_custom_field_table->display(); ?>
			</form>
		</div>
		<?php //endif; ?>
		<?php
	}

	// New Render Methods for View-based Navigation
	public function renderShortcodeShowcase() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php //if($this->not_implemented): ?>
			<?php //$this->renderNotImplementedMessage(); ?>
		<?php //else: ?>
		<?php
		// Check if ShortcodeGlut plugin is active first
		$shortcodeglut_active = $this->is_shortcodeglut_active();

		if ( $shortcodeglut_active ) {
			// Use ShortcodeGlut's Shortcode Showcase
			$this->render_shortcodeglut_shortcode_showcase();
		} else {
			// Show GitHub download message when ShortcodeGlut is not active
			$this->render_shortcodeglut_download_message( 'shortcode_showcase' );
		}
		?>
		<?php //endif; ?>
		<?php
	}

	public function renderProductTemplates() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php //if($this->not_implemented): ?>
			<?php //$this->renderNotImplementedMessage(); ?>
		<?php //else: ?>
		<?php
		// Check if ShortcodeGlut plugin is active first
		$shortcodeglut_active = $this->is_shortcodeglut_active();

		if ( $shortcodeglut_active ) {
			// Use ShortcodeGlut's Woo Templates
			$this->render_shortcodeglut_woo_templates();
		} else {
			// Show GitHub download message when ShortcodeGlut is not active
			$this->render_shortcodeglut_download_message( 'woo_templates' );
		}
		?>
		<?php //endif; ?>
		<?php
	}

	public function renderLoginRegister() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		// Include the login register system
		require_once SHOPGLUT_PATH . 'src/tools/loginRegister/LoginRegister.php';
		$loginRegister = new \Shopglut\tools\loginRegister\LoginRegister();
		$loginRegister->renderLoginRegisterContent();
		?>
		<?php endif; ?>
		<?php
	}

	public function renderMiniCart() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		// Use singleton instance from ShopGlutBase initialization
		$miniCart = \Shopglut\tools\miniCart\MiniCart::get_instance();
		$miniCart->renderMiniCartContent();
		?>
		<?php endif; ?>
		<?php
	}

	public function renderWooThemes() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php if($this->not_implemented): ?>
			<?php $this->renderNotImplementedMessage(); ?>
		<?php else: ?>
		<?php
		$woo_themes = new WooThemes();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for display mode only
		if ( isset( $_GET['customize'] ) ) {
			$woo_themes->renderCustomizer();
		} else {
			$woo_themes->renderThemesList();
		}
		?>
		<?php endif; ?>
		<?php
	}

	public function renderPosglut() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<?php
		// Check if PosGlut plugin is active first
		$posglut_active = $this->is_posglut_active();

		if ( $posglut_active ) {
			// Use PosGlut's Management interface
			$this->render_posglut_management();
		} else {
			// Show GitHub download message when PosGlut is not active
			$this->render_posglut_download_message();
		}
		?>
		<?php
	}

	public function renderWooCommerceTools() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2 style="text-align: center; font-weight:bold"><?php echo esc_html__( 'WooCommerce Tools', 'shopglut' ); ?></h2>
			<p class="subheading" style="text-align: center;">
				<?php echo esc_html__( 'Powerful tools and widgets to enhance your WooCommerce store functionality', 'shopglut' ); ?>
			</p>

			<div class="shopglut-enhancements-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
				
				<!-- Product Custom Fields -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-cogs" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Product Custom Fields', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Add custom fields to your products and pages with advanced field types.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=product_custom_field' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Fields', 'shopglut' ); ?></a>
				</div>

				<!-- Shortcode Showcase -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-code" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Shortcode Showcaseff', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create and manage custom shortcodes to display content anywhere.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=shortcode_showcase' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Shortcodes', 'shopglut' ); ?></a>
				</div>

				<!-- Product Templates -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-file-alt" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Woo Templates', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create custom WooCommerce templates for products and pages.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=woo_templates' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Templates', 'shopglut' ); ?></a>
				</div>

				<!-- Login/Register -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-user-lock" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Login/Register', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Customize login and registration forms with beautiful designs.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=login_register' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Forms', 'shopglut' ); ?></a>
				</div>

				<!-- Mini Cart -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-shopping-basket" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Mini Cart', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Configure and style the mini cart widget for better user experience.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=mini_cart' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Configure Mini Cart', 'shopglut' ); ?></a>
				</div>

				<!-- Woo Themes -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-paint-brush" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Woo Themes', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Customize WooCommerce store themes with header and footer elements.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=woo_themes' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Themes', 'shopglut' ); ?></a>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render download message when ShortcodeGlut is not active
	 *
	 * @param string $feature Feature name: 'shortcode_showcase' or 'woo_templates'
	 * @return void
	 */
	private function render_shortcodeglut_download_message( $feature ) {
		$plugin_slug = 'shortcodeglut/shortcodeglut.php';
		$active_plugins = get_option( 'active_plugins', array() );
		$is_active = in_array( $plugin_slug, $active_plugins );
		$plugin_exists = file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug );

		$github_url = 'https://github.com/appglut/shortcodeglut';
		$activate_url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $plugin_slug ), 'activate-plugin_' . $plugin_slug );

		$feature_titles = array(
			'shortcode_showcase' => esc_html__( 'Shortcode Showcase', 'shopglut' ),
			'woo_templates' => esc_html__( 'Woo Templates', 'shopglut' ),
		);

		$feature_title = isset( $feature_titles[ $feature ] ) ? $feature_titles[ $feature ] : esc_html__( 'this feature', 'shopglut' );

		$feature_descriptions = array(
			'shortcode_showcase' => esc_html__( 'Powerful shortcodes for displaying woocommerce products in beautiful layouts.', 'shopglut' ),
			'woo_templates' => esc_html__( 'Create custom WooCommerce product templates with our visual template editor.', 'shopglut' ),
		);

		$feature_desc = isset( $feature_descriptions[ $feature ] ) ? $feature_descriptions[ $feature ] : '';

		?>
		<div class="wrap shopglut-admin-contents" style="max-width: 700px; margin: 40px auto;">
			<div class="shopglut-download-notice" style="background: #fff; border: 1px solid #c3c4c7; padding: 40px; text-align: center;">

				<?php if ( $plugin_exists && ! $is_active ) : ?>
					<!-- Plugin Installed - Show Activate Message -->
					<div>
						<i class="fa fa-plug" style="color: #2271b1; font-size: 48px; margin-bottom: 15px;"></i>
					</div>
					<h2 style="color: #1d2327; font-size: 24px; margin: 0 0 8px 0;">
						<?php echo esc_html( $feature_title ); ?> <?php esc_html_e( 'requires ShortcodeGlut', 'shopglut' ); ?>
					</h2>
					<p style="color: #50575e; font-size: 15px; margin: 0 0 20px 0; max-width: 500px; margin-left: auto; margin-right: auto;">
						<?php echo esc_html( $feature_desc ); ?>
					</p>
					<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-bottom: 25px; text-align: center;">
						<p style="margin: 0 0 8px 0; color: #0a4b78; font-size: 15px; font-weight: 600;">
							<?php esc_html_e( 'Ready to Activate!', 'shopglut' ); ?>
						</p>
						<p style="margin: 0 0 15px 0; color: #0a4b78; font-size: 14px; line-height: 1.6;">
							<?php esc_html_e( 'Great news! ShortcodeGlut is already installed on your site. Just activate it to unlock all the powerful features.', 'shopglut' ); ?>
						</p>
						<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary">
							<i class="fa fa-power-off" style="margin-right: 5px;"></i>
							<?php esc_html_e( 'Activate ShortcodeGlut', 'shopglut' ); ?>
						</a>
					</div>

				<?php else : ?>
					<!-- Plugin Not Installed - Show Download Message -->
					<div>
						<i class="fa fa-download" style="color: #2271b1; font-size: 48px; margin-bottom: 15px;"></i>
					</div>
					<h2 style="color: #1d2327; font-size: 24px; margin: 0 0 8px 0;">
						<?php echo esc_html( $feature_title ); ?> <?php esc_html_e( 'requires ShortcodeGlut', 'shopglut' ); ?>
					</h2>
					<p style="color: #50575e; font-size: 15px; margin: 0 0 20px 0; max-width: 500px; margin-left: auto; margin-right: auto;">
						<?php echo esc_html( $feature_desc ); ?>
					</p>
					<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-bottom: 25px; text-align: center;">
						<p style="margin: 0 0 8px 0; color: #0a4b78; font-size: 15px; font-weight: 600;">
							<?php esc_html_e( 'Free Plugin', 'shopglut' ); ?>
						</p>
						<p style="margin: 0 0 15px 0; color: #0a4b78; font-size: 14px; line-height: 1.6;">
							<?php esc_html_e( 'ShortcodeGlut is completely free! Download it from GitHub to unlock powerful shortcodes and template customization.', 'shopglut' ); ?>
						</p>
						<a href="<?php echo esc_url( $github_url ); ?>" target="_blank" class="button button-primary">
							<i class="fa fa-cloud-download" style="margin-right: 5px;"></i>
							<?php esc_html_e( 'Download from GitHub', 'shopglut' ); ?>
						</a>
					</div>

					<div style="border-top: 1px solid #c3c4c7; padding-top: 20px; color: #646970; font-size: 13px; text-align: center;">
						<p style="margin: 0 0 8px 0; font-weight: 600;">
							<?php esc_html_e( 'Installation Instructions:', 'shopglut' ); ?>
						</p>
						<ol style="margin: 0; padding-left: 0; list-style-position: inside;">
							<li><?php esc_html_e( 'Download ShortcodeGlut from GitHub', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Go to Plugins → Add New → Upload Plugin', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Upload and activate the ShortcodeGlut plugin', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Return to this page to access the feature', 'shopglut' ); ?></li>
						</ol>
					</div>

				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	/**
	 * Check if ShortcodeGlut plugin is installed and active
	 *
	 * @return bool True if ShortcodeGlut is active
	 */
	private function is_shortcodeglut_active() {
		// Check by active plugins list
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );

		if ( is_multisite() ) {
			// Get network active plugins
			$network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
		}

		// Check for shortcodeglut/shortcodeglut.php plugin
		foreach ( $active_plugins as $plugin ) {
			if ( $plugin === 'shortcodeglut/shortcodeglut.php' ) {
				return true;
			}
		}

		// Also check if the main class exists
		return class_exists( 'Shortcodeglut\\ShortcodeglutBase' );
	}

	/**
	 * Get ShortcodeGlut plugin path if installed
	 *
	 * @return string|false Path to ShortcodeGlut plugin or false if not found
	 */
	private function get_shortcodeglut_path() {
		// Check standard plugin path
		$plugin_path = WP_PLUGIN_DIR . '/shortcodeglut';

		if ( file_exists( $plugin_path . '/shortcodeglut.php' ) ) {
			return $plugin_path;
		}

		// Fallback: check in plugins list
		$plugins = get_plugins();

		foreach ( $plugins as $plugin_path_key => $plugin_data ) {
			if ( strpos( $plugin_path_key, 'shortcodeglut.php' ) !== false ) {
				return WP_PLUGIN_DIR . '/' . dirname( $plugin_path_key );
			}
		}

		return false;
	}

	/**
	 * Render ShortcodeGlut's Shortcode Showcase
	 *
	 * @return void
	 */
	private function render_shortcodeglut_shortcode_showcase() {
		$shortcodeglut_path = $this->get_shortcodeglut_path();

		if ( $shortcodeglut_path && file_exists( $shortcodeglut_path . '/src/shortcodeShowcase/AdminPage.php' ) ) {
			require_once $shortcodeglut_path . '/src/shortcodeShowcase/AdminPage.php';
			$shortcodeShowcase = new \Shortcodeglut\shortcodeShowcase\AdminPage();
			$shortcodeShowcase->renderShortcodeShowcaseContent();
		} else {
			// Fallback to ShopGlut's built-in
			$module_manager = \Shopglut\ModuleManager::get_instance();
			$module_manager->render_disabled_module_message( 'shortcode_showcase' );
		}
	}

	/**
	 * Render ShortcodeGlut's Woo Templates
	 *
	 * @return void
	 */
	private function render_shortcodeglut_woo_templates() {
		$shortcodeglut_path = $this->get_shortcodeglut_path();

		if ( ! $shortcodeglut_path ) {
			wp_die( esc_html__( 'ShortcodeGlut plugin not found.', 'shopglut' ) );
		}

		// Load required files
		$files_to_load = array(
			'src/wooTemplates/WooTemplates.php',
			'src/wooTemplates/WooTemplatesListTable.php',
			'src/wooTemplates/WooTemplatesEntity.php',
		);

		foreach ( $files_to_load as $file ) {
			$file_path = $shortcodeglut_path . '/' . $file;
			if ( file_exists( $file_path ) ) {
				require_once $file_path;
			}
		}

		// Handle delete actions
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for action routing
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['template_id'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for template ID
			$template_id = absint( $_GET['template_id'] );

			// Verify nonce
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is performed here
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_template_' . $template_id ) ) {
				\Shortcodeglut\wooTemplates\WooTemplatesEntity::delete_template( $template_id );
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_tools&view=woo_templates&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// Ensure default templates exist
		\Shortcodeglut\wooTemplates\WooTemplatesEntity::insert_default_templates();

		$templates_table = new \Shortcodeglut\wooTemplates\WooTemplatesListTable();
		$templates_table->prepare_items();

		// Display success message
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for success message display only
		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Template deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		// Render the table
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Woo Templates', 'shopglut' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&editor=woo_template' ) ); ?>">
					<span class="add-new-h2"><?php echo esc_html__( 'Add New Template', 'shopglut' ); ?></span>
				</a>
			</h2>
			<form method="post">
				<?php $templates_table->display(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render PosGlut's Management interface
	 *
	 * @return void
	 */
	private function render_posglut_management() {
		$posglut_path = $this->get_posglut_path();

		if ( $posglut_path && file_exists( $posglut_path . '/src/pos/AdminPage.php' ) ) {
			require_once $posglut_path . '/src/pos/AdminPage.php';
			$posglut_admin = new \Posglut\pos\AdminPage();
			$posglut_admin->renderManagementPage();
		} else {
			// Show download message if PosGlut files are not found
			$this->render_posglut_download_message();
		}
	}

	/**
	 * Render download message when PosGlut is not active
	 *
	 * @return void
	 */
	private function render_posglut_download_message() {
		$plugin_slug = 'posglut/posglut.php';
		$active_plugins = get_option( 'active_plugins', array() );
		$is_active = in_array( $plugin_slug, $active_plugins );
		$plugin_exists = file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug );

		$github_url = 'https://github.com/appglut/posglut';
		$activate_url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $plugin_slug ), 'activate-plugin_' . $plugin_slug );

		?>
		<div class="wrap shopglut-admin-contents" style="max-width: 700px; margin: 40px auto;">
			<div class="shopglut-download-notice" style="background: #fff; border: 1px solid #c3c4c7; padding: 40px; text-align: center;">

				<?php if ( $plugin_exists && ! $is_active ) : ?>
					<!-- Plugin Installed - Show Activate Message -->
					<div>
						<i class="fa fa-shopping-cart" style="color: #2271b1; font-size: 48px; margin-bottom: 15px;"></i>
					</div>
					<h2 style="color: #1d2327; font-size: 24px; margin: 0 0 8px 0;">
						<?php esc_html_e( 'POS (Point of Sale) requires PosGlut', 'shopglut' ); ?>
					</h2>
					<p style="color: #50575e; font-size: 15px; margin: 0 0 20px 0; max-width: 500px; margin-left: auto; margin-right: auto;">
						<?php esc_html_e( 'Complete Point of Sale (POS) system for WooCommerce with barcode scanning, receipt printing, and inventory management.', 'shopglut' ); ?>
					</p>
					<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-bottom: 25px; text-align: center;">
						<p style="margin: 0 0 8px 0; color: #0a4b78; font-size: 15px; font-weight: 600;">
							<?php esc_html_e( 'Ready to Activate!', 'shopglut' ); ?>
						</p>
						<p style="margin: 0 0 15px 0; color: #0a4b78; font-size: 14px; line-height: 1.6;">
							<?php esc_html_e( 'Great news! PosGlut is already installed on your site. Just activate it to unlock the POS features.', 'shopglut' ); ?>
						</p>
						<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary">
							<i class="fa fa-power-off" style="margin-right: 5px;"></i>
							<?php esc_html_e( 'Activate PosGlut', 'shopglut' ); ?>
						</a>
					</div>

				<?php else : ?>
					<!-- Plugin Not Installed - Show Download Message -->
					<div>
						<i class="fa fa-download" style="color: #2271b1; font-size: 48px; margin-bottom: 15px;"></i>
					</div>
					<h2 style="color: #1d2327; font-size: 24px; margin: 0 0 8px 0;">
						<?php esc_html_e( 'POS (Point of Sale) requires PosGlut', 'shopglut' ); ?>
					</h2>
					<p style="color: #50575e; font-size: 15px; margin: 0 0 20px 0; max-width: 500px; margin-left: auto; margin-right: auto;">
						<?php esc_html_e( 'Complete Point of Sale (POS) system for WooCommerce with barcode scanning, receipt printing, and inventory management.', 'shopglut' ); ?>
					</p>
					<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-bottom: 25px; text-align: center;">
						<p style="margin: 0 0 8px 0; color: #0a4b78; font-size: 15px; font-weight: 600;">
							<?php esc_html_e( 'Free Plugin', 'shopglut' ); ?>
						</p>
						<p style="margin: 0 0 15px 0; color: #0a4b78; font-size: 14px; line-height: 1.6;">
							<?php esc_html_e( 'PosGlut is completely free! Download it from GitHub to unlock powerful POS features for your WooCommerce store.', 'shopglut' ); ?>
						</p>
						<a href="<?php echo esc_url( $github_url ); ?>" target="_blank" class="button button-primary">
							<i class="fa fa-cloud-download" style="margin-right: 5px;"></i>
							<?php esc_html_e( 'Download from GitHub', 'shopglut' ); ?>
						</a>
					</div>

					<div style="border-top: 1px solid #c3c4c7; padding-top: 20px; color: #646970; font-size: 13px; text-align: center;">
						<p style="margin: 0 0 8px 0; font-weight: 600;">
							<?php esc_html_e( 'Installation Instructions:', 'shopglut' ); ?>
						</p>
						<ol style="margin: 0; padding-left: 0; list-style-position: inside;">
							<li><?php esc_html_e( 'Download PosGlut from GitHub', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Go to Plugins → Add New → Upload Plugin', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Upload and activate the PosGlut plugin', 'shopglut' ); ?></li>
							<li><?php esc_html_e( 'Return to this page to access POS features', 'shopglut' ); ?></li>
						</ol>
					</div>

				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	/**
	 * Check if PosGlut plugin is installed and active
	 *
	 * @return bool True if PosGlut is active
	 */
	private function is_posglut_active() {
		// Check by active plugins list
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );

		if ( is_multisite() ) {
			// Get network active plugins
			$network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
		}

		// Check for posglut/posglut.php plugin
		foreach ( $active_plugins as $plugin ) {
			if ( $plugin === 'posglut/posglut.php' ) {
				return true;
			}
		}

		// Also check if the main class exists
		return class_exists( 'Posglut\\PosglutBase' );
	}

	/**
	 * Get PosGlut plugin path if installed
	 *
	 * @return string|false Path to PosGlut plugin or false if not found
	 */
	private function get_posglut_path() {
		// Check standard plugin path
		$plugin_path = WP_PLUGIN_DIR . '/posglut';

		if ( file_exists( $plugin_path . '/posglut.php' ) ) {
			return $plugin_path;
		}

		// Fallback: check in plugins list
		$plugins = get_plugins();

		foreach ( $plugins as $plugin_path_key => $plugin_data ) {
			if ( strpos( $plugin_path_key, 'posglut.php' ) !== false ) {
				return WP_PLUGIN_DIR . '/' . dirname( $plugin_path_key );
			}
		}

		return false;
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}