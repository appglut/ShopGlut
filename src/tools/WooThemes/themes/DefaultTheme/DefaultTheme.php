<?php

namespace Shopglut\tools\WooThemes\themes\DefaultTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DefaultTheme {
	
	public function getThemeInfo() {
		return [
			'id' => 'default',
			'name' => esc_html__( 'Default Theme', 'shopglut' ),
			'description' => esc_html__( 'Clean and simple default theme for WooCommerce stores.', 'shopglut' ),
			'screenshot' => plugin_dir_url( __FILE__ ) . '../../../../../assets/themes/default/screenshot.svg',
			'css_file' => 'default',
		];
	}
	
	public function getCustomizationOptions() {
		return [
			'header_bg_color' => '#ffffff',
			'footer_bg_color' => '#6c757d',
			'enable_custom_header' => false,
			'enable_custom_footer' => false,
		];
	}
}