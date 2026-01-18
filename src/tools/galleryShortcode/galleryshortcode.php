<?php
/**
 * Gallery Shortcode Main Class
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GalleryShortcode {

    /**
     * Single instance of the class
     *
     * @var GalleryShortcode
     */
    private static $instance = null;

    /**
     * Gallery shortcode tag
     *
     * @var string
     */
    private $shortcode_tag = 'shopglut_gallery';

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode($this->shortcode_tag, [$this, 'render_gallery_shortcode']);

        // Register AJAX handlers for interactive features
        add_action('wp_ajax_gallery_load_products', [$this, 'ajax_load_products']);
        add_action('wp_ajax_nopriv_gallery_load_products', [$this, 'ajax_load_products']);

        // Add custom scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_gallery_scripts']);
    }

    /**
     * Get single instance of the class
     *
     * @return GalleryShortcode
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Render gallery shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Rendered gallery HTML
     */
    public function render_gallery_shortcode($atts) {
        // Default attributes
        $atts = shortcode_atts([
            'id' => '',
            'layout' => 'grid', // grid, masonry, carousel, isotope
            'columns' => 3,
            'columns_tablet' => 2,
            'columns_mobile' => 1,
            'spacing' => 'medium', // none, small, medium, large
            'filter' => 'yes', // yes, no
            'filter_position' => 'top', // top, bottom
            'pagination' => 'yes', // yes, no, load_more
            'items_per_page' => 12,
            'orderby' => 'date', // date, title, price, sales, rating
            'order' => 'DESC', // ASC, DESC
            'category' => '',
            'tag' => '',
            'featured_only' => 'no',
            'sale_only' => 'no',
            'show_price' => 'yes',
            'show_title' => 'yes',
            'show_category' => 'yes',
            'show_rating' => 'yes',
            'show_add_to_cart' => 'yes',
            'hover_effect' => 'zoom', // zoom, fade, slide, none
            'lazy_load' => 'yes',
            'animation' => 'fadeIn', // fadeIn, slideUp, none
        ], $atts, $this->shortcode_tag);

        // Get gallery data from database if ID is provided
        $gallery_data = [];
        if (!empty($atts['id'])) {
            $gallery_data = $this->get_gallery_data($atts['id']);
            if ($gallery_data) {
                // Merge saved settings with shortcode attributes
                $atts = array_merge($atts, $gallery_data);
            }
        }

        // Enqueue required scripts and styles
        $this->enqueue_gallery_dependencies($atts);

        // Build gallery HTML
        $gallery_id = 'shopglut-gallery-' . uniqid();
        $gallery_classes = $this->build_gallery_classes($atts);

        // Start output buffering
        ob_start();

        // Include gallery template
        $this->include_gallery_template($gallery_id, $gallery_classes, $atts);

        return ob_get_clean();
    }

    /**
     * Get gallery data from database
     *
     * @param int $gallery_id Gallery ID
     * @return array|false Gallery data or false if not found
     */
    private function get_gallery_data($gallery_id) {
        $cache_key = "gallery_data_{$gallery_id}";
        $result = wp_cache_get($cache_key, 'shopglut_galleries');

        if ($result === false) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'shopglut_gallery_shortcode';

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for gallery data, implementing custom caching, safe table name from internal method
            $result = $wpdb->get_row($wpdb->prepare(
                sprintf("SELECT * FROM `%s` WHERE id = %d AND status = %d", esc_sql($table_name)), // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- Using %s instead of %i for compatibility, table name escaped with esc_sql()
                absint($gallery_id),
                1
            ));

            if ($result) {
                wp_cache_set($cache_key, $result, 'shopglut_galleries', 3600);
            }
        }

        if ($result) {
            return [
                'layout' => $result->layout,
                'columns' => $result->columns,
                'columns_tablet' => $result->columns_tablet,
                'columns_mobile' => $result->columns_mobile,
                'spacing' => $result->spacing,
                'filter' => $result->enable_filter,
                'filter_position' => $result->filter_position,
                'pagination' => $result->pagination_type,
                'items_per_page' => $result->items_per_page,
                'orderby' => $result->orderby,
                'order' => $result->order,
                'category' => $result->category_ids,
                'tag' => $result->tag_ids,
                'show_price' => $result->show_price,
                'show_title' => $result->show_title,
                'show_category' => $result->show_category,
                'show_rating' => $result->show_rating,
                'show_add_to_cart' => $result->show_add_to_cart,
                'hover_effect' => $result->hover_effect,
                'animation' => $result->animation,
            ];
        }

        return false;
    }

    /**
     * Build gallery CSS classes
     *
     * @param array $atts Gallery attributes
     * @return string CSS classes
     */
    private function build_gallery_classes($atts) {
        $classes = [
            'shopglut-gallery',
            'shopglut-gallery-' . sanitize_title($atts['layout']),
            'shopglut-gallery-' . sanitize_title($atts['spacing']),
            'shopglut-gallery-cols-' . intval($atts['columns']),
            'shopglut-gallery-cols-tablet-' . intval($atts['columns_tablet']),
            'shopglut-gallery-cols-mobile-' . intval($atts['columns_mobile']),
        ];

        if ($atts['filter'] === 'yes') {
            $classes[] = 'shopglut-gallery-filterable';
        }

        if (!empty($atts['hover_effect']) && $atts['hover_effect'] !== 'none') {
            $classes[] = 'shopglut-gallery-hover-' . sanitize_title($atts['hover_effect']);
        }

        if (!empty($atts['animation']) && $atts['animation'] !== 'none') {
            $classes[] = 'shopglut-gallery-animate-' . sanitize_title($atts['animation']);
        }

        return implode(' ', $classes);
    }

    /**
     * Include gallery template
     *
     * @param string $gallery_id Gallery ID
     * @param string $gallery_classes CSS classes
     * @param array $atts Gallery attributes
     */
    private function include_gallery_template($gallery_id, $gallery_classes, $atts) {
        // Add filter before template
        do_action('shopglut_gallery_before_render', $gallery_id, $atts);

        // Include main template file
        include __DIR__ . '/templates/gallery-template.php';

        // Add filter after template
        do_action('shopglut_gallery_after_render', $gallery_id, $atts);
    }

    /**
     * Enqueue gallery scripts and styles
     */
    public function enqueue_gallery_scripts() {
        // Main gallery styles
        wp_enqueue_style(
            'shopglut-gallery',
            SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/css/gallery.css',
            [],
            SHOPGLUT_VERSION
        );

        // Main gallery script
        wp_enqueue_script(
            'shopglut-gallery',
            SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/js/gallery.js',
            ['jquery'],
            SHOPGLUT_VERSION,
            true
        );

        // Pass AJAX URL to script
        wp_localize_script('shopglut-gallery', 'shopglutGallery', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_gallery_nonce'),
        ]);
    }

    /**
     * Enqueue gallery-specific dependencies
     *
     * @param array $atts Gallery attributes
     */
    private function enqueue_gallery_dependencies($atts) {
        // Enqueue Isotope for filtering functionality
        if ($atts['filter'] === 'yes' || $atts['layout'] === 'isotope') {
            wp_enqueue_script('isotope');

            // Custom Isotope integration
            wp_enqueue_script(
                'shopglut-gallery-isotope',
                SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/js/isotope-integration.js',
                ['jquery', 'isotope'],
                SHOPGLUT_VERSION,
                true
            );
        }

        // Enqueue Swiper for carousel layout
        if ($atts['layout'] === 'carousel') {
            wp_enqueue_script('swiper');
            wp_enqueue_style('swiper');

            // Custom Swiper integration
            wp_enqueue_script(
                'shopglut-gallery-carousel',
                SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/js/swiper-integration.js',
                ['jquery', 'swiper'],
                SHOPGLUT_VERSION,
                true
            );
        }

        // Enqueue lazy loading if enabled
        if ($atts['lazy_load'] === 'yes') {
            wp_enqueue_script(
                'shopglut-gallery-lazyload',
                SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/js/lazyload.js',
                ['jquery'],
                SHOPGLUT_VERSION,
                true
            );
        }
    }

    /**
     * AJAX handler for loading products
     */
    public function ajax_load_products() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_gallery_nonce')) {
            wp_die('Security check failed');
        }

        $page = isset($_POST['page']) ? absint(wp_unslash($_POST['page'])) : 1;
        $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
        $orderby = isset($_POST['orderby']) ? sanitize_text_field(wp_unslash($_POST['orderby'])) : 'date';
        $order = isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : 'DESC';

        // Build WooCommerce product query
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'paged' => $page,
            'orderby' => $orderby,
            'order' => $order,
        ];

        // Add category filter
        if (!empty($category)) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category,
            ];
        }

        $query = new \WP_Query($args);
        $products = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                if ($product) {
                    $products[] = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'price' => $product->get_price_html(),
                        'image' => wp_get_attachment_image_src(get_post_thumbnail_id(), 'woocommerce_thumbnail')[0],
                        'category' => wc_get_product_category_list(get_the_ID(), ', '),
                        'rating' => wc_get_rating_html($product->get_average_rating()),
                        'add_to_cart' => do_shortcode('[add_to_cart id="' . get_the_ID() . '" style=""]'),
                    ];
                }
            }
        }

        wp_reset_postdata();

        // Send response
        wp_send_json_success([
            'products' => $products,
            'max_pages' => $query->max_num_pages,
            'current_page' => $page,
        ]);
    }

    /**
     * Get available product categories for filters
     *
     * @return array Categories with slug and name
     */
    public static function get_product_categories() {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'slug' => $category->slug,
                'name' => $category->name,
                'count' => $category->count,
            ];
        }

        return $result;
    }
}