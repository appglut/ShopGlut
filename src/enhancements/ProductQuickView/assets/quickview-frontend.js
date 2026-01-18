/**
 * ShopGlut Product QuickView - Frontend JavaScript
 */
(function($) {
    'use strict';

    const ShopGlutQuickView = {
        /**
         * Initialize QuickView
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Handle quickview button click
            $(document).on('click', '.shopglut-quickview-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const button = $(this);
                const productId = button.data('product-id');

                if (!productId) {
                    console.error('ShopGlut QuickView: No product ID found');
                    return;
                }

                self.loadQuickView(productId, button);
            });

            // Handle modal close
            $(document).on('click', '.quickview-close, .quickview-modal-overlay', function(e) {
                e.preventDefault();
                self.closeQuickView();
            });

            // Prevent modal content click from closing modal
            $(document).on('click', '.quickview-modal-content', function(e) {
                e.stopPropagation();
            });

            // Handle escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closeQuickView();
                }
            });

            // Handle thumbnail clicks
            $(document).on('click', '.shopglut-product-quickview .thumbnail-item', function() {
                const $this = $(this);
                const largeImageUrl = $this.find('img').data('large') || $this.find('img').attr('src');

                // Update active state
                $this.siblings('.thumbnail-item').removeClass('active');
                $this.addClass('active');

                // Update main image
                $this.closest('.quickview-gallery').find('.quickview-main-image').attr('src', largeImageUrl);
            });

            // Handle quantity buttons
            $(document).on('click', '.shopglut-product-quickview .qty-decrease', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const minVal = parseInt($input.attr('min')) || 1;

                if (currentVal > minVal) {
                    $input.val(currentVal - 1).trigger('change');
                }
            });

            $(document).on('click', '.shopglut-product-quickview .qty-increase', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const maxVal = parseInt($input.attr('max')) || 9999;

                if (currentVal < maxVal) {
                    $input.val(currentVal + 1).trigger('change');
                }
            });

            // Handle add to cart
            $(document).on('click', '.shopglut-product-quickview .add-to-cart-btn', function(e) {
                e.preventDefault();
                self.handleAddToCart($(this));
            });
        },

        /**
         * Load QuickView modal content via AJAX
         */
        loadQuickView: function(productId, button) {
            const self = this;

            // Show loading state on button
            const originalButtonContent = button.html();
            button.prop('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

            // Show modal loader immediately
            self.showQuickViewLoader();

            // AJAX request to get product data
            $.ajax({
                url: shopglutQuickViewData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_quickview_product',
                    product_id: productId,
                    nonce: shopglutQuickViewData.nonce
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        self.showQuickView(response.data.html);
                    } else {
                        console.error('ShopGlut QuickView: Failed to load product', response);
                        self.hideQuickViewLoader();
                        alert(response.data.message || 'Failed to load product quick view');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('ShopGlut QuickView: AJAX error', error);
                    self.hideQuickViewLoader();
                    alert('Failed to load product quick view. Please try again.');
                },
                complete: function() {
                    // Restore button state
                    button.prop('disabled', false).removeClass('loading').html(originalButtonContent);
                }
            });
        },

        /**
         * Show QuickView modal
         */
        showQuickView: function(html) {
            const $container = $('#shopglut-quickview-modal-container');

            if ($container.length === 0) {
                console.error('Quick View: Modal container not found!');
                return;
            }

            // Insert HTML
            $container.html(html);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.quickview-modal').addClass('active');
                $('body').addClass('shopglut-quickview-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Close QuickView modal
         */
        closeQuickView: function() {
            const $container = $('#shopglut-quickview-modal-container');

            // Hide with animation
            $container.find('.quickview-modal').removeClass('active');
            $('body').removeClass('shopglut-quickview-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Show QuickView loader modal
         */
        showQuickViewLoader: function() {
            const $container = $('#shopglut-quickview-modal-container');

            // Create loader HTML
            const loaderHtml = `
                <div class="quickview-modal">
                    <div class="quickview-modal-overlay"></div>
                    <div class="quickview-modal-content">
                        <div class="quickview-loader-container">
                            <div class="quickview-loader">
                                <div class="quickview-loader-spinner">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="quickview-loader-text">Loading product details...</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Insert loader HTML
            $container.html(loaderHtml);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.quickview-modal').addClass('active');
                $('body').addClass('shopglut-quickview-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Hide QuickView loader modal
         */
        hideQuickViewLoader: function() {
            const $container = $('#shopglut-quickview-modal-container');

            // Hide with animation
            $container.find('.quickview-modal').removeClass('active');
            $('body').removeClass('shopglut-quickview-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Handle add to cart button click
         */
        handleAddToCart: function($button) {
            const $quickview = $button.closest('.shopglut-product-quickview');
            const productId = $button.data('product-id');
            const quantity = $quickview.find('.qty-input').val() || 1;

            // Check for variations
            const $variations = $quickview.find('.variation-select');
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
                console.error('ShopGlut QuickView: WooCommerce add to cart params not found');
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
                            ShopGlutQuickView.closeQuickView();
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
        ShopGlutQuickView.init();

        // Expose to global window object for other modules
        window.ShopGlutQuickView = ShopGlutQuickView;
    });

})(jQuery);
