<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: datetime
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_datetime' ) ) {
  class AGSHOPGLUT_datetime extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      $args = wp_parse_args( $this->field, array(
        'format' => 'Y-m-d H:i:s',
        'min_date' => '',
        'max_date' => '',
        'time_format' => '24',
        'date_format' => 'mm/dd/yy',
        'show_time' => true,
        'placeholder' => '',
        'readonly' => false,
      ) );

      // Parse current value
      $current_datetime = '';
      $current_date = '';
      $current_time = '';
      
      if ( ! empty( $this->value ) ) {
        if ( is_numeric( $this->value ) ) {
          // Unix timestamp
          $datetime = new DateTime();
          $datetime->setTimestamp( $this->value );
          $current_datetime = $datetime->format( 'Y-m-d H:i' );
          $current_date = $datetime->format( 'Y-m-d' );
          $current_time = $datetime->format( 'H:i' );
        } else {
          // String format
          $datetime = new DateTime( $this->value );
          $current_datetime = $datetime->format( 'Y-m-d H:i' );
          $current_date = $datetime->format( 'Y-m-d' );
          $current_time = $datetime->format( 'H:i' );
        }
      }

      echo '<div class="agshopglut-datetime-field" data-field-id="' . esc_attr( $this->field['id'] ) . '">';
      
      // Main datetime input (HTML5)
      echo '<div class="datetime-input-wrapper">';
      echo '<input type="datetime-local" ';
      echo 'name="' . esc_attr( $this->field_name() ) . '" ';
      echo 'id="' . esc_attr( $this->field['id'] ) . '" ';
      echo 'value="' . esc_attr( $current_datetime ) . '" ';
      echo 'class="agshopglut-datetime-input widefat" ';
      
      if ( ! empty( $args['placeholder'] ) ) {
        echo 'placeholder="' . esc_attr( $args['placeholder'] ) . '" ';
      }
      
      if ( $args['readonly'] ) {
        echo 'readonly ';
      }
      
      if ( ! empty( $args['min_date'] ) ) {
        echo 'min="' . esc_attr( $args['min_date'] ) . '" ';
      }
      
      if ( ! empty( $args['max_date'] ) ) {
        echo 'max="' . esc_attr( $args['max_date'] ) . '" ';
      }
      
      echo '/>';
      echo '</div>';

      // Alternative inputs for better browser support
      echo '<div class="datetime-fallback" style="display: none;">';
      
      // Date input
      echo '<div class="date-input-wrapper" style="display: inline-block; margin-right: 10px;">';
      echo '<label>' . esc_html__( 'Date:', 'shopglut' ) . '</label><br>';
      echo '<input type="date" ';
      echo 'name="' . esc_attr( $this->field_name( '_date' ) ) . '" ';
      echo 'value="' . esc_attr( $current_date ) . '" ';
      echo 'class="agshopglut-date-input" />';
      echo '</div>';
      
      // Time input
      if ( $args['show_time'] ) {
        echo '<div class="time-input-wrapper" style="display: inline-block;">';
        echo '<label>' . esc_html__( 'Time:', 'shopglut' ) . '</label><br>';
        echo '<input type="time" ';
        echo 'name="' . esc_attr( $this->field_name( '_time' ) ) . '" ';
        echo 'value="' . esc_attr( $current_time ) . '" ';
        echo 'class="agshopglut-time-input" />';
        echo '</div>';
      }
      
      echo '</div>';

      // Quick preset buttons
      echo '<div class="datetime-presets" style="margin-top: 15px;">';
      echo '<label style="font-weight: bold; display: block; margin-bottom: 10px;">' . esc_html__( 'Quick Options:', 'shopglut' ) . '</label>';
      
      $presets = array(
        'now' => __( 'Now', 'shopglut' ),
        '1hour' => __( 'In 1 Hour', 'shopglut' ),
        '1day' => __( 'Tomorrow', 'shopglut' ),
        '1week' => __( 'Next Week', 'shopglut' ),
        'clear' => __( 'Clear', 'shopglut' ),
      );
      
      foreach ( $presets as $preset_key => $preset_label ) {
        $button_class = ( $preset_key === 'clear' ) ? 'button-secondary' : 'button-primary';
        echo '<button type="button" class="button ' . esc_attr($button_class) . ' datetime-preset" ';
        echo 'data-preset="' . esc_attr( $preset_key ) . '" ';
        echo 'style="margin-right: 5px; margin-bottom: 5px;">';
        echo esc_html( $preset_label );
        echo '</button>';
      }
      
      echo '</div>';

      // Current selection display
      echo '<div class="datetime-display" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 4px;">';
      echo '<strong>' . esc_html__( 'Selected:', 'shopglut' ) . '</strong> ';
      echo '<span class="selected-datetime">';
      if ( ! empty( $current_datetime ) ) {
        $display_datetime = new DateTime( $current_datetime );
        echo esc_html( $display_datetime->format( 'F j, Y \a\t g:i A' ) );
      } else {
        echo '<em>' . esc_html__( 'Send immediately', 'shopglut' ) . '</em>';
      }
      echo '</span>';
      echo '</div>';

      // Timezone display
      echo '<div class="timezone-info" style="margin-top: 10px; font-size: 12px; color: #666;">';
      echo '<strong>' . esc_html__( 'Timezone:', 'shopglut' ) . '</strong> ';
      echo esc_html( wp_timezone_string() );
      echo '</div>';

      echo '</div>';

      // Add CSS and JavaScript
      $this->add_datetime_assets();
    }

    private function add_datetime_assets() {
      ?>
      <style>
        .agshopglut-datetime-field {
          background: #fff;
          border: 1px solid #ddd;
          padding: 20px;
          border-radius: 6px;
        }
        
        .agshopglut-datetime-input {
          padding: 8px 12px;
          border: 1px solid #ddd;
          border-radius: 4px;
          font-size: 14px;
          width: 100%;
          max-width: 300px;
        }
        
        .agshopglut-date-input,
        .agshopglut-time-input {
          padding: 8px 12px;
          border: 1px solid #ddd;
          border-radius: 4px;
          font-size: 14px;
        }
        
        .datetime-presets .button {
          font-size: 12px;
          padding: 4px 8px;
          height: auto;
          line-height: 1.4;
        }
        
        .datetime-display {
          border-left: 4px solid #007cba;
        }
        
        .datetime-display .selected-datetime {
          font-weight: bold;
          color: #333;
        }
        
        .timezone-info {
          border-top: 1px solid #eee;
          padding-top: 10px;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
          .date-input-wrapper,
          .time-input-wrapper {
            display: block !important;
            margin-bottom: 10px;
          }
          
          .agshopglut-datetime-input {
            max-width: 100%;
          }
        }
      </style>
      
      <script>
      jQuery(document).ready(function($) {
        
        // Check if browser supports datetime-local
        function supportsDatetimeLocal() {
          var input = document.createElement('input');
          input.setAttribute('type', 'datetime-local');
          return input.type === 'datetime-local';
        }
        
        // Initialize datetime fields
        $('.agshopglut-datetime-field').each(function() {
          var $container = $(this);
          var $datetimeInput = $container.find('.agshopglut-datetime-input');
          var $fallback = $container.find('.datetime-fallback');
          
          // Show fallback for older browsers
          if (!supportsDatetimeLocal()) {
            $datetimeInput.hide();
            $fallback.show();
            
            // Sync fallback inputs with main input
            $container.find('.agshopglut-date-input, .agshopglut-time-input').on('change', function() {
              updateMainInput($container);
            });
          }
          
          // Handle preset buttons
          $container.find('.datetime-preset').on('click', function() {
            var preset = $(this).data('preset');
            var now = new Date();
            var targetDate;
            
            switch(preset) {
              case 'now':
                targetDate = now;
                break;
              case '1hour':
                targetDate = new Date(now.getTime() + (60 * 60 * 1000));
                break;
              case '1day':
                targetDate = new Date(now.getTime() + (24 * 60 * 60 * 1000));
                break;
              case '1week':
                targetDate = new Date(now.getTime() + (7 * 24 * 60 * 60 * 1000));
                break;
              case 'clear':
                $datetimeInput.val('');
                $container.find('.agshopglut-date-input').val('');
                $container.find('.agshopglut-time-input').val('');
                updateDisplay($container, '');
                return;
            }
            
            if (targetDate) {
              var datetimeString = formatDatetimeLocal(targetDate);
              $datetimeInput.val(datetimeString);
              
              // Update fallback inputs
              $container.find('.agshopglut-date-input').val(targetDate.toISOString().split('T')[0]);
              $container.find('.agshopglut-time-input').val(
                targetDate.getHours().toString().padStart(2, '0') + ':' +
                targetDate.getMinutes().toString().padStart(2, '0')
              );
              
              updateDisplay($container, datetimeString);
            }
          });
          
          // Update display when main input changes
          $datetimeInput.on('change', function() {
            updateDisplay($container, $(this).val());
          });
          
          // Initialize display
          updateDisplay($container, $datetimeInput.val());
        });
        
        function formatDatetimeLocal(date) {
          var year = date.getFullYear();
          var month = (date.getMonth() + 1).toString().padStart(2, '0');
          var day = date.getDate().toString().padStart(2, '0');
          var hours = date.getHours().toString().padStart(2, '0');
          var minutes = date.getMinutes().toString().padStart(2, '0');
          
          return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
        }
        
        function updateMainInput($container) {
          var dateVal = $container.find('.agshopglut-date-input').val();
          var timeVal = $container.find('.agshopglut-time-input').val();
          
          if (dateVal && timeVal) {
            var datetimeString = dateVal + 'T' + timeVal;
            $container.find('.agshopglut-datetime-input').val(datetimeString);
            updateDisplay($container, datetimeString);
          }
        }
        
        function updateDisplay($container, datetimeString) {
          var $display = $container.find('.selected-datetime');
          
          if (datetimeString) {
            try {
              var date = new Date(datetimeString);
              var options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
              };
              $display.html(date.toLocaleDateString('en-US', options));
            } catch (e) {
              $display.html('<em>Invalid date</em>');
            }
          } else {
            $display.html('<em>Send immediately</em>');
          }
        }
      });
      </script>
      <?php
    }

    public function output() {
      return $this->value;
    }

    public function enqueue() {
      // Enqueue any additional scripts if needed
    }
  }
}