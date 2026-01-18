<?php
namespace Shopglut\BusinessSolutions\EmailCustomizer\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit;

class AjaxController {
    
    public function __construct() {
        add_action('wp_ajax_shopglut_save_email_template', array($this, 'saveEmailTemplate'));
        add_action('wp_ajax_shopglut_load_email_template', array($this, 'loadEmailTemplate'));
        add_action('wp_ajax_shopglut_send_test_email', array($this, 'sendTestEmail'));
    }
    
    public function saveEmailTemplate() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_email_customizer')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        $template_data = isset($_POST['template_data']) ? sanitize_text_field(wp_unslash($_POST['template_data'])) : '';
        $decoded_data = json_decode(stripslashes($template_data), true);
        
        if (!$decoded_data || !isset($decoded_data['template'])) {
            wp_send_json_error('Invalid template data');
            return;
        }
        
        $template_name = sanitize_text_field($decoded_data['template']);
        $components = isset($decoded_data['components']) ? $decoded_data['components'] : array();
        
        // Sanitize components data
        $sanitized_components = array();
        foreach ($components as $component) {
            $sanitized_components[] = array(
                'type' => sanitize_text_field($component['type']),
                'id' => sanitize_text_field($component['id']),
                'html' => wp_kses_post($component['html'])
            );
        }
        
        // Save to WordPress options
        $option_name = 'shopglut_email_template_' . $template_name;
        $template_config = array(
            'template' => $template_name,
            'components' => $sanitized_components,
            'updated' => current_time('mysql')
        );
        
        $saved = update_option($option_name, $template_config);
        
        if ($saved) {
            wp_send_json_success('Template saved successfully');
        } else {
            wp_send_json_error('Failed to save template');
        }
    }

    public function loadEmailTemplate() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_email_customizer')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        $template_name = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])) : '';
        
        if (empty($template_name)) {
            wp_send_json_error('Template name is required');
            return;
        }
        
        // Load from WordPress options
        $option_name = 'shopglut_email_template_' . $template_name;
        $template_data = get_option($option_name, null);
        
        if ($template_data) {
            wp_send_json_success(array(
                'template' => $template_data
            ));
        } else {
            wp_send_json_error('Template not found');
        }
    }

    public function sendTestEmail() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_email_customizer')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        $test_email = isset($_POST['test_email']) ? sanitize_email(wp_unslash($_POST['test_email'])) : '';
        $template_html = isset($_POST['template_html']) ? wp_kses_post(wp_unslash($_POST['template_html'])) : '';
        $subject = isset($_POST['subject']) ? sanitize_text_field(wp_unslash($_POST['subject'])) : '';
        
        if (empty($test_email) || !is_email($test_email)) {
            wp_send_json_error('Valid email address is required');
            return;
        }
        
        if (empty($template_html)) {
            wp_send_json_error('Email template is required');
            return;
        }
        
        if (empty($subject)) {
            $subject = 'Test Email from Shopglut Email Customizer';
        }
        
        // Set email content type to HTML
        add_filter('wp_mail_content_type', function() {
            return 'text/html';
        });
        
        $sent = wp_mail($test_email, $subject, $template_html);
        
        // Reset content type
        remove_filter('wp_mail_content_type', '__return_true');
        
        if ($sent) {
            wp_send_json_success('Test email sent successfully');
        } else {
            wp_send_json_error('Failed to send test email');
        }
    }
}