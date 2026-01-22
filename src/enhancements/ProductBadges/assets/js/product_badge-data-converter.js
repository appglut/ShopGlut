/**
 * ShopGlut Order Complete Layout Data Converter Module
 *
 * Handles order complete layout form data conversion and saving
 */

(function($) {
    'use strict';

    // Create namespace if it doesn't exist
    window.ShopGlutProductBadgeLayout = window.ShopGlutProductBadgeLayout || {};

    // Data converter module namespace
    ShopGlutProductBadgeLayout.dataConverter = {

        /**
         * Convert form data to clean JSON structure
         */
        convertFormDataToJSON: function() {
            var cleanData = {};

            // Get form element
            var $form = this.getFormElement('#shopglut_product_badge_layouts');
            if (!$form.length) {
                return cleanData;
            }

            // Temporarily make hidden content visible for data collection
            var hiddenTabsData = this.showHiddenTabs($form);

            try {
                // Collect all form data generically
                cleanData = this.collectAllFormData($form);

            } catch (error) {
                console.error('Error collecting order complete layout data:', error);
            } finally {
                // Restore original hidden state of tabs
                this.restoreHiddenTabs(hiddenTabsData);
            }

            return cleanData;
        },

        /**
         * Get form element safely
         */
        getFormElement: function(selector) {
            var $form = $(selector);
            if (!$form.length) {
                $form = $('form').first(); // Fallback to first form
            }
            return $form;
        },

        /**
         * Temporarily show hidden tabs for data collection
         */
        showHiddenTabs: function($form) {
            var $hiddenTabs = $form.find('.agl-tabbed-content.hidden, .agl-tabbed-content[style*="display: none"]');
            var hiddenTabsData = [];

            $hiddenTabs.each(function() {
                var $tab = $(this);
                hiddenTabsData.push({
                    element: $tab,
                    wasHidden: $tab.hasClass('hidden'),
                    originalDisplay: $tab.css('display')
                });
                $tab.removeClass('hidden').css('display', 'block');
            });

            return hiddenTabsData;
        },

        /**
         * Restore hidden state of tabs
         */
        restoreHiddenTabs: function(hiddenTabsData) {
            hiddenTabsData.forEach(function(tabData) {
                if (tabData.wasHidden) {
                    tabData.element.addClass('hidden');
                }
                if (tabData.originalDisplay === 'none') {
                    tabData.element.css('display', 'none');
                }
            });
        },

        /**
         * Collect all form data generically
         */
        collectAllFormData: function($form) {
            var formData = {};
            var self = this;
            var fieldPrefix = 'shopglut_product_badge_settings';

            // Collect all input, select, and textarea elements
            $form.find('input, select, textarea').each(function() {
                var $element = $(this);
                var name = $element.attr('name');
                var type = $element.attr('type');
                var value = $element.val();

                if (!name) return; // Skip elements without names

                // Only collect fields that belong to product badge settings
                if (!name.startsWith(fieldPrefix)) {
                    return;
                }

                // Strip the prefix to get the clean field name
                // e.g., "shopg_product_badge_settings[enable_badge]" becomes "enable_badge"
                var cleanName = name.replace(fieldPrefix, '');

                // Handle different input types
                if (type === 'checkbox') {
                    if ($element.is(':checked')) {
                        // For checkboxes, collect as array if multiple with same base name
                        // Handle both [field][key] and [field][] formats
                        var checkboxMatch = cleanName.match(/^\[([^\[\]]+)\](?:\[\]|\[([^\[\]]+)\])$/);
                        if (checkboxMatch) {
                            var basePath = '[' + checkboxMatch[1] + ']';
                            var optionKey = checkboxMatch[2] || value;

                            // Initialize array if needed
                            if (!formData[basePath]) {
                                formData[basePath] = [];
                            }
                            // Only add if not already in array
                            if (formData[basePath].indexOf(optionKey) === -1) {
                                formData[basePath].push(optionKey);
                            }
                        } else {
                            formData[cleanName] = value;
                        }
                    }
                } else if (type === 'radio') {
                    if ($element.is(':checked')) {
                        formData[cleanName] = value;
                    }
                } else if ($element.is('select[multiple]')) {
                    // Handle multi-select
                    if (value && value.length > 0) {
                        formData[cleanName] = Array.isArray(value) ? value : [value];
                    }
                } else {
                    // Handle regular inputs, selects, and textareas
                    if (value !== '' && value !== null && value !== undefined) {
                        formData[cleanName] = value;
                    }
                }
            });

            // Convert flat field names to nested structure
            var nestedData = this.convertToNestedStructure(formData);

            return nestedData;
        },

        /**
         * Check if a field should be skipped during data collection
         */
        shouldSkipField: function(name) {
            // Skip fields that start with underscores (template fields)
            if (name.startsWith('___') || name.startsWith('______')) {
                return true;
            }

            // Skip non-productbadge layout related fields
            var skipFields = [
                'agl_metabox_nonce',
                '_wp_http_referer',
                '_pseudo',
                'shopg_productbadge_layouts_nonce',
                'shopg_shop_layoutid',
                'layout_name',
                'layout_template'
            ];

            for (var i = 0; i < skipFields.length; i++) {
                if (name === skipFields[i] || name.includes(skipFields[i])) {
                    return true;
                }
            }

            return false;
        },

        /**
         * Convert flat field names to nested object structure
         * e.g., "shopg_productbadge_settings_template1[tab][field]" -> {shopg_productbadge_settings_template1: {tab: {field: value}}}
         */
        convertToNestedStructure: function(flatData) {
            var nestedData = {};

            for (var fieldName in flatData) {
                if (flatData.hasOwnProperty(fieldName)) {
                    var value = flatData[fieldName];
                    this.setNestedValue(nestedData, fieldName, value);
                }
            }

            return nestedData;
        },

        /**
         * Set a nested value in an object using a field name like "a[b][c]"
         */
        setNestedValue: function(obj, fieldName, value) {
            // Parse the field name to extract the path
            var keys = this.parseFieldName(fieldName);

            var current = obj;
            for (var i = 0; i < keys.length - 1; i++) {
                var key = keys[i];
                if (!current[key]) {
                    // Check if the next key is numeric to decide if this should be an array
                    var nextKey = keys[i + 1];
                    if (nextKey && !isNaN(parseInt(nextKey))) {
                        current[key] = [];
                    } else {
                        current[key] = {};
                    }
                }
                current = current[key];
            }

            // Set the final value
            var finalKey = keys[keys.length - 1];
            current[finalKey] = value;
        },

        /**
         * Parse a field name like "shopg_productbadge_settings_template1[tab][field][0]" into an array of keys
         */
        parseFieldName: function(fieldName) {
            var keys = [];
            var match;
            var regex = /([^\[\]]+)/g;

            while ((match = regex.exec(fieldName)) !== null) {
                keys.push(match[1]);
            }

            return keys;
        },

        /**
         * Save order complete layout data via AJAX
         */
        saveProductBadgeLayoutData: function() {
            // Show loader - direct CSS like single product module
            $(".loader-overlay").css({"display": "flex", "opacity": "1"});
            $(".loader-container").show();

            // Get form data
            var formData = this.convertFormDataToJSON();

            var layoutName = $('#badge_name').val() || 'Untitled Badge';
            var layoutId = $('#shopg_badge_id').val() || 0;
            var nonce = $('input[name="shopg_productbadge_nonce"]').val();

            // Wrap the data similar to single product module
            // Single product uses: shopg_singleproduct_settings_template1
            // Badges uses: shopg_product_badge_settings (without template suffix since badges don't have templates)
            var wrappedData = {};
            wrappedData['shopg_product_badge_settings'] = formData;

            // Stringify to JSON like single product does
            var jsonString = JSON.stringify(wrappedData);

            // Prepare AJAX data - send as JSON string like single product
            var ajaxData = {
                action: 'save_shopg_productbadge_data',
                badge_name: layoutName,
                shopg_badge_id: layoutId,
                shopg_productbadge_nonce: nonce,
                shopg_product_badge_settings: jsonString  // Send as JSON string
            };

            // Send AJAX request
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                traditional: false,  // Important: use jQuery's nested object serialization
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        ShopGlutProductBadgeLayout.dataConverter.showNotification('success', response.data.message || 'Product badge saved successfully!');

                        // Update layout ID if it's a new layout
                        if (response.data.badge_id) {
                            $('#shopg_badge_id').val(response.data.badge_id);
                            $('input[name="shopg_badge_id"]').val(response.data.badge_id);

                            // Reload the preview from server after save
                            ShopGlutProductBadgeLayout.dataConverter.reloadBadgePreview(response.data.badge_id);
                        }
                    } else {
                        // Show error message
                        ShopGlutProductBadgeLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to save product badge.');
                    }
                },
                error: function(xhr) {
                    // Try to parse error message from response
                    var errorMessage = 'Network error occurred while saving.';
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.data && errorResponse.data.message) {
                            errorMessage = errorResponse.data.message;
                        } else if (errorResponse.data) {
                            errorMessage = errorResponse.data;
                        }
                    } catch (e) {
                        if (xhr.responseText) {
                            errorMessage = 'Error: ' + xhr.responseText.substring(0, 100);
                        }
                    }

                    ShopGlutProductBadgeLayout.dataConverter.showNotification('error', errorMessage);
                },
                complete: function() {
                    // Hide loader - direct CSS like single product module
                    $(".loader-overlay").css({"display": "none", "opacity": "0"});
                    $(".loader-container").hide();
                }
            });
        },

        /**
         * Reset order complete layout settings to default
         */
        resetProductBadgeLayoutSettings: function() {
            if (!confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')) {
                return;
            }

            // Show loader - direct CSS like single product module
            $(".loader-overlay").css({"display": "flex", "opacity": "1"});
            $(".loader-container").show();

            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var nonce = $('input[name="shopg_productbadge_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'reset_shopg_productbadge_settings',
                badge_id: layoutId,
                nonce: nonce
            };

            // Send AJAX request
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        ShopGlutProductBadgeLayout.dataConverter.showNotification('success', response.data.message || 'Settings reset to default successfully!');

                        // Reload the page after a short delay to refresh all settings
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        ShopGlutProductBadgeLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to reset settings.');
                    }
                },
                error: function() {
                    ShopGlutProductBadgeLayout.dataConverter.showNotification('error', 'Network error occurred while resetting settings.');
                },
                complete: function() {
                    // Hide loader - direct CSS like single product module
                    $(".loader-overlay").css({"display": "none", "opacity": "0"});
                    $(".loader-container").hide();
                }
            });
        },

        /**
         * Show notification message
         */
        showNotification: function(type, message) {
            var $container = $('#shopg-notification-container');
            if (!$container.length) {
                $container = $('<div id="shopg-notification-container"></div>').prependTo('.shopglut_layout_contents');
            }

            var cssClass = type === 'success' ? 'notice-success' : 'notice-error';
            var $notice = $('<div class="notice ' + cssClass + ' is-dismissible"><p>' + message + '</p></div>');

            $container.html($notice);

            // Scroll to top to show notification
            $('html, body').animate({ scrollTop: 0 }, 300);

            // Auto-hide after 5 seconds
            setTimeout(function() {
                $notice.fadeOut();
            }, 5000);

            // Make dismissible
            $notice.on('click', '.notice-dismiss', function() {
                $notice.remove();
            });
        },

        /**
         * Reload badge preview from server after save
         */
        reloadBadgePreview: function(badgeId) {
            if (!badgeId) {
                return;
            }

            var nonce = $('input[name="shopg_productbadge_nonce"]').val();
            if (!nonce) {
                console.warn('Missing nonce for badge preview request');
                return;
            }

            // Fetch the updated preview from the server
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_badge_preview',
                    badge_id: badgeId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        // Replace the preview container with the updated preview
                        var $previewContainer = $('.shopglut-badge-preview-wrapper');
                        if ($previewContainer.length) {
                            $previewContainer.replaceWith(response.data.html);
                        } else {
                            // If preview container doesn't exist, append it to the settings panel
                            $('#shopg-productbadge-settings').prepend(response.data.html);
                        }
                    }
                },
                error: function() {
                    console.warn('Failed to reload badge preview');
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Hide loader on page load - same as single product module
        $(window).on('load', function() {
            setTimeout(function() {
                $(".loader-overlay").css({"display": "none", "opacity": "0"});
                $(".loader-container").hide();
            }, 500);
        });

        // Fallback: Hide loader after maximum 10 seconds
        setTimeout(function() {
            $(".loader-overlay").css({"display": "none", "opacity": "0"});
            $(".loader-container").hide();
        }, 10000);

        // Bind save button click
        $('#productbadge-publishing-action #productbadge-save-badge-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutProductBadgeLayout.dataConverter.saveProductBadgeLayoutData();
        });

        // Bind reset button click
        $('#productbadge-reset-settings-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutProductBadgeLayout.dataConverter.resetProductBadgeLayoutSettings();
        });

        // Badge display selection AJAX - following select_products pattern
        $(".agl-field.agl-field-select_badge_display .agl-fieldset").on("click", function () {
            if ($(".agl-field.agl-field-select_badge_display .chosen-drop .chosen-results").length > 0) {

                // Validate required data exists
                if (!shopglut_admin_vars || !shopglut_admin_vars.ajax_url) {
                    console.warn('Missing required data for badge display options request');
                    return;
                }

                var layoutId = $('#shopg_shop_layoutid').val() || 0;
                var nonce = $('input[name="shopg_productbadge_layouts_nonce"]').val();

                if (!nonce) {
                    console.warn('Missing nonce for badge display options request');
                    return;
                }

                // Show loading spinner
                $(".agl-field.agl-field-select_badge_display .chosen-drop").append('<div class="loading-spinner"></div>');

                $.ajax({
                    url: shopglut_admin_vars.ajax_url || ajaxurl,
                    method: "POST",
                    data: {
                        action: "shopglut_get_badge_display_options",
                        nonce: nonce,
                        badge_id: layoutId,
                    },
                    timeout: 15000, // 15 second timeout
                    success: function(response) {
                        if (response.success && response.data) {
                            // response.data is an array of {value, text, disabled} objects
                            var usedOptions = response.data.filter(opt => opt.disabled).map(opt => opt.text);

                            $(".agl-field.agl-field-select_badge_display .chosen-drop .chosen-results li").each(function() {
                                var itemText = $(this).text().trim();
                                // Remove the " (Used by another layout)" suffix for badge
                                var cleanItemText = itemText.replace(' (Used by another layout)', '');

                                if (usedOptions.some(usedText => cleanItemText.includes(usedText.replace(' (Used by another layout)', '')))) {
                                    $(this).addClass("result-selected").removeClass("active-result");
                                } else {
                                    $(this).addClass("active-result").removeClass("result-selected");
                                }
                            });
                        } else {
                            console.warn('Invalid response from server:', response);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Failed to retrieve badge display options:", {
                            status: textStatus,
                            error: errorThrown,
                            response: jqXHR.responseText
                        });

                        if (jqXHR.status === 403) {
                            ShopGlutProductBadgeLayout.dataConverter.showNotification('error', 'Access denied. Please refresh the page.');
                        }
                    },
                    complete: function () {
                        // Remove loading spinner
                        $(".agl-field.agl-field-select_badge_display .chosen-drop .loading-spinner").remove();
                        $(".agl-field.agl-field-select_badge_display .chosen-results").removeClass("filter-dis");
                    }
                });
            }
        });
    });

})(jQuery);