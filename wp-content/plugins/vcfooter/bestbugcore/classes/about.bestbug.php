<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
if (!class_exists('About_Bestbug')) {

	/**
	 * About_Bestbug Class
	 *
	 * @since	1.0 
	 */

	class About_Bestbug{
		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct()
		{
            $this->init();
        }
        public function init() {
			add_action('admin_menu',array($this,'wpbb_add_submenu_page_about'), 11 );
			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
        }
		public function adminEnqueueScripts(){
			wp_enqueue_script('script', BESTBUG_CORE_URL . '/assets/js/script.js', array(), null, true );
		}
        public function wpbb_add_submenu_page_about() {
			$this->options = apply_filters( 'bb_about_submenu_page', array() );
            if( !isset($this->options)) {
    			return;
			}
            if( !empty($this->options) ) {
				foreach( $this->options as $submenu){
					add_submenu_page(
						$submenu['parent_slug'],
						esc_html__('About' , 'bestbug'),
						esc_html__('About' , 'bestbug'),
						'manage_options',
						$submenu['slug'],
						array(&$this, 'plugin_about')
					);
				}
				
			}
        }
        public function plugin_about() {
			include 'templates/about.view.php';
        }
	}
	new About_Bestbug();
}