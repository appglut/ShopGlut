<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistControls {
    
    public function render_wishlist_controls() {
        do_action( 'shopglut_before_render_wishlist_controls' );
        
        $output = $this->internal_render_wishlist_controls();
        
        do_action( 'shopglut_after_render_wishlist_controls' );
        
        return $output;
    }
    
    public static function trigger_wishlist_controls_render() {
        do_action( 'shopglut_render_wishlist_controls' );
    }
    
    private function internal_render_wishlist_controls() {
        // Get current sort and filter from URL parameters
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_sort = isset($_GET['wishlist_sort']) ? sanitize_text_field( wp_unslash( $_GET['wishlist_sort'] ) ) : 'date_added';
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_filter = isset($_GET['wishlist_filter']) ? sanitize_text_field( wp_unslash( $_GET['wishlist_filter'] ) ) : 'all';
        
        ob_start();
        ?>
        <div class="shopglut-wishlist-controls">
            <div class="wishlist-controls-row">
                <!-- Sorting Enhancements -->
                <div class="wishlist-sort">
                    <label for="wishlist-sort"><?php echo esc_html__('Sort by:', 'shopglut'); ?></label>
                    <select class="wishlist-sort-select">
                        <option value="date_added" <?php selected($current_sort, 'date_added'); ?>><?php echo esc_html__('Date Added', 'shopglut'); ?></option>
                        <option value="name" <?php selected($current_sort, 'name'); ?>><?php echo esc_html__('Product Name', 'shopglut'); ?></option>
                        <option value="price_low" <?php selected($current_sort, 'price_low'); ?>><?php echo esc_html__('Price: Low to High', 'shopglut'); ?></option>
                        <option value="price_high" <?php selected($current_sort, 'price_high'); ?>><?php echo esc_html__('Price: High to Low', 'shopglut'); ?></option>
                        <option value="availability" <?php selected($current_sort, 'availability'); ?>><?php echo esc_html__('Availability', 'shopglut'); ?></option>
                    </select>
                </div>

                <!-- Filter Enhancements -->
                <div class="wishlist-filter">
                    <label for="wishlist-filter"><?php echo esc_html__('Filter:', 'shopglut'); ?></label>
                    <select class="wishlist-filter-select">
                        <option value="all" <?php selected($current_filter, 'all'); ?>><?php echo esc_html__('All Products', 'shopglut'); ?></option>
                        <option value="in_stock" <?php selected($current_filter, 'in_stock'); ?>><?php echo esc_html__('In Stock Only', 'shopglut'); ?></option>
                        <option value="out_stock" <?php selected($current_filter, 'out_stock'); ?>><?php echo esc_html__('Out of Stock', 'shopglut'); ?></option>
                        <option value="on_sale" <?php selected($current_filter, 'on_sale'); ?>><?php echo esc_html__('On Sale', 'shopglut'); ?></option>
                    </select>
                </div>

            
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get current sorting parameter from URL
     */
    public static function get_current_sort() {
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return isset($_GET['wishlist_sort']) ? sanitize_text_field( wp_unslash( $_GET['wishlist_sort'] ) ) : 'date_added';
    }
    
    /**
     * Get current filter parameter from URL
     */
    public static function get_current_filter() {
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return isset($_GET['wishlist_filter']) ? sanitize_text_field( wp_unslash( $_GET['wishlist_filter'] ) ) : 'all';
    }
    
    /**
     * Sort wishlist products array
     */
    public static function sort_wishlist_products($products, $sort_by = 'date_added') {
        if (empty($products) || !is_array($products)) {
            return $products;
        }
        
        usort($products, function($a, $b) use ($sort_by) {
            switch($sort_by) {
                case 'name':
                    $product_a = wc_get_product($a['product_id']);
                    $product_b = wc_get_product($b['product_id']);
                    $name_a = $product_a ? $product_a->get_name() : '';
                    $name_b = $product_b ? $product_b->get_name() : '';
                    return strcmp($name_a, $name_b);
                    
                case 'price_low':
                    $product_a = wc_get_product($a['product_id']);
                    $product_b = wc_get_product($b['product_id']);
                    $price_a = $product_a ? (float)$product_a->get_price() : 0;
                    $price_b = $product_b ? (float)$product_b->get_price() : 0;
                    return $price_a - $price_b;
                    
                case 'price_high':
                    $product_a = wc_get_product($a['product_id']);
                    $product_b = wc_get_product($b['product_id']);
                    $price_a = $product_a ? (float)$product_a->get_price() : 0;
                    $price_b = $product_b ? (float)$product_b->get_price() : 0;
                    return $price_b - $price_a;
                    
                case 'availability':
                    $product_a = wc_get_product($a['product_id']);
                    $product_b = wc_get_product($b['product_id']);
                    $stock_a = $product_a && $product_a->is_in_stock() ? 1 : 0;
                    $stock_b = $product_b && $product_b->is_in_stock() ? 1 : 0;
                    return $stock_b - $stock_a; // In stock first
                    
                case 'date_added':
                default:
                    $date_a = isset($a['date_added']) ? strtotime($a['date_added']) : 0;
                    $date_b = isset($b['date_added']) ? strtotime($b['date_added']) : 0;
                    return $date_b - $date_a; // Newest first
            }
        });
        
        return $products;
    }
    
    /**
     * Filter wishlist products array
     */
    public static function filter_wishlist_products($products, $filter_by = 'all') {
        if (empty($products) || !is_array($products) || $filter_by === 'all') {
            return $products;
        }
        
        return array_filter($products, function($item) use ($filter_by) {
            $product = wc_get_product($item['product_id']);
            if (!$product) {
                return false;
            }
            
            switch($filter_by) {
                case 'in_stock':
                    return $product->is_in_stock();
                    
                case 'out_stock':
                    return !$product->is_in_stock();
                    
                case 'on_sale':
                    return $product->is_on_sale();
                    
                default:
                    return true;
            }
        });
    }
    
    public function init_wishlist_controls_hooks() {
        add_action( 'shopglut_render_wishlist_controls', array( $this, 'handle_render_wishlist_controls' ), 10 );
    }
    
    public function handle_render_wishlist_controls() {
        // Output controls directly - this is plugin-generated trusted content, not user input
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->render_wishlist_controls();
    }
}