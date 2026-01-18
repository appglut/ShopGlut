<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro8;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		?>
		<style id="shopglut-templatePro8-dynamic-css">
        
        .shopglut-single-templatePro8 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .shopglut-single-templatePro8 .product-page {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 40px;
            margin: 40px 0;
        }

        /* ==================== LEFT SIDE ==================== */
        .shopglut-single-templatePro8 .left-side {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Main Image */
        .shopglut-single-templatePro8 .main-image-wrapper {
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .shopglut-single-templatePro8 .main-image-wrapper img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro8 .main-image-wrapper:hover img {
            transform: scale(1.03);
        }

        /* Image Grid 3x2 */
        .shopglut-single-templatePro8 .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .shopglut-single-templatePro8 .grid-image {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
            background: white;
        }

        .shopglut-single-templatePro8 .grid-image:hover,
        .shopglut-single-templatePro8 .grid-image.active {
            border-color: #0073aa;
            transform: scale(1.05);
        }

        .shopglut-single-templatePro8 .grid-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ==================== RIGHT SIDE ==================== */
        .shopglut-single-templatePro8 .right-side {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Breadcrumb */
        .shopglut-single-templatePro8 .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #666;
        }

        .shopglut-single-templatePro8 .breadcrumb a {
            color: #0073aa;
            text-decoration: none;
            transition: color 0.3s;
        }

        .shopglut-single-templatePro8 .breadcrumb a:hover {
            color: #005a87;
        }

        /* Product Category */
        .shopglut-single-templatePro8 .product-category {
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro8 .product-category a {
            color: #0073aa;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Product Title */
        .shopglut-single-templatePro8 .product-title {
            font-size: 32px;
            font-weight: 600;
            line-height: 1.3;
            color: #222;
        }

        /* Stock & Reviews */
        .shopglut-single-templatePro8 .stock-reviews {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro8 .stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .shopglut-single-templatePro8 .stock-info i {
            color: #28a745;
        }

        .shopglut-single-templatePro8 .stock-info span {
            color: #28a745;
            font-weight: 500;
        }

        .shopglut-single-templatePro8 .reviews {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-single-templatePro8 .stars {
            color: #ffc107;
        }

        .shopglut-single-templatePro8 .reviews-count {
            color: #666;
            font-size: 14px;
        }

        /* Product Description */
        .shopglut-single-templatePro8 .product-description {
            color: #555;
            font-size: 15px;
            line-height: 1.7;
        }

        /* Product Price */
        .shopglut-single-templatePro8 .product-price {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shopglut-single-templatePro8 .current-price {
            font-size: 36px;
            font-weight: 700;
            color: #28a745;
        }

        .shopglut-single-templatePro8 .original-price {
            font-size: 20px;
            color: #999;
            text-decoration: line-through;
        }

        /* Product Variations */
        .shopglut-single-templatePro8 .product-variations {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro8 .variation-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .shopglut-single-templatePro8 .variation-label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .shopglut-single-templatePro8 .swatches {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro8 .swatch {
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            font-size: 14px;
        }

        .shopglut-single-templatePro8 .swatch:hover {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro8 .swatch.selected {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }

        .shopglut-single-templatePro8 .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro8 .color-swatch:hover,
        .shopglut-single-templatePro8 .color-swatch.selected {
            border-color: #0073aa;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #0073aa;
        }

        /* Quantity & Add to Cart */
        .shopglut-single-templatePro8 .quantity-cart {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .shopglut-single-templatePro8 .quantity-input {
            display: flex;
            align-items: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .shopglut-single-templatePro8 .quantity-input button {
            background: #f8f9fa;
            border: none;
            padding: 14px 18px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro8 .quantity-input button:hover {
            background: #0073aa;
            color: white;
        }

        .shopglut-single-templatePro8 .quantity-input input {
            border: none;
            text-align: center;
            width: 60px;
            font-size: 16px;
            font-weight: 500;
            padding: 14px 0;
        }

        .shopglut-single-templatePro8 .add-to-cart-btn {
            background: #0073aa;
            color: white;
            border: none;
            padding: 16px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-single-templatePro8 .add-to-cart-btn:hover {
            background: #005a87;
        }

        /* Buy Now */
        .shopglut-single-templatePro8 .buy-now-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .shopglut-single-templatePro8 .buy-now-btn:hover {
            background: #218838;
        }

        /* Action Buttons */
        .shopglut-single-templatePro8 .action-buttons {
            display: flex;
            gap: 15px;
        }

        .shopglut-single-templatePro8 .action-btn {
            flex: 1;
            padding: 12px;
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #666;
        }

        .shopglut-single-templatePro8 .action-btn:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        /* ==================== FREQUENTLY BOUGHT TOGETHER ==================== */
        .shopglut-single-templatePro8 .frequently-bought {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .shopglut-single-templatePro8 .frequently-bought h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        .shopglut-single-templatePro8 .bundle-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro8 .bundle-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .shopglut-single-templatePro8 .bundle-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .shopglut-single-templatePro8 .bundle-item-info {
            flex: 1;
        }

        .shopglut-single-templatePro8 .bundle-item-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .shopglut-single-templatePro8 .bundle-item-price {
            font-size: 14px;
            color: #28a745;
            font-weight: 600;
        }

        .shopglut-single-templatePro8 .bundle-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .shopglut-single-templatePro8 .total-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .shopglut-single-templatePro8 .total-price {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
        }

        .shopglut-single-templatePro8 .checkout-btn {
            background: linear-gradient(135deg, #0073aa, #005a87);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro8 .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,115,170,0.3);
        }

        /* ==================== PRODUCT TABS ==================== */
        .shopglut-single-templatePro8 .full-width-section {
            grid-column: 1 / -1;
        }

        .shopglut-single-templatePro8 .product-tabs {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin: 40px 0;
        }

        .shopglut-single-templatePro8 .tab-navigation {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            justify-content: center;
        }

        .shopglut-single-templatePro8 .tab-button {
            padding: 18px 35px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            color: #666;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .shopglut-single-templatePro8 .tab-button:hover {
            color: #0073aa;
        }

        .shopglut-single-templatePro8 .tab-button.active {
            color: #0073aa;
            border-bottom-color: #0073aa;
        }

        .shopglut-single-templatePro8 .tab-content {
            padding: 35px;
            display: none;
        }

        .shopglut-single-templatePro8 .tab-content.active {
            display: block;
        }

        .shopglut-single-templatePro8 .tab-content h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .shopglut-single-templatePro8 .tab-content p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
        }

        /* ==================== BANNER SECTION ==================== */
        .shopglut-single-templatePro8 .info-banner {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            margin: 40px 0;
            background: linear-gradient(135deg, #0073aa 0%, #005a87 100%);
            padding: 60px 40px;
            color: white;
            text-align: center;
        }

        .shopglut-single-templatePro8 .info-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></svg>');
            background-size: 100px 100px;
            opacity: 0.5;
        }

        .shopglut-single-templatePro8 .banner-content {
            position: relative;
            z-index: 1;
        }

        .shopglut-single-templatePro8 .info-banner h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro8 .info-banner p {
            font-size: 18px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 25px;
        }

        .shopglut-single-templatePro8 .banner-features {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro8 .banner-feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-single-templatePro8 .banner-feature i {
            font-size: 24px;
        }

        .shopglut-single-templatePro8 .banner-feature span {
            font-size: 16px;
            font-weight: 500;
        }

        /* ==================== RELATED PRODUCTS SLIDER ==================== */
        .shopglut-single-templatePro8 .related-products {
            margin: 40px 0;
        }

        .shopglut-single-templatePro8 .section-title {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }

        .shopglut-single-templatePro8 .slider-container {
            position: relative;
        }

        .shopglut-single-templatePro8 .slider-wrapper {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 20px 5px;
            scrollbar-width: none;
        }

        .shopglut-single-templatePro8 .slider-wrapper::-webkit-scrollbar {
            display: none;
        }

        .shopglut-single-templatePro8 .related-product {
            flex: 0 0 280px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro8 .related-product:hover {
            transform: translateY(-5px);
        }

        .shopglut-single-templatePro8 .related-product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .shopglut-single-templatePro8 .related-product-info {
            padding: 18px;
        }

        .shopglut-single-templatePro8 .related-product-title {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 10px;
            color: #333;
        }

        .shopglut-single-templatePro8 .related-product-price {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 12px;
        }

        .shopglut-single-templatePro8 .quick-add-btn {
            width: 100%;
            padding: 10px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro8 .quick-add-btn:hover {
            background: #005a87;
        }

        .shopglut-single-templatePro8 .slider-nav {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .shopglut-single-templatePro8 .nav-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            border: 2px solid #ddd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 18px;
            color: #333;
        }

        .shopglut-single-templatePro8 .nav-btn:hover {
            background: #0073aa;
            border-color: #0073aa;
            color: white;
        }

        /* Toast Notification */
        .shopglut-single-templatePro8 .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #0073aa;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            z-index: 1000;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }

        .shopglut-single-templatePro8 .toast.show {
            transform: translateX(0);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .shopglut-single-templatePro8 .product-page {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro8 .main-image-wrapper img {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro8 .quantity-cart {
                flex-direction: column;
                align-items: stretch;
            }

            .shopglut-single-templatePro8 .action-buttons {
                flex-direction: column;
            }

            .shopglut-single-templatePro8 .tab-navigation {
                overflow-x: auto;
            }

            .shopglut-single-templatePro8 .related-product {
                flex: 0 0 220px;
            }

            .shopglut-single-templatePro8 .info-banner h2 {
                font-size: 24px;
            }

            .shopglut-single-templatePro8 .banner-features {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .shopglut-single-templatePro8 .stock-reviews {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .shopglut-single-templatePro8 .product-title {
                font-size: 24px;
            }

            .shopglut-single-templatePro8 .current-price {
                font-size: 28px;
            }

            .shopglut-single-templatePro8 .related-product {
                flex: 0 0 200px;
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
