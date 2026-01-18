<?php
namespace Shopglut\BusinessSolutions\PdfInvoices;

if ( ! defined( 'ABSPATH' ) ) exit;

use AGSHOPGLUT;
use AGSHOPGLUT_Options;

// Load tab classes
require_once __DIR__ . '/tabs/DashboardTab.php';
require_once __DIR__ . '/tabs/InvoiceTemplates.php';
require_once __DIR__ . '/tabs/PackagingTemplates.php';
require_once __DIR__ . '/tabs/FreeVsProTab.php';
require_once __DIR__ . '/tabs/SupportTab.php';

use Shopglut\BusinessSolutions\PdfInvoices\Tabs\DashboardTab;
use Shopglut\BusinessSolutions\PdfInvoices\Tabs\InvoiceTemplates;
use Shopglut\BusinessSolutions\PdfInvoices\Tabs\PackagingTemplates;
use Shopglut\BusinessSolutions\PdfInvoices\Tabs\FreeVsProTab;
use Shopglut\BusinessSolutions\PdfInvoices\Tabs\SupportTab;


class PdfInvoicesMenuHandler {

    private $agshopglut_instance = null;

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        
        // Hook into AGSHOPGLUT initialization - try multiple hooks
        add_action('agl_loaded', array($this, 'getAGSHOPGLUTInstance'));
        add_action('init', array($this, 'getAGSHOPGLUTInstance'), 999); // Late init
        add_action('admin_init', array($this, 'getAGSHOPGLUTInstance'), 999);
        
        // Also try to get instance when admin page loads
        add_action('current_screen', array($this, 'getAGSHOPGLUTInstance'));
    }

    /**
     * Get AGSHOPGLUT instance after it's loaded - Multiple approaches
     */
    public function getAGSHOPGLUTInstance() {
        // Skip if already found
        if ($this->agshopglut_instance) {
            return;
        }

        // Method 1: Check AGSHOPGLUT_Options static instances
        if (class_exists('AGSHOPGLUT_Options')) {
            // Check if the static instances property exists
            if (isset(AGSHOPGLUT_Options::$instances) && is_array(AGSHOPGLUT_Options::$instances)) {
                foreach (AGSHOPGLUT_Options::$instances as $key => $instance) {
                    if ($key === 'agshopglut_pdf_invoices_options') {
                        $this->agshopglut_instance = $instance;
                        return;
                    }
                }
            }
        }

        // Method 2: Try to get from AGSHOPGLUT main class instances
        if (class_exists('AGSHOPGLUT') && isset(AGSHOPGLUT::$inited)) {
            if (isset(AGSHOPGLUT::$inited['agshopglut_pdf_invoices_options'])) {
                // Try to recreate or find the instance
                $this->tryRecreateInstance();
            }
        }

        // Method 3: Check global variables
        global $agshopglut_pdf_invoices_options;
        if (isset($agshopglut_pdf_invoices_options) && is_object($agshopglut_pdf_invoices_options)) {
            $this->agshopglut_instance = $agshopglut_pdf_invoices_options;
            return;
        }

        // Method 4: Try to find any AGSHOPGLUT_Options instance and check its unique property
        if (class_exists('AGSHOPGLUT_Options')) {
            // Get all defined variables and look for AGSHOPGLUT_Options instances
            $this->findInstanceInGlobals();
        }
    }

    /**
     * Try to recreate instance from stored data
     */
    private function tryRecreateInstance() {
        if (!class_exists('AGSHOPGLUT_Options')) {
            return;
        }

        // Get the stored sections from AGSHOPGLUT
        if (isset(AGSHOPGLUT::$args['sections']['agshopglut_pdf_invoices_options'])) {
            $sections = AGSHOPGLUT::$args['sections']['agshopglut_pdf_invoices_options'];
            $args = isset(AGSHOPGLUT::$args['admin_options']['agshopglut_pdf_invoices_options']) ? 
                    AGSHOPGLUT::$args['admin_options']['agshopglut_pdf_invoices_options'] : array();

            try {
                $this->agshopglut_instance = new AGSHOPGLUT_Options('agshopglut_pdf_invoices_options', array(
                    'args' => $args,
                    'sections' => $sections
                ));
            } catch (Exception $e) {
                // Handle silently
            }
        }
    }

    /**
     * Find instance in global variables
     */
    private function findInstanceInGlobals() {
        // Check all global variables for AGSHOPGLUT_Options instances
        foreach ($GLOBALS as $var_name => $var_value) {
            if (is_object($var_value) && 
                $var_value instanceof AGSHOPGLUT_Options && 
                isset($var_value->unique) && 
                $var_value->unique === 'agshopglut_pdf_invoices_options') {
                $this->agshopglut_instance = $var_value;
                return;
            }
        }
    }

    /**
     * Get instance with fallback options
     */
    private function getInstanceWithFallback() {
        if ($this->agshopglut_instance) {
            return $this->agshopglut_instance;
        }

        // Try one more time to get the instance
        $this->getAGSHOPGLUTInstance();

        if ($this->agshopglut_instance) {
            return $this->agshopglut_instance;
        }

        // Final fallback - try to access options directly
        return $this->createFallbackInstance();
    }

    /**
     * Create fallback instance with basic functionality
     */
    private function createFallbackInstance() {
        // Get options directly from database
        $options = get_option('agshopglut_pdf_invoices_options', array());
        
        if (empty($options)) {
            return null;
        }

        // Create a simple object to hold the options
        $fallback = new \stdClass();
        $fallback->options = $options;
        $fallback->unique = 'agshopglut_pdf_invoices_options';
        $fallback->pre_sections = array(); // Will be empty but prevents errors
        
        return $fallback;
    }

    /**
     * Main render method - displays the complete PDF invoices interface
     */
   public function render() {
    // Check if pdf_invoices module is disabled
    $module_manager = \Shopglut\ModuleManager::get_instance();
    if ($module_manager->should_show_disabled_message('pdf_invoices')) {
        $module_manager->render_disabled_module_message('pdf_invoices');
        return;
    }
    
    // Get current tab from URL parameter
    // Valid tab names to prevent potential issues
    $valid_tabs = array('dashboard', 'settings', 'templates', 'help', 'pro');
    $current_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'dashboard'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $current_tab = in_array($current_tab, $valid_tabs, true) ? $current_tab : 'dashboard';
    
    // Check if pro version is active
    $is_pro = $this->isProVersionActive();
 
    ?>
    <div class="wrap shopglut-pdf-invoices-wrapper">
        <!-- PDF Invoices Tab Navigation -->
        <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
            <a href="<?php echo esc_url( $this->getTabUrl('dashboard') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">
                📊 <?php echo esc_html__('Dashboard', 'shopglut'); ?>
            </a>
            
            <a href="<?php echo esc_url( $this->getTabUrl('settings') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                ⚙️ <?php echo esc_html__('Settings', 'shopglut'); ?>
            </a>

            <a href="<?php echo esc_url( $this->getTabUrl('invoice_templates') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'invoice_templates' ? 'nav-tab-active' : ''; ?>">
                🎨 <?php echo esc_html__('Invoice Templates', 'shopglut'); ?>
            </a>

            <a href="<?php echo esc_url( $this->getTabUrl('packaging_templates') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'packaging_templates' ? 'nav-tab-active' : ''; ?>">
                📦 <?php echo esc_html__('Packaging Templates', 'shopglut'); ?>
            </a>
            
            <!-- Help & Support - always available -->
            <a href="<?php echo esc_url( $this->getTabUrl('free_vs_pro') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'free_vs_pro' ? 'nav-tab-active' : ''; ?>">
                ⭐ <?php echo esc_html__('Free vs Pro', 'shopglut'); ?>
            </a>
            
            <a href="<?php echo esc_url( $this->getTabUrl('support') ); ?>" 
               class="nav-tab <?php echo $current_tab === 'support' ? 'nav-tab-active' : ''; ?>">
                🆘 <?php echo esc_html__('Help & Support', 'shopglut'); ?>
            </a>
        </nav>

        <!-- Tab Content -->
        <div class="shopglut-pdf-invoices-settings tab-content-wrapper">
            <?php
            switch ($current_tab) {
                case 'dashboard':
                    $dashboardTab = new DashboardTab();
                    $dashboardTab->render();
                    break;
                case 'settings':
                    $this->renderSettingsTab();
                    break;
                case 'invoice_templates':
                    $invoiceTemplatesTab = new InvoiceTemplates();
                    $invoiceTemplatesTab->render();
                    break;
                case 'packaging_templates':
                    $packagingTemplatesTab = new PackagingTemplates();
                    $packagingTemplatesTab->render();
                    break;
                case 'free_vs_pro':
                    $freeVsProTab = new FreeVsProTab();
                    $freeVsProTab->render();
                    break;
                case 'support':
                    $supportTab = new SupportTab();
                    $supportTab->render();
                    break;
                default:
                    $dashboardTab = new DashboardTab();
                    $dashboardTab->render();
            }
            ?>
        </div>
    </div>

    <?php $this->renderStyles(); ?>
    <?php
}

