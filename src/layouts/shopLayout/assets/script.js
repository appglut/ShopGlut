/**
 * ShopGlut Shop Layout - Frontend Script
 * Handles Add to Cart, Wishlist, Quick View, and Comparison functionality
 */

(function($) {
    'use strict';

    var ShopGlutShopFrontend = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;

            // Add to Cart (exclude: view-cart-link, href[cart], already in cart links)
            $(document).on('click', '.shopg-add-to-cart, .add-to-cart-button, .ajax-spin-cart:not(.view-cart-link):not([href*="cart"]):not([href*="wc-cart"])', function(e) {
                e.preventDefault();
                self.addToCart($(this));
            });

            // Handle existing View Cart links (products already in cart)
            $(document).on('click', '.add-to-cart[href*="cart"], .add-to-cart[href*="wc-cart"]', function(e) {
                // Don't prevent default - let the link work naturally
                // Open in new tab for consistency
                var href = $(this).attr('href');
                if (href && href !== '#') {
                    e.preventDefault();
                    window.open(href, '_blank');
                }
            });

            // View Cart links will be handled by the existing cart links handler above

            // Wishlist - match template classes - use wishlist module's toggle
            $(document).on('click', '.wishlist, .shopglut-add-to-wishlist, .shopg-add-to-wishlist', function(e) {
                e.preventDefault();
                console.log('Wishlist button clicked!', $(this));
                self.toggleWishlist($(this));
            });

            // Quick View - use Quick View module
            $(document).on('click', '.quick-view', function(e) {
                e.preventDefault();
                var $button = $(this);
                var productId = $button.data('product-id');

                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }

                // Call Quick View module function if available
                if (window.ShopGlutQuickView && typeof window.ShopGlutQuickView.loadQuickView === 'function') {
                    window.ShopGlutQuickView.loadQuickView(productId, $button);
                } else {
                    console.error('Quick View module not loaded');
                }
            });

            // Comparison - handle template1 icon-based buttons
            $(document).on('click', '.shopglut-add-to-comparison.compare', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var $button = $(this);
                var productId = parseInt($button.data('product-id'));

                if (!productId) {
                    return;
                }

                // Get current comparison products
                var comparisonProducts = self.loadComparisonProducts();
                var isInComparison = comparisonProducts.includes(productId);

                // Toggle product in comparison
                if (isInComparison) {
                    comparisonProducts = comparisonProducts.filter(function(id) { return id !== productId; });
                } else {
                    comparisonProducts.push(productId);
                }

                // Save to localStorage
                localStorage.setItem('shopglut_comparison_products', JSON.stringify(comparisonProducts));

                // Update icon
                self.updateCompareIcon($button, !isInComparison);

                // Trigger floating bar update
                if (window.shopglutUpdateComparisonUI) {
                    window.shopglutUpdateComparisonUI();
                }
            });

            // Sync comparison icons on page load
            self.syncCompareIcons();

            // Listen for removal from floating bar
            $(document).on('click', '.shopglut-remove-comparison-product, #shopglut-clear-comparison-btn', function() {
                setTimeout(function() {
                    self.syncCompareIcons();
                }, 100);
            });
        },

        updateCompareIcon: function($button, isAdded) {
            if (isAdded) {
                $button.find('.not-added').hide();
                $button.find('.added').show();
            } else {
                $button.find('.not-added').show();
                $button.find('.added').hide();
            }
        },

        syncCompareIcons: function() {
            var self = this;
            var comparisonProducts = self.loadComparisonProducts();

            $('.shopglut-add-to-comparison.compare').each(function() {
                var $button = $(this);
                var productId = parseInt($button.data('product-id'));
                var isInComparison = comparisonProducts.includes(productId);

                self.updateCompareIcon($button, isInComparison);
            });
        },

        loadComparisonProducts: function() {
            try {
                return JSON.parse(localStorage.getItem('shopglut_comparison_products') || '[]');
            } catch (e) {
                return [];
            }
        },

        addToCart: function($button) {
            var productId = $button.data('product-id');
            var self = this;

            if (!productId) {
                console.error('Product ID not found');
                return;
            }

            // Show loading state
            var $cartContents = $button.find('.cart-contents');
            var $cartLoading = $button.find('.cart-loading');
            var $cartAdded = $button.find('.cart-added');
            var $cartUnavailable = $button.find('.cart-unavailable');

            // Hide all states and show loading
            $cartContents.hide();
            $cartAdded.hide();
            $cartUnavailable.hide();
            $cartLoading.show();
            $button.addClass('loading').prop('disabled', true);

            $.ajax({
                url: shopglut_shop_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'add_to_cart',
                    product_id: productId,
                    nonce: shopglut_shop_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show success state
                        $cartLoading.hide();
                        $cartAdded.show();

                        // Change button to view cart link
                        setTimeout(function() {
                            var cartUrl = response.cart_url || shopglut_shop_vars.cart_url;

                            // Remove all click handlers first to avoid conflicts
                            $button.off('click');

                            // Change button attributes - make it a proper cart link
                            $button.attr('href', cartUrl);
                            $button.attr('target', '_blank');
                            $button.removeClass('ajax-spin-cart loading').addClass('view-cart-link');
                            $button.find('.cart-title').html('<i class="fa fa-check-square"></i> View Cart');
                            $button.prop('disabled', false);

                            console.log('Button converted to View Cart link:', cartUrl);
                        }, 1000);

                        self.showNotification('Product added to cart!', 'success');

                        // Update any cart elements on the page
                        if (response.cart_count !== undefined) {
                            $('.cart-count').text(response.cart_count);
                        }
                        if (response.cart_total !== undefined) {
                            $('.cart-total').text(response.cart_total);
                        }

                        // Don't trigger WooCommerce cart update event to avoid drawer/sidebar opening
                        // $(document.body).trigger('added_to_cart', [response.data || {}, response.cart_hash, $button]);
                    } else {
                        // Show error state
                        $cartLoading.hide();
                        $cartContents.show();
                        self.showNotification(response.data || 'Failed to add to cart', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    // Show error state
                    $cartLoading.hide();
                    $cartContents.show();
                    console.error('Add to Cart AJAX error:', error);
                    self.showNotification('Network error occurred', 'error');
                },
                complete: function() {
                    // Only remove loading if not showing success state
                    if ($cartLoading.is(':visible')) {
                        $button.removeClass('loading').prop('disabled', false);
                        $cartLoading.hide();
                        $cartContents.show();
                    }
                }
            });
        },

        toggleWishlist: function($button) {
            var productId = $button.data('product-id');
            var addIcon = $button.data('add-icon');
            var addedIcon = $button.data('added-icon');
            var self = this;

            if (!productId) {
                console.error('Product ID not found');
                return;
            }

            // Check if product is already in wishlist by checking parent div class
            var $parent = $button.find('div');
            var isAdded = $parent.hasClass('added') ? 1 : 0;
            var $icon = $button.find('i');

            console.log('Toggling wishlist - Product ID:', productId, 'Is Added:', isAdded);
            console.log('Add Icon:', addIcon, 'Added Icon:', addedIcon);

            $button.addClass('loading').prop('disabled', true);

            $.ajax({
                url: shopglut_shop_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_toggle_wishlist',
                    product_id: productId,
                    is_added: isAdded,
                    post_type: 'shop',
                    nonce: shopglut_shop_vars.wishlist_nonce
                },
                success: function(response) {
                    console.log('Wishlist response:', response);
                    if (response.success) {
                        // Use shop layout's icons instead of module's icons
                        if (isAdded) {
                            // Was in wishlist, now removing - show add icon
                            $parent.removeClass('added').addClass('not-added');
                            $icon.attr('class', addIcon);
                            console.log('Removed from wishlist, showing add icon:', addIcon);
                        } else {
                            // Was not in wishlist, now adding - show added icon
                            $parent.removeClass('not-added').addClass('added');
                            $icon.attr('class', addedIcon);
                            console.log('Added to wishlist, showing added icon:', addedIcon);
                        }

                        // Show notification from response using module settings
                        if (response.data.notification_text) {
                            if (shopglut_shop_vars.notification_type === "side-notification") {
                                self.showSideNotification(
                                    response.data.notification_text,
                                    shopglut_shop_vars.notification_position,
                                    shopglut_shop_vars.side_notification_effect,
                                    isAdded
                                );
                            } else if (shopglut_shop_vars.notification_type === "popup-notification") {
                                self.showPopupNotification(
                                    response.data.notification_text,
                                    shopglut_shop_vars.popup_notification_effect,
                                    isAdded
                                );
                            } else {
                                // Fallback to simple notification
                                self.showNotification(response.data.notification_text, 'success');
                            }
                        }
                    } else {
                        var errorMsg = response.data && response.data.message ? response.data.message : 'Failed to update wishlist';
                        self.showNotification(errorMsg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Wishlist AJAX error:', status, error);
                    self.showNotification('Network error occurred', 'error');
                },
                complete: function() {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },

        showSideNotification: function(message, position, effect, isAdded) {
            var statusClass = isAdded ? "removed" : "added";
            var $notification = $("<div class='shog-wishlist-notification side-notification'></div>").text(message);
            $notification.addClass(position);
            $notification.addClass(statusClass);
            $("body").append($notification);

            // Apply the selected effect
            switch (effect) {
                case "slide-down-up":
                    $notification
                        .hide()
                        .slideDown()
                        .delay(5000)
                        .slideUp(function () {
                            $(this).remove();
                        });
                    break;

                case "slide-from-left":
                    $notification
                        .css({left: "-100px", right: "auto"})
                        .animate({left: "7px"}, 500)
                        .delay(5000)
                        .animate({left: "-210px"}, 500, function () {
                            $(this).remove();
                        });
                    break;

                case "slide-from-right":
                    $notification
                        .css({right: "-100px", left: "auto"})
                        .animate({right: "7px"}, 500)
                        .delay(5000)
                        .animate({right: "-210px"}, 500, function () {
                            $(this).remove();
                        });
                    break;

                case "bounce":
                    $notification
                        .css({top: "-=30px", opacity: 0})
                        .animate({top: "+=30px", opacity: 1}, 300)
                        .delay(5000)
                        .animate({top: "-=10px"}, 100)
                        .animate({top: "+=20px"}, 100)
                        .animate({top: "-=10px"}, 100, function () {
                            $(this).fadeOut().remove();
                        });
                    break;

                default:
                    $notification
                        .fadeIn()
                        .delay(5000)
                        .fadeOut(function () {
                            $(this).remove();
                        });
            }
        },

        showPopupNotification: function(message, effect, isAdded) {
            var statusClass = isAdded ? "removed" : "added";
            var $popup = $("<div class='shog-wishlist-notification popup-notification'></div>").text(message);
            $popup.addClass(statusClass);
            $("body").append($popup);

            // Apply the selected effect
            switch (effect) {
                case "zoom-in":
                    $popup
                        .css({ transform: "translate(-50%, -50%) scale(0)", opacity: 0 })
                        .animate({ opacity: 1 }, {
                            duration: 300,
                            step: function (now, fx) {
                                if (fx.prop === "opacity") {
                                    var scale = now;
                                    $(this).css("transform", "translate(-50%, -50%) scale(" + scale + ")");
                                }
                            }
                        })
                        .delay(3000)
                        .animate({ opacity: 0 }, {
                            duration: 300,
                            step: function (now, fx) {
                                if (fx.prop === "opacity") {
                                    var scale = now;
                                    $(this).css("transform", "translate(-50%, -50%) scale(" + scale + ")");
                                }
                            },
                            complete: function () {
                                $(this).remove();
                            }
                        });
                    break;

                case "bounce":
                    $popup
                        .css({ opacity: 0, top: "40%" })
                        .animate({ opacity: 1, top: "50%" }, 300)
                        .delay(3000)
                        .animate({ top: "48%" }, 100)
                        .animate({ top: "52%" }, 100)
                        .animate({ top: "50%", opacity: 0 }, 200, function () {
                            $(this).remove();
                        });
                    break;

                case "shake":
                    $popup
                        .css({ display: "block", opacity: 1 })
                        .delay(3000)
                        .fadeOut(function () {
                            $(this).remove();
                        });
                    break;

                case "drop-in":
                    $popup
                        .css({ top: "-100px", opacity: 0 })
                        .animate({ top: "50%", opacity: 1 }, 400)
                        .delay(3000)
                        .animate({ top: "-100px", opacity: 0 }, 400, function () {
                            $(this).remove();
                        });
                    break;

                default:
                    $popup
                        .fadeIn()
                        .delay(3000)
                        .fadeOut(function () {
                            $(this).remove();
                        });
            }
        },

        showNotification: function(message, type) {
            var $notification = $('<div class="shopg-notification shopg-notification-' + type + '">' + message + '</div>');
            $('body').append($notification);

            setTimeout(function() {
                $notification.addClass('active');
            }, 10);

            setTimeout(function() {
                $notification.removeClass('active');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        if (typeof ShopGlutShopFrontend !== 'undefined') {
            ShopGlutShopFrontend.init();
        }
    });

})(jQuery);
