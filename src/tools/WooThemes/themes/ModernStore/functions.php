<?php
/**
 * ShopGlut Modern Store Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme setup
function shopglut_modern_store_setup() {
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
add_action( 'after_setup_theme', 'shopglut_modern_store_setup' );

// Enqueue scripts and styles
function shopglut_modern_store_scripts() {
    // Theme stylesheet
    wp_enqueue_style( 'shopglut-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    
    // Font Awesome for icons - Removed external resource loading for WordPress.org compliance
    // wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0' );
    
    // Theme JavaScript
    wp_enqueue_script( 'shopglut-script', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
    
    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'shopglut_modern_store_scripts' );

// Register widget areas
function shopglut_modern_store_widgets_init() {
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
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'shopglut' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'shopglut' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'shopglut' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'shopglut' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'shopglut' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'shopglut' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'shopglut_modern_store_widgets_init' );

// Default menu fallback
function shopglut_modern_store_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'shopglut' ) . '</a></li>';
    if ( class_exists( 'WooCommerce' ) ) {
        echo '<li><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Shop', 'shopglut' ) . '</a></li>';
    }
    echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'shopglut' ) . '</a></li>';
    echo '</ul>';
}

// Custom excerpt length
function shopglut_modern_store_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'shopglut_modern_store_excerpt_length', 999 );

// Custom excerpt more
function shopglut_modern_store_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'shopglut_modern_store_excerpt_more' );

// WooCommerce customizations
if ( class_exists( 'WooCommerce' ) ) {
    // Remove default WooCommerce wrapper
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    // Add custom WooCommerce wrapper
    function shopglut_modern_store_woocommerce_wrapper_start() {
        echo '<div class="container"><div class="site-content clearfix"><main class="content-area">';
    }
    add_action( 'woocommerce_before_main_content', 'shopglut_modern_store_woocommerce_wrapper_start', 10 );

    function shopglut_modern_store_woocommerce_wrapper_end() {
        echo '</main>';
        get_sidebar();
        echo '</div></div>';
    }
    add_action( 'woocommerce_after_main_content', 'shopglut_modern_store_woocommerce_wrapper_end', 10 );

    // Change number of products per row
    function shopglut_modern_store_products_per_row() {
        return 3;
    }
    add_filter( 'loop_shop_columns', 'shopglut_modern_store_products_per_row' );
}

// Customizer settings
function shopglut_modern_store_customize_register( $wp_customize ) {
    // Add custom colors section
    $wp_customize->add_section( 'shopglut_colors', array(
        'title'    => __( 'Theme Colors', 'shopglut' ),
        'priority' => 30,
    ) );

    // Primary color
    $wp_customize->add_setting( 'primary_color', array(
        'default'           => '#667eea',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
        'label'    => __( 'Primary Color', 'shopglut' ),
        'section'  => 'shopglut_colors',
        'settings' => 'primary_color',
    ) ) );

    // Secondary color
    $wp_customize->add_setting( 'secondary_color', array(
        'default'           => '#764ba2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
        'label'    => __( 'Secondary Color', 'shopglut' ),
        'section'  => 'shopglut_colors',
        'settings' => 'secondary_color',
    ) ) );
}
add_action( 'customize_register', 'shopglut_modern_store_customize_register' );

// Output custom colors
function shopglut_modern_store_custom_colors() {
    $primary_color = get_theme_mod( 'primary_color', '#667eea' );
    $secondary_color = get_theme_mod( 'secondary_color', '#764ba2' );
    
    if ( $primary_color !== '#667eea' || $secondary_color !== '#764ba2' ) {
        ?>
        <style type="text/css">
            .site-header {
                background: linear-gradient(135deg, <?php echo esc_attr( $primary_color ); ?> 0%, <?php echo esc_attr( $secondary_color ); ?> 100%) !important;
            }
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button {
                background: linear-gradient(135deg, <?php echo esc_attr( $primary_color ); ?> 0%, <?php echo esc_attr( $secondary_color ); ?> 100%) !important;
            }
            .entry-title a:hover {
                color: <?php echo esc_attr( $primary_color ); ?> !important;
            }
            .widget-title {
                border-bottom-color: <?php echo esc_attr( $primary_color ); ?> !important;
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'shopglut_modern_store_custom_colors' );

// Add editor styles
function shopglut_modern_store_add_editor_styles() {
    add_editor_style( get_stylesheet_uri() );
}
add_action( 'admin_init', 'shopglut_modern_store_add_editor_styles' );