<?php
namespace Shopglut;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\layouts\AllLayouts;
use Shopglut\layouts\singleProduct\chooseTemplates as SingleProductTemplates;
use Shopglut\layouts\singleProduct\dataManage as SingleProductDataManage;


use Shopglut\layouts\cartPage\chooseTemplates as CartPageTemplates;
use Shopglut\layouts\cartPage\dataManage as CartPageDataManage;

use Shopglut\layouts\checkoutPage\CheckoutFieldsManager;
use Shopglut\layouts\checkoutPage\CheckoutFieldsDisplay;
use Shopglut\layouts\checkoutPage\BlockCheckoutFields;
use Shopglut\layouts\checkoutPage\CheckoutFieldsInit;

use Shopglut\layouts\orderCompletePage\chooseTemplates as orderCompleteTemplates;
use Shopglut\layouts\orderCompletePage\dataManage as orderCompletedataManage;


use Shopglut\layouts\accountPage\AccountPageChooseTemplates;
use Shopglut\layouts\accountPage\AccountPageDataManage;


use Shopglut\layouts\shopLayout\chooseTemplates as ShopLayoutTemplates;
use Shopglut\layouts\shopLayout\dataManage as ShopLayoutDataManage;

use Shopglut\enhancements\wishlist\dataManage as wishlistManage;
use Shopglut\enhancements\wishlist\admin\dataManage as wishlistAdminManage;

use Shopglut\enhancements\Filters\dataManage as FiltersDataManage;

use Shopglut\enhancements\Swatches\Swatches;
use Shopglut\enhancements\Swatches\dataManage as SwatchesdataManage;

use Shopglut\enhancements\ProductBadges\BadgechooseTemplates;
use Shopglut\enhancements\ProductBadges\BadgeDataManage;

use Shopglut\enhancements\ProductComparison\ProductComparisonDataManage;
use Shopglut\enhancements\ProductComparison\ComparisonchooseTemplates;


use Shopglut\enhancements\ProductQuickView\QuickViewchooseTemplates;
use Shopglut\enhancements\ProductQuickView\QuickViewDataManage;


use Shopglut\tools\loginRegister\LoginRegister;
use Shopglut\tools\productCustomField\ProductCustomFieldDataManage;
use Shopglut\tools\productCustomField\ProductCustomFieldHandler;
use Shopglut\tools\miniCart\MiniCart;

use Shopglut\showcases\Sliders\SliderchooseTemplates;
use Shopglut\showcases\Sliders\SliderDataManage;

use Shopglut\showcases\ShopBanner\ShopBannerchooseTemplates;
use Shopglut\showcases\ShopBanner\ShopBannerDataManage;

use Shopglut\showcases\Gallery\GallerychooseTemplates;

use Shopglut\showcases\Tabs\TabchooseTemplates;
use Shopglut\showcases\Tabs\TabDataManage;
use Shopglut\showcases\Tabs\TabEntity;

use Shopglut\showcases\ShopBanner\ShopBannerFrontend;

use Shopglut\showcases\Accordions\AccordionchooseTemplates;
use Shopglut\showcases\Accordions\AccordionDataManage;

use Shopglut\enhancements\AllEnhancements;

use Shopglut\enhancements\ProductSwatches\chooseTemplates as SwatchesTemplates;
use Shopglut\enhancements\ProductSwatches\dataManage as ProductSwatchesDataManage;
use Shopglut\enhancements\ProductSwatches\AttributeSwatchesManager;


class ShopGlutBase {

	// Declare properties to fix PHP 8.2+ deprecation warnings
	public $menu_slug;

