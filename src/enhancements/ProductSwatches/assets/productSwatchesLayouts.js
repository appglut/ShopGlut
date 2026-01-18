// Fixed JavaScript with proper security and consistency for Product Swatches
jQuery(document).ready(function($) {

    // Wait for everything to fully load including images, styles, and scripts
    $(window).on('load', function() {
        // Add a small delay to ensure everything is rendered
        setTimeout(function() {
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
        }, 500); // 500ms delay to ensure complete loading
    });

    // Fallback: Hide loader after maximum 10 seconds even if something doesn't load
    setTimeout(function() {
        $(".loader-overlay").css({"display": "none", "opacity": "0"});
        $(".loader-container").hide();
    }, 10000);

    // Reset settings functionality
    $("#productSwatches-reset-settings-button").on("click", function (e) {
        e.preventDefault();

        // Show confirmation dialog
        if (!confirm('Are you sure you want to reset all settings? This action cannot be undone.')) {
            return;
        }

        // Show loading
        $(".loader-overlay").css({"display": "flex", "opacity": "1"});
        $(".loader-container").show();

        var postid = $("#shopg_shop_layoutid").val();

        // Fallback: try different layout ID sources
        if (!postid) {
            postid = $("input[name='shopg_shop_layoutid']").val();
        }
        if (!postid) {
            postid = shopglut_product_swatches_vars.layout_id;
        }
        if (!postid) {
            postid = $("input[name='layout_id']").val();
        }

        console.log('Reset: Layout ID found:', postid);

        var nonce = $("input[name='shopg_productswatches_layouts_nonce']").val();

        // Fallback: try different nonce field names
        if (!nonce) {
            nonce = $("#shopg_productswatches_layouts_nonce").val();
        }
        if (!nonce) {
            nonce = $("input[name='_wpnonce']").val();
        }
        if (!nonce) {
            nonce = shopglut_product_swatches_vars.nonce;
        }

        if (!nonce) {
            console.error("Security nonce not found");
            showNotification('Security error: nonce not found', 'error');
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
            return;
        }

        if (!postid) {
            console.error("Layout ID not found");
            showNotification('Error: Layout ID not found', 'error');
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
            return;
        }

        // AJAX request to reset settings
        $.ajax({
            type: "POST",
            url: shopglut_product_swatches_vars.ajaxurl,
            data: {
                action: 'shopglut_reset_product_swatches_layout',
                shopg_productswatches_layouts_nonce: nonce,
                shopg_shop_layoutid: postid
            },
            timeout: 30000,
            success: function (response) {
                $(".loader-overlay").css({"display": "none", "opacity": "0"});
                $(".loader-container").hide();

                if (response.success) {
                    // Reload the page instantly to show reset settings
                   window.location.reload();
                } else {
                    var errorMsg = response.data ? response.data : 'Unknown error occurred';
                    console.error("Failed to reset settings:", errorMsg);
                    showNotification('Failed to reset: ' + errorMsg, 'error');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".loader-overlay").css({"display": "none", "opacity": "0"});
                $(".loader-container").hide();

                console.error("Reset request failed:", {
                    status: textStatus,
                    error: errorThrown,
                    responseText: jqXHR.responseText
                });

                showNotification('Reset failed: ' + textStatus, 'error');
            }
        });
    });

    // Main form submission - Product Swatches
    $("#productSwatchesLayout-publishing-action #publish").on("click", function (e) {
        e.preventDefault();

        // Validate required fields before processing
        var layoutName = $("#layout_name").val();
        var layoutTemplate = $("#layout_template").val();

        if (!layoutName || !layoutTemplate) {
            alert('Please fill in all required fields (Layout Name and Template).');
            return;
        }

        // Check if nonce exists
        var nonce = $('input[name="shopg_productswatches_layouts_nonce"]').val();

        $(".loader-overlay").css({"display": "flex", "opacity": "1"});
        $(".loader-container").show();

        var postid = $("#shopg_shop_layoutid").val();
        var layoutType = $("#layout_type").val();

        // Use Product Swatches action name
        var actionToUse = 'shopglut_save_product_swatches_layout';

        var shopgOptionsSettings = {};

        function setNestedProperty(obj, keys, value) {
            var lastKey = keys.pop();
            var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), obj);
            nestedObj[lastKey] = value;
        }

        function setNestedArrayProperty(obj, keys, value) {
            var lastKey = keys.pop();
            var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), obj);

            if (!nestedObj[lastKey]) {
                nestedObj[lastKey] = [];
            }

            if (Array.isArray(nestedObj[lastKey]) && !nestedObj[lastKey].includes(value)) {
                nestedObj[lastKey].push(value);
            }
        }

        // Process form inputs from product swatches settings
        $("#shopg-productswatches-layout-settings :input, #shopg-productswatches-layout-settings select").each(function () {
            var input = $(this);
            var name = input.attr("name");
            var value;

            // Skip invalid fields and WordPress internal fields
            if (!name ||
                name.startsWith('___shopg_productswatches_settings') ||
                name === '_pseudo' ||
                name.startsWith('agl_metabox_nonce') ||
                name.startsWith('_wp_') ||
                name === 'shopg_productswatches_layouts_nonce' ||
                name === 'shopg_shop_layoutid' ||
                name === 'layout_name' ||
                name === 'layout_template' ||
                name === 'action' ||
                name.startsWith('_ajax_') ||
                name.startsWith('closedpostboxesnonce') ||
                name.startsWith('meta-box-order-nonce')) {
                return;
            }

            // Handle different input types
            if (input.is("select")) {
                if (input.prop('multiple')) {
                    var selectedValues = input.val();
                    if (selectedValues && selectedValues.length > 0) {
                        var keys = name.replace('[]', '').split("[").map((k) => k.replace("]", ""));
                        var lastKey = keys.pop();
                        var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), shopgOptionsSettings);
                        nestedObj[lastKey] = selectedValues;
                    }
                    return;
                } else {
                    value = input.val();
                }
            }
            else if (input.is(":checkbox")) {
                if (input.is(":checked")) {
                    value = input.val();

                    if (name.endsWith('[]')) {
                        var keys = name.replace('[]', '').split("[").map((k) => k.replace("]", ""));
                        setNestedArrayProperty(shopgOptionsSettings, keys, value);
                        return;
                    }
                } else {
                    return;
                }
            }
            else if (input.is(":radio")) {
                if (input.is(":checked")) {
                    value = input.val();
                } else {
                    return;
                }
            }
            else {
                value = input.val();
            }

            if (name && !name.endsWith('[]')) {
                var keys = name.split("[").map((k) => k.replace("]", ""));
                setNestedProperty(shopgOptionsSettings, keys, value);
            }
        });


        // AJAX request with improved error handling
        $.ajax({
            type: "POST",
            url: shopglut_product_swatches_vars.ajaxurl,
            data: {
                action: actionToUse,
                shopg_productswatches_layouts_nonce: nonce,
                shopg_shop_layoutid: postid,
                layout_name: layoutName,
                layout_template: layoutTemplate,
                shopg_options_settings: JSON.stringify(shopgOptionsSettings)
            },
            timeout: 30000, // 30 second timeout
            success: function (response) {
                $(".loader-overlay").css({"display": "none", "opacity": "0"});
                $(".loader-container").hide();

                if (response.success) {
                    // Check if server requested a page reload
                    if (response.data.reload) {
                        // Reload the page instantly
                        window.location.reload();
                    } else if (response.data.html) {
                        // Show success message only for HTML updates
                        showNotification('Layout saved successfully!', 'success');
                        // Fallback: update HTML if provided (for backward compatibility)
                        $(".agl-field-preview").html(response.data.html);

                        // Reinitialize galleries if function exists
                        if (window.initShopglutGalleries) {
                            window.initShopglutGalleries();
                        }
                    }
                } else {
                    var errorMsg = response.data ? response.data : 'Unknown error occurred';
                    console.error("Failed to save data:", errorMsg);
                    showNotification('Failed to save: ' + errorMsg, 'error');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".loader-overlay").css({"display": "none", "opacity": "0"});
                $(".loader-container").hide();

                var errorMsg = 'AJAX request failed';
                if (textStatus === 'timeout') {
                    errorMsg = 'Request timed out. Please try again.';
                } else if (jqXHR.status === 403) {
                    errorMsg = 'Access denied. Please refresh the page and try again.';
                } else if (jqXHR.status === 500) {
                    errorMsg = 'Server error. Please check server logs.';
                }

                console.error("AJAX Error:", {
                    status: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText
                });

                showNotification(errorMsg, 'error');
            }
        });
    });

    // Utility function for notifications - uses centralized ShopGlutNotification utility
    function showNotification(message, type) {
        if (typeof ShopGlutNotification !== 'undefined') {
            ShopGlutNotification.show(message, type, { duration: 5000 });
        } else {
            // Fallback if centralized utility not loaded
            $('.shopglut-notification').remove();
            var notification = $('<div class="shopglut-notification shopglut-notification-' + type + '">' +
                               '<span>' + message + '</span>' +
                               '<button class="notification-close">×</button>' +
                               '</div>');
            $('body').append(notification);
            setTimeout(function() {
                notification.fadeOut(300, function() { $(this).remove(); });
            }, 5000);
            notification.find('.notification-close').on('click', function() {
                notification.fadeOut(300, function() { $(this).remove(); });
            });
        }
    }
});

