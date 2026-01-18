<?php
namespace Shopglut\layouts\singleProduct\templates\templatePro9;

if (!defined('ABSPATH')) {
	exit;
}

class templateStyle {

	public function dynamicCss($layout_id = 0) {
		?>
		<style id="shopglut-templatePro9-dynamic-css">

        .shopglut-single-templatePro9 .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ==================== THREE COLUMN LAYOUT ==================== */
        .shopglut-single-templatePro9 .product-page {
            display: grid;
            grid-template-columns: 0.9fr 1.3fr 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        /* ==================== LEFT COLUMN - GALLERY ==================== */
        .shopglut-single-templatePro9 .left-gallery {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Main Image with Badge */
        .shopglut-single-templatePro9 .main-image-container {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .shopglut-single-templatePro9 .main-image-container img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s;
        }

        .shopglut-single-templatePro9 .main-image-container:hover img {
            transform: scale(1.05);
        }

        .shopglut-single-templatePro9 .image-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }

        /* Thumbnail Slider */
        .shopglut-single-templatePro9 .thumbnail-slider {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 8px 0;
            scrollbar-width: thin;
        }

        .shopglut-single-templatePro9 .thumbnail-slider::-webkit-scrollbar {
            height: 6px;
        }

        .shopglut-single-templatePro9 .thumbnail-slider::-webkit-scrollbar-thumb {
            background: #0073aa;
            border-radius: 3px;
        }

        .shopglut-single-templatePro9 .thumb-item {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .shopglut-single-templatePro9 .thumb-item:hover,
        .shopglut-single-templatePro9 .thumb-item.active {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro9 .thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ==================== MIDDLE COLUMN - INFO ==================== */
        .shopglut-single-templatePro9 .middle-info {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Reviews */
        .shopglut-single-templatePro9 .reviews-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .stars-large {
            color: #ffc107;
            font-size: 18px;
        }

        .shopglut-single-templatePro9 .review-count {
            color: #666;
            font-size: 14px;
        }

        .shopglut-single-templatePro9 .review-count a {
            color: #0073aa;
            text-decoration: none;
        }

        /* Product Title */
        .shopglut-single-templatePro9 .product-title {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.3;
            color: #1a1a1a;
        }

        /* Product Price */
        .shopglut-single-templatePro9 .product-price {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shopglut-single-templatePro9 .current-price {
            font-size: 36px;
            font-weight: 700;
            color: #28a745;
        }

        .shopglut-single-templatePro9 .original-price {
            font-size: 20px;
            color: #999;
            text-decoration: line-through;
        }

        /* Product Info List */
        .shopglut-single-templatePro9 .product-info-list {
            list-style: none;
        }

        .shopglut-single-templatePro9 .product-info-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: #555;
            font-size: 14px;
        }

        .shopglut-single-templatePro9 .product-info-list li i {
            color: #0073aa;
        }

        /* Shipping Info */
        .shopglut-single-templatePro9 .shipping-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: linear-gradient(135deg, #e8f4f8, #f0f8ff);
            border-radius: 10px;
        }

        .shopglut-single-templatePro9 .shipping-info i {
            font-size: 24px;
            color: #0073aa;
        }

        .shopglut-single-templatePro9 .shipping-info div {
            display: flex;
            flex-direction: column;
        }

        .shopglut-single-templatePro9 .shipping-info strong {
            font-size: 14px;
            color: #333;
        }

        .shopglut-single-templatePro9 .shipping-info span {
            font-size: 12px;
            color: #666;
        }

        /* Divider */
        .shopglut-single-templatePro9 .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
        }

        /* Color Options with Image */
        .shopglut-single-templatePro9 .color-section h4,
        .shopglut-single-templatePro9 .size-section h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
        }

        .shopglut-single-templatePro9 .color-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .shopglut-single-templatePro9 .color-option {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro9 .color-option:hover,
        .shopglut-single-templatePro9 .color-option.selected {
            border-color: #0073aa;
            background: #f8f9fa;
        }

        .shopglut-single-templatePro9 .color-option-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .shopglut-single-templatePro9 .color-option-info {
            flex: 1;
        }

        .shopglut-single-templatePro9 .color-option-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .shopglut-single-templatePro9 .color-option-price {
            font-size: 13px;
            color: #28a745;
            font-weight: 600;
        }

        /* Size Options */
        .shopglut-single-templatePro9 .size-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro9 .size-option {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }

        .shopglut-single-templatePro9 .size-option:hover,
        .shopglut-single-templatePro9 .size-option.selected {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }

        /* Small Banner */
        .shopglut-single-templatePro9 .small-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            color: white;
            text-align: center;
        }

        .shopglut-single-templatePro9 .small-banner h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .shopglut-single-templatePro9 .small-banner p {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Product Meta */
        .shopglut-single-templatePro9 .product-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .meta-item {
            display: flex;
            font-size: 13px;
        }

        .shopglut-single-templatePro9 .meta-item span {
            color: #666;
            min-width: 80px;
        }

        .shopglut-single-templatePro9 .meta-item strong {
            color: #333;
        }

        .shopglut-single-templatePro9 .meta-item a {
            color: #0073aa;
            text-decoration: none;
        }

        /* Social Share */
        .shopglut-single-templatePro9 .social-share-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shopglut-single-templatePro9 .social-share-section span {
            font-size: 14px;
            color: #666;
        }

        .shopglut-single-templatePro9 .social-icons {
            display: flex;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .social-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro9 .social-icon:hover {
            transform: scale(1.1);
        }

        .shopglut-single-templatePro9 .social-icon.facebook { background: #1877f2; }
        .shopglut-single-templatePro9 .social-icon.twitter { background: #1da1f2; }
        .shopglut-single-templatePro9 .social-icon.whatsapp { background: #25d366; }
        .shopglut-single-templatePro9 .social-icon.pinterest { background: #bd081c; }

        /* ==================== RIGHT COLUMN - CHECKOUT ==================== */
        .shopglut-single-templatePro9 .right-checkout {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        /* Total Price */
        .shopglut-single-templatePro9 .total-price-section {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .shopglut-single-templatePro9 .total-price-section span {
            font-size: 14px;
            color: #666;
        }

        .shopglut-single-templatePro9 .total-price-section strong {
            font-size: 42px;
            color: #28a745;
            font-weight: 700;
        }

        /* Stock Info */
        .shopglut-single-templatePro9 .stock-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: #e8f5e9;
            border-radius: 8px;
        }

        .shopglut-single-templatePro9 .stock-info i {
            color: #28a745;
        }

        .shopglut-single-templatePro9 .stock-info span {
            color: #28a745;
            font-weight: 500;
        }

        /* Quantity */
        .shopglut-single-templatePro9 .quantity-section {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shopglut-single-templatePro9 .quantity-input {
            display: flex;
            align-items: center;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .shopglut-single-templatePro9 .quantity-input button {
            background: #f8f9fa;
            border: none;
            padding: 14px 18px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro9 .quantity-input button:hover {
            background: #0073aa;
            color: white;
        }

        .shopglut-single-templatePro9 .quantity-input input {
            border: none;
            text-align: center;
            width: 60px;
            font-size: 18px;
            font-weight: 600;
            padding: 14px 0;
        }

        /* Add to Cart */
        .shopglut-single-templatePro9 .add-to-cart-btn {
            width: 100%;
            background: linear-gradient(135deg, #0073aa, #005a87);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,115,170,0.3);
        }

        /* Buy Now */
        .shopglut-single-templatePro9 .buy-now-btn {
            width: 100%;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .buy-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40,167,69,0.3);
        }

        /* Wishlist & Compare */
        .shopglut-single-templatePro9 .wishlist-compare {
            display: flex;
            gap: 12px;
        }

        .shopglut-single-templatePro9 .action-btn {
            flex: 1;
            padding: 12px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #666;
        }

        .shopglut-single-templatePro9 .action-btn:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        /* Payment Options */
        .shopglut-single-templatePro9 .payment-options {
            text-align: center;
        }

        .shopglut-single-templatePro9 .payment-options span {
            font-size: 12px;
            color: #999;
            display: block;
            margin-bottom: 10px;
        }

        .shopglut-single-templatePro9 .payment-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .shopglut-single-templatePro9 .payment-icon {
            font-size: 32px;
            color: #333;
        }

        /* Contact Info */
        .shopglut-single-templatePro9 .contact-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .shopglut-single-templatePro9 .contact-section h4 {
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
        }

        .shopglut-single-templatePro9 .call-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .shopglut-single-templatePro9 .contact-features {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .shopglut-single-templatePro9 .contact-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .shopglut-single-templatePro9 .contact-feature i {
            color: #0073aa;
            font-size: 20px;
        }

        .shopglut-single-templatePro9 .contact-feature span {
            font-size: 11px;
            color: #666;
        }

        /* ==================== TWO COLUMN SECTION ==================== */
        .shopglut-single-templatePro9 .two-column-section {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        /* Frequently Bought Together */
        .shopglut-single-templatePro9 .frequently-bought {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .shopglut-single-templatePro9 .frequently-bought h3 {
            font-size: 20px;
            margin-bottom: 25px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .shopglut-single-templatePro9 .bundle-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .shopglut-single-templatePro9 .bundle-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .shopglut-single-templatePro9 .bundle-item:hover {
            border-color: #0073aa;
        }

        .shopglut-single-templatePro9 .bundle-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .shopglut-single-templatePro9 .bundle-item-info {
            flex: 1;
        }

        .shopglut-single-templatePro9 .bundle-item-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .shopglut-single-templatePro9 .bundle-item-price {
            font-size: 16px;
            color: #28a745;
            font-weight: 700;
        }

        .shopglut-single-templatePro9 .bundle-checkbox {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        .shopglut-single-templatePro9 .bundle-total-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .shopglut-single-templatePro9 .bundle-total-label {
            font-size: 16px;
            color: #666;
        }

        .shopglut-single-templatePro9 .bundle-total-price {
            font-size: 28px;
            color: #28a745;
            font-weight: 700;
        }

        .shopglut-single-templatePro9 .bundle-actions {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1.2fr;
            gap: 12px;
        }

        .shopglut-single-templatePro9 .bundle-btn {
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shopglut-single-templatePro9 .bundle-add-cart {
            background: #0073aa;
            color: white;
            border: none;
        }

        .shopglut-single-templatePro9 .bundle-add-cart:hover {
            background: #005a87;
        }

        .shopglut-single-templatePro9 .bundle-buy-now {
            background: #28a745;
            color: white;
            border: none;
        }

        .shopglut-single-templatePro9 .bundle-buy-now:hover {
            background: #218838;
        }

        .shopglut-single-templatePro9 .bundle-wishlist {
            background: white;
            border: 2px solid #e0e0e0;
            color: #666;
        }

        .shopglut-single-templatePro9 .bundle-wishlist:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        /* Right Banners */
        .shopglut-single-templatePro9 .right-banners {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .shopglut-single-templatePro9 .banner-vertical {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .shopglut-single-templatePro9 .banner-vertical img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }

        /* ==================== PRODUCT TABS ==================== */
        .shopglut-single-templatePro9 .full-width-section {
            grid-column: 1 / -1;
        }

        .shopglut-single-templatePro9 .product-tabs {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin: 40px 0;
        }

        .shopglut-single-templatePro9 .tab-navigation {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            justify-content: center;
        }

        .shopglut-single-templatePro9 .tab-button {
            padding: 20px 35px;
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

        .shopglut-single-templatePro9 .tab-button:hover {
            color: #0073aa;
        }

        .shopglut-single-templatePro9 .tab-button.active {
            color: #0073aa;
            border-bottom-color: #0073aa;
        }

        .shopglut-single-templatePro9 .tab-content {
            padding: 40px;
            display: none;
        }

        .shopglut-single-templatePro9 .tab-content.active {
            display: block;
        }

        .shopglut-single-templatePro9 .tab-content h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .shopglut-single-templatePro9 .tab-content p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
        }

        /* ==================== RELATED PRODUCTS ==================== */
        .shopglut-single-templatePro9 .related-products {
            margin: 40px 0;
        }

        .shopglut-single-templatePro9 .section-title {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }

        .shopglut-single-templatePro9 .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .shopglut-single-templatePro9 .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .shopglut-single-templatePro9 .product-card:hover {
            transform: translateY(-5px);
        }

        .shopglut-single-templatePro9 .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .shopglut-single-templatePro9 .product-card-info {
            padding: 18px;
        }

        .shopglut-single-templatePro9 .product-card-title {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 10px;
            color: #333;
        }

        .shopglut-single-templatePro9 .product-card-price {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 12px;
        }

        .shopglut-single-templatePro9 .product-card-btn {
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

        .shopglut-single-templatePro9 .product-card-btn:hover {
            background: #005a87;
        }

        /* ==================== RECENTLY VIEWED ==================== */
        .shopglut-single-templatePro9 .recently-viewed {
            margin: 40px 0;
        }

        /* Toast */
        .shopglut-single-templatePro9 .toast {
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

        .shopglut-single-templatePro9 .toast.show {
            transform: translateX(0);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .shopglut-single-templatePro9 .product-page {
                grid-template-columns: 1fr 1fr;
            }

            .shopglut-single-templatePro9 .right-checkout {
                grid-column: 1 / -1;
                position: static;
            }

            .shopglut-single-templatePro9 .two-column-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .shopglut-single-templatePro9 .product-page {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-templatePro9 .bundle-actions {
                grid-template-columns: 1fr;
            }

            .shopglut-single-templatePro9 .tab-navigation {
                overflow-x: auto;
            }

            .shopglut-single-templatePro9 .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .shopglut-single-templatePro9 .product-title {
                font-size: 24px;
            }

            .shopglut-single-templatePro9 .current-price {
                font-size: 28px;
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
