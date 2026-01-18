<?php
/**
 * Product Custom Field Settings
 *
 * This file contains settings for creating custom fields that can be added to WooCommerce products.
 * Features:
 * - Multiple field types (text, textarea, select, checkbox, radio, etc.)
 * - Choose where to display on product page (before/after add to cart, in tabs, etc.)
 * - Show as metabox in product edit page
 * - Display on frontend product page
 */

if (!defined('ABSPATH')) {
    exit;
}

// Function to get products options for select field
function get_product_custom_field_products_options() {
    $options = array();

    // Add "All Products" option
    $options['all'] = __('All Products', 'shopglut');

    // Get all published products
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_id = get_the_ID();
            $product_title = get_the_title();

            // Add SKU to display if available
            $product = wc_get_product($product_id);
            $display_title = $product_title;
            if ($product && $product->get_sku()) {
                $display_title .= ' (SKU: ' . $product->get_sku() . ')';
            }

            $options[$product_id] = $display_title;
        }
        wp_reset_postdata();
    }

    return $options;
}


$SHOPG_product_custom_field_STYLING = "shopg_product_custom_field_settings";

// Product Custom Field Settings
AGSHOPGLUT::createMetabox(
    $SHOPG_product_custom_field_STYLING,
    array(
        'title' => esc_html__('Product Custom Field Settings', 'shopglut'),
        'post_type' => 'shopglut_product_custom_field',
        'context' => 'side',
    )
);

AGSHOPGLUT::createSection(
    $SHOPG_product_custom_field_STYLING,
    array(
        'fields' => array(

            // Product Selection
            array(
                'id' => 'select_products',
                'type' => 'select_products',
                'title' => __('Select Products', 'shopglut'),
                'desc' => __('Choose which products will have these custom fields. Select "All Products" to apply to all products, or select specific products.', 'shopglut'),
                'placeholder' => __('Select products...', 'shopglut'),
                'chosen' => true,
                'multiple' => true,
                'ajax' => false,
                'options' => get_product_custom_field_products_options(),
                'default' => array(),
            ),

            // Custom Fields Repeater
            array(
                'id' => 'custom_fields',
                'type' => 'group',
                'title' => __('Custom Fields', 'shopglut'),
                'fields' => array(
                    array(
                        'id' => 'field_label',
                        'type' => 'text',
                        'title' => __('Field Label', 'shopglut'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'field_key',
                        'type' => 'text',
                        'title' => __('Field Key', 'shopglut'),
                        'desc' => __('Unique key (lowercase, no spaces)', 'shopglut'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'field_type',
                        'type' => 'select',
                        'title' => __('Field Type', 'shopglut'),
                        'options' => array(
                            'textarea' => __('Design Content', 'shopglut'),
                        ),
                        'default' => 'textarea',
                    ),
                   
                    array(
                        'id' => 'textarea_field_design',
                        'type' => 'textarea_design_selector',
                        'title' => __('Design Style', 'shopglut'),
                        'desc' => __('Choose how your content will be displayed on the product page', 'shopglut'),
                        'default' => 'simple_list',
                    ),

                     array(
                        'id' => 'field_content',
                        'type' => 'textarea',
                        'title' => __('Design Content', 'shopglut'),
                        'desc' => __('Enter your content. Each new line will be displayed as a separate item based on the selected design.', 'shopglut'),
                        'default' => '',
                        'attributes' => array(
                            'rows' => 6,
                            'placeholder' => __('Enter content here...', 'shopglut'),
                        ),
                    ),

                    array(
                        'id' => 'content_position',
                        'type' => 'select',
                        'title' => __('Display Position', 'shopglut'),
                        'desc' => __('Choose where to display the content on the product page', 'shopglut'),
                        'options' => array(
                            'before_title' => __('Before Product Title', 'shopglut'),
                            'after_title' => __('After Product Title', 'shopglut'),
                            'before_price' => __('Before Price', 'shopglut'),
                            'after_price' => __('After Price', 'shopglut'),
                            'before_add_to_cart' => __('Before Add to Cart', 'shopglut'),
                            'after_add_to_cart' => __('After Add to Cart', 'shopglut')
                        ),
                        'default' => 'after_title',
                    )
                ),
                'default' => array(),
            ),

        ),
    )
);
