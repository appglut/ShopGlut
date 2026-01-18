jQuery(document).ready(function($) {
    'use strict';

    // Flag to prevent multiple simultaneous clicks
    var isProcessingQuantityChange = false;

    // Quantity selector functionality - Single unified handler for both simple and variable products
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .qty-decrease, .shopglut-single-product .qty-increase', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation(); // Stop any other handlers from firing

        // Prevent rapid-fire clicks
        if (isProcessingQuantityChange) {
            return false;
        }

        var $button = $(this);
        var isIncrease = $button.hasClass('qty-increase');
        var isDecrease = $button.hasClass('qty-decrease');

        // Find the quantity input - try multiple selectors to support both custom and WooCommerce inputs
        var $input = $button.siblings('.qty-input, input[name="quantity"], .qty, .input-text');

        // If sibling approach doesn't work, try finding within the same parent or nearby
        if ($input.length === 0) {
            $input = $button.closest('.quantity-selector, .quantity').find('.qty-input, input[name="quantity"], .qty, .input-text');
        }

        // For WooCommerce variable products, try finding in the closest quantity wrapper
        if ($input.length === 0) {
            $input = $button.closest('.quantity-cart-wrapper, .woocommerce-variation-add-to-cart').find('input[name="quantity"], .qty, .input-text');
        }

        if ($input.length > 0) {
            isProcessingQuantityChange = true;

            var currentValue = parseInt($input.val()) || 1;

            if (isIncrease) {
                var maxValue = parseInt($input.attr('max')) || 9999;
                // Handle WooCommerce's -1 (no limit) case
                if (maxValue === -1) {
                    maxValue = 9999;
                }

                if (currentValue < maxValue) {
                    var newValue = currentValue + 1;
                    $input.val(newValue);
                }
            } else if (isDecrease) {
                var minValue = parseInt($input.attr('min')) || 1;

                if (currentValue > minValue) {
                    var newValue = currentValue - 1;
                    $input.val(newValue);
                }
            }

            // Reset processing flag after a short delay
            setTimeout(function() {
                isProcessingQuantityChange = false;
            }, 100);
        }

        return false;
    });

    // Validate quantity input for both custom and WooCommerce inputs
    $(document).on('change keyup', '.qty-input, input[name="quantity"], .qty', function() {
        var $input = $(this);
        var value = parseInt($input.val()) || 1;
        var minValue = parseInt($input.attr('min')) || 1;
        var maxValue = parseInt($input.attr('max')) || 9999;

        // Handle WooCommerce's -1 (no limit) case
        if (maxValue === -1) {
            maxValue = 9999;
        }

        if (value < minValue) {
            $input.val(minValue);
        } else if (value > maxValue) {
            $input.val(maxValue);
        }
    });

    // DISABLED: Handle variable product AJAX submission using improved approach
    $(document).on('submit', 'form.shopglut-variations-form.DISABLED', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $form = $(this);
        var $button = $form.find('.shopglut-variable-add-to-cart');

        // Prevent any other handlers from firing or duplicate processing
        if ($button.hasClass('loading') || $button.prop('disabled') || $form.data('processing')) {
            return false;
        }

        // Check if variation is selected by looking for the variation_id
        var variation_id = $form.find('input[name="variation_id"]').val();
        if (!variation_id || variation_id == '0' || variation_id == '') {
            showNotification('Please select product options before adding to cart.', 'error');
            return false;
        }

        // Mark as processing to prevent duplicate calls
        $form.data('processing', true);

        // Show loading state
        var originalText = $button.text();
        $button.text('Adding...').prop('disabled', true).addClass('loading');

        // Get all form data
        var formData = $form.serializeArray();
        var data = {};

        // Convert form data to object
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        // Ensure we have the product ID (add-to-cart parameter)
        if (!data['add-to-cart']) {
            var product_id = $form.find('input[name="add-to-cart"]').val() ||
                           $form.find('button[name="add-to-cart"]').val() ||
                           $form.data('product_id');
            if (product_id) {
                data['add-to-cart'] = product_id;
            }
        }

        // Add AJAX action and nonce for Template1 handler
        data.action = 'shopglut_template1_add_variable_to_cart';
        data.nonce = shopglut_frontend_vars.nonce;

        $.ajax({
            type: 'POST',
            url: shopglut_frontend_vars.ajax_url,
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $button.removeClass('added').addClass('loading');
            },
            complete: function() {
                $button.removeClass('loading');
            },
            success: function(response) {

                if (response.success === false) {
                    // Handle error from our custom handler
                    var errorMsg = response.data && response.data.error ? response.data.error : 'Failed to add product to cart';
                    showNotification('Error: ' + errorMsg, 'error');
                    $button.text(originalText).prop('disabled', false);
                    return;
                }

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                // Check for errors in response
                if (response.error) {
                    showNotification('Error: ' + response.error, 'error');
                    $button.text(originalText).prop('disabled', false);
                    return;
                }

                if (response.fragments) {
                    // Update cart fragments
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                }

                // Show success state
                $button.addClass('added').text('Added to Cart');

                // Enhanced success message with quantity feedback
                var successMessage = 'Product added to cart successfully!';
                if (response.item_quantity && response.was_merged) {
                    successMessage = 'Quantity updated to ' + response.item_quantity + ' in cart';
                } else if (response.item_quantity) {
                    successMessage = 'Added to cart (quantity: ' + response.item_quantity + ')';
                }
                showNotification(successMessage, 'success');

                // Reset quantity input to 1 for better UX (optional)
                var $qtyInput = $form.find('.qty-input');
                if ($qtyInput.length > 0) {
                    $qtyInput.val(1);
                }

                // Trigger WooCommerce events
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                // Reset button after 2 seconds
                setTimeout(function() {
                    $button.text(originalText).removeClass('added').prop('disabled', false);
                }, 2000);
            },
            error: function(xhr, status, error) {

                // Try to parse error response
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.data && errorResponse.data.error) {
                        showNotification('Error: ' + errorResponse.data.error, 'error');
                    } else {
                        showNotification('Failed to add product to cart. Please try again.', 'error');
                    }
                } catch (e) {
                    showNotification('Failed to add product to cart. Please try again.', 'error');
                }

                $button.text(originalText).prop('disabled', false);
                $form.data('processing', false);
            }
        });

        return false;
    });

    // DISABLED: Prevent WooCommerce's native form submission for our custom forms
    $(document).on('submit', 'form.variations_form[data-shopglut-handled="true"].DISABLED', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        return false;
    });

    // WORKING VERSION: Just add a simple notification after page loads
    $(document).ready(function() {
        // Check if we just added something to cart (look for URL parameter or session)
        if (window.location.search.includes('add-to-cart=') || sessionStorage.getItem('shopglut_just_added')) {
            sessionStorage.removeItem('shopglut_just_added');
        }

        // Set flag before form submits
        $(document).on('submit', 'form.shopglut-variations-form', function() {
            sessionStorage.setItem('shopglut_just_added', 'true');
        });
    });

    // Backup: Enable WooCommerce AJAX for variable products
    $(document).ready(function() {

        // Enable WooCommerce AJAX add to cart for variable products
        $('body').on('adding_to_cart', function(event, $button, data) {
            $button.addClass('loading');
        });

        $('body').on('added_to_cart', function(event, fragments, cart_hash, $button) {
            $button.removeClass('loading').addClass('added');

            // Show success message - try multiple methods
            if (typeof showNotification === 'function') {
                showNotification('Product added to cart successfully!', 'success');
            } else {
                // Fallback notification methods

                // Show a simple alert as fallback
                var $notice = $('<div class="woocommerce-message" style="position: fixed; bottom: 20px; right: 20px; background: #28a745; color: white; padding: 15px 20px; border-radius: 4px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">Product added to cart successfully!</div>');
                $('body').append($notice);

                // Remove notice after 3 seconds
                setTimeout(function() {
                    $notice.fadeOut(function() {
                        $notice.remove();
                    });
                }, 3000);
            }

            // Reset quantity input
            var $form = $button.closest('form');
            var $qtyInput = $form.find('.qty-input');
            if ($qtyInput.length > 0) {
                $qtyInput.val(1);
            }

            // Reset button text after 2 seconds
            setTimeout(function() {
                $button.removeClass('added').text($button.data('original-text') || 'Add to cart');
            }, 2000);
        });

        $('body').on('wc_add_to_cart_error', function(event, error_message) {
            if (typeof showNotification === 'function') {
                showNotification('Error: ' + error_message, 'error');
            } else {
                // Fallback error notification
                var $errorNotice = $('<div class="woocommerce-error" style="position: fixed; top: 20px; right: 20px; background: #dc3545; color: white; padding: 15px 20px; border-radius: 4px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">Error: ' + error_message + '</div>');
                $('body').append($errorNotice);

                // Remove notice after 5 seconds
                setTimeout(function() {
                    $errorNotice.fadeOut(function() {
                        $errorNotice.remove();
                    });
                }, 5000);
            }
        });
    });

    // Listen for variation found events to update our form data
    $(document).on('found_variation', 'form.shopglut-variations-form', function(event, variation) {
        var $form = $(this);

        // Store variation data for our AJAX submission
        $form.data('current_variation', variation);

        // Update hidden inputs if they exist
        var $variation_id_input = $form.find('input[name="variation_id"]');
        if ($variation_id_input.length === 0) {
            $form.append('<input type="hidden" name="variation_id" value="' + variation.variation_id + '">');
        } else {
            $variation_id_input.val(variation.variation_id);
        }
    });

    // Handle variation reset
    $(document).on('reset_data', 'form.shopglut-variations-form', function(event) {
        var $form = $(this);
        $form.removeData('current_variation');
        $form.find('input[name="variation_id"]').val('');
    });

    // Add to Cart functionality for simple products only
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .add-to-cart-btn:not(.single_add_to_cart_button)', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.closest('.shopglut-single-product-container');
        var $groupedForm = $button.closest('form.grouped_form');

        // Handle grouped product forms
        if ($groupedForm.length > 0) {
            // Check if at least one grouped product quantity is greater than 0
            var hasQuantity = false;
            $groupedForm.find('.qty-input').each(function() {
                if (parseInt($(this).val()) > 0) {
                    hasQuantity = true;
                    return false; // Break the loop
                }
            });

            if (!hasQuantity) {
                showNotification('Please select at least one product to add to cart.', 'error');
                return;
            }

            // Let the form submit naturally for grouped products
            return true;
        }

        // Handle external/affiliate products
        if ($button.hasClass('external-product-btn')) {
            // External products are handled by their link - no additional logic needed
            return true;
        }

        // Handle simple products with custom AJAX
        var $qtyInput = $container.find('.qty-input');
        var quantity = parseInt($qtyInput.val()) || 1;

        // Get product ID from current page
        var productId = getProductIdFromCurrentPage();

        if (!productId) {
            showNotification('Product ID not found', 'error');
            return;
        }

        // Show loading state
        var originalText = $button.text();
        $button.text('Adding...').prop('disabled', true);

        // Prepare data for WooCommerce AJAX add to cart
        var data = {
            action: 'woocommerce_add_to_cart',
            product_id: productId,
            quantity: quantity,
            product_sku: '',
            variation_id: 0,
            variation: {}
        };

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function() {
                $button.removeClass('added').addClass('loading');
            },
            complete: function() {
                $button.removeClass('loading');
            },
            success: function(response) {
                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                if (response.fragments) {
                    // Update cart fragments
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                }

                // Show success state
                $button.addClass('added').text('Added to Cart');
                showNotification('Product added to cart successfully!', 'success');

                // Trigger WooCommerce events
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                // Reset button after 2 seconds
                setTimeout(function() {
                    $button.text(originalText).removeClass('added').prop('disabled', false);
                }, 2000);
            },
            error: function(xhr, status, error) {
                showNotification('Failed to add product to cart. Please try again.', 'error');
                $button.text(originalText).prop('disabled', false);
            }
        });
    });

    // Handle grouped product form submissions (variable products are handled in the click handler above)
    $(document).on('submit', 'form.grouped_form', function(e) {
        var $form = $(this);
        var $button = $form.find('.add-to-cart-btn');

        // Show loading state for grouped products
        var originalText = $button.text();
        $button.text('Adding...').prop('disabled', true);

        // Reset button state after form processes
        setTimeout(function() {
            $button.text(originalText).prop('disabled', false);
        }, 3000);
    });

    // Quick Add functionality for related products
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .quick-add-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var productId = $button.data('product-id');

        if (!productId) {
            showNotification('Product ID not found', 'error');
            return;
        }

        // Show loading state
        var originalText = $button.text();
        $button.text('Adding...').prop('disabled', true);

        var data = {
            action: 'woocommerce_add_to_cart',
            product_id: productId,
            quantity: 1
        };

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function() {
                $button.removeClass('added').addClass('loading');
            },
            complete: function() {
                $button.removeClass('loading');
            },
            success: function(response) {
                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                if (response.fragments) {
                    // Update cart fragments
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                }

                // Show success state
                $button.addClass('added').text('Added!');
                showNotification('Product added to cart successfully!', 'success');

                // Trigger WooCommerce events
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                // Reset button after 2 seconds
                setTimeout(function() {
                    $button.text(originalText).removeClass('added').prop('disabled', false);
                }, 2000);
            },
            error: function(xhr, status, error) {
                showNotification('Failed to add product to cart. Please try again.', 'error');
                $button.text(originalText).prop('disabled', false);
            }
        });
    });

    // Thumbnail gallery functionality - Enhanced with settings support
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .thumbnail-item', function(e) {
        e.preventDefault();

        var $thumbnail = $(this);
        var $gallery = $thumbnail.closest('.product-gallery-section');
        var $mainImageContainer = $gallery.find('.main-image-container');
        var $mainImage = $mainImageContainer.find('.main-product-image');

        // Get full-size image URL from data attribute
        var fullImageSrc = $thumbnail.data('image-full');
        var thumbnailSrc = $thumbnail.find('.thumbnail-image').attr('src');
        var newImageAlt = $thumbnail.find('.thumbnail-image').attr('alt');

        // Use full-size image if available, otherwise use thumbnail
        var newImageSrc = fullImageSrc || thumbnailSrc;

        // Update active thumbnail
        $gallery.find('.thumbnail-item').removeClass('active');
        $thumbnail.addClass('active');

        // Update main image with fade effect
        $mainImage.fadeOut(200, function() {
            $(this).attr('src', newImageSrc).attr('alt', newImageAlt).fadeIn(200);
        });

        // Update data attributes on main image for lightbox
        $mainImage.attr('data-image-full', newImageSrc);
    });

    // Initialize gallery settings on page load
    function initializeGallerySettings() {
        $('.shopglut-single-product-container .product-gallery-section').each(function() {
            var $gallerySection = $(this);
            var $mainImageContainer = $gallerySection.find('.main-image-container');
            var $thumbnailGallery = $gallerySection.find('.thumbnail-gallery');

            // Apply cursor style based on settings
            var cursorStyle = $mainImageContainer.data('cursor-style');
            var lightboxEnabled = $mainImageContainer.data('lightbox-enabled');
            var hoverZoomEnabled = $mainImageContainer.data('hover-zoom-enabled');
            var hoverZoomLevel = $mainImageContainer.data('hover-zoom-level');

            if (cursorStyle) {
                $mainImageContainer.find('.main-product-image').css('cursor', cursorStyle);
            }

            // Initialize lightbox if enabled
            if (lightboxEnabled === 'true' || lightboxEnabled === true) {
                $mainImageContainer.find('.main-product-image').addClass('lightbox-enabled');
            }

            // Store hover zoom settings for JavaScript to use
            if (hoverZoomEnabled === 'true' || hoverZoomEnabled === true) {
                $mainImageContainer.data('hover-zoom-enabled', true);
                $mainImageContainer.data('hover-zoom-level', hoverZoomLevel || 2);
            }

            // Apply thumbnail alignment from data attribute
            var thumbnailAlignment = $thumbnailGallery.data('alignment');
            if (thumbnailAlignment) {
                $thumbnailGallery.css('justify-content', thumbnailAlignment);
            }
        });
    }

    // Position-based hover zoom effect for main product image
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('mousemove', '.shopglut-single-product .main-image-container', function(e) {
        var $container = $(this);
        var $image = $container.find('.main-product-image');

        // Check if hover zoom is enabled via data attribute
        var hoverZoomEnabled = $container.data('hover-zoom-enabled');
        if (hoverZoomEnabled !== 'true' && hoverZoomEnabled !== true) {
            return;
        }

        // Get container and image dimensions
        var containerOffset = $container.offset();
        var containerWidth = $container.width();
        var containerHeight = $container.height();

        // Calculate mouse position relative to container
        var mouseX = e.pageX - containerOffset.left;
        var mouseY = e.pageY - containerOffset.top;

        // Calculate position as percentage (0-100%)
        var percentX = (mouseX / containerWidth) * 100;
        var percentY = (mouseY / containerHeight) * 100;

        // Get zoom level from data attribute or default to 2
        var zoomLevel = $container.data('hover-zoom-level') || 2;

        // Apply transform based on mouse position
        $image.css({
            'transform-origin': percentX + '% ' + percentY + '%',
            'transform': 'scale(' + zoomLevel + ')'
        });
    });

    // Reset zoom when mouse leaves the container
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('mouseleave', '.shopglut-single-product .main-image-container', function() {
        var $container = $(this);
        var $image = $container.find('.main-product-image');
        var hoverZoomEnabled = $container.data('hover-zoom-enabled');

        if (hoverZoomEnabled === 'true' || hoverZoomEnabled === true) {
            $image.css({
                'transform-origin': 'center center',
                'transform': 'scale(1)'
            });
        }
    });

    // Lightbox functionality for product images
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .main-product-image.lightbox-enabled', function(e) {
        e.preventDefault();

        var $mainImage = $(this);
        var fullImageUrl = $mainImage.data('image-full') || $mainImage.attr('src');

        // Create lightbox overlay
        var $lightbox = $('<div class="shopglut-lightbox-overlay">' +
            '<div class="shopglut-lightbox-container">' +
                '<button class="shopglut-lightbox-close">&times;</button>' +
                '<img src="' + fullImageUrl + '" alt="Lightbox image" class="shopglut-lightbox-image">' +
            '</div>' +
        '</div>');

        $('body').append($lightbox);

        // Fade in lightbox
        $lightbox.fadeIn(300);

        // Close lightbox on close button click
        $lightbox.find('.shopglut-lightbox-close').on('click', function() {
            $lightbox.fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Close lightbox on overlay click
        $lightbox.on('click', function(e) {
            if (e.target === this || $(e.target).hasClass('shopglut-lightbox-container')) {
                $lightbox.fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });

        // Close lightbox on Escape key
        $(document).one('keydown.lightbox', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                $lightbox.fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
    });

    // Attribute selection functionality (for color swatches, sizes, etc.)
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .color-swatch, .shopglut-single-product .size-button, .shopglut-single-product .attribute-value', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $item = $(this);
        var $group = $item.closest('.attribute-group');

        // Remove active class from siblings and add to clicked item
        $group.find('.color-swatch, .size-button, .attribute-value').removeClass('active selected');
        $item.addClass('active selected');
    });

    // Wishlist functionality (basic implementation)
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .wishlist-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var productId = getProductIdFromCurrentPage();

        if (!productId) {
            showNotification('Product ID not found', 'error');
            return;
        }

        // Toggle wishlist state
        if ($button.hasClass('in-wishlist')) {
            $button.removeClass('in-wishlist').find('i').removeClass('fas').addClass('far');
            showNotification('Removed from wishlist', 'success');
        } else {
            $button.addClass('in-wishlist').find('i').removeClass('far').addClass('fas');
            showNotification('Added to wishlist', 'success');
        }
    });

    // Compare functionality (basic implementation)
    // Scoped to ShopGlut templates to avoid conflicts
    $(document).on('click', '.shopglut-single-product .compare-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var productId = getProductIdFromCurrentPage();

        if (!productId) {
            showNotification('Product ID not found', 'error');
            return;
        }

        // Toggle compare state
        if ($button.hasClass('in-compare')) {
            $button.removeClass('in-compare');
            showNotification('Removed from compare', 'success');
        } else {
            $button.addClass('in-compare');
            showNotification('Added to compare', 'success');
        }
    });

    // Helper function to get product ID from current page
    function getProductIdFromCurrentPage() {
        // Try to get from URL parameters first
        var urlParams = new URLSearchParams(window.location.search);
        var productId = urlParams.get('product_id') || urlParams.get('p');

        if (productId) {
            return productId;
        }

        // Try to get from WordPress body classes
        var bodyClasses = $('body').attr('class') || '';
        var postIdMatch = bodyClasses.match(/postid-(\d+)/);
        if (postIdMatch) {
            return postIdMatch[1];
        }

        // Try to get from data attributes on the product container
        var $container = $('.shopglut-single-product-container');
        productId = $container.data('product-id');
        if (productId) {
            return productId;
        }

        // Try to get from WooCommerce variables if available
        if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.post_id) {
            return wc_single_product_params.post_id;
        }

        // Try to get from global WordPress variables
        if (typeof shopglut_vars !== 'undefined' && shopglut_vars.product_id) {
            return shopglut_vars.product_id;
        }

        return null;
    }

    // Notification function - uses centralized ShopGlutNotification utility
    function showNotification(message, type) {
        if (typeof ShopGlutNotification !== 'undefined') {
            ShopGlutNotification.show(message, type, { position: 'top-right', duration: 3000 });
        } else {
            // Fallback if centralized utility not loaded
            $('.shopglut-frontend-notification').remove();
            var notification = $('<div class="shopglut-frontend-notification shopglut-notification-' + type + '">' +
                               '<span>' + message + '</span>' +
                               '<button class="notification-close">×</button>' +
                               '</div>');
            $('body').append(notification);
            setTimeout(function() {
                notification.fadeOut(300, function() { $(this).remove(); });
            }, 3000);
            notification.find('.notification-close').on('click', function() {
                notification.fadeOut(300, function() { $(this).remove(); });
            });
        }
    }

    // Removed duplicate alternative handler to prevent double-firing

    // Initialize on page load
    initializeShopglutTemplate();

    function initializeShopglutTemplate() {
        // Set initial active states
        $('.thumbnail-gallery .thumbnail-item:first').addClass('active');
        $('.color-swatches .color-swatch:first').addClass('active');
        $('.size-buttons .size-button:first').addClass('active');

        // Initialize gallery settings (cursor, alignment, lightbox)
        initializeGallerySettings();

        // Initialize WooCommerce tabs
        initializeWooCommerceTabs();

        // Ensure quantity input has proper min value
        $('.qty-input').each(function() {
            var $input = $(this);
            if (!$input.attr('min')) {
                $input.attr('min', '1');
            }
            if (!$input.val() || parseInt($input.val()) < 1) {
                $input.val(1);
            }
        });

        // Initialize quantity selector elements
    }

    // Initialize WooCommerce tabs functionality - uses event delegation for dynamic content
    function initializeWooCommerceTabs() {
        var $tabsContainer = $('.shopglut-single-product-container .woocommerce-tabs');
        if ($tabsContainer.length === 0) {
            return;
        }

        $tabsContainer.each(function() {
            var $container = $(this);
            var $tabs = $container.find('.wc-tabs li');
            var $panels = $container.find('.woocommerce-Tabs-panel');

            // Set first tab as active by default
            if ($tabs.length > 0 && !$tabs.filter('.active').length) {
                $tabs.first().addClass('active');
                $panels.first().addClass('active');
            }
        });
    }

    // Use event delegation for tab clicks - works with dynamically loaded content
    // Scoped to ShopGlut single product templates to avoid conflicts with WooCommerce admin tabs
    $(document).on('click', '.shopglut-single-product .wc-tabs a', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $link = $(this);
        var $tab = $link.closest('li');
        var $tabsContainer = $link.closest('.woocommerce-tabs');
        var targetId = $link.attr('href');

        // Find target panel - first in the same container, then globally
        var $targetPanel = $tabsContainer.find(targetId);
        if ($targetPanel.length === 0) {
            $targetPanel = $(targetId);
        }

        if ($targetPanel.length === 0) {
            return;
        }

        // Remove active classes from all tabs and panels in this container
        $tabsContainer.find('.wc-tabs li').removeClass('active');
        $tabsContainer.find('.woocommerce-Tabs-panel').removeClass('active');

        // Add active classes to clicked tab and corresponding panel
        $tab.addClass('active');
        $targetPanel.addClass('active');

        // No automatic scroll - let user control scrolling
    });
});

