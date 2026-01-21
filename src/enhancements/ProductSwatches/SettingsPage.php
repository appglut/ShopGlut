<?php
namespace Shopglut\enhancements\ProductSwatches;

class SettingsPage {

	public $menu_slug = 'product_swatches';


	public function loadProductSwatchesEditor() {

		$layout_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'global-assets/images/loading-icon.png';

		do_action( 'save_shopg_layout_data', $layout_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

		$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		// Get layout data with caching
		$cache_key = "shopglut_layout_data_{$layout_id}";
		$layout_data = wp_cache_get( $cache_key, 'shopglut_layouts' );

		if ( false === $layout_data ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Direct query required for custom table operation
			$layout_data = $wpdb->get_row(
				$wpdb->prepare( "SELECT layout_name, layout_template FROM `{$table_name}` WHERE id = %d", $layout_id )
			);
			wp_cache_set( $cache_key, $layout_data, 'shopglut_layouts', 30 * MINUTE_IN_SECONDS );
		}

		if ( $layout_data ) {
			$layout_name = $layout_data->layout_name;
			$layout_template = $layout_data->layout_template;
		} else {
			?>
			<div class="wrap">
				<p><?php esc_html_e( 'No layout data found.', 'shopglut' ); ?></p>
			</div>
			<?php
			return;
		}

		?>
		<div id="shopg-layout-admin-settings" class="wrap layout_settings shopglut-cart_settings">

			<div class="loader-overlay" id="main-loader-overlay" style="display: flex; opacity: 1;">
				<div class="loader-container">
					<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>

	<form id="shopglut_shop_layouts" method="post" action="">

				<?php 
				$shopg_cpage_nonce = wp_create_nonce( 'shopg_productswatches_layouts' ); 
				?>
				<input type="hidden" name="shopg_productswatches_layouts_nonce" value="<?php echo esc_attr( $shopg_cpage_nonce ); ?>">
				<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
					value="<?php echo esc_attr( $layout_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="shopglut_editor_header">

						<div class="back-to-menu">

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_enhancements&view=product_swatches' ) ); ?>"
								class="button button-secondary button-large">
								<i class="fa-solid fa-angles-left"></i>
								<?php echo esc_html__( 'Back To Menu', 'shopglut' ); ?>
							</a>

							<div class="clear"></div>
						</div>

						<div class="shopglut_layout_name">
							<label for="layout_name"><?php esc_html_e( 'Layout Name:', 'shopglut' ); ?></label>
							<input type="text" id="layout_name" name="layout_name"
								value="<?php echo esc_html( $layout_name ); ?>" />
							<input type="hidden" id="layout_template" name="layout_template"
								value="<?php echo esc_html( $layout_template ); ?>" />
						</div>

					</div>

					<div class="shopglut_layout_caption">
						<i class="fa-solid fa-circle-info"></i>
						<p class="info"><?php echo esc_html__( 'Info:', 'shopglut' ); ?></p>
						<p><?php echo esc_html__( 'Save Layout and see the update Preview', 'shopglut' ); ?></p>
					</div>

				<div id="shopg-notification-container"></div>

				</div>

                <div id="poststuff" class="shopglut-shoplayouts">

				<div id="post-body" class="metabox-holder columns-2">

						<div id="shopg-productswatches-layout-settings" class="postbox-container shopg-layout-settings-wrapper">
							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>
						</div>
			
					<button type="button" id="toggle-settings-button" class="toggle-button"><?php echo esc_html__('Hide', 'shopglut');  ?></button>

						<div class="submitbox" id="submitpost">
								<div id="productSwatchesLayout-publishing-action">
									<button type="button" id="productSwatches-reset-settings-button" class="btn btn-fullwidth btn-secondary"
									style=" background: #dc3545; color: white; border: none;">
									<?php echo esc_attr__( 'Reset', 'shopglut' ); ?>
									</button>
									<input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
									value="<?php echo esc_attr__( 'Save Layout', 'shopglut' ); ?>">

								</div>
								<div class="clear"></div>
						</div>
						<div id="shopg-productSwatches-layout-container" class="shopg-admin-edit-panel">
							<div class="shopg-inside-loader">
								<div class="shopg-inside-loader-overlay">
									<div class="shopg-inside-loader-container">
										<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon"
											class="shopg-inside-loader-image">
										<div class="shopg-inside-loader-dash-circle"></div>
									</div>
								</div>
							</div>
							<?php do_meta_boxes( $this->menu_slug, 'normal', '' ); ?>
						</div>
				</div>

				</div>

				
	</div>

    </form>

		</div>
		<style>
			html.wp-toolbar {
				padding-top: 0px !important;
			}

			/* Override the collapsed button position to left side */
			#shopg-cart-layout-settings .toggle-button.collapsed {
				right: auto !important;
				left: 0px !important;
				transform: rotate(90deg) translateY(-50%) !important;
				transform-origin: left center !important;
				pointer-events: auto !important;
				cursor: pointer !important;
				background: #097dab !important;
				transition: none !important;
			}

			/* Prevent hover movement/scaling on collapsed button */
			#shopg-cart-layout-settings .toggle-button.collapsed:hover {
				transform: rotate(90deg) translateY(-50%) !important;
				background: #075a87 !important;
			}

