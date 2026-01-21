<?php
if (!defined('ABSPATH')) {
    die;
}

// Dynamic template loading based on selected template
function load_template_settings_dynamically() {
    global $wpdb;

    // Get the current layout ID from URL or POST data
    $layout_id = isset($_GET['layout_id']) ? absint($_GET['layout_id']) : 0;
    if ($layout_id === 0 && isset($_POST['shopg_shop_layoutid'])) {
        $layout_id = absint($_POST['shopg_shop_layoutid']);
    }

    if ($layout_id === 0) {
        // Default to template1 if no layout ID found
        require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template1/template1-settings.php';
        return;
    }

    // Get layout template from database
    $cache_key = "shopglut_layout_template_{$layout_id}";
    $layout_template = wp_cache_get($cache_key, 'shopglut_layouts');

    if (false === $layout_template) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        $layout_template = $wpdb->get_var(
            $wpdb->prepare("SELECT layout_template FROM {$wpdb->prefix}shopglut_single_product_layout WHERE id = %d", $layout_id)
        );
        wp_cache_set($cache_key, $layout_template, 'shopglut_layouts', 30 * MINUTE_IN_SECONDS);
    }

    // Load settings based on template
    switch ($layout_template) {
        case 'templatePro1':
            // Check if pro plugin is active and load pro settings
            if (defined('SHOPGLUT_SINGLEPRODUCT_PRO_PATH') &&
                file_exists(SHOPGLUT_SINGLEPRODUCT_PRO_PATH . 'templates/templatePro1/templatePro1-settings.php')) {
                require_once SHOPGLUT_SINGLEPRODUCT_PRO_PATH . 'templates/templatePro1/templatePro1-settings.php';
            } else {
                // Create pro required metabox with message
                $SHOPG_singleproduct_STYLING = "shopg_singleproduct_settings_templatePro1";

                // Live Preview metabox (show preview even for pro template)
                AGSHOPGLUT::createMetabox(
                    'shopg_single_product_live_preview',
                    array(
                        'title' => __( 'Preview - Demo Mode', 'shopglut' ),
                        'post_type' => 'singleproduct',
                        'context' => 'normal',
                    )
                );
                AGSHOPGLUT::createSection(
                    'shopg_single_product_live_preview',
                    array(
                        'fields' => array(
                            array(
                                'type' => 'preview',
                            ),
                        ),
                    )
                );

                // Pro required metabox with message
                AGSHOPGLUT::createMetabox(
                    $SHOPG_singleproduct_STYLING,
                    array(
                        'title' => esc_html__('⭐ TemplatePro1 Pro Features', 'shopglut'),
                        'post_type' => 'singleproduct',
                        'context' => 'side',
                    )
                );

                AGSHOPGLUT::createSection(
                    $SHOPG_singleproduct_STYLING,
                    array(
                        'fields' => array(
                            array(
                                'type' => 'callback',
                                'function' => 'shopglut_templatePro1_pro_required_message',
                            ),
                        ),
                    )
                );
            }
            break;
        case 'template2':
            require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template2/template2-settings.php';
            break;
        case 'template3':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template3/template3-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template3/template3-settings.php';
            }
            break;
        case 'template4':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template4/template4-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template4/template4-settings.php';
            }
            break;
        case 'template5':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template5/template5-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template5/template5-settings.php';
            }
            break;
        case 'template6':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template6/template6-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template6/template6-settings.php';
            }
            break;
        case 'template7':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template7/template7-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template7/template7-settings.php';
            }
            break;
        case 'template8':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template8/template8-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template8/template8-settings.php';
            }
            break;
        case 'template9':
            // Future template support
            if (file_exists(SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template9/template9-settings.php')) {
                require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template9/template9-settings.php';
            }
            break;
        case 'template1':
        default:
            require_once SHOPGLUT_PATH . 'src/layouts/singleProduct/templates/template1/template1-settings.php';
            break;
    }
}

// Add action hook to load template settings at the right time
add_action('shopglut_layout_metaboxes', function($context) {
    if ($context === 'shopglut') {
        load_template_settings_dynamically();
    }
}, 10);

// Also register for WordPress admin init as backup
add_action('admin_init', function() {
    // Only load on single product editor pages
    if (isset($_GET['page']) && $_GET['page'] === 'shopglut_layouts' &&
        isset($_GET['view']) && $_GET['view'] === 'single_product' &&
        isset($_GET['action']) && $_GET['action'] === 'edit') {
        load_template_settings_dynamically();
    }
}, 15);

// Immediate load for direct access (as fallback)
if (is_admin() &&
    (isset($_GET['layout_id']) || (isset($_POST['shopg_shop_layoutid']) && !empty($_POST['shopg_shop_layoutid'])))) {
    load_template_settings_dynamically();
}

/**
 * Helper function to get current layout template
 * Can be used by other parts of the system
 */
function shopglut_get_current_layout_template() {
    static $current_template = null;

    if ($current_template !== null) {
        return $current_template;
    }

    global $wpdb;

    // Try to get from current layout context
    $layout_id = isset($_GET['layout_id']) ? absint($_GET['layout_id']) :
                 (isset($_POST['shopg_shop_layoutid']) ? absint($_POST['shopg_shop_layoutid']) : 0);

    if ($layout_id === 0) {
        $current_template = 'template1'; // Default
        return $current_template;
    }

    // Get template from database with caching
    $cache_key = "shopglut_current_template_{$layout_id}";
    $current_template = wp_cache_get($cache_key, 'shopglut_layouts');

    if (false === $current_template) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        $current_template = $wpdb->get_var(
            $wpdb->prepare("SELECT layout_template FROM {$wpdb->prefix}shopglut_single_product_layout WHERE id = %d", $layout_id)
        );
        $current_template = $current_template ?: 'template1'; // Fallback
        wp_cache_set($cache_key, $current_template, 'shopglut_layouts', 30 * MINUTE_IN_SECONDS);
    }

    return $current_template;
}

/**
 * Callback function to display pro required message for templatePro1
 */
function shopglut_templatePro1_pro_required_message() {
    ?>
    <div style="padding: 16px; text-align: center;">
        <div style="margin-bottom: 16px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block; margin: 0 auto 16px;">
                <rect x="3" y="3" width="18" height="18" rx="2" stroke="#667eea" stroke-width="2"/>
                <path d="M12 8V12L15 15" stroke="#667eea" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="12" r="3" fill="#667eea" opacity="0.2"/>
            </svg>
            <h3 style="margin: 0 0 8px 0; color: #1a1a1a; font-size: 16px;">
                <?php esc_html_e('Pro Template Active', 'shopglut'); ?>
            </h3>
            <p style="margin: 0 0 16px 0; color: #666; font-size: 13px; line-height: 1.5;">
                <?php esc_html_e('This template requires the Single Product Pro plugin to access settings.', 'shopglut'); ?>
            </p>
        </div>
        <a href="<?php echo esc_url(SHOPGLUT_PRICING_URL); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 16px; background: #667eea; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; transition: all 0.2s ease; box-sizing: border-box;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
            </svg>
            <span><?php esc_html_e('Get Single Product Pro', 'shopglut'); ?></span>
        </a>
        <p style="margin: 12px 0 0 0; color: #888; font-size: 12px;">
            <?php esc_html_e('or activate the plugin from Plugins page', 'shopglut'); ?>
        </p>
    </div>
    <style>
        .shopglut-pro-metabox-link:hover {
            background: #5568d3 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
    </style>
    <?php
}

