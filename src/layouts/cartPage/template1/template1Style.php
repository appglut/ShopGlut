<?php
namespace Shopglut\layouts\cartPage\template1;

class template1Style {

    public function dynamicCss($layout_id = 0)
    {
        // Get settings for this layout
        $settings = $this->getLayoutSettings($layout_id);

        ?>
        <style>
            .shopglut-cart.template1 .cart-container {
                max-width: 1200px;
                margin: 10px auto;
                padding: 0 10px;
            }

            .shopglut-cart.template1 .cart-header {
                background: white;
                border-radius: 16px 16px 0 0;
                padding: 40px;
                border-bottom: 1px solid #e2e8f0;
                text-align: center;
            }

            .shopglut-cart.template1 .cart-header h1 {
                font-size: 2.5rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 8px;
                letter-spacing: -0.02em;
            }

            .shopglut-cart.template1 .cart-header .subtitle {
                font-size: 1.1rem;
                color: #64748b;
                font-weight: 400;
            }

            .shopglut-cart.template1 .cart-header .cart-count {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #f1f5f9;
                padding: 8px 16px;
                border-radius: 25px;
                font-size: 0.9rem;
                color: #475569;
                margin-top: 16px;
                font-weight: 500;
            }

            .shopglut-cart.template1 .cart-content {
                background: <?php echo esc_attr($settings['table_background_color']); ?>;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                border-radius: 0 0 16px 16px;
            }

            .shopglut-cart.template1 .cart-table-container {
                overflow-x: auto;
                padding: 40px;
                padding-bottom: 20px;
            }

            .shopglut-cart.template1 .cart-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                border-top-left-radius:15px;
                border-top-right-radius:15px;
            }

            <?php if ($settings['show_table_header']): ?>
            .shopglut-cart.template1 .cart-table th {
                background: <?php echo esc_attr($settings['header_background_color']); ?>;
                padding: <?php echo esc_attr($settings['header_padding']['top'] . $settings['header_padding']['unit'] . ' ' . $settings['header_padding']['right'] . $settings['header_padding']['unit'] . ' ' . $settings['header_padding']['bottom'] . $settings['header_padding']['unit'] . ' ' . $settings['header_padding']['left'] . $settings['header_padding']['unit']); ?>;
                text-align: left;
                font-weight: <?php echo esc_attr($settings['header_font_weight']); ?>;
                color: <?php echo esc_attr($settings['header_text_color']); ?>;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: <?php echo esc_attr($settings['table_border_width'] . 'px solid ' . $settings['table_border_color']); ?>;
                white-space: nowrap;
            }
            <?php else: ?>
            .shopglut-cart.template1 .cart-table th {
                display: none;
            }
            <?php endif; ?>

            .shopglut-cart.template1 .cart-table th:first-child {
                border-radius: 12px 0 0 0;
            }

            .shopglut-cart.template1 .cart-table th:last-child {
                border-radius: 0 12px 0 0;
            }

            .shopglut-cart.template1 .cart-table td {
                padding: <?php echo esc_attr($settings['row_padding']['top'] . $settings['row_padding']['unit'] . ' ' . $settings['row_padding']['right'] . $settings['row_padding']['unit'] . ' ' . $settings['row_padding']['bottom'] . $settings['row_padding']['unit'] . ' ' . $settings['row_padding']['left'] . $settings['row_padding']['unit']); ?>;
                border-bottom: <?php echo esc_attr($settings['table_border_width'] . 'px solid ' . $settings['table_border_color']); ?>;
                vertical-align: middle;
                background: <?php echo esc_attr($settings['table_background_color']); ?>;
                transition: all 0.2s ease;
            }

            <?php if ($settings['row_hover_effect']): ?>
            .shopglut-cart.template1 .cart-table tbody tr:hover td {
                background: <?php echo esc_attr($settings['row_hover_color']); ?>;
            }
            <?php endif; ?>

            .shopglut-cart.template1 .product-cell {
                display: flex;
                align-items: center;
                gap: 20px;
                min-width: 300px;
            }

