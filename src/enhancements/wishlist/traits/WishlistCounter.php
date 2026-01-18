<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistCounter {
    
    
   public function menu_counter_custom_styles() {
    // Basic Colors
    $text_color = $this->enhancements['wishlist-page-menu-button-text-color'] ?? '#000000';
    $icon_color = $this->enhancements['wishlist-page-menu-button-icon-color'] ?? '#000000';
    $bg_color = $this->enhancements['wishlist-page-menu-button-background-color'] ?? '#ffffff';
    
    // Counter Bubble Colors
    $counter_bg_color = $this->enhancements['wishlist-page-menu-counter-bubble-bg-color'] ?? '#ff4444';
    $counter_text_color = $this->enhancements['wishlist-page-menu-counter-bubble-text-color'] ?? '#ffffff';

    // Spacing & Margins
    $button_margin_top = $this->enhancements['wishlist-page-menu-button-text-margin']['top'] ?? '5';
    $button_margin_bottom = $this->enhancements['wishlist-page-menu-button-text-margin']['bottom'] ?? '5';
    $button_margin_left = $this->enhancements['wishlist-page-menu-button-text-margin']['left'] ?? '5';
    $button_margin_right = $this->enhancements['wishlist-page-menu-button-text-margin']['right'] ?? '5';
    
    $button_padding_top = $this->enhancements['wishlist-page-menu-button-padding']['top'] ?? '8';
    $button_padding_bottom = $this->enhancements['wishlist-page-menu-button-padding']['bottom'] ?? '8';
    $button_padding_left = $this->enhancements['wishlist-page-menu-button-padding']['left'] ?? '12';
    $button_padding_right = $this->enhancements['wishlist-page-menu-button-padding']['right'] ?? '12';

    // Typography
    $font_size = $this->enhancements['wishlist-page-menu-button-font-size'] ?? '14';
    $font_weight = $this->enhancements['wishlist-page-menu-button-font-weight'] ?? '500';
    $icon_size = $this->enhancements['wishlist-page-menu-icon-size'] ?? '16';

    // Design Settings
    $border_radius = $this->enhancements['wishlist-page-menu-button-border-radius'] ?? '4';
    $border_width = $this->enhancements['wishlist-page-menu-button-border-width'] ?? '0';
    $border_color = $this->enhancements['wishlist-page-menu-button-border-color'] ?? '#cccccc';
    $elements_gap = $this->enhancements['wishlist-page-menu-elements-gap'] ?? '8';

    // Hover Effects
    $hover_bg_color = $this->enhancements['wishlist-page-menu-button-hover-bg-color'] ?? '#f5f5f5';
    $hover_text_color = $this->enhancements['wishlist-page-menu-button-hover-text-color'] ?? '#000000';
    $hover_icon_color = $this->enhancements['wishlist-page-menu-button-hover-icon-color'] ?? '#000000';

    // Animation Settings
    $transition_duration = $this->enhancements['wishlist-page-menu-button-transition-duration'] ?? '300';
    $hover_transform = $this->enhancements['wishlist-page-menu-button-hover-transform'] ?? true;

    // Counter Bubble Settings
    $counter_font_size = $this->enhancements['wishlist-page-menu-counter-bubble-font-size'] ?? '12';
    $counter_min_width = $this->enhancements['wishlist-page-menu-counter-bubble-min-width'] ?? '18';

    // Build border style
    $border_style = '';
    if ($border_width > 0) {
        $border_style = 'border: ' . esc_attr($border_width) . 'px solid ' . esc_attr($border_color) . ';';
    }

    // Build transform style
    $transform_style = $hover_transform ? 'transform: translateY(-1px);' : '';
    
    // Output custom CSS
    echo '<style type="text/css">
        .shopglut-wishlist-counter {
            background-color: ' . esc_attr($bg_color) . ' !important;
            display: inline-flex;
            align-items: center;
            gap: ' . esc_attr($elements_gap) . 'px;
            padding: ' . esc_attr($button_padding_top) . 'px ' . esc_attr($button_padding_right) . 'px ' . esc_attr($button_padding_bottom) . 'px ' . esc_attr($button_padding_left) . 'px;
            border-radius: ' . esc_attr($border_radius) . 'px;
            ' . wp_kses_post($border_style) . '
            text-decoration: none;
            transition: all ' . esc_attr($transition_duration) . 'ms ease;
            margin-top: ' . esc_attr($button_margin_top) . 'px !important;
            margin-bottom: ' . esc_attr($button_margin_bottom) . 'px !important;
            margin-left: ' . esc_attr($button_margin_left) . 'px !important;
            margin-right: ' . esc_attr($button_margin_right) . 'px !important;
        }
        
        .shopglut-wishlist-counter .menu-text {
            color: ' . esc_attr($text_color) . ' !important;
            font-weight: ' . esc_attr($font_weight) . ';
            font-size: ' . esc_attr($font_size) . 'px;
        }
        
        .shopglut-wishlist-counter i {
            color: ' . esc_attr($icon_color) . ' !important;
            font-size: ' . esc_attr($icon_size) . 'px;
        }
        
        .shopglut-wishlist-counter .counter-bubble {
            background-color: ' . esc_attr($counter_bg_color) . ';
            color: ' . esc_attr($counter_text_color) . ';
            border-radius: 50%;
            padding: 2px 6px;
            font-size: ' . esc_attr($counter_font_size) . 'px;
            font-weight: bold;
            min-width: ' . esc_attr($counter_min_width) . 'px;
            text-align: center;
            line-height: 1.2;
        }
        
        .shopglut-wishlist-counter:hover {
            background-color: ' . esc_attr($hover_bg_color) . ' !important;
            text-decoration: none;
            ' . wp_kses_post($transform_style) . '
        }
        
        .shopglut-wishlist-counter:hover .menu-text {
            color: ' . esc_attr($hover_text_color) . ' !important;
        }
        
        .shopglut-wishlist-counter:hover i {
            color: ' . esc_attr($hover_icon_color) . ' !important;
        }
        
        /* Menu specific styling */
        .menu-item.wishlist-counter-item {
            position: relative;
        }
        
        .menu-item.wishlist-counter-item .shopglut-wishlist-counter {
            margin: 0;
        }
        
        /* Counter animation */
        .counter-bubble.bounce {
            animation: bounceCounter 0.5s ease-in-out;
        }
        
        @keyframes bounceCounter {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            80% {
                transform: translateY(-5px);
            }
        }
    </style>';
}
    
   public function get_wishlist_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shopglut_wishlist';
    $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_guest_user_id();
    
    if (!$user_id) return 0;
    
    $query = "SELECT product_ids FROM $table_name WHERE wish_user_id = %s";
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
    $wishlist_data = $wpdb->get_row($wpdb->prepare( $query, $user_id ));
    
    if (!$wishlist_data) return 0;
    
    $count = 0;
    
    // Count main wishlist products
    if (!empty($wishlist_data->product_ids)) {
        $products = array_filter(explode(',', $wishlist_data->product_ids));
        $count += count($products);
    }
    
    // Count sublist products from social table (pro feature)
    $sublists = apply_filters('shopglut_get_user_sublists', [], $user_id);
    if (is_array($sublists)) {
        foreach ($sublists as $sublist) {
            if (is_array($sublist)) {
                $count += count(array_filter($sublist));
            }
        }
    }
    
    return $count;
}
    
    public function update_wishlist_count() {
        check_ajax_referer('shopLayouts_nonce', 'nonce');
        wp_send_json_success(array(
            'count' => $this->get_wishlist_count(),
            'animation' => 'bounce' // Add animation class
        ));
    }
    
    public function wishlist_count_shortcode($atts) {
        $count = $this->get_wishlist_count();
        $wishlist_page_id = $this->enhancements['wishlist-general-page'] ?? 0;
        $wishlist_url = $wishlist_page_id ? get_permalink($wishlist_page_id) : '#';
        
        // Get menu text and icon from settings
        $menu_text = $this->enhancements['wishlist-menu-btn-text'] ?? __('Wishlist', 'shopglut');
        $menu_icon = $this->enhancements['wishlist-menu-btn-icon'] ?? 'fa-solid fa-heart';
        
        $counter_html = sprintf(
        '<a href="%s" class="shopglut-wishlist-counter">
            <i class="%s"></i>
            <span class="menu-text">%s</span>
            <span class="counter-bubble">%d</span>
        </a>',
        esc_url($wishlist_url),
        esc_attr($menu_icon),
        esc_html($menu_text),
        $count
    );
    
    return apply_filters('shopglut_wishlist_counter_html', $counter_html, $count, $wishlist_url);

    }
    
    public function add_wishlist_count_to_menu($items, $args) {
    // Check if menu button is enabled
    if (!isset($this->enhancements['wishlist-enable-menu-btn']) || 
        $this->enhancements['wishlist-enable-menu-btn'] != '1') {
        return $items;
    }



    if ($args->theme_location == 'primary') {
        $count = $this->get_wishlist_count();
        $wishlist_page_id = $this->enhancements['wishlist-general-page'] ?? 0;
        $wishlist_url = $wishlist_page_id ? get_permalink($wishlist_page_id) : '#';
        
        // Get menu text and icon from settings
        $menu_text = $this->enhancements['wishlist-menu-btn-text'] ?? __('Wishlist', 'shopglut');
        $menu_icon = $this->enhancements['wishlist-menu-btn-icon'] ?? 'fa-solid fa-heart';
 
        $counter_html = sprintf(
            '<a href="%s" class="shopglut-wishlist-counter">
                <i class="%s"></i>
                <span class="menu-text">%s</span>
                <span class="counter-bubble">%d</span>
            </a>',
            esc_url($wishlist_url),
            esc_attr($menu_icon),
            esc_html($menu_text),
            $count
        );
        
        // HOOK: Allow Pro plugin to modify the counter HTML
        $enhanced_counter = apply_filters('shopglut_wishlist_counter_html', $counter_html, $count, $wishlist_url);
        
        $items .= sprintf(
            '<li class="menu-item wishlist-counter-item">%s</li>',
            $enhanced_counter
        );
    }
    return $items;
}

    
    private function get_guest_user_id() {
        return isset($_COOKIE['shopglutw_guest_user_id']) ? sanitize_text_field( wp_unslash( $_COOKIE['shopglutw_guest_user_id'] ) ) : '';
    }
}