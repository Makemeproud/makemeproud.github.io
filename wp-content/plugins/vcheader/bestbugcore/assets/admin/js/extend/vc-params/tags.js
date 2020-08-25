(function($) {
	'use strict';
/*	
	$(selector).tagsInput({
		'autocomplete_url': url_to_autocomplete_api,
		'autocomplete': { option: value, option: value},
		'height':'100px',
		'width':'300px',
		'interactive':true,
		'defaultText':'add a tag',
		'onAddTag':callback_function,
		'onRemoveTag':callback_function,
		'onChange' : callback_function,
		'delimiter': [',',';'],   // Or a string with a single delimiter. Ex: ';'
		'removeWithBackspace' : true,
		'minChars' : 0,
		'maxChars' : 0, // if not provided there is no limit
		'placeholderColor' : '#666666'
	});
*/
	$('.bb-tags .wpb_vc_param_value').tagsInput({
		'width':'99%'
	});
	
}(window.jQuery));
