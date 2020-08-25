<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_LOGO_SHORTCODE' ) ) {
	/**
	 * BESTBUG_FB_LOGO_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_LOGO_SHORTCODE {

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
			
			add_shortcode( BESTBUG_FB_LOGO_SHORTCODE, array( $this, 'shortcode' ) );
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
			    "name" => esc_html__( "Logo", 'bestbug' ),
			    "base" => BESTBUG_FB_LOGO_SHORTCODE,
			    "as_parent" => array('except' => BESTBUG_FB_LOGO_SHORTCODE),
			    "content_element" => true,
				"icon" => 'icon-' . BESTBUG_FB_LOGO_SHORTCODE,
				"description" => esc_html__( "Set logo for header", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BBFOOTER_DESIGNER_CATEGORY ) ),
			    "params" => array(
					array(
						'type'        => 'textfield',
						'heading'     => 'Title',
						'param_name'  => 'title',
						'admin_label' => true,
					),
					array(
						'type'        => 'attach_image',
						'heading'     => 'Logo',
						'param_name'  => 'logo',
						'admin_label' => true,
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
		public function settings($attr = BESTBUG_FB_LOGO_SHORTCODE) {
			return BESTBUG_FB_LOGO_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			extract( shortcode_atts( array(
				'title' => '',
				'logo' => '',
				'css' => '',
				'el_class' => '',
			), $atts ) );
			
			$class_array = array('bbfb-logo');
			if(isset($css) && !empty($css)) {
				array_push($class_array, BESTBUG_HELPER::vc_shortcode_custom_css_class($css));
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_FB_LOGO_SHORTCODE, $atts );
			
			if ( $logo > 0 ) {
				$logo = wp_get_attachment_image_src( $logo, 'full' );
				if(isset($logo[0]) && !empty($logo[0])) {
					return '<a class="'.esc_attr($class_string).'" href="'.home_url().'" ><img alt="'.$title.'" src="'.$logo[0].'" /></a>';
				}
			}
			
			return '';
		}
		
    }
	
	new BESTBUG_FB_LOGO_SHORTCODE();
}
