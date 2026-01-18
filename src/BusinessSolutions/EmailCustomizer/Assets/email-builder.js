/**
 * Shopglut Email Builder JavaScript
 * Handles drag-and-drop email template builder functionality
 */

class ShopglutEmailBuilder {
    constructor() {
        this.currentTemplate = 'new-order';
        this.elements = [];
        this.selectedElement = null;
        
        // Element templates
        this.elementTemplates = {
            // Basic Elements
            'logo': '<div class="element-content" style="text-align: center; padding: 20px;"><img src="https://via.placeholder.com/150x50/007cba/ffffff?text=LOGO" alt="Logo" style="max-width: 150px;"></div>',
            'text': '<div class="element-content" style="padding: 15px;"><p style="margin: 0; line-height: 1.6;">Your text content here...</p></div>',
            'heading': '<div class="element-content" style="padding: 15px;"><h2 style="margin: 0; color: #333; font-size: 24px;">Your heading here</h2></div>',
            'title': '<div class="element-content" style="padding: 15px;"><h1 style="margin: 0; color: #333; font-size: 28px;">Your title here</h1></div>',
            'image': '<div class="element-content" style="text-align: center; padding: 15px;"><img src="https://via.placeholder.com/400x200" alt="Image" style="max-width: 100%; height: auto;"></div>',
            'button': '<div class="element-content" style="text-align: center; padding: 15px;"><a href="#" style="background: #007cba; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Button Text</a></div>',
            'social_icon': '<div class="element-content" style="text-align: center; padding: 15px;"><a href="#" style="display: inline-block; margin: 0 5px;"><img src="https://via.placeholder.com/32x32/3b5998/ffffff?text=f" alt="Facebook" style="width: 32px; height: 32px;"></a><a href="#" style="display: inline-block; margin: 0 5px;"><img src="https://via.placeholder.com/32x32/1da1f2/ffffff?text=t" alt="Twitter" style="width: 32px; height: 32px;"></a></div>',
            'video': '<div class="element-content" style="text-align: center; padding: 15px;"><div style="background: #f0f0f0; padding: 60px 20px; border: 2px dashed #ddd;"><p style="margin: 0; color: #666;">Video Player</p><p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Click to configure video</p></div></div>',
            'image_list': '<div class="element-content" style="padding: 15px;"><div style="display: flex; gap: 10px; flex-wrap: wrap;"><img src="https://via.placeholder.com/100x100" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;"><img src="https://via.placeholder.com/100x100" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;"><img src="https://via.placeholder.com/100x100" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;"></div></div>',
            'image_box': '<div class="element-content" style="padding: 15px;"><div style="display: flex; align-items: center; gap: 15px;"><img src="https://via.placeholder.com/80x80" style="width: 80px; height: 80px; border-radius: 4px;"><div><h4 style="margin: 0 0 5px 0;">Image Box Title</h4><p style="margin: 0; font-size: 14px; color: #666;">Description text goes here</p></div></div></div>',
            'text_list': '<div class="element-content" style="padding: 15px;"><ul style="margin: 0; padding-left: 20px;"><li>List item one</li><li>List item two</li><li>List item three</li></ul></div>',
            'html': '<div class="element-content" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd;"><p style="margin: 0; font-family: monospace; font-size: 12px; color: #666;">Custom HTML Content</p></div>',
            'footer': '<div class="element-content" style="text-align: center; padding: 20px; background: #f8f9fa; border-top: 1px solid #ddd;"><p style="margin: 0; color: #666; font-size: 12px;">© 2024 Your Company Name. All rights reserved.</p></div>',
            'rating_stars': '<div class="element-content" style="text-align: center; padding: 15px;"><div style="color: #ffd700; font-size: 20px;">★★★★★</div><p style="margin: 5px 0 0 0; font-size: 14px;">5 out of 5 stars</p></div>',
            
            // General Elements
            'space': '<div class="element-content" style="height: 30px;"></div>',
            'divider': '<div class="element-content" style="padding: 15px;"><hr style="border: none; border-top: 1px solid #ddd; margin: 0;"></div>',
            'container': '<div class="element-content" style="padding: 20px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;"><p style="margin: 0; text-align: center; color: #666;">Container Element</p></div>',
            'column_layout_1': '<div class="element-content" style="padding: 15px;"><div style="width: 100%; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Single Column</div></div>',
            'column_layout_2': '<div class="element-content" style="padding: 15px;"><div style="display: flex; gap: 10px;"><div style="flex: 1; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Column 1</div><div style="flex: 1; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Column 2</div></div></div>',
            'column_layout_3': '<div class="element-content" style="padding: 15px;"><div style="display: flex; gap: 10px;"><div style="flex: 1; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Col 1</div><div style="flex: 1; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Col 2</div><div style="flex: 1; padding: 15px; border: 1px solid #ddd; text-align: center; background: #f9f9f9;">Col 3</div></div></div>',
            'column_layout_4': '<div class="element-content" style="padding: 15px;"><div style="display: flex; gap: 5px;"><div style="flex: 1; padding: 10px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; font-size: 12px;">1</div><div style="flex: 1; padding: 10px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; font-size: 12px;">2</div><div style="flex: 1; padding: 10px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; font-size: 12px;">3</div><div style="flex: 1; padding: 10px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; font-size: 12px;">4</div></div></div>',
            
            // WooCommerce Elements
            'shipping_address': '<div class="element-content" style="padding: 15px;"><h3 style="margin: 0 0 10px 0;">Shipping Address</h3><p style="margin: 0; line-height: 1.4;">{{shipping_first_name}} {{shipping_last_name}}<br>{{shipping_address_1}}<br>{{shipping_city}}, {{shipping_state}} {{shipping_postcode}}</p></div>',
            'billing_address': '<div class="element-content" style="padding: 15px;"><h3 style="margin: 0 0 10px 0;">Billing Address</h3><p style="margin: 0; line-height: 1.4;">{{billing_first_name}} {{billing_last_name}}<br>{{billing_address_1}}<br>{{billing_city}}, {{billing_state}} {{billing_postcode}}</p></div>',
            'billing_shipping_address': '<div class="element-content" style="padding: 15px;"><div style="display: flex; gap: 20px;"><div style="flex: 1;"><h3 style="margin: 0 0 10px 0;">Billing Address</h3><p style="margin: 0; line-height: 1.4;">{{billing_first_name}} {{billing_last_name}}<br>{{billing_address_1}}<br>{{billing_city}}, {{billing_state}} {{billing_postcode}}</p></div><div style="flex: 1;"><h3 style="margin: 0 0 10px 0;">Shipping Address</h3><p style="margin: 0; line-height: 1.4;">{{shipping_first_name}} {{shipping_last_name}}<br>{{shipping_address_1}}<br>{{shipping_city}}, {{shipping_state}} {{shipping_postcode}}</p></div></div></div>',
            'order_details': '<div class="element-content" style="padding: 15px;"><h3 style="margin: 0 0 15px 0; color: #333;">Order Details</h3><table style="width: 100%; border-collapse: collapse;"><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Product</th><th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Qty</th><th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Price</th></tr><tr><td style="padding: 10px; border: 1px solid #ddd;">{{product_name}}</td><td style="padding: 10px; text-align: center; border: 1px solid #ddd;">{{quantity}}</td><td style="padding: 10px; text-align: right; border: 1px solid #ddd;">{{price}}</td></tr></table></div>',
            'order_details_download': '<div class="element-content" style="padding: 15px;"><h3 style="margin: 0 0 15px 0; color: #333;">Downloads</h3><p style="margin: 0; color: #666;">Your downloadable products will appear here.</p></div>',
            'hook': '<div class="element-content" style="padding: 15px; border: 2px dashed #ddd; background: #f9f9f9; text-align: center;"><p style="margin: 0; color: #666;">Custom Hook Element</p><p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Configure hook name in properties</p></div>',
            
            // Block Elements (PRO features - placeholder)
            'cross_up_sells_products': '<div class="element-content" style="padding: 20px; text-align: center; border: 2px dashed #cda534; background: rgba(255, 217, 101, 0.1);"><h4 style="margin: 0 0 10px 0; color: #cda534;">Cross/Up-Sell Products</h4><p style="margin: 0; color: #666;">PRO Feature - Show related products</p></div>',
            'featured_products': '<div class="element-content" style="padding: 20px; text-align: center; border: 2px dashed #cda534; background: rgba(255, 217, 101, 0.1);"><h4 style="margin: 0 0 10px 0; color: #cda534;">Featured Products</h4><p style="margin: 0; color: #666;">PRO Feature - Display featured items</p></div>',
            'products_with_reviews': '<div class="element-content" style="padding: 20px; text-align: center; border: 2px dashed #cda534; background: rgba(255, 217, 101, 0.1);"><h4 style="margin: 0 0 10px 0; color: #cda534;">Products with Reviews</h4><p style="margin: 0; color: #666;">PRO Feature - Show reviewed products</p></div>',
            'simple_offer': '<div class="element-content" style="padding: 20px; text-align: center; border: 2px dashed #cda534; background: rgba(255, 217, 101, 0.1);"><h4 style="margin: 0 0 10px 0; color: #cda534;">Simple Offer</h4><p style="margin: 0; color: #666;">PRO Feature - Create special offers</p></div>',
            'single_banner': '<div class="element-content" style="padding: 20px; text-align: center; border: 2px dashed #cda534; background: rgba(255, 217, 101, 0.1);"><h4 style="margin: 0 0 10px 0; color: #cda534;">Single Banner</h4><p style="margin: 0; color: #666;">PRO Feature - Custom banner ads</p></div>'
        };
    }
    
