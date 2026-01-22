<?php
namespace Shopglut\enhancements;

if ( ! defined( 'ABSPATH' ) ) exit;

use Shopglut\enhancements\Filters\FilterListTable;

use Shopglut\enhancements\Filters\FiltersEntity;

use  Shopglut\enhancements\Filters\FiltersSettingsPage;



use Shopglut\enhancements\ProductBadges\BadgeListTable;

use Shopglut\enhancements\ProductBadges\BadgeEntity;


use Shopglut\enhancements\ProductBadges\BadgesSettingsPage;

use Shopglut\enhancements\ProductBadges\BadgeTemplates;

use Shopglut\enhancements\ProductBadges\BadgechooseTemplates;



use Shopglut\enhancements\ProductQuickView\QuickViewListTable;

use Shopglut\enhancements\ProductQuickView\QuickViewEntity;

use Shopglut\enhancements\ProductQuickView\QuickViewSettingsPage;

use Shopglut\enhancements\ProductQuickView\QuickViewchooseTemplates as QuickViewTemplates;

use Shopglut\enhancements\ProductComparison\ProductComparisonListTable;

use Shopglut\enhancements\ProductComparison\ProductComparisonEntity;

use Shopglut\enhancements\ProductComparison\SettingsPage as ComparisonSettings;

use Shopglut\enhancements\ProductComparison\ComparisonchooseTemplates as ComparisonTemplates;

use Shopglut\enhancements\ProductSwatches\ProductSwatchesListTable;

use Shopglut\enhancements\ProductSwatches\ProductSwatchesEntity;

use Shopglut\enhancements\ProductSwatches\SettingsPage as ProductSwatchesSettings;

use Shopglut\enhancements\ProductSwatches\chooseTemplates as SwatchesChooseTemplates;

use Shopglut\enhancements\ProductSwatches\AttributeSwatchesManager;


class AllEnhancements {

	public $not_implemented;

