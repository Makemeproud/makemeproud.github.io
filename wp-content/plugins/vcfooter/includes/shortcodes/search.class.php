<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_SEARCH_SHORTCODE' ) ) {
	/**
	 * BESTBUG_FB_SEARCH_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_SEARCH_SHORTCODE {

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
			
			add_shortcode( BESTBUG_FB_SEARCH_SHORTCODE, array( $this, 'shortcode' ) );
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
			    "name" => esc_html__( "Search box", 'bestbug' ),
			    "base" => BESTBUG_FB_SEARCH_SHORTCODE,
			    "content_element" => true,
				"icon" => 'icon-' . BESTBUG_FB_SEARCH_SHORTCODE,
				"description" => esc_html__( "Search box for footer", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BBFOOTER_DESIGNER_CATEGORY ) ),
			    "params" => array(
					array(
						'type'        => 'textfield',
						'heading'     => 'Placeholder',
						'param_name'  => 'placeholder',
						'admin_label' => true,
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
						'selector' => '#class# .bbfb-btn-search',
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
						'selector' => '#class# .bbfb-btn-search:hover',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
			    ),
			) );
        }
		public function settings($attr = BESTBUG_FB_SEARCH_SHORTCODE) {
			return BESTBUG_FB_SEARCH_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			extract( shortcode_atts( array(
				'placeholder' => esc_html__('What are you looking for?', 'bestbug'),
				'el_class' => '',
				'open_icon' => '',
				'open_icon_hover' => '',
			), $atts ) );
			
			$id = 'bbfb-search-box-'.uniqid();

			$class_array = array('bbfb-search-box-wrap');
			if(isset($atts[ 'open_icon' ]) && !empty($atts[ 'open_icon' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'open_icon' ]));
			}
			if(isset($atts[ 'open_icon_hover' ]) && !empty($atts[ 'open_icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'open_icon_hover' ]));
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_FB_SEARCH_SHORTCODE, $atts );
			
			return '<div class="'.esc_attr($class_string).'">
						<a class="bbfb-btn-search" href="#'.esc_attr($id).'" >
							<span class="fa fa-search"></span>
						</a>
						<div id="'.esc_attr($id).'" class="bbfb-search-box">
							<div class="container">
								<form method="GET" action="'.esc_url( home_url('/') ).'">
									<button class="bbfb-search-submit" type="submit"><span></span></button>
									<input class="bbfb-search-txt" type="search" name="s"
										   placeholder="'. $placeholder .'">
									<div class="bbfb-search-close"><span></span></div>
								</form>
							</div>
						</div>
					</div>
			';
		}
		
    }
	
	new BESTBUG_FB_SEARCH_SHORTCODE();
}
