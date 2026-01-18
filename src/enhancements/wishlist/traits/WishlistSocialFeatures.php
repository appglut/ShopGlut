<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistSocialFeatures {
    
    /**
     * Generate shareable wishlist URL with encrypted user data (similar to QR)
     */
    private function get_social_shareable_wishlist_url($user_id, $list_name = '') {
        // Get wishlist page URL
        $wishlist_page_id = isset($this->enhancements['wishlist-general-page']) ? $this->enhancements['wishlist-general-page'] : '';
        $base_url = $wishlist_page_id ? get_permalink($wishlist_page_id) : home_url('/wishlist/');
        
        // Create encrypted data payload
        $share_data = array(
            'user_id' => $user_id,
            'list_name' => $list_name,
            'timestamp' => time(),
            'source' => 'social_share'
        );
        
        // Encrypt the data (simple base64 encoding for basic obfuscation)
        $encrypted_data = base64_encode(json_encode($share_data));
        
        // Add share parameters
        $share_url = add_query_arg(array(
            'social_shared' => '1',
            'data' => $encrypted_data
        ), $base_url);
        
        return $share_url;
    }
    
    /**
     * Render social share buttons for specific list
     */
    public function render_social_share_buttons($list_name = '') {
        // Get enhancements
        $enhancements = get_option( 'agshopglut_wishlist_options' );
        
        // Check if social share is enabled
        if (empty($enhancements['enable-social-share'])) {
            return '<!-- Social share is disabled -->';
        }

        // Get current user ID
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        
        if (!$user_id) {
            return '<!-- No user ID found for sharing -->';
        }

        $share_title = isset($enhancements['social-share-title']) ? esc_html($enhancements['social-share-title']) : esc_html__('Share Wishlist:', 'shopglut');
        
        // Generate shareable URL with encrypted data
        $current_url = $this->get_social_shareable_wishlist_url($user_id, $list_name);
        
        $html = '<div class="shopglut-social-share">';
        $html .= '<span class="share-title">' . $share_title . '</span>';

        // Facebook Share
        if (!empty($enhancements['enable-facebook-share'])) {
            $html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($current_url) . '" target="_blank" class="facebook-share social-share-btn" title="' . esc_attr__('Share on Facebook', 'shopglut') . '"><i class="fab fa-facebook"></i></a>';
        }

        // Dynamic share text based on list name
        // translators: %s is the wishlist name
        $list_text = $list_name ? sprintf(esc_html__('Check out my "%s" wishlist!', 'shopglut'), $list_name) : esc_html__("Check out my wishlist!", 'shopglut');
        // translators: %s is the wishlist name
        $list_title = $list_name ? sprintf(esc_html__('My "%s" Wishlist', 'shopglut'), $list_name) : esc_html__("My Wishlist", 'shopglut');

        // Twitter Share
        if (!empty($enhancements['enable-twitter-share'])) {
            $twitter_text = isset($enhancements['twitter-share-text']) ? $enhancements['twitter-share-text'] : $list_text;
            $html .= '<a href="https://twitter.com/intent/tweet?url=' . urlencode($current_url) . '&text=' . urlencode($twitter_text) . '" target="_blank" class="twitter-share social-share-btn" title="' . esc_attr__('Share on Twitter', 'shopglut') . '"><i class="fab fa-x-twitter"></i></a>';
        }

        // WhatsApp Share
        if (!empty($enhancements['enable-whatsapp-share'])) {
            $whatsapp_text = isset($enhancements['whatsapp-share-text']) ? $enhancements['whatsapp-share-text'] : $list_text;
            $html .= '<a href="https://api.whatsapp.com/send?text=' . urlencode($whatsapp_text . " " . $current_url) . '" target="_blank" class="whatsapp-share social-share-btn" title="' . esc_attr__('Share on WhatsApp', 'shopglut') . '"><i class="fab fa-whatsapp"></i></a>';
        }

        // Pinterest Share
        if (!empty($enhancements['enable-pinterest-share'])) {
            $pinterest_description = $list_text;
            $html .= '<a href="https://pinterest.com/pin/create/button/?url=' . urlencode($current_url) . '&description=' . urlencode($pinterest_description) . '" target="_blank" class="pinterest-share social-share-btn" title="' . esc_attr__('Share on Pinterest', 'shopglut') . '"><i class="fab fa-pinterest"></i></a>';
        }

        // LinkedIn Share
        if (!empty($enhancements['enable-linkedin-share'])) {
            $linkedin_title = isset($enhancements['linkedin-share-title']) ? $enhancements['linkedin-share-title'] : $list_title;
            $html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($current_url) . '&title=' . urlencode($linkedin_title) . '&summary=' . urlencode($list_text) . '" target="_blank" class="linkedin-share social-share-btn" title="' . esc_attr__('Share on LinkedIn', 'shopglut') . '"><i class="fab fa-linkedin"></i></a>';
        }

        // Telegram Share
        if (!empty($enhancements['enable-telegram-share'])) {
            $telegram_text = isset($enhancements['telegram-share-text']) ? $enhancements['telegram-share-text'] : $list_text;
            $html .= '<a href="https://telegram.me/share/url?url=' . urlencode($current_url) . '&text=' . urlencode($telegram_text) . '" target="_blank" class="telegram-share social-share-btn" title="' . esc_attr__('Share on Telegram', 'shopglut') . '"><i class="fab fa-telegram"></i></a>';
        }

        // Email Share
        if (!empty($enhancements['enable-email-share'])) {
            $email_subject = isset($enhancements['email-share-subject']) ? $enhancements['email-share-subject'] : $list_title;
            $email_body = isset($enhancements['email-share-body']) ? $enhancements['email-share-body'] : ($list_text . "\n\n");
            $html .= '<a href="mailto:?subject=' . urlencode($email_subject) . '&body=' . urlencode($email_body . $current_url) . '" class="email-share social-share-btn" title="' . esc_attr__('Share via Email', 'shopglut') . '"><i class="fas fa-envelope"></i></a>';
        }

        $html .= '</div>';
        
        // Add inline styles
        $html .= $this->generate_social_share_styles();
        
        return $html;
    }
    
    /**
     * Handle shared social wishlist display (uses same logic as QR features)
     */
    public function handle_shared_social_wishlist_display() {
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!isset($_GET['social_shared']) || sanitize_text_field( wp_unslash( $_GET['social_shared'] ) ) !== '1') {
            return false;
        }
        
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $encrypted_data = isset($_GET['data']) ? sanitize_text_field( wp_unslash( $_GET['data'] ) ) : '';
        
        if (!$encrypted_data) {
            echo '<div class="shopglut-error">' . esc_html__('Invalid share link.', 'shopglut') . '</div>';
            return true;
        }
        
        // Decrypt the data
        $share_data = json_decode(base64_decode($encrypted_data), true);
        
        if (!$share_data || !isset($share_data['user_id'])) {
            echo '<div class="shopglut-error">' . esc_html__('Invalid or corrupted share link.', 'shopglut') . '</div>';
            return true;
        }
        
        $user_id = $share_data['user_id'];
        $list_name = isset($share_data['list_name']) ? $share_data['list_name'] : '';
        $source = isset($share_data['source']) ? $share_data['source'] : 'unknown';
        
        // Optional: Check if link is too old (e.g., older than 30 days)
        if (isset($share_data['timestamp'])) {
            $days_old = (time() - $share_data['timestamp']) / (24 * 60 * 60);
            if ($days_old > 30) {
                echo '<div class="shopglut-warning">' . esc_html__('This share link is quite old and may not reflect the current wishlist.', 'shopglut') . '</div>';
            }
        }
        
        // Get wishlist data including sublists
        $wishlist_data = $this->get_social_shared_wishlist_data($user_id, $list_name);

        
        // Display shared wishlist with tabs if sublists exist
        echo '<div class="shopglut-shared-wishlist">';
        echo '<div class="shared-header">';
        echo '<h3><i class="fas fa-share-alt"></i> ' . esc_html__('Shared Wishlist', 'shopglut') . '</h3>';
        
        if ($list_name) {
            // translators: %s is the wishlist name
            echo '<p class="list-name">' . sprintf(esc_html__('List: %s', 'shopglut'), '<strong>' . esc_html($list_name) . '</strong>') . '</p>';
        }
        
        if ($source === 'social_share') {
            echo '<p class="share-source">' . esc_html__('Shared via social media', 'shopglut') . '</p>';
        }
        echo '</div>';
        
        // Check if we have sublists to display as tabs
        if (isset($wishlist_data['sublists']) && !empty($wishlist_data['sublists'])) {
            $this->render_social_wishlist_tabs($wishlist_data, $list_name);
        } else {
            // Single list display
            if ($wishlist_data && !empty($wishlist_data['product_ids'])) {
                $this->render_shared_social_wishlist_table($wishlist_data, $list_name);
            } else {
                echo '<div class="empty-wishlist">';
                echo '<i class="fas fa-heart-broken"></i>';
                echo '<p>' . esc_html__('This wishlist is empty or no longer available.', 'shopglut') . '</p>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        
        return true;
    }
    
    /**
     * Get shared wishlist data including sublists
     */
    private function get_social_shared_wishlist_data($user_id, $list_name = '') {
        global $wpdb;
        
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
        
        $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
        
        if (!$wishlist_data) {
            return false;
        }
        
        $result = array();
        
        // Get sublists from social table (pro feature)
        $sublists = apply_filters('shopglut_get_user_sublists', [], $user_id);
        
        // If a specific list is requested
        if ($list_name) {
            if (isset($sublists[$list_name]) && is_array($sublists[$list_name])) {
                $result = array(
                    'product_ids' => array_filter($sublists[$list_name]),
                    'list_name' => $list_name
                );
            } else {
                return false;
            }
        } else {
            // Return main wishlist and all sublists
            $main_product_ids = array();
            if ($wishlist_data->product_ids) {
                $main_product_ids = array_filter(array_map('trim', explode(',', $wishlist_data->product_ids)));
            }
            
            $result = array(
                'product_ids' => $main_product_ids,
                'list_name' => '',
                'sublists' => $sublists
            );
        }
        
        return $result;
    }
    
    /**
     * Render wishlist tabs for main list and sublists
     */
    private function render_social_wishlist_tabs($wishlist_data, $active_list = '') {
        $sublists = $wishlist_data['sublists'];
        $main_list_count = count($wishlist_data['product_ids']);
        
        echo '<div class="wishlist-tabs-container">';
        echo '<ul class="wishlist-tabs">';
        
        // Main list tab
        echo '<li class="' . (empty($active_list) ? 'active' : '') . '">';
        echo '<a href="#main-list" data-tab="main-list">';
        echo '<i class="fas fa-heart"></i>';
        echo esc_html__('Main List', 'shopglut');
        echo '<span class="item-count">' . esc_html($main_list_count) . '</span>';
        echo '</a>';
        echo '</li>';
        
        // Sublist tabs
        foreach ($sublists as $list_name => $product_ids) {
            if (empty($product_ids) || !is_array($product_ids)) continue;
            
            $product_ids = array_filter($product_ids);
            $count = count($product_ids);
            
            if ($count == 0) continue;
            
            echo '<li class="' . ($active_list === $list_name ? 'active' : '') . '">';
            echo '<a href="#' . esc_attr(sanitize_title($list_name)) . '" data-tab="' . esc_attr(sanitize_title($list_name)) . '">';
            echo '<i class="fas fa-list-alt"></i>';
            echo esc_html($list_name);
            echo '<span class="item-count">' . esc_html($count) . '</span>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        
        // Tab content
        echo '<div class="wishlist-tab-content">';
        
        // Main list content
        echo '<div id="main-list" class="tab-pane ' . (empty($active_list) ? 'active' : '') . '">';
        if (!empty($wishlist_data['product_ids'])) {
            $this->render_shared_social_wishlist_table($wishlist_data);
        } else {
            echo '<div class="empty-wishlist">';
            echo '<i class="fas fa-heart-broken"></i>';
            echo '<p>' . esc_html__('The main wishlist is empty.', 'shopglut') . '</p>';
            echo '</div>';
        }
        echo '</div>';
        
        // Sublist content
        foreach ($sublists as $list_name => $product_ids) {
            if (empty($product_ids) || !is_array($product_ids)) continue;
            
            $product_ids = array_filter($product_ids);
            if (empty($product_ids)) continue;
            
            $tab_id = sanitize_title($list_name);
            
            echo '<div id="' . esc_attr($tab_id) . '" class="tab-pane ' . ($active_list === $list_name ? 'active' : '') . '">';
            $this->render_shared_social_wishlist_table(array(
                'product_ids' => $product_ids,
                'list_name' => $list_name
            ));
            echo '</div>';
        }
        
        echo '</div>'; // .wishlist-tab-content
        echo '</div>'; // .wishlist-tabs-container
        
        // Add tab styles and scripts
        $this->add_social_wishlist_tab_styles();
        $this->add_social_wishlist_tab_scripts();
    }
    
    /**
     * Add styles for wishlist tabs
     */
    private function add_social_wishlist_tab_styles() {
        echo '<style>
        .wishlist-tabs-container {
            margin: 30px 0;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .wishlist-tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            flex-wrap: wrap;
        }
        
        .wishlist-tabs li {
            margin: 0;
            flex: 1;
            min-width: 0;
        }
        
        .wishlist-tabs li a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px 20px;
            background: transparent;
            border: none;
            text-decoration: none !important;
            color: #6c757d;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            gap: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .wishlist-tabs li a::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .wishlist-tabs li.active a {
            background: #ffffff;
            color: #495057;
            font-weight: 600;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .wishlist-tabs li.active a::before {
            transform: scaleX(1);
        }
        
        .wishlist-tabs li a:hover {
            background: rgba(255, 255, 255, 0.7);
            color: #495057;
            transform: translateY(-1px);
        }
        
        .wishlist-tabs li.active a:hover {
            transform: none;
        }
        
        .wishlist-tabs .item-count {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border-radius: 50px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
            flex-shrink: 0;
        }
        
        .wishlist-tabs li.active .item-count {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
        }
        
        .wishlist-tab-content {
            background: #ffffff;
            min-height: 400px;
        }
        
        .wishlist-tab-content .tab-pane {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .wishlist-tab-content .tab-pane.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        .wishlist-tabs li a i {
            font-size: 16px;
            margin-right: 8px;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .wishlist-tabs li.active a i {
            opacity: 1;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .wishlist-tabs {
                flex-direction: column;
            }
            
            .wishlist-tabs li {
                flex: none;
            }
            
            .wishlist-tabs li a {
                padding: 16px 20px;
                justify-content: flex-start;
                font-size: 14px;
            }
            
            .wishlist-tabs li a::before {
                height: 2px;
            }
        }
        
        @media (max-width: 480px) {
            .wishlist-tabs-container {
                margin: 20px -12px;
                border-radius: 0;
            }
            
            .wishlist-tabs li a {
                padding: 14px 16px;
                gap: 8px;
            }
            
            .wishlist-tabs .item-count {
                padding: 3px 8px;
                font-size: 11px;
            }
        }
        </style>';
    }
    
    /**
     * Add scripts for wishlist tabs functionality
     */
    private function add_social_wishlist_tab_scripts() {
        echo '<script>
        jQuery(document).ready(function($) {
            // Enhanced tab switching with smooth animations
            $(".wishlist-tabs a").on("click", function(e) {
                e.preventDefault();
                
                var $clickedTab = $(this);
                var $clickedLi = $clickedTab.parent();
                var tabId = $clickedTab.attr("href");
                
                // Prevent double clicks
                if ($clickedLi.hasClass("active")) {
                    return;
                }
                
                // Update active tab with smooth transition
                $(".wishlist-tabs li").removeClass("active");
                $clickedLi.addClass("active");
                
                // Hide current content with fade out
                var $currentPane = $(".wishlist-tab-content .tab-pane.active");
                $currentPane.fadeOut(200, function() {
                    $currentPane.removeClass("active");
                    
                    // Show new content with fade in
                    $(tabId).addClass("active").hide().fadeIn(300);
                });
                
                // Add ripple effect (optional)
                var $ripple = $("<span class=\"tab-ripple\"></span>");
                $clickedTab.append($ripple);
                
                setTimeout(function() {
                    $ripple.remove();
                }, 600);
            });
            
            // Add hover effects
            $(".wishlist-tabs a").hover(
                function() {
                    if (!$(this).parent().hasClass("active")) {
                        $(this).find(".item-count").addClass("hover");
                    }
                },
                function() {
                    $(this).find(".item-count").removeClass("hover");
                }
            );
        });
        </script>
        
        <style>
        .wishlist-tabs .item-count.hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }
        
        .tab-ripple {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.3);
            transform: translate(-50%, -50%);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        }
        
        @keyframes ripple {
            to {
                width: 100px;
                height: 100px;
                opacity: 0;
            }
        }
        
        .wishlist-tab-content .tab-pane {
            display: none;
        }
        
        .wishlist-tab-content .tab-pane.active {
            display: block;
        }
        </style>';
    }
    
    /**
     * Render shared social wishlist table
     */
    private function render_shared_social_wishlist_table($wishlist_data, $list_name = '') {
        $product_ids = $wishlist_data['product_ids'];
        
        if (empty($product_ids)) {
            echo '<div class="empty-wishlist">';
            echo '<i class="fas fa-heart-broken"></i>';
            echo '<p>' . esc_html__('This wishlist is empty.', 'shopglut') . '</p>';
            echo '</div>';
            return;
        }
        
        // Add some stats
        echo '<div class="wishlist-stats">';
        // translators: %d is the number of items in the wishlist
        echo '<span class="item-count"><i class="fas fa-list"></i> ' . sprintf(esc_html__('%d items', 'shopglut'), count($product_ids)) . '</span>';
        if ($list_name) {
            echo '<span class="list-name"><i class="fas fa-tag"></i> ' . esc_html($list_name) . '</span>';
        }
        echo '</div>';
        
        echo '<div class="shared-wishlist-grid">';
        
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) continue;
            
            $this->render_shared_social_product_card($product);
        }
        
        echo '</div>';
        
        // Add styles for the shared wishlist display
        $this->add_social_shared_wishlist_styles();
    }
    
    /**
     * Render individual product card for social shared view
     */
    private function render_shared_social_product_card($product) {
        $product_id = $product->get_id();
        $product_url = get_permalink($product_id);
        $is_in_stock = $product->is_in_stock();
        $on_sale = $product->is_on_sale();
        
        echo '<div class="shared-product-card ' . ($is_in_stock ? 'in-stock' : 'out-of-stock') . '">';
        
        // Product Image
        echo '<div class="product-image">';
        echo '<a href="' . esc_url($product_url) . '">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $product->get_image('medium');
        if ($on_sale) {
            echo '<span class="sale-badge">' . esc_html__('SALE', 'shopglut') . '</span>';
        }
        echo '</a>';
        echo '</div>';
        
        // Product Info
        echo '<div class="product-info">';
        
        // Product details in one line
        echo '<div class="product-details">';
        echo '<h4><a href="' . esc_url($product_url) . '">' . esc_html($product->get_name()) . '</a></h4>';
        
        // Price
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="product-price">' . $product->get_price_html() . '</div>';
        
        // Stock Status
        echo '<div class="stock-status">';
        if ($is_in_stock) {
            $stock_quantity = $product->get_stock_quantity();
            if ($stock_quantity && $stock_quantity <= 10) {
                echo '<span class="low-stock"><i class="fas fa-exclamation-triangle"></i> ' . 
                     // translators: %d is the stock quantity remaining
                     sprintf(esc_html__('Only %d left!', 'shopglut'), esc_html($stock_quantity)) . '</span>';
            } else {
                echo '<span class="in-stock"><i class="fas fa-check"></i> ' . esc_html__('In Stock', 'shopglut') . '</span>';
            }
        } else {
            echo '<span class="out-of-stock"><i class="fas fa-times"></i> ' . esc_html__('Out of Stock', 'shopglut') . '</span>';
        }
        echo '</div>';
        echo '</div>'; // .product-details
        
        // Action Buttons
        echo '<div class="product-actions">';
        echo '<a href="' . esc_url($product_url) . '" class="view-product-btn">';
        echo '<i class="fas fa-eye"></i> ' . esc_html__('View Product', 'shopglut');
        echo '</a>';
        echo '</div>';
        
        echo '</div>'; // .product-info
        echo '</div>'; // .shared-product-card
    }

    public function generate_social_share_styles() {
        $enhancements = get_option( 'agshopglut_wishlist_options' );
        
        if (empty($enhancements['enable-social-share'])) {
            return '';
        }

        $styles = '<style type="text/css">';
        
        // Container styles
        $container_margin = isset($enhancements['social-share-container-margin']) ? $enhancements['social-share-container-margin'] : array();
        $container_padding = isset($enhancements['social-share-container-padding']) ? $enhancements['social-share-container-padding'] : array();
        
        $styles .= '.shopglut-social-share {';
        $styles .= 'display: flex;';
        $styles .= 'align-items: center;';
        $styles .= 'flex-wrap: wrap;';
        $styles .= 'justify-content:flex-end;';
        $gap_width = !empty($enhancements['social-share-button-spacing']['width']) ? $enhancements['social-share-button-spacing']['width'] : '8';
        $gap_unit = !empty($enhancements['social-share-button-spacing']['unit']) ? $enhancements['social-share-button-spacing']['unit'] : 'px';
        $styles .= 'gap: ' . $gap_width . $gap_unit . ';';
        
        $styles .= sprintf('margin: %s%s %s%s %s%s %s%s;',
            !empty($container_margin['top']) ? $container_margin['top'] : '20',
            !empty($container_margin['unit']) ? $container_margin['unit'] : 'px',
            !empty($container_margin['right']) ? $container_margin['right'] : '0',
            !empty($container_margin['unit']) ? $container_margin['unit'] : 'px',
            !empty($container_margin['bottom']) ? $container_margin['bottom'] : '20',
            !empty($container_margin['unit']) ? $container_margin['unit'] : 'px',
            !empty($container_margin['left']) ? $container_margin['left'] : '0',
            !empty($container_margin['unit']) ? $container_margin['unit'] : 'px'
        );

        $styles .= sprintf('padding: %s%s %s%s %s%s %s%s;',
            !empty($container_padding['top']) ? $container_padding['top'] : '15',
            !empty($container_padding['unit']) ? $container_padding['unit'] : 'px',
            !empty($container_padding['right']) ? $container_padding['right'] : '15',
            !empty($container_padding['unit']) ? $container_padding['unit'] : 'px',
            !empty($container_padding['bottom']) ? $container_padding['bottom'] : '15',
            !empty($container_padding['unit']) ? $container_padding['unit'] : 'px',
            !empty($container_padding['left']) ? $container_padding['left'] : '15',
            !empty($container_padding['unit']) ? $container_padding['unit'] : 'px'
        );
        $styles .= '}';

        // Share title styles
        $title_color = !empty($enhancements['social-share-title-color']) ? $enhancements['social-share-title-color'] : '#333333';
        $title_font_width = !empty($enhancements['social-share-title-font-size']['width']) ? $enhancements['social-share-title-font-size']['width'] : '16';
        $title_font_unit = !empty($enhancements['social-share-title-font-size']['unit']) ? $enhancements['social-share-title-font-size']['unit'] : 'px';
        $title_font_size = $title_font_width . $title_font_unit;
        
        $styles .= '.shopglut-social-share .share-title {';
        $styles .= 'color: ' . $title_color . ';';
        $styles .= 'font-size: ' . $title_font_size . ';';
        $styles .= 'margin-right: 10px;';
        $styles .= 'font-weight: 500;';
        $styles .= '}';

        // Button base styles
        $button_size = array(
            'width' => !empty($enhancements['social-share-button-size']['width']) ? $enhancements['social-share-button-size']['width'] : '40',
            'height' => !empty($enhancements['social-share-button-size']['height']) ? $enhancements['social-share-button-size']['height'] : '40',
            'unit' => !empty($enhancements['social-share-button-size']['unit']) ? $enhancements['social-share-button-size']['unit'] : 'px'
        );
        $border_radius_width = !empty($enhancements['social-share-button-border-radius']['width']) ? $enhancements['social-share-button-border-radius']['width'] : '5';
        $border_radius_unit = !empty($enhancements['social-share-button-border-radius']['unit']) ? $enhancements['social-share-button-border-radius']['unit'] : 'px';
        $border_radius = $border_radius_width . $border_radius_unit;
        $icon_color = !empty($enhancements['social-share-icon-color']) ? $enhancements['social-share-icon-color'] : '#ffffff';
        $hover_opacity_value = $enhancements['social-share-button-hover-opacity'] ?? 80;
        if (is_array($hover_opacity_value)) {
            $hover_opacity_value = !empty($hover_opacity_value['social-share-button-hover-opacity']) ? $hover_opacity_value['social-share-button-hover-opacity'] : 80;
        }
        $hover_opacity = $hover_opacity_value / 100;

        $styles .= '.shopglut-social-share .social-share-btn {';
        $styles .= 'display: inline-flex;';
        $styles .= 'align-items: center;';
        $styles .= 'justify-content: center;';
        $styles .= 'width: ' . $button_size['width'] . $button_size['unit'] . ';';
        $styles .= 'height: ' . $button_size['height'] . $button_size['unit'] . ';';
        $styles .= 'border-radius: ' . $border_radius . ';';
        $styles .= 'text-decoration: none !important;';
        $styles .= 'transition: opacity 0.3s ease;';
        $styles .= 'color: ' . $icon_color . ' !important;';
        $styles .= '}';

        $styles .= '.shopglut-social-share .social-share-btn:hover {';
        $styles .= 'opacity: ' . $hover_opacity . ';';
        $styles .= '}';

        $styles .= '.shopglut-social-share .social-share-btn i {';
        $styles .= 'font-size: calc(' . $button_size['width'] . $button_size['unit'] . ' * 0.5);';
        $styles .= '}';

        // Individual platform colors
        $platform_colors = array(
            'facebook' => !empty($enhancements['social-share-facebook-color']) ? $enhancements['social-share-facebook-color'] : '#1877f2',
            'twitter' => !empty($enhancements['social-share-twitter-color']) ? $enhancements['social-share-twitter-color'] : '#1da1f2',
            'whatsapp' => !empty($enhancements['social-share-whatsapp-color']) ? $enhancements['social-share-whatsapp-color'] : '#25d366',
            'pinterest' => !empty($enhancements['social-share-pinterest-color']) ? $enhancements['social-share-pinterest-color'] : '#bd081c',
            'linkedin' => !empty($enhancements['social-share-linkedin-color']) ? $enhancements['social-share-linkedin-color'] : '#0077b5',
            'telegram' => !empty($enhancements['social-share-telegram-color']) ? $enhancements['social-share-telegram-color'] : '#0088cc',
            'email' => !empty($enhancements['social-share-email-color']) ? $enhancements['social-share-email-color'] : '#666666',
        );

        foreach ($platform_colors as $platform => $color) {
            $styles .= '.shopglut-social-share .' . $platform . '-share {';
            $styles .= 'background-color: ' . $color . ';';
            $styles .= '}';
        }

        $styles .= '</style>';
        
        return $styles;
    }

    /**
     * Output social share styles in header (optional method to include in wp_head)
     */
    public function enqueue_social_share_styles() {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->generate_social_share_styles();
    }
    
    /**
     * Add CSS styles for shared social wishlist (similar to QR styles)
     */
    private function add_social_shared_wishlist_styles() {
        // Only add styles once
        static $styles_added = false;
        if ($styles_added) return;
        $styles_added = true;
        
        ?>
        <style>
        .shopglut-shared-wishlist {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .shared-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .shared-header h3 {
            margin: 0 0 10px 0;
            font-size: 28px;
            color:#fff;
        }
        
        .shared-header .list-name {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        
        .wishlist-stats {
            margin-bottom: 20px;
            text-align: center;
        }
        
       .item-count {
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            color: #495057;
            margin-right: 15px;
        }
        
        .list-name {
            background: #e9ecef;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            color: #495057;
        }
        
        .shared-wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 32px;
            margin-top: 40px;
            padding: 0 20px;
        }
        
        .shared-wishlist-grid  .shared-product-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0f2f5;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
         .shared-wishlist-grid .shared-product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            border-color: #e8eaed;
        }
        
         .shared-wishlist-grid  .shared-product-card.out-of-stock {
            opacity: 0.7;
        }
        
        .shared-wishlist-grid .product-image {
            position: relative;
            overflow: hidden;
            background: #fafbfc;
        }
        
        .shared-wishlist-grid  .product-image img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        
         .shared-wishlist-grid  .shared-product-card:hover .product-image img {
            transform: scale(1.05);
        }
        
         .shared-wishlist-grid .sale-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.25);
        }
        
         .shared-wishlist-grid .product-info {
            padding: 28px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
          
        }
        
         .shared-wishlist-grid .product-details {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 24px;
            gap: 12px;
            flex-wrap: nowrap;
            flex-direction:row;
        }
        
         .shared-wishlist-grid .product-info h4 {
            margin: 0;
            font-size: 16px;
            line-height: 1.4;
            font-weight: 600;
            color: #1a1a1a;
            flex: 1;
            min-width: 0;
            display: inline-block;
        }
        
        .shared-wishlist-grid  .product-info h4 a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
            display: inline;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
         .shared-wishlist-grid .product-info h4 a:hover {
            color: #0066cc;
        }
        
         .shared-wishlist-grid .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #0066cc;
            margin: 0;
            white-space: nowrap;
            line-height: 1.2;
            flex-shrink: 0;
            display: inline-block;
        }
        
         .shared-wishlist-grid .product-price .woocommerce-Price-amount {
            display: inline;
        }
        
         .shared-wishlist-grid .product-price del {
            color: #999999;
            font-size: 14px;
            font-weight: 500;
            margin-right: 6px;
            text-decoration: line-through;
        }
        
         .shared-wishlist-grid .product-price ins {
            text-decoration: none;
            color: #0066cc;
            font-weight: 700;
        }
        
         .shared-wishlist-grid .stock-status {
            margin: 0;
            white-space: nowrap;
            flex-shrink: 0;
            display: inline-block;
        }
        
         .shared-wishlist-grid .stock-status span {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
         .shared-wishlist-grid .in-stock {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .shared-wishlist-grid  .low-stock {
            background: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ffcc02;
        }
        
         .shared-wishlist-grid .out-of-stock {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        
         .shared-wishlist-grid .product-actions {
            display: block;
        }
        
        .shared-wishlist-grid  .view-product-btn {
            display: inline-block;
            width: 100%;
            background: #0066cc;
            color: white;
            padding: 16px 24px;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
         .shared-wishlist-grid .view-product-btn:hover {
            background: #0052a3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .shared-wishlist-grid  .view-product-btn:active {
            transform: translateY(0);
        }
        
        .shared-wishlist-grid  .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
         .shared-wishlist-grid .empty-wishlist i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .shopglut-error,
        .shopglut-warning {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .shopglut-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .shopglut-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        @media (max-width: 768px) {
            .shared-wishlist-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 24px;
                padding: 0 16px;
            }
            
             .shared-wishlist-grid .product-info {
                padding: 24px;
            }
            
             .shared-wishlist-grid .product-details {
                gap: 12px;
            }
            
            .shared-wishlist-grid  .product-info h4 {
                font-size: 15px;
            }
            
             .shared-wishlist-grid .product-price {
                font-size: 16px;
            }
            
             .shared-wishlist-grid .stock-status span {
                padding: 6px 12px;
                font-size: 12px;
            }
            
             .shared-wishlist-grid .view-product-btn {
                padding: 14px 20px;
                font-size: 14px;
            }
            
            .shared-wishlist-grid  .shared-header h3 {
                font-size: 24px;
            }
        }
        
        @media (max-width: 480px) {
            .shared-wishlist-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 12px;
            }
            
            .shared-wishlist-grid  .product-image img {
                height: 220px;
            }
            
            .shared-wishlist-grid  .product-info {
                padding: 20px;
            }
            
            .shared-wishlist-grid  .product-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
             .shared-wishlist-grid .product-info h4 {
                font-size: 16px;
            }
            
             .shared-wishlist-grid .product-price {
                font-size: 18px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Initialize social features hooks for pro integration
     */
    public function init_wishlist_social_features_hooks() {
        add_action( 'shopglut_render_social_share_buttons', array( $this, 'handle_render_social_share_buttons' ), 10, 1 );
        add_action( 'shopglut_render_wishlist_popular', array( $this, 'handle_render_wishlist_popular' ), 10, 1 );
    }
    
    /**
     * Handle social share buttons hook
     */
    public function handle_render_social_share_buttons($list_name = '') {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->render_social_share_buttons($list_name);
    }
    
    /**
     * Handle popular wishlists hook
     */
    public function handle_render_wishlist_popular($product_ids = array()) {
        // Check if popular wishlists are enabled
        if (isset( $this->enhancements['wishlist-enable-other-wishlist'] ) && 
            $this->enhancements['wishlist-enable-other-wishlist'] === '1') {
            echo wp_kses_post( $this->render_wishlist_popular($product_ids) );
        }
    }
}