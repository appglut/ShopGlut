<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_emailTemplateManager' ) ) {
    class AGSHOPGLUT_emailTemplateManager extends AGSHOPGLUTP {

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
            parent::__construct( $field, $value, $unique, $where, $parent );
        }

        public function render() {
            echo wp_kses_post($this->field_before());

            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            ?>

            <div class="agshopglut-email-template-manager">
                <?php if ( $is_pro_active ) : ?>
                    <div class="template-manager-content">
                        <div class="template-actions">
                            <h4><?php esc_html_e( 'Email Template Management', 'shopglut' ); ?></h4>
                            <div class="action-buttons">
                                <button type="button" class="button button-primary create-new-template">
                                    <?php esc_html_e( 'Create New Template', 'shopglut' ); ?>
                                </button>
                                <button type="button" class="button import-template">
                                    <?php esc_html_e( 'Import Template', 'shopglut' ); ?>
                                </button>
                                <button type="button" class="button export-templates">
                                    <?php esc_html_e( 'Export All Templates', 'shopglut' ); ?>
                                </button>
                            </div>
                        </div>
                        
                        <div class="templates-grid">
                            <?php echo wp_kses_post( $this->render_template_cards() ); ?>
                        </div>
                        
                        <div class="template-variables-reference">
                            <h4><?php esc_html_e( 'Available Template Variables', 'shopglut' ); ?></h4>
                            <div class="variables-grid">
                                <div class="variable-group">
                                    <h5><?php esc_html_e( 'User Variables', 'shopglut' ); ?></h5>
                                    <ul>
                                        <li><code>{{user_name}}</code> - <?php esc_html_e( 'User display name', 'shopglut' ); ?></li>
                                        <li><code>{{user_email}}</code> - <?php esc_html_e( 'User email address', 'shopglut' ); ?></li>
                                        <li><code>{{user_first_name}}</code> - <?php esc_html_e( 'User first name', 'shopglut' ); ?></li>
                                        <li><code>{{user_last_name}}</code> - <?php esc_html_e( 'User last name', 'shopglut' ); ?></li>
                                    </ul>
                                </div>
                                <div class="variable-group">
                                    <h5><?php esc_html_e( 'Site Variables', 'shopglut' ); ?></h5>
                                    <ul>
                                        <li><code>{{site_name}}</code> - <?php esc_html_e( 'Website name', 'shopglut' ); ?></li>
                                        <li><code>{{site_url}}</code> - <?php esc_html_e( 'Website URL', 'shopglut' ); ?></li>
                                        <li><code>{{current_date}}</code> - <?php esc_html_e( 'Current date', 'shopglut' ); ?></li>
                                    </ul>
                                </div>
                                <div class="variable-group">
                                    <h5><?php esc_html_e( 'Product Variables', 'shopglut' ); ?></h5>
                                    <ul>
                                        <li><code>{{product_name}}</code> - <?php esc_html_e( 'Product name', 'shopglut' ); ?></li>
                                        <li><code>{{product_price}}</code> - <?php esc_html_e( 'Product price', 'shopglut' ); ?></li>
                                        <li><code>{{old_price}}</code> - <?php esc_html_e( 'Original price (for price drops)', 'shopglut' ); ?></li>
                                        <li><code>{{new_price}}</code> - <?php esc_html_e( 'New price (for price drops)', 'shopglut' ); ?></li>
                                        <li><code>{{product_url}}</code> - <?php esc_html_e( 'Product page URL', 'shopglut' ); ?></li>
                                    </ul>
                                </div>
                                <div class="variable-group">
                                    <h5><?php esc_html_e( 'Wishlist Variables', 'shopglut' ); ?></h5>
                                    <ul>
                                        <li><code>{{wishlist_count}}</code> - <?php esc_html_e( 'Number of items in wishlist', 'shopglut' ); ?></li>
                                        <li><code>{{wishlist_url}}</code> - <?php esc_html_e( 'Wishlist page URL', 'shopglut' ); ?></li>
                                    </ul>
                                </div>
                                <div class="variable-group">
                                    <h5><?php esc_html_e( 'System Variables', 'shopglut' ); ?></h5>
                                    <ul>
                                        <li><code>{{unsubscribe_url}}</code> - <?php esc_html_e( 'Unsubscribe link', 'shopglut' ); ?></li>
                                        <li><code>{{tracking_pixel}}</code> - <?php esc_html_e( 'Email open tracking pixel', 'shopglut' ); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Template Editor Modal -->
                    <div id="template-editor-modal" class="template-modal" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="modal-title"><?php esc_html_e( 'Edit Template', 'shopglut' ); ?></h3>
                                <button type="button" class="close-modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="template-form">
                                    <div class="form-row">
                                        <label for="template-name"><?php esc_html_e( 'Template Name:', 'shopglut' ); ?></label>
                                        <input type="text" id="template-name" style="width: 300px;" />
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="template-type"><?php esc_html_e( 'Template Type:', 'shopglut' ); ?></label>
                                        <select id="template-type">
                                            <option value="price-drop"><?php esc_html_e( 'Price Drop Alert', 'shopglut' ); ?></option>
                                            <option value="back-in-stock"><?php esc_html_e( 'Back in Stock', 'shopglut' ); ?></option>
                                            <option value="wishlist-reminder"><?php esc_html_e( 'Wishlist Reminder', 'shopglut' ); ?></option>
                                            <option value="promotional"><?php esc_html_e( 'Promotional', 'shopglut' ); ?></option>
                                            <option value="social-update"><?php esc_html_e( 'Social Update', 'shopglut' ); ?></option>
                                            <option value="custom"><?php esc_html_e( 'Custom', 'shopglut' ); ?></option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="template-subject"><?php esc_html_e( 'Default Subject:', 'shopglut' ); ?></label>
                                        <input type="text" id="template-subject" style="width: 500px;" />
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="template-content"><?php esc_html_e( 'Template Content:', 'shopglut' ); ?></label>
                                        <div class="editor-toolbar">
                                            <button type="button" class="editor-btn" data-tag="strong"><?php esc_html_e( 'Bold', 'shopglut' ); ?></button>
                                            <button type="button" class="editor-btn" data-tag="em"><?php esc_html_e( 'Italic', 'shopglut' ); ?></button>
                                            <button type="button" class="editor-btn" data-action="link"><?php esc_html_e( 'Link', 'shopglut' ); ?></button>
                                            <button type="button" class="editor-btn" data-action="variable"><?php esc_html_e( 'Insert Variable', 'shopglut' ); ?></button>
                                        </div>
                                        <textarea id="template-content" rows="15" style="width: 100%; font-family: monospace;"></textarea>
                                        <small class="description"><?php esc_html_e( 'Use HTML and template variables. Preview to see how it will look.', 'shopglut' ); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button preview-template"><?php esc_html_e( 'Preview', 'shopglut' ); ?></button>
                                <button type="button" class="button button-primary save-template"><?php esc_html_e( 'Save Template', 'shopglut' ); ?></button>
                                <button type="button" class="button cancel-edit"><?php esc_html_e( 'Cancel', 'shopglut' ); ?></button>
                            </div>
                        </div>
                    </div>
                    
                <?php else : ?>
                    <div class="pro-notice">
                        <p><?php esc_html_e( 'Email template management is available in the Pro version.', 'shopglut' ); ?></p>
                        <p><?php esc_html_e( 'You can still edit templates in the individual template sections below.', 'shopglut' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <style>
                .agshopglut-email-template-manager {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    padding: 20px;
                }
                .template-actions {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                }
                .template-actions h4 {
                    margin: 0;
                    color: #0073aa;
                }
                .action-buttons {
                    display: flex;
                    gap: 10px;
                }
                .templates-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 20px;
                    margin-bottom: 30px;
                }
                .template-card {
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    padding: 15px;
                    background: #f9f9f9;
                    transition: box-shadow 0.2s;
                }
                .template-card:hover {
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .template-card h5 {
                    margin: 0 0 10px 0;
                    color: #0073aa;
                }
                .template-type {
                    display: inline-block;
                    background: #0073aa;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 3px;
                    font-size: 11px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .template-preview {
                    font-size: 12px;
                    color: #666;
                    line-height: 1.4;
                    margin-bottom: 15px;
                    max-height: 60px;
                    overflow: hidden;
                }
                .template-actions-row {
                    display: flex;
                    gap: 5px;
                }
                .template-actions-row .button {
                    font-size: 11px;
                    padding: 4px 8px;
                }
                .variables-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                    margin-top: 15px;
                }
                .variable-group h5 {
                    margin: 0 0 10px 0;
                    color: #0073aa;
                }
                .variable-group ul {
                    margin: 0;
                    padding-left: 20px;
                }
                .variable-group li {
                    margin-bottom: 5px;
                    font-size: 13px;
                }
                .variable-group code {
                    background: #f1f1f1;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-family: monospace;
                }
                .template-variables-reference {
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                    margin-top: 20px;
                }
                .template-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.8);
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .modal-content {
                    background: white;
                    border-radius: 8px;
                    max-width: 900px;
                    max-height: 90vh;
                    overflow-y: auto;
                    width: 90%;
                }
                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px;
                    border-bottom: 1px solid #ddd;
                }
                .modal-header h3 {
                    margin: 0;
                }
                .close-modal {
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #666;
                }
                .modal-body {
                    padding: 20px;
                }
                .modal-footer {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    padding: 20px;
                    border-top: 1px solid #ddd;
                    background: #f9f9f9;
                }
                .template-form .form-row {
                    margin-bottom: 15px;
                }
                .template-form label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .editor-toolbar {
                    margin-bottom: 10px;
                }
                .editor-btn {
                    padding: 5px 10px;
                    margin-right: 5px;
                    border: 1px solid #ddd;
                    background: #f9f9f9;
                    cursor: pointer;
                    border-radius: 3px;
                }
                .editor-btn:hover {
                    background: #e9e9e9;
                }
                .pro-notice {
                    text-align: center;
                    padding: 40px;
                    color: #666;
                }
            </style>

            <script>
            jQuery(document).ready(function($) {
                var currentTemplateId = null;
                
                // Create new template
                $('.create-new-template').on('click', function() {
                    currentTemplateId = null;
                    $('#modal-title').text('<?php echo esc_html__( "Create New Template", "shopglut" ); ?>');
                    $('#template-name, #template-subject, #template-content').val('');
                    $('#template-type').val('custom');
                    $('#template-editor-modal').show();
                });
                
                // Edit template
                $(document).on('click', '.edit-template', function() {
                    currentTemplateId = $(this).data('template-id');
                    var card = $(this).closest('.template-card');
                    
                    $('#modal-title').text('<?php echo esc_html__( "Edit Template", "shopglut" ); ?>');
                    $('#template-name').val(card.find('h5').text());
                    $('#template-type').val($(this).data('template-type') || 'custom');
                    
                    // Load template data via AJAX
                    $.post(ajaxurl, {
                        action: 'shopglut_get_template',
                        template_id: currentTemplateId,
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                    }, function(response) {
                        if (response.success) {
                            $('#template-subject').val(response.data.subject || '');
                            $('#template-content').val(response.data.content || '');
                        }
                    });
                    
                    $('#template-editor-modal').show();
                });
                
                // Close modal
                $('.close-modal, .cancel-edit').on('click', function() {
                    $('#template-editor-modal').hide();
                });
                
                // Modal backdrop click
                $('#template-editor-modal').on('click', function(e) {
                    if (e.target === this) {
                        $(this).hide();
                    }
                });
                
                // Editor toolbar buttons
                $('.editor-btn').on('click', function() {
                    var textarea = document.getElementById('template-content');
                    var start = textarea.selectionStart;
                    var end = textarea.selectionEnd;
                    var text = textarea.value;
                    var selectedText = text.substring(start, end);
                    
                    if ($(this).data('tag')) {
                        var tag = $(this).data('tag');
                        var newText = '<' + tag + '>' + selectedText + '</' + tag + '>';
                        textarea.value = text.substring(0, start) + newText + text.substring(end);
                        textarea.selectionStart = textarea.selectionEnd = start + newText.length;
                    } else if ($(this).data('action') === 'link') {
                        var url = prompt('<?php echo esc_html__( "Enter URL:", "shopglut" ); ?>', 'http://');
                        if (url) {
                            var linkText = selectedText || 'Link Text';
                            var newText = '<a href="' + url + '">' + linkText + '</a>';
                            textarea.value = text.substring(0, start) + newText + text.substring(end);
                            textarea.selectionStart = textarea.selectionEnd = start + newText.length;
                        }
                    } else if ($(this).data('action') === 'variable') {
                        // Show variable picker
                        var variables = [
                            '{{user_name}}', '{{user_email}}', '{{user_first_name}}', '{{user_last_name}}',
                            '{{site_name}}', '{{site_url}}', '{{current_date}}',
                            '{{product_name}}', '{{product_price}}', '{{old_price}}', '{{new_price}}', '{{product_url}}',
                            '{{wishlist_count}}', '{{wishlist_url}}',
                            '{{unsubscribe_url}}', '{{tracking_pixel}}'
                        ];
                        
                        var variable = prompt('<?php echo esc_html__( "Choose variable:", "shopglut" ); ?>\n\n' + variables.join('\n'));
                        if (variable && variables.includes(variable)) {
                            textarea.value = text.substring(0, start) + variable + text.substring(end);
                            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
                        }
                    }
                    
                    textarea.focus();
                });
                
                // Preview template
                $('.preview-template').on('click', function() {
                    var templateContent = $('#template-content').val();
                    var templateSubject = $('#template-subject').val();
                    
                    // Create preview modal
                    var previewModal = $('<div class="template-preview-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10001; display: flex; align-items: center; justify-content: center;">' +
                        '<div style="background: white; padding: 20px; border-radius: 8px; max-width: 800px; max-height: 80vh; overflow-y: auto; position: relative;">' +
                        '<h3>Template Preview</h3>' +
                        '<div><strong>Subject:</strong> ' + templateSubject + '</div>' +
                        '<hr>' +
                        '<div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">' + templateContent + '</div>' +
                        '<button class="button close-preview" style="margin-top: 15px;">Close Preview</button>' +
                        '</div>' +
                        '</div>');
                    
                    $('body').append(previewModal);
                    
                    previewModal.find('.close-preview').on('click', function() {
                        previewModal.remove();
                    });
                    
                    previewModal.on('click', function(e) {
                        if (e.target === this) {
                            previewModal.remove();
                        }
                    });
                });
                
                // Save template
                $('.save-template').on('click', function() {
                    var templateData = {
                        action: 'shopglut_save_template',
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>',
                        template_id: currentTemplateId,
                        name: $('#template-name').val(),
                        type: $('#template-type').val(),
                        subject: $('#template-subject').val(),
                        content: $('#template-content').val()
                    };
                    
                    if (!templateData.name || !templateData.content) {
                        alert('<?php echo esc_html__( "Please fill in template name and content.", "shopglut" ); ?>');
                        return;
                    }
                    
                    var button = $(this);
                    button.prop('disabled', true).text('<?php echo esc_html__( "Saving...", "shopglut" ); ?>');
                    
                    $.post(ajaxurl, templateData, function(response) {
                        if (response.success) {
                            alert('<?php echo esc_html__( "Template saved successfully!", "shopglut" ); ?>');
                            $('#template-editor-modal').hide();
                            location.reload(); // Reload to show updated templates
                        } else {
                            alert('<?php echo esc_html__( "Error saving template:", "shopglut" ); ?> ' + (response.data || '<?php echo esc_html__( "Unknown error", "shopglut" ); ?>'));
                        }
                        button.prop('disabled', false).text('<?php echo esc_html__( "Save Template", "shopglut" ); ?>');
                    }).fail(function() {
                        alert('<?php echo esc_html__( "Network error occurred", "shopglut" ); ?>');
                        button.prop('disabled', false).text('<?php echo esc_html__( "Save Template", "shopglut" ); ?>');
                    });
                });
                
                // Delete template
                $(document).on('click', '.delete-template', function() {
                    if (!confirm('<?php echo esc_html__( "Are you sure you want to delete this template?", "shopglut" ); ?>')) {
                        return;
                    }
                    
                    var templateId = $(this).data('template-id');
                    var card = $(this).closest('.template-card');
                    
                    $.post(ajaxurl, {
                        action: 'shopglut_delete_template',
                        template_id: templateId,
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                    }, function(response) {
                        if (response.success) {
                            card.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('<?php echo esc_html__( "Error deleting template:", "shopglut" ); ?> ' + response.data);
                        }
                    });
                });
                
                // Export templates
                $('.export-templates').on('click', function() {
                    window.location.href = ajaxurl + '?action=shopglut_export_templates&nonce=<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>';
                });
                
                // Import template
                $('.import-template').on('click', function() {
                    var input = $('<input type="file" accept=".json" style="display: none;">');
                    
                    input.on('change', function() {
                        var file = this.files[0];
                        if (!file) return;
                        
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            try {
                                var templates = JSON.parse(e.target.result);
                                
                                $.post(ajaxurl, {
                                    action: 'shopglut_import_templates',
                                    templates: templates,
                                    nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                                }, function(response) {
                                    if (response.success) {
                                        alert('<?php echo esc_html__( "Templates imported successfully!", "shopglut" ); ?>');
                                        location.reload();
                                    } else {
                                        alert('<?php echo esc_html__( "Error importing templates:", "shopglut" ); ?> ' + response.data);
                                    }
                                });
                            } catch (e) {
                                alert('<?php echo esc_html__( "Invalid template file format", "shopglut" ); ?>');
                            }
                        };
                        reader.readAsText(file);
                    });
                    
                    input.trigger('click');
                });
            });
            </script>

            <?php
            echo wp_kses_post($this->field_after());
        }
        
        private function render_template_cards() {
            // Get saved templates from options or database
            $options = get_option( 'agshopglut_wishlist_options', [] );
            
            $default_templates = [
                'price-drop' => [
                    'name' => __( 'Price Drop Alert', 'shopglut' ),
                    'type' => 'price-drop',
                    'preview' => __( 'Hi {{user_name}}, Great news! The price has dropped on {{product_name}}...', 'shopglut' )
                ],
                'back-in-stock' => [
                    'name' => __( 'Back in Stock', 'shopglut' ),
                    'type' => 'back-in-stock',
                    'preview' => __( 'Hi {{user_name}}, Good news! {{product_name}} is back in stock...', 'shopglut' )
                ],
                'wishlist-reminder' => [
                    'name' => __( 'Wishlist Reminder', 'shopglut' ),
                    'type' => 'wishlist-reminder',
                    'preview' => __( 'Hi {{user_name}}, You have {{wishlist_count}} items waiting for you...', 'shopglut' )
                ],
                'promotional' => [
                    'name' => __( 'Promotional Email', 'shopglut' ),
                    'type' => 'promotional',
                    'preview' => __( 'Hi {{user_name}}, We have a special offer just for you...', 'shopglut' )
                ],
                'social-update' => [
                    'name' => __( 'Social Update', 'shopglut' ),
                    'type' => 'social-update',
                    'preview' => __( 'Hi {{user_name}}, There\'s an update on your social wishlist...', 'shopglut' )
                ]
            ];
            
            ob_start();
            
            foreach ( $default_templates as $template_id => $template ) :
            ?>
                <div class="template-card">
                    <span class="template-type"><?php echo esc_html( $template['type'] ); ?></span>
                    <h5><?php echo esc_html( $template['name'] ); ?></h5>
                    <div class="template-preview">
                        <?php echo esc_html( $template['preview'] ); ?>
                    </div>
                    <div class="template-actions-row">
                        <button type="button" class="button edit-template" data-template-id="<?php echo esc_attr( $template_id ); ?>" data-template-type="<?php echo esc_attr( $template['type'] ); ?>">
                            <?php echo esc_html__( 'Edit', 'shopglut' ); ?>
                        </button>
                        <button type="button" class="button duplicate-template" data-template-id="<?php echo esc_attr( $template_id ); ?>">
                            <?php echo esc_html__( 'Duplicate', 'shopglut' ); ?>
                        </button>
                        <button type="button" class="button preview-template-card" data-template-id="<?php echo esc_attr( $template_id ); ?>">
                            <?php echo esc_html__( 'Preview', 'shopglut' ); ?>
                        </button>
                    </div>
                </div>
            <?php
            endforeach;
            
            return ob_get_clean();
        }
    }
}