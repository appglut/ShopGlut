<?php
namespace Shopglut\BusinessSolutions\PdfInvoices\Tabs;

class FreeVsProTab {

    public function render() {
        ?>
        <div class="wrap">
            <div class="shopglut-free-vs-pro">
                <style>
                    .shopglut-free-vs-pro {
                        margin: 20px 0;
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 0;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .shopglut-comparison-header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 20px;
                        text-align: center;
                        border-radius: 8px 8px 0 0;
                    }
                    .shopglut-comparison-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 0;
                    }
                    .shopglut-comparison-table th {
                        background: #f8f9fa;
                        padding: 15px;
                        text-align: center;
                        font-weight: 600;
                        border-bottom: 2px solid #e9ecef;
                    }
                    .shopglut-comparison-table td {
                        padding: 12px 15px;
                        border-bottom: 1px solid #e9ecef;
                        vertical-align: top;
                    }
                    .shopglut-comparison-table tr:nth-child(even) {
                        background: #f8f9fa;
                    }
                    .shopglut-feature-list {
                        margin: 0;
                        padding: 0;
                        list-style: none;
                    }
                    .shopglut-feature-list li {
                        margin: 5px 0;
                        padding: 0;
                        display: flex;
                        align-items: center;
                    }
                    .shopglut-check-yes {
                        color: #28a745;
                        font-size: 16px;
                        margin-right: 8px;
                    }
                    .shopglut-check-no {
                        color: #dc3545;
                        font-size: 16px;
                        margin-right: 8px;
                    }
                    .shopglut-pro-button {
                        text-align: center;
                        padding: 30px;
                        background: #f8f9fa;
                        border-radius: 0 0 8px 8px;
                    }
                    .shopglut-upgrade-btn {
                        display: inline-block;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 12px 30px;
                        border-radius: 25px;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 16px;
                        transition: transform 0.2s;
                    }
                    .shopglut-upgrade-btn:hover {
                        transform: translateY(-2px);
                        color: white;
                        text-decoration: none;
                    }
                    .shopglut-guarantee {
                        margin-top: 15px;
                        color: #6c757d;
                        font-size: 14px;
                    }
                </style>

                <div class="shopglut-comparison-header">
                    <h1>ShopGlut PDF Invoices - Free vs Pro Features</h1>
                    <p>Compare features and upgrade to unlock powerful business tools</p>
                </div>

                <table class="shopglut-comparison-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Features</th>
                            <th style="width: 30%;">Free Version</th>
                            <th style="width: 30%;">Pro Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Document Types -->
                        <tr>
                            <td><strong>Supported Documents</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>PDF Invoices</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Packing Slips</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Credit Notes</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Shipping Labels</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>PDF Invoices</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Packing Slips</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Credit Notes</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Shipping Labels</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- UBL & Business Documents -->
                        <tr>
                            <td><strong>UBL & E-Invoicing</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Basic UBL 2.1</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>PEPPOL BIS</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Factur-X</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>ZUGFeRD</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Full UBL 2.1 & 2.0</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>PEPPOL BIS</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Factur-X</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>ZUGFeRD</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Email Attachments -->
                        <tr>
                            <td><strong>Email Attachments</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Invoice to Customer</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Packing Slip to Customer</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>All Email Types</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Invoice to Customer</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Packing Slip to Customer</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>All Email Types</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Document Numbering -->
                        <tr>
                            <td><strong>Document Numbering</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Sequential Numbers</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Custom Prefix/Suffix</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Yearly Reset</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Advanced Calculation</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Sequential Numbers</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Custom Prefix/Suffix</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Yearly Reset</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Advanced Calculation</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Customer Access -->
                        <tr>
                            <td><strong>Customer Access</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>My Account Downloads</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Guest Access</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Pretty URLs</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Custom Access Control</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>My Account Downloads</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Guest Access</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Pretty URLs</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Custom Access Control</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Template & Design -->
                        <tr>
                            <td><strong>Template & Design</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>4 Basic Templates</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Company Logo</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Basic Colors</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Advanced Customization</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>15+ Premium Templates</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Company Logo</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Full Color Control</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Advanced Customization</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Product Data -->
                        <tr>
                            <td><strong>Product Data</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Basic Product Info</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Product Images</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Product Attributes</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Product Variations</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Basic Product Info</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Product Images</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Product Attributes</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Product Variations</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Advanced Features -->
                        <tr>
                            <td><strong>Advanced Features</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Bulk Downloads</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Order List Columns</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Document Analytics</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Multi-language</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Bulk Downloads</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Order List Columns</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Document Analytics</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Multi-language</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- System & Security -->
                        <tr>
                            <td><strong>System & Security</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Basic File Storage</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>WP Filesystem API</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Access Control</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Document Encryption</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Advanced File Storage</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>WP Filesystem API</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Full Access Control</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Document Encryption</li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Support -->
                        <tr>
                            <td><strong>Support & Updates</strong></td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Community Support</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Free Updates</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Priority Support</li>
                                    <li><span class="dashicons dashicons-dismiss shopglut-check-no"></span>Custom Development</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="shopglut-feature-list">
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Community Support</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Free Updates</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Priority Support</li>
                                    <li><span class="dashicons dashicons-yes-alt shopglut-check-yes"></span>Custom Development</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="shopglut-pro-button">
                    <a href="#" class="shopglut-upgrade-btn">
                        <span class="dashicons dashicons-star-filled" style="margin-right: 8px;"></span>
                        Upgrade to ShopGlut Pro
                    </a>
                    <div class="shopglut-guarantee">
                        <p><strong>30-Day Money Back Guarantee</strong> • Premium Support • Regular Updates</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}