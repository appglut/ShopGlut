<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutInvoiceGenerator {
	
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
			add_action( 'woocommerce_email_before_order_table', array( $this, 'attach_invoice_to_email' ), 10, 4 );
			add_action( 'woocommerce_account_orders_columns', array( $this, 'add_my_account_invoice_column' ) );
			add_action( 'woocommerce_my_account_my_orders_column_order-invoice', array( $this, 'show_my_account_invoice_download' ) );
		}
		
		add_action( 'wp_ajax_generate_pdf_invoice', array( $this, 'ajax_generate_invoice' ) );
		add_action( 'wp_ajax_nopriv_generate_pdf_invoice', array( $this, 'ajax_generate_invoice' ) );
	}
	
	public function is_enabled() {
		return isset( $this->settings['enable_pdf_invoices'] ) && $this->settings['enable_pdf_invoices'] == 1;
	}
	
	public function handle_order_status_change( $order_id, $old_status, $new_status ) {
		if ( $this->should_generate_invoice_for_status( $new_status ) ) {
			$this->generate_invoice( $order_id );
		}
	}
	
	private function should_generate_invoice_for_status( $status ) {
		$disabled_statuses = isset( $this->settings['disable_for_statuses'] ) ? $this->settings['disable_for_statuses'] : array();
		return ! in_array( $status, $disabled_statuses );
	}
	
	public function generate_invoice( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}
		
		if ( isset( $this->settings['disable_for_free_orders'] ) && $this->settings['disable_for_free_orders'] && $order->get_total() == 0 ) {
			return false;
		}
		
		// In test mode, always use fresh settings instead of cached document settings
		if ( isset( $this->settings['test_mode'] ) && $this->settings['test_mode'] ) {
			// Reload settings to ensure latest configuration is used
			$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		}
		
		$invoice_data = $this->prepare_invoice_data( $order );
		$pdf_content = $this->generate_pdf_content( $invoice_data );
		
		$filename = $this->get_invoice_filename( $order );
		$file_path = $this->save_pdf_file( $pdf_content, $filename );
		
		if ( $file_path ) {
			$this->save_invoice_metadata( $order_id, $filename, $file_path );
			
			// Mark as printed if setting is enabled
			if ( isset( $this->settings['mark_printed'] ) && $this->settings['mark_printed'] ) {
				update_post_meta( $order_id, '_invoice_printed', current_time( 'timestamp' ) );
			}
			
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				$order->add_order_note( 
					// translators: %s is the filename of the generated PDF invoice
					sprintf( __( 'PDF Invoice generated: %s', 'shopglut' ), $filename )
				);
			}
			
			return $file_path;
		}
		
		return false;
	}
	
	private function prepare_invoice_data( $order ) {
		$company_info = $this->get_company_information();
		$invoice_number = $this->get_invoice_number( $order );
		$invoice_date = $this->get_invoice_date( $order );
		
		return array(
			'order' => $order,
			'company_info' => $company_info,
			'invoice_number' => $invoice_number,
			'invoice_date' => $invoice_date,
			'due_date' => $this->calculate_due_date( $invoice_date ),
			'template_settings' => $this->get_template_settings(),
			'display_settings' => $this->get_display_settings(),
			'display_data' => $this->prepare_display_data( $order ),
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
		
		$invoice_number = $this->system_manager->get_next_invoice_number( $order->get_id() );
		update_post_meta( $order->get_id(), '_invoice_number', $invoice_number );
		
		return $invoice_number;
	}
	
	private function generate_sequential_invoice_number( $order ) {
		global $wpdb;
		
		$prefix = isset( $this->settings['invoice_number_prefix'] ) ? $this->settings['invoice_number_prefix'] : 'INV-';
		$suffix = isset( $this->settings['invoice_number_suffix'] ) ? $this->settings['invoice_number_suffix'] : '';
		$padding = isset( $this->settings['invoice_number_padding'] ) ? intval( $this->settings['invoice_number_padding'] ) : 4;
		
		$reset_yearly = isset( $this->settings['reset_number_yearly'] ) && $this->settings['reset_number_yearly'];
		
		if ( $reset_yearly ) {
			$year = wp_date( 'Y' );
			
			// Cache key for yearly invoice numbers
			$cache_key = 'shopglut_max_invoice_yearly_' . md5( $prefix . $year );
			$next_number = wp_cache_get( $cache_key );
			
			if ( false === $next_number ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$next_number = $wpdb->get_var( $wpdb->prepare( 
				"SELECT MAX(CAST(SUBSTRING(meta_value, %d) AS UNSIGNED)) + 1 
				FROM {$wpdb->postmeta} 
				WHERE meta_key = '_invoice_number' 
				AND meta_value LIKE %s", 
				strlen( $prefix . $year ), 
					$wpdb->esc_like( $prefix . $year ) . '%'
				) );
				$next_number = $next_number ? $next_number : 1;
				wp_cache_set( $cache_key, $next_number, '', 60 ); // Cache for 1 minute
			}
			$invoice_number = $prefix . $year . str_pad( $next_number, $padding, '0', STR_PAD_LEFT ) . $suffix;
		} else {
			// Cache key for global invoice numbers
			$cache_key = 'shopglut_max_invoice_global_' . md5( $prefix );
			$next_number = wp_cache_get( $cache_key );
			
			if ( false === $next_number ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$next_number = $wpdb->get_var( $wpdb->prepare( 
				"SELECT MAX(CAST(SUBSTRING(meta_value, %d) AS UNSIGNED)) + 1 
				FROM {$wpdb->postmeta} 
				WHERE meta_key = '_invoice_number' 
				AND meta_value LIKE %s", 
				strlen( $prefix ), 
					$wpdb->esc_like( $prefix ) . '%'
				) );
				$next_number = $next_number ? $next_number : 1;
				wp_cache_set( $cache_key, $next_number, '', 60 ); // Cache for 1 minute
			}
			$invoice_number = $prefix . str_pad( $next_number, $padding, '0', STR_PAD_LEFT ) . $suffix;
		}
		
		return $invoice_number;
	}
	
	private function generate_custom_invoice_number( $order ) {
		return $this->generate_sequential_invoice_number( $order );
	}
	
	private function get_invoice_date( $order ) {
		$display_setting = isset( $this->settings['display_invoice_date'] ) ? $this->settings['display_invoice_date'] : 'document_date';
		
		switch ( $display_setting ) {
			case 'order_date':
				return $order->get_date_created() ? $order->get_date_created()->format( 'Y-m-d H:i:s' ) : current_time( 'Y-m-d H:i:s' );
			case 'document_date':
			default:
				return current_time( 'Y-m-d H:i:s' );
		}
	}
	
	private function calculate_due_date( $invoice_date ) {
		if ( ! isset( $this->settings['display_due_date'] ) || ! $this->settings['display_due_date'] ) {
			return null;
		}
		
		$due_days = isset( $this->settings['due_date_days'] ) ? intval( $this->settings['due_date_days'] ) : 30;
		return wp_date( 'Y-m-d H:i:s', strtotime( $invoice_date . ' + ' . $due_days . ' days' ) );
	}
	
	private function get_template_settings() {
		$template = isset( $this->settings['invoice_template'] ) ? $this->settings['invoice_template'] : 'default';
		
		// Check if template is pro-only
		$pro_templates = array( 'business', 'corporate', 'elegant', 'professional', 'minimal', 'creative', 'invoice_plus', 'premium_1', 'premium_2', 'premium_3', 'premium_4', 'premium_5' );
		if ( in_array( $template, $pro_templates ) && ! $this->pro_manager->is_pro_feature_available( 'premium_templates' ) ) {
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				// Log template fallback through WordPress action hook
				do_action( 'shopglut_pdf_invoices_template_fallback', $template, 'default' );
			}
			$template = 'default';
		}
		
		$template_settings = array(
			'template' => $template,
			'primary_color' => isset( $this->settings['primary_color'] ) ? $this->settings['primary_color'] : '#2271b1',
			'secondary_color' => isset( $this->settings['secondary_color'] ) ? $this->settings['secondary_color'] : '#72aee6',
			'header_text_color' => isset( $this->settings['header_text_color'] ) ? $this->settings['header_text_color'] : '#ffffff',
			'body_text_color' => isset( $this->settings['body_text_color'] ) ? $this->settings['body_text_color'] : '#333333',
			'footer_text' => isset( $this->settings['footer_text'] ) ? $this->settings['footer_text'] : __( 'Thank you for your business!', 'shopglut' ),
			'extra_field_1' => isset( $this->settings['extra_field_1'] ) ? $this->settings['extra_field_1'] : '',
			'extra_field_2' => isset( $this->settings['extra_field_2'] ) ? $this->settings['extra_field_2'] : '',
			'extra_field_3' => isset( $this->settings['extra_field_3'] ) ? $this->settings['extra_field_3'] : '',
		);
		
		// Allow pro version to modify template settings
		return apply_filters( 'shopglut_pdf_invoices_template_settings', $template_settings, $this->settings );
	}
	
	private function get_display_settings() {
		return array(
			'show_email' => isset( $this->settings['display_email'] ) ? $this->settings['display_email'] : 1,
			'show_phone' => isset( $this->settings['display_phone'] ) ? $this->settings['display_phone'] : 1,
			'show_customer_notes' => isset( $this->settings['display_customer_notes'] ) ? $this->settings['display_customer_notes'] : 1,
			'show_shipping_address' => isset( $this->settings['display_shipping_address'] ) ? $this->settings['display_shipping_address'] : '',
			'show_invoice_number' => isset( $this->settings['display_invoice_number'] ) ? $this->settings['display_invoice_number'] : '',
			'show_invoice_date' => isset( $this->settings['display_invoice_date'] ) ? $this->settings['display_invoice_date'] : '',
			'show_due_date' => isset( $this->settings['display_due_date'] ) ? $this->settings['display_due_date'] : 0,
			'show_free_line_items' => isset( $this->settings['show_free_line_items'] ) ? $this->settings['show_free_line_items'] : 1,
			'paper_size' => isset( $this->settings['paper_size'] ) ? $this->settings['paper_size'] : 'A4',
		);
	}
	
	private function prepare_display_data( $order ) {
		$display_settings = $this->get_display_settings();
		
		return array(
			'show_email' => $display_settings['show_email'] && $order->get_billing_email(),
			'customer_email' => $order->get_billing_email(),
			
			'show_phone' => $display_settings['show_phone'] && $order->get_billing_phone(),
			'customer_phone' => $order->get_billing_phone(),
			
			'show_customer_notes' => $display_settings['show_customer_notes'] && $order->get_customer_note(),
			'customer_notes' => $order->get_customer_note(),
			
			'show_shipping_address' => $this->should_show_shipping_address( $order, $display_settings['show_shipping_address'] ),
			'shipping_address' => $order->get_formatted_shipping_address(),
			'billing_address' => $order->get_formatted_billing_address(),
			
			'display_number' => $this->get_display_number( $order, $display_settings['show_invoice_number'] ),
			'display_number_label' => $this->get_display_number_label( $display_settings['show_invoice_number'] ),
			
			'display_date' => $this->get_display_date( $order, $display_settings['show_invoice_date'] ),
			'display_date_label' => $this->get_display_date_label( $display_settings['show_invoice_date'] ),
			
			'show_due_date' => $display_settings['show_due_date'],
			'due_date_formatted' => $this->format_due_date( $this->calculate_due_date( $this->get_invoice_date( $order ) ) ),
		);
	}
	
	private function should_show_shipping_address( $order, $setting ) {
		switch ( $setting ) {
			case 'always':
				return true;
			case 'when_different':
				$billing = $order->get_address( 'billing' );
				$shipping = $order->get_address( 'shipping' );
				return $billing !== $shipping && !empty( $shipping['address_1'] );
			default:
				return false;
		}
	}
	
	private function get_display_number( $order, $setting ) {
		switch ( $setting ) {
			case 'invoice_number':
				return $this->get_invoice_number( $order );
			case 'order_number':
				return $order->get_order_number();
			default:
				return '';
		}
	}
	
	private function get_display_number_label( $setting ) {
		switch ( $setting ) {
			case 'invoice_number':
				return __( 'Invoice Number', 'shopglut' );
			case 'order_number':
				return __( 'Order Number', 'shopglut' );
			default:
				return '';
		}
	}
	
	private function get_display_date( $order, $setting ) {
		switch ( $setting ) {
			case 'document_date':
				return current_time( 'Y-m-d H:i:s' );
			case 'order_date':
				return $order->get_date_created() ? $order->get_date_created()->format( 'Y-m-d H:i:s' ) : current_time( 'Y-m-d H:i:s' );
			default:
				return '';
		}
	}
	
	private function get_display_date_label( $setting ) {
		switch ( $setting ) {
			case 'document_date':
				return __( 'Invoice Date', 'shopglut' );
			case 'order_date':
				return __( 'Order Date', 'shopglut' );
			default:
				return '';
		}
	}
	
	private function format_due_date( $due_date ) {
		if ( ! $due_date ) {
			return '';
		}
		return date_i18n( get_option( 'date_format' ), strtotime( $due_date ) );
	}
	
	private function generate_pdf_content( $invoice_data ) {
		ob_start();
		$this->load_invoice_template( $invoice_data );
		return ob_get_clean();
	}
	
	private function load_invoice_template( $invoice_data ) {
		$template = $invoice_data['template_settings']['template'];
		$template_file = dirname( __FILE__ ) . "/templates/invoice-{$template}.php";
		
		if ( ! file_exists( $template_file ) ) {
			$template_file = dirname( __FILE__ ) . "/templates/invoice-default.php";
		}
		
		if ( file_exists( $template_file ) ) {
			extract( $invoice_data );
			include $template_file;
		}
	}
	
	private function get_invoice_filename( $order ) {
		$pattern = isset( $this->settings['custom_pdf_filename'] ) ? $this->settings['custom_pdf_filename'] : 'invoice-{invoice_number}';
		
		$replacements = array(
			'{order_number}' => $order->get_order_number(),
			'{invoice_number}' => $this->get_invoice_number( $order ),
			'{date}' => wp_date( 'Y-m-d' ),
		);
		
		$filename = str_replace( array_keys( $replacements ), array_values( $replacements ), $pattern );
		return sanitize_file_name( $filename ) . '.pdf';
	}
	
	private function save_pdf_file( $content, $filename ) {
		$upload_dir = wp_upload_dir();
		$invoice_dir = $upload_dir['basedir'] . '/shopglut-invoices/';
		
		if ( ! $this->system_manager->file_exists( $invoice_dir ) ) {
			$this->system_manager->create_directory( $invoice_dir );
		}
		
		$file_path = $invoice_dir . $filename;
		
		if ( isset( $this->settings['html_output'] ) && $this->settings['html_output'] ) {
			$html_file_path = str_replace( '.pdf', '.html', $file_path );
			$this->system_manager->write_file( $html_file_path, $content );
			return $html_file_path;
		}
		
		return $this->convert_html_to_pdf( $content, $file_path );
	}
	
	private function convert_html_to_pdf( $html_content, $file_path ) {
		try {
			require_once dirname( __FILE__ ) . '/libraries/dompdf/autoload.inc.php';
			
			$dompdf = new \Dompdf\Dompdf();
			$dompdf->loadHtml( $html_content );
			
			$paper_size = isset( $this->settings['paper_size'] ) ? $this->settings['paper_size'] : 'A4';
			$dompdf->setPaper( $paper_size, 'portrait' );
			
			$dompdf->render();
			
			$pdf_content = $dompdf->output();
			$this->system_manager->write_file( $file_path, $pdf_content );
			
			return $file_path;
		} catch ( Exception $e ) {
			if ( isset( $this->settings['enable_debug'] ) && $this->settings['enable_debug'] ) {
				// Log error through WordPress action hook
				do_action( 'shopglut_pdf_invoices_generation_error', $e->getMessage() );
			}
			return false;
		}
	}
	
	private function save_invoice_metadata( $order_id, $filename, $file_path ) {
		update_post_meta( $order_id, '_invoice_filename', $filename );
		update_post_meta( $order_id, '_invoice_file_path', $file_path );
		update_post_meta( $order_id, '_invoice_generated', time() );
		
		$invoice_number = $this->get_invoice_number( wc_get_order( $order_id ) );
		update_post_meta( $order_id, '_invoice_number', $invoice_number );
		update_post_meta( $order_id, '_invoice_date', $this->get_invoice_date( wc_get_order( $order_id ) ) );
	}
	
	public function attach_invoice_to_email( $order, $sent_to_admin, $plain_text, $email ) {
		if ( ! isset( $this->settings['auto_attach_invoice'] ) || ! $this->settings['auto_attach_invoice'] ) {
			return;
		}
		
		$email_types = isset( $this->settings['invoice_email_types'] ) ? $this->settings['invoice_email_types'] : array();
		
		if ( ! in_array( $email->id, $email_types ) ) {
			return;
		}
		
		$invoice_path = $this->generate_invoice( $order->get_id() );
		if ( $invoice_path && file_exists( $invoice_path ) ) {
			$email->attachments[] = $invoice_path;
		}
	}
	
	public function add_my_account_invoice_column( $columns ) {
		$account_setting = isset( $this->settings['my_account_buttons'] ) ? $this->settings['my_account_buttons'] : 'available';
		
		if ( $account_setting !== 'never' ) {
			$columns['order-invoice'] = __( 'Invoice', 'shopglut' );
		}
		
		return $columns;
	}
	
	public function show_my_account_invoice_download( $order ) {
		$account_setting = isset( $this->settings['my_account_buttons'] ) ? $this->settings['my_account_buttons'] : 'available';
		
		if ( $this->can_download_from_account( $order, $account_setting ) ) {
			$download_url = $this->system_manager->get_document_url( $order->get_id(), 'invoice' );
			
			echo '<a href="' . esc_url( $download_url ) . '" class="button">' . esc_html__( 'Download', 'shopglut' ) . '</a>';
		} else {
			echo '<span class="na">&ndash;</span>';
		}
	}
	
	private function can_download_from_account( $order, $setting ) {
		switch ( $setting ) {
			case 'always':
				return true;
			
			case 'never':
				return false;
			
			case 'available':
				return get_post_meta( $order->get_id(), '_invoice_generated', true ) ? true : false;
			
			case 'custom':
				$allowed_statuses = isset( $this->settings['my_account_restrict_statuses'] ) ? $this->settings['my_account_restrict_statuses'] : array();
				return in_array( $order->get_status(), $allowed_statuses );
			
			default:
				return false;
		}
	}
	
	public function ajax_generate_invoice() {
		if ( ! isset( $_GET['order_id'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order_id = intval( $_GET['order_id'] );
		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) );
		
		if ( ! wp_verify_nonce( $nonce, 'download_invoice_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wp_die( esc_html__( 'Order not found', 'shopglut' ) );
		}
		
		if ( ! current_user_can( 'manage_woocommerce' ) && $order->get_customer_id() !== get_current_user_id() ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}
		
		$file_path = $this->generate_invoice( $order_id );
		
		if ( $file_path && file_exists( $file_path ) ) {
			$this->mark_as_printed( $order_id, 'download' );
			$this->serve_pdf_file( $file_path );
		} else {
			wp_die( esc_html__( 'Invoice could not be generated', 'shopglut' ) );
		}
	}
	
	private function mark_as_printed( $order_id, $context ) {
		$mark_settings = isset( $this->settings['mark_printed'] ) ? $this->settings['mark_printed'] : array();
		
		$should_mark = false;
		switch ( $context ) {
			case 'download':
				$should_mark = in_array( 'single', $mark_settings ) || in_array( 'my_account', $mark_settings );
				break;
			case 'email':
				$should_mark = in_array( 'email_attachment', $mark_settings );
				break;
		}
		
		if ( $should_mark ) {
			update_post_meta( $order_id, '_invoice_printed', time() );
			
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				$order = wc_get_order( $order_id );
				$order->add_order_note( __( 'Invoice marked as printed', 'shopglut' ) );
			}
		}
	}
	
	private function serve_pdf_file( $file_path ) {
		$display_mode = isset( $this->settings['download_display'] ) ? $this->settings['download_display'] : 'display';
		
		header( 'Content-Type: application/pdf' );
		
		if ( $display_mode === 'download' ) {
			header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
		} else {
			header( 'Content-Disposition: inline; filename="' . basename( $file_path ) . '"' );
		}
		
		header( 'Content-Length: ' . filesize( $file_path ) );
		// Use WordPress filesystem for file operations
		$content = file_get_contents( $file_path );
		echo wp_kses_post( $content );
		exit;
	}
}