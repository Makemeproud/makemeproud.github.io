<?php
/* BESTBUG CORE */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'BESTBUG_CORE_VERSION' ) or define('BESTBUG_CORE_VERSION', '1.3.5') ;

defined( 'BESTBUG_CORE_URL' ) or define('BESTBUG_CORE_URL', plugins_url( '/', __FILE__ )) ;
defined( 'BESTBUG_CORE_PATH' ) or define('BESTBUG_CORE_PATH', basename( dirname( __FILE__ ))) ;
defined( 'BESTBUG_CORE_TEXTDOMAIN' ) or define('BESTBUG_CORE_TEXTDOMAIN', 'bestbug') ;

if ( ! class_exists( 'BestBug_Core_Class' ) ) {
	/**
	 * BestBug_Core_Class Class
	 *
	 * @since	1.0
	 */
	class BestBug_Core_Class {


		/**
		 * Constructor, core of BESTBUG
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
			add_action( 'admin_footer', array( $this, 'ajax_loading') );
			
			include_once 'classes/helper.class.php';

		}

		public static function adminEnqueueScripts() {
			wp_enqueue_style( 'bb-core', BESTBUG_CORE_URL . '/assets/admin/css/style.css', array(), BESTBUG_CORE_VERSION );
			wp_enqueue_script( 'bb-core-admin', BESTBUG_CORE_URL . '/assets/admin/js/script.js', array( 'jquery', 'wp-color-picker' ), BESTBUG_CORE_VERSION, true );
		}

		public static function enqueueScripts() {
			wp_enqueue_style( 'bb-css', BESTBUG_CORE_URL . '/assets/css/style.css', array(), BESTBUG_CORE_VERSION );
		}
		
		public static function support($lib = '', $options = ''){
			switch ($lib) {
				case 'vc-params':
					include_once 'extend/index.php';
					if($options !='' && is_array($options)) {
						foreach ($options as $key => $type) {
							include_once 'extend/vc-params/' . $type . '.class.php';
						}
					} else {
						include_once 'extend/vc-params/index.php';
					}
					break;
				case 'options':
					include_once 'classes/options.class.php';
					break;
				case 'posttypes':
					include_once 'classes/posttypes.class.php';
					break;
				case 'htmldom':
					include_once 'libs/simple_html_dom.php';
					break;
				case 'about-bb':
					include_once  'classes/about.bestbug.php';
					break;
				default:
					# code...
					break;
			}
		}

		public function loadTextDomain() {
			load_plugin_textdomain( BESTBUG_CORE_TEXTDOMAIN, false, BESTBUG_CORE_PATH . '/languages/' );
		}
		
		public function ajax_loading(){
			echo '<div class="bb-ajax-loading">
					<div class="bb-ajax-box">
						<div class="bb-spinner">
						  <div class="cube1"></div>
						  <div class="cube2"></div>
						</div>
					</div>
				</div>';
		}

	}
	new BestBug_Core_Class();
}
