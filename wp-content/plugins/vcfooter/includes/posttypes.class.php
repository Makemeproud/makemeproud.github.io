<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_POSTTYPES' ) ) {
	/**
	 * BESTBUG_FB_POSTTYPES Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_POSTTYPES {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			//$this->init();
			add_filter( 'bb_register_posttypes', array( $this, 'register_posttypes' ), 10, 1 );
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
        
		public function register_posttypes($posttypes) {

			if( empty($posttypes) ) {
				$posttypes = array();
			}

			$labels = array(
				'name'               => _x( 'Footer Contents', 'Footer Contents', 'bestbug' ),
				'singular_name'      => _x( 'Footer Content', 'Footer Content', 'bestbug' ),
				'menu_name'          => __( 'Footer Builder', 'bestbug' ),
				'name_admin_bar'     => __( 'Footer Content', 'bestbug' ),
				'parent_item_colon'  => __( 'Parent Menu:', 'bestbug' ),
				'all_items'          => __( 'All Footers', 'bestbug' ),
				'add_new_item'       => __( 'Add New Footer Content', 'bestbug' ),
				'add_new'            => __( 'Add New', 'bestbug' ),
				'new_item'           => __( 'New Footer Content', 'bestbug' ),
				'edit_item'          => __( 'Edit Footer Content', 'bestbug' ),
				'update_item'        => __( 'Update Footer Content', 'bestbug' ),
				'view_item'          => __( 'View Footer Content', 'bestbug' ),
				'search_items'       => __( 'Search Footer Content', 'bestbug' ),
				'not_found'          => __( 'Not found', 'bestbug' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'bestbug' ),
			);
			$args   = array(
				'label'               => __( 'Footer Content', 'lamblue' ),
				'description'         => __( 'Footer Content', 'lamblue' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', ),
				'capability_type' 	  => 'page',
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 13,
				'menu_icon' 		  => BESTBUG_FB_URL . 'assets/admin/images/fb-logo.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => true,
			);
			$posttypes[BESTBUG_FB_FOOTER_POSTTYPE] = $args;
			return $posttypes;
		}
        
    }
	
	new BESTBUG_FB_POSTTYPES();
}

