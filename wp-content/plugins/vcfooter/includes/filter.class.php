<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_FILTER' ) ) {
	/**
	 * BESTBUG_FB_FILTER Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_FILTER {

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
			
			add_action( 'bbfb_footer', array( $this, 'bbfb_footer' ));
			if(bb_option(BESTBUG_FB_PREFIX . 'auto_show') == 'yes') {
				add_action( 'wp_footer', array( $this, 'bbfb_footer' ));
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
		
		public function bbfb_footer() {
			if (is_singular('bbhd_content') || is_singular('bbhd_megamenu') || is_singular(BESTBUG_FB_FOOTER_POSTTYPE)) {
				return;
			}
			if(bb_option(BESTBUG_FB_PREFIX . 'dark_theme') != '' && bb_option(BESTBUG_FB_PREFIX . 'dark_theme') == 'yes'){
				
			}
			$footer_name = '';

			if (bb_option(BESTBUG_FB_PREFIX . 'display_by_fsettings') != '' && bb_option(BESTBUG_FB_PREFIX . 'display_by_fsettings') == 'yes') {
				$footer_name = self::get_footer_by_own_settings();
			} else {
				$footer_name = self::get_footer_by_global_settings();
			}

			if(!$footer_name) {
				return;
			}

			$footer = get_page_by_path( $footer_name, OBJECT, BESTBUG_FB_FOOTER_POSTTYPE );

			if(!$footer) {
				return;
			}

			global $bb_footer_ID;
			$bb_footer_ID = $footer->ID;
			add_filter('bbfb_id_footer', function(){
				global $bb_footer_ID;
				return $bb_footer_ID;
			});

			$class_array = array('bb-footer-container');
			
			if(function_exists('icl_object_id')) {
				$post_id = BESTBUG_FB_HELPER::ml_get_the_content($footer->ID);
				$footer_tmp = new WP_Query( array( 'post_type' => BESTBUG_FB_FOOTER_POSTTYPE, 'p' => $post_id ) );
				if(isset($footer_tmp->posts[0])) {
					$footer = $footer_tmp->posts[0];
				}
				if(get_post_meta( $post_id, '_bb_footer_reveal_footer', true ) == 'yes') {
					array_push($class_array, 'bb-footer-reveal');
				}
			} else {
				if(get_post_meta( $footer->ID, '_bb_footer_reveal_footer', true ) == 'yes') {
					array_push($class_array, 'bb-footer-reveal');
				}
			}
			
			$class_string = implode(' ', $class_array);

			?>
				<footer id="bb-footer-container-<?php echo esc_attr($footer_name) ?>" class="<?php echo esc_attr($class_string) ?>">
					<div id="bb-footer-inside-<?php echo esc_attr($footer_name) ?>" class="bb-footer-inside">
						<?php echo do_shortcode( $footer->post_content ); ?>
					</div>
				</footer>
			<?php
		}
		
		public static function get_footer_by_global_settings($footer_name = ''){
			if(empty($footer_name)) {
				if( isset( $_REQUEST['bbfb_content'] ) && !empty($_REQUEST['bbfb_content']) ) {
					$footer_name = esc_attr($_REQUEST['bbfb_content']);
					if (is_numeric($footer_name)) {
						$footer_name = get_post_field('post_name', $footer_name);
					}
				}
			}
			
			if(empty($footer_name)) {
				$post_types = bb_option(BESTBUG_FB_PREFIX . 'use_metabox');
				foreach ($post_types as $key => $value) {
					if($value != 1){
						unset($post_types[$key]);
					}
				}

				if(is_singular() && array_key_exists( get_post_type(), $post_types ) ) {
					$metadata = apply_filters( 'bbfb_get_footer_metadata', get_post_meta( get_the_ID(), BESTBUG_FB_METABOX_FOOTER, true ) );
					if($metadata) {
						$footer_name = $metadata;
					}
				}
			}
			if(empty($footer_name) && function_exists('eval')) {
				$conditions = bb_option(BESTBUG_FB_PREFIX . 'conditions');
				if(is_array($conditions)) {
					foreach ($conditions as $key => $condition) {
						if(!empty($condition['value']) && eval("if (".$condition['value'].") {return true;} else {return false;}")) {
							$footer_name = $condition['value2'];
						}
					}
				}
			}
			
			if(empty($footer_name)) {
				$footer_name = bb_option(BESTBUG_FB_PREFIX . 'footer');
			}
			return $footer_name;
		}

		function load_template($template) {
			global $post;

			if ($post->post_type == BESTBUG_FB_FOOTER_POSTTYPE && $template !== locate_template(array("single-". BESTBUG_FB_FOOTER_POSTTYPE .".php"))){
				/* This is a "BESTBUG_HB_HEADER_POSTTYPE" post 
				* AND a 'single BESTBUG_HB_HEADER_POSTTYPE template' is not found on 
				* theme or child theme directories, so load it 
				* from our plugin directory
				*/
				return plugin_dir_path(__FILE__) . "single-". BESTBUG_FB_FOOTER_POSTTYPE .".php";
			}

			return $template;
		}

		public static function get_footer_by_own_settings(){
			$all_footers = get_posts(array(
				'post_type' => BESTBUG_FB_FOOTER_POSTTYPE,
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
			));

			if(!empty($all_footers)) {
				foreach ($all_footers  as $key => $post) {
					$_bbfb_singular = get_post_meta( $post->ID, '_bbfb_singular', true);
					$_bbfb_singular_only = (array) get_post_meta( $post->ID, '_bbfb_singular_only', true);
					$_bbfb_pages = get_post_meta( $post->ID, '_bbfb_pages', true);
					$_bbfb_pages_only = (array) get_post_meta( $post->ID, '_bbfb_pages_only', true);
					$_bbfb_posts = get_post_meta( $post->ID, '_bbfb_posts', true );
					$_bbfb_posts_only = (array) get_post_meta( $post->ID, '_bbfb_posts_only', true);
					$_bbfb_taxs = get_post_meta( $post->ID, '_bbfb_taxs', true);
					$_bbfb_taxs_only = (array) get_post_meta($post->ID, '_bbfb_taxs_only', true);
					$_bbfb_others = get_post_meta( $post->ID, '_bbfb_others', true);
					$_bbfb_others_only = (array) get_post_meta( $post->ID, '_bbfb_others_only', true);
					$_bbfb_custom_conditions = get_post_meta( $post->ID, '_bbfb_custom_conditions', true);

					if($_bbfb_singular == 'all' && is_singular() ) {
						return $post->post_name;
					} else if($_bbfb_singular == 'only') {
						if(in_array('page', $_bbfb_singular_only)) {
							if($_bbfb_pages == '') {
								return $post->post_name;
							} else if($_bbfb_pages == 'only' && is_page($_bbfb_pages_only)) {
								return $post->post_name;
							}
						}
						if(is_single()) {
							if(in_array('post', $_bbfb_singular_only)) {
								if($_bbfb_posts == '') {
									return $post->post_name;
								} else if($_bbfb_posts == 'only' && is_singular($_bbfb_posts_only)) {
									return $post->post_name;
								}
							}
						}
						if(in_array('attachment', $_bbfb_singular_only) && is_attachment()) {
							return $post->post_name;
						}
						foreach ($_bbfb_singular_only as $key => $value) {
							if($value == 'post' || $value == 'page' || $value == 'attachment') {
								unset($_bbfb_singular_only[$key]);
							}
						}

						if(!empty($_bbfb_singular_only) && is_singular($_bbfb_singular_only)) {
							return $post->post_name;
						}
					}

					if($_bbfb_taxs == 'all' && (is_tax() || is_category() || is_tag()) ) {
						return $post->post_name;
					} else if ($_bbfb_taxs == 'only') {
						if(in_array('category', $_bbfb_taxs_only) && is_category()) {
							return $post->post_name;
						}
						if(in_array('tag', $_bbfb_taxs_only) && is_tag()) {
							return $post->post_name;
						}
						if(is_tax($_bbfb_taxs_only)) {
							return $post->post_name;
						}
					}

					if($_bbfb_others == 'all' && (is_home() || is_search() || is_404() || is_date() || is_author() || is_front_page() ) ) {
						return $post->post_name;
					} else if ($_bbfb_others == 'only') {
						if(in_array('front_page', $_bbfb_others_only) && is_front_page()) {
							return $post->post_name;
						}
						if(in_array('blog', $_bbfb_others_only) && is_home()) {
							return $post->post_name;
						}
						if(in_array('search', $_bbfb_others_only) && is_search()) {
							return $post->post_name;
						}
						if(in_array('404', $_bbfb_others_only) && is_404()) {
							return $post->post_name;
						}
						if(in_array('date', $_bbfb_others_only) && is_date()) {
							return $post->post_name;
						}
						if(in_array('author', $_bbfb_others_only) && is_author()) {
							return $post->post_name;
						}
					}
					if(!empty($_bbfb_custom_conditions)) {
						if(eval("if (". $_bbfb_custom_conditions .") {return true;} else {return false;}")) {
							return $post->post_name;
						}
					}
					
				} // end foreach
			}
			return;
		}
        
    }
	
	new BESTBUG_FB_FILTER();
}

