<?php
/**
 * ModernStore Header Template
 *
 * @package ShopGlut
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>

                <h1 class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </h1>

                <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
                ?>
                    <p class="site-description"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => 'shopglut_modern_store_default_menu',
                ) );
                ?>
            </nav>

            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <div class="header-cart">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                        <span class="cart-total"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div id="content" class="site-content">
