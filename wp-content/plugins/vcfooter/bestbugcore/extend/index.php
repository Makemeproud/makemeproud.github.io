<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if(!class_exists('BestBug_Extend_Vc'))
{
	class BestBug_Extend_Vc
	{
		function __construct()
		{
			add_action('init', array($this, 'init'));
			add_filter( 'vc_shortcodes_css_class', array($this, 'support_5columns'), 10, 3 );
		}
		
		function init()
		{
			global $vc_row_layouts;
			if(!empty($vc_row_layouts)) {
				foreach ($vc_row_layouts as $key => $layout) {
					if (isset($layout['cells']) && $layout['cells'] == '15_15_15_15_15') {
						return;
					}
				}
			}
			$vc_row_layouts[] = array( 'cells' => '15_15_15_15_15', 'mask' => '530', 'title' => '1/5 + 1/5 + 1/5 + 1/5 + 1/5', 'icon_class' => 'l_15_15_15_15_15' );
			return $vc_row_layouts;
		}
		
		public function support_5columns( $class_string = '', $tag = '', $atts = null ){
			if ( $tag != 'vc_column' ) {
				return $class_string;
			}

			if(isset($atts['width']) && $atts['width'] == '1/5') {
				$class_string = str_replace("vc_col-sm-3", "vc_col-sm-1-per-5", $class_string);
			}

			return $class_string;
		}

	}

	new BestBug_Extend_Vc();
}
