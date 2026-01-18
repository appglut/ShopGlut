<?php
namespace Shopglut\BusinessSolutions\PdfInvoices\Tabs;

if ( ! defined( 'ABSPATH' ) ) exit;

class InvoiceTemplates {
    
    public function render() {
        $current_template = get_option('agshopglut_pdf_invoices_invoice_template', 'default');
        ?>
        <div class="shopglut-invoice-templates-tab">
            <div class="templates-header">
                <h2><?php echo esc_html__('Invoice Templates', 'shopglut'); ?></h2>
                <p><?php echo esc_html__('Select, edit and customize your PDF invoice templates.', 'shopglut'); ?></p>
            </div>

            <div class="template-selection-section">
                <h3><?php echo esc_html__('Select Invoice Template', 'shopglut'); ?></h3>
                <div class="template-grid">
                    <?php
                    $templates = $this->getAvailableTemplates();
                    foreach ($templates as $template_id => $template_data):
                    ?>
                    <div class="template-card <?php echo $current_template === $template_id ? 'active' : ''; ?>" data-template="<?php echo esc_attr($template_id); ?>">
                        <div class="template-preview">
                            <img src="<?php echo esc_url($template_data['preview']); ?>" alt="<?php echo esc_attr($template_data['name']); ?>">
                        </div>
                        <div class="template-info">
                            <h4><?php echo esc_html($template_data['name']); ?></h4>
                            <p><?php echo esc_html($template_data['description']); ?></p>
                            <div class="template-actions">
                                <button class="button button-primary preview-template-btn" data-template="<?php echo esc_attr($template_id); ?>">
                                    <?php echo esc_html__('Preview', 'shopglut'); ?>
                                </button>
                                <button class="button edit-template" data-template="<?php echo esc_attr($template_id); ?>">
                                    <?php echo esc_html__('Edit', 'shopglut'); ?>
                                </button>
                                <button class="button reset-template" data-template="<?php echo esc_attr($template_id); ?>">
                                    <?php echo esc_html__('Reset', 'shopglut'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="template-editor-section" id="template-editor" style="display: none;">
                <h3><?php echo esc_html__('Edit Template', 'shopglut'); ?></h3>
                <div class="editor-container">
                    <div class="editor-tabs">
                        <button class="editor-tab active" data-tab="html"><?php echo esc_html__('HTML', 'shopglut'); ?></button>
                        <button class="editor-tab" data-tab="css"><?php echo esc_html__('CSS', 'shopglut'); ?></button>
                        <button class="editor-tab" data-tab="preview"><?php echo esc_html__('Preview', 'shopglut'); ?></button>
                    </div>
                    <div class="editor-content">
                        <div class="editor-panel active" data-panel="html">
                            <textarea id="template-html" rows="20" cols="100" placeholder="<?php echo esc_attr__('HTML template content...', 'shopglut'); ?>"></textarea>
                        </div>
                        <div class="editor-panel" data-panel="css">
                            <textarea id="template-css" rows="20" cols="100" placeholder="<?php echo esc_attr__('CSS styles...', 'shopglut'); ?>"></textarea>
                        </div>
                        <div class="editor-panel" data-panel="preview">
                            <div id="template-preview" class="template-preview-area">
                                <?php echo esc_html__('Preview will appear here...', 'shopglut'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="editor-actions">
                        <button class="button button-primary save-template" id="save-template">
                            <?php echo esc_html__('Save Template', 'shopglut'); ?>
                        </button>
                        <button class="button cancel-edit" id="cancel-edit">
                            <?php echo esc_html__('Cancel', 'shopglut'); ?>
                        </button>
                        <button class="button preview-template" id="preview-template">
                            <?php echo esc_html__('Update Preview', 'shopglut'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .shopglut-invoice-templates-tab {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .templates-header {
            margin-bottom: 30px;
        }

        .templates-header h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .templates-header p {
            color: #666;
            font-size: 16px;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .template-card {
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .template-card:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }

        .template-card.active {
            border-color: #0073aa;
            background: #f0f8ff;
        }

        .template-preview img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .template-info {
            padding: 15px;
        }

        .template-info h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .template-info p {
            color: #666;
            margin: 0 0 15px 0;
            font-size: 14px;
        }

        .template-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .template-actions .button {
            flex: 1;
            min-width: 70px;
            text-align: center;
        }

        .template-editor-section {
            margin-top: 30px;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 20px;
            background: #f9f9f9;
        }

        .editor-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .editor-tab {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .editor-tab.active {
            border-bottom-color: #0073aa;
            color: #0073aa;
            font-weight: 600;
        }

        .editor-panel {
            display: none;
        }

        .editor-panel.active {
            display: block;
        }

        .editor-panel textarea {
            width: 100%;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            resize: vertical;
        }

        .template-preview-area {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            background: white;
            min-height: 400px;
            overflow: auto;
        }

        .editor-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .template-selection-section h3,
        .template-editor-section h3 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            let currentEditingTemplate = null;

            // Preview template
            $('.preview-template-btn').on('click', function(e) {
                e.preventDefault();
                const templateId = $(this).data('template');
                previewTemplate(templateId);
            });

            // Edit template
            $('.edit-template').on('click', function(e) {
                e.preventDefault();
                const templateId = $(this).data('template');
                editTemplate(templateId);
            });

            // Reset template
            $('.reset-template').on('click', function(e) {
                e.preventDefault();
                const templateId = $(this).data('template');
                if (confirm('<?php echo esc_js(__('Are you sure you want to reset this template to default?', 'shopglut')); ?>')) {
                    resetTemplate(templateId);
                }
            });

            // Editor tabs
            $('.editor-tab').on('click', function() {
                const tab = $(this).data('tab');
                $('.editor-tab').removeClass('active');
                $('.editor-panel').removeClass('active');
                $(this).addClass('active');
                $(`.editor-panel[data-panel="${tab}"]`).addClass('active');
            });

            // Save template
            $('#save-template').on('click', function(e) {
                e.preventDefault();
                saveTemplate();
            });

            // Cancel edit
            $('#cancel-edit').on('click', function(e) {
                e.preventDefault();
                $('#template-editor').hide();
                currentEditingTemplate = null;
            });

            // Preview template
            $('#preview-template').on('click', function(e) {
                e.preventDefault();
                updatePreview();
            });

            function previewTemplate(templateId) {
                $.post(ajaxurl, {
                    action: 'shopglut_preview_invoice_template',
                    template: templateId,
                    nonce: '<?php echo esc_js( wp_create_nonce('shopglut_template_nonce') ); ?>'
                }, function(response) {
                    if (response.success) {
                        // Open preview in new window/tab
                        const previewWindow = window.open('', '_blank');
                        previewWindow.document.write(response.data.html);
                        previewWindow.document.close();
                    } else {
                        alert('<?php echo esc_js(__('Error generating preview.', 'shopglut')); ?>');
                    }
                });
            }

            function editTemplate(templateId) {
                currentEditingTemplate = templateId;
                $('#template-editor').show();
                
                // Load template content
                $.post(ajaxurl, {
                    action: 'shopglut_get_invoice_template',
                    template: templateId,
                    nonce: '<?php echo esc_js( wp_create_nonce('shopglut_template_nonce') ); ?>'
                }, function(response) {
                    if (response.success) {
                        $('#template-html').val(response.data.html);
                        $('#template-css').val(response.data.css);
                        updatePreview();
                    }
                });
            }

            function saveTemplate() {
                if (!currentEditingTemplate) return;
                
                const html = $('#template-html').val();
                const css = $('#template-css').val();
                
                $.post(ajaxurl, {
                    action: 'shopglut_save_invoice_template',
                    template: currentEditingTemplate,
                    html: html,
                    css: css,
                    nonce: '<?php echo esc_js( wp_create_nonce('shopglut_template_nonce') ); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('<?php echo esc_js(__('Template saved successfully!', 'shopglut')); ?>');
                        $('#template-editor').hide();
                        currentEditingTemplate = null;
                    } else {
                        alert('<?php echo esc_js(__('Error saving template.', 'shopglut')); ?>');
                    }
                });
            }

            function resetTemplate(templateId) {
                $.post(ajaxurl, {
                    action: 'shopglut_reset_invoice_template',
                    template: templateId,
                    nonce: '<?php echo esc_js( wp_create_nonce('shopglut_template_nonce') ); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('<?php echo esc_js(__('Template reset successfully!', 'shopglut')); ?>');
                        location.reload();
                    } else {
                        alert('<?php echo esc_js(__('Error resetting template.', 'shopglut')); ?>');
                    }
                });
            }

            function updatePreview() {
                const html = $('#template-html').val();
                const css = $('#template-css').val();
                const combinedContent = `<style>${css}</style>${html}`;
                $('#template-preview').html(combinedContent);
            }
        });
        </script>
        <?php
    }

    private function getAvailableTemplates() {
        return array(
            'default' => array(
                'name' => __('Default Template', 'shopglut'),
                'description' => __('Clean and professional default invoice template', 'shopglut'),
                'preview' => plugins_url('assets/images/templates/invoice-default.png', dirname(__FILE__, 2))
            ),
            'modern' => array(
                'name' => __('Modern Template', 'shopglut'),
                'description' => __('Modern design with bold typography and clean layout', 'shopglut'),
                'preview' => plugins_url('assets/images/templates/invoice-modern.png', dirname(__FILE__, 2))
            ),
            'classic' => array(
                'name' => __('Classic Template', 'shopglut'),
                'description' => __('Traditional business invoice with professional styling', 'shopglut'),
                'preview' => plugins_url('assets/images/templates/invoice-classic.png', dirname(__FILE__, 2))
            ),
            'minimal' => array(
                'name' => __('Minimal Template', 'shopglut'),
                'description' => __('Clean and minimal design focusing on essential information', 'shopglut'),
                'preview' => plugins_url('assets/images/templates/invoice-minimal.png', dirname(__FILE__, 2))
            )
        );
    }
}