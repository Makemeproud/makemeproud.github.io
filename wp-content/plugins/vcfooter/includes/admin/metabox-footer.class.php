<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_FOOTER_METABOX' ) ) {
	/**
	 * BESTBUG_FB_FOOTER_METABOX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_FOOTER_METABOX {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array($this, 'bb_footer_builder_content_box'));
			add_action( 'save_post', array($this, 'bb_footer_builder_content_metabox_save') );
			$this->init();
		}

		public function init() {

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
			BESTBUG_CORE_CLASS::adminEnqueueScripts();
			wp_enqueue_script('chosen', BESTBUG_CORE_URL . '/assets/admin/libs/chosen/chosen.jquery.min.js', array('jquery'), BESTBUG_CORE_VERSION, true);
			wp_enqueue_style('chosen', BESTBUG_CORE_URL . '/assets/admin/libs/chosen/chosen.css');
		}

		public function enqueueScripts() {
		
        }
		
		public function bb_footer_builder_content_box() {
			add_meta_box( 'bb_footer_builder_content', 'Footer Settings', array($this, 'bb_footer_builder_content_meta'), BESTBUG_FB_FOOTER_POSTTYPE );

			if(bb_option(BESTBUG_FB_PREFIX .'display_by_fsettings') != '' && bb_option(BESTBUG_FB_PREFIX .'display_by_fsettings') == 'yes') {
				add_meta_box('bb_footer_builder_conditions', 'Conditions to display this Footer', array($this, 'fb_conditions_to_display'), BESTBUG_FB_FOOTER_POSTTYPE);
			}
		}
		
		public function bb_footer_builder_content_meta( $post )
		{
			$bb_footer_max_width = get_post_meta( $post->ID, '_bb_footer_max_width', true );
			$bb_footer_reveal_footer = get_post_meta( $post->ID, '_bb_footer_reveal_footer', true );
			wp_nonce_field( 'bb_footer_verify', 'bb_footer_nonce' );
		?>
		<table class="widefat">
			<tr>
				<td width="100px"><label class="bbfb-metabox-label" for="_bb_footer_max_width"><?php esc_html_e('Max width', 'bestbug') ?></label></td>
				<td>
					<input name="_bb_footer_max_width" id="_bb_footer_max_width" value="<?php echo esc_html($bb_footer_max_width) ?>" />
				</td>
			</tr>
			<tr>
				<td><label class="bbfb-metabox-label" for="_bb_footer_reveal_footer"><?php esc_html_e('Reveal Footer?', 'bestbug') ?></label></td>
				<td>
					<select name="_bb_footer_reveal_footer" id="_bb_footer_reveal_footer">
						<option value=""><?php esc_html_e('Default', 'bestbug') ?></option>
						<option value="no" <?php selected($bb_footer_reveal_footer, 'no'); ?>><?php esc_html_e('No', 'bestbug') ?></option>
						<option value="yes" <?php selected($bb_footer_reveal_footer,'yes'); ?>><?php esc_html_e('Yes', 'bestbug') ?></option>
					</select>
				</td>
			</tr>
		</table>
		<?php
		}

		public function fb_conditions_to_display( $post )
		{
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
		?>
		<table class="widefat bb-table-metabox">
			<tr>
				<td width="150px"><label class="bbfb-metabox-label" for="_bbfb_singular"><?php esc_html_e('Singular', 'bestbug') ?></label></td>
				<td>
					<select name="_bbfb_singular" id="_bbfb_singular" class="bb-condition-control" data-ref=".bb-only-singular" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbfb_singular, 'all'); ?>><?php esc_html_e('All Singular', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbfb_singular, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					<div class="bb-hidden bb-only-singular">
						<br/>
						<?php 
							$all_post_types = get_post_types(array('public' => true), 'objects'); 
							if(isset($all_post_types['bbfb_content'])) {
								unset($all_post_types['bbfb_content']);
							}
							if(isset($all_post_types['bbhd_content'])) {
								unset($all_post_types['bbhd_content']);
							}
							if(isset($all_post_types['wphb_megamenu'])) {
								unset($all_post_types['wphb_megamenu']);
							}
						?>
						<select name="_bbfb_singular_only[]" id="_bbfb_singular_only" class="bb-chosen-select bb-condition-control2" multiple="multiple" data-ref-prefix=".bb-condition-box">
							<?php foreach ($all_post_types as $key => $post_type) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbfb_singular_only)) echo 'selected'; ?>><?php echo esc_html($post_type->label) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bb-hidden bb-hidden2 bb-condition-box bb-condition-box-page bb-only-singular">
				<td><label class="bbfb-metabox-label" for="_bbfb_pages"><?php esc_html_e('Singular for Pages', 'bestbug') ?></label></td>
				<td>
					<select name="_bbfb_pages" id="_bbfb_pages" class="bb-condition-control" data-ref=".bb-only-pages" data-val="only">
						<option value="" <?php selected($_bbfb_pages, 'all'); ?>><?php esc_html_e('All pages', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbfb_pages, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					<div class="bb-hidden bb-only-pages">
						<br/>
						<?php 
							$all_pages = get_pages(array('post_status' => 'publish'));
						?>
						<select name="_bbfb_pages_only[]" multiple="multiple" class="bb-chosen-select" id="_bbfb_pages_only">
							<?php foreach ($all_pages as $key => $page) { ?>
								<option value="<?php echo esc_attr($page->ID) ?>" <?php if (in_array($page->ID, $_bbfb_pages_only)) echo 'selected'; ?> ><?php echo esc_html($page->post_title) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bb-hidden bb-hidden2 bb-condition-box bb-condition-box-post bb-only-singular">
				<td><label class="bbfb-metabox-label" for="_bbfb_posts"><?php esc_html_e('Singular for Posts', 'bestbug') ?></label></td>
				<td>
					<select name="_bbfb_posts" id="_bbfb_posts" class="bb-condition-control" data-ref=".bb-only-posts" data-val="only">
						<option value="" <?php selected($_bbfb_posts, 'all'); ?>><?php esc_html_e('All posts', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbfb_posts, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					<div class="bb-hidden bb-only-posts">
						<br/>
						<?php 
							$all_posts = get_posts(array('post_status' => 'publish'));
						?>
						<select name="_bbfb_posts_only[]" multiple="multiple" class="bb-chosen-select" id="_bbfb_posts_only">
							<?php foreach ($all_posts as $key => $post) { ?>
								<option value="<?php echo esc_attr($post->ID) ?>" <?php if (in_array($post->ID, $_bbfb_posts_only)) echo 'selected'; ?>><?php echo esc_html($post->post_title) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bbfb-bg-grey">
				<td><label class="bbfb-metabox-label" for="_bbfb_taxs"><?php esc_html_e('Taxonomies', 'bestbug') ?></label></td>
				<td>
					<select name="_bbfb_taxs" id="_bbfb_taxs" class="bb-condition-control" data-ref=".bb-only-tax" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbfb_taxs, 'all'); ?>><?php esc_html_e('All taxonomies', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbfb_taxs, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					
					<div class="bb-hidden bb-only-tax">
						<br/>
						<?php $all_taxs = get_taxonomies(array('public' => true), 'objects'); ?>
						<select name="_bbfb_taxs_only[]" id="_bbfb_taxs_only" class="bb-chosen-select" multiple="multiple">
							<?php foreach ($all_taxs as $key => $taxonomy) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbfb_taxs_only)) echo 'selected'; ?>><?php echo esc_html($taxonomy->labels->name) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td><label class="bbfb-metabox-label" for="_bbfb_others"><?php esc_html_e('Others', 'bestbug') ?></label></td>
				<td>
					<select name="_bbfb_others" id="_bbfb_others" class="bb-condition-control" data-ref=".bb-only-other" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbfb_others, 'all'); ?>><?php esc_html_e('All', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbfb_others, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>

					<div class="bb-hidden bb-only-other">
						<br/>
						<?php $others = array(
							'front_page' => esc_html__('Front Page', 'bestbug'),
							'blog' => esc_html__('Blog (Posts Page)', 'bestbug'),
							'search' => esc_html__('Search', 'bestbug'),
							'404' => esc_html__('404 Page', 'bestbug'),
							'date' => esc_html__('Date', 'bestbug'),
							'author' => esc_html__('Author', 'bestbug'),
						); ?>
						<select name="_bbfb_others_only[]" id="_bbfb_others_only" class="bb-chosen-select" multiple="multiple">
							<?php foreach ($others as $key => $other) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbfb_others_only)) echo 'selected'; ?>><?php echo esc_html($other) ?></option>
							<?php 
							} ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bbfb-bg-grey">
				<td><label class="bbfb-metabox-label" for="_bbfb_custom_conditions"><?php esc_html_e('PHP Custom Conditions', 'bestbug') ?></label></td>
				<td>
					<input class="bb-metabox-control" type="text" name="_bbfb_custom_conditions" value="<?php echo esc_attr($_bbfb_custom_conditions) ?>">
					<p class="bb-metabox-desc">
						<?php echo wp_kses(__('Conditions like <b>is_single()</b>  or  <b>is_single() && is_home()</b> you can read about condition tags in WordPress in <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">here</a> <br/>(Just for Developers)', "bestbug"), array('br' => array(), 'b' => array(), 'a' => array('href' => array()))); ?>
					</p>
				</td>
			</tr>
		</table>
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

			if(isset( $_POST['_bb_footer_max_width'] )) {
				update_post_meta( $post_id, '_bb_footer_max_width', sanitize_text_field($_POST['_bb_footer_max_width']) );
			}
			if(isset( $_POST['_bb_footer_reveal_footer'] )) {
				update_post_meta( $post_id, '_bb_footer_reveal_footer', sanitize_text_field($_POST['_bb_footer_reveal_footer']) );
			}
			if (isset($_POST['_bbfb_singular'])) {
				update_post_meta($post_id, '_bbfb_singular', sanitize_text_field($_POST['_bbfb_singular']));
			}
			if (isset($_POST['_bbfb_singular_only'])) {
				update_post_meta($post_id, '_bbfb_singular_only', ($_POST['_bbfb_singular_only']));
			}
			if (isset($_POST['_bbfb_pages'])) {
				update_post_meta($post_id, '_bbfb_pages', sanitize_text_field($_POST['_bbfb_pages']));
			}
			if (isset($_POST['_bbfb_pages_only'])) {
				update_post_meta($post_id, '_bbfb_pages_only', ($_POST['_bbfb_pages_only']));
			}
			if (isset($_POST['_bbfb_posts'])) {
				update_post_meta($post_id, '_bbfb_posts', sanitize_text_field($_POST['_bbfb_posts']));
			}
			if (isset($_POST['_bbfb_posts_only'])) {
				update_post_meta($post_id, '_bbfb_posts_only', ($_POST['_bbfb_posts_only']));
			}
			if (isset($_POST['_bbfb_taxs'])) {
				update_post_meta($post_id, '_bbfb_taxs', sanitize_text_field($_POST['_bbfb_taxs']));
			}
			if (isset($_POST['_bbfb_taxs_only'])) {
				update_post_meta($post_id, '_bbfb_taxs_only', ($_POST['_bbfb_taxs_only']));
			}
			if (isset($_POST['_bbfb_others'])) {
				update_post_meta($post_id, '_bbfb_others', sanitize_text_field($_POST['_bbfb_others']));
			}
			if (isset($_POST['_bbfb_others_only'])) {
				update_post_meta($post_id, '_bbfb_others_only', ($_POST['_bbfb_others_only']));
			}
			if (isset($_POST['_bbfb_custom_conditions'])) {
				update_post_meta($post_id, '_bbfb_custom_conditions', ($_POST['_bbfb_custom_conditions']));
			}
		}
        
    }
	
	new BESTBUG_FB_FOOTER_METABOX();
}

