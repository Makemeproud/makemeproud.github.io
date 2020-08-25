<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** How to use

vc_add_param( $shortcode, array(
	'type' => 'bb_tags',
	'heading' => esc_html__('Show/Hide on ', 'bestbug'),
	'param_name' => 'show_hide',
	'group' => $group,
	'value' => 'yes',
)); */

if(!class_exists('BestBug_Extend_VcParams_Tags'))
{
	class BestBug_Extend_VcParams_Tags
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
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
					
					WpbakeryShortcodeParams::addField('bb_tags' , array($this, 'bb_tags'), BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/tags.js');
				}
			}
		}

		function bb_tags($settings, $value){

			$output = $checked = '';
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			if(empty($value)) {
				$value = isset($settings['value']) ? $settings['value'] : '';
			}

			$output = '<div class="bb-tags">';

			$output .= '<input class="wpb_vc_param_value" name="'.$param_name.'" type="text" value="'.$value.'" />';

			$output .= '</div>';

			return $output;
		}

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'tagsinput', BESTBUG_CORE_URL . '/assets/admin/css/jquery.tagsinput.css' );
			wp_enqueue_style( 'bb-tags', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/tags.css' );
			
			wp_enqueue_script( 'tagsinput', BESTBUG_CORE_URL . '/assets/admin/js/jquery.tagsinput.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
		}

	}

	new BestBug_Extend_VcParams_Tags();
}
