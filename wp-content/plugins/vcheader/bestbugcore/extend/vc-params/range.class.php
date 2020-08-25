<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** How to use

vc_add_param( $shortcode, array(
	'type' => 'bb_range',
	'heading' => esc_html__('Show/Hide on ', 'bestbug'),
	'param_name' => 'range',
	'group' => $group,
	'value' => '100',
)); */

if(!class_exists('BestBug_Extend_VcParams_Range'))
{
	class BestBug_Extend_VcParams_Range
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
					WpbakeryShortcodeParams::addField('bb_range' , array($this, 'bb_range'), BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/range.js');
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
				}
			}
		}

		function bb_range($settings, $value){

			$output = $checked = '';
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			if(empty($value)) {
				$value = isset($settings['value']) ? $settings['value'] : '';
			}

			$output = '<div class="bb-range-slider">';

			$output .= '<input type="range" step="0.01" min="0" max="1" class="bb-range-slider__range wpb_vc_param_value" name="'.$param_name.'" value="'.$value.'"><span class="bb-range-slider__value">'.$value.'</span>';

			$output .= '</div>';

			return $output;
		}

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'bb-range', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/range.css' );
		}

	}

	new BestBug_Extend_VcParams_Range();
}
