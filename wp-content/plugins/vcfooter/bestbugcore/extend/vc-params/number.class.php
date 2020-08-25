<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** How to use

array(
	'type'       => 'number',
	'heading'    => esc_html__( 'Number field', 'bestbug' ),
	'param_name' => 'number_field',
	'value'      => 100,
	'min'        => 10,
	'max'        => 100,
	'step'       => 1,
	'suffix'     => 'px',
), */

if(!class_exists('BestBug_Extend_VcParams_Number'))
{
	class BestBug_Extend_VcParams_Number
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
					WpbakeryShortcodeParams::addField('bb_number' , array($this, 'bb_number'), BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/number.js');
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
				}
			}
		}

		function bb_number($settings, $value){

			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$step       = isset( $settings['step'] ) ? $settings['step'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';

			$output = '<div class="bb-number">';
			$output .= '<input type="button" value="-" class="minus" />';
			$output .= '<input type="number" min="' . esc_attr( $min ) . '"' . ' max="' . esc_attr( $max ) . '"' . ' step="' . esc_attr( $step ) . '"' . ' class="wpb_vc_param_value ' . esc_attr( $param_name ) . '"' . ' name="' . esc_attr( $param_name ) . '"' . ' value="' . esc_attr( $value ) . '" />';
			$output .= '<input type="button" value="+" class="plus" />' . '<span>' . $suffix . '</span>';
			$output .= '</div>';

			return $output;
		}

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'bb-number', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/number.css' );
		}

	}

	new BestBug_Extend_VcParams_Number();
}
