<?php
/*
	Plugin Name: WP Domain Checker
	Plugin URI: http://asdqwe.net/wordpress-plugins/wp-domain-checker/
	Description: Check domain name availability for all Top Level Domains using shortcode or widget with Ajax search.
	Author: Asdqwe Dev
	Version: 5.0.2
	Author URI: http://asdqwe.net/wordpress-plugins/wp-domain-checker/
	Text Domain: wdc
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define('WDC_VERSION', '5.0.2');
define('WDC_DEBUG', 0);

require_once ('titan-framework/titan-framework-embedder.php');
require_once ('lib/whoisFunc.php');

add_action('init', 'wdc_load_textdomain');

function wdc_load_textdomain()
{
    load_plugin_textdomain('wdc', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

function wdc_load_styles()
{
    wp_register_style('wdc-styles-main', plugins_url('assets/style.css', __FILE__) , array() , WDC_VERSION);
    wp_register_style('wdc-styles-1', plugins_url('assets/css/main.css', __FILE__) , array() , WDC_VERSION);
    wp_register_style('wdc-styles-bootstrap', plugins_url('assets/css/bootstrap.min.css', __FILE__) , array() , WDC_VERSION);
    wp_register_style('wdc-styles-fas', plugins_url('assets/css/all.min.css', __FILE__) , array() , WDC_VERSION);
    wp_register_style('wdc-styles-extras', plugins_url('assets/bootstrap-flat-extras.css', __FILE__) , array() , WDC_VERSION);
    wp_register_style('wdc-styles-flat', plugins_url('assets/bootstrap-flat.css', __FILE__) , array() , WDC_VERSION);
    //wp_enqueue_style('wdc-styles-bootstrap');
    wp_enqueue_style('wdc-styles-1');
    wp_enqueue_style('wdc-styles-fas');
    wp_enqueue_style('wdc-styles-main');
    wp_enqueue_style('wdc-styles-extras');
    wp_enqueue_style('wdc-styles-flat');
}

function wdc_load_scripts()
{
    $titan = TitanFramework::getInstance('wdc-options');
    $buy_button_icon = $titan->getOption('additional_button_icon');
    $wdc_config_js = array('buy_button_icon' => $buy_button_icon ? '<i class="fas fa-'.$buy_button_icon.' wdc-icon-result"></i>' : '' );

    wp_register_script('wdc-script', plugins_url('assets/script.js', __FILE__) , array(
        'jquery'
    ) , WDC_VERSION, true);
    wp_localize_script('wdc-script', 'wdc_config_js', $wdc_config_js);
    wp_localize_script('wdc-script', 'wdc_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php', 'relative') ,
        'wdc_nonce' => wp_create_nonce('wdc_nonce')
    ));
    if (wp_script_is('wdc-google-recaptcha', 'registered'))
    {
        return;
    }
    else
    {
        wp_register_script('wdc-google-recaptcha', 'https://www.google.com/recaptcha/api.js?onload=wdcRecaptchaCallback&render=explicit', array() , '2.0');
    }
    wp_enqueue_script('wdc-script');

}
function wdc_add_async_attribute($tag, $handle)
{
    if ('wdc-google-recaptcha' !== $handle) return $tag;
    return str_replace(' src', ' async defer src', $tag);
}
add_filter('script_loader_tag', 'wdc_add_async_attribute', 10, 2);

add_action('wp_enqueue_scripts', 'wdc_load_styles');
add_action('wp_enqueue_scripts', 'wdc_load_scripts', 99);
add_action('admin_enqueue_scripts', 'wdc_load_styles', 99);
add_action('admin_enqueue_scripts', 'wdc_load_scripts', 99);

function wdc_load_custom_wp_admin_style($hook)
{

    // Load only on ?page=mypluginname
    if ($hook == 'wp-domain-checker_page_wdc-whois' || $hook == 'toplevel_page_wp-domain-checker')
    {

        wp_enqueue_style('wdc-admin-style', plugins_url('assets/wdc-admin-style.css', __FILE__) , array() , WDC_VERSION);
        wp_enqueue_script('wdc-admin-script', plugins_url('assets/wdc-admin-script.js', __FILE__) , array(
            'jquery'
        ) , WDC_VERSION);
        wp_localize_script('wdc-admin-script', 'wdc_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php', 'relative') ,
            'wdc_nonce' => wp_create_nonce('wdc_nonce')
        ));
    }
}
add_action('admin_enqueue_scripts', 'wdc_load_custom_wp_admin_style');

function wdc_load_fonts()
{
    wp_register_style('wdcGoogleFonts', '//fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Montserrat:700,regular|Source+Sans+Pro:regular,300&subset=latin,cyrillic-ext,latin-ext,cyrillic,vietnamese,greek,greek-ext');
    wp_enqueue_style('wdcGoogleFonts');

}

add_action('wp_print_styles', 'wdc_load_fonts');

function wdc_render_recaptcha()
{

?>
	<script type="text/javascript">
	var wdcRecaptchaCallback = function() {
    //var forms = document.getElementsByClassName('wdc-form');
	var forms = document.getElementsByTagName('form');
	var pattern = /(^|\s)g-recaptcha(\s|$)/;
	for (var i = 0; i < forms.length; i++) {
		var divs = forms[i].getElementsByTagName('div');
		for (var j = 0; j < divs.length; j++) {
            var form = forms[i];
            var sitekey = divs[j].getAttribute('data-sitekey');
            var theme = divs[j].getAttribute('data-theme');
		    var size = divs[j].getAttribute('data-size');
            var type = divs[j].getAttribute('data-type');
            var tabindex = divs[j].getAttribute('data-tabindex');
            var expired_callback = divs[j].getAttribute('data-expired-callback');
            var callback = divs[j].getAttribute('data-callback');

			if (divs[j].className && divs[j].className.match(pattern) && sitekey) {

                var widgetId = grecaptcha.render(divs[j], {
  					'sitekey': sitekey,
  					'theme': theme,
  					'type': type,
  					'size': size,
  					'tabindex': tabindex,
  					'expired-callback': expired_callback,
                    'callback': callback
				  });
          break;

		}
	}
}
};

	</script>
<?php
}

function wdc_display_price($domain, $product_id, $woo_custom_price = FALSE)
{
    global $woocommerce;
    $titan = TitanFramework::getInstance('wdc-options');
    $extensions = $titan->getOption('wdc_custom_price');
    if($woo_custom_price) $extensions = $woo_custom_price;
    //$product_id = $titan->getOption( 'additional_button_link' );
    if (get_post_meta($product_id, '_sale_price', true))
    {
        $price = get_post_meta($product_id, '_sale_price', true);
    }
    else
    {
        $price = get_post_meta($product_id, '_regular_price', true);
    }
    $currency = get_woocommerce_currency_symbol();
    $extensions = preg_replace('/\s+/', '', $extensions);
    $tlds = explode(',', $extensions);

    list($domain, $ext) = explode('.', $domain, 2);

    foreach ($tlds as $key => $value)
    {
        $tld = explode('|', $value);
        if (strtolower($ext) == strtolower($tld[0]))
        {
            $price = $tld[1];
        }
    }

    $currency_pos = get_option('woocommerce_currency_pos');

    switch ($currency_pos)
    {
        case 'left':
            $price_text = $currency . $price;
        break;
        case 'right':
            $price_text = $price . $currency;
        break;
        case 'left_space':
            $price_text = $currency . ' ' . $price;
        break;
        case 'right_space':
            $price_text = $price . ' ' . $currency;
        break;
    }
    return array('price' => $price, 'currency' => $currency, 'default_price_text' => $price_text);
}

function wdc_check_domain()
{
    //check_ajax_referer( 'wdc_nonce', 'security' );
    $titan = TitanFramework::getInstance('wdc-options');
    $multi_tlds_enable = $titan->getOption('wdc_multi_tlds_check');
    $multi_tlds = $titan->getOption('wdc_multi_tlds');
    $multi_tlds = preg_replace('/\s+/', '', $multi_tlds);
    if (isset($_POST['domain']))
    {
        $domain = sanitize_text_field($_POST['domain']);
        $domain = str_replace(array(
            'www.',
            'http://',
            'https://',
            '/',
            '"',
            '\\',
            '\''
        ) , NULL, sanitize_text_field($_POST['domain']));
        if (strpos($domain, '.') == false)
        {

            if ($_POST['tld'] != '')
            {
                $multi_tlds = $_POST['tld'];
            }

            if ($multi_tlds == '' || !$multi_tlds_enable)
            {
                $multi_tlds = array(
                    'com'
                );
            }
            else
            {
                $multi_tlds = explode(',', $multi_tlds);
            }

        }elseif ($multi_tlds_enable) {
            if ($_POST['tld'] != '')
            {
                $multi_tlds = $_POST['tld'];
            }

            if ($multi_tlds == '' && strpos($domain, '.') == false)
            {
                $multi_tlds = array(
                    'com'
                );
            }
            else
            {
                $multi_tlds = explode(',', $multi_tlds);
            }

            if (strpos($domain, '.') == true){
                list($sp, $split) = explode('.', $domain, 2);
                if(in_array($split, $multi_tlds)){
                    $multi_tlds = array_diff($multi_tlds, array($split));
                }
                array_unshift($multi_tlds, $split);
            }
        }
        else
        {
            list($sp, $split) = explode('.', $domain, 2);
            $multi_tlds = array(
                ($split)
            );
        }

        if (function_exists('idn_to_ascii'))
        {
            $punny_domain = wdc_idn_to_ascii($domain);
            $ascii_domain = $domain;
            $ascii_tld = array_map('wdc_idn_to_ascii', $multi_tlds);
        }
        else
        {
            $ascii_domain = $domain;
            $punny_domain = $domain;
            $ascii_tld = $multi_tlds;
            //$ascii_domain = preg_replace("/[^-a-zA-Z0-9.]+/", "", $ascii_domain);

        }
        //$ascii_domain = preg_replace("/[^-a-zA-Z0-9.]+/", "", $ascii_domain);
        if (strlen($ascii_domain) > 0)
        {

            if (strpos($punny_domain, '.') == true)
            {
                list($dom, $ext) = explode('.', $punny_domain, 2);
            }
            else
            {
                $dom = $punny_domain;
            }
            if (strpos($ascii_domain, '.') == true)
            {
                list($dom2, $ext) = explode('.', $ascii_domain, 2);
            }
            else
            {
                $dom2 = $ascii_domain;
            }

            $domains = array(
                'domain' => $dom,
                'idn' => $dom2,
                'tld' => $multi_tlds,
                'ascii_tld' => $ascii_tld
            );
            wp_send_json_success($domains);

        }
    }
    wp_die();
}
add_action('wp_ajax_wdc_check_domain', 'wdc_check_domain');
add_action('wp_ajax_nopriv_wdc_check_domain', 'wdc_check_domain');

function wdc_display_func()
{
    //check_ajax_referer( 'wdc_nonce', 'security' );
    global $woocommerce;
    $titan = TitanFramework::getInstance('wdc-options');
    $whois = $titan->getOption('whois_option');
    $whois_custom_url = $titan->getOption('whois_custom_url');
    $whois_button_text = $titan->getOption('whois_button_text');
    $show_price = $titan->getOption('show_price');
    $whois_new_tab = $titan->getOption('_blank1_option');
    $buy_new_tab = $titan->getOption('_blank2_option');
    $ajax = $titan->getOption('ajax_option');
    $integration = $titan->getOption('integration');
    $extensions = $titan->getOption('extensions');
    $extensions = preg_replace('/\s+/', '', $extensions);
    $multi_tlds = $titan->getOption('wdc_multi_tlds');
    $multi_tlds = preg_replace('/\s+/', '', $multi_tlds);
    $ext_message = $titan->getOption('ext_message');
    $whois_notfound_text = $titan->getOption('whois_notfound_text');
    $additional_button_name = $titan->getOption('additional_button_name');
    $additional_button_link = $titan->getOption('additional_button_link');
    $custom_found_result_texts = $titan->getOption('custom_found_result_text');
    if ($custom_found_result_texts == '') $custom_found_result_texts = __('{domain}', 'wdc');
    $custom_not_found_result_texts = $titan->getOption('custom_not_found_result_text');
    if ($custom_not_found_result_texts == '') $custom_not_found_result_texts = __('{domain}', 'wdc');
    if ($ext_message == '') $ext_message = __('Sorry, we currently do not handle that particular tld.', 'wdc');
    if ($whois_button_text == '') $whois_button_text = __('WHOIS', 'wdc');
    // if ($additional_button_name == '') $additional_button_name = __('BUY', 'wdc');
    $priceText = FALSE;
    $woo_custom_price = FALSE;

    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'integration':
                $integration = $value;
                break;
            case 'domain':
                $domain_ascii = $value;
                break;
            case 'idn':
                $domain = $value;
                break;
            case 'allowed_tld':
                $allowed_tld = $value;
                break;
            case 'item_id': //deprecated
                $additional_button_link = $value;
                break;
            case 'url':
                $additional_button_link = $value;
                break;
            case 'available_msg':
                $custom_found_result_texts = $value;
                break;
            case 'unavailable_msg':
                $custom_not_found_result_texts = $value;
                break;
            case 'whois_button':
                $whois = $value;
                break;
            // case 'custom_price': pending on implementation due security reasons
            //     $woo_custom_price = $value;
            //     break;
        }
    }

    list($dom, $ex) = explode('.', $domain, 2);

    $args = array('integration' => $integration, 'domain' => $domain, 'tld' => $ex, 'sld' => $dom, 'item_id' => $additional_button_link );
    $wdc_domain_filter = apply_filters( 'wdc_domain_filter', $args );
    $domain = $wdc_domain_filter['domain'];
    $dom = $wdc_domain_filter['sld'];
    $ex = $wdc_domain_filter['tld'];
    $additional_button_link = $wdc_domain_filter['item_id'];
    $integration = $wdc_domain_filter['integration'];

    if ( function_exists( 'parse_ini_string' ) ) {
        $wdc_config = parse_ini_string($titan->getOption('wdc_config'),true);
    }else{
        $wdc_config = wdc_parse_ini_string_m($titan->getOption('wdc_config'),true);
    }
    if($wdc_config){
        $domainLimitChr = $wdc_config['DomainMinLengthRestrictions'];
        if (array_key_exists($ex, $domainLimitChr) || array_key_exists('all', $domainLimitChr) ){
            if(array_key_exists($ex, $domainLimitChr)){
                $domainLimitLength = $domainLimitChr[$ex];
            }else{
                $domainLimitLength = $domainLimitChr['all'];
            }
            if (strlen($dom) < $domainLimitLength )
            {
                if(array_key_exists("errmsg",$domainLimitChr) && $domainLimitChr['errmsg'] !== ''){
                    $errmsg = str_replace(array(
                        '{length}',
                        '{sld}',
                        '{tld}'
                    ) , array(
                        $domainLimitLength,
                        $dom,
                        $ex
                    ) , $domainLimitChr['errmsg']);
                }else{
                    $errmsg = 'Minimum length of a .'.$ex.' domain name is '.$domainLimitLength.' characters.';
                }

                wdc_send_result_json($integration, 3, $domain, $ex, '', $errmsg, '', '');
                wp_die();
            }
        }

        $priceFormatConfig = $wdc_config['PriceFormat'];
        if (array_key_exists('text', $priceFormatConfig) ){
            $priceText = __($priceFormatConfig['text'], 'wdc');
        }
    }

    if ($ex == 'de') $domain_ascii = $domain;
    $Domains = new wdcWhoisFunc();
    $available = json_decode($Domains->is_available($domain_ascii));

    $custom_found_result_text = str_replace(array(
        '{domain}',
        '{sld}',
        '{tld}'
    ) , array(
        $domain,
        $dom,
        $ex
    ) , $custom_found_result_texts);

    if ($allowed_tld != '')
    {
        $extensions = $allowed_tld;
    }
    if ($extensions != '')
    {

        $tlds = explode(',', $extensions);
        // $tlds = array_map('wdc_idn_to_ascii', $tlds);
        if (!in_array($ex, $tlds))
        {

            wdc_send_result_json('', 3, $domain, $ex, '', __($ext_message, 'wdc'), '', '');

            wp_die();
        }
    }
    if ($whois !== 'disable')
    {
        if ($whois_custom_url != '' && $whois == 'custom')
        {
            $whois_custom_url = str_replace(array(
                '{domain}',
                '{sld}',
                '{tld}'
            ) , array(
                $domain,
                $dom,
                $ex
            ) , $whois_custom_url);
            $whoiss = $whois_custom_url;
        }
        else
        {
            $whoiss = wp_nonce_url(get_permalink($whois) . "?&domain=$domain_ascii", 'wdc_whois_page', '_wpnonce');
        }

        $whois_link = array('url' => $whoiss, 'newtab' => $whois_new_tab, 'text' =>  __($whois_button_text, 'wdc') );
        //"<a data-domain='$domain' href='" . $whoiss . "' " . $whois_new_tab . " ><button id='whois' class='btn btn-danger btn-xs pull-right whois-btn'>" . __($whois_button_text, 'wdc') . "</button></a>";
    }
    else
    {
        $whois_link = array('class' => 'hide' );
    }

    if ($integration == 'whmcs' or $integration == 'whmcs_bridge')
    {
        $check_ex = explode('.', $ex);

        if (count($check_ex) == 2)
        {
            $ex_name = $check_ex[0] . "_" . $check_ex[1];
        }
        else
        {
            $ex_name = $check_ex[0];
        }
        $ex_name = str_replace('-', '_', $ex_name);

        $additional_button = array('url' => "javascript:void(0)", 'newtab' => false, 'text' => __($additional_button_name, 'wdc'), 'product_id' => '', 'price' => '', 'class' => '', 'attribute' => "onclick=\"submitform_{$dom}_{$ex_name}(this)\"" );
        //"<a href='javascript:void(0)' onclick='submitform_$dom_$ex_name()' ><button id='buy' class='btn btn-success btn-xs pull-right order-btn'>" . __($additional_button_name, 'wdc') . "</button></a>";
    }
    elseif ($integration == 'woocommerce')
    {
        if ($show_price)
        {
            $getPrice = wdc_display_price($domain, $additional_button_link, $woo_custom_price);
            if($priceText){
                $priceText = str_replace(array('{currency}','{price}'), array($getPrice['currency'], $getPrice['price']), $priceText);
            }else{
                $priceText = ' ' . $getPrice['default_price_text'] . __('/year', 'wdc');
            }
        }
        $cart_url = do_shortcode("[add_to_cart_url id='$additional_button_link']");

        if ($ajax)
        {
            $class = 'add_to_cart_button ajax_add_to_cart';
        }
        else
        {
            $class = '';
        }
        $additional_button = array('url' => "{$cart_url}&domain={$domain}", 'newtab' => $buy_new_tab, 'text' => __($additional_button_name, 'wdc'), 'product_id' => $additional_button_link, 'price' => $priceText, 'class' => "$class", 'attribute' => '' );
        //"<a href='$cart_url&domain=$domain' data-product_id='$additional_button_link' data-domain='$domain' id='buy' class='$ajax btn btn-success btn-xs pull-right order-btn' $buy_new_tab >" . __($additional_button_name, 'wdc') . " $show_price</a>";
    }
    elseif ($integration == 'whm_press')
    {
        $addedtocart_msg =  __('Added to cart', 'wdc');
        $error_msg =  __('Error', 'wdc');
        $check_ex = explode('.', $ex);

        if (count($check_ex) == 2)
        {
            $ex_name = $check_ex[0] . "_" . $check_ex[1];
        }
        else
        {
            $ex_name = $check_ex[0];
        }
        $ex_name = str_replace('-', '_', $ex_name);

        $additional_button = array('url' => "javascript:void(0)", 'newtab' => false, 'text' => __($additional_button_name, 'wdc'), 'product_id' => $additional_button_link, 'price' => '', 'class' => '', 'attribute' => 'data-add-to-cart-msg="'.$addedtocart_msg.'" data-error-msg="'.$error_msg.'" onclick="wdc_add_to_cart_whmpress(this,\''.$domain.'\')"' );
        //"<a href=\"javascript:void(0)\" ><button id=\"buy\" data-add-to-cart-msg=\"{$addedtocart_msg}\" data-error-msg=\"{$error_msg}\" class=\"btn btn-success btn-xs pull-right order-btn\" onclick=\"wdc_add_to_cart_whmpress(this,'$domain')\">" . __($additional_button_name, 'wdc') . "</button></a>";
    }
    elseif ($integration == 'custom')
    {
        if ($additional_button_name != '' && $additional_button_link != '')
        {
            $additional_button_links = str_replace(array(
                '{domain}',
                '{sld}',
                '{tld}'
            ) , array(
                $domain,
                $dom,
                $ex
            ) , $additional_button_link);
            $additional_button =  array('url' => "{$additional_button_links}", 'newtab' => $buy_new_tab, 'text' => __($additional_button_name, 'wdc'), 'product_id' => '', 'price' => '', 'class' => '', 'attribute' => '' );
            //"<a id='buy' class='btn btn-success btn-xs pull-right order-btn' href='$additional_button_links' $buy_new_tab >" . __($additional_button_name, 'wdc') . "</a>";
        }
        else
        {
            $additional_button =  array('url' => "", 'newtab' => '', 'text' => '', 'product_id' => '', 'price' => '', 'class' => 'hide', 'attribute' => '' );
        }
    }
    else
    {
        $additional_button =  array('url' => "", 'newtab' => '', 'text' => '', 'product_id' => '', 'price' => '', 'class' => 'hide', 'attribute' => '' );
    }

    $custom_not_found_result_text = str_replace(array(
        '{domain}',
        '{sld}',
        '{tld}'
    ) , array(
        $domain,
        $dom,
        $ex
    ) , $custom_not_found_result_texts);
    if ($integration == 'whmcs' or $integration == 'whmcs_bridge')
    {
        if ($integration == 'whmcs')
        {
            $param = 'cart.php?a=add&domain=register';
        }
        else
        {
            $param = '?ccce=cart&a=add&domain=register';
        }
        if($buy_new_tab){
            $buy_new_tab = "target=_blank";
        }else{
            $buy_new_tab = "target=_self";
        }
        $whmcs = "<script type='text/javascript'>
				function submitform_{$dom}_{$ex_name}(el)
				{
                    var form = jQuery(el.parentNode).find('form');
                    form[0].submit();
				}
				</script>
				<form style='display:none' method='post' name='whmcs_{$dom}_{$ex_name}' id='whmcs' action='$additional_button_link/$param' $buy_new_tab>
				<input type='hidden' name='domains[]' value='$domain' >
				<input type='hidden' name='domainsregperiod[$domain]'>
				</form>";

    }
    else
    {
        $whmcs = '';
    }
    if ($available->status == 1)
    {
        wdc_send_result_json($integration, 1, $domain, $ex, $available->protocol, $custom_found_result_text, $additional_button, $whmcs, $available->response);
    }
    elseif ($available->status == 0)
    {
        wdc_send_result_json($integration, 0, $domain, $ex, $available->protocol, $custom_not_found_result_text, $whois_link, '', $available->response);
    }
    elseif ($available->status == 2)
    {
        if($whois_notfound_text != ""){
           $whois_notfound_text = str_replace(array(
                '{domain}',
                '{sld}',
                '{tld}'
            ) , array(
                $domain,
                $dom,
                $ex
            ) , $whois_notfound_text);
            $custom_text = $whois_notfound_text;
        }else{
            $custom_text = sprintf( __( 'WHOIS server not found for <strong>.%s</strong> TLD', 'wdc' ), $ex );//__('WHOIS server not found for  <strong>.' . $ex . '</strong> TLD', 'wdc');
        }
        wdc_send_result_json($integration, 2, $domain, $ex, $available->protocol, $custom_text, '', '');
    }

    wp_die();

}

add_action('wp_ajax_wdc_display', 'wdc_display_func');
add_action('wp_ajax_nopriv_wdc_display', 'wdc_display_func');

function wdc_send_result_json($integration = NULL, $status = NULL, $domain = NULL, $tld = NULL, $protocol = NULL, $result_text = NULL, $additional_button = NULL, $whmcs = NULL, $response = NULL){
    if(!WDC_DEBUG){
        $response = NULL;
    }
    $result = array(
        'integration' => $integration,
        'status' => $status,
        'domain' => $domain,
        'tld' => $tld,
        'protocol' => $protocol,
        'result_text' => $result_text,
        'additional_button' => $additional_button,
        'whmcs' => $whmcs,
        'debug' => $response
    );
    wp_send_json_success($result);
}

function wdc_display_dashboard()
{
    echo do_shortcode('[wpdomainchecker]');
}

function wdc_add_dashboard_widgets()
{

    wp_add_dashboard_widget('wdc_dashboard_widget', 'WP Domain Checker', 'wdc_display_dashboard'
);
}
add_action('wp_dashboard_setup', 'wdc_add_dashboard_widgets');

function wdc_whois_shortcode()
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'wdc_whois_page'))
    {
        return;
    }
    if (isset($_GET['domain']))
    {
        $get_domain = sanitize_text_field($_GET['domain']);
        $re = '/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/m';
        preg_match_all($re, $get_domain, $domain, PREG_SET_ORDER, 0);
        if(!$domain){
            return;
        }
        $domain = $domain[0][0];
        $text = '<h3>' . __('Whois record for', 'wdc') . ' <b>' . $domain . '</b></h3>';

        $whois = new wdcWhoisFunc();
        $text .= "<pre>";

        if (function_exists('idn_to_ascii'))
        {
            $text .= $whois->whois_lookup_details(wdc_idn_to_ascii($domain));
        }
        else
        {
            $text .= $whois->whois_lookup_details($domain);
        }

        $text .= "</pre>";
        return $text;
    }

}
add_shortcode('wpdomainwhois', 'wdc_whois_shortcode');

function wdc_display_shortcode($atts)
{
    // wp_enqueue_style('wdc-styles-bootstrap');
    // wp_enqueue_style('wdc-styles-1');
    // wp_enqueue_style('wdc-styles-fas');
    // wp_enqueue_style('wdc-styles-main');
    // wp_enqueue_style('wdc-styles-extras');
    // wp_enqueue_style('wdc-styles-flat');
    // wp_enqueue_script('wdc-script');

    $titan = TitanFramework::getInstance('wdc-options');
    $item_id = $titan->getOption('additional_button_link');
    $image = $titan->getOption('loading_image');
    $recaptcha_enable = $titan->getOption('recaptcha');
    $placeholder = $titan->getOption('input_placeholder');
    $recaptcha_sitekey = $titan->getOption('recaptcha_sitekey');
    $recaptcha_invis = $titan->getOption('recaptcha_invis');
    $custom_found_result_texts = $titan->getOption('custom_found_result_text');
    $custom_not_found_result_texts = $titan->getOption('custom_not_found_result_text');
    $integration = $titan->getOption('integration');
    $req_domain_text = $titan->getOption('req_domain_text');
    $req_recaptcha_text = $titan->getOption('req_recaptcha_text');
    $whois_button = $titan->getOption('whois_option');
    $custom_price = $titan->getOption('wdc_custom_price');

    $text = array(
        'req_domain_text' => __('Please enter your domain.', 'wdc') ,
        'req_recaptcha_text' => __('Please verify that you are not a robot.', 'wdc')
    );

    if($req_domain_text != ""){
        $text['req_domain_text'] = $req_domain_text;
    }

    if($req_recaptcha_text != ""){
        $text['req_recaptcha_text'] = $req_domain_text;
    }

    if ($image == '')
    {
        $image = plugins_url('/images/load.gif', __FILE__);
    }
    else
    {
        $image = wp_get_attachment_image_src($image);
        $image = $image[0];
    }
    $atts = shortcode_atts(array(
        'width' => '900',
        'button' => __('Search', 'wdc') ,
        'recaptcha' => 'no',
        'item_id' => $item_id,
        'url' => '',
        'tld' => '',
        'size' => 'large',
        'class' => '',
        'allowed_tld' => '',
        'style' => '1',
        'available_msg' => $custom_found_result_texts,
        'unavailable_msg' => $custom_not_found_result_texts,
        'integration' => $integration,
        'whois_button' => $whois_button,
        'custom_price' => $custom_price
    ) , $atts);
    if ($atts['recaptcha'] == 'yes')
    {
        wp_enqueue_script('wdc-google-recaptcha');
        add_action('wp_footer', 'wdc_render_recaptcha');
        if (!$recaptcha_invis)
        {
            $show_recaptcha = '<div id="wdc-recaptcha" class="g-recaptcha" data-sitekey="' . $recaptcha_sitekey . '" style="margin:10px auto 0;max-width:'.$atts['width'].'px;"></div>
            <noscript>
              <div>
                <div style="width: 302px; height: 422px; position: relative;">
                  <div style="width: 302px; height: 422px; position: absolute;">
                    <iframe src="https://www.google.com/recaptcha/api/fallback?k=' . $recaptcha_sitekey . '"
                            frameborder="0" scrolling="no"
                            style="width: 302px; height:422px; border-style: none;">
                    </iframe>
                  </div>
                </div>
                <div style="width: 300px; height: 60px; border-style: none;
                               bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
                               background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
                  <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                               class="g-recaptcha-response"
                               style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
                                      margin: 10px 25px; padding: 0px; resize: none;" >
                  </textarea>
                </div>
              </div>
            </noscript>';
        }
        else if ($recaptcha_invis)
        {
            $show_recaptcha = '<div id="wdc-recaptcha" class="g-recaptcha" data-callback="wdcInvisrecaptchaCallback" data-size="invisible" data-sitekey="' . $recaptcha_sitekey . '"></div>';
        }
    }
    else
    {
        $show_recaptcha = "";
    }
    $default_content = "<div id='wdc-domain-form' class='wdc-form {$atts['class']}' data-wdc-style='{$atts['style']}'>
        <div id='wdc-style' >
            <form method='post' action='' id='form' class='pure-form' data-wdc-style='{$atts['style']}'>

                <input type='hidden' name='integration' value='{$atts['integration']}'>
                ".($atts['available_msg'] ? "<input type='hidden' name='available_msg' value='{$atts['available_msg']}'>" : "<input type='hidden' name='available_msg' value='".__("{domain} is available", "wdc")."'>")."
                ".($atts['unavailable_msg'] ? "<input type='hidden' name='unavailable_msg' value='{$atts['unavailable_msg']}'>" : "<input type='hidden' name='unavailable_msg' value='".__("{domain} is unavailable", "wdc")."'>" )."
                <input type='hidden' name='item_id' value='{$atts['item_id']}'>
                <input type='hidden' name='url' value='{$atts['url']}'>
                <input type='hidden' name='allowed_tld' value='{$atts['allowed_tld']}'>
                <input type='hidden' name='tld' value='{$atts['tld']}'>
                <input type='hidden' name='whois_button' value='{$atts['whois_button']}'>
                <input type='hidden' name='custom_price' value='{$atts['custom_price']}'>
                <div class='input-group {$atts['size']}' style='max-width:{$atts["width"]}px;'>
                    <input type='text' class='form-control' autocomplete='off' id='Search' name='domain' placeholder='" . __($placeholder, 'wdc') . "'>
                        <span class='input-group-btn'>
                        <button type='submit' id='Submit' class='btn btn-default btn-info'>{$atts["button"]}</button>
                        </span>
                </div>
            {$show_recaptcha}
            <div id='loading'><img src='$image' alt='loading'></img></div>
        </form>
    <div style='max-width:{$atts["width"]}px;'>
            <div id='results' class='result {$atts['size']}'>
            <div class='callout callout-warning alert-warning clearfix req-domain-text'><div class='col-xs-10' style='padding-left:1px;text-align:left;'><i class='glyphicon glyphicon-remove' style='margin-right:10px;'></i> {$text['req_domain_text']}</div></div>
            <div class='callout callout-warning alert-warning clearfix recaptcha-text'><div class='col-xs-10' style='padding-left:1px;text-align:left;'><i class='glyphicon glyphicon-remove' style='margin-right:10px;'></i> {$text['req_recaptcha_text']}</div></div>
            </div>

    </div>
        </div>
    </div>";

    if($atts['style'] == 1){
        $content = $default_content;
    }else if($atts['style'] == 2){
        $content = '
        <div id="wdc-domain-form" class="wdc-form" data-wdc-style="'.$atts["style"].'">
        <div class="wdc-search-wrapper">
    			<div class="wdc-search-one '.$atts['class'].'" style="max-width:'.$atts["width"].'px;">

    				<form method="post" action="" id="form" class="wdc-search-form text-center" data-wdc-style="'.$atts["style"].'">
                    <input type="hidden" name="integration" value="'.$atts['integration'].'">
                    <input type="hidden" name="available_msg" value="'.$atts['available_msg'].'">
                    <input type="hidden" name="unavailable_msg" value="'.$atts['unavailable_msg'].'">
                    <input type="hidden" name="item_id" value="'.$atts["item_id"].'">
                    <input type="hidden" name="url" value="'.$atts["url"].'">
        			<input type="hidden" name="allowed_tld" value="'.$atts["allowed_tld"].'">
                    <input type="hidden" name="tld" value="'.$atts["tld"].'">
                    <input type="hidden" name="whois_button" value="'.$atts["whois_button"].'">
        			<input type="hidden" name="custom_price" value="'.$atts["custom_price"].'">
    				  	<div class="wdc-search-content">
    				  		<div class="wdc-search-input__field">
    				  			<label class="wdc-search-input__label">
    				  				<input id="Search" type="search" name="domain" id="query" autocomplete="off" placeholder="'.__($placeholder, 'wdc').'" class="wdc-input">
    				  			</label>
    				  			<button id="Submit" class="wdc-button wdc-search-button" name="button" type="submit">'.$atts["button"].'</button>
    				  		</div>
    				  	</div>
                        '.$show_recaptcha.'
    				</form>
                    <span class="wdc-heading-query req-domain-text text-center">'.$text["req_domain_text"].'</span>
                    <span class="wdc-heading-query recaptcha-text text-center">'.$text["req_recaptcha_text"].'</span>
                    <div class="wdc-heading">

                        <h3 class="wdc-heading-result">'.__('Search results for','wdc').'
                        <span class="wdc-heading-query"></span>

                        </h3>

                    </div>
                    <div class="wdc-action">
                            <div id="loading" class="wdc-spinner">'.__('Loading results','wdc').'</div>
                    </div>
                    <div class="wdc-container mt-5 mb-5">
				    <h3 class="wdc-result-subhead">Domain Names</h3>

					<div class="wdc-domain-result"><div id="results"></div></div>

                    </div>
                </div>
    		</div>
            </div>
    	';
    }else{
        $content = $default_content;
    }

    return $content;

}

add_shortcode('wpdomainchecker', 'wdc_display_shortcode');

/* Woocommerce Function */
add_filter('product_type_options', 'add_wdc_product_option');
function add_wdc_product_option($product_type_options)
{
    $product_type_options['wdc_custom_option'] = array(
        'id' => '_wdc_custom_option',
        'wrapper_class' => 'show_if_simple show_if_variable',
        'label' => __('WDC', 'woocommerce') ,
        'description' => __('', 'woocommerce') ,
        'default' => 'no'
    );

    return $product_type_options;
}

