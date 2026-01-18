<?php
namespace Shopglut\layouts\singleProduct\templates\template1;

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* Base Template Styles */

        /* Image Loader Fix - Hide by default for demo */
        .shopglut-single-product.template1 .image-loading-placeholder {
            display: none !important;
        }

        .shopglut-single-product.template1 .loading-spinner {
            display: none !important;
        }

        .shopglut-single-product.template1 .main-image-container {
            position: relative;
        }

        /* Only show loader when explicitly loading */
        .shopglut-single-product.template1 .main-image-container.loading .image-loading-placeholder {
            display: block !important;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        .shopglut-single-product.template1 .main-image-container.loading .loading-spinner {
            display: block !important;
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #8b5cf6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Override inline styles for better demo layout */
        .shopglut-single-product.template1 .product-main-wrapper {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 40px !important;
            align-items: start !important;
        }

        /* Ensure layout integrity with any content */
        .shopglut-single-product.template1 .product-gallery-section {
            overflow: hidden;
            position: relative;
        }

        .shopglut-single-product.template1 .product-info-section {
            overflow: hidden;
            position: relative;
        }

        .single-product-template2 .product-info nav.breadcrumb span {
          font-size:14px;
        }

        /* Prevent content from breaking grid layout */
        .shopglut-single-product.template1 .product-description {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .shopglut-single-product.template1 .product-description * {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Ensure images in description don't break layout */
        .shopglut-single-product.template1 .product-description img {
            max-width: 100%;
            height: auto;
        }

        /* Ensure tables don't overflow */
        .shopglut-single-product.template1 .product-description table {
            max-width: 100%;
            table-layout: fixed;
        }

        .shopglut-single-product.template1 .product-gallery-section {
            width: 100% !important;
        }

        .shopglut-single-product.template1 .product-info-section {
            width: 100% !important;
        }

        .shopglut-single-product.template1 .main-image-container {
            width: 100% !important;
            position: relative !important;
            display: block !important;
        }

        .shopglut-single-product.template1 .main-product-image {
            width: 100% !important;
            height: auto !important;
            box-sizing: border-box !important;
            /* object-fit and border-radius controlled by settings */
        }

        /* Related Products section styling controlled by settings - see CSS generation section below */

        /* Quick add button styling controlled by Related Products settings */

        /* Template2-inspired Container and Layout */
        .shopglut-single-product.template1 .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Main Product Section */
        .shopglut-single-product.template1 .shopglut-single-product-container {
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .product-gallery-section {
            position: relative;
        }

        .shopglut-single-product.template1 .main-image-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease, margin 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 0;
            box-sizing: border-box;
        }

        .shopglut-single-product.template1 .main-image-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 5px;
        }

        .shopglut-single-product.template1 .main-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        .shopglut-single-product.template1 .thumbnail-gallery {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap;
            /* gap, margin-top, justify-content controlled by settings */
        }

        .shopglut-single-product.template1 .thumbnail-item {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            flex: 0 0 auto;
            /* width, height, border, border-radius, opacity controlled by settings */
        }

        .shopglut-single-product.template1 .thumbnail-image {
            width: 100%;
            height: 100%;
            /* object-fit controlled by settings */
        }

        /* Base hover effect - specific styles controlled by settings */
        .shopglut-single-product.template1 .thumbnail-item:hover {
            /* border-color, transform controlled by settings */
        }

        .shopglut-single-product.template1 .thumbnail-item.active {
            /* border-color controlled by settings */
        }

        .shopglut-single-product.template1 .product-info {
            display: block !important;
            align-items: unset !important;
            gap: unset !important;
        }

        /* Override conflicting CSS for product-info divs */
        .shopglut-single-product.template1 .product-info > div {
            display: block !important;
            flex-direction: unset !important;
            gap: unset !important;
        }

        /* Specific flex layouts for elements that should use flex */
        .shopglut-single-product.template1 .product-info .product-badges {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
            margin-bottom: 20px;
        }

        .shopglut-single-product.template1 .product-info .rating-section {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            align-items: center !important;
            gap: 15px !important;
            margin-bottom: 24px;
        }

        .shopglut-single-product.template1 .product-info .price-section {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 12px !important;
            margin-bottom: 32px;
        }

        .shopglut-single-product.template1 .product-info .color-options {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 12px !important;
        }

        .shopglut-single-product.template1 .product-info .size-options {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 12px !important;
        }

        .shopglut-single-product.template1 .product-info .quantity-cart {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            align-items: center !important;
            gap: 20px !important;
            margin-bottom: 20px;
        }

        .shopglut-single-product.template1 .product-info .secondary-actions {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            gap: 12px !important;
        }

        .shopglut-single-product.template1 .product-info h1 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            line-height: 1.2;
            text-align: left;
        }

        .shopglut-single-product.template1 .product-badges {
            display: flex;
            flex-direction: row !important;
            gap: 10px;
            margin-bottom: 20px;
        }

        .shopglut-single-product.template1 .badge {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            width: auto !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }

        .shopglut-single-product.template1 .badge.new {
            background: #ef4444;
        }

        .shopglut-single-product.template1 .badge.trending {
            background: #f59e0b;
        }

        .shopglut-single-product.template1 .rating-section {
            display: flex !important;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
            flex-direction: row !important;
            justify-content: flex-start !important;
        }

        .shopglut-single-product.template1 .stars-container {
            display: flex !important;
            gap: 2px;
            align-items: center;
        }

        .shopglut-single-product.template1 .star {
            color: #e5e7eb;
            font-size: 1.2rem;
        }

        .shopglut-single-product.template1 .star.filled {
            color: #fbbf24;
        }

        .shopglut-single-product.template1 .rating-text {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Template2-inspired Gradient Price Section */
        .shopglut-single-product.template1 .price-section {
            margin-bottom: 32px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 12px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }



        .shopglut-single-product.template1 .price-section > * {
            position: relative;
            z-index: 1;
        }

        .shopglut-single-product.template1 .current-price {
            margin-right: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .shopglut-single-product.template1 .original-price {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: line-through;
        }

        .shopglut-single-product.template1 .discount-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: auto !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }

        .shopglut-single-product.template1 .description {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 32px;
            line-height: 1.7;
            text-align: left;
        }

        .shopglut-single-product.template1 .product-options {
            margin-bottom: 32px;
        }

        .shopglut-single-product.template1 .option-group {
            margin-bottom: 24px;
        }

        .shopglut-single-product.template1 .option-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: block;
            text-align: left;
            font-size: 1rem;
        }

        .shopglut-single-product.template1 .color-swatches {
            display: flex !important;
            flex-direction: row !important;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .shopglut-single-product.template1 .color-option {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 4px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .shopglut-single-product.template1 .color-option:hover,
        .shopglut-single-product.template1 .color-option.active {
            border-color: #8b5cf6;
            transform: scale(1.1);
        }

        .shopglut-single-product.template1 .color-option.active:after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            text-shadow: 0 0 3px rgba(0,0,0,0.5);
        }

        .shopglut-single-product.template1 .size-buttons {
            display: flex !important;
            flex-direction: row !important;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .shopglut-single-product.template1 .size-option {
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 60px;
            text-align: left;
        }

        .shopglut-single-product.template1 .size-option:hover {
            border-color: #8b5cf6;
            transform: translateY(-2px);
        }

        .shopglut-single-product.template1 .size-option.active {
            border-color: #8b5cf6;
            background: #8b5cf6;
            color: white;
            transform: translateY(-2px);
        }

        .shopglut-single-product.template1 .purchase-section {
            background: #f8fafc;
            padding: 32px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .purchase-container {
            background: #f8fafc;
            padding: 32px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            margin-top: 24px;
        }

        .shopglut-modules-wrapper.position-after_add_to_cart {
            display: flex;
        }

        .shopglut-modules-wrapper.position-after_add_to_cart .shopglut-comparison-button-wrapper{
            margin-top:0px;
        }

        /* Quantity Selector Container */
        .shopglut-single-product.template1 .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        /* Quantity Decrease/Increase Buttons */
        .shopglut-single-product.template1 .qty-decrease,
        .shopglut-single-product.template1 .qty-increase {
            width: 44px;
            height: 44px;
            padding: 0;
            background: #f9fafb;
            border: none;
            color: #374151;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .shopglut-single-product.template1 .qty-decrease:hover,
        .shopglut-single-product.template1 .qty-increase:hover {
            background: #e5e7eb;
        }

        /* Quantity Input */
        .shopglut-single-product.template1 .qty-input {
            width: 50px;
            height: 44px;
            border: none;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            padding: 0 12px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            background: #ffffff;
        }

        /* Remove spinner from number input */
        .shopglut-single-product.template1 .qty-input::-webkit-outer-spin-button,
        .shopglut-single-product.template1 .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .shopglut-single-product.template1 .qty-input[type=number] {
            -moz-appearance: textfield;
        }

        /* Add to Cart Button - Base styles only (color/background controlled by dynamic CSS) */
        .shopglut-single-product.template1 .add-to-cart-btn {
            padding: 14px 32px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .shopglut-single-product.template1 .add-to-cart-btn i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .shopglut-single-product.template1 .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .shopglut-single-product.template1 .add-to-cart-btn:hover i {
            transform: translateX(3px);
        }

        .shopglut-single-product.template1 .secondary-actions {
            display: flex !important;
            gap: 12px;
            flex-direction: row !important;
            justify-content: flex-start !important;
        }

        .shopglut-single-product.template1 .wishlist-btn, .shopglut-single-product.template1 .compare-btn {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            color: #475569;
            padding: 14px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 14px;
            flex: 1;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .wishlist-btn::before, .shopglut-single-product.template1 .compare-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            transition: left 0.5s ease;
        }

        .shopglut-single-product.template1 .wishlist-btn:hover, .shopglut-single-product.template1 .compare-btn:hover {
            border-color: #8b5cf6;
            color: #8b5cf6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2), 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .shopglut-single-product.template1 .wishlist-btn:hover::before, .shopglut-single-product.template1 .compare-btn:hover::before {
            left: 100%;
        }

        .shopglut-single-product.template1 .wishlist-btn i, .shopglut-single-product.template1 .compare-btn i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .shopglut-single-product.template1 .wishlist-btn:hover i, .shopglut-single-product.template1 .compare-btn:hover i {
            transform: scale(1.1);
        }

        .shopglut-single-product.template1 .wishlist-btn.active {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-color: #dc2626;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3), 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .shopglut-single-product.template1 .wishlist-btn.active i {
            animation: heartBeat 0.6s ease-in-out;
        }

        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.2); }
            50% { transform: scale(0.95); }
            75% { transform: scale(1.1); }
        }

        .shopglut-single-product.template1 .compare-btn.added {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #059669;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3), 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Template2-inspired Product Features Section */
        .shopglut-single-product.template1 .features-section {
            margin-top: 60px;
            padding: 60px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .shopglut-single-product.template1 .features-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        .shopglut-single-product.template1 .features-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 2px;
        }

        .shopglut-single-product.template1 .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .shopglut-single-product.template1 .feature-item {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: border-color 0.2s ease;
            text-align: left;
        }

        .shopglut-single-product.template1 .feature-item:hover {
            border-color: #d1d5db;
        }

        .shopglut-single-product.template1 .feature-icon {
            margin: 0 0 16px 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .shopglut-single-product.template1 .feature-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }

        .shopglut-single-product.template1 .feature-description {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* ========== PRODUCT TABS - FRESH CSS ========== */

        /* WooCommerce Tabs Section Container */
        .shopglut-single-product.template1 .woocommerce-tabs-section {
            margin: 60px 0 40px 0;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* Tab Navigation */
        .shopglut-single-product.template1 .wc-tabs {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .wc-tabs li {
            flex: 1;
            margin: 0;
            padding: 0;
            border: none;
            background: none;
        }

        .shopglut-single-product.template1 .wc-tabs li a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 0px;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 15px;
            text-align: center;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            background: transparent;
            width: 100%;
        }

        .shopglut-single-product.template1 .wc-tabs li a i {
            font-size: 16px;
        }

        .shopglut-single-product.template1 .wc-tabs li.active a,
        .shopglut-single-product.template1 .wc-tabs li a:hover {
            color: #8b5cf6;
            background: #ffffff;
            border-bottom-color: #8b5cf6;
        }

        .shopglut-single-product.template1 .wc-tabs li.active a {
            font-weight: 600;
        }

        /* Tab Panels */
        .shopglut-single-product.template1 .woocommerce-Tabs-panel {
            padding: 30px;
            background: #ffffff;
            display: none !important;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel.active,
        .shopglut-single-product.template1 .woocommerce-Tabs-panel.panel.active {
            display: block !important;
        }

        /* Typography - All Left Aligned */
        .shopglut-single-product.template1 .woocommerce-Tabs-panel,
        .shopglut-single-product.template1 .woocommerce-Tabs-panel *,
        .shopglut-single-product.template1 .woocommerce-Tabs-panel > * {
            text-align: left !important;
            /* margin-top:5px;
            padding-top:10px; */
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel .comment-form{
             margin-top:5px;
            padding-top:10px; 
          }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel h2 {
            color: #1e293b;
            font-size: 1.5rem !important;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel h3 {
            color: #1e293b;
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 12px;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel p {
            color: #475569;
            font-size: 15px;
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel ul,
        .shopglut-single-product.template1 .woocommerce-Tabs-panel ol {
            margin: 18px 0;
            padding-left: 26px;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel li {
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 8px;
        }

        .shopglut-single-product.template1 .woocommerce-Tabs-panel ul li::marker {
            color: #8b5cf6;
        }

        /* Additional Information Table */
        .shopglut-single-product.template1 .woocommerce-product-attributes {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: #ffffff;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes tr:last-child {
            border-bottom: none;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes tr:hover {
            background-color: #f8fafc;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes th {
            text-align: left;
            padding: 14px 18px;
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            width: 25%;
            min-width: 150px;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes td {
            padding: 14px 18px;
            color: #475569;
            font-size: 15px;
            line-height: 1.6;
        }

        .shopglut-single-product.template1 .woocommerce-product-attributes td p {
            margin: 0;
        }

        /* ========== Reviews - Professional Style ========== */
        .shopglut-single-product.template1 .woocommerce-Reviews {
            max-width: 100%;
        }

        /* Reviews Header */
        .shopglut-single-product.template1 .reviews-header {
            text-align: center;
            margin-bottom: 1.5em;
            padding-bottom: 1em;
        }

        .shopglut-single-product.template1 .woocommerce-Reviews-title {
            font-size: 2em;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* Comment List */
        .shopglut-single-product.template1 .commentlist {
            list-style: none;
            margin: 0 0 3em;
            padding: 0;
        }

        .shopglut-single-product.template1 .commentlist li {
            margin: 0 0 2em;
            padding: 0;
            border: none;
        }

        .shopglut-single-product.template1 .commentlist li:last-child {
            margin-bottom: 0;
        }

        .shopglut-single-product.template1 .comment_container {
            display: flex;
            gap: 1.25em;
            align-items: flex-start;
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            padding: 1.5em;
            transition: all 0.3s ease;
        }

        .shopglut-single-product.template1 .comment_container:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-color: #d0d0d0;
        }

        /* Review Avatar */
        .shopglut-single-product.template1 .review-avatar {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shopglut-single-product.template1 .avatar-initial {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }

        .shopglut-single-product.template1 .comment-text {
            flex: 1;
            padding: 0;
        }

        /* Review Header */
        .shopglut-single-product.template1 .review-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75em;
            flex-wrap: wrap;
            gap: 0.5em;
        }

        .shopglut-single-product.template1 .review-meta {
            display: flex;
            align-items: center;
            gap: 0.75em;
            flex-wrap: wrap;
        }

        .shopglut-single-product.template1 .woocommerce-review__author {
            font-weight: 600;
            font-size: 16px;
            color: #1a1a1a;
        }

        /* Star Rating - FontAwesome */
        .shopglut-single-product.template1 .review-rating-stars {
            display: flex;
            gap: 3px;
        }

        .shopglut-single-product.template1 .review-rating-stars i {
            font-size: 14px;
        }

        .shopglut-single-product.template1 .review-rating-stars .fas {
            color: #ffc107;
        }

        .shopglut-single-product.template1 .review-rating-stars .far {
            color: #e0e0e0;
        }

        .shopglut-single-product.template1 .woocommerce-review__published-date {
            font-size: 13px;
            color: #888;
        }

        /* Review Description */
        .shopglut-single-product.template1 .description {
            margin: 0 0 1em;
        }

        .shopglut-single-product.template1 .description p {
            margin: 0;
            font-size: 15px;
            line-height: 1.7;
            color: #444;
        }

        /* Review Actions */
        .shopglut-single-product.template1 .review-actions {
            margin-top: 0.75em;
        }

        .shopglut-single-product.template1 .review-verified {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #28a745;
            font-weight: 500;
            background: rgba(40, 167, 69, 0.1);
            padding: 4px 10px;
            border-radius: 20px;
        }

        .shopglut-single-product.template1 .review-verified i {
            font-size: 12px;
        }

        /* Hide old star rating */
        .shopglut-single-product.template1 .star-rating {
            display: none;
        }

        /* Review Form Wrapper */
        .shopglut-single-product.template1 #review_form_wrapper {
            margin-top: 3em !important;
            padding: 2em !important;
            background: #f9f9f9 !important;
            border-radius: 12px !important;
            border: 1px solid #e8e8e8 !important;
        }

        .shopglut-single-product.template1 .comment-reply-title {
            font-size: 1.5em !important;
            font-weight: 700 !important;
            color: #1a1a1a !important;
            margin: 0 0 0.5em 0 !important;
        }

        .shopglut-single-product.template1 .review-form-intro {
            margin-bottom: 0.75em !important;
        }

        .shopglut-single-product.template1 .review-form-intro p {
            margin: 0 !important;
            font-size: 14px !important;
            color: #666 !important;
        }

        /* Form Layout */
        .shopglut-single-product.template1 #review_form_wrapper {
            display: flex !important;
            flex-direction: column !important;
            gap: 1.25em !important;
            margin-top: 10px !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper p {
            margin: 0 !important;
        }

        .shopglut-single-product.template1 .form-row {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5em !important;
            margin-top: 20px !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper label {
            font-weight: 600 !important;
            font-size: 14px !important;
            color: #1a1a1a !important;
            margin: 0 !important;
            display: block !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper .required {
            color: #e74c3c !important;
        }

        /* Star Rating Select */
        .shopglut-single-product.template1 .star-rating-select {
            display: flex !important;
            gap: 5px !important;
        }

        .shopglut-single-product.template1 .star-rating-select i {
            font-size: 24px !important;
            color: #e0e0e0 !important;
            cursor: pointer !important;
            transition: color 0.2s ease !important;
        }

        .shopglut-single-product.template1 .star-rating-select i:hover,
        .shopglut-single-product.template1 .star-rating-select i.active {
            color: #ffc107 !important;
        }

        /* Form Inputs */
        .shopglut-single-product.template1 #review_form_wrapper input[type="text"],
        .shopglut-single-product.template1 #review_form_wrapper input[type="email"],
        .shopglut-single-product.template1 #review_form_wrapper textarea {
            width: 100% !important;
            padding: 12px 16px !important;
            border: 1px solid #d0d0d0 !important;
            border-radius: 8px !important;
            font-size: 15px !important;
            color: #333 !important;
            background: #ffffff !important;
            transition: all 0.3s ease !important;
            font-family: inherit !important;
            box-sizing: border-box !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper input::placeholder,
        .shopglut-single-product.template1 #review_form_wrapper textarea::placeholder {
            color: #aaa !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper input:focus,
        .shopglut-single-product.template1 #review_form_wrapper textarea:focus {
            outline: none !important;
            border-color: #8b5cf6 !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper textarea {
            resize: vertical !important;
            min-height: 120px !important;
            line-height: 1.6 !important;
        }

        /* Submit Button */
        .shopglut-single-product.template1 .form-row-submit {
            margin-top: 0.5em !important;
            text-align: center !important;
            display: block !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper .submit {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
            color: #ffffff !important;
            padding: 12px 28px !important;
            border: none !important;
            border-radius: 8px !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3) !important;
            display: inline-block !important;
            width: auto !important;
            min-width: 150px !important;
            float: none !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper .submit:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4) !important;
        }

        .shopglut-single-product.template1 #review_form_wrapper .submit:active {
            transform: translateY(0) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .shopglut-single-product.template1 .comment_container {
                flex-direction: column;
                gap: 1em;
            }

            .shopglut-single-product.template1 .review-avatar {
                width: 45px;
                height: 45px;
            }

            .shopglut-single-product.template1 .avatar-initial {
                font-size: 13px;
            }

            .shopglut-single-product.template1 .review-header-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .shopglut-single-product.template1 #review_form_wrapper {
                padding: 1.5em;
            }
        }

        /* ========== END PRODUCT TABS CSS ========== */

        /* Description Tab Styles */
        .shopglut-single-product.template1 .description-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin: 28px 0;
        }

        .shopglut-single-product.template1 .feature-item-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            position: relative;
            transition: all 0.3s ease;
        }

        .shopglut-single-product.template1 .feature-item-card:hover {
            border-color: #8b5cf6;
            box-shadow: 0 4px 20px rgba(13, 148, 136, 0.1);
        }

        .shopglut-single-product.template1 .feature-number {
            font-size: 48px;
            font-weight: 700;
            color: #f5f3ff;
            line-height: 1;
            position: absolute;
            top: 12px;
            right: 16px;
            opacity: 0.6;
        }

        .shopglut-single-product.template1 .feature-item-card h4 {
            color: #1f2937;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 8px 0;
            position: relative;
            z-index: 1;
        }

        .shopglut-single-product.template1 .feature-item-card p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .shopglut-single-product.template1 .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .shopglut-single-product.template1 .features-list li {
            color: #475569;
            font-size: 15px;
            line-height: 1.8;
            padding: 0;
            margin: 0 0 10px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .shopglut-single-product.template1 .check-icon {
            color: #10b981;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* Shipping & Delivery Tab Styles */
        .shopglut-single-product.template1 .shipping-options-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .shipping-options-table thead {
            background: #f8fafc;
        }

        .shopglut-single-product.template1 .shipping-options-table th {
            padding: 14px 16px;
            text-align: left;
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .shipping-options-table td {
            padding: 14px 16px;
            color: #475569;
            font-size: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .shipping-options-table tr:last-child td {
            border-bottom: none;
        }

        .shopglut-single-product.template1 .shipping-options-table tr:hover {
            background-color: #f8fafc;
        }

        .shopglut-single-product.template1 .free-shipping {
            display: inline-block;
            background: #10b981;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .shopglut-single-product.template1 .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .shopglut-single-product.template1 .feature-item {
            text-align: left;
            padding: 20px;
        }

        .shopglut-single-product.template1 .feature-icon {
            margin-bottom: 0px !important;
        }

        .shopglut-single-product.template1 .feature-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            margin-top:4px !important;
        }

        .shopglut-single-product.template1 .feature-text {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Related Products */
        .shopglut-single-product.template1 .related-products {
            margin-top: 80px;
            padding-top: 60px;
            border-top: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 40px;
            text-align: left;
            line-height: 1.2;
        }

        .shopglut-single-product.template1 .related-products-title {
            font-size: 2.2rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            margin-bottom: 40px !important;
            text-align: center !important;
            line-height: 1.2 !important;
        }

        /* Demo Variation Wrapper Styling */
        .shopglut-single-product.template1 .demo-variation-wrapper {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .shopglut-single-product.template1 .demo-variation-wrapper:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }

        /* Variation Header */
        .shopglut-single-product.template1 .variation-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .variation-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .shopglut-single-product.template1 .variation-title h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .shopglut-single-product.template1 .variation-title p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        /* Variation Groups */
        .shopglut-single-product.template1 .variation-group {
            margin-bottom: 24px;
        }

        .shopglut-single-product.template1 .variation-label {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 12px;
        }

        .shopglut-single-product.template1 .label-text {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
        }

        .shopglut-single-product.template1 .label-required {
            color: #ef4444;
            font-size: 14px;
            font-weight: 700;
        }

        /* Size Options */
        .shopglut-single-product.template1 .variation-options {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .shopglut-single-product.template1 .size-option {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            min-width: 80px;
            position: relative;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .size-option:hover {
            border-color: #8b5cf6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
        }

        .shopglut-single-product.template1 .size-option.active {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-color: #8b5cf6;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(139, 92, 246, 0.4);
        }

        .shopglut-single-product.template1 .size-option.active:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-color: #8b5cf6;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(139, 92, 246, 0.4);
        }

        .shopglut-single-product.template1 .size-label {
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
        }

        .shopglut-single-product.template1 .size-detail {
            font-size: 11px;
            opacity: 0.8;
            line-height: 1;
        }

        .shopglut-single-product.template1 .size-option.active .size-detail {
            opacity: 0.9;
        }

        /* Color Options */
        .shopglut-single-product.template1 .color-options {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .shopglut-single-product.template1 .color-option {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 90px;
            position: relative;
        }

        .shopglut-single-product.template1 .color-option:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .shopglut-single-product.template1 .color-option.active {
            border-color: #8b5cf6;
            background: #f0f4ff;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .shopglut-single-product.template1 .color-option.active::after {
            content: '✓';
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            background: #8b5cf6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .shopglut-single-product.template1 .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--color);
            border: 2px solid rgba(0, 0, 0, 0.1);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .shopglut-single-product.template1 .color-name {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            text-transform: capitalize;
        }

        /* Variation Info */
        .shopglut-single-product.template1 .variation-info {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 13px;
        }

        .shopglut-single-product.template1 .info-item svg {
            flex-shrink: 0;
            opacity: 0.7;
        }

        .shopglut-single-product.template1 .review-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 0;
            margin-bottom: 20px;
        }

        .shopglut-single-product.template1 .review-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .shopglut-single-product.template1 .review-rating {
            color: #fbbf24;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .shopglut-single-product.template1 .review-author {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .shopglut-single-product.template1 .review-date {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .shopglut-single-product.template1 .review-content {
            color: #475569;
            line-height: 1.6;
        }

        .shopglut-single-product.template1 .review-summary {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid #e2e8f0;
        }

        .shopglut-single-product.template1 .rating-breakdown {
            margin-top: 15px;
        }

        .shopglut-single-product.template1 .rating-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .shopglut-single-product.template1 .rating-bar span {
            color: #64748b;
            font-weight: 500;
        }

        /* Responsive tabs - Tablets */
        @media (max-width: 1024px) {
            .shopglut-single-product.template1 .wc-tabs li a {
                padding: 14px 16px;
                font-size: 14px;
                gap: 6px;
            }

            .shopglut-single-product.template1 .wc-tabs li a i {
                font-size: 14px;
            }
        }

        /* Responsive tabs - Mobile - Stack vertically */
        @media (max-width: 768px) {
            .shopglut-single-product.template1 .wc-tabs {
                flex-direction: column;
                border-bottom: none !important;
                background: transparent !important;
                gap: 0;
            }

            .shopglut-single-product.template1 .wc-tabs li {
                width: 100% !important;
                flex: none !important;
                margin: 0 0 8px 0 !important;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
            }

            .shopglut-single-product.template1 .wc-tabs li:last-child {
                margin-bottom: 0 !important;
            }

            .shopglut-single-product.template1 .wc-tabs li a {
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                padding: 14px 16px !important;
                font-size: 14px !important;
                gap: 10px;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                background: #ffffff !important;
                color: #64748b !important;
                text-decoration: none !important;
                transition: all 0.2s ease;
                line-height: 1.4;
                min-height: 48px;
                height: auto;
                width: 100%;
                box-sizing: border-box;
            }

            /* Hide icons on mobile to save space for text */
            .shopglut-single-product.template1 .wc-tabs li a i {
                display: none !important;
            }

            .shopglut-single-product.template1 .wc-tabs li.active a {
                background: ' . $this->getSetting($settings, 'tab_title_active_background_color', '#ffffff') . ' !important;
                color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ' !important;
                border-color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ' !important;
            }

            .shopglut-single-product.template1 .wc-tabs li a:hover {
                border-color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ';
                color: ' . $this->getSetting($settings, 'tab_title_hover_color', '#667eea') . ';
                background: ' . $this->getSetting($settings, 'tab_title_hover_background_color', '#e5e7eb') . ' !important;
            }

            /* Ensure non-active tabs stay with their background color */
            .shopglut-single-product.template1 .wc-tabs li:not(.active) a {
                background: ' . $this->getSetting($settings, 'tab_title_background_color', '#f3f4f6') . ' !important;
                color: ' . $this->getSetting($settings, 'tab_title_color', '#374151') . ' !important;
                border-color: #e2e8f0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel {
                padding: 20px;
                margin-top:5px;
            }

            /* Responsive table for Additional Information */
            .shopglut-single-product.template1 .woocommerce-product-attributes th,
            .shopglut-single-product.template1 .woocommerce-product-attributes td {
                padding: 12px 16px;
                font-size: 14px;
            }

            .shopglut-single-product.template1 .woocommerce-product-attributes th {
                width: 35%;
                min-width: 120px;
            }

            /* Responsive reviews */
            .shopglut-single-product.template1 .reviews-two-column-layout {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .shopglut-single-product.template1 .reviews-list-column {
                max-height: none;
                overflow-y: visible;
            }

            .shopglut-single-product.template1 .review-form-column {
                position: static;
            }

            .shopglut-single-product.template1 .review-item {
                padding: 18px;
            }

            .shopglut-single-product.template1 .review-rating {
                font-size: 18px;
            }

            /* Shipping tab responsive */
            .shopglut-single-product.template1 .shipping-options-table th,
            .shopglut-single-product.template1 .shipping-options-table td {
                padding: 10px 12px;
                font-size: 13px;
            }

            .shopglut-single-product.template1 .shipping-destinations {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .shopglut-single-product.template1 .shipping-destinations li {
                font-size: 14px;
                padding: 8px;
            }

            /* Description tab responsive */
            .shopglut-single-product.template1 .description-features-grid {
                grid-template-columns: 1fr;
            }

            .shopglut-single-product.template1 .features-list {
                grid-template-columns: 1fr;
            }

            .shopglut-single-product.template1 .whats-included {
                grid-template-columns: repeat(2, 1fr);
            }

            .shopglut-single-product.template1 .quality-assurance {
                padding: 18px;
            }
        }

        @media (max-width: 480px) {
            /* Tabs are already stacked vertically with icons hidden */
            /* Minor size adjustments for very small screens */
            .shopglut-single-product.template1 .wc-tabs li a {
                padding: 12px 14px;
                font-size: 13px;
            }

            /* Two column reviews stack on very small screens */
            .shopglut-single-product.template1 .reviews-two-column-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .shopglut-single-product.template1 .reviews-list-column {
                max-height: none;
                overflow-y: visible;
                padding-right: 0;
            }

            .shopglut-single-product.template1 .review-form-column {
                position: static;
            }

            .shopglut-single-product.template1 #review_form_wrapper {
                padding: 20px;
            }

            /* Description tab responsive for very small screens */
            .shopglut-single-product.template1 .whats-included {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .shopglut-single-product.template1 .feature-highlight {
                padding: 16px;
            }

            /* Shipping tab responsive */
            .shopglut-single-product.template1 .shipping-destinations {
                grid-template-columns: 1fr;
            }

            /* Stack table on very small screens */
            .shopglut-single-product.template1 .woocommerce-product-attributes tr {
                display: block;
                border-bottom: 1px solid #e2e8f0;
                padding: 12px 0;
            }

            .shopglut-single-product.template1 .woocommerce-product-attributes th,
            .shopglut-single-product.template1 .woocommerce-product-attributes td {
                display: block;
                width: 100%;
                padding: 8px 0;
                border-right: none;
            }

            .shopglut-single-product.template1 .woocommerce-product-attributes th {
                background: transparent;
                font-weight: 600;
                color: #1e293b;
                padding-bottom: 4px;
            }

            .shopglut-single-product.template1 .woocommerce-product-attributes td {
                padding-top: 4px;
            }
        }

        .shopglut-single-product.template1 .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

        .shopglut-single-product.template1 .product-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .shopglut-single-product.template1 .product-image {
            width: 100%;
            height: 220px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .shopglut-single-product.template1 .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .shopglut-single-product.template1 .product-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .shopglut-single-product.template1 .product-rating .stars {
            color: #fbbf24;
            font-size: 0.9rem;
        }

        .shopglut-single-product.template1 .product-rating .count {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .shopglut-single-product.template1 .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #059669;
            margin-bottom: 16px;
        }

        .shopglut-single-product.template1 .product-price .original {
            font-size: 1rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 400;
        }

        .shopglut-single-product.template1 .quick-add-btn {
            width: 100%;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            color: #374151;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shopglut-single-product.template1 .quick-add-btn:hover {
            background: #8b5cf6;
            border-color: #8b5cf6;
            color: white;
            transform: translateY(-2px);
        }

        /* Footer */
        .shopglut-single-product.template1 .footer {
            margin-top: 80px;
            background: #1e293b;
            color: #e2e8f0;
            padding: 60px 0 30px;
        }

        .shopglut-single-product.template1 .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }

        .shopglut-single-product.template1 .footer-section h3 {
            margin-bottom: 20px;
            color: white;
        }

        .shopglut-single-product.template1 .footer-section ul {
            list-style: none;
        }

        .shopglut-single-product.template1 .footer-section ul li {
            margin-bottom: 8px;
        }

        .shopglut-single-product.template1 .footer-section ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .shopglut-single-product.template1 .footer-section ul li a:hover {
            color: white;
        }

        .shopglut-single-product.template1 .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 20px;
            text-align: left;
            color: #94a3b8;
        }

        /* Admin Preview Specific Styles */
        .shopglut-single-product.template1 .demo-content {
            width: 100%;
            overflow-x: auto;
        }

        .shopglut-single-product.template1 .demo-content.responsive-preview {
            min-width: 320px;
            max-width: 100%;
        }

        .shopglut-single-product.template1 .single-product-container {
            width: 100%;
            max-width: none;
        }

        .shopglut-single-product.template1 .live-content {
            width: 100%;
        }

        /* Admin Preview Container Adjustments */
        .shopglut-single-product.template1 .demo-content .container {
            max-width: 100%;
            padding: 0 15px;
        }

        /* Responsive Preview Specific Adjustments */
        .shopglut-single-product.template1 .responsive-preview .container {
            min-width: 280px;
        }

        .shopglut-single-product.template1 .responsive-preview .product-main {
            min-width: 0;
        }

        .shopglut-single-product.template1 .responsive-preview .product-gallery,
        .shopglut-single-product.template1 .responsive-preview .product-info {
            min-width: 0;
            overflow: hidden;
        }

        /* Container Query Based Responsive Design */

        /* Large Containers (Desktop) */
        @container (min-width: 1200px) {
            .shopglut-single-product.template1 .container {
                max-width: 1400px;
                padding: 0 40px;
            }
        }

        /* Medium Containers (Desktop/Tablet) */
        @container (max-width: 1199px) and (min-width: 1024px) {
            .shopglut-single-product.template1 .container {
                max-width: 100%;
                padding: 0 30px;
            }

            .shopglut-single-product.template1 .product-main {
                gap: 40px;
            }

            .shopglut-single-product.template1 .products-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 24px;
            }
        }

        /* Below 1024px - All Single Column Layout + Center Content */
        @container (max-width: 1023px) {
            .shopglut-single-product.template1 .container {
                padding: 0 20px;
            }

            .shopglut-single-product.template1 .product-main {
                display: flex !important;
                flex-direction: column !important;
                gap: 30px;
                margin-bottom: 60px;
            }

            .shopglut-single-product.template1 .product-gallery {
                position: static;
                width: 100% !important;
                max-width: 100% !important;
                order: 1;
            }

            .shopglut-single-product.template1 .product-info {
                display: block !important;
                align-items: unset !important;
                gap: unset !important;
                width: 100% !important;
                max-width: 100% !important;
                order: 2;
            }

            /* Fix product info layout */
            .shopglut-single-product.template1 .product-info .product-badges {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 20px;
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info h1 {
                font-size: 2.4rem;
                line-height: 1.2;
                margin-bottom: 20px;
                word-wrap: break-word;
            }

            .shopglut-single-product.template1 .price-section {
                margin-bottom: 30px;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 12px;
            }

            .shopglut-single-product.template1 .description {
                margin-bottom: 30px;
                line-height: 1.6;
            }

            .shopglut-single-product.template1 .product-options {
                margin-bottom: 30px;
            }

            .shopglut-single-product.template1 .option-group {
                margin-bottom: 24px;
                text-align: left;
            }

            .shopglut-single-product.template1 .color-options,
            .shopglut-single-product.template1 .size-options {
                flex-wrap: wrap;
                gap: 12px;
            }

            .shopglut-single-product.template1 .purchase-section {
                padding: 24px;
                margin-top: 20px;
            }

            .shopglut-single-product.template1 .quantity-cart {
                flex-direction: column;
                gap: 16px;
                margin-bottom: 16px;
            }

            .shopglut-single-product.template1 .quantity-selector {
                justify-content: center;
                max-width: 200px;
                margin: 0 auto;
            }

            .shopglut-single-product.template1 .add-to-cart {
                width: 100%;
                text-align: left;
            }

            .shopglut-single-product.template1 .secondary-actions {
                flex-direction: column;
                gap: 12px;
            }

            /* Center content on mobile/tablet */
            .shopglut-single-product.template1 .product-info h1 {
                text-align: left;
            }

            .shopglut-single-product.template1 .description {
                text-align: left;
            }

            .shopglut-single-product.template1 .option-label {
                text-align: left;
            }

            .shopglut-single-product.template1 .option-group {
                text-align: left;
            }

            .shopglut-single-product.template1 .product-info .product-badges {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .rating-section {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .price-section {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .color-options {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .size-options {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .quantity-cart {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .product-info .secondary-actions {
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .shopglut-single-product.template1 .footer-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }

        /* Tablet Portrait */
        @container (max-width: 768px) {
            .shopglut-single-product.template1 .container {
                padding: 0 16px;
            }

            .shopglut-single-product.template1 .product-main {
                gap: 25px;
            }

            .shopglut-single-product.template1 .product-info h1 {
                font-size: 2rem;
                margin-bottom: 16px;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 1.8rem;
            }

            .shopglut-single-product.template1 .original-price {
                font-size: 1.3rem;
            }

            .shopglut-single-product.template1 .main-image {
                height: 350px;
            }

            .shopglut-single-product.template1 .thumbnail-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }

            .shopglut-single-product.template1 .product-badges {
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 6px;
                margin-bottom: 16px;
            }

            .shopglut-single-product.template1 .rating-section {
                margin-bottom: 20px;
                gap: 12px;
            }

            .shopglut-single-product.template1 .price-section {
                margin-bottom: 25px;
            }

            .shopglut-single-product.template1 .description {
                font-size: 1rem;
                margin-bottom: 25px;
            }

            .shopglut-single-product.template1 .product-options {
                margin-bottom: 25px;
            }

            .shopglut-single-product.template1 .quantity-cart {
                flex-direction: column;
                gap: 15px;
            }

            .shopglut-single-product.template1 .secondary-actions {
                flex-direction: column;
                gap: 10px;
            }

            .shopglut-single-product.template1 .purchase-section {
                padding: 20px;
                margin-top: 15px;
            }

            .shopglut-single-product.template1 .color-options,
            .shopglut-single-product.template1 .size-options {
                flex-wrap: wrap;
            }
        }

        /* Mobile Large */
        @container (max-width: 576px) {
            .shopglut-single-product.template1 .container {
                padding: 0 14px;
            }

            .shopglut-single-product.template1 .product-section {
                padding: 25px 0;
            }

            .shopglut-single-product.template1 .product-main {
                gap: 20px;
                margin-bottom: 40px;
            }

            .shopglut-single-product.template1 .product-info {
                order: 2; /* Ensure product info comes after gallery on mobile */
            }

            .shopglut-single-product.template1 .product-gallery {
                order: 1;
            }

            .shopglut-single-product.template1 .product-info h1 {
                font-size: 1.6rem;
                line-height: 1.3;
                margin-bottom: 14px;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 1.6rem;
            }

            .shopglut-single-product.template1 .original-price {
                font-size: 1.1rem;
            }

            .shopglut-single-product.template1 .main-image {
                height: 280px;
                font-size: 15px;
            }

            .shopglut-single-product.template1 .thumbnail-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .shopglut-single-product.template1 .thumbnail {
                height: 70px;
            }

            .shopglut-single-product.template1 .product-badges {
                gap: 6px;
                margin-bottom: 14px;
            }

            .shopglut-single-product.template1 .badge {
                padding: 4px 10px;
                font-size: 0.75rem;
            }

            .shopglut-single-product.template1 .rating-section {
                margin-bottom: 18px;
                gap: 10px;
            }

            .shopglut-single-product.template1 .stars {
                font-size: 1.1rem;
            }

            .shopglut-single-product.template1 .price-section {
                margin-bottom: 22px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .shopglut-single-product.template1 .description {
                font-size: 0.95rem;
                margin-bottom: 22px;
                line-height: 1.5;
            }

            .shopglut-single-product.template1 .product-options {
                margin-bottom: 22px;
            }

            .shopglut-single-product.template1 .option-group {
                margin-bottom: 18px;
                text-align: left;
            }

            .shopglut-single-product.template1 .option-label {
                font-size: 0.9rem;
                margin-bottom: 10px;
                text-align: left;
                font-weight: 600;
            }

            .shopglut-single-product.template1 .color-option {
                width: 38px;
                height: 38px;
            }

            .shopglut-single-product.template1 .size-option {
                padding: 8px 14px;
                min-width: 45px;
                font-size: 0.85rem;
            }

            .shopglut-single-product.template1 .purchase-section {
                padding: 18px;
                margin-top: 12px;
            }

            .shopglut-single-product.template1 .quantity-cart {
                gap: 14px;
                margin-bottom: 14px;
            }

            .shopglut-single-product.template1 .secondary-actions {
                gap: 10px;
            }

            .shopglut-single-product.template1 .products-grid {
                grid-template-columns: 1fr !important;
                gap: 16px;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: 1fr !important;
                gap: 16px;
            }

            .shopglut-single-product.template1 .footer-content {
                grid-template-columns: 1fr !important;
                gap: 25px;
            }
        }

        /* Mobile Small */
        @container (max-width: 480px) {
            .shopglut-single-product.template1 .container {
                padding: 0 12px;
            }

            .shopglut-single-product.template1 .product-section {
                padding: 20px 0;
            }

            .shopglut-single-product.template1 .product-main {
                gap: 20px;
                margin-bottom: 40px;
            }

            .shopglut-single-product.template1 .product-info h1 {
                font-size: 1.6rem;
                margin-bottom: 12px;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 1.6rem;
            }

            .shopglut-single-product.template1 .original-price {
                font-size: 1.1rem;
            }

            .shopglut-single-product.template1 .main-image {
                height: 250px;
                font-size: 14px;
                border-radius: 16px;
            }

            .shopglut-single-product.template1 .thumbnail-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .shopglut-single-product.template1 .thumbnail {
                height: 70px;
                border-radius: 10px;
            }

            .shopglut-single-product.template1 .product-badges {
                gap: 8px;
                margin-bottom: 16px;
            }

            .shopglut-single-product.template1 .badge {
                padding: 4px 8px;
                font-size: 0.7rem;
                border-radius: 16px;
            }

            .shopglut-single-product.template1 .color-option {
                width: 35px;
                height: 35px;
            }

            .shopglut-single-product.template1 .size-option {
                padding: 8px 12px;
                min-width: 45px;
                font-size: 0.85rem;
            }

            .shopglut-single-product.template1 .purchase-section {
                padding: 16px;
                border-radius: 16px;
            }

            .shopglut-single-product.template1 .qty-btn {
                padding: 10px 14px;
                font-size: 0.95rem;
            }

            .shopglut-single-product.template1 .qty-input {
                padding: 10px 14px;
                width: 60px;
                font-size: 0.95rem;
            }

            .shopglut-single-product.template1 .add-to-cart {
                padding: 10px 20px;
                font-size: 0.95rem;
                border-radius: 10px;
            }

            .shopglut-single-product.template1 .wishlist-btn,
            .shopglut-single-product.template1 .compare-btn {
                padding: 10px 16px;
                font-size: 0.85rem;
                border-radius: 10px;
            }

            .shopglut-single-product.template1 .product-card {
                padding: 16px;
                border-radius: 16px;
            }

            .shopglut-single-product.template1 .product-image {
                height: 160px;
                border-radius: 12px;
                margin-bottom: 16px;
            }

            .shopglut-single-product.template1 .features-section {
                margin-top: 30px;
                padding: 20px;
                border-radius: 16px;
            }

            .shopglut-single-product.template1 .feature-item {
                padding: 16px;
            }

            .shopglut-single-product.template1 .feature-icon {
                margin-bottom: 8px;
            }

            .shopglut-single-product.template1 .section-title {
                font-size: 1.8rem;
                margin-bottom: 30px;
            }
        }

        /* Extra Small Containers */
        @container (max-width: 320px) {
            .shopglut-single-product.template1 .container {
                padding: 0 8px;
            }

            .shopglut-single-product.template1 .product-info h1 {
                font-size: 1.4rem;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 1.4rem;
            }

            .shopglut-single-product.template1 .main-image {
                height: 200px;
                font-size: 12px;
            }

            .shopglut-single-product.template1 .thumbnail {
                height: 50px;
            }

            .shopglut-single-product.template1 .purchase-section {
                padding: 12px;
            }

            .shopglut-single-product.template1 .features-section {
                padding: 16px;
            }
        }

        /* Container-Based Responsive Design */
        .shopglut-single-product.template1 {
            container-type: inline-size;
        }

        .shopglut-single-product.template1 .demo-content,
        .shopglut-single-product.template1 .live-content {
            container-type: inline-size;
        }

        /* Mobile Responsive Overrides */
        @media (max-width: 1024px) {
            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 20px !important;
            }

            .shopglut-single-product.template1 .variation-header {
                gap: 12px;
            }

            .shopglut-single-product.template1 .variation-icon {
                width: 40px;
                height: 40px;
            }

            .shopglut-single-product.template1 .variation-title h3 {
                font-size: 18px;
            }

            .shopglut-single-product.template1 .variation-options {
                gap: 10px;
            }

            .shopglut-single-product.template1 .size-option {
                min-width: 70px;
                padding: 10px 16px;
            }

            .shopglut-single-product.template1 .color-option {
                min-width: 80px;
                padding: 8px 12px;
            }

            .shopglut-single-product.template1 .variation-info {
                flex-direction: column;
                gap: 12px;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-product.template1 .product-main-wrapper {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 16px !important;
            }

            .shopglut-single-product.template1 .quantity-cart-wrapper {
                flex-direction: column !important;
                gap: 16px !important;
            }

            .shopglut-single-product.template1 .secondary-actions {
                flex-direction: column !important;
                gap: 12px !important;
            }
        }

        @media (max-width: 480px) {
            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: 1fr !important;
            }

            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }

            .shopglut-single-product.template1 .related-product-image {
                height: 140px !important;
            }

            .shopglut-single-product.template1 .related-product-card {
                padding: 16px !important;
            }
        }

        /* Fallback Media Queries for Non-Container Query Support */
        @media (max-width: 1023px) {
            .shopglut-single-product.template1 .product-main {
                display: flex !important;
                flex-direction: column !important;
                gap: 30px;
                margin-bottom: 60px;
            }

            .shopglut-single-product.template1 .product-gallery {
                position: static;
                width: 100% !important;
                max-width: 100% !important;
                order: 1;
            }

            .shopglut-single-product.template1 .product-info {
                display: block !important;
                align-items: unset !important;
                gap: unset !important;
                width: 100% !important;
                max-width: 100% !important;
                order: 2;
            }

            .shopglut-single-product.template1 .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .shopglut-single-product.template1 .footer-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .shopglut-single-product.template1 .container {
                padding: 0 16px;
            }

            .shopglut-single-product.template1 .product-main {
                display: flex !important;
                flex-direction: column !important;
                gap: 25px;
            }

            .shopglut-single-product.template1 .product-gallery,
            .shopglut-single-product.template1 .product-info {
                width: 100% !important;
                max-width: 100% !important;
            }

            .shopglut-single-product.template1 .product-info {
                display: block !important;
                align-items: unset !important;
                gap: unset !important;
            }

            .shopglut-single-product.template1 .quantity-cart {
                flex-direction: column;
                gap: 15px;
            }

            .shopglut-single-product.template1 .secondary-actions {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .shopglut-single-product.template1 .product-main {
                display: flex !important;
                flex-direction: column !important;
                gap: 20px;
            }

            .shopglut-single-product.template1 .product-gallery,
            .shopglut-single-product.template1 .product-info {
                width: 100% !important;
                max-width: 100% !important;
            }

            .shopglut-single-product.template1 .product-info {
                display: block !important;
                align-items: unset !important;
                gap: unset !important;
            }

            .shopglut-single-product.template1 .products-grid {
                grid-template-columns: 1fr !important;
                gap: 16px;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: 1fr !important;
                gap: 16px;
            }

            .shopglut-single-product.template1 .footer-content {
                grid-template-columns: 1fr !important;
                gap: 25px;
            }
        }

        /* Admin Preview Specific Responsive Adjustments */
        @media (max-width: 768px) {
            .shopglut-single-product.template1 .demo-content .container,
            .shopglut-single-product.template1 .responsive-preview .container {
                padding: 0 10px;
            }

            .shopglut-single-product.template1 .demo-content .product-main,
            .shopglut-single-product.template1 .responsive-preview .product-main {
                grid-template-columns: 1fr !important;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .shopglut-single-product.template1 .demo-content .container,
            .shopglut-single-product.template1 .responsive-preview .container {
                padding: 0 8px;
            }

            .shopglut-single-product.template1 .demo-content .product-main,
            .shopglut-single-product.template1 .responsive-preview .product-main {
                gap: 16px;
            }
        }

        /* ========== COMPREHENSIVE RESPONSIVE CSS ========== */

        /* Tablet and Below (1024px and below) */
        @media (max-width: 1024px) {
            .shopglut-single-product.template1 .product-main-wrapper {
                grid-template-columns: 1fr !important;
                gap: 30px !important;
            }

            .shopglut-single-product.template1 .product-gallery-section,
            .shopglut-single-product.template1 .product-info-section {
                width: 100% !important;
            }

            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .shopglut-single-product.template1 .variation-selector .variation-options {
                flex-wrap: wrap !important;
            }

            .shopglut-single-product.template1 .size-option {
                min-width: 70px !important;
                padding: 10px 14px !important;
            }
        }

        /* Mobile Landscape and Portrait (768px and below) */
        @media (max-width: 768px) {
            .shopglut-single-product.template1 .shopglut-single-product-container {
                border-radius: 0 !important;
            }

            .shopglut-single-product.template1 .container {
                padding: 0 16px !important;
            }

            .shopglut-single-product.template1 .product-main-wrapper {
                gap: 25px !important;
            }

            .shopglut-single-product.template1 .main-image-container {
                padding: 12px !important;
            }

            .shopglut-single-product.template1 .thumbnail-gallery {
                gap: 8px !important;
            }

            /* On mobile, let responsive CSS handle thumbnail sizing */

            /* Product Info Section */
            .shopglut-single-product.template1 .product-title {
                font-size: 24px !important;
                line-height: 1.3 !important;
            }

            .shopglut-single-product.template1 .rating-section {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .price-section {
                flex-wrap: wrap !important;
                gap: 8px !important;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 22px !important;
            }

            .shopglut-single-product.template1 .original-price {
                font-size: 16px !important;
            }

            .shopglut-single-product.template1 .discount-badge {
                font-size: 12px !important;
                padding: 4px 8px !important;
            }

            .shopglut-single-product.template1 .product-description {
                font-size: 15px !important;
                line-height: 1.6 !important;
            }

            /* Purchase Section */
            .shopglut-single-product.template1 .quantity-cart-wrapper {
                flex-direction: column !important;
                gap: 12px !important;
            }

            .shopglut-single-product.template1 .quantity-selector {
                max-width: 100% !important;
            }

            .shopglut-single-product.template1 .add-to-cart-btn,
            .shopglut-single-product.template1 .shopglut-variable-add-to-cart {
                width: 100% !important;
                justify-content: center !important;
            }

            .shopglut-single-product.template1 .secondary-actions {
                flex-direction: column !important;
                gap: 10px !important;
            }

            .shopglut-single-product.template1 .wishlist-btn,
            .shopglut-single-product.template1 .compare-btn {
                width: 100% !important;
            }

            /* Features Section */
            .shopglut-single-product.template1 .features-section {
                padding: 30px 16px !important;
            }

            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 16px !important;
            }

            .shopglut-single-product.template1 .feature-item {
                padding: 16px !important;
            }

            .shopglut-single-product.template1 .feature-icon {
                /* Font size controlled by settings */
            }

            .shopglut-single-product.template1 .feature-title {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .feature-description {
                font-size: 13px !important;
            }

            /* WooCommerce Tabs */
            .shopglut-single-product.template1 .woocommerce-tabs-section {
                padding: 0 !important;
                margin: 30px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs {
                flex-wrap: wrap !important;
                gap: 4px !important;
                justify-content: flex-start !important;
                margin: 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li {
                flex: 1 1 auto !important;
                min-width: 45% !important;
                max-width: 50% !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a {
                font-size: 13px !important;
                padding: 10px 12px !important;
                white-space: nowrap !important;
                text-overflow: ellipsis !important;
                overflow: hidden !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a i {
                display: inline-block !important;
                font-size: 12px !important;
                flex-shrink: 0 !important;
            }

            /* Tab Content Panels */
            .shopglut-single-product.template1 .woocommerce-Tabs-panel {
                padding: 20px 16px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel h2,
            .shopglut-single-product.template1 .woocommerce-Tabs-panel h3 {
                font-size: 18px !important;
                margin: 0 0 12px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel p {
                font-size: 14px !important;
                line-height: 1.6 !important;
            }

            /* Related Products */
            .shopglut-single-product.template1 .related-products-section {
                padding: 30px 0 !important;
            }

            .shopglut-single-product.template1 .related-products-title {
                font-size: 20px !important;
                margin-bottom: 20px !important;
            }

            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 16px !important;
            }

            .shopglut-single-product.template1 .related-product-card {
                padding: 12px !important;
            }

            .shopglut-single-product.template1 .related-product-image {
                height: 150px !important;
            }

            .shopglut-single-product.template1 .related-product-name {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .quick-add-btn {
                padding: 8px 12px !important;
                font-size: 13px !important;
            }

            /* Reviews */
            .shopglut-single-product.template1 .comment_container {
                flex-direction: column !important;
                gap: 12px !important;
            }

            .shopglut-single-product.template1 .review-avatar {
                width: 50px !important;
                height: 50px !important;
            }

            .shopglut-single-product.template1 .review-form-intro p {
                font-size: 14px !important;
            }

            /* Form Elements */
            .shopglut-single-product.template1 .variations .label {
                font-size: 13px !important;
                min-width: 80px !important;
            }

            .shopglut-single-product.template1 .variations select {
                max-width: 100% !important;
            }
        }

        /* Mobile (576px and below) */
        @media (max-width: 576px) {
            .shopglut-single-product.template1 .container {
                padding: 0 12px !important;
            }

            .shopglut-single-product.template1 .product-main-wrapper {
                gap: 20px !important;
            }

            .shopglut-single-product.template1 .product-title {
                font-size: 20px !important;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 20px !important;
            }

            .shopglut-single-product.template1 .original-price {
                font-size: 14px !important;
            }

            /* Thumbnail Gallery */
            .shopglut-single-product.template1 .thumbnail-gallery {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 6px !important;
            }

            /* On small mobile, thumbnails adjust automatically */
            .shopglut-single-product.template1 .thumbnail-item {
                /* Width and height controlled by settings */
            }

            /* Features - Single Column */
            .shopglut-single-product.template1 .features-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            /* Related Products - Single Column */
            .shopglut-single-product.template1 .related-products-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            .shopglut-single-product.template1 .related-product-image {
                height: 180px !important;
            }

            /* WooCommerce Tabs - Full Width */
            .shopglut-single-product.template1 .woocommerce-tabs-section {
                margin: 20px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs {
                gap: 4px !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li {
                min-width: 100% !important;
                max-width: 100% !important;
                flex: 1 1 100% !important;
                text-align: center !important;
                margin: 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a {
                justify-content: center !important;
                width: 100% !important;
                padding: 12px 16px !important;
                text-overflow: ellipsis !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a i {
                display: inline-block !important;
            }

            /* Tab Content Panels - More Compact */
            .shopglut-single-product.template1 .woocommerce-Tabs-panel {
                padding: 16px 12px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel h2,
            .shopglut-single-product.template1 .woocommerce-Tabs-panel h3 {
                font-size: 16px !important;
                margin: 0 0 10px 0 !important;
            }

            /* Review Form */
            .shopglut-single-product.template1 #review_form_wrapper {
                padding: 16px !important;
            }

            .shopglut-single-product.template1 .form-row input,
            .shopglut-single-product.template1 .form-row textarea {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .star-rating-select i {
                font-size: 18px !important;
            }
        }

        /* Small Mobile (480px and below) */
        @media (max-width: 480px) {
            .shopglut-single-product.template1 .container {
                padding: 0 10px !important;
            }

            .shopglut-single-product.template1 .product-title {
                font-size: 18px !important;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 18px !important;
            }

            .shopglut-single-product.template1 .product-description {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .add-to-cart-btn,
            .shopglut-single-product.template1 .shopglut-variable-add-to-cart {
                font-size: 14px !important;
                padding: 12px 16px !important;
            }

            .shopglut-single-product.template1 .wishlist-btn,
            .shopglut-single-product.template1 .compare-btn {
                font-size: 13px !important;
                padding: 10px 14px !important;
            }

            /* WooCommerce Product Attributes Table */
            .shopglut-single-product.template1 .woocommerce-product-attributes {
                font-size: 13px !important;
            }

            .shopglut-single-product.template1 .woocommerce-product-attributes th,
            .shopglut-single-product.template1 .woocommerce-product-attributes td {
                padding: 8px 6px !important;
            }

            /* Shipping Table */
            .shopglut-single-product.template1 .shipping-options-table {
                font-size: 12px !important;
            }

            .shopglut-single-product.template1 .shipping-options-table th,
            .shopglut-single-product.template1 .shipping-options-table td {
                padding: 8px 6px !important;
            }

            /* WooCommerce Tabs - Extra Small Mobile */
            .shopglut-single-product.template1 .woocommerce-tabs-section {
                margin: 16px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a {
                font-size: 12px !important;
                padding: 10px 12px !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a i {
                font-size: 11px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel {
                padding: 14px 10px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel h2,
            .shopglut-single-product.template1 .woocommerce-Tabs-panel h3 {
                font-size: 15px !important;
                margin: 0 0 8px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel p {
                font-size: 13px !important;
            }
        }

        /* Extra Small Mobile (360px and below) */
        @media (max-width: 360px) {
            .shopglut-single-product.template1 .container {
                padding: 0 8px !important;
            }

            .shopglut-single-product.template1 .product-title {
                font-size: 16px !important;
            }

            .shopglut-single-product.template1 .current-price {
                font-size: 16px !important;
            }

            /* On extra small mobile, thumbnails controlled by settings */
            .shopglut-single-product.template1 .thumbnail-item {
                /* Height controlled by settings */
            }

            .shopglut-single-product.template1 .quantity-selector {
                min-width: 120px !important;
            }

            .shopglut-single-product.template1 .qty-input {
                width: 40px !important;
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .qty-decrease,
            .shopglut-single-product.template1 .qty-increase {
                width: 32px !important;
                height: 32px !important;
                font-size: 16px !important;
            }

            /* WooCommerce Tabs - Minimum Mobile */
            .shopglut-single-product.template1 .woocommerce-tabs-section {
                margin: 12px 0 !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a {
                font-size: 11px !important;
                padding: 8px 10px !important;
            }

            .shopglut-single-product.template1 .woocommerce-tabs .tabs li a i {
                font-size: 10px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel {
                padding: 12px 8px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel h2,
            .shopglut-single-product.template1 .woocommerce-Tabs-panel h3 {
                font-size: 14px !important;
            }

            .shopglut-single-product.template1 .woocommerce-Tabs-panel p {
                font-size: 12px !important;
            }
        }

        /* ========== END COMPREHENSIVE RESPONSIVE CSS ========== */

        /* Settings-based Dynamic CSS - Loaded last to override base styles */
        <?php echo $this->generateSettingsBasedCSS($settings); ?>

        </style>
        <?php
    }

    /**
     * Generate CSS based on settings
     */
    private function generateSettingsBasedCSS($settings) {
        $css = '';

        // Product Gallery Settings
        $css .= '.shopglut-single-product.template1 .product-gallery-section {';
        $css .= 'margin-bottom: ' . $this->getSetting($settings, 'gallery_section_margin', 40) . 'px;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .main-image-container {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'main_image_background', '#f9fafb') . ' !important;';
        $css .= 'background-image: none !important;';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px !important;';
        $css .= 'border: ' . $this->getSetting($settings, 'main_image_border_width', 1) . 'px solid ' . $this->getSetting($settings, 'main_image_border_color', '#e5e7eb') . ' !important;';
        $css .= 'padding: ' . $this->getSetting($settings, 'main_image_padding', 8) . 'px !important;';
        $css .= 'margin-bottom: ' . $this->getSetting($settings, 'main_image_margin_bottom', 20) . 'px !important;';
        if ($this->getSetting($settings, 'main_image_shadow', true)) {
            $shadow_color = $this->getSetting($settings, 'main_image_shadow_color', 'rgba(0,0,0,0.1)');
            $css .= 'box-shadow: 0 4px 12px ' . $shadow_color . ' !important;';
        } else {
            $css .= 'box-shadow: none !important;';
        }
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .main-product-image {';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px;';
        $css .= 'width: 100%;';
        $css .= 'height: auto;';
        $css .= 'object-fit: ' . $this->getSetting($settings, 'main_image_object_fit', 'cover') . ';';
        $css .= 'cursor: ' . $this->getSetting($settings, 'main_image_cursor', 'zoom-in') . ';';
        $css .= 'transition: transform 0.3s ease, box-shadow 0.3s ease;';
        $css .= '}';

        // Image cursor style
        if ($this->getSetting($settings, 'enable_image_lightbox', true)) {
            $css .= '.shopglut-single-product.template1 .main-product-image {';
            $css .= 'cursor: ' . $this->getSetting($settings, 'main_image_cursor', 'zoom-in') . ';';
            $css .= '}';
        }

        // Hover zoom effect CSS
        if ($this->getSetting($settings, 'enable_image_hover_zoom', false)) {
            $zoom_level = $this->getSetting($settings, 'hover_zoom_level', 2);
            $css .= '.shopglut-single-product.template1 .main-image-container {';
            $css .= 'position: relative;';
            $css .= 'overflow: hidden;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .main-product-image {';
            $css .= 'transform-origin: center center;';
            $css .= 'transition: transform 0.3s ease;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .main-image-container:hover .main-product-image {';
            $css .= 'transform: scale(' . $zoom_level . ');';
            $css .= '}';
        }

        // Shimmer effect
        if ($this->getSetting($settings, 'enable_shimmer_effect', false)) {
            $shimmer_speed = $this->getSetting($settings, 'shimmer_speed', 3);
            $shimmer_opacity = $this->getSetting($settings, 'shimmer_opacity', 20) / 100;
            $css .= '.shopglut-single-product.template1 .main-image-container::before {';
            $css .= 'display: block !important;';
            $css .= 'background: linear-gradient(45deg, transparent, rgba(255,255,255,' . $shimmer_opacity . '), transparent) !important;';
            $css .= 'animation: shimmer ' . $shimmer_speed . 's infinite !important;';
            $css .= '}';
        } else {
            // Disable shimmer when not enabled
            $css .= '.shopglut-single-product.template1 .main-image-container::before {';
            $css .= 'display: none !important;';
            $css .= '}';
        }

        // Main image hover effects - combined into single rule
        $has_hover_scale = $this->getSetting($settings, 'main_image_hover_scale', false);
        $has_hover_brightness = $this->getSetting($settings, 'main_image_hover_brightness', false);
        $has_hover_zoom = $this->getSetting($settings, 'enable_image_hover_zoom', false);

        // Only apply these hover effects if position-based hover zoom is NOT enabled
        // (hover zoom uses JavaScript and shouldn't conflict with CSS hover effects)
        if (($has_hover_scale || $has_hover_brightness) && !$has_hover_zoom) {
            $css .= '.shopglut-single-product.template1 .main-product-image:hover {';
            if ($has_hover_scale) {
                $hover_scale = $this->getSetting($settings, 'main_image_hover_scale_value', 1.05);
                $css .= 'transform: scale(' . $hover_scale . ') !important;';
            }
            if ($has_hover_brightness) {
                $brightness = $this->getSetting($settings, 'main_image_hover_brightness_value', 110);
                $css .= 'filter: brightness(' . $brightness . '%) !important;';
            }
            $css .= '}';
        }

        // Thumbnail Gallery
        if ($this->getSetting($settings, 'show_thumbnails', true)) {
            $css .= '.shopglut-single-product.template1 .thumbnail-gallery {';
            $css .= 'gap: ' . $this->getSetting($settings, 'thumbnail_spacing', 8) . 'px;';
            $css .= 'margin-top: ' . $this->getSetting($settings, 'thumbnail_gallery_margin_top', 16) . 'px;';
            $css .= 'justify-content: ' . $this->getSetting($settings, 'thumbnail_alignment', 'flex-start') . ';';
            $css .= '}';

            // Thumbnail item - combined styles
            $css .= '.shopglut-single-product.template1 .thumbnail-item {';
            $css .= 'width: ' . $this->getSetting($settings, 'thumbnail_size', 80) . 'px !important;';
            $css .= 'height: ' . $this->getSetting($settings, 'thumbnail_size', 80) . 'px !important;';
            $css .= 'padding: 3px;'; // Space for shadow/border on hover
            $css .= 'cursor: pointer;';
            $css .= 'transition: all 0.3s ease;';
            $css .= 'box-sizing: border-box;';
            $css .= 'position: relative;';
            $css .= 'background: transparent;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'thumbnail_border_radius', 6) . 'px !important;';
            $css .= 'border: ' . $this->getSetting($settings, 'thumbnail_border_width', 2) . 'px solid ' . $this->getSetting($settings, 'thumbnail_border_color', 'transparent') . ' !important;';
            // Opacity for inactive thumbnails
            $opacity = $this->getSetting($settings, 'thumbnail_opacity', 70) / 100;
            $css .= 'opacity: ' . $opacity . ' !important;';
            $css .= '}';

            // Active thumbnail - full opacity
            $css .= '.shopglut-single-product.template1 .thumbnail-item.active {';
            $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_active_border', '#667eea') . ' !important;';
            $css .= 'opacity: 1 !important;';
            $css .= '}';

            // Hover effect - only border and shadow change, no transform on container
            $css .= '.shopglut-single-product.template1 .thumbnail-item:hover {';
            $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_hover_border', '#2563eb') . ' !important;';
            $css .= 'opacity: 1 !important;';
            if ($this->getSetting($settings, 'thumbnail_hover_scale', true)) {
                $css .= 'z-index: 10;';
                $css .= 'box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            }
            $css .= '}';

            // Image scales on hover - not the container
            if ($this->getSetting($settings, 'thumbnail_hover_scale', true)) {
                $css .= '.shopglut-single-product.template1 .thumbnail-item:hover .thumbnail-image {';
                $css .= 'transform: scale(1.08);';
                $css .= '}';
            }

            // Thumbnail image - stays within container
            $css .= '.shopglut-single-product.template1 .thumbnail-image {';
            $css .= 'width: 100%;';
            $css .= 'height: 100%;';
            $css .= 'object-fit: cover;';
            $css .= 'border-radius: ' . max(0, $this->getSetting($settings, 'thumbnail_border_radius', 6) - 2) . 'px;';
            $css .= 'display: block;';
            $css .= 'transition: transform 0.3s ease;';
            $css .= '}';
        }

        // Product Badges
        if ($this->getSetting($settings, 'show_product_badges', true)) {
            $css .= '.shopglut-single-product.template1 .product-badges-container {';
            $css .= 'display: flex !important;';
            $css .= 'gap: ' . $this->getSetting($settings, 'badge_spacing', 8) . 'px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= 'flex-wrap: wrap !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .product-badge {';
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
                    $css .= '.shopglut-single-product.template1 .badge-' . $type . ' {';
                    $css .= 'background-color: ' . $this->getSetting($settings, $type . '_badge_background_color', $default_color) . ' !important;';
                    $css .= 'color: ' . $this->getSetting($settings, $type . '_badge_text_color', '#ffffff') . ' !important;';
                    $css .= '}';
                }
            }
        }

        // Product Title
        $css .= '.shopglut-single-product.template1 .product-title {';
        $css .= 'color: ' . $this->getSetting($settings, 'product_title_color', '#111827') . ' !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'product_title_font_size', 32) . 'px !important;';
        $css .= 'font-weight: ' . $this->getSetting($settings, 'product_title_font_weight', '700') . ' !important;';
        $css .= 'margin-bottom: 16px !important;';
        $css .= 'text-align:left;';
        $css .= 'line-height: 1.2 !important;';
        $css .= '}';

        // Rating Section
        if ($this->getSetting($settings, 'show_rating', true)) {
            $css .= '.shopglut-single-product.template1 .stars-container .star.filled {';
            $css .= 'color: ' . $this->getSetting($settings, 'star_color', '#fbbf24') . ';';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .rating-text {';
            $css .= 'color: ' . $this->getSetting($settings, 'rating_text_color', '#6b7280') . ';';
            $css .= 'font-size: ' . $this->getSetting($settings, 'rating_font_size', 14) . 'px;';
            $css .= '}';
        }

        // Price Section
        $css .= '.shopglut-single-product.template1 .price-section {';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'gap: 12px !important;';
        $css .= 'margin-bottom: 24px !important;';
        $css .= 'flex-wrap: wrap !important;';
        $css .= 'background-color: ' . $this->getSetting($settings, 'price_background_color', 'transparent') . ' !important;';
        $css .= 'padding: 12px 16px !important;';
        $css .= 'border-radius: 8px !important;';
        $css .= '}';

        // Current Price - from Product Info Settings
        $css .= '.shopglut-single-product.template1 .current-price {';
        $css .= 'color: ' . $this->getSetting($settings, 'current_price_color', '#ffffff') . ' !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'current_price_font_size', 28) . 'px !important;';
        $css .= 'font-weight: 700 !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .original-price {';
        $css .= 'color: ' . $this->getSetting($settings, 'original_price_color', '#9ca3af') . ' !important;';
        $css .= 'font-size: 1.2rem !important;';
        $css .= 'text-decoration: line-through !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .discount-badge {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'discount_badge_color', '#ef4444') . ' !important;';
        $css .= 'color: ' . $this->getSetting($settings, 'discount_badge_text_color', '#ffffff') . ' !important;';
        $css .= 'padding: 4px 8px !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'font-size: 12px !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= '}';

        // Description
        if ($this->getSetting($settings, 'show_description', true)) {
            $css .= '.shopglut-single-product.template1 .product-description {';
            $css .= 'color: ' . $this->getSetting($settings, 'description_color', '#6b7280') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'description_font_size', 16) . 'px !important;';
            $css .= 'line-height: ' . $this->getSetting($settings, 'description_line_height', 1.6) . ' !important;';
            $css .= 'margin-bottom: 24px !important;';
            $css .= '}';
        }

        // Product Attributes
        if ($this->getSetting($settings, 'show_product_attributes', true)) {
            // General Attribute Settings
            $css .= '.shopglut-single-product.template1 .attribute-group {';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
            $css .= '}';

            // Attribute Layout Style
            $layout_style = $this->getSetting($settings, 'attribute_layout_style', 'horizontal');
            if ($layout_style === 'vertical') {
                $css .= '.shopglut-single-product.template1 .product-attributes {';
                $css .= 'display: flex !important;';
                $css .= 'flex-direction: column !important;';
                $css .= '}';
            } elseif ($layout_style === 'grid') {
                $css .= '.shopglut-single-product.template1 .product-attributes {';
                $css .= 'display: grid !important;';
                $css .= 'grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;';
                $css .= 'gap: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
                $css .= '}';
            } else {
                $css .= '.shopglut-single-product.template1 .product-attributes {';
                $css .= 'display: flex !important;';
                $css .= 'flex-direction: row !important;';
                $css .= 'flex-wrap: wrap !important;';
                $css .= 'gap: ' . $this->getSetting($settings, 'attribute_spacing', 20) . 'px !important;';
                $css .= '}';
            }

            // Attribute Labels
            if ($this->getSetting($settings, 'show_attribute_labels', true)) {
                $css .= '.shopglut-single-product.template1 .attribute-label {';
                $css .= 'display: block !important;';
                $css .= 'color: ' . $this->getSetting($settings, 'attribute_label_color', '#374151') . ' !important;';
                $css .= 'font-size: ' . $this->getSetting($settings, 'attribute_label_font_size', 14) . 'px !important;';
                $css .= 'font-weight: ' . $this->getSetting($settings, 'attribute_label_font_weight', '500') . ' !important;';
                $css .= 'margin-bottom: ' . $this->getSetting($settings, 'attribute_label_margin_bottom', 8) . 'px !important;';
                $css .= '}';
            }

            
            // Button Attributes (Size, Weight, Version)
            $css .= '.shopglut-single-product.template1 .size-button {';
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
            $css .= '.shopglut-single-product.template1 .size-button.active {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'button_attribute_active_background', '#8b5cf6') . ' !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'button_attribute_active_text', '#ffffff') . ' !important;';
            $css .= 'border-color: ' . $this->getSetting($settings, 'button_attribute_active_border', '#8b5cf6') . ' !important;';
            $css .= '}';

            // Dropdown Attributes
            $css .= '.shopglut-single-product.template1 .attribute-dropdown {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'dropdown_attribute_background', '#ffffff') . ' !important;';
            $css .= 'border: 1px solid ' . $this->getSetting($settings, 'dropdown_attribute_border_color', '#d1d5db') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'dropdown_attribute_border_radius', 6) . 'px !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'dropdown_attribute_padding', 12) . 'px !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'dropdown_attribute_text_color', '#374151') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'dropdown_attribute_font_size', 14) . 'px !important;';
            $css .= '}';

            // Attribute Behavior - Unavailable attributes styling
            if ($this->getSetting($settings, 'show_unavailable_attributes', true)) {
                $css .= '.shopglut-single-product.template1 .size-button.unavailable, ';
                $css .= '.shopglut-single-product.template1 .attribute-dropdown.unavailable {';
                $css .= 'opacity: ' . $this->getSetting($settings, 'unavailable_attribute_opacity', 0.5) . ' !important;';
                $css .= 'cursor: not-allowed !important;';
                $css .= '}';
            }

            // Required asterisk styling
            if ($this->getSetting($settings, 'attribute_required_asterisk', true)) {
                $css .= '.shopglut-single-product.template1 .attribute-label.required:after {';
                $css .= 'content: " *" !important;';
                $css .= 'color: ' . $this->getSetting($settings, 'required_asterisk_color', '#ef4444') . ' !important;';
                $css .= '}';
            }
        }

        // Purchase Section
        $css .= '.shopglut-single-product.template1 .purchase-section {';
        $css .= 'background: #f8fafc !important;';
        $css .= 'padding: 24px !important;';
        $css .= 'border-radius: 16px !important;';
        $css .= 'border: 1px solid #e2e8f0 !important;';
        $css .= 'margin-top: 24px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .quantity-cart-wrapper {';
        $css .= 'display: flex !important;';
        $css .= 'gap: 12px !important;';
        $css .= 'margin-bottom: 16px !important;';
        $css .= 'align-items: center !important;';
        $css .= '}';

        // Quantity Selector
        $quantity_border_radius = $this->getSetting($settings, 'quantity_border_radius', 6);
        $quantity_input_background = $this->getSetting($settings, 'quantity_input_background', '#ffffff');
        $quantity_input_border = $this->getSetting($settings, 'quantity_input_border', '#d1d5db');
        $quantity_button_background = $this->getSetting($settings, 'quantity_button_background', '#f3f4f6');
        $quantity_button_text_color = $this->getSetting($settings, 'quantity_button_text_color', '#374151');

        $css .= '.shopglut-single-product.template1 .quantity-selector {';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'gap: 0 !important;';
        $css .= 'border: 2px solid ' . $quantity_input_border . ' !important;';
        $css .= 'border-radius: ' . $quantity_border_radius . 'px !important;';
        $css .= 'overflow: hidden !important;';
        $css .= 'background: ' . $quantity_input_background . ' !important;';
        $css .= '}';

        // Quantity Buttons
        $css .= '.shopglut-single-product.template1 .qty-decrease, .shopglut-single-product.template1 .qty-increase {';
        $css .= 'width: 44px !important;';
        $css .= 'height: 44px !important;';
        $css .= 'padding: 0 !important;';
        $css .= 'background: ' . $quantity_button_background . ' !important;';
        $css .= 'border: none !important;';
        $css .= 'color: ' . $quantity_button_text_color . ' !important;';
        $css .= 'font-size: 20px !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= 'cursor: pointer !important;';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'justify-content: center !important;';
        $css .= 'transition: background-color 0.2s !important;';
        $css .= 'border-radius: 0 !important;';
        $css .= '}';

        // First button border radius (left)
        $css .= '.shopglut-single-product.template1 .qty-decrease {';
        $css .= 'border-top-left-radius: ' . ($quantity_border_radius - 2) . 'px !important;';
        $css .= 'border-bottom-left-radius: ' . ($quantity_border_radius - 2) . 'px !important;';
        $css .= '}';

        // Last button border radius (right)
        $css .= '.shopglut-single-product.template1 .qty-increase {';
        $css .= 'border-top-right-radius: ' . ($quantity_border_radius - 2) . 'px !important;';
        $css .= 'border-bottom-right-radius: ' . ($quantity_border_radius - 2) . 'px !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .qty-decrease:hover, .shopglut-single-product.template1 .qty-increase:hover {';
        $css .= 'background: #e5e7eb !important;';
        $css .= '}';

        // Quantity Input
        $css .= '.shopglut-single-product.template1 .qty-input {';
        $css .= 'background: ' . $quantity_input_background . ' !important;';
        $css .= 'color: ' . $quantity_button_text_color . ' !important;';
        $css .= 'border: none !important;';
        $css .= '}';

        // Add to Cart Button
        $cart_button_background = $this->getSetting($settings, 'cart_button_background', '#667eea');
        $cart_button_text_color = $this->getSetting($settings, 'cart_button_text_color', '#ffffff');
        $cart_button_hover_background = $this->getSetting($settings, 'cart_button_hover_background', '#5a67d8');
        $cart_button_border_radius = $this->getSetting($settings, 'cart_button_border_radius', 8);
        $cart_button_font_size = $this->getSetting($settings, 'cart_button_font_size', 16);
        $cart_button_font_weight = $this->getSetting($settings, 'cart_button_font_weight', '600');

        $css .= '.shopglut-single-product.template1 .add-to-cart-btn {';
        $css .= 'padding: 14px 32px !important;';
        $css .= 'background: ' . $cart_button_background . ' !important;';
        $css .= 'color: ' . $cart_button_text_color . ' !important;';
        $css .= 'border: none !important;';
        $css .= 'border-radius: ' . $cart_button_border_radius . 'px !important;';
        $css .= 'font-size: ' . $cart_button_font_size . 'px !important;';
        $css .= 'font-weight: ' . $cart_button_font_weight . ' !important;';
        $css .= 'cursor: pointer !important;';
        $css .= 'transition: background-color 0.2s !important;';
        $css .= 'white-space: nowrap !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .add-to-cart-btn:hover {';
        $css .= 'background: ' . $cart_button_hover_background . ' !important;';
        $css .= '}';

        // Also apply to variable product add to cart button
        $css .= '.shopglut-single-product.template1 .shopglut-variable-add-to-cart {';
        $css .= 'padding: 14px 32px !important;';
        $css .= 'background: ' . $cart_button_background . ' !important;';
        $css .= 'color: ' . $cart_button_text_color . ' !important;';
        $css .= 'border: none !important;';
        $css .= 'border-radius: ' . $cart_button_border_radius . 'px !important;';
        $css .= 'font-size: ' . $cart_button_font_size . 'px !important;';
        $css .= 'font-weight: ' . $cart_button_font_weight . ' !important;';
        $css .= 'cursor: pointer !important;';
        $css .= 'transition: background-color 0.2s !important;';
        $css .= 'white-space: nowrap !important;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .shopglut-variable-add-to-cart:hover {';
        $css .= 'background: ' . $cart_button_hover_background . ' !important;';
        $css .= '}';

        // Secondary Actions
        if ($this->getSetting($settings, 'show_wishlist_button', true) || $this->getSetting($settings, 'show_compare_button', true)) {
            $hover_color = $this->getSetting($settings, 'secondary_button_hover_color', '#8b5cf6');

            $css .= '.shopglut-single-product.template1 .wishlist-btn, .shopglut-single-product.template1 .compare-btn {';
            $css .= 'color: ' . $this->getSetting($settings, 'secondary_button_color', '#475569') . ' !important;';
            $css .= 'background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;';
            $css .= 'border: 1px solid #e2e8f0 !important;';
            $css .= 'padding: 14px 20px !important;';
            $css .= 'border-radius: 10px !important;';
            $css .= 'cursor: pointer !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'font-size: 14px !important;';
            $css .= 'flex: 1 !important;';
            $css .= 'text-align: center !important;';
            $css .= 'display: inline-flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'justify-content: center !important;';
            $css .= 'gap: 10px !important;';
            $css .= 'box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1) !important;';
            $css .= 'position: relative !important;';
            $css .= 'overflow: hidden !important;';
            $css .= 'transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .wishlist-btn::before, .shopglut-single-product.template1 .compare-btn::before {';
            $css .= 'content: "" !important;';
            $css .= 'position: absolute !important;';
            $css .= 'top: 0 !important;';
            $css .= 'left: -100% !important;';
            $css .= 'width: 100% !important;';
            $css .= 'height: 100% !important;';
            $css .= 'background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent) !important;';
            $css .= 'transition: left 0.5s ease !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .wishlist-btn:hover, .shopglut-single-product.template1 .compare-btn:hover {';
            $css .= 'color: ' . $hover_color . ' !important;';
            $css .= 'border-color: ' . $hover_color . ' !important;';
            $css .= 'transform: translateY(-2px) !important;';
            $css .= 'box-shadow: 0 4px 12px ' . $this->hexToRgba($hover_color, 0.2) . ', 0 2px 4px rgba(0, 0, 0, 0.08) !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .wishlist-btn:hover::before, .shopglut-single-product.template1 .compare-btn:hover::before {';
            $css .= 'left: 100% !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .wishlist-btn i, .shopglut-single-product.template1 .compare-btn i {';
            $css .= 'font-size: 16px !important;';
            $css .= 'transition: transform 0.3s ease !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .wishlist-btn:hover i, .shopglut-single-product.template1 .compare-btn:hover i {';
            $css .= 'transform: scale(1.1) !important;';
            $css .= '}';

            // Conditional visibility
            if (!$this->getSetting($settings, 'show_wishlist_button', true)) {
                $css .= '.shopglut-single-product.template1 .wishlist-btn { display: none !important; }';
            }
            if (!$this->getSetting($settings, 'show_compare_button', true)) {
                $css .= '.shopglut-single-product.template1 .compare-btn { display: none !important; }';
            }
        }

        // Features Section
        if ($this->getSetting($settings, 'show_features_section', true)) {
            $css .= '.shopglut-single-product.template1 .features-section {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'features_background_color', '#f9fafb') . ' !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'features_border_radius', 12) . 'px !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'features_padding', 24) . 'px !important;';
            $css .= 'margin-top: 40px !important;';
            $css .= 'border: 1px solid #e2e8f0 !important;';
            $css .= '}';

            // Features Section Title
            if ($this->getSetting($settings, 'show_features_section_title', false)) {
                $css .= '.shopglut-single-product.template1 .features-title {';
                $css .= 'color: ' . $this->getSetting($settings, 'features_section_title_color', '#111827') . ' !important;';
                $css .= 'font-size: 24px !important;';
                $css .= 'font-weight: 700 !important;';
                $css .= 'text-align: center !important;';
                $css .= 'margin-bottom: 32px !important;';
                $css .= '}';
            }

            $grid_columns = $this->getSetting($settings, 'features_grid_columns', '4');
            $css .= '.shopglut-single-product.template1 .features-grid {';
            $css .= 'display: grid !important;';
            $css .= 'grid-template-columns: repeat(' . $grid_columns . ', 1fr) !important;';
            $css .= 'gap: ' . $this->getSetting($settings, 'features_gap', 20) . 'px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .feature-item {';
            $css .= 'text-align: ' . $this->getSetting($settings, 'feature_item_alignment', 'center') . ' !important;';
            $css .= 'padding: 16px !important;';
            $css .= '}';

            // Feature Icons
            $icon_display_mode = $this->getSetting($settings, 'feature_icon_display_mode', 'icon_with_background');
            $icon_width = $this->getSetting($settings, 'feature_icon_width', 48);
            $icon_height = $this->getSetting($settings, 'feature_icon_height', 48);
            $icon_size = $this->getSetting($settings, 'feature_icon_size', 32);

            $css .= '.shopglut-single-product.template1 .feature-icon {';

            // Set width and height based on display mode
            if ($icon_display_mode === 'icon_only') {
                $css .= 'width: auto !important;';
                $css .= 'height: auto !important;';
                $css .= 'padding: 0 !important;';
                $css .= 'background-color: transparent !important;';
            } elseif ($icon_display_mode === 'icon_circle') {
                $css .= 'width: ' . max($icon_width, $icon_height) . 'px !important;';
                $css .= 'height: ' . max($icon_width, $icon_height) . 'px !important;';
                $css .= 'border-radius: 50% !important;';
                $css .= 'background-color: ' . $this->getSetting($settings, 'feature_icon_background', '#f3f4f6') . ' !important;';
                $css .= 'padding: ' . $this->getSetting($settings, 'feature_icon_padding', 12) . 'px !important;';
            } else {
                // icon_with_background (default)
                $css .= 'width: ' . $icon_width . 'px !important;';
                $css .= 'height: ' . $icon_height . 'px !important;';
                $css .= 'border-radius: ' . $this->getSetting($settings, 'feature_icon_border_radius', 8) . 'px !important;';
                $css .= 'background-color: ' . $this->getSetting($settings, 'feature_icon_background', '#f3f4f6') . ' !important;';
                $css .= 'padding: ' . $this->getSetting($settings, 'feature_icon_padding', 8) . 'px !important;';
            }

            // Icon font size and color
            $css .= 'font-size: ' . $icon_size . 'px !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_icon_color', '#8b5cf6') . ' !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'feature_title_margin_top', 12) . 'px !important;';
            $css .= 'display: inline-flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'justify-content: center !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';

            // Custom image icons
            $css .= '.shopglut-single-product.template1 .feature-icon img {';
            if ($icon_display_mode === 'icon_only') {
                $css .= 'width: ' . $icon_size . 'px !important;';
                $css .= 'height: ' . $icon_size . 'px !important;';
            } else {
                $css .= 'width: ' . ($icon_size - 8) . 'px !important;';
                $css .= 'height: ' . ($icon_size - 8) . 'px !important;';
            }
            $css .= 'object-fit: contain !important;';
            $css .= '}';

            // Feature Titles
            $css .= '.shopglut-single-product.template1 .feature-title {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_title_color', '#111827') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'feature_title_font_size', 16) . 'px !important;';
            $css .= 'font-weight: ' . $this->getSetting($settings, 'feature_title_font_weight', '600') . ' !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'feature_description_margin_top', 6) . 'px !important;';
            $css .= 'margin-top: ' . $this->getSetting($settings, 'feature_title_margin_top', 12) . 'px !important;';
            $css .= '}';

            // Feature Descriptions
            $css .= '.shopglut-single-product.template1 .feature-description {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_description_color', '#6b7280') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'feature_description_font_size', 14) . 'px !important;';
            $css .= 'line-height: ' . $this->getSetting($settings, 'feature_description_line_height', 1.5) . ' !important;';
            $css .= '}';

            // Feature Links
            $css .= '.shopglut-single-product.template1 .feature-item a {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_link_color', '#8b5cf6') . ' !important;';
            $css .= 'text-decoration: ' . $this->getSetting($settings, 'feature_link_decoration', 'none') . ' !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .feature-item a:hover {';
            $css .= 'color: ' . $this->getSetting($settings, 'feature_link_hover_color', '#7c3aed') . ' !important;';
            $css .= '}';
        }

        // Related Products Section
        if ($this->getSetting($settings, 'show_related_products', true)) {
            // Related Products Section Container
            $css .= '.shopglut-single-product.template1 .related-products-section {';
            $css .= 'margin-top: 60px !important;';
            $css .= 'padding-top: 40px !important;';
            $css .= '}';

            // Related Products Title
            $css .= '.shopglut-single-product.template1 .related-products-title {';
            $css .= 'color: ' . $this->getSetting($settings, 'related_section_title_color', '#111827') . ' !important;';
            $css .= '}';


            // Products Grid - Use flexbox for better responsive behavior
            $products_per_row = $this->getSetting($settings, 'related_products_per_row', '4');
            $css .= '.shopglut-single-product.template1 .related-products-grid {';
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
            $css .= '.shopglut-single-product.template1 .related-product-card {';
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
                $css .= '.shopglut-single-product.template1 .related-product-card:hover {';
                $css .= 'box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;';
                $css .= 'transform: translateY(-2px) !important;';
                $css .= '}';
            }

            // Product Images
            $css .= '.shopglut-single-product.template1 .related-product-image {';
            $css .= 'width: 100% !important;';
            $css .= 'height: 180px !important;';
            $css .= 'border-radius: 12px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= 'position: relative !important;';
            $css .= 'overflow: hidden !important;';
            $css .= 'background: #f8f9fa !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .related-product-img {';
            $css .= 'width: 100% !important;';
            $css .= 'height: 100% !important;';
            $css .= 'object-fit: cover !important;';
            $css .= 'object-position: center !important;';
            $css .= 'border-radius: 12px !important;';
            $css .= '}';

            // Product Badges
            $css .= '.shopglut-single-product.template1 .related-product-badge {';
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
            $css .= '.shopglut-single-product.template1 .related-product-name {';
            $css .= 'font-size: 16px !important;';
            $css .= 'font-weight: 600 !important;';
            $css .= 'color: #1e293b !important;';
            $css .= 'margin-bottom: 8px !important;';
            $css .= 'line-height: 1.3 !important;';
            $css .= '}';

            // Product Ratings
            $css .= '.shopglut-single-product.template1 .related-product-rating {';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'gap: 8px !important;';
            $css .= 'margin-bottom: 12px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .related-product-rating .stars {';
            $css .= 'color: #fbbf24 !important;';
            $css .= 'font-size: 14px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .related-product-rating .count {';
            $css .= 'color: #94a3b8 !important;';
            $css .= 'font-size: 12px !important;';
            $css .= '}';

            // Product Prices
            $css .= '.shopglut-single-product.template1 .related-product-price {';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'gap: 8px !important;';
            $css .= 'margin-bottom: 16px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .related-current-price {';
            $css .= 'font-size: 18px !important;';
            $css .= 'font-weight: 700 !important;';
            $css .= 'color: #059669 !important;';
            $css .= '}';

            $css .= '.shopglut-single-product.template1 .related-original-price, ';
            $css .= '.shopglut-single-product.template1 .related-product-price .original {';
            $css .= 'font-size: 14px !important;';
            $css .= 'color: #94a3b8 !important;';
            $css .= 'text-decoration: line-through !important;';
            $css .= 'font-weight: 400 !important;';
            $css .= '}';

            // Quick Add Button
            $css .= '.shopglut-single-product.template1 .quick-add-btn {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'quick_add_button_background', '#8b5cf6') . ' !important;';
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

            $css .= '.shopglut-single-product.template1 .quick-add-btn:hover {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'quick_add_button_hover_background', '#5a67d8') . ' !important;';
            $css .= 'transform: translateY(-2px) !important;';
            $css .= '}';

            // Responsive styles for Related Products
            // Tablet devices - show 3 products per row
            $css .= '@media (max-width: 1024px) {';
            $css .= '.shopglut-single-product.template1 .related-products-grid {';
            if ($products_per_row == '2') {
                $css .= 'flex-wrap: wrap !important;';
            } else {
                $css .= 'grid-template-columns: repeat(3, 1fr) !important;';
            }
            $css .= '}';
            $css .= '}';

            // Small tablet devices - show 2 products per row
            $css .= '@media (max-width: 768px) {';
            $css .= '.shopglut-single-product.template1 .related-products-grid {';
            if ($products_per_row == '2') {
                $css .= 'flex-wrap: wrap !important;';
            } else {
                $css .= 'grid-template-columns: repeat(2, 1fr) !important;';
            }
            $css .= '}';
            $css .= '}';

            // Mobile devices - show 2 products per row
            $css .= '@media (max-width: 576px) {';
            $css .= '.shopglut-single-product.template1 .related-products-grid {';
            if ($products_per_row == '2') {
                $css .= 'flex-wrap: wrap !important;';
            } else {
                $css .= 'grid-template-columns: repeat(2, 1fr) !important;';
                $css .= 'gap: 12px !important;';
            }
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .related-product-image {';
            $css .= 'height: 140px !important;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .related-product-name {';
            $css .= 'font-size: 14px !important;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .related-current-price {';
            $css .= 'font-size: 16px !important;';
            $css .= '}';
            $css .= '.shopglut-single-product.template1 .quick-add-btn {';
            $css .= 'padding: 10px 16px !important;';
            $css .= 'font-size: 13px !important;';
            $css .= '}';
            $css .= '}';
        }

        // Product Tabs Section - WooCommerce tabs structure with custom styling
        // Tabs Container
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs {';
        $css .= 'margin-top: 40px;';
        $css .= 'margin-bottom: 20px;';
        $css .= '}';

        // Tabs Navigation (ul)
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs {';
        $css .= 'list-style: none;';
        $css .= 'margin: 0;';
        $css .= 'padding: 0;';
        $css .= 'display: flex;';
        $css .= 'flex-wrap: wrap;';
        $css .= 'gap: 4px;';
        $css .= 'border-bottom: 2px solid #e5e7eb;';
        $css .= '}';

        // Tab Items (li)
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li {';
        $css .= 'margin: 0;';
        $css .= 'padding: 0;';
        $css .= 'list-style: none;';
        $css .= '}';

        // Tab Links (a)
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li a {';
        $css .= 'display: inline-flex;';
        $css .= 'align-items: center;';
        $css .= 'gap: 8px;';
        $css .= 'padding: 12px 20px;';
        $css .= 'text-decoration: none;';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_color', '#374151') . ';';
        $css .= 'font-size: ' . $this->getSetting($settings, 'tab_title_font_size', 15) . 'px;';
        $css .= 'font-weight: ' . $this->getSetting($settings, 'tab_title_font_weight', '500') . ';';
        $css .= 'background-color: ' . $this->getSetting($settings, 'tab_title_background_color', '#f3f4f6') . ';';
        $css .= 'transition: all 0.3s ease;';
        $css .= 'border-bottom: 3px solid transparent;';
        $css .= 'margin-bottom: -2px;';
        $css .= '}';

        // Tab Icon inside links
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li a i {';
        $css .= 'font-size: ' . $this->getSetting($settings, 'tab_icon_size', 16) . 'px;';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_color', '#6b7280') . ';';
        $css .= 'transition: color 0.3s ease;';
        $css .= '}';

        // Tab Hover
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li a:hover {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_hover_color', '#667eea') . ';';
        $css .= 'background-color: ' . $this->getSetting($settings, 'tab_title_hover_background_color', '#e5e7eb') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li a:hover i {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_hover_color', '#667eea') . ';';
        $css .= '}';

        // Active Tab
        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li.active a {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ';';
        $css .= 'background-color: ' . $this->getSetting($settings, 'tab_title_active_background_color', '#ffffff') . ';';
        $css .= 'border-bottom-color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-tabs ul.tabs li.active a i {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_active_color', '#667eea') . ';';
        $css .= '}';

        // Tab Content Panels
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel {';
        $css .= 'display: none;';
        $css .= 'padding: 25px 0;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel.active {';
        $css .= 'display: block;';
        $css .= '}';

        // Custom tab content styling
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content p:last-child {';
        $css .= 'margin-bottom: 0;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content h1, ';
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content h2, ';
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content h3, ';
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content h4 {';
        $css .= 'margin-top: 0;';
        $css .= 'margin-bottom: 10px;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content ul, ';
        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content ol {';
        $css .= 'margin-bottom: 15px;';
        $css .= 'padding-left: 20px;';
        $css .= '}';

        $css .= '.shopglut-single-product.template1 .woocommerce-Tabs-panel .custom-tab-content li {';
        $css .= 'margin-bottom: 5px;';
        $css .= '}';

        // Responsive adjustments for features grid
        $css .= '@media (max-width: 768px) {';
        $css .= '.shopglut-single-product.template1 .features-grid {';
        $css .= 'grid-template-columns: repeat(2, 1fr);';
        $css .= '}';
        $css .= '.shopglut-single-product.template1 .related-products-grid {';
        $css .= 'grid-template-columns: repeat(2, 1fr);';
        $css .= '}';
        $css .= '}';

        $css .= '@media (max-width: 480px) {';
        $css .= '.shopglut-single-product.template1 .features-grid {';
        $css .= 'grid-template-columns: 1fr;';
        $css .= '}';
        $css .= '.shopglut-single-product.template1 .related-products-grid {';
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
     * Convert hex color to rgba format
     */
    private function hexToRgba($hex, $alpha = 1) {
        // Remove hash if present
        $hex = ltrim($hex, '#');

        // Parse the hex values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
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
            if (isset($settings['shopg_singleproduct_settings_template1']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template1']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template1']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template1']['cart-page-settings']);
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
            'main_image_object_fit' => 'cover',
            'main_image_border_radius' => 8,
            'main_image_border_color' => '#e5e7eb',
            'main_image_border_width' => 1,
            'main_image_padding' => 14,
            'main_image_shadow' => true,
            'main_image_shadow_color' => 'rgba(0,0,0,0.1)',
            'main_image_margin_bottom' => 20,
            'main_image_cursor' => 'zoom-in',
            'enable_shimmer_effect' => false,
            'shimmer_speed' => 3,
            'shimmer_opacity' => 20,
            'main_image_hover_scale' => false,
            'main_image_hover_scale_value' => 1.05,
            'main_image_hover_brightness' => false,
            'main_image_hover_brightness_value' => 110,
            'show_thumbnails' => true,
            'thumbnail_alignment' => 'flex-start',
            'thumbnail_size' => 80,
            'thumbnail_border_radius' => 6,
            'thumbnail_spacing' => 8,
            'thumbnail_border_width' => 2,
            'thumbnail_border_color' => 'transparent',
            'thumbnail_active_border' => '#667eea',
            'thumbnail_hover_border' => '#2563eb',
            'thumbnail_opacity' => 70,
            'thumbnail_hover_scale' => true,
            'thumbnail_gallery_margin_top' => 16,
            'enable_image_lightbox' => true,
            'enable_image_hover_zoom' => false,
            'hover_zoom_level' => 2,

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
            'current_price_color' => '#ffffff',
            'current_price_font_size' => 28,
            'price_background_color' => 'transparent',
            'original_price_color' => '#9ca3af',
            'discount_badge_color' => '#ef4444',
            'discount_badge_text_color' => '#ffffff',

            // Description
            'show_description' => true,
            'description_color' => '#6b7280',
            'description_font_size' => 16,
            'description_line_height' => 1.6,

            // Purchase Section
            'quantity_button_background' => '#f3f4f6',
            'quantity_button_text_color' => '#374151',
            'quantity_input_background' => '#ffffff',
            'quantity_input_border' => '#d1d5db',
            'quantity_border_radius' => 6,
            'cart_button_background' => '#8b5cf6',
            'cart_button_text_color' => '#ffffff',
            'cart_button_hover_background' => '#7c3aed',
            'cart_button_border_radius' => 8,
            'cart_button_font_size' => 16,
            'cart_button_font_weight' => '600',
            'show_wishlist_button' => true,
            'show_compare_button' => true,
            'secondary_button_color' => '#6b7280',
            'secondary_button_hover_color' => '#8b5cf6',

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
            'feature_icon_color' => '#8b5cf6',
            'feature_icon_background' => '#f3f4f6',
            'feature_icon_padding' => 8,
            'feature_icon_border_radius' => 8,
            'feature_icon_display_mode' => 'icon_with_background',
            'feature_icon_width' => 48,
            'feature_icon_height' => 48,
            'feature_title_color' => '#111827',
            'feature_title_font_size' => 16,
            'feature_title_font_weight' => '600',
            'feature_title_margin_top' => 12,
            'feature_description_color' => '#6b7280',
            'feature_description_font_size' => 14,
            'feature_description_line_height' => 1.5,
            'feature_description_margin_top' => 6,
            'feature_link_color' => '#8b5cf6',
            'feature_link_hover_color' => '#7c3aed',
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
            'quick_add_button_background' => '#8b5cf6',
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

            // Product Tabs
            'tab_icon_size' => 16,
            'tab_icon_color' => '#6b7280',
            'tab_icon_hover_color' => '#667eea',
            'tab_icon_active_color' => '#667eea',
            'tab_title_color' => '#374151',
            'tab_title_hover_color' => '#667eea',
            'tab_title_active_color' => '#667eea',
            'tab_title_background_color' => '#f3f4f6',
            'tab_title_hover_background_color' => '#e5e7eb',
            'tab_title_active_background_color' => '#ffffff',
            'tab_title_font_size' => 15,
            'tab_title_font_weight' => '500',
        );
    }

}
