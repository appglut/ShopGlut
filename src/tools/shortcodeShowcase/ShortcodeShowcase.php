<?php
namespace Shopglut\shortcodeShowcase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use Shopglut\shortcodeShowcase\SettingsPage as templatesSettings;

use Shopglut\tools\shortcodeShowcase\ShortcodesShowcaseEntity;


class ShortcodeShowcase {

	public function __construct() {
		add_filter( 'admin_body_class', array( $this, 'shopglutBodyClass' ) );
		
		// Initialize shortcodes
		$this->initializeShortcodes();
	}
	
	private function initializeShortcodes() {
		// Include and initialize the ProductDisplay class for shortcodes
		require_once __DIR__ . '/ProductDisplay.php';
		new ProductDisplay();

		// CategoryShortcode is already loaded in init.php, no need to reload it

		// Register additional shortcodes directly
		add_shortcode( 'shopglut_archive', array( $this, 'render_archive_shortcode' ) );
		add_shortcode( 'shopglut_product_table', array( $this, 'render_product_table_shortcode' ) );
	}

	public function shopglutBodyClass( $classes ) {

		$current_screen = get_current_screen();

		if ( empty( $current_screen ) ) {
			return $classes;
		}

		if ( false !== strpos( $current_screen->id, 'shopglut_' ) ) {

			$classes .= ' shopglut-admin';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter checks for CSS class assignment
		if ( isset( $_GET['page'] ) && 'shopglut_shortcode_showcase' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) &&
		     // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin editor parameter check
		     isset( $_GET['editor'] ) && 'shortcode_showcase' === sanitize_text_field( wp_unslash( $_GET['editor'] ) ) &&
		     // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin template ID parameter check
		     isset( $_GET['template_id'] ) ) {
			$classes .= '-shopglut-shortcode-showcase-template-editor ';
		}

		return $classes;
	}

