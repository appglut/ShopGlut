<?php
namespace Shopglut\tools\megaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MegaMenu {

    private $option_group = 'shopglut_mega_menu_settings';

    public function __construct() {
        add_action('wp_ajax_shopglut_save_mega_menu_settings', array($this, 'ajax_save_settings'));
        add_action('wp_ajax_shopglut_customize_mega_menu_template', array($this, 'ajax_customize_template'));
        add_action('wp_ajax_shopglut_get_mega_menu_template_preview', array($this, 'ajax_get_template_preview'));
    }

    public function renderMegaMenuContent() {
        $this->renderSettingsPage();
    }

    private function renderSettingsPage() {
        $settings = $this->getSettings();
        $templates = $this->getPrebuiltTemplates();
        ?>
        <div class="wrap shopglut-admin-contents">
            <h2 style="text-align: center; font-weight: 700;"><?php echo esc_html__( 'Mega Menu', 'shopglut' ); ?></h2>
            <p class="subheading" style="text-align: center;"><?php echo esc_html__( 'Create beautiful mega menus for your WordPress site navigation', 'shopglut' ); ?></p>

            <div class="mega-menu-container">
                <form id="mega-menu-settings-form" method="post">
                    <div class="settings-section shopglut-mega-menu-settings">
                        <!-- Header -->
                        <div class="settings-header" onclick="toggleMegaMenuSettings()">
                            <div class="settings-header-content">
                                <div class="settings-title">
                                    <h3><?php echo esc_html__( 'Mega Menu Settings', 'shopglut' ); ?></h3>
                                    <p><?php echo esc_html__( 'Configure and customize your mega menu appearance and behavior', 'shopglut' ); ?></p>
                                </div>
                                <div class="settings-collapse-indicator">
                                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-body" id="mega-menu-settings-body" style="display: none;">
                            <!-- Main Mega Menu Toggle -->
                            <div class="setting-row override-toggle">
                                <div class="setting-col-full">
                                    <div class="setting-field">
                                        <label class="toggle-label">
                                            <input type="checkbox" id="enable_mega_menu" name="enable_mega_menu" value="1" <?php checked($settings['enable_mega_menu'], 1); ?>>
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">
                                                <strong><?php echo esc_html__( 'Enable Mega Menu', 'shopglut' ); ?></strong>
                                                <small><?php echo esc_html__( 'Activate mega menu functionality on your site', 'shopglut' ); ?></small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Template & Options Container -->
                            <div id="template-selection" class="template-options-container" style="<?php echo $settings['enable_mega_menu'] ? '' : 'display: none;'; ?>">

                                <!-- Template Selection -->
                                <div class="setting-row">
                                    <div class="setting-col-full">
                                        <label class="field-label"><?php echo esc_html__( 'Selected Template', 'shopglut' ); ?></label>
                                        <select id="selected-template" name="selected_template" class="minimal-select">
                                            <option value=""><?php echo esc_html__( 'Choose a template...', 'shopglut' ); ?></option>
                                            <?php foreach ($templates as $template_id => $template): ?>
                                                <option value="<?php echo esc_attr($template_id); ?>" <?php selected($settings['selected_template'], $template_id); ?>>
                                                    <?php echo esc_html($template['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Three Column Layout -->
                                <div class="setting-row three-cols">
                                    <!-- Menu Location -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Menu Location', 'shopglut' ); ?></label>
                                        <div class="radio-list">
                                            <label class="radio-item">
                                                <input type="radio" name="menu_location" value="primary" <?php checked($settings['menu_location'], 'primary'); ?>>
                                                <span><?php echo esc_html__( 'Primary Menu', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="menu_location" value="secondary" <?php checked($settings['menu_location'], 'secondary'); ?>>
                                                <span><?php echo esc_html__( 'Secondary Menu', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="menu_location" value="custom" <?php checked($settings['menu_location'], 'custom'); ?>>
                                                <span><?php echo esc_html__( 'Custom Menu', 'shopglut' ); ?></span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Trigger Method -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Trigger Method', 'shopglut' ); ?></label>
                                        <div class="radio-list">
                                            <label class="radio-item">
                                                <input type="radio" name="trigger_method" value="hover" <?php checked($settings['trigger_method'], 'hover'); ?>>
                                                <span><?php echo esc_html__( 'On Hover', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="trigger_method" value="click" <?php checked($settings['trigger_method'], 'click'); ?>>
                                                <span><?php echo esc_html__( 'On Click', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="trigger_method" value="both" <?php checked($settings['trigger_method'], 'both'); ?>>
                                                <span><?php echo esc_html__( 'Both', 'shopglut' ); ?></span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Animation -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Animation', 'shopglut' ); ?></label>
                                        <div class="radio-list">
                                            <label class="radio-item">
                                                <input type="radio" name="animation" value="fade" <?php checked($settings['animation'], 'fade'); ?>>
                                                <span><?php echo esc_html__( 'Fade', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="animation" value="slide" <?php checked($settings['animation'], 'slide'); ?>>
                                                <span><?php echo esc_html__( 'Slide', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="animation" value="none" <?php checked($settings['animation'], 'none'); ?>>
                                                <span><?php echo esc_html__( 'None', 'shopglut' ); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template Preview Section -->
                                <div class="setting-row">
                                    <div class="setting-col-full">
                                        <div class="template-preview-section" id="template-preview-section">
                                            <div class="template-preview-header">
                                                <h4><?php echo esc_html__( 'Template Preview', 'shopglut' ); ?></h4>
                                                <button type="button" class="button button-secondary" onclick="customizeSelectedTemplate()" id="customize-btn" disabled>
                                                    <?php echo esc_html__( 'Customize Template', 'shopglut' ); ?>
                                                </button>
                                            </div>
                                            <div class="template-preview-area" id="template-preview-area">
                                                <div class="no-template-selected">
                                                    <p><?php echo esc_html__( 'Select a template to see preview', 'shopglut' ); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Save Settings Section -->
                        <div class="save-settings-section">
                            <button type="submit" class="button button-primary">
                                <?php echo esc_html__( 'Save Settings', 'shopglut' ); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Template Customization Modal -->
            <div id="template-customize-modal" class="template-customize-modal" style="display: none;">
                <div class="modal-overlay" onclick="closeCustomizeModal()"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="customize-modal-title" style="margin: 0; font-size: 20px; font-weight: 600; color: #1d2327;">Customize Template</h2>
                        <button type="button" class="modal-close" onclick="closeCustomizeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666; line-height: 1; padding: 0; width: 30px; height: 30px;">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="customize-template-form">
                            <input type="hidden" id="customize-template-id" name="template_id">

                            <!-- Color Settings -->
                            <div class="customize-section">
                                <h3><?php echo esc_html__( 'Colors', 'shopglut' ); ?></h3>
                                <div class="setting-row three-cols">
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Primary Color', 'shopglut' ); ?></label>
                                        <input type="color" id="primary_color" name="primary_color" class="color-input">
                                    </div>
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Background Color', 'shopglut' ); ?></label>
                                        <input type="color" id="background_color" name="background_color" class="color-input">
                                    </div>
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Text Color', 'shopglut' ); ?></label>
                                        <input type="color" id="text_color" name="text_color" class="color-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Layout Settings -->
                            <div class="customize-section">
                                <h3><?php echo esc_html__( 'Layout', 'shopglut' ); ?></h3>
                                <div class="setting-row three-cols">
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Columns', 'shopglut' ); ?></label>
                                        <select id="columns" name="columns" class="minimal-input">
                                            <option value="2">2 Columns</option>
                                            <option value="3">3 Columns</option>
                                            <option value="4">4 Columns</option>
                                            <option value="5">5 Columns</option>
                                        </select>
                                    </div>
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Menu Width', 'shopglut' ); ?></label>
                                        <input type="number" id="menu_width" name="menu_width" class="minimal-input" placeholder="800" min="400" max="1200">
                                    </div>
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Border Radius', 'shopglut' ); ?></label>
                                        <input type="text" id="border_radius" name="border_radius" class="minimal-input" placeholder="8px">
                                    </div>
                                </div>
                            </div>

                            <!-- Content Settings -->
                            <div class="customize-section">
                                <h3><?php echo esc_html__( 'Content', 'shopglut' ); ?></h3>
                                <div class="setting-row">
                                    <div class="setting-col-full">
                                        <label class="toggle-label">
                                            <input type="checkbox" id="show_images" name="show_images" value="1">
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">
                                                <strong><?php echo esc_html__( 'Show Category Images', 'shopglut' ); ?></strong>
                                                <small><?php echo esc_html__( 'Display category thumbnail images in the menu', 'shopglut' ); ?></small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="setting-row">
                                    <div class="setting-col-full">
                                        <label class="toggle-label">
                                            <input type="checkbox" id="show_product_count" name="show_product_count" value="1">
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">
                                                <strong><?php echo esc_html__( 'Show Product Count', 'shopglut' ); ?></strong>
                                                <small><?php echo esc_html__( 'Display number of products in each category', 'shopglut' ); ?></small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Customization -->
                            <div class="customize-actions">
                                <button type="button" class="button" onclick="closeCustomizeModal()">
                                    <?php echo esc_html__( 'Cancel', 'shopglut' ); ?>
                                </button>
                                <button type="submit" class="button button-primary">
                                    <?php echo esc_html__( 'Save Customization', 'shopglut' ); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Modal -->
            <div id="template-preview-modal" class="template-preview-modal" style="display: none;">
                <div class="modal-overlay" onclick="closePreviewModal()"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="modal-template-title" style="margin: 0; font-size: 20px; font-weight: 600; color: #1d2327;">Template Preview</h2>
                        <button type="button" class="modal-close" onclick="closePreviewModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666; line-height: 1; padding: 0; width: 30px; height: 30px;">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="mega-menu-preview-content" class="preview-content">
                            <!-- Mega menu preview will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
        /* Mega Menu Settings Styles */
        .shopglut-mega-menu-settings {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .settings-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e5e5;
            cursor: pointer;
            transition: background-color 0.2s ease;
            user-select: none;
        }

        .settings-header:hover {
            background-color: #f8f9f9;
        }

        .settings-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .settings-title {
            flex: 1;
        }

        .settings-collapse-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
            color: #646970;
        }

        .settings-header:hover .settings-collapse-indicator {
            background-color: #f0f0f1;
            color: #135e96;
        }

        .settings-header.collapsed .settings-collapse-indicator .dashicons {
            transform: rotate(180deg);
        }

        .save-settings-section {
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e5e5e5;
            text-align: right;
        }

        .settings-header h3 {
            margin: 0 0 4px 0;
            font-size: 18px;
            font-weight: 600;
            color: #23282d;
        }

        .settings-header p {
            margin: 0;
            font-size: 13px;
            color: #646970;
        }

        .settings-body {
            padding: 24px;
        }

        /* Row and Column Layout */
        .setting-row {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f0f0f1;
        }

        .setting-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .setting-row.three-cols {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
        }

        .setting-col-full {
            width: 100%;
        }

        .setting-col {
            min-width: 0;
        }

        /* Toggle Switch */
        .toggle-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            font-weight: normal;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            background: #ccc;
            border-radius: 12px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .toggle-switch::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        input[type="checkbox"]:checked + .toggle-switch {
            background: #007cba;
        }

        input[type="checkbox"]:checked + .toggle-switch::before {
            transform: translateX(20px);
        }

        .toggle-text strong {
            display: block;
            font-weight: 600;
            color: #1d2327;
        }

        .toggle-text small {
            display: block;
            font-size: 12px;
            color: #646970;
            margin-top: 2px;
        }

        /* Form Elements */
        .field-label {
            display: block;
            font-weight: 600;
            color: #1d2327;
            margin-bottom: 8px;
        }

        .minimal-input, .minimal-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        .minimal-input:focus, .minimal-select:focus {
            border-color: #007cba;
            outline: none;
        }

        .color-input {
            width: 100%;
            height: 36px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px;
        }

        /* Radio Lists */
        .radio-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-item input[type="radio"] {
            margin: 0;
        }

        /* Template Preview */
        .template-preview-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .template-preview-header {
            background: #f8f9f9;
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .template-preview-header h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .template-preview-area {
            padding: 20px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .no-template-selected {
            text-align: center;
            color: #646970;
        }

        /* Modal Styles */
        .template-customize-modal,
        .template-preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100000;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 8px;
            max-width: 800px;
            max-height: 90vh;
            width: 90%;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9f9;
        }

        .modal-body {
            padding: 24px;
            max-height: calc(90vh - 80px);
            overflow-y: auto;
        }

        .customize-section {
            margin-bottom: 32px;
        }

        .customize-section h3 {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .customize-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .preview-content {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .setting-row.three-cols {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .customize-actions {
                flex-direction: column;
            }

            .customize-actions .button {
                width: 100%;
            }
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Toggle settings visibility
            window.toggleMegaMenuSettings = function() {
                var body = $('#mega-menu-settings-body');
                var header = $('.settings-header');
                var indicator = header.find('.dashicons');

                if (body.is(':visible')) {
                    body.slideUp();
                    header.addClass('collapsed');
                    indicator.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                } else {
                    body.slideDown();
                    header.removeClass('collapsed');
                    indicator.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                }
            };

            // Toggle template options based on main enable checkbox
            $('#enable_mega_menu').change(function() {
                var isVisible = $(this).is(':checked');
                $('#template-selection').toggle(isVisible);
            });

            // Handle template selection
            $('#selected-template').change(function() {
                var templateId = $(this).val();
                $('#customize-btn').prop('disabled', !templateId);

                if (templateId) {
                    loadTemplatePreview(templateId);
                    loadTemplateCustomization(templateId);
                } else {
                    showNoTemplateSelected();
                }
            });

            // Save settings
            $('#mega-menu-settings-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $(this).serialize() + '&action=shopglut_save_mega_menu_settings&nonce=<?php echo esc_attr(wp_create_nonce('shopglut_mega_menu_nonce')); ?>',
                    success: function(response) {
                        if (response.success) {
                            alert('<?php echo esc_js(__('Settings saved successfully!', 'shopglut')); ?>');
                        } else {
                            alert('<?php echo esc_js(__('Error saving settings.', 'shopglut')); ?>');
                        }
                    },
                    error: function() {
                        alert('<?php echo esc_js(__('Connection error. Please try again.', 'shopglut')); ?>');
                    }
                });
            });

            // Save template customization
            $('#customize-template-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $(this).serialize() + '&action=shopglut_customize_mega_menu_template&nonce=<?php echo esc_attr(wp_create_nonce('shopglut_mega_menu_nonce')); ?>',
                    success: function(response) {
                        if (response.success) {
                            alert('<?php echo esc_js(__('Template customized successfully!', 'shopglut')); ?>');
                            closeCustomizeModal();
                            // Update preview
                            var templateId = $('#selected-template').val();
                            if (templateId) {
                                loadTemplatePreview(templateId);
                            }
                        } else {
                            alert('<?php echo esc_js(__('Error customizing template.', 'shopglut')); ?>');
                        }
                    },
                    error: function() {
                        alert('<?php echo esc_js(__('Connection error. Please try again.', 'shopglut')); ?>');
                    }
                });
            });

            // Initialize state
            var isEnabled = $('#enable_mega_menu').is(':checked');
            $('#template-selection').toggle(isEnabled);
        });

        // Load template preview
        function loadTemplatePreview(templateId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_mega_menu_template_preview',
                    template_id: templateId,
                    nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_mega_menu_nonce')); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $('#template-preview-area').html(response.data.html);
                    }
                },
                error: function() {
                    $('#template-preview-area').html('<p><?php echo esc_js(__('Error loading preview', 'shopglut')); ?></p>');
                }
            });
        }

