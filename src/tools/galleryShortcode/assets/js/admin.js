/**
 * ShopGlut Gallery Shortcode Admin JavaScript
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initGalleryAdmin();
    });

    function initGalleryAdmin() {
        initLayoutSelector();
        initColumnControls();
        initFilterToggle();
        initGalleryActions();
        initShortcodeCopy();
        initSearch();
        initBulkActions();
        initColorPickers();
        initMediaUploaders();
    }

    /**
     * Initialize layout selector
     */
    function initLayoutSelector() {
        $('.layout-selector').on('change', function() {
            var layout = $(this).val();
            var $settingsContainer = $('.layout-settings');
            var $allSettings = $settingsContainer.find('> div');

            // Hide all layout-specific settings
            $allSettings.hide();

            // Show settings for selected layout
            switch (layout) {
                case 'grid':
                    $('.grid-settings').show();
                    break;
                case 'isotope':
                    $('.isotope-settings').show();
                    break;
                case 'carousel':
                    $('.carousel-settings').show();
                    break;
                case 'masonry':
                    $('.masonry-settings').show();
                    break;
            }

            // Update column display for grid layouts
            if (layout === 'grid' || layout === 'isotope' || layout === 'masonry') {
                $('.column-controls').show();
            } else {
                $('.column-controls').hide();
            }
        });
    }

    /**
     * Initialize column controls
     */
    function initColumnControls() {
        // Real-time column count updates
        $('select[name="columns"], select[name="columns_tablet"], select[name="columns_mobile"]').on('change', function() {
            var $select = $(this);
            var value = $select.val();
            var $preview = $('.column-preview');

            if ($preview.length) {
                $preview.attr('data-cols', value);
            }
        });
    }

    /**
     * Initialize filter toggle
     */
    function initFilterToggle() {
        $('input[name="enable_filter"]').on('change', function() {
            var checked = $(this).is(':checked');
            $('.filter-position-container').toggle(checked);
        }).trigger('change');
    }

    /**
     * Initialize gallery actions
     */
    function initGalleryActions() {
        // Delete gallery
        $(document).on('click', '.delete-gallery', function(e) {
            e.preventDefault();

            if (!confirm(shopglutGalleryAdmin.strings.confirm_delete)) {
                return;
            }

            var $button = $(this);
            var galleryId = $button.data('id');

            $.ajax({
                url: shopglutGalleryAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'gallery_delete',
                    gallery_id: galleryId,
                    nonce: shopglutGalleryAdmin.nonce
                },
                beforeSend: function() {
                    $button.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || shopglutGalleryAdmin.strings.error_occurred);
                    }
                },
                error: function() {
                    alert(shopglutGalleryAdmin.strings.error_occurred);
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        });

        // Duplicate gallery
        $(document).on('click', '.duplicate-gallery', function(e) {
            e.preventDefault();

            if (!confirm(shopglutGalleryAdmin.strings.confirm_duplicate)) {
                return;
            }

            var $button = $(this);
            var galleryId = $button.data('id');

            $.ajax({
                url: shopglutGalleryAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'gallery_duplicate',
                    gallery_id: galleryId,
                    nonce: shopglutGalleryAdmin.nonce
                },
                beforeSend: function() {
                    $button.prop('disabled', true);
                    $button.text('Duplicating...');
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || shopglutGalleryAdmin.strings.error_occurred);
                    }
                },
                error: function() {
                    alert(shopglutGalleryAdmin.strings.error_occurred);
                },
                complete: function() {
                    $button.prop('disabled', false);
                    $button.text('Duplicate');
                }
            });
        });

        // Save gallery
        $('#gallery-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('#save-gallery');
            var originalText = $submitBtn.val();

            // Validate required fields
            if (!$form.find('#gallery_name').val().trim()) {
                alert('Gallery name is required');
                $form.find('#gallery_name').focus();
                return;
            }

            // Disable submit button
            $submitBtn.prop('disabled', true);
            $submitBtn.val('Saving...');

            $.ajax({
                url: shopglutGalleryAdmin.ajaxurl,
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(shopglutGalleryAdmin.strings.gallery_saved);

                        // Update gallery URL if it's a new gallery
                        var galleryId = response.data.gallery_id;
                        if (galleryId && !$form.find('input[name="gallery_id"]').val()) {
                            var newUrl = window.location.href.split('?')[0] + '?page=shopglut-gallery-shortcode&view=edit&gallery_id=' + galleryId;
                            history.pushState({}, '', newUrl);

                            // Add gallery ID to form
                            $form.find('input[name="gallery_id"]').val(galleryId);

                            // Show shortcode info
                            showShortcodeInfo(response.data.shortcode);
                        }
                    } else {
                        alert(response.data.message || shopglutGalleryAdmin.strings.error_occurred);
                    }
                },
                error: function() {
                    alert(shopglutGalleryAdmin.strings.error_occurred);
                },
                complete: function() {
                    $submitBtn.prop('disabled', false);
                    $submitBtn.val(originalText);
                }
            });
        });
    }

    /**
     * Initialize shortcode copy functionality
     */
    function initShortcodeCopy() {
        $(document).on('click', '.copy-shortcode', function(e) {
            e.preventDefault();

            var $button = $(this);
            var shortcode = $button.data('shortcode');

            // Create temporary textarea to copy shortcode
            var $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(shortcode).select();
            document.execCommand('copy');
            $temp.remove();

            // Update button text
            var originalText = $button.text();
            $button.text('Copied!');

            setTimeout(function() {
                $button.text(originalText);
            }, 2000);
        });
    }

    /**
     * Initialize search functionality
     */
    function initSearch() {
        $('.search-galleries').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            var $rows = $('.shopglut-gallery-table tbody tr');

            $rows.each(function() {
                var $row = $(this);
                var text = $row.text().toLowerCase();

                if (text.includes(searchTerm)) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
        });
    }

    /**
     * Initialize bulk actions
     */
    function initBulkActions() {
        // Select all checkbox
        $('#cb-select-all-1').on('change', function() {
            var checked = $(this).is(':checked');
            $('.shopglut-gallery-table tbody input[type="checkbox"]').prop('checked', checked);
            updateBulkActionButton();
        });

        // Individual checkboxes
        $(document).on('change', '.shopglut-gallery-table tbody input[type="checkbox"]', function() {
            updateBulkActionButton();
        });

        // Bulk delete action
        $('.bulk-delete-btn').on('click', function() {
            var selectedIds = $('.shopglut-gallery-table tbody input[type="checkbox"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one gallery to delete');
                return;
            }

            if (!confirm('Are you sure you want to delete the selected galleries? This action cannot be undone.')) {
                return;
            }

            // Delete galleries one by one
            var deletedCount = 0;
            var failedCount = 0;

            $.each(selectedIds, function(index, galleryId) {
                $.ajax({
                    url: shopglutGalleryAdmin.ajaxurl,
                    type: 'POST',
                    async: false,
                    data: {
                        action: 'gallery_delete',
                        gallery_id: galleryId,
                        nonce: shopglutGalleryAdmin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            deletedCount++;
                        } else {
                            failedCount++;
                        }
                    },
                    error: function() {
                        failedCount++;
                    }
                });
            });

            if (deletedCount > 0) {
                location.reload();
            } else {
                alert('Failed to delete galleries');
            }
        });
    }

    /**
     * Update bulk action button state
     */
    function updateBulkActionButton() {
        var checkedCount = $('.shopglut-gallery-table tbody input[type="checkbox"]:checked').length;
        $('.bulk-delete-btn').prop('disabled', checkedCount === 0);
    }

    /**
     * Initialize color pickers
     */
    function initColorPickers() {
        $('.color-picker').wpColorPicker();
    }

    /**
     * Initialize media uploaders
     */
    function initMediaUploaders() {
        $(document).on('click', '.media-uploader-button', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $input = $button.siblings('input[type="hidden"]');
            var $preview = $button.siblings('.media-preview');

            var frame = wp.media({
                title: 'Select Image',
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.id);

                if ($preview.length) {
                    $preview.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;">');
                }
            });

            frame.open();
        });

        // Remove media
        $(document).on('click', .media-remove-button', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $input = $button.siblings('input[type="hidden"]');
            var $preview = $button.siblings('.media-preview');

            $input.val('');
            $preview.empty();
        });
    }

    /**
     * Show shortcode information
     */
    function showShortcodeInfo(shortcode) {
        var $shortcodeInfo = $('<div class="shortcode-info notice notice-success inline">' +
            '<p><strong>' + shopglutGalleryAdmin.strings.gallery_saved + '</strong></p>' +
            '<p>Shortcode: <code>' + shortcode + '</code> <button class="button button-small copy-shortcode" data-shortcode="' + shortcode + '">Copy</button></p>' +
            '</div>');

        $('#gallery-form').prepend($shortcodeInfo);

        // Auto-hide after 5 seconds
        setTimeout(function() {
            $shortcodeInfo.fadeOut();
        }, 5000);
    }

})(jQuery);