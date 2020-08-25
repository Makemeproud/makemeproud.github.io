<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_VC_ROW_SHORTCODE' ) ) {
	/**
	 * BESTBUG_HB_VC_ROW_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_VC_ROW_SHORTCODE {

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
			add_filter( 'vc_shortcodes_css_class', array($this, 'vc_row_filter'), 10, 3 );
			
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
			$group = BESTBUG_HB_CATEGORY;
			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Show on Desktop', 'bestbug'),
				'param_name' => 'show_hide_on_desktop',
				'group' => $group,
				'value' => 'yes',
			));

			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Show on Mobile', 'bestbug'),
				'param_name' => 'show_hide_on_mobile',
				'group' => $group,
				'value' => 'yes',
			));

			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Sticky', 'bestbug'),
				'param_name' => 'bbhd_sticky',
				'group' => $group,
				'value' => 'no',
			));

			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Hide border without sticky?', 'bestbug'),
				'param_name' => 'bbhd_hide_border',
				'group' => $group,
				'value' => 'no',
			));

			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Hide background without sticky?', 'bestbug'),
				'param_name' => 'bbhd_hide_background',
				'group' => $group,
				'value' => 'no',
			));

			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Overlay', 'bestbug'),
				'param_name' => 'bbhd_overlay',
				'group' => $group,
				'value' => 'no',
			));
			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Flex-container boxed?', 'bestbug'),
				'param_name' => 'bbhd_container_boxed',
				'group' => $group,
				'value' => 'no',
			));
			
			vc_add_param( 'vc_row', array(
				'type' => 'bb_toggle',
				'heading' => esc_html__('Overflow?', 'bestbug'),
				'param_name' => 'bb_overflow',
				'group' => $group,
				'value' => 'yes',
			));
        }
		
		public function vc_row_filter( $class_string = '', $tag = '', $atts = null ) {

			if ($tag != 'vc_row' ) {
				return $class_string;
			}

			if(isset($atts['show_hide_on_desktop']) && !empty($atts['show_hide_on_desktop']) && $atts['show_hide_on_desktop'] == 'no') {
				$class_string .= ' bbhd-hide-on-desktop';
			}
			if(isset($atts['show_hide_on_mobile']) && !empty($atts['show_hide_on_mobile']) && $atts['show_hide_on_mobile'] == 'no') {
				$class_string .= ' bbhd-hide-on-mobile';
			}
			
			if(isset($atts['bbhd_overlay']) && !empty($atts['bbhd_overlay']) && $atts['bbhd_overlay'] == 'yes') {
				$class_string .= ' bbhd-overlay';
			}
			
			if(isset($atts['bbhd_container_boxed']) && !empty($atts['bbhd_container_boxed']) && $atts['bbhd_container_boxed'] == 'yes') {
				$class_string .= ' bbhd-container-boxed';
			}
			
			if(isset($atts['bbhd_sticky']) && !empty($atts['bbhd_sticky']) && $atts['bbhd_sticky'] == 'yes') {
				$class_string .= ' bbhd-sticky';
			}
			if(isset($atts['bbhd_hide_border']) && !empty($atts['bbhd_hide_border']) && $atts['bbhd_hide_border'] == 'yes') {
				$class_string .= ' bbhd-hide-border';
			}
			if(isset($atts['bbhd_hide_background']) && !empty($atts['bbhd_hide_background']) && $atts['bbhd_hide_background'] == 'yes') {
				$class_string .= ' bbhd-hide-background';
			}
			
			if(isset($atts['bb_overflow']) && !empty($atts['bb_overflow']) && $atts['bb_overflow'] == 'yes') {
				$class_string .= ' bbhd-overflow';
			}

			return $class_string;
		}
		
    }
	
	new BESTBUG_HB_VC_ROW_SHORTCODE();
}