/**
 * Check if pro version is active
 */
private function isProVersionActive() {

    
    // Option 1: Check if pro plugin is active
    if (is_plugin_active('shopglut-wishlist-pro/shopglut-wishlist-pro.php')) {
        
        return true;
    }
    
    // Option 2: Check for license key
    // $license_key = get_option('shopglut_wishlist_license_key');
    // if (!empty($license_key) && $this->validateLicense($license_key)) {
    //     return true;
    // }
    
    // Option 3: Check for pro constant
    if (defined('SHOPGLUT_WISHLIST_PRO') && SHOPGLUT_WISHLIST_PRO === true) {
        return true;
    }
    
    return false;
}

/**
 * Validate license key (implement your own logic)
 */
private function validateLicense($license_key) {
    // Implement your license validation logic here
    // This could involve API calls to your server
    return false; // Placeholder
}

/**
 * Get upgrade URL
 */
private function getUpgradeUrl() {
    return 'https://www.appglut.com/shopglut-wishlist/'; // Replace with your actual upgrade URL
}


/**
 * Render Analytics Tab - Pro Feature Placeholder
 */
public function renderAnalyticsTab() {
    // Check if pro version extends this
    if (has_action('shopglut_render_analytics_tab')) {
        do_action('shopglut_render_analytics_tab');
        return;
    }
    
    // Default free version preview
    $this->renderProTabPreview('analytics');
}

/**
 * Render Users Tab - Pro Feature Placeholder
 */
public function renderUsersTab() {
    // Check if pro version extends this
    if (has_action('shopglut_render_users_tab')) {
        do_action('shopglut_render_users_tab');
        return;
    }
    
    // Default free version preview
    $this->renderProTabPreview('users');
}

/**
 * Render Integrations Tab - Pro Feature Placeholder
 */
public function renderIntegrationsTab() {
    // Check if pro version extends this
    if (has_action('shopglut_render_integrations_tab')) {
        do_action('shopglut_render_integrations_tab');
        return;
    }
    
    // Default free version preview
    $this->renderProTabPreview('integrations');
}

/**
 * Render pro tab preview with content and upgrade overlay
 */
private function renderProTabPreview($tab_name) {
    // Allow pro plugin to completely override this function
    if (has_action("shopglut_render_{$tab_name}_tab")) {
        do_action("shopglut_render_{$tab_name}_tab");
        return;
    }
    
    // Hook before tab preview rendering
    do_action('shopglut_before_pro_tab_preview', $tab_name);
    
    $tab_configs = [
        'analytics' => [
            'title' => __('Analytics Dashboard', 'shopglut'),
            'description' => __('Get detailed insights into your wishlist performance, user behavior, and conversion rates.', 'shopglut'),
            'content' => $this->getAnalyticsPreviewContent()
        ],
        'users' => [
            'title' => __('Email Management', 'shopglut'),
            'description' => __('Manage wishlist users mail, view their activity, and understand user engagement patterns.', 'shopglut'),
            'content' => $this->getUsersPreviewContent()
        ],
        'integrations' => [
            'title' => __('Third-party Integrations', 'shopglut'),
            'description' => __('Connect your wishlist with popular email marketing, CRM, and analytics platforms.', 'shopglut'),
            'content' => $this->getIntegrationsPreviewContent()
        ]
    ];
    
    // Allow filtering of tab configs
    $tab_configs = apply_filters('shopglut_pro_tab_configs', $tab_configs, $tab_name);
    
    $config = isset($tab_configs[$tab_name]) ? $tab_configs[$tab_name] : $tab_configs['analytics'];
    
    // Allow filtering of individual tab config
    $config = apply_filters("shopglut_pro_tab_config_{$tab_name}", $config);
    ?>
    <div class="shopglut-pro-tab-preview">
        <?php do_action('shopglut_pro_tab_preview_start', $tab_name, $config); ?>
        
        <!-- Pro Content Preview -->
        <div class="pro-content-preview">
            <?php 
            // Hook to modify preview content
            do_action("shopglut_before_{$tab_name}_preview_content");
            echo wp_kses_post( $config['content'] ); 
            do_action("shopglut_after_{$tab_name}_preview_content");
            ?>
        </div>
        
        <!-- Pro Upgrade Overlay -->
        <div class="pro-upgrade-overlay">
            <?php do_action('shopglut_before_upgrade_overlay', $tab_name); ?>
            
            <div class="pro-upgrade-content">
                <?php do_action('shopglut_upgrade_content_start', $tab_name); ?>
                
                <div class="pro-upgrade-icon">⭐</div>
                <h2><?php echo esc_html( $config['title'] ); ?> - <?php echo esc_html__('Pro Feature', 'shopglut'); ?></h2>
                <p><?php echo esc_html( $config['description'] ); ?></p>
                
                <?php do_action('shopglut_upgrade_content_middle', $tab_name, $config); ?>
                
                <div class="pro-upgrade-actions">
                    <?php do_action('shopglut_before_upgrade_actions', $tab_name); ?>
                    
                    <a href="<?php echo esc_url( $this->getUpgradeUrl() ); ?>" 
                       class="button button-primary button-hero" 
                       target="_blank">
                        <?php echo esc_html__('Unlock This Feature', 'shopglut'); ?>
                    </a>
                    
                    <?php do_action('shopglut_after_upgrade_actions', $tab_name); ?>
                </div>
                
                <?php do_action('shopglut_upgrade_content_end', $tab_name); ?>
            </div>
            
            <?php do_action('shopglut_after_upgrade_overlay', $tab_name); ?>
        </div>
        
        <?php do_action('shopglut_pro_tab_preview_end', $tab_name, $config); ?>
    </div>
    
    <?php
    // Hook for custom styles
    do_action('shopglut_pro_tab_preview_styles', $tab_name);
    
    // Default styles (can be overridden by pro plugin)
    if (!has_action('shopglut_pro_tab_preview_styles')) {
        $this->renderDefaultProTabStyles();
    }
    
    // Hook after tab preview rendering
    do_action('shopglut_after_pro_tab_preview', $tab_name);
}

/**
 * Render default pro tab styles (can be overridden)
 */
private function renderDefaultProTabStyles() {
    // Allow pro plugin to completely override styles
    if (has_filter('shopglut_pro_tab_custom_styles')) {
        echo wp_kses_post( apply_filters('shopglut_pro_tab_custom_styles', '') );
        return;
    }
    ?>
    <style>
   
    
    <?php echo wp_kses_post( apply_filters('shopglut_pro_tab_additional_styles', '') ); ?>
    </style>
    <?php
}


/**
 * Get analytics preview content
 */
