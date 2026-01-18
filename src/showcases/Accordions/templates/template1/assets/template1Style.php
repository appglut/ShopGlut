<?php
namespace Shopglut\showcases\Accordions\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Style {

	public function dynamicCss($layout_id) {
		$settings = $this->getLayoutSettings($layout_id);

		$accordion_style = isset($settings['accordion_style']) ? sanitize_text_field($settings['accordion_style']) : 'default';
		$allow_multiple = isset($settings['allow_multiple']) ? (bool) $settings['allow_multiple'] : false;
		$animation_speed = isset($settings['animation_speed']) ? intval($settings['animation_speed']) : 300;

		$custom_css = "
/* ShopGlut Accordion Template1 - Product Information Accordion */
.shopglut-accordion-container.template1 {
	margin: 20px 0;
	background: #ffffff;
	border-radius: 12px;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
	overflow: hidden;
}

.shopglut-accordion-container.template1 .accordion-wrapper {
	width: 100%;
}

/* Accordion Items */
.shopglut-accordion-container.template1 .accordion-item {
	border-bottom: 1px solid #e9ecef;
	transition: all {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .accordion-item:last-child {
	border-bottom: none;
}

.shopglut-accordion-container.template1 .accordion-item.expanded {
	background: #f8f9fa;
}

/* Accordion Headers */
.shopglut-accordion-container.template1 .accordion-header {
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 18px 24px;
	background: none;
	border: none;
	color: #2c3e50;
	font-size: 16px;
	font-weight: 600;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
	cursor: pointer;
	transition: all {$animation_speed}ms ease;
	position: relative;
}

.shopglut-accordion-container.template1 .accordion-header:hover {
	background: #f8f9fa;
	color: #1a1a1a;
}

.shopglut-accordion-container.template1 .accordion-header:focus {
	outline: 2px solid #007cba;
	outline-offset: -2px;
}

/* Accordion Icons */
.shopglut-accordion-container.template1 .accordion-icon {
	display: flex;
	align-items: center;
	gap: 12px;
}

.shopglut-accordion-container.template1 .icon-wrapper {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	background: #f1f3f4;
	border-radius: 8px;
	color: #6c757d;
	transition: all {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .accordion-item.expanded .icon-wrapper {
	background: #007bff;
	color: #ffffff;
}

.shopglut-accordion-container.template1 .chevron {
	display: flex;
	align-items: center;
	justify-content: center;
	transition: transform {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .accordion-item.expanded .chevron {
	transform: rotate(180deg);
}

/* Accordion Titles */
.shopglut-accordion-container.template1 .accordion-title {
	flex: 1;
	text-align: left;
}

/* Accordion Content */
.shopglut-accordion-container.template1 .accordion-content {
	max-height: 0;
	overflow: hidden;
	transition: max-height {$animation_speed}ms ease-out, padding {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .accordion-item.expanded .accordion-content {
	max-height: 1000px; /* Arbitrary large value */
	transition: max-height {$animation_speed}ms ease-in, padding {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .content-inner {
	padding: 0 24px 20px 24px;
}

/* Content Specific Styles */
.shopglut-accordion-container.template1 .product-details-grid {
	display: grid;
	gap: 24px;
}

.shopglut-accordion-container.template1 .detail-category h4 {
	margin: 0 0 12px 0;
	color: #2c3e50;
	font-size: 14px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.shopglut-accordion-container.template1 .detail-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 8px 0;
	border-bottom: 1px solid #f1f3f4;
}

.shopglut-accordion-container.template1 .detail-item:last-child {
	border-bottom: none;
}

.shopglut-accordion-container.template1 .detail-item strong {
	color: #495057;
	font-weight: 600;
}

.shopglut-accordion-container.template1 .detail-item span {
	color: #6c757d;
}

.shopglut-accordion-container.template1 .detail-item .in-stock {
	color: #28a745;
	font-weight: 600;
}

.shopglut-accordion-container.template1 .detail-item .out-of-stock {
	color: #dc3545;
	font-weight: 600;
}

/* Features List */
.shopglut-accordion-container.template1 .features-list {
	display: grid;
	gap: 12px;
}

.shopglut-accordion-container.template1 .feature-check-item {
	display: flex;
	align-items: flex-start;
	gap: 12px;
}

.shopglut-accordion-container.template1 .check-icon {
	color: #28a745;
	font-weight: bold;
	font-size: 16px;
	margin-top: 2px;
}

/* Specifications Table */
.shopglut-accordion-container.template1 .specs-table {
	width: 100%;
	border-collapse: collapse;
	margin: 20px 0;
	background: #ffffff;
	border-radius: 8px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.shopglut-accordion-container.template1 .specs-table tr {
	border-bottom: 1px solid #f1f3f4;
}

.shopglut-accordion-container.template1 .specs-table tr:last-child {
	border-bottom: none;
}

.shopglut-accordion-container.template1 .specs-table td {
	padding: 12px 16px;
	vertical-align: middle;
}

.shopglut-accordion-container.template1 .specs-table td:first-child {
	background: #f8f9fa;
	font-weight: 600;
	color: #495057;
	width: 45%;
}

.shopglut-accordion-container.template1 .specs-table td:last-child {
	color: #6c757d;
}

/* Product Overview Grid */
.shopglut-accordion-container.template1 .overview-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 16px;
}

.shopglut-accordion-container.template1 .overview-item {
	display: flex;
	flex-direction: column;
	gap: 4px;
}

.shopglut-accordion-container.template1 .overview-item strong {
	color: #495057;
	font-weight: 600;
}

.shopglut-accordion-container.template1 .overview-item span {
	color: #6c757d;
}

/* Shipping Info */
.shopglut-accordion-container.template1 .shipping-info {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 24px;
}

.shopglut-accordion-container.template1 .shipping-section,
.shopglut-accordion-container.template1 .returns-section {
	padding: 16px;
	background: #f8f9fa;
	border-radius: 8px;
}

.shopglut-accordion-container.template1 .shipping-section h4,
.shopglut-accordion-container.template1 .returns-section h4 {
	margin: 0 0 12px 0;
	color: #2c3e50;
	font-size: 16px;
	font-weight: 600;
}

.shopglut-accordion-container.template1 .shipping-section p,
.shopglut-accordion-container.template1 .returns-section p {
	margin: 0 0 12px 0;
	color: #6c757d;
	line-height: 1.5;
}

.shopglut-accordion-container.template1 .shipping-section ul,
.shopglut-accordion-container.template1 .returns-section ul {
	margin: 0;
	padding: 0 0 0 20px;
}

.shopglut-accordion-container.template1 .shipping-section li,
.shopglut-accordion-container.template1 .returns-section li {
	margin: 6px 0;
	color: #495057;
	line-height: 1.5;
}

/* Reviews Styles */
.shopglut-accordion-container.template1 .reviews-wrapper {
	display: grid;
	gap: 24px;
}

.shopglut-accordion-container.template1 .reviews-summary {
	background: #f8f9fa;
	border-radius: 8px;
	padding: 20px;
	text-align: center;
}

.shopglut-accordion-container.template1 .rating-summary {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 8px;
}

.shopglut-accordion-container.template1 .average-rating {
	font-size: 32px;
	font-weight: 700;
	color: #2c3e50;
}

.shopglut-accordion-container.template1 .rating-stars {
	font-size: 18px;
	color: #ffc107;
	letter-spacing: 2px;
}

.shopglut-accordion-container.template1 .rating-count {
	color: #6c757d;
	font-size: 14px;
}

.shopglut-accordion-container.template1 .review-item {
	border-bottom: 1px solid #f1f3f4;
	padding-bottom: 16px;
}

.shopglut-accordion-container.template1 .review-item:last-child {
	border-bottom: none;
	padding-bottom: 0;
}

.shopglut-accordion-container.template1 .review-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 8px;
}

.shopglut-accordion-container.template1 .reviewer-name {
	font-weight: 600;
	color: #2c3e50;
}

.shopglut-accordion-container.template1 .review-rating {
	color: #ffc107;
	font-size: 14px;
}

.shopglut-accordion-container.template1 .review-date {
	color: #6c757d;
	font-size: 13px;
	margin-bottom: 8px;
}

.shopglut-accordion-container.template1 .review-content {
	color: #495057;
	line-height: 1.6;
	margin: 0;
}

/* Star Rating Styles */
.shopglut-accordion-container.template1 .star {
	font-size: 16px;
}

.shopglut-accordion-container.template1 .star.star-full {
	color: #ffc107;
}

.shopglut-accordion-container.template1 .star.star-half {
	color: #ffc107;
}

.shopglut-accordion-container.template1 .star.star-empty {
	color: #e0e0e0;
}

/* Demo Mode Styles */
.shopglut-accordion-demo-container {
	max-width: 800px;
	margin: 0 auto;
	padding: 20px;
	background: #f8f9fa;
	border-radius: 12px;
	border: 1px solid #e9ecef;
}

.shopglut-accordion-demo-container .demo-header {
	text-align: center;
	margin-bottom: 30px;
}

.shopglut-accordion-demo-container .demo-header h3 {
	font-size: 24px;
	font-weight: 600;
	color: #2c3e50;
	margin-bottom: 10px;
}

.shopglut-accordion-demo-container .demo-header p {
	font-size: 16px;
	color: #6c757d;
	line-height: 1.6;
}

.shopglut-accordion-container.template1.demo-mode {
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Accordion Style Variants */
.shopglut-accordion-container.template1 .accordion-wrapper.minimal .accordion-item {
	border-bottom: 2px solid #e9ecef;
}

.shopglut-accordion-container.template1 .accordion-wrapper.minimal .accordion-header {
	padding: 16px 20px;
	font-weight: 500;
}

.shopglut-accordion-container.template1 .accordion-wrapper.minimal .icon-wrapper {
	width: 32px;
	height: 32px;
}

.shopglut-accordion-container.template1 .accordion-wrapper.minimal .chevron {
	display: none;
}

.shopglut-accordion-container.template1 .accordion-wrapper.minimal .accordion-indicator {
	width: 24px;
	height: 4px;
	background: #e9ecef;
	border-radius: 2px;
	transition: all {$animation_speed}ms ease;
}

.shopglut-accordion-container.template1 .accordion-wrapper.minimal .accordion-item.expanded .accordion-indicator {
	background: #007bff;
	transform: scale(1.2);
}

/* Animations */
@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.shopglut-accordion-container.template1 .accordion-item.expanded .content-inner {
	animation: slideDown {$animation_speed}ms ease;
}

/* Responsive Design */
@media (max-width: 768px) {
	.shopglut-accordion-container.template1 .accordion-header {
		padding: 16px 20px;
		font-size: 15px;
	}

	.shopglut-accordion-container.template1 .content-inner {
		padding: 0 20px 16px 20px;
	}

	.shopglut-accordion-container.template1 .detail-item {
		flex-direction: column;
		align-items: flex-start;
		gap: 4px;
	}

	.shopglut-accordion-container.template1 .specs-table td {
		padding: 10px 12px;
		font-size: 14px;
	}

	.shopglut-accordion-container.template1 .specs-table td:first-child {
		width: auto;
	}

	.shopglut-accordion-container.template1 .overview-grid {
		grid-template-columns: 1fr;
	}

	.shopglut-accordion-container.template1 .shipping-info {
		grid-template-columns: 1fr;
		gap: 16px;
	}

	.shopglut-accordion-container.template1 .average-rating {
		font-size: 28px;
	}

	.shopglut-accordion-demo-container {
		padding: 15px;
		margin: 10px;
	}
}

@media (max-width: 480px) {
	.shopglut-accordion-container.template1 .accordion-header {
		padding: 14px 16px;
		font-size: 14px;
	}

	.shopglut-accordion-container.template1 .icon-wrapper {
		width: 30px;
		height: 30px;
	}

	.shopglut-accordion-container.template1 .content-inner {
		padding: 0 16px 14px 16px;
	}

	.shopglut-accordion-container.template1 .specs-table td {
		padding: 8px 10px;
		font-size: 13px;
	}

	.shopglut-accordion-container.template1 .shipping-section,
	.shopglut-accordion-container.template1 .returns-section {
		padding: 12px;
	}

	.shopglut-accordion-container.template1 .shipping-section h4,
	.shopglut-accordion-container.template1 .returns-section h4 {
		font-size: 15px;
	}

	.shopglut-accordion-demo-container .demo-header h3 {
		font-size: 20px;
	}

	.shopglut-accordion-demo-container .demo-header p {
		font-size: 14px;
	}
}

/* Loading States */
.shopglut-accordion-container.template1 .accordion-content.loading {
	background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
	background-size: 200% 100%;
	animation: loading 1.5s infinite;
}

@keyframes loading {
	0% { background-position: 200% 0; }
	100% { background-position: -200% 0; }
}

/* Accessibility */
.shopglut-accordion-container.template1 .accordion-header:focus-visible {
	outline: 2px solid #007cba;
	outline-offset: 2px;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
	.shopglut-accordion-container.template1 .accordion-item {
		border-bottom-color: #000000;
	}

	.shopglut-accordion-container.template1 .accordion-header {
		color: #000000;
	}

	.shopglut-accordion-container.template1 .accordion-header:hover {
		background: #ffffff;
		color: #000000;
	}

	.shopglut-accordion-container.template1 .accordion-item.expanded {
		background: #ffff00;
	}

	.shopglut-accordion-container.template1 .accordion-item.expanded .accordion-header {
		color: #000000;
	}

	.shopglut-accordion-container.template1 .icon-wrapper {
		background: #000000;
		color: #ffffff;
	}

	.shopglut-accordion-container.template1 .accordion-item.expanded .icon-wrapper {
		background: #0000ff;
	}
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
	.shopglut-accordion-container.template1 .accordion-item,
	.shopglut-accordion-container.template1 .accordion-header,
	.shopglut-accordion-container.template1 .accordion-content,
	.shopglut-accordion-container.template1 .chevron,
	.shopglut-accordion-container.template1 .icon-wrapper {
		transition: none;
	}

	.shopglut-accordion-container.template1 .accordion-item.expanded .content-inner {
		animation: none;
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
		$table_name = $wpdb->prefix . 'shopglut_accordion_layouts';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
		$layout_data = $wpdb->get_row(
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
		$accordion_settings = null;

		if (isset($settings['shopg_product_accordion_settings_template1'])) {
			$accordion_settings = $this->flattenSettings($settings['shopg_product_accordion_settings_template1']);
		}
		elseif (isset($settings['template1']) || isset($settings[$layout_data->layout_template])) {
			$template_key = isset($settings[$layout_data->layout_template]) ? $layout_data->layout_template : 'template1';
			$accordion_settings = $this->flattenSettings($settings[$template_key]);
		}
		elseif (isset($settings['accordion_style']) || isset($settings['accordion_settings'])) {
			$accordion_settings = $this->flattenSettings($settings);
		}

		if ($accordion_settings) {
			return $accordion_settings;
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
			'accordion_style' => 'default',
			'allow_multiple' => false,
			'show_product_info' => true,
			'show_description' => true,
			'show_specifications' => true,
			'show_reviews' => true,
			'show_shipping' => true,
			'animation_speed' => 300,
			'layout_id' => 0
		);
	}
}