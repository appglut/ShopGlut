<?php
namespace Shopglut;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\ShopGlutDatabase;

class ModuleManager {
    
    private static $instance = null;
    private $modules = [];
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_modules();
        $this->initialize_default_states();
        add_action('wp_ajax_toggle_shopglut_module', array($this, 'ajax_toggle_module'));
        add_action('wp_ajax_enable_all_shopglut_modules', array($this, 'ajax_enable_all_modules'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    private function init_modules() {
        $this->modules = [
            // Layout Modules
            'single_product' => [
                'name' => __('Single Product', 'shopglut'),
                'description' => __('Product Page Builder', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_single_layouts',
                'icon' => 'fas fa-box-open'
            ],
            'shop_layouts' => [
                'name' => __('Shop & Archive Layouts', 'shopglut'),
                'description' => __('Shop & Archive Page Designer', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_shop_layouts',
                'icon' => 'fas fa-th-large'
            ],
            'cart_page' => [
                'name' => __('Cart Page', 'shopglut'),
                'description' => __('Cart Layout Builder', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_cartpage_layouts',
                'icon' => 'fas fa-shopping-cart'
            ],
            'checkout_page' => [
                'name' => __('Checkout Page', 'shopglut'),
                'description' => __('Checkout Field Editor', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_checkout_fields_table',
                'icon' => 'fas fa-check-circle'
            ],
            'orderComplete_page' => [
                'name' => __('OrderComplete Page', 'shopglut'),
                'description' => __('Order Complete Builder', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_ordercomplete_layouts',
                'icon' => 'fas fa-check-double'
            ],
            'account_page' => [
                'name' => __('My Account', 'shopglut'),
                'description' => __('Account Page Builder', 'shopglut'),
                'type' => 'layout',
                'table_method' => 'create_accountpage_layouts',
                'icon' => 'fas fa-user'
            ],
            
            // Enhancement Modules
            'wishlist' => [
                'name' => __('Wishlist', 'shopglut'),
                'description' => __('Save Favorite Products', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => 'create_wishlist_table',
                'icon' => 'fas fa-heart'
            ],
            'product_badges' => [
                'name' => __('Product Badges', 'shopglut'),
                'description' => __('Custom Product Labels', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => 'create_product_badges',
                'icon' => 'fas fa-tags'
            ],
            'product_comparison' => [
                'name' => __('Product Comparison', 'shopglut'),
                'description' => __('Compare Products Table', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => 'create_product_comparisons',
                'icon' => 'fas fa-balance-scale'
            ],
            'quick_views' => [
                'name' => __('Quick View', 'shopglut'),
                'description' => __('Product Popup Modal', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => 'create_product_quickview',
                'icon' => 'fa-solid fa-forward-fast'
            ],
            'product_swatches' => [
                'name' => __('Product Swatches', 'shopglut'),
                'description' => __('Color Image Swatches', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => null, // No database table needed
                'icon' => 'fas fa-palette'
            ],
            
            // Tool Modules
            'acf_fields' => [
                'name' => __('Product Custom Fields', 'shopglut'),
                'description' => __('Product Custom Fields', 'shopglut'),
                'type' => 'tool',
                'table_method' => 'create_product_custom_field_settings',
                'icon' => 'fas fa-plus-circle'
            ],
            'shortcode_showcase' => [
                'name' => __('Shortcode Showcase', 'shopglut'),
                'description' => __('Product Display Shortcodes', 'shopglut'),
                'type' => 'tool',
                'table_method' => 'create_shortcodes_showcase',
                'icon' => 'fa-solid fa-code'
            ],
            'gallery_shortcode' => [
                'name' => __('Gallery Shortcode', 'shopglut'),
                'description' => __('Interactive Product Gallery Shortcodes', 'shopglut'),
                'type' => 'tool',
                'table_method' => 'create_gallery_shortcode',
                'icon' => 'fas fa-images'
            ],
            'woo_templates' => [
                'name' => __('Product Templates', 'shopglut'),
                'description' => __('Custom Display Templates', 'shopglut'),
                'type' => 'tool',
                'table_method' => 'create_woo_templates',
                'icon' => 'fa-solid fa-hashtag'
            ],
            'sliders' => [
                'name' => __('Sliders', 'shopglut'),
                'description' => __('Product Image Sliders', 'shopglut'),
                'type' => 'showcase',
                'table_method' => null, // Has its own table creation
                'icon' => 'fas fa-images'
            ],
            'tabs' => [
                'name' => __('Tabs', 'shopglut'),
                'description' => __('Tabbed Content Sections', 'shopglut'),
                'type' => 'showcase',
                'table_method' => 'create_tabs_showcase',
                'icon' => 'fas fa-folder-open'
            ],
            'accordions' => [
                'name' => __('Accordion', 'shopglut'),
                'description' => __('Collapsible Content Sections', 'shopglut'),
                'type' => 'showcase',
                'table_method' => null,
                'icon' => 'fas fa-list-ul'
            ],
            'gallery' => [
                'name' => __('Gallery', 'shopglut'),
                'description' => __('Image Gallery Builder', 'shopglut'),
                'type' => 'showcase',
                'table_method' => null,
                'icon' => 'fas fa-th-large'
            ],
            'woo_themes' => [
                'name' => __('Woo Theme', 'shopglut'),
                'description' => __('Theme Customization Tools', 'shopglut'),
                'type' => 'showcase',
                'table_method' => null,
                'icon' => 'fas fa-shopping-cart'
            ],
            'mega_menu' => [
                'name' => __('Mega Menu', 'shopglut'),
                'description' => __('Advanced Navigation Menus', 'shopglut'),
                'type' => 'showcase',
                'table_method' => null,
                'icon' => 'fas fa-bars'
            ],
            'shop_filters' => [
                'name' => __('Shop Filters', 'shopglut'),
                'description' => __('Product Filter System', 'shopglut'),
                'type' => 'enhancement',
                'table_method' => 'create_showcase_filters',
                'icon' => 'fas fa-filter'
            ],
            'mini_cart' => [
                'name' => __('Mini Cart', 'shopglut'),
                'description' => __('Enhanced Cart Drawer', 'shopglut'),
                'type' => 'tool',
                'table_method' => null,
                'icon' => 'fas fa-shopping-basket'
            ],
            'shop_banner' => [
                'name' => __('Shop Banner', 'shopglut'),
                'description' => __('Custom Shop Banners', 'shopglut'),
                'type' => 'showcase',
                'table_method' => 'create_showcase_banners',
                'icon' => 'fa-solid fa-ticket'
            ],
            'login_register' => [
                'name' => __('Login/Register Page', 'shopglut'),
                'description' => __('Custom Login Pages', 'shopglut'),
                'type' => 'layout',
                'table_method' => null,
                'icon' => 'fa-solid fa-user'
            ],
            
            // Business Solution Modules
            'pdf_invoices' => [
                'name' => __('Invoices & Packing Slips', 'shopglut'),
                'description' => __('PDF Invoice Generator', 'shopglut'),
                'type' => 'business',
                'table_method' => null, // No database table needed
                'icon' => 'fa-solid fa-file-invoice'
            ],
            'email_customizer' => [
                'name' => __('Email Customizer', 'shopglut'),
                'description' => __('Email Template Designer', 'shopglut'),
                'type' => 'business',
                'table_method' => null, // Has its own table creation
                'icon' => 'fa-solid fa-envelope-open-text'
            ]
        ];
    }
    
    private function initialize_default_states() {
        // Set all modules as disabled by default if no explicit setting exists
        foreach (array_keys($this->modules) as $module_key) {
            $option_name = 'shopglut_module_' . $module_key . '_enabled';
            // Only set if the option doesn't exist in the database
            if (get_option($option_name) === false) {
                add_option($option_name, false);  // Default to disabled
            }
        }
    }
    
    public function is_module_enabled($module_key) {
        return (bool) get_option('shopglut_module_' . $module_key . '_enabled', false);
    }
    
    public function toggle_module($module_key, $enabled) {
        if (!isset($this->modules[$module_key])) {
            return false;
        }
        
        $module = $this->modules[$module_key];
        
        if ($enabled && $module['table_method']) {
            // Create database table if enabling
            if (method_exists('Shopglut\ShopGlutDatabase', $module['table_method'])) {
                // Special handling for galleryShortcode module
                if ($module['table_method'] === 'create_gallery_shortcode') {
                    // Include the required file first
                    $gallery_path = SHOPGLUT_PATH . 'src/tools/galleryShortcode/gallerydatatables.php';
                    if (file_exists($gallery_path)) {
                        require_once $gallery_path;
                    }
                }
                call_user_func(['Shopglut\ShopGlutDatabase', $module['table_method']]);
            }
        }
        
        $option_name = 'shopglut_module_' . $module_key . '_enabled';
        $result = update_option($option_name, (bool) $enabled);
        
        return true;
    }
    
    public function ajax_toggle_module() {
        check_ajax_referer('shopglut_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'shopglut'));
        }
        
        $module_key = isset($_POST['module']) ? sanitize_text_field(wp_unslash($_POST['module'])) : '';
        $enabled = isset($_POST['enabled']) ? filter_var(wp_unslash($_POST['enabled']), FILTER_VALIDATE_BOOLEAN) : false;
        
        if (empty($module_key)) {
            wp_send_json_error(['message' => __('Invalid module specified.', 'shopglut')]);
            return;
        }
        
        if ($this->toggle_module($module_key, $enabled)) {
            wp_send_json_success([
                'message' => $enabled ? __('Module enabled successfully!', 'shopglut') : __('Module disabled successfully!', 'shopglut')
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Failed to toggle module.', 'shopglut')
            ]);
        }
    }
    
    public function ajax_enable_all_modules() {
        check_ajax_referer('shopglut_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error([
                'message' => __('You do not have sufficient permissions to access this page.', 'shopglut')
            ]);
        }
        
        $enabled_count = 0;
        $failed_modules = [];
        
        foreach (array_keys($this->modules) as $module_key) {
            if ($this->toggle_module($module_key, true)) {
                $enabled_count++;
            } else {
                $failed_modules[] = $module_key;
            }
        }
        
        if (empty($failed_modules)) {
            wp_send_json_success([
                'message' => sprintf(// translators: %d is the number of enabled modules
                    __('Successfully enabled all %d modules! Database tables have been created.', 'shopglut'),
                    $enabled_count
                ),
                'enabled_count' => $enabled_count
            ]);
        } else {
            wp_send_json_error([
                'message' => sprintf(
                    // translators: %1$d is the number of enabled modules, %2$d is the number of failed modules, %3$s is the list of failed module names
                    __('Enabled %1$d modules, but %2$d failed: %3$s', 'shopglut'),
                    $enabled_count,
                    count($failed_modules),
                    implode(', ', $failed_modules)
                ),
                'enabled_count' => $enabled_count,
                'failed_modules' => $failed_modules
            ]);
        }
    }
    
    public function get_modules() {
        return $this->modules;
    }
    
    public function get_modules_by_type($type) {
        return array_filter($this->modules, function($module) use ($type) {
            return $module['type'] === $type;
        });
    }
    
    public function enqueue_admin_scripts($hook_suffix) {
        if (strpos($hook_suffix, 'shopglut') !== false || strpos($hook_suffix, 'shopg_') !== false) {
            wp_enqueue_script('shopglut-module-manager', SHOPGLUT_URL . 'global-assets/js/module-manager.js', ['jquery'], '1.0.0', true);
            wp_localize_script('shopglut-module-manager', 'shopglut_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_nonce')
            ]);
        }
    }
    
    public function render_module_card($module_key, $url = '', $show_switcher = true) {
        if (!isset($this->modules[$module_key])) {
            return;
        }

        $module = $this->modules[$module_key];
        $is_enabled = $this->is_module_enabled($module_key);
        $disabled_class = !$is_enabled ? 'shopglut-module-disabled' : '';

        // Array of module keys that should have disable-link class
        $disabled_modules = [
            'shop_layouts',
            'archive_layouts',
            'product_badges',
            'shop_filters',
            'acf_fields',
            'shortcode_showcase',
            'gallery_shortcode',
            'woo_templates',
            'mini_cart',
            'login_register',
            'sliders',
            'tabs',
            'accordions',
            'gallery',
            'woo_themes',
            'shop_banner',
            'mega_menu',
            'pdf_invoices',
            'email_customizer'
        ];

        ?>
        <div class="shopglut-module-card <?php echo esc_attr($disabled_class); ?>">
            <?php if ($show_switcher): ?>
                <div class="shopglut-module-switcher">
                    <label class="shopglut-switch">
                        <input type="checkbox"
                               class="shopglut-module-toggle"
                               data-module="<?php echo esc_attr($module_key); ?>"
                               <?php checked($is_enabled); ?>>
                        <span class="shopglut-slider round"></span>
                    </label>
                </div>
            <?php endif; ?>

            <a href="<?php echo esc_url($url); ?>">
                <div class="shopg-woo-builder grid-item">
                    <i class="<?php echo esc_attr($module['icon']); ?>"></i>
                    <h3><?php echo esc_html($module['name']); ?></h3>
                    <?php if (!empty($module['description'])): ?>
                        <p class="description"><?php echo esc_html($module['description']); ?></p>
                    <?php endif; ?>
                    <?php if (!$is_enabled): ?>
                        <div class="module-disabled-overlay"></div>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <?php
    }
    
    /**
     * Check if a module should show disabled tab message
     */
    public function should_show_disabled_message($module_key) {
        return !$this->is_module_enabled($module_key);
    }
    
    /**
     * Render disabled module message for admin pages
     */
    public function render_disabled_module_message($module_key) {
        if (!isset($this->modules[$module_key]) || $this->is_module_enabled($module_key)) {
            return;
        }
        
        $module = $this->modules[$module_key];
        ?>
        <div style="margin: 0; margin-top:-20px; padding-bottom:55px !important; background: #ffffff; border-left: 4px solid #ffb900; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 20px; text-align: center;">
            <div style="color: #666; font-size: 24px; margin-bottom: 15px;">
                <i class="<?php echo esc_attr($module['icon']); ?>"></i>
            </div>
            <h3 style="margin: 0 0 8px 0; color: #23282d; font-size: 18px; font-weight: 600;">
                <?php echo sprintf(
                    // translators: %s is the module name
                    esc_html__('%s Module has been Disabled', 'shopglut'), 
                    esc_html($module['name'])
                ); ?>
            </h3>
            <p style="margin: 0 0 15px 0; color: #555; font-size: 14px; line-height: 1.4;">
                <?php echo esc_html__('Please enable from here to access its functionality and settings.', 'shopglut'); ?>
            </p>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
                <button class="shopglut-single-module-enable" 
                        data-module="<?php echo esc_attr($module_key); ?>" 
                        style="background: #0073aa; color: #ffffff; border: none; padding: 8px 12px; border-radius: 3px; cursor: pointer; font-size: 13px;">
                    <?php echo sprintf(
                        // translators: %s is the module name
                        esc_html__('Enable %s Module', 'shopglut'), 
                        esc_html($module['name'])
                    ); ?>
                </button>
                
                <?php if ($module['type'] === 'business'): ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=shopg_business_solution')); ?>" 
                       style="background: #f7f7f7; color: #555; text-decoration: none; border: 1px solid #cccccc; padding: 8px 12px; border-radius: 3px; font-size: 13px; display: inline-block;">
                        <?php echo esc_html__('Go to Business Modules', 'shopglut'); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=shopg_woocommerce_builder')); ?>" 
                       style="background: #f7f7f7; color: #555; text-decoration: none; border: 1px solid #cccccc; padding: 8px 12px; border-radius: 3px; font-size: 13px; display: inline-block;">
                        <?php echo esc_html__('Go to Builder Modules', 'shopglut'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.shopglut-single-module-enable').on('click', function() {
                var $button = $(this);
                var moduleKey = $button.data('module');
                var originalText = $button.html();
                
                // Set loading state
                $button.html('<span style="margin-right: 6px;">⏳</span><?php echo esc_js(__('Enabling...', 'shopglut')); ?>');
                $button.prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'toggle_shopglut_module',
                        module: moduleKey,
                        enabled: true,
                        nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_nonce')); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reload page to show enabled module
                            location.reload();
                        } else {
                            alert('<?php echo esc_js(__('Failed to enable module. Please try again.', 'shopglut')); ?>');
                            $button.html(originalText);
                            $button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('<?php echo esc_js(__('An error occurred. Please try again.', 'shopglut')); ?>');
                        $button.html(originalText);
                        $button.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Filter tabs to show disabled state
     */
    public function filter_tabs_for_disabled_module($tabs, $module_key) {
        if (!$this->should_show_disabled_message($module_key)) {
            return $tabs;
        }
        
        // Add disabled class to all tab titles
        foreach ($tabs as &$tab) {
            if (isset($tab['label'])) {
                $tab['label'] .= ' <span class="shopglut-tab-disabled">(' . esc_html__('Module Disabled', 'shopglut') . ')</span>';
            }
        }
        
        return $tabs;
    }
}