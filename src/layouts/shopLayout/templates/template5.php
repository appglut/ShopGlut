<?php
namespace Shopglut\layouts\shopLayout\templates;

if (!defined('ABSPATH')) {
	exit;
}
class template5 {

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


		$second_image_id = null;
		$product_gallery_ids = $product->get_gallery_image_ids();
		if ($product_gallery_ids) {
			$second_image_id = $product_gallery_ids[0];
		}
		$second_image_url = wp_get_attachment_url($second_image_id);

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

		$product_type = $product->get_type();

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
		?>

<div class="product-design product-template5" <?php if (!empty($product_id)): ?>
    data-product-id="<?php echo esc_attr($product_id); ?>" <?php endif;?> <?php if (!empty($category_ids)): ?>
    data-product-category="<?php echo esc_attr($category_ids); ?>" <?php endif;?> <?php if (!empty($tag_ids)): ?>
    data-product-tags="<?php echo esc_attr($tag_ids); ?>" <?php endif;?> <?php if (!empty($product_type)): ?>
    data-product-type="<?php echo esc_attr($product_type); ?>" <?php endif;?>>

    <div class="product-item">
        <div class="product-thumb">
            <?php if (isset($template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-link-option']) && $template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-link-option'] === 'enable'): ?>
            <a href="<?php echo esc_url($product_link); ?>"
                <?php if (isset($template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-product-open-newtab']) && $template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-product-open-newtab'] === '1'): ?>
                target="_blank" <?php endif;?>>
                <?php endif;?>
                <img class="popup_cart_image pri-img" src="<?php echo esc_url($product_image); ?>" alt="">
               
                <?php if (isset($template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-link-option']) && $template_data['shopg_options_settings']['shopg_settings_options']['shopg_style_settings_accordion']['image-link-option'] === 'enable'): ?>
            </a>
            <?php endif;?>
            <div class="product-badge">
                <?php if ($is_new): ?>
                <span class="new-badge product-label"><?php echo esc_html__('New', 'shopglut'); ?></span>
                <?php endif;?>
                <?php if ($is_out_of_stock): ?>
                <span
                    class="outofstock-badge product-label"><?php echo esc_html__('Out of Stock', 'shopglut'); ?></span>
                <?php endif;?>
                <?php if ($is_featured): ?>
                <span class="featured-badge product-label"><?php echo esc_html__('Featured', 'shopglut'); ?></span>
                <?php endif;?>
                <?php if ($discount_percentage > 0): ?>
                <span class="discount-badge product-label">-<?php echo esc_html($discount_percentage); ?>%</span>
                <?php endif;?>
            </div>
            <div class="button-group">
                <div class="add-to-cart">
                    <a class="add-to-cart" title="Add to Cart" href="#"
                        data-product-id="<?php echo esc_attr($product_id); ?>"><i
                            class="fa-solid fa-cart-shopping"></i></a>
                </div>
                <div class="quick-shop">
                    <a class="quick-shop" title="Quick Shop" href="#"
                        data-product-id="<?php echo esc_attr($product_id); ?>"><i class="fa-solid fa-shop"></i></a>
                </div>
                <div class="shopg-wishlist-section"><a title="Wishlist" class="wishlist" href="#"
                        data-product-id="<?php echo esc_attr($product_id); ?>">
                        <?php if (in_array($product_id, $wishlist_product_ids)): ?>
                        <div class="added">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <?php else: ?>
                        <div class="not-added">
                            <i class="fa-regular fa-heart"></i>
                        </div>
                        <?php endif;?>
                    </a>
                </div>
                <div class="shopg-compare-section"> <a href="#" class="compare"
                        data-product-id="<?php echo esc_attr($product_id); ?>" title="Add To Compare"><i
                            class="fa fa-refresh"></i></a>
                </div>
                <div class="quickview-section ">
                    <a class="quick-view" title="Quick View" href="#"
                        data-product-id="<?php echo esc_attr($product_id); ?>"><i class="fa-regular fa-eye"></i></a>
                </div>
            </div>

        </div>
        <div class="product-caption">
            <div class="product-identity">

                <div class="product-title popup_cart_title">
                    <a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($product_name); ?></a>
                </div>

                <div class="price-box">
                    <?php if ($sale_price): ?>
                    <span class="price-old">
                        <del>
                            <span class="money" data-currency-usd="<?php echo esc_attr($sale_price); ?>">
                                <?php echo esc_html($currency_symbol . $sale_price); ?>
                            </span>
                        </del>
                    </span>
                    <?php endif;?>
                    <span class="product-price">
                        <span class="money" data-currency-usd="<?php echo esc_attr($regular_price); ?>">
                            <?php if ($regular_price): ?>
                            <?php echo esc_html($currency_symbol . $regular_price); ?>
                            <?php endif;?>
                        </span>
                    </span>

                </div>


            </div>

        </div>
    </div>
</div>
<?php

	}

}