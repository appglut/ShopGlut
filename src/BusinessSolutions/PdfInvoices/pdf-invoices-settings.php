<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$SHOPGLUT_PDF_INVOICES_OPTIONS = 'shopglut_pdf_invoices_options';

// Create PDF Invoices options
AGSHOPGLUT::createOptions( $SHOPGLUT_PDF_INVOICES_OPTIONS, array(
	// menu settings
	'menu_title' => esc_html__( 'PDF Invoices & Packing Slips Settings', 'shopglut' ),
	'show_bar_menu' => false,
	'hide_menu' => true,
	'menu_slug' => 'shopglut_pdf_invoices_settings',
	'menu_parent' => 'shopglut_layouts',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'PDF Invoices & Packing Slips Settings', 'shopglut' ),
	'show_reset_section' => true,
	'shortcode_option' => '[shopglut_pdf_invoices]',
	'framework_class' => 'shopglut_pdf_invoices_settings',
	'footer_credit' => __( "ShopGlut (PDF Invoices & Packing Slips)", 'shopglut' ),
	'menu_position' => 4
) );

//
// Create a top-tab for General Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'id' => 'general_tab',
	'title' => __( 'General Settings', 'shopglut' ),
	'icon' => 'fa fa-cog',
) );

// Create a sub-tab for Invoice Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'general_tab',
	'title' => __( 'Invoice Settings', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'enable_pdf_invoices',
			'type' => 'switcher',
			'title' => __( 'Enable PDF Invoices', 'shopglut' ),
			'subtitle' => __( 'Enable automatic PDF invoice generation for WooCommerce orders', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'auto_attach_invoice',
			'type' => 'switcher',
			'title' => __( 'Auto Attach to Emails', 'shopglut' ),
			'subtitle' => __( 'Automatically attach PDF invoices to order confirmation emails', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable_pdf_invoices', '==', '1' ),
		),

		array(
			'id' => 'invoice_email_types',
			'type' => 'checkbox',
			'title' => __( 'Attach to Email Types', 'shopglut' ),
			'subtitle' => __( 'Select which email types should include the PDF invoice', 'shopglut' ),
			'options' => array(
				'new_order' => __( 'New order (Admin email)', 'shopglut' ),
				'cancelled_order' => __( 'Cancelled order (Admin)', 'shopglut' ),
				'customer_cancelled_order' => __( 'Cancelled order (Customer)', 'shopglut' ),
				'failed_order' => __( 'Failed order (Admin)', 'shopglut' ),
				'customer_failed_order' => __( 'Failed order (Customer)', 'shopglut' ),
				'customer_on_hold_order' => __( 'Order on-hold', 'shopglut' ),
				'customer_processing_order' => __( 'Processing order', 'shopglut' ),
				'customer_completed_order' => __( 'Completed order', 'shopglut' ),
				'customer_refunded_order' => __( 'Refunded order', 'shopglut' ),
				'customer_invoice' => __( 'Order details (Manual email)', 'shopglut' ),
				'customer_note' => __( 'Customer note', 'shopglut' ),
			),
			'default' => array( 'new_order', 'customer_on_hold_order', 'customer_completed_order' ),
			'dependency' => array( 'auto_attach_invoice', '==', '1' ),
		),

		array(
			'id' => 'disable_for_statuses',
			'type' => 'checkbox',
			'title' => __( 'Disable for Order Statuses', 'shopglut' ),
			'subtitle' => __( 'Select order statuses for which invoices should not be generated', 'shopglut' ),
			'options' => array(
				'pending' => __( 'Pending payment', 'shopglut' ),
				'processing' => __( 'Processing', 'shopglut' ),
				'on-hold' => __( 'On hold', 'shopglut' ),
				'completed' => __( 'Completed', 'shopglut' ),
				'cancelled' => __( 'Cancelled', 'shopglut' ),
				'refunded' => __( 'Refunded', 'shopglut' ),
				'failed' => __( 'Failed', 'shopglut' ),
			),
			'default' => array(),
			'dependency' => array( 'enable_pdf_invoices', '==', '1' ),
		),

		array(
			'id' => 'invoice_number_format',
			'type' => 'select',
			'title' => __( 'Invoice Number Format', 'shopglut' ),
			'subtitle' => __( 'Choose how invoice numbers should be formatted', 'shopglut' ),
			'options' => array(
				'order_number' => __( 'Use Order Number', 'shopglut' ),
				'sequential' => __( 'Sequential Numbers', 'shopglut' ),
				'custom' => __( 'Custom Format', 'shopglut' ),
			),
			'default' => 'sequential',
			'dependency' => array( 'enable_pdf_invoices', '==', '1' ),
		),

		array(
			'id' => 'invoice_number_prefix',
			'type' => 'text',
			'title' => __( 'Invoice Number Prefix', 'shopglut' ),
			'subtitle' => __( 'Add a prefix to invoice numbers (e.g., INV-)', 'shopglut' ),
			'default' => 'INV-',
			'dependency' => array( 'invoice_number_format', '!=', 'order_number' ),
		),

		array(
			'id' => 'invoice_number_suffix',
			'type' => 'text',
			'title' => __( 'Invoice Number Suffix', 'shopglut' ),
			'subtitle' => __( 'Add a suffix to invoice numbers', 'shopglut' ),
			'default' => '',
			'dependency' => array( 'invoice_number_format', '!=', 'order_number' ),
		),

		array(
			'id' => 'invoice_number_padding',
			'type' => 'number',
			'title' => __( 'Number Padding', 'shopglut' ),
			'subtitle' => __( 'Minimum number of digits for invoice numbers (e.g., 4 = 0001)', 'shopglut' ),
			'default' => 4,
			'min' => 1,
			'max' => 10,
			'dependency' => array( 'invoice_number_format', '!=', 'order_number' ),
		),

		array(
			'id' => 'my_account_buttons',
			'type' => 'select',
			'title' => __( 'Allow My Account Invoice Download', 'shopglut' ),
			'subtitle' => __( 'Control when customers can download invoices from their account page', 'shopglut' ),
			'options' => array(
				'available' => __( 'Only when an invoice is already created/emailed', 'shopglut' ),
				'custom' => __( 'Only for specific order statuses (define below)', 'shopglut' ),
				'always' => __( 'Always', 'shopglut' ),
				'never' => __( 'Never', 'shopglut' ),
			),
			'default' => 'available',
			'dependency' => array( 'enable_pdf_invoices', '==', '1' ),
		),

		array(
			'id' => 'my_account_restrict_statuses',
			'type' => 'checkbox',
			'title' => __( 'My Account Restrict to Statuses', 'shopglut' ),
			'subtitle' => __( 'Select order statuses for which download is allowed', 'shopglut' ),
			'options' => array(
				'pending' => __( 'Pending payment', 'shopglut' ),
				'processing' => __( 'Processing', 'shopglut' ),
				'on-hold' => __( 'On hold', 'shopglut' ),
				'completed' => __( 'Completed', 'shopglut' ),
				'cancelled' => __( 'Cancelled', 'shopglut' ),
				'refunded' => __( 'Refunded', 'shopglut' ),
				'failed' => __( 'Failed', 'shopglut' ),
			),
			'default' => array( 'completed' ),
			'dependency' => array( 'my_account_buttons', '==', 'custom' ),
		),

	)
) );

