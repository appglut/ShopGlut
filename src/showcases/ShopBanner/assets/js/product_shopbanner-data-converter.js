/**
 * ShopGlut Order Complete Layout Data Converter Module
 *
 * Handles order complete layout form data conversion and saving
 */

(function($) {
    'use strict';

    // Create namespace if it doesn't exist
    window.ShopGlutShopBannerLayout = window.ShopGlutShopBannerLayout || {};

    // Data converter module namespace
    ShopGlutShopBannerLayout.dataConverter = {

        /**
         * Convert form data to clean JSON structure
         */
        convertFormDataToJSON: function() {
            var cleanData = {};

            // Get form element
            var $form = this.getFormElement('#shopglut_shop_layouts');
            if (!$form.length) {
                console.log('Order complete layout form not found');
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

            // Collect all input, select, and textarea elements
            $form.find('input, select, textarea').each(function() {
                var $element = $(this);
                var name = $element.attr('name');
                var type = $element.attr('type');
                var value = $element.val();

                if (!name) return; // Skip elements without names

                // Filter out unwanted fields
                if (self.shouldSkipField(name)) {
                    return;
                }

                // Handle different input types
                if (type === 'checkbox') {
                    if ($element.is(':checked')) {
                        // Handle checkbox arrays (name ends with [])
                        if (name.endsWith('[]')) {
                            var cleanName = name.replace('[]', '');
                            if (!formData[cleanName]) {
                                formData[cleanName] = [];
                            }
                            formData[cleanName].push(value);
                        } else {
                            formData[name] = value;
                        }
                    }
                } else if (type === 'radio') {
                    if ($element.is(':checked')) {
                        formData[name] = value;
                    }
                } else if ($element.is('select[multiple]')) {
                    // Handle multi-select
                    if (value && value.length > 0) {
                        formData[name] = Array.isArray(value) ? value : [value];
                    }
                } else {
                    // Handle regular inputs, selects, and textareas
                    if (value !== '' && value !== null && value !== undefined) {
                        formData[name] = value;
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

            // Skip non-ShopBanner layout related fields
            var skipFields = [
                'agl_metabox_nonce',
                '_wp_http_referer',
                '_pseudo',
                'shopg_ShopBanner_layouts_nonce',
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
         * e.g., "shopg_ShopBanner_settings_template1[tab][field]" -> {shopg_ShopBanner_settings_template1: {tab: {field: value}}}
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
         * Parse a field name like "shopg_ShopBanner_settings_template1[tab][field][0]" into an array of keys
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
        saveShopBannerLayoutData: function() {
            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            // Get form data
            var formData = this.convertFormDataToJSON();
            var layoutName = $('#layout_name').val() || 'Untitled Layout';
            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var layoutTemplate = $('#layout_template').val() || 'template1';
            var nonce = $('input[name="shopg_ShopBanner_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'save_shopg_ShopBanner_layoutdata',
                layout_name: layoutName,
                layout_template: layoutTemplate,
                shopg_ShopBanner_layoutid: layoutId,
                ShopBanner_nonce: nonce,
                shopg_options_settings: JSON.stringify(formData)
            };

            // Send AJAX request
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                traditional: true,
                success: function(response) {
                    if (response.success) {

                        // Show success message
                        ShopGlutShopBannerLayout.dataConverter.showNotification('success', response.data.message || 'Order complete layout saved successfully!');

                        // Update layout ID if it's a new layout
                        if (response.data.layout_id) {
                            $('#shopg_shop_layoutid').val(response.data.layout_id);
                        }

                        // Update preview if HTML is returned - find the specific preview field
                        if (response.data.html) {
                            
                            // Target the preview field content, not the wrapper
                            var $previewField = $('.agl-field-preview');
                            if ($previewField.length) {

                                // Update only the inner HTML to preserve the field wrapper
                                $previewField.html(response.data.html);

                                // FIX: The tab contents have inline display:none that persists even after click
                                // We need to fix the display style whenever a tab is clicked
                                setTimeout(function() {
                                    // Add a click handler that runs AFTER the original handler
                                    // to fix the display style issue
                                    $('.agl-tabbed-nav a').off('click.fixdisplay').on('click.fixdisplay', function() {
                                        // Wait for the original handler to run first
                                        setTimeout(function() {
                                            // Fix display for ALL tab contents based on their hidden class
                                            $('.agl-tabbed-content').each(function() {
                                                var $content = $(this);
                                                if ($content.hasClass('hidden')) {
                                                    // Hide tabs that should be hidden
                                                    $content.css('display', 'none');
                                                } else {
                                                    // Show tabs that should be visible
                                                    $content.css('display', 'block');
                                                }
                                            });
                                        }, 10);
                                    });

                                    // Also fix the currently visible tab immediately
                                    $('.agl-tabbed-content').each(function() {
                                        var $content = $(this);
                                        if ($content.hasClass('hidden')) {
                                            $content.css('display', 'none');
                                        } else {
                                            $content.css('display', 'block');
                                        }
                                    });
                                }, 100);
                            }
                        }
                    } else {
                        // Show error message
                        ShopGlutShopBannerLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to save order complete layout.');
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

                    ShopGlutShopBannerLayout.dataConverter.showNotification('error', errorMessage);
                },
                complete: function() {
                    // Hide loader
                    if (window.hideLoader && typeof window.hideLoader === 'function') {
                        window.hideLoader();
                    }
                }
            });
        },

        /**
         * Reset order complete layout settings to default
         */
        resetShopBannerLayoutSettings: function() {
            if (!confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')) {
                return;
            }

            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var nonce = $('input[name="shopg_ShopBanner_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'reset_shopg_ShopBanner_layout_settings',
                layout_id: layoutId,
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
                        ShopGlutShopBannerLayout.dataConverter.showNotification('success', response.data.message || 'Settings reset to default successfully!');

                        // Reload the page after a short delay to refresh all settings
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        ShopGlutShopBannerLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to reset settings.');
                    }
                },
                error: function() {
                    ShopGlutShopBannerLayout.dataConverter.showNotification('error', 'Network error occurred while resetting settings.');
                },
                complete: function() {
                    // Hide loader
                    if (window.hideLoader && typeof window.hideLoader === 'function') {
                        window.hideLoader();
                    }
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
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Bind save button click
        $('#ShopBanner-save-layout-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutShopBannerLayout.dataConverter.saveShopBannerLayoutData();
        });

        // Bind reset button click
        $('#ShopBanner-reset-settings-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutShopBannerLayout.dataConverter.resetShopBannerLayoutSettings();
        });

        // ShopBanner display selection AJAX - following select_products pattern
        $(".agl-field.agl-field-select_shopbanner_display .agl-fieldset").on("click", function () {
            if ($(".agl-field.agl-field-select_shopbanner_display .chosen-drop .chosen-results").length > 0) {

                // Validate required data exists
                if (!shopglut_admin_vars || !shopglut_admin_vars.ajax_url) {
                    console.warn('Missing required data for shopbanner display options request');
                    return;
                }

                var layoutId = $('#shopg_shop_layoutid').val() || 0;
                var nonce = $('input[name="shopg_ShopBanner_layouts_nonce"]').val();

                if (!nonce) {
                    console.warn('Missing nonce for shopbanner display options request');
                    return;
                }

                // Show loading spinner
                $(".agl-field.agl-field-select_shopbanner_display .chosen-drop").append('<div class="loading-spinner"></div>');

                $.ajax({
                    url: shopglut_admin_vars.ajax_url || ajaxurl,
                    method: "POST",
                    data: {
                        action: "shopglut_get_shopbanner_display_options",
                        nonce: nonce,
                        layout_id: layoutId,
                    },
                    timeout: 15000, // 15 second timeout
                    success: function(response) {
                        console.log('ShopBanner display options response:', response);

                        if (response.success && response.data) {
                            // response.data is an array of {value, text, disabled} objects
                            var usedOptions = response.data.filter(opt => opt.disabled).map(opt => opt.text);

                            $(".agl-field.agl-field-select_shopbanner_display .chosen-drop .chosen-results li").each(function() {
                                var itemText = $(this).text().trim();
                                // Remove the " (Used by another layout)" suffix for shopbanner
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
                        console.error("Failed to retrieve shopbanner display options:", {
                            status: textStatus,
                            error: errorThrown,
                            response: jqXHR.responseText
                        });

                        if (jqXHR.status === 403) {
                            ShopGlutShopBannerLayout.dataConverter.showNotification('error', 'Access denied. Please refresh the page.');
                        }
                    },
                    complete: function () {
                        // Remove loading spinner
                        $(".agl-field.agl-field-select_shopbanner_display .chosen-drop .loading-spinner").remove();
                        $(".agl-field.agl-field-select_shopbanner_display .chosen-results").removeClass("filter-dis");
                    }
                });
            }
        });
    });

})(jQuery);