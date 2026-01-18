<?php
namespace Shopglut\enhancements\wishlist\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

// It's good practice to check if the class exists before declaring it.
if ( ! class_exists( 'dataManage' ) ) {
	/**
	 * Class dataManage
	 *
	 * Handles all AJAX requests for wishlist data management, including fetching,
	 * exporting, importing, and clearing data.
	 */
	class dataManage {

		/**
		 * The single instance of the class.
		 *
		 * @var dataManage|null
		 */
		private static $instance = null;

		/**
		 * Ensures only one instance of the class is loaded.
		 *
		 * @return dataManage An instance of this class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Private constructor to prevent direct instantiation and set up AJAX hooks.
		 */
		private function __construct() {
			// Add AJAX handlers for various data operations.
			add_action( 'wp_ajax_get_all_activity', array( $this, 'ajax_get_all_activity' ) );
			add_action( 'wp_ajax_export_top_products', array( $this, 'ajax_export_top_products' ) );
			add_action( 'wp_ajax_export_all_data', array( $this, 'ajax_export_all_data' ) );
			add_action( 'wp_ajax_export_guest_data', array( $this, 'ajax_export_guest_data' ) );
			add_action( 'wp_ajax_export_registered_data', array( $this, 'ajax_export_registered_data' ) );
			add_action( 'wp_ajax_clear_old_data', array( $this, 'ajax_clear_old_data' ) );
			add_action( 'wp_ajax_preview_import_file', array( $this, 'ajax_preview_import_file' ) );
			add_action( 'wp_ajax_upload_import_file', array( $this, 'ajax_upload_import_file' ) );
			add_action( 'wp_ajax_import_wishlist_data', array( $this, 'ajax_import_wishlist_data' ) );
		}

		/**
		 * Helper function to get the wishlist table name with the WordPress prefix.
		 *
		 * @return string The name of the wishlist table.
		 */
		private function table_shopg_wishlist() {
			global $wpdb;
			return $wpdb->prefix . 'shopg_wishlist';
		}

		/**
		 * AJAX handler to get all wishlist activity.
		 */
		public function ajax_get_all_activity() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();
			$user_type  = isset( $_POST['user_type'] ) ? sanitize_text_field( wp_unslash( $_POST['user_type'] ) ) : 'all';

			$where_clause = '';
			$params       = array();

			if ( 'guest' === $user_type ) {
				$where_clause = 'WHERE (w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s)';
				$params[]     = $wpdb->esc_like( 'guest_' ) . '%';
				$params[]     = '%' . $wpdb->esc_like( 'guest_' ) . '%';
				$params[]     = $wpdb->esc_like( 'Guest' ) . '%';
			} elseif ( 'registered' === $user_type ) {
				$where_clause = 'WHERE NOT (w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s)';
				$params[]     = $wpdb->esc_like( 'guest_' ) . '%';
				$params[]     = '%' . $wpdb->esc_like( 'guest_' ) . '%';
				$params[]     = $wpdb->esc_like( 'Guest' ) . '%';
			}

			$query_params = array_merge(
				array(
					$wpdb->esc_like( 'guest_' ) . '%',
					'%' . $wpdb->esc_like( 'guest_' ) . '%',
					$wpdb->esc_like( 'Guest' ) . '%',
				),
				$params
			);

			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$query = "SELECT w.*,
						CASE
							WHEN w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s
							THEN 'guest'
							ELSE 'registered'
						END as user_type
					 FROM %i w
					 {$where_clause}
					 ORDER BY w.product_added_time DESC
					 LIMIT 200";

			// Prepare the base parameters
			$base_params = array('guest_%', '%guest_%', 'Guest%');
			$all_params = array_merge($base_params, $params);

			$activities = $wpdb->get_results(
				$wpdb->prepare( $query, $table_name, ...$all_params ));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery

			ob_start();
			?>
			<table class="dashboard-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'User', 'shopglut' ); ?></th>
						<th><?php esc_html_e( 'Type', 'shopglut' ); ?></th>
						<th><?php esc_html_e( 'Product', 'shopglut' ); ?></th>
						<th><?php esc_html_e( 'Action', 'shopglut' ); ?></th>
						<th><?php esc_html_e( 'Date', 'shopglut' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( ! empty( $activities ) ) {
						foreach ( $activities as $activity ) {
							$product_ids = explode( ',', (string) $activity->product_ids );
							foreach ( $product_ids as $product_id ) {
								if ( empty( $product_id ) ) {
									continue;
								}
								$product = wc_get_product( $product_id );
								if ( ! $product ) {
									continue;
								}
								?>
								<tr>
									<td><div class="user-info"><strong><?php echo esc_html( $activity->username ); ?></strong><span><?php echo esc_html( $activity->useremail ); ?></span></div></td>
									<td><span class="user-type-badge <?php echo esc_attr( $activity->user_type ); ?>"><?php echo 'guest' === $activity->user_type ? esc_html__( 'Guest', 'shopglut' ) : esc_html__( 'Registered', 'shopglut' ); ?></span></td>
									<td><div class="product-info"><strong><?php echo esc_html( $product->get_name() ); ?></strong><span><?php echo wp_kses_post( $product->get_price_html() ); ?></span></div></td>
									<td><span class="action-badge added"><?php esc_html_e( 'Added to Wishlist', 'shopglut' ); ?></span></td>
									<td><?php echo esc_html( wp_date( 'M j, Y g:i A', strtotime( $activity->product_added_time ) ) ); ?></td>
								</tr>
								<?php
							}
						}
					} else {
						?>
						<tr>
							<td colspan="5"><?php esc_html_e( 'No activity found.', 'shopglut' ); ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<?php
			$html = ob_get_clean();

			wp_send_json_success( array( 'html' => $html ) );
		}


		/**
		 * AJAX handler to export top products to a CSV file.
		 */
		public function ajax_export_top_products() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = "SELECT 
						SUBSTRING_INDEX(SUBSTRING_INDEX(w.product_ids, ',', numbers.n), ',', -1) as product_id,
						COUNT(*) as wishlist_count,
						SUM(CASE WHEN w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s THEN 1 ELSE 0 END) as guest_count,
						SUM(CASE WHEN NOT (w.wish_user_id LIKE %s OR w.useremail LIKE %s OR w.username LIKE %s) THEN 1 ELSE 0 END) as registered_count
					 FROM %i w
					 JOIN (
						SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
						UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
					 ) numbers ON CHAR_LENGTH(w.product_ids) - CHAR_LENGTH(REPLACE(w.product_ids, ',', '')) >= numbers.n - 1
					 WHERE w.product_ids != '' 
					 AND SUBSTRING_INDEX(SUBSTRING_INDEX(w.product_ids, ',', numbers.n), ',', -1) != ''
					 GROUP BY product_id
					 ORDER BY wishlist_count DESC";
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$top_products = $wpdb->get_results(
				$wpdb->prepare(
					$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
					$wpdb->esc_like( 'guest_' ) . '%',
					'%' . $wpdb->esc_like( 'guest_' ) . '%',
					$wpdb->esc_like( 'Guest' ) . '%',
					$wpdb->esc_like( 'guest_' ) . '%',
					'%' . $wpdb->esc_like( 'guest_' ) . '%',
					$wpdb->esc_like( 'Guest' ) . '%'
				)
			);

			$csv_content = "Product ID,Product Name,Times Wishlisted,By Guests,By Registered,Stock Status,Price\n";

			foreach ( $top_products as $product_data ) {
				$product = wc_get_product( $product_data->product_id );
				if ( ! $product ) {
					continue;
				}

				$csv_content .= sprintf(
					"%s,%s,%s,%s,%s,%s,%s\n",
					$this->escape_csv_field( $product_data->product_id ),
					$this->escape_csv_field( $product->get_name() ),
					$this->escape_csv_field( $product_data->wishlist_count ),
					$this->escape_csv_field( $product_data->guest_count ),
					$this->escape_csv_field( $product_data->registered_count ),
					$this->escape_csv_field( $product->get_stock_status() ),
					$this->escape_csv_field( $product->get_price() )
				);
			}

			$filename = 'shopglut-top-products-' . wp_date( 'Y-m-d' ) . '.csv';

			wp_send_json_success(
				array(
					'filename' => $filename,
					'content'  => $csv_content,
				)
			);
		}


		/**
		 * AJAX handler to export all wishlist data to a CSV file.
		 */
		public function ajax_export_all_data() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();

			try {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$all_data = $wpdb->get_results( "SELECT * FROM %i ORDER BY product_added_time DESC" );

				if ( empty( $all_data ) ) {
					wp_send_json_error( array( 'message' => 'No data found to export.' ) );
					return;
				}

				$csv_content = "id,wish_user_id,username,useremail,product_ids,product_meta,wishlist_notifications,product_added_time,product_individual_dates,share_data\n";

				foreach ( $all_data as $row ) {
					$csv_content .= sprintf(
						"%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
						$this->escape_csv_field( $row->id ),
						$this->escape_csv_field( $row->wish_user_id ),
						$this->escape_csv_field( $row->username ),
						$this->escape_csv_field( $row->useremail ),
						$this->escape_csv_field( $this->extract_product_ids( $row->product_ids ) ),
						$this->escape_csv_field( $row->product_meta ),
						$this->escape_csv_field( $row->wishlist_notifications ),
						$this->escape_csv_field( $row->product_added_time ),
						$this->escape_csv_field( $row->product_individual_dates ),
						$this->escape_csv_field( $row->share_data )
					);
				}

				$filename = 'shopglut-wishlist-export-' . wp_date( 'Y-m-d-H-i-s' ) . '.csv';

				wp_send_json_success(
					array(
						'filename'      => $filename,
						'content'       => $csv_content,
						'total_records' => count( $all_data ),
					)
				);

			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Export failed: ' . $e->getMessage() ) );
			}
		}

		/**
		 * Extracts product IDs from a potentially JSON-encoded string.
		 *
		 * @param string|null $product_ids The product ID string.
		 * @return string Comma-separated product IDs.
		 */
		private function extract_product_ids( $product_ids ) {
			if ( is_string( $product_ids ) && strpos( $product_ids, '{' ) !== false ) {
				$decoded = json_decode( $product_ids, true );
				if ( is_array( $decoded ) ) {
					return implode( ',', array_keys( $decoded ) );
				}
			}
			return (string) $product_ids;
		}

		/**
		 * Helper function to properly escape a value for CSV output.
		 *
		 * @param mixed $value The value to escape.
		 * @return string The escaped value.
		 */
		private function escape_csv_field( $value ) {
			if ( null === $value ) {
				return '';
			}
			$value = (string) $value;
			if ( strpos( $value, ',' ) !== false || strpos( $value, "\n" ) !== false || strpos( $value, '"' ) !== false ) {
				$value = '"' . str_replace( '"', '""', $value ) . '"';
			}
			return $value;
		}


		/**
		 * AJAX handler to export guest wishlist data.
		 */
		public function ajax_export_guest_data() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = "SELECT * FROM %i 
					 WHERE wish_user_id LIKE %s OR useremail LIKE %s OR username LIKE %s
					 ORDER BY product_added_time DESC";
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$guest_data = $wpdb->get_results(
				$wpdb->prepare(
					$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
					$wpdb->esc_like( 'guest_' ) . '%',
					'%' . $wpdb->esc_like( 'guest_' ) . '%',
					$wpdb->esc_like( 'Guest' ) . '%'
				)
			);

			$csv_content = "ID,User ID,Username,Email,Product IDs,Added Time\n";

			foreach ( $guest_data as $row ) {
				$csv_content .= sprintf(
					"%s,%s,%s,%s,%s,%s\n",
					$this->escape_csv_field( $row->id ),
					$this->escape_csv_field( $row->wish_user_id ),
					$this->escape_csv_field( $row->username ),
					$this->escape_csv_field( $row->useremail ),
					$this->escape_csv_field( $row->product_ids ),
					$this->escape_csv_field( $row->product_added_time )
				);
			}

			$filename = 'shopglut-guest-data-' . wp_date( 'Y-m-d' ) . '.csv';

			wp_send_json_success(
				array(
					'filename' => $filename,
					'content'  => $csv_content,
				)
			);
		}


		/**
		 * AJAX handler to export registered users' wishlist data.
		 */
		public function ajax_export_registered_data() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = "SELECT * FROM %i 
					 WHERE NOT (wish_user_id LIKE %s OR useremail LIKE %s OR username LIKE %s)
					 ORDER BY product_added_time DESC";
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$registered_data = $wpdb->get_results(
				$wpdb->prepare(
					$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
					$wpdb->esc_like( 'guest_' ) . '%',
					'%' . $wpdb->esc_like( 'guest_' ) . '%',
					$wpdb->esc_like( 'Guest' ) . '%'
				)
			);

			$csv_content = "ID,User ID,Username,Email,Product IDs,Added Time\n";

			foreach ( $registered_data as $row ) {
				$csv_content .= sprintf(
					"%s,%s,%s,%s,%s,%s\n",
					$this->escape_csv_field( $row->id ),
					$this->escape_csv_field( $row->wish_user_id ),
					$this->escape_csv_field( $row->username ),
					$this->escape_csv_field( $row->useremail ),
					$this->escape_csv_field( $row->product_ids ),
					$this->escape_csv_field( $row->product_added_time )
				);
			}

			$filename = 'shopglut-registered-data-' . wp_date( 'Y-m-d' ) . '.csv';

			wp_send_json_success(
				array(
					'filename' => $filename,
					'content'  => $csv_content,
				)
			);
		}


		/**
		 * AJAX handler to clear old wishlist data based on a specified number of days.
		 */
		public function ajax_clear_old_data() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			global $wpdb;
			$table_name = $this->table_shopg_wishlist();
			$days_old   = isset( $_POST['days_old'] ) ? absint( $_POST['days_old'] ) : 0;

			if ( $days_old < 1 ) {
				wp_send_json_error( array( 'message' => 'Invalid number of days' ) );
			}

			try {
				$cutoff_date = gmdate( 'Y-m-d H:i:s', strtotime( '-' . $days_old . ' days', current_time( 'timestamp' ) ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$query = "DELETE FROM %i WHERE product_added_time < %s AND product_added_time IS NOT NULL";
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$deleted_count = $wpdb->query(
					$wpdb->prepare( $query, $cutoff_date ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				);

				if ( false === $deleted_count ) {
					wp_send_json_error(
						array(
							'message' => 'Database error: ' . $wpdb->last_error,
						)
					);
				}

				wp_send_json_success(
					array(
						'deleted_count' => $deleted_count,
						'cutoff_date'   => $cutoff_date,
						'message'       => sprintf(
							// translators: %1$d: Number of deleted entries, %2$d: Number of days.
							esc_html__( 'Deleted %1$d old wishlist entries older than %2$d days.', 'shopglut' ),
							$deleted_count,
							$days_old
						),
					)
				);

			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Error: ' . $e->getMessage() ) );
			}
		}

		/**
		 * AJAX handler to preview an uploaded import file.
		 */
		public function ajax_preview_import_file() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! isset( $_FILES['file']['tmp_name'] ) || empty( $_FILES['file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['file']['tmp_name'] ) ) {
				wp_send_json_error( array( 'message' => 'No file uploaded or invalid file.' ) );
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$file     = $_FILES['file'];
			$file_ext = strtolower( pathinfo( sanitize_file_name( $file['name'] ), PATHINFO_EXTENSION ) );

			if ( 'csv' !== $file_ext ) {
				wp_send_json_error( array( 'message' => 'Only CSV files are supported.' ) );
			}

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
			global $wp_filesystem;

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$file_content = $wp_filesystem->get_contents( $file['tmp_name'] );

			if ( false === $file_content ) {
				wp_send_json_error( array( 'message' => 'Could not read file contents.' ) );
			}

			if ( ! session_id() ) {
				session_start();
			}

			$lines        = explode( "\n", $file_content );
			$headers      = str_getcsv( array_shift( $lines ) );
			$preview_data = array();

			for ( $i = 0; $i < min( 5, count( $lines ) ); $i++ ) {
				if ( trim( $lines[ $i ] ) ) {
					$preview_data[] = str_getcsv( $lines[ $i ] );
				}
			}

			ob_start();
			?>
			<table class="dashboard-table">
				<thead><tr>
				<?php foreach ( $headers as $header ) : ?>
					<th><?php echo esc_html( $header ); ?></th>
				<?php endforeach; ?>
				</tr></thead>
				<tbody>
				<?php foreach ( $preview_data as $row ) : ?>
					<tr>
					<?php foreach ( $row as $cell ) : ?>
						<td><?php echo esc_html( $cell ); ?></td>
					<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php
			$preview_html = ob_get_clean();

			$db_fields = array(
				'wish_user_id' => 'User ID',
				'username'     => 'Username',
				'useremail'    => 'Email',
				'product_ids'  => 'Product IDs',
			);

			ob_start();
			?>
			<div class="field-mapping-container">
				<?php foreach ( $db_fields as $db_field => $label ) : ?>
				<div class="mapping-row">
					<label><?php echo esc_html( $label ); ?>:</label>
					<select data-field="<?php echo esc_attr( $db_field ); ?>">
						<option value="">-- <?php esc_html_e( 'Select Column', 'shopglut' ); ?> --</option>
						<?php foreach ( $headers as $header ) : ?>
							<?php
							$header_lower      = strtolower( (string) $header );
							$db_field_lower    = strtolower( $db_field );
							$db_field_spaced   = str_replace( '_', ' ', $db_field_lower );
							$selected          = ( $header_lower === $db_field_lower || $header_lower === $db_field_spaced ) ? 'selected' : '';
							?>
							<option value="<?php echo esc_attr( $header ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $header ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endforeach; ?>
			</div>
			<?php
			$mapping_html = ob_get_clean();

			$_SESSION['shopglut_import_data'] = array(
				'headers' => $headers,
				'content' => $file_content,
				'type'    => 'csv',
			);

			wp_send_json_success(
				array(
					'preview' => $preview_html,
					'mapping' => $mapping_html,
				)
			);
		}

		/**
		 * AJAX handler for uploading the import file and storing it in the session.
		 */
		public function ajax_upload_import_file() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! isset( $_FILES['import_file'] ) || ! isset( $_FILES['import_file']['error'] ) || UPLOAD_ERR_OK !== $_FILES['import_file']['error'] || ! is_uploaded_file( $_FILES['import_file']['tmp_name'] ) ) {
				wp_send_json_error( array( 'message' => 'File upload error.' ) );
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$file           = $_FILES['import_file'];
			$file_name      = sanitize_file_name( $file['name'] );
			$file_extension = strtolower( pathinfo( $file_name, PATHINFO_EXTENSION ) );

			if ( 'csv' !== $file_extension ) {
				wp_send_json_error( array( 'message' => 'Unsupported file format. Please upload CSV files only.' ) );
			}

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
			global $wp_filesystem;

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$content = $wp_filesystem->get_contents( $file['tmp_name'] );
			if ( false === $content ) {
				wp_send_json_error( array( 'message' => 'Could not read uploaded file.' ) );
			}

			if ( ! session_id() ) {
				session_start();
			}

			$_SESSION['shopglut_import_data'] = array(
				'type'     => 'csv',
				'content'  => $content,
				'filename' => $file_name,
			);

			$lines        = explode( "\n", $content );
			$headers      = str_getcsv( array_shift( $lines ) );
			$preview_rows = array();
			$count        = 0;
			foreach ( $lines as $line ) {
				if ( trim( $line ) && $count < 5 ) {
					$preview_rows[] = str_getcsv( $line );
					$count++;
				}
			}

			wp_send_json_success(
				array(
					'headers'      => $headers,
					'preview_rows' => $preview_rows,
					'type'         => 'csv',
				)
			);
		}


		/**
		 * AJAX handler to process the wishlist data import from session data.
		 */
		public function ajax_import_wishlist_data() {
			check_ajax_referer( 'shopglut_dashboard_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Unauthorized', 403 );
			}

			if ( ! session_id() ) {
				session_start();
			}

			if ( empty( $_SESSION['shopglut_import_data'] ) ) {
				wp_send_json_error( array( 'message' => 'No import data found. Please upload file again.' ) );
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$import_data         = isset( $_SESSION['shopglut_import_data'] ) ? (array) $_SESSION['shopglut_import_data'] : array();
			$import_type         = isset( $_POST['import_type'] ) ? sanitize_key( wp_unslash( $_POST['import_type'] ) ) : 'merge';
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$field_mapping_raw   = isset( $_POST['field_mapping'] ) && is_array( $_POST['field_mapping'] ) ? wp_unslash( $_POST['field_mapping'] ) : array();
			$field_mapping       = $this->sanitize_array( $field_mapping_raw );

			if ( empty( $field_mapping ) ) {
				wp_send_json_error( array( 'message' => 'Field mapping is required.' ) );
			}

			global $wpdb;
			$table_name     = $this->table_shopg_wishlist();
			$imported_count = 0;

			try {
				if ( 'replace' === $import_type ) {
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$wpdb->query( "TRUNCATE TABLE `{$table_name}`" );
				}

				if ( ! empty( $import_data['type'] ) && 'csv' === $import_data['type'] ) {
					$lines   = explode( "\n", (string) $import_data['content'] );
					$headers = str_getcsv( array_shift( $lines ) );

					foreach ( $lines as $line ) {
						if ( trim( $line ) ) {
							$row_data    = str_getcsv( $line );
							$mapped_data = array();

							foreach ( $field_mapping as $db_field => $csv_column ) {
								if ( $csv_column && in_array( $csv_column, $headers, true ) ) {
									$column_index = array_search( $csv_column, $headers, true );
									if ( false !== $column_index && isset( $row_data[ $column_index ] ) ) {
										$mapped_data[ $db_field ] = $row_data[ $column_index ];
									}
								}
							}

							if ( ! empty( $mapped_data ) ) {
								$this->import_wishlist_row( $mapped_data, $import_type );
								$imported_count++;
							}
						}
					}
				}

				unset( $_SESSION['shopglut_import_data'] );

				wp_send_json_success(
					array(
						'imported_count' => $imported_count,
						'message'        => sprintf(
							// translators: %d: Number of imported records.
							esc_html__( 'Successfully imported %d records.', 'shopglut' ),
							$imported_count
						),
					)
				);

			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Import failed: ' . $e->getMessage() ) );
			}
		}

		/**
		 * Helper function to recursively sanitize an array.
		 *
		 * @param array $array The array to sanitize.
		 * @return array The sanitized array.
		 */
		private function sanitize_array( $array ) {
			foreach ( $array as $key => &$value ) {
				$key = sanitize_key( $key );
				if ( is_array( $value ) ) {
					$value = $this->sanitize_array( $value );
				} else {
					$value = sanitize_text_field( $value );
				}
			}
			return $array;
		}


		/**
		 * Imports a single row of wishlist data into the database.
		 *
		 * @param array  $mapped_data The data for the row.
		 * @param string $import_type The type of import (merge or replace).
		 * @return int|false The number of rows affected, or false on error.
		 * @throws Exception On database error or missing fields.
		 */
		private function import_wishlist_row( $mapped_data, $import_type ) {
			global $wpdb;
			$table_name = $this->table_shopg_wishlist();

			$required_fields = array( 'wish_user_id', 'username', 'useremail', 'product_ids' );
			foreach ( $required_fields as $field ) {
				if ( empty( $mapped_data[ $field ] ) ) {
					throw new Exception( "Required field '" . esc_html( $field ) . "' is missing or empty" );
				}
			}

			$insert_data     = array();
			$allowed_columns = array(
				'wish_user_id',
				'username',
				'useremail',
				'product_ids',
				'product_meta',
				'wishlist_notifications',
				'product_added_time',
				'product_individual_dates',
				'share_data',
			);

			foreach ( $allowed_columns as $column ) {
				if ( isset( $mapped_data[ $column ] ) ) {
					$insert_data[ $column ] = $mapped_data[ $column ];
				}
			}

			if ( ! empty( $insert_data['product_added_time'] ) ) {
				$insert_data['product_added_time'] = gmdate( 'Y-m-d H:i:s', strtotime( $insert_data['product_added_time'] ) );
			} else {
				$insert_data['product_added_time'] = current_time( 'mysql', 1 );
			}

			$insert_data['wishlist_notifications'] = $insert_data['wishlist_notifications'] ?? '';

			if ( 'merge' === $import_type ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$query = "SELECT id FROM %i WHERE wish_user_id = %s AND useremail = %s";
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$existing_id = $wpdb->get_var(
					$wpdb->prepare(
						$query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
						$insert_data['wish_user_id'],
						$insert_data['useremail']
					)
				);

				if ( $existing_id ) {
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$result = $wpdb->update( $table_name, $insert_data, array( 'id' => $existing_id ) );
				} else {
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$result = $wpdb->insert( $table_name, $insert_data );
				}
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$result = $wpdb->insert( $table_name, $insert_data );
			}

			if ( false === $result ) {
				throw new Exception( 'Database error: ' . esc_html( $wpdb->last_error ) );
			}

			return $result;
		}

		/**
		 * Calculates the trend for a specific field over a period.
		 *
		 * @param string $table_name The database table name.
		 * @param string $field The column to calculate the trend on.
		 * @param string $function The SQL aggregate function (e.g., 'COUNT', 'SUM').
		 * @param int    $days The number of days for the period.
		 * @return float The trend percentage.
		 */
		private function calculateTrend( $table_name, $field, $function, $days ) {
			global $wpdb;

			$current_start_date  = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );
			$previous_start_date = gmdate( 'Y-m-d H:i:s', strtotime( '-' . ( $days * 2 ) . ' days' ) );
			$previous_end_date   = $current_start_date;

			// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$current_period = $wpdb->get_var( $wpdb->prepare( "SELECT {$function}({$field}) FROM {$table_name} WHERE product_added_time >= %s", $current_start_date ) );

			$previous_period = $wpdb->get_var( $wpdb->prepare( "SELECT {$function}({$field}) FROM {$table_name} WHERE product_added_time >= %s AND product_added_time < %s", $previous_start_date, $previous_end_date ) );
			// phpcs:enable

			if ( ! $previous_period ) {
				return $current_period > 0 ? 100.0 : 0;
			}

			return round( ( ( $current_period - $previous_period ) / $previous_period ) * 100, 1 );
		}

	} // End class dataManage
} // End if class_exists check

