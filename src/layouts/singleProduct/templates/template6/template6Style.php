<?php
namespace Shopglut\layouts\singleProduct\templates\template6;

class template6Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
  <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
        }

        .single-product-template6 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: var(--dark-color);
        }

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .px-4 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-lg-6 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            flex-basis: 100%;
            max-width: 100%;
        }

        @media (min-width: 992px) {
            .col-lg-6 {
                flex-basis: 0;
                flex-grow: 1;
                max-width: 50%;
            }
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .fw-bold {
            font-weight: 700;
        }

        .text-muted {
            color: #6b7280;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        /* Tab functionality styles */
        .tab-content .tab-pane {
            display: none;
        }

        .tab-content .tab-pane.active {
            display: block;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color) !important;
            background-color: white !important;
            font-weight: 600;
            border-bottom-color: var(--primary-color) !important;
        }

        .single-product-template6 .product-page {
            padding: 30px 0;
        }

        .single-product-template6 .product-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        /* Left Side - Image Gallery */
        .single-product-template6 .left-gallery {
            padding-right: 30px;
        }

        .single-product-template6 .image-gallery {
            display: flex;
            gap: 15px;
            height: 100%;
        }

        .single-product-template6 .thumbnail-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .single-product-template6 .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            flex-shrink: 0;
        }

        .single-product-template6 .thumbnail.active,
        .single-product-template6 .thumbnail:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .single-product-template6 .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .single-product-template6 .main-image-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            min-height: 400px;
        }

        .single-product-template6 .main-image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .single-product-template6 .main-image:hover {
            transform: scale(1.05);
        }

        /* Right Side - Product Details */
        .single-product-template6 .product-details {
            padding-left: 30px;
        }

        .single-product-template6 .rating-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .single-product-template6 .stars {
            color: var(--secondary-color);
        }

        .single-product-template6 .review-count {
            font-size: 14px;
            color: #6b7280;
            padding-left: 15px;
            border-left: 1px solid var(--border-color);
        }

        .single-product-template6 .product-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
            color: var(--dark-color);
        }

        .single-product-template6 .product-inline-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .single-product-template6 .inline-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6b7280;
        }

        .single-product-template6 .inline-meta-item .meta-icon {
            color: var(--primary-color);
            font-size: 16px;
        }

        .single-product-template6 .inline-meta-item .meta-label {
            font-weight: 600;
            color: #374151;
        }

        .single-product-template6 .inline-meta-item .meta-value {
            color: #6b7280;
        }

        .single-product-template6 .price-stock {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .single-product-template6 .price {
            font-size: 32px;
            font-weight: 700;
            color: var(--danger-color);
        }

        .single-product-template6 .stock-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background-color: #ecfdf5;
            border-radius: 20px;
            color: var(--success-color);
            font-weight: 600;
            font-size: 14px;
        }

        .single-product-template6 .short-description {
            font-size: 16px;
            line-height: 1.6;
            color: #6b7280;
            margin-bottom: 25px;
        }

        /* Product Options - Enhanced */
        .single-product-template6 .product-options {
            margin-bottom: 30px;
        }

        .single-product-template6 .option-group {
            margin-bottom: 25px;
        }

        .single-product-template6 .option-label {
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
            color: var(--dark-color);
            font-size: 15px;
        }

        .single-product-template6 .option-values {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .single-product-template6 .option-value {
            padding: 10px 18px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        /* Color options - Enhanced */
        .single-product-template6 .color-option {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            padding: 3px;
            position: relative;
            border: 3px solid transparent;
        }

        .single-product-template6 .color-option .color-swatch {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: block;
        }

        .single-product-template6 .color-option:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .single-product-template6 .color-option.selected {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        /* Size options - Enhanced */
        .single-product-template6 .size-option {
            min-width: 60px;
            text-align: center;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .single-product-template6 .option-value:hover {
            border-color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .single-product-template6 .option-value.selected {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Action Buttons */
        .single-product-template6 .action-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 25px;
        }

        .single-product-template6 .action-row-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .single-product-template6 .action-row-bottom {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .single-product-template6 .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
            height: 50px;
        }

        .single-product-template6 .quantity-selector button {
            background: #f8f9fa;
            border: none;
            padding: 0 20px;
            height: 100%;
            cursor: pointer;
            color: var(--dark-color);
            transition: all 0.2s ease;
            font-weight: 600;
            font-size: 20px;
        }

        .single-product-template6 .quantity-selector button:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .single-product-template6 .quantity-selector input {
            border: none;
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            text-align: center;
            width: 70px;
            height: 100%;
            font-weight: 600;
            font-size: 16px;
            background-color: white;
        }

        .single-product-template6 .btn-add-to-cart {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            border: none;
            padding: 16px 36px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            justify-content: center;
            letter-spacing: 0.5px;
            height: 50px;
        }

        .single-product-template6 .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .single-product-template6 .btn-add-to-cart:active {
            transform: translateY(0);
        }

        /* Wishlist and Compare buttons */
        .single-product-template6 .action-row-bottom {
            display: flex;
            gap: 12px;
        }

        .single-product-template6 .btn-wishlist,
        .single-product-template6 .btn-compare {
            background: white;
            color: var(--dark-color);
            border: 2px solid var(--border-color);
            padding: 0 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            white-space: nowrap;
            height: 54px;
            flex: 1;
        }

        .single-product-template6 .btn-wishlist:hover {
            border-color: #ef4444;
            color: #ef4444;
            background-color: #fef2f2;
        }

        .single-product-template6 .btn-compare:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: #eff6ff;
        }

        .single-product-template6 .btn-wishlist span,
        .single-product-template6 .btn-compare span {
            font-size: 20px;
        }

        .single-product-template6 .border-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 25px 0;
        }

        /* Product Meta */
        .single-product-template6 .product-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .single-product-template6 .meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0;
        }

        .single-product-template6 .meta-item span:first-child {
            color: var(--primary-color);
            font-size: 18px;
        }

        .single-product-template6 .meta-content h6 {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.5px;
        }

        .single-product-template6 .meta-content p {
            font-size: 14px;
            margin: 0;
            color: var(--dark-color);
            line-height: 1.4;
        }

        .single-product-template6 .social-share {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
            justify-content: flex-end;
        }

        .single-product-template6 .social-share h6 {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: var(--dark-color);
            white-space: nowrap;
        }

        .single-product-template6 .social-icons {
            display: flex;
            gap: 10px;
        }

        .single-product-template6 .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .single-product-template6 .social-icon:hover {
            transform: translateY(-3px);
        }

        .single-product-template6 .social-icon.facebook:hover {
            background-color: #1877f2;
            color: white;
            box-shadow: 0 4px 12px rgba(24, 119, 242, 0.4);
        }

        .single-product-template6 .social-icon.twitter:hover {
            background-color: #1da1f2;
            color: white;
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.4);
        }

        .single-product-template6 .social-icon.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 39, 67, 0.4);
        }

        .single-product-template6 .social-icon.pinterest:hover {
            background-color: #bd081c;
            color: white;
            box-shadow: 0 4px 12px rgba(189, 8, 28, 0.4);
        }

        /* Tabs Section */
        .single-product-template6 .tabs-wrapper {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 40px;
            background-color: white;
        }

        .single-product-template6 .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            background-color: var(--light-bg);
            padding: 0;
            display: flex;
            list-style: none;
            margin: 0;
        }

        .single-product-template6 .nav-tabs .nav-item {
            margin-bottom: 0;
        }

        .single-product-template6 .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6b7280;
            font-weight: 500;
            font-size: 15px;
            padding: 16px 24px;
            margin: 0;
            border-radius: 0;
            transition: all 0.3s ease;
            background: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .single-product-template6 .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.05);
        }

        .single-product-template6 .tab-content {
            padding: 30px;
        }

        .single-product-template6 .tab-pane h4 {
            margin-bottom: 20px;
            color: var(--dark-color);
            font-size: 22px;
        }

        .single-product-template6 .tab-pane p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Related Products */
        .single-product-template6 .related-products {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .single-product-template6 .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .single-product-template6 .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .single-product-template6 .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .single-product-template6 .product-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .single-product-template6 .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .single-product-template6 .product-image {
            height: 150px;
            overflow: hidden;
            position: relative;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single-product-template6 .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .single-product-template6 .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .single-product-template6 .product-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: var(--danger-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .single-product-template6 .product-info {
            padding: 15px;
        }

        .single-product-template6 .product-name {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .single-product-template6 .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .single-product-template6 .current-price-small {
            font-size: 16px;
            font-weight: 700;
            color: var(--danger-color);
        }

        .single-product-template6 .original-price-small {
            font-size: 12px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .single-product-template6 .product-rating-small {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .single-product-template6 .product-rating-small .stars {
            color: var(--secondary-color);
        }

        .single-product-template6 .review-count-small {
            color: #9ca3af;
        }

        .single-product-template6 .btn-view-product {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .single-product-template6 .btn-view-product:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 992px) {
            .single-product-template6 .image-gallery {
                flex-direction: column;
            }

            .single-product-template6 .thumbnail-list {
                flex-direction: row;
                justify-content: center;
            }

            .single-product-template6 .main-image-container {
                min-height: 300px;
            }

            .single-product-template6 .product-details {
                padding-left: 0;
                padding-top: 30px;
                border-top: 1px solid var(--border-color);
            }

            .single-product-template6 .left-gallery {
                padding-right: 0;
            }
        }

        @media (max-width: 768px) {
            .single-product-template6 .product-container {
                padding: 20px;
            }

            .single-product-template6 .product-title {
                font-size: 22px;
            }

            .single-product-template6 .price {
                font-size: 24px;
            }

            .single-product-template6 .price-stock {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .single-product-template6 .action-row {
                flex-direction: column;
                gap: 10px;
            }

            .single-product-template6 .action-row .quantity-selector,
            .single-product-template6 .action-row .btn-add-to-cart,
            .single-product-template6 .action-row .btn-wishlist,
            .single-product-template6 .action-row .btn-compare {
                width: 100%;
            }

            .single-product-template6 .action-row .btn-wishlist,
            .single-product-template6 .action-row .btn-compare {
                justify-content: center;
            }

            .single-product-template6 .product-meta {
                grid-template-columns: 1fr;
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
            if (isset($settings['shopg_singleproduct_settings_template6']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template6']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template6']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template6']['cart-page-settings']);
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
