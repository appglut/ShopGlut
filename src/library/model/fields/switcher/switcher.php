<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_switcher' ) ) {
	class AGSHOPGLUT_switcher extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$active = ( ! empty( $this->value ) ) ? ' agl--active' : '';
			$text_on = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'shopglut' );
			$text_off = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'shopglut' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . wp_kses_post( $this->field['text_width'] ) . 'px;"' : '';

			echo esc_attr( $this->field_before() );

			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

			// Query shop layouts without cache for real-time conflict detection
			$layout_values = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time conflict check
				sprintf("SELECT * FROM `%s`", esc_sql($table_name)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.MissingReplacements -- Using sprintf with escaped table name, no additional parameters needed
			);

	
			// Default value for enable_switcher
			$enable_switcher = '0';
			$shop_layout_taken_message = '';

			// Get current layout ID if editing
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout ID only
			$current_editing_layout_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 0;

			// Loop through each layout and check if 'overwrite-shop-page' is enabled
			if ( ! empty( $layout_values ) ) {
				foreach ( $layout_values as $layout ) {
					// Skip the current layout being edited
					if ( $current_editing_layout_id > 0 && $layout->id == $current_editing_layout_id ) {
						continue;
					}

					// Check if $layout->layout_settings is a non-empty string before calling unserialize()
					$layout_data_array = !empty( $layout->layout_settings ) && is_string( $layout->layout_settings )
						? unserialize( $layout->layout_settings )
						: [];

					$layout_array_values = isset( $layout_data_array['shopg_options_settings']['shopg_settings_options'] )
						? $layout_data_array['shopg_options_settings']['shopg_settings_options']
						: '';

					// Check if 'overwrite-shop-page' is enabled for this layout
					$switcher_value = isset( $layout_array_values['shopg_display_settings_accordion']['overwrite-shop-page'] )
						? $layout_array_values['shopg_display_settings_accordion']['overwrite-shop-page']
						: '0';

	
					if ( $switcher_value !== '0' ) {
						// At least one OTHER layout has the switcher enabled
						$enable_switcher = '1';
						$shop_layout_taken_message = __( 'Already Taken', 'shopglut' ) . ' - ' . esc_html( $layout->layout_name );
								break; // No need to check further once we find an enabled switcher
					}
				}
			}

			// Initialize current_enable_switcher with default value (outside the layout_id check so it's always set)
			$current_enable_switcher = '0';

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout ID only
			if ( isset( $_GET['layout_id'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout ID only
				$layout_id = absint( wp_unslash( $_GET['layout_id'] ) );

				$single_product_table_name = $wpdb->prefix . 'shopglut_single_product_layout';

				// Default values for single product switcher logic
				$disable_single_product_switcher = false;
				$single_product_taken_message = '';

				// Default values for order complete switcher logic
				$disable_ordercomplete_switcher = false;
				$ordercomplete_taken_message = '';

				// Cache key for single product layouts
				// Query WITHOUT cache for real-time conflict detection
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time conflict check
				$single_product_layout_values = $wpdb->get_results(
					sprintf("SELECT * FROM `%s`", esc_sql($single_product_table_name)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name, no prepare needed
				);

				// Query current layout WITHOUT cache for real-time status check
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time status check
				$current_layout = $wpdb->get_row(
					sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($single_product_table_name), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated ID
				);

				// If current layout exists, get its details
				if ( $current_layout ) {
					// Safely unserialize layout settings
					$current_layout_data = !empty( $current_layout->layout_settings )
						? maybe_unserialize( $current_layout->layout_settings )
						: [];

					// Get current layout template to check the correct template settings
					$current_template = isset( $current_layout->layout_template ) ? $current_layout->layout_template : 'template1';
					$template_settings_key = 'shopg_singleproduct_settings_' . $current_template;

					// Check template-specific settings for overwrite-all-products (both direct and nested)
					$current_overwrite_all_products = isset( $current_layout_data[$template_settings_key]['overwrite-all-products'] )
						? $current_layout_data[$template_settings_key]['overwrite-all-products']
						: '0';

					// Also check nested structure: single-product-settings -> overwrite-all-products
					if ( !$current_overwrite_all_products && isset( $current_layout_data[$template_settings_key]['single-product-settings']['overwrite-all-products'] ) ) {
						$current_overwrite_all_products = $current_layout_data[$template_settings_key]['single-product-settings']['overwrite-all-products'];
					}
				}

				// Only check for conflicts if the current layout does NOT have overwrite-all-products enabled
				if ( !$current_overwrite_all_products || $current_overwrite_all_products === '0' || $current_overwrite_all_products === '' ) {
					// Check all OTHER layouts for overwrite-all-products conflicts
					if ( ! empty( $single_product_layout_values ) ) {
						foreach ( $single_product_layout_values as $slayout ) {
							// Skip checking the current layout against itself
							if ( $slayout->id == $layout_id ) {
								continue;
							}

							// Safely unserialize layout settings
							$slayout_data = !empty( $slayout->layout_settings )
								? maybe_unserialize( $slayout->layout_settings )
								: [];

							// Get the other layout's template to check the correct structure
							$other_template = isset( $slayout->layout_template ) ? $slayout->layout_template : 'template1';
							$other_template_key = 'shopg_singleproduct_settings_' . $other_template;

							// Check template-specific settings for overwrite-all-products (both direct and nested)
							$overwrite_all_products = isset( $slayout_data[$other_template_key]['overwrite-all-products'] )
								? $slayout_data[$other_template_key]['overwrite-all-products']
								: '0';

							// Also check nested structure: single-product-settings -> overwrite-all-products
							if ( !$overwrite_all_products && isset( $slayout_data[$other_template_key]['single-product-settings']['overwrite-all-products'] ) ) {
								$overwrite_all_products = $slayout_data[$other_template_key]['single-product-settings']['overwrite-all-products'];
							}

							if ( $overwrite_all_products == '1' ) {
								$disable_single_product_switcher = true;
								$single_product_taken_message = __( 'Already Taken', 'shopglut' ) . ' - ' . esc_html( $slayout->layout_name );
								break;
							}

							// If we found a conflict, no need to check further
							if ( $disable_single_product_switcher ) {
								break;
							}
						}
					}
				}

				// Get current shop layout without cache for real-time status check
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time status check
				$current_layout_values = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time status check
				sprintf("SELECT * FROM `%s` WHERE id = %d", esc_sql($table_name), $layout_id) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name and validated ID
			);

				if ( ! empty( $current_layout_values ) ) {
					// Check if $current_layout_values[0]->layout_settings is a non-empty string before calling unserialize()
					$current_layout_data_array = !empty( $current_layout_values[0]->layout_settings ) && is_string( $current_layout_values[0]->layout_settings )
						? unserialize( $current_layout_values[0]->layout_settings )
						: [];
						
					$current_layout_array_values = isset( $current_layout_data_array['shopg_options_settings']['shopg_settings_options'] )
						? $current_layout_data_array['shopg_options_settings']['shopg_settings_options']
						: '';
				
					$current_enable_switcher = isset( $current_layout_array_values['shopg_display_settings_accordion']['overwrite-shop-page'] )
						? $current_layout_array_values['shopg_display_settings_accordion']['overwrite-shop-page']
						: '0';
				}

				// ==================== ORDER COMPLETE SWITCHER LOGIC ====================
				$ordercomplete_table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

				// Get all order complete layouts (no cache)
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query needed for real-time override check
				$ordercomplete_layout_values = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query needed for real-time override check
					sprintf("SELECT * FROM `%s`", esc_sql($ordercomplete_table_name)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name
				);

				// Get current order complete layout (no cache)
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time override check
				$current_oc_layout = $wpdb->get_row( $wpdb->prepare(
					"SELECT * FROM `{$wpdb->prefix}shopglut_ordercomplete_layouts` WHERE id = %d",
					$layout_id
				) );

				// If current layout exists, get its override status
				$current_oc_override = false;
				if ( $current_oc_layout ) {
					// Safely unserialize layout settings
					$current_oc_data = !empty( $current_oc_layout->layout_settings )
						? maybe_unserialize( $current_oc_layout->layout_settings )
						: [];

					// Check the correct path (inside ordercomplete-page-settings tab)
					if ( isset( $current_oc_data['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] ) ) {
						$current_oc_override = $current_oc_data['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'];
					}
				}

				// Only check for conflicts if the current layout does NOT have override enabled
				if ( !$current_oc_override || $current_oc_override == '0' || $current_oc_override == 0 ) {
					// Check all OTHER layouts for override conflicts
					if ( ! empty( $ordercomplete_layout_values ) ) {
						foreach ( $ordercomplete_layout_values as $oc_layout ) {
							// Skip checking the current layout against itself
							if ( $oc_layout->id == $layout_id ) {
								continue;
							}

							// Safely unserialize layout settings
							$oc_layout_data = !empty( $oc_layout->layout_settings )
								? maybe_unserialize( $oc_layout->layout_settings )
								: [];

							// Check the correct path (inside ordercomplete-page-settings tab)
							$oc_override = false;
							if ( isset( $oc_layout_data['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'] ) ) {
								$oc_override = $oc_layout_data['shopg_ordercomplete_settings_template1']['ordercomplete-page-settings']['override_woocommerce_ordercomplete'];
							}

							if ( $oc_override && ( $oc_override == '1' || $oc_override == 1 || $oc_override === true ) ) {
								$disable_ordercomplete_switcher = true;
								$ordercomplete_taken_message = __( 'Already Taken', 'shopglut' ) . ' - ' . esc_html( $oc_layout->layout_name );
								break;
							}
						}
					}
				}

				// ==================== ACCOUNT PAGE SWITCHER LOGIC ====================
				$accountpage_table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

				// Default values for account page switcher logic
				$disable_accountpage_switcher = false;
				$accountpage_taken_message = '';

				// Get all account page layouts (no cache)
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query needed for real-time override check
				$accountpage_layout_values = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Direct query needed for real-time override check
					sprintf("SELECT * FROM `%s`", esc_sql($accountpage_table_name)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Using sprintf with escaped table name
				);

				// Get current account page layout (no cache)
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query needed for real-time override check
				$current_ap_layout = $wpdb->get_row( $wpdb->prepare(
					"SELECT * FROM `{$wpdb->prefix}shopglut_accountpage_layouts` WHERE id = %d",
					$layout_id
				) );

				// If current layout exists, get its enable status
				$current_ap_enabled = false;
				if ( $current_ap_layout ) {
					// Safely unserialize layout settings
					$current_ap_data = !empty( $current_ap_layout->layout_settings )
						? maybe_unserialize( $current_ap_layout->layout_settings )
						: [];

					// Check if enable_accountpage is enabled for this layout
					if ( isset( $current_ap_data['shopg_accountpage_settings_template1']['enable_accountpage'] ) ) {
						$current_ap_enabled = $current_ap_data['shopg_accountpage_settings_template1']['enable_accountpage'];
					}
				}

				// Only check for conflicts if the current layout does NOT have enable_accountpage enabled
				if ( !$current_ap_enabled || $current_ap_enabled == '0' || $current_ap_enabled == 0 || $current_ap_enabled === false ) {
					// Check all OTHER layouts for enable_accountpage conflicts
					if ( ! empty( $accountpage_layout_values ) ) {
						foreach ( $accountpage_layout_values as $ap_layout ) {
							// Skip checking the current layout against itself
							if ( $ap_layout->id == $layout_id ) {
								continue;
							}

							// Safely unserialize layout settings
							$ap_layout_data = !empty( $ap_layout->layout_settings )
								? maybe_unserialize( $ap_layout->layout_settings )
								: [];

							// Check if enable_accountpage is enabled for this layout
							$ap_enabled = false;
							if ( isset( $ap_layout_data['shopg_accountpage_settings_template1']['enable_accountpage'] ) ) {
								$ap_enabled = $ap_layout_data['shopg_accountpage_settings_template1']['enable_accountpage'];
							}

							if ( $ap_enabled && ( $ap_enabled == '1' || $ap_enabled == 1 || $ap_enabled === true ) ) {
								$disable_accountpage_switcher = true;
								$accountpage_taken_message = __( 'Already Taken', 'shopglut' ) . ' - ' . esc_html( $ap_layout->layout_name );
								break;
							}
						}
					}
				}
			}

			// Check if 'pro' is set and has a value
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );

			// If 'pro' is set, disable the switcher and show pro version text
			if ( $is_pro ) {
				echo '<div class="agl--switcher agl--pro agl--disabled"' . wp_kses_post( $text_width ) . '>';
				echo '<span class="agl--on">' . esc_attr( $text_on ) . '</span>';
				echo '<span class="agl--off">' . esc_attr( $text_off ) . '</span>';
				echo '<span class="agl--ball"></span>';
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="0" disabled />';
				echo '</div>';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
			} else if ( isset( $enable_switcher ) && $enable_switcher === '1' && isset( $current_enable_switcher ) && $current_enable_switcher === '0' && $this->field['id'] === 'overwrite-shop-page' ) {
				echo '<div class="agl--switcher-taken">';
				echo '<span class="agl--already-taken" title="' . esc_attr( $shop_layout_taken_message ) . '">' . esc_html( $shop_layout_taken_message ) . '</span>';
				echo '</div>';
			} elseif ( isset($disable_single_product_switcher) && $disable_single_product_switcher && $this->field['id'] === 'overwrite-all-products' ) {
				// Show "Already Taken" only for layouts that don't have overwrite-all-products enabled
				echo '<div class="agl--switcher-single-product">';
				echo '<span class="agl--already-taken" title="' . esc_attr( $single_product_taken_message ) . '">' . esc_html( $single_product_taken_message ) . '</span>';
				echo '</div>';
			} elseif ( isset($disable_ordercomplete_switcher) && $disable_ordercomplete_switcher && $this->field['id'] === 'override_woocommerce_ordercomplete' ) {
				// Show "Already Taken" for order complete override switcher
				echo '<div class="agl--switcher-ordercomplete">';
				echo '<span class="agl--already-taken" title="' . esc_attr( $ordercomplete_taken_message ) . '">' . esc_html( $ordercomplete_taken_message ) . '</span>';
				echo '</div>';
			} elseif ( isset($disable_accountpage_switcher) && $disable_accountpage_switcher && $this->field['id'] === 'enable_accountpage' ) {
				// Show "Already Taken" for account page enable switcher
				echo '<div class="agl--switcher-accountpage">';
				echo '<span class="agl--already-taken" title="' . esc_attr( $accountpage_taken_message ) . '">' . esc_html( $accountpage_taken_message ) . '</span>';
				echo '</div>';
			} else {
				// Normal switcher
				echo '<div class="agl--switcher' . esc_attr( $active ) . '"' . wp_kses_post( $text_width ) . '>';
				echo '<span class="agl--on">' . esc_attr( $text_on ) . '</span>';
				echo '<span class="agl--off">' . esc_attr( $text_off ) . '</span>';
				echo '<span class="agl--ball"></span>';
				$value = ( $this->value == 1 ) ? $this->value : 0;
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
				echo '</div>';
			}

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="agl--label">' . esc_attr( $this->field['label'] ) . '</span>' : '';

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Get product names from product IDs
		 *
		 * @param array $product_ids Array of product IDs
		 * @return string Comma-separated product names
		 */
		private function get_product_names( $product_ids ) {
			if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
				return '';
			}
			
			$product_names = array();
			
			foreach ( $product_ids as $product_id ) {
				$product_id = absint( $product_id );
				if ( $product_id > 0 ) {
					$product = wc_get_product( $product_id );
					if ( $product ) {
						$product_names[] = $product->get_name();
					} else {
						// If product not found, show ID with indication
						/* translators: %d: product ID number */
					// translators: %d is the product ID that was not found
						$product_names[] = sprintf( __( 'Product ID: %d (not found)', 'shopglut' ), $product_id );
					}
				}
			}
			
			// Limit display to avoid very long strings
			if ( count( $product_names ) > 3 ) {
				$displayed_names = array_slice( $product_names, 0, 3 );
				$remaining_count = count( $product_names ) - 3;
				/* translators: %d: number of additional items not shown */
			// translators: %d is the number of additional items not shown
				return implode( ', ', $displayed_names ) . sprintf( __( ' and %d more', 'shopglut' ), $remaining_count );			}
			
			return implode( ', ', $product_names );
		}
	}
}