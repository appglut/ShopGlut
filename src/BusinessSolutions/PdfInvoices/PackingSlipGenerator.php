<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPackingSlipGenerator {
	
	private $settings;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->init_hooks();
	}
	
	private function init_hooks() {
		if ( $this->is_enabled() ) {
			add_action( 'woocommerce_order_status_changed', array( $this, 'handle_order_status_change' ), 10, 3 );
			add_action( 'woocommerce_email_before_order_table', array( $this, 'attach_packing_slip_to_email' ), 10, 4 );
			add_action( 'woocommerce_account_orders_columns', array( $this, 'add_my_account_packing_slip_column' ) );
			add_action( 'woocommerce_my_account_my_orders_column_packing-slip', array( $this, 'show_my_account_packing_slip_download' ) );
		}
		
		add_action( 'wp_ajax_generate_packing_slip', array( $this, 'ajax_generate_packing_slip' ) );
		add_action( 'wp_ajax_nopriv_generate_packing_slip', array( $this, 'ajax_generate_packing_slip' ) );
	}
	
	public function is_enabled() {
		return isset( $this->settings['enable_packing_slips'] ) && $this->settings['enable_packing_slips'] == 1;
	}
	
	public function handle_order_status_change( $order_id, $old_status, $new_status ) {
		if ( $this->should_generate_packing_slip_for_status( $new_status ) ) {
			$this->generate_packing_slip( $order_id );
		}
	}
	
	private function should_generate_packing_slip_for_status( $status ) {
		// Generate packing slips for orders that are being processed or fulfilled
		$processing_statuses = array( 'processing', 'completed' );
		
		// Check if there are custom status restrictions (could be added later)
		$disabled_statuses = isset( $this->settings['packing_slip_disable_for_statuses'] ) ? $this->settings['packing_slip_disable_for_statuses'] : array();
		
		return in_array( $status, $processing_statuses ) && ! in_array( $status, $disabled_statuses );
	}
	
	public function generate_packing_slip( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}
		
		// In test mode, always use fresh settings instead of cached document settings
		if ( isset( $this->settings['test_mode'] ) && $this->settings['test_mode'] ) {
			// Reload settings to ensure latest configuration is used
			$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		}
		
		$packing_slip_data = $this->prepare_packing_slip_data( $order );
		$pdf_content = $this->generate_pdf_content( $packing_slip_data );
		
		$filename = $this->get_packing_slip_filename( $order );
		$file_path = $this->save_pdf_file( $pdf_content, $filename );
		
		if ( $file_path ) {
			$this->save_packing_slip_metadata( $order_id, $filename, $file_path );
			
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				$order->add_order_note( 
					// translators: %s is the filename of the generated packing slip
					sprintf( __( 'Packing Slip generated: %s', 'shopglut' ), $filename )
				);
			}
			
			return $file_path;
		}
		
		return false;
	}
	
	private function prepare_packing_slip_data( $order ) {
		$company_info = $this->get_company_information();
		
		return array(
			'order' => $order,
			'company_info' => $company_info,
			'packing_slip_number' => $this->get_packing_slip_number( $order ),
			'packing_date' => current_time( 'Y-m-d H:i:s' ),
			'template_settings' => $this->get_template_settings(),
			'display_settings' => $this->get_display_settings(),
			'show_sku' => isset( $this->settings['show_sku_on_packing_slip'] ) ? $this->settings['show_sku_on_packing_slip'] : 1,
			'show_weight' => isset( $this->settings['show_weight_on_packing_slip'] ) ? $this->settings['show_weight_on_packing_slip'] : 0,
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
	
	private function get_packing_slip_number( $order ) {
		return 'PS-' . $order->get_order_number();
	}
	
	private function get_template_settings() {
		return array(
			'template' => isset( $this->settings['packaging_template'] ) ? $this->settings['packaging_template'] : 'default',
			'primary_color' => isset( $this->settings['primary_color'] ) ? $this->settings['primary_color'] : '#2271b1',
			'secondary_color' => isset( $this->settings['secondary_color'] ) ? $this->settings['secondary_color'] : '#72aee6',
			'header_text_color' => isset( $this->settings['header_text_color'] ) ? $this->settings['header_text_color'] : '#ffffff',
			'body_text_color' => isset( $this->settings['body_text_color'] ) ? $this->settings['body_text_color'] : '#333333',
			'footer_text' => isset( $this->settings['footer_text'] ) ? $this->settings['footer_text'] : __( 'Thank you for your business!', 'shopglut' ),
			'extra_field_1' => isset( $this->settings['extra_field_1'] ) ? $this->settings['extra_field_1'] : '',
			'extra_field_2' => isset( $this->settings['extra_field_2'] ) ? $this->settings['extra_field_2'] : '',
			'extra_field_3' => isset( $this->settings['extra_field_3'] ) ? $this->settings['extra_field_3'] : '',
		);
	}
	
	private function get_display_settings() {
		return array(
			'show_email' => isset( $this->settings['display_email'] ) ? $this->settings['display_email'] : 1,
			'show_phone' => isset( $this->settings['display_phone'] ) ? $this->settings['display_phone'] : 1,
			'show_customer_notes' => isset( $this->settings['display_customer_notes'] ) ? $this->settings['display_customer_notes'] : 1,
			'show_shipping_address' => 'always',
			'paper_size' => isset( $this->settings['paper_size'] ) ? $this->settings['paper_size'] : 'A4',
			'show_prices' => isset( $this->settings['show_prices'] ) ? $this->settings['show_prices'] : 0,
		);
	}
	
	private function generate_pdf_content( $packing_slip_data ) {
		ob_start();
		$this->load_packing_slip_template( $packing_slip_data );
		return ob_get_clean();
	}
	
	private function load_packing_slip_template( $packing_slip_data ) {
		$template = $packing_slip_data['template_settings']['template'];
		$template_file = dirname( __FILE__ ) . "/templates/packing-slip-{$template}.php";
		
		if ( ! file_exists( $template_file ) ) {
			$template_file = dirname( __FILE__ ) . "/templates/packing-slip-default.php";
		}
		
		if ( file_exists( $template_file ) ) {
			extract( $packing_slip_data );
			include $template_file;
		}
	}
	
	private function get_packing_slip_filename( $order ) {
		$filename = 'packing-slip-' . $order->get_order_number() . '-' . wp_date( 'Y-m-d' );
		return sanitize_file_name( $filename ) . '.pdf';
	}
	
	private function save_pdf_file( $content, $filename ) {
		$upload_dir = wp_upload_dir();
		$packing_slip_dir = $upload_dir['basedir'] . '/shopglut-packing-slips/';
		
		if ( ! file_exists( $packing_slip_dir ) ) {
			wp_mkdir_p( $packing_slip_dir );
		}
		
		$file_path = $packing_slip_dir . $filename;
		
		if ( isset( $this->settings['html_output'] ) && $this->settings['html_output'] ) {
			// Use WordPress filesystem for file operations
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			$wp_filesystem->put_contents( str_replace( '.pdf', '.html', $file_path ), $content, FS_CHMOD_FILE );
			return str_replace( '.pdf', '.html', $file_path );
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
			// Use WordPress filesystem for file operations
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			$wp_filesystem->put_contents( $file_path, $pdf_content, FS_CHMOD_FILE );
			
			return $file_path;
		} catch ( Exception $e ) {
			if ( isset( $this->settings['enable_debug'] ) && $this->settings['enable_debug'] ) {
				// Log error through WordPress action hook
				do_action( 'shopglut_pdf_packing_slip_generation_error', $e->getMessage() );
			}
			return false;
		}
	}
	
	private function save_packing_slip_metadata( $order_id, $filename, $file_path ) {
		update_post_meta( $order_id, '_packing_slip_filename', $filename );
		update_post_meta( $order_id, '_packing_slip_file_path', $file_path );
		update_post_meta( $order_id, '_packing_slip_generated', time() );
	}
	
	public function attach_packing_slip_to_email( $order, $sent_to_admin, $plain_text, $email ) {
		if ( ! isset( $this->settings['auto_attach_packing_slip'] ) || ! $this->settings['auto_attach_packing_slip'] ) {
			return;
		}
		
		$email_types = isset( $this->settings['packing_slip_email_types'] ) ? $this->settings['packing_slip_email_types'] : array();
		
		if ( ! in_array( $email->id, $email_types ) ) {
			return;
		}
		
		$packing_slip_path = $this->generate_packing_slip( $order->get_id() );
		if ( $packing_slip_path && file_exists( $packing_slip_path ) ) {
			$email->attachments[] = $packing_slip_path;
		}
	}
	
	public function add_my_account_packing_slip_column( $columns ) {
		// Only add column if packing slips are enabled
		if ( $this->is_enabled() ) {
			$columns['packing-slip'] = __( 'Packing Slip', 'shopglut' );
		}
		return $columns;
	}
	
	public function show_my_account_packing_slip_download( $order ) {
		if ( get_post_meta( $order->get_id(), '_packing_slip_generated', true ) ) {
			$download_url = wp_nonce_url( 
				admin_url( 'admin-ajax.php?action=generate_packing_slip&order_id=' . $order->get_id() ),
				'download_packing_slip_' . $order->get_id()
			);
			
			echo '<a href="' . esc_url( $download_url ) . '" class="button">' . esc_html__( 'Download', 'shopglut' ) . '</a>';
		} else {
			echo '<span class="na">&ndash;</span>';
		}
	}
	
	public function ajax_generate_packing_slip() {
		if ( ! isset( $_GET['order_id'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order_id = intval( $_GET['order_id'] );
		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) );
		
		if ( ! wp_verify_nonce( $nonce, 'download_packing_slip_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wp_die( esc_html__( 'Order not found', 'shopglut' ) );
		}
		
		if ( ! current_user_can( 'manage_woocommerce' ) && $order->get_customer_id() !== get_current_user_id() ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}
		
		$file_path = $this->generate_packing_slip( $order_id );
		
		if ( $file_path && file_exists( $file_path ) ) {
			$this->serve_pdf_file( $file_path );
		} else {
			wp_die( esc_html__( 'Packing slip could not be generated', 'shopglut' ) );
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