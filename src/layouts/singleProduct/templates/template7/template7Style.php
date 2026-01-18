<?php
namespace Shopglut\layouts\singleProduct\templates\template7;

class template7Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
             <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f59e0b;
            --border-color: #e5e7eb;
            --danger-color: #ef4444;
            --success-color: #10b981;
            --dark-color: #1f2937;
            --light-bg: #f9fafb;
        }

 .single-product-template7 .product-page {
            padding: 30px 0;
        }

 .single-product-template7 .product-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 40px;
            margin-bottom: 30px;
        }

        /* Left Side - Image Gallery */
 .single-product-template7 .left-gallery {
            padding-right: 40px;
        }

 .single-product-template7 .main-image-container {
            width: 100%;
            height: 500px;
            background-color: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

 .single-product-template7 .main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

 .single-product-template7 .main-image:hover {
            transform: scale(1.05);
        }

 .single-product-template7 .zoom-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template7 .zoom-badge:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Image Carousel */
 .single-product-template7 .image-carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 8px;
        }

 .single-product-template7 .carousel-container {
            display: flex;
            transition: transform 0.3s ease;
            gap: 10px;
        }

 .single-product-template7 .carousel-item {
            flex: 0 0 calc(33.333% - 7px);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template7 .carousel-item:hover,
 .single-product-template7 .carousel-item.active {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

 .single-product-template7 .carousel-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

 .single-product-template7 .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-size: 14px;
            transition: all 0.3s ease;
        }

 .single-product-template7 .carousel-nav:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

 .single-product-template7 .carousel-prev {
            left: 5px;
        }

 .single-product-template7 .carousel-next {
            right: 5px;
        }

        /* Right Side - Product Details */
 .single-product-template7 .product-details {
            padding-left: 40px;
        }

        /* Rating Section */
 .single-product-template7 .rating-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

 .single-product-template7 .stars {
            color: var(--secondary-color);
            font-size: 20px;
        }

 .single-product-template7 .review-count {
            font-size: 16px;
            color: #6b7280;
        }

        /* Product Title */
 .single-product-template7 .product-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            color: var(--dark-color);
        }

        /* Price Section */
 .single-product-template7 .price-section {
            margin-bottom: 20px;
        }

 .single-product-template7 .current-price {
            font-size: 36px;
            font-weight: 700;
            color: var(--danger-color);
        }

 .single-product-template7 .original-price {
            font-size: 22px;
            color: #9ca3af;
            text-decoration: line-through;
            margin-left: 10px;
        }

        /* Stock Information */
 .single-product-template7 .stock-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        /* Short Description */
 .single-product-template7 .short-description {
            margin-bottom: 25px;
            color: #6b7280;
            line-height: 1.6;
            font-size: 15px;
        }

 .single-product-template7 .stock-status {
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

 .single-product-template7 .left-in-stock {
            color: #6b7280;
            font-size: 14px;
        }

        /* Product Variations */
 .single-product-template7 .product-variations {
            margin-bottom: 30px;
        }

 .single-product-template7 .variation-group {
            margin-bottom: 20px;
        }

 .single-product-template7 .variation-label {
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            color: #6b7280;
        }

 .single-product-template7 .variation-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

 .single-product-template7 .variation-option {
            padding: 10px 20px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            font-weight: 500;
        }

 .single-product-template7 .variation-option:hover {
            border-color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.05);
        }

 .single-product-template7 .variation-option.selected {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Color Swatches */
 .single-product-template7 .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

 .single-product-template7 .color-swatch:hover {
            transform: scale(1.1);
        }

 .single-product-template7 .color-swatch.selected {
            border-color: var(--primary-color);
        }

 .single-product-template7 .color-swatch.selected::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            text-shadow: 0 0 3px rgba(0,0,0,0.5);
        }

        /* Quantity Section */
 .single-product-template7 .quantity-section {
            margin-bottom: 25px;
        }

 .single-product-template7 .quantity-label {
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            color: #6b7280;
        }

 .single-product-template7 .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            width: 140px;
        }

 .single-product-template7 .quantity-selector button {
            background: none;
            border: none;
            padding: 12px 15px;
            cursor: pointer;
            color: var(--dark-color);
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

 .single-product-template7 .quantity-selector button:hover {
            background-color: var(--light-bg);
        }

 .single-product-template7 .quantity-selector input {
            border: none;
            text-align: center;
            width: 50px;
            padding: 12px 0;
            font-weight: 600;
            font-size: 16px;
        }

        /* Action Buttons */
 .single-product-template7 .quantity-action-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

 .single-product-template7 .quantity-section-inline {
            margin-bottom: 0;
        }

 .single-product-template7 .btn-add-to-cart {
            flex: 1;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

 .single-product-template7 .btn-add-to-cart:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

 .single-product-template7 .btn-buy-now {
            width: 100%;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            margin-bottom: 25px;
        }

 .single-product-template7 .btn-buy-now:hover {
            background-color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
        }

        /* Wishlist and Compare */
 .single-product-template7 .wishlist-compare {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

 .single-product-template7 .btn-wishlist,
 .single-product-template7 .btn-compare {
            flex: 1;
            background-color: transparent;
            color: var(--dark-color);
            border: 2px solid var(--border-color);
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

 .single-product-template7 .btn-wishlist:hover,
 .single-product-template7 .btn-compare:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Product Information Grid */
 .single-product-template7 .product-info {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0;
            margin-bottom: 25px;
            overflow: hidden;
        }

 .single-product-template7 .info-grid {
            display: flex;
            flex-wrap: wrap;
            padding: 12px;
            gap: 10px;
            border-bottom: 1px solid var(--border-color);
        }

 .single-product-template7 .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 110px;
            padding: 8px;
            background-color: #fafbfc;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

 .single-product-template7 .info-item:hover {
            background-color: #f0f4ff;
            transform: translateY(-1px);
        }

 .single-product-template7 .info-title-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }

 .single-product-template7 .info-item i {
            color: var(--primary-color);
            font-size: 14px;
            min-width: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

 .single-product-template7 .info-content h6 {
            font-size: 10px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
        }

 .single-product-template7 .info-content p {
            font-size: 13px;
            margin: 0;
            color: var(--dark-color);
            font-weight: 500;
            line-height: 1.3;
        }

 .single-product-template7 .info-item > p {
            font-size: 13px;
        }

        /* Social Share */
 .single-product-template7 .social-share-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
            padding: 15px 0;
        }

 .single-product-template7 .social-share-section h6 {
            font-size: 11px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
        }

 .single-product-template7 .social-icons {
            display: flex;
            gap: 8px;
        }

 .single-product-template7 .social-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-color);
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

 .single-product-template7 .social-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

 .single-product-template7 .social-icon.facebook:hover {
            background-color: #1877f2;
            border-color: #1877f2;
            color: white;
        }

 .single-product-template7 .social-icon.twitter:hover {
            background-color: #000000;
            border-color: #000000;
            color: white;
        }

 .single-product-template7 .social-icon.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            border-color: transparent;
            color: white;
        }

 .single-product-template7 .social-icon.pinterest:hover {
            background-color: #bd081c;
            border-color: #bd081c;
            color: white;
        }

        /* Payment Options */
 .single-product-template7 .payment-options {
            padding: 12px 15px;
            background-color: #fafbfc;
        }

 .single-product-template7 .payment-options h6 {
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
        }

 .single-product-template7 .payment-methods {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

 .single-product-template7 .payment-method {
            padding: 6px 10px;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            color: var(--dark-color);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

 .single-product-template7 .payment-method:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.15);
        }

        /* Tabs Section */
 .single-product-template7 .tabs-section {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 40px;
        }

 .single-product-template7 .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            background-color: var(--light-bg);
            padding: 0 30px;
        }

 .single-product-template7 .nav-tabs .nav-link {
            border: none;
            color: #6b7280;
            font-weight: 500;
            padding: 18px 25px;
            margin-right: 5px;
            border-radius: 0;
            transition: all 0.3s ease;
            position: relative;
        }

 .single-product-template7 .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background-color: white;
        }

 .single-product-template7 .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: white;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: -2px;
        }

 .single-product-template7 .tab-content {
            padding: 40px;
        }

 .single-product-template7 .tab-pane h4 {
            margin-bottom: 20px;
            color: var(--dark-color);
            font-size: 24px;
        }

 .single-product-template7 .tab-pane p {
            margin-bottom: 15px;
            line-height: 1.7;
        }

        /* Related Products */
 .single-product-template7 .related-products {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 40px;
        }

 .single-product-template7 .section-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

 .single-product-template7 .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

 .single-product-template7 .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

 .single-product-template7 .product-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

 .single-product-template7 .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

 .single-product-template7 .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

 .single-product-template7 .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

 .single-product-template7 .product-card:hover .product-image img {
            transform: scale(1.05);
        }

 .single-product-template7 .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--danger-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

 .single-product-template7 .product-info {
            padding: 20px;
        }

 .single-product-template7 .product-category {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 8px;
        }

 .single-product-template7 .product-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

 .single-product-template7 .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

 .single-product-template7 .current-price-small {
            font-size: 18px;
            font-weight: 700;
            color: var(--danger-color);
        }

 .single-product-template7 .original-price-small {
            font-size: 14px;
            color: #9ca3af;
            text-decoration: line-through;
        }

 .single-product-template7 .product-rating-small {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

 .single-product-template7 .product-rating-small .stars {
            color: var(--secondary-color);
        }

 .single-product-template7 .review-count-small {
            color: #9ca3af;
        }

 .single-product-template7 .product-actions {
            display: flex;
            gap: 10px;
        }

 .single-product-template7 .btn-add-to-cart-small {
            flex: 1;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template7 .btn-add-to-cart-small:hover {
            background-color: #1d4ed8;
        }

 .single-product-template7 .btn-wishlist-small {
            background-color: transparent;
            color: var(--dark-color);
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

 .single-product-template7 .btn-wishlist-small:hover {
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        @media (max-width: 992px) {
 .single-product-template7 .left-gallery {
                padding-right: 20px;
            }

 .single-product-template7 .product-details {
                padding-left: 20px;
                border-left: 1px solid var(--border-color);
                margin-left: 20px;
            }

 .single-product-template7 .info-grid {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
 .single-product-template7 .product-container {
                padding: 20px;
            }

 .single-product-template7 .main-image-container {
                height: 350px;
            }

 .single-product-template7 .product-title {
                font-size: 24px;
            }

 .single-product-template7 .current-price {
                font-size: 28px;
            }

 .single-product-template7 .quantity-action-row {
                flex-direction: column;
            }

 .single-product-template7 .quantity-selector {
                width: 100%;
            }

 .single-product-template7 .wishlist-compare {
                flex-direction: column;
            }

 .single-product-template7 .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }
        }

        /* Bootstrap Grid Replacement */
 .single-product-template7 * {
            box-sizing: border-box;
        }

 .single-product-template7 .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

 .single-product-template7 .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

 .single-product-template7 .col-lg-6 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

 .single-product-template7 .col-md-4,
 .single-product-template7 .col-md-8 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

 @media (min-width: 992px) {
 .single-product-template7 .col-lg-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

 @media (min-width: 768px) {
 .single-product-template7 .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
 .single-product-template7 .col-md-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }
        }

        /* Icon Styles - SVG Icons */
 .single-product-template7 .icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            stroke-width: 0;
            stroke: currentColor;
            fill: currentColor;
            vertical-align: middle;
        }

 .single-product-template7 i {
            display: inline-block;
            font-style: normal;
        }

        /* Bootstrap Icons Replacement */
 .single-product-template7 .bi-star-fill::before {
            content: "★";
        }

 .single-product-template7 .bi-star-half::before {
            content: "★";
            opacity: 0.5;
        }

 .single-product-template7 .bi-star::before {
            content: "☆";
        }

 .single-product-template7 .bi-zoom-in::before {
            content: "🔍";
        }

 .single-product-template7 .bi-chevron-left::before {
            content: "◀";
        }

 .single-product-template7 .bi-chevron-right::before {
            content: "▶";
        }

 .single-product-template7 .bi-check-circle-fill::before {
            content: "✓";
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: var(--success-color);
            color: white;
            text-align: center;
            line-height: 16px;
        }

 .single-product-template7 .bi-cart-plus::before {
            content: "🛒";
        }

 .single-product-template7 .bi-lightning-charge-fill::before {
            content: "⚡";
        }

 .single-product-template7 .bi-heart::before {
            content: "♡";
        }

 .single-product-template7 .bi-arrow-left-right::before {
            content: "⇄";
        }

 .single-product-template7 .bi-truck::before {
            content: "🚚";
        }

 .single-product-template7 .bi-upc-scan::before {
            content: "⚏";
        }

 .single-product-template7 .bi-shield-check::before {
            content: "🛡";
        }

        /* Font Awesome Replacement - Using SVG Icons */
 .single-product-template7 .fab {
            font-family: inherit;
            font-weight: normal;
        }

 .single-product-template7 .fa-facebook-f::before {
            content: "";
            display: inline-block;
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath fill='currentColor' d='M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

 .single-product-template7 .fa-twitter::before {
            content: "";
            display: inline-block;
            width: 16px;
            height: 16px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='currentColor' d='M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

 .single-product-template7 .fa-instagram::before {
            content: "";
            display: inline-block;
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='currentColor' d='M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

 .single-product-template7 .fa-pinterest-p::before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'%3E%3Cpath fill='currentColor' d='M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.4 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.9-23.7-59.2 0-54.7 41.4-107.6 112-107.6 50.1 0 75.5 36.4 75.5 82.4 0 62-31.6 135.4-109.1 135.4-24.4 0-46.7-16.2-50.6-39.6 17.4-59.2 48.7-112.3 48.7-112.3 0 0 0-6.2-.9-15.6-8.4-15.6-25.6-12.8-25.6-4.5 0 9.9 2.1 16.2 5.4 31.5-18.9 42.4-49.2 66.7-49.2 66.7 0 0-29.9-35.4-29.9-82.4 0-64.4 44.4-117.3 110-117.3 55.4 0 84.8 38.9 84.8 86.2 0 50.1-28.3 111.7-61.2 111.7-16.2 0-26.3-14.5-26.3-30.9 0-23.7 18-52.5 18-52.5 0 0 0-18-1.8-29.9-4.5-9-14.5-7.2-14.5-1.8 0 7.2 1.8 14.5 1.8 14.5 0 0-9 37.1-9 54.4 0 32.4 21.5 54.4 48.7 54.4 62.9 0 105.4-74.5 105.4-153.5C384 78.2 304.4 6.5 204 6.5z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* Nav Tabs Styles */
 .single-product-template7 .nav {
            display: flex;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

 .single-product-template7 .nav-item {
            margin-bottom: 0;
        }

 .single-product-template7 .nav-link {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            cursor: pointer;
            background-color: transparent;
            border: none;
        }

 .single-product-template7 .tab-content {
            display: none;
        }

 .single-product-template7 .tab-content.active,
 .single-product-template7 .tab-content.show {
            display: block;
        }

 .single-product-template7 .tab-pane {
            display: none;
        }

 .single-product-template7 .tab-pane.active,
 .single-product-template7 .tab-pane.show {
            display: block;
        }

 .single-product-template7 .fade {
            opacity: 0;
            transition: opacity 0.15s linear;
        }

 .single-product-template7 .fade.show {
            opacity: 1;
        }

 .single-product-template7 .table {
            width: 100%;
            border-collapse: collapse;
        }

 .single-product-template7 .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

 .single-product-template7 .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid var(--border-color);
        }

 .single-product-template7 .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            cursor: pointer;
            background-color: var(--primary-color);
            color: white;
        }

 .single-product-template7 .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

 .single-product-template7 .btn:hover {
            opacity: 0.9;
        }

 .single-product-template7 .d-flex {
            display: flex !important;
        }

 .single-product-template7 .justify-content-between {
            justify-content: space-between !important;
        }

 .single-product-template7 .mb-2,
 .single-product-template7 .mb-4 {
            margin-bottom: 0.5rem !important;
        }

 .single-product-template7 .mb-4 {
            margin-bottom: 1.5rem !important;
        }

 .single-product-template7 .fw-bold {
            font-weight: 700 !important;
        }

 .single-product-template7 .text-muted {
            color: #6c757d !important;
        }

 .single-product-template7 .text-center {
            text-align: center !important;
        }

 .single-product-template7 .display-4 {
            font-size: 3.5rem;
            font-weight: 300;
            line-height: 1.2;
        }

        /* Price and Stock on same line */
 .single-product-template7 .price-stock-row {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

 .single-product-template7 .price-section {
            margin-bottom: 0;
        }

 .single-product-template7 .stock-info {
            margin-bottom: 0;
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
            if (isset($settings['shopg_singleproduct_settings_template7']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template7']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template7']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template7']['cart-page-settings']);
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
