<?php
namespace Shopglut\BusinessSolutions\EmailCustomizer;

if ( ! defined( 'ABSPATH' ) ) exit;

// Import the separated components
use Shopglut\BusinessSolutions\EmailCustomizer\Views\HeaderView;
use Shopglut\BusinessSolutions\EmailCustomizer\Views\TemplatesTableView;
use Shopglut\BusinessSolutions\EmailCustomizer\Views\EmailBuilderView;
use Shopglut\BusinessSolutions\EmailCustomizer\Controllers\AjaxController;
use Shopglut\BusinessSolutions\EmailCustomizer\Models\TemplateModel;

class EmailCustomizer {

    private $ajaxController;

    public function __construct() {
        // Initialize AJAX controller
        $this->ajaxController = new AjaxController();
        
        // Initialize email customizer hooks and actions
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueAssets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
    }

    public function init() {
        // Initialize email customizer functionality
    }

    public function enqueueAssets() {
        // Enqueue CSS and JS assets
        wp_enqueue_style('shopglut-email-customizer', SHOPGLUT_URL . 'src/BusinessSolutions/EmailCustomizer/Assets/email-customizer.css', array(), '1.0.0');
        wp_enqueue_script('shopglut-email-builder', SHOPGLUT_URL . 'src/BusinessSolutions/EmailCustomizer/Assets/email-builder.js', array('jquery'), '1.0.0', true);
        
        // Enqueue local FontAwesome for icons (instead of external CDN)
        // Note: Consider including FontAwesome locally or using WordPress Dashicons instead
        // wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
        
        // Use WordPress Dashicons instead of external FontAwesome
        wp_enqueue_style('dashicons');
        
        // Localize script for AJAX
        wp_localize_script('shopglut-email-builder', 'shopglutEmailCustomizer', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_email_customizer'),
        ));
    }

    public function renderEmailCustomizerPage() {
        // Check if email_customizer module is disabled
        $module_manager = \Shopglut\ModuleManager::get_instance();
        if ($module_manager->should_show_disabled_message('email_customizer')) {
            $module_manager->render_disabled_module_message('email_customizer');
            return;
        }
        
        HeaderView::render();
        TemplatesTableView::render();
        EmailBuilderView::render();
        $this->renderPageScript();
    }

    private function renderPageScript() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Handle customize template buttons
            $(document).on('click', '.customize-template', function() {
                const template = $(this).data('template');
                $('#templates-overview').hide();
                $('#email-builder').show();
                $('#template-selector').val(template);
                
                // Initialize builder if not already done
                if (typeof window.shopglutEmailBuilder === 'undefined') {
                    window.shopglutEmailBuilder = new ShopglutEmailBuilder();
                    window.shopglutEmailBuilder.init();
                } else {
                    window.shopglutEmailBuilder.loadTemplate(template);
                }
            });

            // Handle preview template buttons
            $(document).on('click', '.preview-template', function() {
                const template = $(this).data('template');
                // Load and preview template
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'shopglut_load_email_template',
                        template: template,
                        nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_email_customizer')); ?>'
                    },
                    success: function(response) {
                        if (response.success && response.data.template) {
                            const templateHtml = response.data.template.components
                                .map(comp => comp.html)
                                .join('');
                            
                            const previewWindow = window.open('', 'preview', 'width=800,height=600,scrollbars=yes');
                            previewWindow.document.write(`
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <title>Email Template Preview</title>
                                    <style>
                                        body { font-family: Arial, sans-serif; margin: 20px; }
                                        .email-preview { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; }
                                    </style>
                                </head>
                                <body>
                                    <div class="email-preview">
                                        ${templateHtml}
                                    </div>
                                </body>
                                </html>
                            `);
                        } else {
                            alert('Template not found or not yet customized');
                        }
                    },
                    error: function() {
                        alert('Failed to load template preview');
                    }
                });
            });

            // Handle duplicate template buttons
            $(document).on('click', '.duplicate-template', function() {
                const template = $(this).data('template');
                const newName = prompt('Enter name for duplicated template:', template + '_copy');
                
                if (newName && newName !== template) {
                    // Load original template and save as new name
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'shopglut_load_email_template',
                            template: template,
                            nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_email_customizer')); ?>'
                        },
                        success: function(response) {
                            if (response.success && response.data.template) {
                                const templateData = response.data.template;
                                templateData.template = newName;
                                
                                // Save as new template
                                $.ajax({
                                    url: ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: 'shopglut_save_email_template',
                                        template_data: JSON.stringify(templateData),
                                        nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_email_customizer')); ?>'
                                    },
                                    success: function(saveResponse) {
                                        if (saveResponse.success) {
                                            alert('Template duplicated successfully!');
                                            location.reload(); // Refresh to show new template
                                        } else {
                                            alert('Failed to duplicate template');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });

            // Handle template search
            $('.templates-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.shopglut-templates-table tbody tr').each(function() {
                    const templateName = $(this).find('.template-title').text().toLowerCase();
                    const templateDesc = $(this).find('.template-description').text().toLowerCase();
                    
                    if (templateName.includes(searchTerm) || templateDesc.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Handle status filter
            $('.templates-status-filter').on('change', function() {
                const filterStatus = $(this).val();
                
                $('.shopglut-templates-table tbody tr').each(function() {
                    const status = $(this).find('.status-badge').hasClass('status-active') ? 'active' : 'inactive';
                    
                    if (filterStatus === '' || status === filterStatus) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Handle select all checkbox
            $('#select-all-templates').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.template-checkbox').prop('checked', isChecked);
                $('.bulk-actions').prop('disabled', !isChecked);
            });

            // Handle individual template checkboxes
            $(document).on('change', '.template-checkbox', function() {
                const checkedCount = $('.template-checkbox:checked').length;
                $('.bulk-actions').prop('disabled', checkedCount === 0);
                
                const totalCount = $('.template-checkbox').length;
                $('#select-all-templates').prop('checked', checkedCount === totalCount);
            });
        });
        </script>
        <?php
    }

    public static function get_instance() {
        static $instance;
        if ( is_null( $instance ) ) {
            $instance = new self();
        }
        return $instance;
    }
}