	public function __construct() {

		add_filter( 'admin_body_class', array( $this, 'shopglutBodyClass' ) );
		add_action( 'admin_post_create_badge', array( $this, 'handleCreateBadge' ) );

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
		if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) && isset( $_GET['editor'] ) ) {
			$classes .= '-shopglut-editor-collapse ';
		}


        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
        if ( isset( $_GET['page'] ) && 'shopglut_enhancements' === sanitize_text_field( wp_unslash($_GET['page'] )) ) {
            
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
            if ( isset( $_GET['editor'] ) ) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for CSS class addition only
                $editor = sanitize_text_field( wp_unslash($_GET['editor']) );
                
                switch ( $editor ) {
                    case 'filters':
                        $classes .= '-shopglut-filters-editor ';
                        $classes .= ' shopglut-fullwidth-editor ';
                        break;
                    case 'product_badges':
                        $classes .= '-shopglut-badges-editor ';
                        $classes .= ' shopglut-fullwidth-editor ';
                        break;
                }
            }
        }

        return $classes;
   }

	public function renderenhancementsPages() {

		// Check user permissions first
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}


		$filter_settings = new FiltersSettingsPage();
		$badge_settings = new BadgesSettingsPage();
	    $comparison_settings = new ComparisonSettings();
		$quickview_settings = new QuickViewSettingsPage();


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
		$layout_id = isset( $_GET['layout_id'] ) ? sanitize_text_field( wp_unslash( $_GET['layout_id'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only

	     // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$filter_id = isset( $_GET['filter_id'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_id'] ) ) : '';

	     // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
		$badge_id = isset( $_GET['badge_id'] ) ? sanitize_text_field( wp_unslash( $_GET['badge_id'] ) ) : '';


		// Handle shopglut_enhancements page
		if ( 'shopglut_enhancements' === $page ) {

			
			// Editor routes with layout_id
			if ( ! empty( $editor ) && (! empty( $layout_id ) || (!empty($filter_id)) || (!empty($badge_id)))) {
				switch ( $editor ) {
					case 'filters':
						$filter_settings->FilterSettings();
						break;
					case 'product_badges':
						$badge_settings->BadgeEditor();
						break;
					case 'product_swatches':
						$swatches_settings = new ProductSwatchesSettings();
						$swatches_settings->render();
						break;
					case 'product_comparison':
						$comparison_settings->loadProductComparisonEditor();
						break;
					case 'product_quickview':
						$quickview_settings->loadProductQuickviewEditor();
						break;

					case 'product_quickview':
						$quickview_settings->loadAcfEditor();
						break;

					default:
						wp_die( esc_html__( 'Invalid editor type.', 'shopglut' ) );
				}
			}
			// View routes with parameters
			elseif ( ! empty( $view ) && isset( $_GET['attribute'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
				$attribute = sanitize_text_field( wp_unslash( $_GET['attribute'] ) );

				switch ( $view ) {
					case 'product_swatches_templates':
						$this->renderSwatchesTemplates( $attribute );
						break;
					default:
						break;
				}
			}
			// Template view routes
			elseif ( ! empty( $view ) ) {
				switch ( $view ) {
					
					case 'product_badges':
						$this->renderProductBadges();
						break;
					case 'product_badge_templates':
						$this->renderBadgeTemplates();
						break;
					case 'product_comparisons':
						$this->renderProductComparison();
						break;
					case 'product_quickviews':
						$this->renderQuickView();
						break;
					case 'product_comparison_templates':
						$this->renderProductComparisonTemplates();
						break;
					case 'product_quick_view_templates':
						$this->renderQuickViewTemplates();
						break;
					case 'wishlist':
						$this->renderWishlist();
						break;
					case 'shop_filters':
						$this->renderShopFilters();
						break;
					case 'filter_another':
						$this->renderFiltersTable();
						break;
					case 'product_swatches':
						$this->renderProductSwatches();
						break;
					case 'product_swatches_templates':
						$this->renderSwatchesTemplates();
						break;
					case 'attribute_swatches':
						$this->renderAttributeSwatches();
						break;
					default:
						// Default case - you can uncomment if needed
						// $this->renderenhancementsTable();
						break;
				}
			}
			// Default shopglut_enhancements page
			else {
				$this->renderWooCommerceEnhancements();
			}
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
		return 'all_enhancements';
	}

	public function headerMenuTabs() {
			$tabs = [ 
				1 => [ 'id' => 'all_enhancements', 'url' => admin_url( 'admin.php?page=shopglut_enhancements' ), 'label' => '✨ ' . esc_html__( 'All Enhancements', 'shopglut' ) ],
				5 => [ 'id' => 'wishlist', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=wishlist' ), 'label' => '❤️ ' . esc_html__( 'Wishlist', 'shopglut' ) ],
				10 => [ 'id' => 'shop_filters', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=shop_filters' ), 'label' => '🔍 ' . esc_html__( 'Shop Filters', 'shopglut' ) ],
				15 => [ 'id' => 'product_swatches', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=product_swatches' ), 'label' => '🎨 ' . esc_html__( 'Product Swatches', 'shopglut' ) ],
				20 => [ 'id' => 'product_badges', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=product_badges' ), 'label' => '🏷️ ' . esc_html__( 'Product Badges', 'shopglut' ) ],
				25 => [ 'id' => 'product_comparisons', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparisons' ), 'label' => '⚖️ ' . esc_html__( 'Product Comparison', 'shopglut' ) ],
				30 => [ 'id' => 'product_quickviews', 'url' => admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' ), 'label' => '⚡ ' . esc_html__( 'Quick View', 'shopglut' ) ],
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
				// If no view parameter, we're on the main landing page (all_enhancements)
				if ( !isset( $_GET['view'] ) && isset( $_GET['page'] ) && $_GET['page'] === 'shopglut_enhancements' ) {
					return 'all_enhancements';
				}
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for menu display only
				return isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : $this->defaultHeaderMenu();
			}

			return false;
	}

	public function renderenhancementsTable() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification handled below
		if ( isset( $_GET['action'] ) && 'delete' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) && isset( $_GET['layout_id'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification handled below
			$layout_id = absint( wp_unslash( $_GET['layout_id'] ) );

			// Verify nonce
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This IS the nonce verification
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				LayoutEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for success message display only
		if ( isset( $_GET['deleted'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['deleted'] ) ) ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		
		$enhancements_table = new ShopListTable();
		$enhancements_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'enhancements', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=shop_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $enhancements_table->display(); ?>
			</form>
		</div>
		<?php
	}
	
	public function renderWooCommerceEnhancements() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2 style="text-align: center; font-weight: bold;"><?php echo esc_html__( 'WooCommerce Enhancements', 'shopglut' ); ?></h2>
			<p class="subheading" style="text-align: center;">
				<?php echo esc_html__( 'Configure and manage your WooCommerce store enhancements', 'shopglut' ); ?>
			</p>

			<div class="shopglut-enhancements-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
				
				<!-- Wishlist -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-heart" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Wishlist', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Enable and configure wishlist functionality for your customers.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=wishlist' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Wishlist', 'shopglut' ); ?></a>
				</div>

				<!-- Shop Filters -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-filter" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Shop Filters', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Configure advanced filtering enhancements for your shop pages.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=shop_filters' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Setup Filters', 'shopglut' ); ?></a>
				</div>

				<!-- Product Swatches -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-palette" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Product Swatches', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create color and image swatches for product variations.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_swatches' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Configure Swatches', 'shopglut' ); ?></a>
				</div>

				<!-- Product Badges -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-tags" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Product Badges', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Create and manage promotional badges for your products.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_badges' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Badges', 'shopglut' ); ?></a>
				</div>

				<!-- Product Comparison -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fas fa-balance-scale" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Product Comparison', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Allow customers to compare products side by side.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparisons' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Setup Comparison', 'shopglut' ); ?></a>
				</div>

				<!-- Quick View -->
				<div class="shopglut-option-card" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<div class="option-header" style="display: flex; align-items: center; margin-bottom: 15px;">
						<i class="fa-solid fa-forward-fast" style="font-size: 24px; color: #667eea; margin-right: 12px;"></i>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html__( 'Quick View', 'shopglut' ); ?></h3>
					</div>
					<p style="color: #666; margin-bottom: 15px;"><?php echo esc_html__( 'Enable quick view popups for products on shop pages.', 'shopglut' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Configure Quick View', 'shopglut' ); ?></a>
				</div>

			</div>
		</div>
		<?php
	}

	public function renderProductBadges() {
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

		// Check if product_badges module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'product_badges' ) ) {
			$module_manager->render_disabled_module_message( 'product_badges' );
			return;
		}
		?>


		<?php if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['badge_id'] ) ) {
			$badge_id = absint( $_GET['badge_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_badge_' . $badge_id ) ) {
				// Delete the badge using direct database query
				global $wpdb;
				$table_name = \Shopglut\ShopGlutDatabase::table_product_badges();
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
				$result = $wpdb->delete( $table_name, array( 'id' => $badge_id ), array( '%d' ) );

				if ( $result === false ) {
					wp_die( esc_html__( 'Database error: Could not delete badge.', 'shopglut' ) );
				}

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&view=product_badges&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Badge deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		$badges_table = new BadgeListTable();
		$badges_table->prepare_items();
		$active_menu = $this->activeMenuTab();

		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Product Badges ', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_badge_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Badge', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $badges_table->display(); ?>
			</form>
			<?php //endif;  ?>
		</div>
		<?php

	}

	private function renderBadgeEditor( $badge_id ) {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		

		if($this->not_implemented):
		$this->renderNotImplementedMessage();
		else:
		// Get badge from database with caching and proper escaping
		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

		// Use caching for better performance
		$cache_key = "shopglut_badge_{$badge_id}";
		$badge = wp_cache_get( $cache_key );

		if ( false === $badge ) {
			// Use escaped table name for backward compatibility
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$badge = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM `" . esc_sql($table_name) . "` WHERE id = %d",
					$badge_id
				)
			);

			// Cache the result for 30 minutes
			wp_cache_set( $cache_key, $badge, '', 30 * MINUTE_IN_SECONDS );
		}

		if ( ! $badge ) {
			wp_die( esc_html__( 'Badge not found.', 'shopglut' ) );
		}

		$badge_data = maybe_unserialize( $badge->layout_settings );

		?>
		<div class="wrap shopglut-admin-contents">
			<div class="shopglut-badge-editor">
				<div class="editor-header">
					<h1><?php echo esc_html__( 'Edit Badge:', 'shopglut' ); ?> <?php echo esc_html( $badge->layout_name ); ?></h1>
					<div class="editor-actions">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_badges' ) ); ?>" 
						   class="button">
							<?php echo esc_html__( '← Back to Badges', 'shopglut' ); ?>
						</a>
						<button type="button" class="button button-primary" id="save-badge-editor">
							<?php echo esc_html__( 'Save Changes', 'shopglut' ); ?>
						</button>
					</div>
				</div>

				<div class="editor-content">
					<form id="badge-editor-form" method="post">
						<?php wp_nonce_field( 'shopglut_save_badge_' . $badge_id, 'badge_nonce' ); ?>
						<input type="hidden" name="badge_id" value="<?php echo esc_attr( $badge_id ); ?>">
						
						<div class="editor-main">
							<div class="editor-sidebar">
								<div class="form-section">
									<h3><?php echo esc_html__( 'Badge Settings', 'shopglut' ); ?></h3>
									
									<div class="form-row">
										<label for="badge-name"><?php echo esc_html__( 'Badge Name', 'shopglut' ); ?></label>
										<input type="text" id="badge-name" name="badge_name"
											   value="<?php echo esc_attr( $badge->layout_name ); ?>" required>
									</div>
									
									<div class="form-row">
										<label for="badge-text"><?php echo esc_html__( 'Badge Text', 'shopglut' ); ?></label>
										<input type="text" id="badge-text" name="badge_text" 
											   value="<?php echo esc_attr( $badge_data['text'] ?? '' ); ?>">
									</div>
									
									<div class="form-row">
										<label for="badge-status"><?php echo esc_html__( 'Status', 'shopglut' ); ?></label>
										<select id="badge-status" name="badge_status">
											<option value="active" <?php selected( $badge->status, 'active' ); ?>>
												<?php echo esc_html__( 'Active', 'shopglut' ); ?>
											</option>
											<option value="inactive" <?php selected( $badge->status, 'inactive' ); ?>>
												<?php echo esc_html__( 'Inactive', 'shopglut' ); ?>
											</option>
										</select>
									</div>
								</div>

								<div class="form-section">
									<h3><?php echo esc_html__( 'Style Options', 'shopglut' ); ?></h3>
									
									<div class="form-row">
										<label for="background-color"><?php echo esc_html__( 'Background Color', 'shopglut' ); ?></label>
										<input type="text" id="background-color" name="background_color" 
											   class="color-picker" value="<?php echo esc_attr( $badge_data['style']['background_color'] ?? '#ff0000' ); ?>">
									</div>
									
									<div class="form-row">
										<label for="text-color"><?php echo esc_html__( 'Text Color', 'shopglut' ); ?></label>
										<input type="text" id="text-color" name="text_color" 
											   class="color-picker" value="<?php echo esc_attr( $badge_data['style']['text_color'] ?? '#ffffff' ); ?>">
									</div>
									
									<div class="form-row">
										<label for="font-size"><?php echo esc_html__( 'Font Size (px)', 'shopglut' ); ?></label>
										<input type="number" id="font-size" name="font_size" 
											   value="<?php echo esc_attr( $badge_data['style']['font_size'] ?? 12 ); ?>" min="8" max="48">
									</div>
									
									<div class="form-row">
										<label for="border-radius"><?php echo esc_html__( 'Border Radius (px)', 'shopglut' ); ?></label>
										<input type="number" id="border-radius" name="border_radius" 
											   value="<?php echo esc_attr( $badge_data['style']['border_radius'] ?? 3 ); ?>" min="0" max="50">
									</div>
									
									<div class="form-row">
										<label for="padding"><?php echo esc_html__( 'Padding', 'shopglut' ); ?></label>
										<input type="text" id="padding" name="padding" 
											   value="<?php echo esc_attr( $badge_data['style']['padding'] ?? '5px 10px' ); ?>">
									</div>
									
									<div class="form-row">
										<label for="position"><?php echo esc_html__( 'Position', 'shopglut' ); ?></label>
										<select id="position" name="position">
											<option value="top-left" <?php selected( $badge_data['style']['position'] ?? '', 'top-left' ); ?>>
												<?php echo esc_html__( 'Top Left', 'shopglut' ); ?>
											</option>
											<option value="top-right" <?php selected( $badge_data['style']['position'] ?? '', 'top-right' ); ?>>
												<?php echo esc_html__( 'Top Right', 'shopglut' ); ?>
											</option>
											<option value="bottom-left" <?php selected( $badge_data['style']['position'] ?? '', 'bottom-left' ); ?>>
												<?php echo esc_html__( 'Bottom Left', 'shopglut' ); ?>
											</option>
											<option value="bottom-right" <?php selected( $badge_data['style']['position'] ?? '', 'bottom-right' ); ?>>
												<?php echo esc_html__( 'Bottom Right', 'shopglut' ); ?>
											</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="editor-preview">
								<h3><?php echo esc_html__( 'Preview', 'shopglut' ); ?></h3>
								<div class="preview-container">
									<div class="preview-product">
										<div class="product-image">
											<div class="badge-preview-area" id="live-badge-preview">
												<!-- Live preview will be inserted here -->
											</div>
											<div class="placeholder-image">
												<span class="dashicons dashicons-format-image"></span>
											</div>
										</div>
										<div class="product-info">
											<h4><?php echo esc_html__( 'Sample Product', 'shopglut' ); ?></h4>
											<div class="price">$29.99</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php endif;
	}

	public function renderWishlist() {
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>

		<?php
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}
		?>
		<div class="wrap shopglut-admin-contents">
			<div style="max-width: 800px; margin: 12px auto; text-align: center;">
				<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
					<div style="margin-bottom: 30px;">
						<div style="font-size: 64px; margin-bottom: 20px; opacity: 0.7;">
							❤️
						</div>
						<h1 style="color: #2c3e50; font-size: 32px; font-weight: 600; margin: 0 0 15px 0; line-height: 1.3;">
							<?php echo esc_html__( 'Wishlist Management', 'shopglut' ); ?>
						</h1>
						<p style="color: #7f8c8d; font-size: 18px; margin: 0 0 40px 0; line-height: 1.6;">
							<?php echo esc_html__( 'Configure and manage wishlist functionality for your WooCommerce store', 'shopglut' ); ?>
						</p>
					</div>

					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_wishlist' ) ); ?>" class="shopglut-wishlist-button">
						<span><i class="fa fa-cog"></i></span>
						<?php echo esc_html__( 'Go to Wishlist Admin', 'shopglut' ); ?>
					</a>
				</div>
			</div>
		</div>

		<?php
	}

	public function renderShopFilters() {
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

		// Check if shop_filters module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'shop_filters' ) ) {
			$module_manager->render_disabled_module_message( 'shop_filters' );
			return;
		}
		?>


		<?php if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['filter_id'] ) ) {
			$filter_id = absint( $_GET['filter_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_filter_' . $filter_id ) ) {
				// Delete the layout
				FiltersEntity::delete_layout( $filter_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Filter deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		$filters_table = new FilterListTable();
		$filters_table->prepare_items();
		$active_menu = $this->activeMenuTab();

		?>
		<div class="wrap shopglut-admin-contents">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="create_filter">
				<?php global $wpdb;
				$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';

				// Get max ID without caching
				$max_id_sql = $wpdb->prepare( "SELECT MAX(id) FROM `%s`", $table_name );
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table name prepared, no user input
				$cached_max_id = intval( $wpdb->get_var( $max_id_sql ) );

				$filter_id = $cached_max_id ? $cached_max_id + 1 : 1;
				?>
				<input type="hidden" name="filter_id" value="<?php echo esc_attr( $filter_id ); ?>">
				<?php wp_nonce_field( 'create_filter_nonce', 'create_filter_nonce' ); ?>
				<div class="wrap">
					<h2><?php echo esc_html__( 'Shop Filters ', 'shopglut' ); ?><input class="add-new-h2" type="submit"
							name="publish" id="publish" value="<?php echo esc_html__( "Add New Filter", 'shopglut' ); ?>" />
					</h2>
				</div>

			</form>

			<form method="post">
				<?php $filters_table->display(); ?>
			</form>
		</div>
		<?php endif; ?>
		<?php

	}

	public function renderFiltersTable() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['filter_id'] ) ) {
			$filter_id = absint( $_GET['filter_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_filter_' . $filter_id ) ) {
				// Delete the layout
				FiltersEntity1::delete_layout( $filter_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Filter deleted successfully.', 'shopglut' ) . '</p></div>';
		}

		$filters_table = new FilterListTable1();
		$filters_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="create_filter1">
				<?php global $wpdb;
				$table_name = $wpdb->prefix . 'shopglut_enhancements_filters';
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Custom table query, table name is safe (uses $wpdb->prefix)
				$filter_id = intval( $wpdb->get_var( "SELECT MAX(id) FROM {$table_name}" ) );
				$filter_id = $filter_id ? $filter_id + 1 : 1;
				?>
				<input type="hidden" name="filter_id" value="<?php echo esc_attr( $filter_id ); ?>">
				<?php wp_nonce_field( 'create_filter1_nonce', 'create_filter1_nonce' ); ?>
				<div class="wrap">
					<h2><?php echo esc_html__( 'Shop Filters ', 'shopglut' ); ?><input class="add-new-h2" type="submit"
							name="publish" id="publish" value="<?php echo esc_html__( "Add New Filter", 'shopglut' ); ?>" />
					</h2>
				</div>

			</form>

			<form method="post">
				<?php $filters_table->display(); ?>
			</form>

		</div>
		<?php
	}

	public function renderProductSwatches() {
		$active_menu = 'product_swatches';
		$this->settingsPageHeader( $active_menu );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if product_swatches module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'product_swatches' ) ) {
			$module_manager->render_disabled_module_message( 'product_swatches' );
			return;
		}

		// Get all WooCommerce product attributes
		$product_attributes = wc_get_attribute_taxonomies();

		// Get existing attribute layouts to show which are configured
		global $wpdb;
		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$attribute_layouts = $wpdb->get_results(
			"SELECT id, layout_name, layout_template, assigned_attributes FROM `{$table_name}`
			WHERE assignment_type = 'attribute'
			ORDER BY updated_at DESC",
			ARRAY_A
		);

		// Create a map of attribute -> layout
		$attribute_layout_map = array();
		foreach ($attribute_layouts as $layout) {
			$assigned_attrs = !empty($layout['assigned_attributes']) ? json_decode($layout['assigned_attributes'], true) : array();
			foreach ($assigned_attrs as $attr) {
				$attribute_layout_map[$attr] = array(
					'layout_id' => $layout['id'],
					'layout_name' => $layout['layout_name'],
					'template' => $layout['layout_template'],
				);
			}
		}
		?>

		<div class="wrap shopglut-admin-contents">
			<h1 style="font-weight: 600;"><?php esc_html_e( 'Product Attribute Swatches', 'shopglut' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Select an attribute to customize its swatch display. Each attribute can have its own template and styling.', 'shopglut' ); ?></p>

			<div class="shopglut-attributes-grid">
				<?php if ( empty( $product_attributes ) ) : ?>
					<div class="shopglut-empty-state">
						<div class="empty-state-icon">🎨</div>
						<h3><?php esc_html_e( 'No Product Attributes Found', 'shopglut' ); ?></h3>
						<p><?php esc_html_e( 'You need to create product attributes first. Go to Products → Attributes to create them.', 'shopglut' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product&page=product_attributes' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Manage Attributes', 'shopglut' ); ?>
						</a>
					</div>
				<?php else : ?>
					<?php foreach ( $product_attributes as $attribute ) :
						$taxonomy_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
						$is_configured = isset( $attribute_layout_map[ $taxonomy_name ] );
						$layout_info = $is_configured ? $attribute_layout_map[ $taxonomy_name ] : null;
						?>

						<div class="shopglut-attribute-card <?php echo $is_configured ? 'configured' : ''; ?>"
							 data-attribute="<?php echo esc_attr( $taxonomy_name ); ?>"
							 data-label="<?php echo esc_attr( $attribute->attribute_label ); ?>">

							<div class="attribute-card-header">
								<div class="attribute-icon">
									<?php
									$icon = 'palette';
									if ( $attribute->attribute_type === 'color' ) $icon = 'admin-appearance';
									elseif ( $attribute->attribute_type === 'image' ) $icon = 'format-image';
									elseif ( $attribute->attribute_type === 'label' ) $icon = 'tag';
									?>
									<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span>
								</div>
								<div class="attribute-info">
									<h3><?php echo esc_html( $attribute->attribute_label ); ?></h3>
									<span class="attribute-taxonomy"><?php echo esc_html( $taxonomy_name ); ?></span>
									<span class="attribute-type"><?php echo esc_html( ucfirst( $attribute->attribute_type ) ); ?></span>
								</div>
								<div class="attribute-status">
									<?php if ( $is_configured ) : ?>
										<span class="status-badge configured">
											<span class="dashicons dashicons-yes-alt"></span>
											<?php esc_html_e( 'Configured', 'shopglut' ); ?>
										</span>
										<span class="template-badge"><?php echo esc_html( $layout_info['template'] ); ?></span>
									<?php else : ?>
										<span class="status-badge not-configured">
											<span class="dashicons dashicons-minus"></span>
											<?php esc_html_e( 'Not Set', 'shopglut' ); ?>
										</span>
									<?php endif; ?>
								</div>
							</div>

							<div class="attribute-card-actions">
								<?php if ( $is_configured ) : ?>
									<a href="<?php echo esc_url( admin_url( sprintf(
										'admin.php?page=shopglut_enhancements&editor=product_swatches&layout_id=%d',
										$layout_info['layout_id']
									) ) ); ?>" class="button button-secondary">
										<span class="dashicons dashicons-edit"></span>
										<?php esc_html_e( 'Edit Layout', 'shopglut' ); ?>
									</a>
									<button type="button" class="button button-secondary shopglut-reset-layout"
											data-attribute="<?php echo esc_attr( $taxonomy_name ); ?>"
											data-layout-id="<?php echo esc_attr( $layout_info['layout_id'] ); ?>"
											data-nonce="<?php echo esc_attr( wp_create_nonce( 'shopglut_reset_attribute_layout_' . $layout_info['layout_id'] ) ); ?>">
										<span class="dashicons dashicons-dismiss"></span>
										<?php esc_html_e( 'Reset', 'shopglut' ); ?>
									</button>
								<?php else : ?>
									<a href="<?php echo esc_url( admin_url( sprintf(
										'admin.php?page=shopglut_enhancements&view=product_swatches_templates&attribute=%s',
										$taxonomy_name
									) ) ); ?>" class="button button-primary">
										<span class="dashicons dashicons-plus"></span>
										<?php esc_html_e( 'Add Layout', 'shopglut' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>

					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<!-- Global Settings Section -->
			<div class="shopglut-global-settings-section">
				<h2><?php esc_html_e('Global Settings', 'shopglut'); ?></h2>
				<p class="description"><?php esc_html_e('Configure global settings that apply to all swatch displays.', 'shopglut'); ?></p>

				<?php
				// Get global swatches settings
				$global_swatches_settings = get_option('shopglut_global_swatches_settings', array());
				$clear_button = isset($global_swatches_settings['clear_button']) ? $global_swatches_settings['clear_button'] : array();
				$price_display = isset($global_swatches_settings['price_display']) ? $global_swatches_settings['price_display'] : array();
				?>

				<div class="shopglut-settings-grid">
					<!-- Clear Button Settings -->
					<div class="shopglut-setting-card">
						<div class="setting-card-header">
							<div class="setting-icon">
								<span class="dashicons dashicons-dismiss"></span>
							</div>
							<h3><?php esc_html_e('Clear Button', 'shopglut'); ?></h3>
						</div>
						<div class="setting-card-content">
							<table class="form-table">
								<tr>
									<th><?php esc_html_e('Enable Clear Button', 'shopglut'); ?></th>
									<td>
										<label class="switch">
											<input type="checkbox" id="shopglut_enable_clear_button" value="1" <?php checked(isset($clear_button['enable']) ? $clear_button['enable'] : true, true); ?>>
											<span class="slider round"></span>
										</label>
										<p class="description"><?php esc_html_e('Show a clear button to reset selected variations', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Button Text', 'shopglut'); ?></th>
									<td>
										<input type="text" id="shopglut_clear_button_text" class="regular-text" value="<?php echo esc_attr(isset($clear_button['text']) ? $clear_button['text'] : 'Clear'); ?>">
										<p class="description"><?php esc_html_e('Text to display on the clear button', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_clear_button_color" value="<?php echo esc_attr(isset($clear_button['color']) ? $clear_button['color'] : '#2271b1'); ?>">
										<p class="description"><?php esc_html_e('Color of the clear button text', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Size', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_font_size" class="small-text" value="<?php echo esc_attr(isset($clear_button['font_size']) ? $clear_button['font_size'] : 14); ?>" min="10" max="20">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Margin', 'shopglut'); ?></th>
									<td>
										<div class="shopglut-padding-box-wrapper">
											<div class="shopglut-padding-box">
												<div class="shopglut-padding-field">
													<label for="shopglut_clear_button_margin_top"><?php esc_html_e('Top', 'shopglut'); ?></label>
													<input type="number" id="shopglut_clear_button_margin_top" class="small-text" value="<?php echo esc_attr(isset($clear_button['margin']['top']) ? $clear_button['margin']['top'] : (isset($clear_button['margin_top']) ? $clear_button['margin_top'] : 0)); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
												<div class="shopglut-padding-row">
													<div class="shopglut-padding-field">
														<label for="shopglut_clear_button_margin_left"><?php esc_html_e('Left', 'shopglut'); ?></label>
														<input type="number" id="shopglut_clear_button_margin_left" class="small-text" value="<?php echo esc_attr(isset($clear_button['margin']['left']) ? $clear_button['margin']['left'] : (isset($clear_button['margin_left']) ? $clear_button['margin_left'] : 15)); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
													<div class="shopglut-padding-field">
														<label for="shopglut_clear_button_margin_right"><?php esc_html_e('Right', 'shopglut'); ?></label>
														<input type="number" id="shopglut_clear_button_margin_right" class="small-text" value="<?php echo esc_attr(isset($clear_button['margin']['right']) ? $clear_button['margin']['right'] : (isset($clear_button['margin_right']) ? $clear_button['margin_right'] : 0)); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
												</div>
												<div class="shopglut-padding-field">
													<label for="shopglut_clear_button_margin_bottom"><?php esc_html_e('Bottom', 'shopglut'); ?></label>
													<input type="number" id="shopglut_clear_button_margin_bottom" class="small-text" value="<?php echo esc_attr(isset($clear_button['margin']['bottom']) ? $clear_button['margin']['bottom'] : (isset($clear_button['margin_bottom']) ? $clear_button['margin_bottom'] : 0)); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Family', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_font_family">
											<option value="inherit" <?php selected(isset($clear_button['font_family']) ? $clear_button['font_family'] : 'inherit', 'inherit'); ?>><?php esc_html_e('Inherit', 'shopglut'); ?></option>
											<option value="Arial, sans-serif" <?php selected(isset($clear_button['font_family']) ? $clear_button['font_family'] : 'inherit', 'Arial, sans-serif'); ?>><?php esc_html_e('Arial', 'shopglut'); ?></option>
											<option value="Georgia, serif" <?php selected(isset($clear_button['font_family']) ? $clear_button['font_family'] : 'inherit', 'Georgia, serif'); ?>><?php esc_html_e('Georgia', 'shopglut'); ?></option>
											<option value="'Times New Roman', serif" <?php selected(isset($clear_button['font_family']) ? $clear_button['font_family'] : 'inherit', '"Times New Roman", serif'); ?>><?php esc_html_e('Times New Roman', 'shopglut'); ?></option>
											<option value="Verdana, sans-serif" <?php selected(isset($clear_button['font_family']) ? $clear_button['font_family'] : 'inherit', 'Verdana, sans-serif'); ?>><?php esc_html_e('Verdana', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Weight', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_font_weight">
											<option value="300" <?php selected(isset($clear_button['font_weight']) ? $clear_button['font_weight'] : '500', '300'); ?>><?php esc_html_e('Light', 'shopglut'); ?></option>
											<option value="400" <?php selected(isset($clear_button['font_weight']) ? $clear_button['font_weight'] : '500', '400'); ?>><?php esc_html_e('Normal', 'shopglut'); ?></option>
											<option value="500" <?php selected(isset($clear_button['font_weight']) ? $clear_button['font_weight'] : '500', '500'); ?>><?php esc_html_e('Medium', 'shopglut'); ?></option>
											<option value="600" <?php selected(isset($clear_button['font_weight']) ? $clear_button['font_weight'] : '500', '600'); ?>><?php esc_html_e('Semi Bold', 'shopglut'); ?></option>
											<option value="700" <?php selected(isset($clear_button['font_weight']) ? $clear_button['font_weight'] : '500', '700'); ?>><?php esc_html_e('Bold', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Text Transform', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_text_transform">
											<option value="none" <?php selected(isset($clear_button['text_transform']) ? $clear_button['text_transform'] : 'none', 'none'); ?>><?php esc_html_e('None', 'shopglut'); ?></option>
											<option value="uppercase" <?php selected(isset($clear_button['text_transform']) ? $clear_button['text_transform'] : 'none', 'uppercase'); ?>><?php esc_html_e('Uppercase', 'shopglut'); ?></option>
											<option value="lowercase" <?php selected(isset($clear_button['text_transform']) ? $clear_button['text_transform'] : 'none', 'lowercase'); ?>><?php esc_html_e('Lowercase', 'shopglut'); ?></option>
											<option value="capitalize" <?php selected(isset($clear_button['text_transform']) ? $clear_button['text_transform'] : 'none', 'capitalize'); ?>><?php esc_html_e('Capitalize', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Text Decoration', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_text_decoration">
											<option value="none" <?php selected(isset($clear_button['text_decoration']) ? $clear_button['text_decoration'] : 'underline', 'none'); ?>><?php esc_html_e('None', 'shopglut'); ?></option>
											<option value="underline" <?php selected(isset($clear_button['text_decoration']) ? $clear_button['text_decoration'] : 'underline', 'underline'); ?>><?php esc_html_e('Underline', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Letter Spacing', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_letter_spacing" class="small-text" value="<?php echo esc_attr(isset($clear_button['letter_spacing']) ? $clear_button['letter_spacing'] : 0); ?>" min="0" max="5" step="0.1">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Line Height', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_line_height" class="small-text" value="<?php echo esc_attr(isset($clear_button['line_height']) ? $clear_button['line_height'] : 14); ?>" min="10" max="25">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Text Align', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_text_align">
											<option value="left" <?php selected(isset($clear_button['text_align']) ? $clear_button['text_align'] : 'left', 'left'); ?>><?php esc_html_e('Left', 'shopglut'); ?></option>
											<option value="center" <?php selected(isset($clear_button['text_align']) ? $clear_button['text_align'] : 'left', 'center'); ?>><?php esc_html_e('Center', 'shopglut'); ?></option>
											<option value="right" <?php selected(isset($clear_button['text_align']) ? $clear_button['text_align'] : 'left', 'right'); ?>><?php esc_html_e('Right', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Background Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_clear_button_background_color" value="<?php echo esc_attr(isset($clear_button['background_color']) ? $clear_button['background_color'] : 'transparent'); ?>">
										<p class="description"><?php esc_html_e('Background color of the button', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_clear_button_border_color" value="<?php echo esc_attr(isset($clear_button['border_color']) ? $clear_button['border_color'] : 'transparent'); ?>">
										<p class="description"><?php esc_html_e('Border color', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Width', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_border_width" class="small-text" value="<?php echo esc_attr(isset($clear_button['border_width']) ? $clear_button['border_width'] : 0); ?>" min="0" max="3">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Radius', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_border_radius" class="small-text" value="<?php echo esc_attr(isset($clear_button['border_radius']) ? $clear_button['border_radius'] : 4); ?>" min="0" max="20">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Style', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_clear_button_border_style">
											<option value="solid" <?php selected(isset($clear_button['border_style']) ? $clear_button['border_style'] : 'solid', 'solid'); ?>><?php esc_html_e('Solid', 'shopglut'); ?></option>
											<option value="dashed" <?php selected(isset($clear_button['border_style']) ? $clear_button['border_style'] : 'solid', 'dashed'); ?>><?php esc_html_e('Dashed', 'shopglut'); ?></option>
											<option value="dotted" <?php selected(isset($clear_button['border_style']) ? $clear_button['border_style'] : 'solid', 'dotted'); ?>><?php esc_html_e('Dotted', 'shopglut'); ?></option>
											<option value="double" <?php selected(isset($clear_button['border_style']) ? $clear_button['border_style'] : 'solid', 'double'); ?>><?php esc_html_e('Double', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Padding', 'shopglut'); ?></th>
									<td>
										<div class="shopglut-padding-box-wrapper">
											<div class="shopglut-padding-box">
												<div class="shopglut-padding-field">
													<label for="shopglut_clear_button_padding_top"><?php esc_html_e('Top', 'shopglut'); ?></label>
													<input type="number" id="shopglut_clear_button_padding_top" class="small-text" value="<?php echo esc_attr(isset($clear_button['padding']['top']) ? $clear_button['padding']['top'] : 6); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
												<div class="shopglut-padding-row">
													<div class="shopglut-padding-field">
														<label for="shopglut_clear_button_padding_left"><?php esc_html_e('Left', 'shopglut'); ?></label>
														<input type="number" id="shopglut_clear_button_padding_left" class="small-text" value="<?php echo esc_attr(isset($clear_button['padding']['left']) ? $clear_button['padding']['left'] : 12); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
													<div class="shopglut-padding-field">
														<label for="shopglut_clear_button_padding_right"><?php esc_html_e('Right', 'shopglut'); ?></label>
														<input type="number" id="shopglut_clear_button_padding_right" class="small-text" value="<?php echo esc_attr(isset($clear_button['padding']['right']) ? $clear_button['padding']['right'] : 12); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
												</div>
												<div class="shopglut-padding-field">
													<label for="shopglut_clear_button_padding_bottom"><?php esc_html_e('Bottom', 'shopglut'); ?></label>
													<input type="number" id="shopglut_clear_button_padding_bottom" class="small-text" value="<?php echo esc_attr(isset($clear_button['padding']['bottom']) ? $clear_button['padding']['bottom'] : 6); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Hover Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_clear_button_hover_color" value="<?php echo esc_attr(isset($clear_button['hover_color']) ? $clear_button['hover_color'] : '#135e96'); ?>">
										<p class="description"><?php esc_html_e('Text color on hover', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Hover Background', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_clear_button_hover_background" value="<?php echo esc_attr(isset($clear_button['hover_background']) ? $clear_button['hover_background'] : 'rgba(34, 113, 177, 0.05)'); ?>">
										<p class="description"><?php esc_html_e('Background color on hover', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Transition Duration', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_clear_button_transition_duration" class="small-text" value="<?php echo esc_attr(isset($clear_button['transition_duration']) ? $clear_button['transition_duration'] : 0.2); ?>" min="0" max="2" step="0.1">
										<span class="unit">s</span>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Price Display Settings -->
					<div class="shopglut-setting-card">
						<div class="setting-card-header">
							<div class="setting-icon">
								<span class="dashicons dashicons-money-alt"></span>
							</div>
							<h3><?php esc_html_e('Price Display', 'shopglut'); ?></h3>
						</div>
						<div class="setting-card-content">
							<table class="form-table">
								<tr>
									<th><?php esc_html_e('Show Price', 'shopglut'); ?></th>
									<td>
										<label class="switch">
											<input type="checkbox" id="shopglut_enable_variation_price" value="1" <?php checked(isset($price_display['enable']) ? $price_display['enable'] : true, true); ?>>
											<span class="slider round"></span>
										</label>
										<p class="description"><?php esc_html_e('Display price based on selected variation', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Position', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_display_position">
											<option value="before_clear_button" <?php selected(isset($price_display['position']) ? $price_display['position'] : 'after_clear_button', 'before_clear_button'); ?>><?php esc_html_e('Before Clear Button', 'shopglut'); ?></option>
											<option value="after_clear_button" <?php selected(isset($price_display['position']) ? $price_display['position'] : 'after_clear_button', 'after_clear_button'); ?>><?php esc_html_e('After Clear Button', 'shopglut'); ?></option>
										</select>
										<p class="description"><?php esc_html_e('Where to show the price relative to the clear button', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_price_color" value="<?php echo esc_attr(isset($price_display['color']) ? $price_display['color'] : '#2271b1'); ?>">
										<p class="description"><?php esc_html_e('Price text color', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Size', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_font_size" class="small-text" value="<?php echo esc_attr(isset($price_display['font_size']) ? $price_display['font_size'] : 16); ?>" min="12" max="24">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Weight', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_font_weight">
											<option value="400" <?php selected(isset($price_display['font_weight']) ? $price_display['font_weight'] : '600', '400'); ?>><?php esc_html_e('Normal', 'shopglut'); ?></option>
											<option value="500" <?php selected(isset($price_display['font_weight']) ? $price_display['font_weight'] : '600', '500'); ?>><?php esc_html_e('Medium', 'shopglut'); ?></option>
											<option value="600" <?php selected(isset($price_display['font_weight']) ? $price_display['font_weight'] : '600', '600'); ?>><?php esc_html_e('Semi Bold', 'shopglut'); ?></option>
											<option value="700" <?php selected(isset($price_display['font_weight']) ? $price_display['font_weight'] : '600', '700'); ?>><?php esc_html_e('Bold', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Top Margin', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_margin_top" class="small-text" value="<?php echo esc_attr(isset($price_display['margin_top']) ? $price_display['margin_top'] : 12); ?>" min="0" max="30">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Family', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_font_family">
											<option value="inherit" <?php selected(isset($price_display['font_family']) ? $price_display['font_family'] : 'inherit', 'inherit'); ?>><?php esc_html_e('Inherit', 'shopglut'); ?></option>
											<option value="Arial, sans-serif" <?php selected(isset($price_display['font_family']) ? $price_display['font_family'] : 'inherit', 'Arial, sans-serif'); ?>><?php esc_html_e('Arial', 'shopglut'); ?></option>
											<option value="Georgia, serif" <?php selected(isset($price_display['font_family']) ? $price_display['font_family'] : 'inherit', 'Georgia, serif'); ?>><?php esc_html_e('Georgia', 'shopglut'); ?></option>
											<option value="'Times New Roman', serif" <?php selected(isset($price_display['font_family']) ? $price_display['font_family'] : 'inherit', '"Times New Roman", serif'); ?>><?php esc_html_e('Times New Roman', 'shopglut'); ?></option>
											<option value="Verdana, sans-serif" <?php selected(isset($price_display['font_family']) ? $price_display['font_family'] : 'inherit', 'Verdana, sans-serif'); ?>><?php esc_html_e('Verdana', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Line Height', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_line_height" class="small-text" value="<?php echo esc_attr(isset($price_display['line_height']) ? $price_display['line_height'] : 14); ?>" min="10" max="25">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Text Transform', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_text_transform">
											<option value="none" <?php selected(isset($price_display['text_transform']) ? $price_display['text_transform'] : 'none', 'none'); ?>><?php esc_html_e('None', 'shopglut'); ?></option>
											<option value="uppercase" <?php selected(isset($price_display['text_transform']) ? $price_display['text_transform'] : 'none', 'uppercase'); ?>><?php esc_html_e('Uppercase', 'shopglut'); ?></option>
											<option value="lowercase" <?php selected(isset($price_display['text_transform']) ? $price_display['text_transform'] : 'none', 'lowercase'); ?>><?php esc_html_e('Lowercase', 'shopglut'); ?></option>
											<option value="capitalize" <?php selected(isset($price_display['text_transform']) ? $price_display['text_transform'] : 'none', 'capitalize'); ?>><?php esc_html_e('Capitalize', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Letter Spacing', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_letter_spacing" class="small-text" value="<?php echo esc_attr(isset($price_display['letter_spacing']) ? $price_display['letter_spacing'] : 0); ?>" min="0" max="5" step="0.1">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Text Align', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_text_align">
											<option value="left" <?php selected(isset($price_display['text_align']) ? $price_display['text_align'] : 'left', 'left'); ?>><?php esc_html_e('Left', 'shopglut'); ?></option>
											<option value="center" <?php selected(isset($price_display['text_align']) ? $price_display['text_align'] : 'left', 'center'); ?>><?php esc_html_e('Center', 'shopglut'); ?></option>
											<option value="right" <?php selected(isset($price_display['text_align']) ? $price_display['text_align'] : 'left', 'right'); ?>><?php esc_html_e('Right', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Font Style', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_font_style">
											<option value="normal" <?php selected(isset($price_display['font_style']) ? $price_display['font_style'] : 'normal', 'normal'); ?>><?php esc_html_e('Normal', 'shopglut'); ?></option>
											<option value="italic" <?php selected(isset($price_display['font_style']) ? $price_display['font_style'] : 'normal', 'italic'); ?>><?php esc_html_e('Italic', 'shopglut'); ?></option>
											<option value="oblique" <?php selected(isset($price_display['font_style']) ? $price_display['font_style'] : 'normal', 'oblique'); ?>><?php esc_html_e('Oblique', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Background Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_price_background_color" value="<?php echo esc_attr(isset($price_display['background_color']) ? $price_display['background_color'] : 'transparent'); ?>">
										<p class="description"><?php esc_html_e('Background color of the price', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Color', 'shopglut'); ?></th>
									<td>
										<input type="color" id="shopglut_price_border_color" value="<?php echo esc_attr(isset($price_display['border_color']) ? $price_display['border_color'] : 'transparent'); ?>">
										<p class="description"><?php esc_html_e('Border color', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Width', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_border_width" class="small-text" value="<?php echo esc_attr(isset($price_display['border_width']) ? $price_display['border_width'] : 0); ?>" min="0" max="3">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Radius', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_price_border_radius" class="small-text" value="<?php echo esc_attr(isset($price_display['border_radius']) ? $price_display['border_radius'] : 4); ?>" min="0" max="20">
										<span class="unit">px</span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Border Style', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_price_border_style">
											<option value="solid" <?php selected(isset($price_display['border_style']) ? $price_display['border_style'] : 'solid', 'solid'); ?>><?php esc_html_e('Solid', 'shopglut'); ?></option>
											<option value="dashed" <?php selected(isset($price_display['border_style']) ? $price_display['border_style'] : 'solid', 'dashed'); ?>><?php esc_html_e('Dashed', 'shopglut'); ?></option>
											<option value="dotted" <?php selected(isset($price_display['border_style']) ? $price_display['border_style'] : 'solid', 'dotted'); ?>><?php esc_html_e('Dotted', 'shopglut'); ?></option>
											<option value="double" <?php selected(isset($price_display['border_style']) ? $price_display['border_style'] : 'solid', 'double'); ?>><?php esc_html_e('Double', 'shopglut'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Padding', 'shopglut'); ?></th>
									<td>
										<div class="shopglut-padding-box-wrapper">
											<div class="shopglut-padding-box">
												<div class="shopglut-padding-field">
													<label for="shopglut_price_padding_top"><?php esc_html_e('Top', 'shopglut'); ?></label>
													<input type="number" id="shopglut_price_padding_top" class="small-text" value="<?php echo esc_attr(isset($price_display['padding']['top']) ? $price_display['padding']['top'] : 4); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
												<div class="shopglut-padding-row">
													<div class="shopglut-padding-field">
														<label for="shopglut_price_padding_left"><?php esc_html_e('Left', 'shopglut'); ?></label>
														<input type="number" id="shopglut_price_padding_left" class="small-text" value="<?php echo esc_attr(isset($price_display['padding']['left']) ? $price_display['padding']['left'] : 8); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
													<div class="shopglut-padding-field">
														<label for="shopglut_price_padding_right"><?php esc_html_e('Right', 'shopglut'); ?></label>
														<input type="number" id="shopglut_price_padding_right" class="small-text" value="<?php echo esc_attr(isset($price_display['padding']['right']) ? $price_display['padding']['right'] : 8); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
												</div>
												<div class="shopglut-padding-field">
													<label for="shopglut_price_padding_bottom"><?php esc_html_e('Bottom', 'shopglut'); ?></label>
													<input type="number" id="shopglut_price_padding_bottom" class="small-text" value="<?php echo esc_attr(isset($price_display['padding']['bottom']) ? $price_display['padding']['bottom'] : 4); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Margin', 'shopglut'); ?></th>
									<td>
										<div class="shopglut-padding-box-wrapper">
											<div class="shopglut-padding-box">
												<div class="shopglut-padding-field">
													<label for="shopglut_price_margin_top"><?php esc_html_e('Top', 'shopglut'); ?></label>
													<input type="number" id="shopglut_price_margin_top" class="small-text" value="<?php echo esc_attr(isset($price_display['margin']['top']) ? $price_display['margin']['top'] : (isset($price_display['margin_top']) ? $price_display['margin_top'] : 12)); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
												<div class="shopglut-padding-row">
													<div class="shopglut-padding-field">
														<label for="shopglut_price_margin_left"><?php esc_html_e('Left', 'shopglut'); ?></label>
														<input type="number" id="shopglut_price_margin_left" class="small-text" value="<?php echo esc_attr(isset($price_display['margin']['left']) ? $price_display['margin']['left'] : (isset($price_display['margin_left']) ? $price_display['margin_left'] : 0)); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
													<div class="shopglut-padding-field">
														<label for="shopglut_price_margin_right"><?php esc_html_e('Right', 'shopglut'); ?></label>
														<input type="number" id="shopglut_price_margin_right" class="small-text" value="<?php echo esc_attr(isset($price_display['margin']['right']) ? $price_display['margin']['right'] : (isset($price_display['margin_right']) ? $price_display['margin_right'] : 15)); ?>" min="0" max="30">
														<span class="unit">px</span>
													</div>
												</div>
												<div class="shopglut-padding-field">
													<label for="shopglut_price_margin_bottom"><?php esc_html_e('Bottom', 'shopglut'); ?></label>
													<input type="number" id="shopglut_price_margin_bottom" class="small-text" value="<?php echo esc_attr(isset($price_display['margin']['bottom']) ? $price_display['margin']['bottom'] : (isset($price_display['margin_bottom']) ? $price_display['margin_bottom'] : 0)); ?>" min="0" max="30">
													<span class="unit">px</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Actions Position Settings -->
					<div class="shopglut-setting-card">
						<div class="setting-card-header">
							<div class="setting-icon">
								<span class="dashicons dashicons-editor-alignleft"></span>
							</div>
							<h3><?php esc_html_e('Actions Position', 'shopglut'); ?></h3>
						</div>
						<div class="setting-card-content">
							<table class="form-table">
								<tr>
									<th><?php esc_html_e('Clear Button & Price Position', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_actions_position">
											<option value="same_line" <?php selected(isset($global_swatches_settings['actions_position']) ? $global_swatches_settings['actions_position'] : 'new_line', 'same_line'); ?>><?php esc_html_e('Same Line - Display inline with the last attribute', 'shopglut'); ?></option>
											<option value="new_line" <?php selected(isset($global_swatches_settings['actions_position']) ? $global_swatches_settings['actions_position'] : 'new_line', 'new_line'); ?>><?php esc_html_e('New Line - Display beneath the last attribute', 'shopglut'); ?></option>
										</select>
										<p class="description"><?php esc_html_e('Choose where to display the clear button and price relative to the last attribute', 'shopglut'); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Variations Form Styling Card -->
					<div class="shopglut-setting-card">
						<div class="setting-card-header">
							<div class="setting-icon">
								<span class="dashicons dashicons-table-col-before"></span>
							</div>
							<h3><?php esc_html_e('Variations Form Styling', 'shopglut'); ?></h3>
						</div>
						<div class="setting-card-content">
							<p class="description"><?php esc_html_e('Control the styling of WooCommerce default variations form table (borders, padding, margins).', 'shopglut'); ?></p>
							<table class="form-table">
								<tr>
									<th><?php esc_html_e('Form Container Bottom Margin', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_variations_margin_bottom" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['margin_bottom']) ? $global_swatches_settings['variations_form']['margin_bottom'] : ''); ?>" min="0" max="40" step="1" placeholder="20">
										<span class="unit">px</span>
										<p class="description"><?php esc_html_e('Spacing below the variations form', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Table Cell Padding', 'shopglut'); ?></th>
									<td>
										<div class="shopglut-padding-box-wrapper">
											<div class="shopglut-padding-box">
												<div class="shopglut-padding-field">
													<label for="shopglut_variations_padding_top"><?php esc_html_e('Top', 'shopglut'); ?></label>
													<input type="number" id="shopglut_variations_padding_top" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['padding_top']) ? $global_swatches_settings['variations_form']['padding_top'] : ''); ?>" min="0" max="30" step="1" placeholder="10">
													<span class="unit">px</span>
												</div>
												<div class="shopglut-padding-row">
													<div class="shopglut-padding-field">
														<label for="shopglut_variations_padding_left"><?php esc_html_e('Left', 'shopglut'); ?></label>
														<input type="number" id="shopglut_variations_padding_left" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['padding_left']) ? $global_swatches_settings['variations_form']['padding_left'] : ''); ?>" min="0" max="30" step="1" placeholder="10">
														<span class="unit">px</span>
													</div>
													<div class="shopglut-padding-field">
														<label for="shopglut_variations_padding_right"><?php esc_html_e('Right', 'shopglut'); ?></label>
														<input type="number" id="shopglut_variations_padding_right" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['padding_right']) ? $global_swatches_settings['variations_form']['padding_right'] : ''); ?>" min="0" max="30" step="1" placeholder="10">
														<span class="unit">px</span>
													</div>
												</div>
												<div class="shopglut-padding-field">
													<label for="shopglut_variations_padding_bottom"><?php esc_html_e('Bottom', 'shopglut'); ?></label>
													<input type="number" id="shopglut_variations_padding_bottom" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['padding_bottom']) ? $global_swatches_settings['variations_form']['padding_bottom'] : ''); ?>" min="0" max="30" step="1" placeholder="10">
													<span class="unit">px</span>
												</div>
											</div>
										</div>
										<p class="description"><?php esc_html_e('Set padding for table cells. Leave empty to use WooCommerce defaults.', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Row Height', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_variations_row_height" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['row_height']) ? $global_swatches_settings['variations_form']['row_height'] : ''); ?>" min="0" max="100" step="0.1" placeholder="2">
										<span class="unit">em</span>
										<p class="description"><?php esc_html_e('Line-height for table rows. Default: 2em', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Remove Borders', 'shopglut'); ?></th>
									<td>
										<label class="switch">
											<input type="checkbox" id="shopglut_variations_remove_borders" value="1" <?php checked(isset($global_swatches_settings['variations_form']['remove_borders']) ? $global_swatches_settings['variations_form']['remove_borders'] : false, true); ?>>
											<span class="slider round"></span>
										</label>
										<span class="switch-label"><?php esc_html_e('Enable to remove all borders from the variations table', 'shopglut'); ?></span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Vertical Align', 'shopglut'); ?></th>
									<td>
										<select id="shopglut_variations_vertical_align">
											<option value="" <?php selected(isset($global_swatches_settings['variations_form']['vertical_align']) ? $global_swatches_settings['variations_form']['vertical_align'] : '', ''); ?>><?php esc_html_e('Default (Middle)', 'shopglut'); ?></option>
											<option value="top" <?php selected(isset($global_swatches_settings['variations_form']['vertical_align']) ? $global_swatches_settings['variations_form']['vertical_align'] : '', 'top'); ?>><?php esc_html_e('Top', 'shopglut'); ?></option>
											<option value="middle" <?php selected(isset($global_swatches_settings['variations_form']['vertical_align']) ? $global_swatches_settings['variations_form']['vertical_align'] : '', 'middle'); ?>><?php esc_html_e('Middle', 'shopglut'); ?></option>
											<option value="bottom" <?php selected(isset($global_swatches_settings['variations_form']['vertical_align']) ? $global_swatches_settings['variations_form']['vertical_align'] : '', 'bottom'); ?>><?php esc_html_e('Bottom', 'shopglut'); ?></option>
										</select>
										<p class="description"><?php esc_html_e('Vertical alignment of content in table cells', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Table Cell Padding Bottom', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_variations_cell_padding_bottom" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['cell_padding_bottom']) ? $global_swatches_settings['variations_form']['cell_padding_bottom'] : ''); ?>" min="0" max="50" step="1" placeholder="">
										<span class="unit">px</span>
										<p class="description"><?php esc_html_e('Override padding-bottom for table cells (td, th). Leave empty to use WooCommerce default (var(--wp--style--block-gap)).', 'shopglut'); ?></p>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e('Form Margin Bottom', 'shopglut'); ?></th>
									<td>
										<input type="number" id="shopglut_variations_form_margin_bottom" class="small-text" value="<?php echo esc_attr(isset($global_swatches_settings['variations_form']['form_margin_bottom']) ? $global_swatches_settings['variations_form']['form_margin_bottom'] : ''); ?>" min="0" max="100" step="1" placeholder="">
										<span class="unit">px</span>
										<p class="description"><?php esc_html_e('Override margin-bottom for the variations form (div.product form.cart .variations). Leave empty to use WooCommerce default (1em).', 'shopglut'); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Pro Features Card -->
					<div class="shopglut-setting-card shopglut-pro-card">
						<div class="setting-card-header">
							<div class="setting-icon pro-icon">
								<span class="dashicons dashicons-star-filled"></span>
							</div>
							<h3><?php esc_html_e('Pro Features', 'shopglut'); ?></h3>
							<span class="pro-badge">PRO</span>
						</div>
						<div class="setting-card-content">
							<div class="pro-feature-item">
								<div class="pro-feature-icon">
									<span class="dashicons dashicons-products"></span>
								</div>
								<div class="pro-feature-text">
									<h4><?php esc_html_e('Per-Product Settings', 'shopglut'); ?></h4>
									<p><?php esc_html_e('Override swatch settings individually for each product. Choose different templates, colors, and styles for specific products.', 'shopglut'); ?></p>
								</div>
								<div class="pro-feature-action">
									<button type="button" class="button button-secondary" disabled>
										<?php esc_html_e('Coming Soon', 'shopglut'); ?>
									</button>
								</div>
							</div>
							<div class="pro-feature-item">
								<div class="pro-feature-icon">
									<span class="dashicons dashicons-admin-appearance"></span>
								</div>
								<div class="pro-feature-text">
									<h4><?php esc_html_e('Advanced Styling', 'shopglut'); ?></h4>
									<p><?php esc_html_e('Custom CSS, hover animations, tooltips, and more advanced styling options for your swatches.', 'shopglut'); ?></p>
								</div>
								<div class="pro-feature-action">
									<button type="button" class="button button-secondary" disabled>
										<?php esc_html_e('Coming Soon', 'shopglut'); ?>
									</button>
								</div>
							</div>
							<div class="pro-feature-item">
								<div class="pro-feature-icon">
									<span class="dashicons dashicons-clipboard"></span>
								</div>
								<div class="pro-feature-text">
									<h4><?php esc_html_e('Import/Export Settings', 'shopglut'); ?></h4>
									<p><?php esc_html_e('Backup your swatch configurations and import them to other sites.', 'shopglut'); ?></p>
								</div>
								<div class="pro-feature-action">
									<button type="button" class="button button-secondary" disabled>
										<?php esc_html_e('Coming Soon', 'shopglut'); ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Save and Reset Buttons -->
				<div class="shopglut-settings-actions">
					<button type="button" id="save-global-settings" class="button button-primary">
						<span class="dashicons dashicons-saved"></span>
						<?php esc_html_e('Save Global Settings', 'shopglut'); ?>
					</button>
					<button type="button" id="reset-global-settings" class="button">
						<span class="dashicons dashicons-undo"></span>
						<?php esc_html_e('Reset Settings', 'shopglut'); ?>
					</button>
					<span class="save-spinner" style="display: none;"></span>
					<span class="save-message"></span>
				</div>
			</div>
		</div>

		<style>
			.wrap.shopglut-admin-contents > h1 {
				text-align: center;
				margin-bottom: 10px;
			}

			.wrap.shopglut-admin-contents > .description {
				text-align: center;
				margin-bottom: 20px;
			}

			.shopglut-attributes-grid {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
				gap: 20px;
				margin-top: 40px;
			}

			.shopglut-attribute-card {
				width: 350px;
				flex-shrink: 0;
				background: #fff;
				border: 2px solid #e0e0e0;
				border-radius: 8px;
				padding: 20px;
				transition: all 0.3s ease;
			}

			.shopglut-attribute-card:hover {
				border-color: #2271b1;
				box-shadow: 0 4px 12px rgba(0,0,0,0.1);
				transform: translateY(-2px);
			}

			.shopglut-attribute-card.configured {
				border-color: #2271b1;
				background: linear-gradient(135deg, #f9f9f9 0%, #f0f6fc 100%);
			}

			.attribute-card-header {
				display: flex;
				align-items: flex-start;
				gap: 15px;
				margin-bottom: 15px;
				padding-bottom: 15px;
				border-bottom: 1px solid #eee;
			}

			.attribute-icon {
				width: 50px;
				height: 50px;
				background: #f0f0f1;
				border-radius: 8px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
			}

			.attribute-icon .dashicons {
				font-size: 28px;
				width: 28px;
				height: 28px;
				color: #2271b1;
			}

			.configured .attribute-icon .dashicons {
				color: #2271b1;
			}

			.attribute-info {
				flex: 1;
			}

			.attribute-info h3 {
				margin: 0 0 5px 0;
				font-size: 16px;
				font-weight: 600;
				color: #1d2327;
			}

			.attribute-taxonomy {
				display: block;
				font-size: 12px;
				color: #646970;
				font-family: monospace;
				margin-bottom: 3px;
			}

			.attribute-type {
				display: inline-block;
				font-size: 11px;
				background: #e0e0e0;
				color: #50575e;
				padding: 2px 8px;
				border-radius: 3px;
				text-transform: uppercase;
			}

			.attribute-status {
				text-align: right;
				flex-shrink: 0;
			}

			.status-badge {
				display: inline-flex;
				align-items: center;
				gap: 5px;
				padding: 5px 10px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 600;
			}

			.status-badge.configured {
				background: #e7f3ff;
				color: #2271b1;
			}

			.status-badge.not-configured {
				background: #f0f0f1;
				color: #646970;
			}

			.template-badge {
				display: block;
				margin-top: 5px;
				font-size: 11px;
				background: #e7f3ff;
				color: #2271b1;
				padding: 3px 8px;
				border-radius: 3px;
			}

			.attribute-card-actions {
				display: flex;
				gap: 10px;
			}

			.attribute-card-actions .button {
				flex: 1;
				text-align: center;
				justify-content: center;
				display: inline-flex;
				align-items: center;
				gap: 5px;
			}

			.shopglut-empty-state {
				grid-column: 1 / -1;
				text-align: center;
				padding: 60px 20px;
				background: #fff;
				border: 2px dashed #c3c4c7;
				border-radius: 8px;
			}

			.empty-state-icon {
				font-size: 64px;
				margin-bottom: 20px;
				opacity: 0.5;
			}

			.shopglut-empty-state h3 {
				margin: 0 0 10px 0;
				font-size: 20px;
				color: #1d2327;
			}

			.shopglut-empty-state p {
				margin: 0 0 20px 0;
				color: #646970;
			}

			/* Global Settings Section */
			.shopglut-global-settings-section {
				margin-top: 60px;
				padding-top: 40px;
				border-top: 1px solid #c3c4c7;
			}

			.shopglut-global-settings-section > h2 {
				text-align: center;
				margin-bottom: 15px;
				font-size:24px;
			}

			.shopglut-global-settings-section > .description {
				text-align: center;
				margin-bottom: 30px;
				color: #646970;
			}

			.shopglut-settings-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
				gap: 25px;
				margin-bottom: 30px;
			}

			.shopglut-setting-card {
				background: #fff;
				border: 1px solid #c3c4c7;
				border-radius: 8px;
				padding: 20px;
				box-shadow: 0 1px 3px rgba(0,0,0,0.05);
			}

			.setting-card-header {
				display: flex;
				align-items: center;
				gap: 12px;
				margin-bottom: 20px;
				padding-bottom: 15px;
				border-bottom: 1px solid #e0e0e0;
			}

			.setting-icon {
				width: 40px;
				height: 40px;
				background: #f0f6fc;
				border-radius: 6px;
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.setting-icon .dashicons {
				font-size: 22px;
				width: 22px;
				height: 22px;
				color: #2271b1;
			}

			.setting-card-header h3 {
				margin: 0;
				font-size: 16px;
				font-weight: 600;
				color: #1d2327;
			}

			.shopglut-setting-card .form-table {
				margin: 0;
			}

			.shopglut-setting-card .form-table th {
				padding: 12px 10px;
				width: 200px;
				font-weight: 500;
				color: #1d2327;
			}

			.shopglut-setting-card .form-table td {
				padding: 12px 10px;
			}

			.shopglut-setting-card input[type="color"] {
				width: 50px;
				height: 35px;
				cursor: pointer;
			}

			.shopglut-setting-card .small-text {
				width: 70px;
				padding: 6px 8px;
			}

			.shopglut-setting-card .unit {
				margin-left: 5px;
				color: #646970;
				font-size: 13px;
			}

			.shopglut-setting-card .description {
				margin-top: 5px;
				font-size: 12px;
				color: #646970;
				font-style: italic;
			}

			/* Toggle Switch */
			.switch {
				position: relative;
				display: inline-block;
				width: 50px;
				height: 24px;
			}

			.switch input {
				opacity: 0;
				width: 0;
				height: 0;
			}

			.slider {
				position: absolute;
				cursor: pointer;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: #ccc;
				transition: .4s;
				border-radius: 24px;
			}

			.slider:before {
				position: absolute;
				content: "";
				height: 18px;
				width: 18px;
				left: 3px;
				bottom: 3px;
				background-color: white;
				transition: .4s;
				border-radius: 50%;
			}

			input:checked + .slider {
				background-color: #2271b1;
			}

			input:checked + .slider:before {
				transform: translateX(26px);
			}

			/* Pro Card */
			.shopglut-pro-card {
				background: linear-gradient(135deg, #f9f9f9 0%, #fff9e6 100%);
				border-color: #d6b860;
			}

			.pro-icon {
				background: #fff3cd;
			}

			.pro-icon .dashicons {
				color: #d6b860;
			}

			.pro-badge {
				display: inline-block;
				background: #d6b860;
				color: #fff;
				font-size: 10px;
				font-weight: 700;
				padding: 2px 8px;
				border-radius: 3px;
				margin-left: auto;
			}

			.pro-feature-item {
				display: flex;
				align-items: flex-start;
				gap: 15px;
				padding: 15px 0;
				border-bottom: 1px solid #f0f0f0;
			}

			.pro-feature-item:last-child {
				border-bottom: none;
			}

			.pro-feature-icon {
				width: 32px;
				height: 32px;
				background: #fff3cd;
				border-radius: 6px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
			}

			.pro-feature-icon .dashicons {
				font-size: 18px;
				width: 18px;
				height: 18px;
				color: #d6b860;
			}

			.pro-feature-text {
				flex: 1;
			}

			.pro-feature-text h4 {
				margin: 0 0 5px 0;
				font-size: 14px;
				font-weight: 600;
				color: #1d2327;
			}

			.pro-feature-text p {
				margin: 0;
				font-size: 13px;
				color: #646970;
				line-height: 1.5;
			}

			.pro-feature-action .button {
				padding: 4px 12px;
				font-size: 12px;
				height: auto;
				line-height: 1.8;
			}

			/* Save Actions */
			.shopglut-settings-actions {
				text-align: center;
				padding: 20px 0;
			}

			.shopglut-settings-actions .button {
				padding: 10px 25px;
				font-size: 15px;
				height: auto;
				margin-right: 10px;
			}

			.shopglut-settings-actions .button:last-child {
				margin-right: 0;
			}

			.shopglut-settings-actions .button .dashicons {
				vertical-align: middle;
				margin-top: -2px;
			}

			.save-spinner {
				display: inline-block;
				width: 16px;
				height: 16px;
				border: 2px solid #f3f3f3;
				border-top: 2px solid #2271b1;
				border-radius: 50%;
				animation: spin 1s linear infinite;
				margin-left: 10px;
				vertical-align: middle;
			}

			.save-message {
				margin-left: 15px;
				font-weight: 500;
			}

			.save-message.success {
				color: #00a32a;
			}

			.save-message.error {
				color: #d63638;
			}

			/* Switch Label */
			.switch-label {
				margin-left: 10px;
				font-size: 13px;
				color: #646970;
			}

			/* Padding Box Wrapper */
			.shopglut-padding-box-wrapper {
				display: inline-block;
			}

			.shopglut-padding-box {
				display: grid;
				grid-template-columns: 60px 60px 60px;
				grid-template-rows: 55px 55px;
				gap: 8px;
				align-items: center;
				justify-items: center;
				background: #f6f7f7;
				border: 1px solid #dcdcde;
				border-radius: 8px;
				padding: 12px;
			}

			.shopglut-padding-field {
				display: flex;
				flex-direction: column;
				align-items: center;
				gap: 4px;
				text-align: center;
			}

			.shopglut-padding-field label {
				font-size: 11px;
				font-weight: 500;
				color: #646970;
				margin: 0;
			}

			.shopglut-padding-field input {
				width: 50px !important;
				padding: 4px 6px !important;
				text-align: center;
				font-size: 12px;
			}

			.shopglut-padding-field .unit {
				font-size: 10px;
				color: #a7aaad;
				margin: 0;
			}

			.shopglut-padding-row {
				grid-column: 2 / 4;
				display: flex;
				gap: 8px;
				justify-content: center;
			}

			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}
		</style>

		<script>
		jQuery(document).ready(function($) {
			// Handle reset layout button
			$(document).on('click', '.shopglut-reset-layout', function(e) {
				e.preventDefault();

				var $button = $(this);
				var layoutId = $button.data('layout-id');
				var attribute = $button.data('attribute');
				var nonce = $button.data('nonce');
				var $card = $button.closest('.shopglut-attribute-card');

				if (!confirm('<?php esc_html_e('Are you sure you want to reset this layout? This will remove the template assignment for this attribute.', 'shopglut'); ?>')) {
					return;
				}

				$button.prop('disabled', true);

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'shopglut_reset_attribute_layout',
						layout_id: layoutId,
						attribute: attribute,
						nonce: nonce
					},
					success: function(response) {
						if (response.success) {
							// Reload the page to show updated state
							window.location.reload();
						} else {
							alert(response.data.message || '<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
							$button.prop('disabled', false);
						}
					},
					error: function() {
						alert('<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
						$button.prop('disabled', false);
					}
				});
			});

			// Handle global settings save
			$('#save-global-settings').on('click', function() {
				var $button = $(this);
				var $spinner = $('.save-spinner');
				var $message = $('.save-message');

				$button.prop('disabled', true);
				$spinner.show();
				$message.removeClass('success error').text('');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'shopglut_save_global_swatches_settings',
						// Clear Button - Basic
						clear_button_enable: $('#shopglut_enable_clear_button').is(':checked') ? 1 : 0,
						clear_button_text: $('#shopglut_clear_button_text').val(),
						clear_button_color: $('#shopglut_clear_button_color').val(),
						clear_button_font_size: $('#shopglut_clear_button_font_size').val(),
						clear_button_margin_left: $('#shopglut_clear_button_margin_left').val(),
						// Clear Button - Typography
						clear_button_font_family: $('#shopglut_clear_button_font_family').val(),
						clear_button_font_weight: $('#shopglut_clear_button_font_weight').val(),
						clear_button_text_transform: $('#shopglut_clear_button_text_transform').val(),
						clear_button_text_decoration: $('#shopglut_clear_button_text_decoration').val(),
						clear_button_letter_spacing: $('#shopglut_clear_button_letter_spacing').val(),
						clear_button_line_height: $('#shopglut_clear_button_line_height').val(),
						clear_button_text_align: $('#shopglut_clear_button_text_align').val(),
						// Clear Button - Background & Border
						clear_button_background_color: $('#shopglut_clear_button_background_color').val(),
						clear_button_border_color: $('#shopglut_clear_button_border_color').val(),
						clear_button_border_width: $('#shopglut_clear_button_border_width').val(),
						clear_button_border_radius: $('#shopglut_clear_button_border_radius').val(),
						clear_button_border_style: $('#shopglut_clear_button_border_style').val(),
						// Clear Button - Padding
						clear_button_padding_top: $('#shopglut_clear_button_padding_top').val(),
						clear_button_padding_right: $('#shopglut_clear_button_padding_right').val(),
						clear_button_padding_bottom: $('#shopglut_clear_button_padding_bottom').val(),
						clear_button_padding_left: $('#shopglut_clear_button_padding_left').val(),
						// Clear Button - Margins
						clear_button_margin_right: $('#shopglut_clear_button_margin_right').val(),
						clear_button_margin_top: $('#shopglut_clear_button_margin_top').val(),
						clear_button_margin_bottom: $('#shopglut_clear_button_margin_bottom').val(),
						// Clear Button - Hover & Transition
						clear_button_hover_color: $('#shopglut_clear_button_hover_color').val(),
						clear_button_hover_background: $('#shopglut_clear_button_hover_background').val(),
						clear_button_transition_duration: $('#shopglut_clear_button_transition_duration').val(),
						// Price Display - Basic
						price_enable: $('#shopglut_enable_variation_price').is(':checked') ? 1 : 0,
						price_position: $('#shopglut_price_display_position').val(),
						price_color: $('#shopglut_price_color').val(),
						price_font_size: $('#shopglut_price_font_size').val(),
						price_font_weight: $('#shopglut_price_font_weight').val(),
						price_margin_top: $('#shopglut_price_margin_top').val(),
						// Price Display - Typography
						price_font_family: $('#shopglut_price_font_family').val(),
						price_line_height: $('#shopglut_price_line_height').val(),
						price_text_transform: $('#shopglut_price_text_transform').val(),
						price_letter_spacing: $('#shopglut_price_letter_spacing').val(),
						price_text_align: $('#shopglut_price_text_align').val(),
						price_font_style: $('#shopglut_price_font_style').val(),
						// Price Display - Background & Border
						price_background_color: $('#shopglut_price_background_color').val(),
						price_border_color: $('#shopglut_price_border_color').val(),
						price_border_width: $('#shopglut_price_border_width').val(),
						price_border_radius: $('#shopglut_price_border_radius').val(),
						price_border_style: $('#shopglut_price_border_style').val(),
						// Price Display - Padding
						price_padding_top: $('#shopglut_price_padding_top').val(),
						price_padding_right: $('#shopglut_price_padding_right').val(),
						price_padding_bottom: $('#shopglut_price_padding_bottom').val(),
						price_padding_left: $('#shopglut_price_padding_left').val(),
						// Price Display - Margins
						price_margin_left: $('#shopglut_price_margin_left').val(),
						price_margin_right: $('#shopglut_price_margin_right').val(),
						price_margin_bottom: $('#shopglut_price_margin_bottom').val(),
						// Actions Position
						actions_position: $('#shopglut_actions_position').val(),
						// Variations form styling
						variations_margin_bottom: $('#shopglut_variations_margin_bottom').val(),
						variations_padding_top: $('#shopglut_variations_padding_top').val(),
						variations_padding_right: $('#shopglut_variations_padding_right').val(),
						variations_padding_bottom: $('#shopglut_variations_padding_bottom').val(),
						variations_padding_left: $('#shopglut_variations_padding_left').val(),
						variations_row_height: $('#shopglut_variations_row_height').val(),
						variations_remove_borders: $('#shopglut_variations_remove_borders').is(':checked') ? 1 : 0,
						variations_vertical_align: $('#shopglut_variations_vertical_align').val(),
						variations_cell_padding_bottom: $('#shopglut_variations_cell_padding_bottom').val(),
						variations_form_margin_bottom: $('#shopglut_variations_form_margin_bottom').val(),
						nonce: '<?php echo wp_create_nonce('shopglut_global_settings'); ?>'
					},
					success: function(response) {
						$button.prop('disabled', false);
						$spinner.hide();

						if (response.success) {
							$message.addClass('success').text('<?php esc_html_e('Settings saved successfully!', 'shopglut'); ?>');
							setTimeout(function() {
								$message.fadeOut();
							}, 3000);
						} else {
							$message.addClass('error').text(response.data.message || '<?php esc_html_e('Error saving settings.', 'shopglut'); ?>');
						}
					},
					error: function() {
						$button.prop('disabled', false);
						$spinner.hide();
						$message.addClass('error').text('<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
					}
				});
			});

			// Initialize color pickers for global settings
			if ($.fn.wpColorPicker) {
				$('#shopglut_clear_button_color, #shopglut_clear_button_background_color, #shopglut_clear_button_border_color, #shopglut_clear_button_hover_color, #shopglut_clear_button_hover_background, #shopglut_price_color, #shopglut_price_background_color, #shopglut_price_border_color').wpColorPicker();
			}

			// Handle reset settings button
			$('#reset-global-settings').on('click', function() {
				if (!confirm('<?php esc_html_e('Are you sure you want to reset all settings to default values? This action cannot be undone.', 'shopglut'); ?>')) {
					return;
				}

				var $button = $(this);
				var $spinner = $('.save-spinner');
				var $message = $('.save-message');

				$button.prop('disabled', true);
				$spinner.show();
				$message.removeClass('success error').text('');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'shopglut_reset_global_swatches_settings',
						nonce: '<?php echo wp_create_nonce('shopglut_global_settings'); ?>'
					},
					success: function(response) {
						$button.prop('disabled', false);
						$spinner.hide();

						if (response.success) {
							$message.addClass('success').text('<?php esc_html_e('Settings reset to defaults successfully! Reloading...', 'shopglut'); ?>');
							setTimeout(function() {
								location.reload();
							}, 1500);
						} else {
							$message.addClass('error').text(response.data.message || '<?php esc_html_e('Error resetting settings.', 'shopglut'); ?>');
						}
					},
					error: function() {
						$button.prop('disabled', false);
						$spinner.hide();
						$message.addClass('error').text('<?php esc_html_e('An error occurred.', 'shopglut'); ?>');
					}
				});
			});
		});
		</script>
		<?php
	}

	public function renderSwatchesTemplates( $attribute = '' ) {
		$active_menu = 'product_swatches';
		$this->settingsPageHeader( $active_menu );

		// Get attribute label if attribute is provided
		$attribute_label = '';
		if ( ! empty( $attribute ) ) {
			// Get attribute taxonomies
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			// Extract attribute name from taxonomy (remove 'pa_' prefix)
			$attr_name = str_replace( 'pa_', '', $attribute );

			// Find matching attribute
			foreach ( $attribute_taxonomies as $attr ) {
				if ( $attr->attribute_name === $attr_name ) {
					$attribute_label = $attr->attribute_label;
					break;
				}
			}

			// Fallback to taxonomy name if label not found
			if ( empty( $attribute_label ) ) {
				$attribute_label = ucwords( str_replace( '_', ' ', $attr_name ) );
			}
		}

		$swatches_templates = new SwatchesChooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<div class="shopglut-templates-header">
				<h1>
					<?php
					if ( ! empty( $attribute_label ) ) {
						printf( esc_html__( 'Choose Template for: %s', 'shopglut' ), esc_html( $attribute_label ) );
					} else {
						esc_html_e( 'PreBuilt Swatches Templates', 'shopglut' );
					}
					?>
				</h1>
				<?php if ( ! empty( $attribute_label ) ) : ?>
					<p class="subheading">
						<?php
						printf( esc_html__( 'Select a template to customize the %s attribute display', 'shopglut' ), esc_html( $attribute_label ) );
						?>
					</p>
				<?php else : ?>
					<p class="subheading"><?php esc_html_e( 'Choose your desired template to customize', 'shopglut' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php $swatches_templates->loadProductSwatchesTemplates( $attribute );
	}

	public function renderAttributeSwatches() {
		$active_menu = 'product_swatches';
		$this->settingsPageHeader( $active_menu );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Check if product_swatches module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'product_swatches' ) ) {
			$module_manager->render_disabled_module_message( 'product_swatches' );
			return;
		}

		// Initialize and render the attribute swatches manager
		$attribute_manager = AttributeSwatchesManager::get_instance();
		$attribute_manager->render_manager_page();
	}

	public function renderProductComparison() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// $active_menu = $this->activeMenuTab();
		// $this->settingsPageHeader( $active_menu );

		//if($this->not_implemented):
		//	$this->renderNotImplementedMessage();
		// else: 
		// Check if product_comparison module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'product_comparison' ) ) {
			$module_manager->render_disabled_module_message( 'product_comparison' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				ProductComparisonEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparisons&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new ProductComparisonListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Product Comparison Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_comparison_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php //endif;
	}

	public function renderQuickView() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );

		if($this->not_implemented):
			$this->renderNotImplementedMessage();
		 else: 

		// Check if quick_views module is enabled
		$module_manager = \Shopglut\ModuleManager::get_instance();
		if ( ! $module_manager->is_module_enabled( 'quick_views' ) ) {
			$module_manager->render_disabled_module_message( 'quick_views' );
			return;
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				QuickViewEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_safe_redirect( admin_url( 'admin.php?page=shopglut_enhancements&view=product_quickviews&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new QuickViewListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php echo esc_html__( 'Quick View Layouts', 'shopglut' ); ?><a
					href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_quick_view_templates' ) ); ?>"><span
						class="add-new-h2"><?php echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php $layouts_table->display(); ?>
			</form>
		</div>
		<?php endif;
	}

	public function renderProductComparisonTemplates() {
		$active_menu = 'product_comparisons';
		$this->settingsPageHeader( $active_menu );
		$comparisonLayout_templates = new ComparisonTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Comparison Layout Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $comparisonLayout_templates->loadProductComparisonTemplates();
	}

	public function renderQuickViewTemplates() {
		$active_menu = 'product_quickviews';
		$this->settingsPageHeader( $active_menu );
		$quickviewLayout_templates = new QuickViewTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Quick View Layout Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $quickviewLayout_templates->loadProductQuickviewTemplates();
	}

	public function renderBadgeTemplates() {
		$active_menu = 'product_badges';
		$this->settingsPageHeader( $active_menu );
		$badge_templates = new BadgechooseTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt Badge Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $badge_templates->loadProductBadgeTemplates();
	}

	public function handleCreateBadge() {
		if (
			isset( $_POST['create_badge_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['create_badge_nonce'] ) ), 'create_badge_nonce' ) &&
			current_user_can( 'manage_options' )
		) {
			// Get next available ID
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';
			$max_id_sql = $wpdb->prepare( "SELECT MAX(id) FROM `%s`", $table_name );
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Table name prepared, no user input
			$max_id = intval( $wpdb->get_var( $max_id_sql ) );
			$badge_id = $max_id ? $max_id + 1 : 1;

			$badge_name = sanitize_text_field( 'Badge(#' . $badge_id . ')' );

			// Create default badge data
			$default_badge_data = json_encode(array(
				'text' => 'New Badge',
				'style' => array(
					'background_color' => '#ff0000',
					'text_color' => '#fff',
					'font_size' => 12,
					'border_radius' => 3,
					'padding' => '5px 10px',
					'position' => 'top-left'
				)
			));

			// Create badge using direct database query
			global $wpdb;
			$table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

			$badge_settings = array(
				'shopg_product_badge_settings' => $default_badge_data
			);

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
			$result = $wpdb->insert(
				$table_name,
				array(
					'layout_name' => $badge_name,
					'layout_template' => 'template1',
					'layout_settings' => serialize($badge_settings),
					'created_at' => current_time('mysql')
				),
				array('%s', '%s', '%s', '%s')
			);

			$new_badge_id = $result ? $wpdb->insert_id : false;

			if ( $new_badge_id ) {
				$redirect_url = admin_url( 'admin.php?page=shopglut_enhancements&editor=product_badges&badge_id=' . $new_badge_id );
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				wp_die( esc_html__( 'Database insertion error', 'shopglut' ) );
			}
		} else {
			wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
		}
	}


	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}