<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/31/2016
 * Time: 1:42 PM
 */
/* ini get */
$_search = array('M','G','K','m','g','k');
$memory_limit = (int)str_replace($_search, null, ini_get("memory_limit"));
$max_time = (int)ini_get("max_execution_time");
$post_max_size = (int)str_replace($_search, null, ini_get('post_max_size'));
$php_ver = PHP_VERSION;
$_notice = ($memory_limit < 128 || $max_time < 60 || $post_max_size < 32) ? 'redux-critical' : 'redux-info';
/* get all demo */
//$demos = $this->get_all_demo_folder();
    //ob_start();
$URL = $this->demo_data_listing_url;
if( ini_get('allow_url_fopen') ) {
	$data = file_get_contents( $this->demo_data_listing_url );
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
/* get demo installed. */
$demo_installed = get_option('akd-current-demo-installed');
$create_demo = $this->export_demo_mode();
?>
<div class="hasIcon redux-notice-field redux-field-info <?php echo esc_attr($_notice); ?>">
    <p class="redux-info-icon"><i class="el el-info-circle icon-large"></i></p>
        <table class="akd-server-info">
            <tr>
                <th><?php esc_html_e('PHP Version:', 'envato-market'); ?></th>
                <td><i class="el el-check"></i></td>
                <td><?php echo esc_html($php_ver); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Memory Limit:', 'envato-market') ?></th>
                <?php if($memory_limit >= 128): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $memory_limit); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 128M)', ''), $memory_limit); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <th><?php esc_html_e('Max. Execution Time:', 'envato-market') ?></th>
                <?php if($max_time >= 60): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (s)', ''), $max_time); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 60s)', ''), $max_time); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <th><?php esc_html_e('Max. Post Size:', 'envato-market') ?></th>
                <?php if($post_max_size >= 32): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $post_max_size); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 32M)', ''), $post_max_size); ?></td>
                <?php endif; ?>
            </tr>
        <tr>
            <th><?php esc_html_e('File Get Content Command:', 'envato-market') ?></th>
	        <?php if( ini_get('allow_url_fopen') ) { ?>
                <td><i class="el el-check"></i></td>
                <td style="color: darkgreen; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('Acitve', '')); ?></td>
	        <?php } else{ ?>
                <td><i class="el el-remove-circle"></i></td>
                <td style="color: #ff6262; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('Deactive', '')); ?></td>
	        <?php }?>
        </tr>
        <tr>
            <th><?php esc_html_e('CURL Command:', 'envato-market') ?></th>
			<?php if( function_exists('curl_version') ) { ?>
                <td><i class="el el-check"></i></td>
                <td style="color: darkgreen; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('Acitve', '')); ?></td>
			<?php } else{ ?>
                <td><i class="el el-remove-circle"></i></td>
                <td style="color: #ff6262; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('Deactive', '')); ?></td>
			<?php }?>
        </tr>
        <tr>
            <th><?php esc_html_e('Upload DIR Write Permission:', 'envato-market') ?></th>
			<?php
            $root = dirname (dirname (dirname (dirname ( __FILE__ ))));
            $uplodDIR = $root.'/uploads';
			$rights = substr(sprintf('%o', fileperms($uplodDIR)), -4);
			?>
	        <?php if(is_writable( $uplodDIR )===true){ ?>
                <td><i class="el el-check"></i></td>
                <td style="color: darkgreen; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('Yes', '')); ?></td>
	        <?php } else{ ?>
                <td><i class="el el-remove-circle"></i></td>
                <td style="color: #ff6262; font-size: 16px; font-weight: bold"><?php echo sprintf(esc_html__('No', '')); ?></td>
	        <?php }?>
        </tr>
       
        </table>
