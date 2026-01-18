<?php
namespace Shopglut;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ShopGlutRegisterScripts {

    private $load_assets = false;
    public $wishOptions;

    public function __construct() {

        $this->wishOptions = get_option( 'agshopglut_wishlist_options' );
        
        // Include all module asset registration files
        $this->include_module_assets();

        add_action( 'admin_init', [ $this, 'shopglut_check_admin_pages' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'shopglut_conditionally_enqueue_assets' ], 9999 );
        add_action( 'admin_enqueue_scripts', [ $this, 'shopglut_conditionally_enqueue_assets' ], 9999 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueTemplatePreviewAssets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueEnhancementTemplateAssets' ] );
    }
    
    /**
     * Include all module asset registration files
     */
    private function include_module_assets() {
        $module_assets = [
            // BusinessSolutions
           // SHOPGLUT_PATH . 'src/BusinessSolutions/EmailCustomizer/assets.php',
         //   SHOPGLUT_PATH . 'src/BusinessSolutions/PdfInvoices/assets.php',
            
            // Enhancements
         // SHOPGLUT_PATH . 'src/enhancements/Comparison/assets.php',
            SHOPGLUT_PATH . 'src/enhancements/Filters/assets.php',
            SHOPGLUT_PATH . 'src/enhancements/ProductBadges/assets.php',
            SHOPGLUT_PATH . 'src/enhancements/ProductComparison/assets.php',
            SHOPGLUT_PATH . 'src/enhancements/ProductQuickView/assets.php',

            //SHOPGLUT_PATH . 'src/enhancements/ProductSwatches/assets.php',
            SHOPGLUT_PATH . 'src/enhancements/wishlist/assets.php',
            
            // Layouts
            SHOPGLUT_PATH . 'src/layouts/accountPage/assets.php',
            SHOPGLUT_PATH . 'src/layouts/cartPage/assets.php',
            SHOPGLUT_PATH . 'src/layouts/checkoutPage/assets.php',
            SHOPGLUT_PATH . 'src/layouts/shopLayout/assets.php',
            SHOPGLUT_PATH . 'src/layouts/singleProduct/assets.php',
            SHOPGLUT_PATH . 'src/layouts/orderCompletePage/assets.php',
            
            // Showcases
             SHOPGLUT_PATH . 'src/showcases/Accordions/assets.php',
             SHOPGLUT_PATH . 'src/showcases/ShopBanner/assets.php',
            // SHOPGLUT_PATH . 'src/showcases/Gallery/assets.php',
            // SHOPGLUT_PATH . 'src/showcases/MegaMenu/assets.php',
             SHOPGLUT_PATH . 'src/showcases/Gallery/gallery_assets.php',
             SHOPGLUT_PATH . 'src/showcases/Sliders/slider_assets.php',
             SHOPGLUT_PATH . 'src/showcases/Tabs/assets.php',

            // Tools
             SHOPGLUT_PATH . 'src/tools/productCustomField/assets.php',
            // SHOPGLUT_PATH . 'src/tools/loginRegister/assets.php',
            // SHOPGLUT_PATH . 'src/tools/miniCart/assets.php',
             SHOPGLUT_PATH . 'src/tools/wooTemplates/assets.php',
            // SHOPGLUT_PATH . 'src/tools/shortcodeShowcase/assets.php',
            // SHOPGLUT_PATH . 'src/tools/wooTemplates/assets.php',
            // SHOPGLUT_PATH . 'src/tools/WooThemes/assets.php'
        ];
        
        foreach ($module_assets as $asset_file) {
            if (file_exists($asset_file)) {
                require_once $asset_file;
            }
        }
    }

    public function shopglut_check_admin_pages() {
        // Security check for admin pages
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Verify nonce for admin pages that modify data - FIXED: Proper sanitization and unslashing
        if (isset($_GET['action']) && !isset($_GET['_wpnonce'])) {
            return;
        }
        
        if (isset($_GET['action']) && isset($_GET['_wpnonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
            if (!wp_verify_nonce($nonce, 'shopglut_admin_nonce')) {
                return;
            }
        }
        
        $this->load_assets = true;
    }

    public function shopglut_conditionally_enqueue_assets() {
        // Always load Font Awesome (safe to load globally)
        wp_enqueue_style( 'shopglut-fontawesome', SHOPGLUT_URL . 'src/library/model/assets/css/all.min.css', [], SHOPGLUT_VERSION, 'all' );

        // Load main assets
        $this->shopglut_plugin_css();
        $this->shopglut_plugin_js();

       if ( is_singular() ) {
		global $post;

		// Extract shortcodes with attributes
		$shortcode_pattern = get_shortcode_regex( [ 'shopg_shop_layout' ] );
		if ( preg_match_all( '/' . $shortcode_pattern . '/s', $post->post_content, $matches ) ) {
			foreach ( $matches[3] as $shortcode_attrs ) {
				$atts = shortcode_parse_atts( $shortcode_attrs );
				if ( isset( $atts['id'] ) ) {
					$shortcode_id = absint( $atts['id'] );

					$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
					$dynamic_css = $dynamic_style->dynamicCss( $shortcode_id );

					if ( ! empty( $dynamic_css ) ) {
						wp_add_inline_style( 'shopglut-main', $dynamic_css );
					}
				}
			}
		}
	}
    }

    public function shopglut_plugin_css() {
        // Load main plugin stylesheet - use filemtime for cache busting
        $style_css_path = SHOPGLUT_PATH . 'global-assets/css/style.css';
        wp_enqueue_style(
            'shopglut-main',
            SHOPGLUT_URL . 'global-assets/css/style.css',
            [],
            file_exists( $style_css_path ) ? filemtime( $style_css_path ) : SHOPGLUT_VERSION
        );

        // Load centralized notification stylesheet - use filemtime for cache busting
        $notification_css_path = SHOPGLUT_PATH . 'global-assets/css/shopglut-notification.css';
        wp_enqueue_style(
            'shopglut-notification',
            SHOPGLUT_URL . 'global-assets/css/shopglut-notification.css',
            [],
            file_exists( $notification_css_path ) ? filemtime( $notification_css_path ) : SHOPGLUT_VERSION
        );
    }

    public function shopglut_plugin_js() {
        // Core dependencies
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-draggable' );

        // Load centralized notification utility - must load before other scripts that use it
        $notification_script_path = SHOPGLUT_PATH . 'global-assets/js/shopglut-notification.js';
        wp_enqueue_script(
            'shopglut-notification',
            SHOPGLUT_URL . 'global-assets/js/shopglut-notification.js',
            [],
            file_exists( $notification_script_path ) ? filemtime( $notification_script_path ) : SHOPGLUT_VERSION,
            true
        );

        $admin_script_path = SHOPGLUT_PATH . 'global-assets/js/shopglut-admin.js';
        wp_enqueue_script(
                'shopglut-admin-script',
                SHOPGLUT_URL . 'global-assets/js/shopglut-admin.js',
                ['jquery', 'shopglut-notification'],
                file_exists( $admin_script_path ) ? filemtime( $admin_script_path ) : SHOPGLUT_VERSION,
                true
            );
    }

    /**
     * Enqueue CSS and JS for template preview functionality
     * Used by: Single Product, Cart, Order Complete, My Account template galleries, and all showcase modules
     */
    public function enqueueTemplatePreviewAssets( $hook ) {
        // Only load on the templates page
        $page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
        if ( $page !== 'shopglut_layouts' && $page !== 'shopglut_showcases' ) {
            return;
        }

        // Verify nonce for admin operations
        if ( isset( $_GET['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_admin_action' ) ) {
            return;
        }

        // Enqueue centralized CSS
        wp_enqueue_style(
            'shopglut-template-html-demo',
            SHOPGLUT_URL . 'global-assets/css/template-html-demo.css',
            array(),
            SHOPGLUT_VERSION
        );

        // Enqueue centralized JS
        wp_enqueue_script(
            'shopglut-template-html-demo',
            SHOPGLUT_URL . 'global-assets/js/template-html-demo.js',
            array(),
            SHOPGLUT_VERSION,
            true
        );

        // Enqueue shared tabs script - universal tabs handler for all templates
        // Works in modals, AJAX loaded content, and regular pages
        $tabs_script_path = SHOPGLUT_PATH . 'src/layouts/singleProduct/assets/singleProduct-tabs.js';
        $tabs_script_url = SHOPGLUT_URL . 'src/layouts/singleProduct/assets/singleProduct-tabs.js';

        wp_enqueue_script(
            'shopglut-tabs',
            $tabs_script_url,
            array(),
            file_exists( $tabs_script_path ) ? filemtime( $tabs_script_path ) : time(),
            true
        );

        // Localize script with ajax URL and nonce for template demo functionality
        wp_localize_script('shopglut-template-html-demo', 'shopglut_admin_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_admin_nonce')
        ));
    }

    /**
     * Enqueue CSS and JS for enhancement template galleries
     * Used by: Product Badges, Product Comparison, Product Quick View
     */
    public function enqueueEnhancementTemplateAssets( $hook ) {
        // Only load on the enhancements page
        $page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
        if ( $page !== 'shopglut_enhancements' ) {
            return;
        }

        // Verify nonce for admin operations
        if ( isset( $_GET['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'shopglut_admin_action' ) ) {
            return;
        }

        // Enqueue centralized CSS (same template gallery styles)
        wp_enqueue_style(
            'shopglut-template-html-demo',
            SHOPGLUT_URL . 'global-assets/css/template-html-demo.css',
            array(),
            SHOPGLUT_VERSION
        );

        // Enqueue centralized JS
        wp_enqueue_script(
            'shopglut-template-html-demo',
            SHOPGLUT_URL . 'global-assets/js/template-html-demo.js',
            array(),
            SHOPGLUT_VERSION,
            true
        );

        // Enqueue shared tabs script - universal tabs handler for all templates
        // Works in modals, AJAX loaded content, and regular pages
        $tabs_script_path = SHOPGLUT_PATH . 'src/layouts/singleProduct/assets/singleProduct-tabs.js';
        $tabs_script_url = SHOPGLUT_URL . 'src/layouts/singleProduct/assets/singleProduct-tabs.js';

        wp_enqueue_script(
            'shopglut-tabs',
            $tabs_script_url,
            array(),
            file_exists( $tabs_script_path ) ? filemtime( $tabs_script_path ) : time(),
            true
        );

        // Localize script with ajax URL and nonce for template demo functionality
        wp_localize_script('shopglut-template-html-demo', 'shopglut_admin_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_admin_nonce')
        ));
    }

    /**
     * Securely get layout_id with proper validation - FIXED: Added nonce verification
     */
    private function shopglut_get_secure_layout_id() {
        // Only get layout_id in admin context with proper permissions
        if (!is_admin() || !current_user_can('manage_options')) {
            return 0;
        }

        // FIXED: Added nonce verification for GET requests
        if (isset($_GET['page']) && isset($_GET['layout_id'])) {
            // For layout editing pages, we should verify nonce if it's provided
            if (isset($_GET['_wpnonce'])) {
                $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
                if (!wp_verify_nonce($nonce, 'shopglut_admin_nonce')) {
                    return 0;
                }
            }
        }

        // Verify page context - FIXED: Added proper sanitization
        if (!isset($_GET['page'])) {
            return 0;
        }
        
        $page = sanitize_text_field(wp_unslash($_GET['page']));
        if (!in_array($page, ['shopglut_layouts', 'shopglut_enhancements', 'shopglut_showcases'], true)) {
            return 0;
        }

        // Get and validate layout_id - FIXED: Added proper sanitization
        $layout_id = isset($_GET['layout_id']) ? absint(wp_unslash($_GET['layout_id'])) : 0;
        
        // Additional validation: check if layout_id exists in database
        if ($layout_id > 0 && !$this->shopglut_layout_exists($layout_id)) {
            return 0;
        }

        return $layout_id;
    }

    /**
     * Check if layout exists in database - FIXED: Proper prepared statements
     */
    private function shopglut_layout_exists($layout_id) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'shopglut_single_product_layout';

        // Check cache first
        $cache_key = "shopglut_layout_exists_{$layout_id}";
        $cached_result = wp_cache_get( $cache_key, 'shopglut_layouts' );

        if ( false !== $cached_result ) {
            return (bool) $cached_result;
        }

        // FIXED: Check if table exists with proper prepared statement
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table existence check
        $table_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = %s AND table_name = %s",
                DB_NAME,
                $table_name
            )
        );