private function getAnalyticsPreviewContent() {
    ob_start();
    ?>
    <div class="analytics-preview">
        <h2><?php echo esc_html__('Analytics Dashboard', 'shopglut'); ?></h2>
        
        <div class="analytics-cards">
            <div class="analytics-card">
                <h3><?php echo esc_html__('Total Wishlists', 'shopglut'); ?></h3>
                <div class="analytics-number">1,234</div>
                <div class="analytics-change positive">+12% this month</div>
            </div>
            
            <div class="analytics-card">
                <h3><?php echo esc_html__('Conversion Rate', 'shopglut'); ?></h3>
                <div class="analytics-number">24.5%</div>
                <div class="analytics-change positive">+3.2% this month</div>
            </div>
            
            <div class="analytics-card">
                <h3><?php echo esc_html__('Active Users', 'shopglut'); ?></h3>
                <div class="analytics-number">856</div>
                <div class="analytics-change negative">-2% this month</div>
            </div>
        </div>
        
        <div class="analytics-charts">
            <div class="chart-placeholder">
                <h4><?php echo esc_html__('Wishlist Activity Over Time', 'shopglut'); ?></h4>
                <div class="chart-mock"></div>
            </div>
            
            <div class="chart-placeholder">
                <h4><?php echo esc_html__('Top Wishlist Products', 'shopglut'); ?></h4>
                <div class="chart-mock"></div>
            </div>
        </div>
    </div>
    
    <style>
    .analytics-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .analytics-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-align: center;
    }
    
    .analytics-number {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin: 10px 0;
    }
    
    .analytics-change.positive {
        color: #28a745;
    }
    
    .analytics-change.negative {
        color: #dc3545;
    }
    
    .analytics-charts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 30px;
    }
    
    .chart-placeholder {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        min-height: 300px;
    }
    
    .chart-mock {
        background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%),
                    linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        height: 200px;
        border-radius: 4px;
        margin-top: 15px;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Get users preview content
 */
