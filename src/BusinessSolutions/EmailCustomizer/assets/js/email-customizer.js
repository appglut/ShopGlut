/**
 * ShopGlut Email Customizer - Professional Email Builder
 */

(function($) {
    'use strict';
    
    // Global variables
    let selectedElement = null;
    let selectedComponent = null;
    let currentTemplate = 'new-order';
    let componentCounter = 0;
    let dragDropInitialized = false;

    // Initialize Email Customizer
    function initializeEmailCustomizer() {
        // Event handlers for templates overview
        $('.customize-template-btn').on('click', function(e) {
            e.preventDefault();
            const template = $(this).data('template');
            openEmailBuilder(template);
        });

        $('.close-customizer').on('click', function(e) {
            e.preventDefault();
            $('#templates-overview').show();
            $('#email-builder').hide();
        });

        // Template table interactions
        $('.template-checkbox').on('change', function() {
            updateBulkActionsState();
        });

        $('#select-all-templates').on('change', function() {
            const checked = $(this).is(':checked');
            $('.template-checkbox').prop('checked', checked);
            updateBulkActionsState();
        });

        // Search functionality
        $('.search-templates').on('input', function() {
            const query = $(this).val();
            filterTemplates(query);
        });

        $('.filter-templates').on('change', function() {
            const status = $(this).val();
            filterTemplatesByStatus(status);
        });

        // Element search
        $('.element-search').on('input', function() {
            const query = $(this).val();
            filterElements(query);
        });

        // Bulk actions
        $('.bulk-actions').on('change', function() {
            const action = $(this).val();
            if (action) {
                executeBulkAction(action);
                $(this).val('');
            }
        });

        // Email builder initialization
        initializeEmailBuilder();
    }

    function openEmailBuilder(template) {
        currentTemplate = template;
        $('#templates-overview').hide();
        $('#email-builder').show();
        $('#template-select').val(template);
        
        // Update header title
        $('.builder-title h2').text(`Customize ${getTemplateName(template)}`);
        
        // Load template content
        loadTemplate(template);
        
        // Initialize drag and drop if not already done
        if (!dragDropInitialized) {
            initializeDragAndDrop();
            dragDropInitialized = true;
        }
    }

    function getTemplateName(template) {
        const names = {
            'new-order': 'Order Confirmation',
            'processing-order': 'Order Processing', 
            'completed-order': 'Order Completed',
            'invoice': 'Invoice Email',
            'customer-new-account': 'Customer Registration',
            'customer-reset-password': 'Password Reset'
        };
        return names[template] || 'Email Template';
    }

    function initializeEmailBuilder() {
        // Close properties panel
        $('.close-properties').on('click', function() {
            hidePropertiesPanel();
        });

        // Group header toggles
        $('.group-header').on('click', function() {
            const content = $(this).next('.group-content');
            const toggle = $(this).find('.group-toggle');
            
            if (content.is(':visible')) {
                content.slideUp();
                toggle.text('▼');
            } else {
                content.slideDown();
                toggle.text('▲');
            }
        });

        // Canvas click to deselect
        $('.drop-zone').on('click', function(e) {
            if (e.target === this) {
                $('.email-component').removeClass('selected');
                selectedComponent = null;
                hidePropertiesPanel();
            }
        });

        // Test email functionality
        $('.send-test-email').on('click', function() {
            sendTestEmail();
        });
    }

    function showPropertiesPanel(elementType) {
        $('#properties-panel').removeClass('hidden');
        
        // Load properties for this element type
        const form = getPropertyForm(elementType);
        $('.properties-content').html(form);
        
        // Bind apply button
        $('.apply-properties').on('click', function() {
            applyElementProperties(elementType);
        });
    }

    function hidePropertiesPanel() {
        $('#properties-panel').addClass('hidden');
        $('.email-element').removeClass('selected');
        selectedElement = null;
    }

    function applyElementProperties(elementType) {
        if (!selectedElement) return;
        
        const elementContent = selectedElement.find('.element-content');
        
        switch(elementType) {
            case 'text':
                const textContent = $('#prop-text-content').val();
                const fontSize = $('#prop-font-size').val();
                const textColor = $('#prop-text-color').val();
                
                elementContent.html(`
                    <div style="padding: 20px;">
                        <p style="margin: 0; line-height: 1.6; font-size: ${fontSize}; color: ${textColor};">${textContent}</p>
                    </div>
                `);
                break;
            
            case 'heading':
                const headingText = $('#prop-heading-text').val();
                const headingLevel = $('#prop-heading-level').val();
                const headingColor = $('#prop-heading-color').val();
                
                elementContent.html(`
                    <div style="padding: 20px;">
                        <${headingLevel} style="margin: 0; color: ${headingColor};">${headingText}</${headingLevel}>
                    </div>
                `);
                break;
            
            case 'button':
                const buttonText = $('#prop-button-text').val();
                const buttonUrl = $('#prop-button-url').val();
                const buttonBg = $('#prop-button-bg').val();
                const buttonTextColor = $('#prop-button-text-color').val();
                
                elementContent.html(`
                    <div style="padding: 20px; text-align: center;">
                        <a href="${buttonUrl}" style="display: inline-block; padding: 12px 24px; background: ${buttonBg}; color: ${buttonTextColor}; text-decoration: none; border-radius: 6px; font-weight: 500;">${buttonText}</a>
                    </div>
                `);
                break;
        }
        
        showNotification('Properties updated successfully!', 'success');
    }

    // Utility functions
    function previewEmail() {
        const emailHTML = generateEmailHTML();
        const previewWindow = window.open('', '_blank', 'width=800,height=600');
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Email Preview - ${currentTemplate}</title>
                <style>
                    body { 
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
                        margin: 0; 
                        padding: 20px; 
                        background: #f6f7f9;
                    }
                    .email-preview { 
                        max-width: 600px; 
                        margin: 0 auto; 
                        background: white; 
                        border-radius: 8px; 
                        overflow: hidden;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    }
                </style>
            </head>
            <body>
                <div class="email-preview">
                    ${emailHTML}
                </div>
            </body>
            </html>
        `);
    }

    function sendTestEmail() {
        const email = prompt('Enter email address for test:');
        if (!email) return;
        
        const emailHTML = generateEmailHTML();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_send_test_email',
                email: email,
                template: currentTemplate,
                content: emailHTML,
                nonce: window.shopglutEmailCustomizer.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Test email sent successfully!', 'success');
                } else {
                    showNotification('Error sending test email: ' + response.data, 'error');
                }
            },
            error: function() {
                showNotification('Error sending test email', 'error');
            }
        });
    }

    function generateEmailHTML() {
        let html = '';
        $('#drop-zone .email-component').each(function() {
            html += $(this).find('.component-content').html();
        });
        return html || '<div style="padding: 40px; text-align: center; color: #6b7280;">No content added yet</div>';
    }

    function filterTemplates(query) {
        const rows = $('.shopglut-templates-table tbody tr');
        if (!query) {
            rows.show();
            return;
        }
        
        rows.each(function() {
            const text = $(this).find('.template-title').text().toLowerCase();
            const desc = $(this).find('.template-description').text().toLowerCase();
            const searchText = query.toLowerCase();
            
            if (text.includes(searchText) || desc.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function filterTemplatesByStatus(status) {
        const rows = $('.shopglut-templates-table tbody tr');
        if (!status) {
            rows.show();
            return;
        }
        
        rows.each(function() {
            const rowStatus = $(this).find('.status-badge').hasClass('status-' + status);
            if (rowStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function filterElements(query) {
        const elements = $('.component-item');
        if (!query) {
            elements.show();
            $('.component-group').show();
            return;
        }
        
        elements.each(function() {
            const name = $(this).find('.component-info h5').text().toLowerCase();
            const desc = $(this).find('.component-desc').text().toLowerCase();
            const searchText = query.toLowerCase();
            
            if (name.includes(searchText) || desc.includes(searchText)) {
                $(this).show();
                $(this).closest('.component-group').show();
            } else {
                $(this).hide();
            }
        });
    }

    function updateBulkActionsState() {
        const checkedBoxes = $('.template-checkbox:checked').length;
        $('.bulk-actions').prop('disabled', checkedBoxes === 0);
    }

    function executeBulkAction(action) {
        const checkedBoxes = $('.template-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        const templates = [];
        checkedBoxes.each(function() {
            templates.push($(this).val());
        });
        
        // Execute bulk action via AJAX
        console.log('Bulk action:', action, 'Templates:', templates);
        showNotification(`Bulk action "${action}" executed on ${templates.length} templates`, 'info');
    }

    function showLoading() {
        const overlay = '<div class="loading-overlay"><div class="loading-spinner"></div></div>';
        $('.canvas-container').css('position', 'relative').append(overlay);
    }

    function hideLoading() {
        $('.loading-overlay').remove();
    }

    function showNotification(message, type = 'info') {
        const notification = $(`
            <div class="notification notification-${type}" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                font-size: 14px;
                font-weight: 500;
                max-width: 400px;
                animation: slideIn 0.3s ease;
            ">
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Component templates
    const componentTemplates = {
        header: `<div class="component-content">
            <div style="text-align: center; padding: 20px; background: #f0f0f1;">
                <img src="https://via.placeholder.com/150x50/007cba/ffffff?text=LOGO" alt="Logo" style="max-width: 150px;">
            </div>
        </div>`,
        
        footer: `<div class="component-content">
            <div style="text-align: center; padding: 20px; background: #f8f9fa; border-top: 1px solid #ddd;">
                <p style="margin: 0; color: #666; font-size: 12px;">© 2024 Your Company Name. All rights reserved.</p>
            </div>
        </div>`,
        
        text: `<div class="component-content">
            <div style="padding: 15px;">
                <p style="margin: 0; line-height: 1.6;">This is a text block. Click to edit this content and customize your email message.</p>
            </div>
        </div>`,
        
        heading: `<div class="component-content">
            <div style="padding: 15px;">
                <h2 style="margin: 0; color: #333; font-size: 24px;">Your Heading Text</h2>
            </div>
        </div>`,
        
        image: `<div class="component-content">
            <div style="text-align: center; padding: 15px;">
                <img src="https://via.placeholder.com/300x150/f0f0f1/666?text=Image+Placeholder" alt="Image" style="max-width: 100%; height: auto;">
            </div>
        </div>`,
        
        button: `<div class="component-content">
            <div style="text-align: center; padding: 20px;">
                <a href="#" style="display: inline-block; padding: 12px 30px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Click Here</a>
            </div>
        </div>`,
        
        divider: `<div class="component-content">
            <div style="padding: 20px;">
                <hr style="border: none; border-top: 1px solid #ddd; margin: 0;">
            </div>
        </div>`,
        
        spacer: `<div class="component-content">
            <div style="height: 40px;"></div>
        </div>`,
        
        'order-details': `<div class="component-content">
            <div style="padding: 15px;">
                <h3 style="margin: 0 0 15px 0; color: #333;">Order Details</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Product</th>
                        <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Qty</th>
                        <th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Price</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">Sample Product</td>
                        <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">1</td>
                        <td style="padding: 10px; text-align: right; border: 1px solid #ddd;">$99.00</td>
                    </tr>
                </table>
            </div>
        </div>`,
        
        'customer-details': `<div class="component-content">
            <div style="padding: 15px;">
                <h3 style="margin: 0 0 15px 0; color: #333;">Customer Details</h3>
                <p style="margin: 5px 0;"><strong>Name:</strong> John Doe</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> john@example.com</p>
                <p style="margin: 5px 0;"><strong>Phone:</strong> (555) 123-4567</p>
            </div>
        </div>`
    };

    // Property forms for different components
    const propertyForms = {
        text: `
            <div class="property-group">
                <label>Text Content</label>
                <textarea class="prop-text-content" rows="4">This is a text block. Click to edit this content and customize your email message.</textarea>
            </div>
            <div class="property-group">
                <label>Text Color</label>
                <input type="color" class="prop-text-color" value="#333333">
            </div>
            <div class="property-group">
                <label>Font Size</label>
                <select class="prop-font-size">
                    <option value="12px">12px</option>
                    <option value="14px" selected>14px</option>
                    <option value="16px">16px</option>
                    <option value="18px">18px</option>
                    <option value="20px">20px</option>
                </select>
            </div>`,
        
        heading: `
            <div class="property-group">
                <label>Heading Text</label>
                <input type="text" class="prop-heading-text" value="Your Heading Text">
            </div>
            <div class="property-group">
                <label>Heading Level</label>
                <select class="prop-heading-level">
                    <option value="h1">H1</option>
                    <option value="h2" selected>H2</option>
                    <option value="h3">H3</option>
                    <option value="h4">H4</option>
                </select>
            </div>
            <div class="property-group">
                <label>Text Color</label>
                <input type="color" class="prop-heading-color" value="#333333">
            </div>`,
        
        button: `
            <div class="property-group">
                <label>Button Text</label>
                <input type="text" class="prop-button-text" value="Click Here">
            </div>
            <div class="property-group">
                <label>Button URL</label>
                <input type="url" class="prop-button-url" value="#" placeholder="https://example.com">
            </div>
            <div class="property-group">
                <label>Background Color</label>
                <input type="color" class="prop-button-bg" value="#007cba">
            </div>
            <div class="property-group">
                <label>Text Color</label>
                <input type="color" class="prop-button-color" value="#ffffff">
            </div>`
    };

    function getPropertyForm(componentType) {
        return propertyForms[componentType] || '<div class="no-selection"><p>No properties available for this component</p></div>';
    }

    // Initialize drag and drop
    function initializeDragAndDrop() {
        // Make components draggable
        $('.component-item').each(function() {
            this.draggable = true;
            $(this).on('dragstart', function(e) {
                e.originalEvent.dataTransfer.setData('text/plain', $(this).data('component'));
                $(this).addClass('dragging');
            });
            
            $(this).on('dragend', function(e) {
                $(this).removeClass('dragging');
            });
        });

        // Setup drop zone
        const dropZone = $('.drop-zone')[0];
        if (!dropZone) return;
        
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('drag-over');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            // Only remove drag-over if leaving the drop zone entirely
            if (!this.contains(e.relatedTarget)) {
                $(this).removeClass('drag-over');
            }
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('drag-over');
            
            const componentType = e.dataTransfer.getData('text/plain');
            if (componentType) {
                addComponent(componentType);
            }
        });
    }

    function addComponent(componentType) {
        componentCounter++;
        const componentId = 'component-' + componentCounter;
        const template = componentTemplates[componentType] || '<div class="component-content"><p>Component: ' + componentType + '</p></div>';
        
        const componentHtml = `
            <div class="email-component" data-component="${componentType}" data-id="${componentId}">
                <div class="component-controls">
                    <button class="component-control move-up" title="Move Up">↑</button>
                    <button class="component-control move-down" title="Move Down">↓</button>
                    <button class="component-control duplicate" title="Duplicate">⧉</button>
                    <button class="component-control delete" title="Delete">×</button>
                </div>
                ${template}
            </div>
        `;

        if ($('.drop-zone .email-component').length === 0) {
            $('.drop-zone-placeholder').hide();
        }
        
        $('.drop-zone').append(componentHtml);
        attachComponentEvents();
        showNotification(`${componentType} component added`, 'success');
    }

    function attachComponentEvents() {
        // Component selection
        $('.email-component').off('click').on('click', function(e) {
            e.stopPropagation();
            $('.email-component').removeClass('selected');
            $(this).addClass('selected');
            selectedComponent = $(this);
            showComponentProperties($(this).data('component'));
        });
        
        // Component controls
        $('.component-control').off('click').on('click', function(e) {
            e.stopPropagation();
            const action = $(this).attr('class').split(' ')[1];
            const component = $(this).closest('.email-component');
            
            switch(action) {
                case 'move-up':
                    const prev = component.prev('.email-component');
                    if (prev.length) {
                        component.insertBefore(prev);
                        showNotification('Component moved up', 'info');
                    }
                    break;
                case 'move-down':
                    const next = component.next('.email-component');
                    if (next.length) {
                        component.insertAfter(next);
                        showNotification('Component moved down', 'info');
                    }
                    break;
                case 'duplicate':
                    const cloned = component.clone();
                    componentCounter++;
                    cloned.attr('data-id', 'component-' + componentCounter);
                    component.after(cloned);
                    attachComponentEvents();
                    showNotification('Component duplicated', 'info');
                    break;
                case 'delete':
                    if (confirm('Are you sure you want to delete this component?')) {
                        component.remove();
                        if ($('.drop-zone .email-component').length === 0) {
                            $('.drop-zone-placeholder').show();
                        }
                        hidePropertiesPanel();
                        showNotification('Component deleted', 'info');
                    }
                    break;
            }
        });
    }

    function showComponentProperties(componentType) {
        $('.properties-panel').removeClass('hidden');
        const form = getPropertyForm(componentType);
        
        $('.properties-content').html(`
            <div class="component-properties">
                <h4>Edit ${componentType.charAt(0).toUpperCase() + componentType.slice(1)}</h4>
                ${form}
                <button class="button btn-primary apply-properties" style="margin-top: 15px;">Apply Changes</button>
            </div>
        `);
        
        // Apply properties button
        $('.apply-properties').on('click', function() {
            applyComponentProperties(componentType);
        });
        
        // Load current values from component
        loadComponentProperties(componentType);
    }

    function loadComponentProperties(componentType) {
        if (!selectedComponent) return;
        
        const componentContent = selectedComponent.find('.component-content');
        
        // Extract current values based on component type
        switch(componentType) {
            case 'text':
                const textContent = componentContent.find('p').text();
                const textColor = componentContent.find('p').css('color') || '#333333';
                const fontSize = componentContent.find('p').css('font-size') || '14px';
                
                $('.prop-text-content').val(textContent);
                $('.prop-text-color').val(rgbToHex(textColor));
                $('.prop-font-size').val(fontSize);
                break;
                
            case 'heading':
                const headingText = componentContent.find('h1, h2, h3, h4').text();
                const headingLevel = componentContent.find('h1, h2, h3, h4')[0]?.tagName.toLowerCase() || 'h2';
                const headingColor = componentContent.find('h1, h2, h3, h4').css('color') || '#333333';
                
                $('.prop-heading-text').val(headingText);
                $('.prop-heading-level').val(headingLevel);
                $('.prop-heading-color').val(rgbToHex(headingColor));
                break;
                
            case 'button':
                const buttonElement = componentContent.find('a');
                const buttonText = buttonElement.text();
                const buttonUrl = buttonElement.attr('href') || '#';
                const buttonBg = buttonElement.css('background-color') || '#007cba';
                const buttonColor = buttonElement.css('color') || '#ffffff';
                
                $('.prop-button-text').val(buttonText);
                $('.prop-button-url').val(buttonUrl);
                $('.prop-button-bg').val(rgbToHex(buttonBg));
                $('.prop-button-color').val(rgbToHex(buttonColor));
                break;
        }
    }

    function applyComponentProperties(componentType) {
        if (!selectedComponent) return;
        
        const componentContent = selectedComponent.find('.component-content');
        
        switch(componentType) {
            case 'text':
                const textContent = $('.prop-text-content').val();
                const textColor = $('.prop-text-color').val();
                const fontSize = $('.prop-font-size').val();
                
                componentContent.html(`
                    <div style="padding: 15px;">
                        <p style="margin: 0; line-height: 1.6; color: ${textColor}; font-size: ${fontSize};">${textContent}</p>
                    </div>
                `);
                break;
                
            case 'heading':
                const headingText = $('.prop-heading-text').val();
                const headingLevel = $('.prop-heading-level').val();
                const headingColor = $('.prop-heading-color').val();
                
                componentContent.html(`
                    <div style="padding: 15px;">
                        <${headingLevel} style="margin: 0; color: ${headingColor}; font-size: 24px;">${headingText}</${headingLevel}>
                    </div>
                `);
                break;
                
            case 'button':
                const buttonText = $('.prop-button-text').val();
                const buttonUrl = $('.prop-button-url').val();
                const buttonBg = $('.prop-button-bg').val();
                const buttonColor = $('.prop-button-color').val();
                
                componentContent.html(`
                    <div style="text-align: center; padding: 20px;">
                        <a href="${buttonUrl}" style="display: inline-block; padding: 12px 30px; background: ${buttonBg}; color: ${buttonColor}; text-decoration: none; border-radius: 4px; font-weight: bold;">${buttonText}</a>
                    </div>
                `);
                break;
        }
        
        showNotification('Properties applied successfully!', 'success');
    }

    // Component category switching
    function initializeCategoryTabs() {
        $('.component-category').on('click', function() {
            $('.component-category').removeClass('active');
            $(this).addClass('active');
            
            $('.component-group').removeClass('active');
            $('.' + $(this).data('category') + '-components').addClass('active');
        });
    }

    // Template management functions
    function saveTemplate() {
        const templateData = {
            template: currentTemplate,
            components: []
        };
        
        $('.drop-zone .email-component').each(function() {
            templateData.components.push({
                type: $(this).data('component'),
                id: $(this).data('id'),
                html: $(this).find('.component-content').html()
            });
        });
        
        showLoading();
        
        // AJAX call to save template
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_save_email_template',
                template_data: JSON.stringify(templateData),
                nonce: window.shopglutEmailCustomizer.nonce
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    showNotification('Template saved successfully!', 'success');
                } else {
                    showNotification('Error saving template: ' + response.data, 'error');
                }
            },
            error: function() {
                hideLoading();
                showNotification('Error saving template', 'error');
            }
        });
    }

    function loadTemplate(templateType) {
        // Clear current canvas
        $('.drop-zone').html('<div class="drop-zone-placeholder"><i class="fa-solid fa-plus"></i><p>Drag components here to build your email template</p></div>');
        
        showLoading();
        
        // Load saved template data via AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_load_email_template',
                template: templateType,
                nonce: window.shopglutEmailCustomizer.nonce
            },
            success: function(response) {
                hideLoading();
                if (response.success && response.data.components) {
                    $('.drop-zone-placeholder').hide();
                    response.data.components.forEach(function(component) {
                        const componentHtml = `
                            <div class="email-component" data-component="${component.type}" data-id="${component.id}">
                                <div class="component-controls">
                                    <button class="component-control move-up" title="Move Up">↑</button>
                                    <button class="component-control move-down" title="Move Down">↓</button>
                                    <button class="component-control duplicate" title="Duplicate">⧉</button>
                                    <button class="component-control delete" title="Delete">×</button>
                                </div>
                                <div class="component-content">${component.html}</div>
                            </div>
                        `;
                        $('.drop-zone').append(componentHtml);
                        componentCounter = Math.max(componentCounter, parseInt(component.id.replace('component-', '')));
                    });
                    attachComponentEvents();
                    showNotification('Template loaded successfully!', 'success');
                } else {
                    showNotification('Template loaded (empty)', 'info');
                }
            },
            error: function() {
                hideLoading();
                showNotification('Error loading template', 'error');
            }
        });
    }

    // Utility function to convert rgb to hex
    function rgbToHex(rgb) {
        if (!rgb || rgb.indexOf('rgb') !== 0) return rgb;
        
        const values = rgb.match(/\d+/g);
        if (!values || values.length < 3) return rgb;
        
        return '#' + values.map(function(val) {
            const hex = parseInt(val).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        }).join('');
    }

    // Global event handlers
    $(document).ready(function() {
        // Initialize the customizer
        initializeEmailCustomizer();
        initializeCategoryTabs();
        
        // Template selector
        $('#template-select').on('change', function() {
            currentTemplate = $(this).val();
            $('.builder-title h2').text(`Customize ${getTemplateName(currentTemplate)}`);
            loadTemplate(currentTemplate);
        });
        
        // Canvas actions
        $('.preview-btn').on('click', function() {
            previewEmail();
        });
        
        $('.save-template-btn').on('click', function() {
            saveTemplate();
        });
        
        // Back to overview
        $('.back-to-overview').on('click', function(e) {
            e.preventDefault();
            $('#email-builder').hide();
            $('#templates-overview').show();
        });
    });

})(jQuery);