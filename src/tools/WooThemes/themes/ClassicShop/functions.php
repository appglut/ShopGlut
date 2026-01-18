<?php
/**
 * ShopGlut Classic Shop Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme setup
function shopglut_classic_shop_setup() {
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
add_action( 'after_setup_theme', 'shopglut_classic_shop_setup' );

// Enqueue scripts and styles
function shopglut_classic_shop_scripts() {
    // Theme stylesheet
    wp_enqueue_style( 'shopglut-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    
    // Google Fonts - Removed external resource loading for WordPress.org compliance
    // wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:ital,wght@0,400;0,500;1,400&display=swap', array(), '1.0.0' );
    
    // Font Awesome for icons - Removed external resource loading for WordPress.org compliance
    // wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0' );
    
    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'shopglut_classic_shop_scripts' );

// Register widget areas
function shopglut_classic_shop_widgets_init() {
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
add_action( 'widgets_init', 'shopglut_classic_shop_widgets_init' );

// Default menu fallback
function shopglut_classic_shop_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'shopglut' ) . '</a></li>';
    if ( class_exists( 'WooCommerce' ) ) {
        echo '<li><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Shop', 'shopglut' ) . '</a></li>';
    }
    echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'shopglut' ) . '</a></li>';
    echo '</ul>';
}

// Custom excerpt length
function shopglut_classic_shop_excerpt_length( $length ) {
    return 35;
}
add_filter( 'excerpt_length', 'shopglut_classic_shop_excerpt_length', 999 );

// WooCommerce customizations
if ( class_exists( 'WooCommerce' ) ) {
    // Remove default WooCommerce wrapper
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    // Add custom WooCommerce wrapper
    function shopglut_classic_shop_woocommerce_wrapper_start() {
        echo '<div class="container"><div class="site-content clearfix"><main class="content-area">';
    }
    add_action( 'woocommerce_before_main_content', 'shopglut_classic_shop_woocommerce_wrapper_start', 10 );

    function shopglut_classic_shop_woocommerce_wrapper_end() {
        echo '</main>';
        get_sidebar();
        echo '</div></div>';
    }
    add_action( 'woocommerce_after_main_content', 'shopglut_classic_shop_woocommerce_wrapper_end', 10 );
}

// Customizer settings
function shopglut_classic_shop_customize_register( $wp_customize ) {
    // Add custom colors section
    $wp_customize->add_section( 'shopglut_classic_colors', array(
        'title'    => __( 'Theme Colors', 'shopglut' ),
        'priority' => 30,
    ) );

    // Accent color
    $wp_customize->add_setting( 'accent_color', array(
        'default'           => '#d4a574',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'label'    => __( 'Accent Color', 'shopglut' ),
        'section'  => 'shopglut_classic_colors',
        'settings' => 'accent_color',
    ) ) );
}
add_action( 'customize_register', 'shopglut_classic_shop_customize_register' );

// Output custom colors
function shopglut_classic_shop_custom_colors() {
    $accent_color = get_theme_mod( 'accent_color', '#d4a574' );
    
    if ( $accent_color !== '#d4a574' ) {
        ?>
        <style type="text/css">
            .site-header {
                border-bottom-color: <?php echo esc_attr( $accent_color ); ?> !important;
            }
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button {
                background-color: <?php echo esc_attr( $accent_color ); ?> !important;
                border-color: <?php echo esc_attr( $accent_color ); ?> !important;
            }
            .entry-title::after,
            .widget-title::after,
            .footer-widget h3::after {
                background-color: <?php echo esc_attr( $accent_color ); ?> !important;
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'shopglut_classic_shop_custom_colors' );