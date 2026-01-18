<?php
/**
 * ModernStore Footer Template
 *
 * @package ShopGlut
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-widgets">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="footer-widgets">
                    <div class="footer-widget">
                        <h3><?php echo esc_html__( 'About Us', 'shopglut' ); ?></h3>
                        <p><?php echo esc_html__( 'Welcome to our modern store! We offer high-quality products with exceptional customer service.', 'shopglut' ); ?></p>
                    </div>
                    <div class="footer-widget">
                        <h3><?php echo esc_html__( 'Quick Links', 'shopglut' ); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html__( 'Home', 'shopglut' ); ?></a></li>
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html__( 'Shop', 'shopglut' ); ?></a></li>
                                <li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>"><?php echo esc_html__( 'Cart', 'shopglut' ); ?></a></li>
                                <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php echo esc_html__( 'My Account', 'shopglut' ); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="footer-widget">
                        <h3><?php echo esc_html__( 'Contact Info', 'shopglut' ); ?></h3>
                        <p><?php echo esc_html__( 'Email: info@yourstore.com', 'shopglut' ); ?></p>
                        <p><?php echo esc_html__( 'Phone: (555) 123-4567', 'shopglut' ); ?></p>
                        <p><?php echo esc_html__( 'Address: 123 Store Street, City, State 12345', 'shopglut' ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="site-info">
                <p>
                    &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                    <?php echo esc_html__( 'All rights reserved.', 'shopglut' ); ?>
                </p>
                <p>
                    <?php echo esc_html__( 'Powered by', 'shopglut' ); ?>
                    <a href="https://shopglut.com" target="_blank">ShopGlut</a>
                    <?php echo esc_html__( '&amp;', 'shopglut' ); ?>
                    <a href="https://wordpress.org" target="_blank">WordPress</a>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