add_action("save_post_product", function ($post_ID, $product, $update)
{

    update_post_meta($product->ID, "_wdc_custom_option", isset($_POST["_wdc_custom_option"]) ? "yes" : "no");

}
, 10, 3);

function wdc_get_product_id($product)
{

    if (version_compare(WC_VERSION, '2.7', '<'))
    {

        // vesion less then 2.7
        return $product->ID;
    }
    else
    {

        return $product->get_id();
    }
}

function wdc_custom_add_to_cart_redirect($wc_get_cart_url)
{
    if (isset($_REQUEST['domain']))
    {
        return wc_get_cart_url();
    }
    else
    {
        return $wc_get_cart_url;
    }
}
add_filter('woocommerce_add_to_cart_redirect', 'wdc_custom_add_to_cart_redirect');

function save_name_on_wdc_field($cart_item_key, $product_id = null, $quantity = null, $variation_id = null, $variation = null)
{

    if (isset($_REQUEST['domain']))
    {

        $domain = str_replace(array(
            'www.',
            'http://',
            'https://',
            '/',
            '"',
            '\\',
            '\''
        ) , NULL, sanitize_text_field($_REQUEST['domain']));
        $domains = explode(',', $domain);
        if(strpos($domain, '.') !== false){
            foreach ($domains as $key => $value) {
                if(!empty($value)){
                    WC()->session->set( $cart_item_key.'_domain', $value);
                }
            }
        }
        // return WC()
        //     ->session
        //     ->set($cart_item_key . '_domain', $domain);
        //WC()->session->set( $cart_item_key.'_price', $_GET['price'] );

    }
}
add_action('woocommerce_add_to_cart', 'save_name_on_wdc_field', 1, 5);

