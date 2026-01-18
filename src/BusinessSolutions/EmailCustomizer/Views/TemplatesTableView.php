<?php
namespace Shopglut\BusinessSolutions\EmailCustomizer\Views;

class TemplatesTableView {
    
    public static function render() {
        ?>
        <div class="shopglut-email-customizer-wrapper">
            <!-- Email Templates Overview Table -->
            <div class="shopglut-email-templates-overview" id="templates-overview">
                <div class="templates-header">
                    <div class="templates-header-left">
                        <h1><?php echo esc_html__( 'WooCommerce Email Customizer', 'shopglut' ); ?></h1>
                        <p class="templates-subtitle"><?php echo esc_html__( 'Customize your WooCommerce email templates with our professional drag-and-drop builder', 'shopglut' ); ?></p>
                    </div>
                    <div class="templates-header-right">
                        <button class="shopglut-btn shopglut-btn-primary" id="create-new-template">
                            <i class="fa-solid fa-plus"></i>
                            <?php echo esc_html__( 'Create New Template', 'shopglut' ); ?>
                        </button>
                    </div>
                </div>

                <div class="templates-table-container">
                    <div class="templates-table-filters">
                        <div class="table-filter-left">
                            <input type="text" class="templates-search" placeholder="<?php echo esc_attr__( 'Search templates...', 'shopglut' ); ?>">
                            <select class="templates-status-filter">
                                <option value=""><?php echo esc_html__( 'All Status', 'shopglut' ); ?></option>
                                <option value="active"><?php echo esc_html__( 'Active', 'shopglut' ); ?></option>
                                <option value="inactive"><?php echo esc_html__( 'Inactive', 'shopglut' ); ?></option>
                            </select>
                        </div>
                        <div class="table-filter-right">
                            <button class="shopglut-btn shopglut-btn-outline bulk-actions" disabled>
                                <?php echo esc_html__( 'Bulk Actions', 'shopglut' ); ?>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>

                    <div class="templates-table-wrapper">
                        <?php self::renderTemplatesTable(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private static function renderTemplatesTable() {
        ?>
        <table class="shopglut-templates-table">
            <thead>
                <tr>
                    <th class="table-checkbox">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" id="select-all-templates">
                            <span class="checkbox-custom"></span>
                        </label>
                    </th>
                    <th class="template-name sortable" data-sort="name">
                        <?php echo esc_html__( 'Template Name', 'shopglut' ); ?>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th class="template-status sortable" data-sort="status">
                        <?php echo esc_html__( 'Status', 'shopglut' ); ?>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th class="template-recipient">
                        <?php echo esc_html__( 'Recipient(s)', 'shopglut' ); ?>
                    </th>
                    <th class="template-source">
                        <?php echo esc_html__( 'Source', 'shopglut' ); ?>
                    </th>
                    <th class="template-updated sortable" data-sort="updated">
                        <?php echo esc_html__( 'Last Updated', 'shopglut' ); ?>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th class="template-actions">
                        <?php echo esc_html__( 'Actions', 'shopglut' ); ?>
                    </th>
                </tr>
            </thead>
            <tbody id="email-templates-tbody">
                <?php self::renderTemplateRows(); ?>
            </tbody>
        </table>
        <?php
    }
    
    private static function renderTemplateRows() {
        // New Order template (active)
        ?>
        <tr data-template="new-order">
            <td class="table-checkbox">
                <label class="checkbox-wrapper">
                    <input type="checkbox" class="template-checkbox" value="new-order">
                    <span class="checkbox-custom"></span>
                </label>
            </td>
            <td class="template-name">
                <span class="template-title"><?php echo esc_html__( 'New Order', 'shopglut' ); ?></span>
                <div class="template-meta">
                    <span class="template-description"><?php echo esc_html__( 'Sent when a new order is received', 'shopglut' ); ?></span>
                </div>
            </td>
            <td class="template-status">
                <span class="status-badge status-active">
                    <span class="status-dot"></span>
                    <?php echo esc_html__( 'Active', 'shopglut' ); ?>
                </span>
            </td>
            <td class="template-recipient">
                <span class="recipient-badge recipient-admin"><?php echo esc_html__( 'Admin', 'shopglut' ); ?></span>
            </td>
            <td class="template-source">WooCommerce</td>
            <td class="template-updated">
                <span class="update-time"><?php echo esc_html(current_time( 'M j, Y g:i a' )); ?></span>
            </td>
            <td class="template-actions">
                <div class="action-buttons">
                    <button class="action-btn customize-template" data-template="new-order" title="<?php echo esc_attr__( 'Customize', 'shopglut' ); ?>">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="action-btn preview-template" data-template="new-order" title="<?php echo esc_attr__( 'Preview', 'shopglut' ); ?>">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="action-btn duplicate-template" data-template="new-order" title="<?php echo esc_attr__( 'Duplicate', 'shopglut' ); ?>">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php 
        
        // Other templates
        $templates = array(
            array('key' => 'processing-order', 'name' => 'Processing Order', 'desc' => 'Sent when order status changes to processing', 'recipient' => 'Customer'),
            array('key' => 'completed-order', 'name' => 'Completed Order', 'desc' => 'Sent when order is completed', 'recipient' => 'Customer'),
            array('key' => 'cancelled-order', 'name' => 'Cancelled Order', 'desc' => 'Sent when order is cancelled', 'recipient' => 'Customer'),
            array('key' => 'failed-order', 'name' => 'Failed Order', 'desc' => 'Sent when order payment fails', 'recipient' => 'Admin'),
            array('key' => 'refunded-order', 'name' => 'Refunded Order', 'desc' => 'Sent when order is refunded', 'recipient' => 'Customer'),
            array('key' => 'customer-new-account', 'name' => 'New Account', 'desc' => 'Sent when customer creates account', 'recipient' => 'Customer'),
            array('key' => 'customer-reset-password', 'name' => 'Reset Password', 'desc' => 'Sent when customer resets password', 'recipient' => 'Customer'),
            array('key' => 'customer-invoice', 'name' => 'Customer Invoice', 'desc' => 'Sent with order invoice', 'recipient' => 'Customer'),
        );
        
        foreach ($templates as $template): ?>
        <tr data-template="<?php echo esc_attr($template['key']); ?>">
            <td class="table-checkbox">
                <label class="checkbox-wrapper">
                    <input type="checkbox" class="template-checkbox" value="<?php echo esc_attr($template['key']); ?>">
                    <span class="checkbox-custom"></span>
                </label>
            </td>
            <td class="template-name">
                <span class="template-title"><?php echo esc_html($template['name']); ?></span>
                <div class="template-meta">
                    <span class="template-description"><?php echo esc_html($template['desc']); ?></span>
                </div>
            </td>
            <td class="template-status">
                <span class="status-badge status-inactive">
                    <span class="status-dot"></span>
                    <?php echo esc_html__( 'Inactive', 'shopglut' ); ?>
                </span>
            </td>
            <td class="template-recipient">
                <span class="recipient-badge recipient-<?php echo esc_attr(strtolower($template['recipient'])); ?>"><?php echo esc_html($template['recipient']); ?></span>
            </td>
            <td class="template-source">WooCommerce</td>
            <td class="template-updated">
                <span class="update-time"><?php echo esc_html(current_time( 'M j, Y g:i a' )); ?></span>
            </td>
            <td class="template-actions">
                <div class="action-buttons">
                    <button class="action-btn customize-template" data-template="<?php echo esc_attr($template['key']); ?>" title="<?php echo esc_attr__( 'Customize', 'shopglut' ); ?>">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="action-btn preview-template" data-template="<?php echo esc_attr($template['key']); ?>" title="<?php echo esc_attr__( 'Preview', 'shopglut' ); ?>">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="action-btn duplicate-template" data-template="<?php echo esc_attr($template['key']); ?>" title="<?php echo esc_attr__( 'Duplicate', 'shopglut' ); ?>">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; 
    }
}