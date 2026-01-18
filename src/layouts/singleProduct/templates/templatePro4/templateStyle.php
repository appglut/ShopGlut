<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro4;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		?>
	<style id="shopglut-templatePro4-dynamic-css">';

        .shopglut-single-templatePro4 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .shopglut-single-templatePro4 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .shopglut-single-templatePro4 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            background: white;
            min-height: 100vh;
        }

        .shopglut-single-templatePro4 .product-container {
            display: flex;
            gap: 50px;
            margin-bottom: 60px;
        }

        /* Left Side - Image Gallery */
        .shopglut-single-templatePro4 .image-gallery {
            flex: 0 0 600px;
        }

        .shopglut-single-templatePro4 .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 15px;
            height: 500px;
        }

        .shopglut-single-templatePro4 .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .shopglut-single-templatePro4 .gallery-item:hover {
            transform: scale(1.02);
        }

        .shopglut-single-templatePro4 .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .shopglut-single-templatePro4 .gallery-item {
            grid-column: auto;
            grid-row: auto;
        }

        /* Right Side - Product Details */
        .shopglut-single-templatePro4 .product-details {
            flex: 1;
            padding: 20px 0;
        }

        /* Discount Badge */
        .shopglut-single-templatePro4 .discount-badge {
            display: inline-block;
            background: #2c2c2c;
            color: white;
            padding: 8px 18px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Product Title */
        .shopglut-single-templatePro4 .product-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        /* Reviews */
        .shopglut-single-templatePro4 .reviews-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro4 .stars {
            color: #ffc107;
        }

        .shopglut-single-templatePro4 .review-count {
            color: #666;
            font-size: 14px;
        }

        .shopglut-single-templatePro4 .write-review {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            margin-left: auto;
        }

        /* Description */
        .shopglut-single-templatePro4 .product-description {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        /* Price and Quantity */
        .shopglut-single-templatePro4 .price-quantity-row {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro4 .price-section {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .shopglut-single-templatePro4 .current-price {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }

        .shopglut-single-templatePro4 .original-price {
            font-size: 18px;
            color: #999;
            text-decoration: line-through;
        }

        .shopglut-single-templatePro4 .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .shopglut-single-templatePro4 .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }

        .shopglut-single-templatePro4 .quantity-btn:hover {
            background: #e9ecef;
        }

        .shopglut-single-templatePro4 .quantity-input {
            width: 60px;
            text-align: center;
            border: none;
            height: 40px;
            font-size: 16px;
        }

        /* Action Buttons */
        .shopglut-single-templatePro4 .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .shopglut-single-templatePro4 .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-single-templatePro4 .btn-primary {
            background: #1a1a1a;
            color: white;
            flex: 1;
            border: 1px solid #1a1a1a;
            text-align: center;
            justify-content: center;
        }

        .shopglut-single-templatePro4 .btn-secondary {
            background: white;
            border: 1px solid #e8e8e8;
            color: #4a4a4a;
            flex: 1;
            text-align: center;
            justify-content: center;
        }

        .shopglut-single-templatePro4 .btn-primary:hover {
            background: #2c2c2c;
            border-color: #2c2c2c;
        }

        .shopglut-single-templatePro4 .btn-secondary:hover {
            background: #f5f5f5;
            border-color: #d4d4d4;
        }

        /* Divider */
        .shopglut-single-templatePro4 .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 30px 0;
        }

        /* Product Meta */
        .shopglut-single-templatePro4 .product-meta {
            margin: 35px 0;
            padding: 35px 0;
            border-top: 1px solid #e8e8e8;
        }

        .shopglut-single-templatePro4 .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro4 .meta-item:last-child {
            margin-bottom: 0;
        }

        .shopglut-single-templatePro4 .meta-content {
            flex: 1;
        }

        .shopglut-single-templatePro4 .meta-label {
            color: #8e8e8e;
            font-size: 11px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        .shopglut-single-templatePro4 .meta-value {
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.4;
        }

        .shopglut-single-templatePro4 .tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro4 .tag {
            background: #f5f5f5;
            color: #4a4a4a;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.2px;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid #e8e8e8;
        }

        .shopglut-single-templatePro4 .tag:hover {
            background: #ebebeb;
            border-color: #d4d4d4;
        }

        .shopglut-single-templatePro4 .share-buttons {
            display: flex;
            gap: 8px;
        }

        .shopglut-single-templatePro4 .share-btn {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e8e8e8;
            color: #6b6b6b;
            font-size: 14px;
        }

        .shopglut-single-templatePro4 .share-btn:hover {
            background: #f5f5f5;
            border-color: #d4d4d4;
            color: #1a1a1a;
        }

        /* Features */
        .shopglut-single-templatePro4 .features-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .shopglut-single-templatePro4 .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shopglut-single-templatePro4 .feature-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #667eea;
        }

        .shopglut-single-templatePro4 .feature-text h4 {
            font-size: 14px;
            margin-bottom: 2px;
            color: #333;
        }

        .shopglut-single-templatePro4 .feature-text p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        /* Tabs */
        .shopglut-single-templatePro4 .tabs-container {
            margin-bottom: 60px;
        }

        .shopglut-single-templatePro4 .tabs-header {
            display: flex;
            justify-content: center;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 30px;
        }

        .shopglut-single-templatePro4 .tab-btn {
            padding: 15px 40px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro4 .tab-btn.active {
            color: #1a1a1a;
            font-weight: 500;
        }

        .shopglut-single-templatePro4 .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: #1a1a1a;
        }

        .shopglut-single-templatePro4 .tab-content {
            padding: 30px;
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
        }

        .shopglut-single-templatePro4 .tab-pane {
            display: none;
        }

        .shopglut-single-templatePro4 .tab-pane.active {
            display: block;
        }

        /* Related Products */
        .shopglut-single-templatePro4 .section-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .shopglut-single-templatePro4 .section-tab {
            padding: 10px 30px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .shopglut-single-templatePro4 .section-tab.active {
            background: #1a1a1a;
            color: white;
            border-color: #1a1a1a;
        }

        .shopglut-single-templatePro4 .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .shopglut-single-templatePro4 .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }

        .shopglut-single-templatePro4 .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .shopglut-single-templatePro4 .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .shopglut-single-templatePro4 .product-card-content {
            padding: 20px;
        }

        .shopglut-single-templatePro4 .product-card-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro4 .product-card-price {
            font-size: 18px;
            font-weight: 500;
            color: #1a1a1a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .shopglut-single-templatePro4 .product-container {
                flex-direction: column;
            }

            .shopglut-single-templatePro4 .image-gallery {
                flex: none;
                width: 100%;
            }

            .shopglut-single-templatePro4 .features-section {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro4 .product-meta {
                grid-template-columns: 1fr;
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
