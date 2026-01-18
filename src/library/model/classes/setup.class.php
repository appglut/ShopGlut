<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Setup Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'AGSHOPGLUT' ) ) {
	class AGSHOPGLUT {

		// Default constants
		public static $pro = true;
		public static $version = '1.0.6';
		public static $dir = '';
		public static $url = '';
		public static $css = '';
		public static $webfonts = array();
		public static $subsets = array();
		public static $inited = array();
		public static $fields = array();
		public static $args = array(
			'admin_options' => array(),
			'metabox_options' => array(),
			'layout_options' => array(),
			'ecustomizer_options' => array(),
			'taxonomy_options' => array(),

		);

		// Initalize
		public static function init() {

			// Init action
			do_action( 'agl_init' );

			// Set directory constants
			self::constants();

			// Include files
			self::includes();

			// Setup textdomain
			self::textdomain();

			 add_action( 'after_setup_theme', array( 'AGSHOPGLUT', 'setup' ) );
			 add_action( 'init', array( 'AGSHOPGLUT', 'setup' ) );
			 add_action( 'switch_theme', array( 'AGSHOPGLUT', 'setup' ) );
			 add_action( 'admin_enqueue_scripts', array( 'AGSHOPGLUT', 'add_admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( 'AGSHOPGLUT', 'add_typography_enqueue_styles' ), 80 );
			add_action( 'wp_head', array( 'AGSHOPGLUT', 'add_custom_css' ), 80 );
			add_filter( 'admin_body_class', array( 'AGSHOPGLUT', 'add_admin_body_class' ) );

		}

		// Setup frameworks
		public static function setup() {

			// Welcome page
			self::include_plugin_file( 'views/welcome.php' );

			// Setup admin option framework
			$params = array();
			if ( ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args'] = $value;
						$params['sections'] = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						AGSHOPGLUT_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							$value['output_css'] = false;
							$value['enqueue_webfont'] = false;
							self::$args['customize_options'][ $key ] = $value;
							self::$inited[ $key ] = null;
						}

					}
				}
			}

			// Setup taxonomy option framework
			$params = array();
			if ( class_exists( 'AGSHOPGLUT_Tax_Options' ) && ! empty( self::$args['taxonomy_options'] ) ) {
				$taxonomy = '';
				foreach ( self::$args['taxonomy_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args'] = $value;
						$params['sections'] = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						AGSHOPGLUT_Tax_Options::instance( $key, $params );

					}
				}
			}

			// Setup metabox option framework
			$params = array();
			if ( ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args'] = $value;
						$params['sections'] = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						AGSHOPGLUT_Metabox::instance( $key, $params );

					}
				}

			}
			$params = array();
			if ( ! empty( self::$args['layout_options'] ) ) {
				foreach ( self::$args['layout_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args'] = $value;
						$params['sections'] = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						AGSHOPGLUT_Layouts::instance( $key, $params );

					}
				}

			}

			$params = array();
			if ( ! empty( self::$args['ecustomizer_options'] ) ) {
				foreach ( self::$args['ecustomizer_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args'] = $value;
						$params['sections'] = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						AGSHOPGLUT_eCustomizer::instance( $key, $params );

					}
				}

			}

			do_action( 'agl_loaded' );

		}

		// Create options
		public static function createOptions( $id, $args = array() ) {
			self::$args['admin_options'][ $id ] = $args;
		}

		// Create metabox options
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metabox_options'][ $id ] = $args;
		}

		public static function createSettings( $id, $args = array() ) {
			self::$args['layout_options'][ $id ] = $args;
		}

		public static function createEcustomSettings( $id, $args = array() ) {
			self::$args['ecustomizer_options'][ $id ] = $args;
		}


		// Create taxonomy options
		public static function createTaxonomyOptions( $id, $args = array() ) {
			self::$args['taxonomy_options'][ $id ] = $args;
		}

		// Create section
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		// Set directory constants
		public static function constants() {

			// We need this path-finder code for set URL of framework
			$dirname = str_replace( '//', '/', wp_normalize_path( dirname( dirname( __FILE__ ) ) ) );
			$theme_dir = str_replace( '//', '/', wp_normalize_path( get_parent_theme_file_path() ) );
			$plugin_dir = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$foldername = str_replace( $directory, '', $dirname );
			$protocol_uri = ( is_ssl() ) ? 'https' : 'http';
			$directory_uri = set_url_scheme( $directory_uri, $protocol_uri );

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;

		}

		// Include file helper
		public static function include_plugin_file( $file, $load = true ) {

			$path = '';
			$file = ltrim( $file, '/' );
			$override = apply_filters( 'agl_override', 'agl-override' );

			if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_parent_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists( self::$dir . '/' . $file ) ) {
				$path = self::$dir . '/' . $file;
			}

			if ( ! empty( $path ) && ! empty( $file ) && $load ) {

				global $wp_query;

				if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

					load_template( $path, true );

				} else {

					require_once $path;

				}

			} else {

				return self::$dir . '/' . $file;

			}

		}

		// Is active plugin helper
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', array() ) );
		}

		// Sanitize dirname
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		// Set url constant
		public static function include_plugin_url( $file ) {
			return esc_url( self::$url ) . '/' . ltrim( $file, '/' );
		}

		// Include files
		public static function includes() {

			// Helpers
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// Includes free version classes
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/options.class.php' );
			self::include_plugin_file( 'classes/metabox.class.php' );
			self::include_plugin_file( 'classes/taxonomy.class.php' );
			//self::include_plugin_file( 'classes/singleLayout.class.php' );
			//self::include_plugin_file( 'classes/ecustomizer.class.php' );

		}

		// Maybe include a field class
		public static function maybe_include_field( $type = '' ) {
			if ( ! class_exists( 'AGSHOPGLUT_' . $type ) && class_exists( 'AGSHOPGLUTP' ) ) {
				self::include_plugin_file( 'fields/' . $type . '/' . $type . '.php' );
			}
		}

		// Setup textdomain
		public static function textdomain() {
			load_textdomain( 'shopglut', self::$dir . '/languages/' . get_locale() . '.mo' );
		}

		// Set all of used fields
		public static function set_used_fields( $sections ) {

			if ( ! empty( $sections['fields'] ) ) {

				foreach ( $sections['fields'] as $field ) {

					if ( ! empty( $field['fields'] ) ) {
						self::set_used_fields( $field );
					}

					if ( ! empty( $field['tabs'] ) ) {
						self::set_used_fields( array( 'fields' => $field['tabs'] ) );
					}

					if ( ! empty( $field['accordions'] ) ) {
						self::set_used_fields( array( 'fields' => $field['accordions'] ) );
					}

					if ( ! empty( $field['type'] ) ) {
						self::$fields[ $field['type'] ] = $field;
					}

				}

			}

		}

		// Enqueue admin and fields styles and scripts
		public static function add_admin_enqueue_scripts() {

			// Loads scripts and styles only when needed
			$enqueue = false;
			$wpscreen = get_current_screen();

			if ( ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $argument ) {
					if ( substr( $wpscreen->id, -strlen( $argument['menu_slug'] ) ) === $argument['menu_slug'] ) {
						$enqueue = true;
					}
				}
			}

			if ( ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $argument ) {
					if ( strpos( $wpscreen->base, $argument['post_type'] ) ) {
						$enqueue = true;
					}
				}
			}

			if ( ! empty( self::$args['layout_options'] ) ) {
				foreach ( self::$args['layout_options'] as $argument ) {
					if ( strpos( $wpscreen->base, $argument['post_type'] ) ) {
						$enqueue = true;
					}
				}
			}

			if ( ! empty( self::$args['taxonomy_options'] ) ) {
				foreach ( self::$args['taxonomy_options'] as $argument ) {
					if ( in_array( $wpscreen->taxonomy, (array) $argument['taxonomy'] ) ) {
						$enqueue = true;
					}
				}
			}

			// Check for developer mode
			$min = ( self::$pro && SCRIPT_DEBUG ) ? '' : '.min';

			// Admin utilities
			wp_enqueue_media();

			// Wp color picker
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );


			// Font awesome 4 and 5 loader
			if ( apply_filters( 'agl_fa4', true ) ) {
				wp_enqueue_style( 'agl-fa6', self::include_plugin_url( 'assets/css/all.min.css' ), array(), '6.5.1', 'all' );
			} else {

			}

			// Main style
			wp_enqueue_style( 'shopglut', self::include_plugin_url( 'assets/css/style.css' ), array(), self::$version, 'all' );

			// Main RTL styles
			if ( is_rtl() ) {
				wp_enqueue_style( 'agl-rtl', self::include_plugin_url( 'assets/css/style-rtl' . $min . '.css' ), array(), self::$version, 'all' );
			}

			//Custom Js
			wp_enqueue_script( 'agl-custom', self::include_plugin_url( 'assets/js/custom.js' ), array(), self::$version, true );

			// Main scripts
			wp_enqueue_script( 'agl-plugins', self::include_plugin_url( 'assets/js/plugins.js' ), array(), self::$version, true );
			wp_enqueue_script( 'shopglut', self::include_plugin_url( 'assets/js/main.js' ), array( 'agl-plugins' ), self::$version, true );

			// Main variables
			wp_localize_script( 'shopglut', 'agl_vars', array(
				'color_palette' => apply_filters( 'agl_color_palette', array() ),
				'i18n' => array(
					'confirm' => esc_html__( 'Are you sure?', 'shopglut' ),
					'typing_text' => esc_html__( 'Please enter more characters', 'shopglut' ),
					'searching_text' => esc_html__( 'Searching...', 'shopglut' ),
					'no_results_text' => esc_html__( 'No results found.', 'shopglut' ),
				),
			) );

			// Enqueue fields scripts and styles
			$enqueued = array();

			if ( ! empty( self::$fields ) ) {
				foreach ( self::$fields as $field ) {
					if ( ! empty( $field['type'] ) ) {
						$classname = 'AGSHOPGLUT_' . $field['type'];
						self::maybe_include_field( $field['type'] );
						if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
							$instance = new $classname( $field );
							if ( method_exists( $classname, 'enqueue' ) ) {
								$instance->enqueue();
							}
							unset( $instance );
						}
					}
				}
			}

			do_action( 'agl_enqueue' );

		}

		// Add typography enqueue styles to front page
		public static function add_typography_enqueue_styles() {

			if ( ! empty( self::$webfonts ) ) {

				if ( ! empty( self::$webfonts['enqueue'] ) ) {

					$query = array();
					$fonts = array();

					foreach ( self::$webfonts['enqueue'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					if ( ! empty( $fonts ) ) {
						$query['family'] = implode( '%7C', $fonts );
					}

					if ( ! empty( self::$subsets ) ) {
						$query['subset'] = implode( ',', self::$subsets );
					}

					$query['display'] = 'swap';

					//wp_enqueue_style('agl-google-web-fonts', esc_url(add_query_arg($query, '//fonts.googleapis.com/css')), array(), self::$version, null);

				}

				if ( ! empty( self::$webfonts['async'] ) ) {

					$fonts = array();

					foreach ( self::$webfonts['async'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					//wp_enqueue_script('agl-google-web-fonts', esc_url('//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js'), array(), self::$version, true);

					//wp_localize_script('agl-google-web-fonts', 'WebFontConfig', array('google' => array('families' => $fonts)));

				}

			}

		}

		// Add admin body class
		public static function add_admin_body_class( $classes ) {

			if ( apply_filters( 'agl_fa4', false ) ) {
				$classes .= 'agl-fa5-shims';
			}

			return $classes;

		}

		// Add custom css to front page
		public static function add_custom_css() {

			if ( ! empty( self::$css ) ) {
				echo '<style type="text/css">' . wp_kses_post( wp_strip_all_tags( self::$css ) ) . '</style>';
			}

		}

		// Add a new framework field
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for unallow fields
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field = array();
				$field['content'] = esc_html__( 'Oops! Not allowed.', 'shopglut' ) . ' <strong>(' . $field_type . ')</strong>';
				$field['type'] = 'notice';
				$field['style'] = 'danger';

			}

			$depend = '';
			$visible = '';
			$unique = ( ! empty( $unique ) ) ? $unique : '';
			$class = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo = ( ! empty( $field['pseudo'] ) ) ? ' agl-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';

			if ( ! empty( $field['dependency'] ) ) {

				$dependency = $field['dependency'];
				$depend_visible = '';
				$data_controller = '';
				$data_condition = '';
				$data_value = '';
				$data_global = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition = implode( '|', array_column( $dependency, 1 ) );
					$data_value = implode( '|', array_column( $dependency, 2 ) );
					$data_global = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = ( ! empty( $depend_visible ) ) ? ' agl-depend-visible' : ' agl-depend-hidden';

			}

			if ( ! empty( $field_type ) ) {

				// These attributes has been sanitized above.
				echo '<div class="agl-field agl-field-' . esc_attr( $field_type ) . esc_attr( $is_pseudo ) . esc_attr( $class ) . esc_attr( $visible ) . '"' . wp_kses_post( $depend ) . '>';

				if ( ! empty( $field['fancy_title'] ) ) {
					echo '<div class="agl-fancy-title">' . wp_kses_post( $field['fancy_title'] ) . '</div>';
				}

				if ( ! empty( $field['title'] ) ) {
					echo '<div class="agl-title">';
					echo '<h4>' . wp_kses_post( $field['title'] ) . '</h4>';
					echo ( ! empty( $field['subtitle'] ) ) ? '<div class="agl-subtitle-text">' . wp_kses_post( $field['subtitle'] ) . '</div>' : '';
					echo '</div>';
				}

				$device_type = array(
					'desktop', 'tablet', 'mobile',
				);

				if ( ! empty( $field['active_device'] ) ) {
					echo '<div class="agl-res-devices">';
					foreach ( $device_type as $device ) {
						$checked_device = ( $field['active_device'] === 'data' ) ? 'checked="checked"' : '';
						$title = '';
						$icon_class = '';
						if ( $device === 'desktop' ) {
							$title = 'Desktop';
							$icon_class = 'desktop';
						} elseif ( $device === 'tablet' ) {
							$title = 'Tablet';
							$icon_class = 'tablet-screen-button';
						} elseif ( $device === 'mobile' ) {
							$title = 'Mobile';
							$icon_class = 'mobile-screen';
						}
						echo '<label select-type="' . esc_attr( $field['id'] ) . '-select-type-' . esc_attr( $device ) . '" id="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['id'] ) . '-select-type-' . esc_attr( $device ) . ' "' . ( $checked_device ? 'class="checked"' : '' ) . '><i class="' . esc_attr( $field['id'] ) . ' fa fa-' . esc_attr( $icon_class ) . '" title="' . esc_attr( $title ) . '"><input type="radio" name="' . esc_attr( $device ) . '" value="' . esc_attr( $device ) . '" data-depend-id="agl-responsive-' . esc_attr( $device ) . '" class="agl-hidden-radio" style="display: none;" /></i></label>';
					}
					echo '<i class="info fa-solid fa-info" title="' . esc_html__( 'Change display device and adjust value where to show', 'shopglut' ) . '"></i>';
					echo '</div>';
				}

				echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '<div class="agl-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				//print_r($value);

				self::maybe_include_field( $field_type );

				$classname = 'AGSHOPGLUT_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'Field not found!', 'shopglut' ) . '</p>';
				}

			} else {
				echo '<p>' . esc_html__( 'Field not found!', 'shopglut' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';

		}

	}

	AGSHOPGLUT::init();
}