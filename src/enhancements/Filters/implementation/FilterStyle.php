<?php
namespace Shopglut\enhancements\Filters\implementation;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Filter Style Handler
 * Centralized class for handling both backend preview and frontend filter appearance settings
 *
 * @since 1.0.0
 */
if (!class_exists('Shopglut\enhancements\Filters\implementation\FilterStyle')) {
class FilterStyle {

    private $filter_id;
    private $filter_settings;
    private $preview_data;

    /**
     * Constructor
     */
    public function __construct($filter_id, $filter_settings) {
        $this->filter_id = $filter_id;
        $this->filter_settings = $filter_settings;
        $this->preview_data = $this->extract_preview_data();
    }

    /**
     * Get global filter settings
     */
    public function get_global_settings() {
        return [
            'filter-option' => $this->get_setting('filter-option', 'select-apply-filter'),
            'filter-title-appearance' => $this->get_setting('filter-title-appearance', 'accordion-design'),
            'filter-title-group' => $this->get_setting('filter-title-group', []),
            'filter-apply-button-text' => $this->get_setting('filter-apply-button-text', 'Apply Filter'),
            'filter-reset-button-text' => $this->get_setting('filter-reset-button-text', 'Reset Filter'),
        ];
    }

    /**
     * Get individual filter appearance settings
     */
    public function get_filter_appearance($filter_item) {
        // Handle both appearance-tab structure and direct settings
        if (isset($filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed']['appearance-tab'])) {
            $appearance = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed']['appearance-tab'];
        } else {
            // Get appearance settings directly from sub-tabbed level
            $subTabbed = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed'] ?? [];
            $appearance = [];
            $appearance_keys = [
                'filter-show-title',
                'filter-content-bg-color',
                'filter-content-font-color',
                'show-count'
            ];
            foreach ($appearance_keys as $key) {
                if (isset($subTabbed[$key])) {
                    $appearance[$key] = $subTabbed[$key];
                }
            }
        }

        return $appearance;
    }

    /**
     * Generate CSS for individual filter based on appearance settings
     */
    public function generate_filter_css($filter_index, $filter_item) {
        $appearance = $this->get_filter_appearance($filter_item);
        if (empty($appearance)) {
            return '';
        }
        
        $css = '';
        $filter_selector = ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-item:nth-child(" . ($filter_index + 1) . ")";

        // Show Filter Title
        $show_filter_title = $appearance['filter-show-title'] ?? true;
        if (!$show_filter_title) {
            $css .= $filter_selector . " .filter-title-accordion, " . $filter_selector . " .filter-title-static { display: none !important; }\n";
        } else {
            $css .= $filter_selector . " .filter-title-accordion, " . $filter_selector . " .filter-title-static { display: flex !important; }\n";
        }

        // Content Background Color and Content Font Color
        $content_bg_color = $appearance['filter-content-bg-color'] ?? '#ffffff';
        $content_font_color = $appearance['filter-content-font-color'] ?? '#000000';

        $css .= $filter_selector . " .filter-content {\n";
        $css .= "    background-color: {$content_bg_color} !important;\n";
        $css .= "    color: {$content_font_color} !important;\n";
        $css .= "    padding: 16px;\n";
        $css .= "    border-radius: 4px;\n";
        $css .= "}\n";

        // Show Count styling
        $show_count = $appearance['show-count'] ?? false;
        if ($show_count) {
            $css .= $filter_selector . " .filter-count {\n";
            $css .= "    display: inline-block;\n";
            $css .= "    background-color: #f0f0f0;\n";
            $css .= "    color: #666;\n";
            $css .= "    min-width: 5px;\n";
            $css .= "    padding: 2px 8px;\n";
            $css .= "    border-radius: 12px;\n";
            $css .= "    font-size: 12px;\n";
            $css .= "    margin-left: 8px;\n";
            $css .= "}\n";
        } else {
            $css .= $filter_selector . " .filter-count { display: none !important; }\n";
        }

        return $css;
    }

    /**
     * Generate global filter CSS
     */
    public function generate_global_css() {
        $global_settings = $this->get_global_settings();
        $css = '';
                // Filter Title Appearance global styles
        $title_appearance = $global_settings['filter-title-appearance'];
        $title_group = $global_settings['filter-title-group'] ?? [];

        // Apply title colors if available
        if (isset($title_group['filter-title-color-groups'])) {
                            

            $title_colors = $title_group['filter-title-color-groups'];
            $bg_color = $title_colors['filter-title-bg-color'] ?? '#FFFFFF';
            $title_color = $title_colors['filter-title-color'] ?? '#000000';
            $icon_color = $title_colors['filter-title-icon-color'] ?? '#000000';

            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static, ";
            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-accordion {\n";
            $css .= "    background-color: {$bg_color} !important;\n";
            $css .= "    color: {$title_color} !important;\n";
            $css .= "    padding: 12px 16px;\n";
            $css .= "    margin-bottom: 0;\n";
            $css .= "    font-weight: 600;\n";
            $css .= "    position: relative;\n";
            $css .= "    font-size: 16px;\n";
            $css .= "    border-radius: 4px;\n";
            $css .= "    display: flex;\n";
            $css .= "    justify-content: space-between;\n";
            $css .= "    align-items: center;\n";
            $css .= "    width: 100%;\n";
            $css .= "    box-sizing: border-box;\n";
            $css .= "}\n";

            // Handle border styling for normal design
            $hide_options = $title_group['filter-title-normal-design-hide'] ?? [];
            $hide_border1 = in_array('hide-small-border', $hide_options);

            if (!$hide_border1 && isset($title_group['filter-title-border1'])) {
                $border = $title_group['filter-title-border1'];
                $border_color = $border['color'] ?? '#1e73be';
                $border_thickness = $border['bottom'] ?? '1';
                $border_style = $border['style'] ?? 'solid';

                $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static.has-border {\n";
                $css .= "    border-bottom: {$border_thickness}px {$border_style} {$border_color};\n";
                $css .= "}\n";

                $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static.no-border {\n";
                $css .= "    border-bottom: none !important;\n";
                $css .= "}\n";
            } else {
                // Default behavior when border settings are not configured
                $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static.has-border {\n";
                $css .= "    border-bottom: 1px solid #1e73be;\n";
                $css .= "}\n";

                $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static.no-border {\n";
                $css .= "    border-bottom: none !important;\n";
                $css .= "}\n";
            }

            // Add styling for title container in normal design
            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static {\n";
            $css .= "    display: flex;\n";
            $css .= "    justify-content: space-between;\n";
            $css .= "    align-items: center;\n";
            $css .= "    width: 100%;\n";
            $css .= "    box-sizing: border-box;\n";
            $css .= "}\n";

            // Styling for title text
            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static .title-text {\n";
            $css .= "    flex: 1;\n";
            $css .= "    text-align: left;\n";
            $css .= "}\n";

            // Add styling for title icon in normal design (on the right)
            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static .title-icon {\n";
            $css .= "    font-size: 14px;\n";
            $css .= "    vertical-align: middle;\n";
            $css .= "    opacity: 0.8;\n";
            $css .= "    transition: opacity 0.2s ease;\n";
            $css .= "    flex-shrink: 0;\n";
            $css .= "}\n";

            $css .= ".shopglut-filter-container[data-filter-id='{$this->filter_id}'] .filter-title-static:hover .title-icon {\n";
            $css .= "    opacity: 1;\n";
            $css .= "}\n";


        }


        return $css;
    }

    /**
     * Output CSS for backend preview and frontend
     */
    public function output_styles() {
        $global_css = $this->generate_global_css();
        $individual_css = '';

        // Generate CSS for each individual filter
        $filter_data = $this->get_filter_data();
        if (!empty($filter_data)) {
            foreach ($filter_data as $index => $filter_item) {
                $individual_css .= $this->generate_filter_css($index, $filter_item);
            }
        }


        // Add backend smooth transition fix and classic design styles
        $backend_fix = "
        /* Backend smooth transition fix - override display:none that breaks transitions */
        .filter-content.accordion-content.hidden {
            display: block !important;
        }

        /* Classic Hierarchical Categories Styling */
        .shopglut-categories-hierarchical .parent-category {
            margin-bottom: 6px;
            padding: 4px 0;
        }

        .shopglut-categories-hierarchical .parent-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            color: inherit;
            cursor: pointer;
        }

        .shopglut-categories-hierarchical .parent-icon {
            margin-right: 8px;
            color: #666;
            font-size: 12px;
        }

        .shopglut-categories-hierarchical .child-categories {
            margin-left: 20px;
            margin-top: 4px;
        }

        .shopglut-categories-hierarchical .child-category {
            margin-bottom: 4px;
            padding: 2px 0;
        }

        .shopglut-categories-hierarchical .child-label {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: inherit;
            cursor: pointer;
        }

        .shopglut-categories-hierarchical .child-icon {
            margin-right: 8px;
            color: #999;
            font-size: 10px;
        }

        /* Classic Tags Styling */
        .shopglut-tags-cloud {
            padding: 4px 0;
        }

        .shopglut-tags-cloud .tag-item {
            display: inline-block;
            margin-right: 8px;
            margin-bottom: 6px;
        }

        .shopglut-tags-cloud .tag-label {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: inherit;
            cursor: pointer;
            padding: 2px 0;
        }

        .shopglut-tags-cloud .tag-icon {
            margin-right: 6px;
            color: #999;
            font-size: 10px;
        }

        /* Simple checkbox styling */
        .shopglut-categories-hierarchical input[type=\"checkbox\"],
        .shopglut-categories-hierarchical input[type=\"radio\"],
        .shopglut-tags-cloud input[type=\"checkbox\"],
        .shopglut-tags-cloud input[type=\"radio\"] {
            width: 14px;
            height: 14px;
            margin-right: 8px;
        }

        /* Classic filter count styling */
        .shopglut-categories-hierarchical .filter-count,
        .shopglut-tags-cloud .filter-count {
            font-size: 11px;
            color: #999;
            margin-left: 6px;
        }

        /* Filter Actions Buttons Styling */
        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding: 16px 0;
            border-top: 1px solid #e2e8f0;
        }

        .filter-actions button {
            padding: 14px;
            border: 2px solid transparent;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-width: 100px;
        }

        .filter-actions .apply-filter-btn {
            background-color: #0073aa;
            color: #ffffff;
            border-color: #0073aa;
        }

        .filter-actions .apply-filter-btn:hover {
            background-color: #005a87;
            border-color: #005a87;
            box-shadow: 0 3px 6px rgba(0,115,170,0.2);
        }

        .filter-actions .reset-filter-btn {
            background-color: #ffffff;
            color: #6b7280;
            border-color: #d1d5db;
        }

        .filter-actions .reset-filter-btn:hover {
            background-color: #f8fafc;
            color: #374151;
            border-color: #9ca3af;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        .filter-actions button:active {
            transform: translateY(1px);
        }

        /* Loading overlay for products */
        .products.loading {
            position: relative;
            opacity: 0.6;
            pointer-events: none;
        }

        .shopglut-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .shopglut-loading-overlay .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0073aa;
            border-radius: 50%;
            animation: shopglut-spin 1s linear infinite;
        }

        @keyframes shopglut-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Fix checkbox and label alignment */
        .shopglut-categories-hierarchical .shopglut-filter-checkbox,
        .shopglut-tags-cloud .shopglut-filter-checkbox {
            display: block;
            margin-bottom: 6px;
        }

        .shopglut-categories-hierarchical .shopglut-filter-checkbox label,
        .shopglut-tags-cloud .shopglut-filter-checkbox label {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            vertical-align: middle;
        }

        /* Hide default checkboxes and radios */
        .shopglut-categories-hierarchical input[type=\"checkbox\"],
        .shopglut-categories-hierarchical input[type=\"radio\"],
        .shopglut-tags-cloud input[type=\"checkbox\"],
        .shopglut-tags-cloud input[type=\"radio\"] {
            width: 14px;
            height: 14px;
            margin-right: 8px;
            vertical-align: middle;
            margin-bottom: 0;
            opacity: 0;
            position: absolute;
        }

        /* Custom checkbox styling */
        .shopglut-categories-hierarchical input[type=\"checkbox\"] + label::before,
        .shopglut-tags-cloud input[type=\"checkbox\"] + label::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ddd;
            border-radius: 3px;
            background-color: #fff;
            margin-right: 8px;
            vertical-align: middle;
            transition: all 0.2s ease;
            position: relative;
            flex-shrink: 0;
        }

        /* Custom radio button styling */
        .shopglut-categories-hierarchical input[type=\"radio\"] + label::before,
        .shopglut-tags-cloud input[type=\"radio\"] + label::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ddd;
            border-radius: 50%;
            background-color: #fff;
            margin-right: 8px;
            vertical-align: middle;
            transition: all 0.2s ease;
            position: relative;
            flex-shrink: 0;
        }

        /* Checkbox checked state */
        .shopglut-categories-hierarchical input[type=\"checkbox\"]:checked + label::before,
        .shopglut-tags-cloud input[type=\"checkbox\"]:checked + label::before {
            background-color: #0073aa;
            border-color: #0073aa;
        }

        .shopglut-categories-hierarchical input[type=\"checkbox\"]:checked + label::after,
        .shopglut-tags-cloud input[type=\"checkbox\"]:checked + label::after {
            content: '✓';
            position: absolute;
            left: 2px;
            top: -1px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        /* Radio button checked state */
        .shopglut-categories-hierarchical input[type=\"radio\"]:checked + label::before,
        .shopglut-tags-cloud input[type=\"radio\"]:checked + label::before {
            background-color: #0073aa;
            border-color: #0073aa;
        }

        .shopglut-categories-hierarchical input[type=\"radio\"]:checked + label::after,
        .shopglut-tags-cloud input[type=\"radio\"]:checked + label::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 4px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #fff;
        }

        /* Hover effects */
        .shopglut-categories-hierarchical input[type=\"checkbox\"]:hover + label::before,
        .shopglut-categories-hierarchical input[type=\"radio\"]:hover + label::before,
        .shopglut-tags-cloud input[type=\"checkbox\"]:hover + label::before,
        .shopglut-tags-cloud input[type=\"radio\"]:hover + label::before {
            border-color: #0073aa;
            box-shadow: 0 0 0 2px rgba(0,115,170,0.1);
        }";

        // Output combined CSS
        $all_css = $global_css . $individual_css . $backend_fix;

        // Add professional checkbox and radio styling with toggle functionality
        $all_css .= "
        /* Professional Checkbox and Radio Styling */
        .shopglut-categories-hierarchical .shopglut-filter-checkbox,
        .shopglut-tags-cloud .shopglut-filter-checkbox {
            position: relative;
            margin-bottom: 2px;
        }

        .shopglut-categories-hierarchical .shopglut-filter-checkbox label,
        .shopglut-tags-cloud .shopglut-filter-checkbox label {
            cursor: pointer;
            padding: 6px 8px 6px 32px;
            border-radius: 4px;
            transition: all 0.2s ease;
            display: inline-block;
            position: relative;
            font-size: 14px;
            line-height: 1.4;
            min-height: 20px;
            vertical-align: middle;
            border: 1px solid transparent;
            margin-bottom: 2px;
        }

        /* Custom Checkbox styling */
        .shopglut-categories-hierarchical[data-appearance='check-list'] .shopglut-filter-checkbox label:before,
        .shopglut-tags-cloud[data-appearance='check-list'] .shopglut-filter-checkbox label:before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        /* Checkbox Checkmark */
        .shopglut-categories-hierarchical[data-appearance='check-list'] .shopglut-filter-checkbox label:after,
        .shopglut-tags-cloud[data-appearance='check-list'] .shopglut-filter-checkbox label:after {
            content: '';
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%) scale(0);
            width: 6px;
            height: 10px;
            border: solid #ffffff;
            border-width: 0 2px 2px 0;
            transition: all 0.2s ease;
        }

        /* Custom Radio styling */
        .shopglut-categories-hierarchical[data-appearance='radio'] .shopglut-filter-checkbox label:before,
        .shopglut-tags-cloud[data-appearance='radio'] .shopglut-filter-checkbox label:before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        /* Radio Dot */
        .shopglut-categories-hierarchical[data-appearance='radio'] .shopglut-filter-checkbox label:after,
        .shopglut-tags-cloud[data-appearance='radio'] .shopglut-filter-checkbox label:after {
            content: '';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%) scale(0);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        /* Hover states for both */
        .shopglut-categories-hierarchical .shopglut-filter-checkbox label:hover,
        .shopglut-tags-cloud .shopglut-filter-checkbox label:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }

        .shopglut-categories-hierarchical .shopglut-filter-checkbox label:hover:before,
        .shopglut-tags-cloud .shopglut-filter-checkbox label:hover:before {
            border-color: #0073aa;
            box-shadow: 0 0 0 3px rgba(0,115,170,0.1);
        }

        /* Checked state for checkboxes */
        .shopglut-categories-hierarchical[data-appearance='check-list'] .shopglut-filter-checkbox.checked label,
        .shopglut-tags-cloud[data-appearance='check-list'] .shopglut-filter-checkbox.checked label {
            background-color: #f0f9ff;
            border-color: #bfdbfe;
            font-weight: 500;
        }

        .shopglut-categories-hierarchical[data-appearance='check-list'] .shopglut-filter-checkbox.checked label:before,
        .shopglut-tags-cloud[data-appearance='check-list'] .shopglut-filter-checkbox.checked label:before {
            background-color: #0073aa;
            border-color: #0073aa;
            box-shadow: 0 2px 4px rgba(0,115,170,0.2);
        }

        .shopglut-categories-hierarchical[data-appearance='check-list'] .shopglut-filter-checkbox.checked label:after,
        .shopglut-tags-cloud[data-appearance='check-list'] .shopglut-filter-checkbox.checked label:after {
            transform: translateY(-50%) scale(1) rotate(45deg);
        }

        /* Checked state for radio buttons */
        .shopglut-categories-hierarchical[data-appearance='radio'] .shopglut-filter-checkbox.checked label,
        .shopglut-tags-cloud[data-appearance='radio'] .shopglut-filter-checkbox.checked label {
            background-color: #f0f9ff;
            border-color: #bfdbfe;
            font-weight: 500;
        }

        .shopglut-categories-hierarchical[data-appearance='radio'] .shopglut-filter-checkbox.checked label:before,
        .shopglut-tags-cloud[data-appearance='radio'] .shopglut-filter-checkbox.checked label:before {
            background-color: #0073aa;
            border-color: #0073aa;
            box-shadow: 0 2px 4px rgba(0,115,170,0.2);
        }

        .shopglut-categories-hierarchical[data-appearance='radio'] .shopglut-filter-checkbox.checked label:after,
        .shopglut-tags-cloud[data-appearance='radio'] .shopglut-filter-checkbox.checked label:after {
            transform: translateY(-50%) scale(1);
        }

        /* Category-specific styling */
        .shopglut-categories-hierarchical .parent-category > label {
            font-weight: 600;
            color: #374151;
        }

        .shopglut-categories-hierarchical .child-category label {
            font-weight: 400;
            color: #6b7280;
        }

        ";

        
        if (!empty($all_css)) {
            // Use wp_kses_post for CSS content since it's properly validated in set methods
            $safe_css = $this->escape_css($all_css);
            echo '<style id="shopglut-filter-styles-' . esc_attr($this->filter_id) . '">';
            echo '/* Generated Filter Styles for Filter ID: ' . esc_attr($this->filter_id) . ' */';
            echo wp_kses_post($safe_css); // CSS is already sanitized in escape_css method
            echo '</style>';

            // Add JavaScript for checkbox and radio functionality
            echo '<script>
            (function() {
                // Function to initialize filter interactions
                function initializeFilterInteractions() {
                    // Get all filter data
                    const filterData = ' . json_encode($this->get_individual_appearance_setting()) . ';

                    // Set appearance data attributes based on filter settings for each filter
                    const containers = document.querySelectorAll(".shopglut-categories-hierarchical, .shopglut-tags-cloud");
                    containers.forEach(function(container, index) {
                        // Get appearance setting for this specific filter index
                        const filterItem = filterData[index];
                        let appearanceType = "check-list"; // default

                        if (filterItem && filterItem["shopg-filter-accordion"] && filterItem["shopg-filter-accordion"]["shopg-filter-sub-tabbed"]) {
                            const subTabbed = filterItem["shopg-filter-accordion"]["shopg-filter-sub-tabbed"];

                            // Check if this is a categories or tags container
                            if (container.classList.contains("shopglut-categories-hierarchical")) {
                                // Use categories appearance
                                if (subTabbed["filter-product-categories-appearance"]) {
                                    appearanceType = subTabbed["filter-product-categories-appearance"];
                                }
                            } else if (container.classList.contains("shopglut-tags-cloud")) {
                                // Use tags appearance
                                if (subTabbed["filter-product-tags-appearance"]) {
                                    appearanceType = subTabbed["filter-product-tags-appearance"];
                                }
                            }
                        }

                        container.setAttribute("data-appearance", appearanceType);
                    });

                    // Add click handlers to all filter checkboxes
                    const filterCheckboxes = document.querySelectorAll(".shopglut-filter-checkbox");

                    filterCheckboxes.forEach(function(checkbox) {
                        const label = checkbox.querySelector("label");
                        if (label) {
                            // Remove existing event listeners to prevent duplicates
                            label.removeEventListener("click", handleLabelClick);
                            // Add new event listener
                            label.addEventListener("click", handleLabelClick);
                        }
                    });
                }

                // Event handler function
                function handleLabelClick(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const checkbox = this.closest(".shopglut-filter-checkbox");
                    const container = checkbox.closest(".shopglut-categories-hierarchical, .shopglut-tags-cloud");
                    const appearanceType = container ? container.getAttribute("data-appearance") : "check-list";

                    // Handle radio behavior (only one can be selected)
                    if (appearanceType === "radio") {
                        // Remove checked class from all other checkboxes in the same container
                        const siblings = container.querySelectorAll(".shopglut-filter-checkbox");
                        siblings.forEach(function(sibling) {
                            sibling.classList.remove("checked");
                            const siblingInput = sibling.querySelector("input[type=\\"hidden\\"]");
                            if (siblingInput) {
                                siblingInput.checked = false;
                            }
                        });

                        // Add checked class to current checkbox
                        checkbox.classList.add("checked");
                    } else {
                        // Handle checkbox behavior (multiple can be selected)
                        checkbox.classList.toggle("checked");
                    }

                    // Create or find hidden input to maintain form functionality
                    let input = checkbox.querySelector("input[type=\\"hidden\\"]");
                    if (!input) {
                        input = document.createElement("input");
                        input.type = "hidden";

                        // Extract value from label for attribute
                        const forAttr = this.getAttribute("for");
                        if (forAttr && forAttr.includes("cat-")) {
                            input.name = "product_cat[]";
                            input.value = forAttr.replace("cat-", "");
                        } else if (forAttr && forAttr.includes("tag-")) {
                            input.name = "product_tag[]";
                            input.value = forAttr.replace("tag-", "");
                        } else {
                            input.name = "filter_value[]";
                            input.value = forAttr || "";
                        }

                        checkbox.appendChild(input);
                    }

                    // Set input value based on checked state
                    input.checked = checkbox.classList.contains("checked");

                    // Trigger change event for any listeners
                    const event = new Event("change", { bubbles: true });
                    input.dispatchEvent(event);
                }

                // Check if DOM is already loaded
                if (document.readyState === "loading") {
                    // DOM is still loading, add event listener
                    document.addEventListener("DOMContentLoaded", initializeFilterInteractions);
                } else {
                    // DOM is already loaded, initialize immediately
                    initializeFilterInteractions();
                }
            })();
            </script>';
        }
    }

    /**
     * Escape CSS content for safe output
     */
    private function escape_css($css) {
        // Remove any potential XSS vectors while preserving valid CSS
        $css = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $css);
        $css = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '', $css);
        $css = preg_replace('/javascript:/i', '', $css);
        $css = preg_replace('/vbscript:/i', '', $css);
        $css = preg_replace('/data:/i', '', $css);

        // Remove any HTML tags that shouldn't be in CSS
        $css = wp_strip_all_tags($css);

        // Remove any potentially dangerous CSS constructs
        $css = preg_replace('/expression\s*\(/i', '', $css);
        $css = preg_replace('/@import/i', '', $css);
        $css = preg_replace('/javascript\s*:/i', '', $css);
        $css = preg_replace('/vbscript\s*:/i', '', $css);

        // Don't use htmlspecialchars as it breaks CSS - the CSS is output in style tags
        return $css;
    }

    /**
     * Get helper methods for frontend integration
     */
    public function should_show_filter_title($filter_item) {
        $appearance = $this->get_filter_appearance($filter_item);
        return $appearance['filter-show-title'] ?? true;
    }

    public function should_show_count($filter_item) {
        $appearance = $this->get_filter_appearance($filter_item);
        return $appearance['show-count'] ?? false;
    }

    public function get_content_bg_color($filter_item) {
        $appearance = $this->get_filter_appearance($filter_item);
        return $appearance['filter-content-bg-color'] ?? '#ffffff';
    }

    public function get_content_font_color($filter_item) {
        $appearance = $this->get_filter_appearance($filter_item);
        return $appearance['filter-content-font-color'] ?? '#000000';
    }

    /**
     * Get global settings for frontend
     */
    public function get_filter_option() {
        return $this->get_setting('filter-option', 'select-apply-filter');
    }

    public function get_title_appearance() {
        return $this->get_setting('filter-title-appearance', 'accordion-design');
    }

    public function get_title_group() {
        return $this->get_setting('filter-title-group', []);
    }

    public function get_apply_button_text() {
        return $this->get_setting('filter-apply-button-text', 'Apply Filter');
    }

    public function get_reset_button_text() {
        return $this->get_setting('filter-reset-button-text', 'Reset Filter');
    }

    /**
     * Private helper methods
     */
    private function extract_preview_data() {
        if (isset($this->filter_settings['shopg_filter_options_settings'])) {
            return $this->filter_settings['shopg_filter_options_settings'];
        }
        return [];
    }

    private function get_setting($key, $default = null) {
        // preview_data already contains shopg_filter_options_settings from extract_preview_data()
        $settings = $this->preview_data['shopglut-filter-settings-main-tab'] ?? [];
        if (isset($settings[$key])) {
            return $settings[$key];
        }
        return $default;
    }

    private function get_filter_data() {
        // preview_data already contains shopg_filter_options_settings from extract_preview_data()
        $settings = $this->preview_data['shopglut-filter-settings-main-tab'] ?? [];
        return $settings['shopg-filter-add-new'] ?? [];
    }

    private function get_individual_appearance_setting() {
        $filter_data = $this->get_filter_data();
        return $filter_data; // Return all filter data so each filter can use its own settings
    }
} // End class FilterStyle

} // End class_exists check