    init() {
        this.bindEvents();
        this.loadTemplate(this.currentTemplate);
    }
    
    bindEvents() {
        const $ = jQuery;
        
        // Template switching
        $('#template-selector').on('change', (e) => {
            this.loadTemplate(e.target.value);
        });
        
        // Collapsible sections
        $('.shopglut-collapse-header').on('click', (e) => {
            const $header = $(e.currentTarget);
            const $item = $header.closest('.shopglut-collapse-item');
            const $content = $item.find('.shopglut-collapse-content');
            
            if ($content.hasClass('active')) {
                $content.removeClass('active');
                $item.addClass('collapsed');
            } else {
                $content.addClass('active');
                $item.removeClass('collapsed');
            }
        });
        
        // Element dragging - Updated to work with new element structure
        $('.shopglut-customizer-element').on('dragstart', (e) => {
            const elementType = $(e.currentTarget).data('shopglut-element-type');
            e.originalEvent.dataTransfer.setData('text/plain', elementType);
        });
        
        // Also support old element items for backward compatibility
        $('.element-item').on('dragstart', (e) => {
            e.originalEvent.dataTransfer.setData('text/plain', e.currentTarget.dataset.type);
        });
        
        // Canvas drop zone
        $('#email-body').on('dragover', (e) => {
            e.preventDefault();
            $(e.currentTarget).addClass('drag-over');
        }).on('dragleave', (e) => {
            $(e.currentTarget).removeClass('drag-over');
        }).on('drop', (e) => {
            e.preventDefault();
            $(e.currentTarget).removeClass('drag-over');
            const elementType = e.originalEvent.dataTransfer.getData('text/plain');
            this.addElement(elementType);
        });
        
        // Save template
        $('#save-template').on('click', () => {
            this.saveTemplate();
        });
        
        // Preview email
        $('#preview-email').on('click', () => {
            this.previewEmail();
        });
        
        // Send test email
        $('#send-test-email').on('click', () => {
            this.sendTestEmail();
        });
        
        // Back to templates
        $('#back-to-templates').on('click', () => {
            $('#email-builder').hide();
            $('#templates-overview').show();
        });
        
        // Element selection
        $(document).on('click', '.email-element', (e) => {
            e.stopPropagation();
            this.selectElement(e.currentTarget);
        });
        
        // Deselect element when clicking canvas
        $('#email-body').on('click', (e) => {
            if (e.target === e.currentTarget) {
                this.deselectElement();
            }
        });
        
        // Disable dragging for disabled elements
        $('.shopglut-customizer-sidebar-element__disabled').parent().parent().on('dragstart', (e) => {
            e.preventDefault();
            return false;
        });
    }
    
