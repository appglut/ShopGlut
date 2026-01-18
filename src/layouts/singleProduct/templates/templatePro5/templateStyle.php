<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro5;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		?>
		<style id="shopglut-templatePro5-dynamic-css">
	
        .shopglut-single-templatePro5 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .shopglut-single-templatePro5 .breadcrumb-section {
            background-color: white;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .shopglut-single-templatePro5 .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .shopglut-single-templatePro5 .breadcrumb-item a {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .shopglut-single-templatePro5 .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .shopglut-single-templatePro5 .product-page {
            padding: 30px 0;
        }

        .shopglut-single-templatePro5 .product-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            width: 100%;
            max-width: 100%;
        }

        /* Left Section Styles */
        .shopglut-single-templatePro5 .left-section h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .shopglut-single-templatePro5 .rating-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .stars {
            color: #f59e0b;
        }

        .shopglut-single-templatePro5 .rating-value {
            font-weight: 600;
            color: #1f2937;
        }

        .shopglut-single-templatePro5 .review-count {
            color: #6b7280;
            font-size: 14px;
        }

        .shopglut-single-templatePro5 .price-section {
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .current-price {
            font-size: 32px;
            font-weight: 700;
            color: #ef4444;
            margin-right: 10px;
        }

        .shopglut-single-templatePro5 .original-price {
            font-size: 20px;
            color: #9ca3af;
            text-decoration: line-through;
            margin-right: 10px;
        }

        .shopglut-single-templatePro5 .discount-badge {
            background-color: #ef4444;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
        }

        .shopglut-single-templatePro5 .stock-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background-color: #ecfdf5;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .stock-info.in-stock {
            background-color: #ecfdf5;
            color: #10b981;
        }

        .shopglut-single-templatePro5 .stock-info.low-stock {
            background-color: #fef3c7;
            color: #d97706;
        }

        .shopglut-single-templatePro5 .availability-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .availability-info i {
            color: #10b981;
        }

        /* Middle Section Styles */
        .shopglut-single-templatePro5 .product-image-slider {
            position: relative;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        .shopglut-single-templatePro5 .product-image-slider img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .shopglut-single-templatePro5 .image-thumbnails {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .shopglut-single-templatePro5 .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro5 .thumbnail.active,
        .shopglut-single-templatePro5 .thumbnail:hover {
            border-color: #2563eb;
        }

        .shopglut-single-templatePro5 .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Section Styles */
        .shopglut-single-templatePro5 .product-meta {
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .meta-item {
            margin-bottom: 12px;
            font-size: 15px;
        }

        .shopglut-single-templatePro5 .meta-label {
            font-weight: 600;
            color: #1f2937;
            margin-right: 5px;
        }

        .shopglut-single-templatePro5 .meta-value {
            color: #6b7280;
        }

        .shopglut-single-templatePro5 .meta-value a {
            color: #2563eb;
            text-decoration: none;
        }

        .shopglut-single-templatePro5 .meta-value a:hover {
            text-decoration: underline;
        }

        .shopglut-single-templatePro5 .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .shopglut-single-templatePro5 .quantity-selector button {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            color: #1f2937;
            transition: background-color 0.3s ease;
        }

        .shopglut-single-templatePro5 .quantity-selector button:hover {
            background-color: #f9fafb;
        }

        .shopglut-single-templatePro5 .quantity-selector input {
            border: none;
            text-align: center;
            width: 60px;
            padding: 8px 0;
        }

        .shopglut-single-templatePro5 .btn-add-to-cart {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-single-templatePro5 .btn-add-to-cart:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro5 .btn-buy-now {
            background-color: #f59e0b;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro5 .btn-buy-now:hover {
            background-color: #d97706;
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro5 .delivery-return {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro5 .delivery-info,
        .shopglut-single-templatePro5 .return-info {
            flex: 1;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
            font-size: 14px;
        }

        .shopglut-single-templatePro5 .delivery-info h5,
        .shopglut-single-templatePro5 .return-info h5 {
            margin-bottom: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-single-templatePro5 .btn-ask-question {
            background-color: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro5 .btn-ask-question:hover {
            background-color: #2563eb;
            color: white;
        }

        .shopglut-single-templatePro5 .wishlist-compare {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro5 .btn-wishlist,
        .shopglut-single-templatePro5 .btn-compare {
            flex: 1;
            background-color: transparent;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shopglut-single-templatePro5 .btn-wishlist:hover,
        .shopglut-single-templatePro5 .btn-compare:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        .shopglut-single-templatePro5 .payment-share-section {
            background-color: white;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .shopglut-single-templatePro5 .payment-options,
        .shopglut-single-templatePro5 .share-options {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shopglut-single-templatePro5 .payment-options h5,
        .shopglut-single-templatePro5 .share-options h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
        }

        .shopglut-single-templatePro5 .payment-methods {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro5 .payment-method {
            width: 50px;
            height: 32px;
            background-color: #f9fafb;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
        }

        .shopglut-single-templatePro5 .social-share {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro5 .social-icon {
            width: 36px;
            height: 36px;
            background-color: #f9fafb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .shopglut-single-templatePro5 .social-icon:hover {
            color: white;
        }

        .shopglut-single-templatePro5 .social-icon.facebook:hover {
            background-color: #1877f2;
        }

        .shopglut-single-templatePro5 .social-icon.twitter:hover {
            background-color: #1da1f2;
        }

        .shopglut-single-templatePro5 .social-icon.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .shopglut-single-templatePro5 .social-icon.pinterest:hover {
            background-color: #bd081c;
        }

        .shopglut-single-templatePro5 .tabs-section {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .shopglut-single-templatePro5 .tabs-container {
            display: flex;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro5 .tab-nav {
            width: 25%;
            padding: 30px;
            background-color: #f9fafb;
            border-right: 1px solid #e5e7eb;
        }

        .shopglut-single-templatePro5 .nav-pills .nav-link {
            border-radius: 8px;
            color: #1f2937;
            padding: 12px 15px;
            margin-bottom: 10px;
            font-weight: 500;
            text-align: left;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro5 .nav-pills .nav-link.active {
            background-color: #2563eb;
            color: white;
        }

        .shopglut-single-templatePro5 .nav-pills .nav-link:hover:not(.active) {
            background-color: rgba(37, 99, 235, 0.1);
        }

        .shopglut-single-templatePro5 .tab-content {
            width: 75%;
            padding: 30px;
        }

        .shopglut-single-templatePro5 .tab-pane h4 {
            margin-bottom: 20px;
            color: #1f2937;
        }

        .shopglut-single-templatePro5 .tab-pane p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .shopglut-single-templatePro5 .specifications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .shopglut-single-templatePro5 .specifications-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .shopglut-single-templatePro5 .specifications-table tr:last-child {
            border-bottom: none;
        }

        .shopglut-single-templatePro5 .specifications-table td {
            padding: 12px 15px;
        }

        .shopglut-single-templatePro5 .specifications-table td:first-child {
            font-weight: 600;
            width: 30%;
        }

        .shopglut-single-templatePro5 .review-item {
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .shopglut-single-templatePro5 .review-item:last-child {
            border-bottom: none;
        }

        .shopglut-single-templatePro5 .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro5 .review-author {
            font-weight: 600;
        }

        .shopglut-single-templatePro5 .review-date {
            color: #9ca3af;
            font-size: 14px;
        }

        .shopglut-single-templatePro5 .review-rating {
            color: #f59e0b;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro5 .review-text {
            line-height: 1.6;
        }

        .shopglut-single-templatePro5 .related-products {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .shopglut-single-templatePro5 .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .shopglut-single-templatePro5 .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #2563eb;
        }

        .shopglut-single-templatePro5 .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .shopglut-single-templatePro5 .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro5 .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .shopglut-single-templatePro5 .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .shopglut-single-templatePro5 .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .shopglut-single-templatePro5 .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .shopglut-single-templatePro5 .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ef4444;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .shopglut-single-templatePro5 .product-info {
            padding: 15px;
        }

        .shopglut-single-templatePro5 .product-category {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 5px;
        }

        .shopglut-single-templatePro5 .product-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .shopglut-single-templatePro5 .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro5 .current-price-small {
            font-size: 18px;
            font-weight: 700;
            color: #ef4444;
        }

        .shopglut-single-templatePro5 .original-price-small {
            font-size: 14px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .shopglut-single-templatePro5 .product-rating-small {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .shopglut-single-templatePro5 .product-rating-small .stars {
            color: #f59e0b;
        }

        .shopglut-single-templatePro5 .review-count-small {
            color: #9ca3af;
        }

        .shopglut-single-templatePro5 .product-actions {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro5 .btn-add-to-cart-small {
            flex: 1;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro5 .btn-add-to-cart-small:hover {
            background-color: #1d4ed8;
        }

        .shopglut-single-templatePro5 .btn-wishlist-small {
            background-color: transparent;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shopglut-single-templatePro5 .btn-wishlist-small:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        @media (max-width: 992px) {
            .shopglut-single-templatePro5 .tab-nav {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            .shopglut-single-templatePro5 .tab-content {
                width: 100%;
            }

            .shopglut-single-templatePro5 .nav-pills {
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .shopglut-single-templatePro5 .nav-pills .nav-link {
                white-space: nowrap;
                margin-right: 10px;
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro5 .product-container {
                padding: 20px;
            }

            .shopglut-single-templatePro5 .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
            }

            .shopglut-single-templatePro5 .delivery-return {
                flex-direction: column;
            }

            .shopglut-single-templatePro5 .payment-share-section {
                padding: 15px 0;
            }

            .shopglut-single-templatePro5 .payment-options,
            .shopglut-single-templatePro5 .share-options {
                justify-content: center;
                margin-bottom: 15px;
            }

            .shopglut-single-templatePro5 .payment-options:last-child,
            .shopglut-single-templatePro5 .share-options:last-child {
                margin-bottom: 0;
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
