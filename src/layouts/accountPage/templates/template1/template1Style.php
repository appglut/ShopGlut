<?php
namespace Shopglut\layouts\accountPage\templates\template1;

if (!defined('ABSPATH')) {
	exit;
}

class template1Style {

    public function dynamicCss($layout_id = 0) {
        $settings = $this->getLayoutSettings($layout_id);

        ?>
        <style>
        /* ===== ACCOUNT PAGE CONTAINER ===== */
        .shopglut-woocommerce-account.template1 {
            width: 100%;
            margin: 0 auto;
            padding: 40px 20px;
            background: #f9fafb;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content-wrapper {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* ===== NAVIGATION SIDEBAR ===== */
        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation {
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 0;
            width: 100% !important;
            float: none !important;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li {
            margin: 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li:last-child {
            border-bottom: none;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li a {
            display: block;
            padding: 15px 20px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li a:hover {
            background: #f9fafb;
            color: #667eea;
            padding-left: 25px;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li.is-active a,
        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link--dashboard.is-active a {
            background: #667eea;
            color: #ffffff;
            border-left: 4px solid #5a67d8;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation li.is-active a:hover {
            background: #5a67d8;
        }

        /* ===== CONTENT AREA ===== */
        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content {
            padding: 40px;
            width: 100% !important;
            float: none !important;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content h2 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 20px 0;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content a {
            color: #000000ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content a.woocommerce-button.button.view{
            color: #ffffffff;
            text-decoration: none;
            transition: color 0.3s ease;
            background: #1082ba;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content a:hover {
            color: #dcdde4ff;
            text-decoration: underline;
        }

        /* ===== ORDERS TABLE ===== */
        .shopglut-woocommerce-account.template1 .woocommerce-orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .shopglut-woocommerce-account.template1 .woocommerce-orders-table thead {
            background: #f9fafb;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-orders-table thead th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-orders-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-orders-table tbody tr:hover {
            background: #f9fafb;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-orders-table tbody td {
            padding: 15px;
            color: #6b7280;
            font-size: 14px;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-order-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-order-status.status-processing {
            background: #fef3c7;
            color: #92400e;
        }

        .shopglut-woocommerce-account.template1 .woocommerce-order-status.status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-content-wrapper {
                grid-template-columns: 1fr;
            }

            .shopglut-woocommerce-account.template1 .woocommerce-MyAccount-navigation {
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
        }
        </style>
        <?php
    }

    private function getLayoutSettings($layout_id) {
        return array('layout_id' => $layout_id);
    }
}
