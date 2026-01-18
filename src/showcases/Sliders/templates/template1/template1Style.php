<?php
namespace Shopglut\showcases\Sliders\templates\template1;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* ===== RESET & BASE STYLES ===== */
        .shopglut-product-slider.template1 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .shopglut-product-slider.template1 {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* ===== MODAL OVERLAY ===== */
        .shopglut-product-slider.template1 .slider-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 999999;
        }

        .shopglut-product-slider.template1 .slider-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .shopglut-product-slider.template1 .slider-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: <?php echo esc_attr($settings['modal_overlay_color'] ?? 'rgba(0, 0, 0, 0.75)'); ?>;
            backdrop-filter: blur(<?php echo esc_attr($settings['modal_overlay_blur'] ?? 4); ?>px);
        }

        /* ===== MODAL CONTENT ===== */
        .shopglut-product-slider.template1 .slider-modal-content {
            position: relative;
            background: <?php echo esc_attr($settings['modal_background_color'] ?? '#ffffff'); ?>;
            border-radius: <?php echo esc_attr($settings['modal_border_radius'] ?? 12); ?>px;
            max-width: <?php echo esc_attr($settings['modal_max_width'] ?? 1100); ?>px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .shopglut-product-slider.template1 .slider-modal.active .slider-modal-content {
            transform: scale(1);
        }

        /* ===== CLOSE BUTTON ===== */
        .shopglut-product-slider.template1 .slider-close {
            position: absolute;
            top: 15px;
            right: 15px;
            width: <?php echo esc_attr($settings['close_button_size'] ?? 40); ?>px;
            height: <?php echo esc_attr($settings['close_button_size'] ?? 40); ?>px;
            background: <?php echo esc_attr($settings['close_button_bg_color'] ?? '#ffffff'); ?>;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
            color: <?php echo esc_attr($settings['close_button_color'] ?? '#374151'); ?>;
        }

        .shopglut-product-slider.template1 .slider-close:hover {
            background: <?php echo esc_attr($settings['close_button_hover_bg'] ?? '#f3f4f6'); ?>;
            border-color: #d1d5db;
            transform: rotate(90deg);
            color: <?php echo esc_attr($settings['close_button_hover_color'] ?? '#111827'); ?>;
        }

        .shopglut-product-slider.template1 .slider-close svg {
            width: <?php echo esc_attr(($settings['close_button_size'] ?? 40) / 2); ?>px;
            height: <?php echo esc_attr(($settings['close_button_size'] ?? 40) / 2); ?>px;
        }

        /* ===== SLIDER INNER LAYOUT ===== */
        .shopglut-product-slider.template1 .slider-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: <?php echo esc_attr($settings['modal_padding'] ?? 40); ?>px;
        }

        /* ===== PRODUCT GALLERY ===== */
        .shopglut-product-slider.template1 .slider-gallery {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-product-slider.template1 .main-image-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            background: <?php echo esc_attr($settings['main_image_bg_color'] ?? '#f9fafb'); ?>;
            border-radius: <?php echo esc_attr($settings['main_image_border_radius'] ?? 12); ?>px;
            overflow: hidden;
            border: <?php echo esc_attr($settings['main_image_border_width'] ?? 1); ?>px solid <?php echo esc_attr($settings['main_image_border_color'] ?? '#e5e7eb'); ?>;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shopglut-product-slider.template1 .slider-main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .shopglut-product-slider.template1 .main-image-wrapper:hover .slider-main-image {
            transform: scale(1.05);
        }

        .shopglut-product-slider.template1 .sale-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: <?php echo esc_attr($settings['sale_badge_bg_color'] ?? '#ef4444'); ?>;
            color: <?php echo esc_attr($settings['sale_badge_text_color'] ?? '#ffffff'); ?>;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: <?php echo esc_attr($settings['sale_badge_font_size'] ?? 14); ?>px;
            font-weight: 600;
            z-index: 2;
            <?php if (isset($settings['show_sale_badge']) && !$settings['show_sale_badge']): ?>
            display: none;
            <?php endif; ?>
        }

        /* ===== GALLERY THUMBNAILS ===== */
        .shopglut-product-slider.template1 .gallery-thumbnails {
            <?php if (isset($settings['show_thumbnails']) && !$settings['show_thumbnails']): ?>
            display: none;
            <?php else: ?>
            display: flex;
            gap: <?php echo esc_attr($settings['thumbnail_gap'] ?? 10); ?>px;
            overflow-x: auto;
            padding: 5px 0;
            <?php endif; ?>
        }

        .shopglut-product-slider.template1 .gallery-thumbnails::-webkit-scrollbar {
            height: 6px;
        }

        .shopglut-product-slider.template1 .gallery-thumbnails::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .shopglut-product-slider.template1 .gallery-thumbnails::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .shopglut-product-slider.template1 .thumbnail-item {
            flex-shrink: 0;
            width: <?php echo esc_attr($settings['thumbnail_size'] ?? 80); ?>px;
            height: <?php echo esc_attr($settings['thumbnail_size'] ?? 80); ?>px;
            border-radius: <?php echo esc_attr($settings['thumbnail_border_radius'] ?? 8); ?>px;
            overflow: hidden;
            border: 2px solid <?php echo esc_attr($settings['thumbnail_border_color'] ?? '#e5e7eb'); ?>;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .shopglut-product-slider.template1 .thumbnail-item:hover {
            border-color: #9ca3af;
        }

        .shopglut-product-slider.template1 .thumbnail-item.active {
            border-color: <?php echo esc_attr($settings['thumbnail_active_border_color'] ?? '#667eea'); ?>;
            box-shadow: 0 0 0 2px <?php echo esc_attr($settings['thumbnail_active_border_color'] ?? '#667eea'); ?>33;
        }

        .shopglut-product-slider.template1 .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== PRODUCT INFO ===== */
        .shopglut-product-slider.template1 .slider-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ===== PRODUCT TITLE ===== */
        .shopglut-product-slider.template1 .product-title {
            font-size: <?php echo esc_attr($settings['title_font_size'] ?? 28); ?>px;
            font-weight: <?php echo esc_attr($settings['title_font_weight'] ?? 700); ?>;
            color: <?php echo esc_attr($settings['title_color'] ?? '#111827'); ?>;
            line-height: 1.3;
            margin: 0;
        }

        /* ===== PRODUCT RATING ===== */
        .shopglut-product-slider.template1 .product-rating {
            <?php if (isset($settings['show_rating']) && !$settings['show_rating']): ?>
            display: none;
            <?php else: ?>
            display: flex;
            align-items: center;
            gap: 10px;
            <?php endif; ?>
        }

        .shopglut-product-slider.template1 .stars {
            display: flex;
            gap: 2px;
        }

        .shopglut-product-slider.template1 .star {
            font-size: <?php echo esc_attr($settings['star_size'] ?? 18); ?>px;
            color: #d1d5db;
        }

        .shopglut-product-slider.template1 .star-full {
            color: <?php echo esc_attr($settings['star_color'] ?? '#fbbf24'); ?>;
        }

        .shopglut-product-slider.template1 .star-half {
            color: <?php echo esc_attr($settings['star_color'] ?? '#fbbf24'); ?>;
            opacity: 0.5;
        }

        .shopglut-product-slider.template1 .rating-text {
            font-size: 14px;
            color: <?php echo esc_attr($settings['rating_text_color'] ?? '#6b7280'); ?>;
        }

        /* ===== PRODUCT PRICE ===== */
        .shopglut-product-slider.template1 .product-price {
            display: flex;
            align-items: baseline;
            gap: 12px;
        }

        .shopglut-product-slider.template1 .sale-price,
        .shopglut-product-slider.template1 .current-price {
            font-size: <?php echo esc_attr($settings['price_font_size'] ?? 32); ?>px;
            font-weight: 700;
            color: <?php echo esc_attr($settings['price_color'] ?? '#111827'); ?>;
        }

        .shopglut-product-slider.template1 .regular-price {
            font-size: 20px;
            color: <?php echo esc_attr($settings['regular_price_color'] ?? '#9ca3af'); ?>;
        }

        .shopglut-product-slider.template1 .regular-price del {
            text-decoration: line-through;
        }

        /* ===== PRODUCT DESCRIPTION ===== */
        .shopglut-product-slider.template1 .product-description {
            <?php if (isset($settings['show_description']) && !$settings['show_description']): ?>
            display: none;
            <?php else: ?>
            font-size: <?php echo esc_attr($settings['description_font_size'] ?? 15); ?>px;
            color: <?php echo esc_attr($settings['description_color'] ?? '#6b7280'); ?>;
            line-height: 1.7;
            <?php endif; ?>
        }

        .shopglut-product-slider.template1 .product-description p {
            margin: 0 0 10px 0;
        }

        .shopglut-product-slider.template1 .product-description p:last-child {
            margin-bottom: 0;
        }

        /* ===== PRODUCT META ===== */
        .shopglut-product-slider.template1 .product-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 15px;
            background: <?php echo esc_attr($settings['meta_bg_color'] ?? '#f9fafb'); ?>;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .shopglut-product-slider.template1 .meta-item {
            display: flex;
            gap: 8px;
            font-size: 14px;
        }

        .shopglut-product-slider.template1 .meta-label {
            font-weight: 600;
            color: <?php echo esc_attr($settings['meta_label_color'] ?? '#374151'); ?>;
            min-width: 100px;
        }

        .shopglut-product-slider.template1 .meta-value {
            color: <?php echo esc_attr($settings['meta_value_color'] ?? '#6b7280'); ?>;
        }

        .shopglut-product-slider.template1 .meta-value a {
            color: #667eea;
            text-decoration: none;
        }

        .shopglut-product-slider.template1 .meta-value a:hover {
            text-decoration: underline;
        }

        .shopglut-product-slider.template1 .stock-status {
            font-weight: 600;
        }

        .shopglut-product-slider.template1 .stock-status.in-stock {
            color: <?php echo esc_attr($settings['in_stock_color'] ?? '#10b981'); ?>;
        }

        .shopglut-product-slider.template1 .stock-status.out-of-stock {
            color: <?php echo esc_attr($settings['out_of_stock_color'] ?? '#ef4444'); ?>;
        }

        /* ===== PRODUCT VARIATIONS ===== */
        .shopglut-product-slider.template1 .product-variations {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 15px 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 5px;
        }

        .shopglut-product-slider.template1 .variation-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .shopglut-product-slider.template1 .variation-label {
            font-size: <?php echo esc_attr($settings['variation_label_font_size'] ?? 14); ?>px;
            font-weight: 600;
            color: <?php echo esc_attr($settings['variation_label_color'] ?? '#374151'); ?>;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            display: block;
            margin-bottom: 4px;
        }

        .shopglut-product-slider.template1 .variation-select {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: <?php echo esc_attr($settings['variation_select_border_width'] ?? 2); ?>px solid <?php echo esc_attr($settings['variation_select_border'] ?? '#d1d5db'); ?>;
            border-radius: <?php echo esc_attr($settings['variation_select_border_radius'] ?? 8); ?>px;
            font-size: 15px;
            font-weight: 500;
            color: <?php echo esc_attr($settings['variation_select_text_color'] ?? '#374151'); ?>;
            background-color: <?php echo esc_attr($settings['variation_select_bg'] ?? '#ffffff'); ?>;
            cursor: pointer;
            transition: all 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            <?php
            // Create dropdown arrow with dynamic color based on text color
            $text_color = $settings['variation_select_text_color'] ?? '#374151';
            $encoded_color = str_replace('#', '%23', $text_color);
            ?>
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='<?php echo esc_attr($encoded_color); ?>' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .shopglut-product-slider.template1 .variation-select:hover {
            border-color: <?php
                $border_color = $settings['variation_select_border'] ?? '#d1d5db';
                // Keep the same color on hover but add shadow
                echo esc_attr($border_color);
            ?>;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        .shopglut-product-slider.template1 .variation-select:focus {
            outline: none;
            border-color: <?php echo esc_attr($settings['variation_select_focus_border'] ?? '#667eea'); ?>;
            <?php
            // Generate dynamic box-shadow based on focus border color
            $focus_color = $settings['variation_select_focus_border'] ?? '#667eea';
            // Convert hex to rgb for rgba usage
            if (strpos($focus_color, '#') === 0) {
                $hex = ltrim($focus_color, '#');
                if (strlen($hex) === 6) {
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                    echo 'box-shadow: 0 0 0 3px rgba(' . esc_attr($r) . ', ' . esc_attr($g) . ', ' . esc_attr($b) . ', 0.15);';
                } else {
                    echo 'box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);';
                }
            } else {
                echo 'box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);';
            }
            ?>
            transform: translateY(0);
        }

        .shopglut-product-slider.template1 .variation-select:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #f3f4f6;
        }

        /* Variation Select Options */
        .shopglut-product-slider.template1 .variation-select option {
            padding: 10px;
            color: <?php echo esc_attr($settings['variation_select_text_color'] ?? '#374151'); ?>;
            background-color: <?php echo esc_attr($settings['variation_select_bg'] ?? '#ffffff'); ?>;
        }

        .shopglut-product-slider.template1 .variation-select option:disabled {
            color: #9ca3af;
        }

        .shopglut-product-slider.template1 .variation-select option:checked {
            background-color: <?php echo esc_attr($settings['variation_select_focus_border'] ?? '#667eea'); ?>;
            color: #ffffff;
        }

        /* ===== PRODUCT ACTIONS ===== */
        .shopglut-product-slider.template1 .product-actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .shopglut-product-slider.template1 .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid <?php echo esc_attr($settings['qty_border_color'] ?? '#d1d5db'); ?>;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .shopglut-product-slider.template1 .qty-btn {
            width: 44px;
            height: 50px;
            background: <?php echo esc_attr($settings['qty_button_bg_color'] ?? '#f9fafb'); ?>;
            border: none;
            font-size: 18px;
            font-weight: 600;
            color: <?php echo esc_attr($settings['qty_button_color'] ?? '#374151'); ?>;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .shopglut-product-slider.template1 .qty-btn:hover {
            background: <?php echo esc_attr($settings['qty_button_hover_bg'] ?? '#e5e7eb'); ?>;
        }

        .shopglut-product-slider.template1 .qty-input {
            width: 60px;
            height: 50px;
            border: none;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            -moz-appearance: textfield;
        }

        .shopglut-product-slider.template1 .qty-input::-webkit-outer-spin-button,
        .shopglut-product-slider.template1 .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .shopglut-product-slider.template1 .qty-input:focus {
            outline: none;
        }

        .shopglut-product-slider.template1 .add-to-cart-btn {
            flex: 1;
            height: 50px;
            background: <?php echo esc_attr($settings['cart_button_bg_color'] ?? '#667eea'); ?>;
            color: <?php echo esc_attr($settings['cart_button_text_color'] ?? '#ffffff'); ?>;
            border: none;
            border-radius: <?php echo esc_attr($settings['cart_button_border_radius'] ?? 8); ?>px;
            font-size: <?php echo esc_attr($settings['cart_button_font_size'] ?? 16); ?>px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shopglut-product-slider.template1 .add-to-cart-btn:hover {
            background: <?php echo esc_attr($settings['cart_button_hover_bg'] ?? '#5a67d8'); ?>;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .shopglut-product-slider.template1 .add-to-cart-btn:active {
            transform: translateY(0);
        }

        /* ===== ADDITIONAL ACTIONS ===== */
        .shopglut-product-slider.template1 .additional-actions {
            display: flex;
            gap: 10px;
        }

        .shopglut-product-slider.template1 .view-details-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: <?php echo esc_attr($settings['details_button_bg_color'] ?? '#f9fafb'); ?>;
            color: <?php echo esc_attr($settings['details_button_text_color'] ?? '#374151'); ?>;
            border: 1px solid <?php echo esc_attr($settings['details_button_border_color'] ?? '#d1d5db'); ?>;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .shopglut-product-slider.template1 .view-details-btn:hover {
            background: <?php echo esc_attr($settings['details_button_hover_bg'] ?? '#e5e7eb'); ?>;
            border-color: #9ca3af;
        }

        /* ===== ERROR STATE ===== */
        .shopglut-slider-error {
            padding: 40px;
            text-align: center;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #991b1b;
        }

        /* ===== RESPONSIVE STYLES ===== */
        @media (max-width: 1024px) {
            .shopglut-product-slider.template1 .slider-inner {
                gap: 30px;
                padding: 30px;
            }

            .shopglut-product-slider.template1 .product-title {
                font-size: 24px;
            }

            .shopglut-product-slider.template1 .sale-price,
            .shopglut-product-slider.template1 .current-price {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .shopglut-product-slider.template1 .slider-modal {
                padding: 10px;
            }

            .shopglut-product-slider.template1 .slider-modal-content {
                border-radius: 8px;
                max-height: 95vh;
            }

            .shopglut-product-slider.template1 .slider-inner {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 20px;
            }

            .shopglut-product-slider.template1 .slider-close {
                width: 36px;
                height: 36px;
                top: 10px;
                right: 10px;
            }

            .shopglut-product-slider.template1 .product-title {
                font-size: 22px;
            }

            .shopglut-product-slider.template1 .sale-price,
            .shopglut-product-slider.template1 .current-price {
                font-size: 24px;
            }

            .shopglut-product-slider.template1 .regular-price {
                font-size: 18px;
            }

            .shopglut-product-slider.template1 .product-actions {
                flex-direction: column;
            }

            .shopglut-product-slider.template1 .quantity-selector {
                width: 100%;
            }

            .shopglut-product-slider.template1 .qty-input {
                flex: 1;
            }

            .shopglut-product-slider.template1 .thumbnail-item {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 480px) {
            .shopglut-product-slider.template1 .slider-inner {
                padding: 15px;
                gap: 20px;
            }

            .shopglut-product-slider.template1 .product-title {
                font-size: 20px;
            }

            .shopglut-product-slider.template1 .sale-price,
            .shopglut-product-slider.template1 .current-price {
                font-size: 22px;
            }

            .shopglut-product-slider.template1 .gallery-thumbnails {
                gap: 8px;
            }

            .shopglut-product-slider.template1 .thumbnail-item {
                width: 60px;
                height: 60px;
            }

            .shopglut-product-slider.template1 .meta-label {
                min-width: 80px;
                font-size: 13px;
            }

            .shopglut-product-slider.template1 .meta-value {
                font-size: 13px;
            }
        }

        /* ===== UTILITY CLASSES ===== */
        .shopglut-product-slider.template1 .hidden {
            display: none !important;
        }

        /* ===== LOADING STATE ===== */
        .shopglut-product-slider.template1 .loading {
            pointer-events: none;
            opacity: 0.6;
        }

        .shopglut-product-slider.template1 .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            margin: -15px 0 0 -15px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: shopglut-spin 1s linear infinite;
        }

        @keyframes shopglut-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== ACCESSIBILITY ===== */
        .shopglut-product-slider.template1 *:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .shopglut-product-slider.template1 .slider-modal-overlay,
            .shopglut-product-slider.template1 .slider-close,
            .shopglut-product-slider.template1 .product-actions,
            .shopglut-product-slider.template1 .additional-actions {
                display: none !important;
            }

            .shopglut-product-slider.template1 .slider-modal {
                position: static;
            }

            .shopglut-product-slider.template1 .slider-modal-content {
                box-shadow: none;
                max-height: none;
            }
        }
    </style>
        <?php
    }

    /**
     * Get layout settings from database
     */
    private function getLayoutSettings($layout_id) {
        if (!$layout_id) {
            return $this->getDefaultSettings();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'shopglut_slider_layouts';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with prepare
        $layout_data = $wpdb->get_row(
            $wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_slider_layouts` WHERE id = %d", $layout_id)
        );

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);
            if (isset($settings['shopg_product_slider_settings_template1']['product_slider-page-settings'])) {
                return $this->flattenSettings($settings['shopg_product_slider_settings_template1']['product_slider-page-settings']);
            }
        }

        return $this->getDefaultSettings();
    }

    /**
     * Flatten nested settings structure to simple key-value pairs
     */
    private function flattenSettings($nested_settings) {
        $flat_settings = array();

        foreach ($nested_settings as $group_key => $group_values) {
            if (is_array($group_values)) {
                foreach ($group_values as $setting_key => $setting_value) {
                    if (is_array($setting_value) && isset($setting_value[$setting_key])) {
                        $flat_settings[$setting_key] = $setting_value[$setting_key];
                    } else {
                        $flat_settings[$setting_key] = $setting_value;
                    }
                }
            }
        }

        return array_merge($this->getDefaultSettings(), $flat_settings);
    }

    /**
     * Get default settings values
     */
    private function getDefaultSettings() {
        return array(
            'modal_width' => '1100px',
            'modal_border_radius' => '12px',
            'primary_color' => '#667eea',
            'primary_hover_color' => '#5a67d8',
            'sale_badge_color' => '#ef4444',
            'rating_color' => '#fbbf24',
        );
    }
}
