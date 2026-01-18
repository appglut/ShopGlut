<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_emailSender' ) ) {
    class AGSHOPGLUT_emailSender extends AGSHOPGLUTP {

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
            parent::__construct( $field, $value, $unique, $where, $parent );
        }

        public function render() {
            echo wp_kses_post($this->field_before());

            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            ?>

            <div class="agshopglut-email-sender">
                <?php if ( $is_pro_active ) : ?>
                    <div class="email-sender-form">
                        <div class="sender-section">
                            <h4><?php esc_html_e( 'Send Email to Selected Users', 'shopglut' ); ?></h4>
                            
                            <div class="form-row">
                                <label for="email-template-select"><?php esc_html_e( 'Select Template:', 'shopglut' ); ?></label>
                                <select id="email-template-select" style="width: 300px;">
                                    <option value="price-drop"><?php esc_html_e( 'Price Drop Notification', 'shopglut' ); ?></option>
                                    <option value="back-in-stock"><?php esc_html_e( 'Back in Stock', 'shopglut' ); ?></option>
                                    <option value="wishlist-reminder"><?php esc_html_e( 'Wishlist Reminder', 'shopglut' ); ?></option>
                                    <option value="promotional"><?php esc_html_e( 'Promotional Email', 'shopglut' ); ?></option>
                                    <option value="social-update"><?php esc_html_e( 'Social Update', 'shopglut' ); ?></option>
                                    <option value="custom"><?php esc_html_e( 'Custom Message', 'shopglut' ); ?></option>
                                </select>
                                <button type="button" class="button preview-template-btn"><?php esc_html_e( 'Preview Template', 'shopglut' ); ?></button>
                            </div>
                            
                            <div class="form-row">
                                <label for="email-subject"><?php esc_html_e( 'Subject:', 'shopglut' ); ?></label>
                                <input type="text" id="email-subject" style="width: 500px;" placeholder="<?php esc_html_e( 'Enter email subject', 'shopglut' ); ?>" />
                            </div>
                            
                            <div class="form-row custom-message-row" style="display: none;">
                                <label for="custom-email-content"><?php esc_html_e( 'Custom Message:', 'shopglut' ); ?></label>
                                <textarea id="custom-email-content" rows="8" style="width: 100%;" placeholder="<?php esc_html_e( 'Enter your custom email content...', 'shopglut' ); ?>"></textarea>
                                <small class="description"><?php esc_html_e( 'You can use variables like {{user_name}}, {{site_name}}, {{current_date}}, etc.', 'shopglut' ); ?></small>
                            </div>
                            
                            <div class="form-row">
                                <label for="email-schedule-type"><?php esc_html_e( 'Send:', 'shopglut' ); ?></label>
                                <select id="email-schedule-type">
                                    <option value="now"><?php esc_html_e( 'Send Now', 'shopglut' ); ?></option>
                                    <option value="scheduled"><?php esc_html_e( 'Schedule for Later', 'shopglut' ); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-row schedule-options" style="display: none;">
                                <label for="email-schedule-date"><?php esc_html_e( 'Schedule Date & Time:', 'shopglut' ); ?></label>
                                <input type="datetime-local" id="email-schedule-date" />
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
                                        <input type="radio" name="recipient-type" value="all" />
                                        <?php esc_html_e( 'All users with wishlists', 'shopglut' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="recipient-type" value="custom" />
                                        <?php esc_html_e( 'Custom email addresses', 'shopglut' ); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-row custom-emails-row" style="display: none;">
                                <label for="custom-emails"><?php esc_html_e( 'Email Addresses:', 'shopglut' ); ?></label>
                                <textarea id="custom-emails" rows="4" style="width: 100%;" placeholder="<?php esc_html_e( 'Enter email addresses, one per line or comma separated', 'shopglut' ); ?>"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <button type="button" class="button button-primary send-bulk-email" disabled>
                                    <?php esc_html_e( 'Send Email', 'shopglut' ); ?>
                                </button>
                                <span class="email-sending-status" style="margin-left: 10px;"></span>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="pro-notice">
                        <p><?php esc_html_e( 'Email sender functionality is available in the Pro version.', 'shopglut' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <style>
                .agshopglut-email-sender {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    padding: 20px;
                }
                .email-sender-form {
                    max-width: 800px;
                }
                .sender-section {
                    padding: 20px;
                    background: #f9f9f9;
                    border-radius: 4px;
                }
                .sender-section h4 {
                    margin-top: 0;
                    color: #0073aa;
                }
                .form-row {
                    margin-bottom: 15px;
                }
                .form-row label {
                    display: inline-block;
                    width: 140px;
                    font-weight: bold;
                    vertical-align: top;
                    padding-top: 5px;
                }
                .form-row textarea,
                .form-row input[type="text"],
                .form-row select {
                    font-size: 14px;
                }
                .recipient-options {
                    display: inline-block;
                }
                .recipient-options label {
                    display: block;
                    width: auto;
                    font-weight: normal;
                    margin-bottom: 8px;
                    cursor: pointer;
                }
                .recipient-options input[type="radio"] {
                    margin-right: 8px;
                }
                .selected-users-count {
                    color: #0073aa;
                    font-weight: bold;
                }
                .email-sending-status {
                    font-weight: bold;
                }
                .email-sending-status.success {
                    color: #28a745;
                }
                .email-sending-status.error {
                    color: #dc3545;
                }
                .pro-notice {
                    text-align: center;
                    padding: 40px;
                    color: #666;
                }
                .preview-template-btn {
                    margin-left: 10px;
                }
            </style>

            <script>
            jQuery(document).ready(function($) {
                // Update selected count from parent table
                function updateSelectedCount() {
                    var count = $('.user-checkbox:checked').length;
                    $('.selected-users-count').text('(' + count + ' selected)');
                    
                    // Enable/disable send button based on selection and recipient type
                    var recipientType = $('input[name="recipient-type"]:checked').val();
                    var canSend = false;
                    
                    if (recipientType === 'selected') {
                        canSend = count > 0;
                    } else if (recipientType === 'all') {
                        canSend = true;
                    } else if (recipientType === 'custom') {
                        canSend = $('#custom-emails').val().trim().length > 0;
                    }
                    
                    canSend = canSend && $('#email-subject').val().trim().length > 0;
                    
                    $('.send-bulk-email').prop('disabled', !canSend);
                }
                
                // Listen for changes in user table checkboxes
                $(document).on('change', '.user-checkbox, #select-all-users', updateSelectedCount);
                
                // Show/hide schedule options
                $('#email-schedule-type').on('change', function() {
                    if ($(this).val() === 'scheduled') {
                        $('.schedule-options').show();
                    } else {
                        $('.schedule-options').hide();
                    }
                });
                
                // Show/hide custom message
                $('#email-template-select').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('.custom-message-row').show();
                    } else {
                        $('.custom-message-row').hide();
                    }
                });
                
                // Show/hide custom emails
                $('input[name="recipient-type"]').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('.custom-emails-row').show();
                    } else {
                        $('.custom-emails-row').hide();
                    }
                    updateSelectedCount();
                });
                
                // Update send button when subject or custom emails change
                $('#email-subject, #custom-emails').on('input', updateSelectedCount);
                
                // Preview template
                $('.preview-template-btn').on('click', function() {
                    var template = $('#email-template-select').val();
                    var customContent = $('#custom-email-content').val();
                    
                    // Create preview modal
                    var modal = $('<div class="template-preview-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">' +
                        '<div style="background: white; padding: 20px; border-radius: 8px; max-width: 800px; max-height: 80vh; overflow-y: auto; position: relative;">' +
                        '<h3>Template Preview: ' + template + '</h3>' +
                        '<div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; min-height: 300px;">' +
                        '<p>Loading preview...</p>' +
                        '</div>' +
                        '<button class="button close-preview" style="margin-top: 15px;">Close Preview</button>' +
                        '</div>' +
                        '</div>');
                    
                    $('body').append(modal);
                    
                    // Load actual preview via AJAX if needed
                    $.post(ajaxurl, {
                        action: 'shopglut_preview_email_template',
                        template: template,
                        custom_content: customContent,
                        nonce: '<?php echo esc_attr(wp_create_nonce( "shopglut_admin_nonce" )); ?>'
                    }, function(response) {
                        if (response.success) {
                            modal.find('.template-preview-modal div div').html(response.data.preview);
                        }
                    });
                    
                    modal.find('.close-preview').on('click', function() {
                        modal.remove();
                    });
                    
                    modal.on('click', function(e) {
                        if (e.target === this) {
                            modal.remove();
                        }
                    });
                });
                
                // Send bulk email
                $('.send-bulk-email').on('click', function() {
                    var recipientType = $('input[name="recipient-type"]:checked').val();
                    var recipients = [];
                    
                    if (recipientType === 'selected') {
                        recipients = $('.user-checkbox:checked').map(function() {
                            return this.value;
                        }).get();
                        
                        if (recipients.length === 0) {
                            alert('<?php esc_html_e( "Please select at least one user.", "shopglut" ); ?>');
                            return;
                        }
                    } else if (recipientType === 'custom') {
                        var emailText = $('#custom-emails').val().trim();
                        if (!emailText) {
                            alert('<?php esc_html_e( "Please enter email addresses.", "shopglut" ); ?>');
                            return;
                        }
                        recipients = emailText.split(/[\n,]/).map(function(email) {
                            return email.trim();
                        }).filter(function(email) {
                            return email.length > 0;
                        });
                    }
                    
                    var emailData = {
                        action: 'shopglut_send_bulk_email',
                        nonce: '<?php echo esc_attr(wp_create_nonce( "shopglut_admin_nonce" )); ?>',
                        recipient_type: recipientType,
                        recipients: recipients,
                        template: $('#email-template-select').val(),
                        subject: $('#email-subject').val(),
                        custom_content: $('#custom-email-content').val(),
                        schedule_type: $('#email-schedule-type').val(),
                        schedule_date: $('#email-schedule-date').val()
                    };
                    
                    var button = $(this);
                    var status = $('.email-sending-status');
                    
                    button.prop('disabled', true).text('<?php esc_html_e( "Sending...", "shopglut" ); ?>');
                    status.removeClass('success error').text('<?php esc_html_e( "Preparing to send emails...", "shopglut" ); ?>');
                    
                    $.post(ajaxurl, emailData, function(response) {
                        if (response.success) {
                            status.addClass('success').text(response.data.message || '<?php esc_html_e( "Emails sent successfully!", "shopglut" ); ?>');
                            
                            // Reset form
                            $('#email-subject, #custom-email-content, #custom-emails').val('');
                            $('#email-template-select, #email-schedule-type').val($('#email-template-select option:first, #email-schedule-type option:first').val());
                            $('.custom-message-row, .schedule-options, .custom-emails-row').hide();
                            $('input[name="recipient-type"][value="selected"]').prop('checked', true);
                            
                        } else {
                            status.addClass('error').text('<?php esc_html_e( "Error:", "shopglut" ); ?> ' + (response.data || '<?php esc_html_e( "Unknown error occurred", "shopglut" ); ?>'));
                        }
                        
                        button.prop('disabled', false).text('<?php esc_html_e( "Send Email", "shopglut" ); ?>');
                        
                        // Clear status after 5 seconds
                        setTimeout(function() {
                            status.removeClass('success error').text('');
                        }, 5000);
                    }).fail(function() {
                        status.addClass('error').text('<?php esc_html_e( "Network error occurred", "shopglut" ); ?>');
                        button.prop('disabled', false).text('<?php esc_html_e( "Send Email", "shopglut" ); ?>');
                    });
                });
                
                // Initial count update
                updateSelectedCount();
            });
            </script>

            <?php
            echo wp_kses_post($this->field_after());
        }
    }
}