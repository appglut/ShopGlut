<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include all the trait files
require_once __DIR__ . '/traits/WishlistButtons.php';
require_once __DIR__ . '/traits/WishlistAccountPage.php';
require_once __DIR__ . '/traits/WishlistAddtoCartCheckout.php';
require_once __DIR__ . '/traits/WishlistMerge.php';
require_once __DIR__ . '/traits/WishlistHooks.php';


// Include the new smaller display-related trait files
require_once __DIR__ . '/traits/WishlistDisplay.php';
require_once __DIR__ . '/traits/WishlistBulkActions.php';
require_once __DIR__ . '/traits/WishlistPopular.php';
require_once __DIR__ . '/traits/WishlistCounter.php';
require_once __DIR__ . '/traits/WishlistTableRenderer.php';
require_once __DIR__ . '/traits/WishlistTableCells.php';
require_once __DIR__ . '/traits/WishlistControls.php';
require_once __DIR__ . '/traits/WishlistSocialFeatures.php';
require_once __DIR__ . '/traits/WishlistAjaxHandlers.php';
require_once __DIR__ . '/traits/WishlistDataHelper.php';
require_once __DIR__ . '/traits/WishlistToggleHandler.php';
require_once __DIR__ . '/traits/WishlistQRFeatures.php';

//require_once __DIR__ . '/traits/WishlistTechnicalImprovements.php';


class dataManage {
    use WishlistButtons;
    use WishlistAccountPage;
    use WishlistAddtoCartCheckout;
    use WishlistMerge;
    use WishlistHooks;
    use WishlistDisplay;
    use WishlistBulkActions;
    use WishlistPopular;

    use WishlistTableRenderer;
    use WishlistTableCells;
    use WishlistControls;
    use WishlistSocialFeatures;
    use WishlistAjaxHandlers;
    use WishlistDataHelper;
    use WishlistToggleHandler;
    use WishlistQRFeatures;
    //use WishlistTechnicalImprovements;
    use WishlistCounter;

    // Add the missing static property
    private static $instance = null;

    private $enhancements;
    private $cron_token;
    
    public function __construct() {

        $this->enhancements = get_option( 'agshopglut_wishlist_options' );
        $this->cron_token = get_option( 'shopglut_wishlist_cron_token', '' );
        $this->shopglut_schedule_guest_cleanup();


        if ( empty( $this->cron_token ) ) {
            $this->cron_token = $this->generate_cron_token();
            update_option( 'shopglut_wishlist_cron_token', $this->cron_token );
        }
        $this->add_actions();

       // $this->init_cache_compatibility();
      //  $this->init_gdpr_compliance();
      //  $this->init_rtl_support();
      //  $this->init_performance_optimizations();
     //   $this->init_core_web_vitals_optimization();
      //  $this->init_lazy_loading();
        $this->init_wishlist_table_hooks();
        $this->init_wishlist_controls_hooks();
        $this->init_wishlist_action_buttons_hooks();
        $this->init_wishlist_social_features_hooks();
        
    }
    
