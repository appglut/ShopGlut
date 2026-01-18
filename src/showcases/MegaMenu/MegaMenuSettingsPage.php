<?php
namespace Shopglut\showcases\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MegaMenuSettingsPage {

    public $menu_slug = 'megamenu';

    public function __construct() {

    }

    public function loadMegaMenuEditor() {
        $layout_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin context with parameter validation

        if ($layout_id === 0) {
            ?>
            <div class="wrap">
                <p><?php esc_html_e( 'Invalid layout ID.', 'shopglut' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ) ); ?>" class="button">
                    <?php esc_html_e( 'Back to Mega Menu', 'shopglut' ); ?>
                </a>
            </div>
            <?php
            return;
        }

        $loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

        do_action( 'save_shopg_layout_data', $layout_id );
        do_action( 'shopglut_layout_metaboxes', 'shopglut' );

        // Get layout data using our entity
        $layout_data = MegaMenuEntity::retrieve($layout_id);

        if ( $layout_data ) {
            $layout_name = $layout_data['menu_name'];
            $layout_template = $layout_data['menu_template'];
            $layout_settings = json_decode($layout_data['menu_settings'], true) ?: [];
        } else {
            ?>
            <div class="wrap">
                <p><?php esc_html_e( 'No layout data found.', 'shopglut' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ) ); ?>" class="button">
                    <?php esc_html_e( 'Back to Mega Menu', 'shopglut' ); ?>
                </a>
            </div>
            <?php
            return;
        }

        // Extract settings with defaults
        $general = $layout_settings['general'] ?? [];
        $colors = $layout_settings['colors'] ?? [];
        $layout = $layout_settings['layout'] ?? [];
        $content = $layout_settings['content'] ?? [];
        $typography = $layout_settings['typography'] ?? [];
        $advanced = $layout_settings['advanced'] ?? [];

        ?>
        <div id="shopg-layout-admin-settings" class="wrap layout_settings">

            <div class="loader-overlay" style="display: flex; opacity: 1;">
                <div class="loader-container">
                    <img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
                    <div class="loader-dash-circle"></div>
                </div>
            </div>

            <form id="shopglut_mega_menu_layout" method="post" action="">

                <?php
                $shopg_megamenu_nonce = wp_create_nonce( 'shopg_megamenu_layouts' );
                ?>
                <input type="hidden" name="shopg_megamenu_layouts_nonce" value="<?php echo esc_attr( $shopg_megamenu_nonce ); ?>">
                <input type="hidden" name="shopg_megamenu_layoutid" id="shopg_megamenu_layoutid"
                    value="<?php echo esc_attr( $layout_id ); ?>">

                <div class="shopglut_layout_contents">

                    <div class="back-to-menu">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ) ); ?>"
                            class="button button-secondary button-large">
                            <i class="fa-solid fa-angles-left"></i>
                            <?php echo esc_html__( 'Back To Mega Menu', 'shopglut' ); ?>
                        </a>
                        <div class="clear"></div>
                    </div>

                    <div class="shopglut_layout_name">
                        <label for="layout_name"><?php esc_html_e( 'Menu Name:', 'shopglut' ); ?></label>
                        <input type="text" id="layout_name" name="layout_name"
                            value="<?php echo esc_html( $layout_name ); ?>" />
                        <input type="hidden" id="layout_template" name="layout_template"
                            value="<?php echo esc_html( $layout_template ); ?>" />
                    </div>

                    <div id="shopg-notification-container"></div>
                </div>

                <div class="mega-menu-editor-content">
                    <div class="editor-grid">
                        <!-- Left Sidebar Settings -->
                        <div class="editor-sidebar">
                            <div class="settings-section">
                                <h3><?php esc_html_e( 'General Settings', 'shopglut' ); ?></h3>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Menu Location', 'shopglut' ); ?></label>
                                    <select name="menu_location">
                                        <option value="primary" <?php selected($general['menu_location'] ?? '', 'primary'); ?>><?php esc_html_e( 'Primary Menu', 'shopglut' ); ?></option>
                                        <option value="secondary" <?php selected($general['menu_location'] ?? '', 'secondary'); ?>><?php esc_html_e( 'Secondary Menu', 'shopglut' ); ?></option>
                                        <option value="custom" <?php selected($general['menu_location'] ?? '', 'custom'); ?>><?php esc_html_e( 'Custom Menu', 'shopglut' ); ?></option>
                                    </select>
                                </div>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Trigger Method', 'shopglut' ); ?></label>
                                    <select name="trigger_method">
                                        <option value="hover" <?php selected($general['trigger_method'] ?? '', 'hover'); ?>><?php esc_html_e( 'On Hover', 'shopglut' ); ?></option>
                                        <option value="click" <?php selected($general['trigger_method'] ?? '', 'click'); ?>><?php esc_html_e( 'On Click', 'shopglut' ); ?></option>
                                        <option value="both" <?php selected($general['trigger_method'] ?? '', 'both'); ?>><?php esc_html_e( 'Both', 'shopglut' ); ?></option>
                                    </select>
                                </div>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Animation', 'shopglut' ); ?></label>
                                    <select name="animation">
                                        <option value="fade" <?php selected($general['animation'] ?? '', 'fade'); ?>><?php esc_html_e( 'Fade', 'shopglut' ); ?></option>
                                        <option value="slide" <?php selected($general['animation'] ?? '', 'slide'); ?>><?php esc_html_e( 'Slide', 'shopglut' ); ?></option>
                                        <option value="none" <?php selected($general['animation'] ?? '', 'none'); ?>><?php esc_html_e( 'None', 'shopglut' ); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="settings-section">
                                <h3><?php esc_html_e( 'Layout Settings', 'shopglut' ); ?></h3>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Columns', 'shopglut' ); ?></label>
                                    <select name="columns">
                                        <option value="2" <?php selected($layout['columns'] ?? '', '2'); ?>>2 Columns</option>
                                        <option value="3" <?php selected($layout['columns'] ?? '', '3'); ?>>3 Columns</option>
                                        <option value="4" <?php selected($layout['columns'] ?? '', '4'); ?>>4 Columns</option>
                                        <option value="5" <?php selected($layout['columns'] ?? '', '5'); ?>>5 Columns</option>
                                    </select>
                                </div>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Menu Width', 'shopglut' ); ?></label>
                                    <input type="number" name="menu_width" value="<?php echo esc_attr($layout['menu_width'] ?? '800'); ?>" min="400" max="1200">
                                </div>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Border Radius', 'shopglut' ); ?></label>
                                    <input type="text" name="border_radius" value="<?php echo esc_attr($layout['border_radius'] ?? '8px'); ?>">
                                </div>
                            </div>

                            <div class="settings-section">
                                <h3><?php esc_html_e( 'Content Settings', 'shopglut' ); ?></h3>

                                <div class="setting-row">
                                    <label>
                                        <input type="checkbox" name="show_images" value="1" <?php checked($content['show_images'] ?? '', '1'); ?>>
                                        <?php esc_html_e( 'Show Category Images', 'shopglut' ); ?>
                                    </label>
                                </div>

                                <div class="setting-row">
                                    <label>
                                        <input type="checkbox" name="show_product_count" value="1" <?php checked($content['show_product_count'] ?? '', '1'); ?>>
                                        <?php esc_html_e( 'Show Product Count', 'shopglut' ); ?>
                                    </label>
                                </div>

                                <div class="setting-row">
                                    <label>
                                        <input type="checkbox" name="show_descriptions" value="1" <?php checked($content['show_descriptions'] ?? '', '1'); ?>>
                                        <?php esc_html_e( 'Show Descriptions', 'shopglut' ); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content Area -->
                        <div class="editor-main">
                            <div class="settings-section">
                                <h3><?php esc_html_e( 'Color Settings', 'shopglut' ); ?></h3>

                                <div class="color-grid">
                                    <div class="color-setting">
                                        <label><?php esc_html_e( 'Primary Color', 'shopglut' ); ?></label>
                                        <input type="color" name="primary_color" value="<?php echo esc_attr($colors['primary_color'] ?? '#007cba'); ?>">
                                    </div>

                                    <div class="color-setting">
                                        <label><?php esc_html_e( 'Background Color', 'shopglut' ); ?></label>
                                        <input type="color" name="background_color" value="<?php echo esc_attr($colors['background_color'] ?? '#ffffff'); ?>">
                                    </div>

                                    <div class="color-setting">
                                        <label><?php esc_html_e( 'Text Color', 'shopglut' ); ?></label>
                                        <input type="color" name="text_color" value="<?php echo esc_attr($colors['text_color'] ?? '#333333'); ?>">
                                    </div>

                                    <div class="color-setting">
                                        <label><?php esc_html_e( 'Hover Color', 'shopglut' ); ?></label>
                                        <input type="color" name="hover_color" value="<?php echo esc_attr($colors['hover_color'] ?? '#005a87'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="settings-section">
                                <h3><?php esc_html_e( 'Typography', 'shopglut' ); ?></h3>

                                <div class="typography-grid">
                                    <div class="typo-setting">
                                        <label><?php esc_html_e( 'Font Family', 'shopglut' ); ?></label>
                                        <select name="font_family">
                                            <option value="inherit" <?php selected($typography['font_family'] ?? '', 'inherit'); ?>><?php esc_html_e( 'Inherit', 'shopglut' ); ?></option>
                                            <option value="Arial, sans-serif" <?php selected($typography['font_family'] ?? '', 'Arial, sans-serif'); ?>>Arial</option>
                                            <option value="'Helvetica Neue', Helvetica, sans-serif" <?php selected($typography['font_family'] ?? '', "'Helvetica Neue', Helvetica, sans-serif"); ?>>Helvetica</option>
                                            <option value="Georgia, serif" <?php selected($typography['font_family'] ?? '', 'Georgia, serif'); ?>>Georgia</option>
                                        </select>
                                    </div>

                                    <div class="typo-setting">
                                        <label><?php esc_html_e( 'Font Size', 'shopglut' ); ?></label>
                                        <input type="text" name="font_size" value="<?php echo esc_attr($typography['font_size'] ?? '14px'); ?>">
                                    </div>

                                    <div class="typo-setting">
                                        <label><?php esc_html_e( 'Font Weight', 'shopglut' ); ?></label>
                                        <select name="font_weight">
                                            <option value="normal" <?php selected($typography['font_weight'] ?? '', 'normal'); ?>><?php esc_html_e( 'Normal', 'shopglut' ); ?></option>
                                            <option value="bold" <?php selected($typography['font_weight'] ?? '', 'bold'); ?>><?php esc_html_e( 'Bold', 'shopglut' ); ?></option>
                                            <option value="600" <?php selected($typography['font_weight'] ?? '', '600'); ?>><?php esc_html_e( 'Semi-Bold', 'shopglut' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-section">
                                <h3><?php esc_html_e( 'Advanced Settings', 'shopglut' ); ?></h3>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Mobile Breakpoint', 'shopglut' ); ?></label>
                                    <input type="number" name="mobile_breakpoint" value="<?php echo esc_attr($advanced['mobile_breakpoint'] ?? '768'); ?>" min="300" max="1200">
                                    <small><?php esc_html_e( 'Screen width in pixels where mobile menu starts', 'shopglut' ); ?></small>
                                </div>

                                <div class="setting-row">
                                    <label><?php esc_html_e( 'Custom CSS', 'shopglut' ); ?></label>
                                    <textarea name="custom_css" rows="10" placeholder="/* Custom CSS rules here */"><?php echo esc_textarea($advanced['custom_css'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="editor-actions">
                        <button type="submit" class="button button-primary button-large">
                            <?php esc_html_e( 'Save Mega Menu', 'shopglut' ); ?>
                        </button>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_showcases&view=mega_menu' ) ); ?>" class="button button-secondary button-large">
                            <?php esc_html_e( 'Cancel', 'shopglut' ); ?>
                        </a>
                    </div>
                </div>

            </form>

        </div>

        <style>
        .shopglut_layout_contents {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }

        .back-to-menu {
            margin-bottom: 20px;
        }

        .shopglut_layout_name {
            margin-bottom: 20px;
        }

        .shopglut_layout_name label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        .shopglut_layout_name input[type="text"] {
            width: 100%;
            max-width: 400px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Editor Layout Styles */
        .mega-menu-editor-content {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            overflow: hidden;
        }

        .editor-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            min-height: 600px;
        }

        .editor-sidebar {
            background: #f8f9fa;
            border-right: 1px solid #e5e5e5;
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .editor-main {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .settings-section {
            margin-bottom: 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
        }

        .settings-section h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .setting-row {
            margin-bottom: 15px;
        }

        .setting-row:last-child {
            margin-bottom: 0;
        }

        .setting-row label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #1d2327;
        }

        .setting-row input[type="text"],
        .setting-row input[type="number"],
        .setting-row select,
        .setting-row textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .setting-row input[type="checkbox"] {
            margin-right: 8px;
        }

        .setting-row small {
            display: block;
            margin-top: 5px;
            color: #646970;
            font-size: 12px;
        }

        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .color-setting {
            text-align: center;
        }

        .color-setting input[type="color"] {
            width: 100%;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .typography-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .editor-actions {
            padding: 20px;
            border-top: 1px solid #ddd;
            background: #f8f9fa;
            text-align: left;
        }

        .editor-actions .button {
            margin-right: 10px;
        }

        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-container {
            text-align: center;
        }

        .loader-image {
            width: 32px;
            height: 32px;
        }

        /* Responsive styles */
        @media (max-width: 1200px) {
            .editor-grid {
                grid-template-columns: 1fr;
            }

            .editor-sidebar {
                border-right: none;
                border-bottom: 1px solid #e5e5e5;
                max-height: none;
            }

            .editor-main {
                max-height: none;
            }
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Hide loader when page is ready
            $('.loader-overlay').fadeOut(500);

            // Handle form submission
            $('#shopglut_mega_menu_layout').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var $submitButton = $(this).find('input[type="submit"], button[type="submit"]');

                // Show loading state
                $submitButton.prop('disabled', true).val('Saving...');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData + '&action=shopglut_save_mega_menu_layout',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('#shopg-notification-container').html(
                                '<div class="notice notice-success is-dismissible"><p>' +
                                response.data.message +
                                '</p></div>'
                            );

                            // Auto dismiss after 3 seconds
                            setTimeout(function() {
                                $('#shopg-notification-container .notice').fadeOut();
                            }, 3000);
                        } else {
                            // Show error message
                            $('#shopg-notification-container').html(
                                '<div class="notice notice-error is-dismissible"><p>' +
                                response.data.message +
                                '</p></div>'
                            );
                        }
                    },
                    error: function() {
                        // Show generic error message
                        $('#shopg-notification-container').html(
                            '<div class="notice notice-error is-dismissible"><p>' +
                            '<?php esc_html_e( "An error occurred while saving. Please try again.", "shopglut" ); ?>' +
                            '</p></div>'
                        );
                    },
                    complete: function() {
                        // Restore button state
                        $submitButton.prop('disabled', false).val('<?php esc_html_e( "Save Menu", "shopglut" ); ?>');
                    }
                });
            });

            // Handle dismissible notices
            $(document).on('click', '.notice-dismiss', function() {
                $(this).closest('.notice').fadeOut();
            });
        });
        </script>

        <?php
    }

    public static function get_instance() {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}