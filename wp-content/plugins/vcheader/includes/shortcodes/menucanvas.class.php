<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Bbhd_Menucanvas' ) ) {
	class WPBakeryShortCode_Bbhd_Menucanvas extends WPBakeryShortCodesContainer {
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
		if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Bbhd_Menucanvas' ) ) {
			class WPBakeryShortCode_Bbhd_Menucanvas extends WPBakeryShortCodesContainer {
			}
		}
	} );
}


if ( ! class_exists( 'BESTBUG_HB_MENUCANVAS_SHORTCODE' ) ) {
	/**
	 * BESTBUG_HB_MENUCANVAS_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_MENUCANVAS_SHORTCODE {

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
			
			add_shortcode( BESTBUG_HB_MENUCANVAS_SHORTCODE, array( $this, 'shortcode' ) );
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
			    "name" => esc_html__( "Menu canvas", 'bestbug' ),
			    "base" => BESTBUG_HB_MENUCANVAS_SHORTCODE,
			    "content_element" => true,
				"icon" => "bbhd_icon_plus",
				"as_parent" => array('except' => BESTBUG_HB_MENUCANVAS_SHORTCODE),
			    "js_view" => 'VcColumnView',
				"description" => esc_html__( "Menu in right side", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BESTBUG_HB_CATEGORY ) ),
			    "params" => array(
					array(
				        'type'        => 'dropdown',
				        'param_name'  => 'style',
				        'heading'     => esc_html__( 'Style', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Right side', 'bestbug' ) => 'right-side',
							esc_html__( 'Dropdown', 'bestbug' ) => 'dropdown',
						),
			        ),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'bestbug' ),
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'bestbug'),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Open Icon', 'bestbug' ),
						'param_name' => 'open_icon',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bbhd-open-menucanvas',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Open Icon Hover', 'bestbug' ),
						'param_name' => 'open_icon_hover',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'background',
						),
						'selector' => '#class# .bbhd-open-menucanvas:hover',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Close Icon', 'bestbug' ),
						'param_name' => 'close_icon',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
							'position',
						),
						'selector' => '#class# .bbhd-close-menucanvas',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Close Icon Hover', 'bestbug' ),
						'param_name' => 'close_icon_hover',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'background',
						),
						'selector' => '#class# .bbhd-close-menucanvas:hover',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Container style', 'bestbug' ),
						'param_name' => 'menu_container',
						'use' => array(
							'border',
							'border-radius',
							'margin',
							'padding',
							'background',
							'width-height',
						),
						'selector' => '#class# .bbhd-header-menuside, #class# .dropdown-inner-container',
						'group' => esc_html__( "Container style", 'bestbug' ),
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
		public function settings($attr = BESTBUG_HB_MENUCANVAS_SHORTCODE) {
			return BESTBUG_HB_MENUCANVAS_SHORTCODE;
		}
		
		public function shortcode( $atts, $content = '' ){

			extract( shortcode_atts( array(
				'css' => '',
				'el_class' => '',
				'style' => '',
				
				'open_icon' => '',
				'open_icon_hover' => '',
				'close_icon' => '',
				'close_icon_hover' => '',
				'menu_container' => '',
			), $atts ) );

			$class_array = array('bbhd-menu-canvas-wrap');
			if(isset($atts[ 'open_icon' ]) && !empty($atts[ 'open_icon' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'open_icon' ]));
			}
			if(isset($atts[ 'open_icon_hover' ]) && !empty($atts[ 'open_icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'open_icon_hover' ]));
			}
			if(isset($atts[ 'close_icon' ]) && !empty($atts[ 'close_icon' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'close_icon' ]));
			}
			if(isset($atts[ 'close_icon_hover' ]) && !empty($atts[ 'close_icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'close_icon_hover' ]));
			}
			if(isset($atts[ 'menu_container' ]) && !empty($atts[ 'menu_container' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'menu_container' ]));
			}
			if(isset($atts[ 'style' ]) && !empty($atts[ 'style' ])) {
				array_push($class_array, 'style-' . $atts[ 'style' ]);
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_HB_MENU_SHORTCODE, $atts );
			
			if(isset($atts[ 'style' ]) && $atts[ 'style' ] == 'dropdown') {
				$inside_content = '<div class="dropdown-content">
                                        <div class="dropdown-inner-container">
                                            <div class="triangle"></div>
                                            <div class="bbhb-inside-content">
											'.do_shortcode($content).'
											</div>
                                        </div>
                                    </div>';
			} else {
				$inside_content = '<div class="bbhd-header-menuside">
					<div class="bbhd-menuside-inside">
						<div class="bbhd-close-menucanvas">×</div>
						'.do_shortcode($content).'
					</div>
				</div>';
			}
			
			return '<div class="'.esc_attr($class_string).'">
						<a class="bbhd-open-menucanvas" href="javascript:;">
							<span class="fa fa-bars"></span>
						</a>
						'.$inside_content.'
					</div>';
		}
    }
	
	new BESTBUG_HB_MENUCANVAS_SHORTCODE();
}
