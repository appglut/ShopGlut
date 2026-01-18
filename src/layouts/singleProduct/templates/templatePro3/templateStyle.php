<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro3;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {

		?>
		<style id="shopglut-templatePro1-dynamic-css">


        .shopglut-single-templatePro3 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .shopglut-single-templatePro3 {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .shopglut-single-templatePro3 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Product Section */
        .shopglut-single-templatePro3 .product-section {
            display: flex;
            gap: 40px;
            margin-bottom: 60px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        /* Left Side - Image Gallery */
        .shopglut-single-templatePro3 .product-images {
            flex: 0.6;
            max-width: 600px;
        }

        .shopglut-single-templatePro3 .main-image {
            width: 100%;
            height: 500px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .shopglut-single-templatePro3 .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .shopglut-single-templatePro3 .main-image:hover img {
            transform: scale(1.05);
        }

        .shopglut-single-templatePro3 .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 5px 0;
        }

        .shopglut-single-templatePro3 .thumbnail {
            min-width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro3 .thumbnail:hover,
        .shopglut-single-templatePro3 .thumbnail.active {
            border-color: #4a6cf7;
            transform: translateY(-3px);
        }

        .shopglut-single-templatePro3 .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side - Product Information */
        .shopglut-single-templatePro3 .product-info {
            flex: 0.4;
            padding-left: 20px;
        }

        .shopglut-single-templatePro3 .product-category {
            color: #6c757d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro3 .product-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1a1a1a;
            line-height: 1.3;
        }

        .shopglut-single-templatePro3 .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro3 .stars {
            color: #ffc107;
            font-size: 16px;
        }

        .shopglut-single-templatePro3 .rating-count {
            color: #6c757d;
            font-size: 14px;
        }

        .shopglut-single-templatePro3 .product-price {
            display: flex;
            align-items: baseline;
            gap: 15px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro3 .current-price {
            font-size: 32px;
            font-weight: 700;
            color: #4a6cf7;
        }

        .shopglut-single-templatePro3 .original-price {
            font-size: 20px;
            color: #6c757d;
            text-decoration: line-through;
        }

        .shopglut-single-templatePro3 .product-description {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 15px;
            line-height: 1.7;
        }

        /* Product Options */
        .shopglut-single-templatePro3 .product-options {
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro3 .option-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .shopglut-single-templatePro3 .option-label {
            font-weight: 600;
            margin-right: 10px;
            min-width: 50px;
        }

        .shopglut-single-templatePro3 .size-options,
        .shopglut-single-templatePro3 .color-options {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro3 .size-btn,
        .shopglut-single-templatePro3 .color-btn {
            padding: 8px 16px;
            border: 2px solid #e0e0e0;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro3 .size-btn:hover,
        .shopglut-single-templatePro3 .color-btn:hover {
            border-color: #4a6cf7;
        }

        .shopglut-single-templatePro3 .size-btn.active,
        .shopglut-single-templatePro3 .color-btn.active {
            background: #4a6cf7;
            color: white;
            border-color: #4a6cf7;
        }

        .shopglut-single-templatePro3 .color-btn {
            width: 30px;
            height: 30px;
            padding: 0;
            position: relative;
        }

        .shopglut-single-templatePro3 .color-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.2);
        }

        .shopglut-single-templatePro3 .color-btn.black::after {
            background: #000;
        }

        .shopglut-single-templatePro3 .color-btn.blue::after {
            background: #4a6cf7;
        }

        .shopglut-single-templatePro3 .color-btn.red::after {
            background: #dc3545;
        }

        .shopglut-single-templatePro3 .color-btn.green::after {
            background: #28a745;
        }

        /* Quantity and Add to Cart */
        .shopglut-single-templatePro3 .quantity-cart-section {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro3 .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .shopglut-single-templatePro3 .quantity-btn {
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s ease;
        }

        .shopglut-single-templatePro3 .quantity-btn:hover {
            background: #e9ecef;
        }

        .shopglut-single-templatePro3 .quantity-input {
            width: 50px;
            height: 40px;
            text-align: center;
            border: none;
            font-size: 16px;
            font-weight: 600;
        }

        .shopglut-single-templatePro3 .add-to-cart-btn {
            padding: 12px 30px;
            background: #4a6cf7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro3 .add-to-cart-btn:hover {
            background: #3a5bd9;
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro3 .stock-info {
            color: #28a745;
            font-weight: 600;
            margin-left: auto;
        }

        /* Wishlist and Compare */
        .shopglut-single-templatePro3 .wishlist-compare {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro3 .wishlist-btn,
        .shopglut-single-templatePro3 .compare-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .shopglut-single-templatePro3 .wishlist-btn:hover,
        .shopglut-single-templatePro3 .compare-btn:hover {
            border-color: #4a6cf7;
            color: #4a6cf7;
        }

        /* SKU and Categories */
        .shopglut-single-templatePro3 .product-meta {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .shopglut-single-templatePro3 .meta-item {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro3 .meta-label {
            font-weight: 600;
            color: #1a1a1a;
        }

        .shopglut-single-templatePro3 .meta-value {
            color: #6c757d;
        }

        /* Social Share */
        .shopglut-single-templatePro3 .social-share {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 25px 0;
        }

        .shopglut-single-templatePro3 .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .shopglut-single-templatePro3 .social-btn:hover {
            transform: scale(1.1);
        }

        .shopglut-single-templatePro3 .social-btn.facebook { background: #1877f2; }
        .shopglut-single-templatePro3 .social-btn.twitter { background: #1da1f2; }
        .shopglut-single-templatePro3 .social-btn.instagram { background: #e1306c; }
        .shopglut-single-templatePro3 .social-btn.whatsapp { background: #25d366; }

        /* Tabs Section */
        .shopglut-single-templatePro3 .tabs-section {
            display: flex;
            gap: 30px;
            margin-bottom: 60px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .shopglut-single-templatePro3 .product-tabs {
            flex: 0.7;
        }

        .shopglut-single-templatePro3 .tab-nav {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro3 .tab-btn {
            padding: 12px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
            position: relative;
        }

        .shopglut-single-templatePro3 .tab-btn.active {
            color: #4a6cf7;
        }

        .shopglut-single-templatePro3 .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: #4a6cf7;
        }

        .shopglut-single-templatePro3 .tab-content {
            min-height: 300px;
        }

        .shopglut-single-templatePro3 .tab-pane {
            display: none;
        }

        .shopglut-single-templatePro3 .tab-pane.active {
            display: block;
        }

        /* Featured Product */
        .shopglut-single-templatePro3 .featured-product {
            flex: 0.2;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .shopglut-single-templatePro3 .featured-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .shopglut-single-templatePro3 .featured-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .shopglut-single-templatePro3 .featured-item:hover {
            transform: translateY(-3px);
        }

        .shopglut-single-templatePro3 .featured-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .shopglut-single-templatePro3 .featured-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 15px;
        }

        .shopglut-single-templatePro3 .featured-product-title {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .shopglut-single-templatePro3 .featured-price {
            color: #ffc107;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro3 .buy-now-btn {
            padding: 8px 16px;
            background: #4a6cf7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .shopglut-single-templatePro3 .buy-now-btn:hover {
            background: #3a5bd9;
        }

        /* Related Products Section */
        .shopglut-single-templatePro3 .related-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .shopglut-single-templatePro3 .section-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: #1a1a1a;
        }

        .shopglut-single-templatePro3 .related-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .shopglut-single-templatePro3 .related-tab-btn {
            padding: 12px 30px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro3 .related-tab-btn:first-child {
            border-radius: 6px 0 0 6px;
        }

        .shopglut-single-templatePro3 .related-tab-btn:last-child {
            border-radius: 0 6px 6px 0;
        }

        .shopglut-single-templatePro3 .related-tab-btn.active {
            background: #4a6cf7;
            color: white;
        }

        .shopglut-single-templatePro3 .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .shopglut-single-templatePro3 .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .shopglut-single-templatePro3 .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .shopglut-single-templatePro3 .product-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .shopglut-single-templatePro3 .product-card-body {
            padding: 20px;
        }

        .shopglut-single-templatePro3 .product-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .shopglut-single-templatePro3 .product-card-price {
            font-size: 20px;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro3 .add-card-btn {
            width: 100%;
            padding: 10px;
            background: #4a6cf7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .shopglut-single-templatePro3 .add-card-btn:hover {
            background: #3a5bd9;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .shopglut-single-templatePro3 .product-section {
                flex-direction: column;
            }

            .shopglut-single-templatePro3 .tabs-section {
                flex-direction: column;
            }

            .shopglut-single-templatePro3 .product-tabs {
                flex: 1;
            }

            .shopglut-single-templatePro3 .featured-product {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro3 .container {
                padding: 10px;
            }

            .shopglut-single-templatePro3 .product-section,
            .shopglut-single-templatePro3 .tabs-section,
            .shopglut-single-templatePro3 .related-section {
                padding: 20px;
            }

            .shopglut-single-templatePro3 .product-title {
                font-size: 24px;
            }

            .shopglut-single-templatePro3 .current-price {
                font-size: 24px;
            }

            .shopglut-single-templatePro3 .quantity-cart-section {
                flex-wrap: wrap;
            }

            .shopglut-single-templatePro3 .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
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
