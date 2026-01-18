<?php
/**
 * Gallery Admin Interface
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

namespace Shopglut\galleryShortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GalleryAdmin {

    /**
     * Single instance of the class
     *
     * @var GalleryAdmin
     */
    private static $instance = null;

    /**
     * Menu slug
     *
     * @var string
     */
    private $menu_slug = 'shopglut-gallery-shortcode';

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('wp_ajax_gallery_save', [$this, 'ajax_save_gallery']);
        add_action('wp_ajax_gallery_delete', [$this, 'ajax_delete_gallery']);
        add_action('wp_ajax_gallery_duplicate', [$this, 'ajax_duplicate_gallery']);
        add_action('wp_ajax_gallery_get_preview', [$this, 'ajax_get_gallery_preview']);
    }

    /**
     * Get single instance of the class
     *
     * @return GalleryAdmin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'shopglut',
            __('Gallery Shortcode', 'shopglut'),
            __('Gallery Shortcode', 'shopglut'),
            'manage_options',
            $this->menu_slug,
            [$this, 'render_admin_page']
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, $this->menu_slug) === false) {
            return;
        }

        // Admin styles
        wp_enqueue_style(
            'shopglut-gallery-admin',
            SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/css/admin.css',
            [],
            SHOPGLUT_VERSION
        );

        // Admin scripts
        wp_enqueue_script(
            'shopglut-gallery-admin',
            SHOPGLUT_URL . 'src/tools/galleryShortcode/assets/js/admin.js',
            ['jquery', 'wp-color-picker'],
            SHOPGLUT_VERSION,
            true
        );

        // Color picker
        wp_enqueue_style('wp-color-picker');

        // Media uploader
        wp_enqueue_media();

        // Pass data to admin script
        wp_localize_script('shopglut-gallery-admin', 'shopglutGalleryAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shopglut_gallery_admin_nonce'),
            'strings' => [
                'confirm_delete' => __('Are you sure you want to delete this gallery?', 'shopglut'),
                'confirm_duplicate' => __('Are you sure you want to duplicate this gallery?', 'shopglut'),
                'gallery_saved' => __('Gallery saved successfully!', 'shopglut'),
                'gallery_deleted' => __('Gallery deleted successfully!', 'shopglut'),
                'gallery_duplicated' => __('Gallery duplicated successfully!', 'shopglut'),
                'error_occurred' => __('An error occurred. Please try again.', 'shopglut'),
            ]
        ]);
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        $view = isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : 'list'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only
        $gallery_id = isset($_GET['gallery_id']) ? absint(wp_unslash($_GET['gallery_id'])) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe admin page parameter check for routing only

        switch ($view) {
            case 'edit':
            case 'add':
                $this->render_gallery_editor($gallery_id);
                break;
            default:
                $this->render_gallery_list();
                break;
        }
    }

    /**
     * Render gallery list
     */
    private function render_gallery_list() {
        $galleries = GalleryDataTables::get_galleries(['limit' => 100]);
        $templates = GalleryDataTables::get_templates(['is_default' => 'yes']);
        ?>

        <div class="wrap shopglut-gallery-admin">
            <h1 class="wp-heading-inline">
                <?php esc_html_e('Gallery Shortcodes', 'shopglut'); ?>
            </h1>

            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->menu_slug . '&view=add')); ?>"
               class="page-title-action">
                <?php esc_html_e('Add New Gallery', 'shopglut'); ?>
            </a>

            <hr class="wp-header-end">

            <div class="shopglut-gallery-overview">
                <div class="shopglut-gallery-stats">
                    <div class="stat-card">
                        <h3><?php echo intval(count($galleries)); ?></h3>
                        <p><?php esc_html_e('Total Galleries', 'shopglut'); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo intval(count($templates)); ?></h3>
                        <p><?php esc_html_e('Available Templates', 'shopglut'); ?></p>
                    </div>
                </div>
            </div>

            <div class="shopglut-gallery-list-container">
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <button type="button" class="button action bulk-delete-btn" disabled>
                            <?php esc_html_e('Delete Selected', 'shopglut'); ?>
                        </button>
                    </div>
                    <div class="alignright">
                        <input type="text" class="regular-text search-galleries" placeholder="<?php esc_attr_e('Search galleries...', 'shopglut'); ?>">
                    </div>
                    <div class="clear"></div>
                </div>

                <table class="wp-list-table widefat fixed striped shopglut-gallery-table">
                    <thead>
                        <tr>
                            <th class="manage-column column-cb check-column">
                                <input type="checkbox" id="cb-select-all-1">
                            </th>
                            <th class="manage-column"><?php esc_html_e('Gallery Name', 'shopglut'); ?></th>
                            <th class="manage-column"><?php esc_html_e('Layout', 'shopglut'); ?></th>
                            <th class="manage-column"><?php esc_html_e('Products', 'shopglut'); ?></th>
                            <th class="manage-column"><?php esc_html_e('Shortcode', 'shopglut'); ?></th>
                            <th class="manage-column"><?php esc_html_e('Created', 'shopglut'); ?></th>
                            <th class="manage-column"><?php esc_html_e('Actions', 'shopglut'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($galleries)): ?>
                            <?php foreach ($galleries as $gallery): ?>
                                <tr>
                                    <th class="check-column">
                                        <input type="checkbox" name="gallery[]" value="<?php echo intval($gallery['id']); ?>">
                                    </th>
                                    <td>
                                        <strong>
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->menu_slug . '&view=edit&gallery_id=' . $gallery['id'])); ?>">
                                                <?php echo esc_html($gallery['gallery_name']); ?>
                                            </a>
                                        </strong>
                                        <?php if (!empty($gallery['gallery_description'])): ?>
                                            <div class="row-actions">
                                                <span class="description"><?php echo esc_html(substr($gallery['gallery_description'], 0, 100)); ?>...</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="gallery-layout-badge layout-<?php echo esc_attr($gallery['layout']); ?>">
                                            <?php echo esc_html(ucfirst($gallery['layout'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html($gallery['items_per_page']); ?></td>
                                    <td>
                                        <code>[shopglut_gallery id="<?php echo intval($gallery['id']); ?>"]</code>
                                        <button type="button" class="button button-small copy-shortcode"
                                                data-shortcode="[shopglut_gallery id=&quot;<?php echo intval($gallery['id']); ?>&quot;]">
                                            <?php esc_html_e('Copy', 'shopglut'); ?>
                                        </button>
                                    </td>
                                    <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($gallery['created_at']))); ?></td>
                                    <td>
                                        <div class="row-actions">
                                            <span class="edit">
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->menu_slug . '&view=edit&gallery_id=' . $gallery['id'])); ?>">
                                                    <?php esc_html_e('Edit', 'shopglut'); ?>
                                                </a>
                                            </span>
                                            |
                                            <span class="duplicate">
                                                <a href="#" class="duplicate-gallery" data-id="<?php echo intval($gallery['id']); ?>">
                                                    <?php esc_html_e('Duplicate', 'shopglut'); ?>
                                                </a>
                                            </span>
                                            |
                                            <span class="trash">
                                                <a href="#" class="delete-gallery" data-id="<?php echo intval($gallery['id']); ?>">
                                                    <?php esc_html_e('Delete', 'shopglut'); ?>
                                                </a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">
                                    <div class="no-galleries">
                                        <p><?php esc_html_e('No galleries found. Create your first gallery to get started!', 'shopglut'); ?></p>
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->menu_slug . '&view=add')); ?>"
                                           class="button button-primary">
                                            <?php esc_html_e('Create First Gallery', 'shopglut'); ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }

    /**
     * Render gallery editor
     *
     * @param int $gallery_id Gallery ID
     */
    private function render_gallery_editor($gallery_id) {
        $gallery = null;
        $is_edit = false;

        if ($gallery_id > 0) {
            $gallery = GalleryDataTables::get_gallery($gallery_id);
            $is_edit = !empty($gallery);
        }

        // Get templates
        $templates = GalleryDataTables::get_templates();

        // Get product categories
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $product_tags = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        ]);
        ?>

        <div class="wrap shopglut-gallery-admin">
            <h1 class="wp-heading-inline">
                <?php echo $is_edit ? esc_html__('Edit Gallery', 'shopglut') : esc_html__('Add New Gallery', 'shopglut'); ?>
            </h1>

            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->menu_slug)); ?>"
               class="page-title-action">
                <?php esc_html_e('Back to Gallery List', 'shopglut'); ?>
            </a>

            <hr class="wp-header-end">

            <form id="gallery-form" class="shopglut-gallery-form">
                <div class="gallery-form-wrapper">
                    <div class="gallery-form-main">
                        <div class="postbox">
                            <h2 class="hndle"><?php esc_html_e('Gallery Settings', 'shopglut'); ?></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th>
                                            <label for="gallery_name"><?php esc_html_e('Gallery Name', 'shopglut'); ?> *</label>
                                        </th>
                                        <td>
                                            <input type="text" id="gallery_name" name="gallery_name"
                                                   value="<?php echo $is_edit ? esc_attr($gallery['gallery_name']) : ''; ?>"
                                                   class="regular-text required">
                                            <p class="description"><?php esc_html_e('Internal name for this gallery', 'shopglut'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="gallery_description"><?php esc_html_e('Description', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <textarea id="gallery_description" name="gallery_description"
                                                      rows="3" class="large-text"><?php echo $is_edit ? esc_textarea($gallery['gallery_description']) : ''; ?></textarea>
                                            <p class="description"><?php esc_html_e('Optional description for this gallery', 'shopglut'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="postbox">
                            <h2 class="hndle"><?php esc_html_e('Layout & Display', 'shopglut'); ?></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th>
                                            <label for="layout"><?php esc_html_e('Layout', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <select id="layout" name="layout" class="layout-selector">
                                                <option value="grid" <?php selected($is_edit && $gallery['layout'] === 'grid'); ?>><?php esc_html_e('Grid', 'shopglut'); ?></option>
                                                <option value="isotope" <?php selected($is_edit && $gallery['layout'] === 'isotope'); ?>><?php esc_html_e('Isotope (Filterable)', 'shopglut'); ?></option>
                                                <option value="carousel" <?php selected($is_edit && $gallery['layout'] === 'carousel'); ?>><?php esc_html_e('Carousel', 'shopglut'); ?></option>
                                                <option value="masonry" <?php selected($is_edit && $gallery['layout'] === 'masonry'); ?>><?php esc_html_e('Masonry', 'shopglut'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="layout-settings grid-settings isotope-settings masonry-settings">
                                        <th>
                                            <label><?php esc_html_e('Columns', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <div class="column-controls">
                                                <div>
                                                    <label><?php esc_html_e('Desktop:', 'shopglut'); ?></label>
                                                    <select name="columns" class="small-text">
                                                        <?php for ($i = 1; $i <= 6; $i++): ?>
                                                            <option value="<?php echo esc_attr($i); ?>" <?php selected($is_edit && intval($gallery['columns']) === $i); ?>><?php echo esc_html($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label><?php esc_html_e('Tablet:', 'shopglut'); ?></label>
                                                    <select name="columns_tablet" class="small-text">
                                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                                            <option value="<?php echo esc_attr($i); ?>" <?php selected($is_edit && intval($gallery['columns_tablet']) === $i); ?>><?php echo esc_html($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label><?php esc_html_e('Mobile:', 'shopglut'); ?></label>
                                                    <select name="columns_mobile" class="small-text">
                                                        <?php for ($i = 1; $i <= 2; $i++): ?>
                                                            <option value="<?php echo esc_attr($i); ?>" <?php selected($is_edit && intval($gallery['columns_mobile']) === $i); ?>><?php echo esc_html($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="spacing"><?php esc_html_e('Spacing', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <select id="spacing" name="spacing">
                                                <option value="none" <?php selected($is_edit && $gallery['spacing'] === 'none'); ?>><?php esc_html_e('None', 'shopglut'); ?></option>
                                                <option value="small" <?php selected($is_edit && $gallery['spacing'] === 'small'); ?>><?php esc_html_e('Small', 'shopglut'); ?></option>
                                                <option value="medium" <?php selected($is_edit && $gallery['spacing'] === 'medium'); ?>><?php esc_html_e('Medium', 'shopglut'); ?></option>
                                                <option value="large" <?php selected($is_edit && $gallery['spacing'] === 'large'); ?>><?php esc_html_e('Large', 'shopglut'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="layout-settings isotope-settings">
                                        <th>
                                            <label><?php esc_html_e('Filter', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <div>
                                                <label>
                                                    <input type="checkbox" name="enable_filter" value="yes" <?php checked($is_edit && $gallery['enable_filter'] === 'yes'); ?>>
                                                    <?php esc_html_e('Enable category filter', 'shopglut'); ?>
                                                </label>
                                            </div>
                                            <div>
                                                <label><?php esc_html_e('Filter Position:', 'shopglut'); ?></label>
                                                <select name="filter_position">
                                                    <option value="top" <?php selected($is_edit && $gallery['filter_position'] === 'top'); ?>><?php esc_html_e('Top', 'shopglut'); ?></option>
                                                    <option value="bottom" <?php selected($is_edit && $gallery['filter_position'] === 'bottom'); ?>><?php esc_html_e('Bottom', 'shopglut'); ?></option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="postbox">
                            <h2 class="hndle"><?php esc_html_e('Product Settings', 'shopglut'); ?></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th>
                                            <label for="items_per_page"><?php esc_html_e('Items Per Page', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <input type="number" id="items_per_page" name="items_per_page"
                                                   value="<?php echo $is_edit ? intval($gallery['items_per_page']) : 12; ?>"
                                                   min="1" max="50" class="small-text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="orderby"><?php esc_html_e('Order By', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <select id="orderby" name="orderby">
                                                <option value="date" <?php selected($is_edit && $gallery['orderby'] === 'date'); ?>><?php esc_html_e('Date', 'shopglut'); ?></option>
                                                <option value="title" <?php selected($is_edit && $gallery['orderby'] === 'title'); ?>><?php esc_html_e('Title', 'shopglut'); ?></option>
                                                <option value="price" <?php selected($is_edit && $gallery['orderby'] === 'price'); ?>><?php esc_html_e('Price', 'shopglut'); ?></option>
                                                <option value="sales" <?php selected($is_edit && $gallery['orderby'] === 'sales'); ?>><?php esc_html_e('Sales', 'shopglut'); ?></option>
                                                <option value="rating" <?php selected($is_edit && $gallery['orderby'] === 'rating'); ?>><?php esc_html_e('Rating', 'shopglut'); ?></option>
                                                <option value="rand" <?php selected($is_edit && $gallery['orderby'] === 'rand'); ?>><?php esc_html_e('Random', 'shopglut'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="order"><?php esc_html_e('Order', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <select id="order" name="order">
                                                <option value="DESC" <?php selected($is_edit && $gallery['order'] === 'DESC'); ?>><?php esc_html_e('Descending', 'shopglut'); ?></option>
                                                <option value="ASC" <?php selected($is_edit && $gallery['order'] === 'ASC'); ?>><?php esc_html_e('Ascending', 'shopglut'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label><?php esc_html_e('Product Categories', 'shopglut'); ?></label>
                                        </th>
                                        <td>
                                            <div class="category-selector">
                                                <select name="category_ids[]" multiple class="category-select">
                                                    <?php foreach ($categories as $category): ?>
                                                        <?php
                                                        $selected = '';
                                                        if ($is_edit && !empty($gallery['category_ids'])) {
                                                            $selected_categories = explode(',', $gallery['category_ids']);
                                                            if (in_array($category->term_id, $selected_categories)) {
                                                                $selected = 'selected';
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo esc_attr(intval($category->term_id)); ?>" <?php echo esc_attr($selected); ?>>
                                                            <?php echo esc_html($category->name); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <p class="description"><?php esc_html_e('Leave empty to show all categories', 'shopglut'); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-form-sidebar">
                        <div class="postbox">
                            <h2 class="hndle"><?php esc_html_e('Publish', 'shopglut'); ?></h2>
                            <div class="inside">
                                <div class="submitbox">
                                    <div id="delete-action">
                                        <?php if ($is_edit): ?>
                                            <a href="#" class="submitdelete deletion delete-gallery" data-id="<?php echo intval($gallery['id']); ?>">
                                                <?php esc_html_e('Delete Gallery', 'shopglut'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div id="publishing-action">
                                        <input type="hidden" name="gallery_id" value="<?php echo intval($gallery_id); ?>">
                                        <input type="hidden" name="action" value="gallery_save">
                                        <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('gallery_save')); ?>">
                                        <?php submit_button($is_edit ? __('Update Gallery', 'shopglut') : __('Create Gallery', 'shopglut'), 'primary', 'save-gallery'); ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div class="postbox">
                            <h2 class="hndle"><?php esc_html_e('Display Options', 'shopglut'); ?></h2>
                            <div class="inside">
                                <div class="display-options">
                                    <div>
                                        <label>
                                            <input type="checkbox" name="show_price" value="yes" <?php checked(!$is_edit || $gallery['show_price'] === 'yes'); ?>>
                                            <?php esc_html_e('Show Price', 'shopglut'); ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="show_title" value="yes" <?php checked(!$is_edit || $gallery['show_title'] === 'yes'); ?>>
                                            <?php esc_html_e('Show Title', 'shopglut'); ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="show_category" value="yes" <?php checked(!$is_edit || $gallery['show_category'] === 'yes'); ?>>
                                            <?php esc_html_e('Show Category', 'shopglut'); ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="show_rating" value="yes" <?php checked(!$is_edit || $gallery['show_rating'] === 'yes'); ?>>
                                            <?php esc_html_e('Show Rating', 'shopglut'); ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="show_add_to_cart" value="yes" <?php checked(!$is_edit || $gallery['show_add_to_cart'] === 'yes'); ?>>
                                            <?php esc_html_e('Show Add to Cart', 'shopglut'); ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="lazy_load" value="yes" <?php checked(!$is_edit || $gallery['lazy_load'] === 'yes'); ?>>
                                            <?php esc_html_e('Lazy Load Images', 'shopglut'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
    }

    /**
     * AJAX save gallery
     */
    public function ajax_save_gallery() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'gallery_save')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions']);
        }

        // Sanitize and validate data
        $gallery_id = isset($_POST['gallery_id']) ? absint(wp_unslash($_POST['gallery_id'])) : 0;
        $gallery_data = [
            'gallery_name' => isset($_POST['gallery_name']) ? sanitize_text_field(wp_unslash($_POST['gallery_name'])) : '',
            'gallery_description' => isset($_POST['gallery_description']) ? sanitize_textarea_field(wp_unslash($_POST['gallery_description'])) : '',
            'layout' => isset($_POST['layout']) ? sanitize_text_field(wp_unslash($_POST['layout'])) : '',
            'columns' => isset($_POST['columns']) ? absint(wp_unslash($_POST['columns'])) : 4,
            'columns_tablet' => isset($_POST['columns_tablet']) ? absint(wp_unslash($_POST['columns_tablet'])) : 3,
            'columns_mobile' => isset($_POST['columns_mobile']) ? absint(wp_unslash($_POST['columns_mobile'])) : 2,
            'spacing' => isset($_POST['spacing']) ? sanitize_text_field(wp_unslash($_POST['spacing'])) : 'medium',
            'enable_filter' => isset($_POST['enable_filter']) ? 'yes' : 'no',
            'filter_position' => isset($_POST['filter_position']) ? sanitize_text_field(wp_unslash($_POST['filter_position'])) : 'top',
            'items_per_page' => isset($_POST['items_per_page']) ? absint(wp_unslash($_POST['items_per_page'])) : 12,
            'orderby' => isset($_POST['orderby']) ? sanitize_text_field(wp_unslash($_POST['orderby'])) : 'date',
            'order' => isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : 'DESC',
            'category_ids' => isset($_POST['category_ids']) ? implode(',', array_map('absint', wp_unslash($_POST['category_ids']))) : '',
            'show_price' => isset($_POST['show_price']) ? 'yes' : 'no',
            'show_title' => isset($_POST['show_title']) ? 'yes' : 'no',
            'show_category' => isset($_POST['show_category']) ? 'yes' : 'no',
            'show_rating' => isset($_POST['show_rating']) ? 'yes' : 'no',
            'show_add_to_cart' => isset($_POST['show_add_to_cart']) ? 'yes' : 'no',
            'lazy_load' => isset($_POST['lazy_load']) ? 'yes' : 'no',
        ];

        // Validate required fields
        if (empty($gallery_data['gallery_name'])) {
            wp_send_json_error(['message' => 'Gallery name is required']);
        }

        // Save gallery
        $result = GalleryDataTables::save_gallery($gallery_data, $gallery_id > 0 ? $gallery_id : null);

        if ($result) {
            wp_send_json_success([
                'message' => __('Gallery saved successfully!', 'shopglut'),
                'gallery_id' => $result,
                'shortcode' => '[shopglut_gallery id="' . $result . '"]'
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to save gallery']);
        }
    }

    /**
     * AJAX delete gallery
     */
    public function ajax_delete_gallery() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_gallery_admin_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions']);
        }

        $gallery_id = isset($_POST['gallery_id']) ? absint(wp_unslash($_POST['gallery_id'])) : 0;

        if ($gallery_id <= 0) {
            wp_send_json_error(['message' => 'Invalid gallery ID']);
        }

        $result = GalleryDataTables::delete_gallery($gallery_id);

        if ($result) {
            wp_send_json_success(['message' => __('Gallery deleted successfully!', 'shopglut')]);
        } else {
            wp_send_json_error(['message' => 'Failed to delete gallery']);
        }
    }

    /**
     * AJAX duplicate gallery
     */
    public function ajax_duplicate_gallery() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_gallery_admin_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions']);
        }

        $gallery_id = isset($_POST['gallery_id']) ? absint(wp_unslash($_POST['gallery_id'])) : 0;

        if ($gallery_id <= 0) {
            wp_send_json_error(['message' => 'Invalid gallery ID']);
        }

        // Get original gallery
        $original_gallery = GalleryDataTables::get_gallery($gallery_id);

        if (!$original_gallery) {
            wp_send_json_error(['message' => 'Gallery not found']);
        }

        // Prepare duplicate data
        $duplicate_data = $original_gallery;
        unset($duplicate_data['id']);
        unset($duplicate_data['created_at']);
        unset($duplicate_data['updated_at']);
        $duplicate_data['gallery_name'] = $original_gallery['gallery_name'] . ' (Copy)';

        // Save duplicate
        $result = GalleryDataTables::save_gallery($duplicate_data);

        if ($result) {
            wp_send_json_success([
                'message' => __('Gallery duplicated successfully!', 'shopglut'),
                'gallery_id' => $result
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to duplicate gallery']);
        }
    }

    /**
     * AJAX get gallery preview
     */
    public function ajax_get_gallery_preview() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'shopglut_gallery_admin_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        $gallery_id = isset($_POST['gallery_id']) ? absint(wp_unslash($_POST['gallery_id'])) : 0;

        if ($gallery_id <= 0) {
            wp_send_json_error(['message' => 'Invalid gallery ID']);
        }

        // Generate preview HTML
        $preview_html = do_shortcode('[shopglut_gallery id="' . $gallery_id . '" items_per_page="6"]');

        wp_send_json_success([
            'preview' => $preview_html
        ]);
    }
}