<?php
namespace Shopglut\wooTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\tools\wooTemplates\WooTemplatesEntity;

class SettingsPage {
	private $menu_slug = 'shopglut_tools';
	private $template_tags = [ 
		'product' => [ 
			'[product_title]' => 'Product Title - Displays the main product title',
			'[product_price]' => 'Product Price - Shows current price (sale or regular)',
			'[product_regular_price]' => 'Regular Price - Displays the regular product price',
			'[product_sale_price]' => 'Sale Price - Shows the discounted price when on sale',
			'[product_short_description]' => 'Short Description - Brief product summary',
			'[product_description]' => 'Full Description - Complete product details',
			'[product_image]' => 'Product Image - Main product featured image',
			'[product_gallery]' => 'Product Gallery - All product images as a gallery',
			'[product_sku]' => 'Product SKU - Stock keeping unit identifier',
			'[product_stock]' => 'Stock Status - Shows if product is in stock',
			'[product_categories]' => 'Categories - List of product categories',
			'[product_tags]' => 'Tags - List of product tags'
		],
		'buttons' => [ 
			'[btn_cart]' => 'Add to Cart Button - Button to add product to cart',
			'[btn_view]' => 'View Product Button - Link to product page',
			'[btn_wishlist]' => 'Add to Wishlist Button - Save product to wishlist',
			'[btn_compare]' => 'Compare Button - Add product to comparison list'
		],
		'ratings' => [ 
			'[product_rating]' => 'Product Rating - Star rating display',
			'[product_rating_count]' => 'Rating Count - Number of customer reviews'
		],
		'attributes' => [ 
			'[product_attributes]' => 'All Attributes - Complete list of product attributes',
			'[product_dimensions]' => 'Dimensions - Product size information',
			'[product_weight]' => 'Weight - Product weight information'
		]
	];

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueEditorScripts' ) );
		add_action( 'wp_ajax_save_woo_template', array( $this, 'ajaxSaveTemplate' ) );
		add_action( 'admin_init', array( $this, 'handleTemplateActions' ) );
	}

	/**
	 * Enqueue scripts and styles for the template editor
	 */
	public function enqueueEditorScripts( $hook ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for script loading
		if ( ! isset( $_GET['page'] ) || $this->menu_slug !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for editor type verification
		if ( ! isset( $_GET['editor'] ) || 'woo_template' !== sanitize_text_field( wp_unslash( $_GET['editor'] ) ) ) {
			return;
		}

		// Enqueue WordPress code editor assets
		$code_editor_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// Enqueue custom CSS file
		$css_url = SHOPGLUT_URL . 'src/tools/wooTemplates/assets/template-editor.css';
		$css_version = '3.0.0.' . time();

		wp_enqueue_style(
			'shopglut-template-editor',
			$css_url,
			array(),
			$css_version
		);

		// Debug: Output CSS URL as HTML comment
		add_action( 'admin_footer', function() use ( $css_url, $css_version ) {
			echo "\n<!-- Shopglut Template Editor CSS: " . esc_html( $css_url ) . "?ver=" . esc_html( $css_version ) . " -->\n";
		} );
	}

	/**
	 * Save template data from form submission
	 */
	private function saveTemplate( $post_data, $template_id = null ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_woo_templates';

		// Get form data
		$template_name = isset( $post_data['template_name'] ) ? sanitize_text_field( $post_data['template_name'] ) : '';
		$template_slug = isset( $post_data['template_slug'] ) ? sanitize_key( $post_data['template_slug'] ) : '';
		$template_html = isset( $post_data['template_html'] ) ? wp_unslash( $post_data['template_html'] ) : '';
		$template_css = isset( $post_data['template_css'] ) ? wp_unslash( $post_data['template_css'] ) : '';
		$template_tags = isset( $post_data['template_tags'] ) ? wp_unslash( $post_data['template_tags'] ) : json_encode( $this->template_tags );

		// Validate required fields
		if ( empty( $template_name ) || empty( $template_slug ) ) {
			add_settings_error( 'shopglut_templates', 'required-fields', esc_html__( 'Template name and ID are required.', 'shopglut' ), 'error' );
			return false;
		}

		// Check if template ID already exists for a different template
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for template validation
		$existing_template = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE template_id = %s AND id != %d",
			$template_slug,
			$template_id ? $template_id : 0
		) );

		if ( $existing_template ) {
			add_settings_error( 'shopglut_templates', 'duplicate-template-id', esc_html__( 'Template ID must be unique. This ID is already in use.', 'shopglut' ), 'error' );
			return false;
		}

		// Prepare data for database
		$data = array(
			'template_name' => $template_name,
			'template_id' => $template_slug,
			'template_html' => $template_html,
			'template_css' => $template_css,
			'template_tags' => $template_tags,
			'updated_at' => current_time( 'mysql' )
		);

		$success = false;

		if ( $template_id ) {
			// Check if the record exists based on the primary key (id)
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query to check template existence for update
			$existing_record = $wpdb->get_row( $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE id = %d",
				$template_id
			) );

			if ( $existing_record ) {
				// Update existing template
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function
				$success = $wpdb->update( $table_name, $data, array( 'id' => $template_id ) );
			} else {
				// Insert new template
				$data['created_at'] = current_time( 'mysql' );
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table insert operation
				$success = $wpdb->insert( $table_name, $data );
				$template_id = $wpdb->insert_id;
			}
		} else {
			// Insert new template
			$data['created_at'] = current_time( 'mysql' );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table insert operation
			$success = $wpdb->insert( $table_name, $data );
			$template_id = $wpdb->insert_id;
		}

		if ( $success ) {
			add_settings_error( 'shopglut_templates', 'template-saved', esc_html__( 'Template saved successfully.', 'shopglut' ), 'success' );
			return $template_id;
		} else {
			add_settings_error( 'shopglut_templates', 'save-failed', esc_html__( 'Failed to save template.', 'shopglut' ), 'error' );
			return false;
		}
	}

	/**
	 * Display the template editor page
	 */
	public function templateEditorPage() {
		global $wpdb;

		// Get template_id from the URL
		$template_id = isset( $_GET['template_id'] ) ? absint( $_GET['template_id'] ) : 0;
		$template = null;

		// If template_id is provided and not 0, fetch the template data from the database
		if ( $template_id > 0 ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query to fetch template data for editing
			$template = $wpdb->get_row( $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE id = %d",
				$template_id
			), ARRAY_A ); // Fetch as associative array
		}

		// Handle form submission
		if ( isset( $_POST['save_template'] ) && isset( $_POST['template_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['template_nonce'] ) ), 'save_woo_template' ) ) {
			$saved_template_id = $this->saveTemplate( $_POST, $template_id > 0 ? $template_id : null );
			if ( $saved_template_id && $template_id === 0 ) {
				// Redirect to edit page for the newly created template
				wp_safe_redirect( admin_url( 'admin.php?page=' . $this->menu_slug . '&editor=woo_template&template_id=' . $saved_template_id ) );
				exit;
			}
			// If updating existing template, set the template_id for display
			if ( $saved_template_id ) {
				$template_id = $saved_template_id;
				// Refresh template data after save
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query to fetch updated template data
				$template = $wpdb->get_row( $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE id = %d",
					$template_id
				), ARRAY_A );
			}
		}

		// Get template data
		$template_name = $template ? $template['template_name'] : '';
		$template_slug = $template ? $template['template_id'] : '';
		$template_html = $template ? $template['template_html'] : $this->getDefaultTemplateHTML();
		$template_css = $template ? $template['template_css'] : $this->getDefaultTemplateCSS();
		$template_tags_json = $template ? $template['template_tags'] : json_encode( $this->template_tags );

		// Display the editor interface
		$this->displayEditorInterface( $template_id, $template_name, $template_slug, $template_html, $template_css, $template_tags_json );
	}
	/**
	 * Display the template editor interface
	 */
	private function displayEditorInterface( $template_id, $template_name, $template_slug, $template_html, $template_css, $template_tags_json ) {
		$is_new = ! $template_id;
		$page_title = $is_new ? esc_html__( 'Add New Template', 'shopglut' ) : esc_html__( 'Edit Template', 'shopglut' );

		// Display settings errors
		settings_errors( 'shopglut_templates' );
		?>
		<div class="wrap shopglut-admin-contents woo-templates-editor">
			
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->menu_slug . '&view=woo_templates' ) ); ?>"
				class="page-title-action"><?php echo esc_html__( 'Back to Templates', 'shopglut' ); ?></a>
				<h1 class="wp-heading-inline"><?php echo esc_html( $page_title ); ?></h1>
			<hr class="wp-header-end">

			<form method="post" id="template-editor-form">
				<?php wp_nonce_field( 'save_woo_template', 'template_nonce' ); ?>

				<!-- Template Settings Header Section -->
				<div class="shopglut-template-settings">
					<div class="shopglut-template-settings-row">
						<div class="shopglut-template-setting">
							<label for="template_name"><?php echo esc_html__( 'Template Name', 'shopglut' ); ?></label>
							<input type="text" id="template_name" name="template_name"
								value="<?php echo esc_attr( $template_name ); ?>" required>
						</div>

						<div class="shopglut-template-setting">
							<label for="template_slug"><?php echo esc_html__( 'Template ID', 'shopglut' ); ?></label>
							<input type="text" id="template_slug" name="template_slug"
								value="<?php echo esc_attr( $template_slug ); ?>" required>
							<p class="description">
								<?php echo esc_html__( 'Used in shortcode: [shopglut_template id="template-id"]', 'shopglut' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div id="editor-success-message"></div>

				<!-- Save Button Before Editor Container -->
				<div class="shopglut-template-save-top">
					<button type="submit" name="save_template" class="button button-primary button-large">
						<?php echo esc_html__( 'Save Template', 'shopglut' ); ?>
					</button>
					<div id="shopglut-save-message" class="shopglut-save-message" style="display: none;">
						<span class="success"><i class="dashicons dashicons-yes"></i>
							<?php echo esc_html__( 'Template saved successfully!', 'shopglut' ); ?></span>
					</div>
				</div>

				<div class="shopglut-template-editor-container">
					<!-- Main Editor Area - Left Column (Wider) -->
					<div class="shopglut-template-editor-main">
						<div class="shopglut-template-editor-tabs">
							<button type="button" class="shopglut-template-editor-tab active"
								data-tab="html"><?php echo esc_html__( 'HTML', 'shopglut' ); ?></button>
							<button type="button" class="shopglut-template-editor-tab"
								data-tab="css"><?php echo esc_html__( 'CSS', 'shopglut' ); ?></button>
						</div>

						<div class="shopglut-template-editor-content">
							<div class="shopglut-template-editor-panel active" data-panel="html">
								<?php
								// HTML Editor
								$html_editor_args = array(
									'id' => 'template_html',
									'title' => esc_html__( 'HTML Template', 'shopglut' ),
									'settings' => array(
										'theme' => 'monokai',
										'mode' => 'htmlmixed',
										'lineNumbers' => true,
										'indentUnit' => 4,
										'tabSize' => 4,
									),
									'value' => $template_html,
								);
								$this->renderCodeEditor( $html_editor_args );
								?>
							</div>

							<div class="shopglut-template-editor-panel" data-panel="css">
								<?php
								// CSS Editor
								$css_editor_args = array(
									'id' => 'template_css',
									'title' => esc_html__( 'CSS Styles', 'shopglut' ),
									'settings' => array(
										'theme' => 'monokai',
										'mode' => 'css',
										'lineNumbers' => true,
										'indentUnit' => 4,
										'tabSize' => 4,
									),
									'value' => $template_css,
								);
								$this->renderCodeEditor( $css_editor_args );
								?>
							</div>
						</div>

						<input type="hidden" name="template_tags" id="template_tags"
							value="<?php echo esc_attr( $template_tags_json ); ?>">
					</div>

					<!-- Template Tags - Right Column (Smaller) -->
					<div class="shopglut-template-editor-sidebar">
						<div class="shopglut-template-tags">
							<h2><?php echo esc_html__( 'Template Tags', 'shopglut' ); ?></h2>
							<p class="description">
								<?php echo esc_html__( 'Click on a tag to insert it into the editor.', 'shopglut' ); ?>
							</p>

							<?php foreach ( $this->template_tags as $category => $tags ) : ?>
								<div class="shopglut-template-tags-category">
									<div class="shopglut-template-tags-list">
										<?php foreach ( $tags as $tag => $description ) : ?>
											<?php
											$tag_parts = explode( ' - ', $description );
											$tag_name = $tag_parts[0];
											$tag_desc = isset( $tag_parts[1] ) ? $tag_parts[1] : '';
											?>
											<div class="shopglut-template-tag" data-tag="<?php echo esc_attr( $tag ); ?>"
												title="<?php echo esc_attr( $description ); ?>">
												<code><?php echo esc_html( $tag ); ?></code>
												<span class="shopglut-template-tag-info">
													<strong><?php echo esc_html( $tag_name ); ?></strong>
													<?php if ( ! empty( $tag_desc ) ) : ?>
														<small><?php echo esc_html( $tag_desc ); ?></small>
													<?php endif; ?>
												</span>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="shopglut-template-editor-actions">
					<button type="submit" name="save_template" class="button button-primary button-large">
						<?php echo esc_html__( 'Save Template', 'shopglut' ); ?>
					</button>
				</div>
			</form>

			<style>
               html.wp-toolbar{
				padding:0px;
			   }
			</style>

			<script type="text/javascript">
			jQuery(document).ready(function($) {
				// Tab switching functionality
				$('.shopglut-template-editor-tab').on('click', function() {
					var tab = $(this).data('tab');

					// Update tabs
					$('.shopglut-template-editor-tab').removeClass('active');
					$(this).addClass('active');

					// Update panels
					$('.shopglut-template-editor-panel').removeClass('active');
					$('.shopglut-template-editor-panel[data-panel="' + tab + '"]').addClass('active');
				});
			});
			</script>
		</div>
		<?php
	}

	/**
	 * Format HTML with proper indentation
	 */
	private function formatHTML( $html ) {
		if ( empty( $html ) ) {
			return $html;
		}

		$formatted = '';
		$indent = 0;
		$indent_str = '    '; // 4 spaces

		// Remove extra whitespace
		$html = trim( preg_replace( '/>\s+</', '><', $html ) );

		// Split into tokens
		preg_match_all( '/(<[^>]+>|[^<]+)/', $html, $matches );
		$tokens = $matches[0];

		foreach ( $tokens as $token ) {
			$token = trim( $token );
			if ( empty( $token ) ) {
				continue;
			}

			// Check if it's a tag
			if ( substr( $token, 0, 1 ) === '<' ) {
				if ( substr( $token, 0, 2 ) === '</' ) {
					// Closing tag
					$indent = max( 0, $indent - 1 );
					$formatted .= str_repeat( $indent_str, $indent ) . $token . "\n";
				} elseif ( substr( $token, -2 ) === '/>' ) {
					// Self-closing tag
					$formatted .= str_repeat( $indent_str, $indent ) . $token . "\n";
				} else {
					// Opening tag
					$formatted .= str_repeat( $indent_str, $indent ) . $token . "\n";
					$indent++;
				}
			} else {
				// Text content
				$formatted .= str_repeat( $indent_str, $indent ) . $token . "\n";
			}
		}

		return trim( $formatted );
	}

	/**
	 * Render a code editor field
	 */
	private function renderCodeEditor( $args ) {
		$defaults = array(
			'id' => '',
			'title' => '',
			'settings' => array(),
			'value' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		// Format HTML before rendering
		$is_html = isset( $args['settings']['mode'] ) && $args['settings']['mode'] !== 'css';
		if ( $is_html && ! empty( $args['value'] ) ) {
			$args['value'] = $this->formatHTML( $args['value'] );
		}

		// Create a unique ID for the editor
		$editor_id = ! empty( $args['id'] ) ? $args['id'] : 'shopglut-code-editor-' . uniqid();

		// Set up CodeMirror settings based on mode
		$is_html = $args['settings']['mode'] !== 'css';
		$code_editor_settings = array(
			'type' => $is_html ? 'text/html' : 'text/css',
			'codemirror' => array(
				'lineNumbers' => true,
				'mode' => $is_html ? 'htmlmixed' : 'css',
				'indentUnit' => 4,
				'tabSize' => 4,
				'autoCloseTags' => true,
				'autoCloseBrackets' => true,
				'matchTags' => true,
				'matchBrackets' => true,
				'styleActiveLine' => true,
				'extraKeys' => array(
					'Ctrl-Space' => 'autocomplete',
				),
			),
		);
		?>
		<div class="shopglut-code-editor-field">
			<?php if ( ! empty( $args['title'] ) ) : ?>
				<h3 class="shopglut-code-editor-title"><?php echo esc_html( $args['title'] ); ?></h3>
			<?php endif; ?>

			<div class="shopglut-code-editor-container">
				<textarea id="<?php echo esc_attr( $editor_id ); ?>" name="<?php echo esc_attr( $editor_id ); ?>"
					class="shopglut-code-editor large-text code"
					rows="20"
					style="width: 100%; font-family: monospace;"><?php echo esc_textarea( $args['value'] ); ?></textarea>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						if (typeof wp !== 'undefined' && wp.codeEditor) {
							wp.codeEditor.initialize('<?php echo esc_js( $editor_id ); ?>', <?php echo wp_json_encode( $code_editor_settings ); ?>);
						}
					});
				</script>
			</div>
		</div>
		<?php
	}

	/**
	 * Get default template HTML
	 */
	private function getDefaultTemplateHTML() {
		return '<div class="shopglut-product-template">' .
			'<div class="product-image">' .
				'[product_image]' .
			'</div>' .
			'<div class="product-details">' .
				'<h2 class="product-title">[product_title]</h2>' .
				'<div class="product-price">' .
					'[product_price]' .
				'</div>' .
				'<div class="product-rating">' .
					'[product_rating]' .
				'</div>' .
				'<div class="product-description">' .
					'[product_short_description]' .
				'</div>' .
				'<div class="product-actions">' .
					'[btn_cart]' .
					'[btn_view]' .
				'</div>' .
			'</div>' .
		'</div>';
	}

	/**
	 * Get default template CSS
	 */
	private function getDefaultTemplateCSS() {
		return '.shopglut-product-template { display: flex; flex-wrap: wrap; max-width: 100%; margin-bottom: 30px; border: 1px solid #eee; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }' .
			'.product-image { flex: 0 0 40%; max-width: 40%; padding-right: 20px; }' .
			'.product-details { flex: 0 0 60%; max-width: 60%; }' .
			'.product-title { font-size: 24px; margin-top: 0; margin-bottom: 10px; }' .
			'.product-price { font-size: 20px; font-weight: bold; color: #333; margin-bottom: 15px; }' .
			'.product-description { margin-bottom: 20px; color: #666; }' .
			'.product-actions { display: flex; gap: 10px; }' .
			'@media (max-width: 768px) { .shopglut-product-template { flex-direction: column; } .product-image, .product-details { flex: 0 0 100%; max-width: 100%; } .product-image { padding-right: 0; margin-bottom: 20px; } }';
	}

	/**
	 * AJAX handler for saving template data
	 */
	public function ajaxSaveTemplate() {
		// Check nonce for security
		if ( ! isset( $_POST['template_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['template_nonce'] ) ), 'save_woo_template' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed.' ) );
			return;
		}

		// Get template ID
		$template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( wp_unslash( $_POST['template_id'] ) ) : null;

		// Save the template$
		global $wpdb;

		$template_name = isset( $_POST['template_name'] ) ? sanitize_text_field( wp_unslash( $_POST['template_name'] ) ) : '';
		$template_slug = isset( $_POST['template_slug'] ) ? sanitize_key( wp_unslash( $_POST['template_slug'] ) ) : '';
		$template_html = isset( $_POST['template_html'] ) ? wp_kses_post( wp_unslash( $_POST['template_html'] ) ) : '';
		$template_css = isset( $_POST['template_css'] ) ? sanitize_textarea_field( wp_unslash( $_POST['template_css'] ) ) : '';
		$template_tags = isset( $_POST['template_tags'] ) ? sanitize_textarea_field( wp_unslash( $_POST['template_tags'] ) ) : json_encode( $this->template_tags );

		// Validate required fields
		if ( empty( $template_name ) || empty( $template_slug ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Template name and ID are required.', 'shopglut' ) ) );
			return;
		}

		// Check if template ID already exists for a different template
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for template validation in AJAX handler
		$existing_template = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE template_id = %s AND id != %d",
			$template_slug,
			$template_id ? $template_id : 0
		) );

		if ( $existing_template ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Template ID must be unique. This ID is already in use.', 'shopglut' ) ) );
			return;
		}

		// Prepare data for database
		$data = array(
			'template_name' => $template_name,
			'template_id' => $template_slug,
			'template_html' => $template_html,
			'template_css' => $template_css,
			'template_tags' => $template_tags,
			'updated_at' => current_time( 'mysql' )
		);

		$success = false;
		$new_id = 0;

		if ( $template_id ) {
			// Check if the record exists based on the primary key (id)
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query to check template existence for AJAX update
			$existing_record = $wpdb->get_row( $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}shopglut_woo_templates WHERE id = %d",
				$template_id
			) );

			if ( $existing_record ) {
				// Update existing template
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table existence check with caching, safe table name from internal function
				$success = $wpdb->update( $table_name, $data, array( 'id' => $template_id ) );

			} else {
				// Insert new template
				$data['created_at'] = current_time( 'mysql' );
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table insert operation
				$success = $wpdb->insert( $table_name, $data );
				$new_id = $wpdb->insert_id;
			}
		} else {
			// Insert new template
			$data['created_at'] = current_time( 'mysql' );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table insert operation
			$success = $wpdb->insert( $table_name, $data );
			$new_id = $wpdb->insert_id;
		}

		if ( $success ) {
			wp_send_json_success( array( 'message' => esc_html__( 'Template saved successfully.', 'shopglut' ), 'template_id' => $new_id ? $new_id : $template_id ) );
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to save template.', 'shopglut' ) ) );
		}
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
	
	/**
	 * Handle template actions like delete
	 */
	public function handleTemplateActions() {
		// Check if we're on the templates page
		if ( ! isset( $_GET['page'] ) || $this->menu_slug !== $_GET['page'] ) {
			return;
		}
		
		// Handle delete action
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['template_id'] ) ) {
			$template_id = intval( $_GET['template_id'] );
			
			// Verify nonce
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_template_' . $template_id ) ) {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
			
			// Delete the template
			WooTemplatesEntity::delete_template( $template_id );
			
			// Redirect back to the templates list with a success message
			wp_safe_redirect( add_query_arg( 
				array( 
					'page' => $this->menu_slug,
					'deleted' => '1' 
				), 
				admin_url( 'admin.php' ) 
			) );
			exit;
		}
		
		// Handle bulk delete action
		if ( isset( $_POST['action'] ) && 'delete' === $_POST['action'] && isset( $_POST['template_ids'] ) && is_array( $_POST['template_ids'] ) ) {
			// Verify nonce
			check_admin_referer( 'bulk-templates' );
			
			$deleted = 0;
			$template_ids = array_map( 'sanitize_text_field', wp_unslash( $_POST['template_ids'] ) );
			foreach ( $template_ids as $template_id ) {
				$template_id = intval( $template_id );
				WooTemplatesEntity::delete_template( $template_id );
				$deleted++;
			}
			
			// Redirect back to the templates list with a success message
			wp_safe_redirect( add_query_arg( 
				array( 
					'page' => $this->menu_slug,
					'deleted' => $deleted 
				), 
				admin_url( 'admin.php' ) 
			) );
			exit;
		}
	}
}