<?php
namespace Shopglut\layouts\shopLayout\templates;

if (!defined('ABSPATH')) {
    exit;
}
class template19 {

    public function layout_render($template_data) {

        $product_id = get_the_ID();
        $product = wc_get_product($product_id);

        // New Product Badge
        $newness_days = 30;
        $product_created_date = $product->get_date_created()->getTimestamp();
        $current_date = current_time('timestamp');
        $is_new = ($product_created_date > strtotime('-' . $newness_days . ' days'));

        $is_out_of_stock = !$product->is_in_stock();
        $is_featured = $product->is_featured();

        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $discount_percentage = 0;

        if ($regular_price && $sale_price) {
            $discount_percentage = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
        }

        $currency_symbol = get_woocommerce_currency_symbol();
        $product_link = get_permalink($product_id);
        $product_name = get_the_title($product_id);
        $product_image = get_the_post_thumbnail_url($product_id, 'full');
        if(!$product_image){
            $product_image = SHOPGLUT_URL."assets/images/no-image.jpg"; 
        }

        // Get gallery images for carousel
        $gallery_images = [];
        $product_gallery_ids = $product->get_gallery_image_ids();
        if ($product_gallery_ids) {
            foreach ($product_gallery_ids as $image_id) {
                $gallery_images[] = wp_get_attachment_url($image_id);
            }
        }

        $user_id = get_current_user_id();
        $cache_key = 'shopglut_wishlist_' . $user_id;
        $wishlist_product_ids = wp_cache_get($cache_key);
        
        if (false === $wishlist_product_ids) {
            global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            $user_actions = $wpdb->prefix . 'shopglut_user_actions';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT product_id FROM {$wpdb->prefix}shopglut_user_actions WHERE user_id = %d AND action_type = %s",
                $user_id,
                'wishlist'
            ));

            $wishlist_product_ids = array();
            if ($results) {
                foreach ($results as $row) {
                    $wishlist_product_ids[] = $row->product_id;
                }
            }
            
            wp_cache_set($cache_key, $wishlist_product_ids, '', 3600);
        }

        // Get product categories and tags
        $categories_id_array = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $tags_id_array = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'ids'));
        $category_ids = !empty($categories_id_array) ? implode(', ', $categories_id_array) : 'No categories';
        $tag_ids = !empty($tags_id_array) ? implode(', ', $tags_id_array) : 'No tags';

        $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
        $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names'));
        $category_names = !empty($categories) ? implode(', ', $categories) : 'No categories';
        $tag_names = !empty($tags) ? implode(', ', $tags) : 'No tags';

        // Get product attributes for display in properties list
        $attributes = $product->get_attributes();
        $product_attributes = [];
        
        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                if ($attribute->get_visible()) {
                    $attribute_name = wc_attribute_label($attribute->get_name());
                    
                    if ($attribute->is_taxonomy()) {
                        $terms = wp_get_post_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                        $attribute_value = implode(', ', $terms);
                    } else {
                        $attribute_value = $attribute->get_options()[0];
                    }
                    
                    $product_attributes[$attribute_name] = $attribute_value;
                }
            }
        }

        $product_type = $product->get_type();

        // Get the WooCommerce cart instance
        $cart = WC()->cart;
        $is_in_cart = false;
        if (is_object($cart)) {
            $cart_items = $cart->get_cart();
            foreach ($cart_items as $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    $is_in_cart = true;
                    break;
                }
            }
        }

        // Get product rating and reviews
        $average_rating = $product->get_average_rating();
        $review_count = $product->get_review_count();

        $wishlist_options = get_option('agshopglut_wishlist_options');
        ?>

