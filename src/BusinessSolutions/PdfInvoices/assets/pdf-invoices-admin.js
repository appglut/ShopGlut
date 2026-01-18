/**
 * ShopGlut PDF Invoices Admin JavaScript
 * Handles interactive functionality for the admin pages
 */

jQuery(document).ready(function($) {
    'use strict';

    // Initialize admin functionality
    var ShopGlutPdfAdmin = {
        
        init: function() {
            this.bindEvents();
            this.initTooltips();
            this.initTabNavigation();
            this.fixLayoutIssues();
        },

        bindEvents: function() {
            // Test invoice button
            $('.test-invoice').on('click', this.handleTestInvoice);
            
            // Bulk actions
            $('.bulk-download-selected, .regenerate-selected').on('click', this.handleBulkActions);
            
            // Tab switching with state preservation
            $('.nav-tab').on('click', this.handleTabSwitch);
            
            // Address sync functionality
            $('.sync-address').on('click', this.handleAddressSync);
            
            // Settings form improvements
            this.enhanceSettingsForm();
        },

        initTooltips: function() {
            // Add tooltips to help icons and buttons
            $('[data-tooltip]').each(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });
        },

        initTabNavigation: function() {
            // Ensure proper tab activation based on URL
            var currentTab = this.getCurrentTabFromURL();
            if (currentTab) {
                $('.nav-tab[href*="tab=' + currentTab + '"]').addClass('nav-tab-active');
            }
            
            // Smooth scrolling for internal links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $($(this).attr('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
        },

        fixLayoutIssues: function() {
            // Fix AGSHOPGLUT framework conflicts
            setTimeout(function() {
                $('.shopglut-pdf-invoices-wrapper .agshopglut-header').hide();
                $('.shopglut-pdf-invoices-wrapper .agshopglut-wrapper').css({
                    'margin': '0',
                    'box-shadow': 'none',
                    'border': 'none'
                });
            }, 100);

            // Ensure proper responsive behavior
            this.handleResponsive();
            $(window).on('resize', this.handleResponsive);
        },

        handleTestInvoice: function(e) {
            e.preventDefault();
            var $button = $(this);
            var originalText = $button.text();
            
            $button.prop('disabled', true)
                   .html('<i class="fa fa-spinner fa-spin"></i> Generating...');

            // Simulate API call (replace with actual AJAX call)
            setTimeout(function() {
                $button.prop('disabled', false).text(originalText);
                ShopGlutPdfAdmin.showNotice('Test invoice generated successfully!', 'success');
            }, 2000);
        },

        handleBulkActions: function(e) {
            e.preventDefault();
            var action = $(this).hasClass('bulk-download-selected') ? 'download' : 'regenerate';
            var selectedItems = $('.document-checkbox:checked').length;
            
            if (selectedItems === 0) {
                ShopGlutPdfAdmin.showNotice('Please select at least one document.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to ' + action + ' ' + selectedItems + ' document(s)?')) {
                // Implement bulk action logic here
                ShopGlutPdfAdmin.showNotice('Bulk ' + action + ' started for ' + selectedItems + ' documents.', 'info');
            }
        },

        handleTabSwitch: function(e) {
            var $tab = $(this);
            var href = $tab.attr('href');
            
            // Add loading state
            if (!$tab.hasClass('nav-tab-active')) {
                $tab.append(' <i class="fa fa-spinner fa-spin" style="font-size: 12px;"></i>');
                
                // Remove loading state after navigation
                setTimeout(function() {
                    $tab.find('.fa-spinner').remove();
                }, 1000);
            }
        },

        enhanceSettingsForm: function() {
            // Auto-save draft changes
            var saveTimeout;
            $('.agshopglut input, .agshopglut select, .agshopglut textarea').on('change', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(function() {
                    // Auto-save logic could go here
                }, 5000);
            });

            // Validate required fields
            $('.agshopglut form').on('submit', function(e) {
                var isValid = true;
                $(this).find('[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('error');
                        isValid = false;
                    } else {
                        $(this).removeClass('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    ShopGlutPdfAdmin.showNotice('Please fill in all required fields.', 'error');
                }
            });
        },

        handleResponsive: function() {
            var windowWidth = $(window).width();
            
            // Adjust layout for mobile
            if (windowWidth < 768) {
                $('.dashboard-grid, .stats-grid').addClass('mobile-layout');
                $('.nav-tab-wrapper').addClass('mobile-tabs');
            } else {
                $('.dashboard-grid, .stats-grid').removeClass('mobile-layout');
                $('.nav-tab-wrapper').removeClass('mobile-tabs');
            }
        },

        getCurrentTabFromURL: function() {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('tab') || 'dashboard';
        },

        showNotice: function(message, type) {
            type = type || 'info';
            var noticeClass = 'notice notice-' + type;
            
            var $notice = $('<div class="' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
            
            // Insert notice after the page title
            $('.wrap h1').after($notice);
            
            // Add dismiss functionality
            $notice.append('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>');
            
            $notice.find('.notice-dismiss').on('click', function() {
                $notice.fadeOut();
            });
            
            // Auto-dismiss success messages
            if (type === 'success') {
                setTimeout(function() {
                    $notice.fadeOut();
                }, 5000);
            }
        },

        handleAddressSync: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var fieldId = $button.attr('id').replace('_action', '');
            var $field = $('#' + fieldId);
            
            // Show loading state
            $button.prop('disabled', true).find('.dashicons').addClass('spin');
            
            // AJAX request to sync address
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_sync_wc_address',
                    nonce: 'shopglut_pdf_sync_nonce',
                    field: fieldId
                },
                success: function(response) {
                    if (response.success) {
                        // Update the field with synced data
                        if (response.data && response.data[fieldId]) {
                            $field.val(response.data[fieldId]).trigger('change');
                        }
                        
                        // Show success message
                        ShopGlutPdfAdmin.showNotice(response.data.message || 'Address synchronized successfully!', 'success');
                    } else {
                        ShopGlutPdfAdmin.showNotice(response.data.message || 'Sync failed. Please try again.', 'error');
                    }
                },
                error: function() {
                    ShopGlutPdfAdmin.showNotice('Network error occurred. Please try again.', 'error');
                },
                complete: function() {
                    // Remove loading state
                    $button.prop('disabled', false).find('.dashicons').removeClass('spin');
                }
            });
        },

        updateBulkActionButtons: function() {
            var checkedBoxes = $('.document-checkbox:checked').length;
            $('.bulk-download-selected, .regenerate-selected').prop('disabled', checkedBoxes === 0);
            
            if (checkedBoxes > 0) {
                $('.bulk-actions-bar').removeClass('hidden');
                $('.bulk-selection-info').html('Selected <strong>' + checkedBoxes + '</strong> document(s)');
            } else {
                $('.bulk-actions-bar').addClass('hidden');
            }
        },

        updateDocumentStats: function() {
            var visibleRows = $('.documents-table tbody tr:visible').length;
            var totalRows = $('.documents-table tbody tr').length;
            
            if (visibleRows !== totalRows) {
                $('.documents-stats-summary').html('Showing ' + visibleRows + ' of ' + totalRows + ' documents');
            }
        },

        // Utility functions
        utils: {
            formatFileSize: function(bytes) {
                if (bytes === 0) return '0 Bytes';
                var k = 1024;
                var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                var i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            formatDate: function(dateString) {
                var date = new Date(dateString);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            },

            debounce: function(func, wait, immediate) {
                var timeout;
                return function() {
                    var context = this, args = arguments;
                    var later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            }
        }
    };

    // Initialize when document is ready
    ShopGlutPdfAdmin.init();

    // Make it globally available
    window.ShopGlutPdfAdmin = ShopGlutPdfAdmin;

    // Additional enhancements for specific page elements
    
    // Enhance action buttons with loading states
    $('.action-button').on('click', function() {
        var $this = $(this);
        if (!$this.hasClass('loading')) {
            $this.addClass('loading');
            setTimeout(function() {
                $this.removeClass('loading');
            }, 1000);
        }
    });

    // Improve checkbox selection behavior
    $('#select-all-documents').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.document-checkbox').prop('checked', isChecked);
        $('.bulk-download-selected, .regenerate-selected').prop('disabled', !isChecked);
    });

    $('.document-checkbox').on('change', function() {
        var totalCheckboxes = $('.document-checkbox').length;
        var checkedCheckboxes = $('.document-checkbox:checked').length;
        
        $('#select-all-documents').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#select-all-documents').prop('checked', checkedCheckboxes === totalCheckboxes);
        
        $('.bulk-download-selected, .regenerate-selected').prop('disabled', checkedCheckboxes === 0);
    });

    // Add search functionality to document lists
    $('#doc_search').on('keyup', ShopGlutPdfAdmin.utils.debounce(function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.documents-table tbody tr').each(function() {
            var rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(searchTerm) > -1);
        });
        ShopGlutPdfAdmin.updateDocumentStats();
    }, 300));

    // Handle filter changes
    $('.document-filter, #doc_filter, #doc_status').on('change', function() {
        // Auto-submit the form when filters change
        $(this).closest('form').submit();
    });

    // Hide notices that are marked as hidden
    $('.documents-header .notice[style*="display: none"]').remove();
    
    // Improve button disabled states
    ShopGlutPdfAdmin.updateBulkActionButtons();

    // Statistics animation on scroll
    $(window).on('scroll', function() {
        $('.stat-number').each(function() {
            var $this = $(this);
            if (!$this.hasClass('animated') && $this.offset().top < $(window).scrollTop() + $(window).height()) {
                $this.addClass('animated');
                var finalValue = parseInt($this.text().replace(/,/g, ''));
                $this.text('0');
                
                $({Counter: 0}).animate({Counter: finalValue}, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.ceil(this.Counter).toLocaleString());
                    },
                    complete: function() {
                        $this.text(finalValue.toLocaleString());
                    }
                });
            }
        });
    });
});

// Global utility functions available to other scripts
window.ShopGlutUtils = {
    showNotice: function(message, type) {
        if (window.ShopGlutPdfAdmin) {
            window.ShopGlutPdfAdmin.showNotice(message, type);
        }
    },
    
    formatFileSize: function(bytes) {
        return window.ShopGlutPdfAdmin.utils.formatFileSize(bytes);
    },
    
    formatDate: function(dateString) {
        return window.ShopGlutPdfAdmin.utils.formatDate(dateString);
    }
};