// Add CSS styles for interactions
// Notification styles are now centralized in shopglut-notification.css
jQuery(document).ready(function($) {
    if (!$('#shopglut-frontend-styles').length) {
        $('head').append(`
            <style id="shopglut-frontend-styles">
                /* Button states */
                .add-to-cart-btn.loading,
                .quick-add-btn.loading {
                    opacity: 0.7;
                    cursor: not-allowed;
                }

                .add-to-cart-btn.added,
                .quick-add-btn.added {
                    background-color: #28a745 !important;
                }

                /* Thumbnail interactions */
                .thumbnail-item {
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .thumbnail-item:hover {
                    opacity: 0.8;
                }

                .thumbnail-item.active {
                    border: 2px solid #8b5cf6;
                }

                /* Attribute interactions */
                .color-swatch,
                .size-button,
                .attribute-value {
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .color-swatch:hover,
                .size-button:hover,
                .attribute-value:hover {
                    transform: scale(1.05);
                }

                .color-swatch.active,
                .size-button.active,
                .attribute-value.active {
                    transform: scale(1.1);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                }

                /* Quantity selector improvements */
                .qty-decrease,
                .qty-increase {
                    cursor: pointer;
                    user-select: none;
                    transition: all 0.2s ease;
                }

                .qty-decrease:hover,
                .qty-increase:hover {
                    background-color: #f8f9fa;
                }

                /* Wishlist and Compare states */
                .wishlist-btn.in-wishlist,
                .compare-btn.in-compare {
                    color: #dc3545;
                }

                /* Lightbox Styles */
                .shopglut-lightbox-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.9);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    animation: fadeIn 0.3s ease;
                }

                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }

                .shopglut-lightbox-container {
                    position: relative;
                    max-width: 90%;
                    max-height: 90%;
                }

                .shopglut-lightbox-image {
                    max-width: 100%;
                    max-height: 90vh;
                    display: block;
                    border-radius: 4px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
                }

                .shopglut-lightbox-close {
                    position: absolute;
                    top: -40px;
                    right: -10px;
                    background: transparent;
                    border: none;
                    color: white;
                    font-size: 36px;
                    font-weight: bold;
                    cursor: pointer;
                    padding: 0;
                    width: 40px;
                    height: 40px;
                    line-height: 1;
                    transition: transform 0.2s ease;
                }

                .shopglut-lightbox-close:hover {
                    transform: scale(1.1);
                }
            </style>
        `);
    }
});