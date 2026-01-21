/**
 * Shopglut Cart Template1 Functionality
 * Handles AJAX cart operations for the template1 layout
 */

(function($) {
    'use strict';

    class ShopglutCartTemplate1 {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.setupLoadingStates();
        }

        bindEvents() {
            // Quantity controls
            $(document).on('click', '.shopglut-cart.template1 .qty-decrease', this.decreaseQuantity.bind(this));
            $(document).on('click', '.shopglut-cart.template1 .qty-increase', this.increaseQuantity.bind(this));
            $(document).on('change', '.shopglut-cart.template1 .qty-input', this.updateQuantity.bind(this));

            // Remove item
            $(document).on('click', '.shopglut-cart.template1 .remove-btn', this.removeItem.bind(this));

            // Coupon functionality
            $(document).on('submit', '.shopglut-cart.template1 #shopglut-coupon-form', this.applyCoupon.bind(this));
            $(document).on('click', '.shopglut-cart.template1 .remove-coupon', this.removeCoupon.bind(this));

            // Checkout
            $(document).on('click', '.shopglut-cart.template1 #proceed-to-checkout', this.proceedToCheckout.bind(this));

            // Update cart fragments (WooCommerce native)
            $(document.body).on('updated_wc_div', this.onCartUpdated.bind(this));
        }

        setupLoadingStates() {
            this.loadingClass = 'shopglut-loading';
            this.disabledClass = 'shopglut-disabled';
        }

        /**
         * Decrease quantity
         */
        decreaseQuantity(e) {
            e.preventDefault();
            const button = $(e.currentTarget);
            const cartKey = button.data('cart-key');
            const qtyControl = button.closest('.qty-control');
            const qtyInput = qtyControl.find('.qty-input');
            const currentQty = parseInt(qtyInput.val()) || 1;

            if (currentQty > 1) {
                // Update the input immediately for visual feedback
                qtyInput.val(currentQty - 1);
                this.updateCartQuantity(cartKey, currentQty - 1, qtyInput);
            }
        }

        /**
         * Increase quantity
         */
        increaseQuantity(e) {
            e.preventDefault();
            const button = $(e.currentTarget);
            const cartKey = button.data('cart-key');
            const qtyControl = button.closest('.qty-control');
            const qtyInput = qtyControl.find('.qty-input');
            const currentQty = parseInt(qtyInput.val()) || 1;
            const maxQty = parseInt(qtyInput.attr('max')) || 999;

            if (currentQty < maxQty) {
                // Update the input immediately for visual feedback
                qtyInput.val(currentQty + 1);
                this.updateCartQuantity(cartKey, currentQty + 1, qtyInput);
            }
        }

        /**
         * Update quantity from input
         */
        updateQuantity(e) {
            const qtyInput = $(e.currentTarget);
            const cartKey = qtyInput.data('cart-key');
            const newQty = parseInt(qtyInput.val());
            const minQty = parseInt(qtyInput.attr('min')) || 1;
            const maxQty = parseInt(qtyInput.attr('max')) || 999;

            // Validate quantity
            if (newQty < minQty) {
                qtyInput.val(minQty);
                return;
            }
            if (newQty > maxQty) {
                qtyInput.val(maxQty);
                return;
            }

            this.updateCartQuantity(cartKey, newQty, qtyInput);
        }

        /**
         * Update cart quantity via AJAX
         */
        updateCartQuantity(cartKey, quantity, qtyInput) {
            const cartItem = qtyInput.closest('.cart-item');

            // Store original value for rollback
            if (!qtyInput.data('original-value')) {
                qtyInput.data('original-value', qtyInput.val());
            }

            this.setLoading(cartItem, true);

            $.ajax({
                url: shopglut_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_update_cart_quantity',
                    cart_item_key: cartKey,
                    quantity: quantity,
                    nonce: shopglut_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        if (response.data && response.data.fragments) {
                            this.updateCartFragments(response.data.fragments);
                        }
                        // Update the line total
                        this.updateLineTotal(cartItem, quantity);
                        // Update order summary
                        this.updateOrderSummary();

                        // Show success message
                        const message = (response.data && response.data.message) ?
                            response.data.message : shopglut_cart_ajax.i18n.updating;
                        this.showMessage(message, 'success');

                        // Update original value
                        qtyInput.data('original-value', quantity);
                    } else {
                        const errorMessage = (response.data && response.data.message) ?
                            response.data.message : shopglut_cart_ajax.i18n.error;
                        this.showMessage(errorMessage, 'error');
                        // Revert quantity to original value
                        const originalValue = qtyInput.data('original-value') || 1;
                        qtyInput.val(originalValue);
                    }
                },
                error: () => {
                    this.showMessage(shopglut_cart_ajax.i18n.error, 'error');
                    // Revert quantity to original value
                    const originalValue = qtyInput.data('original-value') || 1;
                    qtyInput.val(originalValue);
                },
                complete: () => {
                    this.setLoading(cartItem, false);
                }
            });
        }

        /**
         * Remove item from cart
         */
        removeItem(e) {
            e.preventDefault();
            const button = $(e.currentTarget);
            const cartKey = button.data('cart-key');
            const cartItem = button.closest('.cart-item');

            if (!confirm(shopglut_cart_ajax.i18n.confirm_remove || 'Are you sure you want to remove this item?')) {
                return;
            }

            this.setLoading(cartItem, true);

            $.ajax({
                url: shopglut_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_remove_cart_item',
                    cart_item_key: cartKey,
                    nonce: shopglut_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        if (response.data && response.data.fragments) {
                            this.updateCartFragments(response.data.fragments);
                        }
                        cartItem.fadeOut(300, function() {
                            $(this).remove();
                            // Check if cart is empty
                            if ($('.cart-item').length === 0) {
                                location.reload(); // Reload to show empty cart state
                            }
                        });

                        const message = (response.data && response.data.message) ?
                            response.data.message : shopglut_cart_ajax.i18n.removing;
                        this.showMessage(message, 'success');
                    } else {
                        const errorMessage = (response.data && response.data.message) ?
                            response.data.message : shopglut_cart_ajax.i18n.error;
                        this.showMessage(errorMessage, 'error');
                        this.setLoading(cartItem, false);
                    }
                },
                error: () => {
                    this.showMessage(shopglut_cart_ajax.i18n.error, 'error');
                    this.setLoading(cartItem, false);
                }
            });
        }

        /**
         * Apply coupon
         */
        applyCoupon(e) {
            e.preventDefault();
            const form = $(e.currentTarget);
            const couponCode = form.find('#couponCode').val().trim();
            const messageContainer = form.find('#couponMessage');

            if (!couponCode) {
                this.showCouponMessage(messageContainer, 'Please enter a coupon code.', 'error');
                return;
            }

            const submitButton = form.find('.apply-btn');
            const originalText = submitButton.text();

            submitButton.text(shopglut_cart_ajax.i18n.updating).prop('disabled', true);

            $.ajax({
                url: shopglut_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_apply_coupon',
                    coupon_code: couponCode,
                    nonce: shopglut_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showCouponMessage(messageContainer, response.data.message || shopglut_cart_ajax.i18n.coupon_applied, 'success');
                        form.find('#couponCode').val('');

                        if (response.data && response.data.fragments) {
                            this.updateCartFragments(response.data.fragments);
                        }

                        // Reload cart to show applied coupon
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        this.showCouponMessage(messageContainer, response.data.message || shopglut_cart_ajax.i18n.invalid_coupon, 'error');
                    }
                },
                error: () => {
                    this.showCouponMessage(messageContainer, shopglut_cart_ajax.i18n.error, 'error');
                },
                complete: () => {
                    submitButton.text(originalText).prop('disabled', false);
                }
            });
        }

        /**
         * Remove coupon
         */
        removeCoupon(e) {
            e.preventDefault();
            const button = $(e.currentTarget);
            const couponCode = button.data('coupon');
            const couponElement = button.closest('.applied-coupon');

            this.setLoading(couponElement, true);

            $.ajax({
                url: shopglut_cart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_remove_coupon',
                    coupon_code: couponCode,
                    nonce: shopglut_cart_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        if (response.data && response.data.fragments) {
                            this.updateCartFragments(response.data.fragments);
                        }
                        couponElement.fadeOut(300, function() {
                            $(this).remove();
                        });
                        this.showMessage(response.data.message || shopglut_cart_ajax.i18n.coupon_removed, 'success');
                    } else {
                        this.showMessage(response.data.message || shopglut_cart_ajax.i18n.error, 'error');
                        this.setLoading(couponElement, false);
                    }
                },
                error: () => {
                    this.showMessage(shopglut_cart_ajax.i18n.error, 'error');
                    this.setLoading(couponElement, false);
                }
            });
        }

        /**
         * Proceed to checkout
         */
        proceedToCheckout(e) {
            e.preventDefault();
            window.location.href = shopglut_cart_ajax.checkout_url;
        }

        /**
         * Show loading state
         */
        setLoading(element, isLoading) {
            if (isLoading) {
                element.addClass(this.loadingClass);
                element.find('button, input').addClass(this.disabledClass).prop('disabled', true);
            } else {
                element.removeClass(this.loadingClass);
                element.find('button, input').removeClass(this.disabledClass).prop('disabled', false);
            }
        }

        /**
         * Show message
         */
        showMessage(message, type = 'info') {
            // Create or update message container
            let messageContainer = $('.shopglut-cart-message');
            if (messageContainer.length === 0) {
                messageContainer = $('<div class="shopglut-cart-message"></div>');
                $('.shopglut-cart.template1').prepend(messageContainer);
            }

            messageContainer
                .removeClass('success error info')
                .addClass(type)
                .text(message)
                .fadeIn();

            // Auto hide after 3 seconds
            setTimeout(() => {
                messageContainer.fadeOut();
            }, 3000);
        }

        /**
         * Show coupon message
         */
        showCouponMessage(container, message, type) {
            container
                .removeClass('success error')
                .addClass(type)
                .text(message)
                .show();

            // Auto hide after 5 seconds
            setTimeout(() => {
                container.fadeOut();
            }, 5000);
        }

        /**
         * Update cart fragments
         */
        updateCartFragments(fragments) {
            $.each(fragments, function(key, value) {
                $(key).replaceWith(value);
            });

            // Trigger cart updated event
            $(document.body).trigger('wc_fragment_refresh');
        }

        /**
         * Update line total for a cart item
         */
        updateLineTotal(cartItem, quantity) {
            const priceCell = cartItem.find('.price-cell').first();
            const priceText = priceCell.text();

            // Extract numeric value from price (handles currency symbols)
            const priceMatch = priceText.match(/[\d,]+\.?\d*/);
            if (!priceMatch) return;

            const price = parseFloat(priceMatch[0].replace(/,/g, ''));
            const total = price * quantity;

            // Get currency symbol and position
            const currencySymbol = priceText.replace(/[\d,.\s]/g, '').trim();
            const isSymbolBefore = priceText.indexOf(currencySymbol) < priceText.indexOf(priceMatch[0]);

            // Format the new total
            const formattedTotal = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const newTotal = isSymbolBefore ?
                currencySymbol + formattedTotal :
                formattedTotal + currencySymbol;

            cartItem.find('.item-total').text(newTotal);
        }

        /**
         * Update order summary totals
         */
        updateOrderSummary() {
            let subtotal = 0;

            // Calculate subtotal from all cart items
            $('.cart-item').each(function() {
                const itemTotal = $(this).find('.item-total').text();
                const priceMatch = itemTotal.match(/[\d,]+\.?\d*/);
                if (priceMatch) {
                    subtotal += parseFloat(priceMatch[0].replace(/,/g, ''));
                }
            });

            // Update subtotal display
            const subtotalElement = $('#subtotal, .summary-row .value').first();
            if (subtotalElement.length) {
                const currencySymbol = subtotalElement.text().replace(/[\d,.\s]/g, '').trim();
                const isSymbolBefore = subtotalElement.text().indexOf(currencySymbol) < subtotalElement.text().search(/\d/);

                const formattedSubtotal = subtotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const newSubtotal = isSymbolBefore ?
                    currencySymbol + formattedSubtotal :
                    formattedSubtotal + currencySymbol;

                subtotalElement.text(newSubtotal);
            }

            // Update total (subtotal + shipping + tax - discount)
            this.updateFinalTotal(subtotal);
        }

        /**
         * Update final total calculation
         */
        updateFinalTotal(subtotal) {
            let shipping = 0;
            let tax = 0;
            let discount = 0;

            // Get shipping amount
            const shippingElement = $('#shipping');
            if (shippingElement.length) {
                const shippingMatch = shippingElement.text().match(/[\d,]+\.?\d*/);
                if (shippingMatch) {
                    shipping = parseFloat(shippingMatch[0].replace(/,/g, ''));
                }
            }

            // Get tax amount
            const taxElement = $('#tax');
            if (taxElement.length) {
                const taxMatch = taxElement.text().match(/[\d,]+\.?\d*/);
                if (taxMatch) {
                    tax = parseFloat(taxMatch[0].replace(/,/g, ''));
                }
            }

            // Get discount amount
            const discountElement = $('#discount');
            if (discountElement.length && discountElement.is(':visible')) {
                const discountMatch = discountElement.text().match(/[\d,]+\.?\d*/);
                if (discountMatch) {
                    discount = parseFloat(discountMatch[0].replace(/,/g, ''));
                }
            }

            // Calculate final total
            const total = subtotal + shipping + tax - discount;

            // Update total display
            const totalElement = $('#total, .total-row .value').last();
            if (totalElement.length) {
                const currencySymbol = totalElement.text().replace(/[\d,.\s]/g, '').trim();
                const isSymbolBefore = totalElement.text().indexOf(currencySymbol) < totalElement.text().search(/\d/);

                const formattedTotal = total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const newTotal = isSymbolBefore ?
                    currencySymbol + formattedTotal :
                    formattedTotal + currencySymbol;

                totalElement.text(newTotal);
            }
        }

        /**
         * Handle cart updated event
         */
        onCartUpdated() {
            // Re-initialize any new elements
            this.bindEvents();
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        const cartTemplate = new ShopglutCartTemplate1();

        // Store original quantities for all inputs
        $('.shopglut-cart.template1 .qty-input').each(function() {
            const input = $(this);
            input.data('original-value', input.val());
        });
    });

})(jQuery);