        // Load template customization settings
        function loadTemplateCustomization(templateId) {
            // This would load existing customization settings for the template
            // For now, we'll set default values
            $('#customize-template-id').val(templateId);
        }

        // Show no template selected message
        function showNoTemplateSelected() {
            $('#template-preview-area').html('<div class="no-template-selected"><p><?php echo esc_html__('Select a template to see preview', 'shopglut'); ?></p></div>');
        }

        // Customize template
        window.customizeSelectedTemplate = function() {
            var templateId = $('#selected-template').val();
            if (templateId) {
                loadTemplateCustomization(templateId);
                $('#template-customize-modal').show();
            }
        };

        // Close customize modal
        window.closeCustomizeModal = function() {
            $('#template-customize-modal').hide();
        };

        // Close preview modal
        window.closePreviewModal = function() {
            $('#template-preview-modal').hide();
        };

        // Open preview modal
        window.openPreviewModal = function(templateId) {
            $('#modal-template-title').text('Mega Menu Preview - ' + $('#selected-template option:selected').text());

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_mega_menu_template_preview',
                    template_id: templateId,
                    nonce: '<?php echo esc_attr(wp_create_nonce('shopglut_mega_menu_nonce')); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $('#mega-menu-preview-content').html(response.data.html);
                        $('#template-preview-modal').show();
                    }
                },
                error: function() {
                    $('#mega-menu-preview-content').html('<p><?php echo esc_js(__('Error loading preview', 'shopglut')); ?></p>');
                    $('#template-preview-modal').show();
                }
            });
        };
        </script>
        <?php
    }

    private function getPrebuiltTemplates() {
        // Free templates - Mega menu specific designs
        $free_templates = array(
            'horizontal_dropdown' => array(
                'name' => 'Horizontal Dropdown',
                'description' => 'Classic horizontal mega menu with multi-column dropdown layout. Perfect for comprehensive navigation with categories and subcategories.',
                'category' => 'Classic',
                'primary_color' => '#007cba',
                'background_color' => '#ffffff',
                'text_color' => '#333333',
                'layout_type' => 'horizontal',
                'default_columns' => 4,
                'is_pro' => false
            ),
            'vertical_sidebar' => array(
                'name' => 'Vertical Sidebar',
                'description' => 'Expandable vertical menu ideal for sidebar placement. Features smooth animations and nested category support.',
                'category' => 'Modern',
                'primary_color' => '#667eea',
                'background_color' => '#f8f9fa',
                'text_color' => '#2d3748',
                'layout_type' => 'vertical',
                'default_columns' => 1,
                'is_pro' => false
            ),
            'grid_menu' => array(
                'name' => 'Grid Layout',
                'description' => 'Modern grid-based mega menu with icon support. Great for showcasing categories with visual appeal and balanced layout.',
                'category' => 'Modern',
                'primary_color' => '#2271b1',
                'background_color' => '#ffffff',
                'text_color' => '#1d2327',
                'layout_type' => 'grid',
                'default_columns' => 3,
                'is_pro' => false
            )
        );

        /**
         * Filter to add Pro templates
         *
         * Pro add-on can hook into this filter to add additional templates
         *
         * @param array $free_templates Array of template configurations
         * @return array Modified array with Pro templates added
         */
        $all_templates = apply_filters('shopglut_mega_menu_templates', $free_templates);

        return $all_templates;
    }

    private function getSettings() {
        $defaults = array(
            'enable_mega_menu' => 0,
            'selected_template' => '',
            'menu_location' => 'primary',
            'trigger_method' => 'hover',
            'animation' => 'fade',
            'custom_settings' => array()
        );

        $settings = get_option($this->option_group, $defaults);
        return wp_parse_args($settings, $defaults);
    }

    public function ajax_save_settings() {
        check_ajax_referer('shopglut_mega_menu_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Security check failed');
        }

        $settings = array(
            'enable_mega_menu' => isset($_POST['enable_mega_menu']) ? 1 : 0,
            'selected_template' => isset($_POST['selected_template']) ? sanitize_text_field(wp_unslash($_POST['selected_template'])) : '',
            'menu_location' => isset($_POST['menu_location']) ? sanitize_text_field(wp_unslash($_POST['menu_location'])) : 'primary',
            'trigger_method' => isset($_POST['trigger_method']) ? sanitize_text_field(wp_unslash($_POST['trigger_method'])) : 'hover',
            'animation' => isset($_POST['animation']) ? sanitize_text_field(wp_unslash($_POST['animation'])) : 'fade'
        );

        update_option($this->option_group, $settings);

        wp_send_json_success(array('message' => 'Settings saved successfully'));
    }

    public function ajax_customize_template() {
        check_ajax_referer('shopglut_mega_menu_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Security check failed');
        }

        $template_id = isset($_POST['template_id']) ? sanitize_text_field(wp_unslash($_POST['template_id'])) : '';

        $custom_settings = array(
            // Colors
            'primary_color' => isset($_POST['primary_color']) ? sanitize_hex_color(wp_unslash($_POST['primary_color'])) : '',
            'background_color' => isset($_POST['background_color']) ? sanitize_hex_color(wp_unslash($_POST['background_color'])) : '',
            'text_color' => isset($_POST['text_color']) ? sanitize_hex_color(wp_unslash($_POST['text_color'])) : '#333333',

            // Layout
            'columns' => isset($_POST['columns']) ? sanitize_text_field(wp_unslash($_POST['columns'])) : '4',
            'menu_width' => isset($_POST['menu_width']) ? sanitize_text_field(wp_unslash($_POST['menu_width'])) : '800',
            'border_radius' => isset($_POST['border_radius']) ? sanitize_text_field(wp_unslash($_POST['border_radius'])) : '8px',

            // Content
            'show_images' => isset($_POST['show_images']) ? '1' : '0',
            'show_product_count' => isset($_POST['show_product_count']) ? '1' : '0'
        );

        // Save custom settings for template
        $settings = $this->getSettings();
        $settings['custom_settings'][$template_id] = $custom_settings;
        update_option($this->option_group, $settings);

        wp_send_json_success(array('message' => 'Template customized successfully'));
    }

    public function ajax_get_template_preview() {
        check_ajax_referer('shopglut_mega_menu_nonce', 'nonce');

        $template_id = isset($_POST['template_id']) ? sanitize_text_field(wp_unslash($_POST['template_id'])) : '';
        $templates = $this->getPrebuiltTemplates();
        $settings = $this->getSettings();

        if (!isset($templates[$template_id])) {
            wp_send_json_error('Template not found');
        }

        $template = $templates[$template_id];
        $custom_settings = $settings['custom_settings'][$template_id] ?? array();

        // Merge custom settings with template defaults
        $preview_settings = wp_parse_args($custom_settings, array(
            'primary_color' => $template['primary_color'],
            'background_color' => $template['background_color'],
            'text_color' => $template['text_color'],
            'columns' => $template['default_columns'],
            'show_images' => 1,
            'show_product_count' => 1
        ));

        ob_start();
        $this->renderTemplatePreview($template, $preview_settings);
        $preview_html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $preview_html,
            'template' => $template_id
        ));
    }

    private function renderTemplatePreview($template, $settings) {
        $primary_color = $settings['primary_color'];
        $background_color = $settings['background_color'];
        $text_color = $settings['text_color'];
        $columns = $settings['columns'];
        $show_images = $settings['show_images'];
        $show_product_count = $settings['show_product_count'];
        ?>

        <div class="mega-menu-preview" style="
            background: <?php echo esc_attr($background_color); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            border: 2px solid <?php echo esc_attr($primary_color); ?>;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        ">
            <!-- Menu Header -->
            <div class="menu-header" style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid rgba(<?php echo esc_attr(hex2rgb($primary_color)); ?>, 0.2);
            ">
                <div class="menu-title" style="
                    font-weight: 600;
                    font-size: 16px;
                    color: <?php echo esc_attr($primary_color); ?>;
                ">Main Menu Item</div>
                <div class="menu-arrow" style="
                    font-size: 12px;
                    opacity: 0.7;
                ">▼</div>
            </div>

            <!-- Mega Menu Content -->
            <div class="mega-menu-content" style="
                display: grid;
                grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);
                gap: 20px;
            ">
                <?php for ($i = 1; $i <= ($columns * 2); $i++): ?>
                    <div class="menu-category" style="text-align: center;">
                        <?php if ($show_images): ?>
                            <div class="category-image" style="
                                width: 50px;
                                height: 50px;
                                background: rgba(<?php echo esc_attr(hex2rgb($primary_color)); ?>, 0.1);
                                border-radius: 8px;
                                margin: 0 auto 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 20px;
                            ">📦</div>
                        <?php endif; ?>

                        <div class="category-title" style="
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Category <?php echo esc_html($i); ?></div>

                        <div class="subcategory-list" style="font-size: 12px; opacity: 0.8;">
                            <div>Subcategory 1</div>
                            <div>Subcategory 2</div>
                            <div>Subcategory 3</div>
                            <?php if ($show_product_count): ?>
                                <div style="font-size: 11px; opacity: 0.6; margin-top: 4px;">(25 items)</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <?php
    }

    public static function get_instance() {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}

// Helper function to convert hex color to RGB
if (!function_exists('hex2rgb')) {
    function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return "$r,$g,$b";
    }
}