<?php

namespace Shopglut\enhancements\Filters\implementation;

use WP_Query;

/**
 * Filter Content Generator
 * Centralized class for generating filter HTML content for both backend preview and frontend
 *
 * @since 1.0.0
 */
if (!class_exists('Shopglut\enhancements\Filters\implementation\FilterContent')) {
class FilterContent {

    private $filter_style_handler;
    private $filter_id;
    private $filter_settings;

    /**
     * Constructor
     */
    public function __construct($filter_style_handler = null, $filter_id = null, $filter_settings = null) {
        $this->filter_style_handler = $filter_style_handler;
        $this->filter_id = $filter_id;
        $this->filter_settings = $filter_settings;
    }

    /**
     * Generate complete filter HTML for both backend and frontend
     */
    public function generate_filter_html($is_preview = false) {
        if ($is_preview) {
            return $this->generate_preview_content();
        } else {
            return $this->generate_frontend_content();
        }
    }

    /**
     * Generate preview content (backend)
     */
    private function generate_preview_content() {
        $settings = $this->get_filter_settings();

        // Never call demo filters - always try to render real settings
        $html = '';

        if (!empty($settings)) {
            foreach ($settings as $filter_index => $filter_item) {
                $html .= $this->render_filter_item($filter_item, $filter_index, true);
            }
        }

        return $html;
    }

    /**
     * Generate frontend content
     */
    private function generate_frontend_content() {
        $settings = $this->get_filter_settings();

        $html = '';
        if (!empty($settings)) {
            foreach ($settings as $filter_index => $filter_item) {
                $html .= $this->render_filter_item($filter_item, $filter_index, false);
            }
        }

        return $html;
    }

    /**
     * Render individual filter item (both preview and frontend)
     */
    private function render_filter_item($filter_item, $filter_index, $is_preview) {
        $filter_type = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed']['filter-type'] ?? '';
        $filter_title = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed']['accordion-title'] ?? 'Filter ' . ($filter_index + 1);
        $subTabbed = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed'] ?? [];

        // Get appearance settings using FilterStyle if available
        $show_filter_title = true;
        $title_appearance = 'accordion-design';
        $content_bgcolor = '#ffffff';
        $show_count = false;

        if ($this->filter_style_handler) {
            $show_filter_title = $this->filter_style_handler->should_show_filter_title($filter_item);
            $title_appearance = $this->filter_style_handler->get_title_appearance();
            $content_bgcolor = $this->filter_style_handler->get_content_bg_color($filter_item);
            $show_count = $this->filter_style_handler->should_show_count($filter_item);
        } else {
            // Fallback to manual extraction
            $show_filter_title = ($subTabbed['filter-show-title'] ?? '1') === '1';
            $title_appearance = $subTabbed['filter-title-appearance'] ?? 'accordion-design';
            $content_bgcolor = $subTabbed['filter-content-bg-color'] ?? '#ffffff';
            $show_count = ($subTabbed['show-count'] ?? '0') === '1';
        }

        $html = '<div class="filter-item" data-filter-index="' . esc_attr($filter_index) . '">';

        // Render title if enabled
        if ($show_filter_title) {
            $html .= $this->render_filter_title($filter_title, $title_appearance, $filter_item, $is_preview);
        }

        // Render filter content
        if ($title_appearance === 'accordion-design') {
            // For accordion, the .filter-content is already opened in render_filter_title
            $html .= '<div class="filter-options">';
            $html .= $this->render_filter_options($filter_type, $filter_item, $show_count);
            $html .= '</div>';
        } else {
            // For static design, open .filter-content here
            $html .= '<div class="filter-content"';
            if (!$is_preview) {
                $html .= ' style="background-color: ' . esc_attr($content_bgcolor) . ';"';
            }
            $html .= '>';
            $html .= '<div class="filter-options">';
            $html .= $this->render_filter_options($filter_type, $filter_item, $show_count);
            $html .= '</div>';
            $html .= '</div>';
        }

        // Close accordion if needed
        if ($show_filter_title && $title_appearance === 'accordion-design') {
            $html .= '</div>'; // Close accordion-content
        }

        $html .= '</div>'; // Close filter-item

        return $html;
    }

    /**
     * Render filter title (both preview and frontend)
     */
    private function render_filter_title($title, $title_appearance, $filter_item, $is_preview) {
        $style = '';
        $expand_icon = 'fa fa-plus';
        $close_icon = 'fa fa-minus';
        $title_icon = '';
        $hide_icon = false;
        $hide_border = false;

        if ($this->filter_style_handler) {
            $title_group = $this->filter_style_handler->get_title_group();

            // Get color settings
            if (isset($title_group['filter-title-color-groups'])) {
                $title_colors = $title_group['filter-title-color-groups'];
                $bg_color = $title_colors['filter-title-bg-color'] ?? '#FFFFFF';
                $title_color = $title_colors['filter-title-color'] ?? '#000000';
                $icon_color = $title_colors['filter-title-icon-color'] ?? '#000000';
                $style .= "background-color: {$bg_color} !important;";
                $style .= "color: {$title_color} !important;";
            }

            // Get accordion icons
            $close_icon = $title_group['filter-title-close-icon'] ?? 'fa fa-minus';
            $expand_icon = $title_group['filter-title-expand-icon'] ?? 'fa fa-plus';

            // Get normal design icon
            $title_icon = $title_group['filter-title-icon'] ?? '';

            // Get hide/show options
            $hide_options = $title_group['filter-title-normal-design-hide'] ?? [];
            $hide_icon = in_array('hide-icon', $hide_options);
            $hide_border = in_array('hide-small-border', $hide_options);
        }

        $html = '';
        if ($title_appearance === 'accordion-design') {
            $html .= '<h4 class="filter-title-accordion" style="' . esc_attr($style) . ' cursor:pointer" data-expand-icon="' . esc_attr($expand_icon) . '" data-close-icon="' . esc_attr($close_icon) . '">';
            $html .= esc_html($title);
            $html .= '<i class="' . esc_attr($expand_icon) . '"></i>';
            $html .= '</h4>';
            $html .= '<div class="filter-content accordion-content">';
        } else {
            // Normal design
            $html .= '<div class="filter-content">';

            // Add border data attribute for CSS targeting
            $border_class = $hide_border ? 'no-border' : 'has-border';

            $html .= '<h4 class="filter-title-static ' . esc_attr($border_class) . '" style="' . esc_attr($style) . '">';

            // Title on the left
            $html .= '<span class="title-text">' . esc_html($title) . '</span>';

            // Add title icon on the right if not hidden and icon is set
            if (!$hide_icon && !empty($title_icon)) {
                $html .= '<i class="' . esc_attr($title_icon) . ' title-icon" style="margin-left: 8px; color: ' . esc_attr($icon_color ?? '#000000') . ';"></i>';
            }

            $html .= '</h4>';
        }

        return $html;
    }

    /**
     * Render filter options based on type
     */
    private function render_filter_options($filter_type, $filter_item, $show_count) {
        $subTabbed = $filter_item['shopg-filter-accordion']['shopg-filter-sub-tabbed'] ?? [];

        switch ($filter_type) {
            case 'product-categories':
                return $this->render_product_categories($subTabbed, $show_count);

            case 'product-tags':
                return $this->render_product_tags($subTabbed, $show_count);

            case 'product-price':
                return $this->render_product_price($subTabbed);

            case 'product-rating':
                return $this->render_product_rating($subTabbed);

            case 'product-author':
                return $this->render_product_author($subTabbed);

            case 'product-stock':
                return $this->render_product_stock($subTabbed);

            case 'product-sortby':
                return $this->render_product_sortby($subTabbed);

            default:
                return $this->render_default_filter();
        }
    }

    /**
     * Render product categories with hierarchical structure
     */
    private function render_product_categories($subTabbed, $show_count) {
        $appearance_type = $subTabbed['filter-product-categories-appearance'] ?? 'check-list';
        $terms = $this->get_terms('product_cat', $subTabbed);

        $html = '';
        if ($appearance_type === 'check-list' || $appearance_type === 'radio') {
            $html .= '<div class="shopglut-filter-checklist shopglut-categories-hierarchical">';

            // Group terms by parent for hierarchical display
            $parent_terms = [];
            $child_terms = [];

            foreach ($terms as $term) {
                if ($term->parent == 0) {
                    $parent_terms[] = $term;
                } else {
                    $child_terms[] = $term;
                }
            }

            // Render parent categories first
            foreach ($parent_terms as $parent_term) {
                $is_radio = $appearance_type === 'radio';
                $html .= '<div class="shopglut-filter-checkbox parent-category">';
                $html .= '<input type="' . ($is_radio ? 'radio' : 'checkbox') . '"
                          name="product_cat[]" value="' . esc_attr($parent_term->term_id) . '" id="cat-' . esc_attr($parent_term->term_id) . '">';
                $html .= '<label for="cat-' . esc_attr($parent_term->term_id) . '" class="parent-label">';
                $html .= '<span class="category-name">' . esc_html($parent_term->name) . '</span>';
                if ($show_count && $parent_term->count > 0) {
                    $html .= ' <span class="filter-count">(' . $parent_term->count . ')</span>';
                }
                $html .= '</label>';
                $html .= '</div>';

                // Render child categories
                $children = array_filter($child_terms, function($child) use ($parent_term) {
                    return $child->parent == $parent_term->term_id;
                });

                if (!empty($children)) {
                    $html .= '<div class="child-categories">';
                    foreach ($children as $child_term) {
                        $html .= '<div class="shopglut-filter-checkbox child-category">';
                        $html .= '<input type="' . ($is_radio ? 'radio' : 'checkbox') . '"
                                  name="product_cat[]" value="' . esc_attr($child_term->term_id) . '" id="cat-' . esc_attr($child_term->term_id) . '">';
                        $html .= '<label for="cat-' . esc_attr($child_term->term_id) . '" class="child-label">';
                        $html .= '<span class="category-name">' . esc_html($child_term->name) . '</span>';
                        if ($show_count && $child_term->count > 0) {
                            $html .= ' <span class="filter-count">(' . $child_term->count . ')</span>';
                        }
                        $html .= '</label>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
            }

            $html .= '</div>';
        } elseif ($appearance_type === 'dropdown') {
            $html .= '<select name="product_cat[]" class="shopglut-filter-dropdown">';
            $html .= '<option value="">' . __('All Categories', 'shopglut') . '</option>';

            // Render hierarchical dropdown
            foreach ($terms as $term) {
                $prefix = $term->parent > 0 ? str_repeat('— ', 1) : '';
                $html .= '<option value="' . esc_attr($term->term_id) . '">';
                $html .= $prefix . esc_html($term->name);
                if ($show_count) {
                    $html .= ' (' . $term->count . ')';
                }
                $html .= '</option>';
            }
            $html .= '</select>';
        }

        return $html;
    }

    /**
     * Render product tags with nice tag design
     */
    private function render_product_tags($subTabbed, $show_count) {
        $appearance_type = $subTabbed['filter-product-tags-appearance'] ?? 'check-list';
        $terms = $this->get_terms('product_tag', $subTabbed);

        $html = '';
        if ($appearance_type === 'check-list' || $appearance_type === 'radio') {
            $html .= '<div class="shopglut-filter-checklist shopglut-tags-cloud">';
            foreach ($terms as $term) {
                $is_radio = $appearance_type === 'radio';
                $html .= '<div class="shopglut-filter-checkbox tag-item">';
                $html .= '<input type="' . ($is_radio ? 'radio' : 'checkbox') . '"
                          name="product_tag[]" value="' . esc_attr($term->term_id) . '" id="tag-' . esc_attr($term->term_id) . '">';
                $html .= '<label for="tag-' . esc_attr($term->term_id) . '" class="tag-label">';
                $html .= '<span class="tag-name">' . esc_html($term->name) . '</span>';
                if ($show_count && $term->count > 0) {
                    $html .= ' <span class="tag-count">' . $term->count . '</span>';
                }
                $html .= '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Render product price
     */
    private function render_product_price($subTabbed) {
        $appearance_type = $subTabbed['filter-product-price-appearance'] ?? 'price-range-slider';
        $min_price = $subTabbed['filter-product-price-range']['min'] ?? 0;
        $max_price = $subTabbed['filter-product-price-range']['max'] ?? 1000;

        $html = '';
        if ($appearance_type === 'price-range-slider') {
            $html .= '<div class="price-range-filter">';
            $html .= '<div class="price-slider" data-min="' . esc_attr($min_price) . '" data-max="' . esc_attr($max_price) . '">';
            $html .= '<div class="price-values">';
            $html .= '<span class="min-price">' . wc_price($min_price) . '</span>';
            $html .= '<span class="max-price">' . wc_price($max_price) . '</span>';
            $html .= '</div>';
            $html .= '<div class="slider-range"></div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Render product rating
     */
    private function render_product_rating($subTabbed) {
        $html = '<div class="rating-filter">';
        $ratings = [5, 4, 3, 2, 1];

        foreach ($ratings as $rating) {
            $html .= '<div class="rating-option">';
            $html .= '<input type="checkbox" name="rating[]" value="' . $rating . '" id="rating-' . $rating . '">';
            $html .= '<label for="rating-' . $rating . '">';
            $html .= '<div class="star-rating">';
            for ($i = 1; $i <= 5; $i++) {
                $star_class = $i <= $rating ? 'star-filled' : 'star-empty';
                $html .= '<i class="fa fa-star ' . $star_class . '"></i>';
            }
            $html .= ' ' . __('up', 'shopglut') . '</div></label>';
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Render product author
     */
    private function render_product_author($subTabbed) {
        $authors = get_users(['who' => 'authors', 'fields' => ['ID', 'display_name']]);

        $html = '<div class="author-filter">';
        $html .= '<select name="product_author" class="shopglut-filter-dropdown">';
        $html .= '<option value="">' . __('All Authors', 'shopglut') . '</option>';

        foreach ($authors as $author) {
            $html .= '<option value="' . esc_attr($author->ID) . '">';
            $html .= esc_html($author->display_name);
            $html .= '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render product stock
     */
    private function render_product_stock($subTabbed) {
        $html = '<div class="stock-filter">';

        $stock_options = [
            'in-stock' => __('In Stock', 'shopglut'),
            'out-of-stock' => __('Out of Stock', 'shopglut')
        ];

        foreach ($stock_options as $value => $label) {
            $html .= '<div class="stock-option">';
            $html .= '<input type="checkbox" name="stock_status[]" value="' . $value . '" id="stock-' . $value . '">';
            $html .= '<label for="stock-' . $value . '">' . esc_html($label) . '</label>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render product sort by
     */
    private function render_product_sortby($subTabbed) {
        $html = '<div class="sortby-filter">';
        $html .= '<select name="orderby" class="shopglut-filter-dropdown">';

        $sort_options = [
            'popularity' => __('Sort by popularity', 'shopglut'),
            'rating' => __('Sort by average rating', 'shopglut'),
            'date' => __('Sort by newness', 'shopglut'),
            'price' => __('Sort by price: low to high', 'shopglut'),
            'price-desc' => __('Sort by price: high to low', 'shopglut')
        ];

        foreach ($sort_options as $value => $label) {
            $html .= '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render default filter for unknown types
     */
    private function render_default_filter() {
        return '<div class="default-filter">' . __('Filter type not supported', 'shopglut') . '</div>';
    }

    /**
     * Generate demo filters for testing
     */
    private function generate_demo_filters($is_preview) {
        // DISABLED: Return empty to prevent double filter generation
        return '';
    }

    /**
     * Create demo filter item array
     */
    private function create_demo_filter_item($filter) {
        return [
            'shopg-filter-accordion' => [
                'shopg-filter-sub-tabbed' => [
                    'accordion-title' => $filter['title'],
                    'filter-type' => $filter['type'],
                    'filter-product-categories-appearance' => 'check-list',
                    'filter-show-title' => '1',
                    'filter-content-bg-color' => '#f8f9fa',
                    'show-count' => '1'
                ]
            ]
        ];
    }

    /**
     * Get terms by taxonomy with hierarchical structure and include/exclude logic
     */
    private function get_terms($taxonomy, $subTabbed = []) {
        $args = [
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
            'hierarchical' => true
        ];

        // Handle include/exclude logic for categories
        if ($taxonomy === 'product_cat') {
            $filter_option = $subTabbed['filter-category-exclude-include-button'] ?? 'all-cat';

            if ($filter_option === 'include') {
                $include_categories = $subTabbed['shopg-filter-include-category'] ?? [];
                if (!empty($include_categories)) {
                    $args['include'] = $include_categories;
                }
            } elseif ($filter_option === 'exclude') {
                $exclude_categories = $subTabbed['shopg-filter-exclude-category'] ?? [];
                if (!empty($exclude_categories)) {
                    // Use 'exclude_tree' for better performance when excluding categories
                    $args['exclude_tree'] = $exclude_categories;
                }
            }
        }

        // Handle include/exclude logic for tags
        if ($taxonomy === 'product_tag') {
            $filter_option = $subTabbed['filter-tag-exclude-include-button'] ?? 'all-tags';

            if ($filter_option === 'include') {
                $include_tags = $subTabbed['shopg-filter-include-tag'] ?? [];
                if (!empty($include_tags)) {
                    $args['include'] = $include_tags;
                }
            } elseif ($filter_option === 'exclude') {
                $exclude_tags = $subTabbed['shopg-filter-exclude-tag'] ?? [];
                if (!empty($exclude_tags)) {
                    // Use tag__not_in for better performance when excluding products by tags
                    // Limit the number of excluded tags to prevent performance issues
                    $args['tag__not_in'] = array_slice($exclude_tags, 0, 100);
                    // Add performance optimization - limit results for better performance
                    $args['number'] = $args['number'] ?? 100;
                }
            }
        }

        return get_terms($args);
    }

    /**
     * Get filter settings
     */
    private function get_filter_settings() {
        if ($this->filter_settings) {
            // Same structure as FilterStyle - extract preview_data first
            if (isset($this->filter_settings['shopg_filter_options_settings'])) {
                $preview_data = $this->filter_settings['shopg_filter_options_settings'];
                $settings = $preview_data['shopglut-filter-settings-main-tab']['shopg-filter-add-new'] ?? [];
                return $settings;
            }
        }
        return [];
    }

    /**
     * Render action buttons
     */
    public function render_action_buttons() {
        if (!$this->filter_style_handler) {
            return '';
        }

        $filter_option = $this->filter_style_handler->get_filter_option();
        if ($filter_option !== 'select-submit-filter' && $filter_option !== 'select-apply-filter') {
            return '';
        }

        $apply_text = $this->filter_style_handler->get_apply_button_text();
        $reset_text = $this->filter_style_handler->get_reset_button_text();

        $html = '<div class="filter-actions">';
        $html .= '<button type="button" class="apply-filter-btn">' . esc_html($apply_text) . '</button>';
        $html .= '<button type="button" class="reset-filter-btn">' . esc_html($reset_text) . '</button>';
        $html .= '</div>';

        return $html;
    }

} // End class FilterContent

} // End class_exists check