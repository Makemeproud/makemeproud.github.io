<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_CART_SHORTCODE' ) ) {
	/**
	 * BESTBUG_FB_CART_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_CART_SHORTCODE {

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
			if ( class_exists( 'WooCommerce' ) ) {
				add_shortcode( BESTBUG_FB_CART_SHORTCODE, array( $this, 'shortcode' ) );
				if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
					$this->vc_shortcode();
				}

				if(is_admin()) {
					add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
				}
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
				add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'woo_header_cart_fragment' ) );
			}
        }
		
		function woo_header_cart_fragment( $fragments ) {
			ob_start();
			echo self::header_cart();
			$fragments['.bbfb-mini-cart'] = ob_get_clean();

			return $fragments;
		}
		
		public static function header_cart() {
			if ( class_exists( 'WooCommerce' ) ) {
				global $woocommerce;
				
				$cart_html = '';
				$qty       = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : '';
				$total     = is_object( WC()->cart ) ? WC()->cart->get_cart_total() : '';

				$cart_html .= '<div class="bbfb-mini-cart"><div class="bbfb-mini-cart-icon" data-count="' . $qty . '"><i class="fa fa-shopping-cart"></i></div>';
				$cart_html .= '<div class="bbfb-mini-cart-text">' . esc_html__( 'Go to cart', 'tm-wilson' ) . '<div class="bbfb-mini-cart-total">' . $total . '</div></div>';
				$cart_html .= '</div>';

				return $cart_html;
			}
			return;
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
			    "name" => esc_html__( "Minicart", 'bestbug' ),
			    "base" => BESTBUG_FB_CART_SHORTCODE,
			    "content_element" => true,
				"icon" => 'icon-' . BESTBUG_FB_CART_SHORTCODE,
				"description" => esc_html__( "Cart box for header", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BBFOOTER_DESIGNER_CATEGORY ) ),
			    "params" => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'bestbug' ),
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'bestbug'),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Normal status', 'bestbug' ),
						'param_name' => 'icon_normal',
						'use' => array(
							'font',
						),
						'selector' => '#class# .bbfb-mini-cart-icon',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Hover status', 'bestbug' ),
						'param_name' => 'icon_hover',
						'use' => array(
							'font',
						),
						'selector' => '#class# .bbfb-mini-cart-icon:hover',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Quantity', 'bestbug' ),
						'param_name' => 'icon_quantity',
						'use' => array(
							'font',
							'background',
							'width-height'
						),
						'selector' => '#class# .bbfb-mini-cart-icon:after',
						'group' => esc_html__( "Icon style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Popup container', 'bestbug' ),
						'param_name' => 'popup_container_style',
						'use' => array(
							'padding',
							'margin',
							'border',
							'border-radius',
							'background',
							'width-height',
						),
						'selector' => '#class# .widget_shopping_cart_content',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Item container', 'bestbug' ),
						'param_name' => 'item_container_style',
						'use' => array(
							'padding',
							'margin',
							'border',
						),
						'selector' => '#class# .widget_shopping_cart_content li',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Images style', 'bestbug' ),
						'param_name' => 'images_style',
						'use' => array(
							'padding',
							'margin',
							'border',
							'width-height',
						),
						'selector' => '#class# .widget_shopping_cart_content li img',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Title style', 'bestbug' ),
						'param_name' => 'title_style',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content li a:not(.remove)',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Title style hover', 'bestbug' ),
						'param_name' => 'title_style_hover',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content li a:not(.remove):hover',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Quantity style', 'bestbug' ),
						'param_name' => 'quantity_style',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content li .quantity',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Remove button style', 'bestbug' ),
						'param_name' => 'remove_style',
						'use' => array(
							'font',
							'margin',
						),
						'selector' => '#class# .widget_shopping_cart_content li a.remove',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Remove button style hover', 'bestbug' ),
						'param_name' => 'remove_style_hover',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content li a.remove:hover',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Total label', 'bestbug' ),
						'param_name' => 'total_label',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content .total strong',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Total Amount', 'bestbug' ),
						'param_name' => 'total_amount',
						'use' => array(
							'font',
						),
						'selector' => '#class# .widget_shopping_cart_content .total .amount',
						'group' => esc_html__( "Popup style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Button Style', 'bestbug' ),
						'param_name' => 'button_style',
						'use' => array(
							'font',
							'background',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .widget_shopping_cart_content .buttons a',
						'group' => esc_html__( "Button style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Button Style Hover', 'bestbug' ),
						'param_name' => 'button_style_hover',
						'use' => array(
							'font',
							'background',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .widget_shopping_cart_content .buttons a:hover',
						'group' => esc_html__( "Button style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Button Checkout Style', 'bestbug' ),
						'param_name' => 'button_checkout_style',
						'use' => array(
							'font',
							'background',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .widget_shopping_cart_content .buttons a.checkout',
						'group' => esc_html__( "Button style", 'bestbug' ),
					),
					array(
						'type' => 'bb_responsive',
						'heading' => esc_html__( 'Button Checkout Style Hover', 'bestbug' ),
						'param_name' => 'button_checkout_style_hover',
						'use' => array(
							'font',
							'background',
							'border',
							'border-radius',
							'padding',
							'margin',
						),
						'selector' => '#class# .widget_shopping_cart_content .buttons a.checkout:hover',
						'group' => esc_html__( "Button style", 'bestbug' ),
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
		public function settings($attr = BESTBUG_FB_CART_SHORTCODE) {
			return BESTBUG_FB_CART_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			extract( shortcode_atts( array(
				'css' => '',
				'el_class' => '',
				'icon_normal' => '',
				'icon_hover' => '',
				'icon_quantity' => '',
				'popup_container_style' => '',
				'item_container_style' => '',
				'images_style' => '',
				'title_style' => '',
				'title_style_hover' => '',
				'quantity_style' => '',
				'remove_style' => '',
				'remove_style_hover' => '',
				'total_label' => '',
				'total_amount' => '',
				'button_style' => '',
				'button_style_hover' => '',
				'button_checkout_style' => '',
				'button_checkout_style_hover' => '',
			), $atts ) );
			
			$class_array = array();
			if(isset($atts[ 'el_class' ]) && !empty($atts[ 'el_class' ])) {
				array_push($class_array, $atts[ 'el_class' ]);
			}
			if(isset($atts[ 'icon_normal' ]) && !empty($atts[ 'icon_normal' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_normal' ]));
			}
			if(isset($atts[ 'icon_hover' ]) && !empty($atts[ 'icon_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_hover' ]));
			}
			if(isset($atts[ 'icon_quantity' ]) && !empty($atts[ 'icon_quantity' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'icon_quantity' ]));
			}
			if(isset($atts[ 'popup_container_style' ]) && !empty($atts[ 'popup_container_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'popup_container_style' ]));
			}
			if(isset($atts[ 'item_container_style' ]) && !empty($atts[ 'item_container_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'item_container_style' ]));
			}
			if(isset($atts[ 'images_style' ]) && !empty($atts[ 'images_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'images_style' ]));
			}
			if(isset($atts[ 'title_style' ]) && !empty($atts[ 'title_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'title_style' ]));
			}
			if(isset($atts[ 'title_style_hover' ]) && !empty($atts[ 'title_style_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'title_style_hover' ]));
			}
			if(isset($atts[ 'quantity_style' ]) && !empty($atts[ 'quantity_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'quantity_style' ]));
			}
			if(isset($atts[ 'remove_style' ]) && !empty($atts[ 'remove_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'remove_style' ]));
			}
			if(isset($atts[ 'remove_style_hover' ]) && !empty($atts[ 'remove_style_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'remove_style_hover' ]));
			}
			if(isset($atts[ 'total_label' ]) && !empty($atts[ 'total_label' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'total_label' ]));
			}
			if(isset($atts[ 'total_amount' ]) && !empty($atts[ 'total_amount' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'total_amount' ]));
			}
			if(isset($atts[ 'button_style' ]) && !empty($atts[ 'button_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'button_style' ]));
			}
			if(isset($atts[ 'button_style_hover' ]) && !empty($atts[ 'button_style_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'button_style_hover' ]));
			}
			if(isset($atts[ 'button_checkout_style' ]) && !empty($atts[ 'button_checkout_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'button_checkout_style' ]));
			}
			if(isset($atts[ 'button_checkout_style_hover' ]) && !empty($atts[ 'button_checkout_style_hover' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'button_checkout_style_hover' ]));
			}
			if(isset($css) && !empty($css)) {
				array_push($class_array, BESTBUG_HELPER::vc_shortcode_custom_css_class($css));
			}
			
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_FB_CART_SHORTCODE, $atts );
			
			return '<div class="bbfb-mini-cart-wrap '.esc_attr($class_string).'"><div class="bbfb-mini-cart">'.$this->header_cart($class_string) . '</div><div class="widget_shopping_cart_content"></div></div>';
		}
    }
	
	new BESTBUG_FB_CART_SHORTCODE();
}
