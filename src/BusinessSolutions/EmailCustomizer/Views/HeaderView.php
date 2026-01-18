<?php
namespace Shopglut\BusinessSolutions\EmailCustomizer\Views;

class HeaderView {
    
    public static function render() {
        $logo_url = SHOPGLUT_URL . 'global-assets/images/header-logo.svg';
        ?>
        <div class="shopglut-page-header">
            <div class="shopglut-page-header-wrap">
                <div class="shopglut-page-header-banner shopglut-pro shopglut-no-submenu">
                    <div class="shopglut-page-header-banner__logo">
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="">
                    </div>
                    <div class="shopglut-page-header-banner__helplinks">
                        <span><a rel="noopener"
                                href="https://shopglut.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=adminmenu"
                                target="_blank">
                                <span class="dashicons dashicons-admin-page"></span>
                                <?php echo esc_html__( 'Documentation', 'shopglut' ); ?>
                            </a></span>
                        <span><a class="shopglut-active" rel="noopener"
                                href="https://www.appglut.com/plugin/shopglut/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=upgrade"
                                target="_blank">
                                <span class="dashicons dashicons-unlock"></span>
                                <?php echo esc_html__( 'Unlock Pro Edition', 'shopglut' ); ?>
                            </a></span>
                        <span><a rel="noopener"
                                href="https://www.appglut.com/support/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=support"
                                target="_blank">
                                <span class="dashicons dashicons-share-alt"></span>
                                <?php echo esc_html__( 'Support', 'shopglut' ); ?>
                            </a></span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <?php
    }
}