// Toggle button functionality is now centralized in global-assets/js/shopglut-admin.js
// @see global-assets/js/shopglut-admin.js:initToggleButton()

// Notification styles are now centralized in shopglut-notification.css
// @see global-assets/css/shopglut-notification.css

// Initialize WordPress color picker for color fields
jQuery(document).ready(function($) {
    // Initialize all AGSHOPGLUT framework fields
    function initAGLFields($container) {
        // Initialize switchers
        $container.find('.agl-field-switcher:not(.agl-initialized)').each(function() {
            if (typeof $.fn.AGSHOPGLUT_switcher === 'function') {
                $(this).addClass('agl-initialized').AGSHOPGLUT_switcher();
            }
        });

        // Initialize button sets (radio buttons styled as buttons)
        $container.find('.agl-field-button_set .agl-siblings:not(.agl-initialized)').each(function() {
            if (typeof $.fn.agl_siblings === 'function') {
                $(this).addClass('agl-initialized').agl_siblings();
            }
        });

        // Initialize color picker on static color fields
        $container.find('.agl-color, input[type="text"].color-picker, .agl--field-color input[type="text"]:not(.wp-color-picker)').each(function() {
            if (!$(this).hasClass('wp-color-picker')) {
                $(this).wpColorPicker();
            }
        });

        // Initialize spinners
        $container.find('.agl-field-spinner:not(.agl-initialized)').each(function() {
            if (typeof $.fn.AGSHOPGLUT_spinner === 'function') {
                $(this).addClass('agl-initialized').AGSHOPGLUT_spinner();
            }
        });

        // Initialize checkboxes
        $container.find('.agl-field-checkbox .agl-checkbox:not(.agl-initialized)').each(function() {
            if (typeof $.fn.agl_checkbox === 'function') {
                $(this).addClass('agl-initialized').agl_checkbox();
            }
        });

        // Initialize icon selectors
        $container.find('.agl-field-icon_selector:not(.agl-initialized)').each(function() {
            if (typeof $.fn.AGSHOPGLUT_icon_selector === 'function') {
                $(this).addClass('agl-initialized').AGSHOPGLUT_icon_selector();
            }
        });

        // Initialize image selectors
        $container.find('.agl-field-image_selector:not(.agl-initialized)').each(function() {
            if (typeof $.fn.AGSHOPGLUT_image_selector === 'function') {
                $(this).addClass('agl-initialized').AGSHOPGLUT_image_selector();
            }
        });

        // Initialize typography fields
        $container.find('.agl-field-typography:not(.agl-initialized)').each(function() {
            if (typeof $.fn.AGSHOPGLUT_typography === 'function') {
                $(this).addClass('agl-initialized').AGSHOPGLUT_typography();
            }
        });
    }

    // Initialize on document ready
    initAGLFields($('body'));

    // Initialize on dynamically loaded fields (for AJAX/tabs)
    $(document).on('agl-field-added', function() {
        initAGLFields($('body'));
    });

    // Initialize when tabs are switched (for fields in hidden tabs)
    $(document).on('click', '.agl--tabs, .agl-tab-nav, [data-agl-tab]', function() {
        setTimeout(function() {
            initAGLFields($('body'));
        }, 100);
    });
});
