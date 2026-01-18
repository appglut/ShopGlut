<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: tabbed
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('AGSHOPGLUT_Field_tabbed')) {
	class AGSHOPGLUT_tabbed extends AGSHOPGLUTP {

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render() {

			$unallows = array('tabbed');

			echo esc_attr($this->field_before());

			echo '<div class="agl-tabbed-nav agl-tabbed-nav-' . esc_attr($this->field['id']) . '">';
			foreach ($this->field['tabs'] as $key => $tab) {

				// Handle tab dependency - store data attributes
				$tab_data_attrs = '';
				$tab_dep_class = '';
				if (isset($tab['dependency'])) {
					$dependency = $tab['dependency'];
					$controller = (!empty($dependency[0])) ? $dependency[0] : '';
					$condition = (!empty($dependency[1])) ? $dependency[1] : '';
					$value = (!empty($dependency[2])) ? $dependency[2] : '';

					if ($controller && $value) {
						$tab_data_attrs = ' data-dep-controller="' . esc_attr($controller) . '" data-dep-value="' . esc_attr($value) . '"';
						$tab_dep_class = ' agl-tab-has-dep';
					}
				}

				$tabbed_icon = (!empty($tab['icon'])) ? '<i class="agl--icon ' . esc_attr($tab['icon']) . '"></i>' : '';
				$tabbed_active = (empty($key)) ? 'agl-tabbed-active' : '';
				$tab_class = isset($tab['class']) ? wp_kses_post($tab['class']) . ' ' : '';

				echo '<a href="#" ' . $tab_data_attrs . ' class="' . $tab_class . esc_attr($tabbed_active) . esc_attr($tab_dep_class) . '">' . wp_kses_post($tabbed_icon) . esc_html($tab['title']) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped

			}
			echo '</div>';

			echo '<div class="agl-tabbed-contents agl-tabbed-contents-' . esc_attr($this->field['id']) . '">';
			foreach ($this->field['tabs'] as $key => $tab) {

				// Handle tab content dependency - store data attributes
				$tab_data_attrs = '';
				$tab_dep_class = '';
				if (isset($tab['dependency'])) {
					$dependency = $tab['dependency'];
					$controller = (!empty($dependency[0])) ? $dependency[0] : '';
					$condition = (!empty($dependency[1])) ? $dependency[1] : '';
					$value = (!empty($dependency[2])) ? $dependency[2] : '';

					if ($controller && $value) {
						$tab_data_attrs = ' data-dep-controller="' . esc_attr($controller) . '" data-dep-value="' . esc_attr($value) . '"';
						$tab_dep_class = ' agl-tab-has-dep agl-tab-hidden';
					}
				}

				$tabbed_hidden = (!empty($key)) ? ' hidden' : '';
				$tab_class = isset($tab['class']) ? wp_kses_post($tab['class']) . ' ' : '';

				echo '<div class="agl-tabbed-content ' . $tab_class . esc_attr($tabbed_hidden) . esc_attr($tab_dep_class) . '" ' . $tab_data_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped

				foreach ($tab['fields'] as $field) {

					$field_id = (isset($field['id'])) ? $field['id'] : '';
					$field_default = (isset($field['default'])) ? $field['default'] : '';
					$field_value = (isset($this->value[$field_id])) ? $this->value[$field_id] : $field_default;
					$unique_id = (!empty($this->unique)) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					AGSHOPGLUT::field($field, $field_value, $unique_id, 'field/tabbed');

				}

				echo '</div>';

			}
			echo '</div>';

			echo esc_attr($this->field_after());

			// Simple inline JS for tab dependencies
			$field_id = esc_attr($this->field['id']);
			?>
			<script>
			(function($) {
				$(document).ready(function() {
					var $tabbedNav = $('.agl-tabbed-nav-<?php echo $field_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped above ?>');
					var $tabbedContents = $('.agl-tabbed-contents-<?php echo $field_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped above ?>');

					// Function to check checkbox values and show/hide tabs
					function updateTabDependencies() {
						$tabbedNav.find('a.agl-tab-has-dep').each(function() {
							var $nav = $(this);
							var controller = $nav.data('dep-controller');
							var value = $nav.data('dep-value');

							// Find the checkbox field
							var $checkbox = $('[name*="' + controller + '"][value="' + value + '"]');

							var isChecked = false;
							if ($checkbox.length) {
								if ($checkbox.attr('type') === 'checkbox') {
									isChecked = $checkbox.is(':checked');
								}
							}

							// Get corresponding content
							var index = $nav.index();
							var $content = $tabbedContents.find('.agl-tabbed-content').eq(index);

							if (isChecked) {
								$nav.show();
								$content.removeClass('agl-tab-hidden');
							} else {
								$nav.hide();
								$content.addClass('agl-tab-hidden');

								// If this was the active tab, switch to first visible tab
								if ($nav.hasClass('agl-tabbed-active')) {
									$nav.removeClass('agl-tabbed-active');
								}
							}
						});

						// Update active state and content visibility
						$tabbedNav.find('a').not('.agl-tab-has-dep').removeClass('agl-tabbed-active');
						var $firstVisible = $tabbedNav.find('a:visible:first');
						if ($firstVisible.length) {
							// Make first visible tab active
							$tabbedNav.find('a').removeClass('agl-tabbed-active');
							$firstVisible.addClass('agl-tabbed-active');

							// Show first visible content, hide others
							var firstIndex = $firstVisible.index();
							$tabbedContents.find('.agl-tabbed-content').addClass('hidden');
							$tabbedContents.find('.agl-tabbed-content').eq(firstIndex).removeClass('hidden');
						}
					}

					// Initial check
					setTimeout(updateTabDependencies, 100);

					// Listen for checkbox changes
					$(document).on('change', '[name*="badge_type"]', function() {
						updateTabDependencies();
					});
				});
			})(jQuery);
			</script>
			<style>
			.agl-tabbed-nav a.agl-tab-hidden,
			.agl-tabbed-content.agl-tab-hidden {
				display: none !important;
			}
			</style>
			<?php

		}

	}
}