private function getUsersPreviewContent() {
    ob_start();
    ?>
    <div class="users-preview">
        <h2><?php echo esc_html__('User Management', 'shopglut'); ?></h2>
        
        <div class="users-table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('User', 'shopglut'); ?></th>
                        <th><?php echo esc_html__('Items in Wishlist', 'shopglut'); ?></th>
                        <th><?php echo esc_html__('Last Activity', 'shopglut'); ?></th>
                        <th><?php echo esc_html__('Total Purchases', 'shopglut'); ?></th>
                        <th><?php echo esc_html__('Actions', 'shopglut'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>John Doe</strong><br>john@example.com</td>
                        <td>12 items</td>
                        <td>2 hours ago</td>
                        <td>$245.80</td>
                        <td><button class="button">View Details</button></td>
                    </tr>
                    <tr>
                        <td><strong>Jane Smith</strong><br>jane@example.com</td>
                        <td>8 items</td>
                        <td>1 day ago</td>
                        <td>$156.20</td>
                        <td><button class="button">View Details</button></td>
                    </tr>
                    <tr>
                        <td><strong>Mike Johnson</strong><br>mike@example.com</td>
                        <td>15 items</td>
                        <td>3 days ago</td>
                        <td>$398.50</td>
                        <td><button class="button">View Details</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get integrations preview content
 */
private function getIntegrationsPreviewContent() {
    ob_start();
    ?>
    <div class="integrations-preview">
        <h2><?php echo esc_html__('Available Integrations', 'shopglut'); ?></h2>
        
        <div class="integrations-grid">
            <div class="integration-card">
                <div class="integration-logo">📧</div>
                <h3>Mailchimp</h3>
                <p>Sync wishlist data with your email campaigns</p>
                <button class="button button-primary">Connect</button>
            </div>
            
            <div class="integration-card">
                <div class="integration-logo">📊</div>
                <h3>Google Analytics</h3>
                <p>Track wishlist events in your analytics</p>
                <button class="button button-primary">Connect</button>
            </div>
            
            <div class="integration-card">
                <div class="integration-logo">🔗</div>
                <h3>Zapier</h3>
                <p>Connect with 1000+ apps via Zapier</p>
                <button class="button button-primary">Connect</button>
            </div>
            
            <div class="integration-card">
                <div class="integration-logo">💬</div>
                <h3>Slack</h3>
                <p>Get wishlist notifications in Slack</p>
                <button class="button button-primary">Connect</button>
            </div>
        </div>
    </div>
    
    <style>
    .integrations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .integration-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-align: center;
    }
    
    .integration-logo {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .integration-card h3 {
        margin: 15px 0 10px 0;
        color: #333;
    }
    
    .integration-card p {
        color: #666;
        margin-bottom: 20px;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Render Support Tab - Always available in free version
 */
private function renderSupportTab() {
    ?>
    <div class="shopglut-support-tab">
        <div class="support-header">
            <h2><?php echo esc_html__('Help & Support', 'shopglut'); ?></h2>
            <p><?php echo esc_html__('Get help with Shopglut Wishlist plugin. Find answers, contact support, and access documentation.', 'shopglut'); ?></p>
        </div>

        <div class="support-content">
            <div class="support-grid">
                <!-- Documentation Section -->
                <div class="support-card">
                    <div class="support-icon">📚</div>
                    <h3><?php echo esc_html__('Documentation', 'shopglut'); ?></h3>
                    <p><?php echo esc_html__('Browse our comprehensive documentation to get started quickly.', 'shopglut'); ?></p>
                    <a href="https://www.documentation.appglut.com/shopglut-wishlist/" target="_blank" class="button button-primary">
                        <?php echo esc_html__('View Documentation', 'shopglut'); ?>
                    </a>
                </div>

               

                <!-- Contact Support -->
                <div class="support-card">
                    <div class="support-icon">💬</div>
                    <h3><?php echo esc_html__('Contact Support', 'shopglut'); ?></h3>
                    <p><?php echo esc_html__('Need help? Our support team is here to assist you.', 'shopglut'); ?></p>
                    <a href="https://www.appglut.com/support" target="_blank" class="button button-primary">
                        <?php echo esc_html__('Contact Us', 'shopglut'); ?>
                    </a>
                </div>

                <!-- Feature Requests -->
                <div class="support-card">
                    <div class="support-icon">💡</div>
                    <h3><?php echo esc_html__('Feature Requests', 'shopglut'); ?></h3>
                    <p><?php echo esc_html__('Have an idea? Submit feature requests and suggestions.', 'shopglut'); ?></p>
                    <a href="https://www.appglut.com/support/forum/plugin-feature-request/" target="_blank" class="button button-primary">
                        <?php echo esc_html__('Submit Request', 'shopglut'); ?>
                    </a>
                </div>
            </div>


            <!-- System Information -->
            <div class="system-info-section">
                <h3><?php echo esc_html__('System Information', 'shopglut'); ?></h3>
                <div class="system-info-grid">
                    <div class="info-item">
                        <strong><?php echo esc_html__('Plugin Version:', 'shopglut'); ?></strong>
                        <span><?php echo esc_html( defined('SHOPGLUT_VERSION') ? SHOPGLUT_VERSION : '1.0.0' ); ?></span>
                    </div>
                    <div class="info-item">
                        <strong><?php echo esc_html__('WordPress Version:', 'shopglut'); ?></strong>
                        <span><?php echo esc_html( get_bloginfo('version') ); ?></span>
                    </div>
                    <div class="info-item">
                        <strong><?php echo esc_html__('WooCommerce Version:', 'shopglut'); ?></strong>
                        <span><?php echo esc_html( defined('WC_VERSION') ? WC_VERSION : __('Not Installed', 'shopglut') ); ?></span>
                    </div>
                    <div class="info-item">
                        <strong><?php echo esc_html__('PHP Version:', 'shopglut'); ?></strong>
                        <span><?php echo PHP_VERSION; ?></span>
                    </div>
                </div>
                
               
            </div>
        </div>
    </div>

<style>
    .shopglut-support-tab {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .support-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .support-header h2 {
        color: #333;
        margin-bottom: 10px;
    }

    .support-header p {
        color: #666;
        font-size: 16px;
    }

    .support-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .support-card {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #eee;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .support-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .support-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .support-card h3 {
        color: #333;
        margin-bottom: 15px;
    }

    .support-card p {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .quick-help-section, .system-info-section {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        margin-top: 30px;
    }

    .help-accordion {
        margin-top: 20px;
    }

    .help-item {
        border-bottom: 1px solid #eee;
        margin-bottom: 10px;
    }

    .help-question {
        width: 100%;
        background: none;
        border: none;
        padding: 15px 0;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
        font-weight: 500;
        color: #333;
    }

    .help-question:hover {
        color: #0073aa;
    }

    .help-toggle {
        font-size: 20px;
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .help-answer {
        display: none;
        padding: 0 0 20px 0;
        color: #666;
        line-height: 1.6;
    }

    .help-answer.show {
        display: block;
    }

    .help-item.active .help-toggle {
        transform: rotate(45deg);
    }

    .system-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .system-actions {
        margin-top: 20px;
    }

    .system-actions .button {
        margin-right: 10px;
    }
</style>

<script>
    function toggleHelp(button) {
        const item = button.closest('.help-item');
        const answer = item.querySelector('.help-answer');
        const isActive = item.classList.contains('active');
        
        // Close all other items
        document.querySelectorAll('.help-item').forEach(el => {
            el.classList.remove('active');
            el.querySelector('.help-answer').classList.remove('show');
        });
        
        // Toggle current item
        if (!isActive) {
            item.classList.add('active');
            answer.classList.add('show');
        }
    }

    function copySystemInfo() {
        const systemInfo = `
        Plugin Version: <?php echo esc_js( defined('SHOPGLUT_WISHLIST_VERSION') ? SHOPGLUT_WISHLIST_VERSION : '1.0.0' ); ?>
        WordPress Version: <?php echo esc_js( get_bloginfo('version') ); ?>
        WooCommerce Version: <?php echo esc_js( defined('WC_VERSION') ? WC_VERSION : 'Not Installed' ); ?>
        PHP Version: <?php echo PHP_VERSION; ?>
        Site URL: <?php echo esc_js( home_url() ); ?>
            `.trim();
        
        navigator.clipboard.writeText(systemInfo).then(function() {
            alert('<?php echo esc_js(__('System information copied to clipboard!', 'shopglut')); ?>');
        }).catch(function() {
            console.log('Failed to copy system info');
        });
    }
    </script>
    <?php
}
    /**
     * Render Settings Tab using AGSHOPGLUT
     */
    private function renderSettingsTab() {
        // Try to get instance
        $instance = $this->getInstanceWithFallback();
        
        if (!$instance) {
            ?>
            <div class="settings-content">
                <div class="notice notice-error">
                    <p><?php echo esc_html__('Settings are not available at this time. Please refresh the page.', 'shopglut'); ?></p>
                    <p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=shopglut_wishlist&tab=settings')); ?>" class="button button-primary">
                            <?php echo esc_html__('Refresh Page', 'shopglut'); ?>
                        </a>
                    </p>
                </div>
            </div>
            <?php
            return;
        }

        // Get current subtab
        // Valid subtab names to prevent potential issues
        $valid_subtabs = array('general', 'templates', 'advanced', 'pro', 'numbering', 'display', 'email');
        $current_subtab = isset($_GET['subtab']) ? sanitize_text_field(wp_unslash($_GET['subtab'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_subtab = in_array($current_subtab, $valid_subtabs, true) ? $current_subtab : '';

        ?>
        <div class="settings-content">
            <?php if ($current_subtab): ?>
                <div class="settings-nav">
                    <nav class="nav-tab-wrapper settings-nav-tabs">
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'general') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'general' ? 'nav-tab-active' : ''; ?>">
                            🔧 <?php echo esc_html__('General', 'shopglut'); ?>
                        </a>
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'wishlist-page') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'wishlist-page' ? 'nav-tab-active' : ''; ?>">
                            📋 <?php echo esc_html__('Wishlist Page', 'shopglut'); ?>
                        </a>
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'account-page') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'account-page' ? 'nav-tab-active' : ''; ?>">
                            👤 <?php echo esc_html__('Account Page', 'shopglut'); ?>
                        </a>
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'product-page') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'product-page' ? 'nav-tab-active' : ''; ?>">
                            🛍️ <?php echo esc_html__('Product Page', 'shopglut'); ?>
                        </a>
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'shop-page') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'shop-page' ? 'nav-tab-active' : ''; ?>">
                            🏪 <?php echo esc_html__('Shop Page', 'shopglut'); ?>
                        </a>
                        <a href="<?php echo esc_url( $this->getTabUrl('settings', 'archive-page') ); ?>" 
                           class="nav-tab <?php echo $current_subtab === 'archive-page' ? 'nav-tab-active' : ''; ?>">
                            📁 <?php echo esc_html__('Archive Page', 'shopglut'); ?>
                        </a>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Render AGSHOPGLUT Settings -->
            <div class="agshopglut-settings-wrapper">
                <?php $this->renderAGSHOPGLUTSettings($current_subtab, $instance); ?>
            </div>
        </div>
        
        <?php $this->renderSettingsStyles(); ?>
        <?php
    }

    /**
     * Render AGSHOPGLUT Settings with optional filtering
     */
    private function renderAGSHOPGLUTSettings($subtab = '', $instance = null) {
        if (!$instance) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Settings instance not available.', 'shopglut') . '</p></div>';
            return;
        }

        // Check if this is a full AGSHOPGLUT_Options instance
        if (method_exists($instance, 'add_options_html')) {
            if ($subtab) {
                $this->renderFilteredAGSHOPGLUTSettings($subtab, $instance);
            } else {
                // Render all settings using AGSHOPGLUT's native method
                $instance->add_options_html();
            }
        } else {
            // This is our fallback instance, show basic options
            $this->renderBasicOptionsForm($instance);
        }
    }

    /**
     * Render filtered AGSHOPGLUT settings for specific subtab
     */
    private function renderFilteredAGSHOPGLUTSettings($subtab, $instance) {
        // Get sections that match the subtab
        $filtered_sections = $this->getFilteredSections($subtab, $instance);
        
        if (empty($filtered_sections)) {
            echo '<div class="notice notice-info"><p>' . esc_html__('No settings available for this section.', 'shopglut') . '</p></div>';
            return;
        }

        // Render filtered settings
        $this->renderCustomAGSHOPGLUTForm($filtered_sections, $instance);
    }

    /**
     * Filter sections based on subtab
     */
    private function getFilteredSections($subtab, $instance) {
        $all_sections = isset($instance->pre_sections) ? $instance->pre_sections : array();
        $filtered_sections = array();

        // Map subtabs to section titles
        $subtab_mapping = array(
            'general' => array('General'),
            'product-page' => array('Product Page'),
            'shop-page' => array('Shop Page'),
            'archive-page' => array('Archive Page'),
            'wishlist-page' => array('Wishlist Page'),
            'account-page' => array('Account Page')
        );

        if (!isset($subtab_mapping[$subtab])) {
            return $filtered_sections;
        }

        $target_titles = $subtab_mapping[$subtab];

        foreach ($all_sections as $section) {
            if (isset($section['title']) && in_array($section['title'], $target_titles)) {
                $filtered_sections[] = $section;
            }
        }

        return $filtered_sections;
    }

    /**
     * Render custom AGSHOPGLUT form with filtered sections
     */
    private function renderCustomAGSHOPGLUTForm($sections, $instance) {
        $has_nav = false; // No navigation for filtered view
        $show_all = ' agl-show-all';
        $ajax_class = (isset($instance->args['ajax_save']) && $instance->args['ajax_save']) ? ' agl-save-ajax' : '';
        $wrapper_class = (isset($instance->args['framework_class']) && $instance->args['framework_class']) ? ' ' . $instance->args['framework_class'] : '';
        $theme = (isset($instance->args['theme']) && $instance->args['theme']) ? ' agl-theme-' . $instance->args['theme'] : '';
        $form_action = (isset($instance->args['form_action']) && $instance->args['form_action']) ? $instance->args['form_action'] : '';

        ?>
        <div class="agl agl-options agl-wishlist-embedded<?php echo esc_attr($theme . $wrapper_class); ?>" 
             data-slug="<?php echo esc_attr(isset($instance->args['menu_slug']) ? $instance->args['menu_slug'] : ''); ?>" 
             data-unique="<?php echo esc_attr($instance->unique); ?>">
            
            <div class="agl-container">
                <form method="post" action="<?php echo esc_attr($form_action); ?>" 
                      enctype="multipart/form-data" id="agl-form" autocomplete="off" novalidate="novalidate">
                    
                    <input type="hidden" class="agl-section-id" name="agl_transient[section]" value="1">
                    <?php wp_nonce_field('agl_options_nonce', 'agl_options_nonce' . $instance->unique); ?>

                    <!-- Form messages -->
                    <?php $this->renderFormMessages($instance); ?>

                    <div class="agl-wrapper<?php echo esc_attr($show_all); ?>">
                        <div class="agl-content">
                            <div class="agl-sections">
                                <?php foreach ($sections as $section): ?>
                                    <div class="agl-section agl-onload" data-section-id="<?php echo esc_attr(sanitize_title($section['title'] ?? '')); ?>">
                                        
                                        <?php if (!empty($section['title'])): ?>
                                            <div class="agl-section-title">
                                                <h3>
                                                    <?php if (!empty($section['icon'])): ?>
                                                        <i class="agl-section-icon <?php echo esc_attr($section['icon']); ?>"></i>
                                                    <?php endif; ?>
                                                    <?php echo esc_html($section['title']); ?>
                                                </h3>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($section['description'])): ?>
                                            <div class="agl-field agl-section-description">
                                                <?php echo wp_kses_post($section['description']); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($section['fields'])): ?>
                                            <?php foreach ($section['fields'] as $field): ?>
                                                <?php $this->renderAGSHOPGLUTField($field, $instance); ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="agl-no-option">
                                                <?php echo esc_html__('No options available for this section.', 'shopglut'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <?php if (!empty($instance->args['show_footer'])): ?>
                        <div class="agl-footer">
                            <div class="agl-buttons">
                                <input type="submit" name="agl_transient[save]" 
                                       class="button button-primary agl-save<?php echo esc_attr($ajax_class); ?>" 
                                       value="<?php echo esc_attr__('Save Settings', 'shopglut'); ?>" 
                                       data-save="<?php echo esc_attr__('Saving...', 'shopglut'); ?>">
                                
                                <?php if (isset($instance->args['show_reset_section']) && $instance->args['show_reset_section']): ?>
                                    <input type="submit" name="agl_transient[reset_section]" 
                                           class="button button-secondary agl-reset-section agl-confirm" 
                                           value="<?php echo esc_attr__('Reset Section', 'shopglut'); ?>" 
                                           data-confirm="<?php echo esc_attr__('Are you sure to reset this section options?', 'shopglut'); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render individual AGSHOPGLUT field
     */
    private function renderAGSHOPGLUTField($field, $instance) {
        // Check for field errors
        if (method_exists($instance, 'error_check')) {
            $is_field_error = $instance->error_check($field);
            if (!empty($is_field_error)) {
                $field['_error'] = $is_field_error;
            }
        }

        // Set field default
        if (!empty($field['id']) && method_exists($instance, 'get_default')) {
            $field['default'] = $instance->get_default($field);
        }

        // Get field value
        $value = '';
        if (!empty($field['id']) && isset($instance->options[$field['id']])) {
            $value = $instance->options[$field['id']];
        }

        // Render using AGSHOPGLUT
        AGSHOPGLUT::field($field, $value, $instance->unique, 'options');
    }

    /**
     * Render form messages
     */
    private function renderFormMessages($instance) {
        if (isset($instance->args['show_form_warning']) && $instance->args['show_form_warning']) {
            echo '<div class="agl-form-result agl-form-warning">' . esc_html__('You have unsaved changes, save your changes!', 'shopglut') . '</div>';
        }

        $notice_class = (!empty($instance->notice)) ? 'agl-form-show' : '';
        $notice_text = (!empty($instance->notice)) ? $instance->notice : '';

        echo '<div class="agl-form-result agl-form-success ' . esc_attr($notice_class) . '">' . wp_kses_post($notice_text) . '</div>';
    }

    /**
     * Render basic options form for fallback
     */
    private function renderBasicOptionsForm($instance) {
        ?>
        <div class="basic-options-form">
            <h3><?php echo esc_html__('Settings', 'shopglut'); ?></h3>
            <form method="post" action="options.php">
                <?php settings_fields('agshopglut_pdf_invoices_options'); ?>
                <table class="form-table">
                    <?php foreach ($instance->options as $key => $value): ?>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr($key); ?>">
                                    <?php echo esc_html(str_replace('-', ' ', ucwords($key, '-'))); ?>
                                </label>
                            </th>
                            <td>
                                <?php if (is_bool($value) || $value === '1' || $value === '0'): ?>
                                    <label>
                                        <input type="checkbox" 
                                               id="<?php echo esc_attr($key); ?>"
                                               name="agshopglut_pdf_invoices_options[<?php echo esc_attr($key); ?>]" 
                                               value="1" 
                                               <?php checked($value, 1); ?>>
                                        <?php echo esc_html__('Enable', 'shopglut'); ?>
                                    </label>
                                <?php else: ?>
                                    <input type="text" 
                                           id="<?php echo esc_attr($key); ?>"
                                           name="agshopglut_pdf_invoices_options[<?php echo esc_attr($key); ?>]" 
                                           value="<?php echo esc_attr($value); ?>" 
                                           class="regular-text">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get saved option value
     */
    public function getOption($option_key, $default = '') {
        $instance = $this->getInstanceWithFallback();
        
        if (!$instance || !isset($instance->options)) {
            // Fallback to direct database access
            $options = get_option('agshopglut_pdf_invoices_options', array());
            return isset($options[$option_key]) ? $options[$option_key] : $default;
        }

        return isset($instance->options[$option_key]) ? $instance->options[$option_key] : $default;
    }

    /**
     * Get all options
     */
    public function getEnhancements() {
        $instance = $this->getInstanceWithFallback();
        
        if (!$instance || !isset($instance->options)) {
            return get_option('agshopglut_pdf_invoices_options', array());
        }

        return $instance->options;
    }

    /**
     * Helper method to get tab URL with subtab support
     */
    private function getTabUrl($tab, $subtab = '') {
        $url = admin_url('admin.php?page=shopglut_pdf_invoices_slips&tab=' . $tab);
        if ($subtab) {
            $url .= '&subtab=' . $subtab;
        }
        return $url;
    }
private function renderSettingsStyles() {
        ?>
        <style>
        .shopglut-pdf-invoices-settings .agl-options {
             margin-top:0px;
             padding-top:8px;
        }
        .basic-options-form {
            background: #fff;
            padding: 20px;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
        }
        
        .settings-nav-tabs {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        
        .settings-nav-tabs .nav-tab {
            font-size: 13px;
            padding: 8px 15px;
        }
        
        .agl-wishlist-embedded {
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            background: #fff;
        }
        
        .agl-wishlist-embedded .agl-container {
            padding: 0;
        }
        
        .agl-wishlist-embedded .agl-header,
        .agl-wishlist-embedded .agl-nav {
            display: none !important;
        }
        
        .agl-wishlist-embedded .agl-wrapper {
            margin: 0;
            border: none;
            background: transparent;
        }
        
        .agl-wishlist-embedded .agl-content {
            padding: 20px;
            margin: 0;
        }
        </style>
        <?php
    }

    private function getDashboardData() {
        global $wpdb;
        
        $table_name = $this->table_shopg_wishlist();
        
        // Validate table name for security
        if (!$this->sanitize_table_name($table_name)) {
            return array('error' => 'Invalid table name');
        }
        
        // Initialize data array
        $data = [];
        
        // Get current date for trends
        $last_month_date = gmdate('Y-m-d', strtotime('-30 days')); // Use gmdate
        $last_week_date = gmdate('Y-m-d', strtotime('-7 days')); // Use gmdate
        
        // Total Users (both registered and guest)
        $cache_key = 'shopglut_total_users_' . md5($table_name);
        $total_users = wp_cache_get($cache_key);
        if ($total_users === false) {
            // Use sprintf to safely inject table name since prepare() cannot handle table names
            $query = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s", esc_sql($table_name));
            $total_users = $wpdb->get_var($query); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            wp_cache_set($cache_key, $total_users, '', 300); // Cache for 5 minutes
        }
        
        // Guest Users (users with guest_ prefix, guest email, or Guest username)
        $cache_key = 'shopglut_guest_users_' . md5($table_name);
        $guest_users = wp_cache_get($cache_key);
        if ($guest_users === false) {
            // Use sprintf for table name and prepare for parameters
            $query = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s 
                 WHERE wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s", esc_sql($table_name));
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $guest_users = $wpdb->get_var($wpdb->prepare(
                $query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                'guest_%',
                '%guest_%',
                'Guest%'
            ));
            wp_cache_set($cache_key, $guest_users, '', 300); // Cache for 5 minutes
        }
        
        // Registered Users
        $registered_users = $total_users - $guest_users;
        
        // Total Wishlists
        $cache_key = 'shopglut_total_wishlists_' . md5($table_name);
        $total_wishlists = wp_cache_get($cache_key);
        if ($total_wishlists === false) {
                      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $total_wishlists = $wpdb->get_var(
                "SELECT COUNT(*) FROM `" . esc_sql($table_name) . "`"
            );
            wp_cache_set($cache_key, $total_wishlists, '', 300); // Cache for 5 minutes
        }
        
        // Total Products in Wishlists
        $cache_key = 'shopglut_total_products_' . md5($table_name);
        $total_products = wp_cache_get($cache_key);
        if ($total_products === false) {
            // Complex statistical query
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $total_products = $wpdb->get_var(
                "
                    SELECT SUM(
                        CASE
                            WHEN product_ids = '' THEN 0
                            ELSE (LENGTH(product_ids) - LENGTH(REPLACE(product_ids, ',', '')) + 1)
                        END
                    ) FROM `" . esc_sql($table_name) . "` WHERE product_ids != ''
                "
            );
            wp_cache_set($cache_key, $total_products, '', 300); // Cache for 5 minutes
        }
        
        // Average products per wishlist
        $avg_products_per_wishlist = $total_wishlists > 0 ? ($total_products / $total_wishlists) : 0;
        
        // Get trends (last 30 days vs previous 30 days)
        $users_trend = $this->calculateTrend($table_name, 'wish_user_id', 'DISTINCT', 30);
        $guest_trend = $this->calculateGuestTrend($table_name, 30);
        $registered_trend = $this->calculateRegisteredTrend($table_name, 30);
        $wishlists_trend = $this->calculateTrend($table_name, 'id', 'COUNT', 30);
        $products_trend = $this->calculateProductsTrend($table_name, 30);
        $avg_trend = $this->calculateAverageTrend($table_name, 30);
        
        // Recent Activity (last 50 activities)
        $cache_key = 'shopglut_recent_activity_' . md5($table_name);
        $recent_activity = wp_cache_get($cache_key);
       if ( $recent_activity === false ) {
          global $wpdb;

    // Build the base query
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $recent_activity = $wpdb->get_results(
                $wpdb->prepare(
                    "
                    SELECT w.*,
                        CASE
                            WHEN w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s
                            THEN 'guest'
                            ELSE 'registered'
                        END as user_type,
                        w.product_added_time as activity_date
                    FROM `" . esc_sql($table_name) . "` w
                    ORDER BY w.product_added_time DESC
                    LIMIT 50
                    ",
                    'guest_%',
                    '%guest_%',
                    'Guest%'
                )
            );


            wp_cache_set( $cache_key, $recent_activity, '', 300 ); // Cache for 5 minutes
        }

        // Process recent activity
        $processed_activity = [];
        foreach ($recent_activity as $activity) {
            $product_ids = explode(',', $activity->product_ids);
            $product_dates = json_decode($activity->product_individual_dates, true);
            
            foreach ($product_ids as $index => $product_id) {
                if (empty($product_id)) continue;
                
                $product = wc_get_product($product_id);
                if (!$product) continue;
                
                $processed_activity[] = [
                    'username' => $activity->username ?: 'Unknown User',
                    'useremail' => $activity->useremail ?: 'No Email',
                    'user_type' => $activity->user_type,
                    'product_name' => $product->get_name(),
                    'product_price' => $product->get_price(),
                    'action_type' => 'added',
                    'action_text' => __('Added to Wishlist', 'shopglut'),
                    'date_formatted' => wp_date('M j, Y g:i A', strtotime($activity->activity_date)) // Use wp_date()
                ];
                
                // Limit to 20 items for display
                if (count($processed_activity) >= 20) break 2;
            }
        }
        
        // Top Products
        $cache_key = 'shopglut_top_products_' . md5($table_name);
        $top_products_query = wp_cache_get($cache_key);
        if ($top_products_query === false) {

            // Build the complex query
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $top_products_query = $wpdb->get_results($wpdb->prepare(
                "
                SELECT
                    SUBSTRING_INDEX(SUBSTRING_INDEX(w.product_ids, ',', numbers.n), ',', -1) as product_id,
                    COUNT(*) as wishlist_count,
                    SUM(CASE WHEN w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s THEN 1 ELSE 0 END) as guest_count,
                    SUM(CASE WHEN NOT (w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s) THEN 1 ELSE 0 END) as registered_count
                 FROM
                 `" . esc_sql($table_name) . "` w
                 JOIN (
                    SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
                    UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
                 ) numbers ON CHAR_LENGTH(w.product_ids) - CHAR_LENGTH(REPLACE(w.product_ids, ',', '')) >= numbers.n - 1
                 WHERE w.product_ids != ''
                 AND SUBSTRING_INDEX(SUBSTRING_INDEX(w.product_ids, ',', numbers.n), ',', -1) != ''
                 GROUP BY product_id
                 ORDER BY wishlist_count DESC
                 LIMIT 10
                ",
                'guest_%',
                '%guest_%',
                'Guest%',
                'guest_%',
                '%guest_%',
                'Guest%'
            ));
            wp_cache_set($cache_key, $top_products_query, '', 300); // Cache for 5 minutes
        }
        
        $top_products = [];
        $top_products_labels = [];
        $top_products_data = [];
        
        foreach ($top_products_query as $product_data) {
            $product = wc_get_product($product_data->product_id);
            if (!$product) continue;
            
            $image_id = $product->get_image_id();
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : wc_placeholder_img_src();
            
            $top_products[] = [
                'id' => $product_data->product_id,
                'name' => $product->get_name(),
                'image' => $image_url,
                'wishlist_count' => $product_data->wishlist_count,
                'guest_count' => $product_data->guest_count,
                'registered_count' => $product_data->registered_count,
                'stock_status' => $product->get_stock_status(),
                'stock_text' => $product->get_stock_status() === 'instock' ? __('In Stock', 'shopglut') : __('Out of Stock', 'shopglut'),
                'price' => $product->get_price()
            ];
            
            $top_products_labels[] = $product->get_name();
            $top_products_data[] = (int)$product_data->wishlist_count;
        }
        
        // Activity chart data (last 30 days)
        $activity_data = $this->getActivityChartData(30);
        
        // Compile all data
        $data = [
            'total_users' => (int)$total_users,
            'guest_users' => (int)$guest_users,
            'registered_users' => (int)$registered_users,
            'total_wishlists' => (int)$total_wishlists,
            'total_products' => (int)$total_products,
            'avg_products_per_wishlist' => (float)$avg_products_per_wishlist,
            'users_trend' => $users_trend,
            'guest_trend' => $guest_trend,
            'registered_trend' => $registered_trend,
            'wishlists_trend' => $wishlists_trend,
            'products_trend' => $products_trend,
            'avg_trend' => $avg_trend,
            'recent_activity' => $processed_activity,
            'top_products' => $top_products,
            'top_products_labels' => $top_products_labels,
            'top_products_data' => $top_products_data,
            'activity_labels' => $activity_data['labels'],
            'activity_data' => $activity_data['data'],
            'guest_activity_data' => $activity_data['guest_data']
        ];
        
        return $data;
    }

// Helper method to calculate trends
private function calculateTrend($table_name, $field, $function = 'COUNT', $days = 30) {
    global $wpdb;
    
    // Validate table name for security
    if (!$this->sanitize_table_name($table_name)) {
        return array('current' => 0, 'previous' => 0, 'trend' => 0);
    }
    
    // Validate function name (only allow safe SQL functions)
    $allowed_functions = array('COUNT', 'SUM', 'AVG', 'MAX', 'MIN');
    if (!in_array(strtoupper($function), $allowed_functions, true)) {
        $function = 'COUNT';
    }
    
    // Validate field name (only allow known safe field names)
    $allowed_fields = array('wish_user_id', 'product_added_time', 'id', '*');
    if (!in_array($field, $allowed_fields, true)) {
        $field = '*';
    }
    
    $current_period_start = gmdate('Y-m-d', strtotime("-{$days} days"));
    $previous_period_start = gmdate('Y-m-d', strtotime("-" . ($days * 2) . " days"));
    $previous_period_end = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    $cache_key_current = 'shopglut_trend_current_' . md5($table_name . $field . $function . $current_period_start);
    $current_count = wp_cache_get($cache_key_current);
    if ($current_count === false) {
        // Use sprintf for table name, function, and field names, then prepare for parameters
        $query = sprintf("SELECT %s(%s) FROM %s WHERE DATE(product_added_time) >= %%s", 
                        esc_sql($function), 
                        esc_sql($field), 
                        esc_sql($table_name));
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $current_count = $wpdb->get_var($wpdb->prepare(
            $query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start
        ));
        wp_cache_set($cache_key_current, $current_count, '', 300);
    }
    
    $cache_key_previous = 'shopglut_trend_previous_' . md5($table_name . $field . $function . $previous_period_start . $previous_period_end);
    $previous_count = wp_cache_get($cache_key_previous);
    if ($previous_count === false) {
        // Use sprintf for table name, function, and field names, then prepare for parameters
        $query = sprintf("SELECT %s(%s) FROM %s WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s", 
                        esc_sql($function), 
                        esc_sql($field), 
                        esc_sql($table_name));
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $previous_count = $wpdb->get_var($wpdb->prepare(
            $query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end
        ));
        wp_cache_set($cache_key_previous, $previous_count, '', 300);
    }
    
    if ($previous_count == 0) {
        return $current_count > 0 ? 100 : 0;
    }
    
    return round((($current_count - $previous_count) / $previous_count) * 100, 1);
}

// Helper method to calculate guest user trend
private function calculateGuestTrend($table_name, $days = 30) {
    global $wpdb;
    
    $current_period_start = gmdate('Y-m-d', strtotime("-{$days} days"));
    $previous_period_start = gmdate('Y-m-d', strtotime("-" . ($days * 2) . " days"));
    $previous_period_end = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    $cache_key_current = 'shopglut_guest_trend_current_' . md5($table_name . $current_period_start);
    $current_count = wp_cache_get($cache_key_current);
    if ($current_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s 
             WHERE DATE(product_added_time) >= %%s 
             AND (wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s)", esc_sql($table_name));
        $current_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start,
            'guest_%',
            '%guest_%',
            'Guest%'
        ));
        wp_cache_set($cache_key_current, $current_count, '', 300);
    }
    
    $cache_key_previous = 'shopglut_guest_trend_previous_' . md5($table_name . $previous_period_start . $previous_period_end);
    $previous_count = wp_cache_get($cache_key_previous);
    if ($previous_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s 
             WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s
             AND (wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s)", esc_sql($table_name));
        $previous_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end,
            'guest_%',
            '%guest_%',
            'Guest%'
        ));
        wp_cache_set($cache_key_previous, $previous_count, '', 300);
    }
    
    if ($previous_count == 0) {
        return $current_count > 0 ? 100 : 0;
    }
    
    return round((($current_count - $previous_count) / $previous_count) * 100, 1);
}

// Helper method to calculate registered user trend
private function calculateRegisteredTrend($table_name, $days = 30) {
    global $wpdb;
    
    $current_period_start = gmdate('Y-m-d', strtotime("-{$days} days"));
    $previous_period_start = gmdate('Y-m-d', strtotime("-" . ($days * 2) . " days"));
    $previous_period_end = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    $cache_key_current = 'shopglut_registered_trend_current_' . md5($table_name . $current_period_start);
    $current_count = wp_cache_get($cache_key_current);
    if ($current_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s 
             WHERE DATE(product_added_time) >= %%s 
             AND NOT (wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s)", esc_sql($table_name));
        $current_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start,
            'guest_%',
            '%guest_%',
            'Guest%'
        ));
        wp_cache_set($cache_key_current, $current_count, '', 300);
    }
    
    $cache_key_previous = 'shopglut_registered_trend_previous_' . md5($table_name . $previous_period_start . $previous_period_end);
    $previous_count = wp_cache_get($cache_key_previous);
    if ($previous_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(DISTINCT wish_user_id) FROM %s 
             WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s
             AND NOT (wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s)", esc_sql($table_name));
        $previous_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end,
            'guest_%',
            '%guest_%',
            'Guest%'
        ));
        wp_cache_set($cache_key_previous, $previous_count, '', 300);
    }
    
    if ($previous_count == 0) {
        return $current_count > 0 ? 100 : 0;
    }
    
    return round((($current_count - $previous_count) / $previous_count) * 100, 1);
}

