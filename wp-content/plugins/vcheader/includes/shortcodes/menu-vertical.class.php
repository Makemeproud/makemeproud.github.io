<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_MENUVERTICAL_SHORTCODE' ) ) {
	/**
	 * BESTBUG_HB_MENUVERTICAL_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_MENUVERTICAL_SHORTCODE {

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
			
			add_shortcode( BESTBUG_HB_MENUVERTICAL_SHORTCODE, array( $this, 'shortcode' ) );
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
		        'name'                      => esc_html__( 'Menus vertical', 'bestbug' ),
		        'base'                      => BESTBUG_HB_MENUVERTICAL_SHORTCODE,
		        'category'                  => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BESTBUG_HB_CATEGORY ) ),
				"icon" => "bbhd_icon_menu",
				'description' => esc_html__( 'Menu for Mega or Canvas Menu', 'bestbug' ),
		        'allowed_container_element' => 'vc_row',
		        'params'                    => array(
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Title', 'bestbug' ),
						"param_name"  => "title",
						"value"       => "",
						'admin_label' => true,
					),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Choose Menu', 'bestbug' ),
				        'value'       => $this->bbhd_get_menu(),
				        'param_name'  => 'menu',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Style', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Default', 'bestbug' ) => '',
							esc_html__( 'Dark', 'bestbug' ) => 'dark',
							esc_html__( 'Light', 'bestbug' ) => 'light',
							esc_html__( 'Dropdown', 'bestbug' ) => 'dropdown-style',
						),
				        'param_name'  => 'color',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Text align', 'bestbug' ),
				        'value'       => array(
							'Default' => '',
							esc_html__( 'Left', 'bestbug' ) => 'text-left',
							esc_html__( 'Center', 'bestbug' ) => 'text-center',
							esc_html__( 'Right', 'bestbug' ) => 'text-right',
						),
				        'param_name'  => 'text_align',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Custom Class CSS', 'bestbug' ),
						"param_name"  => "el_class",
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Heading', 'bestbug' ),
						'param_name' => 'heading_style',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bbhd-menu-title',
						'group' => esc_html__( "Heading style", 'bestbug' ),
					),
					
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Link normal status', 'bestbug' ),
						'param_name' => 'link_normal',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# a',
						'group' => esc_html__( "Menu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Link hover status', 'bestbug' ),
						'param_name' => 'link_hover',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# a:hover',
						'group' => esc_html__( "Menu style", 'bestbug' ),
					),
		        ),
		    ) );
        }
		public function settings($attr = BESTBUG_HB_MENUVERTICAL_SHORTCODE) {
			return BESTBUG_HB_MENUVERTICAL_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			$atts = shortcode_atts( array(
				'title' => '',
				'menu' => '',
				'style' => '',
				'color' => '',
				'text_align' => '',
				'el_class' => '',
				
				'heading_style' => '',
				'link_normal' => '',
				'link_hover' => '',
			), $atts );
			$attr = $atts;
			extract( $attr );
			
			$class_array = array('bbhd-menu-vertical');
			if(isset($atts[ 'heading_style' ]) && !empty($atts[ 'heading_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'heading_style' ]));
			}
			if(isset($atts[ 'link_normal' ]) && !empty($atts[ 'link_normal' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'link_normal' ]));
			}
			if(isset($atts[ 'link_hover' ]) && !empty($atts[ 'link_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'link_hover' ]));
			}
			if(isset($style) && !empty($style)) {
				array_push($class_array, 'bbhd-menu-'.$style);
			}
			if(isset($color) && !empty($color)) {
				array_push($class_array, 'bbhd-menu-'.$color);
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			if(isset($text_align) && !empty($text_align)) {
				array_push($class_array, $text_align);
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_HB_MENU_SHORTCODE, $atts );

			$nav_menu = wp_get_nav_menu_object( $menu ); // Get menu

			if ( ! $nav_menu ) {
				return;
			}

			$html = '<div class="'.esc_attr( $class_string ).'">';
			if ( !empty( $title ) ) :
				$html .= '<h5 class="bbhd-menu-title"> '.esc_html($title).' </h5>';
			endif;
			
			$nav = array(
				'container_class' => 'bbhd-menu-horizontal menu__container',
				'menu' => $nav_menu, 
				'echo' => false,
			);
			
			$html .= wp_nav_menu( $nav );
			$html .= '</div>';

			return $html;
		}
		
		function bbhd_get_menu() {
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			// Properly format the array.
			$items = array();
			foreach ( $menus as $menu ) {
				$items[ $menu->name ] = $menu->slug;
			}

			return $items;
		}
    }
	
	new BESTBUG_HB_MENUVERTICAL_SHORTCODE();
}
