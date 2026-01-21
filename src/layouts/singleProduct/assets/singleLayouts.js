// Fixed JavaScript with proper security and consistency
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
    $("#singleProduct-reset-settings-button").on("click", function (e) {
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
            postid = shopglut_single_product_vars.layout_id;
        }
        if (!postid) {
            postid = $("input[name='layout_id']").val();
        }

        var nonce = $("input[name='shopg_singleproduct_layouts_nonce']").val();

        // Fallback: try different nonce field names
        if (!nonce) {
            nonce = $("#shopg_singleproduct_layouts_nonce").val();
        }
        if (!nonce) {
            nonce = $("input[name='_wpnonce']").val();
        }
        if (!nonce) {
            nonce = shopglut_single_product_vars.nonce;
        }

        if (!nonce) {
            showNotification('Security error: nonce not found', 'error');
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
            return;
        }

        if (!postid) {
            showNotification('Error: Layout ID not found', 'error');
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
            return;
        }

        // AJAX request to reset settings
        $.ajax({
            type: "POST",
            url: shopglut_single_product_vars.ajaxurl,
            data: {
                action: 'shopglut_reset_single_product_layout',
                shopg_singleproduct_layouts_nonce: nonce,
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

    // Main form submission - UPDATED with security fixes
    $("#singleProductLayout-publishing-action #publish").on("click", function (e) {
        e.preventDefault();

        // Validate required fields before processing
        var layoutName = $("#layout_name").val();
        var layoutTemplate = $("#layout_template").val();

        if (!layoutName || !layoutTemplate) {
            alert('Please fill in all required fields (Layout Name and Template).');
            return;
        }

        // Check if nonce exists
        var nonce = $('input[name="shopg_singleproduct_layouts_nonce"]').val();


        $(".loader-overlay").css({"display": "flex", "opacity": "1"});
        $(".loader-container").show();

        var postid = $("#shopg_shop_layoutid").val();
        var layoutType = $("#layout_type").val();

        // Use the updated action name to match PHP changes
        var actionToUse = 'shopglut_save_single_product_layout';

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

        // Process form inputs
        $("#shopg-cart-layout-settings :input, #shopg-cart-layout-settings select").each(function () {
            var input = $(this);
            var name = input.attr("name");
            var value;

            // Skip invalid fields and WordPress internal fields
            if (!name ||
                name.startsWith('___shopg_single_product_settings') ||
                name === '_pseudo' ||
                name.startsWith('agl_metabox_nonce') ||
                name.startsWith('_wp_') ||
                name === 'shopg_singleproduct_layouts_nonce' ||
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
            url: shopglut_single_product_vars.ajaxurl, // Updated variable name
            data: {
                action: actionToUse,
                shopg_singleproduct_layouts_nonce: nonce,
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

    // Product selection AJAX - UPDATED with security fixes
    $(".agl-field.agl-field-select_products .agl-fieldset").on("click", function () {
        if ($(".agl-field.agl-field-select_products .chosen-drop .chosen-results").length > 0) {

            // Validate required data exists
            if (!shopglut_single_product_vars.layout_id || !shopglut_single_product_vars.nonce) {
                console.warn('Missing required data for product options request');
                return;
            }
            
            // Show loading spinner
            $(".agl-field.agl-field-select_products .chosen-drop").css('background', '#fff').append('<div class="loading-spinner"></div>');
            $(".agl-field.agl-field-select_products .chosen-results").addClass("filter-dis");

            $.ajax({
                url: shopglut_single_product_vars.ajaxurl, // Updated variable name
                method: "POST",
                data: {
                    action: "shopglut_singleProduct_get_product_options", // Updated action name
                    nonce: shopglut_single_product_vars.nonce,
                    layout_id: shopglut_single_product_vars.layout_id, // Fixed parameter name
                },
                timeout: 15000, // 15 second timeout
                success: function(response) {
                    if (response.success && response.data) {
                        var usedProductNames = response.data.map(product => product.name);

                        // Get currently selected products from the select element
                        var selectedProducts = [];
                        $(".agl-field.agl-field-select_products select").find('option:selected').each(function() {
                            selectedProducts.push($(this).text().trim());
                        });

                        $(".agl-field.agl-field-select_products .chosen-drop .chosen-results li").each(function() {
                            var itemText = $(this).text().trim();
                            // Disable if used in other layouts OR already selected in this layout
                            if (usedProductNames.includes(itemText) || selectedProducts.includes(itemText)) {
                                $(this).addClass("result-selected").removeClass("active-result");
                            } else {
                                $(this).addClass("active-result").removeClass("result-selected");
                            }
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    
                    if (jqXHR.status === 403) {
                        showNotification('Access denied. Please refresh the page.', 'error');
                    }
                },
                complete: function () {
                    // Remove loading spinner
                    $(".agl-field.agl-field-select_products .chosen-drop .loading-spinner").remove();
                    $(".agl-field.agl-field-select_products .chosen-drop").css('background', '');
                    $(".agl-field.agl-field-select_products .chosen-results").removeClass("filter-dis");
                }
            });
        }
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

// Notification styles are now centralized in shopglut-notification.css
// @see global-assets/css/shopglut-notification.css