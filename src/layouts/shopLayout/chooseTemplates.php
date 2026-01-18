<?php
namespace Shopglut\layouts\shopLayout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class chooseTemplates {

	public function __construct() {
		add_action( 'admin_post_create_layout', array( $this, 'handleCreateLayout' ) );
		// Hook to allow pro plugin to add templates
		add_action( 'init', array( $this, 'init_template_system' ) );
	}

	public function init_template_system() {
		// Allow other plugins to register templates
		do_action( 'shopglut_register_templates' );
	}

	public function loadShoplayoutTemplates() {
		// Default arguments with posts_per_page as 3
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 3,
		);

		// Get tabs and templates (now filterable)
		$tab_names = $this->get_tab_names();
		$template_names = $this->get_template_names();

		$query = new \WP_Query( $args );

		?>
		<div class="shopg-tab-container">
			<ul class="shopg-tabs">
				<?php foreach ( $tab_names as $tab_id => $tab ) : ?>
					<li class="shopg-tab" data-tab="<?php echo esc_attr( $tab_id ); ?>">
						<?php echo esc_html( $tab['name'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php foreach ( $tab_names as $tab_id => $tab ) : ?>
				<div class="shopg-tab-content" id="<?php echo esc_attr( $tab_id ); ?>">
					<?php foreach ( $tab['templates'] as $layout_template ) : ?>
						<?php if ( $this->is_template_available( $layout_template ) ) : ?>
							<div class="shopg_templates_layouts column row col-3 choose-template">
								<div class="shopg_template_title">
									<h2><?php echo esc_html( $template_names[ $layout_template ] ?? '' ); ?></h2>
								</div>
								<div class="template_design_layouts <?php echo esc_attr( $layout_template ?? '' ); ?>">
									<?php
									// If the template is template6, modify posts_per_page
									if ( in_array( $layout_template, ['template6', 'template7', 'template8', 'template9'] ) ) {
										$args['posts_per_page'] = 2;  // Set to 2 for caption layouts
									} else {
										$args['posts_per_page'] = 3;
									}

									// The query with updated arguments
									$query = new \WP_Query( $args );

									if ( $query->have_posts() ) {
										while ( $query->have_posts() ) {
											$query->the_post();

											$layout_class = $this->get_template_class( $layout_template );

											if ( class_exists( $layout_class ) ) {
												$layout_instance = new $layout_class();
												$layout_instance->layout_render( [] );
											}
										}
										wp_reset_postdata();
									} else {
										echo esc_html__('No Product Found', 'shopglut');
									}
									?>
								</div>
								<div class="shopg-create-layout">
									<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
										<input type="hidden" name="action" value="create_layout">
										<input type="hidden" name="layout_template" value="<?php echo esc_attr( $layout_template ); ?>">
										<?php wp_nonce_field( 'create_layout_nonce', 'create_layout_nonce' ); ?>
										<input type="submit" name="publish" id="publish" class="btn btn-green btn-large"
											value="<?php echo esc_html__( "Choose and Edit Style", 'shopglut' ); ?>" />
									</form>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Get tab names with filter for extensibility
	 */
	protected function get_tab_names() {
		$default_tabs = array(
			'tab1' => array( 'name' => esc_html__( "General", 'shopglut' ), 'templates' => array( 'template1') ),
			//'tab2' => array( 'name' => esc_html__( "Caption Layouts", 'shopglut' ), 'templates' => array( 'template6', 'template7', 'template8', 'template9' ) ),
			//'tab3' => array( 'name' => esc_html__( "Interactive Effects", 'shopglut' ), 'templates' => array( 'template11', 'template12', 'template13', 'template14', 'template15', 'template16', 'template17', 'template18' ) ),
			//'tab4' => array( 'name' => esc_html__( "Free", 'shopglut' ), 'templates' => array( 'template1', 'template2', 'template4', 'template5', 'template6', 'template7', 'template8', 'template9', 'template10', 'template11', 'template12', 'template13', 'template14', 'template15', 'template16', 'template17', 'template18' ) ),
		);

		return apply_filters( 'shopglut_template_tabs', $default_tabs );
	}

	/**
	 * Get template names with filter for extensibility
	 */
	protected function get_template_names() {
		$default_templates = array(
			'template1' => esc_html__( "Template One", 'shopglut' ),
			'template2' => esc_html__( "Template Two", 'shopglut' ),
			'template3' => esc_html__( "Template Three", 'shopglut' ),
			'template4' => esc_html__( "Template Four", 'shopglut' ),
			'template5' => esc_html__( "Template Five", 'shopglut' ),
			'template6' => esc_html__( "Template Six", 'shopglut' ),
			'template7' => esc_html__( "Template Seven", 'shopglut' ),
			'template8' => esc_html__( "Template Eight", 'shopglut' ),
			'template9' => esc_html__( "Template Nine", 'shopglut' ),
			'template10' => esc_html__( "Template Ten", 'shopglut' ),
			'template11' => esc_html__( "Template Eleven", 'shopglut' ),
			'template12' => esc_html__( "Template Twelve", 'shopglut' ),
			'template13' => esc_html__( "Template Thirteen", 'shopglut' ),
			'template14' => esc_html__( "Template Fourteen", 'shopglut' ),
			'template15' => esc_html__( "Template FiftTeen", 'shopglut' ),
			'template16' => esc_html__( "Template SixTeen", 'shopglut' ),
			'template17' => esc_html__( "Template Seventeen", 'shopglut' ),
			'template18' => esc_html__( "Template Eighteen", 'shopglut' ),
		);

		return apply_filters( 'shopglut_template_names', $default_templates );
	}

	/**
	 * Check if template is available (exists and class is loadable)
	 */
	protected function is_template_available( $template_id ) {
		$template_class = $this->get_template_class( $template_id );
		return class_exists( $template_class );
	}

	/**
	 * Get template class name with filter for pro plugin namespace override
	 */
	protected function get_template_class( $template_id ) {
		$default_namespace = 'Shopglut\\layouts\\shopLayout\\templates\\';
		$template_class = $default_namespace . $template_id;
		
		// Allow pro plugin to override template classes
		return apply_filters( 'shopglut_template_class', $template_class, $template_id );
	}

	public function handleCreateLayout() {
		if (
			isset( $_POST['create_layout_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['create_layout_nonce'] ) ), 'create_layout_nonce' ) &&
			current_user_can( 'manage_options' )
		) {
			$layout_template = isset( $_POST['layout_template'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_template'] ) ) : '';

			global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Inserting new layout configuration
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$inserted = $wpdb->insert(
				$table_name,
				array(
					'layout_template' => $layout_template,
				)
			);

			if ( $inserted ) {
				$layout_id = $wpdb->insert_id;
				$layout_name = sanitize_text_field( 'Layout(#' . $layout_id . ')' );

				// Update the layout name now that we have the ID
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update(
					$table_name,
					array( 'layout_name' => $layout_name ),
					array( 'id' => $layout_id ),
					array( '%s' ),
					array( '%d' )
				);

				$redirect_url = admin_url( 'admin.php?page=shopglut_layouts&editor=shop&layout_id=' . $layout_id );
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				wp_die( 'Database insertion error' );
			}
		} else {
			wp_die( 'Permission error' );
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