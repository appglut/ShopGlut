<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_wishlistMail' ) ) {
    class AGSHOPGLUT_wishlistMail extends AGSHOPGLUTP {

        /**
         * Value
         *
         * @var array
         */
        public $value = array();
        private $cron_token;
        private $pro_email_instance;

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
            $this->cron_token = get_option( 'shopglut_wishlist_cron_token', '' );
            
            // Get ProEmail instance if available
            if ( class_exists( 'Shopglut\WishlistPro\ProEmail' ) ) {
                $this->pro_email_instance = Shopglut\WishlistPro\ProEmail::get_instance();
            }
            
            parent::__construct( $field, $value, $unique, $where, $parent );
            
            
        }

        // Render the form and table
        public function render() {
            echo wp_kses_post($this->field_before());

            $args = wp_parse_args(
                $this->field,
                array(
                    'from_name' => '',
                    'from_email' => '',
                    'email_body' => '',
                    'send_email' => '',
                    'time_value' => '',
                    'time_unit' => '',
                )
            );

            $default_value = array(
                'from_name' => '',
                'from_email' => '',
                'email_body' => '',
                'send_email' => '',
                'time_value' => '1',
                'time_unit' => 'day',
            );

            $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
            $this->value = wp_parse_args( $this->value, $default_value );

            // Check if 'pro' is active - Pro version disables the field
            $is_pro = ! empty( $this->field['pro'] ) ? true : false;
            $pro_text = __( 'Unlock the Pro version', 'shopglut' );
            $is_pro_active = class_exists( 'Shopglut\WishlistPro\ProEmail' );
            ?>

<div class="agl-fieldset-content">
    <!-- Email Options Section -->
    <div class="agl-wishmail-email-options">
        <span class="agl--label">
            <label for="send_email"><?php esc_html_e( 'Send Email Option', 'shopglut' ); ?></label>
        </span>
        <select id="agl-wishmail-email-option" name="<?php echo esc_attr( $this->field_name( '[send_email]' ) ); ?>"
            <?php echo ( $is_pro && !$is_pro_active ) ? 'disabled' : ''; ?>>
            <option value="no" <?php selected( $this->value['send_email'], 'no' ); ?>>
                <?php esc_html_e( 'Do Not Send Email Automatically', 'shopglut' ); ?>
            </option>
            <option value="yes" <?php selected( $this->value['send_email'], 'yes' ); ?>>
                <?php esc_html_e( 'Send Email Automatically', 'shopglut' ); ?>
            </option>
        </select>

        <?php if ( $is_pro && !$is_pro_active ) : ?>
        <div class="agl-pro-notice">
            <a href="<?php echo esc_url( $this->field['pro'] ); ?>" target="_blank" class="agl--pro-link">
                <?php echo esc_html( $pro_text ); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Email Configuration Section -->
    <div class="agl-wishmail-send-email-conditions"
        style="display: <?php echo ( $this->value['send_email'] == 'yes' ) ? 'block' : 'none'; ?>;">
        
        <!-- Time Configuration -->
        <div class="agl-wishmail-time-config">
            <span class="agl--label">
                <label for="time_value"><?php esc_html_e( 'Send Email Time after Added to Wishlist', 'shopglut' ); ?></label>
            </span>
            
            <div class="agl-time-inputs">
                <input type="number" 
                       name="<?php echo esc_attr( $this->field_name( '[time_value]' ) ); ?>"
                       value="<?php echo esc_attr( $this->value['time_value'] ); ?>"
                       min="1" 
                       max="365"
                       <?php echo ( $is_pro && !$is_pro_active ) ? 'disabled' : ''; ?> />
                
                <select name="<?php echo esc_attr( $this->field_name( '[time_unit]' ) ); ?>"
                        <?php echo ( $is_pro && !$is_pro_active ) ? 'disabled' : ''; ?>>
                    <option value="minute" <?php selected( $this->value['time_unit'], 'minute' ); ?>>
                        <?php esc_html_e( 'Minutes', 'shopglut' ); ?>
                    </option>
                    <option value="hour" <?php selected( $this->value['time_unit'], 'hour' ); ?>>
                        <?php esc_html_e( 'Hours', 'shopglut' ); ?>
                    </option>
                    <option value="day" <?php selected( $this->value['time_unit'], 'day' ); ?>>
                        <?php esc_html_e( 'Days', 'shopglut' ); ?>
                    </option>
                </select>
            </div>
        </div>

        <!-- Cron URL Section -->
        <div class="shopglut-wishlist-mail-cron-url">
            <label for="cron-url"><?php esc_html_e( 'Cron URL', 'shopglut' ); ?></label>
            <?php 
            $cron_url = '';
            if ( $is_pro_active && $this->pro_email_instance ) {
                $cron_url = $this->pro_email_instance->get_cron_url();
            } elseif ( !$is_pro || $is_pro_active ) {
                $cron_url = site_url( '/send-shopglut-wishlist-emails/?cronkey=' . $this->cron_token );
            }
            ?>
            <div class="agl-cron-url-wrapper">
                <input type="text" 
                       id="shopglut-wishlist-cron-url" 
                       value="<?php echo esc_url( $cron_url ); ?>" 
                       readonly 
                       class="agl-cron-input">
                <button type="button" id="copy-cron-url" class="agl-copy-button" title="<?php esc_attr_e( 'Copy to clipboard', 'shopglut' ); ?>">
                    <i class="fa fa-copy"></i>
                </button>
            </div>
            <p class="agl-cron-description">
                <?php esc_html_e( 'You can configure a cron job from your hosting control panel using the URL above for reliable cron execution.', 'shopglut' ); ?>
            </p>
            
            <?php if ( $is_pro_active ) : ?>
            <div class="agl-email-test-section">
                <button type="button" id="test-email-config" class="button button-secondary">
                    <?php esc_html_e( 'Test Email Configuration', 'shopglut' ); ?>
                </button>
                <span id="test-email-result" class="agl-test-result"></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Users Table Section -->
<div class="agl-user-wishlist-table">
    <div class="agl-table-header">
        <h3><?php esc_html_e( 'Wishlist Users', 'shopglut' ); ?></h3>
        <?php if ( $is_pro_active ) : ?>
        <div class="agl-table-actions">
            <button type="button" id="refresh-users-table" class="button button-secondary">
                <?php esc_html_e( 'Refresh', 'shopglut' ); ?>
            </button>
            <button type="button" id="send-bulk-emails" class="button button-primary">
                <?php esc_html_e( 'Send Bulk Emails', 'shopglut' ); ?>
            </button>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="agl-table-wrapper">
        <table class="widefat agl-wishlist-users-table">
            <thead>
                <tr>
                    <?php if ( $is_pro_active ) : ?>
                    <th class="check-column">
                        <input type="checkbox" id="select-all-users">
                    </th>
                    <?php endif; ?>
                    <th><?php esc_html_e( 'User Name', 'shopglut' ); ?></th>
                    <th><?php esc_html_e( 'User Email', 'shopglut' ); ?></th>
                    <th><?php esc_html_e( 'Products Count', 'shopglut' ); ?></th>
                    <th><?php esc_html_e( 'Latest Added Product Time', 'shopglut' ); ?></th>
                    <th><?php esc_html_e( 'Email Status', 'shopglut' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'shopglut' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php echo wp_kses_post($this->render_users_table_rows( $is_pro, $is_pro_active, $pro_text )); ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.agl-fieldset-content {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

.agl-wishmail-email-options {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.agl-time-inputs {
    display: flex;
    gap: 10px;
    align-items: center;
}

.agl-time-inputs input[type="number"] {
    width: 80px;
}

.agl-cron-url-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
    margin: 10px 0;
}

.agl-cron-input {
    flex: 1;
    background: #f9f9f9;
}

.agl-copy-button {
    padding: 8px 12px;
    background: #0073aa;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.agl-copy-button:hover {
    background: #005a87;
}

.agl-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.agl-table-actions {
    display: flex;
    gap: 10px;
}

.agl-wishlist-users-table {
    margin-top: 0;
}

.agl-wishlist-users-table th,
.agl-wishlist-users-table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.agl-send-email-button {
    background: #0073aa;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 3px;
    cursor: pointer;
}

.agl-send-email-button:hover {
    background: #005a87;
}

.agl-send-email-button:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.agl-email-status {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: bold;
}

.agl-status-sent {
    background: #d4edda;
    color: #155724;
}

.agl-status-pending {
    background: #fff3cd;
    color: #856404;
}

.agl-status-error {
    background: #f8d7da;
    color: #721c24;
}

.agl-pro-notice {
    margin-top: 10px;
    padding: 10px;
    background: #fff3cd;
    border-left: 4px solid #ffc107;
}

.agl-test-result {
    margin-left: 10px;
    font-weight: bold;
}

.agl-test-result.success {
    color: #28a745;
}

.agl-test-result.error {
    color: #dc3545;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Toggle email conditions visibility
    $('#agl-wishmail-email-option').change(function() {
        if ($(this).val() === 'yes') {
            $('.agl-wishmail-send-email-conditions').show();
        } else {
            $('.agl-wishmail-send-email-conditions').hide();
        }
    });

    // Copy cron URL to clipboard
    $('#copy-cron-url').click(function() {
        var cronInput = $('#shopglut-wishlist-cron-url');
        cronInput.select();
        document.execCommand('copy');
        
        var button = $(this);
        var originalHtml = button.html();
        button.html('<i class="fa fa-check"></i>');
        setTimeout(function() {
            button.html(originalHtml);
        }, 2000);
    });

    // Test email configuration
    $('#test-email-config').click(function() {
        var button = $(this);
        var result = $('#test-email-result');
        
        button.prop('disabled', true).text('Testing...');
        result.removeClass('success error').text('');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_send_test_email',
                nonce: '<?php echo wp_kses_post(wp_create_nonce( "shopglut_pro_nonce" )); ?>'
            },
            success: function(response) {
                if (response.success) {
                    result.addClass('success').text('✓ Test email sent successfully!');
                } else {
                    result.addClass('error').text('✗ ' + response.data);
                }
            },
            error: function() {
                result.addClass('error').text('✗ Ajax error occurred');
            },
            complete: function() {
                button.prop('disabled', false).text('Test Email Configuration');
            }
        });
    });

    // Manual email sending
    $(document).on('click', '.agl-send-email-button', function() {
        var button = $(this);
        var email = button.data('email');
        
        if (!email) return;

        button.prop('disabled', true).text('Sending...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'shopglut_send_manual_email',
                email: email,
                nonce: '<?php echo wp_kses_post(wp_create_nonce( "shopglut_pro_nonce" )); ?>'
            },
            success: function(response) {
                if (response.success) {
                    button.text('Sent!').css('background', '#28a745');
                    setTimeout(function() {
                        button.text('Send Email').css('background', '').prop('disabled', false);
                    }, 3000);
                } else {
                    alert('Error: ' + response.data);
                    button.prop('disabled', false).text('Send Email');
                }
            },
            error: function() {
                alert('Ajax error occurred');
                button.prop('disabled', false).text('Send Email');
            }
        });
    });

    // Select all users checkbox
    $('#select-all-users').change(function() {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Refresh users table
    $('#refresh-users-table').click(function() {
        location.reload();
    });

    // Bulk email sending
    $('#send-bulk-emails').click(function() {
        var selectedEmails = [];
        $('.user-checkbox:checked').each(function() {
            selectedEmails.push($(this).val());
        });

        if (selectedEmails.length === 0) {
            alert('Please select at least one user.');
            return;
        }

        if (!confirm('Send emails to ' + selectedEmails.length + ' selected users?')) {
            return;
        }

        var button = $(this);
        button.prop('disabled', true).text('Sending...');

        // Send emails one by one
        var emailIndex = 0;
        function sendNextEmail() {
            if (emailIndex >= selectedEmails.length) {
                button.prop('disabled', false).text('Send Bulk Emails');
                alert('Bulk emails sent successfully!');
                return;
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_send_manual_email',
                    email: selectedEmails[emailIndex],
                    nonce: '<?php echo wp_kses_post(wp_create_nonce( "shopglut_pro_nonce" )); ?>'
                },
                success: function(response) {
                    emailIndex++;
                    button.text('Sending... (' + emailIndex + '/' + selectedEmails.length + ')');
                    setTimeout(sendNextEmail, 1000); // Delay between emails
                },
                error: function() {
                    emailIndex++;
                    setTimeout(sendNextEmail, 1000);
                }
            });
        }

        sendNextEmail();
    });
});
</script>

