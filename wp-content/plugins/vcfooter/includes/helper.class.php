<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_HELPER' ) ) {
	/**
	 * BESTBUG_FB_HELPER Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_HELPER {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			//$this->init();
		}

		public function init() {

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
		
		}

		public function enqueueScripts() {
		
        }
        
        public static function ml_get_the_content($post_id) {
			if(function_exists('icl_object_id')) {
				$post = icl_object_id( $post_id, BESTBUG_FB_FOOTER_POSTTYPE, true);
			} else {
				$post = get_post($post_id);
			}
			return $post;
        }
        
    }
	
	new BESTBUG_FB_HELPER();
}

