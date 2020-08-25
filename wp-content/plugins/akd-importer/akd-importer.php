<?php

/**
 * Plugin Name: AKD Importer
 * Plugin URI: https://designingmedia.com
 * Description: AKD-Framework auto create demo data package for developer, auto import demo data for clients. After import you can deactivate or remove plugin.
 * Version: 3.7.2
 * Author: akd team
 * Author URI: https://designingmedia.com
 * License: GPLv2 or later
 * Text Domain: akd-importer
 */

if (!defined('ABSPATH')) {

    exit();

}

update_option('enable_full_version','1');

$whitelist = array( '127.0.0.1', '::1' );

if (!get_option( 'enable_full_version' ) && !in_array( $_SERVER['REMOTE_ADDR'], $whitelist)) {

return ;

}

if (!class_exists('AKD_Importer')) :



    /**

     * Main Class

     *

     * @class AKD_Importer

     *

     * @version 1.0.0

     */

    final class AKD_Importer

    {



        /* single instance of the class */

        public $file = '';



        public $basename = '';



        /* base plugin_dir. */

        public $plugin_dir = '';

        public $plugin_url = '';



        /* base acess folder. */

        public $acess_dir = '';

        public $acess_url = '';



        public $theme_dir = '';

        public $theme_url = '';
        public $path_verify_file = 'https://designingmedia.com/TLM/index.php';



        /**

         * Main AKD_Importer Instance

         *

         * Ensures only one instance of AKD_Importer is loaded or can be loaded.

         *

         * @since 1.0.0

         * @static

         *

         * @see AKD_Importer()

         * @return AKD_Importer - Main instance

         */

        public static function instance()

        {

            static $_instance = null;



            /* Check php ver. */

            if(!version_compare(PHP_VERSION, '5.3', '>=')){

                add_action( 'admin_notices', array(new AKD_Importer(),'admin_notice_error'));

                return;

            }



            if (is_null($_instance)) {



                $_instance = new AKD_Importer();



                // globals.

                $_instance->setup_globals();



                // includes.

                $_instance->includes();



                // actions.

                $_instance->setup_actions();

            }

            return $_instance;

        }



        /**

         * globals value.

         *

         * @package AKD_Importer

         * @global path + uri.

         */

        private function setup_globals()

        {

            $this->file = __FILE__;



            /* base name. */

            $this->basename = plugin_basename($this->file);



            /* base plugin. */

            $this->plugin_dir = plugin_dir_path($this->file);

            $this->plugin_url = plugin_dir_url($this->file);



            /* base assets. */

            $this->acess_dir = trailingslashit($this->plugin_dir . 'assets');

            $this->acess_url = trailingslashit($this->plugin_url . 'assets');



            $this->theme_dir = trailingslashit(get_template_directory() . '/inc/demo-data') ;

            $this->theme_url = trailingslashit(get_template_directory_uri() . '/inc/demo-data') ;



            $themeDetails = wp_get_theme();
            $ThemName = $themeDetails->get('Name');
            $ThemeVersion = (int) $themeDetails->get( 'Version' );
            
            if($ThemName =="Hostiko" || $ThemName =="Hostiko Child"){
                if($ThemeVersion>30){
                    $this->demo_data_url = 'https://demo.designingmedia.com/demo-data/' ;
                    $this->demo_data_listing_url = 'https://demo.designingmedia.com/demo-data/demo-data-listing.json' ;
                }
                else{
                    $this->demo_data_url = 'http://designingmedia.com/demo-data/' ;
                    $this->demo_data_listing_url = 'http://designingmedia.com/demo-data/demo-data-listing.json' ;
                }

            }
            else if($ThemName =="Master" || $ThemName =="Master Child"){
                $this->demo_data_url = 'https://demo.designingmedia.com/demo-master/' ;
                $this->demo_data_listing_url = 'https://demo.designingmedia.com/demo-master/demo-master-listing.json' ;
            }






        }



        /**

         * Setup all actions + filter.

         *

         * @package AKD_Importer

         * @version 1.0.0

         */

        private function setup_actions()

        {

            add_action('admin_menu', array($this, 'add_admin_page'));



            add_action('extension_import_export_before', array($this, 'get_option_layout'));



            add_action('wp_ajax_akd_export', array($this, 'ajax_export'));

            add_action('wp_ajax_verify_theme_request' , array($this, 'verify_theme_request'));

            add_action('wp_ajax_akd_import', array($this, 'ajax_inport'));



            add_action('wp_ajax_akd_download', array($this, 'ajax_download'));

	

        }



        /**

         * include files.

         *

         * @package AKD_Importer

         * @version 1.0.0

         */

        private function includes()

        {

            global $wp_filesystem;



            /* add WP_Filesystem. */

            if ( !class_exists('WP_Filesystem') ) {

                require_once(ABSPATH . 'wp-admin/includes/file.php');

                WP_Filesystem();

            }



            /* dropbox */

            require_once $this->plugin_dir . 'plugins/dropbox/dropbox.php';



            /* content export. */

            require_once $this->plugin_dir . 'plugins/content/export.php';

            /* content import. */

            require_once $this->plugin_dir . 'plugins/content/import.php';



            /* media export. */

            require_once $this->plugin_dir . 'plugins/media/media.php';



            /* widget import. */

            require_once $this->plugin_dir . 'plugins/widget/import.php';

            /* widget export. */

            require_once $this->plugin_dir . 'plugins/widget/export.php';



            /* setting. */

            require_once $this->plugin_dir . 'plugins/setting/reduxframework.php';



            /* ctp ui. */

            require_once $this->plugin_dir . 'plugins/ctp-ui/ctp-ui.php';



            /* revslider export. */

            require_once $this->plugin_dir . 'plugins/revslider/export.php';

            /* revslider import. */

            require_once $this->plugin_dir . 'plugins/revslider/import.php';



            /* options */

            require_once $this->plugin_dir . 'plugins/options/options.php';



            /* download demo. */

            require_once $this->plugin_dir . 'plugins/download/download.php';



            /* reset data. */

            require_once $this->plugin_dir . 'plugins/reset/wordpress-reset.php';



            /* clear data */

            require_once $this->plugin_dir . 'plugins/clear/clear-tmp.php';



            /* git */

            require_once $this->plugin_dir . 'plugins/git/git.php';



            /* Grid. */

            require_once $this->plugin_dir . 'plugins/essential-grid/essential-grid.php';



        }



        /**

         * admin page.

         */

        function add_admin_page(){





            if(is_dir($this->theme_dir) || $this->export_demo_mode())

                add_submenu_page('tools.php', esc_attr__('Install Demo', 'akd-importer'), esc_attr__('Install Demo', 'akd-importer'), 'manage_options', 'akd-importer', array($this, 'get_admin_page_html'));

			

        }



        function get_admin_page_html(){

            echo '<div id="akd-admin-demo-page">';

            $this->get_option_layout();

            echo '</div>';

        }



        function admin_notice_error(){

            ?>

            <div class="notice notice-error is-dismissible">

                <p><?php echo sprintf(esc_html__('PHP V%s (It was too backward and incompatible with many plugins.) We recommend you upgrade to a newer version. You can contact your hosting or search "How to Change your PHP Version in cPanel".', 'akd-importer'), PHP_VERSION); ?></p>

            </div>

            <?php

        }



        /**

         * html options

         */

        function get_option_layout(){



            wp_enqueue_style('akd-importer', $this->acess_url . 'akd-importer.css');

            wp_enqueue_script('akd-importer' , $this->acess_url . 'akd-importer.js');



            do_action('akd-option-before');



            require_once $this->plugin_dir . 'templates/options-template.php';

        }



        /**

         * scan demo data folder

         * @return array|bool demo folders

         */

        function get_all_demo_folder(){



            if(!is_dir($this->theme_dir))

                return false;



            $files = scandir($this->theme_dir, 1);



            return array_diff($files, array('..', '.', 'attachment'));

        }



        /**

         * on or off export functions.

         * @return mixed|void

         */

        function export_demo_mode(){

            return apply_filters('akd-enable-create-demo', true);

        }

        function verify_theme_request(){
            echo '{"result":"access_success"}';
            die;
            
            $data =  get_option('opt_theme_options');
            $code_to_verify = $data['tlm'];
            $verify = 0;
            $type ='live';
            $path = site_url();
            $agent = base64_encode($_SERVER['HTTP_USER_AGENT']);
            $email = wp_get_current_user()->data->user_email;
            $ch = curl_init();
            $URL = $this->path_verify_file.'?action=verifycode&p_code='.$code_to_verify.'&path='.$path.'&email='.$email.'&removed_status='.$verify.'&type='.$type.'&agent='.$agent;
            curl_setopt($ch, CURLOPT_URL,  $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $output = curl_exec($ch);
            //$output = json_decode($output,true);
            //echo '<pre>data: '.print_r($output,true).'</pre><br>';
            echo $output;
            die();
           

        }



        function ajax_inport(){

    		global $wp_filesystem;

            if(empty($_REQUEST['id']) && empty($_REQUEST['import'])) exit();

			//echo '_REQUEST: <pre>'.print_r($_REQUEST,true).'</pre><br>';





			$upload_dir = wp_upload_dir();

			/* download & unzip. */

			$_cache = trailingslashit($upload_dir['basedir'] .'/akd_demo');



			if(!is_dir($_cache))

				wp_mkdir_p($_cache);



			$folder_dir =$_cache . $_REQUEST['id'];



			/* get options. */

			if(!file_exists($folder_dir)){

				wp_safe_remote_get( $this->demo_data_url.$_REQUEST['id'].'.zip', array( 'timeout' => 300, 'stream' => true, 'filename' => $_cache . $_REQUEST['id'].'.zip' ) );

				unzip_file($_cache . $_REQUEST['id'].'.zip', $_cache);

				@unlink($_cache . $_REQUEST['id'].'.zip');

				//exit();

				//$demos = json_decode( file_get_contents( $this->demo_data_listing_url),true );

				//echo 'demos: <pre>'.print_r($demos,true).'</pre><br>';

				//ob_start();

				//$demos = json_decode( file_get_contents( $file ),true );

                //echo 'text: <pre>'.print_r('demo folder not exist',true).'</pre><br>';



            }

            $response = $options = array();



            /* get demo dir. */

            $folder_dir = trailingslashit($_cache . $_REQUEST['id']);



            /* get options. */

            if(!file_exists($folder_dir . 'options.json')) exit();

	        $URL =  $folder_dir . 'options.json';

	        if( ini_get('allow_url_fopen') ) {

		        $options = file_get_contents( $URL );

	        }

	        else {

		        $ch = curl_init();

		        curl_setopt( $ch, CURLOPT_URL, $URL );

		        curl_setopt( $ch, CURLOPT_TIMEOUT, 500 );

		        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		        curl_setopt( $ch, CURLOPT_FAILONERROR, true );

		        $options = curl_exec( $ch );

		        if ( curl_error( $ch ) ) {

			        echo curl_error( $ch );

			        exit();

		        }

		        curl_close( $ch );

	        }



            $options = json_decode($options,true);

            $options['folder'] = $folder_dir;



            set_time_limit(0);

            switch ($_REQUEST['import']) {

                /* import media. */

                case 'attachment':

                    $response = akd_media_import($options);

                    break;

                /* import widgets. */

                case 'widgets':

                    $response = akd_widgets_process_import_file($folder_dir . 'widgets.wie');;

                    break;

                /* import theme setting. */

                case 'settings':

                    $response = akd_setting_import($folder_dir . 'setting.json');

                    break;

                /* import options */

                case 'options':

                    $response = akd_options_import($options);

                    break;

                /* import post type */

                case 'ctp_ui':

                    $response = akd_ctp_ui_import($folder_dir);;

                    break;

                /* import content */

                case 'content':

                    $response = akd_grid_import($folder_dir);

                    $response = akd_content_import($options);

                    break;

                /* revslider import */

				case 'revslider':

					$response = akd_revslider_import($folder_dir);

					break;

				case 'files':

					require_once akd_importer()->plugin_dir . 'lib/WordPressFileManager.php';

					$URL = $this->demo_data_listing_url;

					if( ini_get('allow_url_fopen') ) {

						$data = file_get_contents( $URL );

					}

					else {

						$ch = curl_init();

						curl_setopt( $ch, CURLOPT_URL, $URL );

						curl_setopt( $ch, CURLOPT_TIMEOUT, 500 );

						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

						curl_setopt( $ch, CURLOPT_FAILONERROR, true );

						$data = curl_exec( $ch );

						if ( curl_error( $ch ) ) {

							echo curl_error( $ch );

							exit();

						}

						curl_close( $ch );

					}

					$demos = json_decode( $data,true );



					//$demos = json_decode( file_get_contents( $this->demo_data_listing_url ),true );

					$selectedDemo = $demos[$_REQUEST['key']];

					$allFiles = $selectedDemo['files'];



					foreach ($allFiles as $file){

						if($file['type']=='folder'){

							$scrFolder = $folder_dir.'/'.$file['name'];

							$desFolder = get_template_directory().'/'.$file['des'].'/'.$file['name'];

							$copied = WordPressFileManager::copyFolder($scrFolder, $desFolder);

						}

                        elseif($file['type']=='file'){

							//$file = $file['name'];

							$ScrFolder =  $folder_dir;

							$DestinationFolder = get_template_directory().'/'.$file['des'];

							$copied = WordPressFileManager::copyFile($ScrFolder, $DestinationFolder, $file['name']);

						}

					}

					$response = $copied;

					break;

                case 'clear':

					$response = akd_clear_tmp();

					$response = akd_remove_cache($_cache);

                    //exit();

                    break;

                case 'finish':

                    do_action('akd-import-finish', $_REQUEST['id']);

					/* set demo id installed. */

                    update_option('akd-current-demo-installed', $_REQUEST['id']);

                    break;

            }



            do_action('akd-demo-'.$_REQUEST['id'].'-'.$_REQUEST['import'].'-after');



            exit($response);

        }



        function ajax_export(){



            if(empty($_REQUEST['id']) || empty($_REQUEST['export']))

                exit();



            $response = array();



            $folder_name = sanitize_title($_REQUEST['id']);

            $export_action = $_REQUEST['export'];



            /* get demo dir. */

            $folder_dir = $this->process_demo_folder($folder_name);



            /* screenshot */

            $this->process_demo_thumb($folder_name);



            switch ($export_action) {

                /* export widgets. */

                case 'attachment':

                    $response = akd_media_export($folder_dir);

                    break;

                /* export widgets. */

                case 'widgets':

                    $response = akd_widgets_save_export_file($folder_dir . 'widgets.wie');

                    break;

                /* export theme setting. */

                case 'settings':

                    $response = akd_setting_export($folder_dir . 'setting.json');

                    break;

                /* export options */

                case 'options':

                    $response = akd_options_export($folder_dir . 'options.json');

                    break;

                /* custom post type */

                case 'ctp_ui':

                    $response = akd_ctp_ui_export($folder_dir);

                    break;

                /* export content */

                case 'content':

                    $response = akd_grid_export($folder_dir);

                    $response = akd_content_export($folder_dir);

                    break;

                /* revslider export */

                case 'revslider':

                    $response = akd_revslider_export($folder_dir);

                    break;

                /* syn to git. */

                case 'git':

                    $response = akd_git_shell();

                    break;

                /* clear tmp. */

                case 'clear':

                    $response = akd_clear_tmp();

                    break;



                /* clear tmp. */

                case 'grid':

                    $response = akd_grid_export();

                    break;

            }



            do_action('akd-export');



            exit(json_encode($response));

        }



        /**

         * download demo data.

         */

        function ajax_download(){



            $zip_file = akd_download_demo_zip();



            header("Content-type: application/zip");

            header("Content-Disposition: attachment; filename=demo-data.zip");

            header("Pragma: no-cache");

            header("Expires: 0");

            readfile($zip_file);



            @unlink($zip_file); //delete file after sending it to user



            exit();

        }



        /**

         * check and create folder.

         *

         * @param $folder_name

         * @return string folder dir

         */

        private function process_demo_folder($folder_name){



            if(!is_dir($this->theme_dir . $folder_name))

                wp_mkdir_p($this->theme_dir . $folder_name);



            return trailingslashit($this->theme_dir . $folder_name);

        }



        /*

         * auto copy screenshot from theme.

         */

        private function process_demo_thumb($folder_name){



            if(is_file($this->theme_dir . $folder_name . '/screenshot.png'))

                return;



            if(!is_file(get_template_directory() . '/screenshot.png'))

                return;



            copy(get_template_directory() . '/screenshot.png' , $this->theme_dir . $folder_name . '/screenshot.png');

        }

    }

endif;



/**

 * Returns the main instance of AKD_Importer() to prevent the need to use globals.

 *

 * @since 1.0

 * @return AKD_Importer

 */

if (!function_exists('akd_importer')) {



    function akd_importer()

    {

        return AKD_Importer::instance();

    }

}



if (defined('AKD_IMPORT_EXPORT_LATE_LOAD')) {

    add_action('plugins_loaded', 'akd_importer', (int)AKD_IMPORT_EXPORT_LATE_LOAD);

} else {

    akd_importer();

}