// Create a sub-tab for Document Details
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'general_tab',
	'title' => __( 'Document Details', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'display_email',
			'type' => 'switcher',
			'title' => __( 'Display Email Address', 'shopglut' ),
			'subtitle' => __( 'Show customer email address on invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'display_phone',
			'type' => 'switcher',
			'title' => __( 'Display Phone Number', 'shopglut' ),
			'subtitle' => __( 'Show customer phone number on invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'display_customer_notes',
			'type' => 'switcher',
			'title' => __( 'Display Customer Notes', 'shopglut' ),
			'subtitle' => __( 'Show customer notes on invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'display_shipping_address',
			'type' => 'select',
			'title' => __( 'Display Shipping Address', 'shopglut' ),
			'subtitle' => __( 'Control when to show shipping address on invoices', 'shopglut' ),
			'options' => array(
				'' => __( 'No', 'shopglut' ),
				'when_different' => __( 'Only when different from billing address', 'shopglut' ),
				'always' => __( 'Always', 'shopglut' ),
			),
			'default' => '',
		),

		array(
			'id' => 'display_invoice_number',
			'type' => 'select',
			'title' => __( 'Display Invoice Number', 'shopglut' ),
			'subtitle' => __( 'Choose which number to display on invoices', 'shopglut' ),
			'options' => array(
				'' => __( 'No', 'shopglut' ),
				'invoice_number' => __( 'Invoice Number', 'shopglut' ),
				'order_number' => __( 'Order Number', 'shopglut' ),
			),
			'default' => '',
		),

		array(
			'id' => 'display_invoice_date',
			'type' => 'select',
			'title' => __( 'Display Invoice Date', 'shopglut' ),
			'subtitle' => __( 'Choose which date to display on invoices', 'shopglut' ),
			'options' => array(
				'' => __( 'No', 'shopglut' ),
				'document_date' => __( 'Invoice Date', 'shopglut' ),
				'order_date' => __( 'Order Date', 'shopglut' ),
			),
			'default' => '',
		),

		array(
			'id' => 'display_due_date',
			'type' => 'switcher',
			'title' => __( 'Display Due Date', 'shopglut' ),
			'subtitle' => __( 'Show due date on invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'due_date_days',
			'type' => 'number',
			'title' => __( 'Due Date Days', 'shopglut' ),
			'subtitle' => __( 'Number of days after invoice date for due date', 'shopglut' ),
			'default' => 30,
			'min' => 1,
			'max' => 365,
			'dependency' => array( 'display_due_date', '==', '1' ),
		),

	)
) );

// Create a sub-tab for Packing Slip Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'general_tab',
	'title' => __( 'Packing Slip Settings', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'enable_packing_slips',
			'type' => 'switcher',
			'title' => __( 'Enable Packing Slips', 'shopglut' ),
			'subtitle' => __( 'Enable PDF packing slip generation for WooCommerce orders', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'auto_attach_packing_slip',
			'type' => 'switcher',
			'title' => __( 'Auto Attach to Emails', 'shopglut' ),
			'subtitle' => __( 'Automatically attach packing slips to order emails', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
			'dependency' => array( 'enable_packing_slips', '==', '1' ),
		),

		array(
			'id' => 'packing_slip_email_types',
			'type' => 'checkbox',
			'title' => __( 'Attach to Email Types', 'shopglut' ),
			'subtitle' => __( 'Select which email types should include the packing slip', 'shopglut' ),
			'options' => array(
				'customer_completed_order' => __( 'Order Completed', 'shopglut' ),
				'customer_processing_order' => __( 'Order Processing', 'shopglut' ),
				'customer_on_hold_order' => __( 'Order On-Hold', 'shopglut' ),
			),
			'default' => array( 'customer_processing_order' ),
			'dependency' => array( 'auto_attach_packing_slip', '==', '1' ),
		),

		array(
			'id' => 'show_sku_on_packing_slip',
			'type' => 'switcher',
			'title' => __( 'Show Product SKU', 'shopglut' ),
			'subtitle' => __( 'Display product SKUs on packing slips', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'enable_packing_slips', '==', '1' ),
		),

		array(
			'id' => 'show_weight_on_packing_slip',
			'type' => 'switcher',
			'title' => __( 'Show Product Weight', 'shopglut' ),
			'subtitle' => __( 'Display product weights on packing slips', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
			'dependency' => array( 'enable_packing_slips', '==', '1' ),
		),

	)
) );

//
// Create a top-tab for Template Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'id' => 'template_tab',
	'title' => __( 'Template Settings', 'shopglut' ),
	'icon' => 'fa fa-file-text',
) );

