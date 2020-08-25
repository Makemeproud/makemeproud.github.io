<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BestBug_Core_Options' ) ) {
	/**
	 * BestBug_Core_Options Class
	 *
	 * @since	1.0
	 */
	class BestBug_Core_Options {

		public $fields;
		public $page_title;
		public $ajax_action;
		public $button_text;
        
		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'admin_menu', array( $this, 'options' ), 11 );
			add_action( 'wp_ajax_bb_save_options', array( $this, 'save_options') );
			add_action( 'wp_ajax_bb_save_post', array( $this, 'save_post') );
		}

		public static function adminEnqueueScripts() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker');
		
			wp_enqueue_style( 'codemirror', BESTBUG_CORE_URL .'/assets/libs/codemirror/lib/codemirror.css' );
			wp_enqueue_script( 'codemirror', BESTBUG_CORE_URL .'/assets/libs/codemirror/lib/codemirror.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'codemirror-javascript', BESTBUG_CORE_URL .'/assets/libs/codemirror/mode/javascript/javascript.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'codemirror-active-line', BESTBUG_CORE_URL .'/assets/libs/codemirror/addon/selection/active-line.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'codemirror-matchbrackets', BESTBUG_CORE_URL .'/assets/libs/codemirror/addon/edit/matchbrackets.js', array( 'jquery' ), null, true );
			
			wp_enqueue_media();
			
			wp_enqueue_style( 'dataTables', BESTBUG_CORE_URL .'/assets/admin/css/jquery.dataTables.min.css' );
			wp_enqueue_script( 'dataTables', BESTBUG_CORE_URL .'/assets/admin/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.16', true );
			
			wp_enqueue_style( 'tagsinput', BESTBUG_CORE_URL . '/assets/admin/css/jquery.tagsinput.css' );
			wp_enqueue_script( 'tagsinput', BESTBUG_CORE_URL . '/assets/admin/js/jquery.tagsinput.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			
			wp_enqueue_style( 'select2', BESTBUG_CORE_URL . '/assets/admin/css/select2.css' );
			wp_enqueue_script( 'select2', BESTBUG_CORE_URL . '/assets/admin/js/select2.min.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			
			wp_enqueue_style( 'growl', BESTBUG_CORE_URL . '/assets/admin/css/jquery.growl.css' );
			wp_enqueue_script( 'growl', BESTBUG_CORE_URL . '/assets/admin/js/jquery.growl.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			
			wp_enqueue_script( 'bb-toggle', BESTBUG_CORE_URL . '/assets/admin/js/extend/vc-params/toggle.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			
			wp_enqueue_style( 'bb-toggle', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/toggle.css' );
			wp_enqueue_style( 'bb-number', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/number.css' );
			wp_enqueue_style( 'bb-tags', BESTBUG_CORE_URL . '/assets/admin/css/extend/vc-params/number.css' );
			
			wp_enqueue_script( 'multi-select', BESTBUG_CORE_URL . '/assets/admin/js/jquery.multi-select.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			wp_enqueue_script( 'quicksearch', BESTBUG_CORE_URL . '/assets/admin/js/jquery.quicksearch.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			wp_enqueue_script( 'wp-color-picker-alpha', BESTBUG_CORE_URL . '/assets/admin/js/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker' ), BESTBUG_CORE_VERSION, true );
		}
        
        public function options() {
            $this->options = apply_filters( 'bb_register_options', array() );
			
    		if( !isset($this->options) || count($this->options) <= 0 ) {
    			return;
    		}
    		foreach ($this->options as $key => $option) {
				$slug = $option['menu']['menu_slug'];
				$this->fields[$slug]['fields'] = $option['fields'];
				
				$this->fields[$slug]['page_title'] = $option['menu']['page_title'];
				$this->type = $option['type'];
				
				if(isset($option['ajax_action'])) {
					$this->fields[$slug]['ajax_action'] = $option['ajax_action'];
				}
				if(isset($option['button_text'])) {
					$this->fields[$slug]['button_text'] = $option['button_text'];
				}
				
				if($option['menu']['type'] == 'add_menu_page') {
					add_menu_page($option['menu']['page_title'],
								$option['menu']['menu_title'],
								$option['menu']['capability'],
								$option['menu']['menu_slug'],
								array($this, $this->type),
								$option['menu']['icon'],
								$option['menu']['position']
							);
				} else if($option['menu']['type'] == 'add_submenu_page') {
					add_submenu_page($option['menu']['parent_slug'],
								$option['menu']['page_title'],
								$option['menu']['menu_title'],
								$option['menu']['capability'],
								$option['menu']['menu_slug'],
								array($this, $this->type)
							);
				}
    		}
        }
		
		public function options_fields(){
			if(empty($this->fields[$_GET['page']])) {
				return;
			}
			$slug = $_GET['page'];
			if(isset($this->fields[$slug]['ajax_action'])) {
				$this->ajax_action = $this->fields[$slug]['ajax_action'];
			}
			if(isset($this->fields[$slug]['button_text'])) {
				$this->button_text = $this->fields[$slug]['button_text'];
			}
			$this->page_title = $this->fields[$slug]['page_title'];
			
			$this->begin_form_html();
			$fields = $this->fields[$slug]['fields'];
			foreach ($fields as $key => $field) {
				$allow = array('tags', 
							'textfield',
							'number', 
							'textarea',
							'javascript',
							'dropdown',
							'dropdown',
							'dropdown2',
							'couple3',
							'couple4',
							'multi_select',
							'couple',
							'couple2',
							'attach',
							'toggle',
							'colorpicker',
							'checkbox',
							'hidden',
							'text',
						);
						
				if(in_array($field['type'], $allow)) {
					$option_exists = (get_option( $field['param_name'], null));
					if($option_exists != null) {
						if(is_array($field['value'])) {
							$field['std'] = $option_exists;
						} else {
							$field['value'] = $option_exists;
						}
					} 
					
					$dependency = '';
				    if(isset($field['dependency'])) {
				        $dependency = 'data-dependency="true" data-element="'.$field['dependency']['element'].'" data-value="'.implode(',', $field['dependency']['value']).'"';
				    }
					
					include 'fields/' . $field['type'] . ".view.php";
				} else {
					esc_html_e('Type-field is not exists', 'bestbug');
				}
			}
			
			$this->end_form_html();
		}
		
		public function post_fields(){
			if(empty($this->fields[$_GET['page']])) {
				return;
			}
			$slug = $_GET['page'];
			if(isset($this->fields[$slug]['ajax_action'])) {
				$this->ajax_action = $this->fields[$slug]['ajax_action'];
			}
			if(isset($this->fields[$slug]['button_text'])) {
				$this->button_text = $this->fields[$slug]['button_text'];
			}
			$this->page_title = $this->fields[$slug]['page_title'];
			
			$this->begin_form_html();
			$fields = $this->fields[$slug]['fields'];
			foreach ($fields as $key => $field) {
				$allow = array('tags', 
							'textfield',
							'number', 
							'textarea',
							'javascript',
							'multi_select',
							'dropdown',
							'dropdown2',
							'couple3',
							'couple4',
							'couple',
							'couple2',
							'attach',
							'toggle',
							'colorpicker',
							'checkbox',
							'hidden',
							'text',
						);
						
				if(in_array($field['type'], $allow)) {
					
					if(isset($_REQUEST['ID']) && !empty($_REQUEST['ID'])) {
						$this->post_id = $_REQUEST['ID'];
						if($field['param_name'] == 'post_title') {
							$value_exists = get_the_title($this->post_id);
						} elseif($field['param_name'] == 'post_name') {
							$value_exists = get_post_field('post_name', $this->post_id);
						} else {
							$value_exists = (get_post_meta($this->post_id, $field['param_name'], true));
						}
						
						if($value_exists != null) {
							if(is_array($field['value'])) {
								$field['std'] = $value_exists;
							} else {
								$field['value'] = $value_exists;
							}
						}
					}
					
					$dependency = '';
				    if(isset($field['dependency'])) {
				        $dependency = 'data-dependency="true" data-element="'.$field['dependency']['element'].'" data-value="'.implode(',', $field['dependency']['value']).'"';
				    }
					
					include 'fields/' . $field['type'] . ".view.php";
				} else {
					esc_html_e('Type-field is not exists', 'bestbug');
				}
			}
			
			$this->end_form_html();
		}
		
		public function begin_form_html(){
			if(empty($this->ajax_action)) {
				$this->ajax_action = 'bb_save_options';
			}
			?>
			<div class="wrap bb-wrap bb-settings" id="<?php echo esc_attr($_GET['page']) ?>">
			    <h2 class="bb-headtitle"><?php echo esc_html($this->page_title) ?></h2>
				<form class="bb-form" method="post" action="">
			<?php
		}
        
		public function end_form_html(){
			?>
					<input type="hidden" name="action" value="<?php echo esc_attr($this->ajax_action) ?>">
					<button type="submit" name="submit" class="button success">
						<span class="dashicons dashicons-admin-generic"></span>
						<?php echo (!empty($this->button_text))?$this->button_text:esc_html__('Save Changes', 'bestbug') ?>
					</button>
				</form>
			</div>
			<?php
		}
		
		public function save_options(){
			
			if(!current_user_can('administrator')) {
				exit;
			}
			unset($_POST['action']);
			
			do_action_ref_array( 'bb_before_save_options', array(&$_POST) );
			
			function mapArry($val){
				if (is_array($val)){
					return array_map('mapArry', $val);
				}else{
					return base64_decode($val);
				}
			}
			
			foreach ($_POST as $key => $value) {
				if (is_array($value)) {
					if(!BestBug_Helper::update_option($key, array_map('mapArry', $value))) {
						$result = array(
							'status' => 'error',
							'title' => esc_html__('Error','bestbug'),
							'message' => esc_html__('Fail! can not save data','bestbug')
						);
						echo json_encode($result);
						exit;
					}
				} else {
					if(!BestBug_Helper::update_option($key, base64_decode($value))) {
						$result = array(
							'status' => 'error',
							'title' => esc_html__('Error','bestbug'),
							'message' => esc_html__('Fail! can not save data','bestbug')
						);
						echo json_encode($result);
						exit;
					}
				}
			}
			
			$result = array(
				'status' => 'notice',
				'title' => esc_html__('Saved','bestbug'),
				'message' => esc_html__('Everything saved','bestbug')
			);
			echo json_encode($result);
			
			exit;
		}
		
		public function save_post(){
			
			if(!current_user_can('administrator')) {
				exit;
			}
			unset($_POST['action']);
			$post = array(
				'post_status' => 'publish',
			);
			
			if(isset($_POST['post_title']) && !empty($_POST['post_title'])) {
				$post['post_title'] = $_POST['post_title'];
				unset($_POST['post_title']);
			}
			if(isset($_POST['post_name']) && !empty($_POST['post_name'])) {
				$post['post_name'] = $_POST['post_name'];
				unset($_POST['post_name']);
				
				$path = $post['post_name']; 
				$post_type = 'page';
				if(isset($_POST['post_type']) && !empty($_POST['post_type'])) {
					$post_type = $_POST['post_type'];
				}
				$slug_query = get_page_by_path($path, ARRAY_A , $post_type);
				if($slug_query !== NULL && count($slug_query) > 0) {
					if(!isset($_POST['ID']) || empty($_POST['ID']) || $slug_query['ID'] != $_POST['ID']) {
						$result = array(
							'status' => 'warning',
							'title' => esc_html__('Slug exists','bestbug'),
							'message' => esc_html__('Please choose other slug','bestbug')
						);
						echo json_encode($result);
						exit;
					}
				}
			}
			if(isset($_POST['post_type']) && !empty($_POST['post_type'])) {
				$post['post_type'] = $_POST['post_type'];
				unset($_POST['post_type']);
			}
			if(isset($_POST['post_status']) && !empty($_POST['post_status'])) {
				$post['post_status'] = $_POST['post_status'];
				unset($_POST['post_status']);
			}
			if(isset($_POST['post_content']) && !empty($_POST['post_content'])) {
				$post['post_content'] = $_POST['post_content'];
				unset($_POST['post_content']);
			}
			if(isset($_POST['ID']) && !empty($_POST['ID'])) {
				$post['ID'] = $_POST['ID'];
				unset($_POST['ID']);
			}

			$ID = wp_insert_post( $post, $error );
			$custom_js = '';
			if(!isset($_POST['ID']) || empty($_POST['ID'])) {
				$custom_js = 'bb_after_add('.$ID.')';
			}
			do_action_ref_array( 'bb_after_save_post', array(&$ID, &$post, &$_POST) );
			if(!is_int($ID)) {
				$result = array(
					'status' => 'error',
					'title' => esc_html__('Error','bestbug'),
					'message' => esc_html__('Fail! can not save data','bestbug')
				);
				echo json_encode($result);
				exit;
			}
			function mapArry($val){
				if (is_array($val)){
					return array_map('mapArry', $val);
				}else{
					return base64_decode($val);
				}
			}
			foreach ($_POST as $key => $value) {
				if (is_array($value)){
					if(!BestBug_Helper::update_meta($ID, $key, array_map('mapArry', $value))) {
						$result = array(
							'status' => 'error', 
							'title' => esc_html__('Error','bestbug'),
							'message' => esc_html__('Fail! can not save meta data','bestbug')
						);
						echo json_encode($result);
						exit;
					}
				} else {
					if(!BestBug_Helper::update_meta($ID, $key, base64_decode($value))) {
						$result = array(
							'status' => 'error', 
							'title' => esc_html__('Error','bestbug'),
							'message' => esc_html__('Fail! can not save meta data','bestbug')
						);
						echo json_encode($result);
						exit;
					}
				}
				

				
			}
			
			$result = array(
				'status' => 'notice',
				'title' => esc_html__('Saved','bestbug'),
				'message' => esc_html__('Everything saved','bestbug'),
				'custom_js' => $custom_js,
			);
			echo json_encode($result);
			
			exit;
		}
		
    }
	
	new BestBug_Core_Options();
}

