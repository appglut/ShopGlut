/**
 * Shopglut Admin JavaScript
 *
 * Handles loader overlay and settings panel toggle functionality
 */

(function($) {
    'use strict';

    /**
     * Show loader overlay - Global function
     */
    window.showLoader = function() {
        var loader = $('.loader-overlay');
        if (loader.length) {
            loader.css({
                'display': 'flex',
                'opacity': '1'
            });
        }
    };

    /**
     * Hide loader overlay - Global function
     */
    window.hideLoader = function() {
        var loader = $('.loader-overlay');
        if (loader.length) {
            loader.css('opacity', '0');
            setTimeout(function() {
                loader.css('display', 'none');
            }, 500);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        initLoader();
        initFormSubmission();
        initToggleButton();
        initShortcodeCopy();
    });

    // Toggle button functionality - unified implementation
    function initToggleButton() {
        $(document).on('click', '#toggle-settings-button', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $wrapper = $button.prevAll('.shopg-layout-settings-wrapper').first();
            var $container = $button.siblings('.shopg-admin-edit-panel, .shopg-layout-container');

            if ($wrapper.hasClass('collapsed')) {
                // Show settings
                $wrapper.removeClass('collapsed');
                $button.text('Hide').removeClass('collapsed');
                if ($container.length) {
                    $container.removeClass('collapsed');
                }
            } else {
                // Hide settings - expand panel to 100%
                $wrapper.addClass('collapsed');
                $button.text('Show').addClass('collapsed');
                if ($container.length) {
                    $container.addClass('collapsed');
                }
            }
        });
    }

    /**
     * Initialize loader functionality
     */
    function initLoader() {
        // Hide loader after page fully loads
        $(window).on('load', function() {
            window.hideLoader();
        });

        // Fallback - hide loader after 2 seconds maximum
        setTimeout(function() {
            window.hideLoader();
        }, 2000);
    }

    /**
     * Initialize form submission handlers
     */
    function initFormSubmission() {
        const form = document.getElementById('shopglut_shop_layouts');
        if (form) {
            form.addEventListener('submit', function() {
                window.showLoader();
            });
        }
    }

    /**
     * Initialize shortcode copy functionality
     */
    function initShortcodeCopy() {
        // Create notification element if it doesn't exist
        if ($('.shopglut-copy-notification').length === 0) {
            $('body').append('<div class="shopglut-copy-notification">Copied to clipboard!</div>');
        }

        // Handle click on shortcode input fields
        $(document).on('click', '.shortcode_shopg_table', function(e) {
            e.preventDefault();

            var $input = $(this);

            // Select the text
            $input.select();

            // Copy to clipboard using modern API with fallback
            try {
                // Try modern clipboard API first
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText($input.val()).then(function() {
                        showCopyNotification();
                    }).catch(function() {
                        // Fallback to execCommand
                        copyWithExecCommand($input);
                    });
                } else {
                    // Fallback for older browsers or non-secure contexts
                    copyWithExecCommand($input);
                }
            } catch (err) {
                // Final fallback
                copyWithExecCommand($input);
            }
        });
    }

    /**
     * Fallback copy method using execCommand
     */
    function copyWithExecCommand($input) {
        $input.select();
        document.execCommand('copy');
        showCopyNotification();
    }

    /**
     * Show copy notification
     */
    function showCopyNotification() {
        var $notification = $('.shopglut-copy-notification');

        $notification.addClass('show');

        setTimeout(function() {
            $notification.removeClass('show');
        }, 2000);
    }

})(jQuery);