        if (!$table_exists) {
            return false;
        }

        // FIXED: Proper prepared statement for counting records
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table query
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}shopglut_single_product_layout WHERE id = %d",
                $layout_id
            )
        );

        $layout_exists = $exists > 0;

        // Cache for 15 minutes
        wp_cache_set( $cache_key, $layout_exists, 'shopglut_layouts', 15 * MINUTE_IN_SECONDS );

        return $layout_exists;
    }

  
    /**
     * Validate admin page access with proper nonce handling
     */
    private function shopglut_validate_admin_access() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return false;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Page parameter validation only, no data modification
        if (!isset($_GET['page'])) {
            return false;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Page parameter validation only, no data modification
        $page = sanitize_text_field(wp_unslash($_GET['page']));
        $allowed_pages = ['shopglut_layouts', 'shopglut_enhancements', 'shopglut_showcases'];
        
        if (!in_array($page, $allowed_pages, true)) {
            return false;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checking for action existence before nonce verification
        $has_get_action = isset($_GET['action']);
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checking for action existence before nonce verification  
        $has_post_action = isset($_POST['action']);
        
        if ($has_get_action || $has_post_action) {
            $nonce_field = '_wpnonce';
            
            if ($has_post_action) {
                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification performed below
                if (!isset($_POST[$nonce_field])) {
                    return false;
                }
                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- This is the nonce verification
                $nonce = sanitize_text_field(wp_unslash($_POST[$nonce_field]));
            } else {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification performed below
                if (!isset($_GET[$nonce_field])) {
                    return false;
                }
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is the nonce verification
                $nonce = sanitize_text_field(wp_unslash($_GET[$nonce_field]));
            }
            
            if (!wp_verify_nonce($nonce, 'shopglut_admin_nonce')) {
                return false;
            }
        }

        return true;
    }

    public static function get_instance() {
        static $instance = null;

        if ( is_null( $instance ) ) {
            $instance = new self();
        }

        return $instance;
    }
}