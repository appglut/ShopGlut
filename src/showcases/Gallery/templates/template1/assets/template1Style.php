<?php
namespace Shopglut\showcases\Gallery\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Style {

	public function dynamicCss($layout_id) {
		$settings = $this->getLayoutSettings($layout_id);

		$columns = isset($settings['grid_columns']) ? intval($settings['grid_columns']) : 3;
		$gap = isset($settings['grid_gap']) ? intval($settings['grid_gap']) : 10;
		$border_radius = isset($settings['border_radius']) ? intval($settings['border_radius']) : 8;
		$enable_lightbox = isset($settings['enable_lightbox']) ? (bool) $settings['enable_lightbox'] : true;
		$enable_captions = isset($settings['enable_captions']) ? (bool) $settings['enable_captions'] : true;

		$custom_css = "
/* ShopGlut Gallery Template1 - Simple Grid Gallery */
.shopglut-gallery-simple-grid.template1 {
	margin: 20px 0;
}

.shopglut-gallery-simple-grid.template1 .gallery-grid {
	display: grid;
	align-items: start;
	width: 100%;
}

.shopglut-gallery-simple-grid.template1 .gallery-item {
	position: relative;
	overflow: hidden;
	background: #f8f9fa;
	transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.shopglut-gallery-simple-grid.template1 .gallery-item:hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.shopglut-gallery-simple-grid.template1 .gallery-lightbox-link {
	display: block;
	text-decoration: none;
	color: inherit;
}

.shopglut-gallery-simple-grid.template1 .image-wrapper {
	position: relative;
	width: 100%;
	overflow: hidden;
	background: #ffffff;
}

.shopglut-gallery-simple-grid.template1 .image-wrapper img {
	width: 100%;
	height: 250px;
	object-fit: cover;
	display: block;
	transition: transform 0.3s ease;
}

.shopglut-gallery-simple-grid.template1 .gallery-item:hover .image-wrapper img {
	transform: scale(1.05);
}

.shopglut-gallery-simple-grid.template1 .image-overlay {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.6);
	display: flex;
	align-items: center;
	justify-content: center;
	opacity: 0;
	transition: opacity 0.3s ease;
	backdrop-filter: blur(2px);
}

.shopglut-gallery-simple-grid.template1 .gallery-item:hover .image-overlay {
	opacity: 1;
}

.shopglut-gallery-simple-grid.template1 .overlay-content {
	text-align: center;
	color: #ffffff;
}

.shopglut-gallery-simple-grid.template1 .overlay-content svg {
	width: 32px;
	height: 32px;
	margin-bottom: 8px;
	stroke: #ffffff;
	opacity: 0;
	transform: translateY(10px);
	transition: all 0.3s ease 0.1s;
}

.shopglut-gallery-simple-grid.template1 .overlay-content span {
	display: block;
	font-size: 14px;
	font-weight: 500;
	opacity: 0;
	transform: translateY(10px);
	transition: all 0.3s ease 0.2s;
}

.shopglut-gallery-simple-grid.template1 .gallery-item:hover .overlay-content svg,
.shopglut-gallery-simple-grid.template1 .gallery-item:hover .overlay-content span {
	opacity: 1;
	transform: translateY(0);
}

.shopglut-gallery-simple-grid.template1 .image-caption {
	padding: 12px;
	background: #ffffff;
	border-top: 1px solid #e9ecef;
}

.shopglut-gallery-simple-grid.template1 .image-caption span {
	display: block;
	font-size: 14px;
	color: #495057;
	text-align: center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

/* Lightbox Styles */
.shopglut-gallery-simple-grid.template1 .gallery-lightbox-modal {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 999999;
	display: none;
	align-items: center;
	justify-content: center;
}

.shopglut-gallery-simple-grid.template1 .lightbox-overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.9);
	backdrop-filter: blur(5px);
}

.shopglut-gallery-simple-grid.template1 .lightbox-content {
	position: relative;
	max-width: 90vw;
	max-height: 90vh;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
}

.shopglut-gallery-simple-grid.template1 .lightbox-close,
.shopglut-gallery-simple-grid.template1 .lightbox-prev,
.shopglut-gallery-simple-grid.template1 .lightbox-next {
	position: absolute;
	background: rgba(255, 255, 255, 0.1);
	border: none;
	color: #ffffff;
	cursor: pointer;
	border-radius: 50%;
	width: 48px;
	height: 48px;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s ease;
	z-index: 10;
	backdrop-filter: blur(10px);
}

.shopglut-gallery-simple-grid.template1 .lightbox-close:hover,
.shopglut-gallery-simple-grid.template1 .lightbox-prev:hover,
.shopglut-gallery-simple-grid.template1 .lightbox-next:hover {
	background: rgba(255, 255, 255, 0.2);
	transform: scale(1.1);
}

.shopglut-gallery-simple-grid.template1 .lightbox-close {
	top: 20px;
	right: 20px;
}

.shopglut-gallery-simple-grid.template1 .lightbox-prev {
	left: 20px;
	top: 50%;
	transform: translateY(-50%);
}

.shopglut-gallery-simple-grid.template1 .lightbox-next {
	right: 20px;
	top: 50%;
	transform: translateY(-50%);
}

.shopglut-gallery-simple-grid.template1 .lightbox-image-container {
	max-width: 90vw;
	max-height: 70vh;
	display: flex;
	align-items: center;
	justify-content: center;
}

.shopglut-gallery-simple-grid.template1 .lightbox-image {
	max-width: 100%;
	max-height: 100%;
	object-fit: contain;
	border-radius: 8px;
	box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.shopglut-gallery-simple-grid.template1 .lightbox-caption {
	margin-top: 20px;
	text-align: center;
}

.shopglut-gallery-simple-grid.template1 .caption-title {
	color: #ffffff;
	font-size: 18px;
	font-weight: 500;
	margin: 0;
	text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

/* Demo Mode Styles */
.shopglut-gallery-demo-container {
	max-width: 800px;
	margin: 0 auto;
	padding: 20px;
	background: #f8f9fa;
	border-radius: 12px;
	border: 1px solid #e9ecef;
}

.shopglut-gallery-demo-container .demo-header {
	text-align: center;
	margin-bottom: 30px;
}

.shopglut-gallery-demo-container .demo-header h3 {
	font-size: 24px;
	font-weight: 600;
	color: #2c3e50;
	margin-bottom: 10px;
}

.shopglut-gallery-demo-container .demo-header p {
	font-size: 16px;
	color: #6c757d;
	line-height: 1.6;
}

.shopglut-gallery-simple-grid.template1.demo-mode {
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
	border-radius: 12px;
	overflow: hidden;
	background: #ffffff;
	padding: 20px;
}

/* Responsive Design */
@media (max-width: 1200px) {
	.shopglut-gallery-simple-grid.template1 .gallery-grid {
		grid-template-columns: repeat(2, 1fr) !important;
	}
}

@media (max-width: 768px) {
	.shopglut-gallery-simple-grid.template1 .gallery-grid {
		grid-template-columns: 1fr 1fr !important;
		gap: 8px !important;
	}

	.shopglut-gallery-simple-grid.template1 .image-wrapper img {
		height: 200px;
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-content {
		max-width: 95vw;
		max-height: 95vh;
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-close,
	.shopglut-gallery-simple-grid.template1 .lightbox-prev,
	.shopglut-gallery-simple-grid.template1 .lightbox-next {
		width: 40px;
		height: 40px;
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-close {
		top: 10px;
		right: 10px;
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-prev {
		left: 10px;
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-next {
		right: 10px;
	}

	.shopglut-gallery-demo-container {
		padding: 15px;
		margin: 10px;
	}
}

@media (max-width: 480px) {
	.shopglut-gallery-simple-grid.template1 .gallery-grid {
		grid-template-columns: 1fr !important;
		gap: 6px !important;
	}

	.shopglut-gallery-simple-grid.template1 .image-wrapper img {
		height: 250px;
	}

	.shopglut-gallery-simple-grid.template1 .overlay-content span {
		font-size: 13px;
	}

	.shopglut-gallery-simple-grid.template1 .image-caption {
		padding: 8px;
	}

	.shopglut-gallery-simple-grid.template1 .image-caption span {
		font-size: 13px;
		white-space: normal;
		line-height: 1.4;
	}

	.shopglut-gallery-demo-container .demo-header h3 {
		font-size: 20px;
	}

	.shopglut-gallery-demo-container .demo-header p {
		font-size: 14px;
	}
}

/* Loading States */
.shopglut-gallery-simple-grid.template1 .gallery-item.loading {
	background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
	background-size: 200% 100%;
	animation: loading 1.5s infinite;
}

@keyframes loading {
	0% { background-position: 200% 0; }
	100% { background-position: -200% 0; }
}

/* Accessibility */
.shopglut-gallery-simple-grid.template1 .gallery-lightbox-link:focus {
	outline: 3px solid #007cba;
	outline-offset: 2px;
}

.shopglut-gallery-simple-grid.template1 .lightbox-close:focus,
.shopglut-gallery-simple-grid.template1 .lightbox-prev:focus,
.shopglut-gallery-simple-grid.template1 .lightbox-next:focus {
	outline: 2px solid #ffffff;
	outline-offset: 2px;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
	.shopglut-gallery-simple-grid.template1 .image-overlay {
		background: rgba(0, 0, 0, 0.8);
	}

	.shopglut-gallery-simple-grid.template1 .lightbox-overlay {
		background: rgba(0, 0, 0, 0.95);
	}
}
";

		return $custom_css;
	}

	/**
	 * Get layout settings from database
	 */
	private function getLayoutSettings($layout_id) {
		if (!$layout_id) {
			return $this->getDefaultSettings();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_gallery_layouts';

		$layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
			sprintf("SELECT layout_settings, layout_template FROM `%s` WHERE id = %d", esc_sql($table_name), absint($layout_id)) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Using sprintf with escaped table name and validated ID
		);

		if (!$layout_data || empty($layout_data->layout_settings)) {
			return $this->getDefaultSettings();
		}

		$settings = maybe_unserialize($layout_data->layout_settings);

		if ($settings === false || !is_array($settings)) {
			return $this->getDefaultSettings();
		}

		// Try different possible settings structures
		$gallery_settings = null;

		if (isset($settings['shopg_product_gallery_settings_template1'])) {
			$gallery_settings = $this->flattenSettings($settings['shopg_product_gallery_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$gallery_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['grid_columns']) || isset($settings['gallery_settings'])) {
			$gallery_settings = $this->flattenSettings($settings);
		}

		if ($gallery_settings) {
			return $gallery_settings;
		}

		return $this->getDefaultSettings();
	}

	/**
	 * Flatten nested settings structure
	 */
	private function flattenSettings($nested_settings) {
		$flat_settings = [];

		foreach ($nested_settings as $group_key => $group_values) {
			if (is_array($group_values)) {
				foreach ($group_values as $setting_key => $setting_value) {
					if (is_array($setting_value) && isset($setting_value[$setting_key])) {
						$flat_settings[$setting_key] = $setting_value[$setting_key];
					} else {
						$flat_settings[$setting_key] = $setting_value;
					}
				}
			} else {
				$flat_settings[$group_key] = $group_values;
			}
		}

		// Recursively flatten if there are still nested arrays
		foreach ($flat_settings as $key => $value) {
			if (is_array($value)) {
				unset($flat_settings[$key]);
				$flat_settings = array_merge($flat_settings, $this->flattenSettings($value));
			}
		}

		return array_merge($this->getDefaultSettings(), $flat_settings);
	}

	/**
	 * Get default settings values
	 */
	private function getDefaultSettings() {
		return array(
			'grid_columns' => 3,
			'grid_gap' => 10,
			'border_radius' => 8,
			'enable_lightbox' => true,
			'enable_captions' => true,
			'layout_id' => 0
		);
	}
}