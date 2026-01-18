<?php
if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: Preview
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if (!class_exists('AGSHOPGLUT_publish')) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 2.0.15
	 * @version 2.0.15
	 */
	class AGSHOPGLUT_publish extends AGSHOPGLUTP {

		/**
		 * Shortcode field constructor.
		 */
		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			?>
<div class="submitbox" id="submitpost">
    <div id="<?php echo esc_attr($this->field['id']); ?>">
        <input type="submit" name="publish" id="publish" class="btn btn-fullwidth"
            value="<?php echo esc_attr($this->field['button_text']) ?>">
    </div>
    <div class="clear"></div>
</div>
<?php

		}

	}
}