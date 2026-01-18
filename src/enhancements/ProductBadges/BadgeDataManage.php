<?php
namespace Shopglut\enhancements\ProductBadges;

if ( ! defined( 'ABSPATH' ) ) exit;


class BadgeDataManage {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // AJAX handlers
        add_action('wp_ajax_save_shopg_productbadge_data', array($this, 'save_productbadge_data'));
        add_action('wp_ajax_reset_shopg_productbadge_settings', array($this, 'reset_productbadge_settings'));
        add_action('wp_ajax_shopglut_get_badge_preview', array($this, 'get_badge_preview_ajax'));

        // Frontend display
        add_action('wp_head', array($this, 'add_badge_custom_css'));

        // Badge display hooks
        $this->register_badge_display_hooks();

        // Add JavaScript for badge positioning
        add_action('wp_footer', array($this, 'add_badge_positioning_script'));
    }

    /**
     * Register badge display hooks
     */
    private function register_badge_display_hooks() {
        // Product image areas
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'display_badges_on_product_image'), 5);
        add_action('woocommerce_before_single_product_summary', array($this, 'display_badges_on_product_image'), 15);

        // Before product title area
        add_action('woocommerce_before_shop_loop_item', array($this, 'display_badges_before_title'), 20);
        add_action('woocommerce_single_product_summary', array($this, 'display_badges_before_title'), 4);
    }

    /**
     * Add custom CSS
     */
    public function add_badge_custom_css() {
        $custom_css = $this->generate_badge_css();
        if ($custom_css) {
            echo '<style id="shopglut-badges-css">' . wp_kses_post($custom_css) . '</style>';
        }
    }

    /**
     * Generate CSS for all active badges
     */
    private function generate_badge_css() {
        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        $cache_key = 'shopglut_badges_css';
        $badges = wp_cache_get($cache_key, 'shopglut_badges');

        if (false === $badges) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $badges = $wpdb->get_results("SELECT * FROM `" . esc_sql($table_name) . "` ORDER BY id DESC");
            wp_cache_set($cache_key, $badges, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
        }

        if (empty($badges)) {
            return '';
        }

        $css = '';
        foreach ($badges as $badge) {
            $badge_settings = maybe_unserialize($badge->layout_settings);

            $enable_badge = $this->get_nested_value($badge_settings, 'enable_badge', false);

            if ($enable_badge) {
                $css .= $this->build_badge_css($badge->id, $badge_settings);
            }
        }

        $css .= $this->build_badge_positioning_css();

        return $css;
    }

    /**
     * Build CSS for individual badge - handles multiple badge types
     */
    private function build_badge_css($badge_id, $badge_settings) {
        $css = '';

        // Get enabled badge types
        $badge_types = $this->get_nested_value($badge_settings, 'badge_type', array());

        if (empty($badge_types) || !is_array($badge_types)) {
            return $css;
        }

        // Generate CSS for each enabled badge type
        foreach ($badge_types as $badge_type) {
            $type_css = $this->build_badge_type_css($badge_id, $badge_settings, $badge_type);
            $css .= $type_css;
        }

        return $css;
    }

    /**
     * Build CSS for a specific badge type (sale, new, out_of_stock)
     */
    private function build_badge_type_css($badge_id, $badge_settings, $badge_type) {
        $css = ".shopglut-badge-{$badge_id}.shopglut-badge-type-{$badge_type} {\n";

        // Get style settings based on badge type
        $style_settings = $this->get_badge_type_style_settings($badge_settings, $badge_type);

        // Text color
        $text_color = isset($style_settings['text_color']) ? $style_settings['text_color'] : '#ffffff';
        $css .= "  color: " . sanitize_hex_color($text_color) . ";\n";

        // Font size
        $font_size = isset($style_settings['font_size']) ? $style_settings['font_size'] : 12;
        if (is_array($font_size) && isset($font_size[$badge_type . '_badge_font_size'])) {
            $font_size = intval($font_size[$badge_type . '_badge_font_size']);
        } else {
            $font_size = intval($font_size);
        }
        $css .= "  font-size: " . $font_size . "px;\n";

        // Font weight
        $font_weight = isset($style_settings['font_weight']) ? $style_settings['font_weight'] : '700';
        $css .= "  font-weight: " . $font_weight . ";\n";

        // Text transform
        $text_transform = isset($style_settings['text_transform']) ? $style_settings['text_transform'] : 'uppercase';
        $css .= "  text-transform: " . $text_transform . ";\n";

        // Background color
        $bg_color = isset($style_settings['bg_color']) ? $style_settings['bg_color'] : '#ff0000';
        $enable_gradient = isset($style_settings['enable_gradient']) ? $style_settings['enable_gradient'] : false;
        $gradient_color = isset($style_settings['gradient_color']) ? $style_settings['gradient_color'] : '#cc0000';

        if ($enable_gradient && $gradient_color) {
            $css .= "  background: linear-gradient(135deg, " . sanitize_hex_color($bg_color) . ", " . sanitize_hex_color($gradient_color) . ");\n";
        } else {
            $css .= "  background-color: " . sanitize_hex_color($bg_color) . ";\n";
        }

        // Padding
        $padding_v = isset($style_settings['padding_v']) ? $style_settings['padding_v'] : 5;
        if (is_array($padding_v) && isset($padding_v[$badge_type . '_badge_padding_v'])) {
            $padding_v = intval($padding_v[$badge_type . '_badge_padding_v']);
        } else {
            $padding_v = intval($padding_v);
        }

        $padding_h = isset($style_settings['padding_h']) ? $style_settings['padding_h'] : 10;
        if (is_array($padding_h) && isset($padding_h[$badge_type . '_badge_padding_h'])) {
            $padding_h = intval($padding_h[$badge_type . '_badge_padding_h']);
        } else {
            $padding_h = intval($padding_h);
        }

        $css .= "  padding: " . $padding_v . "px " . $padding_h . "px;\n";

        // Border radius
        $border_radius = isset($style_settings['border_radius']) ? $style_settings['border_radius'] : 3;
        if (is_array($border_radius) && isset($border_radius[$badge_type . '_badge_border_radius'])) {
            $border_radius = intval($border_radius[$badge_type . '_badge_border_radius']);
        } else {
            $border_radius = intval($border_radius);
        }
        $css .= "  border-radius: " . $border_radius . "px;\n";

        // Border
        $border_width = isset($style_settings['border_width']) ? $style_settings['border_width'] : 0;
        if (is_array($border_width) && isset($border_width[$badge_type . '_badge_border_width'])) {
            $border_width = intval($border_width[$badge_type . '_badge_border_width']);
        } else {
            $border_width = intval($border_width);
        }

        if ($border_width > 0) {
            $border_color = isset($style_settings['border_color']) ? $style_settings['border_color'] : '#000000';
            $css .= "  border: " . $border_width . "px solid " . sanitize_hex_color($border_color) . ";\n";
        }

        // Shadow
        $enable_shadow = isset($style_settings['enable_shadow']) ? $style_settings['enable_shadow'] : false;
        if ($enable_shadow) {
            $shadow_color = isset($style_settings['shadow_color']) ? $style_settings['shadow_color'] : 'rgba(0, 0, 0, 0.2)';
            $shadow_blur = isset($style_settings['shadow_blur']) ? $style_settings['shadow_blur'] : 4;
            if (is_array($shadow_blur) && isset($shadow_blur[$badge_type . '_badge_shadow_blur'])) {
                $shadow_blur = intval($shadow_blur[$badge_type . '_badge_shadow_blur']);
            } else {
                $shadow_blur = intval($shadow_blur);
            }
            $css .= "  box-shadow: 0 2px " . $shadow_blur . "px " . $shadow_color . ";\n";
        }

        // Base styles
        $css .= "  display: inline-block;\n";
        $css .= "  font-weight: bold;\n";
        $css .= "  text-align: center;\n";
        $css .= "  position: relative;\n";
        $css .= "  z-index: 10;\n";
        $css .= "  line-height: 1.2;\n";
        $css .= "  box-sizing: border-box;\n";
        $css .= "}\n\n";

        return $css;
    }

    /**
     * Get style settings for a specific badge type
     */
    private function get_badge_type_style_settings($badge_settings, $badge_type) {
        $prefix = $badge_type . '_badge_';

        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        $style = array();

        // Map the settings to the style array
        $style['text_color'] = $this->get_nested_value($settings, $prefix . 'text_color', '#ffffff');
        $style['font_size'] = $this->get_nested_value($settings, $prefix . 'font_size', 12);
        $style['font_weight'] = $this->get_nested_value($settings, $prefix . 'font_weight', '700');
        $style['text_transform'] = $this->get_nested_value($settings, $prefix . 'text_transform', 'uppercase');
        $style['bg_color'] = $this->get_nested_value($settings, $prefix . 'bg_color', '#ff0000');
        $style['enable_gradient'] = $this->get_nested_value($settings, $prefix . 'enable_gradient', false);
        $style['gradient_color'] = $this->get_nested_value($settings, $prefix . 'gradient_color', '#cc0000');
        $style['padding_v'] = $this->get_nested_value($settings, $prefix . 'padding_v', 5);
        $style['padding_h'] = $this->get_nested_value($settings, $prefix . 'padding_h', 10);
        $style['border_radius'] = $this->get_nested_value($settings, $prefix . 'border_radius', 3);
        $style['border_width'] = $this->get_nested_value($settings, $prefix . 'border_width', 0);
        $style['border_color'] = $this->get_nested_value($settings, $prefix . 'border_color', '#000000');
        $style['enable_shadow'] = $this->get_nested_value($settings, $prefix . 'enable_shadow', false);
        $style['shadow_color'] = $this->get_nested_value($settings, $prefix . 'shadow_color', 'rgba(0, 0, 0, 0.2)');
        $style['shadow_blur'] = $this->get_nested_value($settings, $prefix . 'shadow_blur', 4);

        return $style;
    }

    /**
     * Get CSS for a specific badge layout
     */
    public function get_badge_css($badge_layout_id) {
        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
        $badge = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `{$table_name}` WHERE id = %d", $badge_layout_id)
        );

        if (!$badge) {
            return '';
        }

        $badge_settings = maybe_unserialize($badge->layout_settings);

        $enable_badge = $this->get_nested_value($badge_settings, 'enable_badge', false);

        if (!$enable_badge) {
            return '';
        }

        return $this->build_badge_css($badge->id, $badge_settings);
    }

    /**
     * Build CSS for badge positioning
     */
    private function build_badge_positioning_css() {
        $css = "/* ShopGlut Badge Positioning */\n";

        // Hide WooCommerce default badges
        $css .= ".onsale { display: none !important; }\n";
        $css .= ".woocommerce-loop-product__link .onsale, .single-product .onsale, .product .onsale, .woocommerce-product-gallery .onsale { display: none !important; }\n";
        $css .= ".ast-on-card-button.ast-onsale-card, .ast-onsale-card { display: none !important; }\n";
        $css .= ".astra-shop-thumbnail-wrap .ast-onsale-card { display: none !important; }\n";

        // Product image badges positioning
        $css .= ".shopglut-badges-product-image { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; pointer-events: none; display: flex; flex-direction: column; gap: 5px; align-items: flex-start; }\n";

        // Badge position classes
        $css .= ".shopglut-badge-position-top-left { position: absolute; top: 10px; left: 10px; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: flex-start; }\n";
        $css .= ".shopglut-badge-position-top-center { position: absolute; top: 10px; left: 25%; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: center; transform: translateX(-50%); }\n";
        $css .= ".shopglut-badge-position-top-right { position: absolute; top: 10px; right: 10px; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: flex-end; }\n";
        $css .= ".shopglut-badge-position-bottom-left { position: absolute; bottom: 10px; left: 10px; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: flex-start; }\n";
        $css .= ".shopglut-badge-position-bottom-center { position: absolute; bottom: 10px; left: 25%; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: center; transform: translateX(-50%); }\n";
        $css .= ".shopglut-badge-position-bottom-right { position: absolute; bottom: 10px; right: 10px; z-index: 20; display: flex; flex-direction: column; gap: 5px; align-items: flex-end; }\n";
        $css .= ".woocommerce-loop-product__link, .product-images-wrapper { position: relative; }\n";

        // Inline positioning classes
        $css .= ".shopglut-badge-inline-left { display: block; text-align: left; margin-right: auto; margin-left: 0; }\n";
        $css .= ".shopglut-badge-inline-center { display: block; text-align: center; margin-right: auto; margin-left: auto; }\n";
        $css .= ".shopglut-badge-inline-right { display: block; text-align: right; margin-left: auto; margin-right: 0; }\n";
        $css .= ".shopglut-badges-before-title { margin-bottom: 10px; }\n";
        $css .= ".shopglut-badges-before-title .shopglut-badge { margin-bottom: 5px; display: inline-block; }\n";

        // Responsive
        $css .= "@media (max-width: 768px) { .shopglut-badges-product-image .shopglut-badge { font-size: 10px; padding: 3px 6px; } }\n";

        return $css;
    }

    /**
     * Get nested value from settings array
     */
    private function get_nested_value($settings, $key, $default = '') {
        // Direct access
        if (isset($settings[$key])) {
            $value = $settings[$key];
            if (is_array($value) && isset($value[$key])) {
                return $value[$key];
            }
            return $value;
        }

        // Check shopg_product_badge_settings
        if (isset($settings['shopg_product_badge_settings'][$key])) {
            $value = $settings['shopg_product_badge_settings'][$key];
            if (is_array($value) && isset($value[$key])) {
                return $value[$key];
            }
            return $value;
        }

        // Check product_badge-settings
        if (isset($settings['shopg_product_badge_settings']['product_badge-settings'][$key])) {
            $value = $settings['shopg_product_badge_settings']['product_badge-settings'][$key];
            if (is_array($value) && isset($value[$key])) {
                return $value[$key];
            }
            return $value;
        }

        return $default;
    }

    /**
     * Display badges on product image
     */
    public function display_badges_on_product_image() {
        global $product;

        if (!$product) {
            return;
        }

        $product_id = $product->get_id();
        $badges = $this->get_active_badges_for_product_by_display_area($product_id, 'product_image');

        if (empty($badges)) {
            return;
        }

        echo '<div class="shopglut-badges-container shopglut-badges-product-image">';

        foreach ($badges as $badge) {
            $this->render_badge($badge, $product_id);
        }

        echo '</div>';
    }

    /**
     * Display badges before product title
     */
    public function display_badges_before_title() {
        global $product;

        if (!$product) {
            return;
        }

        $product_id = $product->get_id();
        $badges = $this->get_active_badges_for_product_by_display_area($product_id, 'before_product_title');

        if (empty($badges)) {
            return;
        }

        echo '<div class="shopglut-badges-container shopglut-badges-before-title">';
        foreach ($badges as $badge) {
            $this->render_badge($badge, $product_id);
        }
        echo '</div>';
    }

    /**
     * Render a badge with all applicable types
     */
    public function render_badge($badge, $product_id) {
        $badge_settings = maybe_unserialize($badge->layout_settings);

        if (!$badge_settings || !is_array($badge_settings)) {
            return;
        }

        $enable_badge = $this->get_nested_value($badge_settings, 'enable_badge');
        if (empty($enable_badge)) {
            return;
        }

        // Get enabled badge types
        $badge_types = $this->get_nested_value($badge_settings, 'badge_type', array());

        if (empty($badge_types) || !is_array($badge_types)) {
            return;
        }

        // Check each badge type and render if conditions are met
        foreach ($badge_types as $badge_type) {
            if ($this->should_display_badge_type($badge_settings, $badge_type, $product_id)) {
                $this->render_single_badge_type($badge, $badge_settings, $badge_type);
            }
        }
    }

    /**
     * Check if a specific badge type should display
     */
    private function should_display_badge_type($badge_settings, $badge_type, $product_id) {
        switch ($badge_type) {
            case 'sale':
                return $this->check_sale_conditions($badge_settings, $product_id);
            case 'new':
                return $this->check_new_conditions($badge_settings, $product_id);
            case 'out_of_stock':
                return $this->check_out_of_stock_conditions($badge_settings, $product_id);
            default:
                return false;
        }
    }

    /**
     * Render a single badge type
     */
    private function render_single_badge_type($badge, $badge_settings, $badge_type) {
        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        // Get badge text
        $text_key = $badge_type . '_badge_text';
        $badge_text = isset($settings[$text_key]) ? $settings[$text_key] : strtoupper(str_replace('_', ' ', $badge_type));

        // Get display area and position
        $display_area = isset($settings[$badge_type . '_display_area']) ? $settings[$badge_type . '_display_area'] : 'product_image';

        $inline_class = '';
        if ($display_area !== 'product_image') {
            $position_inline = isset($settings[$badge_type . '_position_inline']) ? $settings[$badge_type . '_position_inline'] : 'left';
            $inline_class = ' shopglut-badge-inline-' . esc_attr($position_inline);
        }

        echo '<span class="shopglut-badge shopglut-badge-' . esc_attr($badge->id) . ' shopglut-badge-type-' . esc_attr($badge_type) . esc_attr($inline_class) . '">';
        echo esc_html($badge_text);
        echo '</span>';
    }

    /**
     * Get active badges for product by display area
     */
    private function get_active_badges_for_product_by_display_area($product_id, $display_area = null) {
        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        $cache_key = 'shopglut_all_badges';
        $all_badges = wp_cache_get($cache_key, 'shopglut_badges');

        if (false === $all_badges) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $all_badges = $wpdb->get_results("SELECT * FROM `" . esc_sql($table_name) . "`");
            wp_cache_set($cache_key, $all_badges, 'shopglut_badges', 10 * MINUTE_IN_SECONDS);
        }

        if (empty($all_badges)) {
            return array();
        }

        $active_badges = array();
        foreach ($all_badges as $badge) {
            $badge_settings = maybe_unserialize($badge->layout_settings);

            if (!$badge_settings || !is_array($badge_settings)) {
                continue;
            }

            $enable_badge = $this->get_nested_value($badge_settings, 'enable_badge');
            if (empty($enable_badge)) {
                continue;
            }

            // Check if badge should display for this product
            if (!$this->should_display_badge_for_product($badge_settings, $product_id)) {
                continue;
            }

            // Get enabled badge types
            $badge_types = $this->get_nested_value($badge_settings, 'badge_type', array());

            if (empty($badge_types) || !is_array($badge_types)) {
                continue;
            }

            // Check if any badge type matches the display area and meets conditions
            $matches_display_area = false;
            $matches_conditions = false;

            $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
                ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
                : array();

            foreach ($badge_types as $badge_type) {
                $type_display_area = isset($settings[$badge_type . '_display_area']) ? $settings[$badge_type . '_display_area'] : 'product_image';

                if ($display_area === null || $type_display_area === $display_area) {
                    $matches_display_area = true;
                }

                if ($this->should_display_badge_type($badge_settings, $badge_type, $product_id)) {
                    $matches_conditions = true;
                }
            }

            if ($matches_display_area && $matches_conditions) {
                $active_badges[] = $badge;
            }
        }

        return $active_badges;
    }

    /**
     * Check if badge should display for product based on display locations
     */
    private function should_display_badge_for_product($badge_settings, $product_id) {
        $display_locations = $this->get_nested_value($badge_settings, 'display-locations', array());

        if (empty($display_locations)) {
            return false;
        }

        if (in_array('All Products', $display_locations)) {
            return true;
        }

        if (in_array('Single Product Template1', $display_locations) && is_product()) {
            return true;
        }

        if (in_array('product_' . $product_id, $display_locations)) {
            return true;
        }

        return false;
    }

    /**
     * Check sale conditions
     */
    private function check_sale_conditions($badge_settings, $product_id) {
        if (!function_exists('wc_get_product')) {
            return false;
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_on_sale()) {
            return false;
        }

        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        $sale_condition = isset($settings['sale_condition']) ? $settings['sale_condition'] : 'any_sale';

        switch ($sale_condition) {
            case 'any_sale':
                return true;
            case 'min_discount':
                return $this->product_meets_minimum_discount($settings, $product);
            case 'percentage_sale':
                return $this->product_has_percentage_discount($settings, $product);
            case 'fixed_sale':
                return $this->product_has_fixed_discount($settings, $product);
            default:
                return true;
        }
    }

    /**
     * Check if product meets minimum discount
     */
    private function product_meets_minimum_discount($settings, $product) {
        $min_percentage = isset($settings['min_discount_percentage']) ? $settings['min_discount_percentage'] : 10;
        $min_amount = isset($settings['min_discount_amount']) ? $settings['min_discount_amount'] : 5;

        if ($product->is_type('variable')) {
            foreach ($product->get_variation_prices() as $variation) {
                if (isset($variation['regular_price']) && isset($variation['sale_price']) &&
                    $variation['regular_price'] > 0 && $variation['sale_price'] > 0) {

                    $discount_percentage = (($variation['regular_price'] - $variation['sale_price']) / $variation['regular_price']) * 100;
                    $discount_amount = $variation['regular_price'] - $variation['sale_price'];

                    if ($discount_percentage >= $min_percentage || $discount_amount >= $min_amount) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            if ($regular_price > 0 && $sale_price > 0) {
                $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;
                $discount_amount = $regular_price - $sale_price;

                return $discount_percentage >= $min_percentage || $discount_amount >= $min_amount;
            }
        }

        return false;
    }

    /**
     * Check if product has percentage discount
     */
    private function product_has_percentage_discount($settings, $product) {
        $min_percentage = isset($settings['min_discount_percentage']) ? $settings['min_discount_percentage'] : 10;

        if ($product->is_type('variable')) {
            foreach ($product->get_variation_prices() as $variation) {
                if (isset($variation['regular_price']) && isset($variation['sale_price']) &&
                    $variation['regular_price'] > 0 && $variation['sale_price'] > 0) {

                    $discount_percentage = (($variation['regular_price'] - $variation['sale_price']) / $variation['regular_price']) * 100;

                    if ($discount_percentage >= $min_percentage) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            if ($regular_price > 0 && $sale_price > 0) {
                $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;
                return $discount_percentage >= $min_percentage;
            }
        }

        return false;
    }

    /**
     * Check if product has fixed discount
     */
    private function product_has_fixed_discount($settings, $product) {
        $min_amount = isset($settings['min_discount_amount']) ? $settings['min_discount_amount'] : 5;

        if ($product->is_type('variable')) {
            foreach ($product->get_variation_prices() as $variation) {
                if (isset($variation['regular_price']) && isset($variation['sale_price']) &&
                    $variation['regular_price'] > 0 && $variation['sale_price'] > 0) {

                    $discount_amount = $variation['regular_price'] - $variation['sale_price'];

                    if ($discount_amount >= $min_amount) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            if ($regular_price > 0 && $sale_price > 0) {
                $discount_amount = $regular_price - $sale_price;
                return $discount_amount >= $min_amount;
            }
        }

        return false;
    }

    /**
     * Check new product conditions
     */
    private function check_new_conditions($badge_settings, $product_id) {
        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        $new_days = isset($settings['new_product_days']) ? $settings['new_product_days'] : 7;

        if (is_array($new_days) && isset($new_days['new_product_days'])) {
            $new_days = intval($new_days['new_product_days']);
        } else {
            $new_days = intval($new_days);
        }

        if (function_exists('wc_get_product')) {
            $product = wc_get_product($product_id);
            if (!$product) {
                return false;
            }

            $created_date = $product->get_date_created();
            if ($created_date) {
                $current_time = current_time('timestamp', true);
                $created_time = $created_date->getTimestamp();
                $days_diff = ($current_time - $created_time) / DAY_IN_SECONDS;

                return $days_diff <= $new_days;
            }
        }

        $post = get_post($product_id);
        if ($post) {
            $current_time = current_time('timestamp', true);
            $post_time = strtotime($post->post_date);
            $days_diff = ($current_time - $post_time) / DAY_IN_SECONDS;

            return $days_diff <= $new_days;
        }

        return false;
    }

    /**
     * Check out of stock conditions
     */
    private function check_out_of_stock_conditions($badge_settings, $product_id) {
        if (!function_exists('wc_get_product')) {
            return false;
        }

        $product = wc_get_product($product_id);
        if (!$product || $product->is_in_stock()) {
            return false;
        }

        return true;
    }

    /**
     * Save badge data
     */
    public function save_productbadge_data() {
        if (!isset($_POST['shopg_productbadge_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shopg_productbadge_nonce'])), 'shopg_productbadge_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }

        $badge_id = isset($_POST['shopg_badge_id']) ? intval(sanitize_text_field(wp_unslash($_POST['shopg_badge_id']))) : 0;
        $badge_name = isset($_POST['badge_name']) ? sanitize_text_field(wp_unslash($_POST['badge_name'])) : '';
        $badge_template = isset($_POST['badge_template']) ? sanitize_text_field(wp_unslash($_POST['badge_template'])) : 'template1';

        $badge_settings = array();
        if (isset($_POST['shopg_product_badge_settings'])) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Raw data will be sanitized below
            $settings_raw = wp_unslash($_POST['shopg_product_badge_settings']);

            if (is_string($settings_raw)) {
                $settings_decoded = json_decode($settings_raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($settings_decoded)) {
                    $badge_settings = $this->sanitize_badge_settings($settings_decoded);
                }
            } elseif (is_array($settings_raw)) {
                $badge_settings = $this->sanitize_badge_settings($settings_raw);
            }
        }

        if (empty($badge_name)) {
            wp_send_json_error('Badge name is required');
            return;
        }

        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        $data = array(
            'layout_name' => $badge_name,
            'layout_template' => $badge_template,
            'layout_settings' => serialize($badge_settings)
        );

        if ($badge_id > 0) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $result = $wpdb->update($table_name, $data, array('id' => $badge_id), array('%s', '%s', '%s'), array('%d'));
        } else {
            $data['created_at'] = current_time('mysql');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for custom table operation
            $result = $wpdb->insert($table_name, $data, array('%s', '%s', '%s', '%s'));
            $badge_id = $wpdb->insert_id;
        }

        wp_cache_delete('shopglut_product_badges', 'shopglut_badges');

        if ($result === false) {
            wp_send_json_error(array('message' => 'Database error: ' . $wpdb->last_error));
            return;
        }

        // Generate preview HTML to return in the response
        $preview_html = $this->shopglut_render_badge_preview($badge_id);

        wp_send_json_success(array(
            'message' => 'Product badge saved successfully',
            'badge_id' => $badge_id,
            'html' => $preview_html
        ));
    }

    /**
     * AJAX handler to get badge preview HTML
     */
    public function get_badge_preview_ajax() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_productbadge_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }

        $badge_id = isset($_POST['badge_id']) ? intval(sanitize_text_field(wp_unslash($_POST['badge_id']))) : 0;

        if ($badge_id <= 0) {
            wp_send_json_error('Invalid badge ID');
            return;
        }

        $preview_html = $this->shopglut_render_badge_preview($badge_id);

        wp_send_json_success(array(
            'html' => $preview_html,
            'badge_id' => $badge_id
        ));
    }

    /**
     * Reset badge settings
     */
    public function reset_productbadge_settings() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopg_productbadge_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }

        $badge_id = isset($_POST['badge_id']) ? intval(sanitize_text_field(wp_unslash($_POST['badge_id']))) : 0;

        if ($badge_id <= 0) {
            wp_send_json_error('Invalid badge ID');
            return;
        }

        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        $empty_settings = array();
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
        $result = $wpdb->update($table_name, array('layout_settings' => serialize($empty_settings), 'updated_at' => current_time('mysql')), array('id' => $badge_id), array('%s', '%s'), array('%d'));

        if ($result === false) {
            wp_send_json_error(array('message' => 'Database error: ' . $wpdb->last_error));
            return;
        }

        wp_send_json_success(array(
            'message' => 'Badge settings reset successfully!',
            'badge_id' => $badge_id
        ));
    }

    /**
     * Sanitize badge settings
     */
    private function sanitize_badge_settings($settings) {
        if (!is_array($settings)) {
            return sanitize_text_field($settings);
        }

        $sanitized = array();
        foreach ($settings as $key => $value) {
            $sanitized_key = sanitize_key($key);
            if (is_array($value)) {
                $sanitized[$sanitized_key] = $this->sanitize_badge_settings($value);
            } else {
                $sanitized[$sanitized_key] = sanitize_text_field($value);
            }
        }
        return $sanitized;
    }

    /**
     * Render badge preview for admin interface
     */
    public function shopglut_render_badge_preview($badge_id) {
        global $wpdb;
        $table_name = \Shopglut\ShopGlutDatabase::table_product_badges();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
        $badge = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . esc_sql($table_name) . "` WHERE id = %d", $badge_id));

        if (!$badge) {
            return '<div class="shopglut-preview-error">Badge not found</div>';
        }

        $badge_settings = maybe_unserialize($badge->layout_settings);

        $badge_types = $this->get_nested_value($badge_settings, 'badge_type', array());

        if (empty($badge_types)) {
            return '<div class="shopglut-preview-error">No badge types enabled. Please select at least one badge type.</div>';
        }

        $html = '<div class="shopglut-badge-preview-wrapper">';
        $html .= '<h3>Badge Preview</h3>';
        $html .= '<div class="shopglut-preview-container">';

        foreach ($badge_types as $badge_type) {
            $html .= $this->render_preview_badge($badge, $badge_settings, $badge_type);
        }

        $html .= '</div></div>';

        return $html;
    }

    /**
     * Render preview for a single badge type
     */
    private function render_preview_badge($badge, $badge_settings, $badge_type) {
        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        $text_key = $badge_type . '_badge_text';
        $badge_text = isset($settings[$text_key]) ? $settings[$text_key] : strtoupper(str_replace('_', ' ', $badge_type));

        $style = $this->build_preview_badge_style($settings, $badge_type);

        return sprintf(
            '<span class="shopglut-badge-preview shopglut-badge-type-%s" data-badge-type="%s" style="%s">%s</span> ',
            esc_attr($badge_type),
            esc_attr($badge_type),
            esc_attr($style),
            esc_html($badge_text)
        );
    }

    /**
     * Build preview style for a badge type
     */
    private function build_preview_badge_style($settings, $badge_type) {
        $prefix = $badge_type . '_badge_';

        $styles = array();

        $text_color = isset($settings[$prefix . 'text_color']) ? $settings[$prefix . 'text_color'] : '#ffffff';
        $styles[] = 'color: ' . sanitize_hex_color($text_color);

        $bg_color = isset($settings[$prefix . 'bg_color']) ? $settings[$prefix . 'bg_color'] : '#ff0000';
        $styles[] = 'background-color: ' . sanitize_hex_color($bg_color);

        $font_size = isset($settings[$prefix . 'font_size']) ? $settings[$prefix . 'font_size'] : 12;
        if (is_array($font_size) && isset($font_size[$prefix . 'font_size'])) {
            $font_size = intval($font_size[$prefix . 'font_size']);
        } else {
            $font_size = intval($font_size);
        }
        $styles[] = 'font-size: ' . $font_size . 'px';

        $font_weight = isset($settings[$prefix . 'font_weight']) ? $settings[$prefix . 'font_weight'] : '700';
        $styles[] = 'font-weight: ' . $font_weight;

        $border_radius = isset($settings[$prefix . 'border_radius']) ? $settings[$prefix . 'border_radius'] : 3;
        if (is_array($border_radius) && isset($border_radius[$prefix . 'border_radius'])) {
            $border_radius = intval($border_radius[$prefix . 'border_radius']);
        } else {
            $border_radius = intval($border_radius);
        }
        $styles[] = 'border-radius: ' . $border_radius . 'px';

        $padding_v = isset($settings[$prefix . 'padding_v']) ? $settings[$prefix . 'padding_v'] : 5;
        if (is_array($padding_v) && isset($padding_v[$prefix . 'padding_v'])) {
            $padding_v = intval($padding_v[$prefix . 'padding_v']);
        } else {
            $padding_v = intval($padding_v);
        }

        $padding_h = isset($settings[$prefix . 'padding_h']) ? $settings[$prefix . 'padding_h'] : 10;
        if (is_array($padding_h) && isset($padding_h[$prefix . 'padding_h'])) {
            $padding_h = intval($padding_h[$prefix . 'padding_h']);
        } else {
            $padding_h = intval($padding_h);
        }
        $styles[] = 'padding: ' . $padding_v . 'px ' . $padding_h . 'px';

        $styles[] = 'display: inline-block';
        $styles[] = 'margin: 5px';

        return implode('; ', $styles);
    }

    /**
     * Add JavaScript for badge positioning
     */
    public function add_badge_positioning_script() {
        if (!is_woocommerce() && !is_product() && !is_shop() && !is_product_category()) {
            return;
        }
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined') {
                jQuery(document).ready(function($) {
                    function hideWooCommerceBadges() {
                        $('.onsale, .ast-onsale-card, .ast-on-card-button.ast-onsale-card').css('display', 'none');
                    }

                    function positionBadgesOnProductImages() {
                        hideWooCommerceBadges();

                        $('.shopglut-badges-product-image').each(function() {
                            var $container = $(this);
                            var $productContainer = $container.closest('.product, .woocommerce-loop-product');
                            var $imageContainer = null;

                            if ($productContainer.length) {
                                $imageContainer = $productContainer.find('.astra-shop-thumbnail-wrap, .woocommerce-loop-product__link, .product-image').first();
                            }

                            if ($imageContainer && $imageContainer.length) {
                                if ($imageContainer.css('position') === 'static') {
                                    $imageContainer.css('position', 'relative');
                                }
                                if (!$container.parent().is($imageContainer)) {
                                    $imageContainer.append($container);
                                }
                            }
                        });
                    }

                    setTimeout(positionBadgesOnProductImages, 100);

                    $(window).on('resize', function() {
                        setTimeout(positionBadgesOnProductImages, 250);
                    });

                    setInterval(hideWooCommerceBadges, 500);
                });
            }
        });
        </script>
        <?php
    }

    /**
     * Get badges HTML for external use
     */
    public function get_badges_html($product_id = null, $display_area = null, $badge_layout_id = null) {
        global $product;

        if (!$product_id) {
            if (!$product) {
                return '';
            }
            $product_id = $product->get_id();
        }

        $badges = $this->get_active_badges_for_product_by_display_area($product_id, $display_area);

        if (empty($badges)) {
            return '';
        }

        if ($badge_layout_id !== null) {
            $badges = array_filter($badges, function($badge) use ($badge_layout_id) {
                return (int) $badge->id === (int) $badge_layout_id;
            });
        }

        if (empty($badges)) {
            return '';
        }

        $html = '';
        foreach ($badges as $badge) {
            $badge_settings = maybe_unserialize($badge->layout_settings);
            $badge_types = $this->get_nested_value($badge_settings, 'badge_type', array());

            foreach ($badge_types as $badge_type) {
                if ($this->should_display_badge_type($badge_settings, $badge_type, $product_id)) {
                    $html .= $this->render_badge_html($badge, $badge_settings, $badge_type, $display_area);
                }
            }
        }

        return $html;
    }

    /**
     * Render badge as HTML string
     */
    private function render_badge_html($badge, $badge_settings, $badge_type, $display_area) {
        $settings = isset($badge_settings['shopg_product_badge_settings']['product_badge-settings'])
            ? $badge_settings['shopg_product_badge_settings']['product_badge-settings']
            : array();

        $text_key = $badge_type . '_badge_text';
        $badge_text = isset($settings[$text_key]) ? $settings[$text_key] : strtoupper(str_replace('_', ' ', $badge_type));

        $inline_class = '';
        if ($display_area && $display_area !== 'product_image') {
            $position_inline = isset($settings[$badge_type . '_position_inline']) ? $settings[$badge_type . '_position_inline'] : 'left';
            $inline_class = ' shopglut-badge-inline-' . esc_attr($position_inline);
        }

        return '<span class="shopglut-badge shopglut-badge-' . esc_attr($badge->id) . ' shopglut-badge-type-' . esc_attr($badge_type) . esc_attr($inline_class) . '">' . esc_html($badge_text) . '</span>';
    }
}
