<?php
namespace Shopglut\showcases\ShopBanner;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ShopBannerFrontend {

    public function __construct() {
        // Initialize frontend functionality
        add_action('wp_footer', [$this, 'render_banner_container'], 1);
        add_action('wp_head', [$this, 'add_inline_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Hook to display banner on page load
        add_action('wp_footer', [$this, 'maybe_display_shopbanner'], 999);
    }

    /**
     * Render the container for shopbanner modals
     */
    public function render_banner_container() {
        echo '<div id="shopglut-shopbanner-modal-container"></div>';
    }

    /**
     * Add inline styles for shopbanner
     */
    public function add_inline_styles() {
        ?>
        <style>
            /* Shop Banner Premium Modal Styles */
            .shopglut-shopbanner-open {
                overflow: hidden !important;
            }

            /* Premium Overlay Background */
            .shopglut-banner-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.85);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                z-index: 999998;
                opacity: 0;
                visibility: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .shopglut-banner-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            /* Premium Banner Container */
            .shopglut-banner-container {
                position: fixed;
                top: 50%;
                left: 50%;
                z-index: 999999;
                max-width: 90vw;
                max-height: 90vh;
                opacity: 0;
                visibility: hidden;
                transform: translate(-50%, -50%) scale(0.7) rotateX(-10deg);
                transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
                overflow: visible;
            }

            .shopglut-banner-container.active {
                opacity: 1;
                visibility: visible;
                transform: translate(-50%, -50%) scale(1) rotateX(0deg);
                box-shadow: 0 30px 100px rgba(0, 0, 0, 0.5);
            }

            /* Premium Close Button */
            .shopglut-banner-close {
                position: absolute !important;
                top: 15px !important;
                right: 15px !important;
                width: 36px !important;
                height: 36px !important;
                background: rgba(255, 255, 255, 0.95) !important;
                border: 2px solid #ffffff !important;
                border-radius: 50% !important;
                cursor: pointer !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                z-index: 10000 !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
                transition: all 0.3s ease !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .shopglut-banner-close:hover {
                background: #ef4444 !important;
                border-color: #ef4444 !important;
                color: #ffffff !important;
                transform: rotate(90deg) scale(1.1) !important;
                box-shadow: 0 6px 25px rgba(239, 68, 68, 0.4) !important;
            }

            .shopglut-banner-close button {
                background: none !important;
                border: none !important;
                cursor: pointer !important;
                color: #374151 !important;
                font-size: 20px !important;
                font-weight: bold !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                line-height: 1 !important;
            }

            .shopglut-banner-close button:hover {
                color: #ffffff !important;
            }

            /* Premium Banner Body */
            .shopglut-banner-body {
                text-align: center;
                position: relative;
                background: #ffffff;
                border-radius: 20px;
                padding: 40px;
                box-shadow:
                    0 20px 60px rgba(0, 0, 0, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
                transform: translateZ(0);
                animation: premiumFloat 6s ease-in-out infinite;
                overflow: visible;
            }

            @keyframes premiumFloat {
                0%, 100% {
                    transform: translateY(0px) translateZ(0);
                }
                50% {
                    transform: translateY(-10px) translateZ(0);
                }
            }

            /* Premium Button Styling */
            .shopglut-banner-button {
                position: relative;
                display: inline-block;
                padding: 16px 40px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #ffffff;
                text-decoration: none;
                border-radius: 50px;
                font-weight: 600;
                font-size: 16px;
                letter-spacing: 0.5px;
                border: none;
                cursor: pointer;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow:
                    0 8px 30px rgba(102, 126, 234, 0.4),
                    0 2px 10px rgba(0, 0, 0, 0.1);
                transform: translateY(0px) scale(1);
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            }

            .shopglut-banner-button:before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                transition: left 0.6s ease;
            }

            .shopglut-banner-button:hover {
                transform: translateY(-2px) scale(1.05);
                box-shadow:
                    0 12px 40px rgba(102, 126, 234, 0.5),
                    0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .shopglut-banner-button:hover:before {
                left: 100%;
            }

            .shopglut-banner-button:active {
                transform: translateY(-1px) scale(1.02);
            }

            /* Premium Text Styling */
            .shopglut-banner-title {
                font-size: 32px;
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-shadow: none;
                animation: premiumGlow 3s ease-in-out infinite;
            }

            @keyframes premiumGlow {
                0%, 100% {
                    filter: brightness(1);
                }
                50% {
                    filter: brightness(1.1);
                }
            }

            .shopglut-banner-description {
                font-size: 18px;
                line-height: 1.6;
                color: #4b5563;
                margin-bottom: 30px;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Premium Image Styling */
            .shopglut-banner-image {
                border-radius: 15px;
                box-shadow:
                    0 10px 30px rgba(0, 0, 0, 0.2),
                    0 0 0 1px rgba(255, 255, 255, 0.1);
                margin-bottom: 25px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                transform: scale(1);
            }

            .shopglut-banner-image:hover {
                transform: scale(1.05);
                box-shadow:
                    0 15px 40px rgba(0, 0, 0, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.2);
            }

            /* Premium Close Animation */
            .shopglut-banner-container.closing {
                animation: premiumClose 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            .shopglut-banner-overlay.closing {
                animation: overlayClose 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            @keyframes premiumClose {
                0% {
                    opacity: 1;
                    visibility: visible;
                    transform: translate(-50%, -50%) scale(1) rotateX(0deg);
                }
                50% {
                    transform: translate(-50%, -50%) scale(1.1) rotateX(5deg);
                }
                100% {
                    opacity: 0;
                    visibility: hidden;
                    transform: translate(-50%, -50%) scale(0.7) rotateX(15deg);
                }
            }

            @keyframes overlayClose {
                0% {
                    opacity: 1;
                    visibility: visible;
                }
                100% {
                    opacity: 0;
                    visibility: hidden;
                }
            }

            @keyframes wrapperClose {
                0% {
                    opacity: 1;
                    visibility: visible;
                    transform: translate(-50%, -50%) scale(1) rotateX(0deg);
                }
                50% {
                    transform: translate(-50%, -50%) scale(1.05) rotateX(5deg);
                }
                100% {
                    opacity: 0;
                    visibility: hidden;
                    transform: translate(-50%, -50%) scale(0.7) rotateX(15deg);
                }
            }

            .shopglut-banner-close.closing {
                animation: premiumClose 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            .shopglut-banner-overlay.closing {
                animation: overlayClose 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            div[style*="position: fixed"][style*="top: 50%"][style*="left: 50%"].closing {
                animation: wrapperClose 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .shopglut-banner-body {
                    padding: 30px 25px;
                    border-radius: 15px;
                }

                .shopglut-banner-title {
                    font-size: 26px;
                }

                .shopglut-banner-description {
                    font-size: 16px;
                }

                .shopglut-banner-button {
                    padding: 14px 35px;
                    font-size: 15px;
                }

                .shopglut-banner-container {
                    max-width: 95vw;
                    max-height: 95vh;
                    margin: 20px;
                }
            }

            @media (max-width: 480px) {
                .shopglut-banner-body {
                    padding: 25px 20px;
                }

                .shopglut-banner-title {
                    font-size: 22px;
                }

                .shopglut-banner-description {
                    font-size: 15px;
                }

                .shopglut-banner-button {
                    padding: 12px 30px;
                    font-size: 14px;
                }

                .shopglut-banner-close {
                    top: 8px !important;
                    right: 8px !important;
                    width: 40px !important;
                    height: 40px !important;
                }

                .shopglut-banner-close button svg {
                    width: 20px !important;
                    height: 20px !important;
                }
            }

            /* Special Effects */
            .shopglut-banner-sparkle {
                position: absolute;
                width: 4px;
                height: 4px;
                background: #ffffff;
                border-radius: 50%;
                animation: sparkle 3s linear infinite;
            }

            @keyframes sparkle {
                0%, 100% {
                    opacity: 0;
                    transform: scale(0);
                }
                50% {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        </style>
        <?php
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // This will be handled by the assets.php file
    }

    /**
     * Check if we should display shopbanner on current page and display it
     */
    public function maybe_display_shopbanner() {
        $active_banners = $this->get_active_banners_for_current_page();

        if (empty($active_banners)) {
            return;
        }

        // Display the first active banner (could be extended to show multiple)
        $banner = $active_banners[0];
        $this->display_banner($banner);
    }

    /**
     * Get active banners for current page
     */
    private function get_active_banners_for_current_page() {
        $all_banners = ShopBannerEntity::retrieveAll(999, 1);
        $active_banners = array();

        foreach ($all_banners as $banner) {
            if ($this->is_banner_active_for_page($banner)) {
                $active_banners[] = $banner;
            }
        }

        return $active_banners;
    }

    /**
     * Check if banner should be displayed on current page
     */
    private function is_banner_active_for_page($banner) {
        if (!isset($banner['layout_settings'])) {
            return false;
        }

        $settings = maybe_unserialize($banner['layout_settings']);

        // Check if banner is enabled
        if (!isset($settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner']) ||
            !$settings['shopg_product_shopbanner_settings_template1']['enable_shopbanner']) {
            return false;
        }

        // Check if display locations are set
        if (!isset($settings['shopg_product_shopbanner_settings_template1']['display-locations']) ||
            empty($settings['shopg_product_shopbanner_settings_template1']['display-locations'])) {
            return false;
        }

        $display_locations = $settings['shopg_product_shopbanner_settings_template1']['display-locations'];

        if (!is_array($display_locations)) {
            $display_locations = array($display_locations);
        }

        // Check if current page matches any display location
        foreach ($display_locations as $location) {
            if ($this->page_matches_location($location)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if current page matches the display location
     */
    private function page_matches_location($location) {
        global $post;

        switch ($location) {
            case 'all_pages':
                return is_page();

            case 'all_posts':
                return is_single() && $post && $post->post_type === 'post';

            case 'all_products':
                return is_product() || is_shop() || is_product_category() || is_product_tag();

            case 'Woo Shop Page':
                return is_shop();

            default:
                // Check for specific page/post by ID
                if (is_numeric($location)) {
                    return is_page($location) || (is_single($location) && $post);
                }

                // Check for category/tag (starts with cat_ or tag_)
                if (strpos($location, 'cat_') === 0) {
                    $cat_id = str_replace('cat_', '', $location);
                    return is_product_category($cat_id);
                }

                if (strpos($location, 'tag_') === 0) {
                    $tag_id = str_replace('tag_', '', $location);
                    return is_product_tag($tag_id);
                }

                // Check for specific product
                if (strpos($location, 'product_') === 0) {
                    $product_id = str_replace('product_', '', $location);
                    return is_product($product_id);
                }

                return false;
        }
    }

    /**
     * Display the banner
     */
    private function display_banner($banner) {
        if (!isset($banner['id']) || !isset($banner['layout_settings'])) {
            return;
        }

        $settings = maybe_unserialize($banner['layout_settings']);
        $banner_settings = isset($settings['shopg_product_shopbanner_settings_template1']['shopbanner_settings_tabs'])
            ? $settings['shopg_product_shopbanner_settings_template1']['shopbanner_settings_tabs']
            : array();

        // Get display delay
        $delay = isset($banner_settings['display_delay']['display_delay'])
            ? intval($banner_settings['display_delay']['display_delay'])
            : 3000;

        // Get the template markup and render
        $template_file = __DIR__ . '/templates/' . $banner['layout_template'] . '/' . $banner['layout_template'] . 'Markup.php';

        if (!file_exists($template_file)) {
            return;
        }

        require_once $template_file;

        $markup_class = 'Shopglut\\showcases\\ShopBanner\\templates\\' . $banner['layout_template'] . '\\' . $banner['layout_template'] . 'Markup';

        if (!class_exists($markup_class)) {
            return;
        }

        $markup_instance = new $markup_class();

        if (!method_exists($markup_instance, 'render_demo_shopbanner')) {
            return;
        }

        // Prepare banner settings
        $prepared_settings = $this->prepare_banner_settings($banner_settings);

        // Start output buffering
        ob_start();
        $markup_instance->render_demo_shopbanner($prepared_settings, $banner['id']);
        $banner_html = ob_get_clean();

        // Add JavaScript to display banner after delay
        $this->add_banner_display_script($banner_html, $delay);
    }

    /**
     * Prepare banner settings for rendering
     */
    private function prepare_banner_settings($banner_settings) {
        // Extract settings from the nested structure
        $settings = array();

        // Content settings
        if (isset($banner_settings['banner_title'])) {
            $settings['banner_title'] = $banner_settings['banner_title'];
        }
        if (isset($banner_settings['banner_description'])) {
            $settings['banner_description'] = $banner_settings['banner_description'];
        }
        if (isset($banner_settings['banner_image'])) {
            $settings['banner_image'] = $banner_settings['banner_image'];
        }
        if (isset($banner_settings['show_button'])) {
            $settings['show_button'] = $banner_settings['show_button'];
        }
        if (isset($banner_settings['banner_button_text'])) {
            $settings['banner_button_text'] = $banner_settings['banner_button_text'];
        }
        if (isset($banner_settings['banner_button_url'])) {
            $settings['banner_button_url'] = $banner_settings['banner_button_url'];
        }

        // Styling settings
        if (isset($banner_settings['text_styling'])) {
            $settings = array_merge($settings, $banner_settings['text_styling']);
        }
        if (isset($banner_settings['banner_appearance'])) {
            $settings = array_merge($settings, $banner_settings['banner_appearance']);
        }
        if (isset($banner_settings['button_styling'])) {
            $settings = array_merge($settings, $banner_settings['button_styling']);
        }

        return $settings;
    }

    /**
     * Add JavaScript to display banner after delay
     */
    private function add_banner_display_script($banner_html, $delay) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                // Create overlay element
                var overlay = document.createElement('div');
                overlay.className = 'shopglut-banner-overlay';

                // Create a temporary div to hold the banner HTML
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = <?php echo json_encode($banner_html); ?>;

                // Extract the banner container
                var bannerContainer = tempDiv.querySelector('.shopglut-banner-container');
                if (bannerContainer) {
                    // Add overlay first
                    document.body.appendChild(overlay);

                    // Add banner directly to body (let it keep its own structure)
                    document.body.appendChild(bannerContainer);

                    // Create and add close button directly to banner container
                    var closeButton = document.createElement('div');
                    closeButton.className = 'shopglut-banner-close';
                    closeButton.innerHTML = '<button type="button" aria-label="Close">×</button>';
                    bannerContainer.appendChild(closeButton);

                    // Add some sparkle effects for premium feel
                    addSparkleEffects(bannerContainer);

                    // Show overlay first
                    setTimeout(function() {
                        overlay.classList.add('active');

                        // Show banner with slight delay for better effect
                        setTimeout(function() {
                            bannerContainer.classList.add('active');
                        }, 100);
                    }, 50);

                    // Handle close button with premium animation
                    var closeBtn = bannerContainer.querySelector('.shopglut-banner-close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            closeBannerWithPremiumEffect(overlay, bannerContainer);
                        });
                    }

                    // Handle overlay click to close
                    overlay.addEventListener('click', function() {
                        closeBannerWithPremiumEffect(overlay, bannerContainer);
                    });

                    // Prevent banner body click from closing
                    var bannerBody = bannerContainer.querySelector('.shopglut-banner-body');
                    if (bannerBody) {
                        bannerBody.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }
                }
            }, <?php echo esc_js($delay); ?>);
        });

        function closeBannerWithPremiumEffect(overlay, bannerContainer) {
            // Add closing animations
            overlay.classList.add('closing');
            bannerContainer.classList.add('closing');

            // Remove elements after animation completes
            setTimeout(function() {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
                if (bannerContainer.parentNode) {
                    bannerContainer.parentNode.removeChild(bannerContainer);
                }
            }, 600);
        }

        function addSparkleEffects(container) {
            // Add 3-5 sparkle elements randomly around the banner
            var sparkleCount = Math.floor(Math.random() * 3) + 3;

            for (var i = 0; i < sparkleCount; i++) {
                setTimeout(function() {
                    var sparkle = document.createElement('div');
                    sparkle.className = 'shopglut-banner-sparkle';

                    // Random position around the banner
                    var bannerBody = container.querySelector('.shopglut-banner-body');
                    if (bannerBody) {
                        var rect = bannerBody.getBoundingClientRect();
                        var centerX = rect.left + rect.width / 2;
                        var centerY = rect.top + rect.height / 2;
                        var radius = Math.max(rect.width, rect.height) / 2 + 50;

                        var angle = Math.random() * Math.PI * 2;
                        var x = centerX + Math.cos(angle) * radius;
                        var y = centerY + Math.sin(angle) * radius;

                        sparkle.style.left = x + 'px';
                        sparkle.style.top = y + 'px';
                        sparkle.style.animationDelay = Math.random() * 2 + 's';

                        document.body.appendChild(sparkle);

                        // Remove sparkle after animation
                        setTimeout(function() {
                            if (sparkle.parentNode) {
                                sparkle.parentNode.removeChild(sparkle);
                            }
                        }, 3000);
                    }
                }, i * 200);
            }
        }
        </script>
        <?php
    }

    /**
     * Get singleton instance
     */
    public static function get_instance() {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}

// Initialize the frontend class
ShopBannerFrontend::get_instance();