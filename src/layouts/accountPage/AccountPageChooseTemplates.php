<?php
namespace Shopglut\layouts\accountPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AccountPageChooseTemplates {

	public function __construct() {

		add_action( 'admin_post_create_accountpage_layout', array( $this, 'handleCreateAccountPageEnhancement' ) );

	}


	public function loadProductAccountPageTemplates() {
		$tab_names = [
			'tab1' => [
				'name' => esc_html__("General", 'shopglut'),
				'templates' => ['template1']
			]
		];

		$template_names = ['template1' => esc_html__("Template One", 'shopglut')];

		// Define template images using the constant
		$template_images = [
			'template1' => 'template1.png'
		];

		?>
		<div class="shopg-tab-container shopg-template-gallery">
			<ul class="shopg-tabs">
				<?php foreach ($tab_names as $tab_id => $tab): ?>
					<li class="shopg-tab" data-tab="<?php echo esc_attr($tab_id); ?>">
						<?php echo esc_html($tab['name']); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php foreach ($tab_names as $tab_id => $tab): ?>
				<div class="shopg-tab-content" id="<?php echo esc_attr($tab_id); ?>">
					<?php foreach ($tab['templates'] as $layout_template): ?>
						<div class="shopg-template-preview" data-template="<?php echo esc_attr($layout_template); ?>">
							<div class="template-header">
								<h2><?php echo wp_kses_post($template_names[$layout_template]) ?? ''; ?></h2>
							</div>

							<div class="template-content product-demo">
								<?php if ($layout_template === 'template1'): ?>
									<!-- HTML Demo Preview for Template 1 -->
									<div class="template-html-container">
										<!-- Background: Scaled HTML Preview -->
										<div class="html-preview-background">
											<?php $this->renderAccountPageTemplate1Preview(); ?>
										</div>

										<!-- Foreground: View Demo Button with Overlay -->
										<div class="html-preview-overlay">
											<button type="button" class="demo-view-btn" onclick="openHtmlDemoModal('<?php echo esc_attr($layout_template); ?>', 'account')">
												<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
													<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
												<span>View Live Demo</span>
											</button>
										</div>
									</div>
								<?php else: ?>
									<!-- Image Preview for other templates -->
									<div class="template-image-container" onclick="openImageModal('<?php echo esc_attr($template_images[$layout_template]); ?>')">
										<!-- Loading Placeholder -->
										<div class="image-loading-placeholder">
											<div class="loading-skeleton">
												<div class="skeleton-shimmer"></div>
											</div>
											<div class="loading-icon">
												<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M21 19V5C21 3.9 20.1 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19ZM8.5 13.5L11 16.51L14.5 12L19 18H5L8.5 13.5Z" fill="#e0e0e0"/>
												</svg>
											</div>
										</div>

										<!-- Template Image (same for preview and modal) -->
										<img src="<?php echo esc_url(SHOPGLUT_URL .'global-assets/images/accountpage_page-templates/'. $template_images[$layout_template]);// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>"
											 alt="<?php echo esc_attr($template_names[$layout_template]); ?>"
											 class="template-preview-image"
											 loading="lazy">

										<!-- Hover Overlay -->
										<div class="image-overlay">
											<div class="expand-icon-container">
												<svg class="expand-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M15 3H21V9M9 21H3V15M21 3L14 10M3 21L10 14" stroke="#333333" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<div class="template-footer">
								<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
									<input type="hidden" name="action" value="create_accountpage_layout">
									<input type="hidden" name="layout_template" value="<?php echo esc_attr($layout_template); ?>">
									<?php wp_nonce_field('create_accountpage_layout_nonce', 'create_accountpage_layout_nonce'); ?>
									<button type="submit" class="choose-template-btn">
										<?php esc_html_e("Choose & Customize", 'shopglut'); ?>
									</button>
								</form>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Image Modal -->
		<div id="imageModal" class="shopglut-template-modal-image-modal" style="display: none;">
			<div class="shopglut-template-modal-modal-content">
				<span class="shopglut-template-modal-close-modal" onclick="closeImageModal()">&times;</span>
				<div class="shopglut-template-modal-modal-body">
					<img id="modalMainImage" src="" alt="Template Preview" class="shopglut-template-modal-modal-image"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				</div>
			</div>
		</div>

		<!-- HTML Demo Modal for Account Page -->
		<div id="shopglut-account-html-demo-modal" class="shopglut-template-modal-image-modal" style="display: none;">
			<div class="shopglut-template-modal-modal-content shopglut-html-demo-modal-content">
				<span class="shopglut-template-modal-close-modal" onclick="closeHtmlDemoModal('account')">&times;</span>
				<div class="shopglut-template-modal-modal-body">
					<div class="html-demo-header">
						<h3 class="html-demo-header-title">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M15 3V7H9V3H15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							My Account Template One - Live Preview
						</h3>
						<p class="html-demo-header-subtitle">Explore the full interactive my account page layout below</p>
					</div>
					<div id="accountPageHtmlDemoContent" class="html-demo-full-view">
						<?php $this->renderAccountPageTemplate1FullDemo(); ?>
					</div>
				</div>
			</div>
		</div>


		<script>
		// Image modal functions
		function openImageModal(imageName) {
			const modal = document.getElementById('imageModal');
			const mainImage = document.getElementById('modalMainImage');
			const assetsUrl = '<?php echo esc_js(SHOPGLUT_URL . "global-assets/images/singleproduct-templates/"); ?>';

			if (!modal || !mainImage) {
				return;
			}

			mainImage.src = assetsUrl + imageName;
			modal.style.setProperty('display', 'flex', 'important');
			modal.style.setProperty('z-index', '999999', 'important');
			document.body.style.overflow = 'hidden';
		}

		function closeImageModal() {
			const modal = document.getElementById('imageModal');
			if (modal) {
				modal.style.setProperty('display', 'none', 'important');
				document.body.style.overflow = 'auto';
			}
		}

		// Image loading handler
		document.addEventListener('DOMContentLoaded', function() {
			const images = document.querySelectorAll('.template-preview-image');
			images.forEach(function(img) {
				if (img.complete && img.naturalHeight !== 0) {
					const placeholder = img.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.style.display = 'none';
					}
					img.classList.add('loaded');
				}

				img.addEventListener('load', function() {
					const placeholder = this.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.style.display = 'none';
					}
					this.classList.add('loaded');
				});

				img.addEventListener('error', function() {
					const placeholder = this.parentElement.querySelector('.image-loading-placeholder');
					if (placeholder) {
						placeholder.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Image not found</div>';
					}
					this.style.display = 'none';
				});
			});
		});

		// Close modals when clicking outside or pressing Escape
		document.addEventListener('click', function(e) {
			const imageModal = document.getElementById('imageModal');
			if (e.target === imageModal) {
				closeImageModal();
			}
		});

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeImageModal();
			}
		});
		</script>
		<?php
	 }

	/**
	 * Render Account Page Template 1 Preview (scaled down for gallery view)
	 */
	private function renderAccountPageTemplate1Preview() {
		// Include the template markup class
		require_once SHOPGLUT_PATH . 'src/layouts/accountPage/templates/template1/template1Markup.php';
		require_once SHOPGLUT_PATH . 'src/layouts/accountPage/templates/template1/template1Style.php';

		$markup = new \Shopglut\layouts\accountPage\templates\template1\template1Markup();
		$style = new \Shopglut\layouts\accountPage\templates\template1\template1Style();

		// Render styles inline
		$style->dynamicCss(0);

		// Render the markup
		$markup->layout_render(array('layout_id' => 0));
	}

	/**
	 * Render Account Page Template 1 Full Demo (for modal view)
	 */
	private function renderAccountPageTemplate1FullDemo() {
		// Include the template markup class
		require_once SHOPGLUT_PATH . 'src/layouts/accountPage/templates/template1/template1Markup.php';
		require_once SHOPGLUT_PATH . 'src/layouts/accountPage/templates/template1/template1Style.php';

		$markup = new \Shopglut\layouts\accountPage\templates\template1\template1Markup();
		$style = new \Shopglut\layouts\accountPage\templates\template1\template1Style();

		// Render styles inline
		$style->dynamicCss(0);

		// Render the markup
		$markup->layout_render(array('layout_id' => 0));
	}

	public function handleCreateAccountPageEnhancement() {


		if (
			!isset($_POST['create_accountpage_layout_nonce']) ||
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_accountpage_layout_nonce'])), 'create_accountpage_layout_nonce') ||
			!current_user_can('manage_options')
		) {
			wp_die('Security check failed', 'Error', ['response' => 403]);
		}

		try {


			// Validate required POST data
			if (!isset($_POST['layout_template'])) {
				wp_die('Missing required fields', 'Error', ['response' => 400]);
			}

			// Database insertion
			global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

			// Prepare data for insertion (without id, let auto_increment handle it)
			$data = array(
				'layout_name' => '',  // Will be updated after insert with the actual ID
				'layout_template' => sanitize_text_field(wp_unslash($_POST['layout_template'])),
				'layout_settings' => '{}', // Default empty JSON object
			);

			// Format specifiers for wpdb
			$format = array(
				'%s',  // layout_name
				'%s',  // layout_template
				'%s',  // layout_settings
			);

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$inserted = $wpdb->insert($table_name, $data, $format);

			if ($inserted === false) {

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				wp_die('Database insertion failed: ' . esc_html($wpdb->last_error), 'Error', ['response' => 500]);
			}

			// Get the auto-generated ID
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$layout_id = $wpdb->insert_id;

			// Update the layout_name with the actual ID
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update(
				$table_name,
				array('layout_name' => 'Layout(#' . $layout_id . ')'),
				array('id' => $layout_id),
				array('%s'),
				array('%d')
			);

			// Redirect on success
			$redirect_url = add_query_arg(
				array(
					'page' => 'shopglut_layouts',
					'editor' => 'accountpage',
					'layout_id' => $layout_id
				),
				admin_url('admin.php')
			);

			wp_safe_redirect($redirect_url);
			exit;

		} catch (Exception $e) {
			wp_die('An error occurred: ' . esc_html($e->getMessage()), 'Error', ['response' => 500]);
		}
	}


	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}