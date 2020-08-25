<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 8/15/2016
 * Time: 9:36 AM
 */

if (!defined('ABSPATH')) exit();

if(!class_exists('AKD_IAE_Dropbox')){
    class AKD_IAE_Dropbox{

        public $config = array();

        function __construct()
        {
            if (strlen((string) PHP_INT_MAX) < 19) return;

            $this->config = array('key' => '7qmzlz7m3fe312v', 'secret' => 'mskbar6oeo8091d');

            add_action('akd-export-action-after', array($this,'form'));
            add_action('akd-option-before', array($this,'load_script'));
            add_action('wp_ajax_akd_dropbox_upload', array($this, 'upload_to_dropbox'));
            add_action('wp_ajax_akd_dropbox_get_access_token', array($this, 'get_access_token'));
            add_action('wp_ajax_akd_dropbox_get_access_files', array($this, 'dropbox_files'));
        }

        function load_script(){

            add_thickbox();

            wp_enqueue_style('jqueryFileTree-css', akd_importer()->plugin_url . 'plugins/dropbox/jqueryFileTree.css');
            wp_enqueue_script('jqueryFileTree-js', akd_importer()->plugin_url . 'plugins/dropbox/jqueryFileTree.js');

            wp_enqueue_script('akd-dropbox' , akd_importer()->plugin_url . 'plugins/dropbox/akd-dropbox.js');
        }

        function form(){

            require_once akd_importer()->plugin_dir . 'lib/Dropbox/autoload.php';

            $appInfo = \Dropbox\AppInfo::loadFromJson($this->config);
            $webAuth = new \Dropbox\WebAuthNoRedirect($appInfo,'PHP-Example/1.0');
            $authorizeUrl = $webAuth->start();

            $code = get_transient('akd-dropbox-code');

            //delete_transient('akd-dropbox-code');
            //delete_transient('akd-dropbox-access-token');

            ?>
            <?php if(!$code): ?>
                <tr xmlns="http://www.w3.org/1999/html">
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Get Dropbox Code', 'akd-importer'); ?><span class="spinner"></span>
                    </div>
                </th>
                <td>
                    <a href="<?php echo esc_url($authorizeUrl); ?>" target="_blank" class="button"><?php esc_html_e('Authorize', 'akd-importer'); ?></a>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Dropbox Code', 'akd-importer'); ?><span class="spinner"></span>
                        <span class="description"><?php esc_html_e('Copy code & paste', 'akd-importer'); ?></span>
                        <span id="dropbox-loading" class="spinner"></span>
                    </div>
                </th>
                <td>
                    <input type="text" id="dropbox-code" class="regular-text" value="<?php echo esc_attr($code); ?>" placeholder="gFcJTcb5pBsAAAAAAAADDUeWaGyCWt9lmDMqbXRc5gE"<?php if($code) { echo ' disabled="disabled"';}?>>
                    <input type="text" id="dropbox-dir">
                    <a id="dropbox-select-dir" href="#TB_inline?width=600&height=550&inlineId=dropbox-tree" class="button thickbox"><?php esc_html_e('Save To','akd-importer'); ?></a>
                    <button id="dropbox-upload" type="button" class="button"><?php esc_html_e('Package & Upload','akd-importer'); ?></button>
                    <br><hr>
                    <input id="create-pakage" type="checkbox">
                    <label for="create-pakage"><?php esc_html_e('Create full package & chill theme (If network slow recommended usual way packing.)', 'akd-importer'); ?></label>
                    <a id="dropbox-url" href="#" target="_blank" style="display: none"></a>
                    <div id="dropbox-tree" style="display:none;">
                        <div class="tree-content"></div>
                    </div>
                </td>
            </tr>
            <?php
        }

        /**
         * get access-token from code.
         * @return bool/access-token
         */
        function get_access_token(){

            if(empty($_REQUEST['code'])) exit(false);

            require_once akd_importer()->plugin_dir . 'lib/Dropbox/autoload.php';

            $accessToken = get_transient('akd-dropbox-access-token');

            if(!$accessToken){
                $appInfo = \Dropbox\AppInfo::loadFromJson($this->config);
                $webAuth = new \Dropbox\WebAuthNoRedirect($appInfo,'PHP-Example/1.0');
                list($accessToken, $dropboxUserId) = $webAuth->finish($_REQUEST['code']);

                if ($accessToken){
                    set_transient('akd-dropbox-access-token', $accessToken, 3500);
                    set_transient('akd-dropbox-code', $_REQUEST['code'], 3500);
                }
            }

            exit($accessToken);
        }

        function dropbox_files(){

            $accessToken = get_transient('akd-dropbox-access-token');
            if(!$accessToken) exit(false);

            require_once akd_importer()->plugin_dir . 'lib/Dropbox/autoload.php';

            $dbxClient = new \Dropbox\Client($accessToken, "PHP-Example/1.0");

            $dir = urldecode(!empty($_POST['dir']) ? $_POST['dir'] : '/');

            $metaData = $dbxClient->getMetadataWithChildren($dir);
            $files = $metaData['contents'];

            if( count($files) > 0 ){
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach( $files as $file ) {
                    if($file['is_dir']) {
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($file['path']) . "\">" . basename($file['path']) . "</a></li>";
                    }
                }
                echo "</ul>";
            }

            exit(json_decode($files));
        }

        function upload_to_dropbox(){

            set_time_limit(0);

            /* zip */
            if(!class_exists('ZipArchive')) exit(esc_html__('Zip archive missing!', 'akd-importer'));

            $theme = wp_get_theme();
            $_dir = !empty($_POST['dir']) ? $_POST['dir'] . '/' : '/';
            $full_package = isset($_POST['package']) ? $_POST['package'] : false ;

            $accessToken = get_transient('akd-dropbox-access-token');
            if(!$accessToken) exit(esc_html__('Access token expired!', 'akd-importer'));

            require_once akd_importer()->plugin_dir . 'lib/Dropbox/autoload.php';
            $dbxClient = new \Dropbox\Client($accessToken, "PHP-Example/1.0");

            /* zip package. */
            $files = array();
            if($full_package == 'true') {
                $_dir = $_dir . $theme->get('Name');
                $files = $this->create_theme_package();
                $dbxClient->createFolder($_dir);
                $_dir = trailingslashit($_dir);
            } else {
                $files[] = $this->package();
            }

            if(count($files) > 0) if(!$accessToken) exit(esc_html__('Files not exists!', 'akd-importer'));

            // Uploading the file
            foreach ($files as $file) {

                if(!file_exists($file)) continue;

                $f = fopen($file, "rb");
                $result = $dbxClient->uploadFile( $_dir . basename($file), \Dropbox\WriteMode::add(), $f);
                fclose($f);
            }

            akd_clear_tmp();

            exit(htmlentities('https://www.dropbox.com/home' . $_dir));
        }

        function package(){

            $_cache = trailingslashit(ABSPATH . 'wp-content/uploads/akd_demo');
            if(!is_dir($_cache)) wp_mkdir_p($_cache);

            $theme = $_cache . basename(get_template_directory()). '.zip';

            $zip = new ZipArchive;
            $zip->open($theme, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

            $removeFile = array(
                '.git',
                '.idea',
                '.DS_Store',
                'githook.php',
            );

            folderToZip(trailingslashit(get_template_directory()), $zip, '', $removeFile);

            $zip->close();

            return $theme;
        }

        function create_theme_package(){

            $theme = wp_get_theme();

            $_licensing = trailingslashit(akd_importer()->plugin_dir . 'templates/licensing');

            /* check cache folder. */
            $_cache = trailingslashit(ABSPATH . 'wp-content/uploads/akd_demo');
            if(!is_dir($_cache)) wp_mkdir_p($_cache);

            /* create full package folder. */
            if(!is_dir($_cache . 'full-package')) wp_mkdir_p($_cache . 'full-package');
            if(!is_dir($_cache . 'full-package/Licensing')) wp_mkdir_p($_cache . 'full-package/Licensing');
            if(!is_dir($_cache . 'full-package/Documentation')) wp_mkdir_p($_cache . 'full-package/Documentation');

            /* Licensing */
            copy($_licensing . 'GPL.txt', $_cache . 'full-package/Licensing/' . 'GPL.txt');
            copy($_licensing . 'README_License.txt', $_cache . 'full-package/Licensing/' . 'README_License.txt');

            /* child theme */
            $this->create_child_theme($_cache, $theme);

            /* theme. */
            $file = $this->package();
            copy($file, $_cache . 'full-package/' . basename($file));

            $_full_package = $_cache . 'FullPackage(Unzip First).zip';

            /* zip */
            $zip = new ZipArchive;
            $zip->open($_full_package, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
            folderToZip(trailingslashit($_cache . 'full-package'), $zip);
            $zip->close();

            return array($_full_package, $file);
        }

        function create_child_theme($_cache, $theme){
            global $wp_filesystem;

            /* create folder */
            $chill_dir = trailingslashit($_cache . 'chill-theme/' . $theme->get('TextDomain') . '-chill');

            if(!is_dir($chill_dir)) wp_mkdir_p($chill_dir);

            /* create style.css */
            $style = $wp_filesystem->get_contents(akd_importer()->plugin_dir . 'templates/chill-theme/style.css');

            $_info = array(
                '[name]'        => $theme->get('Name') . ' Child',
                '[uri]'         => $theme->get('ThemeURI'),
                '[desc]'        => $theme->get('Description'),
                '[author]'      => $theme->get('Author'),
                '[author-uri]'  => $theme->get('AuthorURI'),
                '[template]'    => $theme->get('TextDomain'),
                '[version]'     => $theme->get('Version'),
                '[tags]'        => $theme->get('Tags'),
                '[text-domain]' => $theme->get('TextDomain') . '-child',
            );

            foreach ($_info as $key => $value){
                $style = str_replace($key, $value, $style);
            }

            $wp_filesystem->put_contents($chill_dir . 'style.css', $style, FS_CHMOD_FILE);

            /* create functions.php */
            copy(akd_importer()->plugin_dir . 'templates/chill-theme/functions.php',$chill_dir . 'functions.php');
            /* create screen shot. */
            copy(get_template_directory() . '/screenshot.png',$chill_dir . 'screenshot.png');

            /* zip */
            $zip = new ZipArchive;
            $zip->open($_cache . 'full-package/' . basename($chill_dir) . '.zip', ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
            folderToZip(trailingslashit($_cache . 'chill-theme'), $zip);
            $zip->close();

            return $chill_dir;
        }
    }

    new AKD_IAE_Dropbox();
}