function wdcInvisrecaptchaCallback(token){

var responses = document.getElementsByClassName('g-recaptcha-response');

for(var i = 0; i <= responses.length; i++){
	if(responses[i].value === token){
		var form = responses[i];
		break;
	}
}

form = form.parentNode.parentNode.parentNode.parentNode;
	var domain = jQuery("input[name='domain']",form).val();
	var item_id = jQuery("input[name='item_id']",form).val();
	var tld = jQuery("input[name='tld']",form).val();
	var z = jQuery(".g-recaptcha-response",form).attr('id');

	if(z !== undefined)
	{
	  z = z.match(/\d+/)

		if(z === null){
			z = 0;
		}else{
		  z = z[0];
		}
	}
	console.log(z);

	wdc_ajax_check_domain(form,domain,item_id,tld);
	grecaptcha.reset(z);
	return false;
}

jQuery(document).ready(function() {

	var loading;
	var results;
	var display;
	var action;


		jQuery("div[id='wdc-domain-form']").on("submit", function(){

		var form = this;
		var domain = jQuery("input[name='domain']",form).val();
		var item_id = jQuery("input[name='item_id']",form).val();
		var tld = jQuery("input[name='tld']",form).val();
		var z = jQuery(".g-recaptcha-response",form).attr('id');

		if(z !== undefined)
		{
		  z = z.match(/\d+/)

			if(z === null){
				z = 0;
			}else{
			  z = z[0];
			}
		}


			if(jQuery("input[name='domain']",form).val() === "")
				{
					//alert('please enter your domain');
					jQuery("div[id='results']",form).html(unescape('<div class="callout callout-warning alert-warning clearfix"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-remove" style="margin-right:10px;"></i> '+wdc_script.req_domain_text+'</div></div>'));

					return false;
				}

				var enable = jQuery('.g-recaptcha', form).length;
				var invis = jQuery("div[id='wdc-recaptcha']",form).attr('data-size');
				if(enable > 0 && invis === 'invisible' && grecaptcha.getResponse(z) === ''){

					grecaptcha.execute(z);
					return false;

			}else	if(enable > 0){
					action = 'wdc_recaptcha';
					var recaptcha_response = jQuery("textarea[name='g-recaptcha-response']",form).val();//eval('grecaptcha.getResponse(wdcs.' + z + ');');
					if(recaptcha_response === ''){
					jQuery("div[id='results']",form).html(unescape('<div class="callout callout-warning alert-warning clearfix"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-remove" style="margin-right:10px;"></i> '+wdc_script.recaptcha_text+'</div></div>'));

					return false;
					}

				var recaptcha_data = {
		      		'action': action,
		      		'response': recaptcha_response,
		      		'security' : wdc_ajax.wdc_nonce
		    		};
				jQuery("div[id='results']",form).css('display','none');
				jQuery("div[id='loading']",form).css('display','inline');

				jQuery.ajax({
				type: "GET",
				url: wdc_ajax.ajaxurl,
				action: action,
				data: recaptcha_data,
				success: function(response_y){

				var y = JSON.parse(response_y);

				if(y.success === false && y["error-codes"] === ''){
					jQuery("div[id='loading']",form).css('display','none');
					jQuery("div[id='results']",form).html(unescape('<div class="callout callout-warning alert-warning clearfix"><div class="col-xs-10" style="padding-left:1px;text-align:left;"><i class="glyphicon glyphicon-remove" style="margin-right:10px;"></i> '+wdc_script.recaptcha_text+'</div></div>'));
					eval(grecaptcha.reset(z));
					return false;
				}else if(y.success === false && y["error-codes"] == 'missing-input-secret'){
					jQuery("div[id='loading']",form).css('display','none');
					jQuery("div[id='results']",form).html(unescape('<p class="not-available">The secret parameter is missing.</p>'));
					eval(grecaptcha.reset(z));
					return false;
				}else if(y.success === false && y["error-codes"] == 'invalid-input-secret'){
					jQuery("div[id='loading']",form).css('display','none');
					jQuery("div[id='results']",form).html(unescape('<p class="not-available">The secret parameter is invalid or malformed.</p>'));
					eval(grecaptcha.reset(z));
					return false;
				}else if(y.success === false && y["error-codes"] == 'missing-input-response'){
					jQuery("div[id='loading']",form).css('display','none');
					jQuery("div[id='results']",form).html(unescape('<p class="not-available">The response parameter is missing.</p>'));
					eval(grecaptcha.reset(z));
					return false;
				}else if(y.success === false && y["error-codes"] == 'invalid-input-response'){
					jQuery("div[id='loading']",form).css('display','none');
					jQuery("div[id='results']",form).html(unescape('<p class="not-available">The response parameter is invalid or malformed.</p>'));
					eval(grecaptcha.reset(z));
					return false;
				}

				wdc_ajax_check_domain(form,domain,item_id,tld);

				return false;
				}
				});
		}else{

				wdc_ajax_check_domain(form,domain,item_id,tld);
			}
			if(enable > 0){
			eval(grecaptcha.reset(z));
			}
				return false;
	});
	return false;
});

jQuery('#Search').keypress(function (e) {
	if(e.keyCode === 8){
   	return true;
   	}
    var regex = new RegExp("^[\x7f-\xffa-zA-Z0-9\-\.]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
     if(e.which == 13) {
     	jQuery(this).submit();
     	return false;
     }

    e.preventDefault();
    return false;
});

function wdc_ajax_check_domain(form,domain,item_id,tld){
	jQuery("div[id='results']",form).css('display','none').html('');
				jQuery("div[id='loading']",form).css('display','inline');
				jQuery("#Submit",form).prop("disabled", true);
				var data = {
			      		'action': 'wdc_check_domain',
			      		'domain': domain,
			      		'item_id': item_id,
			      		'tld': tld,
			      		'security' : wdc_ajax.wdc_nonce
			    		};

				jQuery.post(wdc_ajax.ajaxurl, data, function(response) {
				response = jQuery.parseJSON(response);
				 jQuery("#Submit",form).removeAttr('disabled');
				 jQuery.each(response.tld, function(i, data) {

				  domain = response.domain+'.'+data;
				  idn = response.idn+'.'+data;
				  var data = {
			      		'action': 'wdc_display',
			      		'domain': domain,
			      		'idn'	: idn,
			      		'item_id': item_id,
			      		'tld': tld,
			      		'security' : wdc_ajax.wdc_nonce
			    		};

				jQuery.post(wdc_ajax.ajaxurl, data, function(response) {
				jQuery("div[id='loading']",form).css('display','inline');
				jQuery("div[id='results']",form).css('display','block').append(unescape(response));

				}).always(function() {
				     jQuery("div[id='loading']",form).css('display','none');
				});

				});


				});

}