add_action('woocommerce_before_calculate_totals', 'wdc_add_custom_price');

function wdc_add_custom_price($cart_object)
{
    //  error_log($cart_item['product_id']);
    //error_log(wc_get_formatted_cart_item_data( $cart_item ));
    $titan = TitanFramework::getInstance('wdc-options');

    global $woocommerce;
    $tld = array();
    $extensions = $titan->getOption('wdc_custom_price');
    $wdc_product_id = $titan->getOption('additional_button_link');
    $extensions = preg_replace('/\s+/', '', $extensions);
    $tlds = explode(',', $extensions);
    foreach ($woocommerce
        ->cart
        ->get_cart() as $cart_item_key => $cart_item)
    {

        if ($cart_item['product_id'] == $wdc_product_id || get_post_meta($cart_item['product_id'], '_wdc_custom_option'))
        {
            if (class_exists('WC_Subscriptions_Product'))
            {
                if (WC_Subscriptions_Product::is_subscription($cart_item['product_id']))
                {
                    if (array_key_exists("subscription_initial_payment", $cart_item))
                    {
                        if (array_key_exists("Domain", $cart_item['subscription_initial_payment']['custom_line_item_meta']))
                        {
                            $domain = $cart_item['subscription_initial_payment']['custom_line_item_meta']['Domain'];
                            list($domain, $ext) = explode('.', $domain, 2);
                            foreach ($tlds as $key => $value)
                            {
                                $tld = explode('|', $value);
                                if (strtolower($ext) == strtolower($tld[0]))
                                {
                                    $price = $tld[1];
                                    $cart_item['data']->set_price($price);
                                }
                            }
                        }
                    }
                }
            }

            if (WC()
                ->session
                ->get($cart_item_key . '_domain'))
            {
                $domain = WC()
                    ->session
                    ->get($cart_item_key . '_domain');
                if (strpos($domain, '.') !== false) {
                    list($domain, $ext) = explode('.', $domain, 2);

                    foreach ($tlds as $key => $value)
                    {
                        $tld = explode('|', $value);
                        if (strtolower($ext) == strtolower($tld[0]))
                        {
                            $price = $tld[1];
                            $cart_item['data']->set_price($price);
                        }
                    }
                }
            }
        }
    }
}

