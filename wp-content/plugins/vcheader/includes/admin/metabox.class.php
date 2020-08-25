<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_METABOX' ) ) {
	/**
	 * BESTBUG_HB_METABOX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_METABOX {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			if(apply_filters( 'bbhb_show_header_metabox', true )) {
				add_action( 'add_meta_boxes', array($this, 'bb_header_builder_content_box') );
				add_action( 'save_post', array($this, 'bb_header_builder_content_metabox_save') );
			}
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
		
		public function bb_header_builder_content_box() {
			$post_types = bb_option(BESTBUG_HB_PREFIX . 'use_metabox');
			foreach ($post_types as $key => $value) {
				if($value != 1){
					unset($post_types[$key]);
				}
			}
			add_meta_box( 'bb_header_builder', 'Choose Header', array($this, 'bb_header_builder_meta'), array_keys($post_types) );
        }
		
		public function bb_header_builder_meta( $post )
		{
			$bb_header = get_post_meta( $post->ID, '_bb_header', true );
			wp_nonce_field( 'bb_header_verify', 'bb_header_nonce' );

			$allHeaders = array(
				'' => 'Default Header',
			);
			$args = array(
				'posts_per_page'      => -1,
				'post_type' => BESTBUG_HB_HEADER_POSTTYPE,
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC',
			);
			$query = new WP_Query( $args );

			if($query->post_count > 0) {
				foreach ($query->posts as $key => $post) {
					$allHeaders[ $post->post_name ] = $post->post_title;
				}
			}
		?>

		<p>
			<select name="_bb_header" id="_bb_header">
				<?php  foreach ($allHeaders as $header_key => $header_title) { ?>
					<option value="<?php echo esc_html($header_key) ?>" <?php echo ($bb_header == $header_key)?'selected="selected"' : ''; ?>><?php echo esc_html($header_title) ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
		}
		
		public function bb_header_builder_content_metabox_save( $post_id )
		{
			if(!isset($_POST['bb_header_nonce'])) {
				return;
			}
			$bb_header_nonce = $_POST['bb_header_nonce'];
			if( !wp_verify_nonce( $bb_header_nonce, 'bb_header_verify' ) ) {
				return;
			}

			if(isset( $_POST['_bb_header'] )) {
				$bb_header = sanitize_text_field( $_POST['_bb_header'] );
				update_post_meta( $post_id, '_bb_header', $bb_header );
			}
		}
        
    }
	
	new BESTBUG_HB_METABOX();
}

