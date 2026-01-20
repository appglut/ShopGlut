/**
 * Product Swatches Frontend JavaScript
 * Handles swatch selection, variation updates, and WooCommerce integration
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {

        // Flag to prevent price updates during reset
        var isResetting = false;

        // Function to check if any swatches are selected and show/hide clear button
        function updateClearButtonVisibility() {
            var $form = $('.variations_form').first();
            if (!$form.length) {
                return;
            }

            var hasSelection = false;
            $form.find('.shopglut-swatch-button.selected, .shopglut-color-swatch.selected, .shopglut-image-swatch.selected').each(function() {
                hasSelection = true;
                return false; // Break loop
            });

            // Also check if any dropdown has a value selected
            if (!hasSelection) {
                $form.find('.shopglut-swatch-dropdown').each(function() {
                    if ($(this).val() && $(this).val() !== '') {
                        hasSelection = true;
                        return false; // Break loop
                    }
                });
            }

            var $resetButton = $form.find('.shopglut-reset-variations');
            if (hasSelection) {
                $resetButton.removeClass('shopglut-reset-hidden');
            } else {
                $resetButton.addClass('shopglut-reset-hidden');
            }
        }

        // Common handler for all swatch types
        function handleSwatchClick($swatch, wrapperClass) {
            var $wrapper = $swatch.closest('.' + wrapperClass);
            var value = $swatch.data('value');
            var attribute = $swatch.data('attribute');

            if (!value || !attribute) {
                return;
            }

            // Remove selected class from siblings in same container
            $swatch.siblings().removeClass('selected');

            // Add selected class to clicked swatch
            $swatch.addClass('selected');

            // Find the variation form and update the select element
            var $form = $wrapper.closest('.variations_form');

            if ($form.length) {
                // First try to find the select in the wrapper (for button/image/color swatches with hidden select)
                var $select = $wrapper.find('select[name="' + attribute + '"]');

                // If not found in wrapper, look in the form (for default WooCommerce dropdowns)
                if (!$select.length) {
                    $select = $form.find('select[name="' + attribute + '"]').not('.shopglut-swatch-dropdown');
                }

                if ($select.length) {
                    $select.val(value).trigger('change');
                }
            }

            // Update clear button visibility
            updateClearButtonVisibility();
        }

        // Handle button swatch clicks
        $(document.body).on('click', '.shopglut-swatch-button:not(.disabled)', function(e) {
            e.preventDefault();
            handleSwatchClick($(this), 'shopglut-buttons-container');
        });

        // Handle color swatch clicks
        $(document.body).on('click', '.shopglut-color-swatch:not(.out-of-stock)', function(e) {
            e.preventDefault();
            handleSwatchClick($(this), 'shopglut-color-swatches-container');
        });

        // Handle image swatch clicks
        $(document.body).on('click', '.shopglut-image-swatch:not(.out-of-stock)', function(e) {
            e.preventDefault();
            handleSwatchClick($(this), 'shopglut-image-swatches-container');
        });

        // Handle dropdown change events
        $(document.body).on('change', '.shopglut-swatch-dropdown', function(e) {
            var $select = $(this);
            var value = $select.val();
            var attribute = $select.data('attribute');

            // Add visual feedback animation
            $select.addClass('option-selected');
            setTimeout(function() {
                $select.removeClass('option-selected');
            }, 300);

            if (!value) {
                return;
            }

            // Prevent infinite loop - check if this is not our own dropdown
            if ($select.hasClass('shopglut-swatch-dropdown')) {
                // Find the variation form
                var $form = $select.closest('.variations_form');

                if ($form.length) {
                    // Update WooCommerce's variation select (excluding our dropdown)
                    var $wcSelect = $form.find('select[name="' + attribute + '"]').not('.shopglut-swatch-dropdown');
                    if ($wcSelect.length && $wcSelect.val() !== value) {
                        $wcSelect.val(value).trigger('change');
                    }
                }

                // Update clear button visibility
                updateClearButtonVisibility();
            }
        });

        // Sync WooCommerce select changes to our custom swatches
        $(document).on('change', '.variations_form select', function(e) {
            var $select = $(this);
            var value = $select.val();
            var attribute = $select.attr('name');

            // Skip if this is our own dropdown (prevent infinite loop)
            if ($select.hasClass('shopglut-swatch-dropdown')) {
                return;
            }

            // Update button swatches
            $('.shopglut-swatch-button[data-attribute="' + attribute + '"]').removeClass('selected');
            if (value) {
                $('.shopglut-swatch-button[data-attribute="' + attribute + '"][data-value="' + value + '"]').addClass('selected');
            }

            // Update color swatches
            $('.shopglut-color-swatch[data-attribute="' + attribute + '"]').removeClass('selected');
            if (value) {
                $('.shopglut-color-swatch[data-attribute="' + attribute + '"][data-value="' + value + '"]').addClass('selected');
            }

            // Update image swatches
            $('.shopglut-image-swatch[data-attribute="' + attribute + '"]').removeClass('selected');
            if (value) {
                $('.shopglut-image-swatch[data-attribute="' + attribute + '"][data-value="' + value + '"]').addClass('selected');
            }

            // Update dropdowns without triggering change event (prevent infinite loop)
            var $dropdown = $('.shopglut-swatch-dropdown[data-attribute="' + attribute + '"]');
            if ($dropdown.length && $dropdown.val() !== value) {
                // Use prop to set value without triggering change event
                $dropdown.prop('value', value);
            }
        });

        // Update swatches availability based on selected variations
        $(document).on('woocommerce_variation_has_changed', '.variations_form', function() {
            var $form = $(this);

            // Get current form data - check both variations selects and shopglut dropdowns
            var currentAttributes = {};

            // First check .variations select (standard WooCommerce dropdowns)
            $form.find('.variations select').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val() || '';
                currentAttributes[name] = value;
            });

            // Also check shopglut dropdowns (for template1 style)
            $form.find('.shopglut-swatch-dropdown').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val() || '';
                currentAttributes[name] = value;
            });

            // Helper function to check if a variation combination is valid
            function isVariationAvailable(attributes) {
                var variations = $form.data('product_variations');
                if (!variations) {
                    return true; // Can't determine, assume available
                }

                for (var i = 0; i < variations.length; i++) {
                    var variation = variations[i];
                    var isMatch = true;
                    var hasAllAttributes = true;

                    for (var attr in attributes) {
                        if (attributes[attr] === '') {
                            continue;
                        }
                        if (!variation.attributes[attr]) {
                            hasAllAttributes = false;
                            break;
                        }
                        if (variation.attributes[attr] !== attributes[attr]) {
                            isMatch = false;
                            break;
                        }
                    }

                    if (isMatch && hasAllAttributes && variation.is_in_stock && variation.is_purchasable) {
                        return true;
                    }
                }

                return false;
            }

            // Update button availability
            $form.find('.shopglut-swatch-button').each(function() {
                var $swatch = $(this);
                var value = $swatch.data('value');
                var attribute = $swatch.data('attribute');

                if ($swatch.hasClass('disabled')) {
                    return; // Already disabled by server
                }

                var testAttributes = $.extend({}, currentAttributes);
                testAttributes[attribute] = value;

                if (isVariationAvailable(testAttributes)) {
                    $swatch.removeClass('unavailable');
                } else {
                    $swatch.addClass('unavailable');
                }
            });

            // Update color swatch availability
            $form.find('.shopglut-color-swatch').each(function() {
                var $swatch = $(this);
                var value = $swatch.data('value');
                var attribute = $swatch.data('attribute');

                if ($swatch.hasClass('out-of-stock')) {
                    return; // Already disabled by server
                }

                var testAttributes = $.extend({}, currentAttributes);
                testAttributes[attribute] = value;

                if (isVariationAvailable(testAttributes)) {
                    $swatch.removeClass('unavailable').css('opacity', '');
                } else {
                    $swatch.addClass('unavailable').css('opacity', '0.3');
                }
            });

            // Update image swatch availability
            $form.find('.shopglut-image-swatch').each(function() {
                var $swatch = $(this);
                var value = $swatch.data('value');
                var attribute = $swatch.data('attribute');

                if ($swatch.hasClass('out-of-stock')) {
                    return; // Already disabled by server
                }

                var testAttributes = $.extend({}, currentAttributes);
                testAttributes[attribute] = value;

                if (isVariationAvailable(testAttributes)) {
                    $swatch.removeClass('unavailable').css('opacity', '');
                } else {
                    $swatch.addClass('unavailable').css('opacity', '0.3');
                }
            });
        });

        // Tooltip functionality for swatches
        function showTooltip($element, text, position) {
            var $tooltip = $('<div class="shopglut-swatch-tooltip">' + text + '</div>');
            $element.append($tooltip);

            setTimeout(function() {
                var offset = $element.offset();
                var width = $element.outerWidth();
                var height = $element.outerHeight();
                var tooltipWidth = $tooltip.outerWidth();
                var tooltipHeight = $tooltip.outerHeight();

                var css = {};
                switch (position) {
                    case 'top':
                        css = {
                            'bottom': (height + 8) + 'px',
                            'left': (width / 2 - tooltipWidth / 2) + 'px'
                        };
                        break;
                    case 'bottom':
                        css = {
                            'top': (height + 8) + 'px',
                            'left': (width / 2 - tooltipWidth / 2) + 'px'
                        };
                        break;
                    case 'left':
                        css = {
                            'right': (width + 8) + 'px',
                            'top': (height / 2 - tooltipHeight / 2) + 'px'
                        };
                        break;
                    case 'right':
                        css = {
                            'left': (width + 8) + 'px',
                            'top': (height / 2 - tooltipHeight / 2) + 'px'
                        };
                        break;
                }

                $tooltip.css(css);
            }, 10);
        }

        $(document).on('mouseenter', '[data-tooltip]', function() {
            var $element = $(this);
            var tooltipText = $element.data('tooltip');
            var tooltipPosition = $element.data('tooltip-position') || 'top';

            // Remove any existing tooltip
            $element.find('.shopglut-swatch-tooltip').remove();

            showTooltip($element, tooltipText, tooltipPosition);
        });

        $(document).on('mouseleave', '[data-tooltip]', function() {
            $(this).find('.shopglut-swatch-tooltip').remove();
        });

        // Initialize swatches on page load
        if ($('.variations_form').length) {
            // Trigger initial availability check
            setTimeout(function() {
                $('.variations_form').trigger('woocommerce_variation_has_changed');
            }, 100);

            // Hide clear button initially (no selections yet)
            updateClearButtonVisibility();

            // Wrap actions (clear button and price) in a container for proper positioning
            wrapActionsContainer();
        }

        // Wrap actions (clear button and price) in a container for proper positioning
        function wrapActionsContainer() {
            var $form = $('.variations_form').first();
            if (!$form.length) {
                return;
            }

            // Check if already wrapped
            if ($form.find('.shopglut-actions-container').length) {
                // Container exists, make sure any new price/clear elements are moved to it
                var $actionsContainer = $form.find('.shopglut-actions-container');
                var $resetButton = $form.find('.shopglut-reset-variations').not($actionsContainer.find('.shopglut-reset-variations'));
                var $priceElement = $form.find('.shopglut-variation-price').not($actionsContainer.find('.shopglut-variation-price'));

                // Move any elements not already in container
                if ($resetButton.length) {
                    $resetButton.appendTo($actionsContainer);
                }
                if ($priceElement.length) {
                    $priceElement.appendTo($actionsContainer);
                }
                return;
            }

            // Find the clear button and price (look everywhere in form)
            var $resetButton = $form.find('.shopglut-reset-variations');
            var $priceElement = $form.find('.shopglut-variation-price');

            // Always create container for template1, template2, template3 or if we have elements
            var isTemplate1 = $form.closest('.shopglut-single-product.template1').length > 0;
            var isTemplate2 = $form.closest('.single-product-template2').length > 0;
            var isTemplate3 = $form.closest('.shopglut-single-product-container').length > 0;

            if ($resetButton.length || $priceElement.length || isTemplate1 || isTemplate2 || isTemplate3) {
                // Create actions container
                var $actionsContainer = $('<div class="shopglut-actions-container"></div>');

                // Insert container after the variations table
                var $variationsTable = $form.find('.variations');
                if ($variationsTable.length) {
                    $actionsContainer.insertAfter($variationsTable);
                } else {
                    // Fallback: insert at end of form
                    $actionsContainer.appendTo($form);
                }

                // Move clear button to container
                if ($resetButton.length) {
                    $resetButton.appendTo($actionsContainer);
                }

                // Move price element to container
                if ($priceElement.length) {
                    $priceElement.appendTo($actionsContainer);
                }
            }
        }

        // Re-wrap actions container when needed
        $(document).on('woocommerce_variation_has_changed', '.variations_form', function() {
            wrapActionsContainer();
        });

        // Price display update functionality
        function updateVariationPriceDisplay() {
            // Don't update price during reset
            if (isResetting) {
                return;
            }

            var $form = $('.variations_form');

            if (!$form.length) {
                return;
            }

            // Get current variation data - check both variations selects and shopglut dropdowns
            var currentAttributes = {};

            // First check .variations select (standard WooCommerce dropdowns)
            $form.find('.variations select').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val() || '';
                currentAttributes[name] = value;
            });

            // Also check shopglut dropdowns (for template1 style)
            $form.find('.shopglut-swatch-dropdown').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val() || '';
                currentAttributes[name] = value;
            });

            // Check if all attributes have values (complete variation selected)
            var allSelected = true;
            for (var attr in currentAttributes) {
                if (!currentAttributes[attr] || currentAttributes[attr] === '') {
                    allSelected = false;
                    break;
                }
            }

            // Find matching variation FIRST, before animating
            var variations = $form.data('product_variations');
            var matchingVariation = null;

            if (variations) {
                for (var i = 0; i < variations.length; i++) {
                    var variation = variations[i];
                    var isMatch = true;
                    var hasAllAttributes = true;

                    for (var attr in currentAttributes) {
                        if (currentAttributes[attr] === '') {
                            hasAllAttributes = false;
                            break;
                        }
                        if (!variation.attributes[attr] || variation.attributes[attr] !== currentAttributes[attr]) {
                            isMatch = false;
                            break;
                        }
                    }

                    if (isMatch && hasAllAttributes) {
                        matchingVariation = variation;
                        break;
                    }
                }
            }

            // Update price display FIRST, before animating
            var priceFound = false;
            var priceUpdated = false;
            $('.shopglut-variation-price').each(function() {
                priceFound = true;
                var $priceEl = $(this);
                var attributeName = $priceEl.data('attribute');
                var isGlobalPrice = $priceEl.hasClass('shopglut-global-price');

                if (matchingVariation && matchingVariation.display_price) {
                    var priceHtml = matchingVariation.price_html || '';
                    if (!priceHtml && matchingVariation.price_html) {
                        priceHtml = matchingVariation.price_html;
                    }

                    // If no HTML, format the price ourselves
                    if (!priceHtml && matchingVariation.display_price) {
                        // Use WooCommerce's formatting if available
                        if (typeof accounting !== 'undefined') {
                            priceHtml = '<span class="amount">' + accounting.formatMoney(matchingVariation.display_price, {
                                symbol: woocommerce_params && woocommerce_params.currency_format_symbol ? woocommerce_params.currency_format_symbol : '$',
                                format: '%s%v',
                                precision: woocommerce_params && woocommerce_params.currency_format_num_decimals ? woocommerce_params.currency_format_num_decimals : 2
                            }) + '</span>';
                        } else {
                            // Fallback simple formatting
                            priceHtml = '$' + matchingVariation.display_price.toFixed(2);
                        }
                    }

                    // Clean up price HTML - remove strikethrough (del) and screen reader text
                    // Keep only the current price (ins element or the price amount)
                    if (priceHtml) {
                        // Create a temporary element to parse the HTML
                        var $temp = $('<div>' + priceHtml + '</div>');

                        // Try to find the current price (ins element)
                        var $currentPrice = $temp.find('ins').first();
                        if ($currentPrice.length) {
                            // Use the ins element content (current price)
                            priceHtml = $currentPrice.html();
                        } else {
                            // No ins element, check if there's a price amount
                            var $priceAmount = $temp.find('.woocommerce-Price-amount').first();
                            if ($priceAmount.length) {
                                // If there are multiple, get the last one (current price is usually last)
                                var $allPrices = $temp.find('.woocommerce-Price-amount');
                                if ($allPrices.length > 1) {
                                    priceHtml = $allPrices.last().parent().html();
                                } else {
                                    priceHtml = $priceAmount.parent().html();
                                }
                            } else {
                                // Fallback to the original HTML
                                // Remove del elements
                                priceHtml = priceHtml.replace(/<del[^>]*>.*?<\/del>/gi, '');
                                // Remove screen-reader-text spans
                                priceHtml = priceHtml.replace(/<span class="screen-reader-text">.*?<\/span>/gi, '');
                            }
                        }
                    }

                    // For global price, always set the HTML (but don't show yet)
                    // For per-attribute prices, set when that attribute has a value
                    if (isGlobalPrice) {
                        if (priceHtml) {
                            $priceEl.html(priceHtml);
                            priceUpdated = true;
                            if (window.console && window.console.log) {
                                console.log('ShopGlut: Price updated to:', priceHtml);
                            }
                        }
                    } else {
                        // Check if this attribute has a value selected
                        var attrName = 'attribute_' + attributeName;
                        var hasValue = currentAttributes[attrName] && currentAttributes[attrName] !== '';
                        if (hasValue) {
                            $priceEl.html(priceHtml);
                            priceUpdated = true;
                        } else {
                            $priceEl.html('');
                        }
                    }
                } else {
                    // No matching variation - clear price
                    if (isGlobalPrice) {
                        // Only clear if all attributes are selected
                        var allSelectedCheck = true;
                        for (var attr in currentAttributes) {
                            if (!currentAttributes[attr] || currentAttributes[attr] === '') {
                                allSelectedCheck = false;
                                break;
                            }
                        }
                        if (allSelectedCheck) {
                            $priceEl.html('');
                        }
                    } else {
                        // Check if this attribute has a value selected
                        var attrName = 'attribute_' + attributeName;
                        var hasValue = currentAttributes[attrName] && currentAttributes[attrName] !== '';
                        if (!hasValue) {
                            $priceEl.html('');
                        }
                    }
                }
            });

            if (window.console && window.console.log) {
                console.log('ShopGlut: Price elements found:', priceFound, 'Price updated:', priceUpdated, 'Matching variation:', matchingVariation ? matchingVariation.variation_id : 'none');
            }

            // NOW animate the elements AFTER price has been populated
            // Add/remove variation-selected class on the product container
            // Support multiple template types: template1, template2, template3
            var $productContainer = $form.closest('.shopglut-single-product, .single-product-template2, .shopglut-single-product-container');
            if (allSelected) {
                $productContainer.addClass('variation-selected');

                // Fade in the variation price and clear button with animation
                var $priceEl = $form.find('.shopglut-variation-price');
                var $resetBtn = $form.find('.shopglut-reset-variations');

                // Remove hidden class first, then animate
                $resetBtn.removeClass('shopglut-reset-hidden');

                // Animate price element
                $priceEl.removeClass('fade-in is-visible').css({
                    'opacity': 0,
                    'transform': 'translateY(-10px)',
                    'display': 'inline-block'  // Override CSS with inline style
                }).addClass('is-visible').animate({
                    'opacity': 1,
                    'transform': 'translateY(0)'
                }, 300, function() {
                    $(this).addClass('fade-in');
                });

                // Animate reset button
                $resetBtn.removeClass('fade-in').css({
                    'opacity': 0,
                    'transform': 'translateY(-10px)'
                }).show().animate({
                    'opacity': 1,
                    'transform': 'translateY(0)'
                }, 300, function() {
                    $(this).addClass('fade-in');
                });
            } else {
                $productContainer.removeClass('variation-selected');

                // Fade out the variation price and clear button
                $form.find('.shopglut-variation-price').removeClass('is-visible').fadeOut(200);
                $form.find('.shopglut-reset-variations').fadeOut(200, function() {
                    $(this).addClass('shopglut-reset-hidden');
                });
            }
        }

        // Update price when variation changes
        $(document).on('woocommerce_variation_has_changed', '.variations_form', function() {
            updateVariationPriceDisplay();
        });

        // Update price when swatch is clicked
        $(document).on('click', '.shopglut-swatch-button, .shopglut-color-swatch, .shopglut-image-swatch', function() {
            setTimeout(function() {
                updateVariationPriceDisplay();
            }, 50);
        });

        // Update price when dropdown changes
        $(document).on('change', '.shopglut-swatch-dropdown', function() {
            setTimeout(function() {
                updateVariationPriceDisplay();
            }, 50);
        });

        // Clear button functionality
        $(document).on('click', '.shopglut-reset-variations', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $button = $(this);

            // Set resetting flag to prevent price updates
            isResetting = true;

            // Find the variation form - try multiple methods
            var $form = $button.closest('.variations_form');

            // If not found directly, try finding through actions container
            if (!$form.length) {
                var $actionsContainer = $button.closest('.shopglut-swatches-actions');
                if ($actionsContainer.length) {
                    // Form is likely before the actions container
                    $form = $actionsContainer.prevAll('.variations_form').first();
                    if (!$form.length) {
                        $form = $actionsContainer.siblings('.variations_form').first();
                    }
                    if (!$form.length) {
                        $form = $actionsContainer.parent().find('.variations_form').first();
                    }
                }
            }

            // Last resort - find first variations form on page
            if (!$form.length) {
                $form = $('.variations_form').first();
            }

            if ($form.length) {
                // Reset all WooCommerce variation selects WITHOUT triggering change
                $form.find('.variations select').val('').each(function() {
                    // Use prop to avoid triggering change event
                    $(this).prop('selectedIndex', 0);
                });

                // Reset all swatch selections
                $form.find('.shopglut-swatch-button, .shopglut-color-swatch, .shopglut-image-swatch').removeClass('selected');

                // Reset all dropdowns
                $form.find('.shopglut-swatch-dropdown').prop('selectedIndex', 0);

                // Clear price displays immediately
                $('.shopglut-variation-price').html('');

                // Also hide WooCommerce's default variation price if visible
                $form.find('.woocommerce-variation-price').hide();

                // Trigger WooCommerce reset event
                $form.trigger('reset_data');

                // Clear resetting flag after a delay to allow all events to complete
                setTimeout(function() {
                    isResetting = false;
                    // Double-check price is cleared
                    $('.shopglut-variation-price').html('');
                    // Hide clear button after reset
                    updateClearButtonVisibility();
                }, 100);
            } else {
                // Form not found, reset flag immediately
                isResetting = false;
            }

            // Hide clear button immediately after reset
            $button.addClass('shopglut-reset-hidden');

            return false;
        });

        // Log initialization
        if (window.console) {
            console.log('ShopGlut Product Swatches initialized');
        }
    });

    // Add tooltip styles dynamically
    $('<style>')
        .prop('type', 'text/css')
        .html('.shopglut-swatch-tooltip {' +
            'position: absolute;' +
            'background: #1f2937;' +
            'color: #ffffff;' +
            'padding: 6px 12px;' +
            'border-radius: 4px;' +
            'font-size: 12px;' +
            'white-space: nowrap;' +
            'z-index: 1000;' +
            'pointer-events: none;' +
            'box-shadow: 0 2px 8px rgba(0,0,0,0.15);' +
            '}' +
            '.shopglut-swatch-tooltip::after {' +
            'content: "";' +
            'position: absolute;' +
            'border: 6px solid transparent;' +
            '}' +
            '[data-tooltip-position="top"] .shopglut-swatch-tooltip::after {' +
            'bottom: 100%;' +
            'left: 50%;' +
            'transform: translateX(-50%);' +
            'border-top-color: #1f2937;' +
            '}' +
            '[data-tooltip-position="bottom"] .shopglut-swatch-tooltip::after {' +
            'top: 100%;' +
            'left: 50%;' +
            'transform: translateX(-50%);' +
            'border-bottom-color: #1f2937;' +
            '}')
        .appendTo('head');

})(jQuery);
