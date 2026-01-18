<?php
namespace Shopglut\layouts\shopLayout\templates;

if (!defined('ABSPATH')) {
	exit;
}
class template1 {

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

		// Fetch wishlist product IDs from wishlist module's table (NO CACHE)
		global $wpdb;
		$wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
		$user_id = get_current_user_id();

		// Get wishlist instance for guest ID if needed
		$wishlist_instance = null;
		if (class_exists('Shopglut\\enhancements\\wishlist\\dataManage')) {
			$wishlist_instance = \Shopglut\enhancements\wishlist\dataManage::get_instance();
		}

		// Determine user ID (logged in user or guest)
		if (!$user_id && $wishlist_instance && method_exists($wishlist_instance, 'get_shopglutw_guest_user_id')) {
			$user_id = $wishlist_instance->get_shopglutw_guest_user_id();
		}

		$wishlist_product_ids = array();

		if ($user_id) {
			// Suppress WordPress object cache for this query
			$suppress = $wpdb->suppress_errors();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table name is safe, dynamically constructed
			$wishlist_row = $wpdb->get_row($wpdb->prepare(
				"SELECT product_ids FROM `" . esc_sql($wishlist_table) . "` WHERE wish_user_id = %s",
				$user_id
			), OBJECT, 0); // OBJECT mode with 0 offset to bypass cache

			$wpdb->suppress_errors($suppress);

			if ($wishlist_row && !empty($wishlist_row->product_ids)) {
				// Parse comma-separated product IDs (NO CACHE - always fresh from DB)
				$wishlist_product_ids = array_filter(array_map('intval', array_map('trim', explode(',', $wishlist_row->product_ids))));
			}
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

		// Extract module settings from layout configuration
		$accordion_settings = isset($template_data['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion'])
			? $template_data['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']
			: array();

		// DEBUG: Show what's in accordion settings
		echo '<!-- DEBUG Accordion Settings Keys: ';
		if (!empty($accordion_settings)) {
			echo esc_html(implode(', ', array_map('sanitize_text_field', array_keys($accordion_settings))));
		} else {
			echo 'EMPTY';
		}
		echo ' -->';

		// Settings are stored directly in accordion_settings (flattened structure)
		// Wishlist settings
		$wishlist_enabled = isset($accordion_settings['enable_wishlist']) ? $accordion_settings['enable_wishlist'] : true;
		$wishlist_add_icon = isset($accordion_settings['wishlist_add_icon']) ? $accordion_settings['wishlist_add_icon'] : 'fa-regular fa-heart';
		$wishlist_added_icon = isset($accordion_settings['wishlist_added_icon']) ? $accordion_settings['wishlist_added_icon'] : 'fa-solid fa-heart';
		$wishlist_icon_color = isset($accordion_settings['wishlist_icon_color']) ? $accordion_settings['wishlist_icon_color'] : '';
		$wishlist_bg_color = isset($accordion_settings['wishlist_bg_color']) ? $accordion_settings['wishlist_bg_color'] : '';

		// Comparison settings
		$comparison_enabled = isset($accordion_settings['enable_comparison']) ? $accordion_settings['enable_comparison'] : true;
		$comparison_icon = isset($accordion_settings['comparison_icon']) ? $accordion_settings['comparison_icon'] : 'fa fa-refresh';
		$comparison_added_icon = isset($accordion_settings['comparison_added_icon']) ? $accordion_settings['comparison_added_icon'] : 'fa fa-check-circle';
		$comparison_icon_color = isset($accordion_settings['comparison_icon_color']) ? $accordion_settings['comparison_icon_color'] : '';
		$comparison_bg_color = isset($accordion_settings['comparison_bg_color']) ? $accordion_settings['comparison_bg_color'] : '';

		// Quick View settings
		$quickview_enabled = isset($accordion_settings['enable_quickview']) ? $accordion_settings['enable_quickview'] : true;
		$quickview_icon = isset($accordion_settings['quickview_icon']) ? $accordion_settings['quickview_icon'] : 'fa-regular fa-eye';
		$quickview_icon_color = isset($accordion_settings['quickview_icon_color']) ? $accordion_settings['quickview_icon_color'] : '';
		$quickview_bg_color = isset($accordion_settings['quickview_bg_color']) ? $accordion_settings['quickview_bg_color'] : '';

		?>

        <div class="product-design product-template1" <?php if (!empty($product_id)): ?>
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
                        <?php if ($wishlist_enabled): ?>
                        <div class="shopg-wishlist-section">
                            <?php
                            $wishlist_style = '';
                            if ($wishlist_bg_color) {
                                $wishlist_style .= 'background-color: ' . esc_attr($wishlist_bg_color) . ';';
                            }
                            if ($wishlist_icon_color) {
                                $wishlist_style .= 'color: ' . esc_attr($wishlist_icon_color) . ';';
                            }
                            ?>
                            <a title="Wishlist" style="<?php echo esc_attr($wishlist_style); ?>"
                                <?php if ($wishlist_options['wishlist-require-login'] == true && !is_user_logged_in()): ?>
                                href="<?php echo esc_url(wp_login_url(site_url(isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : ''))); ?>" <?php else: ?>
                                class="wishlist" href="#"
                                data-product-id="<?php echo esc_attr($product_id); ?>"
                                data-add-icon="<?php echo esc_attr($wishlist_add_icon); ?>"
                                data-added-icon="<?php echo esc_attr($wishlist_added_icon); ?>" <?php endif;?>>
                                <?php if ($wishlist_options['wishlist-require-login'] == true && !is_user_logged_in()): ?>
                                <div class="locked">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <?php else: ?>
                                <?php if (in_array($product_id, $wishlist_product_ids)): ?>
                                <div class="added">
                                    <i class="<?php echo esc_attr($wishlist_added_icon); ?>"></i>
                                </div>
                                <?php else: ?>
                                <div class="not-added">
                                    <i class="<?php echo esc_attr($wishlist_add_icon); ?>"></i>
                                </div>
                                <?php endif;?>

                                <?php endif;?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if ($comparison_enabled): ?>
                        <div class="shopg-compare-section">
                            <?php
                            $comparison_style = '';
                            if ($comparison_bg_color) {
                                $comparison_style .= 'background-color: ' . esc_attr($comparison_bg_color) . ';';
                            }
                            if ($comparison_icon_color) {
                                $comparison_style .= 'color: ' . esc_attr($comparison_icon_color) . ';';
                            }
                            ?>
                            <a href="#" class="shopglut-add-to-comparison compare"
                                style="<?php echo esc_attr($comparison_style); ?>"
                                data-product-id="<?php echo esc_attr($product_id); ?>"
                                data-add-icon="<?php echo esc_attr($comparison_icon); ?>"
                                data-added-icon="<?php echo esc_attr($comparison_added_icon); ?>"
                                title="Compare">
                                <div class="not-added">
                                    <i class="<?php echo esc_attr($comparison_icon); ?>"></i>
                                </div>
                                <div class="added" style="display: none;">
                                    <i class="<?php echo esc_attr($comparison_added_icon); ?>"></i>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if ($quickview_enabled): ?>
                        <div class="quickview-section">
                            <?php
                            $quickview_style = '';
                            if ($quickview_bg_color) {
                                $quickview_style .= 'background-color: ' . esc_attr($quickview_bg_color) . ';';
                            }
                            if ($quickview_icon_color) {
                                $quickview_style .= 'color: ' . esc_attr($quickview_icon_color) . ';';
                            }
                            ?>
                            <a class="quick-view" title="Quick View" href="#" style="<?php echo esc_attr($quickview_style); ?>"
                                data-product-id="<?php echo esc_attr($product_id); ?>">
                                <i class="<?php echo esc_attr($quickview_icon); ?>"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-cart">
                        <div class="product-cart-action">
                            <?php if ( $product_type === 'simple' ) : ?>
                            <?php if ( $is_in_cart ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="add-to-cart btn btn-cart">
                                <span class="cart-title">
                                    <i class="fa fa-check-square"></i><?php echo esc_html__( ' View Cart', 'shopglut' ); ?>
                                </span>
                            </a>
                            <?php else : ?>
                            <a href="#" data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                class="add-to-cart ajax-spin-cart btn btn-cart">
                                <span>
                                    <span class="cart-contents">
                                        <i class="fa fa-shopping-cart"></i><span class="cart-title">
                                            <?php echo esc_html__( 'Add to Cart', 'shopglut' ); ?>
                                        </span>
                                    </span>
                                    <span class="cart-loading" style="display: none;">
                                        <i class="fa fa-spinner animated rotateIn infinite"></i>
                                        <?php echo esc_html__( 'Wait..', 'shopglut' ); ?>
                                    </span>
                                    <span class="cart-added" style="display: none;">
                                        <i class="fa fa-check-square"></i> <?php echo esc_html__( ' View cart', 'shopglut' ); ?>
                                    </span>
                                    <span class="cart-unavailable" style="display: none;">
                                        <i class="fa fa-shopping-cart"></i>
                                        <?php echo esc_html__( 'Unavailable', 'shopglut' ); ?>
                                    </span>
                                </span>
                            </a>
                            <?php endif; ?>
                            <?php elseif ( $product_type === 'external' ) : ?>
                            <a href="<?php echo esc_url( $product->get_product_url() ); ?>"
                                class="external-product btn btn-cart" target="_blank">
                                <span class="cart-title">
                                    <i
                                        class="fa fa-external-link"></i><?php echo esc_html__( ' External Product', 'shopglut' ); ?>
                                </span>
                            </a>
                            <?php elseif ( $product_type === 'grouped' ) : ?>
                            <a href="<?php echo esc_url( $product_link ); ?>" class="grouped-product btn btn-cart">
                                <span class="cart-title">
                                    <i class="fa fa-cubes"></i><?php echo esc_html__( ' Grouped Product', 'shopglut' ); ?>
                                </span>
                            </a>
                            <?php elseif ( $product_type === 'variable' ) : ?>
                                <a href="<?php echo esc_url( $product_link ); ?>" class="variable-product btn btn-cart">
                                    <span class="cart-title">
                                        <i class="fa-solid fa-sitemap"></i><?php echo esc_html__( ' Variable Product', 'shopglut' ); ?>
                                </span>
                            </a>
                            <?php else : ?>
                            <div class="add-to-cart not-allowed-purchase btn btn-cart">
                                <span class="cart-title">
                                    <i class="fa fa-ban"></i>
                                    <?php echo esc_html__( ' Purchase not allowed', 'shopglut' ); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <div class="product-caption">
                    <div class="product-identity">
                        <p class="product-category">
                            <?php foreach ($categories as $index => $category_name): ?>
                            <a href="<?php echo esc_url(get_term_link($category_name, 'product_cat')); ?>">
                                <?php echo esc_html($category_name); ?>
                            </a>
                            <?php if ($index < count($categories) - 1): ?>,<?php endif;?>
                            <?php endforeach;?>
                        </p>
                        <div class="ratings">
                            <span class="spr-badge" id="spr_badge_<?php echo esc_attr($product_id); ?>"
                                data-rating="<?php echo esc_attr($average_rating); ?>">
                                <span class="spr-starrating spr-badge-starrating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa <?php echo $i <= $average_rating ? 'fa-solid fa-star' : 'fa-regular fa-star'; ?>"
                                        aria-hidden="true"></i>
                                    <?php endfor;?>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="product-identity">
                        <p class="product-title popup_cart_title">
                            <a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($product_name); ?></a>
                        </p>
                        <div class="price-box">
                            <span class="product-price">
                                <span class="money" data-currency-usd="<?php echo esc_attr($regular_price); ?>">
                                    <?php if ($regular_price): ?>
                                    <?php echo esc_html($currency_symbol . $regular_price); ?>
                                    <?php endif;?>
                                </span>
                            </span>
                            <?php if ($sale_price): ?>
                            <span class="price-old">
                                <del>
                                    <span class="money" data-currency-usd="<?php echo esc_attr($sale_price); ?>">
                                        <?php echo esc_html($currency_symbol . $sale_price); ?>
                                    </span>
                                </del>
                            </span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   <?php

	}

}