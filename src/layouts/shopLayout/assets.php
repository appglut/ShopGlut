<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Asset registration for shopLayout
 */

class ShopLayoutAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // Handle shortcode dynamic CSS
        add_action('wp', [$this, 'handle_shortcode_css']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/style.css')) {
            wp_enqueue_style(
                'shopLayout-style',
                $plugin_url . '/assets/css/style.css',
                [],
                filemtime(__DIR__ . '/assets/css/style.css')
            );

            // Add wishlist dynamic styles for notifications
            $this->add_wishlist_dynamic_styles();
        }

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/layouts.css')) {
            wp_enqueue_style(
                'shopLayout-layouts-style',
                $plugin_url . '/assets/css/layouts.css',
                [],
                filemtime(__DIR__ . '/assets/css/layouts.css')
            );
        }


         // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/style-front.css')) {
            wp_enqueue_style(
                'shopLayout-front-style',
                $plugin_url . '/assets/css/style-front.css',
                [],
                filemtime(__DIR__ . '/assets/css/style-front.css')
            );
        }



        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'shopLayout-script',
                $plugin_url . '/assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );

            // Localize script with AJAX URL and nonces
            $wishlist_options = get_option('agshopglut_wishlist_options', array());
            wp_localize_script('shopLayout-script', 'shopglut_shop_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopLayouts_nonce'),
                'wishlist_nonce' => wp_create_nonce('shopglut_wishlist_nonce'),
                'cart_url' => wc_get_cart_url(),
                'notification_type' => $wishlist_options['wishlist-general-notification'] ?? 'notification-off',
                'notification_position' => $wishlist_options['wishlist-side-notification-appear'] ?? 'bottom-right',
                'side_notification_effect' => $wishlist_options['wishlist-side-notification-effect'] ?? 'fade-in-out',
                'popup_notification_effect' => $wishlist_options['wishlist-popup-notification-effect'] ?? 'fade-in-out',
            ));
        }
    }

    /**
     * Add wishlist dynamic styles for notifications
     */
    private function add_wishlist_dynamic_styles() {
        if (class_exists('\Shopglut\enhancements\wishlist\dynamicStyle')) {
            $wishlist_dynamic_style = new \Shopglut\enhancements\wishlist\dynamicStyle();
            $wishlist_dynamic_css = $wishlist_dynamic_style->dynamicCss();
            if (!empty($wishlist_dynamic_css)) {
                wp_add_inline_style('shopLayout-style', $wishlist_dynamic_css);
            }
        }
    }

    public function enqueue_admin_assets($hook) {
        $plugin_url = plugin_dir_url(__FILE__);

        // Only load on shop layout editor page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        $is_shop_layout_editor = isset($_GET['page']) && $_GET['page'] === 'shopglut_layouts' && isset($_GET['editor']) && $_GET['editor'] === 'shop';

         // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/style.css')) {
            wp_enqueue_style(
                'shopLayout-style',
                $plugin_url . '/assets/css/style.css',
                [],
                filemtime(__DIR__ . '/assets/css/style.css')
            );
        }

        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/layouts.css')) {
            wp_enqueue_style(
                'shopLayout-layouts-style',
                $plugin_url . '/assets/css/layouts.css',
                [],
                filemtime(__DIR__ . '/assets/css/layouts.css')
            );
        }


         // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/css/style-front.css')) {
            wp_enqueue_style(
                'shopLayout-front-style',
                $plugin_url . '/assets/css/style-front.css',
                [],
                filemtime(__DIR__ . '/assets/css/style-front.css')
            );
        }

        // Enqueue template1 module styles (wishlist, comparison, etc.) for admin preview
        if (file_exists(__DIR__ . '/assets/css/template1-modules.css')) {
            wp_enqueue_style(
                'shopLayout-template1-modules',
                $plugin_url . 'assets/css/template1-modules.css',
                [],
                filemtime(__DIR__ . '/assets/css/template1-modules.css')
            );
        }

        // Enqueue layout data converter on editor page
        if ($is_shop_layout_editor && file_exists(__DIR__ . '/assets/js/shopLayouts.js')) {
            wp_enqueue_script(
                'shopglut-shoplayout-data-converter',
                $plugin_url . 'assets/js/shopLayouts.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/js/shopLayouts.js'),
                true
            );

            // Localize script with ajax URL and other data
            wp_localize_script('shopglut-shoplayout-data-converter', 'shopglut_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_admin_nonce')
            ));
        }

        // Enqueue select archive page fix script
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe check for admin page parameter
        if (isset($_GET['page']) && $_GET['page'] === 'shopglut_layouts') {
            if (file_exists(__DIR__ . '/assets/js/select-archive-fix.js')) {
                wp_enqueue_script(
                    'shopglut-select-archive-fix',
                    $plugin_url . 'assets/js/select-archive-fix.js',
                    ['jquery'],
                    filemtime(__DIR__ . '/assets/js/select-archive-fix.js'),
                    true
                );
            }
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page query parameters for editor functionality
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'shopglut_layouts' && isset( $_GET['editor'] ) && $_GET['editor'] === 'shop' && isset( $_GET['layout_id'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page query parameters for editor functionality
		$layout_id = absint( $_GET['layout_id'] ); // Sanitize the layout_id

		$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
		$dynamic_css = $dynamic_style->dynamicCss( $layout_id ); // Use the layout_id

		if ( ! empty( $dynamic_css ) ) {
			wp_add_inline_style( 'shopLayout-style', $dynamic_css );
		}
	}
    }
    
    /**
     * Handle shopLayout shortcode dynamic CSS
     */
    public function handle_shortcode_css() {
        if (!is_singular()) {
            return;
        }
        
        global $post;
        
        if (!$post || !isset($post->post_content)) {
            return;
        }

        // Extract shortcodes with attributes
        $shortcode_pattern = get_shortcode_regex(['shopg_shop_layout']);
        if (preg_match_all('/' . $shortcode_pattern . '/s', $post->post_content, $matches)) {
            foreach ($matches[3] as $shortcode_attrs) {
                $atts = shortcode_parse_atts($shortcode_attrs);
                if (isset($atts['id'])) {
                    $shortcode_id = absint($atts['id']);

                    if (class_exists('\Shopglut\layouts\shopLayout\dynamicStyle')) {
                        $dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
                        $dynamic_css = $dynamic_style->dynamicCss($shortcode_id);

                        if (!empty($dynamic_css)) {
                            wp_add_inline_style('shopglut-main', $dynamic_css);
                        }
                    }
                }
            }
        }
    }
}

// Initialize the assets class
new ShopLayoutAssets();
