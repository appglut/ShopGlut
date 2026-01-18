/**
 * ShopGlut Mini Cart JavaScript
 * @package ShopGlut
 */

(function($) {
    'use strict';

    const ShopGlutMiniCart = {

        init: function() {
            this.bindEvents();
            this.initAutoClose();
        },

        bindEvents: function() {
            // Open cart on floating icon click
            $(document).on('click', '.shopglut-floating-cart', this.openCart.bind(this));

            // Open cart on menu icon click
            $(document).on('click', '.shopglut-menu-cart', this.openCart.bind(this));

            // Close cart
            $(document).on('click', '.shopglut-cart-close', this.closeCart.bind(this));
            $(document).on('click', '.shopglut-mini-cart-overlay', this.closeCart.bind(this));

            // Quantity controls
            $(document).on('click', '.shopglut-quantity-btn.plus', this.increaseQuantity.bind(this));
            $(document).on('click', '.shopglut-quantity-btn.minus', this.decreaseQuantity.bind(this));
            $(document).on('change', '.shopglut-quantity-input', this.updateQuantity.bind(this));

            // Remove item
            $(document).on('click', '.shopglut-cart-item-remove', this.removeItem.bind(this));

            // Cart share
            $(document).on('click', '.shopglut-cart-share-button', this.toggleShareForm.bind(this));
            $(document).on('click', '.shopglut-cart-share-send', this.sendCartEmail.bind(this));

            // Continue shopping
            $(document).on('click', '.shopglut-continue-shopping', this.closeCart.bind(this));

            // Listen for WooCommerce add to cart events
            $(document.body).on('added_to_cart', this.onProductAdded.bind(this));
        },

        openCart: function(e) {
            if (e) {
                e.preventDefault();
            }

            $('.shopglut-mini-cart-overlay').addClass('active');
            $('.shopglut-mini-cart-sidebar').addClass('active');
            $('body').css('overflow', 'hidden');

            // Reset auto-close timer
            this.initAutoClose();
        },

        closeCart: function(e) {
            if (e) {
                e.preventDefault();
            }

            $('.shopglut-mini-cart-overlay').removeClass('active');
            $('.shopglut-mini-cart-sidebar').removeClass('active');
            $('body').css('overflow', '');

            // Clear auto-close timer
            this.clearAutoClose();
        },

        increaseQuantity: function(e) {
            e.preventDefault();
            const $input = $(e.currentTarget).siblings('.shopglut-quantity-input');
            const currentVal = parseInt($input.val()) || 1;
            $input.val(currentVal + 1).trigger('change');
        },

        decreaseQuantity: function(e) {
            e.preventDefault();
            const $input = $(e.currentTarget).siblings('.shopglut-quantity-input');
            const currentVal = parseInt($input.val()) || 1;
            if (currentVal > 1) {
                $input.val(currentVal - 1).trigger('change');
            }
        },

        updateQuantity: function(e) {
            const $input = $(e.currentTarget);
            const cartKey = $input.data('cart-key');
            const quantity = parseInt($input.val()) || 1;

            if (quantity < 1) {
                $input.val(1);
                return;
            }

            this.updateCartItem(cartKey, quantity);
        },

        updateCartItem: function(cartKey, quantity) {
            const $cartItem = $('[data-cart-key="' + cartKey + '"]').closest('.shopglut-cart-item');

            $cartItem.addClass('shopglut-cart-loading');

            $.ajax({
                url: shopglut_mini_cart.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_minicart_update_quantity',
                    cart_key: cartKey,
                    quantity: quantity,
                    nonce: shopglut_mini_cart.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.refreshCart();
                    } else {
                        this.showNotification(response.data.message || 'Failed to update cart', 'error');
                        $cartItem.removeClass('shopglut-cart-loading');
                    }
                },
                error: () => {
                    this.showNotification('Failed to update cart', 'error');
                    $cartItem.removeClass('shopglut-cart-loading');
                }
            });
        },

        removeItem: function(e) {
            e.preventDefault();

            const $button = $(e.currentTarget);
            const cartKey = $button.data('cart-key');
            const $cartItem = $button.closest('.shopglut-cart-item');

            if (!confirm(shopglut_mini_cart.i18n.remove_confirm || 'Remove this item from cart?')) {
                return;
            }

            $cartItem.addClass('shopglut-cart-loading');

            // First, get a fresh nonce and session token
            $.ajax({
                url: shopglut_mini_cart.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_get_nonce',
                    cart_key: cartKey
                },
                success: (response) => {
                    if (response.success && response.data.nonce) {
                        // Use fresh nonce and session token for the actual delete request
                        this.removeItemWithNonce(cartKey, response.data.nonce, response.data.session_token, $cartItem);
                    } else {
                        this.showNotification('Failed to get security token', 'error');
                        $cartItem.removeClass('shopglut-cart-loading');
                    }
                },
                error: () => {
                    this.showNotification('Failed to get security token', 'error');
                    $cartItem.removeClass('shopglut-cart-loading');
                }
            });
        },

        removeItemWithNonce: function(cartKey, nonce, sessionToken, $cartItem) {
            const ajaxData = {
                action: 'shopglut_minicart_remove_item',
                cart_key: cartKey,
                nonce: nonce
            };

            // Add session token if available
            if (sessionToken) {
                ajaxData.session_token = sessionToken;
            }

            $.ajax({
                url: shopglut_mini_cart.ajax_url,
                type: 'POST',
                data: ajaxData,
                success: (response) => {
                    if (response.success) {
                        this.refreshCart();
                        this.showNotification(shopglut_mini_cart.i18n.item_removed || 'Item removed from cart', 'success');
                    } else {
                        this.showNotification(response.data.message || 'Failed to remove item', 'error');
                        $cartItem.removeClass('shopglut-cart-loading');
                    }
                },
                error: () => {
                    this.showNotification('Failed to remove item', 'error');
                    $cartItem.removeClass('shopglut-cart-loading');
                }
            });
        },

        refreshCart: function() {
            $.ajax({
                url: shopglut_mini_cart.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_get_mini_cart',
                    nonce: shopglut_mini_cart.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $('.shopglut-cart-items').html(response.data.items_html);
                        $('.shopglut-cart-footer').html(response.data.footer_html);

                        // Update cart count badges
                        const count = response.data.cart_count || 0;
                        $('.shopglut-floating-cart-badge, .shopglut-menu-cart-count').text(count);

                        if (count === 0) {
                            $('.shopglut-floating-cart-badge, .shopglut-menu-cart-count').hide();
                        } else {
                            $('.shopglut-floating-cart-badge, .shopglut-menu-cart-count').show();
                        }
                    }
                }
            });
        },

        onProductAdded: function(e, fragments, cart_hash, $button) {
            // Refresh cart content
            this.refreshCart();

            // Auto-open cart if enabled
            if (shopglut_mini_cart.auto_open === '1') {
                setTimeout(() => {
                    this.openCart();
                }, 300);
            }

            // Show notification
            this.showNotification(shopglut_mini_cart.i18n.added_to_cart || 'Product added to cart', 'success');
        },

        toggleShareForm: function(e) {
            e.preventDefault();
            // This method is no longer needed - using inline JavaScript
            console.log('Legacy toggleShareForm called - using inline JavaScript instead');
        },

        sendCartEmail: function(e) {
            e.preventDefault();

            const $button = $(e.currentTarget);
            const $form = $button.closest('.shopglut-cart-share-form');

            const senderName = $form.find('input[name="sender_name"]').val();
            const senderEmail = $form.find('input[name="sender_email"]').val();
            const recipientEmail = $form.find('input[name="recipient_email"]').val();
            const message = $form.find('textarea[name="message"]').val();

            if (!senderName || !senderEmail || !recipientEmail) {
                this.showNotification(shopglut_mini_cart.i18n.fill_required || 'Please fill all required fields', 'error');
                return;
            }

            $button.prop('disabled', true).text(shopglut_mini_cart.i18n.sending || 'Sending...');

            $.ajax({
                url: shopglut_mini_cart.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_send_cart_email',
                    sender_name: senderName,
                    sender_email: senderEmail,
                    recipient_email: recipientEmail,
                    message: message,
                    nonce: shopglut_mini_cart.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification(shopglut_mini_cart.i18n.cart_sent || 'Cart shared successfully!', 'success');
                        $form.removeClass('active');
                        $form.find('input, textarea').val('');
                    } else {
                        this.showNotification(response.data || 'Failed to send email', 'error');
                    }
                    $button.prop('disabled', false).text(shopglut_mini_cart.i18n.send_email || 'Send Email');
                },
                error: () => {
                    this.showNotification('Failed to send email', 'error');
                    $button.prop('disabled', false).text(shopglut_mini_cart.i18n.send_email || 'Send Email');
                }
            });
        },

        initAutoClose: function() {
            this.clearAutoClose();

            const autoCloseTime = parseInt(shopglut_mini_cart.auto_close_time || 0);

            if (autoCloseTime > 0) {
                this.autoCloseTimer = setTimeout(() => {
                    this.closeCart();
                }, autoCloseTime * 1000);
            }
        },

        clearAutoClose: function() {
            if (this.autoCloseTimer) {
                clearTimeout(this.autoCloseTimer);
                this.autoCloseTimer = null;
            }
        },

        showNotification: function(message, type) {
            const $notification = $('<div class="shopglut-cart-notification ' + type + '">' + message + '</div>');

            $('body').append($notification);

            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        },
    };

    // Initialize on document ready
    $(document).ready(function() {
        console.log('ShopGlut Mini Cart initializing...');
        ShopGlutMiniCart.init();
        console.log('ShopGlut Mini Cart initialized.');
    });

    // Make it globally available
    window.ShopGlutMiniCart = ShopGlutMiniCart;

})(jQuery);
