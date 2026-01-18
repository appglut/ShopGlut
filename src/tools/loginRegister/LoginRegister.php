<?php
namespace Shopglut\tools\loginRegister;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoginRegister {

    private $option_group = 'shopglut_login_register_settings';
    
    public function __construct() {
        add_action('wp_ajax_shopglut_save_login_register_settings', array($this, 'ajax_save_settings'));
        add_action('wp_ajax_shopglut_customize_template', array($this, 'ajax_customize_template'));
        add_action('wp_ajax_shopglut_get_template_preview', array($this, 'ajax_get_template_preview'));
        
        // Hook into WordPress login/register if override is enabled
        $this->maybe_override_login();
    }

    public function renderLoginRegisterContent() {
        $this->renderSettingsPage();
    }

    private function renderSettingsPage() {
        $settings = $this->getSettings();
        $templates = $this->getPrebuiltTemplates();
        ?>
        <div class="wrap shopglut-admin-contents">
            <h2 style="text-align: center; font-weight: 700;"><?php echo esc_html__( 'Login/Register', 'shopglut' ); ?></h2>
            <p class="subheading" style="text-align: center;"><?php echo esc_html__( 'Override default WordPress login and registration with custom templates', 'shopglut' ); ?></p>
            
            <div class="login-register-container">
                <form id="login-register-settings-form" method="post">
                    <div class="settings-section shopglut-override-settings">
                        <!-- Header -->
                        <div class="settings-header" onclick="toggleOverrideSettings()">
                            <div class="settings-header-content">
                                <div class="settings-title">
                                    <h3><?php echo esc_html__( 'Override Settings', 'shopglut' ); ?></h3>
                                    <p><?php echo esc_html__( 'Configure how login and registration pages behave on your site', 'shopglut' ); ?></p>
                                </div>
                                <div class="settings-collapse-indicator">
                                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-body" id="override-settings-body" style="display: none;">
                            <!-- Main Override Toggle -->
                            <div class="setting-row override-toggle">
                                <div class="setting-col-full">
                                    <div class="setting-field">
                                        <label class="toggle-label">
                                            <input type="checkbox" id="override-login" name="override_login" value="1" <?php checked($settings['override_login'], 1); ?>>
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">
                                                <strong><?php echo esc_html__( 'Overwrite Default Login/Register Pages', 'shopglut' ); ?></strong>
                                                <small><?php echo esc_html__( 'Replace WordPress default pages with custom template', 'shopglut' ); ?></small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Template & Options Container -->
                            <div id="template-selection" class="template-options-container" style="<?php echo $settings['override_login'] ? '' : 'display: none;'; ?>">

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
                                    <!-- Login Redirect -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Login Redirect', 'shopglut' ); ?></label>
                                        <div class="radio-list">
                                            <label class="radio-item">
                                                <input type="radio" name="login_redirect_type" value="default" <?php checked($settings['login_redirect_type'], 'default'); ?>>
                                                <span><?php echo esc_html__( 'Default WordPress redirect', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="login_redirect_type" value="homepage" <?php checked($settings['login_redirect_type'], 'homepage'); ?>>
                                                <span><?php echo esc_html__( 'Redirect to homepage', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="login_redirect_type" value="custom" <?php checked($settings['login_redirect_type'], 'custom'); ?>>
                                                <span><?php echo esc_html__( 'Redirect to custom URL', 'shopglut' ); ?></span>
                                            </label>
                                            <input type="url" name="login_redirect_url" value="<?php echo esc_attr($settings['login_redirect_url']); ?>" placeholder="<?php echo esc_attr__('https://example.com/my-account', 'shopglut'); ?>" class="minimal-input" style="display: <?php echo ($settings['login_redirect_type'] === 'custom') ? 'block' : 'none'; ?>;">
                                        </div>
                                    </div>

                                    <!-- Registration Redirect -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Registration Redirect', 'shopglut' ); ?></label>
                                        <div class="radio-list">
                                            <label class="radio-item">
                                                <input type="radio" name="register_redirect_type" value="default" <?php checked($settings['register_redirect_type'], 'default'); ?>>
                                                <span><?php echo esc_html__( 'Default WordPress redirect', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="register_redirect_type" value="homepage" <?php checked($settings['register_redirect_type'], 'homepage'); ?>>
                                                <span><?php echo esc_html__( 'Redirect to homepage', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="register_redirect_type" value="login" <?php checked($settings['register_redirect_type'], 'login'); ?>>
                                                <span><?php echo esc_html__( 'Redirect to login page', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="radio-item">
                                                <input type="radio" name="register_redirect_type" value="custom" <?php checked($settings['register_redirect_type'], 'custom'); ?>>
                                                <span><?php echo esc_html__( 'Redirect to custom URL', 'shopglut' ); ?></span>
                                            </label>
                                            <input type="url" name="register_redirect_url" value="<?php echo esc_attr($settings['register_redirect_url']); ?>" placeholder="<?php echo esc_attr__('https://example.com/welcome', 'shopglut'); ?>" class="minimal-input" style="display: <?php echo ($settings['register_redirect_type'] === 'custom') ? 'block' : 'none'; ?>;">
                                        </div>
                                    </div>

                                    <!-- Other Options -->
                                    <div class="setting-col">
                                        <label class="field-label"><?php echo esc_html__( 'Other Options', 'shopglut' ); ?></label>
                                        <div class="checkbox-list">
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="hide_admin_bar" value="1" <?php checked($settings['hide_admin_bar'], 1); ?>>
                                                <span><?php echo esc_html__( 'Hide admin bar for subscribers', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="enable_recaptcha" value="1" <?php checked($settings['enable_recaptcha'], 1); ?>>
                                                <span><?php echo esc_html__( 'Enable reCAPTCHA (requires API keys)', 'shopglut' ); ?></span>
                                            </label>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="disable_registration" value="1" <?php checked($settings['disable_registration'], 1); ?>>
                                                <span><?php echo esc_html__( 'Disable user registration', 'shopglut' ); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Save Settings Button -->
                            <div class="save-settings-section">
                                <button type="submit" class="button button-primary minimal-save-btn">
                                    <?php echo esc_html__( 'Save Settings', 'shopglut' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="templates-section">
                    <h1 style="text-align: center; font-weight:bold"><?php echo esc_html__( 'Prebuilt Login/Register Templates', 'shopglut' ); ?></h1>
                    <p style="color: #666; margin-bottom: 20px; text-align: center;">
                        <?php echo esc_html__( 'Choose from our professionally designed login and registration page templates. You can customize colors, fonts, and layout.', 'shopglut' ); ?>
                    </p>
                    
                    <div class="templates-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
                        <?php foreach ($templates as $template_id => $template): ?>
                            <?php $this->renderTemplateCard($template_id, $template); ?>
                        <?php endforeach; ?>
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
                    <div class="modal-tabs">
                        <button type="button" class="modal-tab active" data-preview-tab="login" onclick="switchPreviewTab('login')">
                            Login Page
                        </button>
                        <button type="button" class="modal-tab" data-preview-tab="register" onclick="switchPreviewTab('register')">
                            Register Page
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="login-preview-content" class="preview-tab-content active" style="display: block;">
                            <!-- Login preview will be loaded here -->
                        </div>
                        <div id="register-preview-content" class="preview-tab-content" style="display: none;">
                            <!-- Register preview will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <style>

        /* Minimal Override Settings */
        .shopglut-override-settings {
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

        /* Override Toggle */
        .override-toggle {
            background: #f6f7f7;
            padding: 16px;
            border-radius: 3px;
            border: 1px solid #e0e0e0;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .toggle-label input[type="checkbox"] {
            display: none;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            background: #9ea3a8;
            border-radius: 12px;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .toggle-switch:after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
        }

        .toggle-label input[type="checkbox"]:checked + .toggle-switch {
            background: #2271b1;
        }

        .toggle-label input[type="checkbox"]:checked + .toggle-switch:after {
            transform: translateX(20px);
        }

        .toggle-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .toggle-text strong {
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }

        .toggle-text small {
            font-size: 13px;
            color: #646970;
        }

        /* Template Options Container */
        .template-options-container {
            margin-top: 24px;
        }

        /* Field Labels */
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
            margin-bottom: 8px;
        }

        /* Select Dropdown */
        .minimal-select {
            width: 100%;
            max-width: 100%;
            padding: 8px 10px;
            font-size: 14px;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            background: #fff;
            color: #2c3338;
        }

        .minimal-select:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }

        /* Radio List */
        .radio-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            cursor: pointer;
        }

        .radio-item input[type="radio"] {
            margin: 0;
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .radio-item span {
            font-size: 13px;
            color: #2c3338;
            line-height: 1.4;
        }

        .radio-item input[type="radio"]:checked + span {
            font-weight: 600;
        }

        /* Minimal Input */
        .minimal-input {
            width: 100%;
            padding: 8px 10px;
            font-size: 13px;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            margin-top: 8px;
        }

        .minimal-input:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }

        /* Checkbox List */
        .checkbox-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            cursor: pointer;
        }

        .checkbox-item input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-item span {
            font-size: 13px;
            color: #2c3338;
            line-height: 1.4;
        }

        .checkbox-item input[type="checkbox"]:checked + span {
            font-weight: 600;
        }

        .minimal-save-btn {
            padding: 6px 20px !important;
            font-size: 14px !important;
            height: auto !important;
            line-height: 1.6 !important;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .setting-row.three-cols {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 782px) {
            .settings-header,
            .settings-body {
                padding: 16px;
            }

            .save-settings-section {
                padding-top: 16px;
                margin-top: 16px;
            }
        }

        .login-template-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: all 0.3s ease;
            position: relative;
        }
        .login-template-card:hover {
            border-color: #0073aa;
            box-shadow: 0 6px 20px rgba(0, 115, 170, 0.15);
            transform: translateY(-2px);
        }
        .login-template-card.pro-template-locked {
            opacity: 0.85;
        }
        .login-template-card.pro-template-locked:hover {
            border-color: #d63638;
            box-shadow: 0 6px 20px rgba(214, 54, 56, 0.15);
        }
        .pro-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
            border-radius: 8px;
        }
        .login-template-card.pro-template-locked:hover .pro-overlay {
            opacity: 1;
        }
        .pro-overlay-content {
            text-align: center;
        }
        .pro-badge {
            background: linear-gradient(135deg, #d63638 0%, #8b0000 100%) !important;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pro-badge .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
        }
        .pro-badge-inline {
            display: inline-block;
            background: linear-gradient(135deg, #d63638 0%, #8b0000 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 8px;
            vertical-align: middle;
        }
        .upgrade-pro-button {
            background: linear-gradient(135deg, #d63638 0%, #8b0000 100%) !important;
            border-color: #d63638 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .upgrade-pro-button:hover {
            background: linear-gradient(135deg, #8b0000 0%, #d63638 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(214, 54, 56, 0.3);
        }
        /* Professional Template Card Styles */
        .login-template-card {
            background: #ffffff;
            border: 1px solid #e1e5e9;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .login-template-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            border-color: #d7dade;
        }

        .template-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e9ecef;
        }

        .template-card-icon {
            width: 48px;
            height: 48px;
            background: #0073aa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .pro-template-locked .template-card-icon {
            background: #d63638;
        }

        .template-card-badge {
            display: flex;
            align-items: center;
        }

        .category-badge {
            background: #0073aa;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pro-badge-small {
            background: #d63638;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .template-card-body {
            padding: 24px;
        }

        .template-title {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 600;
            color: #1d2327;
            line-height: 1.3;
        }

        .template-description {
            margin: 0 0 20px;
            color: #646970;
            font-size: 14px;
            line-height: 1.5;
        }

        .template-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .button-secondary {
            background: #f8f9fa;
            border: 1px solid #d7dade;
            color: #2c3338;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .button-secondary:hover {
            background: #e9ecef;
            border-color: #c7cdd1;
            color: #1d2327;
        }

        .button-primary {
            background: #0073aa;
            border: 1px solid #0073aa;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .button-primary:hover {
            background: #005a87;
            border-color: #005a87;
        }
        .template-preview.centered {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .template-preview.split {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }
        .template-preview.card {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        }
        .template-mockup {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.95);
            padding: 20px;
            border-radius: 8px;
            width: 200px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Login Form Mockup Styles */
        .login-form-mockup {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            text-align: center;
        }

        .mockup-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 16px;
            text-align: center;
        }

        .mockup-field {
            margin-bottom: 12px;
            
        }

        .mockup-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .mockup-input {
            background: #f8f9fa;
            border: 1px solid #ddd;
            height: 16px;
            border-radius: 3px;
        }

        .mockup-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            gap: 6px;
        }

        .mockup-checkbox-box {
            width: 10px;
            height: 10px;
            border: 1px solid #999;
            border-radius: 2px;
            background: #fff;
        }

        .mockup-checkbox-label {
            font-size: 10px;
            color: #666;
            flex: 1;
        }

        .mockup-button {
            background: #0073aa;
            color: white;
            padding: 6px 0;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .mockup-links {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .mockup-link {
            font-size: 10px;
            color: #0073aa;
            text-decoration: underline;
            cursor: pointer;
            flex: 1;
            text-align: center;
        }
        .template-info {
            padding: 20px;
            text-align: center;
        }
        .template-actions {
            display: flex !important;
            gap: 10px;
            margin-top: 15px;
            justify-content: center !important;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }
        .template-actions .button {
            margin: 0 !important;
            flex: 0 0 auto;
            width: auto !important;
            max-width: none !important;
            float: none !important;
        }
        .template-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Preview Modal Styles */
        .template-preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100000;
        }
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(2px);
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            z-index: 100001;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid #e0e0e0;
        }
        .modal-close:hover {
            color: #000;
        }
        .modal-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e0e0e0;
            padding: 0 25px;
            background: #f9f9f9;
        }
        .modal-tab {
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-bottom: 3px solid transparent;
            color: #666;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: -2px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .modal-tab:hover {
            color: #0073aa;
        }
        .modal-tab.active {
            background: #fff;
            border-bottom-color: #0073aa;
            color: #0073aa;
        }
        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            background: #f5f5f5;
        }
        .preview-tab-content {
            display: none;
            width: 100%;
            padding: 0;
        }
        .preview-tab-content.active {
            display: block;
        }
        /* Ensure consistent styling in modal previews */
        .preview-tab-content > div:first-child {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        /* Frontend template preview consistency */
        .template-preview-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .template-preview-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        /* Template Customizer Preview Tab Styles */
        .preview-tabs {
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
            padding: 15px;
            border-bottom: 2px solid #e5e7eb;
        }
        .preview-tab {
            padding: 10px 20px;
            border: none;
            background: transparent;
            border-bottom: 3px solid transparent;
            color: #6b7280;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            border-radius: 4px 4px 0 0;
            margin-right: 5px;
        }
        .preview-tab:hover {
            color: #0073aa;
            background: rgba(0, 115, 170, 0.05);
        }
        .preview-tab.active {
            color: #0073aa;
            background: #fff;
            border-bottom-color: #0073aa;
        }

        /* Preview content styling consistency */
        .preview-content {
            display: none;
        }
        .preview-content.active {
            display: block;
        }

        /* Form Element Styles */
        .field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }

        .minimal-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s ease;
        }

        .minimal-select:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }

        .minimal-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s ease;
        }

        .minimal-input:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }

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
            padding: 4px 0;
            transition: background-color 0.2s ease;
        }

        .radio-item:hover {
            background-color: #f8f9fa;
        }

        .radio-item input[type="radio"] {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: #2271b1;
        }

        .radio-item span {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }

        .checkbox-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px 0;
            transition: background-color 0.2s ease;
        }

        .checkbox-item:hover {
            background-color: #f8f9fa;
        }

        .checkbox-item input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: #2271b1;
        }

        .checkbox-item span {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }

        .minimal-save-btn {
            padding: 10px 20px;
            font-size: 14px;
            height: auto;
            line-height: 1.4;
        }

        .minimal-save-btn:hover {
            background: #0067b0 !important;
            border-color: #0067b0 !important;
        }

        .template-options-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .override-toggle {
            background: #f0f6fc;
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid #2271b1;
        }

        .setting-field {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            margin: 0;
            user-select: none;
        }

        .toggle-label input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Preview Tabs Styling */
        .live-preview {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Customizer Preview Tabs - Maximum Specificity */
        .wp-customizer .preview-tabs,
        .customize-control .preview-tabs,
        .template-customizer .preview-tabs,
        div.preview-tabs {
            display: flex !important;
            gap: 0 !important;
            border-bottom: 2px solid #ddd !important;
            background: #f9f9f9 !important;
            border-radius: 6px 6px 0 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Preview Tab Buttons - Maximum Specificity */
        .wp-customizer .preview-tab,
        .customize-control .preview-tab,
        .template-customizer .preview-tab,
        div.preview-tabs button,
        .preview-tab button,
        button.preview-tab {
            padding: 12px 20px !important;
            border: none !important;
            background: #f9f9f9 !important;
            border-bottom: 3px solid transparent !important;
            color: #666 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
            position: relative !important;
            top: 0 !important;
            margin: 0 !important;
            flex: 1 !important;
            text-align: center !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            text-transform: none !important;
            line-height: 1.4 !important;
            min-height: 44px !important;
            height: auto !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            float: none !important;
            display: block !important;
            width: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .wp-customizer .preview-tab:hover,
        .customize-control .preview-tab:hover,
        .template-customizer .preview-tab:hover,
        div.preview-tabs button:hover,
        .preview-tab button:hover,
        button.preview-tab:hover {
            color: #0073aa !important;
            background: rgba(0, 115, 170, 0.1) !important;
            border-bottom-color: transparent !important;
        }

        .wp-customizer .preview-tab.active,
        .customize-control .preview-tab.active,
        .template-customizer .preview-tab.active,
        div.preview-tabs button.active,
        .preview-tab button.active,
        button.preview-tab.active {
            color: #0073aa !important;
            background: #fff !important;
            border-bottom: 3px solid #0073aa !important;
            box-shadow: 0 -3px 0 0 #0073aa !important;
            position: relative !important;
            z-index: 1 !important;
        }

        .wp-customizer .preview-tab:focus,
        .customize-control .preview-tab:focus,
        .template-customizer .preview-tab:focus,
        div.preview-tabs button:focus,
        .preview-tab button:focus,
        button.preview-tab:focus {
            outline: 2px solid #0073aa !important;
            outline-offset: -2px !important;
        }

        .wp-customizer .preview-tab:active,
        .customize-control .preview-tab:active,
        .template-customizer .preview-tab:active,
        div.preview-tabs button:active,
        .preview-tab button:active,
        button.preview-tab:active {
            transform: translateY(1px) !important;
        }

        .preview-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #0073aa;
        }

        .template-preview-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
        }

        .template-preview-content {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .preview-content {
            display: none;
            width: 100%;
        }

        .preview-content.active {
            display: block;
        }

        /* Live Preview Header */
        .live-preview h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1d2327;
            margin: 0;
        }

        /* Template Customization Panel */
        .customization-panel {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .customization-panel h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
            margin: 20px 25px 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .settings-tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            background: #f9f9f9;
            padding: 0 10px;
        }

        .settings-tab {
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-bottom: 3px solid transparent;
            color: #666;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: -2px;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .settings-tab:hover {
            color: #0073aa;
            background: #f8f9fa;
        }

        .settings-tab.active {
            background: #fff;
            color: #0073aa;
            border-bottom-color: #0073aa;
            box-shadow: 0 -1px 0 0 #0073aa;
        }

        .settings-tab-content {
            display: none;
            padding: 0;
        }

        .settings-tab-content.active {
            display: block;
        }

        /* Customization Group Styling */
        .customization-group {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .customization-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .customization-group h4 {
            margin-top: 0;
            color: #2271b1;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Color Inputs */
        input[type="color"] {
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }

        input[type="color"]:hover {
            border-color: #0073aa;
        }

        input[type="color"]:focus {
            outline: none;
            border-color: #0073aa;
            box-shadow: 0 0 0 2px rgba(0, 115, 170, 0.3);
        }

        /* Text Inputs */
        input[type="text"],
        input[type="url"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.4;
            transition: border-color 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #0073aa;
            box-shadow: 0 0 0 1px #0073aa;
        }

        /* Select Inputs */
        select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            background: #fff;
            transition: border-color 0.2s ease;
        }

        select:focus {
            outline: none;
            border-color: #0073aa;
            box-shadow: 0 0 0 1px #0073aa;
        }

        /* Text Labels */
        .customization-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 12px;
            color: #333;
        }

        /* Range Slider */
        input[type="range"] {
            width: 100%;
            height: 6px;
            background: #ddd;
            border-radius: 3px;
            outline: none;
            -webkit-appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            background: #0073aa;
            border-radius: 50%;
            cursor: pointer;
        }

        input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            background: #0073aa;
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }
        </style>

        <script>
        function toggleOverrideSettings() {
            var $body = jQuery('#override-settings-body');
            var $header = jQuery('.settings-header');

            if ($body.is(':visible')) {
                $body.slideUp();
                $header.addClass('collapsed');
            } else {
                $body.slideDown();
                $header.removeClass('collapsed');
            }
        }

        jQuery(document).ready(function($) {
            // Initialize collapse state
            $('.settings-header').addClass('collapsed');

            // Toggle template selection visibility
            $('#override-login').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#template-selection').slideDown();
                } else {
                    $('#template-selection').slideUp();
                }
            });

            // Toggle custom URL fields based on radio selection
            $('input[name="login_redirect_type"]').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('input[name="login_redirect_url"]').slideDown();
                } else {
                    $('input[name="login_redirect_url"]').slideUp();
                }
            });

            $('input[name="register_redirect_type"]').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('input[name="register_redirect_url"]').slideDown();
                } else {
                    $('input[name="register_redirect_url"]').slideUp();
                }
            });

            // Save settings form
            $('#login-register-settings-form').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    action: 'shopglut_save_login_register_settings',
                    override_login: $('#override-login').is(':checked') ? 1 : 0,
                    selected_template: $('#selected-template').val(),
                    login_redirect_type: $('input[name="login_redirect_type"]:checked').val(),
                    login_redirect_url: $('input[name="login_redirect_url"]').val(),
                    register_redirect_type: $('input[name="register_redirect_type"]:checked').val(),
                    register_redirect_url: $('input[name="register_redirect_url"]').val(),
                    hide_admin_bar: $('input[name="hide_admin_bar"]').is(':checked') ? 1 : 0,
                    enable_recaptcha: $('input[name="enable_recaptcha"]').is(':checked') ? 1 : 0,
                    disable_registration: $('input[name="disable_registration"]').is(':checked') ? 1 : 0,
                    nonce: '<?php echo esc_attr( wp_create_nonce('shopglut_login_register_nonce') ); ?>'
                };

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        // Disable the submit button
                        $('#login-register-settings-form button[type="submit"]').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success notification with better styling
                            var notification = $('<div class="shopglut-settings-toast" style="position: fixed !important; bottom: 20px !important; right: 20px !important; background: #4CAF50 !important; color: white !important; padding: 15px 20px !important; border-radius: 6px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important; z-index: 999999 !important; font-weight: 600 !important; display: inline-flex !important; align-items: center !important; gap: 10px !important; width: auto !important; max-width: 350px !important; white-space: nowrap !important;"><span class="dashicons dashicons-yes-alt" style="font-size: 20px; width: 20px; height: 20px;"></span><span>Settings saved successfully!</span></div>');
                            $('body').append(notification);
                            setTimeout(function() {
                                notification.fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        } else {
                            // Show error notification
                            var errorNotification = $('<div class="shopglut-settings-toast" style="position: fixed !important; bottom: 20px !important; right: 20px !important; background: #d63638 !important; color: white !important; padding: 15px 20px !important; border-radius: 6px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important; z-index: 999999 !important; font-weight: 600 !important; display: inline-flex !important; align-items: center !important; gap: 10px !important; width: auto !important; max-width: 350px !important;"><span class="dashicons dashicons-warning" style="font-size: 20px; width: 20px; height: 20px;"></span><span>' + (response.data.message || 'Error saving settings') + '</span></div>');
                            $('body').append(errorNotification);
                            setTimeout(function() {
                                errorNotification.fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 4000);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = 'Error saving settings';
                        if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                            errorMsg = xhr.responseJSON.data.message;
                        } else {
                            errorMsg += ': ' + error;
                        }
                        var errorNotification = $('<div class="shopglut-settings-toast" style="position: fixed !important; bottom: 20px !important; right: 20px !important; background: #d63638 !important; color: white !important; padding: 15px 20px !important; border-radius: 6px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important; z-index: 999999 !important; font-weight: 600 !important; display: inline-flex !important; align-items: center !important; gap: 10px !important; width: auto !important; max-width: 350px !important;"><span class="dashicons dashicons-warning" style="font-size: 20px; width: 20px; height: 20px;"></span><span>' + errorMsg + '</span></div>');
                        $('body').append(errorNotification);
                        setTimeout(function() {
                            errorNotification.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 4000);
                    },
                    complete: function() {
                        // Re-enable the submit button
                        $('#login-register-settings-form button[type="submit"]').prop('disabled', false).text('<?php echo esc_js(__('Save Settings', 'shopglut')); ?>');
                    }
                });
            });
        });

        // Global functions - explicitly attached to window object
        window.customizeTemplate = function(templateId) {
            window.location.href = '<?php echo esc_url_raw( admin_url('admin.php?page=shopglut_tools&editor=login_register&template_id=') ); ?>' + templateId;
        };

        window.previewTemplate = function(templateId) {
            console.log('previewTemplate called with templateId:', templateId);

            // Create or get inline loader
            var loaderId = 'shopglut-preview-loader';
            var loader = document.getElementById(loaderId);

            if (!loader) {
                // Create loader if it doesn't exist
                loader = document.createElement('div');
                loader.id = loaderId;
                loader.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100vh; width: 100vw; position: fixed; top: 0; left: 0;">
                        <div style="text-align: center; background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); border: 1px solid #e5e7eb;">
                            <div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #007cba; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                            <div style="color: #333; font-size: 16px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;">Loading Preview...</div>
                        </div>
                    </div>
                    <style>
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                `;
                document.body.appendChild(loader);
            }


            // Force inline styles to override any CSS
            loader.style.setProperty('display', 'flex', 'important');
            loader.style.setProperty('opacity', '1', 'important');
            loader.style.setProperty('position', 'fixed', 'important');
            loader.style.setProperty('top', '0', 'important');
            loader.style.setProperty('left', '0', 'important');
            loader.style.setProperty('width', '100%', 'important');
            loader.style.setProperty('height', '100%', 'important');
            loader.style.setProperty('z-index', '9999999', 'important');
            loader.style.setProperty('background-color', 'rgba(255, 255, 255, 0.95)', 'important');


            // Get template data
            var templates = <?php echo wp_json_encode($templates); ?>;
            var template = templates[templateId];

            if (!template) {
                // Hide loader
                if (loader) {
                    loader.style.setProperty('opacity', '0', 'important');
                    setTimeout(function() {
                        loader.style.setProperty('display', 'none', 'important');
                    }, 300);
                }
                alert('Template not found');
                return;
            }

            // Set modal title
            document.getElementById('modal-template-title').textContent = template.name + ' - Preview';

            // Load previews via AJAX
            jQuery.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'shopglut_get_template_preview',
                    template_id: templateId,
                    nonce: '<?php echo esc_attr( wp_create_nonce('shopglut_login_register_nonce') ); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        document.getElementById('login-preview-content').innerHTML = response.data.login_preview;
                        document.getElementById('register-preview-content').innerHTML = response.data.register_preview;

                        // Show modal
                        document.getElementById('template-preview-modal').style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    } else {
                        alert('Error loading preview: ' + (response.data.message || 'Unknown error'));
                    }
                    // Hide loader with fade effect
                    if (loader) {
                        loader.style.setProperty('opacity', '0', 'important');
                        setTimeout(function() {
                            loader.style.setProperty('display', 'none', 'important');
                        }, 300);
                    }
                },
                error: function() {
                    // Hide loader on error
                    if (loader) {
                        loader.style.setProperty('opacity', '0', 'important');
                        setTimeout(function() {
                            loader.style.setProperty('display', 'none', 'important');
                        }, 300);
                    }
                    alert('Error loading preview');
                }
            });
        };

        window.closePreviewModal = function() {
            document.getElementById('template-preview-modal').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.switchPreviewTab = function(tab) {
            // Update tab buttons
            var tabs = document.querySelectorAll('.modal-tab');
            tabs.forEach(function(t) {
                t.classList.remove('active');
            });

            var activeTab = document.querySelector('.modal-tab[data-preview-tab="' + tab + '"]');
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Update tab content with proper display management
            var loginContent = document.getElementById('login-preview-content');
            var registerContent = document.getElementById('register-preview-content');

            if (loginContent && registerContent) {
                if (tab === 'login') {
                    loginContent.style.display = 'block';
                    loginContent.classList.add('active');
                    registerContent.style.display = 'none';
                    registerContent.classList.remove('active');
                } else {
                    registerContent.style.display = 'block';
                    registerContent.classList.add('active');
                    loginContent.style.display = 'none';
                    loginContent.classList.remove('active');
                }
            }
        };

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closePreviewModal();
            }
        });
        </script>
        <?php
    }

    private function renderTemplateCard($template_id, $template) {
        $is_pro = isset($template['is_pro']) && $template['is_pro'] === true;
        $card_class = $is_pro ? 'login-template-card pro-template-locked' : 'login-template-card';

        // Get current settings to check if this template is selected
        $settings = $this->getSettings();
        $is_current_template = ($settings['selected_template'] === $template_id);
        ?>
        <div class="<?php echo esc_attr($card_class); ?>">
            <div class="template-card-header">
                <div class="template-card-icon">
                    <?php if ($is_pro): ?>
                        <span class="dashicons dashicons-lock"></span>
                    <?php else: ?>
                        <span class="dashicons dashicons-admin-users"></span>
                    <?php endif; ?>
                </div>
                <div class="template-card-badge">
                    <?php if ($is_pro): ?>
                        <span class="pro-badge-small">PRO</span>
                    <?php else: ?>
                        <span class="category-badge"><?php echo esc_html($template['category']); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="template-card-body">
                <h3 class="template-title">
                    <?php echo esc_html($template['name']); ?>
                </h3>
                <p class="template-description">
                    <?php echo esc_html($template['description']); ?>
                </p>

                <div class="template-actions">
                    <?php if ($is_pro): ?>
                        <a href="https://www.appglut.com/plugin/shopglut/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=upgrade"
                           target="_blank"
                           class="button button-primary upgrade-pro-button">
                            <span class="dashicons dashicons-unlock" style="font-size: 14px; margin-right: 6px;"></span>
                            <?php echo esc_html__('Upgrade to Pro', 'shopglut'); ?>
                        </a>
                    <?php else: ?>
                        <button type="button" class="button button-primary" onclick="customizeTemplate('<?php echo esc_attr($template_id); ?>')">
                            <?php echo esc_html__('Customize', 'shopglut'); ?>
                        </button>
                        <button type="button" class="button button-secondary" onclick="previewTemplate('<?php echo esc_attr($template_id); ?>')">
                            <?php echo esc_html__('Preview', 'shopglut'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function renderTemplateCustomizer() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter for template ID
        $template_id = isset($_GET['template_id']) ? sanitize_text_field(wp_unslash($_GET['template_id'])) : '';

        if (empty($template_id)) {
            wp_die('Template ID is required');
        }
        $template = $this->getTemplate($template_id);
        if (!$template) {
            wp_die('Template not found');
        }

        $custom_settings = $this->getTemplateCustomSettings($template_id);
        ?>
        <style>
        html.wp-toolbar{
            padding-top:0px !important;
        }
        </style>
        <div class="wrap shopglut-admin-contents">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #ddd;">
                <a href="<?php echo esc_url( admin_url('admin.php?page=shopglut_tools&view=login_register') ); ?>" class="button" style="display: inline-flex; align-items: center; padding: 5px 15px; text-decoration: none;">
                    <?php echo esc_html__('← Back to Templates', 'shopglut'); ?>
                </a>
                <div style="flex: 1; text-align: center; padding: 0 20px;">
                    <h1 style="margin: 0 0 5px 0; font-weight: 700; font-size: 24px; line-height: 1.2;"><?php echo esc_html__('Customize Template:', 'shopglut'); ?> <strong><?php echo esc_html($template['name']); ?></strong></h1>
                    <p style="margin: 0; color: #666; font-size: 14px;"><?php echo esc_html__('Customize colors, fonts, and layout for your login/register page', 'shopglut'); ?></p>
                </div>
                <div style="width: 140px;"></div>
            </div>

            <div style="display: grid; grid-template-columns: 350px 1fr; gap: 30px;">
                <!-- Customization Panel -->
                <div class="customization-panel" style="background: #fff; padding: 0; border: 1px solid #ddd; border-radius: 8px; height: fit-content; max-height: 95vh; overflow: hidden; display: flex; flex-direction: column;">
                    <h3 style="margin: 20px 25px 15px; border-bottom: 1px solid #ddd; padding-bottom: 15px;"><?php echo esc_html__('Customization Options', 'shopglut'); ?></h3>

                    <!-- Tab Navigation -->
                    <div class="settings-tabs" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-bottom: 2px solid #ddd; background: #f9f9f9; padding: 0;">
                        <button type="button" class="settings-tab active" data-tab="design" style="padding: 16px 20px; border: none; background: #fff; border-bottom: 3px solid #0073aa; color: #0073aa; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-right: 1px solid #ddd; border-bottom: 3px solid #0073aa;">
                            <?php echo esc_html__('Design', 'shopglut'); ?>
                        </button>
                        <button type="button" class="settings-tab" data-tab="login-text" style="padding: 16px 20px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-bottom: 3px solid transparent;">
                            <?php echo esc_html__('Login Text', 'shopglut'); ?>
                        </button>
                        <?php if (isset($template['layout_type']) && $template['layout_type'] === 'split'): ?>
                        <button type="button" class="settings-tab" data-tab="content" style="padding: 16px 20px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-right: 1px solid #ddd; border-bottom: 3px solid transparent;">
                            <?php echo esc_html__('Content', 'shopglut'); ?>
                        </button>
                        <button type="button" class="settings-tab" data-tab="register-text" style="padding: 16px 20px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-bottom: 3px solid transparent;">
                            <?php echo esc_html__('Register Text', 'shopglut'); ?>
                        </button>
                        <?php else: ?>
                        <button type="button" class="settings-tab" data-tab="register-text" style="padding: 16px 20px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-right: 1px solid #ddd; border-bottom: 3px solid transparent;">
                            <?php echo esc_html__('Register Text', 'shopglut'); ?>
                        </button>
                        <?php endif; ?>
                        <button type="button" class="settings-tab" data-tab="forgot-text" style="padding: 16px 20px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; border-bottom: 3px solid transparent;">
                            <?php echo esc_html__('Forgot Password', 'shopglut'); ?>
                        </button>
                    </div>

                    <form id="template-customization-form" style="flex: 1; overflow-y: auto; padding: 20px 25px;">
                        <!-- Design Tab Content -->
                        <div class="settings-tab-content" id="design-tab" style="display: block;">
                            <!-- Colors Section -->
                            <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Colors', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Primary Color', 'shopglut'); ?></label>
                                <input type="color" name="primary_color" value="<?php echo esc_attr($custom_settings['primary_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Secondary Color', 'shopglut'); ?></label>
                                <input type="color" name="secondary_color" value="<?php echo esc_attr($custom_settings['secondary_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Background Color', 'shopglut'); ?></label>
                                <input type="color" name="background_color" value="<?php echo esc_attr($custom_settings['background_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Text Color', 'shopglut'); ?></label>
                                <input type="color" name="text_color" value="<?php echo esc_attr($custom_settings['text_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Input Border Color', 'shopglut'); ?></label>
                                <input type="color" name="input_border_color" value="<?php echo esc_attr($custom_settings['input_border_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>
                        </div>

                        <!-- Typography Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Typography', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Font Family', 'shopglut'); ?></label>
                                <select name="font_family" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="inherit" <?php selected($custom_settings['font_family'], 'inherit'); ?>>Default</option>
                                    <option value="'Arial', sans-serif" <?php selected($custom_settings['font_family'], "'Arial', sans-serif"); ?>>Arial</option>
                                    <option value="'Helvetica', sans-serif" <?php selected($custom_settings['font_family'], "'Helvetica', sans-serif"); ?>>Helvetica</option>
                                    <option value="'Georgia', serif" <?php selected($custom_settings['font_family'], "'Georgia', serif"); ?>>Georgia</option>
                                    <option value="'Times New Roman', serif" <?php selected($custom_settings['font_family'], "'Times New Roman', serif"); ?>>Times New Roman</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Body Font Size', 'shopglut'); ?></label>
                                <select name="font_size" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="14px" <?php selected($custom_settings['font_size'], '14px'); ?>>Small (14px)</option>
                                    <option value="16px" <?php selected($custom_settings['font_size'], '16px'); ?>>Medium (16px)</option>
                                    <option value="18px" <?php selected($custom_settings['font_size'], '18px'); ?>>Large (18px)</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Heading Font Size', 'shopglut'); ?></label>
                                <select name="heading_font_size" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="20px" <?php selected($custom_settings['heading_font_size'], '20px'); ?>>Small (20px)</option>
                                    <option value="24px" <?php selected($custom_settings['heading_font_size'], '24px'); ?>>Medium (24px)</option>
                                    <option value="28px" <?php selected($custom_settings['heading_font_size'], '28px'); ?>>Large (28px)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Layout Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Layout & Spacing', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Form Width', 'shopglut'); ?></label>
                                <select name="form_width" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="300px" <?php selected($custom_settings['form_width'], '300px'); ?>>Narrow (300px)</option>
                                    <option value="400px" <?php selected($custom_settings['form_width'], '400px'); ?>>Medium (400px)</option>
                                    <option value="500px" <?php selected($custom_settings['form_width'], '500px'); ?>>Wide (500px)</option>
                                    <option value="600px" <?php selected($custom_settings['form_width'], '600px'); ?>>Extra Wide (600px)</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Border Radius', 'shopglut'); ?></label>
                                <select name="border_radius" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="0px" <?php selected($custom_settings['border_radius'], '0px'); ?>>None (0px)</option>
                                    <option value="4px" <?php selected($custom_settings['border_radius'], '4px'); ?>>Small (4px)</option>
                                    <option value="8px" <?php selected($custom_settings['border_radius'], '8px'); ?>>Medium (8px)</option>
                                    <option value="16px" <?php selected($custom_settings['border_radius'], '16px'); ?>>Large (16px)</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Input Padding', 'shopglut'); ?></label>
                                <select name="input_padding" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="8px" <?php selected($custom_settings['input_padding'], '8px'); ?>>Compact (8px)</option>
                                    <option value="12px" <?php selected($custom_settings['input_padding'], '12px'); ?>>Normal (12px)</option>
                                    <option value="16px" <?php selected($custom_settings['input_padding'], '16px'); ?>>Comfortable (16px)</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Button Padding', 'shopglut'); ?></label>
                                <select name="button_padding" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="10px" <?php selected($custom_settings['button_padding'], '10px'); ?>>Compact (10px)</option>
                                    <option value="15px" <?php selected($custom_settings['button_padding'], '15px'); ?>>Normal (15px)</option>
                                    <option value="20px" <?php selected($custom_settings['button_padding'], '20px'); ?>>Large (20px)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Form Elements Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Form Elements', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Input Field Style', 'shopglut'); ?></label>
                                <select name="input_field_style" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="minimal" <?php selected(isset($custom_settings['input_field_style']) ? $custom_settings['input_field_style'] : 'minimal', 'minimal'); ?>>Minimal (Bottom border only)</option>
                                    <option value="outlined" <?php selected(isset($custom_settings['input_field_style']) ? $custom_settings['input_field_style'] : 'minimal', 'outlined'); ?>>Outlined (Full border)</option>
                                    <option value="filled" <?php selected(isset($custom_settings['input_field_style']) ? $custom_settings['input_field_style'] : 'minimal', 'filled'); ?>>Filled (Background)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Input Focus Color', 'shopglut'); ?></label>
                                <input type="color" name="input_focus_color" value="<?php echo esc_attr(isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : '#007cba'); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Show Input Icons', 'shopglut'); ?></label>
                                <select name="show_input_icons" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="yes" <?php selected(isset($custom_settings['show_input_icons']) ? $custom_settings['show_input_icons'] : 'yes', 'yes'); ?>>Yes</option>
                                    <option value="no" <?php selected(isset($custom_settings['show_input_icons']) ? $custom_settings['show_input_icons'] : 'yes', 'no'); ?>>No</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Label Font Weight', 'shopglut'); ?></label>
                                <select name="label_font_weight" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="400" <?php selected(isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600', '400'); ?>>Normal</option>
                                    <option value="500" <?php selected(isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600', '500'); ?>>Medium</option>
                                    <option value="600" <?php selected(isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600', '600'); ?>>Semi-Bold</option>
                                    <option value="700" <?php selected(isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600', '700'); ?>>Bold</option>
                                </select>
                            </div>
                        </div>

                        <!-- Button Styling Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Button Styling', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Button Style', 'shopglut'); ?></label>
                                <select name="button_style" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="filled" <?php selected(isset($custom_settings['button_style']) ? $custom_settings['button_style'] : 'filled', 'filled'); ?>>Filled</option>
                                    <option value="outline" <?php selected(isset($custom_settings['button_style']) ? $custom_settings['button_style'] : 'filled', 'outline'); ?>>Outline</option>
                                    <option value="gradient" <?php selected(isset($custom_settings['button_style']) ? $custom_settings['button_style'] : 'filled', 'gradient'); ?>>Gradient</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Button Hover Effect', 'shopglut'); ?></label>
                                <select name="button_hover_effect" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="lift" <?php selected(isset($custom_settings['button_hover_effect']) ? $custom_settings['button_hover_effect'] : 'lift', 'lift'); ?>>Lift Up</option>
                                    <option value="scale" <?php selected(isset($custom_settings['button_hover_effect']) ? $custom_settings['button_hover_effect'] : 'lift', 'scale'); ?>>Scale Up</option>
                                    <option value="shadow" <?php selected(isset($custom_settings['button_hover_effect']) ? $custom_settings['button_hover_effect'] : 'lift', 'shadow'); ?>>Add Shadow</option>
                                    <option value="none" <?php selected(isset($custom_settings['button_hover_effect']) ? $custom_settings['button_hover_effect'] : 'lift', 'none'); ?>>None</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Button Border Radius', 'shopglut'); ?></label>
                                <select name="button_border_radius" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="4px" <?php selected(isset($custom_settings['button_border_radius']) ? $custom_settings['button_border_radius'] : '8px', '4px'); ?>>Small (4px)</option>
                                    <option value="8px" <?php selected(isset($custom_settings['button_border_radius']) ? $custom_settings['button_border_radius'] : '8px', '8px'); ?>>Medium (8px)</option>
                                    <option value="12px" <?php selected(isset($custom_settings['button_border_radius']) ? $custom_settings['button_border_radius'] : '8px', '12px'); ?>>Large (12px)</option>
                                    <option value="25px" <?php selected(isset($custom_settings['button_border_radius']) ? $custom_settings['button_border_radius'] : '8px', '25px'); ?>>Pill Shape</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Button Text Transform', 'shopglut'); ?></label>
                                <select name="button_text_transform" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="none" <?php selected(isset($custom_settings['button_text_transform']) ? $custom_settings['button_text_transform'] : 'none', 'none'); ?>>None</option>
                                    <option value="uppercase" <?php selected(isset($custom_settings['button_text_transform']) ? $custom_settings['button_text_transform'] : 'none', 'uppercase'); ?>>Uppercase</option>
                                    <option value="capitalize" <?php selected(isset($custom_settings['button_text_transform']) ? $custom_settings['button_text_transform'] : 'none', 'capitalize'); ?>>Capitalize</option>
                                </select>
                            </div>
                        </div>

                        <!-- Logo/Branding Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Logo & Branding', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Logo Style', 'shopglut'); ?></label>
                                <select name="logo_style" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="circle" <?php selected(isset($custom_settings['logo_style']) ? $custom_settings['logo_style'] : 'circle', 'circle'); ?>>Circle</option>
                                    <option value="square" <?php selected(isset($custom_settings['logo_style']) ? $custom_settings['logo_style'] : 'circle', 'square'); ?>>Square</option>
                                    <option value="rounded" <?php selected(isset($custom_settings['logo_style']) ? $custom_settings['logo_style'] : 'circle', 'rounded'); ?>>Rounded Square</option>
                                    <option value="text" <?php selected(isset($custom_settings['logo_style']) ? $custom_settings['logo_style'] : 'circle', 'text'); ?>>Text Only</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Logo Size', 'shopglut'); ?></label>
                                <select name="logo_size" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="60px" <?php selected(isset($custom_settings['logo_size']) ? $custom_settings['logo_size'] : '80px', '60px'); ?>>Small (60px)</option>
                                    <option value="80px" <?php selected(isset($custom_settings['logo_size']) ? $custom_settings['logo_size'] : '80px', '80px'); ?>>Medium (80px)</option>
                                    <option value="100px" <?php selected(isset($custom_settings['logo_size']) ? $custom_settings['logo_size'] : '80px', '100px'); ?>>Large (100px)</option>
                                    <option value="120px" <?php selected(isset($custom_settings['logo_size']) ? $custom_settings['logo_size'] : '80px', '120px'); ?>>Extra Large (120px)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Logo Text', 'shopglut'); ?></label>
                                <input type="text" name="logo_text" value="<?php echo esc_attr(isset($custom_settings['logo_text']) ? $custom_settings['logo_text'] : 'SG'); ?>" style="width: 100%; padding: 6px; font-size: 13px;" placeholder="SG">
                                <p style="color: #666; font-size: 12px; margin: 5px 0 0 0;"><?php echo esc_html__('Text to show in logo when using text style.', 'shopglut'); ?></p>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Show Subtitle', 'shopglut'); ?></label>
                                <select name="show_subtitle" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="yes" <?php selected(isset($custom_settings['show_subtitle']) ? $custom_settings['show_subtitle'] : 'yes', 'yes'); ?>>Yes</option>
                                    <option value="no" <?php selected(isset($custom_settings['show_subtitle']) ? $custom_settings['show_subtitle'] : 'yes', 'no'); ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Advanced Typography Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Advanced Typography', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Line Height', 'shopglut'); ?></label>
                                <select name="line_height" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="1.2" <?php selected(isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5', '1.2'); ?>>Tight (1.2)</option>
                                    <option value="1.4" <?php selected(isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5', '1.4'); ?>Normal (1.4)</option>
                                    <option value="1.5" <?php selected(isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5', '1.5'); ?>>Comfortable (1.5)</option>
                                    <option value="1.6" <?php selected(isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5', '1.6'); ?>>Relaxed (1.6)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Letter Spacing', 'shopglut'); ?></label>
                                <select name="letter_spacing" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="-0.5px" <?php selected(isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0', '-0.5px'); ?>>Tight (-0.5px)</option>
                                    <option value="0" <?php selected(isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0', '0'); ?>>Normal (0)</option>
                                    <option value="0.5px" <?php selected(isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0', '0.5px'); ?>>Loose (0.5px)</option>
                                    <option value="1px" <?php selected(isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0', '1px'); ?>>Very Loose (1px)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Heading Weight', 'shopglut'); ?></label>
                                <select name="heading_weight" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="300" <?php selected(isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600', '300'); ?>>Light</option>
                                    <option value="400" <?php selected(isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600', '400'); ?>>Normal</option>
                                    <option value="500" <?php selected(isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600', '500'); ?>>Medium</option>
                                    <option value="600" <?php selected(isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600', '600'); ?>>Semi-Bold</option>
                                    <option value="700" <?php selected(isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600', '700'); ?>>Bold</option>
                                </select>
                            </div>
                        </div>

                        <!-- Visual Effects Section -->
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Visual Effects', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Form Shadow', 'shopglut'); ?></label>
                                <select name="form_shadow" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="none" <?php selected(isset($custom_settings['form_shadow']) ? $custom_settings['form_shadow'] : 'medium', 'none'); ?>>None</option>
                                    <option value="light" <?php selected(isset($custom_settings['form_shadow']) ? $custom_settings['form_shadow'] : 'medium', 'light'); ?>>Light</option>
                                    <option value="medium" <?php selected(isset($custom_settings['form_shadow']) ? $custom_settings['form_shadow'] : 'medium', 'medium'); ?>>Medium</option>
                                    <option value="strong" <?php selected(isset($custom_settings['form_shadow']) ? $custom_settings['form_shadow'] : 'medium', 'strong'); ?>>Strong</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Animation Speed', 'shopglut'); ?></label>
                                <select name="animation_speed" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="fast" <?php selected(isset($custom_settings['animation_speed']) ? $custom_settings['animation_speed'] : 'normal', 'fast'); ?>>Fast (0.1s)</option>
                                    <option value="normal" <?php selected(isset($custom_settings['animation_speed']) ? $custom_settings['animation_speed'] : 'normal', 'normal'); ?>>Normal (0.2s)</option>
                                    <option value="slow" <?php selected(isset($custom_settings['animation_speed']) ? $custom_settings['animation_speed'] : 'normal', 'slow'); ?>>Slow (0.3s)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Enable Animations', 'shopglut'); ?></label>
                                <select name="enable_animations" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="yes" <?php selected(isset($custom_settings['enable_animations']) ? $custom_settings['enable_animations'] : 'yes', 'yes'); ?>>Yes</option>
                                    <option value="no" <?php selected(isset($custom_settings['enable_animations']) ? $custom_settings['enable_animations'] : 'yes', 'no'); ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Card-Specific Settings (Floating Card Only) -->
                        <?php if (isset($template['layout_type']) && $template['layout_type'] === 'card'): ?>
                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Card Styling', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Border Top Accent Color', 'shopglut'); ?></label>
                                <input type="color" name="card_border_top_color" value="<?php echo esc_attr($custom_settings['card_border_top_color']); ?>" style="width: 100%; height: 40px; cursor: pointer;">
                                <p style="color: #666; font-size: 12px; margin: 5px 0 0 0;"><?php echo esc_html__('The colored border at the top of the card.', 'shopglut'); ?></p>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Border Top Width', 'shopglut'); ?></label>
                                <select name="card_border_top_width" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="0px" <?php selected($custom_settings['card_border_top_width'], '0px'); ?>>None (0px)</option>
                                    <option value="2px" <?php selected($custom_settings['card_border_top_width'], '2px'); ?>>Thin (2px)</option>
                                    <option value="4px" <?php selected($custom_settings['card_border_top_width'], '4px'); ?>>Medium (4px)</option>
                                    <option value="6px" <?php selected($custom_settings['card_border_top_width'], '6px'); ?>>Thick (6px)</option>
                                    <option value="8px" <?php selected($custom_settings['card_border_top_width'], '8px'); ?>>Extra Thick (8px)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Shadow Intensity', 'shopglut'); ?></label>
                                <select name="card_shadow_intensity" style="width: 100%; padding: 8px; font-size: 14px;">
                                    <option value="none" <?php selected($custom_settings['card_shadow_intensity'], 'none'); ?>>None</option>
                                    <option value="light" <?php selected($custom_settings['card_shadow_intensity'], 'light'); ?>>Light</option>
                                    <option value="medium" <?php selected($custom_settings['card_shadow_intensity'], 'medium'); ?>>Medium</option>
                                    <option value="strong" <?php selected($custom_settings['card_shadow_intensity'], 'strong'); ?>>Strong</option>
                                    <option value="dramatic" <?php selected($custom_settings['card_shadow_intensity'], 'dramatic'); ?>>Dramatic</option>
                                </select>
                                <p style="color: #666; font-size: 12px; margin: 5px 0 0 0;"><?php echo esc_html__('Controls the elevation and depth of the card shadow.', 'shopglut'); ?></p>
                            </div>
                        </div>

                        <div class="customization-group" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                            <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Icon Settings', 'shopglut'); ?></h4>

                            <div style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; font-weight: 600; font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="card_show_icon" value="1" <?php checked($custom_settings['card_show_icon'], '1'); ?> style="margin-right: 8px;">
                                    <?php echo esc_html__('Show Icon', 'shopglut'); ?>
                                </label>
                                <p style="color: #666; font-size: 12px; margin: 5px 0 0 0;"><?php echo esc_html__('Display circular icon at the top of the card.', 'shopglut'); ?></p>
                            </div>

                            <div id="card-icon-options" style="<?php echo $custom_settings['card_show_icon'] === '1' ? '' : 'display: none;'; ?>">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Login Icon', 'shopglut'); ?></label>
                                    <select name="card_login_icon" style="width: 100%; padding: 8px; font-size: 14px;">
                                        <option value="👤" <?php selected($custom_settings['card_login_icon'], '👤'); ?>>👤 User</option>
                                        <option value="🔐" <?php selected($custom_settings['card_login_icon'], '🔐'); ?>>🔐 Lock</option>
                                        <option value="🔑" <?php selected($custom_settings['card_login_icon'], '🔑'); ?>>🔑 Key</option>
                                        <option value="✉️" <?php selected($custom_settings['card_login_icon'], '✉️'); ?>>✉️ Mail</option>
                                        <option value="🎯" <?php selected($custom_settings['card_login_icon'], '🎯'); ?>>🎯 Target</option>
                                        <option value="⭐" <?php selected($custom_settings['card_login_icon'], '⭐'); ?>>⭐ Star</option>
                                        <option value="💼" <?php selected($custom_settings['card_login_icon'], '💼'); ?>>💼 Briefcase</option>
                                        <option value="🏠" <?php selected($custom_settings['card_login_icon'], '🏠'); ?>>🏠 Home</option>
                                    </select>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Register Icon', 'shopglut'); ?></label>
                                    <select name="card_register_icon" style="width: 100%; padding: 8px; font-size: 14px;">
                                        <option value="✍️" <?php selected($custom_settings['card_register_icon'], '✍️'); ?>>✍️ Writing</option>
                                        <option value="👋" <?php selected($custom_settings['card_register_icon'], '👋'); ?>>👋 Wave</option>
                                        <option value="🎉" <?php selected($custom_settings['card_register_icon'], '🎉'); ?>>🎉 Party</option>
                                        <option value="🚀" <?php selected($custom_settings['card_register_icon'], '🚀'); ?>>🚀 Rocket</option>
                                        <option value="✨" <?php selected($custom_settings['card_register_icon'], '✨'); ?>>✨ Sparkles</option>
                                        <option value="🌟" <?php selected($custom_settings['card_register_icon'], '🌟'); ?>>🌟 Glowing Star</option>
                                        <option value="📝" <?php selected($custom_settings['card_register_icon'], '📝'); ?>>📝 Memo</option>
                                        <option value="➕" <?php selected($custom_settings['card_register_icon'], '➕'); ?>>➕ Plus</option>
                                    </select>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Icon Size', 'shopglut'); ?></label>
                                    <select name="card_icon_size" style="width: 100%; padding: 8px; font-size: 14px;">
                                        <option value="60px" <?php selected($custom_settings['card_icon_size'], '60px'); ?>>Small (60px)</option>
                                        <option value="70px" <?php selected($custom_settings['card_icon_size'], '70px'); ?>>Medium (70px)</option>
                                        <option value="80px" <?php selected($custom_settings['card_icon_size'], '80px'); ?>>Large (80px)</option>
                                        <option value="90px" <?php selected($custom_settings['card_icon_size'], '90px'); ?>>Extra Large (90px)</option>
                                    </select>
                                </div>

                                <div style="margin-bottom: 0;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;"><?php echo esc_html__('Icon Font Size', 'shopglut'); ?></label>
                                    <select name="card_icon_font_size" style="width: 100%; padding: 8px; font-size: 14px;">
                                        <option value="28px" <?php selected($custom_settings['card_icon_font_size'], '28px'); ?>>Small (28px)</option>
                                        <option value="36px" <?php selected($custom_settings['card_icon_font_size'], '36px'); ?>>Medium (36px)</option>
                                        <option value="42px" <?php selected($custom_settings['card_icon_font_size'], '42px'); ?>>Large (42px)</option>
                                        <option value="48px" <?php selected($custom_settings['card_icon_font_size'], '48px'); ?>>Extra Large (48px)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        </div>

                        <!-- Content Tab (Split Screen Only) -->
                        <?php if (isset($template['layout_type']) && $template['layout_type'] === 'split'): ?>
                        <div class="settings-tab-content" id="content-tab" style="display: none;">
                            <div class="customization-group" style="margin-bottom: 25px;">
                                <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Welcome Panel Content', 'shopglut'); ?></h4>
                                <p style="color: #666; font-size: 13px; margin-bottom: 15px;"><?php echo esc_html__('Customize the branding messages shown in the left panel of split screen layout.', 'shopglut'); ?></p>

                                <h5 style="margin: 20px 0 10px 0; color: #555; font-size: 14px; font-weight: 600;"><?php echo esc_html__('Login Page Welcome', 'shopglut'); ?></h5>
                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Welcome Title', 'shopglut'); ?></label>
                                    <input type="text" name="split_welcome_title" value="<?php echo esc_attr($custom_settings['split_welcome_title']); ?>" style="width: 100%; padding: 8px; font-size: 13px;" placeholder="Welcome Back">
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Welcome Subtitle', 'shopglut'); ?></label>
                                    <input type="text" name="split_welcome_subtitle" value="<?php echo esc_attr($custom_settings['split_welcome_subtitle']); ?>" style="width: 100%; padding: 8px; font-size: 13px;" placeholder="Sign in to continue to your account">
                                </div>

                                <h5 style="margin: 20px 0 10px 0; color: #555; font-size: 14px; font-weight: 600;"><?php echo esc_html__('Register Page Welcome', 'shopglut'); ?></h5>
                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Welcome Title', 'shopglut'); ?></label>
                                    <input type="text" name="split_register_welcome_title" value="<?php echo esc_attr($custom_settings['split_register_welcome_title']); ?>" style="width: 100%; padding: 8px; font-size: 13px;" placeholder="Join Us Today">
                                </div>
                                <div style="margin-bottom: 0;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Welcome Subtitle', 'shopglut'); ?></label>
                                    <input type="text" name="split_register_welcome_subtitle" value="<?php echo esc_attr($custom_settings['split_register_welcome_subtitle']); ?>" style="width: 100%; padding: 8px; font-size: 13px;" placeholder="Create an account to get started">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Login Text Tab Content -->
                        <div class="settings-tab-content" id="login-text-tab" style="display: none;">
                            <div class="customization-group" style="margin-bottom: 25px;">
                                <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Login Form Text', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Login Title', 'shopglut'); ?></label>
                                <input type="text" name="login_title" value="<?php echo esc_attr($custom_settings['login_title']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Username Label', 'shopglut'); ?></label>
                                <input type="text" name="login_username_label" value="<?php echo esc_attr($custom_settings['login_username_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Username Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="login_username_placeholder" value="<?php echo esc_attr($custom_settings['login_username_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Password Label', 'shopglut'); ?></label>
                                <input type="text" name="login_password_label" value="<?php echo esc_attr($custom_settings['login_password_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Password Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="login_password_placeholder" value="<?php echo esc_attr($custom_settings['login_password_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Remember Me Label', 'shopglut'); ?></label>
                                <input type="text" name="login_remember_label" value="<?php echo esc_attr($custom_settings['login_remember_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Button Text', 'shopglut'); ?></label>
                                <input type="text" name="login_button_text" value="<?php echo esc_attr($custom_settings['login_button_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Forgot Password Text', 'shopglut'); ?></label>
                                <input type="text" name="login_forgot_text" value="<?php echo esc_attr($custom_settings['login_forgot_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Register Link Text', 'shopglut'); ?></label>
                                <input type="text" name="login_register_link_text" value="<?php echo esc_attr($custom_settings['login_register_link_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                        </div>
                        </div>

                        <!-- Register Text Tab Content -->
                        <div class="settings-tab-content" id="register-text-tab" style="display: none;">
                            <div class="customization-group" style="margin-bottom: 25px;">
                                <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Register Form Text', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Register Title', 'shopglut'); ?></label>
                                <input type="text" name="register_title" value="<?php echo esc_attr($custom_settings['register_title']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Full Name Label', 'shopglut'); ?></label>
                                <input type="text" name="register_name_label" value="<?php echo esc_attr($custom_settings['register_name_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Full Name Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="register_name_placeholder" value="<?php echo esc_attr($custom_settings['register_name_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Email Label', 'shopglut'); ?></label>
                                <input type="text" name="register_email_label" value="<?php echo esc_attr($custom_settings['register_email_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Email Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="register_email_placeholder" value="<?php echo esc_attr($custom_settings['register_email_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Username Label', 'shopglut'); ?></label>
                                <input type="text" name="register_username_label" value="<?php echo esc_attr($custom_settings['register_username_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Username Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="register_username_placeholder" value="<?php echo esc_attr($custom_settings['register_username_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Password Label', 'shopglut'); ?></label>
                                <input type="text" name="register_password_label" value="<?php echo esc_attr($custom_settings['register_password_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Password Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="register_password_placeholder" value="<?php echo esc_attr($custom_settings['register_password_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Confirm Password Label', 'shopglut'); ?></label>
                                <input type="text" name="register_confirm_password_label" value="<?php echo esc_attr($custom_settings['register_confirm_password_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Confirm Password Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="register_confirm_password_placeholder" value="<?php echo esc_attr($custom_settings['register_confirm_password_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                        <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Button Text', 'shopglut'); ?></label>
                                <input type="text" name="register_button_text" value="<?php echo esc_attr($custom_settings['register_button_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Login Link Text', 'shopglut'); ?></label>
                                <input type="text" name="register_login_link_text" value="<?php echo esc_attr($custom_settings['register_login_link_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                        </div>
                        </div>

                        <!-- Forgot Password Text Tab Content -->
                        <div class="settings-tab-content" id="forgot-text-tab" style="display: none;">
                            <div class="customization-group" style="margin-bottom: 25px;">
                                <h4 style="margin-top: 0; color: #2271b1;"><?php echo esc_html__('Forgot Password Form Text', 'shopglut'); ?></h4>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Forgot Password Title', 'shopglut'); ?></label>
                                <input type="text" name="forgot_title" value="<?php echo esc_attr($custom_settings['forgot_title']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Description Text', 'shopglut'); ?></label>
                                <input type="text" name="forgot_description" value="<?php echo esc_attr($custom_settings['forgot_description']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Email Label', 'shopglut'); ?></label>
                                <input type="text" name="forgot_email_label" value="<?php echo esc_attr($custom_settings['forgot_email_label']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Email Placeholder', 'shopglut'); ?></label>
                                <input type="text" name="forgot_email_placeholder" value="<?php echo esc_attr($custom_settings['forgot_email_placeholder']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Button Text', 'shopglut'); ?></label>
                                <input type="text" name="forgot_button_text" value="<?php echo esc_attr($custom_settings['forgot_button_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                            <div style="margin-bottom: 0;">
                                <label style="display: block; margin-bottom: 3px; font-weight: 600; font-size: 12px;"><?php echo esc_html__('Login Link Text', 'shopglut'); ?></label>
                                <input type="text" name="forgot_login_link_text" value="<?php echo esc_attr($custom_settings['forgot_login_link_text']); ?>" style="width: 100%; padding: 6px; font-size: 13px;">
                            </div>
                        </div>
                        </div>
                    </form>
                </div>

                <!-- Live Preview -->
                <div class="live-preview" style="background: #fff; padding: 25px; border: 1px solid #ddd; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0;"><?php echo esc_html__('Live Preview', 'shopglut'); ?></h3>
                        <div class="preview-tabs" style="display: flex !important; gap: 0 !important; border-bottom: 2px solid #ddd !important; background: #f9f9f9 !important; border-radius: 6px 6px 0 0 !important; overflow: hidden !important; margin: 0 !important; padding: 0 !important;">
                            <button type="button" class="preview-tab active" data-tab="login" style="padding: 12px 20px !important; border: none !important; background: #fff !important; border-bottom: 3px solid #007cba !important; color: #007cba !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.2s ease !important; white-space: nowrap !important; position: relative !important; top: 0 !important; margin: 0 !important; flex: 1 !important; text-align: center !important; border-radius: 0 !important; box-shadow: none !important; text-transform: none !important; line-height: 1.4 !important; min-height: 44px !important; height: auto !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; float: none !important; display: block !important; width: auto !important; opacity: 1 !important; visibility: visible !important;">
                                <?php echo esc_html__('Login Page', 'shopglut'); ?>
                            </button>
                            <button type="button" class="preview-tab" data-tab="register" style="padding: 12px 20px !important; border: none !important; background: #f9f9f9 !important; border-bottom: 3px solid transparent !important; color: #666 !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.2s ease !important; white-space: nowrap !important; position: relative !important; top: 0 !important; margin: 0 !important; flex: 1 !important; text-align: center !important; border-radius: 0 !important; box-shadow: none !important; text-transform: none !important; line-height: 1.4 !important; min-height: 44px !important; height: auto !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; float: none !important; display: block !important; width: auto !important; opacity: 1 !important; visibility: visible !important;">
                                <?php echo esc_html__('Register Page', 'shopglut'); ?>
                            </button>
                        </div>
                    </div>
                    <div class="template-preview-section">
                        <div id="preview-container" class="template-preview-content">
                            <div id="login-preview" class="preview-content active">
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-only preview with controlled input
                                echo $this->generateLoginPreview($template_id, $custom_settings);
                                ?>
                            </div>
                            <div id="register-preview" class="preview-content" style="display: none;">
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-only preview with controlled input
                                echo $this->generateRegisterPreview($template_id, $custom_settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fixed Action Buttons -->
            <div style="position: fixed; bottom: 5px; left: 8px; z-index: 1000; background: #ffffff; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); border: 1px solid #ddd;">
                <div style="display: flex; gap: 12px;">
                    <button type="button" class="button button-primary button-large" onclick="saveCustomization()"
                        style="padding: 12px 24px; font-size: 14px; font-weight: 600; border-radius: 4px; background: #0073aa !important; color: #fff !important; border: 1px solid #0073aa !important; transition: all 0.3s ease; cursor: pointer;">
                        <?php echo esc_html__('Save Customization', 'shopglut'); ?>
                    </button>
                    <button type="button" class="button button-secondary button-large" onclick="resetToDefault()"
                        style="padding: 12px 24px; font-size: 14px; font-weight: 600; border-radius: 4px; background: #f0f0f1 !important; color: #2c3338 !important; border: 1px solid #8c8f94 !important; transition: all 0.3s ease; cursor: pointer;">
                        <?php echo esc_html__('Reset to Default', 'shopglut'); ?>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function saveCustomization() {
                var formData = new FormData(document.getElementById('template-customization-form'));
                formData.append('action', 'shopglut_customize_template');
                formData.append('template_id', '<?php echo esc_js($template_id); ?>');
                formData.append('nonce', '<?php echo esc_attr( wp_create_nonce('shopglut_login_register_nonce') ); ?>');
                
                jQuery.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            var notification = jQuery('<div style="position: fixed; bottom: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px 25px; border-radius: 6px; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 500;">✓ Customization saved!</div>');
                            jQuery('body').append(notification);
                            setTimeout(function() {
                                notification.fadeOut(function() {
                                    notification.remove();
                                });
                            }, 3000);
                            updatePreview();
                        } else {
                            alert(response.data.message || 'Error saving customization');
                        }
                    }
                });
            }

            function resetToDefault() {
                if (confirm('<?php echo esc_js(__('Reset all customizations to default? This cannot be undone.', 'shopglut')); ?>')) {
                    location.reload();
                }
            }

            function updatePreview() {
                // Update preview with current form values
                var formData = new FormData(document.getElementById('template-customization-form'));
                // This would typically update the preview in real-time
                // For now, we'll just reload the preview
                location.reload();
            
                    jQuery(document).ready(function($) {
                        // Settings tab switching functionality
                        $('.settings-tab').on('click', function() {
                            var tab = $(this).data('tab');

                            // Update tab buttons with consistent styling
                            $('.settings-tab').removeClass('active');
                            $(this).addClass('active');

                            // Update inline styles for visual feedback
                            $('.settings-tab').each(function() {
                                if ($(this).hasClass('active')) {
                                    // Active tab styling
                                    $(this).attr('style', 'padding: 15px 25px; border: none; background: #fff; border-bottom: 3px solid #0073aa; color: #0073aa; font-weight: 600; cursor: pointer; margin-right: 0; font-size: 14px; text-align: center;');
                                } else {
                                    // Inactive tab styling
                                    $(this).attr('style', 'padding: 15px 25px; border: none; background: transparent; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; margin-right: 0; font-size: 14px; text-align: center;');
                                }
                            });

                            // Show/hide tab content
                            $('.settings-tab-content').hide();
                            $('#' + tab + '-tab').show();
                        });

                        // Add hover effects for settings tabs
                        $('.settings-tab').on('mouseenter', function() {
                            if (!$(this).hasClass('active')) {
                                $(this).css({
                                    'background': 'rgba(0, 115, 170, 0.1)',
                                    'color': '#0073aa'
                                });
                            }
                        }).on('mouseleave', function() {
                            if (!$(this).hasClass('active')) {
                                $(this).css({
                                    'background': 'transparent',
                                    'color': '#666'
                                });
                            }
                        });

                        // Icon show/hide toggle for Floating Card
                        $('input[name="card_show_icon"]').on('change', function() {
                            if ($(this).is(':checked')) {
                                $('#card-icon-options').slideDown(200);
                            } else {
                                $('#card-icon-options').slideUp(200);
                            }
                        });

                        // Preview tab switching functionality
                        $('.preview-tab').on('click', function() {
                            var tab = $(this).data('tab');

                            // Update tab buttons with consistent styling and inline styles
                            $('.preview-tab').removeClass('active');
                            $(this).addClass('active');

                            // Update inline styles for visual feedback
                            $('.preview-tab').each(function() {
                                if ($(this).hasClass('active')) {
                                    // Active tab styling
                                    $(this).attr('style', 'padding: 12px 20px !important; border: none !important; background: #fff !important; border-bottom: 3px solid #007cba !important; color: #007cba !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.2s ease !important; white-space: nowrap !important; position: relative !important; top: 0 !important; margin: 0 !important; flex: 1 !important; text-align: center !important; border-radius: 0 !important; box-shadow: none !important; text-transform: none !important; line-height: 1.4 !important; min-height: 44px !important; height: auto !important; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif !important; float: none !important; display: block !important; width: auto !important; opacity: 1 !important; visibility: visible !important;');
                                } else {
                                    // Inactive tab styling
                                    $(this).attr('style', 'padding: 12px 20px !important; border: none !important; background: #f9f9f9 !important; border-bottom: 3px solid transparent !important; color: #666 !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.2s ease !important; white-space: nowrap !important; position: relative !important; top: 0 !important; margin: 0 !important; flex: 1 !important; text-align: center !important; border-radius: 0 !important; box-shadow: none !important; text-transform: none !important; line-height: 1.4 !important; min-height: 44px !important; height: auto !important; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif !important; float: none !important; display: block !important; width: auto !important; opacity: 1 !important; visibility: visible !important;');
                                }
                            });

                            // Show/hide preview content with proper class management
                            $('.preview-content').removeClass('active').hide();
                            $('#' + tab + '-preview').addClass('active').show();
                        });

                        $('#template-customization-form input, #template-customization-form select').on('change', function() {
                            // This would update preview in real-time
                            // For demo purposes, we'll just add a visual indication
                            $('#preview-container').css('opacity', '0.7');
                            setTimeout(function() {
                                $('#preview-container').css('opacity', '1');
                            }, 300);
                        });
                    });
           
            }
         </script>
        <?php

    }
    

    private function getPrebuiltTemplates() {
        // Free templates (1 available in free version) - Content-oriented designs
        $free_templates = array(
            'centered' => array(
                'name' => 'Centered Form',
                'description' => 'Traditional centered layout with a clean form card. Perfect for focus and simplicity with minimal distractions.',
                'category' => 'Classic',
                'primary_color' => '#007cba',
                'secondary_color' => '#f0f0f1',
                'background_color' => '#ffffff',
                'layout_type' => 'centered',
                'is_pro' => false
            ),
            // 'split' => array(
            //     'name' => 'Split Screen',
            //     'description' => 'Two-column layout with form on one side and welcome message/branding space on the other. Ideal for brand presentation.',
            //     'category' => 'Modern',
            //     'primary_color' => '#667eea',
            //     'secondary_color' => '#764ba2',
            //     'background_color' => '#f8f9fa',
            //     'layout_type' => 'split',
            //     'is_pro' => false
            // ),
            // 'card' => array(
            //     'name' => 'Floating Card',
            //     'description' => 'Elegant floating card design with shadow effects. Form appears elevated above background for modern appeal.',
            //     'category' => 'Elegant',
            //     'primary_color' => '#2c3e50',
            //     'secondary_color' => '#3498db',
            //     'background_color' => '#ecf0f1',
            //     'layout_type' => 'card',
            //     'is_pro' => false
            // )
        );

        /**
         * Filter to add Pro templates
         *
         * Pro add-on can hook into this filter to add additional templates
         *
         * @param array $free_templates Array of template configurations
         * @return array Modified array with Pro templates added
         */
        $all_templates = apply_filters('shopglut_login_register_templates', $free_templates);

        // Force centered template to be free regardless of filter modifications
        if (isset($all_templates['centered'])) {
            $all_templates['centered']['is_pro'] = false;
        }

        return $all_templates;
    }

    private function getTemplate($template_id) {
        $templates = $this->getPrebuiltTemplates();
        return isset($templates[$template_id]) ? $templates[$template_id] : null;
    }

    private function getSettings() {
        $defaults = array(
            'override_login' => 0,
            'selected_template' => '',
            'login_redirect_type' => 'default',
            'login_redirect_url' => '',
            'register_redirect_type' => 'default',
            'register_redirect_url' => '',
            'hide_admin_bar' => 0,
            'enable_recaptcha' => 0,
            'disable_registration' => 0
        );

        return wp_parse_args(get_option('shopglut_login_register_settings', array()), $defaults);
    }

    private function getTemplateCustomSettings($template_id) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        $defaults = array(
            // Colors
            'primary_color' => $template['primary_color'],
            'secondary_color' => $template['secondary_color'],
            'background_color' => $template['background_color'],
            'text_color' => '#333333',
            'input_border_color' => '#ddd',

            // Typography
            'font_family' => 'inherit',
            'font_size' => '16px',
            'heading_font_size' => '24px',

            // Layout
            'form_width' => '400px',
            'border_radius' => '8px',
            'input_padding' => '12px',
            'button_padding' => '15px',

            // Content-specific settings for Split Screen layout
            'split_welcome_title' => 'Welcome Back',
            'split_welcome_subtitle' => 'Sign in to continue to your account',
            'split_register_welcome_title' => 'Join Us Today',
            'split_register_welcome_subtitle' => 'Create an account to get started',

            // Appearance-specific settings for Floating Card layout
            'card_show_icon' => '1',
            'card_login_icon' => '👤',
            'card_register_icon' => '✍️',
            'card_icon_size' => '70px',
            'card_icon_font_size' => '36px',
            'card_border_top_color' => $template['primary_color'],
            'card_border_top_width' => '4px',
            'card_shadow_intensity' => 'medium',

            // Login Form Labels
            'login_title' => 'Login to Your Account',
            'login_username_label' => 'Username or Email',
            'login_username_placeholder' => 'Enter your username or email',
            'login_password_label' => 'Password',
            'login_password_placeholder' => 'Enter your password',
            'login_remember_label' => 'Remember me',
            'login_button_text' => 'Log In',
            'login_forgot_text' => 'Forgot your password?',
            'login_register_link_text' => "Don't have an account? Sign up",

            // Register Form Labels
            'register_title' => 'Create Your Account',
            'register_name_label' => 'Full Name',
            'register_name_placeholder' => 'Enter your full name',
            'register_email_label' => 'Email Address',
            'register_email_placeholder' => 'Enter your email address',
            'register_username_label' => 'Username',
            'register_username_placeholder' => 'Choose a username',
            'register_password_label' => 'Password',
            'register_password_placeholder' => 'Create a strong password',
            'register_confirm_password_label' => 'Confirm Password',
            'register_confirm_password_placeholder' => 'Confirm your password',
            'register_button_text' => 'Create Account',
            'register_login_link_text' => 'Already have an account? Log in',

            // Forgot Password Form Labels
            'forgot_title' => 'Reset Your Password',
            'forgot_description' => 'Enter your email address and we\'ll send you a link to reset your password.',
            'forgot_email_label' => 'Email Address',
            'forgot_email_placeholder' => 'Enter your email address',
            'forgot_button_text' => 'Get Reset Link',
            'forgot_login_link_text' => 'Remember your password? Log in',

            // Form Elements
            'input_field_style' => 'minimal',
            'input_focus_color' => '#007cba',
            'show_input_icons' => 'yes',
            'label_font_weight' => '600',

            // Button Styling
            'button_style' => 'filled',
            'button_hover_effect' => 'lift',
            'button_border_radius' => '8px',
            'button_text_transform' => 'none',

            // Logo/Branding
            'logo_style' => 'circle',
            'logo_size' => '80px',
            'logo_text' => 'SG',
            'show_subtitle' => 'yes',

            // Advanced Typography
            'line_height' => '1.5',
            'letter_spacing' => '0',
            'heading_weight' => '600',

            // Visual Effects
            'form_shadow' => 'medium',
            'animation_speed' => 'normal',
            'enable_animations' => 'yes'
        );

        $saved_settings = get_option('shopglut_template_custom_' . $template_id, array());
        return wp_parse_args($saved_settings, $defaults);
    }

    private function generateActualLoginForm($template_id, $custom_settings) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET/POST parameter for redirect URL
        $redirect_to = isset($_REQUEST['redirect_to']) ? esc_url_raw(wp_unslash($_REQUEST['redirect_to'])) : admin_url();
        $login_url = site_url('wp-login.php', 'login_post');
        $register_url = get_option('users_can_register') ? wp_registration_url() : '';
        $lostpassword_url = site_url('wp-login.php?action=lostpassword', 'login');

        // Generate the actual form based on layout type
        if ($layout_type === 'centered') {
            return $this->generateCenteredLoginForm($custom_settings, $redirect_to, $login_url, $register_url, $lostpassword_url);
        } elseif ($layout_type === 'split') {
            return $this->generateSplitLoginForm($custom_settings, $redirect_to, $login_url, $register_url, $lostpassword_url);
        } elseif ($layout_type === 'card') {
            return $this->generateCardLoginForm($custom_settings, $redirect_to, $login_url, $register_url, $lostpassword_url);
        }

        return '';
    }

    private function generateActualRegisterForm($template_id, $custom_settings) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        $login_url = wp_login_url();
        $register_url = site_url('wp-login.php?action=register', 'login_post');

        // Generate the actual form based on layout type
        if ($layout_type === 'centered') {
            return $this->generateCenteredRegisterForm($custom_settings, $login_url, $register_url);
        } elseif ($layout_type === 'split') {
            return $this->generateSplitRegisterForm($custom_settings, $login_url, $register_url);
        } elseif ($layout_type === 'card') {
            return $this->generateCardRegisterForm($custom_settings, $login_url, $register_url);
        }

        return '';
    }

    private function generateCenteredLoginForm($settings, $redirect_to, $login_url, $register_url, $lostpassword_url) {
        // Apply advanced typography settings
        $line_height = isset($settings['line_height']) ? $settings['line_height'] : '1.5';
        $letter_spacing = isset($settings['letter_spacing']) ? $settings['letter_spacing'] : '0';
        $heading_weight = isset($settings['heading_weight']) ? $settings['heading_weight'] : '600';
        $label_font_weight = isset($settings['label_font_weight']) ? $settings['label_font_weight'] : '600';

        // Apply form shadow
        $form_shadow_style = '';
        if (isset($settings['form_shadow'])) {
            switch ($settings['form_shadow']) {
                case 'none':
                    $form_shadow_style = 'box-shadow: none;';
                    break;
                case 'light':
                    $form_shadow_style = 'box-shadow: 0 2px 8px rgba(0,0,0,0.08);';
                    break;
                case 'medium':
                    $form_shadow_style = 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
                    break;
                case 'strong':
                    $form_shadow_style = 'box-shadow: 0 8px 30px rgba(0,0,0,0.15);';
                    break;
            }
        }

        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; background: <?php echo esc_attr($settings['background_color']); ?>; padding: 30px; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-family: <?php echo esc_attr($settings['font_family']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; color: <?php echo esc_attr($settings['text_color']); ?>; <?php echo esc_attr($form_shadow_style); ?>  line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;">
            <div style="text-align: center; margin-bottom: 30px;">
                <?php
                // Generate logo based on style
                $logo_html = $this->generateLogoHtml($settings);
                echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates escaped HTML
                ?>
                <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 10px 0; font-weight: <?php echo esc_attr($heading_weight); ?>; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['login_title']); ?></h2>
                <?php
                // Show subtitle if enabled
                if (isset($settings['show_subtitle']) && $settings['show_subtitle'] === 'yes') {
                    echo '<p style="color: #666; margin: 0; font-size: 14px; line-height: ' . esc_attr($line_height) . '; letter-spacing: ' . esc_attr($letter_spacing) . ';">Welcome back! Please login to your account.</p>';
                }
                ?>
            </div>

            <form name="loginform" id="loginform" action="<?php echo esc_url($login_url); ?>" method="post">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: <?php echo esc_attr($label_font_weight); ?>; color: #555; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['login_username_label']); ?></label>
                    <div style="position: relative;">
                        <?php
                        // Show input icon if enabled
                        $show_icons = isset($settings['show_input_icons']) && $settings['show_input_icons'] === 'yes';
                        if ($show_icons) {
                            echo '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">👤</span>';
                        }
                        ?>
                        <input type="text" name="log" id="user_login" placeholder="<?php echo esc_attr($settings['login_username_placeholder']); ?>" style="<?php echo esc_attr($this->getInputFieldStyle($settings)); ?>" onfocus="this.style.borderColor='<?php echo esc_attr(isset($settings['input_focus_color']) ? $settings['input_focus_color'] : $settings['primary_color']); ?>'" onblur="this.style.borderColor='<?php echo esc_attr($settings['input_border_color']); ?>'" required>
                    </div>
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: <?php echo esc_attr($label_font_weight); ?>; color: #555; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['login_password_label']); ?></label>
                    <div style="position: relative;">
                        <?php
                        if ($show_icons) {
                            echo '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">🔒</span>';
                        }
                        ?>
                        <input type="password" name="pwd" id="user_pass" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>" style="<?php echo esc_attr($this->getInputFieldStyle($settings)); ?>" onfocus="this.style.borderColor='<?php echo esc_attr(isset($settings['input_focus_color']) ? $settings['input_focus_color'] : $settings['primary_color']); ?>'" onblur="this.style.borderColor='<?php echo esc_attr($settings['input_border_color']); ?>'" required>
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; font-size: 14px; color: #666; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;">
                        <input type="checkbox" name="rememberme" value="forever" style="margin-right: 8px;"> <?php echo esc_html($settings['login_remember_label']); ?>
                    </label>
                </div>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                <button type="submit" name="wp-submit" style="<?php echo esc_attr($this->getButtonStyle($settings)); ?>">
                    <?php echo esc_html($settings['login_button_text']); ?>
                </button>
                <div style="text-align: center; margin-top: 20px;">
                    <p style="margin: 0; color: #666; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><a href="<?php echo esc_url($lostpassword_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none;"><?php echo esc_html($settings['login_forgot_text']); ?></a></p>
                    <?php if ($register_url): ?>
                    <p style="margin: 10px 0 0; color: #666; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><a href="<?php echo esc_url($register_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['login_register_link_text']); ?></a></p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateSplitLoginForm($settings, $redirect_to, $login_url, $register_url, $lostpassword_url) {
        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; font-family: <?php echo esc_attr($settings['font_family']); ?>; display: grid; grid-template-columns: 1fr 1fr; min-height: 500px; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <!-- Left Side - Branding -->
            <div style="background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?> 0%, <?php echo esc_attr($settings['secondary_color']); ?> 100%); color: white; padding: 40px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold;">
                    S
                </div>
                <h2 style="margin: 0 0 15px 0; font-size: 28px; font-weight: 700;"><?php echo esc_html($settings['split_welcome_title']); ?></h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.9;"><?php echo esc_html__('Welcome back! Sign in to access your account.', 'shopglut'); ?></p>
                <div style="margin-top: 30px;">
                    <div style="width: 60px; height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px; margin: 0 auto;"></div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div style="background: <?php echo esc_attr($settings['background_color']); ?>; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
                <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 30px 0; font-weight: 600; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>;"><?php echo esc_html($settings['login_title']); ?></h2>

                <form name="loginform" id="loginform" action="<?php echo esc_url($login_url); ?>" method="post">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px;"><?php echo esc_html($settings['login_username_label']); ?></label>
                        <input type="text" name="log" id="user_login" placeholder="<?php echo esc_attr($settings['login_username_placeholder']); ?>" value="john.doe@example.com" style="width: 100%; padding: <?php echo esc_attr($settings['input_padding']); ?>; border: 2px solid <?php echo esc_attr($settings['input_border_color']); ?>; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box;" required>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px;"><?php echo esc_html($settings['login_password_label']); ?></label>
                        <input type="password" name="pwd" id="user_pass" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>" value="••••••••" style="width: 100%; padding: <?php echo esc_attr($settings['input_padding']); ?>; border: 2px solid <?php echo esc_attr($settings['input_border_color']); ?>; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box;" required>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: flex; align-items: center; font-size: 14px; color: #666;">
                            <input type="checkbox" name="rememberme" value="forever" style="margin-right: 8px;" checked> <?php echo esc_html($settings['login_remember_label']); ?>
                        </label>
                    </div>
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                    <button type="submit" name="wp-submit" style="width: 100%; padding: <?php echo esc_attr($settings['button_padding']); ?>; background: <?php echo esc_attr($settings['primary_color']); ?>; color: white; border: none; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; font-weight: 600; cursor: pointer; margin-bottom: 20px;">
                        <?php echo esc_html($settings['login_button_text']); ?>
                    </button>
                    <div style="text-align: center;">
                        <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><a href="<?php echo esc_url($lostpassword_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none;"><?php echo esc_html($settings['login_forgot_text']); ?></a></p>
                        <?php if ($register_url): ?>
                        <p style="margin: 0; color: #666; font-size: 14px;"><a href="<?php echo esc_url($register_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['login_register_link_text']); ?></a></p>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateCardLoginForm($settings, $redirect_to, $login_url, $register_url, $lostpassword_url) {
        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; font-family: <?php echo esc_attr($settings['font_family']); ?>;">
            <!-- Card Container -->
            <div style="background: <?php echo esc_attr($settings['background_color']); ?>; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 8px 25px rgba(0,0,0,0.1); overflow: hidden; position: relative;">
                <!-- Top Accent -->
                <div style="height: 8px; background: linear-gradient(90deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>);"></div>

                <div style="padding: 40px;">
                    <!-- Logo Section -->
                    <div style="text-align: center; margin-bottom: 35px; position: relative;">
                        <div style="width: 90px; height: 90px; background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: bold; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative;">
                            <div style="position: absolute; top: -5px; right: -5px; width: 20px; height: 20px; background: #4CAF50; border-radius: 50%; border: 3px solid white;"></div>
                            F
                        </div>
                        <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 10px 0; font-weight: 700; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>;"><?php echo esc_html($settings['login_title']); ?></h2>
                        <p style="color: #666; margin: 0; font-size: 15px;">Welcome back! Please login to continue</p>
                    </div>

                    <form name="loginform" id="loginform" action="<?php echo esc_url($login_url); ?>" method="post">
                        <div style="margin-bottom: 22px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px;"><?php echo esc_html($settings['login_username_label']); ?></label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 18px;">👤</span>
                                <input type="text" name="log" id="user_login" placeholder="<?php echo esc_attr($settings['login_username_placeholder']); ?>" value="john.doe@example.com" style="width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #e1e5e9; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box; transition: all 0.3s ease;" onmouseover="this.style.borderColor='<?php echo esc_attr($settings['primary_color']); ?>'" onmouseout="this.style.borderColor='#e1e5e9'" required>
                            </div>
                        </div>
                        <div style="margin-bottom: 28px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px;"><?php echo esc_html($settings['login_password_label']); ?></label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 18px;">🔒</span>
                                <input type="password" name="pwd" id="user_pass" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>" value="••••••••" style="width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #e1e5e9; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box; transition: all 0.3s ease;" onmouseover="this.style.borderColor='<?php echo esc_attr($settings['primary_color']); ?>'" onmouseout="this.style.borderColor='#e1e5e9'" required>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
                            <label style="display: flex; align-items: center; font-size: 14px; color: #666;">
                                <input type="checkbox" name="rememberme" value="forever" style="margin-right: 8px; width: 16px; height: 16px;" checked> <?php echo esc_html($settings['login_remember_label']); ?>
                            </label>
                            <a href="<?php echo esc_url($lostpassword_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-size: 14px; font-weight: 500;"><?php echo esc_html($settings['login_forgot_text']); ?></a>
                        </div>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                        <button type="submit" name="wp-submit" style="width: 100%; padding: <?php echo esc_attr($settings['button_padding']); ?>; background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>); color: white; border: none; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; font-weight: 600; cursor: pointer; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <?php echo esc_html($settings['login_button_text']); ?>
                        </button>
                        <?php if ($register_url): ?>
                        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e1e5e9;">
                            <p style="margin: 0; color: #666; font-size: 14px;">
                                <?php echo esc_html__('Don\'t have an account?', 'shopglut'); ?> <a href="<?php echo esc_url($register_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['login_register_link_text']); ?></a>
                            </p>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateCenteredRegisterForm($settings, $login_url, $register_url) {
        // Apply advanced typography settings
        $line_height = isset($settings['line_height']) ? $settings['line_height'] : '1.5';
        $letter_spacing = isset($settings['letter_spacing']) ? $settings['letter_spacing'] : '0';
        $heading_weight = isset($settings['heading_weight']) ? $settings['heading_weight'] : '600';
        $label_font_weight = isset($settings['label_font_weight']) ? $settings['label_font_weight'] : '600';

        // Apply form shadow
        $form_shadow_style = '';
        if (isset($settings['form_shadow'])) {
            switch ($settings['form_shadow']) {
                case 'none':
                    $form_shadow_style = 'box-shadow: none;';
                    break;
                case 'light':
                    $form_shadow_style = 'box-shadow: 0 2px 8px rgba(0,0,0,0.08);';
                    break;
                case 'medium':
                    $form_shadow_style = 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
                    break;
                case 'strong':
                    $form_shadow_style = 'box-shadow: 0 8px 30px rgba(0,0,0,0.15);';
                    break;
            }
        }

        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; background: <?php echo esc_attr($settings['background_color']); ?>; padding: 30px; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-family: <?php echo esc_attr($settings['font_family']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; color: <?php echo esc_attr($settings['text_color']); ?>; <?php echo esc_attr($form_shadow_style); ?>  line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;">
            <div style="text-align: center; margin-bottom: 30px;">
                <?php
                // Generate logo based on style
                $logo_html = $this->generateLogoHtml($settings);
                echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates escaped HTML
                ?>
                <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 10px 0; font-weight: <?php echo esc_attr($heading_weight); ?>; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['register_title']); ?></h2>
                <?php
                // Show subtitle if enabled
                if (isset($settings['show_subtitle']) && $settings['show_subtitle'] === 'yes') {
                    echo '<p style="color: #666; margin: 0; font-size: 14px; line-height: ' . esc_attr($line_height) . '; letter-spacing: ' . esc_attr($letter_spacing) . ';">Create your account to get started.</p>';
                }
                ?>
            </div>

            <form name="registerform" id="registerform" action="<?php echo esc_url($register_url); ?>" method="post">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: <?php echo esc_attr($label_font_weight); ?>; color: #555; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['register_username_label']); ?></label>
                    <div style="position: relative;">
                        <?php
                        // Show input icon if enabled
                        $show_icons = isset($settings['show_input_icons']) && $settings['show_input_icons'] === 'yes';
                        if ($show_icons) {
                            echo '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">👤</span>';
                        }
                        ?>
                        <input type="text" name="user_login" id="user_login" placeholder="<?php echo esc_attr($settings['register_username_placeholder']); ?>" style="<?php echo esc_attr($this->getInputFieldStyle($settings)); ?>" onfocus="this.style.borderColor='<?php echo esc_attr(isset($settings['input_focus_color']) ? $settings['input_focus_color'] : $settings['primary_color']); ?>'" onblur="this.style.borderColor='<?php echo esc_attr($settings['input_border_color']); ?>'" required>
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: <?php echo esc_attr($label_font_weight); ?>; color: #555; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['register_email_label']); ?></label>
                    <div style="position: relative;">
                        <?php
                        if ($show_icons) {
                            echo '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">✉️</span>';
                        }
                        ?>
                        <input type="email" name="user_email" id="user_email" placeholder="<?php echo esc_attr($settings['register_email_placeholder']); ?>" style="<?php echo esc_attr($this->getInputFieldStyle($settings)); ?>" onfocus="this.style.borderColor='<?php echo esc_attr(isset($settings['input_focus_color']) ? $settings['input_focus_color'] : $settings['primary_color']); ?>'" onblur="this.style.borderColor='<?php echo esc_attr($settings['input_border_color']); ?>'" required>
                    </div>
                </div>
                <p id="reg_passmail" style="font-size: 13px; color: #666; margin-bottom: 20px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;">
                    <?php echo esc_html__('Registration confirmation will be emailed to you.', 'shopglut'); ?>
                </p>
                <button type="submit" name="wp-submit" id="wp-submit" style="<?php echo esc_attr($this->getButtonStyle($settings)); ?>">
                    <?php echo esc_html($settings['register_button_text']); ?>
                </button>
                <div style="text-align: center; margin-top: 20px;">
                    <p style="margin: 0; color: #666; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><a href="<?php echo esc_url($login_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['register_login_link_text']); ?></a></p>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateForgotPasswordForm($settings, $login_url) {
        // Apply advanced typography settings
        $line_height = isset($settings['line_height']) ? $settings['line_height'] : '1.5';
        $letter_spacing = isset($settings['letter_spacing']) ? $settings['letter_spacing'] : '0';
        $heading_weight = isset($settings['heading_weight']) ? $settings['heading_weight'] : '600';
        $label_font_weight = isset($settings['label_font_weight']) ? $settings['label_font_weight'] : '600';

        // Apply form shadow
        $form_shadow_style = '';
        if (isset($settings['form_shadow'])) {
            switch ($settings['form_shadow']) {
                case 'none':
                    $form_shadow_style = 'box-shadow: none;';
                    break;
                case 'light':
                    $form_shadow_style = 'box-shadow: 0 2px 8px rgba(0,0,0,0.08);';
                    break;
                case 'medium':
                    $form_shadow_style = 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
                    break;
                case 'strong':
                    $form_shadow_style = 'box-shadow: 0 8px 30px rgba(0,0,0,0.15);';
                    break;
            }
        }

        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; background: <?php echo esc_attr($settings['background_color']); ?>; padding: 30px; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-family: <?php echo esc_attr($settings['font_family']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; color: <?php echo esc_attr($settings['text_color']); ?>; <?php echo esc_attr($form_shadow_style); ?>  line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;">
            <div style="text-align: center; margin-bottom: 30px;">
                <?php
                // Generate logo based on style
                $logo_html = $this->generateLogoHtml($settings);
                echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates escaped HTML
                ?>
                <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 10px 0; font-weight: <?php echo esc_attr($heading_weight); ?>; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['forgot_title']); ?></h2>
                <p style="color: #666; margin: 0; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['forgot_description']); ?></p>
            </div>

            <form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" method="post">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: <?php echo esc_attr($label_font_weight); ?>; color: #555; font-size: 14px; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><?php echo esc_html($settings['forgot_email_label']); ?></label>
                    <div style="position: relative;">
                        <?php
                        // Show input icon if enabled
                        $show_icons = isset($settings['show_input_icons']) && $settings['show_input_icons'] === 'yes';
                        if ($show_icons) {
                            echo '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">🔑</span>';
                        }
                        ?>
                        <input type="email" name="user_login" id="user_login" placeholder="<?php echo esc_attr($settings['forgot_email_placeholder']); ?>" style="<?php echo esc_attr($this->getInputFieldStyle($settings)); ?>" onfocus="this.style.borderColor='<?php echo esc_attr(isset($settings['input_focus_color']) ? $settings['input_focus_color'] : $settings['primary_color']); ?>'" onblur="this.style.borderColor='<?php echo esc_attr($settings['input_border_color']); ?>'" required>
                    </div>
                </div>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($login_url); ?>">
                <button type="submit" name="wp-submit" id="wp-submit" style="<?php echo esc_attr($this->getButtonStyle($settings)); ?>">
                    <?php echo esc_html($settings['forgot_button_text']); ?>
                </button>
                <div style="text-align: center; margin-top: 20px;">
                    <p style="margin: 0; color: #666; line-height: <?php echo esc_attr($line_height); ?>; letter-spacing: <?php echo esc_attr($letter_spacing); ?>;"><a href="<?php echo esc_url($login_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['forgot_login_link_text']); ?></a></p>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateSplitRegisterForm($settings, $login_url, $register_url) {
        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; font-family: <?php echo esc_attr($settings['font_family']); ?>; display: grid; grid-template-columns: 1fr 1fr; min-height: 500px; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <!-- Left Side - Branding -->
            <div style="background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?> 0%, <?php echo esc_attr($settings['secondary_color']); ?> 100%); color: white; padding: 40px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold;">
                    R
                </div>
                <h2 style="margin: 0 0 15px 0; font-size: 28px; font-weight: 700;"><?php echo esc_html($settings['register_title']); ?></h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.9;"><?php echo esc_html__('Join us today! Create your account to get started.', 'shopglut'); ?></p>
                <div style="margin-top: 30px;">
                    <div style="width: 60px; height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px; margin: 0 auto;"></div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div style="background: <?php echo esc_attr($settings['background_color']); ?>; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
                <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 30px 0; font-weight: 600; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>;"><?php echo esc_html($settings['register_title']); ?></h2>

                <form name="registerform" id="registerform" action="<?php echo esc_url($register_url); ?>" method="post">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px;"><?php echo esc_html($settings['register_name_label']); ?></label>
                        <input type="text" name="user_login" id="user_login" placeholder="<?php echo esc_attr($settings['register_name_placeholder']); ?>" style="width: 100%; padding: <?php echo esc_attr($settings['input_padding']); ?>; border: 2px solid <?php echo esc_attr($settings['input_border_color']); ?>; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box;" required>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px;"><?php echo esc_html($settings['register_email_label']); ?></label>
                        <input type="email" name="user_email" id="user_email" placeholder="<?php echo esc_attr($settings['register_email_placeholder']); ?>" style="width: 100%; padding: <?php echo esc_attr($settings['input_padding']); ?>; border: 2px solid <?php echo esc_attr($settings['input_border_color']); ?>; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box;" required>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: flex; align-items: flex-start; font-size: 14px; color: #666;">
                            <input type="checkbox" style="margin-right: 8px; margin-top: 2px;" required>
                            <span><?php echo esc_html($settings['register_terms_label']); ?></span>
                        </label>
                    </div>
                    <p id="reg_passmail" style="font-size: 13px; color: #666; margin-bottom: 20px;">
                        <?php echo esc_html__('Registration confirmation will be emailed to you.', 'shopglut'); ?>
                    </p>
                    <button type="submit" name="wp-submit" id="wp-submit" style="width: 100%; padding: <?php echo esc_attr($settings['button_padding']); ?>; background: <?php echo esc_attr($settings['primary_color']); ?>; color: white; border: none; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; font-weight: 600; cursor: pointer; margin-bottom: 20px;">
                        <?php echo esc_html($settings['register_button_text']); ?>
                    </button>
                    <div style="text-align: center;">
                        <p style="margin: 0; color: #666; font-size: 14px;"><a href="<?php echo esc_url($login_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['register_login_link_text']); ?></a></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateCardRegisterForm($settings, $login_url, $register_url) {
        ob_start();
        ?>
        <div style="max-width: <?php echo esc_attr($settings['form_width']); ?>; width:100%; margin: 0 auto; font-family: <?php echo esc_attr($settings['font_family']); ?>;">
            <!-- Card Container -->
            <div style="background: <?php echo esc_attr($settings['background_color']); ?>; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 8px 25px rgba(0,0,0,0.1); overflow: hidden; position: relative;">
                <!-- Top Accent -->
                <div style="height: 8px; background: linear-gradient(90deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>);"></div>

                <div style="padding: 40px;">
                    <!-- Logo Section -->
                    <div style="text-align: center; margin-bottom: 35px; position: relative;">
                        <div style="width: 90px; height: 90px; background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: bold; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative;">
                            <div style="position: absolute; top: -5px; right: -5px; width: 20px; height: 20px; background: #FF9800; border-radius: 50%; border: 3px solid white;"></div>
                            R
                        </div>
                        <h2 style="color: <?php echo esc_attr($settings['primary_color']); ?>; margin: 0 0 10px 0; font-weight: 700; font-size: <?php echo esc_attr($settings['heading_font_size']); ?>;"><?php echo esc_html($settings['register_title']); ?></h2>
                        <p style="color: #666; margin: 0; font-size: 15px;">Create your account to get started</p>
                    </div>

                    <form name="registerform" id="registerform" action="<?php echo esc_url($register_url); ?>" method="post">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px;"><?php echo esc_html($settings['register_name_label']); ?></label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 18px;">👤</span>
                                <input type="text" name="user_login" id="user_login" placeholder="<?php echo esc_attr($settings['register_name_placeholder']); ?>" style="width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #e1e5e9; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box; transition: all 0.3s ease;" onmouseover="this.style.borderColor='<?php echo esc_attr($settings['primary_color']); ?>'" onmouseout="this.style.borderColor='#e1e5e9'" required>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px;"><?php echo esc_html($settings['register_email_label']); ?></label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 18px;">✉️</span>
                                <input type="email" name="user_email" id="user_email" placeholder="<?php echo esc_attr($settings['register_email_placeholder']); ?>" style="width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #e1e5e9; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; box-sizing: border-box; transition: all 0.3s ease;" onmouseover="this.style.borderColor='<?php echo esc_attr($settings['primary_color']); ?>'" onmouseout="this.style.borderColor='#e1e5e9'" required>
                            </div>
                        </div>
                        <p id="reg_passmail" style="font-size: 13px; color: #666; margin-bottom: 25px; padding: 12px; background: #f8f9fa; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; border-left: 4px solid <?php echo esc_attr($settings['primary_color']); ?>;">
                            <?php echo esc_html__('Registration confirmation will be emailed to you.', 'shopglut'); ?>
                        </p>
                        <button type="submit" name="wp-submit" id="wp-submit" style="width: 100%; padding: <?php echo esc_attr($settings['button_padding']); ?>; background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?>, <?php echo esc_attr($settings['secondary_color']); ?>); color: white; border: none; border-radius: <?php echo esc_attr($settings['border_radius']); ?>; font-size: <?php echo esc_attr($settings['font_size']); ?>; font-weight: 600; cursor: pointer; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <?php echo esc_html($settings['register_button_text']); ?>
                        </button>
                        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e1e5e9;">
                            <p style="margin: 0; color: #666; font-size: 14px;">
                                <?php echo esc_html__('Already have an account?', 'shopglut'); ?> <a href="<?php echo esc_url($login_url); ?>" style="color: <?php echo esc_attr($settings['primary_color']); ?>; text-decoration: none; font-weight: 600;"><?php echo esc_html($settings['register_login_link_text']); ?></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function generateLoginPreview($template_id, $custom_settings) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        // Apply advanced typography settings
        $line_height = isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5';
        $letter_spacing = isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0';
        $heading_weight = isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600';
        $label_font_weight = isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600';

        // Apply form shadow
        $form_shadow_style = '';
        if (isset($custom_settings['form_shadow'])) {
            switch ($custom_settings['form_shadow']) {
                case 'none':
                    $form_shadow_style = 'box-shadow: none;';
                    break;
                case 'light':
                    $form_shadow_style = 'box-shadow: 0 2px 8px rgba(0,0,0,0.08);';
                    break;
                case 'medium':
                    $form_shadow_style = 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
                    break;
                case 'strong':
                    $form_shadow_style = 'box-shadow: 0 8px 30px rgba(0,0,0,0.15);';
                    break;
            }
        }

        // Apply animation settings
        $animation_speed = isset($custom_settings['animation_speed']) ? $custom_settings['animation_speed'] : 'normal';
        $speed_map = ['fast' => '0.1s', 'normal' => '0.2s', 'slow' => '0.3s'];
        $transition_speed = $speed_map[$animation_speed] ?? '0.2s';
        $enable_animations = isset($custom_settings['enable_animations']) && $custom_settings['enable_animations'] === 'yes';

        // Layout 1: Centered Form (Traditional centered card)
        if ($layout_type === 'centered') {
            // Generate logo based on style
            $logo_html = $this->generateLogoHtml($custom_settings);

            // Show subtitle if enabled
            $subtitle_html = '';
            if (isset($custom_settings['show_subtitle']) && $custom_settings['show_subtitle'] === 'yes') {
                $subtitle_html = '<p style="color: #666; margin: 0; font-size: 14px; line-height: ' . $line_height . '; letter-spacing: ' . $letter_spacing . ';">Welcome back! Please login to your account.</p>';
            }

            // Generate input fields based on style
            $input_style = $this->getInputFieldStyle($custom_settings);
            $show_icons = isset($custom_settings['show_input_icons']) && $custom_settings['show_input_icons'] === 'yes';

            // Generate button style
            $button_style = $this->getButtonStyle($custom_settings);

            return sprintf(
                '<div style="max-width: %s; width:100%%; margin: 0 auto; background: %s; padding: 30px; border-radius: %s; font-family: %s; font-size: %s; color: %s; %s  line-height: %s; letter-spacing: %s;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        %s
                        <h2 style="color: %s; margin: 0 0 10px 0; font-weight: %s; font-size: %s; line-height: %s; letter-spacing: %s;">%s</h2>
                        %s
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: %s; color: #555; font-size: 14px; line-height: %s; letter-spacing: %s;">%s</label>
                        <div style="position: relative;">
                            %s
                            <input type="text" placeholder="%s" value="john.doe@example.com" style="%s" onmouseover="this.style.borderColor=\'%s\'" onmouseout="this.style.borderColor=\'%s\'" onfocus="this.style.borderColor=\'%s\'" onblur="this.style.borderColor=\'%s\'">
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: %s; color: #555; font-size: 14px; line-height: %s; letter-spacing: %s;">%s</label>
                        <div style="position: relative;">
                            %s
                            <input type="password" placeholder="%s" value="••••••••" style="%s" onmouseover="this.style.borderColor=\'%s\'" onmouseout="this.style.borderColor=\'%s\'" onfocus="this.style.borderColor=\'%s\'" onblur="this.style.borderColor=\'%s\'">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <label style="display: flex; align-items: center; font-size: 14px; color: #666; cursor: pointer; line-height: %s; letter-spacing: %s;">
                            <input type="checkbox" checked style="margin-right: 8px; width: 16px; height: 16px; cursor: pointer;"> %s
                        </label>
                        <a href="#" style="color: %s; text-decoration: none; font-size: 14px; transition: color %s ease;" onmouseover="this.style.color=\'%s\'" onmouseout="this.style.color=\'%s\'">%s</a>
                    </div>

                    <button style="%s">
                        %s
                    </button>

                    <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                        <p style="margin: 0 0 10px; color: #666; font-size: 14px; line-height: %s; letter-spacing: %s;">Don\'t have an account?</p>
                        <a href="#" style="color: %s; text-decoration: none; font-weight: 600; font-size: 14px; transition: color %s ease;" onmouseover="this.style.color=\'%s\'" onmouseout="this.style.color=\'%s\'">%s</a>
                    </div>
                </div>',
                $custom_settings['form_width'],
                $custom_settings['background_color'],
                $custom_settings['border_radius'],
                $custom_settings['font_family'],
                $custom_settings['font_size'],
                $custom_settings['text_color'],
                $form_shadow_style,
                $line_height,
                $letter_spacing,
                $logo_html,
                $custom_settings['primary_color'],
                $heading_weight,
                $custom_settings['heading_font_size'],
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['login_title']),
                $subtitle_html,
                $label_font_weight,
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['login_username_label']),
                $show_icons ? '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">👤</span>' : '',
                esc_attr($custom_settings['login_username_placeholder']),
                $input_style,
                isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : $custom_settings['primary_color'],
                $custom_settings['input_border_color'],
                isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : $custom_settings['primary_color'],
                $custom_settings['input_border_color'],
                $label_font_weight,
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['login_password_label']),
                $show_icons ? '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">🔒</span>' : '',
                esc_attr($custom_settings['login_password_placeholder']),
                $input_style,
                isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : $custom_settings['primary_color'],
                $custom_settings['input_border_color'],
                isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : $custom_settings['primary_color'],
                $custom_settings['input_border_color'],
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['login_remember_label']),
                $custom_settings['primary_color'],
                $transition_speed,
                $custom_settings['primary_color'],
                $custom_settings['primary_color'],
                esc_html($custom_settings['login_forgot_text']),
                $button_style,
                esc_html($custom_settings['login_button_text']),
                $line_height,
                $letter_spacing,
                $custom_settings['primary_color'],
                $transition_speed,
                $custom_settings['primary_color'],
                $custom_settings['primary_color'],
                esc_html($custom_settings['login_register_link_text'])
            );
        }

        // Layout 2: Split Screen (Two columns: branding + form)
        elseif ($layout_type === 'split') {
            return sprintf(
                '<div style="display: grid; width:100%%; grid-template-columns: 1fr 1fr; gap: 0; max-width: 900px; margin: 0 auto; border-radius: %s; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: %s;">
                    <div style="background: linear-gradient(135deg, %s, %s); padding: 40px; display: flex; align-items: center; justify-content: center; color: white;">
                        <div style="text-align: center;">
                            <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 15px 0;">%s</h1>
                            <p style="font-size: 16px; opacity: 0.95; margin: 0;">%s</p>
                        </div>
                    </div>
                    <div style="background: %s; padding: 40px; font-size: %s; color: %s;">
                        <h2 style="color: %s; margin: 0 0 30px 0; font-weight: 600; font-size: %s;">%s</h2>
                        <div style="margin-bottom: 18px;">
                            <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #555; font-size: 13px;">%s</label>
                            <input type="text" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #555; font-size: 13px;">%s</label>
                            <input type="password" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; font-size: 13px; color: #666;">
                                <input type="checkbox" style="margin-right: 6px;"> %s
                            </label>
                            <a href="#" style="color: %s; text-decoration: none; font-size: 13px;">%s</a>
                        </div>
                        <button style="width: 100%%; padding: %s; background: %s; color: white; border: none; border-radius: %s; font-size: %s; font-weight: 600; cursor: pointer; margin-bottom: 20px;">
                            %s
                        </button>
                        <p style="text-align: center; margin: 0; color: #666; font-size: 13px;"><a href="#" style="color: %s; text-decoration: none; font-weight: 600;">%s</a></p>
                    </div>
                </div>',
                $custom_settings['border_radius'], $custom_settings['font_family'],
                $custom_settings['primary_color'], $custom_settings['secondary_color'],
                esc_html($custom_settings['split_welcome_title']), esc_html($custom_settings['split_welcome_subtitle']),
                $custom_settings['background_color'], $custom_settings['font_size'], $custom_settings['text_color'],
                $custom_settings['primary_color'], $custom_settings['heading_font_size'], esc_html($custom_settings['login_title']),
                esc_html($custom_settings['login_username_label']), esc_attr($custom_settings['login_username_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['login_password_label']), esc_attr($custom_settings['login_password_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['login_remember_label']), $custom_settings['primary_color'], esc_html($custom_settings['login_forgot_text']),
                $custom_settings['button_padding'], $custom_settings['primary_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['login_button_text']), $custom_settings['primary_color'], esc_html($custom_settings['login_register_link_text'])
            );
        }

        // Layout 3: Floating Card (Elevated card with prominent shadow)
        elseif ($layout_type === 'card') {
            // Determine shadow intensity
            $shadow_styles = array(
                'none' => 'none',
                'light' => '0 2px 8px rgba(0,0,0,0.08), 0 0 1px rgba(0,0,0,0.05)',
                'medium' => '0 10px 40px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1)',
                'strong' => '0 15px 50px rgba(0,0,0,0.22), 0 5px 15px rgba(0,0,0,0.15)',
                'dramatic' => '0 25px 60px rgba(0,0,0,0.3), 0 10px 25px rgba(0,0,0,0.2)'
            );
            $shadow = isset($shadow_styles[$custom_settings['card_shadow_intensity']]) ?
                      $shadow_styles[$custom_settings['card_shadow_intensity']] :
                      $shadow_styles['medium'];

            // Build icon HTML if enabled
            $icon_html = '';
            if ($custom_settings['card_show_icon'] === '1') {
                $icon_html = sprintf(
                    '<div style="width: %s; width:100%%; height: %s; background: linear-gradient(135deg, %s, %s); border-radius: 50%%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <span style="color: white; font-size: %s; font-weight: 700;">%s</span>
                    </div>',
                    $custom_settings['card_icon_size'],
                    $custom_settings['card_icon_size'],
                    $custom_settings['primary_color'],
                    $custom_settings['secondary_color'],
                    $custom_settings['card_icon_font_size'],
                    esc_html($custom_settings['card_login_icon'])
                );
            }

            return sprintf(
                '<div style="max-width: 500px; margin: 0 auto; font-family: %s;">
                    <div style="background: %s; padding: 45px 40px; border-radius: %s; box-shadow: %s; font-size: %s; color: %s; border-top: %s solid %s; ">
                        <div style="text-align: center; margin-bottom: 35px;">
                            %s
                            <h2 style="color: %s; margin: 0; font-weight: 700; font-size: %s;">%s</h2>
                        </div>
                        <div style="margin-bottom: 22px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; ">%s</label>
                            <input type="text" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box; transition: border-color 0.3s;">
                        </div>
                        <div style="margin-bottom: 22px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; ">%s</label>
                            <input type="password" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box; transition: border-color 0.3s;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <label style="display: flex; align-items: center; font-size: 14px; color: #666;">
                                <input type="checkbox" style="margin-right: 7px;"> %s
                            </label>
                            <a href="#" style="color: %s; text-decoration: none; font-size: 14px; font-weight: 500;">%s</a>
                        </div>
                        <button style="width: 100%%; padding: %s; background: linear-gradient(135deg, %s, %s); color: white; border: none; border-radius: %s; font-size: %s; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 25px;">
                            %s
                        </button>
                        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #666; font-size: 14px;"><a href="#" style="color: %s; text-decoration: none; font-weight: 600;">%s</a></p>
                        </div>
                    </div>
                </div>',
                $custom_settings['font_family'], $custom_settings['background_color'], $custom_settings['border_radius'],
                $shadow, $custom_settings['font_size'], $custom_settings['text_color'],
                $custom_settings['card_border_top_width'], $custom_settings['card_border_top_color'],
                $icon_html,
                $custom_settings['primary_color'], $custom_settings['heading_font_size'], esc_html($custom_settings['login_title']),
                esc_html($custom_settings['login_username_label']), esc_attr($custom_settings['login_username_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['login_password_label']), esc_attr($custom_settings['login_password_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['login_remember_label']), $custom_settings['primary_color'], esc_html($custom_settings['login_forgot_text']),
                $custom_settings['button_padding'], $custom_settings['primary_color'], $custom_settings['secondary_color'],
                $custom_settings['border_radius'], $custom_settings['font_size'], esc_html($custom_settings['login_button_text']),
                $custom_settings['primary_color'], esc_html($custom_settings['login_register_link_text'])
            );
        }

        // Default fallback
        return '';
    }

    private function generateRegisterPreview($template_id, $custom_settings) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        // Common form fields HTML generator
        $common_fields = function($compact = false) use ($custom_settings) {
            $mb = $compact ? '16px' : '20px';
            $fs = $compact ? '13px' : '';
            return sprintf('
                <div style="margin-bottom: %s;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555; %s">%s</label>
                    <input type="text" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: %s;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555; %s">%s</label>
                    <input type="email" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: %s;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555; %s">%s</label>
                    <input type="text" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: %s;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #555; %s">%s</label>
                    <input type="password" placeholder="%s" style="width: 100%%; padding: %s; border: 2px solid %s; border-radius: %s; font-size: %s; box-sizing: border-box;">
                </div>',
                $mb, $fs, esc_html($custom_settings['register_name_label']), esc_attr($custom_settings['register_name_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                $mb, $fs, esc_html($custom_settings['register_email_label']), esc_attr($custom_settings['register_email_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                $mb, $fs, esc_html($custom_settings['register_username_label']), esc_attr($custom_settings['register_username_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                $mb, $fs, esc_html($custom_settings['register_password_label']), esc_attr($custom_settings['register_password_placeholder']),
                $custom_settings['input_padding'], $custom_settings['input_border_color'], $custom_settings['border_radius'], $custom_settings['font_size']
            );
        };

        // Layout 1: Centered Form - Match the actual generateCenteredRegisterForm
        if ($layout_type === 'centered') {
            // Apply advanced typography settings
            $line_height = isset($custom_settings['line_height']) ? $custom_settings['line_height'] : '1.5';
            $letter_spacing = isset($custom_settings['letter_spacing']) ? $custom_settings['letter_spacing'] : '0';
            $heading_weight = isset($custom_settings['heading_weight']) ? $custom_settings['heading_weight'] : '600';
            $label_font_weight = isset($custom_settings['label_font_weight']) ? $custom_settings['label_font_weight'] : '600';

            // Apply form shadow
            $form_shadow_style = '';
            if (isset($custom_settings['form_shadow'])) {
                switch ($custom_settings['form_shadow']) {
                    case 'none':
                        $form_shadow_style = 'box-shadow: none;';
                        break;
                    case 'light':
                        $form_shadow_style = 'box-shadow: 0 2px 8px rgba(0,0,0,0.08);';
                        break;
                    case 'medium':
                        $form_shadow_style = 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
                        break;
                    case 'strong':
                        $form_shadow_style = 'box-shadow: 0 8px 30px rgba(0,0,0,0.15);';
                        break;
                }
            }

            // Generate logo based on style
            $logo_html = $this->generateLogoHtml($custom_settings);

            // Show subtitle if enabled
            $subtitle_html = '';
            if (isset($custom_settings['show_subtitle']) && $custom_settings['show_subtitle'] === 'yes') {
                $subtitle_html = '<p style="color: #666; margin: 0; font-size: 14px; line-height: ' . $line_height . '; letter-spacing: ' . $letter_spacing . ';">Create your account to get started.</p>';
            }

            // Generate input fields based on style
            $input_style = $this->getInputFieldStyle($custom_settings);
            $show_icons = isset($custom_settings['show_input_icons']) && $custom_settings['show_input_icons'] === 'yes';

            return sprintf(
                '<div style="max-width: %s; width:100%%; margin: 0 auto; background: %s; padding: 30px; border-radius: %s; font-family: %s; font-size: %s; color: %s; %s  line-height: %s; letter-spacing: %s;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        %s
                        <h2 style="color: %s; margin: 0 0 10px 0; font-weight: %s; font-size: %s; line-height: %s; letter-spacing: %s;">%s</h2>
                        %s
                    </div>

                    <form name="registerform" id="registerform" method="post">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: %s; color: #555; font-size: 14px; line-height: %s; letter-spacing: %s;">%s</label>
                            <div style="position: relative;">
                                %s
                                <input type="text" name="user_login" placeholder="%s" value="testuser" style="%s">
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: %s; color: #555; font-size: 14px; line-height: %s; letter-spacing: %s;">%s</label>
                            <div style="position: relative;">
                                %s
                                <input type="email" name="user_email" placeholder="%s" value="test@example.com" style="%s">
                            </div>
                        </div>
                        <p id="reg_passmail" style="font-size: 13px; color: #666; margin-bottom: 20px; line-height: %s; letter-spacing: %s;">
                            Registration confirmation will be emailed to you.
                        </p>
                        <button type="submit" name="wp-submit" id="wp-submit" style="%s">
                            %s
                        </button>
                        <div style="text-align: center; margin-top: 20px;">
                            <p style="margin: 0; color: #666; line-height: %s; letter-spacing: %s;"><a href="#" style="color: %s; text-decoration: none; font-weight: 600;">%s</a></p>
                        </div>
                    </form>
                </div>',
                $custom_settings['form_width'],
                $custom_settings['background_color'],
                $custom_settings['border_radius'],
                $custom_settings['font_family'],
                $custom_settings['font_size'],
                $custom_settings['text_color'],
                $form_shadow_style,
                $line_height,
                $letter_spacing,
                $logo_html,
                $custom_settings['primary_color'],
                $heading_weight,
                $custom_settings['heading_font_size'],
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['register_title']),
                $subtitle_html,
                $label_font_weight,
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['register_username_label']),
                $show_icons ? '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">👤</span>' : '',
                esc_attr($custom_settings['register_username_placeholder']),
                $input_style,
                $label_font_weight,
                $line_height,
                $letter_spacing,
                esc_html($custom_settings['register_email_label']),
                $show_icons ? '<span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1;">✉️</span>' : '',
                esc_attr($custom_settings['register_email_placeholder']),
                $input_style,
                $line_height,
                $letter_spacing,
                $this->getButtonStyle($custom_settings),
                esc_html($custom_settings['register_button_text']),
                $line_height,
                $letter_spacing,
                $custom_settings['primary_color'],
                esc_html($custom_settings['register_login_link_text'])
            );
        }

        // Layout 2: Split Screen
        elseif ($layout_type === 'split') {
            return sprintf(
                '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; max-width: 900px; margin: 0 auto; border-radius: %s; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: %s;">
                    <div style="background: linear-gradient(135deg, %s, %s); padding: 40px; display: flex; align-items: center; justify-content: center; color: white;">
                        <div style="text-align: center;">
                            <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 15px 0;">%s</h1>
                            <p style="font-size: 16px; opacity: 0.95; margin: 0;">%s</p>
                        </div>
                    </div>
                    <div style="background: %s; padding: 35px; font-size: %s; color: %s;">
                        <h2 style="color: %s; margin: 0 0 20px 0; font-weight: 600; font-size: 22px;">%s</h2>
                        %s
                        <button style="width: 100%%; padding: %s; background: %s; color: white; border: none; border-radius: %s; font-size: %s; font-weight: 600; cursor: pointer; margin-bottom: 15px;">
                            %s
                        </button>
                        <p style="text-align: center; margin: 0; color: #666; font-size: 12px;"><a href="#" style="color: %s; text-decoration: none; font-weight: 600;">%s</a></p>
                    </div>
                </div>',
                $custom_settings['border_radius'], $custom_settings['font_family'],
                $custom_settings['primary_color'], $custom_settings['secondary_color'],
                esc_html($custom_settings['split_register_welcome_title']), esc_html($custom_settings['split_register_welcome_subtitle']),
                $custom_settings['background_color'], $custom_settings['font_size'], $custom_settings['text_color'],
                $custom_settings['primary_color'], esc_html($custom_settings['register_title']),
                $common_fields(true),
                $custom_settings['button_padding'], $custom_settings['primary_color'], $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['register_button_text']),
                $custom_settings['primary_color'], esc_html($custom_settings['register_login_link_text'])
            );
        }

        // Layout 3: Floating Card
        elseif ($layout_type === 'card') {
            // Determine shadow intensity
            $shadow_styles = array(
                'none' => 'none',
                'light' => '0 2px 8px rgba(0,0,0,0.08), 0 0 1px rgba(0,0,0,0.05)',
                'medium' => '0 10px 40px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1)',
                'strong' => '0 15px 50px rgba(0,0,0,0.22), 0 5px 15px rgba(0,0,0,0.15)',
                'dramatic' => '0 25px 60px rgba(0,0,0,0.3), 0 10px 25px rgba(0,0,0,0.2)'
            );
            $shadow = isset($shadow_styles[$custom_settings['card_shadow_intensity']]) ?
                      $shadow_styles[$custom_settings['card_shadow_intensity']] :
                      $shadow_styles['medium'];

            // Build icon HTML if enabled
            $icon_html = '';
            if ($custom_settings['card_show_icon'] === '1') {
                $icon_html = sprintf(
                    '<div style="width: %s; height: %s; background: linear-gradient(135deg, %s, %s); border-radius: 50%%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <span style="color: white; font-size: %s; font-weight: 700;">%s</span>
                    </div>',
                    $custom_settings['card_icon_size'],
                    $custom_settings['card_icon_size'],
                    $custom_settings['primary_color'],
                    $custom_settings['secondary_color'],
                    $custom_settings['card_icon_font_size'],
                    esc_html($custom_settings['card_register_icon'])
                );
            }

            return sprintf(
                '<div style="max-width: 520px; margin: 0 auto; font-family: %s;">
                    <div style="background: %s; padding: 40px 35px; border-radius: %s; box-shadow: %s; font-size: %s; color: %s; border-top: %s solid %s; ">
                        <div style="text-align: center; margin-bottom: 30px;">
                            %s
                            <h2 style="color: %s; margin: 0; font-weight: 700; font-size: %s;">%s</h2>
                        </div>
                        %s
                        <button style="width: 100%%; padding: %s; background: linear-gradient(135deg, %s, %s); color: white; border: none; border-radius: %s; font-size: %s; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px;">
                            %s
                        </button>
                        <div style="text-align: center; padding-top: 18px; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #666; font-size: 13px;"><a href="#" style="color: %s; text-decoration: none; font-weight: 600;">%s</a></p>
                        </div>
                    </div>
                </div>',
                $custom_settings['font_family'], $custom_settings['background_color'], $custom_settings['border_radius'],
                $shadow, $custom_settings['font_size'], $custom_settings['text_color'],
                $custom_settings['card_border_top_width'], $custom_settings['card_border_top_color'],
                $icon_html,
                $custom_settings['primary_color'], $custom_settings['heading_font_size'], esc_html($custom_settings['register_title']),
                $common_fields(false),
                $custom_settings['button_padding'], $custom_settings['primary_color'], $custom_settings['secondary_color'],
                $custom_settings['border_radius'], $custom_settings['font_size'],
                esc_html($custom_settings['register_button_text']),
                $custom_settings['primary_color'], esc_html($custom_settings['register_login_link_text'])
            );
        }

        return '';
    }

    private function maybe_override_login() {
        $settings = $this->getSettings();
        if ($settings['override_login'] && !empty($settings['selected_template'])) {
            // Hook into WordPress login system
            add_action('login_init', array($this, 'override_login_page'));
            add_filter('login_url', array($this, 'custom_login_url'));

            // Handle login redirect
            if ($settings['login_redirect_type'] !== 'default') {
                add_filter('login_redirect', array($this, 'redirect_after_login'), 10, 3);
            }

            // Handle registration redirect
            if ($settings['register_redirect_type'] !== 'default') {
                add_filter('registration_redirect', array($this, 'redirect_after_register'));
            }

            if ($settings['hide_admin_bar']) {
                add_action('after_setup_theme', array($this, 'hide_admin_bar_for_subscribers'));
            }

            // Disable user registration if enabled (only on login/register pages)
            if ($settings['disable_registration']) {
                add_action('login_init', array($this, 'disable_registration_on_login_pages'));
            }

            // Add reCAPTCHA if enabled
            if ($settings['enable_recaptcha']) {
                add_action('login_form', array($this, 'add_recaptcha_to_login'));
                add_action('register_form', array($this, 'add_recaptcha_to_register'));
                add_filter('wp_authenticate_user', array($this, 'verify_recaptcha_on_login'), 10, 2);
                add_filter('registration_errors', array($this, 'verify_recaptcha_on_register'), 10, 3);
            }
        }
    }

    public function override_login_page() {
        $settings = $this->getSettings();
        $template_id = $settings['selected_template'];

        if (empty($template_id)) {
            return;
        }

        $template = $this->getTemplate($template_id);
        if (!$template) {
            return;
        }

        $custom_settings = $this->getTemplateCustomSettings($template_id);

        // Check if this is a registration page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET/POST parameter for determining page type
        $action = isset($_REQUEST['action']) ? sanitize_text_field(wp_unslash($_REQUEST['action'])) : 'login';

        // Handle form submissions first
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Safe read-only check for request method
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            return; // Let WordPress handle the form submission
        }

        // Display custom template
        ob_start();
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo esc_html(get_bloginfo('name')); ?> - <?php echo ($action === 'register') ? esc_html__('Register', 'shopglut') : esc_html__('Login', 'shopglut'); ?></title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                html, body {
                    height: 100%;
                    margin: 0;
                    padding: 0;
                }
                body.login-page {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    background: <?php echo esc_attr($custom_settings['background_color']); ?>;
                    padding: 20px;
                }
            </style>
            <?php wp_head(); ?>
        </head>
        <body class="login-page">
            <?php
            if ($action === 'register') {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates properly escaped HTML content
                echo $this->generateActualRegisterForm($template_id, $custom_settings);
            } elseif ($action === 'rp' || $action === 'lostpassword') {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates properly escaped HTML content
                $login_url = site_url('wp-login.php', 'login_post');
                echo wp_kses_post($this->generateForgotPasswordForm($custom_settings, $login_url));
            } else {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method generates properly escaped HTML content
                echo $this->generateActualLoginForm($template_id, $custom_settings);
            }
            ?>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
        $content = ob_get_clean();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output buffered content already escaped
        echo $content;
        exit;
    }

    public function custom_login_url($login_url) {
        // Keep the default wp-login.php URL but it will be overridden by override_login_page()
        return $login_url;
    }

    public function redirect_after_login($redirect_to, $request, $user) {
        $settings = $this->getSettings();

        if ($settings['login_redirect_type'] === 'homepage') {
            return home_url();
        } elseif ($settings['login_redirect_type'] === 'custom' && !empty($settings['login_redirect_url'])) {
            return esc_url($settings['login_redirect_url']);
        }

        return $redirect_to;
    }

    public function redirect_after_register($redirect_url) {
        $settings = $this->getSettings();

        if ($settings['register_redirect_type'] === 'homepage') {
            return home_url();
        } elseif ($settings['register_redirect_type'] === 'login') {
            return wp_login_url();
        } elseif ($settings['register_redirect_type'] === 'custom' && !empty($settings['register_redirect_url'])) {
            return esc_url($settings['register_redirect_url']);
        }

        return $redirect_url;
    }

    public function hide_admin_bar_for_subscribers() {
        if (!current_user_can('edit_posts')) {
            show_admin_bar(false);
        }
    }

    public function add_recaptcha_to_login() {
        $recaptcha_site_key = get_option('shopglut_recaptcha_site_key', '');
        if (!empty($recaptcha_site_key)) {
            echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($recaptcha_site_key) . '" style="margin-bottom: 10px;"></div>';
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), '3.0', true);
        }
    }

    public function add_recaptcha_to_register() {
        $recaptcha_site_key = get_option('shopglut_recaptcha_site_key', '');
        if (!empty($recaptcha_site_key)) {
            echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($recaptcha_site_key) . '" style="margin-bottom: 10px;"></div>';
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), '3.0', true);
        }
    }

    public function verify_recaptcha_on_login($user, $password) {
        $recaptcha_secret_key = get_option('shopglut_recaptcha_secret_key', '');

        if (empty($recaptcha_secret_key)) {
            return $user;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- reCAPTCHA response is verified below
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';

        if (empty($recaptcha_response)) {
            return new \WP_Error('recaptcha_error', __('Please complete the reCAPTCHA verification.', 'shopglut'));
        }

        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $response = wp_remote_post($verify_url, array(
            'body' => array(
                'secret' => $recaptcha_secret_key,
                'response' => $recaptcha_response,
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- REMOTE_ADDR is server variable, safe for IP address validation
                'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : ''
            )
        ));

        if (is_wp_error($response)) {
            return new \WP_Error('recaptcha_error', __('reCAPTCHA verification failed. Please try again.', 'shopglut'));
        }

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body);

        if (!$result->success) {
            return new \WP_Error('recaptcha_error', __('reCAPTCHA verification failed. Please try again.', 'shopglut'));
        }

        return $user;
    }

    public function verify_recaptcha_on_register($errors, $sanitized_user_login, $user_email) {
        $recaptcha_secret_key = get_option('shopglut_recaptcha_secret_key', '');

        if (empty($recaptcha_secret_key)) {
            return $errors;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- reCAPTCHA response is verified below
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';

        if (empty($recaptcha_response)) {
            $errors->add('recaptcha_error', __('Please complete the reCAPTCHA verification.', 'shopglut'));
            return $errors;
        }

        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $response = wp_remote_post($verify_url, array(
            'body' => array(
                'secret' => $recaptcha_secret_key,
                'response' => $recaptcha_response,
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- REMOTE_ADDR is server variable, safe for IP address validation
                'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : ''
            )
        ));

        if (is_wp_error($response)) {
            $errors->add('recaptcha_error', __('reCAPTCHA verification failed. Please try again.', 'shopglut'));
            return $errors;
        }

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body);

        if (!$result->success) {
            $errors->add('recaptcha_error', __('reCAPTCHA verification failed. Please try again.', 'shopglut'));
        }

        return $errors;
    }

    // AJAX handlers
    public function ajax_save_settings() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_login_register_nonce')) {
            wp_send_json_error(array('message' => 'Security verification failed. Please refresh the page and try again.'), 403);
            return;
        }

        $settings = array(
            'override_login' => isset($_POST['override_login']) ? intval($_POST['override_login']) : 0,
            'selected_template' => isset($_POST['selected_template']) ? sanitize_text_field(wp_unslash($_POST['selected_template'])) : '',
            'login_redirect_type' => isset($_POST['login_redirect_type']) ? sanitize_text_field(wp_unslash($_POST['login_redirect_type'])) : 'default',
            'login_redirect_url' => isset($_POST['login_redirect_url']) ? esc_url_raw(wp_unslash($_POST['login_redirect_url'])) : '',
            'register_redirect_type' => isset($_POST['register_redirect_type']) ? sanitize_text_field(wp_unslash($_POST['register_redirect_type'])) : 'default',
            'register_redirect_url' => isset($_POST['register_redirect_url']) ? esc_url_raw(wp_unslash($_POST['register_redirect_url'])) : '',
            'hide_admin_bar' => isset($_POST['hide_admin_bar']) ? intval($_POST['hide_admin_bar']) : 0,
            'enable_recaptcha' => isset($_POST['enable_recaptcha']) ? intval($_POST['enable_recaptcha']) : 0,
            'disable_registration' => isset($_POST['disable_registration']) ? intval($_POST['disable_registration']) : 0
        );

        update_option('shopglut_login_register_settings', $settings);

        wp_send_json_success(array('message' => 'Settings saved successfully'));
    }

    public function ajax_customize_template() {
        check_ajax_referer('shopglut_login_register_nonce', 'nonce');

        $template_id = isset($_POST['template_id']) ? sanitize_text_field(wp_unslash($_POST['template_id'])) : '';
        $custom_settings = array(
            // Colors
            'primary_color' => isset($_POST['primary_color']) ? sanitize_hex_color(wp_unslash($_POST['primary_color'])) : '',
            'secondary_color' => isset($_POST['secondary_color']) ? sanitize_hex_color(wp_unslash($_POST['secondary_color'])) : '',
            'background_color' => isset($_POST['background_color']) ? sanitize_hex_color(wp_unslash($_POST['background_color'])) : '',
            'text_color' => isset($_POST['text_color']) ? sanitize_hex_color(wp_unslash($_POST['text_color'])) : '#333333',
            'input_border_color' => isset($_POST['input_border_color']) ? sanitize_hex_color(wp_unslash($_POST['input_border_color'])) : '#ddd',

            // Typography
            'font_family' => isset($_POST['font_family']) ? sanitize_text_field(wp_unslash($_POST['font_family'])) : 'inherit',
            'font_size' => isset($_POST['font_size']) ? sanitize_text_field(wp_unslash($_POST['font_size'])) : '16px',
            'heading_font_size' => isset($_POST['heading_font_size']) ? sanitize_text_field(wp_unslash($_POST['heading_font_size'])) : '24px',

            // Layout
            'form_width' => isset($_POST['form_width']) ? sanitize_text_field(wp_unslash($_POST['form_width'])) : '400px',
            'border_radius' => isset($_POST['border_radius']) ? sanitize_text_field(wp_unslash($_POST['border_radius'])) : '8px',
            'input_padding' => isset($_POST['input_padding']) ? sanitize_text_field(wp_unslash($_POST['input_padding'])) : '12px',
            'button_padding' => isset($_POST['button_padding']) ? sanitize_text_field(wp_unslash($_POST['button_padding'])) : '15px',

            // Split Screen Content
            'split_welcome_title' => isset($_POST['split_welcome_title']) ? sanitize_text_field(wp_unslash($_POST['split_welcome_title'])) : 'Welcome Back',
            'split_welcome_subtitle' => isset($_POST['split_welcome_subtitle']) ? sanitize_text_field(wp_unslash($_POST['split_welcome_subtitle'])) : 'Sign in to continue to your account',
            'split_register_welcome_title' => isset($_POST['split_register_welcome_title']) ? sanitize_text_field(wp_unslash($_POST['split_register_welcome_title'])) : 'Join Us Today',
            'split_register_welcome_subtitle' => isset($_POST['split_register_welcome_subtitle']) ? sanitize_text_field(wp_unslash($_POST['split_register_welcome_subtitle'])) : 'Create an account to get started',

            // Floating Card Appearance
            'card_show_icon' => isset($_POST['card_show_icon']) ? '1' : '0',
            'card_login_icon' => isset($_POST['card_login_icon']) ? sanitize_text_field(wp_unslash($_POST['card_login_icon'])) : '👤',
            'card_register_icon' => isset($_POST['card_register_icon']) ? sanitize_text_field(wp_unslash($_POST['card_register_icon'])) : '✍️',
            'card_icon_size' => isset($_POST['card_icon_size']) ? sanitize_text_field(wp_unslash($_POST['card_icon_size'])) : '70px',
            'card_icon_font_size' => isset($_POST['card_icon_font_size']) ? sanitize_text_field(wp_unslash($_POST['card_icon_font_size'])) : '36px',
            'card_border_top_color' => isset($_POST['card_border_top_color']) ? sanitize_hex_color(wp_unslash($_POST['card_border_top_color'])) : '',
            'card_border_top_width' => isset($_POST['card_border_top_width']) ? sanitize_text_field(wp_unslash($_POST['card_border_top_width'])) : '4px',
            'card_shadow_intensity' => isset($_POST['card_shadow_intensity']) ? sanitize_text_field(wp_unslash($_POST['card_shadow_intensity'])) : 'medium',

            // Login Form Labels
            'login_title' => isset($_POST['login_title']) ? sanitize_text_field(wp_unslash($_POST['login_title'])) : 'Login to Your Account',
            'login_username_label' => isset($_POST['login_username_label']) ? sanitize_text_field(wp_unslash($_POST['login_username_label'])) : 'Username or Email',
            'login_username_placeholder' => isset($_POST['login_username_placeholder']) ? sanitize_text_field(wp_unslash($_POST['login_username_placeholder'])) : 'Enter your username or email',
            'login_password_label' => isset($_POST['login_password_label']) ? sanitize_text_field(wp_unslash($_POST['login_password_label'])) : 'Password',
            'login_password_placeholder' => isset($_POST['login_password_placeholder']) ? sanitize_text_field(wp_unslash($_POST['login_password_placeholder'])) : 'Enter your password',
            'login_remember_label' => isset($_POST['login_remember_label']) ? sanitize_text_field(wp_unslash($_POST['login_remember_label'])) : 'Remember me',
            'login_button_text' => isset($_POST['login_button_text']) ? sanitize_text_field(wp_unslash($_POST['login_button_text'])) : 'Log In',
            'login_forgot_text' => isset($_POST['login_forgot_text']) ? sanitize_text_field(wp_unslash($_POST['login_forgot_text'])) : 'Forgot your password?',
            'login_register_link_text' => isset($_POST['login_register_link_text']) ? sanitize_text_field(wp_unslash($_POST['login_register_link_text'])) : "Don't have an account? Sign up",

            // Register Form Labels
            'register_title' => isset($_POST['register_title']) ? sanitize_text_field(wp_unslash($_POST['register_title'])) : 'Create Your Account',
            'register_name_label' => isset($_POST['register_name_label']) ? sanitize_text_field(wp_unslash($_POST['register_name_label'])) : 'Full Name',
            'register_name_placeholder' => isset($_POST['register_name_placeholder']) ? sanitize_text_field(wp_unslash($_POST['register_name_placeholder'])) : 'Enter your full name',
            'register_email_label' => isset($_POST['register_email_label']) ? sanitize_text_field(wp_unslash($_POST['register_email_label'])) : 'Email Address',
            'register_email_placeholder' => isset($_POST['register_email_placeholder']) ? sanitize_text_field(wp_unslash($_POST['register_email_placeholder'])) : 'Enter your email address',
            'register_username_label' => isset($_POST['register_username_label']) ? sanitize_text_field(wp_unslash($_POST['register_username_label'])) : 'Username',
            'register_username_placeholder' => isset($_POST['register_username_placeholder']) ? sanitize_text_field(wp_unslash($_POST['register_username_placeholder'])) : 'Choose a username',
            'register_password_label' => isset($_POST['register_password_label']) ? sanitize_text_field(wp_unslash($_POST['register_password_label'])) : 'Password',
            'register_password_placeholder' => isset($_POST['register_password_placeholder']) ? sanitize_text_field(wp_unslash($_POST['register_password_placeholder'])) : 'Create a strong password',
            'register_confirm_password_label' => isset($_POST['register_confirm_password_label']) ? sanitize_text_field(wp_unslash($_POST['register_confirm_password_label'])) : 'Confirm Password',
            'register_confirm_password_placeholder' => isset($_POST['register_confirm_password_placeholder']) ? sanitize_text_field(wp_unslash($_POST['register_confirm_password_placeholder'])) : 'Confirm your password',
            'register_button_text' => isset($_POST['register_button_text']) ? sanitize_text_field(wp_unslash($_POST['register_button_text'])) : 'Create Account',
            'register_login_link_text' => isset($_POST['register_login_link_text']) ? sanitize_text_field(wp_unslash($_POST['register_login_link_text'])) : 'Already have an account? Log in',

            // Forgot Password Form Labels
            'forgot_title' => isset($_POST['forgot_title']) ? sanitize_text_field(wp_unslash($_POST['forgot_title'])) : 'Reset Your Password',
            'forgot_description' => isset($_POST['forgot_description']) ? sanitize_text_field(wp_unslash($_POST['forgot_description'])) : "Enter your email address and we'll send you a link to reset your password.",
            'forgot_email_label' => isset($_POST['forgot_email_label']) ? sanitize_text_field(wp_unslash($_POST['forgot_email_label'])) : 'Email Address',
            'forgot_email_placeholder' => isset($_POST['forgot_email_placeholder']) ? sanitize_text_field(wp_unslash($_POST['forgot_email_placeholder'])) : 'Enter your email address',
            'forgot_button_text' => isset($_POST['forgot_button_text']) ? sanitize_text_field(wp_unslash($_POST['forgot_button_text'])) : 'Get Reset Link',
            'forgot_login_link_text' => isset($_POST['forgot_login_link_text']) ? sanitize_text_field(wp_unslash($_POST['forgot_login_link_text'])) : 'Remember your password? Log in',

            // Form Elements
            'input_field_style' => isset($_POST['input_field_style']) ? sanitize_text_field(wp_unslash($_POST['input_field_style'])) : 'minimal',
            'input_focus_color' => isset($_POST['input_focus_color']) ? sanitize_hex_color(wp_unslash($_POST['input_focus_color'])) : '#007cba',
            'show_input_icons' => isset($_POST['show_input_icons']) ? sanitize_text_field(wp_unslash($_POST['show_input_icons'])) : 'yes',
            'label_font_weight' => isset($_POST['label_font_weight']) ? sanitize_text_field(wp_unslash($_POST['label_font_weight'])) : '600',

            // Button Styling
            'button_style' => isset($_POST['button_style']) ? sanitize_text_field(wp_unslash($_POST['button_style'])) : 'filled',
            'button_hover_effect' => isset($_POST['button_hover_effect']) ? sanitize_text_field(wp_unslash($_POST['button_hover_effect'])) : 'lift',
            'button_border_radius' => isset($_POST['button_border_radius']) ? sanitize_text_field(wp_unslash($_POST['button_border_radius'])) : '8px',
            'button_text_transform' => isset($_POST['button_text_transform']) ? sanitize_text_field(wp_unslash($_POST['button_text_transform'])) : 'none',

            // Logo/Branding
            'logo_style' => isset($_POST['logo_style']) ? sanitize_text_field(wp_unslash($_POST['logo_style'])) : 'circle',
            'logo_size' => isset($_POST['logo_size']) ? sanitize_text_field(wp_unslash($_POST['logo_size'])) : '80px',
            'logo_text' => isset($_POST['logo_text']) ? sanitize_text_field(wp_unslash($_POST['logo_text'])) : 'SG',
            'show_subtitle' => isset($_POST['show_subtitle']) ? sanitize_text_field(wp_unslash($_POST['show_subtitle'])) : 'yes',

            // Advanced Typography
            'line_height' => isset($_POST['line_height']) ? sanitize_text_field(wp_unslash($_POST['line_height'])) : '1.5',
            'letter_spacing' => isset($_POST['letter_spacing']) ? sanitize_text_field(wp_unslash($_POST['letter_spacing'])) : '0',
            'heading_weight' => isset($_POST['heading_weight']) ? sanitize_text_field(wp_unslash($_POST['heading_weight'])) : '600',

            // Visual Effects
            'form_shadow' => isset($_POST['form_shadow']) ? sanitize_text_field(wp_unslash($_POST['form_shadow'])) : 'medium',
            'animation_speed' => isset($_POST['animation_speed']) ? sanitize_text_field(wp_unslash($_POST['animation_speed'])) : 'normal',
            'enable_animations' => isset($_POST['enable_animations']) ? sanitize_text_field(wp_unslash($_POST['enable_animations'])) : 'yes'
        );

        update_option('shopglut_template_custom_' . $template_id, $custom_settings);

        wp_send_json_success(array('message' => 'Template customization saved successfully'));
    }

    public function ajax_get_template_preview() {
        check_ajax_referer('shopglut_login_register_nonce', 'nonce');

        $template_id = isset($_POST['template_id']) ? sanitize_text_field(wp_unslash($_POST['template_id'])) : '';

        if (empty($template_id)) {
            wp_send_json_error(array('message' => 'Template ID is required'));
        }

        $template = $this->getTemplate($template_id);
        if (!$template) {
            wp_send_json_error(array('message' => 'Template not found'));
        }

        $custom_settings = $this->getTemplateCustomSettings($template_id);

        // Generate login and register previews
        $login_preview = $this->generateLoginPreview($template_id, $custom_settings);
        $register_preview = $this->generateRegisterPreview($template_id, $custom_settings);

        wp_send_json_success(array(
            'login_preview' => $login_preview,
            'register_preview' => $register_preview
        ));
    }

    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }

    private function generateMiniLoginPreview($template_id, $custom_settings) {
        $template = $this->getTemplate($template_id);
        $layout_type = isset($template['layout_type']) ? $template['layout_type'] : 'centered';

        // Use custom settings for colors and styling
        $primary_color = !empty($custom_settings['primary_color']) ? $custom_settings['primary_color'] : '#007cba';
        $secondary_color = !empty($custom_settings['secondary_color']) ? $custom_settings['secondary_color'] : '#005a87';
        $background_color = !empty($custom_settings['background_color']) ? $custom_settings['background_color'] : '#ffffff';
        $text_color = !empty($custom_settings['text_color']) ? $custom_settings['text_color'] : '#333333';
        $font_family = !empty($custom_settings['font_family']) ? $custom_settings['font_family'] : '-apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif';

        // Generate brief preview showing header and part of form
        if ($layout_type === 'centered') {
            return sprintf(
                '<div class="template-actual-design">
                    <div style="width: 100%%;height: 100%%;background: linear-gradient(135deg, %s 0%%, %s 100%%);padding: 4px;border-radius: 8px;align-items: center;justify-content: center;display: flex;">
                        <div style="width: 200px;height: 180px;background: %s;padding: 20px 15px;border-radius: 6px;font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif;color: %s;flex-direction: column;align-items: center;justify-content: center;display: flex;">
                            <!-- Logo -->
                            <div style="width: 45px;height: 45px;background: %s;border-radius: 50%%;margin-top: 22px;margin-bottom: 15px;align-items: center;justify-content: center;color: white;font-size: 20px;font-weight: bold;flex-shrink: 0;display: flex;">
                                L
                            </div>

                            <!-- Title -->
                            <div style="color: %s;font-size: 14px;font-weight: 600;margin-bottom: 20px;text-align: center">%s</div>

                            <!-- Form Fields -->
                            <div style="width: 100%%;">
                                <div style="background: white;border: 2px solid #e0e0e0;border-radius: 4px;padding: 8px;margin-bottom: 10px;font-size: 11px;color: #999;text-align: center;">
                                    john@example.com
                                </div>
                                <div style="background: white;border: 2px solid #e0e0e0;border-radius: 4px;padding: 8px;margin-bottom: 15px;font-size: 11px;color: #999;text-align: center;">
                                    ••••••••
                                </div>
                            </div>

                            <!-- Button -->
                            <div style="width: 100%%;background: %s;color: white;border-radius: 4px;padding: 10px;text-align: center;font-size: 12px;font-weight: 600;">
                                %s
                            </div>
                        </div>
                    </div>
                </div>',
                $primary_color,
                $secondary_color,
                $background_color,
                $text_color,
                $primary_color,
                $primary_color,
                substr($custom_settings['login_title'], 0, 25),
                $primary_color,
                substr($custom_settings['login_button_text'], 0, 20)
            );
        }
        elseif ($layout_type === 'split') {
            return sprintf(
                '<div style="width: 100%%; height: 100%%; display: grid; grid-template-columns: 1fr 1fr; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif;">
                    <!-- Left Side - Branding -->
                    <div style="background: linear-gradient(135deg, %s 0%%, %s 100%%); color: white; padding: 15px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%%; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                            S
                        </div>
                        <div style="font-size: 14px; font-weight: 600; text-align: center;">%s</div>
                        <div style="font-size: 10px; opacity: 0.9; text-align: center; margin-top: 5px;">Welcome Back</div>
                    </div>

                    <!-- Right Side - Form -->
                    <div style="background: %s; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
                        <div style="color: %s; font-size: 16px; font-weight: 600; margin-bottom: 20px; text-align: center;">%s</div>
                        <div style="background: white; border: 2px solid #e0e0e0; border-radius: 4px; padding: 10px; margin-bottom: 10px; font-size: 12px; color: #999; text-align: center;">john@example.com</div>
                        <div style="background: white; border: 2px solid #e0e0e0; border-radius: 4px; padding: 10px; margin-bottom: 15px; font-size: 12px; color: #999; text-align: center;">••••••••</div>
                        <div style="background: %s; color: white; border-radius: 4px; padding: 12px; text-align: center; font-size: 12px; font-weight: 600; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                            %s
                        </div>
                    </div>
                </div>',
                $primary_color, $secondary_color,
                substr($custom_settings['split_welcome_title'], 0, 12),
                $background_color,
                $primary_color,
                substr($custom_settings['login_title'], 0, 15),
                $primary_color,
                substr($custom_settings['login_button_text'], 0, 15)
            );
        }
        elseif ($layout_type === 'card') {
            return sprintf(
                '<div style="width: 100%%; height: 100%%; background: %s; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; color: %s; padding: 8px; border-radius: 8px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <div style="width: 200px; height: 180px; background: %s; padding: 25px; border-radius: 6px; position: relative;">
                        <!-- Top Accent -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, %s, %s);"></div>

                        <!-- Logo Section -->
                        <div style="text-align: center; margin-bottom: 20px; position: relative;">
                            <div style="width: 45px; height: 45px; background: linear-gradient(135deg, %s, %s); border-radius: 50%%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.2); position: relative;">
                                F
                            </div>
                            <div style="color: %s; font-size: 16px; font-weight: 700; margin-bottom: 5px;">%s</div>
                            <div style="color: #666; font-size: 11px;">Welcome back! Please login</div>
                        </div>

                        <!-- Form Fields -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="background: white; border: 2px solid #e1e5e9; border-radius: 4px; padding: 10px; font-size: 12px; color: #999; text-align: center; position: relative;">
                                <span style="position: absolute; left: 10px; top: 50%%; transform: translateY(-50%%); color: #999; font-size: 14px;">👤</span>
                                john@example.com
                            </div>
                            <div style="background: white; border: 2px solid #e1e5e9; border-radius: 4px; padding: 10px; font-size: 12px; color: #999; text-align: center; position: relative;">
                                <span style="position: absolute; left: 10px; top: 50%%; transform: translateY(-50%%); color: #999; font-size: 14px;">🔒</span>
                                ••••••••
                            </div>
                        </div>

                        <!-- Button -->
                        <div style="background: linear-gradient(135deg, %s, %s); color: white; border-radius: 4px; padding: 12px; text-align: center; font-size: 13px; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.2); margin-top: 20px;">
                            %s
                        </div>
                    </div>
                </div>',
                $background_color,
                $text_color,
                $background_color,
                $primary_color,
                $secondary_color,
                $primary_color,
                $secondary_color,
                $primary_color,
                substr($custom_settings['login_title'], 0, 20),
                $primary_color,
                $secondary_color,
                substr($custom_settings['login_button_text'], 0, 18)
            );
        }

        // Fallback to simple design if layout type not recognized
        return '<div style="width: 100%; height: 100%; background: #ffffff; padding: 10px; border-radius: 6px; border: 2px solid #e0e0e0; display: flex; align-items: center; justify-content: center; color: #333333; font-size: 10px; font-weight: 600; text-align: center; font-family: Arial, sans-serif;">Login Form</div>';
    }

    /**
     * Generate logo HTML based on customization settings
     */
    private function generateLogoHtml($custom_settings) {
        $logo_style = isset($custom_settings['logo_style']) ? $custom_settings['logo_style'] : 'circle';
        $logo_size = isset($custom_settings['logo_size']) ? $custom_settings['logo_size'] : '80px';
        $logo_text = isset($custom_settings['logo_text']) ? $custom_settings['logo_text'] : 'SG';
        $primary_color = isset($custom_settings['primary_color']) ? $custom_settings['primary_color'] : '#007cba';
        $secondary_color = isset($custom_settings['secondary_color']) ? $custom_settings['secondary_color'] : '#f0f0f1';

        switch ($logo_style) {
            case 'text':
                return sprintf(
                    '<div style="font-size: %s; font-weight: bold; color: %s; margin-bottom: 20px; line-height: 1;">%s</div>',
                    $logo_size,
                    $primary_color,
                    esc_html($logo_text)
                );

            case 'square':
                return sprintf(
                    '<div style="width: %s; height: %s; background: linear-gradient(135deg, %s, %s); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: %s; font-weight: bold;">%s</div>',
                    $logo_size,
                    $logo_size,
                    $primary_color,
                    $secondary_color,
                    intval($logo_size) * 0.4 . 'px',
                    esc_html(substr($logo_text, 0, 3))
                );

            case 'rounded':
                return sprintf(
                    '<div style="width: %s; height: %s; background: linear-gradient(135deg, %s, %s); border-radius: 12px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: %s; font-weight: bold;">%s</div>',
                    $logo_size,
                    $logo_size,
                    $primary_color,
                    $secondary_color,
                    intval($logo_size) * 0.4 . 'px',
                    esc_html(substr($logo_text, 0, 3))
                );

            case 'circle':
            default:
                return sprintf(
                    '<div style="width: %s; height: %s; background: linear-gradient(135deg, %s, %s); border-radius: 50%%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: %s; font-weight: bold;">%s</div>',
                    $logo_size,
                    $logo_size,
                    $primary_color,
                    $secondary_color,
                    intval($logo_size) * 0.4 . 'px',
                    esc_html(substr($logo_text, 0, 3))
                );
        }
    }

    /**
     * Generate input field CSS based on customization settings
     */
    private function getInputFieldStyle($custom_settings) {
        $input_style = isset($custom_settings['input_field_style']) ? $custom_settings['input_field_style'] : 'minimal';
        $input_padding = isset($custom_settings['input_padding']) ? $custom_settings['input_padding'] : '12px';
        $border_radius = isset($custom_settings['border_radius']) ? $custom_settings['border_radius'] : '8px';
        $font_size = isset($custom_settings['font_size']) ? $custom_settings['font_size'] : '16px';
        $border_color = isset($custom_settings['input_border_color']) ? $custom_settings['input_border_color'] : '#ddd';
        $focus_color = isset($custom_settings['input_focus_color']) ? $custom_settings['input_focus_color'] : '#007cba';
        $show_icons = isset($custom_settings['show_input_icons']) && $custom_settings['show_input_icons'] === 'yes';

        $padding_left = $show_icons ? '40px' : $input_padding;
        $base_style = "width: 100%; padding: {$input_padding} {$input_padding} {$input_padding} {$padding_left}; font-size: {$font_size}; box-sizing: border-box; transition: border-color 0.2s ease;";

        switch ($input_style) {
            case 'minimal':
                return $base_style . " border: none; border-bottom: 2px solid {$border_color}; border-radius: 0; background: transparent;";

            case 'outlined':
                return $base_style . " border: 2px solid {$border_color}; border-radius: {$border_radius}; background: transparent;";

            case 'filled':
                return $base_style . " border: none; border-radius: {$border_radius}; background: #f8f9fa;";

            default:
                return $base_style . " border: 2px solid {$border_color}; border-radius: {$border_radius}; background: white;";
        }
    }

    /**
     * Generate button CSS based on customization settings
     */
    private function getButtonStyle($custom_settings) {
        $button_style = isset($custom_settings['button_style']) ? $custom_settings['button_style'] : 'filled';
        $button_padding = isset($custom_settings['button_padding']) ? $custom_settings['button_padding'] : '15px';
        $button_border_radius = isset($custom_settings['button_border_radius']) ? $custom_settings['button_border_radius'] : '8px';
        $font_size = isset($custom_settings['font_size']) ? $custom_settings['font_size'] : '16px';
        $primary_color = isset($custom_settings['primary_color']) ? $custom_settings['primary_color'] : '#007cba';
        $secondary_color = isset($custom_settings['secondary_color']) ? $custom_settings['secondary_color'] : '#f0f0f1';
        $hover_effect = isset($custom_settings['button_hover_effect']) ? $custom_settings['button_hover_effect'] : 'lift';
        $text_transform = isset($custom_settings['button_text_transform']) ? $custom_settings['button_text_transform'] : 'none';
        $enable_animations = isset($custom_settings['enable_animations']) && $custom_settings['enable_animations'] === 'yes';

        $base_style = "width: 100%; padding: {$button_padding}; border-radius: {$button_border_radius}; font-size: {$font_size}; font-weight: 600; cursor: pointer; text-transform: {$text_transform};";

        // Add hover effects
        if ($enable_animations) {
            switch ($hover_effect) {
                case 'lift':
                    $base_style .= ' transition: transform 0.2s ease, box-shadow 0.2s ease;';
                    $base_style .= ' onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 6px 20px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\'"';
                    break;
                case 'scale':
                    $base_style .= ' transition: transform 0.2s ease;';
                    $base_style .= ' onmouseover="this.style.transform=\'scale(1.05)\'" onmouseout="this.style.transform=\'scale(1)\'"';
                    break;
                case 'shadow':
                    $base_style .= ' transition: box-shadow 0.2s ease;';
                    $base_style .= ' onmouseover="this.style.boxShadow=\'0 6px 20px rgba(0,0,0,0.15)\'" onmouseout="this.style.boxShadow=\'none\'"';
                    break;
                case 'none':
                default:
                    $base_style .= ' transition: none;';
                    break;
            }
        }

        switch ($button_style) {
            case 'outline':
                return $base_style . " background: transparent; color: {$primary_color}; border: 2px solid {$primary_color};";

            case 'gradient':
                return $base_style . " background: linear-gradient(135deg, {$primary_color}, {$secondary_color}); color: white; border: none;";

            case 'filled':
            default:
                return $base_style . " background: {$primary_color}; color: white; border: none;";
        }
    }

    /**
     * Disable registration only on login/register pages
     */
    public function disable_registration_on_login_pages() {
        // Check if we're on the registration page
        $action = isset($_REQUEST['action']) ? sanitize_text_field(wp_unslash($_REQUEST['action'])) : 'login'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin context with parameter validation

        // Only disable registration on the registration page, not globally
        if ($action === 'register') {
            add_filter('option_users_can_register', '__return_false');
            add_filter('pre_option_users_can_register', '__return_false');
        }
    }
}