jQuery(document).ready(function($) {
    // Handle PDF generation buttons
    $('.shopglut-pdf-actions a').on('click', function(e) {
        var $button = $(this);
        var originalText = $button.text();
        
        $button.text(shopglut_pdf_invoices.strings.generating);
        $button.prop('disabled', true);
        
        // Reset button after 3 seconds
        setTimeout(function() {
            $button.text(originalText);
            $button.prop('disabled', false);
        }, 3000);
    });
    
    // Handle unmark as printed functionality
    $('.button[href*="unmark_invoice_printed"]').on('click', function(e) {
        if (!confirm('Are you sure you want to unmark this invoice as printed?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Handle bulk actions
    $('#doaction, #doaction2').on('click', function(e) {
        var action = $(this).siblings('select').val();
        
        if (action.indexOf('download_') === 0) {
            var selectedOrders = $('input[name="post[]"]:checked').length;
            
            if (selectedOrders === 0) {
                alert('Please select at least one order.');
                e.preventDefault();
                return false;
            }
            
            if (selectedOrders > 50) {
                if (!confirm('You are about to download ' + selectedOrders + ' documents. This may take some time. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
    
    // Template preview functionality
    $('.template-preview').on('click', function() {
        $('.template-preview').removeClass('selected');
        $(this).addClass('selected');
        
        var templateValue = $(this).data('template');
        var $input = $(this).closest('.csf-field').find('input[type="radio"]');
        $input.prop('checked', false);
        $input.filter('[value="' + templateValue + '"]').prop('checked', true);
    });
    
    // Color picker enhancements
    $('.wp-color-picker-field').each(function() {
        $(this).wpColorPicker({
            change: function(event, ui) {
                // Update live preview if exists
                var colorValue = ui.color.toString();
                var fieldName = $(this).attr('name');
                updateLivePreview(fieldName, colorValue);
            }
        });
    });
    
    // Live preview updates
    function updateLivePreview(fieldName, value) {
        var $preview = $('.invoice-live-preview');
        
        if ($preview.length === 0) {
            return;
        }
        
        switch(fieldName) {
            case 'primary_color':
                $preview.find('.invoice-header').css('border-color', value);
                $preview.find('.invoice-title').css('color', value);
                break;
            case 'secondary_color':
                // Update secondary color elements
                break;
            case 'header_text_color':
                $preview.find('.invoice-header').css('color', value);
                break;
            case 'body_text_color':
                $preview.find('.invoice-container').css('color', value);
                break;
        }
    }
    
    // Test mode warning
    $('input[name="test_mode"]').on('change', function() {
        var $warning = $('.test-mode-notice');
        
        if ($(this).is(':checked')) {
            if ($warning.length === 0) {
                var notice = '<div class="test-mode-notice">' +
                    '<span class="dashicons dashicons-warning"></span>' +
                    'Test mode is enabled. All generated documents will use current settings instead of historical settings.' +
                    '</div>';
                $(this).closest('.csf-field').after(notice);
            }
        } else {
            $warning.remove();
        }
    });
    
    // Debug mode warning
    $('input[name="enable_debug"]').on('change', function() {
        if ($(this).is(':checked')) {
            if (!confirm('Enabling debug mode may reveal sensitive information. Only enable this for troubleshooting. Continue?')) {
                $(this).prop('checked', false);
                return false;
            }
        }
    });
    
    // UBL tax mapping
    $('.add-tax-mapping').on('click', function(e) {
        e.preventDefault();
        
        var $container = $(this).closest('.ubl-tax-mapping');
        var $template = $container.find('.tax-mapping-row').first().clone();
        
        $template.find('select').val('');
        $template.append('<button type="button" class="button remove-tax-mapping">Remove</button>');
        
        $container.find('.tax-mapping-rows').append($template);
    });
    
    $(document).on('click', '.remove-tax-mapping', function(e) {
        e.preventDefault();
        $(this).closest('.tax-mapping-row').remove();
    });
    
    // Danger zone confirmations
    $('.danger-zone .button').on('click', function(e) {
        var action = $(this).text();
        
        if (!confirm('WARNING: This action cannot be undone. Are you sure you want to ' + action.toLowerCase() + '?')) {
            e.preventDefault();
            return false;
        }
        
        if (!confirm('This is your final warning. This action is IRREVERSIBLE. Proceed?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Settings validation
    $('form').on('submit', function(e) {
        var $form = $(this);
        
        // Validate invoice number format
        var numberFormat = $form.find('select[name="invoice_number_format"]').val();
        var prefix = $form.find('input[name="invoice_number_prefix"]').val();
        var padding = parseInt($form.find('input[name="invoice_number_padding"]').val());
        
        if (numberFormat !== 'order_number' && padding < 1) {
            alert('Number padding must be at least 1.');
            e.preventDefault();
            return false;
        }
        
        // Validate due date days
        var dueDateDays = parseInt($form.find('input[name="due_date_days"]').val());
        if (dueDateDays && (dueDateDays < 1 || dueDateDays > 365)) {
            alert('Due date days must be between 1 and 365.');
            e.preventDefault();
            return false;
        }
        
        // Validate cleanup days
        var cleanupDays = parseInt($form.find('input[name="cleanup_days"]').val());
        if (cleanupDays && (cleanupDays < 1 || cleanupDays > 365)) {
            alert('Cleanup days must be between 1 and 365.');
            e.preventDefault();
            return false;
        }
        
        // Validate company information
        var companyName = $form.find('input[name="company_name"]').val();
        if (!companyName.trim()) {
            alert('Company name is required.');
            e.preventDefault();
            return false;
        }
        
        // Validate email format
        var companyEmail = $form.find('input[name="company_email"]').val();
        if (companyEmail && !isValidEmail(companyEmail)) {
            alert('Please enter a valid company email address.');
            e.preventDefault();
            return false;
        }
    });
    
    // Helper function to validate email
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Auto-save functionality for large forms
    var autoSaveTimer;
    $('.csf-field input, .csf-field select, .csf-field textarea').on('change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Could implement auto-save here if needed
        }, 5000);
    });
    
    // Accordion functionality for settings sections
    $('.csf-section-title').on('click', function() {
        var $section = $(this).next('.csf-section');
        $section.slideToggle();
        $(this).find('.dashicons').toggleClass('dashicons-arrow-down dashicons-arrow-up');
    });
    
    // Initialize tooltips (check if tooltip function exists)
    if (typeof $.fn.tooltip !== 'undefined') {
        $('.has-tooltip').tooltip();
    }
    
    // Settings export/import functionality
    $('#export-settings').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: shopglut_pdf_invoices.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_export_settings',
                nonce: shopglut_pdf_invoices.nonce
            },
            success: function(response) {
                if (response.success) {
                    var blob = new Blob([JSON.stringify(response.data, null, 2)], {
                        type: 'application/json'
                    });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'shopglut-pdf-invoices-settings.json';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                } else {
                    alert('Error exporting settings: ' + response.data);
                }
            },
            error: function() {
                alert('Error exporting settings.');
            }
        });
    });
    
    // Handle file import
    $('#import-settings-file').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        
        var reader = new FileReader();
        reader.onload = function(e) {
            try {
                var settings = JSON.parse(e.target.result);
                $('#import-settings-data').val(JSON.stringify(settings, null, 2));
            } catch (error) {
                alert('Invalid settings file format.');
            }
        };
        reader.readAsText(file);
    });
});