/**
 * ShopGlut Mega Menu JavaScript
 */
(function($) {
    'use strict';

    var MegaMenuAdmin = {
        
        init: function() {
            this.bindEvents();
            this.initCategorySelector();
        },

        bindEvents: function() {
            // Customize prebuilt template
            $(document).on('click', '.customize-mega-menu', this.openCustomizeModal);
            
            // Edit custom menu
            $(document).on('click', '.edit-custom-menu', this.openEditModal);
            
            // Delete custom menu
            $(document).on('click', '.delete-custom-menu', this.deleteCustomMenu);
            
            // Preview mega menu
            $(document).on('click', '#preview-mega-menu-btn', this.previewMegaMenu);
            
            // Close modals
            $(document).on('click', '.modal-close', this.closeModal);
            
            // Form submission
            $(document).on('submit', '#mega-menu-editor-form', this.saveMegaMenu);
            
            // Category selection
            $(document).on('change', '.category-checkbox', this.onCategorySelect);
            $(document).on('change', '.subcategory-checkbox', this.onSubcategorySelect);
            
            // Form field changes for live preview updates
            $(document).on('input change', '#mega-menu-editor-form input, #mega-menu-editor-form select, #mega-menu-editor-form textarea', this.onFormFieldChange);
        },

        openCustomizeModal: function(e) {
            e.preventDefault();
            var templateId = $(this).data('template');
            MegaMenuAdmin.loadTemplate(templateId);
            $('#editor-title').text('Customize Mega Menu Template');
            $('#mega-menu-editor-modal').fadeIn();
        },

        openEditModal: function(e) {
            e.preventDefault();
            var menuId = $(this).data('id');
            MegaMenuAdmin.loadMegaMenu(menuId);
            $('#editor-title').text('Edit Mega Menu');
            $('#mega-menu-editor-modal').fadeIn();
        },

        deleteCustomMenu: function(e) {
            e.preventDefault();
            var menuId = $(this).data('id');
            var menuName = $(this).closest('.custom-menu-card').find('h4').text();
            
            if (!confirm('Are you sure you want to delete "' + menuName + '"? This action cannot be undone.')) {
                return;
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_mega_menu',
                    menu_id: menuId,
                    nonce: MegaMenuAdmin.getNonce()
                },
                beforeSend: function() {
                    $(e.target).prop('disabled', true).text('Deleting...');
                },
                success: function(response) {
                    if (response.success) {
                        MegaMenuAdmin.showMessage(response.data, 'success');
                        location.reload();
                    } else {
                        MegaMenuAdmin.showMessage(response.data || 'Error deleting mega menu', 'error');
                    }
                },
                error: function() {
                    MegaMenuAdmin.showMessage('Connection error. Please try again.', 'error');
                },
                complete: function() {
                    $(e.target).prop('disabled', false).text('Delete');
                }
            });
        },

        loadTemplate: function(templateId) {
            // Get template data from PHP (this would normally be localized)
            var templates = {
                'category_grid': {
                    'name': 'Category Grid - Custom',
                    'description': 'Customized from Category Grid template',
                    'columns': 4,
                    'width': 800,
                    'bg_color': '#ffffff',
                    'text_color': '#333333',
                    'show_images': true,
                    'show_product_count': true
                },
                'fashion_showcase': {
                    'name': 'Fashion Showcase - Custom',
                    'description': 'Customized from Fashion Showcase template',
                    'columns': 3,
                    'width': 900,
                    'bg_color': '#f8f9fa',
                    'text_color': '#2c3e50',
                    'show_images': true,
                    'show_product_count': false
                },
                'electronics_menu': {
                    'name': 'Electronics Menu - Custom',
                    'description': 'Customized from Electronics template',
                    'columns': 5,
                    'width': 1000,
                    'bg_color': '#1a1a1a',
                    'text_color': '#ffffff',
                    'show_images': true,
                    'show_product_count': true
                },
                'minimal_clean': {
                    'name': 'Minimal Clean - Custom',
                    'description': 'Customized from Minimal template',
                    'columns': 3,
                    'width': 700,
                    'bg_color': '#ffffff',
                    'text_color': '#555555',
                    'show_images': false,
                    'show_product_count': false
                },
                'colorful_modern': {
                    'name': 'Colorful Modern - Custom',
                    'description': 'Customized from Colorful Modern template',
                    'columns': 4,
                    'width': 850,
                    'bg_color': '#667eea',
                    'text_color': '#ffffff',
                    'show_images': true,
                    'show_product_count': true
                }
            };

            var template = templates[templateId];
            if (template) {
                MegaMenuAdmin.populateFormWithTemplate(template);
            }
        },

        populateFormWithTemplate: function(template) {
            $('#menu-id').val('');
            $('#menu-name').val(template.name);
            $('#menu-description').val(template.description);
            $('#menu-columns').val(template.columns);
            $('#menu-width').val(template.width);
            $('#menu-bg-color').val(template.bg_color);
            $('#menu-text-color').val(template.text_color);
            $('#show-images').prop('checked', template.show_images);
            $('#show-product-count').prop('checked', template.show_product_count);
            
            // Reset category selections
            $('.category-checkbox, .subcategory-checkbox').prop('checked', false);
        },

        loadMegaMenu: function(menuId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_mega_menu',
                    menu_id: menuId,
                    nonce: MegaMenuAdmin.getNonce()
                },
                beforeSend: function() {
                    $('.modal-body').html('<div class="mega-menu-loading">Loading mega menu...</div>');
                },
                success: function(response) {
                    if (response.success) {
                        MegaMenuAdmin.populateForm(response.data, menuId);
                    } else {
                        MegaMenuAdmin.showMessage(response.data || 'Error loading mega menu', 'error');
                    }
                },
                error: function() {
                    MegaMenuAdmin.showMessage('Connection error. Please try again.', 'error');
                }
            });
        },

        populateForm: function(menuData, menuId) {
            // Reload the form first
            location.reload(); // Simple approach - in production you might want to reload form content via AJAX
        },

        saveMegaMenu: function(e) {
            e.preventDefault();
            
            var formData = MegaMenuAdmin.getFormData();
            
            if (!MegaMenuAdmin.validateForm(formData)) {
                return false;
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'save_mega_menu',
                    ...formData,
                    nonce: MegaMenuAdmin.getNonce()
                },
                beforeSend: function() {
                    $('#mega-menu-editor-form button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                },
                success: function(response) {
                    if (response.success) {
                        MegaMenuAdmin.showMessage(response.data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        MegaMenuAdmin.showMessage(response.data || 'Error saving mega menu', 'error');
                    }
                },
                error: function() {
                    MegaMenuAdmin.showMessage('Connection error. Please try again.', 'error');
                },
                complete: function() {
                    $('#mega-menu-editor-form button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Save Mega Menu');
                }
            });
        },

        previewMegaMenu: function(e) {
            e.preventDefault();
            MegaMenuAdmin.updatePreviewModal();
            $('#mega-menu-preview-modal').fadeIn();
        },

        closeModal: function(e) {
            e.preventDefault();
            $(this).closest('.shopglut-modal').fadeOut();
        },

        resetForm: function() {
            $('#mega-menu-editor-form')[0].reset();
            $('#menu-id').val('');
            $('.category-checkbox, .subcategory-checkbox').prop('checked', false);
        },

        getFormData: function() {
            var selectedCategories = [];
            $('.category-checkbox:checked, .subcategory-checkbox:checked').each(function() {
                selectedCategories.push($(this).data('id'));
            });

            return {
                menu_id: $('#menu-id').val(),
                menu_name: $('#menu-name').val(),
                menu_description: $('#menu-description').val(),
                menu_type: $('#menu-type').val(),
                menu_columns: $('#menu-columns').val(),
                menu_width: $('#menu-width').val(),
                menu_bg_color: $('#menu-bg-color').val(),
                menu_text_color: $('#menu-text-color').val(),
                show_images: $('#show-images').is(':checked') ? 1 : 0,
                show_product_count: $('#show-product-count').is(':checked') ? 1 : 0,
                selected_categories: selectedCategories
            };
        },

        validateForm: function(formData) {
            if (!formData.menu_name.trim()) {
                MegaMenuAdmin.showMessage('Please enter a menu name', 'error');
                $('#menu-name').focus();
                return false;
            }

            if (formData.selected_categories.length === 0) {
                MegaMenuAdmin.showMessage('Please select at least one category', 'error');
                return false;
            }

            return true;
        },

        initCategorySelector: function() {
            // Initialize category selection behavior
            this.setupCategoryHierarchy();
        },

        setupCategoryHierarchy: function() {
            // When parent category is selected, auto-select all subcategories
            $('.category-checkbox').change(function() {
                var parentId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                $('.subcategory-checkbox[data-parent="' + parentId + '"]').prop('checked', isChecked);
            });
        },

        onCategorySelect: function() {
            var categoryId = $(this).data('id');
            var isChecked = $(this).is(':checked');
            
            // Auto-select/deselect subcategories
            $('.subcategory-checkbox[data-parent="' + categoryId + '"]').prop('checked', isChecked);
        },

        onSubcategorySelect: function() {
            var parentId = $(this).data('parent');
            var parentCheckbox = $('.category-checkbox[data-id="' + parentId + '"]');
            
            // If any subcategory is selected, select parent
            var hasCheckedSubcategories = $('.subcategory-checkbox[data-parent="' + parentId + '"]:checked').length > 0;
            parentCheckbox.prop('checked', hasCheckedSubcategories);
        },

        onMenuTypeChange: function() {
            var menuType = $(this).val();
            // Show/hide relevant sections based on menu type
            if (menuType === 'custom') {
                $('#menu-categories-section').hide();
                // Show custom content builder (to be implemented)
            } else {
                $('#menu-categories-section').show();
            }
        },

        onFormFieldChange: function() {
            // Update preview if modal is open (debounced)
            clearTimeout(MegaMenuAdmin.previewTimeout);
            MegaMenuAdmin.previewTimeout = setTimeout(function() {
                if ($('#mega-menu-preview-modal').is(':visible')) {
                    MegaMenuAdmin.updatePreviewModal();
                }
            }, 500);
        },

        updatePreviewModal: function() {
            var formData = MegaMenuAdmin.getFormData();
            
            // Generate preview HTML
            var previewHtml = MegaMenuAdmin.generatePreviewHtml(formData);
            $('#mega-menu-preview-display').html(previewHtml);
        },

        generatePreviewHtml: function(formData) {
            var selectedCategories = formData.selected_categories;
            var columns = formData.menu_columns || 4;
            var bgColor = formData.menu_bg_color || '#ffffff';
            var textColor = formData.menu_text_color || '#333333';
            var width = formData.menu_width || 800;
            var showImages = formData.show_images;
            var showCount = formData.show_product_count;

            var html = '<div class="shopglut-mega-menu" style="' +
                'background-color: ' + bgColor + '; ' +
                'color: ' + textColor + '; ' +
                'width: ' + width + 'px; ' +
                'padding: 20px; ' +
                'border-radius: 8px; ' +
                'box-shadow: 0 4px 12px rgba(0,0,0,0.1); ' +
                'opacity: 1; ' +
                'visibility: visible; ' +
                'position: relative;' +
                '">';

            html += '<div class="mega-menu-grid" style="' +
                'display: grid; ' +
                'grid-template-columns: repeat(' + columns + ', 1fr); ' +
                'gap: 20px;' +
                '">';

            if (selectedCategories.length === 0) {
                html += '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">Select categories to see preview</div>';
            } else {
                // For preview, show sample category content
                for (var i = 0; i < Math.min(selectedCategories.length, 8); i++) {
                    html += '<div class="mega-menu-category">';
                    
                    if (showImages) {
                        html += '<div class="category-image" style="margin-bottom: 10px; width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #ccc;">📷</div>';
                    }
                    
                    html += '<h4 style="margin: 0 0 10px 0;">';
                    html += 'Category ' + (i + 1);
                    if (showCount) {
                        html += ' <span style="font-size: 12px; opacity: 0.7;">(15)</span>';
                    }
                    html += '</h4>';
                    
                    html += '<ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">';
                    html += '<li style="margin-bottom: 5px;">Subcategory 1</li>';
                    html += '<li style="margin-bottom: 5px;">Subcategory 2</li>';
                    html += '<li style="margin-bottom: 5px;">Subcategory 3</li>';
                    html += '</ul>';
                    
                    html += '</div>';
                }
            }

            html += '</div></div>';

            return html;
        },

        showMessage: function(message, type) {
            var messageClass = type === 'success' ? 'success' : 'error';
            var messageHtml = '<div class="mega-menu-message ' + messageClass + '">' + message + '</div>';
            
            // Remove existing messages
            $('.mega-menu-message').remove();
            
            // Add new message
            $('.shopglut-mega-menu-admin').prepend(messageHtml);
            
            // Auto-remove after 5 seconds
            setTimeout(function() {
                $('.mega-menu-message').fadeOut();
            }, 5000);
        },

        getNonce: function() {
            // In a real implementation, this should be localized from PHP
            return $('#_wpnonce').val() || '';
        },

        previewTimeout: null
    };

    // Frontend Mega Menu Handler
    var MegaMenuFrontend = {
        
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Handle mega menu hover/click
            $('.menu-item-has-mega-menu').hover(
                this.showMegaMenu,
                this.hideMegaMenu
            );
            
            // Handle mobile touch
            $('.menu-item-has-mega-menu > a').on('click', this.toggleMegaMenuMobile);
        },

        showMegaMenu: function() {
            $(this).addClass('mega-menu-active');
        },

        hideMegaMenu: function() {
            $(this).removeClass('mega-menu-active');
        },

        toggleMegaMenuMobile: function(e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                $(this).parent().toggleClass('mega-menu-active');
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Initialize admin functionality if on admin page
        if ($('body').hasClass('admin-page') || $('.shopglut-mega-menu-admin').length) {
            MegaMenuAdmin.init();
        }
        
        // Initialize frontend functionality
        MegaMenuFrontend.init();
    });

})(jQuery);