function render_meta_on_cart_item($title = null, $cart_item = null, $cart_item_key = null)
{
    global $product_id;
    $titan = TitanFramework::getInstance('wdc-options');
    $wdc_product_id = $titan->getOption('additional_button_link');

    if ($cart_item_key && is_cart())
    {
        if ($cart_item['product_id'] == $wdc_product_id || get_post_meta($cart_item['product_id'], '_wdc_custom_option'))
        {
            if (WC()
                ->session
                ->get($cart_item_key . '_domain'))
            {

                $title = $title . '<dl class="">
    				 <dt class="">' . __('Domain :', 'wdc') . ' </dt>
    				 <dd class=""><p>' . WC()
                    ->session
                    ->get($cart_item_key . '_domain') . '</p></dd>
    			  </dl>';
            }
            else
            {
                //echo $title;

            }
        }
    }
    else
    {
        //echo $title;

    }
    return $title;
}
add_filter('woocommerce_cart_item_name', 'render_meta_on_cart_item', 1, 3);

function render_meta_on_checkout_order_review_item($quantity = null, $cart_item = null, $cart_item_key = null)
{
    $titan = TitanFramework::getInstance('wdc-options');
    $wdc_product_id = $titan->getOption('additional_button_link');

    if ($cart_item_key)
    {
        if ($cart_item['product_id'] == $wdc_product_id || get_post_meta($cart_item['product_id'], '_wdc_custom_option'))
        {
            if (WC()
                ->session
                ->get($cart_item_key . '_domain'))
            {

                $quantity = $quantity . '<dl class="">
                   <dt class="">' . __('Domain :', 'wdc') . ' </dt>
                   <dd class=""><p>' . WC()
                    ->session
                    ->get($cart_item_key . '_domain') . '</p></dd>
                </dl>';
            }
            else
            {
                //echo $title;

            }
        }
    }
    return $quantity;
}
add_filter('woocommerce_checkout_cart_item_quantity', 'render_meta_on_checkout_order_review_item', 1, 3);

