<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** How to use

vc_add_param( $shortcode, array(
	'type' => 'bb_toggle',
	'heading' => esc_html__('Show/Hide on ', 'bestbug'),
	'param_name' => 'show_hide',
	'group' => $group,
	'value' => 'yes',
)); */

if(!class_exists('BestBug_Extend_VcParams_Toggle'))
{
	class BestBug_Extend_VcParams_Toggle
	{
		function __construct()
		{
			add_action('init', array($this, 'init'));
		}
		
		function init()
		{
			if ( class_exists( 'WpbakeryShortcodeParams' ) && is_admin() )
			{
				// Load enqueueScripts
				if(is_admin()) {
					WpbakeryShortcodeParams::addField('bb_toggle' , array($this, 'bb_toggle'), BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/toggle.js');
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
				}
			}
		}

		function bb_toggle($settings, $value){

			$output = $checked = '';
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			if(empty($value)) {
				$value = isset($settings['value']) ? $settings['value'] : '';
			}

			$output = '<label class="bb-toggle">';

			$output .= '<input class="checkbox" type="checkbox" '.(($value == "yes")?'checked':"").'><div class="slider round"></div>';

			$output .= '<input class="bb-value wpb_vc_param_value" name="'.$param_name.'" type="text" value="'.$value.'" />';

			$output .= '</label>';

			return $output;
		}

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'bb-toggle', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/toggle.css' );
		}

	}

	new BestBug_Extend_VcParams_Toggle();
}
