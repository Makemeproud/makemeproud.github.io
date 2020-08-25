<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_MENUMOBILE_SHORTCODE' ) ) {
	/**
	 * BESTBUG_HB_MENUMOBILE_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_MENUMOBILE_SHORTCODE {

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
			
			add_shortcode( BESTBUG_HB_MENUMOBILE_SHORTCODE, array( $this, 'shortcode' ) );
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
		        'name'                      => esc_html__( 'Menus mobile', 'bestbug' ),
		        'base'                      => 'bbhd_menu_mobile',
		        'category'                  => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BESTBUG_HB_CATEGORY ) ),
				"icon" => "bbhd_icon_menu",
		        'allowed_container_element' => 'vc_row',
				'description' => esc_html__( 'Menu Perfect on mobile', 'bestbug' ),
		        'params'                    => array(
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Choose Menu', 'bestbug' ),
				        'value'       => $this->bbhd_get_menu(),
				        'param_name'  => 'menu',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
						'type' => 'bb_toggle',
						'heading' => esc_html__('Show Close button', 'bestbug'),
						'param_name' => 'show_close_btn',
						'value' => 'no',
					),
					array(
						'type' => 'bb_toggle',
						'heading' => esc_html__('Full width', 'bestbug'),
						'param_name' => 'full_width',
						'value' => 'no',
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
						'param_name' => 'icon_normal',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bbhd-open-menu-mobile',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Hover status', 'bestbug' ),
						'param_name' => 'icon_hover',
						'use' => array(
							'font',
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bbhd-open-menu-mobile:hover',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Menu container', 'bestbug' ),
						'param_name' => 'menu_container',
						'use' => array(
							'border',
							'border-radius',
							'padding',
							'margin',
							'background',
						),
						'selector' => '#class# .bbhd-header-menuside',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Container Menu item', 'bestbug' ),
						'param_name' => 'container_menu_item',
						'use' => array(
							'padding',
							'background',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Menu item', 'bestbug' ),
						'param_name' => 'menu_item',
						'use' => array(
							'font',
							'padding',
							'background',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li a',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Container Menu item expand', 'bestbug' ),
						'param_name' => 'container_menu_item_expand',
						'use' => array(
							'padding',
							'background',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li.expand',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Menu item expand', 'bestbug' ),
						'param_name' => 'menu_item_expand',
						'use' => array(
							'font',
							'padding',
							'background',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li.expand a',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Sub menu', 'bestbug' ),
						'param_name' => 'submenu_style',
						'use' => array(
							'padding',
							'background',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li .bb-dropdown-menu',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
					),
					
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Icon dropdown', 'bestbug' ),
						'param_name' => 'icon_dropdown_style',
						'use' => array(
							'padding',
							'font',
							'border',
						),
						'selector' => '#class# .bbhd-menu-mobile ul li .bb-dropdown-menu-toggle',
						'group' => esc_html__( "Menu Style", 'bestbug' ),
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
		public function settings($attr = BESTBUG_HB_MENUMOBILE_SHORTCODE) {
			return BESTBUG_HB_MENUMOBILE_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			$atts = shortcode_atts( array(
				'menu' => '',
				'css' => '',
				'el_class' => '',
				
				'show_close_btn' => '',
				'full_width' => '',
				
				'icon_normal' => '',
				'icon_hover' => '',
				'menu_container' => '',
				'container_menu_item' => '',
				'menu_item' => '',
				'container_menu_item_expand' => '',
				'menu_item_expand' => '',
				'submenu_style' => '',
				'icon_dropdown_style' => '',
				
			), $atts );

			extract( $atts );
			
			$class_array = array('bbhd-menu-mobile-wrap');
			if(isset($atts[ 'icon_normal' ]) && !empty($atts[ 'icon_normal' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_normal' ]));
			}
			if(isset($atts[ 'icon_hover' ]) && !empty($atts[ 'icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_hover' ]));
			}
			if(isset($atts[ 'menu_container' ]) && !empty($atts[ 'menu_container' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'menu_container' ]));
			}
			if(isset($atts[ 'container_menu_item' ]) && !empty($atts[ 'container_menu_item' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'container_menu_item' ]));
			}
			if(isset($atts[ 'menu_item' ]) && !empty($atts[ 'menu_item' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'menu_item' ]));
			}
			if(isset($atts[ 'container_menu_item_expand' ]) && !empty($atts[ 'container_menu_item_expand' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'container_menu_item_expand' ]));
			}
			if(isset($atts[ 'menu_item_expand' ]) && !empty($atts[ 'menu_item_expand' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'menu_item_expand' ]));
			}
			if(isset($atts[ 'submenu_style' ]) && !empty($atts[ 'submenu_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'submenu_style' ]));
			}
			if(isset($atts[ 'icon_dropdown_style' ]) && !empty($atts[ 'icon_dropdown_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_dropdown_style' ]));
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			if(isset($css) && !empty($css)) {
				array_push($class_array, BESTBUG_HELPER::vc_shortcode_custom_css_class($css));
			}
			
			$close_btn_html = '';
			if(isset($atts['show_close_btn']) && $atts['show_close_btn'] == 'yes') {
				$close_btn_html = '<ul class="bbhd-mm-close"><li><a href="javascript:;" class="bbhd-close-mm-mobile"><i class="fa fa-times"></i> '.esc_html__('CLOSE', 'bestbug').'</a></li></ul>';
			}
			if(isset($atts['full_width']) && $atts['full_width'] == 'yes') {
				array_push($class_array, 'bbhd-mm-full_width');
			}
			
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_HB_MENU_SHORTCODE, $atts );

			$nav_menu = wp_get_nav_menu_object( $menu ); // Get menu

			if ( ! $nav_menu ) {
				return;
			}

			$html = '<div class="bbhd-menu-mobile">';
			$nav = array(
				'container_class' => 'bbhd-menu-mobile menu__container',
				'after'           => '<i class="bb-dropdown-menu-toggle fa fa-angle-down"></i>',
				'menu' => $nav_menu, 
				'echo' => false,
			);
			$nav['walker'] = new BESTBUG_MEGAMENU_WALKER;
			$html .= wp_nav_menu( $nav );
			$html .= '</div>';
			
			return '<div class="'.esc_attr($class_string).'">
						<div class="bbhd-close-menu-mobile bbhd-close-mm-mobile"></div>
						<a class="bbhd-open-menu-mobile" href="javascript:;">
							<span class="fa fa-bars"></span>
						</a>
						<div class="bbhd-header-menuside">
							<div class="bbhd-menuside-inside">
								'.$close_btn_html.do_shortcode($html).'
							</div>
						</div>
					</div>';
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
	
	new BESTBUG_HB_MENUMOBILE_SHORTCODE();
}
