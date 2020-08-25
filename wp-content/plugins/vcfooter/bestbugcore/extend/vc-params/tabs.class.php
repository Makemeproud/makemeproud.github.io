<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** How to use

vc_add_param( $shortcode, array(
	'type' => 'bb_tab',
	'param_name' => 'bb_tab_container',
	'active' => BestBugVCEDOHelper::$tab_active,
	'tabs' => $tabs,
	'suffix' => array('typo', 'show_hide'),
	'class' => BestBugVCEDOHelper::$menu_tab_position,
	'group' => $group,
)); */

if(!class_exists('BestBug_Extend_VcParams_Tabs'))
{
	class BestBug_Extend_VcParams_Tabs
	{
		function __construct()
		{
			add_action('init', array($this, 'init'));
		}
		
		function init()
		{
			if ( class_exists( 'WpbakeryShortcodeParams' ) )
			{
				WpbakeryShortcodeParams::addField('bb_tabs' , array($this, 'bb_tabs'), BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/tabs.js');
				
				// Load enqueueScripts
				if(is_admin()) {
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
				}
			}
		}

		function bb_tabs($settings, $value){

			$output = $checked = '';

			if( isset($settings['suffix']) && !empty($settings['suffix']) ) {
				$suffix = implode('|', $settings['suffix']);
			} else {
				$suffix = '';
			}

			$output = '<div class="bb-tabs-container '.esc_attr($settings['class']).'" data-tab-active="'.esc_attr($settings['active']).'" data-suffix="'.esc_attr($suffix).'"><ul>';

			$flag = true;
			foreach ($settings['tabs'] as $param_name => $tab) {
				$class = '';
				if($settings['active'] == $param_name) {
					$class = 'active';
					$flag = false;
				}

				if($settings['class'] == 'top') {
					$bbhelp_class = 'bbhelp--bottom';
				} elseif($settings['class'] == 'right') {
					$bbhelp_class = 'bbhelp--left';
				}

				if($tab['icon'] == 'class_icon' && !empty($tab['class_icon'])) {
					$icon = '<span class="'.esc_attr($tab['class_icon']).'"></span>';
				} elseif($tab['icon'] == 'image_icon' && !empty($tab['image_icon'])) {
					$icon = '<div class="img"><img src="'.esc_attr($tab['image_icon']).'" alt="" /></div>';
				} else {
					$icon = '<span class="dashicons dashicons-image-rotate-right"></span>';
				}

				$output .= '<li class="'.$class.' bb-tab-item-container"><a bbhelp-label="'.$tab['label'].'" class="'.esc_attr($bbhelp_class).'" href="#" data-bb-tab-target="'.$param_name.'">'.$icon.'</a></li>';
			}

			$output .= '</ul></div>';

			return $output;

		}

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'bb-tabs', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/tabs.css' );
		}

	}

	new BestBug_Extend_VcParams_Tabs();
}
