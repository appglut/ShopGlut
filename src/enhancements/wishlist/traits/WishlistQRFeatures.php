<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistQRFeatures {
    
    /**
     * Generate QR code for wishlist sharing
     */
    public function generate_wishlist_qr_code($user_id = null, $list_name = '') {
        if (!$user_id) {
            $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        }
        
        // Create shareable wishlist URL
        $wishlist_url = $this->get_shareable_wishlist_url($user_id, $list_name);
        
        // Generate QR code using Google Charts API (free)
        $qr_code_url = $this->generate_qr_code_url($wishlist_url);
        
        return array(
            'qr_url' => $qr_code_url,
            'wishlist_url' => $wishlist_url,
            'user_id' => $user_id,
            'list_name' => $list_name
        );
    }
    
    /**
     * Generate QR code for individual product
     */
    public function generate_product_qr_code($product_id) {
        $product = wc_get_product($product_id);
        if (!$product) {
            return false;
        }
        
        $product_url = get_permalink($product_id);
        $qr_code_url = $this->generate_qr_code_url($product_url);
        
        return array(
            'qr_url' => $qr_code_url,
            'product_url' => $product_url,
            'product_name' => $product->get_name(),
            'product_id' => $product_id
        );
    }
    
    /**
     * Create shareable wishlist URL with encrypted user data
     */
    private function get_shareable_wishlist_url($user_id, $list_name = '') {
        // Get wishlist page URL
        $wishlist_page_id = isset($this->enhancements['wishlist-general-page']) ? $this->enhancements['wishlist-general-page'] : '';
        $base_url = $wishlist_page_id ? get_permalink($wishlist_page_id) : home_url('/wishlist/');
        
        // Create encrypted data payload
        $share_data = array(
            'user_id' => $user_id,
            'list_name' => $list_name,
            'timestamp' => time()
        );
        
        // Encrypt the data (simple base64 encoding for basic obfuscation)
        $encrypted_data = base64_encode(json_encode($share_data));
        
        // Add share parameters
        $share_url = add_query_arg(array(
            'view_shared' => '1',
            'data' => $encrypted_data
        ), $base_url);
        
        return $share_url;
    }
    
    /**
     * Generate QR code URL using modern alternatives
     */
    private function generate_qr_code_url($data, $size = '200x200') {
        // Get size in pixels (remove 'x' format)
        $size_pixels = str_replace('x', '', $size);
        
        // Option 1: QR Server API (reliable free service)
        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . '&data=' . urlencode($data);
        
        // Option 2: QuickChart (alternative reliable service)
        // $qr_url = 'https://quickchart.io/qr?text=' . urlencode($data) . '&size=' . $size_pixels;
        
        // Option 3: QR Code Generator API (another alternative)
        // $qr_url = 'https://qr-code-generator.com/api/qr-code?data=' . urlencode($data) . '&size=' . $size_pixels;
        
        return $qr_url;
    }
    
    /**
     * Generate QR code using PHP library (fallback method)
     */
    private function generate_qr_code_php($data, $size = 200) {
        // Check if we can use a simple PHP QR code generation
        if (function_exists('imagecreate')) {
            return $this->create_simple_qr_code($data, $size);
        }
        
        return false;
    }
    
    /**
     * Create a simple QR code using basic PHP (very basic implementation)
     */
    private function create_simple_qr_code($data, $size = 200) {
        // This is a very basic implementation - for production use a proper QR library
        // Like: https://github.com/endroid/qr-code or similar
        
        // For now, return a data URL that generates via JavaScript
        return 'data:qr,' . base64_encode($data);
    }
    
    /**
     * Get multiple QR service URLs for redundancy
     */
    private function get_qr_service_urls($data, $size = '200x200') {
        $services = array(
            'qrserver' => 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . '&data=' . urlencode($data),
            'quickchart' => 'https://quickchart.io/qr?text=' . urlencode($data) . '&size=' . str_replace('x', '', $size),
            'qrcode_tec' => 'https://qrcode.tec-it.com/API/QRCode?data=' . urlencode($data) . '&size=' . str_replace('x', '', $size),
        );
        
        return $services;
    }
    
    /**
     * Handle shared wishlist display
     */
    public function handle_shared_wishlist_display() {
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!isset($_GET['view_shared']) || sanitize_text_field( wp_unslash( $_GET['view_shared'] ) ) !== '1') {
            return false;
        }
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        
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
        
        // Optional: Check if link is too old (e.g., older than 30 days)
        if (isset($share_data['timestamp'])) {
            $days_old = (time() - $share_data['timestamp']) / (24 * 60 * 60);
            if ($days_old > 30) {
                echo '<div class="shopglut-warning">' . esc_html__('This share link is quite old and may not reflect the current wishlist.', 'shopglut') . '</div>';
            }
        }
        
        // Display shared wishlist
        echo '<div class="shopglut-shared-wishlist">';
        echo '<div class="shared-header">';
        echo '<h3><i class="fas fa-heart"></i> ' . esc_html__('Shared Wishlist', 'shopglut') . '</h3>';
        
        if ($list_name) {
            // translators: %s is the wishlist name
            echo '<p class="list-name">' . sprintf(esc_html__('List: %s', 'shopglut'), '<strong>' . esc_html($list_name) . '</strong>') . '</p>';
        }
        echo '</div>';
        
        // Get wishlist data
        $wishlist_data = $this->get_shared_wishlist_data($user_id, $list_name);
        
        if ($wishlist_data && !empty($wishlist_data['product_ids'])) {
            $this->render_shared_wishlist_table($wishlist_data, $list_name);
        } else {
            echo '<div class="empty-wishlist">';
            echo '<i class="fas fa-heart-broken"></i>';
            echo '<p>' . esc_html__('This wishlist is empty or no longer available.', 'shopglut') . '</p>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return true;
    }
    
    /**
     * Get shared wishlist data
     */
    private function get_shared_wishlist_data($user_id, $list_name = '') {
        global $wpdb;
        
        $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
        
        $query = "SELECT product_ids FROM $wishlist_table WHERE wish_user_id = %s";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $wishlist_data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
        
        if (!$wishlist_data) {
            return false;
        }
        
        if ($list_name) {
            $sublists = apply_filters('shopglut_get_user_sublists', [], $user_id);
            if (isset($sublists[$list_name])) {
                return array(
                    'product_ids' => $sublists[$list_name],
                    'list_name' => $list_name
                );
            }
            return false;
        }
        
        // Return main wishlist
        $product_ids = array_filter(array_map('trim', explode(',', $wishlist_data->product_ids)));
        
        return array(
            'product_ids' => $product_ids,
            'list_name' => ''
        );
    }
    
    /**
     * Render shared wishlist table (read-only)
     */
    private function render_shared_wishlist_table($wishlist_data, $list_name) {
        $product_ids = $wishlist_data['product_ids'];
        
        if (empty($product_ids)) {
            echo '<p>' . esc_html__('This wishlist is empty.', 'shopglut') . '</p>';
            return;
        }
        
        // Add some stats
        echo '<div class="wishlist-stats">';
        // translators: %d is the number of items in the wishlist
        echo '<span class="item-count"><i class="fas fa-list"></i> ' . sprintf(esc_html__('%d items', 'shopglut'), count($product_ids)) . '</span>';
        echo '</div>';
        
        echo '<div class="shared-wishlist-grid">';
        
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) continue;
            
            $this->render_shared_product_card($product);
        }
        
        echo '</div>';
        
        // Add styles
        $this->add_shared_wishlist_styles();
    }
    
    /**
     * Render individual product card for shared view
     */
    private function render_shared_product_card($product) {
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
    
    /**
     * Add CSS styles for shared wishlist
     */
    private function add_shared_wishlist_styles() {
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
     * Render QR share button for wishlist
     */
    public function render_qr_share_button($list_name) {
        ?>
        <button class="shopglut-qr-share-btn" data-list-name="<?php echo esc_attr($list_name); ?>">
            <i class="fas fa-qrcode"></i> <?php echo esc_html__('Share via QR', 'shopglut'); ?>
        </button>
        <?php
    }
    
    /**
     * Render QR code modal (simplified)
     */
    public function render_qr_code_modal() {
        ?>
        <div id="shopglut-qr-modal" class="shopglut-modal" style="display: none;">
            <div class="shopglut-modal-content">
                <div class="shopglut-modal-header">
                    <span class="shopglut-modal-close">&times;</span>
                    <h3><i class="fas fa-qrcode"></i> <?php echo esc_html__('Share Wishlist', 'shopglut'); ?></h3>
                </div>
                <div class="shopglut-modal-body">
                    <div class="qr-code-container">
                        <div class="loading"><?php echo esc_html__('Generating QR code...', 'shopglut'); ?></div>
                    </div>
                    <div class="share-enhancements">
                        <div class="share-url-container">
                            <label><?php echo esc_html__('Share URL:', 'shopglut'); ?></label>
                            <div class="url-input-group">
                                <input type="text" id="share-url-input" readonly />
                                <button class="copy-link-btn">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="share-buttons">
                            <button class="download-wishlist-qr-btn">
                                <i class="fas fa-download"></i> <?php echo esc_html__('Download QR', 'shopglut'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .shopglut-modal {
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }
        
        .shopglut-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .shopglut-modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        #shopglut-qr-modal h3 {
            margin: 0;
            font-size: 18px;
            color:#fff;
        }
        
        .shopglut-modal-close {
            color: white;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .shopglut-modal-close:hover {
            opacity: 1;
        }
        
        .shopglut-modal-body {
            padding: 30px;
        }
        
        .qr-code-container {
            text-align: center;
            margin-bottom: 25px;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .qr-code-container img {
            max-width: 200px;
            height: auto;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            background: #fff;
        }
        
        .loading {
            color: #6c757d;
            font-style: italic;
        }
        
        .share-enhancements {
            margin-top: 20px;
        }
        
        .share-url-container {
            margin-bottom: 20px;
        }
        
        .share-url-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #495057;
        }
        
        .url-input-group {
            display: flex;
            border: 1px solid #ced4da;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .url-input-group input {
            flex: 1;
            padding: 12px;
            border: none;
            font-size: 14px;
            background: #f8f9fa;
        }
        
        .url-input-group input:focus {
            outline: none;
            background: #fff;
        }
        
        .copy-link-btn {
            padding: 12px 15px;
            border: none;
            background: #007cba;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 14px;
        }
        
        .copy-link-btn:hover {
            background: #005a87;
        }
        
        .copy-link-btn.copied {
            background: #28a745;
        }
        
        .share-buttons {
            text-align: center;
        }
        
        .download-wishlist-qr-btn {
            padding: 12px 25px;
            border: 1px solid #6c757d;
            background: #6c757d;
            color: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .download-wishlist-qr-btn:hover {
            background: #545b62;
        }
        
        body.shopglut-modal-open {
            overflow: hidden;
        }
        </style>
        <?php
    }
    
    /**
     * AJAX handler for generating QR code
     */
    public function shopglut_generate_qr_code() {
        check_ajax_referer('shopLayouts_nonce', 'nonce');
        
        $list_name = isset($_POST['list_name']) ? sanitize_text_field( wp_unslash( $_POST['list_name'] ) ) : '';
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
        
        if (!$user_id) {
            wp_send_json_error('User not found');
        }
        
        $qr_data = $this->generate_wishlist_qr_code($user_id, $list_name);
        
        if ($qr_data) {
            wp_send_json_success($qr_data);
        } else {
            wp_send_json_error('Failed to generate QR code');
        }
    }
}