			/* Expand preview section when settings panel is collapsed */
			#shopg-cart-layout-settings.collapsed ~ #shopg-productSwatches-layout-container {
				width: 95% !important;
			}

			/* Ensure preview takes full width when settings expanded */
			#shopg-productSwatches-layout-container {
				transition: all 0.3s ease !important;
			}

			#shopg-cart-layout-settings.collapsed .inside,
			#shopg-cart-layout-settings.collapsed .agshopglut-metabox,
			#shopg-cart-layout-settings.collapsed h2,
			#shopg-cart-layout-settings.collapsed .postbox {
				display: none !important;
			}

			/* Ensure button is always visible and clickable */
			#shopg-cart-layout-settings .toggle-button {
				pointer-events: auto !important;
				z-index: 999999 !important;
				position: absolute !important;
				cursor: pointer !important;
			}

			/* Ensure loader covers all content including buttons when visible */
			.loader-overlay[style*="display: flex"],
			.loader-overlay:not([style*="display: none"]) {
				z-index: 9999999 !important;
				position: fixed !important;
				top: 0 !important;
				left: 0 !important;
				width: 100% !important;
				height: 100% !important;
				align-items: center !important;
				justify-content: center !important;
				background-color: rgba(255, 255, 255, 0.9) !important;
			}

			/* Center the loader container properly when loader is visible */
			.loader-overlay[style*="display: flex"] .loader-container,
			.loader-overlay:not([style*="display: none"]) .loader-container {
				position: relative !important;
				text-align: center !important;
				transform: none !important;
				top: auto !important;
				left: auto !important;
				margin: 0 auto !important;
			}

			/* Make the loader image proper size when visible */
			.loader-overlay[style*="display: flex"] .loader-image,
			.loader-overlay:not([style*="display: none"]) .loader-image {
				width: 80px !important;
				height: 80px !important;
				max-width: 80px !important;
				max-height: 80px !important;
			}

			/* Ensure hidden loader doesn't block interaction */
			.loader-overlay[style*="display: none"] {
				pointer-events: none !important;
			}

			/* When loader is visible, hide buttons underneath */
			.loader-overlay:not([style*="display: none"]) ~ * #shopg-cart-layout-settings .toggle-button {
				pointer-events: none !important;
				opacity: 0.3 !important;
			}

			/* Product Swatches publishing action container - fixed at bottom left */
			#productSwatchesLayout-publishing-action {
				display: flex;
				justify-content: center;
				align-items: center;
				position: fixed;
				bottom: 0px;
				z-index: 98;
				left: 10px;
				background-color: #e8f0f6;
				width: 23.3vw;
				padding: 10px;
				border-radius: 10px;
				padding-top: 15px;
				padding-bottom: 8px;
				border: 1px solid #c7c7c7;
			}

			/* Button styles for Product Swatches */
			#productSwatchesLayout-publishing-action .btn.btn-fullwidth {
				width: 9vw;
				background: #006bba;
				color: white;
				padding: 11px;
				cursor: pointer;
				font-weight: 600;
				border: 1px solid #f4f4f4;
				border-radius: 8px;
				font-size: 1.24vw;
			}

			#productSwatchesLayout-publishing-action .btn.btn-fullwidth:hover {
				background: #005699;
				border-color: #e0e0e0;
			}

			#productSwatchesLayout-publishing-action .btn.btn-fullwidth.btn-secondary {
				background: #dc3545;
				color: white;
				border: none;
			}

			#productSwatchesLayout-publishing-action .btn.btn-fullwidth.btn-secondary:hover {
				background: #c82333;
			}

			/* Responsive CSS for ProductSwatches */
			@media only screen and (max-width: 1400px) {
				#shopg-productSwatches-layout-container {
					width: 65% !important;
				}
			}

			@media only screen and (max-width: 1200px) {
				#shopg-productSwatches-layout-container {
					width: 55% !important;
				}
				#productSwatchesLayout-publishing-action {
					width: 30vw !important;
				}
				#productSwatchesLayout-publishing-action .btn.btn-fullwidth {
					width: 13vw !important;
				}
			}

			@media only screen and (max-width: 992px) {
				#shopg-productSwatches-layout-container {
					width: 50% !important;
				}
				#productSwatchesLayout-publishing-action {
					width: 38vw !important;
				}
				#productSwatchesLayout-publishing-action .btn.btn-fullwidth {
					width: 16vw !important;
				}
			}

			@media only screen and (max-width: 782px) {
				#shopg-productSwatches-layout-container {
					width: 65.5% !important;
				}
				#productSwatchesLayout-publishing-action {
					width: 50vw !important;
				}
				#productSwatchesLayout-publishing-action .btn.btn-fullwidth {
					width: 21vw !important;
				}
			}
		</style>

		<script>
		jQuery(document).ready(function($) {
			// Dynamic template loading functionality
			<?php if (!empty($layout_template)): ?>
			// Add template identifier to body for CSS targeting
			$('body').addClass('shopglut-template-<?php echo esc_js($layout_template); ?>');
			<?php endif; ?>

			// Handle template switching if there's a template selector
			$(document).on('change', '#layout_template, select[name="layout_template"]', function() {
				var selectedTemplate = $(this).val();
				var layoutId = $('#shopg_shop_layoutid').val();

				if (selectedTemplate && layoutId) {
					// Show loading state
					$('#main-loader-overlay').css({'display': 'flex', 'opacity': '1'});

					// Reload page with new template parameter
					var currentUrl = new URL(window.location);
					currentUrl.searchParams.set('layout_id', layoutId);
					currentUrl.searchParams.set('template', selectedTemplate);

					window.location.href = currentUrl.toString();
				}
			});

			// Auto-refresh template settings when template field changes
			$(document).on('change', 'input[name="layout_template"]', function() {
				var newTemplate = $(this).val();

				// Add visual indicator that settings need to be reloaded
				$('#shopg-notification-container').html(
					'<div class="notice notice-info inline">' +
					'<p><?php esc_html_e('Template changed. Please save the layout to reload the appropriate settings.', 'shopglut'); ?></p>' +
					'</div>'
				);
			});
		});
		</script>

		<?php
	}

	public function render() {
		$this->loadProductSwatchesEditor();
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}