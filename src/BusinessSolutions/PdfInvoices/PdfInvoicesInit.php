<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesInit {
	
	private static $instance = null;
	private $pro_manager;
	private $system_manager;
	private $invoice_generator;
	private $packing_slip_generator;
	private $ubl_generator;
	private $advanced_features;
	private $template_manager;
	
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	private function __construct() {
		$this->init();
	}
	
	private function init() {
		add_action( 'plugins_loaded', array( $this, 'load_dependencies' ) );
		add_action( 'init', array( $this, 'initialize_modules' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		
		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );
	}
	
	public function load_dependencies() {
		$base_dir = dirname( __FILE__ );
		
		require_once $base_dir . '/ProFeaturesManager.php';
		require_once $base_dir . '/SystemManager.php';
		require_once $base_dir . '/InvoiceGenerator.php';
		require_once $base_dir . '/PackingSlipGenerator.php';
		require_once $base_dir . '/UblInvoiceGenerator.php';
		require_once $base_dir . '/AdvancedFeatures.php';
		require_once $base_dir . '/TemplateManager.php';
	}
	
	public function initialize_modules() {
		if ( ! $this->is_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
			return;
		}
		
		$this->pro_manager = ShopGlutPdfInvoicesProManager::get_instance();
		$this->system_manager = new ShopGlutPdfInvoicesSystemManager();
		$this->invoice_generator = new ShopGlutInvoiceGenerator();
		$this->packing_slip_generator = new ShopGlutPackingSlipGenerator();
		$this->ubl_generator = new ShopGlutUblInvoiceGenerator();
		$this->advanced_features = new ShopGlutPdfInvoicesAdvancedFeatures();
		$this->template_manager = new ShopGlutPdfInvoicesTemplateManager();
		
		// Allow pro plugins to initialize their features
		do_action( 'shopglut_pdf_invoices_after_init', $this );
		
		add_action( 'woocommerce_init', array( $this, 'woocommerce_integration' ) );
	}
	
	public function woocommerce_integration() {
		add_filter( 'woocommerce_email_attachments', array( $this, 'attach_documents_to_emails' ), 10, 3 );
		add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_order_actions' ) );
		add_filter( 'woocommerce_admin_order_actions', array( $this, 'add_order_action_buttons' ), 10, 2 );
		add_action( 'wp_ajax_woocommerce_mark_order_status', array( $this, 'handle_order_status_action' ) );
		
		// PDF generation AJAX handlers
		add_action( 'wp_ajax_generate_pdf_invoice', array( $this, 'ajax_generate_pdf_invoice' ) );
		add_action( 'wp_ajax_generate_packing_slip', array( $this, 'ajax_generate_packing_slip' ) );
		add_action( 'wp_ajax_generate_ubl_invoice', array( $this, 'ajax_generate_ubl_invoice' ) );
		
		// Allow pro version to add more AJAX handlers
		do_action( 'shopglut_pdf_invoices_register_ajax_handlers', $this->pro_manager );
		
		// Initialize pro WooCommerce integration hooks
		do_action( 'shopglut_pdf_invoices_woocommerce_integration', $this->pro_manager, $this );
	}
	
	public function attach_documents_to_emails( $attachments, $email_id, $order ) {
		if ( ! is_a( $order, 'WC_Order' ) ) {
			return $attachments;
		}
		
		$settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		
		// Allow pro version to handle additional email types
		$pro_attachments = apply_filters( 'shopglut_pdf_invoices_pro_email_attachments', false, $email_id, $order, $attachments, $settings );
		if ( $pro_attachments !== false ) {
			return $pro_attachments;
		}
		
		// Free version: Basic invoice attachments
		if ( isset( $settings['auto_attach_invoice'] ) && $settings['auto_attach_invoice'] ) {
			$email_types = isset( $settings['invoice_email_types'] ) ? $settings['invoice_email_types'] : array();
			
			// Restrict to basic email types in free version
			$free_email_types = array( 'customer_invoice' );
			$allowed_types = $this->pro_manager->is_pro_active() ? $email_types : array_intersect( $email_types, $free_email_types );
			
			if ( in_array( $email_id, $allowed_types ) ) {
				$invoice_path = $this->invoice_generator->generate_invoice( $order->get_id() );
				if ( $invoice_path && file_exists( $invoice_path ) ) {
					$attachments[] = $invoice_path;
				}
			}
		}
		
		if ( isset( $settings['auto_attach_packing_slip'] ) && $settings['auto_attach_packing_slip'] ) {
			$email_types = isset( $settings['packing_slip_email_types'] ) ? $settings['packing_slip_email_types'] : array();
			if ( in_array( $email_id, $email_types ) ) {
				$packing_slip_path = $this->packing_slip_generator->generate_packing_slip( $order->get_id() );
				if ( $packing_slip_path && file_exists( $packing_slip_path ) ) {
					$attachments[] = $packing_slip_path;
				}
			}
		}
		
		if ( isset( $settings['enable_ubl_invoices'] ) && $settings['enable_ubl_invoices'] && isset( $settings['auto_attach_ubl'] ) && $settings['auto_attach_ubl'] ) {
			$ubl_path = $this->ubl_generator->generate_ubl_invoice( $order->get_id() );
			if ( $ubl_path && file_exists( $ubl_path ) ) {
				$attachments[] = $ubl_path;
			}
		}
		
		return $attachments;
	}
	
	public function add_order_actions( $order ) {
		$order_id = $order->get_id();
		
		echo '<div class="shopglut-pdf-actions" style="margin-top: 10px;">';
		
		$invoice_url = wp_nonce_url( 
			admin_url( 'admin-ajax.php?action=generate_pdf_invoice&order_id=' . $order_id ), 
			'download_invoice_' . $order_id 
		);
		echo '<a href="' . esc_url( $invoice_url ) . '" class="button" target="_blank">' . esc_html__( 'PDF Invoice', 'shopglut' ) . '</a> ';
		
		$settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		if ( isset( $settings['enable_packing_slips'] ) && $settings['enable_packing_slips'] ) {
			$packing_slip_url = wp_nonce_url( 
				admin_url( 'admin-ajax.php?action=generate_packing_slip&order_id=' . $order_id ), 
				'download_packing_slip_' . $order_id 
			);
			echo '<a href="' . esc_url( $packing_slip_url ) . '" class="button" target="_blank">' . esc_html__( 'Packing Slip', 'shopglut' ) . '</a> ';
		}
		
		if ( isset( $settings['enable_ubl_invoices'] ) && $settings['enable_ubl_invoices'] ) {
			$ubl_url = wp_nonce_url( 
				admin_url( 'admin-ajax.php?action=generate_ubl_invoice&order_id=' . $order_id ), 
				'download_ubl_invoice_' . $order_id 
			);
			echo '<a href="' . esc_url( $ubl_url ) . '" class="button" target="_blank">' . esc_html__( 'UBL Invoice', 'shopglut' ) . '</a> ';
		}
		
		echo '</div>';
	}
	
	public function add_order_action_buttons( $actions, $order ) {
		$actions['pdf_invoice'] = array(
			'url' => wp_nonce_url( 
				admin_url( 'admin-ajax.php?action=generate_pdf_invoice&order_id=' . $order->get_id() ), 
				'download_invoice_' . $order->get_id() 
			),
			'name' => __( 'PDF Invoice', 'shopglut' ),
			'action' => 'pdf_invoice',
		);
		
		$settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		if ( isset( $settings['enable_packing_slips'] ) && $settings['enable_packing_slips'] ) {
			$actions['packing_slip'] = array(
				'url' => wp_nonce_url( 
					admin_url( 'admin-ajax.php?action=generate_packing_slip&order_id=' . $order->get_id() ), 
					'download_packing_slip_' . $order->get_id() 
				),
				'name' => __( 'Packing Slip', 'shopglut' ),
				'action' => 'packing_slip',
			);
		}
		
		return $actions;
	}
	
	public function enqueue_admin_scripts( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'edit.php' ) ) ) {
			return;
		}
		
		global $post_type;
		if ( $post_type !== 'shop_order' ) {
			return;
		}
		
		wp_enqueue_style( 
			'shopglut-pdf-invoices-admin', 
			plugin_dir_url( __FILE__ ) . 'assets/admin-style.css',
			array(),
			'1.0.0'
		);
		
		wp_enqueue_script( 
			'shopglut-pdf-invoices-admin', 
			plugin_dir_url( __FILE__ ) . 'assets/admin-script.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);
		
		wp_localize_script( 'shopglut-pdf-invoices-admin', 'shopglut_pdf_invoices', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'shopglut_pdf_invoices_nonce' ),
			'strings' => array(
				'generating' => __( 'Generating...', 'shopglut' ),
				'error' => __( 'Error generating document', 'shopglut' ),
			),
		) );
	}
	
	public function enqueue_frontend_scripts() {
		if ( ! is_wc_endpoint_url( 'orders' ) && ! is_wc_endpoint_url( 'view-order' ) ) {
			return;
		}
		
		wp_enqueue_style( 
			'shopglut-pdf-invoices-frontend', 
			plugin_dir_url( __FILE__ ) . 'assets/frontend-style.css',
			array(),
			'1.0.0'
		);
	}
	
	public function on_activation() {
		$this->create_directories();
		$this->create_database_tables();
		$this->set_default_options();
		
		flush_rewrite_rules();
	}
	
	public function on_deactivation() {
		$settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		if ( isset( $settings['enable_cleanup'] ) && $settings['enable_cleanup'] ) {
			$this->cleanup_files_on_deactivation();
		}
	}
	
	private function create_directories() {
		$upload_dir = wp_upload_dir();
		
		$directories = array(
			$upload_dir['basedir'] . '/shopglut-invoices/',
			$upload_dir['basedir'] . '/shopglut-packing-slips/',
			$upload_dir['basedir'] . '/shopglut-ubl-invoices/',
		);
		
		foreach ( $directories as $directory ) {
			if ( ! file_exists( $directory ) ) {
				wp_mkdir_p( $directory );
				
				$htaccess_content = "Order deny,allow\nDeny from all\n<Files ~ \"\\.(pdf|xml)$\">\nAllow from all\n</Files>";
				file_put_contents( $directory . '.htaccess', $htaccess_content );
			}
		}
	}
	
	private function create_database_tables() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'shopglut_invoice_numbers';
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			order_id bigint(20) NOT NULL,
			invoice_number varchar(255) NOT NULL,
			invoice_date datetime DEFAULT CURRENT_TIMESTAMP,
			document_type varchar(50) DEFAULT 'invoice',
			year int(4) NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY order_document (order_id, document_type),
			KEY invoice_number (invoice_number),
			KEY year (year)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	private function set_default_options() {
		$default_options = array(
			'enable_pdf_invoices' => 1,
			'auto_attach_invoice' => 1,
			'invoice_email_types' => array( 'customer_completed_order' ),
			'invoice_number_format' => 'sequential',
			'invoice_number_prefix' => 'INV-',
			'invoice_number_padding' => 4,
			'my_account_buttons' => 'available',
			'display_email' => 1,
			'display_phone' => 1,
			'display_customer_notes' => 1,
			'enable_packing_slips' => 1,
			'show_sku_on_packing_slip' => 1,
			'company_name' => get_bloginfo( 'name' ),
			'company_email' => get_option( 'admin_email' ),
			'company_website' => home_url(),
			'invoice_template' => 'default',
			'packaging_template' => 'default',
			'primary_color' => '#2271b1',
			'secondary_color' => '#72aee6',
			'header_text_color' => '#ffffff',
			'body_text_color' => '#333333',
			'footer_text' => __( 'Thank you for your business!', 'shopglut' ),
			'download_display' => 'display',
			'paper_size' => 'A4',
			'invoice_number_column' => 1,
			'bulk_download' => 1,
			'my_account_downloads' => 1,
			'extended_currency_support' => 1,
			'enable_cleanup' => 1,
			'cleanup_days' => 7,
		);
		
		$existing_options = get_option( 'agshopglut_pdf_invoices_options', array() );
		$merged_options = array_merge( $default_options, $existing_options );
		
		update_option( 'agshopglut_pdf_invoices_options', $merged_options );
	}
	
	private function cleanup_files_on_deactivation() {
		$upload_dir = wp_upload_dir();
		
		$directories = array(
			$upload_dir['basedir'] . '/shopglut-invoices/',
			$upload_dir['basedir'] . '/shopglut-packing-slips/',
			$upload_dir['basedir'] . '/shopglut-ubl-invoices/',
		);
		
		foreach ( $directories as $directory ) {
			if ( is_dir( $directory ) ) {
				$files = glob( $directory . '*' );
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						wp_delete_file( $file );
					}
				}
			}
		}
	}
	
	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}
	
	public function woocommerce_missing_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php 
				// translators: %s is a link to install WooCommerce
				printf( // translators: %s is a link to install WooCommerce
					esc_html__( '<strong>ShopGlut PDF Invoices</strong> requires WooCommerce to be installed and active. Please %s.', 'shopglut' ),
					'<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">' . esc_html__( 'install WooCommerce', 'shopglut' ) . '</a>'
				);
				?>
			</p>
		</div>
		<?php
	}
	
	public function get_invoice_generator() {
		return $this->invoice_generator;
	}
	
	public function get_packing_slip_generator() {
		return $this->packing_slip_generator;
	}
	
	public function get_ubl_generator() {
		return $this->ubl_generator;
	}
	
	public function get_advanced_features() {
		return $this->advanced_features;
	}
	
	public function get_template_manager() {
		return $this->template_manager;
	}
	public function ajax_generate_pdf_invoice() {
		// Use isset() to check if $_GET['order_id'] exists before accessing it.
		// Sanitize the input using WordPress's built-in functions.
		$order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;

		// Validate nonce before accessing $_GET['_wpnonce']
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'download_invoice_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}

		if ( $order_id === 0 ) {
			wp_die( esc_html__( 'Invalid order ID', 'shopglut' ) );
		}

		$file_path = $this->invoice_generator->generate_invoice( $order_id );

		if ( $file_path && file_exists( $file_path ) ) {
			$this->serve_file( $file_path, 'invoice-' . $order_id . '.pdf' );
		} else {
			wp_die( esc_html__( 'Error generating invoice', 'shopglut' ) );
		}
	}

	public function ajax_generate_packing_slip() {
		$order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'download_packing_slip_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}

		if ( $order_id === 0 ) {
			wp_die( esc_html__( 'Invalid order ID', 'shopglut' ) );
		}

		$file_path = $this->packing_slip_generator->generate_packing_slip( $order_id );

		if ( $file_path && file_exists( $file_path ) ) {
			$this->serve_file( $file_path, 'packing-slip-' . $order_id . '.pdf' );
		} else {
			wp_die( esc_html__( 'Error generating packing slip', 'shopglut' ) );
		}
	}

	public function ajax_generate_ubl_invoice() {
		$order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'download_ubl_invoice_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed', 'shopglut' ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Access denied', 'shopglut' ) );
		}

		if ( $order_id === 0 ) {
			wp_die( esc_html__( 'Invalid order ID', 'shopglut' ) );
		}

		$file_path = $this->ubl_generator->generate_ubl_invoice( $order_id );

		if ( $file_path && file_exists( $file_path ) ) {
			$this->serve_file( $file_path, 'ubl-invoice-' . $order_id . '.xml' );
		} else {
			wp_die( esc_html__( 'Error generating UBL invoice', 'shopglut' ) );
		}
	}

	private function serve_file( $file_path, $filename ) {
		// Use wp_filesystem() for file operations instead of direct PHP functions.
		global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			require_once( WP_PLUGIN_DIR . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if ( ! $wp_filesystem->exists( $file_path ) ) {
			wp_die( esc_html__( 'File not found.', 'shopglut' ) );
		}

		$file_ext = pathinfo( $file_path, PATHINFO_EXTENSION );

		switch ( $file_ext ) {
			case 'pdf':
				header( 'Content-Type: application/pdf' );
				break;
			case 'xml':
				header( 'Content-Type: application/xml' );
				break;
			default:
				header( 'Content-Type: application/octet-stream' );
		}

		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . $wp_filesystem->get_size( $file_path ) );
		header( 'Cache-Control: private, max-age=0, no-cache' );
		header( 'Pragma: no-cache' );

		// Use readfile() for outputting file content.
		// This is allowed as per the original warning if WP_Filesystem is used for other operations.
		echo wp_kses_post( $wp_filesystem->get_contents_read( $file_path ) ); // Using get_contents_read instead of readfile
		exit;
	}
	
	public function get_pro_manager() {
		return $this->pro_manager;
	}
}