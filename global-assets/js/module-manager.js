jQuery(document).ready(function($) {
    
    // Handle module toggle switches
    $('.shopglut-module-toggle').on('change', function() {
        const $toggle = $(this);
        const $moduleCard = $toggle.closest('.shopglut-module-card');
        const module = $toggle.data('module');
        const enabled = $toggle.is(':checked');
        
        // Store original dimensions BEFORE any changes
        const originalWidth = $moduleCard.outerWidth();
        const originalHeight = $moduleCard.outerHeight();

        // Apply dimensions immediately to prevent collapse
        $moduleCard.css({
            'width': originalWidth + 'px',
            'height': originalHeight + 'px',
            'min-width': originalWidth + 'px',
            'min-height': originalHeight + 'px'
        });

        // Add preservation class and show loading state
        $moduleCard.addClass('shopglut-preserve-dimensions');
        $toggle.prop('disabled', true);
        $moduleCard.addClass('shopglut-loading');
        
        $.ajax({
            url: shopglut_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'toggle_shopglut_module',
                module: module,
                enabled: enabled,
                nonce: shopglut_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotification(response.data.message, 'success');

                    // Check if we're on the WooCommerce Builder Modules page
                    if (window.location.href.indexOf('shopg_woocommerce_builder') !== -1) {
                        // Reload the page after a short delay to show the success message
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // For other pages, update UI based on enabled state (existing logic)
                        // if (enabled) {
                        //    // $moduleCard.removeClass('shopglut-module-disabled');
                        //   //  $moduleCard.find('.disable-link').removeClass('disable-link');
                        //   //  $moduleCard.find('.module-disabled-overlay').remove();
                        // } else {
                        //     $moduleCard.addClass('shopglut-module-disabled');
                        //     $moduleCard.find('a').addClass('disable-link');
                        //     // Add overlay if it doesn't exist
                        //     if ($moduleCard.find('.module-disabled-overlay').length === 0) {
                        //         $moduleCard.find('.grid-item').append('<div class="module-disabled-overlay"></div>');
                        //     }
                        // }
                    }
                } else {
                    // Revert toggle state on error
                    $toggle.prop('checked', !enabled);
                    showNotification(response.data.message || 'Unknown error occurred.', 'error');
                }
            },
            error: function(xhr, status, error) {
                // Revert toggle state on error
                $toggle.prop('checked', !enabled);
                showNotification('An error occurred while toggling the module.', 'error');
            },
            complete: function() {
                // Remove loading state and reset dimensions
                $toggle.prop('disabled', false);
                $moduleCard.removeClass('shopglut-loading');
                $moduleCard.removeClass('shopglut-preserve-dimensions');

                // Clear ALL inline dimension styles after loading
                $moduleCard.css({
                    'width': '',
                    'height': '',
                    'min-width': '',
                    'min-height': ''
                });
            }
        });
    });
    
        
    // Show notification function - uses centralized ShopGlutNotification utility
    function showNotification(message, type) {
        if (typeof ShopGlutNotification !== 'undefined') {
            ShopGlutNotification.show(message, type, { duration: 3000 });
        } else {
            // Fallback if centralized utility not loaded
            const $notification = $('<div class="shopglut-notification shopglut-notification-' + type + '">' + message + '</div>');
            $('body').append($notification);
            setTimeout(function() { $notification.addClass('show'); }, 100);
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() { $notification.remove(); }, 300);
            }, 3000);
        }
    }
});