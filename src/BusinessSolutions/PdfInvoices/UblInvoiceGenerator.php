<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutUblInvoiceGenerator {
	
	private $settings;
	private $system_manager;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->system_manager = new ShopGlutPdfInvoicesSystemManager();
		$this->init_hooks();
	}
	
	private function init_hooks() {
		if ( $this->is_enabled() ) {
			add_action( 'woocommerce_order_status_changed', array( $this, 'handle_order_status_change' ), 10, 3 );
			add_action( 'woocommerce_email_before_order_table', array( $this, 'attach_ubl_to_email' ), 10, 4 );
			add_action( 'woocommerce_account_orders_columns', array( $this, 'add_my_account_ubl_column' ) );
			add_action( 'woocommerce_my_account_my_orders_column_ubl-invoice', array( $this, 'show_my_account_ubl_download' ) );
		}
		
		add_action( 'wp_ajax_generate_ubl_invoice', array( $this, 'ajax_generate_ubl_invoice' ) );
		add_action( 'wp_ajax_nopriv_generate_ubl_invoice', array( $this, 'ajax_generate_ubl_invoice' ) );
	}
	
	public function is_enabled() {
		return isset( $this->settings['enable_ubl_invoices'] ) && $this->settings['enable_ubl_invoices'] == 1;
	}
	
	public function handle_order_status_change( $order_id, $old_status, $new_status ) {
		if ( $this->should_generate_ubl_for_status( $new_status ) ) {
			$this->generate_ubl_invoice( $order_id );
		}
	}
	
	private function should_generate_ubl_for_status( $status ) {
		$generation_statuses = array( 'completed', 'processing' );
		return in_array( $status, $generation_statuses );
	}
	
	public function generate_ubl_invoice( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}
		
		$ubl_data = $this->prepare_ubl_data( $order );
		$ubl_content = $this->generate_ubl_content( $ubl_data );
		
		$filename = $this->get_ubl_filename( $order );
		$file_path = $this->save_ubl_file( $ubl_content, $filename );
		
		if ( $file_path ) {
			$this->save_ubl_metadata( $order_id, $filename, $file_path );
			
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				$order->add_order_note( 
					// translators: %s is the filename of the generated UBL invoice
					sprintf( __( 'UBL Invoice generated: %s', 'shopglut' ), $filename )
				);
			}
			
			return $file_path;
		}
		
		return false;
	}
	
	private function prepare_ubl_data( $order ) {
		$company_info = $this->get_company_information();
		$invoice_number = $this->get_invoice_number( $order );
		$invoice_date = $this->get_invoice_date( $order );
		
		return array(
			'order' => $order,
			'company_info' => $company_info,
			'invoice_number' => $invoice_number,
			'invoice_date' => $invoice_date,
			'due_date' => $this->calculate_due_date( $invoice_date ),
			'ubl_format' => $this->get_ubl_format(),
			'tax_mappings' => $this->get_tax_mappings(),
			'currency_code' => $order->get_currency(),
		);
	}
	
	private function get_company_information() {
		return array(
			'name' => isset( $this->settings['company_name'] ) ? $this->settings['company_name'] : get_bloginfo( 'name' ),
			'logo' => isset( $this->settings['company_logo'] ) ? $this->settings['company_logo'] : '',
			'logo_height' => isset( $this->settings['logo_height'] ) ? $this->settings['logo_height'] : '40mm',
			'address' => isset( $this->settings['company_address'] ) ? $this->settings['company_address'] : '',
			'country' => isset( $this->settings['company_country'] ) ? $this->settings['company_country'] : '',
			'state' => isset( $this->settings['company_state'] ) ? $this->settings['company_state'] : '',
			'city' => isset( $this->settings['company_city'] ) ? $this->settings['company_city'] : '',
			'postcode' => isset( $this->settings['company_postcode'] ) ? $this->settings['company_postcode'] : '',
			'phone' => isset( $this->settings['company_phone'] ) ? $this->settings['company_phone'] : '',
			'email' => isset( $this->settings['company_email'] ) ? $this->settings['company_email'] : get_option( 'admin_email' ),
			'website' => isset( $this->settings['company_website'] ) ? $this->settings['company_website'] : home_url(),
			'tax_number' => isset( $this->settings['tax_number'] ) ? $this->settings['tax_number'] : '',
			'coc_number' => isset( $this->settings['coc_number'] ) ? $this->settings['coc_number'] : '',
		);
	}
	
	private function get_invoice_number( $order ) {
		$existing_number = get_post_meta( $order->get_id(), '_invoice_number', true );
		if ( $existing_number ) {
			return $existing_number;
		}
		
		return 'UBL-' . $order->get_order_number();
	}
	
	private function get_invoice_date( $order ) {
		return current_time( 'Y-m-d\TH:i:s\Z' );
	}
	
	private function calculate_due_date( $invoice_date ) {
		$due_days = isset( $this->settings['due_date_days'] ) ? intval( $this->settings['due_date_days'] ) : 30;
		return wp_date( 'Y-m-d\TH:i:s\Z', strtotime( $invoice_date . ' + ' . $due_days . ' days' ) );
	}
	
	private function get_ubl_format() {
		return isset( $this->settings['ubl_invoice_format'] ) ? $this->settings['ubl_invoice_format'] : 'ubl_2_1';
	}
	
	private function get_tax_mappings() {
		$mappings = isset( $this->settings['ubl_tax_mapping'] ) ? $this->settings['ubl_tax_mapping'] : array();
		
		$default_mappings = array(
			'standard' => 'S',
			'reduced' => 'S',
			'zero' => 'Z',
			'exempt' => 'E',
		);
		
		return array_merge( $default_mappings, $mappings );
	}
	
	private function generate_ubl_content( $ubl_data ) {
		$format = $ubl_data['ubl_format'];
		
		// Check if pro formats are available
		$pro_formats = array( 'ubl_2_0', 'peppol_bis', 'factur_x', 'zugferd' );
		if ( in_array( $format, $pro_formats ) && ! $this->pro_manager->is_pro_feature_available( 'ubl_advanced_formats' ) ) {
			// Fallback to free format with upsell message in logs
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				// Log format fallback through WordPress action hook
				do_action( 'shopglut_pdf_ubl_format_fallback', $format, 'ubl_2_1' );
			}
			$format = 'ubl_2_1';
		}
		
		switch ( $format ) {
			case 'ubl_2_1':
				$content = $this->generate_ubl_2_1( $ubl_data );
				break;
			case 'xml_simple':
				$content = $this->generate_simple_xml( $ubl_data );
				break;
			// Pro formats - only if pro is active
			case 'ubl_2_0':
				$content = $this->pro_manager->execute_pro_feature( 'ubl_2_0', array( $this, 'generate_ubl_2_0' ), array( $ubl_data ) );
				if ( $content === false ) $content = $this->generate_ubl_2_1( $ubl_data );
				break;
			case 'peppol_bis':
				$content = $this->pro_manager->execute_pro_feature( 'peppol_bis', array( $this, 'generate_peppol_bis' ), array( $ubl_data ) );
				if ( $content === false ) $content = $this->generate_ubl_2_1( $ubl_data );
				break;
			case 'factur_x':
				$content = $this->pro_manager->execute_pro_feature( 'factur_x', array( $this, 'generate_factur_x' ), array( $ubl_data ) );
				if ( $content === false ) $content = $this->generate_ubl_2_1( $ubl_data );
				break;
			case 'zugferd':
				$content = $this->pro_manager->execute_pro_feature( 'zugferd', array( $this, 'generate_zugferd' ), array( $ubl_data ) );
				if ( $content === false ) $content = $this->generate_ubl_2_1( $ubl_data );
				break;
			default:
				$content = $this->generate_ubl_2_1( $ubl_data );
				break;
		}
		
		// Allow pro version to modify any UBL content
		return apply_filters( 'shopglut_pdf_invoices_ubl_content', $content, $ubl_data, $format );
	}
	
	private function generate_ubl_2_1( $ubl_data ) {
		$xml = new DOMDocument( '1.0', 'UTF-8' );
		$xml->formatOutput = true;
		
		$invoice = $xml->createElement( 'Invoice' );
		$invoice->setAttribute( 'xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' );
		$invoice->setAttribute( 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' );
		$invoice->setAttribute( 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' );
		$xml->appendChild( $invoice );
		
		$customization_id = $xml->createElement( 'cbc:CustomizationID', 'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0' );
		$invoice->appendChild( $customization_id );
		
		$profile_id = $xml->createElement( 'cbc:ProfileID', 'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0' );
		$invoice->appendChild( $profile_id );
		
		$id = $xml->createElement( 'cbc:ID', $ubl_data['invoice_number'] );
		$invoice->appendChild( $id );
		
		$issue_date = $xml->createElement( 'cbc:IssueDate', wp_date( 'Y-m-d', strtotime( $ubl_data['invoice_date'] ) ) );
		$invoice->appendChild( $issue_date );
		
		if ( $ubl_data['due_date'] ) {
			$due_date = $xml->createElement( 'cbc:DueDate', wp_date( 'Y-m-d', strtotime( $ubl_data['due_date'] ) ) );
			$invoice->appendChild( $due_date );
		}
		
		$invoice_type_code = $xml->createElement( 'cbc:InvoiceTypeCode', '380' );
		$invoice->appendChild( $invoice_type_code );
		
		$document_currency_code = $xml->createElement( 'cbc:DocumentCurrencyCode', $ubl_data['currency_code'] );
		$invoice->appendChild( $document_currency_code );
		
		$this->add_supplier_party( $xml, $invoice, $ubl_data );
		$this->add_customer_party( $xml, $invoice, $ubl_data );
		$this->add_invoice_lines( $xml, $invoice, $ubl_data );
		$this->add_monetary_totals( $xml, $invoice, $ubl_data );
		
		return $xml->saveXML();
	}
	
	private function add_supplier_party( $xml, $invoice, $ubl_data ) {
		$supplier_party = $xml->createElement( 'cac:AccountingSupplierParty' );
		$party = $xml->createElement( 'cac:Party' );
		
		$party_name = $xml->createElement( 'cac:PartyName' );
		$name = $xml->createElement( 'cbc:Name', htmlspecialchars( $ubl_data['company_info']['name'] ) );
		$party_name->appendChild( $name );
		$party->appendChild( $party_name );
		
		if ( $ubl_data['company_info']['address'] ) {
			$postal_address = $xml->createElement( 'cac:PostalAddress' );
			
			if ( $ubl_data['company_info']['address'] ) {
				$street_name = $xml->createElement( 'cbc:StreetName', htmlspecialchars( $ubl_data['company_info']['address'] ) );
				$postal_address->appendChild( $street_name );
			}
			
			if ( $ubl_data['company_info']['city'] ) {
				$city_name = $xml->createElement( 'cbc:CityName', htmlspecialchars( $ubl_data['company_info']['city'] ) );
				$postal_address->appendChild( $city_name );
			}
			
			if ( $ubl_data['company_info']['postcode'] ) {
				$postal_zone = $xml->createElement( 'cbc:PostalZone', htmlspecialchars( $ubl_data['company_info']['postcode'] ) );
				$postal_address->appendChild( $postal_zone );
			}
			
			if ( $ubl_data['company_info']['country'] ) {
				$country = $xml->createElement( 'cac:Country' );
				$identification_code = $xml->createElement( 'cbc:IdentificationCode', $ubl_data['company_info']['country'] );
				$country->appendChild( $identification_code );
				$postal_address->appendChild( $country );
			}
			
			$party->appendChild( $postal_address );
		}
		
		if ( $ubl_data['company_info']['tax_number'] ) {
			$party_tax_scheme = $xml->createElement( 'cac:PartyTaxScheme' );
			$company_id = $xml->createElement( 'cbc:CompanyID', $ubl_data['company_info']['tax_number'] );
			$party_tax_scheme->appendChild( $company_id );
			
			$tax_scheme = $xml->createElement( 'cac:TaxScheme' );
			$tax_scheme_id = $xml->createElement( 'cbc:ID', 'VAT' );
			$tax_scheme->appendChild( $tax_scheme_id );
			$party_tax_scheme->appendChild( $tax_scheme );
			
			$party->appendChild( $party_tax_scheme );
		}
		
		$supplier_party->appendChild( $party );
		$invoice->appendChild( $supplier_party );
	}
	
	private function add_customer_party( $xml, $invoice, $ubl_data ) {
		$order = $ubl_data['order'];
		
		$customer_party = $xml->createElement( 'cac:AccountingCustomerParty' );
		$party = $xml->createElement( 'cac:Party' );
		
		$party_name = $xml->createElement( 'cac:PartyName' );
		$name = $xml->createElement( 'cbc:Name', htmlspecialchars( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) );
		$party_name->appendChild( $name );
		$party->appendChild( $party_name );
		
		$postal_address = $xml->createElement( 'cac:PostalAddress' );
		
		if ( $order->get_billing_address_1() ) {
			$street_name = $xml->createElement( 'cbc:StreetName', htmlspecialchars( $order->get_billing_address_1() ) );
			$postal_address->appendChild( $street_name );
		}
		
		if ( $order->get_billing_city() ) {
			$city_name = $xml->createElement( 'cbc:CityName', htmlspecialchars( $order->get_billing_city() ) );
			$postal_address->appendChild( $city_name );
		}
		
		if ( $order->get_billing_postcode() ) {
			$postal_zone = $xml->createElement( 'cbc:PostalZone', htmlspecialchars( $order->get_billing_postcode() ) );
			$postal_address->appendChild( $postal_zone );
		}
		
		if ( $order->get_billing_country() ) {
			$country = $xml->createElement( 'cac:Country' );
			$identification_code = $xml->createElement( 'cbc:IdentificationCode', $order->get_billing_country() );
			$country->appendChild( $identification_code );
			$postal_address->appendChild( $country );
		}
		
		$party->appendChild( $postal_address );
		$customer_party->appendChild( $party );
		$invoice->appendChild( $customer_party );
	}
	
	private function add_invoice_lines( $xml, $invoice, $ubl_data ) {
		$order = $ubl_data['order'];
		$line_id = 1;
		
		foreach ( $order->get_items() as $item_id => $item ) {
			$invoice_line = $xml->createElement( 'cac:InvoiceLine' );
			
			$id = $xml->createElement( 'cbc:ID', $line_id );
			$invoice_line->appendChild( $id );
			
			$quantity = $xml->createElement( 'cbc:InvoicedQuantity', $item->get_quantity() );
			$quantity->setAttribute( 'unitCode', 'C62' );
			$invoice_line->appendChild( $quantity );
			
			$line_amount = $xml->createElement( 'cbc:LineExtensionAmount', number_format( $item->get_total(), 2, '.', '' ) );
			$line_amount->setAttribute( 'currencyID', $order->get_currency() );
			$invoice_line->appendChild( $line_amount );
			
			$this->add_item_info( $xml, $invoice_line, $item, $ubl_data );
			$this->add_price_info( $xml, $invoice_line, $item, $order );
			
			$invoice->appendChild( $invoice_line );
			$line_id++;
		}
	}
	
	private function add_item_info( $xml, $invoice_line, $item, $ubl_data ) {
		$item_element = $xml->createElement( 'cac:Item' );
		
		$description = $xml->createElement( 'cbc:Description', htmlspecialchars( $item->get_name() ) );
		$item_element->appendChild( $description );
		
		$name = $xml->createElement( 'cbc:Name', htmlspecialchars( $item->get_name() ) );
		$item_element->appendChild( $name );
		
		$product = $item->get_product();
		if ( $product && $product->get_sku() ) {
			$sellers_item_identification = $xml->createElement( 'cac:SellersItemIdentification' );
			$id = $xml->createElement( 'cbc:ID', $product->get_sku() );
			$sellers_item_identification->appendChild( $id );
			$item_element->appendChild( $sellers_item_identification );
		}
		
		$this->add_item_tax_info( $xml, $item_element, $item, $ubl_data );
		
		$invoice_line->appendChild( $item_element );
	}
	
	private function add_item_tax_info( $xml, $item_element, $item, $ubl_data ) {
		$tax_category = $xml->createElement( 'cac:ClassifiedTaxCategory' );
		
		$tax_class = $item->get_tax_class();
		$ubl_tax_category = isset( $ubl_data['tax_mappings'][ $tax_class ] ) ? $ubl_data['tax_mappings'][ $tax_class ] : 'S';
		
		$id = $xml->createElement( 'cbc:ID', $ubl_tax_category );
		$tax_category->appendChild( $id );
		
		$tax_rates = WC_Tax::get_rates_for_tax_class( $tax_class );
		$tax_rate = ! empty( $tax_rates ) ? reset( $tax_rates )->tax_rate : 0;
		
		$percent = $xml->createElement( 'cbc:Percent', number_format( $tax_rate, 2, '.', '' ) );
		$tax_category->appendChild( $percent );
		
		$tax_scheme = $xml->createElement( 'cac:TaxScheme' );
		$scheme_id = $xml->createElement( 'cbc:ID', 'VAT' );
		$tax_scheme->appendChild( $scheme_id );
		$tax_category->appendChild( $tax_scheme );
		
		$item_element->appendChild( $tax_category );
	}
	
	private function add_price_info( $xml, $invoice_line, $item, $order ) {
		$price = $xml->createElement( 'cac:Price' );
		
		$unit_price = $item->get_total() / $item->get_quantity();
		$price_amount = $xml->createElement( 'cbc:PriceAmount', number_format( $unit_price, 2, '.', '' ) );
		$price_amount->setAttribute( 'currencyID', $order->get_currency() );
		$price->appendChild( $price_amount );
		
		$invoice_line->appendChild( $price );
	}
	
	private function add_monetary_totals( $xml, $invoice, $ubl_data ) {
		$order = $ubl_data['order'];
		
		$legal_monetary_total = $xml->createElement( 'cac:LegalMonetaryTotal' );
		
		$line_extension_amount = $xml->createElement( 'cbc:LineExtensionAmount', number_format( $order->get_subtotal(), 2, '.', '' ) );
		$line_extension_amount->setAttribute( 'currencyID', $order->get_currency() );
		$legal_monetary_total->appendChild( $line_extension_amount );
		
		$tax_exclusive_amount = $xml->createElement( 'cbc:TaxExclusiveAmount', number_format( $order->get_total() - $order->get_total_tax(), 2, '.', '' ) );
		$tax_exclusive_amount->setAttribute( 'currencyID', $order->get_currency() );
		$legal_monetary_total->appendChild( $tax_exclusive_amount );
		
		$tax_inclusive_amount = $xml->createElement( 'cbc:TaxInclusiveAmount', number_format( $order->get_total(), 2, '.', '' ) );
		$tax_inclusive_amount->setAttribute( 'currencyID', $order->get_currency() );
		$legal_monetary_total->appendChild( $tax_inclusive_amount );
		
		$payable_amount = $xml->createElement( 'cbc:PayableAmount', number_format( $order->get_total(), 2, '.', '' ) );
		$payable_amount->setAttribute( 'currencyID', $order->get_currency() );
		$legal_monetary_total->appendChild( $payable_amount );
		
		$invoice->appendChild( $legal_monetary_total );
	}
	
	private function generate_ubl_2_0( $ubl_data ) {
		return $this->generate_ubl_2_1( $ubl_data );
	}
	
	private function generate_simple_xml( $ubl_data ) {
		$xml = new DOMDocument( '1.0', 'UTF-8' );
		$xml->formatOutput = true;
		
		$root = $xml->createElement( 'Invoice' );
		$xml->appendChild( $root );
		
		$header = $xml->createElement( 'Header' );
		$root->appendChild( $header );
		
		$invoice_number = $xml->createElement( 'InvoiceNumber', $ubl_data['invoice_number'] );
		$header->appendChild( $invoice_number );
		
		$invoice_date = $xml->createElement( 'InvoiceDate', wp_date( 'Y-m-d', strtotime( $ubl_data['invoice_date'] ) ) );
		$header->appendChild( $invoice_date );
		
		return $xml->saveXML();
	}
	
	private function generate_peppol_bis( $ubl_data ) {
		return $this->generate_ubl_2_1( $ubl_data );
	}
	
	private function generate_factur_x( $ubl_data ) {
		return $this->generate_ubl_2_1( $ubl_data );
	}
	
	private function generate_zugferd( $ubl_data ) {
		return $this->generate_ubl_2_1( $ubl_data );
	}
	
	private function get_ubl_filename( $order ) {
		$format = $this->get_ubl_format();
		$extension = in_array( $format, array( 'factur_x', 'zugferd' ) ) ? '.pdf' : '.xml';
		
		$filename = 'ubl-invoice-' . $order->get_order_number() . '-' . wp_date( 'Y-m-d' );
		return sanitize_file_name( $filename ) . $extension;
	}
	
	private function save_ubl_file( $content, $filename ) {
		$upload_dir = wp_upload_dir();
		$ubl_dir = $upload_dir['basedir'] . '/shopglut-ubl-invoices/';
		
		if ( ! $this->system_manager->file_exists( $ubl_dir ) ) {
			$this->system_manager->create_directory( $ubl_dir );
		}
		
		$file_path = $ubl_dir . $filename;
		$this->system_manager->write_file( $file_path, $content );
		
		return $file_path;
	}
	
	private function save_ubl_metadata( $order_id, $filename, $file_path ) {
		update_post_meta( $order_id, '_ubl_invoice_filename', $filename );
		update_post_meta( $order_id, '_ubl_invoice_file_path', $file_path );
		update_post_meta( $order_id, '_ubl_invoice_generated', time() );
	}
	
	public function attach_ubl_to_email( $order, $sent_to_admin, $plain_text, $email ) {
		if ( ! isset( $this->settings['auto_attach_ubl'] ) || ! $this->settings['auto_attach_ubl'] ) {
			return;
		}
		
		$ubl_path = $this->generate_ubl_invoice( $order->get_id() );
		if ( $ubl_path && file_exists( $ubl_path ) ) {
			$email->attachments[] = $ubl_path;
		}
	}
	
	public function add_my_account_ubl_column( $columns ) {
		$columns['ubl-invoice'] = __( 'UBL Invoice', 'shopglut' );
		return $columns;
	}
	
	public function show_my_account_ubl_download( $order ) {
		if ( get_post_meta( $order->get_id(), '_ubl_invoice_generated', true ) ) {
			$download_url = $this->system_manager->get_document_url( $order->get_id(), 'ubl_invoice' );
			
			echo '<a href="' . esc_url( $download_url ) . '" class="button">' . esc_html__( 'Download', 'shopglut' ) . '</a>';
		} else {
			echo '<span class="na">&ndash;</span>';
		}
	}
	
	public function ajax_generate_ubl_invoice() {
		if ( ! isset( $_GET['order_id'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order_id = intval( $_GET['order_id'] );
		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) );
		
		if ( ! wp_verify_nonce( $nonce, 'download_ubl_invoice_' . $order_id ) ) {
			$this->system_manager->handle_access_denied( $order_id, 'ubl_invoice' );
			return;
		}
		
		if ( ! $this->system_manager->check_document_access( $order_id, 'ubl_invoice' ) ) {
			$this->system_manager->handle_access_denied( $order_id, 'ubl_invoice' );
			return;
		}
		
		$file_path = $this->generate_ubl_invoice( $order_id );
		
		if ( $file_path && $this->system_manager->file_exists( $file_path ) ) {
			$this->serve_ubl_file( $file_path );
		} else {
			wp_die( esc_html__( 'UBL Invoice could not be generated', 'shopglut' ) );
		}
	}
	
	private function serve_ubl_file( $file_path ) {
		$extension = pathinfo( $file_path, PATHINFO_EXTENSION );
		
		if ( $extension === 'xml' ) {
			header( 'Content-Type: application/xml' );
		} else {
			header( 'Content-Type: application/pdf' );
		}
		
		header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
		header( 'Content-Length: ' . filesize( $file_path ) );
		// Use WordPress filesystem for file operations
		$content = file_get_contents( $file_path );
		echo wp_kses_post( $content );
		exit;
	}
}