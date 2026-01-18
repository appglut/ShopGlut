<?php
namespace Shopglut\layouts\singleProduct\templates\template4;

class template4Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* Settings-based Dynamic CSS */
        <?php echo wp_kses($this->generateSettingsBasedCSS($settings), array()); ?>

              /* Design 2: Premium */
 .single-product-template4 {
            background: #ffffff;
            color: #333333;
            padding: 20px 0;
            min-height: 100vh;
        }

 .single-product-template4 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

 .single-product-template4 .product-main {
            display: grid;
            grid-template-columns: 1.0fr 1.1fr;
            gap: 40px;
        }

 .single-product-template4 .product-showcase {
            position: relative;
            max-width: 650px;
            margin: 0 auto;
        }

 .single-product-template4 .main-image {
            width: 100%;
            height: 500px;
            background: url('demo-image.png') center/cover no-repeat;
            border-radius: 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 600;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }

 .single-product-template4 .main-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.15) 100%);
            border-radius: 16px;
            pointer-events: none;
        }

 .single-product-template4 .image-nav {
            position: absolute;
            top: 35%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.95);
            border: none;
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #1a1a1a;
            font-size: 1.3rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 10;
        }

 .single-product-template4 .image-nav:hover {
            background: #10b981;
            color: white;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
        }

 .single-product-template4 .prev {
            left: 12px;
        }

 .single-product-template4 .next {
            right: 12px;
        }

 .single-product-template4 .product-details {
            padding: 10px 0;
        }

 .single-product-template4 .badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

 .single-product-template4 .badge::before {
            content: '';
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

 .single-product-template4 h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-top: 12px;
            margin-bottom: 12px;
            line-height: 1.1;
        }

 .single-product-template4 .rating {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

 .single-product-template4 .stars {
            color: #fbbf24;
            font-size: 1.2rem;
        }

 .single-product-template4 .price-section {
            margin-bottom: 20px;
        }

 .single-product-template4 .current-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #10b981;
        }

 .single-product-template4 .original-price {
            font-size: 1.5rem;
            color: #6b7280;
            text-decoration: line-through;
            margin-left: 12px;
        }

 .single-product-template4 .description {
            color: #4b5563;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

 .single-product-template4 .premium-features {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

 .single-product-template4 .premium-features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

 .single-product-template4 .feature-list {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 24px;
        }

 .single-product-template4 .feature-list li {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #374151;
            background: white;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

 .single-product-template4 .feature-list li:hover {
            transform: translateX(5px);
            border-color: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

 .single-product-template4 .feature-list li:before {
            content: "";
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

 .single-product-template4 .feature-list li:after {
            content: "✓";
            color: white;
            font-weight: bold;
            font-size: 12px;
            margin-left: -22px;
        }

 .single-product-template4 .cta-section {
            display: flex;
            gap: 16px;
        }

 .single-product-template4 .primary-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

 .single-product-template4 .primary-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
        }

 .single-product-template4 .secondary-btn {
            background: transparent;
            color: #4b5563;
            border: 2px solid #d1d5db;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template4 .secondary-btn:hover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

 .single-product-template4 .wishlist-btn {
            background: transparent;
            border: 2px solid #d1d5db;
            color: #6b7280;
            padding: 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

 .single-product-template4 .wishlist-btn:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

 .single-product-template4 .wishlist-btn.active {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        /* Product Tabs */
 .single-product-template4 .product-tabs {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 32px;
            width: 100%;
        }

 .single-product-template4 .tab-navigation {
            display: flex;
            gap: 40px;
            margin-bottom: 32px;
            border-bottom: 1px solid #e5e7eb;
            width: 100%;
            justify-content: flex-start;
        }

 .single-product-template4 .tab-btn {
            background: none;
            border: none;
            padding: 16px 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
        }

 .single-product-template4 .tab-btn:hover,
 .single-product-template4 .tab-btn.active {
            color: #1a1a1a;
        }

 .single-product-template4 .tab-btn.active:after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #10b981;
        }

 .single-product-template4 .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

 .single-product-template4 .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

 .single-product-template4 .tab-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
        }

 .single-product-template4 .tab-content p {
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 16px;
        }

 .single-product-template4 .specifications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

 .single-product-template4 .spec-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

 .single-product-template4 .spec-label {
            font-weight: 600;
            color: #1a1a1a;
        }

 .single-product-template4 .spec-value {
            color: #4b5563;
        }

 .single-product-template4 .review-item {
            background: #f9fafb;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }

 .single-product-template4 .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

 .single-product-template4 .reviewer-name {
            font-weight: 600;
            color: #1a1a1a;
        }

 .single-product-template4 .review-rating {
            color: #fbbf24;
        }

 .single-product-template4 .review-text {
            color: #4b5563;
            line-height: 1.6;
        }

        /* Related Products */
 .single-product-template4 .related-products {
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid #e5e7eb;
        }

 .single-product-template4 .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-align: center;
        }

 .single-product-template4 .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

 .single-product-template4 .product-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

 .single-product-template4 .product-card:hover {
            transform: translateY(-4px);
            background: #ffffff;
            border-color: #d1d5db;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

 .single-product-template4 .product-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            background: url('demo-image.png') center/cover no-repeat;
            position: relative;
        }

 .single-product-template4 .product-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0.08) 100%);
            border-radius: 12px;
            pointer-events: none;
        }

 .single-product-template4 .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

 .single-product-template4 .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 16px;
        }

 .single-product-template4 .quick-add-btn {
            width: 100%;
            background: transparent;
            border: 2px solid #d1d5db;
            color: #4b5563;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template4 .quick-add-btn:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        /* Customer Reviews Summary */
 .single-product-template4 .reviews-summary {
            background: #f9fafb;
            padding: 32px;
            border-radius: 16px;
            margin-bottom: 32px;
            border: 1px solid #e5e7eb;
        }

 .single-product-template4 .rating-overview {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 32px;
            align-items: center;
        }

 .single-product-template4 .overall-rating {
            text-align: center;
        }

 .single-product-template4 .rating-number {
            font-size: 3rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

 .single-product-template4 .rating-stars {
            color: #fbbf24;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

 .single-product-template4 .rating-count {
            color: #6b7280;
            font-size: 0.9rem;
        }

 .single-product-template4 .rating-breakdown {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

 .single-product-template4 .rating-bar {
            display: flex;
            align-items: center;
            gap: 12px;
        }

 .single-product-template4 .star-count {
            font-size: 0.9rem;
            color: #6b7280;
            width: 60px;
        }

 .single-product-template4 .bar-container {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

 .single-product-template4 .bar-fill {
            height: 100%;
            background: #fbbf24;
            transition: width 0.3s ease;
        }

 .single-product-template4 .bar-percentage {
            font-size: 0.9rem;
            color: #6b7280;
            width: 40px;
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
 .single-product-template4 .product-main {
                grid-template-columns: 1fr;
                gap: 30px;
            }

 .single-product-template4 .feature-list {
                grid-template-columns: 1fr;
            }

 .single-product-template4 h1 {
                font-size: 2rem;
            }

 .single-product-template4 .current-price {
                font-size: 1.8rem;
            }

 .single-product-template4 .main-image {
                height: 400px;
            }

 .single-product-template4 .cta-section {
                flex-direction: column;
                gap: 12px;
            }

 .single-product-template4 .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

 .single-product-template4 .tab-navigation {
                gap: 20px;
                flex-wrap: wrap;
            }

 .single-product-template4 .tab-btn {
                font-size: 1rem;
            }

 .single-product-template4 .rating-overview {
                grid-template-columns: 1fr;
                gap: 20px;
            }

 .single-product-template4 .specifications-grid {
                grid-template-columns: 1fr;
            }

 .single-product-template4 .main-image {
                height: 380px;
            }

 .single-product-template4 .product-showcase {
                max-width: 100%;
            }

 .single-product-template4 .image-nav {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

 .single-product-template4 .prev {
                left: 10px;
            }

 .single-product-template4 .next {
                right: 10px;
            }
        }

        @media (max-width: 480px) {
 .single-product-template4 .container {
                padding: 0 15px;
            }

 .single-product-template4 h1 {
                font-size: 1.8rem;
            }

 .single-product-template4 .current-price {
                font-size: 1.5rem;
            }

 .single-product-template4 .main-image {
                height: 280px;
                font-size: 16px;
            }

 .single-product-template4 .product-showcase {
                max-width: 100%;
            }

 .single-product-template4 .image-nav {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

 .single-product-template4 .prev {
                left: 8px;
            }

 .single-product-template4 .next {
                right: 8px;
            }

 .single-product-template4 .products-grid {
                grid-template-columns: 1fr;
            }

 .single-product-template4 .tab-navigation {
                gap: 15px;
            }

 .single-product-template4 .tab-btn {
                font-size: 0.9rem;
                padding: 12px 0;
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
            $css .= 'transform: scale(' . $this->getSetting($settings, 'thumbnail_hover_scale', 0.65) . ');';
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
        $css .= '}';

        $css .= '.shopglut-single-product-container .add-to-cart-btn:hover {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'cart_button_hover_background', '#5a67d8') . ' !important;';
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
            if (isset($settings['shopg_singleproduct_settings_template4']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template4']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template4']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template4']['cart-page-settings']);
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
