<?php
namespace Shopglut\wooTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\tools\wooTemplates\WooTemplatesEntity;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class WooTemplatesListTable extends \WP_List_Table {
    
    public function __construct() {
        parent::__construct([
            'singular' => 'template',
            'plural'   => 'templates',
            'ajax'     => false
        ]);
    }
    
    /**
     * Get columns for the table
     */
    public function get_columns() {
        return [
            'cb'            => '<input type="checkbox" />',
            'template_name' => esc_html__('Template Name', 'shopglut'),
            'template_id'   => esc_html__('Template ID', 'shopglut')
        ];
    }
    
    /**
     * Prepare items for the table
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = [$columns, $hidden, $sortable];
        
        // Get templates from database
        $per_page = 10;
        $current_page = $this->get_pagenum();
        
        $total_items = WooTemplatesEntity::retrieveAllCount();
        $this->items = WooTemplatesEntity::retrieveAll($per_page, $current_page);
        
        // Set pagination args
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }
    
    /**
     * Get sortable columns
     */
    public function get_sortable_columns() {
        return [
            'template_name' => ['template_name', false],
            'template_id'   => ['template_id', false]
        ];
    }
    
    /**
     * Column default
     */
    public function column_default($item, $column_name) {
        return esc_html($item[$column_name] ?? '');
    }
    
    /**
     * Checkbox column
     */
    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="template_ids[]" value="%s" />',
            $item['id']
        );
    }
    
    /**
     * Template name column with actions
     */
    public function column_template_name($item) {
        $actions = [
            'edit' => sprintf(
                '<a href="%s">%s</a>',
                esc_url(admin_url('admin.php?page=shopglut_tools&editor=woo_template&template_id=' . $item['id'])),
                esc_html__('Edit', 'shopglut')
            ),
            'delete' => sprintf(
                '<a href="%s" class="submitdelete" onclick="return confirm(\'%s\');">%s</a>',
                esc_url(wp_nonce_url(admin_url('admin.php?page=shopglut_tools&view=woo_templates&action=delete&template_id=' . $item['id']), 'delete_template_' . $item['id'])),
                esc_html__('Are you sure you want to delete this template?', 'shopglut'),
                esc_html__('Delete', 'shopglut')
            )
        ];

        return sprintf(
            '<strong><a href="%s">%s</a></strong>%s',
            esc_url(admin_url('admin.php?page=shopglut_tools&editor=woo_template&template_id=' . $item['id'])),
            esc_html($item['template_name']),
            $this->row_actions($actions)
        );
    }

    /**
     * Template ID column with copy button
     */
    public function column_template_id($item) {
        return sprintf(
            '<code>%s</code> <button type="button" class="button button-small copy-template-id" data-template-id="%s" title="%s" style="padding: 2px 8px; font-size: 12px; height: auto; line-height: 1.5;"><span class="dashicons dashicons-admin-page" style="font-size: 14px; width: 14px; height: 14px; margin-top: 2px;"></span></button>',
            esc_html($item['template_id']),
            esc_attr($item['template_id']),
            esc_attr__('Copy Template ID', 'shopglut')
        );
    }
    
    /**
     * Get bulk actions
     */
    public function get_bulk_actions() {
        return [
            'delete' => esc_html__('Delete', 'shopglut')
        ];
    }
    
    /**
     * No items found text
     */
    public function no_items() {
        esc_html_e('No templates found.', 'shopglut');
    }
}