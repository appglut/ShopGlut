<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesTemplateManager {
	
	private $settings;
	private $template_dir;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->template_dir = dirname( __FILE__ ) . '/templates/';
		$this->init_hooks();
	}
	
	private function init_hooks() {
		add_action( 'init', array( $this, 'create_templates_directory' ) );
		add_filter( 'shopglut_pdf_invoice_template_path', array( $this, 'get_template_path' ), 10, 2 );
		add_filter( 'shopglut_pdf_invoice_css_styles', array( $this, 'get_template_styles' ) );
	}
	
	public function create_templates_directory() {
		if ( ! file_exists( $this->template_dir ) ) {
			wp_mkdir_p( $this->template_dir );
			$this->create_default_templates();
		}
	}
	
	private function create_default_templates() {
		$this->create_default_invoice_template();
		$this->create_classic_invoice_template();
		$this->create_modern_invoice_template();
		$this->create_minimal_invoice_template();
		
		$this->create_default_packing_slip_template();
		$this->create_classic_packing_slip_template();
		$this->create_modern_packing_slip_template();
		$this->create_minimal_packing_slip_template();
		
		$this->create_template_css();
	}
	
	private function create_default_invoice_template() {
		$template_content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $invoice_number ); ?></title>
    <style>
        <?php echo $this->get_template_css(); ?>
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid <?php echo $template_settings["primary_color"]; ?>;
            padding-bottom: 20px;
        }
        .company-logo img {
            max-height: <?php echo $company_info["logo_height"]; ?>;
        }
        .invoice-title {
            font-size: 36px;
            color: <?php echo $template_settings["primary_color"]; ?>;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-logo">
                <?php if ( $company_info["logo"] ): ?>
                    <img src="<?php echo esc_url( wp_get_attachment_image_src( $company_info["logo"], "full" )[0] ); ?>" alt="<?php echo esc_attr( $company_info["name"] ); ?>">
                <?php endif; ?>
            </div>
            <div class="invoice-info">
                <h1 class="invoice-title"><?php echo esc_html__( "INVOICE", "shopglut" ); ?></h1>
                <?php if ( $display_settings["show_invoice_number"] ): ?>
                    <p><strong><?php echo esc_html__( "Invoice #:", "shopglut" ); ?></strong> <?php echo esc_html( $invoice_number ); ?></p>
                <?php endif; ?>
                <?php if ( $display_settings["show_invoice_date"] ): ?>
                    <p><strong><?php echo esc_html__( "Date:", "shopglut" ); ?></strong> <?php echo esc_html( date_i18n( get_option( "date_format" ), strtotime( $invoice_date ) ) ); ?></p>
                <?php endif; ?>
                <?php if ( $due_date && $display_settings["show_due_date"] ): ?>
                    <p><strong><?php echo esc_html__( "Due Date:", "shopglut" ); ?></strong> <?php echo esc_html( date_i18n( get_option( "date_format" ), strtotime( $due_date ) ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="addresses">
            <div class="company-address">
                <h3><?php echo esc_html__( "From:", "shopglut" ); ?></h3>
                <p><strong><?php echo esc_html( $company_info["name"] ); ?></strong></p>
                <?php if ( $company_info["address"] ): ?>
                    <p><?php echo nl2br( esc_html( $company_info["address"] ) ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info["city"] || $company_info["postcode"] ): ?>
                    <p><?php echo esc_html( trim( $company_info["city"] . " " . $company_info["postcode"] ) ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info["country"] ): ?>
                    <p><?php echo esc_html( WC()->countries->countries[ $company_info["country"] ] ?? $company_info["country"] ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info["phone"] ): ?>
                    <p><?php echo esc_html__( "Phone:", "shopglut" ); ?> <?php echo esc_html( $company_info["phone"] ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info["email"] ): ?>
                    <p><?php echo esc_html__( "Email:", "shopglut" ); ?> <?php echo esc_html( $company_info["email"] ); ?></p>
                <?php endif; ?>
                <?php if ( $company_info["tax_number"] ): ?>
                    <p><?php echo esc_html__( "Tax Number:", "shopglut" ); ?> <?php echo esc_html( $company_info["tax_number"] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="customer-address">
                <h3><?php echo esc_html__( "To:", "shopglut" ); ?></h3>
                <p><strong><?php echo esc_html( $order->get_billing_first_name() . " " . $order->get_billing_last_name() ); ?></strong></p>
                <?php if ( $order->get_billing_company() ): ?>
                    <p><?php echo esc_html( $order->get_billing_company() ); ?></p>
                <?php endif; ?>
                <p><?php echo esc_html( $order->get_billing_address_1() ); ?></p>
                <?php if ( $order->get_billing_address_2() ): ?>
                    <p><?php echo esc_html( $order->get_billing_address_2() ); ?></p>
                <?php endif; ?>
                <p><?php echo esc_html( $order->get_billing_city() . " " . $order->get_billing_postcode() ); ?></p>
                <p><?php echo esc_html( WC()->countries->countries[ $order->get_billing_country() ] ?? $order->get_billing_country() ); ?></p>
                <?php if ( $display_settings["show_email"] && $order->get_billing_email() ): ?>
                    <p><?php echo esc_html__( "Email:", "shopglut" ); ?> <?php echo esc_html( $order->get_billing_email() ); ?></p>
                <?php endif; ?>
                <?php if ( $display_settings["show_phone"] && $order->get_billing_phone() ): ?>
                    <p><?php echo esc_html__( "Phone:", "shopglut" ); ?> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <table class="invoice-items">
            <thead>
                <tr>
                    <th><?php echo esc_html__( "Item", "shopglut" ); ?></th>
                    <th><?php echo esc_html__( "Qty", "shopglut" ); ?></th>
                    <th><?php echo esc_html__( "Price", "shopglut" ); ?></th>
                    <th><?php echo esc_html__( "Total", "shopglut" ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $order->get_items() as $item_id => $item ): ?>
                    <?php 
                    $product = $item->get_product();
                    $item_total = $item->get_total();
                    if ( $item_total == 0 && ( !isset( $this->settings["show_free_line_items"] ) || !$this->settings["show_free_line_items"] ) ) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html( $item->get_name() ); ?></strong>
                            <?php if ( $product && $product->get_sku() ): ?>
                                <br><small><?php echo esc_html__( "SKU:", "shopglut" ); ?> <?php echo esc_html( $product->get_sku() ); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                        <td><?php echo wc_price( $item->get_total() / $item->get_quantity(), array( "currency" => $order->get_currency() ) ); ?></td>
                        <td><?php echo wc_price( $item->get_total(), array( "currency" => $order->get_currency() ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="invoice-totals">
            <table>
                <tr>
                    <td><?php echo esc_html__( "Subtotal:", "shopglut" ); ?></td>
                    <td><?php echo wc_price( $order->get_subtotal(), array( "currency" => $order->get_currency() ) ); ?></td>
                </tr>
                <?php if ( $order->get_total_shipping() > 0 ): ?>
                    <tr>
                        <td><?php echo esc_html__( "Shipping:", "shopglut" ); ?></td>
                        <td><?php echo wc_price( $order->get_total_shipping(), array( "currency" => $order->get_currency() ) ); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ( $order->get_total_tax() > 0 ): ?>
                    <tr>
                        <td><?php echo esc_html__( "Tax:", "shopglut" ); ?></td>
                        <td><?php echo wc_price( $order->get_total_tax(), array( "currency" => $order->get_currency() ) ); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td><strong><?php echo esc_html__( "Total:", "shopglut" ); ?></strong></td>
                    <td><strong><?php echo wc_price( $order->get_total(), array( "currency" => $order->get_currency() ) ); ?></strong></td>
                </tr>
            </table>
        </div>

        <?php if ( $display_settings["show_customer_notes"] && $order->get_customer_note() ): ?>
            <div class="customer-notes">
                <h3><?php echo esc_html__( "Customer Notes:", "shopglut" ); ?></h3>
                <p><?php echo nl2br( esc_html( $order->get_customer_note() ) ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( $template_settings["footer_text"] ): ?>
            <div class="invoice-footer">
                <p><?php echo nl2br( esc_html( $template_settings["footer_text"] ) ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>';

		file_put_contents( $this->template_dir . 'invoice-default.php', $template_content );
	}
	
	private function create_classic_invoice_template() {
		$template_content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $invoice_number ); ?></title>
    <style>
        <?php echo $this->get_template_css(); ?>
        .classic-header {
            text-align: center;
            margin-bottom: 40px;
            border: 2px solid <?php echo $template_settings["primary_color"]; ?>;
            padding: 20px;
        }
        .classic-title {
            font-size: 28px;
            color: <?php echo $template_settings["primary_color"]; ?>;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="invoice-container classic-template">
        <div class="classic-header">
            <?php if ( $company_info["logo"] ): ?>
                <img src="<?php echo esc_url( wp_get_attachment_image_src( $company_info["logo"], "full" )[0] ); ?>" alt="<?php echo esc_attr( $company_info["name"] ); ?>" style="max-height: <?php echo $company_info["logo_height"]; ?>; margin-bottom: 20px;">
            <?php endif; ?>
            <h1 class="classic-title"><?php echo esc_html( $company_info["name"] ); ?></h1>
            <p><?php echo esc_html__( "INVOICE", "shopglut" ); ?></p>
        </div>
        <!-- Rest of classic template content similar to default but with classic styling -->
    </div>
</body>
</html>';

		file_put_contents( $this->template_dir . 'invoice-classic.php', $template_content );
	}
	
	private function create_modern_invoice_template() {
		$template_content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $invoice_number ); ?></title>
    <style>
        <?php echo $this->get_template_css(); ?>
        .modern-header {
            background: linear-gradient(135deg, <?php echo $template_settings["primary_color"]; ?>, <?php echo $template_settings["secondary_color"]; ?>);
            color: <?php echo $template_settings["header_text_color"]; ?>;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        .modern-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container modern-template">
        <div class="modern-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <?php if ( $company_info["logo"] ): ?>
                        <img src="<?php echo esc_url( wp_get_attachment_image_src( $company_info["logo"], "full" )[0] ); ?>" alt="<?php echo esc_attr( $company_info["name"] ); ?>" style="max-height: <?php echo $company_info["logo_height"]; ?>;">
                    <?php endif; ?>
                </div>
                <div style="text-align: right;">
                    <h1 style="margin: 0; font-size: 36px;"><?php echo esc_html__( "INVOICE", "shopglut" ); ?></h1>
                    <p style="margin: 5px 0 0 0;"><?php echo esc_html( $invoice_number ); ?></p>
                </div>
            </div>
        </div>
        
        <?php if ( $template_settings["extra_field_1"] || $template_settings["extra_field_2"] || $template_settings["extra_field_3"] ): ?>
            <div class="modern-grid modern-footer">
                <?php if ( $template_settings["extra_field_1"] ): ?>
                    <div><?php echo nl2br( esc_html( $template_settings["extra_field_1"] ) ); ?></div>
                <?php endif; ?>
                <?php if ( $template_settings["extra_field_2"] ): ?>
                    <div><?php echo nl2br( esc_html( $template_settings["extra_field_2"] ) ); ?></div>
                <?php endif; ?>
                <?php if ( $template_settings["extra_field_3"] ): ?>
                    <div><?php echo nl2br( esc_html( $template_settings["extra_field_3"] ) ); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Rest of modern template content -->
    </div>
</body>
</html>';

		file_put_contents( $this->template_dir . 'invoice-modern.php', $template_content );
	}
	
	private function create_minimal_invoice_template() {
		$template_content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $invoice_number ); ?></title>
    <style>
        <?php echo $this->get_template_css(); ?>
        .minimal-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 40px;
            padding-bottom: 20px;
        }
        .minimal-title {
            font-size: 24px;
            color: #333;
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="invoice-container minimal-template">
        <div class="minimal-header">
            <h1 class="minimal-title"><?php echo esc_html__( "Invoice", "shopglut" ); ?> <?php echo esc_html( $invoice_number ); ?></h1>
            <?php if ( $company_info["name"] ): ?>
                <p style="margin: 10px 0 0 0; color: #666;"><?php echo esc_html( $company_info["name"] ); ?></p>
            <?php endif; ?>
        </div>
        <!-- Rest of minimal template content -->
    </div>
</body>
</html>';

		file_put_contents( $this->template_dir . 'invoice-minimal.php', $template_content );
	}
	
	private function create_default_packing_slip_template() {
		$template_content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $packing_slip_number ); ?></title>
    <style>
        <?php echo $this->get_template_css(); ?>
        .packing-slip-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid <?php echo $template_settings["primary_color"]; ?>;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="packing-slip-header">
            <div class="company-logo">
                <?php if ( $company_info["logo"] ): ?>
                    <img src="<?php echo esc_url( wp_get_attachment_image_src( $company_info["logo"], "full" )[0] ); ?>" alt="<?php echo esc_attr( $company_info["name"] ); ?>" style="max-height: <?php echo $company_info["logo_height"]; ?>;">
                <?php endif; ?>
            </div>
            <div class="packing-slip-info">
                <h1 style="color: <?php echo $template_settings["primary_color"]; ?>; font-size: 36px; margin: 0;"><?php echo esc_html__( "PACKING SLIP", "shopglut" ); ?></h1>
                <p><strong><?php echo esc_html__( "Packing Slip #:", "shopglut" ); ?></strong> <?php echo esc_html( $packing_slip_number ); ?></p>
                <p><strong><?php echo esc_html__( "Date:", "shopglut" ); ?></strong> <?php echo esc_html( date_i18n( get_option( "date_format" ), strtotime( $packing_date ) ) ); ?></p>
                <p><strong><?php echo esc_html__( "Order #:", "shopglut" ); ?></strong> <?php echo esc_html( $order->get_order_number() ); ?></p>
            </div>
        </div>

        <div class="addresses">
            <div class="company-address">
                <h3><?php echo esc_html__( "From:", "shopglut" ); ?></h3>
                <p><strong><?php echo esc_html( $company_info["name"] ); ?></strong></p>
                <?php if ( $company_info["address"] ): ?>
                    <p><?php echo nl2br( esc_html( $company_info["address"] ) ); ?></p>
                <?php endif; ?>
            </div>

            <div class="customer-address">
                <h3><?php echo esc_html__( "Ship To:", "shopglut" ); ?></h3>
                <?php if ( $order->get_shipping_first_name() ): ?>
                    <p><strong><?php echo esc_html( $order->get_shipping_first_name() . " " . $order->get_shipping_last_name() ); ?></strong></p>
                    <?php if ( $order->get_shipping_company() ): ?>
                        <p><?php echo esc_html( $order->get_shipping_company() ); ?></p>
                    <?php endif; ?>
                    <p><?php echo esc_html( $order->get_shipping_address_1() ); ?></p>
                    <?php if ( $order->get_shipping_address_2() ): ?>
                        <p><?php echo esc_html( $order->get_shipping_address_2() ); ?></p>
                    <?php endif; ?>
                    <p><?php echo esc_html( $order->get_shipping_city() . " " . $order->get_shipping_postcode() ); ?></p>
                    <p><?php echo esc_html( WC()->countries->countries[ $order->get_shipping_country() ] ?? $order->get_shipping_country() ); ?></p>
                <?php else: ?>
                    <p><strong><?php echo esc_html( $order->get_billing_first_name() . " " . $order->get_billing_last_name() ); ?></strong></p>
                    <p><?php echo esc_html( $order->get_billing_address_1() ); ?></p>
                    <p><?php echo esc_html( $order->get_billing_city() . " " . $order->get_billing_postcode() ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <table class="invoice-items">
            <thead>
                <tr>
                    <th><?php echo esc_html__( "Item", "shopglut" ); ?></th>
                    <?php if ( $show_sku ): ?><th><?php echo esc_html__( "SKU", "shopglut" ); ?></th><?php endif; ?>
                    <th><?php echo esc_html__( "Qty", "shopglut" ); ?></th>
                    <?php if ( $show_weight ): ?><th><?php echo esc_html__( "Weight", "shopglut" ); ?></th><?php endif; ?>
                    <?php if ( $show_prices ): ?><th><?php echo esc_html__( "Price", "shopglut" ); ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $order->get_items() as $item_id => $item ): ?>
                    <?php $product = $item->get_product(); ?>
                    <tr>
                        <td><strong><?php echo esc_html( $item->get_name() ); ?></strong></td>
                        <?php if ( $show_sku ): ?>
                            <td><?php echo $product && $product->get_sku() ? esc_html( $product->get_sku() ) : "-"; ?></td>
                        <?php endif; ?>
                        <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                        <?php if ( $show_weight ): ?>
                            <td><?php echo $product && $product->get_weight() ? esc_html( $product->get_weight() . " " . get_option( "woocommerce_weight_unit" ) ) : "-"; ?></td>
                        <?php endif; ?>
                        <?php if ( $show_prices ): ?>
                            <td><?php echo wc_price( $item->get_total(), array( "currency" => $order->get_currency() ) ); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ( $display_settings["show_customer_notes"] && $order->get_customer_note() ): ?>
            <div class="customer-notes">
                <h3><?php echo esc_html__( "Customer Notes:", "shopglut" ); ?></h3>
                <p><?php echo nl2br( esc_html( $order->get_customer_note() ) ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( $template_settings["footer_text"] ): ?>
            <div class="invoice-footer">
                <p><?php echo nl2br( esc_html( $template_settings["footer_text"] ) ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>';

		file_put_contents( $this->template_dir . 'packing-slip-default.php', $template_content );
	}
	
	private function create_classic_packing_slip_template() {
		$content = str_replace( 'PACKING SLIP', 'PACKING LIST', file_get_contents( $this->template_dir . 'packing-slip-default.php' ) );
		file_put_contents( $this->template_dir . 'packing-slip-classic.php', $content );
	}
	
	private function create_modern_packing_slip_template() {
		$content = str_replace( 'packing-slip-header', 'modern-header', file_get_contents( $this->template_dir . 'packing-slip-default.php' ) );
		file_put_contents( $this->template_dir . 'packing-slip-modern.php', $content );
	}
	
	private function create_minimal_packing_slip_template() {
		$content = str_replace( 'packing-slip-header', 'minimal-header', file_get_contents( $this->template_dir . 'packing-slip-default.php' ) );
		file_put_contents( $this->template_dir . 'packing-slip-minimal.php', $content );
	}
	
	private function create_template_css() {
		$css_content = 'body {
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: #333;
    margin: 0;
    padding: 20px;
    background: #fff;
}

.invoice-container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 40px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.addresses {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
}

.addresses > div {
    width: 48%;
}

.addresses h3 {
    margin-top: 0;
    color: #666;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.invoice-items {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.invoice-items th,
.invoice-items td {
    padding: 12px 8px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.invoice-items th {
    background-color: #f8f9fa;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}

.invoice-items tr:hover {
    background-color: #f8f9fa;
}

.invoice-totals {
    margin-top: 40px;
    text-align: right;
}

.invoice-totals table {
    margin-left: auto;
    width: 300px;
}

.invoice-totals td {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
}

.invoice-totals .total-row td {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    font-size: 18px;
}

.customer-notes {
    margin-top: 40px;
    padding: 20px;
    background-color: #f8f9fa;
    border-left: 4px solid #007cba;
}

.invoice-footer {
    margin-top: 40px;
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
    color: #666;
}

@media print {
    body {
        padding: 0;
    }
    
    .invoice-container {
        box-shadow: none;
        padding: 20px;
    }
}';

		file_put_contents( $this->template_dir . 'invoice-styles.css', $css_content );
	}
	
	public function get_template_path( $template_name, $document_type = 'invoice' ) {
		$template_file = $this->template_dir . $document_type . '-' . $template_name . '.php';
		
		if ( file_exists( $template_file ) ) {
			return $template_file;
		}
		
		return $this->template_dir . $document_type . '-default.php';
	}
	
	public function get_template_styles() {
		$css_file = $this->template_dir . 'invoice-styles.css';
		
		if ( file_exists( $css_file ) ) {
			return file_get_contents( $css_file );
		}
		
		return '';
	}
	
	private function get_template_css() {
		return $this->get_template_styles();
	}
}