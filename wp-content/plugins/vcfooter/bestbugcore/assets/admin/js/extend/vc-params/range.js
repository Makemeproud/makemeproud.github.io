(function($) {
	'use strict';
	
	$('.bb-range-slider .wpb_vc_param_value').on('input', function(e){
		var _self = $(this);
		_self.next('span').text(_self.val());
	});
	
}(window.jQuery));