// Helper method to calculate products trend
private function calculateProductsTrend($table_name, $days = 30) {
    global $wpdb;
    
    $current_period_start = gmdate('Y-m-d', strtotime("-{$days} days"));
    $previous_period_start = gmdate('Y-m-d', strtotime("-" . ($days * 2) . " days"));
    $previous_period_end = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    $cache_key_current = 'shopglut_products_trend_current_' . md5($table_name . $current_period_start);
    $current_count = wp_cache_get($cache_key_current);
    if ($current_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT SUM(
                CASE 
                    WHEN product_ids = '' THEN 0 
                    ELSE (LENGTH(product_ids) - LENGTH(REPLACE(product_ids, ',', '')) + 1) 
                END
            ) FROM %s WHERE DATE(product_added_time) >= %%s AND product_ids != ''", esc_sql($table_name));
        $current_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start
        ));
        wp_cache_set($cache_key_current, $current_count, '', 300);
    }
    
    $cache_key_previous = 'shopglut_products_trend_previous_' . md5($table_name . $previous_period_start . $previous_period_end);
    $previous_count = wp_cache_get($cache_key_previous);
    if ($previous_count === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT SUM(
                CASE 
                    WHEN product_ids = '' THEN 0 
                    ELSE (LENGTH(product_ids) - LENGTH(REPLACE(product_ids, ',', '')) + 1) 
                END
            ) FROM %s WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s AND product_ids != ''", esc_sql($table_name));
        $previous_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end
        ));
        wp_cache_set($cache_key_previous, $previous_count, '', 300);
    }
    
    if ($previous_count == 0) {
        return $current_count > 0 ? 100 : 0;
    }
    
    return round((($current_count - $previous_count) / $previous_count) * 100, 1);
}

