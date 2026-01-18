<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_subscriptionTable' ) ) {
	class AGSHOPGLUT_subscriptionTable extends AGSHOPGLUTP {

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			$this->cron_token = get_option( 'shopglut_wishlist_cron_token', '' );
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

			<div class="agl-woo-subscription-table">
				<h3><?php esc_html_e( 'Subscribers', 'shopglut' ); ?></h3>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Subscription', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Item', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Payment', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Start Date', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Next Payment', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Last Order', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'End Date', 'shopglut' ); ?></th>
							<th><?php esc_html_e( 'Status', 'shopglut' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						global $wpdb;
						$table_name = $wpdb->prefix . 'shopglut_woo_subscriptions';
			   // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function
						$subscriptions = $wpdb->get_results( 
							""
						);

						if ( ! empty( $subscriptions ) ) {
							foreach ( $subscriptions as $subscription ) {
								$user_info = get_userdata($subscription->user_id);
								$username = $user_info ? $user_info->display_name : __('Unknown User', 'shopglut');
								$product_title = $subscription->product_name ? $subscription->product_name : __('Unknown Product', 'shopglut');
								
								$status_class = 'status-' . $subscription->status;
								?>
								<tr class="iedit author-self level-1 type-shop_subscription status-<?php echo esc_attr($status_class); ?> hentry">
									<td class="order_title column-order_title has-row-actions column-primary" data-colname="Subscription">
										<div class="tips">
											<a href="<?php echo esc_url(admin_url('post.php?post=' . $subscription->order_id . '&action=edit')); ?>">
												#<strong>AG-<?php echo esc_html($subscription->subscription_id); ?></strong>
											</a>
											for <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $subscription->user_id)); ?>">
												<?php echo esc_html($username); ?>
											</a>
										</div>
									</td>

									<td class="order_items column-order_items" data-colname="Items">
										<div class="order-item">
											<a href="<?php echo esc_url(admin_url('post.php?post=' . $subscription->product_id . '&action=edit')); ?>">
												<?php echo esc_html($product_title); ?>
											</a>
										</div>
									</td>

									<td class="recurring_total column-recurring_total" data-colname="Total">
										<?php echo esc_attr(wc_price($subscription->recurring_amount)); ?>
										<?php if ($subscription->payment_method_title): ?>
											<small class="meta"><?php echo esc_html($subscription->payment_method_title); ?></small>
										<?php endif; ?>
									</td>

									<td class="start_date column-start_date" data-colname="Start Date">
										<time class="start_date" title="<?php echo esc_attr($subscription->start_date); ?>">
											<?php echo esc_attr(date_i18n(get_option('date_format'), strtotime($subscription->start_date))); ?>
										</time>
									</td>

									<td class="next_payment_date column-next_payment_date" data-colname="Next Payment">
										<?php if ($subscription->next_payment_date): ?>
											<?php echo esc_attr(date_i18n(get_option('date_format'), strtotime($subscription->next_payment_date))); ?>
										<?php else: ?>
											<?php esc_html_e('N/A', 'shopglut'); ?>
										<?php endif; ?>
									</td>

									<td class="last_payment_date column-last_payment_date" data-colname="Last Order Date">
										<?php if ($subscription->last_payment_date): ?>
											<?php echo esc_attr(date_i18n(get_option('date_format'), strtotime($subscription->last_payment_date))); ?>
										<?php else: ?>
											<?php esc_html_e('N/A', 'shopglut'); ?>
										<?php endif; ?>
									</td>

									<td class="end_date column-end_date" data-colname="End Date">
										<?php if ($subscription->end_date): ?>
											<time class="end_date" title="<?php echo esc_attr($subscription->end_date); ?>">
												<?php echo esc_attr(date_i18n(get_option('date_format'), strtotime($subscription->end_date))); ?>
											</time>
										<?php else: ?>
											<?php esc_html_e('N/A', 'shopglut'); ?>
										<?php endif; ?>
									</td>

									<td class="status column-status" data-colname="Status">
										<mark class="subscription-status order-status status-<?php echo esc_attr($subscription->status); ?> tips">
											<span><?php echo esc_html(ucfirst($subscription->status)); ?></span>
										</mark>
									</td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr>
								<td colspan="8"><?php esc_html_e( 'No subscriptions available', 'shopglut' ); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php
		}
	}
}