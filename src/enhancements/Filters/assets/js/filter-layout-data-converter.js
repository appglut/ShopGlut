/**
 * ShopGlut Filter Layout Data Converter Module
 *
 * Handles filter layout form data conversion and saving
 */

(function($) {
    'use strict';

    // Create namespace if it doesn't exist
    window.ShopGlutFilterLayout = window.ShopGlutFilterLayout || {};

    // Data converter module namespace
    ShopGlutFilterLayout.dataConverter = {

        /**
         * Convert form data to clean JSON structure
         */
        convertFormDataToJSON: function() {
            var cleanData = {};

            // Get form element
            var $form = this.getFormElement('#shopglut_shop_filter');
            if (!$form.length) {
                return cleanData;
            }

            // Temporarily make hidden content visible for data collection
            var hiddenTabsData = this.showHiddenTabs($form);

            try {
                // Collect all form data generically
                cleanData = this.collectAllFormData($form);

            } catch (error) {
                console.error('Error collecting filter layout data:', error);
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

            // Skip non-filter layout related fields
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
         * e.g., "shopg_filter_settings_template1[tab][field]" -> {shopg_filter_settings_template1: {tab: {field: value}}}
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
         * Parse a field name like "shopg_ordercomplete_settings_template1[tab][field][0]" into an array of keys
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
         * Save filter layout data via AJAX
         */
        saveFilterLayoutData: function() {
            // Show loader
            if (window.showLoader && typeof window.showLoader === 'function') {
                window.showLoader();
            }

            // Get form data
            var formData = this.convertFormDataToJSON();
            var layoutName = $('#filter_name').val() || 'Untitled Filter';
            var layoutId = $('#shopg_shop_filter_id').val() || 0;
            var nonce = $('input[name="shopg_shop_filters_nonce"]').val();

            
            // Check if formData is valid before proceeding
            if (!formData || typeof formData !== 'object') {
                console.error('Invalid form data detected:', formData);
                if (window.hideLoader && typeof window.hideLoader === 'function') {
                    window.hideLoader();
                }
                this.showNotification('error', 'Invalid form data. Please check your filter settings.');
                return;
            }

            // Prepare AJAX data with error handling
            var filterDataString;
            try {
                filterDataString = JSON.stringify(formData);
            } catch (error) {
                console.error('JSON.stringify error:', error);
                console.error('FormData that failed:', formData);
                if (window.hideLoader && typeof window.hideLoader === 'function') {
                    window.hideLoader();
                }
                this.showNotification('error', 'Failed to serialize filter data. Please check for invalid characters.');
                return;
            }

            var ajaxData = {
                action: 'save_shopg_filterdata',
                filter_name: layoutName,
                filter_data: filterDataString,
                shopg_shop_filter_id: layoutId,
                nonce: nonce
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
                        ShopGlutFilterLayout.dataConverter.showNotification('success', response.data.message || 'Filter layout saved successfully!');

                        // Update layout ID if it's a new layout
                        if (response.data.filter_id) {
                            $('#shopg_shop_filter_id').val(response.data.filter_id);
                        }

                        // Update preview if HTML is returned - find the specific preview field
                        if (response.data.preview_html) {
                            // Target the preview field content, not the wrapper
                            var $previewField = $('.agl-field-preview');
                            if ($previewField.length) {
                                // Update only the inner HTML to preserve the field wrapper
                                $previewField.html(response.data.preview_html);

                                // Initialize frontend JavaScript for the preview
                                setTimeout(function() {
                                    // Initialize filter interactions in preview
                                    ShopGlutFilterLayout.dataConverter.initializePreviewInteractions($previewField);

                                    // FIX: The tab contents have inline display:none that persists even after click
                                    // We need to fix the display style whenever a tab is clicked
                                    ShopGlutFilterLayout.dataConverter.fixTabDisplay();

                                    // Initialize price sliders in preview
                                    if (typeof ShopGlutFilters !== 'undefined' && ShopGlutFilters.priceSlider) {
                                        ShopGlutFilters.priceSlider.initializeSliders();
                                    }

                                    // The FilterStyle JavaScript handles checkbox/radio initialization automatically
                                }, 200);
                            }
                        }
                    } else {
                        // Show error message
                        console.error('Server returned error response:', response);
                        console.error('Full error response:', JSON.stringify(response, null, 2));
                        ShopGlutFilterLayout.dataConverter.showNotification('error', response.data.message || response.data || 'Failed to save filter layout.');
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

                    ShopGlutFilterLayout.dataConverter.showNotification('error', errorMessage);
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
        },

        /**
         * Initialize preview interactions
         */
        initializePreviewInteractions: function($container) {
            var self = this;

            if (!$container || !$container.length) {
                $container = $('.agl-field-preview');
            }

            if (!$container.length) return;

            // Initialize ShopGlutFilters global object for preview
            if (typeof ShopGlutFilters === 'undefined') {
                window.ShopGlutFilters = {
                    config: {
                        ajaxUrl: ajaxurl || shopglut_admin_vars.ajax_url,
                        nonce: shopglut_admin_vars.nonce || '',
                        enableLivePreview: false
                    },
                    modules: {},
                    frontend: {
                        initializeFilterInteractions: function() {
                            // Mock initialization for preview
                        }
                    }
                };
            }

            // Initialize filter item interactions
            $container.find('.term-grid-item, .term-cloud-button, .term-button').each(function() {
                var $item = $(this);
                $item.off('click.preview').on('click.preview', function(e) {
                    e.preventDefault();
                    self.toggleFilterItemState($(this));
                });
            });

            // Initialize checkbox interactions
            $container.find('.term-checkbox input[type="checkbox"], .shopglut-filter-checkbox input[type="checkbox"]').each(function() {
                var $checkbox = $(this);
                $checkbox.off('change.preview').on('change.preview', function() {
                    self.updateFilterItemState($(this));
                });
            });

            // Initialize radio button interactions
            $container.find('.shopg-term-radio input[type="radio"], .shopglut-filter-checkbox input[type="radio"]').each(function() {
                var $radio = $(this);
                $radio.off('change.preview').on('change.preview', function() {
                    self.updateFilterItemState($(this));
                });
            });

            // Initialize accordion toggles
            $container.find('.filter-title-accordion').each(function() {
                var $title = $(this);
                $title.off('click.preview').on('click.preview', function(e) {
                    e.preventDefault();
                    self.toggleAccordionPreview($(this));
                });
            });

            // Initialize dropdown changes
            $container.find('.shopg-term-dropdown').each(function() {
                var $dropdown = $(this);
                $dropdown.off('change.preview').on('change.preview', function() {
                    self.logPreviewChange($(this));
                });
            });

            // Initialize search input
            $container.find('.shopglut-filter-input-search').each(function() {
                var $input = $(this);
                $input.off('input.preview').on('input.preview', function() {
                    self.logPreviewChange($(this));
                });
            });
        },

        /**
         * Toggle filter item state in preview
         */
        toggleFilterItemState: function($element) {
            var $checkbox = $element.find('input[type="checkbox"]');
            if ($checkbox.length) {
                $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change.preview');
            }
            this.updateFilterItemState($element);
        },

        /**
         * Update filter item visual state
         */
        updateFilterItemState: function($element) {
            var $container = $element.closest('.shopglut-filter-group');
            var isChecked = false;

            if ($element.is('input')) {
                isChecked = $element.prop('checked');
                $element = $element.closest('.term-checkbox, .shopg-term-radio, .term-grid-item, .term-cloud-button, .term-button, .term-color, .term-image');
            } else {
                var $checkbox = $element.find('input[type="checkbox"], input[type="radio"]');
                if ($checkbox.length) {
                    isChecked = $checkbox.prop('checked');
                }
            }

            if ($element.hasClass('term-grid-item') ||
                $element.hasClass('term-cloud-button') ||
                $element.hasClass('term-button') ||
                $element.hasClass('term-color') ||
                $element.hasClass('term-image')) {

                if (isChecked) {
                    $element.addClass('selected');
                } else {
                    $element.removeClass('selected');
                }
            }
        },

        /**
         * Toggle accordion in preview
         */
        toggleAccordionPreview: function($title) {
            var $content = $title.next('.filter-content');
            var $icon = $title.find('.accordion-toggle-icon');

            if ($content.is(':visible')) {
                $content.slideUp(300);
                $icon.removeClass('expanded');
                $title.removeClass('expanded');
            } else {
                $content.slideDown(300);
                $icon.addClass('expanded');
                $title.addClass('expanded');
            }
        },

        
        /**
         * Fix tab display issues
         */
        fixTabDisplay: function() {
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
        },

        /**
         * Update live preview on form changes
         */
        updateLivePreview: function() {
            var self = this;
            var formData = this.convertFormDataToJSON();

            // Show loading state in preview
            var $previewField = $('.agl-field-preview');
            if ($previewField.length) {
                $previewField.addClass('loading');
            }

            // AJAX request to update preview
            $.ajax({
                url: shopglut_admin_vars.ajax_url || ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_filter_live_preview',
                    preview_data: JSON.stringify(formData),
                    nonce: shopglut_admin_vars.nonce || ''
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data.preview_html) {
                        $previewField.html(response.data.preview_html);

                        // Reinitialize preview interactions
                        setTimeout(function() {
                            self.initializePreviewInteractions($previewField);
                            self.fixTabDisplay();

                            // Initialize price sliders
                            if (typeof ShopGlutFilters !== 'undefined' && ShopGlutFilters.priceSlider) {
                                ShopGlutFilters.priceSlider.initializeSliders();
                            }
                        }, 200);
                    }
                },
                complete: function() {
                    $previewField.removeClass('loading');
                }
            });
        },

        /**
         * Bind live preview events to form fields
         */
        bindLivePreviewEvents: function() {
            var self = this;
            var previewTimeout;

            // Debounced preview update
            function debouncedUpdatePreview() {
                clearTimeout(previewTimeout);
                previewTimeout = setTimeout(function() {
                    self.updateLivePreview();
                }, 500); // 500ms delay to prevent excessive requests
            }

        },

        /**
         * Initialize existing preview content on page load
         */
        initializeExistingPreview: function() {
            var $previewField = $('.agl-field-preview');

            if ($previewField.length && $previewField.find('.shopg-filter-live-preview').length) {
                // Initialize ShopGlutFilters global object if not exists
                if (typeof ShopGlutFilters === 'undefined') {
                    window.ShopGlutFilters = {
                        config: {
                            ajaxUrl: shopglut_admin_vars.ajax_url || ajaxurl,
                            nonce: shopglut_admin_vars.nonce || '',
                            enableLivePreview: false
                        },
                        modules: {},
                        frontend: {
                            initializeFilterInteractions: function() {
                                // Mock initialization for preview
                            }
                        }
                    };
                }

                // Initialize preview interactions
                setTimeout(function() {
                    this.initializePreviewInteractions($previewField);
                    this.fixTabDisplay();

                    // Initialize price sliders if they exist
                    if (typeof ShopGlutFilters !== 'undefined' && ShopGlutFilters.priceSlider) {
                        ShopGlutFilters.priceSlider.initializeSliders();
                    }

                    // Add fade-in animation
                    $previewField.addClass('fade-in');
                }.bind(this), 300);
            }
        }
    };

    
    // Initialize on document ready
    $(document).ready(function() {
        
        // Handle Filter tab click - properly reinitialize field components
        $(document).on('click', '.agl-tabbed-nav a.shopglut-filter-settings-main-tab', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Set active tab
            $('.agl-tabbed-nav a').removeClass('agl-tabbed-active');
            $(this).addClass('agl-tabbed-active');

            // Hide ALL filter-related tab contents first
            $('.agl-tabbed-content[class*="shopglut-filter-settings-main-tab"]').addClass('hidden').hide();

            // Show filter content
            $('.agl-tabbed-content.shopglut-filter-settings-main-tab').removeClass('hidden').show();

            // Properly reinitialize field components after content is visible
            setTimeout(function() {
                var $filterContent = $('.agl-tabbed-content.shopglut-filter-settings-main-tab');

                // Reinitialize all field types within the filter tab
                $filterContent.agl_reload_script();

                // Reinitialize dependency system first (this will handle show/hide logic)
                $filterContent.AGSHOPGLUT_dependency();

                // Then reinitialize specific field types
                setTimeout(function() {
                    // Specifically reinitialize switchers (with delay to avoid conflicts)
                    $filterContent.find('.agl-field-switcher').AGSHOPGLUT_switcher();

                    // Reinitialize button sets
                    $filterContent.find('.agl-field-button_set .agl-siblings').agl_siblings();

                    // Reinitialize other common field types
                    $filterContent.find('.agl-field-select .agl-chosen').agl_chosen();
                    $filterContent.find('.agl-field-spinner').AGSHOPGLUT_spinner();
                    $filterContent.find('.agl-field-checkbox .agl-checkbox').agl_checkbox();
                }, 50);

            }, 200);
        });

        // Handle Content tab click - properly reinitialize field components
        $(document).on('click', '.agl-tabbed-nav a.shopg_settings_content', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Set active tab
            $('.agl-tabbed-nav a').removeClass('agl-tabbed-active');
            $(this).addClass('agl-tabbed-active');

            // Hide ALL filter-related tab contents first
            $('.agl-tabbed-content[class*="shopglut-filter-settings-main-tab"]').addClass('hidden').hide();
            $('.agl-tabbed-content.shopg_settings_content').addClass('hidden').hide();

            // Show content tab
            $('.agl-tabbed-content.shopg_settings_content').removeClass('hidden').show();

            // Reinitialize all field components with comprehensive approach
            setTimeout(function() {
                var $contentTabContent = $('.agl-tabbed-content.shopg_settings_content');

                // Reinitialize all field types within the content tab
                $contentTabContent.agl_reload_script();

                // Reinitialize dependency system first (this will handle show/hide logic)
                $contentTabContent.AGSHOPGLUT_dependency();

                // Then reinitialize specific field types
                setTimeout(function() {
                    // Specifically reinitialize switchers (with delay to avoid conflicts)
                    $contentTabContent.find('.agl-field-switcher').AGSHOPGLUT_switcher();

                    // Reinitialize button sets
                    $contentTabContent.find('.agl-field-button_set .agl-siblings').agl_siblings();

                    // Reinitialize other common field types
                    $contentTabContent.find('.agl-field-select .agl-chosen').agl_chosen();
                    $contentTabContent.find('.agl-field-spinner').AGSHOPGLUT_spinner();
                    $contentTabContent.find('.agl-field-checkbox .agl-checkbox').agl_checkbox();
                }, 50);

            }, 200);
        });

        // Handle Settings tab click - properly reinitialize field components
        $(document).on('click', '.agl-tabbed-nav a.shopglut-filter-settings-main-tab2', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Set active tab
            $('.agl-tabbed-nav a').removeClass('agl-tabbed-active');
            $(this).addClass('agl-tabbed-active');

            // Hide ALL filter-related tab contents first
            $('.agl-tabbed-content[class*="shopglut-filter-settings-main-tab"]').addClass('hidden').hide();

            // Show settings content
            $('.agl-tabbed-content.shopglut-filter-settings-main-tab2').removeClass('hidden').show();

            // Properly reinitialize field components after content is visible
            // Use a longer delay and wait for WordPress dependencies
            setTimeout(function() {
                var $settingsContent = $('.agl-tabbed-content.shopglut-filter-settings-main-tab2');

                // Reinitialize all field types within the settings tab
                $settingsContent.agl_reload_script();

                // Reinitialize dependency system first (this will handle show/hide logic)
                $settingsContent.agl_dependency();

                // Then reinitialize specific field types
                setTimeout(function() {
                    // Specifically reinitialize switchers (with delay to avoid conflicts)
                    $settingsContent.find('.agl-field-switcher').AGSHOPGLUT_switcher();

                    // Reinitialize button sets
                    $settingsContent.find('.agl-field-button_set .agl-siblings').agl_siblings();

                    // Reinitialize other common field types
                    $settingsContent.find('.agl-field-select .agl-chosen').agl_chosen();
                    $settingsContent.find('.agl-field-spinner').AGSHOPGLUT_spinner();
                    $settingsContent.find('.agl-field-checkbox .agl-checkbox').agl_checkbox();
                }, 50);
            }, 500);
        });

        // Bind save button click
        $('#filterLayout-publishing-action #publish').on('click', function(e) {
            e.preventDefault();
            ShopGlutFilterLayout.dataConverter.saveFilterLayoutData();
        });

        // Bind live preview updates on form changes
        ShopGlutFilterLayout.dataConverter.bindLivePreviewEvents();

        // Initialize preview on page load if there's existing content
        ShopGlutFilterLayout.dataConverter.initializeExistingPreview();
    });

    
})(jQuery);