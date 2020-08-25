<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Bbhd_Flexbox' ) ) {
	class WPBakeryShortCode_Bbhd_Flexbox extends WPBakeryShortCodesContainer {
	}
} else {
	add_action( 'init', function(){
		global $composer_settings;
		if ( ! empty( $composer_settings ) ) {
			if ( array_key_exists( 'COMPOSER_LIB', $composer_settings ) ) {
				$lib_dir = $composer_settings['COMPOSER_LIB'];
				if ( file_exists( $lib_dir . 'shortcodes.php' ) ) {
					require_once( $lib_dir . 'shortcodes.php' );
				}
			}
		}
		if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Bbhd_Flexbox' ) ) {
			class WPBakeryShortCode_Bbhd_Flexbox extends WPBakeryShortCodesContainer {
			}
		}
	} );
}

if ( ! class_exists( 'BESTBUG_HB_SHORTCODE_FLEXBOX_ITEM' ) ) {
	/**
	 * BESTBUG_HB_SHORTCODE_FLEXBOX_ITEM Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_SHORTCODE_FLEXBOX_ITEM {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			
			add_shortcode( BESTBUG_HB_FLEXBOX_SHORTCODE, array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/admin/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
		}

		public function enqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
		}
        
		public function vc_shortcode() {
			vc_map( array(
			    "name" => esc_html__( "Flexbox", 'bestbug' ),
			    "base" => BESTBUG_HB_FLEXBOX_SHORTCODE,
			    "as_parent" => array('except' => BESTBUG_HB_FLEXBOX_SHORTCODE),
				"as_child" => array('only' => BESTBUG_HB_FLEXBOX_CONTAINER_SHORTCODE),
			    "content_element" => true,
				"icon" => "bbhd_icon_plus",
			    "js_view" => 'VcColumnView',
				"description" => esc_html__( "Like Column shortcode", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BESTBUG_HB_CATEGORY ) ),
			    "params" => array(
					array(
						'type'        => 'dropdown',
						'heading'     => 'Smart width',
						'param_name'  => 'flex_grow',
						'value' => array(
							'Default' => '',
							esc_html__('The rest of space', 'bestbug') => '1',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Max width', 'bestbug' ),
						'param_name' => 'max_width',
						'description' => esc_html__('Example: 30px or 30%', 'bestbug'),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => 'Text Align',
						'param_name'  => 'align',
						'value' => array(
							'Left' => 'left',
							'Center' => 'center',
							'Right' => 'right',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'bestbug' ),
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'bestbug'),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'css_editor',
						'heading' => 'CSS box',
						'param_name' => 'css',
						'group' => 'Design Options',
					),
			    ),
			) );
        }
		public function settings($attr = BESTBUG_HB_FLEXBOX_SHORTCODE) {
			return BESTBUG_HB_FLEXBOX_SHORTCODE;
		}
		
		public function shortcode( $atts, $content = '' ){
			$atts = (is_array($atts))?$atts:array();
			shortcode_atts( array(
				'align' => '',
				'flex_grow' => '',
				'css' => '',
				'el_class' => '',
			), $atts );
			$attr = $atts;
			
			if(!isset($attr['el_class'])) {
				$attr['el_class'] = '';
			}
			if(!isset($attr['css'])) {
				$attr['css'] = '';
			}

			if(!isset($atts['align'])) {
				$attr['align'] = 'left';
			}
			if(!isset($atts['flex_grow'])) {
				$attr['flex_grow'] = '0';
			}
			
			if(!isset($atts['max_width'])) {
				$attr['max_width'] = '';
			} else if(!empty($atts['max_width'])) {
				if(is_numeric($atts['max_width'])) {
					$atts['max_width'] .= '%';
				}
				$attr['max_width'] = 'style="max-width: '.$atts['max_width'].';flex-basis: '.$atts['max_width'].';"';
			}
			
			$css_class = apply_filters( BESTBUG_HELPER::$VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, BESTBUG_HELPER::vc_shortcode_custom_css_class( $attr['css'], ' ' ), BESTBUG_HB_FLEXBOX_SHORTCODE, $atts );
			$css_class .= ' bbhd-flexbox-item ' . $attr['el_class'] . ' bbhd-text-' . $attr['align'] . ' flex-grow-' . $attr['flex_grow'];

			return '<div class="'.esc_attr($css_class).'" '.$attr['max_width'].'>'.do_shortcode($content).'</div>';
		}
		
    }
	
	new BESTBUG_HB_SHORTCODE_FLEXBOX_ITEM();
}