	public function renderCustomMenuPage() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for page rendering
		if ( isset( $_GET['page'] ) && 'shopglut_shortcode_showcase' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			$this->ShortcodeShowcasePage();
		} else {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'shopglut' ) );
		}
		;

	}

	public function settingsPageHeader( $active_menu ) {
		$logo_url = SHOPGLUT_URL . 'global-assets/images/header-logo.svg';
		?>
		<div class="shopglut-page-header">
			<div class="shopglut-page-header-wrap">
				<div class="shopglut-page-header-banner shopglut-pro shopglut-no-submenu">
					<div class="shopglut-page-header-banner__logo">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
					</div>
					<div class="shopglut-page-header-banner__helplinks">
						<span><a rel="noopener"
								href="https://shopglut.appglut.com/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=adminmenu"
								target="_blank">
								<span class="dashicons dashicons-admin-page"></span>
								<?php echo esc_html__( 'Documentation', 'shopglut' ); ?>
							</a></span>
						<span><a class="shopglut-active" rel="noopener"
								href="https://www.appglut.com/plugin/shopglut/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=upgrade"
								target="_blank">
								<span class="dashicons dashicons-unlock"></span>
								<?php echo esc_html__( 'Unlock Pro Edition', 'shopglut' ); ?>
							</a></span>
						<span><a rel="noopener"
								href="https://www.appglut.com/support/?utm_source=shoglutplugin-admin&utm_medium=referral&utm_campaign=support"
								target="_blank">
								<span class="dashicons dashicons-share-alt"></span>
								<?php echo esc_html__( 'Support', 'shopglut' ); ?>
							</a></span>
					</div>
					<div class="clear"></div>
					<?php $this->settingsPageHeaderMenus( $active_menu ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function ShortcodeShowcasePage($active_menu = 'sshowcase', $allTools = null) {

		$this->settingsPageHeader( $active_menu );
		
		// Render navigation tabs if AllTools instance is provided
		if ($allTools) {
			$allTools->settingsPageHeaderMenus( $active_menu );
		}
		
		$this->renderShortcodeShowcaseContent();
	}
	
	public function renderShortcodeShowcaseContent() {
		$shortcode1_url = SHOPGLUT_URL . 'global-assets/images/shortcode-1.png';
		$shortcode2_url = SHOPGLUT_URL . 'global-assets/images/shortcode-2.png';

		//$shopLayout_templates = new ShopLayoutTemplates();
		?>
		<div class="wrap shopglut-admin-contents">
			<h2 style="text-align: center; font-weight: bold;"><?php echo esc_html__( 'Shortcode Showcase', 'shopglut' ); ?></h2>
			<p class="subheading" style="text-align: center; margin-bottom:30px; margin-top:8px"><?php echo esc_html__( 'Display WooCommerce content with powerful shortcodes', 'shopglut' ); ?></p>
			
			<div class="shopg-tab-container">
			<ul class="shopg-tabs">
				<li class="shopg-tab active" data-tab="tab1"><?php echo esc_html__( 'All Shortcodes', 'shopglut' ) ?> </li>
				<!-- <li class="shopg-tab" data-tab="tab2"><?php echo esc_html__( 'Shortcode Templates', 'shopglut' ) ?> </li> -->

			</ul>

			<div class="shopg-tab-content active" id="tab1">
				<div class="image-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

					<?php $this->renderShortcodeCard( '[shopglut_woo_category id="category-slug" template="template-id"]', 'WooCommerce Category Products', 'Query and display all products from one or more WooCommerce categories with advanced filtering, sorting, and pagination. Uses WooTemplates for product display.' ); ?>

				</div>
			</div>

			<div class="shopg-tab-content" id="tab2">
				<?php //$this->renderSShowcaseTable(); ?>
			</div>

		</div>

		<script>
		// Generate WooTemplates admin URL for use in JavaScript
		var wooTemplatesUrl = '<?php echo esc_js( admin_url( 'admin.php?page=shopglut_tools&view=woo_templates' ) ); ?>';

		function copyShortcode(shortcode) {
			// Check if clipboard API is available
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(shortcode).then(function() {
					showCopyNotification('Shortcode copied to clipboard!', true);
				}).catch(function(err) {
					console.error('Failed to copy: ', err);
					fallbackCopyTextToClipboard(shortcode);
				});
			} else {
				// Fallback for older browsers
				fallbackCopyTextToClipboard(shortcode);
			}
		}

		function fallbackCopyTextToClipboard(text) {
			var textArea = document.createElement("textarea");
			textArea.value = text;
			textArea.style.position = "fixed";
			textArea.style.top = "0";
			textArea.style.left = "0";
			textArea.style.width = "2em";
			textArea.style.height = "2em";
			textArea.style.padding = "0";
			textArea.style.border = "none";
			textArea.style.outline = "none";
			textArea.style.boxShadow = "none";
			textArea.style.background = "transparent";
			document.body.appendChild(textArea);
			textArea.focus();
			textArea.select();

			try {
				var successful = document.execCommand('copy');
				showCopyNotification(successful ? 'Shortcode copied to clipboard!' : 'Failed to copy shortcode', successful);
			} catch (err) {
				console.error('Fallback: Could not copy text: ', err);
				showCopyNotification('Failed to copy shortcode', false);
			}

			document.body.removeChild(textArea);
		}

		function showCopyNotification(message, isSuccess) {
			var notification = document.createElement('div');
			notification.innerHTML = message;
			notification.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: ' + (isSuccess ? '#4CAF50' : '#f44336') + '; color: white; padding: 10px 20px; border-radius: 4px; z-index: 9999; font-size: 14px;';
			document.body.appendChild(notification);
			setTimeout(function() {
				if (document.body.contains(notification)) {
					document.body.removeChild(notification);
				}
			}, 2000);
		}
		
		function previewShortcode(shortcode, title) {
			// Get demo image URL
			var demoImageUrl = '<?php echo esc_js(SHOPGLUT_URL . 'global-assets/images/demo-image.png'); ?>';

			// Create preview modal
			var modal = document.createElement('div');
			modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px;';

			var content = document.createElement('div');
			content.style.cssText = 'background: #fff; border-radius: 12px; max-width: 1200px; width: 100%; max-height: 90vh; overflow: hidden; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.3);';

			var html = '<div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">' +
					   '<button onclick="document.body.removeChild(this.closest(\'div\').parentNode.parentNode)" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; width: 32px; height: 32px; border-radius: 6px; font-size: 20px; cursor: pointer; color: #6b7280; transition: all 0.2s;" onmouseover="this.style.background=\'#f3f4f6\'; this.style.color=\'#111827\';" onmouseout="this.style.background=\'transparent\'; this.style.color=\'#6b7280\';">&times;</button>' +
					   '<h2 style="margin: 0 40px 8px 0; font-size: 20px; font-weight: 600; color: #111827;">' + title + ' - Preview</h2>' +
					   '<p style="margin: 0; color: #6b7280; font-size: 14px;">Demo preview of complete shortcode output</p>' +
					   '</div>' +
					   '<div style="padding: 30px; overflow-y: auto; max-height: calc(90vh - 120px); background: #f8fafc;">' +
					   '<div class="shopglut-preview-wrapper">' +

					   // Filter Toolbar - matches actual frontend
					   '<div class="shopglut-toolbar-preview" style="border: 1px solid rgba(0,0,0,0.125); border-radius: 4px; margin-bottom: 24px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">' +
					   '<div style="padding: 12px 20px; background-color: rgba(0,0,0,0.03); border-top: 1px solid rgba(0,0,0,0.125);">' +
					   '<div style="display: flex; flex-wrap: wrap; margin: 0 -15px; align-items: center;">' +
					   // Search field (flexible width)
					   '<div style="flex: 1 1 auto; padding: 0 15px; margin-bottom: 10px; min-width: 200px;">' +
					   '<input type="text" placeholder="Search Keyword..." style="display: block; width: 100%; height: calc(1.5em + 0.75rem + 2px); padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; box-sizing: border-box;">' +
					   '</div>' +
					   // Order By dropdown
					   '<div style="flex: 0 0 auto; padding: 0 15px; margin-bottom: 10px; min-width: 180px;">' +
					   '<select style="display: block; width: 100%; height: calc(1.5em + 0.75rem + 2px); padding: 0.375rem 1.75rem 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-image: url(\'data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'4\' height=\'5\' viewBox=\'0 0 4 5\'%3e%3cpath fill=\'%23343a40\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/%3e%3c/svg%3e\'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 8px 10px; border: 1px solid #ced4da; border-radius: 0.25rem; appearance: none; box-sizing: border-box; cursor: pointer;">' +
					   '<option disabled>Order By:</option>' +
					   '<option selected>Publish Date</option>' +
					   '<option>Title</option>' +
					   '<option>Update Date</option>' +
					   '<option>Sales</option>' +
					   '<option>Menu Order</option>' +
					   '<option>Price</option>' +
					   '</select>' +
					   '</div>' +
					   // Order direction dropdown
					   '<div style="flex: 0 0 auto; padding: 0 15px; margin-bottom: 10px; min-width: 160px;">' +
					   '<select style="display: block; width: 100%; height: calc(1.5em + 0.75rem + 2px); padding: 0.375rem 1.75rem 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-image: url(\'data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'4\' height=\'5\' viewBox=\'0 0 4 5\'%3e%3cpath fill=\'%23343a40\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/%3e%3c/svg%3e\'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 8px 10px; border: 1px solid #ced4da; border-radius: 0.25rem; appearance: none; box-sizing: border-box; cursor: pointer;">' +
					   '<option disabled>Order:</option>' +
					   '<option selected>Descending</option>' +
					   '<option>Ascending</option>' +
					   '</select>' +
					   '</div>' +
					   // Apply button
					   '<div style="flex: 0 0 auto; padding: 0 15px; margin-bottom: 10px; min-width: 140px;">' +
					   '<button type="submit" style="display: block; width: 100%; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; border-radius: 0.25rem; color: #fff; background-color: #6c757d; border: 1px solid #6c757d; text-align: center; cursor: pointer; box-sizing: border-box; font-weight: 400;" onmouseover="this.style.backgroundColor=\'#5a6268\'; this.style.borderColor=\'#545b62\';" onmouseout="this.style.backgroundColor=\'#6c757d\'; this.style.borderColor=\'#6c757d\';">Apply Filter</button>' +
					   '</div>' +
					   '</div>' + // End flex row
					   '</div>' + // End panel-footer
					   '</div>' + // End panel


					   // Demo product grid with 3 columns
					   '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">' +
					   // Product 1
					   '<div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.15)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\';">' +
					   '<img src="' + demoImageUrl + '" alt="Product" style="width: 100%; height: 200px; object-fit: cover;">' +
					   '<div style="padding: 16px;">' +
					   '<h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Premium Product 1</h3>' +
					   '<p style="margin: 0 0 12px 0; color: #10b981; font-size: 18px; font-weight: 600;">$29.99</p>' +
					   '<button style="width: 100%; padding: 10px; background: #2271b1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity=\'0.9\'" onmouseout="this.style.opacity=\'1\'">Add to Cart</button>' +
					   '</div></div>' +
					   // Product 2
					   '<div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.15)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\';">' +
					   '<img src="' + demoImageUrl + '" alt="Product" style="width: 100%; height: 200px; object-fit: cover;">' +
					   '<div style="padding: 16px;">' +
					   '<h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Premium Product 2</h3>' +
					   '<p style="margin: 0 0 12px 0; color: #10b981; font-size: 18px; font-weight: 600;">$49.99</p>' +
					   '<button style="width: 100%; padding: 10px; background: #2271b1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity=\'0.9\'" onmouseout="this.style.opacity=\'1\'">Add to Cart</button>' +
					   '</div></div>' +
					   // Product 3
					   '<div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.15)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\';">' +
					   '<img src="' + demoImageUrl + '" alt="Product" style="width: 100%; height: 200px; object-fit: cover;">' +
					   '<div style="padding: 16px;">' +
					   '<h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Premium Product 3</h3>' +
					   '<p style="margin: 0 0 12px 0; color: #10b981; font-size: 18px; font-weight: 600;">$39.99</p>' +
					   '<button style="width: 100%; padding: 10px; background: #2271b1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity=\'0.9\'" onmouseout="this.style.opacity=\'1\'">Add to Cart</button>' +
					   '</div></div>' +
					   '</div>' +

					   // Pagination
					   '<div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 20px; display: flex; justify-content: center; align-items: center; gap: 8px;">' +
					   '<button style="padding: 8px 12px; background: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Previous</button>' +
					   '<button style="padding: 8px 14px; background: #2271b1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">1</button>' +
					   '<button style="padding: 8px 14px; background: #f9fafb; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">2</button>' +
					   '<button style="padding: 8px 14px; background: #f9fafb; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">3</button>' +
					   '<button style="padding: 8px 12px; background: #2271b1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Next</button>' +
					   '</div>' +

					   // Info note
					   '<div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; text-align: center;">' +
					   '<p style="margin: 0; color: #1e40af; font-size: 13px; line-height: 1.6;"><strong>Note:</strong> This is a demo preview showing the complete shortcode appearance including the filter toolbar, product grid, and pagination. The actual shortcode will display your real products with fully functional filtering, sorting, search, and pagination features.</p>' +
					   '</div>' +
					   '</div>' + // End shopglut-preview-wrapper
					   '</div>';

			content.innerHTML = html;
			modal.appendChild(content);
			document.body.appendChild(modal);

			// Close on click outside
			modal.addEventListener('click', function(e) {
				if (e.target === modal) {
					document.body.removeChild(modal);
				}
			});
		}

		function previewShortcodeDetails(shortcode, title, description) {
			// Create modal overlay
			var modal = document.createElement('div');
			modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);';

			var content = document.createElement('div');
			content.style.cssText = 'background: #fff; border-radius: 16px; max-width: 1200px; width: 100%; max-height: 90vh; overflow: hidden; position: relative; box-shadow: 0 20px 60px rgba(0,0,0,0.3);';

			// Modal header
			var header = '<div style="background: #667eea; padding: 30px; color: white;">' +
						 '<button onclick="document.body.removeChild(this.closest(\'div\').parentNode.parentNode)" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 24px; cursor: pointer; color: white; transition: all 0.2s;" onmouseover="this.style.background=\'rgba(255,255,255,0.3)\'" onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">&times;</button>' +
						 '<h2 style="margin: 0 40px 10px 0; font-size: 28px; font-weight: 600; color: white;">' + title + '</h2>' +
						 '<p style="margin: 0; font-size: 15px; line-height: 1.6; color: white;">' + description + '</p>' +
						 '</div>';

			// Modal body - NO TABS, single scrollable content
			var body = '<div style="overflow-y: auto; max-height: calc(90vh - 150px); padding: 40px;">' +

					   // Shortcode Syntax Section
					   '<h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 600; color: #1e293b;">Shortcode Syntax</h3>' +
					   '<div style="background: #1e293b; padding: 20px; border-radius: 8px; margin-bottom: 32px;">' +
					   '<code style="color: #10b981; font-family: \'Monaco\', \'Courier New\', monospace; font-size: 14px; line-height: 1.8; word-break: break-all;">' + shortcode + '</code>' +
					   '</div>' +

					   // How to Use Section
					   '<div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 20px; margin-bottom: 32px;">' +
					   '<p style="margin: 0; color: #0c4a6e; font-size: 14px; line-height: 1.6;"><strong style="color: #075985;">💡 How to use:</strong> Copy the shortcode above and paste it into any WordPress page, post, or widget area where you want to display products from WooCommerce categories.</p>' +
					   '</div>' +

					   // Common Use Cases Section
					   '<h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #1e293b;">Common Use Cases</h4>' +
					   '<ul style="margin: 0 0 32px 0; padding-left: 24px; color: #475569; line-height: 2;">' +
					   '<li>Display products from specific categories on custom pages</li>' +
					   '<li>Create filtered product showcases with search and sorting</li>' +
					   '<li>Build category-specific product catalogs with pagination</li>' +
					   '<li>Use custom WooTemplates for unique product displays</li>' +
					   '</ul>' +

					   // Example Section
					   '<h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #1e293b;">Example Usage</h4>' +
					   '<div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 20px; margin-bottom: 32px;">' +
					   '<code style="color: #78350f; font-family: monospace; font-size: 13px; word-break: break-all;">[shopglut_woo_category id="electronics" template="my-template" cols="4" items_per_page="12"]</code>' +
					   '</div>' +

					   // Available Parameters Section
					   '<h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 600; color: #1e293b;">Available Parameters</h3>' +
					   '<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">' +
					   '<table style="width: 100%; border-collapse: collapse;">' +
					   '<thead><tr style="background: #f1f5f9;"><th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Parameter</th><th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Description</th><th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Default</th></tr></thead>' +
					   '<tbody>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">id</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;"><strong>Required.</strong> Category slug(s), comma-separated</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">-</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">template</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">WooTemplate ID for custom product display</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">-</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">cols</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Desktop columns (1-12)</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">1</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">colspad</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Tablet columns (1-12)</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">1</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">colsphone</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Mobile columns (1-12)</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">1</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">items_per_page</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Products per page</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">10</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">orderby</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Sort by: date, title, modified, price, total_sales, menu_order</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">date</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">order</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Sort direction: ASC or DESC</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">DESC</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">toolbar</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Show toolbar: 1 (full), 0 (hidden), compact</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">1</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">paging</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Show pagination: 1 or 0</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">1</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">async</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Async pagination: 1 or 0</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">0</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">operator</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Category operator: IN, NOT IN, AND</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">IN</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">title</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Show category title: "1" or custom text</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">-</td></tr>' +
					   '<tr><td style="padding: 14px 16px; border-bottom: 1px solid #e2e8f0;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">desc</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">Show category description: "1" or custom text</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px; border-bottom: 1px solid #e2e8f0;">-</td></tr>' +
					   '<tr><td style="padding: 14px 16px;"><code style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">icon</code></td><td style="padding: 14px 16px; color: #64748b; font-size: 13px;">Custom icon URL</td><td style="padding: 14px 16px; color: #64748b; font-size: 13px;">Category thumbnail</td></tr>' +
					   '</tbody></table>' +
					   '</div>' +

					   // WooTemplates Link Section
					   '<div style="background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 8px; padding: 20px; margin-bottom: 20px;">' +
					   '<p style="margin: 0 0 12px 0; color: #075985; font-size: 14px; line-height: 1.6;"><strong style="color: #0c4a6e;">🎨 Custom Templates:</strong> You can create custom product display templates using the WooTemplates feature. Use the template parameter with your template ID to apply custom styling.</p>' +
					   '<a href="' + wooTemplatesUrl + '" target="_blank" style="display: inline-block; padding: 10px 18px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor=\'#0369a1\'" onmouseout="this.style.backgroundColor=\'#0284c7\'">Manage WooTemplates &rarr;</a>' +
					   '</div>' +

					   '</div>';

			content.innerHTML = header + body;
			modal.appendChild(content);
			document.body.appendChild(modal);

			// Close on click outside
			modal.addEventListener('click', function(e) {
				if (e.target === modal) {
					document.body.removeChild(modal);
				}
			});
		}
		
		// Add hover effects for cards
		document.addEventListener('DOMContentLoaded', function() {
			var cards = document.querySelectorAll('.shortcode-card');
			cards.forEach(function(card) {
				card.addEventListener('mouseenter', function() {
					this.style.borderColor = '#d1d5db';
					this.style.boxShadow = '0 4px 16px rgba(0,0,0,0.08)';
				});
				card.addEventListener('mouseleave', function() {
					this.style.borderColor = '#e5e7eb';
					this.style.boxShadow = 'none';
				});
			});
		});
		</script>
		
		
		<?php

	}

	public function settingsPageHeaderMenus( $active_menu ) {
		$menus = $this->headerMenuTabs();

		if ( count( $menus ) < 2 ) {
			return;
		}

		?>
		<div class="shopglut-header-menus">
			<nav class="shopglut-nav-tab-wrapper nav-tab-wrapper">
				<?php foreach ( $menus as $menu ) : ?>
					<?php
					$id = $menu['id'];
					$url = esc_url_raw( ! empty( $menu['url'] ) ? $menu['url'] : '' );
					?>
					<a href="<?php echo esc_url( remove_query_arg( wp_removable_query_args(), $url ) ); ?>"
						class="shopglut-nav-tab nav-tab<?php echo esc_attr( $id ) == esc_attr( $active_menu ) ? ' shopglut-nav-active' : ''; ?>">
						<?php echo esc_html( $menu['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
		<?php
	}

	public function defaultHeaderMenu() {
		return 'sshowcase';
	}

	public function headerMenuTabs() {
		$tabs = [ 
			5 => [ 'id' => 'sshowcase', 'url' => admin_url( 'admin.php?page=shopglut_shortcode_showcase' ), 'label' => esc_html__( 'Shortcode Showcase', 'shopglut' ) ]
		];

		return $tabs;
	}

	public function activeMenuTab() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter checks for menu tab display
		if ( isset( $_GET['page'] ) && ( strpos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'shopglut' ) !== false ) ) {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin view parameter for menu tab
			return isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : $this->defaultHeaderMenu();
		}

		return false;
	}

	// Helper method to render shortcode cards
	private function renderShortcodeCard( $shortcode, $title, $description ) {
		?>
		<div class="shortcode-card" style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #ffffff; transition: all 0.25s ease;">
			<!-- Card Header -->
			<div class="shortcode-card-header" style="padding: 20px 20px 16px;">
				<h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">
					<?php echo esc_html( $title ); ?>
				</h3>
				<p style="margin: 0; color: #6b7280; font-size: 13px; line-height: 1.5;">
					<?php echo esc_html( $description ); ?>
				</p>
			</div>

			<!-- Shortcode Display with Copy Icon -->
			<div class="shortcode-display" style="padding: 0 20px 16px;">
				<div style="display: flex; align-items: center; gap: 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px;">
					<code style="flex: 1; font-family: 'Menlo', 'Monaco', 'Courier New', monospace; font-size: 12px; color: #374151; overflow-x: auto; white-space: nowrap;"><?php echo esc_html( $shortcode ); ?></code>
					<button type="button" class="copy-btn" onclick="copyShortcode('<?php echo esc_js( $shortcode ); ?>')" title="<?php echo esc_attr__( 'Copy shortcode', 'shopglut' ); ?>" style="min-width: 32px; height: 32px; background: transparent; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; padding: 0;" onmouseover="this.style.background='#f3f4f6'; this.style.borderColor='#9ca3af';" onmouseout="this.style.background='transparent'; this.style.borderColor='#d1d5db';">
						<span class="dashicons dashicons-admin-page" style="font-size: 16px; width: 16px; height: 16px; color: #6b7280;"></span>
					</button>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="shortcode-actions" style="padding: 0 20px 20px; display: flex; gap: 8px;">
				<button type="button" onclick="previewShortcodeDetails('<?php echo esc_js( $shortcode ); ?>', '<?php echo esc_js( $title ); ?>', '<?php echo esc_js( $description ); ?>')" style="flex: 1; height: 36px; background: #ffffff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px;" onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#d1d5db';">
					<span class="dashicons dashicons-info-outline" style="font-size: 16px; width: 16px; height: 16px;"></span>
					<?php echo esc_html__( 'Details', 'shopglut' ); ?>
				</button>
				<button type="button" onclick="previewShortcode('<?php echo esc_js( $shortcode ); ?>', '<?php echo esc_js( $title ); ?>')" style="flex: 1; height: 36px; background: #2271b1; color: #ffffff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
					<span class="dashicons dashicons-welcome-view-site" style="font-size: 16px; width: 16px; height: 16px;"></span>
					<?php echo esc_html__( 'Preview', 'shopglut' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	// Shortcode render methods
	public function render_archive_shortcode( $atts ) {
		// Basic archive shortcode - show latest products
		$atts = shortcode_atts( array(
			'limit' => 12,
			'columns' => 4,
			'orderby' => 'date',
			'order' => 'DESC',
		), $atts, 'shopglut_archive' );

		if ( ! function_exists( 'wc_get_products' ) ) {
			return '<p>' . esc_html__( 'WooCommerce is required for this shortcode.', 'shopglut' ) . '</p>';
		}

		$products = wc_get_products( array(
			'limit' => intval( $atts['limit'] ),
			'orderby' => sanitize_text_field( $atts['orderby'] ),
			'order' => sanitize_text_field( $atts['order'] ),
			'status' => 'publish',
		) );

		if ( empty( $products ) ) {
			return '<p>' . esc_html__( 'No products found.', 'shopglut' ) . '</p>';
		}

		$columns = intval( $atts['columns'] );
		$output = '<div class="shopglut-archive-grid" style="display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: 20px; margin: 20px 0;">';

		foreach ( $products as $product ) {
			$output .= '<div class="shopglut-product-item" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">';
			$output .= '<h4><a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a></h4>';
			$output .= '<p class="price">' . $product->get_price_html() . '</p>';
			$output .= '<p>' . wp_trim_words( $product->get_short_description(), 15 ) . '</p>';
			$output .= '</div>';
		}

		$output .= '</div>';
		return $output;
	}

	public function render_product_table_shortcode( $atts ) {
		// Basic product table shortcode
		$atts = shortcode_atts( array(
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC',
		), $atts, 'shopglut_product_table' );

		if ( ! function_exists( 'wc_get_products' ) ) {
			return '<p>' . esc_html__( 'WooCommerce is required for this shortcode.', 'shopglut' ) . '</p>';
		}

		$products = wc_get_products( array(
			'limit' => intval( $atts['limit'] ),
			'orderby' => sanitize_text_field( $atts['orderby'] ),
			'order' => sanitize_text_field( $atts['order'] ),
			'status' => 'publish',
		) );

		if ( empty( $products ) ) {
			return '<p>' . esc_html__( 'No products found.', 'shopglut' ) . '</p>';
		}

		$output = '<div class="shopglut-product-table" style="overflow-x: auto;">';
		$output .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
		$output .= '<thead><tr style="background: #f5f5f5;">';
		$output .= '<th style="padding: 10px; border: 1px solid #ddd;">' . esc_html__( 'Product', 'shopglut' ) . '</th>';
		$output .= '<th style="padding: 10px; border: 1px solid #ddd;">' . esc_html__( 'Price', 'shopglut' ) . '</th>';
		$output .= '<th style="padding: 10px; border: 1px solid #ddd;">' . esc_html__( 'Stock', 'shopglut' ) . '</th>';
		$output .= '<th style="padding: 10px; border: 1px solid #ddd;">' . esc_html__( 'Action', 'shopglut' ) . '</th>';
		$output .= '</tr></thead><tbody>';

		foreach ( $products as $product ) {
			$output .= '<tr>';
			$output .= '<td style="padding: 10px; border: 1px solid #ddd;"><a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a></td>';
			$output .= '<td style="padding: 10px; border: 1px solid #ddd;">' . $product->get_price_html() . '</td>';
			$output .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ( $product->is_in_stock() ? esc_html__( 'In Stock', 'shopglut' ) : esc_html__( 'Out of Stock', 'shopglut' ) ) . '</td>';
			$output .= '<td style="padding: 10px; border: 1px solid #ddd;"><a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'View', 'shopglut' ) . '</a></td>';
			$output .= '</tr>';
		}

		$output .= '</tbody></table></div>';
		return $output;
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}