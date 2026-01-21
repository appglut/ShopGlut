/**
 * ShopGlut Shop Layout Data Converter Module
 *
 * Handles shop layout form data conversion and saving
 */

(function($) {
    'use strict';

    // Create namespace if it doesn't exist
    window.ShopGlutShopLayout = window.ShopGlutShopLayout || {};

    // Data converter module namespace
    ShopGlutShopLayout.dataConverter = {

        /**
         * Convert form data to clean JSON structure
         */
        convertFormDataToJSON: function() {
            var cleanData = {};

            // Get form element
            var $form = this.getFormElement('#shopglut_shop_layouts');
            if (!$form.length) {
                return cleanData;
            }

            try {
                // Collect all form data generically (no need to show hidden tabs - form data exists regardless of visibility)
                cleanData = this.collectAllFormData($form);

            } catch (error) {
                // Silently fail
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

            // Skip non-shoplayouts layout related fields
            var skipFields = [
                'agl_metabox_nonce',
                '_wp_http_referer',
                '_pseudo',
                'shopg_shoplayouts_layouts_nonce',
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
         * e.g., "shopg_settings_template1[tab][field]" -> {shopg_settings_template1: {tab: {field: value}}}
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
         * Parse a field name like "shopg_settings_template1[tab][field][0]" into an array of keys
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
         * Save shop layout data via AJAX
         */
        saveShopLayoutData: function() {
            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            // Get form data
            var formData = this.convertFormDataToJSON();
            var layoutName = $('#layout_name').val() || 'Untitled Layout';
            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var layoutTemplate = $('#layout_template').val() || 'template1';
            var nonce = $('input[name="shopg_shoplayouts_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'save_shopg_shopdata',
                layout_name: layoutName,
                layout_template: layoutTemplate,
                shopg_shop_layoutid: layoutId,
                shoplayouts_nonce: nonce,
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
                        ShopGlutShopLayout.dataConverter.showNotification('success', response.data.message || 'Shop layout saved successfully!');

                        // Update layout ID if it's a new layout
                        if (response.data.layout_id) {
                            $('#shopg_shop_layoutid').val(response.data.layout_id);
                        }

                        // Update preview with saved layout
                        if (response.data.html) {
                            // Find the outer preview wrapper
                            var $previewWrapper = $('.shopg_shop_layout_contents');

                            if ($previewWrapper.length) {
                                // Directly replace with the server's HTML (your updated layout)
                                $previewWrapper.replaceWith(response.data.html);

                                // Scroll to the updated preview
                                setTimeout(function() {
                                    var $newPreview = $('.shopg_shop_layout_contents');
                                    if ($newPreview.length) {
                                        $('html, body').animate({
                                            scrollTop: $newPreview.offset().top - 100
                                        }, 500);
                                    }
                                }, 100);
                            }
                        }
                    } else {
                        // Show error message
                        ShopGlutShopLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to save shop layout.');
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

                    ShopGlutShopLayout.dataConverter.showNotification('error', errorMessage);
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
         * Reset shop layout settings to default
         */
        resetShopLayoutSettings: function() {
            if (!confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')) {
                return;
            }

            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var nonce = $('input[name="shopg_shoplayouts_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'reset_shopglut_layouts',
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
                        ShopGlutShopLayout.dataConverter.showNotification('success', response.data.message || 'Settings reset to default successfully!');

                        // Reload the page after a short delay to refresh all settings
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        ShopGlutShopLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to reset settings.');
                    }
                },
                error: function() {
                    ShopGlutShopLayout.dataConverter.showNotification('error', 'Network error occurred while resetting settings.');
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
        $('#shoplayoutsLayout-publishing-action #publish').on('click', function(e) {
            e.preventDefault();
            ShopGlutShopLayout.dataConverter.saveShopLayoutData();
        });

        // Bind reset button click
        $('#shoplayouts-reset-settings-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutShopLayout.dataConverter.resetShopLayoutSettings();
        });
    });

})(jQuery);