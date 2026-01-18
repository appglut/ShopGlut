// Add this JavaScript code to your dashboard page or enqueue it as a separate file

jQuery(document).ready(function($) {
    
    // User Type Filter for Recent Activity Table
    $('#user-type-filter').on('change', function() {
        var selectedType = $(this).val();
        var tableRows = $('.dashboard-table tbody tr[data-user-type]');
        
        if (selectedType === 'all') {
            tableRows.show();
        } else {
            tableRows.hide();
            tableRows.filter('[data-user-type="' + selectedType + '"]').show();
        }
        
        // Update view all button text based on filter
        var viewAllBtn = $('.view-all-btn').not('.export-btn');
        if (selectedType === 'all') {
            viewAllBtn.text('View All');
        } else {
            viewAllBtn.text('View All ' + (selectedType === 'guest' ? 'Guest' : 'Registered') + ' Users');
        }
    });
    
    // View All Button for Recent Activity
    $('.view-all-btn').not('.export-btn').on('click', function(e) {
        e.preventDefault();
        var selectedType = $('#user-type-filter').val();
        
        // Create modal or redirect to full page
        showAllActivityModal(selectedType);
    });
    
    // Export Top Products
    $('.export-btn[data-export="top-products"]').on('click', function(e) {
        e.preventDefault();
        exportTopProducts();
    });
    
    // Quick Actions
    $('.quick-action-btn').on('click', function() {
        var action = $(this).data('action');
        
        switch(action) {
            case 'export-all':
                exportAllData();
                break;
            case 'export-guest':
                exportGuestData();
                break;
            case 'export-registered':
                exportRegisteredUsers();
                break;
            case 'clear-old':
                clearOldData();
                break;
            // import-data is handled by onclick in HTML
        }
    });
    
  // Import File Handler
    $('#import-file').on('change', function() {
        var file = this.files[0];
        if (file) {
            handleImportFile(file);
        }
    });
    
    // Import Modal Controls
    $('#cancel-import').on('click', function() {
        $('#import-modal').hide();
        window.shopglut_import_ready = false;
    });
    
    $('#confirm-import').on('click', function() {
        confirmImport();
    });
    
    $('.close-modal').on('click', function() {
        $('#import-modal').hide();
        window.shopglut_import_ready = false;
    });
    // Functions
    
    function showAllActivityModal(userType) {
        var modal = $('<div class="shopglut-modal" id="activity-modal">');
        var modalContent = $('<div class="modal-content">');
        var modalHeader = $('<div class="modal-header">');
        var modalBody = $('<div class="modal-body">');
        var modalFooter = $('<div class="modal-footer">');
        
        modalHeader.html('<h3>All Recent Activity' + (userType !== 'all' ? ' - ' + (userType === 'guest' ? 'Guest' : 'Registered') + ' Users' : '') + '</h3><span class="close-modal">&times;</span>');
        modalBody.html('<div class="loading">Loading all activity...</div>');
        modalFooter.html('<button class="button button-secondary close-modal">Close</button>');
        
        modalContent.append(modalHeader, modalBody, modalFooter);
        modal.append(modalContent);
        $('body').append(modal);
        
        modal.show();
        
        // Load full activity data via AJAX
        $.ajax({
            url: shopglut_wishlist_admin_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'get_all_activity',
                user_type: userType,
                nonce: shopglut_wishlist_admin_dashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    modalBody.html(response.data.html);
                } else {
                    modalBody.html('<div class="error">Failed to load activity data.</div>');
                }
            },
            error: function() {
                modalBody.html('<div class="error">Error loading activity data.</div>');
            }
        });
        
        // Close modal handlers
        modal.find('.close-modal').on('click', function() {
            modal.remove();
        });
    }
    
    function exportTopProducts() {
        showLoadingState('Exporting top products...');
        
        $.ajax({
            url: shopglut_wishlist_admin_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'export_top_products',
                nonce: shopglut_wishlist_admin_dashboard.nonce
            },
            success: function(response) {
                hideLoadingState();
                if (response.success) {
                    downloadFile(response.data.filename, response.data.content, 'text/csv');
                    showNotification('Top products exported successfully!', 'success');
                } else {
                    showNotification('Export failed: ' + response.data.message, 'error');
                }
            },
            error: function() {
                hideLoadingState();
                showNotification('Export failed due to server error.', 'error');
            }
        });
    }
    
   function exportAllData() {
    if (!confirm('This will export all wishlist data. Continue?')) return;
    
    showLoadingState('Exporting all data...');
    
    $.ajax({
        url: shopglut_wishlist_admin_dashboard.ajax_url,
        type: 'POST',
        data: {
            action: 'export_all_data',
            nonce: shopglut_wishlist_admin_dashboard.nonce
        },
        success: function(response) {
            hideLoadingState();
            if (response.success) {
                downloadFile(response.data.filename, response.data.content, 'text/csv');
                showNotification('Successfully exported ' + response.data.total_records + ' records!', 'success');
            } else {
                showNotification('Export failed: ' + response.data.message, 'error');
            }
        },
        error: function() {
            hideLoadingState();
            showNotification('Export failed due to server error.', 'error');
        }
    });
}

    // Enhanced download function to handle larger files
    function downloadFile(filename, content, mimeType) {
        try {
            // Create blob
            const blob = new Blob([content], { type: mimeType + ';charset=utf-8;' });
            
            // Create download link
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            
            // Add to DOM, click, and remove
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Clean up
            URL.revokeObjectURL(url);
            
        } catch (error) {
            console.error('Download failed:', error);
            showNotification('Download failed. Please try again.', 'error');
        }
    }
    
    function exportGuestData() {
        showLoadingState('Exporting guest data...');
        
        $.ajax({
            url: shopglut_wishlist_admin_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'export_guest_data',
                nonce: shopglut_wishlist_admin_dashboard.nonce
            },
            success: function(response) {
                hideLoadingState();
                if (response.success) {
                    downloadFile(response.data.filename, response.data.content, 'text/csv');
                    showNotification('Guest data exported successfully!', 'success');
                } else {
                    showNotification('Export failed: ' + response.data.message, 'error');
                }
            },
            error: function() {
                hideLoadingState();
                showNotification('Export failed due to server error.', 'error');
            }
        });
    }
    
    function exportRegisteredUsers() {
        showLoadingState('Exporting registered users data...');
        
        $.ajax({
            url: shopglut_wishlist_admin_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'export_registered_data',
                nonce: shopglut_wishlist_admin_dashboard.nonce
            },
            success: function(response) {
                hideLoadingState();
                if (response.success) {
                    downloadFile(response.data.filename, response.data.content, 'text/csv');
                    showNotification('Registered users data exported successfully!', 'success');
                } else {
                    showNotification('Export failed: ' + response.data.message, 'error');
                }
            },
            error: function() {
                hideLoadingState();
                showNotification('Export failed due to server error.', 'error');
            }
        });
    }
    
    function clearOldData() {
        var daysOld = prompt('Delete data older than how many days? (Default: 90)', '90');
        if (daysOld === null) return;
        
        daysOld = parseInt(daysOld) || 90;
        
        if (!confirm('This will permanently delete wishlist data older than ' + daysOld + ' days. This action cannot be undone. Continue?')) {
            return;
        }
        
        showLoadingState('Cleaning old data...');
        
        $.ajax({
            url: shopglut_wishlist_admin_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'clear_old_data',
                days_old: daysOld,
                nonce: shopglut_wishlist_admin_dashboard.nonce
            },
            success: function(response) {
                hideLoadingState();
                if (response.success) {
                    showNotification('Cleaned ' + response.data.deleted_count + ' old wishlist entries.', 'success');
                    // Refresh the dashboard
                    // setTimeout(function() {
                    //     location.reload();
                    // }, 2000);
                } else {
                    showNotification('Cleanup failed: ' + response.data.message, 'error');
                }
            },
            error: function() {
                hideLoadingState();
                showNotification('Cleanup failed due to server error.', 'error');
            }
        });
    }

    // Update your existing handleImportFile function
