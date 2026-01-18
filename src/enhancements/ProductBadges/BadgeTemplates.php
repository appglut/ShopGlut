<?php
namespace Shopglut\enhancements\ProductBadges;

class BadgeTemplates {

    /**
     * Get prebuilt badge templates
     */
    public static function get_prebuilt_templates() {
        return [
            'sale_red' => [
                'name' => __('Sale Badge - Red', 'shopglut'),
                'description' => __('Eye-catching red sale badge with gradient effect', 'shopglut'),
                'preview_style' => [
                    'background' => 'linear-gradient(135deg, #ff0000, #cc0000)',
                    'color' => '#ffffff',
                    'font_size' => '14px',
                    'font_weight' => 'bold',
                    'padding' => '8px 16px',
                    'border_radius' => '4px',
                    'text_transform' => 'uppercase'
                ],
                'default_settings' => [
                    'badge_type' => 'sale',
                    'sale_badge_text' => __('SALE', 'shopglut'),
                    'enable_badge' => '1',
                    'badge_display_area' => 'product_image',
                    'badge_position' => 'top-left'
                ]
            ],
            'new_green' => [
                'name' => __('New Badge - Green', 'shopglut'),
                'description' => __('Fresh green badge for new products', 'shopglut'),
                'preview_style' => [
                    'background' => 'linear-gradient(135deg, #10b981, #059669)',
                    'color' => '#ffffff',
                    'font_size' => '14px',
                    'font_weight' => 'bold',
                    'padding' => '8px 16px',
                    'border_radius' => '4px',
                    'text_transform' => 'uppercase'
                ],
                'default_settings' => [
                    'badge_type' => 'new',
                    'new_badge_text' => __('NEW', 'shopglut'),
                    'enable_badge' => '1',
                    'badge_display_area' => 'product_image',
                    'badge_position' => 'top-right'
                ]
            ]
        ];
    }

    /**
     * Get template settings for creating a new badge
     */
    public static function get_template_settings($template_id) {
        $templates = self::get_prebuilt_templates();

        if (!isset($templates[$template_id])) {
            return null;
        }

        $template = $templates[$template_id];

        // Return complete settings structure
        return [
            'enable_badge' => '1',
            'display-locations' => ['All Products'],
            'badge_display_area' => $template['default_settings']['badge_display_area'] ?? 'product_image',
            'badge_position' => $template['default_settings']['badge_position'] ?? 'top-left',
            'badge_position_inline' => 'left',
            'badge_position_cart' => 'after_item_name',
            'badge_position_summary' => 'before_title',
            'product_badge-settings' => array_merge([
                'badge_type' => $template['default_settings']['badge_type'],
                'badge_text_settings' => [
                    'text_color' => $template['preview_style']['color'] ?? '#ffffff',
                    'font_size' => [
                        'font_size' => intval($template['preview_style']['font_size']) ?? 14,
                        'font_size-unit' => 'px'
                    ],
                    'font_weight' => $template['preview_style']['font_weight'] ?? '700',
                    'text_transform' => $template['preview_style']['text_transform'] ?? 'uppercase'
                ],
                'badge_background_settings' => [
                    'background_color' => '#ff0000',
                    'enable_gradient' => '1',
                    'gradient_color' => '#cc0000'
                ],
                'badge_dimensions_settings' => [
                    'padding_top_bottom' => [
                        'padding_top_bottom' => 8,
                        'padding_top_bottom-unit' => 'px'
                    ],
                    'padding_left_right' => [
                        'padding_left_right' => 16,
                        'padding_left_right-unit' => 'px'
                    ],
                    'border_radius' => [
                        'border_radius' => 4,
                        'border_radius-unit' => 'px'
                    ]
                ],
                'badge_border_settings' => [
                    'border_width' => [
                        'border_width' => 0,
                        'border_width-unit' => 'px'
                    ],
                    'border_color' => '#000000'
                ],
                'badge_shadow_settings' => [
                    'enable_shadow' => '1',
                    'shadow_color' => 'rgba(0, 0, 0, 0.2)',
                    'shadow_blur' => [
                        'shadow_blur' => 4,
                        'shadow_blur-unit' => 'px'
                    ]
                ]
            ], $template['default_settings'])
        ];
    }

    /**
     * Get template data for handleCreateBadgeFromTemplate method
     */
    public static function get_template($template_id) {
        $settings = self::get_template_settings($template_id);

        if (!$settings) {
            return null;
        }

        $templates = self::get_prebuilt_templates();
        $template = $templates[$template_id];

        return [
            'name' => $template['name'],
            'data' => $settings
        ];
    }
}