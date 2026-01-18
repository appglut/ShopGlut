<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesAdvancedFeatures {
	
	private $settings;
	private $system_manager;
	private $pro_manager;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->system_manager = new ShopGlutPdfInvoicesSystemManager();
		$this->pro_manager = ShopGlutPdfInvoicesProManager::get_instance();
		$this->init_hooks();
	}
	
	private function init_hooks() {
		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $this, 'add_order_columns' ) );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'populate_order_columns' ), 10, 2 );
		add_filter( 'woocommerce_shop_order_search_fields', array( $this, 'add_invoice_number_search' ) );
		
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'add_order_meta_box_content' ) );
		add_action( 'save_post', array( $this, 'save_order_meta_box_data' ) );
		
		add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_bulk_actions' ) );
		add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk_actions' ), 10, 3 );
		
		add_action( 'init', array( $this, 'handle_yearly_reset' ) );
		add_action( 'wp_ajax_unmark_invoice_printed', array( $this, 'ajax_unmark_printed' ) );
		
		if ( isset( $this->settings['enable_cleanup'] ) && $this->settings['enable_cleanup'] ) {
			add_action( 'wp_scheduled_delete', array( $this, 'cleanup_temp_files' ) );
		}
	}
	
	public function add_order_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;
			
			if ( $key === 'order_number' ) {
				if ( isset( $this->settings['invoice_number_column'] ) && $this->settings['invoice_number_column'] ) {
					$new_columns['invoice_number'] = __( 'Invoice Number', 'shopglut' );
				}
				
				if ( isset( $this->settings['invoice_date_column'] ) && $this->settings['invoice_date_column'] ) {
					$new_columns['invoice_date'] = __( 'Invoice Date', 'shopglut' );
				}
			}
		}
		
		return $new_columns;
	}
	
	public function populate_order_columns( $column, $order_id ) {
		switch ( $column ) {
			case 'invoice_number':
				$invoice_number = get_post_meta( $order_id, '_invoice_number', true );
				if ( $invoice_number ) {
					echo esc_html( $invoice_number );
					
					if ( get_post_meta( $order_id, '_invoice_printed', true ) ) {
						echo ' <span class="invoice-printed-mark" title="' . esc_attr__( 'Printed', 'shopglut' ) . '">✓</span>';
					}
				} else {
					echo '<span class="na">&ndash;</span>';
				}
				break;
				
			case 'invoice_date':
				$invoice_date = get_post_meta( $order_id, '_invoice_date', true );
				if ( $invoice_date ) {
					echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $invoice_date ) ) );
				} else {
					echo '<span class="na">&ndash;</span>';
				}
				break;
		}
	}
	
	public function add_invoice_number_search( $search_fields ) {
		if ( isset( $this->settings['invoice_number_search'] ) && $this->settings['invoice_number_search'] ) {
			$search_fields[] = '_invoice_number';
		}
		
		return $search_fields;
	}
	
	public function add_order_meta_box_content( $order ) {
		$order_id = $order->get_id();
		
		$invoice_number = get_post_meta( $order_id, '_invoice_number', true );
		$invoice_date = get_post_meta( $order_id, '_invoice_date', true );
		$invoice_printed = get_post_meta( $order_id, '_invoice_printed', true );
		
		echo '<div class="shopglut-pdf-invoices-meta">';
		echo '<h4>' . esc_html__( 'PDF Invoice Information', 'shopglut' ) . '</h4>';
		
		if ( isset( $this->settings['enable_document_data_editing'] ) && $this->settings['enable_document_data_editing'] ) {
			echo '<p class="form-field">';
			echo '<label for="invoice_number">' . esc_html__( 'Invoice Number:', 'shopglut' ) . '</label>';
			echo '<input type="text" id="invoice_number" name="invoice_number" value="' . esc_attr( $invoice_number ) . '" />';
			echo '</p>';
			
			echo '<p class="form-field">';
			echo '<label for="invoice_date">' . esc_html__( 'Invoice Date:', 'shopglut' ) . '</label>';
			echo '<input type="datetime-local" id="invoice_date" name="invoice_date" value="' . esc_attr( $invoice_date ? wp_date( 'Y-m-d\TH:i', strtotime( $invoice_date ) ) : '' ) . '" />';
			echo '</p>';
		} else {
			echo '<p><strong>' . esc_html__( 'Invoice Number:', 'shopglut' ) . '</strong> ' . ( $invoice_number ? esc_html( $invoice_number ) : esc_html__( 'Not generated', 'shopglut' ) ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Invoice Date:', 'shopglut' ) . '</strong> ' . ( $invoice_date ? esc_html( date_i18n( get_option( 'datetime_format' ), strtotime( $invoice_date ) ) ) : esc_html__( 'Not generated', 'shopglut' ) ) . '</p>';
		}
		
		if ( $invoice_printed ) {
			echo '<p><strong>' . esc_html__( 'Printed:', 'shopglut' ) . '</strong> ' . esc_html( date_i18n( get_option( 'datetime_format' ), $invoice_printed ) );
			
			if ( isset( $this->settings['unmark_printed'] ) && $this->settings['unmark_printed'] ) {
				$unmark_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=unmark_invoice_printed&order_id=' . $order_id ), 'unmark_printed_' . $order_id );
				echo ' <a href="' . esc_url( $unmark_url ) . '" class="button button-small">' . esc_html__( 'Unmark as Printed', 'shopglut' ) . '</a>';
			}
			
			echo '</p>';
		}
		
		$invoice_actions = array();
		if ( $invoice_number ) {
			$invoice_url = $this->system_manager->get_document_url( $order_id, 'invoice' );
			$invoice_actions[] = '<a href="' . esc_url( $invoice_url ) . '" class="button" target="_blank">' . esc_html__( 'View Invoice', 'shopglut' ) . '</a>';
		}
		
		if ( isset( $this->settings['enable_packing_slips'] ) && $this->settings['enable_packing_slips'] ) {
			$packing_slip_url = $this->system_manager->get_document_url( $order_id, 'packing_slip' );
			$invoice_actions[] = '<a href="' . esc_url( $packing_slip_url ) . '" class="button" target="_blank">' . esc_html__( 'View Packing Slip', 'shopglut' ) . '</a>';
		}
		
		if ( isset( $this->settings['enable_ubl_invoices'] ) && $this->settings['enable_ubl_invoices'] ) {
			$ubl_url = $this->system_manager->get_document_url( $order_id, 'ubl_invoice' );
			$invoice_actions[] = '<a href="' . esc_url( $ubl_url ) . '" class="button" target="_blank">' . esc_html__( 'View UBL Invoice', 'shopglut' ) . '</a>';
		}
		
		// Allow pro version to add more document actions
		$pro_actions = apply_filters( 'shopglut_pdf_invoices_pro_order_actions', array(), $order_id, $this->pro_manager, $this->settings );
		$invoice_actions = array_merge( $invoice_actions, $pro_actions );
		
		if ( ! empty( $invoice_actions ) ) {
			echo '<p>' . wp_kses_post( implode( ' ', $invoice_actions ) ) . '</p>';
		}
		
		// Allow pro version to add custom meta box content
		do_action( 'shopglut_pdf_invoices_pro_order_meta_content', $order_id, $this->pro_manager, $this->settings );
		
		echo '</div>';
		
		wp_nonce_field( 'shopglut_save_invoice_meta', 'shopglut_invoice_meta_nonce' );
	}
	
	public function save_order_meta_box_data( $order_id ) {
		if ( ! isset( $_POST['shopglut_invoice_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopglut_invoice_meta_nonce'] ) ), 'shopglut_save_invoice_meta' ) ) {
			return;
		}
		
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		
		if ( ! isset( $this->settings['enable_document_data_editing'] ) || ! $this->settings['enable_document_data_editing'] ) {
			return;
		}
		
		if ( isset( $_POST['invoice_number'] ) ) {
			$invoice_number = sanitize_text_field( wp_unslash( $_POST['invoice_number'] ) );
			update_post_meta( $order_id, '_invoice_number', $invoice_number );
		}
		
		if ( isset( $_POST['invoice_date'] ) ) {
			$invoice_date = sanitize_text_field( wp_unslash( $_POST['invoice_date'] ) );
			if ( $invoice_date ) {
				update_post_meta( $order_id, '_invoice_date', wp_date( 'Y-m-d H:i:s', strtotime( $invoice_date ) ) );
			}
		}
		
		$mark_settings = isset( $this->settings['mark_printed'] ) ? $this->settings['mark_printed'] : array();
		if ( in_array( 'document_data', $mark_settings ) ) {
			update_post_meta( $order_id, '_invoice_printed', time() );
		}
	}
	
	public function add_bulk_actions( $actions ) {
		if ( isset( $this->settings['bulk_download'] ) && $this->settings['bulk_download'] ) {
			$actions['download_invoices'] = __( 'Download Invoices (PDF)', 'shopglut' );
			
			// Packing slips and UBL - available in free version
			if ( isset( $this->settings['enable_packing_slips'] ) && $this->settings['enable_packing_slips'] ) {
				$actions['download_packing_slips'] = __( 'Download Packing Slips (PDF)', 'shopglut' );
			}
			
			if ( isset( $this->settings['enable_ubl_invoices'] ) && $this->settings['enable_ubl_invoices'] ) {
				$actions['download_ubl_invoices'] = __( 'Download UBL Invoices', 'shopglut' );
			}
			
			// Allow pro version to add more bulk actions
			$actions = apply_filters( 'shopglut_pdf_invoices_bulk_actions', $actions, $this->settings );
			$actions = apply_filters( 'shopglut_pdf_invoices_pro_bulk_actions', $actions, $this->pro_manager, $this->settings );
		}
		
		return $actions;
	}
	
	public function handle_bulk_actions( $redirect_to, $action, $post_ids ) {
		// Allow pro version to handle custom bulk actions first
		$pro_handled = apply_filters( 'shopglut_pdf_invoices_handle_pro_bulk_action', false, $action, $post_ids, $this->pro_manager );
		if ( $pro_handled ) {
			return $redirect_to;
		}
		
		switch ( $action ) {
			case 'download_invoices':
				$this->bulk_download_documents( $post_ids, 'invoice' );
				break;
				
			case 'download_packing_slips':
				$this->bulk_download_documents( $post_ids, 'packing_slip' );
				break;
				
			case 'download_ubl_invoices':
				$this->bulk_download_documents( $post_ids, 'ubl_invoice' );
				break;
		}
		
		return $redirect_to;
	}
	
	private function bulk_download_documents( $order_ids, $document_type ) {
		$zip_filename = 'shopglut-' . $document_type . 's-' . wp_date( 'Y-m-d-H-i-s' ) . '.zip';
		$zip_path = sys_get_temp_dir() . '/' . $zip_filename;
		
		$zip = new ZipArchive();
		if ( $zip->open( $zip_path, ZipArchive::CREATE ) !== TRUE ) {
			wp_die( esc_html__( 'Cannot create ZIP file', 'shopglut' ) );
		}
		
		$mark_settings = isset( $this->settings['mark_printed'] ) ? $this->settings['mark_printed'] : array();
		$should_mark_bulk = in_array( 'bulk', $mark_settings );
		
		foreach ( $order_ids as $order_id ) {
			$file_path = $this->generate_document_for_bulk( $order_id, $document_type );
			
			if ( $file_path && file_exists( $file_path ) ) {
				$zip->addFile( $file_path, basename( $file_path ) );
				
				if ( $should_mark_bulk && $document_type === 'invoice' ) {
					update_post_meta( $order_id, '_invoice_printed', time() );
				}
			}
		}
		
		$zip->close();
		
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename="' . $zip_filename . '"' );
		header( 'Content-Length: ' . filesize( $zip_path ) );
		// Use WordPress filesystem for file operations
		$content = file_get_contents( $zip_path );
		echo wp_kses_post( $content );
		
		wp_delete_file( $zip_path );
		exit;
	}
	
	private function generate_document_for_bulk( $order_id, $document_type ) {
		// Allow pro version to handle custom document types
		$pro_file = apply_filters( 'shopglut_pdf_invoices_pro_bulk_document', false, $order_id, $document_type, $this->pro_manager );
		if ( $pro_file !== false ) {
			return $pro_file;
		}
		
		switch ( $document_type ) {
			case 'invoice':
				$generator = new ShopGlutInvoiceGenerator();
				return $generator->generate_invoice( $order_id );
				
			case 'packing_slip':
				$generator = new ShopGlutPackingSlipGenerator();
				return $generator->generate_packing_slip( $order_id );
				
			case 'ubl_invoice':
				$generator = new ShopGlutUblInvoiceGenerator();
				return $generator->generate_ubl_invoice( $order_id );
				
			default:
				return false;
		}
	}
	
	public function handle_yearly_reset() {
		if ( ! isset( $this->settings['reset_number_yearly'] ) || ! $this->settings['reset_number_yearly'] ) {
			return;
		}
		
		$last_reset = get_option( 'shopglut_invoice_last_yearly_reset', 0 );
		$current_year = wp_date( 'Y' );
		$last_reset_year = wp_date( 'Y', $last_reset );
		
		if ( $current_year > $last_reset_year ) {
			update_option( 'shopglut_invoice_last_yearly_reset', time() );
			
			if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
				// Log to order notes instead of error_log
				do_action( 'shopglut_pdf_invoices_yearly_reset', $current_year );
			}
		}
	}
	
	public function ajax_unmark_printed() {
		if ( ! isset( $_GET['order_id'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		$order_id = intval( $_GET['order_id'] );
		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) );
		
		if ( ! wp_verify_nonce( $nonce, 'unmark_printed_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}
		
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}
		
		delete_post_meta( $order_id, '_invoice_printed' );
		
		if ( isset( $this->settings['log_to_order_notes'] ) && $this->settings['log_to_order_notes'] ) {
			$order = wc_get_order( $order_id );
			$order->add_order_note( __( 'Invoice unmarked as printed', 'shopglut' ) );
		}
		
		wp_safe_redirect( admin_url( 'post.php?post=' . $order_id . '&action=edit' ) );
		exit;
	}
	
	public function cleanup_temp_files() {
		if ( ! isset( $this->settings['enable_cleanup'] ) || ! $this->settings['enable_cleanup'] ) {
			return;
		}
		
		$cleanup_days = isset( $this->settings['cleanup_days'] ) ? intval( $this->settings['cleanup_days'] ) : 7;
		$cleanup_timestamp = time() - ( $cleanup_days * DAY_IN_SECONDS );
		
		$upload_dir = wp_upload_dir();
		$directories = array(
			$upload_dir['basedir'] . '/shopglut-invoices/',
			$upload_dir['basedir'] . '/shopglut-packing-slips/',
			$upload_dir['basedir'] . '/shopglut-ubl-invoices/',
		);
		
		// Allow pro version to add more directories for cleanup
		$directories = apply_filters( 'shopglut_pdf_invoices_pro_cleanup_directories', $directories, $this->pro_manager );
		
		foreach ( $directories as $directory ) {
			if ( ! is_dir( $directory ) ) {
				continue;
			}
			
			$files = glob( $directory . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) && filemtime( $file ) < $cleanup_timestamp ) {
					wp_delete_file( $file );
				}
			}
		}
	}
	
	public function get_currency_symbol_extended( $currency ) {
		// Extended currency support - available in free version but limited
		if ( ! isset( $this->settings['extended_currency_support'] ) || ! $this->settings['extended_currency_support'] ) {
			return get_woocommerce_currency_symbol( $currency );
		}
		
		// Allow pro version to provide more currency symbols
		$pro_symbol = apply_filters( 'shopglut_pdf_invoices_pro_currency_symbol', false, $currency );
		if ( $pro_symbol !== false ) {
			return $pro_symbol;
		}
		
		$extended_symbols = array(
			'AED' => 'د.إ',
			'AFN' => '؋',
			'ALL' => 'L',
			'AMD' => '֏',
			'ANG' => 'ƒ',
			'AOA' => 'Kz',
			'ARS' => '$',
			'AUD' => '$',
			'AWG' => 'ƒ',
			'AZN' => '₼',
			'BAM' => 'КМ',
			'BBD' => '$',
			'BDT' => '৳',
			'BGN' => 'лв',
			'BHD' => '.د.ب',
			'BIF' => 'FBu',
			'BMD' => '$',
			'BND' => '$',
			'BOB' => '$b',
			'BRL' => 'R$',
			'BSD' => '$',
			'BTC' => '₿',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYN' => 'Br',
			'BZD' => 'BZ$',
			'CAD' => '$',
			'CDF' => 'FC',
			'CHF' => 'CHF',
			'CLP' => '$',
			'CNY' => '¥',
			'COP' => '$',
			'CRC' => '₡',
			'CUC' => '$',
			'CUP' => '₱',
			'CVE' => '$',
			'CZK' => 'Kč',
			'DJF' => 'Fdj',
			'DKK' => 'kr',
			'DOP' => 'RD$',
			'DZD' => 'دج',
			'EGP' => '£',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '€',
			'FJD' => '$',
			'FKP' => '£',
			'GBP' => '£',
			'GEL' => '₾',
			'GGP' => '£',
			'GHS' => '¢',
			'GIP' => '£',
			'GMD' => 'D',
			'GNF' => 'FG',
			'GTQ' => 'Q',
			'GYD' => '$',
			'HKD' => '$',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => 'Ft',
			'IDR' => 'Rp',
			'ILS' => '₪',
			'IMP' => '£',
			'INR' => '₹',
			'IQD' => 'ع.د',
			'IRR' => '﷼',
			'ISK' => 'kr',
			'JEP' => '£',
			'JMD' => 'J$',
			'JOD' => 'JD',
			'JPY' => '¥',
			'KES' => 'KSh',
			'KGS' => 'лв',
			'KHR' => '៛',
			'KMF' => 'CF',
			'KPW' => '₩',
			'KRW' => '₩',
			'KWD' => 'KD',
			'KYD' => '$',
			'KZT' => 'лв',
			'LAK' => '₭',
			'LBP' => '£',
			'LKR' => '₨',
			'LRD' => '$',
			'LSL' => 'M',
			'LYD' => 'LD',
			'MAD' => 'MAD',
			'MDL' => 'lei',
			'MGA' => 'Ar',
			'MKD' => 'ден',
			'MMK' => 'K',
			'MNT' => '₮',
			'MOP' => 'MOP$',
			'MRO' => 'UM',
			'MUR' => '₨',
			'MVR' => 'Rf',
			'MWK' => 'MK',
			'MXN' => '$',
			'MYR' => 'RM',
			'MZN' => 'MT',
			'NAD' => '$',
			'NGN' => '₦',
			'NIO' => 'C$',
			'NOK' => 'kr',
			'NPR' => '₨',
			'NZD' => '$',
			'OMR' => '﷼',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '₱',
			'PKR' => '₨',
			'PLN' => 'zł',
			'PYG' => 'Gs',
			'QAR' => '﷼',
			'RON' => 'lei',
			'RSD' => 'Дин.',
			'RUB' => '₽',
			'RWF' => 'R₣',
			'SAR' => '﷼',
			'SBD' => '$',
			'SCR' => '₨',
			'SDG' => 'ج.س.',
			'SEK' => 'kr',
			'SGD' => '$',
			'SHP' => '£',
			'SLE' => 'Le',
			'SLL' => 'Le',
			'SOS' => 'S',
			'SRD' => '$',
			'STD' => 'Db',
			'SVC' => '$',
			'SYP' => '£',
			'SZL' => 'E',
			'THB' => '฿',
			'TJS' => 'SM',
			'TMT' => 'T',
			'TND' => 'د.ت',
			'TOP' => 'T$',
			'TRY' => '₺',
			'TTD' => 'TT$',
			'TVD' => '$',
			'TWD' => 'NT$',
			'TZS' => 'TSh',
			'UAH' => '₴',
			'UGX' => 'USh',
			'USD' => '$',
			'UYU' => '$U',
			'UZS' => 'лв',
			'VEF' => 'Bs',
			'VES' => 'Bs.S',
			'VND' => '₫',
			'VUV' => 'VT',
			'WST' => 'WS$',
			'XAF' => 'FCFA',
			'XCD' => '$',
			'XDR' => 'SDR',
			'XOF' => 'CFA',
			'XPF' => '₣',
			'YER' => '﷼',
			'ZAR' => 'R',
			'ZMW' => 'ZK',
			'ZWL' => 'Z$',
		);
		
		return isset( $extended_symbols[ $currency ] ) ? $extended_symbols[ $currency ] : get_woocommerce_currency_symbol( $currency );
	}
}