function handleImportFile(file) {
    if (!file) return;
    
    const formData = new FormData();
    formData.append('import_file', file);
    formData.append('action', 'upload_import_file');
    formData.append('nonce', shopglut_wishlist_admin_dashboard.nonce);
    
    showLoadingState('Processing file...');
    
    $.ajax({
        url: shopglut_wishlist_admin_dashboard.ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            hideLoadingState();
            
            if (response.success) {
                // Display file preview
                displayFilePreview(response.data);
                // Generate field mapping UI
                generateFieldMapping(response.data);
                // Show the import modal
                $('#import-modal').show();
                // Set flag that file is ready
                window.shopglut_import_ready = true;
            } else {
                showNotification('File upload failed: ' + response.data.message, 'error');
            }
        },
        error: function() {
            hideLoadingState();
            showNotification('File upload failed due to server error.', 'error');
        }
    });
}

// Update your existing confirmImport function
function confirmImport() {
    // Check if we have file data ready
    if (!window.shopglut_import_ready) {
        showNotification('Please upload a file first before importing.', 'error');
        return;
    }
    
    var importType = $('input[name="import_type"]:checked').val();
    var fieldMapping = {};
    
    $('#field-mapping select').each(function() {
        var field = $(this).data('field');
        var value = $(this).val();
        if (value) {
            fieldMapping[field] = value;
        }
    });
    
    showLoadingState('Importing data...');
    
    $.ajax({
        url: shopglut_wishlist_admin_dashboard.ajax_url,
        type: 'POST',
        data: {
            action: 'import_wishlist_data',
            import_type: importType,
            field_mapping: fieldMapping,
            nonce: shopglut_wishlist_admin_dashboard.nonce
        },
        success: function(response) {
            hideLoadingState();
            $('#import-modal').hide();
            
            if (response.success) {
                showNotification('Data imported successfully! ' + response.data.imported_count + ' records processed.', 'success');
                // Reset the flag
                window.shopglut_import_ready = false;
                // Refresh the dashboard
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                showNotification('Import failed: ' + response.data.message, 'error');
            }
        },
        error: function() {
            hideLoadingState();
            $('#import-modal').hide();
            showNotification('Import failed due to server error.', 'error');
        }
    });
}

