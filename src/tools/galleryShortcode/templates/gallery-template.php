<?php
/**
 * Gallery Template
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Extract attributes for easier access
$layout = $atts['layout'];
$columns = intval($atts['columns']);
$columns_tablet = intval($atts['columns_tablet']);
$columns_mobile = intval($atts['columns_mobile']);
$enable_filter = $atts['filter'] === 'yes';
$filter_position = $atts['filter_position'];
$pagination_type = $atts['pagination'];
$items_per_page = intval($atts['items_per_page']);
$show_price = $atts['show_price'] === 'yes';
$show_title = $atts['show_title'] === 'yes';
$show_category = $atts['show_category'] === 'yes';
$show_rating = $atts['show_rating'] === 'yes';
$show_add_to_cart = $atts['show_add_to_cart'] === 'yes';
$hover_effect = $atts['hover_effect'];
$animation = $atts['animation'];
$lazy_load = $atts['lazy_load'] === 'yes';

// Build WooCommerce product query
$query_args = [
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $items_per_page,
    'orderby' => $atts['orderby'],
    'order' => $atts['order'],
];

// Add category filter
if (!empty($atts['category'])) {
    $category_ids = explode(',', $atts['category']);
    $query_args['tax_query'][] = [
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => array_map('intval', $category_ids),
    ];
}

// Add tag filter
if (!empty($atts['tag'])) {
    $tag_ids = explode(',', $atts['tag']);
    $query_args['tax_query'][] = [
        'taxonomy' => 'product_tag',
        'field' => 'term_id',
        'terms' => array_map('intval', $tag_ids),
    ];
}

// Featured only filter
if ($atts['featured_only'] === 'yes') {
    $query_args['tax_query'][] = [
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => 'featured',
    ];
}

// Sale only filter
if ($atts['sale_only'] === 'yes') {
    $query_args['meta_query'][] = [
        'key' => '_sale_price',
        'value' => 0,
        'compare' => '>',
        'type' => 'NUMERIC',
    ];
}

$product_query = new \WP_Query($query_args);

// Get product categories for filter
$filter_categories = [];
if ($enable_filter) {
    $filter_categories = GalleryShortcode::get_product_categories();
}
?>

<div id="<?php echo esc_attr($gallery_id); ?>" class="<?php echo esc_attr($gallery_classes); ?>"
     data-gallery-id="<?php echo esc_attr($gallery_id); ?>"
     data-layout="<?php echo esc_attr($layout); ?>"
     data-columns="<?php echo esc_attr($columns); ?>"
     data-columns-tablet="<?php echo esc_attr($columns_tablet); ?>"
     data-columns-mobile="<?php echo esc_attr($columns_mobile); ?>"
     data-items-per-page="<?php echo esc_attr($items_per_page); ?>"
     data-pagination="<?php echo esc_attr($pagination_type); ?>"
     data-orderby="<?php echo esc_attr($atts['orderby']); ?>"
     data-order="<?php echo esc_attr($atts['order']); ?>"
     data-lazy-load="<?php echo $lazy_load ? 'true' : 'false'; ?>">

    <?php if ($enable_filter && !empty($filter_categories) && $filter_position === 'top'): ?>
        <div class="shopglut-gallery-filters">
            <div class="shopglut-gallery-filter-buttons">
                <button class="shopglut-filter-btn active" data-filter="*">
                    <?php esc_html_e('All', 'shopglut'); ?>
                </button>
                <?php foreach ($filter_categories as $category): ?>
                    <button class="shopglut-filter-btn" data-filter=".category-<?php echo esc_attr($category['slug']); ?>">
                        <?php echo esc_html($category['name']); ?>
                        <span class="shopglut-filter-count">(<?php echo intval($category['count']); ?>)</span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="shopglut-gallery-wrapper">
        <?php if ($layout === 'carousel'): ?>
            <div class="swiper shopglut-gallery-carousel">
                <div class="swiper-wrapper">
        <?php endif; ?>

        <?php if ($layout === 'isotope' || $enable_filter): ?>
            <div class="shopglut-gallery-isotope">
        <?php endif; ?>

        <div class="shopglut-gallery-items"
             <?php echo $layout === 'grid' ? 'style="display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: 20px;"' : ''; ?>>

            <?php
            if ($product_query->have_posts()) {
                while ($product_query->have_posts()) {
                    $product_query->the_post();
                    global $product;

                    if (!$product) {
                        continue;
                    }

                    // Get product categories for filter classes
                    $product_categories = get_the_terms(get_the_ID(), 'product_cat');
                    $filter_classes = [];
                    if ($product_categories && !is_wp_error($product_categories)) {
                        foreach ($product_categories as $cat) {
                            $filter_classes[] = 'category-' . $cat->slug;
                        }
                    }

                    // Get product image
                    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'woocommerce_thumbnail');
                    $image_url = $product_image ? $product_image[0] : wc_placeholder_img_src('woocommerce_thumbnail');

                    // Get product category display
                    $product_category_display = '';
                    if ($show_category && $product_categories) {
                        $category_names = array_map(function($cat) {
                            return $cat->name;
                        }, array_slice($product_categories, 0, 2));
                        $product_category_display = implode(', ', $category_names);
                    }
                    ?>

                    <div class="shopglut-gallery-item <?php echo esc_attr(implode(' ', $filter_classes)); ?>"
                         <?php if ($layout === 'carousel'): ?>class="swiper-slide"<?php endif; ?>
                         <?php if ($lazy_load): ?>data-src="<?php echo esc_url($image_url); ?>"<?php endif; ?>>

                        <div class="shopglut-gallery-item-inner">
                            <div class="shopglut-gallery-image-wrapper">
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="shopglut-gallery-image-link">
                                    <img src="<?php echo esc_url($lazy_load ? wc_placeholder_img_src('woocommerce_thumbnail') : $image_url); ?>"
                                         alt="<?php echo esc_attr(get_the_title()); ?>"
                                         class="shopglut-gallery-image"
                                         <?php if ($lazy_load): ?>loading="lazy"<?php endif; ?>>

                                    <?php if ($product->is_on_sale()): ?>
                                        <span class="shopglut-gallery-sale-badge"><?php esc_html_e('Sale!', 'shopglut'); ?></span>
                                    <?php endif; ?>

                                    <?php if ($product->is_featured()): ?>
                                        <span class="shopglut-gallery-featured-badge"><?php esc_html_e('Featured', 'shopglut'); ?></span>
                                    <?php endif; ?>
                                </a>

                                <?php if ($show_add_to_cart): ?>
                                    <div class="shopglut-gallery-overlay">
                                        <div class="shopglut-gallery-overlay-content">
                                            <?php
                                            if ($product->is_type('simple')) {
                                                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="shopglut-add-to-cart button">' . esc_html($product->add_to_cart_text()) . '</a>';
                                            } else {
                                                echo '<a href="' . esc_url(get_permalink()) . '" class="shopglut-view-product button">' . esc_html__('View Product', 'shopglut') . '</a>';
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(get_permalink()); ?>" class="shopglut-view-details button">
                                                <?php esc_html_e('Details', 'shopglut'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="shopglut-gallery-content">
                                <?php if ($show_category && !empty($product_category_display)): ?>
                                    <div class="shopglut-gallery-category">
                                        <?php echo esc_html($product_category_display); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($show_title): ?>
                                    <h3 class="shopglut-gallery-title">
                                        <a href="<?php echo esc_url(get_permalink()); ?>">
                                            <?php echo wp_kses_post(get_the_title()); ?>
                                        </a>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($show_rating && $product->get_average_rating() > 0): ?>
                                    <div class="shopglut-gallery-rating">
                                        <?php echo wp_kses_post(wc_get_rating_html($product->get_average_rating())); ?>
                                        <span class="shopglut-rating-count">
                                            (<?php echo intval($product->get_rating_count()); ?>)
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($show_price): ?>
                                    <div class="shopglut-gallery-price">
                                        <?php echo wp_kses_post($product->get_price_html()); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                wp_reset_postdata();
            } else {
                ?>
                <div class="shopglut-gallery-no-products">
                    <p><?php esc_html_e('No products found.', 'shopglut'); ?></p>
                </div>
                <?php
            }
            ?>

        </div>

        <?php if ($layout === 'isotope' || $enable_filter): ?>
            </div>
        <?php endif; ?>

        <?php if ($layout === 'carousel'): ?>
                </div>

                <?php if ($product_query->post_count > 1): ?>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($enable_filter && !empty($filter_categories) && $filter_position === 'bottom'): ?>
        <div class="shopglut-gallery-filters">
            <div class="shopglut-gallery-filter-buttons">
                <button class="shopglut-filter-btn active" data-filter="*">
                    <?php esc_html_e('All', 'shopglut'); ?>
                </button>
                <?php foreach ($filter_categories as $category): ?>
                    <button class="shopglut-filter-btn" data-filter=".category-<?php echo esc_attr($category['slug']); ?>">
                        <?php echo esc_html($category['name']); ?>
                        <span class="shopglut-filter-count">(<?php echo intval($category['count']); ?>)</span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($pagination_type === 'yes' && $product_query->max_num_pages > 1): ?>
        <div class="shopglut-gallery-pagination">
            <?php
            $pagination_args = [
                'format' => '?pg=%#%',
                'current' => max(1, get_query_var('pg')),
                'total' => $product_query->max_num_pages,
                'prev_text' => __('&laquo; Previous', 'shopglut'),
                'next_text' => __('Next &raquo;', 'shopglut'),
                'type' => 'array',
                'before_page_number' => '<span>',
                'after_page_number' => '</span>',
            ];

            $pagination_links = paginate_links($pagination_args);

            if ($pagination_links) {
                echo '<ul class="shopglut-pagination-links">';
                foreach ($pagination_links as $link) {
                    echo '<li class="shopglut-pagination-item">' . wp_kses_post($link) . '</li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if ($pagination_type === 'load_more' && $product_query->max_num_pages > 1): ?>
        <div class="shopglut-gallery-load-more">
            <button class="shopglut-load-more-btn" data-page="2" data-max-pages="<?php echo intval($product_query->max_num_pages); ?>">
                <?php esc_html_e('Load More Products', 'shopglut'); ?>
                <span class="shopglut-loading-spinner" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
            </button>
        </div>
    <?php endif; ?>

</div>

<!-- Gallery Configuration JSON -->
<script type="application/json" id="<?php echo esc_attr($gallery_id); ?>-config">
{
    "galleryId": "<?php echo esc_attr($gallery_id); ?>",
    "layout": "<?php echo esc_attr($layout); ?>",
    "columns": <?php echo intval($columns); ?>,
    "columnsTablet": <?php echo intval($columns_tablet); ?>,
    "columnsMobile": <?php echo intval($columns_mobile); ?>,
    "spacing": "<?php echo esc_attr($atts['spacing']); ?>",
    "enableFilter": <?php echo $enable_filter ? 'true' : 'false'; ?>,
    "filterPosition": "<?php echo esc_attr($filter_position); ?>",
    "paginationType": "<?php echo esc_attr($pagination_type); ?>",
    "itemsPerPage": <?php echo intval($items_per_page); ?>,
    "hoverEffect": "<?php echo esc_attr($hover_effect); ?>",
    "animation": "<?php echo esc_attr($animation); ?>",
    "lazyLoad": <?php echo $lazy_load ? 'true' : 'false'; ?>,
    "orderby": "<?php echo esc_attr($atts['orderby']); ?>",
    "order": "<?php echo esc_attr($atts['order']); ?>",
    "category": "<?php echo esc_attr($atts['category']); ?>",
    "tag": "<?php echo esc_attr($atts['tag']); ?>"
}
</script>