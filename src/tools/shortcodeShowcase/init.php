<?php
/**
 * Shortcode Showcase Module Initialization
 *
 * This file initializes the shortcodes on both frontend and admin
 * to make them available for use in pages and posts
 *
 * @package Shopglut
 */

namespace Shopglut\shortcodeShowcase;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize Shortcode Showcase shortcodes
 */
function init_shortcode_showcase() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Check if the module is enabled (if ModuleManager is available)
    if (class_exists('\Shopglut\ModuleManager')) {
        $module_manager = \Shopglut\ModuleManager::get_instance();
        if (!$module_manager->is_module_enabled('shortcode_showcase')) {
            return;
        }
    }

    // Initialize CategoryShortcode
    require_once __DIR__ . '/CategoryShortcode.php';
    CategoryShortcode::get_instance();

    // Initialize ProductDisplay for other shortcodes
    require_once __DIR__ . '/ProductDisplay.php';
    new ProductDisplay();

    // Initialize dataManage for additional shortcodes
    require_once __DIR__ . '/dataManage.php';
    dataManage::get_instance();
}

// Hook into WordPress init with priority 20 to ensure ModuleManager is loaded
add_action('init', __NAMESPACE__ . '\\init_shortcode_showcase', 20);