// Add these helper functions
function displayFilePreview(data) {
    let previewHtml = '<div class="file-preview-container">';
    
    if (data.type === 'csv') {
        previewHtml += '<h5>CSV Preview (showing first 5 rows):</h5>';
        previewHtml += '<table class="widefat striped">';
        
        // Headers
        previewHtml += '<thead><tr>';
        data.headers.forEach(function(header) {
            previewHtml += '<th>' + header + '</th>';
        });
        previewHtml += '</tr></thead>';
        
        // Preview rows
        previewHtml += '<tbody>';
        data.preview_rows.forEach(function(row) {
            previewHtml += '<tr>';
            row.forEach(function(cell) {
                previewHtml += '<td>' + (cell || '') + '</td>';
            });
            previewHtml += '</tr>';
        });
        previewHtml += '</tbody>';
        previewHtml += '</table>';
        
    } else if (data.type === 'json') {
        previewHtml += '<h5>JSON Preview (showing first 5 records):</h5>';
        previewHtml += '<pre style="background: #f1f1f1; padding: 10px; max-height: 200px; overflow-y: auto;">' + JSON.stringify(data.data, null, 2) + '</pre>';
    }
    
    previewHtml += '</div>';
    $('#file-preview').html(previewHtml);
}

function generateFieldMapping(data) {
    let mappingHtml = '<div class="field-mapping-container">';
    
    // Database fields that match your actual table structure
    const dbFields = {
        'wish_user_id': 'User ID',
        'username': 'Username', 
        'useremail': 'User Email',
        'product_ids': 'Product IDs',
        'product_meta': 'Product Meta Data',
        'wishlist_notifications': 'Wishlist Notifications',
        'product_added_time': 'Date Added',
        'product_individual_dates': 'Individual Product Dates',
        'share_data': 'Share Data'
    };
    
    let availableColumns = [];
    if (data.type === 'csv') {
        availableColumns = data.headers;
    } else if (data.type === 'json' && data.data.length > 0) {
        availableColumns = Object.keys(data.data[0]);
    }
    
    mappingHtml += '<table class="widefat striped">';
    mappingHtml += '<thead><tr><th>Database Field</th><th>Source Column</th><th>Required</th></tr></thead>';
    mappingHtml += '<tbody>';
    
    // Required fields
    const requiredFields = ['wish_user_id', 'username', 'useremail', 'product_ids'];
    
    Object.keys(dbFields).forEach(function(field) {
        mappingHtml += '<tr>';
        mappingHtml += '<td><strong>' + dbFields[field] + '</strong></td>';
        mappingHtml += '<td><select data-field="' + field + '" class="regular-text">';
        mappingHtml += '<option value="">-- Select Column --</option>';
        
        availableColumns.forEach(function(column) {
            let selected = '';
            // Auto-match similar field names
            if (field.toLowerCase() === column.toLowerCase() || 
                field.toLowerCase().includes(column.toLowerCase()) ||
                column.toLowerCase().includes(field.toLowerCase()) ||
                (field === 'wish_user_id' && column.toLowerCase().includes('user')) ||
                (field === 'product_ids' && column.toLowerCase().includes('product'))) {
                selected = 'selected';
            }
            mappingHtml += '<option value="' + column + '" ' + selected + '>' + column + '</option>';
        });
        
        mappingHtml += '</select></td>';
        mappingHtml += '<td>' + (requiredFields.includes(field) ? '<span style="color: red;">Required</span>' : 'Optional') + '</td>';
        mappingHtml += '</tr>';
    });
    
    mappingHtml += '</tbody>';
    mappingHtml += '</table>';
    mappingHtml += '</div>';
    
    $('#field-mapping').html(mappingHtml);
}
    
    function showLoadingState(message) {
        if ($('#loading-overlay').length === 0) {
            $('body').append('<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;"><div class="loading-content"><div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>' + message + '</div></div>');
        }
    }
    
    function hideLoadingState() {
        $('#loading-overlay').remove();
    }
    
    function showNotification(message, type) {
        var notification = $('<div class="dashboard-notification ' + type + '" style="position: fixed; top: 20px; right: 20px; padding: 15px 20px; background: ' + (type === 'success' ? '#4CAF50' : '#f44336') + '; color: white; border-radius: 5px; z-index: 10000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">' + message + '</div>');
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(function() {
                notification.remove();
            });
        }, 5000);
    }
    
    // Add CSS for spinner animation
    $('<style>').text(`
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .dashboard-notification {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `).appendTo('head');
});