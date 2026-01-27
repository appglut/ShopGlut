<?php
namespace Shopglut;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\layouts\AllLayouts;
use Shopglut\layouts\singleProduct\SingleProductListTable;
use Shopglut\layouts\cartPage\CartPageListTable;
use Shopglut\layouts\orderCompletePage\orderCompleteListTable;

use Shopglut\layouts\accountPage\AccountPageListTable;

use Shopglut\layouts\shopLayout\ShopListTable;

use Shopglut\shortcodeShowcase\ShortcodeShowcase;

// use Shopglut\showCase\AllShowcases;
// use Shopglut\showCase\Filters\FilterListTable;
// use Shopglut\showCase\Badges\BadgeListTable;
// use Shopglut\showCase\Banners\BannerListTable;
// use Shopglut\showCase\Tabs\TabsListTable;


use Shopglut\enhancements\wishlist\WishlistMenuHandler; // Import the new wishlist handler
use Shopglut\BusinessSolutions\PdfInvoices\PdfInvoicesHandler; // Import PDF Invoices handler
use Shopglut\BusinessSolutions\PdfInvoices\PdfInvoicesMenuHandler; // Import PDF Invoices menu handler
use Shopglut\BusinessSolutions\AllBusinessSolutions; // Import Business Solutions handler
use Shopglut\BusinessSolutions\EmailCustomizer\EmailCustomizer; // Import Email Customizer handler
use Shopglut\ModuleManager; // Import Module Manager

use  Shopglut\tools\AllTools;

use Shopglut\tools\productCustomField\ProductCustomFieldListTable;

use Shopglut\showcases\Sliders\SliderListTable;

use Shopglut\showcases\Tabs\TabListTable;

use Shopglut\showcases\Accordions\AccordionListTable;


use  Shopglut\enhancements\AllEnhancements;

use Shopglut\enhancements\Filters\FilterListTable;

use  Shopglut\showcases\AllShowcases;


class ShopGlutRegisterMenu {

