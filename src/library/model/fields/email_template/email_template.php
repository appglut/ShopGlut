<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: email_template
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_email_template' ) ) {
  class AGSHOPGLUT_email_template extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      $args = wp_parse_args( $this->field, array(
        'templates' => array(),
        'show_preview' => true,
        'allow_custom' => true,
      ) );

      // Get default templates
      $templates = $this->get_default_templates();
      
      // Merge with custom templates if provided
      if ( ! empty( $args['templates'] ) ) {
        $templates = array_merge( $templates, $args['templates'] );
      }

      // Current value
      $current_template = ! empty( $this->value['template'] ) ? $this->value['template'] : 'welcome';
      $current_subject = ! empty( $this->value['subject'] ) ? $this->value['subject'] : '';
      $current_content = ! empty( $this->value['content'] ) ? $this->value['content'] : '';

      echo '<div class="agshopglut-email-template-field">';
      
      // Template Selector
      echo '<div class="template-selector-wrapper">';
      echo '<label><strong>' . esc_html__( 'Choose Template:', 'shopglut' ) . '</strong></label>';
      echo '<select name="' . esc_attr( $this->field_name( '[template]' ) ) . '" class="template-selector">';
      foreach ( $templates as $template_id => $template_data ) {
        $selected = selected( $current_template, $template_id, false );
        echo '<option value="' . esc_attr( $template_id ) . '"' . esc_attr( $selected ) . '>';
        echo esc_html( $template_data['name'] );
        echo '</option>';
      }
      echo '</select>';
      echo '</div>';

      // Subject Line Editor
      echo '<div class="subject-line-wrapper" style="margin: 20px 0;">';
      echo '<label><strong>' . esc_html__( 'Subject Line:', 'shopglut' ) . '</strong></label>';
      echo '<input type="text" name="' . esc_attr( $this->field_name( '[subject]' ) ) . '" ';
      echo 'value="' . esc_attr( $current_subject ) . '" ';
      echo 'class="template-subject widefat" placeholder="' . esc_attr__( 'Enter email subject...', 'shopglut' ) . '" />';
      echo '</div>';

      // Content Editor
      echo '<div class="content-editor-wrapper">';
      echo '<label><strong>' . esc_html__( 'Email Content:', 'shopglut' ) . '</strong></label>';
      
      // WordPress Editor
      $editor_id = str_replace( array( '[', ']' ), array( '_', '_' ), $this->field_name( '[content]' ) );
      $editor_settings = array(
        'textarea_name' => $this->field_name( '[content]' ),
        'textarea_rows' => 15,
        'media_buttons' => true,
        'tinymce' => array(
          'toolbar1' => 'bold,italic,underline,strikethrough,|,alignleft,aligncenter,alignright,|,link,unlink,|,bullist,numlist,|,forecolor,backcolor',
          'toolbar2' => 'formatselect,fontselect,fontsizeselect,|,outdent,indent,|,undo,redo,|,code',
        ),
      );
      
      wp_editor( $current_content, $editor_id, $editor_settings );
      echo '</div>';

      // Live Preview Section
      if ( $args['show_preview'] ) {
        echo '<div class="template-preview-wrapper" style="margin-top: 30px;">';
        echo '<h4>' . esc_html__( 'Live Preview:', 'shopglut' ) . '</h4>';
        echo '<div class="email-preview-container">';
        
        foreach ( $templates as $template_id => $template_data ) {
          $display_style = ( $template_id === $current_template ) ? 'block' : 'none';
          echo '<div class="template-preview-item" data-template="' . esc_attr( $template_id ) . '" style="display: ' . esc_attr( $display_style ) . ';">';
          echo '<div class="email-preview-frame">';
          echo wp_kses_post( $template_data['preview_html'] );
          echo '</div>';
          echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
      }

      // Template Variables Helper
      echo '<div class="template-variables-wrapper" style="margin-top: 20px;">';
      echo '<h4>' . esc_html__( 'Available Variables:', 'shopglut' ) . '</h4>';
      echo '<div class="variables-list">';
      echo '<code>{user_name}</code> - Customer name<br>';
      echo '<code>{site_name}</code> - Your website name<br>';
      echo '<code>{wishlist_url}</code> - Link to wishlist<br>';
      echo '<code>{product_name}</code> - Product name<br>';
      echo '<code>{product_price}</code> - Product price<br>';
      echo '<code>{discount_code}</code> - Discount code<br>';
      echo '<code>{unsubscribe_url}</code> - Unsubscribe link<br>';
      echo '</div>';
      echo '</div>';

      echo '</div>';

      // Add CSS and JavaScript
      $this->add_template_assets();
    }

    private function get_default_templates() {
      return array(
        'welcome' => array(
          'name' => __( 'Welcome Email', 'shopglut' ),
          'subject' => __( 'Welcome to {site_name}!', 'shopglut' ),
          'content' => $this->get_welcome_template_content(),
          'preview_html' => $this->get_welcome_preview_html(),
        ),
        'abandoned' => array(
          'name' => __( 'Abandoned Wishlist', 'shopglut' ),
          'subject' => __( 'Your wishlist is waiting for you!', 'shopglut' ),
          'content' => $this->get_abandoned_template_content(),
          'preview_html' => $this->get_abandoned_preview_html(),
        ),
        'price_drop' => array(
          'name' => __( 'Price Drop Alert', 'shopglut' ),
          'subject' => __( '🎉 Price drop on {product_name}!', 'shopglut' ),
          'content' => $this->get_price_drop_template_content(),
          'preview_html' => $this->get_price_drop_preview_html(),
        ),
        'back_in_stock' => array(
          'name' => __( 'Back in Stock', 'shopglut' ),
          'subject' => __( '{product_name} is back in stock!', 'shopglut' ),
          'content' => $this->get_back_in_stock_template_content(),
          'preview_html' => $this->get_back_in_stock_preview_html(),
        ),
        'seasonal' => array(
          'name' => __( 'Seasonal Sale', 'shopglut' ),
          'subject' => __( 'Special offer on your wishlist items!', 'shopglut' ),
          'content' => $this->get_seasonal_template_content(),
          'preview_html' => $this->get_seasonal_preview_html(),
        ),
      );
    }

    private function get_welcome_template_content() {
      return '<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px;">
        <h2 style="color: #333; text-align: center;">Welcome to {site_name}!</h2>
        <p>Hi {user_name},</p>
        <p>Thank you for creating your wishlist with us! You can now save your favorite products and never lose track of what you love.</p>
        <div style="text-align: center; margin: 30px 0;">
          <a href="{wishlist_url}" style="background: #007cba; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Your Wishlist</a>
        </div>
        <p>Happy shopping!<br>The {site_name} Team</p>
      </div>';
    }

    private function get_abandoned_template_content() {
      return '<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px;">
        <h2 style="color: #333; text-align: center;">Your Wishlist is Missing You!</h2>
        <p>Hi {user_name},</p>
        <p>You have some amazing items waiting in your wishlist. Don\'t let them slip away!</p>
        <div style="border: 1px solid #ddd; padding: 20px; margin: 20px 0; background: #f9f9f9;">
          <h3>Featured Item: {product_name}</h3>
          <p>Price: {product_price}</p>
        </div>
        <div style="text-align: center; margin: 30px 0;">
          <a href="{wishlist_url}" style="background: #e74c3c; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Return to Wishlist</a>
        </div>
        <p>Best regards,<br>The {site_name} Team</p>
      </div>';
    }

    private function get_price_drop_template_content() {
      return '<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px;">
        <h2 style="color: #27ae60; text-align: center;">🎉 Great News! Price Drop Alert</h2>
        <p>Hi {user_name},</p>
        <p>The price has dropped on one of your wishlist items!</p>
        <div style="border: 2px solid #27ae60; padding: 20px; margin: 20px 0; background: #f8fff8; text-align: center;">
          <h3 style="margin: 0;">{product_name}</h3>
          <p style="font-size: 18px; margin: 10px 0;">
            <span style="text-decoration: line-through; color: #999;">$99.99</span>
            <span style="color: #27ae60; font-weight: bold; font-size: 24px;">$79.99</span>
          </p>
          <p style="color: #e74c3c; font-weight: bold;">Save $20.00!</p>
        </div>
        <div style="text-align: center; margin: 30px 0;">
          <a href="{wishlist_url}" style="background: #27ae60; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Buy Now</a>
        </div>
        <p>Happy shopping!<br>The {site_name} Team</p>
      </div>';
    }

    private function get_back_in_stock_template_content() {
      return '<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px;">
        <h2 style="color: #3498db; text-align: center;">📦 Back in Stock!</h2>
        <p>Hi {user_name},</p>
        <p>Good news! <strong>{product_name}</strong> from your wishlist is back in stock and ready to order.</p>
        <div style="border: 1px solid #3498db; padding: 20px; margin: 20px 0; background: #f8fbff; text-align: center;">
          <h3 style="margin: 0; color: #3498db;">{product_name}</h3>
          <p style="font-size: 18px; margin: 10px 0;">{product_price}</p>
          <p style="color: #e74c3c;">⚡ Limited stock available!</p>
        </div>
        <div style="text-align: center; margin: 30px 0;">
          <a href="{wishlist_url}" style="background: #3498db; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Shop Now</a>
        </div>
        <p>Best regards,<br>The {site_name} Team</p>
      </div>';
    }

    private function get_seasonal_template_content() {
      return '<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px;">
        <h2 style="color: #f39c12; text-align: center;">🍂 Seasonal Sale - Limited Time!</h2>
        <p>Hi {user_name},</p>
        <p>Your wishlist items are now on sale! Don\'t miss this limited-time offer.</p>
        <div style="background: #fff3cd; border: 2px solid #ffeaa7; padding: 20px; margin: 20px 0; text-align: center; border-radius: 8px;">
          <h3 style="margin: 0; color: #f39c12;">Use Code: {discount_code}</h3>
          <p style="font-size: 18px; margin: 10px 0; color: #856404;">Get 20% OFF your wishlist items!</p>
          <p style="color: #721c24; font-weight: bold;">⏰ Offer expires in 3 days!</p>
        </div>
        <div style="text-align: center; margin: 30px 0;">
          <a href="{wishlist_url}" style="background: #f39c12; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Shop Your Wishlist</a>
        </div>
        <p>Happy shopping!<br>The {site_name} Team</p>
      </div>';
    }

    private function get_welcome_preview_html() {
      return str_replace(
        array( '{site_name}', '{user_name}', '{wishlist_url}' ),
        array( 'Your Store', 'John Doe', '#' ),
        $this->get_welcome_template_content()
      );
    }

    private function get_abandoned_preview_html() {
      return str_replace(
        array( '{site_name}', '{user_name}', '{product_name}', '{product_price}', '{wishlist_url}' ),
        array( 'Your Store', 'Jane Smith', 'Amazing Product', '$49.99', '#' ),
        $this->get_abandoned_template_content()
      );
    }

    private function get_price_drop_preview_html() {
      return str_replace(
        array( '{site_name}', '{user_name}', '{product_name}', '{wishlist_url}' ),
        array( 'Your Store', 'Mike Johnson', 'Cool Gadget', '#' ),
        $this->get_price_drop_template_content()
      );
    }

    private function get_back_in_stock_preview_html() {
      return str_replace(
        array( '{site_name}', '{user_name}', '{product_name}', '{product_price}', '{wishlist_url}' ),
        array( 'Your Store', 'Sarah Wilson', 'Popular Item', '$89.99', '#' ),
        $this->get_back_in_stock_template_content()
      );
    }

    private function get_seasonal_preview_html() {
      return str_replace(
        array( '{site_name}', '{user_name}', '{discount_code}', '{wishlist_url}' ),
        array( 'Your Store', 'Alex Brown', 'FALL20', '#' ),
        $this->get_seasonal_template_content()
      );
    }

    private function add_template_assets() {
      ?>
      <style>
        .agshopglut-email-template-field {
          background: #fff;
          border: 1px solid #ddd;
          padding: 20px;
          border-radius: 6px;
        }
        .template-selector-wrapper {
          margin-bottom: 20px;
        }
        .template-selector {
          width: 100%;
          max-width: 300px;
          padding: 8px 12px;
          border: 1px solid #ddd;
          border-radius: 4px;
        }
        .email-preview-container {
          border: 1px solid #ddd;
          padding: 20px;
          background: #f9f9f9;
          border-radius: 6px;
          max-height: 500px;
          overflow-y: auto;
        }
        .email-preview-frame {
          background: white;
          padding: 20px;
          border-radius: 4px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .template-preview-item {
          margin-bottom: 20px;
        }
        .variables-list {
          background: #f5f5f5;
          padding: 15px;
          border-radius: 4px;
          border-left: 4px solid #007cba;
        }
        .variables-list code {
          background: #fff;
          padding: 2px 6px;
          border-radius: 3px;
          margin-right: 10px;
        }
      </style>
      
      <script>
      jQuery(document).ready(function($) {
        $('.template-selector').on('change', function() {
          var selectedTemplate = $(this).val();
          var container = $(this).closest('.agshopglut-email-template-field');
          
          // Hide all previews
          container.find('.template-preview-item').hide();
          
          // Show selected preview
          container.find('.template-preview-item[data-template="' + selectedTemplate + '"]').show();
          
          // Load template content into editor
          loadTemplateContent(selectedTemplate, container);
        });
        
        function loadTemplateContent(templateId, container) {
          var templates = {
            'welcome': {
              'subject': 'Welcome to {site_name}!',
              'content': <?php echo json_encode( $this->get_welcome_template_content() ); ?>
            },
            'abandoned': {
              'subject': 'Your wishlist is waiting for you!',
              'content': <?php echo json_encode( $this->get_abandoned_template_content() ); ?>
            },
            'price_drop': {
              'subject': '🎉 Price drop on {product_name}!',
              'content': <?php echo json_encode( $this->get_price_drop_template_content() ); ?>
            },
            'back_in_stock': {
              'subject': '{product_name} is back in stock!',
              'content': <?php echo json_encode( $this->get_back_in_stock_template_content() ); ?>
            },
            'seasonal': {
              'subject': 'Special offer on your wishlist items!',
              'content': <?php echo json_encode( $this->get_seasonal_template_content() ); ?>
            }
          };
          
          if (templates[templateId]) {
            // Update subject
            container.find('.template-subject').val(templates[templateId].subject);
            
            // Update content in WordPress editor
            var editorId = container.find('.wp-editor-area').attr('id');
            if (editorId && typeof tinymce !== 'undefined') {
              var editor = tinymce.get(editorId);
              if (editor) {
                editor.setContent(templates[templateId].content);
              } else {
                container.find('.wp-editor-area').val(templates[templateId].content);
              }
            }
          }
        }
      });
      </script>
      <?php
    }

    public function output() {
      return $this->value;
    }
  }
}