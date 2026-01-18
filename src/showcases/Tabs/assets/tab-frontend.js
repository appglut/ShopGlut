/**
 * ShopGlut Product Tab - Frontend JavaScript
 */
(function($) {
    'use strict';

    const ShopGlutTab = {
        /**
         * Initialize Tab
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Handle tab button click
            $(document).on('click', '.shopglut-tab-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const button = $(this);
                const productId = button.data('product-id');

                if (!productId) {
                    console.error('ShopGlut Tab: No product ID found');
                    return;
                }

                self.loadTab(productId, button);
            });

            // Handle modal close
            $(document).on('click', '.tab-close, .tab-modal-overlay', function(e) {
                e.preventDefault();
                self.closeTab();
            });

            // Prevent modal content click from closing modal
            $(document).on('click', '.tab-modal-content', function(e) {
                e.stopPropagation();
            });

            // Handle escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closeTab();
                }
            });

            // Handle thumbnail clicks
            $(document).on('click', '.shopglut-product-tab .thumbnail-item', function() {
                const $this = $(this);
                const largeImageUrl = $this.find('img').data('large') || $this.find('img').attr('src');

                // Update active state
                $this.siblings('.thumbnail-item').removeClass('active');
                $this.addClass('active');

                // Update main image
                $this.closest('.tab-gallery').find('.tab-main-image').attr('src', largeImageUrl);
            });

            // Handle quantity buttons
            $(document).on('click', '.shopglut-product-tab .qty-decrease', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const minVal = parseInt($input.attr('min')) || 1;

                if (currentVal > minVal) {
                    $input.val(currentVal - 1).trigger('change');
                }
            });

            $(document).on('click', '.shopglut-product-tab .qty-increase', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const maxVal = parseInt($input.attr('max')) || 9999;

                if (currentVal < maxVal) {
                    $input.val(currentVal + 1).trigger('change');
                }
            });

            // Handle add to cart
            $(document).on('click', '.shopglut-product-tab .add-to-cart-btn', function(e) {
                e.preventDefault();
                self.handleAddToCart($(this));
            });
        },

        /**
         * Load Tab modal content via AJAX
         */
        loadTab: function(productId, button) {
            const self = this;

            // Show loading state on button
            const originalButtonContent = button.html();
            button.prop('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

            // Show modal loader immediately
            self.showTabLoader();

            // AJAX request to get product data
            $.ajax({
                url: shopglutTabData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_tab_product',
                    product_id: productId,
                    nonce: shopglutTabData.nonce
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        self.showTab(response.data.html);
                    } else {
                        console.error('ShopGlut Tab: Failed to load product', response);
                        self.hideTabLoader();
                        alert(response.data.message || 'Failed to load product quick view');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('ShopGlut Tab: AJAX error', error);
                    self.hideTabLoader();
                    alert('Failed to load product quick view. Please try again.');
                },
                complete: function() {
                    // Restore button state
                    button.prop('disabled', false).removeClass('loading').html(originalButtonContent);
                }
            });
        },

        /**
         * Show Tab modal
         */
        showTab: function(html) {
            const $container = $('#shopglut-tab-modal-container');

            if ($container.length === 0) {
                console.error('Quick View: Modal container not found!');
                return;
            }

            // Insert HTML
            $container.html(html);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.tab-modal').addClass('active');
                $('body').addClass('shopglut-tab-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Close Tab modal
         */
        closeTab: function() {
            const $container = $('#shopglut-tab-modal-container');

            // Hide with animation
            $container.find('.tab-modal').removeClass('active');
            $('body').removeClass('shopglut-tab-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Show Tab loader modal
         */
        showTabLoader: function() {
            const $container = $('#shopglut-tab-modal-container');

            // Create loader HTML
            const loaderHtml = `
                <div class="tab-modal">
                    <div class="tab-modal-overlay"></div>
                    <div class="tab-modal-content">
                        <div class="tab-loader-container">
                            <div class="tab-loader">
                                <div class="tab-loader-spinner">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="tab-loader-text">Loading product details...</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Insert loader HTML
            $container.html(loaderHtml);

            // Show modal with animation
            setTimeout(function() {
                $container.find('.tab-modal').addClass('active');
                $('body').addClass('shopglut-tab-open').css('overflow', 'hidden');
            }, 10);
        },

        /**
         * Hide Tab loader modal
         */
        hideTabLoader: function() {
            const $container = $('#shopglut-tab-modal-container');

            // Hide with animation
            $container.find('.tab-modal').removeClass('active');
            $('body').removeClass('shopglut-tab-open').css('overflow', '');

            // Remove content after animation
            setTimeout(function() {
                $container.empty();
            }, 300);
        },

        /**
         * Handle add to cart button click
         */
        handleAddToCart: function($button) {
            const $tab = $button.closest('.shopglut-product-tab');
            const productId = $button.data('product-id');
            const quantity = $tab.find('.qty-input').val() || 1;

            // Check for variations
            const $variations = $tab.find('.variation-select');
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
                console.error('ShopGlut Tab: WooCommerce add to cart params not found');
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
                            ShopGlutTab.closeTab();
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
        ShopGlutTab.init();

        // Expose to global window object for other modules
        window.ShopGlutTab = ShopGlutTab;
    });

})(jQuery);
