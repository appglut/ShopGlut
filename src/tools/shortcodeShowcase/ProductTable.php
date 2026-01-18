<?php
namespace Shopglut\shortcodeShowcase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductTable {
    /**
     * Constructor to initialize the shortcode
     */
    public function __construct() {
        // Register the shortcode
        add_shortcode('shopglut_product_table', array($this, 'render_product_table'));
        
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
    }
    
    /**
     * Register required scripts and styles for the product table
     */
    public function register_scripts() {
       
    }
    
    /**
     * Render the product table shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output of the product table
     */
    public function render_product_table($atts) {
        
    }
    
    /**
     * Get instance of the class
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}