<div class="product-design product-template19 prodtb-i" <?php if (!empty($product_id)): ?>
    data-product-id="<?php echo esc_attr($product_id); ?>" <?php endif;?> <?php if (!empty($category_ids)): ?>
    data-product-category="<?php echo esc_attr($category_ids); ?>" <?php endif;?> <?php if (!empty($tag_ids)): ?>
    data-product-tags="<?php echo esc_attr($tag_ids); ?>" <?php endif;?> <?php if (!empty($product_type)): ?>
    data-product-type="<?php echo esc_attr($product_type); ?>" <?php endif;?>>

    <!-- Product Top Bar - AllStore Style -->
    <div class="prodtb-i-top">
        <button class="prodtb-i-toggle" type="button"></button>
        <h3 class="prodtb-i-ttl"><a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($product_name); ?></a></h3>
        <div class="prodtb-i-info">
            <span class="prodtb-i-price">
                <b><?php echo esc_html($currency_symbol . $regular_price); ?></b>
                <?php if ($sale_price): ?>
                <del><?php echo esc_html($currency_symbol . $sale_price); ?></del>
                <?php endif; ?>
            </span>
            <p class="prodtb-i-qnt">
                <input value="1" type="text">
                <a href="#" class="prodtb-i-plus"><i class="fa fa-angle-up"></i></a>
                <a href="#" class="prodtb-i-minus"><i class="fa fa-angle-down"></i></a>
            </p>
        </div>
        <p class="prodtb-i-action">
            <!-- Wishlist Button -->
            <a href="#" class="prodtb-i-favorites" 
                <?php if ($wishlist_options['wishlist-require-login'] == true && !is_user_logged_in()): ?>
                    href="<?php echo esc_url(wp_login_url(site_url(isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : ''))); ?>"
                <?php else: ?>
                    data-product-id="<?php echo esc_attr($product_id); ?>"
                <?php endif; ?>>
                <span>Wishlist</span>
                <i class="fa <?php echo in_array($product_id, $wishlist_product_ids) ? 'fa-heart' : 'fa-heart-o'; ?>"></i>
            </a>
            
            <!-- Compare Button -->
            <a class="prodtb-i-compare" href="#" data-product-id="<?php echo esc_attr($product_id); ?>">
                <span>Compare</span>
                <i class="fa fa-bar-chart"></i>
            </a>
            
            <!-- Quick View Button -->
            <a href="#" class="qview-btn prodtb-i-qview" data-product-id="<?php echo esc_attr($product_id); ?>">
                <span>Quick View</span>
                <i class="fa fa-search"></i>
            </a>
            
            <!-- Add to Cart Button -->
            <?php if ($product_type === 'simple'): ?>
            <a href="<?php echo $is_in_cart ? esc_url(wc_get_cart_url()) : '#'; ?>" 
               class="prodtb-i-buy <?php echo !$is_in_cart ? 'ajax-spin-cart' : ''; ?>"
               <?php if (!$is_in_cart): ?>data-product-id="<?php echo esc_attr($product_id); ?>"<?php endif; ?>>
                <span>Add to cart</span>
                <i class="fa fa-shopping-basket"></i>
            </a>
            <?php endif; ?>
        </p>
    </div>

    <!-- Product Details - Expanded Content -->
    <div class="prodlist-i">
        <!-- Product Image Carousel -->
        <a class="list-img-carousel prodlist-i-img" href="<?php echo esc_url($product_link); ?>">
            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_name); ?>">
            <?php foreach ($gallery_images as $gallery_image): ?>
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($product_name); ?>">
            <?php endforeach; ?>
        </a>
        
        <!-- Product Content -->
        <div class="prodlist-i-cont">
            <!-- Product Description -->
            <div class="prodlist-i-txt">
                <?php echo esc_html(wp_trim_words($product->get_short_description(), 25, '...')); ?>
            </div>
            
            <!-- Product Options (Colors, Sizes, etc) -->
            <?php if (!empty($attributes)): ?>
            <div class="prodlist-i-skuwrap">
                <?php foreach ($attributes as $attribute): ?>
                <?php if ($attribute->get_visible()): ?>
                <div class="prodlist-i-skuitem">
                    <p class="prodlist-i-skuttl"><?php echo esc_html(wc_attribute_label($attribute->get_name())); ?></p>
                    
                    <?php if (strpos(strtolower($attribute->get_name()), 'color') !== false): ?>
                    <!-- Color Swatch Display -->
                    <ul class="prodlist-i-skucolor">
                        <?php
                        $terms = wp_get_post_terms($product_id, $attribute->get_name(), array('fields' => 'all'));
                        foreach ($terms as $term):
                            $color = get_term_meta($term->term_id, 'product_attribute_color', true) ?: '#cccccc';
                        ?>
                        <li class="<?php echo $term->slug === 'blue' ? 'active' : ''; ?>">
                            <img src="<?php echo esc_url(SHOPGLUT_URL . 'global-assets/images/color/' . $term->slug . '.jpg'); ?>" 
                                 alt="<?php echo esc_attr($term->name); ?>"
                                 style="background-color: <?php echo esc_attr($color); ?>;">
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <!-- Dropdown Select for Other Attributes -->
                    <div class="offer-props-select">
                        <p>XS</p>
                        <ul>
                            <?php
                            if ($attribute->is_taxonomy()):
                                $terms = wp_get_post_terms($product_id, $attribute->get_name(), array('fields' => 'all'));
                                foreach ($terms as $term):
                            ?>
                            <li class="<?php echo $term->slug === 'xs' ? 'active' : ''; ?>">
                                <a href="#"><?php echo esc_html($term->name); ?></a>
                            </li>
                            <?php 
                                endforeach;
                            else:
                                $values = $attribute->get_options();
                                foreach ($values as $value):
                            ?>
                            <li><a href="#"><?php echo esc_html($value); ?></a></li>
                            <?php endforeach; endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Product Properties/Specifications -->
        <ul class="prodlist-i-props2">
            <?php 
            // Display product attributes in two columns
            if (!empty($product_attributes)):
                $count = 0;
                foreach ($product_attributes as $name => $value):
                    $count++;
            ?>
            <li>
                <span class="prodlist-i-propttl"><span><?php echo esc_html($name); ?></span></span>
                <span class="prodlist-i-propval"><?php echo esc_html($value); ?></span>
            </li>
            <?php endforeach; endif; ?>
            
            <!-- Add standard product information if attributes are insufficient -->
            <?php if ($count < 4): ?>
            <li>
                <span class="prodlist-i-propttl"><span>Category</span></span>
                <span class="prodlist-i-propval"><?php echo esc_html($category_names); ?></span>
            </li>
            <?php endif; ?>
            
            <?php if (!empty($tags) && $count < 6): ?>
            <li>
                <span class="prodlist-i-propttl"><span>Tags</span></span>
                <span class="prodlist-i-propval"><?php echo esc_html($tag_names); ?></span>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Product Badge/Sticker -->
        <div class="prod-sticker">
            <?php if ($discount_percentage > 0): ?>
            <p class="prod-sticker-3">-<?php echo esc_html($discount_percentage); ?>%</p>
            <?php endif; ?>
            
            <?php if ($is_new): ?>
            <p class="prod-sticker-4">New</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
    }
}