// Create a sub-tab for Company Information
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'template_tab',
	'title' => __( 'Company Information', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'company_logo',
			'type' => 'media',
			'title' => __( 'Company Logo', 'shopglut' ),
			'subtitle' => __( 'Upload your company logo to display on invoices and packing slips', 'shopglut' ),
			'library' => 'image',
		),

		array(
			'id' => 'logo_height',
			'type' => 'text',
			'title' => __( 'Logo Height', 'shopglut' ),
			'subtitle' => __( 'Enter the total height of the logo in mm, cm or in and use a dot for decimals. For example: 1.15in or 40mm', 'shopglut' ),
			'default' => '40mm',
			'dependency' => array( 'company_logo', '!=', '' ),
		),

		array(
			'id' => 'company_name',
			'type' => 'text',
			'title' => __( 'Company Name', 'shopglut' ),
			'subtitle' => __( 'Your company name as it should appear on documents', 'shopglut' ),
			'default' => get_bloginfo( 'name' ),
		),

		array(
			'id' => 'company_address',
			'type' => 'textarea',
			'title' => __( 'Company Address', 'shopglut' ),
			'subtitle' => __( 'Your complete business address', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'company_country',
			'type' => 'select',
			'title' => __( 'Company Country', 'shopglut' ),
			'subtitle' => __( 'The country in which your business is located', 'shopglut' ),
			'options' => array(
				'' => __( 'Select a country', 'shopglut' ),
				'AF' => __( 'Afghanistan', 'shopglut' ),
				'AL' => __( 'Albania', 'shopglut' ),
				'DZ' => __( 'Algeria', 'shopglut' ),
				'AD' => __( 'Andorra', 'shopglut' ),
				'AO' => __( 'Angola', 'shopglut' ),
				'AR' => __( 'Argentina', 'shopglut' ),
				'AM' => __( 'Armenia', 'shopglut' ),
				'AU' => __( 'Australia', 'shopglut' ),
				'AT' => __( 'Austria', 'shopglut' ),
				'AZ' => __( 'Azerbaijan', 'shopglut' ),
				'BS' => __( 'Bahamas', 'shopglut' ),
				'BH' => __( 'Bahrain', 'shopglut' ),
				'BD' => __( 'Bangladesh', 'shopglut' ),
				'BB' => __( 'Barbados', 'shopglut' ),
				'BY' => __( 'Belarus', 'shopglut' ),
				'BE' => __( 'Belgium', 'shopglut' ),
				'BZ' => __( 'Belize', 'shopglut' ),
				'BJ' => __( 'Benin', 'shopglut' ),
				'BT' => __( 'Bhutan', 'shopglut' ),
				'BO' => __( 'Bolivia', 'shopglut' ),
				'BA' => __( 'Bosnia and Herzegovina', 'shopglut' ),
				'BW' => __( 'Botswana', 'shopglut' ),
				'BR' => __( 'Brazil', 'shopglut' ),
				'BN' => __( 'Brunei', 'shopglut' ),
				'BG' => __( 'Bulgaria', 'shopglut' ),
				'BF' => __( 'Burkina Faso', 'shopglut' ),
				'BI' => __( 'Burundi', 'shopglut' ),
				'KH' => __( 'Cambodia', 'shopglut' ),
				'CM' => __( 'Cameroon', 'shopglut' ),
				'CA' => __( 'Canada', 'shopglut' ),
				'CV' => __( 'Cape Verde', 'shopglut' ),
				'CF' => __( 'Central African Republic', 'shopglut' ),
				'TD' => __( 'Chad', 'shopglut' ),
				'CL' => __( 'Chile', 'shopglut' ),
				'CN' => __( 'China', 'shopglut' ),
				'CO' => __( 'Colombia', 'shopglut' ),
				'KM' => __( 'Comoros', 'shopglut' ),
				'CG' => __( 'Congo (Brazzaville)', 'shopglut' ),
				'CD' => __( 'Congo (Kinshasa)', 'shopglut' ),
				'CR' => __( 'Costa Rica', 'shopglut' ),
				'HR' => __( 'Croatia', 'shopglut' ),
				'CU' => __( 'Cuba', 'shopglut' ),
				'CY' => __( 'Cyprus', 'shopglut' ),
				'CZ' => __( 'Czech Republic', 'shopglut' ),
				'DK' => __( 'Denmark', 'shopglut' ),
				'DJ' => __( 'Djibouti', 'shopglut' ),
				'DM' => __( 'Dominica', 'shopglut' ),
				'DO' => __( 'Dominican Republic', 'shopglut' ),
				'EC' => __( 'Ecuador', 'shopglut' ),
				'EG' => __( 'Egypt', 'shopglut' ),
				'SV' => __( 'El Salvador', 'shopglut' ),
				'GQ' => __( 'Equatorial Guinea', 'shopglut' ),
				'ER' => __( 'Eritrea', 'shopglut' ),
				'EE' => __( 'Estonia', 'shopglut' ),
				'SZ' => __( 'Eswatini', 'shopglut' ),
				'ET' => __( 'Ethiopia', 'shopglut' ),
				'FJ' => __( 'Fiji', 'shopglut' ),
				'FI' => __( 'Finland', 'shopglut' ),
				'FR' => __( 'France', 'shopglut' ),
				'GA' => __( 'Gabon', 'shopglut' ),
				'GM' => __( 'Gambia', 'shopglut' ),
				'GE' => __( 'Georgia', 'shopglut' ),
				'DE' => __( 'Germany', 'shopglut' ),
				'GH' => __( 'Ghana', 'shopglut' ),
				'GR' => __( 'Greece', 'shopglut' ),
				'GD' => __( 'Grenada', 'shopglut' ),
				'GT' => __( 'Guatemala', 'shopglut' ),
				'GN' => __( 'Guinea', 'shopglut' ),
				'GW' => __( 'Guinea-Bissau', 'shopglut' ),
				'GY' => __( 'Guyana', 'shopglut' ),
				'HT' => __( 'Haiti', 'shopglut' ),
				'HN' => __( 'Honduras', 'shopglut' ),
				'HU' => __( 'Hungary', 'shopglut' ),
				'IS' => __( 'Iceland', 'shopglut' ),
				'IN' => __( 'India', 'shopglut' ),
				'ID' => __( 'Indonesia', 'shopglut' ),
				'IR' => __( 'Iran', 'shopglut' ),
				'IQ' => __( 'Iraq', 'shopglut' ),
				'IE' => __( 'Ireland', 'shopglut' ),
				'IL' => __( 'Israel', 'shopglut' ),
				'IT' => __( 'Italy', 'shopglut' ),
				'CI' => __( 'Ivory Coast', 'shopglut' ),
				'JM' => __( 'Jamaica', 'shopglut' ),
				'JP' => __( 'Japan', 'shopglut' ),
				'JO' => __( 'Jordan', 'shopglut' ),
				'KZ' => __( 'Kazakhstan', 'shopglut' ),
				'KE' => __( 'Kenya', 'shopglut' ),
				'KI' => __( 'Kiribati', 'shopglut' ),
				'KW' => __( 'Kuwait', 'shopglut' ),
				'KG' => __( 'Kyrgyzstan', 'shopglut' ),
				'LA' => __( 'Laos', 'shopglut' ),
				'LV' => __( 'Latvia', 'shopglut' ),
				'LB' => __( 'Lebanon', 'shopglut' ),
				'LS' => __( 'Lesotho', 'shopglut' ),
				'LR' => __( 'Liberia', 'shopglut' ),
				'LY' => __( 'Libya', 'shopglut' ),
				'LI' => __( 'Liechtenstein', 'shopglut' ),
				'LT' => __( 'Lithuania', 'shopglut' ),
				'LU' => __( 'Luxembourg', 'shopglut' ),
				'MG' => __( 'Madagascar', 'shopglut' ),
				'MW' => __( 'Malawi', 'shopglut' ),
				'MY' => __( 'Malaysia', 'shopglut' ),
				'MV' => __( 'Maldives', 'shopglut' ),
				'ML' => __( 'Mali', 'shopglut' ),
				'MT' => __( 'Malta', 'shopglut' ),
				'MH' => __( 'Marshall Islands', 'shopglut' ),
				'MR' => __( 'Mauritania', 'shopglut' ),
				'MU' => __( 'Mauritius', 'shopglut' ),
				'MX' => __( 'Mexico', 'shopglut' ),
				'FM' => __( 'Micronesia', 'shopglut' ),
				'MD' => __( 'Moldova', 'shopglut' ),
				'MC' => __( 'Monaco', 'shopglut' ),
				'MN' => __( 'Mongolia', 'shopglut' ),
				'ME' => __( 'Montenegro', 'shopglut' ),
				'MA' => __( 'Morocco', 'shopglut' ),
				'MZ' => __( 'Mozambique', 'shopglut' ),
				'MM' => __( 'Myanmar', 'shopglut' ),
				'NA' => __( 'Namibia', 'shopglut' ),
				'NR' => __( 'Nauru', 'shopglut' ),
				'NP' => __( 'Nepal', 'shopglut' ),
				'NL' => __( 'Netherlands', 'shopglut' ),
				'NZ' => __( 'New Zealand', 'shopglut' ),
				'NI' => __( 'Nicaragua', 'shopglut' ),
				'NE' => __( 'Niger', 'shopglut' ),
				'NG' => __( 'Nigeria', 'shopglut' ),
				'KP' => __( 'North Korea', 'shopglut' ),
				'MK' => __( 'North Macedonia', 'shopglut' ),
				'NO' => __( 'Norway', 'shopglut' ),
				'OM' => __( 'Oman', 'shopglut' ),
				'PK' => __( 'Pakistan', 'shopglut' ),
				'PS' => __( 'Palestinian Territory', 'shopglut' ),
				'PA' => __( 'Panama', 'shopglut' ),
				'PG' => __( 'Papua New Guinea', 'shopglut' ),
				'PY' => __( 'Paraguay', 'shopglut' ),
				'PE' => __( 'Peru', 'shopglut' ),
				'PH' => __( 'Philippines', 'shopglut' ),
				'PL' => __( 'Poland', 'shopglut' ),
				'PT' => __( 'Portugal', 'shopglut' ),
				'QA' => __( 'Qatar', 'shopglut' ),
				'RO' => __( 'Romania', 'shopglut' ),
				'RU' => __( 'Russia', 'shopglut' ),
				'RW' => __( 'Rwanda', 'shopglut' ),
				'KN' => __( 'Saint Kitts and Nevis', 'shopglut' ),
				'LC' => __( 'Saint Lucia', 'shopglut' ),
				'VC' => __( 'Saint Vincent and the Grenadines', 'shopglut' ),
				'WS' => __( 'Samoa', 'shopglut' ),
				'SM' => __( 'San Marino', 'shopglut' ),
				'ST' => __( 'São Tomé and Príncipe', 'shopglut' ),
				'SA' => __( 'Saudi Arabia', 'shopglut' ),
				'SN' => __( 'Senegal', 'shopglut' ),
				'RS' => __( 'Serbia', 'shopglut' ),
				'SC' => __( 'Seychelles', 'shopglut' ),
				'SL' => __( 'Sierra Leone', 'shopglut' ),
				'SG' => __( 'Singapore', 'shopglut' ),
				'SK' => __( 'Slovakia', 'shopglut' ),
				'SI' => __( 'Slovenia', 'shopglut' ),
				'SB' => __( 'Solomon Islands', 'shopglut' ),
				'SO' => __( 'Somalia', 'shopglut' ),
				'ZA' => __( 'South Africa', 'shopglut' ),
				'KR' => __( 'South Korea', 'shopglut' ),
				'SS' => __( 'South Sudan', 'shopglut' ),
				'ES' => __( 'Spain', 'shopglut' ),
				'LK' => __( 'Sri Lanka', 'shopglut' ),
				'SD' => __( 'Sudan', 'shopglut' ),
				'SR' => __( 'Suriname', 'shopglut' ),
				'SE' => __( 'Sweden', 'shopglut' ),
				'CH' => __( 'Switzerland', 'shopglut' ),
				'SY' => __( 'Syria', 'shopglut' ),
				'TW' => __( 'Taiwan', 'shopglut' ),
				'TJ' => __( 'Tajikistan', 'shopglut' ),
				'TZ' => __( 'Tanzania', 'shopglut' ),
				'TH' => __( 'Thailand', 'shopglut' ),
				'TL' => __( 'Timor-Leste', 'shopglut' ),
				'TG' => __( 'Togo', 'shopglut' ),
				'TO' => __( 'Tonga', 'shopglut' ),
				'TT' => __( 'Trinidad and Tobago', 'shopglut' ),
				'TN' => __( 'Tunisia', 'shopglut' ),
				'TR' => __( 'Türkiye', 'shopglut' ),
				'TM' => __( 'Turkmenistan', 'shopglut' ),
				'TV' => __( 'Tuvalu', 'shopglut' ),
				'UG' => __( 'Uganda', 'shopglut' ),
				'UA' => __( 'Ukraine', 'shopglut' ),
				'AE' => __( 'United Arab Emirates', 'shopglut' ),
				'GB' => __( 'United Kingdom (UK)', 'shopglut' ),
				'US' => __( 'United States (US)', 'shopglut' ),
				'UY' => __( 'Uruguay', 'shopglut' ),
				'UZ' => __( 'Uzbekistan', 'shopglut' ),
				'VU' => __( 'Vanuatu', 'shopglut' ),
				'VA' => __( 'Vatican', 'shopglut' ),
				'VE' => __( 'Venezuela', 'shopglut' ),
				'VN' => __( 'Vietnam', 'shopglut' ),
				'YE' => __( 'Yemen', 'shopglut' ),
				'ZM' => __( 'Zambia', 'shopglut' ),
				'ZW' => __( 'Zimbabwe', 'shopglut' ),
			),
			'default' => '',
		),

		array(
			'id' => 'company_state',
			'type' => 'text',
			'title' => __( 'Company State/Province', 'shopglut' ),
			'subtitle' => __( 'The state or province in which your business is located', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'company_city',
			'type' => 'text',
			'title' => __( 'Company City', 'shopglut' ),
			'subtitle' => __( 'The city in which your business is located', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'company_postcode',
			'type' => 'text',
			'title' => __( 'Company Postcode/ZIP', 'shopglut' ),
			'subtitle' => __( 'The postal code or ZIP code for your business location', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'company_phone',
			'type' => 'text',
			'title' => __( 'Phone Number', 'shopglut' ),
			'subtitle' => __( 'Company phone number', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'company_email',
			'type' => 'text',
			'title' => __( 'Email Address', 'shopglut' ),
			'subtitle' => __( 'Company email address', 'shopglut' ),
			'default' => get_option( 'admin_email' ),
		),

		array(
			'id' => 'company_website',
			'type' => 'text',
			'title' => __( 'Website URL', 'shopglut' ),
			'subtitle' => __( 'Company website URL', 'shopglut' ),
			'default' => home_url(),
		),

		array(
			'id' => 'tax_number',
			'type' => 'text',
			'title' => __( 'Tax/VAT Number', 'shopglut' ),
			'subtitle' => __( 'Your business tax or VAT registration number', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'coc_number',
			'type' => 'text',
			'title' => __( 'Chamber of Commerce Number', 'shopglut' ),
			'subtitle' => __( 'Your business Chamber of Commerce registration number', 'shopglut' ),
			'default' => '',
		),

	)
) );

// Create a sub-tab for Display Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'template_tab',
	'title' => __( 'Display Settings', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'download_display',
			'type' => 'select',
			'title' => __( 'How do you want to view the PDF?', 'shopglut' ),
			'subtitle' => __( 'Choose how PDFs should be displayed when accessed', 'shopglut' ),
			'options' => array(
				'download' => __( 'Download the PDF', 'shopglut' ),
				'display' => __( 'Open the PDF in a new browser tab/window', 'shopglut' ),
			),
			'default' => 'display',
		),

		array(
			'id' => 'paper_size',
			'type' => 'select',
			'title' => __( 'Paper Size', 'shopglut' ),
			'subtitle' => __( 'Select the paper size for PDF documents', 'shopglut' ),
			'options' => array(
				'A4' => __( 'A4 (210 x 297 mm)', 'shopglut' ),
				'Letter' => __( 'Letter (8.5 x 11 in)', 'shopglut' ),
				'Legal' => __( 'Legal (8.5 x 14 in)', 'shopglut' ),
			),
			'default' => 'A4',
		),

		array(
			'id' => 'test_mode',
			'type' => 'switcher',
			'title' => __( 'Test Mode', 'shopglut' ),
			'subtitle' => __( 'With test mode enabled, any document generated will always use the latest settings, rather than using the settings as configured at the time the document was first created.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );

// Create a sub-tab for Template Design
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'template_tab',
	'title' => __( 'Template Design', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'invoice_template',
			'type' => 'image_select',
			'title' => __( 'Invoice Template', 'shopglut' ),
			'subtitle' => __( 'Choose a template design for your invoices', 'shopglut' ),
			'options' => array(
				'default' => SHOPGLUT_URL . 'global-assets/images/invoice-template-default.png',
				'classic' => SHOPGLUT_URL . 'global-assets/images/invoice-template-classic.png',
				'modern' => SHOPGLUT_URL . 'global-assets/images/invoice-template-modern.png',
				'minimal' => SHOPGLUT_URL . 'global-assets/images/invoice-template-minimal.png',
			),
			'default' => 'default',
		),

		array(
			'id' => 'packaging_template',
			'type' => 'image_select',
			'title' => __( 'Packaging Slip Template', 'shopglut' ),
			'subtitle' => __( 'Choose a template design for your packaging slips', 'shopglut' ),
			'options' => array(
				'default' => SHOPGLUT_URL . 'global-assets/images/packaging-template-default.png',
				'classic' => SHOPGLUT_URL . 'global-assets/images/packaging-template-classic.png',
				'modern' => SHOPGLUT_URL . 'global-assets/images/packaging-template-modern.png',
				'minimal' => SHOPGLUT_URL . 'global-assets/images/packaging-template-minimal.png',
			),
			'default' => 'default',
		),

		array(
			'id' => 'primary_color',
			'type' => 'color',
			'title' => __( 'Primary Color', 'shopglut' ),
			'subtitle' => __( 'Main color used in the template design', 'shopglut' ),
			'default' => '#2271b1',
		),

		array(
			'id' => 'secondary_color',
			'type' => 'color',
			'title' => __( 'Secondary Color', 'shopglut' ),
			'subtitle' => __( 'Secondary color for accents and highlights', 'shopglut' ),
			'default' => '#72aee6',
		),

		array(
			'id' => 'header_text_color',
			'type' => 'color',
			'title' => __( 'Header Text Color', 'shopglut' ),
			'subtitle' => __( 'Color for header text and titles', 'shopglut' ),
			'default' => '#ffffff',
		),

		array(
			'id' => 'body_text_color',
			'type' => 'color',
			'title' => __( 'Body Text Color', 'shopglut' ),
			'subtitle' => __( 'Color for main body text', 'shopglut' ),
			'default' => '#333333',
		),

		array(
			'id' => 'show_prices',
			'type' => 'switcher',
			'title' => __( 'Show Prices on Packing Slips', 'shopglut' ),
			'subtitle' => __( 'Display product prices on packing slips', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'footer_text',
			'type' => 'textarea',
			'title' => __( 'Footer Text', 'shopglut' ),
			'subtitle' => __( 'Additional text to display in the document footer', 'shopglut' ),
			'default' => __( 'Thank you for your business!', 'shopglut' ),
		),

		array(
			'id' => 'extra_field_1',
			'type' => 'textarea',
			'title' => __( 'Extra Field 1', 'shopglut' ),
			'subtitle' => __( 'This is footer column 1 in the Modern template', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'extra_field_2',
			'type' => 'textarea',
			'title' => __( 'Extra Field 2', 'shopglut' ),
			'subtitle' => __( 'This is footer column 2 in the Modern template', 'shopglut' ),
			'default' => '',
		),

		array(
			'id' => 'extra_field_3',
			'type' => 'textarea',
			'title' => __( 'Extra Field 3', 'shopglut' ),
			'subtitle' => __( 'This is footer column 3 in the Modern template', 'shopglut' ),
			'default' => '',
		),

	)
) );

//
// Create a top-tab for UBL Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'id' => 'ubl_tab',
	'title' => __( 'UBL Invoices', 'shopglut' ),
	'icon' => 'fa-solid fa-file-code',
) );

// Create a sub-tab for UBL Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'ubl_tab',
	'title' => __( 'UBL Configuration', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'enable_ubl_invoices',
			'type' => 'switcher',
			'title' => __( 'Enable UBL Invoices', 'shopglut' ),
			'subtitle' => __( 'Generate UBL (Universal Business Language) format invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'ubl_invoice_format',
			'type' => 'select',
			'title' => __( 'UBL Invoice Format', 'shopglut' ),
			'subtitle' => __( 'Select the UBL invoice format to generate', 'shopglut' ),
			'options' => array(
				'ubl_2_1' => __( 'UBL 2.1', 'shopglut' ),
				'ubl_2_0' => __( 'UBL 2.0', 'shopglut' ),
				'xml_simple' => __( 'Simple XML', 'shopglut' ),
				'peppol_bis' => __( 'PEPPOL BIS', 'shopglut' ),
				'factur_x' => __( 'Factur-X', 'shopglut' ),
				'zugferd' => __( 'ZUGFeRD', 'shopglut' ),
			),
			'default' => 'ubl_2_1',
			'dependency' => array( 'enable_ubl_invoices', '==', '1' ),
		),

		array(
			'id' => 'auto_attach_ubl',
			'type' => 'switcher',
			'title' => __( 'Auto Attach UBL to Emails', 'shopglut' ),
			'subtitle' => __( 'Automatically attach UBL invoices to order confirmation emails', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
			'dependency' => array( 'enable_ubl_invoices', '==', '1' ),
		),

		array(
			'id' => 'ubl_tax_mapping',
			'type' => 'group',
			'title' => __( 'Tax Class Mapping', 'shopglut' ),
			'subtitle' => __( 'Map WooCommerce tax classes to UBL tax categories', 'shopglut' ),
			'fields' => array(
				array(
					'id' => 'wc_tax_class',
					'type' => 'select',
					'title' => __( 'WooCommerce Tax Class', 'shopglut' ),
					'options' => 'tax_classes',
				),
				array(
					'id' => 'ubl_tax_category',
					'type' => 'select',
					'title' => __( 'UBL Tax Category', 'shopglut' ),
					'options' => array(
						'S' => __( 'Standard rate (S)', 'shopglut' ),
						'Z' => __( 'Zero rated (Z)', 'shopglut' ),
						'E' => __( 'Exempt (E)', 'shopglut' ),
						'AE' => __( 'Reverse charge (AE)', 'shopglut' ),
						'K' => __( 'Intra-community supply (K)', 'shopglut' ),
					),
				),
			),
			'dependency' => array( 'enable_ubl_invoices', '==', '1' ),
		),

	)
) );

//
// Create a top-tab for Advanced Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'id' => 'advanced_tab',
	'title' => __( 'Advanced', 'shopglut' ),
	'icon' => 'fa fa-cogs',
) );