function wdc_order_meta_handler($item_id, $values, $cart_item_key)
{

    if ($values->legacy_cart_item_key)
    {
        if (WC()
            ->session
            ->get($values->legacy_cart_item_key . '_domain'))
        {
            wc_add_order_item_meta($item_id, "Domain", WC()
                ->session
                ->get($values->legacy_cart_item_key . '_domain'));
        }
    }

}
add_action('woocommerce_new_order_item', 'wdc_order_meta_handler', 1, 3);

// Remove "Domain:" meta label
// function wdc_filter_woocommerce_display_item_meta( $html, $item, $args ) {
//     // make filter magic happen here...
//     $html = preg_replace('/<p>(.*?)<\/p>/i','<strong>$1</strong>',$html);
//    $string = preg_replace ("/<strong.*?class=\"\wc-item-meta-label\"\>(.*?)<\/strong>/i", "", $html);
//     error_log(print_r($string,true));
//     return $string;
// };
// add_filter( 'woocommerce_display_item_meta', 'wdc_filter_woocommerce_display_item_meta', 10, 3 );
function wdc_force_individual_cart_items($cart_item_data, $product_id)
{
    $titan = TitanFramework::getInstance('wdc-options');
    $id = $titan->getOption('additional_button_link');
    $unique_cart_item_key = md5(microtime() . rand());
    $cart_item_data['unique_key'] = $unique_cart_item_key;

    return $cart_item_data;

}
add_filter('woocommerce_add_cart_item_data', 'wdc_force_individual_cart_items', 10, 2);

