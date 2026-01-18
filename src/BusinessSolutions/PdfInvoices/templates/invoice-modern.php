<?php
/**
 * Modern Invoice Template
 * 
 * Features:
 * - Clean modern design with better typography
 * - Utilizes extra fields in footer columns
 * - Enhanced color usage and spacing
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
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: <?php echo esc_attr( $shopglut_body_text_color ); ?>;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .invoice-header {
            background: linear-gradient(135deg, <?php echo esc_attr( $shopglut_primary_color ); ?>, <?php echo esc_attr( $shopglut_secondary_color ); ?>);
            color: <?php echo esc_attr( $shopglut_header_text_color ); ?>;
            padding: 40px;
            position: relative;
        }
        .invoice-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-section {
            flex: 1;
        }
        .invoice-section {
            flex: 1;
            text-align: right;
        }
        .company-logo {
            max-height: <?php echo esc_attr( $company_info['logo_height'] ); ?>;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .company-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: 300;
            margin: 0 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .content-section {
            padding: 40px;
        }
        .billing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid <?php echo esc_attr( $shopglut_primary_color ); ?>;
        }
        .info-card h3 {
            margin: 0 0 15px 0;
            color: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 40px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .modern-table th {
            background: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            color: <?php echo esc_attr( $shopglut_header_text_color ); ?>;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }
        .modern-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .modern-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .modern-table tbody tr:hover {
            background: rgba(<?php echo esc_attr( implode(',', sscanf($shopglut_primary_color, "#%02x%02x%02x")) ); ?>, 0.05);
        }
        .totals-section {
            float: right;
            width: 350px;
            margin-top: 20px;
        }
        .totals-card {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .total-final {
            background: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            color: <?php echo esc_attr( $shopglut_header_text_color ); ?>;
            font-weight: 700;
            font-size: 18px;
        }
        .footer-columns {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid <?php echo esc_attr( $shopglut_primary_color ); ?>;
        }
        .footer-column {
            text-align: center;
        }
        .footer-column h4 {
            color: <?php echo esc_attr( $shopglut_primary_color ); ?>;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-main {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .badge {
            display: inline-block;
            background: <?php echo esc_attr( $shopglut_secondary_color ); ?>;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Modern Header -->
    <div class="invoice-header">
        <div class="header-content">
            <div class="company-section">
                <?php if ( $company_info['logo'] ): ?>
                    <img src="<?php echo esc_url( $company_info['logo'] ); ?>" class="company-logo" alt="Company Logo">
                <?php endif; ?>
                <h2 class="company-name"><?php echo esc_html( $company_info['name'] ); ?></h2>
                <?php if ( $company_info['address'] ): ?>
                    <p style="margin: 5px 0; opacity: 0.9;"><?php echo nl2br( esc_html( $company_info['address'] ) ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info['phone'] || $company_info['email'] ): ?>
                    <p style="margin: 5px 0; opacity: 0.9;">
                        <?php if ( $company_info['phone'] ): ?>
                            <?php echo esc_html( $company_info['phone'] ); ?>
                        <?php endif; ?>
                        <?php if ( $company_info['phone'] && $company_info['email'] ): ?> | <?php endif; ?>
                        <?php if ( $company_info['email'] ): ?>
                            <?php echo esc_html( $company_info['email'] ); ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="invoice-section">
                <h1 class="invoice-title"><?php esc_html_e( 'Invoice', 'shopglut' ); ?></h1>
                <?php if ( $display_data['display_number'] ): ?>
                    <p style="margin: 8px 0;"><strong><?php echo esc_html( $display_data['display_number_label'] ); ?>:</strong> 
                    <span class="badge"><?php echo esc_html( $display_data['display_number'] ); ?></span></p>
                <?php endif; ?>
                <?php if ( $display_data['display_date'] ): ?>
                    <p style="margin: 8px 0;"><strong><?php echo esc_html( $display_data['display_date_label'] ); ?>:</strong> 
                    <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $display_data['display_date'] ) ) ); ?></p>
                <?php endif; ?>
                <?php if ( $display_data['show_due_date'] && $display_data['due_date_formatted'] ): ?>
                    <p style="margin: 8px 0;"><strong><?php esc_html_e( 'Due Date:', 'shopglut' ); ?></strong> 
                    <span style="color: #ff6b6b; font-weight: 600;"><?php echo esc_html( $display_data['due_date_formatted'] ); ?></span></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="content-section">
        <!-- Billing Information Grid -->
        <div class="billing-grid">
            <div class="info-card">
                <h3><?php esc_html_e( 'Bill To', 'shopglut' ); ?></h3>
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
            <div class="info-card">
                <h3><?php esc_html_e( 'Ship To', 'shopglut' ); ?></h3>
                <p><?php echo esc_html( $display_data['shipping_address'] ); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Modern Items Table -->
        <table class="modern-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Product', 'shopglut' ); ?></th>
                    <th style="text-align: center;"><?php esc_html_e( 'Qty', 'shopglut' ); ?></th>
                    <th style="text-align: right;"><?php esc_html_e( 'Price', 'shopglut' ); ?></th>
                    <th style="text-align: right;"><?php esc_html_e( 'Total', 'shopglut' ); ?></th>
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
                            <strong><?php echo esc_html( $shopglut_item->get_name() ); ?></strong>
                            <?php if ( $shopglut_product && $shopglut_product->get_sku() ): ?>
                                <br><span style="color: #666; font-size: 12px;"><?php esc_html_e( 'SKU:', 'shopglut' ); ?> <?php echo esc_html( $shopglut_product->get_sku() ); ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center; font-weight: 600;"><?php echo esc_html( $item->get_quantity() ); ?></td>
                        <td style="text-align: right;"><?php echo wp_kses_post( wc_price( $shopglut_item->get_total() / $item->get_quantity() ) ); ?></td>
                        <td style="text-align: right; font-weight: 600;"><?php echo wp_kses_post( wc_price( $shopglut_item->get_total() ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modern Totals -->
        <div class="totals-section">
            <div class="totals-card">
                <div class="totals-row">
                    <span><?php esc_html_e( 'Subtotal:', 'shopglut' ); ?></span>
                    <span><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></span>
                </div>
                
                <?php if ( $order->get_total_shipping() > 0 ): ?>
                <div class="totals-row">
                    <span><?php esc_html_e( 'Shipping:', 'shopglut' ); ?></span>
                    <span><?php echo wp_kses_post( wc_price( $order->get_total_shipping() ) ); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ( $order->get_total_tax() > 0 ): ?>
                <div class="totals-row">
                    <span><?php esc_html_e( 'Tax:', 'shopglut' ); ?></span>
                    <span><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="totals-row total-final">
                    <span><?php esc_html_e( 'Total:', 'shopglut' ); ?></span>
                    <span><?php echo wp_kses_post( wc_price( $order->get_total() ) ); ?></span>
                </div>
            </div>
        </div>
        
        <div style="clear: both;"></div>

        <!-- Customer Notes -->
        <?php if ( $display_data['show_customer_notes'] && $display_data['customer_notes'] ): ?>
        <div class="info-card" style="margin-top: 40px;">
            <h3><?php esc_html_e( 'Notes', 'shopglut' ); ?></h3>
            <p><?php echo esc_html( $display_data['customer_notes'] ); ?></p>
        </div>
        <?php endif; ?>

        <!-- Footer Columns (Modern template feature) -->
        <?php if ( $template_settings['extra_field_1'] || $template_settings['extra_field_2'] || $template_settings['extra_field_3'] ): ?>
        <div class="footer-columns">
            <?php if ( $template_settings['extra_field_1'] ): ?>
            <div class="footer-column">
                <h4><?php esc_html_e( 'Payment Terms', 'shopglut' ); ?></h4>
                <p><?php echo nl2br( esc_html( $template_settings['extra_field_1'] ) ); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ( $template_settings['extra_field_2'] ): ?>
            <div class="footer-column">
                <h4><?php esc_html_e( 'Contact Info', 'shopglut' ); ?></h4>
                <p><?php echo nl2br( esc_html( $template_settings['extra_field_2'] ) ); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ( $template_settings['extra_field_3'] ): ?>
            <div class="footer-column">
                <h4><?php esc_html_e( 'Additional Info', 'shopglut' ); ?></h4>
                <p><?php echo nl2br( esc_html( $template_settings['extra_field_3'] ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Main Footer -->
        <div class="footer-main">
            <?php if ( $template_settings['footer_text'] ): ?>
                <p style="font-size: 16px; margin-bottom: 15px;"><?php echo esc_html( $template_settings['footer_text'] ); ?></p>
            <?php endif; ?>
            
            <p style="font-size: 12px; color: #666; margin: 10px 0;">
                <?php if ( $company_info['tax_number'] ): ?>
                    <?php esc_html_e( 'Tax Number:', 'shopglut' ); ?> <?php echo esc_html( $company_info['tax_number'] ); ?>
                <?php endif; ?>
                
                <?php if ( $company_info['tax_number'] && $company_info['coc_number'] ): ?> | <?php endif; ?>
                
                <?php if ( $company_info['coc_number'] ): ?>
                    <?php esc_html_e( 'Chamber of Commerce:', 'shopglut' ); ?> <?php echo esc_html( $company_info['coc_number'] ); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

</body>
</html>