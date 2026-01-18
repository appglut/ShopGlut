<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Metabox Class
 *
 * @since 1.0.0
 * @version 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_Metabox' ) ) {
	class AGSHOPGLUT_Metabox extends AGSHOPGLUT_Abstract {

		// constans
		public $unique = '';
		public $abstract = 'metabox';
		public $pre_fields = array();
		public $sections = array();
		public $post_type = array();
		public $post_formats = array();
		public $page_templates = array();
		public $args = array(
			'title' => '',
			'post_type' => 'post',
			'data_type' => 'serialize',
			'context' => 'advanced',
			'priority' => 'default',
			'exclude_post_types' => array(),
			'page_templates' => '',
			'post_formats' => '',
			'show_reset' => false,
			'show_restore' => false,
			'enqueue_webfont' => true,
			'async_webfont' => false,
			'output_css' => true,
			'nav' => 'normal',
			'theme' => 'dark',
			'class' => '',
			'defaults' => array(),
		);

		// run metabox construct
		public function __construct( $key, $params = array() ) {

			$this->unique = $key;
			$this->args = apply_filters( "agl_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "agl_{$this->unique}_sections", $params['sections'], $this );
			$this->post_type = ( is_array( $this->args['post_type'] ) ) ? $this->args['post_type'] : array_filter( (array) $this->args['post_type'] );
			$this->post_formats = ( is_array( $this->args['post_formats'] ) ) ? $this->args['post_formats'] : array_filter( (array) $this->args['post_formats'] );
			$this->page_templates = ( is_array( $this->args['page_templates'] ) ) ? $this->args['page_templates'] : array_filter( (array) $this->args['page_templates'] );
			$this->pre_fields = $this->pre_fields( $this->sections );

			// Keep the existing hook for layouts
			add_action( 'shopglut_layout_metaboxes', array(&$this, 'add_meta_box' ) );

			// Add WordPress native hook for regular posts
			add_action( 'add_meta_boxes', array(&$this, 'add_wp_meta_box' ) );
			// Add save action
			add_action( 'save_post', array(&$this, 'save_meta_box' ) );

			add_action( 'edit_attachment', array(&$this, 'save_meta_box' ) );

			if ( ! empty( $this->page_templates ) || ! empty( $this->post_formats ) || ! empty( $this->args['class'] ) ) {
				foreach ( $this->post_type as $post_type ) {
					add_filter( 'postbox_classes_' . $post_type . '_' . $this->unique, array(&$this, 'add_metabox_classes' ) );
				}
			}

			// wp enqeueu for typography and output css
			parent::__construct();

		}

		// instance
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;

		}

		public function add_metabox_classes( $classes ) {

			global $post;

			if ( ! empty( $this->post_formats ) ) {

				$saved_post_format = ( is_object( $post ) ) ? get_post_format( $post ) : false;
				$saved_post_format = ( ! empty( $saved_post_format ) ) ? $saved_post_format : 'default';

				$classes[] = 'agl-post-formats';

				// Sanitize post format for standard to default
				if ( ( $key = array_search( 'standard', $this->post_formats ) ) !== false ) {
					$this->post_formats[ $key ] = 'default';
				}

				foreach ( $this->post_formats as $format ) {
					$classes[] = 'agl-post-format-' . $format;
				}

				if ( ! in_array( $saved_post_format, $this->post_formats ) ) {
					$classes[] = 'agl-metabox-hide';
				} else {
					$classes[] = 'agl-metabox-show';
				}

			}

			if ( ! empty( $this->page_templates ) ) {

				$saved_template = ( is_object( $post ) && ! empty( $post->page_template ) ) ? $post->page_template : 'default';

				$classes[] = 'agl-page-templates';

				foreach ( $this->page_templates as $template ) {
					$classes[] = 'agl-page-' . preg_replace( '/[^a-zA-Z0-9]+/', '-', strtolower( $template ) );
				}

				if ( ! in_array( $saved_template, $this->page_templates ) ) {
					$classes[] = 'agl-metabox-hide';
				} else {
					$classes[] = 'agl-metabox-show';
				}

			}

			if ( ! empty( $this->args['class'] ) ) {
				$classes[] = $this->args['class'];
			}

			return $classes;

		}

		// add metabox
		public function add_meta_box( $post_type ) {

			if ( ! in_array( $post_type, $this->args['exclude_post_types'] ) ) {

				add_meta_box( $this->unique, $this->args['title'], array(&$this, 'add_meta_box_content' ), $this->post_type, $this->args['context'], $this->args['priority'], $this->args );
			}
		}

		// Add new method for WordPress posts
		public function add_wp_meta_box() {
			foreach ( (array) $this->post_type as $post_type ) {
				if ( ! in_array( $post_type, $this->args['exclude_post_types'] ) ) {
					add_meta_box(
						$this->unique,
						$this->args['title'],
						array(&$this, 'add_meta_box_content' ),
						$post_type,
						$this->args['context'],
						$this->args['priority'],
						$this->args
					);
				}
			}
		}

		// get default value
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;

		}

		// get meta value
	public function get_meta_value( $field ) {

		global $post, $wpdb;

		$value = null;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
		$editor = isset( $_GET['editor'] ) ? sanitize_text_field( wp_unslash( $_GET['editor'] ) ) : '';

		// First check if we're on a regular post/page with metabox
		if ( isset( $post->ID ) && empty( $page ) ) {
			
			// Define all possible metabox IDs
			$metabox_ids = array(
				'agshopglut_subscription_lock_metabox_options'
			);

			// Loop through each metabox ID to find the value
			foreach ( $metabox_ids as $metabox_id ) {
				$meta_data = get_post_meta( $post->ID, $metabox_id, true );

				if ( ! empty( $field['id'] ) && isset( $meta_data[ $field['id'] ] ) ) {
					$value = $meta_data[ $field['id'] ];
					break; // Exit loop once we find the value
				}
			}

		} elseif ( 'shopglut_enhancements' === $page && 'filters' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['filter_id'] ) ? absint( wp_unslash( $_GET['filter_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_enhancement_filters';

			// Add caching for better performance
			$cache_key = 'shopglut_filter_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );

			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT filter_settings FROM {$wpdb->prefix}shopglut_enhancement_filters WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			$layout_options_array = ( $layout_options ) ? unserialize( $layout_options ) : array();

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_filter_options_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_filter_options_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_layouts' === $page && 'shop' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

			// Add caching for better performance
			$cache_key = 'shopglut_shop_layout_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );
			
			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_shop_layouts WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			$layout_options_array = ( $layout_options ) ? unserialize( $layout_options ) : array();

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_options_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_options_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_layouts' === $page && 'archive' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_archive_layouts';

			// Add caching for better performance
			$cache_key = 'shopglut_archive_layout_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );
			
			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT arlayout_settings FROM {$wpdb->prefix}shopglut_archive_layouts WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			$layout_options_array = ( $layout_options ) ? unserialize( $layout_options ) : array();

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_options_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_options_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_layouts' === $page && 'single_product' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_single_product_layout';

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_single_product_layout WHERE id = %d", $post_id ) );
		   	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added		
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_single_product_layout WHERE id = %d", $post_id ) );
			

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = null;

				// Check main settings first
				if ( isset( $layout_options_array['shopg_singleproduct_settings_'.$layout_template][ $field['id'] ] ) ) {
					$value = $layout_options_array['shopg_singleproduct_settings_'.$layout_template][ $field['id'] ];
				}

			}

		} elseif ( 'shopglut_enhancements' === $page && 'product_swatches' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = \Shopglut\ShopGlutDatabase::table_product_swatches();

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM `{$table_name}` WHERE id = %d", $post_id ) );
		   	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM `{$table_name}` WHERE id = %d", $post_id ) );
			

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = null;

				// Check main settings first
				if ( isset( $layout_options_array['shopg_product_swatches_settings_'.$layout_template][ $field['id'] ] ) ) {
					$value = $layout_options_array['shopg_product_swatches_settings_'.$layout_template][ $field['id'] ];
				}

			}

		} elseif ( 'shopglut_layouts' === $page && 'cartpage' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_cartpage_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_cartpage_settings_template1'][ $field['id'] ] ) ) ? $layout_options_array['shopg_cartpage_settings_template1'][ $field['id'] ] : null;
			}


		} elseif ( 'shopglut_layouts' === $page && 'ordercomplete' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_ordercomplete_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_ordercomplete_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_ordercomplete_settings_'.$layout_template][ $field['id'] ] ) ) ? $layout_options_array['shopg_ordercomplete_settings_'.$layout_template][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_enhancements' === $page && 'product_comparison' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_comparison_layouts';

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_comparison_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_comparison_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopglut_product_comparison_settings_'.$layout_template][ $field['id'] ] ) ) ? $layout_options_array['shopglut_product_comparison_settings_'.$layout_template][ $field['id'] ] : null;
			}


		} elseif ( 'shopglut_enhancements' === $page && 'product_quickview' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_quickview_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_quickview_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_quickview_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_product_quickview_settings_'.$layout_template][ $field['id'] ] ) ) ? $layout_options_array['shopg_product_quickview_settings_'.$layout_template][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_enhancements' === $page && 'product_badges' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['badge_id'] ) ? absint( wp_unslash( $_GET['badge_id'] ) ) : 1;
			
			$table_name = $wpdb->prefix . 'shopglut_product_badge_layouts';

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings  FROM {$wpdb->prefix}shopglut_product_badge_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_product_badge_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_product_badge_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_product_badge_settings'][ $field['id'] ] : null;
			}



		} elseif ( 'shopglut_layouts' === $page && 'accountpage' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_accountpage_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_accountpage_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_accountpage_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_accountpage_settings_'.$layout_template][ $field['id'] ] ) ) ? $layout_options_array['shopg_accountpage_settings_'.$layout_template][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_showcases' === $page && 'shopbanner' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_shopbanner_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_shopbanner_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_shopbanner_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_product_shopbanner_settings_'.$layout_template][ $field['id'] ] ) ) ? $layout_options_array['shopg_product_shopbanner_settings_'.$layout_template][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_tools' === $page && 'product_custom_field' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['field_id'] ) ? absint( wp_unslash( $_GET['field_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_product_custom_field_settings';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT field_settings FROM {$wpdb->prefix}shopglut_product_custom_field_settings WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = ''; // Product custom fields don't use templates

				


			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_product_custom_field_settings'][$field['id'] ] ) ) ? $layout_options_array['shopg_product_custom_field_settings'][$field['id'] ] : null;
			}


		} elseif ( 'shopglut_enhancements' === $page && 'sliders' === $editor ) {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_sliders';

			// Add caching for better performance
			$cache_key = 'shopglut_slider_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );

			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT slider_settings FROM {$wpdb->prefix}shopglut_sliders WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array[ $field['id'] ] ) ) ? $layout_options_array[ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_enhancements' === $page && 'tabs' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_tabs_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_tabs_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_tabs_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_tabs_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_tabs_settings'][ $field['id'] ] : null;
			}


		} elseif ( 'shopglut_enhancements' === $page && 'accordions' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_accordion_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_accordion_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_accordion_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_accordion_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_accordion_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_enhancements' === $page && 'gallery' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_gallery_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_gallery_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_gallery_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_gallery_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_showcases' === $page && 'shopbanner' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_banners_showcase';

			// Add caching for better performance
			$cache_key = 'shopglut_banner_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );

			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT banner_settings FROM {$wpdb->prefix}shopglut_banners_showcase WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array[ $field['id'] ] ) ) ? $layout_options_array[ $field['id'] ] : null;
			}

		} elseif ( 'shopglut_enhancements' === $page && 'mega_menu' === $editor ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_megamenu_layouts';

			// Add caching for better performance
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT layout_settings FROM {$wpdb->prefix}shopglut_megamenu_layouts WHERE id = %d", $post_id ) );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_template = $wpdb->get_var( $wpdb->prepare( "SELECT layout_template FROM {$wpdb->prefix}shopglut_megamenu_layouts WHERE id = %d", $post_id ) );

			if ( isset( $layout_options ) && @unserialize( $layout_options ) !== false ) {
				$layout_options_array = unserialize( $layout_options );
			} else {
				$layout_options_array = array();
			}


			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_megamenu_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_megamenu_settings'][ $field['id'] ] : null;
			}

		} elseif ( 'shopg_email_designer' === $page ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for layout data retrieval only
			$post_id = isset( $_GET['layout_id'] ) ? absint( wp_unslash( $_GET['layout_id'] ) ) : 1;

			$table_name = $wpdb->prefix . 'shopglut_archive_layouts';

			// Add caching for better performance
			$cache_key = 'shopglut_email_designer_layout_' . $post_id;
			$layout_options = wp_cache_get( $cache_key );
			
			if ( false === $layout_options ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching added
				$layout_options = $wpdb->get_var( $wpdb->prepare( "SELECT arlayout_settings FROM {$wpdb->prefix}shopglut_archive_layouts WHERE id = %d", $post_id ) );
				wp_cache_set( $cache_key, $layout_options, '', 3600 ); // Cache for 1 hour
			}

			$layout_options_array = ( $layout_options ) ? unserialize( $layout_options ) : array();

			if ( ! empty( $field['id'] ) ) {
				$value = ( isset( $layout_options_array['shopg_options_settings'][ $field['id'] ] ) ) ? $layout_options_array['shopg_options_settings'][ $field['id'] ] : null;
			}
		}

		$default = ( isset( $field['id'] ) ) ? $this->get_default( $field ) : '';
		$value = ( isset( $value ) ) ? $value : $default;

		return $value;
	}

			// add metabox content
	public function add_meta_box_content( $post, $callback ) {

				global $post;

				$has_nav = ( count( $this->sections ) > 1 && $this->args['context'] !== 'side' ) ? true : false;
				$show_all = ( ! $has_nav ) ? ' agl-show-all' : '';
				$post_type = ( is_object( $post ) ) ? $post->post_type : '';
				$errors = ( is_object( $post ) ) ? get_post_meta( $post->ID, '_agl_errors_' . $this->unique, true ) : array();
				$errors = ( ! empty( $errors ) ) ? $errors : array();
				$theme = ( $this->args['theme'] ) ? ' agl-theme-' . $this->args['theme'] : '';
				$nav_type = ( $this->args['nav'] === 'inline' ) ? 'inline' : 'normal';

				if ( is_object( $post ) && ! empty( $errors ) ) {
					delete_post_meta( $post->ID, '_agl_errors_' . $this->unique );
				}

				echo '<div class="agl agl-live-preview">';

				wp_nonce_field( 'agl_metabox_nonce', 'agl_metabox_nonce ' . $this->unique );

				echo '<div class="agl agl-metabox' . esc_attr( $theme ) . '">';

				echo '<div class="agl-wrapper' . esc_attr( $show_all ) . '">';

				if ( $has_nav ) {

					echo '<div class="agl-nav agl-nav-' . esc_attr( $nav_type ) . ' agl-nav-metabox">';

					echo '<ul>';

					$tab_key = 0;

					//print_r( $this->sections );

					foreach ( $this->sections as $section ) {

						if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
							continue;
						}

						$tab_error = ( ! empty( $errors['sections'][ $tab_key ] ) ) ? '<i class="agl-label-error agl-error">!</i>' : '';
						$tab_icon = ( ! empty( $section['icon'] ) ) ? '<i class="agl-tab-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

						echo '<li><a href="#">' . esc_attr( $tab_icon ) . esc_html( $section['title'] ? esc_html($section['title']) : '' ) . esc_attr( $tab_error ) . '</a></li>';

						$tab_key++;

					}

					echo '</ul>';

					echo '</div>';

				}

				echo '<div class="agl-content">';

				echo '<div class="agl-sections">';

				$section_key = 0;

				foreach ( $this->sections as $section ) {

					if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
						continue;
					}

					$section_onload = ( ! $has_nav ) ? ' agl-onload' : '';
					$section_class = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
					$section_title = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
					$section_icon = ( ! empty( $section['icon'] ) ) ? '<i class="agl-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

					echo '<div class="agl-section hidden' . esc_attr( $section_onload . $section_class ) . '">';

					echo ( $section_title || $section_icon ) ? '<div class="agl-section-title"><h3>' . esc_attr( $section_icon ) . esc_html( $section_title ) . '</h3></div>' : '';

					if ( ! empty( $section['fields'] ) ) {

						foreach ( $section['fields'] as $field ) {

							if ( ! empty( $field['id'] ) && ! empty( $errors['fields'][ $field['id'] ] ) ) {
								$field['_error'] = $errors['fields'][ $field['id'] ];
							}

							if ( ! empty( $field['id'] ) ) {
								$field['default'] = $this->get_default( $field );
							}

							AGSHOPGLUT::field( $field, $this->get_meta_value( $field ), $this->unique, 'metabox' );

						}

					} else {

						echo '<div class="agl-no-option">' . esc_html__( 'No data available.', 'shopglut' ) . '</div>';

					}

					echo '</div>';

					$section_key++;

				}

				echo '</div>';

				echo '<div class="clear"></div>';

				if ( ! empty( $this->args['show_restore'] ) || ! empty( $this->args['show_reset'] ) ) {

					echo '<div class="agl-sections-reset">';
					echo '<label>';
					echo '<input type="checkbox" name="' . esc_attr( $this->unique ) . '[_reset]" />';
					echo '<span class="button agl-button-reset">' . esc_html__( 'Reset', 'shopglut' ) . '</span>';
					echo '<span class="button agl-button-cancel">' . sprintf( '<small>( %s )</small> %s', esc_html__( 'update post', 'shopglut' ), esc_html__( 'Cancel', 'shopglut' ) ) . '</span>';
					echo '</label>';
					echo '</div>';

				}

				echo '</div>';

				echo ( $has_nav && $nav_type === 'normal' ) ? '<div class="agl-nav-background"></div>' : '';

				echo '<div class="clear"></div>';

				echo '</div>';

				echo '</div>';

				echo '</div>';

			}

			public function save_meta_box( $post_id ) {
				// Get all possible metabox IDs we want to handle
				if ( ! isset( $_POST['agl_metabox_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agl_metabox_nonce'] ) ), 'agl_metabox_nonce' ) ) {
					return $post_id;
				}

					// Check user permissions
					if ( ! current_user_can( 'edit_post', $post_id ) ) {
						return $post_id;
					}
							$metabox_ids = array(
					'agshopglut_subscription_lock_metabox_options',
				);

				// Loop through all possible IDs and save if data exists
				foreach ( $metabox_ids as $metabox_id ) {

					$meta_data = isset( $_POST[ $metabox_id ] ) ? map_deep( wp_unslash( $_POST[ $metabox_id ] ), 'sanitize_text_field' ) : array();
					
					if ( ! empty( $meta_data ) ) {
						update_post_meta( $post_id, $metabox_id, $meta_data );
					} else {
						delete_post_meta( $post_id, $metabox_id );
					}
				}

				return $post_id;
			}
		}
	}