<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistTechnicalImprovements {
    
    /**
     * ==========================================
     * CACHE PLUGIN COMPATIBILITY
     * ==========================================
     */
    
    /**
     * Initialize cache compatibility
     */
    public function init_cache_compatibility() {
        // WP Rocket compatibility
        add_filter('rocket_cache_dynamic_cookies', [$this, 'add_wishlist_cookies_to_wp_rocket']);
        add_filter('rocket_cache_reject_uri', [$this, 'exclude_wishlist_pages_from_cache']);
        
        // W3 Total Cache compatibility
        add_filter('w3tc_can_cache', [$this, 'w3tc_cache_compatibility'], 10, 2);
        
        // WP Super Cache compatibility
        add_action('wp_cache_served_cache_file', [$this, 'wp_super_cache_compatibility']);
        
        // LiteSpeed Cache compatibility
        add_action('litespeed_cache_api_vary', [$this, 'litespeed_cache_compatibility']);
        
        // Autoptimize compatibility
        add_filter('autoptimize_filter_js_exclude', [$this, 'exclude_wishlist_js_from_autoptimize']);
        
        // Add cache-busting for wishlist content
        add_action('wp_enqueue_scripts', [$this, 'add_cache_busting_scripts']);
        
        // Add no-cache headers for wishlist AJAX
        add_action('wp_ajax_shopglut_toggle_wishlist', [$this, 'add_no_cache_headers'], 1);
        add_action('wp_ajax_nopriv_shopglut_toggle_wishlist', [$this, 'add_no_cache_headers'], 1);
    }
    
    public function add_wishlist_cookies_to_wp_rocket($cookies) {
        $wishlist_cookies = [
            'shopglutw_guest_user_id',
            'shopglut_wishlist_items',
            'shopglut_wishlist_count'
        ];
        return array_merge($cookies, $wishlist_cookies);
    }
    
    public function exclude_wishlist_pages_from_cache($uri) {
        $wishlist_page_id = $this->enhancements['wishlist-general-page'] ?? '';
        if ($wishlist_page_id) {
            $wishlist_url = str_replace(home_url(), '', get_permalink($wishlist_page_id));
            $uri[] = $wishlist_url;
        }
        $uri[] = '/wp-admin/admin-ajax.php.*shopglut.*';
        return $uri;
    }
    
    public function w3tc_cache_compatibility($can_cache, $buffer) {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        if (strpos($request_uri, 'shopglut') !== false) {
            return false;
        }
        return $can_cache;
    }
    
    public function wp_super_cache_compatibility() {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        if (isset($_COOKIE['shopglutw_guest_user_id']) || 
            strpos($request_uri, 'shopglut') !== false) {
            define('DONOTCACHEPAGE', true);
        }
    }
    
    public function litespeed_cache_compatibility() {
        if (isset($_COOKIE['shopglutw_guest_user_id'])) {
            do_action('litespeed_vary_add', 'shopglut_wishlist');
        }
    }
    
    public function exclude_wishlist_js_from_autoptimize($exclude) {
        return $exclude . ', shopglut-wishlist, wishlist-qr';
    }
    
    public function add_cache_busting_scripts() {
        $cache_version = get_option('shopglut_wishlist_cache_version', time());
        wp_localize_script('shopglut-wishlist', 'shopglut_cache', [
            'version' => $cache_version,
            'nonce' => wp_create_nonce('shopglut_cache_nonce')
        ]);
    }
    
    public function add_no_cache_headers() {
        nocache_headers();
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
    
    /**
     * ==========================================
     * GDPR COMPLIANCE
     * ==========================================
     */
    
    /**
     * Initialize GDPR compliance
     */
    public function init_gdpr_compliance() {
        // Add GDPR consent hooks
        add_action('wp_footer', [$this, 'add_gdpr_consent_modal']);
        add_action('wp_ajax_shopglut_gdpr_consent', [$this, 'handle_gdpr_consent']);
        add_action('wp_ajax_nopriv_shopglut_gdpr_consent', [$this, 'handle_gdpr_consent']);
        
        // Add data export/deletion hooks
        add_filter('wp_privacy_personal_data_exporters', [$this, 'register_wishlist_data_exporter']);
        add_filter('wp_privacy_personal_data_erasers', [$this, 'register_wishlist_data_eraser']);
        
        // Add privacy policy content
        add_action('admin_init', [$this, 'add_privacy_policy_content']);
        
        // Cookie consent integration
        add_action('wp_enqueue_scripts', [$this, 'enqueue_gdpr_scripts']);
    }
    
    public function add_gdpr_consent_modal() {
        if (!$this->gdpr_consent_required()) {
            return;
        }
        ?>
        <div id="shopglut-gdpr-modal" class="shopglut-gdpr-modal" style="display: none;">
            <div class="shopglut-gdpr-content">
                <h3><?php esc_html_e('Privacy & Cookies', 'shopglut'); ?></h3>
                <p><?php esc_html_e('We use cookies to remember your wishlist items and provide a better shopping experience. By continuing to use our wishlist feature, you consent to our use of cookies.', 'shopglut'); ?></p>
                <div class="shopglut-gdpr-actions">
                    <button id="shopglut-gdpr-accept" class="shopglut-gdpr-btn accept">
                        <?php esc_html_e('Accept', 'shopglut'); ?>
                    </button>
                    <button id="shopglut-gdpr-decline" class="shopglut-gdpr-btn decline">
                        <?php esc_html_e('Decline', 'shopglut'); ?>
                    </button>
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" target="_blank" class="shopglut-gdpr-policy">
                        <?php esc_html_e('Privacy Policy', 'shopglut'); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <style>
        .shopglut-gdpr-modal {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.9);
            z-index: 10000;
            padding: 20px;
        }
        
        .shopglut-gdpr-content {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .shopglut-gdpr-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .shopglut-gdpr-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .shopglut-gdpr-btn.accept {
            background: #4CAF50;
            color: white;
        }
        
        .shopglut-gdpr-btn.decline {
            background: #f44336;
            color: white;
        }
        
        .shopglut-gdpr-policy {
            color: #007cba;
            text-decoration: underline;
            padding: 10px;
        }
        
        @media (max-width: 768px) {
            .shopglut-gdpr-content {
                padding: 15px;
            }
            
            .shopglut-gdpr-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .shopglut-gdpr-btn {
                width: 100%;
                max-width: 200px;
            }
        }
        </style>
        <?php
    }
    
    public function handle_gdpr_consent() {
        check_ajax_referer('shopglut_gdpr_nonce', 'nonce');
        
        if (!isset($_POST['consent'])) {
            wp_send_json_error(['message' => __('Consent parameter is required.', 'shopglut')]);
            return;
        }
        
        $consent = sanitize_text_field(wp_unslash($_POST['consent']));
        
        if ($consent === 'accept') {
            setcookie('shopglut_gdpr_consent', 'accepted', time() + (365 * 24 * 60 * 60), '/');
            wp_send_json_success(['message' => __('Consent accepted', 'shopglut')]);
        } else {
            setcookie('shopglut_gdpr_consent', 'declined', time() + (365 * 24 * 60 * 60), '/');
            // Clear wishlist cookies
            setcookie('shopglutw_guest_user_id', '', time() - 3600, '/');
            wp_send_json_success(['message' => __('Consent declined', 'shopglut')]);
        }
    }
    
    public function register_wishlist_data_exporter($exporters) {
        $exporters['shopglut-wishlist'] = [
            'exporter_friendly_name' => __('Shopglut Wishlist Data', 'shopglut'),
            'callback' => [$this, 'export_wishlist_data']
        ];
        return $exporters;
    }
    
    public function register_wishlist_data_eraser($erasers) {
        $erasers['shopglut-wishlist'] = [
            'eraser_friendly_name' => __('Shopglut Wishlist Data', 'shopglut'),
            'callback' => [$this, 'erase_wishlist_data']
        ];
        return $erasers;
    }
    
    public function export_wishlist_data($email_address) {
        $user = get_user_by('email', $email_address);
        if (!$user) {
            return ['data' => [], 'done' => true];
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'shopglut_wishlist';
        
        $query = "SELECT * FROM $table WHERE wish_user_id = %d";
        $wishlist_data = $wpdb->get_row($wpdb->prepare($query, $user->ID)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared	

        
        $export_items = [];
        if ($wishlist_data) {
            $export_items[] = [
                'group_id' => 'shopglut-wishlist',
                'group_label' => __('Wishlist Data', 'shopglut'),
                'item_id' => 'wishlist-' . $user->ID,
                'data' => [
                    [
                        'name' => __('Product IDs', 'shopglut'),
                        'value' => $wishlist_data->product_ids
                    ],
                    [
                        'name' => __('Date Added', 'shopglut'),
                        'value' => $wishlist_data->product_added_time
                    ],
                    [
                        'name' => __('Notifications', 'shopglut'),
                        'value' => $wishlist_data->wishlist_notifications
                    ]
                ]
            ];
        }
        
        return ['data' => $export_items, 'done' => true];
    }
    
    public function erase_wishlist_data($email_address) {
        $user = get_user_by('email', $email_address);
        if (!$user) {
            return ['items_removed' => 0, 'items_retained' => 0, 'messages' => [], 'done' => true];
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'shopglut_wishlist';
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->delete($table, ['wish_user_id' => $user->ID]);
        
        return [
            'items_removed' => $result ? 1 : 0,
            'items_retained' => 0,
            'messages' => $result ? [__('Wishlist data erased.', 'shopglut')] : [],
            'done' => true
        ];
    }
    
    private function gdpr_consent_required() {
        return !isset($_COOKIE['shopglut_gdpr_consent']) && 
               !is_user_logged_in() && 
               (isset($this->enhancements['gdpr-compliance-enabled']) && $this->enhancements['gdpr-compliance-enabled'] === '1');
    }
    
    /**
     * ==========================================
     * RTL (RIGHT-TO-LEFT) SUPPORT
     * ==========================================
     */
    
    /**
     * Initialize RTL support
     */
    public function init_rtl_support() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
        add_filter('shopglut_wishlist_css_class', [$this, 'add_rtl_class']);
    }
    
    public function enqueue_rtl_styles() {
        if (is_rtl()) {
            wp_enqueue_style(
                'shopglut-wishlist-rtl',
                plugin_dir_url(__FILE__) . 'assets/css/wishlist-rtl.css',
                ['shopglut-wishlist'],
                '1.0.0'
            );
        }
    }
    
    public function add_rtl_class($classes) {
        if (is_rtl()) {
            $classes .= ' shopglut-rtl';
        }
        return $classes;
    }
    
    /**
     * ==========================================
     * PERFORMANCE OPTIMIZATIONS
     * ==========================================
     */
    
    /**
     * Initialize performance optimizations
     */
    public function init_performance_optimizations() {
        // Optimize database queries
        add_action('init', [$this, 'optimize_database_queries']);
        
        // Add object caching
        add_action('init', [$this, 'init_object_caching']);
        
        // Optimize script loading
        add_action('wp_enqueue_scripts', [$this, 'optimize_script_loading']);
        
        // Add resource hints
        add_action('wp_head', [$this, 'add_resource_hints']);
        
        // Optimize AJAX requests
        add_filter('shopglut_wishlist_ajax_response', [$this, 'optimize_ajax_response']);
    }
    
    public function optimize_database_queries() {
        // Add database indexes if they don't exist
        global $wpdb;
        
        $table = $wpdb->prefix . 'shopglut_wishlist';
        $show_index_query = "SHOW INDEX FROM $table WHERE Key_name = 'idx_user_id'";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching 
        $index_exists = $wpdb->get_results( $show_index_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	
        
        if (empty($index_exists)) {
            $create_index_query = "CREATE INDEX idx_user_id ON $table (wish_user_id)";
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
            $wpdb->query( $create_index_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching	
        }
    }
    
    public function init_object_caching() {
        // Cache wishlist counts
        add_filter('shopglut_wishlist_count', [$this, 'cache_wishlist_count'], 10, 2);
        
        // Cache product data
        add_filter('shopglut_wishlist_product_data', [$this, 'cache_product_data'], 10, 2);
    }
    
    public function cache_wishlist_count($count, $user_id) {
        $cache_key = 'shopglut_wishlist_count_' . $user_id;
        $cached_count = wp_cache_get($cache_key, 'shopglut_wishlist');
        
        if ($cached_count === false) {
            wp_cache_set($cache_key, $count, 'shopglut_wishlist', 300); // 5 minutes
            return $count;
        }
        
        return $cached_count;
    }
    
    public function optimize_script_loading() {
        // Only load scripts on pages that need them
        if (!$this->is_wishlist_page() && !$this->has_wishlist_buttons()) {
            return;
        }
        
        // Add async/defer attributes
        add_filter('script_loader_tag', [$this, 'add_async_defer_attributes'], 10, 2);
    }
    
    public function add_async_defer_attributes($tag, $handle) {
        if (strpos($handle, 'shopglut-wishlist') !== false) {
            return str_replace(' src', ' async defer src', $tag);
        }
        return $tag;
    }
    
    public function add_resource_hints() {
        // DNS prefetch for external services - Disabled for WordPress.org compliance
        // echo '<link rel="dns-prefetch" href="//api.qrserver.com">';
        // echo '<link rel="dns-prefetch" href="//quickchart.io">';
        
        // Preconnect to CDNs - Disabled for WordPress.org compliance  
        // echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        // echo '<link rel="preconnect" href="https://cdn.jsdelivr.net">';
    }
    
    /**
     * ==========================================
     * CORE WEB VITALS OPTIMIZATION
     * ==========================================
     */
    
    /**
     * Initialize Core Web Vitals optimization
     */
    public function init_core_web_vitals_optimization() {
        // Optimize Largest Contentful Paint (LCP)
        add_action('wp_head', [$this, 'optimize_lcp']);
        
        // Optimize First Input Delay (FID)
        add_action('wp_enqueue_scripts', [$this, 'optimize_fid']);
        
        // Optimize Cumulative Layout Shift (CLS)
        add_action('wp_head', [$this, 'optimize_cls']);
        
        // Add performance monitoring
        add_action('wp_footer', [$this, 'add_performance_monitoring']);
    }
    
    public function optimize_lcp() {
        
        // Optimize image loading
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_priority_hints'], 10, 3);
    }
    
    public function add_priority_hints($attr, $attachment, $size) {
        if ($this->is_wishlist_page()) {
            $attr['loading'] = 'eager';
            $attr['fetchpriority'] = 'high';
        }
        return $attr;
    }
    
    public function optimize_fid() {
        // Defer non-critical JavaScript
        wp_script_add_data('shopglut-wishlist', 'defer', true);
        
        // Use passive event listeners
        wp_add_inline_script('shopglut-wishlist', '
            document.addEventListener("DOMContentLoaded", function() {
                const buttons = document.querySelectorAll(".shopglut-wishlist-btn");
                buttons.forEach(btn => {
                    btn.addEventListener("click", handleWishlistClick, { passive: true });
                });
            });
        ');
    }
    
    public function optimize_cls() {
        // Add size hints for dynamic content
        ?>
        <style>
        .shopglut-wishlist-container {
            min-height: 200px;
        }
        
        .shopglut-wishlist-table {
            width: 100%;
            table-layout: fixed;
        }
        
        .shopglut-wishlist-button {
            width: 120px;
            height: 40px;
        }
        
        .shopglut-wishlist-loading {
            width: 20px;
            height: 20px;
        }
        </style>
        <?php
    }
    
    public function add_performance_monitoring() {
        if (!$this->is_wishlist_page()) {
            return;
        }
        
        ?>
        <script>
        // Core Web Vitals monitoring
        function measureWebVitals() {
            if ('PerformanceObserver' in window) {
                // Measure LCP
                new PerformanceObserver((entryList) => {
                    const entries = entryList.getEntries();
                    const lastEntry = entries[entries.length - 1];
                    console.log('LCP:', lastEntry.startTime);
                }).observe({entryTypes: ['largest-contentful-paint']});
                
                // Measure FID
                new PerformanceObserver((entryList) => {
                    const entries = entryList.getEntries();
                    entries.forEach(entry => {
                        console.log('FID:', entry.processingStart - entry.startTime);
                    });
                }).observe({entryTypes: ['first-input']});
                
                // Measure CLS
                new PerformanceObserver((entryList) => {
                    let clsValue = 0;
                    entryList.getEntries().forEach(entry => {
                        if (!entry.hadRecentInput) {
                            clsValue += entry.value;
                        }
                    });
                    console.log('CLS:', clsValue);
                }).observe({entryTypes: ['layout-shift']});
            }
        }
        
        // Run monitoring on page load
        window.addEventListener('load', measureWebVitals);
        </script>
        <?php
    }
    
    /**
     * ==========================================
     * LAZY LOADING FOR WISHLIST IMAGES
     * ==========================================
     */
    
    /**
     * Initialize lazy loading
     */
    public function init_lazy_loading() {
        // Add lazy loading to wishlist images
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading_attributes'], 10, 3);
        
        // Add intersection observer for lazy loading
        add_action('wp_footer', [$this, 'add_lazy_loading_script']);
        
        // Add placeholder images
        add_filter('shopglut_wishlist_product_image', [$this, 'add_image_placeholder'], 10, 2);
    }
    
    public function add_lazy_loading_attributes($attr, $attachment, $size) {
        if ($this->is_wishlist_context()) {
            $attr['loading'] = 'lazy';
            $attr['data-src'] = $attr['src'];
            $attr['src'] = $this->get_placeholder_image();
            $attr['class'] = ($attr['class'] ?? '') . ' shopglut-lazy-image';
        }
        return $attr;
    }
    
    public function add_lazy_loading_script() {
        if (!$this->is_wishlist_page()) {
            return;
        }
        
        ?>
        <script>
        // Intersection Observer for lazy loading
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('.shopglut-lazy-image');
            
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('shopglut-lazy-image');
                            img.classList.add('shopglut-lazy-loaded');
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });
                
                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback for older browsers
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                    img.classList.remove('shopglut-lazy-image');
                    img.classList.add('shopglut-lazy-loaded');
                });
            }
        });
        </script>
        
        <style>
        .shopglut-lazy-image {
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .shopglut-lazy-loaded {
            opacity: 1;
        }
        
        .shopglut-lazy-image::before {
            content: '';
            display: block;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        </style>
        <?php
    }
    
    public function get_placeholder_image() {
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f0f0f0"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999">Loading...</text>
            </svg>'
        );
    }
    
    /**
     * ==========================================
     * HELPER METHODS
     * ==========================================
     */
    
    private function is_wishlist_page() {
        $wishlist_page_id = $this->enhancements['wishlist-general-page'] ?? '';
        return $wishlist_page_id && is_page($wishlist_page_id);
    }
    
    private function has_wishlist_buttons() {
        return is_shop() || is_product() || is_product_category() || is_product_tag();
    }
    
    private function is_wishlist_context() {
        return $this->is_wishlist_page() || 
               (isset($_SERVER['REQUEST_URI']) && strpos(esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])), 'shopglut') !== false) ||
               // phpcs:ignore WordPress.Security.NonceVerification.Missing
               (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['action']) && strpos(sanitize_text_field(wp_unslash($_POST['action'])), 'shopglut') !== false);
    }
    
    public function enqueue_gdpr_scripts() {
       
    }
    
    public function add_privacy_policy_content() {
        if (function_exists('wp_add_privacy_policy_content')) {
            wp_add_privacy_policy_content(
                'Shopglut Wishlist',
                $this->get_privacy_policy_content()
            );
        }
    }
    
    private function get_privacy_policy_content() {
        return '
        <h3>Wishlist Data Collection</h3>
        <p>Our wishlist feature collects the following information:</p>
        <ul>
            <li>Products you add to your wishlist</li>
            <li>Date and time when items were added</li>
            <li>Your IP address (for guest users)</li>
            <li>Browser cookies to maintain your session</li>
        </ul>
        
        <h3>How We Use This Data</h3>
        <p>We use your wishlist data to:</p>
        <ul>
            <li>Maintain your wishlist across sessions</li>
            <li>Send notifications about price changes (if enabled)</li>
            <li>Improve our product recommendations</li>
            <li>Analyze popular products</li>
        </ul>
        
        <h3>Data Retention</h3>
        <p>Wishlist data is retained for:</p>
        <ul>
            <li>Registered users: Until account deletion</li>
            <li>Guest users: 30 days of inactivity</li>
        </ul>
        
        <h3>Your Rights</h3>
        <p>You have the right to:</p>
        <ul>
            <li>Access your wishlist data</li>
            <li>Delete your wishlist data</li>
            <li>Opt-out of notifications</li>
            <li>Request data portability</li>
        </ul>
        ';
    }
}