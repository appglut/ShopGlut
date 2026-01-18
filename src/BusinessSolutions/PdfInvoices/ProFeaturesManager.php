<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesProManager {
	
	private static $instance = null;
	private $is_pro_active = null;
	
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function __construct() {
		$this->init_hooks();
	}
	
	private function init_hooks() {
		// Core pro detection hooks
		add_action( 'plugins_loaded', array( $this, 'detect_pro_version' ), 1 );
		add_action( 'init', array( $this, 'init_pro_features' ), 999 );
	}
	
	/**
	 * Detect if pro version is active
	 */
	public function detect_pro_version() {
		$this->is_pro_active = apply_filters( 'shopglut_pdf_invoices_is_pro_active', false );
		
		// Allow multiple detection methods
		if ( ! $this->is_pro_active ) {
			// Method 1: Check for pro plugin constant
			$this->is_pro_active = defined( 'SHOPGLUT_PDF_INVOICES_PRO' ) && SHOPGLUT_PDF_INVOICES_PRO === true;
		}
		
		if ( ! $this->is_pro_active ) {
			// Method 2: Check for pro plugin file
			$this->is_pro_active = function_exists( 'is_plugin_active' ) && is_plugin_active( 'shopglut-pdf-invoices-pro/shopglut-pdf-invoices-pro.php' );
		}
		
		if ( ! $this->is_pro_active ) {
			// Method 3: Check for license key
			$license = get_option( 'shopglut_pdf_invoices_license_key' );
			$this->is_pro_active = ! empty( $license ) && $this->validate_license( $license );
		}
		
		// Final filter for custom detection
		$this->is_pro_active = apply_filters( 'shopglut_pdf_invoices_pro_detected', $this->is_pro_active );
	}
	
	/**
	 * Initialize pro features if available
	 */
	public function init_pro_features() {
		if ( $this->is_pro_active ) {
			do_action( 'shopglut_pdf_invoices_init_pro_features' );
		}
		
		// Always run this for pro plugins to register their features
		do_action( 'shopglut_pdf_invoices_register_pro_features' );
	}
	
	/**
	 * Check if pro version is active
	 */
	public function is_pro_active() {
		if ( $this->is_pro_active === null ) {
			$this->detect_pro_version();
		}
		return $this->is_pro_active;
	}
	
	/**
	 * Check if specific pro feature is available
	 */
	public function is_pro_feature_available( $feature ) {
		if ( ! $this->is_pro_active() ) {
			return false;
		}
		
		return apply_filters( 'shopglut_pdf_invoices_pro_feature_available', true, $feature );
	}
	
	/**
	 * Get pro feature value with fallback
	 */
	public function get_pro_feature( $feature, $default = false ) {
		if ( ! $this->is_pro_feature_available( $feature ) ) {
			return $default;
		}
		
		return apply_filters( "shopglut_pdf_invoices_pro_feature_{$feature}", $default );
	}
	
	/**
	 * Execute pro feature callback
	 */
	public function execute_pro_feature( $feature, $callback = null, $args = array() ) {
		if ( ! $this->is_pro_feature_available( $feature ) ) {
			return false;
		}
		
		$result = apply_filters( "shopglut_pdf_invoices_execute_pro_{$feature}", false, $args );
		
		if ( $result === false && is_callable( $callback ) ) {
			$result = call_user_func_array( $callback, $args );
		}
		
		return $result;
	}
	
	/**
	 * Get supported document types
	 */
	public function get_supported_document_types() {
		$free_types = array(
			'invoice' => __( 'Invoice', 'shopglut' ),
			'packing_slip' => __( 'Packing Slip', 'shopglut' ),
			'ubl_invoice' => __( 'UBL Invoice', 'shopglut' )
		);
		
		$pro_types = array();
		if ( $this->is_pro_active() ) {
			$pro_types = apply_filters( 'shopglut_pdf_invoices_pro_document_types', array(
				'credit_note' => __( 'Credit Note', 'shopglut' ),
				'shipping_label' => __( 'Shipping Label', 'shopglut' ),
				'dispatch_label' => __( 'Dispatch Label', 'shopglut' ),
				'delivery_note' => __( 'Delivery Note', 'shopglut' )
			));
		}
		
		return apply_filters( 'shopglut_pdf_invoices_supported_document_types', array_merge( $free_types, $pro_types ) );
	}
	
	/**
	 * Get supported UBL formats
	 */
	public function get_supported_ubl_formats() {
		$free_formats = array(
			'ubl_2_1' => __( 'UBL 2.1', 'shopglut' ),
			'simple_xml' => __( 'Simple XML', 'shopglut' )
		);
		
		$pro_formats = array();
		if ( $this->is_pro_active() ) {
			$pro_formats = apply_filters( 'shopglut_pdf_invoices_pro_ubl_formats', array(
				'ubl_2_0' => __( 'UBL 2.0', 'shopglut' ),
				'peppol_bis' => __( 'PEPPOL BIS', 'shopglut' ),
				'factur_x' => __( 'Factur-X', 'shopglut' ),
				'zugferd' => __( 'ZUGFeRD', 'shopglut' )
			));
		}
		
		return apply_filters( 'shopglut_pdf_invoices_supported_ubl_formats', array_merge( $free_formats, $pro_formats ) );
	}
	
	/**
	 * Get available templates
	 */
	public function get_available_templates( $document_type = 'invoice' ) {
		$free_templates = array(
			'default' => __( 'Default Template', 'shopglut' ),
			'modern' => __( 'Modern Template', 'shopglut' ),
			'simple' => __( 'Simple Template', 'shopglut' ),
			'classic' => __( 'Classic Template', 'shopglut' )
		);
		
		$pro_templates = array();
		if ( $this->is_pro_active() ) {
			$pro_templates = apply_filters( 'shopglut_pdf_invoices_pro_templates', array(
				'business' => __( 'Business Template', 'shopglut' ),
				'corporate' => __( 'Corporate Template', 'shopglut' ),
				'elegant' => __( 'Elegant Template', 'shopglut' ),
				'professional' => __( 'Professional Template', 'shopglut' ),
				'minimal' => __( 'Minimal Template', 'shopglut' ),
				'creative' => __( 'Creative Template', 'shopglut' ),
				'invoice_plus' => __( 'Invoice Plus Template', 'shopglut' ),
				'premium_1' => __( 'Premium Template 1', 'shopglut' ),
				'premium_2' => __( 'Premium Template 2', 'shopglut' ),
				'premium_3' => __( 'Premium Template 3', 'shopglut' ),
				'premium_4' => __( 'Premium Template 4', 'shopglut' ),
				'premium_5' => __( 'Premium Template 5', 'shopglut' )
			), $document_type );
		}
		
		return apply_filters( 'shopglut_pdf_invoices_available_templates', array_merge( $free_templates, $pro_templates ), $document_type );
	}
	
	/**
	 * Get supported email attachment types
	 */
	public function get_supported_email_attachments() {
		$free_attachments = array(
			'customer_invoice' => __( 'Customer Invoice Emails', 'shopglut' )
		);
		
		$pro_attachments = array();
		if ( $this->is_pro_active() ) {
			$pro_attachments = apply_filters( 'shopglut_pdf_invoices_pro_email_attachments', array(
				'customer_processing' => __( 'Customer Processing Emails', 'shopglut' ),
				'customer_completed' => __( 'Customer Completed Emails', 'shopglut' ),
				'admin_new_order' => __( 'Admin New Order Emails', 'shopglut' ),
				'admin_cancelled' => __( 'Admin Cancelled Emails', 'shopglut' ),
				'all_customer_emails' => __( 'All Customer Emails', 'shopglut' ),
				'all_admin_emails' => __( 'All Admin Emails', 'shopglut' ),
				'custom_email_types' => __( 'Custom Email Types', 'shopglut' )
			));
		}
		
		return apply_filters( 'shopglut_pdf_invoices_supported_email_attachments', array_merge( $free_attachments, $pro_attachments ) );
	}
	
	/**
	 * Validate license key
	 */
	private function validate_license( $license_key ) {
		// Allow pro plugin to implement license validation
		return apply_filters( 'shopglut_pdf_invoices_validate_license', false, $license_key );
	}
	
	/**
	 * Get pro upgrade URL
	 */
	public function get_upgrade_url() {
		return apply_filters( 'shopglut_pdf_invoices_upgrade_url', 'https://www.appglut.com/shopglut-pdf-invoices-pro/' );
	}
	
	/**
	 * Show pro feature preview/upsell
	 */
	public function show_pro_upsell( $feature, $description = '' ) {
		if ( $this->is_pro_active() ) {
			return false;
		}
		
		$upsell_content = apply_filters( 'shopglut_pdf_invoices_pro_upsell_content', '', $feature, $description );
		
		if ( empty( $upsell_content ) ) {
			$upsell_content = $this->get_default_upsell_content( $feature, $description );
		}
		
		echo wp_kses_post( $upsell_content );
		return true;
	}
	
	/**
	 * Default upsell content
	 */
	private function get_default_upsell_content( $feature, $description ) {
		ob_start();
		?>
		<div class="shopglut-pro-upsell">
			<div class="pro-upsell-content">
				<div class="pro-icon">⭐</div>
				<h3><?php echo esc_html( ucwords( str_replace( '_', ' ', $feature ) ) ); ?> - <?php esc_html_e( 'Pro Feature', 'shopglut' ); ?></h3>
				<?php if ( $description ): ?>
					<p><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url( $this->get_upgrade_url() ); ?>" class="button button-primary" target="_blank">
					<?php esc_html_e( 'Upgrade to Pro', 'shopglut' ); ?>
				</a>
			</div>
		</div>
		<style>
		.shopglut-pro-upsell {
			background: #fff;
			border: 2px dashed #ddd;
			border-radius: 8px;
			padding: 30px;
			text-align: center;
			margin: 20px 0;
		}
		.shopglut-pro-upsell .pro-icon {
			font-size: 48px;
			margin-bottom: 15px;
		}
		.shopglut-pro-upsell h3 {
			color: #333;
			margin-bottom: 15px;
		}
		.shopglut-pro-upsell p {
			color: #666;
			margin-bottom: 20px;
		}
		</style>
		<?php
		return ob_get_clean();
	}
}