            .shopglut-cart.template1 .product-image {
                width: <?php echo esc_attr($settings['product_image_size']['width'] . $settings['product_image_size']['unit']); ?>;
                height: <?php echo esc_attr($settings['product_image_size']['height'] . $settings['product_image_size']['unit']); ?>;
                background: <?php echo esc_attr($settings['image_background_color']); ?>;
                border-radius: <?php echo esc_attr($settings['image_border_radius'] . 'px'); ?>;
                border: <?php echo esc_attr($settings['image_border_width'] . 'px solid ' . $settings['image_border_color']); ?>;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: white;
                flex-shrink: 0;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            }

            .shopglut-cart.template1 .product-details {
                min-width: 0;
                flex: 1;
            }

            .shopglut-cart.template1 .product-name {
                font-weight: <?php echo esc_attr($settings['product_title_font_weight']); ?>;
                color: <?php echo esc_attr($settings['product_title_color']); ?>;
                font-size: <?php echo esc_attr($settings['product_title_font_size'] . 'px'); ?>;
                margin-bottom: 4px;
                line-height: 1.4;
            }

            <?php if ($settings['show_product_meta']): ?>
            .shopglut-cart.template1 .product-meta {
                color: <?php echo esc_attr($settings['product_meta_color']); ?>;
                font-size: <?php echo esc_attr($settings['product_meta_font_size'] . 'px'); ?>;
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            <?php else: ?>
            .shopglut-cart.template1 .product-meta {
                display: none;
            }
            <?php endif; ?>

            <?php if ($settings['show_product_badges']): ?>
            .shopglut-cart.template1 .product-badge {
                background: <?php echo esc_attr($settings['badge_background_color']); ?>;
                color: <?php echo esc_attr($settings['badge_text_color']); ?>;
                padding: 2px 8px;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
            }
            <?php else: ?>
            .shopglut-cart.template1 .product-badge {
                display: none;
            }
            <?php endif; ?>

            .shopglut-cart.template1 .price-cell {
                font-weight: <?php echo esc_attr($settings['price_font_weight']); ?>;
                color: <?php echo esc_attr($settings['price_color']); ?>;
                font-size: <?php echo esc_attr($settings['price_font_size'] . 'px'); ?>;
                white-space: nowrap;
            }

            .shopglut-cart.template1 .quantity-cell {
                min-width: 120px;
            }

            .shopglut-cart.template1 .qty-control {
                display: inline-flex;
                border: 2px solid <?php echo esc_attr($settings['quantity_input_border']); ?>;
                border-radius: <?php echo esc_attr($settings['quantity_control_border_radius'] . 'px'); ?>;
                overflow: hidden;
                background: <?php echo esc_attr($settings['quantity_input_background']); ?>;
            }

            .shopglut-cart.template1 .qty-control:focus-within {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .shopglut-cart.template1 .qty-btn {
                width: 36px;
                height: 36px;
                border: none;
                background: <?php echo esc_attr($settings['quantity_button_color']); ?>;
                color: <?php echo esc_attr($settings['quantity_button_text_color']); ?>;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .shopglut-cart.template1 .qty-btn:hover {
                background: <?php echo esc_attr($settings['quantity_button_hover_color']); ?>;
                color: <?php echo esc_attr($settings['quantity_button_text_color']); ?>;
            }

            .shopglut-cart.template1 .qty-input {
                width: 50px;
                height: 36px;
                border: none;
                text-align: center;
                font-weight: 600;
                color: <?php echo esc_attr($settings['quantity_button_text_color']); ?>;
                background: <?php echo esc_attr($settings['quantity_input_background']); ?>;
            }

            .shopglut-cart.template1 .qty-input:focus {
                outline: none;
            }

            .shopglut-cart.template1 .remove-btn {
                background: none;
                border: none;
                color: #ef4444;
                cursor: pointer;
                padding: 8px;
                border-radius: 6px;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .shopglut-cart.template1 .remove-btn:hover {
                background: #fef2f2;
                color: #dc2626;
            }

            .shopglut-cart.template1 .cart-footer {
                padding: 40px;
                padding-top: 20px;
                border-top: 1px solid #e5e7eb;
                background: #fafbfc;
                border-radius: 0 0 16px 16px;
            }

            .shopglut-cart.template1 .footer-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 40px;
                margin-bottom: 40px;
            }

            .shopglut-cart.template1 .footer-section {
                background: white;
                padding: 32px;
                border-radius: 12px;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }

            .shopglut-cart.template1 .section-title {
                font-size: 1.2rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .shopglut-cart.template1 .section-title i {
                color: #3b82f6;
                font-size: 1.1rem;
            }

            /* Coupon Section */
            .shopglut-cart.template1 .coupon-form {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .shopglut-cart.template1 .input-group {
                display: flex;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                overflow: hidden;
                transition: border-color 0.15s ease;
            }

            .shopglut-cart.template1 .input-group:focus-within {
                border-color: #3b82f6;
            }

            .shopglut-cart.template1 .coupon-input {
                flex: 1;
                padding: 12px 16px;
                border: none;
                font-size: 0.95rem;
                background: white;
            }

            .shopglut-cart.template1 .coupon-input:focus {
                outline: none;
            }

            .shopglut-cart.template1 .coupon-input::placeholder {
                color: #9ca3af;
            }

            .shopglut-cart.template1 .apply-btn {
                background: #3b82f6;
                color: white;
                border: none;
                padding: 12px 20px;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.15s ease;
                font-size: 0.95rem;
            }

            .shopglut-cart.template1 .apply-btn:hover {
                background: #2563eb;
            }

            .shopglut-cart.template1 .coupon-message {
                font-size: 0.875rem;
                margin-top: 8px;
            }

            /* Shipping Section */
            .shopglut-cart.template1 .shipping-toggle {
                background: none;
                border: none;
                color: #3b82f6;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.95rem;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 0;
                transition: color 0.15s ease;
                width: 100%;
                text-align: left;
            }

            .shopglut-cart.template1 .shipping-toggle:hover {
                color: #2563eb;
            }

            .shopglut-cart.template1 .shipping-form {
                display: none;
                margin-top: 20px;
                animation: slideDown 0.2s ease-out;
            }

            .shopglut-cart.template1 .shipping-form.active {
                display: block;
            }

            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .shopglut-cart.template1 .form-row {
                margin-bottom: 16px;
            }

            .shopglut-cart.template1 .form-label {
                display: block;
                margin-bottom: 6px;
                font-weight: 500;
                color: #374151;
                font-size: 0.9rem;
            }

            .shopglut-cart.template1 .form-input {
                width: 100%;
                padding: 10px 12px;
                border: 2px solid #e5e7eb;
                border-radius: 6px;
                font-size: 0.95rem;
                transition: border-color 0.15s ease;
                background: white;
            }

            .shopglut-cart.template1 .form-input:focus {
                border-color: #3b82f6;
                outline: none;
            }

            .shopglut-cart.template1 .shipping-options {
                margin-top: 20px;
                display: none;
            }

            .shopglut-cart.template1 .shipping-options.show {
                display: block;
            }

            .shopglut-cart.template1 .shipping-option {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                margin-bottom: 12px;
                cursor: pointer;
                transition: all 0.15s ease;
                background: white;
            }

            .shopglut-cart.template1 .shipping-option:hover {
                border-color: #3b82f6;
                background: #f8faff;
            }

            .shopglut-cart.template1 .shipping-option.selected {
                border-color: #3b82f6;
                background: #eff6ff;
            }

            .shopglut-cart.template1 .shipping-info {
                display: flex;
                align-items: center;
                gap: 12px;
                flex: 1;
            }

            .shopglut-cart.template1 .shipping-radio {
                margin: 0;
            }

            .shopglut-cart.template1 .shipping-details {
                flex: 1;
            }

            .shopglut-cart.template1 .shipping-name {
                font-weight: 600;
                color: #1f2937;
                font-size: 0.95rem;
            }

            .shopglut-cart.template1 .shipping-desc {
                color: #6b7280;
                font-size: 0.85rem;
                margin-top: 2px;
            }

            .shopglut-cart.template1 .shipping-price {
                font-weight: 700;
                color: #059669;
                font-size: 1rem;
            }

            /* Cart Summary */
            <?php if ($settings['show_summary_section']): ?>
            .shopglut-cart.template1 .cart-summary {
                background: <?php echo esc_attr($settings['summary_background_color']); ?>;
                border: 2px solid <?php echo esc_attr($settings['summary_border_color']); ?>;
                border-radius: <?php echo esc_attr($settings['summary_border_radius'] . 'px'); ?>;
                overflow: hidden;
            }

            <?php if ($settings['show_summary_header']): ?>
            .shopglut-cart.template1 .summary-header {
                background: <?php echo esc_attr($settings['summary_background_color']); ?>;
                padding: <?php echo esc_attr($settings['summary_padding']['top'] . $settings['summary_padding']['unit'] . ' ' . $settings['summary_padding']['right'] . $settings['summary_padding']['unit']); ?>;
                border-bottom: 1px solid <?php echo esc_attr($settings['summary_border_color']); ?>;
            }

            .shopglut-cart.template1 .summary-title {
                font-size: <?php echo esc_attr($settings['summary_title_font_size'] . 'px'); ?>;
                font-weight: 600;
                color: <?php echo esc_attr($settings['summary_title_color']); ?>;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            <?php else: ?>
            .shopglut-cart.template1 .summary-header {
                display: none;
            }
            <?php endif; ?>
            <?php else: ?>
            .shopglut-cart.template1 .cart-summary {
                display: none;
            }
            <?php endif; ?>

            .shopglut-cart.template1 .summary-content {
                padding: <?php echo esc_attr($settings['summary_padding']['top'] . $settings['summary_padding']['unit'] . ' ' . $settings['summary_padding']['right'] . $settings['summary_padding']['unit'] . ' ' . $settings['summary_padding']['bottom'] . $settings['summary_padding']['unit'] . ' ' . $settings['summary_padding']['left'] . $settings['summary_padding']['unit']); ?>;
            }

            .shopglut-cart.template1 .summary-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: <?php echo esc_attr($settings['row_spacing'] . 'px'); ?> 0;
                border-bottom: 1px solid #f3f4f6;
                font-size: <?php echo esc_attr($settings['row_font_size'] . 'px'); ?>;
            }

            .shopglut-cart.template1 .summary-row:last-of-type {
                <?php if ($settings['total_row_separator']): ?>
                border-bottom: 2px solid <?php echo esc_attr($settings['total_separator_color']); ?>;
                <?php else: ?>
                border-bottom: none;
                <?php endif; ?>
                font-weight: <?php echo esc_attr($settings['total_font_weight']); ?>;
                font-size: <?php echo esc_attr($settings['total_font_size'] . 'px'); ?>;
                color: <?php echo esc_attr($settings['total_label_color']); ?>;
                margin-bottom: 24px;
                padding-bottom: 16px;
            }

            .shopglut-cart.template1 .summary-row .label {
                color: <?php echo esc_attr($settings['row_label_color']); ?>;
            }

            .shopglut-cart.template1 .summary-row .value {
                font-weight: 600;
                color: <?php echo esc_attr($settings['row_value_color']); ?>;
            }

            .shopglut-cart.template1 .summary-row:last-of-type .value {
                color: <?php echo esc_attr($settings['total_value_color']); ?>;
            }

            .shopglut-cart.template1 .summary-row .discount {
                color: #059669;
            }

            .shopglut-cart.template1 .checkout-btn {
                width: 100%;
                background: <?php echo esc_attr($settings['checkout_button_background']); ?>;
                color: <?php echo esc_attr($settings['checkout_button_text_color']); ?>;
                border: none;
                padding: <?php echo esc_attr($settings['checkout_button_padding']['top'] . $settings['checkout_button_padding']['unit'] . ' ' . $settings['checkout_button_padding']['right'] . $settings['checkout_button_padding']['unit'] . ' ' . $settings['checkout_button_padding']['bottom'] . $settings['checkout_button_padding']['unit'] . ' ' . $settings['checkout_button_padding']['left'] . $settings['checkout_button_padding']['unit']); ?>;
                border-radius: <?php echo esc_attr($settings['checkout_button_border_radius'] . 'px'); ?>;
                font-size: <?php echo esc_attr($settings['checkout_button_font_size'] . 'px'); ?>;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            .shopglut-cart.template1 .checkout-btn:hover {
                background: <?php echo esc_attr($settings['checkout_button_hover_background']); ?>;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .shopglut-cart.template1 .checkout-btn:active {
                transform: translateY(0);
            }

            <?php if ($settings['show_security_badges']): ?>
            .shopglut-cart.template1 .security-info {
                display: flex;
                justify-content: center;
                gap: <?php echo esc_attr($settings['security_badge_spacing'] . 'px'); ?>;
                margin-top: 16px;
                <?php if ($settings['security_badges_layout'] === 'vertical'): ?>
                flex-direction: column;
                align-items: center;
                <?php elseif ($settings['security_badges_layout'] === 'grid'): ?>
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                justify-items: center;
                <?php else: ?>
                flex-wrap: wrap;
                <?php endif; ?>
            }

            .shopglut-cart.template1 .security-badge {
                display: flex;
                align-items: center;
                gap: 6px;
                color: #6b7280;
                font-size: 0.8rem;
            }

            .shopglut-cart.template1 .security-badge i {
                color: #059669;
            }
            <?php else: ?>
            .shopglut-cart.template1 .security-info {
                display: none;
            }
            <?php endif; ?>

            .shopglut-cart.template1 .continue-shopping {
                text-align: center;
                margin-top: 32px;
            }

            .shopglut-cart.template1 .continue-link {
                color: #3b82f6;
                text-decoration: none;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: color 0.15s ease;
            }

            .shopglut-cart.template1 .continue-link:hover {
                color: #2563eb;
            }

            /* Preview Container Responsive */
            @container (max-width: 1024px) {
                .shopglut-cart.template1 .cart-container {
                    max-width: 100%;
                    margin: 5px;
                    padding: 0 10px;
                }

                .shopglut-cart.template1 .footer-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }

                .shopglut-cart.template1 .cart-table-container {
                    padding: 20px 15px;
                }
            }

            @container (max-width: 800px) {
                .shopglut-cart.template1 .cart-header {
                    padding: 16px;
                }

                .shopglut-cart.template1 .cart-header h1 {
                    font-size: 1.4rem;
                }

                .shopglut-cart.template1 .cart-table {
                    min-width: 400px;
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 150px;
                    gap: 8px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 45px;
                    height: 45px;
                    font-size: 1rem;
                }

                .shopglut-cart.template1 .cart-table th,
                .shopglut-cart.template1 .cart-table td {
                    padding: 10px 6px;
                }
            }

            @container (max-width: 500px) {
                .shopglut-cart.template1 .cart-container {
                    margin: 2px;
                    padding: 0 5px;
                }

                .shopglut-cart.template1 .cart-header {
                    padding: 12px;
                    border-radius: 8px 8px 0 0;
                }

                .shopglut-cart.template1 .cart-content {
                    border-radius: 0 0 8px 8px;
                }

                .shopglut-cart.template1 .cart-table {
                    min-width: 320px;
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 120px;
                    gap: 6px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 35px;
                    height: 35px;
                    font-size: 0.8rem;
                }

                .shopglut-cart.template1 .product-name {
                    font-size: 0.8rem;
                    line-height: 1.2;
                }

                .shopglut-cart.template1 .cart-footer {
                    padding: 15px 10px;
                }

                .shopglut-cart.template1 .footer-section {
                    padding: 12px;
                }

                .shopglut-cart.template1 .input-group {
                    flex-direction: column;
                    border-radius: 6px;
                }

                .shopglut-cart.template1 .coupon-input {
                    border-radius: 4px 4px 0 0;
                    font-size: 0.8rem;
                    padding: 8px 10px;
                }

                .shopglut-cart.template1 .apply-btn {
                    border-radius: 0 0 4px 4px;
                    padding: 8px 12px;
                    font-size: 0.8rem;
                }

                .shopglut-cart.template1 .checkout-btn {
                    padding: 10px;
                    font-size: 0.85rem;
                }
            }

            /* Container Query Support for Preview Areas */
            .shopglut-cart.template1 {
                container-type: inline-size;
                container-name: cart-preview;
            }

            /* Responsive Design */
            @media (max-width: 1200px) {
                .shopglut-cart.template1 .cart-container {
                    max-width: 100%;
                    margin: 10px;
                    padding: 0 15px;
                }

                .shopglut-cart.template1 .cart-table-container {
                    padding: 30px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 70px;
                    height: 70px;
                    font-size: 1.8rem;
                }
            }

            @media (max-width: 1024px) {
                .shopglut-cart.template1 .footer-grid {
                display: block;
            }

                .shopglut-cart.template1 .product-cell {
                    min-width: 250px;
                    gap: 15px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 60px;
                    height: 60px;
                    font-size: 1.5rem;
                }

                .shopglut-cart.template1 .cart-table th,
                .shopglut-cart.template1 .cart-table td {
                    padding: 24px 16px;
                }
            }

            @media (max-width: 768px) {

            .shopglut-cart.template1 .footer-grid {
                display: block;
            }
                .shopglut-cart.template1 .cart-container {
                    margin: 10px;
                    padding: 0 10px;
                }

                .shopglut-cart.template1 .cart-header {
                    padding: 24px 16px;
                }

                .shopglut-cart.template1 .cart-header h1 {
                    font-size: 1.8rem;
                }

                .shopglut-cart.template1 .cart-header .subtitle {
                    font-size: 1rem;
                }

                .shopglut-cart.template1 .cart-table-container {
                    padding: 20px 10px;
                }

                .shopglut-cart.template1 .cart-table {
                    min-width: 650px;
                }

                .shopglut-cart.template1 .cart-table th,
                .shopglut-cart.template1 .cart-table td {
                    padding: 16px 12px;
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 220px;
                    gap: 12px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 50px;
                    height: 50px;
                    font-size: 1.3rem;
                }

                .shopglut-cart.template1 .product-name {
                    font-size: 1rem;
                }

                .shopglut-cart.template1 .product-meta {
                    font-size: 0.8rem;
                }

                .shopglut-cart.template1 .qty-control {
                    transform: scale(0.9);
                }

                .shopglut-cart.template1 .footer-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }

                .shopglut-cart.template1 .cart-footer {
                    padding: 24px 16px;
                }

                .shopglut-cart.template1 .footer-section {
                    padding: 20px;
                }

                .shopglut-cart.template1 .section-title {
                    font-size: 1.1rem;
                }
            }

            @media (max-width: 640px) {
                .shopglut-cart.template1 .cart-table {
                    min-width: 550px;
                }

                .shopglut-cart.template1 .cart-table th:first-child,
                .shopglut-cart.template1 .cart-table td:first-child {
                    position: sticky;
                    left: 0;
                    background: white;
                    z-index: 1;
                    box-shadow: 2px 0 4px rgba(0,0,0,0.1);
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 200px;
                }

                .shopglut-cart.template1 .cart-table th {
                    font-size: 0.8rem;
                    padding: 12px 8px;
                }

                .shopglut-cart.template1 .cart-table td {
                    padding: 16px 8px;
                }
            }

            @media (max-width: 480px) {
                .shopglut-cart.template1 .cart-container {
                    margin: 5px;
                    padding: 0 8px;
                }

                .shopglut-cart.template1 .cart-header {
                    padding: 20px 12px;
                }

                .shopglut-cart.template1 .cart-header h1 {
                    font-size: 1.5rem;
                }

                .shopglut-cart.template1 .cart-header .subtitle {
                    font-size: 0.9rem;
                }

                .shopglut-cart.template1 .cart-header .cart-count {
                    font-size: 0.8rem;
                    padding: 6px 12px;
                }

                .shopglut-cart.template1 .cart-table-container {
                    padding: 15px 8px;
                }

                .shopglut-cart.template1 .cart-table {
                    min-width: 500px;
                }

                .shopglut-cart.template1 .cart-table th,
                .shopglut-cart.template1 .cart-table td {
                    padding: 12px 6px;
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 160px;
                    gap: 8px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 40px;
                    height: 40px;
                    font-size: 1rem;
                }

                .shopglut-cart.template1 .product-name {
                    font-size: 0.9rem;
                    line-height: 1.3;
                }

                .shopglut-cart.template1 .product-meta {
                    font-size: 0.75rem;
                }

                .shopglut-cart.template1 .product-badge {
                    padding: 1px 4px;
                    font-size: 0.65rem;
                }

                .shopglut-cart.template1 .qty-control {
                    transform: scale(0.8);
                }

                .shopglut-cart.template1 .qty-btn {
                    width: 30px;
                    height: 30px;
                    font-size: 0.9rem;
                }

                .shopglut-cart.template1 .qty-input {
                    width: 40px;
                    height: 30px;
                    font-size: 0.9rem;
                }

                .shopglut-cart.template1 .price-cell {
                    font-size: 0.95rem;
                }

                .shopglut-cart.template1 .remove-btn {
                    padding: 6px;
                }

                .shopglut-cart.template1 .cart-footer {
                    padding: 20px 12px;
                }

                .shopglut-cart.template1 .footer-section {
                    padding: 16px;
                }

                .shopglut-cart.template1 .section-title {
                    font-size: 1rem;
                    margin-bottom: 15px;
                }

                .shopglut-cart.template1 .input-group {
                    flex-direction: column;
                }

                .shopglut-cart.template1 .coupon-input {
                    border-radius: 6px 6px 0 0;
                }

                .shopglut-cart.template1 .apply-btn {
                    border-radius: 0 0 6px 6px;
                }

                .shopglut-cart.template1 .checkout-btn {
                    padding: 14px;
                    font-size: 1rem;
                }

                .shopglut-cart.template1 .security-info {
                    gap: 12px;
                    flex-direction: column;
                    align-items: center;
                }

                .shopglut-cart.template1 .security-badge {
                    font-size: 0.75rem;
                }
            }

            @media (max-width: 380px) {
                .shopglut-cart.template1 .cart-table {
                    min-width: 450px;
                }

                .shopglut-cart.template1 .product-cell {
                    min-width: 140px;
                }

                .shopglut-cart.template1 .product-image {
                    width: 35px;
                    height: 35px;
                    font-size: 0.9rem;
                }

                .shopglut-cart.template1 .product-name {
                    font-size: 0.8rem;
                }

                .shopglut-cart.template1 .cart-header h1 {
                    font-size: 1.3rem;
                }

                .shopglut-cart.template1 .footer-section {
                    padding: 12px;
                }

                .shopglut-cart.template1 .summary-row {
                    font-size: 0.85rem;
                }

                .shopglut-cart.template1 .checkout-btn {
                    font-size: 0.9rem;
                    padding: 12px;
                }
            }

            /* Empty cart state */
            .shopglut-cart.template1 .empty-cart {
                text-align: center;
                padding: 80px 40px;
                color: #6b7280;
            }

            .shopglut-cart.template1 .empty-cart i {
                font-size: 4rem;
                margin-bottom: 24px;
                color: #d1d5db;
            }

            .shopglut-cart.template1 .empty-cart h3 {
                font-size: 1.5rem;
                color: #374151;
                margin-bottom: 8px;
            }

            .shopglut-cart.template1 .empty-cart p {
                font-size: 1rem;
                margin-bottom: 24px;
            }
        </style>
        <?php
    }

    /**
     * Get layout settings from database
     */
    private function getLayoutSettings($layout_id) {
        if (!$layout_id) {
            return $this->getDefaultSettings();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'shopglut_cartpage_layouts';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $layout_data = $wpdb->get_row(
            $wpdb->prepare("SELECT layout_settings FROM `{$wpdb->prefix}shopglut_cartpage_layouts` WHERE id = %d", $layout_id)
        );

        if ($layout_data && !empty($layout_data->layout_settings)) {
            $settings = maybe_unserialize($layout_data->layout_settings);
            if (isset($settings['shopg_cartpage_settings_template1']['cart-page-settings'])) {
                return $this->flattenSettings($settings['shopg_cartpage_settings_template1']['cart-page-settings']);
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
     * Get default settings values
     */
    private function getDefaultSettings() {
        return array(
            // Table Header Settings
            'show_table_header' => true,
            'header_background_color' => '#f3f4f6',
            'header_text_color' => '#374151',
            'header_font_weight' => '600',
            'header_padding' => array('top' => '16', 'right' => '12', 'bottom' => '16', 'left' => '12', 'unit' => 'px'),

            // Product Image Settings
            'product_image_size' => array('width' => 60, 'height' => 60, 'unit' => 'px'),
            'image_background_color' => '#f9fafb',
            'image_border_radius' => 8,
            'image_border_color' => '#e5e7eb',
            'image_border_width' => 1,

            // Product Title Settings
            'product_title_color' => '#111827',
            'product_title_font_size' => 16,
            'product_title_font_weight' => '600',
            'show_product_link' => true,

            // Product Meta Settings
            'show_product_meta' => true,
            'product_meta_color' => '#6b7280',
            'product_meta_font_size' => 14,
            'show_product_badges' => true,
            'badge_background_color' => '#3b82f6',
            'badge_text_color' => '#ffffff',

            // Quantity Settings
            'quantity_button_color' => '#f3f4f6',
            'quantity_button_text_color' => '#374151',
            'quantity_button_hover_color' => '#e5e7eb',
            'quantity_input_background' => '#ffffff',
            'quantity_input_border' => '#d1d5db',
            'quantity_control_border_radius' => 6,

            // Pricing Settings
            'price_color' => '#111827',
            'price_font_size' => 16,
            'price_font_weight' => '600',
            'total_price_highlight' => true,
            'total_price_color' => '#059669',

            // Table Styling
            'table_background_color' => '#ffffff',
            'table_border_color' => '#e5e7eb',
            'table_border_width' => 1,
            'table_border_radius' => 8,
            'row_padding' => array('top' => '16', 'right' => '12', 'bottom' => '16', 'left' => '12', 'unit' => 'px'),
            'row_hover_effect' => true,
            'row_hover_color' => '#f8fafc',

            // Summary Section Settings
            'show_summary_section' => true,
            'summary_background_color' => '#f9fafb',
            'summary_border_color' => '#e5e7eb',
            'summary_border_radius' => 8,
            'summary_padding' => array('top' => '24', 'right' => '20', 'bottom' => '24', 'left' => '20', 'unit' => 'px'),

            // Summary Header
            'show_summary_header' => true,
            'summary_title_text' => 'Order Summary',
            'summary_title_color' => '#111827',
            'summary_title_font_size' => 20,
            'show_summary_icon' => true,
            'summary_icon_color' => '#3b82f6',

            // Summary Rows
            'show_subtotal' => true,
            'show_shipping' => true,
            'show_tax' => true,
            'show_discount_row' => true,
            'row_label_color' => '#6b7280',
            'row_value_color' => '#111827',
            'row_font_size' => 14,
            'row_spacing' => 12,

            // Total Row
            'total_label_color' => '#111827',
            'total_value_color' => '#059669',
            'total_font_size' => 18,
            'total_font_weight' => '700',
            'total_row_separator' => true,
            'total_separator_color' => '#e5e7eb',

            // Checkout Button
            'checkout_button_text' => 'Secure Checkout',
            'checkout_button_background' => '#059669',
            'checkout_button_text_color' => '#ffffff',
            'checkout_button_hover_background' => '#047857',
            'checkout_button_font_size' => 16,
            'checkout_button_padding' => array('top' => '16', 'right' => '24', 'bottom' => '16', 'left' => '24', 'unit' => 'px'),
            'checkout_button_border_radius' => 8,
            'show_checkout_icon' => true,

            // Security Badges
            'show_security_badges' => true,
            'security_badges_layout' => 'horizontal',
            'security_badge_spacing' => 8,
            'show_ssl_badge' => true,
            'ssl_badge_text' => 'SSL Secured',
        );
    }

}