    private function add_actions() {
        // Set cookie for guest user ID if the user is not logged in
        add_action( 'init', function () {
            if ( ! is_user_logged_in() && ! isset( $_COOKIE['shopglutw_guest_user_id'] ) ) {
                $guest_id = 'guest_' . uniqid();
                setcookie( 'shopglutw_guest_user_id', $guest_id, time() + ( 86400 * 30 ), '/' ); // Cookie expires in 30 days
            }
        } );

        // Register shortcode
        add_shortcode( 'shopglut_wishlist', [ $this, 'shopglut_wishlist_shortcode' ] );

        // Register AJAX actions
        add_action( 'wp_ajax_shopglut_load_wishlist_content', [ $this, 'shopglut_load_wishlist_content' ] );
        add_action( 'wp_ajax_nopriv_shopglut_load_wishlist_content', [ $this, 'shopglut_load_wishlist_content' ] );

        add_action( 'wp_ajax_shopglut_load_account_wishlist_content', [ $this, 'shopglut_load_account_wishlist_content' ] );
        add_action( 'wp_ajax_nopriv_shopglut_load_account_wishlist_content', [ $this, 'shopglut_load_account_wishlist_content' ] );

        add_action( 'wp_ajax_shopglut_remove_from_wishlist', [ $this, 'shopglut_remove_from_wishlist' ] );
        add_action( 'wp_ajax_nopriv_shopglut_remove_from_wishlist', [ $this, 'shopglut_remove_from_wishlist' ] );

        add_action( 'wp_ajax_shopglut_wishlist_add_to_cart', [ $this, 'shopglut_wishlist_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_shopglut_wishlist_add_to_cart', [ $this, 'shopglut_wishlist_add_to_cart' ] );

        add_action( 'wp_ajax_shopglut_add_to_cart_and_checkout', [ $this, 'shopglut_add_to_cart_and_checkout' ] );
        add_action( 'wp_ajax_nopriv_shopglut_add_to_cart_and_checkout', [ $this, 'shopglut_add_to_cart_and_checkout' ] );


        // AJAX handlers for wishlist actions
        add_action( 'wp_ajax_shopglut_toggle_wishlist', [ $this, 'shopglut_toggle_wishlist_callback' ] );
        add_action( 'wp_ajax_nopriv_shopglut_toggle_wishlist', [ $this, 'shopglut_toggle_wishlist_callback' ] );
        add_action( 'wp_ajax_shopglut_merge_guest_wishlist', [ $this, 'shopglut_merge_guest_wishlist' ] );
        add_action( 'wp_ajax_nopriv_shopglut_merge_guest_wishlist', [ $this, 'shopglut_merge_guest_wishlist' ] );


        add_action('wp_ajax_shopglut_add_all_to_cart', array($this, 'shopglut_add_all_to_cart'));
        add_action('wp_ajax_nopriv_shopglut_add_all_to_cart', array($this, 'shopglut_add_all_to_cart'));


        add_action('wp_ajax_shopglut_bulk_add_to_cart', array($this, 'shopglut_bulk_add_to_cart'));
        add_action('wp_ajax_nopriv_shopglut_bulk_add_to_cart', array($this, 'shopglut_bulk_add_to_cart'));
        
        add_action('wp_ajax_shopglut_bulk_remove_from_wishlist', array($this, 'shopglut_bulk_remove_from_wishlist'));
        add_action('wp_ajax_nopriv_shopglut_bulk_remove_from_wishlist', array($this, 'shopglut_bulk_remove_from_wishlist'));

          // QR Code functionality
        add_action('wp_ajax_shopglut_generate_qr_code', array($this, 'shopglut_generate_qr_code'));
        add_action('wp_ajax_nopriv_shopglut_generate_qr_code', array($this, 'shopglut_generate_qr_code'));


        // Set a transient when a user logs in to potentially merge guest wishlist data
        add_action( 'wp_login', [ $this, 'shopglut_set_merge_wishlist_transient' ], 10, 2 );
        add_action('shopglut_daily_guest_cleanup', array($this, 'shopglut_cleanup_old_guest_products'));



        // Register wishlist buttons AFTER WooCommerce is fully initialized
        add_action( 'wp', [ $this, 'shopglut_register_wishlist_buttons' ] );


        // Only initialize account page functionality if pro version is not active
        if ( isset( $this->enhancements['wishlist-page-account-page'] ) && 
             $this->enhancements['wishlist-page-account-page'] === '1' &&
             (!defined('SHOPGLUT_WISHLIST_PRO') || (defined('SHOPGLUT_WISHLIST_PRO') && SHOPGLUT_WISHLIST_PRO !== true)) ) {
            
            // Add Wishlist to My Account Page
            add_filter( 'woocommerce_account_menu_items', [ $this, 'add_my_account_menu_item' ] );
            add_action( 'init', [ $this, 'add_my_account_endpoint' ] );
            
            // Set dynamic endpoint and hook for content
            $endpoint = sanitize_title( $this->enhancements['wishlist-page-account-page-name'] ?? 'my-wishlist' );
            add_action( "woocommerce_account_{$endpoint}_endpoint", [ $this, 'my_account_wishlist_content' ] );
            
            // AJAX handlers for account page
            add_action( 'wp_ajax_load_account_wishlist_content', [ $this, 'load_account_wishlist_content' ] );
            add_action( 'wp_ajax_nopriv_load_account_wishlist_content', [ $this, 'load_account_wishlist_content' ] );
        }

        if (!defined('SHOPGLUT_WISHLIST_PRO') || (defined('SHOPGLUT_WISHLIST_PRO') && SHOPGLUT_WISHLIST_PRO !== true)) {
                // Register shortcode for wishlist count
                add_shortcode('shopglut_wishlist_count', array($this, 'wishlist_count_shortcode'));
                
                // Add AJAX handlers
                add_action('wp_ajax_update_wishlist_count', array($this, 'update_wishlist_count'));
                add_action('wp_ajax_nopriv_update_wishlist_count', array($this, 'update_wishlist_count'));
                
                // Add menu item filter
                add_filter('wp_nav_menu_items', array($this, 'add_wishlist_count_to_menu'), 10, 2);
                add_action('wp_head', array($this, 'menu_counter_custom_styles'));
            }
                      
            // Add custom styles to head
        
    
    }

    private function generate_cron_token() {
        return bin2hex( random_bytes( 16 ) );
    }

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}