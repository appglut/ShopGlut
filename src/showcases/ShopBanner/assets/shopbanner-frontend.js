/**
 * ShopGlut Product ShopBanner - Frontend JavaScript
 */
(function($) {
    'use strict';

    const ShopGlutShopBanner = {
        /**
         * Initialize ShopBanner
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Handle shopbanner button click
            $(document).on('click', '.shopglut-shopbanner-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const button = $(this);
                const productId = button.data('product-id');

                if (!productId) {
                    console.error('ShopGlut ShopBanner: No product ID found');
                    return;
                }

                self.loadShopBanner(productId, button);
            });

            // Handle modal close
            $(document).on('click', '.shopbanner-close, .shopbanner-modal-overlay', function(e) {
                e.preventDefault();
                self.closeShopBanner();
            });

            // Prevent modal content click from closing modal
            $(document).on('click', '.shopbanner-modal-content', function(e) {
                e.stopPropagation();
            });

            // Handle escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closeShopBanner();
                }
            });

            // Handle thumbnail clicks
            $(document).on('click', '.shopglut-product-shopbanner .thumbnail-item', function() {
                const $this = $(this);
                const largeImageUrl = $this.find('img').data('large') || $this.find('img').attr('src');

                // Update active state
                $this.siblings('.thumbnail-item').removeClass('active');
                $this.addClass('active');

                // Update main image
                $this.closest('.shopbanner-gallery').find('.shopbanner-main-image').attr('src', largeImageUrl);
            });

            // Handle quantity buttons
            $(document).on('click', '.shopglut-product-shopbanner .qty-decrease', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const minVal = parseInt($input.attr('min')) || 1;

                if (currentVal > minVal) {
                    $input.val(currentVal - 1).trigger('change');
                }
            });

            $(document).on('click', '.shopglut-product-shopbanner .qty-increase', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const maxVal = parseInt($input.attr('max')) || 9999;

                if (currentVal < maxVal) {
                    $input.val(currentVal + 1).trigger('change');
                }
            });

            // Handle add to cart
            $(document).on('click', '.shopglut-product-shopbanner .add-to-cart-btn', function(e) {
                e.preventDefault();
                self.handleAddToCart($(this));
            });
        },

        /**
         * Load ShopBanner modal content via AJAX
         */
        loadShopBanner: function(productId, button) {
            const self = this;

            // Show loading state on button
            const originalButtonContent = button.html();
            button.prop('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

            // Show modal loader immediately
            self.showShopBannerLoader();

            // AJAX request to get product data
            $.ajax({
                url: shopglutShopBannerData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_shopbanner_product',
                    product_id: productId,
                    nonce: shopglutShopBannerData.nonce
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        self.showShopBanner(response.data.html);
                    } else {
                        console.error('ShopGlut ShopBanner: Failed to load product', response);
                        self.hideShopBannerLoader();
                        alert(response.data.message || 'Failed to load product quick view');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('ShopGlut ShopBanner: AJAX error', error);
                    self.hideShopBannerLoader();
                    alert('Failed to load product quick view. Please try again.');
                },
                complete: function() {
                    // Restore button state
                    button.prop('disabled', false).removeClass('loading').html(originalButtonContent);
                }
            });
        },

        /**
         * Show ShopBanner modal
         */
        showShopBanner: function(html) {
            const $container = $('#shopglut-shopbanner-modal-container');

            if ($container.length === 0) {
                console.error('Quick View: Modal container not found!');
                return;
            }

            // Insert HTML
            $container.html(html);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.shopbanner-modal').addClass('active');
                $('body').addClass('shopglut-shopbanner-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Close ShopBanner modal
         */
        closeShopBanner: function() {
            const $container = $('#shopglut-shopbanner-modal-container');

            // Hide with animation
            $container.find('.shopbanner-modal').removeClass('active');
            $('body').removeClass('shopglut-shopbanner-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Show ShopBanner loader modal
         */
        showShopBannerLoader: function() {
            const $container = $('#shopglut-shopbanner-modal-container');

            // Create loader HTML
            const loaderHtml = `
                <div class="shopbanner-modal">
                    <div class="shopbanner-modal-overlay"></div>
                    <div class="shopbanner-modal-content">
                        <div class="shopbanner-loader-container">
                            <div class="shopbanner-loader">
                                <div class="shopbanner-loader-spinner">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="shopbanner-loader-text">Loading product details...</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Insert loader HTML
            $container.html(loaderHtml);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.shopbanner-modal').addClass('active');
                $('body').addClass('shopglut-shopbanner-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Hide ShopBanner loader modal
         */
        hideShopBannerLoader: function() {
            const $container = $('#shopglut-shopbanner-modal-container');

            // Hide with animation
            $container.find('.shopbanner-modal').removeClass('active');
            $('body').removeClass('shopglut-shopbanner-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Handle add to cart button click
         */
        handleAddToCart: function($button) {
            const $shopbanner = $button.closest('.shopglut-product-shopbanner');
            const productId = $button.data('product-id');
            const quantity = $shopbanner.find('.qty-input').val() || 1;

            // Check for variations
            const $variations = $shopbanner.find('.variation-select');
            const variations = {};

            if ($variations.length > 0) {
                let allSelected = true;

                $variations.each(function() {
                    const $select = $(this);
                    const value = $select.val();

                    if (!value) {
                        allSelected = false;
                        $select.addClass('error');
                    } else {
                        $select.removeClass('error');
                        variations[$select.attr('name')] = value;
                    }
                });

                if (!allSelected) {
                    alert('Please select all product options');
                    return;
                }
            }

            // Save original button text if not already saved
            if (!$button.data('original-text')) {
                $button.data('original-text', $button.html());
            }

            // Show loading state
            $button.prop('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i> Adding...');

            // Build form data for WooCommerce
            const formData = {
                product_id: productId,
                quantity: quantity,
                ...variations
            };

            // Check if WooCommerce AJAX is available
            if (typeof wc_add_to_cart_params === 'undefined') {
                console.error('ShopGlut ShopBanner: WooCommerce add to cart params not found');
                alert('WooCommerce is required for add to cart functionality');
                $button.prop('disabled', false).removeClass('loading').html($button.data('original-text'));
                return;
            }

            // Use WooCommerce's add to cart
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        // Reset button on error
                        $button.prop('disabled', false).removeClass('loading').html($button.data('original-text'));
                    } else {
                        // Trigger WooCommerce added_to_cart event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                        // Show success message
                        $button.removeClass('loading').html('✓ Added to Cart!');

                        // Close modal after delay - Don't re-enable button since modal is closing
                        setTimeout(function() {
                            ShopGlutShopBanner.closeShopBanner();
                        }, 1500);
                    }
                },
                error: function() {
                    alert('Failed to add product to cart. Please try again.');
                    // Reset button on error
                    $button.prop('disabled', false).removeClass('loading').html($button.data('original-text'));
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopGlutShopBanner.init();

        // Expose to global window object for other modules
        window.ShopGlutShopBanner = ShopGlutShopBanner;
    });

})(jQuery);
