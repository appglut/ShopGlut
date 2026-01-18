<?php
/**
 * Mini Cart Template
 *
 * @package ShopGlut
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart = WC()->cart;
$cart_count = $cart->get_cart_contents_count();
$cart_items = $cart->get_cart();
$cart_total = $cart->get_cart_total();
$cart_subtotal = $cart->get_cart_subtotal();

// Get settings safely
$options = get_option('agshopglut_minicart_options', array());
$show_images = isset($options['show-product-images']) && !is_array($options['show-product-images']) ? $options['show-product-images'] : 1;
$show_quantity_controls = isset($options['enable-quantity-controls']) && !is_array($options['enable-quantity-controls']) ? $options['enable-quantity-controls'] : 1;
$show_shipping = isset($options['show-shipping-calculator']) && !is_array($options['show-shipping-calculator']) ? $options['show-shipping-calculator'] : 0;
$show_cart_share = isset($options['enable-cart-share']) && !is_array($options['enable-cart-share']) ? $options['enable-cart-share'] : 1;
$show_continue_shopping = isset($options['show-continue-shopping']) && !is_array($options['show-continue-shopping']) ? $options['show-continue-shopping'] : 1;
$position = isset($options['cart-position']) && !is_array($options['cart-position']) ? $options['cart-position'] : 'right';
$animation = isset($options['animation-style']) && !is_array($options['animation-style']) ? $options['animation-style'] : 'slide';
?>

<!-- Mini Cart Overlay -->
<div class="shopglut-mini-cart-overlay"></div>

<!-- Mini Cart Sidebar -->
<div class="shopglut-mini-cart-sidebar position-<?php echo esc_attr($position); ?> animation-<?php echo esc_attr($animation); ?>">

    <!-- Cart Header -->
    <div class="shopglut-cart-header">
        <h3><?php echo esc_html__('Shopping Cart', 'shopglut'); ?> (<span class="shopglut-cart-count"><?php echo esc_html($cart_count); ?></span>)</h3>
        <button class="shopglut-cart-close" aria-label="<?php echo esc_attr__('Close cart', 'shopglut'); ?>">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <!-- Cart Items -->
    <div class="shopglut-cart-items">
        <?php if (empty($cart_items)) : ?>
            <div class="shopglut-cart-empty">
                <i class="fa-solid fa-shopping-cart"></i>
                <p><?php echo esc_html__('Your cart is empty', 'shopglut'); ?></p>
            </div>
        <?php else : ?>
            <?php foreach ($cart_items as $cart_item_key => $cart_item) :
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>

                    <div class="shopglut-cart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">

                        <?php if ($show_images) : ?>
                            <div class="shopglut-cart-item-image">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                if (!$product_permalink) {
                                    echo wp_kses_post($thumbnail); // WooCommerce thumbnail is filtered and safe
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), wp_kses_post($thumbnail)); // WooCommerce thumbnail is filtered and safe
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="shopglut-cart-item-details">
                            <h4 class="shopglut-cart-item-name">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }
                                ?>
                            </h4>

                            <div class="shopglut-cart-item-price">
                                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); // WooCommerce price is filtered and safe ?>
                            </div>

                            <?php if ($show_quantity_controls) : ?>
                                <div class="shopglut-cart-item-quantity">
                                    <div class="shopglut-quantity-controls">
                                        <button class="shopglut-quantity-btn minus" type="button">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input type="number"
                                               class="shopglut-quantity-input"
                                               value="<?php echo esc_attr($cart_item['quantity']); ?>"
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
                                    echo esc_html(sprintf(__('Qty: %s', 'shopglut'), $cart_item['quantity']));
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

                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Cart Footer -->
    <?php if (!empty($cart_items)) : ?>
        <div class="shopglut-cart-footer">

            <?php if ($show_shipping) : ?>
                <div class="shopglut-shipping-calculator">
                    <h4><?php echo esc_html__('Calculate Shipping', 'shopglut'); ?></h4>
                    <!-- Shipping calculator fields would go here -->
                </div>
            <?php endif; ?>

            <div class="shopglut-cart-subtotal">
                <span class="shopglut-cart-subtotal-label"><?php echo esc_html__('Subtotal:', 'shopglut'); ?></span>
                <span class="shopglut-cart-subtotal-amount"><?php echo wp_kses_post($cart_subtotal); // WooCommerce subtotal is filtered and safe ?></span>
            </div>

            <div class="shopglut-cart-buttons">
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="shopglut-checkout-button shopglut-cart-button-primary">
                    <?php echo esc_html__('Proceed to Checkout', 'shopglut'); ?>
                </a>

                <?php if ($show_continue_shopping) : ?>
                    <button class="shopglut-cart-button-secondary shopglut-continue-shopping">
                        <?php echo esc_html__('Continue Shopping', 'shopglut'); ?>
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($show_cart_share) : ?>
                <div class="shopglut-cart-share">
                    <button type="button" class="shopglut-cart-share-button" onclick="openShareModal()">
                        <i class="fa-solid fa-share-nodes"></i>
                        <span><?php echo esc_html__('Share Cart', 'shopglut'); ?></span>
                    </button>
                </div>

                <!-- Share Cart Modal -->
                <div id="share-cart-modal" style="display: none; position: fixed; z-index: 9999999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
                    <div style="position: relative; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">

                        <!-- Modal Header -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                            <h3 style="margin: 0; color: #333; font-size: 20px;"><?php echo esc_html__('Share Your Cart', 'shopglut'); ?></h3>
                            <button onclick="closeShareModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666; padding: 0; line-height: 1;">&times;</button>
                        </div>

                        <!-- Modal Content -->
                        <div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555;"><?php echo esc_html__('Your Name*', 'shopglut'); ?></label>
                                <input type="text" id="share-name" placeholder="<?php echo esc_attr__('Enter your name', 'shopglut'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555;"><?php echo esc_html__('Your Email*', 'shopglut'); ?></label>
                                <input type="email" id="share-email" placeholder="<?php echo esc_attr__('your@email.com', 'shopglut'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555;"><?php echo esc_html__('Recipient Email*', 'shopglut'); ?></label>
                                <input type="email" id="share-recipient" placeholder="<?php echo esc_attr__('recipient@email.com', 'shopglut'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555;"><?php echo esc_html__('Message', 'shopglut'); ?></label>
                                <textarea id="share-message" placeholder="<?php echo esc_attr__('Add a personal message...', 'shopglut'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; min-height: 100px; resize: vertical;"></textarea>
                            </div>

                            <!-- Modal Footer -->
                            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px;">
                                <button onclick="closeShareModal()" style="padding: 10px 20px; border: 1px solid #ddd; background: white; color: #666; border-radius: 4px; cursor: pointer; font-size: 14px;"><?php echo esc_html__('Cancel', 'shopglut'); ?></button>
                                <button onclick="sendCartShare()" style="padding: 10px 25px; border: none; background: #2271b1; color: white; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600;"><?php echo esc_html__('Send Email', 'shopglut'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                function openShareModal() {
                    document.getElementById('share-cart-modal').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }

                function closeShareModal() {
                    document.getElementById('share-cart-modal').style.display = 'none';
                    document.body.style.overflow = '';
                    // Clear form
                    document.getElementById('share-name').value = '';
                    document.getElementById('share-email').value = '';
                    document.getElementById('share-recipient').value = '';
                    document.getElementById('share-message').value = '';
                }

                function sendCartShare() {
                    const name = document.getElementById('share-name').value.trim();
                    const email = document.getElementById('share-email').value.trim();
                    const recipient = document.getElementById('share-recipient').value.trim();
                    const message = document.getElementById('share-message').value.trim();

                    if (!name || !email || !recipient) {
                        alert('Please fill all required fields');
                        return;
                    }

                    if (!validateEmail(email) || !validateEmail(recipient)) {
                        alert('<?php echo esc_js('Please enter valid email addresses'); ?>');
                        return;
                    }

                    // Show loading state
                    const sendButton = event.target;
                    const originalText = sendButton.innerHTML;
                    sendButton.innerHTML = '<?php echo esc_js('Sending...'); ?>';
                    sendButton.disabled = true;

                    // AJAX call
                    fetch('<?php echo esc_url_raw(admin_url('admin-ajax.php')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=shopglut_send_cart_email&sender_name=' + encodeURIComponent(name) + '&sender_email=' + encodeURIComponent(email) + '&recipient_email=' + encodeURIComponent(recipient) + '&message=' + encodeURIComponent(message) + '&nonce=<?php echo esc_attr(wp_create_nonce('shopglut_mini_cart_nonce')); ?>'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('<?php echo esc_js('Cart shared successfully!'); ?>');
                            closeShareModal();
                        } else {
                            alert(data.data || '<?php echo esc_js('Failed to send email'); ?>');
                        }
                    })
                    .catch(error => {
                        alert('<?php echo esc_js('Error: '); ?>' + error.message);
                    })
                    .finally(() => {
                        sendButton.innerHTML = originalText;
                        sendButton.disabled = false;
                    });
                }

                function validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                }

                // Close modal when clicking outside
                document.getElementById('share-cart-modal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeShareModal();
                    }
                });
                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
