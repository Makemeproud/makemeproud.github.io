(function($) {
	'use strict';
	
	$( document ).ready( function( $ ) {
		$('.bb-toggle .checkbox').on('change', function(e){
			var _self = $(this).closest('.bb-toggle');
			if($(this).is(':checked')) {
				_self.find('.bb-value').val('yes');
			} else {
				_self.find('.bb-value').val('no');
			}
			$('[name="'+_self.find('.bb-value').attr('name')+'"]').trigger('change');
		});
	});
	
}(window.jQuery));