// Helper method to calculate average trend
private function calculateAverageTrend($table_name, $days = 30) {
    global $wpdb;
    
    $current_period_start = gmdate('Y-m-d', strtotime("-{$days} days"));
    $previous_period_start = gmdate('Y-m-d', strtotime("-" . ($days * 2) . " days"));
    $previous_period_end = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    // Current period
    $cache_key_current_wishlists = 'shopglut_avg_trend_current_wishlists_' . md5($table_name . $current_period_start);
    $current_wishlists = wp_cache_get($cache_key_current_wishlists);
    if ($current_wishlists === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(*) FROM %s WHERE DATE(product_added_time) >= %%s", esc_sql($table_name));
        $current_wishlists = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start
        ));
        wp_cache_set($cache_key_current_wishlists, $current_wishlists, '', 300);
    }
    
    $cache_key_current_products = 'shopglut_avg_trend_current_products_' . md5($table_name . $current_period_start);
    $current_products = wp_cache_get($cache_key_current_products);
    if ($current_products === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT SUM(
                CASE 
                    WHEN product_ids = '' THEN 0 
                    ELSE (LENGTH(product_ids) - LENGTH(REPLACE(product_ids, ',', '')) + 1) 
                END
            ) FROM %s WHERE DATE(product_added_time) >= %%s AND product_ids != ''", esc_sql($table_name));
        $current_products = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $current_period_start
        ));
        wp_cache_set($cache_key_current_products, $current_products, '', 300);
    }
    
    $current_avg = $current_wishlists > 0 ? ($current_products / $current_wishlists) : 0;
    
    // Previous period
    $cache_key_previous_wishlists = 'shopglut_avg_trend_previous_wishlists_' . md5($table_name . $previous_period_start . $previous_period_end);
    $previous_wishlists = wp_cache_get($cache_key_previous_wishlists);
    if ($previous_wishlists === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT COUNT(*) FROM %s WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s", esc_sql($table_name));
        $previous_wishlists = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end
        ));
        wp_cache_set($cache_key_previous_wishlists, $previous_wishlists, '', 300);
    }
    
    $cache_key_previous_products = 'shopglut_avg_trend_previous_products_' . md5($table_name . $previous_period_start . $previous_period_end);
    $previous_products = wp_cache_get($cache_key_previous_products);
    if ($previous_products === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT SUM(
                CASE 
                    WHEN product_ids = '' THEN 0 
                    ELSE (LENGTH(product_ids) - LENGTH(REPLACE(product_ids, ',', '')) + 1) 
                END
            ) FROM %s WHERE DATE(product_added_time) >= %%s AND DATE(product_added_time) < %%s AND product_ids != ''", esc_sql($table_name));
        $previous_products = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $previous_period_start,
            $previous_period_end
        ));
        wp_cache_set($cache_key_previous_products, $previous_products, '', 300);
    }
    
    $previous_avg = $previous_wishlists > 0 ? ($previous_products / $previous_wishlists) : 0;
    
    if ($previous_avg == 0) {
        return $current_avg > 0 ? 100 : 0;
    }
    
    return round((($current_avg - $previous_avg) / $previous_avg) * 100, 1);
}

