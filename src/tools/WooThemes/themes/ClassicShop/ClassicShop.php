<?php

namespace Shopglut\tools\WooThemes\themes\ClassicShop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ClassicShop {
	
	public function getThemeInfo() {
		return [
			'id' => 'theme2',
			'name' => esc_html__( 'Classic Shop', 'shopglut' ),
			'description' => esc_html__( 'Classic and elegant design suitable for any type of store.', 'shopglut' ),
			'screenshot' => plugin_dir_url( __FILE__ ) . '../../../../../assets/themes/theme2/screenshot.svg',
			'css_file' => 'theme2',
		];
	}
	
	public function getCustomizationOptions() {
		return [
			'header_bg_color' => '#ffffff',
			'footer_bg_color' => '#2c3e50',
			'enable_custom_header' => true,
			'enable_custom_footer' => true,
			'header_border_color' => '#d4a574',
			'footer_accent_color' => '#d4a574',
		];
	}
}