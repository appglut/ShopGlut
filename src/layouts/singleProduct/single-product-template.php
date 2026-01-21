<?php
/**
 * Single Product Template for ShopGlut
 * Uses WooCommerce hooks to inject content while preserving theme
 */

if (!defined('ABSPATH')) {
    exit;
}

global $shopglut_custom_layout;

if (empty($shopglut_custom_layout)) {
    // No custom layout, load default WooCommerce template
    wc_get_template('single-product.php');
    exit;
}

// Setup hooks before loading the template
add_action('wp_head', function() {
    // Add CSS to hide default WooCommerce product content
    echo '<style>
        .woocommerce .product,
        .woocommerce-page .product {
            display: none !important;
        }
        .shopglut-custom-product-wrapper {
            display: block !important;
        }
    </style>';
}, 999);

// Hook before main content to inject our custom content
add_action('woocommerce_before_main_content', function() {
    global $shopglut_custom_layout;

    if (empty($shopglut_custom_layout)) {
        return;
    }

    echo '<div class="shopglut-custom-product-wrapper">';

    $layout_template = $shopglut_custom_layout['layout_template'];
    $is_pro_template = (strpos($layout_template, 'templatePro') === 0);

    if ($is_pro_template) {
        $template_markup_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/templateMarkup.php';
        $template_style_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/templateStyle.php';
        $markup_class = 'Shopglut\\layouts\\singleProduct\\templates\\' . $layout_template . '\\templateMarkup';
        $style_class = 'Shopglut\\layouts\\singleProduct\\templates\\' . $layout_template . '\\templateStyle';
    } else {
        $template_markup_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/' . $layout_template . 'Markup.php';
        $template_style_path = plugin_dir_path(__FILE__) . 'templates/' . $layout_template . '/' . $layout_template . 'Style.php';
        $markup_class = 'Shopglut\\layouts\\singleProduct\\templates\\' . $layout_template . '\\' . $layout_template . 'Markup';
        $style_class = 'Shopglut\\layouts\\singleProduct\\templates\\' . $layout_template . '\\' . $layout_template . 'Style';
    }

    if (file_exists($template_markup_path) && file_exists($template_style_path)) {
        require_once $template_markup_path;
        require_once $template_style_path;

        if (class_exists($markup_class) && class_exists($style_class)) {
            global $layout_settings;
            $layout_settings = maybe_unserialize($shopglut_custom_layout['layout_settings']);

            $markup_instance = new $markup_class(array(), false);
            $style_instance = new $style_class();

            // Add CSS
            echo '<style type="text/css">
                .shopglut-single-product-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                    padding: 0 20px !important;
                }
                .shopglut-single-product {
                    width: 100% !important;
                    max-width: 1240px !important;
                    margin: 0 auto !important;
                    padding: 20px !important;
                }
                .shopglut-single-product-container .product-main-wrapper {
                    display: flex !important;
                    gap: 40px !important;
                    margin-bottom: 40px !important;
                }
                @media (min-width: 922px) {
                    .shopglut-single-product-container .product-gallery-section {
                        flex: 0 0 50% !important;
                    }
                    .shopglut-single-product-container .product-info-section {
                        flex: 0 0 50% !important;
                    }
                }
                @media (max-width: 921px) {
                    .shopglut-single-product-container .product-main-wrapper {
                        flex-direction: column !important;
                    }
                }
            </style>';

            // Generate and output template-specific CSS
            if (method_exists($style_instance, 'dynamicCss')) {
                $layout_id = $shopglut_custom_layout['id'];
                $dynamic_css = $style_instance->dynamicCss($layout_id);
                if (!empty($dynamic_css)) {
                    echo '<style type="text/css">' . wp_kses($dynamic_css, array()) . '</style>';
                }
            }

            // Render template
            if (method_exists($markup_instance, 'layout_render')) {
                $template_data = array(
                    'layout_id' => $shopglut_custom_layout['id'],
                    'layout_name' => $shopglut_custom_layout['layout_name'] ?? '',
                    'settings' => $layout_settings
                );
                $markup_instance->layout_render($template_data);
            }
        }
    }

    echo '</div>';

    // Hide default WooCommerce notices
    echo '<style>.woocommerce-notices-wrapper { display: none !important; }</style>';
}, 5);

// Remove default WooCommerce product content
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// Load the default WooCommerce template
// This will load the theme's header/footer through WordPress template system
wc_get_template('single-product.php');