</div><!-- server info -->
<?php if($demos): ?>
<div class="akd-importer">
    <ul class="akd-list-demos">
    <?php foreach ($demos as $key => $demo): ?>
        <?php
        //$demo_url = trailingslashit($this->theme_url . $demo['scr']);
        $demo_url = $this->demo_data_url;
        $demo_opacity = $demo_action = $input_disabled = '';
        /**
         * $demo_installed == $demo
         */
        if($demo_installed == $demo['scr']){
            $demo_action = '<form method="post">
                <a class="button uninstall-demo" href="tools.php?page=wordpress-reset" title="'.esc_attr__('Uninstall reset all wordpress data, you can back-up all data before uninstall.', 'akd-importer').'"><span class="dashicons dashicons-trash"></span></a>
                <button type="button" class="button button-primary install-demo" data-key="'.esc_attr($key).'" data-demo="'.esc_attr($demo['scr']).'"><span class="dashicons dashicons-controls-repeat"></span> '.esc_html__('Update', 'akd-importer').'</button>
            </form>';
        } else {
            if ($demo_installed){
                $demo_opacity = ' akd-opacity-0-5';
                $input_disabled = ' disabled="disabled"';
            }
            if($create_demo) $demo_action = '<a href="#" class="button delete-demo" title="'.esc_attr__('Delete Demo Files','akd-importer').'"><span class="dashicons dashicons-no"></span></a>';
            $demo_action .= '<button type="button" class="button button-primary install-demo" data-key="'.esc_attr($key).'" data-demo="'.esc_attr($demo['scr']).'"'.$input_disabled.'><span class="dashicons dashicons-randomize"></span> '.esc_html__('Install', 'akd-importer').'</button>';
        }
        ?>
        <li class="akd-demo<?php echo esc_attr($demo_opacity); ?>">
            <div class="akd-content">
                <div class="akd-thumb"><img src="<?php echo esc_url($demo_url . $demo['scr'].'.png') ?>" alt="<?php echo esc_attr($demo['title']); ?>"></div>
                <div class="akd-action">
                    <h3><?php echo esc_attr($demo['title']); ?></h3>
                    <div>
                        <button type="button" class="button select-import-data" title="<?php esc_attr_e('Default import all data.', 'akd-importer'); ?>"<?php echo $input_disabled; ?>><span class="dashicons dashicons-media-archive"></span> <?php esc_html_e('Select Data', 'akd-importer'); ?></button>
                        <ul class="akd-data-import">
                            <li>
                                <input class="all-data" type="checkbox" value="all" checked="checked">
                                <label><?php esc_html_e('All', 'akd-importer'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="attachment">
                                <label><?php esc_html_e('Media', 'akd-importer'); ?></label>
                            </li>
                            <?php if(function_exists('cptui_get_post_type_data')): ?>
                            <li>
                                <input type="checkbox" value="ctp_ui">
                                <label><?php esc_html_e('Post Type', 'akd-importer'); ?></label>
                            </li>
                            <?php endif;?>
                            <?php if(class_exists('ReduxFramework')): ?>
                                <li>
                                    <input type="checkbox" value="settings">
                                    <label><?php esc_html_e('Theme Options', 'akd-importer'); ?></label>
                                </li>
                            <?php endif;?>
                            <li>
                                <input type="checkbox" value="content">
                                <label><?php esc_html_e('Content', 'akd-importer'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="widgets">
                                <label><?php esc_html_e('Widgets', 'akd-importer'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="options">
                                <label><?php esc_html_e('WP Settings', 'akd-importer'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="files">
                                <label><?php esc_html_e('Layout Files', 'akd-importer'); ?></label>
                            </li>
                            <?php if(class_exists('RevSlider')): ?>
                                <li>
                                    <input type="checkbox" value="revslider">
                                    <label><?php esc_html_e('Slider Revolution', 'akd-importer'); ?></label>
                                </li>
                            <?php endif;?>
                            <?php do_action('akd-import-action-list-after'); ?>
                        </ul>
                        <?php echo $demo_action; ?>
                    </div>
                </div><!-- actions. -->
                <div class="akd-demo-process">
                    <div class="akd-process">
                        <span>0%</span>
                        <div></div>
                    </div>
                </div><!-- process bar. -->
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</div><!-- demo list. -->
<?php endif; ?>
<?php if($create_demo): ?>
<div class="redux-custom redux-notice-field redux-field-info" style="border-color:purple;">
    <p class="redux-info-desc"><b><?php esc_attr_e('Create new demo data (only for developer)', 'akd-importer'); ?></b><br><?php esc_attr_e('Set demo name and click to button, tool auto package demo.', 'akd-importer'); ?></p>
</div><!-- notice -->
<div class="akd-export-demo">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Demo Name (*)', 'akd-importer'); ?>
                        <span class="description"><?php esc_html_e('Enter demo slug (EXP : demo 1, demo-1, theme-name-demo-name)', 'akd-importer'); ?></span>
                    </div>
                </th>
                <td>
                    <fieldset class="redux-field-container redux-field redux-field-init redux-container-text ">
                        <input id="akd-demo-slug" type="text" value="" class="regular-text" placeholder="<?php esc_attr_e('demo-name', 'akd-importer'); ?>">
                    </fieldset>
                </td>
            </tr>
            <?php do_action('akd-export-action-before'); ?>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Data (*)', 'akd-importer'); ?>
                        <span class="description"><?php esc_html_e('Select data export.', 'akd-importer'); ?></span>
                    </div>
                </th>
                <td>
                <fieldset class="redux-field-container redux-field akd-export-types">
                        <input name="akd-export-type[]" type="checkbox" value="attachment" checked="checked">
                        <label><?php esc_html_e('Media', 'akd-importer'); ?></label>
                        <input name="akd-export-type[]" type="checkbox" value="widgets" checked="checked">
                        <label><?php esc_html_e('Widgets', 'akd-importer'); ?></label>
                    <?php if(class_exists('ReduxFramework')): ?>
                        <input name="akd-export-type[]" type="checkbox" value="settings" checked="checked">
                        <label><?php esc_html_e('Theme Options', 'akd-importer'); ?></label>
                    <?php endif;?>
                        <input name="akd-export-type[]" type="checkbox" value="options" checked="checked">
                        <label><?php esc_html_e('WP Settings', 'akd-importer'); ?></label>
                    <?php if(function_exists('cptui_get_post_type_data')): ?>
                        <input name="akd-export-type[]" type="checkbox" value="ctp_ui" checked="checked">
                        <label><?php esc_html_e('Post Type', 'akd-importer'); ?></label>
                    <?php endif;?>
                        <input name="akd-export-type[]" type="checkbox" value="content" checked="checked">
                        <label><?php esc_html_e('Content', 'akd-importer'); ?></label>
                    <?php if(class_exists('RevSlider')): ?>
                        <input name="akd-export-type[]" type="checkbox" value="revslider" checked="checked">
                        <label><?php esc_html_e('Slider Revolution', 'akd-importer'); ?></label>
                    <?php endif;?>
                    <?php if(akd_git_exists()): ?>
                        <input name="akd-export-type[]" type="checkbox" value="git" checked="checked">
                        <label><?php esc_html_e('Sync Git', 'akd-importer'); ?></label>
                    <?php endif; ?>
                    <?php do_action('akd-export-action-list-after'); ?>
                </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Export Demo', 'akd-importer'); ?><span class="spinner"></span>
                        <span class="description"><?php esc_html_e('Auto create demo files "your-theme/inc/demo-data/demo-name"', 'akd-importer'); ?></span>
                    </div>
                </th>
                <td>
                    <button type="button" class="button button-primary create-demo"><?php esc_html_e('Create Demo', 'akd-importer'); ?></button>
                    <button type="button" class="button button-primary download-demo"><?php esc_html_e('Download Demo', 'akd-importer'); ?></button>
                </td>
            </tr>
            <?php do_action('akd-export-action-after'); ?>
        </tbody>
    </table>
</div><!-- export demo -->
<?php endif; ?>
<div class="redux-custom redux-notice-field redux-field-info" style="border-color:#0099d5;">
    <p class="redux-info-desc"><b><?php esc_html_e('Setting Export & Import', 'akd-importer'); ?></b><br><?php esc_attr_e('Import and export settings Theme Options.', 'akd-importer'); ?></p>
</div><!-- notice -->
