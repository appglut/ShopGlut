<?php
namespace Shopglut\tools\WooThemes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Shopglut\tools\WooThemes\themes\DefaultTheme\DefaultTheme;
use Shopglut\tools\WooThemes\themes\ModernStore\ModernStore;
use Shopglut\tools\WooThemes\themes\ClassicShop\ClassicShop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooThemes {
	
	public function __construct() {
		add_action( 'wp_ajax_activate_woo_theme', [ $this, 'activateTheme' ] );
		add_action( 'wp_ajax_nopriv_activate_woo_theme', [ $this, 'activateTheme' ] );
		add_filter( 'body_class', [ $this, 'addThemeBodyClass' ] );
	}

	public function renderThemesList() {
		$themes = $this->getAvailableThemes();
		$active_theme = get_option( 'shopglut_active_woo_theme', 'default' );
		
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Woo Themes', 'shopglut' ); ?></h1>
			<div class="notice notice-info">
				<p><strong><?php echo esc_html__( 'Important:', 'shopglut' ); ?></strong> <?php echo esc_html__( 'These are WooCommerce store themes that only customize your site\'s header and footer elements. They work with your existing WordPress theme and do not replace it. Visit your frontend to see the changes.', 'shopglut' ); ?></p>
			</div>
			<p><?php echo esc_html__( 'Choose a WooCommerce theme for your store. These themes customize only the header and footer elements while keeping other sections normal.', 'shopglut' ); ?></p>
			
			<div class="themes-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
				<?php foreach ( $themes as $theme_id => $theme ) : ?>
					<div class="theme-card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
						<div class="theme-screenshot" style="position: relative;">
							<img src="<?php echo esc_url( $theme['screenshot'] ); ?>" alt="<?php echo esc_attr( $theme['name'] ); ?>" style="width: 100%; height: 200px; object-fit: cover;">
							<?php if ( $active_theme === $theme_id ) : ?>
								<div class="active-badge" style="position: absolute; top: 10px; right: 10px; background: #00a32a; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
									<?php echo esc_html__( 'Active', 'shopglut' ); ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="theme-info" style="padding: 20px;">
							<h3 style="margin: 0 0 10px 0; color: #333;"><?php echo esc_html( $theme['name'] ); ?></h3>
							<p style="color: #666; margin: 0 0 15px 0; font-size: 14px;"><?php echo esc_html( $theme['description'] ); ?></p>
							<div class="theme-actions">
								<?php if ( $active_theme === $theme_id ) : ?>
									<button class="button button-primary" disabled><?php echo esc_html__( 'Active Theme', 'shopglut' ); ?></button>
								<?php else : ?>
									<button class="button button-primary activate-theme" data-theme-id="<?php echo esc_attr( $theme_id ); ?>">
										<?php echo esc_html__( 'Activate', 'shopglut' ); ?>
									</button>
								<?php endif; ?>
								<button class="button customize-theme" data-theme-id="<?php echo esc_attr( $theme_id ); ?>">
									<?php echo esc_html__( 'Customize', 'shopglut' ); ?>
								</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('.activate-theme').on('click', function() {
				var themeId = $(this).data('theme-id');
				var button = $(this);
				
				button.prop('disabled', true).text('<?php echo esc_js( __( 'Activating...', 'shopglut' ) ); ?>');
				
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'activate_woo_theme',
						theme_id: themeId,
						nonce: '<?php echo esc_attr( wp_create_nonce( 'shopglut_theme_nonce' ) ); ?>'
					},
					success: function(response) {
						if (response.success) {
							location.reload();
						} else {
							alert('<?php echo esc_js( __( 'Error activating theme', 'shopglut' ) ); ?>');
							button.prop('disabled', false).text('<?php echo esc_js( __( 'Activate', 'shopglut' ) ); ?>');
						}
					},
					error: function() {
						alert('<?php echo esc_js( __( 'Error activating theme', 'shopglut' ) ); ?>');
						button.prop('disabled', false).text('<?php echo esc_js( __( 'Activate', 'shopglut' ) ); ?>');
					}
				});
			});

			$('.customize-theme').on('click', function() {
				var themeId = $(this).data('theme-id');
				window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=woo_themes&customize=' ) ); ?>' + themeId;
			});
		});
		</script>
		<?php
	}

	public function renderCustomizer() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for theme customizer display
		$theme_id = isset( $_GET['customize'] ) ? sanitize_text_field( wp_unslash( $_GET['customize'] ) ) : '';
		$themes = $this->getAvailableThemes();
		
		if ( ! isset( $themes[$theme_id] ) ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Invalid theme ID', 'shopglut' ) . '</p></div>';
			return;
		}
		
		$theme = $themes[$theme_id];
		
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Customize Theme:', 'shopglut' ); ?> <?php echo esc_html( $theme['name'] ); ?></h1>
			<p><?php echo esc_html__( 'Customize header and footer elements for this theme.', 'shopglut' ); ?></p>
			
			<div class="theme-customizer" style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 30px;">
				<div class="customizer-controls">
					<h3><?php echo esc_html__( 'Customization Options', 'shopglut' ); ?></h3>
					
					<div class="control-group" style="margin-bottom: 20px;">
						<label><strong><?php echo esc_html__( 'Header Settings', 'shopglut' ); ?></strong></label>
						<p>
							<label>
								<input type="checkbox" id="custom_header" <?php checked( get_option( "shopglut_{$theme_id}_custom_header", false ) ); ?>>
								<?php echo esc_html__( 'Enable Custom Header', 'shopglut' ); ?>
							</label>
						</p>
						<p>
							<label><?php echo esc_html__( 'Header Background Color:', 'shopglut' ); ?></label>
							<input type="color" id="header_bg_color" value="<?php echo esc_attr( get_option( "shopglut_{$theme_id}_header_bg", '#ffffff' ) ); ?>">
						</p>
					</div>
					
					<div class="control-group" style="margin-bottom: 20px;">
						<label><strong><?php echo esc_html__( 'Footer Settings', 'shopglut' ); ?></strong></label>
						<p>
							<label>
								<input type="checkbox" id="custom_footer" <?php checked( get_option( "shopglut_{$theme_id}_custom_footer", false ) ); ?>>
								<?php echo esc_html__( 'Enable Custom Footer', 'shopglut' ); ?>
							</label>
						</p>
						<p>
							<label><?php echo esc_html__( 'Footer Background Color:', 'shopglut' ); ?></label>
							<input type="color" id="footer_bg_color" value="<?php echo esc_attr( get_option( "shopglut_{$theme_id}_footer_bg", '#333333' ) ); ?>">
						</p>
					</div>
					
					<button class="button button-primary" id="save_customizations" data-theme-id="<?php echo esc_attr( $theme_id ); ?>">
						<?php echo esc_html__( 'Save Changes', 'shopglut' ); ?>
					</button>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_tools&view=woo_themes' ) ); ?>" class="button">
						<?php echo esc_html__( 'Back to Themes', 'shopglut' ); ?>
					</a>
				</div>
				
				<div class="theme-preview">
					<h3><?php echo esc_html__( 'Preview', 'shopglut' ); ?></h3>
					<div class="preview-frame" style="border: 1px solid #ddd; background: #f9f9f9; min-height: 400px; padding: 20px;">
						<p><?php echo esc_html__( 'Theme preview will be displayed here.', 'shopglut' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function activateTheme() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'shopglut_theme_nonce' ) ) {
			wp_die( 'Security check failed' );
		}
		
		$theme_id = isset( $_POST['theme_id'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_id'] ) ) : '';
		$themes = $this->getAvailableThemes();
		
		if ( ! isset( $themes[$theme_id] ) ) {
			wp_send_json_error( 'Invalid theme ID' );
		}
		
		// Copy theme from plugin to wp-content/themes
		$source_path = $this->getThemeSourcePath( $theme_id );
		$theme_name = $this->getThemeFolderName( $theme_id );
		$destination_path = WP_CONTENT_DIR . '/themes/' . $theme_name;
		
		// Debug: Log paths for verification (only in debug mode)
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Debug logging only when WP_DEBUG is enabled
			//error_log( 'ShopGlut WooThemes - Source path: ' . $source_path );
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Debug logging only when WP_DEBUG is enabled
			//error_log( 'ShopGlut WooThemes - Destination path: ' . $destination_path );
		}
		
		// Copy theme files
		if ( $this->copyThemeFiles( $source_path, $destination_path ) ) {
			// Activate the WordPress theme
			switch_theme( $theme_name );
			
			// Update our option as well
			update_option( 'shopglut_active_woo_theme', $theme_id );
			
			wp_send_json_success( 'Theme copied and activated successfully' );
		} else {
			wp_send_json_error( 'Failed to copy theme files' );
		}
	}

	public function addThemeBodyClass( $classes ) {
		if ( ! is_admin() ) {
			$active_theme = get_option( 'shopglut_active_woo_theme', 'default' );
			$classes[] = 'shopglut-woo-theme-' . $active_theme;
			$classes[] = 'shopglut-woo-theme-active';
		}
		return $classes;
	}

	private function getThemeSourcePath( $theme_id ) {
		$theme_mappings = [
			'default' => 'DefaultTheme',
			'theme1' => 'ModernStore',
			'theme2' => 'ClassicShop',
		];
		
		$folder_name = isset( $theme_mappings[$theme_id] ) ? $theme_mappings[$theme_id] : 'DefaultTheme';
		return plugin_dir_path( __FILE__ ) . 'themes/' . $folder_name;
	}

	private function getThemeFolderName( $theme_id ) {
		$theme_mappings = [
			'default' => 'shopglut-default',
			'theme1' => 'shopglut-modern-store',
			'theme2' => 'shopglut-classic-shop',
		];
		
		return isset( $theme_mappings[$theme_id] ) ? $theme_mappings[$theme_id] : 'shopglut-default';
	}

	private function copyThemeFiles( $source, $destination ) {
		// Check if source directory exists
		if ( ! is_dir( $source ) ) {
			return false;
		}

		// Create destination directory if it doesn't exist
		if ( ! is_dir( $destination ) ) {
			if ( ! wp_mkdir_p( $destination ) ) {
				return false;
			}
		}

		// Copy files recursively
		return $this->copyDirectory( $source, $destination );
	}

	private function copyDirectory( $source, $destination ) {
		$directory = new \RecursiveDirectoryIterator( $source, \RecursiveDirectoryIterator::SKIP_DOTS );
		$iterator = new \RecursiveIteratorIterator( $directory, \RecursiveIteratorIterator::SELF_FIRST );

		foreach ( $iterator as $item ) {
			$target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
			
			if ( $item->isDir() ) {
				if ( ! is_dir( $target ) ) {
					wp_mkdir_p( $target );
				}
			} else {
				if ( ! copy( $item, $target ) ) {
					return false;
				}
			}
		}

		return true;
	}

	private function getAvailableThemes() {
		return [
			'default' => [
				'name' => esc_html__( 'Default Theme', 'shopglut' ),
				'description' => esc_html__( 'Clean and simple default theme for WooCommerce stores.', 'shopglut' ),
				'screenshot' => plugin_dir_url( __FILE__ ) . '../../../assets/themes/default/screenshot.svg',
			],
			'theme1' => [
				'name' => esc_html__( 'Modern Store', 'shopglut' ),
				'description' => esc_html__( 'Modern and sleek design perfect for fashion and lifestyle stores.', 'shopglut' ),
				'screenshot' => plugin_dir_url( __FILE__ ) . '../../../assets/themes/theme1/screenshot.svg',
			],
			'theme2' => [
				'name' => esc_html__( 'Classic Shop', 'shopglut' ),
				'description' => esc_html__( 'Classic and elegant design suitable for any type of store.', 'shopglut' ),
				'screenshot' => plugin_dir_url( __FILE__ ) . '../../../assets/themes/theme2/screenshot.svg',
			],
		];
	}
}