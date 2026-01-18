<?php
/**
 * ShopGlut Default Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme setup
function shopglut_default_setup() {
    // Add theme support for various features
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'custom-header' );
    add_theme_support( 'custom-background' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register navigation menus
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Primary Menu', 'shopglut' ),
    ) );

    // Set content width
    if ( ! isset( $content_width ) ) {
        $content_width = 800;
    }
}
add_action( 'after_setup_theme', 'shopglut_default_setup' );

// Enqueue scripts and styles
function shopglut_default_scripts() {
    wp_enqueue_style( 'shopglut-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'shopglut_default_scripts' );

// Register widget areas
function shopglut_default_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'shopglut' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'shopglut' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    // Footer widget areas
    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar( array(
            // translators: %d is the footer widget area number
            'name'          => sprintf( esc_html__( 'Footer %d', 'shopglut' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'shopglut' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'shopglut_default_widgets_init' );

// Default menu fallback
function shopglut_default_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'shopglut' ) . '</a></li>';
    if ( class_exists( 'WooCommerce' ) ) {
        echo '<li><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Shop', 'shopglut' ) . '</a></li>';
    }
    echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'shopglut' ) . '</a></li>';
    echo '</ul>';
}

// WooCommerce customizations
if ( class_exists( 'WooCommerce' ) ) {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    function shopglut_default_woocommerce_wrapper_start() {
        echo '<div class="container"><div class="site-content clearfix"><main class="content-area">';
    }
    add_action( 'woocommerce_before_main_content', 'shopglut_default_woocommerce_wrapper_start', 10 );

    function shopglut_default_woocommerce_wrapper_end() {
        echo '</main>';
        get_sidebar();
        echo '</div></div>';
    }
    add_action( 'woocommerce_after_main_content', 'shopglut_default_woocommerce_wrapper_end', 10 );
}