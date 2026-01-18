<?php
/**
 * Default Invoice Template
 * 
 * Available variables:
 * $order - WooCommerce order object
 * $company_info - Array with company information
 * $invoice_number - Generated invoice number
 * $invoice_date - Invoice generation date
 * $due_date - Payment due date (if enabled)
 * $template_settings - Array with design settings
 * $display_settings - Array with display preferences
 * $display_data - Array with processed display data
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shopglut_primary_color = $template_settings['primary_color'];
$shopglut_secondary_color = $template_settings['secondary_color'];
$shopglut_header_text_color = $template_settings['header_text_color'];
$shopglut_body_text_color = $template_settings['body_text_color'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: <?php echo esc_attr( $shopglut_body_text_color ); ?>;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            background-color: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            color: <?php echo esc_attr( $shopglut_header_text_color ); ?>;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        .company-logo {
            max-height: <?php echo esc_attr( $company_info['logo_height'] ); ?>;
            margin-bottom: 10px;
        }
        .clear {
            clear: both;
        }
        .billing-section {
            margin: 30px 0;
        }
        .billing-info {
            float: left;
            width: 48%;
        }
        .invoice-details {
            float: right;
            width: 48%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .items-table th {
            background-color: <?php echo esc_attr( $shopglut_secondary_color ); ?>;
            color: white;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid <?php echo esc_attr( $shopglut_primary_color ); ?>;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .totals-section {
            float: right;
            width: 40%;
            margin-top: 20px;
        }
        .totals-table {
            width: 100%;
        }
        .totals-table td {
            padding: 5px 10px;
            border-top: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            background-color: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            color: <?php echo esc_attr( $shopglut_header_text_color ); ?>;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="company-info">
            <?php if ( $company_info['logo'] ): ?>
                <img src="<?php echo esc_url( $company_info['logo'] ); ?>" class="company-logo" alt="Company Logo">
            <?php endif; ?>
            <h2><?php echo esc_html( $company_info['name'] ); ?></h2>
            <?php if ( $company_info['address'] ): ?>
                <p><?php echo nl2br( esc_html( $company_info['address'] ) ); ?></p>
            <?php endif; ?>
            <?php if ( $company_info['phone'] ): ?>
                <p><?php esc_html_e( 'Phone:', 'shopglut' ); ?> <?php echo esc_html( $company_info['phone'] ); ?></p>
            <?php endif; ?>
            <?php if ( $company_info['email'] ): ?>
                <p><?php esc_html_e( 'Email:', 'shopglut' ); ?> <?php echo esc_html( $company_info['email'] ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="invoice-info">
            <h1><?php esc_html_e( 'INVOICE', 'shopglut' ); ?></h1>
            <?php if ( $display_data['display_number'] ): ?>
                <p><strong><?php echo esc_html( $display_data['display_number_label'] ); ?>:</strong> <?php echo esc_html( $display_data['display_number'] ); ?></p>
            <?php endif; ?>
            <?php if ( $display_data['display_date'] ): ?>
                <p><strong><?php echo esc_html( $display_data['display_date_label'] ); ?>:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $display_data['display_date'] ) ) ); ?></p>
            <?php endif; ?>
            <?php if ( $display_data['show_due_date'] && $display_data['due_date_formatted'] ): ?>
                <p><strong><?php esc_html_e( 'Due Date:', 'shopglut' ); ?></strong> <?php echo esc_html( $display_data['due_date_formatted'] ); ?></p>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Billing Information -->
    <div class="billing-section">
        <div class="billing-info">
            <h3><?php esc_html_e( 'Bill To:', 'shopglut' ); ?></h3>
            <p><strong><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></strong></p>
            <p><?php echo esc_html( $order->get_formatted_billing_address() ); ?></p>
            
            <?php if ( $display_data['show_email'] ): ?>
                <p><?php esc_html_e( 'Email:', 'shopglut' ); ?> <?php echo esc_html( $display_data['customer_email'] ); ?></p>
            <?php endif; ?>
            
            <?php if ( $display_data['show_phone'] ): ?>
                <p><?php esc_html_e( 'Phone:', 'shopglut' ); ?> <?php echo esc_html( $display_data['customer_phone'] ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( $display_data['show_shipping_address'] ): ?>
        <div class="invoice-details">
            <h3><?php esc_html_e( 'Ship To:', 'shopglut' ); ?></h3>
            <p><?php echo esc_html( $display_data['shipping_address'] ); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="clear"></div>
    </div>

    <!-- Order Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Product', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Qty', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Price', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Total', 'shopglut' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $order->get_items() as $shopglut_item_id => $shopglut_item ): ?>
                <?php
                $shopglut_product = $shopglut_item->get_product();
                if ( ! $display_settings['show_free_line_items'] && $shopglut_item->get_total() == 0 ) {
                    continue;
                }
                ?>
                <tr>
                    <td>
                        <?php echo esc_html( $shopglut_item->get_name() ); ?>
                        <?php if ( $shopglut_product && $shopglut_product->get_sku() ): ?>
                            <br><small><?php esc_html_e( 'SKU:', 'shopglut' ); ?> <?php echo esc_html( $shopglut_product->get_sku() ); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $shopglut_item->get_total() / $item->get_quantity() ) ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $shopglut_item->get_total() ) ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Order Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td><?php esc_html_e( 'Subtotal:', 'shopglut' ); ?></td>
                <td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></td>
            </tr>
            
            <?php if ( $order->get_total_shipping() > 0 ): ?>
            <tr>
                <td><?php esc_html_e( 'Shipping:', 'shopglut' ); ?></td>
                <td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_total_shipping() ) ); ?></td>
            </tr>
            <?php endif; ?>
            
            <?php if ( $order->get_total_tax() > 0 ): ?>
            <tr>
                <td><?php esc_html_e( 'Tax:', 'shopglut' ); ?></td>
                <td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></td>
            </tr>
            <?php endif; ?>
            
            <tr class="total-row">
                <td><strong><?php esc_html_e( 'Total:', 'shopglut' ); ?></strong></td>
                <td style="text-align: right;"><strong><?php echo wp_kses_post( wc_price( $order->get_total() ) ); ?></strong></td>
            </tr>
        </table>
    </div>
    
    <div class="clear"></div>

    <!-- Customer Notes -->
    <?php if ( $display_data['show_customer_notes'] && $display_data['customer_notes'] ): ?>
    <div style="margin-top: 30px;">
        <h3><?php esc_html_e( 'Notes:', 'shopglut' ); ?></h3>
        <p><?php echo esc_html( $display_data['customer_notes'] ); ?></p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <?php if ( $template_settings['footer_text'] ): ?>
            <p><?php echo esc_html( $template_settings['footer_text'] ); ?></p>
        <?php endif; ?>
        
        <?php if ( $company_info['tax_number'] ): ?>
            <p><?php esc_html_e( 'Tax Number:', 'shopglut' ); ?> <?php echo esc_html( $company_info['tax_number'] ); ?></p>
        <?php endif; ?>
        
        <?php if ( $company_info['coc_number'] ): ?>
            <p><?php esc_html_e( 'Chamber of Commerce:', 'shopglut' ); ?> <?php echo esc_html( $company_info['coc_number'] ); ?></p>
        <?php endif; ?>
    </div>

</body>
</html>