// Helper method to get activity chart data
private function getActivityChartData($days = 30) {
    global $wpdb;
    
    $table_name = $this->table_shopg_wishlist();
    $start_date = gmdate('Y-m-d', strtotime("-{$days} days"));
    
    $labels = [];
    $data = [];
    $guest_data = [];
    
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = gmdate('Y-m-d', strtotime("-{$i} days"));
        $labels[] = gmdate('M j', strtotime($date));
        
        // Total wishlists for this date
        $cache_key_total = 'shopglut_activity_total_' . md5($table_name . $date);
        $total_count = wp_cache_get($cache_key_total);
        if ($total_count === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql = sprintf("SELECT COUNT(*) FROM %s WHERE DATE(product_added_time) = %%s", esc_sql($table_name));
            $total_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
                $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                $date
            ));
            wp_cache_set($cache_key_total, $total_count, '', 300);
        }
        
        // Guest wishlists for this date
        $cache_key_guest = 'shopglut_activity_guest_' . md5($table_name . $date);
        $guest_count = wp_cache_get($cache_key_guest);
        if ($guest_count === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql = sprintf("SELECT COUNT(*) FROM %s 
                 WHERE DATE(product_added_time) = %%s 
                 AND (wish_user_id LIKE %%s OR useremail LIKE %%s OR username LIKE %%s)", esc_sql($table_name));
            $guest_count = $wpdb->get_var($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
                $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                $date,
                'guest_%',
                '%guest_%',
                'Guest%'
            ));
            wp_cache_set($cache_key_guest, $guest_count, '', 300);
        }
        
        $data[] = (int)$total_count;
        $guest_data[] = (int)$guest_count;
    }
    
    return [
        'labels' => $labels,
        'data' => $data,
        'guest_data' => $guest_data
    ];
}

