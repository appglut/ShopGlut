<?php
/*
 * Plugin Name: ShopGlut - Builder for WooCommerce
 * Description: Complete WooCommerce builder with 6 powerful modules including single product builder, wishlist, product custom fields, swatches, badges, and comparison for professional e-commerce sites
 * Version: 1.7.4
 * Author: AppGlut
 * Author URI: https://www.appglut.com
 * Plugin URI: https://wordpress.org/plugins/shopglut/
 * License: GPLv2 or later
 * Text Domain: shopglut
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) or die;

define( 'SHOPGLUT_NAME', 'ShopGlut' );
define( 'SHOPGLUT_VERSION', '1.7.4' );
define( 'SHOPGLUT_BASENAME', plugin_basename( __FILE__ ) );
define( 'SHOPGLUT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOPGLUT_URL', plugin_dir_url( __FILE__ ) );
define( 'SHOPGLUT_ADMIN_IMAGES', plugin_dir_url( __FILE__ ) . 'src/library/model/assets/images/' );
define( 'SHOPGLUT_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'SHOPGLUT_SLUG', dirname( plugin_basename( __FILE__ ) ) );

// Pro upgrade URLs
define( 'SHOPGLUT_PRICING_URL', 'https://appglut.com' );
define( 'SHOPGLUT_PRO_URL', 'https://appglut.com' );
define( 'SHOPGLUT_UPGRADE_URL', 'https://appglut.com' );

// Autoloader for class loading
require __DIR__ . '/autoloader.php';


// Hook into WooCommerce initialization
add_action( 'woocommerce_init', 'shopglut_plugin_initialize' );


function shopglut_plugin_initialize() {
	// Ensure that WooCommerce is loaded before proceeding
	if ( class_exists( 'WooCommerce' ) ) {
		// Run ShopGlut initialization
		Shopglut\ShopGlutBase::get_instance();
		// Initialize Module Manager
		Shopglut\ModuleManager::get_instance();

	}
}


