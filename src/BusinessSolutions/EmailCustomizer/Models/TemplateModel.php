<?php
namespace Shopglut\BusinessSolutions\EmailCustomizer\Models;

class TemplateModel {
    
    public static function getDefaultTemplates() {
        return array(
            'new-order' => array(
                'template' => 'new-order',
                'components' => array(
                    array(
                        'type' => 'header',
                        'id' => 'default-header',
                        'html' => '<div style="text-align: center; padding: 20px; background: #f0f0f1;"><div style="width: 150px; height: 50px; background: #007cba; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">LOGO</div></div>'
                    ),
                    array(
                        'type' => 'heading',
                        'id' => 'default-heading',
                        'html' => '<div style="padding: 15px;"><h2 style="margin: 0; color: #333; font-size: 24px;">Thank you for your order!</h2></div>'
                    ),
                    array(
                        'type' => 'text',
                        'id' => 'default-text',
                        'html' => '<div style="padding: 15px;"><p style="margin: 0; line-height: 1.6;">We have received your order and are processing it now. You will receive a confirmation email shortly.</p></div>'
                    ),
                    array(
                        'type' => 'order-details',
                        'id' => 'default-order-details',
                        'html' => '<div style="padding: 15px;"><h3 style="margin: 0 0 15px 0; color: #333;">Order Details</h3><table style="width: 100%; border-collapse: collapse;"><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Product</th><th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Qty</th><th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Price</th></tr><tr><td style="padding: 10px; border: 1px solid #ddd;">Sample Product</td><td style="padding: 10px; text-align: center; border: 1px solid #ddd;">1</td><td style="padding: 10px; text-align: right; border: 1px solid #ddd;">$99.00</td></tr></table></div>'
                    ),
                    array(
                        'type' => 'footer',
                        'id' => 'default-footer',
                        'html' => '<div style="text-align: center; padding: 20px; background: #f8f9fa; border-top: 1px solid #ddd;"><p style="margin: 0; color: #666; font-size: 12px;">© 2024 Your Company Name. All rights reserved.</p></div>'
                    )
                )
            ),
            'processing-order' => array(
                'template' => 'processing-order',
                'components' => array(
                    array(
                        'type' => 'header',
                        'id' => 'processing-header',
                        'html' => '<div style="text-align: center; padding: 20px; background: #f0f0f1;"><div style="width: 150px; height: 50px; background: #007cba; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">LOGO</div></div>'
                    ),
                    array(
                        'type' => 'heading',
                        'id' => 'processing-heading',
                        'html' => '<div style="padding: 15px;"><h2 style="margin: 0; color: #333; font-size: 24px;">Your order is being processed</h2></div>'
                    ),
                    array(
                        'type' => 'text',
                        'id' => 'processing-text',
                        'html' => '<div style="padding: 15px;"><p style="margin: 0; line-height: 1.6;">Good news! Your order is now being processed and will be shipped soon.</p></div>'
                    )
                )
            ),
            'completed-order' => array(
                'template' => 'completed-order',
                'components' => array(
                    array(
                        'type' => 'header',
                        'id' => 'completed-header',
                        'html' => '<div style="text-align: center; padding: 20px; background: #f0f0f1;"><div style="width: 150px; height: 50px; background: #007cba; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">LOGO</div></div>'
                    ),
                    array(
                        'type' => 'heading',
                        'id' => 'completed-heading',
                        'html' => '<div style="padding: 15px;"><h2 style="margin: 0; color: #28a745; font-size: 24px;">Order Completed!</h2></div>'
                    ),
                    array(
                        'type' => 'text',
                        'id' => 'completed-text',
                        'html' => '<div style="padding: 15px;"><p style="margin: 0; line-height: 1.6;">Your order has been completed successfully. Thank you for your business!</p></div>'
                    )
                )
            )
        );
    }
    
    public static function getTemplate($template_name) {
        $option_name = 'shopglut_email_template_' . $template_name;
        return get_option($option_name, null);
    }
    
    public static function saveTemplate($template_name, $template_data) {
        $option_name = 'shopglut_email_template_' . $template_name;
        $template_config = array(
            'template' => $template_name,
            'components' => $template_data['components'],
            'updated' => current_time('mysql')
        );
        
        return update_option($option_name, $template_config);
    }
    
    public static function deleteTemplate($template_name) {
        $option_name = 'shopglut_email_template_' . $template_name;
        return delete_option($option_name);
    }
    
    public static function getAllTemplates() {
        global $wpdb;
        
        $templates = array();
        
        // Check cache first
        $cache_key = 'shopglut_email_templates_all';
        $cached_results = wp_cache_get( $cache_key );
        
        if ( false === $cached_results ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $results = $wpdb->get_results(
                "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'shopglut_email_template_%'"
            );
            // Cache for 5 minutes
            wp_cache_set( $cache_key, $results, '', 300 );
        } else {
            $results = $cached_results;
        }
        
        foreach ($results as $result) {
            $template_data = maybe_unserialize($result->option_value);
            if (is_array($template_data) && isset($template_data['template'])) {
                $templates[$template_data['template']] = $template_data;
            }
        }
        
        return $templates;
    }
}