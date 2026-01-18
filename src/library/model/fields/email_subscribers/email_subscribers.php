<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_email_subscribers' ) ) {
	class AGSHOPGLUT_email_subscribers extends AGSHOPGLUTP {

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		// Render the form and table
		public function render() {
			echo wp_kses_post($this->field_before());

			$args = wp_parse_args(
				$this->field,
				array(
					'from_name' => '',
					'from_email' => '',
					'email_body' => '',
					'send_email' => '',
					'time_value' => '',
					'time_unit' => '',
				)
			);

			$default_value = array(
				'from_name' => '',
				'from_email' => '',
				'email_body' => '',
				'send_email' => '',
				'time_value' => '',
				'time_unit' => '',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value = wp_parse_args( $this->value, $default_value );

			// Check if 'pro' is active
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );
			?>

			<div class="agl-fieldset-content">

			</div>

			<div class="agl-user-subscription-table">
				<h3><?php esc_html_e( 'Subscribed Users', 'shopglut' ); ?></h3>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Name', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Email', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Subscription Status', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Lock Type', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Expiry Date', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Created At', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'shopglut' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						global $wpdb;
						$table_name = $wpdb->prefix . 'shopglut_lock_settings';
						$escaped_table = esc_sql($table_name);
				$subscribed_users = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table existence check with caching
							sprintf("SELECT * FROM `%s`", $escaped_table) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.MissingReplacements -- Using sprintf with escaped table name, no additional parameters needed
						);

						if ( ! empty( $subscribed_users ) ) {
							foreach ( $subscribed_users as $user ) {
								$name = esc_html( $user->name_subscribe );
								$email = esc_html( $user->email_subscribe );
								$status = esc_html( $user->subscription_status );
								$lock_type = esc_html( $user->lock_type );
								$expiry_date = ! empty( $user->expiry_date ) ? esc_html( $user->expiry_date ) : __( 'N/A', 'shopglut' );
								$created_at = esc_html( $user->created_at );
								?>
								<tr>
									<td><?php echo esc_html($name); ?></td>
									<td><?php echo esc_html($email); ?></td>
									<td>
										<span class="subscription-status status-<?php echo sanitize_html_class( $status ); ?>">
											<?php echo esc_html($status); ?>
										</span>
									</td>
									<td><?php echo esc_attr($lock_type); ?></td>
									<td><?php echo esc_attr($expiry_date); ?></td>
									<td><?php echo esc_attr($created_at); ?></td>
									<td>
										<!-- Disable send email button if Pro version is active -->
										<?php if ( $is_pro ) : ?>
											<a href="<?php echo esc_url( $this->field['pro'] ); ?>" target="_blank" class="agl--pro-link">
												<?php echo esc_html( $pro_text ); ?>
											</a>
										<?php else : ?>
											<button class="agl-send-email-button"
												data-email="<?php echo esc_attr( $user->email_subscribe ); ?>">
												<?php esc_html_e( 'Send Email', 'shopglut' ); ?>
											</button>
											<button class="agl-edit-user-button" data-id="<?php echo esc_attr( $user->id ); ?>">
												<?php esc_html_e( 'Edit', 'shopglut' ); ?>
											</button>
										<?php endif; ?>
									</td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr>
								<td colspan="7"><?php esc_html_e( 'No data available', 'shopglut' ); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php
		}
	}
}