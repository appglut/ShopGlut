<?php
namespace Shopglut\layouts\singleProduct\templates\template2;

class template2Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
       <style>
        /* Settings-based Dynamic CSS */
        <?php echo wp_kses($this->generateSettingsBasedCSS($settings), array()); ?>

        .single-product-template2 {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .single-product-template2 .container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 10px;
            width: 100%;
            overflow-x: visible;
            box-sizing: border-box;
        }

        /* Main Product Layout */
        .single-product-template2 .product-page {
            background: white;
            border-radius: 12px;
            margin: 15px 0;
            overflow: visible;
            width: 100%;
            box-sizing: border-box;
        }

        .single-product-template2 .product-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35px;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            overflow: visible;
        }

        /* Left Side - Product Image */
        .single-product-template2 .product-image {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Thumbnail Gallery - Base styles (dynamic settings will override) */
        .single-product-template2 .thumbnail-gallery {
            display: flex;
            /* gap, margin-top, justify-content set by dynamic settings */
            width: 100%;
            max-width: 550px;
        }

        .single-product-template2 .thumbnail-item {
            /* width, height, border-radius, border, opacity set by dynamic settings */
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .single-product-template2 .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Main Product Image - Base styles (dynamic settings will override) */
        .single-product-template2 .main-product-image {
            /* background, border-radius, border, padding, margin set by dynamic settings */
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single-product-template2 .main-product-image img {
            /* object-fit, cursor, shadow set by dynamic settings */
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        /* Right Side - Product Info */
        .single-product-template2 .product-info {
            padding: 20px 0;
            display: block;
            width: 100%;
            max-width: 100%;
            overflow-x: visible;
            box-sizing: border-box;
        }

        .single-product-template2 .product-info * {
            box-sizing: border-box;
            max-width: 100%;
        }

        .single-product-template2 .product-info h1,
        .single-product-template2 .product-info h2,
        .single-product-template2 .product-info h3,
        .single-product-template2 .product-info p {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        /* Breadcrumb */
        .single-product-template2 .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px !important;
            color: #666;
        }

        .single-product-template2 .breadcrumb a {
            color: #0073aa;
            text-decoration: none;
            transition: color 0.3s;
        }

        .single-product-template2 .breadcrumb a:hover {
            color: #005a87;
        }

        .single-product-template2 .breadcrumb .separator {
            color: #999;
        }

         .single-product-template2 .breadcrumb span {
            font-size:14px;
        }

        /* Reviews Section */
        .single-product-template2 .reviews-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .single-product-template2 .rating-stars {
            display: flex;
            gap: 2px;
            color: #ffc107;
            font-size: 18px;
        }

        .single-product-template2 .reviews-count {
            color: #666;
            font-size: 16px;
        }

        /* Product Title */
        .single-product-template2 .product-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #1a1a1a;
            text-align:left;
        }

        /* Price */
        .single-product-template2 .price-section {
            margin-bottom: 20px;
        }

        .single-product-template2 .current-price {
            font-size: 36px;
            font-weight: 700;
            color: #28a745;
        }

        .single-product-template2 .original-price {
            font-size: 24px;
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
        }

        /* Short Description */
        .single-product-template2 .short-description {
            font-size: 16px;
            line-height: 1.7;
            color: #555;
            margin-bottom: 30px;
        }

        /* Cart Actions Row */
        .single-product-template2 .cart-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            width: 100%;
            flex-wrap: wrap;
            justify-content: space-around;
            
        }

        /* Quantity Selector - Clean Modern Design */
        .single-product-template2 .quantity-selector,
        .shopglut-single-product-container .quantity-selector {
            display: inline-flex;
            align-items: stretch;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .single-product-template2 .quantity-btn,
        .shopglut-single-product-container .qty-decrease,
        .shopglut-single-product-container .qty-increase {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            border: none;
            width: 44px;
            height: 44px;
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
            line-height: 1;
        }

        .single-product-template2 .quantity-btn:hover,
        .single-product-template2 .qty-decrease:hover,
        .single-product-template2 .qty-increase:hover,
        .shopglut-single-product-container .qty-decrease:hover,
        .shopglut-single-product-container .qty-increase:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .single-product-template2 .quantity-btn:active,
        .shopglut-single-product-container .qty-decrease:active,
        .shopglut-single-product-container .qty-increase:active {
            background: #d1d5db;
        }

        .single-product-template2 .quantity-input,
        .single-product-template2 .qty-input,
        .shopglut-single-product-container .qty-input {
            border: none;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            text-align: center;
            width: 64px;
            height: 44px;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            background: #ffffff;
            outline: none;
            padding: 0;
            -moz-appearance: textfield;
            appearance: none;
        }

        .single-product-template2 .quantity-input::-webkit-inner-spin-button,
        .single-product-template2 .quantity-input::-webkit-outer-spin-button,
        .single-product-template2 .qty-input::-webkit-inner-spin-button,
        .single-product-template2 .qty-input::-webkit-outer-spin-button,
        .shopglut-single-product-container .qty-input::-webkit-inner-spin-button,
        .shopglut-single-product-container .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .single-product-template2 .quantity-input:focus,
        .single-product-template2 .qty-input:focus,
        .shopglut-single-product-container .qty-input:focus {
            background: #f9fafb;
        }

        .single-product-template2 .add-to-cart {
            background: #0073aa;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .single-product-template2 .add-to-cart:hover {
            background: #005a87;
        }

        .single-product-template2 .wishlist-btn {
            background: white;
            color: #666;
            border: 2px solid #e0e0e0;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .single-product-template2 .wishlist-btn:hover {
            border-color: #dc3545;
            color: #dc3545;
        }

        /* Comparison Button */
        .single-product-template2 .comparison-btn {
            background: white;
            color: #666;
            border: 2px solid #e0e0e0;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .single-product-template2 .comparison-btn:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        .single-product-template2 .comparison-btn i {
            font-size: 16px;
        }

        /* Integrated Comparison Module Button */
        .single-product-template2 .shopglut-add-to-comparison-single {
            background: white;
            color: #666;
            border: 2px solid #e0e0e0;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .single-product-template2 .shopglut-add-to-comparison-single:hover {
            border-color: #0073aa;
            color: #0073aa;
        }

        .single-product-template2 .shopglut-add-to-comparison-single.product-added-to-comparison {
            background: #0073aa;
            color: white;
            border-color: #0073aa;
        }

        /* Buy Now Button */
        .single-product-template2 .buy-now-btn {
            width: 100%;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Bottom border after Buy Now */
        .single-product-template2 .buy-now-border {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
            margin: 0 0 30px 0;
        }

        .single-product-template2 .buy-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        /* Product Metadata */
        .single-product-template2 .product-meta {
            margin-bottom: 40px;
        }

        .single-product-template2 .meta-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .single-product-template2 .meta-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            min-width: 80px;
        }

        .single-product-template2 .meta-value {
            font-size: 14px;
            color: #666;
        }

        .single-product-template2 .meta-value a {
            color: #0073aa;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s;
        }

        .single-product-template2 .meta-value a:hover {
            color: #005a87;
        }

        .single-product-template2 .share-icons {
            display: flex;
            gap: 10px;
        }

        .single-product-template2 .share-icon {
            width: 36px;
            height: 36px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }

        .single-product-template2 .share-icon:hover {
            background: #0073aa;
            color: white;
        }

        /* Tabs Section */
        .single-product-template2 .tabs-section {
            background: white;
            border-radius: 12px;
            
            margin: 40px 0;
            overflow: hidden;
        }

        .single-product-template2 .tabs-container {
            display: grid;
            grid-template-columns: 250px 1fr;
        }

        /* Vertical Tabs Navigation */
        .single-product-template2 .tabs-nav {
            background: #f8f9fa;
            padding: 0;
            border-right: 1px solid #e0e0e0;
        }

        .single-product-template2 .tab-button {
            width: 100%;
            padding: 20px 30px;
            background: none;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .single-product-template2 .tab-button i {
            font-size: 18px;
            width: 20px;
            text-align: left;
        }

        .single-product-template2 .tab-button:last-child {
            border-bottom: none;
        }

        .single-product-template2 .tab-button:hover {
            background: white;
            color: #0073aa;
        }

        .single-product-template2 .tab-button.active {
            background: white;
            color: #0073aa;
            border-left: 4px solid #0073aa;
        }

        /* Tab Content */
        .single-product-template2 .tab-content {
            padding: 40px;
            display: none;
        }

        .single-product-template2 .tab-content.active {
            display: block;
        }

        .single-product-template2 .tab-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .single-product-template2 .tab-description {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }

        /* WooCommerce Tabs Styling - Enhanced */
        .woocommerce-tabs-section,
        .shopglut-single-product-container .woocommerce-tabs-section {
            background: white;
            border-radius: 12px;
            margin: 40px 0;
            overflow: hidden;
            clear: both;
        }

        .woocommerce-tabs,
        .shopglut-single-product-container .woocommerce-tabs {
            margin: 0;
            position: relative;
        }

        .woocommerce-tabs .wc-tabs,
        .shopglut-single-product-container .woocommerce-tabs .wc-tabs {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            flex-wrap: wrap;
            position: relative;
            z-index: 10;
        }

        .woocommerce-tabs .wc-tabs li,
        .shopglut-single-product-container .woocommerce-tabs .wc-tabs li {
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            display: block !important;
            float: none !important;
        }

        .woocommerce-tabs .wc-tabs li a,
        .shopglut-single-product-container .woocommerce-tabs .wc-tabs li a {
            display: block !important;
            padding: 18px 28px;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            float: none !important;
        }

        .woocommerce-tabs .wc-tabs li a:hover,
        .shopglut-single-product-container .woocommerce-tabs .wc-tabs li a:hover {
            color: #0073aa;
            background: white;
        }

        .woocommerce-tabs .wc-tabs li.active a,
        .shopglut-single-product-container .woocommerce-tabs .wc-tabs li.active a {
            color: #0073aa;
            background: white;
            border-bottom-color: #0073aa;
        }

        /* Hide all panels by default, show only active - Matches template1 pattern */
        .woocommerce-tabs-section .woocommerce-Tabs-panel,
        .woocommerce-tabs .woocommerce-Tabs-panel,
        .wc-tabs-wrapper .woocommerce-Tabs-panel,
        .woocommerce-Tabs-panel.panel,
        .woocommerce-tabs-section .wc-tab,
        .woocommerce-tabs .wc-tab,
        .wc-tabs-wrapper .wc-tab {
            padding: 35px 40px;
            background: white;
            display: none !important;
        }

        /* Show only active panel - Matches template1 pattern */
        .woocommerce-tabs-section .woocommerce-Tabs-panel.active,
        .woocommerce-tabs .woocommerce-Tabs-panel.active,
        .wc-tabs-wrapper .woocommerce-Tabs-panel.active,
        .woocommerce-Tabs-panel.panel.active,
        .woocommerce-Tabs-panel.wc-tab.active,
        .woocommerce-tabs-section .wc-tab.active,
        .woocommerce-tabs .wc-tab.active,
        .wc-tabs-wrapper .wc-tab.active {
            display: block !important;
        }

        /* Tab Content Typography */
        .woocommerce-Tabs-panel h2,
        .shopglut-single-product-container .woocommerce-Tabs-panel h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .woocommerce-Tabs-panel h3,
        .shopglut-single-product-container .woocommerce-Tabs-panel h3 {
            font-size: 18px;
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 12px;
            color: #333;
        }

        .woocommerce-Tabs-panel h4,
        .shopglut-single-product-container .woocommerce-Tabs-panel h4 {
            font-size: 16px;
            font-weight: 600;
            margin-top: 18px;
            margin-bottom: 10px;
            color: #444;
        }

        .woocommerce-Tabs-panel p,
        .shopglut-single-product-container .woocommerce-Tabs-panel p {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 16px;
        }

        .woocommerce-Tabs-panel ul,
        .woocommerce-Tabs-panel ol,
        .shopglut-single-product-container .woocommerce-Tabs-panel ul,
        .shopglut-single-product-container .woocommerce-Tabs-panel ol {
            margin: 16px 0;
            padding-left: 24px;
        }

        .woocommerce-Tabs-panel li,
        .shopglut-single-product-container .woocommerce-Tabs-panel li {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 10px;
        }

        /* Tables in tabs (Additional Information) */
        .woocommerce-Tabs-panel table,
        .shopglut-single-product-container .woocommerce-Tabs-panel table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 15px;
        }

        .woocommerce-Tabs-panel table tr,
        .shopglut-single-product-container .woocommerce-Tabs-panel table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .woocommerce-Tabs-panel table tr:last-child,
        .shopglut-single-product-container .woocommerce-Tabs-panel table tr:last-child {
            border-bottom: none;
        }

        .woocommerce-Tabs-panel table td,
        .woocommerce-Tabs-panel table th,
        .shopglut-single-product-container .woocommerce-Tabs-panel table td,
        .shopglut-single-product-container .woocommerce-Tabs-panel table th {
            padding: 14px 16px;
            text-align: left;
        }

        .woocommerce-Tabs-panel table th,
        .shopglut-single-product-container .woocommerce-Tabs-panel table th {
            font-weight: 600;
            color: #333;
            width: 30%;
            background: #f9fafb;
        }

        .woocommerce-Tabs-panel table td,
        .shopglut-single-product-container .woocommerce-Tabs-panel table td {
            color: #555;
        }

        /* Review Section in Tabs */
        .woocommerce-Tabs-panel #reviews,
        .shopglut-single-product-container .woocommerce-Tabs-panel #reviews {
            margin-top: 20px;
        }

        .woocommerce-Tabs-panel .commentlist,
        .shopglut-single-product-container .woocommerce-Tabs-panel .commentlist {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .woocommerce-Tabs-panel .commentlist li,
        .shopglut-single-product-container .woocommerce-Tabs-panel .commentlist li {
            padding: 20px;
            margin-bottom: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .woocommerce-Tabs-panel .commentlist li .meta,
        .shopglut-single-product-container .woocommerce-Tabs-panel .commentlist li .meta {
            font-size: 13px;
            color: #888;
            margin-bottom: 8px;
        }

        .woocommerce-Tabs-panel .commentlist li .star-rating,
        .shopglut-single-product-container .woocommerce-Tabs-panel .commentlist li .star-rating {
            color: #fbbf24;
            margin-bottom: 10px;
        }

        .woocommerce-Tabs-panel .comment-form,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form {
            margin-top: 30px;
            padding: 24px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .woocommerce-Tabs-panel .comment-form label,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .woocommerce-Tabs-panel .comment-form input,
        .woocommerce-Tabs-panel .comment-form textarea,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form input,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .woocommerce-Tabs-panel .comment-form input:focus,
        .woocommerce-Tabs-panel .comment-form textarea:focus,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form input:focus,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form textarea:focus {
            outline: none;
            border-color: #0073aa;
        }

        .woocommerce-Tabs-panel .comment-form-submit,
        .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form-submit {
            margin-top: 10px;
        }

        /* Product Description Content */
        .product-description-content,
        .shopglut-single-product-container .product-description-content {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
        }

        .product-description-content img,
        .shopglut-single-product-container .product-description-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }

        /* Links in tab content */
        .woocommerce-Tabs-panel a,
        .shopglut-single-product-container .woocommerce-Tabs-panel a {
            color: #0073aa;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .woocommerce-Tabs-panel a:hover,
        .shopglut-single-product-container .woocommerce-Tabs-panel a:hover {
            color: #005a87;
            text-decoration: underline;
        }

        /* Blockquotes in tab content */
        .woocommerce-Tabs-panel blockquote,
        .shopglut-single-product-container .woocommerce-Tabs-panel blockquote {
            border-left: 4px solid #0073aa;
            padding-left: 20px;
            margin: 20px 0;
            color: #666;
            font-style: italic;
        }

        /* ========== PRODUCT ATTRIBUTES STYLES ========== */

        /* Product Attributes Container */
        .single-product-template2 .product-attributes-wrapper,
        .shopglut-single-product-container .product-attributes-wrapper {
            margin-bottom: 20px;
            width: 100%;
            overflow: visible;
        }

        .single-product-template2 .product-attributes,
        .shopglut-single-product-container .product-attributes {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            width: 100%;
            overflow: visible;
            padding-left: 4px;
        }

        /* Attribute Labels */
        .single-product-template2 .attribute-label,
        .shopglut-single-product-container .attribute-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            text-transform: capitalize;
            display: block;
            width: 100%;
        }

        /* Attribute Options Container */
        .single-product-template2 .attribute-options,
        .shopglut-single-product-container .attribute-options {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            width: 100%;
            overflow: visible;
            justify-content: flex-start;
            padding-left: 3px;
        }

        /* Attribute Option Items - Base Styles */
        .single-product-template2 .attribute-option,
        .shopglut-single-product-container .attribute-option {
            padding: 10px 20px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 60px;
            text-align: center;
            user-select: none;
        }

        /* Color Attribute Options - Special Styles */
        .single-product-template2 .attribute-option[style*="background-color"],
        .shopglut-single-product-container .attribute-option[style*="background-color"] {
            width: 40px;
            height: 40px;
            min-width: 40px;
            padding: 0;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* White color special handling */
        .single-product-template2 .attribute-option[style*="background-color: #ffffff"],
        .single-product-template2 .attribute-option[style*="background-color:#ffffff"],
        .single-product-template2 .attribute-option[style*="background-color: white"],
        .single-product-template2 .attribute-option[style*="background-color:rgb(255, 255, 255)"],
        .shopglut-single-product-container .attribute-option[style*="background-color: #ffffff"],
        .shopglut-single-product-container .attribute-option[style*="background-color:#ffffff"],
        .shopglut-single-product-container .attribute-option[style*="background-color: white"],
        .shopglut-single-product-container .attribute-option[style*="background-color:rgb(255, 255, 255)"] {
            border-color: #d1d5db;
        }

        /* Selected State */
        .single-product-template2 .attribute-option.selected,
        .shopglut-single-product-container .attribute-option.selected {
            border-color: #667eea !important;
            background-color: #667eea !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            transform: scale(1.05);
        }

        /* Selected State for Color Options */
        .single-product-template2 .attribute-option[style*="background-color"].selected,
        .shopglut-single-product-container .attribute-option[style*="background-color"].selected {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3), 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Hover State */
        .single-product-template2 .attribute-option:hover,
        .shopglut-single-product-container .attribute-option:hover {
            border-color: #667eea;
            background-color: #f9fafb;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Hover State for Color Options */
        .single-product-template2 .attribute-option[style*="background-color"]:hover,
        .shopglut-single-product-container .attribute-option[style*="background-color"]:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }

        /* Focus State for Accessibility */
        .single-product-template2 .attribute-option:focus,
        .shopglut-single-product-container .attribute-option:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        /* ========== END PRODUCT ATTRIBUTES STYLES ========== */

        /* Code blocks in tab content */
        .woocommerce-Tabs-panel code,
        .woocommerce-Tabs-panel pre,
        .shopglut-single-product-container .woocommerce-Tabs-panel code,
        .shopglut-single-product-container .woocommerce-Tabs-panel pre {
            background: #f4f4f4;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
            overflow-x: auto;
        }

        .woocommerce-Tabs-panel pre,
        .shopglut-single-product-container .woocommerce-Tabs-panel pre {
            margin: 16px 0;
        }

        /* Related Products Slider */
        .single-product-template2 .related-products {
            background: white;
            border-radius: 12px;
            
            margin: 40px 0;
            padding: 40px;
        }

        .single-product-template2 .related-products h2 {
            font-size: 28px !important;
            margin-bottom: 30px;
            text-align: left;
            color: #1a1a1a;
            font-weight:600;
        }

        .single-product-template2 .products-slider {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px 0;
        }

        .single-product-template2 .related-product {
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
            min-width: auto;
        }

        .single-product-template2 .related-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .single-product-template2 .related-product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .single-product-template2 .related-product-info {
            padding: 20px;
        }

        .single-product-template2 .related-product-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1a1a1a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .single-product-template2 .related-product-price {
            font-size: 20px;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 15px;
        }

        .single-product-template2 .add-related-btn {
            width: 100%;
            padding: 10px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .single-product-template2 .add-related-btn:hover {
            background: #005a87;
        }

        /* ========== ADMIN PREVIEW SPECIFIC STYLES ========== */

        /* Admin Preview Mode Styles */
        .single-product-template2 .demo-content {
            width: 100%;
            overflow-x: hidden;
        }

        .single-product-template2 .demo-content.responsive-preview {
            min-width: 320px;
            max-width: 100%;
            overflow-x: hidden;
        }

        .single-product-template2 .single-product-container {
            width: 100%;
            overflow-x: hidden;
        }

        .single-product-template2 .live-content {
            width: 100%;
            overflow-x: hidden;
        }

        /* Admin Preview Container Adjustments */
        .single-product-template2 .demo-content .container {
            max-width: 100%;
            padding: 0 15px;
            overflow-x: hidden;
        }

        .single-product-template2 .live-content .container {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Responsive Preview Specific Adjustments - FORCE SINGLE COLUMN */
        .single-product-template2 .responsive-preview .container {
            min-width: 280px;
            max-width: 100%;
            overflow-x: hidden;
        }

        .single-product-template2 .responsive-preview .product-page {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* CRITICAL: Force single column layout in preview mode */
        .single-product-template2 .demo-content .product-container,
        .single-product-template2 .responsive-preview .product-container {
            display: grid;
            grid-template-columns: 1fr !important;
            gap: 15px;
            min-width: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Constrain image width in preview */
        .single-product-template2 .demo-content .product-image,
        .single-product-template2 .responsive-preview .product-image {
            min-width: 0;
            width: 100%;
            max-width: 100%;
        }

        .single-product-template2 .demo-content .main-product-image,
        .single-product-template2 .responsive-preview .main-product-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
        }

        .single-product-template2 .responsive-preview .product-info {
            min-width: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        .single-product-template2 .responsive-preview .product-info * {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Cart Actions Responsive Fix - Always use column layout in admin preview */
        .single-product-template2 .responsive-preview .cart-actions,
        .single-product-template2 .demo-content .cart-actions {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            width: 100%;
        }

        .single-product-template2 .responsive-preview .quantity-selector,
        .single-product-template2 .demo-content .quantity-selector {
            width: 100%;
            display: flex;
        }

        .single-product-template2 .responsive-preview .quantity-btn,
        .single-product-template2 .demo-content .quantity-btn {
            flex: 1;
            width: auto !important;
        }

        .single-product-template2 .responsive-preview .quantity-input,
        .single-product-template2 .demo-content .quantity-input {
            flex: 2;
            width: auto !important;
        }

        .single-product-template2 .responsive-preview .add-to-cart,
        .single-product-template2 .responsive-preview .wishlist-btn,
        .single-product-template2 .demo-content .add-to-cart,
        .single-product-template2 .demo-content .wishlist-btn {
            width: 100%;
            flex-shrink: 0;
        }

        /* Container Query Based Responsive Design */
        .single-product-template2 {
            container-type: inline-size;
        }

        .single-product-template2 .demo-content,
        .single-product-template2 .live-content {
            container-type: inline-size;
        }

        /* ========== COMPREHENSIVE RESPONSIVE CSS ========== */

        /* Extra Small Mobile (360px and below) */
        @media (max-width: 360px) {
            .single-product-template2 .container,
            .shopglut-single-product-container .container {
                padding: 0 8px;
            }

            .single-product-template2 .product-title,
            .shopglut-single-product-container .product-title {
                font-size: 20px;
            }

            .single-product-template2 .current-price,
            .shopglut-single-product-container .current-price {
                font-size: 24px;
            }

            .single-product-template2 .original-price,
            .shopglut-single-product-container .original-price {
                font-size: 16px;
            }

            .single-product-template2 .thumbnail-item,
            .shopglut-single-product-container .thumbnail-item {
                width: 55px !important;
                height: 55px !important;
            }

            .single-product-template2 .thumbnail-gallery,
            .shopglut-single-product-container .thumbnail-gallery {
                gap: 5px;
                justify-content: center;
            }

            /* Attribute Options - Extra Small Mobile */
            .single-product-template2 .attribute-options,
            .shopglut-single-product-container .attribute-options {
                gap: 5px;
            }

            .single-product-template2 .attribute-option,
            .shopglut-single-product-container .attribute-option {
                padding: 5px 10px;
                font-size: 11px;
                min-width: 40px;
            }

            .single-product-template2 .attribute-option[style*="background-color"],
            .shopglut-single-product-container .attribute-option[style*="background-color"] {
                width: 30px;
                height: 30px;
                min-width: 30px;
                border-width: 2px;
            }

            /* Social Icons - Extra Small Mobile */
            .single-product-template2 .share-icons,
            .shopglut-single-product-container .share-icons {
                gap: 5px;
            }

            .single-product-template2 .share-icon,
            .shopglut-single-product-container .share-icon {
                width: 28px !important;
                height: 28px !important;
                font-size: 11px;
            }

            .single-product-template2 .quantity-selector,
            .shopglut-single-product-container .quantity-selector {
                width: 100%;
                max-width: 200px;
            }

            .single-product-template2 .add-to-cart,
            .single-product-template2 .wishlist-btn,
            .shopglut-single-product-container .add-to-cart,
            .shopglut-single-product-container .wishlist-btn {
                font-size: 14px;
                padding: 12px 16px;
            }

            .single-product-template2 .buy-now-btn,
            .shopglut-single-product-container .buy-now-btn {
                font-size: 16px;
                padding: 14px;
            }
        }

        /* Small Mobile (480px and below) */
        @media (max-width: 480px) {
            .single-product-template2 .container,
            .shopglut-single-product-container .container {
                padding: 0 10px;
            }

            .single-product-template2 .product-page,
            .shopglut-single-product-container .product-page {
                margin: 10px 0;
                border-radius: 8px;
            }

            .single-product-template2 .product-container,
            .shopglut-single-product-container .product-container {
                padding: 20px 15px;
                gap: 25px;
            }

            .single-product-template2 .product-title,
            .shopglut-single-product-container .product-title {
                font-size: 22px;
            }

            .single-product-template2 .current-price,
            .shopglut-single-product-container .current-price {
                font-size: 26px;
            }

            .single-product-template2 .short-description,
            .shopglut-single-product-container .short-description {
                font-size: 15px;
            }

            .single-product-template2 .main-product-image,
            .shopglut-single-product-container .main-product-image {
                height: 250px;
            }

            .single-product-template2 .thumbnail-item,
            .shopglut-single-product-container .thumbnail-item {
                width: 60px !important;
                height: 60px !important;
            }

            .single-product-template2 .thumbnail-gallery,
            .shopglut-single-product-container .thumbnail-gallery {
                justify-content: center;
                gap: 6px;
            }

            /* Attribute Options - Small Mobile */
            .single-product-template2 .attribute-options,
            .shopglut-single-product-container .attribute-options {
                gap: 6px;
            }

            .single-product-template2 .attribute-option,
            .shopglut-single-product-container .attribute-option {
                padding: 6px 12px;
                font-size: 12px;
                min-width: 45px;
            }

            .single-product-template2 .attribute-option[style*="background-color"],
            .shopglut-single-product-container .attribute-option[style*="background-color"] {
                width: 32px;
                height: 32px;
                min-width: 32px;
                border-width: 2px;
            }

            /* Social Icons - Small Mobile */
            .single-product-template2 .share-icons,
            .shopglut-single-product-container .share-icons {
                gap: 6px;
            }

            .single-product-template2 .share-icon,
            .shopglut-single-product-container .share-icon {
                width: 30px !important;
                height: 30px !important;
                font-size: 12px;
            }

            .single-product-template2 .products-slider,
            .shopglut-single-product-container .products-slider {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .single-product-template2 .related-product,
            .shopglut-single-product-container .related-product {
                min-width: auto;
            }

            .single-product-template2 .related-product-title,
            .shopglut-single-product-container .related-product-title {
                font-size: 14px;
            }

            .single-product-template2 .related-product-price,
            .shopglut-single-product-container .related-product-price {
                font-size: 18px;
            }
        }

        /* Mobile (576px and below) */
        @media (max-width: 576px) {
            .single-product-template2 .container {
                padding: 0 12px;
            }

            .single-product-template2 .products-slider {
                grid-template-columns: 1fr;
            }

            .single-product-template2 .product-container {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 25px 18px;
            }

            .single-product-template2 .main-product-image {
                height: 280px;
            }

            .single-product-template2 .product-title {
                font-size: 24px;
            }

            .single-product-template2 .current-price {
                font-size: 28px;
            }

            .single-product-template2 .cart-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .single-product-template2 .quantity-selector {
                width: 100%;
                display: flex;
            }

            .single-product-template2 .quantity-btn {
                flex: 1;
                width: auto !important;
            }

            .single-product-template2 .quantity-input {
                flex: 2;
                width: auto !important;
            }

            .single-product-template2 .add-to-cart,
            .single-product-template2 .wishlist-btn {
                width: 100%;
            }

            .single-product-template2 .buy-now-btn {
                font-size: 16px;
                padding: 14px;
            }
        }

        /* Tablet and Below (768px and below) */
        @media (max-width: 768px) {
            .single-product-template2 .container {
                padding: 0 16px;
            }

            .single-product-template2 .product-page {
                margin: 12px 0;
            }

            .single-product-template2 .product-container {
                grid-template-columns: 1fr;
                gap: 35px;
                padding: 30px 20px;
            }

            .single-product-template2 .main-product-image {
                height: 320px;
            }

            .single-product-template2 .product-title {
                font-size: 26px;
                margin-bottom: 16px;
            }

            .single-product-template2 .current-price {
                font-size: 30px;
            }

            .single-product-template2 .original-price {
                font-size: 20px;
            }

            .single-product-template2 .short-description {
                font-size: 15px;
                margin-bottom: 24px;
            }

            .single-product-template2 .breadcrumb {
                font-size: 13px;
                margin-bottom: 16px;
            }

            .single-product-template2 .rating-stars {
                font-size: 16px;
            }

            .single-product-template2 .reviews-count {
                font-size: 14px;
            }

            /* Thumbnail gallery mobile responsive */
            .single-product-template2 .thumbnail-gallery {
                gap: 8px;
                margin-top: 12px;
            }

            .single-product-template2 .thumbnail-item {
                width: 70px;
                height: 70px;
            }

            /* Tabs responsive */
            .single-product-template2 .tabs-container {
                grid-template-columns: 1fr;
            }

            .single-product-template2 .tabs-nav {
                display: flex;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .single-product-template2 .tab-button {
                border-bottom: none;
                border-right: 1px solid #e0e0e0;
                white-space: nowrap;
                border-left: none !important;
                padding: 16px 18px;
            }

            .single-product-template2 .tab-button.active {
                border-bottom: 3px solid #0073aa;
                border-left: none !important;
            }

            .single-product-template2 .tab-content {
                padding: 24px 20px;
            }

            .single-product-template2 .tab-title {
                font-size: 20px;
            }

            .single-product-template2 .tab-description {
                font-size: 15px;
            }

            /* WooCommerce Tabs Mobile Responsive */
            .woocommerce-tabs .wc-tabs,
            .shopglut-single-product-container .woocommerce-tabs .wc-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .woocommerce-tabs .wc-tabs li a,
            .shopglut-single-product-container .woocommerce-tabs .wc-tabs li a {
                padding: 14px 16px;
                font-size: 14px;
                white-space: nowrap;
            }

            .woocommerce-Tabs-panel,
            .shopglut-single-product-container .woocommerce-Tabs-panel {
                padding: 24px 18px;
            }

            .woocommerce-Tabs-panel h2,
            .shopglut-single-product-container .woocommerce-Tabs-panel h2 {
                font-size: 20px;
            }

            .woocommerce-Tabs-panel h3,
            .shopglut-single-product-container .woocommerce-Tabs-panel h3 {
                font-size: 16px;
            }

            .woocommerce-Tabs-panel h4,
            .shopglut-single-product-container .woocommerce-Tabs-panel h4 {
                font-size: 15px;
            }

            .woocommerce-Tabs-panel p,
            .shopglut-single-product-container .woocommerce-Tabs-panel p {
                font-size: 14px;
            }

            .woocommerce-Tabs-panel table,
            .shopglut-single-product-container .woocommerce-Tabs-panel table {
                font-size: 14px;
            }

            .woocommerce-Tabs-panel table td,
            .woocommerce-Tabs-panel table th,
            .shopglut-single-product-container .woocommerce-Tabs-panel table td,
            .shopglut-single-product-container .woocommerce-Tabs-panel table th {
                padding: 10px 12px;
            }

            .woocommerce-Tabs-panel table th,
            .shopglut-single-product-container .woocommerce-Tabs-panel table th {
                width: 35%;
            }

            .woocommerce-Tabs-panel .commentlist li,
            .shopglut-single-product-container .woocommerce-Tabs-panel .commentlist li {
                padding: 16px;
            }

            .woocommerce-Tabs-panel .comment-form,
            .shopglut-single-product-container .woocommerce-Tabs-panel .comment-form {
                padding: 18px;
            }

            /* Related Products Mobile */
            .single-product-template2 .related-products {
                padding: 30px 20px;
            }

            .single-product-template2 .related-products h2 {
                font-size: 24px;
            }

            .single-product-template2 .products-slider {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .single-product-template2 .related-product {
                min-width: auto;
            }

            .single-product-template2 .related-product-title {
                font-size: 15px;
            }

            .single-product-template2 .related-product-price {
                font-size: 19px;
            }

            .single-product-template2 .add-related-btn {
                padding: 10px;
                font-size: 13px;
            }

            /* Product Meta Mobile */
            .single-product-template2 .product-meta {
                margin-bottom: 30px;
            }

            .single-product-template2 .meta-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                margin-bottom: 12px;
            }

            .single-product-template2 .meta-label {
                min-width: auto;
            }

            /* Attribute Options Mobile */
            .single-product-template2 .attribute-options,
            .shopglut-single-product-container .attribute-options {
                gap: 8px;
            }

            .single-product-template2 .attribute-option,
            .shopglut-single-product-container .attribute-option {
                padding: 8px 16px;
                font-size: 13px;
                min-width: 50px;
            }

            .single-product-template2 .attribute-option[style*="background-color"],
            .shopglut-single-product-container .attribute-option[style*="background-color"] {
                width: 35px;
                height: 35px;
                min-width: 35px;
            }

            /* Social Icons Mobile */
            .single-product-template2 .share-icons,
            .shopglut-single-product-container .share-icons {
                gap: 8px;
            }

            .single-product-template2 .share-icon,
            .shopglut-single-product-container .share-icon {
                width: 32px !important;
                height: 32px !important;
            }

            /* Thumbnail Gallery Mobile */
            .single-product-template2 .thumbnail-gallery,
            .shopglut-single-product-container .thumbnail-gallery {
                gap: 8px;
                justify-content: center;
            }

            .single-product-template2 .thumbnail-item,
            .shopglut-single-product-container .thumbnail-item {
                width: 70px !important;
                height: 70px !important;
            }
        }

        /* Tablet (992px and below) */
        @media (max-width: 992px) {
            .single-product-template2 .product-container {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 40px 25px;
            }

            .single-product-template2 .main-product-image {
                height: 380px;
            }

            /* Cart actions full width on tablet */
            .single-product-template2 .cart-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .single-product-template2 .quantity-selector {
                width: 100%;
                display: flex;
            }

            .single-product-template2 .quantity-btn {
                flex: 1;
                width: auto !important;
            }

            .single-product-template2 .quantity-input {
                flex: 2;
                width: auto !important;
            }

            .single-product-template2 .add-to-cart,
            .single-product-template2 .wishlist-btn {
                width: 100%;
            }

            .single-product-template2 .tabs-container {
                grid-template-columns: 1fr;
            }

            .single-product-template2 .tabs-nav {
                display: flex;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
                overflow-x: auto;
            }

            .single-product-template2 .tab-button {
                border-bottom: none;
                border-right: 1px solid #e0e0e0;
                white-space: nowrap;
                border-left: none !important;
            }

            .single-product-template2 .tab-button.active {
                border-bottom: 3px solid #0073aa;
                border-left: none !important;
            }

            /* WooCommerce Tabs Responsive */
            .woocommerce-tabs .wc-tabs,
            .shopglut-single-product-container .woocommerce-tabs .wc-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .woocommerce-tabs .wc-tabs li a,
            .shopglut-single-product-container .woocommerce-tabs .wc-tabs li a {
                padding: 16px 20px;
                white-space: nowrap;
            }

            .woocommerce-Tabs-panel,
            .shopglut-single-product-container .woocommerce-Tabs-panel {
                padding: 30px 25px;
            }
        }

        /* Large Tablet (1024px and below) */
        @media (max-width: 1024px) {
            .single-product-template2 .container {
                max-width: 100%;
                padding: 0 20px;
            }

            .single-product-template2 .product-container {
                gap: 35px;
            }

            /* Cart actions full width on large tablet */
            .single-product-template2 .cart-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .single-product-template2 .quantity-selector {
                width: 100%;
                display: flex;
            }

            .single-product-template2 .quantity-btn {
                flex: 1;
                width: auto !important;
            }

            .single-product-template2 .quantity-input {
                flex: 2;
                width: auto !important;
            }

            .single-product-template2 .add-to-cart,
            .single-product-template2 .wishlist-btn {
                width: 100%;
            }

            .single-product-template2 .related-products {
                padding: 35px;
            }
        }

        /* ========== ADMIN PREVIEW SPECIFIC RESPONSIVE ADJUSTMENTS ========== */

        /* Admin Preview Responsive - Tablet and Below */
        @media (max-width: 768px) {
            .single-product-template2 .demo-content .container,
            .single-product-template2 .responsive-preview .container {
                padding: 0 10px;
            }

            .single-product-template2 .demo-content .product-container,
            .single-product-template2 .responsive-preview .product-container {
                grid-template-columns: 1fr !important;
                gap: 15px;
            }
        }

        /* Admin Preview Responsive - Small Mobile */
        @media (max-width: 480px) {
            .single-product-template2 .demo-content .container,
            .single-product-template2 .responsive-preview .container {
                padding: 0 8px;
            }

            .single-product-template2 .demo-content .product-container,
            .single-product-template2 .responsive-preview .product-container {
                gap: 16px;
            }
        }

        /* Admin Preview Responsive - Extra Small Mobile */
        @media (max-width: 360px) {
            .single-product-template2 .demo-content .container,
            .single-product-template2 .responsive-preview .container {
                padding: 0 6px;
            }
        }

        /* Shimmer Animation Keyframes */
        @keyframes shimmer-template2 {
            100% {
                transform: translateX(100%);
            }
        }

        /* ========== CONTAINER QUERIES FOR ADMIN PREVIEW ========== */
        /* These respond to actual container width, not viewport width */

        /* When container is less than 800px, force single column */
        @container (max-width: 800px) {
            .single-product-template2 .product-container {
                grid-template-columns: 1fr !important;
                gap: 20px;
            }

            .single-product-template2 .products-slider {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* When container is less than 600px */
        @container (max-width: 600px) {
            .single-product-template2 .product-container {
                gap: 15px;
                padding: 15px;
            }

            .single-product-template2 .products-slider {
                grid-template-columns: 1fr;
            }

            .single-product-template2 .main-product-image {
                max-height: 280px;
            }

            .single-product-template2 .product-title {
                font-size: 24px;
            }

            .single-product-template2 .current-price {
                font-size: 28px;
            }
        }

        /* When container is less than 400px */
        @container (max-width: 400px) {
            .single-product-template2 .product-container {
                gap: 12px;
                padding: 12px;
            }

            .single-product-template2 .main-product-image {
                max-height: 220px;
            }

            .single-product-template2 .product-title {
                font-size: 20px;
            }

            .single-product-template2 .current-price {
                font-size: 24px;
            }

            .single-product-template2 .thumbnail-item {
                width: 60px;
                height: 60px;
            }

            .single-product-template2 .cart-actions {
                flex-direction: column;
                gap: 10px;
            }

            .single-product-template2 .quantity-selector {
                width: 100%;
                display: flex;
            }

            .single-product-template2 .quantity-btn {
                flex: 1;
                width: auto !important;
            }

            .single-product-template2 .quantity-input {
                flex: 2;
                width: auto !important;
            }

            .single-product-template2 .add-to-cart,
            .single-product-template2 .wishlist-btn {
                width: 100%;
            }
        }

        /* ========== GALLERY INTERACTION STYLES ========== */

        /* Lightbox Styles */
        .shopglut-lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .shopglut-lightbox-overlay.shopglut-lightbox-active {
            opacity: 1;
        }

        .shopglut-lightbox-container {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }

        .shopglut-lightbox-container img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .shopglut-lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            font-size: 40px;
            color: #ffffff;
            cursor: pointer;
            transition: color 0.3s ease;
            line-height: 1;
        }

        .shopglut-lightbox-close:hover {
            color: #667eea;
        }

        /* Shimmer Animation Keyframes */
        @keyframes shimmer-template2 {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        /* Hover Zoom Container Styles */
        .single-product-template2 .main-product-image.hover-zoom-enabled {
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
        }

        .single-product-template2 .main-product-image.hover-zoom-enabled img {
            transition: transform 0.1s ease-out;
        }

        /* ========== END GALLERY INTERACTION STYLES ========== */

        </style>
        <?php
    }

    /**
     * Generate CSS based on settings
     */
    private function generateSettingsBasedCSS($settings) {
        $css = '';

        // Product Gallery Settings - Target both demo (.single-product-template2) and live (.shopglut-single-product-container)
        $gallery_selectors = array('.single-product-template2', '.shopglut-single-product-container');

        foreach ($gallery_selectors as $selector) {
            // Main Image Container Styles
            $css .= $selector . ' .main-product-image {';
            $css .= 'background-color: ' . $this->getSetting($settings, 'main_image_background', '#f9fafb') . ' !important;';
            $css .= 'background-image: none !important;';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px !important;';
            $css .= 'border: ' . $this->getSetting($settings, 'main_image_border_width', 1) . 'px solid ' . $this->getSetting($settings, 'main_image_border_color', '#e5e7eb') . ' !important;';
            $css .= 'padding: ' . $this->getSetting($settings, 'main_image_padding', 14) . 'px !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'main_image_margin_bottom', 20) . 'px !important;';
            $css .= 'position: relative;';
            $css .= 'overflow: hidden;';
            $css .= 'width: 100%;';
            $css .= 'max-width: 550px;';
            $css .= 'height: 500px;';
            $css .= 'display: flex;';
            $css .= 'align-items: center;';
            $css .= 'justify-content: center;';
            $css .= '}';

            // Main Image inner img styles
            $css .= $selector . ' .main-product-image img {';
            $css .= 'border-radius: ' . $this->getSetting($settings, 'main_image_border_radius', 8) . 'px;';
            $css .= 'width: 100%;';
            $css .= 'height: 100%;';
            $css .= 'object-fit: ' . $this->getSetting($settings, 'main_image_object_fit', 'cover') . ';';
            $css .= 'cursor: ' . $this->getSetting($settings, 'main_image_cursor', 'zoom-in') . ';';
            $css .= 'transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;';
            // Add shadow if enabled
            if ($this->getSetting($settings, 'main_image_shadow', true)) {
                $css .= 'box-shadow: 0 4px 20px ' . $this->getSetting($settings, 'main_image_shadow_color', 'rgba(0,0,0,0.1)') . ';';
            }
            $css .= '}';

            // Shimmer effect for main image
            if ($this->getSetting($settings, 'enable_shimmer_effect', false)) {
                $shimmer_speed = $this->getSetting($settings, 'shimmer_speed', 3);
                $shimmer_opacity = $this->getSetting($settings, 'shimmer_opacity', 20) / 100;
                $css .= $selector . ' .main-product-image::before {';
                $css .= 'content: "";';
                $css .= 'position: absolute;';
                $css .= 'top: 0; left: 0; right: 0; bottom: 0;';
                $css .= 'background: linear-gradient(90deg, transparent, rgba(255,255,255,' . $shimmer_opacity . '), transparent);';
                $css .= 'transform: translateX(-100%);';
                $css .= 'animation: shimmer-template2 ' . $shimmer_speed . 's infinite;';
                $css .= 'pointer-events: none;';
                $css .= 'z-index: 1;';
                $css .= '}';
            }

            // Main image hover effects
            $has_hover_scale = $this->getSetting($settings, 'main_image_hover_scale', false);
            $has_hover_brightness = $this->getSetting($settings, 'main_image_hover_brightness', false);
            $has_hover_zoom = $this->getSetting($settings, 'enable_image_hover_zoom', false);

            if (($has_hover_scale || $has_hover_brightness) && !$has_hover_zoom) {
                $css .= $selector . ' .main-product-image:hover img {';
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
                $css .= $selector . ' .thumbnail-gallery {';
                $css .= 'display: flex;';
                $css .= 'gap: ' . $this->getSetting($settings, 'thumbnail_spacing', 10) . 'px;';
                $css .= 'margin-top: ' . $this->getSetting($settings, 'thumbnail_gallery_margin_top', 16) . 'px;';
                $css .= 'justify-content: ' . $this->getSetting($settings, 'thumbnail_alignment', 'center') . ';';
                $css .= 'width: 100%;';
                $css .= 'max-width: 550px;';
                $css .= '}';

                // Get thumbnail opacity setting (convert percentage to decimal)
                $thumbnail_opacity = $this->getSetting($settings, 'thumbnail_opacity', 70) / 100;

                $css .= $selector . ' .thumbnail-item {';
                $css .= 'width: ' . $this->getSetting($settings, 'thumbnail_size', 100) . 'px;';
                $css .= 'height: ' . $this->getSetting($settings, 'thumbnail_size', 100) . 'px;';
                $css .= 'border-radius: ' . $this->getSetting($settings, 'thumbnail_border_radius', 6) . 'px;';
                $css .= 'border: ' . $this->getSetting($settings, 'thumbnail_border_width', 2) . 'px solid ' . $this->getSetting($settings, 'thumbnail_border_color', 'transparent') . ';';
                $css .= 'overflow: hidden;';
                $css .= 'cursor: pointer;';
                $css .= 'transition: all 0.3s ease;';
                $css .= 'opacity: ' . $thumbnail_opacity . ';';
                $css .= 'background: #f8f9fa;';
                $css .= '}';

                // Active thumbnail should have full opacity
                $css .= $selector . ' .thumbnail-item.active {';
                $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_active_border', '#667eea') . ';';
                $css .= 'opacity: 1;';
                $css .= 'box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);';
                $css .= '}';

                // Thumbnail hover effect
                if ($this->getSetting($settings, 'thumbnail_hover_scale', true)) {
                    $css .= $selector . ' .thumbnail-item:hover {';
                    $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_hover_border', '#2563eb') . ';';
                    $css .= 'transform: scale(' . $this->getSetting($settings, 'thumbnail_hover_scale_value', 1.05) . ');';
                    $css .= 'opacity: 1;';
                    $css .= '}';
                } else {
                    $css .= $selector . ' .thumbnail-item:hover {';
                    $css .= 'border-color: ' . $this->getSetting($settings, 'thumbnail_hover_border', '#2563eb') . ';';
                    $css .= 'opacity: 1;';
                    $css .= '}';
                }

                $css .= $selector . ' .thumbnail-item img {';
                $css .= 'width: 100%;';
                $css .= 'height: 100%;';
                $css .= 'object-fit: ' . $this->getSetting($settings, 'thumbnail_object_fit', 'cover') . ';';
                $css .= '}';
            }
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

        // ==================== PRODUCT INFO SETTINGS ====================

        // Breadcrumb Settings
        if ($this->getSetting($settings, 'show_breadcrumb', true)) {
            $css .= '.shopglut-single-product-container .breadcrumb {';
            $css .= 'font-size: ' . $this->getSetting($settings, 'breadcrumb_font_size', 14) . 'px !important;';
            $css .= 'color: ' . $this->getSetting($settings, 'breadcrumb_text_color', '#6b7280') . ' !important;';
            $css .= 'margin-bottom: ' . $this->getSetting($settings, 'breadcrumb_margin_bottom', 16) . 'px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .breadcrumb a {';
            $css .= 'color: ' . $this->getSetting($settings, 'breadcrumb_link_color', '#667eea') . ' !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .breadcrumb a:hover {';
            $css .= 'color: ' . $this->getSetting($settings, 'breadcrumb_link_hover_color', '#5a67d8') . ' !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .breadcrumb .separator {';
            $css .= 'color: ' . $this->getSetting($settings, 'breadcrumb_separator_color', '#9ca3af') . ' !important;';
            $css .= '}';
        }

        // Product Metadata Settings
        if ($this->getSetting($settings, 'show_product_meta', true)) {
            $css .= '.shopglut-single-product-container .product-meta {';
            $css .= 'margin-bottom: 24px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .meta-item {';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'gap: 12px !important;';
            $css .= 'margin-bottom: 12px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .meta-label {';
            $css .= 'color: ' . $this->getSetting($settings, 'meta_label_color', '#374151') . ' !important;';
            $css .= 'font-size: ' . $this->getSetting($settings, 'meta_label_font_size', 14) . 'px !important;';
            $css .= 'font-weight: ' . $this->getSetting($settings, 'meta_label_font_weight', '500') . ' !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .meta-value a {';
            $css .= 'color: ' . $this->getSetting($settings, 'meta_link_color', '#667eea') . ' !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .meta-value a:hover {';
            $css .= 'color: ' . $this->getSetting($settings, 'meta_link_hover_color', '#5a67d8') . ' !important;';
            $css .= '}';
        }

        // Social Share Settings
        if ($this->getSetting($settings, 'enable_social_share', true)) {
            $social_icons = $this->getSetting($settings, 'social_share_icons', array());
            $icon_size = $this->getSetting($settings, 'social_icon_size', 36);
            $icon_spacing = $this->getSetting($settings, 'social_icon_spacing', 8);

            $css .= '.shopglut-single-product-container .share-icons {';
            $css .= 'display: flex !important;';
            $css .= 'gap: ' . $icon_spacing . 'px !important;';
            $css .= '}';

            $css .= '.shopglut-single-product-container .share-icon {';
            $css .= 'width: ' . $icon_size . 'px !important;';
            $css .= 'height: ' . $icon_size . 'px !important;';
            $css .= 'display: flex !important;';
            $css .= 'align-items: center !important;';
            $css .= 'justify-content: center !important;';
            $css .= 'cursor: pointer !important;';
            $css .= 'transition: all 0.3s ease !important;';
            $css .= '}';

            // Generate individual social icon styles from repeater
            if (!empty($social_icons) && is_array($social_icons)) {
                foreach ($social_icons as $index => $icon) {
                    $icon_class = !empty($icon['social_icon']) ? str_replace(' ', '.', $icon['social_icon']) : 'icon-' . $index;

                    $css .= '.shopglut-single-product-container .share-icon i.' . $icon_class . ', ';
                    $css .= '.shopglut-single-product-container .share-icon.' . $icon_class . ' {';
                    $css .= 'background-color: ' . (!empty($icon['social_background']) ? $icon['social_background'] : '#1877f2') . ' !important;';
                    $css .= 'color: ' . (!empty($icon['social_color']) ? $icon['social_color'] : '#ffffff') . ' !important;';
                    $css .= 'border-radius: ' . (!empty($icon['social_border_radius']) ? $icon['social_border_radius'] : 6) . 'px !important;';
                    $css .= '}';

                    $css .= '.shopglut-single-product-container .share-icon i.' . $icon_class . ':hover, ';
                    $css .= '.shopglut-single-product-container .share-icon.' . $icon_class . ':hover {';
                    $css .= 'background-color: ' . (!empty($icon['social_hover_background']) ? $icon['social_hover_background'] : '#0e5f9e') . ' !important;';
                    if (!empty($icon['social_hover_color'])) {
                        $css .= 'color: ' . $icon['social_hover_color'] . ' !important;';
                    }
                    $css .= '}';
                }
            }
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
        $css .= 'display: inline-flex !important;';
        $css .= 'align-items: stretch !important;';
        $css .= 'border: 1px solid #d1d5db !important;';
        $css .= 'border-radius: ' . $this->getSetting($settings, 'quantity_border_radius', 8) . 'px !important;';
        $css .= 'overflow: hidden !important;';
        $css .= 'background: #ffffff !important;';
        $css .= 'box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .qty-decrease,';
        $css .= '.shopglut-single-product-container .qty-increase {';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'justify-content: center !important;';
        $css .= 'background: #f9fafb !important;';
        $css .= 'border: none !important;';
        $css .= 'width: 44px !important;';
        $css .= 'height: 44px !important;';
        $css .= 'font-size: 18px !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= 'color: #374151 !important;';
        $css .= 'cursor: pointer !important;';
        $css .= 'transition: all 0.2s ease !important;';
        $css .= 'padding: 0 !important;';
        $css .= 'line-height: 1 !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .qty-decrease:hover,';
        $css .= '.shopglut-single-product-container .qty-increase:hover {';
        $css .= 'background: #e5e7eb !important;';
        $css .= 'color: #111827 !important;';
        $css .= '}';

        $css .= '.shopglut-single-product-container .qty-input {';
        $css .= 'background-color: ' . $this->getSetting($settings, 'quantity_input_background', '#ffffff') . ' !important;';
        $css .= 'border: none !important;';
        $css .= 'border-left: 1px solid #e5e7eb !important;';
        $css .= 'border-right: 1px solid #e5e7eb !important;';
        $css .= 'text-align: center !important;';
        $css .= 'width: 64px !important;';
        $css .= 'height: 44px !important;';
        $css .= 'font-weight: 600 !important;';
        $css .= 'color: #111827 !important;';
        $css .= 'font-size: 16px !important;';
        $css .= 'padding: 0 !important;';
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
            $css .= 'text-align: left !important;';
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
                $css .= 'text-align: left !important;';
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

        // Product Tabs Styling
        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li a {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_color', '#374151') . ';';
        $css .= 'font-size: ' . $this->getSetting($settings, 'tab_title_font_size', 15) . 'px;';
        $css .= 'font-weight: ' . $this->getSetting($settings, 'tab_title_font_weight', '500') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li a i {';
        $css .= 'font-size: ' . $this->getSetting($settings, 'tab_icon_size', 16) . 'px;';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_color', '#6b7280') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li a:hover {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_hover_color', '#667eea') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li a:hover i {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_hover_color', '#667eea') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li.active a {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_title_active_color', '#667eea') . ';';
        $css .= '}';

        $css .= '.shopglut-single-product-container .woocommerce-tabs .wc-tabs li.active a i {';
        $css .= 'color: ' . $this->getSetting($settings, 'tab_icon_active_color', '#667eea') . ';';
        $css .= '}';

        // Product Swatches Styles for Template2
        // Dropdown width fix
        $css .= '.single-product-template2 .shopglut-swatch-dropdown {';
        $css .= 'min-width: 200px !important;';
        $css .= 'width: 100% !important;';
        $css .= 'max-width: 100% !important;';
        $css .= '}';

        $css .= '.single-product-template2 table.variations td.value {';
        $css .= 'width: 100% !important;';
        $css .= 'min-width: 250px !important;';
        $css .= '}';

        $css .= '.single-product-template2 .shopglut-swatches-wrapper {';
        $css .= 'width: 100% !important;';
        $css .= 'display: block !important;';
        $css .= '}';

        // Clear button and price styling
        $css .= '.single-product-template2 .shopglut-reset-variations {';
        $css .= 'margin-top: 5px !important;';
        $css .= 'margin-bottom: 5px !important;';
        $css .= '}';

        $css .= '.single-product-template2 .shopglut-variation-price {';
        $css .= 'display: inline-block !important;';
        $css .= 'margin-top: 5px !important;';
        $css .= 'margin-bottom: 5px !important;';
        $css .= 'background-color: transparent !important;';
        $css .= 'border: none !important;';
        $css .= '}';

        $css .= '.single-product-template2 .shopglut-actions-container {';
        $css .= 'display: flex !important;';
        $css .= 'align-items: center !important;';
        $css .= 'gap: 15px !important;';
        $css .= 'flex-wrap: wrap !important;';
        $css .= 'margin-top: 8px !important;';
        $css .= 'margin-bottom: 8px !important;';
        $css .= '}';

        $css .= '.single-product-template2 table.variations {';
        $css .= 'margin-bottom: 5px !important;';
        $css .= '}';

        // Price range hide/show for variable products
        $css .= '.single-product-template2.variation-selected .product-info .price-section,';
        $css .= '.single-product-template2.variation-selected .price-section {';
        $css .= 'display: none !important;';
        $css .= '}';

        $css .= '.single-product-template2:not(.variation-selected) .shopglut-variation-price {';
        $css .= 'display: none !important;';
        $css .= '}';

        // WooCommerce price styling for variable product price ranges
        $css .= '.single-product-template2 .price-section .price {';
        $css .= 'color: ' . $this->getSetting($settings, 'current_price_color', '#667eea') . ' !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'price_font_size', 28) . 'px !important;';
        $css .= 'font-weight: 700 !important;';
        $css .= '}';

        $css .= '.single-product-template2 .price-section .woocommerce-Price-amount {';
        $css .= 'color: ' . $this->getSetting($settings, 'current_price_color', '#667eea') . ' !important;';
        $css .= 'font-size: ' . $this->getSetting($settings, 'price_font_size', 28) . 'px !important;';
        $css .= 'font-weight: 700 !important;';
        $css .= '}';

        $css .= '.single-product-template2 .price-section .woocommerce-Price-currencySymbol {';
        $css .= 'color: ' . $this->getSetting($settings, 'current_price_color', '#667eea') . ' !important;';
        $css .= '}';

        $css .= '.single-product-template2 .price-section .price > * {';
        $css .= 'color: inherit !important;';
        $css .= '}';

        $css .= '.single-product-template2 .price-section del {';
        $css .= 'color: ' . $this->getSetting($settings, 'original_price_color', '#9ca3af') . ' !important;';
        $css .= 'font-size: 1.2rem !important;';
        $css .= 'opacity: 0.8 !important;';
        $css .= '}';

        // Animation for price and clear button
        $css .= '@keyframes shopglutFadeInUp {';
        $css .= 'from { opacity: 0; transform: translateY(-10px); }';
        $css .= 'to { opacity: 1; transform: translateY(0); }';
        $css .= '}';

        $css .= '.single-product-template2.variation-selected .shopglut-variation-price.fade-in {';
        $css .= 'animation: shopglutFadeInUp 0.3s ease-out forwards;';
        $css .= '}';

        $css .= '.single-product-template2.variation-selected .shopglut-reset-variations.fade-in {';
        $css .= 'animation: shopglutFadeInUp 0.3s ease-out forwards;';
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
            if (isset($settings['shopg_singleproduct_settings_template2']['single-product-settings'])) {
                return $this->flattenSettings($settings['shopg_singleproduct_settings_template2']['single-product-settings']);
            } elseif (isset($settings['shopg_cartpage_settings_template2']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template2']['cart-page-settings']);
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
            'main_image_cursor' => 'zoom-in',
            'main_image_shadow' => true,
            'main_image_shadow_color' => 'rgba(0,0,0,0.1)',
            'enable_shimmer_effect' => false,
            'shimmer_speed' => 3,
            'shimmer_opacity' => 20,
            'main_image_hover_scale' => false,
            'main_image_hover_scale_value' => 1.05,
            'main_image_hover_brightness' => false,
            'main_image_hover_brightness_value' => 110,
            'show_thumbnails' => true,
            'thumbnail_size' => 100,
            'thumbnail_spacing' => 10,
            'thumbnail_border_radius' => 6,
            'thumbnail_border_width' => 2,
            'thumbnail_border_color' => 'transparent',
            'thumbnail_active_border' => '#667eea',
            'thumbnail_hover_border' => '#2563eb',
            'thumbnail_opacity' => 70,
            'thumbnail_hover_scale' => true,
            'thumbnail_hover_scale_value' => 1.05,
            'thumbnail_gallery_margin_top' => 16,
            'thumbnail_alignment' => 'center',
            'thumbnail_object_fit' => 'cover',
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

            // ==================== PRODUCT INFO SETTINGS ====================
            // Breadcrumb Settings
            'show_breadcrumb' => true,
            'breadcrumb_font_size' => 14,
            'breadcrumb_text_color' => '#6b7280',
            'breadcrumb_link_color' => '#667eea',
            'breadcrumb_link_hover_color' => '#5a67d8',
            'breadcrumb_separator' => '>',
            'breadcrumb_separator_color' => '#9ca3af',
            'breadcrumb_margin_bottom' => 16,

            // Product Metadata Settings
            'show_product_meta' => true,
            'show_categories' => true,
            'show_tags' => true,
            'meta_label_color' => '#374151',
            'meta_label_font_size' => 14,
            'meta_label_font_weight' => '500',
            'meta_link_color' => '#667eea',
            'meta_link_hover_color' => '#5a67d8',

            // Social Share Settings
            'enable_social_share' => true,
            'social_share_label' => 'Share:',
            'social_icon_size' => 36,
            'social_icon_spacing' => 8,

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
            'related_section_title' => 'Related Products',
            'related_section_title_color' => '#111827',
            'related_products_per_row' => '4',
            'product_card_background' => '#ffffff',
            'product_card_border_color' => '#e5e7eb',
            'product_card_border_radius' => 8,
            'product_card_hover_shadow' => true,
            'quick_add_button_background' => '#667eea',
            'quick_add_button_text_color' => '#ffffff',

            // Product Tabs
            'product_tabs_list' => array(
                array(
                    'tab_icon' => 'fas fa-shipping-fast',
                    'tab_title' => 'Shipping Info',
                    'tab_content' => 'Free shipping on all orders over $50. Delivery within 3-5 business days.',
                ),
                array(
                    'tab_icon' => 'fas fa-undo',
                    'tab_title' => 'Returns',
                    'tab_content' => '30-day hassle-free returns on all products.',
                ),
            ),
            'tab_icon_size' => 16,
            'tab_icon_color' => '#6b7280',
            'tab_icon_hover_color' => '#667eea',
            'tab_icon_active_color' => '#667eea',
            'tab_title_color' => '#374151',
            'tab_title_hover_color' => '#667eea',
            'tab_title_active_color' => '#667eea',
            'tab_title_font_size' => 15,
            'tab_title_font_weight' => '500',

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
