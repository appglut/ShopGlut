/**
 * ShopGlut Cart Layout Data Converter Module
 *
 * Handles cart layout form data conversion and saving
 */

(function($) {
    'use strict';

    // Create namespace if it doesn't exist
    window.ShopGlutCartLayout = window.ShopGlutCartLayout || {};

    // Data converter module namespace
    ShopGlutCartLayout.dataConverter = {

        /**
         * Convert form data to clean JSON structure
         */
        convertFormDataToJSON: function() {
            var cleanData = {};

            // Get form element
            var $form = this.getFormElement('#shopglut_shop_layouts');
            if (!$form.length) {
                console.log('Cart layout form not found');
                return cleanData;
            }

            // Temporarily make hidden content visible for data collection
            var hiddenTabsData = this.showHiddenTabs($form);

            try {
                // Collect all form data generically
                cleanData = this.collectAllFormData($form);

            } catch (error) {
                console.error('Error collecting cart layout data:', error);
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

            // Skip non-cart layout related fields
            var skipFields = [
                'agl_metabox_nonce',
                '_wp_http_referer',
                '_pseudo'
            ];

            for (var i = 0; i < skipFields.length; i++) {
                if (name.includes(skipFields[i])) {
                    return true;
                }
            }

            return false;
        },

        /**
         * Convert flat field names to nested object structure
         * e.g., "shopg_cartpage_settings_template1[tab][field]" -> {shopg_cartpage_settings_template1: {tab: {field: value}}}
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
         * Parse a field name like "shopg_cartpage_settings_template1[tab][field][0]" into an array of keys
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
         * Save cart layout data via AJAX
         */
        saveCartLayoutData: function() {
            console.log('Starting cart layout data save...');

            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            // Get form data
            var formData = this.convertFormDataToJSON();
            var layoutName = $('#layout_name').val() || 'Untitled Layout';
            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var nonce = $('input[name="shopg_cartpage_layouts_nonce"]').val();

            console.log('Form data collected:', formData);
            console.log('Layout name:', layoutName);
            console.log('Layout ID:', layoutId);

            // Prepare AJAX data
            var ajaxData = {
                action: 'save_shopg_cartlayoutdata',
                cart_layout_data: JSON.stringify(formData),
                layout_name: layoutName,
                shopg_cart_layoutid: layoutId,
                nonce: nonce
            };

            // Send AJAX request
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                success: function(response) {
                    console.log('Save response:', response);

                    if (response.success) {
                        // Show success message
                        ShopGlutCartLayout.dataConverter.showNotification('success', response.data.message || 'Cart layout saved successfully!');

                        // Update layout ID if it's a new layout
                        if (response.data.layout_id) {
                            $('#shopg_shop_layoutid').val(response.data.layout_id);
                        }
                    } else {
                        // Show error message
                        ShopGlutCartLayout.dataConverter.showNotification('error', response.data.message || 'Failed to save cart layout.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    ShopGlutCartLayout.dataConverter.showNotification('error', 'Network error occurred while saving.');
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
         * Reset cart layout settings to default
         */
        resetCartLayoutSettings: function() {
            if (!confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')) {
                return;
            }

            console.log('Resetting cart layout settings to default...');

            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            var layoutId = $('#shopg_shop_layoutid').val() || 0;
            var nonce = $('input[name="shopg_cartpage_layouts_nonce"]').val();

            // Prepare AJAX data
            var ajaxData = {
                action: 'reset_shopg_cartlayout_settings',
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
                    console.log('Reset response:', response);

                    if (response.success) {
                        // Show success message
                        ShopGlutCartLayout.dataConverter.showNotification('success', response.data.message || 'Settings reset to default successfully!');

                        // Reload the page after a short delay to refresh all settings
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        ShopGlutCartLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to reset settings.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    ShopGlutCartLayout.dataConverter.showNotification('error', 'Network error occurred while resetting settings.');
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
        $('#cartLayout-publishing-action').on('click', function(e) {
            e.preventDefault();
            ShopGlutCartLayout.dataConverter.saveCartLayoutData();
        });

        // Bind reset button click
        $('#reset-settings-button').on('click', function(e) {
            e.preventDefault();
            ShopGlutCartLayout.dataConverter.resetCartLayoutSettings();
        });
    });

})(jQuery);