<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_emailUsersTable' ) ) {
    class AGSHOPGLUT_emailUsersTable extends AGSHOPGLUTP {

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
            parent::__construct( $field, $value, $unique, $where, $parent );
        }

        public function render() {
            echo wp_kses_post($this->field_before());

            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            ?>

            <div class="agshopglut-email-users-table">
                <div class="table-actions" style="margin-bottom: 15px;">
                    <button type="button" class="button refresh-users-table"><?php esc_htmlesc_html_e( 'Refresh Tables', 'shopglut' ); ?></button>
                    <?php if ( $is_pro_active ) : ?>
                        <button type="button" class="button select-all-users"><?php esc_htmlesc_html_e( 'Select All', 'shopglut' ); ?></button>
                        <button type="button" class="button deselect-all-users"><?php esc_htmlesc_html_e( 'Deselect All', 'shopglut' ); ?></button>
                        <span class="selected-count">0 <?php esc_htmlesc_html_e( 'selected', 'shopglut' ); ?></span>
                    <?php endif; ?>
                </div>
                
                <div id="users-table-container">
                    <?php echo wp_kses_post($this->render_notification_tables()); ?>
                </div>
            </div>

            <style>
                .agshopglut-email-users-table {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    padding: 20px;
                }
                .table-actions {
                    padding: 10px;
                    background: #f9f9f9;
                    border-radius: 3px;
                    margin-bottom: 15px;
                }
                .table-actions .button {
                    margin-right: 10px;
                }
                .selected-count {
                    font-weight: bold;
                    color: #0073aa;
                    margin-left: 10px;
                }
                .users-email-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .users-email-table th,
                .users-email-table td {
                    padding: 12px 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                .users-email-table th {
                    background: #f9f9f9;
                    font-weight: bold;
                }
                .users-email-table .check-column {
                    width: 40px;
                    text-align: center;
                }
                .wishlist-count {
                    font-weight: bold;
                    color: #0073aa;
                }
                .notification-types {
                    font-size: 11px;
                    color: #666;
                }
                .button-small {
                    padding: 4px 8px;
                    font-size: 11px;
                    margin: 0 2px;
                }
            </style>

            <script>
            jQuery(document).ready(function($) {
                // Refresh table functionality
                $('.refresh-users-table').on('click', function() {
                    var button = $(this);
                    button.text('Loading...');
                    
                    $.post(ajaxurl, {
                        action: 'shopglut_refresh_users_table',
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                    }, function(response) {
                        if (response.success) {
                            $('#users-table-container').html(response.data);
                            updateSelectedCount();
                        }
                        button.text('Refresh Table');
                    });
                });
                
                // Select/deselect all functionality for different table types
                $(document).on('change', '.select-all-main', function() {
                    $('.main-wishlist-checkbox').prop('checked', $(this).prop('checked'));
                    updateSelectedCount();
                });
                
                $(document).on('change', '.select-all-sublist', function() {
                    $('.sublist-checkbox').prop('checked', $(this).prop('checked'));
                    updateSelectedCount();
                });
                
                $(document).on('change', '.select-all-products', function() {
                    $('.product-checkbox').prop('checked', $(this).prop('checked'));
                    updateSelectedCount();
                });
                
                $('.select-all-users').on('click', function() {
                    $('.user-checkbox, .select-all-main, .select-all-sublist, .select-all-products').prop('checked', true);
                    updateSelectedCount();
                });
                
                $('.deselect-all-users').on('click', function() {
                    $('.user-checkbox, .select-all-main, .select-all-sublist, .select-all-products').prop('checked', false);
                    updateSelectedCount();
                });
                
                $(document).on('change', '.user-checkbox', function() {
                    updateSelectedCount();
                });
                
                function updateSelectedCount() {
                    var count = $('.user-checkbox:checked').length;
                    $('.selected-count').text(count + ' selected');
                    $('.selected-users-count').text('(' + count + ' selected)');
                    
                    // Enable/disable send email button based on selection and recipient type
                    var recipientType = $('input[name="recipient-type"]:checked').val();
                    var canSend = false;
                    
                    if (recipientType === 'selected' && count > 0) {
                        canSend = true;
                    } else if (recipientType !== 'selected') {
                        canSend = true;
                    }
                    
                    $('.send-bulk-email').prop('disabled', !canSend);
                }
                
                // Email sender form functionality
                $('#email-template-select').on('change', function() {
                    var template = $(this).val();
                    var subject = '';
                    
                    // Auto-populate subject based on template
                    switch(template) {
                        case 'price-drop':
                            subject = 'Great news! Price drop on your wishlist item';
                            break;
                        case 'back-in-stock':
                            subject = 'Your wishlist item is back in stock!';
                            break;
                        case 'wishlist-reminder':
                            subject = 'Don\'t forget about your wishlist items';
                            break;
                        case 'promotional':
                            subject = 'Special offer for you!';
                            break;
                        case 'social-update':
                            subject = 'Updates from your wishlist community';
                            break;
                        case 'new-products':
                            subject = 'New products you might like';
                            break;
                        case 'sale-alert':
                            subject = 'Sale alert on your favorite items!';
                            break;
                        case 'abandoned-wishlist':
                            subject = 'Complete your purchase - items waiting for you';
                            break;
                        case 'custom':
                            subject = '';
                            break;
                    }
                    
                    $('#email-subject').val(subject);
                    
                    // Show/hide custom message area
                    if (template === 'custom') {
                        $('.custom-message-row').show();
                    } else {
                        $('.custom-message-row').hide();
                    }
                });
                
                // Schedule type change handler
                $('#email-schedule-type').on('change', function() {
                    var type = $(this).val();
                    
                    $('.schedule-options').hide();
                    $('.recurring-options').hide();
                    
                    if (type === 'scheduled') {
                        $('.schedule-options').show();
                    } else if (type === 'recurring') {
                        $('.recurring-options').show();
                    }
                });
                
                // Recipient type change handler
                $('input[name="recipient-type"]').on('change', function() {
                    var type = $(this).val();
                    
                    $('.custom-emails-row').hide();
                    
                    if (type === 'custom') {
                        $('.custom-emails-row').show();
                    }
                    
                    updateSelectedCount();
                });
                
                // Preview template button
                $('.preview-template-btn').on('click', function() {
                    var template = $('#email-template-select').val();
                    var subject = $('#email-subject').val();
                    
                    // Show preview modal with template content
                    showEmailPreview(template, subject);
                });
                
                // Close preview modal
                $('.close-preview').on('click', function() {
                    $('.email-preview-modal').hide();
                });
                
                // Send test email
                $('.send-test-email-btn').on('click', function() {
                    if (confirm('Send test email to admin email address?')) {
                        sendTestEmail();
                    }
                });
                
                // Send bulk email
                $('.send-bulk-email').on('click', function() {
                    var recipientType = $('input[name="recipient-type"]:checked').val();
                    var scheduleType = $('#email-schedule-type').val();
                    var selectedCount = $('.user-checkbox:checked').length;
                    
                    var message = 'Are you sure you want to send this email?';
                    
                    if (recipientType === 'selected') {
                        message = 'Send email to ' + selectedCount + ' selected users?';
                    } else if (recipientType === 'main-wishlist') {
                        message = 'Send email to all users with main wishlist notifications?';
                    } else if (recipientType === 'sublist') {
                        message = 'Send email to all users with sublist notifications?';
                    } else if (recipientType === 'product-subscribers') {
                        message = 'Send email to all product subscribers?';
                    }
                    
                    if (scheduleType === 'scheduled') {
                        message = message.replace('Send', 'Schedule');
                    } else if (scheduleType === 'recurring') {
                        message = message.replace('Send', 'Set up recurring');
                    }
                    
                    if (confirm(message)) {
                        sendBulkEmail();
                    }
                });
                
                // View scheduled emails
                $('.view-scheduled-emails').on('click', function() {
                    // This would open a modal or redirect to scheduled emails page
                    alert('This would show scheduled emails list (to be implemented)');
                });
                
                function showEmailPreview(template, subject) {
                    var previewContent = generateEmailPreview(template, subject);
                    $('.email-preview-modal .preview-content').html(previewContent);
                    $('.email-preview-modal').show();
                }
                
                function generateEmailPreview(template, subject) {
                    var content = '<h3>Subject: ' + subject + '</h3><hr>';
                    
                    switch(template) {
                        case 'price-drop':
                            content += '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">' +
                                '<h2 style="color: #e74c3c;">🔥 Price Drop Alert!</h2>' +
                                '<p>Great news! The price has dropped on an item from your wishlist:</p>' +
                                '<div style="border: 1px solid #ddd; padding: 15px; margin: 20px 0;">' +
                                '<strong>{{product_name}}</strong><br>' +
                                'Was: <span style="text-decoration: line-through;">{{old_price}}</span><br>' +
                                'Now: <span style="color: #e74c3c; font-size: 18px;">{{new_price}}</span>' +
                                '</div>' +
                                '<p><a href="{{product_url}}" style="background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">Buy Now</a></p>' +
                                '</div>';
                            break;
                        case 'back-in-stock':
                            content += '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">' +
                                '<h2 style="color: #27ae60;">✅ Back in Stock!</h2>' +
                                '<p>Good news {{user_name}}! An item from your wishlist is now available:</p>' +
                                '<div style="border: 1px solid #ddd; padding: 15px; margin: 20px 0;">' +
                                '<strong>{{product_name}}</strong><br>' +
                                'Price: {{product_price}}<br>' +
                                'Status: <span style="color: #27ae60;">In Stock</span>' +
                                '</div>' +
                                '<p><a href="{{product_url}}" style="background: #27ae60; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">Shop Now</a></p>' +
                                '</div>';
                            break;
                        case 'wishlist-reminder':
                            content += '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">' +
                                '<h2 style="color: #9b59b6;">📝 Your Wishlist Awaits</h2>' +
                                '<p>Hi {{user_name}},</p>' +
                                '<p>You have {{wishlist_count}} items in your wishlist that are waiting for you:</p>' +
                                '<p>Don\'t let these great items slip away!</p>' +
                                '<p><a href="{{wishlist_url}}" style="background: #9b59b6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">View My Wishlist</a></p>' +
                                '</div>';
                            break;
                        default:
                            content += '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">' +
                                '<p>This is a preview of the ' + template.replace('-', ' ') + ' email template.</p>' +
                                '<p>The actual content will be dynamically generated based on user data.</p>' +
                                '</div>';
                    }
                    
                    return content;
                }
                
                function sendTestEmail() {
                    var $status = $('.email-sending-status');
                    $status.removeClass('success error').addClass('sending').text('Sending test email...');
                    
                    var data = {
                        action: 'shopglut_send_test_email',
                        template: $('#email-template-select').val(),
                        subject: $('#email-subject').val(),
                        custom_content: $('#custom-email-content').val(),
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                    };
                    
                    $.post(ajaxurl, data, function(response) {
                        if (response.success) {
                            $status.removeClass('sending').addClass('success').text('Test email sent successfully!');
                        } else {
                            $status.removeClass('sending').addClass('error').text('Failed to send test email: ' + (response.data || 'Unknown error'));
                        }
                        
                        setTimeout(function() {
                            $status.removeClass('sending success error').text('');
                        }, 5000);
                    }).fail(function() {
                        $status.removeClass('sending').addClass('error').text('Network error occurred');
                    });
                }
                
                function sendBulkEmail() {
                    var $status = $('.email-sending-status');
                    var $button = $('.send-bulk-email');
                    
                    $status.removeClass('success error').addClass('sending').text('Sending emails...');
                    $button.prop('disabled', true).text('Sending...');
                    
                    var selectedUsers = [];
                    $('.user-checkbox:checked').each(function() {
                        selectedUsers.push($(this).val());
                    });
                    
                    var data = {
                        action: 'shopglut_send_bulk_email',
                        template: $('#email-template-select').val(),
                        subject: $('#email-subject').val(),
                        custom_content: $('#custom-email-content').val(),
                        recipient_type: $('input[name="recipient-type"]:checked').val(),
                        selected_users: selectedUsers,
                        custom_emails: $('#custom-emails').val(),
                        schedule_type: $('#email-schedule-type').val(),
                        schedule_date: $('#email-schedule-date').val(),
                        recurring_frequency: $('#recurring-frequency').val(),
                        recurring_start: $('#recurring-start').val(),
                        recurring_end: $('#recurring-end').val(),
                        track_opens: $('#track-opens').is(':checked'),
                        track_clicks: $('#track-clicks').is(':checked'),
                        nonce: '<?php echo esc_attr( wp_create_nonce( "shopglut_admin_nonce" ) ); ?>'
                    };
                    
                    $.post(ajaxurl, data, function(response) {
                        if (response.success) {
                            var message = response.data.message || 'Emails sent successfully!';
                            $status.removeClass('sending').addClass('success').text(message);
                            
                            // Reset form if immediate send
                            if (data.schedule_type === 'now') {
                                $('.user-checkbox').prop('checked', false);
                                updateSelectedCount();
                            }
                        } else {
                            $status.removeClass('sending').addClass('error').text('Failed to send emails: ' + (response.data || 'Unknown error'));
                        }
                        
                        $button.prop('disabled', false).text('Send Email');
                        
                        setTimeout(function() {
                            $status.removeClass('sending success error').text('');
                        }, 8000);
                    }).fail(function() {
                        $status.removeClass('sending').addClass('error').text('Network error occurred');
                        $button.prop('disabled', false).text('Send Email');
                    });
                }
            });
            </script>

            <?php
            echo wp_kses_post($this->field_after());
        }

        private function render_notification_tables() {
            global $wpdb;
            
            $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
            $wishlist_social_table = $wpdb->prefix . 'shopglut_wishlist_social';
            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            
            ob_start();
            ?>
            
            <!-- Table 1: Main Wishlist Notifications -->
            <div class="notification-table-section">
                <h3 class="table-title"><?php esc_htmlesc_html_e( 'Main Wishlist Notifications', 'shopglut' ); ?></h3>
                <p class="table-description"><?php esc_htmlesc_html_e( 'Users with main wishlist notification preferences from shopglut_wishlist.wishlist_notifications', 'shopglut' ); ?></p>
                <?php echo wp_kses_post($this->render_main_wishlist_table()); ?>
            </div>
            
            <!-- Table 2: Sublist Notifications -->
            <div class="notification-table-section">
                <h3 class="table-title"><?php esc_htmlesc_html_e( 'Sublist Notifications', 'shopglut' ); ?></h3>
                <p class="table-description"><?php esc_htmlesc_html_e( 'Users with sublist notification preferences from shopglut_wishlist.sublist_notifications', 'shopglut' ); ?></p>
                <?php echo wp_kses_post($this->render_sublist_notifications_table()); ?>
            </div>
            
            <!-- Table 3: Individual Product Subscriptions -->
            <div class="notification-table-section">
                <h3 class="table-title"><?php esc_htmlesc_html_e( 'Individual Product Subscriptions', 'shopglut' ); ?></h3>
                <p class="table-description"><?php esc_htmlesc_html_e( 'Individual product subscriptions from shopglut_wishlist_social.product_subscriptions', 'shopglut' ); ?></p>
                <?php echo wp_kses_post($this->render_product_subscriptions_table()); ?>
            </div>
            
            <!-- Email Sender Section -->
            <div class="agshopglut-email-sender">
                <?php if ( $is_pro_active ) : ?>
                    <div class="email-sender-form">
                        <div class="sender-section">
                            <h3 class="table-title" style="margin: 0 0 20px 0;"><?php esc_htmlesc_html_e( 'Send Email to Selected Users', 'shopglut' ); ?></h3>
                            
                            <div class="form-row">
                                <label for="email-template-select"><?php esc_htmlesc_html_e( 'Select Template:', 'shopglut' ); ?></label>
                                <select id="email-template-select" style="width: 300px;">
                                    <option value="price-drop"><?php esc_htmlesc_html_e( 'Price Drop Notification', 'shopglut' ); ?></option>
                                    <option value="back-in-stock"><?php esc_htmlesc_html_e( 'Back in Stock', 'shopglut' ); ?></option>
                                    <option value="wishlist-reminder"><?php esc_htmlesc_html_e( 'Wishlist Reminder', 'shopglut' ); ?></option>
                                    <option value="promotional"><?php esc_htmlesc_html_e( 'Promotional Email', 'shopglut' ); ?></option>
                                    <option value="social-update"><?php esc_htmlesc_html_e( 'Social Update', 'shopglut' ); ?></option>
                                    <option value="new-products"><?php esc_htmlesc_html_e( 'New Products Alert', 'shopglut' ); ?></option>
                                    <option value="sale-alert"><?php esc_htmlesc_html_e( 'Sale Alert', 'shopglut' ); ?></option>
                                    <option value="abandoned-wishlist"><?php esc_htmlesc_html_e( 'Abandoned Wishlist', 'shopglut' ); ?></option>
                                    <option value="custom"><?php esc_htmlesc_html_e( 'Custom Message', 'shopglut' ); ?></option>
                                </select>
                                <button type="button" class="button preview-template-btn"><?php esc_htmlesc_html_e( 'Preview Template', 'shopglut' ); ?></button>
                            </div>
                            
                            <div class="form-row">
                                <label for="email-subject"><?php esc_htmlesc_html_e( 'Subject:', 'shopglut' ); ?></label>
                                <input type="text" id="email-subject" style="width: 500px;" placeholder="<?php esc_attr_e( 'Enter email subject', 'shopglut' ); ?>" />
                            </div>
                            
                            <div class="form-row custom-message-row" style="display: none;">
                                <label for="custom-email-content"><?php esc_htmlesc_html_e( 'Custom Message:', 'shopglut' ); ?></label>
                                <textarea id="custom-email-content" rows="8" style="width: 100%;" placeholder="<?php esc_attr_e( 'Enter your custom email content...', 'shopglut' ); ?>"></textarea>
                                <small class="description"><?php esc_htmlesc_html_e( 'You can use variables like {{user_name}}, {{site_name}}, {{current_date}}, {{product_name}}, {{wishlist_count}}, etc.', 'shopglut' ); ?></small>
                            </div>
                            
                            <div class="form-row">
                                <label for="email-schedule-type"><?php esc_htmlesc_html_e( 'Send:', 'shopglut' ); ?></label>
                                <select id="email-schedule-type">
                                    <option value="now"><?php esc_htmlesc_html_e( 'Send Now', 'shopglut' ); ?></option>
                                    <option value="scheduled"><?php esc_htmlesc_html_e( 'Schedule for Later', 'shopglut' ); ?></option>
                                    <option value="recurring"><?php esc_html_e( 'Recurring Email', 'shopglut' ); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-row schedule-options" style="display: none;">
                                <label for="email-schedule-date"><?php esc_html_e( 'Schedule Date & Time:', 'shopglut' ); ?></label>
                                <input type="datetime-local" id="email-schedule-date" />
                            </div>
                            
                            <div class="form-row recurring-options" style="display: none;">
                                <label><?php esc_html_e( 'Recurring Settings:', 'shopglut' ); ?></label>
                                <div class="recurring-settings">
                                    <div class="recurring-row">
                                        <label for="recurring-frequency"><?php esc_html_e( 'Frequency:', 'shopglut' ); ?></label>
                                        <select id="recurring-frequency">
                                            <option value="daily"><?php esc_html_e( 'Daily', 'shopglut' ); ?></option>
                                            <option value="weekly"><?php esc_html_e( 'Weekly', 'shopglut' ); ?></option>
                                            <option value="biweekly"><?php esc_html_e( 'Bi-weekly', 'shopglut' ); ?></option>
                                            <option value="monthly"><?php esc_html_e( 'Monthly', 'shopglut' ); ?></option>
                                        </select>
                                    </div>
                                    <div class="recurring-row">
                                        <label for="recurring-start"><?php esc_html_e( 'Start Date:', 'shopglut' ); ?></label>
                                        <input type="datetime-local" id="recurring-start" />
                                    </div>
                                    <div class="recurring-row">
                                        <label for="recurring-end"><?php esc_html_e( 'End Date (Optional):', 'shopglut' ); ?></label>
                                        <input type="datetime-local" id="recurring-end" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <label><?php esc_html_e( 'Recipients:', 'shopglut' ); ?></label>
                                <div class="recipient-options">
                                    <label>
                                        <input type="radio" name="recipient-type" value="selected" checked />
                                        <?php esc_html_e( 'Selected users from table above', 'shopglut' ); ?>
                                        <span class="selected-users-count">(0 selected)</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="recipient-type" value="main-wishlist" />
                                        <?php esc_html_e( 'All users with main wishlist notifications', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="recipient-type" value="sublist" />
                                        <?php esc_html_e( 'All users with sublist notifications', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="recipient-type" value="product-subscribers" />
                                        <?php esc_html_e( 'All product subscribers', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="recipient-type" value="custom" />
                                        <?php esc_html_e( 'Custom email addresses', 'shopglut' ); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-row custom-emails-row" style="display: none;">
                                <label for="custom-emails"><?php esc_html_e( 'Email Addresses:', 'shopglut' ); ?></label>
                                <textarea id="custom-emails" rows="4" style="width: 100%;" placeholder="<?php esc_attr_e( 'Enter email addresses, one per line or comma separated', 'shopglut' ); ?>"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <label><?php esc_html_e( 'Email Options:', 'shopglut' ); ?></label>
                                <div class="email-options">
                                    <label>
                                        <input type="checkbox" id="send-test-email" />
                                        <?php esc_html_e( 'Send test email to admin first', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="checkbox" id="track-opens" checked />
                                        <?php esc_html_e( 'Track email opens', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="checkbox" id="track-clicks" checked />
                                        <?php esc_html_e( 'Track link clicks', 'shopglut' ); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <button type="button" class="button button-primary send-bulk-email" disabled>
                                    <?php esc_html_e( 'Send Email', 'shopglut' ); ?>
                                </button>
                                <button type="button" class="button send-test-email-btn" style="margin-left: 10px;">
                                    <?php esc_html_e( 'Send Test', 'shopglut' ); ?>
                                </button>
                                <button type="button" class="button view-scheduled-emails" style="margin-left: 10px;">
                                    <?php esc_html_e( 'View Scheduled', 'shopglut' ); ?>
                                </button>
                                <span class="email-sending-status" style="margin-left: 15px;"></span>
                            </div>
                            
                            <div class="email-preview-modal" style="display: none;">
                                <div class="modal-overlay">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4><?php esc_html_e( 'Email Preview', 'shopglut' ); ?></h4>
                                            <button type="button" class="close-preview">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="preview-content"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="button close-preview"><?php esc_html_e( 'Close', 'shopglut' ); ?></button>
                                            <button type="button" class="button button-primary use-template"><?php esc_html_e( 'Use This Template', 'shopglut' ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="notification-table-section">
                        <h3 class="table-title"><?php esc_html_e( 'Email Sender (Pro Feature)', 'shopglut' ); ?></h3>
                        <div style="padding: 20px;">
                            <p><?php esc_html_e( 'Email sender functionality with templates and scheduling is available in the Pro version.', 'shopglut' ); ?></p>
                            <p><?php esc_html_e( 'Features include:', 'shopglut' ); ?></p>
                            <ul style="margin-left: 20px;">
                                <li><?php esc_html_e( 'Pre-built email templates', 'shopglut' ); ?></li>
                                <li><?php esc_html_e( 'Custom message composition', 'shopglut' ); ?></li>
                                <li><?php esc_html_e( 'Email scheduling', 'shopglut' ); ?></li>
                                <li><?php esc_html_e( 'Recurring email campaigns', 'shopglut' ); ?></li>
                                <li><?php esc_html_e( 'Email tracking & analytics', 'shopglut' ); ?></li>
                                <li><?php esc_html_e( 'Test email functionality', 'shopglut' ); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <style>
                .notification-table-section {
                    margin-bottom: 40px;
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    overflow: hidden;
                }
                
                .table-title {
                    background: #f8f9fa;
                    padding: 15px 20px;
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                    color: #2c3e50;
                    border-bottom: 1px solid #e9ecef;
                }
                
                .table-description {
                    background: #f8f9fa;
                    padding: 0 20px 15px;
                    margin: 0;
                    font-size: 13px;
                    color: #6c757d;
                    font-style: italic;
                }
                
                .notification-tables .wp-list-table {
                    margin: 0;
                    border: none;
                }
                
                .notification-types-cell {
                    max-width: 200px;
                    word-wrap: break-word;
                }
                
                .notification-badge {
                    display: inline-block;
                    background: #007cba;
                    color: white;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 10px;
                    margin: 1px;
                }
                
                .sublist-name {
                    font-weight: 600;
                    color: #2c3e50;
                }
                
                .product-info {
                    font-size: 12px;
                    color: #666;
                }
                
                .source-badge {
                    display: inline-block;
                    padding: 3px 8px;
                    border-radius: 4px;
                    font-size: 11px;
                    font-weight: 600;
                    text-transform: uppercase;
                }
                
                .source-wishlist {
                    background: #e1f5fe;
                    color: #0277bd;
                }
                
                .source-social {
                    background: #f3e5f5;
                    color: #7b1fa2;
                }
                
                .agshopglut-email-sender {
                    margin-top: 40px;
                }
                
                
                .sender-section {
                    max-width: 100%;
                }
                
                .form-row {
                    margin-bottom: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                }
                
                .form-row label {
                    font-weight: 600;
                    color: #2c3e50;
                    font-size: 14px;
                }
                
                .form-row input[type="text"],
                .form-row input[type="datetime-local"],
                .form-row select,
                .form-row textarea {
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                }
                
                .form-row textarea {
                    resize: vertical;
                    min-height: 100px;
                }
                
                .recipient-options label,
                .email-options label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 400;
                    cursor: pointer;
                }
                
                .recipient-options input[type="radio"],
                .email-options input[type="checkbox"] {
                    margin-right: 8px;
                }
                
                .selected-users-count {
                    color: #007cba;
                    font-weight: 600;
                    margin-left: 5px;
                }
                
                .recurring-settings {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 15px;
                    margin-top: 10px;
                }
                
                .recurring-row {
                    display: flex;
                    flex-direction: column;
                    gap: 5px;
                }
                
                .recurring-row label {
                    font-size: 13px;
                    font-weight: 500;
                }
                
                .email-preview-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 999999;
                }
                
                .modal-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(4px);
                }
                
                .modal-content {
                    background: white;
                    border-radius: 8px;
                    max-width: 800px;
                    width: 90%;
                    max-height: 80vh;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                }
                
                .modal-header {
                    padding: 20px 25px 15px;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    background: #f8f9fa;
                    border-radius: 8px 8px 0 0;
                }
                
                .modal-header h4 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                    color: #2c3e50;
                }
                
                .close-preview {
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    padding: 0;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 4px;
                }
                
                .close-preview:hover {
                    background: #e9ecef;
                }
                
                .modal-body {
                    padding: 25px;
                    overflow-y: auto;
                    flex: 1;
                }
                
                .modal-footer {
                    padding: 15px 25px 20px;
                    border-top: 1px solid #eee;
                    display: flex;
                    gap: 10px;
                    justify-content: flex-end;
                    background: #f8f9fa;
                    border-radius: 0 0 8px 8px;
                }
                
                .email-sending-status {
                    font-weight: 600;
                    padding: 5px 0;
                }
                
                .email-sending-status.sending {
                    color: #f39c12;
                }
                
                .email-sending-status.success {
                    color: #27ae60;
                }
                
                .email-sending-status.error {
                    color: #e74c3c;
                }
                
                @media (max-width: 768px) {
                    .recurring-settings {
                        grid-template-columns: 1fr;
                    }
                    
                    .modal-content {
                        width: 95%;
                        max-height: 90vh;
                    }
                    
                    .modal-header,
                    .modal-body,
                    .modal-footer {
                        padding: 15px;
                    }
                    
                    .form-row input[type="text"] {
                        width: 100% !important;
                        max-width: none;
                    }
                }
            </style>
            
            <?php
            return ob_get_clean();
        }
        
        private function render_main_wishlist_table() {
            global $wpdb;
            $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
            $users = $wpdb->get_results(
                "SELECT DISTINCT 
                    w.id,
                    w.wish_user_id,
                    w.username,
                    w.useremail,
                    w.wishlist_notifications,
                    w.product_added_time
                FROM {$wpdb->prefix}shopglut_wishlist w
                WHERE w.wishlist_notifications IS NOT NULL 
                AND w.wishlist_notifications != ''
                AND w.wishlist_notifications != '[]'
                AND w.useremail IS NOT NULL 
                AND w.useremail != ''
                ORDER BY w.product_added_time DESC
                LIMIT 100"
            );
            
            ob_start();
            ?>
            <div class="notification-tables">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <?php if ( $is_pro_active ) : ?>
                            <th class="check-column"><input type="checkbox" class="select-all-main" /></th>
                            <?php endif; ?>
                            <th><?php esc_html_e( 'User ID', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Username', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Email', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Notification Types', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Registered', 'shopglut' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( !empty($users) ) : ?>
                            <?php foreach ( $users as $user ) : ?>
                                <?php
                                $notifications = json_decode( $user->wishlist_notifications ?? '[]', true );
                                $notifications = is_array($notifications) ? $notifications : [];
                                ?>
                                <tr>
                                    <?php if ( $is_pro_active ) : ?>
                                    <td class="check-column">
                                        <input type="checkbox" class="user-checkbox main-wishlist-checkbox" value="<?php echo esc_attr( $user->wish_user_id ); ?>" />
                                    </td>
                                    <?php endif; ?>
                                    <td><?php echo esc_html( $user->wish_user_id ); ?></td>
                                    <td><?php echo esc_html( $user->username ?: 'N/A' ); ?></td>
                                    <td><?php echo esc_html( $user->useremail ); ?></td>
                                    <td class="notification-types-cell">
                                        <?php if ( !empty($notifications) ) : ?>
                                            <?php foreach ( $notifications as $notification ) : ?>
                                                <span class="notification-badge"><?php echo esc_html( $notification ); ?></span>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <em><?php esc_html_e( 'None', 'shopglut' ); ?></em>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $user->product_added_time ) ) ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="<?php echo $is_pro_active ? '6' : '5'; ?>">
                                    <?php esc_html_e( 'No main wishlist notifications found.', 'shopglut' ); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }
        
        private function render_sublist_notifications_table() {
            global $wpdb;
            $wishlist_table = $wpdb->prefix . 'shopglut_wishlist';
            $wishlist_social_table = $wpdb->prefix . 'shopglut_wishlist_social';
            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            
            // Note: sublist_notifications column has been removed, now using social table
            $wishlist_users = [];
            
            // Get users from shopglut_wishlist_social table with notification_settings  
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
            $social_users = $wpdb->get_results(
                "SELECT DISTINCT 
                    s.id,
                    s.wishlist_user_id,
                    s.list_name,
                    s.notification_settings,
                    s.created_at,
                    'social' as source
                FROM {$wpdb->prefix}shopglut_wishlist_social s
                WHERE s.notification_settings IS NOT NULL 
                AND s.notification_settings != ''
                AND s.notification_settings != '{}'
                AND s.list_name IS NOT NULL 
                AND s.list_name != ''
                ORDER BY s.created_at DESC
                LIMIT 100"
            );
            
            // Combine and process data
            $combined_data = [];
            
            // Process wishlist users (removed due to migration to social table)
            
            // Process social users
            if ($social_users) {
                foreach ($social_users as $user) {
                    $notification_settings = json_decode( $user->notification_settings ?? '{}', true );
                    
                    if (is_array($notification_settings) && !empty($notification_settings)) {
                        $email = $notification_settings['email'] ?? '';
                        $notify_types = $notification_settings['notify_types'] ?? [];
                        $price_threshold = $notification_settings['price_threshold'] ?? null;
                        $updated_at = $notification_settings['updated_at'] ?? null;
                        
                        if ($email && !empty($notify_types)) {
                            $combined_data[] = (object) [
                                'user_id' => $user->wishlist_user_id,
                                'username' => 'N/A',
                                'email' => $email,
                                'sublist_name' => $user->list_name,
                                'notifications' => $notify_types,
                                'date' => $user->created_at,
                                'source' => 'social',
                                'price_threshold' => $price_threshold,
                                'updated_at' => $updated_at
                            ];
                        }
                    }
                }
            }
            
            // Sort by date (most recent first)
            usort($combined_data, function($a, $b) {
                return strtotime($b->date) - strtotime($a->date);
            });
            
            ob_start();
            ?>
            <div class="notification-tables">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <?php if ( $is_pro_active ) : ?>
                            <th class="check-column"><input type="checkbox" class="select-all-sublist" /></th>
                            <?php endif; ?>
                            <th><?php esc_html_e( 'User ID', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Username', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Email', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Sublist Name', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Notification Types', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Price Threshold', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Source', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Date', 'shopglut' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( !empty($combined_data) ) : ?>
                            <?php foreach ( $combined_data as $item ) : ?>
                                <tr>
                                    <?php if ( $is_pro_active ) : ?>
                                    <td class="check-column">
                                        <input type="checkbox" class="user-checkbox sublist-checkbox" value="<?php echo esc_attr( $item->user_id . '_' . $item->sublist_name . '_' . $item->source ); ?>" />
                                    </td>
                                    <?php endif; ?>
                                    <td><?php echo esc_html( $item->user_id ); ?></td>
                                    <td><?php echo esc_html( $item->username ?: 'N/A' ); ?></td>
                                    <td><?php echo esc_html( $item->email ); ?></td>
                                    <td>
                                        <span class="sublist-name"><?php echo esc_html( $item->sublist_name ); ?></span>
                                        <?php if ( $item->source === 'social' && $item->updated_at ) : ?>
                                            <br><small style="color: #666;"><?php esc_html_e( 'Updated:', 'shopglut' ); ?> <?php echo esc_html( date_i18n( 'M j, Y H:i', strtotime( $item->updated_at ) ) ); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="notification-types-cell">
                                        <?php if ( is_array($item->notifications) && !empty($item->notifications) ) : ?>
                                            <?php foreach ( $item->notifications as $notification ) : ?>
                                                <span class="notification-badge"><?php echo esc_html( $notification ); ?></span>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <em><?php esc_html_e( 'None', 'shopglut' ); ?></em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ( $item->price_threshold ) : ?>
                                            <?php echo wp_kses_post( wc_price( $item->price_threshold ) ); ?>
                                        <?php else : ?>
                                            <em><?php esc_html_e( 'N/A', 'shopglut' ); ?></em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="source-badge source-<?php echo esc_attr( $item->source ); ?>">
                                            <?php echo esc_html( ucfirst( $item->source ) ); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $item->date ) ) ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="<?php echo $is_pro_active ? '9' : '8'; ?>">
                                    <?php esc_html_e( 'No sublist notifications found.', 'shopglut' ); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }
        
        private function render_product_subscriptions_table() {
            global $wpdb;
            $wishlist_social_table = $wpdb->prefix . 'shopglut_wishlist_social';
            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query
            $social_users = $wpdb->get_results(
                "SELECT DISTINCT 
                    s.id,
                    s.wishlist_user_id,
                    s.list_name,
                    s.notification_settings,
                    s.product_subscriptions,
                    s.created_at
                FROM {$wpdb->prefix}shopglut_wishlist_social s
                WHERE s.product_subscriptions IS NOT NULL 
                AND s.product_subscriptions != ''
                AND s.product_subscriptions != '[]'
                ORDER BY s.created_at DESC
                LIMIT 100"
            );
            
            ob_start();
            ?>
            <div class="notification-tables">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <?php if ( $is_pro_active ) : ?>
                            <th class="check-column"><input type="checkbox" class="select-all-products" /></th>
                            <?php endif; ?>
                            <th><?php esc_html_e( 'User ID', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Email', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Product ID', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Product Name', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Notification Types', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Price Threshold', 'shopglut' ); ?></th>
                            <th><?php esc_html_e( 'Subscribed At', 'shopglut' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( !empty($social_users) ) : ?>
                            <?php foreach ( $social_users as $user ) : ?>
                                <?php
                                $product_subscriptions = json_decode( $user->product_subscriptions ?? '[]', true );
                                $notification_settings = json_decode( $user->notification_settings ?? '{}', true );
                                $user_email = $notification_settings['email'] ?? 'N/A';
                                
                                if ( is_array($product_subscriptions) && !empty($product_subscriptions) ) :
                                    foreach ( $product_subscriptions as $subscription ) :
                                        $product_id = $subscription['product_id'] ?? 0;
                                        $product = $product_id ? wc_get_product($product_id) : null;
                                        $notify_types = $subscription['notify_types'] ?? [];
                                        $price_threshold = $subscription['price_threshold'] ?? 0;
                                        $subscribed_at = $subscription['subscribed_at'] ?? '';
                                ?>
                                        <tr>
                                            <?php if ( $is_pro_active ) : ?>
                                            <td class="check-column">
                                                <input type="checkbox" class="user-checkbox product-checkbox-input" value="<?php echo esc_attr( $user->wishlist_user_id . '_' . $product_id ); ?>" />
                                            </td>
                                            <?php endif; ?>
                                            <td><?php echo esc_html( $user->wishlist_user_id ); ?></td>
                                            <td><?php echo esc_html( $user_email ); ?></td>
                                            <td><?php echo esc_html( $product_id ); ?></td>
                                            <td>
                                                <?php if ( $product ) : ?>
                                                    <strong><?php echo esc_html( $product->get_name() ); ?></strong>
                                                    <div class="product-info">
                                                        <?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?>
                                                        | <?php echo esc_html( $product->get_stock_status() ); ?>
                                                    </div>
                                                <?php else : ?>
                                                    <em><?php esc_html_e( 'Product not found', 'shopglut' ); ?></em>
                                                <?php endif; ?>
                                            </td>
                                            <td class="notification-types-cell">
                                                <?php if ( is_array($notify_types) && !empty($notify_types) ) : ?>
                                                    <?php foreach ( $notify_types as $notification ) : ?>
                                                        <span class="notification-badge"><?php echo esc_html( $notification ); ?></span>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <em><?php esc_html_e( 'None', 'shopglut' ); ?></em>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $price_threshold ? wp_kses_post( wc_price( $price_threshold ) ) : esc_html( __( 'N/A', 'shopglut' ) ); ?></td>
                                            <td><?php echo $subscribed_at ? esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $subscribed_at ) ) ) : esc_html( __( 'N/A', 'shopglut' ) ); ?></td>
                                        </tr>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="<?php echo $is_pro_active ? '8' : '7'; ?>">
                                    <?php esc_html_e( 'No product subscriptions found.', 'shopglut' ); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }
    }
}