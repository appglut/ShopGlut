(function($) {
    'use strict';

    // Get settings from localized script
    const settings = window.shopglutComparisonSettings || {
        minProductsShowBar: 1,
        maxProductsCompare: 4,
        storageMethod: 'localstorage',
        cookieExpiryDays: 30,
        enableAnimations: true,
        animationSpeed: 300,
        showNotifications: true,
        notificationPosition: 'top-right',
        notificationDuration: 3000
    };

    // Comparison products storage
    let comparisonProducts = loadComparisonProducts();

    // Load products based on storage method
    function loadComparisonProducts() {
        if (settings.storageMethod === 'cookie') {
            return getCookie('shopglut_comparison_products') || [];
        } else if (settings.storageMethod === 'session') {
            return JSON.parse(sessionStorage.getItem('shopglut_comparison_products') || '[]');
        } else {
            return JSON.parse(localStorage.getItem('shopglut_comparison_products') || '[]');
        }
    }

    // Save products based on storage method
    function saveComparisonProducts(products) {
        if (settings.storageMethod === 'cookie') {
            setCookie('shopglut_comparison_products', products, settings.cookieExpiryDays);
        } else if (settings.storageMethod === 'session') {
            sessionStorage.setItem('shopglut_comparison_products', JSON.stringify(products));
        } else {
            localStorage.setItem('shopglut_comparison_products', JSON.stringify(products));
        }
    }

    // Cookie helper functions
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + JSON.stringify(value) + ';expires=' + expires.toUTCString() + ';path=/';
    }

    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                try {
                    return JSON.parse(c.substring(nameEQ.length, c.length));
                } catch (e) {
                    return [];
                }
            }
        }
        return null;
    }

    // Show notification - uses centralized ShopGlutNotification utility
    function showNotification(message, type = 'success') {
        if (!settings.showNotifications) {
            return;
        }

        // Use centralized ShopGlutNotification utility
        if (typeof ShopGlutNotification !== 'undefined') {
            const positions = settings.notificationPosition.split('-');
            ShopGlutNotification.show(message, type, {
                position: settings.notificationPosition,
                duration: settings.notificationDuration
            });
            return;
        }

        // Fallback if centralized utility not loaded
        const notification = $('<div class="shopglut-notification ' + type + '">' + message + '</div>');
        notification.css({
            'position': 'fixed',
            'z-index': '999999',
            'padding': '15px 20px',
            'background': type === 'success' ? '#10b981' : '#ef4444',
            'color': '#fff',
            'border-radius': '4px',
            'box-shadow': '0 2px 10px rgba(0,0,0,0.2)',
            'font-size': '14px',
            'opacity': '0',
            'transform': 'translateY(-20px)',
            'transition': 'all ' + settings.animationSpeed + 'ms ease'
        });

        // Position notification
        const positions = settings.notificationPosition.split('-');
        if (positions[0] === 'top') {
            notification.css('top', '20px');
        } else {
            notification.css('bottom', '20px');
        }
        if (positions[1] === 'right') {
            notification.css('right', '20px');
        } else {
            notification.css('left', '20px');
        }

        $('body').append(notification);

        // Animate in
        setTimeout(function() {
            notification.css({
                'opacity': '1',
                'transform': 'translateY(0)'
            });
        }, 10);

        // Auto remove
        setTimeout(function() {
            notification.css({
                'opacity': '0',
                'transform': 'translateY(-20px)'
            });
            setTimeout(function() {
                notification.remove();
            }, settings.animationSpeed);
        }, settings.notificationDuration);
    }

    // Initialize
    $(document).ready(function() {
        updateComparisonUI();
        initializeButtons();
        createComparisonModal();
    });

    // Create comparison modal HTML
    function createComparisonModal() {
        if ($('#shopglut-comparison-modal').length) return;

        const loadingGif = window.shopglutComparisonData?.loadingGif || '';

        const modalHTML = `
            <div id="shopglut-comparison-modal" class="shopglut-modal" style="display: none;">
                <div class="shopglut-modal-overlay"></div>
                <div class="shopglut-modal-content">
                    <button class="shopglut-modal-close">&times;</button>
                    <div class="shopglut-modal-body">
                        <div id="shopglut-comparison-table-container"></div>
                        <div class="shopglut-modal-loader" style="display: none;">
                            <div class="loader-container">
                                <img src="${loadingGif}" alt="Loading" class="loader-image">
                                <div class="loader-dash-circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHTML);

        // Add modal styles
        const modalStyles = `
            <style>
                .shopglut-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999999; opacity: 0; visibility: hidden; transition: opacity 0.25s ease, visibility 0.25s ease; }
                .shopglut-modal.active { opacity: 1; visibility: visible; }
                .shopglut-modal-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); transition: opacity 0.25s ease; }
                .shopglut-modal-content { position: relative; max-width: 90%; min-height: 400px; max-height: 90vh; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
                .shopglut-modal:not(.active) .shopglut-modal-content { opacity: 0; }
                .shopglut-modal.active .shopglut-modal-content { opacity: 1; transition: opacity 0.25s ease; }
                .shopglut-modal-close { position: absolute; top: 10px; right: 20px; z-index: 100; background: #fff; border: none; font-size: 28px; cursor: pointer; color: #666; line-height: 1; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s ease; }
                .shopglut-modal-close:hover { color: #000; background: #f3f4f6; transform: scale(1.1); }
                .shopglut-modal-body { padding: 20px; padding-top: 50px; min-height: 400px; max-height: 90vh; overflow-y: auto; position: relative; }
                .shopglut-modal-body .comparison-header { padding-top: 0; }
                .shopglut-modal-loader { position: absolute; top: 0; left: 0; width: 100%; height: 100%; min-height: 400px; background: rgba(255,255,255,0.95); display: none; align-items: center; justify-content: center; z-index: 50; }
                .shopglut-modal-loader .loader-container { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; z-index: 999; }
                .shopglut-modal-loader .loader-image { width: 80%; height: 80%; position: relative; z-index: 1; animation: breathe 1.7s ease-in-out infinite; }
                .shopglut-modal-loader .loader-dash-circle { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 3px dashed #0668c4; border-radius: 50%; animation: rotate 6.4s linear infinite; transform-origin: center center; z-index: 0; }
                @keyframes breathe { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
                @keyframes rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

                /* Override WooCommerce star rating styles in comparison modal */
                .shopglut-modal .star-rating { float: none !important; margin-right: 0 !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; vertical-align: middle !important; width: auto !important; }
                .shopglut-modal .woocommerce .star-rating { width: auto !important; letter-spacing: normal !important; }
                .shopglut-modal .star-rating .review-rating { display: inline-block !important; }
                .shopglut-modal .star-rating .review-rating .star-rating { width: 5.4em !important; height: 1em !important; position: relative !important; font-size: 1em !important; }
                .shopglut-modal .star-rating .review-rating .star-rating span { position: absolute !important; top: 0 !important; left: 0 !important; overflow: hidden !important; padding: 0 !important; font-size: 0 !important; }
                .shopglut-modal .star-rating .review-rating .star-rating strong { font-size: 0 !important; }
                .shopglut-modal .star-rating .rating-count { display: inline-block !important; font-size: 0.9em !important; color: #666 !important; margin-left: 0 !important; }
            </style>
        `;
        $('head').append(modalStyles);
    }

    // Initialize button event handlers
    function initializeButtons() {
        // Add to comparison button click (skip template1 icon-only buttons)
        $(document).on('click', '.shopglut-add-to-comparison:not(.compare), .shopglut-add-to-comparison-single', function(e) {
            e.preventDefault();
            console.log('ShopGlut Comparison: Button clicked!');
            const $button = $(this);
            const productId = parseInt($button.data('product-id'));
            const addedText = $button.data('added-text') || 'Remove from Compare';
            const defaultText = $button.data('default-text') || 'Add to Compare';
            console.log('ShopGlut Comparison: Product ID:', productId);

            if (comparisonProducts.includes(productId)) {
                // Remove from comparison
                comparisonProducts = comparisonProducts.filter(id => id !== productId);
                $button.removeClass('added');
                showNotification('Product removed from comparison', 'success');

                // Update button content while preserving icon
                const $icon = $button.find('i').clone();
                const iconPosition = $icon.length && $button.html().indexOf('<i') === 0 ? 'left' : 'right';

                if ($icon.length) {
                    $icon.removeClass('fa-check').addClass($icon.attr('class').match(/fa-[a-z-]+/)?.[0] || 'fa-exchange-alt');
                    if (iconPosition === 'left') {
                        $button.html($icon[0].outerHTML + ' ' + defaultText);
                    } else {
                        $button.html(defaultText + ' ' + $icon[0].outerHTML);
                    }
                } else {
                    $button.text(defaultText);
                }
            } else {
                // Add to comparison (use max from settings)
                if (comparisonProducts.length >= settings.maxProductsCompare) {
                    showNotification('You can compare maximum ' + settings.maxProductsCompare + ' products at a time.', 'error');
                    return;
                }
                comparisonProducts.push(productId);
                $button.addClass('added');
                showNotification('Product added to comparison', 'success');

                // Update button content while preserving icon
                const $icon = $button.find('i').clone();
                const iconPosition = $icon.length && $button.html().indexOf('<i') === 0 ? 'left' : 'right';

                if ($icon.length) {
                    $icon.removeClass().addClass('fa-check');
                    if (iconPosition === 'left') {
                        $button.html($icon[0].outerHTML + ' ' + addedText);
                    } else {
                        $button.html(addedText + ' ' + $icon[0].outerHTML);
                    }
                } else {
                    $button.text(addedText);
                }
            }

            // Save using configured storage method
            saveComparisonProducts(comparisonProducts);
            updateComparisonUI();
        });

        // Compare now button click - Show modal
        $(document).on('click', '#shopglut-compare-now-btn', function(e) {
            e.preventDefault();
            if (comparisonProducts.length < 2) {
                alert('Please select at least 2 products to compare.');
                return;
            }
            showComparisonModal();
        });

        // Clear comparison button click
        $(document).on('click', '#shopglut-clear-comparison-btn', function(e) {
            e.preventDefault();
            comparisonProducts = [];
            saveComparisonProducts(comparisonProducts);
            updateComparisonUI();
            showNotification('All products cleared from comparison', 'success');
        });

        // Remove product from floating bar
        $(document).on('click', '.shopglut-remove-comparison-product', function(e) {
            e.preventDefault();
            const productId = parseInt($(this).data('product-id'));
            comparisonProducts = comparisonProducts.filter(id => id !== productId);
            saveComparisonProducts(comparisonProducts);
            updateComparisonUI();
            showNotification('Product removed from comparison', 'success');

            // Update button state
            $(`.shopglut-add-to-comparison[data-product-id="${productId}"], .shopglut-add-to-comparison-single[data-product-id="${productId}"]`)
                .removeClass('added')
                .each(function() {
                    const defaultText = $(this).data('default-text') || 'Add to Compare';
                    $(this).text(defaultText);
                    if ($(this).find('i').length) {
                        $(this).find('i').removeClass('fa-check').addClass('fa-exchange-alt');
                    }
                });
        });

        // Close modal handlers
        $(document).on('click', '.shopglut-modal-close, .shopglut-modal-overlay', function(e) {
            e.preventDefault();
            const $modal = $('#shopglut-comparison-modal');
            $modal.removeClass('active');
            setTimeout(function() {
                $modal.hide();
            }, 300);
        });

        // Remove product from comparison table (inside modal)
        $(document).on('click', '.remove-product', function(e) {
            e.preventDefault();
            const productId = parseInt($(this).data('product-id'));
            comparisonProducts = comparisonProducts.filter(id => id !== productId);
            saveComparisonProducts(comparisonProducts);
            updateComparisonUI();
            showNotification('Product removed from comparison', 'success');

            // Update button state
            $(`.shopglut-add-to-comparison[data-product-id="${productId}"], .shopglut-add-to-comparison-single[data-product-id="${productId}"]`)
                .removeClass('added')
                .each(function() {
                    const defaultText = $(this).data('default-text') || 'Add to Compare';
                    $(this).text(defaultText);
                    if ($(this).find('i').length) {
                        $(this).find('i').removeClass('fa-check').addClass('fa-exchange-alt');
                    }
                });

            // Reload modal content if still have products
            if (comparisonProducts.length >= 2) {
                showComparisonModal();
            } else {
                const $modal = $('#shopglut-comparison-modal');
                $modal.removeClass('active');
                setTimeout(function() {
                    $modal.hide();
                }, 300);
                if (comparisonProducts.length < 2) {
                    showNotification('At least 2 products required for comparison', 'error');
                }
            }
        });

        // Clear all products from comparison
        $(document).on('click', '.clear-all-btn', function(e) {
            e.preventDefault();
            comparisonProducts = [];
            saveComparisonProducts(comparisonProducts);
            updateComparisonUI();
            showNotification('All products cleared from comparison', 'success');
            $('#shopglut-comparison-modal').fadeOut();
        });

        // Add to cart from comparison table
        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();
            const $button = $(this);
            const productId = parseInt($button.data('product-id'));

            if (!productId) {
                showNotification('Invalid product', 'error');
                return;
            }

            // Disable button during AJAX
            $button.prop('disabled', true).text('Adding...');

            // Add to cart via AJAX using WooCommerce AJAX endpoint
            $.ajax({
                url: window.shopglutComparisonData?.ajaxUrl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    showNotification('Product added to cart', 'success');

                    // Get cart URL from WooCommerce or fallback to default
                    const cartUrl = window.shopglutComparisonData?.cartUrl || wc_add_to_cart_params?.cart_url || '/cart';

                    // Change button to "Go to Cart" link
                    $button.prop('disabled', false)
                        .removeClass('add-to-cart-btn')
                        .addClass('goto-cart-btn')
                        .text('Go to Cart')
                        .css({
                            'background': '#0668c4',
                            'cursor': 'pointer'
                        });

                    // Trigger WooCommerce added_to_cart event
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                    // Update WooCommerce cart fragments
                    if (response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }

                    // Store cart URL on button for click handler
                    $button.data('cart-url', cartUrl);
                },
                error: function() {
                    showNotification('Failed to add product to cart', 'error');
                    $button.prop('disabled', false).text('Add to Cart');
                }
            });
        });

        // Handle "Go to Cart" button click
        $(document).on('click', '.goto-cart-btn', function(e) {
            e.preventDefault();
            const cartUrl = $(this).data('cart-url');
            if (cartUrl) {
                window.open(cartUrl, '_blank');
            }
        });
    }

    // Update comparison UI (floating bar)
    function updateComparisonUI() {
        // Reload products from storage (in case updated externally)
        comparisonProducts = loadComparisonProducts();

        const $floatingBar = $('#shopglut-floating-comparison-bar');
        const $count = $('#shopglut-comparison-count');
        const $products = $('#shopglut-comparison-products');

        // Use settings for min products to show bar
        if (comparisonProducts.length >= settings.minProductsShowBar) {
            if (settings.enableAnimations) {
                $floatingBar.fadeIn(settings.animationSpeed);
            } else {
                $floatingBar.show();
            }
            $count.text(comparisonProducts.length);
            $products.html('');

            // Fetch product data via AJAX
            if (comparisonProducts.length > 0) {
                $.ajax({
                    url: window.shopglutComparisonData?.ajaxUrl || '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'shopglut_get_comparison_products',
                        product_ids: comparisonProducts,
                        nonce: window.shopglutComparisonData?.nonce || ''
                    },
                    success: function(response) {
                        if (response.success && response.data.products) {
                            $products.html('');
                            response.data.products.forEach(function(product) {
                                $products.append(`
                                    <div class="comparison-product-item" style="position: relative; padding: 5px 10px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; gap: 8px; max-width: 200px;">
                                        ${product.image ? `<img src="${product.image}" alt="${product.name}" style="width: 30px; height: 30px; object-fit: cover; border-radius: 3px;">` : ''}
                                        <span style="font-size: 12px; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${product.name}">${product.name}</span>
                                        <button class="shopglut-remove-comparison-product" data-product-id="${product.id}" style="background: #ef4444; color: white; border: none; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; padding: 0; line-height: 1; flex-shrink: 0;">×</button>
                                    </div>
                                `);
                            });
                        }
                    },
                    error: function() {
                        // Fallback to product IDs if AJAX fails
                        comparisonProducts.forEach(function(productId) {
                            $products.append(`
                                <div class="comparison-product-item" style="position: relative; padding: 5px 10px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 12px;">Product #${productId}</span>
                                    <button class="shopglut-remove-comparison-product" data-product-id="${productId}" style="background: #ef4444; color: white; border: none; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; padding: 0;">×</button>
                                </div>
                            `);
                        });
                    }
                });
            }
        } else {
            if (settings.enableAnimations) {
                $floatingBar.fadeOut(settings.animationSpeed);
            } else {
                $floatingBar.hide();
            }
        }

        // Update button states (skip template1 icon-based buttons)
        $('.shopglut-add-to-comparison:not(.compare), .shopglut-add-to-comparison-single').each(function() {
            const $button = $(this);
            const productId = $button.data('product-id');
            const addedText = $button.data('added-text') || 'Remove from Compare';
            const defaultText = $button.data('default-text') || 'Add to Compare';

            if (comparisonProducts.includes(productId)) {
                $button.addClass('added');

                // Update button content while preserving icon
                const $icon = $button.find('i').clone();
                const iconPosition = $icon.length && $button.html().indexOf('<i') === 0 ? 'left' : 'right';

                if ($icon.length) {
                    $icon.removeClass().addClass('fa-check');
                    if (iconPosition === 'left') {
                        $button.html($icon[0].outerHTML + ' ' + addedText);
                    } else {
                        $button.html(addedText + ' ' + $icon[0].outerHTML);
                    }
                } else {
                    $button.text(addedText);
                }
            } else {
                $button.removeClass('added');

                // Update button content while preserving icon
                const $icon = $button.find('i').clone();
                const iconPosition = $icon.length && $button.html().indexOf('<i') === 0 ? 'left' : 'right';

                if ($icon.length) {
                    // Get all original icon classes (e.g., "fas fa-exchange-alt")
                    const originalIconClass = $icon.attr('class') || 'fas fa-exchange-alt';
                    // Remove any 'added' state classes and restore original
                    $icon.attr('class', originalIconClass.replace('fa-check', '').replace(/\s+/g, ' ').trim() || 'fas fa-exchange-alt');
                    if (iconPosition === 'left') {
                        $button.html($icon[0].outerHTML + ' ' + defaultText);
                    } else {
                        $button.html(defaultText + ' ' + $icon[0].outerHTML);
                    }
                } else {
                    $button.text(defaultText);
                }
            }
        });
    }

    // Show comparison modal with AJAX loaded content
    function showComparisonModal() {
        const $modal = $('#shopglut-comparison-modal');
        const $container = $('#shopglut-comparison-table-container');
        const $loader = $('.shopglut-modal-loader');

        // Clear content and prepare loader
        $container.html('');
        $loader.css('display', 'flex');

        // Show modal and trigger animation properly
        $modal.css('display', 'block');

        // Force reflow to ensure CSS transitions work properly
        $modal[0].offsetHeight;

        // Trigger animation after reflow
        requestAnimationFrame(function() {
            $modal.addClass('active');
        });

        // Detect page context
        const pageContext = {
            is_shop: $('body').hasClass('woocommerce-shop') || $('body').hasClass('post-type-archive-product'),
            is_product: $('body').hasClass('single-product'),
            is_category: $('body').hasClass('tax-product_cat'),
            is_tag: $('body').hasClass('tax-product_tag'),
            category_id: $('body').hasClass('tax-product_cat') ? ($('body').attr('class').match(/term-(\d+)/)?.[1] || 0) : 0,
            tag_id: $('body').hasClass('tax-product_tag') ? ($('body').attr('class').match(/term-(\d+)/)?.[1] || 0) : 0,
            product_id: $('body').hasClass('single-product') ? ($('.product').data('product-id') || 0) : 0
        };

        // Make AJAX request to render comparison table
        $.ajax({
            url: window.shopglutComparisonData?.ajaxUrl || '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'shopglut_render_comparison_table',
                product_ids: comparisonProducts,
                page_context: pageContext
            },
            success: function(response) {
                setTimeout(function() {
                    $loader.fadeOut(200, function() {
                        if (response.success && response.data.html) {
                            $container.html(response.data.html);
                        } else {
                            $container.html('<p class="error">' + (response.data?.message || 'Failed to load comparison table.') + '</p>');
                        }
                    });
                }, 300);
            },
            error: function() {
                setTimeout(function() {
                    $loader.fadeOut(200, function() {
                        $container.html('<p class="error">Failed to load comparison table. Please try again.</p>');
                    });
                }, 300);
            }
        });
    }

    // Expose functions globally for template1 integration
    window.shopglutUpdateComparisonUI = updateComparisonUI;
    window.shopglutLoadComparisonProducts = loadComparisonProducts;

})(jQuery);