    addElement(type) {
        const elementId = 'element-' + Date.now();
        const template = this.elementTemplates[type] || '<div class="element-content">Unknown element</div>';
        
        const elementHtml = `
            <div class="email-element" data-type="${type}" data-id="${elementId}">
                <div class="element-controls">
                    <button class="element-control edit-element" title="Edit">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="element-control delete-element" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                ${template}
            </div>
        `;
        
        jQuery('#email-body').append(elementHtml);
        
        // Bind element controls
        this.bindElementControls(elementId);
    }
    
    bindElementControls(elementId) {
        const $ = jQuery;
        const $element = $(`.email-element[data-id="${elementId}"]`);
        
        $element.find('.delete-element').on('click', (e) => {
            e.stopPropagation();
            $element.remove();
        });
        
        $element.find('.edit-element').on('click', (e) => {
            e.stopPropagation();
            this.editElement(elementId);
        });
    }
    
    selectElement(element) {
        jQuery('.email-element').removeClass('selected');
        jQuery(element).addClass('selected');
        this.selectedElement = element;
        this.showProperties(element);
    }
    
    deselectElement() {
        jQuery('.email-element').removeClass('selected');
        this.selectedElement = null;
        this.hideProperties();
    }
    
    showProperties(element) {
        const $ = jQuery;
        const $element = $(element);
        const type = $element.data('type');
        
        // Show properties panel
        $('#properties-panel').show();
        
        // Load appropriate properties based on element type
        this.loadProperties(type, element);
    }
    
