<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function wdc_options()
{
    $titan = TitanFramework::getInstance('wdc-options');
    $panel = $titan->createAdminPanel(array(
        'name' => 'WP Domain Checker',
    ));

    $generaltab = $panel->createTab(array(
        'name' => 'General',
    ));

    $generaltab->createOption(array(
        'name' => __('Custom Available Result Text', 'wdc') ,
        'id' => 'custom_found_result_text',
        'type' => 'textarea',
        'desc' => __('This is custom available result text. Available template tag {domain},{sld},{tld} <br><strong>e.g: example.com: {domain} = example.com, {sld} = example, {tld} = com</strong>', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Custom Unvailable Result Text', 'wdc') ,
        'id' => 'custom_not_found_result_text',
        'type' => 'textarea',
        'desc' => __('This is custom not available result text. Available template tag {domain},{sld},{tld} <br><strong>e.g: example.com: {domain} = example.com, {sld} = example, {tld} = com</strong>', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Input Placeholder', 'wdc') ,
        'id' => 'input_placeholder',
        'type' => 'text',
        'desc' => __('Placeholder for domain search input.', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Loading Image', 'wdc') ,
        'id' => 'loading_image',
        'type' => 'upload',
        'desc' => __('Upload your image', 'wdc')
    ));

    $pages = get_pages(array(
        'post_type' => 'page',
        'post_status' => 'publish'
    ));
    foreach ($pages as $page)
    {
        $whois_page['disable'] = 'Disable';
        $whois_page['custom'] = 'Custom URL';
        $whois_page[$page
            ->ID] = $page->post_title;
    }
    $generaltab->createOption(array(
        'name' => __('Whois Page', 'wdc') ,
        'id' => 'whois_option',
        'type' => 'select',
        'options' => $whois_page,
        'desc' => __('Enable or disable whois button if domain not available, make sure you put shortcode <strong>[wpdomainwhois]</strong> in whois page.', 'wdc') ,
        'default' => 'disable',
    ));

    $generaltab->createOption(array(
        'name' => __('Whois Button Text', 'wdc') ,
        'id' => 'whois_button_text',
        'type' => 'text',
        'desc' => __('Default: Whois', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Whois Custom URL', 'wdc') ,
        'id' => 'whois_custom_url',
        'type' => 'text',
        'desc' => __('Use custom URL instead whois page. Available template tag {domain},{sld},{tld} ', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Open in new tab ?', 'wdc') ,
        'id' => '_blank1_option',
        'type' => 'checkbox',
        'desc' => __('Open the Whois Link in new tab.', 'wdc') ,
        'default' => false,
    ));

    $generaltab->createOption(array(
        'name' => __('Integration', 'wdc') ,
        'id' => 'integration',
        'type' => 'select',
        'options' => array(
            'disable' => 'Disable',
            'whmcs' => 'WHMCS',
            'whmcs_bridge' => 'WHMCS Bridge',
            'whm_press' => 'WHMPRESS',
            'woocommerce' => 'Woocommerce',
            'custom' => 'Custom URL',
        ) ,
        'desc' => __('Enable or disable integration.', 'wdc') ,
        'default' => 'disable',
    ));

    $generaltab->createOption(array(
        'name' => __('Integration Button Link', 'wdc') ,
        'id' => 'additional_button_link',
        'type' => 'text',
        'desc' => __('Integration button link. (e.g. for WHMCS: "http://billing.host.com"). <a href="http://asdqwe.net/wordpress-plugins/wp-domain-checker-docs/" target="_blank">Documentation</a><br>
    			For custom link, you can use template tag {domain} to include domain in the link. <br>e.g: http://godaddy.com/?aff=12345&domain={domain} <br>Available template tag {domain},{sld},{tld}', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Enable AJAX add to cart buttons', 'wdc') ,
        'id' => 'ajax_option',
        'type' => 'checkbox',
        'desc' => __('Enable AJAX add to cart buttons on WooCommerce integration. <br>Make sure you enable AJAX add to cart buttons in <strong>WooCommerce settings -> Product -> Display</strong>.', 'wdc') ,
        'default' => false,
    ));

    $generaltab->createOption(array(
        'name' => __('Show Price?', 'wdc') ,
        'id' => 'show_price',
        'type' => 'checkbox',
        'default' => false,
        'desc' => '<em>' . __('WooCommerce Integration Only.', 'wdc') . '</em>'
    ));

    $generaltab->createOption(array(
        'name' => __('Buy Button Text', 'wdc') ,
        'id' => 'additional_button_name',
        'type' => 'text',
        'desc' => __('Integration Button Text. (e.g.: "ORDER NOW")', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Buy Button Icon', 'wdc') ,
        'id' => 'additional_button_icon',
        'type' => 'text',
        'desc' => __('e.g: "cart-plus" (<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">Font Awesome</a>)', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Open in new tab ?', 'wdc') ,
        'id' => '_blank2_option',
        'type' => 'checkbox',
        'desc' => __('Open the Integration Link in new tab.', 'wdc') ,
        'default' => false,
    ));

    $generaltab->createOption(array(
        'name' => __('Supported TLD Extensions', 'wdc') ,
        'id' => 'extensions',
        'type' => 'textarea',
        'desc' => __('Allow only specific extensions to be check. Separate by comma without dot. (e.g: com,net,org,co.uk,co.id)<br>Leave it blank to allow all extensions.', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Unsupported TLD Extensions Message', 'wdc') ,
        'id' => 'ext_message',
        'type' => 'text',
        'desc' => __('Not Supported TLD Extensions Messages. (e.g.: "Sorry, we currently do not handle that particular tld.")', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('WooCommerce Custom Price', 'wdc') ,
        'id' => 'wdc_custom_price',
        'type' => 'textarea',
        'desc' => __('Allow custom price for specific tld. (e.g: com|9,net|10,org|11,co.uk|12,co.id|13)', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Multiple TLDs Check', 'wdc') ,
        'id' => 'wdc_multi_tlds_check',
        'type' => 'checkbox',
        'desc' => __('Enable', 'wdc') ,
        'default' => false,
    ));

    $generaltab->createOption(array(
        'id' => 'wdc_multi_tlds',
        'type' => 'textarea',
        'desc' => __('Multiple TLDs (e.g: com,net,org,info)', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Require Domain Text', 'wdc') ,
        'id' => 'req_domain_text',
        'type' => 'text',
        'desc' => __(' e.g.: "Please enter your domain name."', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Require reCaptcha Text', 'wdc') ,
        'id' => 'req_recaptcha_text',
        'type' => 'text',
        'desc' => __(' e.g.: "Please verify your are not robot."', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Whois Server Not Found Text', 'wdc') ,
        'id' => 'whois_notfound_text',
        'type' => 'text',
        'desc' => __(' e.g.: "WHOIS server not found for .{tld} TLD"', 'wdc')
    ));

    $generaltab->createOption(array(
        'name' => __('Available Domain Cache Time', 'wdc') ,
        'id' => 'available_domain_cache',
        'type' => 'number',
        'min' => 0,
        'max' => 604800,
        'desc' => __('Cache the available domain result in X seconds (3600 = 1 Hour / 86400 = 1 Day). 0 means cache disabled.', 'wdc'),
        'default' => 0,
        'unit' => 'seconds'

    ));

    $generaltab->createOption(array(
        'name' => __('Unavailable Domain Cache Time', 'wdc') ,
        'id' => 'unavailable_domain_cache',
        'type' => 'number',
        'min' => 0,
        'max' => 604800,
        'slider' => false,
        'desc' => __('Cache the unavailable domain result in X seconds (3600 = 1 Hour / 86400 = 1 Day). 0 means cache disabled.', 'wdc'),
        'default' => 0,
        'unit' => 'seconds'
    ));

    $recaptchaTab = $panel->createTab(array(
        'name' => 'reCaptcha',
    ));

    $recaptchaTab->createOption(array(
        'name' => __('Google reCAPTCHA v2 Site Key', 'wdc') ,
        'id' => 'recaptcha_sitekey',
        'type' => 'text',
        'desc' => __('Your reCaptcha Site Key. <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"> Get reCaptcha Key</a>', 'wdc')
    ));

    $recaptchaTab->createOption(array(
        'name' => __('Google reCAPTCHA v2 Secret Key', 'wdc') ,
        'id' => 'recaptcha_secretkey',
        'type' => 'text',
        'desc' => __('Your reCaptcha Secret Key.', 'wdc')
    ));

    $recaptchaTab->createOption(array(
        'name' => __('Invisible Google reCAPTCHA', 'wdc') ,
        'id' => 'recaptcha_invis',
        'type' => 'checkbox'
    ));

    $wdc_styles = $panel->createTab(array(
        'name' => 'Styles',
    ));

    $wdc_styles->createOption( array(
        'name' => 'Style #1',
        'type' => 'heading',
    ) );


    $wdc_styles->createOption(array(
        'name' => __('Check Button Color', 'wdc') ,
        'id' => 'check_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#5bc0de',
        'css' => '#wdc-style .btn-info { background-color: value !important;border-color:value! important; } #wdc-style input:focus {border-color:value !important}'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Check Button Text Color', 'wdc') ,
        'id' => 'check_button_text_color',
        'alpha' => true,
        'type' => 'color',
        'default' => '#fff',
        'css' => '#wdc-style .btn-info { color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Purchase Button Color', 'wdc') ,
        'id' => 'order_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#5cb85c',
        'css' => '#wdc-style #results .order-btn, #wdc-style #results .order-btn:hover { background-color: value; border-color: value; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Purchase Icon Color', 'wdc') ,
        'id' => 'style_1_purchase_icon_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fff',
        'css' => '#wdc-style .wdc-domain-result__list .icon-only {color: value;} #wdc-style .wdc-domain-result__list .icon-only i {color: value;}'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Purchase Button Text Color', 'wdc') ,
        'id' => 'order_button_text_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fff',
        'css' => '#wdc-style .order-btn { color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Whois Button Color', 'wdc') ,
        'id' => 'whois_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#d9534f',
        'css' => '#wdc-style .whois-btn { background-color: value !important;border-color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Whois Button Text Color', 'wdc') ,
        'id' => 'whois_button_text_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fff',
        'css' => '#wdc-style .whois-btn { color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Available Result Background Color', 'wdc') ,
        'id' => 'available_background_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#e7fadf',
        'css' => '#wdc-style .available { background-color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Available Result Border Color', 'wdc') ,
        'id' => 'available_border_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#b9ceab',
        'css' => '#wdc-style .available { border-color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Available Result Text Color', 'wdc') ,
        'id' => 'available_text_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#3c763d',
        'css' => '#wdc-style .available { color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Unavailable Background Color', 'wdc') ,
        'id' => 'not_available_background_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fcf2f2',
        'css' => '#wdc-style .not-available { background-color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Unavailable Border Color', 'wdc') ,
        'id' => 'not_available_border_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#dFb5b4',
        'css' => '#wdc-style .not-available { border-color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Unavailable Result Text Color', 'wdc') ,
        'id' => 'not_available_text_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#a94442',
        'css' => '#wdc-style .not-available { color: value !important; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Search Box Border Color', 'wdc') ,
        'id' => 'search_box_border_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fff',
        'css' => '#wdc-style .input-group { border: 1px solid value; }'
    ));

    $wdc_styles->createOption( array(
        'name' => 'Style #2',
        'type' => 'heading',
    ) );


    $wdc_styles->createOption(array(
        'name' => __('Check Button Color', 'wdc') ,
        'id' => 'style_2_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#29498d',
        'css' => '.wdc-search-one .wdc-button,.wdc-search-one .wdc-button:hover,.wdc-search-one .wdc-button:focus { background: value; } .wdc-search-one .wdc-spinner {color: value} .wdc-search-one .wdc-spinner:before {border: 3px solid value;border-top-color: transparent;}'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Check Box Border Color', 'wdc') ,
        'id' => 'style_2_search_box_border_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#29498d',
        'css' => '.wdc-search-one .wdc-search-form .wdc-search-content .wdc-search-input__field .wdc-search-input__label .wdc-input:focus {box-shadow: 0 0 0 2px value}'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Purchase Button Color', 'wdc') ,
        'id' => 'style_2_purchase_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#29498d',
        'css' => '.wdc-search-one .wdc-domain-available button { background-color: value; }'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Purchase Icon Color', 'wdc') ,
        'id' => 'style_2_purchase_icon_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#fff',
        'css' => '.wdc-search-one .wdc-icon-result, .wdc-search-one #results .added_to_cart { color: value; } .wdc-search-one .wdc-domain-result__button i {color: value;} .wdc-search-one .wdc-domain-result__button .icon-only i {color: value;}'
    ));

    $wdc_styles->createOption(array(
        'name' => __('Whois Button Color', 'wdc') ,
        'id' => 'style_2_whois_button_color',
        'type' => 'color',
        'alpha' => true,
        'default' => '#29498d',
        'css' => '.wdc-search-one .wdc-domain-unavailable button,.wdc-search-one .wdc-domain-result__list .wdc-domain-unavailable button:hover { background: value; }'
    ));

    $customCSS = $panel->createTab(array(
        'name' => __('Custom CSS', 'wdc') ,
    ));

    $customCSS->createOption(array(
        'name' => __('Custom CSS', 'wdc') ,
        'id' => 'custom_css',
        'type' => 'code',
        'desc' => __('Put your custom CSS rules here', 'wdc') ,
        'lang' => 'css',
    ));

    $wdc_config = $panel->createTab(array(
        'name' => 'Config',
    ));

    $wdc_config->createOption( array(
        'name' => 'Config',
        'id' => 'wdc_config',
        'type' => 'textarea',
        'desc' => 'Put your additional Config rules here',
        'is_code' => true,
    ) );

    $wdc_config->createOption( array(
        'name' => 'Config Example',
        'type' => 'custom',
        'custom' => '<textarea disabled class="large-text code" name="wdc-options_wdc_config" placeholder="" id="wdc-options_wdc_config" rows="10" cols="50">; This is a sample configuration file

[DomainMinLengthRestrictions]
errmsg = "Minimum length of a .{tld} domain name is {length} characters."
com = 3
id = 4
nl = 3
co.uk = 3
;all = 3    ;Restrict all TLDs to 3 characters minimum of length.

;Custom Whois Server, you can use web based (http/https) or whois based server (port 43).
[WhoisServer]
;web based request example
com[uri] = "http://www.whois.server.example/whois.php?domain="
com[string] = "NOT FOUND"

;whois based server port 43 request example
org[uri] = "whois.server.example"
org[string] = "NOT FOUND"

[PriceFormat]
text = "{currency}{price} USD/year"
</textarea>'/*.print_r(parse_ini_string($titan->getOption('wdc_config'),true),true)*/,
        'is_code' => true,
    ) );


    $panel->createOption(array(
        'type' => 'save'
    ));
}

add_action('tf_create_options', 'wdc_options');
