jQuery(document).ready(function($) {
    'use strict';

    // Handle save badge button
    $(document).on('click', '#save-badge-editor', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $form = $('#badge-editor-form');

        if (!$form.length) {
            // Try to find any form containing badge settings
            $form = $('form').has('[name*="shopg_product_badge_settings"]').first();
        }

        if (!$form.length) {
            alert('Badge form not found');
            return;
        }

        // Create form data
        var formData = new FormData($form[0]);

        // Add required nonce if not present
        if (!formData.has('shopg_productbadge_nonce')) {
            formData.append('shopg_productbadge_nonce', shopglut_badges.nonce);
        }

        // Show loading state
        $button.prop('disabled', true).text('Saving...');

        $.ajax({
            url: shopglut_badges.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotice('Badge saved successfully!', 'success');

                    // Update badge ID if it's a new badge
                    if (response.data.badge_id) {
                        $('input[name="shopg_badge_id"]').val(response.data.badge_id);

                        // Reload the preview from server to get the correct saved values
                        reloadPreviewFromServer(response.data.badge_id);
                    }
                } else {
                    showNotice(response.data.message || 'Failed to save badge', 'error');
                }
            },
            error: function(xhr, status, error) {
                showNotice('AJAX error: ' + error, 'error');
            },
            complete: function() {
                // Restore button state
                $button.prop('disabled', false).text('Save Badge');
            }
        });
    });

    // Function to reload preview from server after save
    function reloadPreviewFromServer(badgeId) {
        if (!badgeId) {
            return;
        }

        // Get the current URL and update/add the badge_id parameter
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('badge_id', badgeId);
        currentUrl.searchParams.set('editor', 'product_badges');
        currentUrl.searchParams.set('page', 'shopglut_enhancements');

        // Fetch the updated preview from the server
        $.ajax({
            url: shopglut_badges.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_get_badge_preview',
                badge_id: badgeId,
                nonce: shopglut_badges.nonce
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    // Replace the preview container with the updated preview
                    var $previewContainer = $('.shopglut-badge-preview-wrapper');
                    if ($previewContainer.length) {
                        $previewContainer.replaceWith(response.data.html);
                    }
                }
            }
        });
    }

    // Function to update preview after save
    function updatePreviewAfterSave() {
        // Get all badge preview elements and update each individually
        var $badges = $('.shopglut-badge-preview');

        if ($badges.length === 0) {
            return;
        }

        $badges.each(function() {
            var $badge = $(this);
            var badgeType = $badge.data('badge-type') || $badge.attr('class').match(/shopglut-badge-type-([\w_]+)/);

            if (badgeType && badgeType[1]) {
                badgeType = badgeType[1];
            } else {
                return; // Skip if we can't determine badge type
            }

            var badgeText = '';
            var backgroundColor = $badge.css('background-color') || '#ff0000';
            var textColor = $badge.css('color') || '#ffffff';
            var fontSize = $badge.css('font-size') || '12px';
            var fontWeight = $badge.css('font-weight') || '700';
            var borderRadius = $badge.css('border-radius') || '3px';
            var padding = $badge.css('padding') || '5px 10px';

            // Get badge text from form input - match the actual form field name structure
            // The field names follow pattern: shopg_product_badge_settings[product_badge-settings][{type}_badge_text]
            var textInput = $('input[name*="' + badgeType + '_badge_text"]');
            if (textInput.length > 0) {
                badgeText = textInput.first().val();
            }

            // If still no text, keep the existing preview text (don't override with hardcoded defaults)
            if (!badgeText) {
                badgeText = $badge.text();
            }

            // Only update if we have values from the form
            if (textInput.length > 0) {
                var bgInput = $('input[name*="' + badgeType + '_badge_bg"]');
                var colorInput = $('input[name*="' + badgeType + '_badge_text_color"]');
                var fontSizeInput = $('input[name*="' + badgeType + '_badge_font_size"]');
                var borderRadiusInput = $('input[name*="' + badgeType + '_badge_border_radius"]');

                if (bgInput.length) backgroundColor = bgInput.first().val() || backgroundColor;
                if (colorInput.length) textColor = colorInput.first().val() || textColor;
                if (fontSizeInput.length) fontSize = fontSizeInput.first().val() + 'px' || fontSize;
                if (borderRadiusInput.length) borderRadius = borderRadiusInput.first().val() + 'px' || borderRadius;

                // Build inline styles
                var styles = [
                    'background-color: ' + backgroundColor,
                    'color: ' + textColor,
                    'font-size: ' + fontSize,
                    'font-weight: ' + fontWeight,
                    'border-radius: ' + borderRadius,
                    'padding: ' + padding,
                    'display: inline-block',
                    'white-space: nowrap'
                ].join('; ');

                $badge.attr('style', styles).text(badgeText);
            }
        });
    }

    // Show notice function
    function showNotice(message, type) {
        var className = type === 'success' ? 'notice-success' : 'notice-error';
        var $notice = $('<div class="notice ' + className + ' is-dismissible"><p>' + message + '</p></div>');

        // Add to page
        $('.wrap').prepend($notice);

        // Auto remove after 3 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);

        // Make dismissible
        $notice.on('click', '.notice-dismiss', function() {
            $notice.remove();
        });
    }

    // Live preview updates disabled - preview is only updated on page load and after save
    // This prevents issues with preview changing while typing

    // Tab switching functionality
    function initTabSwitching() {
        $('.agl-tabbed-nav a').off('click').on('click', function(e) {
            e.preventDefault();

            var $tabLink = $(this);
            var $navContainer = $tabLink.closest('.agl-tabbed-nav');
            var $contentContainer = $navContainer.next('.agl-tabbed-contents');

            // Remove active class from all tabs and contents
            $navContainer.find('a').removeClass('agl-tabbed-active');
            $contentContainer.find('.agl-tabbed-content').addClass('hidden').hide();

            // Add active class to clicked tab
            $tabLink.addClass('agl-tabbed-active');

            // Show corresponding content
            var tabIndex = $tabLink.index();
            $contentContainer.find('.agl-tabbed-content').eq(tabIndex).removeClass('hidden').show();
        });
    }

    // Initialize tab switching
    initTabSwitching();

    // Re-initialize tab switching after AJAX save
    var originalAjaxSuccess = $.ajaxSettings.success;
    $.ajaxSetup({
        success: function(data, textStatus, jqXHR) {
            // Call original success handler first
            if (originalAjaxSuccess) {
                originalAjaxSuccess.call(this, data, textStatus, jqXHR);
            }

            // Re-initialize tabs after AJAX complete
            setTimeout(function() {
                initTabSwitching();
            }, 100);
        }
    });

});