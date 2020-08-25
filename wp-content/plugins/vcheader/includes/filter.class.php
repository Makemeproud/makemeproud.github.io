<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_FILTER' ) ) {
	/**
	 * BESTBUG_HB_FILTER Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_FILTER {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ));
			add_filter('single_template', array($this, 'load_template'));
		}

		public function init() {
			add_action( 'bbhb_header', array( $this, 'bbhb_header' ));
			if(bb_option(BESTBUG_HB_PREFIX . 'auto_show') == 'yes') {
				add_action( 'wp_footer', array( $this, 'bbhb_header' ));
			}

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
		
		}

		public function enqueueScripts() {
		
        }
		
		public function bbhb_header() {

			if (is_singular(BESTBUG_HB_HEADER_POSTTYPE) || is_singular(BESTBUG_HB_MEGAMENU_POSTTYPE) || is_singular('bbfb_content')) {
				return;
			}
			
			$header_name = '';

			if (bb_option(BESTBUG_HB_PREFIX . 'display_by_hsettings') != '' && bb_option(BESTBUG_HB_PREFIX . 'display_by_hsettings') == 'yes') {
				$header_name = self::get_header_by_own_settings();
			} else {
				$header_name = self::get_header_by_global_settings();
			}

			if (!$header_name) {
				return;
			}

			$header = get_page_by_path( $header_name, OBJECT, BESTBUG_HB_HEADER_POSTTYPE );

			if(!$header) {
				return;
			}

			global $bb_header_ID;
			$bb_header_ID = $header->ID;
			add_filter('bbhb_id_header', function(){
				global $bb_header_ID;
				return $bb_header_ID;
			});

			$class_array = array('bb-header-container');
			if(bb_option(BESTBUG_HB_PREFIX . 'auto_show') == 'yes') {
				array_push($class_array, 'bb-auto-add-header');
			}

			if (function_exists('icl_object_id')) {
				$post_id = icl_object_id($header->ID, BESTBUG_HB_HEADER_POSTTYPE, true);
				if($post_id) {
					$tmp = new WP_Query(array('post_type' => BESTBUG_HB_HEADER_POSTTYPE, 'p' => $post_id));
					if (isset($tmp->posts[0])) {
						$header = $tmp->posts[0];
					}
				}
			}

			$class_string = implode(' ', $class_array);
			
			?>
			<header id="bb-header-container-<?php echo esc_attr($header_name) ?>" class="<?php echo esc_attr($class_string) ?>">
				<div id="bb-header-inside-<?php echo esc_attr($header_name) ?>" class="bb-header-inside">
					<?php echo do_shortcode( $header->post_content ); ?>
				</div>
			</header>
			<?php
		}
		
		function load_template($template) {
			global $post;

			if ($post->post_type == BESTBUG_HB_HEADER_POSTTYPE && $template !== locate_template(array("single-". BESTBUG_HB_HEADER_POSTTYPE .".php"))){
				/* This is a "BESTBUG_HB_HEADER_POSTTYPE" post 
				* AND a 'single BESTBUG_HB_HEADER_POSTTYPE template' is not found on 
				* theme or child theme directories, so load it 
				* from our plugin directory
				*/
				return plugin_dir_path(__FILE__) . "single-bbhd_content.php";
			}

			if ($post->post_type == BESTBUG_HB_MEGAMENU_POSTTYPE && $template !== locate_template(array("single-". BESTBUG_HB_MEGAMENU_POSTTYPE .".php"))){
				/* This is a "BESTBUG_HB_MEGAMENU_POSTTYPE" post 
				* AND a 'single BESTBUG_HB_MEGAMENU_POSTTYPE template' is not found on 
				* theme or child theme directories, so load it 
				* from our plugin directory
				*/
				return plugin_dir_path(__FILE__) . "single-". BESTBUG_HB_MEGAMENU_POSTTYPE .".php";
			}

			return $template;
		}

		public static function get_header_by_global_settings($header_name = ''){

			if( isset( $_REQUEST[BESTBUG_HB_HEADER_POSTTYPE] ) && !empty($_REQUEST[BESTBUG_HB_HEADER_POSTTYPE]) ) {
				$header_name = esc_attr($_REQUEST[BESTBUG_HB_HEADER_POSTTYPE]);
				if (is_numeric($header_name)) {
					$header_name = get_post_field('post_name', $header_name);
				}
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

			return $header_name;
		}

		public static function get_header_by_own_settings(){
			$all_headers = get_posts(array(
				'post_type' => BESTBUG_HB_HEADER_POSTTYPE,
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
			));

			if(!empty($all_headers)) {
				foreach ($all_headers  as $key => $post) {
					$_bbhd_singular = get_post_meta( $post->ID, '_bbhd_singular', true);
					$_bbhd_singular_only = (array) get_post_meta( $post->ID, '_bbhd_singular_only', true);
					$_bbhd_pages = get_post_meta( $post->ID, '_bbhd_pages', true);
					$_bbhd_pages_only = (array) get_post_meta( $post->ID, '_bbhd_pages_only', true);
					$_bbhd_posts = get_post_meta( $post->ID, '_bbhd_posts', true );
					$_bbhd_posts_only = (array) get_post_meta( $post->ID, '_bbhd_posts_only', true);
					$_bbhd_taxs = get_post_meta( $post->ID, '_bbhd_taxs', true);
					$_bbhd_taxs_only = (array) get_post_meta($post->ID, '_bbhd_taxs_only', true);
					$_bbhd_others = get_post_meta( $post->ID, '_bbhd_others', true);
					$_bbhd_others_only = (array) get_post_meta( $post->ID, '_bbhd_others_only', true);
					$_bbhd_custom_conditions = get_post_meta( $post->ID, '_bbhd_custom_conditions', true);

					if($_bbhd_singular == 'all' && is_singular() ) {
						return $post->post_name;
					} else if($_bbhd_singular == 'only') {
						if(in_array('page', $_bbhd_singular_only)) {
							if($_bbhd_pages == '') {
								return $post->post_name;
							} else if($_bbhd_pages == 'only' && is_page($_bbhd_pages_only)) {
								return $post->post_name;
							}
						}
						if(is_single()) {
							if(in_array('post', $_bbhd_singular_only)) {
								if($_bbhd_posts == '') {
									return $post->post_name;
								} else if($_bbhd_posts == 'only' && is_singular($_bbhd_posts_only)) {
									return $post->post_name;
								}
							}
						}
						if(in_array('attachment', $_bbhd_singular_only) && is_attachment()) {
							return $post->post_name;
						}
						foreach ($_bbhd_singular_only as $key => $value) {
							if($value == 'post' || $value == 'page' || $value == 'attachment') {
								unset($_bbhd_singular_only[$key]);
							}
						}

						if(!empty($_bbhd_singular_only) && is_singular($_bbhd_singular_only)) {
							return $post->post_name;
						}
					}

					if($_bbhd_taxs == 'all' && (is_tax() || is_category() || is_tag()) ) {
						return $post->post_name;
					} else if ($_bbhd_taxs == 'only') {
						if(in_array('category', $_bbhd_taxs_only) && is_category()) {
							return $post->post_name;
						}
						if(in_array('tag', $_bbhd_taxs_only) && is_tag()) {
							return $post->post_name;
						}
						if(is_tax($_bbhd_taxs_only)) {
							return $post->post_name;
						}
					}

					if($_bbhd_others == 'all' && (is_home() || is_search() || is_404() || is_date() || is_author() || is_front_page() ) ) {
						return $post->post_name;
					} else if ($_bbhd_others == 'only') {
						if(in_array('front_page', $_bbhd_others_only) && is_front_page()) {
							return $post->post_name;
						}
						if(in_array('blog', $_bbhd_others_only) && is_home()) {
							return $post->post_name;
						}
						if(in_array('search', $_bbhd_others_only) && is_search()) {
							return $post->post_name;
						}
						if(in_array('404', $_bbhd_others_only) && is_404()) {
							return $post->post_name;
						}
						if(in_array('date', $_bbhd_others_only) && is_date()) {
							return $post->post_name;
						}
						if(in_array('author', $_bbhd_others_only) && is_author()) {
							return $post->post_name;
						}
					}
					if(!empty($_bbhd_custom_conditions)) {
						if(eval("if (". $_bbhd_custom_conditions .") {return true;} else {return false;}")) {
							return $post->post_name;
						}
					}
					
				} // end foreach
			}
			return;
		}
        
    }
	
	new BESTBUG_HB_FILTER();
}

