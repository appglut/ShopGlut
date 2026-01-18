<?php
/**
 * Default Packing Slip Template
 * 
 * Available variables:
 * $order - WooCommerce order object
 * $company_info - Array with company information
 * $packing_slip_number - Generated packing slip number
 * $packing_date - Packing slip generation date
 * $template_settings - Array with design settings
 * $display_settings - Array with display preferences
 * $show_sku - Boolean for SKU display
 * $show_weight - Boolean for weight display
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
        .packing-slip-header {
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
        .slip-info {
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
        .shipping-section {
            margin: 30px 0;
        }
        .shipping-info {
            float: left;
            width: 48%;
        }
        .order-details {
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

    <!-- Packing Slip Header -->
    <div class="packing-slip-header">
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
        </div>
        
        <div class="slip-info">
            <h1><?php esc_html_e( 'PACKING SLIP', 'shopglut' ); ?></h1>
            <p><strong><?php esc_html_e( 'Packing Slip:', 'shopglut' ); ?></strong> <?php echo esc_html( $packing_slip_number ); ?></p>
            <p><strong><?php esc_html_e( 'Order:', 'shopglut' ); ?></strong> #<?php echo esc_html( $order->get_order_number() ); ?></p>
            <p><strong><?php esc_html_e( 'Date:', 'shopglut' ); ?></strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $packing_date ) ) ); ?></p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Shipping Information -->
    <div class="shipping-section">
        <div class="shipping-info">
            <h3><?php esc_html_e( 'Ship To:', 'shopglut' ); ?></h3>
            <p><strong><?php echo esc_html( $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() ); ?></strong></p>
            <p><?php echo esc_html( $order->get_formatted_shipping_address() ); ?></p>
        </div>
        
        <div class="order-details">
            <h3><?php esc_html_e( 'Order Details:', 'shopglut' ); ?></h3>
            <p><strong><?php esc_html_e( 'Order Date:', 'shopglut' ); ?></strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $order->get_date_created() ) ) ); ?></p>
            <p><strong><?php esc_html_e( 'Payment Method:', 'shopglut' ); ?></strong> <?php echo esc_html( $order->get_payment_method_title() ); ?></p>
            <p><strong><?php esc_html_e( 'Shipping Method:', 'shopglut' ); ?></strong> <?php echo esc_html( $order->get_shipping_method() ); ?></p>
        </div>
        
        <div class="clear"></div>
    </div>

    <!-- Order Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Product', 'shopglut' ); ?></th>
                <?php if ( $show_sku ): ?><th><?php esc_html_e( 'SKU', 'shopglut' ); ?></th><?php endif; ?>
                <th><?php esc_html_e( 'Qty', 'shopglut' ); ?></th>
                <?php if ( $show_weight ): ?><th><?php esc_html_e( 'Weight', 'shopglut' ); ?></th><?php endif; ?>
                <?php if ( $display_settings['show_prices'] ): ?><th><?php esc_html_e( 'Price', 'shopglut' ); ?></th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $order->get_items() as $shopglut_item_id => $shopglut_item ): ?>
                <?php $shopglut_product = $shopglut_item->get_product(); ?>
                <tr>
                    <td><?php echo esc_html( $shopglut_item->get_name() ); ?></td>
                    
                    <?php if ( $show_sku ): ?>
                    <td><?php echo $shopglut_product && $shopglut_product->get_sku() ? esc_html( $shopglut_product->get_sku() ) : '-'; ?></td>
                    <?php endif; ?>
                    
                    <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                    
                    <?php if ( $show_weight ): ?>
                    <td><?php echo $shopglut_product && $shopglut_product->get_weight() ? esc_html( $shopglut_product->get_weight() . ' ' . get_option( 'woocommerce_weight_unit' ) ) : '-'; ?></td>
                    <?php endif; ?>
                    
                    <?php if ( $display_settings['show_prices'] ): ?>
                    <td><?php echo wp_kses_post( wc_price( $item->get_total() ) ); ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Order Notes -->
    <?php if ( $order->get_customer_note() ): ?>
    <div style="margin-top: 30px;">
        <h3><?php esc_html_e( 'Order Notes:', 'shopglut' ); ?></h3>
        <p><?php echo esc_html( $order->get_customer_note() ); ?></p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <?php if ( $template_settings['footer_text'] ): ?>
            <p><?php echo esc_html( $template_settings['footer_text'] ); ?></p>
        <?php endif; ?>
        
        <p style="font-size: 12px; color: #999;">
            <?php esc_html_e( 'This packing slip was generated automatically. Please check all items before shipment.', 'shopglut' ); ?>
        </p>
    </div>

</body>
</html>