/**
 * ShopGlut Product Gallery - Admin JavaScript
 * Handles Live View Demo modal functionality in admin area
 */
(function($) {
    'use strict';

    const ShopGlutGalleryAdmin = {
        /**
         * Initialize Admin Gallery
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
            $(document).on('click', '.gallery-close, .gallery-modal-overlay, .shopglut-template-modal-close-modal', function(e) {
                e.preventDefault();
                self.closeGallery();
            });

            // Prevent modal content click from closing modal
            $(document).on('click', '.gallery-modal-content, .shopglut-template-modal-modal-content', function(e) {
                e.stopPropagation();
            });

            // Handle escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closeGallery();
                }
            });

            // Handle thumbnail clicks in demo
            $(document).on('click', '.shopglut-product-gallery .thumbnail-item', function() {
                const $this = $(this);
                const largeImageUrl = $this.find('img').data('large') || $this.find('img').attr('src');

                // Update active state
                $this.siblings('.thumbnail-item').removeClass('active');
                $this.addClass('active');

                // Update main image
                $this.closest('.gallery-gallery').find('.gallery-main-image').attr('src', largeImageUrl);
            });

            // Handle quantity buttons in demo
            $(document).on('click', '.shopglut-product-gallery .qty-decrease', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const minVal = parseInt($input.attr('min')) || 1;

                if (currentVal > minVal) {
                    $input.val(currentVal - 1).trigger('change');
                }
            });

            $(document).on('click', '.shopglut-product-gallery .qty-increase', function() {
                const $input = $(this).siblings('.qty-input');
                const currentVal = parseInt($input.val()) || 1;
                const maxVal = parseInt($input.attr('max')) || 9999;

                if (currentVal < maxVal) {
                    $input.val(currentVal + 1).trigger('change');
                }
            });

            // Handle add to cart button in demo (show message instead of actual add to cart)
            $(document).on('click', '.shopglut-product-gallery .add-to-cart-btn', function(e) {
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
         * Close Gallery modal
         */
        closeGallery: function() {
            // Handle the specific htmlDemoModal structure
            const $htmlModal = $('#htmlDemoModal');
            if ($htmlModal.length) {
                $htmlModal.hide();
                // Remove any body classes that might have been added
                $('body').removeClass('shopglut-gallery-open').css('overflow', '');
                return;
            }

            // Handle standard gallery modal structure
            const $container = $('#shopglut-gallery-modal-container');
            if ($container.length) {
                // Hide with animation
                $container.find('.gallery-modal').removeClass('active');
                $('body').removeClass('shopglut-gallery-open').css('overflow', '');

                // Remove content after animation
                setTimeout(function() {
                    $container.empty();
                }, 300);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopGlutGalleryAdmin.init();

        // Expose to global window object for other modules
        window.ShopGlutGalleryAdmin = ShopGlutGalleryAdmin;
    });

})(jQuery);