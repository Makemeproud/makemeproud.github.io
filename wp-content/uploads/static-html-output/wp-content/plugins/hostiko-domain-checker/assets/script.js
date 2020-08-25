jQuery(document).ready(function() {
    var form = jQuery('[id="wdc-slider"]');
    if (jQuery(form).length > 0) {
        jQuery.each(form, function(x, y) {
            //console.log(jQuery(y).width());
            if (jQuery(y).width() < 500) {
                jQuery(y).addClass('wdc-resp');
            }
        });
    }

});

jQuery(document).ready(function() {

    jQuery("div[id='wdc-domain-form']").on("submit", function() {

        var form = this;
        var z = jQuery(".g-recaptcha-response", form).attr('id');
        jQuery(".req-domain-text", form).hide();
        jQuery(".recaptcha-text", form).hide();

        if (z !== undefined) {
            z = z.match(/\d+/);

            if (z === null) {
                z = 0;
            } else {
                z = z[0];
            }
        }

        if (jQuery("input[name='domain']", form).val() === "") {
            jQuery(".req-domain-text", form).css( "display", "block" );
            return false;
        }

        var enable = jQuery('.g-recaptcha', form).length;
        var invis = jQuery("div[id='wdc-recaptcha']", form).attr('data-size');
        if (enable > 0 && invis === 'invisible' && grecaptcha.getResponse(z) === '') {

            grecaptcha.execute(z);
            return false;
        } else if (enable > 0) {
            var action = 'wdc_recaptcha';
            var recaptcha_response = jQuery("textarea[name='g-recaptcha-response']", form).val(); //eval('grecaptcha.getResponse(wdcs.' + z + ');');
            if (recaptcha_response === '') {
                jQuery(".recaptcha-text", form).show();

                return false;
            }

            var recaptcha_data = {
                'action': action,
                'response': recaptcha_response,
                'security': wdc_ajax.wdc_nonce
            };
            jQuery("div[id='results']", form).hide();

            jQuery.ajax({
                type: "GET",
                url: wdc_ajax.ajaxurl,
                action: action,
                data: recaptcha_data,
                success: function(response_y) {

                    var y = JSON.parse(response_y);

                    if (y.success === false && y["error-codes"] === '') {
                        jQuery("div[id='loading']", form).hide();
                        jQuery(".recaptcha-text", form).show();

                        //jQuery("div[id='results']", form).html(unescape('<div class="callout callout-warning alert-warning clearfix"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-remove" style="margin-right:10px;"></i> ' + wdc_script.recaptcha_text + '</div></div>'));
                        return eval(grecaptcha.reset(z));
                    } else if (y.success === false && y["error-codes"] == 'missing-input-secret') {
                        jQuery("div[id='loading']", form).hide();
                        jQuery("div[id='results']", form).html(unescape('<p class="not-available">The secret parameter is missing.</p>'));

                        return eval(grecaptcha.reset(z));
                    } else if (y.success === false && y["error-codes"] == 'invalid-input-secret') {
                        jQuery("div[id='loading']", form).hide();
                        jQuery("div[id='results']", form).html(unescape('<p class="not-available">The secret parameter is invalid or malformed.</p>'));
                        return eval(grecaptcha.reset(z));
                    } else if (y.success === false && y["error-codes"] == 'missing-input-response') {
                        jQuery("div[id='loading']", form).hide();
                        jQuery("div[id='results']", form).html(unescape('<p class="not-available">The response parameter is missing.</p>'));

                        return eval(grecaptcha.reset(z));
                    } else if (y.success === false && y["error-codes"] == 'invalid-input-response') {
                        jQuery("div[id='loading']", form).hide();
                        jQuery("div[id='results']", form).html(unescape('<p class="not-available">The response parameter is invalid or malformed.</p>'));

                        return eval(grecaptcha.reset(z));
                    }

                    return wdc_ajax_check_domain(form);
                }
            });
        } else {
            wdc_ajax_check_domain(form);
        }

       // jQuery("div[class='wdc-container mt-5 mb-5']", form).show('slow');

        //console.log(domain)
        jQuery("div[id='loading']", form).show();
        jQuery("div[id='results']", form).hide().html('');

        if (enable > 0) {
            eval(grecaptcha.reset(z));
        }
        return false;
    });
    return false;
});


function wdcInvisrecaptchaCallback(token) {

    var responses = document.getElementsByClassName('g-recaptcha-response');
		var form;

    for (var i = 0; i <= responses.length; i++) {
        if (responses[i].value === token) {
            form = responses[i];
            break;
        }
    }
    form = jQuery(form).closest(".wdc-form");//form.parentNode.parentNode.parentNode.parentNode;
    var z = jQuery(".g-recaptcha-response", form).attr('id');


    if (z !== undefined) {
        z = z.match(/\d+/);

        if (z === null) {
            z = 0;
        } else {
            z = z[0];
        }
    }
    //console.log(z);

    wdc_ajax_check_domain(form);

    return grecaptcha.reset(z);
}



