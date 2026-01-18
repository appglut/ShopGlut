/**
 * ShopGlut Product QuickView - Admin JavaScript
 * Handles Live View Demo modal functionality in admin area
 */
(function($) {
    'use strict';

    const ShopGlutQuickViewAdmin = {
        /**
         * Initialize Admin QuickView
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Handle modal close in admin area - support both modal structures
            $(document).on('click', '.quickview-close, .quickview-modal-overlay, .shopglut-template-modal-close-modal', function(e) {
                e.preventDefault();
                self.closeQuickView();
            });

            // Prevent modal content click from closing modal
            $(document).on('click', '.quickview-modal-content, .shopglut-template-modal-modal-content', function(e) {
                e.stopPropagation();
            });

            // Handle escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closeQuickView();
                }
            });

            // Handle thumbnail clicks in demo
            $(document).on('click', '.shopglut-product-quickview .thumbnail-item', function() {
                const $this = $(this);
                const largeImageUrl = $this.find('img').data('large') || $this.find('img').attr('src');

                // Update active state
                $this.siblings('.thumbnail-item').removeClass('active');
                $this.addClass('active');

                // Update main image
                $this.closest('.quickview-gallery').find('.quickview-main-image').attr('src', largeImageUrl);
            });

            // Handle quantity buttons in demo
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

            // Handle add to cart button in demo (show message instead of actual add to cart)
            $(document).on('click', '.shopglut-product-quickview .add-to-cart-btn', function(e) {
                e.preventDefault();
                const $button = $(this);

                // Save original button text if not already saved
                if (!$button.data('original-text')) {
                    $button.data('original-text', $button.html());
                }

                // Show loading state
                $button.prop('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i> Adding...');

                // Simulate add to cart for demo
                setTimeout(function() {
                    $button.removeClass('loading').html('✓ Added to Cart (Demo)!');

                    // Reset button after delay
                    setTimeout(function() {
                        $button.prop('disabled', false).html($button.data('original-text'));
                    }, 2000);
                }, 1000);
            });
        },

        /**
         * Close QuickView modal
         */
        closeQuickView: function() {
            // Handle the specific htmlDemoModal structure
            const $htmlModal = $('#htmlDemoModal');
            if ($htmlModal.length) {
                $htmlModal.hide();
                // Remove any body classes that might have been added
                $('body').removeClass('shopglut-quickview-open').css('overflow', '');
                return;
            }

            // Handle standard quickview modal structure
            const $container = $('#shopglut-quickview-modal-container');
            if ($container.length) {
                // Hide with animation
                $container.find('.quickview-modal').removeClass('active');
                $('body').removeClass('shopglut-quickview-open').css('overflow', '');

                // Remove content after animation
                setTimeout(function() {
                    $container.empty();
                }, 300);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopGlutQuickViewAdmin.init();

        // Expose to global window object for other modules
        window.ShopGlutQuickViewAdmin = ShopGlutQuickViewAdmin;
    });

})(jQuery);