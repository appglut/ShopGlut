<?php
namespace Shopglut\tools\megaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Assets {

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontendEnqueueScripts'));
    }

    public function adminEnqueueScripts($hook) {
        // Only load on our specific admin page
        if (strpos($hook, 'shopglut') !== false) {
            // Enqueue admin styles
            wp_enqueue_style(
                'shopglut-mega-menu-admin',
                plugin_dir_url(__FILE__) . 'assets/css/mega-menu-admin.css',
                array(),
                '1.0.0'
            );

            // Enqueue admin scripts
            wp_enqueue_script(
                'shopglut-mega-menu-admin',
                plugin_dir_url(__FILE__) . 'assets/js/mega-menu-admin.js',
                array('jquery'),
                '1.0.0',
                true
            );

            // Localize script
            wp_localize_script('shopglut-mega-menu-admin', 'shopglutMegaMenu', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shopglut_mega_menu_nonce'),
                'strings' => array(
                    'confirm' => __('Are you sure?', 'shopglut'),
                    'loading' => __('Loading...', 'shopglut'),
                    'error' => __('Error occurred. Please try again.', 'shopglut'),
                    'saved' => __('Settings saved successfully!', 'shopglut'),
                    'customized' => __('Template customized successfully!', 'shopglut')
                )
            ));
        }
    }

    public function frontendEnqueueScripts() {
        $settings = get_option('shopglut_mega_menu_settings', array());

        // Only load if mega menu is enabled
        if (!empty($settings['enable_mega_menu'])) {
            // Enqueue frontend styles
            wp_enqueue_style(
                'shopglut-mega-menu-frontend',
                plugin_dir_url(__FILE__) . 'assets/css/mega-menu-frontend.css',
                array(),
                '1.0.0'
            );

            // Enqueue frontend scripts
            wp_enqueue_script(
                'shopglut-mega-menu-frontend',
                plugin_dir_url(__FILE__) . 'assets/js/mega-menu-frontend.js',
                array('jquery'),
                '1.0.0',
                true
            );

            // Pass settings to frontend
            wp_localize_script('shopglut-mega-menu-frontend', 'shopglutMegaMenuConfig', array(
                'enabled' => !empty($settings['enable_mega_menu']),
                'menuLocation' => !empty($settings['menu_location']) ? $settings['menu_location'] : 'primary',
                'triggerMethod' => !empty($settings['trigger_method']) ? $settings['trigger_method'] : 'hover',
                'animation' => !empty($settings['animation']) ? $settings['animation'] : 'fade',
                'selectedTemplate' => !empty($settings['selected_template']) ? $settings['selected_template'] : 'horizontal_dropdown',
                'customSettings' => !empty($settings['custom_settings']) ? $settings['custom_settings'] : array()
            ));
        }
    }
}

// Helper function to get default settings
function shopglut_get_default_mega_menu_settings() {
    return array(
        'enable_mega_menu' => 0,
        'selected_template' => '',
        'menu_location' => 'primary',
        'trigger_method' => 'hover',
        'animation' => 'fade',
        'custom_settings' => array()
    );
}