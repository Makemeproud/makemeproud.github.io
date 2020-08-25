<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_METABOX' ) ) {
	/**
	 * BESTBUG_FB_METABOX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_METABOX {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			if(apply_filters( 'bbfb_show_footer_metabox', true )) {
				add_action( 'add_meta_boxes', array($this, 'bb_footer_builder_content_box') );
				add_action( 'save_post', array($this, 'bb_footer_builder_content_metabox_save') );
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
		
		public function bb_footer_builder_content_box() {
			$post_types = bb_option(BESTBUG_FB_PREFIX . 'use_metabox');
			foreach ($post_types as $key => $value) {
				if($value != 1){
					unset($post_types[$key]);
				}
			}
			add_meta_box( 'bb_footer_builder', 'Choose the Footer for this page', array($this, 'bb_footer_builder_meta'), array_keys($post_types) );
        }
		
		public function bb_footer_builder_meta( $post )
		{
			$bb_footer = get_post_meta( $post->ID, '_bb_footer', true );
			wp_nonce_field( 'bb_footer_verify', 'bb_footer_nonce' );

			$allFooters = array(
				'' => 'Default Footer',
			);
			$args = array(
				'posts_per_page'      => -1,
				'post_type' => 'bbfb_content',
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC',
			);
			$query = new WP_Query( $args );

			if($query->post_count > 0) {
				foreach ($query->posts as $key => $post) {
					$allFooters[ $post->post_name ] = $post->post_title;
				}
			}
		?>
		<table class="widefat">
			<tr>
				<td><label class="bbfb-metabox-label" for="_bb_footer_reveal_footer"><?php esc_html_e('Use footer', 'bestbug') ?></label></td>
				<td>
					<select name="_bb_footer" id="_bb_footer">
						<?php foreach ($allFooters as $footer_key => $footer_title) { ?>
							<option value="<?php echo esc_html($footer_key) ?>" <?php selected($bb_footer, $footer_key); ?>><?php echo esc_html($footer_title) ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table>

		<p>
			
		</p>

		<?php
		}
		
		public function bb_footer_builder_content_metabox_save( $post_id )
		{
			if(!isset($_POST['bb_footer_nonce'])) {
				return;
			}
			$bb_footer_nonce = $_POST['bb_footer_nonce'];
			if( !wp_verify_nonce( $bb_footer_nonce, 'bb_footer_verify' ) ) {
				return;
			}

			if(isset( $_POST['_bb_footer'] )) {
				$bb_footer = sanitize_text_field( $_POST['_bb_footer'] );
				update_post_meta( $post_id, '_bb_footer', $bb_footer );
			}
		}
        
    }
	
	new BESTBUG_FB_METABOX();
}