	public function __construct() {

		// Initialize core components
		ShopGlutDatabase::ShopGlut_initialize();
		ShopGlutRegisterScripts::get_instance();
		ShopGlutRegisterMenu::get_instance();
		AllLayouts::get_instance();
		AllEnhancements::get_instance();

		SingleProductDataManage::get_instance();
		SingleProductTemplates::get_instance();

		// CartPageTemplates::get_instance();
		// CartPageDataManage::get_instance();
		
		
		// ShopLayoutTemplates::get_instance();
		// ShopLayoutDataManage::get_instance();

		// CheckoutFieldsManager::get_instance();
		// CheckoutFieldsDisplay::get_instance();
		// CheckoutFieldsInit::get_instance();
		// BlockCheckoutFields::get_instance();

        wishlistManage::get_instance();
		wishlistAdminManage::get_instance();

		// FiltersDataManage::get_instance();


		// orderCompletedataManage::get_instance();
		// orderCompleteTemplates::get_instance();

		// AccountPageChooseTemplates::get_instance();
		// AccountPageDataManage::get_instance();

		BadgechooseTemplates::get_instance();
		BadgeDataManage::get_instance();

		ComparisonchooseTemplates::get_instance();
		ProductComparisonDataManage::get_instance();

		// QuickViewchooseTemplates::get_instance();
		// QuickViewDataManage::get_instance();


		// SliderchooseTemplates::get_instance();
		// SliderDataManage::get_instance();

		// GalleryChooseTemplates::get_instance();

		ProductCustomFieldDataManage::get_instance();
		ProductCustomFieldHandler::get_instance();

		SwatchesTemplates::get_instance();
		ProductSwatchesDataManage::get_instance();
		AttributeSwatchesManager::get_instance();

		// LoginRegister::get_instance();

		// MiniCart::get_instance();
		
		// Initialize ShopBanner module
		// ShopBannerChooseTemplates::get_instance();
		// ShopBannerDataManage::get_instance();
		// ShopBannerFrontend::get_instance();

		// Initialize Accordion module
		// AccordionchooseTemplates::get_instance();
		// AccordionDataManage::get_instance();

		// Initialize Tabs module
		// TabchooseTemplates::get_instance();

		// SliderdataManage::get_instance();
		// TabDataManage::get_instance();
	


		// Add actions
		add_action( 'init', array( $this, 'shopglutInitialFunctions' ), 9 );
		add_filter( 'update_footer', array( $this, 'shopglut_admin_footer_version' ), 999 );

		// Hook the redirection function into admin_init
		add_action( 'admin_init', array( $this, 'shopglut_redirect_after_activation' ) );
	}

	public function shopglut_redirect_after_activation() {
		if ( ! get_option( 'shopglut_plugin_first_activation_redirect' ) ) {
			// Set the option to ensure this runs only once
			update_option( 'shopglut_plugin_first_activation_redirect', true );

			// Redirect to the welcome page after activation
			wp_safe_redirect( admin_url( 'admin.php?page=shopglut-welcome' ) );
			exit;
		}
	}

	public function shopglutInitialFunctions() {
		// Load required files
		require_once SHOPGLUT_PATH . 'src/ModuleManager.php';
		require_once SHOPGLUT_PATH . 'src/library/model/classes/setup.class.php';
		require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/singleLayout-settings.php';
		//require_once SHOPGLUT_PATH . 'src/layouts/cartPage/template-settings.php';
		//require_once SHOPGLUT_PATH . 'src/layouts/orderCompletePage/template-settings.php';
		//require_once SHOPGLUT_PATH . 'src/layouts/accountPage/template-settings.php';
		//require_once SHOPGLUT_PATH . 'src/enhancements/Filters/filters-config.php';
		// Commented out - using new ProductSwatches module instead
		// require_once SHOPGLUT_PATH . 'src/enhancements/Swatches/product-swatches-settings.php';
		require_once SHOPGLUT_PATH . 'src/enhancements/ProductSwatches/productSwatches-settings.php';
		require_once SHOPGLUT_PATH . 'src/enhancements/ProductBadges/product-badges-settings.php';
		require_once SHOPGLUT_PATH . 'src/enhancements/wishlist/wishlist-settings.php';
		//require_once SHOPGLUT_PATH . 'src/tools/miniCart/mini-cart-settings.php';
		require_once SHOPGLUT_PATH . 'src/enhancements/ProductComparison/template-settings.php';
		//require_once SHOPGLUT_PATH . 'src/enhancements/ProductQuickView/template-settings.php';
		//require_once SHOPGLUT_PATH . 'src/BusinessSolutions/PdfInvoices/pdf-invoices-settings.php';
		// require_once SHOPGLUT_PATH . 'src/layouts/shopLayout/shopLayouts-config.php';
	     require_once SHOPGLUT_PATH . 'src/tools/productCustomField/product-custom-field-settings.php';
		// require_once SHOPGLUT_PATH . 'src/showcases/Gallery/templates/template1/template1-settings.php';
		// require_once SHOPGLUT_PATH . 'src/showcases/ShopBanner/template-settings.php';
		// require_once SHOPGLUT_PATH . 'src/showcases/Sliders/template-settings.php';
	    // require_once SHOPGLUT_PATH . 'src/showcases/Tabs/template-settings.php';
	    // require_once SHOPGLUT_PATH . 'src/showcases/Accordions/template-settings.php';
	    // require_once SHOPGLUT_PATH . 'src/tools/shortcodeShowcase/init.php';
	
		
		// Load business solutions modules
		if ( file_exists( SHOPGLUT_PATH . 'src/business-solutions/index.php' ) ) {
			require_once SHOPGLUT_PATH . 'src/business-solutions/index.php';
		}
	}

	public function shopglut_admin_footer_version() {
		return '<span id="shopglut-footer-version" style="display: none;">ShopGlut ' . SHOPGLUT_VERSION . '</span>';
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}