function wdc_ajax_check_domain(form) {
    var style = jQuery(form).data("wdc-style");
    // jQuery("div[class='wdc-container mt-5 mb-5']", form).show('slow');
    var domain = jQuery("input[name='domain']", form).val();
    var item_id = jQuery("input[name='item_id']", form).val();
    var url = jQuery("input[name='url']", form).val();
    var allowed_tld = jQuery("input[name='allowed_tld']", form).val();
    var integration = jQuery("input[name='integration']", form).val();
    var available_msg = jQuery("input[name='available_msg']", form).val();
    var unavailable_msg = jQuery("input[name='unavailable_msg']", form).val();
    var whois_button = jQuery("input[name='whois_button']", form).val();
    var custom_price = jQuery("input[name='custom_price']", form).val();
    var tld = jQuery("input[name='tld']", form).val();

    if(url.length > 0) {
        item_id = url;
    }
    jQuery("div[class='wdc-heading']", form).show('slow');
    jQuery("span[class='wdc-heading-query']", form).text(jQuery("input[name='domain']", form).val());
    
    jQuery("div[id='results']", form).hide().html('');
    jQuery("div[id='loading']", form).show();
    jQuery("#Submit", form).prop("disabled", true);
    var data = {
        'action': 'wdc_check_domain',
        'domain': domain,
        'item_id': item_id,
        'allowed_tld': allowed_tld,
        'tld': tld,
        'security': wdc_ajax.wdc_nonce
    };

    jQuery.post(wdc_ajax.ajaxurl, data, function(response) {
        response = response.data;
        jQuery("#Submit", form).removeAttr('disabled');
        jQuery.each(response.ascii_tld, function(i, data) {

            var x = data;
            var pre_result = '<div id="wdc-tld-' + x + '" class="wdc-domain-result__list"></div>';
            jQuery("div[id='results']", form).append(pre_result).show('slow');
            domain = response.domain + '.' + data;
            idn = response.idn + '.' + response.tld[i];
            var data = {
                'action': 'wdc_display',
                'domain': domain,
                'idn': idn,
                'item_id': item_id,
                'allowed_tld': allowed_tld,
                'tld': tld,
                'punny_tld': response.tld[i],
                'security': wdc_ajax.wdc_nonce,
                'integration': integration,
                'available_msg': available_msg,
                'unavailable_msg': unavailable_msg,
                'whois_button': whois_button,
                'custom_price': custom_price
            };

            jQuery.post(wdc_ajax.ajaxurl, data, function(r) {
                response = r.data;
                var tld = response.tld;
                var result_text;
                result_text = wdcTemplate(response, style);
                jQuery("div[id='results'] div[id='wdc-tld-" + tld + "']", form).show('slow').append(result_text);
            }).always(function() {
                jQuery("div[id='loading']", form).hide();
                jQuery("div[class='wdc-container mt-5 mb-5']", form).show('slow');
            });

        });


    }).fail(function() {
    console.log( "wdc error" );
  });

}

function wdc_add_to_cart_whmpress(el, domain) {
    var button = jQuery(el);
    button.prop('disabled', true);
    button.html("<i class='fa fa-spinner fa-spin '></i>");
    var data = {
        'action': 'whcom_op',
        'whcom_op_what': 'add_remove_domain_whmp',
        'cart_index': -1,
        'domain_action': 'add_domain',
        'domain': domain,
        'domaintype': 'register'
    };
    jQuery.post(wdc_ajax.ajaxurl, data, function(response) {
        response = JSON.parse(response);
        if (response.status === "OK") {
            button.text(button.data('add-to-cart-msg'));
        } else {
            button.text(button.data('error-msg'));
        }
    }).fail(function() {
        button.text('Error');
    });
}

'use strict';

