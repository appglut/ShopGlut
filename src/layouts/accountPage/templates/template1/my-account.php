<?php
/**
 * My Account Page - Template1
 *
 * This template overrides the default WooCommerce my-account.php template
 *
 * @package Shopglut
 */

defined( 'ABSPATH' ) || exit;

// Get the layout ID (default to 1)
global $wpdb;
$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
$layout = $wpdb->get_row(
	"SELECT id, layout_template FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = 1 LIMIT 1"
);

if ( ! $layout ) {
	// Fallback to default WooCommerce template if no layout found
	wc_get_template( 'myaccount/my-account.php' );
	return;
}

$layout_id = $layout->id;
$template_name = $layout->layout_template;

// Check if template markup file exists
$markup_file = __DIR__ . '/' . $template_name . 'Markup.php';
if ( ! file_exists( $markup_file ) ) {
	// Fallback to default WooCommerce template
	wc_get_template( 'myaccount/my-account.php' );
	return;
}

// Include the markup file
require_once $markup_file;

// Get the markup class
$markup_class = 'Shopglut\\layouts\\accountPage\\templates\\' . $template_name . '\\' . $template_name . 'Markup';

if ( ! class_exists( $markup_class ) ) {
	// Fallback to default WooCommerce template
	wc_get_template( 'myaccount/my-account.php' );
	return;
}

// Initialize markup instance
$markup_instance = new $markup_class();

// Check if required method exists
if ( ! method_exists( $markup_instance, 'layout_render' ) ) {
	// Fallback to default WooCommerce template
	wc_get_template( 'myaccount/my-account.php' );
	return;
}

// Prepare template data for live mode
$template_data = array(
	'layout_id' => $layout_id,
	'is_demo' => false, // Live mode - not demo
);

// Render the template markup in live mode
$markup_instance->layout_render( $template_data );