    hideProperties() {
        jQuery('#properties-panel').hide();
    }
    
    loadProperties(type, element) {
        const $ = jQuery;
        const $content = $('#properties-content');
        
        // Basic properties form based on element type
        let propertiesHtml = '';
        
        switch(type) {
            case 'text':
                propertiesHtml = this.getTextProperties(element);
                break;
            case 'heading':
                propertiesHtml = this.getHeadingProperties(element);
                break;
            case 'image':
                propertiesHtml = this.getImageProperties(element);
                break;
            case 'button':
                propertiesHtml = this.getButtonProperties(element);
                break;
            default:
                propertiesHtml = '<p>No properties available for this element.</p>';
        }
        
        $content.html(propertiesHtml);
    }
    
    getTextProperties(element) {
        const currentText = jQuery(element).find('p').text();
        return `
            <div class="property-group">
                <label>Text Content:</label>
                <textarea class="property-input" data-property="text">${currentText}</textarea>
            </div>
            <div class="property-group">
                <label>Text Color:</label>
                <input type="color" class="property-input" data-property="color" value="#333333">
            </div>
        `;
    }
    
    getHeadingProperties(element) {
        const currentText = jQuery(element).find('h2').text();
        return `
            <div class="property-group">
                <label>Heading Text:</label>
                <input type="text" class="property-input" data-property="text" value="${currentText}">
            </div>
            <div class="property-group">
                <label>Heading Color:</label>
                <input type="color" class="property-input" data-property="color" value="#333333">
            </div>
        `;
    }
    
    getImageProperties(element) {
        const currentSrc = jQuery(element).find('img').attr('src');
        return `
            <div class="property-group">
                <label>Image URL:</label>
                <input type="url" class="property-input" data-property="src" value="${currentSrc}">
            </div>
            <div class="property-group">
                <label>Alt Text:</label>
                <input type="text" class="property-input" data-property="alt" value="Image">
            </div>
        `;
    }
    
    getButtonProperties(element) {
        const currentText = jQuery(element).find('a').text();
        const currentHref = jQuery(element).find('a').attr('href');
        return `
            <div class="property-group">
                <label>Button Text:</label>
                <input type="text" class="property-input" data-property="text" value="${currentText}">
            </div>
            <div class="property-group">
                <label>Button Link:</label>
                <input type="url" class="property-input" data-property="href" value="${currentHref}">
            </div>
            <div class="property-group">
                <label>Button Color:</label>
                <input type="color" class="property-input" data-property="background" value="#007cba">
            </div>
        `;
    }
    
