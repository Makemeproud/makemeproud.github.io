(function($) {
	'use strict';

	function bb_tab_suffix( target, active, suffix ){
		if(suffix == '') {
			return;
		}

		if(!suffix.match(/\|/ig)) {
			suffix = [suffix];
		} else {
			suffix = suffix.split(/\|/);
		}

		$.each(suffix, function( index, suffix_name ) {
			if(active) {
				$('[data-vc-shortcode-param-name="' + target + suffix_name + '"]').addClass('bb-tab-target-active');
			} else {
				$('[data-vc-shortcode-param-name="' + target + suffix_name + '"]').removeClass('bb-tab-target-active').addClass('bb-tab-target');
			}
		});
	}

	$('document').ready(function(){
		var bb_tab_item = $('a[data-bb-tab-target]');

		if(bb_tab_item.length > 0) {
			bb_tab_item.each(function(index){
				var _self = $(this),
					bb_tabs_container = _self.closest('.bb-tabs-container');

				$('[data-vc-shortcode-param-name="' + _self.data('bb-tab-target') + '"]').addClass('bb-tab-target');
				bb_tab_suffix( _self.data('bb-tab-target'), false, bb_tabs_container.data('suffix') );
				if(bb_tabs_container.data('tab-active') == _self.data('bb-tab-target')) {
					$('[data-vc-shortcode-param-name="' + _self.data('bb-tab-target') + '"]').addClass('bb-tab-target-active');
					bb_tab_suffix( _self.data('bb-tab-target'), true, bb_tabs_container.data('suffix') );
				}
			});

			bb_tab_item.on('click', function(){
				var _self = $(this),
				_parent = _self.closest('.bb-tabs-container');
				_self.closest('li').addClass('active').siblings().removeClass('active');

				_parent.find('a[data-bb-tab-target]').each(function(index){
					$('[data-vc-shortcode-param-name="' + $(this).data('bb-tab-target') + '"]').removeClass('bb-tab-target-active');
					bb_tab_suffix( $(this).data('bb-tab-target'), false, _parent.data('suffix') );
				});
				$('[data-vc-shortcode-param-name="'+ _self.data('bb-tab-target') +'"]').addClass('bb-tab-target-active');
				bb_tab_suffix( _self.data('bb-tab-target'), true, _parent.data('suffix') );

				return false;
			});
		}
	});
}(window.jQuery));
