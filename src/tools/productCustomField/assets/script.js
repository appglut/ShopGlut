/**
 * Product Custom Field - Frontend JavaScript
 *
 * Handles interactions for textarea field designs (accordion, tabs, etc.)
 */

jQuery(document).ready(function($) {
    'use strict';

    // =========================
    // Textarea Field Interactions
    // =========================

    // Accordion functionality
    $('.shopglut-content-accordion .shopglut-accordion-title').on('click', function() {
        const $item = $(this).parent('.shopglut-accordion-item');
        const $accordion = $(this).closest('.shopglut-accordion');

        // Check if this is an accordion that allows multiple open items
        const allowMultiple = $accordion.hasClass('allow-multiple');

        if (!allowMultiple) {
            // Close all other accordion items in this accordion
            $accordion.find('.shopglut-accordion-item').not($item).removeClass('active');
            $accordion.find('.shopglut-accordion-content').not($item.find('.shopglut-accordion-content')).css('max-height', '0');
        }

        // Toggle current item
        $item.toggleClass('active');

        if ($item.hasClass('active')) {
            // Open the current item
            const $content = $item.find('.shopglut-accordion-content');
            const contentHeight = $content.prop('scrollHeight');
            $content.css('max-height', contentHeight + 'px');
        } else {
            // Close the current item
            $item.find('.shopglut-accordion-content').css('max-height', '0');
        }
    });

    // Tabs functionality
    $('.shopglut-content-tabs .shopglut-tab-header').on('click', function() {
        const $tabHeader = $(this);
        const $tabs = $(this).closest('.shopglut-tabs');
        const tabIndex = $tabHeader.data('tab');

        // Remove active class from all headers and contents in this tab group
        $tabs.find('.shopglut-tab-header').removeClass('active');
        $tabs.find('.shopglut-tab-content').removeClass('active');

        // Add active class to clicked header and corresponding content
        $tabHeader.addClass('active');
        $tabs.find('.shopglut-tab-content[data-tab="' + tabIndex + '"]').addClass('active');
    });

    // Initialize first tab as active if none is selected
    $('.shopglut-content-tabs').each(function() {
        if (!$(this).find('.shopglut-tab-header.active').length) {
            $(this).find('.shopglut-tab-header:first').trigger('click');
        }
    });

    // Initialize first accordion item as active if none is selected and auto-open is enabled
    $('.shopglut-content-accordion.auto-open-first').each(function() {
        if (!$(this).find('.shopglut-accordion-item.active').length) {
            $(this).find('.shopglut-accordion-item:first .shopglut-accordion-title').trigger('click');
        }
    });

    // Smooth scroll to content when hash is present
    if (window.location.hash) {
        const targetId = window.location.hash.substring(1);
        const $targetElement = $('#' + targetId + ', [data-tab="' + targetId + '"]');

        if ($targetElement.length) {
            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $targetElement.offset().top - 100
                }, 500);

                // If it's a tab, activate it
                if ($targetElement.hasClass('shopglut-tab-header')) {
                    $targetElement.trigger('click');
                }

                // If it's an accordion item, open it
                if ($targetElement.hasClass('shopglut-accordion-title')) {
                    $targetElement.trigger('click');
                }
            }, 100);
        }
    }

    // Add hash change handling
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            const targetId = window.location.hash.substring(1);
            const $targetElement = $('#' + targetId + ', [data-tab="' + targetId + '"]');

            if ($targetElement.length) {
                $('html, body').animate({
                    scrollTop: $targetElement.offset().top - 100
                }, 500);

                // If it's a tab, activate it
                if ($targetElement.hasClass('shopglut-tab-header')) {
                    $targetElement.trigger('click');
                }
            }
        }
    });

    // Keyboard navigation for tabs
    $('.shopglut-content-tabs .shopglut-tab-header').on('keydown', function(e) {
        const $tabHeaders = $(this).parent().find('.shopglut-tab-header');
        const currentIndex = $tabHeaders.index(this);
        let newIndex = currentIndex;

        switch (e.keyCode) {
            case 37: // Left arrow
                newIndex = currentIndex > 0 ? currentIndex - 1 : $tabHeaders.length - 1;
                break;
            case 39: // Right arrow
                newIndex = currentIndex < $tabHeaders.length - 1 ? currentIndex + 1 : 0;
                break;
            case 36: // Home
                newIndex = 0;
                break;
            case 35: // End
                newIndex = $tabHeaders.length - 1;
                break;
            default:
                return;
        }

        if (newIndex !== currentIndex) {
            e.preventDefault();
            $tabHeaders.eq(newIndex).focus().trigger('click');
        }
    });

    // Add ARIA attributes for better accessibility
    $('.shopglut-content-accordion .shopglut-accordion-title').attr('role', 'button');
    $('.shopglut-content-accordion .shopglut-accordion-title').attr('aria-expanded', 'false');
    $('.shopglut-content-accordion .shopglut-accordion-content').attr('role', 'region');

    // Update ARIA attributes when accordion is toggled
    $('.shopglut-content-accordion .shopglut-accordion-title').on('click', function() {
        const $item = $(this).parent('.shopglut-accordion-item');
        const isExpanded = $item.hasClass('active');
        $(this).attr('aria-expanded', isExpanded ? 'true' : 'false');
    });

    // Initialize ARIA attributes for active accordion items
    $('.shopglut-content-accordion .shopglut-accordion-item.active .shopglut-accordion-title').attr('aria-expanded', 'true');

    // Add ARIA attributes for tabs
    $('.shopglut-content-tabs').attr('role', 'tablist');
    $('.shopglut-content-tabs .shopglut-tab-header').attr('role', 'tab');
    $('.shopglut-content-tabs .shopglut-tab-content').attr('role', 'tabpanel');

    // Update ARIA attributes for active tabs
    $('.shopglut-content-tabs .shopglut-tab-header').on('click', function() {
        const $tabs = $(this).closest('.shopglut-tabs');
        const tabIndex = $(this).data('tab');

        // Remove aria-selected from all tabs and hide all panels
        $tabs.find('.shopglut-tab-header').attr('aria-selected', 'false');
        $tabs.find('.shopglut-tab-content').attr('aria-hidden', 'true');

        // Add aria-selected to active tab and show panel
        $(this).attr('aria-selected', 'true');
        $tabs.find('.shopglut-tab-content[data-tab="' + tabIndex + '"]').attr('aria-hidden', 'false');
    });

    // Initialize ARIA attributes for active tabs
    $('.shopglut-content-tabs .shopglut-tab-header.active').attr('aria-selected', 'true');
    $('.shopglut-content-tabs .shopglut-tab-content.active').attr('aria-hidden', 'false');

    // Handle window resize for responsive behavior
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Recalculate accordion content heights
            $('.shopglut-content-accordion .shopglut-accordion-item.active').each(function() {
                const $content = $(this).find('.shopglut-accordion-content');
                const contentHeight = $content.prop('scrollHeight');
                $content.css('max-height', contentHeight + 'px');
            });
        }, 250);
    });

    // =========================
    // Radio Field Interactions
    // =========================

    // Card Style Radio Interaction
    $('.shopglut-content-card_style .shopglut-radio-card-item input[type="radio"]').on('change', function() {
        const $cardItem = $(this).closest('.shopglut-radio-cards');
        const $selectedCard = $(this).closest('.shopglut-radio-card-item');

        // Remove selected class from all cards in this group
        $cardItem.find('.shopglut-radio-card-item').removeClass('selected');

        // Add selected class to the clicked card
        $selectedCard.addClass('selected');
    });

    // Initialize selected state for card style radios
    $('.shopglut-content-card_style input[type="radio"]:checked').each(function() {
        $(this).closest('.shopglut-radio-card-item').addClass('selected');
    });

    // Enhanced visual feedback for all radio interactions
    $('.shopglut-radio-field input[type="radio"]').on('change', function() {
        const $radioField = $(this).closest('.shopglut-radio-field');
        const $changedRadio = $(this);

        // Add animation class to the changed radio item
        const $radioItem = $changedRadio.closest('.shopglut-radio-item, .shopglut-radio-button-item, .shopglut-radio-card-item');
        $radioItem.addClass('shopglut-radio-changed');

        // Remove animation class after animation completes
        setTimeout(function() {
            $radioItem.removeClass('shopglut-radio-changed');
        }, 500);

        // Trigger custom event for external scripts
        $radioField.trigger('shopglut_radio_changed', [$(this).val(), $changedRadio]);
    });

    // Keyboard navigation for radio groups
    $('.shopglut-radio-field').on('keydown', function(e) {
        const $radios = $(this).find('input[type="radio"]');
        const $currentRadio = $(this).find('input[type="radio"]:focus');

        if (!$currentRadio.length) return;

        const currentIndex = $radios.index($currentRadio);
        let newIndex = currentIndex;

        switch (e.keyCode) {
            case 37: // Left arrow
            case 38: // Up arrow
                e.preventDefault();
                newIndex = currentIndex > 0 ? currentIndex - 1 : $radios.length - 1;
                break;
            case 39: // Right arrow
            case 40: // Down arrow
                e.preventDefault();
                newIndex = currentIndex < $radios.length - 1 ? currentIndex + 1 : 0;
                break;
            case 13: // Enter
            case 32: // Space
                e.preventDefault();
                $currentRadio.prop('checked', true).trigger('change');
                return;
            default:
                return;
        }

        if (newIndex !== currentIndex) {
            $radios.eq(newIndex).prop('checked', true).trigger('change').focus();
        }
    });

    // Add focus styles for accessibility
    $('.shopglut-radio-field input[type="radio"]').on('focus', function() {
        $(this).closest('.shopglut-radio-item, .shopglut-radio-button-item, .shopglut-radio-card-item').addClass('shopglut-radio-focused');
    });

    $('.shopglut-radio-field input[type="radio"]').on('blur', function() {
        $(this).closest('.shopglut-radio-item, .shopglut-radio-button-item, .shopglut-radio-card-item').removeClass('shopglut-radio-focused');
    });

    // Dropdown Style Radio Enhancement
    $('.shopglut-content-dropdown .shopglut-dropdown-select').on('change', function() {
        const $dropdown = $(this);
        const $radioField = $dropdown.closest('.shopglut-radio-field');

        // Add visual feedback
        $dropdown.addClass('shopglut-dropdown-changed');

        setTimeout(function() {
            $dropdown.removeClass('shopglut-dropdown-changed');
        }, 500);

        // Trigger custom event
        $radioField.trigger('shopglut_dropdown_changed', [$dropdown.val()]);
    });

    // Form validation for required radio fields
    function validateRadioFields() {
        let isValid = true;
        const $requiredRadios = $('.shopglut-radio-field .shopglut-radio-label.required');

        $requiredRadios.each(function() {
            const $field = $(this).closest('.shopglut-radio-field');
            const $checkedRadio = $field.find('input[type="radio"]:checked');

            if (!$checkedRadio.length) {
                $field.addClass('shopglut-radio-error');
                isValid = false;

                // Add error message if not already present
                if (!$field.find('.shopglut-error-message').length) {
                    const $errorMessage = $('<div class="shopglut-error-message">' +
                        shopglut_custom_field_vars.required_message || 'Please select an option.' +
                        '</div>');
                    $field.append($errorMessage);
                }
            } else {
                $field.removeClass('shopglut-radio-error');
                $field.find('.shopglut-error-message').remove();
            }
        });

        return isValid;
    }

    // Auto-save radio field values (if enabled)
    function autoSaveRadioValue($radioInput) {
        if (typeof shopglut_custom_field_vars !== 'undefined' && shopglut_custom_field_vars.auto_save) {
            const $field = $radioInput.closest('.shopglut-radio-field');
            const fieldName = $radioInput.attr('name');
            const fieldValue = $radioInput.val();

            // Create AJAX request to save value
            $.ajax({
                url: shopglut_custom_field_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_save_radio_field',
                    field_name: fieldName,
                    field_value: fieldValue,
                    post_id: shopglut_custom_field_vars.post_id,
                    nonce: shopglut_custom_field_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $field.addClass('shopglut-radio-saved');
                        setTimeout(function() {
                            $field.removeClass('shopglut-radio-saved');
                        }, 2000);
                    }
                }
            });
        }
    }

    // Bind auto-save to radio change events
    $('.shopglut-radio-field input[type="radio"]').on('change', function() {
        autoSaveRadioValue($(this));
    });

    // Bind validation to form submission
    $('form').on('submit', function(e) {
        if (!validateRadioFields()) {
            e.preventDefault();

            // Scroll to first error
            const $firstError = $('.shopglut-radio-error').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
            }
        }
    });

    // Enhanced animations for button style radios
    $('.shopglut-content-inline_buttons .shopglut-radio-button-label').on('click', function() {
        const $label = $(this);
        const $input = $label.find('input[type="radio"]');

        // Add ripple effect
        const ripple = $('<span class="shopglut-button-ripple"></span>');
        $label.append(ripple);

        setTimeout(function() {
            ripple.remove();
        }, 600);
    });

    // Initialize tooltips for radio fields if data-tooltip attributes exist
    $('.shopglut-radio-field [data-tooltip]').each(function() {
        const $element = $(this);
        const tooltip = $element.data('tooltip');

        $element.attr('title', tooltip);

        // You can integrate with a tooltip library here
        // For now, we'll just use the browser's default tooltip
    });

    // Trigger a custom event when radio fields are ready
    $(document).trigger('shopglut_radio_fields_ready');
});