add_filter('woocommerce_loop_add_to_cart_link', 'wdc_replace_add_to_cart', 10, 2);
function wdc_replace_add_to_cart($links, $product)
{

    if (get_post_meta(wdc_get_product_id($product) , 'wdc_hide_addtocart', true) == 'yes')
    {
        $links = '';
    }
    return $links;

}

function wdc_remove_cart_button()
{
    $product_id = get_the_ID();
    if (get_post_meta($product_id, 'wdc_hide_addtocart', true) == 'yes')
    {
        //remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
}
add_action('wp', 'wdc_remove_cart_button');

function wdc_woo_add_custom_general_fields()
{

    global $woocommerce, $post;

    echo '<div class="options_group">';

    woocommerce_wp_checkbox(array(
        'id' => 'wdc_hide_addtocart',
        'wrapper_class' => 'wdc_item_edit_class',
        'label' => __('WDC?', 'wdc') ,
        'description' => __('Check me if you want to hide Add to Cart button on single product page.', 'wdc')
    ));

    echo '</div>';

}

function wdc_woo_add_custom_general_fields_save($post_id)
{
    $woocommerce_checkbox = isset($_POST['wdc_hide_addtocart']) ? 'yes' : 'no';
    update_post_meta($post_id, 'wdc_hide_addtocart', $woocommerce_checkbox);
}

// Display Fields
add_action('woocommerce_product_options_general_product_data', 'wdc_woo_add_custom_general_fields');

// Save Fields
add_action('woocommerce_process_product_meta', 'wdc_woo_add_custom_general_fields_save');
/* Woocommerce End Function */

function wdc_url_get_contents($Url)
{
    if (!function_exists('curl_init'))
    {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function wdc_recaptcha_func()
{
    //check_ajax_referer( 'wdc_nonce', 'security' );
    if (isset($_GET['response']))
    {
        $titan = TitanFramework::getInstance('wdc-options');
        $captcha = $_GET['response'];
        $secret_key = $titan->getOption('recaptcha_secretkey');
        $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        echo $response['body'];
    }

    wp_die();
}

add_action('wp_ajax_wdc_recaptcha', 'wdc_recaptcha_func');
add_action('wp_ajax_nopriv_wdc_recaptcha', 'wdc_recaptcha_func');

include('wdc-options.php');

function wdc_update_whois_func()
{
    $args = array(
        'user-agent' => 'WordPress-WP-Domain-Checker'
    );
    check_ajax_referer('wdc_nonce', 'security');
    $whois_file = plugin_dir_path(__FILE__) . 'lib/whois.json';
    $version_file = plugin_dir_path(__FILE__) . 'lib/version.txt';
    $whois_json = wp_remote_get('http://api.asdqwe.net/wdc/whois.json', $args);
    if (is_wp_error($whois_json))
    {
        echo '503';
        wp_die();
    }
    $server_version = wp_remote_get('http://api.asdqwe.net/wdc/version.txt', $args);
    $whois_json = wp_remote_retrieve_body($whois_json);
    $server_version = wp_remote_retrieve_body($server_version);
    $update_whois = fopen($whois_file, "wa+");
    $update_version = fopen($version_file, "wa+");
    fwrite($update_whois, $whois_json);
    fwrite($update_version, $server_version);
    fclose($update_whois);
    fclose($update_version);

    echo 'ok';
    wp_die();
}
add_action('wp_ajax_nopriv_wdc_update_whois', 'wdc_update_whois_func');
add_action('wp_ajax_wdc_update_whois', 'wdc_update_whois_func');
function wdc_whois_submenu()
{
    add_submenu_page('wp-domain-checker', 'Whois Updater', 'Whois Updater', 'manage_options', 'wdc-whois', 'wdc_whois_option');

}
add_action('admin_menu', 'wdc_whois_submenu');

function wdc_whois_option()
{
?>
   <div class="wrap">
	<h2>
		Whois Updater
	</h2>
	</div>
   <div class='wrap'>
   <div id="wdc-whois-panel">
    <?php echo wdc_check_whois_version(); ?>

   </div>
   </div>
   <?php
}

function wdc_check_whois_version()
{

    if (is_admin())
    {
        $args = array(
            'user-agent' => 'WordPress-WP-Domain-Checker',
            'sslverify' => false
        );

        $version_file = plugin_dir_path(__FILE__) . 'lib/version.txt';

        $server_version = wp_remote_get('http://api.asdqwe.net/wdc/version.txt', $args);
        $server_version = wp_remote_retrieve_body($server_version);

        $local_version_open = fopen(plugin_dir_path(__FILE__) . 'lib/version.txt', 'r');
        $local_version = fread($local_version_open, filesize($version_file));
        fclose($local_version_open);

        $last_update = date("F d Y H:i:s.", filemtime($version_file));

        $out = '<div class="inside">
				<ul>
					<li><strong>Whois version</strong> : <span>' . $local_version . '</span></li>
					<li><strong>Last updated</strong> : <span>' . $last_update . '</span></li>';

        if (!version_compare($local_version, $server_version, '>='))
        {

            $out .= '<li class="update-message notice inline notice-warning notice-alt">
				<p><strong>Whois version ' . $server_version . ' now available!</strong></p>
			</li>
			<li>
				<a class="button" id="whois-update">Update Now</a>
				</li>
				';
        }
        else
        {
            $out .= '<li class="update-message updated-message notice inline notice-success notice-alt">
				<p><strong>You have the latest version!</strong></p>
			</li>
			<li>
				<a class="install-now button" href="#" disabled>Updated</a>
				</li>';
        }

        $out .= '<p class="description"><a href="https://asdqwe.net/get-in-touch/" target="_blank">Report broken TLDs or suggest new TLDs.</a></p></ul>
				</div>';

        return $out;
    }
}

class wdc_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(false, $name = __('WP Domain Checker Widget', 'wdc'));
    }
    function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : __("Domain Availability Check", "wdc") ;
        $width = isset($instance['width']) ? $instance['width'] : "";
        $button = isset($instance['button']) ? $instance['button'] : "";
        $recaptcha = isset($instance['recaptcha']) ? $instance['recaptcha'] : "no";
        $size = isset($instance['size']) ? $instance['size'] : "small";
        $style = isset($instance['style']) ? $instance['style'] : "1";
        $class = isset($instance['class']) ? $instance['class'] : "";

?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wdc'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'wdc'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Button Name:', 'wdc'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo $button; ?>" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('recaptcha'); ?>"><?php _e('reCaptcha:', 'wdc'); ?>
			<select id="<?php echo $this->get_field_id('recaptcha'); ?>" name="<?php echo $this->get_field_name('recaptcha'); ?>">
            <option <?php if ('no' == $recaptcha) echo 'selected="selected"'; ?> value="no">Disable</option>
    		<option <?php if ('yes' == $recaptcha) echo 'selected="selected"'; ?> value="yes">Enable</option>
            </select>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size:', 'wdc'); ?>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
            <option <?php if ('small' == $size) echo 'selected="selected"'; ?> value="small">Small</option>
    		<option <?php if ('large' == $size) echo 'selected="selected"'; ?> value="large">Large</option>
            </select>
		</label>
		</p>
        <p>
		<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', 'wdc'); ?>
			<select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
            <option <?php if ('1' == $style) echo 'selected="selected"'; ?> value="1">1</option>
    		<option <?php if ('2' == $style) echo 'selected="selected"'; ?> value="2">2</option>
            </select>
		</label>
		</p>
        <p>
		<label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Class:', 'wdc'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
		</label>
		</p>
	<?php
    }
    function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['width'] = (!empty($new_instance['width'])) ? strip_tags($new_instance['width']) : '';
        $instance['button'] = (!empty($new_instance['button'])) ? strip_tags($new_instance['button']) : '';
        $instance['recaptcha'] = (!empty($new_instance['recaptcha'])) ? strip_tags($new_instance['recaptcha']) : '';
        $instance['size'] = (!empty($new_instance['size'])) ? strip_tags($new_instance['size']) : '';
        $instance['style'] = (!empty($new_instance['style'])) ? strip_tags($new_instance['style']) : '';
        $instance['class'] = (!empty($new_instance['class'])) ? strip_tags($new_instance['class']) : '';

        return $instance;
    }

    function widget($args, $instance)
    {
        $title = $instance['title'];
        if ($title == '') $title = __('Domain Availability Check', 'wdc');
        $width = $instance['width'];
        if ($width == '') $width = '500';
        $button = $instance['button'];
        if ($button == '') $button = __('Check', 'wdc');
        $recaptcha = $instance['recaptcha'];
        if ($recaptcha == '') $recaptcha = 'no';
        $size = $instance['size'];
        if ($size == '') $size = 'small';
        $style = $instance['style'];
        if ($style == '') $style = '1';
        $class = $instance['class'];
        if ($class == '') $class = '';

        echo $args['before_widget'];

        if ($title)
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo do_shortcode("[wpdomainchecker width='$width' button='$button' recaptcha='$recaptcha' size='$size' style='$style' class='$class']");

        echo $args['after_widget'];
    }
}

