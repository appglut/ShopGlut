<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro10;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		?>
		<style id="shopglut-templatePro10-dynamic-css">
        .shopglut-single-templatePro10 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ==================== THREE COLUMN LAYOUT ==================== */
        .shopglut-single-templatePro10 .product-page {
            display: grid;
            grid-template-columns: 1.4fr 1.2fr 1fr;
            gap: 35px;
            margin: 50px 0;
        }

        /* ==================== LEFT COLUMN - GALLERY ==================== */
        .shopglut-single-templatePro10 .left-gallery {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .shopglut-single-templatePro10 .main-image-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        .shopglut-single-templatePro10 .main-image-wrapper img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .shopglut-single-templatePro10 .main-image-wrapper:hover img {
            transform: scale(1.08);
        }

        .shopglut-single-templatePro10 .image-zoom-badge {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro10 .image-zoom-badge:hover {
            background: #0073aa;
            color: white;
            transform: scale(1.1);
        }

        /* Thumbnails Beneath Main Image */
        .shopglut-single-templatePro10 .thumbnails-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        /* Gallery Actions */
        .shopglut-single-templatePro10 .gallery-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .shopglut-single-templatePro10 .gallery-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: white;
            border: 2px solid #e8ecf1;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .shopglut-single-templatePro10 .gallery-action-btn:hover {
            border-color: #0073aa;
            color: #0073aa;
            background: #f8f9fa;
        }

        .shopglut-single-templatePro10 .gallery-action-btn i {
            font-size: 18px;
        }

        /* Quick Features */
        .shopglut-single-templatePro10 .quick-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .shopglut-single-templatePro10 .quick-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 15px 10px;
            background: white;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }

        .shopglut-single-templatePro10 .quick-feature:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .shopglut-single-templatePro10 .quick-feature i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0073aa, #005a87);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .shopglut-single-templatePro10 .quick-feature span {
            font-size: 11px;
            color: #666;
            font-weight: 500;
        }

        .shopglut-single-templatePro10 .thumbnail-item {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .shopglut-single-templatePro10 .thumbnail-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .shopglut-single-templatePro10 .thumbnail-item.active {
            border-color: #0073aa;
            box-shadow: 0 8px 25px rgba(0,115,170,0.25);
        }

        .shopglut-single-templatePro10 .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ==================== MIDDLE COLUMN - INFO ==================== */
        .shopglut-single-templatePro10 .middle-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Product Badge */
        .shopglut-single-templatePro10 .product-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            width: fit-content;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Product Title */
        .shopglut-single-templatePro10 .product-title {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.3;
            color: #1a1a2e;
            letter-spacing: -0.5px;
        }

        /* Reviews Section */
        .shopglut-single-templatePro10 .reviews-section {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #fff9e6 0%, #fff3d3 100%);
            border-radius: 16px;
            border-left: 4px solid #ffc107;
        }

        .shopglut-single-templatePro10 .reviews-stars {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .shopglut-single-templatePro10 .reviews-stars .stars {
            color: #ffc107;
            font-size: 22px;
        }

        .shopglut-single-templatePro10 .reviews-stars .average {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .shopglut-single-templatePro10 .reviews-details {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .shopglut-single-templatePro10 .reviews-details span {
            font-size: 14px;
            color: #666;
        }

        .shopglut-single-templatePro10 .reviews-details a {
            color: #0073aa;
            text-decoration: none;
            font-weight: 500;
        }

        .shopglut-single-templatePro10 .reviews-details a:hover {
            text-decoration: underline;
        }

        /* Product Description */
        .shopglut-single-templatePro10 .product-description {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Price Section */
        .shopglut-single-templatePro10 .price-section {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 25px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 16px;
        }

        .shopglut-single-templatePro10 .current-price {
            font-size: 42px;
            font-weight: 700;
            color: #2e7d32;
        }

        .shopglut-single-templatePro10 .original-price {
            font-size: 22px;
            color: #999;
            text-decoration: line-through;
        }

        .shopglut-single-templatePro10 .save-badge {
            background: #2e7d32;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Product Variations */
        .shopglut-single-templatePro10 .variations-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 25px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .shopglut-single-templatePro10 .variation-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .shopglut-single-templatePro10 .variation-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .shopglut-single-templatePro10 .color-swatches {
            display: flex;
            gap: 12px;
        }

        .shopglut-single-templatePro10 .color-swatch {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .shopglut-single-templatePro10 .color-swatch:hover,
        .shopglut-single-templatePro10 .color-swatch.selected {
            border-color: #0073aa;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,115,170,0.3);
        }

        .shopglut-single-templatePro10 .size-swatches {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro10 .size-swatch {
            padding: 12px 24px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            background: white;
        }

        .shopglut-single-templatePro10 .size-swatch:hover {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro10 .size-swatch.selected {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }

        /* Product Meta Information */
        .shopglut-single-templatePro10 .product-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .shopglut-single-templatePro10 .meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            transition: all 0.3s;
            border: 1px solid #e8ecf1;
        }

        .shopglut-single-templatePro10 .meta-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 15px rgba(0,115,170,0.1);
        }

        .shopglut-single-templatePro10 .meta-item i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0073aa, #005a87);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .shopglut-single-templatePro10 .meta-item div {
            display: flex;
            flex-direction: column;
        }

        .shopglut-single-templatePro10 .meta-item label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .shopglut-single-templatePro10 .meta-item span {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .shopglut-single-templatePro10 .meta-item a {
            font-size: 14px;
            color: #0073aa;
            text-decoration: none;
            font-weight: 500;
        }

        .shopglut-single-templatePro10 .meta-item a:hover {
            text-decoration: underline;
        }

        /* Stock Status */
        .shopglut-single-templatePro10 .stock-status {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
        }

        .shopglut-single-templatePro10 .stock-indicator {
            width: 12px;
            height: 12px;
            background: #2e7d32;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .shopglut-single-templatePro10 .stock-status span {
            font-size: 14px;
            color: #1565c0;
            font-weight: 500;
        }

        /* ==================== RIGHT COLUMN - SIDEBAR ==================== */
        .shopglut-single-templatePro10 .right-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Border Box - Product Highlights */
        .shopglut-single-templatePro10 .highlight-box {
            background: white;
            border: 2px solid #e8ecf1;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }

        .shopglut-single-templatePro10 .highlight-box h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-single-templatePro10 .highlight-box h3 i {
            color: #0073aa;
        }

        .shopglut-single-templatePro10 .highlight-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro10 .highlight-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro10 .highlight-item:hover {
            background: #e8f4f8;
            transform: translateX(5px);
        }

        .shopglut-single-templatePro10 .highlight-item i {
            color: #28a745;
            font-size: 18px;
            margin-top: 2px;
        }

        .shopglut-single-templatePro10 .highlight-item div {
            flex: 1;
        }

        .shopglut-single-templatePro10 .highlight-item strong {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .shopglut-single-templatePro10 .highlight-item p {
            font-size: 12px;
            color: #666;
            margin: 0;
            line-height: 1.5;
        }

        /* Total Price Box */
        .shopglut-single-templatePro10 .total-box {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: 0 15px 50px rgba(26, 26, 46, 0.4);
        }

        .shopglut-single-templatePro10 .total-box-label {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro10 .total-box-price {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro10 .total-box-price span {
            font-size: 24px;
        }

        .shopglut-single-templatePro10 .total-box-savings {
            background: rgba(255,255,255,0.1);
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
        }

        /* Action Buttons */
        .shopglut-single-templatePro10 .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro10 .btn {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .shopglut-single-templatePro10 .btn-wishlist {
            background: white;
            border: 2px solid #e8ecf1;
            color: #555;
        }

        .shopglut-single-templatePro10 .btn-wishlist:hover {
            border-color: #e91e63;
            color: #e91e63;
            background: #fff5f8;
        }

        .shopglut-single-templatePro10 .btn-addcart {
            background: linear-gradient(135deg, #0073aa 0%, #005a87 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(0,115,170,0.3);
        }

        .shopglut-single-templatePro10 .btn-addcart:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,115,170,0.4);
        }

        /* Trust Badges */
        .shopglut-single-templatePro10 .trust-badges {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .shopglut-single-templatePro10 .trust-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro10 .trust-badge:hover {
            border-color: #0073aa;
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro10 .trust-badge i {
            font-size: 24px;
            color: #0073aa;
        }

        .shopglut-single-templatePro10 .trust-badge span {
            font-size: 12px;
            color: #555;
            font-weight: 500;
        }

        /* ==================== FULL WIDTH SECTIONS ==================== */
        .shopglut-single-templatePro10 .full-width {
            grid-column: 1 / -1;
        }

        /* Product Tabs */
        .shopglut-single-templatePro10 .product-tabs {
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 50px 0;
        }

        .shopglut-single-templatePro10 .tab-navigation {
            display: flex;
            border-bottom: 1px solid #e8ecf1;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .shopglut-single-templatePro10 .tab-button {
            padding: 22px 40px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            color: #666;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -1px;
            position: relative;
        }

        .shopglut-single-templatePro10 .tab-button:hover {
            color: #0073aa;
        }

        .shopglut-single-templatePro10 .tab-button.active {
            color: #0073aa;
            background: white;
        }

        .shopglut-single-templatePro10 .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: #0073aa;
        }

        .shopglut-single-templatePro10 .tab-content {
            padding: 45px;
            display: none;
        }

        .shopglut-single-templatePro10 .tab-content.active {
            display: block;
        }

        .shopglut-single-templatePro10 .tab-content h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #1a1a2e;
        }

        .shopglut-single-templatePro10 .tab-content p {
            line-height: 1.9;
            color: #555;
            margin-bottom: 15px;
            font-size: 16px;
        }

        /* Related Products */
        .shopglut-single-templatePro10 .related-products {
            margin: 50px 0;
        }

        .shopglut-single-templatePro10 .section-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #1a1a2e;
            position: relative;
        }

        .shopglut-single-templatePro10 .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #0073aa, #005a87);
            border-radius: 2px;
        }

        .shopglut-single-templatePro10 .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
        }

        .shopglut-single-templatePro10 .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s;
        }

        .shopglut-single-templatePro10 .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .shopglut-single-templatePro10 .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .shopglut-single-templatePro10 .product-card-info {
            padding: 22px;
        }

        .shopglut-single-templatePro10 .product-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1a1a2e;
        }

        .shopglut-single-templatePro10 .product-card-price {
            font-size: 22px;
            font-weight: 700;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro10 .product-card-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0073aa, #005a87);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro10 .product-card-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0,115,170,0.3);
        }

        /* Toast */
        .shopglut-single-templatePro10 .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: white;
            padding: 18px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 1000;
            transform: translateX(150%);
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .shopglut-single-templatePro10 .toast.show {
            transform: translateX(0);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .shopglut-single-templatePro10 .product-page {
                grid-template-columns: 1fr 1fr;
            }

            .shopglut-single-templatePro10 .right-sidebar {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 992px) {
            .shopglut-single-templatePro10 .product-page {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro10 .main-image-wrapper img {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro10 .product-title {
                font-size: 26px;
            }

            .shopglut-single-templatePro10 .current-price {
                font-size: 32px;
            }

            .shopglut-single-templatePro10 .tab-navigation {
                overflow-x: auto;
            }

            .shopglut-single-templatePro10 .product-meta {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro10 .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .shopglut-single-templatePro10 .thumbnails-container {
                grid-template-columns: repeat(3, 1fr);
            }

            .shopglut-single-templatePro10 .total-box-price {
                font-size: 36px;
            }
        }
		</style>
<?php
	}

	private function getBaseStyles() {
		return '
		/* TemplatePro1 - Premium Product Layout Base Styles */
		.shopglut-pro-product-wrapper {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			line-height: 1.6;
			color: #1a1a1a;
			background: #ffffff;
		}

		.pro-header-banner {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: #ffffff;
			text-align: center;
			padding: 30px 20px;
			margin-bottom: 30px;
		}

		.pro-header-banner .pro-badge {
			display: inline-block;
			background: rgba(255,255,255,0.2);
			padding: 5px 15px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			letter-spacing: 1px;
			margin-bottom: 15px;
		}

		.pro-header-banner h2 {
			font-size: 28px;
			font-weight: 700;
			margin: 0 0 10px 0;
		}

		.pro-header-banner p {
			font-size: 16px;
			opacity: 0.9;
			margin: 0;
		}
		';
	}

	private function getHeaderBannerStyles() {
		return '
		/* Header Banner Responsive */
		@media (max-width: 768px) {
			.pro-header-banner h2 {
				font-size: 22px;
			}
			.pro-header-banner p {
				font-size: 14px;
			}
		}
		';
	}

	private function getProductContainerStyles() {
		return '
		/* Product Container */
		.pro-product-container {
			display: flex;
			gap: 60px;
			max-width: 1240px;
			margin: 0 auto;
			padding: 0 20px;
		}

		@media (max-width: 968px) {
			.pro-product-container {
				flex-direction: column;
				gap: 40px;
			}
		}

		/* Product Gallery Section */
		.pro-product-gallery {
			flex: 0 0 50%;
		}

		@media (max-width: 968px) {
			.pro-product-gallery {
				flex: 1;
			}
		}

		/* Product Details Section */
		.pro-product-details {
			flex: 0 0 50%;
		}

		@media (max-width: 968px) {
			.pro-product-details {
				flex: 1;
			}
		}
		';
	}

	private function getGalleryStyles() {
		return '
		/* Main Product Image */
		.pro-main-image {
			position: relative;
			border-radius: 16px;
			overflow: hidden;
			background: #f8f9fa;
			margin-bottom: 20px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
		}

		.pro-main-image img {
			width: 100%;
			height: auto;
			display: block;
			transition: transform 0.3s ease;
		}

		.pro-main-image:hover img {
			transform: scale(1.05);
		}

		/* Quick View Badge */
		.pro-quick-view-badge {
			position: absolute;
			top: 20px;
			right: 20px;
			background: rgba(255,255,255,0.95);
			padding: 8px 16px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			color: #667eea;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}

		/* Thumbnails */
		.pro-thumbnails {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 12px;
		}

		.pro-thumb {
			border-radius: 12px;
			overflow: hidden;
			cursor: pointer;
			border: 3px solid transparent;
			transition: all 0.3s ease;
			background: #f8f9fa;
		}

		.pro-thumb:hover,
		.pro-thumb.active {
			border-color: #667eea;
			transform: translateY(-2px);
		}

		.pro-thumb img {
			width: 100%;
			height: 80px;
			object-fit: cover;
			display: block;
		}

		@media (max-width: 480px) {
			.pro-thumbnails {
				gap: 8px;
			}
			.pro-thumb img {
				height: 60px;
			}
		}
		';
	}

	private function getProductDetailsStyles() {
		return '
		/* Product Badges */
		.pro-badges {
			display: flex;
			gap: 10px;
			margin-bottom: 15px;
		}

		.pro-badge-new,
		.pro-badge-hot,
		.pro-badge-sale {
			padding: 6px 14px;
			border-radius: 6px;
			font-size: 12px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.pro-badge-new {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%);
			color: #ffffff;
		}

		.pro-badge-hot {
			background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
			color: #ffffff;
		}

		.pro-badge-sale {
			background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
			color: #ffffff;
		}

		/* Product Category */
		.pro-category {
			color: #6b7280;
			font-size: 14px;
			margin-bottom: 10px;
		}

		/* Product Title */
		.pro-title {
			font-size: 32px;
			font-weight: 700;
			color: #1a1a1a;
			margin: 0 0 15px 0;
			line-height: 1.3;
		}

		@media (max-width: 768px) {
			.pro-title {
				font-size: 24px;
			}
		}

		/* Rating */
		.pro-rating {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 20px;
		}

		.pro-stars {
			color: #fbbf24;
			font-size: 16px;
		}

		.pro-rating-text {
			color: #6b7280;
			font-size: 14px;
		}

		/* Price */
		.pro-price-wrapper {
			display: flex;
			align-items: center;
			gap: 15px;
			margin-bottom: 20px;
			flex-wrap: wrap;
		}

		.pro-current-price {
			font-size: 36px;
			font-weight: 700;
			color: #1a1a1a;
		}

		.pro-original-price {
			font-size: 24px;
			color: #9ca3af;
			text-decoration: line-through;
		}

		.pro-discount {
			background: #10b981;
			color: #ffffff;
			padding: 6px 12px;
			border-radius: 6px;
			font-size: 14px;
			font-weight: 600;
		}

		@media (max-width: 768px) {
			.pro-current-price {
				font-size: 28px;
			}
			.pro-original-price {
				font-size: 18px;
			}
		}

		/* Short Description */
		.pro-short-description {
			color: #6b7280;
			font-size: 16px;
			line-height: 1.7;
			margin-bottom: 25px;
		}

		/* Product Options */
		.pro-options {
			margin-bottom: 25px;
		}

		.pro-option-group {
			margin-bottom: 20px;
		}

		.pro-option-group label {
			display: block;
			font-weight: 600;
			color: #1a1a1a;
			margin-bottom: 10px;
			font-size: 14px;
		}

		/* Color Options */
		.pro-color-options {
			display: flex;
			gap: 10px;
		}

		.pro-color {
			width: 36px;
			height: 36px;
			border-radius: 50%;
			cursor: pointer;
			transition: all 0.3s ease;
			border: 3px solid transparent;
		}

		.pro-color:hover,
		.pro-color.active {
			transform: scale(1.15);
			border-color: #667eea;
			box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
		}

		/* Size Options */
		.pro-size-options {
			display: flex;
			gap: 10px;
		}

		.pro-size-btn {
			padding: 10px 24px;
			border: 2px solid #e5e7eb;
			background: #ffffff;
			border-radius: 8px;
			cursor: pointer;
			font-size: 14px;
			font-weight: 500;
			transition: all 0.3s ease;
		}

		.pro-size-btn:hover {
			border-color: #667eea;
			background: #f5f3ff;
		}

		.pro-size-btn.active {
			border-color: #667eea;
			background: #667eea;
			color: #ffffff;
		}

		/* Cart Actions */
		.pro-cart-actions {
			display: flex;
			gap: 15px;
			margin-bottom: 20px;
			flex-wrap: wrap;
		}

		.pro-quantity {
			display: flex;
			align-items: center;
			border: 2px solid #e5e7eb;
			border-radius: 10px;
			overflow: hidden;
		}

		.pro-qty-btn {
			width: 44px;
			height: 50px;
			border: none;
			background: #f8f9fa;
			cursor: pointer;
			font-size: 18px;
			font-weight: 600;
			color: #1a1a1a;
			transition: background 0.2s ease;
		}

		.pro-qty-btn:hover {
			background: #e5e7eb;
		}

		.pro-qty-input {
			width: 60px;
			height: 50px;
			border: none;
			text-align: center;
			font-size: 16px;
			font-weight: 600;
		}

		.pro-add-cart {
			flex: 1;
			min-width: 160px;
			padding: 15px 30px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: #ffffff;
			border: none;
			border-radius: 10px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
		}

		.pro-add-cart:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
		}

		.pro-buy-now {
			padding: 15px 30px;
			background: #1a1a1a;
			color: #ffffff;
			border: none;
			border-radius: 10px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.pro-buy-now:hover {
			background: #333;
			transform: translateY(-2px);
		}

		@media (max-width: 576px) {
			.pro-cart-actions {
				flex-direction: column;
			}
			.pro-quantity {
				width: 100%;
			}
			.pro-qty-input {
				flex: 1;
			}
			.pro-add-cart,
			.pro-buy-now {
				width: 100%;
			}
		}

		/* Secondary Actions */
		.pro-secondary-actions {
			display: flex;
			gap: 15px;
			margin-bottom: 25px;
		}

		.pro-wishlist,
		.pro-compare {
			padding: 12px 20px;
			background: #ffffff;
			border: 2px solid #e5e7eb;
			border-radius: 8px;
			font-size: 14px;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.pro-wishlist:hover,
		.pro-compare:hover {
			border-color: #667eea;
			color: #667eea;
		}

		/* Trust Badges */
		.pro-trust-badges {
			display: flex;
			gap: 20px;
			padding-top: 20px;
			border-top: 1px solid #e5e7eb;
		}

		.pro-trust-item {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #6b7280;
			font-size: 14px;
		}

		.pro-trust-item i {
			color: #10b981;
			font-size: 18px;
		}

		@media (max-width: 576px) {
			.pro-trust-badges {
				flex-direction: column;
				gap: 12px;
			}
		}
		';
	}

	private function getTabsStyles() {
		return '
		/* Tabs Section */
		.pro-tabs-section {
			max-width: 1240px;
			margin: 60px auto 0;
			padding: 0 20px;
		}

		.pro-tabs {
			display: flex;
			gap: 5px;
			border-bottom: 2px solid #e5e7eb;
			margin-bottom: 30px;
			overflow-x: auto;
		}

		.pro-tab-btn {
			padding: 15px 25px;
			background: transparent;
			border: none;
			font-size: 15px;
			font-weight: 500;
			color: #6b7280;
			cursor: pointer;
			transition: all 0.3s ease;
			white-space: nowrap;
			position: relative;
		}

		.pro-tab-btn:hover {
			color: #1a1a1a;
		}

		.pro-tab-btn.active {
			color: #667eea;
			font-weight: 600;
		}

		.pro-tab-btn.active::after {
			content: "";
			position: absolute;
			bottom: -2px;
			left: 0;
			right: 0;
			height: 2px;
			background: #667eea;
		}

		.pro-tab-content {
			display: none;
			padding: 20px 0;
		}

		.pro-tab-content.active {
			display: block;
		}

		.pro-tab-content h3 {
			font-size: 22px;
			font-weight: 600;
			color: #1a1a1a;
			margin-bottom: 15px;
		}

		.pro-tab-content p {
			color: #6b7280;
			line-height: 1.7;
			margin-bottom: 15px;
		}

		/* Features List */
		.pro-features-list {
			list-style: none;
			padding: 0;
			margin: 20px 0;
		}

		.pro-features-list li {
			padding: 12px 0;
			padding-left: 30px;
			position: relative;
			color: #4b5563;
		}

		.pro-features-list li::before {
			content: "✓";
			position: absolute;
			left: 0;
			color: #10b981;
			font-weight: 700;
		}

		/* Specs Table */
		.pro-specs-table {
			width: 100%;
			border-collapse: collapse;
			margin: 20px 0;
		}

		.pro-specs-table td {
			padding: 15px;
			border-bottom: 1px solid #e5e7eb;
		}

		.pro-specs-table td:first-child {
			font-weight: 600;
			color: #1a1a1a;
			width: 40%;
		}

		.pro-specs-table td:last-child {
			color: #6b7280;
		}

		/* Review Summary */
		.pro-review-summary {
			display: flex;
			align-items: center;
			gap: 40px;
			margin: 20px 0;
		}

		.pro-average-rating {
			font-size: 64px;
			font-weight: 700;
			color: #1a1a1a;
		}

		.pro-rating-bars {
			flex: 1;
		}

		.pro-rating-bar {
			display: flex;
			align-items: center;
			gap: 15px;
			margin-bottom: 10px;
		}

		.pro-rating-bar span {
			width: 70px;
			font-size: 14px;
			color: #6b7280;
		}

		.pro-rating-bar .pro-bar-fill {
			flex: 1;
			height: 8px;
			background: linear-gradient(90deg, #fbbf24 0%, #fbbf24 var(--fill), #e5e7eb var(--fill));
			border-radius: 4px;
		}

		@media (max-width: 576px) {
			.pro-review-summary {
				flex-direction: column;
				gap: 20px;
			}
			.pro-average-rating {
				font-size: 48px;
			}
		}
		';
	}
}