    editElement(elementId) {
        // Open inline editor or properties panel
        this.selectElement(jQuery(`.email-element[data-id="${elementId}"]`)[0]);
    }
    
    loadTemplate(templateName) {
        const $ = jQuery;
        this.currentTemplate = templateName;
        
        // AJAX call to load template
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_load_email_template',
                template: templateName,
                nonce: shopglutEmailCustomizer.nonce
            },
            success: (response) => {
                if (response.success && response.data.template) {
                    this.renderTemplate(response.data.template);
                } else {
                    // Load default template
                    this.renderDefaultTemplate(templateName);
                }
            },
            error: () => {
                console.error('Failed to load template');
                this.renderDefaultTemplate(templateName);
            }
        });
    }
    
    renderTemplate(templateData) {
        const $ = jQuery;
        $('#email-body').empty();
        
        if (templateData.components && templateData.components.length > 0) {
            templateData.components.forEach((component, index) => {
                this.renderComponent(component, index);
            });
        }
    }
    
    renderComponent(component, index) {
        const elementId = component.id || 'element-' + Date.now() + '-' + index;
        
        const elementHtml = `
            <div class="email-element" data-type="${component.type}" data-id="${elementId}">
                <div class="element-controls">
                    <button class="element-control edit-element" title="Edit">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="element-control delete-element" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                ${component.html}
            </div>
        `;
        
        jQuery('#email-body').append(elementHtml);
        this.bindElementControls(elementId);
    }
    
    renderDefaultTemplate(templateName) {
        // Render a basic default template
        this.addElement('header');
        this.addElement('heading');
        this.addElement('text');
        if (templateName === 'new-order') {
            this.addElement('order-details');
        }
        this.addElement('footer');
    }
    
    saveTemplate() {
        const $ = jQuery;
        const components = [];
        
        $('#email-body .email-element').each(function() {
            const $element = $(this);
            components.push({
                type: $element.data('type'),
                id: $element.data('id'),
                html: $element.find('.element-content').prop('outerHTML')
            });
        });
        
        const templateData = {
            template: this.currentTemplate,
            components: components
        };
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_save_email_template',
                template_data: JSON.stringify(templateData),
                nonce: shopglutEmailCustomizer.nonce
            },
            success: (response) => {
                if (response.success) {
                    // Show success message
                    this.showNotification('Template saved successfully!', 'success');
                } else {
                    this.showNotification('Failed to save template: ' + response.data, 'error');
                }
            },
            error: () => {
                this.showNotification('Failed to save template', 'error');
            }
        });
    }
    
    previewEmail() {
        const emailHtml = this.getEmailHTML();
        const previewWindow = window.open('', 'preview', 'width=800,height=600,scrollbars=yes');
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Email Preview</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .email-preview { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <div class="email-preview">
                    ${emailHtml}
                </div>
            </body>
            </html>
        `);
    }
    
    sendTestEmail() {
        const $ = jQuery;
        const testEmail = prompt('Enter email address for test:');
        
        if (!testEmail || !this.isValidEmail(testEmail)) {
            alert('Please enter a valid email address');
            return;
        }
        
        const emailHtml = this.getEmailHTML();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_send_test_email',
                test_email: testEmail,
                template_html: emailHtml,
                subject: 'Test Email from Shopglut Email Customizer',
                nonce: shopglutEmailCustomizer.nonce
            },
            success: (response) => {
                if (response.success) {
                    this.showNotification('Test email sent successfully!', 'success');
                } else {
                    this.showNotification('Failed to send test email: ' + response.data, 'error');
                }
            },
            error: () => {
                this.showNotification('Failed to send test email', 'error');
            }
        });
    }
    
    getEmailHTML() {
        return jQuery('#email-body').html();
    }
    
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    showNotification(message, type) {
        const $ = jQuery;
        const notification = $(`
            <div class="shopglut-notification ${type}">
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(() => {
                notification.remove();
            });
        }, 3000);
    }
}

// Initialize when DOM is ready
jQuery(document).ready(function() {
    // Make sure the builder is only initialized when needed
    if (jQuery('#email-builder').length > 0) {
        window.shopglutEmailBuilder = new ShopglutEmailBuilder();
    }
});