function register_wdc_widget()
{
    register_widget('wdc_widget');
}
add_action('widgets_init', 'register_wdc_widget');

function wdc_idn_to_ascii($domain)
{
    if (function_exists('idn_to_ascii'))
    {
        if(defined('IDNA_NONTRANSITIONAL_TO_ASCII') && defined('INTL_IDNA_VARIANT_UTS46')){
            $ascii_domain = idn_to_ascii($domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
        }else{
            $ascii_domain = idn_to_ascii($domain);
        }
    }
    else
    {
        $ascii_domain = $domain;
    }
    return $ascii_domain;
}

function wdc_parse_ini_string_m($str) {

    if(empty($str)) return false;

    $lines = explode("\n", $str);
    $ret = Array();
    $inside_section = false;

    foreach($lines as $line) {

        $line = trim($line);

        if(!$line || $line[0] == "#" || $line[0] == ";") continue;

        if($line[0] == "[" && $endIdx = strpos($line, "]")){
            $inside_section = substr($line, 1, $endIdx-1);
            continue;
        }

        if(!strpos($line, '=')) continue;

        $tmp = explode("=", $line, 2);

        if($inside_section) {

            $key = rtrim($tmp[0]);
            $value = ltrim($tmp[1]);

            if(preg_match("/^\".*\"$/", $value) || preg_match("/^'.*'$/", $value)) {
                $value = mb_substr($value, 1, mb_strlen($value) - 2);
            }

            $t = preg_match("^\[(.*?)\]^", $key, $matches);
            if(!empty($matches) && isset($matches[0])) {

                $arr_name = preg_replace('#\[(.*?)\]#is', '', $key);

                if(!isset($ret[$inside_section][$arr_name]) || !is_array($ret[$inside_section][$arr_name])) {
                    $ret[$inside_section][$arr_name] = array();
                }

                if(isset($matches[1]) && !empty($matches[1])) {
                    $ret[$inside_section][$arr_name][$matches[1]] = $value;
                } else {
                    $ret[$inside_section][$arr_name][] = $value;
                }

            } else {
                $ret[$inside_section][trim($tmp[0])] = $value;
            }

        } else {

            $ret[trim($tmp[0])] = ltrim($tmp[1]);

        }
    }
    return $ret;
}
