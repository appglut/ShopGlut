<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro1;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		$css = '<style id="shopglut-templatePro1-dynamic-css">';

		// // Pro Template Base Styles
		// $css .= $this->getBaseStyles();
		// $css .= $this->getHeaderBannerStyles();
		// $css .= $this->getProductContainerStyles();
		// $css .= $this->getGalleryStyles();
		// $css .= $this->getProductDetailsStyles();

        $css .= '
        .shopglut-single-templatePro1 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .shopglut-single-templatePro1 .product-page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin: 40px 0;
        }

        /* Left Side - Image Gallery */
        .shopglut-single-templatePro1 .product-gallery {
            position: relative;
        }

        .shopglut-single-templatePro1 .breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .breadcrumb a {
            display: inline-flex;
            align-items: center;
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .shopglut-single-templatePro1 .breadcrumb a:hover {
            color: #0073aa;
            background-color: #f5f5f5;
        }

        .shopglut-single-templatePro1 .breadcrumb a svg {
            margin-right: 8px;
        }

        .shopglut-single-templatePro1 .breadcrumb span {
            color: #999;
            font-size: 16px;
            user-select: none;
        }

        .shopglut-single-templatePro1 .breadcrumb span:last-child {
            color: #666;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .gallery-container {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 25px;
        }

        .shopglut-single-templatePro1 .thumbnail-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .shopglut-single-templatePro1 .thumbnail {
            width: 120px;
            height: 120px;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro1 .thumbnail:hover,
        .shopglut-single-templatePro1 .thumbnail.active {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro1 .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .shopglut-single-templatePro1 .main-image {
            border-radius: 12px;
            overflow: hidden;
            background: transparent;
            line-height: 0;
        }

        .shopglut-single-templatePro1 .main-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s;
            display: block;
            border: none;
            margin: 0;
            border-radius: 12px;
        }

        .shopglut-single-templatePro1 .main-image:hover img {
            transform: scale(1.02);
        }

        /* Right Side - Product Details */
        .shopglut-single-templatePro1 .product-details {
            padding: 20px 20px 20px 0;
        }

        .shopglut-single-templatePro1 .product-category {
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro1 .product-category a {
            color: #0073aa;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.3s;
        }

        .shopglut-single-templatePro1 .product-category a:hover {
            color: #005a87;
        }

        .shopglut-single-templatePro1 .product-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .shopglut-single-templatePro1 .stock-reviews {
            display: flex;
            align-items: center;
            gap:20px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro1 .stock-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .shopglut-single-templatePro1 .in-stock {
            color: #28a745;
        }

        .shopglut-single-templatePro1 .out-of-stock {
            color: #dc3545;
        }

        .shopglut-single-templatePro1 .reviews {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shopglut-single-templatePro1 .stars {
            color: #ffc107;
        }

        .shopglut-single-templatePro1 .stars .far {
            color: #ddd;
        }

        .shopglut-single-templatePro1 .reviews-count {
            color: #666;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .product-description {
            margin-bottom: 25px;
            color: #555;
            font-size: 16px;
            line-height: 1.7;
        }

        .shopglut-single-templatePro1 .product-price {
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro1 .current-price {
            font-size: 32px;
            font-weight: 600;
            color: #28a745;
        }

        .shopglut-single-templatePro1 .original-price {
            font-size: 20px;
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
        }

        /* Product Variations */
        .shopglut-single-templatePro1 .product-variations {
            margin-bottom: 50px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: flex-start;
        }

        .shopglut-single-templatePro1 .variation-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .shopglut-single-templatePro1 .variation-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0;
        }

        .shopglut-single-templatePro1 .swatches {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro1 .swatch {
            padding: 8px 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .shopglut-single-templatePro1 .swatch:hover {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro1 .swatch.selected {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }

        .shopglut-single-templatePro1 .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro1 .color-swatch:hover,
        .shopglut-single-templatePro1 .color-swatch.selected {
            border-color: #0073aa;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #0073aa;
        }

        /* Add to Cart Section */
        .shopglut-single-templatePro1 .add-to-cart-section {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro1 .quantity-input {
            display: flex;
            align-items: center;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .shopglut-single-templatePro1 .quantity-input button {
            background: #f8f9fa;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s;
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shopglut-single-templatePro1 .quantity-input button:hover,
        .shopglut-single-templatePro1 .quantity-input button:focus {
            background-color: #0073aa;
            color: white;
        }

        .shopglut-single-templatePro1 .quantity-input input {
            border: none;
            border-left: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            text-align: left;
            width: 50px;
            font-size: 16px;
            font-weight: 500;
            background: white;
            outline: none;
            padding: 12px 0;
            -moz-appearance: textfield;
        }

        .shopglut-single-templatePro1 .quantity-input input::-webkit-outer-spin-button,
        .shopglut-single-templatePro1 .quantity-input input::-webkit-inner-spin-button {
            -webkit-appearance: auto;
            opacity: 1;
        }

        .shopglut-single-templatePro1 .quantity-input input[type=number]::-webkit-outer-spin-button,
        .shopglut-single-templatePro1 .quantity-input input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: auto;
            opacity: 1;
        }

        .shopglut-single-templatePro1 .quantity-input input[type=number] {
            -moz-appearance: textfield;
        }

        .shopglut-single-templatePro1 .add-to-cart {
            background: #0073aa;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shopglut-single-templatePro1 .add-to-cart:hover {
            background: #005a87;
        }

        .shopglut-single-templatePro1 .buy-now {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro1 .buy-now:hover {
            background: #218838;
        }

        /* Action Buttons */
        .shopglut-single-templatePro1 .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }

        .shopglut-single-templatePro1 .action-button {
            flex: 1;
            padding: 10px;
            background: white;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
        }

        .shopglut-single-templatePro1 .action-button:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        /* Product Tabs */
        .shopglut-single-templatePro1 .product-tabs {
            grid-column: 1 / -1;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .shopglut-single-templatePro1 .tab-navigation {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            justify-content: center;
        }

        .shopglut-single-templatePro1 .tab-button {
            padding: 16px 32px;
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

        .shopglut-single-templatePro1 .tab-button:hover {
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .tab-button.active {
            color: #0073aa;
            border-bottom-color: #0073aa;
        }

        .shopglut-single-templatePro1 .tab-content {
            padding: 30px;
            display: none;
        }

        .shopglut-single-templatePro1 .tab-content.active {
            display: block;
        }

        /* Reviews Section Styles */
        .shopglut-single-templatePro1 .reviews-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .shopglut-single-templatePro1 .review-statistics {
            padding-left: 20px;
            border-left: 1px solid #e0e0e0;
        }

        .shopglut-single-templatePro1 .review-form-container {
            padding-right: 20px;
        }

        /* Average Rating Section */
        .shopglut-single-templatePro1 .average-rating {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .shopglut-single-templatePro1 .rating-number {
            font-size: 48px;
            font-weight: bold;
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .rating-stars {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .shopglut-single-templatePro1 .rating-count {
            font-size: 14px;
            color: #666;
        }

        /* Rating Breakdown */
        .shopglut-single-templatePro1 .rating-breakdown {
            margin-bottom: 30px;
        }

        .shopglut-single-templatePro1 .rating-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .shopglut-single-templatePro1 .star-label {
            font-size: 14px;
            color: #333;
            width: 60px;
        }

        .shopglut-single-templatePro1 .bar-container {
            flex: 1;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .shopglut-single-templatePro1 .bar-fill {
            height: 100%;
            background: #0073aa;
            transition: width 0.3s ease;
        }

        .shopglut-single-templatePro1 .bar-count {
            font-size: 14px;
            color: #666;
            width: 40px;
            text-align: right;
        }

        /* Customer Reviews List */
        .shopglut-single-templatePro1 .customer-reviews {
            margin-top: 30px;
        }

        .shopglut-single-templatePro1 .customer-reviews h4 {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .shopglut-single-templatePro1 .review-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .shopglut-single-templatePro1 .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro1 .review-comment {
            color: #555;
            line-height: 1.6;
        }

        /* Review Form */
        .shopglut-single-templatePro1 .review-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .shopglut-single-templatePro1 .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .shopglut-single-templatePro1 .form-group label {
            font-weight: 500;
            color: #333;
        }

        .shopglut-single-templatePro1 .form-group input,
        .shopglut-single-templatePro1 .form-group textarea {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .shopglut-single-templatePro1 .form-group input:focus,
        .shopglut-single-templatePro1 .form-group textarea:focus {
            outline: none;
            border-color: #0073aa;
        }

        .shopglut-single-templatePro1 .star-rating-input {
            display: flex;
            gap: 5px;
        }

        .shopglut-single-templatePro1 .star-rating-input i {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.3s;
        }

        .shopglut-single-templatePro1 .star-rating-input i:hover,
        .shopglut-single-templatePro1 .star-rating-input i.active {
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .submit-review-btn {
            padding: 12px 24px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .shopglut-single-templatePro1 .submit-review-btn:hover {
            background: #005a87;
        }

        /* Description Tab Styles */
        .shopglut-single-templatePro1 .description-container {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 40px;
        }

        .shopglut-single-templatePro1 .description-text {
            line-height: 1.7;
        }

        .shopglut-single-templatePro1 .description-text h4 {
            margin: 25px 0 15px 0;
            font-size: 20px;
            color: #333;
        }

        .shopglut-single-templatePro1 .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .shopglut-single-templatePro1 .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro1 .feature-item:hover {
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro1 .feature-item i {
            font-size: 20px;
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .feature-item span {
            font-size: 14px;
            color: #333;
        }

        .shopglut-single-templatePro1 .highlight-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro1 .highlight-box h4 {
            margin-bottom: 15px;
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .box-contents {
            list-style: none;
            padding: 0;
        }

        .shopglut-single-templatePro1 .box-contents li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #555;
        }

        .shopglut-single-templatePro1 .box-contents i {
            color: #28a745;
        }

        .shopglut-single-templatePro1 .warranty-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro1 .warranty-item strong {
            color: #333;
            font-size: 16px;
        }

        .shopglut-single-templatePro1 .warranty-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        /* Specifications Tab Styles */
        .shopglut-single-templatePro1 .specifications-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .shopglut-single-templatePro1 .spec-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
        }

        .shopglut-single-templatePro1 .spec-title {
            font-size: 20px;
            color: #0073aa;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .shopglut-single-templatePro1 .spec-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shopglut-single-templatePro1 .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .shopglut-single-templatePro1 .spec-item:last-child {
            border-bottom: none;
        }

        .shopglut-single-templatePro1 .spec-item label {
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .spec-item span {
            color: #666;
            font-size: 14px;
            text-align: right;
        }

        /* Shipping & Returns Tab Styles */
        .shopglut-single-templatePro1 .shipping-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .shopglut-single-templatePro1 .shipping-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .shopglut-single-templatePro1 .shipping-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .shopglut-single-templatePro1 .shipping-header i {
            font-size: 28px;
            color: #0073aa;
        }

        .shopglut-single-templatePro1 .shipping-header h3 {
            margin: 0;
            color: #333;
        }

        .shopglut-single-templatePro1 .shipping-options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .shopglut-single-templatePro1 .shipping-option {
            display: flex;
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro1 .shipping-option:hover {
            transform: translateY(-2px);
        }

        .shopglut-single-templatePro1 .option-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: #0073aa;
            color: white;
            border-radius: 50%;
        }

        .shopglut-single-templatePro1 .option-icon i {
            font-size: 24px;
        }

        .shopglut-single-templatePro1 .option-details strong {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .shopglut-single-templatePro1 .option-details p {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .option-details .price {
            color: #28a745;
            font-weight: 500;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .option-details .note {
            color: #999;
            font-size: 13px;
        }

        .shopglut-single-templatePro1 .return-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .shopglut-single-templatePro1 .return-feature {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .shopglut-single-templatePro1 .feature-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #0073aa;
            color: white;
            font-size: 28px;
            font-weight: bold;
            border-radius: 50%;
        }

        .shopglut-single-templatePro1 .feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: #28a745;
            color: white;
            border-radius: 50%;
        }

        .shopglut-single-templatePro1 .feature-icon i {
            font-size: 24px;
        }

        .shopglut-single-templatePro1 .feature-text strong {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .shopglut-single-templatePro1 .feature-text p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .shopglut-single-templatePro1 .contact-support {
            margin-top: 30px;
            padding: 20px;
            background: #e8f4f8;
            border-radius: 10px;
            text-align: left;
        }

        .shopglut-single-templatePro1 .contact-support p {
            margin: 0 0 15px 0;
            color: #666;
        }

        .shopglut-single-templatePro1 .support-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .shopglut-single-templatePro1 .support-btn:hover {
            background: #005a87;
        }

        /* Mobile Responsive for All Tabs */
        @media (max-width: 768px) {
            /* Reviews Responsive */
            .shopglut-single-templatePro1 .reviews-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .shopglut-single-templatePro1 .review-statistics {
                padding-left: 0;
                border-left: none;
                border-top: 1px solid #e0e0e0;
                padding-top: 30px;
            }

            .shopglut-single-templatePro1 .review-form-container {
                padding-right: 0;
            }

            .shopglut-single-templatePro1 .average-rating {
                flex-direction: column;
                text-align: left;
                gap: 10px;
            }

            .shopglut-single-templatePro1 .rating-number {
                font-size: 36px;
            }

            /* Description Responsive */
            .shopglut-single-templatePro1 .description-container {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro1 .features-grid {
                grid-template-columns: 1fr;
            }

            /* Specifications Responsive */
            .shopglut-single-templatePro1 .specifications-container {
                grid-template-columns: 1fr;
            }

            /* Shipping Responsive */
            .shopglut-single-templatePro1 .shipping-container {
                grid-template-columns: 1fr;
            }
        }

        /* Related Products */
        .shopglut-single-templatePro1 .related-products {
            grid-column: 1 / -1;
        }

        .shopglut-single-templatePro1 .related-products h2 {
            font-size: 28px;
            margin-bottom: 30px;
            text-align: left;
        }

        .shopglut-single-templatePro1 .related-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .shopglut-single-templatePro1 .related-product {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro1 .related-product:hover {
            transform: translateY(-5px);
        }

        .shopglut-single-templatePro1 .related-product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .shopglut-single-templatePro1 .related-product-info {
            padding: 20px;
        }

        .shopglut-single-templatePro1 .related-product-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro1 .related-product-price {
            font-size: 20px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro1 .quick-add-btn {
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

        .shopglut-single-templatePro1 .quick-add-btn:hover {
            background: #005a87;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .shopglut-single-templatePro1 .product-page {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro1 .main-image img {
                height: 400px;
                object-fit: cover;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro1 .product-title {
                font-size: 24px;
            }

            .shopglut-single-templatePro1 .gallery-container {
                grid-template-columns: 90px 1fr;
                gap: 18px;
            }

            .shopglut-single-templatePro1 .thumbnail {
                width: 90px;
                height: 90px;
            }

            .shopglut-single-templatePro1 .main-image img {
                height: 300px;
                object-fit: cover;
            }

            .shopglut-single-templatePro1 .tab-navigation {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .shopglut-single-templatePro1 .related-products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 576px) {
            .shopglut-single-templatePro1 .stock-reviews {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .shopglut-single-templatePro1 .add-to-cart-section {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro1 .action-buttons {
                flex-direction: column;
            }
        }
    ';
		// $css .= $this->getTabsStyles();

		$css .= '</style>';

		// Allow style tags in the output for preview mode
		$allowed_html = array(
			'style' => array(
				'id' => true,
				'media' => true,
			),
		);
		echo wp_kses($css, $allowed_html); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Inline styles for template preview
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
			text-align: left;
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
			text-align: left;
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
