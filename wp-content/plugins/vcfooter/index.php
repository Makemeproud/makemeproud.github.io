<?php
/* 
Plugin Name: Ultimate Footer Builder
Description: Easy way to create any footers you can imagine.
Author: BestBug
Version: 1.9.2
Author URI: http://bb-footer-builder.bestbug.net/
Text Domain: bestbug
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'BBFOOTER_DESIGNER_VERSION' ) or define('BBFOOTER_DESIGNER_VERSION', '1.9.0') ;
defined( 'BBFOOTER_DESIGNER_CATEGORY' ) or define('BBFOOTER_DESIGNER_CATEGORY', 'Footer Builder') ;

defined( 'BESTBUG_FB_URL' ) or define('BESTBUG_FB_URL', plugins_url( '/', __FILE__ )) ;

defined( 'BESTBUG_FB_PATH' ) or define('BESTBUG_FB_PATH', basename( dirname( __FILE__ ))) ;
defined( 'BESTBUG_FB_TEXTDOMAIN' ) or define('BESTBUG_FB_TEXTDOMAIN', plugins_url( '/', __FILE__ )) ;

// SHORTCODE
defined( 'BESTBUG_FB_SHORTCODE_MENU' ) or define('BESTBUG_FB_SHORTCODE_MENU', 'bbfb_menus') ;
defined( 'BESTBUG_FB_SHORTCODE_INSTAGRAM' ) or define('BESTBUG_FB_SHORTCODE_INSTAGRAM', 'bbfb_instagram') ;
defined( 'BESTBUG_FB_SHORTCODE_SOCIAL' ) or define('BESTBUG_FB_SHORTCODE_SOCIAL', 'bbfb_social') ;
defined( 'BESTBUG_FB_SEARCH_SHORTCODE' ) or define('BESTBUG_FB_SEARCH_SHORTCODE', 'bbfb_search') ;
defined( 'BESTBUG_FB_LOGO_SHORTCODE' ) or define('BESTBUG_FB_LOGO_SHORTCODE', 'bbfb_logo') ;
defined( 'BESTBUG_FB_CART_SHORTCODE' ) or define('BESTBUG_FB_CART_SHORTCODE', 'bbfb_cart') ;
defined( 'BESTBUG_FB_CONTACT_SHORTCODE' ) or define('BESTBUG_FB_CONTACT_SHORTCODE', 'bbfb_contact') ;
// PREFIX
defined( 'BESTBUG_FB_PREFIX' ) or define('BESTBUG_FB_PREFIX', 'bb_fb_') ;

//SLUG
defined( 'BESTBUG_FB_PAGESLUG' ) or define('BESTBUG_FB_PAGESLUG', 'bb_footer_builder') ;

defined( 'BESTBUG_FB_DEFAULT_FOOTER' ) or define('BESTBUG_FB_DEFAULT_FOOTER', 'bbfb_default_footer') ;
defined( 'BESTBUG_FB_POST_TYPES' ) or define('BESTBUG_FB_POST_TYPES', 'bbfb_post_types') ;

defined( 'BESTBUG_FB_FOOTER_POSTTYPE' ) or define('BESTBUG_FB_FOOTER_POSTTYPE', 'bbfb_content') ;

defined( 'BESTBUG_FB_METABOX_FOOTER' ) or define('BESTBUG_FB_METABOX_FOOTER', '_bb_footer') ;
defined( 'BESTBUG_FB_METABOX_MAX_WIDTH' ) or define('BESTBUG_FB_METABOX_MAX_WIDTH', '_bb_footer_max_width') ;


if ( ! class_exists( 'BESTBUG_FB_CLASS' ) ) {
	/**
	 * BESTBUG_FB_CLASS Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_CLASS {
		
		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			// Load core
			if(!class_exists('BESTBUG_CORE_CLASS')) {
				include_once 'bestbugcore/index.php';
			}
				
			BESTBUG_CORE_CLASS::support('vc-params');
			BESTBUG_CORE_CLASS::support('options');
			BESTBUG_CORE_CLASS::support('about-bb');
			if(is_admin()) {
				include_once 'includes/admin/index.php';
			}
			BESTBUG_CORE_CLASS::support('posttypes');
			include_once 'includes/index.php';
			include_once 'includes/shortcodes/index.php';
			
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'bb_about_submenu_page', array( $this, 'about_menu' ), 99 );
			
		}

		public function init() {
			// Load enqueueScripts
			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

			add_action('admin_bar_menu', array( $this,'edit_post_current'), 200);
			// Ultimate addons
			add_action('wp_enqueue_scripts', array( $this,'support_ultimate_addons'),100);
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links') );
			
        }
		
		public	function edit_post_current($wp_admin_bar) {
			$footer_id = apply_filters('bbfb_id_footer', 0);
			if($footer_id <= 0) {
				return;
			}
			$wp_admin_bar->add_node(array(
				'id' => 'bb-edit-footer',
				'title' => 'Edit Footer',
				'href' =>  get_site_url().'/wp-admin/post.php?post='.$footer_id.'&action=edit',
				'meta' => array('class' => 'bbfb_edit'),
			));
			$wp_admin_bar->remove_node('admin');
		}

		public function about_menu($sub_menu) {
			$sub_menu[] = array(
				'parent_slug' => 'edit.php?post_type=bbfb_content',
				'slug' => 'bbft_wpbb_about',
			);
			return $sub_menu;
		}
		
		public function adminEnqueueScripts() {
			BESTBUG_CORE_CLASS::adminEnqueueScripts();
			wp_enqueue_style( 'font-awesome', BESTBUG_FB_URL . '/assets/lib/font-awesome/css/font-awesome.min.css', array(), BBFOOTER_DESIGNER_VERSION  );
			wp_enqueue_style( 'bbfb', BESTBUG_FB_URL . '/assets/admin/css/admin.css', array(), BBFOOTER_DESIGNER_VERSION  );
			wp_enqueue_script( 'bbfb-admin', BESTBUG_FB_URL . '/assets/admin/js/bbfb-admin.js', array( 'jquery' ), BBFOOTER_DESIGNER_VERSION, true );

		}

		public function enqueueScripts() {
			BESTBUG_CORE_CLASS::enqueueScripts();
			
			wp_enqueue_style( 'bbfb', BESTBUG_FB_URL . '/assets/css/bbfb.css', array(), BBFOOTER_DESIGNER_VERSION );
			wp_enqueue_script( 'bbfb-builder', BESTBUG_FB_URL . '/assets/js/script.js', array( 'jquery' ), BBFOOTER_DESIGNER_VERSION, true );

			$footer_name = '';

			if (bb_option(BESTBUG_FB_PREFIX . 'display_by_fsettings') != '' && bb_option(BESTBUG_FB_PREFIX . 'display_by_fsettings') == 'yes') {
				$footer_name = BESTBUG_FB_FILTER::get_footer_by_own_settings();
			} else {
				$footer_name = BESTBUG_FB_FILTER::get_footer_by_global_settings();
			}

			if(!$footer_name) {
				return;
			}

			$footer = get_page_by_path( $footer_name, OBJECT, BESTBUG_FB_FOOTER_POSTTYPE );

			if(!$footer) {
				return;
			}
			if(function_exists('icl_object_id')) {
				$post_id = BESTBUG_FB_HELPER::ml_get_the_content($footer->ID);
				$footer_tmp = new WP_Query( array( 'post_type' => BESTBUG_FB_FOOTER_POSTTYPE, 'p' => $post_id ) );
				if(isset($footer_tmp->posts[0])) {
					$footer = $footer_tmp->posts[0];
				}
			}

			$custom_css = get_post_meta( $footer->ID , '_wpb_shortcodes_custom_css', true );

			$maxWidth = get_post_meta( $footer->ID , BESTBUG_FB_METABOX_MAX_WIDTH, true );

			if(!$maxWidth) {
				$selector = '#bb-footer-inside-' . $footer_name . ' .bb-footer-inside';
				$maxWidth = bb_option(BESTBUG_FB_PREFIX . 'max_width');
			}
			if($maxWidth) {
				if(is_numeric($maxWidth)) {
					if($maxWidth <= 100) {
						$maxWidth .= '%';
					} else {
						$maxWidth .= 'px';
					}
					$custom_css .= $selector . ' { max-width: ' . $maxWidth . '; }';

				} else {
					$custom_css .= $selector . ' { max-width: ' . $maxWidth . '; }';
				}
			} 

			// $bgDefaults = get_post_meta( $footer->ID , BESTBUG_FB_METABOX_BACKGROUND, true );

			$selector = '#bb-footer-inside-' . $footer_name;
			// if($bgDefaults) {
			// 	$custom_css .= $selector . ' { background: '.$bgDefaults.'; }';
			// }

			wp_add_inline_style( 'bbfb', $custom_css );
		}
		
		public function loadTextDomain() {
			load_plugin_textdomain( BESTBUG_FB_TEXTDOMAIN, false, BESTBUG_FB_PATH . '/languages/' );
		}
		
		public function add_action_links ( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'admin.php?page=bb_footer_builder' ) . '">Settings</a>',
			);
			return array_merge( $mylinks, $links );
		}
		
		public function support_ultimate_addons() {
			if(defined('ULTIMATE_VERSION')) {
				wp_enqueue_style('ultimate-style-min');
			}
		}
	}
	new BESTBUG_FB_CLASS();
}
