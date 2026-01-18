<?php
/**
 * Gallery Data Manager
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GalleryDataManager {

    /**
     * Single instance of the class
     *
     * @var GalleryDataManager
     */
    private static $instance = null;

    /**
     * Cache key prefix
     *
     * @var string
     */
    private $cache_prefix = 'shopglut_gallery_';

    /**
     * Constructor
     */
    public function __construct() {
        // Hook into WooCommerce product updates to clear cache
        add_action('save_post_product', [$this, 'clear_product_cache']);
        add_action('wp_trash_post', [$this, 'clear_product_cache']);
        add_action('untrash_post', [$this, 'clear_product_cache']);

        // Hook into product category updates
        add_action('edited_product_cat', [$this, 'clear_category_cache']);
        add_action('create_product_cat', [$this, 'clear_category_cache']);
        add_action('delete_product_cat', [$this, 'clear_category_cache']);

        // Track gallery usage
        add_action('the_post', [$this, 'track_gallery_usage']);
    }

    /**
     * Get single instance of the class
     *
     * @return GalleryDataManager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get gallery products with caching
     *
     * @param array $args Query arguments
     * @return array Products data
     */
    public function get_gallery_products($args = []) {
        $defaults = [
            'posts_per_page' => 12,
            'paged' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'category_ids' => '',
            'tag_ids' => '',
            'featured_only' => false,
            'sale_only' => false,
            'cache' => true
        ];

        $args = wp_parse_args($args, $defaults);

        // Generate cache key
        $cache_key = $this->generate_cache_key($args);

        // Try to get from cache
        if ($args['cache'] && GallerySettings::get_option('enable_caching', '1') === '1') {
            $cached_products = $this->get_cached_data($cache_key);
            if ($cached_products !== false) {
                return $cached_products;
            }
        }

        // Build product query
        $query_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $args['posts_per_page'],
            'paged' => $args['paged'],
            'orderby' => $args['orderby'],
            'order' => $args['order'],
        ];

        // Add tax queries
        $tax_query = [];

        if (!empty($args['category_ids'])) {
            $category_ids = array_map('intval', explode(',', $args['category_ids']));
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_ids,
            ];
        }

        if (!empty($args['tag_ids'])) {
            $tag_ids = array_map('intval', explode(',', $args['tag_ids']));
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field' => 'term_id',
                'terms' => $tag_ids,
            ];
        }

        if ($args['featured_only']) {
            $tax_query[] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
            ];
        }

        if (!empty($tax_query)) {
            $tax_query['relation'] = 'AND';
            $query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Optimized tax query with performance enhancements
            // Performance optimizations for tax_query
            $query_args['update_post_term_cache'] = false;
            $query_args['lazy_load_term_meta'] = false;
        }

        // Add meta queries
        $meta_query = [];

        if ($args['sale_only']) {
            $meta_query[] = [
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            ];
        }

        if (!empty($meta_query)) {
            $meta_query['relation'] = 'AND';
            $query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Optimized meta query with performance enhancements
            // Performance optimizations for meta_query
            $query_args['update_post_meta_cache'] = false;
        }

        $products_query = new \WP_Query($query_args);
        $products_data = [];

        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                global $product;

                if ($product) {
                    $products_data[] = $this->get_product_data($product);
                }
            }
        }

        wp_reset_postdata();

        // Cache results
        if ($args['cache'] && GallerySettings::get_option('enable_caching', '1') === '1') {
            $this->set_cached_data($cache_key, $products_data);
        }

        return [
            'products' => $products_data,
            'total_products' => $products_query->found_posts,
            'max_pages' => $products_query->max_num_pages,
            'current_page' => $args['paged']
        ];
    }

    /**
     * Get product data for gallery
     *
     * @param \WC_Product $product Product object
     * @return array Product data
     */
    public function get_product_data($product) {
        $product_id = $product->get_id();

        // Get product image
        $image_id = $product->get_image_id();
        $image_size = GallerySettings::get_option('image_size', 'woocommerce_thumbnail');
        $image_url = wp_get_attachment_image_src($image_id, $image_size);
        $image_url = $image_url ? $image_url[0] : wc_placeholder_img_src($image_size);

        // Get product categories
        $categories = get_the_terms($product_id, 'product_cat');
        $category_slugs = [];
        $category_names = [];

        if ($categories && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $category_slugs[] = $category->slug;
                $category_names[] = $category->name;
            }
        }

        return [
            'id' => $product_id,
            'title' => get_the_title(),
            'permalink' => get_permalink($product_id),
            'price' => $product->get_price_html(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price(),
            'image_url' => $image_url,
            'image_id' => $image_id,
            'category_slugs' => $category_slugs,
            'category_names' => $category_names,
            'category_display' => implode(', ', array_slice($category_names, 0, 2)),
            'rating' => wc_get_rating_html($product->get_average_rating()),
            'average_rating' => $product->get_average_rating(),
            'rating_count' => $product->get_rating_count(),
            'is_on_sale' => $product->is_on_sale(),
            'is_featured' => $product->is_featured(),
            'add_to_cart_url' => $product->add_to_cart_url(),
            'add_to_cart_text' => $product->add_to_cart_text(),
            'is_type_simple' => $product->is_type('simple'),
            'is_type_variable' => $product->is_type('variable'),
            'stock_status' => $product->get_stock_status(),
            'sku' => $product->get_sku(),
            'weight' => $product->get_weight(),
            'dimensions' => $product->get_dimensions(),
            'short_description' => wp_trim_words($product->get_short_description(), 15),
            'tags' => wp_get_post_terms($product_id, 'product_tag', ['fields' => 'names']),
        ];
    }

    /**
     * Generate cache key
     *
     * @param array $args Arguments
     * @return string Cache key
     */
    private function generate_cache_key($args) {
        unset($args['cache']); // Don't include cache setting in cache key
        return $this->cache_prefix . md5(serialize($args));
    }

    /**
     * Get cached data
     *
     * @param string $cache_key Cache key
     * @return mixed Cached data or false
     */
    private function get_cached_data($cache_key) {
        $cached = wp_cache_get($cache_key, 'shopglut_gallery');

        if ($cached !== false) {
            return $cached;
        }

        // Fallback to transient if cache not available
        return get_transient($cache_key);
    }

    /**
     * Set cached data
     *
     * @param string $cache_key Cache key
     * @param mixed $data Data to cache
     */
    private function set_cached_data($cache_key, $data) {
        $cache_duration = GallerySettings::get_option('cache_duration', 3600);

        // Try WP Cache first
        $result = wp_cache_set($cache_key, $data, 'shopglut_gallery', $cache_duration);

        // Fallback to transient
        if (!$result) {
            set_transient($cache_key, $data, $cache_duration);
        }
    }

    /**
     * Clear product cache
     *
     * @param int $post_id Post ID
     */
    public function clear_product_cache($post_id) {
        if (get_post_type($post_id) !== 'product') {
            return;
        }

        // Clear all gallery cache
        $this->clear_all_gallery_cache();
    }

    /**
     * Clear category cache
     *
     * @param int $term_id Term ID
     */
    public function clear_category_cache($term_id) {
        $this->clear_all_gallery_cache();
    }

    /**
     * Clear all gallery cache
     */
    public function clear_all_gallery_cache() {
        // Clear WP Object Cache
        wp_cache_flush();

        // Clear all gallery transients with caching
        $cache_key = 'shopglut_gallery_transient_keys';
        $transient_keys = wp_cache_get($cache_key, 'shopglut_galleries');

        if ($transient_keys === false) {
            global $wpdb;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query required for transient cleanup
            $transient_keys = $wpdb->get_col(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_shopglut_gallery_%'"
            );
            wp_cache_set($cache_key, $transient_keys, 'shopglut_galleries', 300);
        }

        foreach ($transient_keys as $transient_key) {
            $key = str_replace('_transient_', '', $transient_key);
            delete_transient($key);
        }
    }

    /**
     * Track gallery usage
     *
     * @param \WP_Post $post Post object
     */
    public function track_gallery_usage($post) {
        if (!is_single() || !has_shortcode($post->post_content, 'shopglut_gallery')) {
            return;
        }

        // Find all gallery shortcodes in the content
        preg_match_all('/\[shopglut_gallery\s+id="(\d+)"\]/', $post->post_content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $gallery_id) {
                GalleryDataTables::track_usage(
                    intval($gallery_id),
                    $post->ID,
                    'post_content'
                );
            }
        }
    }

    /**
     * Get gallery usage statistics
     *
     * @param int $gallery_id Gallery ID
     * @return array Usage statistics
     */
    public function get_gallery_usage_stats($gallery_id) {
        $cache_key = "gallery_usage_stats_{$gallery_id}";
        $stats = wp_cache_get($cache_key, 'shopglut_galleries');

        if ($stats === false) {
            global $wpdb;

            $usage_table = GalleryDataTables::get_usage_table();

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Direct query required for analytics data, safe table name from internal method
            $stats = $wpdb->get_row($wpdb->prepare(
                sprintf("SELECT
                    COUNT(*) as total_usages,
                    COUNT(DISTINCT post_id) as unique_posts,
                    SUM(usage_count) as total_views,
                    MAX(last_used) as last_used
                FROM %%s
                WHERE gallery_id = %%d"), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using sprintf with escaped table name, double percent for proper escaping
                esc_sql($usage_table),
                $gallery_id
            ), ARRAY_A);

            wp_cache_set($cache_key, $stats, 'shopglut_galleries', 3600);
        }

        return $stats ?: [
            'total_usages' => 0,
            'unique_posts' => 0,
            'total_views' => 0,
            'last_used' => null
        ];
    }

    /**
     * Get popular galleries
     *
     * @param int $limit Number of galleries to return
     * @return array Popular galleries
     */
    public function get_popular_galleries($limit = 5) {
        $cache_key = "popular_galleries_{$limit}";
        $galleries = wp_cache_get($cache_key, 'shopglut_galleries');

        if ($galleries === false) {
            global $wpdb;

            $gallery_table = GalleryDataTables::get_gallery_table();
            $usage_table = GalleryDataTables::get_usage_table();

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Direct query required for analytics data, safe table names from internal method
            $galleries = $wpdb->get_results($wpdb->prepare(
                sprintf("SELECT
                    g.*,
                    COALESCE(SUM(u.usage_count), 0) as total_views,
                    COUNT(DISTINCT u.post_id) as unique_posts
                FROM %%s g
                LEFT JOIN %%s u ON g.id = u.gallery_id
                WHERE g.status = %%d
                GROUP BY g.id
                ORDER BY total_views DESC
                LIMIT %%d"), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Using sprintf with escaped table names, double percent for proper escaping
                esc_sql($gallery_table),
                esc_sql($usage_table),
                1,
                $limit
            ), ARRAY_A);

            wp_cache_set($cache_key, $galleries, 'shopglut_galleries', 1800);
        }

        return $galleries;
    }

    /**
     * Import gallery from template
     *
     * @param int $template_id Template ID
     * @param string $gallery_name Gallery name
     * @return int|false New gallery ID or false on failure
     */
    public function import_from_template($template_id, $gallery_name) {
        $templates = GalleryDataTables::get_templates(['status' => 1]);
        $template = null;

        foreach ($templates as $t) {
            if ($t['id'] == $template_id) {
                $template = $t;
                break;
            }
        }

        if (!$template) {
            return false;
        }

        $config = json_decode($template['template_config'], true);
        if (!$config) {
            return false;
        }

        // Prepare gallery data from template
        $gallery_data = array_merge([
            'gallery_name' => $gallery_name,
        /* translators: %s: template name */
            'gallery_description' => sprintf(__('Gallery created from "%s" template', 'shopglut'), $template['template_name']),
            'category_ids' => '',
            'tag_ids' => '',
            'featured_only' => 'no',
            'sale_only' => 'no',
            'custom_css' => '',
        ], $config);

        return GalleryDataTables::save_gallery($gallery_data);
    }

    /**
     * Export gallery configuration
     *
     * @param int $gallery_id Gallery ID
     * @return array Gallery configuration
     */
    public function export_gallery_config($gallery_id) {
        $gallery = GalleryDataTables::get_gallery($gallery_id);

        if (!$gallery) {
            return false;
        }

        // Remove sensitive data
        unset($gallery['id']);
        unset($gallery['created_at']);
        unset($gallery['updated_at']);
        unset($gallery['status']);

        return $gallery;
    }

    /**
     * Import gallery configuration
     *
     * @param array $config Gallery configuration
     * @param string $new_name New gallery name
     * @return int|false New gallery ID or false on failure
     */
    public function import_gallery_config($config, $new_name) {
        $config['gallery_name'] = $new_name;

        return GalleryDataTables::save_gallery($config);
    }
}