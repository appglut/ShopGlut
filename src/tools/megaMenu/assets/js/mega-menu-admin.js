/**
 * ShopGlut Mega Menu Admin JavaScript
 */

(function($) {
    'use strict';

    var ShopGlutMegaMenuAdmin = {
        init: function() {
            this.bindEvents();
            this.initializeColorPickers();
            this.setupValidation();
        },

        bindEvents: function() {
            var self = this;

            // Form submissions
            $(document).on('submit', '#mega-menu-settings-form', function(e) {
                e.preventDefault();
                self.saveSettings($(this));
            });

            $(document).on('submit', '#customize-template-form', function(e) {
                e.preventDefault();
                self.saveTemplateCustomization($(this));
            });

            // Color picker changes
            $(document).on('change', '.color-input', function() {
                self.updatePreviewColor($(this));
            });

            // Settings changes
            $(document).on('change', '#selected-template', function() {
                self.onTemplateChange($(this));
            });

            // Column count change
            $(document).on('change', '#columns', function() {
                self.updatePreviewColumns($(this).val());
            });

            // Image/Product count toggles
            $(document).on('change', '#show_images, #show_product_count', function() {
                self.updatePreviewContent();
            });

            // Menu width change
            $(document).on('input', '#menu_width', function() {
                self.updatePreviewWidth($(this).val());
            });
        },

        initializeColorPickers: function() {
            // Initialize WP color pickers
            $('.color-input').wpColorPicker({
                change: function(event, ui) {
                    var $input = $(event.target);
                    $input.val(ui.color.toString());
                    $(document).trigger('color_picker_change', [$input, ui.color]);
                },
                clear: function(event) {
                    var $input = $(event.target);
                    $input.val('');
                    $(document).trigger('color_picker_clear', [$input]);
                }
            });
        },

        setupValidation: function() {
            // Add form validation
            $(document).on('input', '#menu_width', function() {
                var value = parseInt($(this).val());
                var min = parseInt($(this).attr('min'));
                var max = parseInt($(this).attr('max'));

                if (value < min) {
                    $(this).val(min);
                } else if (value > max) {
                    $(this).val(max);
                }
            });

            $(document).on('input', '#border_radius', function() {
                var value = $(this).val();
                // Allow px, em, rem, etc.
                if (value && !value.match(/^[0-9]+(px|em|rem|%)$/)) {
                    $(this).val(value.replace(/[^0-9]/g, '') + 'px');
                }
            });
        },

        saveSettings: function($form) {
            var self = this;
            var $submitButton = $form.find('button[type="submit"]');
            var originalText = $submitButton.text();

            // Show loading state
            $submitButton.prop('disabled', true).text(shopglutMegaMenu.strings.loading);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: $form.serialize() + '&action=shopglut_save_mega_menu_settings&nonce=' + shopglutMegaMenu.nonce,
                beforeSend: function() {
                    self.showLoadingOverlay();
                },
                success: function(response) {
                    if (response.success) {
                        self.showSuccessMessage(response.data.message || shopglutMegaMenu.strings.saved);
                        // Update config object for preview
                        if (typeof shopglutMegaMenuConfig !== 'undefined') {
                            shopglutMegaMenuConfig.enabled = $('#enable_mega_menu').is(':checked');
                            shopglutMegaMenuConfig.selectedTemplate = $('#selected-template').val();
                        }
                    } else {
                        self.showErrorMessage(shopglutMegaMenu.strings.error);
                    }
                },
                error: function() {
                    self.showErrorMessage(shopglutMegaMenu.strings.error);
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text(originalText);
                    self.hideLoadingOverlay();
                }
            });
        },

        saveTemplateCustomization: function($form) {
            var self = this;
            var $submitButton = $form.find('button[type="submit"]');
            var originalText = $submitButton.text();

            // Show loading state
            $submitButton.prop('disabled', true).text(shopglutMegaMenu.strings.loading);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: $form.serialize() + '&action=shopglut_customize_mega_menu_template&nonce=' + shopglutMegaMenu.nonce,
                beforeSend: function() {
                    self.showLoadingOverlay();
                },
                success: function(response) {
                    if (response.success) {
                        self.showSuccessMessage(response.data.message || shopglutMegaMenu.strings.customized);
                        closeCustomizeModal();

                        // Update preview
                        var templateId = $('#selected-template').val();
                        if (templateId) {
                            self.updateTemplatePreview(templateId);
                        }
                    } else {
                        self.showErrorMessage(shopglutMegaMenu.strings.error);
                    }
                },
                error: function() {
                    self.showErrorMessage(shopglutMegaMenu.strings.error);
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text(originalText);
                    self.hideLoadingOverlay();
                }
            });
        },

        onTemplateChange: function($select) {
            var templateId = $select.val();
            var $customizeBtn = $('#customize-btn');

            $customizeBtn.prop('disabled', !templateId);

            if (templateId) {
                this.loadTemplatePreview(templateId);
                this.loadTemplateCustomization(templateId);
            } else {
                this.showNoTemplateSelected();
            }
        },

        loadTemplatePreview: function(templateId) {
            var self = this;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_mega_menu_template_preview',
                    template_id: templateId,
                    nonce: shopglutMegaMenu.nonce
                },
                beforeSend: function() {
                    self.showPreviewLoading();
                },
                success: function(response) {
                    if (response.success) {
                        $('#template-preview-area').html(response.data.html);
                    } else {
                        self.showPreviewError();
                    }
                },
                error: function() {
                    self.showPreviewError();
                }
            });
        },

        loadTemplateCustomization: function(templateId) {
            // Load existing customization settings for this template
            // This would typically come from the server
            var customSettings = shopglutMegaMenuConfig.customSettings[templateId] || {};

            // Update form fields with existing settings
            if (customSettings.primary_color) {
                $('#primary_color').wpColorPicker('color', customSettings.primary_color);
            }
            if (customSettings.background_color) {
                $('#background_color').wpColorPicker('color', customSettings.background_color);
            }
            if (customSettings.text_color) {
                $('#text_color').wpColorPicker('color', customSettings.text_color);
            }
            if (customSettings.columns) {
                $('#columns').val(customSettings.columns);
            }
            if (customSettings.menu_width) {
                $('#menu_width').val(customSettings.menu_width);
            }
            if (customSettings.border_radius) {
                $('#border_radius').val(customSettings.border_radius);
            }
            if (customSettings.show_images) {
                $('#show_images').prop('checked', customSettings.show_images === '1');
            }
            if (customSettings.show_product_count) {
                $('#show_product_count').prop('checked', customSettings.show_product_count === '1');
            }
        },

        updateTemplatePreview: function(templateId) {
            // Refresh the preview with new settings
            this.loadTemplatePreview(templateId);
        },

        updatePreviewColor: function($input) {
            var color = $input.val();
            var fieldName = $input.attr('name');

            // Update preview based on field name
            switch (fieldName) {
                case 'primary_color':
                    $('.mega-menu-preview .mega-menu-category-title').css('color', color);
                    break;
                case 'background_color':
                    $('.mega-menu-preview').css('background-color', color);
                    break;
                case 'text_color':
                    $('.mega-menu-preview').css('color', color);
                    break;
            }
        },

        updatePreviewColumns: function(columns) {
            var $preview = $('.mega-menu-preview');
            $preview.removeClass('columns-2 columns-3 columns-4 columns-5')
                     .addClass('columns-' + columns);

            // Update grid layout in preview
            $('.mega-menu-content').css('grid-template-columns', 'repeat(' + columns + ', 1fr)');
        },

        updatePreviewContent: function() {
            var showImages = $('#show_images').is(':checked');
            var showCount = $('#show_product_count').is(':checked');

            // Toggle category images
            $('.mega-menu-category-image').toggle(showImages);

            // Toggle product counts
            $('.mega-menu-product-count').toggle(showCount);
        },

        updatePreviewWidth: function(width) {
            $('.mega-menu-preview').css('width', width + 'px');
        },

        showPreviewLoading: function() {
            $('#template-preview-area').html('<div class="loading-spinner"></div>' + shopglutMegaMenu.strings.loading);
        },

        showPreviewError: function() {
            $('#template-preview-area').html('<div class="error-message">' + shopglutMegaMenu.strings.error + '</div>');
        },

        showNoTemplateSelected: function() {
            $('#template-preview-area').html('<div class="no-template-selected"><p>Select a template to see preview</p></div>');
        },

        showLoadingOverlay: function() {
            if (!$('#loading-overlay').length) {
                $('<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;"><div class="loading-spinner"></div></div>').appendTo('body');
            }
            $('#loading-overlay').show();
        },

        hideLoadingOverlay: function() {
            $('#loading-overlay').hide();
        },

        showSuccessMessage: function(message) {
            this.showMessage(message, 'success');
        },

        showErrorMessage: function(message) {
            this.showMessage(message, 'error');
        },

        showMessage: function(message, type) {
            var className = type === 'success' ? 'success-message' : 'error-message';
            var $message = $('<div class="' + className + '">' + message + '</div>');

            // Remove any existing messages
            $('.success-message, .error-message').remove();

            // Add message to top of form
            $('.shopglut-admin-contents').prepend($message);

            // Auto-remove after 5 seconds
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);

            // Scroll to top to show message
            $('html, body').animate({
                scrollTop: $('.shopglut-admin-contents').offset().top - 50
            }, 200);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopGlutMegaMenuAdmin.init();
    });

    // Make available globally
    window.ShopGlutMegaMenuAdmin = ShopGlutMegaMenuAdmin;

})(jQuery);