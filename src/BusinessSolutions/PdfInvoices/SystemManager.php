<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesSystemManager {
	
	private $settings;
	private $filesystem;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->init_hooks();
		$this->init_filesystem();
	}
	
	private function init_hooks() {
		add_action( 'init', array( $this, 'init_pretty_links' ) );
		add_action( 'template_redirect', array( $this, 'handle_pretty_document_links' ) );
		add_action( 'wp_ajax_shopglut_download_document', array( $this, 'handle_document_download' ) );
		add_action( 'wp_ajax_nopriv_shopglut_download_document', array( $this, 'handle_document_download' ) );
	}
	
	private function init_filesystem() {
		$method = isset( $this->settings['file_system_method'] ) ? $this->settings['file_system_method'] : 'php';
		
		if ( $method === 'wp' ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			
			if ( ! WP_Filesystem() ) {
				// Fallback to PHP method if WP Filesystem fails
				$this->filesystem = 'php';
			} else {
				global $wp_filesystem;
				$this->filesystem = $wp_filesystem;
			}
		} else {
			$this->filesystem = 'php';
		}
	}
	
	public function write_file( $file_path, $content ) {
		if ( $this->filesystem === 'php' ) {
			return file_put_contents( $file_path, $content );
		} else {
			return $this->filesystem->put_contents( $file_path, $content, FS_CHMOD_FILE );
		}
	}
	
	public function create_directory( $directory ) {
		if ( $this->filesystem === 'php' ) {
			return wp_mkdir_p( $directory );
		} else {
			return $this->filesystem->mkdir( $directory, FS_CHMOD_DIR );
		}
	}
	
	public function file_exists( $file_path ) {
		if ( $this->filesystem === 'php' ) {
			return file_exists( $file_path );
		} else {
			return $this->filesystem->exists( $file_path );
		}
	}
	
	public function check_document_access( $order_id, $document_type = 'invoice' ) {
		$access_type = isset( $this->settings['document_link_access_type'] ) ? $this->settings['document_link_access_type'] : 'logged_in';
		$order = wc_get_order( $order_id );
		
		if ( ! $order ) {
			return false;
		}
		
		switch ( $access_type ) {
			case 'full':
				return true;
				
			case 'logged_in':
			default:
				if ( ! is_user_logged_in() ) {
					return false;
				}
				
				// Admin can access all documents
				if ( current_user_can( 'manage_woocommerce' ) ) {
					return true;
				}
				
				// Customer can only access their own documents
				return $order->get_customer_id() === get_current_user_id();
		}
	}
	
	public function handle_access_denied( $order_id, $document_type = 'invoice' ) {
		$redirect_type = isset( $this->settings['document_access_denied_redirect_page'] ) ? $this->settings['document_access_denied_redirect_page'] : 'blank_page';
		
		switch ( $redirect_type ) {
			case 'login_page':
				$redirect_url = wp_login_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : home_url() );
				wp_safe_redirect( $redirect_url );
				exit;
				
			case 'myaccount_page':
				if ( function_exists( 'wc_get_page_id' ) ) {
					$myaccount_page_id = wc_get_page_id( 'myaccount' );
					if ( $myaccount_page_id > 0 ) {
						$redirect_url = get_permalink( $myaccount_page_id );
						wp_safe_redirect( $redirect_url );
						exit;
					}
				}
				// Fallback to blank page if myaccount page not found
				$this->show_access_denied_page();
				break;
				
			case 'custom_page':
				$custom_url = isset( $this->settings['document_custom_redirect_page'] ) ? $this->settings['document_custom_redirect_page'] : '';
				if ( ! empty( $custom_url ) && ! filter_var( $custom_url, FILTER_VALIDATE_URL ) === false ) {
					// Ensure it's a local URL for security
					if ( wp_parse_url( $custom_url, PHP_URL_HOST ) === wp_parse_url( home_url(), PHP_URL_HOST ) ) {
						wp_safe_redirect( $custom_url );
						exit;
					}
				}
				// Fallback to blank page if custom URL is invalid
				$this->show_access_denied_page();
				break;
				
			case 'blank_page':
			default:
				$this->show_access_denied_page();
				break;
		}
	}
	
	private function show_access_denied_page() {
		status_header( 403 );
		wp_die( 
			esc_html__( 'Access denied. You do not have permission to view this document.', 'shopglut' ),
			esc_html__( 'Access Denied', 'shopglut' ),
			array( 'response' => 403 )
		);
	}
	
	public function init_pretty_links() {
		if ( ! isset( $this->settings['pretty_document_links'] ) || ! $this->settings['pretty_document_links'] ) {
			return;
		}
		
		add_rewrite_rule(
			'^shopglut/documents/([^/]+)/([0-9]+)/?$',
			'index.php?shopglut_document_type=$matches[1]&shopglut_order_id=$matches[2]',
			'top'
		);
		
		add_rewrite_tag( '%shopglut_document_type%', '([^&]+)' );
		add_rewrite_tag( '%shopglut_order_id%', '([0-9]+)' );
		
		// Flush rewrite rules if the setting was just enabled
		$pretty_links_version = get_option( 'shopglut_pretty_links_version', 0 );
		if ( $pretty_links_version < 1 ) {
			flush_rewrite_rules();
			update_option( 'shopglut_pretty_links_version', 1 );
		}
	}
	
	public function handle_pretty_document_links() {
		$document_type = get_query_var( 'shopglut_document_type' );
		$order_id = get_query_var( 'shopglut_order_id' );
		
		if ( $document_type && $order_id ) {
			$this->serve_document( $order_id, $document_type );
		}
	}
	
	public function get_document_url( $order_id, $document_type = 'invoice' ) {
		if ( isset( $this->settings['pretty_document_links'] ) && $this->settings['pretty_document_links'] ) {
			return home_url( "shopglut/documents/{$document_type}/{$order_id}/" );
		} else {
			$action_map = array(
				'invoice' => 'generate_pdf_invoice',
				'packing_slip' => 'generate_packing_slip',
				'ubl_invoice' => 'generate_ubl_invoice',
			);
			
			$action = isset( $action_map[ $document_type ] ) ? $action_map[ $document_type ] : 'generate_pdf_invoice';
			
			return wp_nonce_url( 
				admin_url( "admin-ajax.php?action={$action}&order_id={$order_id}" ),
				"download_{$document_type}_{$order_id}"
			);
		}
	}
	
	public function handle_document_download() {
		$order_id = intval( $_GET['order_id'] ?? 0 );
		$document_type = isset( $_GET['document_type'] ) ? sanitize_text_field( wp_unslash( $_GET['document_type'] ) ) : 'invoice';
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		
		if ( ! wp_verify_nonce( $nonce, "download_{$document_type}_{$order_id}" ) ) {
			$this->handle_access_denied( $order_id, $document_type );
			return;
		}
		
		$this->serve_document( $order_id, $document_type );
	}
	
	private function serve_document( $order_id, $document_type ) {
		if ( ! $this->check_document_access( $order_id, $document_type ) ) {
			$this->handle_access_denied( $order_id, $document_type );
			return;
		}
		
		$file_path = false;
		
		switch ( $document_type ) {
			case 'invoice':
				$generator = new ShopGlutInvoiceGenerator();
				$file_path = $generator->generate_invoice( $order_id );
				break;
				
			case 'packing_slip':
				if ( class_exists( 'ShopGlutPackingSlipGenerator' ) ) {
					$generator = new ShopGlutPackingSlipGenerator();
					$file_path = $generator->generate_packing_slip( $order_id );
				}
				break;
				
			case 'ubl_invoice':
				$generator = new ShopGlutUblInvoiceGenerator();
				$file_path = $generator->generate_ubl_invoice( $order_id );
				break;
		}
		
		if ( $file_path && $this->file_exists( $file_path ) ) {
			$this->serve_file( $file_path );
		} else {
			wp_die( esc_html__( 'Document could not be generated', 'shopglut' ) );
		}
	}
	
	private function serve_file( $file_path ) {
		$extension = pathinfo( $file_path, PATHINFO_EXTENSION );
		
		switch ( $extension ) {
			case 'pdf':
				header( 'Content-Type: application/pdf' );
				break;
			case 'xml':
				header( 'Content-Type: application/xml' );
				break;
			default:
				header( 'Content-Type: application/octet-stream' );
		}
		
		$display_setting = isset( $this->settings['download_display'] ) ? $this->settings['download_display'] : 'display';
		
		if ( $display_setting === 'download' ) {
			header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
		} else {
			header( 'Content-Disposition: inline; filename="' . basename( $file_path ) . '"' );
		}
		
		header( 'Content-Length: ' . filesize( $file_path ) );
		
		if ( $this->filesystem === 'php' ) {
			// Use WP_Filesystem instead of readfile for better compatibility
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			
			if ( WP_Filesystem() ) {
				global $wp_filesystem;
				echo $wp_filesystem->get_contents( $file_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				// Fallback to direct file reading only if WP_Filesystem fails
				readfile( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
			}
		} else {
			echo $this->filesystem->get_contents( $file_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		exit;
	}
	
	public function get_next_invoice_number( $order_id ) {
		$calculate_numbers = isset( $this->settings['calculate_document_numbers'] ) && $this->settings['calculate_document_numbers'];
		
		if ( $calculate_numbers ) {
			// Use database query to calculate the next number
			global $wpdb;
			
			$prefix = isset( $this->settings['invoice_number_prefix'] ) ? $this->settings['invoice_number_prefix'] : 'INV-';
			$suffix = isset( $this->settings['invoice_number_suffix'] ) ? $this->settings['invoice_number_suffix'] : '';
			
			// Get the highest existing invoice number
			$cache_key = 'shopglut_highest_invoice_' . md5( $prefix );
			$results = wp_cache_get( $cache_key );
			
			if ( false === $results ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$results = $wpdb->get_results( $wpdb->prepare( "
					SELECT meta_value 
					FROM {$wpdb->postmeta} 
					WHERE meta_key = '_invoice_number' 
					AND meta_value LIKE %s
					ORDER BY CAST(SUBSTRING(meta_value, %d) AS UNSIGNED) DESC 
					LIMIT 1
				", $prefix . '%', strlen( $prefix ) + 1 ) );
				
				wp_cache_set( $cache_key, $results, '', 300 ); // Cache for 5 minutes
			}
			
			if ( ! empty( $results ) ) {
				$last_number = $results[0]->meta_value;
				// Extract the numeric part
				$numeric_part = preg_replace( '/[^0-9]/', '', str_replace( array( $prefix, $suffix ), '', $last_number ) );
				$next_number = intval( $numeric_part ) + 1;
			} else {
				$next_number = 1;
			}
			
			$padding = isset( $this->settings['invoice_number_padding'] ) ? intval( $this->settings['invoice_number_padding'] ) : 4;
			$padded_number = str_pad( $next_number, $padding, '0', STR_PAD_LEFT );
			
			return $prefix . $padded_number . $suffix;
		} else {
			// Use AUTO_INCREMENT method (existing logic)
			$format = isset( $this->settings['invoice_number_format'] ) ? $this->settings['invoice_number_format'] : 'sequential';
			
			if ( $format === 'order_number' ) {
				$order = wc_get_order( $order_id );
				return $order ? $order->get_order_number() : $order_id;
			}
			
			$prefix = isset( $this->settings['invoice_number_prefix'] ) ? $this->settings['invoice_number_prefix'] : 'INV-';
			$suffix = isset( $this->settings['invoice_number_suffix'] ) ? $this->settings['invoice_number_suffix'] : '';
			$padding = isset( $this->settings['invoice_number_padding'] ) ? intval( $this->settings['invoice_number_padding'] ) : 4;
			
			$padded_number = str_pad( $order_id, $padding, '0', STR_PAD_LEFT );
			
			return $prefix . $padded_number . $suffix;
		}
	}
}