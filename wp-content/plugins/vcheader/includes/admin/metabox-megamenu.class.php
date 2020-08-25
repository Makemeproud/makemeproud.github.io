<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_MEGAMENU_METABOX' ) ) {
	/**
	 * BESTBUG_HB_MEGAMENU_METABOX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_MEGAMENU_METABOX {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array($this, 'bbhb_megamenu_content_box') );
			add_action( 'save_post', array($this, 'bbhb_megamenu_content_metabox_save') );
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
		
		public function bbhb_megamenu_content_box() {
			add_meta_box( 'bbhb_megamenu', 'Megamenu Settings', array($this, 'bbhb_megamenu_content_meta'), BESTBUG_HB_MEGAMENU_POSTTYPE );
        }
		
		public function bbhb_megamenu_content_meta( $post )
		{

			$bb_megamenu_max_width = get_post_meta( $post->ID, '_bb_megamenu_max_width', true );
			wp_nonce_field( 'bb_megamenu_verify', 'bb_megamenu_nonce' );

		?>
		<table class="widefat">
			<tr>
				<td width="300px"><label class="bbhd-metabox-label" for="_bb_megamenu_max_width"><?php esc_html_e('Max width', 'bestbug') ?></label></td>
				<td>
					<input name="_bb_megamenu_max_width" id="_bb_megamenu_max_width" class="bb-metabox-control" value="<?php echo esc_attr($bb_megamenu_max_width) ?>" />
				</td>
			</tr>
		</table>
		<?php
		}
		
		public function bbhb_megamenu_content_metabox_save( $post_id )
		{
			if(!isset($_POST['bb_megamenu_nonce'])) {
				return;
			}
			$bb_megamenu_nonce = $_POST['bb_megamenu_nonce'];
			if( !wp_verify_nonce( $bb_megamenu_nonce, 'bb_megamenu_verify' ) ) {
				return;
			}

			if(isset( $_POST['_bb_megamenu_max_width'] )) {
				$bb_megamenu_max_width = sanitize_text_field( $_POST['_bb_megamenu_max_width'] );
				update_post_meta( $post_id, '_bb_megamenu_max_width', $bb_megamenu_max_width );
			}
		}
        
    }
	
	new BESTBUG_HB_MEGAMENU_METABOX();
}

