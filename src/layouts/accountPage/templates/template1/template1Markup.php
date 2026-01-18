<?php
namespace Shopglut\layouts\accountPage\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Markup {

	public function layout_render($template_data) {
		// Get settings
		$layout_id = isset($template_data['layout_id']) ? $template_data['layout_id'] : 0;
		$settings = $this->getLayoutSettings($layout_id);

		// Check if we're rendering demo or live
		$is_demo = isset($template_data['is_demo']) ? $template_data['is_demo'] : true;

		if ($is_demo) {
			// Render demo account page
			$this->render_demo_accountpage($settings);
		} else {
			// Render live account page with WooCommerce integration
			$this->render_live_accountpage($settings);
		}
	}

	/**
	 * Render live WooCommerce account page
	 */
	public function render_live_accountpage($settings) {
		if (!class_exists('WooCommerce')) {
			echo '<div class="shopglut-accountpage-error"><p>' . esc_html__('WooCommerce is not active.', 'shopglut') . '</p></div>';
			return;
		}

		$current_user = wp_get_current_user();
		if (!is_user_logged_in()) {
			// Show login form if user is not logged in
			wc_get_template('myaccount/form-login.php');
			return;
		}

		?>
		<div class="shopglut-woocommerce-account template1">
			<div class="woocommerce-MyAccount-content-wrapper">
				<!-- Account Navigation -->
				<nav class="woocommerce-MyAccount-navigation">
					<ul>
						<?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
							<li class="<?php echo esc_attr( wc_get_account_menu_item_classes($endpoint) ); ?>">
								<a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
									<?php echo esc_html($label); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<!-- Account Content -->
				<div class="woocommerce-MyAccount-content">
					<?php
					// Display the current account page content
					do_action('woocommerce_account_content');
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo account page for preview
	 */
	public function render_demo_accountpage($settings) {
		?>
		<div class="shopglut-woocommerce-account template1">
			<div class="woocommerce-MyAccount-content-wrapper">
				<!-- Account Navigation -->
				<nav class="woocommerce-MyAccount-navigation">
					<ul>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active">
							<a href="#"><?php esc_html_e('Dashboard', 'shopglut'); ?></a>
						</li>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders">
							<a href="#"><?php esc_html_e('Orders', 'shopglut'); ?></a>
						</li>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--downloads">
							<a href="#"><?php esc_html_e('Downloads', 'shopglut'); ?></a>
						</li>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address">
							<a href="#"><?php esc_html_e('Addresses', 'shopglut'); ?></a>
						</li>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account">
							<a href="#"><?php esc_html_e('Account details', 'shopglut'); ?></a>
						</li>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
							<a href="#"><?php esc_html_e('Logout', 'shopglut'); ?></a>
						</li>
					</ul>
				</nav>

				<!-- Account Content -->
				<div class="woocommerce-MyAccount-content">
					<!-- Dashboard Content -->
					<div class="woocommerce-account-dashboard">
						<p>
							<?php
							echo wp_kses_post( sprintf(
								/* translators: 1: user display name 2: logout url */
								__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'shopglut'),
								'<strong>John Doe</strong>',
								'#'
							) );
							?>
						</p>

						<p>
							<?php
							echo wp_kses_post( sprintf(
								/* translators: 1: Orders URL 2: Address URL 3: Account URL */
								__('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'shopglut'),
								'#',
								'#',
								'#'
							) );
							?>
						</p>

						<!-- Recent Orders Section -->
						<div class="woocommerce-account-recent-orders">
							<h2><?php esc_html_e('Recent Orders', 'shopglut'); ?></h2>
							<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
								<thead>
									<tr>
										<th class="woocommerce-orders-table__header"><span class="nobr"><?php esc_html_e('Order', 'shopglut'); ?></span></th>
										<th class="woocommerce-orders-table__header"><span class="nobr"><?php esc_html_e('Date', 'shopglut'); ?></span></th>
										<th class="woocommerce-orders-table__header"><span class="nobr"><?php esc_html_e('Status', 'shopglut'); ?></span></th>
										<th class="woocommerce-orders-table__header"><span class="nobr"><?php esc_html_e('Total', 'shopglut'); ?></span></th>
										<th class="woocommerce-orders-table__header"><span class="nobr"><?php esc_html_e('Actions', 'shopglut'); ?></span></th>
									</tr>
								</thead>
								<tbody>
									<tr class="woocommerce-orders-table__row order">
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Order', 'shopglut'); ?>">
											<a href="#">#12345</a>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Date', 'shopglut'); ?>">
											<time datetime="2024-10-01">October 1, 2024</time>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Status', 'shopglut'); ?>">
											<span class="woocommerce-order-status status-processing"><?php esc_html_e('Processing', 'shopglut'); ?></span>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Total', 'shopglut'); ?>">
											<span class="woocommerce-Price-amount amount"><bdi>$299.99 <span class="woocommerce-Price-currencySymbol"></span></bdi></span> <?php esc_html_e('for 2 items', 'shopglut'); ?>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Actions', 'shopglut'); ?>">
											<a href="#" class="woocommerce-button button view"><?php esc_html_e('View', 'shopglut'); ?></a>
										</td>
									</tr>
									<tr class="woocommerce-orders-table__row order">
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Order', 'shopglut'); ?>">
											<a href="#">#12344</a>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Date', 'shopglut'); ?>">
											<time datetime="2024-09-28">September 28, 2024</time>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Status', 'shopglut'); ?>">
											<span class="woocommerce-order-status status-completed"><?php esc_html_e('Completed', 'shopglut'); ?></span>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Total', 'shopglut'); ?>">
											<span class="woocommerce-Price-amount amount"><bdi>$149.99 <span class="woocommerce-Price-currencySymbol"></span></bdi></span> <?php esc_html_e('for 1 item', 'shopglut'); ?>
										</td>
										<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e('Actions', 'shopglut'); ?>">
											<a href="#" class="woocommerce-button button view"><?php esc_html_e('View', 'shopglut'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row(
			$wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = %d", $layout_id)
		);

		if ($layout_data && !empty($layout_data->layout_settings)) {
			$settings = maybe_unserialize($layout_data->layout_settings);
			if (isset($settings['shopg_accountpage_settings_template1']['accountpage-page-settings'])) {
				return $this->flattenSettings($settings['shopg_accountpage_settings_template1']['accountpage-page-settings']);
			}
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Flatten nested settings structure to simple key-value pairs
	 */
	private function flattenSettings($nested_settings) {
		$flat_settings = array();

		foreach ($nested_settings as $group_key => $group_values) {
			if (is_array($group_values)) {
				foreach ($group_values as $setting_key => $setting_value) {
					if (is_array($setting_value) && isset($setting_value[$setting_key])) {
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} else {
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			'layout_id' => 0,
		);
	}
}
