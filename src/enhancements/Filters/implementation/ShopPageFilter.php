<?php

namespace Shopglut\enhancements\Filters\implementation;


if (!class_exists('Shopglut\enhancements\Filters\implementation\ShopPageFilter')) {

/**
 * Shop Page Filter
 * Centralized filter rendering for WooCommerce shop page
 *
 * @since 1.0.0
 */
class ShopPageFilter {

    private $filter_id;
    private $filter_settings;

    /**
     * Class Constructor
     */
    public function __construct($filter_id, $filter_settings = []) {
        $this->filter_id = $filter_id;
        $this->filter_settings = $filter_settings;
    }

    /**
     * Check if should display on Woo Shop Page
     */
    public static function should_display($filter_settings) {
        if (empty($filter_settings)) return false;

        $show_on_pages = $filter_settings['shopg_filter_options_settings']['shopglut-filter-settings-main-tab']['filter-show-on-pages'] ?? [];

        return in_array('Woo Shop Page', $show_on_pages) && is_shop();
    }

    /**
     * Render filter
     */
    public function render() {
        if (!self::should_display($this->filter_settings)) {
            return '';
        }

        // Output centralized styles using FilterStyle
        if (class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
            $filter_style_handler = new \Shopglut\enhancements\Filters\implementation\FilterStyle($this->filter_id, $this->filter_settings);
            $style_filter = $filter_style_handler->output_styles();
        }

        // Simple filter HTML rendering
        ob_start();
        ?>
        <div class="shopglut-filter-container" data-filter-id="<?php echo esc_attr($this->filter_id); ?>">
            <?php echo wp_kses_post($this->render_filter_content()); ?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * Render filter content from settings (now uses FilterContent)
     */
    private function render_filter_content() {
        // Initialize FilterStyle for styling
        $filter_style_handler = null;
        if (class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
            $filter_style_handler = new \Shopglut\enhancements\Filters\implementation\FilterStyle($this->filter_id, $this->filter_settings);
        }

        // Initialize FilterContent for HTML generation
        $filter_content = new \Shopglut\enhancements\Filters\implementation\FilterContent($filter_style_handler, $this->filter_id, $this->filter_settings);
        
        // Generate frontend filter HTML
        $html = $filter_content->generate_filter_html(false);

        // Add action buttons
        $html .= $filter_content->render_action_buttons();

        return $html;
    }

    /**
     * Render preview for backend (returns HTML with inline styles)
     */
    public function render_preview() {
        ob_start();

        // Preview wrapper
        echo '<div class="shopglut-filter-preview-wrapper">';

        // Initialize FilterContent for HTML generation
        $filter_style_handler = null;
        if (class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
            $filter_style_handler = new \Shopglut\enhancements\Filters\implementation\FilterStyle($this->filter_id, $this->filter_settings);
            // Output styles once here
            $filter_style_handler->output_styles();
        }
        $filter_content = new \Shopglut\enhancements\Filters\implementation\FilterContent($filter_style_handler, $this->filter_id, $this->filter_settings);

        // Add preview wrapper and container
        echo '<div class="shopg-filter-live-preview shopglut-filter">';
        echo '<div class="shopglut-filter-container" data-filter-id="' . esc_attr($this->filter_id) . '">';

        // Render the filter content as preview
        echo wp_kses_post($filter_content->generate_filter_html(true));

        echo '</div>'; // End shopglut-filter-container
        echo '</div>'; // End shopg-filter-live-preview
        echo '</div>'; // End shopglut-filter-preview-wrapper

        return ob_get_clean();
    }

    /**
     * Render action buttons (delegates to FilterContent)
     */
    public function render_action_buttons() {
        // Initialize FilterContent for action buttons
        $filter_style_handler = null;
        if (class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
            $filter_style_handler = new \Shopglut\enhancements\Filters\implementation\FilterStyle($this->filter_id, $this->filter_settings);
        }
        $filter_content = new \Shopglut\enhancements\Filters\implementation\FilterContent($filter_style_handler, $this->filter_id, $this->filter_settings);
        return $filter_content->render_action_buttons();
    }

} // End class ShopPageFilter

} // End class_exists check