// Create a sub-tab for Advanced Options
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'advanced_tab',
	'title' => __( 'Advanced Options', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'invoice_number_column',
			'type' => 'switcher',
			'title' => __( 'Enable Invoice Number Column in Orders List', 'shopglut' ),
			'subtitle' => __( 'Add invoice number column to the WooCommerce orders list', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'invoice_date_column',
			'type' => 'switcher',
			'title' => __( 'Enable Invoice Date Column in Orders List', 'shopglut' ),
			'subtitle' => __( 'Add invoice date column to the WooCommerce orders list', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'invoice_number_search',
			'type' => 'switcher',
			'title' => __( 'Enable Invoice Number Search in Orders List', 'shopglut' ),
			'subtitle' => __( 'Allow searching orders by invoice number in the admin orders list', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'reset_number_yearly',
			'type' => 'switcher',
			'title' => __( 'Reset Invoice Number Yearly', 'shopglut' ),
			'subtitle' => __( 'Reset invoice numbering to 1 at the beginning of each year', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'mark_printed',
			'type' => 'checkbox',
			'title' => __( 'Mark as Printed', 'shopglut' ),
			'subtitle' => __( 'Automatically mark invoices as printed when accessed', 'shopglut' ),
			'options' => array(
				'manually' => __( 'Manually', 'shopglut' ),
				'single' => __( 'On single order action', 'shopglut' ),
				'bulk' => __( 'On bulk order action', 'shopglut' ),
				'my_account' => __( 'On my account', 'shopglut' ),
				'email_attachment' => __( 'On email attachment', 'shopglut' ),
				'document_data' => __( 'On order document data (number and/or date set manually)', 'shopglut' ),
			),
			'default' => array(),
		),

		array(
			'id' => 'unmark_printed',
			'type' => 'switcher',
			'title' => __( 'Unmark as Printed', 'shopglut' ),
			'subtitle' => __( 'Add a link in the order page to allow removing the printed mark', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'extended_currency_support',
			'type' => 'switcher',
			'title' => __( 'Extended Currency Symbol Support', 'shopglut' ),
			'subtitle' => __( 'Enable this if your currency symbol is not displaying properly', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'disable_for_free_orders',
			'type' => 'switcher',
			'title' => __( 'Disable for Free Orders', 'shopglut' ),
			'subtitle' => __( 'Do not generate invoices for orders with zero total', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'show_free_line_items',
			'type' => 'switcher',
			'title' => __( 'Show Free Line Items', 'shopglut' ),
			'subtitle' => __( 'Display line items with zero cost on invoices', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'my_account_downloads',
			'type' => 'switcher',
			'title' => __( 'My Account Downloads', 'shopglut' ),
			'subtitle' => __( 'Allow customers to download invoices from their account page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'bulk_download',
			'type' => 'switcher',
			'title' => __( 'Bulk Download/Print', 'shopglut' ),
			'subtitle' => __( 'Enable bulk download and print from the orders admin page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'custom_pdf_filename',
			'type' => 'text',
			'title' => __( 'Custom PDF Filename', 'shopglut' ),
			'subtitle' => __( 'Custom filename pattern. Use {order_number}, {invoice_number}, {date}', 'shopglut' ),
			'default' => 'invoice-{invoice_number}',
		),

		array(
			'id' => 'use_latest_settings',
			'type' => 'switcher',
			'title' => __( 'Always Use Most Current Settings', 'shopglut' ),
			'subtitle' => __( 'When enabled, the document will always reflect the most current settings rather than using historical settings. Caution: This means previously generated documents will also be affected if you change company info.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );

//
// Create a top-tab for Debug/System Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'id' => 'debug_tab',
	'title' => __( 'Debug & System', 'shopglut' ),
	'icon' => 'fa fa-bug',
) );

// Create a sub-tab for System Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'debug_tab',
	'title' => __( 'System Settings', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'file_system_method',
			'type' => 'select',
			'title' => __( 'File System Method', 'shopglut' ),
			'subtitle' => __( 'Choose the filesystem method for file operations. By default, our plugin uses PHP Filesystem Functions. If you prefer to use the WP Filesystem API, please note that only the direct method is supported.', 'shopglut' ),
			'options' => array(
				'php' => __( 'PHP Filesystem Functions (recommended)', 'shopglut' ),
				'wp' => __( 'WP Filesystem API', 'shopglut' ),
			),
			'default' => 'php',
		),

		array(
			'id' => 'document_link_access_type',
			'type' => 'select',
			'title' => __( 'Document Link Access Type', 'shopglut' ),
			'subtitle' => __( 'Control who can access document links', 'shopglut' ),
			'options' => array(
				'logged_in' => __( 'Logged in (recommended)', 'shopglut' ),
				'full' => __( 'Full', 'shopglut' ),
			),
			'default' => 'logged_in',
		),

		array(
			'id' => 'document_access_denied_redirect_page',
			'type' => 'select',
			'title' => __( 'Document Access Denied Redirect Page', 'shopglut' ),
			'subtitle' => __( 'Select a frontend page to be used to redirect users when the document access is denied', 'shopglut' ),
			'options' => array(
				'blank_page' => __( 'Blank page with message (default)', 'shopglut' ),
				'login_page' => __( 'Login page', 'shopglut' ),
				'myaccount_page' => __( 'My Account page', 'shopglut' ),
				'custom_page' => __( 'Custom page (enter below)', 'shopglut' ),
			),
			'default' => 'blank_page',
		),

		array(
			'id' => 'document_custom_redirect_page',
			'type' => 'text',
			'title' => __( 'Custom Redirect Page URL', 'shopglut' ),
			'subtitle' => __( 'Enter the URL for custom redirect page (external URLs not allowed)', 'shopglut' ),
			'default' => '',
			'dependency' => array( 'document_access_denied_redirect_page', '==', 'custom_page' ),
		),

		array(
			'id' => 'pretty_document_links',
			'type' => 'switcher',
			'title' => __( 'Pretty Document Links', 'shopglut' ),
			'subtitle' => __( 'Changes the document links to a prettier URL scheme', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'calculate_document_numbers',
			'type' => 'switcher',
			'title' => __( 'Calculate Document Numbers (slow)', 'shopglut' ),
			'subtitle' => __( 'Document numbers (such as invoice numbers) are generated using AUTO_INCREMENT by default. Use this setting if your database auto increments with more than 1.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'enable_document_data_editing',
			'type' => 'switcher',
			'title' => __( 'Enable Document Data Editing', 'shopglut' ),
			'subtitle' => __( 'Allow editing of document number and date on the order page. Note: Changing document data is prohibited in some countries. This setting is disabled by default to comply with legal requirements.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );

// Create a sub-tab for Cleanup Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'debug_tab',
	'title' => __( 'Cleanup & Maintenance', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'enable_cleanup',
			'type' => 'switcher',
			'title' => __( 'Enable Automatic Cleanup', 'shopglut' ),
			'subtitle' => __( 'Automatically clean up PDF files stored in the temporary folder (used for email attachments)', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'cleanup_days',
			'type' => 'number',
			'title' => __( 'Cleanup Interval (Days)', 'shopglut' ),
			'subtitle' => __( 'Number of days after which to clean up temporary PDF files', 'shopglut' ),
			'default' => 7,
			'min' => 1,
			'max' => 365,
			'dependency' => array( 'enable_cleanup', '==', '1' ),
		),

	)
) );

// Create a sub-tab for Development Settings
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'debug_tab',
	'title' => __( 'Development & Debug', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'html_output',
			'type' => 'switcher',
			'title' => __( 'Output to HTML', 'shopglut' ),
			'subtitle' => __( 'Send the template output as HTML to the browser instead of creating a PDF. You can also add &output=html to the URL to apply this on a per-order basis.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'enable_debug',
			'type' => 'switcher',
			'title' => __( 'Enable Debug Output', 'shopglut' ),
			'subtitle' => __( 'Enable this option to output plugin errors if you\'re getting a blank page or other PDF generation issues. Caution! This setting may reveal errors in other places on your site too. You can also add &debug=true to the URL to apply this on a per-order basis.', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );

// Create a sub-tab for Logging Settings  
AGSHOPGLUT::createSection( $AGSHOPGLUT_PDF_INVOICES_OPTIONS, array(
	'parent' => 'debug_tab',
	'title' => __( 'Logging & Monitoring', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'log_to_order_notes',
			'type' => 'switcher',
			'title' => __( 'Log to Order Notes', 'shopglut' ),
			'subtitle' => __( 'Log PDF document creation, deletion, and mark/unmark as printed to order notes', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

	)
) );