// Helper method to get table name
private function table_shopg_wishlist() {
    global $wpdb;
    return $wpdb->prefix . 'shopglut_wishlist';
}

/**
 * Validate and sanitize table name to prevent SQL injection
 * Only allows predefined WordPress table names with the correct prefix
 */
private function sanitize_table_name($table_name) {
    global $wpdb;
    
    // List of allowed table suffixes (without prefix)
    $allowed_tables = array(
        'shopglut_wishlist',
        'posts',
        'postmeta',
        'users',
        'usermeta'
    );
    
    // Check if table name starts with WordPress prefix
    if (strpos($table_name, $wpdb->prefix) !== 0) {
        return false;
    }
    
    // Extract suffix (table name without prefix)
    $suffix = substr($table_name, strlen($wpdb->prefix));
    
    // Check if suffix is in allowed list
    if (!in_array($suffix, $allowed_tables, true)) {
        return false;
    }
    
    return $table_name;
}


private function getRecentActivity($limit = 10) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shopglut_wishlist';
    
    $cache_key = 'shopglut_recent_activities_' . md5($table_name . $limit);
    $activities = wp_cache_get($cache_key);
    if ($activities === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT * FROM %s ORDER BY product_added_time DESC LIMIT %%d", esc_sql($table_name));
        $activities = $wpdb->get_results($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $limit
        ));
        wp_cache_set($cache_key, $activities, '', 300); // Cache for 5 minutes
    }
    
    $result = [];
    foreach ($activities as $activity) {
        $product_ids = array_filter(explode(',', $activity->product_ids));
        $first_product_id = !empty($product_ids) ? $product_ids[0] : 0;
        $product = wc_get_product($first_product_id);
        
        if ($product) {
            $result[] = [
                'username' => $activity->username ?: 'Guest User',
                'useremail' => $activity->useremail ?: 'guest@example.com',
                'product_name' => $product->get_name(),
                'product_price' => $product->get_price(),
                'action_type' => 'added',
                'action_text' => __('Added to Wishlist', 'shopglut'),
                'date_formatted' => wp_date('M j, Y g:i A', strtotime($activity->product_added_time))
            ];
        }
    }
    
    return $result;
}


private function getTopProducts($limit = 10) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shopglut_wishlist';
    
    // Get all product IDs from wishlists
    $cache_key = 'shopglut_product_ids_' . md5($table_name);
    $product_ids_query = wp_cache_get($cache_key);
    if ($product_ids_query === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $sql = sprintf("SELECT product_ids FROM %s WHERE product_ids != %%s", esc_sql($table_name));
        $product_ids_query = $wpdb->get_col($wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
            $sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            ''
        ));
        wp_cache_set($cache_key, $product_ids_query, '', 300); // Cache for 5 minutes
    }
    
    $product_counts = [];
    foreach ($product_ids_query as $product_ids) {
        $ids = array_filter(explode(',', $product_ids));
        foreach ($ids as $id) {
            $id = trim($id);
            if (!empty($id)) {
                $product_counts[$id] = isset($product_counts[$id]) ? $product_counts[$id] + 1 : 1;
            }
        }
    }
    
    // Sort by count
    arsort($product_counts);
    
    // Get top products
    $top_products = [];
    $count = 0;
    foreach ($product_counts as $product_id => $wishlist_count) {
        if ($count >= $limit) break;
        
        $product = wc_get_product($product_id);
        if ($product) {
            $top_products[] = [
                'id' => $product_id,
                'name' => $product->get_name(),
                'wishlist_count' => $wishlist_count,
                'price' => $product->get_price(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'stock_status' => $product->is_in_stock() ? 'in_stock' : 'out_of_stock',
                'stock_text' => $product->is_in_stock() ? __('In Stock', 'shopglut') : __('Out of Stock', 'shopglut')
            ];
            $count++;
        }
    }
    
    return $top_products;
}


private function getTotalWishlists() {
    return 1247;
}

private function renderStyles() {
    // Your existing styles
}

public function enqueueAssets($hook) {
    if (strpos($hook, 'shopglut_pdf_invoices') === false) {
        return;
    }
    wp_enqueue_script('jquery');
}

}