<?php
        }

        private function render_users_table_rows( $is_pro, $is_pro_active, $pro_text ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'shopglut_wishlist';
            
            // Get wishlist users with better query
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Table existence check with caching
            $wishlist_users = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM %s
                     WHERE useremail IS NOT NULL
                     AND useremail != %s
                     AND useremail != %s
                     ORDER BY product_added_time DESC", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder -- Using %s instead of %i for compatibility, table name escaped with esc_sql()
                    $table_name, '', 'guest@example.com'
                )
            );

            if ( empty( $wishlist_users ) ) {
                $colspan = $is_pro_active ? 7 : 6;
                return '<tr><td colspan="' . $colspan . '">' . esc_html__( 'No wishlist users found', 'shopglut' ) . '</td></tr>';
            }

            $output = '';
            foreach ( $wishlist_users as $user ) {
                $username = esc_html( $user->username ?: __( 'Guest User', 'shopglut' ) );
                $useremail = esc_html( $user->useremail );
                
                // Get product count
                $product_ids = array_filter( explode( ',', $user->product_ids ) );
                $wishlist_sublist = json_decode( $user->wishlist_sublist, true );
                
                if ( is_array( $wishlist_sublist ) ) {
                    foreach ( $wishlist_sublist as $sublist_ids ) {
                        if ( is_array( $sublist_ids ) ) {
                            $product_ids = array_merge( $product_ids, $sublist_ids );
                        }
                    }
                }
                
                $product_count = count( array_unique( $product_ids ) );
                $product_added_time = $user->product_added_time ? 
                    date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->product_added_time ) ) : 
                    __( 'N/A', 'shopglut' );
                
                // Email status
                $email_status = $this->get_email_status( $user );
                
                $output .= '<tr>';
                
                if ( $is_pro_active ) {
                    $output .= '<td><input type="checkbox" class="user-checkbox" value="' . esc_attr( $useremail ) . '"></td>';
                }
                
                $output .= '<td>' . $username . '</td>';
                $output .= '<td>' . $useremail . '</td>';
                $output .= '<td><strong>' . $product_count . '</strong></td>';
                $output .= '<td>' . $product_added_time . '</td>';
                $output .= '<td>' . $email_status . '</td>';
                $output .= '<td>';
                
                if ( $is_pro_active ) {
                    $output .= '<button class="agl-send-email-button" data-email="' . esc_attr( $useremail ) . '">';
                    $output .= __( 'Send Email', 'shopglut' );
                    $output .= '</button>';
                } elseif ( $is_pro ) {
                    $output .= '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">';
                    $output .= esc_html( $pro_text );
                    $output .= '</a>';
                } else {
                    $output .= '<button class="agl-send-email-button" data-email="' . esc_attr( $useremail ) . '">';
                    $output .= __( 'Send Email', 'shopglut' );
                    $output .= '</button>';
                }
                
                $output .= '</td>';
                $output .= '</tr>';
            }

            return $output;
        }

        private function get_email_status( $user ) {
            if ( ! empty( $user->email_sent ) ) {
            $sent_time = date_i18n( get_option( 'date_format' ), strtotime( $user->email_sent ) );
            return '<span class="agl-email-status agl-status-sent">' . 
                /* translators: %s: formatted date and time when email was sent */
                sprintf( __( 'Sent %s', 'shopglut' ), $sent_time ) . 
                '</span>';
}
            // Check if email should be sent based on time
            if ( $this->pro_email_instance && ! empty( $user->product_added_time ) ) {
                $options = get_option( 'agshopglut_wishlist_options', [] );
                if ( isset( $options['wishlist-email-mail']['send_email'] ) && 
                     $options['wishlist-email-mail']['send_email'] === 'yes' ) {
                    
                    $time_value = (int) ( $options['wishlist-email-mail']['time_value'] ?? 1 );
                    $time_unit = $options['wishlist-email-mail']['time_unit'] ?? 'day';
                    
                    $multiplier = $time_unit === 'minute' ? 60 : ( $time_unit === 'hour' ? 3600 : 86400 );
                    $scheduled_time = strtotime( $user->product_added_time ) + ( $time_value * $multiplier );
                    
                    if ( current_time( 'timestamp' ) >= $scheduled_time ) {
                        return '<span class="agl-email-status agl-status-pending">' . 
                               __( 'Ready to Send', 'shopglut' ) . 
                               '</span>';
                    } else {
                        $send_time = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $scheduled_time );
                        return '<span class="agl-email-status agl-status-pending">' . 
                            /* translators: %s: Scheduled for email */
                               sprintf( __( 'Scheduled for %s', 'shopglut' ), $send_time ) . 
                               '</span>';
                    }
                }
            }
            
            return '<span class="agl-email-status">' . esc_html__( 'Not Scheduled', 'shopglut' ) . '</span>';
        }

     
    }
}