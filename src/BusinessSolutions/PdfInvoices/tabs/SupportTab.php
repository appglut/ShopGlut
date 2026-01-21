<?php
namespace Shopglut\BusinessSolutions\PdfInvoices\Tabs;

if ( ! defined( 'ABSPATH' ) ) exit;

class SupportTab {

    public function render() {
        ?>
        <div class="shopglut-support-tab">
            <div class="support-header">
                <h2><?php echo esc_html__('Help & Support', 'shopglut'); ?></h2>
                <p><?php echo esc_html__('Get help with Shopglut PDF Invoices plugin. Find answers, contact support, and access documentation.', 'shopglut'); ?></p>
            </div>

            <div class="support-content">
                <div class="support-grid">
                    <!-- Documentation Section -->
                    <div class="support-card">
                        <div class="support-icon">📚</div>
                        <h3><?php echo esc_html__('Documentation', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Browse our comprehensive documentation to get started quickly with PDF invoices.', 'shopglut'); ?></p>
                        <a href="https://www.documentation.appglut.com/shopglut-pdf-invoices/" target="_blank" class="button button-primary">
                            <?php echo esc_html__('View Documentation', 'shopglut'); ?>
                        </a>
                    </div>

                    <!-- Video Tutorials -->
                    <div class="support-card">
                        <div class="support-icon">🎥</div>
                        <h3><?php echo esc_html__('Video Tutorials', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Watch step-by-step video guides for setting up and using PDF invoices.', 'shopglut'); ?></p>
                        <a href="https://www.youtube.com/appglut" target="_blank" class="button button-primary">
                            <?php echo esc_html__('Watch Tutorials', 'shopglut'); ?>
                        </a>
                    </div>

                    <!-- Contact Support -->
                    <div class="support-card">
                        <div class="support-icon">💬</div>
                        <h3><?php echo esc_html__('Contact Support', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Need help? Our support team is here to assist you with PDF invoice issues.', 'shopglut'); ?></p>
                        <a href="https://www.appglut.com/support" target="_blank" class="button button-primary">
                            <?php echo esc_html__('Contact Us', 'shopglut'); ?>
                        </a>
                    </div>

                    <!-- Feature Requests -->
                    <div class="support-card">
                        <div class="support-icon">💡</div>
                        <h3><?php echo esc_html__('Feature Requests', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Have an idea for PDF invoices? Submit feature requests and suggestions.', 'shopglut'); ?></p>
                        <a href="https://www.appglut.com/support/forum/plugin-feature-request/" target="_blank" class="button button-primary">
                            <?php echo esc_html__('Submit Request', 'shopglut'); ?>
                        </a>
                    </div>

                    <!-- Templates Gallery -->
                    <div class="support-card">
                        <div class="support-icon">🎨</div>
                        <h3><?php echo esc_html__('Invoice Templates', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Browse and download professional invoice templates for your business.', 'shopglut'); ?></p>
                        <a href="https://www.appglut.com/invoice-templates/" target="_blank" class="button button-primary">
                            <?php echo esc_html__('Browse Templates', 'shopglut'); ?>
                        </a>
                    </div>

                    <!-- Community Forum -->
                    <div class="support-card">
                        <div class="support-icon">👥</div>
                        <h3><?php echo esc_html__('Community Forum', 'shopglut'); ?></h3>
                        <p><?php echo esc_html__('Connect with other users, share tips, and get community support.', 'shopglut'); ?></p>
                        <a href="https://www.appglut.com/community/" target="_blank" class="button button-primary">
                            <?php echo esc_html__('Join Community', 'shopglut'); ?>
                        </a>
                    </div>
                </div>

                <!-- Quick Help Section -->
                <div class="quick-help-section">
                    <h3><?php echo esc_html__('Quick Help', 'shopglut'); ?></h3>
                    <div class="help-accordion">
                        <div class="help-item">
                            <button class="help-question" onclick="toggleHelp(this)">
                                <?php echo esc_html__('How do I generate my first PDF invoice?', 'shopglut'); ?>
                                <span class="help-toggle">+</span>
                            </button>
                            <div class="help-answer">
                                <p><?php echo esc_html__('To generate your first PDF invoice, go to WooCommerce > Orders, select an order, and click "Generate PDF Invoice". You can also enable automatic generation in the settings.', 'shopglut'); ?></p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <button class="help-question" onclick="toggleHelp(this)">
                                <?php echo esc_html__('Can I customize the invoice template?', 'shopglut'); ?>
                                <span class="help-toggle">+</span>
                            </button>
                            <div class="help-answer">
                                <p><?php echo esc_html__('Yes! Go to PDF Invoices > Settings > Invoice Template to customize colors, fonts, layout, and add your company logo and information.', 'shopglut'); ?></p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <button class="help-question" onclick="toggleHelp(this)">
                                <?php echo esc_html__('How do I add my company information?', 'shopglut'); ?>
                                <span class="help-toggle">+</span>
                            </button>
                            <div class="help-answer">
                                <p><?php echo esc_html__('Navigate to PDF Invoices > Settings > Company Info to add your business name, address, logo, tax information, and other company details.', 'shopglut'); ?></p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <button class="help-question" onclick="toggleHelp(this)">
                                <?php echo esc_html__('Can invoices be sent automatically via email?', 'shopglut'); ?>
                                <span class="help-toggle">+</span>
                            </button>
                            <div class="help-answer">
                                <p><?php echo esc_html__('Yes, with the Pro version you can set up automatic email delivery. Configure email templates and triggers in PDF Invoices > Settings > Email Settings.', 'shopglut'); ?></p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <button class="help-question" onclick="toggleHelp(this)">
                                <?php echo esc_html__('Is there a bulk invoice generation feature?', 'shopglut'); ?>
                                <span class="help-toggle">+</span>
                            </button>
                            <div class="help-answer">
                                <p><?php echo esc_html__('Yes! In WooCommerce > Orders, select multiple orders using checkboxes and choose "Generate PDF Invoices" from the bulk actions dropdown.', 'shopglut'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="system-info-section">
                    <h3><?php echo esc_html__('System Information', 'shopglut'); ?></h3>
                    <div class="system-info-grid">
                        <div class="info-item">
                            <strong><?php echo esc_html__('Plugin Version:', 'shopglut'); ?></strong>
                            <span><?php echo esc_html( defined('SHOPGLUT_VERSION') ? SHOPGLUT_VERSION : '1.0.0' ); ?></span>
                        </div>
                        <div class="info-item">
                            <strong><?php echo esc_html__('WordPress Version:', 'shopglut'); ?></strong>
                            <span><?php echo esc_html( get_bloginfo('version') ); ?></span>
                        </div>
                        <div class="info-item">
                            <strong><?php echo esc_html__('WooCommerce Version:', 'shopglut'); ?></strong>
                            <span><?php echo esc_html( defined('WC_VERSION') ? WC_VERSION : __('Not Installed', 'shopglut') ); ?></span>
                        </div>
                        <div class="info-item">
                            <strong><?php echo esc_html__('PHP Version:', 'shopglut'); ?></strong>
                            <span><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="info-item">
                            <strong><?php echo esc_html__('PDF Library:', 'shopglut'); ?></strong>
                            <span><?php echo class_exists('TCPDF') ? 'TCPDF Available' : 'Not Available'; ?></span>
                        </div>
                        <div class="info-item">
                            <strong><?php echo esc_html__('Max Upload Size:', 'shopglut'); ?></strong>
                            <span><?php echo esc_html( wp_max_upload_size() ? size_format(wp_max_upload_size()) : 'Unknown' ); ?></span>
                        </div>
                    </div>
                    
                    <div class="system-actions">
                        <button class="button" onclick="copySystemInfo()"><?php echo esc_html__('Copy System Info', 'shopglut'); ?></button>
                        <a href="<?php echo esc_url( admin_url('admin.php?page=shopglut_pdf_invoices_slips&tab=settings&subtab=general') ); ?>" class="button button-primary">
                            <?php echo esc_html__('Go to Settings', 'shopglut'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .shopglut-support-tab {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .support-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .support-header h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .support-header p {
            color: #666;
            font-size: 16px;
        }

        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .support-card {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #eee;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .support-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .support-card h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .support-card p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .quick-help-section, .system-info-section {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .help-accordion {
            margin-top: 20px;
        }

        .help-item {
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }

        .help-question {
            width: 100%;
            background: none;
            border: none;
            padding: 15px 0;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .help-question:hover {
            color: #0073aa;
        }

        .help-toggle {
            font-size: 20px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .help-answer {
            display: none;
            padding: 0 0 20px 0;
            color: #666;
            line-height: 1.6;
        }

        .help-answer.show {
            display: block;
        }

        .help-item.active .help-toggle {
            transform: rotate(45deg);
        }

        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .system-actions {
            margin-top: 20px;
        }

        .system-actions .button {
            margin-right: 10px;
        }
        </style>

        <script>
        function toggleHelp(button) {
            const item = button.closest('.help-item');
            const answer = item.querySelector('.help-answer');
            const isActive = item.classList.contains('active');
            
            // Close all other items
            document.querySelectorAll('.help-item').forEach(el => {
                el.classList.remove('active');
                el.querySelector('.help-answer').classList.remove('show');
            });
            
            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
                answer.classList.add('show');
            }
        }

        function copySystemInfo() {
            const systemInfo = `
Plugin Version: <?php echo esc_js( defined('SHOPGLUT_VERSION') ? SHOPGLUT_VERSION : '1.0.0' ); ?>
WordPress Version: <?php echo esc_js( get_bloginfo('version') ); ?>
WooCommerce Version: <?php echo esc_js( defined('WC_VERSION') ? WC_VERSION : 'Not Installed' ); ?>
PHP Version: <?php echo PHP_VERSION; ?>
PDF Library: <?php echo class_exists('TCPDF') ? 'TCPDF Available' : 'Not Available'; ?>
Max Upload Size: <?php echo esc_js( wp_max_upload_size() ? size_format(wp_max_upload_size()) : 'Unknown' ); ?>
Site URL: <?php echo esc_js( home_url() ); ?>
            `.trim();

            navigator.clipboard.writeText(systemInfo).then(function() {
                alert('<?php echo esc_js(__('System information copied to clipboard!', 'shopglut')); ?>');
            }).catch(function() {
                // Silently fail
            });
        }
        </script>
        <?php
    }
}