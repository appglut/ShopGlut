<?php
namespace Shopglut\showcases\Tabs\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Style {

	public function dynamicCss($layout_id) {
		$settings = $this->getLayoutSettings($layout_id);

		$autoplay = isset($settings['autoplay']) ? (bool) $settings['autoplay'] : true;
		$autoplay_speed = isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 3000;
		$show_dots = isset($settings['show_dots']) ? (bool) $settings['show_dots'] : true;
		$show_arrows = isset($settings['show_arrows']) ? (bool) $settings['show_arrows'] : true;
		$slides_per_view = isset($settings['slides_per_view']) ? intval($settings['slides_per_view']) : 4;
		$animation_speed = isset($settings['animation_speed']) ? intval($settings['animation_speed']) : 500;

		$custom_css = "
/* ShopGlut Product Tab Template1 - WooCommerce Product Carousel */
.shopglut-product-tab.template1 {
	margin: 20px 0;
	background: #ffffff;
	border-radius: 12px;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
	overflow: hidden;
}

.shopglut-product-tab.template1 .product-tab-container {
	width: 100%;
}

/* Tab Header */
.shopglut-product-tab.template1 .tab-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 24px 30px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: #ffffff;
}

.shopglut-product-tab.template1 .tab-title {
	margin: 0;
	font-size: 28px;
	font-weight: 700;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.shopglut-product-tab.template1 .tab-controls {
	display: flex;
	gap: 12px;
}

.shopglut-product-tab.template1 .tab-nav {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	background: rgba(255, 255, 255, 0.2);
	border: none;
	border-radius: 50%;
	color: #ffffff;
	cursor: pointer;
	transition: all {$animation_speed}ms ease;
	backdrop-filter: blur(10px);
}

.shopglut-product-tab.template1 .tab-nav:hover {
	background: rgba(255, 255, 255, 0.3);
	transform: scale(1.1);
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.shopglut-product-tab.template1 .tab-nav:disabled {
	opacity: 0.5;
	cursor: not-allowed;
	transform: none;
}

/* Product Tab Track */
.shopglut-product-tab.template1 .product-tab-track {
	position: relative;
	overflow: hidden;
	padding: 30px;
	width: 100%;
}

.shopglut-product-tab.template1 .product-tab-wrapper {
	display: flex;
	transition: transform {$animation_speed}ms cubic-bezier(0.4, 0, 0.2, 1);
	width: 100%;
	height: auto;
}

/* Product Slides */
.shopglut-product-tab.template1 .product-slide {
	flex: 0 0 100%;
	width: 100%;
	min-width: 0;
	max-width: 100%;
}

.shopglut-product-tab.template1 .products-container {
	display: grid;
	grid-template-columns: repeat({$slides_per_view}, 1fr);
	gap: 20px;
	padding: 0 10px;
}

.shopglut-product-tab.template1 .product-slide .product-card {
	height: 100%;
	display: flex;
	flex-direction: column;
}

.shopglut-product-tab.template1 .product-card {
	background: #ffffff;
	border-radius: 16px;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
	overflow: hidden;
	transition: all {$animation_speed}ms cubic-bezier(0.4, 0, 0.2, 1);
	position: relative;
}

.shopglut-product-tab.template1 .product-card:hover {
	transform: translateY(-8px);
	box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* Product Image */
.shopglut-product-tab.template1 .product-image {
	position: relative;
	width: 100%;
	height: 280px;
	overflow: hidden;
	background: #f8f9fa;
}

.shopglut-product-tab.template1 .product-image a {
	display: block;
	width: 100%;
	height: 100%;
	text-decoration: none;
}

.shopglut-product-tab.template1 .product-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform {$animation_speed}ms ease;
}

.shopglut-product-tab.template1 .product-card:hover .product-img {
	transform: scale(1.05);
}

/* Badges */
.shopglut-product-tab.template1 .sale-badge,
.shopglut-product-tab.template1 .out-of-stock-badge {
	position: absolute;
	top: 16px;
	left: 16px;
	padding: 6px 12px;
	border-radius: 20px;
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	z-index: 2;
}

.shopglut-product-tab.template1 .sale-badge {
	background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
	color: #ffffff;
	box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.shopglut-product-tab.template1 .out-of-stock-badge {
	background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
	color: #ffffff;
	box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Product Actions */
.shopglut-product-tab.template1 .product-actions {
	position: absolute;
	top: 16px;
	right: 16px;
	display: flex;
	flex-direction: column;
	gap: 8px;
	opacity: 0;
	transform: translateX(10px);
	transition: all {$animation_speed}ms ease;
	z-index: 2;
}

.shopglut-product-tab.template1 .product-card:hover .product-actions {
	opacity: 1;
	transform: translateX(0);
}

.shopglut-product-tab.template1 .action-btn {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	background: rgba(255, 255, 255, 0.95);
	border: none;
	border-radius: 50%;
	color: #495057;
	text-decoration: none;
	cursor: pointer;
	transition: all {$animation_speed}ms ease;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	backdrop-filter: blur(10px);
}

.shopglut-product-tab.template1 .action-btn:hover {
	background: #ffffff;
	color: #007bff;
	transform: scale(1.1);
	box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.shopglut-product-tab.template1 .action-btn.add-to-cart-btn {
	color: #28a745;
}

.shopglut-product-tab.template1 .action-btn.add-to-cart-btn:hover {
	background: #28a745;
	color: #ffffff;
}

/* Product Info */
.shopglut-product-tab.template1 .product-info {
	padding: 20px;
}

.shopglut-product-tab.template1 .product-categories {
	margin-bottom: 8px;
	font-size: 12px;
	color: #6c757d;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.shopglut-product-tab.template1 .product-categories a {
	color: inherit;
	text-decoration: none;
	transition: color 0.3s ease;
}

.shopglut-product-tab.template1 .product-categories a:hover {
	color: #007bff;
}

.shopglut-product-tab.template1 .product-name {
	margin: 0 0 8px 0;
	font-size: 18px;
	font-weight: 600;
	line-height: 1.4;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.shopglut-product-tab.template1 .product-name a {
	color: #2c3e50;
	text-decoration: none;
	transition: color 0.3s ease;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
}

.shopglut-product-tab.template1 .product-name a:hover {
	color: #007bff;
}

/* Product Rating */
.shopglut-product-tab.template1 .product-rating {
	margin-bottom: 12px;
}

.shopglut-product-tab.template1 .star-rating {
	color: #ffc107;
}

/* Product Price */
.shopglut-product-tab.template1 .product-price {
	margin-bottom: 12px;
	font-size: 20px;
	font-weight: 700;
	color: #2c3e50;
}

.shopglut-product-tab.template1 .regular-price {
	color: #495057;
}

.shopglut-product-tab.template1 .regular-price.has-sale {
	text-decoration: line-through;
	font-size: 16px;
	font-weight: 500;
	color: #6c757d;
	margin-right: 8px;
}

.shopglut-product-tab.template1 .sale-price {
	color: #28a745;
	font-size: 22px;
	font-weight: 700;
}

/* Product SKU */
.shopglut-product-tab.template1 .product-sku {
	display: flex;
	align-items: center;
	gap: 4px;
	font-size: 12px;
	color: #6c757d;
}

.shopglut-product-tab.template1 .sku-label {
	font-weight: 500;
}

/* Dots Navigation */
.shopglut-product-tab.template1 .product-tab-dots {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 8px;
	padding: 20px 30px 30px;
}

.shopglut-product-tab.template1 .dot-item {
	width: 10px;
	height: 10px;
	background: #e9ecef;
	border: none;
	border-radius: 50%;
	cursor: pointer;
	transition: all {$animation_speed}ms ease;
	padding: 0;
	position: relative;
}

.shopglut-product-tab.template1 .dot-item::before {
	content: '';
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: 0;
	height: 0;
	background: #007bff;
	border-radius: 50%;
	transition: all {$animation_speed}ms ease;
}

.shopglut-product-tab.template1 .dot-item:hover {
	background: #dee2e6;
	transform: scale(1.2);
}

.shopglut-product-tab.template1 .dot-item.active {
	background: transparent;
}

.shopglut-product-tab.template1 .dot-item.active::before {
	width: 12px;
	height: 12px;
}

/* Demo Mode Styles */
.shopglut-product-tab-demo-container {
	max-width: 1200px;
	margin: 0 auto;
	padding: 20px;
	background: #f8f9fa;
	border-radius: 12px;
	border: 1px solid #e9ecef;
}

.shopglut-product-tab-demo-container .demo-header {
	text-align: center;
	margin-bottom: 30px;
}

.shopglut-product-tab-demo-container .demo-header h3 {
	font-size: 28px;
	font-weight: 600;
	color: #2c3e50;
	margin-bottom: 10px;
}

.shopglut-product-tab-demo-container .demo-header p {
	font-size: 16px;
	color: #6c757d;
	line-height: 1.6;
}

.shopglut-product-tab.template1.demo-mode {
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Animations */
@keyframes slideIn {
	from {
		opacity: 0;
		transform: translateX(30px);
	}
	to {
		opacity: 1;
		transform: translateX(0);
	}
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}

@keyframes slideUp {
	from {
		opacity: 0;
		transform: translateY(20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.shopglut-product-tab.template1 .product-slide {
	animation: slideIn 0.6s ease-out;
}

.shopglut-product-tab.template1 .product-card {
	animation: fadeIn 0.8s ease-out;
}

/* Loading States */
.shopglut-product-tab.template1 .product-slide.loading {
	background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
	background-size: 200% 100%;
	animation: loading 1.5s infinite;
}

@keyframes loading {
	0% { background-position: 200% 0; }
	100% { background-position: -200% 0; }
}

/* Responsive Design */
@media (max-width: 1200px) {
	.shopglut-product-tab.template1 .tab-header {
		padding: 20px 24px;
	}

	.shopglut-product-tab.template1 .tab-title {
		font-size: 26px;
	}

	.shopglut-product-tab.template1 .product-tab-track {
		padding: 24px;
	}

	.shopglut-product-tab.template1 .products-container {
		grid-template-columns: repeat(3, 1fr);
		gap: 16px;
	}

	.shopglut-product-tab.template1 .product-image {
		height: 240px;
	}

	.shopglut-product-tab.template1 .product-info {
		padding: 18px;
	}

	.shopglut-product-tab.template1 .product-name {
		font-size: 17px;
	}

	.shopglut-product-tab-demo-container {
		padding: 18px;
		margin: 10px;
	}
}

@media (max-width: 768px) {
	.shopglut-product-tab.template1 .tab-header {
		padding: 20px;
		flex-direction: column;
		gap: 16px;
		text-align: center;
	}

	.shopglut-product-tab.template1 .tab-title {
		font-size: 24px;
	}

	.shopglut-product-tab.template1 .product-tab-track {
		padding: 20px;
	}

	.shopglut-product-tab.template1 .products-container {
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		padding: 0 5px;
	}

	.shopglut-product-tab.template1 .product-image {
		height: 220px;
	}

	.shopglut-product-tab.template1 .product-info {
		padding: 16px;
	}

	.shopglut-product-tab.template1 .product-name {
		font-size: 16px;
	}

	.shopglut-product-tab.template1 .product-price {
		font-size: 18px;
	}

	.shopglut-product-tab-demo-container {
		padding: 15px;
		margin: 10px;
	}
}

@media (max-width: 480px) {
	.shopglut-product-tab.template1 .tab-header {
		padding: 16px;
	}

	.shopglut-product-tab.template1 .tab-title {
		font-size: 20px;
	}

	.shopglut-product-tab.template1 .product-tab-track {
		padding: 16px;
	}

	.shopglut-product-tab.template1 .products-container {
		grid-template-columns: 1fr;
		gap: 10px;
		padding: 0 2px;
	}

	.shopglut-product-tab.template1 .product-image {
		height: 200px;
	}

	.shopglut-product-tab.template1 .product-info {
		padding: 12px;
	}

	.shopglut-product-tab.template1 .product-actions {
		flex-direction: row;
		top: auto;
		bottom: 16px;
		right: 16px;
		gap: 6px;
	}

	.shopglut-product-tab.template1 .action-btn {
		width: 36px;
		height: 36px;
	}

	.shopglut-product-tab-demo-container .demo-header h3 {
		font-size: 20px;
	}

	.shopglut-product-tab-demo-container .demo-header p {
		font-size: 14px;
	}
}

/* Accessibility */
.shopglut-product-tab.template1 .tab-nav:focus,
.shopglut-product-tab.template1 .action-btn:focus,
.shopglut-product-tab.template1 .dot-item:focus {
	outline: 3px solid #007cba;
	outline-offset: 2px;
}

.shopglut-product-tab.template1 .product-name a:focus {
	outline: 2px solid #007cba;
	outline-offset: 2px;
	border-radius: 4px;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
	.shopglut-product-tab.template1 {
		border: 2px solid #000000;
	}

	.shopglut-product-tab.template1 .product-card {
		border: 1px solid #000000;
	}

	.shopglut-product-tab.template1 .product-name a {
		color: #000000;
	}

	.shopglut-product-tab.template1 .product-name a:hover {
		color: #0000ff;
		text-decoration: underline;
	}

	.shopglut-product-tab.template1 .action-btn {
		background: #ffffff;
		border: 2px solid #000000;
		color: #000000;
	}

	.shopglut-product-tab.template1 .action-btn:hover {
		background: #ffff00;
		color: #000000;
	}
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
	.shopglut-product-tab.template1 .product-tab-wrapper,
	.shopglut-product-tab.template1 .product-card,
	.shopglut-product-tab.template1 .product-img,
	.shopglut-product-tab.template1 .action-btn,
	.shopglut-product-tab.template1 .dot-item,
	.shopglut-product-tab.template1 .product-actions {
		transition: none;
	}

	.shopglut-product-tab.template1 .product-slide,
	.shopglut-product-tab.template1 .product-card {
		animation: none;
	}
}

/* RTL Support */
.rtl .shopglut-product-tab.template1 .tab-nav svg {
	transform: scaleX(-1);
}

.rtl .shopglut-product-tab.template1 .product-actions {
	left: 16px;
	right: auto;
	transform: translateX(-10px);
}

.rtl .shopglut-product-tab.template1 .product-card:hover .product-actions {
	transform: translateX(0);
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
		$table_name = $wpdb->prefix . 'shopglut_tab_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Using sprintf with esc_sql() for compatibility, direct query required for custom table operation
			$wpdb->prepare(sprintf("SELECT layout_settings, layout_template FROM `%s` WHERE id = %d", esc_sql($table_name)), $layout_id)
		);

		if (!$layout_data || empty($layout_data->layout_settings)) {
			return $this->getDefaultSettings();
		}

		$settings = maybe_unserialize($layout_data->layout_settings);

		if ($settings === false || !is_array($settings)) {
			return $this->getDefaultSettings();
		}

		// Try different possible settings structures
		$tab_settings = null;

		if (isset($settings['shopg_product_tab_settings_template1'])) {
			$tab_settings = $this->flattenSettings($settings['shopg_product_tab_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$tab_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['autoplay']) || isset($settings['tab_settings'])) {
			$tab_settings = $this->flattenSettings($settings);
		}

		if ($tab_settings) {
			return $tab_settings;
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
			'autoplay' => true,
			'autoplay_speed' => 3000,
			'show_dots' => true,
			'show_arrows' => true,
			'show_thumbnails' => true,
			'animation_speed' => 500,
			'products_to_show' => 8,
			'slides_per_view' => 4,
			'layout_id' => 0
		);
	}
}