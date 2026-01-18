<?php
namespace Shopglut\layouts\singleProduct\templates\template3;

class template3Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* Settings-based Dynamic CSS */
        <?php echo wp_kses($this->generateSettingsBasedCSS($settings), array()); ?>

        .single-product-template3 {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .single-product-template3 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Product Page Layout */
        .single-product-template3 .product-page {
            display: grid;
            grid-template-columns: 1.2fr 1.5fr;
            gap: 60px;
            margin: 40px 0;
        }

        /* Left Side - Product Image */
        .single-product-template3 .product-image {
            /* Removed sticky positioning */
        }

        .single-product-template3 .main-product-image {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .single-product-template3 .main-product-image:hover {
            transform: scale(1.02);
        }

        .single-product-template3 .main-product-image img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* Thumbnail Gallery */
        .single-product-template3 .thumbnail-gallery {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .single-product-template3 .thumbnail-item {
            flex: 0 0 auto;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .single-product-template3 .thumbnail-item:hover {
            border-color: #0073aa;
        }

        .single-product-template3 .thumbnail-item:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            z-index: 1;
            pointer-events: none;
        }

        .single-product-template3 .thumbnail-item .thumbnail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .single-product-template3 .thumbnail-item.active {
            border-color: #0073aa;
        }

        .single-product-template3 .thumbnail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side - Product Info */
        .single-product-template3 .product-info {
            padding: 5px 0;
            display:block;
        }

        /* Product Badges Container */
        .single-product-template3 .product-badges-container {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .single-product-template3 .product-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .single-product-template3 .badge-new {
            background: #28a745;
            color: white;
        }

        .single-product-template3 .badge-sale {
            background: #dc3545;
            color: white;
        }

        /* Reviews and Rating Section */
        .single-product-template3 .reviews-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding: 5px 0;
        }

        .single-product-template3 .rating-stars {
            display: flex;
            gap: 2px;
            font-size: 18px;
            color: #ffc107;
        }

        .single-product-template3 .rating-stars .far {
            color: #ddd;
        }

        .single-product-template3 .reviews-count {
            font-size: 16px;
            color: #666;
            cursor: pointer;
            transition: color 0.3s;
        }

        .single-product-template3 .reviews-count:hover {
            color: #0073aa;
            text-decoration: underline;
        }

        /* Price Section */
        .single-product-template3 .price-section {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .single-product-template3 .current-price {
            font-size: 36px;
            font-weight: 700;
            color: #28a745;
        }

        .single-product-template3 .original-price {
            font-size: 22px;
            color: #999;
            text-decoration: line-through;
        }

        .single-product-template3 .discount-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dc3545;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Product Title */
        .single-product-template3 .product-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
            color: #1a1a1a;
            text-align:left;
        }

        /* Short Description */
        .single-product-template3 .short-description {
            font-size: 16px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #0073aa;
        }

        /* Product Variations */
        .single-product-template3 .variations-container {
            margin-bottom: 30px;
            padding: 25px;
            border-top: 2px solid #e9ecef;
            border-bottom: 2px solid #e9ecef;
        }

        .single-product-template3 .variation-group {
            margin-bottom: 20px;
        }

        .single-product-template3 .variation-group:last-child {
            margin-bottom: 0;
        }

        .single-product-template3 .variation-label {
            display: block;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .single-product-template3 .color-options {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .single-product-template3 .color-swatch {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .single-product-template3 .color-swatch:hover,
        .single-product-template3 .color-swatch.selected {
            border-color: #0073aa;
            transform: scale(1.1);
        }

        .single-product-template3 .color-swatch.selected::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-shadow: 0 0 3px rgba(0,0,0,0.5);
        }

        .single-product-template3 .size-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .single-product-template3 .size-option {
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .single-product-template3 .size-option:hover {
            border-color: #0073aa;
            background: #f8f9ff;
        }

        .single-product-template3 .size-option.selected {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }

        /* Quantity and Add to Cart */
        .single-product-template3 .cart-section {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .single-product-template3 .quantity-selector {
            display: flex;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .single-product-template3 .quantity-btn {
            background: #f8f9fa;
            border: none;
            padding: 12px 16px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single-product-template3 .quantity-btn:hover {
            background: #0073aa;
            color: white;
        }

        .single-product-template3 .quantity-input {
            border: none;
            text-align: center;
            width: 50px;
            font-size: 16px;
            font-weight: 600;
            background: white;
            outline: none;
            padding: 12px 0;
            -moz-appearance: textfield;
        }

        .single-product-template3 .add-to-cart-btn {
            background: linear-gradient(135deg, #0073aa, #005a87);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .single-product-template3 .add-to-cart-btn:hover {
            transform: translateY(-2px);
        }

        /* Action Buttons */
        .single-product-template3 .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .single-product-template3 .action-btn {
            flex: 1;
            min-width: 140px;
            padding: 12px 16px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            height: 48px;
        }

        .single-product-template3 .action-btn:hover {
            border-color: #0073aa;
            color: #0073aa;
            background: #f8f9ff;
        }

        .single-product-template3 .social-share {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            justify-content: center;
        }

        .single-product-template3 .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .single-product-template3 .social-icon:hover {
            background: #0073aa;
            color: white;
        }

        /* Full Width Description Section */
        .single-product-template3 .product-description {
            grid-column: 1 / -1;
            margin: 5px 0;
            padding: 10px;
            background: white;
            border-radius: 12px;
        }

        .single-product-template3 .description-title {
            font-size: 28px !important;
            font-weight: 700 !important;
            margin-bottom: 25px !important;
            margin-top:0px !important;
            color: #1a1a1a !important;
            position: relative !important;
            padding-bottom: 18px !important;
            text-align: center !important;
        }

        .single-product-template3 .description-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #0073aa;
        }

        .single-product-template3 .description-content {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        .single-product-template3 .description-content h3 {
            margin: 30px 0 15px 0;
            color: #333;
        }

        .single-product-template3 .description-content ul {
            list-style: none;
            padding: 0;
        }

        .single-product-template3 .description-content li {
            padding: 8px 0;
            padding-left: 30px;
            position: relative;
        }

        .single-product-template3 .description-content li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }

        /* Reviews Section */
        .single-product-template3 .reviews-main-section {
            grid-column: 1 / -1;
            margin: 7px 0;
            padding: 12px;
            background: white;
            border-radius: 12px;
        }

        .single-product-template3 .reviews-header h2 {
            font-size: 28px !important;
            font-weight: 700 !important;
            margin-top: 0px !important;
            margin-bottom: 25px !important;
            color: #1a1a1a !important;
            position: relative !important;
            padding-bottom: 18px !important;
            text-align: center !important;
        }

        .single-product-template3 .reviews-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #0073aa;
        }

        
        .single-product-template3 .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .single-product-template3 .review-card {
            padding: 25px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            background: #fafbfc;
            transition: all 0.3s ease;
        }

        .single-product-template3 .review-card:hover {
            transform: translateY(-2px);
        }

        .single-product-template3 .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .single-product-template3 .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .single-product-template3 .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #0073aa;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        .single-product-template3 .reviewer-name {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .single-product-template3 .review-date {
            color: #999;
            font-size: 14px;
        }

        .single-product-template3 .review-rating {
            color: #ffc107;
            font-size: 16px;
        }

        .single-product-template3 .review-text {
            color: #555;
            line-height: 1.6;
            margin-top: 15px;
        }

  /* Review Form */
        .single-product-template3 .review-form-container {
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            margin-top: 25px;
        }

        .single-product-template3 .review-form-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            margin-top:10px;
            color: #333;
        }

        .single-product-template3 .form-group {
            margin-bottom: 20px;
        }

        .single-product-template3 .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .single-product-template3 .form-input,
        .single-product-template3 .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .single-product-template3 .form-input:focus,
        .single-product-template3 .form-textarea:focus {
            outline: none;
            border-color: #0073aa;
        }

        .single-product-template3 .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .single-product-template3 .star-rating-input {
            display: flex;
            gap: 5px;
            font-size: 24px;
        }

        .single-product-template3 .star-rating-input i {
            color: #ddd;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .single-product-template3 .star-rating-input i:hover,
        .single-product-template3 .star-rating-input i.active {
            color: #ffc107;
        }

        .single-product-template3 .submit-review-btn {
            background: #0073aa;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .single-product-template3 .submit-review-btn:hover {
            background: #005a87;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .single-product-template3 .product-page {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .single-product-template3 .product-page {
                grid-template-columns: 1fr;
                gap: 30px;
            }

  
            .single-product-template3 .product-title {
                font-size: 24px;
            }

            .single-product-template3 .current-price {
                font-size: 28px;
            }

            .single-product-template3 .cart-section {
                grid-template-columns: 1fr;
            }

            .single-product-template3 .action-buttons {
                flex-direction: column;
            }

            .single-product-template3 .reviews-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .single-product-template3 .container {
                padding: 0 15px;
            }

            .single-product-template3 .product-description,
            .single-product-template3 .reviews-main-section {
                padding: 25px 20px;
            }

            .single-product-template3 .variations-container {
                padding: 20px 15px;
            }

            .single-product-template3 .short-description {
                padding: 15px;
            }
        }
        </style>
        <?php
    }

    /**
     * Generate CSS based on settings
     */
    private function generateSettingsBasedCSS($settings) {
        $css = '';

        // Product Gallery Settings
        $css .= '.shopglut-single-product-container .product-gallery-section {';
        $css .= 'margin-bottom: ' . $this->getSetting($settings, 'gallery_section_margin', 40) . 'px;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .main-image-container {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'main_image_background', '#f9fafb') . ' !important;';
        $css .= 'background-image: none !important;';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px !important;';
        $css .= 'border: ' . $this->getSetting($settings, 'main_image_border_width', 1) . 'px solid ' . $this->getSetting($settings, 'main_image_border_color', '#e5e7eb') . ' !important;';
        $css .= 'padding: ' . $this->getSetting($settings, 'main_image_padding', 8) . 'px !important;';
        $css .= 'margin-bottom: ' . $this->getSetting($settings, 'main_image_margin_bottom', 20) . 'px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .main-product-image {';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px;';
        $css .= 'width: 100%;';
        $css .= 'height: auto;';
        $css .= 'object-fit: ' . $this->getSetting($settings, 'main_image_object_fit', 'cover') . ';';
        $css .= '}';

        // Thumbnail Gallery
        if ($this->getSetting($settings, 'show_thumbnails', true)) {
            $css .= '.shopglut-single-product-container .thumbnail-gallery {';
            $css .= 'gap: ' . $this->getSetting($settings, 'thumbnail_spacing', 8) . 'px;';
            $css .= 'margin-top: ' . $this->getSetting($settings, 'thumbnail_gallery_margin_top', 16) . 'px;';
            $css .= 'justify-content: ' . $this->getSetting($settings, 'thumbnail_alignment', 'flex-start') . ';';
            $css .= '}';
            $css .= '.shopglut-single-product-container .thumbnail-item {';
            $css .= 'width: ' . $this->getSetting($settings, 'thumbnail_size', 140) . 'px;';
            $css .= 'height: ' . $this->getSetting($settings, 'thumbnail_size', 120) . 'px;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'thumbnail_border_radius', 6) . 'px;';
            $css .= 'border: ' . $this->getSetting($settings, 'thumbnail_border_width', 2) . 'px solid ' . $this->getSetting($settings, 'thumbnail_border_color', 'transparent') . ';';
            $css .= 'overflow: hidden;';
            $css .= 'cursor: pointer;';
            $css .= 'transition: all 0.3s ease;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .thumbnail-item:hover {';
            $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_hover_border', '#2563eb') . ';';
            $css .= 'transform: scale(' . $this->getSetting($settings, 'thumbnail_hover_scale', 1.05) . ');';
            $css .= '}';

            $css .= '.shopglut-single-product-container .thumbnail-item.active {';
            $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_active_border', '#667eea') . ';';
            $css .= '}';

            $css .= '.shopglut-single-product-container .thumbnail-image {';
            $css .= 'width: 100%;';
            $css .= 'height: 100%;';
            $css .= 'object-fit: ' . $this->getSetting($settings, 'thumbnail_object_fit', 'cover') . ';';
            $css .= '}';
        }

        // Product Badges
        if ($this->getSetting($settings, 'show_product_badges', true)) {
            $css .= '.shopglut-single-product-container .product-badges-container {';
            $css .= 'display: flex !important;';
            $css .= 'gap: ' . $this->getSetting($settings, 'badge_spacing', 8) . 'px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= 'flex-wrap: wrap !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .product-badge {';
            $css .= 'padding: 6px 12px !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'badge_border_radius', 20) . 'px !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'badge_font_size', 12) . 'px !important;';
            $css .= 'font-weight: ' . $this->getSetting($settings, 'badge_font_weight', '600') . ' !important;';
            $css .= 'color: white !important;';
            $css .= '}';

            // Individual badge types with their default colors
            $badge_defaults = array(
                'new' => '#10b981',
                'trending' => '#f59e0b',
                'bestseller' => '#ef4444',
                'hot' => '#dc2626',
                'sale' => '#8b5cf6',
                'limited' => '#6b7280'
            );

            foreach ($badge_defaults as $type => $default_color) {
                if ($this->getSetting($settings, 'show_' . $type . '_badge', true)) {
                    $css .= '.shopglut-single-product-container .badge-' . $type . ' {';
                    $css .= 'background-color: ' . $this->getSetting($settings, $type . '_badge_background_color', $default_color) . ' !important;';
                    $css .= 'color: ' . $this->getSetting($settings, $type . '_badge_text_color', '#ffffff') . ' !important;';
                    $css .= '}';
                }
            }
        }

        // Product Title
        $css .= '.shopglut-single-product-container .product-title {';
        $css .= 'color: ' . $this->getSetting($settings, 'product_title_color', '#111827') . ' !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'product_title_font_size', 32) . 'px !important;';
        $css .= 'font-weight: ' . $this->getSetting($settings, 'product_title_font_weight', '700') . ' !important;';
        $css .= 'margin-bottom: 16px !important;';
        $css .= 'line-height: 1.2 !important;';
        $css .= '}';

        // Rating Section
        if ($this->getSetting($settings, 'show_rating', true)) {
            $css .= '.shopglut-single-product-container .stars-container .star.filled {';
            $css .= 'color: ' . $this->getSetting($settings, 'star_color', '#fbbf24') . ';';
            $css .= '}';
            $css .= '.shopglut-single-product-container .rating-text {';
            $css .= 'color: ' . $this->getSetting($settings, 'rating_text_color', '#6b7280') . ';';
            $css .= 'font-size: ' . $this->getSetting($settings, 'rating_font_size', 14) . 'px;';
            $css .= '}';
        }

        // Price Section
        $css .= '.shopglut-single-product-container .price-section {';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'gap: 12px !important;';
        $css .= 'margin-bottom: 24px !important;';
        $css .= 'flex-wrap: wrap !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .original-price {';
        $css .= 'color: ' . $this->getSetting($settings, 'original_price_color', '#9ca3af') . ' !important;';
        $css .= 'font-size: 1.2rem !important;';
        $css .= 'text-decoration: line-through !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .discount-badge {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'discount_badge_color', '#ef4444') . ' !important;';
        $css .= 'color: ' . $this->getSetting($settings, 'discount_badge_text_color', '#ffffff') . ' !important;';
        $css .= 'padding: 4px 8px !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'font-size: 12px !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= '}';

        // Description
        if ($this->getSetting($settings, 'show_description', true)) {
            $css .= '.shopglut-single-product-container .product-description {';
            $css .= 'color: ' . $this->getSetting($settings, 'description_color', '#6b7280') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'description_font_size', 16) . 'px !important;';
            $css .= 'line-height: ' . $this->getSetting($settings, 'description_line_height', 1.6) . ' !important;';
            $css .= 'margin-bottom: 24px !important;';
            $css .= '}';
        }

        // Product Attributes
        if ($this->getSetting($settings, 'show_product_attributes', true)) {
            // General Attribute Settings
            $css .= '.shopglut-single-product-container .attribute-group {';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
            $css .= '}';

            // Attribute Layout Style
            $layout_style = $this->getSetting($settings, 'attribute_layout_style', 'horizontal');
            if ($layout_style === 'vertical') {
                $css .= '.shopglut-single-product-container .product-attributes {';
                $css .= 'display: flex !important;';
                $css .= 'flex-direction: column !important;';
                $css .= '}';
            } elseif ($layout_style === 'grid') {
                $css .= '.shopglut-single-product-container .product-attributes {';
                $css .= 'display: grid !important;';
                $css .= 'grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;';
                $css .= 'gap: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
                $css .= '}';
            } else {
                $css .= '.shopglut-single-product-container .product-attributes {';
                $css .= 'display: flex !important;';
                $css .= 'flex-direction: row !important;';
                $css .= 'flex-wrap: wrap !important;';
                $css .= 'gap: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
                $css .= '}';
            }

            // Attribute Labels
            if ($this->getSetting($settings, 'show_attribute_labels', true)) {
                $css .= '.shopglut-single-product-container .attribute-label {';
                $css .= 'display: block !important;';
                $css .= 'color: ' . $this->getSetting($settings, 'attribute_label_color', '#374151') . ' !important;';
                $css .= 'font-size: ' . $this->getSetting($settings, 'attribute_label_font_size', 14) . 'px !important;';
                $css .= 'font-weight: ' . $this->getSetting($settings, 'attribute_label_font_weight', '500') . ' !important;';
                $css .= 'margin-bottom: ' . $this->getSetting($settings, 'attribute_label_margin_bottom', 8) . 'px !important;';
                $css .= '}';
            }

            
            // Button Attributes (Size, Weight, Version)
            $css .= '.shopglut-single-product-container .size-button {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'button_attribute_background', '#f3f4f6') . ' !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'button_attribute_text_color', '#374151') . ' !important;';
            $css .= 'border: 1px solid ' . $this->getSetting($settings, 'button_attribute_border_color', '#d1d5db') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'button_attribute_border_radius', 6) . 'px !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'button_attribute_padding_vertical', 8) . 'px ' . $this->getSetting($settings, 'button_attribute_padding_horizontal', 16) . 'px !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'button_attribute_font_size', 14) . 'px !important;';
            $css .= 'font-weight: ' . $this->getSetting($settings, 'button_attribute_font_weight', '500') . ' !important;';
            $css .= 'margin-right: ' . $this->getSetting($settings, 'button_attribute_spacing', 8) . 'px !important;';
            $css .= 'cursor: pointer !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';
            $css .= '.shopglut-single-product-container .size-button.active {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'button_attribute_active_background', '#667eea') . ' !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'button_attribute_active_text', '#ffffff') . ' !important;';
            $css .= 'border-color: ' . $this->getSetting($settings, 'button_attribute_active_border', '#667eea') . ' !important;';
            $css .= '}';

            // Dropdown Attributes
            $css .= '.shopglut-single-product-container .attribute-dropdown {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'dropdown_attribute_background', '#ffffff') . ' !important;';
            $css .= 'border: 1px solid ' . $this->getSetting($settings, 'dropdown_attribute_border_color', '#d1d5db') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'dropdown_attribute_border_radius', 6) . 'px !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'dropdown_attribute_padding', 12) . 'px !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'dropdown_attribute_text_color', '#374151') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'dropdown_attribute_font_size', 14) . 'px !important;';
            $css .= '}';

            // Attribute Behavior - Unavailable attributes styling
            if ($this->getSetting($settings, 'show_unavailable_attributes', true)) {
                $css .= '.shopglut-single-product-container .size-button.unavailable, ';
                $css .= '.shopglut-single-product-container .attribute-dropdown.unavailable {';
                $css .= 'opacity: ' . $this->getSetting($settings, 'unavailable_attribute_opacity', 0.5) . ' !important;';
                $css .= 'cursor: not-allowed !important;';
                $css .= '}';
            }

            // Required asterisk styling
            if ($this->getSetting($settings, 'attribute_required_asterisk', true)) {
                $css .= '.shopglut-single-product-container .attribute-label.required:after {';
                $css .= 'content: " *" !important;';
                $css .= 'color: ' . $this->getSetting($settings, 'required_asterisk_color', '#ef4444') . ' !important;';
                $css .= '}';
            }
        }

        // Purchase Section
        $css .= '.shopglut-single-product-container .purchase-section {';
        $css .= 'background: #f8fafc !important;';
        $css .= 'padding: 24px !important;';
        $css .= 'border-radius: 16px !important;';
        $css .= 'border: 1px solid #e2e8f0 !important;';
        $css .= 'margin-top: 24px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .quantity-cart-wrapper {';
        $css .= 'display: flex !important;';
        $css .= 'gap: 12px !important;';
        $css .= 'margin-bottom: 16px !important;';
        $css .= 'align-items: center !important;';
        $css .= '}';

        // Quantity Selector
        $css .= '.shopglut-single-product-container .quantity-selector {';
        $css .= 'display: flex !important;';
        $css .= 'border: 2px solid #e2e8f0 !important;';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'quantity_border_radius', 6) . 'px !important;';
        $css .= 'overflow: hidden !important;';
        $css .= 'background: white !important;';
        $css .= 'min-height: 48px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .quantity-selector .qty-decrease, ';
        $css .= '.shopglut-single-product-container .quantity-selector .qty-increase {';
        $css .= 'min-height: 48px !important;';
        $css .= 'padding: 12px 16px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .quantity-selector .qty-input {';
        $css .= 'min-height: 48px !important;';
        $css .= 'padding: 12px 0 !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .qty-inputty {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'quantity_input_background', '#ffffff') . ' !important;';
        $css .= 'border: none !important;';
        $css .= 'padding: 12px !important;';
        $css .= 'width: 60px !important;';
        $css .= 'text-align: center !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= '}';

        // Add to Cart Button
        $css .= '.shopglut-single-product-container .add-to-cart-btn {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'cart_button_background', '#667eea') . ' !important;';
        $css .= 'color: ' . $this->getSetting($settings, 'cart_button_text_color', '#ffffff') . ' !important;';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'cart_button_border_radius', 8) . 'px !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'cart_button_font_size', 16) . 'px !important;';
        $css .= 'font-weight: ' . $this->getSetting($settings, 'cart_button_font_weight', '600') . ' !important;';
        $css .= 'padding: 12px 24px !important;';
        $css .= 'border: none !important;';
        $css .= 'cursor: pointer !important;';
        $css .= 'transition: all 0.3s ease !important;';
        $css .= 'min-height: 48px !important;';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'justify-content: center !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .add-to-cart-btn:hover {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'cart_button_hover_background', '#5a67d8') . ' !important;';
        $css .= '}';

        // Variable Product Add to Cart Button
        $css .= '.shopglut-single-product-container .shopglut-variable-add-to-cart {';
        $css .= 'min-height: 48px !important;';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'justify-content: center !important;';
        $css .= '}';

        // Secondary Actions
        if ($this->getSetting($settings, 'show_wishlist_button', true) || $this->getSetting($settings, 'show_compare_button', true)) {
            $css .= '.shopglut-single-product-container .wishlist-btn, .shopglut-single-product-container .compare-btn {';
            $css .= 'color: ' . $this->getSetting($settings, 'secondary_button_color', '#6b7280') . ' !important;';
            $css .= 'background: white !important;';
            $css .= 'border: 2px solid #e2e8f0 !important;';
            $css .= 'padding: 12px 20px !important;';
            $css .= 'border-radius: 8px !important;';
            $css .= 'cursor: pointer !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'flex: 1 !important;';
            $css .= 'text-align: center !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= 'min-height: 48px !important;';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'justify-content: center !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .wishlist-btn:hover, .shopglut-single-product-container .compare-btn:hover {';
            $css .= 'color: ' . $this->getSetting($settings, 'secondary_button_hover_color', '#667eea') . ' !important;';
            $css .= 'border-color: ' . $this->getSetting($settings, 'secondary_button_hover_color', '#667eea') . ' !important;';
            $css .= '}';

            // Conditional visibility
            if (!$this->getSetting($settings, 'show_wishlist_button', true)) {
                $css .= '.shopglut-single-product-container .wishlist-btn { display: none !important; }';
            }
            if (!$this->getSetting($settings, 'show_compare_button', true)) {
                $css .= '.shopglut-single-product-container .compare-btn { display: none !important; }';
            }
        }

        // Features Section
        if ($this->getSetting($settings, 'show_features_section', true)) {
            $css .= '.shopglut-single-product-container .features-section {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'features_background_color', '#f9fafb') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'features_border_radius', 12) . 'px !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'features_padding', 24) . 'px !important;';
            $css .= 'margin-top: 40px !important;';
            $css .= 'border: 1px solid #e2e8f0 !important;';
            $css .= '}';

            // Features Section Title
            if ($this->getSetting($settings, 'show_features_section_title', false)) {
                $css .= '.shopglut-single-product-container .features-section-title {';
                $css .= 'color: ' . $this->getSetting($settings, 'features_section_title_color', '#111827') . ' !important;';
                $css .= 'font-size: 24px !important;';
                $css .= 'font-weight: 700 !important;';
                $css .= 'text-align: center !important;';
                $css .= 'margin-bottom: 32px !important;';
                $css .= '}';
            }

            $grid_columns = $this->getSetting($settings, 'features_grid_columns', '4');
            $css .= '.shopglut-single-product-container .features-grid {';
            $css .= 'display: grid !important;';
            $css .= 'grid-template-columns: repeat(' . $grid_columns . ', 1fr) !important;';
            $css .= 'gap: ' . $this->getSetting($settings, 'features_gap', 20) . 'px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .feature-item {';
            $css .= 'text-align: ' . $this->getSetting($settings, 'feature_item_alignment', 'center') . ' !important;';
            $css .= 'padding: 16px !important;';
            $css .= '}';

            // Feature Icons
            $css .= '.shopglut-single-product-container .feature-icon {';
            $css .= 'font-size: ' . $this->getSetting($settings, 'feature_icon_size', 32) . 'px !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_icon_color', '#667eea') . ' !important;';
            $css .= 'background-color: ' . $this->getSetting($settings, 'feature_icon_background', 'transparent') . ' !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'feature_icon_padding', 8) . 'px !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'feature_icon_border_radius', 8) . 'px !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'feature_title_margin_top', 12) . 'px !important;';
            $css .= 'display: inline-block !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';

            // Custom image icons
            $css .= '.shopglut-single-product-container .feature-icon img {';
            $css .= 'width: ' . $this->getSetting($settings, 'feature_icon_size', 32) . 'px !important;';
            $css .= 'height: ' . $this->getSetting($settings, 'feature_icon_size', 32) . 'px !important;';
            $css .= 'object-fit: contain !important;';
            $css .= '}';

            // Feature Titles
            $css .= '.shopglut-single-product-container .feature-title {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_title_color', '#111827') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'feature_title_font_size', 16) . 'px !important;';
            $css .= 'font-weight: ' . $this->getSetting($settings, 'feature_title_font_weight', '600') . ' !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'feature_description_margin_top', 6) . 'px !important;';
            $css .= 'margin-top: ' . $this->getSetting($settings, 'feature_title_margin_top', 12) . 'px !important;';
            $css .= '}';

            // Feature Descriptions
            $css .= '.shopglut-single-product-container .feature-description {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_description_color', '#6b7280') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'feature_description_font_size', 14) . 'px !important;';
            $css .= 'line-height: ' . $this->getSetting($settings, 'feature_description_line_height', 1.5) . ' !important;';
            $css .= '}';

            // Feature Links
            $css .= '.shopglut-single-product-container .feature-item a {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_link_color', '#667eea') . ' !important;';
            $css .= 'text-decoration: ' . $this->getSetting($settings, 'feature_link_decoration', 'none') . ' !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';
            $css .= '.shopglut-single-product-container .feature-item a:hover {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_link_hover_color', '#5a67d8') . ' !important;';
            $css .= '}';
        }

        // Related Products Section
        if ($this->getSetting($settings, 'show_related_products', true)) {
            // Related Products Section Container
            $css .= '.shopglut-single-product-container .related-products-section {';
            $css .= 'margin-top: 60px !important;';
            $css .= 'padding-top: 40px !important;';
            $css .= '}';


            // Products Grid - Use flexbox for better responsive behavior
            $products_per_row = $this->getSetting($settings, 'related_products_per_row', '4');
            $css .= '.shopglut-single-product-container .related-products-grid {';
            if ($products_per_row == '2') {
                $css .= 'display: flex !important;';
                $css .= 'flex-wrap: wrap !important;';
                $css .= 'gap: 20px !important;';
                $css .= 'justify-content: center !important;';
            } else {
                $css .= 'display: grid !important;';
                $css .= 'grid-template-columns: repeat(' . $products_per_row . ', 1fr) !important;';
                $css .= 'gap: 20px !important;';
            }
            $css .= 'margin-top: 32px !important;';
            $css .= '}';

            // Product Cards
            $css .= '.shopglut-single-product-container .related-product-card {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'product_card_background', '#ffffff') . ' !important;';
            $css .= 'border: 1px solid ' . $this->getSetting($settings, 'product_card_border_color', '#e5e7eb') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'product_card_border_radius', 8) . 'px !important;';
            $css .= 'padding: 20px !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= 'position: relative !important;';
            $css .= 'overflow: hidden !important;';
            $css .= 'box-sizing: border-box !important;';

            if ($products_per_row == '2') {
                $css .= 'flex: 0 1 calc(50% - 10px) !important;';
                $css .= 'min-width: 200px !important;';
                $css .= 'max-width: 300px !important;';
            } else {
                $css .= 'width: 100% !important;';
            }
            $css .= '}';

            // Card Hover Effects
            if ($this->getSetting($settings, 'product_card_hover_shadow', true)) {
                $css .= '.shopglut-single-product-container .related-product-card:hover {';
                $css .= 'box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;';
                $css .= 'transform: translateY(-2px) !important;';
                $css .= '}';
            }

            // Product Images
            $css .= '.shopglut-single-product-container .related-product-image {';
            $css .= 'width: 100% !important;';
            $css .= 'height: 180px !important;';
            $css .= 'border-radius: 12px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= 'position: relative !important;';
            $css .= 'overflow: hidden !important;';
            $css .= 'background: #f8f9fa !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .related-product-img {';
            $css .= 'width: 100% !important;';
            $css .= 'height: 100% !important;';
            $css .= 'object-fit: cover !important;';
            $css .= 'object-position: center !important;';
            $css .= 'border-radius: 12px !important;';
            $css .= '}';

            // Product Badges
            $css .= '.shopglut-single-product-container .related-product-badge {';
            $css .= 'position: absolute !important;';
            $css .= 'top: 12px !important;';
            $css .= 'left: 12px !important;';
            $css .= 'background: #ef4444 !important;';
            $css .= 'color: white !important;';
            $css .= 'padding: 4px 8px !important;';
            $css .= 'border-radius: 8px !important;';
            $css .= 'font-size: 12px !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'z-index: 2 !important;';
            $css .= '}';

            // Product Names
            $css .= '.shopglut-single-product-container .related-product-name {';
            $css .= 'font-size: 16px !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'color: #1e293b !important;';
            $css .= 'margin-bottom: 8px !important;';
            $css .= 'line-height: 1.3 !important;';
            $css .= '}';

            // Product Ratings
            $css .= '.shopglut-single-product-container .related-product-rating {';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'gap: 8px !important;';
            $css .= 'margin-bottom: 12px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .related-product-rating .stars {';
            $css .= 'color: #fbbf24 !important;';
            $css .= 'font-size: 14px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .related-product-rating .count {';
            $css .= 'color: #94a3b8 !important;';
            $css .= 'font-size: 12px !important;';
            $css .= '}';

            // Product Prices
            $css .= '.shopglut-single-product-container .related-product-price {';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'gap: 8px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .related-current-price {';
            $css .= 'font-size: 18px !important;';
            $css .= 'font-weight: 700 !important;';
            $css .= 'color: #059669 !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .related-original-price, ';
            $css .= '.shopglut-single-product-container .related-product-price .original {';
            $css .= 'font-size: 14px !important;';
            $css .= 'color: #94a3b8 !important;';
            $css .= 'text-decoration: line-through !important;';
            $css .= 'font-weight: 400 !important;';
            $css .= '}';

            // Quick Add Button
            $css .= '.shopglut-single-product-container .quick-add-btn {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'quick_add_button_background', '#667eea') . ' !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'quick_add_button_text_color', '#ffffff') . ' !important;';
            $css .= 'width: 100% !important;';
            $css .= 'padding: 12px 20px !important;';
            $css .= 'border: none !important;';
            $css .= 'border-radius: 8px !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'cursor: pointer !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= 'font-size: 14px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .quick-add-btn:hover {';
            $css .= 'background-color: #5a67d8 !important;';
            $css .= 'transform: translateY(-2px) !important;';
            $css .= '}';
        }

        // Responsive adjustments for features grid
        $css .= '@media (max-width: 768px) {';
        $css .= '.shopglut-single-product-container .features-grid {';
        $css .= 'grid-template-columns: repeat(2, 1fr);';
        $css .= '}';
        $css .= '.shopglut-single-product-container .related-products-grid {';
        $css .= 'grid-template-columns: repeat(2, 1fr);';
        $css .= '}';
        $css .= '}';

        $css .= '@media (max-width: 480px) {';
        $css .= '.shopglut-single-product-container .features-grid {';
        $css .= 'grid-template-columns: 1fr;';
        $css .= '}';
        $css .= '.shopglut-single-product-container .related-products-grid {';
        $css .= 'grid-template-columns: 1fr;';
        $css .= '}';
        $css .= '}';

        return $css;
    }

    /**
     * Helper method to get setting value with fallback
     */
    private function getSetting($settings, $key, $default = '') {
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * Get layout settings from database
     */
    private function getLayoutSettings($layout_id) {
        if (!$layout_id) {
            return $this->getDefaultSettings();
        }

        // Check cache first
        $cache_key = 'shopglut_single_product_layout_' . $layout_id;
        $layout_data = wp_cache_get($cache_key, 'shopglut_layouts');

        if (false === $layout_data) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_single_product_layout';

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query with caching implemented
            $layout_data = $wpdb->get_row(
                $wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_single_product_layout` WHERE id = %d", $layout_id)
            );

            // Cache the result for 1 hour
            wp_cache_set($cache_key, $layout_data, 'shopglut_layouts', HOUR_IN_SECONDS);
        }

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);

            // Try different possible settings paths
            if (isset($settings['shopg_singleproduct_settings_template3']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template3']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template3']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template3']['cart-page-settings']);
            } elseif (is_array($settings)) {
                // If settings is already flattened, return as is
                return array_merge($this->getDefaultSettings(), $settings);
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
                    // Handle slider fields that have separate value and unit
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
     * Get default settings values for single product template
     */
    private function getDefaultSettings() {
        return array(
            // Product Gallery
            'gallery_section_margin' => 40,
            'main_image_background' => '#f9fafb',
            'main_image_border_radius' => 8,
            'main_image_border_color' => '#e5e7eb',
            'main_image_border_width' => 1,
            'main_image_padding' => 8,
            'main_image_margin_bottom' => 20,
            'main_image_object_fit' => 'cover',
            'show_thumbnails' => true,
            'thumbnail_size' => 80,
            'thumbnail_spacing' => 8,
            'thumbnail_border_radius' => 6,
            'thumbnail_border_width' => 2,
            'thumbnail_border_color' => 'transparent',
            'thumbnail_active_border' => '#667eea',
            'thumbnail_hover_border' => '#2563eb',
            'thumbnail_hover_scale' => 1.05,
            'thumbnail_gallery_margin_top' => 16,
            'thumbnail_alignment' => 'flex-start',
            'thumbnail_object_fit' => 'cover',

            // Product Badges
            'show_product_badges' => true,
            'badge_border_radius' => 4,
            'badge_font_size' => 12,
            'badge_font_weight' => '500',
            'badge_spacing' => 5,
            'show_new_badge' => true,
            'new_badge_text' => 'New',
            'new_badge_background_color' => '#10b981',
            'new_badge_text_color' => '#ffffff',
            'show_trending_badge' => true,
            'trending_badge_text' => 'Trending',
            'trending_badge_background_color' => '#f59e0b',
            'trending_badge_text_color' => '#ffffff',
            'show_bestseller_badge' => true,
            'bestseller_badge_text' => 'Best Seller',
            'bestseller_badge_background_color' => '#ef4444',
            'bestseller_badge_text_color' => '#ffffff',

            // Product Title
            'product_title_color' => '#111827',
            'product_title_font_size' => 32,
            'product_title_font_weight' => '700',

            // Rating
            'show_rating' => true,
            'star_color' => '#fbbf24',
            'rating_text_color' => '#6b7280',
            'rating_font_size' => 14,

            // Price
            'current_price_color' => '#111827',
            'current_price_font_size' => 28,
            'original_price_color' => '#9ca3af',
            'discount_badge_color' => '#ef4444',
            'discount_badge_text_color' => '#ffffff',

            // Description
            'show_description' => true,
            'description_color' => '#6b7280',
            'description_font_size' => 16,
            'description_line_height' => 1.6,

            // Attributes
            'show_product_attributes' => true,
            'show_attribute_labels' => true,
            'attribute_label_color' => '#374151',
            'attribute_label_font_size' => 14,
            'attribute_label_font_weight' => '500',
            'attribute_label_margin_bottom' => 8,

            
            // Button Attributes
            'button_attribute_background' => '#f3f4f6',
            'button_attribute_text_color' => '#374151',
            'button_attribute_border_color' => '#d1d5db',
            'button_attribute_active_background' => '#667eea',
            'button_attribute_active_text' => '#ffffff',
            'button_attribute_active_border' => '#667eea',
            'button_attribute_border_radius' => 6,
            'button_attribute_padding_horizontal' => 16,
            'button_attribute_padding_vertical' => 8,
            'button_attribute_font_size' => 14,
            'button_attribute_font_weight' => '500',
            'button_attribute_spacing' => 8,

            // Purchase Section
            'quantity_button_background' => '#f3f4f6',
            'quantity_button_text_color' => '#374151',
            'quantity_input_background' => '#ffffff',
            'quantity_input_border' => '#d1d5db',
            'quantity_border_radius' => 6,
            'cart_button_background' => '#667eea',
            'cart_button_text_color' => '#ffffff',
            'cart_button_hover_background' => '#5a67d8',
            'cart_button_border_radius' => 8,
            'cart_button_font_size' => 16,
            'cart_button_font_weight' => '600',
            'show_wishlist_button' => true,
            'show_compare_button' => true,
            'secondary_button_color' => '#6b7280',
            'secondary_button_hover_color' => '#667eea',

            // Features Section
            'show_features_section' => true,
            'features_section_title' => 'Why Choose Us',
            'show_features_section_title' => false,
            'features_background_color' => '#f9fafb',
            'features_border_radius' => 12,
            'features_grid_columns' => '4',
            'features_padding' => 24,
            'features_gap' => 20,
            'feature_item_alignment' => 'center',
            'feature_icon_size' => 32,
            'feature_icon_color' => '#667eea',
            'feature_icon_background' => 'transparent',
            'feature_icon_padding' => 8,
            'feature_icon_border_radius' => 8,
            'feature_title_color' => '#111827',
            'feature_title_font_size' => 16,
            'feature_title_font_weight' => '600',
            'feature_title_margin_top' => 12,
            'feature_description_color' => '#6b7280',
            'feature_description_font_size' => 14,
            'feature_description_line_height' => 1.5,
            'feature_description_margin_top' => 6,
            'feature_link_color' => '#667eea',
            'feature_link_hover_color' => '#5a67d8',
            'feature_link_decoration' => 'none',

            // Related Products
            'show_related_products' => true,
            'related_section_title' => 'You Might Also Like',
            'related_section_title_color' => '#111827',
            'related_products_per_row' => '4',
            'product_card_background' => '#ffffff',
            'product_card_border_color' => '#e5e7eb',
            'product_card_border_radius' => 8,
            'product_card_hover_shadow' => true,
            'quick_add_button_background' => '#667eea',
            'quick_add_button_text_color' => '#ffffff',

            // Default features
            'product_features' => array(
                array(
                    'feature_icon_type' => 'fontawesome',
                    'feature_fontawesome_icon' => 'fas fa-shipping-fast',
                    'feature_title' => 'Free Shipping',
                    'feature_description' => 'Free shipping on orders over $50',
                    'feature_link_enabled' => false,
                ),
                array(
                    'feature_icon_type' => 'fontawesome',
                    'feature_fontawesome_icon' => 'fas fa-undo',
                    'feature_title' => 'Easy Returns',
                    'feature_description' => '30-day hassle-free returns',
                    'feature_link_enabled' => false,
                ),
                array(
                    'feature_icon_type' => 'fontawesome',
                    'feature_fontawesome_icon' => 'fas fa-shield-alt',
                    'feature_title' => 'Secure Payment',
                    'feature_description' => '100% secure payment processing',
                    'feature_link_enabled' => false,
                ),
                array(
                    'feature_icon_type' => 'fontawesome',
                    'feature_fontawesome_icon' => 'fas fa-headset',
                    'feature_title' => '24/7 Support',
                    'feature_description' => 'Round-the-clock customer support',
                    'feature_link_enabled' => false,
                ),
            ),
        );
    }

}
