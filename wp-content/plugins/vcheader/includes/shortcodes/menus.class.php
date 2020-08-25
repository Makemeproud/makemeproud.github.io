<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_MENU_SHORTCODE' ) ) {
	/**
	 * BESTBUG_HB_MENU_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_MENU_SHORTCODE {

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
			
			add_shortcode( BESTBUG_HB_MENU_SHORTCODE, array( $this, 'shortcode' ) );
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
		        'name'                      => esc_html__( 'Main Menus', 'bestbug' ),
		        'base'                      => BESTBUG_HB_MENU_SHORTCODE,
		        'category'                  => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BESTBUG_HB_CATEGORY ) ),
				"icon" => "bbhd_icon_menu",
				'description' => esc_html__( 'Menu for your header', 'bestbug' ),
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
				        'heading'     => esc_html__( 'Menu Style', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Default', 'bestbug' ) => '',
							esc_html__( 'Inline Large', 'bestbug' ) => 'inline-large',
							esc_html__( 'Inline Normal', 'bestbug' ) => 'inline-normal',
							esc_html__( 'Inline Small', 'bestbug' ) => 'inline-small',
							esc_html__( 'List Normal', 'bestbug' ) => 'list-normal',
						),
				        'param_name'  => 'style',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Color', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Dark', 'bestbug' ) => 'dark',
							esc_html__( 'Light', 'bestbug' ) => 'light',
						),
				        'param_name'  => 'color',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Submenu Shape', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Rounded', 'bestbug' ) => 'rounded',
							esc_html__( 'Square', 'bestbug' ) => 'square',
						),
				        'param_name'  => 'sub_shape',
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
						'heading' => esc_html__( 'Normal status', 'bestbug' ),
						'param_name' => 'root_menu_style',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .bbhd-menu > ul > li > a',
						'group' => esc_html__( "Root menu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Hover status', 'bestbug' ),
						'param_name' => 'root_menu_hover',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .bbhd-menu > ul > li:hover > a',
						'group' => esc_html__( "Root menu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Active status', 'bestbug' ),
						'param_name' => 'root_menu_actived',
						'use' => array(
							'font',
							'border',
						),
						'selector' => '#class# .bbhd-menu > ul > li.current-menu-item > a',
						'group' => esc_html__( "Root menu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu Container', 'bestbug' ),
						'param_name' => 'submenu_container',
						'use' => array(
							'border',
							'border-radius',
							'padding',
							'margin',
							'width-height',
							'background',
						),
						'selector' => '#class# .bb-dropdown-menu',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu link', 'bestbug' ),
						'param_name' => 'submenu_link',
						'use' => array(
							'font',
							'border',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bb-dropdown-menu a',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu icon', 'bestbug' ),
						'param_name' => 'submenu_icon',
						'use' => array(
							'font',
						),
						'selector' => '#class# .bb-dropdown-menu .dropdown:after',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu link hover', 'bestbug' ),
						'param_name' => 'submenu_link_hover',
						'use' => array(
							'font',
							'border',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bb-dropdown-menu a:hover',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu link active', 'bestbug' ),
						'param_name' => 'submenu_link_actived',
						'use' => array(
							'font',
							'border',
							'background',
						),
						'selector' => '#class# .dropdown .bb-dropdown-menu .current-menu-item > a',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Submenu icon hover', 'bestbug' ),
						'param_name' => 'submenu_icon_hover',
						'use' => array(
							'font',
						),
						'selector' => '#class# .bb-dropdown-menu .dropdown:hover:after',
						'group' => esc_html__( "Submenu style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Megamenu container', 'bestbug' ),
						'param_name' => 'megamenu_container',
						'use' => array(
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
							'width-height',
						),
						'selector' => '#class# .bbhd-mega-menu',
						'group' => esc_html__( "Megamenu style", 'bestbug' ),
					),
		        ),
		    ) );
        }
		public function settings($attr = BESTBUG_HB_MENU_SHORTCODE) {
			return BESTBUG_HB_MENU_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			$atts = shortcode_atts( array(
				'title' => '',
				'menu' => '',
				'style' => '',
				'color' => '',
				'text_align' => '',
				'sub_shape' => '',
				'el_class' => '',
				
				'root_menu_style' => '',
				'root_menu_hover' => '',
				'root_menu_actived' => '',
				'submenu_container' => '',
				'submenu_link' => '',
				'submenu_icon' => '',
				'submenu_link_hover' => '',
				'submenu_icon_hover' => '',
				'submenu_link_actived' => '',
				'megamenu_container' => '',
			), $atts );

			extract( $atts );

			$class_array = array('bbhd-menu');
			if(isset($atts[ 'submenu_container' ]) && !empty($atts[ 'submenu_container' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_container' ]));
			}
			if(isset($atts[ 'submenu_link' ]) && !empty($atts[ 'submenu_link' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_link' ]));
			}
			if(isset($atts[ 'submenu_icon' ]) && !empty($atts[ 'submenu_icon' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_icon' ]));
			}
			if(isset($atts[ 'submenu_link_hover' ]) && !empty($atts[ 'submenu_link_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_link_hover' ]));
			}
			if(isset($atts[ 'submenu_icon_hover' ]) && !empty($atts[ 'submenu_icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_icon_hover' ]));
			}
			if(isset($atts[ 'submenu_link_actived' ]) && !empty($atts[ 'submenu_link_actived' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_link_actived' ]));
			}
			if(isset($atts[ 'megamenu_container' ]) && !empty($atts[ 'megamenu_container' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'megamenu_container' ]));
			}
			if(isset($atts[ 'root_menu_style' ]) && !empty($atts[ 'root_menu_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'root_menu_style' ]));
			}
			if(isset($atts[ 'root_menu_hover' ]) && !empty($atts[ 'root_menu_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'root_menu_hover' ]));
			}
			if(isset($atts[ 'root_menu_actived' ]) && !empty($atts[ 'root_menu_actived' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'root_menu_actived' ]));
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
			if(isset($sub_shape) && !empty($sub_shape)) {
				array_push($class_array, 'bbhd-shape-'.$sub_shape);
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
				'container_class' => 'bbhd-menu menu__container',
				//'after'           => '<i class="bb-dropdown-menu-toggle fa fa-angle-down"></i>',
				'menu' => $nav_menu, 
				'echo' => false,
			);
			
			$nav['walker'] = new BESTBUG_MEGAMENU_WALKER;
			
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
	
	new BESTBUG_HB_MENU_SHORTCODE();
}