function wdcTemplate(response, style) {

	if (style === 1 || style > 2) {
		if (response.status === 0) {
			result_text = '<div protocol="' + response.protocol + '" class="callout callout-danger alert-danger clearfix not-available"><div class="col-xs-6" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-remove" style="margin-right:1px;"></i> ' + response.result_text + '</div><div class="col-xs-6" style="padding-right:1px;text-align: right;"><a data-domain="' + response.domain + '" href="' + response.additional_button.url + '" target="' + (response.additional_button.newtab ? '_blank' : '_self') + '"><button id="whois" class="'+response.additional_button.class+' btn btn-danger btn-xs pull-right whois-btn">' + response.additional_button.text + '</button></a></div></div>';
		} else if (response.status === 1) {
			result_text = '<div protocol="' + response.protocol + '" class="callout callout-success alert-success clearfix available"><div class="col-xs-6" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-ok" style="margin-right:1px;"></i> ' + response.result_text + '</div><div class="col-xs-6" style="padding-right:1px;text-align: right;"><span style="padding-right: 10px;">' + response.additional_button.price + '</span>' + response.whmcs + '<a href="' + response.additional_button.url + '" data-product_id="' + response.additional_button.product_id + '" data-domain="' + response.domain + '" id="buy" target="' + (response.additional_button.newtab ? '_blank' : '_self') + '" class="'+(response.additional_button.text.length ? '' : 'icon-only') +' btn btn-success btn-xs pull-right order-btn ' + response.additional_button.class + '" ' + response.additional_button.attribute + '>'+wdc_config_js.buy_button_icon+'' + response.additional_button.text + '</a></div></div>';
		} else if (response.status === 2) {
			result_text = '<div protocol="' + response.protocol + '" class="callout callout-warning alert-warning clearfix notfound"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-exclamation-sign" style="margin-right:1px;"></i> ' + response.result_text + '</div></div>';
		} else if (response.status === 3) {
			result_text = '<div class="callout callout-warning alert-warning clearfix"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-exclamation-sign" style="margin-right:1px;"></i> ' + response.result_text + '</div></div>';
		}
	} else if (style === 2) {
		var sld = response.domain.split('.');

		if (response.status === 0) {
			result_text = '<div name="button" class="wdc-domain-result__button"><div protocol="' + response.protocol + '" class="row wdc-grid">    <div class="col-md-6 col-lg-6 wdc-grid-item text-left"><span class="wdc-domain-name">' + sld[0] + '.<strong>' + response.tld + '</strong></span>    </div>    <div class="col-md-6 col-lg-6 wdc-grid-item wdc-grid-price text-right"><span class="wdc-domain-price">' + response.result_text + '</span> <a href="' + response.additional_button.url + '" target="' + (response.additional_button.newtab ? '_blank' : '_self') + '" class="'+response.additional_button.class+' wdc-domain-unavailable"><button type="button" class="btn">' + response.additional_button.text + '</button></a>    </div></div></div>';
		} else if (response.status === 1) {
			result_text = '<div name="button" class="wdc-domain-result__button"><div protocol="' + response.protocol + '" class="row wdc-grid">    <div class="col-md-6 col-lg-6 wdc-grid-item text-left"><span class="wdc-domain-name">' + sld[0] + '.<strong>' + response.tld + '</strong></span>    </div>    <div class="col-md-6 col-lg-6 wdc-grid-item wdc-grid-price text-right">      <span class="wdc-domain-price">' + response.result_text + ' ' + response.additional_button.price + '</span>' + response.whmcs + '      <a href="' + response.additional_button.url + '" data-product_id="' + response.additional_button.product_id + '" data-domain="' + response.domain + '" class="'+(response.additional_button.text.length ? '' : 'icon-only') + ' wdc-domain-available ' + response.additional_button.class + '" ' + response.additional_button.attribute + ' target="' + (response.additional_button.newtab ? '_blank' : '_self') + '"><button type="button" class="btn">'+wdc_config_js.buy_button_icon+'<span>' + response.additional_button.text + '</span></button></a>    </div></div></div>';
		} else if (response.status === 2) {
			result_text = '<div name="button" class="wdc-domain-result__button"><div protocol="' + response.protocol + '" class="row wdc-grid">    <div class="col-md-8 col-lg-8 wdc-grid-item text-left"><span class="wdc-domain-name">' + response.result_text + '</span>    </div>   <!-- <div class="col-md-6 col-lg-6 wdc-grid-item text-right"><span class="wdc-domain-price">' + response.result_text + '</span>    </div> --></div></div>';
		} else if (response.status === 3) {
			result_text = '<div name="button" class="wdc-domain-result__button"><div protocol="' + response.protocol + '" class="row wdc-grid">    <div class="col-md-8 col-lg-8 wdc-grid-item text-left"><span class="wdc-domain-name">' + response.result_text + '</span>    </div>   <!-- <div class="col-md-6 col-lg-6 wdc-grid-item text-right"><span class="wdc-domain-price">' + response.result_text + '</span>    </div>--></div></div>';
		}
	}
	return result_text;
}

jQuery("input[id*='Search']").each(function() {
    jQuery(this).keypress(function(e) {
        if (e.keyCode === 8) {
            return true;
        }
        var regex = new RegExp("^[\x7f-\xffa-z\u0400-\u04FFA-Z0-9\-\.]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        if (e.which == 13) {
            jQuery(this).submit();
            return false;
        }

        e.preventDefault();
        return false;
    });
    jQuery(this).bind('change', function(){
      jQuery(this).val(function(_, v){
       return v.replace(/[\s+!@#$%^&*()_+=\[\]\{\}\;\'\\\:\"\|\,\/\<\?\“\>]/g, '');
      });
    });
});
