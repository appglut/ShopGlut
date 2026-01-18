<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ShopGlutPdfInvoicesDashboard {
	
	private $settings;
	
	public function __construct() {
		$this->settings = get_option( 'agshopglut_pdf_invoices_options', array() );
		$this->init_hooks();
	}
	
	private function init_hooks() {
		add_action( 'wp_ajax_shopglut_dashboard_stats', array( $this, 'ajax_get_dashboard_stats' ) );
		add_action( 'wp_ajax_shopglut_recent_documents', array( $this, 'ajax_get_recent_documents' ) );
		add_action( 'wp_ajax_shopglut_cleanup_documents', array( $this, 'ajax_cleanup_documents' ) );
	}
	
	public function render_dashboard() {
		wp_enqueue_style( 'shopglut-simple-dashboard', plugin_dir_url( __FILE__ ) . 'assets/simple-dashboard.css', array(), '1.0.0' );
		wp_enqueue_script( 'shopglut-simple-dashboard', plugin_dir_url( __FILE__ ) . 'assets/simple-dashboard.js', array( 'jquery' ), '1.0.0', true );
		
		wp_localize_script( 'shopglut-simple-dashboard', 'shopglut_dashboard', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'shopglut_dashboard_nonce' ),
		) );
		
		$stats = $this->get_dashboard_statistics();
		$recent_docs = $this->get_recent_documents();
		
		?>
		<div class="shopglut-simple-dashboard">
			
			<!-- Header -->
			<div class="dashboard-header">
				<h1><?php esc_html_e( 'PDF Documents Dashboard', 'shopglut' ); ?></h1>
				<p><?php esc_html_e( 'Overview of your PDF invoices, packing slips and UBL documents', 'shopglut' ); ?></p>
			</div>

			<!-- Stats Row -->
			<div class="stats-row">
				<div class="stat-box">
					<div class="stat-icon">
						<span class="dashicons dashicons-media-document"></span>
					</div>
					<div class="stat-number"><?php echo esc_html( $stats['total_invoices'] ); ?></div>
					<div class="stat-label"><?php esc_html_e( 'PDF Invoices', 'shopglut' ); ?></div>
				</div>

				<div class="stat-box">
					<div class="stat-icon">
						<span class="dashicons dashicons-products"></span>
					</div>
					<div class="stat-number"><?php echo esc_html( $stats['total_packing_slips'] ); ?></div>
					<div class="stat-label"><?php esc_html_e( 'Packing Slips', 'shopglut' ); ?></div>
				</div>

				<div class="stat-box">
					<div class="stat-icon">
						<span class="dashicons dashicons-media-code"></span>
					</div>
					<div class="stat-number"><?php echo esc_html( $stats['total_ubl'] ); ?></div>
					<div class="stat-label"><?php esc_html_e( 'UBL Invoices', 'shopglut' ); ?></div>
				</div>

				<div class="stat-box">
					<div class="stat-icon">
						<span class="dashicons dashicons-database-view"></span>
					</div>
					<div class="stat-number"><?php echo esc_html( $stats['storage_used'] ); ?></div>
					<div class="stat-label"><?php esc_html_e( 'Storage Used', 'shopglut' ); ?></div>
				</div>
			</div>

			<!-- Content Row -->
			<div class="content-row">
				
				<!-- Quick Actions -->
				<div class="content-box">
					<h3><?php esc_html_e( 'Quick Actions', 'shopglut' ); ?></h3>
					<div class="actions-list">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_pdf_invoices_settings' ) ); ?>" class="action-link">
							<span class="dashicons dashicons-admin-settings"></span>
							<?php esc_html_e( 'Plugin Settings', 'shopglut' ); ?>
						</a>
						
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=shop_order' ) ); ?>" class="action-link">
							<span class="dashicons dashicons-list-view"></span>
							<?php esc_html_e( 'View Orders', 'shopglut' ); ?>
						</a>
						
						<button type="button" class="action-link" id="cleanup-files">
							<span class="dashicons dashicons-trash"></span>
							<?php esc_html_e( 'Cleanup Old Files', 'shopglut' ); ?>
						</button>
						
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-status' ) ); ?>" class="action-link">
							<span class="dashicons dashicons-admin-tools"></span>
							<?php esc_html_e( 'System Status', 'shopglut' ); ?>
						</a>
					</div>
				</div>

				<!-- System Status -->
				<div class="content-box">
					<h3><?php esc_html_e( 'System Status', 'shopglut' ); ?></h3>
					<div class="status-list">
						<div class="status-item">
							<span class="status-label"><?php esc_html_e( 'PDF Invoices:', 'shopglut' ); ?></span>
							<span class="status-value <?php echo $this->is_feature_enabled( 'enable_pdf_invoices' ) ? 'enabled' : 'disabled'; ?>">
								<?php echo $this->is_feature_enabled( 'enable_pdf_invoices' ) ? esc_html__( 'Enabled', 'shopglut' ) : esc_html__( 'Disabled', 'shopglut' ); ?>
							</span>
						</div>
						
						<div class="status-item">
							<span class="status-label"><?php esc_html_e( 'Packing Slips:', 'shopglut' ); ?></span>
							<span class="status-value <?php echo $this->is_feature_enabled( 'enable_packing_slips' ) ? 'enabled' : 'disabled'; ?>">
								<?php echo $this->is_feature_enabled( 'enable_packing_slips' ) ? esc_html__( 'Enabled', 'shopglut' ) : esc_html__( 'Disabled', 'shopglut' ); ?>
							</span>
						</div>
						
						<div class="status-item">
							<span class="status-label"><?php esc_html_e( 'UBL Invoices:', 'shopglut' ); ?></span>
							<span class="status-value <?php echo $this->is_feature_enabled( 'enable_ubl_invoices' ) ? 'enabled' : 'disabled'; ?>">
								<?php echo $this->is_feature_enabled( 'enable_ubl_invoices' ) ? esc_html__( 'Enabled', 'shopglut' ) : esc_html__( 'Disabled', 'shopglut' ); ?>
							</span>
						</div>
						
						<div class="status-item">
							<span class="status-label"><?php esc_html_e( 'Auto Email:', 'shopglut' ); ?></span>
							<span class="status-value <?php echo $this->is_feature_enabled( 'auto_attach_invoice' ) ? 'enabled' : 'disabled'; ?>">
								<?php echo $this->is_feature_enabled( 'auto_attach_invoice' ) ? esc_html__( 'Enabled', 'shopglut' ) : esc_html__( 'Disabled', 'shopglut' ); ?>
							</span>
						</div>
						
						<div class="status-item">
							<span class="status-label"><?php esc_html_e( 'File Permissions:', 'shopglut' ); ?></span>
							<span class="status-value <?php echo $this->check_file_permissions() ? 'enabled' : 'disabled'; ?>">
								<?php echo $this->check_file_permissions() ? esc_html__( 'OK', 'shopglut' ) : esc_html__( 'Issues', 'shopglut' ); ?>
							</span>
						</div>
					</div>
				</div>
			</div>

			<!-- Current Template Settings -->
			<div class="content-box full-width">
			
				<div class="info-table">
					<div class="info-row">
						<span class="info-label"><?php esc_html_e( 'PDF Invoice Template:', 'shopglut' ); ?></span>
						<span class="info-value template-name"><?php echo esc_html( $this->get_current_template( 'invoice' ) ); ?></span>
					</div>
					
					<div class="info-row">
						<span class="info-label"><?php esc_html_e( 'Packing Slip Template:', 'shopglut' ); ?></span>
						<span class="info-value template-name"><?php echo esc_html( $this->get_current_template( 'packing_slip' ) ); ?></span>
					</div>
					
					<div class="info-row">
						<span class="info-label"><?php esc_html_e( 'UBL Invoice Format:', 'shopglut' ); ?></span>
						<span class="info-value template-name"><?php echo esc_html( $this->get_current_template( 'ubl' ) ); ?></span>
					</div>
					
					<div class="info-row">
						<span class="info-label"><?php esc_html_e( 'Invoice Numbering:', 'shopglut' ); ?></span>
						<span class="info-value"><?php echo esc_html( $this->get_numbering_format() ); ?></span>
					</div>
					
					<div class="info-row">
						<span class="info-label"><?php esc_html_e( 'Default Language:', 'shopglut' ); ?></span>
						<span class="info-value"><?php echo esc_html( $this->get_default_language() ); ?></span>
					</div>
				</div>
			</div>

			<!-- Recent Documents -->
			<div class="content-box full-width">
				<div class="box-header">
					<h3><?php esc_html_e( 'Recent Documents', 'shopglut' ); ?></h3>
					<button type="button" class="refresh-btn" id="refresh-recent">
						<span class="dashicons dashicons-update"></span>
						<?php esc_html_e( 'Refresh', 'shopglut' ); ?>
					</button>
				</div>
				
				<div class="documents-table">
					<?php if ( ! empty( $recent_docs ) ): ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Order', 'shopglut' ); ?></th>
								<th><?php esc_html_e( 'Customer', 'shopglut' ); ?></th>
								<th><?php esc_html_e( 'Date', 'shopglut' ); ?></th>
								<th><?php esc_html_e( 'Documents', 'shopglut' ); ?></th>
								<th><?php esc_html_e( 'Total', 'shopglut' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'shopglut' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $recent_docs as $doc ): ?>
							<tr>
								<td><strong>#<?php echo esc_html( $doc['order_number'] ); ?></strong></td>
								<td><?php echo esc_html( $doc['customer_name'] ); ?></td>
								<td><?php echo esc_html( $doc['date'] ); ?></td>
								<td><?php echo esc_html( $doc['document_types'] ); ?></td>
								<td><?php echo esc_html( $doc['order_total'] ); ?></td>
								<td>
									<a href="<?php echo esc_url( $this->get_document_url( $doc['order_id'], 'invoice' ) ); ?>" class="button button-small" target="_blank">
										<?php esc_html_e( 'View Invoice', 'shopglut' ); ?>
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php else: ?>
					<p class="no-documents"><?php esc_html_e( 'No recent documents found.', 'shopglut' ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<!-- System Information -->
			<div class="content-row">
				<div class="content-box">
					<h3><?php esc_html_e( 'System Information', 'shopglut' ); ?></h3>
					<div class="info-table">
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Plugin Version:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_plugin_version() ); ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'WooCommerce:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_woocommerce_version() ); ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'PHP Version:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo PHP_VERSION; ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Memory Limit:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( ini_get( 'memory_limit' ) ); ?></span>
						</div>
					</div>
				</div>

				<div class="content-box">
					<h3><?php esc_html_e( 'Configuration Summary', 'shopglut' ); ?></h3>
					<div class="info-table">
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Company Name:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_company_name() ); ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Invoice Format:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_invoice_format() ); ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Paper Size:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_paper_size() ); ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><?php esc_html_e( 'Template:', 'shopglut' ); ?></span>
							<span class="info-value"><?php echo esc_html( $this->get_template_name() ); ?></span>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php
	}
	
	public function ajax_cleanup_documents() {
		check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
		
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( -1 );
		}
		
		$result = $this->cleanup_old_documents();
		wp_send_json_success( $result );
	}
	
	private function get_dashboard_statistics() {
		global $wpdb;
		
		// Cache key for total invoices
		$cache_key = 'shopglut_total_invoices';
		$total_invoices = wp_cache_get( $cache_key );
		
		if ( false === $total_invoices ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$total_invoices = $wpdb->get_var( "
				SELECT COUNT(*) 
				FROM {$wpdb->postmeta} 
				WHERE meta_key = '_invoice_generated'
			" );
			wp_cache_set( $cache_key, $total_invoices, '', 300 );
		}
		
		// Cache key for total packing slips
		$cache_key = 'shopglut_total_packing_slips';
		$total_packing_slips = wp_cache_get( $cache_key );
		
		if ( false === $total_packing_slips ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$total_packing_slips = $wpdb->get_var( "
				SELECT COUNT(*) 
				FROM {$wpdb->postmeta} 
				WHERE meta_key = '_packing_slip_generated'
			" );
			wp_cache_set( $cache_key, $total_packing_slips, '', 300 );
		}
		
		// Cache key for total UBL invoices
		$cache_key = 'shopglut_total_ubl';
		$total_ubl = wp_cache_get( $cache_key );
		
		if ( false === $total_ubl ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$total_ubl = $wpdb->get_var( "
				SELECT COUNT(*) 
				FROM {$wpdb->postmeta} 
				WHERE meta_key = '_ubl_invoice_generated'
			" );
			wp_cache_set( $cache_key, $total_ubl, '', 300 );
		}
		
		$storage_info = $this->get_storage_usage();
		
		return array(
			'total_invoices' => number_format( intval( $total_invoices ) ),
			'total_packing_slips' => number_format( intval( $total_packing_slips ) ),
			'total_ubl' => number_format( intval( $total_ubl ) ),
			'storage_used' => $storage_info['formatted_size'],
		);
	}
	
	private function get_recent_documents() {
		global $wpdb;
		
		// Cache key for recent documents
		$cache_key = 'shopglut_recent_documents';
		$documents = wp_cache_get( $cache_key );
		
		if ( false === $documents ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$documents = $wpdb->get_results( "
			SELECT 
				p.ID as order_id,
				p.post_date,
				pm1.meta_value as invoice_generated,
				pm2.meta_value as invoice_number,
				pm3.meta_value as packing_slip_generated
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_invoice_generated'
			LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_invoice_number'
			LEFT JOIN {$wpdb->postmeta} pm3 ON p.ID = pm3.post_id AND pm3.meta_key = '_packing_slip_generated'
			WHERE p.post_type = 'shop_order'
			AND (pm1.meta_value IS NOT NULL OR pm3.meta_value IS NOT NULL)
			ORDER BY p.post_date DESC
			LIMIT 5
		" );
			wp_cache_set( $cache_key, $documents, '', 300 );
		}
		
		$formatted_documents = array();
		foreach ( $documents as $doc ) {
			$order = wc_get_order( $doc->order_id );
			if ( ! $order ) continue;
			
			$document_types = array();
			if ( $doc->invoice_generated ) $document_types[] = 'Invoice';
			if ( $doc->packing_slip_generated ) $document_types[] = 'Packing Slip';
			
			$formatted_documents[] = array(
				'order_id' => $doc->order_id,
				'order_number' => $order->get_order_number(),
				'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
				'date' => date_i18n( get_option( 'date_format' ), strtotime( $doc->post_date ) ),
				'document_types' => implode( ', ', $document_types ),
				'order_total' => $order->get_formatted_order_total(),
			);
		}
		
		return $formatted_documents;
	}
	
	private function get_storage_usage() {
		$upload_dir = wp_upload_dir();
		$directories = array(
			$upload_dir['basedir'] . '/shopglut-invoices/',
			$upload_dir['basedir'] . '/shopglut-packing-slips/',
			$upload_dir['basedir'] . '/shopglut-ubl-invoices/',
		);
		
		$total_size = 0;
		$file_count = 0;
		
		foreach ( $directories as $directory ) {
			if ( ! is_dir( $directory ) ) continue;
			
			$files = glob( $directory . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					$total_size += filesize( $file );
					$file_count++;
				}
			}
		}
		
		return array(
			'total_size' => $total_size,
			'formatted_size' => size_format( $total_size ),
			'file_count' => $file_count,
		);
	}
	
	private function cleanup_old_documents() {
		$cleanup_days = isset( $this->settings['cleanup_days'] ) ? intval( $this->settings['cleanup_days'] ) : 7;
		$cleanup_timestamp = time() - ( $cleanup_days * DAY_IN_SECONDS );
		
		$upload_dir = wp_upload_dir();
		$directories = array(
			$upload_dir['basedir'] . '/shopglut-invoices/',
			$upload_dir['basedir'] . '/shopglut-packing-slips/',
			$upload_dir['basedir'] . '/shopglut-ubl-invoices/',
		);
		
		$deleted_files = 0;
		$freed_space = 0;
		
		foreach ( $directories as $directory ) {
			if ( ! is_dir( $directory ) ) continue;
			
			$files = glob( $directory . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) && filemtime( $file ) < $cleanup_timestamp ) {
					$file_size = filesize( $file );
					if ( wp_delete_file( $file ) ) {
						$deleted_files++;
						$freed_space += $file_size;
					}
				}
			}
		}
		
		return array(
			'deleted_files' => $deleted_files,
			'freed_space' => size_format( $freed_space ),
		);
	}
	
	private function get_document_url( $order_id, $document_type ) {
		$base_url = admin_url( 'admin-ajax.php' );
		$action = 'generate_pdf_' . $document_type;
		$nonce = wp_create_nonce( 'download_' . $document_type . '_' . $order_id );
		return add_query_arg( array(
			'action' => $action,
			'order_id' => $order_id,
			'_wpnonce' => $nonce,
		), $base_url );
	}
	
	private function is_feature_enabled( $feature ) {
		return isset( $this->settings[ $feature ] ) && $this->settings[ $feature ] == 1;
	}
	
	private function check_file_permissions() {
    global $wp_filesystem;

    if ( ! function_exists( 'WP_Filesystem' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    WP_Filesystem();

    $upload_dir = wp_upload_dir();

    // If WP_Filesystem is not available, bail out gracefully
    if ( ! $wp_filesystem || ! method_exists( $wp_filesystem, 'is_writable' ) ) {
        return false;
    }

    return $wp_filesystem->is_writable( $upload_dir['basedir'] );
}

	
	private function get_plugin_version() {
		return '1.0.0';
	}
	
	private function get_woocommerce_version() {
		return defined( 'WC_VERSION' ) ? WC_VERSION : 'N/A';
	}
	
	private function get_company_name() {
		return isset( $this->settings['company_name'] ) ? $this->settings['company_name'] : get_bloginfo( 'name' );
	}
	
	private function get_invoice_format() {
		$format = isset( $this->settings['invoice_number_format'] ) ? $this->settings['invoice_number_format'] : 'sequential';
		switch ( $format ) {
			case 'order_number': return 'Order Number';
			case 'sequential': return 'Sequential';
			case 'custom': return 'Custom Format';
			default: return 'Sequential';
		}
	}
	
	private function get_paper_size() {
		return isset( $this->settings['paper_size'] ) ? $this->settings['paper_size'] : 'A4';
	}
	
	private function get_template_name() {
		$template = isset( $this->settings['invoice_template'] ) ? $this->settings['invoice_template'] : 'default';
		return ucfirst( $template );
	}
	
	private function get_current_template( $type ) {
		switch ( $type ) {
			case 'invoice':
				$template = isset( $this->settings['invoice_template'] ) ? $this->settings['invoice_template'] : 'default';
				return ucfirst( $template ) . ' Template';
			case 'packing_slip':
				$template = isset( $this->settings['packing_slip_template'] ) ? $this->settings['packing_slip_template'] : 'simple';
				return ucfirst( $template ) . ' Template';
			case 'ubl':
				$format = isset( $this->settings['ubl_format'] ) ? $this->settings['ubl_format'] : '2.1';
				return 'UBL ' . $format;
			default:
				return 'Not Set';
		}
	}
	
	private function get_numbering_format() {
		$format = isset( $this->settings['invoice_number_format'] ) ? $this->settings['invoice_number_format'] : 'sequential';
		$prefix = isset( $this->settings['invoice_number_prefix'] ) ? $this->settings['invoice_number_prefix'] : '';
		$suffix = isset( $this->settings['invoice_number_suffix'] ) ? $this->settings['invoice_number_suffix'] : '';
		
		$display = ucfirst( $format );
		if ( $prefix || $suffix ) {
			$display .= ' (' . $prefix . 'XXXX' . $suffix . ')';
		}
		
		return $display;
	}
	
	private function get_default_language() {
		$language = isset( $this->settings['default_language'] ) ? $this->settings['default_language'] : get_locale();
		$languages = array(
			'en_US' => 'English (US)',
			'en_GB' => 'English (UK)',
			'es_ES' => 'Spanish',
			'fr_FR' => 'French',
			'de_DE' => 'German',
			'it_IT' => 'Italian',
			'nl_NL' => 'Dutch',
			'pt_PT' => 'Portuguese'
		);
		
		return isset( $languages[ $language ] ) ? $languages[ $language ] : $language;
	}
}