<?php
/**
 * Gallery Settings Class
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GallerySettings {

    /**
     * Single instance of the class
     *
     * @var GallerySettings
     */
    private static $instance = null;

    /**
     * Settings page slug
     *
     * @var string
     */
    private $settings_slug = 'shopglut-gallery-settings';

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_settings_scripts']);
    }

    /**
     * Get single instance of the class
     *
     * @return GallerySettings
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add settings menu
     */
    public function add_settings_menu() {
        add_submenu_page(
            'shopglut',
            __('Gallery Settings', 'shopglut'),
            __('Gallery Settings', 'shopglut'),
            'manage_options',
            $this->settings_slug,
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Register settings group
        register_setting(
            'shopglut_gallery_settings',
            'shopglut_gallery_options',
            [$this, 'sanitize_settings']
        );

        // General settings section
        add_settings_section(
            'shopglut_gallery_general',
            __('General Settings', 'shopglut'),
            [$this, 'general_section_callback'],
            'shopglut_gallery_settings'
        );

        add_settings_field(
            'default_layout',
            __('Default Layout', 'shopglut'),
            [$this, 'layout_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_general'
        );

        add_settings_field(
            'default_columns',
            __('Default Columns', 'shopglut'),
            [$this, 'columns_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_general'
        );

        add_settings_field(
            'enable_lazy_loading',
            __('Enable Lazy Loading', 'shopglut'),
            [$this, 'checkbox_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_general',
            [
                'name' => 'enable_lazy_loading',
                'description' => __('Enable lazy loading for images by default', 'shopglut')
            ]
        );

        // Performance settings section
        add_settings_section(
            'shopglut_gallery_performance',
            __('Performance Settings', 'shopglut'),
            [$this, 'performance_section_callback'],
            'shopglut_gallery_settings'
        );

        add_settings_field(
            'image_size',
            __('Gallery Image Size', 'shopglut'),
            [$this, 'image_size_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_performance'
        );

        add_settings_field(
            'enable_caching',
            __('Enable Caching', 'shopglut'),
            [$this, 'checkbox_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_performance',
            [
                'name' => 'enable_caching',
                'description' => __('Cache gallery output for better performance', 'shopglut')
            ]
        );

        add_settings_field(
            'cache_duration',
            __('Cache Duration', 'shopglut'),
            [$this, 'cache_duration_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_performance'
        );

        // Styling settings section
        add_settings_section(
            'shopglut_gallery_styling',
            __('Styling Settings', 'shopglut'),
            [$this, 'styling_section_callback'],
            'shopglut_gallery_settings'
        );

        add_settings_field(
            'custom_css',
            __('Custom CSS', 'shopglut'),
            [$this, 'textarea_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_styling',
            [
                'name' => 'custom_css',
                'description' => __('Custom CSS for all galleries', 'shopglut'),
                'rows' => 10
            ]
        );

        add_settings_field(
            'disable_default_styles',
            __('Disable Default Styles', 'shopglut'),
            [$this, 'checkbox_field_callback'],
            'shopglut_gallery_settings',
            'shopglut_gallery_styling',
            [
                'name' => 'disable_default_styles',
                'description' => __('Disable default gallery styles (useful for theme integration)', 'shopglut')
            ]
        );
    }

    /**
     * Sanitize settings
     *
     * @param array $input Raw input
     * @return array Sanitized input
     */
    public function sanitize_settings($input) {
        $sanitized = [];

        // General settings
        $sanitized['default_layout'] = sanitize_text_field($input['default_layout']);
        $sanitized['default_columns'] = intval($input['default_columns']);
        $sanitized['enable_lazy_loading'] = isset($input['enable_lazy_loading']) ? '1' : '0';

        // Performance settings
        $sanitized['image_size'] = sanitize_text_field($input['image_size']);
        $sanitized['enable_caching'] = isset($input['enable_caching']) ? '1' : '0';
        $sanitized['cache_duration'] = absint($input['cache_duration']);

        // Styling settings
        $sanitized['custom_css'] = wp_kses_post($input['custom_css']);
        $sanitized['disable_default_styles'] = isset($input['disable_default_styles']) ? '1' : '0';

        return $sanitized;
    }

    /**
     * Enqueue settings scripts
     */
    public function enqueue_settings_scripts($hook) {
        if (strpos($hook, $this->settings_slug) === false) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap shopglut-gallery-settings">
            <h1><?php esc_html_e('Gallery Settings', 'shopglut'); ?></h1>

            <form action="options.php" method="post">
                <?php
                settings_fields('shopglut_gallery_settings');
                do_settings_sections('shopglut_gallery_settings');
                submit_button();
                ?>
            </form>

            <div class="shopglut-gallery-settings-info">
                <h2><?php esc_html_e('Information', 'shopglut'); ?></h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h3><?php esc_html_e('Shortcode Usage', 'shopglut'); ?></h3>
                        <p><?php esc_html_e('Use the following shortcode to display galleries:', 'shopglut'); ?></p>
                        <code>[shopglut_gallery id="1"]</code>
                        <p><?php esc_html_e('Or create a new gallery from the Gallery Shortcodes menu.', 'shopglut'); ?></p>
                    </div>
                    <div class="info-card">
                        <h3><?php esc_html_e('Available Parameters', 'shopglut'); ?></h3>
                        <ul>
                            <li><code>layout</code> - <?php esc_html_e('Grid, isotope, carousel, masonry', 'shopglut'); ?></li>
                            <li><code>columns</code> - <?php esc_html_e('Number of columns (1-6)', 'shopglut'); ?></li>
                            <li><code>filter</code> - <?php esc_html_e('Enable filtering (yes/no)', 'shopglut'); ?></li>
                            <li><code>items_per_page</code> - <?php esc_html_e('Products per page', 'shopglut'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .shopglut-gallery-settings .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .shopglut-gallery-settings .info-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            padding: 20px;
            border-radius: 4px;
        }

        .shopglut-gallery-settings .info-card h3 {
            margin-top: 0;
        }

        .shopglut-gallery-settings .info-card code {
            background: #f0f0f1;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .shopglut-gallery-settings .info-card ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        </style>
        <?php
    }

    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>' . esc_html__('Configure the default settings for your galleries.', 'shopglut') . '</p>';
    }

    /**
     * Performance section callback
     */
    public function performance_section_callback() {
        echo '<p>' . esc_html__('Optimize gallery performance and loading speed.', 'shopglut') . '</p>';
    }

    /**
     * Styling section callback
     */
    public function styling_section_callback() {
        echo '<p>' . esc_html__('Customize the appearance of your galleries.', 'shopglut') . '</p>';
    }

    /**
     * Layout field callback
     */
    public function layout_field_callback() {
        $options = get_option('shopglut_gallery_options');
        $default_layout = $options['default_layout'] ?? 'grid';

        $layouts = [
            'grid' => __('Grid', 'shopglut'),
            'isotope' => __('Isotope (Filterable)', 'shopglut'),
            'carousel' => __('Carousel', 'shopglut'),
            'masonry' => __('Masonry', 'shopglut'),
        ];

        echo '<select name="shopglut_gallery_options[default_layout]">';
        foreach ($layouts as $value => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected($default_layout, $value, false),
                esc_html($label)
            );
        }
        echo '</select>';
    }

    /**
     * Columns field callback
     */
    public function columns_field_callback() {
        $options = get_option('shopglut_gallery_options');
        $default_columns = $options['default_columns'] ?? 3;

        echo '<select name="shopglut_gallery_options[default_columns]">';
        for ($i = 1; $i <= 6; $i++) {
            printf(
                '<option value="%d" %s>%d</option>',
                esc_attr($i),
                selected($default_columns, $i, false),
                esc_html($i)
            );
        }
        echo '</select>';
    }

    /**
     * Image size field callback
     */
    public function image_size_field_callback() {
        $options = get_option('shopglut_gallery_options');
        $image_size = $options['image_size'] ?? 'woocommerce_thumbnail';

        $image_sizes = get_intermediate_image_sizes();
        echo '<select name="shopglut_gallery_options[image_size]">';
        foreach ($image_sizes as $size) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($size),
                selected($image_size, $size, false),
                esc_html(ucfirst(str_replace('_', ' ', $size)))
            );
        }
        echo '</select>';
    }

    /**
     * Cache duration field callback
     */
    public function cache_duration_field_callback() {
        $options = get_option('shopglut_gallery_options');
        $cache_duration = $options['cache_duration'] ?? 3600;

        echo '<input type="number" name="shopglut_gallery_options[cache_duration]" value="' . esc_attr($cache_duration) . '" min="60" max="86400" class="small-text">';
        echo ' <span class="description">' . esc_html__('seconds (1 hour = 3600)', 'shopglut') . '</span>';
    }

    /**
     * Checkbox field callback
     *
     * @param array $args Field arguments
     */
    public function checkbox_field_callback($args) {
        $options = get_option('shopglut_gallery_options');
        $name = $args['name'];
        $value = $options[$name] ?? 0;

        printf(
            '<input type="checkbox" name="shopglut_gallery_options[%s]" value="1" %s>',
            esc_attr($name),
            checked(1, $value, false)
        );

        if (!empty($args['description'])) {
            echo ' <span class="description">' . esc_html($args['description']) . '</span>';
        }
    }

    /**
     * Textarea field callback
     *
     * @param array $args Field arguments
     */
    public function textarea_field_callback($args) {
        $options = get_option('shopglut_gallery_options');
        $name = $args['name'];
        $value = $options[$name] ?? '';
        $rows = $args['rows'] ?? 5;

        printf(
            '<textarea name="shopglut_gallery_options[%s]" rows="%d" class="large-text">%s</textarea>',
            esc_attr($name),
            intval($rows),
            esc_textarea($value)
        );

        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }

    /**
     * Get gallery option
     *
     * @param string $key Option key
     * @param mixed $default Default value
     * @return mixed Option value
     */
    public static function get_option($key, $default = null) {
        $options = get_option('shopglut_gallery_options', []);
        return $options[$key] ?? $default;
    }

    /**
     * Get default gallery settings
     *
     * @return array Default settings
     */
    public static function get_default_settings() {
        return [
            'layout' => self::get_option('default_layout', 'grid'),
            'columns' => self::get_option('default_columns', 3),
            'columns_tablet' => 2,
            'columns_mobile' => 1,
            'spacing' => 'medium',
            'enable_filter' => 'yes',
            'filter_position' => 'top',
            'pagination_type' => 'yes',
            'items_per_page' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
            'lazy_load' => self::get_option('enable_lazy_loading', '1') === '1',
            'show_price' => 'yes',
            'show_title' => 'yes',
            'show_category' => 'yes',
            'show_rating' => 'yes',
            'show_add_to_cart' => 'yes',
            'hover_effect' => 'zoom',
            'animation' => 'fadeIn',
        ];
    }
}