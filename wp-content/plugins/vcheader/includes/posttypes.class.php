<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_POSTTYPES' ) ) {
	/**
	 * BESTBUG_HB_POSTTYPES Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_POSTTYPES {


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
				'name'               => _x( 'Mega Menus', 'Mega Menus', 'bestbug' ),
				'singular_name'      => _x( 'Mega Menu', 'Mega Menu', 'bestbug' ),
				'menu_name'          => __( 'Mega Menu', 'bestbug' ),
				'name_admin_bar'     => __( 'Mega Menu', 'bestbug' ),
				'parent_item_colon'  => __( 'Parent Menu:', 'bestbug' ),
				'all_items'          => __( 'All Mega Menus', 'bestbug' ),
				'add_new_item'       => __( 'Add New Mega Menu', 'bestbug' ),
				'add_new'            => __( 'Add New', 'bestbug' ),
				'new_item'           => __( 'New Mega Menu', 'bestbug' ),
				'edit_item'          => __( 'Edit Mega Menu', 'bestbug' ),
				'update_item'        => __( 'Update Mega Menu', 'bestbug' ),
				'view_item'          => __( 'View Mega Menu', 'bestbug' ),
				'search_items'       => __( 'Search Mega Menu', 'bestbug' ),
				'not_found'          => __( 'Not found', 'bestbug' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'bestbug' ),
			);
			$args   = array(
				'label'               => __( 'Mega Menu', 'lamblue' ),
				'description'         => __( 'Mega Menu', 'lamblue' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 13,
				'menu_icon'           => BESTBUG_HB_URL . 'assets/images/megamenu.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => true,
				'capability_type'     => 'page',
			);
			$posttypes[BESTBUG_HB_MEGAMENU_POSTTYPE] = $args;

			$labels = array(
				'name'               => _x( 'Header Contents', 'Header Contents', 'bestbug' ),
				'singular_name'      => _x( 'Header Content', 'Header Content', 'bestbug' ),
				'menu_name'          => __( 'Header Builder', 'bestbug' ),
				'name_admin_bar'     => __( 'Header Content', 'bestbug' ),
				'parent_item_colon'  => __( 'Parent Menu:', 'bestbug' ),
				'all_items'          => __( 'All Headers', 'bestbug' ),
				'add_new_item'       => __( 'Add New Header Content', 'bestbug' ),
				'add_new'            => __( 'Add New', 'bestbug' ),
				'new_item'           => __( 'New Header Content', 'bestbug' ),
				'edit_item'          => __( 'Edit Header Content', 'bestbug' ),
				'update_item'        => __( 'Update Header Content', 'bestbug' ),
				'view_item'          => __( 'View Header Content', 'bestbug' ),
				'search_items'       => __( 'Search Header Content', 'bestbug' ),
				'not_found'          => __( 'Not found', 'bestbug' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'bestbug' ),
			);
			$args   = array(
				'label'               => __( 'Header Content', 'lamblue' ),
				'description'         => __( 'Header Content', 'lamblue' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 13,
				'menu_icon'           => BESTBUG_HB_URL . 'assets/images/hw-mini-logo.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => true,
				'capability_type'     => 'page',
			);
			$posttypes[BESTBUG_HB_HEADER_POSTTYPE] = $args;
			
			return $posttypes;
		}
        
    }
	
	new BESTBUG_HB_POSTTYPES();
}

