<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_HB_HEADER_METABOX' ) ) {
	/**
	 * BESTBUG_HB_HEADER_METABOX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_HB_HEADER_METABOX {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array($this, 'bb_header_builder_content_box') );
			add_action( 'save_post', array($this, 'bb_header_builder_content_metabox_save') );
			$this->init();
		}

		public function init()
		{

			if (is_admin()) {
				add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
			}
			add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

		}

		public function adminEnqueueScripts()
		{
			BESTBUG_CORE_CLASS::adminEnqueueScripts();
			wp_enqueue_script('chosen', BESTBUG_CORE_URL . '/assets/admin/libs/chosen/chosen.jquery.min.js', array('jquery'), BESTBUG_CORE_VERSION, true);
			wp_enqueue_style('chosen', BESTBUG_CORE_URL . '/assets/admin/libs/chosen/chosen.css');
		}

		public function enqueueScripts() {
		
        }
		
		public function bb_header_builder_content_box() {
			add_meta_box( 'bb_header_builder_content', 'Header Settings', array($this, 'bb_header_builder_content_meta'), BESTBUG_HB_HEADER_POSTTYPE );
			if (bb_option(BESTBUG_HB_PREFIX . 'display_by_hsettings') != '' && bb_option(BESTBUG_HB_PREFIX . 'display_by_hsettings') == 'yes') {
				add_meta_box('bb_header_builder_conditions', 'Conditions to display this Header', array($this, 'hd_conditions_to_display'), BESTBUG_HB_HEADER_POSTTYPE);
			}
        }
		
		public function bb_header_builder_content_meta( $post )
		{

			$bb_header_max_width = get_post_meta( $post->ID, '_bb_header_max_width', true );
			wp_nonce_field( 'bb_header_verify', 'bb_header_nonce' );

		?>
		<table class="widefat">
			<tr>
				<td width="150px"><label class="bbhd-metabox-label" for="_bb_header_max_width"><?php esc_html_e('Max width', 'bestbug') ?></label></td>
				<td>
					<input class="bb-metabox-control" name="_bb_header_max_width" id="_bb_header_max_width" value="<?php echo esc_attr($bb_header_max_width) ?>" />
				</td>
			</tr>
		</table>
		<?php
		}

		public function hd_conditions_to_display( $post )
		{
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
		?>
		<table class="widefat bb-table-metabox">
			<tr>
				<td width="150px"><label class="bbfb-metabox-label" for="_bbhd_singular"><?php esc_html_e('Singular', 'bestbug') ?></label></td>
				<td>
					<select name="_bbhd_singular" id="_bbhd_singular" class="bb-condition-control" data-ref=".bb-only-singular" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbhd_singular, 'all'); ?>><?php esc_html_e('All Singular', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbhd_singular, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
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
							if(isset($all_post_types['bbhd_megamenu'])) {
								unset($all_post_types['bbhd_megamenu']);
							}
						?>
						<select name="_bbhd_singular_only[]" id="_bbhd_singular_only" class="bb-chosen-select bb-condition-control2" multiple="multiple" data-ref-prefix=".bb-condition-box">
							<?php foreach ($all_post_types as $key => $post_type) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbhd_singular_only)) echo 'selected'; ?>><?php echo esc_html($post_type->label) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bb-hidden bb-hidden2 bb-condition-box bb-condition-box-page bb-only-singular">
				<td><label class="bbfb-metabox-label" for="_bbhd_pages"><?php esc_html_e('Singular for Pages', 'bestbug') ?></label></td>
				<td>
					<select name="_bbhd_pages" id="_bbhd_pages" class="bb-condition-control" data-ref=".bb-only-pages" data-val="only">
						<option value="" <?php selected($_bbhd_pages, 'all'); ?>><?php esc_html_e('All pages', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbhd_pages, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					<div class="bb-hidden bb-only-pages">
						<br/>
						<?php 
							$all_pages = get_pages(array('post_status' => 'publish'));
						?>
						<select name="_bbhd_pages_only[]" multiple="multiple" class="bb-chosen-select" id="_bbhd_pages_only">
							<?php foreach ($all_pages as $key => $page) { ?>
								<option value="<?php echo esc_attr($page->ID) ?>" <?php if (in_array($page->ID, $_bbhd_pages_only)) echo 'selected'; ?> ><?php echo esc_html($page->post_title) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bb-hidden bb-hidden2 bb-condition-box bb-condition-box-post bb-only-singular">
				<td><label class="bbfb-metabox-label" for="_bbhd_posts"><?php esc_html_e('Singular for Posts', 'bestbug') ?></label></td>
				<td>
					<select name="_bbhd_posts" id="_bbhd_posts" class="bb-condition-control" data-ref=".bb-only-posts" data-val="only">
						<option value="" <?php selected($_bbhd_posts, 'all'); ?>><?php esc_html_e('All posts', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbhd_posts, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					<div class="bb-hidden bb-only-posts">
						<br/>
						<?php 
							$all_posts = get_posts(array('post_status' => 'publish'));
						?>
						<select name="_bbhd_posts_only[]" multiple="multiple" class="bb-chosen-select" id="_bbhd_posts_only">
							<?php foreach ($all_posts as $key => $post) { ?>
								<option value="<?php echo esc_attr($post->ID) ?>" <?php if (in_array($post->ID, $_bbhd_posts_only)) echo 'selected'; ?>><?php echo esc_html($post->post_title) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bbfb-bg-grey">
				<td><label class="bbfb-metabox-label" for="_bbhd_taxs"><?php esc_html_e('Taxonomies', 'bestbug') ?></label></td>
				<td>
					<select name="_bbhd_taxs" id="_bbhd_taxs" class="bb-condition-control" data-ref=".bb-only-tax" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbhd_taxs, 'all'); ?>><?php esc_html_e('All taxonomies', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbhd_taxs, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
					</select>
					
					<div class="bb-hidden bb-only-tax">
						<br/>
						<?php $all_taxs = get_taxonomies(array('public' => true), 'objects'); ?>
						<select name="_bbhd_taxs_only[]" id="_bbhd_taxs_only" class="bb-chosen-select" multiple="multiple">
							<?php foreach ($all_taxs as $key => $taxonomy) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbhd_taxs_only)) echo 'selected'; ?>><?php echo esc_html($taxonomy->labels->name) ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td><label class="bbfb-metabox-label" for="_bbhd_others"><?php esc_html_e('Others', 'bestbug') ?></label></td>
				<td>
					<select name="_bbhd_others" id="_bbhd_others" class="bb-condition-control" data-ref=".bb-only-other" data-val="only">
						<option value=""><?php esc_html_e('None', 'bestbug') ?></option>
						<option value="all" <?php selected($_bbhd_others, 'all'); ?>><?php esc_html_e('All', 'bestbug') ?></option>
						<option value="only" <?php selected($_bbhd_others, 'only'); ?>><?php esc_html_e('Only', 'bestbug') ?></option>
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
						<select name="_bbhd_others_only[]" id="_bbhd_others_only" class="bb-chosen-select" multiple="multiple">
							<?php foreach ($others as $key => $other) { ?>
								<option value="<?php echo esc_attr($key) ?>" <?php if (in_array($key, $_bbhd_others_only)) echo 'selected'; ?>><?php echo esc_html($other) ?></option>
							<?php 
							} ?>
						</select>
					</div>
				</td>
			</tr>
			<tr class="bbfb-bg-grey">
				<td><label class="bbfb-metabox-label" for="_bbhd_custom_conditions"><?php esc_html_e('PHP Custom Conditions', 'bestbug') ?></label></td>
				<td>
					<input class="bb-metabox-control" type="text" name="_bbhd_custom_conditions" value="<?php echo esc_attr($_bbhd_custom_conditions) ?>">
					<p class="bb-metabox-desc">
						<?php echo wp_kses(__('Conditions like <b>is_single()</b>  or  <b>is_single() && is_home()</b> you can read about condition tags in WordPress in <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">here</a> <br/>(Just for Developers)', "bestbug"), array('br' => array(), 'b' => array(), 'a' => array('href' => array()))); ?>
					</p>
				</td>
			</tr>
		</table>
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

			if(isset( $_POST['_bb_header_max_width'] )) {
				$bb_header_max_width = sanitize_text_field( $_POST['_bb_header_max_width'] );
				update_post_meta( $post_id, '_bb_header_max_width', $bb_header_max_width );
			}

			if (isset($_POST['_bbhd_singular'])) {
				update_post_meta($post_id, '_bbhd_singular', sanitize_text_field($_POST['_bbhd_singular']));
			}
			if (isset($_POST['_bbhd_singular_only'])) {
				update_post_meta($post_id, '_bbhd_singular_only', ($_POST['_bbhd_singular_only']));
			}
			if (isset($_POST['_bbhd_pages'])) {
				update_post_meta($post_id, '_bbhd_pages', sanitize_text_field($_POST['_bbhd_pages']));
			}
			if (isset($_POST['_bbhd_pages_only'])) {
				update_post_meta($post_id, '_bbhd_pages_only', ($_POST['_bbhd_pages_only']));
			}
			if (isset($_POST['_bbhd_posts'])) {
				update_post_meta($post_id, '_bbhd_posts', sanitize_text_field($_POST['_bbhd_posts']));
			}
			if (isset($_POST['_bbhd_posts_only'])) {
				update_post_meta($post_id, '_bbhd_posts_only', ($_POST['_bbhd_posts_only']));
			}
			if (isset($_POST['_bbhd_taxs'])) {
				update_post_meta($post_id, '_bbhd_taxs', sanitize_text_field($_POST['_bbhd_taxs']));
			}
			if (isset($_POST['_bbhd_taxs_only'])) {
				update_post_meta($post_id, '_bbhd_taxs_only', ($_POST['_bbhd_taxs_only']));
			}
			if (isset($_POST['_bbhd_others'])) {
				update_post_meta($post_id, '_bbhd_others', sanitize_text_field($_POST['_bbhd_others']));
			}
			if (isset($_POST['_bbhd_others_only'])) {
				update_post_meta($post_id, '_bbhd_others_only', ($_POST['_bbhd_others_only']));
			}
			if (isset($_POST['_bbhd_custom_conditions'])) {
				update_post_meta($post_id, '_bbhd_custom_conditions', ($_POST['_bbhd_custom_conditions']));
			}
		}
        
    }
	
	new BESTBUG_HB_HEADER_METABOX();
}

