<?php
/*
Plugin Name: Ultimate Header Builder
Description: Easy way to create any Headers you can imagine.
Author: BestBug
Version: 1.6
Author URI: http://bb-header-builder.bestbug.net/
Text Domain: bestbug
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'BBHEADER_DESIGNER_VERSION' ) or define('BBHEADER_DESIGNER_VERSION', '1.5.9') ;

defined( 'BESTBUG_HB_URL' ) or define('BESTBUG_HB_URL', plugins_url( '/', __FILE__ )) ;

defined( 'BESTBUG_HB_PATH' ) or define('BESTBUG_HB_PATH', basename( dirname( __FILE__ ))) ;
defined( 'BESTBUG_HB_CATEGORY' ) or define('BESTBUG_HB_CATEGORY', 'Header Builder') ;

defined( 'BESTBUG_HB_TEXTDOMAIN' ) or define('BESTBUG_HB_TEXTDOMAIN', plugins_url( '/', __FILE__ )) ;

defined( 'BESTBUG_HB_PREFIX' ) or define('BESTBUG_HB_PREFIX', 'bbhd_') ;

// SHORTCODES
defined( 'BESTBUG_HB_SHORTCODE' ) or define('BESTBUG_HB_SHORTCODE', 'bb_hd') ;
defined( 'BESTBUG_HB_SOCIAL_SHORTCODE' ) or define('BESTBUG_HB_SOCIAL_SHORTCODE', 'bbhd_social') ;
defined( 'BESTBUG_HB_FLEXBOX_SHORTCODE' ) or define('BESTBUG_HB_FLEXBOX_SHORTCODE', 'bbhd_flexbox') ;
defined( 'BESTBUG_HB_FLEXBOX_CONTAINER_SHORTCODE' ) or define('BESTBUG_HB_FLEXBOX_CONTAINER_SHORTCODE', 'bbhd_flexbox_container') ;
defined( 'BESTBUG_HB_LOGO_SHORTCODE' ) or define('BESTBUG_HB_LOGO_SHORTCODE', 'bbhd_logo') ;
defined( 'BESTBUG_HB_INSTAGRAM_SHORTCODE' ) or define('BESTBUG_HB_INSTAGRAM_SHORTCODE', 'bbhd_instagram') ;
defined( 'BESTBUG_HB_MINICART_SHORTCODE' ) or define('BESTBUG_HB_MINICART_SHORTCODE', 'bbhd_minicart') ;
defined( 'BESTBUG_HB_SEARCH_SHORTCODE' ) or define('BESTBUG_HB_SEARCH_SHORTCODE', 'bbhd_search') ;
defined( 'BESTBUG_HB_MENU_SHORTCODE' ) or define('BESTBUG_HB_MENU_SHORTCODE', 'bbhd_menus') ;
defined( 'BESTBUG_HB_MENUCANVAS_SHORTCODE' ) or define('BESTBUG_HB_MENUCANVAS_SHORTCODE', 'bbhd_menucanvas') ;
defined( 'BESTBUG_HB_MENUMOBILE_SHORTCODE' ) or define('BESTBUG_HB_MENUMOBILE_SHORTCODE', 'bbhd_menu_mobile') ;
defined( 'BESTBUG_HB_MENUVERTICAL_SHORTCODE' ) or define('BESTBUG_HB_MENUVERTICAL_SHORTCODE', 'bbhd_menu_horizontal') ;
defined( 'BESTBUG_HB_CONTACT_SHORTCODE' ) or define('BESTBUG_HB_CONTACT_SHORTCODE', 'bbhd_contact') ;
// POSTTYPES
defined( 'BESTBUG_HB_HEADER_POSTTYPE' ) or define('BESTBUG_HB_HEADER_POSTTYPE', 'bbhd_content') ;
defined( 'BESTBUG_HB_MEGAMENU_POSTTYPE' ) or define('BESTBUG_HB_MEGAMENU_POSTTYPE', 'bbhd_megamenu') ;

// METABOXES
defined( 'BESTBUG_HB_METABOX_HEADER' ) or define('BESTBUG_HB_METABOX_HEADER', '_bb_header') ;
defined( 'BESTBUG_HB_METABOX_MAX_WIDTH' ) or define('BESTBUG_HB_METABOX_MAX_WIDTH', '_bb_header_max_width') ;
defined( 'BESTBUG_HB_METABOX_BACKGROUND' ) or define('BESTBUG_HB_METABOX_BACKGROUND', '_bb_header_bg_defaults') ;

// SLUGS
defined( 'BESTBUG_HB_PAGESLUG' ) or define('BESTBUG_HB_PAGESLUG', 'bbhd_settings') ;

if ( ! class_exists( 'BESTBUG_HB_CLASS' ) ) {
	/**
	 * BESTBUG_HB_CLASS Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_CLASS {
		
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
			include_once 'bestbugcore/classes/about.bestbug.php';
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
			$header_id = apply_filters('bbhb_id_header', 0);
			if($header_id <= 0) {
				return;
			}
			$wp_admin_bar->add_node(array(
				'id' => 'bb-edit-header',
				'title' => 'Edit Header',
				'href' =>  get_site_url().'/wp-admin/post.php?post='.$header_id.'&action=edit',
				'meta' => array('class' => 'bbhb_edit'),
			));
			$wp_admin_bar->remove_node('admin');
		}
		public function about_menu($sub_menu) {
			$sub_menu[] = array(
				'parent_slug' => 'edit.php?post_type=' . BESTBUG_HB_HEADER_POSTTYPE,
				'slug' => 'hb_wpbb_about',
			);
			return $sub_menu;
		}

		public function adminEnqueueScripts() {
			BESTBUG_CORE_CLASS::adminEnqueueScripts();
			wp_enqueue_style( 'font-awesome', BESTBUG_HB_URL . '/assets/libs/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_script( 'bbhb-admin', BESTBUG_HB_URL . '/assets/admin/js/bbhb-admin.js', array( 'jquery' ), BBHEADER_DESIGNER_VERSION, true );
			wp_enqueue_style( 'bbhb', BESTBUG_HB_URL . '/assets/admin/css/admin.css' );
		}

		public function enqueueScripts() {
			BESTBUG_CORE_CLASS::enqueueScripts();
			
			wp_enqueue_style( 'font-awesome', BESTBUG_HB_URL . '/assets/libs/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_style( 'bbhb', BESTBUG_HB_URL . '/assets/css/bbhb.css', array(), BBHEADER_DESIGNER_VERSION );
			
			wp_enqueue_script( 'sticky', BESTBUG_HB_URL . 'assets/libs/jquery.sticky.js', array( 'jquery' ), '1.0.4', true );
			wp_enqueue_script( 'bbhd', BESTBUG_HB_URL . 'assets/js/bbhd.js', array( 'jquery' ), BBHEADER_DESIGNER_VERSION, true );

			$header_name = '';
			
			if( isset( $_REQUEST[BESTBUG_HB_HEADER_POSTTYPE] ) && !empty($_REQUEST[BESTBUG_HB_HEADER_POSTTYPE]) ) {
				$header_name = esc_attr($_REQUEST[BESTBUG_HB_HEADER_POSTTYPE]);
			}
			
			if(empty($header_name) && function_exists('eval')) {
				$conditions = bb_option(BESTBUG_HB_PREFIX . 'conditions');
				if(is_array($conditions)) {
					foreach ($conditions as $key => $condition) {
						if(eval("if (".$condition['value'].") {return true;} else {return false;}")) {
							$header_name = $condition['value2'];
						}
					}
				}
			}
			
			if(empty($header_name)) {
				$post_types = bb_option(BESTBUG_HB_PREFIX . 'use_metabox');
				foreach ($post_types as $key => $value) {
					if($value != 1){
						unset($post_types[$key]);
					}
				}

				if(is_singular() && array_key_exists( get_post_type(), $post_types ) ) {
					$metadata = apply_filters( 'bbhb_get_header_metadata', get_post_meta( get_the_ID(), BESTBUG_HB_METABOX_HEADER, true ) );
					if($metadata) {
						$header_name = $metadata;
					}
				}
			}
			
			if(empty($header_name)) {
				$header_name = bb_option(BESTBUG_HB_PREFIX . 'header');
			}

			if(!$header_name) {
				return;
			}

			$header = get_page_by_path( $header_name, OBJECT, BESTBUG_HB_HEADER_POSTTYPE );

			if(!$header) {
				return;
			}

			$custom_css = get_post_meta( $header->ID , '_wpb_shortcodes_custom_css', true );

			$selector = '#bb-header-inside-' . $header_name;
			$maxWidth = get_post_meta( $header->ID , BESTBUG_HB_METABOX_MAX_WIDTH, true );

			if(!$maxWidth) {
				$selector = '.bb-header-inside';
				$maxWidth = bb_option( BESTBUG_HB_PREFIX . 'max_width' );
			}
			if($maxWidth) {
				if(is_numeric($maxWidth)) {
					if($maxWidth <= 100) {
						$maxWidth .= '%';
					} else {
						$maxWidth .= 'px';
					}
					$custom_css .= $selector . ' { max-width: '.$maxWidth.'; }';

				} else {
					$custom_css .= $selector . ' { max-width: '.$maxWidth.'; }';
				}
			}

			wp_add_inline_style( 'bbhb', $custom_css );
		}
		
		public function loadTextDomain() {
			load_plugin_textdomain( BESTBUG_HB_TEXTDOMAIN, false, BESTBUG_HB_PATH . '/languages/' );
		}
		
		public function add_action_links ( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'admin.php?page=' . BESTBUG_HB_PAGESLUG ) . '">Settings</a>',
			);
			return array_merge( $mylinks, $links );
		}
		
		public function support_ultimate_addons() {
			if(defined('ULTIMATE_VERSION')) {
				wp_enqueue_style('ultimate-style-min');
			}
		}
	}
	new BESTBUG_HB_CLASS();
}
