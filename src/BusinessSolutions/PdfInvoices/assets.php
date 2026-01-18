<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Asset registration for PdfInvoices
 */

class ShopGlut_PdfInvoicesAssets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    public function enqueue_frontend_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        
        // Enqueue CSS
        if (file_exists(__DIR__ . '/assets/style.css')) {
            wp_enqueue_style(
                'PdfInvoices-style',
                $plugin_url . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );
        }
        
        // Enqueue JS
        if (file_exists(__DIR__ . '/assets/script.js')) {
            wp_enqueue_script(
                'PdfInvoices-script',
                $plugin_url . 'assets/script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/script.js'),
                true
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        // Only load on PDF invoices admin pages
        if (strpos($hook, 'shopglut_pdf_invoices') === false) {
            return;
        }
        
        $plugin_url = plugin_dir_url(__FILE__);
        
        // PDF Invoices admin CSS
        if (file_exists(__DIR__ . '/assets/pdf-invoices-admin.css')) {
            wp_enqueue_style(
                'shopglut-pdf-invoices-admin',
                $plugin_url . 'assets/pdf-invoices-admin.css',
                [],
                filemtime(__DIR__ . '/assets/pdf-invoices-admin.css')
            );
        }
        
        // Generic admin CSS
        if (file_exists(__DIR__ . '/assets/admin-style.css')) {
            wp_enqueue_style(
                'PdfInvoices-admin-style',
                $plugin_url . 'assets/admin-style.css',
                [],
                filemtime(__DIR__ . '/assets/admin-style.css')
            );
        }
        
        // PDF Invoices admin JS
        if (file_exists(__DIR__ . '/assets/pdf-invoices-admin.js')) {
            wp_enqueue_script(
                'shopglut-pdf-invoices-admin',
                $plugin_url . 'assets/pdf-invoices-admin.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/pdf-invoices-admin.js'),
                true
            );
        }
        
        // Generic admin JS
        if (file_exists(__DIR__ . '/assets/admin-script.js')) {
            wp_enqueue_script(
                'PdfInvoices-admin-script',
                $plugin_url . 'assets/admin-script.js',
                ['jquery'],
                filemtime(__DIR__ . '/assets/admin-script.js'),
                true
            );
        }
    }
}

// Initialize the assets class
new ShopGlut_PdfInvoicesAssets();