	private $menu_slug = 'shopglut_layouts';
	private $menue_slug = 'shopglut_enhancements';
	private $menut_slug = 'shopglut_tools';
	private $menus_slug = 'shopglut_showcases';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'shopglutMenuRegister' ) );
		add_action( 'load-toplevel_page_shopglut_layouts', array( $this, 'shopglutLayoutsScreenOptions' ) );
		add_action( 'load-shopglut_page_shopglut_tools', array( $this, 'shopglutToolsScreenOptions' ) );
		add_action( 'load-shopglut_page_shopglut_showcases', array( $this, 'shopglutShowcasesScreenOptions' ) );
		add_action( 'load-shopglut_page_shopglut_enhancements', array( $this, 'shopglutEnhancementsScreenOptions' ) );


	}

	public function shopglutMenuRegister() {

		$shopg_menu = new AllLayouts();
		$shopt_menu = new AllTools();
		$shope_menu = new AllEnhancements();
		$shops_menu = new AllShowcases();
		$business_solutions = new AllBusinessSolutions();
		$email_customizer = new EmailCustomizer();
		$module_manager = ModuleManager::get_instance();

        $shopg_checkout_fields = new \Shopglut\layouts\checkoutPage\CheckoutFieldsManager();

		

		add_menu_page(
			esc_html__( 'ShopGlut', 'shopglut' ),
			esc_html__( 'ShopGlut', 'shopglut' ),
			'manage_options',
			$this->menu_slug,
			array( $shopg_menu, 'renderLayoutsPages' ),
			$this->getMenuIcon(),
			55.42
		);

		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'Builder Modules', 'shopglut' ),
			esc_html__( 'Builder Modules', 'shopglut' ),
			'manage_options',
			'shopg_woocommerce_builder',
			array( $shopg_menu, 'renderLayoutsPages' )
		);

		// Add a submenu item
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'WooCommerce Layouts', 'shopglut' ),
			esc_html__( 'WooCommerce Layouts', 'shopglut' ),
			'manage_options',
			$this->menu_slug,
			array( $shopg_menu, 'renderLayoutsPages' )
        );

        // Add checkout submenu only if module is enabled
        // if ( $module_manager->is_module_enabled('checkout_page') ) {
        //     add_submenu_page(
        //         $this->menu_slug,
        //         esc_html__( 'Checkout Editor', 'shopglut' ),
        //         esc_html__( '- Checkout Editor', 'shopglut' ),
        //         'manage_options',
        //         'shopglut_checkout_fields',
        //         array( $shopg_checkout_fields, 'renderAdminPage' )
        //     );
        // }

        // Add a submenu item
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'Woo Enhancements', 'shopglut' ),
			esc_html__( 'Woo Enhancements', 'shopglut' ),
			'manage_options',
			$this->menue_slug,
			array( $shope_menu, 'renderenhancementsPages' )
        );

        // Add wishlist submenu only if module is enabled
        if ( $module_manager->is_module_enabled('wishlist') ) {
            add_submenu_page(
                $this->menu_slug,
                esc_html__( 'ShopGlut Wishlist', 'shopglut' ),
                esc_html__( '- ShopGlut Wishlist', 'shopglut' ),
                'manage_options',
                'shopglut_wishlist',
                array( $this, 'renderWishlistMain' )  // This will delegate to the handler
            );
        }

      
         // Add a submenu item
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'WooCommerce Tools', 'shopglut' ),
			esc_html__( 'WooCommerce Tools', 'shopglut' ),
			'manage_options',
			$this->menut_slug,
			array( $shopt_menu, 'rendertoolsPages' )
        );

        // Add Woo Management submenu only if PosGlut is active
        if ( $this->is_posglut_active() ) {
            add_submenu_page(
                $this->menu_slug,
                esc_html__( 'Woo Management', 'shopglut' ),
                esc_html__( 'Woo Management', 'shopglut' ),
                'manage_options',
                'shopglut_management',
                array( $this, 'renderWooManagement' )
            );
        }


         // Add a submenu item
		// add_submenu_page(
		// 	$this->menu_slug,
		// 	esc_html__( 'Woo Showcases', 'shopglut' ),
		// 	esc_html__( 'Woo Showcases', 'shopglut' ),
		// 	'manage_options',
		// 	$this->menus_slug,
		// 	array( $shops_menu, 'rendershowcasesPages' )
        // );

        // add_submenu_page(
		// 	$this->menu_slug,
		// 	esc_html__( 'Business Modules', 'shopglut' ),
		// 	esc_html__( 'Business Modules', 'shopglut' ),
		// 	'manage_options',
		// 	'shopg_business_solution',
		// 	array( $business_solutions, 'renderBusinessSolutionsPage' )
		// );

        //  add_submenu_page(
		// 	$this->menu_slug,
		// 	esc_html__( 'Email Customizer', 'shopglut' ),
		// 	esc_html__( 'Email Customizer', 'shopglut' ),
		// 	'manage_options',
		// 	'shopglut_email_customizer',
		// 	array( $this, 'renderEmailCustomizer' )
		// );

        // add_submenu_page(
		// 	$this->menu_slug,
		// 	esc_html__( 'Invoices & Packing Slips', 'shopglut' ),
		// 	esc_html__( 'Invoices & Packing Slips', 'shopglut' ),
		// 	'manage_options',
		// 	'shopglut_pdf_invoices_slips',
		// 	array( $this, 'renderPdfInvoicesMain' )
		// );

	


		add_submenu_page(
			$this->menu_slug,    // Parent menu slug
			esc_html__( 'Welcome Page', 'shopglut' ),    // Page title
			esc_html__( 'Welcome Page', 'shopglut' ),    // Menu title
			'manage_options',    // Capability
			'shopglut-welcome',  // Menu slug
			array( $this, 'shopglut_welcome_page' ),   // Callback function
			999                  // Set to a high priority number to push it to the bottom
		);


	}

   public function shopglut_welcome_page() {
    ?>
    <style>
        .shopglut-welcome-page {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            max-width: 1200px;
            margin: 0px auto;
            background: #ffffff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-radius: 0px;
            overflow: hidden;
            border: 0px solid #e1e5e9;
        }

        .shopglut-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }

        .shopglut-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.05);
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .shopglut-header-content {
            position: relative;
            z-index: 2;
        }

        .shopglut-welcome-title {
            font-size: 3rem !important;
            font-weight: 700 !important;
            margin: 0 0 12px 0;
			color:#fff;
            letter-spacing: -1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .shopglut-welcome-subtitle {
            font-size: 1.05rem;
            font-weight: 300;
            margin: 0;
            opacity: 0.95;
            letter-spacing: 0.3px;
        }

        .shopglut-main-content {
            padding: 50px 40px;
        }

        .shopglut-intro-card {
            background: linear-gradient(135deg, #f8fbff 0%, #f1f7ff 100%);
            border: 1px solid #e3f2fd;
            border-radius: 12px;
            padding: 35px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .shopglut-intro-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .shopglut-intro-card h2 {
            color: #1a1a1a;
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .shopglut-intro-card h2::before {
            content: "🎉";
            font-size: 1.4rem;
        }

        .shopglut-intro-card p {
            color: #4a5568;
            font-size: 1.05rem;
            line-height: 1.7;
            margin: 0 0 16px 0;
        }

        .shopglut-intro-card p:last-child {
            margin-bottom: 0;
        }

        .shopglut-highlight {
            color: #667eea;
            font-weight: 600;
            background: linear-gradient(120deg, rgba(102, 126, 234, 0.1) 0%, rgba(102, 126, 234, 0.05) 100%);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .shopglut-content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .shopglut-content-section {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .shopglut-content-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .shopglut-content-section:last-child {
            margin-bottom: 0;
        }

        .shopglut-content-section h3 {
            color: #1a1a1a;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0 0 20px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f3f4;
            position: relative;
        }

        .shopglut-content-section h3::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .shopglut-content-section p {
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }

        .shopglut-content-section p:last-child {
            margin-bottom: 0;
        }

        .shopglut-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .shopglut-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .shopglut-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .shopglut-card-header {
            background: linear-gradient(135deg, #f8fbff 0%, #f1f7ff 100%);
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
        }

        .shopglut-card-header h4 {
            color: #1a1a1a;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-card-content {
            padding: 25px;
        }

        .shopglut-card-content p {
            color: #4a5568;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }

        .shopglut-card-content p:last-child {
            margin-bottom: 0;
        }

        .shopglut-card-content ul {
            margin: 15px 0;
            padding-left: 0;
            list-style: none;
        }

        .shopglut-card-content li {
            color: #4a5568;
            font-size: 0.95rem;
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
            line-height: 1.5;
        }

        .shopglut-card-content li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;
            color: #667eea;
            font-weight: bold;
            font-size: 1rem;
        }

        .shopglut-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .shopglut-link:hover {
            color: #5a67d8;
            text-decoration: none;
        }

        .shopglut-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #2271b1;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(34, 113, 177, 0.2);
        }

        .shopglut-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 113, 177, 0.3);
            color: #ffffff;
            text-decoration: none;
        }

        .shopglut-button-secondary {
            background: #ffffff;
            color: #2271b1;
            border: 2px solid #2271b1;
            box-shadow: 0 2px 8px rgba(34, 113, 177, 0.1);
        }

        .shopglut-button-secondary:hover {
            background: #2271b1;
            color: #ffffff;
            border-color: #2271b1;
            box-shadow: 0 6px 20px rgba(34, 113, 177, 0.25);
        }

        .shopglut-button-block {
            display: flex;
            width: 80%;
            margin: 0 0 12px 0;
        }

        .shopglut-button-block:last-child {
            margin-bottom: 0;
        }

        .shopglut-button-icon {
            font-size: 1rem;
            margin-right: 4px;
        }

        .shopglut-button[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .shopglut-loading {
            position: relative;
        }

        #enable-all-modules {
            transition: all 0.3s ease;
            min-height: 52px;
        }

        #enable-all-modules.loading {
            opacity: 0.85;
        }

        /* Notification styles are now centralized in shopglut-notification.css
         * @see global-assets/css/shopglut-notification.css
         */

        .shopglut-actions {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f3f4;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .shopglut-version-badge {
            background: linear-gradient(135deg, #f8fbff 0%, #f1f7ff 100%);
            border: 1px solid #e3f2fd;
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .shopglut-footer {
            background: linear-gradient(135deg, #f8fbff 0%, #f1f7ff 100%);
            border-top: 1px solid #e9ecef;
            padding: 30px 40px;
            text-align: center;
        }

        .shopglut-footer p {
            color: #4a5568;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0 0 12px 0;
        }

        .shopglut-footer p:last-child {
            margin-bottom: 0;
        }

        .shopglut-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0 0 0;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .shopglut-stat {
            text-align: center;
            color: #ffffff;
        }

        .shopglut-stat-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .shopglut-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 300;
        }

        @media (max-width: 768px) {
            .shopglut-welcome-page {
                margin: 10px;
                border-radius: 8px;
            }

            .shopglut-header {
                padding: 40px 20px;
            }

            .shopglut-welcome-title {
                font-size: 2.2rem;
            }

            .shopglut-welcome-subtitle {
                font-size: 1.1rem;
            }

            .shopglut-main-content {
                padding: 30px 20px;
            }

            .shopglut-intro-card {
                padding: 25px;
            }

            .shopglut-content-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .shopglut-content-section {
                padding: 25px;
            }

            .shopglut-card-header {
                padding: 15px 20px;
            }

            .shopglut-card-content {
                padding: 20px;
            }

            .shopglut-footer {
                padding: 25px 20px;
            }

            .shopglut-actions {
                flex-direction: column;
            }

            .shopglut-button {
                width: 100%;
            }

            .shopglut-stats {
                grid-template-columns: 1fr;
                gap: 15px;
                margin: 20px 0 0 0;
                padding: 20px;
            }
        }
    </style>

   <div class="wrap shopglut-welcome-page">
        <!-- Header -->
        <div class="shopglut-header">
            <div class="shopglut-header-content">
                <h1 class="shopglut-welcome-title"><?php echo esc_html__( 'Welcome to ShopGlut', 'shopglut' ); ?></h1>
                <p class="shopglut-welcome-subtitle"><?php echo esc_html__( 'Professional WooCommerce Solution', 'shopglut' ); ?></p>
                
            </div>
        </div>

        <!-- Main Content -->
        <div class="shopglut-main-content">
            <!-- Welcome & Module Setup Card -->
            <div class="shopglut-intro-card" style="background: #ffffff; border: 1px solid #dee2e6; position: relative;">
                <div style="position: absolute; top: 0; left: 0; width: 5px; height: 100%; background: #2271b1;"></div>
                <h2 style="color: #2271b1; margin-bottom: 15px;"><?php echo esc_html__( 'Thank you for installing ShopGlut!', 'shopglut' ); ?></h2>
                <p style="color: #495057; margin-bottom: 25px;"><?php echo esc_html__( 'Get started by enabling the modules you need:', 'shopglut' ); ?></p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <button id="enable-all-modules" class="shopglut-button" style="background: #2271b1; color: #ffffff; border: 2px solid #2271b1; font-size: 1.1rem; padding: 18px 32px; font-weight: 700; box-shadow: 0 4px 15px rgba(34, 113, 177, 0.3); width: 100%; max-width: 320px;" data-loading-text="<?php echo esc_attr__( 'Enabling All Modules...', 'shopglut' ); ?>">
                        <span class="shopglut-button-icon" style="font-size: 1.3rem;">⚡</span>
                        <?php echo esc_html__( 'Enable All Modules', 'shopglut' ); ?>
                    </button>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopg_woocommerce_builder' ) ); ?>" class="shopglut-button" style="background: #2271b1; color: #ffffff; border: 2px solid #2271b1; font-size: 1.1rem; padding: 18px 24px; font-weight: 700; text-align: center; text-decoration: none; box-shadow: 0 4px 15px rgba(34, 113, 177, 0.3);">
                        <span class="shopglut-button-icon" style="font-size: 1.3rem;">🔧</span>
                        <?php echo esc_html__( 'Builder Modules', 'shopglut' ); ?>
                    </a>
                    <!-- <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopg_business_solution' ) ); ?>" class="shopglut-button" style="background: #2271b1; color: #ffffff; border: 2px solid #2271b1; font-size: 1.1rem; padding: 18px 24px; font-weight: 700; text-align: center; text-decoration: none; box-shadow: 0 4px 15px rgba(34, 113, 177, 0.3);">
                        <span class="shopglut-button-icon" style="font-size: 1.3rem;">💼</span>
                        <?php echo esc_html__( 'Business Modules', 'shopglut' ); ?>
                    </a> -->
                </div>
            </div>

            <!-- Version Information -->
            <div class="shopglut-version-badge">
                <?php printf( 
                    // translators: %1$s is the ShopGlut version number, %2$s is the WordPress version number
                    esc_html__( 'ShopGlut Version: %1$s | WordPress Version: %2$s', 'shopglut' ),
                    '<strong>'.esc_attr(SHOPGLUT_VERSION).'</strong>',
                    '<strong>' . esc_attr(get_bloginfo('version')) . '</strong>'
                ); ?>
            </div>

            <!-- Content Grid -->
            <div class="shopglut-content-grid">
                <!-- Main Content -->
                <div>
                    <div class="shopglut-content-section">
                        <h3><?php echo esc_html__( 'Getting Started', 'shopglut' ); ?></h3>
                        <p><?php echo esc_html__( 'ShopGlut provides you with powerful tools to customize every aspect of your WooCommerce store. From product pages to checkout flows, create the perfect shopping experience for your customers.', 'shopglut' ); ?></p>
                        <p><?php echo esc_html__( 'To help you get started quickly, we recommend exploring our comprehensive documentation and example templates.', 'shopglut' ); ?></p>
                        
                        <div class="shopglut-actions">
                            <a href="https://documentation.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=adminmenu" target="_blank" class="shopglut-button">
                                <?php echo esc_html__( 'View Documentation', 'shopglut' ); ?>
                            </a>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts' ) ); ?>" class="shopglut-button shopglut-button-secondary">
                                <?php echo esc_html__( 'Start Building', 'shopglut' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="shopglut-content-section">
                        <h3><?php echo esc_html__( 'Need Help?', 'shopglut' ); ?></h3>
                        <p><?php printf( 
                            // translators: %1$s is the opening HTML tag for the support link, %2$s is the closing HTML tag for the support link
                            esc_html__( 'If you encounter any issues, %1$sreach out to our support team%2$s. We\'re here to help!', 'shopglut' ),
                            '<strong><a href="https://www.appglut.com/support/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=support" target="_blank" class="shopglut-link">',
                            '</a></strong>'
                        ); ?></p>
                        <p><?php echo esc_html__( 'Your feedback is invaluable and helps us improve the plugin for everyone.', 'shopglut' ); ?></p>
                    </div>

                    <div class="shopglut-content-section">
                        <h3><?php echo esc_html__( 'What\'s Next?', 'shopglut' ); ?></h3>
                        <p><?php echo esc_html__( 'Explore the ShopGlut builder options from your WordPress admin menu. Start with creating custom layouts for your shop pages, product pages, and checkout process.', 'shopglut' ); ?></p>
                        <p><?php echo esc_html__( 'Each builder module is designed to give you averagly maximum control over your store\'s appearance and functionality.', 'shopglut' ); ?></p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="shopglut-sidebar">
                    <!-- Quick Start Card -->
                    <div class="shopglut-card">
                        <div class="shopglut-card-header">
                            <h4>📚 <?php echo esc_html__( 'Quick Start', 'shopglut' ); ?></h4>
                        </div>
                        <div class="shopglut-card-content">
                            <p><?php echo esc_html__( 'Access essential resources to get started:', 'shopglut' ); ?></p>
                            <a href="https://www.documentation.appglut.com" target="_blank" class="shopglut-button shopglut-button-secondary shopglut-button-block">
                                <?php echo esc_html__( 'Browse Documentation', 'shopglut' ); ?>
                            </a>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts' ) ); ?>" class="shopglut-button shopglut-button-block">
                                <?php echo esc_html__( 'Access Builder', 'shopglut' ); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Support Card -->
                    <div class="shopglut-card">
                        <div class="shopglut-card-header">
                            <h4>🛠️ <?php echo esc_html__( 'Support Information', 'shopglut' ); ?></h4>
                        </div>
                        <div class="shopglut-card-content">
                            <p><strong><?php echo esc_html__( 'When contacting support, please provide:', 'shopglut' ); ?></strong></p>
                            <ul>
                                <li><?php echo esc_html__( 'Your WordPress and ShopGlut plugin version', 'shopglut' ); ?></li>
                                <li><?php echo esc_html__( 'Steps to reproduce the issue', 'shopglut' ); ?></li>
                                <li><?php echo esc_html__( 'Error messages or screenshots, if any', 'shopglut' ); ?></li>
                                <li><?php echo esc_html__( 'Your website URL (if applicable)', 'shopglut' ); ?></li>
                            </ul>
                            
                            <a href="https://www.appglut.com/support/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=support" target="_blank" class="shopglut-button shopglut-button-block">
                                <?php echo esc_html__( 'Contact Support', 'shopglut' ); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Community Card -->
                    <div class="shopglut-card">
                        <div class="shopglut-card-header">
                            <h4>🌟 <?php echo esc_html__( 'Stay Connected', 'shopglut' ); ?></h4>
                        </div>
                        <div class="shopglut-card-content">
                            <p><?php echo esc_html__( 'Join our community for updates, tips, and feature announcements.', 'shopglut' ); ?></p>
                            <a href="https://www.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=appglut" target="_blank" class="shopglut-button shopglut-button-secondary shopglut-button-block">
                                <?php echo esc_html__( 'Visit Website', 'shopglut' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="shopglut-footer">
            <p><strong><?php echo esc_html__( 'Stay tuned for more amazing features.', 'shopglut' ); ?></strong></p>
            <p><?php printf( 
                // translators: %1$s is the opening HTML tag for the website link, %2$s is the closing HTML tag for the website link
                esc_html__( '%1$sVisit our website%2$s for more information and updates.', 'shopglut' ),
                '<a href="https://www.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=appglut" target="_blank" class="shopglut-link">',
                '</a>'
            ); ?></p>
        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Handle Enable All Modules button
        $('#enable-all-modules').on('click', function() {
            var $button = $(this);
            var originalText = $button.html();
            var loadingText = $button.data('loading-text');

            // Set button to loading state
            $button.addClass('loading').prop('disabled', true);
            $button.html('<span class="shopglut-button-icon">⏳</span> ' + loadingText);
            
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'enable_all_shopglut_modules',
                    nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_nonce')); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('✅ ' + response.data.message, 'success');
                        
                        // Change button to indicate completion
                        $button.html('<span class="shopglut-button-icon">✅</span><?php echo esc_js(__('All Modules Enabled!', 'shopglut')); ?>');

                        setTimeout(function() {
                            $button.removeClass('loading').prop('disabled', false);
                            setTimeout(function() {
                                $button.html(originalText);
                            }, 100);
                        }, 3000);
                        
                    } else {
                        showNotification('❌ ' + (response.data.message || '<?php echo esc_js(__('Failed to enable modules', 'shopglut')); ?>'), 'error');
                        
                        // Reset button
                        $button.removeClass('loading').prop('disabled', false);
                        setTimeout(function() {
                            $button.html(originalText);
                        }, 100);
                    }
                },
                error: function() {
                    showNotification('❌ <?php echo esc_js(__('An error occurred while enabling modules.', 'shopglut')); ?>', 'error');
                    
                    // Reset button
                    $button.removeClass('loading').prop('disabled', false);
                    setTimeout(function() {
                        $button.html(originalText);
                    }, 100);
                }
            });
        });
        
        // Show notification function
        function showNotification(message, type) {
            var $notification = $('<div class="shopglut-notification shopglut-notification-' + type + '">' + message + '</div>');

            $('body').append($notification);

            // Auto remove notification after 4 seconds
            setTimeout(function() {
                $notification.fadeOut(300, function() {
                    $notification.remove();
                });
            }, 4000);
        }
    });
    </script>
    <?php
}

	private function getMenuIcon() {

		return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iNjUiIHpvb21BbmRQYW49Im1hZ25pZnkiIHZpZXdCb3g9IjAgMCA0OC43NSA0OC43NDk5OTgiIGhlaWdodD0iNjUiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiIHZlcnNpb249IjEuMCI+PGRlZnM+PGNsaXBQYXRoIGlkPSJiNzdkYjQ2NjNhIj48cGF0aCBkPSJNIDkgNyBMIDQ3LjgwNDY4OCA3IEwgNDcuODA0Njg4IDMzIEwgOSAzMyBaIE0gOSA3ICIgY2xpcC1ydWxlPSJub256ZXJvIi8+PC9jbGlwUGF0aD48Y2xpcFBhdGggaWQ9ImU2MzU5MGVjZTEiPjxwYXRoIGQ9Ik0gOCAxOCBMIDQ3IDE4IEwgNDcgNDYuNjUyMzQ0IEwgOCA0Ni42NTIzNDQgWiBNIDggMTggIiBjbGlwLXJ1bGU9Im5vbnplcm8iLz48L2NsaXBQYXRoPjwvZGVmcz48cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDI1LjMwMDc4MSA4LjM0Mzc1IEMgMjcuMjk2ODc1IDguMzM5ODQ0IDI5LjI1IDguNjM2NzE5IDMxLjE2MDE1NiA5LjIyNjU2MiBDIDMzLjA2NjQwNiA5LjgyMDMxMiAzNC44NDM3NSAxMC42ODM1OTQgMzYuNDg4MjgxIDExLjgxNjQwNiBMIDM5LjkwMjM0NCA4Ljk3NjU2MiBDIDM4Ljg4MjgxMiA4LjEyNSAzNy43OTI5NjkgNy4zNzEwOTQgMzYuNjM2NzE5IDYuNzE0ODQ0IEMgMzUuNDgwNDY5IDYuMDU4NTk0IDM0LjI3NzM0NCA1LjUwNzgxMiAzMy4wMjM0MzggNS4wNjY0MDYgQyAzMS43Njk1MzEgNC42MjEwOTQgMzAuNDg0Mzc1IDQuMjk2ODc1IDI5LjE3NTc4MSA0LjA4MjAzMSBDIDI3Ljg2MzI4MSAzLjg2NzE4OCAyNi41MzkwNjIgMy43NzM0MzggMjUuMjEwOTM4IDMuNzk2ODc1IEMgMjMuODgyODEyIDMuODIwMzEyIDIyLjU2NjQwNiAzLjk1NzAzMSAyMS4yNjE3MTkgNC4yMTQ4NDQgQyAxOS45NTcwMzEgNC40NzI2NTYgMTguNjgzNTk0IDQuODQzNzUgMTcuNDQ1MzEyIDUuMzI4MTI1IEMgMTYuMjEwOTM4IDUuODEyNSAxNS4wMjM0MzggNi40MDIzNDQgMTMuODkwNjI1IDcuMTAxNTYyIEMgMTIuNzU3ODEyIDcuNzk2ODc1IDExLjY5NTMxMiA4LjU4NTkzOCAxMC43MDMxMjUgOS40NzI2NTYgQyA5LjcxMDkzOCAxMC4zNTkzNzUgOC44MDg1OTQgMTEuMzI0MjE5IDcuOTg4MjgxIDEyLjM3MTA5NCBDIDcuMTY3OTY5IDEzLjQxNzk2OSA2LjQ0OTIxOSAxNC41MzEyNSA1LjgyODEyNSAxNS43MDcwMzEgQyA1LjIwNzAzMSAxNi44ODI4MTIgNC42OTUzMTIgMTguMTAxNTYyIDQuMjkyOTY5IDE5LjM3MTA5NCBDIDMuODkwNjI1IDIwLjYzNjcxOSAzLjYwMTU2MiAyMS45Mjk2ODggMy40Mjk2ODggMjMuMjUgQyAzLjI1NzgxMiAyNC41NjY0MDYgMy4yMDMxMjUgMjUuODkwNjI1IDMuMjY5NTMxIDI3LjIxODc1IEMgMy4zMzIwMzEgMjguNTQ2ODc1IDMuNTExNzE5IDI5Ljg1OTM3NSAzLjgxMjUgMzEuMTUyMzQ0IEMgNC4xMDkzNzUgMzIuNDQ5MjE5IDQuNTE5NTMxIDMzLjcwNzAzMSA1LjA0Mjk2OSAzNC45Mjk2ODggQyA1LjU2NjQwNiAzNi4xNTIzNDQgNi4xOTE0MDYgMzcuMzIwMzEyIDYuOTI1NzgxIDM4LjQyOTY4OCBMIDguNDM3NSAzOC40Mjk2ODggQyA3LjUyMzQzOCAzNi45Mjk2ODggNi44MjAzMTIgMzUuMzM5ODQ0IDYuMzI4MTI1IDMzLjY1MjM0NCBDIDUuODM1OTM4IDMxLjk2ODc1IDUuNTc0MjE5IDMwLjI0NjA5NCA1LjU0Mjk2OSAyOC40OTIxODggQyA1LjUxMTcxOSAyNi43MzQzNzUgNS43MDcwMzEgMjUuMDA3ODEyIDYuMTM2NzE5IDIzLjMwNDY4OCBDIDYuNTYyNSAyMS42MDE1NjIgNy4yMDcwMzEgMTkuOTg0Mzc1IDguMDY2NDA2IDE4LjQ1MzEyNSBDIDguOTIxODc1IDE2LjkyMTg3NSA5Ljk2ODc1IDE1LjUyNzM0NCAxMS4xOTUzMTIgMTQuMjczNDM4IEMgMTIuNDI1NzgxIDEzLjAxOTUzMSAxMy43OTY4NzUgMTEuOTQ5MjE5IDE1LjMxMjUgMTEuMDYyNSBDIDE2LjgyODEyNSAxMC4xNzE4NzUgMTguNDMzNTk0IDkuNSAyMC4xMjUgOS4wMzkwNjIgQyAyMS44MjAzMTIgOC41NzgxMjUgMjMuNTQ2ODc1IDguMzQzNzUgMjUuMzAwNzgxIDguMzQzNzUgWiBNIDI1LjMwMDc4MSA4LjM0Mzc1ICIgZmlsbC1vcGFjaXR5PSIxIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48ZyBjbGlwLXBhdGg9InVybCgjYjc3ZGI0NjYzYSkiPjxwYXRoIGZpbGw9IiNmZmZmZmYiIGQ9Ik0gOS42Njc5NjkgMTguOTYwOTM4IEwgMjEuOTEwMTU2IDE4Ljk2MDkzOCBMIDI2LjE1MjM0NCAyMy4wMDc4MTIgTCA0NC42MTMyODEgNy42MDkzNzUgTCA0Ny42Njc5NjkgNy40MTAxNTYgTCAyNi4wNDY4NzUgMzEuNzg5MDYyIEMgMjUuOTI5Njg4IDMxLjkxNzk2OSAyNS43OTI5NjkgMzIuMDIzNDM4IDI1LjYzMjgxMiAzMi4xMDE1NjIgQyAyNS40NzY1NjIgMzIuMTc1NzgxIDI1LjMwODU5NCAzMi4yMTg3NSAyNS4xMzI4MTIgMzIuMjMwNDY5IEMgMjQuOTU3MDMxIDMyLjI0MjE4OCAyNC43ODkwNjIgMzIuMjE4NzUgMjQuNjIxMDk0IDMyLjE2MDE1NiBDIDI0LjQ1NzAzMSAzMi4xMDE1NjIgMjQuMzA4NTk0IDMyLjAxNTYyNSAyNC4xNzU3ODEgMzEuODk4NDM4IFogTSA5LjY2Nzk2OSAxOC45NjA5MzggIiBmaWxsLW9wYWNpdHk9IjEiIGZpbGwtcnVsZT0ibm9uemVybyIvPjwvZz48ZyBjbGlwLXBhdGg9InVybCgjZTYzNTkwZWNlMSkiPjxwYXRoIGZpbGw9IiNmZmZmZmYiIGQ9Ik0gNDUuNTcwMzEyIDE4LjI3NzM0NCBMIDQxLjg3MTA5NCAxOC4yNzczNDQgQyA0MS41NjI1IDE4LjI4MTI1IDQxLjI4NTE1NiAxOC4zNzUgNDEuMDM5MDYyIDE4LjU1ODU5NCBDIDQwLjc5Njg3NSAxOC43NDYwOTQgNDAuNjI4OTA2IDE4Ljk4ODI4MSA0MC41NDY4NzUgMTkuMjg1MTU2IEwgMzkuMTYwMTU2IDI0LjE5MTQwNiBMIDM1LjQ1NzAzMSAyNC4xOTE0MDYgTCAyNi41MDc4MTIgMzQuMzgyODEyIEMgMjYuMzU5Mzc1IDM0LjU0Njg3NSAyNi4xODc1IDM0LjY3OTY4OCAyNS45ODgyODEgMzQuNzc3MzQ0IEMgMjUuNzg5MDYyIDM0Ljg3NSAyNS41NzgxMjUgMzQuOTMzNTk0IDI1LjM1NTQ2OSAzNC45NDUzMTIgQyAyNS4xMzI4MTIgMzQuOTYwOTM4IDI0LjkxNzk2OSAzNC45Mjk2ODggMjQuNzA3MDMxIDM0Ljg1OTM3NSBDIDI0LjUgMzQuNzg5MDYyIDI0LjMwODU5NCAzNC42NzU3ODEgMjQuMTQ0NTMxIDM0LjUzMTI1IEwgMTIuNDg0Mzc1IDI0LjE5MTQwNiBMIDkuMDU0Njg4IDI0LjE5MTQwNiBDIDguOTQxNDA2IDI0LjE5MTQwNiA4LjgzNTkzOCAyNC4yMTg3NSA4LjczODI4MSAyNC4yNjk1MzEgQyA4LjY0MDYyNSAyNC4zMjAzMTIgOC41NTg1OTQgMjQuMzkwNjI1IDguNDk2MDk0IDI0LjQ4MDQ2OSBDIDguNDMzNTk0IDI0LjU3MDMxMiA4LjM5MDYyNSAyNC42NzE4NzUgOC4zNzUgMjQuNzgxMjUgQyA4LjM2MzI4MSAyNC44OTA2MjUgOC4zNzEwOTQgMjUgOC40MTAxNTYgMjUuMTAxNTYyIEwgMTIuOTQ1MzEyIDM4LjAzNTE1NiBDIDEyLjk5MjE4OCAzOC4xNzU3ODEgMTMuMDc4MTI1IDM4LjI4OTA2MiAxMy4xOTUzMTIgMzguMzcxMDk0IEMgMTMuMzE2NDA2IDM4LjQ1NzAzMSAxMy40NTMxMjUgMzguNDk2MDk0IDEzLjU5NzY1NiAzOC40OTYwOTQgTCAzMy43NzczNDQgMzguNDk2MDk0IEMgMzMuOTU3MDMxIDM4LjQ5NjA5NCAzNC4xMjg5MDYgMzguNTI3MzQ0IDM0LjI5Mjk2OSAzOC41OTc2NTYgQyAzNC40NTcwMzEgMzguNjY0MDYyIDM0LjYwNTQ2OSAzOC43NjE3MTkgMzQuNzMwNDY5IDM4Ljg5MDYyNSBDIDM0Ljg1NTQ2OSAzOS4wMTU2MjUgMzQuOTUzMTI1IDM5LjE2MDE1NiAzNS4wMTk1MzEgMzkuMzI0MjE5IEMgMzUuMDg5ODQ0IDM5LjQ4ODI4MSAzNS4xMjUgMzkuNjYwMTU2IDM1LjEyNSAzOS44Mzk4NDQgQyAzNS4xMjUgNDAuMDE5NTMxIDM1LjA4OTg0NCA0MC4xOTE0MDYgMzUuMDE5NTMxIDQwLjM1NTQ2OSBDIDM0Ljk1MzEyNSA0MC41MTk1MzEgMzQuODU1NDY5IDQwLjY2NDA2MiAzNC43MzA0NjkgNDAuNzg5MDYyIEMgMzQuNjA1NDY5IDQwLjkxNzk2OSAzNC40NTcwMzEgNDEuMDE1NjI1IDM0LjI5Mjk2OSA0MS4wODIwMzEgQyAzNC4xMjg5MDYgNDEuMTUyMzQ0IDMzLjk1NzAzMSA0MS4xODM1OTQgMzMuNzc3MzQ0IDQxLjE4MzU5NCBMIDE1LjcyNjU2MiA0MS4xODM1OTQgQyAxNS41MzEyNSA0MS4xNzU3ODEgMTUuMzQzNzUgNDEuMjA3MDMxIDE1LjE2NDA2MiA0MS4yNzczNDQgQyAxNC45ODA0NjkgNDEuMzQ3NjU2IDE0LjgyMDMxMiA0MS40NDkyMTkgMTQuNjc5Njg4IDQxLjU4MjAzMSBDIDE0LjUzOTA2MiA0MS43MTg3NSAxNC40Mjk2ODggNDEuODc1IDE0LjM1NTQ2OSA0Mi4wNTQ2ODggQyAxNC4yNzczNDQgNDIuMjM0Mzc1IDE0LjIzODI4MSA0Mi40MjE4NzUgMTQuMjM4MjgxIDQyLjYxNzE4OCBDIDE0LjIzODI4MSA0Mi44MTI1IDE0LjI3NzM0NCA0Mi45OTYwOTQgMTQuMzU1NDY5IDQzLjE3NTc4MSBDIDE0LjQyOTY4OCA0My4zNTU0NjkgMTQuNTM5MDYyIDQzLjUxMTcxOSAxNC42Nzk2ODggNDMuNjQ4NDM4IEMgMTQuODIwMzEyIDQzLjc4NTE1NiAxNC45ODA0NjkgNDMuODg2NzE5IDE1LjE2NDA2MiA0My45NTMxMjUgQyAxNS4zNDM3NSA0NC4wMjM0MzggMTUuNTMxMjUgNDQuMDU0Njg4IDE1LjcyNjU2MiA0NC4wNDY4NzUgTCAxNy44Mzk4NDQgNDQuMDQ2ODc1IEMgMTcuODI4MTI1IDQ0LjE0NDUzMSAxNy44MjAzMTIgNDQuMjQ2MDk0IDE3LjgyMDMxMiA0NC4zNDM3NSBDIDE3LjgyMDMxMiA0NC42Mjg5MDYgMTcuODc1IDQ0LjkwNjI1IDE3Ljk4NDM3NSA0NS4xNzE4NzUgQyAxOC4wOTM3NSA0NS40Mzc1IDE4LjI1IDQ1LjY2Nzk2OSAxOC40NDkyMTkgNDUuODcxMDk0IEMgMTguNjUyMzQ0IDQ2LjA3NDIxOSAxOC44ODY3MTkgNDYuMjMwNDY5IDE5LjE1MjM0NCA0Ni4zMzk4NDQgQyAxOS40MTc5NjkgNDYuNDQ5MjE5IDE5LjY5MTQwNiA0Ni41MDM5MDYgMTkuOTc2NTYyIDQ2LjUwMzkwNiBDIDIwLjI2NTYyNSA0Ni41MDM5MDYgMjAuNTM5MDYyIDQ2LjQ0OTIxOSAyMC44MDQ2ODggNDYuMzM5ODQ0IEMgMjEuMDcwMzEyIDQ2LjIzMDQ2OSAyMS4zMDQ2ODggNDYuMDc0MjE5IDIxLjUwMzkwNiA0NS44NzEwOTQgQyAyMS43MDcwMzEgNDUuNjY3OTY5IDIxLjg2MzI4MSA0NS40Mzc1IDIxLjk3MjY1NiA0NS4xNzE4NzUgQyAyMi4wODIwMzEgNDQuOTA2MjUgMjIuMTM2NzE5IDQ0LjYyODkwNiAyMi4xMzY3MTkgNDQuMzQzNzUgQyAyMi4xMzY3MTkgNDQuMjQ2MDk0IDIyLjEyODkwNiA0NC4xNDQ1MzEgMjIuMTE3MTg4IDQ0LjA0Njg3NSBMIDI4Ljg5ODQzOCA0NC4wNDY4NzUgQyAyOC44ODI4MTIgNDQuMTQ0NTMxIDI4Ljg3NSA0NC4yNDYwOTQgMjguODc1IDQ0LjM0Mzc1IEMgMjguODc1IDQ0LjYyODkwNiAyOC45Mjk2ODggNDQuOTA2MjUgMjkuMDM5MDYyIDQ1LjE3MTg3NSBDIDI5LjE0ODQzOCA0NS40MzM1OTQgMjkuMzA0Njg4IDQ1LjY2Nzk2OSAyOS41MDc4MTIgNDUuODcxMDk0IEMgMjkuNzEwOTM4IDQ2LjA3NDIxOSAyOS45NDUzMTIgNDYuMjMwNDY5IDMwLjIwNzAzMSA0Ni4zMzk4NDQgQyAzMC40NzI2NTYgNDYuNDQ5MjE5IDMwLjc1IDQ2LjUwMzkwNiAzMS4wMzUxNTYgNDYuNTAzOTA2IEMgMzEuMzIwMzEyIDQ2LjUwMzkwNiAzMS41OTc2NTYgNDYuNDQ5MjE5IDMxLjg1OTM3NSA0Ni4zMzk4NDQgQyAzMi4xMjUgNDYuMjMwNDY5IDMyLjM1OTM3NSA0Ni4wNzQyMTkgMzIuNTYyNSA0NS44NzEwOTQgQyAzMi43NjU2MjUgNDUuNjY3OTY5IDMyLjkxNzk2OSA0NS40MzM1OTQgMzMuMDI3MzQ0IDQ1LjE3MTg3NSBDIDMzLjE0MDYyNSA0NC45MDYyNSAzMy4xOTUzMTIgNDQuNjI4OTA2IDMzLjE5NTMxMiA0NC4zNDM3NSBDIDMzLjE5MTQwNiA0NC4yNDYwOTQgMzMuMTgzNTk0IDQ0LjE0NDUzMSAzMy4xNjc5NjkgNDQuMDQ2ODc1IEwgMzQuNzUgNDQuMDQ2ODc1IEMgMzUuMjQ2MDk0IDQ0LjA0Njg3NSAzNS42OTE0MDYgNDMuODk4NDM4IDM2LjA4NTkzOCA0My41OTc2NTYgQyAzNi40ODQzNzUgNDMuMzAwNzgxIDM2Ljc1IDQyLjkxNDA2MiAzNi44ODI4MTIgNDIuNDM3NSBMIDQyLjkxMDE1NiAyMS4wMzkwNjIgTCA0NS42MTcxODggMjEuMDM5MDYyIEMgNDUuODA0Njg4IDIxLjAzOTA2MiA0NS45ODQzNzUgMjEuMDAzOTA2IDQ2LjE1NjI1IDIwLjkyOTY4OCBDIDQ2LjMyODEyNSAyMC44NTkzNzUgNDYuNDgwNDY5IDIwLjc1MzkwNiA0Ni42MDkzNzUgMjAuNjIxMDk0IEMgNDYuNzQyMTg4IDIwLjQ4ODI4MSA0Ni44Mzk4NDQgMjAuMzMyMDMxIDQ2LjkwNjI1IDIwLjE1NjI1IEMgNDYuOTc2NTYyIDE5Ljk4NDM3NSA0Ny4wMDc4MTIgMTkuODA0Njg4IDQ3IDE5LjYxNzE4OCBDIDQ2Ljk4ODI4MSAxOS40MzM1OTQgNDYuOTQ1MzEyIDE5LjI2MTcxOSA0Ni44NjcxODggMTkuMDkzNzUgQyA0Ni43ODkwNjIgMTguOTI5Njg4IDQ2LjY4NzUgMTguNzg1MTU2IDQ2LjU1MDc4MSAxOC42NjAxNTYgQyA0Ni40MTc5NjkgMTguNTM1MTU2IDQ2LjI2NTYyNSAxOC40NDE0MDYgNDYuMDk3NjU2IDE4LjM3NSBDIDQ1LjkyNTc4MSAxOC4zMDg1OTQgNDUuNzUgMTguMjc3MzQ0IDQ1LjU3MDMxMiAxOC4yNzczNDQgWiBNIDQ1LjU3MDMxMiAxOC4yNzczNDQgIiBmaWxsLW9wYWNpdHk9IjEiIGZpbGwtcnVsZT0ibm9uemVybyIvPjwvZz48L3N2Zz4=';
	}

	public function shopglutshowcaseScreenoptions() {
		$current_screen = get_current_screen();

		if ( ( 'shopglut_page_shopglut_enhancements' === $current_screen->id ) &&
			isset( $_GET['layout_id'] )
		) {

			return;
		}

		$current_screen = get_current_screen();

		 if ( ( 'shopglut_page_shopglut_enhancements' === $current_screen->id ) &&
			isset( $_GET['view'] ) && ( 'badges' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_enhancements_per_page',
			);
			add_screen_option( 'per_page', $args );

			$badgelist = new BadgeListTable();
		} 
		 else if ( ( 'shopglut_page_shopglut_enhancements' === $current_screen->id ) &&
			isset( $_GET['view'] ) && ( 'banners' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_enhancements_per_page',
			);
			add_screen_option( 'per_page', $args );

			$badgelist = new BannerListTable();
		}  else if ( 'shopglut_page_shopglut_enhancements' === $current_screen->id && ! wp_verify_nonce( isset( $_POST['menu_nonce_check'] ), 'menu_nonce_check' ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_enhancements_per_page',
			);
			add_screen_option( 'per_page', $args );

			$filterlist = new FilterListTable();
		}
	}

    public function renderWishlistMain() {
		// Initialize and render the wishlist interface
		$wishlist_handler = new WishlistMenuHandler();
		$wishlist_handler->render();
	}

	public function renderPdfInvoicesMain() {
		// Initialize and render the PDF Invoices interface using the menu handler
		$pdf_invoices_menu_handler = new PdfInvoicesMenuHandler();
		$pdf_invoices_menu_handler->render();
	}

	public function renderProductSwatches() {
		// Redirect directly to the settings page
		wp_safe_redirect( admin_url( 'admin.php?page=shopglut_swatches_settings' ) );
		exit;
	}


	public function renderEmailCustomizer() {
		$email_customizer = new EmailCustomizer();
		$email_customizer->renderEmailCustomizerPage();
	}

	public function renderGalleryShortcode() {
		// Include the Gallery admin file and handle page rendering
		require_once SHOPGLUT_PATH . 'src/tools/galleryShortcode/galleryadmin.php';
		$gallery_admin = new \Shopglut\galleryShortcode\GalleryAdmin();
		$gallery_admin->render_admin_page();
	}

	public function renderWooManagement() {
		// Check if PosGlut is active
		if ( ! $this->is_posglut_active() ) {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Woo Management', 'shopglut' ); ?></h1>
				<div class="notice notice-info">
					<p>
						<?php
						printf(
							/* translators: %1$s: GitHub URL, %2$s: plugin name */
							esc_html__( 'Woo Management features require the %1$s%2$s%3$s plugin to be installed and activated.', 'shopglut' ),
							'<a href="https://github.com/appglut/posglut" target="_blank">',
							esc_html__( 'PosGlut', 'shopglut' ),
							'</a>'
						);
						?>
					</p>
					<p>
						<a href="https://github.com/appglut/posglut" target="_blank" class="button button-primary">
							<?php esc_html_e( 'Download PosGlut from GitHub', 'shopglut' ); ?>
						</a>
					</p>
				</div>
			</div>
			<?php
			return;
		}

		// Get PosGlut path
		$posglut_path = $this->get_posglut_path();

		if ( ! $posglut_path ) {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Woo Management', 'shopglut' ); ?></h1>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'PosGlut plugin not found. Please install and activate it.', 'shopglut' ); ?></p>
				</div>
			</div>
			<?php
			return;
		}

		// Load PosGlut AdminPage and render management page
		if ( file_exists( $posglut_path . '/src/pos/AdminPage.php' ) ) {
			require_once $posglut_path . '/src/pos/AdminPage.php';
			$posglut_admin = new \Posglut\pos\AdminPage();
			$posglut_admin->renderManagementPage();
		} else {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Woo Management', 'shopglut' ); ?></h1>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'PosGlut AdminPage not found. Please ensure PosGlut is properly installed.', 'shopglut' ); ?></p>
				</div>
			</div>
			<?php
		}
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

	public function shopglutLayoutsScreenOptions() {
		$current_screen = get_current_screen();

		if ( ( 'toplevel_page_shopglut_layouts' === $current_screen->id ) &&
			isset( $_GET['view'] ) && ( 'shop' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_layouts_per_page',
			);
			add_screen_option( 'per_page', $args );

			$singlelayoutlist = new ShopListTable();
		}  else if ( 'toplevel_page_shopglut_layouts' === $current_screen->id  &&
			isset( $_GET['view'] ) && ( 'cartpage' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_layouts_per_page',
			);
			add_screen_option( 'per_page', $args );

			$cartlayoutlist = new CartPageListTable();
		}   else if ( 'toplevel_page_shopglut_layouts' === $current_screen->id  &&
			isset( $_GET['view'] ) && ( 'accountpage' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_layouts_per_page',
			);
			add_screen_option( 'per_page', $args );

			$accountlayoutlist = new AccountPageListTable();
		}   
        else if ( 'toplevel_page_shopglut_layouts' === $current_screen->id  &&
			isset( $_GET['view'] ) && ( 'ordercomplete' === $_GET['view'] ) ) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_layouts_per_page',
			);
			add_screen_option( 'per_page', $args );

			$ordercompletelayoutlist = new orderCompleteListTable();
		} else if ( 'toplevel_page_shopglut_layouts' === $current_screen->id && ! wp_verify_nonce( isset( $_POST['menu_nonce_check'] ), 'menu_nonce_check' )
		) {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_layouts_per_page',
			);
			add_screen_option( 'per_page', $args );

			$layoutlist = new SingleProductListTable();
		}



	}

    public function shopglutToolsScreenOptions(){
        $current_screen = get_current_screen();

        	if ( 'shopglut_page_shopglut_tools' === $current_screen->id && ! wp_verify_nonce( isset( $_POST['menu_nonce_check'] ), 'menu_nonce_check' ) )
		 {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$ProductCustomFieldListTable = new ProductCustomFieldListTable();
		}
  
    }

    public function shopglutShowcasesScreenOptions(){
         $current_screen = get_current_screen();

        	if ( 'shopglut_page_shopglut_showcases' === $current_screen->id && ! wp_verify_nonce( isset( $_POST['menu_nonce_check'] ), 'menu_nonce_check' )
                && isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'] )
                )
		 {

			$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$archiveLayoutlist = new SliderListTable();
		} else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'tabs' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$TablistTable = new TabListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'accordions' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$AccordionlistTable = new AccordionListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'gallery' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$GallerylistTable = new \Shopglut\showcases\Gallery\GalleryListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'shop_banner' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );



			$ShopBannerlistTable = new \Shopglut\showcases\ShopBanner\ShopBannerListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'mega_menu' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$MegaMenulistTable = new \Shopglut\showcases\MegaMenu\MegaMenuListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_showcases' === $_GET['page'])) && (isset( $_GET['view']) && ( 'misc' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

			$MisclistTable = new \Shopglut\showcases\Misc\MiscListTable();
        }


    }


    public function shopglutEnhancementsScreenOptions(){
         $current_screen = get_current_screen();

        	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        	if ((isset( $_GET['page'] ) && ( 'shopglut_enhancements' === $_GET['page'])) && (isset( $_GET['view']) && ( 'shop_filters' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_settings_per_page',
			);
			add_screen_option( 'per_page', $args );

		$FilterlistTable = new FilterListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_enhancements' === $_GET['page'])) && (isset( $_GET['view']) && ( 'product_badges' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_badges_per_page',
			);
			add_screen_option( 'per_page', $args );

		//$BadgelistTable = new \Shopglut\enhancements\ProductBadges\BadgeListTable();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        } else if ((isset( $_GET['page'] ) && ( 'shopglut_enhancements' === $_GET['page'])) && (isset( $_GET['view']) && ( 'product_badges' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_badges_per_page',
			);
			add_screen_option( 'per_page', $args );

			//$BadgelistTable = new \Shopglut\enhancements\ProductBadges\BadgeListTable();
        }        
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        else if ((isset( $_GET['page'] ) && ( 'shopglut_enhancements' === $_GET['page'])) && (isset( $_GET['view']) && ( 'product_comparisons' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_comparisons_per_page',
			);
			add_screen_option( 'per_page', $args );

			$ComparisonlistTable = new \Shopglut\enhancements\ProductComparison\ProductComparisonListTable();
        }
        
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page checks for screen options, not form processing
        else if ((isset( $_GET['page'] ) && ( 'shopglut_enhancements' === $_GET['page'])) && (isset( $_GET['view']) && ( 'product_quickviews' === $_GET['view']))){

            	$args = array(
				'label' => esc_html__( 'Items per page', 'shopglut' ),
				'default' => 10,
				'option' => 'shopglut_quick_views_per_page',
			);
			add_screen_option( 'per_page', $args );

			$QuickViewlistTable = new \Shopglut\enhancements\ProductQuickView\QuickViewListTable();
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