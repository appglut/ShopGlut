<?php

namespace Shopglut\tools\WooThemes\themes\ModernStore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ModernStore {
	
	public function getThemeInfo() {
		return [
			'id' => 'theme1',
			'name' => esc_html__( 'Modern Store', 'shopglut' ),
			'description' => esc_html__( 'Modern and sleek design perfect for fashion and lifestyle stores.', 'shopglut' ),
			'screenshot' => plugin_dir_url( __FILE__ ) . '../../../../../assets/themes/theme1/screenshot.svg',
			'css_file' => 'theme1',
		];
	}
	
	public function getCustomizationOptions() {
		return [
			'header_bg_color' => '#667eea',
			'footer_bg_color' => '#2c3e50',
			'enable_custom_header' => true,
			'enable_custom_footer' => true,
			'header_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
			'footer_gradient' => 'linear-gradient(135deg, #2c3e50 0%, #34495e 100%)',
		];
	}
}