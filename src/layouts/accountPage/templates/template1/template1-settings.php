<?php
/**
 * AccountPage Template1 Specific Settings
 *
 * This file contains settings specifically designed for Template1 account page layout.
 * Template1 features:
 * - Account navigation sidebar
 * - Dashboard content area
 * - Orders table display
 * - Account information
 */

if (!defined('ABSPATH')) {
    exit;
}

$SHOPG_ACCOUNTPAGE_STYLING = "shopg_accountpage_settings_template1";

// Live Preview Section
AGSHOPGLUT::createMetabox(
	'shopg_accountpage_live_preview',
	array(
		'title' => __( 'Preview - Demo Mode', 'shopglut' ),
		'post_type' => 'accountpage',
		'context' => 'normal',
	)
);
AGSHOPGLUT::createSection(
	'shopg_accountpage_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

// Main AccountPage Styling Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_ACCOUNTPAGE_STYLING,
    array(
        'title' => esc_html__('Template1 AccountPage Settings', 'shopglut'),
        'post_type' => 'accountpage',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_ACCOUNTPAGE_STYLING,
    array(
        'fields' => array(

            // ==================== DISPLAY SETTINGS (OUTSIDE TABS) ====================
            array(
                'id' => 'enable_accountpage',
                'type' => 'switcher',
                'title' => __('Enable Custom Account Page', 'shopglut'),
                'desc' => __('Enable or disable the custom WooCommerce account page', 'shopglut'),
                'default' => true,
            ),

            array(
                'type' => 'subheading',
                'content' => __('Account Page Design Settings', 'shopglut'),
            ),

            array(
                'id' => 'accountpage-page-settings',
                'type' => 'tabbed',
                'title' => __('AccountPage Configuration', 'shopglut'),
                'tabs' => array(

                    // ==================== CONTAINER SETTINGS ====================
                    array(
                        'title' => __('Container', 'shopglut'),
                        'icon' => 'fas fa-window-maximize',
                        'fields' => array(

                            // Main Container Settings
                            array(
                                'id' => 'container_settings',
                                'type' => 'fieldset',
                                'title' => __('Main Container', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'container_max_width',
                                        'type' => 'slider',
                                        'title' => __('Container Max Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 1000,
                                        'max' => 1600,
                                        'step' => 50,
                                        'default' => 1200,
                                    ),
                                    array(
                                        'id' => 'container_bg_color',
                                        'type' => 'color',
                                        'title' => __('Container Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'container_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Container Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 30,
                                        'step' => 2,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'container_padding',
                                        'type' => 'slider',
                                        'title' => __('Container Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 80,
                                        'step' => 5,
                                        'default' => 40,
                                    ),
                                    array(
                                        'id' => 'page_bg_color',
                                        'type' => 'color',
                                        'title' => __('Page Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                ),
                            ),

                            // Shadow Settings
                            array(
                                'id' => 'shadow_settings',
                                'type' => 'fieldset',
                                'title' => __('Shadow', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'enable_shadow',
                                        'type' => 'switcher',
                                        'title' => __('Enable Container Shadow', 'shopglut'),
                                        'default' => true,
                                    ),
                                    array(
                                        'id' => 'shadow_color',
                                        'type' => 'color',
                                        'title' => __('Shadow Color', 'shopglut'),
                                        'default' => 'rgba(0, 0, 0, 0.08)',
                                        'dependency' => array('enable_shadow', '==', true),
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== NAVIGATION SETTINGS ====================
                    array(
                        'title' => __('Navigation', 'shopglut'),
                        'icon' => 'fas fa-bars',
                        'fields' => array(

                            // Navigation Width Settings
                            array(
                                'id' => 'navigation_width_settings',
                                'type' => 'fieldset',
                                'title' => __('Navigation Width', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'navigation_width',
                                        'type' => 'slider',
                                        'title' => __('Navigation Sidebar Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 200,
                                        'max' => 350,
                                        'step' => 10,
                                        'default' => 250,
                                    ),
                                ),
                            ),

                            // Navigation Background Settings
                            array(
                                'id' => 'navigation_bg_settings',
                                'type' => 'fieldset',
                                'title' => __('Navigation Background', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'navigation_bg_color',
                                        'type' => 'color',
                                        'title' => __('Navigation Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'navigation_border_color',
                                        'type' => 'color',
                                        'title' => __('Navigation Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                ),
                            ),

                            // Navigation Link Settings
                            array(
                                'id' => 'navigation_link_settings',
                                'type' => 'fieldset',
                                'title' => __('Navigation Links', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'nav_link_color',
                                        'type' => 'color',
                                        'title' => __('Link Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'nav_link_font_size',
                                        'type' => 'slider',
                                        'title' => __('Link Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'nav_link_padding',
                                        'type' => 'slider',
                                        'title' => __('Link Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 30,
                                        'step' => 1,
                                        'default' => 15,
                                    ),
                                    array(
                                        'id' => 'nav_link_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Link Hover Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'nav_link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Link Hover Text Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                ),
                            ),

                            // Active Navigation Link Settings
                            array(
                                'id' => 'navigation_active_link_settings',
                                'type' => 'fieldset',
                                'title' => __('Active Navigation Link', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'nav_active_bg_color',
                                        'type' => 'color',
                                        'title' => __('Active Link Background', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'nav_active_text_color',
                                        'type' => 'color',
                                        'title' => __('Active Link Text Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'nav_active_border_color',
                                        'type' => 'color',
                                        'title' => __('Active Link Border Color', 'shopglut'),
                                        'default' => '#5a67d8',
                                    ),
                                    array(
                                        'id' => 'nav_active_border_width',
                                        'type' => 'slider',
                                        'title' => __('Active Link Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 10,
                                        'step' => 1,
                                        'default' => 4,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== CONTENT AREA SETTINGS ====================
                    array(
                        'title' => __('Content Area', 'shopglut'),
                        'icon' => 'fas fa-file-alt',
                        'fields' => array(

                            // Content Area Settings
                            array(
                                'id' => 'content_area_settings',
                                'type' => 'fieldset',
                                'title' => __('Content Area', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'content_padding',
                                        'type' => 'slider',
                                        'title' => __('Content Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 20,
                                        'max' => 80,
                                        'step' => 5,
                                        'default' => 40,
                                    ),
                                    array(
                                        'id' => 'content_bg_color',
                                        'type' => 'color',
                                        'title' => __('Content Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                ),
                            ),

                            // Heading Settings
                            array(
                                'id' => 'heading_settings',
                                'type' => 'fieldset',
                                'title' => __('Headings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'heading_color',
                                        'type' => 'color',
                                        'title' => __('Heading Color', 'shopglut'),
                                        'default' => '#111827',
                                    ),
                                    array(
                                        'id' => 'heading_font_size',
                                        'type' => 'slider',
                                        'title' => __('Heading Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 18,
                                        'max' => 36,
                                        'step' => 1,
                                        'default' => 24,
                                    ),
                                    array(
                                        'id' => 'heading_font_weight',
                                        'type' => 'select',
                                        'title' => __('Heading Font Weight', 'shopglut'),
                                        'options' => array(
                                            '400' => __('Normal', 'shopglut'),
                                            '500' => __('Medium', 'shopglut'),
                                            '600' => __('Semi Bold', 'shopglut'),
                                            '700' => __('Bold', 'shopglut'),
                                        ),
                                        'default' => '700',
                                    ),
                                ),
                            ),

                            // Text Settings
                            array(
                                'id' => 'text_settings',
                                'type' => 'fieldset',
                                'title' => __('Text', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'text_color',
                                        'type' => 'color',
                                        'title' => __('Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'text_font_size',
                                        'type' => 'slider',
                                        'title' => __('Text Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'link_color',
                                        'type' => 'color',
                                        'title' => __('Link Color', 'shopglut'),
                                        'default' => '#667eea',
                                    ),
                                    array(
                                        'id' => 'link_hover_color',
                                        'type' => 'color',
                                        'title' => __('Link Hover Color', 'shopglut'),
                                        'default' => '#5a67d8',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== ORDERS TABLE SETTINGS ====================
                    array(
                        'title' => __('Orders Table', 'shopglut'),
                        'icon' => 'fas fa-table',
                        'fields' => array(

                            // Table Background Settings
                            array(
                                'id' => 'table_bg_settings',
                                'type' => 'fieldset',
                                'title' => __('Table Background', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'table_bg_color',
                                        'type' => 'color',
                                        'title' => __('Table Background Color', 'shopglut'),
                                        'default' => '#ffffff',
                                    ),
                                    array(
                                        'id' => 'table_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Table Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 8,
                                    ),
                                    array(
                                        'id' => 'table_shadow_color',
                                        'type' => 'color',
                                        'title' => __('Table Shadow Color', 'shopglut'),
                                        'default' => 'rgba(0, 0, 0, 0.08)',
                                    ),
                                ),
                            ),

                            // Table Header Settings
                            array(
                                'id' => 'table_header_settings',
                                'type' => 'fieldset',
                                'title' => __('Table Header', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'thead_bg_color',
                                        'type' => 'color',
                                        'title' => __('Header Background Color', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'thead_text_color',
                                        'type' => 'color',
                                        'title' => __('Header Text Color', 'shopglut'),
                                        'default' => '#374151',
                                    ),
                                    array(
                                        'id' => 'thead_font_size',
                                        'type' => 'slider',
                                        'title' => __('Header Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 11,
                                        'max' => 16,
                                        'step' => 1,
                                        'default' => 13,
                                    ),
                                    array(
                                        'id' => 'thead_border_color',
                                        'type' => 'color',
                                        'title' => __('Header Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'thead_border_width',
                                        'type' => 'slider',
                                        'title' => __('Header Border Width', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 5,
                                        'step' => 1,
                                        'default' => 2,
                                    ),
                                ),
                            ),

                            // Table Body Settings
                            array(
                                'id' => 'table_body_settings',
                                'type' => 'fieldset',
                                'title' => __('Table Body', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'tbody_text_color',
                                        'type' => 'color',
                                        'title' => __('Body Text Color', 'shopglut'),
                                        'default' => '#6b7280',
                                    ),
                                    array(
                                        'id' => 'tbody_font_size',
                                        'type' => 'slider',
                                        'title' => __('Body Font Size', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 12,
                                        'max' => 18,
                                        'step' => 1,
                                        'default' => 14,
                                    ),
                                    array(
                                        'id' => 'tbody_row_hover_bg',
                                        'type' => 'color',
                                        'title' => __('Row Hover Background', 'shopglut'),
                                        'default' => '#f9fafb',
                                    ),
                                    array(
                                        'id' => 'tbody_border_color',
                                        'type' => 'color',
                                        'title' => __('Row Border Color', 'shopglut'),
                                        'default' => '#e5e7eb',
                                    ),
                                    array(
                                        'id' => 'cell_padding',
                                        'type' => 'slider',
                                        'title' => __('Cell Padding', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 10,
                                        'max' => 25,
                                        'step' => 1,
                                        'default' => 15,
                                    ),
                                ),
                            ),

                            // Order Status Badge Settings
                            array(
                                'id' => 'status_badge_settings',
                                'type' => 'fieldset',
                                'title' => __('Order Status Badges', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'status_badge_border_radius',
                                        'type' => 'slider',
                                        'title' => __('Status Badge Border Radius', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 0,
                                        'max' => 20,
                                        'step' => 1,
                                        'default' => 12,
                                    ),
                                    array(
                                        'id' => 'status_processing_bg',
                                        'type' => 'color',
                                        'title' => __('Processing Status Background', 'shopglut'),
                                        'default' => '#fef3c7',
                                    ),
                                    array(
                                        'id' => 'status_processing_text',
                                        'type' => 'color',
                                        'title' => __('Processing Status Text', 'shopglut'),
                                        'default' => '#92400e',
                                    ),
                                    array(
                                        'id' => 'status_completed_bg',
                                        'type' => 'color',
                                        'title' => __('Completed Status Background', 'shopglut'),
                                        'default' => '#d1fae5',
                                    ),
                                    array(
                                        'id' => 'status_completed_text',
                                        'type' => 'color',
                                        'title' => __('Completed Status Text', 'shopglut'),
                                        'default' => '#065f46',
                                    ),
                                ),
                            ),
                        ),
                    ),

                    // ==================== RESPONSIVE SETTINGS ====================
                    array(
                        'title' => __('Responsive', 'shopglut'),
                        'icon' => 'fas fa-mobile-alt',
                        'fields' => array(

                            // Mobile Breakpoint Settings
                            array(
                                'id' => 'responsive_settings',
                                'type' => 'fieldset',
                                'title' => __('Mobile Settings', 'shopglut'),
                                'fields' => array(
                                    array(
                                        'id' => 'mobile_breakpoint',
                                        'type' => 'slider',
                                        'title' => __('Mobile Breakpoint', 'shopglut'),
                                        'desc' => __('Screen width below which mobile layout is applied', 'shopglut'),
                                        'unit' => 'px',
                                        'min' => 480,
                                        'max' => 992,
                                        'step' => 8,
                                        'default' => 768,
                                    ),
                                    array(
                                        'id' => 'mobile_nav_position',
                                        'type' => 'select',
                                        'title' => __('Mobile Navigation Position', 'shopglut'),
                                        'options' => array(
                                            'top' => __('Top (Above Content)', 'shopglut'),
                                            'bottom' => __('Bottom (Below Content)', 'shopglut'),
                                        ),
                                        'default' => 'top',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    )
);
