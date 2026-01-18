<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: image_select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_image_select' ) ) {
    class AGSHOPGLUT_image_select extends AGSHOPGLUTP {

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
            parent::__construct( $field, $value, $unique, $where, $parent );
        }

        public function render() {

            $args = wp_parse_args(
                $this->field,
                array(
                    'multiple' => false,
                    'options'  => array(),
                )
            );

            $value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

            echo esc_attr($this->field_before());

            if ( ! empty( $args['options'] ) ) {

                echo '<div class="agl-siblings wpshopglutpro--image-group" data-multiple="' . esc_attr($args['multiple']) . '">';

                $num = 1;

                foreach ( $args['options'] as $key => $option ) {

                    

                    $type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
                    $extra   = ( $args['multiple'] ) ? '[]' : '';
                    $active  = ( in_array( $key, $value ) ) ? ' agl--active' : '';
                    $checked = ( in_array( $key, $value ) ) ? ' checked' : '';
                    echo '<div class="agl--sibling agl--image' . esc_attr($active) . '">';
                    if ( ! empty( $option['image'] ) ) {
                        echo '<img src="' . esc_attr($option['image']) . '" alt="img-' . esc_attr($num++) . '" />';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
                    } else {
                        echo '<img src="' . esc_url($option) . '" alt="img-' . esc_attr($num++) . '" />';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
                    }
                    echo '<input type="' . esc_attr($type) . '" name="' . esc_attr($this->field_name( $extra )) . '" value="' . esc_attr($key) . '"' . wp_kses_post($this->field_attributes()) . esc_attr($checked) . '/>';
                    if ( ! empty( $option['option_name'] ) ) {
                        echo '<p class="agl-image-name"><b>' . esc_html($option['option_name']) . '</b></p>';
                    }

                    echo '</div>';

                }

                echo '</div>';

            }

            echo '<div class="clear"></div>';

            echo wp_kses_post($this->field_after());

        }

        public function output() {

            $output    = '';
            $bg_image  = array();
            $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
            $elements  = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

            if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
                $output = $elements .'{background-image:url('. $this->value .')'. $important .';}';
            }

            $this->parent->output_css .= $output;

            return $output;

        }

    }
}
