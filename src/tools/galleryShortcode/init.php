<?php
/**
 * Gallery Shortcode Module Initialization
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Module autoloader
 */
spl_autoload_register(function ($class_name) {
    // Only handle classes in our namespace
    if (strpos($class_name, __NAMESPACE__) !== 0) {
        return;
    }

    // Convert namespace to file path
    $class_file = str_replace(__NAMESPACE__ . '\\', '', $class_name);
    $class_file = str_replace('\\', '/', $class_file);
    $class_file = __DIR__ . '/' . strtolower($class_file) . '.php';

    if (file_exists($class_file)) {
        require_once $class_file;
    }
});

// Initialize the module
function init_gallery_shortcode_module() {
    // Check if module is enabled
    $module_manager = \Shopglut\ModuleManager::get_instance();

    if (!$module_manager->is_module_enabled('gallery_shortcode')) {
        return;
    }

    // Get main instances
    $gallery_shortcode = GalleryShortcode::get_instance();
    $gallery_settings = GallerySettings::get_instance();
    $gallery_data_manager = GalleryDataManager::get_instance();

    // Initialize admin functionality
    if (is_admin()) {
        $gallery_admin = GalleryAdmin::get_instance();
    }
}
add_action('plugins_loaded', __NAMESPACE__ . '\\init_gallery_shortcode_module');

// Register activation hook for creating tables
register_activation_hook(SHOPGLUT_FILE, function() {
    require_once(__DIR__ . '/GalleryDataTables.php');
    GalleryDataTables::create_tables();
});