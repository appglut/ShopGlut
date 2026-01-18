<?php
/**
 * Mini Cart Item Template
 *
 * @package ShopGlut
 */

if (!defined('ABSPATH')) {
    exit;
}

$product_name = $product->get_name();
$product_price = wc_price($product->get_price());
$product_image = $product->get_image('thumbnail');
$product_permalink = $product->get_permalink();
$item_total = wc_price($product->get_price() * $quantity);
?>

<div class="shopglut-cart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
    <?php if ($settings['show_product_images']) : ?>
        <div class="shopglut-cart-item-image">
            <a href="<?php echo esc_url($product_permalink); ?>">
                <?php echo wp_kses_post($product_image); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="shopglut-cart-item-details">
        <h4 class="shopglut-cart-item-name">
            <a href="<?php echo esc_url($product_permalink); ?>">
                <?php echo esc_html($product_name); ?>
            </a>
        </h4>

        <div class="shopglut-cart-item-price">
            <?php echo wp_kses_post($product_price); ?>
        </div>

        <?php if ($settings['enable_quantity_controls']) : ?>
            <div class="shopglut-cart-item-quantity">
                <div class="shopglut-quantity-controls">
                    <button class="shopglut-quantity-btn minus" type="button">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <input type="number"
                           class="shopglut-quantity-input"
                           value="<?php echo esc_attr($quantity); ?>"
                           min="1"
                           data-cart-key="<?php echo esc_attr($cart_item_key); ?>" />
                    <button class="shopglut-quantity-btn plus" type="button">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
        <?php else : ?>
            <div class="shopglut-cart-item-quantity-text">
                <?php
                /* translators: %s: Quantity of the cart item */
                echo esc_html(sprintf(__('Qty: %s', 'shopglut'), $quantity));
                ?>
            </div>
        <?php endif; ?>
    </div>

    <button class="shopglut-cart-item-remove"
            data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
            aria-label="<?php echo esc_attr__('Remove item', 'shopglut'); ?>">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>
